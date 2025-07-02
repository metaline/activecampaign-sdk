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

/**
 * The Client is the main entry point to access the ActiveCampaign APIs.
 *
 * @author Daniele De Nobili
 */
interface ClientInterface
{
    /**
     * Performs an HTTP GET request
     */
    public function get(string $path): Result;

    /**
     * Performs an HTTP POST request
     */
    public function post(string $path, array $body): Result;

    /**
     * Performs an HTTP PUT request
     */
    public function put(string $path, array $body): Result;

    /**
     * Performs an HTTP DELETE request
     */
    public function delete(string $path): Result;

    /**
     * Tests the validity of API credentials
     *
     * Makes a request to the 'users/me' endpoint to verify if
     * the configured API credentials are valid and working.
     */
    public function testCredentials(): bool;
}
