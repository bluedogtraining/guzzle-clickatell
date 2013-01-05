# guzzle-clickatell 

A PHP 5.3+ cient for interacting with the Clickatell HTTP API.

## Installation

Add this to your composer.json by running 
`composer.phar require jwpage/guzzle-clickatell`.

## Usage

### Create API client

    $client = \Jwpage\Clickatell\ClickatellClient::factory(array(
        'api_id'   => $apiId,
        'user'     => $user,
        'password' => $password,
    ));

### Authenticate to the API

    $client->getCommand('Auth')->execute()->getSessionId();

### Ping the API to keep the session ID alive

    $client->getCommand('Ping', array('session_id' => $sessionId))->execute();

### Send a message

Passing a `session_id` parameter is optional. If it isn't present the client
will use the authentication details provided.

    $result = $client->getCommand('SendMsg', array(
        'to'   => $mobileNumber,
        'text' => $messageContents,
    ))->execute();

    $result->isSuccessful();  // true
    $result->getMessageIds(); // array('mobile_number' => 'message_id')

### Query a message

    $client->getCommand('QueryMsg', array(
        'apimsgid' => $messageId,
    ))->execute()->getStatus();

## Running Tests

First, install PHPUnit with `composer.phar install --dev`, then run 
`./vendor/bin/phpunit`.

## More Reading

* [guzzlephp.org: Consuming web services using web service clients](http://guzzlephp.org/tour/using_services.html)
* [clickatell.com: HTTP API documentation PDF](http://www.clickatell.com/downloads/http/Clickatell_HTTP.pdf)
