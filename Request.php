<?php
/**
 * @license MIT
 */

namespace vitalik74\request;

use Exception;

/**
 * Simple class implement request with JSON body.
 * Example with use:
 * ~~~
 * $response = (new Request(['url' => 'Url ', 'postData' => 'post data']))->getResponse();
 * ~~~
 * @author Tsibikov Vitaliy <tsibikov_vit@mail.ru> <tsibikov.com>
 * @package vitalik74\request
 */
class Request
{
    /**
     * Data. May be string, array
     * @var mixed
     */
    public $postData = null;
    /**
     * Url to response.
     * @var string
     */
    public $url = null;
    /**
     * POST or GET
     * @var string
     */
    public $method = 'POST';
    /**
     * Convert response from server to array with json_decode function
     * @var bool
     */
    public $toArray = true;
    /**
     * If you no set data set this property to true
     * @var bool
     */
    public $noData = false;
    /**
     * Send to server with json format (add special header)
     * @var bool
     */
    public $sendToJsonFormat = true;

    /**
     * Constructor
     * @param array $properties - the property given in name-value pairs
     * @throws Exception
     */
    function __construct(array $properties)
    {
        foreach ($properties as $name => $value) {
            if (property_exists($this, $name)) {
                $this->$name = $value;
            } else {
                throw new Exception('No property ' . $name . ' in class.');
            }
        }
    }

    /**
     * Get responce from server
     * @return mixed
     */
    public function getResponse()
    {
        try {
            if (!empty($this->sendToJsonFormat)) {
                if (is_string($this->postData)) {
                    $validJson = json_decode($this->postData);

                    if (!empty($validJson)) { // it is valid JSON
                        $postDataJson = $this->postData;
                    } else {
                        $postDataJson = json_encode($this->postData);
                    }
                } else {
                    $postDataJson = json_encode($this->postData);
                }
            } else {
                $postDataJson = $this->postData;
            }
            //@todo replace $postDataJson to $postData
            $this->checkProperties($postDataJson);
            $responseData = $this->checkCurl() ? $this->getResponseWithCurl($postDataJson) : $this->getResponseWithGetContents($postDataJson);

            return $responseData;
        } catch (Exception $e) {
            echo "Caught Exception ('{$e->getMessage()}')\n{$e}\n";
        }
    }

    /**
     * Check to correct propertys
     * @param $postDataJson
     * @throws Exception
     */
    private function checkProperties($postDataJson)
    {
        if ((empty($this->postData) || empty($postDataJson)) && empty($this->noData)) {
            throw new Exception('`postData` it is not correct.');
        }

        if (empty($this->method) || !in_array($this->method, ['POST', 'GET'])) {
            throw new Exception('`method` it is not be null or in `POST`, `GET`');
        }

        if (empty($this->url)) {
            throw new Exception('`url` it is not be null.');
        }
    }

    /**
     * Curl is installed in system?
     * @return bool
     */
    private function checkCurl()
    {
        return function_exists('curl_version');
    }

    /**
     * Get data from Curl
     * @param $postDataJson
     * @return mixed
     */
    private function getResponseWithCurl($postDataJson)
    {
        $ch = curl_init($this->getCorrectUrl());
        $options = array(
            CURLOPT_POST => $this->method == 'POST' ? true : false,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_POSTFIELDS => $postDataJson
        );

        if (!empty($this->sendToJsonFormat)) {
            $options = array_merge($options, array(CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            )));
        }

        curl_setopt_array($ch, $options);
        $response = curl_exec($ch);
        $responseData = $this->convertData($response);

        return $responseData;
    }

    /**
     * Get data from file_get_contents
     * @param $postDataJson
     * @return mixed
     */
    private function getResponseWithGetContents($postDataJson)
    {
        $options = array(
            'method' => $this->method,
            'content' => $postDataJson
        );

        if (!empty($this->sendToJsonFormat)) {
            $options = array_merge($options, array('header'  => "Content-type: application/json\r\n",));
        }

        $context = stream_context_create(array(
            'http' => $options
        ));
        $response = file_get_contents($this->getCorrectUrl(), false, $context);
        $responseData = $this->convertData($response);

        return $responseData;
    }

    /**
     * Convert response from server to array with json_decode function
     * @param $response
     * @return mixed
     */
    private function convertData($response)
    {
        return $this->toArray ? json_decode($response, true) : $response;
    }

    /**
     * @return string
     */
    private function getCorrectUrl()
    {
        return $this->method == 'GET' ? $this->url . '?' . join('&', array_map(function ($v, $k) { return $k . '=' . $v; }, $this->postData, array_keys($this->postData))) : $this->url;
    }
}