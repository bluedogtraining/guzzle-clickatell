<?php

namespace Jwpage\Clickatell;

use Guzzle\Service\Command\OperationCommand;

/**
 * Clicaktell API command.
 */
class Command extends OperationCommand
{
    /**
     * Creates a Clickatell Response after the request has been completed.
     * 
     * @return \Jwpage\Clickatell\Response\AbstractResponse
     */
    protected function process()
    {
        $class = $this->operation->getResponseClass();

        $this->result = $this->get(self::RESPONSE_PROCESSING) == self::TYPE_RAW
            ? $this->request->getResponse()
            : new $class($this->request);
    }

}
