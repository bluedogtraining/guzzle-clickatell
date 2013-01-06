<?php

namespace Jwpage\Clickatell\Response;

use Jwpage\Clickatell\Error;

/**
 * Response from the SendMsg command.
 */
class SendMsg extends AbstractResponse
{
    /**
     * Determines if all messages were successful. 
     * 
     * @return boolean
     */
    public function isSuccessful()
    {
        foreach ($this->parsedResponse as $line) {
            if ($line[1] == 'ERR') {
                return false;
            }
        }
        return true;
    }

    /**
     * Disable the getError command. self::getMessageIds should be used instead,
     * as it returns individual errors for each message sent.
     * 
     * @throws \BadMethodCallException
     */
    public function getError()
    {
        throw new \BadMethodCallException(trim('
            Use getMessageIds instead.
        '));

    }


    /**
     * Get message IDs or error status of each sent message.
     * Response is in the format of:
     *   array(
     *     '0400000001' => Error,
     *     '0400000002' => 'message_id'
     *   )
     * 
     * @return array message ID or error for each sent message.
     */
    public function getMessageIds()
    {
        $toNumbers = explode(',', $this->request->getPostField('to'));
        $result = array();
        foreach ($this->parsedResponse as $key => $line) {
            if ($line[1] == 'ERR') {
                $item = new Error($line[2]);
            } else {
                preg_match('/^(.*?)\s/', $line[2], $matches);
                $item = $matches[1];
            }
            $result[$toNumbers[$key]] = $item;
        }
        return $result;
    }

}
