<?php

namespace PdfTools\Tools;

use PdfTools\Client;
use PdfTools\Request;
use Psr\Http\Message\ResponseInterface;
use PdfTools\Tools\HtmlToPdf\PageOptions;

class HtmlToPdf
{
    const ENDPOINT = '/html-to-pdf';

    /**
     * @var Client
     */
    private $client;

    /**
     * @var PageOptions[]
     */
    private $pages = [];

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param  PageOptions|array|string  $options
     * @return $this
     */
    public function addPage($options): self
    {
        if (is_string($options)) {
            $opt = new PageOptions();
            $opt->url = $options;
        } elseif (is_array($options)) {
            $opt = new PageOptions();

            foreach ($options as $key => $value) {
                $opt->{$key} = $value;
            }
        } else {
            $opt = $options;
        }

        $this->pages[] = $opt;

        return $this;
    }

    public function request(): Request
    {
        return new Request($this->client->uri(self::class), 'POST', array_map(function (PageOptions $page) {
            return $page->toArray();
        }, $this->pages));
    }

    public function create(): ResponseInterface
    {
        return $this->client->send($this->request());
    }
}
