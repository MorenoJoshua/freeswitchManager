<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('ACCESS_KEY', '80473469');
define('SECRET_KEY', 'GhDzk8Lc00xUzUjHFJqDqLztMNq5KMgU');

class Test extends CI_Controller
{

    public $res;

    public function index($function = null)
    {
        isset($_POST['method']) & isset($_POST['api_url']) ?: die('missing');


        $_POST['query'] = isset($_POST['query']) ? json_decode($_POST['query'], true) : [];
        $_POST['route'] = isset($_POST['route']) ? json_decode($_POST['route'], true) : [];

        $this->_test(@$_POST['method'], @$_POST['api_url'], @$_POST['query'], @$_POST['route']);

        var_dump($this->res);

    }

    public function t()
    {

        $query = $_POST;

        $method = 'PUT';
        $api_url = '/v1/routes/test_route';
        $this->_put($method, $api_url, $query);

        var_dump($this->res);

    }

    public function _list_routes()
    {

        $query = $_POST;

        $method = 'GET';
        $api_url = '/v1/routes/';
        $this->_test($method, $api_url, $query);

        var_dump($this->res);

    }


    private function _test($method = null, $api_url = null, $query)
    {
        $querystring = http_build_query($query);

        $data = [
            'body' => '',
            'method' => $method,
            'api_url' => $api_url,
            'query_params' => $query,
            'route_params' => $querystring,
        ];

        $headers = [];

        $this->_flow_put($data, $headers);

    }

    private function _flow_put($pa, $extra_headers)
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
        $params = http_build_query(@$pa['query_params']);
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

    private function _get($method = null, $api_url = null, $query)
    {
        $querystring = http_build_query($query);

        $data = [
            'body' => '',
            'method' => $method,
            'api_url' => $api_url,
            'query_params' => $query,
            'route_params' => $querystring,
        ];

        $headers = [];

        $this->_flow_get($data, $headers);

    }

    private function _put($method = null, $api_url = null, $query)
    {
        $querystring = http_build_query($query);

        $data = [
            'body' => '',
            'method' => $method,
            'api_url' => $api_url,
            'query_params' => $query,
            'route_params' => $querystring,
        ];

        $headers = [];

        $this->_flow_get($data, $headers);

    }

    private function _flow_get($pa, $extra_headers)
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
        $params = http_build_query(@$pa['query_params']);
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

    public function dids()
    {
        $crm = new PDO('mysql:host=crm.crdff.net', 'josh', 'espada98');
        $res = $crm->query('SELECT CONCAT(\'1\', did, \' => \', ext,\',\') as a
FROM crm.users
WHERE STATUS = 1
AND LEVEL IN (1,3)
AND did > 10000
AND ext BETWEEN 100 AND 999
GROUP BY did
ORDER BY did ASC')
            ->fetchAll(PDO::FETCH_ASSOC);
        foreach ($res as $k) {
            @$toeval .= $k['a'];
        }
        eval('$did =[' . $toeval . '16192748782 => 105,];');
    }

    public function notification(){
        $this->load->view('test/notification');
    }
}