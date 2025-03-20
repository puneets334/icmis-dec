<?php

namespace App\Controllers\Judicial;
use App\Controllers\BaseController;
use App\Models\Common\Dropdown_list_model;
use App\Models\Judicial\PhysicalVerificationModel;
use App\Libraries\webservices\Efiling_webservices;
use App\Libraries\webservices\Highcourt_webservices;

class PhysicalVerification extends BaseController
{
    public $Dropdown_list_model;
    public $efiling_webservices;
    public $highcourt_webservices;
    public $PhysicalVerificationModel;

    function __construct(){   
        $this->Dropdown_list_model= new Dropdown_list_model();
        $this->PhysicalVerificationModel = new PhysicalVerificationModel();
    }



    public function index(){
        // $diary_no = $_SESSION['filing_details']['diary_no'];
        // $data['dno'] = $diary_no;
        // $diary_year = substr($diary_no, -4);
        // $data['dyr'] = $diary_year;

        $data['physical_details'] = $this->PhysicalVerificationModel->getreportDetails();
        // echo "<pre>";
        // print_r($data['physical_details']); die;

        return view('Judicial/PhysicalVerification_view', $data);


    }

    public function wrong_updated_get(){
        $data = $_POST;
        if(!empty($data)){
            $modal_details = $this->PhysicalVerificationModel->wrong_updated_get($data);
            echo json_encode($modal_details);
        }
    }

    public function get_sections_by_act(){
        $data = $_POST;
        if(!empty($data)){
            $modal_details = $this->PhysicalVerificationModel->get_sections_by_act($data);
            echo json_encode($modal_details);
        }
    }

    public function get_sub_category_by_main_catId(){
        $data = $_POST;
        if(!empty($data)){
            $modal_details = $this->PhysicalVerificationModel->get_sub_category_by_main_catId($data);
            echo json_encode($modal_details);
        }
    }

    public function physical_verification_data_updation(){
        $data = $_POST;
        //pr($data);
        if(!empty($data)){
            $modal_details = $this->PhysicalVerificationModel->physical_verification_data_updation($data);
            echo json_encode($modal_details);
        }
    }
    
    public function wrong_updated_get_response(){
        $data = $_POST;
        if(!empty($data)){
            $modal_details = $this->PhysicalVerificationModel->wrong_updated_get_response($data);
            echo json_encode($modal_details);
        }
    }



}