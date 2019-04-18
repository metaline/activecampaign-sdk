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
     * @param array $result
     */
    public function __construct($successful = true, array $result = [])
    {
        $this->successful = $successful;

        if ($successful) {
            $this->data = $result;
        } else {
            $this->errors = $result;
        }
    }

    /**
     * Returns true if the client response is successful, false otherwise.
     *
     * @return bool
     */
    public function isSuccessful()
    {
        return $this->successful;
    }

    /**
     * Returns the data returned from the client call.
     *
     * If the result is failed, this method returns an empty array.
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Returns the errors returned from the client call.
     *
     * If the result is successful, this method returns an empty array.
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }
}
