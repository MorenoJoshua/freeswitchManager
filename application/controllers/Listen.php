<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Listen extends CI_Controller
{
    public function index()
    {
        if (!$_SERVER['HTTPS']) {
            header('location: https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
        }
        $this->load->database();
        $con = new PDO('mysql:host=crm.crdff.net', 'josh', 'espada98');

        $query = <<<MYSQL
    select nickname, ext, location, level from crm.users where status = 1 and ext between 100 and 999 group by nickname order by nickname asc
MYSQL;

        $data['users'] = $con->query($query)->fetchAll(PDO::FETCH_ASSOC);

        $this->load->view('templates/header');
        $this->load->view('templates/body');
        $this->load->view('listen', $data);
        $this->load->view('templates/footer');
    }
}