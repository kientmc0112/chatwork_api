<?php

namespace Kitchenu\Chatwork\Tests;

use Kitchenu\Chatwork\Client;
use Kitchenu\Chatwork\Exception\ChatworkException;
use Kitchenu\Chatwork\Response;
use GuzzleHttp\Psr7\Response as HttpResponse;

class ResponseTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Response
     */
    private $response;

    public function setUp()
    {
        $client = new Client('token', ['handler' => make_mock_handler()]);
        $this->response = $client->getMe();
    }

    public function testResult()
    { 
        $result = $this->response->result();

        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('test_1', $result);
        $this->assertSame($result['test_1'], '1');
    }

    public function testInfo()
    { 
        $info = $this->response->info();

        $this->assertInternalType('array', $info);
        $this->assertInstanceOf(\Datetime::class, $info['date']);
    }

    public function testHttpClient()
    { 
        $client = $this->response->httpResponse();

        $this->assertInstanceOf(HttpResponse::class, $client);
    }

    public function testGet()
    { 
        $this->assertSame($this->response->get('test_1'), '1');
        $this->assertSame($this->response->get('limit'), '100');

        $this->assertSame($this->response->test_1, '1');
        $this->assertSame($this->response->limit, '100');
    }
}