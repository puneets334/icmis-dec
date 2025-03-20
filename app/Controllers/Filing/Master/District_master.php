<?php
namespace App\Controllers\Filing\Master;
use App\Controllers\BaseController;
use App\Models\Common\Dropdown_list_model;
use App\Models\Entities\Model_state;

class District_master extends BaseController
{
    public $Dropdown_list_model;
    function __construct()
    {
        $this->Dropdown_list_model= new Dropdown_list_model();
    }
    public function index(){
        // pr($_SESSION['login']);
        $data['param']='';
        $Model_state = new Model_state();
        $db = \Config\Database::connect();
        if ($this->request->getMethod() === 'post' && $this->validate([
                'state_id' => 'required',
                'district_name' => 'required',
                //'district_name' => 'required|is_unique[state.name]',
            ])) {
             $state_id = $this->request->getPost('state_id');
             $district_name = trim($this->request->getPost('district_name'));
            $data['param']=['state_id'=>$state_id,'district_name'=>$district_name];
            $name_lower=strtolower($district_name); $name_upper=strtoupper($name_lower);
            //$name_ucwords=ucwords($name_lower); $name_ucfirst=ucwords($name_lower); $name_lcfirst=lcfirst($name_lower);
            $state = $Model_state->select("trim(LOWER(name))")->where(['district_code !=' => 0, 'sub_dist_code' => 0, 'village_code' => 0, 'state_code <' => 100, 'trim(LOWER(name))' => $name_lower])->get()->getResultArray();

            if (empty($state)) {
                $district_code=1;
                $is_state = $Model_state->select("max(district_code)+1 as district_code")->where(['state_code' => $state_id])->get()->getRowArray();
               if (!empty($is_state) && $is_state['district_code'] !=null) {
                $district_code=$is_state['district_code'];
                }
                $data_array = [
                'state_code' => $this->request->getPost('state_id'),
                'district_code' => $district_code,
                'sub_dist_code' => 0,
                'village_code' => 0,
                'name' => $name_upper,
                'display' => 'Y',
                'ent_user' => $_SESSION['login']['usercode'],
                'ent_time' => date('Y-m-d H:i:s'),
                'ent_ip_address' => date('Y-m-d H:i:s'),

                'cltor_emil' => ' ',
                'region' => ' ',
                'plc_grade' => 0,
                'sci_state_id' =>0,
                'ref_code_id' =>0,
                'pincode' => ' ',

                'create_modify' => date("Y-m-d H:i:s"),
                'updated_by' =>  $_SESSION['login']['usercode'],
                'updated_by_ip' => getClientIP(),
            ];

            $db->transStart();
            // insert code bellow
            //$is_response = $Model_state->insert($data_array);
            $is_response = insert('master.state',$data_array);
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
        $data['state_list'] = $this->Dropdown_list_model->get_address_state_list(); //echo '<pre>';print_r($data);exit();
        $data['data_list'] = $this->db->table("master.state st1")->DISTINCT()->select('st2.state_code,st2.id_no,st2.name')
            ->JOIN('master.state st2', 'st1.state_code = st2.state_code')
        ->select("st2.id_no,st2.name")
        ->WHERE('st2.district_code !=0')
        ->WHERE('st2.sub_dist_code = 0')
        ->WHERE('st2.village_code = 0')
        ->WHERE('st2.display', 'Y')
        ->WHERE('st1.display', 'Y')
        ->WHERE('st2.display', 'Y')
         ->orderBy('st2.id_no', 'desc')
         ->limit(100)->get()->getResultArray();

        return view('Filing/Master/district_master', $data);
    }

}
?>