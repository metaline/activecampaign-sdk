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

use MetaLine\ActiveCampaign\Result;
use PHPUnit\Framework\TestCase;

/**
 * ResultTest
 */
final class ResultTest extends TestCase
{
    public function testDefault()
    {
        $result = new Result();

        $this->assertTrue($result->isSuccessful());
        $this->assertEquals([], $result->getData());
        $this->assertEquals([], $result->getErrors());
    }

    public function testWithData()
    {
        $data = ['foo' => 'bar'];
        $result = new Result(true, $data);

        $this->assertTrue($result->isSuccessful());
        $this->assertEquals($data, $result->getData());
        $this->assertEquals([], $result->getErrors());
    }

    public function testWithErrors()
    {
        $data = ['foo' => 'bar'];
        $result = new Result(false, [], $data);

        $this->assertFalse($result->isSuccessful());
        $this->assertEquals([], $result->getData());
        $this->assertEquals($data, $result->getErrors());
    }
}
