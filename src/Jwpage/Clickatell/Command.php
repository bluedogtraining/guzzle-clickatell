<?php

namespace Jwpage\Clickatell;

use Guzzle\Service\Command\OperationCommand;

class Command extends OperationCommand
{

    protected function process()
    {
        $class = $this->operation->getResponseClass();

        $this->result = $this->get(self::RESPONSE_PROCESSING) == self::TYPE_RAW
            ? $this->request->getResponse()
            : new $class($this->request);
    }

}
