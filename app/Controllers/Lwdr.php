<?php

namespace App\Controllers;
use App\Models\LwdrModel;

class Lwdr extends BaseController
{
    protected $session;
    protected $LwdrModel;


    function __construct()
    {   
        $this->db = \Config\Database::connect();
        $this->LwdrModel = new LwdrModel();
    }

    public function sectionwise_unverified_matters()
    {
        $data['section_name'] = $this->LwdrModel->sectionwise_name();
        return view('lwdr/sectionwise_unverified_matters', $data);
    }

    
    public function sectionwise_unverified_matters_data()
    {
        $section_name = $this->request->getVar('section');
        $data['details'] = $this->LwdrModel->get_sectionwise_matters($section_name);
        $data['num_row'] = count($data['details']);
        $data['section_name'] = $section_name;
        $data['title'] = 'List of unverified matters of Section ' . $section_name . ' as on ' . date("d-m-Y h:i:s A");
        $data['filename'] = 'List of unverified matters of Section ' . $section_name . ' as on ' . date("d-m-Y h:i:s A");
        return view('lwdr/sectionwise_unverified_matters_data', $data);
    }


    public function diarized_but_not_listed()
    {
        return view('lwdr/diarized_but_not_listed');
    }

    public function diarized_but_not_listed_process_data()
    {
        $data['summary_details'] = $this->LwdrModel->diarized_summary();
        return view('lwdr/diarized_but_not_listed_process_data', $data);
    }

    public function rev_cur_con_cat_undefined()
    {
        return view('lwdr/rev_cur_con_cat_undefined');
    }

    public function rev_cur_con_process()
    {
        $data['summary_details'] = $this->LwdrModel->rev_curprocess();
        return view('lwdr/rev_cur_con_process', $data);
    }

    public function Sectionwise_report()
    {
        $data['case_result']='';
        $data['param']=[];
        $data['app_name']='Section wise Report';
        $data['Sections'] = $this->LwdrModel->sectionwise_name();
        $data['MCategories'] = $this->LwdrModel->getMainSubjectCategory();
        return view('lwdr/sectionwise_reports_new',$data);
    }

    public function Sectionwise_report_data()
    {
            $sec_detail = explode('^', $this->request->getPost('section'));
            $section = isset($sec_detail[0]) ? $sec_detail[0] : '';
            $sec_name = isset($sec_detail[1]) ? $sec_detail[1] : '';
            $category = $this->request->getPost('categoryCode');
            $mcat = $this->request->getPost('McategoryCode') ?: '';
            $reportType = $this->request->getPost('reportType');
            $listCourtType = $this->request->getPost('listCourtType') ?: '';
            $dateType = $this->request->getPost('dateType') ?: '';
            $frm_date = $this->request->getPost('from_date') != '' ? date('Y-m-d', strtotime($this->request->getPost('from_date'))) :'';
            $to_date =  $this->request->getPost('to_date') != '' ? date('Y-m-d', strtotime($this->request->getPost('to_date'))) : '';
           
            $result_array = $this->LwdrModel->getSection_Pending_Reports($category, $section, $reportType ,$listCourtType, $dateType, $frm_date, $to_date,$mcat);
            if($result_array == 0) {
                $data['case_result'] = [];
            } else {
                $data['case_result'] = $result_array;
            }
            $data['param']=array($reportType, $listCourtType, $dateType, $frm_date, $to_date, $sec_name,$category);
            return view('lwdr/sectionwise_reports_data',$data);
        
    }

    public function get_Sub_Subject_Category(){
        ob_clean();
        header("Content-Type: application/json;charset=utf-8");
        $data_array = $this->LwdrModel->get_Sub_SubjectCategory($this->request->getPost('Mcat'));
        echo json_encode($data_array);
        ob_end_flush();
    }

    public function cases_notbefore_bench_90days()
    {
        $data['case_result'] = '';
        $data['app_name'] = 'Cases not listed before any bench greater than 90 days Pendency Report';
        $data['section_name'] = $this->LwdrModel->sectionwise_name();
        return view('lwdr/cases_nb_bench_gr_90days', $data);
    }

    public function get_DA_sectionwise(){
        ob_clean();
        header("Content-Type: application/json;charset=utf-8");
        $data_array = $this->LwdrModel->get_DA_sectionwise($this->request->getVar('secId'));
        echo json_encode($data_array);
        ob_end_flush();
    }

    public function cases_notbefore_bench_90days_data()
    {
        if ($this->request->getMethod() === 'post') {
            $section = $this->request->getPost('section');
            $da = $this->request->getPost('slc_da');
            $result_array = $this->LwdrModel->getcases_nb_gr_90days($section, $da);
            $data['case_result'] = $result_array;
            return view('lwdr/cases_nb_bench_gr_90days_data', $data);
        }
    }

}
