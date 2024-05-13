<?php

namespace App\Services;

use App\Services\Contracts\HttpClientInterface;
use App\Exceptions\HttpClientException;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;

class HttpClient implements HttpClientInterface{

    protected $http; 
    protected $headers;

    public function __construct()
    {
        $this->http = Http::retry(2, 100);
    }

    public function config(int $retry, int $sleepTime = 100, int $timeout = 30, ?array $headers)
    {
        $this->http->retry($retry, $sleepTime);
        $this->headers = empty($headers) ? [] : $headers;
        $this->http->withHeaders($headers);  
        $this->http->timeout($timeout);
    }

    public function makeRequest(string $url, string $method, ?array $data): array
    {   
        $method = strtoupper($method);
        switch($method){
            case 'GET': 
                $response = $this->http->get($url, $data);
                break;
            case 'POST':
                $response = $this->http->post($url, $data);
                break;
            default:
                throw new HttpClientException('Invalid Method In makeRequest');
            break;
        }
        if(!$this->isSuccessResponse($response)){
            $message = 'HTTP request failed with status code: ' . $response->status();
            throw new HttpClientException($message);
        }
              
        return json_decode($response->body(), true);
    }

    protected function isSuccessResponse(Response $response): bool
    {
        return $response->successful();
    }
}