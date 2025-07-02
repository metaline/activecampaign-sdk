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

/**
 * Result Container
 *
 * Encapsulates the result of an API call, providing access to success status,
 * response data, and error information. This class is returned by all Client
 * methods to provide a consistent interface for handling API responses.
 *
 * @author Daniele De Nobili
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

    public function __construct(bool $successful = true, array $result = [], array $errors = [])
    {
        $this->successful = $successful;

        if ($successful) {
            $this->data = $result;
        } else {
            $this->errors = $errors;
        }
    }

    /**
     * Checks if the API request was successful
     */
    public function isSuccessful(): bool
    {
        return $this->successful;
    }

    /**
     * Gets the response data from successful API calls
     *
     * If the result is failed, this method returns an empty array.
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * Gets the error information from failed API calls
     *
     * If the result is successful, this method returns an empty array.
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
