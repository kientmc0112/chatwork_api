<?php

namespace Kitchenu\Chatwork;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\ClientException;
use InvalidArgumentException;
use Kitchenu\Chatwork\Exception\BadRequestExceotion;
use Kitchenu\Chatwork\Exception\AuthorizedExceotion;
use Kitchenu\Chatwork\Exception\ChatworkException;

/**
 * @method Response getMe()
 * @method Response getMyStatus()
 * @method Response getMyTasks()
 * @method Response getContacts()
 * @method Response getRooms()
 * @method Response postRooms(array $params = [])
 * @method Response getRoom(array $params = [])
 * @method Response putRoom(array $params = [])
 * @method Response deleteRoom(array $params = [])
 * @method Response getRoomMembers(array $params = [])
 * @method Response putRoomMembers(array $params = [])
 * @method Response getRoomMessages(array $params = [])
 * @method Response postRoomMessages(array $params = [])
 * @method Response putRoomMessagesRead(array $params = [])
 * @method Response putRoomMessagesUnread(array $params = [])
 * @method Response getRoomMessage(array $params = [])
 * @method Response putRoomMessage(array $params = [])
 * @method Response deleteRoomMessage(array $params = [])
 * @method Response putRoomMessage(array $params = [])
 * @method Response deleteRoomMessage(array $params = [])
 * @method Response putRoomMessage(array $params = [])
 **/
class Client
{
    const GET_ME                   = 'me';
    const GET_MY_STATUS            = 'my/status';
    const GET_MY_TASKS             = 'my/tasks';
    const GET_CONTACTS             = 'contacts';
    const GET_ROOMS                = 'rooms';
    const POST_ROOMS               = 'rooms';
    const PUT_ROOM                 = 'rooms/{room_id}';
    const DELETE_ROOM              = 'rooms/{room_id}';
    const GET_ROOM                 = 'rooms/{room_id}';
    const GET_ROOM_MEMBERS         = 'rooms/{room_id}/members';
    const PUT_ROOM_MEMBERS         = 'rooms/{room_id}/members';
    const GET_ROOM_MESSAGES        = 'rooms/{room_id}/messages';
    const POST_ROOM_MESSAGES       = 'rooms/{room_id}/messages';
    const PUT_ROOM_MESSAGES_READ   = 'rooms/{room_id}/messages/read';
    const PUT_ROOM_MESSAGES_UNREAD = 'rooms/{room_id}/messages/unread';
    const GET_ROOM_MESSAGE         = 'rooms/{room_id}/messages/{message_id}';
    const PUT_ROOM_MESSAGE         = 'rooms/{room_id}/messages/{message_id}';
    const DELETE_ROOM_MESSAGE      = 'rooms/{room_id}/messages/{message_id}';
    const GET_ROOM_TASKS           = 'rooms/{room_id}/tasks';
    const POST_ROOM_TASKS          = 'rooms/{room_id}/tasks';
    const GET_ROOM_TASK            = 'rooms/{room_id}/tasks/{task_id}';
    const GET_ROOM_FILES           = 'rooms/{room_id}/files';
    const GET_ROOM_FILE            = 'rooms/{room_id}/files/{file_id}';
    const GET_ROOM_LINK            = 'rooms/{room_id}/link';
    const POST_ROOM_LINK           = 'rooms/{room_id}/link';
    const PUT_ROOM_LINK            = 'rooms/{room_id}/link';
    const DELETE_ROOM_LINK         = 'rooms/{room_id}/link';
    const GET_INCOMING_REQUESTS    = 'incoming_requests';
    const PUT_INCOMING_REQUEST     = 'incoming_requests/{request_id}';
    const DELETE_INCOMING_REQUEST  = 'incoming_requests/{request_id}';

    /**
     * @var HttpClient
     */
    protected $httpClient;

    /**
     * @var string 
     */
    protected $token;

    /**
     * @var string
     */
    protected $uri = 'https://api.chatwork.com/v2/';

    /**
     * Clients accept an array of constructor parameters.
     *
     * @param string|null $token
     * @param array|HttpClient $options
     */
    public function __construct($token, $options = [])
    {
        $this->token = $token;

        $this->httpClient = $options instanceof HttpClient ? $options : new HttpClient($this->httpOptionsDefaults($options));
    }

    /**
     * @param array $options
     * @return array
     */
    protected function httpOptionsDefaults(array $options)
    {
        return array_merge([
            'base_uri' => $this->uri,
            'headers' => [
                'User-Agent' => 'chatwork-php',
                'Accept'     => 'application/json',
            ],
        ], $options);
    }

    /**
     * @param string $method
     * @param string $endpoint
     * @param array  $params
     * @return Response
     * @throws BadRequestExceotion
     * @throws AuthorizedExceotion
     * @throws ChatworkException
     */
    public function request($method, $endpoint, array $params = [])
    {
        $method = strtoupper($method);
        $endpoint = $this->buildEndpoint($endpoint, $params);
        $options = $this->requestOptions($method, $params);

        try {
            $response = $this->httpClient->request($method, $endpoint, $options);
        } catch (ClientException $e) {
            if ($e->getCode() == 400) {
                throw new BadRequestExceotion($e);
            } elseif ($e->getCode() == 401) {
                throw new AuthorizedExceotion($e);
            }
            throw new ChatworkException($e);
        }

        return new Response($response, [
            'method' => $method,
            'endpoint' => $endpoint,
            'params' => $params,
            'token' => $this->token,
        ]);
    }

    /**
     * @param string $endpoint
     * @param array  $params
     * @return string
     */
    protected function buildEndpoint($endpoint, array &$params)
    {
        $endpoint = preg_replace('/^\//', '', $endpoint);

        preg_match_all('/\{(.+?)\}/', $endpoint, $matches);

        if (empty($matches[0])) {
            return $endpoint;
        }

        foreach ($matches[1] as $match) {
            if (isset($params[$match])) {
                $endpoint = preg_replace("/\{$match\}/", $params[$match], $endpoint);
                unset($params[$match]);
            } else {
                throw new InvalidArgumentException();
            }
        }

        return $endpoint;
    }

    /**
     * @param string $method
     * @param array  $params
     * @return array
     */
    protected function requestOptions($method, array $params)
    {
        $options = [
            'headers' => [
                'X-ChatWorkToken' => $this->token
            ]
        ];

        if ($method == 'GET') {
            $options['query'] = $params;
        } else {
            $options['form_paramss'] = $params;
        }

        return $options;
    }

    /**
     * @param null|string $token
     * @return string
     */
    public function token($token = null)
    {
        if (is_string($token)) {
            $this->token = $token;
        }

        return $this->token;
    }

    /**
     * @param null|HttpClient $httpClient
     * @return HttpClient
     */
    public function httpClient($httpClient = null)
    {
        if ($httpClient instanceof HttpClient) {
            $httpClient = $this->httpClient;
        }

        return $this->httpClient;
    }

    public function __call($name, $args)
    {
        $chars = preg_split('/([A-Z][a-z]*)/', $name, null, PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE);

        $constant = 'static::' . strtoupper(implode('_', $chars));

        if (!$constant || !defined($constant)) {
            throw new InvalidArgumentException('Class constant does not exist');
        }

        $endpoint = constant($constant);
        $params = isset($args[0]) ? $args[0] : [];

        return $this->request($chars[0], $endpoint, $params);
    }
}