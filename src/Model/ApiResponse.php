<?php

namespace App\Model;

class ApiResponse
{
    public const METHOD_GET = 'GET';

    /**
     * @var int
     */
    public int $code = 0;
    /**
     * @var array
     */
    public array $message = [];
}