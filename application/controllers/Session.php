<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Session extends CI_Controller
{
    public function index()
    {
        $this->load->view('session/destroy');
    }

    public function login()
    {
        if ($_POST['username'] == 'wrtcadmin' && $_POST['password'] == 'Espada1328%$') {
            session_start();
            $_SESSION['active'] = 'yes';
            header('location: ../extensions');
        } else {
            header('location: ../');
        }
    }
}