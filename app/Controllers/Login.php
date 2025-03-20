<?php

namespace App\Controllers;
use App\Models\LoginModel;
use App\Models\Menu_model;
use CodeIgniter\Controller;

class Login extends Controller
{
    protected $session;

    function __construct()
    {   $session = session();
        $this->session = \Config\Services::session();
        $security = \Config\Services::security();
        $this->session->start();
        // $this->LoginModel = new LoginModel();
        helper(['url', 'form']);
        helper("functions");
    }

    public function index()
    {
        /*$Menu_model = new Menu_model();
        $Menu_model->get_Main_menus(1);*/
        $_SESSION['login_salt'] =$this->generateRandomString();
        unset($_SESSION['login_data']);
        unset($_SESSION['user_section']);
        //echo '<pre>';print_r($_SESSION);exit();
        return view('login');
    }
    private function generateRandomString($length = 10) {
        // generates random string for login salt
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    public function checkLogin()
    { 
        $validation = \Config\Services::validation();

        $validation->setRules([
            'txtuname' => [
                'label' => 'User Name',
                'rules' => 'required|min_length[1]|max_length[8]',
                'errors' => [
                    'required' => 'The {field} field is required.',
                    'min_length' => '{field} must be at least 1 character long.',
                    'max_length' => '{field} must not exceed 8 characters.',
                ],
            ],
            'txtpass' => [
                    'label' => 'User Password',
                    'rules' => 'required|permit_empty|min_length[1]|max_length[4]',
                    'errors' => [
                        'required' => 'The {field} field is required.',
                        'min_length' => '{field} must be at least 1 character long.',
                        'max_length' => '{field} must not exceed 4 characters.',
                    ],
                ],
            ]);
    
        if ($this->request->getMethod() === 'post' && $validation->withRequest($this->request)->run()) {
           // print_r($_POST);die;
            $username = $this->request->getPost('txtuname');
             $pass_hashed = $this->request->getPost('txtpass_hashed'); 
             $password = $this->request->getPost('txtpass'); 

    
            $LoginModel = new LoginModel();
            $row_foruser = $LoginModel->checkLogin($username, $password, $pass_hashed);
    
            if($row_foruser){
                if ($row_foruser['attend'] == 'A') {
                    //$errorMessage = "User is Marked as Absent";
                    session()->setFlashdata("message_error", 'User is Marked as Absent');
                    return redirect()->to('Login');
                }
                else {
                    $is_login_in_update=$LoginModel->login_in_update($username);
                    if ($is_login_in_update) {
                        $is_login=$LoginModel->get_usertype_detials($row_foruser);
                        $login=session()->get('login');
                        if ($login && !empty($is_login)){
                            //return redirect()->to('Filing/Diary');
                            return redirect()->to('Supreme_court');
                        }else{
                            session()->setFlashdata("message_error", 'Please contact to Computer Cell.');
                        }

                        //return redirect()->to('Supreme_court');
                        // return view('login');
                        // header('Location: index.php');  exit();

                    }else{
                        session()->setFlashdata("message_error", 'Invalid credentials.');
                        //return redirect()->to('Login');
                    }
                }


            } else {
                session()->setFlashdata("message_error", 'Invalid credentials.');
                return redirect()->to('Login');
            }
        } else {
            // Pass validation errors to the view
            return view('login', ['validation' => $validation]);
        }
        $_SESSION['login_salt'] =$this->generateRandomString();
        unset($_SESSION['login_data']);
        unset($_SESSION['user_section']);
        return view('login');
    }
    function redirect_on_login() {
        echo 'redirect_on_login';
        //echo '<pre>'; print_r($_SESSION);//exit();
        return redirect()->to('Home');
        //return redirect()->to('dashboard');
    }
}
