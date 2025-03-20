<?php

namespace App\Controllers\Filing;
use App\Controllers\BaseController;
use App\Models\Filing\FileDispatchReceiveModel;
use App\Models\Filing\Model_diary;
use App\Models\Filing\Model_fil_trap;
use CodeIgniter\Model;

class File_trap_dispatch_receive extends BaseController
{
    public $dipatchReceive;
    public $modelFileTrap;

    function __construct()
    {
        $this->dipatchReceive = new FileDispatchReceiveModel();
        $this->modelFileTrap = new Model_fil_trap();

        ini_set('memory_limit','4024M');
    }

    public function index()
    {
//        echo "TTT";die;
        $data=[];
        $curDate=date('d-m-Y');

        $newDate=date('d-m-Y', strtotime($curDate. ' + 60 days'));

//        echo $curDate.">>>>".$newDate;die;

        $condition="and remarks=''";

        $userType=108;
        $typeName='Filing Dispatch Receive';

        $data['record']=[
            'cur_date'=>$curDate,
            'new_date'=>$newDate,
            'condition'=>$condition,
            'user_type'=>$userType,
            'type_name'=>$typeName

        ];

        return view('Filing/filetrap_dispatch_receive',$data);

    }


    public function display_matters()
    {
//        echo "<pre>";
//        print_r($_POST);die;

        if(!empty($_POST['record']))
        {
            $dno = $_POST['record']['dno'];
            $dyr = $_POST['record']['dyr'];
//            $condition =  "a.diary_no".$dno.$dyr;
            $displayData = $this->dipatchReceive->show_dispatch_receive_data($dno,$dyr);
        }else{
            $displayData = $this->dipatchReceive->show_dispatch_receive_data();
        }

//        echo "<pre>";
//        print_r($displayData);die;
        $data['display']=$displayData;
        if($displayData)
        {
//            echo "DDF";
            echo view('Filing/dispatch_receive_record',$data);
        }else{
            echo "<center><h3 style='color:Red'>SORRY!!!, NO RECORD FOUND</h3></center>";
        }

    }

    public function receiveFDR()
    {

        $this->db = \Config\Database::connect();
        $this->db->transStart();

//        var_dump($_SESSION['login']);die;
        $dno=$remarks=$rEmpid=$other=$rByEmpid=$fil_type='';
        $userType =108;
        $fdr =1;
        if(!empty($_POST['id']))
        {
            $id = $_POST['id'];
//            echo $type.">>>".$id;die;
        }
        $filTrap = $this->dipatchReceive->receiveFDR_method($id);
//        echo "<pre>";
//        print_r($filTrap);die;
        if(!empty($filTrap))
        {
            $dno = $filTrap['diary_no'];
            $remarks = $filTrap['remarks'];
            $rEmpid = $filTrap['r_by_empid'];
            $dEmpid = $filTrap['d_to_empid'];
        }
        if($rEmpid == '0') {
            if ($dEmpid == 29) {
                $rByEmpid = $dEmpid;
            } else {
                $rByEmpid = $_SESSION['login']['empid'];
            }

            $filTrapUpdate = $this->dipatchReceive->update_file_trap($id, $rByEmpid, $other);
//            echo "<pre>";
//            print_r($filTrapUpdate);die;
            if (!$filTrapUpdate) {
                echo "Cannot Dispatch, Please Contact Computer Cell!!!! ";
            }
        }

        if( $remarks == 'FDR -> AOR') {
            $queryToUpdateRefilingAttempt = $this->dipatchReceive->update_main($dno);
            if (!$queryToUpdateRefilingAttempt) {
                echo "Cannot Dispatch, Please Contact Computer Cell.... ";
            }
        }

        if($fdr==1) {
            if ($remarks == 'FDR -> AOR') {
//                echo "line 46";
                $this->modelFileTrap->allot_to_AOR($id, $_SESSION['login']['empid'], $remarks, $userType, '1', $fil_type = null, $dno);
//                print_r($ff);die;
            }
        }
            if ($dEmpid == $_SESSION['login']['empid']) {
                $other = '0';
            } else {
                $other = $dEmpid;
            }

        $filTrapUpdateCompOther = $this->dipatchReceive->update_file_trap_comp_other($id,$other);
//            echo "<pre>";
//            print_r($filTrapUpdate);die;
        if (!$filTrapUpdateCompOther) {
            echo "Cannot Dispatch, Please Contact Computer Cell!!!! ";
        }

            $checkMain = $this->dipatchReceive->check_main_table($dno,$id);
            if($checkMain)
            {
                $fil_type='E';
            }else{
                $fil_type='P';
            }

        if($fdr==1) {
            $chk_remark = $this->dipatchReceive->check_remark_filtrap($id);
//            echo "<pre>";
//            print_r($chk_remark);die;

//        echo "<pre>";
//        print_r($remarks);die;
            $given_to = $this->modelFileTrap->allot_to_AOR($id, $_SESSION['login']['empid'], $chk_remark['remarks'], $userType, '2', $fil_type, $dno);
//            echo ">>>";
//            print_r($given_to);die;
            $given_to = explode('~', $given_to);
        }

            if($remarks!='AOR -> FDR' && $remarks!='FDR -> SCR' && $remarks!='FDR -> AOR')
            {
                echo "Completed RETURN TO AOR Successfully ";
            }
            if($given_to[1]!='' and $given_to[1]!=null)
            {
                echo " And  RETURN TO AOR Automatically Allotted to : $given_to[1] [$given_to[0]]";
            }
        $this->db->transComplete();

    }




}
