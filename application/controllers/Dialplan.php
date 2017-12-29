<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dialplan extends CI_Controller
{
    public function toggle_active()
    {
        $r = $_REQUEST;
        isset($r['uuid']) ?: die('UUID Missing.');
        $this->load->database();
        $where = ['dialplan_uuid' => $r['uuid']];
        $res = $this->db
            ->select('dialplan_enabled')
            ->where($where)
            ->get('`fusionpbx`.`v_dialplans`')
            ->result_array();
        count($res) == 1 ?: die('UUID Error.');
        $upd = $res[0]['dialplan_enabled'] == 'true' ? 'false' : 'true';
        $this->db->update('`fusionpbx`.`v_dialplans`', ['dialplan_enabled' => $upd], $where);
        echo $upd;
    }
}