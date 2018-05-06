<?php

namespace yuki\Clients;

use Psr\Http\Message\ResponseInterface;

class Response
{
    /**
     * @var \Psr\Http\Message\ResponseInterface
     */
    protected $response;

    /**
     * Response constructor.
     *
     * @param \Psr\Http\Message\ResponseInterface $response
     */
    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }

    /**
     * @return string
     */
    public function body()
    {
        return (string) $this->response->getBody();
    }

    /**
     * @return mixed
     */
    public function json()
    {
        return json_decode($this->response->getBody());
    }

    /**
     * @param $header
     * @return string
     */
    public function header($header)
    {
        return $this->response->getHeaderLine($header);
    }

    /**
     * @return int
     */
    public function status()
    {
        return $this->response->getStatusCode();
    }

    /**
     * @return bool
     */
    public function isOk()
    {
        return $this->status() >= 200 && $this->status() < 300;
    }

    public function __toString()
    {
        return $this->body();
    }
}
