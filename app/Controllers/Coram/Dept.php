<?php

namespace App\Controllers\Coram;
use App\Controllers\BaseController;
use CodeIgniter\Controller;
use App\Models\Coram\DeptModel;

class Dept extends BaseController
{
    public $model;
    public $diary_no;

    function __construct()
    {   

        $this->model = new DeptModel();

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

        $get_ntl_judge_dept = $this->model->get_ntl_judge_dept();
        // pr($get_ntl_judge_dept);

        $judge = get_from_table_json('judge');

        $get_department = $this->model->get_department();

        foreach($judge as $key => $value):

            if($value['is_retired']=='N'){
                $judge_list[] = $value;
            }

        endforeach;

        $data['judge_list'] = $judge_list;
        $data['get_department'] = $get_department;
        $data['get_ntl_judge_dept'] = $get_ntl_judge_dept;

       return view('Coram/dept',$data);
    }

    public function insert_dept()
    {

        if(!empty($this->request->getPost('judge'))){

            $judge = $this->request->getPost('judge');
            $dept = $this->request->getPost('dept');

            $ins_arr = [
                        'dept_id'             =>      $dept,
                        'org_judge_id'        =>      $judge,
                        'userid'              =>      session()->get('login')['usercode'],
                        'ent_dt'              =>      'NOW()',
                        'del_user'            =>      '0',
                        ];

            $ins = insert('master.ntl_judge_dept',$ins_arr);
            if($ins){
                echo "Record Inserted Successfully";
            }
        }
    }

    public function ntl_judge_dept_delete_response(){
        
        if(!empty($this->request->getPost('dno'))){
            $str_explo = explode("_",$this->request->getPost('dno'));
            $org_judge_id = $str_explo[0];
            $org_dept_id = $str_explo[1];

            $deleted = delete('master.ntl_judge_dept',['dept_id'=>$org_dept_id,'org_judge_id'=>$org_judge_id]);
            echo $deleted;
        }
    }


}