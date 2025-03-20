<?php

namespace App\Controllers\Filing;

use CodeIgniter\Controller;
use App\Libraries\webservices\Efiling_webservices;
use App\Libraries\webservices\Highcourt_webservices;
use App\Models\LoginModel;

class Earliercourt extends Controller
{
   public $session;
   public $LoginModel;
   public $efiling_webservices;
   public $highcourt_webservices;
   function __construct()
   {
      $session = session();
      $this->session = \Config\Services::session();
      $this->session->start();

      $this->LoginModel = new LoginModel();
      helper(['url', 'form']);
      helper("functions");
      date_default_timezone_set('Asia/Calcutta');
   }

   public function index()
   {
      return view('Filing/diary_search');
   }

   public function earliercourt()
   {
       $diary_no = $this->request->getPost('diary_no');
       $diary_year = $this->request->getPost('diary_year');
       
      return view('Filing/earlier_court');
   }
}
