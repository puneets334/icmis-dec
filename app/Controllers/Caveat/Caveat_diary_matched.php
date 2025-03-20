<?php

namespace CodeIgniter\Validation;

namespace App\Controllers\Caveat;

use App\Controllers\BaseController;
use App\Models\Caveat\Model_similarity;
use App\Models\Entities\Model_Caveat;
use App\Models\Entities\Model_CaveatA;
use App\Models\Entities\Model_CaveatAdvocate;
use App\Models\Entities\Model_CaveatAdvocateA;
use App\Models\Entities\Model_CaveatDiaryMatching;
use CodeIgniter\Model;

class Caveat_diary_matched extends BaseController
{
    public $Model_caveat;
    public $Model_caveat_a;
    public $Model_similarity;
    public $Model_caveat_diary_matching;
    public $Model_CaveatAdvocate;
    public $Model_CaveatAdvocateA;
    function __construct()
    {
        $this->Model_caveat= new Model_Caveat();
        $this->Model_caveat_a= new Model_CaveatA();
        $this->Model_similarity= new Model_similarity();
        $this->Model_caveat_diary_matching= new Model_CaveatDiaryMatching();
        $this->Model_CaveatAdvocate= new Model_CaveatAdvocate();
        $this->Model_CaveatAdvocateA= new Model_CaveatAdvocateA();
    }

    public function index()
    {

        return view('Caveat/caveat_diary_matched');
    }
    public function get_caveat_diary_matched(){
        $data['param']=array();
        $response=$this->get_data();
        $data['caveat_diary_matched_list']=$response;
        $resul_view = view('Caveat/get_caveat_diary_matched',$data);
        echo '1@@@'.$resul_view;exit();
    }
    public function get_data($is_archival_table='')
    {
        $query = $this->db->table("lowerct a")
            ->DISTINCT()
            ->select('a.diary_no, b.caveat_no')
            ->join("caveat_lowerct$is_archival_table b", 'a.lct_dec_dt = b.lct_dec_dt AND a.l_state = b.l_state AND trim(LEADING \'0\' FROM a.lct_caseno) = trim(LEADING \'0\' FROM b.lct_caseno) AND a.lct_caseyear = b.lct_caseyear AND a.ct_code = b.ct_code')
            ->join("caveat$is_archival_table c", 'c.caveat_no = b.caveat_no')
            ->Join('caveat_diary_matching cdm', 'cdm.caveat_no = c.caveat_no AND cdm.diary_no = a.diary_no AND cdm.display = \'Y\'','left')
            ->join("main m", 'm.diary_no = a.diary_no AND m.c_status = \'P\'')
            ->where('date(c.diary_no_rec_date) >=', '2017-05-08')
            ->where('a.lw_display', 'Y')
            ->where('b.lw_display', 'Y')
            ->where('a.is_order_challenged', 'Y')
            ->where('b.lct_dec_dt IS NOT NULL')
            ->where('cdm.caveat_no IS NULL')
            ->where('cdm.diary_no IS NULL')
            ->groupStart()
            // ->where('(DATE(c.diary_no_rec_date) <= DATE(m.diary_no_rec_date) OR DATE(c.diary_no_rec_date) > DATE(m.diary_no_rec_date) AND DATE(m.diary_no_rec_date) - DATE(c.diary_no_rec_date) <= 90)')
            ->where('(m.diary_no_rec_date - c.diary_no_rec_date)<=\'90 days\' or date( c.diary_no_rec_date ) > date( m.diary_no_rec_date )')
            ->groupEnd();
        $result = $query->get()->getResultArray();
        $query_a = $this->db->table("lowerct a")
            ->DISTINCT()
            ->select('a.diary_no, b.caveat_no')
            ->join("caveat_lowerct_a b", 'a.lct_dec_dt = b.lct_dec_dt AND a.l_state = b.l_state AND trim(LEADING \'0\' FROM a.lct_caseno) = trim(LEADING \'0\' FROM b.lct_caseno) AND a.lct_caseyear = b.lct_caseyear AND a.ct_code = b.ct_code')
            ->join("caveat_a c", 'c.caveat_no = b.caveat_no')
            ->Join('caveat_diary_matching cdm', 'cdm.caveat_no = c.caveat_no AND cdm.diary_no = a.diary_no AND cdm.display = \'Y\'','left')
            ->join("main m", 'm.diary_no = a.diary_no AND m.c_status = \'P\'')
            ->where('date(c.diary_no_rec_date) >=', '2017-05-08')
            ->where('a.lw_display', 'Y')
            ->where('b.lw_display', 'Y')
            ->where('a.is_order_challenged', 'Y')
            ->where('b.lct_dec_dt IS NOT NULL')
            ->where('cdm.caveat_no IS NULL')
            ->where('cdm.diary_no IS NULL')
            ->groupStart()
            // ->where('(DATE(c.diary_no_rec_date) <= DATE(m.diary_no_rec_date) OR DATE(c.diary_no_rec_date) > DATE(m.diary_no_rec_date) AND DATE(m.diary_no_rec_date) - DATE(c.diary_no_rec_date) <= 90)')
            ->where('(m.diary_no_rec_date - c.diary_no_rec_date)<=\'90 days\' or date( c.diary_no_rec_date ) > date( m.diary_no_rec_date )')
            ->groupEnd();
        $result_a = $query_a->get()->getResultArray();
        return  $response = array_merge($result, $result_a);

    }
}
