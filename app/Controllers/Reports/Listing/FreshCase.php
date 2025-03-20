<?php

namespace App\Controllers\Reports\Listing;
use App\Controllers\BaseController;
use App\Models\Reports\Listing\ReportModel;

class FreshCase extends BaseController
{

    public $ReportModel;

    function __construct()
    {
        $this->ReportModel= new ReportModel();
         
    }

    /**
     * Filter page for fresh cases never listed report
     *
     * @return void
     */
    public function fresh_cases_never_listed_report()
    {
        //$data['cases'] = $this->ReportModel->fresh_cases_never_listed_report();
        return  view('Reports/listing/fresh_cases_never_listed_report');
    }

    /**
     * To get fresh cases never listed report
     *
     * @return void
     */
    public function fresh_cases_never_listed_report_action()
    {
        $from_date = $this->request->getPost('from_date');
        $to_date = $this->request->getPost('to_date');
        $from_date = !empty($from_date) ? $from_date : null;
        $to_date = !empty($from_date) ? $to_date : null;
        $search_rpt_cnt = $this->request->getPost('search_rpt_cnt');
        $display_rpt = $this->request->getPost('Display_rpt');
        $diary_numbers = $this->request->getPost('diary_numbers');
        if($search_rpt_cnt == "Rpt_search_cnt_diary") {
            $cases = $this->ReportModel->fresh_cases_never_listed_report_action($from_date, $to_date);
            if(!empty($cases)){
                echo $cases['diary_cnt'];
                $diary_nos = $cases['diary_nos'];
                
                echo "<input type='hidden' value='$diary_nos' name='diary_numbers' id='diary_numbers'/>";
            }else{
                echo "0"; //Not fresh cases pending..
            }
            
        }

        if($display_rpt == "Rpt_display_cases_data_list") {
            $data['getResults'] = $this->ReportModel->display_cases($diary_numbers);
        }
            
    }
    
    

}
