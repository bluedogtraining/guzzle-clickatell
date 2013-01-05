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
        $required = array('api_id', 'username', 'password', 'base_url');
        $config = Collection::fromConfig($config, $default, $required);
        $description = ServiceDescription::factory(__DIR__.'/service.json');

        $client = new self($config->get('base_url'), $config);
        $client->setDescription($description);

        return $client;
    }
}