<?php

namespace Jwpage\Clickatell\Response;

class Auth extends AbstractResponse
{
    public function getSessionId()
    {
        return $this->isSuccessful() 
            ? trim($this->parsedResponse[2])
            : false;
    }

}