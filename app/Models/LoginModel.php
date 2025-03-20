<?php
namespace App\Models;
use CodeIgniter\Model;
class LoginModel extends Model{
    protected $table = 'users';
    protected $db;

    public function __construct()
    {
        parent::__construct();
        $db = \Config\Database::connect();
        $this->db = db_connect();
    }
    public function selectPassword($loginid) {
        $builder = $this->db->table("users");
        $builder->select("*");
        $builder->WHERE('empid',$loginid);
        $builder->WHERE('display','Y');
        $query =$builder->get(1);
        if($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        }else{return false;}
    }

    public function checkLogin($username,$password,$pass_hashed,$if_loggable = true, $if_match_password = true)
    { // echo 'username='.$username.' password='.$password;// exit();
        $builder = $this->db->table("master.users");
        $builder->select("*");
        $builder->WHERE('empid',$username);
        $builder->WHERE('display','Y');
        $query =$builder->get(1);
        if($query->getNumRows() >= 1) {
            $result = $query->getRowArray();
            if($result && !empty($result)){
                //print_r($result);
               
                if($result['userpass'].$_SESSION['login_salt']==$pass_hashed){  
                    // echo "dfdfdfdfdfdfdfdfdfdf";
                    if($if_loggable) {
                         
                        return $result;
                    }
                }

            }
            return false;

        }else{
            //echo 'Password not match'; exit(); 
              return false;
        }

    }
    function login_in_update($username){
        $builder = $this->db->table("master.users");
        $builder->set('log_in', date('Y-m-d H:i:s'));
        $builder->set('logout', '1970-01-01 00:00:00');
        $builder->WHERE('empid',$username);
        $builder->WHERE('display','Y');
        // $builder->update();
        if($builder->update()) {
            return true;
        }else{return false;}
    }
    public function get_multi_section($username) {
        $builder = $this->db->table("master.user_sec_map as usm");
        $builder->select("*");
        $builder->join('master.usersection as us', 'us.id=usm.usec');
        $builder->WHERE('usm.empid',$username);
        $builder->WHERE('usm.display','Y');
        $query =$builder->get();
        if($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        }else{return false;}
    }
    public function get_usertype($usertype_id)
    {
        $builder = $this->db->table("master.usertype");
        $builder->select("*");
        $builder->WHERE('id',$usertype_id);
        $builder->WHERE('display','Y');
        $query =$builder->get(1);
        if($query->getNumRows() >= 1) {
            return $result = $query->getRowArray();
        }else{return false;}

    }
    public function get_usertype_detials($row_foruser)
    {
        //echo 'usercode=' . $row_foruser['usercode'] . ' section=' . $row_foruser['section'];// exit();
        $breadcrumb=$usertype=$type_name='';
        if ($row_foruser['usercode'] == 1 && $row_foruser['section'] == 71) {$usertype=1;  $type_name='SUPER USER';
            $breadcrumb = [env('FILING_NEW_CASE_DETAIL'), env('FILING_NEW_CASE_EARLIER_COURT'), env('FILING_NEW_CASE_PARTY'), env('FILING_NEW_CASE_ADVOCATE'), env('FILING_NEW_CASE_CATEGORY'), env('FILING_NEW_CASE_LIMITATION'), env('FILING_NEW_CASE_DEFECT'), env('FILING_NEW_CASE_REFILING'), env('FILING_NEW_CASE_IA_DOCUMNETS'), env('FILING_NEW_CASE_CORAM'), env('FILING_NEW_CASE_TAGGING'), env('FILING_NEW_CASE_REGISTRATION'), env('FILING_NEW_CASE_VERIFICATION'), env('FILING_NEW_CASE_FILE_TRAP'), env('FILING_NEW_CASE_VIEW'),env('FILING_NEW_CASE_SIMILARITY')];
        } else {
            $builder = $this->db->table("fil_trap_users f");
            $builder->JOIN('master.users u', 'f.usercode=u.usercode');
            $builder->JOIN('master.usertype ut', 'ut.id=f.usertype');
            $builder->distinct()->select("f.usertype, ut.type_name,u.name,u.usercode");
            $builder->WHERE('u.usercode', $row_foruser['usercode']);
            $builder->WHERE('u.section', $row_foruser['section']);
            $builder->WHERE('u.display', 'Y');
            $builder->WHERE('f.display', 'Y');
            $builder->whereIn('f.usertype', [101, 102, 103, 105, 106, 107]);
            $builder->orderBy('f.usertype', 'ASC');
            $query = $builder->get();
            if ($query->getNumRows() >= 1) {
                $result = $query->getRowArray();
                $usertype=$result['usertype'];  $type_name=$result['type_name'];
                if ($result['usertype'] == 101) {
                    //Filing
                    $breadcrumb = [env('FILING_NEW_CASE_DETAIL'), env('FILING_NEW_CASE_EARLIER_COURT'), env('FILING_NEW_CASE_ADVOCATE'), env('FILING_NEW_CASE_IA_DOCUMNETS'), env('FILING_NEW_CASE_FILE_TRAP'), env('FILING_NEW_CASE_VIEW'),env('FILING_NEW_CASE_SIMILARITY')];

                } elseif ($result['usertype'] == 102) {
                    //Data Entry
                    $breadcrumb = [env('FILING_NEW_CASE_DETAIL'), env('FILING_NEW_CASE_EARLIER_COURT'), env('FILING_NEW_CASE_ADVOCATE'), env('FILING_NEW_CASE_IA_DOCUMNETS'), env('FILING_NEW_CASE_FILE_TRAP'), env('FILING_NEW_CASE_VIEW'),env('FILING_NEW_CASE_SIMILARITY')];

                } elseif ($result['usertype'] == 103) {
                    //Scrutiny
                    $breadcrumb = [env('FILING_NEW_CASE_LIMITATION'), env('FILING_NEW_CASE_DEFECT'), env('FILING_NEW_CASE_REFILING'), env('FILING_NEW_CASE_VIEW'),env('FILING_NEW_CASE_SIMILARITY'),env('FILING_NEW_CASE_FILE_TRAP')];

                } elseif ($result['usertype'] == 105) {
                    //Category
                    $breadcrumb = [env('FILING_NEW_CASE_CATEGORY'), env('FILING_NEW_CASE_VIEW'),env('FILING_NEW_CASE_SIMILARITY'),env('FILING_NEW_CASE_FILE_TRAP')];

                } elseif ($result['usertype'] == 106) {
                    //Category
                    $breadcrumb = [env('FILING_NEW_CASE_CATEGORY'), env('FILING_NEW_CASE_CORAM'), env('FILING_NEW_CASE_TAGGING'), env('FILING_NEW_CASE_REGISTRATION'), env('FILING_NEW_CASE_VERIFICATION'), env('FILING_NEW_CASE_VIEW'),env('FILING_NEW_CASE_SIMILARITY'),env('FILING_NEW_CASE_FILE_TRAP')];
                }
            }

    }
        $log_check_data=[
            'usercode' => $row_foruser['usercode'],
            'username' => $row_foruser['name'],
            'logging' => date('Y-m-d H:i:s'),
            'addr' => getClientIP(),
            'mac_addr' => getClientMAC()
        ];
        if (!empty($usertype)) {
            $dummy_array = [
                'timestamp' => time(),
                'ipadd' => getClientIP(),
                'macadd' => getClientMAC(),
                'theme_session_active' => 'blue-grey',
                'icmis_masquerade_status' => 0,
                'icmis_is_CourtMaster' => 0,
                'log_in_status' => 1,
                'usertype' => $usertype,
                'type_name' => $type_name,
                'access_breadcrumb' => $breadcrumb,
            ];
        }else{
            $dummy_array = [
                'timestamp' => time(),
                'ipadd' => getClientIP(),
                'macadd' => getClientMAC(),
                'theme_session_active' => 'blue-grey',
                'icmis_masquerade_status' => 0,
                'icmis_is_CourtMaster' => 0,
                'log_in_status' => 1,
                'access_breadcrumb' => $breadcrumb,
            ];
        }

        $final_array = array_merge($row_foruser, $dummy_array);
        session()->set(array('login' => $final_array));
        $is_log_check=$this->log_check($log_check_data);
        return $final_array;
    }
    public function log_check($log_check_data)
    {
        $builder = $this->db->table("log_check");
        if($builder->insert($log_check_data)) {
            return true;
        }else{return false;}

    }

}
