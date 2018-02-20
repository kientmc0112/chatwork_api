<?php

namespace Kitchenu\Chatwork;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\ClientException;
use Kitchenu\Chatwork\Exception\BadRequestExceotion;
use Kitchenu\Chatwork\Exception\AuthorizedExceotion;
use Kitchenu\Chatwork\Exception\ChatworkException;

/**
 * @method mixed get(string $endpoint, array $params = [], bool $assoc = false)
 * @method mixed put(string $endpoint, array $params = [], bool $assoc = false)
 * @method mixed post(string $endpoint, array $params = [], bool $assoc = false)
 * @method mixed delete(string $endpoint, array $params = [], bool $assoc = false)
 */
class Client
{
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
    protected $url = 'https://api.chatwork.com/v2/';

    /**
     * Clients accept an array of constructor parameters.
     *
     * @param string $token
     * @param array $options
     */
    public function __construct($token, array $options = [], HttpClient $httpClient = null)
    {
        $this->token = $token;

        $this->httpClient = $httpClient ?: new HttpClient($this->httpOptionsDefaults($options));
    }

    /**
     * @param array $options
     */
    protected function httpOptionsDefaults(array $options)
    {
        $defaults = [
            'base_uri' => $this->url,
            'headers' => [
                'User-Agent' => 'chatwork-php',
                'Accept'     => 'application/json',
            ],
        ];

        return $options + $defaults;
    }

    /**
     * 
     * @param  string $method
     * @param  string $endpoint
     * @param  array  $params
     * @param  bool   $assoc
     * @return mixed
     * @throws BadRequestExceotion
     * @throws AuthorizedExceotion
     * @throws ChatworkException
     */
    public function request($method, $endpoint, array $params = [], $assoc = false)
    {
        $method = strtoupper($method);

        $options = $this->requestOptions($method, $params);

        try {
            $json = $this->httpClient
                        ->request($method, $endpoint, $options)
                        ->getBody()
                        ->getContents();
        } catch (ClientException $e) {
            if ($e->getCode() == 400) {
                throw new BadRequestExceotion($e);
            } elseif ($e->getCode() == 401) {
                throw new AuthorizedExceotion($e);
            }
            throw new ChatworkException($e);
        }

        return json_decode($json, $assoc);
    }

    /**
     * @param string $method
     * @param array  $params
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
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param string $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * @return HttpClient
     */
    public function getHttpClient()
    {
        return $this->httpClient;
    }

    public function __call($method, $args)
    {
        if (count($args) < 1) {
            throw new \InvalidArgumentException('Magic request methods require a URI and optional options array');
        }

        $endpoint = $args[0];
        $params = isset($args[1]) ? $args[1] : [];
        $assoc = isset($args[2]) ? $args[2] : false;

        return $this->request($method, $endpoint, $params, $assoc);
    }
}