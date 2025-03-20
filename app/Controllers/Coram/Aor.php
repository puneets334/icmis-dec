<?php

namespace App\Controllers\Coram;
use App\Controllers\BaseController;
use CodeIgniter\Controller;
use App\Models\Coram\AorModel;

class Aor extends BaseController
{
    public $model;
    public $diary_no;

    function __construct()
    {   

        $this->model = new AorModel();

        if(empty(session()->get('filing_details')['diary_no'])){
            $uri = current_url(true);
            $getUrl = $uri->getSegment(1).'-'.$uri->getSegment(2);
            header('Location:'.base_url('Filing/Diary/search?page_url='.base64_encode($getUrl)));exit();
        }else{
            $this->diary_no = session()->get('filing_details')['diary_no'];
        }
    }

    public function index()
    {
        $diary_no = $this->diary_no;
        $get_ntl_judge = $this->model->get_ntl_judge();
        // pr($get_ntl_judge);
        $judge = get_from_table_json('judge');
        $get_bar = $this->model->get_bar();
        foreach($judge as $key => $value):
            if($value['is_retired']=='N'){
                $judge_list[] = $value;
            }
        endforeach;

        $data['judge_list'] = $judge_list;
        $data['get_bar'] = $get_bar;
        $data['get_ntl_judge'] = $get_ntl_judge;
       return view('Coram/aor',$data);
    }

    public function insert_aor()
    {

        if(!empty($this->request->getPost('judge'))){

            $judge = $this->request->getPost('judge');
            $aor = $this->request->getPost('aor');

            $ins_arr = [
                        'org_advocate_id'     =>      $aor,
                        'org_judge_id'        =>      $judge,
                        'userid'              =>      session()->get('login')['usercode'],
                        'ent_dt'              =>      'NOW()',
                        'del_user'            =>      '0',
                        ];

            $ins = insert('master.ntl_judge',$ins_arr);

            if($ins){
                echo "Record Inserted Successfully";
            }
        }
    }

    public function ntl_judge_delete_response(){
        
        if(!empty($this->request->getPost('dno'))){
            $str_explo = explode("_",$this->request->getPost('dno'));
            $org_judge_id = $str_explo[0];
            $org_advocate_id = $str_explo[1];

            $deleted = delete('master.ntl_judge',['org_advocate_id'=>$org_advocate_id,'org_judge_id'=>$org_judge_id]);
            echo $deleted;
        }
    }


}