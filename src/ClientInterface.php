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
    public function get(string $path): Result;

    public function post(string $path, array $body): Result;

    public function put(string $path, array $body): Result;

    public function delete(string $path): Result;

    public function testCredentials(): bool;
}
