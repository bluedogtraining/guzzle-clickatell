<?php

namespace Jwpage\Clickatell\Response;

use Jwpage\Clickatell\Error;

/**
 * Class to represent responses returned from the Clickatell API.
 */
abstract class AbstractResponse
{
    /**
     * @var array parsed elements of the response
     */
    protected $parsedResponse;

    /**
     * @var \Guzzle\Http\Message\RequestInterface The request object associated
     * with the response.
     */
    protected $request;

    /**
     * Create a new response object from a Clickatell request and response.
     *
     * @param \Guzzle\Http\Message\RequestInterface $request The request object 
     * associated with the response.
     * @throws \UnexpectedValueException when the request does not have a response.
     */
    public function __construct($request)
    {
        $response = $request->getResponse();
        if (!$response) {
            throw new \UnexpectedValueException('Request must have have a response.');
        }

        $this->request = $request;
        $this->parsedResponse = $this->parseBody($response->getBody());
    }

    /**
     * Get the request associated with the response.
     * 
     * @return \Guzzle\Http\Message\RequestInterface
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Get the response associated with this response class.
     * 
     * @return \Guzzle\Http\Message\ResponseInterface
     */
    public function getResponse()
    {
        return $this->request->getResponse();
    }

    /**
     * Used to determine if the response is successful.
     */
    public function isSuccessful()
    {
        $response = $this->parsedResponse;
        return ($response[0][1] != 'ERR');
    }

    /**
     * Get details of the error returned from the API.
     *
     * @return false|Error
     */
    public function getError()
    {
        return $this->isSuccessful() 
            ? false 
            : new Error($this->parsedResponse[0][2]);
    }

    /**
     * Parse the body of the response.
     * 
     * @param string $body Response body
     * @return array parts matched in the response body
     */
    protected function parseBody($body)
    {
        preg_match_all('/(OK|ID|ERR):\s?(.*?)$/m', $body, $matches, PREG_SET_ORDER);
        return $matches;
    }
}
