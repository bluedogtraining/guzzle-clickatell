<?php

namespace Jwpage\Clickatell\Response;

use Jwpage\Clickatell\Error;

class SendMsg extends AbstractResponse
{
    public function isSuccessful()
    {
        foreach ($this->parsedResponse as $line) {
            if ($line[1] == 'ERR') {
                return false;
            }
        }
        return true;
    }

    public function getError()
    {
        throw new \BadMethodCallException(trim('
            Use getMessageIds instead.
        '));

    }

    // alias for getMessageIds
    public function getMessageId()
    {
        return $this->getMessageIds();
    }

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
