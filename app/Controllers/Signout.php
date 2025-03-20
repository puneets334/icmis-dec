<?php

namespace App\Controllers;
use App\Models\LoginModel;
use CodeIgniter\Controller;

class Signout extends Controller
{

    function __construct()
    {

    }

    public function index()
    {
        unset($_SESSION['login']);
        unset($_SESSION['filing_details']);
        unset($_SESSION['login_data']);
        unset($_SESSION['user_section']);
        session()->destroy();
        session()->setFlashdata("message_success", "User logout has been successfully");
        return redirect()->to('Login');

    }

    function redirect_on_login() {
        echo 'redirect_on_login';
        echo '<pre>'; print_r($_SESSION);//exit();
        return redirect()->to('Home');
        //return redirect()->to('dashboard');
    }
}
