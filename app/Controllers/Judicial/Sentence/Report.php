<?php

namespace App\Controllers\Judicial\Sentence;
use App\Controllers\BaseController;
use App\Models\Common\Dropdown_list_model;
use App\Models\Judicial\Model_IA_restore;
use App\Models\Judicial\Sentence\Model_sentence;

class Report extends BaseController
{
    public $Dropdown_list_model;
    public $Model_sentence;
    function __construct()
    {
        $this->Dropdown_list_model= new Dropdown_list_model();
        $this->Model_sentence = new Model_sentence();
    }

    public function index()
    {
        $data['current_page_url'] = base_url('Judicial/IA_restore');
        return view('Judicial/Sentence/report_view',$data);
    }

    public function get_search()
    {

    }

    public function get_content()
    {
        
        if ($this->request->getMethod() === 'get')
        {
            $dataget = $this->request->getGet();
            //print_r($dataget);exit;
            $case_status=$dataget['case_status'];
            $jail_bail=$dataget['jail_bail'];
            
            
            $this->validation->setRule('case_status', 'Please select case status', 'required');
            $this->validation->setRule('jail_bail', 'Please select jail type', 'required');

            $data = [
                'case_status'=>$case_status,
                'jail_bail'=>$jail_bail,
            ];
            
            if (!$this->validation->run($data)) {
                // handle validation errors
                echo '3@@@';
                //echo $this->validation->getError('search_type').$this->validation->getError('case_type');
                echo $this->validation->listErrors();exit();
            }
            
            $data['result']= $this->Model_sentence->get_report_details($data);
            //echo "<pre>";print_r($data['report']);exit();
            $get_view_result= view('Judicial/Sentence/report_get_content',$data);
            echo '1@@@'.$get_view_result;exit();
        }
        //echo "<pre>";print_r($data['dno_data']); die;

        // echo "3@@@Diary No. or Case No. doesn't exist .";exit();
    }

    public function sentence_details()
    {
        $data['result']= $this->Model_sentence->get_sentence_details();
        //echo "<pre>";print_r($data); exit();
        return view('Judicial/Sentence/get_content_sentence_details',$data);
    }
}