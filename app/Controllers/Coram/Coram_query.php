<?php

namespace App\Controllers\Coram;
use App\Controllers\BaseController;
use CodeIgniter\Controller;
use App\Models\Coram\CoramQueryModel;

class Coram_query extends BaseController
{
    public $model;
    public $diary_no;

    function __construct()
    {   

        $this->model = new CoramQueryModel();

        if(empty(session()->get('filing_details')['diary_no'])){
            $uri = current_url(true);
            $getUrl = $uri->getSegment(1).'-'.$uri->getSegment(2);
            header('Location:'.base_url('Filing/Diary/search?page_url='.base64_encode($getUrl)));exit();
        }else{
            $this->diary_no = session()->get('filing_details')['diary_no'];
        }
    }

    public function index($Url_Coram='')
    {
        $diary_no = $this->diary_no;

        $main_row = $this->model->get_main($diary_no);
        $advocate_ntl_judge = $this->model->get_advocate_ntl_judge($diary_no);
        $party_ntl_judge = $this->model->get_party_ntl_judge($diary_no);
        $coram_data = $this->model->get_coram_detail_data($diary_no);

        $coram_detail_data = [];

        foreach($coram_data as $coram_val):
            if($coram_val['notbef']=='C'){

                $get_coram_entry_date = $this->model->get_coram_entry_date($diary_no,$coram_val['coram']);
                
                if (!empty($get_coram_entry_date) && isset($get_coram_entry_date[0])) {
                    $coram_val['entry_date'] = ' by ' . $get_coram_entry_date[0]['name'] . ' on ' . date('d-m-Y H:i:s', strtotime($get_coram_entry_date[0]['ent_dt']));
                } else {
                    // Handle case where no entry date is returned
                    $coram_val['entry_date'] = 'No entry date found';
                }
                
                // $coram_val['entry_date'] = ' by '.$get_coram_entry_date[0]['name'].' on '.date('d-m-Y H:i:s',strtotime($get_coram_entry_date[0]['ent_dt'])) ;

            }else{
                $coram_val['entry_date'] = date('d-m-Y H:i:s',strtotime($coram_val['ent_dt']));
            }

            $coram_detail_data[] = $coram_val;         
        endforeach;

        $data['diary_no'] = $diary_no;
        $data['main_row'] = $main_row;
        $data['advocate_ntl_judge'] = $advocate_ntl_judge;
        $data['party_ntl_judge'] = $party_ntl_judge;
        $data['coram_detail_data'] = $coram_detail_data;
        $data['Url_Coram'] =  $Url_Coram;

        return view('Coram/coram_query',$data);
    }


}