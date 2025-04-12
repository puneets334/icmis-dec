<?php

namespace App\Controllers\Library;

use App\Controllers\BaseController;
use Config\Database;
use App\Models\Library\RequisitionModel;
use App\Models\Library\AdminpermissionModel;
use App\Models\Library\AdminusersModel;

class Requisition extends BaseController
{
    protected $db;
    protected $RequisitionModel;
    protected $AdminpermissionModel;
    protected $AdminusersModel;

    public function __construct()
    {
        $this->db = Database::connect();
        $this->session = session();
        $this->RequisitionModel = new RequisitionModel();
        $this->AdminpermissionModel = new AdminpermissionModel();
        $this->AdminusersModel = new AdminusersModel();
    }

    public function requisition_view()
    {
        $sessionData = $this->session->get();
        //pr($sessionData);
        if (!isset($sessionData['login']['usercode'])) {
            return redirect()->to('/login');
        }
        

        $usercode = $sessionData['login']['usercode'];

        if (!isset($sessionData['token'])) {
            $this->session->set('token', bin2hex(random_bytes(32)));
        }

        $data['token'] = $this->session->get('token');
        

        $result = is_data_from_table('admin', " icmis_user_id= '$usercode'", "*",'');

        if(!empty($result)){             
            $this->session->set('role_id', $result['role_id']);
            $this->session->set('court_number', $result['court_no']);
           
        }
 
        //$requisitions = $this->view_today_RequisitionData($usercode);
        $requisitions = $this->view_requistion_department();
      //  pr($requisitions);
        $listRole = $this->db->query("select * from admin_user_roles  where role_id IN('4','5','6')")->getResultArray();
        return view('Library/requisition', [
            'requisitions' => $requisitions,
            'listRole' => $listRole
        ]);
    }

    private function view_today_RequisitionData($usercode)
    {
        $todayDate = date("Y-m-d");

        $query = "SELECT * FROM tbl_court_requisition 
                  WHERE itemDate >= ? 
                  AND current_status IN ('pending') 
                  AND court_number = ? 
                  ORDER BY id DESC";

        return $this->db->query($query, [$todayDate, $usercode])->getResultArray();
    }

    public function view_requistion_department()
    {
        $sqlQuery = "SELECT *  FROM  tbl_requisition_department WHERE status=1 ORDER BY id asc";
        $stmt = $this->db->query($sqlQuery);
        return $stmt->getResultArray();
        
    }

    public function create_requisition()
    {
        $data = $this->request->getPost();

        // Sanitize inputs
        $courtNumber = htmlspecialchars($data['court_number']);
        // ... sanitize other inputs as needed

        $query = "INSERT INTO tbl_court_requisition (court_number, court_userName, remark1, court_bench, urgent, section, request_file, itemNo, itemDate, alternate_number, user_type, created_by, user_ip, diary_no, advocate_name, appearing_for, party_serial_no) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $this->db->query($query, [
            $courtNumber,
            htmlspecialchars($data['court_userName']),
            htmlspecialchars($data['remark1']),
            htmlspecialchars($data['court_bench']),
            htmlspecialchars($data['urgent']),
            htmlspecialchars($data['section']),
            htmlspecialchars($data['file']),
            htmlspecialchars($data['itemNo']),
            htmlspecialchars($data['itemDate']),
            htmlspecialchars($data['phoneNo']),
            $data['user_type'] ?? 1,
            htmlspecialchars($data['created_by']),
            $_SERVER['REMOTE_ADDR'],
            htmlspecialchars($data['diary_no']),
            htmlspecialchars($data['advocate_name']),
            htmlspecialchars($data['appearing_for']),
            htmlspecialchars($data['party_serial_no']),
        ]);

        return redirect()->to('/success');
    }

    public function frmusrLogin()
    {        
            $time = time();
            $mode =$_POST['mode'];
            $icmis_user_id = $_SESSION['login']['usercode'];

            if($mode =='login'){

                if (!empty($_POST['token'])) {
                  

                    $role_id = $_POST['role_id'];

                    if($_POST['role_id']==5 || $_POST['role_id']==6 || $_POST['role_id']==7)
                    {
                         
                         

                        //$passWord = md5(trim($_POST['user_password']));

                        $stmt = $this->AdminpermissionModel->getRequisitionLogin($icmis_user_id,$role_id);
                        
                        if(!empty($stmt)){

                            $nameUsr = $stmt['username'];
                            $_SESSION['username']=$nameUsr;
                            $_SESSION['role_id']=$_POST['role_id'];

                            $returnArr['status'] = "Success";
                            $returnArr['msg'] = "Login Successfully";
                        }else{
                            $returnArr['status'] = "Error";
                            $returnArr['msg'] = "User not exist";
                            
                        }
                        echo  json_encode($returnArr);

                    }else if($_POST['role_id']==4)
                    {

                       if(trim($_POST['user_name_other'])=="")
                        {                            
                            
                            $stmt = $this->AdminpermissionModel->getRequisitionLogin($icmis_user_id,$_POST['role_id']);
                           
                         
                        }elseif(trim($_POST['user_name_other'])!=""){
                            $usr_name = $_POST['user_name_other'];
                            $passWord = md5(trim($_POST['user_password']));
                            $stmt = $this->AdminpermissionModel->getRequiLogin_Other($passWord,$_POST['role_id']);                             
                        }
 
                        if(!empty($stmt))
                        {
                            $error=0;  
                        }else{
                            $error=1;
                        }


                        if($error==0)
                        {
                            
                            $usr_Name = $stmt['username'];

                            $_SESSION['court_number'] = $_POST['court_number'];
                            $_SESSION['court_bench'] = $_POST['court_bench'];
                            $_SESSION['role_id']=$_POST['role_id'];
                            $_SESSION['username']=$usr_Name;
                            $returnArr['status'] = "Success";
                            $returnArr['msg'] = "Login Successfully";


                        }else if($error==2)
                        {
                            $returnArr['status'] = "Error";
                            $returnArr['msg'] = "Incorrect Login Information !!! ";
                        }else if($error==1)
                        {
                            $returnArr['status'] = "Error";
                            $returnArr['msg'] = "User not exist";                            
                        }
                        echo  json_encode($returnArr);
                    }


                }else{
                    $returnArr['status'] = "Error";
                    $returnArr['msg'] = "Token Issue!!! ";                    
                }  




            }
            if($mode == 'addRequest'){

                if (!empty($_POST['token'])) {
                     $data = array(
                        'court_number' => $_POST['court_number'],
                        'court_username' => $_POST['court_username'],
                        'remark1' => $_POST['remark1'],
                        'court_bench' => $_POST['court_bench'],
                        'urgent' => $_POST['urgent'],
                        'section' => $_POST['section'],
                        'request_file' => '',
                        'itemNo' => $_POST['itemNo'],
                        'itemDate' => $_POST['itemDate'],
                        'alternate_number' => '',
                        'user_type' => ($_POST['user_type'] == '') ?? 1,                       
                        'created_by' => $_POST['username'],
                        'user_ip' => $_POST['userIp'],
                        'diary_no' => $_POST['diary_no'],
                       'advocate_name' => $_POST['advocate_name'],
                        'appearing_for' => $_POST['appearing_for'],
                        'party_serial_no' => $_POST['party_serial_no']
                        
                     );                   

                    
                    if($lastId =$this->RequisitionModel->create($data)){
                        $requisition_id= $lastId;
                        $interaction_remarks= $_POST['request'];
                        $interaction_status= 'pending';
                        $insertData = array(
                            'requisition_id' => $requisition_id,
                            'interaction_status' => $interaction_status,
                            'interaction_remarks' => $interaction_remarks,                             
                            'interaction_ip' => $_SERVER['REMOTE_ADDR'],
                            'created_by' => $icmis_user_id,
                        );
                        $InsertInteraction=$this->RequisitionModel->Insert_Interaction($insertData);



                        $remarks = [];
                        if(!empty($_REQUEST['remarks_arr'])){
                            $remarks = explode(',', $_REQUEST['remarks_arr']);
                        }
                        $countVal = 0;
                        $files = $_FILES;
                        $arr = [];
                        foreach ($remarks as $ky => $rue) {

                            if(!empty($files)){
                                if(!empty($files['file-'.$ky])){
                                    $file_name_path = substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, 4) . '.pdf';
                                    if(move_uploaded_file($files['file-'.$ky]["tmp_name"], "/var/www/html/supreme_court/library_resources_offline/files/library_aor_uploads/".$file_name_path)){
                                        //echo "yes";
                                    }
                                    $statement = ('INSERT INTO requistion_upload (req_id, file_path, usercode, ip, remarks) VALUES (:reqId, :filePath, :userCode, :Ip, :Remark)');

                                    $status = $this->db->query($statement, [
                                        'reqId' => $lastId,
                                        'filePath' => $file_name_path,
                                        'userCode' => $_SESSION['icmic_empid'],
                                        'Ip' => $_SERVER['REMOTE_ADDR'],
                                        'Remark' => $rue
                                    ]);

                                }else{
                                    $statement =('INSERT INTO requistion_upload (req_id, file_path, usercode, ip, remarks) VALUES (:reqId, :filePath, :userCode, :Ip, :Remark)');
                                    $status = $this->db->query($statement,[
                                        'reqId' => $lastId,
                                        'filePath' => '',
                                        'userCode' => $_SESSION['icmic_empid'],
                                        'Ip' => $_SERVER['REMOTE_ADDR'],
                                        'Remark' => $rue
                                    ]);
                                }
                            }else{
                                $statement = ('INSERT INTO requistion_upload (req_id, file_path, usercode, ip, remarks) VALUES (:reqId, :filePath, :userCode, :Ip, :Remark)');
                                $status = $this->db->query($statement,[
                                    'reqId' => $lastId,
                                    'filePath' => '',
                                    'userCode' => $_SESSION['icmic_empid'],
                                    'Ip' => $_SERVER['REMOTE_ADDR'],
                                    'Remark' => $rue
                                ]);
                            }
                        }

                        $returnArr['status'] = "Success";
                        $returnArr['msg'] = "Request Sent Successfully ";
                    }else{
                        $returnArr['status'] = "Error";
                        $returnArr['msg'] = "Something Error";
                    }

                   
                }//not empty token

                echo  json_encode($returnArr);


            }
            if($mode =="REQUISTION-REQUEST"){
                $requestid=$_POST['requestid'];
                $id=$requestid;
                $requistionReq=$this->RequisitionModel->viewRequistionRequest($id);
                $requistionReq['remark1']=stripslashes($requistionReq['remark1']);
                echo json_encode($requistionReq);


            }
            if($mode =="ReuqistionAlert"){
                $totalReq_Pending=$this->RequisitionModel->view_today_ReqAdminData();
                $data = array('total_pendingCase'=>$totalReq_Pending);
                echo json_encode($data);


            }
            if($mode =="ADD-INTERACTION"){


                if (!empty($_POST['token'])) {

                    

                    if (!file_exists('../../reqistionRequest')) {
                        mkdir('../../reqistionRequest', 0777, true);

                    }
                    if ($_FILES['file']['name'] != ""){
                        $info = pathinfo($_FILES['file']['name']);
                        $ext = $info['extension']; // get the extension of the file
                        $time = time();
                        $newname = $time.".".$ext ;
                        $file =$newname;
                        $target = '../../reqistionRequest/'.$newname;
                        $upload = move_uploaded_file($_FILES['file']['tmp_name'], $target);
                    }
                    $requisition_id=$_POST['requisition_id'];
                    $urgent=$_POST['urgent'];
                    $interaction_remarks=$_POST['interaction_remarks'];
                    if($_POST['roleid']=='6')
                    {
                        $interaction_status="Interaction";

                    }else{
                        $interaction_status=trim($_POST['current_status']);
                    }

                    $created_by=trim($_POST['created_by']);
                    $insertData = array(
                        'requisition_id' => $requisition_id,
                        'interaction_status' => $interaction_status,
                        'interaction_remarks' => $interaction_remarks,                             
                        'interaction_ip' => $_SERVER['REMOTE_ADDR'],
                        'created_by' => $created_by,
                    );
                    $InsertInteraction=$this->RequisitionModel->Insert_Interaction($insertData);

                    if($interaction_status=="closed" || $interaction_status=="received" || $interaction_status=="cancel")
                    {
                        $currenttime=date("Y-m-d H:i:s");
                    }else
                    {
                        $currenttime="";
                    }
                    $updateData = array(                         
                        'current_status' => $interaction_status,
                        'remark2' => $interaction_remarks,   
                        'request_file' => $file,                          
                        'urgent' => $urgent,                          
                        'request_close_datetime' => $currenttime,                          
                        'interaction_ip' => $_SERVER['REMOTE_ADDR'],
                        'updated_by' => $created_by,
                        'updated_on' => NOW()
                    );

                    if($updateInteraction=$this->RequisitionModel->update_requistion($requisition_id,$updateData)){
                        $returnArr['status'] = "Success";
                        $returnArr['msg'] = "Interaction Added Successfully ";
                    }else{
                        $returnArr['status'] = "Error";
                        $returnArr['msg'] = "Something Error";
                    }

                    
                }//not empty token

                echo  json_encode($returnArr);
            }



            if($mode =="ADD-AOR-INTERACTION")
            {
                 

                $requisition_id=$_POST['requisition_id'];
                $interaction_remarks=$_POST['interaction_remarks'];
                $interaction_status=trim($_POST['current_status']);
                $created_by=trim($_POST['created_by']);
                $itemNo=trim($_POST['itemNo']);

                $insertData = array(
                    'requisition_id' => $requisition_id,
                    'interaction_status' => $interaction_status,
                    'interaction_remarks' => $interaction_remarks,                             
                    'interaction_ip' => $_SERVER['REMOTE_ADDR'],
                    'created_by' => $created_by,
                );

                $InsertInteraction=$this->RequisitionModel->Insert_Interaction($insertData);

                if($interaction_status=="closed" || $interaction_status=="received" || $interaction_status=="cancel")
                    {
                        $currenttime=date("Y-m-d H:i:s");
                    }else
                    {
                        $currenttime="";
                    }
                    $updateData = array(                         
                        'current_status' => $interaction_status,
                        'remark2' => $interaction_remarks,   
                        'request_file' => $file,                          
                        'urgent' => $urgent,                          
                        'request_close_datetime' => $currenttime,                          
                        'interaction_ip' => $_SERVER['REMOTE_ADDR'],
                        'updated_by' => $created_by,
                        'updated_on' => NOW()
                    );
                if($updateInteraction=$this->RequisitionModel->update_requistion($requisition_id,$updateData)){
                    $returnArr['status'] = "Success";
                    $returnArr['msg'] = "Interaction Added Successfully ";
                }else{
                    $returnArr['status'] = "Error";
                    $returnArr['msg'] = "Something Error";
                }
 
                echo  json_encode($returnArr);

            }




            if($mode == 'getAutoRefresh'){
                $contents = "";$cnt1=1;
                $court_username = $_POST['username'];
                $court_number = $_POST['court_number'];
                $stmt = $this->RequisitionModel->read($court_number);
                if(!empty($stmt)){
                    foreach ($stmt as $res){

                        $req_id = $res['id'];
                        $query11 = "SELECT id, req_id, file_path, remarks FROM requistion_upload where req_id = '".$req_id."' AND is_active = '1' ";
                        $pdo_statement11 = $this->db->query($query11);
                        $count11 = $pdo_statement11->getNumRows();
                        $reqArr= [];
                        
                        if($count11 > 0){
                            $resu = $pdo_statement11->getResultArray();
                        }
                        if(!empty($resu)){
                            foreach ($resu as $ve) {
                                if($ve['remarks'] != ''){
                                    $reqArr[] = $ve['remarks'];
                                }
                            }
                        }

                        $requisition_id=$res['id'];
                        $interaction_count=$this->RequisitionModel->view_requistion_interactions($requisition_id);
                        $created_by=$_POST['username'];
                        $int_assit_readcnt=$this->RequisitionModel->count_Interaction_read_assitant($requisition_id,$created_by);
                        
                        if($res['current_status'] == "pending" ){
                            $btnVal = '<button type="button" class="btn btn-danger">'.strtoupper($res['current_status']).'</button>';
                        }
                        if($res['current_status'] == "Interaction" ){
                            $btnVal = '<button type="button" class="btn btn-dark">'.strtoupper($res['current_status']).'</button>';
                        }if($res['current_status'] == "received"){
                            $btnVal = '<button type="button" class="btn btn-primary">'.strtoupper($res['current_status']).'</button>';
                        }if($res['current_status'] == "Sent"){
                            $btnVal = '<button type="button" class="btn btn-info">'.strtoupper($res['current_status']).'</button>';
                        }if($res['current_status'] == 'attending'){
                            $btnVal = '<button type="button" class="btn btn-warning">'.strtoupper($res['current_status']).'</button>';
                        }if($res['current_status'] == 'closed'){
                            $btnVal = '<button type="button" class="btn btn-success">'.strtoupper($res['current_status']).'</button>';
                        }if($res['current_status'] == 'cancel'){
                            $btnVal = '<button type="button" class="btn btn-secondary">'.strtoupper($res['current_status']).'</button>';
                        }
                         
                        if($res['urgent']=="Yes")
                        {
                            $urgentVal="<span class='badge bg-danger'>".strtoupper($res['urgent'])."</span>";
                        }else{
                            $urgentVal="No";
                        }

                        if ( $res['current_status'] == "closed" || $res['current_status'] == "cancel"
                            || $res['current_status'] == "received")
                        {
                            $bgcolor="#E5E4E2";
                        }else{
                            $bgcolor="";
                        }
               
                        $contents .= '<tr style="background-color:'.$bgcolor.'" >';


                        if(( ($res['current_status'] != 'closed') &&  ($res['current_status'] != 'cancel') && ($res['current_status'] != 'received')) ){
                            $contents .= ' <td><a href="javascript:void(0)" onclick="getQueryData('.$res["id"].');"><b>'.$res['itemNo'].'</b></a></td>';
                        }else{
                            $contents .= '<td>'.$res['itemNo'].'</td>';
                        }

                        $contents .= '   <td>'.($urgentVal).'</td>';




                        if(!empty($reqArr)){
                            $contents .= "<td>";
                            foreach ($reqArr as $vue) {
                                $contents .= $vue.'<br>';
                            }
                            $contents .= "</td>";
                        }else{
                            $contents .= "<td> </td>";
                        }

                        $contents .=  '<td>'.($res['remark2']).'</td>
                                <td align="center">'.$btnVal.'</td>';
                        if($int_assit_readcnt!=0)
                        {

                            $contents.='<td><span class="badge badge-danger  float-right blink"><font color="white"><JavaBlink>'.$int_assit_readcnt.'</JavaBlink></font></span>'.$res['section'].'</td>';

                        }else{
                            $contents.='<td><span class="badge badge-info  float-right blink"><font color="white">'.$int_assit_readcnt.'</font></span>'.$res['section'].'</td>';
                        }




                        if($interaction_count)
                        {
                            $contents .=  '<td><a href="#" onclick="openWin('.$res["id"].');"><button type="button" class="btn btn-warning">View </button></a></td>
                                            ';
                        }else{
                            $contents .=  '<td></td>';
                        }



                        if($res['current_status'] != 'closed' && $res['current_status'] != 'cancel'
                            && $res['current_status'] != 'received')
                        {
                            $contents .=  '<td> <input type="checkbox"   id="status_recive" name="status_r"   value="'.$res["id"].'" class="cbCheck" onclick="changeRstatus('.$res["id"].')"><font size="2" color="#303030">Click here to update the status</font></td>';
                        }else{
                            $contents .=  '<td align="center">'.strtoupper($res["current_status"]).'</td></td>';
                        }


                        $contents .="";

                        $contents.=  '</tr>'; $cnt1++;
                    }
                    $returnArr['html'] = $contents;
                }else{
                    $returnArr['html'] = $contents;
                }
                echo  json_encode($returnArr);

            }
            if($mode == 'getAutoRefresh_Admin'){
                $contents = '';
                
                if($_POST['roletype']==5)
                {
                    $stmt = $this->RequisitionModel->view_today_RequisitionData();


                }
                if($_POST['roletype']==6){
                    $stmt = $this->RequisitionModel->view_today_ReqAdminData();

                }

                $interaction_by_admin="";

                if(!empty($stmt)){
                    foreach ($stmt as $res){

                        $requisition_id=$res['id'];
                        $interaction_count=$this->RequisitionModel->view_requistion_interactions($requisition_id);

                        $created_by=$_SESSION['username'];
                        $interaction_read_cnt=$this->RequisitionModel->count_Interaction_read_libraian($requisition_id,$created_by);
                        $btncolor="btn-warning";
                        if($_POST['roletype']==5)
                        {
                            $Cnt_inter_by_admin=$this->RequisitionModel->count_Interaction_By_Admin($requisition_id);
                            if($Cnt_inter_by_admin) $btncolor="btn-danger";
                        }

                        $entrytime=strtotime($res['created_on']);
                        $nowtime=date("Y-m-d H:i:s");
                        $now_time=strtotime($nowtime);
                        $timeDifference=($now_time-$entrytime);
                        $timeDiff=gmdate("H:i:s",$timeDifference);

                        if($timeDifference<300 )
                        {
                            $img_show="<button class='btn btn-secondary'>NEW</button>";
                        }elseif($timeDifference>300 && $timeDifference<600)
                        {
                            $img_show="<button value='' class='btn btn-secondary'>HURRY UP</button>";
                        }elseif($timeDifference>600){
                            $img_show="<button class='btn btn-secondary'>TIME OUT</button>";                             
                        }

                        if($res['current_status'] == "pending" || $res['current_status'] == 'Interaction'){
                            $btnVal = '<button type="button" class="btn btn-danger">PENDING</button>';
                        }if($res['current_status'] == "received"){
                            $btnVal = '<button type="button" class="btn btn-primary">'.strtoupper($res['current_status']).'</button>';
                        }if($res['current_status'] == "Sent"){
                            $btnVal = '<button type="button" class="btn btn-info">'.strtoupper($res['current_status']).'</button>';
                        }if($res['current_status'] == 'attending'){
                            $btnVal = '<button type="button" class="btn btn-warning">'.strtoupper($res['current_status']).'</button>';
                        }if($res['current_status'] == 'closed'){
                            $btnVal = '<button type="button" class="btn btn-success">'.strtoupper($res['current_status']).'</button>';
                        }if($res['current_status'] == 'cancel'){
                            $btnVal = '<button type="button" class="btn btn-secondary">'.strtoupper($res['current_status']).'</button>';
                        }
                         
                        $createdon=explode(" ",$res['created_on']);$close_datetime=explode(" ",$res['request_close_datetime']);
                        $contents .= '<tr>';
                        if($res['current_status'] != 'closed'  && $res['current_status'] != 'cancel'  && $res['current_status'] != 'received'){
                            $contents .= '<td><a href="#" onclick="view_requistion_result('.$res["id"].')"><b>'.$res['court_number'].'</b></a></td>';
                        }else{
                            $contents .= '<td>'.$res['court_number'].' </td>';
                        }
                        if($res['urgent']=="Yes")
                        {
                            $urgentVal="<span class='badge bg-danger'>".strtoupper($res['urgent'])."</span>";
                        }else{
                            $urgentVal="No";
                        }


                        $contents .= ' <td><b>'.ucwords($res['itemNo']).' </b></td>';
                        $contents .= ' <td><b>'.ucwords($res['section']).' </b></td>';
                        $contents .= ' <td>'.$urgentVal.' </td>';
                         
                        if($interaction_read_cnt!=0)
                        {
                            $contents .= ' 
                    <td>'.ucwords($res['created_by']).'<span class="badge bg-danger float-right"><JavaBlink>'.$interaction_read_cnt.'</JavaBlink></span></td>';
                        }else{
                            $contents .= ' 
                    <td>'.ucwords($res['created_by']).'<span class="badge bg-warning float-right">'.$interaction_read_cnt.'</span></td>';
                        }


                        $contents .= '<td>'.$btnVal.'</td> <td>'.$createdon[1].'</td> <td>'.$img_show.'('.$timeDiff.')</td>';
                        if($interaction_count)
                        {
                            $contents .=  '<td><a href="#" onclick="openWin('.$res["id"].');"><button type="button" class="btn '.$btncolor.'">View </button></a>'.$interaction_by_admin;
                        }else{
                            $contents .=  '<td></td>';
                        }

                    }
                    $returnArr['html'] = $contents;
                }else{
                    $returnArr['html'] = $contents;
                }
                echo  json_encode($returnArr);

            }
            if($mode=="getReuqistionStatus"){
                $returnArr  = array();
                $statusArry=array('pending','attending','Interaction','received','closed','cancel','Sent');
                foreach($statusArry as $stausVal)
                {
                    $current_status=$stausVal;
                    $statuscnt=$this->RequisitionModel->view_requistion_status_cnt($current_status);

                    $returnArr[$stausVal] = $statuscnt;
                }
                $datetime=date("d/m/Y h: i : s A");
                $returnArr['livedateval'] =$datetime;
                echo json_encode( $returnArr);
            }
            if($mode == 'getReqIntractionReport'){
                $contents ='Data Not Available';
                $requisition_id=$_POST['id'];
                $interactions = $this->RequisitionModel->view_requistion_interactions_results($requisition_id);
                if(!empty($interactions))
                {
                    foreach($interactions as $res){
                        if($res->request_file){
                            $inFile = '<a href="../../requisition/reqistionRequest/'.$res->request_file.'" target="_blank"><i class="fa fa-eye"></i></a>';
                        }else{
                            $inFile ='';
                        }
                        $contents .='<tr>'
                            . '<td>'.$res->interaction_ip.'</td>'
                            . '<td>'.$res->interaction_remarks.'</td>'
                            . '<td>'.$inFile.'</td>'
                            . '<td>'.$res->created_by.'</td>'
                            . '<td>'.strtoupper($res->interaction_status).'</td>'
                            . '<td>'.$res->created_on.'</td>'
                            . '</tr>';

                    }
                    $returnArr['html'] = base64_encode($contents);
                }else{
                    $returnArr['html'] = base64_encode($contents);
                }
                echo  json_encode($returnArr);
            }
            if($mode=="CHANGE-STAUS-RECEIVED" && $_POST['requestid']!=""){


                if($_POST['currentstatus']=="received" && $_POST['requestid']!="")
                {
                    $interaction_remarks=ucwords($_POST['created_by'])." changed the status to Received";
                    $requisition_id=$_POST['requestid'];
                    $interaction_status=$_POST['currentstatus'];
                    $created_by=$_POST['created_by'];

                    $insertData = array(
                        'requisition_id' => $requisition_id,
                        'interaction_status' => $interaction_status,
                        'interaction_remarks' => $interaction_remarks,                             
                        'interaction_ip' => $_SERVER['REMOTE_ADDR'],
                        'created_by' => $created_by,
                    );
                    $$this->RequisitionModel->Insert_Interaction($insertData);

                    if($interaction_status=="closed" || $interaction_status=="received" || $interaction_status=="cancel")
                    {
                        $currenttime=date("Y-m-d H:i:s");
                    }else
                    {
                        $currenttime="";
                    }
                    $updateData = array(                         
                        'current_status' => $interaction_status,
                        'remark2' => $interaction_remarks,   
                        'request_file' => $file,                          
                        'urgent' => $urgent,                          
                        'request_close_datetime' => $currenttime,                          
                        'interaction_ip' => $_SERVER['REMOTE_ADDR'],
                        'updated_by' => $created_by,
                        'updated_on' => NOW()
                    );

                    if($updateInteraction=$this->RequisitionModel->update_requistion($requisition_id,$updateData)){
                        $returnArr['status'] = "Success";
                        $returnArr['msg'] = "Successfully updated the status ";
                    }else{
                        $returnArr['status'] = "Error";
                        $returnArr['msg'] = "Something Error";
                    }

                }else{
                    $returnArr['status'] = "Error";
                    $returnArr['msg'] = "No change in status";
                }

                echo  json_encode($returnArr);
            }
            if($mode == 'getAdvReqData'){
             

                $data = array(
                    'court_number' => $_POST['court_number'],
                    'court_username' => $_POST['regId'],
                    'remark1' => $_POST['remark1'],
                    'court_bench' => $_POST['court_bench'],
                    'urgent' => $_POST['urgent'],
                    'section' => $_POST['section'],
                    'request_file' => '',
                    'itemNo' => $_POST['itemNo'],
                    'itemDate' => $_POST['itemDate'],
                    'alternate_number' => $_POST['phoneNo'],
                    'user_type' => ($_POST['user_type'] == '') ?? 1,                       
                    'created_by' => $_POST['regId'],
                    'user_ip' => $_POST['userIp'],
                    'diary_no' => $_POST['diary_no'],
                   'advocate_name' => $_POST['advocate_name'],
                    'appearing_for' => $_POST['appearing_for'],
                    'party_serial_no' => $_POST['party_serial_no']
                    
                 );
 
                 
                if($lastId =$this->RequisitionModel->create($data)){
                    if(isset($_POST['citation'])) $arry_cit= array_filter($_POST['citation']);
                    if(isset($_POST['fileType']))  $arry_filetype= array_filter($_POST['fileType']);
                  
                    if(count($arry_cit) && count($arry_filetype) ){

                        if (!file_exists('../../reqistionRequest')) {
                            mkdir('../../reqistionRequest', 0777, true);

                        }
                        foreach($_FILES['citationFile']['tmp_name'] as $key => $tmp_name)
                        {
                            $requisition_id= $lastId;
                            $file_type= $_POST['fileType'][$key];
                            $file_text= $_POST['citation'][$key];
                            $tempName = $_FILES['citationFile']['tmp_name'][$key];
                            $info = pathinfo($_FILES['citationFile']['name'][$key]);
                            $ext = $info['extension']; // get the extension of the file
                            $newname = $file_type.$key."_".$time.".".$ext ;
                            $target = '../../reqistionRequest/'.$newname;

                            $upload = move_uploaded_file($tempName, $target);
                            if($upload)
                            {
                                $file_name = $newname;
                            }else{
                                $file_name = '';
                            }
                         
                            $insertData = array(
                                'req_id' => $requisition_id,
                                'file_type' => $file_type,
                                'file_text' => $file_text,
                                'file_name' => $file_name,
                                'created_by' => $_POST['created_by'],

                            );

                            if($this->RequisitionModel->advCitationData($insertData)){
                                $returnArr['status'] = "Success";
                                $returnArr['msg'] = "Request Successfully Add";
                            }else{
                                $returnArr['status'] = "Error";
                                $returnArr['msg'] = "Something Error";
                            }
                        }
                    }else{
                        $returnArr['status'] = "Success";
                        $returnArr['msg'] = "Request Successfully Add ";//with out Any Book/Act/citation

                    }


                }else{
                    $returnArr['status'] = "Error";
                    $returnArr['msg'] = "Something Error";
                }
                echo  json_encode($returnArr);
            }

            if($mode == 'getCaseNo'){
                $stmt = $this->RequisitionModel->getCaseNo($_POST['item_no'],$_POST['court_no'], $_POST['dateitem']);
                echo json_encode($stmt);
            }



    }




    public function court_dashboard()
    {
        //pr($_SESSION);
        $data['RequisitionModel'] = $this->RequisitionModel;
        $data['AdminusersModel'] = $this->AdminusersModel;
        $todayDate = date('Y-m-d');
        $court_username = session()->get('login')['name'];
        $sql = "SELECT *  FROM  tbl_court_requisition  where DATE(created_on)='$todayDate' AND court_username='$court_username' ORDER BY id DESC";
        $query = $this->db->query($sql);
        $data['result'] = $query->getResultArray();
        $data['dataDropdown'] = $this->RequisitionModel->dropdownItemDates();
        $data['librarySection'] = $this->RequisitionModel->view_library_section();
        return view('Library/court_dashboard',$data);
    }


    public function view_court_requisition()
    {
        $data['RequisitionModel'] = $this->RequisitionModel;
        $data['AdminusersModel'] = $this->AdminusersModel;

        if ($_SESSION['role_id'] == '5') {
            $data['requistionData'] = $this->RequisitionModel->view_today_RequisitionData();
        } else {
        
            $data['requistionData']= $this->RequisitionModel->view_today_ReqAdminData();
        }

        

        return view('Library/view_court_requisition',$data);
    }
   
}
