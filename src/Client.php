<?php

namespace PdfTools;

use PdfTools\Tools\HtmlToPdf;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Client as GuzzleClient;
use Psr\Http\Message\ResponseInterface;

class Client
{
    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var array
     */
    private $endpoints;

    public function __construct(ClientInterface $client, array $endpoints = [])
    {
        $this->client = $client;
        $this->endpoints = $endpoints;
    }

    /**
     * Create a client instance.
     *
     * @param  string  $baseURI  Base URI to the utility server.
     * @param  array  $guzzleConfig  Guzzle HTTP client config.
     * @param  array  $endpoints  Custom endpoint configuration
     * @return self
     */
    public static function create(string $baseURI, array $guzzleConfig = [], array $endpoints = [])
    {
        $config = array_merge(['base_uri' => $baseURI], $guzzleConfig);

        return new Client(new GuzzleClient($config), array_merge([
            HtmlToPdf::class => HtmlToPdf::ENDPOINT,
        ], $endpoints));
    }

    public function uri(string $tool): string
    {
        return $this->client->getConfig('base_uri') . $this->endpoints[$tool];
    }

    public function send(Request $request): ResponseInterface
    {
        return $request->send($this->client);
    }

    public function htmlToPdf(): HtmlToPdf
    {
        return new HtmlToPdf($this);
    }
}
