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

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use MetaLine\ActiveCampaign\Client;
use PHPUnit\Framework\TestCase;
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

        $this->assertEquals(['foo' => 'baz'], $client->get('test'));
        $this->assertRequest($this->container[0]['request'], 'GET', 'http://example.com/api/3/test');
    }

    /**
     * Tests a API call with POST method.
     */
    public function testPost()
    {
        $client = $this->createClient(new Response(200, [], '{"foo":"baz"}'));

        $this->assertEquals(['foo' => 'baz'], $client->post('test', ['key' => 'value']));
        $this->assertRequest($this->container[0]['request'], 'POST', 'http://example.com/api/3/test', '{"key":"value"}');
    }

    /**
     * Tests a API call with PUT method.
     */
    public function testPut()
    {
        $client = $this->createClient(new Response(200, [], '{"foo":"baz"}'));

        $this->assertEquals(['foo' => 'baz'], $client->put('test', ['key' => 'value']));
        $this->assertRequest($this->container[0]['request'], 'PUT', 'http://example.com/api/3/test', '{"key":"value"}');
    }

    /**
     * Tests a API call with DELETE method.
     */
    public function testDelete()
    {
        $client = $this->createClient(new Response(200, [], '{"foo":"baz"}'));

        $this->assertEquals(['foo' => 'baz'], $client->delete('test'));
        $this->assertRequest($this->container[0]['request'], 'DELETE', 'http://example.com/api/3/test');
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
