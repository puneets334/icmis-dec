<?php

namespace App\Controllers;
class Csrftoken extends BaseController
{
    protected $session;

    function __construct()
    {   $session = session();
        $this->session = \Config\Services::session();
        $this->session->start();
        $security = \Config\Services::security();
        helper(['url', 'form']);
        helper("functions");
        date_default_timezone_set('Asia/Calcutta');
    }
    public function index()
    {
        $response = array(
            'CSRF_TOKEN' =>csrf_token(),// $this->security->get_csrf_token_name(),
            'CSRF_TOKEN_VALUE' =>csrf_hash(),// $this->security->get_csrf_hash()
        );

        $_SESSION['csrf_to_be_checked'] =csrf_hash(); // $this->security->get_csrf_hash();

        echo json_encode($response);
    }
}
