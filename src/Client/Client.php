<?php

declare(strict_types=1);

namespace Printgraph\PhpSdk\Client;

use GuzzleHttp\ClientInterface as HttpClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Prewk\Result;
use Prewk\Result\{Err, Ok};

final class Client implements ClientInterface
{
    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private readonly string $apiVersion
    ) {}

    /**
     * @throws GuzzleException
     */
    public function request(string $method, string $path, array $options = []): Result
    {
        $path = sprintf('/%s/%s', $this->apiVersion, $path);
        $response = $this->httpClient->request($method, $path, $options);
        if ($response->getStatusCode() >= 400) {
            return new Err($response);
        }

        return new Ok($response);
    }
}
