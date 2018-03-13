<?php

namespace Kitchenu\Chatwork\Tests;

use Kitchenu\Chatwork\Client;
use Kitchenu\Chatwork\Exception\ChatworkException;

class ClientTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Client
     */
    private $client;

    public function setUp() {
        $this->client = new Client('token', []);
    }

    public function testRequest()
    {    
        try {
            $this->client->request('GET', 'me');
        } catch (ChatworkException $e) {
            $this->assertEquals($e->getCode(), 401);
        }
        
        try {
            $this->client->request('POST', 'me');
        } catch (ChatworkException $e) {
            $this->assertEquals($e->getCode(), 401);
        }
    }

    public function testSetterGetter()
    {
        $this->assertSame($this->client->token(), 'token');
        
        $this->client->token('test');
        $this->assertSame($this->client->token(), 'test');
    }

    public function FunctionName(Type $var = null)
    {
        try {
            $this->client->getMe();
        } catch (ChatworkException $e) {
            $this->assertEquals($e->getCode(), 401);
        }
    }
}
