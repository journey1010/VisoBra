<?php

namespace App\Services\Contracts;

interface HttpClientInterface
{
    public function config(string $url, string $method, int $retry, ?array $data, ?array $headers);
}
