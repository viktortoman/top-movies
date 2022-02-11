<?php

namespace App\Service;

use App\Interfaces\ApiServiceInterface;
use App\Model\ApiResponse;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use GuzzleHttp\Client;

class ApiClientService implements ApiServiceInterface
{
    /**
     * @var string
     */
    private string $apiUrl;
    /**
     * @var string
     */
    private string $apiKeyV4;

    /**
     * @param ParameterBagInterface $parameterBag
     */
    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->apiUrl = $parameterBag->get('app.tmdb_api_url');
        $this->apiKeyV4 = $parameterBag->get('app.tmdb_api_key_v4');
    }

    /**
     * @throws GuzzleException
     */
    public function call(string $method, string $uri, array $getParams = []): ApiResponse
    {
        return $this->consume(
            $method,
            $uri,
            $getParams
        );
    }

    /**
     * @param string $method
     * @param string $uri
     * @param array $getParams
     * @return ApiResponse
     * @throws GuzzleException
     */
    private function consume(string $method, string $uri, array $getParams): ApiResponse
    {
        $response = new ApiResponse();

        try {
            $client = new Client([
                'verify'    => false, // TODO: this solution is only dev environment
                'base_uri'  => $this->apiUrl
            ]);

            $result = $client->request($method, $uri, [
                'headers' => $this->createHeader(),
                'query' => http_build_query($getParams)
            ]);

            $response->code = $result->getStatusCode();
            $response->message = json_decode($result->getBody(), true);
        } catch (BadResponseException $e) {
            $response->code = $e->getCode();
            $response->message = [$e->getMessage()];
        }

        return $response;
    }

    /**
     * @return string[]
     */
    private function createHeader(): array
    {
        return [
            'Authorization' => 'Bearer ' . $this->apiKeyV4,
            'Accept'        => 'application/json'
        ];
    }
}