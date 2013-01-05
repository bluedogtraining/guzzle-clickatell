<?php

namespace Jwpage\Clickatell\Response;

class SendMsg extends AbstractResponse
{
    public function getMessageId()
    {
        return $this->isSuccessful() 
            ? trim($this->parsedResponse[2])
            : false;
    }

}