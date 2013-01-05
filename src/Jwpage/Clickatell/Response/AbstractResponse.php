<?php

namespace Jwpage\Clickatell\Response;

abstract class AbstractResponse
{
    protected $parsedResponse;

    public function __construct($response)
    {
        $this->parsedResponse = $this->parseBody($response->getBody());
    }

    public function isSuccessful()
    {
        $response = $this->parsedResponse;
        return ($response[1] != 'ERR');
    }

    protected function parseBody($body)
    {
        preg_match('/(OK|ID|ERR):\s?(.*?)$/', $body, $matches);
        return $matches;
    }
}