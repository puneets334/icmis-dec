<?php

namespace App\Controllers\Faster;
use App\Controllers\BaseController;
use App\Models\Court\CourtMasterModel;


class HybridController extends BaseController
{
    protected $CourtMasterModel;
  
    public function __construct()
    {
        // parent::__construct();
        $this->CourtMasterModel = new CourtMasterModel();
  
    }
    public function index(){
    //public function index(){
      //  echo "Hello";
      $usercode = $_SESSION['login']['usercode'];
        $msg='';
        $this->session->set('dcmis_user_idd', $usercode);
        $data['msg']=$msg;
        $data['caseTypes'] = $this->CourtMasterModel->getCaseType();
        $data['usercode'] = $usercode;
        $this->clearFasterSession();
        //var_dump($data['caseDetails']);
        return view('Faster/caseSearch', $data);
    }
   
}
