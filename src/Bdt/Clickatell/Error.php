<?php

namespace Bdt\Clickatell;

/**
 * Class to represent an error returned from the Clicaktell API.
 */
class Error
{
    /**
     * @var int error code
     */
    public $code;

    /**
     * @var string error description
     */
    public $description;

    /**
     * Creates a new error from a Clickatell API error line.
     *
     * @param string Error line in the format of 'Error code, error message'.
     */
    public function __construct($line)
    {
        preg_match('/^(.*?), (.*?)$/', $line, $matches);
        $this->code = $matches[1];
        $this->description = $matches[2];
    }
}
