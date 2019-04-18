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
 * Result
 */
final class Result
{
    /**
     * @var bool
     */
    private $successful;

    /**
     * @var array
     */
    private $data = [];

    /**
     * @var array
     */
    private $errors = [];

    /**
     * @param bool  $successful
     * @param array $data
     * @param array $errors
     */
    public function __construct($successful = true, array $data = [], array $errors = [])
    {
        $this->successful = $successful;
        $this->data = $data;
        $this->errors = $errors;
    }

    /**
     * @return bool
     */
    public function isSuccessful()
    {
        return $this->successful;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }
}
