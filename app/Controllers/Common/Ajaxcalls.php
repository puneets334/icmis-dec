<?php

namespace App\Controllers\Common;

use App\Controllers\BaseController;
use App\Libraries\webservices\Efiling_webservices;
use App\Libraries\webservices\Highcourt_webservices;

use App\Models\Common\Dropdown_list_model;

class Ajaxcalls extends BaseController
{

    public $LoginModel;
    public $efiling_webservices;
    public $highcourt_webservices;
    public $Dropdown_list_model;

    function __construct()
    {
        $this->efiling_webservices = new Efiling_webservices();
        $this->highcourt_webservices = new Highcourt_webservices();
        $this->Dropdown_list_model = new Dropdown_list_model();
    }
    function get_high_court()
    {
        $cmis_state_id = (sanitize($_POST['cmis_state_id']));
        $high_courts = get_from_table_json('state', $cmis_state_id, 'cmis_state_id');
        $dropDownOptions = '<option value="">Select High Court</option>';
        foreach ($high_courts as $courts) {
            $dropDownOptions .= '<option value="' . sanitize($courts['cmis_state_id']) . '">' . sanitize(strtoupper($courts['state_name'])) . '</option>';
        }
        echo $dropDownOptions;
    }

    function get_hc_bench_list()
    {
        $high_court_id = (sanitize($_POST['high_court_id']));
        $court_type = $_POST['court_type'];
        $selected_bench = "";
        $dropDownOptions = '<option value="">Select High Court Bench</option>';
        if (!empty($high_court_id) && !empty($court_type)) {
            $hc_benches = $this->Dropdown_list_model->get_ref_agency_code($high_court_id, $court_type);            
            foreach ($hc_benches as $bench) {
                if (!empty($_POST['bench_id'])) {
                    if ($_POST['bench_id'] == $bench['id']) {
                        $selected_bench = "selected";
                    } else {
                        $selected_bench = "";
                    }
                }
                $dropDownOptions .= '<option value="' . sanitize($bench['id']) . '" ' . $selected_bench . '>' . sanitize(strtoupper($bench['agency_name'])) . '</option>';
            }
        }
        echo $dropDownOptions;
    }

    public function getAddressByPincode()
    {
        //$pincode = sanitize($_POST['pincode']);
        $pincode = sanitize($_GET['pincode']);
        $address = $this->Dropdown_list_model->getPincodeDetails($pincode);
        echo json_encode($address);
    }
    public function getSelectedState()
    {
        $states = $this->Dropdown_list_model->get_address_state_list();
        $stateIdName = array();
        foreach ($states as $state) {
            $tempArr = array();
            $tempArr['id'] = sanitize(trim($state['cmis_state_id']));
            $tempArr['state_name'] = sanitize(strtoupper(trim($state['agency_state'])));
            $stateIdName[] = (object)$tempArr;
        }
        echo json_encode($stateIdName);
    }
    public function get_address_state_list()
    {
        $stateId = sanitize($_GET['state_id']);
        $states = $this->Dropdown_list_model->get_address_state_list($stateId);
        $dropDownOptions = '<option value="">Select District</option>';
        foreach ($states as $state) {
            $dropDownOptions .= '<option value="' . sanitize(trim($state['cmis_state_id'])) . '">' . sanitize(strtoupper(trim($state['agency_state']))) . '</option>';
        }
        echo $dropDownOptions;
    }
    public function get_districts()
    {
        $state_id = $_GET['state_id'];
        $districts = $this->Dropdown_list_model->get_districts_list($state_id);
        $dropDownOptions = '<option value="">Select District</option>';
        foreach ($districts as $district) {
            $dropDownOptions .= '<option value="' . sanitize(trim($district['id_no'])) . '">' . sanitize(strtoupper(trim($district['name']))) . '</option>';
        }
        echo $dropDownOptions;exit();
    }

    public function getSelectedDistricts()
    {
        //$stateId = sanitize($_POST['state_id']);
        $stateId = sanitize($_GET['state_id']);
        $districts = $this->Dropdown_list_model->get_districts_list($stateId);
        $districtIdName = array();
        foreach ($districts as $district) {
            $tempArr = array();
            $tempArr['id'] = sanitize(trim($district['id_no']));
            $tempArr['district_name'] = sanitize(strtoupper(trim($district['name'])));
            $districtIdName[] = (object)$tempArr;
        }
        echo json_encode($districtIdName);
    }

    public function get_subcategories()
    {
        $main_category_id = (sanitize($_POST['main_category_id']));
        $selected_subcat_text = "";
        if (isset($_POST['selected_subcat'])) {
            $selected_subcat = $_POST['selected_subcat'];
        }

        $dropDownOptions = '<option value="">Select Sub Category</option>';
        if (!empty($main_category_id) && !empty($main_category_id)) {
            $sub_categories = $this->Dropdown_list_model->get_subcategory_list($main_category_id);
            if (!empty($sub_categories)) {
                foreach ($sub_categories as $sub_category) {
                    if (isset($_POST['selected_subcat']) && ($sub_category['id'] == $selected_subcat)) {
                        $selected_subcat_text = 'selected';
                    } else {
                        $selected_subcat_text = "";
                    }
                    $dropDownOptions .= '<option value="' . sanitize($sub_category['id']) . '" ' . $selected_subcat_text . '>'. $sub_category['category_sc_old'] .' - ' . sanitize(strtoupper($sub_category['sub_name4'])) . '</option>';
                }
            } else {
                $dropDownOptions = '<option value="" title="Select">Select SubCategory</option>';
            }
        }
        echo $dropDownOptions;
    }
    public function get_only_state_name()
    {
        $q = strtolower($_GET["term"]);
        if (!$q) return;
        $sql = "SELECT deptcode,deptname FROM (SELECT deptcode,deptname FROM master.deptt WHERE deptname ilike 'THE UNION TERRITORY%' OR deptname ilike 'THE STATE OF%' OR deptcode=2 )x WHERE deptname ilike '%$q%'";
        $query = $this->db->query($sql);
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            foreach ($result as $row) {
                $json[] = array(
                    'value' => $row['deptcode'] . '~' . $row['deptname'],
                    'label' => $row['deptname']
                );
            }
        } else {
            $json[] = array('value' => '' . '~' . '', 'label' => '');
        }

        echo json_encode($json);
    }
    public function get_aor_name()
    {
        $q = strtolower($_GET["term"]);
        if (!$q) return;
         $sql = "select aor_code,name from master.bar where isdead='N' and if_sen='N' and (name ilike '%".$q."%' or CAST(aor_code AS text) ilike '%".$q."')";
        //$sql = "select aor_code,name from master.bar where isdead='N' and if_sen='N' and aor_code='$q'";
        $query = $this->db->query($sql);
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            foreach ($result as $row) {
                $json[] = array('value' => $row['aor_code'], 'label' => $row['aor_code'] . '-' . $row['name']);
            }
        } else {
            $json[] = array('value' => '' . '~' . '', 'label' => '');
        }

        echo json_encode($json);
    }
    public function new_filing_autocomp_post()
    {
        if (!isset($_REQUEST['falagofpost'])) {
            $_REQUEST['falagofpost'] = '';
        }
        if ($_REQUEST['falagofpost'] != 'code') {
            $q = strtolower($_GET["term"]);
            if (!$q) return;
            $sql = "select authcode,authdesc from master.authority where display='Y' and authdesc ilike '%$q%'";
            $query = $this->db->query($sql);
            if ($query->getNumRows() >= 1) {
                $result = $query->getResultArray();
                foreach ($result as $row) {
                    $json[] = array('value' => $row['authcode'] . '~' . $row['authdesc'], 'label' => $row['authdesc']);
                }
            } else {
                $json[] = array('value' => '' . '~' . '', 'label' => '');
            }
            echo json_encode($json);
        } else {
            $val = $_REQUEST['val'];
            $sql = "select authcode from master.authority where display='Y' and authdesc ilike '%$val%'";
            $query = $this->db->query($sql);
            if ($query->getNumRows() >= 1) {
                return $result = $query->getResultArray(1);
                foreach ($result as $row) {
                    $json[] = array('authcode' => $row['authcode']);
                }
            } else {
                $json[] = array('authcode' => '');
            }
            echo json_encode($json);
        }
    }
    public function new_filing_autocomp_deptt()
    {
        if (!isset($_REQUEST['falagofpost'])) {
            $_REQUEST['falagofpost'] = '';
        }
        if ($_REQUEST['falagofpost'] != 'code') {
            $q = strtolower($_GET["term"]);
            if (!$q) return;

            if ($_REQUEST['type'] == 'D1')
                $type = " AND deptype='S' ";
            else if ($_REQUEST['type'] == 'D2')
                $type = " AND deptype='C' ";
            if ($_REQUEST['type'] == 'D3')
                $type = " AND deptype NOT IN('S','C')  ";

            $sql = "SELECT deptcode,deptname FROM master.deptt WHERE display = 'Y' $type and deptname ilike '%$q%'";
            $query = $this->db->query($sql);
            if ($query->getNumRows() >= 1) {
                $result = $query->getResultArray();
                foreach ($result as $row) {
                    $json[] = array('value' => $row['deptcode'] . '~' . $row['deptname'], 'label' => $row['deptname']);
                }
            } else {
                $json[] = array('value' => '' . '~' . '', 'label' => '');
            }
            echo json_encode($json);
        } else {
            $val = $_REQUEST['val'];
            $sql = "select deptcode from master.deptt where display='Y' and deptname ilike '%$val%'";
            $query = $this->db->query($sql);
            if ($query->getNumRows() >= 1) {
                return $result = $query->getResultArray(1);
                foreach ($result as $row) {
                    $json[] = array('deptcode' => $row['deptcode']);
                }
            } else {
                $json[] = array('deptcode' => '');
            }
            echo json_encode($json);
        }
    }

    function get_police_station_list()
    {
        $state_agency = (sanitize($_POST['state_agency']));
        $district_id = $_POST['district_id'];
        $dropDownOptions = '<option value="">Select Police Station</option>';
        if (!empty($state_agency) && !empty($district_id)) {

            $policeStations = $this->Dropdown_list_model->get_police_station_list($state_agency, $district_id);
            foreach ($policeStations as $police_station) {
                $dropDownOptions .= '<option value="' . sanitize($police_station['policestncd']) . '">' . sanitize(strtoupper($police_station['policestndesc'])) . '</option>';
            }
        }
        echo $dropDownOptions;
    }

    function get_case_types()
    {
        $state_agency = (sanitize($_POST['state_agency']));
        $court_type = $_POST['court_type'];
        $selected_case_type = "";

        $dropDownOptions = '<option value="">SELECT</option>';
        if (!empty($state_agency) && !empty($court_type)) {
            $caseTypes = $this->Dropdown_list_model->get_case_type_court($state_agency, $court_type);

            foreach ($caseTypes as $case_type) {
                if ($court_type == 4) {
                    if (!empty($_POST['case_type'])) {
                        if ($_POST['case_type'] == $case_type['casecode']) {
                            $selected_case_type = "selected";
                        } else {
                            $selected_case_type = "";
                        }
                    }
                   // pr($case_type);
                    $dropDownOptions .= '<option value="' . sanitize($case_type['casecode']) . '">' . sanitize(strtoupper($case_type['skey'])) . '</option>';
                } else {
                    if (!empty($_POST['case_type'])) {
                        if ($_POST['case_type'] == $case_type['lccasecode']) {
                            $selected_case_type = "selected";
                        } else {
                            $selected_case_type = "";
                        }
                    }
                    $dropDownOptions .= '<option value="' . sanitize($case_type['lccasecode']) . '">' . sanitize(strtoupper($case_type['type_sname'])) . '</option>';
                }
            }
        }
        echo $dropDownOptions;
    }

    function get_judges_list()
    {
        $state_agency = (sanitize($_POST['state_agency']));
        $court_type = $_POST['court_type'];
        $dropDownOptions = '<option value="" disabled>Select Judge</option>';
        if (!empty($state_agency) && !empty($court_type)) {
            $all_judges = $this->Dropdown_list_model->get_all_judges($state_agency, $court_type);

            foreach ($all_judges as $judge) {
                if ($court_type == 4) {
                    $dropDownOptions .= '<option value="' . sanitize($judge['jcode']) . '">' . sanitize(strtoupper($judge['first_name'] . " " . $judge['sur_name'])) . '</option>';
                } else {
                    $dropDownOptions .= '<option value="' . sanitize($judge['id']) . '">' . sanitize(strtoupper($judge['first_name'] . " " . $judge['sur_name'])) . '</option>';
                }
            }
        }
        echo $dropDownOptions;
    }

    function get_rto_code()
    {
        $state_agency = (sanitize($_POST['state_agency']));
        $dropDownOptions = '<option value="">Select Vehicle Number</option>';
        if (!empty($state_agency)) {
            $all_rto_code = $this->Dropdown_list_model->get_all_rtocode($state_agency);

            foreach ($all_rto_code as $rto_code) {
                $dropDownOptions .= '<option value="' . sanitize($rto_code['id']) . '">' . sanitize(strtoupper($rto_code['code'])) . '</option>';
            }
        }
        echo $dropDownOptions;
    }

    function get_m_from_court_list()
    {
        $court_type = (sanitize($_POST['court_type']));
        $dropDownOptions = '<option value="">SELECT</option>';
        if (!empty($court_type)) {
            $all_types_list = $this->Dropdown_list_model->get_all_court_type_list($court_type);

            foreach ($all_types_list as $all_type) {
                $dropDownOptions .= '<option value="' . sanitize($all_type['id']) . '">' . sanitize(strtoupper($all_type['court_name'])) . '</option>';
            }
        }
        echo $dropDownOptions;
    }

    function get_states_list()
    {
        $dropDownOptions = '<option value="">SELECT</option>';

        $all_states_list = $this->Dropdown_list_model->icmis_states();

        foreach ($all_states_list as $all_states) {
            $dropDownOptions .= '<option value="' . sanitize($all_states['id_no']) . '">' . sanitize(strtoupper($all_states['name'])) . '</option>';
        }

        echo $dropDownOptions;
    }
   public function get_casetype()
    {   $ddl_court=$_REQUEST['ddl_court'];
        $dropDownOptions = '<option value="">SELECT</option>';
        $data_list = $this->Dropdown_list_model->get_case_type_caveat($ddl_court);
        foreach ($data_list as $row) {
            $dropDownOptions .= '<option value="' . sanitize($row['casecode']) . '">' . sanitize($row['casename']) . '</option>';
        }
        echo $dropDownOptions;
    }
    public function get_bench()
    {
        $high_court_id = (sanitize($_REQUEST['high_court_id']));
        $court_type = $_REQUEST['court_type'];
        if (!isset($_REQUEST['bench_id'])){$_REQUEST['bench_id']='';}else{ $_REQUEST['bench_id']; }
        $bench_id=$_REQUEST['bench_id'];
        $params = array();
        $params['court_type'] = $court_type;
        $params['cmis_state_id'] =$high_court_id;
        $params['bench_id'] =$bench_id;
        $selected_bench = "";
        $dropDownOptions = '<option value="">Select High Court Bench</option>';
        // if (!empty($high_court_id) && !empty($court_type)) {
        //     $hc_benches = $this->Dropdown_list_model->getHighCourtData($params);
        //     foreach ($hc_benches as $bench) {
        //         if (!empty($_POST['bench_id'])) {
        //             if ($_POST['bench_id'] == $bench['id']) {
        //                 $selected_bench = "selected";
        //             } else {
        //                 $selected_bench = "";
        //             }
        //         }
        //         $dropDownOptions .= '<option value="' . sanitize($bench['id']) . '" ' . $selected_bench . '>' . sanitize(strtoupper($bench['agency_name'])) . '</option>';
        //     }
        // }
        if (!empty($high_court_id) && !empty($court_type)) {
            $hc_benches = $this->Dropdown_list_model->get_ref_agency_code($high_court_id, $court_type);            
            foreach ($hc_benches as $bench) {
                if (!empty($_POST['bench_id'])) {
                    if ($_POST['bench_id'] == $bench['id']) {
                        $selected_bench = "selected";
                    } else {
                        $selected_bench = "";
                    }
                }
                $dropDownOptions .= '<option value="' . sanitize($bench['id']) . '" ' . $selected_bench . '>' . sanitize(strtoupper($bench['agency_name'])) . '</option>';
            }
        }
        echo $dropDownOptions;
        exit();

    }

    function get_hc_bench_list_all_case_type_all_judges()
    {
        $high_court_id = (sanitize($_POST['high_court_id']));
        $court_type = $_POST['court_type'];
        $selected_bench = "";
        $dropDownOptions = '<option value="">Select High Court Bench</option>';
        if (!empty($high_court_id) && !empty($court_type)) {
            $hc_benches = $this->Dropdown_list_model->get_ref_agency_code($high_court_id, $court_type);
            foreach ($hc_benches as $bench) {
                if (!empty($_POST['bench_id'])) {
                    if ($_POST['bench_id'] == $bench['id']) {
                        $selected_bench = "selected";
                    } else {
                        $selected_bench = "";
                    }
                }
                $dropDownOptions .= '<option value="' . sanitize($bench['id']) . '" ' . $selected_bench . '>' . sanitize(strtoupper($bench['agency_name'])) . '</option>';
            }
        }

        $state_agency = $high_court_id;
        $selected_case_type = "";
        $dropDownOptions1 = '<option value="">SELECT</option>';
        if (!empty($state_agency) && !empty($court_type)) {
            $caseTypes = $this->Dropdown_list_model->get_case_type_court($state_agency, $court_type);

            foreach ($caseTypes as $case_type) {
                if ($court_type == 4) {
                    if (!empty($_POST['case_type'])) {
                        if ($_POST['case_type'] == $case_type['casecode']) {
                            $selected_case_type = "selected";
                        } else {
                            $selected_case_type = "";
                        }
                    }
                    $dropDownOptions1 .= '<option value="' . sanitize($case_type['casecode']) . '">' . sanitize(strtoupper($case_type['casename'])) . '</option>';
                } else {
                    if (!empty($_POST['case_type'])) {
                        if ($_POST['case_type'] == $case_type['lccasecode']) {
                            $selected_case_type = "selected";
                        } else {
                            $selected_case_type = "";
                        }
                    }
                    $dropDownOptions1 .= '<option value="' . sanitize($case_type['lccasecode']) . '">' . sanitize(strtoupper($case_type['type_sname'])) . '</option>';
                }
            }
        }

        $state_agency = $high_court_id;
        $dropDownOptions2 = '<option value="">Select Judge</option>';
        if (!empty($state_agency) && !empty($court_type)) {
            $all_judges = $this->Dropdown_list_model->get_all_judges($state_agency, $court_type);

            foreach ($all_judges as $judge) {
                if ($court_type == 4) {
                    $dropDownOptions2 .= '<option value="' . sanitize($judge['jcode']) . '">' . sanitize(strtoupper($judge['first_name'] . " " . $judge['sur_name'])) . '</option>';
                } else {
                    $dropDownOptions2 .= '<option value="' . sanitize($judge['id']) . '">' . sanitize(strtoupper($judge['first_name'] . " " . $judge['sur_name'])) . '</option>';
                }
            }
        }


        echo $dropDownOptions.'@@@'.$dropDownOptions1.'@@@'.$dropDownOptions2;

    }
    public function get_lc_hc_casetype()
    {

        $ddl_court=$_REQUEST['ddl_court'];
        $ddl_st_agncy=$_REQUEST['ddl_st_agncy'];
        $dropDownOptions = '<option value="">SELECT</option>';
        $data_list = $this->Dropdown_list_model->get_lc_hc_casetype($ddl_st_agncy,$ddl_court,null,'Y');
        foreach ($data_list as $row) {
            $dropDownOptions .= '<option value="' . $row['lccasecode'] . '" title="' . $row['lccasename'] . '">' . $row['type_sname'] . '</option>';
        }
echo $dropDownOptions;
}


    public function get_da_name(){
        $q = strtolower($_GET["term"]);
        $ucode = $_SESSION['login']['usercode'];

        if($ucode==638){
            $condition1="section=32";
        }else{
            $condition1 = "section=(select section from master.users where usercode in ($ucode))";
        }    
        $sql_section="select section from master.users where usercode in ($ucode)";
        $sql1 = "select  usercode,name,empid,section_name,id from master.users a left join master.usersection b ON section=b.id   where usertype in (17,50,51) AND (name ILIKE '%$q%' OR empid::TEXT ilike '%$q%' ) and ".$condition1." order by section_name";
        $rs1 = $this->db->query($sql1);
        $result = $rs1->getResultArray();
        $json=array();
        if(!empty($result)){
            foreach ($result as $row) {
                $json[]=array('value'=>$row['usercode'],'label'=>$row['name'].' - ['.$row['empid'].']'.' - ['.$row['section_name'].']');
            }
        }

        echo json_encode($json);

    }
    
}
