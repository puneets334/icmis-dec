<?php

namespace App\Controllers\Filing;
use CodeIgniter\Controller;

class View extends Controller
{
    protected $session;
    function __construct()
    {   $session = session();
        $this->session = \Config\Services::session();
        $this->session->start();
        helper(['url', 'form']);
        helper("functions");
        helper("common");
        date_default_timezone_set('Asia/Calcutta');
    }

    public function index()
    {
       return view('Filing/filing_view');
    }

}
