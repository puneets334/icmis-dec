<?php

namespace App\Controllers\Reports\Court;
use App\Controllers\BaseController;
use App\Models\Reports\Court\Model_high_court;
use App\Models\Reports\Court\ReportModel;
use App\Models\Common\Dropdown_list_model;

class Caveat extends BaseController
{
    public $Dropdown_list_model;

    public $ReportModel;
    public $Model_high_court;
    function __construct()
    {
        ini_set('memory_limit','51200M'); // This also needs to be increased in some cases. Can be changed to a higher value as per need)
        $this->Dropdown_list_model= new Dropdown_list_model();
        $this->ReportModel= new ReportModel();
        $this->Model_high_court= new Model_high_court();
    }

    public function index(){

        $data['state'] = get_from_table_json('state');
        $data['court_type_list'] = $this->Dropdown_list_model->get_court_type_list();
        $data['casetype']=$this->Dropdown_list_model->get_case_type('filing','nature_sci');
        return view('Reports/court/caveat_to_caveat_search',$data);
    }
    public function get_caveat_to_caveat(){

        if ($this->request->getMethod() === 'post') {

            $ddl_court=$this->request->getPost('ddl_court');
            $ddl_st_agncy=$this->request->getPost('ddl_st_agncy');
            $ddl_ref_caseyr=$this->request->getPost('ddl_ref_caseyr');


            $this->validation->setRule('ddl_court', 'Select court type', 'required');
            $this->validation->setRule('ddl_st_agncy', 'Select state', 'required');
            $this->validation->setRule('ddl_ref_caseyr', 'Caveat year', 'required');

            $validation = [
                'ddl_court'=>$ddl_court,
                'ddl_st_agncy'=>$ddl_st_agncy,
                'ddl_ref_caseyr'=>$ddl_ref_caseyr,
            ];

            if (!$this->validation->run($validation)) {
                // handle validation errors
                echo '3@@@';
                echo $this->validation->listErrors();exit();
            }
            $ddl_bench=$this->request->getPost('ddl_bench');
            $txt_ref_caseno=$this->request->getPost('txt_ref_caseno');
            $txt_order_date=$this->request->getPost('txt_order_date');
            $ddl_nature=$this->request->getPost('ddl_nature');
            $data = [
                'ddl_court'=>$ddl_court,
                'ddl_st_agncy'=>$ddl_st_agncy,
                'ddl_ref_caseyr'=>$ddl_ref_caseyr,
                'ddl_bench'=>$ddl_bench,
                'txt_ref_caseno'=>$txt_ref_caseno,
                'txt_order_date'=>$txt_order_date,
                'ddl_ref_case_type'=>$ddl_nature,
            ];
            $final_result= $this->Model_high_court->get_caveat_to_caveat($data);
            $data['reports'] = $final_result;
            $resul_view= view('Reports/court/get_content_caveat_to_caveat',$data);
            echo $resul_view;exit();
        }
    }

    /*start high court*/

    /*start lower court*/
    public function lower_court_search(){
        $data['country'] = get_from_table_json('country');
        $data['state'] = get_from_table_json('state');
        $data['ref_special_category_filing'] = get_from_table_json('ref_special_category_filing','Y','display');
        $data['court_type_list'] = $this->Dropdown_list_model->get_court_type_list();
        $data['usersection']=$this->Dropdown_list_model->get_usersection();
        $data['state_list'] = $this->Dropdown_list_model->get_address_state_list();
        $data['casetype']=$this->Dropdown_list_model->get_case_type('filing','nature_sci');
        $data['casetype_nature_sci']=$this->Dropdown_list_model->get_case_type('filing','nature_sci');
        return view('Reports/court/caveat_lower_court_search',$data);
    }
    public function get_caveat_lower_court_total_count(){

        if ($this->request->getMethod() === 'post') {

            $ddl_court=$this->request->getPost('ddl_court');
            $ddl_st_agncy=$this->request->getPost('ddl_st_agncy');
            $ddl_ref_caseyr=$this->request->getPost('ddl_ref_caseyr');


            $this->validation->setRule('ddl_court', 'Select court type', 'required');
            $this->validation->setRule('ddl_st_agncy', 'Select state', 'required');
            $this->validation->setRule('ddl_ref_caseyr', 'Caveat year', 'required');

            $validation = [
                'ddl_court'=>$ddl_court,
                'ddl_st_agncy'=>$ddl_st_agncy,
                'ddl_ref_caseyr'=>$ddl_ref_caseyr,
            ];

            if (!$this->validation->run($validation)) {
                // handle validation errors
                echo '3@@@';
                echo $this->validation->listErrors();exit();
            }
            $ddl_bench=$this->request->getPost('ddl_bench');
            $txt_ref_caseno=$this->request->getPost('txt_ref_caseno');
            $txt_order_date=$this->request->getPost('txt_order_date');
            $ddl_nature=$this->request->getPost('ddl_nature');
            $data = [
                'ddl_court'=>$ddl_court,
                'ddl_st_agncy'=>$ddl_st_agncy,
                'ddl_ref_caseyr'=>$ddl_ref_caseyr,
                'ddl_bench'=>$ddl_bench,
                'txt_ref_caseno'=>$txt_ref_caseno,
                'txt_order_date'=>$txt_order_date,
                'ddl_ref_case_type'=>$ddl_nature,
                'offset_left'=>500,
                'offset_right'=>0,
                'u_t'=>0,
            ];
            $data['inc_tot_pg'] = 0;
            $total_count= $this->Model_high_court->get_caveat_lower_court_total_count($data);

            if ($total_count > 0){
                $data['total_count'] = $total_count;
                $final_result= $this->Model_high_court->get_content_caveat_lower_court_details($data);
                if (!empty($final_result)){
                    $data['inc_tot_pg'] = count($final_result);
                }

                $data['reports'] = $final_result;
                $pagination_view= view('Common/Component/get_content_pagination',$data);
                $resul_view= view('Reports/court/get_content_caveat_lower_court',$data);
                echo '1@@@'.$total_count.'@@@'.$pagination_view.'@@@'.$resul_view;
            }else{
                echo '3@@@No Record Found!';
            }
            exit();
        }
    }
    public function get_content_caveat_lower_court_details(){
        if ($this->request->getMethod()) {

            $ddl_court=$_REQUEST['ddl_court'];
            $ddl_st_agncy=$_REQUEST['ddl_st_agncy'];
            $ddl_ref_caseyr=$_REQUEST['ddl_ref_caseyr'];

            $ddl_bench=$_REQUEST['ddl_bench'];
            $txt_ref_caseno=$_REQUEST['txt_ref_caseno'];
            $txt_order_date=$_REQUEST['txt_order_date'];
            $ddl_nature=$_REQUEST['ddl_nature'];

            $offset_left=$_REQUEST['inc_val'];
            $offset_right=$_REQUEST['hd_fst'];

            $action_type=$_REQUEST['action_type'];
            $total_count=$_REQUEST['total_count'];
            $inc_tot_pg=$_REQUEST['inc_tot_pg'];
            $inc_tot=$total_count;
            if ($action_type=='L') {

            }else if ($action_type=='R'){

            }else{
                $offset_left=500;
                $offset_right=0;
            }
            $data = [
                'ddl_court'=>$ddl_court,
                'ddl_st_agncy'=>$ddl_st_agncy,
                'ddl_ref_caseyr'=>$ddl_ref_caseyr,
                'ddl_bench'=>$ddl_bench,
                'txt_ref_caseno'=>$txt_ref_caseno,
                'txt_order_date'=>$txt_order_date,
                'ddl_ref_case_type'=>$ddl_nature,

                'offset_left'=>$offset_left,
                'offset_right'=>$offset_right,
                'total_count'=>$total_count,
                'u_t'=>1,
                'inc_tot_pg'=>$inc_tot_pg,
            ];
            $final_result= $this->Model_high_court->get_content_caveat_lower_court_details($data);


            $data['reports'] = $final_result;
            $resul_view= view('Reports/court/get_content_caveat_lower_court',$data);
            echo $resul_view;exit();

        }else{
            echo 'else';
        }
    }
    /*end lower court*/




}
