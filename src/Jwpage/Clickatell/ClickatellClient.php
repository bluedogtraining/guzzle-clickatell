<?php

namespace Jwpage\Clickatell;

use Guzzle\Common\Collection;
use Guzzle\Service\Client;
use Guzzle\Service\Description\ServiceDescription;

class ClickatellClient extends Client
{
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

    public function createRequest($method = RequestInterface::GET, $uri = null, $headers = null, $body = null)
    {
        $request = parent::createRequest($method, $uri, $headers, $body);

        if (!$request->getPostField("session_id")) {
            $config = $this->getConfig();
            $request->addPostFields($config->getAll(array('api_id', 'user', 'password')));
        }

        return $request;
    }

    public static function csv($value)
    {
        if (is_array($value)) {
            $value = implode(',', $value);
        }
        return $value;
    }
}
