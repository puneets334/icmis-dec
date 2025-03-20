<?php
namespace App\Controllers\Filing\Master;
use App\Controllers\BaseController;
use App\Models\Common\Dropdown_list_model;
use App\Models\Entities\Model_judge;
use App\Models\Entities\Model_org_lower_court_judges;
use App\Models\Entities\Model_state;

class Lower_court_judge extends BaseController
{
    public $Dropdown_list_model;
    public $Model_judge;
    public $Model_org_lower_court_judges;
    function __construct()
    {
        $this->Dropdown_list_model= new Dropdown_list_model();
        $this->Model_judge= new Model_judge();
        $this->Model_org_lower_court_judges= new Model_org_lower_court_judges();
    }
    public function index(){
        $data['param']='';
        $Model_state = new Model_state();
        $db = \Config\Database::connect();
        if ($this->request->getMethod() === 'post') {
            $sc_judge_code = $this->request->getPost('sc_judge_code');
            $state_id = $this->request->getPost('state_id');
            $title = $this->request->getPost('title');
            $judge_first_name = trim($this->request->getPost('judge_first_name'));
            $judge_last_name = trim($this->request->getPost('judge_last_name'));
            $data['param'] = ['sc_judge_code' => $sc_judge_code, 'state_id' => $state_id, 'title' => $title, 'judge_first_name' => $judge_first_name, 'judge_last_name' => $judge_last_name];
        }
        if ($this->request->getMethod() === 'post' && $this->validate([
                'sc_judge_code' => 'required',
                'state_id' => 'required',
                'title' => 'required',
                'judge_first_name' => 'required',
                'judge_last_name' => 'required',
            ])) {
             $fname_lower=strtolower($judge_first_name);
             $lname_upper=strtolower($judge_last_name);
            $is_check_response = $this->Model_org_lower_court_judges->select("*")->where(['title' => $title, 'trim(LOWER(first_name))' => $fname_lower, 'trim(LOWER(sur_name))' => $lname_upper, 'cmis_state_id' => $state_id, 'is_deleted' => 'f'])->get()->getResultArray();

            if (empty($is_check_response)) {

                $data_array = [
                'title' => $title,
                'first_name' => $judge_first_name,
                'sur_name' => $judge_last_name,
                'cmis_state_id' => $state_id,
                'supreme_court_jud_id' => $sc_judge_code,
                'updated_on' => date("Y-m-d H:i:s"),
                'is_deleted' =>'f',
                'ent_ip_address' => getClientIP(),

                'create_modify' => date("Y-m-d H:i:s"),
                'updated_by' => $_SESSION['login']['usercode'],
                'updated_by_ip' => getClientIP(),
            ];
                //echo '<pre>';print_r($data_array);exit();
            $db->transStart();
            // insert code bellow
            $is_response = insert('master.org_lower_court_judges',$data_array);
            if ($is_response) {
                session()->setFlashdata("message_success", 'Your request has been successfully saved.');
            } else {
                session()->setFlashdata("message_error", "Your request is not saved please try again!");
            }
            $db->transComplete();

            } else {
                session()->setFlashdata("message_error", "Record Already Exist");
            }
        }
        $data['details'] =[];
        $data['details_update']=[];
        $data['data_list'] = $this->Model_org_lower_court_judges->select("org_lower_court_judges.*,s.name as state_name")->join("master.state s", "org_lower_court_judges.cmis_state_id=s.id_no")->where(['is_deleted' => 'f'])->orderBy('id','desc')->get(100)->getResultArray();
        $data['judge_list'] = $this->Model_judge->select("jcode,first_name,sur_name")->where(['display' => 'Y','is_retired' => 'N'])->get()->getResultArray();
        // pr( $data['judge_list']);
        $data['state_list'] = $this->Dropdown_list_model->get_address_state_list();
        //echo '<pre>';print_r($data['data_list']);exit();
        return view('Filing/Master/lower_court_judge_view', $data);
    }
public function get_judges(){
    $jcode = trim(sanitize($_REQUEST['jcode']));
    if (!empty($jcode) && $jcode !=null){
    $response='';
    $judge=$this->Model_judge->select("jcode,first_name,sur_name")->where(['display' => 'Y','is_retired' => 'N','jcode'=> $jcode])->get()->getRowArray();
    if (!empty($judge)) {
       $response=trim($judge['first_name']).'^'.trim($judge['sur_name']);
    }
    }else{
        $response=''.'^'.'';
    }
    echo $response;
    exit();
}
}
?>