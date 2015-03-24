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
    public $postData = null;
    public $url = null;
    public $method = 'POST';

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
     * @return mixed
     */
    public function getResponse()
    {
        try {
            $postDataJson = json_encode($this->postData);

            $this->checkPropertys($postDataJson);

            $context = stream_context_create(array(
                'http' => array(
                    'method' => $this->method,
                    'header' => 'Content-Type: application/json',
                    'content' => $postDataJson
                )
            ));
            $response = file_get_contents($this->url, false, $context);
            $responseData = json_decode($response, true);

            return $responseData;
        } catch (Exception $e) {
            echo "Caught Exception ('{$e->getMessage()}')\n{$e}\n";
        }
    }

    /**
     * @param $postDataJson
     * @throws Exception
     */
    private function checkPropertys($postDataJson)
    {
        if (empty($this->postData) || empty($postDataJson)) {
            throw new Exception('`postData` it is not correct.');
        }

        if (empty($this->url)) {
            throw new Exception('`url` it is not be null.');
        }
    }
}