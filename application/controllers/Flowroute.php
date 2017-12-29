<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('ACCESS_KEY', '80473469');
define('SECRET_KEY', 'GhDzk8Lc00xUzUjHFJqDqLztMNq5KMgU');

class Flowroute extends CI_Controller
{

    public $res;

    public function api($function = null)
    {
        $function != null ?: die();
        isset($_POST) ?: die('No post information');
        $function == 'routes_list' ? $this->_routes_list($_POST) : false;
        $function == 'routes_create' ? $this->_routes_create($_POST) : false;

        $function == 'telephone_list' ? $this->_telephone_list($_POST) : false;
        $function == 'telephone_detail' ? $this->_telephone_detail($_POST) : false;

        $function == 'purchase_list' ? $this->_purchase_list($_POST) : false;

        var_dump($this->res);

    }

    private function _routes_list($params)
    {

        $limit = isset($params['limit']) ? $params['limit'] : 200;
        $pages = isset($params['pages']) ? $params['pages'] : 1;

        $data = [
            'body' => '',
            'method' => 'GET',
            'api_url' => '/v1/routes/',
            'params' => [
                'limit' => $limit,
                'page' => $pages,
            ],
        ];

        $headers = array();

        $this->_flow($data, $headers);

    }

    private function _telephone_list($params)
    {

        $limit = isset($params['limit']) ? $params['limit'] : 200;
        $pages = isset($params['pages']) ? $params['pages'] : 1;

        $data = [
            'body' => '',
            'method' => 'GET',
            'api_url' => '/v1/tns/',
            'params' => [
                'limit' => $limit,
                'page' => $pages,
            ],
        ];

        $headers = array();

        $this->_flow($data, $headers);

    }

    private function _telephone_detail($params)
    {

        $telephone_number = isset($params['telephone_number']) ? $params['telephone_number'] : die('No phone provided');

        $data = [
            'body' => '',
            'method' => 'GET',
            'api_url' => '/v1/tns/' . $telephone_number,
            'params' => [
//                'telephone_number' => $telephone_number,
            ],
        ];

        $headers = array();

        $this->_flow($data, $headers);

    }



    private function _purchase_list($params)
    {

        $limit = isset($params['limit']) ? $params['limit'] : 200;
        $pages = isset($params['pages']) ? $params['pages'] : 1;

        $data = [
            'body' => '',
            'method' => 'GET',
            'api_url' => '/v1/available-tns/npanxxs/',
            'params' => [
                'limit' => $limit,
                'page' => $pages,
            ],
        ];

        $headers = array();

        $this->_flow($data, $headers);

    }



    //////////////////////////////////////////////////////////////////////////////

    private function _routes_create($params)
    {
//        isset($params['value']) ?: die('No value');
//        isset($params['type']) ?: die('No type');
//        array_search($params['type'], ['HOST', 'PSTN', 'URI', 'PRIVACY-PSTN', 'SIP-REG']) ?: die('Wrong type');
//        isset($params['route_name']) ?: die('No route name');


        $data = [
            'body' => '',
            'method' => 'PUT',
            'api_url' => "/v1/routes/",
            'params' => [
                'value' => 'Test',
                'type' => 'URI',
                'route_name' => 'Name',
//                'value' => $params['value'],
//                'type' => $params['type'],
//                'route_name' => $params['route_name'],
            ],
        ];

        $headers = [
            'content-type: multipart/form-data; boundary=---011000010111000001101001',
        ];


        $this->_flow($data, $headers);

    }


    private function _flow($pa, $extra_headers)
    {

        $access_key = ACCESS_KEY;
        $secret_key = SECRET_KEY;

        date_default_timezone_set('UTC');
        $timestamp = date('Y-m-d\TH:i:s', time());
        $body = $pa['body'] == '' | $pa['body'] == null | !isset($pa['body']) ? '' : $pa['body'];
        $bodymd5 = $body == '' ? '' : md5($body);
        $method = $pa['method'];
        $url = 'https://api.flowroute.com';
        $api_url = $pa['api_url'];
        $params = http_build_query($pa['params']);
        $canonical_uri = "$url$api_url\n$params";
        $request_uri = "$url$api_url?$params";
        $message_string = "$timestamp\n$method\n$bodymd5\n$canonical_uri";
        $signature = hash_hmac('sha1', $message_string, $secret_key);

        $curl = curl_init();

        $headers = array_merge_recursive([
            "accept: application/json",
            "x-timestamp: $timestamp",
        ], $extra_headers);

//        var_dump($headers);

        curl_setopt_array($curl, array(
            CURLOPT_URL => $request_uri,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPAUTH => CURLAUTH_ANY,
            CURLOPT_USERPWD => "$access_key:$signature",
            CURLOPT_HTTPHEADER => $headers,
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            $this->res = "cURL Error #:" . $err;
            return "cURL Error #:" . $err;
        } else {
            $this->res = $response;
            return $response;
        }

    }
}