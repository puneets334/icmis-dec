<?php

namespace App\Models\JudgesLibrary;

use CodeIgniter\Model;


class JudgesLibraryModel extends Model
{

    public function getCaseType()
    {
        $builder = $this->db->table('master.casetype');
        $builder->select('casecode, skey, casename, short_description');
        $builder->where('display', 'Y');
        $builder->where('casecode !=', '9999');
        $builder->orderBy('casecode');
        $query = $builder->get();
        $result = $query->getResultArray();
        return $result;
    }
    public function getSearchDiaryAllFieldsC($caseType, $caseNo, $caseYear, $optradio) {}
    public function getSearchDiaryAllFieldsD($diaryNumber, $diaryYear, $optradio)
    {

        // $builder = $this->db->table('main m');
        // $query = $builder->select('m.*')
        //     ->where("LEFT(diary_no, LENGTH(diary_no) - 4) ::bigint", $diaryNumber.''.$diaryYear)
        //     ->where("RIGHT(diary_no, 4)::bigint", $diaryNumber.''.$diaryYear)
        //     ->get();
        // $result = $query->getResultArray();
        $sql = "Select m.* from main m where diary_no = '$diaryNumber$diaryYear'";
        $query = $this->db->query($sql);
        $result = $query->getResultArray(); 
        return $result;
    }
    public function getWithAllConnected($main_diary)
    {
        $sql = "  SELECT STRING_AGG(diary_no::TEXT, ',') AS conn_list  FROM main WHERE conn_key = '$main_diary'";
        $query = $this->db->query($sql);
        $result = $query->getResultArray();
        return $result;
    }
    public function getCaseDetailsJudgementFlagChange() {
        

    }
}
