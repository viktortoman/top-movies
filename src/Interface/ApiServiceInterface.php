<?php

namespace App\Interfaces;

interface ApiServiceInterface
{
    /**
     * @param string $method
     * @param string $uri
     * @param array $getParams
     * @return mixed
     */
    public function call(string $method, string $uri, array $getParams = []);
}