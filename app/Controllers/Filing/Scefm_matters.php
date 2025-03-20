<?php

namespace App\Controllers\Filing;
use App\Controllers\BaseController;
use App\Models\Filing\Model_scefm_matters;

class Scefm_matters extends BaseController
{
   public $Model_scefm_matters;
    function __construct()
    {
        $this->Model_scefm_matters = new Model_scefm_matters();
        ini_set('memory_limit','1024M');
    }
    public function index()
    {
        $data['app_name']="SC-efm";
        return view('Filing/Scefm_matters/IB_report',$data);
    }
    public function get_all_matters_ib()
    {
        $data['app_name']="SC-efm";
        $data['all_matters']=$this->Model_scefm_matters->get_all_matters_ib();
        return view('Filing/Scefm_matters/IB_report_get_all_matters',$data);
    }
    public function transfer_case()
    {
        $data['app_name']="SC-efm";
        if ($this->request->getMethod() === 'post') {

            $case_type=$this->request->getPost('case_type');
            $diary_no=$this->request->getPost('diary_no');
            $this->validation->setRule('case_type', 'Select Case type', 'required');
            $this->validation->setRule('diary_no', 'Diary number', 'required');

            $data = [
                'case_type'=>$case_type,
                'diary_no'=>$diary_no,
            ];
            if (!$this->validation->run($data)) {
                // handle validation errors
                echo '2#Error! '.$this->validation->listErrors();exit();
            }
            $this->db = \Config\Database::connect();
            $this->db->transStart();
            $all_matters=$this->Model_scefm_matters->transfer_case($case_type,$diary_no);
            $this->db->transComplete();
            echo $all_matters;
            return true;

        }
    }
}
