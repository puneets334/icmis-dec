<?php

namespace App\Models\Filing;

use CodeIgniter\Model;

class EarliercourtModel extends Model
{
    protected $db;

    public function __construct(){
        parent::__construct();
        $db = \Config\Database::connect();
        $this->db = db_connect();
    }

    // public function getAllLowerCourtDetails($dairy_no)

    // {
    //     $builder = $this->db->table("lowerct as a");

    //     $builder->select('casetype_id,
    //     lct_dec_dt,
    //     lct_judge_name,
    //     lctjudname2,
    //     lctjudname3,
    //     l_dist,
    //     ct_code,
    //     l_state,
    //     name,
    //     brief_desc desc1,
    //     sub_law usec2,
    //     lct_judge_desg');
    //     $builder->select("    CASE 
    //     WHEN ct_code = 3 THEN (
    //             CASE WHEN l_state = 490506 THEN (
    //                 SELECT 
    //                 court_name as Name
    //             FROM
    //                 master.state s
    //                     LEFT JOIN
    //                 master.delhi_district_court d ON s.state_code = d.state_code
    //                     AND s.district_code = d.district_code
    //             WHERE
    //                 s.id_no = a.l_dist AND display = 'Y'
    //             )ELSE(
    //                 SELECT 
    //                 Name
    //             FROM
    //                 master.state s
    //             WHERE
    //                 s.id_no = a.l_dist AND display = 'Y'
    //             )
    //         END
    //     )
    //     ELSE (
    //         SELECT 
    //         agency_name
    //     FROM
    //         master.ref_agency_code c
    //     WHERE
    //         c.cmis_state_id = a.l_state
    //             AND c.id = a.l_dist
    //             AND is_deleted = 'f'
    //     )
    // END AS agency_name", false);
    //     $builder->select('crimeno,
    //     crimeyear,
    //     polstncode');
    //     $builder->select("(SELECT 
    //     policestndesc
    // FROM
    //     master.police p
    // WHERE
    //     p.policestncd = a.polstncode
    //         AND p.display = 'Y'
    //         AND p.cmis_state_id = a.l_state
    //         AND p.cmis_district_id = a.l_dist
    //         AND a.crimeno != ''
    //         AND a.crimeno != '0') policestndesc", false);
    //     $builder->select('authdesc,
    //     l_inddep,
    //     l_orgname,
    //     l_ordchno,
    //     l_iopb,
    //     l_iopbn,
    //     l_org,
    //     lct_casetype,
    //     lct_caseno,
    //     lct_caseyear');
    //     $builder->select("CASE 
    //     WHEN ct_code = 4 THEN (
    //         SELECT 
    //         skey
    //     FROM
    //         master.casetype ct
    //     WHERE
    //         ct.display = 'Y'
    //             AND ct.casecode = a.lct_casetype     
    //     )
    //     ELSE (
    //         SELECT 
    //         type_sname
    //     FROM
    //         master.lc_hc_casetype d
    //     WHERE
    //         d.lccasecode = a.lct_casetype
    //             AND d.display = 'Y'          
    //     )
    // END AS type_sname", false);
    //     $builder->select('a.lower_court_id,
    // is_order_challenged,
    // full_interim_flag,
    // judgement_covered_in,
    // vehicle_code,
    // vehicle_no,
    // code,
    // post_name,
    // cnr_no,
    // ref_court,
    // ref_case_type,
    // ref_case_no,
    // ref_case_year,
    // ref_state,
    // ref_district,
    // gov_not_state_id,
    // gov_not_case_type,
    // gov_not_case_no,
    // gov_not_case_year,
    // gov_not_date,
    // relied_court,
    // relied_case_type,
    // relied_case_no,
    // relied_case_year,
    // relied_state,
    // relied_district,
    // transfer_case_type,
    // transfer_case_no,
    // transfer_case_year,
    // transfer_state,
    // transfer_district,
    // transfer_court');
    //     $builder->join('master.state b', 'a.l_state = b.id_no AND b.display = \'Y\'', 'left');
    //     $builder->join('main e', 'e.diary_no = a.diary_no');
    //     $builder->join('master.authority f', 'f.authcode = a.l_iopb AND f.display = \'Y\'', 'left');
    //     $builder->join('master.rto h', 'h.id = a.vehicle_code AND h.display = \'Y\'', 'left');
    //     $builder->join('master.post_t i', 'i.post_code = a.lct_judge_desg AND i.display = \'Y\'', 'left');
    //     $builder->join('relied_details rd', 'rd.lowerct_id = a.lower_court_id AND rd.display = \'Y\'', 'left');
    //     $builder->join('transfer_to_details t_t', 't_t.lowerct_id = a.lower_court_id AND t_t.display = \'Y\'', 'left');
    //     $builder->where('a.diary_no', $dairy_no);
    //     $builder->where('a.lw_display', 'Y');  // and ((lw_display='R' and lct_dec_dt is null and polstncode ='0' and crimeno =''
    //     // and crimeyear ='0') or (lw_display='Y' and lct_dec_dt='2022-03-01' and polstncode ='0' and crimeno =''
    //     // and crimeyear ='0'))
    //     $builder->orderBy('a.lower_court_id');
    //     //  $queryString = $builder->getCompiledSelect();
    //     //  echo $queryString;
    //     //  exit();
    //     $query = $builder->get();

    //     if ($query->getNumRows() >= 1) {
    //         $result = $query->getResultArray();
    //         return $result;
    //         //  echo '<pre>';print_r($result);exit();
    //     } else {
    //         return false;
    //     }  // and ((lw_display='R' and lct_dec_dt is null and polstncode ='0' and crimeno =''
    //     // and crimeyear ='0') or (lw_display='Y' and lct_dec_dt='2022-03-01' and polstncode ='0' and crimeno =''
    //     // and crimeyear ='0'))
    // } 

    public function getAllLowerCourtDetails($dairy_no)
{
    $builder = $this->db->table("lowerct as a");
//main
    $builder->select('e.casetype_id,
    a.lct_dec_dt,
    a.lct_judge_name,
    a.lctjudname2,
    a.lctjudname3,
    a.l_dist,
    a.ct_code,
    a.l_state,
    b.name, 
    a.brief_desc as desc1,
    a.sub_law as usec2,
    a.lct_judge_desg');
    
    $builder->select("CASE 
    WHEN a.ct_code = 3 THEN (
            CASE WHEN a.l_state = 490506 THEN (
                SELECT 
                d.court_name as Name
                FROM
                    master.state s
                    LEFT JOIN
                    master.delhi_district_court d ON s.state_code = d.state_code
                    AND s.district_code = d.district_code
                WHERE
                    s.id_no = a.l_dist AND s.display = 'Y'
                LIMIT 1
            ) ELSE (
                SELECT 
                s.Name
                FROM
                    master.state s
                WHERE
                    s.id_no = a.l_dist AND s.display = 'Y'
                LIMIT 1
            )
        END
    )
    ELSE (
        SELECT 
        c.agency_name
        FROM
            master.ref_agency_code c
        WHERE
            c.cmis_state_id = a.l_state
            AND c.id = a.l_dist
            AND c.is_deleted = 'f'
        LIMIT 1
    )
    END AS agency_name", false);
    
    $builder->select('a.crimeno,
    a.crimeyear,
    a.polstncode');
    
    $builder->select("(SELECT 
    p.policestndesc
    FROM
        master.police p
    WHERE
        p.policestncd = a.polstncode
        AND p.display = 'Y'
        AND p.cmis_state_id = a.l_state
        AND p.cmis_district_id = a.l_dist
        AND a.crimeno != ''
        AND a.crimeno != '0'
        LIMIT 1) AS policestndesc", false);
    
    $builder->select('authdesc,
    a.l_inddep,
    a.l_orgname,
    a.l_ordchno,
    a.l_iopb,
    a.l_iopbn,
    a.l_org,
    a.lct_casetype,
    a.lct_caseno,
    a.lct_caseyear');
    
    $builder->select("CASE 
    WHEN a.ct_code = 4 THEN (
        SELECT 
        ct.skey
        FROM
            master.casetype ct
        WHERE
            ct.display = 'Y'
            AND ct.casecode = a.lct_casetype
        LIMIT 1     
    )
    ELSE (
        SELECT 
        d.type_sname
        FROM
            master.lc_hc_casetype d
        WHERE
            d.lccasecode = a.lct_casetype
            AND d.display = 'Y'
        LIMIT 1          
    )
    END AS type_sname", false);
    
    $builder->select('a.lower_court_id,
    a.is_order_challenged,
    a.full_interim_flag,
    a.judgement_covered_in,
    a.vehicle_code,
    a.vehicle_no,
    code,
    post_name,
    a.cnr_no,
    a.ref_court,
    a.ref_case_type,
    a.ref_case_no,
    a.ref_case_year,
    a.ref_state,
    a.ref_district,
    a.gov_not_state_id,
    a.gov_not_case_type,
    a.gov_not_case_no,
    a.gov_not_case_year,
    a.gov_not_date,
    relied_court,
    relied_case_type,
    relied_case_no,
    relied_case_year,
    relied_state,
    relied_district,
    transfer_case_type,
    transfer_case_no,
    transfer_case_year,
    transfer_state,
    transfer_district,
    transfer_court');
    
    $builder->join('master.state b', 'a.l_state = b.id_no AND b.display = \'Y\'', 'left');
    $builder->join('main e', 'e.diary_no = a.diary_no');
    $builder->join('master.authority f', 'f.authcode = a.l_iopb AND f.display = \'Y\'', 'left');
    $builder->join('master.rto h', 'h.id = a.vehicle_code AND h.display = \'Y\'', 'left');
    $builder->join('master.post_t i', 'i.post_code = a.lct_judge_desg AND i.display = \'Y\'', 'left');
    $builder->join('relied_details rd', 'rd.lowerct_id = a.lower_court_id AND rd.display = \'Y\'', 'left');
    $builder->join('transfer_to_details t_t', 't_t.lowerct_id = a.lower_court_id AND t_t.display = \'Y\'', 'left');
    
    $builder->where('a.diary_no', $dairy_no);
    $builder->where('a.lw_display', 'Y');
    $builder->orderBy('a.lower_court_id');
    // echo $builder->getCompiledSelect(false); 
    // die;
    $query = $builder->get();
   // echo $query = $this->db->getLastQuery();
    //die;
    if ($query->getNumRows() >= 1) {
        return $query->getResultArray();
    } else {
        return false;
    }
}


    public function getAlreadyAddeLowerCourtDetails($conditionArray)
    {

        $dairy_no = $conditionArray['diary_no'];
        $state_agency = $conditionArray['state_agency'];
        $district_id = $conditionArray['district_id'];
        $case_type = $conditionArray['case_type'];
        $lc_case_no = (string)$conditionArray['lc_case_no'];
        $lc_case_year = $conditionArray['lc_case_year'];
        $lct_dec_dt = $conditionArray['lct_dec_dt'];
        $m_policestn = $conditionArray['m_policestn'];
        $crimeno = $conditionArray['crimeno'];
        $crimeyear = $conditionArray['crimeyear'];

        $c_type = $conditionArray['c_type'];

        $builder = $this->db->table("lowerct");
        $builder->select('*');
        $builder->where('diary_no', $dairy_no);
        $builder->where('l_state', $state_agency);
        $builder->where('l_dist', $district_id);
        $builder->where('ct_code', $c_type);
        $builder->where('lct_casetype', $case_type);
        $builder->where('lct_caseno', $lc_case_no);
        $builder->where('lct_caseyear', $lc_case_year)->groupStart()
            ->groupStart()->where('lw_display', 'R')->where('lct_dec_dt is null')->where('polstncode', $m_policestn)->where('crimeno', $crimeno)->where('crimeyear', $crimeyear)->groupEnd()
            ->orGroupStart()->where('lw_display', 'Y')->where('lct_dec_dt', $lct_dec_dt)->where('polstncode', $m_policestn)->where('crimeno', $crimeno)->where('crimeyear', $crimeyear)
            ->groupEnd()->groupEnd();

        $query = $builder->get();
       // $query = $this->db->getLastQuery();
        // echo (string) $query;
        // exit();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return false;
        }

       
        // $result = $query->getResultArray();
    }

    public function getEarlierCourtData($lc_id)
    {
        $builder = $this->db->table("lowerct");
        $builder->select('*');
        $builder->where('lower_court_id', $lc_id);
       
        $query = $builder->get();

        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return false;
        }
        // $result = $query->getResultArray();
    }

    public function insertEarlierCourt($earlierCourtArray)
    {
        $builder = $this->db->table("lowerct");
        // $builder->insert($earlierCourtArray);
        // echo "fsdaf";
        // $query=$this->db->getLastQuery();echo (string) $query;exit();
        //lower_court_id
        if ($builder->insert($earlierCourtArray)) {
            //return true;
            return $this->db->insertID();
        } else {
            return false;
        }
    }

    public function insertLowerCourtJudges($judgesArray)
    {
        $builder = $this->db->table("lowerct_judges");
        if ($builder->insert($judgesArray)) {
            return true;
            //return $this->db->insertID();
        } else {
            return false;
        }
    }

    public function updateEarlierCourt($updateData){
        $builder = $this->db->table("lowerct");
        $builder->updateBatch($updateData, 'lower_court_id');  //pass id in it
    }

    public function getJudgesDetailsofLowerCourt($lowerct_id){
        $builder = $this->db->table("lowerct_judges");
        $builder->select('*');
        $builder->where('lowerct_id', $lowerct_id);
        $builder->where('lct_display', 'Y');
      
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return false;
        }

    }

    public function getJudgeDetailsByDiary($dairy_no)
    {
        $builder = $this->db->table("lowerct as l");
        $builder->select([
            'id',
            'lower_court_id',
            '(CASE 
                WHEN l.ct_code = 4 THEN (
                    SELECT 
                        CASE 
                            WHEN lj.judge_id is not null THEN CONCAT(j.title, \' \', j.first_name, \' \', j.sur_name)
                            ELSE \'\'
                        END          
                    FROM master.judge j 
                    WHERE lj.judge_id = j.jcode            
                )
                ELSE (
                    SELECT 
                        CASE 
                            WHEN lj.judge_id is not null THEN CONCAT(oj.title, \' \', oj.first_name, \' \', oj.sur_name)
                            ELSE \'\'
                        END
                    FROM master.org_lower_court_judges oj 
                    WHERE lj.judge_id = oj.id           
                )
            END) AS judge_name',
        ]);
        
        $builder->join('lowerct_judges as lj', 'l.lower_court_id = lj.lowerct_id', 'RIGHT');
        $builder->where('l.diary_no',$dairy_no);
        $query = $builder->get();
        
        $resultJudges = $query->getResultArray();
        if (is_array($resultJudges)) {
            $lower_court_id_arr = [];
            $baseArr = [];
            foreach ($resultJudges as $judges) {
                $lower_court_id_arr[] = $judges['lower_court_id'];
                $baseArr[] = $judges;
            }

            $arr1 = array_unique($lower_court_id_arr);

            $resultSet = [];
            foreach ($resultJudges as $jud) {
                if (in_array($jud['lower_court_id'], $arr1)) {
                    $id = $jud['lower_court_id'];
                    $resultSet[$id][] = [
                        'judge_name' => $jud['judge_name']
                    ];
                }
            }
        }
        return $resultSet;
    }


    


    public function getReliedDetails($conditionArray)
    {
        $lowerct_id = $conditionArray['lowerct_id'];
        $relied_court = $conditionArray['relied_court'];
        $relied_case_type = $conditionArray['relied_case_type'];
        $relied_case_no = $conditionArray['relied_case_no'];
        $relied_case_year = $conditionArray['relied_case_year'];
        $relied_state = $conditionArray['relied_state'];
        $relied_district = $conditionArray['relied_district'];

        $builder = $this->db->table("relied_details");
        $builder->select('count(*)');
        $builder->where('lowerct_id', $lowerct_id);
        $builder->where('relied_court', $relied_court);
        $builder->where('relied_case_type', $relied_case_type);
        $builder->where('relied_case_no', $relied_case_no);
        $builder->where('relied_case_year', $relied_case_year);
        $builder->where('relied_state', $relied_state);
        $builder->where('relied_district', $relied_district);
      
        $query = $builder->get();

        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return false;
        }
        // $result = $query->getResultArray();
    }

    public function insertJudgeDetails($judge_details)
    {
        $builder = $this->db->table("lowerct_judges");
        if ($builder->insert($judge_details)) {
            return true;
            //return $this->db->insertID();
        } else {
            return false;
        }
    }

    public function insertReliedDetails($relied_details)
    {
        $builder = $this->db->table("relied_details");
        if ($builder->insert($relied_details)) {
            return true;
            //return $this->db->insertID();
        } else {
            return false;
        }
    }
   

    public function getTransferDetails($conditionArray)
    {
        $lowerct_id = $conditionArray['lowerct_id'];
        $transfer_court = $conditionArray['transfer_court'];
        $transfer_case_type = $conditionArray['transfer_case_type'];
        $transfer_case_no = $conditionArray['transfer_case_no'];
        $transfer_case_year = $conditionArray['transfer_case_year'];
        $transfer_state = $conditionArray['transfer_state'];
        $transfer_district = $conditionArray['transfer_district'];

        $builder = $this->db->table("transfer_to_details");
        $builder->select('count(*)');
        $builder->where('lowerct_id', $lowerct_id);
        $builder->where('transfer_court', $transfer_court);
        $builder->where('transfer_case_type', $transfer_case_type);
        $builder->where('transfer_case_no', $transfer_case_no);
        $builder->where('transfer_case_year', $transfer_case_year);
        $builder->where('transfer_state', $transfer_state);
        $builder->where('transfer_district', $transfer_district);
      
        $query = $builder->get();

        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return false;
        }
        // $result = $query->getResultArray();
    }

    public function insertTransferDetails($transfer_details)
    {
        $builder = $this->db->table("transfer_to_details");
        if ($builder->insert($transfer_details)) {
            return true;
            //return $this->db->insertID();
        } else {
            return false;
        }
    }

    public function deleteTransferDetails($lowerCourt){
        $builder = $this->db->table("transfer_to_details");
        $builder->where('lowerct_id', $lowerCourt);
        if($builder->delete()){
            return true;
        } else {
            return false;
        }
    }

    public function deleteReliedDetails($lowerCourt){
        $builder = $this->db->table("relied_details");
        $builder->where('lowerct_id', $lowerCourt);
        if($builder->delete()){
            return true;
        } else {
            return false;
        }
    }


    public function deleteJudgesDetails($lowerCourt){
        $builder = $this->db->table("lowerct_judges");
        $builder->where('lowerct_id', $lowerCourt);
        if($builder->delete()){
            return true;
        } else {
            return false;
        }
    }

    public function deleteLowerCourtDetails($lowerCourt){
        $builder = $this->db->table("lowerct");
        $builder->where('lower_court_id', $lowerCourt);
        if($builder->delete()){
            return true;
        } else {
            return false;
        }
    }

    public function getCaveatDetails($d_no,$lc_cno, $lc_cyear)
    {

        $builder = $this->db->table('caveat_lowerct');

// Subquery to get caveat_no values from caveat_diary_matching
        $subquery = $this->db->table('caveat_diary_matching')
            ->select('caveat_no')
            ->where('diary_no', $d_no)
            ->where('display', 'Y');

        // Build the main query
        $query = $builder->select('*')
            ->whereIn('caveat_no', $subquery)
            ->where('lct_caseyear', $lc_cyear)
            ->where('lct_caseno', $lc_cno)
            ->where('lw_display', 'Y')
            ->get();

        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return false;
        }
        
    }


    public function getCaveatDetailsNumber($d_no,$lc_cno, $lc_cyear)
    {

        $builder = $this->db->table('caveat_lowerct');

        // Subquery to get caveat_no values from caveat_diary_matching
        $subquery = $this->db->table('caveat_diary_matching')
            ->select('caveat_no')
            ->where('diary_no', $d_no)
            ->where('display', 'Y');

        // Build the main query
        $builder->select("STRING_AGG(CONCAT(SUBSTRING(cast(caveat_no as text) FROM 1 FOR LENGTH(cast(caveat_no as text))-4),'/', SUBSTRING(cast(caveat_no as text) FROM -4)), ',')", false)
        ->whereIn('caveat_no', $subquery)
        ->where('lct_caseyear', $lc_cyear)
        ->where('lct_caseno', $lc_cno)
        ->where('lw_display', 'Y');
        // $queryString = $builder->getCompiledSelect();
        // echo $queryString;
        // exit();

        $query = $builder->get();

        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return false;
        }
        
    }
    
    
    public function allReliedDetailsbyLowerCourt($lowerct_id)
    {
       
        $builder = $this->db->table("relied_details");
        $builder->select('*');
        $builder->where('lowerct_id', $lowerct_id);
        $builder->where('display', 'Y');
      
        $query = $builder->get();

        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result[0];
        } else {
            return false;
        }
        // $result = $query->getResultArray();
    }    


    public function allTransferDetails($lowerct_id)
    {
        $builder = $this->db->table("transfer_to_details");
        $builder->select('*');
        $builder->where('lowerct_id', $lowerct_id);
        $builder->where('display', 'Y');
        $query = $builder->get();

        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result[0];
        } else {
            return false;
        }
        // $result = $query->getResultArray();
    }

    public function allTransferDetailsByDiaryNo($dairy_no)
    {
        $builder = $this->db->table("lowerct as a");
        $builder->select([
            'lower_court_id',
            'transfer_court',
            'transfer_case_type',
            'name',
            '(CASE WHEN transfer_court = 3 THEN (CASE WHEN t.transfer_state = 490506 THEN (SELECT court_name FROM master.state s LEFT JOIN master.delhi_district_court d ON s.state_code = d.state_code AND s.district_code = d.district_code WHERE s.id_no = t.transfer_district AND display = \'Y\') ELSE (SELECT Name FROM master.state s WHERE s.id_no = t.transfer_district AND display = \'Y\') END) ELSE (SELECT agency_name FROM master.ref_agency_code c WHERE c.cmis_state_id = t.transfer_state AND c.id = t.transfer_district AND is_deleted = \'f\') END) AS reference_name',
            'CONCAT(CASE WHEN transfer_court = 4 THEN (SELECT skey FROM master.casetype ct WHERE ct.display = \'Y\' AND ct.casecode = t.transfer_case_type) ELSE (SELECT type_sname FROM master.lc_hc_casetype d WHERE d.lccasecode = t.transfer_case_type AND d.display = \'Y\') END, \'-\', transfer_case_no, \'-\', transfer_case_year) AS case_name',
            '(CASE WHEN transfer_court = 4 THEN \'Supreme Court\' WHEN transfer_court = 1 THEN \'High Court\' WHEN transfer_court = 3 THEN \'District Court\' WHEN transfer_court = 5 THEN \'State Agency\' END) AS court_name',
        ]);
        
        $builder->join('transfer_to_details t', 't.lowerct_id = a.lower_court_id AND t.display = \'Y\'', 'LEFT');
        $builder->join('master.state b', 't.transfer_state = b.id_no AND b.display = \'Y\'', 'LEFT');
        
        $builder->where('a.diary_no', $dairy_no);
        $builder->where('a.lw_display', 'Y');
        $builder->where('transfer_court >=', 1);
        
        $builder->orderBy('a.lower_court_id');
        
        $query = $builder->get();

        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            if(is_array($result)){
                foreach($result as $all_data){
                    $data[$all_data['lower_court_id']] = $all_data;
                }
            }
            return $data;
        } else {
            return false;
        }
    }

    public function allReferenceDetailsByDiaryNo($dairy_no)
    {
        $builder = $this->db->table("lowerct as a");
        $builder->select([
            'lower_court_id',
            'ref_court',
            'name',
            '(CASE WHEN ref_court = 3 THEN (CASE WHEN ref_state = 490506 THEN (SELECT court_name as Name FROM master.state s LEFT JOIN master.delhi_district_court d ON s.state_code = d.state_code AND s.district_code = d.district_code WHERE s.id_no = a.ref_district AND display = \'Y\') ELSE (SELECT Name FROM master.state s WHERE s.id_no = a.ref_district AND display = \'Y\') END) ELSE (SELECT agency_name FROM master.ref_agency_code c WHERE c.cmis_state_id = a.ref_state AND c.id = a.ref_district AND is_deleted = \'f\') END) AS reference_name',
            'CONCAT(CASE WHEN ref_court = 4 THEN (SELECT skey FROM master.casetype ct WHERE ct.display = \'Y\' AND ct.casecode = a.ref_case_type) ELSE (SELECT type_sname FROM master.lc_hc_casetype d WHERE d.lccasecode = a.ref_case_type AND d.display = \'Y\') END, \'-\', ref_case_no, \'-\', ref_case_year) AS case_name',
            '(CASE WHEN ref_court = 4 THEN \'Supreme Court\' WHEN ref_court = 1 THEN \'High Court\' WHEN ref_court = 3 THEN \'District Court\' WHEN ref_court = 5 THEN \'State Agency\' END) AS court_name',
        ]);
      
        $builder->join('master.state b', 'a.ref_state = b.id_no AND b.display = \'Y\'', 'LEFT');
        
        $builder->where('a.diary_no', $dairy_no);
        $builder->where('a.lw_display', 'Y');
        $builder->where('ref_court >=', 1);
        
        $builder->orderBy('a.lower_court_id');
        //echo $builder->getCompiledSelect();
        //die;
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            if(is_array($result)){
                foreach($result as $all_data){
                    $data[$all_data['lower_court_id']] = $all_data;
                }
            }
            return $data;
        } else {
            return false;
        }
    }
    public function allGovernmentNotificationsByDiaryNo($dairy_no)
    {
        $builder = $this->db->table("lowerct as a");
        $builder->select([
            'lower_court_id',
            'name',
            'CONCAT(gov_not_case_type, \'-\', gov_not_case_no, \'-\', gov_not_case_year) AS case_name',
            'gov_not_date',
        ]);
       
        $builder->join('master.state b', 'a.gov_not_state_id = b.id_no AND b.display = \'Y\'', 'LEFT');
        
        $builder->where('a.diary_no', $dairy_no);
        $builder->where('a.lw_display', 'Y');
        $builder->where('gov_not_state_id >=', 1);
        
        $builder->orderBy('a.lower_court_id');
        
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            if(is_array($result)){
                foreach($result as $all_data){
                    $data[$all_data['lower_court_id']] = $all_data;
                }
            }
            return $data;
        } else {
            return false;
        }

    }

    public function allReliedDetailsByDiaryNo($dairy_no)
    {
        $builder = $this->db->table("lowerct as a");
        $builder->select([
            'lower_court_id',
            'relied_court',
            'relied_case_type',
            'name',
            '(CASE WHEN relied_court = 3 THEN (CASE WHEN rd.relied_state = 490506 THEN (SELECT court_name as Name FROM master.state s LEFT JOIN master.delhi_district_court d ON s.state_code = d.state_code AND s.district_code = d.district_code WHERE s.id_no = rd.relied_district AND display = \'Y\') ELSE (SELECT Name FROM master.state s WHERE s.id_no = rd.relied_district AND display = \'Y\') END) ELSE (SELECT agency_name FROM master.ref_agency_code c WHERE c.cmis_state_id = rd.relied_state AND c.id = rd.relied_district AND is_deleted = \'f\') END) AS reference_name',
            'CONCAT(CASE WHEN relied_court = 4 THEN (SELECT skey FROM master.casetype ct WHERE ct.display = \'Y\' AND ct.casecode = rd.relied_case_type) ELSE (SELECT type_sname FROM master.lc_hc_casetype d WHERE d.lccasecode = rd.relied_case_type AND d.display = \'Y\') END, \'-\', relied_case_no, \'-\', relied_case_year) AS case_name',
            '(CASE WHEN relied_court = 4 THEN \'Supreme Court\' WHEN relied_court = 1 THEN \'High Court\' WHEN relied_court = 3 THEN \'District Court\' WHEN relied_court = 5 THEN \'State Agency\' END) AS court_name',
        ]);
        
        $builder->join('relied_details rd', 'rd.lowerct_id = a.lower_court_id AND rd.display = \'Y\'', 'LEFT');
        $builder->join('master.state b', 'rd.relied_state = b.id_no AND b.display = \'Y\'', 'LEFT');
        
        $builder->where('a.diary_no',$dairy_no);
        $builder->where('a.lw_display', 'Y');
        $builder->where('relied_court >=', 1);
        
        $builder->orderBy('a.lower_court_id');
        
        $query = $builder->get();

        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            if(is_array($result)){
                foreach($result as $all_data){
                    $data[$all_data['lower_court_id']] = $all_data;
                }
            }
            return $data;
        } else {
            return false;
        }
    }

    public function getSectionOfficer($diary_no)
    {
        $userid = session()->get('login')['usercode'];
        $sql="select  count(*) as t from main m left join master.users u on m.dacode=u.usercode 
where u.display='Y' and m.diary_no=$diary_no and u.section in
(select s.usec from  master.users u  join master.usertype t on u.usertype=t.id join 
 master.user_sec_map s on u.empid=s.empid where t.id in(9,6,4,60) and u.display='Y' and t.display='Y' 
 and u.usercode=$userid and s.display='Y' )";

        $query=$this->db->query($sql);
        return $results = $query->getRowArray();
    }

    public function getIBOfficer()
    {
        $userid = session()->get('login')['usercode'];
        $sql="select count(*) as t from master.users where usercode='$userid' and section=19 and usertype in(14,9,6,4)";
        $query=$this->db->query($sql);
        return $results = $query->getRowArray();
    }




}
