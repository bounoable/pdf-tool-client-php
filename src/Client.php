<?php

namespace PdfTools;

use GuzzleHttp\RequestOptions;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Client as GuzzleClient;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\RequestException as GuzzleRequestException;

class Client
{
    /**
     * @var ClientInterface
     */
    private $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * Create a client instance.
     *
     * @param  string  $baseURI  Base URI to the utility server.
     * @param  array  $guzzleConfig  Guzzle HTTP client config.
     * @return self
     */
    public static function create(string $baseURI, array $guzzleConfig = [])
    {
        $config = array_merge(['base_uri' => $baseURI], $guzzleConfig);

        return new Client(new GuzzleClient($config));
    }

    /**
     * Create a PDF of a webpage.
     *
     * Example:
     *  [
     *      'selector' => '#selector',
     *      'waitForSelector' => '#selector',
     *      'waitUntil' => 'networkidle0'|'networkidle2'|'load'|'domcontentloaded',
     *      'delay' => 3000,
     *      'authToken' => 'Bearer bearer-auth-token',
     *  ]
     *
     * @param  string  $url  URL to the webpage.
     * @param  array  $options
     * @return ResponseInterface
     * @throws RequestException
     */
    public function pageToPdf(string $url, array $options = []): ResponseInterface
    {
        $selector = $options['selector'] ?? null;
        $waitForSelector = $options['waitForSelector'] ?? null;
        $waitUntil = $options['waitUntil'] ?? null;
        $authToken = $options['authToken'] ?? null;
        $delay = $options['delay'] ?? 0;

        $data = ['url' => $url];

        if ($authToken) {
            $data['authToken'] = $authToken;
        }

        if ($selector) {
            $data['selector'] = $selector;
        }

        if ($waitForSelector) {
            $data['waitForSelector'] = $waitForSelector;
        }

        if ($waitUntil) {
            $data['waitUntil'] = $waitUntil;
        }

        if ($delay) {
            $data['delay'] = $delay;
        }

        try {
            return $this->client->request('POST', Endpoint::PAGE_TO_PDF, [
                RequestOptions::JSON => $data,
            ]);
        } catch (GuzzleRequestException $e) {
            $response = $e->getResponse();
            $message = $response ? json_decode($response->getBody()->getContents(), true)['message'] : $this->serverUnreachableMessage(Endpoint::PAGE_TO_PDF);

            throw new RequestException($message);
        }
    }

    protected function serverUnreachableMessage(string $endpoint): string
    {
        return sprintf("The URL '%s%s' was not reachable.", $this->client->getConfig('base_uri'), $endpoint);
    }
}
