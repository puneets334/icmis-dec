<?php

namespace App\Models\Listing;
use CodeIgniter\Model;

class Judge extends Model
{

    protected $table = 'master.judge';
    protected $primaryKey = 'jcode';

    

    
    protected $allowedFields = [ 'jcode', 'jname', 'first_name', 'title', 'sur_name', 'jcourt', 'abbreviation', 'is_retired', 'display', 'appointment_date', 'to_dt', 'cji_date',
                                 'jtype', 'entuser', 'entdt', 'judge_seniority', 'national_uid', 'judge_desg_code', 'create_modify', 'updated_on', 'updated_by', 'updated_by_ip'];



    public function getJudge()
    {
        return $this->where('is_retired', 'N')
                    ->where('display', 'Y')
                    ->where('jtype', 'J')
                    ->findAll(); 
    }
    public function getJudges()
    {
        return $this->where('is_retired', 'N')
                    ->where('display', 'Y')
                    ->where('jtype', 'J')
                    ->orderBy('judge_seniority', 'ASC')
                    ->findAll();
    }


    public function getJudgesList()
    {
        return $this->select("jcode, 
            CASE 
                WHEN jtype = 'J' THEN jname 
                ELSE CONCAT(first_name, ' ', sur_name, ', ', jname) 
            END AS judge_name")
            ->where('display', 'Y')
            ->where('is_retired', 'N')
            ->orderBy('CASE WHEN jtype = \'J\' THEN 1 ELSE 2 END, judge_seniority')
            ->findAll();
    }


}