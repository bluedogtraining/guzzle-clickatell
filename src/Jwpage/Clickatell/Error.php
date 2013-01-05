<?php

namespace Jwpage\Clickatell;

class Error
{
    public $code;
    public $description;

    public function __construct($line)
    {
        preg_match('/^(.*?), (.*?)$/', $line, $matches);
        $this->code = $matches[1];
        $this->description = $matches[2];
    }
}
