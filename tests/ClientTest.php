<?php

namespace Kitchenu\Chatwork\Tests;

use Kitchenu\Chatwork\Client;
use Kitchenu\Chatwork\Exception\ChatworkException;
use Kitchenu\Chatwork\Response;
use GuzzleHttp\Client as HttpClient;

class ClientTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Client
     */
    private $client;

    public function setUp()
    {
        $this->client = new Client('token', ['handler' => make_mock_handler()]);
    }

    public function testRequest()
    { 
        $response = $this->client->request('GET', 'me');

        $this->assertInstanceOf(Response::class, $response);
    }

    public function testHttpClient()
    {
        $this->assertInstanceOf(HttpClient::class, $this->client->httpClient());

        $client = new HttpClient([
            'headers' => [
                'User-Agent' => 'test-chatwork-php',
            ]
        ]);     
        $this->client->httpClient($client);

        $userAgent = $this->client->httpClient()
                                ->getConfig('headers')['User-Agent'];
        $this->assertEquals($userAgent, 'test-chatwork-php');
    }

    public function testToken()
    {
        $this->assertSame($this->client->token(), 'token');
        
        $this->client->token('test');
        $this->assertSame($this->client->token(), 'test');
    }

    public function testGetMe()
    {
        $response = $this->client->getMe();
        $info = $response->info();
        $this->assertSame($info['method'], 'GET');
        $this->assertSame($info['endpoint'], 'me');
    }

    public function testGetMyStatus()
    {
        $response = $this->client->getMyStatus();
        $info = $response->info();
        $this->assertSame($info['method'], 'GET');
        $this->assertSame($info['endpoint'], 'my/status');
    }

    public function testGetMyTasks()
    {
        $response = $this->client->getMyTasks();
        $info = $response->info();
        $this->assertSame($info['method'], 'GET');
        $this->assertSame($info['endpoint'], 'my/tasks');
    }

    public function testGetContacts()
    {
        $response = $this->client->getContacts();
        $info = $response->info();
        $this->assertSame($info['method'], 'GET');
        $this->assertSame($info['endpoint'], 'contacts');
    }

    public function testGetRooms()
    {
        $response = $this->client->getRooms();
        $info = $response->info();
        $this->assertSame($info['method'], 'GET');
        $this->assertSame($info['endpoint'], 'rooms');
    }

    public function testPostRooms()
    {
        $response = $this->client->postRooms([]);
        $info = $response->info();
        $this->assertSame($info['method'], 'POST');
        $this->assertSame($info['endpoint'], 'rooms');
    }

    public function testGetRoom()
    {
        $response = $this->client->getRoom(['room_id' => 1]);
        $info = $response->info();
        $this->assertSame($info['method'], 'GET');
        $this->assertSame($info['endpoint'], 'rooms/1');
    }

    public function testPutRoom()
    {
        $response = $this->client->putRoom(['room_id' => 1]);
        $info = $response->info();
        $this->assertSame($info['method'], 'PUT');
        $this->assertSame($info['endpoint'], 'rooms/1');
    }

    public function testDeleteRoom()
    {
        $response = $this->client->deleteRoom(['room_id' => 1]);
        $info = $response->info();
        $this->assertSame($info['method'], 'DELETE');
        $this->assertSame($info['endpoint'], 'rooms/1');
    }

    public function testGetRoomMembers()
    {
        $response = $this->client->getRoomMembers(['room_id' => 1]);
        $info = $response->info();
        $this->assertSame($info['method'], 'GET');
        $this->assertSame($info['endpoint'], 'rooms/1/members');
    }

    public function testPutRoomMembers()
    {
        $response = $this->client->putRoomMembers(['room_id' => 1]);
        $info = $response->info();
        $this->assertSame($info['method'], 'PUT');
        $this->assertSame($info['endpoint'], 'rooms/1/members');
    }

    public function testGetRoomMessages()
    {
        $response = $this->client->getRoomMessages(['room_id' => 1]);
        $info = $response->info();
        $this->assertSame($info['method'], 'GET');
        $this->assertSame($info['endpoint'], 'rooms/1/messages');
    }

    public function testPostRoomMessages()
    {
        $response = $this->client->postRoomMessages(['room_id' => 1]);
        $info = $response->info();
        $this->assertSame($info['method'], 'POST');
        $this->assertSame($info['endpoint'], 'rooms/1/messages');
    }

    public function testPutRoomMessagesRead()
    {
        $response = $this->client->putRoomMessagesRead(['room_id' => 1]);
        $info = $response->info();
        $this->assertSame($info['method'], 'PUT');
        $this->assertSame($info['endpoint'], 'rooms/1/messages/read');
    }

    public function testPutRoomMessagesUnread()
    {
        $response = $this->client->putRoomMessagesUnread(['room_id' => 1]);
        $info = $response->info();
        $this->assertSame($info['method'], 'PUT');
        $this->assertSame($info['endpoint'], 'rooms/1/messages/unread');
    }

    public function testGetRoomMessage()
    {
        $response = $this->client->getRoomMessage(['room_id' => 1, 'message_id' =>1]);
        $info = $response->info();
        $this->assertSame($info['method'], 'GET');
        $this->assertSame($info['endpoint'], 'rooms/1/messages/1');
    }

    public function testPutRoomMessage()
    {
        $response = $this->client->putRoomMessage(['room_id' => 1, 'message_id' =>1]);
        $info = $response->info();
        $this->assertSame($info['method'], 'PUT');
        $this->assertSame($info['endpoint'], 'rooms/1/messages/1');
    }

    public function testDeleteRoomMessage()
    {
        $response = $this->client->deleteRoomMessage(['room_id' => 1, 'message_id' =>1]);
        $info = $response->info();
        $this->assertSame($info['method'], 'DELETE');
        $this->assertSame($info['endpoint'], 'rooms/1/messages/1');
    }

    public function testGetRoomTasks()
    {
        $response = $this->client->getRoomTasks(['room_id' => 1]);
        $info = $response->info();
        $this->assertSame($info['method'], 'GET');
        $this->assertSame($info['endpoint'], 'rooms/1/tasks');
    }

    public function testPostRoomTasks()
    {
        $response = $this->client->postRoomTasks(['room_id' => 1]);
        $info = $response->info();
        $this->assertSame($info['method'], 'POST');
        $this->assertSame($info['endpoint'], 'rooms/1/tasks');
    }

    public function testGetRoomTask()
    {
        $response = $this->client->getRoomTask(['room_id' => 1, 'task_id' => 1]);
        $info = $response->info();
        $this->assertSame($info['method'], 'GET');
        $this->assertSame($info['endpoint'], 'rooms/1/tasks/1');
    }

    public function testGetRoomFiles()
    {
        $response = $this->client->getRoomFiles(['room_id' => 1]);
        $info = $response->info();
        $this->assertSame($info['method'], 'GET');
        $this->assertSame($info['endpoint'], 'rooms/1/files');
    }

    public function testPostRoomFiles()
    {
        touch('test.txt');
        $response = $this->client->postRoomFiles(['room_id' => 1, 'file' => fopen('test.txt', 'r'), 'message' => 'test']);
        $info = $response->info();
        $this->assertSame($info['method'], 'POST');
        $this->assertSame($info['endpoint'], 'rooms/1/files');
    }

    public function testGetRoomFile()
    {
        $response = $this->client->getRoomFile(['room_id' => 1, 'file_id' => 1]);
        $info = $response->info();
        $this->assertSame($info['method'], 'GET');
        $this->assertSame($info['endpoint'], 'rooms/1/files/1');
    }

    public function testGetRoomLink()
    {
        $response = $this->client->getRoomLink(['room_id' => 1]);
        $info = $response->info();
        $this->assertSame($info['method'], 'GET');
        $this->assertSame($info['endpoint'], 'rooms/1/link');
    }

    public function testPostRoomLink()
    {
        $response = $this->client->postRoomLink(['room_id' => 1]);
        $info = $response->info();
        $this->assertSame($info['method'], 'POST');
        $this->assertSame($info['endpoint'], 'rooms/1/link');
    }

    public function testPutRoomLink()
    {
        $response = $this->client->putRoomLink(['room_id' => 1]);
        $info = $response->info();
        $this->assertSame($info['method'], 'PUT');
        $this->assertSame($info['endpoint'], 'rooms/1/link');
    }

    public function testDeleteRoomLink()
    {
        $response = $this->client->deleteRoomLink(['room_id' => 1]);
        $info = $response->info();
        $this->assertSame($info['method'], 'DELETE');
        $this->assertSame($info['endpoint'], 'rooms/1/link');
    }

    public function testGetIncomingRequests()
    {
        $response = $this->client->getIncomingRequests();
        $info = $response->info();
        $this->assertSame($info['method'], 'GET');
        $this->assertSame($info['endpoint'], 'incoming_requests');
    }

    public function testPutIncomingRequest()
    {
        $response = $this->client->putIncomingRequest(['request_id' => 1]);
        $info = $response->info();
        $this->assertSame($info['method'], 'PUT');
        $this->assertSame($info['endpoint'], 'incoming_requests/1');
    }

    public function testDeleteIncomingRequest()
    {
        $response = $this->client->deleteIncomingRequest(['request_id' => 1]);
        $info = $response->info();
        $this->assertSame($info['method'], 'DELETE');
        $this->assertSame($info['endpoint'], 'incoming_requests/1');
    }
}