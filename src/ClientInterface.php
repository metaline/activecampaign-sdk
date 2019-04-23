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
     * @param string $path
     * @return Result
     */
    public function get($path);

    /**
     * @param string $path
     * @param array  $body
     * @return Result
     */
    public function post($path, array $body);

    /**
     * @param string $path
     * @param array  $body
     * @return Result
     */
    public function put($path, array $body);

    /**
     * @param string $path
     * @return Result
     */
    public function delete($path);

    /**
     * @return bool
     */
    public function testCredentials();
}
