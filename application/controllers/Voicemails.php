<?php

class Voicemails extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
    }

    public function index()
    {
        $this->load->view('voicemail/greet', array('error' => ' '));
    }

    public function do_upload()
    {

        $config['upload_path'] = $this->vd() . $_REQUEST['ext'] . '/';
        $config['allowed_types'] = 'wav|mp3';
        $config['file_name'] = 'greeting_' . $_REQUEST['no'];

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('userfile') | !isset($_REQUEST['no'])) {
            $error = array('error' => $this->upload->display_errors());

            $this->load->view('voicemail/greet', $error);
        } else {
            $data = array('upload_data' => $this->upload->data());

            $this->load->view('voicemail/greet', $data);
        }
    }

    public function choosegreet($ext = null)
    {
        $ext != null ?: die('no ext selected');

        $dir = $this->vd() . $ext;

        $files = dir($dir) ? scandir($dir) : die('not a valid vm');
        foreach ($files as $k => $v) {
            if (strlen($v) != '14') {
                unset($files[$k]);
            }
        }
        $data['greets'] = $files;
        $data['ext'] = $ext;
        $this->load->view('voicemail/listgreets', $data);
    }

    public function listen($ext = null, $no = null)
    {
        $ext != null ?: die('noext');
        $no != null ?: die('nono');
        copy($this->vd() . $ext . '/greeting_' . $no . '.wav', '/var/www/html/greets/' . $ext . '.wav');
        header('location:http://wrtc.crdff.net/greets/' . $ext . '.wav');
    }

    public function usegreet($ext = null, $no = null){
        $this->load->database();

        $set = [
            'greeting_id' => $no,
        ];
        $where = [
            'voicemail_id regexp' => $ext,
        ];

        echo !$this->db->update('fusionpbx.v_voicemails', $set, $where) ?: 'success' ;
    }

    private function vd()
    {
        return '/var/lib/freeswitch/storage/voicemail/default/wrtc.crdff.net/';
    }

}

?>