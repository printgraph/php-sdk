<?php

declare(strict_types=1);

namespace Printgraph\PhpSdk\Client;

use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use Psr\Http\Message\RequestInterface;

final class ClientFactory
{
    /**
     * HttpOptions.
     *
     * @var mixed[]
     */
    private static array $httpOptions = [
        'defaults' => [
            'timeout' => 300,
            'debug' => false,
        ],
        'headers' => [
            'User-Agent' => 'php-printgraph-sdk v1',
            'Accept' => 'application/json',
        ],
    ];

    /**
     * @param string $token
     * @param array<callable> $middlewares
     * @return ClientInterface
     */
    public static function createHttpClient(string $token, array $middlewares = [], string $baseUrl = 'https://api.printgraph.jp'): ClientInterface
    {
        $stack = new HandlerStack();
        $stack->setHandler(new CurlHandler());
        $stack->push(
            Middleware::mapRequest(
                static fn(RequestInterface $request) => $request->withHeader('Authorization', 'Bearer ' . $token)
            )
        );
        foreach ($middlewares as $middleware) {
            $stack->push($middleware);
        }

        $options = array_merge(self::$httpOptions, [
            'handler' => $stack,
            'base_uri' => $baseUrl,
        ]);

        $httpClient = new \GuzzleHttp\Client($options);
        return new Client($httpClient);
    }
}
