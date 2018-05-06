<?php

namespace yuki\Clients;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions as Options;
use GuzzleHttp\Exception\ConnectException;
use yuki\Clients\Exceptions\InvalidBodyFormatException;

class PendingRequest
{
    /**
     * @var string
     */
    protected $bodyFormat = 'json';

    /**
     * @var array|null
     */
    protected $options = [];

    /**
     * @var array
     */
    protected $defaultOptions = [
        'http_errors' => false,
    ];

    /**
     * @var array
     */
    protected $defaultHeaders = [
        'User-Agent' => 'hime/0.1',
    ];

    /**
     * @var array
     */
    protected $availableBodyFormats = [
        Options::BODY,
        Options::JSON,
        Options::FORM_PARAMS,
        Options::MULTIPART,
    ];

    /**
     * PendingRequest constructor.
     *
     * @param null $options
     * @param null $headers
     */
    public function __construct($options = null, $headers = null)
    {
        $this->options = $options ?? $this->defaultOptions;
        $this->options['headers'] = $headers ?? $this->defaultHeaders;
    }

    /**
     * @param mixed ...$args
     * @return static
     */
    public static function make(...$args)
    {
        return new static(...$args);
    }

    /**
     * @param $options
     * @return self
     */
    public function pokeOptions($options)
    {
        $this->options = $this->mergeOptions($options);

        return $this;
    }

    /**
     * @param $headers
     * @return self
     */
    public function pokeHeaders($headers)
    {
        $this->options = $this->mergeOptions(['headers' => $headers]);

        return $this;
    }

    /**
     * @return \yuki\Clients\PendingRequest
     * @throws \yuki\Clients\Exceptions\InvalidBodyFormatException
     */
    public function asJson()
    {
        return $this->bodyFormat(Options::JSON);
    }

    /**
     * @return \yuki\Clients\PendingRequest
     * @throws \yuki\Clients\Exceptions\InvalidBodyFormatException
     */
    public function asFormParams()
    {
        return $this->bodyFormat(Options::FORM_PARAMS);
    }

    /**
     * @return \yuki\Clients\PendingRequest
     * @throws \yuki\Clients\Exceptions\InvalidBodyFormatException
     */
    public function asMultipart()
    {
        return $this->bodyFormat(Options::MULTIPART);
    }

    /**
     * @param string $url
     * @param array  $queryParams
     * @return \yuki\Clients\Response
     */
    public function get(string $url, array $queryParams = [])
    {
        return $this->send('GET', $url, [
            'query' => $queryParams,
        ]);
    }

    /**
     * @param string $url
     * @param array  $params
     * @return \yuki\Clients\Response
     */
    public function post(string $url, array $params = [])
    {
        return $this->send('POST', $url, [
            $this->bodyFormat => $params,
        ]);
    }

    /**
     * @param string $url
     * @param array  $params
     * @return \yuki\Clients\Response
     */
    public function patch(string $url, array $params = [])
    {
        return $this->send('PATCH', $url, [
            $this->bodyFormat => $params,
        ]);
    }

    /**
     * @param string $url
     * @param array  $params
     * @return \yuki\Clients\Response
     */
    public function put(string $url, array $params = [])
    {
        return $this->send('PUT', $url, [
            $this->bodyFormat => $params,
        ]);
    }

    /**
     * @param string $url
     * @param array  $params
     * @return \yuki\Clients\Response
     */
    public function delete(string $url, array $params = [])
    {
        return $this->send('DELETE', $url, [
            $this->bodyFormat => $params,
        ]);
    }

    /**
     * @param $method
     * @param $url
     * @param $options
     * @return \yuki\Clients\Response
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function send($method, $url, $options)
    {
        try {
            $client = $this->buildClient()->request($method, $url, $this->mergeOptions($options));

            return new Response($client);
        } catch (ConnectException | GuzzleException $exception) {
            throw $exception;
        }
    }

    /**
     * @param $format
     * @return self
     * @throws \yuki\Clients\Exceptions\InvalidBodyFormatException
     */
    protected function bodyFormat($format)
    {
        if (! in_array($format, $this->availableBodyFormats)) {
            throw new InvalidBodyFormatException('Invalid body format');
        }

        $this->bodyFormat = $format;

        return $this;
    }

    /**
     * @return \GuzzleHttp\Client
     */
    protected function buildClient()
    {
        return new Client();
    }

    /**
     * @param mixed ...$options
     * @return array
     */
    protected function mergeOptions(...$options)
    {
        return array_merge_recursive($this->options, ...$options);
    }
}
