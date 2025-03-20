<?php

namespace CodeIgniter\Validation;

namespace App\Controllers\Caveat;

use App\Controllers\BaseController;
use App\Models\Entities\Model_Caveat;
use App\Models\Entities\Model_CaveatA;
use App\Models\Entities\Model_CaveatAdvocate;
use App\Models\Entities\Model_CaveatAdvocateA;
use App\Models\Entities\Model_CaveatDiaryMatching;
use App\Models\Entities\Model_main_a;
use App\Models\Model_main;
use App\Models\Common\Component\Model_case_status;

use CodeIgniter\Model;

class Notice extends BaseController
{
    public $Model_caveat;
    public $Model_caveat_a;
    public $Model_caveat_diary_matching;
    public $Model_CaveatAdvocate;
    public $Model_CaveatAdvocateA;
    public $Model_main;
    public $Model_main_a;
    public $Model_case_status;
    function __construct()
    {
        $this->Model_caveat= new Model_Caveat();
        $this->Model_caveat_a= new Model_CaveatA();
        $this->Model_caveat_diary_matching= new Model_CaveatDiaryMatching();
        $this->Model_CaveatAdvocate= new Model_CaveatAdvocate();
        $this->Model_CaveatAdvocateA= new Model_CaveatAdvocateA();
        $this->Model_main= new Model_main();
        $this->Model_main_a= new Model_main_a();
        $this->Model_case_status = new Model_case_status();
    }

    public function index()
    {
        return view('Caveat/notice_view');
    }

    public function get_notice(){
        $data['param']=array();
        if ($this->request->getMethod() === 'post'){
            if ($this->request->getMethod() === 'post' && $this->validate([
                    'caveat_number' => ['label' => 'Caveat Number', 'rules' => 'required|min_length[1]|max_length[8]'],
                    'caveat_year' => ['label' => 'Caveat Year', 'rules' => 'required|min_length[4]'],
                ])) {
                $caveat_number = $this->request->getPost('caveat_number');
                $caveat_year = $this->request->getPost('caveat_year');
                $hd_link = $this->request->getPost('hd_link');
                $caveat_no=$caveat_number.$caveat_year;
                $data['param']=['caveat_number'=>$caveat_number,'caveat_year'=>$caveat_year];
                $is_archive_table_flag = '';
                $is_main_table=$this->Model_caveat->select("pet_name,res_name,TO_CHAR(diary_no_rec_date, 'DD-MM-YYYY') as diary_no_rec_date, 
  (CURRENT_DATE -diary_no_rec_date::date) AS no_of_days,casetype_id,caveat_no,ref_agency_state_id")->where(['caveat_no'=>$caveat_no])->get()->getResultArray();
                if ($is_main_table){
                    $get_main_table=$this->Model_caveat->select('*')->where(['caveat_no'=>$caveat_no])->get()->getRowArray();
                    $this->session->set(array('caveat_details'=> $get_main_table));
                    //return redirect()->to('Caveat/Modify');exit();
                }else{
                    $is_archive_table_flag = '_a';
                    $is_main_table=$this->Model_caveat_a->select("pet_name,res_name,TO_CHAR(diary_no_rec_date, 'DD-MM-YYYY') as diary_no_rec_date, 
  (CURRENT_DATE -diary_no_rec_date::date) AS no_of_days,casetype_id,ref_agency_state_id")->where(['caveat_no'=>$caveat_no])->get()->getResultArray();
                    if ($is_main_table) {
                        $get_main_table = $this->Model_caveat_a->select('*')->where(['caveat_no' => $caveat_no])->get()->getRowArray();
                        $this->session->set(array('caveat_details' => $get_main_table));
                    }else{
                        unset($_SESSION['caveat_details']);
                    }
                }
            }
        }

        $caveat_details= session()->get('caveat_details');
        $caveat_advocate_details = array();$final_array=array(); $advocate_details['caveat_advocate_details'] = array(); $caveatSBCJ=array();$caveatSCC=array();
        if (!empty($caveat_details)){
            $caveat_number=substr($caveat_details['caveat_no'], 0, -4);$caveat_year=substr($caveat_details['caveat_no'],-4);
            $caveat_no=$caveat_number.$caveat_year;
            $this->Model_caveat_diary_matching->select("diary_no,notice_path,print_user_id,date(print_dt) print_dt")->where(['caveat_no'=>$caveat_no])->where('display','Y');
            if ( $hd_link != 0) {
                $this->Model_caveat_diary_matching->where(['diary_no'=>$hd_link]);
            }
            $caveat_diary_matching = $this->Model_caveat_diary_matching->get()->getResultArray();
            $data['caveat_diary_matching'] = $caveat_diary_matching;

        }
        $caveat_advocate_details = $this->get_caveat_advocate($caveat_no,$is_archive_table_flag);

        if(!empty($caveat_diary_matching)){
            foreach($caveat_diary_matching as $row1){
                $sub_details = array(); $casetype_array['casetype_details'] = array(); $linked_dt_array['linked_date_caveat']=array();$party_details_d['party_details_diary'] =$diary_party_details=$caveatLowerct['caveat_lowerct_data']=$main_table_data=$main_table_d['main_data']= $d_advocate_details=$diary_advocate_details=array();
                if(!empty($is_main_table)){
                    $casetype_id = $is_main_table[0]['casetype_id'];
                    $tentative_section = json_decode($this->Model_case_status->get_tentative_section($row1['diary_no'],''),true);
                    if (empty($tentative_section)) {
                        $tentative_section = json_decode($this->Model_case_status->get_tentative_section($row1['diary_no'],'_a'),true);
                    }
                    $sub_details = $this->get_sub_details($row1['diary_no']);
                    if (empty($sub_details)) {
                        $sub_details = $this->get_sub_details($row1['diary_no'], '_a', '_a');
                    }

                    if (!empty($sub_details)) {
                        if (!empty($sub_details['active_fil_no']) && $sub_details['active_fil_no'] != null && $sub_details['active_fil_no'] != '') {
                            $casecode = substr($sub_details['active_fil_no'], 0, 2);
                            $casetype_array['casetype_details'] = is_data_from_table('master.casetype', ['casecode' => $casecode, 'display' => 'Y'], 'casename', 'R');
                        }
                    }
                    $is_order_challenged=null;
                    if($casetype_id !='7'&& $casetype_id !='8' && $casetype_id !='5' && $casetype_id !='6')
                    {
                        $is_order_challenged='Y';
                    }

                    $main_table_data =  is_data_from_table('main', ['diary_no'=>$row1['diary_no']],  "pet_name,res_name,TO_CHAR(diary_no_rec_date, 'DD-MM-YYYY') as diary_no_rec_date",'R');
                    if(empty($main_table_data)){
                        $main_table_data =  is_data_from_table('main_a', ['diary_no'=>$row1['diary_no']], "pet_name,res_name,TO_CHAR(diary_no_rec_date, 'DD-MM-YYYY') as diary_no_rec_date",'R');
                    }

                    $diary_advocate_details = $this->get_diary_advocate($row1['diary_no'],'');
                    if(empty($diary_advocate_details)){
                        $diary_advocate_details = $this->get_diary_advocate($row1['diary_no'],'_a');
                    }
                    $diary_party_details = $this->get_party_details($row1['diary_no'],'');
                    if(empty($diary_party_details)){
                        $diary_party_details = $this->get_party_details($row1['diary_no'],'_a');
                    }

                    $caveatSBCJ_pending=$this->get_CaveatDiary_details($caveat_no,$row1['diary_no'],$is_order_challenged,'','');
                    $caveatSBCJ_disposed=$this->get_CaveatDiary_details($caveat_no,$row1['diary_no'],$is_order_challenged,'_a','_a');
                    $caveatSBCJ_disposed2=$this->get_CaveatDiary_details($caveat_no,$row1['diary_no'],$is_order_challenged,'','_a');
                    $caveatSBCJ_disposed_others=$this->get_CaveatDiary_details($caveat_no,$row1['diary_no'],$is_order_challenged,'_a','');
                    $caveatSBCJ=array_merge($caveatSBCJ_pending,$caveatSBCJ_disposed,$caveatSBCJ_disposed_others,$caveatSBCJ_disposed2);

                    $caveatSCC_pending=$this->get_caveator_cavetee_details($caveat_no,$row1['diary_no'],'','');
                    $caveatSCC_disposed=$this->get_caveator_cavetee_details($caveat_no,$row1['diary_no'],'_a','_a');
                    $caveatSCC_disposed2=$this->get_caveator_cavetee_details($caveat_no,$row1['diary_no'],'','_a');
                    $caveatSCC_disposed_others=$this->get_caveator_cavetee_details($caveat_no,$row1['diary_no'],'_a','');
                    $caveatSCC=array_merge($caveatSCC_pending,$caveatSCC_disposed,$caveatSCC_disposed_others,$caveatSCC_disposed2);

                    $linked_dt_array['linked_date_caveat'] = is_data_from_table('caveat_diary_matching', ['diary_no' => $row1['diary_no'],'caveat_no'=>$caveat_no ,'display' => 'Y'], "caveat_no,TO_CHAR(link_dt,'DD-MM-YYYY') as linked_date", 'R');

                    $sub_array['caveat_diary_matching'] = $row1;
                    $sub_array['sub_details'] = $sub_details;
                    $sub_array['tentative_section'] = $tentative_section;
                    $advocate_details['caveat_advocate_details'] =$caveat_advocate_details;

                    $d_advocate_details['diary_advocate_details'] =$diary_advocate_details;

                    $caveatLowerct['caveat_lowerct_data'] = $caveatSBCJ;
                    $main_table_d['main_data'] = $main_table_data;
                    $party_details_d['party_details_diary'] = $diary_party_details;
                    $caveatorCaveatee['caveator_caveatee_data'] = $caveatSCC;
                    $final_array[] = array_merge($sub_array, $casetype_array,$advocate_details,$caveatLowerct,$main_table_d,$d_advocate_details,$party_details_d,$caveatorCaveatee,$linked_dt_array);
                }

            }
        }

        $data['caveat_data'] = $final_array;
        $data['is_main_table']=$is_main_table;
        //  print_r($data);
        $resul_view = view('Caveat/get_notice_content',$data);
        echo $resul_view;exit();
    }

    public function get_sub_details($diary_no,$is_archival_table='')
    {
        $builder = $this->db->table("main$is_archival_table as m");
        $builder->distinct();
        $builder->select("active_fil_no,date_part('YEAR',active_fil_dt) as active_fil_dt,pet_name,res_name,pno,rno");

        $builder->where('m.diary_no', $diary_no);
        $query = $builder->get(1);
        $result = $query->getRowArray();
        return $result;
    }

    public function get_caveat_advocate($caveat_no,$is_archival_table='')
    {
        $builder = $this->db->table("caveat_advocate$is_archival_table as a");
        $builder->select("name,aor_code,title,caddress,ccity,pet_res,pet_res_no,mobile");
        $builder->join("master.bar as b","a.advocate_id=b.bar_id");
        $builder->where('caveat_no', $caveat_no);
        $builder->where('display', 'Y');
        $query = $builder->get();
        $result = $query->getResultArray();
        return $result;
    }

    public function get_diary_advocate($diary_no,$is_archival_table='')
    {
        $builder = $this->db->table("advocate$is_archival_table as a");
        $builder->select("name,aor_code,title,caddress,ccity,mobile,pet_res,pet_res_no");
        $builder->join("master.bar as b","a.advocate_id=b.bar_id");
        $builder->where('diary_no', $diary_no);
        $builder->where('display', 'Y');
        $builder->where('pet_res', 'P');
        $query = $builder->get();
        $result = $query->getResultArray();
        return $result;
    }

    public function get_CaveatDiary_details($caveat_no,$diary_no,$is_order_challenged=null,$is_archival_table='',$is_archival_table2='')
    {
        $builder = $this->db->table("lowerct$is_archival_table as a");
        $builder->distinct();
        $builder->select('b.lct_dec_dt, b.l_dist, b.l_state, b.lct_casetype, b.lct_caseno, b.lct_caseyear, b.caveat_no c_diary, b.ct_code, name');
        $builder->select("CASE 
        WHEN b.ct_code = 3 THEN (
                CASE WHEN b.l_state = 490506 THEN (
                    SELECT court_name name FROM master.state s
                        LEFT JOIN master.delhi_district_court d ON s.state_code = d.state_code
                        AND s.district_code = d.district_code WHERE s.id_no = a.l_dist AND display = 'Y'
                )ELSE(
                    SELECT name FROM master.state s WHERE s.id_no = a.l_dist AND display = 'Y'
                )
            END
        )
        ELSE (
            SELECT  agency_name FROM master.ref_agency_code r
        WHERE  r.cmis_state_id = b.l_state AND r.id = b.l_dist AND is_deleted = 'f'
        )
    END AS agency_name", false);

        $builder->select("CASE 
        WHEN b.ct_code = 4 THEN (
            SELECT skey
        FROM  master.casetype ct
        WHERE ct.display = 'Y'
                AND ct.casecode = b.lct_casetype     
        )
        ELSE (
            SELECT type_sname
        FROM
            master.lc_hc_casetype d
        WHERE
            d.lccasecode = b.lct_casetype
                AND d.display = 'Y'          
        )
    END AS type_sname", false);
        $builder->select("d.fil_no,fil_dt,e.short_description,f.court_name,d.c_status,d.pet_name,d.res_name,a.diary_no,to_char(diary_no_rec_date,'dd-mm-yyyy') as diary_no_rec_date");

        $builder->join("caveat_lowerct$is_archival_table2 b", "a.lct_dec_dt = b.lct_dec_dt and a.l_state = b.l_state and a.lct_caseyear = b.lct_caseyear and a.ct_code = b.ct_code and trim(leading '0' from a.lct_caseno) = trim(leading '0' from b.lct_caseno)");
        $builder->join('master.state c', "b.l_state = c.id_no AND c.display = 'Y'", 'left');
        $builder->join("caveat$is_archival_table2 d", 'd.caveat_no = b.caveat_no','left');
        $builder->join('master.m_from_court f', "f.id=a.ct_code AND f.display = 'Y'", 'left');
        $builder->join('master.casetype e', "e.casecode = b.lct_casetype AND e.display = 'Y'", 'left');
        $builder->where('a.diary_no', $diary_no);
        $builder->where('b.caveat_no', $caveat_no);

        $builder->where('b.lw_display', 'Y');
        $builder->where('a.lw_display', 'Y');
        if((!empty($is_order_challenged) && $is_order_challenged !=null) && $is_order_challenged =='Y'){
            $builder->where('a.is_order_challenged', 'Y');
            $builder->where('b.lct_dec_dt is not', null);
        }
        $query = $builder->get();
        /* $query = $this->db->getLastQuery();
         echo (string)"DSFDS". $query.'<br>';
         exit();*/
        $result = $query->getResultArray();
        return $result;
    }


    public function get_party_details($diary_no,$is_archival_table='')
    {
        $builder = $this->db->table("party$is_archival_table as a");
        $builder->select("partyname,concat(addr1,' ',addr2) address,contact,state,city");
        $builder->where('diary_no', $diary_no);
        $builder->where('pflag', 'P');
        $builder->where('pet_res', 'P');
        $builder->where('sr_no', '1');
        $query = $builder->get(1);
        $result = $query->getRowArray();
        return $result;
    }

    public function get_caveator_cavetee_details($caveat_no,$diary_no,$is_archival_table='',$is_archival_table2='')
    {
        $builder = $this->db->table("main$is_archival_table as p");
        $builder->distinct();

        $builder->select('m.caveat_no as c_diary,name,p.pet_name,p.res_name,p.diary_no');
        $builder->join("caveat$is_archival_table2 m", "m.ref_agency_state_id=p.ref_agency_state_id and trim(LOWER((m.pet_name))) LIKE   concat('%',trim(LOWER((p.res_name))),'%') and trim(LOWER((m.res_name))) LIKE concat('%',trim(LOWER((p.pet_name))),'%')");
        $builder->join('master.state c', "m.ref_agency_state_id = c.id_no AND c.display = 'Y'", 'left');
        $builder->where('p.diary_no', $diary_no);
        $builder->where('m.caveat_no', $caveat_no);
        $query = $builder->get();
        $result = $query->getResultArray();
        return $result;
    }

    public function save_caveat_report(){
        $chk_status=0;
        $c_date=date('Y-m-d');
        $hd_caveat_no = $this->request->getPost('hd_caveat_no');

        $year =  substr($hd_caveat_no,-4) ;
        $diary_no =  substr($hd_caveat_no,0,-4);
        $ucode = session()->get('login')['usercode'];

        $main_path = "caveat_records/";
        if (!file_exists($main_path)) {
            mkdir($main_path, 0755, true);
        }
        $yearPath = $main_path.'/'.$year;

        if (!file_exists($yearPath)) {
            mkdir($yearPath, 0755, true);
        }

        $diary_path = $yearPath.'/'.$diary_no;
        if (!file_exists($diary_path)) {
            mkdir($diary_path, 0755, true);
        }
        $sp_d_no = $this->request->getPost('sp_d_no');
        $sp_d_no=urldecode($sp_d_no);

        $ex_diary=  explode('~!@#$', $sp_d_no);
        for ($index = 0; $index < count($ex_diary); $index++) {
            $ex_in_exp=  explode('~~~', $ex_diary[$index]);
            $in_diary_no=$ex_in_exp[1];
            $in_year =  substr($in_diary_no,-4) ;
            $in_diary =  substr($in_diary_no,0,-4);

            $fil_nm = $in_diary . '_' . $in_year . '_' . $c_date . '.html';

            $file_path =  $diary_path.'/'.$fil_nm;

            $gh = @fopen($file_path, 'w');
            $fwrite = fwrite($gh, $ex_in_exp[0]);
            fclose($gh);
            if ($fwrite === false) {
                echo "Error in saving";
            } else {
                $dataArray = array(
                    'print_user_id' => $ucode,
                    'print_dt' => date("Y-m-d H:i:s"),
                    'notice_path'=>$fil_nm,
                    'updated_on' => date("Y-m-d H:i:s"),
                    'updated_by' => session()->get('login')['usercode'],
                    'updated_by_ip' => getClientIP(),
                );

                $caveatmatchingupdated = update('caveat_diary_matching', $dataArray, ['caveat_no' => $hd_caveat_no,'diary_no'=>$in_diary_no,'display'=>'Y']);
                if($caveatmatchingupdated){
                    $chk_status = 1;
                }else{
                    $chk_status = 0;
                }

            }
        }

        echo $chk_status;
    }
}