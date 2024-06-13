<?php

namespace App\Services;

use App\Services\Contracts\HttpClientInterface;
use App\Exceptions\HttpClientException;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;

class HttpClient implements HttpClientInterface{

    protected $http; 
    protected $headers;
    public $response;

    public function __construct()
    {
        $this->http = Http::retry(2, 100);
    }

    /**
     * Configura el cliente HTTP.
     *
     * @param int $retry Número de reintentos en caso de fallo.
     * @param int $sleepTime Tiempo de espera entre reintentos en milisegundos.
     * @param int $timeout Tiempo máximo de espera para la solicitud en segundos.
     * @param array|null $headers Encabezados HTTP opcionales.
     */

    public function config(int $retry, int $sleepTime = 100, int $timeout = 30, ?array $headers)
    {
        $this->http->retry($retry, $sleepTime);
        $this->headers = empty($headers) ? [] : $headers;
        $this->http->withHeaders($headers);  
        $this->http->timeout($timeout);
    }

    /**
     * Realiza una solicitud HTTP.
     *
     * @param string $url La URL a la que se realizará la solicitud.
     * @param string $method El método HTTP (GET, POST, etc.).
     * @param array|null $data Datos opcionales a enviar con la solicitud.
     * @param bool $autoConvert Indica si se debe devolver el cuerpo de la respuesta como cadena (true) o como array JSON (false).
     * @return array|string El cuerpo de la respuesta como array JSON o como cadena.
     * @throws HttpClientException Si la solicitud HTTP falla.
     */

    public function makeRequest(string $url, string $method, ?array $data, ?bool $autoConvert = true): array|string
    {   
        $method = strtoupper($method);
        switch($method){
            case 'GET': 
                $this->response = $this->http->get($url, $data);
                break;
            case 'POST':
                $this->response = $this->http->post($url, $data);
                break;
            default:
                throw new HttpClientException('Invalid Method In makeRequest');
            break;
        }
        if(!$this->isSuccessResponse($this->response)){
            $message = 'HTTP request failed with status code: ' . $this->response->status();
            throw new HttpClientException($message);
        }

        if($autoConvert){
            return json_decode($this->response->body(), true);
        }

        return $this->response->body();
    }

    private function isSuccessResponse(Response $response): bool
    {
        return $response->successful();
    }
}