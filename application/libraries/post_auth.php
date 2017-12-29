<?php
$key = @$_REQUEST['key'] ?: die('Access Key Missing');
$auth = @$_REQUEST['secret'] ?: die('Secret key missing');
$this->load->database();
$res = $this->db->get('cardiff.auth', ['key' => $key])->result();
count($res) === 1 ?: die('Authentication error');
password_verify($auth, $res[0]->secret) ?: die('Authentication error');
