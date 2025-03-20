<?php

namespace App\Controllers\MasterManagement;
use App\Controllers\BaseController;
use App\Models\Entities\Model_menu;
use App\Models\MasterManagement\CaseBlockLooseDocModels;
use CodeIgniter\Controller;
use CodeIgniter\Model;
use DateTime;
use CodeIgniter\I18n\Time;


class CaseBlockLooseDoc extends BaseController
{

public $CaseBlockLooseDocModels;
    function __construct()
    {
        ini_set('memory_limit','51200M'); 
        $this->CaseBlockLooseDocModels = new CaseBlockLooseDocModels();
        //error_reporting(0);
    }

    public function index()
    {
      
         $data['cases'] =  $this->CaseBlockLooseDocModels->getdetail();
        return view('MasterManagement/caseblockloosedoc/InsertDelete',$data);
   
    }



    public function SaveCaseBlock()
    {
 
        $ucode = session()->get('login')['usercode'];
        $upuser = session()->get('login')['upuser'];
        $dno = $this->request->getPost('dno') . $this->request->getPost('dyr');
        $reason = strtoupper(trim($this->request->getPost('reason')));
        $existingRecord = $this->CaseBlockLooseDocModels->checkRecordExists($dno);
        if ($existingRecord) {
            return $this->response->setBody('0~RECORD ALREADY PRESENT')->setStatusCode(200);
        } else {
            $data = [
                'diary_no'  => $dno,
                'reason_blk' => $reason,
                'usercode'  => $ucode,
                'ent_dt'    => date('Y-m-d H:i:s'),
                'up_user' => $upuser
            ];
            $builder = $this->db->table('loose_block');
            $builder->insert($data);
            return $this->response->setBody('1~RECORD INSERTED SUCCESSFULLY')->setStatusCode(200);
        }
   
    } 
       
    //  public function getCaseBlock()
    //  {
    //     $data['result'] =  $this->CaseBlockLooseDocModels->getLooseBlockDetails();
    //     pr($data);
    //  } 


     public function getdeleteCaseBlock()
     {
       
        $id =  $this->request->getPost('id');
        $upuser = session()->get('login')['upuser'];
        $builder = $this->db->table('loose_block');
        $builder->set('display', 'N');
        $builder->set('up_user', $upuser);
        $builder->set('up_dt', 'NOW()', false); 
        $builder->where('id', $id);
        $result = $builder->update();
        if ($result) {
            return $this->response->setBody('1~RECORD DELETED SUCCESSFULLY')->setStatusCode(200);
        } else {
            return $this->response->setBody('1~RECORD DELETED FAILED')->setStatusCode(400);
        }
     }




     public function Judges_Report()
     {
        return view('MasterManagement/caseblockloosedoc/Judges_Report');
     }



     public function judges_report_grid()
     {
 
         $is_retired=$this->request->getPost('is_retired');
         $judges=$this->request->getPost('judges');
      
         $result_array= $this->CaseBlockLooseDocModels->Judges_Report_Mod($judges,$is_retired);
         if(isset($result_array) && is_array($result_array) && count($result_array)>0 )  {
            //  pr($result_array);
             ?>
             <table id="reportTable1" class="table table-striped table-hover" style="font-size: small">
                 <thead>
                 <tr style="color:#a94442;font-weight: 600;"><b>
                         <th>Jcode</th>
                         <th>Jname</th>
                         <th>Title</th>
                         <th>Abbreviation</th>
                         <th>Appointment Date</th>
                         <th>TO DATE</th>
                         <th>CJI DATE</th>
                         <th>Jtype</th>
                     </b>
                 </tr>
                 </thead>
                 <tbody>
                    <?php
                    $i = 0;
                    foreach ($result_array as $result) {
                        $i++;
                        ?>
                        <tr>
                            <td><?php echo $result->jcode; ?></td>
                            <td><?php echo $result->jname; ?></td>
                            <td><?php echo $result->title; ?></td>
                            <td><?php echo $result->abbreviation; ?></td>
                            <td><?php
                                if ($result->appointment_date == '0000-00-00' || $result->appointment_date === null) {
                                    echo "";
                                } else {
                                    $newformat = date('d-m-Y', strtotime($result->appointment_date));
                                    echo $newformat;
                                } ?></td>
                            <td><?php
                                if ($result->to_dt == '0000-00-00' || $result->to_dt === null) {
                                    echo "";
                                } else {
                                    $newformat = date('d-m-Y', strtotime($result->to_dt));
                                    echo $newformat;
                                } ?></td>
                            <td><?php
                                if ($result->cji_date == '0000-00-00' || $result->cji_date === null) {
                                    echo "";
                                } else {
                                    $date = $result->cji_date;
                                    if ($date && strtotime($date) !== false) {
                                        $newformat = date('d-m-Y', strtotime($date));
                                        echo $newformat;
                                    } else {
                                        echo "";
                                    }
                                } ?></td>
                            <td><?php echo $result->jtype; ?></td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>

             </table>
             </div>
             <?php
         }
         else if (isset($result_array))
         {
             ?>
            <div class="alert alert-success" style="text-align: center;">
                <strong>No Record Found!</strong>
            </div>

             <?php
         }
         ?>
 
         <?php
 
     }



     public function Menu_List($empCode=null)
     {
      
         $data['param']='';
         $data['menu_list']='';
         $data['target'] = $this->request->getPost('target');
 
         $result_array= $this->CaseBlockLooseDocModels->select_user();
         $data['user_code']=$result_array;
         $result_array1 =  [];

         if(($this->request->getPost('emp')))
         {
             //$section=trim($_POST['emp']);
             $result_array1= $this->CaseBlockLooseDocModels->Menu_Details($this->request->getPost('emp'));
             $data['param']=array($this->request->getPost('emp'));
         }
         if($empCode !=null)
         {
             $result_array1= $this->CaseBlockLooseDocModels->Menu_Details($empCode);
             $ss['param']=array($empCode);
         }
 
         $data['menu_list']=$result_array1;
 
 
         //$result_array=$this->Pending_model->Caveat_List_Filed($date);
         //$this->data['state_year_result']=$result_array;
         return view('MasterManagement/caseblockloosedoc/Menu_Details',$data);
     }
 


     public function Menu_Remove()
     {
         $mn_me_per=$this->request->getGet('mn_me_per');
         $emp_rem=$this->request->getGet('emp_rem');
         $sub_me_per=$this->request->getGet('sub_me_per');
         $sub_sub_menu=$this->request->getGet('sub_sub_menu');
         if ($mn_me_per!=null or $mn_me_per!='')
         {
             $this->CaseBlockLooseDocModels->Menu_remove($mn_me_per,$emp_rem);
         }
         if ($sub_me_per!=null or $sub_me_per!='')
         {
             $this->CaseBlockLooseDocModels->Sub_menu_remove($mn_me_per,$sub_me_per,$emp_rem);
         }
         if ($sub_sub_menu!=null or $sub_sub_menu!='')
         {
             $this->CaseBlockLooseDocModels->Sub_sub_menu_remove($mn_me_per,$sub_me_per,$sub_sub_menu,$emp_rem);
         }
         $this->Menu_List($emp_rem);
     }
    
    
    
}
