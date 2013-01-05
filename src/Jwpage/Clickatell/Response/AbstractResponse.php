<?php

namespace Jwpage\Clickatell\Response;

abstract class AbstractResponse
{
    protected $parsedResponse;
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
        $this->parsedResponse = $this->parseBody($request->getResponse()->getBody());
    }

    public function isSuccessful()
    {
        $response = $this->parsedResponse;
        return ($response[0][1] != 'ERR');
    }

    protected function parseBody($body)
    {
        preg_match_all('/(OK|ID|ERR):\s?(.*?)$/m', $body, $matches, PREG_SET_ORDER);
        return $matches;
    }
}