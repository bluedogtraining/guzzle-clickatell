<?php

namespace Jwpage\Clickatell\Command;

use Jwpage\Clickatell\Command;
use Guzzle\Service\Description\OperationInterface;

class Send extends Command
{
    public function get($key)
    {
        $item = parent::get($key); 
        if ($key == 'to' && is_array($item)) {
            $item = implode(',', $item);
        }
        return $item;
    }
}