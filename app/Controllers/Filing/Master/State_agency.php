<?php
namespace App\Controllers\Filing\Master;
use App\Controllers\BaseController;
use App\Models\Common\Dropdown_list_model;
use App\Models\Entities\Model_ref_agency_code;

class State_agency extends BaseController
{
    public $Dropdown_list_model;
    public $Model_ref_agency_code;
    function __construct()
    {
        $this->Dropdown_list_model= new Dropdown_list_model();
        $this->Model_ref_agency_code= new Model_ref_agency_code();
    }
    public function index(){
        $data['param']='';
        $db = \Config\Database::connect();
        if ($this->request->getMethod() === 'post' && $this->validate([
                'state_id' => 'required',
                'agency_name' => 'required',
                'agency_type' => 'required',
            ])) {

             $state_id = trim(sanitize($this->request->getPost('state_id')));
             $agency_name = trim(sanitize($this->request->getPost('agency_name')));
             $agency_type = trim(sanitize($this->request->getPost('agency_type')));
            $data['param']=['state_id'=>$state_id,'agency_type'=>$agency_type,'agency_name'=>$agency_name];
            $name_lower=strtolower($agency_name); $name_upper=strtoupper($name_lower);
            $state = $this->Model_ref_agency_code->select("*")->where(['trim(LOWER(agency_name))' =>$name_lower,'cmis_state_id'=>$state_id, 'is_deleted' => 'f'])->get()->getResultArray();

            if (empty($state)) {
                $data_array = [
                'agency_name' => $agency_name,
                'adm_updated_by' => $_SESSION['login']['usercode'],
                'updated_on' => date("Y-m-d H:i:s"),
                'agency_or_court' => $agency_type,
                'is_deleted' => 'f',
                'cmis_state_id' => $state_id,
                'ent_ip_address' => getClientIP(),

                'state_id' => 0,
                'short_agency_name' =>' ',
                'head_post' =>' ',
                'address' =>' ',
                'ref_city_id' =>0,

                'create_modify' => date("Y-m-d H:i:s"),
                'updated_by' => $_SESSION['login']['usercode'],
                'updated_by_ip' => getClientIP(),
            ];
            //echo '<pre>';print_r($data_array);exit();
            $db->transStart();
            // insert code bellow
            $is_response = insert('master.ref_agency_code',$data_array);
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
        $data['data_list'] = $this->Model_ref_agency_code->select("ref_agency_code.*,to_char(ref_agency_code.updated_on, 'DD-MM-YYYY') as updated_on, s.name as state_name")->join("master.state s", "ref_agency_code.cmis_state_id=s.id_no")->where(['is_deleted' => 'f'])->orderBy('id','desc')->get(100)->getResultArray();
        $data['state_list'] = $this->Dropdown_list_model->get_address_state_list();
        //echo '<pre>';print_r($data['data_list']);exit();
        return view('Filing/Master/state_agency_view', $data);
    }

}
?>