<?php

namespace Bdt\Clickatell;

use Guzzle\Common\Collection;
use Guzzle\Service\Client;
use Guzzle\Service\Description\ServiceDescription;
use Guzzle\Http\Message\RequestInterface;

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
    public function createRequest($method = RequestInterface::GET, $uri = null, $headers = null, $body = null, array $options = array())
    {
        $request = parent::createRequest($method, $uri, $headers, $body, $options);

        if (!$request->getPostField("session_id")) {
            $config = $this->getConfig();
            $request->addPostFields($config->getAll(array('api_id', 'user', 'password')));
        }

        return $request;
    }

    /**
     * Syntactic sugar for simply sending a message. 
     * 
     * @param integer $to 
     * @param string $message 
     * @return boolean
     */
    public function sendMessage($to, $message)
    {
        $config = $this->getConfig();
        
        $messageConfig = [
            'to'      => $to,
            'text' => $message,
        ];

        // If a sender_id or 'from' is set, use it
        if(!is_null($config->get('from')))
        {
            $messageConfig['from'] = $config->get('from');
        }

        return $this->getCommand('SendMsg', $messageConfig)->execute()->isSuccessful();
    }
}
