<?php

namespace PdfTools\Tools\HtmlToPdf;

class PageOptions
{
    /**
     * @var string|null
     */
    public $uri;

    /**
     * @var string|null
     */
    public $selector;

    /**
     * @var string|null
     */
    public $waitUntil;

    /**
     * @var string|null
     */
    public $waitForSelector;

    /**
     * @var int|null
     */
    public $timeout;

    /**
     * @var int|null
     */
    public $delay;

    /**
     * @var array
     */
    public $headers = [];

    /**
     * @var string|null
     */
    public $format;

    public function toArray(): array
    {
        return [
            'uri' => $this->uri,
            'selector' => $this->selector,
            'waitUntil' => $this->waitUntil,
            'waitForSelector' => $this->waitForSelector,
            'timeout' => $this->timeout,
            'delay' => $this->delay,
            'headers' => $this->headers,
            'format' => $this->format,
        ];
    }
}
