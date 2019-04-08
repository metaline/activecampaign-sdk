<?php

/*
 * This file is part of the ActiveCampaign API SDK.
 *
 * (c) Daniele De Nobili <daniele@metaline.it>
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

final class ClientTest extends TestCase
{
    /**
     * @var array
     */
    private $container = [];

    /**
     * @var Client
     */
    private $client;

    protected function setUp()
    {
        $mock = new MockHandler([
            new Response(200, [], '{"foo":"baz"}')
        ]);

        $handler = HandlerStack::create($mock);

        $history = Middleware::history($this->container);
        $handler->push($history);

        $guzzleClient = new GuzzleClient(['handler' => $handler]);
        $this->client = new Client('http://example.com', 'super-secret-token', $guzzleClient);
    }

    public function testGet()
    {
        $this->assertEquals(['foo' => 'baz'], $this->client->get('test'));
        $this->assertRequest($this->container[0]['request'], 'GET', 'http://example.com/api/3/test');
    }

    public function testPost()
    {
        $this->assertEquals(['foo' => 'baz'], $this->client->post('test', ['key' => 'value']));
        $this->assertRequest($this->container[0]['request'], 'POST', 'http://example.com/api/3/test', '{"key":"value"}');
    }

    public function testPut()
    {
        $this->assertEquals(['foo' => 'baz'], $this->client->put('test', ['key' => 'value']));
        $this->assertRequest($this->container[0]['request'], 'PUT', 'http://example.com/api/3/test', '{"key":"value"}');
    }

    public function testDelete()
    {
        $this->assertEquals(['foo' => 'baz'], $this->client->delete('test'));
        $this->assertRequest($this->container[0]['request'], 'DELETE', 'http://example.com/api/3/test');
    }

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
