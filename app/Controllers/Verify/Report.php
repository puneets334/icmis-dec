<?php
namespace App\Controllers\Verify;
use App\Controllers\BaseController;
use App\Models\Casetype;
use App\Models\Judicial\FileCover\CoverPrintModel;

class Report extends BaseController
{ 
    public $diary_no;
    public $Casetype;
    public $CaseAdd;
    function __construct()
    { 
        $this->Casetype = new Casetype();
    }
    public function index()
    {    $db = \Config\Database::connect();
         $ucode = session()->get('login')['usercode'];
         if($ucode == '1' OR $ucode == '146' OR $ucode == '559' OR $ucode == '469' OR $ucode == '742'){
            $mdacode = "";
             //$mdacode = "AND m.dacode = '1296'";
         }
         else{        
             $mdacode = "AND m.dacode = '$ucode'";
         }

        // Build the subquery for "a"
        $subQueryBuilder = $db->table('heardt h')
            ->select('m.dacode')
            ->select('SUM(CASE WHEN h.ent_dt > cv.ent_dt OR cv.ent_dt IS NULL THEN 1 ELSE 0 END) AS notverified', false)
            ->select('SUM(CASE WHEN cv.ent_dt > h.ent_dt THEN 1 ELSE 0 END) AS verified', false)
            ->join('main m', 'm.diary_no = h.diary_no', 'left')
            ->join('case_verify cv', "cv.diary_no = h.diary_no AND cv.display = 'Y'", 'left')
            ->where('h.next_dt >= NOW()::DATE')
            ->whereIn('h.main_supp_flag', [1, 2])
            ->where('h.roster_id >', 0)
            ->where('m.diary_no IS NOT NULL')
            ->where('m.c_status', 'P')
            ->groupBy('m.dacode');
            
        $subQuery = $subQueryBuilder->getCompiledSelect();

        // Build the main query
        $builder = $db->table("($subQuery) a", false)
            ->select('u.name, u.empid, us.section_name')
            ->select('a.*')
            ->join('master.users u', 'u.usercode = a.dacode AND u.display = \'Y\'', 'left')
            ->join('master.usersection us', "us.id = u.section AND us.display = 'Y'", 'left')
            ->orderBy('us.section_name')
            ->orderBy('u.name');

            // echo $builder->getCompiledSelect();die;

        $query = $builder->get();
        $result = $query->getResultArray();
    //    pr($result);
        
        $data = [
            'results' => $result
        ];

        return view('Verify/report', $data);
       
    }
}
