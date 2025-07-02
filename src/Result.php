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
     * Returns true if the client response is successful, false otherwise.
     */
    public function isSuccessful(): bool
    {
        return $this->successful;
    }

    /**
     * Returns the data returned from the client call.
     *
     * If the result is failed, this method returns an empty array.
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * Returns the errors returned from the client call.
     *
     * If the result is successful, this method returns an empty array.
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
