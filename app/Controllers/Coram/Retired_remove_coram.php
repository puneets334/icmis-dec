<?php

namespace App\Controllers\Coram;
use App\Controllers\BaseController;
use CodeIgniter\Controller;
use App\Models\Coram\RetiredRemoveCoramModel;

class Retired_remove_coram extends BaseController
{
    public $model;
    public $diary_no;

    function __construct()
    {   

        $this->model = new RetiredRemoveCoramModel();

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
        $data['judge_list'] = $judge_list;

        return view('Coram/retired_remove_coram',$data);
    }

    public function remove_coram()
    {

        if(!empty($this->request->getPost('judge'))){

            $judge = $this->request->getPost('judge');
            $crm_dtl = $this->request->getPost('crm_dtl');
            $conn_i_e = $this->request->getPost('conn_i_e');

            if($conn_i_e == 2){
                $exclude_conn = " (CAST(m.diary_no AS text) = m.conn_key OR m.conn_key ='0' OR m.conn_key = '' OR m.conn_key IS NULL) ";//only get main matters
            }else{
                $exclude_conn = '';
            }
         
            $get_data = $this->model->get_data($crm_dtl,$judge,$exclude_conn);

            if(!empty($get_data)){

                $html = '';
            
                $html .= '<table id="example1" class="table table-hover">
                            <thead>
                              <tr>
                                <th>SrNo.</th>
                                <th>Case No. @ Diary No.</th>
                                <th>Main Case Diary No.</th>
                                <th>Coram</th>';
                                if($crm_dtl == 1 || $crm_dtl == 2 || $crm_dtl == 3 || $crm_dtl == 4 || $crm_dtl == 5){
                    $html .= '      <th>Coram Updated Info</th>';
                                }
                    $html .= '  <th>Last order</th>
                              </tr>
                            </thead>
                            <tbody>';
                                $sno = 1;
                                foreach($get_data as $get_data_val):

                                    $sno1 = $sno % 2;
                      $html .= '  <tr>
                                    <td>'.$sno.'</td>
                                    <td>'.$get_data_val['reg_no_display']." @ ".$get_data_val['dno']."-".$get_data_val['dyr'].'</td>
                                    <td>'.substr_replace(!empty($get_data_val['conn_key'])?$get_data_val['conn_key']:'', '-', -4, 0).'</td>
                                    <td>'.$this->f_get_judge_names_inshort($get_data_val['coram']).'</td>';

                                    if($crm_dtl == 2 || $crm_dtl == 3){

                                $html .= '<td>'.$get_data_val['name'].' ['.$get_data_val['empid'].']'.' - '.date("d-m-Y H:i:s", strtotime($get_data_val['ent_dt'])).'</td>';

                                    }

                                    if($crm_dtl == 1 || $crm_dtl == 4 || $crm_dtl == 5){

                                        $coram_updated_array = $this->coram_updated_info($get_data_val['diary_no'],$get_data_val['coram']);
                                        if($coram_updated_array['empid'] > 0){
                                            $coram_updated_detail = $coram_updated_array['username'].' ['.$coram_updated_array['empid'].']'.' - '.date("d-m-Y H:i:s", strtotime($coram_updated_array['entry_date']));
                                        }

                                $html .= '<td>'.$coram_updated_detail.'</td>';
                                    }

                                $html .= '<td>'.$get_data_val['lastorder'].'</td>';    

                      $html .= '  </tr>';
                                $sno++; endforeach;
                $html .= '    </tbody>
                          </table>';
                
                echo $html;
            }
            else{
                echo 'No Recrods Found';
            }

                                  
        }
    }


    public function f_get_judge_names_inshort($chk_jud_id){

        $judge_names_inshort = $this->model->get_judge_names_inshort($chk_jud_id);

        $jname = "";

        foreach($judge_names_inshort as $judge_names_inshort_val):
            $jname .= $judge_names_inshort_val['abbreviation'].", ";
        endforeach;

        return rtrim(trim($jname),",");
    }

    public function coram_updated_info($diary_no,$coram){

        $res = $this->model->get_coram_update_info($diary_no,$coram);

        $coram_rep = "";
        foreach($res as $rw):
            if($coram == $rw['coram']){
                  $info_print = "<br> c ".$rw['coram']." u ".$rw['usercode']." e ".$rw['ent_dt'];
                  $usercode = $rw['usercode'];
                  $ent_date = $rw['ent_dt'];
            }
            else{
                break;
            }
        endforeach;

        if($usercode > 0){
            $rw1 = $this->model->get_user_info($usercode);
            $username = $rw1[0]['name'];
            $empid = $rw1[0]['empid'];
        }

        return array("empid"=>$empid,"username"=>$username,"entry_date"=>$ent_date);
    }

    public function do_remove_coram(){

        if(!empty($this->request->getPost('judge'))){

            $judge = $this->request->getPost('judge');
            $crm_dtl = $this->request->getPost('crm_dtl');

            $data = $this->model->remove_coram_given_by_cji($crm_dtl,$judge);
        }
    }


}