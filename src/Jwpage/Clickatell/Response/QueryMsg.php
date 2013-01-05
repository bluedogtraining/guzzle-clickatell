<?php

namespace Jwpage\Clickatell\Response;

class QueryMsg extends AbstractResponse
{
    public function getStatus()
    {
        preg_match('/Status: (.*?)$/', $this->parsedResponse[0][2], $matches);
        return $matches[1];
    }
}