<?php

namespace Bdt\Clickatell\Response;

/**
 * Response from the QueryMsg command. 
 */
class QueryMsg extends AbstractResponse
{
    /**
     * Get the status of a sent message. 
     * 
     * @return false|string status code
     */
    public function getStatus()
    {
        if (!$this->isSuccessful()) {
            return false;
        }

        preg_match('/Status: (.*?)$/', $this->parsedResponse[0][2], $matches);
        return $matches[1];
    }
}
