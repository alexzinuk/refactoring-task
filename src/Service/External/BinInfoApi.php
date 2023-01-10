<?php

declare(strict_types = 1);

namespace RefactoringTask\Service\External;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use RefactoringTask\Exception\BinInfoApiException;

class BinInfoApi
{
    /**
     * @var Client
     */
    protected $httpClient;

    public function __construct()
    {
        $this->httpClient = new Client([
            'base_uri' => 'https://lookup.binlist.net',
        ]);
    }

    public function request(string $method, string $url, ?array $params = []): array
    {
        try {
            $response = $this->httpClient->request($method, $url, $params);
        } catch (GuzzleException $exception) {
            throw new BinInfoApiException('Could not get connection to bin info api.', null, $exception);
        }

        return json_decode($response->getBody()->getContents(), true);
    }
}