<?php

namespace App\Controllers\Listing;
use App\Controllers\BaseController;
use CodeIgniter\Controller;
use App\Models\Filing\AdvocateModel;
use App\Models\Listing\CaseInfoModel;
use App\Models\Common\Dropdown_list_model;
use App\Models\Listing\Subheading;
use App\Models\Listing\CaseType;
use App\Models\Listing\Submaster;
use App\Models\Listing\ListingPurpose;
use App\Models\Listing\PoolModel;
use App\Models\Listing\CaseAdd;
class Pool extends BaseController
{


    public $model;
    public $diary_no;
    public $CaseInfoModel;
    public $Dropdown_list_model;
    public $PoolModel;
    public $CaseAdd;
    
    function __construct()
    {
        ini_set('memory_limit','4024M');
        $this->CaseInfoModel = new CaseInfoModel();

        $this->PoolModel = new PoolModel();
        $this->CaseAdd = new CaseAdd();
        $this->Dropdown_list_model = new Dropdown_list_model();

        /*if(empty(session()->get('filing_details')['diary_no'])){
            header('Location:'.base_url('Filing/Diary/search'));exit();
        }else{
            $this->diary_no = session()->get('filing_details')['diary_no'];
        }*/

    }


    public function index()
    {
        
        $Subheading = new Subheading();
        $CaseType = new CaseType();
        $Submaster = new Submaster();
        $ListingPurpose = new ListingPurpose();
        $cur_ddt = date('Y-m-d', strtotime('+1 day'));
        $f_ia_query = $this->PoolModel->f_ia();
        $f_act_query = $this->PoolModel->f_act();
        $f_keyword_query = $this->PoolModel->f_keyword();
     
       // $cur_ddt = date('Y-m-d', strtotime(' +1 day'));
       // $nextdate =  date("d-m-Y", strtotime(getNextCourtWorkingDate($cur_ddt)));

         
        $data = [
            'subheadings' => $Subheading->getActiveSubheadings(),
            'caseTypes' => $CaseType->getActiveCaseTypes(),
            'submasters' => $Submaster->getActiveSubmasters(),
            'purposes' => $ListingPurpose->getActivePurposes(),
            'f_ia' => $f_ia_query,
            'f_act' => $f_act_query,
            'f_keyword' => $f_keyword_query,
           // 'holiday_str' => getNextHolidays(),
            //'next_court_working_day'=> $nextdate
        ];

        return view('Listing/pool/index.php', $data); 

    }

    public function actSection()
    {
         $request = \Config\Services::request();
         $db = \Config\Database::connect();
         $actIdArray =  $request->getGet('act');
         $builder = $this->db->table('master.act_section')
        ->select('b.section')
        ->join('act_main a', 'a.id = act_section.act_id', 'inner')
        ->whereIn('a.act', $actIdArray)
        ->where('a.display', 'Y')
        ->like('act_section.section', $query)
        ->where('act_section.display', 'Y')
        ->groupBy('act_section.section');
         $result3=  $builder3->get();
         $result3 = $builder3->getResultArray();

         $json = array();
         if($actIdArray == "all")
         {
             $json[] = array('value'=>"",
                           'label'=>"");
         }else{

           foreach($result3 as $row){
                $json[] =   array('value'=>$row['section'],
                'label'=>$row['section']);
           }
    
        }
        echo json_encode($json);
    }


    public function f_selected_values($parm1){
        $dld = "";
        if ((count($parm1) > 1) && $parm1[0] == 'all') {
            unset($parm1[0]);
        }
        foreach ($parm1 as $key => $value){
            $dld .= $value.",";
        }
        return rtrim($dld,',');
    }
    
    public function getResults()
    {
        $request = \Config\Services::request();
        $db = \Config\Database::connect();
        $listingPurpose = $request->getPost('listing_purpose');
        $mainhead = $request->getPost('mainhead');
        $mainSupp = $request->getPost('main_supp');
        $forFixedDate = $request->getPost('forFixedDate') === 'true';
        $listingDt = $request->getPost('listing_dt'); 

        $mdName = $request->getPost('md_name');

         if ($mdName == '0') {
            $is_nmd = "";
         } else {
            $is_nmd = " AND h.is_nmd = '" . $mdName . "'";
         }

        $listorder = $this->f_selected_values($listingPurpose);
        $pListOrder = ($listorder !== "all") ? "AND h.listorder IN ($listorder)" : '';

        $orderBy = $mainhead === "F" ? 
            "CASE WHEN m.listorder IN (4,5,7,8) THEN 
                IF($mainSupp = 2, m.next_dt = '$listingDt', 
                (m.next_dt BETWEEN '$listingDt' AND ADDDATE('$listingDt', INTERVAL 7 - DAYOFWEEK('$listingDt') DAY) OR m.next_dt <= CURDATE())) 
                ELSE m.next_dt > '1947-08-15' END, 
                CAST(RIGHT(m.diary_no, 4) AS UNSIGNED) ASC, 
                CAST(LEFT(m.diary_no, LENGTH(m.diary_no)-4) AS UNSIGNED) ASC" :
            "IF(date(ia_filing_dt) IS NOT NULL, 1, 2), date(ia_filing_dt),
                CAST(RIGHT(m.diary_no, 4) AS UNSIGNED) ASC, 
                CAST(LEFT(m.diary_no, LENGTH(m.diary_no)-4) AS UNSIGNED) ASC";

        $fromYr = $request->getPost('from_yr');
        $toYr = $request->getPost('to_yr');
        $caseFromToYr = ($fromYr !== "0" && $toYr !== "0") ? "AND YEAR(m.diary_no_rec_date) BETWEEN '$fromYr' AND '$toYr'" : '';

        $civilCriminal = $request->getPost('civil_criminal');
        $caseGrp = ($civilCriminal === 'C' || $civilCriminal === 'R') ? "AND m.case_grp = '$civilCriminal'" : '';

        $bench = $request->getPost('bench');
        $benchCondition = ($bench !== "A") ? "AND h.board_type = '$bench'" : '';

        $subheadArry = $this->f_selected_values($request->getPost('subhead'));
        $getIADate = ($mainhead !== 'F' && in_array('817', explode(',', $subheadArry)))
            ? ", (SELECT MIN(doc.ent_dt) FROM docdetails doc 
                INNER JOIN main mn ON doc.diary_no = mn.diary_no
                LEFT JOIN conct ct ON mn.diary_no = ct.conn_key 
                WHERE doc.doccode = 8 AND doc.doccode1 = 3 AND doc.iastat = 'P' 
                AND doc.display = 'Y' AND (ct.list = 'Y' OR ct.list IS NULL)
                AND (mn.diary_no = m.diary_no OR CAST(m.conn_key AS bigint) = m.diary_no) 
                AND mn.c_status = 'P') AS ia_filing_dt" : ', NULL AS ia_filing_dt';

        $subheadSelect = ($subheadArry !== "all") ? "AND h.subhead IN ($subheadArry)" : '';

        $subCatArry = $this->f_selected_values($request->getPost('subject_cat'));
        $subCatQuery = ($subCatArry !== "all") ? "AND c2.submaster_id IN ($subCatArry)" : '';

        $kwordArry = $this->f_selected_values($request->getPost('kword'));
        $leftJoinKword = ($kwordArry !== "all") ? "LEFT JOIN ec_keyword ek ON ek.diary_no = h.diary_no AND ek.display = 'Y'" : '';
        $kwordSelected = ($kwordArry !== "all") ? "AND keyword_id IN ($kwordArry)" : '';

        $iaArry = $this->f_selected_values($request->getPost('ia'));
        $leftJoinDocDetl = ($iaArry !== "all") ? "LEFT JOIN docdetails dd ON dd.diary_no = h.diary_no" : '';
        $docDetlSelected = ($iaArry !== "all") ? "AND dd.doccode1 IN ($iaArry) AND dd.iastat = 'P' AND dd.display = 'Y' AND dd.doccode = '8'" : '';

        $actArry = $this->f_selected_values($request->getPost('act'));
        $leftJoinAct = ($actArry !== "all") ? "LEFT JOIN act_main at ON at.diary_no = h.diary_no" : '';
        $actSelected = ($actArry !== "all") ? "AND at.display = 'Y' AND at.act IN ($actArry)" : '';

        $section = $request->getPost('section');
        $leftJoinSection = ($actArry !== "all" && $section) ? "LEFT JOIN act_section ast ON ast.act_id = at.id" : '';
        $sectionSelected = ($actArry !== "all" && $section) ? "AND ast.section LIKE '" . $this->db->escapeString($section) . "%' AND ast.display = 'Y'" : '';

        $onlyRegs = "";
        $regUnreg = $request->getPost('reg_unreg');
        if ($regUnreg == 1) {
            $regUnregCondition = "OR (m.active_fil_no = '' OR m.active_fil_no IS NULL)";
        } else {
            $onlyRegs = "AND m.active_fil_no != '' AND m.active_fil_no IS NOT NULL";
        }

        $caseTypeArray = $this->f_selected_values($request->getPost('case_type'));
        $caseType = ($caseTypeArray !== "all") ? "AND (TRIM(LEADING '0' FROM SUBSTRING_INDEX(m.fil_no,'-',1)) IN ($caseTypeArray) $onlyRegs)" : '';

       

        if ($forFixedDate) {
            $qryPartListOrNot = "AND m.c_status = 'P' AND h.main_supp_flag = '0' AND h.next_dt = '$listingDt'";
        } else {
            $qryPartListOrNot = "AND m.c_status = 'P' AND h.main_supp_flag = '0' AND CASE WHEN l.fx_wk = 'F' THEN
                IF($mainSupp = 2, h.next_dt = '$listingDt', (h.next_dt = '$listingDt' OR h.next_dt <= CURDATE()))
                ELSE h.next_dt <= '$listingDt' END";
        }

        if ($mdName === "pool" || $mdName === "transfer") {
            $sqlField = "m.*, GROUP_CONCAT(c.diary_no) AS child_case";
            $sqlField2 = "LEFT JOIN conct c ON c.conn_key = m.diary_no AND list = 'Y' GROUP BY m.diary_no ORDER BY brd_slno ASC, $orderBy";

            if ($mdName === "transfer") {
                $partNo = $request->getPost('part_no');
                $rosterJudgesId = explode("|", $request->getPost('roster_judges_id'));
                $transRosId = $rosterJudgesId[1];
                $qryPartListOrNot = "AND h.roster_id = $transRosId AND h.clno = $partNo AND h.next_dt = '$listingDt'";
            }

            if ($mdName === "pool") {
                $leftJoinCoramR = "LEFT JOIN coram cr ON cr.diary_no = h.diary_no AND cr.board_type = 'R' AND cr.to_dt = '0000-00-00' AND cr.display = 'Y'";
                $leftjoin_field = "cr.jud AS r_coram, ";
            }
        } else {

            $mdName = $request->getPost('md_name');
            if ($mdName == "allocation") {
              
                $roster_judges_id = rtrim($this->request->getPost('roster_judges_id'), "JG");
                $explode_rs = explode("JG", $roster_judges_id);
                
                foreach ($explode_rs as $item) {
                    $explode_rs_jg = explode("|", $item);
                    $coram_sele .= $explode_rs_jg[0] . ",";
                }
                
        
                $cor_slse = rtrim($coram_sele, ",");
                
                if ($cor_slse == '') {
                    $cor_slse = "0";
                }
                
               
                $coram_sele_or_null = "";
                $leftjoin_coram_r = "";
                $leftjoin_field = "";
    
              
                if ($bench == 'J' || $bench == 'S') {
                    $coram_sele_or_null = " AND (h.coram IN ($cor_slse) OR h.coram = 0 OR h.coram IS NULL OR h.coram = '' ) ";
                }
                if ($bench == 'R') {
                    $coram_sele_or_null = " AND (cr.jud IS NULL OR cr.jud IN ($cor_slse)) ";
                    $leftjoin_coram_r = " LEFT JOIN coram cr ON cr.diary_no = h.diary_no AND cr.board_type = 'R' AND cr.to_dt = '0000-00-00' AND cr.display = 'Y'";
                    $leftjoin_field = " cr.jud as r_coram, ";
                }
            }
        }
        $sql_field = "count(*) as avl_rc";
        $sql_field2 = ""; 
        $leftjoin_field ="";
        $leftjoin_coram_r = "";
        $coram_sele_or_null = "";

        
        $rgo_dft_left = "";
        $rgo_dft_qry = "";
        $mul_cat_qry = "";
        $advance_allocated_left = "";
        $advance_drop_note_left = "";
        $advance_allocated_qry = "";
        $advance_drop_note_qry = "";
        
        
        if ( $request->getPost('bench') == 'J' || $request->getPost('bench')== 'S') {
            $rgo_dft_left = " LEFT JOIN rgo_default rd ON rd.fil_no = h.diary_no AND rd.remove_def = 'N' ";
            $rgo_dft_qry = " AND rd.fil_no IS NULL ";
            $mul_cat_qry = " c2.diary_no IS NOT NULL AND ";
        }

        if ( $request->getPost('pool_adv') == 'A') {
            $advance_allocated_left = " LEFT JOIN advance_allocated ad_al ON ad_al.diary_no = h.diary_no AND ad_al.next_dt = $listing_dt ";
            $advance_drop_note_left = " LEFT JOIN advanced_drop_note ad_dn ON ad_dn.diary_no = ad_al.diary_no AND ad_dn.cl_date = ad_al.next_dt ";
            $advance_allocated_qry = " AND ad_al.diary_no IS NOT NULL ";
            $advance_drop_note_qry = " AND ad_dn.diary_no IS NULL ";
        }

    
        // $query = "
        // SELECT $sql_field
        // FROM (
        //     SELECT
        //         $leftjoin_field
        //         m.active_fil_no
        //         $getIADate,
        //         m.active_reg_year,
        //         m.active_casetype_id,
        //         m.reg_no_display,
        //         c.short_description,
        //         m.fil_no,
        //         m.fil_dt,
        //         YEAR(m.fil_dt) AS fil_year,
        //         m.lastorder,
        //         m.diary_no_rec_date,
        //         h.*,
        //         l.purpose,
        //         GROUP_CONCAT(c2.submaster_id) AS cat1
        //     FROM main m
        //     LEFT JOIN heardt h ON m.diary_no = h.diary_no
        //     LEFT JOIN master.listing_purpose l ON l.code = h.listorder
        //     LEFT JOIN master.casetype c ON m.active_casetype_id = c.casecode
        //     LEFT JOIN mul_category c2 ON c2.diary_no = h.diary_no
        //         AND c2.display = 'Y'
        //         AND c2.submaster_id != 331
        //         AND c2.submaster_id != ''
        //      $rgo_dft_left
        //     $leftjoin_coram_r
        //     $leftJoinKword    
        //     $leftJoinDocDetl    
        //     $leftJoinAct    
        //     $sectionSelected 
        //        $advance_allocated_left
        //        $advance_drop_note_left
        //     WHERE $mul_cat_qry l.display = 'Y' $subCatQuery $is_nmd $coram_sele_or_null
        //     $rgo_dft_qry
        //     $pListOrder            
        //     $caseGrp
        //     $onlyRegs    
        //     $caseType   
        //     $benchCondition    
        //     $caseFromToYr  
        //     $subheadSelect    
        //     $kwordSelected  
        //     $docDetlSelected    
        //     $actSelected    
        //     $sectionSelected    
        //      $advance_allocated_qry 
        //     $advance_drop_note_qry
        //     AND (m.diary_no = CAST(m.conn_key AS bigint) OR CAST(m.conn_key AS bigint) = 0 OR CAST(m.conn_key AS bigint) = '' OR CAST(m.conn_key AS bigint) IS NULL)
        //     $qryPartListOrNot
        //     AND h.mainhead = $mainhead
        //     GROUP BY h.diary_no) m
        // $sql_field2";

    //       echo $query;

    //     $result = $db->query($query);
     
    //    return $result->getResultArray();

            $subqueryBuilder = $this->db->table('main m')
            ->select("
                $leftJoinField
                m.active_fil_no,
                $getIADate,
                m.active_reg_year,
                m.active_casetype_id,
                m.reg_no_display,
                c.short_description,
                m.fil_no,
                m.fil_dt,
                YEAR(m.fil_dt) AS fil_year,
                m.lastorder,
                m.diary_no_rec_date,
                h.*,
                l.purpose,
                GROUP_CONCAT(c2.submaster_id) AS cat1
            ")
            ->join('heardt h', 'm.diary_no = h.diary_no', 'left')
            ->join('master.listing_purpose l', 'l.code = h.listorder', 'left')
            ->join('master.casetype c', 'm.active_casetype_id = c.casecode', 'left')
            ->join('mul_category c2', 'c2.diary_no = h.diary_no AND c2.display = \'Y\' AND c2.submaster_id != 331 AND c2.submaster_id != \'\'', 'left')
            ->join('rgo_default rd', 'rd.fil_no = h.diary_no AND rd.remove_def = \'N\'', 'left')
            ->where($rgo_dft_left)
            ->where($leftjoin_coram_r)
            ->where($leftJoinKword)
            ->where($leftJoinDocDetl)
            ->where($leftJoinAct)
            ->where($sectionSelected)
            ->where($mul_cat_qry)
            ->where('l.display', 'Y')
            ->where($subCatQuery)
            ->where($is_nmd)
            ->where($coram_sele_or_null)
            ->where($rgo_dft_qry)
            ->where($pListOrder)
            ->where($caseGrp)
            ->where($onlyRegs)
            ->where($caseType)
            ->where($benchCondition)
            ->where($caseFromToYr)
            ->where($subheadSelect)
            ->where($kwordSelected)
            ->where($docDetlSelected)
            ->where($actSelected)
            ->where($sectionSelected)
            ->where($advance_allocated_qry)
            ->where($advance_drop_note_qry)
            ->where("(m.diary_no = CAST(m.conn_key AS bigint) OR CAST(m.conn_key AS bigint) = 0 OR CAST(m.conn_key AS bigint) = '' OR CAST(m.conn_key AS bigint) IS NULL)")
            ->where($qryPartListOrNot)
            ->where('h.mainhead', $mainhead)
            ->groupBy('h.diary_no');

        // Compile the subquery into SQL
        $subquerySQL = $subqueryBuilder->getCompiledSelect();

        // Build the main query
        $builder = $this->db->table("($subquerySQL) AS m")
            ->select($sqlField)
            ->getSQL(); // This is for debugging; you might want to remove it in production

        return $this->db->query($builder)->getResultArray();        
    }


    public function registraPool(){

        $request = \Config\Services::request();
        $db = \Config\Database::connect();

        $ct = $request->getPost('ct') ?? '';
        $cn = $request->getPost('cn') ?? '';
        $cy = $request->getPost('cy') ?? '';

        // $builder = $db->table('main_casetype_history h');
        // $builder->select("
        //     SUBSTRING(h.diary_no FROM 1 FOR LENGTH(h.diary_no) - 4) AS dn, 
        //     SUBSTRING(h.diary_no FROM LENGTH(h.diary_no) - 3 FOR 4) AS dy,
        //     CASE WHEN h.new_registration_number != '' THEN split_part(h.new_registration_number, '-', 1) ELSE '' END AS ct1,
        //     CASE WHEN h.new_registration_number != '' THEN split_part(h.new_registration_number, '-', 2) ELSE '' END AS crf1,
        //     CASE WHEN h.new_registration_number != '' THEN split_part(h.new_registration_number, '-', 3) ELSE '' END AS crl1"
        // );

        // // Add conditions
        // $builder->groupStart();
        // $builder->where("split_part(h.new_registration_number, '-', 1) =", $ct);

        // $builder->where("CAST(" . $cn . " AS INTEGER) BETWEEN CAST(split_part(h.new_registration_number, '-', 2) AS INTEGER) AND CAST(split_part(h.new_registration_number, '-', 3) AS INTEGER)");
        // $builder->where('h.new_registration_year', $cy);
        // $builder->groupEnd();

        // $builder->orGroupStart();
        // $builder->where("split_part(h.old_registration_number, '-', 1) =", $ct);
        // $builder->where("CAST(" . $cn . " AS INTEGER) BETWEEN CAST(split_part(h.old_registration_number, '-', 2) AS INTEGER) AND CAST(split_part(h.old_registration_number, '-', 3) AS INTEGER)");
        // $builder->where('h.old_registration_year', $cy);
        // $builder->groupEnd();

        // // Additional condition
        // $builder->where('h.is_deleted', 'f');

        // // Execute the query
        // $query = $builder->get();


         //////////////////////////////////////////////
        //  $builder1 = $db->table('main_casetype_history');
        //  $builder1->select("SUBSTR(h.diary_no, 1, LENGTH(h.diary_no) - 4) AS dn, 
        // SUBSTR(h.diary_no, -4) AS dy,
        // IF(h.new_registration_number != '', SUBSTRING_INDEX(h.new_registration_number, '-', 1), '') AS ct1,
        // IF(h.new_registration_number != '', SUBSTRING_INDEX(SUBSTRING_INDEX(h.new_registration_number, '-', 2), '-', -1), '') AS crf1,
        // IF(h.new_registration_number != '', SUBSTRING_INDEX(h.new_registration_number, '-', -1), '') AS crl1");
        // $builder1->groupStart()
        // ->groupStart()
        // ->where("SUBSTRING_INDEX(h.new_registration_number, '-', 1)", $ct)
        // ->where("CAST($cn AS UNSIGNED) BETWEEN SUBSTRING_INDEX(SUBSTRING_INDEX(h.new_registration_number, '-', 2), '-', -1) AND SUBSTRING_INDEX(h.new_registration_number, '-', -1)")
        // ->where("h.new_registration_year", $cy)
        // ->groupEnd()
        // ->groupStart()
        // ->where("SUBSTRING_INDEX(h.old_registration_number, '-', 1)", $ct)
        // ->where("CAST($cn AS UNSIGNED) BETWEEN SUBSTRING_INDEX(SUBSTRING_INDEX(h.old_registration_number, '-', 2), '-', -1) AND SUBSTRING_INDEX(h.old_registration_number, '-', -1)")
        // ->where("h.old_registration_year", $cy)
        // ->groupEnd()
        // ->groupEnd()
        // ->where("h.is_deleted", 'f');
        // $query1= $builder1->get();
        //////////////////////////////////////////////
         $builder2 = $db->table('main m');
         $builder2->select('reg_no_display, m.diary_no, diary_no_rec_date, fil_dt, active_fil_dt, fil_dt_fh, mf_active, c_status, pet_name, res_name, pno, rno, active_casetype_id');
         $builder2->where('m.diary_no', $this->diary_no);
         $builder2->where('m.c_status', 'P');
         $query2 = $builder2->get();
         //$results = $query2->getResultArray();
         $results = $query2->getRowArray();
        /////////////////////////////////////////
        //  $builder3 = $db->table('vacation_registrar_pool');
        //  $builder3->where('diary_no', $this->diary_no);
        //  $query3= $builder3->get();
        //////////////////////////////////////////////
       //  $data[''] = $query->getResultArray();
        // $data[''] = $query1->get()->getResultArray();
         
        // $data[''] = $query3->get()->getResultArray();

            $query11= $this->db->table('heardt')
                ->select('mainhead')
                ->where('diary_no', $this->diary_no)
                ->where('mainhead', 'F')
                ->get();
             

            $query12 = $this->db->table('heardt h')
                ->select('s.stagename, h.mainhead')
                ->join('master.subheading s', 'h.subhead = s.stagecode', 'inner')
                ->where('h.diary_no', $this->diary_no)
                ->where('h.mainhead', 'M')
                ->get();
             
            
            $query13= $this->db->table('mul_category mc')
                ->select('s.sub_name1, s.sub_name2, s.sub_name3, s.sub_name4')
                ->join('master.submaster s', 's.id = mc.submaster_id', 'inner')
                ->where('mc.diary_no', $this->diary_no)
                ->where('mc.display', 'Y')
                ->get();
        
                
            $builder111 = $this->db->table('vacation_registrar_pool');
            $builder111->where('diary_no', $this->diary_no);
            $query111 = $builder111->get();
           
            $data['row_avl'] = $results;
            $data['getMainheadInfo'] = $query11->getRowArray();
            $data['getStageNameInfo'] = $query12->getRowArray();
            $data['getCategoryInfo'] =$query13->getResultArray();
            $data['alreadyInPool'] =  $query111->getRowArray(); 

         return view('Listing/pool/registrar_pool',$data); 
    }

    public function registrar_create_pool_get_response()
    {
        $ucode = session()->get('login')['usercode'];
        $diary_no = $this->request->getPost('valid_dno');
        if ($diary_no > 0) {
            $data = [
                'diary_no' => $diary_no,
                'ent_dt'   => date('Y-m-d H:i:s'),
                'user_code' => $ucode
            ];
            $is_saved = insert('vacation_registrar_pool',$data);
            //$is_saved =1;
            if ($is_saved) {
                return $this->response->setJSON(['message' => true]);
            } else {
                return $this->response->setJSON(['message' => false]);
            }
        }
    }

    public function get_records()
    {   
        $request = service('request');
        $params = [
            'is_nmd' => $request->getPost('is_nmd'),
            'list_dt' => $request->getPost('list_dt'),
            'mainhead' => $request->getPost('mainhead'),
            'main_supp' => $request->getPost('main_supp'),
            'forFixedDate' => $request->getPost('forFixedDate'),
            'from_yr' => $request->getPost('from_yr'),
            'to_yr' => $request->getPost('to_yr'),
            'bench' => $request->getPost('bench'),
            'pool_adv' => $request->getPost('pool_adv'),
            'md_name' => $request->getPost('md_name'),
            'listing_purpose' => $request->getPost('listing_purpose'),
            'civil_criminal' => $request->getPost('civil_criminal'),
            'subhead' => $request->getPost('subhead'),
            'subject_cat' => $request->getPost('subject_cat'),
            'kword' => $request->getPost('kword'),
            'ia' => $request->getPost('ia'),
            'act' => $request->getPost('act'),
            'reg_unreg' => $request->getPost('reg_unreg'),
            'case_type' => $request->getPost('case_type'),
            'roster_judges_id' => $request->getPost('roster_judges_id'),
            'part_no' => $request->getPost('part_no'),
            'section' => $request->getPost('section')
        ];
        $data['cases'] = $this->CaseAdd->getPoolCases($params);
        $diaryNumbers = array_column($data['cases'], 'diary_no');
        $data['ropOrders'] = $this->CaseAdd->getRopOrders($diaryNumbers);
        if (!isset($data['cases'])) {
            $data['cases'] = [];
        }

        if (!isset($data['ropOrders'])) {
            $data['ropOrders'] = [];
        }
        $data['params'] = $params;
        $data['caseAddModel'] = $this->CaseAdd;

        return view('Listing/allocation/get_records', $data);
    }

    public function registrar_create_pool()
    {
        $request = \Config\Services::request();
        $data = [];
        
        if ($request->getMethod() === 'post' && $this->validate([
            'search_type' => ['label' => 'search Type', 'rules' => 'required|min_length[1]|max_length[1]']
        ])) {
            
            $search_type = $this->request->getPost('search_type');
            
            if ($search_type == 'D' && $this->validate([
                'diary_number' => ['label' => 'Diary Number', 'rules' => 'required|min_length[1]|max_length[8]'],
                'diary_year' => ['label' => 'Diary Year', 'rules' => 'required|min_length[4]'],
            ])) {
                $diary_number = $this->request->getPost('diary_number');
                $diary_year = $this->request->getPost('diary_year');
                $diary_no = $diary_number . $diary_year;
                $get_main_table = $this->Dropdown_list_model->get_diary_details_by_diary_no($diary_no);
            } elseif ($search_type == 'C' && $this->validate([
                'case_type' => ['label' => 'Case Type', 'rules' => 'required|min_length[1]|max_length[2]'],
                'case_number' => ['label' => 'Case Number', 'rules' => 'required|min_length[1]|max_length[8]'],
                'case_year' => ['label' => 'Case Year', 'rules' => 'required|min_length[4]'],
            ])) {
                $case_type = $this->request->getPost('case_type');
                $case_number = $this->request->getPost('case_number');
                $case_year = $this->request->getPost('case_year');
                
                $get_main_table = $this->Dropdown_list_model->get_case_details_by_case_no($case_type, $case_number, $case_year);
            } else {
                return $this->response->setJSON(['success' => 0, 'error' => 'Data not Found']);
            }

            if(empty($get_main_table)) {
                return $this->response->setJSON(['success' => 0, 'error' => 'Data not Found']);
            }
        }

        if (!empty($get_main_table)) {
            $this->session->set(array('filing_details' => $get_main_table));
            return $this->response->setJSON(['redirect' => base_url('Listing/Pool/registrar_create_pool_get')]);
        }

        $data['casetype'] = get_from_table_json('casetype');
        $data['sectionHeading'] = "Registrar In Chamber Pool";
        $data['formAction'] = 'Listing/pool/registrar_create_pool';

        return view('Listing/pool/registrar_create_pool', $data);
    }

    public function registrar_create_pool_get()
    {
        $filing_details = session()->get('filing_details');
        $dno = $filing_details['diary_no'];
        $results = $this->PoolModel->getCaseDetails($dno);
        if(empty($results)) {
            session()->setFlashdata("error", 'Record Not Available');
            return redirect()->to('Listing/UpdateHeardt/update_heardt');
        }

        $data['row_avl'] = $results;
        $data['getMainheadInfo'] = $this->PoolModel->getMainheadInfo($dno);
        $data['getStageNameInfo'] = $this->PoolModel->getStageNameInfo($dno);
        $data['getCategoryInfo'] = $this->PoolModel->getCategoryInfo($dno);
        $data['alreadyInPool'] =  $this->PoolModel->isAlreadyInPool($dno);        
        return view('Listing/pool/registrar_create_pool_get', $data);
    }

    public function cl()
    {
        $data=[];
        $request = \Config\Services::request();
        $diaryNumbers = $request->getPost('diaryNos');
        $diary_numbers_string_post = json_decode($diaryNumbers);
        $data['diary_numbers_string'] = implode(',', $diary_numbers_string_post);
        $data['res'] =  $this->PoolModel->getPoolCaseDetails($data['diary_numbers_string']);
        return view('Listing/pool/cl', $data);
    }
}