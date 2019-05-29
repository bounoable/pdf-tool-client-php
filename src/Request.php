<?php

namespace PdfTools;

use GuzzleHttp\RequestOptions;
use GuzzleHttp\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\RequestException as GuzzleRequestException;

class Request
{
    /**
     * @var string
     */
    private $uri;

    /**
     * @var string
     */
    private $method;

    /**
     * @var array
     */
    private $body = [];

    public function __construct(string $uri, string $method, array $body = [])
    {
        $this->uri = $uri;
        $this->method = $method;
        $this->body = $body;
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getBody(): array
    {
        return $this->body;
    }

    public function send(ClientInterface $client): ResponseInterface
    {
        try {
            return $client->request($this->getMethod(), $this->getUri(), [
                RequestOptions::JSON => $this->getBody(),
            ]);
        } catch (GuzzleRequestException $e) {
            $response = $e->getResponse();
            $message = $response ? json_decode($response->getBody()->getContents(), true)['message'] : $this->serverUnreachableMessage();

            throw new RequestException($message);
        }
    }

    protected function serverUnreachableMessage(): string
    {
        return sprintf("The URL '%s' was not reachable.", $this->uri);
    }
}
