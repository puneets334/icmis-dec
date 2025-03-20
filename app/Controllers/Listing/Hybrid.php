<?php

namespace App\Controllers\Listing;

use App\Controllers\BaseController;

use App\Models\Listing\HybridModel;
// use CodeIgniter\Controller;
// use CodeIgniter\Model;
// use App\Models\Filing\AdvocateModel;
// use App\Models\Casetype;
//use App\Models\Entities\Main;
// use App\Models\Listing\CaseAdd;

class Hybrid extends BaseController
{

    protected $hybrid_model;
    // public $Casetype;
    // public $CaseAdd;

    function __construct()
    {
        $this->hybrid_model = new HybridModel();
        // $this->CaseAdd = new CaseAdd();     
    }

    function get_client_ip()
    {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if (getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if (getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if (getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if (getenv('HTTP_FORWARDED'))
            $ipaddress = getenv('HTTP_FORWARDED');
        else if (getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

    public function registry_consent()
    {
        $usercode = session()->get('login')['usercode'];
        $userTypeString = getUser_dpdg_full_2($usercode);
        $userType = explode('~', $userTypeString);
        if (($userType[0] != 1 && $userType[0] != 57 && $userType[0] != 3 && $userType[0] != 4) && ($userType[6] != 450)) {
            echo "YOU ARE NOT AUTHORISED";
            exit();
        }
        
        $data = [
            'masterList' => $this->hybrid_model->getMasterList(),
            'clientIP' => $this->get_client_ip(),
        ];
        return view('Listing/hybrid/registry_consent', $data);
    }

    public function registry_consent_process()
    {
        $usercode = session()->get('login')['usercode'];
        $userTypeString = getUser_dpdg_full_2($usercode);
        $userType = explode('~', $userTypeString);
        if (($userType[0] != 1 && $userType[0] != 57 && $userType[0] != 3 && $userType[0] != 4) && ($userType[6] != 450)) {
            echo "YOU ARE NOT AUTHORISED";
            exit();
        }

        $this->session->set('courtno', $_POST['courtno']);
        $this->session->set('list_type', $_POST['list_type']);
        
        $data = [
            'freezeData' => $this->hybrid_model->getfreezeData($_POST['courtno'], $_POST['list_type']),
            'weekelyList' => $this->hybrid_model->getweekelyList($_POST['courtno']),
            'clientIP' => $this->get_client_ip(),
        ];

        return view('Listing/hybrid/registry_consent_process', $data);
    }

    public function registry_consent_save()
    {
        $_REQUEST['from_time'] = ($_REQUEST['from_time']) ? $_REQUEST['from_time'] : "00:00:00";
        $_REQUEST['to_time'] = ($_REQUEST['to_time']) ? $_REQUEST['to_time'] : "00:00:00";
        if (isset($_REQUEST['diary_no'])) {
            $return_arr = $this->hybrid_model->saveConsentRegistry($_REQUEST);
        } else {
            $return_arr = array("status" => "Error");
        }
        echo json_encode($return_arr);
        // return view('Listing/hybrid/registry_consent', $return_arr);
    }

    public function freeze()
    {
        $usercode = session()->get('login')['usercode'];
        $userTypeString = getUser_dpdg_full_2($usercode);
        $userType = explode('~', $userTypeString);
        if (($userType[0] != 1 && $userType[0] != 57 && $userType[0] != 3 && $userType[0] != 4) && ($userType[6] != 450)) {
            echo "YOU ARE NOT AUTHORISED";
            exit();
        }
        $data = [
            'masterList' => $this->hybrid_model->getMasterList(),
            'clientIP' => $this->get_client_ip(),
        ];
        return view('Listing/hybrid/freeze', $data);
    }

    public function freeze_process()
    {
        $usercode = session()->get('login')['usercode'];
        $userTypeString = getUser_dpdg_full_2($usercode);
        $userType = explode('~', $userTypeString);
        if (($userType[0] != 1 && $userType[0] != 57 && $userType[0] != 3 && $userType[0] != 4) && ($userType[6] != 450)) {
            echo "YOU ARE NOT AUTHORISED";
            exit();
        }
        $data = [
            'freezeProcess' => $this->hybrid_model->getFreezeProcess(),
            'weekelyProcess' => $this->hybrid_model->getProcessData(),
            'hybrid_model' => $this->hybrid_model,
            // 'hybridData' => $this->hybrid_model->getHybridData(),
            'clientIP' => $this->get_client_ip(),
        ];
        return view('Listing/hybrid/freeze_process', $data);
    }

    public function freeze_save()
    {
        if(isset($_POST['courtno'])) {
       
            $usercode = session()->get('login')['usercode'];
            $data = [
                'list_type_id' => $_POST['list_type_id'],
                'list_number'  => $_POST['max_weekly_no'],
                'list_year'    => $_POST['max_weekly_year'],
                'user_id'      => $usercode,
                'user_ip'      => $_POST['ip'],
                'to_date'      => $_POST['max_to_dt'],
                'court_no'     => $_POST['courtno']
            ];
            $this->db->table('hybrid_physical_hearing_consent_freeze')->insert($data);
        
            if ($this->db->affectedRows() > 0) {
                $return_arr = array("status" => "success");
            } else {
                $return_arr = array("status" => "Error:Not Saved");
            }
        }
        else{
            $return_arr = array("status" => "Error");
        }
        echo json_encode($return_arr);
    }

    public function freeze_delete()
    {
        $usercode = session()->get('login')['usercode'];
        if (isset($_POST['freeze_id'])) {
            $return_arr = $this->hybrid_model->freeze_delete($usercode, $_POST);
        } else {
            $return_arr = array("status" => "Error");
        }
        echo json_encode($return_arr);
    }

    public function consent_report()
    {
        $usercode = session()->get('login')['usercode'];
        $userTypeString = getUser_dpdg_full_2($usercode);
        $userType = explode('~', $userTypeString);
        if (($userType[0] != 1 && $userType[0] != 57 && $userType[0] != 3 && $userType[0] != 4) && ($userType[6] != 450)) {
            echo "YOU ARE NOT AUTHORISED";
            exit();
        }
        $data = [
            'masterList' => $this->hybrid_model->getMasterList(),
            'listDetails' => $this->hybrid_model->getListDetails(),
            'clientIP' => $this->get_client_ip(),
        ];
        return view('Listing/hybrid/consent_report', $data);
    }

    public function consent_report_process()
    {
        $usercode = session()->get('login')['usercode'];
        $userTypeString = getUser_dpdg_full_2($usercode);
        $userType = explode('~', $userTypeString);
        if (($userType[0] != 1 && $userType[0] != 57 && $userType[0] != 3 && $userType[0] != 4) && ($userType[6] != 450)) {
            echo "YOU ARE NOT AUTHORISED";
            exit();
        }
        $data = [
            'consentReport' => $this->hybrid_model->getConsentReport(),
            // 'listDetails' => $this->hybrid_model->getListDetails(),
            'clientIP' => $this->get_client_ip(),
        ];

        return view('Listing/hybrid/consent_report_process', $data);
    }

    // public function add_case_info()
    // {    
    //     $ct = $this->request->getPost('case_type');
    //     $cn = $this->request->getPost('case_number');
    //     $cy = $this->request->getPost('case_year');
    //     $dyr = $this->request->getPost('diary_year');
    //     $dn = $this->request->getPost('diary_number');
    //     $dno = '';
    //     if (!empty($ct) && !empty($cn) && !empty($cy))
    //     {
    //         $dno = $this->CaseAdd->getDiaryNo($ct, $cn, $cy);
    //     } else if (!empty($dn) && !empty($dyr)) {
    //         $dno = $dn . '/' . $dyr;
    //     } else {
    //         return redirect()->back()->with('error', 'Required parameters are missing.');
    //     }
    //     $dno = (string) $dno;
    //     $details = $this->CaseAdd->getCaseDetails(str_replace('/','',$dno));
    //     // pr($details);
    //     $data = [
    //         'dno' => str_replace('/','',$dno),
    //         'details' => is_array($details) ? $details : [],
    //         'category' => $this->CaseAdd->getCategory(str_replace('/','',$dno)) ?? '',
    //         'casetype' => $this->CaseAdd->getCaseType(str_replace('/','',$dno)) ?? [],
    //         'main_case' => $this->CaseAdd->checkDiaryNo(str_replace('/','',$dno)) ?? [],
    //         //'already_entries' => $this->CaseAdd->getAlreadyEntries($dno) ?? [],
    //         //'hearingDetails' =>$this->CaseAdd->getHearingDetails(str_replace('/','',$dno)),
    //         'advance_list' => isset($details['next_dt']) ? $this->CaseAdd->getAdvanceList($details['next_dt']) : [],
    //     ];
    //     return view('Listing/advance_list/case_add/add_case_info', $data);
    // }

    // public function upd_umpermission()
    // {
    //     if ($this->request->getMethod() === 'post')
    //     {
    //         date_default_timezone_set('Asia/Kolkata');
    //         $action=htmlentities(trim($_POST['action']));
    //         $menu_id=(int)$_POST['menu_id'];
    //         switch ($action) {
    //             case 'getAlotmentMenu':
    //                 $count=1;
    //                 /*$qsel='select GROUP_CONCAT(role_master_id) from user_role_master_mapping where usercode=? AND display="Y";';
    //                 $qselRs=$dbo->prepare($qsel);
    //                 $qselRs->bindParam(1, $menu_id, PDO::PARAM_INT);
    //                 $qselRs->execute();
    //                 $menuIds=$qselRs->fetchColumn();
    //                 $menuIds=explode(',', $menuIds);
    //                 $query="select id,role_desc,updated_on from role_master where display='Y' order by id;";
    //                 $rs=$dbo->prepare($query);
    //                 $rs->execute();
    //                 while ($rows=$rs->fetch(PDO::FETCH_BOTH, PDO::FETCH_ORI_NEXT)) {
    //                     foreach ($menuIds as $GetmId) {
    //                         $checked=''; $fontColor='text-danger';
    //                         if($rows[0] == $GetmId) {
    //                             $checked=' checked="checked"';
    //                             $fontColor='text-success'; break;
    //                         }
    //                     }
    //                     echo'<tr>
    //                         <td>
    //                             <input type="checkbox" name="mRoleId" value="'.$rows[0].'" id="'.$count.'"'.$checked.'>&nbsp;&nbsp;
    //                             <label class="'.$fontColor.' font-weight-bold" for="'.$count.'">'.$rows[1].'</label>
    //                         </td>
    //                         <td><div class="lupdon"><i class="fa fa-calendar text-warning">&nbsp;Last updated on : </i>'.$rows[2].'</div></td>
    //                     </tr>';
    //                     $count++;
    //                 }*/
    //                 break;
    //             case 'UpdUserDisplay_uy':
    //                 /*$ip = $_SERVER['REMOTE_ADDR']; $now=date('Y-m-d H:i:s');
    //                 $qupd='update users set display=?, updt=?, ip_address=? where usercode=?;'; $display='N';
    //                 $qrs=$dbo->prepare($qupd);
    //                 $qrs->bindParam(1, $display, PDO::PARAM_STR);
    //                 $qrs->bindParam(2, $now, PDO::PARAM_STR);
    //                 $qrs->bindParam(3, $ip, PDO::PARAM_STR);
    //                 $qrs->bindParam(4, $menu_id, PDO::PARAM_INT);
    //                 if($qrs->execute() == 1) echo json_encode(array('data'=>'success'));
    //                 else 					 echo json_encode(array('data'=>'failed'));*/
    //                 break;
    //             case 'UpdUserDisplay_un':
    //                 $ip = $_SERVER['REMOTE_ADDR']; $now=date('Y-m-d H:i:s');
    //                 /*$qupd='update users set display=?, updt=?, ip_address=? where usercode=?;'; $display='Y';
    //                 $qrs=$dbo->prepare($qupd);
    //                 $qrs->bindParam(1, $display, PDO::PARAM_STR);
    //                 $qrs->bindParam(2, $now, PDO::PARAM_STR);
    //                 $qrs->bindParam(3, $ip, PDO::PARAM_STR);
    //                 $qrs->bindParam(4, $menu_id, PDO::PARAM_INT);
    //                 if($qrs->execute() == 1) echo json_encode(array('data'=>'success'));
    //                 else 					 echo json_encode(array('data'=>'failed'));*/
    //                 break;
    //             case 'mn':
    //                 /*$qupd='update menu set display=? where id=?;'; $display='Y';
    //                 $qrs=$dbo->prepare($qupd);
    //                 $qrs->bindParam(1, $display, PDO::PARAM_STR);
    //                 $qrs->bindParam(2, $menu_id, PDO::PARAM_INT);
    //                 if($qrs->execute() == 1) echo json_encode(array('data'=>'success'));
    //                 else 					 echo json_encode(array('data'=>'failed'));*/
    //                 break;
    //             case 'my':
    //                /* $qupd='update menu set display=? where id=?;'; $display='N';
    //                 $qrs=$dbo->prepare($qupd);
    //                 $qrs->bindParam(1, $display, PDO::PARAM_STR);
    //                 $qrs->bindParam(2, $menu_id, PDO::PARAM_INT);
    //                 if($qrs->execute() == 1) echo json_encode(array('data'=>'success'));
    //                 else 					 echo json_encode(array('data'=>'failed'));*/
    //                 break;
    //             case 'editMenu':
    //                 $menu_list=$this->Menu_model->get_menu_by_id($menu_id);
    //                 if(!empty($menu_list)) {
    //                     echo json_encode(array('data'=>$menu_list));
    //                 }
    //                 break;
    //         }
    //         exit();
    //     }
    // }

    // public function addMenu()
    // {
    //     if ($this->request->getMethod() === 'post') {
    //         if($_POST['action']=='GrantPermission') {

    //         } elseif($_POST['action']=='Update') {

    //         } elseif($_POST['action']=='menuUpdate') {
    //             $menu_nm=htmlentities(trim($_POST['caption']));
    //             $priority=htmlentities(trim($_POST['priority']));
    //             $url=htmlentities(trim($_POST['url']));
    //             $menu_id=htmlentities(trim($_POST['menu_id']));
    //             $oldsmid=(int)$_POST['oldsmid'];
    //             $update_menu = [
    //                 'menu_nm'=>$menu_nm,
    //                 'priority'=>$priority,
    //                 'url' => $url,
    //                 'old_smenu_id' => $oldsmid,
    //                 'updated_on' => date("Y-m-d H:i:s"),
    //                 'updated_by'=>$_SESSION['login']['usercode'],
    //                 'updated_by_ip'=>getClientIP(),
    //             ];
    //             $is_update_menu=update('master.menu',$update_menu,['id'=>$menu_id]);
    //             if ($is_update_menu) {
    //                 echo 'Updated';exit();
    //             } else {
    //                 echo 'Failed';exit();
    //             }
    //         }
    //         $menu_id=htmlentities(trim($_POST['mnid']));
    //         if($menu_id !='') {
    //             $disabled='';
    //             switch (strlen($menu_id)) {
    //                 case 2:
    //                     $squery="";
    //                     break;
    //                 case 4:
    //                     $squery="";
    //                     break;
    //                 case 6:
    //                     $squery="";
    //                     break;
    //                 case 8:
    //                     $squery="";
    //                     break;
    //                 case 10:
    //                     $squery="";
    //                     $disabled=' disabled';
    //                     break;
    //             }
    //         } else {
    //             $menuId=htmlentities(trim($_POST['menu']));
    //             $caption=htmlentities(trim($_POST['caption']));
    //             $priority=(int)$_POST['priority'];
    //             $display=htmlentities(trim(strtoupper($_POST['display'])));
    //             $url=htmlentities(trim($_POST['url']));
    //             if($url==null || $url=='') $url='#';
    //             $oldsmid=(int)$_POST['oldsmid'];
    //             if(!is_numeric($oldsmid)) $oldsmid=0;
    //             $child='child';
    //             for($i=1; $i<=5; $i++) {
    //                 $childVar=$child.$i;
    //                 $$childVar=htmlentities(trim($_POST[$childVar]));
    //                 if(!$$childVar) break;
    //             }
    //             if(strstr($child5,'addNew')) {
    //                 $preMenu=strtr($child5,array('addNew'=>''));
    //             } elseif(strstr($child4,'addNew')) {
    //                 $preMenu=strtr($child4,array('addNew'=>''));
    //             } elseif(strstr($child3,'addNew')) {
    //                 $preMenu=strtr($child3,array('addNew'=>''));
    //             } elseif(strstr($child2,'addNew')) {
    //                 $preMenu=strtr($child2,array('addNew'=>''));
    //             } elseif(strstr($child1,'addNew')) {
    //                 $preMenu=strtr($child1,array('addNew'=>''));
    //             } elseif(strstr($menuId,'addNew')) {

    //             }
    //         }
    //     } 
    // }

    public function aor_case_record_report()
    {
        $data['listing_date'] = $this->hybrid_model->listing_date();
        $data['honble_judges'] = $this->hybrid_model->honble_judges();
        return view('/Listing/hybrid/aor_case_record_report', $data);
    }
    public function get_aor_case_record_report_1()
    {
        $date = date('Y-m-d', strtotime($this->request->getPost('listing_dts') ?? 'now'));
        $data['listing_dts'] = $date;
        $data['list_type'] =  $this->request->getPost('list_type');
        $data['judge_code'] = $this->request->getPost('judge_code');
        $data['consent_source'] =  $this->request->getPost('consent_source');
        $data['court_no'] = $this->request->getPost('court_no');
        $data['result_array'] = $this->hybrid_model->get_aor_case_record_report($data['listing_dts'],$data['list_type'],$data['judge_code'],$data['consent_source'],$data['court_no']);
        // pr($data['result_array']);
        return view('/Listing/hybrid/get_aor_case_record_report',$data);
    }
}
