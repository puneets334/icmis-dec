<?php
namespace App\Controllers\Filing\Master;
use App\Controllers\BaseController;
use App\Models\Common\Dropdown_list_model;
use App\Models\Entities\Model_ec_postal_dispatch_connected_letters_history;
use App\Models\Entities\Model_police;
use App\Models\Entities\Model_state;
use CodeIgniter\Model;

class Police_station extends BaseController
{
    public $Dropdown_list_model;
    public $Model_state;
    public $Model_police;
    public $Model_ec_postal_dispatch_connected_letters_history;
    function __construct()
    {
        $this->Dropdown_list_model= new Dropdown_list_model();
        $this->Model_state= new Model_state();
        $this->Model_police= new Model_police();

        $this->Model_ec_postal_dispatch_connected_letters_history= new Model_ec_postal_dispatch_connected_letters_history();
    }

    public function index(){
        $data['param']='';
        $db = \Config\Database::connect();
        if ($this->request->getMethod() === 'post' && $this->validate([
                'state_id' => 'required',
                'district' => 'required',
                'police_station_name' => 'required',
            ])) {
             $state_id = $this->request->getPost('state_id');
             $district = $this->request->getPost('district');
             $police_station_name = trim($this->request->getPost('police_station_name'));
            $data['param']=['state_id'=>$state_id,'district'=>$district,'police_station_name'=>$police_station_name];
            $name_lower=strtolower($police_station_name); $name_upper=strtoupper($police_station_name);
            $Model_police= new Model_police();
            $state_id_no=$this->Model_state->select('id_no')->where('district_code', 0)->where('sub_dist_code', 0)->where('village_code', 0)->where('state_code', $state_id)
                ->get()->getRowArray();
            if (!empty($state_id_no)){
                $id_no=$state_id_no['id_no'];

            $police = $Model_police->select("*")->where(['cmis_state_id' => $id_no, 'cmis_district_id' => $district, 'display' => 'Y', 'trim(LOWER(policestndesc))' => $name_lower])->get()->getResultArray();
             if (empty($police)){
                $newCode=1;
                //$query= $this->Model_police->selectMax('policestncd')
                $query= $this->Model_police->select('max(policestncd)+1 as policestncd')
                    ->where('cmis_state_id=(SELECT id_no FROM master.state WHERE district_code = 0 AND sub_dist_code = 0 AND village_code = 0 AND state_code = ' . $state_id . ')')
                    ->where('cmis_district_id', $district)->get();
                $is_policestncd = $query->getRow();
                if (!empty($is_policestncd)) {
                 $newCode = $is_policestncd->policestncd + 1;;
                }

                 $data_array = [
                     'policestncd' => $newCode,
                     'policestndesc' => $police_station_name,
                     'display' => 'Y',
                     'cmis_state_id' => $id_no,
                     'cmis_district_id' => $district,
                     'ent_user' =>$_SESSION['login']['usercode'],
                     'ent_time' => date('Y-m-d H:i:s'),
                     'ent_ip_address' => getClientIP(),

                     'create_modify' => date("Y-m-d H:i:s"),
                     'updated_by' => $_SESSION['login']['usercode'],
                     'updated_by_ip' => getClientIP(),
                 ];
                //echo '<pre>';print_r($data_array);exit();


            $db->transStart();
            // insert code bellow
                $is_response= insert('master.police',$data_array);
            if ($is_response) {
                session()->setFlashdata("message_success", 'Your request has been successfully saved.');
            } else {
                session()->setFlashdata("message_error", "Your request is not saved please try again!");
            }
            $db->transComplete();
             } else {
                 session()->setFlashdata("message_error", "Police Station Exist");
             }
        } else {
            session()->setFlashdata("message_error", "State is not exist");
        }

        }
        $data['details'] =[];
        $data['details_update']=[];
        // $data['state_list'] = $this->Model_state->select("state_code, name",false)
        // ->WHERE('district_code =0 AND sub_dist_code =0 AND village_code =0',NULL,false)
        // ->orderBy('name', 'ASC')->get()->getResultArray();
        $data['state_list'] = $this->Dropdown_list_model->get_address_state_list(); 
    
        $data['data_list'] = $this->Model_police->select("TO_CHAR(police.ent_time, 'YYYY-MM-DD') as ent_time,police.policestndesc,s.name state,d.name district")
            ->join("master.state s", "police.cmis_state_id=id_no")
            ->join("master.state d", "police.cmis_district_id=d.id_no")
            ->where(['d.display' => 'Y', 's.display' => 'Y','police.ent_time is not'=>null])->orderBy('police.ent_time','desc')->get(1000)->getResultArray();
        return view('Filing/Master/police_station_view', $data);
    }

    public function getStates()
    {
        $dropDownOptions = '<option value="">SELECT</option>';
            $data_list=  $this->Model_state->select("state_code, name",false)
                ->WHERE('district_code =0 AND sub_dist_code =0 AND village_code =0',NULL,false)
                ->orderBy('name', 'ASC')->get()->getResultArray();
            foreach ($data_list as $row) {
                $selected = "";
                if (!empty($_REQUEST['state_id'])) {
                    if ($_REQUEST['state_id'] == $row['state_code']) {
                        $selected = "selected";
                    }
                }
                $dropDownOptions .= '<option '.$selected.' value="' . sanitize($row['state_code']) . '">' . sanitize($row['name']) . '</option>';
            }

        echo $dropDownOptions;
    }
   public function getAgency()
    {
        $state_id=$_REQUEST['state_id'];
        $dropDownOptions = '<option value="">SELECT</option>';
        if (!empty($state_id) && $state_id !=null){
            $data_list= $this->Model_state->select("*",false)
                ->WHERE(['state_code'=>$state_id,'sub_dist_code'=>0,'village_code'=>0])
                ->orderBy('name', 'ASC')->get()->getResultArray();
            foreach ($data_list as $row) {
                $selected = "";
                if (!empty($_REQUEST['district_id'])) {
                    if ($_REQUEST['district_id'] == $row['id_no']) {
                        $selected = "selected";
                    }
                }
                $dropDownOptions .= '<option  '.$selected.' value="' . sanitize($row['id_no']) . '">' . sanitize($row['name']) . '</option>';
            }
        }

        echo $dropDownOptions;
    }
}
?>