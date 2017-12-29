<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Doc extends CI_Controller
{
    public function index()
    {
        $data['title'] = 'Freeswitch API Docs for BOC CRM';
        $this->load->view('templates/header', $data);
        $this->load->view('templates/body');
        $this->load->view('doc/main');
        $this->load->view('templates/footer');
    }

}