<?php

namespace App\Controllers\MasterManagement;
use App\Controllers\BaseController;
use App\Models\MasterManagement\JudgesMoldel;
use CodeIgniter\Controller;
use CodeIgniter\Model;
use App\Models\MasterManagement\IPModel;

 
class JudgesController extends BaseController
{

public $JudgesMoldel;
    function __construct()
    {
        ini_set('memory_limit','51200M'); 
        $this->JudgesMoldel = new JudgesMoldel();
        //error_reporting(0);
    }

   
    public function index($session=null)
    {
      
        $usercode = session()->get('login')['usercode'];
        /*$this->session->set_userdata('dcmis_user_idd', $session);
        echo $usercode=$_SESSION['dcmis_user_idd'];*/
        $jtype= $this->request->getPost('jtype');
        $jcode= $this->request->getPost('jcode');
        $view =  $this->request->getPost('view');
        $first_name= $this->request->getPost('first_name');
        $data['rev_result'] = NULL;
        $title = str_replace("'", "\'", (string) ($this->request->getPost('title') ?? ''));
        $sur_name=$this->request->getPost('sur_name');
        $jcourt=$this->request->getPost('jcourt');
        $abbreviation= $this->request->getPost('abbreviation');

        $from_date = date('Y-m-d', strtotime($this->request->getPost('from_date') ?? ''));
        $to_date = date('Y-m-d', strtotime($this->request->getPost('to_date') ?? ''));        
        $judge_seniority=$this->request->getPost('judge_seniority');

        if($jtype=='R')
{                $first_name=$this->request->getPost('gender')." ".$this->request->getPost('first_name');
                $jname= $this->request->getPost('title');
            }

        else{

            $jname=str_replace("'","\'",(string) $this->request->getPost('title'))." ".$this->request->getPost('first_name')." ".$this->request->getPost('sur_name');
        }

        if($usercode==null || $usercode=='')
        {
            $usercode=$this->request->getPost('usercode');
        }
        $data['usercode'] = $usercode;

        if(isset($view)) {
            $result_array = $this->JudgesMoldel->insert_judges_data($jtype,$jcode, $jname, $first_name, $title, $sur_name, $jcourt, $abbreviation, $from_date, $to_date, $usercode, $judge_seniority);

            if ($result_array == 1)
                $data['rev_result'] = "inserted";
            else
                $data['rev_result'] = "not inserted";
    

        }


         return view('MasterManagement/judges/addJudges',$data);
        
    }
       

    function Getjcodejs(){
        $jtype = $this->request->getGet('jtypeid');
        //$data =array();
        $j_result=$this->JudgesMoldel->jcodesearch($jtype);
        echo json_encode($j_result);

    }



    public function display_Latest_Updates()
    {
        ob_clean();
        header("Content-Type: application/json;charset=utf-8");
        $arr = $this->JudgesMoldel->display_Latest_Updates();
        echo json_encode($arr);
        ob_end_flush();

    }


    

    public function Judges_Update($session=null)
    {
        $usercode = session()->get('login')['usercode'];
        $data['up_result'] = "";
        /*$this->session->set_userdata('dcmis_user_idd', $session);
        echo $usercode=$_SESSION['dcmis_user_idd'];*/
        $jcode=$this->request->getPost('jcode');
        $first_name=$this->request->getPost('first_name');
        $title=$this->request->getPost('title');
        $jname=null;
        $sur_name=$this->request->getPost('sur_name');
        $jcourt=$this->request->getPost('jcourt');
        $abbreviation=$this->request->getPost('abbreviation');
        $retired=$this->request->getPost('retired');
        $display=$this->request->getPost('display');
        $from_date=date('Y-m-d',strtotime($this->request->getPost('from_date') ?? ''));
        $to_date=date('Y-m-d',strtotime($this->request->getPost('to_date') ?? ''));
        //echo $to_date;
        $cji_date=date('Y-m-d',strtotime($this->request->getPost('cji_date') ?? ''));
        //$jtype=$this->request->getPost['jtype'];
        $judge_seniority=$this->request->getPost('judge_seniority');

        if($usercode==null || $usercode=='')
        {
            $usercode=$this->request->getPost['usercode'];
        }
        $data['usercode'] = $usercode;
        $upadtes = $this->request->getPost('update');
        if(isset($upadtes))
        {
            $result_array = $this->JudgesMoldel->update_judges_data($jcode, $jname, $first_name, $title, $sur_name, $jcourt, $abbreviation, $retired, $display, $from_date, $to_date, $cji_date, $usercode, $judge_seniority);
            if ($result_array == 1)
                $data['up_result'] = "updated";
            else
                $data['up_result'] = "not updated";
        }


         return  view('MasterManagement/judges/Judges_Update',$data);
    }


 


    public function get_name()
    {
        $jtype= $this->request->getPost('selectedValue');
        $judges_name=$this->JudgesMoldel->judgesname($jtype);
        if (!is_array($judges_name)) {
            echo '<option value=""></option>';
            return;
        }
        $dropDownOptions = '<option value="">----Select----</option>';
        foreach ($judges_name as $result) {
            if( $result['jtype']=='J') {
                $dropDownOptions .= '<option value=' . $result['jcode'] . ' >' . $result['jname'] . '</option>';
            }
            else
            {
                $dropDownOptions .= '<option value=' . $result['jcode'] . ' >' . $result['jname'] .' ('.$result['first_name'].$result['sur_name'].')'.'</option>';
            }
        }
        echo $dropDownOptions;
    }


    
    function GetDet(){

        $jcode = $_REQUEST['jcodeid'];
        // dd($jcode);

        $j_result=$this->JudgesMoldel->jsearch($jcode);
        echo json_encode($j_result);

    }

}
