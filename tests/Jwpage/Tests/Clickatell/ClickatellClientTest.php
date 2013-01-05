<?php

namespace Jwpage\Test\Clickatell;

use Jwpage\Clickatell\ClickatellClient;
use Guzzle\Tests\GuzzleTestCase;
use Guzzle\Plugin\Log\LogPlugin;

class ClickatellClientTest extends GuzzleTestCase
{
    public function setup()
    {
        $this->client = ClickatellClient::factory(array(
            'api_id'   => '1',
            'password' => 'foo',
            'user'     => 'bar'
        ));

    }
    public function testAuth()
    {
        $this->setMockResponse($this->client, 'auth_success.txt');
        $response = $this->client->getCommand('Auth')->execute();
        $this->assertTrue($response->isSuccessful());
        $this->assertInstanceOf('\\Guzzle\\Http\\Message\\Request', $response->getRequest());
        $this->assertInstanceOf('\\Guzzle\\Http\\Message\\Response', $response->getResponse());
        $this->assertEquals('foo', $response->getSessionId());
    }

    public function testAuthFail()
    {
        $this->setMockResponse($this->client, 'auth_failure.txt');
        $response = $this->client->getCommand('Auth')->execute();
        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->getSessionId());
        $this->assertEquals('001', $response->getError()->code);
    }

    public function testPing()
    {
        $this->setMockResponse($this->client, 'ping_success.txt');
        $response = $this->client->getCommand('Ping', array(
            'session_id' => 'foo'
        ))->execute();
        $this->assertTrue($response->isSuccessful());
    }

    public function testPingFail()
    {
        $this->setMockResponse($this->client, 'auth_failure.txt');
        $response = $this->client->getCommand('Ping', array(
            'session_id' => 'foo'
        ))->execute();
        $this->assertFalse($response->isSuccessful());
    }

    public function testSendMsg()
    {
        $this->setMockResponse($this->client, 'sendmsg_success.txt');
        $response = $this->client->getCommand('SendMsg', array(
            'to' => '0400000000',
            'text' => 'test',
        ))->execute();
        $this->assertTrue($response->isSuccessful());
        $this->assertEquals(array(
            '0400000000' => 'foo' 
        ), $response->getMessageIds());
    }

    public function testSendMsgFail()
    {
        $this->setMockResponse($this->client, 'auth_failure.txt');
        $response = $this->client->getCommand('SendMsg', array(
            'to' => '0400000001',
            'text' => 'test',
        ))->execute();

        $result = $response->getMessageIds();
        $this->assertFalse($response->isSuccessful());
        $this->assertInstanceOf('\Jwpage\Clickatell\Error', $result['0400000001']);
    }

    public function testSendMsgMultiple()
    {
        $this->setMockResponse($this->client, 'sendmsg_multiple.txt');
        $response = $this->client->getCommand('SendMsg', array(
            'to' => array('0400000001', '0400000000'),
            'text' => 'test',
        ))->execute();
        $this->assertTrue($response->isSuccessful());
        $this->assertEquals(array(
            '0400000001' => 'foo',
            '0400000000' => 'bar',
        ), $response->getMessageIds());
    }

    public function testQueryMsg()
    {
        $this->setMockResponse($this->client, 'querymsg_success.txt');
        $response = $this->client->getCommand('QueryMsg', array(
            'apimsgid' => '8a4f34f58cfa1c634f59227e61ef16e3'
        ))->execute();
        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('003', $response->getStatus());
    }

    public function testQueryMsgFail()
    {
        $this->setMockResponse($this->client, 'auth_failure.txt');
        $response = $this->client->getCommand('QueryMsg', array(
            'apimsgid' => '8a4f34f58cfa1c634f59227e61ef16e3'
        ))->execute();
        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->getStatus());
    }

    /**
     * @expectedException BadMethodCallException
     */
    public function testSendMsgException()
    {
        $this->setMockResponse($this->client, 'sendmsg_success.txt');
        $response = $this->client->getCommand('SendMsg', array(
            'to' => '0400000000',
            'text' => 'test',
        ))->execute();       
        $response->getError();
    }
}
