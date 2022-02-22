<?php

namespace Kientmc\Chatwork;

use DateTime;
use GuzzleHttp\Psr7\Response as HttpResponse;
use InvalidArgumentException;

class Response
{
    /**
     * @var HttpResponse
     */
    protected $httpResponse;

    /**
     * @var array
     */
    protected $result;

    /**
     * @var array
     */
    protected $info;

    /**
     * @param Response
     */
    public function __construct(HttpResponse $response, array $info)
    {
        $this->httpResponse = $response;
        $this->result = json_decode($response->getBody()->getContents(), true);
        $this->setInfo($response, $info);
    }

    /**
     * @param HttpResponse $response
     * @param array $info
     */
    protected function setInfo(HttpResponse $response, array $info)
    {
        $headers = $response->getHeaders();
        $this->info = array_merge([
            'method'=> '',
            'endpoint'=> '',
            'params'=> [],
            'token'=> '',            
            'date' => new Datetime($headers['Date'][0]),
            'limit' => $headers['X-RateLimit-Limit'][0],
            'remaining' => $headers['X-RateLimit-Remaining'][0],
            'reset' => $headers['X-RateLimit-Reset'][0],
        ], $info);
    }

    /**
     * Get response result 
     *
     * @return array
     */
    public function result()
    {
        return $this->result;
    }

    /**
     * Get endpoint infomation
     *
     * @return array
     */
    public function info()
    {
        return $this->info;
    }

    /**
     * Get Guzzle Response 
     *
     * @return HttpResponse
     */
    public function httpResponse()
    {
        return $this->httpResponse;
    }

    /**
     * @param string $key
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function get($key)
    {
        if (isset($this->result[$key])) {
            return $this->result[$key];
        } elseif (isset($this->info[$key])) {
            return $this->info[$key];
        }

        throw new InvalidArgumentException();
    }

    /**
     * @param string $key
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function __get($key)
    {
        return $this->get($key);
    }
}
