<?php

/*
 * This file is part of the ActiveCampaign API SDK.
 *
 * (c) Meta Line S.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MetaLine\ActiveCampaign\Tests;

use Exception;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use MetaLine\ActiveCampaign\Client;
use MetaLine\ActiveCampaign\Tests\Fixture\GenericGuzzleException;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use Psr\Http\Message\RequestInterface;

/**
 * @author Daniele De Nobili
 * @covers \MetaLine\ActiveCampaign\Client
 */
final class ClientTest extends TestCase
{
    /**
     * @var array
     */
    private $container = [];

    /**
     * Tests a API call with GET method.
     */
    public function testGet()
    {
        $client = $this->createClient(new Response(200, [], '{"foo":"baz"}'));
        $result = $client->get('test');

        $this->assertTrue($result->isSuccessful());
        $this->assertEquals(['foo' => 'baz'], $result->getData());
        $this->assertEquals([], $result->getErrors());
        $this->assertRequest($this->container[0]['request'], 'GET', 'http://example.com/api/3/test');
    }

    /**
     * Tests a API call with POST method.
     */
    public function testPost()
    {
        $client = $this->createClient(new Response(200, [], '{"foo":"baz"}'));
        $result = $client->post('test', ['key' => 'value']);

        $this->assertTrue($result->isSuccessful());
        $this->assertEquals(['foo' => 'baz'], $result->getData());
        $this->assertEquals([], $result->getErrors());
        $this->assertRequest($this->container[0]['request'], 'POST', 'http://example.com/api/3/test',
            '{"key":"value"}');
    }

    /**
     * Tests a API call with PUT method.
     */
    public function testPut()
    {
        $client = $this->createClient(new Response(200, [], '{"foo":"baz"}'));
        $result = $client->put('test', ['key' => 'value']);

        $this->assertTrue($result->isSuccessful());
        $this->assertEquals(['foo' => 'baz'], $result->getData());
        $this->assertEquals([], $result->getErrors());
        $this->assertRequest($this->container[0]['request'], 'PUT', 'http://example.com/api/3/test', '{"key":"value"}');
    }

    /**
     * Tests a API call with DELETE method.
     */
    public function testDelete()
    {
        $client = $this->createClient(new Response(200, [], '{"foo":"baz"}'));
        $result = $client->delete('test');

        $this->assertTrue($result->isSuccessful());
        $this->assertEquals(['foo' => 'baz'], $result->getData());
        $this->assertEquals([], $result->getErrors());
        $this->assertRequest($this->container[0]['request'], 'DELETE', 'http://example.com/api/3/test');
    }

    /**
     * Tests API calls that returns errors.
     */
    public function testGenericErrors()
    {
        $client = $this->createClient(new Response(400));
        $result = $client->get('test');

        $this->assertFalse($result->isSuccessful());
        $this->assertEquals([], $result->getData());
        $this->assertEquals([], $result->getErrors());
    }

    public function testMessageError()
    {
        $client = $this->createClient(
            new Response(404, [], '{"message":"No Result found for Subscriber with id 18"}')
        );

        $result = $client->get('test');

        $errors = [
            'errors' => [
                [
                    'title' => 'No Result found for Subscriber with id 18',
                ],
            ],
        ];

        $this->assertFalse($result->isSuccessful());
        $this->assertEquals([], $result->getData());
        $this->assertEquals($errors, $result->getErrors());
    }

    /**
     * Tests API calls that returns explained errors.
     *
     * @link https://developers.activecampaign.com/reference#errors
     */
    public function testExplainedErrors()
    {
        $client = $this->createClient(new Response(
            400,
            [],
            '{"errors":[{"title":"The connection service was not provided.","source":{"pointer":"\/data\/attributes\/service"}},{"title":"The connection externalid was not provided.","source":{"pointer":"\/data\/attributes\/externalid"}}]}'
        ));

        $result = $client->get('test');

        $errors = [
            'errors' => [
                [
                    'title'  => 'The connection service was not provided.',
                    'source' => [
                        'pointer' => '/data/attributes/service',
                    ],
                ],
                [
                    'title'  => 'The connection externalid was not provided.',
                    'source' => [
                        'pointer' => '/data/attributes/externalid',
                    ],
                ],
            ],
        ];

        $this->assertFalse($result->isSuccessful());
        $this->assertEquals([], $result->getData());
        $this->assertEquals($errors, $result->getErrors());
    }

    public function testGenericGuzzleException()
    {
        /** @var ClientInterface|PHPUnit_Framework_MockObject_MockObject $guzzleClient */
        $guzzleClient = $this->createMock(ClientInterface::class);

        /** @var GuzzleException|Exception|PHPUnit_Framework_MockObject_MockObject $guzzleException */
        $guzzleException = new GenericGuzzleException('The exception message');

        $client = new Client(
            'http://example.com',
            'super-secret-token',
            $guzzleClient
        );

        $guzzleClient->expects($this->any())->method('request')->willThrowException($guzzleException);
        $result = $client->get('test');

        $errors = [
            'errors' => [
                [
                    'title'  => 'The exception message',
                ],
            ],
        ];

        $this->assertFalse($result->isSuccessful());
        $this->assertEquals([], $result->getData());
        $this->assertEquals($errors, $result->getErrors());
    }

    public function testCorrectCredentials()
    {
        $client = $this->createClient(new Response(200, [], '{"user":{}}'));
        $this->assertTrue($client->testCredentials());
    }

    public function testWrongCredentials()
    {
        $client = $this->createClient(new Response(403));
        $this->assertFalse($client->testCredentials());
    }

    /**
     * @param Response $expectedResponse
     * @return GuzzleClient
     */
    private function createGuzzleClient(Response $expectedResponse)
    {
        $mock = new MockHandler([
            $expectedResponse
        ]);

        $handler = HandlerStack::create($mock);

        $history = Middleware::history($this->container);
        $handler->push($history);

        return new GuzzleClient(['handler' => $handler]);
    }

    /**
     * @param Response $expectedResponse
     * @return Client
     */
    private function createClient(Response $expectedResponse)
    {
        return new Client(
            'http://example.com',
            'super-secret-token',
            $this->createGuzzleClient($expectedResponse)
        );
    }

    /**
     * @param RequestInterface $request
     * @param string           $method
     * @param string           $uri
     * @param string           $body
     */
    private function assertRequest(RequestInterface $request, $method, $uri, $body = null)
    {
        $this->assertEquals($method, $request->getMethod());
        $this->assertEquals($uri, $request->getUri()->__toString());
        $this->assertEquals('super-secret-token', $request->getHeaderLine('Api-Token'));

        if (null !== $body) {
            $this->assertEquals($body, $request->getBody()->__toString());
        }
    }
}
