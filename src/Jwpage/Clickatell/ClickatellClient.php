<?php

namespace Jwpage\Clickatell;

use Guzzle\Common\Collection;
use Guzzle\Service\Client;
use Guzzle\Service\Description\ServiceDescription;

/**
 * Client for interacting with the Clickatell HTTP API.
 */
class ClickatellClient extends Client
{
  /**                                                                          
     * Factory method to create a new ClickatellClient 
     *                                                                           
     * The following array keys and values are available options:                
     * - base_url:  Base URL of web service                                       
     * - api_id:    Clickatell API ID
     * - user:      Clickatell username
     * - password:  Clickatell password
     *                                                                           
     * @param array|Collection $config Configuration data                        
     *                                                                           
     * @return self                                                              
     */           
    public static function factory($config = array())
    {
        $default = array(
            'base_url' => 'http://api.clickatell.com/',
        );
        $required = array('api_id', 'user', 'password', 'base_url');
        $config = Collection::fromConfig($config, $default, $required);
        $description = ServiceDescription::factory(__DIR__.'/service.json');

        $client = new self($config->get('base_url'), $config);
        $client->setDescription($description);

        return $client;
    }

    /**
     * {@inheritdoc}
     * Also includes the `api_id`, `user`, and `password` in the request if 
     * `session_id` has not been provided in the request.
     */
    public function createRequest($method = RequestInterface::GET, $uri = null, $headers = null, $body = null)
    {
        $request = parent::createRequest($method, $uri, $headers, $body);

        if (!$request->getPostField("session_id")) {
            $config = $this->getConfig();
            $request->addPostFields($config->getAll(array('api_id', 'user', 'password')));
        }

        return $request;
    }

    /**
     * Static method to transform an array into a comma-separated string. 
     * 
     * @param string|array $value one or more items
     * @return string comma-separated string
     */
    public static function csv($value)
    {
        if (is_array($value)) {
            $value = implode(',', $value);
        }
        return $value;
    }
}
