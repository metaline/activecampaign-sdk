<?php

/*
 * This file is part of the ActiveCampaign API SDK.
 *
 * (c) Meta Line S.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MetaLine\ActiveCampaign\Tests\Fixture;

use Exception;
use GuzzleHttp\Exception\GuzzleException;

final class GenericGuzzleException extends Exception implements GuzzleException {}
