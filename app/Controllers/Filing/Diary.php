<?php

namespace CodeIgniter\Validation;

namespace App\Controllers\Filing;

use App\Controllers\BaseController;
use App\Libraries\webservices\Efiling_webservices;
use App\Libraries\webservices\Highcourt_webservices;
use App\Models\Common\Dropdown_list_model;
use App\Models\Entities\Model_lc_hc_casetype;
use App\Models\Entities\Model_main_a;
use App\Models\Entities\Model_usersection;
use App\Models\Filing\Model_caveat;
use App\Models\Filing\Model_party;
use App\Models\Filing\Model_renewed_caveat;
use App\Models\Filing\Model_special_category_filing;
use App\Models\Master\Model_casetype;
use App\Models\Master\Model_cnt_diary_no;
use App\Models\Model_main;
use App\Models\Filing\Model_diary;

class Diary extends BaseController
{
    public $LoginModel;
    public $efiling_webservices;
    public $highcourt_webservices;
    public $Dropdown_list_model;
    public $Model_main;
    public $Model_main_a;
    public $Model_diary;
    public $Model_cnt_diary_no;
    public $Model_casetype;
    public $Model_special_category_filing;
    public $Model_party;
    public $Model_renewed_caveat;
    public $Model_caveat;
    public $Model_lc_hc_casetype;
    public $Model_usersection;
    function __construct()
    {
        $this->efiling_webservices = new Efiling_webservices();
        $this->highcourt_webservices = new Highcourt_webservices();
        $this->Dropdown_list_model = new Dropdown_list_model();
        $this->Model_main = new Model_main();
        $this->Model_main_a = new Model_main_a();
        $this->Model_diary = new Model_diary();
        $this->Model_cnt_diary_no = new Model_cnt_diary_no();
        $this->Model_casetype = new Model_casetype();
        $this->Model_special_category_filing = new Model_special_category_filing();
        $this->Model_party = new Model_party();
        $this->Model_renewed_caveat = new Model_renewed_caveat();
        $this->Model_caveat = new Model_caveat();
        $this->Model_lc_hc_casetype = new Model_lc_hc_casetype();
        $this->Model_usersection = new Model_usersection();
    }



    public function search()
    {
       
        if ($this->request->getGet('page_url')) {
            $page_url = $this->request->getGet('page_url');
            $current_page_url = base_url(str_replace('-', '/', base64_decode($page_url)));
            
        } else {
            $redirect_url_to = $_SESSION['redirect_url_to'];
            if ($redirect_url_to == 'Filing/Diary/search') {

                $current_page_url = base_url('Filing/Diary_modify');
            } else {

                $current_page_url = base_url($redirect_url_to);
            }

            //$current_page_url = base_url('Filing/Diary_modify');
        }

        if ($this->request->getMethod() === 'post') {

            if (!isset($_REQUEST['redirect_url'])) {
                $_REQUEST['redirect_url'] = base_url('Filing/Diary_modify');
            }
            $redirect_url = $_REQUEST['redirect_url'];
            $search_type = $this->request->getPost('search_type');

            $diary_number = $this->request->getPost('diary_number');
            $diary_year = $this->request->getPost('diary_year');

            $case_type = $this->request->getPost('case_type');
            $case_number = $this->request->getPost('case_number');
            $case_year = $this->request->getPost('case_year');

            $this->validation->setRule('search_type', 'Select Diary or Case type', 'required');

            if (!empty($search_type) && $search_type != null) {
                if ($search_type == 'D') {
                    $this->validation->setRule('search_type', 'Select Diary or Case type', 'required');
                    $this->validation->setRule('diary_number', 'Diary number', 'required');
                    $this->validation->setRule('diary_year', 'Diary year', 'required');

                    $data = [
                        'search_type' => $search_type,
                        'diary_number' => $diary_number,
                        'diary_year' => $diary_year,
                    ];
                } else {
                    $this->validation->setRule('search_type', 'Select Diary or Case type', 'required');
                    $this->validation->setRule('case_type', 'Case type', 'required');
                    $this->validation->setRule('case_number', 'Case number', 'required');
                    $this->validation->setRule('case_year', 'Case year', 'required');

                    $data = [
                        'search_type' => $search_type,
                        'case_type' => $case_type,
                        'case_number' => $case_number,
                        'case_year' => $case_year,
                    ];
                }
            } else {
                $data = [
                    'search_type' => $search_type
                ];
            }

            if (!$this->validation->run($data)) {
                // handle validation errors
                echo '3@@@';
                //echo $this->validation->getError('search_type').$this->validation->getError('case_type');
                echo $this->validation->listErrors();
                exit();
            }

            $search_type = $this->request->getPost('search_type');
            if ($search_type == 'D') {
                $diary_no = $diary_number . $diary_year;
                $get_main_table = $this->Dropdown_list_model->get_diary_details_by_diary_no($diary_no);
                
            } elseif ($search_type == 'C') {
                $diary_no = get_diary_case_type($case_type, $case_number, $case_year);
                if (!empty($diary_no)) {
                    $diary_number = substr($diary_no, 0, -4);
                    $diary_year = substr($diary_no, -4);
                    $get_main_table = $this->Dropdown_list_model->get_diary_details_by_diary_no($diary_no);
                } else {
                    $get_main_table = array();
                }
            }
            if ($get_main_table) {
                
				$get_main_table['diary_number'] = $diary_number;
				$get_main_table['diary_year'] = $diary_year;
                $this->session->set(array('filing_details' => $get_main_table));
                
                echo '1@@@' . $redirect_url;
                //pr($get_main_table);
                exit();
                //return redirect()->to('Filing/Diary/redirect_on_diary_user_type');exit();
            } else {
                echo '3@@@Data not found!';
                exit(); //session()->setFlashdata("message_error", 'Data not Fount');
            }
            exit();
            
        }

        $data['current_page_url'] = $current_page_url;
        return view('Filing/diary_search', $data);
    }
    function redirect_on_diary_user_type()
    {
        if (session()->get('login')) {
            return redirect()->to('Filing/Diary_modify');
        } else {
            session()->setFlashdata("message_error", 'Accessing permission denied contact to Computer Cell.');
            //return redirect()->to('Filing/Diary/search');
        }
        return redirect()->to('Filing/Diary_modify');
    }

    public function index()
    {
        $data['country'] = get_from_table_json('country');
        $data['state'] = get_from_table_json('state');
        $data['ref_special_category_filing'] = get_from_table_json('ref_special_category_filing', 'Y', 'display');
        //$data['casetype']=get_from_table_json('casetype');
        $data['court_type_list'] = $this->Dropdown_list_model->get_court_type_list();
        $data['usersection'] = $this->Dropdown_list_model->get_usersection();
        $data['state_list'] = $this->Dropdown_list_model->get_address_state_list();
        $role = $this->Model_diary->get_role_fil_trap(session()->get('login')['usercode']);
        $data['casetype'] = $this->Dropdown_list_model->get_case_type($role);
        $data['casetype_nature_sci'] = $this->Dropdown_list_model->get_case_type($role, 'nature_sci');
        $data['role'] = $role;
        //echo '<pre>';print_r(session()->get('login')['type_name']);exit();
        //echo '<pre>';print_r($data['ref_special_category_filing']);exit();
        $data['diary_details'] = array();
        $data['sclsc'] = array();
        //return view('Filing/diary_generation',$data);
        return view('Filing/diary_add', $data);
    }
    public function getdiary_no()
    {
        $dataq = $_REQUEST['q'];
        $c_d = substr_count($dataq, "/");
        if ($c_d <> 0) {
            $a = explode("/", $dataq);
            $ct = $a[0];
            $cn = $a[1];
            $cy = $a[2];
            $diary_no = get_diary_case_type($ct, $cn, $cy);
        } else {
            $diary_no = $dataq;
        }

        $section_id = '';
        // $section = "select tentative_section(diary_no) from main where diary_no='$diary_no'";
        $section = procedure_function('tentative_section', $diary_no);
        //echo '<pre>';print_r($section);exit();
        if (!empty($section)) {
            // $section_name=$section['tentative_section'];
            $section_name = $section;
            $section_details = $this->Model_usersection->select('id')->where(['section_name' => $section_name])->get(1)->getRowArray();
            if (!empty($section_details)) {
                $section_id = $section_details['id'];
            } else {
                $section_id = '';
            }
        }
        echo $section_id;
        exit();
    }
    public function deletion()
    {

        return view('Filing/diary_deletion_view');
    }

    public function get_diary_info()
    {

        $caveat_no = $_REQUEST['cav_no'] . $_REQUEST['cav_yr']; //$caveat_no =3912023;
        $cause_title = $this->Model_main->select("pet_name,res_name,TO_CHAR(diary_no_rec_date, 'DD-MM-YYYY') as diary_no_rec_date,(current_date - diary_no_rec_date::date) no_of_days,casetype_id,c_status")->where(['diary_no' => $caveat_no])->findAll(1);
        if (empty($cause_title)) {
            $cause_title = $this->Model_main_a->select("pet_name,res_name,TO_CHAR(diary_no_rec_date, 'DD-MM-YYYY') as diary_no_rec_date,(current_date - diary_no_rec_date::date) no_of_days,casetype_id,c_status")->where(['diary_no' => $caveat_no])->findAll(1);
        }
        if ($cause_title && !empty($cause_title)) {
            foreach ($cause_title as $row) {
                $c_status = $row['c_status'];
                echo "<input type='hidden' name='c_status' id='c_status' value='$c_status'/>";
                if ($row['c_status'] == 'P') {
                    $c_status = 'Case Status : <span class="text-primary">Pending</span><br/>';
                } elseif ($row['c_status'] == 'D') {
                    $c_status = 'Case Status : <span class="text-danger">Disposed</span></span><br/>';
                }
                echo "</br><font style='text-align: center;font-size: 14px;color: red'>Diary Infomation: </font></br>" . $c_status;
                echo "<font style='text-align: center;font-size: 14px;color: black'> Petitioner: </font><font style='text-align: center;font-size: 14px;color: blue'> " . $row['pet_name'] . "</font></br>";
                echo "<font style='text-align: center;font-size: 14px;color: black'> Respondent:</font><font style='text-align: center;font-size: 14px;color: blue'> " . $row['res_name'] . "</font></br>";
            }
            $is_renewed = $this->Model_caveat->select("*")->where(['caveat_no' => $caveat_no, 'is_renew' => 'Y'])->findAll();
            if (!empty($is_renewed)) { ?><br> <span style="text-align: center;font-size: 20px;color:green"><?php echo "Already Renewed"; ?></span>
                <input type="hidden" name="hd_renew" id="hd_renew" value="<?php echo count($is_renewed); ?>" />
                <?php $get_new_cav = $this->Model_renewed_caveat->select("concat(left((cast(old_caveat_no as text)),-4),'/', right((cast(old_caveat_no as text)),4)) as old_caveat_no,concat(left((cast(new_caveat_no as text)),-4),'/', right((cast(new_caveat_no as text)),4)) as new_caveat_no,TO_CHAR(renew_date, 'DD-MM-YYYY') as renew_date")->where(['old_caveat_no' => $caveat_no])->findAll(1);
                if (count($get_new_cav) > 0) { ?>
                    <table class="table" bgcolor="#ffe4c4" border="1">
                        <th>Old Caveat No.</th>
                        <th>Renewed Caveat No.</th>
                        <th>Renewed On</th>
                        <tr>
                            <td><?php echo $get_new_cav[0]['old_caveat_no']; ?></td>
                            <td><?php echo $get_new_cav[0]['new_caveat_no']; ?></td>
                            <td><?php echo $get_new_cav[0]['renew_date']; ?></td>
                        </tr>
                    </table>
            <?php }
            }
        } else {
            echo "<font style='text-align: center;font-size: 14px;color: red'>Case not found</font>";
        }
        exit();
    }
    
    public function get_diary_delete()
    {
        $remarks = $_REQUEST['remarks'];
        $diary_no = $_REQUEST['d1'];

        $cause_title = $this->Model_main->select("pet_name,res_name,TO_CHAR(diary_no_rec_date, 'DD-MM-YYYY') as diary_no_rec_date,(current_date - diary_no_rec_date::date) no_of_days,casetype_id")->where(['diary_no' => $diary_no])->findAll(1);
       
        if ($cause_title && !empty($cause_title)) 
        {
            foreach ($cause_title as $row) {
                $is_renewed = $this->Model_caveat->select("*")->where(['caveat_no' => $diary_no, 'is_renew' => 'Y'])->findAll();
                if (!empty($is_renewed)) {
                    echo '<span style="text-align: center;font-size: 20px;color:green">Already Renewed</span>';
                    exit();
                }
            }
            //pr($cause_title);
            //$data_array=is_data_from_table('main',['diary_no'=>$diary_no]);
            //$data_array = $this->Model_main->select("*")->where(['diary_no' => $diary_no])->findAll();
            $data_array = is_data_from_table('main',['diary_no'=>$diary_no],'*','');

            if ($data_array) {
                $data_log = [
                    'deleted_reason' => $remarks,
                    'deleted_on' => date("Y-m-d H:i:s"),

                    'create_modify' => date("Y-m-d H:i:s"),
                    'deleted_by' => session()->get('login')['usercode'],
                    'deleted_by_ip' => getClientIP(),
                ];
                //unset($data_array[0]['id']);
                $final_array = array_merge($data_log, $data_array);
                if (!empty($final_array)) {
                    $this->db = \Config\Database::connect();
                    $this->db->transStart();
                    $is_success = insert('main_deleted_cases', $final_array);
                    
                    if ($is_success) {
                        //$is_success_deteled=$this->Model_main->delete('diary_no',$diary_no);
                        $is_success_deteled = delete('main', ['diary_no' => $diary_no]);
                        if ($is_success_deteled) {
                            echo " Diary no-  " . substr($diary_no, 0, strlen($diary_no) - 4) . '/' . substr($diary_no, -4) . " deleted successfully !!";
                        } else {
                            echo " Diary no-  " . substr($diary_no, 0, strlen($diary_no) - 4) . '/' . substr($diary_no, -4) . " is not delete please try again !!";
                        }
                    }
                    $this->db->transComplete();
                }
            }
        } else {
            $disposed = "Reason Diary no-" . substr($diary_no, 0, strlen($diary_no) - 4) . '/' . substr($diary_no, -4) . " is disposed";
            echo "<font style='text-align: center;font-size: 14px;color: red'>Please Contact Computer cell $disposed.</font>";
            exit();
        }
    }



    function getsection()
    {
        $data1 = $_REQUEST['q'];
        //echo'get_section anshu= '.$data1;exit();
        $this->Model_diary->get_section($data1);
        exit();
    }

    function get_adv_name()
    {
        //advno=1779&advyr=&ddl_pet_adv_state=&flag=P&padvt=A&is_ac=N
        //advno=1779&advyr=&ddl_res_adv_state=&flag=R&radvt=A
        $advno = $this->request->getGet('advno');
        $advyr = $this->request->getGet('advyr');
        $flag = $this->request->getGet('flag');
        $is_ac = $this->request->getGet('is_ac');
        $state_id = '';
        $p_r_advt = '';
        if ($flag == 'P') {
            $state_id = $this->request->getGet('ddl_pet_adv_state');
            $p_r_advt = $this->request->getGet('padvt');
        } else if ($flag == 'R') {
            $state_id = $_REQUEST['ddl_res_adv_state'];
            $p_r_advt = $this->request->getGet('radvt');
        }

        $adv = $this->Dropdown_list_model->get_adv_name($p_r_advt, $advno, $advyr, $state_id);
        if (!empty($adv)) {
            echo $adv['name'] . '~' . $adv['mobile'] . '~' . $adv['email'] . '~' . $adv['bar_id'] . '~' . $adv['enroll_year'];
        } else {
            echo '0';
        }
        exit();
    }
    public function get_case_strc()
    {
        //This below code not used confirmed by Mrs.Vandana and Mr.Pavan sir on 15 Feb 2024
        $usercode = session()->get('login')['type_name'];
        $from_court = $this->request->getPost('ddl_court');
        if (isset($from_court) && $from_court == 1) {
            // High Court

        } elseif (isset($from_court) && $from_court == 2) {
            // Others Court

        } elseif (isset($from_court) && $from_court == 3) {
            // District Court

        } elseif (isset($from_court) && $from_court == 4) {
            // Supreme Court

        } elseif (isset($from_court) && $from_court == 5) {
            // State Agency

        }
        $this->validation->setRule('ddl_court', 'Court Type', 'required');
        $this->validation->setRule('ddl_st_agncy', 'Court State', 'required');
        $this->validation->setRule('ddl_bench', 'Court Bench', 'required');

        $ddl_st_agncy = $this->request->getPost('ddl_st_agncy');
        $ddl_bench = $this->request->getPost('ddl_bench');

        $data = [
            'ddl_court' => $from_court,
            'ddl_st_agncy' => $ddl_st_agncy,
            'ddl_bench' => $ddl_bench,
        ];
        if (!$this->validation->run($data)) {
            // handle validation errors
            echo '3@@@';
            //echo $this->validation->getError('from_court').$this->validation->getError('hc_court_bench').$this->validation->getError('case_type_casecode');
            echo $this->validation->listErrors();
        } else {
            //$ddl_st_agncy=$_REQUEST['ddl_st_agncy'];
            //$ddl_bench=$_REQUEST['ddl_bench'];
            $bench = '';

            if ($ddl_st_agncy == 292979) {
                if ($ddl_bench == 17) {
                    $bench = 01;
                } else if ($ddl_bench == 18) {
                    $bench = 02;
                } else if ($ddl_bench == 19) {
                    $bench = 03;
                }
                $data['bench'] = $bench;
                $data['lower_ct_det'] = array();
                /*$lower_ct_det = is_data_from_table('lowerct', ['diary_no' => $diary_no, 'lw_display' => 'R'],'lct_casetype,lct_caseno,lct_caseyear','R');

               if (empty($lower_ct_det)){
                   $lower_ct_det = is_data_from_table('lowerct_a', ['diary_no' => $diary_no, 'lw_display' => 'R'],'lct_casetype,lct_caseno,lct_caseyear','R');
               }
                $data['lower_ct_det']=$lower_ct_det;*/

                $data['lc_hc_casetype'] = $this->Model_lc_hc_casetype->select('*')->where(['display' => 'Y', 'cmis_state_id' => $ddl_st_agncy, 'ref_agency_code_id' => 0, 'corttyp' => 'H'])->orderBy('type_sname')->get()->getResultArray();
                $resul_view = view('Filing/get_case_strc', $data);
            } else {
                $resul_view = 'No Record Found';
            }

            echo '1@@@' . $resul_view;
        }
        exit();
    }


    /*XXXXXXXXXXXXXXXXXXX start Intertion save_new_filing XXXXXXXXXXXXXXXXXX*/
    public function save_new_filing()
    {
        
        $ucode = $_SESSION['login']['usercode'];
        $year = date('Y');
        if (isset($_REQUEST['txt_doc_signed'])) {
            if (!empty($_REQUEST['txt_doc_signed'])) {
                $_REQUEST['txt_doc_signed'] = date('Y-m-d', strtotime($_REQUEST['txt_doc_signed']));;
            }
        }
        /* if (isset($_REQUEST['type_special']) && $_REQUEST['type_special']==6){
            $_REQUEST['txt_doc_signed']=date('Y-m-d',strtotime($_REQUEST['txt_doc_signed']));
        }*/

        $padvno_and_yr = $_REQUEST['hd_p_barid'];
        $radvno_and_yr = $_REQUEST['hd_r_barid'];
        if (!isset($_REQUEST['hd_r_barid'])) {
            $radvno_and_yr = $_REQUEST['hd_r_barid'] = 0;
        } else {
            if (empty($radvno_and_yr)) {
                $radvno_and_yr = $_REQUEST['hd_r_barid'] = 0;
            }
        }
        if ($_REQUEST['padtype'] == 'SS') {
            $padvno_and_yr = '584';
        }

        if ($_REQUEST['radtype'] == 'SS') {
            $radvno_and_yr = '585';
        }



        if (!isset($_REQUEST['padd'])) {
            $_REQUEST['padd'] = '';
        } else {
            $_REQUEST['padd'] = htmlentities(sanitize($_REQUEST['padd']));
        }
        if (!isset($_REQUEST['pocc'])) {
            $_REQUEST['pocc'] = '';
        } else {
            $_REQUEST['pocc'] = htmlentities(sanitize($_REQUEST['pocc']));
        }
        if (!isset($_REQUEST['radd'])) {
            $_REQUEST['radd'] = '';
        } else {
            $_REQUEST['radd'] = htmlentities(sanitize($_REQUEST['radd']));
        }
        if (!isset($_REQUEST['rocc'])) {
            $_REQUEST['rocc'] = '';
        } else {
            $_REQUEST['rocc'] = htmlentities(sanitize($_REQUEST['rocc']));
        }

        if (!isset($_REQUEST['pet_statename_hd'])) {
            $_REQUEST['pet_statename_hd'] = 0;
        }
        if (!isset($_REQUEST['res_statename_hd'])) {
            $_REQUEST['res_statename_hd'] = 0;
        }
        if (empty($_REQUEST['pmob'])) {
            $_REQUEST['pmob'] = 0;
        }
        if (empty($_REQUEST['rmob'])) {
            $_REQUEST['rmob'] = 0;
        }

        if (!isset($_REQUEST['cs_tp'])) {
            $_REQUEST['cs_tp'] = 0;
            $cs_tp = 0;
        }

        if (!isset($_REQUEST['pet_rel_name'])) {
            $_REQUEST['pet_rel_name'] = '';
        }
        if (!isset($_REQUEST['p_age'])) {
            $_REQUEST['p_age'] = 0;
        }
        if (!isset($_REQUEST['p_sex'])) {
            $_REQUEST['p_sex'] = null;
        }
        if (!isset($_REQUEST['pet_rel'])) {
            $_REQUEST['pet_rel'] = '';
        }

        if (!isset($_REQUEST['res_rel_name'])) {
            $_REQUEST['res_rel_name'] = '';
        }
        if (!isset($_REQUEST['r_age'])) {
            $_REQUEST['r_age'] = 0;
        }
        if (!isset($_REQUEST['r_sex'])) {
            $_REQUEST['r_sex'] = null;
        }
        if (!isset($_REQUEST['res_rel'])) {
            $_REQUEST['res_rel'] = '';
        }

        $this->db = \Config\Database::connect();
        $this->db->transStart();

        if ($_REQUEST['controller'] == 'I') {
            echo '!~!';
            //error_reporting(0);
            $fil_q = $this->Model_cnt_diary_no->select('max_diary_no')->where('diary_no_year', $year)->get()->getRowArray();
            
            // $fil = $fil_q['max_diary_no'];
            $fil = isset($fil_q['max_diary_no']) ? $fil_q['max_diary_no'] : null;
            $fil++;
            $diary_no = $fil . $year;
            
            $sclsc = 0;
            if ($_REQUEST['if_sclsc'] == 0 || $_REQUEST['if_sclsc'] == '') {
                $sclsc = 0;
            } else {
                $sclsc = $_REQUEST['if_sclsc'];
            }
            $c_status = '';
            $efil = 0;
            $efil_no = 0;
            $efil_yr = '';
            if (isset($_REQUEST['if_efil']) == 0 || isset($_REQUEST['if_efil']) == '') {
                $efil = 0;
            } else {
                $efil = $_REQUEST['if_efil'];
                $efil_no = $_REQUEST['txt_efil_no'];
                $efil_yr = $_REQUEST['ddl_efil_yr'];
            }
            if (!isset($_REQUEST['case_doc'])) {
                $_REQUEST['case_doc'] = 0;
            } else {
                $_REQUEST['case_doc'] = (!empty($_REQUEST['case_doc'])) ? $_REQUEST['case_doc'] : 0;
            }
            if ($_REQUEST['st_status'] == '0') {

                $da = 0;
                $res_nt = $this->Model_casetype->select('nature,casename')->where('casecode', $_REQUEST['ddl_nature'])->get()->getRowArray();

                $res_nt['nature'];
                $res_nt['casename'];

                $pet_cause_title = $res_cause_title = '';

                if ($_REQUEST['p_type'] == 'I' && $_REQUEST['r_type'] == 'I') {
                    $pet_cause_title = strtoupper(trim($_REQUEST['pname']));
                    $res_cause_title = strtoupper(trim($_REQUEST['rname']));

                    // echo "first insertion";

                    $insert_q = [
                        'pet_name' => $pet_cause_title,
                        'res_name' => $res_cause_title,
                        'pet_adv_id' => $padvno_and_yr,
                        'res_adv_id' => $radvno_and_yr,
                        'diary_no' => $diary_no,
                        'diary_no_rec_date' => date("Y-m-d H:i:s"),
                        'diary_user_id' => $ucode,
                        'ref_agency_state_id' => $_REQUEST['ddl_st_agncy'],
                        'ref_agency_code_id' => $_REQUEST['ddl_bench'],
                        'c_status' => 'P',
                        'case_grp' => $res_nt['nature'],
                        'casetype_id' => $_REQUEST['ddl_nature'],
                        'from_court' => $_REQUEST['ddl_court'],
                        'padvt' => $_REQUEST['padtype'],
                        'radvt' => $_REQUEST['radtype'],
                        'case_status_id' => 1,
                        'nature' => $_REQUEST['type_special'],
                        'pno' => $_REQUEST['t_pet'],
                        'rno' => $_REQUEST['t_res'],
                        'if_sclsc' => $sclsc,
                        'section_id' => $_REQUEST['section'],
                        'dacode' => $da,
                        'case_pages' => $_REQUEST['case_doc'],
                        'ack_id' => $efil_no,
                        'ack_rec_dt' => $efil_yr,



                        'active_fil_no' => '',
                        'fil_no_old' => '',
                        'res_name_old' => '',
                        'old_dacode' => 0,
                        'old_da_ec_case' => 0,
                        'scr_user' => 0,
                        'scr_type' => 'fn',
                        'ref_agency_state_id_old' => 0,
                        'undertaking_doc_type' => 0,
                        'undertaking_reason' => '',
                        'active_casetype_id' => 0,
                        'total_court_fee' => 0,
                        'court_fee' => 0,
                        'valuation' => 0,
                        'brief_description' => '',
                        'fil_no_fh' => '',
                        'fil_no_fh_old' => '',
                        'active_reg_year' => 0,
                        'reg_year_mh' => 0,
                        'reg_year_fh' => 0,
                        'reg_no_display' => '',


                        'create_modify' => date("Y-m-d H:i:s"),
                        'updated_by' => session()->get('login')['usercode'],
                        'updated_by_ip' => getClientIP(),
                    ];
                } else if ($_REQUEST['p_type'] == 'I' && $_REQUEST['r_type'] != 'I') {
                    $pet_cause_title = strtoupper(trim($_REQUEST['pname']));
                    if ($_REQUEST['r_cause_t1'] == 1)
                        $res_cause_title = strtoupper(trim($_REQUEST['res_statename'])) . ' ';
                    if ($_REQUEST['r_cause_t2'] == 1)
                        $res_cause_title .= strtoupper(trim($_REQUEST['res_deptt'])) . ' ';
                    if ($_REQUEST['r_cause_t3'] == 1)
                        $res_cause_title .= strtoupper(trim($_REQUEST['res_post'])) . ' ';
                    $res_cause_title = rtrim(trim($res_cause_title), ',');

                    $insert_q = [
                        'pet_name' => $pet_cause_title,
                        'res_name' => $res_cause_title,
                        'pet_adv_id' => $padvno_and_yr,
                        'res_adv_id' => $radvno_and_yr,
                        'diary_no' => $diary_no,
                        'diary_no_rec_date' => date("Y-m-d H:i:s"),
                        'diary_user_id' => $ucode,
                        'ref_agency_state_id' => $_REQUEST['ddl_st_agncy'],
                        'ref_agency_code_id' => $_REQUEST['ddl_bench'],
                        'c_status' => 'P',
                        'case_grp' => $res_nt['nature'],
                        'casetype_id' => $_REQUEST['ddl_nature'],
                        'from_court' => $_REQUEST['ddl_court'],
                        'padvt' => $_REQUEST['padtype'],
                        'radvt' => $_REQUEST['radtype'],
                        'case_status_id' => 1,
                        'nature' => $_REQUEST['type_special'],
                        'pno' => $_REQUEST['t_pet'],
                        'rno' => $_REQUEST['t_res'],
                        'if_sclsc' => $sclsc,
                        'section_id' => $_REQUEST['section'],
                        'dacode' => $da,
                        'case_pages' => $_REQUEST['case_doc'],
                        'ack_id' => $efil_no,
                        'ack_rec_dt' => $efil_yr,



                        'active_fil_no' => '',
                        'fil_no_old' => '',
                        'res_name_old' => '',
                        'old_dacode' => 0,
                        'old_da_ec_case' => 0,
                        'scr_user' => 0,
                        'scr_type' => 'fn',
                        'ref_agency_state_id_old' => 0,
                        'undertaking_doc_type' => 0,
                        'undertaking_reason' => '',
                        'active_casetype_id' => 0,
                        'total_court_fee' => 0,
                        'court_fee' => 0,
                        'valuation' => 0,
                        'brief_description' => '',
                        'fil_no_fh' => '',
                        'fil_no_fh_old' => '',
                        'active_reg_year' => 0,
                        'reg_year_mh' => 0,
                        'reg_year_fh' => 0,
                        'reg_no_display' => '',

                        'create_modify' => date("Y-m-d H:i:s"),
                        'updated_by' => session()->get('login')['usercode'],
                        'updated_by_ip' => getClientIP(),
                    ];
                } else if ($_REQUEST['p_type'] != 'I' && $_REQUEST['r_type'] == 'I') {
                    if ($_REQUEST['p_cause_t1'] == 1)
                        $pet_cause_title = strtoupper(trim($_REQUEST['pet_statename'])) . ' ';
                    if ($_REQUEST['p_cause_t2'] == 1)
                        $pet_cause_title .= strtoupper(trim($_REQUEST['pet_deptt'])) . ' ';
                    if ($_REQUEST['p_cause_t3'] == 1)
                        $pet_cause_title .= strtoupper(trim($_REQUEST['pet_post'])) . ' ';
                    $pet_cause_title = rtrim(trim($pet_cause_title), ',');
                    $res_cause_title = strtoupper(trim($_REQUEST['rname']));

                    // echo "third insertion"."<br>";
                    $insert_q = [
                        'pet_name' => $pet_cause_title,
                        'res_name' => $res_cause_title,
                        'pet_adv_id' => $padvno_and_yr,
                        'res_adv_id' => $radvno_and_yr,
                        'diary_no' => $diary_no,
                        'diary_no_rec_date' => date("Y-m-d H:i:s"),
                        'diary_user_id' => $ucode,
                        'ref_agency_state_id' => $_REQUEST['ddl_st_agncy'],
                        'ref_agency_code_id' => $_REQUEST['ddl_bench'],
                        'c_status' => 'P',
                        'case_grp' => $res_nt['nature'],
                        'casetype_id' => $_REQUEST['ddl_nature'],
                        'from_court' => $_REQUEST['ddl_court'],
                        'padvt' => $_REQUEST['padtype'],
                        'radvt' => $_REQUEST['radtype'],
                        'case_status_id' => 1,
                        'nature' => $_REQUEST['type_special'],
                        'pno' => $_REQUEST['t_pet'],
                        'rno' => $_REQUEST['t_res'],
                        'if_sclsc' => $sclsc,
                        'section_id' => $_REQUEST['section'],
                        'dacode' => $da,
                        'case_pages' => $_REQUEST['case_doc'],
                        'ack_id' => $efil_no,
                        'ack_rec_dt' => $efil_yr,



                        'active_fil_no' => '',
                        'fil_no_old' => '',
                        'res_name_old' => '',
                        'old_dacode' => 0,
                        'old_da_ec_case' => 0,
                        'scr_user' => 0,
                        'scr_type' => 'fn',
                        'ref_agency_state_id_old' => 0,
                        'undertaking_doc_type' => 0,
                        'undertaking_reason' => '',
                        'active_casetype_id' => 0,
                        'total_court_fee' => 0,
                        'court_fee' => 0,
                        'valuation' => 0,
                        'brief_description' => '',
                        'fil_no_fh' => '',
                        'fil_no_fh_old' => '',
                        'active_reg_year' => 0,
                        'reg_year_mh' => 0,
                        'reg_year_fh' => 0,
                        'reg_no_display' => '',

                        'create_modify' => date("Y-m-d H:i:s"),
                        'updated_by' => session()->get('login')['usercode'],
                        'updated_by_ip' => getClientIP(),
                    ];
                } else if ($_REQUEST['p_type'] != 'I' && $_REQUEST['r_type'] != 'I') {
                    if ($_REQUEST['p_cause_t1'] == 1)
                        $pet_cause_title = strtoupper(trim($_REQUEST['pet_statename'])) . ' ';
                    if ($_REQUEST['p_cause_t2'] == 1)
                        $pet_cause_title .= strtoupper(trim($_REQUEST['pet_deptt'])) . ' ';
                    if ($_REQUEST['p_cause_t3'] == 1)
                        $pet_cause_title .= strtoupper(trim($_REQUEST['pet_post'])) . ' ';
                    $pet_cause_title = rtrim(trim($pet_cause_title), ',');
                    if ($_REQUEST['r_cause_t1'] == 1)
                        $res_cause_title = strtoupper(trim($_REQUEST['res_statename'])) . ' ';
                    if ($_REQUEST['r_cause_t2'] == 1)
                        $res_cause_title .= strtoupper(trim($_REQUEST['res_deptt'])) . ' ';
                    if ($_REQUEST['r_cause_t3'] == 1)
                        $res_cause_title .= strtoupper(trim($_REQUEST['res_post'])) . ' ';
                    $res_cause_title = rtrim(trim($res_cause_title), ',');
                    echo "<br>";
                    $insert_q = [
                        'pet_name' => $pet_cause_title,
                        'res_name' => $res_cause_title,
                        'pet_adv_id' => $padvno_and_yr,
                        'res_adv_id' => $radvno_and_yr,
                        'diary_no' => $diary_no,
                        'diary_no_rec_date' => date("Y-m-d H:i:s"),
                        'diary_user_id' => $ucode,
                        'ref_agency_state_id' => $_REQUEST['ddl_st_agncy'],
                        'ref_agency_code_id' => $_REQUEST['ddl_bench'],
                        'c_status' => 'P',
                        'case_grp' => $res_nt['nature'],
                        'casetype_id' => $_REQUEST['ddl_nature'],
                        'from_court' => $_REQUEST['ddl_court'],
                        'padvt' => $_REQUEST['padtype'],
                        'radvt' => $_REQUEST['radtype'],
                        'case_status_id' => 1,
                        'nature' => $_REQUEST['type_special'],
                        'pno' => $_REQUEST['t_pet'],
                        'rno' => $_REQUEST['t_res'],
                        'if_sclsc' => $sclsc,
                        'section_id' => $_REQUEST['section'],
                        'dacode' => $da,
                        'case_pages' => $_REQUEST['case_doc'],
                        'ack_id' => $efil_no,
                        'ack_rec_dt' => $efil_yr,

                        'active_fil_no' => '',
                        'fil_no_old' => '',
                        'res_name_old' => '',
                        'old_dacode' => 0,
                        'old_da_ec_case' => 0,
                        'scr_user' => 0,
                        'scr_type' => 'fn',
                        'ref_agency_state_id_old' => 0,
                        'undertaking_doc_type' => 0,
                        'undertaking_reason' => '',
                        'active_casetype_id' => 0,
                        'total_court_fee' => 0,
                        'court_fee' => 0,
                        'valuation' => 0,
                        'brief_description' => '',
                        'fil_no_fh' => '',
                        'fil_no_fh_old' => '',
                        'active_reg_year' => 0,
                        'reg_year_mh' => 0,
                        'reg_year_fh' => 0,
                        'reg_no_display' => '',

                        'create_modify' => date("Y-m-d H:i:s"),
                        'updated_by' => session()->get('login')['usercode'],
                        'updated_by_ip' => getClientIP(),
                    ];

                    echo "<br>";
                }
            }

            //CI4 Insert
            // OR
            ///$is_Model_main=$this->Model_main->insert($insert_q); // insert but error return reason pk not default id

            $is_Model_main = insert('main', $insert_q);
            if (!empty($_REQUEST['priority_category']) && $_REQUEST['priority_category'] != 0) {
                $insert_special_category_filing = [
                    'diary_no' => $diary_no,
                    'ref_special_category_filing_id' => $_REQUEST['priority_category'],
                    'display' => 'Y',

                    'create_modify' => date("Y-m-d H:i:s"),
                    'updated_by' => session()->get('login')['usercode'],
                    'updated_by_ip' => getClientIP(),
                ];
                $is_special_category_filing = $this->Model_special_category_filing->insert($insert_special_category_filing);
            }

            $da_diary = '';
            if ((($_REQUEST['ct'] != 0) || (($_REQUEST['cno'] != 0)) || (($_REQUEST['cyr'] != 0))) || ($_REQUEST['dd']) != 0 && ($_REQUEST['dyr'] != 0)) {
                if (($_REQUEST['ddl_nature'] == 9) || ($_REQUEST['ddl_nature'] == 10) || ($_REQUEST['ddl_nature'] == 19) || ($_REQUEST['ddl_nature'] == 25) || ($_REQUEST['ddl_nature'] == 26) || ($_REQUEST['ddl_nature'] == 20) || ($_REQUEST['ddl_nature'] == 39)) {
                    //echo 'now gate active_fil_no,active_reg_year stop'; exit();

                    if (($_REQUEST['dd']) != 0 && ($_REQUEST['dyr'] != 0) && (!empty($_REQUEST['dd']) && $_REQUEST['dyr'])) {
                        $da_diary = trim(($_REQUEST['dd']) . ($_REQUEST['dyr']));

                        if (!empty($da_diary) && $da_diary != 0 && $da_diary != null) {
                            $row_caseno = $this->Model_main->select("active_fil_no,active_reg_year,DATE_PART('year',diary_no_rec_date) as diary_no_rec_date")->where('diary_no', $da_diary)->get()->getRowArray();
                            if (empty($row_caseno)) {
                                $row_caseno = $this->Model_main_a->select("active_fil_no,active_reg_year,DATE_PART('year',diary_no_rec_date) as diary_no_rec_date")->where('diary_no', $da_diary)->get()->getRowArray();
                            }
                            $case_no1 = $row_caseno['active_fil_no'];
                            $reg_year1 = $row_caseno['active_reg_year'];
                            $reg_year2 = $row_caseno['diary_no_rec_date'];


                            //echo '<pre>';print_r($row_caseno);exit();
                            if ($case_no1 == '') {

                                $_REQUEST['ct'] = 31;
                                $_REQUEST['cno'] = $_REQUEST['dd'];
                                $_REQUEST['cyr'] = $reg_year2;
                            } else {


                                $ed = explode('-', $case_no1);
                                $a1 = $ed[0];
                                $a2 = $ed[1];
                                //$a3 = $ed[2];

                                $_REQUEST['ct'] = $a1;
                                $_REQUEST['cno'] = $a2;
                                $_REQUEST['cyr'] = $reg_year1;
                            }
                        }
                    } else {
                        // lower court dacode to be updated
                        if (!empty($_REQUEST['ct']) && !empty($_REQUEST['cno']) && !empty($_REQUEST['cyr'])) {
                            $da_diary = get_diary_case_type($_REQUEST['ct'], $_REQUEST['cno'], $_REQUEST['cyr']);
                        } else {
                            $da_diary = '';
                        }
                    }
                    if (!empty($da_diary) && $da_diary != 0 && $da_diary != null) {
                        $da_res = tentative_da($da_diary);
                        if (!empty($da_res)) {
                            $da = $da_res['usercode'];
                            $c_status = $da_res['c_status'];
                        }


                        /*****************************************************************************************************************************************************/

                        /* code to copy advocate in case of MA */

                        if ($_REQUEST['ddl_nature'] == 39) {
                            //  echo "miscelleneous aplication ";

                            $is_sql_insert_advocate_by_da_diary = '';   
                            $rs_advocate = is_data_from_table('advocate', ['diary_no' => $da_diary, 'display' => 'Y']);
                            
                            if(empty($rs_advocate)) {
                                $rs_advocate = is_data_from_table('advocate_a', ['diary_no' => $da_diary, 'display' => 'Y']);
                            }

                            if (!empty($rs_advocate)) {
                                foreach ($rs_advocate as $rw_advocate) {
                                    $sql_insert_advocate_by_da_diary = [
                                        'diary_no' => $diary_no,   // diary number of the curent matter being diarized .
                                        'adv_type' => $rw_advocate['adv_type'],
                                        'pet_res' => $rw_advocate['pet_res'],
                                        'pet_res_no' => $rw_advocate['pet_res_no'],
                                        'advocate_id' => $rw_advocate['advocate_id'],
                                        'adv' => $rw_advocate['adv'],
                                        'usercode' => 1,
                                        'ent_dt' => date("Y-m-d H:i:s"),
                                        'display' => $rw_advocate['display'],
                                        'stateadv' => $rw_advocate['stateadv'],
                                        'old_adv' => $rw_advocate['old_adv'],
                                        'ent_by_caveat_advocate' => $rw_advocate['ent_by_caveat_advocate'],
                                        'remark' => $rw_advocate['remark'],
                                        'aor_state' => $rw_advocate['aor_state'],
                                        'pet_res_show_no' => $rw_advocate['pet_res_show_no'],
                                        'is_ac' => $rw_advocate['is_ac'],
                                        'writ_adv_remarks' => $rw_advocate['writ_adv_remarks'],

                                        'create_modify' => date("Y-m-d H:i:s"),
                                        'updated_by' => session()->get('login')['usercode'],
                                        'updated_by_ip' => getClientIP(),
                                    ];
                                    $is_sql_insert_advocate_by_da_diary = insert('advocate', $sql_insert_advocate_by_da_diary);
                                }
                            }
                            /* code to copy the parties  */

                            $rs_party_data = is_data_from_table('party', ['diary_no' => $da_diary, 'sr_no <> ' => 1, 'sr_no_show <> ' => '1', 'pflag <> ' => 'T']);
                            if(empty($rs_party_data)) {
                                $rs_party_data = is_data_from_table('party_a', ['diary_no' => $da_diary, 'sr_no <> ' => 1, 'sr_no_show <> ' => '1', 'pflag <> ' => 'T']);
                            }

                            $usercode = session()->get('login')['usercode'];

                            $tr = 1;
                            $is_sql_insert_party_by_da_diary = '';
                            if (!empty($rs_party_data)) {
                                foreach ($rs_party_data as $row) {
                                    $tr++;
                                    $sql_insert_party_by_da_diary = [
                                        'diary_no' => $row['diary_no'],
                                        'pet_res' => $row['pet_res'],
                                        'sr_no' => $row['sr_no'],
                                        'sr_no_show' => $row['sr_no_show'],
                                        'ind_dep' => $row['ind_dep'],
                                        'partysuff' => $row['partysuff'],
                                        'partyname' => $row['partyname'],
                                        'sonof' => $row['sonof'],
                                        'authcode' => $row['authcode'],
                                        'state_in_name' => $row['state_in_name'],
                                        'prfhname' => $row['prfhname'],
                                        'age' => $row['age'],
                                        'sex' => $row['sex'],
                                        'caste' => $row['caste'],
                                        'addr1' => $row['addr1'],
                                        'addr2' => $row['addr2'],
                                        'state' => $row['state'],
                                        'city' => $row['city'],
                                        'pin' => $row['pin'],
                                        'email' => $row['email'],
                                        'contact' => $row['contact'],
                                        'usercode' => $usercode,
                                        'ent_dt' => $row['ent_dt'],
                                        'pflag' => 'P',
                                        'dstname' => $row['dstname'],
                                        'deptcode' => $row['deptcode'],
                                        'pan_card' => $row['pan_card'],
                                        'adhar_card' => $row['adhar_card'],
                                        'country' => $row['country'],
                                        'education' => $row['education'],
                                        'occ_code' => $row['occ_code'],
                                        'edu_code' => $row['edu_code'],
                                        'lowercase_id' => $row['lowercase_id'],
                                        // 'auto_generated_id' => $row['auto_generated_id'],
                                        'remark_lrs' => $row['remark_lrs'],
                                        'remark_del' => $row['remark_del'],
                                        'cont_pro_info' => $row['cont_pro_info'],
                                        'last_dt' => $row['last_dt'],
                                        'last_usercode' => $row['last_usercode'],

                                        'create_modify' => date("Y-m-d H:i:s"),
                                        'updated_by' => session()->get('login')['usercode'],
                                        'updated_by_ip' => getClientIP(),

                                    ];
                                    $is_sql_insert_party_by_da_diary = insert('party', $sql_insert_party_by_da_diary);
                                }
                            }
                            if ($is_sql_insert_party_by_da_diary) {

                                echo '<div class="alert alert-success">'. $tr . " Records Found and Copied Successfully</div>";
                            }
                            //  else {
                            //     echo ("Error!!. Contact Server Room.");
                            // }


                            /* end of the code  - copy party in MA*/
                        }

                        /*   end of the code related to Copy of parties and advocates in case of Miscelleneous Applications   */


                        /****************************************************************************************************************************************************/


                        /******************  **************************************************************/
                        /*********code for copy category of main matter in instant matter*********/

                        //echo " the da diary is ". $da_diary;

                        $list_mul_category = is_data_from_table('mul_category', ['diary_no' => $da_diary, 'display' => 'Y']);
                        
                        if(empty($list_mul_category)) {
                            $list_mul_category = is_data_from_table('mul_category_a', ['diary_no' => $da_diary, 'display' => 'Y']);
                        }

                        if (!empty($list_mul_category)) {
                            foreach ($list_mul_category as $rw_cat) {
                                $submaster_id = $rw_cat['submaster_id'];
                                if ($submaster_id <> 331 and $submaster_id <> 9999) {

                                    if ((($_REQUEST['ddl_nature'] <> 19) && ($_REQUEST['ddl_nature'] <> 20)) || ($c_status == 'P' && (($_REQUEST['ddl_nature'] == 19) || ($_REQUEST['ddl_nature'] == 20)))) {
                                        $sql_insert_mul_category = [
                                            'diary_no' => $diary_no,
                                            'submaster_id' => $submaster_id,
                                            'display' => 'Y',
                                            'od_cat' => 0,
                                            'e_date' => date("Y-m-d H:i:s"),
                                            'mul_cat_user_code' => 1,

                                            'create_modify' => date("Y-m-d H:i:s"),
                                            'updated_by' => session()->get('login')['usercode'],
                                            'updated_by_ip' => getClientIP(),

                                        ];
                                        $is_sql_insert_mul_category = insert('mul_category', $sql_insert_mul_category);
                                    } else {
                                        if ($_REQUEST['ddl_nature'] == 19) {
                                            $submaster_id = 218;
                                            $sql_insert_mul_category = [
                                                'diary_no' => $diary_no,
                                                'submaster_id' => $submaster_id,
                                                'display' => 'Y',
                                                'od_cat' => 0,
                                                'e_date' => date("Y-m-d H:i:s"),
                                                'mul_cat_user_code' => 1,

                                                'create_modify' => date("Y-m-d H:i:s"),
                                                'updated_by' => session()->get('login')['usercode'],
                                                'updated_by_ip' => getClientIP(),

                                            ];
                                            $is_sql_insert_mul_category = insert('mul_category', $sql_insert_mul_category);
                                        }
                                        if ($_REQUEST['ddl_nature'] == 20) {
                                            $submaster_id = 219;
                                            $sql_insert_mul_category = [
                                                'diary_no' => $diary_no,
                                                'submaster_id' => $submaster_id,
                                                'display' => 'Y',
                                                'od_cat' => 0,
                                                'e_date' => date("Y-m-d H:i:s"),
                                                'mul_cat_user_code' => 1,

                                                'create_modify' => date("Y-m-d H:i:s"),
                                                'updated_by' => session()->get('login')['usercode'],
                                                'updated_by_ip' => getClientIP(),

                                            ];
                                            $is_sql_insert_mul_category = insert('mul_category', $sql_insert_mul_category);
                                        }
                                    }
                                }
                                if ($is_sql_insert_mul_category) {
                                    // echo " category successully copied";

                                }
                            }
                        }

                        // DA of main matter  //


                        /*****************retreival of disposal date of main matter*************************/


                        $rw_da_main_case_grp = is_data_from_table('main', ['diary_no' => $da_diary], 'case_grp', 'R');

                        if(empty($rw_da_main_case_grp)) {
                            $rw_da_main_case_grp = is_data_from_table('main_a', ['diary_no' => $da_diary], 'case_grp', 'R');
                        }

                        if (!empty($rw_da_main_case_grp)) {
                            $case_grp = $rw_da_main_case_grp['case_grp'];
                            $update_query_result = update('main', ['case_grp' => $case_grp], ['diary_no' => $diary_no]);
                        }


                        $rw_disposal_date = is_data_from_table('dispose', ['diary_no' => $da_diary], 'disp_dt,jud_id', 'R');
                        if (!empty($rw_disposal_date)) {

                            $disp_date = $rw_disposal_date['disp_dt'];
                            $jud_id = $rw_disposal_date['jud_id'];

                            $tc = substr_count("$jud_id", ",");
                            $a = explode(",", $jud_id);
                            $sql_main_matter_lowerct = [
                                'diary_no' => $diary_no,
                                'usercode' => 1,
                                'ct_code' => 4,
                                'l_state' => 490506,
                                'lw_display' => 'Y',
                                'lct_casetype' => $_REQUEST['ct'],
                                'lct_caseno' => $_REQUEST['cno'],
                                'lct_caseyear' => $_REQUEST['cyr'],
                                'is_order_challenged' => 'Y',
                                'full_interim_flag' => 'F',
                                'lct_dec_dt' => $disp_date,
                                'l_dist' => 10000,
                                'ent_dt' => date("Y-m-d H:i:s"),
                                'create_modify' => date("Y-m-d H:i:s"),
                                'updated_by' => session()->get('login')['usercode'],
                                'updated_by_ip' => getClientIP(),

                            ];
                            $is_sql_main_matter_lowerct = insert('lowerct', $sql_main_matter_lowerct);

                            $rw_judge = is_data_from_table('lowerct', ['diary_no' => $diary_no], 'lower_court_id', 'R');
                            $judge_lowerct = $rw_judge['lower_court_id'];   // id against which jugdes id to be stored in lowerct_judges table.
                            
                            if (!empty($tc) && !empty($a)) {
                                for ($i = 0; $i < ($tc + 1); $i++) {
                                if(!empty($a[$i]))
                                  {
                                        $sql_lowerct_judges = [
                                            'lowerct_id' => $judge_lowerct,
                                            'judge_id' => $a[$i], //(!empty($a[$i])) ? $a[$i] : NULL,
                                            'lct_display' => 'Y',

                                            'create_modify' => date("Y-m-d H:i:s"),
                                            'updated_by' => session()->get('login')['usercode'],
                                            'updated_by_ip' => getClientIP(),

                                        ];
                                    
                                        $is_sql_lowerct_judges = insert('lowerct_judges', $sql_lowerct_judges);
                                    


                                        if ((($_REQUEST['ddl_nature'] == 9) || ($_REQUEST['ddl_nature'] == 10) || ($_REQUEST['ddl_nature'] == 25) || ($_REQUEST['ddl_nature'] == 26)) && ($_REQUEST['ddl_nature'] <> 19) && ($_REQUEST['ddl_nature'] <> 20) && ($_REQUEST['ddl_nature'] <> 39)) {
                                            if ($a[$i] == 0) {
                                                continue;
                                            }
                                            $rs_j = is_data_from_table('master.judge', ['is_retired' => 'N', 'jcode' => $a[$i]]);
                                            if (empty($rs_j)) {
                                                continue;
                                            }
                                            $sql_ins_bef_not = [
                                                'diary_no' => $judge_lowerct,
                                                'j1' => $a[$i],
                                                'notbef' => 'B',
                                                'usercode' => 1,
                                                'ent_dt' => date("Y-m-d H:i:s"),
                                                'enterby' => 19,
                                                'u_ip' => getClientIP(),
                                                'u_mac' => ' ',
                                                'res_add' => ' ',
                                                'res_id' => 0,

                                                'create_modify' => date("Y-m-d H:i:s"),
                                                'updated_by' => session()->get('login')['usercode'],

                                                'updated_by_ip' => getClientIP(),

                                            ];
                                            $is_sql_ins_bef_not = insert('not_before', $sql_ins_bef_not);
                                        }
                                    }
                                }
                            }
                        }
                    }
                    //---------------------------  End of code for copying  category  of main matter ------------------------------------  //

                    ///---------------------------------code for insetion in not before table--------------------------------------//

                    /****************************************************/
                }          // end of the code block of if condition   for review.contempt curative petitions

            }

            if ($da != 0) {

                $update_main_dacode = ['dacode' => $da];
                //CI4 Update
                $is_efected_update_main_dacode = update('main', $update_main_dacode, ['diary_no' => $diary_no]);
            }


            if ($_REQUEST['p_type'] == 'I') {


                $insert_party1_p_q = [
                    'pet_res' => 'P',
                    'sr_no' => 1,
                    'sr_no_show' => 1,
                    'ind_dep' => $_REQUEST['p_type'],
                    'partyname' => (!empty($_REQUEST['pname'])) ? strtoupper(trim($_REQUEST['pname'])) : null,
                    'partysuff' => (!empty($_REQUEST['pname'])) ? strtoupper(trim($_REQUEST['pname'])) : null,
                    'prfhname' => (!empty($_REQUEST['pet_rel_name'])) ? strtoupper(trim($_REQUEST['pet_rel_name'])) : null,
                    'age' => (!empty($_REQUEST['p_age'])) ? $_REQUEST['p_age'] : null,
                    'sex' => (!empty($_REQUEST['p_sex'])) ? $_REQUEST['p_sex'] : null,
                    'addr1' => (!empty($_REQUEST['pocc'])) ? strtoupper(trim($_REQUEST['pocc'])) : null,
                    'addr2' => (!empty($_REQUEST['padd'])) ? strtoupper(trim($_REQUEST['padd'])) : null,
                    'dstname' => (!empty($_REQUEST['pcity'])) ? strtoupper(trim($_REQUEST['pcity'])) : null,
                    'state' => (!empty($_REQUEST['pst'])) ? $_REQUEST['pst'] : null,
                    'city' => (!empty($_REQUEST['pdis'])) ? $_REQUEST['pdis'] : null,
                    'pin' => (!empty($_REQUEST['pp'])) ? $_REQUEST['pp'] : null,
                    'email' => (!empty($_REQUEST['pemail'])) ? $_REQUEST['pemail'] : null,
                    'contact' => (!empty($_REQUEST['pmob'])) ? $_REQUEST['pmob'] : null,
                    'usercode' => $ucode,
                    'ent_dt' =>  date("Y-m-d H:i:s"),
                    'pflag' => 'P',
                    'sonof' => (!empty($_REQUEST['pet_rel'])) ? $_REQUEST['pet_rel'] : null,
                    'authcode' => 0,
                    'deptcode' => 0,
                    'diary_no' => $diary_no,
                    'country' => (!empty($_REQUEST['p_cont'])) ? $_REQUEST['p_cont'] : null,


                    'create_modify' => date("Y-m-d H:i:s"),
                    'updated_by' => session()->get('login')['usercode'],
                    'updated_by_ip' => getClientIP(),
                ];
            } else if ($_REQUEST['p_type'] != 'I') {

                $insert_party1_p_q = [
                    'pet_res' => 'P',
                    'sr_no ' => 1,
                    'sr_no_show' => 1,
                    'ind_dep' => $_REQUEST['p_type'],
                    'partyname' => $pet_cause_title,
                    'partysuff' => (!empty($_REQUEST['pet_statename']) || !empty($_REQUEST['pet_deptt'])) ? strtoupper(trim($_REQUEST['pet_statename'] . ' ' . $_REQUEST['pet_deptt'])) : null,
                    'prfhname' => strtoupper(trim($_REQUEST['pet_rel_name'])),
                    'age' => (!empty($_REQUEST['p_age'])) ? $_REQUEST['p_age'] : null,
                    'sex' => (!empty($_REQUEST['p_sex'])) ? $_REQUEST['p_sex'] : null,
                    'addr1' => (!empty($_REQUEST['pet_post'])) ? strtoupper(trim($_REQUEST['pet_post'])) : null,
                    'addr2' => (!empty($_REQUEST['padd'])) ? strtoupper(trim($_REQUEST['padd'])) : null,
                    'dstname' => (!empty($_REQUEST['pcity'])) ? strtoupper(trim($_REQUEST['pcity'])) : null,
                    'state' => (!empty($_REQUEST['pst'])) ? $_REQUEST['pst'] : null,
                    'city' => (!empty($_REQUEST['pdis'])) ? $_REQUEST['pdis'] : null,
                    'pin' => (!empty($_REQUEST['pp'])) ? $_REQUEST['pp'] : null,
                    'email' => (!empty($_REQUEST['pemail'])) ? $_REQUEST['pemail'] : null,
                    'contact' => (!empty($_REQUEST['pmob'])) ? $_REQUEST['pmob'] : null,
                    'usercode' => $ucode,
                    'ent_dt' =>  date('Y-m-d'),
                    'pflag' => 'P',
                    'sonof' => (!empty($_REQUEST['pet_rel'])) ? $_REQUEST['pet_rel'] : null,
                    'authcode' => (!empty($_REQUEST['pp_code'])) ? $_REQUEST['pp_code'] : null,
                    'deptcode' => (!empty($_REQUEST['pd_code'])) ? $_REQUEST['pd_code'] : null,
                    'diary_no' => $diary_no,
                    'state_in_name' => (!empty($_REQUEST['pet_statename_hd'])) ? $_REQUEST['pet_statename_hd'] : null,
                    'country' => (!empty($_REQUEST['p_cont'])) ? $_REQUEST['p_cont'] : null,


                    'create_modify' => date("Y-m-d H:i:s"),
                    'updated_by' => session()->get('login')['usercode'],
                    'updated_by_ip' => getClientIP(),
                ];
            }

            if ($_REQUEST['r_type'] == 'I') {



                $insert_party1_r_q = [
                    'pet_res' => 'R',
                    'sr_no ' => 1,
                    'sr_no_show' => 1,
                    'ind_dep' => (!empty($_REQUEST['r_type'])) ? $_REQUEST['r_type'] : null,
                    'partyname' => (!empty($_REQUEST['rname'])) ? strtoupper(trim($_REQUEST['rname'])) : null,
                    'partysuff' => (!empty($_REQUEST['rname'])) ? strtoupper(trim($_REQUEST['rname'])) : null,
                    'prfhname' => (!empty($_REQUEST['res_rel_name'])) ? strtoupper(trim($_REQUEST['res_rel_name'])) : null,
                    'age' => (!empty($_REQUEST['r_age'])) ? $_REQUEST['r_age'] : null,
                    'sex' => (!empty($_REQUEST['r_sex'])) ? $_REQUEST['r_sex'] : null,
                    'addr1' => (!empty($_REQUEST['rocc'])) ? strtoupper(trim($_REQUEST['rocc'])) : null,
                    'addr2' => (!empty($_REQUEST['radd'])) ? strtoupper(trim($_REQUEST['radd'])) : null,
                    'dstname' => (!empty($_REQUEST['rcity'])) ? strtoupper(trim($_REQUEST['rcity'])) : null,
                    'state' => (!empty($_REQUEST['rst'])) ? $_REQUEST['rst'] : null,
                    'city' => (!empty($_REQUEST['rdis'])) ? $_REQUEST['rdis'] : null,
                    'pin' => (!empty($_REQUEST['rp'])) ? $_REQUEST['rp'] : null,
                    'email' => (!empty($_REQUEST['remail'])) ? $_REQUEST['remail'] : null,
                    'contact' => (!empty($_REQUEST['rmob'])) ? $_REQUEST['rmob'] : null,
                    'usercode' => (!empty($ucode)) ? $ucode : null,
                    'ent_dt' =>  date("Y-m-d H:i:s"),
                    'pflag' => 'P',
                    'sonof' => (!empty($_REQUEST['res_rel'])) ? $_REQUEST['res_rel'] : null,
                    'authcode' => 0,
                    'deptcode' => 0,
                    'diary_no' => $diary_no,
                    'country' => (!empty($_REQUEST['r_cont'])) ? $_REQUEST['r_cont'] : null,


                    'create_modify' => date("Y-m-d H:i:s"),
                    'updated_by' => session()->get('login')['usercode'],
                    'updated_by_ip' => getClientIP(),
                ];
            } else if ($_REQUEST['r_type'] != 'I') {


                $insert_party1_r_q = [
                    'pet_res' => 'R',
                    'sr_no ' => 1,
                    'sr_no_show' => 1,
                    'ind_dep' => (!empty($_REQUEST['r_type'])) ? $_REQUEST['r_type'] : null,
                    'partyname' => $res_cause_title,
                    'partysuff' => (!empty($_REQUEST['res_statename']) || !empty($_REQUEST['res_deptt'])) ? strtoupper(trim($_REQUEST['res_statename'] . ' ' . $_REQUEST['res_deptt'])) : null,
                    'prfhname' => (!empty($_REQUEST['res_rel_name'])) ? strtoupper(trim($_REQUEST['res_rel_name'])) : null,
                    'age' => (!empty($_REQUEST['r_age'])) ? $_REQUEST['r_age'] : null,
                    'sex' => (!empty($_REQUEST['r_sex'])) ? $_REQUEST['r_sex'] : null,
                    'addr1' => (!empty($_REQUEST['res_post'])) ? strtoupper(trim($_REQUEST['res_post'])) : null,
                    'addr2' => (!empty($_REQUEST['radd'])) ? strtoupper(trim($_REQUEST['radd'])) : null,
                    'dstname' => (!empty($_REQUEST['rcity'])) ? strtoupper(trim($_REQUEST['rcity'])) : null,
                    'state' => (!empty($_REQUEST['rst'])) ? $_REQUEST['rst'] : null,
                    'city' => (!empty($_REQUEST['rdis'])) ? $_REQUEST['rdis'] : null,
                    'pin' => (!empty($_REQUEST['rp'])) ? $_REQUEST['rp'] : null,
                    'email' => (!empty($_REQUEST['remail'])) ? $_REQUEST['remail'] : null,
                    'contact' => (!empty($_REQUEST['rmob'])) ? $_REQUEST['rmob'] : null,
                    'usercode' => (!empty($ucode)) ? $ucode : null,
                    'ent_dt' =>  date("Y-m-d H:i:s"),
                    'pflag' => 'P',
                    'sonof' => (!empty($_REQUEST['res_rel'])) ? $_REQUEST['res_rel'] : null,
                    'authcode' => (!empty($_REQUEST['rp_code'])) ? $_REQUEST['rp_code'] : null,
                    'deptcode' => (!empty($_REQUEST['rd_code'])) ? $_REQUEST['rd_code'] : null,
                    'diary_no' => $diary_no,
                    'state_in_name' => (!empty($_REQUEST['res_statename_hd'])) ? $_REQUEST['res_statename_hd'] : null,
                    'country' => (!empty($_REQUEST['r_cont'])) ? $_REQUEST['r_cont'] : null,


                    'create_modify' => date("Y-m-d H:i:s"),
                    'updated_by' => session()->get('login')['usercode'],
                    'updated_by_ip' => getClientIP(),
                ];
            }


            $is_Model_party_p = insert('party', $insert_party1_p_q);
            $is_Model_party_r = insert('party', $insert_party1_r_q);
            $case_no = 0;
            if ($_REQUEST['hd_mn'] != '' && $_REQUEST['cs_tp'] != '' && $_REQUEST['txtFNo'] != '' && $_REQUEST['txtYear'] != '') {

                $case_no = $cs_tp . $_REQUEST['txtFNo'] . $_REQUEST['txtYear'];
                $_REQUEST['txtFNo'] = ltrim($_REQUEST['txtFNo'], 0);


                $ins_l_c = [
                    'ct_code' => $_REQUEST['ddl_court'],
                    'l_state ' => $_REQUEST['ddl_st_agncy'],
                    'l_dist' => $_REQUEST['ddl_bench'],
                    'diary_no' => $diary_no,
                    'lw_display' => 'R',
                    'lct_casetype' => $_REQUEST['cs_tp'],
                    'lct_caseno' => $_REQUEST['txtFNo'],
                    'lct_caseyear' => $_REQUEST['txtYear'],

                    'create_modify' => date("Y-m-d H:i:s"),
                    'updated_by' => session()->get('login')['usercode'],
                    'updated_by_ip' => getClientIP(),
                ];


                $lowerct_last_insert = insert('lowerct', $ins_l_c);
            }


            $update_cnt_diary_no = ['max_diary_no' => $fil];

            $is_efected_max_diary_no = update('master.cnt_diary_no', $update_cnt_diary_no, ['diary_no_year' => $year]);

            if ($padvno_and_yr != 0) {
                if ($_REQUEST['padtype'] == 'SS')
                    $_REQUEST['padtype'] = 'A';
                $in_person_mob = '';
                $in_person_email = '';
                if ($padvno_and_yr == 584) {
                    $in_person_mob = $_REQUEST['pmob'];
                    $in_person_email = $_REQUEST['pemail'];
                }



                $ins_adv_pet = [
                    'diary_no' => $diary_no,
                    'adv_type ' => 'M',
                    'pet_res' => 'P',
                    'pet_res_no' => 1,
                    'advocate_id' => $padvno_and_yr,
                    'usercode' => $ucode,
                    'ent_dt' =>  date("Y-m-d H:i:s"),
                    'display' => 'Y',
                    'stateadv' => 'N',
                    'aor_state' => $_REQUEST['padtype'],
                    'is_ac' => $_REQUEST['is_ac'],
                    'pet_res_show_no' => 1,
                    'inperson_mobile' => $in_person_mob,
                    'inperson_email' => $in_person_email,


                    'create_modify' => date("Y-m-d H:i:s"),
                    'updated_by' => session()->get('login')['usercode'],
                    'updated_by_ip' => getClientIP(),
                ];

                $advocate_pet_ins_adv = insert('advocate', $ins_adv_pet);
            }
            if ($radvno_and_yr != 0) {
                if ($_REQUEST['radtype'] == 'SS')
                    $_REQUEST['radtype'] = 'A';

                $in_person_mob_r = '';
                $in_person_email_r = '';
                if ($radvno_and_yr == 585) {
                    $in_person_mob_r = $_REQUEST['rmob'];
                    $in_person_email_r = $_REQUEST['remail'];
                }


                $ins_adv_res = [
                    'diary_no' => $diary_no,
                    'adv_type ' => 'M',
                    'pet_res' => 'R',
                    'pet_res_no' => 1,
                    'advocate_id' => $radvno_and_yr,
                    'usercode' => $ucode,
                    'ent_dt' =>  date("Y-m-d H:i:s"),
                    'display' => 'Y',
                    'stateadv' => 'N',
                    'aor_state' => $_REQUEST['radtype'],
                    'is_ac' => $_REQUEST['ris_ac'],
                    'pet_res_show_no' => 1,
                    'inperson_mobile' => $in_person_mob_r,
                    'inperson_email' => $in_person_email_r,

                    'create_modify' => date("Y-m-d H:i:s"),
                    'updated_by' => session()->get('login')['usercode'],
                    'updated_by_ip' => getClientIP(),
                ];

                $advocate_res_ins_adv = insert('advocate', $ins_adv_res);
            }
            //echo '<br>advocate end point line done';
            if (isset($_REQUEST['ext_address']) && $_REQUEST['ext_address'] != '') {
                $is_res_party_id = is_data_from_table('party', ['diary_no' => $diary_no, 'pet_res' => 'P', 'sr_no_show' => '1', 'pflag' => 'P'], 'auto_generated_id', 'R');
                if (!empty($is_res_party_id)) {
                    $res_party_id = $is_res_party_id['auto_generated_id'];
                    $ex_address = explode('^', $_REQUEST['ext_address']);
                    for ($index = 0; $index < count($ex_address); $index++) {
                        $in_exp = explode('~', $ex_address[$index]);
                        $pet_add_det = '';
                        if (!empty($in_exp[1]) && $in_exp[2] && $in_exp[3] && $in_exp[0]) {
                            $pet_add_det = is_data_from_table('party_additional_address', ['party_id' => $res_party_id, 'country' => $in_exp[1], 'state' => $in_exp[2], 'district' => $in_exp[3], 'address' => $in_exp[0]], '*', 'A');
                        }
                        // echo print_r($pet_add_det);
                        if (empty($pet_add_det)) {
                            $ins_party_additional_address = [
                                'country' => $in_exp[1],
                                'state ' => $in_exp[2],
                                'district' => $in_exp[3],
                                'address' => $in_exp[0],
                                'party_id' => $res_party_id,

                                'create_modify' => date("Y-m-d H:i:s"),
                                'updated_by' => session()->get('login')['usercode'],
                                'updated_by_ip' => getClientIP(),
                            ];

                            $is_ins_party_additional_address = insert('party_additional_address', $ins_party_additional_address);
                        }
                    }
                }
            }
            if (isset($_REQUEST['ext_address_r']) && $_REQUEST['ext_address_r'] != '') {
                $is_res_party_id_r = is_data_from_table('party', ['diary_no' => $diary_no, 'pet_res' => 'R', 'sr_no_show' => '1', 'pflag' => 'P'], 'auto_generated_id', 'R');
                if (!empty($is_res_party_id_r)) {
                    $res_party_id_r = $is_res_party_id_r['auto_generated_id'];
                    $ex_address_r = explode('^', $_REQUEST['ext_address_r']);
                    for ($index = 0; $index < count($ex_address_r); $index++) {
                        $in_exp_r = explode('~', $ex_address_r[$index]);
                        $res_add_det_r = '';
                        if (!empty($in_exp_r[1]) && $in_exp_r[2] && $in_exp_r[3] && $in_exp_r[0]) {
                            $res_add_det_r = is_data_from_table('party_additional_address', ['party_id' => $res_party_id_r, 'country' => $in_exp_r[1], 'state' => $in_exp_r[2], 'district' => $in_exp_r[3], 'address' => $in_exp_r[0]]);
                        }
                        if (empty($res_add_det_r)) {

                            $ins_party_additional_address_r = [
                                'country' => $in_exp_r[1],
                                'state ' => $in_exp_r[2],
                                'district' => $in_exp_r[3],
                                'address' => $in_exp_r[0],
                                'party_id' => $res_party_id_r,

                                'create_modify' => date("Y-m-d H:i:s"),
                                'updated_by' => session()->get('login')['usercode'],
                                'updated_by_ip' => getClientIP(),
                            ];

                            $is_ins_party_additional_address_r = insert('party_additional_address', $ins_party_additional_address_r);
                        }
                    }
                }
            }

            ?>
            <?php


            $check_if_fil_user = 0;
            //Jail petition matters should not be marked to data entry users
            $not_to_insert = array(11, 12, 19, 25, 26, 9, 10, 39);
            if (!in_array($_REQUEST['ddl_nature'], $not_to_insert)) {
                $check_if_fil_user = 1;
                //echo "allloted to ".$alloted_to[0];
            }


            $check_marking_rs = is_data_from_table('mark_all_for_hc', ['display' => 'Y']);
            if ($check_marking_rs && !empty($check_marking_rs)) {
                if ($check_if_fil_user == 1) {
                    $alloted_to = allot_to_EC($diary_no, $ucode);
                    $alloted_to = explode('~', $alloted_to);
                }
            } else {
                $fil_type = '';
                if ($efil != 0)
                    $fil_type = 'E';
                else
                    $fil_type = 'P';
                $ucode = session()->get('login')['usercode'];
                if ($check_if_fil_user == 1) {
                    $alloted_to = allot_to_EC($diary_no, $ucode, $fil_type);
                    $alloted_to = explode('~', $alloted_to);
                }
            }


            $res_jail_ent_dt = is_data_from_table('jail_petition_details', ['jail_display' => 'Y', 'diary_no' => $diary_no], 'count(diary_no)', 'R');

            //echo '<pre>jail_petition_details=';print_r($res_jail_ent_dt); exit();
            if ($_REQUEST['type_special'] == 6) {

                if ($res_jail_ent_dt['count'] == 0) {
                    $insert_jail_ent_dt = [
                        'diary_no' => $diary_no,
                        'jailer_sign_dt' => $_REQUEST['txt_doc_signed'],
                        'jail_display' => 'Y',
                        'diary_no_entry_dt' => date("Y-m-d H:i:s"),

                        'create_modify' => date("Y-m-d H:i:s"),
                        'updated_by' => session()->get('login')['usercode'],
                        'updated_by_ip' => getClientIP(),
                    ];
                    $is_insert_jail_ent_dt = insert('jail_petition_details', $insert_jail_ent_dt);
                } else {
                    $update_jail_ent_dt = [
                        'jailer_sign_dt' => $_REQUEST['txt_doc_signed'],
                        'diary_no_entry_dt' => date("Y-m-d H:i:s"),

                        'updated_on' => date("Y-m-d H:i:s"),
                        'updated_by' => $_SESSION['login']['usercode'],
                        'updated_by_ip' => getClientIP(),
                    ];
                    $is_update_jail_ent_dt = update('jail_petition_details', $update_jail_ent_dt, ['jail_display' => 'Y', 'diary_no' => $diary_no]);
                }
            } else {
                if ($res_jail_ent_dt['count'] == 0) {
                    $update_jail_ent_dt = [
                        'jail_display' => 'N',
                        'updated_on' => date("Y-m-d H:i:s"),
                        'updated_by' => $_SESSION['login']['usercode'],
                        'updated_by_ip' => getClientIP(),
                    ];
                    $is_update_jail_ent_dt = update('jail_petition_details', $update_jail_ent_dt, ['jail_display' => 'Y', 'diary_no' => $diary_no]);
                }
            }





            $res_sclsc = is_data_from_table('sclsc_details', ['display' => 'Y', 'diary_no' => $diary_no], 'count(id)', 'R');
            if ($sclsc != 0) {
                if ($res_sclsc['count'] == 0) {
                    $ins_sclsc = [
                        'diary_no' => $diary_no,
                        'sclsc_diary_no' => $_REQUEST['txt_sclsc_no'],
                        'sclsc_diary_year' => $_REQUEST['ddl_sclsc_yr'],
                        'sclsc_ent_dt' => date("Y-m-d H:i:s"),
                        'display' => 'Y',

                        'create_modify' => date("Y-m-d H:i:s"),
                        'updated_by' => $_SESSION['login']['usercode'],
                        'updated_by_ip' => getClientIP(),
                    ];
                    $is_insert_ins_sclsc = insert('sclsc_details', $ins_sclsc);
                } else {
                    $update_sclsc = [
                        'sclsc_diary_no' => $_REQUEST['txt_sclsc_no'],
                        'sclsc_diary_year' => $_REQUEST['ddl_sclsc_yr'],
                        'sclsc_ent_dt' => date("Y-m-d H:i:s"),
                        'display' => 'Y',

                        'updated_on' => date("Y-m-d H:i:s"),
                        'updated_by' => $_SESSION['login']['usercode'],
                        'updated_by_ip' => getClientIP(),
                    ];
                    $is_update_sclsc = update('sclsc_details', $update_sclsc, ['display' => 'Y', 'diary_no' => $diary_no]);
                }
            } else if ($sclsc == 0) {
                if ($res_sclsc['count'] > 0) {
                    $update_sclsc = [
                        'display' => 'N',

                        'updated_on' => date("Y-m-d H:i:s"),
                        'updated_by' => $_SESSION['login']['usercode'],
                        'updated_by_ip' => getClientIP(),
                    ];
                    $is_update_sclsc = update('sclsc_details', $update_sclsc, ['display' => 'Y', 'diary_no' => $diary_no]);
                }
            }


            ?>
            <div style="padding: 30px;">
                <table align="center" width="100%" style="text-align: center;">
                    <thead>
                    <?php

                    if ($check_if_fil_user == 1 && !empty($alloted_to)) {
                    ?>
                        <tr>
                            <th style="color: #ff6600;font-size: 16px;">File Automatically Allotted to: <?php if (isset($alloted_to[1]) && !empty($alloted_to[1])) {
                                                                                                            echo $alloted_to[1];
                                                                                                        }
                                                                                                        if (isset($alloted_to[0]) && !empty($alloted_to[0])) {
                                                                                                            echo ' [' . $alloted_to[0] . ']';
                                                                                                        } ?></th>
                        </tr>
                    <?php
                    }
                    if (!empty($alloted_to)) {
                    ?>
                        <tr>
                            <th style="color: #ff6600;font-size: 16px;"><?php if (isset($alloted_to[2]) && !empty($alloted_to[2])) {
                                                                            echo $alloted_to[2];
                                                                        } ?></th>
                        </tr>
                    <?php
                    }
                    ?>

                    <tr align="center">
                        <th>Diary No.:<h2 style="color: blue"><?php echo $fil . '/' . $year; ?></h2>
                        </th>
                    </tr>

                    <tr align="center">
                        <th style="font-size: 17px;color: blue"><?php echo $pet_cause_title; ?></th>
                    </tr>
                    <tr align="center">
                        <th style="font-size: 14px;color: blue">Versus</th>
                    </tr>
                    <tr align="center">
                        <th style="font-size: 17px;color: blue"><?php echo $res_cause_title; ?></th>
                    </tr>
                    <tr align="center">
                        <th style="font-size: 17px;color: blue"><?php echo "Case Type: " . $res_nt['casename']; ?></th>
                    </tr>
                    <tr align="center">
                        <th style="font-size: 17px;color: blue"><?php echo "Filed by: " . strtoupper(trim($_REQUEST['padvname'])) . "(ADV)"; ?></th>
                    </tr>
                    <?php
                    if (isset($_REQUEST['ddl_st_agncy']) && isset($_REQUEST['ddl_bench'])) {

                        if (!empty($_REQUEST['ddl_st_agncy']) && !empty($_REQUEST['ddl_bench'])) {
                            $ref_agency_code_details = get_ref_agency_code_details($_REQUEST['ddl_st_agncy'], $_REQUEST['ddl_bench']);
                            if ($ref_agency_code_details && !empty($ref_agency_code_details)) {
                                echo "<tr aligh='center'><th style='font-size: 17px;color: blue'>Bench: " . strtoupper(trim($ref_agency_code_details['agency_state']))  . ' - ' . strtoupper(trim($ref_agency_code_details['agency_name'])) . "</th></tr>";
                            }
                    ?>

                    <?php }
                    } ?>
                    </thead>
                </table>
            </div>

            <?php
            $diary_copy = $fil . $year;
            $alpha = '';
            for ($index1 = 0; $index1 < 4; $index1++) {
                if ($index1 == 0)
                    $alpha = 'A';
                else if ($index1 == 1)
                    $alpha = 'B';
                if ($index1 == 2)
                    $alpha = 'C';
                if ($index1 == 3)
                    $alpha = 'D';
                $ins_diary_copy_set = [
                    'diary_no' => $diary_copy,
                    'copy_set' => $alpha,

                    'create_modify' => date("Y-m-d H:i:s"),
                    'updated_by' => $_SESSION['login']['usercode'],
                    'updated_by_ip' => getClientIP(),
                ];
                $is_ins_diary_copy_set = insert('diary_copy_set', $ins_diary_copy_set);
            }

            $_REQUEST['sms_status'] = 'DN';
            $_REQUEST['d_no'] = $fil;
            $_REQUEST['d_yr'] = $year;
        }
 

        $this->db->transComplete();

        /*
        if($this->db->transStatus() === FALSE)
            return FALSE;
        else
            return TRUE ;*/

        //all part of done after end point
        exit();
    }

    /*XXXXXXXXXXXXXXXXXXX end Intertion save_new_filing XXXXXXXXXXXXXXXXXX*/

    public function additional_address()
    {
        $sno = $_REQUEST['hd_add_address'];
        $p_r = $_REQUEST['p_r'];
        $data['sno'] = $sno;
        $data['p_r'] = $p_r;
        $data['country'] = get_from_table_json('country');
        $data['state_list'] = $this->Dropdown_list_model->get_address_state_list();
        return view('Filing/additional_address', $data);
        exit();
    }
    public function get_sc_parties()
    {
        $lccasecode = $_REQUEST['ct'];
        //$hd_mn = $_REQUEST['hd_mn'];
        if (!empty($lccasecode) && $lccasecode != null) {
            $get_st_cd = is_data_from_table('master.lc_hc_casetype', ['display' => 'Y', 'lccasecode' => $lccasecode], 'case_type', 'R');
            if (!empty($get_st_cd)) {
                $cs_tp = $get_st_cd['case_type'];
                if (strlen($cs_tp) == 2) {
                    $cs_tp = '0' . $cs_tp;
                }
            } else {
                //$response='case type is required!';
            }
        } else {
            //$response='data not found!!';
        }

        if (trim($_REQUEST['dno']) == '') {
            $diary_no = get_diary_case_type($_REQUEST['ct'], $_REQUEST['cno'], $_REQUEST['cyr']);
            //$diary_no = get_diary_no_from_casetype($_REQUEST['ct'], $_REQUEST['cno'], $_REQUEST['cyr']);
        } else {
            $diary_no = $_REQUEST['dno'] . $_REQUEST['dyr'];
        }
        if (empty($diary_no)) {
            $response = 'No Record Found!!';
            exit();
        }
        $res_c_fn = is_data_from_table('party', ['pflag' => 'P', 'diary_no' => $diary_no]);
        if (empty($res_c_fn)) {
            $res_c_fn = is_data_from_table('party_a', ['pflag' => 'P', 'diary_no' => $diary_no]);
        }
        if (!empty($res_c_fn)) {

            $is_party_pet = $this->get_sci_party($diary_no);
            $is_party_pet_a = $this->get_sci_party($diary_no, '_a');

            $is_party_res = $this->get_sci_party($diary_no, '', 'R');
            $is_party_res_a = $this->get_sci_party($diary_no, '_a', 'R');

            $data['party_petitioner'] = array_merge($is_party_pet, $is_party_pet_a);
            $data['party_respondent'] = array_merge($is_party_res, $is_party_res_a);
            $response = view('Filing/get_sc_parties', $data);
        } else {

            $response = '<div class="row">';    
            $response .= '<div class="col-sm-6">';
            $response .= '<table border="1" class="table table-striped custom-table showData dataTable no-footer dtr-inline" style="border-collapse: collapse" align="center">
                <thead><tr>
                    <th> S.No.</th>
                    <th> Petitioner</th>
                    <th>P</th>
                    <th>R</th>
                </tr> 
            </thead>
             <tr><td colspan="100%" style="text-align: center"><b>No Record Found...</b></td></tr></table>';
            $response .= '</div>
                        <div class="col-sm-6">';
            $response .= '<table border="1" class="table table-striped custom-table showData dataTable no-footer dtr-inline" style="border-collapse: collapse" align="center">
                <thead><tr>
                    <th>S.No.</th>
                    <th>Respondent</th>
                    <th> P </th>
                    <th>R</th>
                </tr></thead>
                 <tr><td colspan="100%" style="text-align: center"><b>No Record Found...</b></td></tr></table>';

            $response .= '</div></div>';
                                  


            //$response = 'No Record Found!!!';
        }

        echo $response;
        exit();
    }


    public function get_sci_party($diary_no, $is_archival_table = '', $pet_res = 'P')
    {
        $query = $this->db->table("party$is_archival_table a")
            ->select("partyname,pet_res,sr_no,ind_dep,authcode,prfhname,age,sex,addr1,addr2,
             state, city,pin,email,contact,dstname,a.deptcode,sonof,c.deptname")
            ->join('master.deptt c', "a.deptcode = c.deptcode and c.display='Y'", 'left')
            ->where('diary_no', $diary_no)
            ->where('pflag', 'P')
            ->where('pet_res', $pet_res)
            ->orderBy('pet_res,sr_no')
            ->get();
        $final_array = array();
        if ($query->getNumRows() >= 1) {
            $sci_party = $query->getResultArray();
            if (!empty($sci_party)) {
                foreach ($sci_party as $row) {
                    $final_array[] = [
                        'partyname' => !empty($row['partyname']) ? trim($row['partyname']) : '',
                        'pet_res' => !empty($row['pet_res']) ? trim($row['pet_res']) : '',
                        'sr_no' => !empty($row['sr_no']) ? trim($row['sr_no']) : '',
                        'ind_dep' => !empty($row['ind_dep']) ? trim($row['ind_dep']) : '',
                        'authcode' => !empty($row['authcode']) ? trim($row['authcode']) : '',
                        'prfhname' => !empty($row['prfhname']) ? trim($row['prfhname']) : '',
                        'age' => !empty($row['age']) ? trim($row['age']) : '',
                        'sex' => !empty($row['sex']) ? trim($row['sex']) : '',
                        'addr1' => !empty($row['addr1']) ? trim($row['addr1']) : '',
                        'addr2' => !empty($row['addr2']) ? trim($row['addr2']) : '',
                        'state' => !empty($row['state']) ? trim($row['state']) : '',
                        'city' => !empty($row['city']) ? trim($row['city']) : '',
                        'pin' => !empty($row['pin']) ? trim($row['pin']) : '',
                        'email' => !empty($row['email']) ? trim($row['email']) : '',
                        'contact' => !empty($row['contact']) ? trim($row['contact']) : '',
                        'dstname' => !empty($row['dstname']) ? trim($row['dstname']) : '',
                        'deptcode' => !empty($row['deptcode']) ? trim($row['deptcode']) : '',
                        'sonof' => !empty($row['sonof']) ? trim($row['sonof']) : '',
                        'deptname' => !empty($row['deptname']) ? trim($row['deptname']) : '',

                    ];
                }
            }
        }
        return $final_array;
    }
    public function get_party($f_no, $hd_mn, $is_archival_table = '')
    {
        $query = $this->db->table("party$is_archival_table a")
            ->select("partyname, pet_res, sr_no, ind_dep, authcode, prfhname, age, sex, addr1, addr2, dstcode, 
                (select id_no from state where State_code=state and branch=' . $this->db->escape($hd_mn) . ' and District_code=0 and Sub_Dist_code=0 and Village_code=0 and display='Y') as state, 
                (select id_no from state where State_code=state and branch=' . $this->db->escape($hd_mn) . ' and District_code=city and Sub_Dist_code=0 and Village_code=0 and display='Y') as city, 
                pin, email, contact, dstname, a.deptcode, sonof, c.deptname")
            ->join('master.deptt c', "a.deptcode = c.deptcode and c.display='Y'", 'left')
            ->where('fil_no', $f_no)
            ->where('pflag', 'P')
            ->where('pet_res', 'P')
            ->orderBy('pet_res, sr_no')
            ->get();
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }
}
