<?php

/*
 * This file is part of the ActiveCampaign API SDK.
 *
 * (c) Meta Line S.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MetaLine\ActiveCampaign;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientInterface as GuzzleClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;

final class Client
{
    /**
     * @var string
     */
    private $apiUrl;

    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var GuzzleClientInterface
     */
    private $guzzleClient;

    /**
     * @param string            $apiUrl
     * @param string            $apiKey
     * @param GuzzleClientInterface|null $guzzleClient
     */
    public function __construct($apiUrl, $apiKey, GuzzleClientInterface $guzzleClient = null)
    {
        $this->apiUrl = $apiUrl . '/api/3/';
        $this->apiKey = $apiKey;
        $this->guzzleClient = $guzzleClient ?: new GuzzleClient();
    }

    /**
     * @param string $path
     * @return array
     * @throws GuzzleException
     */
    public function get($path)
    {
        return $this->sendRequest('GET', $path);
    }

    /**
     * @param string $path
     * @param array  $body
     * @return array
     * @throws GuzzleException
     */
    public function post($path, array $body)
    {
        return $this->sendRequest('POST', $path, $body);
    }

    /**
     * @param string $path
     * @param array  $body
     * @return array
     * @throws GuzzleException
     */
    public function put($path, array $body)
    {
        return $this->sendRequest('PUT', $path, $body);
    }

    /**
     * @param string $path
     * @return array
     * @throws GuzzleException
     */
    public function delete($path)
    {
        return $this->sendRequest('DELETE', $path);
    }

    /**
     * @param string $method
     * @param string $path
     * @param array  $data
     * @return array
     * @throws GuzzleException
     */
    private function sendRequest($method, $path, array $data = null)
    {
        $uri = $this->apiUrl . $path;

        $requestOptions = [
            RequestOptions::HEADERS => [
                'Api-Token' => $this->apiKey,
            ],
        ];

        if (null !== $data) {
            $requestOptions[RequestOptions::JSON] = $data;
        }

        $response = $this->guzzleClient->request($method, $uri, $requestOptions);
        $result = json_decode($response->getBody(), true);

        return $result;
    }
}
