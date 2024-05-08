<?php

namespace App\Services\Contracts;

interface HttpClientInterface
{
    public function config(int $retry, int $sleepTime, int $timeout, ?array $headers);
    
    public function makeRequest(string $url, string $method, ?array $data);
    
}