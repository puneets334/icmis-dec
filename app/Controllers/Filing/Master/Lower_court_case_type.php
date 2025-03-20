<?php
namespace App\Controllers\Filing\Master;
use App\Controllers\BaseController;
use App\Models\Common\Dropdown_list_model;
use App\Models\Entities\Model_lc_hc_casetype;
use App\Models\Entities\Model_state;
use CodeIgniter\Model;

class Lower_court_case_type extends BaseController
{
    public $Dropdown_list_model;
    public $Model_lc_hc_casetype;
    public $Model_state;
    function __construct()
    {
        $this->Dropdown_list_model= new Dropdown_list_model();
        $this->Model_lc_hc_casetype= new Model_lc_hc_casetype();
        $this->Model_state = new Model_state();
    }
    public function index(){
        $data['param']='';
        $Model_state = new Model_state();
        $Model_lc_hc_casetype= new Model_lc_hc_casetype();
        $db = \Config\Database::connect();
        if ($this->request->getMethod() === 'post') {
            $hclc = $this->request->getPost('hclc');
            $state_id = $this->request->getPost('state_id');
            $district = $this->request->getPost('district');
            $case_type_code = $this->request->getPost('case_type_code');
            $long_name = trim($this->request->getPost('long_name'));
            $short_name = trim($this->request->getPost('short_name'));
            $long_name_l=strtolower($long_name); $short_name_l=strtolower($short_name);
            $data['param'] = ['hclc' => $hclc, 'state_id' => $state_id, 'district' => $district, 'case_type_code' => $case_type_code, 'long_name' => $long_name, 'short_name' => $short_name];
        }
        if ($this->request->getMethod() === 'post' && $this->validate([
                'hclc' => 'required',
                'state_id' => 'required',
                'long_name' => 'required',
                'short_name' => 'required',
            ])) {
            if (!empty($district)){
                $lc_hc_casetype = $Model_lc_hc_casetype->select("trim(LOWER(lccasename)),trim(LOWER(type_sname))")->where(['corttyp' => $hclc,'case_type' => $case_type_code, 'ref_agency_code_id' => $district, 'cmis_state_id' => $state_id, 'display' => 'Y', 'trim(LOWER(lccasename))' => $long_name_l,'trim(LOWER(type_sname))' => $short_name_l])->get()->getResultArray();
            }else{
                $lc_hc_casetype = $Model_lc_hc_casetype->select("trim(LOWER(lccasename)),trim(LOWER(type_sname))")->where(['corttyp' => $hclc,'case_type' => $case_type_code, 'cmis_state_id' => $state_id, 'display' => 'Y', 'trim(LOWER(lccasename))' => $long_name_l,'trim(LOWER(type_sname))' => $short_name_l])->get()->getResultArray();
            }


            if (empty($lc_hc_casetype)) {
                $data_array = [
                    'lccasename' => $long_name,
                    'corttyp' => $hclc,
                    'display' => 'Y',
                    'type_sname' => $short_name,
                    'case_type' => !empty($case_type_code) ? $case_type_code : 0,
                    'id' => 0,
                    'is_deleted' => 'f',
                    'ref_agency_state_id' => 0,
                    'ref_agency_code_id' => !empty($district) ? $district : 0,
                    'cmis_state_id' => $state_id,
                    'ent_user' => session()->get('login')['usercode'],
                    'ent_time' => date('Y-m-d H:i:s'),
                    'ent_ip_address' => getClientIP(),

                    'create_modify' => date("Y-m-d H:i:s"),
                    'updated_by' => session()->get('login')['usercode'],
                    'updated_by_ip' => getClientIP(),
                ];
                // echo '<pre>';print_r($data_array);exit();
                $db->transStart();
                // insert code bellow
                //$is_response = $Model_state->insert($data_array);
                $is_response = insert('master.lc_hc_casetype', $data_array);
                if ($is_response) {
                    session()->setFlashdata("message_success", 'Your request has been successfully saved.');
                } else {
                    session()->setFlashdata("message_error", "Your request is not saved please try again!");
                }

            $db->transComplete();
            } else {
                session()->setFlashdata("message_error", "Lower Court Case Type Already Exist");
            }

        }
        $data['details'] =[];
        $data['details_update']=[];

        $data['state_list'] = $this->Dropdown_list_model->get_address_state_list();
        $data['data_list']=$this->Model_lc_hc_casetype->select('lccasecode as id, cmis_state_id, corttyp, ref_agency_code_id, case_type, type_sname, lccasename,d.name as district_name')
        ->select('(select agency_state from master.ref_agency_state where ref_agency_state.cmis_state_id = lc_hc_casetype.cmis_state_id) as agency_state', false)
          ->join("master.state d", "lc_hc_casetype.ref_agency_code_id=d.id_no" ,'left')
        ->orderBy('id', 'desc')
        ->limit(100)->get()->getResultArray();

        return view('Filing/Master/lower_court_case_type_view', $data);
    }

}
?>