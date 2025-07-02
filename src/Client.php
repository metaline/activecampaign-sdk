<?php

/*
 * This file is part of the ActiveCampaign API SDK.
 *
 * (c) Meta Line S.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace MetaLine\ActiveCampaign;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientInterface as GuzzleClientInterface;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;

/**
 * The Client is the main entry point to access the ActiveCampaign APIs.
 *
 * To create an instance of the Client, you need a URL and a KEY. These parameters are specific to
 * your ActiveCampaign account. You can find them under the section Settings / Developer of your profile.
 *
 * @author Daniele De Nobili
 */
final class Client implements ClientInterface
{
    /**
     * ActiveCampaign API base URL
     *
     * @var string
     */
    private $apiUrl;

    /**
     * API key for authentication
     *
     * @var string
     */
    private $apiKey;

    /**
     * Guzzle HTTP client for making requests
     *
     * @var GuzzleClientInterface
     */
    private $guzzleClient;

    public function __construct(string $apiUrl, string $apiKey, ?GuzzleClientInterface $guzzleClient = null)
    {
        $this->apiUrl = $apiUrl . '/api/3/';
        $this->apiKey = $apiKey;
        $this->guzzleClient = $guzzleClient ?: new GuzzleClient();
    }

    public function get(string $path): Result
    {
        return $this->sendRequest('GET', $path);
    }

    public function post(string $path, array $body): Result
    {
        return $this->sendRequest('POST', $path, $body);
    }

    public function put(string $path, array $body): Result
    {
        return $this->sendRequest('PUT', $path, $body);
    }

    public function delete(string $path): Result
    {
        return $this->sendRequest('DELETE', $path);
    }

    public function testCredentials(): bool
    {
        return $this->sendRequest('GET', 'users/me')->isSuccessful();
    }

    private function sendRequest(string $method, string $path, ?array $data = null): Result
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

        try {
            $response = $this->guzzleClient->request($method, $uri, $requestOptions);
            $result = (array) json_decode($response->getBody(), true);

            return new Result(true, $result);
        } catch (ClientException $e) {
            $errors = [];
            $result = (array) json_decode($e->getResponse()->getBody(), true);

            if (isset($result['message'])) {
                $errors['errors'] = [
                    [
                        'title' => $result['message'],
                    ],
                ];
            } elseif (isset($result['errors']) && is_array($result['errors'])) {
                $errors = $result;
            }

            return new Result(false, [], $errors);
        } catch (GuzzleException $e) {
            $errors['errors'] = [
                [
                    'title' => $e->getMessage(),
                ],
            ];

            return new Result(false, [], $errors);
        }
    }
}
