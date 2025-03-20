<?php

namespace App\Controllers\Supreme_court;
use App\Controllers\BaseController;
use CodeIgniter\Controller;
class DefaultController extends BaseController
{

    function __construct()
    {
    }
    public function index()
    {
        $data['pageTitle']='Dashboard';
        return view('templates/dashboard',$data);
    }
    public function case_status(){
        pr('dsfds');
    }


}
