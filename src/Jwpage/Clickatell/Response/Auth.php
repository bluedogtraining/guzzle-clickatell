<?php

namespace Jwpage\Clickatell\Response;

/**
 * Response from the Auth command.
 */
class Auth extends AbstractResponse
{
    /**
     * Get the session ID. 
     * 
     * @return false|string
     */
    public function getSessionId()
    {
        return $this->isSuccessful()
            ? trim($this->parsedResponse[0][2])
            : false;
    }

}
