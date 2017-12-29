<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ivr extends CI_Controller
{
    public function index()
    {
        $this->load->database();

        $data['res'] = $this->db->get('fusionpbx.v_ivr_menus')->result_array();;

        $this->load->view('templates/header');
        $this->load->view('templates/nav');
        $this->load->view('ivr/list', $data);
        $this->load->view('templates/footer');
    }

    public function edit($uuid)
    {
        $this->load->database();

        $data['res'] = $this->db
            ->where('ivr_menu_uuid', $uuid)
            ->order_by('ivr_menu_option_digits', 'asc')
            ->get('fusionpbx.v_ivr_menu_options')
            ->result_array();

        $this->load->view('templates/header');
        $this->load->view('templates/nav');
        $this->load->view('ivr/edit', $data);
        $this->load->view('templates/footer');
    }

    public function rivr()
    {
        $this->load->database();
        $this->load->helper('uuid');
        $crm = new PDO('mysql:host=crm.crdff.net', 'josh', 'espada98');

        $res = $crm->query('select previous_extension as pic, ext from crm.users where status = 1 and position = "Agent"');

//        var_dump($res);
//
//        die();

        $query = '';

        foreach ($res as $crmrow) {


            $numbers = $crmrow['pic'] > 1 ? $crmrow['pic'] : $crmrow['ext'];
            $uuid = gen_uuid();
            $query .= <<<MYSQL
('$uuid','93f96b92-8885-413a-8bdb-06b1b192d064','dd9b5af7-2f29-42b1-a21c-8ca2339748dc','{$numbers}','menu-exec-app','transfer 999{$crmrow['ext']} XML wrtc.crdff.net','1',NULL),
MYSQL;

            if ($crmrow['pic'] != '') {
                $uuid = gen_uuid();
                $query .= <<<MYSQL
('$uuid','93f96b92-8885-413a-8bdb-06b1b192d064','dd9b5af7-2f29-42b1-a21c-8ca2339748dc','{$crmrow['ext']}','menu-exec-app','transfer 999{$crmrow['ext']} XML wrtc.crdff.net','1',NULL),
MYSQL;
            }

        }

        $query = trim($query, ',');

        $finalquery = <<<MYSQL
INSERT INTO fusionpbx.`v_ivr_menu_options` (
  ivr_menu_option_uuid,
  ivr_menu_uuid,
  domain_uuid,
  ivr_menu_option_digits,
  ivr_menu_option_action,
  ivr_menu_option_param,
  ivr_menu_option_order,
  ivr_menu_option_description
)
VALUES
$query
MYSQL;

//        echo $this->db->delete('fusionpbx.v_ivr_menu_options', ['ivr_menu_option_digits >' => '100', 'ivr_menu_option_digits <' => '999',]);
        echo $this->db->query('delete from fusionpbx.v_ivr_menu_options where ivr_menu_option_order = "1"');
        echo $this->db->query($finalquery) ? 'Ok' : 'Die';

    }

    public function groups($where)
    {
        $this->load->database();

        $active_users = $this->db->query('select presence_id from freeswitch.basic_calls')->result_array();
        $active_array = [];
        foreach ($active_users as $row) {
            $active_array[] = substr(preg_replace('/\D/', '', $row['presence_id']), -3);
        }
        array_filter($active_array);
        $notin = join('","', $active_array);
        $query = <<<MYSQL
select ext from crm.users where location regexp "$where" and position = "Agent" and status = 1 and level = 3 and ext not in ("$notin")
MYSQL;

        $db = new PDO('mysql:host=crm.crdff.net', 'josh', 'espada98');
        $exts = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);
        $this->load->database();

        $brige = '';
        foreach ($exts as $row) {
            $brige .= <<<TXT
,verto.rtc/999{$row['ext']}@wrtc.crdff.net
TXT;
        }
        $brige = trim($brige, ',');

        echo $brige;

    }

}
