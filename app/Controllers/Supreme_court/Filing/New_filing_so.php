<?php

namespace App\Controllers\Supreme_court\Filing;
use CodeIgniter\Controller;
class New_filing_so extends Controller
{
    protected $session;

    function __construct()
    {   $session = session();
        $this->session = \Config\Services::session();
        $this->session->start();
       // $this->LoginModel = new LoginModel();
        helper(['url', 'form']);
        helper("functions");
        if (!isset($_SESSION['dcmis_user_idd'])) {
            header('Location:'.base_url('Signout'));exit();
        }
    }

    public function index()
    {
        echo 'dashboard';
        echo '<pre>'; print_r($_SESSION);exit();
        $data['pageTitle']='Dashboard';
        echo view('templates/index',$data);

    }
}
