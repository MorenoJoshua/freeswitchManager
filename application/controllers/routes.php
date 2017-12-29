<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Routes extends CI_Controller
{
    public function in($did)
    {
        $this->load->database();
        $ext = $this->_efd($did);
        $did == null ? die('No did') : false;
        $act = @$this->db
            ->select('presence_id')
            ->where('presence_id regexp', $ext)
            ->order_by('presence_id', 'desc')
            ->limit(1)
            ->get('freeswitch.basic_calls')
            ->result_array()[0];

        $ext_status = preg_replace('/\D/', '', @$act['presence_id']);

        $this->gen_transfer(@$ext_status, $ext);
    }

    private function gen_transfer($ext = null, $orig)
    {
        $ext == '' ? die($orig) : false;
        strlen($ext) == 3 ? die('999' . $ext) : false;
        substr($ext, 0, 3) == '999' ? die('888' . $ext) : false;
        die('*99' . $orig);
    }

    public function ext_did($did)
    {
        echo $this->_efd($did);
    }

    protected function _efd($did){
        $this->load->database();
        $did = substr($did, -8);
        $db = new PDO('mysql:host=crm.crdff.net', 'josh', 'espada98');
        return $db->query('select ext from crm.users where did regexp "' . $did . '"')->fetchAll(PDO::FETCH_ASSOC)[0]['ext'];
    }
}
