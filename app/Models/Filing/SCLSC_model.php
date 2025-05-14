<?php

namespace App\Models\Filing;

use CodeIgniter\Model;

class SCLSC_model extends Model
{
    public $e_services;
    public $sci_cmis_final_77;
    public $sci_cmis_final;
    public function __construct()
    {
        parent::__construct();
        $db = \Config\Database::connect();
        $this->e_services = \Config\Database::connect('eservices');
        // $this->sci_cmis_final = \Config\Database::connect('default');
        $this->sci_cmis_final_77 = \Config\Database::connect(); //'sci_cmis_final_77'
        $this->sci_cmis_final = \Config\Database::connect(); //'sci_cmis_final'
    }


    public function insertInDB($tablename, $data)
    {
        $builder = $this->sci_cmis_final->table($tablename);
        $builder->insert($data);

        return $this->e_services->affectedRows();
    }


    public function updateDiaryCounter($diaryNo)
    {
        $builder = $this->sci_cmis_final->table('master.cnt_diary_no');
        $builder->set('max_diary_no', $diaryNo);
        $builder->where('diary_no_year', date('Y'));
        $builder->update();
    }

    public function updateDocCounter($maxDockcount)
    {
        $builder = $this->sci_cmis_final->table('master.dockount');
        $builder->set('knt', $maxDockcount);
        $builder->where('year', date('Y'));
        $builder->update();
    }

    public function updateSCLSCDiaryFiled($diaryNo)
    {
        $builder = $this->e_services->table('sclsc_cases');
        $builder->set('is_filed_in_sci', 1);
        $builder->where('diary_no', $diaryNo);
        $builder->update();
    }

    public function updateSCLSCDocdetails($docd_id)
    {
        $builder = $this->e_services->table('sclsc_docdetails');
        $builder->set('is_filed_in_sci', 1);
        $builder->where('docd_id', $docd_id);
        $builder->update();
    }


    public function diaryGeneratedByAPIreport($status)
    {
        if ($status == 'P') {
            $sub_query = "and m.c_status = 'P'";
        } elseif ($status == 'D') {
            $sub_query = "and m.c_status = 'D'";
        } else {
            $sub_query = "";
        }

        $sql = "SELECT b.aor_code, b.title AS aor_title, b.name AS aor_name, sc.paperbook_url, m.*
            FROM e_filing.sclsc_cases sc 
            INNER JOIN sclsc_details sd ON cast(CONCAT(sd.sclsc_diary_no, sd.sclsc_diary_year) as BIGINT) = sc.diary_no
            INNER JOIN main m ON m.diary_no = sd.diary_no  
            INNER JOIN master.bar b ON b.aor_code = sc.aor_code
            WHERE 
            --sc.is_filed_in_sci = 1 AND 
            sd.display = 'Y'
            $sub_query
            ORDER BY m.diary_no_rec_date";

        $query = $this->sci_cmis_final_77->query($sql);
        return $query->getResultArray();
    }


    public function getUnFiledCases()
    {
        return $this->e_services->table('sclsc_cases')
            ->where('is_filed_in_sci', 0)
            ->get()
            ->getResultArray();
    }


    public function getUnFiledDocuments()
    {
        // Define the SQL query
        $sql = "SELECT d.docd_id, d.diary_no, d.document_name, m.pet_name, m.res_name, m.reg_no_display
            FROM sclsc_docdetails d 
            INNER JOIN main m ON m.diary_no = d.diary_no  
            WHERE d.is_filed_in_sci = 0 
            ORDER BY d.record_updated_at";

        // Execute the query
        $query = $this->sci_cmis_final_77->query($sql);

        // Fetch the results as an array
        return $query->getResultArray();
    }


    public function getSCLSCparty($diaryNo)
    {
        $builder = $this->e_services->table('sclsc_party');
        $query = $builder->where('diary_no', $diaryNo)
            ->get();

        return $query->getResultArray();
    }

    public function getUnFiledCaseDetails($diary_no)
    {
        $builder = $this->e_services->table('sclsc_cases');
        $builder->where('diary_no', $diary_no);
        $builder->where('is_filed_in_sci', 0);
        $query = $builder->get();

        return $query->getResultArray();
    }

    public function getCaseStageName($diary_no)
    {
        $sql = "SELECT stagename, h.mainhead FROM heardt h
            INNER JOIN subheading s ON h.subhead = s.stagecode
            WHERE h.diary_no = :diary_no:";

        $query = $this->sci_cmis_final_77->query($sql, ['diary_no' => $diary_no]);

        return $query->getResultArray();
    }

    public function getAgencyState($id)
    {
        $sql = "SELECT agency_state FROM ref_agency_state b WHERE b.cmis_state_id = :id:";
        $query = $this->sci_cmis_final_77->query($sql, ['id' => $id]);
        return $query->getResultArray();
    }


    public function getUnFiledDocumentsDetails($id)
    {
        // Define the SQL query with a placeholder for the docd_id
        $sql = "SELECT m.pet_name, m.res_name, m.reg_no_display, m.c_status, m.fil_dt, m.section_id, 
                   m.ref_agency_state_id, m.dacode, d.*
            FROM sclsc_docdetails d 
            INNER JOIN main m ON m.diary_no = d.diary_no  
            WHERE d.is_filed_in_sci = 0 AND d.docd_id = :id:";

        // Execute the query and bind the parameter
        $query = $this->sci_cmis_final_77->query($sql, ['id' => $id]);
        // Fetch and return the results as an array
        return $query->getResultArray();
    }

    public function getListingInFutureDates($diary_no)
    {
        $sql = "SELECT next_dt, board_type, clno, brd_slno, roster_id 
            FROM heardt 
            WHERE diary_no = :diary_no: 
            AND roster_id > 0 
            AND clno > 0 
            AND next_dt >= CURDATE()";
        $query = $this->sci_cmis_final_77->query($sql, ['diary_no' => $diary_no]);
        return $query->getResultArray();
    }

    public function getCaseListedCount($diary_no)
    {
        $sql = "SELECT COUNT(1) AS listed_count 
            FROM (
                SELECT h.* 
                FROM (
                    SELECT 
                        t1.diary_no,
                        t1.next_dt,
                        t1.roster_id,
                        t1.judges,
                        t1.mainhead,
                        t1.subhead,
                        t1.clno,
                        t1.brd_slno,
                        t1.main_supp_flag
                    FROM heardt t1
                    WHERE t1.diary_no = :diary_no:         
                        AND (t1.main_supp_flag = 1 OR t1.main_supp_flag = 2)
                    
                    UNION
                    
                    SELECT 
                        t2.diary_no,
                        t2.next_dt,
                        t2.roster_id,
                        t2.judges,
                        t2.mainhead,
                        t2.subhead,
                        t2.clno,
                        t2.brd_slno,
                        t2.main_supp_flag
                    FROM last_heardt t2
                    WHERE t2.diary_no = :diary_no:                   
                        AND (t2.main_supp_flag = 1 OR t2.main_supp_flag = 2)
                        AND (t2.bench_flag = '' OR t2.bench_flag IS NULL)
                ) h            
                INNER JOIN cl_printed p 
                    ON p.next_dt = h.next_dt 
                    AND p.part = h.clno 
                    AND p.roster_id = h.roster_id 
                    AND p.display = 'Y'
            ) a";

        $query = $this->sci_cmis_final_77->query($sql, ['diary_no' => $diary_no]);

        return $query->getResultArray();
    }

    public function getMaxDiaryNo()
    {
        // Define the query using the Query Builder
        $builder = $this->sci_cmis_final->table('master.cnt_diary_no');
        $builder->select('max_diary_no');
        $builder->where('diary_no_year', date('Y'));
        $builder->limit(1);

        $query = $builder->get();

        return $query->getRow()->max_diary_no ?? null;
    }

    public function getMaxDockount()
    {
        // Build the query
        $builder = $this->sci_cmis_final->table('dockount');
        $builder->select('knt as max_docdetails_id');
        $builder->where('year', date('Y'));
        $builder->limit(1);

        // Execute the query and return the result
        $query = $builder->get();
        $row = $query->getRow();
        return $row ? $row->max_docdetails_id : null;
    }


    public function getCaseType($caseType)
    {

        // Build the query
        $builder = $this->sci_cmis_final->table('master.casetype');
        $builder->select('short_description as caseType');
        $builder->where('casecode', $caseType);
        $builder->limit(1);

        // Execute the query
        $query = $builder->get();

        // Get the row and return the result
        $row = $query->getRow();
        return $row ? $row->caseType : null;
    }

    public function getAORdetail($aor_code)
    {
        // Build the query
        $builder = $this->sci_cmis_final_77->table('master.bar');
        $builder->where('aor_code', $aor_code);
        $builder->where('if_aor', 'Y');
        $builder->where('isdead', 'N');
        $builder->limit(1);
        // Execute the query
        $query = $builder->get();
        // Return the result as an array
        return $query->getResultArray();
    }

    public function getBARdetail($bar_id)
    {
        // Build the query
        $builder = $this->sci_cmis_final_77->table('master.bar');
        $builder->where('bar_id', $bar_id);
        $builder->where('if_aor', 'Y');
        $builder->where('isdead', 'N');
        $builder->limit(1);

        // Execute the query
        $query = $builder->get();

        // Return the result as an array
        return $query->getResultArray();
    }


    public function getFiledDocuments($doccode, $doccode1)
    {
        // Build the query
        $builder = $this->sci_cmis_final_77->table('docmaster');
        $builder->where('doccode', $doccode);
        $builder->where('doccode1', $doccode1);
        $builder->where('display', 'Y');
        $builder->limit(1);

        // Execute the query
        $query = $builder->get();

        // Return the result as an array
        return $query->getResultArray();
    }


    public function getAdvocate($diaryNo)
    {
        // Prepare the SQL query
        $sql = "SELECT pet_res, name AS adv, adv_type, aor_code FROM advocate a 
            LEFT JOIN master.bar b ON advocate_id = bar_id 
            WHERE diary_no = ? AND a.display = 'Y' 
            ORDER BY pet_res, adv_type DESC, a.ent_dt";

        // Execute the query with parameter binding
        $query = $this->sci_cmis_final_77->query($sql, [$diaryNo]);

        // Return the result as an array
        return $query->getResultArray();
    }


    public function getCaseNature($caseType)
    {

        $builder = $this->sci_cmis_final->table('master.casetype');
        $builder->select('nature');
        $builder->where('casecode', $caseType);
        $builder->limit(1);
        $query = $builder->get();

        $row = $query->getRowArray();
        return $row ? $row['nature'] : null;
    }

    public function getSectionName($section_id)
    {
        // Build the query
        $builder = $this->sci_cmis_final->table('master.usersection');
        $builder->select('section_name');
        $builder->where('id', $section_id);
        $builder->limit(1);
        // Execute the query
        $query = $builder->get();
        // Fetch the row and return the 'section_name' field
        $row = $query->getRow();
        return $row ? $row->section_name : null;
    }


    public function getParty($diaryNo)
    {
        // Prepare the SQL query with parameter binding
        $sql = "
        SELECT pet_res, sr_no, sr_no_show, ind_dep, partyname, pflag 
        FROM party 
        WHERE diary_no = ? AND pflag != 'T'
        ORDER BY pet_res, sr_no, INET_ATON(SUBSTRING_INDEX(CONCAT(sr_no_show, '.0.0'), '.', 3))
    ";
        // Execute the query with parameter binding
        $query = $this->sci_cmis_final->query($sql, [$diaryNo]);
        // Return the result as an array
        return $query->getResultArray();
    }


    /* public function getPartyDetails($diaryNo)
    {
        // pr(386);
        $builder = $this->e_services->table('sclsc_party p')
            ->select('
                p.sr_no_show, 
                p.pet_res, 
                p.ind_dep, 
                p.partyname, 
                p.sonof, 
                p.prfhname, 
                p.age, 
                p.sex, 
                p.caste, 
                p.addr1, 
                p.addr2, 
                p.pin, 
                p.state, 
                p.city, 
                p.email, 
                p.contact AS mobile, 
                p.deptcode, 
                (SELECT deptname FROM master.deptt WHERE deptcode = p.deptcode) AS deptname
            ')
            ->join('sclsc_cases m', 'm.diary_no = p.diary_no AND p.sr_no = 1 AND p.pflag = \'P\' AND p.pet_res IN (\'P\', \'R\')', 'inner')
            ->join('master.casetype c', 'c.casecode = m.diary_no', 'left')
            ->where('m.diary_no', $diaryNo)
            ->orderBy('p.pet_res')
            ->orderBy('SUBSTRING(p.sr_no_show FROM 1 FOR 3)'); // Add the closing parenthesis here
    
        // Get the results
        $query = $builder->get();
        return $query->getResultArray();
    }
    */
    public function getPartyDetails($diaryNo)
    {

        $partyData = $this->e_services->table('sclsc_party p')
            ->select('
                p.sr_no_show, 
                p.pet_res, 
                p.ind_dep, 
                p.partyname, 
                p.sonof, 
                p.prfhname, 
                p.age, 
                p.sex, 
                p.caste, 
                p.addr1, 
                p.addr2, 
                p.pin, 
                p.state, 
                p.city, 
                p.email, 
                p.contact AS mobile, 
                p.deptcode
            ')
            ->join('sclsc_cases m', 'm.diary_no = p.diary_no AND p.sr_no = 1 AND p.pflag = \'P\' AND p.pet_res IN (\'P\', \'R\')', 'inner')
            ->where('m.diary_no', $diaryNo)
            ->orderBy('p.pet_res')
            ->orderBy('SUBSTRING(p.sr_no_show FROM 1 FOR 3)')
            ->get()
            ->getResultArray();

        // Fetch data from the master.deptt table in the sci_cmis_final database
        $deptData = $this->sci_cmis_final->table('master.deptt')
            ->select('deptcode, deptname')
            ->get()
            ->getResultArray();

        // Fetch data from the master.casetype table in the sci_cmis_final database
        $caseTypeData = $this->sci_cmis_final->table('master.casetype')
            ->select('casecode')
            ->where('casecode', $diaryNo) // Adjust condition if necessary
            ->get()
            ->getResultArray();

        // Re-index deptData by deptcode for faster lookup
        $deptIndex = [];
        foreach ($deptData as $dept) {
            $deptIndex[$dept['deptcode']] = $dept['deptname'];
        }

        // Add deptname and casecode to the partyData
        foreach ($partyData as &$party) {
            $party['deptname'] = $deptIndex[$party['deptcode']] ?? null;
            $party['casecode'] = isset($caseTypeData[0]['casecode']) ? $caseTypeData[0]['casecode'] : null;
        }

        return $partyData;
    }

    public function getSubjectCategory($diaryNo)
    {
        // Prepare the SQL query with parameter binding
        $sql = "SELECT b.* 
                FROM mul_category a 
                INNER JOIN submaster b ON a.submaster_id = b.id 
                WHERE a.diary_no = ? AND a.display = 'Y' AND b.display = 'Y' 
                LIMIT 1";
        // Execute the query with parameter binding
        $query = $this->sci_cmis_final_77->query($sql, [$diaryNo]);
        // Return the result as an array
        return $query->getResultArray();
    }

    public function getNameOfPlace($id)
    {
        // Build the query
        $builder = $this->sci_cmis_final->table('master.state');
        $builder->select('name');
        $builder->where('id_no', $id);
        $builder->limit(1);

        // pr($builder->getCompiledSelect());
        // Execute the query
        $query = $builder->get();

        // Fetch the row and return the 'Name' field
        $row = $query->getRowArray()['name']??'';

        return $row;
    }


    public function getFromCourt($id)
    {
        // Build the query
        $builder = $this->sci_cmis_final_77->table('master.m_from_court');
        $builder->select('court_name');
        $builder->where('id', $id);
        $builder->limit(1);
        // Execute the query
        $query = $builder->get();
        // Fetch the row and return the 'court_name' field
        $row = $query->getRow();
        return $row ? $row->court_name : null;
    }


    public function dispatch_ld($dacode, $diary_no, $doccode, $doccode1, $maxDockcount, $year, $icmis_user_code)
    {
        // Build the query to check if the record exists
        $builder = $this->sci_cmis_final->table('ld_move');
        $builder->where('diary_no', $diary_no);
        $builder->where('doccode', $doccode);
        $builder->where('doccode1', $doccode1);
        $builder->where('docnum', $maxDockcount);
        $builder->where('docyear', $year);
        $query = $builder->get();
        // Check if the record exists
        if ($query->getNumRows() == 0) {
            // Prepare the data for insertion
            $data = [
                'diary_no' => $diary_no,
                'doccode' => $doccode,
                'doccode1' => $doccode1,
                'docnum' => $maxDockcount,
                'docyear' => $year,
                'disp_by' => $icmis_user_code,
                'disp_to' => $dacode,
                'disp_dt' => date('Y-m-d H:i:s')  // Current date and time
            ];
            // Insert the record
            $builder->insert($data);
        }
    }

    public function getBenchName($from_court, $ref_agency_code_id)
    {
        // Initialize the query variable
        $sql = '';

        if ($from_court == 3) {
            if ($ref_agency_code_id == '490506') {
                $sql = "SELECT id_no AS id, court_name AS agency_name, Name AS districtname
                    FROM master.state s
                    JOIN master.delhi_district_court d ON s.state_code = d.state_code AND s.district_code = d.district_code
                    WHERE s.state_code = (SELECT state_code FROM master.state WHERE id_no = '$ref_agency_code_id' AND display = 'Y')
                      AND s.display = 'Y'
                      AND s.sub_dist_code = 0
                      AND s.village_code = 0
                      AND s.district_code != 0
                    ORDER BY TRIM(Name)";
            } else {
                $sql = "SELECT id_no AS id, name AS agency_name
                    FROM master.state
                    WHERE state_code = (SELECT State_code FROM master.state WHERE id_no = '$ref_agency_code_id' AND display = 'Y')
                      AND display = 'Y'
                      AND sub_dist_code = 0
                      AND village_code = 0
                      AND district_code != 0
                    ORDER BY TRIM(name)";
            }
        } elseif ($from_court == '1') {
            $sql = "SELECT id, agency_name, short_agency_name
                FROM master.ref_agency_code
                WHERE is_deleted = 'f'
                  AND agency_or_court = '1'
                  AND id = '$ref_agency_code_id'";
        } elseif ($from_court == '4') {
            $sql = "SELECT id, agency_name, short_agency_name
                FROM master.ref_agency_code
                WHERE is_deleted = 'f'
                  AND agency_or_court = '$from_court'
                  AND id = '$ref_agency_code_id'";
        } elseif ($from_court == '5') {
            $sql = "SELECT id, agency_name, short_agency_name
                FROM master.ref_agency_code
                WHERE is_deleted = 'f'
                  AND agency_or_court IN ('2', '5', '6')
                  AND id = '$ref_agency_code_id'";
        }


        // Execute the query and return the result
        $query = $this->sci_cmis_final->query($sql);
        return $query->getResultArray();
    }


    // public function getBenchName($from_court, $ref_agency_code_id)
    // {
    //     // Initialize the query builder
    //     $builder = $this->sci_cmis_final->table('');

    //     // Build the query based on the value of $from_court
    //     if ($from_court == 3) {
    //         if ($ref_agency_code_id == '490506') {
    //             $builder->select('s.id_no AS id, s.court_name AS agency_name, s.name AS districtname');
    //             $builder->from('master.state s');
    //             $builder->join('delhi_district_court d', 's.state_code = d.state_code AND s.district_code = d.district_code');
    //             $builder->where('s.state_code', "(SELECT state_code FROM state WHERE id_no = '$ref_agency_code_id' AND display = 'Y')", false);
    //             $builder->where('s.display', 'Y');
    //             $builder->where('s.sub_dist_code', 0);
    //             $builder->where('s.village_code', 0);
    //             $builder->where('s.district_code !=', 0);
    //             $builder->orderBy('TRIM(s.name)');
    //         } else {
    //             $builder->select('id_no AS id, name AS agency_name');
    //             $builder->from('master.state');
    //             $builder->where('state_code', "(SELECT state_code FROM state WHERE id_no = '$ref_agency_code_id' AND display = 'Y')", false);
    //             $builder->where('display', 'Y');
    //             $builder->where('sub_dist_code', 0);
    //             $builder->where('village_code', 0);
    //             $builder->where('district_code !=', 0);
    //             $builder->orderBy('TRIM(name)');
    //         }
    //     } elseif ($from_court == '1') {
    //         $builder->select('id, agency_name, short_agency_name');
    //         $builder->from('ref_agency_code');
    //         $builder->where('is_deleted', 'f');
    //         $builder->where('agency_or_court', 1);
    //         $builder->where('id', $ref_agency_code_id);
    //     } elseif ($from_court == '4') {
    //         $builder->select('id, agency_name, short_agency_name');
    //         $builder->from('ref_agency_code');
    //         $builder->where('is_deleted', 'f');
    //         $builder->where('agency_or_court', $from_court);
    //         $builder->where('id', $ref_agency_code_id);
    //     } elseif ($from_court == '5') {
    //         $builder->select('id, agency_name, short_agency_name');
    //         $builder->from('ref_agency_code');
    //         $builder->where('is_deleted', 'f');
    //         $builder->whereIn('agency_or_court', [2, 5, 6]);
    //         $builder->where('id', $ref_agency_code_id);
    //     }

    //     // Execute the query and return the result as an array
    //     $query = $builder->get();
    //     return $query->getResultArray();
    // }


    public function getSectionId($casecode, $bench)
    {
        // Initialize the query builder
        $builder = $this->sci_cmis_final_77->table('master.agency_master');
        $builder->select('section_code');
        $builder->where('case_type', $casecode);

        // Build query using LIKE with wildcards
        $builder->where("id LIKE '%$bench%'");
        $builder->limit(1);

        // Execute the query and fetch the result
        $query = $builder->get();
        $row = $query->getRow();

        // Return section_code or null if no result is found
        return $row ? $row->section_code : null;
    }



    public function getSection($courtType, $bench, $casecode)
    {
        // Default section_id
        $section_id = 0;

        // Set default bench value if empty
        if (empty($bench)) {
            $bench = 10000;
        }

        // Initialize tribunal variable
        $tribunal = "";

        // Conditional logic to determine section_id
        if ($courtType == 5 && in_array($casecode, [2, 4])) {
            $section_id = $this->getSectionId($casecode, $bench);
        } elseif ($courtType == 5 && !in_array($casecode, [2, 4])) {
            $section_id = ($tribunal == 5) ? 82 : 52;
        } elseif (in_array($casecode, [32, 33, 34, 35, 40, 41])) {
            $section_id = 52;
        } elseif (in_array($casecode, [17, 18, 21, 22, 27, 36, 37, 38])) {
            $section_id = 82;
        } elseif (in_array($casecode, [7, 8, 27])) {
            $section_id = 51;
        } elseif (in_array($casecode, [5, 6])) {
            $section_id = 42;
        } else {
            $section_id = $this->getSectionId($casecode, $bench);
        }

        return $section_id;
    }




    public function check_fil_trap_sequence()
    {
        // Initialize the query builder
        $builder = $this->sci_cmis_final->table('fil_trap_users a');
        $builder->select('a.usercode as to_usercode, b.name as to_name, empid as to_userno, ddate, c.no as curno');
        $builder->join('master.users b', 'a.usercode = b.usercode');
        $builder->join('fil_trap_seq c', 'c.no < empid', 'left');
        $builder->where('a.usertype', 102);
        $builder->where('a.display', 'Y');
        $builder->where('b.display', 'Y');
        $builder->where('utype', 'DE');
        $builder->where('ddate', date('Y-m-d'));
        $builder->where('attend', 'P');
        $builder->orderBy('empid');

        // Execute the query and return the result
        $query = $builder->get();
        return $query->getRowArray();
    }

    public function allot_to_EC($fil_no, $ucode)
    {
        $check_ava_row = [
            'to_userno' => null,
            'to_name' => null
        ];

        // Get the available user
        $builder = $this->sci_cmis_final->table('fil_trap_users a');
        $builder->select('a.usercode, b.name, empid');
        $builder->join('master.users b', 'a.usercode = b.usercode');
        $builder->where('a.usertype', 102);
        $builder->where('a.display', 'Y');
        $builder->where('b.display', 'Y');
        $builder->where('attend', 'P');
        $builder->orderBy('empid');
        $query = $builder->get();
        $result = $query->getResultArray();
        if (!empty($result)) {

            $first_row = $result[0];

            $sequence_query = $this->check_fil_trap_sequence();
            // $sequence_row = $sequence_query->getRow();
            //pr($first_row);
            if (!empty($sequence_query) && $sequence_query['to_usercode'] != NULL) {
                $check_ava_row['to_userno'] = $sequence_query['to_userno'];
                $check_ava_row['to_name'] = $sequence_query['to_name'];
            } else {               
                $check_ava_row['to_userno'] = $first_row['empid'];
                $check_ava_row['to_name'] = $first_row['name'];
            }
        }

        // Delete if found in fil_trap or fil_trap_his
        $builder = $this->sci_cmis_final->table('fil_trap');
        $builder->where('diary_no', $fil_no);
        $builder->delete();

        $builder = $this->sci_cmis_final->table('fil_trap_his');
        $builder->where('diary_no', $fil_no);
        $builder->delete();

        // Insert into fil_trap
        $data_fil_trap = [
            'diary_no' => $fil_no,
            'd_to_empid' => $check_ava_row['to_userno'],
            'disp_dt' => date('Y-m-d H:i:s'),
            'd_by_empid' => $ucode,
            'remarks' => 'FIL -> DE'
        ];
        $this->sci_cmis_final->table('fil_trap')->insert($data_fil_trap);

        // Check if exists in fil_trap_seq
        $builder = $this->sci_cmis_final->table('fil_trap_seq');
        $builder->select('id');
        $builder->where('ddate', date('Y-m-d'));
        $builder->where('utype', 'DE');
        $query = $builder->get();

        if (!empty($query->getRowArray())) {
            $data_fil_trap_seq = [
                'ddate' => date('Y-m-d'),
                'utype' => 'DE',
                'no' => $check_ava_row['to_userno']
            ];
            $this->sci_cmis_final->table('fil_trap_seq')->insert($data_fil_trap_seq);
        } else {
            $builder = $this->sci_cmis_final->table('fil_trap_seq');
            $builder->set('no', $check_ava_row['to_userno']);
            $builder->where('ddate', date('Y-m-d'));
            $builder->where('utype', 'DE');
            $builder->update();
        }

        return $check_ava_row['to_userno'] . '~' . $check_ava_row['to_name'];
    }


    public function batchInsertInDB(string $tablename, array $data): int
    {
        // Perform batch insert
        $builder = $this->sci_cmis_final->table($tablename);
        $builder->insertBatch($data);

        // Return the number of affected rows
        return $this->sci_cmis_final->affectedRows();
    }

    /* public function get_sclsc_refiling_documents($fromDate, $toDate, $session_r)
    {
        $sql = "SELECT concat(substr(cast(m.diary_no as text), 1, length(cast(m.diary_no as text)) - 4), '/', substr(cast(m.diary_no as text), -4)) as diary_no,
                       m.pet_name,
                       m.res_name,
                       to_char(filed_on, 'DD-MM-YYYY HH12:MI:SS AM') as filed_on,
                       to_char(m.diary_no_rec_date, 'DD-MM-YYYY HH12:MI:SS AM') as diary_on,
                       s.aor_code,
                       b.name,
                       paperbook_url,
                       total_pages,
                       sclsc_id
                FROM sclsc_filed_document s
                JOIN main m ON s.diary_no = m.diary_no
                JOIN master.bar b ON s.aor_code = b.aor_code
                WHERE filing_type = 1 
                  AND date(filed_on) BETWEEN :fromDate: AND :toDate:
                ORDER BY filed_on";

        $query = $this->e_services->query($sql, [
            'fromDate' => $fromDate,
            'toDate' => $toDate,
        ]);
        return $query->getResultArray();
    }
*/

    public function get_sclsc_refiling_documents($fromDate, $toDate, $session_r)
    {
        // Step 1: Fetch data from sclsc_filed_document in e_services
        $sclscDocuments = $this->e_services->table('sclsc_filed_document s')
            ->select('
            s.diary_no,
            s.aor_code,
            s.paperbook_url,
            s.total_pages,
            s.sclsc_id,
            to_char(filed_on, \'DD-MM-YYYY HH12:MI:SS AM\') as filed_on
        ')
            ->where('filing_type', 1)
            ->where("date(filed_on) BETWEEN '$fromDate' AND '$toDate'")
            ->orderBy('filed_on')
            ->get()
            ->getResultArray();

        // Step 2: Fetch data from main and master.bar in sci_cmis_final
        $diaryNos = array_column($sclscDocuments, 'diary_no');
        if (empty($diaryNos)) {
            return []; // Return early if no data
        }

        $mainData = $this->sci_cmis_final->table('main m')
            ->select("
            m.diary_no,
            m.pet_name,
            m.res_name,
            to_char(m.diary_no_rec_date, 'DD-MM-YYYY HH12:MI:SS AM') as diary_on
        ")
            ->whereIn('m.diary_no', $diaryNos)
            ->get();
pr($this->sci_cmis_final->getLastQuery());
            //->getResultArray();
        
        $barData = $this->sci_cmis_final->table('master.bar b')
            ->select('b.aor_code, b.name')
            ->whereIn('b.aor_code', array_column($sclscDocuments, 'aor_code'))
            ->get()
            ->getResultArray();

        // Step 3: Re-index data for faster lookup
        $mainIndex = [];
        foreach ($mainData as $main) {
            $mainIndex[$main['diary_no']] = $main;
        }

        $barIndex = [];
        foreach ($barData as $bar) {
            $barIndex[$bar['aor_code']] = $bar['name'];
        }

        // Step 4: Combine data programmatically
        foreach ($sclscDocuments as &$document) {
            $diaryNo = $document['diary_no'];
            $aorCode = $document['aor_code'];

            $document['diary_no'] = isset($mainIndex[$diaryNo])
                ? $this->concatDiaryNumber($mainIndex[$diaryNo]['diary_no'])
                : $diaryNo;

            $document['pet_name'] = $mainIndex[$diaryNo]['pet_name'] ?? null;
            $document['res_name'] = $mainIndex[$diaryNo]['res_name'] ?? null;
            $document['diary_on'] = $mainIndex[$diaryNo]['diary_on'] ?? null;
            $document['name'] = $barIndex[$aorCode] ?? null;
        }

        return $sclscDocuments;
    }

    // Helper function for formatting diary numbers
    private function concatDiaryNumber($diaryNo)
    {
        return substr($diaryNo, 0, -4) . '/' . substr($diaryNo, -4);
    }


    /*public function get_sclsc_documents($sclsc_id)
    {
        // pr(794);
        $builder = $this->e_services->table('sclsc_docdetails s');
        $builder->select("concat(substr(m.diary_no,1,length(m.diary_no)-4),'/', substr(m.diary_no,-4)) as diary_no,
                          date_format(s.ent_dt, '%d-%m-%Y %h:%i:%s %p') as filing_date,
                          date_format(m.diary_no_rec_date, '%d-%m-%Y %h:%i:%s %p') as diary_on,
                          m.pet_name, m.res_name, c.casename, s.ent_dt, s.advocate_id, b.name,
                          document_name, paperbook_url, total_pages, s.sclsc_id");
        $builder->join('main m', 's.diary_no=m.diary_no');
        $builder->join('master.bar b', 's.advocate_id=b.aor_code');
        $builder->join('master.casetype c', 'm.casetype_id=c.casecode');
        $builder->where('s.sclsc_id', $sclsc_id);

        $query = $builder->get();
        return $query->getResultArray();
    }
        */
    public function get_sclsc_documents($sclsc_id)
    {
        // Step 1: Fetch data from sclsc_docdetails in e_services
        $docDetails = $this->e_services->table('sclsc_docdetails s')
            ->select('
            s.diary_no,
            s.ent_dt,
            s.advocate_id,
            s.document_name,
            s.paperbook_url,
            s.total_pages,
            s.sclsc_id
        ')
            ->where('s.sclsc_id', $sclsc_id)
            ->get()
            ->getResultArray();

        // Step 2: Fetch data from main, master.bar, and master.casetype in sci_cmis_final
        $diaryNos = array_column($docDetails, 'diary_no');
        $advocateIds = array_column($docDetails, 'advocate_id');

        if (empty($diaryNos) || empty($advocateIds)) {
            return []; // Return early if no data
        }

        $mainData = $this->sci_cmis_final->table('main m')
            ->select('
            m.diary_no,
            m.pet_name,
            m.res_name,
            to_char(m.diary_no_rec_date, \'DD-MM-YYYY HH12:MI:SS AM\') as diary_on,
            m.casetype_id
        ')
            ->whereIn('m.diary_no', $diaryNos)
            ->get()
            ->getResultArray();

        $barData = $this->sci_cmis_final->table('master.bar b')
            ->select('b.aor_code, b.name')
            ->whereIn('b.aor_code', $advocateIds)
            ->get()
            ->getResultArray();

        $caseTypeData = $this->sci_cmis_final->table('master.casetype c')
            ->select('c.casecode, c.casename')
            ->whereIn('c.casecode', array_column($mainData, 'casetype_id'))
            ->get()
            ->getResultArray();

        // Step 3: Re-index data for faster lookup
        $mainIndex = [];
        foreach ($mainData as $main) {
            $mainIndex[$main['diary_no']] = $main;
        }

        $barIndex = [];
        foreach ($barData as $bar) {
            $barIndex[$bar['aor_code']] = $bar['name'];
        }

        $caseTypeIndex = [];
        foreach ($caseTypeData as $caseType) {
            $caseTypeIndex[$caseType['casecode']] = $caseType['casename'];
        }

        // Step 4: Combine data programmatically
        foreach ($docDetails as &$doc) {
            $diaryNo = $doc['diary_no'];
            $advocateId = $doc['advocate_id'];

            $doc['diary_no'] = isset($mainIndex[$diaryNo])
                ? $this->concatDiaryNumber($mainIndex[$diaryNo]['diary_no'])
                : $diaryNo;

            $doc['filing_date'] = date('d-m-Y h:i:s A', strtotime($doc['ent_dt']));
            $doc['diary_on'] = $mainIndex[$diaryNo]['diary_on'] ?? null;
            $doc['pet_name'] = $mainIndex[$diaryNo]['pet_name'] ?? null;
            $doc['res_name'] = $mainIndex[$diaryNo]['res_name'] ?? null;
            $doc['casename'] = isset($mainIndex[$diaryNo])
                ? ($caseTypeIndex[$mainIndex[$diaryNo]['casetype_id']] ?? null)
                : null;
            $doc['name'] = $barIndex[$advocateId] ?? null;
        }

        return $docDetails;
    }

    // Helper function for formatting diary numbers
   
}
