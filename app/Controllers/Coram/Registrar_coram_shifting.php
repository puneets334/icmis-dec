<?php

namespace App\Controllers\Coram;
use App\Controllers\BaseController;
use CodeIgniter\Controller;
use App\Models\Coram\RegistrarCoramShiftingModel;

class Registrar_coram_shifting extends BaseController
{
    public $model;
    public $diary_no;

    function __construct()
    {   

        $this->model = new RegistrarCoramShiftingModel();

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

        $judge_list = $this->model->get_judge();

        $get_department = [];

        $data['judge_list'] = $judge_list;
        $data['get_department'] = $get_department;

       return view('Coram/registrar_coram_shifting',$data);
    }

    public function reg_remove_coram()
    {

        if(!empty($this->request->getPost('judge'))){

            $judge = $this->request->getPost('judge');

            $sub_judge_list = $this->model->get_sub_judge();

            echo '<option value="">Select</option>';
            foreach($sub_judge_list as $sub_judge_list_val):
                echo '<option value="'.$sub_judge_list_val['jcode'].'">'.$sub_judge_list_val['jname'].' ('.$sub_judge_list_val['first_name'].' '.$sub_judge_list_val['sur_name'].')'.'</option>';
            endforeach;
        }
    }

    public function reg_do_remove_coram()
    {

        if(!empty($this->request->getPost('judge'))){

            //$cur_dt = date('Y-m-d');
            $usercode = session()->get('login')['usercode'];

            $judge = $this->request->getPost('judge');
            $judge_to = $this->request->getPost('judge_to');

            $get_data = $this->model->get_data($judge,$judge_to,$usercode);

            echo $get_data;
        }
    }


}