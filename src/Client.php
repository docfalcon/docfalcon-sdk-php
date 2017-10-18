<?php

namespace DocFalcon;

use \GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Exception\TransferException;

class Client implements ApiInterface
{
    /** @var string */
    const API_URL = 'https://www.docfalcon.com/api/v1/pdf';

    /** @var string */
    private $apikey;
    /** @var Client */
    private $client;
    /** @var array */
    private $headers;

    public function __construct(GuzzleHttpClient $client, $apikey)
    {
        $this->apikey = $apikey;
        $this->headers = array(
            'Accept' => 'application/json, application/pdf'
        );
        $this->client = $client;
    }

    /**
     * @inheritdoc
     */
    public function generate($document, $path)
    {
        if (null == $document) {
            throw new Exception('Missing document');
        }
        if (null == $path) {
            throw new Exception('Missing pdf save path');
        }
        $url = self::API_URL  . '?' . http_build_query(array('apikey' => $this->apikey));
        try
        {
            $response = $this->client->post($url, array(
                'headers' => $this->headers,
                'json' => $document,
                'http_errors' => false,
            ));
            if ($response->getStatusCode() != 200) {
                $body = json_decode($response->getBody());
                throw new Exception($body->message);
            }
            file_put_contents($path, $response->getBody());
            return $response;
        }
        catch (TransferException $e)
        {
            throw new Exception($e->getMessage());
        }
    }
}
