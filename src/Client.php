<?php

namespace Assemble\PHPTrint;

use Assemble\PHPTrint\RequestValidator;

/**
* Trint Client class
* Handles calls to and from Trint API
*
* @see https://dev.trint.com/reference
*/
class Client {

    private $apiKey;
    private $apiUrl = "https://api.trint.com/";
    private $uploadUrl = "https://upload.trint.com/";
    private $httpClient;
    private $requestMap;

    /**
     * Bootstrap the trint object.
     *
     * @param array $config containing overrides
     *
     * @return void
     */
    public function __construct($config) {
        
        // validate api key is set
        if(!isset($config['api-key']) || empty($config['api-key'])) {
            throw new \BadMethodCallException("PHPTrint Client expects 'api-key' to be provided, none present");
        }
        $this->apiKey = $config['api-key'];
        
        // set url if not set
        if(isset($config['api-url'])) {
            $this->apiUrl = $config['api-url'];
        }

        // set upload url if not set
        if(isset($config['upload-url'])) {
            $this->uploadUrl = $config['upload-url'];
        }

        // bootstrap the internals
        $this->httpClient = new \GuzzleHttp\Client();
        $this->requestMap = require('request_map.php');
    }

    /**
     * list transcripts available
     *
     * @param int $limit quantity returned in list
     * @param int $skip starting position of response in list
     *
     * @return array
     */
    public function list($limit = NULL, $skip = NULL) {

        $requestParams = [];
        if(isset($limit)) {
            $requestParams['limit'] = $limit;
        }
        if(isset($skip)) {
            $requestParams['skip'] = $skip;
        }

        $payload = [
            'headers'   =>  [
                'api-key'   =>  $this->apiKey,
                'Accept'    =>  'application/json'
            ],
            'query'     =>  $requestParams
        ];

        $res = $this->httpClient->request('GET', $this->apiUrl."transcripts", $payload);
        $response = json_decode($res->getBody(), true);

        return $response;

    }

    /**
     * get the requested transcript by id
     *
     * @param string $trintId Trint ID of file
     * @param string $format the format to return
     * @param array $params additional request parameters to send
     * @param bool $returnUrl return the S3 url from trint response rather than content itself
     *
     * @throws BadMethodCallException
     *
     * @return array
     */
    public function get($trintId, $format = 'json', $params = [], $returnUrl = false) {

        if(!isset($trintId) || empty($trintId)) {
            throw new \BadMethodCallException("Missing required parameter trintId");
        }
        if(!in_array($format, array_keys($this->requestMap['export']))) {
            throw new \BadMethodCallException("Invalid format provided. Available: ".implode(",", array_keys($this->requestMap['export'])));
        }

        // run validator
        $this->validate($params, $this->requestMap['export'][$format]['params']);

        // compile payload
        $payload = [
            'headers'   =>  [
                'api-key'       =>  $this->apiKey,
                'Accept'        =>  'application/json',
                'Content-Type'  =>  'application/json',
            ]
        ];
        $payload_position = ($this->requestMap['export'][$format]['method'] == 'GET' ? 'query' : 'body');
        $payload[$payload_position] = ($payload_position == 'body' ? json_encode($params) : $params );

        // send request
        $res = $this->httpClient->request(
            $this->requestMap['export'][$format]['method'], 
            $this->apiUrl.implode("/",["export",$format,$trintId]), 
            $payload
        );
        $response = json_decode($res->getBody(), true);

        // handle returned url if required
        if($this->requestMap['export'][$format]['returns_url'] && $returnUrl == false) {
            // get the actual content
            $res = $this->httpClient->request(
                'GET', 
                $response['url']
            );
            $response = (string)$res->getBody();
        }
        return $response;
    }

    /**
     * validate the parameters before sending
     *
     * @param array $params parameters to be validated
     * @param array $rules rules to validate against
     *
     * @throws \InvalidArgumentException
     *
     * @return void
     */
    private function validate($params, $rules) {
        if(!isset($params) || !isset($rules)) {
            return;
        }
        foreach($params as $param => $value) {
            // valid param name
            if(in_array($param, array_keys($rules))) {
                // invalid type
                if(gettype($value) !== $rules[$param]) {
                    $type1 = $rules[$param];
                    $type2 = gettype($value);
                    throw new \InvalidArgumentException("Invalid parameter type for '$param', expected $type1, got $type2");
                }
            }
        }
        return;
    }

    /**
     * validate the parameters before sending
     *
     * @param string $filePath parameters to be validated
     * @param array $params parameters to send to request
     *
     * @throws \InvalidArgumentException
     *
     * @return void
     */
    public function put($filePath, $params) {

        // run validator
        $this->validate($params, $this->requestMap['upload']['params']);
        $fileData = fopen($filePath, 'r');

        // compile payload
        $payload = [
            'headers'   =>  [
                'api-key'       =>  $this->apiKey
            ],
            'body' => $fileData,
            'query' => $params,
        ];

        // send request
        $res = $this->httpClient->request(
            $this->requestMap['upload']['method'],
            $this->uploadUrl,
            $payload
        );
        $response = json_decode($res->getBody(), true);

        return $response;
    }
}