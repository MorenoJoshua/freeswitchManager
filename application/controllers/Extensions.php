<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Extensions extends CI_Controller
{
    private function _r_auth($key = null, $secret = null)
    {
        $e = 'error';
//        (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') | $_SERVER['SERVER_PORT'] == 443 ?: die('{"error": "Not a secure connection"}');
        $key == null ? die('{"error": "No Key"}') : false;
        $secret == null ? die('{"error": "No Secret"}') : false;
        $key != null & $secret != null ?: die('{"error": "Something is missing"}');
        $this->load->database();
        $this->_dump();
        $res = $this->db->where(['key' => $key])
            ->get('cardiff.auth')->result();
        count($res) === 1 && password_verify($secret, $res[0]->secret) ?: die('{"error": "Authentication error"}');
        $_POST['secret'] = 'ok';
        $_POST['server'] = [
            'HTTP_ORIGIN' => @$_SERVER["HTTP_ORIGIN"],
            'HTTP_USER_AGENT' => @$_SERVER["HTTP_USER_AGENT"],
            'CONTENT_TYPE' => @$_SERVER["CONTENT_TYPE"],
            'REMOTE_ADDR' => @$_SERVER["REMOTE_ADDR"],
            'REMOTE_PORT' => @$_SERVER["REMOTE_PORT"],
            'REQUEST_METHOD' => @$_SERVER["REQUEST_METHOD"],
            'QUERY_STRING' => @$_SERVER["QUERY_STRING"],
            'REQUEST_TIME_FLOAT' => @$_SERVER["REQUEST_TIME_FLOAT"],
            'REQUEST_TIME' => @$_SERVER["REQUEST_TIME"],
        ];
        $this->db->insert('cardiff.api_access', ['dump' => json_encode($_POST), 'who' => $res[0]->email]);
        return $auth_ok = $res[0]->email;
    }

    public function crm()
    {
        $r = $_REQUEST;
        header('Access-Control-Allow-Origin: *');
        header('Content-type: text/json');
        isset($r['function']) ?: die('{"result": "error", "message": "Function is not defined"}');
        $e = $this->_r_auth(@$r['key'], @$r['secret']);
        empty($r['function']) | $r['function'] == '' ? die('{"result": "error", "message": "Function is not defined"}') : null;
        $r['function'] == 'create' ? $this->_create(@$r['nickname'], @$r['email'], @$r['did'], @$r['ext'], @$r['extpw'], @$r['vmpw'], @$r['pan'], @$r['poly']) : null;
        $r['function'] == 'change' ? $this->_change($r['from'], $r['to']) : null;
        $r['function'] == 'delete' ? $this->_delete($r['ext']) : null;
        $r['function'] == 'available' ? $this->_available($r['ext']) : null;
        $r['function'] == 'next' ? $this->_nextavailable() : null;
        $r['function'] == 'did_available' ? $this->_did_available() : null;
        $r['function'] == 'update_vm_pass' ? $this->_updateVmPassword($r['ext'], $r['vmpw']) : null;
        echo $r['function'] == 'test' ? json_encode($r) : null;
        $this->_rebuild();
    }

    public function index()
    {
        $this->load->database();
        $this->load->view('session/check');
        $this->load->view('templates/header');
        $this->load->view('templates/body');
        $this->load->view('templates/nav');

        $select = ['number_alias', 'extension', 'domain_uuid', 'extension_uuid'];
        $where = ['length(extension)' => '3',];

        $data['res'] = $this->db
            ->select($select)
            ->where($where, false)
            ->order_by('extension', 'asc')
            ->get('fusionpbx.v_extensions')->result();

        $this->load->view('extensions', $data);
        $this->load->view('modals', $data);

        $this->load->view('templates/footer');
    }

    private function _rebuild()
    {
        $cmd = 'curl localhost/extension_manager/ivr/rivr';
        exec($cmd);
    }

    public function ch()
    {
        $this->load->database();
        $this->load->view('session/check');
        $this->load->view('templates/header');
        $this->load->view('templates/body');
        $this->load->view('templates/nav');
        $this->load->view('ch');
        $this->load->view('templates/footer');
    }

    private function _create($nick, $email, $did, $ext = null, $extpw, $vmpw, $pan, $poly)
    {
        $ext != null ?: $this->_nextavailable();
        $diearr = '';
        $diearr .= isset($nick) ? '' : "Nickname\\n";
        $diearr .= isset($email) ? '' : "Email\\n";
        $diearr .= isset($did) ? '' : "DID\\n";
//        $diearr .= isset($ext) ? '' : "Extension\\n";
        $diearr .= isset($extpw) ? '' : "Extension Password\\n";
        $diearr .= isset($vmpw) ? '' : "Voicemail Password\\n";
        strlen($diearr) == 0 ?: die('{"result": "error", "message":"Fields Missing:\\n' . $diearr . '"}');

        $this->load->helper('uuid');
        $this->load->helper('extension');
        $this->load->database();
        $e_to_c = ['', '1', '999', '888'];

        (isset($pan) & $pan == 1) ? array_push($e_to_c, '3') : false;
        (isset($poly) & $poly == 1) ? array_push($e_to_c, '2') : false;

        $extstocreate = join('","', $e_to_c);
        $rownums = $this->db->where('extension in ', "(\"$extstocreate\")", false)
            ->get('fusionpbx.v_extensions')->num_rows();
        if ($rownums > 0) {
            echo json_encode(['return' => 'error', 'message' => "$rownums extensions already exist"]);
        } else {
            foreach ($e_to_c as $extTgt) {
                $query = createExtensionQuery($nick, gen_uuid(), $extTgt . $ext, $ext, $extpw, $did, $nick);
                $this->db->simple_query($query);
                $query = createVoicemailQuery($nick, $extTgt . $ext, $email, $vmpw, gen_uuid());
                $this->db->simple_query($query);
            }
            echo json_encode(['return' => 'success', 'message' => "Extensions created", 'data' => ['extension' => $ext]]);
        }

    }

    private function _delete($extension = null)
    {
        $extension != null || strlen($extension) < 3 ?: die();
        $this->load->database();
        $exts = ['', '1', '2', '3', '999', '888'];

        $extstodelete = '("' . join($extension . '","', $exts) . $extension . '")';
        $q1 = <<<MYSQL
DELETE FROM `fusionpbx`.`v_extensions` WHERE extension in $extstodelete AND domain_uuid = 'dd9b5af7-2f29-42b1-a21c-8ca2339748dc';

MYSQL;
        $q2 = <<<MYSQL
DELETE FROM `fusionpbx`.`v_voicemails` WHERE voicemail_id in $extstodelete AND domain_uuid = 'dd9b5af7-2f29-42b1-a21c-8ca2339748dc';

MYSQL;
        $this->db->query($q1);
        $this->db->query($q2);

        echo '{"result": "success", "message": "Extensions deleted"}';
    }

    private function _change($from = null, $to = null)
    {
        $to != null & $from != null ?: die('Something is missing');
        $this->load->database();
        $prefs = ['', '1', '2', '999', '888'];
        $check = '';
        foreach ($prefs as $pref) {
            $q = "update fusionpbx.v_extensions e set e.accountcode = {$pref}{$to}, e.extension = {$pref}{$to} where extension = {$pref}{$from};";
            $q2 = "update fusionpbx.v_voicemails set voicemail_id = {$pref}{$to} where voicemail_id = {$pref}{$from};";
            $check .= $this->db->simple_query($q) ? 'E' : 'e';
            $check .= $this->db->simple_query($q2) ? 'V' : 'v';
        }
        return '{"result": "success", "message": "Extensions modified"}';
    }

    private function _available($ext = null)
    {
        $ext != null ?: die("Something is missing");
        $where = ['extension' => $ext];
        $res = $this->db->where($where)
            ->get('fusionpbx.v_extensions')
            ->result_array();
        $check = count($res);
        $datapar = $check == 1 ? 'false' : 'true';
        $is = $check == 0 ? '' : ' not';
        echo '{"result": "' . $datapar . '", "message": "Extension is' . $is . ' available"}';
    }

    private function _nextavailable()
    {
        $query = <<<MYSQL
SELECT
  MIN(f1.`extension` + 1) AS extension
FROM
  `fusionpbx`.`v_extensions` f1
  LEFT JOIN `fusionpbx`.`v_extensions` f2
    ON f1.`extension` + 1 = f2.`extension`
WHERE f2.`extension` IS NULL
  AND f1.`extension` > 150
MYSQL;
        $res = $this->db->query($query)->result_array();
        echo $res[0]['extension'];
    }

    private function _did_available()
    {
        $this->load->database();
        $used_dids = array_filter(json_decode(file_get_contents('http://crm.crdff.net/crm/crm_web_services/used_dids.php')));
        $res = $this->db->select('did')
            ->where('did not in ', '(' . join(',', $used_dids) . ')', false)
            ->get('cardiff.dids')
            ->result_array();
        foreach ($res as $k => $v) {
            @$toecho['available_dids'][] = $v['did'];
        }
        echo json_encode($toecho);
    }

    public function createauth()
    {
        $email = @$_REQUEST['email'] ?: die('Email missing.');
        $this->load->helper('uuid');

        $user = gen_uuid();
        $auth = str_ireplace('-', '', gen_uuid() . gen_uuid());
        $authhash = password_hash($auth, 1);

        $this->load->database();
        $insert = [
            'key' => $user,
            'secret' => $authhash,
            'email' => $email,
        ];
        if ($this->db->insert('cardiff.auth', $insert)) {
            echo <<<HTML
        Auth User: <input type="text" value="$user" style="width: 500px" onclick="this.select()"><br>
        Auth Key: <input type="text" value="$auth" style="width: 500px" onclick="this.select()"><br>

HTML;

        }
    }

    public function active()
    {
        header('Content-Type: text/json');
        $this->load->database();

        $res = $this->db
            ->select('presence_id, callstate, uuid')
//            ->from()
            ->get('freeswitch.basic_calls')
            ->result_array();

        foreach ($res as $row) {
            $user = strtok($row['presence_id'], '@');
            $user = strlen($user) == 4 && substr($user, 0, 1) != '1' ? substr($user, 1) : $user;
            $state = $row['callstate'];
            $lines[] = ['user' => $user, 'status' => $state, 'uuid' => $row['uuid']];
        }
        echo json_encode(@$lines);
    }

    public function listen()
    {

        if (!$_SERVER['HTTPS']) {
            header('location: https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
        }
        $this->load->database();
        $con = new PDO('mysql:host=crm.crdff.net', 'josh', 'espada98');

        $query = <<<MYSQL
select nickname, ext from crm.users where status = 1 and ext between 100 and 999 group by nickname order by nickname asc
MYSQL;

        $data['users'] = $con->query($query)->fetchAll();
//        $this->load->view('session/check');
        $this->load->view('templates/header');
        $this->load->view('templates/body');
        $this->load->view('templates/nav');
        $this->load->view('listen', $data);
        $this->load->view('templates/footer');
    }

    public function dialplan($search_field = 1, $search_param = 1)
    {
        $this->load->database();
        $data['routes'] = $this->db
            ->where([$search_field => $search_param])
            ->join('fusionpbx.v_dialplan_details', 'v_dialplan_details.dialplan_uuid = v_dialplans.dialplan_uuid')
            ->order_by('dialplan_order', 'asc')
            ->get('`fusionpbx`.`v_dialplans`')->result_array();
        $this->load->view('templates/header');
        $this->load->view('templates/body');
        $this->load->view('templates/nav');
        $this->load->view('dialplan/main', $data);
        $this->load->view('templates/footer');
    }

    public function inbound_overview()
    {
        $this->load->database();
//        Inbound routes on freewitch
        $fsraw = $this->db
            ->select('dialplan_description, dialplan_number, dialplan_detail_type, dialplan_detail_data')
            ->where(['dialplan_context' => 'public'])
            ->join('fusionpbx.v_dialplan_details', 'v_dialplan_details.dialplan_uuid = v_dialplans.dialplan_uuid')
            ->order_by('dialplan_number', 'asc')
            ->get('`fusionpbx`.`v_dialplans`')->result_array();
        $fsroutes = [];
        foreach ($fsraw as $row) {
            $fsroutes[$row['dialplan_number']]['name'] = $row['dialplan_description'];
            if ($row['dialplan_detail_type'] == 'transfer') {
                $fsroutes[$row['dialplan_number']]['to'] = $row['dialplan_detail_data'];
            }
            if ($row['dialplan_detail_type'] == 'destination_number') {
                $fsroutes[$row['dialplan_number']]['number'] = $row['dialplan_detail_data'];
            }
        }
        $data['fsroutes'] = $fsroutes;

//        Inbound routes defined on DIDs file
        require_once '/var/www/html/api/dids.php';
        ksort($did);
        $data['did'] = $did;

//        Numbers specified on CRM
        $crm = new PDO('mysql:host=crm.crdff.net', 'josh', 'espada98');
        $data['crm'] = $crm->query('select nickname, did, ext from crm.users where status = 1 and level in (1, 3) and did > 1000000000 order by did asc')->fetchAll();

        $this->load->view('templates/header');
        $this->load->view('templates/body');
        $this->load->view('dialplan/inbound', $data);
        $this->load->view('templates/footer');

    }

    public function users()
    {
        $crm = new PDO('mysql:host=crm.crdff.net', 'josh', 'espada98');
        $res = $crm->query('SELECT id, `name`, lastname, email, username, ext, did, location, `position` FROM crm.`users` WHERE STATUS = 1 AND LEVEL IN (1,3) and ext between 99 and 1000;')->fetchAll(PDO::FETCH_ASSOC);
        $users = '';
        foreach ($res as $row) {
            @$users .= <<<HTML
<tr>
    <td>{$row['id']}</td>
    <td>{$row['name']}</td>
    <td>{$row['lastname']}</td>
    <td>{$row['email']}</td>
    <td>{$row['username']}</td>
    <td>{$row['ext']}</td>
    <td>{$row['did']}</td>
    <td>{$row['location']}</td>
    <td>{$row['position']}</td>
</tr>

HTML;

        }

        $data['table'] = $users;
        $this->load->view('templates/header');
        $this->load->view('templates/body');
        $this->load->view('templates/nav');
        $this->load->view('users', $data);
        $this->load->view('templates/footer');
    }

    public function calls($ext = null)
    {


        require_once 'MysqliDb.php';
        header('Content-type: text/json');
        $e = $ext != null ? '' . $ext : '1';
        $f = $ext != null ? 'presence_id regexp' : '1';
        $db = new MysqliDb('localhost', 'root', '');


        $res = $db
            ->where($f, $e)
            ->get('freeswitch.basic_calls');

        echo json_encode($res);
    }

    public function uuid($ext = '.*')
    {
        $this->load->database();
        $select = [
            'uuid',
            'presence_id',
        ];
        $where = [
            'presence_id regexp' => $ext,
        ];
        $res = $this->db->select($select)->where($where)->get('freeswitch.basic_calls')->result_array();
        echo json_encode($res);
    }

    public function rollover($did = null)
    {
        $did != null ?: die('DID Missing!!');


// Checking if there is a did/ext combination in the crm users database
        $crm = new PDO('mysql:host=crm.crdff.net', 'josh', 'espada98');

        $query = <<<MYSQL
select ext, did from crm.users where did = '$did';
MYSQL;
        $userexists = $crm->query($query)->fetchAll(PDO::FETCH_ASSOC);

        if (count($userexists) != 0) {
            $ext = $userexists[0]['ext'];
        } else {
            die('3333');
        }


        $db = new PDO('mysql:host=localhost', 'root', '');
        function generateQuery($extToCheck)
        {
            $query = <<<MYSQL
SELECT c.`presence_id` FROM `freeswitch`.`channels` c WHERE `presence_id` REGEXP '^{$extToCheck}@.*'
MYSQL;
            return $query;
        }


        $first = $db->query(generateQuery($ext))->fetch();
        $second = $db->query(generateQuery('999' . @$ext))->fetch();
        $third = $db->query(generateQuery('888' . @$ext))->fetch();

        if (!$second) {
            echo '999' . $ext;
            exit;
        }
        if (!$third) {
            echo '888' . $ext;
            exit;
        }

    }

    private function _updateVmPassword($ext, $password)
    {
        isset($ext) ?: die('no extension');
        isset($password) ?: die('no new password');
        $this->load->database();
        $this->load->helper('extension');
        updateVoicemailPasswordQuery($ext, $password);
    }

    private function _dump()
    {
        $this->load->database();
        $dump = json_encode($_REQUEST);
        $sql = <<<MYSQL
insert into cardiff.dump (dump) VALUES ('{$dump}')
MYSQL;
        $this->db->query($sql);
    }

    private function _get_info()
    {

    }

    public function last_api($cuantos = 5)
    {
        $this->load->database();
        $res = $this->db
            ->limit($cuantos)
            ->order_by('timestamp', 'desc')
            ->get('cardiff.api_access')
            ->result_array();

        foreach($res as $row){
            $row['dump'] = json_decode($row['dump']);
            echo '<pre>';
            print_r($row);
            echo '</pre><hr>';
        }

    }

}