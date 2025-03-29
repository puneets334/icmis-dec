<?php
namespace App\Controllers\Faster;
use App\Controllers\BaseController;
use App\Models\DocumentDashboard_model;

class DocumentDashboard extends BaseController
{
    protected $DocumentDashboard_model;
    public function __construct()
    {
        // parent::__construct();
        $this->DocumentDashboard_model = new DocumentDashboard_model();
    }

    public function index(){
        $data = array();
        $countStepWise = $this->DocumentDashboard_model->getStepWiseTotal();
        $data['stepWisetotal'] = !empty($countStepWise) ? $countStepWise[0] : NULL;
        return view('DocumentDashboard/documentReport',$data);
    }
    
    public function getStatusTypeData(){
         $output= array();
         $type = null;
         if(isset($_POST['type']) && !empty($_POST['type'])){
             $type = trim($_POST['type']);
             $params = array();
             $params['type'] = $type;
             $output = $this->DocumentDashboard_model->getTypeWiseData($params);
         }
         echo json_encode($output);
         exit(0);
    }

    public function getDocumentTimelineData(){
        $timeline ='';
        if(isset($_REQUEST['rowId']) && !empty($_REQUEST['rowId']) && isset($_REQUEST['type']) && !empty($_REQUEST['type'])){
            $rowId = trim($_REQUEST['rowId']);
            $type = $_REQUEST['type'];
            $result = $this->DocumentDashboard_model->getDocumentTimelineDataById($type,$rowId);
            
            if(isset($result) && !empty($result)){
                foreach ($result as $k=>$v)
                {
                    $stage = !empty($v->current_stage) ? $v->current_stage  : 'Add Documents';
                    $ref_faster_steps_id = !empty($v->ref_faster_steps_id) ? $v->ref_faster_steps_id  : NULL;
                    $created_on = !empty($v->created_on) ? date("j F , Y H:i:s",strtotime($v->created_on))  : '';
                    $transaction_created_on = !empty($v->transaction_created_on) ? explode(',',$v->transaction_created_on)  : '';
                    $name = !empty($v->name) ? strtoupper($v->name)  : '';
                    $file_path = !empty($v->file_path) ? explode(',',$v->file_path)  : NULL;
                    $fs_created_on = !empty($v->fs_created_on) ? explode(',',$v->fs_created_on)  : NULL;
                    $dated = !empty($v->dated) ? explode(',',$v->dated)  : NULL;
                    $document_name = !empty($v->document_name) ? explode(',',$v->document_name)  : NULL;
                    $fs_deleted = !empty($v->fs_deleted) ? explode(',',$v->fs_deleted)  : NULL;
                    $groupData ='';
                    $pdf_url='';
                    
                    if(isset($document_name) && !empty($document_name) && isset($file_path) && !empty($file_path)){
                        foreach ($document_name as $key=>$value){
                            $pdf_url='';
                           
                            if(empty($fs_deleted) || $fs_deleted[$key] == '0'){
                                $pdf_url = !empty($file_path[$key]) ? 'target="_blank" href="'.base_url($file_path[$key]).'"' : '';                                
                            }
                            
                            if(isset($ref_faster_steps_id) && !empty($ref_faster_steps_id) && ($ref_faster_steps_id == 1 || $ref_faster_steps_id == 2 || $ref_faster_steps_id ==3 )){
                                    if(isset($dated[$key]) && !empty($dated)){
                                        $fs_dated = date("d-m-Y",strtotime($dated[$key]));
                                    }else{
                                        $fs_dated = '';
                                    }
                                    
                                    $groupData .='<a '.$pdf_url.'  title="'.$document_name[$key].'">  '.$document_name[$key].'  ( '.$fs_dated.' )'.'</a>';
                                    if(isset($dated[$key]) && !empty($dated[$key])){
                                        $shareCreatedDate = date("j F , Y H:i:s",strtotime($fs_created_on[$key]));
                                        $groupData .= ' <a  style="color: blueviolet; float:right;" class="" title="'.$shareCreatedDate.'">'.$shareCreatedDate.'</a></br>';
                                    }
                                    else{
                                        $groupData .= ' <a  style="color: blueviolet; float:right;" class="" title="'.$created_on.'">'.$created_on.'</a></br>';
                                    }

                            }
                            else{
                                $shareCreatedDate = date("j F , Y H:i:s",strtotime($transaction_created_on[0]));
                                $groupData .= ' <a style="color: blueviolet; float:right;" class="" title="'.$shareCreatedDate.'">'.$shareCreatedDate.'</a>';
                            }
                        }
                    }
                    else if($ref_faster_steps_id == 4){
                            $ctn = count($transaction_created_on)-1;
                            $transaction_created_on = array_reverse($transaction_created_on);
                            for($i=0;$i <= $ctn;$i++){
                                if(isset($transaction_created_on[$i]) && !empty($transaction_created_on[$i])){
                                    $shareCreatedDate = date("j F , Y H:i:s",strtotime($transaction_created_on[$i]));
                                    $groupData .= ' <a  style="color: blueviolet; float:right;" class="" title="'.$shareCreatedDate.'">'.$shareCreatedDate.'</a></br>';
                                }

                            }
                        }
                    else if($ref_faster_steps_id == 9){
                            if(isset($transaction_created_on[0]) && !empty($transaction_created_on[0])){
                                $shareCreatedDate = date("j F , Y H:i:s",strtotime($transaction_created_on[0]));
                                $groupData .= ' <a  style="color: blueviolet; float:right;" class="" title="'.$shareCreatedDate.'">'.$shareCreatedDate.'</a></br>';
                            }

                    }
                        else{
                            $groupData .= ' <a  style="color: blueviolet; float:right;" class="" title="'.$created_on.'">'.$created_on.'</a></br>';
                        }

                    $timeline .='<li>
                                <a  style="color: blueviolet; " title="'.$stage.'"><b>Stage:</b>  '.$stage.'</a></br>
                                '.$groupData.'
                                <p><b>By:</b> '.$name.'</p>
                                </li>';
                }
            }
        }
        echo $timeline;
        exit(0);
    }


}