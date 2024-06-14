<?php

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\ResponseInterface;

class EngineApiPartner
{
    private string $entryPoint;
    private string $apiKey;
    private string $apiSecret;

    public function __construct(
        string $endpoint,
        string $apiKey,
        string $apiSecret
    ) {
        $this->entryPoint = $endpoint;
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;
    }

    public function get(string $uri, array $queryParams = []): ResponseInterface
    {
        return $this->call('GET', $uri, $queryParams, '');
    }

    public function delete(string $uri, array $queryParams = []): ResponseInterface
    {
        return $this->call('DELETE', $uri, $queryParams, '');
    }

    public function post(string $uri, array $queryParams, string $requestBody): ResponseInterface
    {
        return $this->call('POST', $uri, $queryParams, $requestBody);
    }

    public function put(string $uri, array $queryParams, string $requestBody): ResponseInterface
    {
        return $this->call('PUT', $uri, $queryParams, $requestBody);
    }

    private function call(string $method, string $uri, array $queryParams, string $requestBody): ResponseInterface
    {
        $fullUri = $this->buildFullUri($uri, $queryParams);
        $headers = $this->buildHeaders();

        echo "\n";
        echo "=======================================================================\n";
        echo "=======================================================================\n";
        print_r(
            [
                'uri'          => $fullUri,
                'method'       => $method,
                'request_body' => $requestBody,
            ]
        );

        $client = HttpClient::create();
        $response = $client->request(
            $method,
            $this->entryPoint . $fullUri,
            [
                'verify_peer' => false,
                'verify_host' => false,
                'headers'     => $headers,
                'body'        => $requestBody,
            ]
        );

        print_r($response->getContent());
        echo "\n";

        return $response;
    }

    private function buildFullUri(string $uri, array $queryParams): string
    {
        $builtQueryParams = http_build_query($queryParams);

        $fullUri = $uri;
        if ($builtQueryParams) {
            $fullUri .= '?' . $builtQueryParams;
        }

        return $fullUri;
    }

    private function buildHeaders(): array
    {
        $requestTime = time();

        $hashParts = [
            $this->apiKey,
            $requestTime,
            $this->apiSecret,
        ];

        $requestHash = hash('sha256', implode('', $hashParts));

        return [
            'api-key: '.$this->apiKey,
            'api-request-time: '.$requestTime,
            'api-request-hash: '.$requestHash,
        ];
    }
}
