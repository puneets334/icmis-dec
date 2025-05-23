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


    public function efiled_pending_matters()
	{
        $usercode = session()->get('login')['usercode'];
        $data['user']=$usercode;
		if(!empty($_POST['from']) && !empty($_POST['to']))
		{

			$from = date('Y-m-d',strtotime($_POST['from']));
			$to = date('Y-m-d',strtotime($_POST['to']));
			$user=$_POST['user'];
            $record['datewise_matters']=$this->Model_scefm_matters->show_datewise_matters($user,$from,$to); 
			return view('Filing/Scefm_matters/Scefm_datewise_matters',$record);

		}else{
 
			  $data['pending_matters'] =$this->Model_scefm_matters->show_sectionmatters($usercode); 
			  return view('Filing/Scefm_matters/Scefm_section_matters',$data);
		}

	}


    public function update_efiled_cases_transfer_status()
	{
		$diary_no = $_POST['dno'];
		$user=$_POST['user'];
		$update = $this->Model_scefm_matters->update_ect_table_status($diary_no,$user);
		if($update)
		{
			// $this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">Updated Successfully !!!</div>');
			echo "<center><span style='color:green;font-size:2em'>Updated Successfully !!!</span></center>";
		}else{
			// $this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">Not Updated Successfully !!!</div>');
			echo  "<center><span style='color:red;font-size:2em'>Not Updated Successfully !!!</span></center>";
		}
	}



}
