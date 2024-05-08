<?php

namespace App\Services;

use App\Services\Contracts\HttpClientInterface;
use Illuminate\Support\Facades\Http;

class HttpClient implements HttpClientInterface
{
    public function config(string $url, string $method, int $retry, ?array $data, ?array $headers)
    {
        $http = Http::retry($retry, 100);
        $headers = empty($headers) ? [] : $headers;
        switch($data){
            case 'GET':
                $http->withHeaders($headers)->get($url, $data); 
                return $http->body();
                
        }
    }
}