<?php

namespace App\Models\Judicial;

use CodeIgniter\Model;

class RegistrationModel extends Model
{
    public function __construct()
    {
        parent::__construct();
        $this->db = db_connect();
    }

    function getBfNbf($tfil_no)
    {
        $builder = $this->db->table('not_before a');
        $builder->select('a.diary_no, STRING_AGG(b.jname, \', \') AS jn, a.notbef');
        $builder->join('master.judge b', 'b.jcode = a.j1'); // OR b.jcode = a.j2 OR b.jcode = a.j3 OR b.jcode = a.j4 OR b.jcode = a.j5
        $builder->where('a.diary_no', $tfil_no);
        $builder->groupBy('a.diary_no, a.notbef');

        $t_nb = $builder->get();

        $pr_bf = $nbf = $bf = "";

        if ($t_nb->getNumRows() > 0) {
            foreach ($t_nb->getResultArray() as $rownb) {
                $t_jn = $rownb["jn"];
                $t_jn1 = stripslashes($t_jn);
                if ($rownb["notbef"] == "B")
                    if ($bf == "")
                        $bf .= $t_jn1;
                    else
                        $bf .= ",  " . $t_jn1;
                if ($rownb["notbef"] == "N")
                    if ($nbf == "")
                        $nbf .= $t_jn1;
                    else
                        $nbf .= ",  " . $t_jn1;
            }
        }

        return $bf . "^|^" . $nbf;
    }

    function getConnectedCases($dairy_no = 0)
    {
        // Main Query
        $builder = $this->db->table('main m');
        $builder->select([
            'm.active_casetype_id',
            'm.case_grp',
            'm.diary_no',
            'cc.list',
            'm.pet_name',
            'm.res_name',
            'm.c_status',
            'cc.conn_type',
            "CASE WHEN (m.diary_no::text = m.conn_key OR m.conn_key = '' OR m.conn_key IS NULL) THEN 'M' ELSE 'C' END AS ct",
            "COALESCE(NULLIF(reg_year_mh, 0), EXTRACT(YEAR FROM m.fil_dt)) AS m_year",
            "COALESCE(NULLIF(reg_year_fh, 0), EXTRACT(YEAR FROM m.fil_dt_fh)) AS f_year",
            'fil_no',
            'fil_no_fh',
            "TO_CHAR(m.fil_dt, 'DD-MM-YYYY HH12:MI AM') AS fil_dt_f",
            "TO_CHAR(m.fil_dt_fh, 'DD-MM-YYYY HH12:MI AM') AS fil_dt_fh",
            "CASE WHEN m.fil_no != '' THEN SPLIT_PART(m.fil_no, '-', 1) ELSE '' END AS ct1",
            "CASE WHEN m.fil_no != '' THEN SPLIT_PART(m.fil_no, '-', 2) ELSE '' END AS crf1",
            "CASE WHEN m.fil_no != '' THEN reverse(split_part(reverse(m.fil_no), '-'::text, 1)) ELSE '' END AS crl1",
            "CASE WHEN m.fil_no_fh != '' THEN SPLIT_PART(m.fil_no_fh, '-', 1) ELSE '' END AS ct2",
            "CASE WHEN m.fil_no_fh != '' THEN SPLIT_PART(m.fil_no_fh, '-', 2) ELSE '' END AS crf2",
            "CASE WHEN m.fil_no_fh != '' THEN reverse(split_part(reverse(m.fil_no_fh), '-'::text, 1)) ELSE '' END AS crl2",
            'm.casetype_id AS ctid'
        ]);

        $builder->join('conct cc', 'cc.diary_no = m.diary_no', 'left');
        $builder->where("m.conn_key = $dairy_no::text OR m.diary_no = $dairy_no");
        $builder->orderBy("CASE WHEN m.diary_no::text = m.conn_key THEN 'M' ELSE 'C' END DESC, m.fil_dt");

        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        }

        return false;
    }

    function getCaseListing($dairy_no = 0)
    {
        $builder = $this->db->table('heardt a');
        $builder->select("TO_CHAR(a.next_dt, 'DD-MM-YYYY') AS next_dt, roster_id AS judgename1");
        $builder->where('a.diary_no', $dairy_no);
        $builder->where('a.next_dt >=', date('Y-m-d')); // Using PHP to get the current date
        $builder->whereIn('a.main_supp_flag', [1, 2]);

        $results_listed_after = $builder->get();

        if ($results_listed_after->getNumRows() > 0) {
            return $results_listed_after->getResultArray();
        }

        return false;
    }

    /**
     * Get the case details
     */
    function getRoaster($dairy_no = 0)
    {
        // Prepare the query using Query Builder
        $builder = $this->db->table('heardt');
        $builder->select('next_dt');
        $builder->where('next_dt >= NOW()');
        $builder->where('clno !=', '0')->where('clno IS NOT NULL');
        $builder->where('brd_slno !=', '0')->where('brd_slno IS NOT NULL');
        $builder->where('main_supp_flag !=', '3');
        $builder->where('roster_id !=', '0')->where('roster_id IS NOT NULL');
        $builder->where('diary_no', $dairy_no);

        // Execute the query
        $query_roster = $builder->get();

        // Check if any rows were returned
        if ($query_roster->getNumRows() > 0) {
            return $query_roster->getRowArray();
        }

        return false;
    }

    function checkCaseRegisteredOrNot($dairy_no = 0)
    {
        $builder = $this->db->table('main');

        $builder->select("reg_no_display, 
                        CASE WHEN fil_no IS NULL OR fil_no = '' THEN 'Misc NF' ELSE 'Misc F' END AS mfilno, 
                        CASE WHEN fil_no_fh IS NULL OR fil_no_fh = '' THEN 'Reg NF' ELSE 'Reg F' END AS rfilno");
        $builder->where('diary_no', $dairy_no);

        $query = $builder->get();

        // Check if any rows were returned
        if ($query->getNumRows() > 0) {
            return $query->getRowArray();
        }

        return false;
    }

    function updateMain($data = [])
    {
        $builder = $this->db->table('main');

        // Prepare data to be updated
        $update_data = [
            'active_casetype_id' => $data['active_casetype_id'],
            'case_grp' => $data['case_grp'],
            'fil_no_fh' => $data['fil_no_fh'],
            'active_fil_no' => $data['active_fil_no'],
            'fil_dt_fh' => $data['fil_dt_fh'],
            'active_fil_dt' => $data['active_fil_dt'],
            'reg_year_fh' => $data['reg_year_fh'],
            'active_reg_year' => $data['active_reg_year'],
            'reg_no_display' => $data['reg_no_display'],
            'mf_active' => $data['mf_active'],
        ];

        // Update the record where diary_no matches
        $builder->set($update_data);
        $builder->where('diary_no', $data['diary_no']);

        return $builder->update();
    }

    /**
     * Get the case details
     */
    function getCase($dairy_no = 0)
    {
        // Prepare the query using Query Builder
        $builder = $this->db->table('main');
        $builder->select('c_status, active_casetype_id,ref_agency_state_id,ref_agency_code_id, active_fil_no, active_fil_dt, fil_no, fil_dt, fil_no_fh, fil_dt_fh, pet_name, res_name');
        $builder->where('diary_no', $dairy_no);

        // Execute the query
        $query = $builder->get();

        // Check if any rows were returned
        if ($query->getNumRows() > 0) {
            return $query->getRowArray();
        }

        return false;
    }

    function getMaxRegNo($data = [])
    {
        $builder = $this->db->table('main');
        $builder->select('active_fil_no');
        $builder->where('active_casetype_id', $data['casetype_id']);

        if (isset($data['filter_year']) && !empty($data['filter_year'])) {
            $builder->where('EXTRACT(YEAR FROM fil_dt)', $data['year']);
        }

        if (isset($data['year']) && !empty($data['year'])) {
            $builder->where('active_reg_year', $data['year']);
        }

        $builder->orderBy('active_fil_no', 'DESC');
        $builder->limit(1);

        $query = $builder->get();

        // Check if any rows were returned
        if ($query->getNumRows() > 0) {
            return $query->getRow()->active_fil_no;
        }

        return 0;
    }

    function getCaseCount($data = [])
    {
        $builder = $this->db->table('main');
        $builder->select('COUNT(diary_no) AS cnt');
        $builder->where("active_fil_no ILIKE '%" . $data['reg_no'] . "%'", null, false);
         // ILIKE for case-insensitive LIKE
        //$builder->like('active_fil_no', "'".$data['reg_no']."'", 'both', false); // 'both' for % on both sides, false to prevent automatic escaping
        $builder->where('active_casetype_id', $data['casetype_id']);
        $builder->where('EXTRACT(YEAR FROM active_fil_dt)', $data['year']);
        ///echo $builder->getCompiledSelect();die;
        $query = $builder->get();

        // Check if any rows were returned
        if ($query->getNumRows() > 0) {
            return $query->getRow()->cnt;
        }

        return 0;
    }

    function getKCounter($data = [])
    {
        $builder = $this->db->table('master.kounter');

        $builder->select('knt');
        $builder->where('casetype_id', $data['casetype_id']);
        $builder->where('year', $data['year']);

        $query = $builder->get();

        // Check if any rows were returned
        if ($query->getNumRows() > 0) {
            return $query->getRow()->knt;
        }

        return 0;
    }

    function addRegistrationTrack($data = [])
    {
        $builder = $this->db->table('registration_track');

        // Prepare data for insertion
        $add_data = [
            'diary_no' => $data['diary_no'],
            'registration_number_alloted' => $data['registration_number_alloted'],
            'registration_year' => $data['registration_year'],
            'usercode' => $data['usercode'],
            'reg_date' => $data['reg_date'],
        ];

        // $result = $builder->insert($add_data)
        // if ($result === false) {
        //     // Handle error
        //     // Error RegistrationModel->addKCounter
        // }

        return $builder->insert($add_data);
    }

    function addCaseHistory($data = [])
    {
        $builder = $this->db->table('main_casetype_history');

        // Prepare data for insertion
        $add_data = [
            'diary_no' => $data['diary_no'],
            'old_registration_number' => $data['old_registration_number'],
            'old_registration_year' => $data['old_registration_year'],
            'new_registration_number' => $data['new_registration_number'],
            'new_registration_year' => $data['new_registration_year'],
            'order_date' => $data['order_date'],
            'ref_old_case_type_id' => $data['ref_old_case_type_id'],
            'ref_new_case_type_id' => $data['ref_new_case_type_id'],
            'adm_updated_by' => $data['adm_updated_by'],
            'updated_on' => $data['updated_on'],
            'is_deleted' => $data['is_deleted'],
            'ec_case_id' => $data['ec_case_id'],
        ];
        // $sql = $builder->set($add_data)->getCompiledInsert();        
        // echo $sql;die;
        return $builder->insert($add_data);
    }

    function getCaseHistory($dairy_no = 0)
    {
        $builder = $this->db->table('main_casetype_history');

        $builder->select('new_registration_number, new_registration_year')
            ->select("CASE 
                            WHEN new_registration_number != '' AND new_registration_number IS NOT NULL 
                            THEN SUBSTRING(new_registration_number FROM 1 FOR POSITION('-' IN new_registration_number) - 1) 
                            ELSE '' 
                        END AS ct1")
            ->where('diary_no', $dairy_no)
            ->orderBy('order_date', 'DESC')
            ->limit(1);

            // echo $builder->getCompiledSelect();die;

        $query = $builder->get();


        // Check if any rows were returned
        if ($query->getNumRows() > 0) {
            return $query->getRowArray();
        }

        return [];
    }

    function updateMainTrack($data = []) 
    {
        $builder = $this->db->table('main');
        
        $update_data = [
            'dacode'        => $data['dacode'],
            //'last_usercode' => session()->get('dcmis_user_idd'),
            'last_usercode' => session()->get('login')['usercode'],
            'last_dt'      => date('Y-m-d H:i:s') // Current timestamp
        ];
        
        $builder->set($update_data)
                ->where('diary_no', $data['diary_no'])
                ->update();        
    }

    function updateKCounter($data = [])
    {
        $builder = $this->db->table('master.kounter');

        $update_data = [
            'knt' => $data['knt']
        ];

        $builder->set($update_data);
        $builder->where('casetype_id', $data['casetype_id']);
        $builder->where('year', $data['year']);

        // $result = $builder->update($add_data)
        // if ($result === false) {
        //     // Handle error
        //     // Error RegistrationModel->addKCounter
        // }

        return $builder->update();
    }

    function addKCounter($data = [])
    {
        $builder = $this->db->table('master.kounter');

        $add_data = [
            'year' => $data['year'],
            'knt' => $data['knt'],
            'casetype_id' => $data['casetype_id']
        ];

        return $builder->insert($add_data);
    }

    function getLowerCourtCount($dairy_no = 0)
    {
        $builder = $this->db->table('lowerct');

        $builder->select('COUNT(diary_no) AS cnt');
        $builder->where('diary_no', $dairy_no);
        $builder->where('lw_display', 'Y');
        $builder->where('is_order_challenged', 'Y');

        $query = $builder->get();

        // Check if any rows were returned
        if ($query->getNumRows() > 0) {
            return $query->getRow()->cnt;
        }

        return 0;
    }

    function getLowerCourtCase($dairy_no = 0)
    {
        $builder = $this->db->table('lowerct');

        $builder->select('ct_code, l_state, lct_casetype, lct_caseno, lct_caseyear')
            ->where('diary_no', $dairy_no)
            ->where('lw_display', 'Y');

        $query = $builder->get();

        // Check if any rows were returned
        if ($query->getNumRows() > 0) {
            return $query->getRowArray();
        }

        return false;
    }

    function check_section($dacode, $matter_section)
    {
        $builder = $this->db->table('users');

        $builder->select('section')
                ->where('usercode', $dacode)
                ->where('display', 'Y');

        $query = $builder->get();

        if ($query->getNumRows() > 0) 
        {
            $da_data = $query->getRowArray(); // Fetch as associative array

            if (isset($da_data['section']) && $da_data['section'] != $matter_section)
            {
                $builder = $this->db->table('matters_with_wrong_section');

                $add_data = [
                    'diary_no'      => $_REQUEST['dno'],
                    'dacode'        => $dacode,
                    'da_section_id' => $da_data['section'],
                    'matter_section_id' => $matter_section,
                    'ent_by'        => session()->get('dcmis_user_idd'),
                    'ent_on'        => date('Y-m-d H:i:s') // Use the current timestamp
                ];

                $result = $builder->insert($add_data);

                return false;
            }            
        }

        return true;
    }

    function getCaseHistoryByReg($data = [])
    {
        $builder = $this->db->table('main_casetype_history a');

        $builder->select('b.dacode, a.diary_no, new_registration_number,')
            ->select("SPLIT_PART(SPLIT_PART(new_registration_number, '-', 2), '-', 1) AS part1")
            ->select("SPLIT_PART(new_registration_number, '-', 1) AS part2")
            ->select('dacode, name, section_name, casetype_id, active_casetype_id, diary_no_rec_date, 
                        reg_year_mh, reg_year_fh, active_reg_year, ref_agency_state_id')
            ->join('main b', 'a.diary_no = b.diary_no', 'left')
            ->join('users c', 'b.dacode = c.usercode', 'left')
            ->join('usersection us', 'c.section = us.id', 'left')
            ->where('ref_new_case_type_id', $data['lct_casetype'])
            ->where('new_registration_year', $data['lct_caseyear'])
            ->where('is_deleted', 'f')
            ->where("'" . $data['lct_caseno'] . "' BETWEEN SPLIT_PART(SPLIT_PART(new_registration_number, '-', 2), '-', 1) AND SPLIT_PART(new_registration_number, '-', 2)");

        // Execute the query
        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            return $query->getRowArray(); // Fetch as associative array
        }

        return false;
    }

    function generate_da_code($dairy_no = 0)
    {
        $sec_da_upto_disposal = array(21, 55);

        // $builder = $this->db->table('main');

        // $builder->select('section_id, dacode, from_court, ref_agency_state_id, ref_agency_code_id')
        //     ->select("CASE 
        //                      WHEN active_casetype_id = 0 OR active_casetype_id IS NULL OR active_casetype_id = '' 
        //                      THEN casetype_id 
        //                      ELSE active_casetype_id 
        //                    END AS casetype_id")
        //     ->select("EXTRACT(YEAR FROM CASE 
        //                      WHEN active_fil_dt = '0000-00-00 00:00:00' OR active_fil_dt IS NULL OR active_fil_dt = '' 
        //                      THEN diary_no_rec_date 
        //                      ELSE active_fil_dt 
        //                    END) AS regyear")
        //     ->select("DATE(diary_no_rec_date) AS fildate")
        //     ->select("DATE(CASE 
        //                      WHEN active_fil_dt = '0000-00-00 00:00:00' OR active_fil_dt IS NULL OR active_fil_dt = '' 
        //                      THEN diary_no_rec_date 
        //                      ELSE active_fil_dt 
        //                    END) AS filregdate")
        //     ->where('diary_no', $dairy_no);
                $sql = "
            SELECT 
                section_id, 
                dacode, 
                from_court, 
                ref_agency_state_id,  
                ref_agency_code_id,   
                CASE 
                    WHEN active_casetype_id = 0 OR active_casetype_id IS NULL THEN casetype_id 
                    ELSE active_casetype_id 
                END AS casetype_id,   
                EXTRACT(YEAR FROM 
                    CASE 
                        WHEN NULLIF(CAST(active_fil_dt AS TEXT), '0000-00-00 00:00:00') IS NULL THEN diary_no_rec_date 
                        ELSE active_fil_dt 
                    END
                ) AS regyear,    
                DATE(diary_no_rec_date) AS fildate,    
                DATE(
                    CASE 
                        WHEN NULLIF(CAST(active_fil_dt AS TEXT), '0000-00-00 00:00:00') IS NULL THEN diary_no_rec_date 
                        ELSE active_fil_dt 
                    END
                ) AS filregdate 
            FROM main 
            WHERE diary_no = ?";

        // Execute the query with binding
        //echo $sql;die;
        $query = $this->db->query($sql, [$dairy_no]);        
        // Fetch the results
        $results = $query->getRowArray();       
            

        //$query = $builder->get();

        $row_main = null; // Default value in case no results are found
        if (count($results) > 0) {
            $rcasetype = array(1, 3);
            //$row_main = $query->getRowArray(); // Fetch as associative array
            $row_main = $results; // Fetch as associative array
            
            //check if dacode already exist and section is matching with da section  matters_with_wrong_section
            if ($row_main['dacode'] != 0 && $row_main['dacode'] != '') {
                // check_section($row_main[dacode],$row_main[section_id]);

                if (in_array($row_main['section_id'], $sec_da_upto_disposal)) {
                    
                    $output['success'] = 0;
                    $output['error'] = "DA already alloted";
                    
                    return false;
                }
            }

            $previous_daname = array(39, 9, 10, 19, 20, 25, 26);
            $forXandPIL = array(5, 6);
            if (in_array($row_main['casetype_id'], $previous_daname)) {
                $lower_case_temp_row = $this->getLowerCourtCase($dairy_no);
                if (!empty($lower_case_temp_row)) {

                    $row_da = $this->getCaseHistoryByReg([
                        'lct_caseno' => str_pad($lower_case_temp_row['lct_caseno'], 6, 0, STR_PAD_LEFT),
                        'lct_casetype' => $lower_case_temp_row['lct_casetype'],
                        'lct_caseyear' => $lower_case_temp_row['lct_caseyear'],
                    ]);

                    if (!empty($row_da)) {

                        $result = $this->check_section($row_da['dacode'], $row_main['section_id']);
                        if($result === false) 
                        {
                            $output['success'] = 0;
                            $output['error'] = "DA already alloted";
                            
                            return $output;
                        }

                        $result = $this->updateMainTrack([
                            'dacode' => $row_da['dacode'],
                            'dairy_no' => $dairy_no,
                        ]);

                        $output['success'] = 1;
                        $output['message'] = "SUCCESSFUL, DA ALLOTTED SUCCESSFULLY";
                    } else 
                    {
                        $output['success'] = 0;
                        $output['message'] = "SORRY, DA NOT FOUND BECAUSE FOR CONT,RP,CURT AND MA PREVIOUS RECORD DOES NOT HAVE DA";
                        
                        return false;
                    }
                } else {
                    $output['success'] = 0;
                    $output['message'] = "SORRY, DA NOT FOUND BECAUSE FOR CONT,RP,CURT AND MA PREVIOUS RECORD NOT FOUND";                    
                    
                    return false;
                }
            } 
            else 
            {
                $dacodeallotted = 0;
                if (in_array($row_main['casetype_id'], $forXandPIL)) {

                    $builder = $this->db->table('mul_category');

                    $builder->select('submaster_id')
                            ->where('diary_no', $dairy_no)
                            ->whereIn('submaster_id', [
                                349, 118, 119, 120, 121, 122, 123, 124, 125, 126, 127, 128, 129, 
                                130, 131, 132, 133, 318, 332, 567, 568, 569, 570, 571, 572, 573, 
                                574, 575, 576, 577, 578, 579, 580, 581, 582
                            ])
                            ->where('display', 'Y');

                    $query = $builder->get();

                    if ($query->getNumRows() > 0) {
                        // your code here
                        $result = $this->check_section('690', $row_main['section_id']);
                        
                        if($result === false) 
                        {
                            $output['success'] = 0;
                            $output['error'] = "DA already alloted";
                            return false;
                        }

                        $this->updateMainTrack([
                            'dacode' => '690',
                            'dairy_no' => $dairy_no,
                        ]);

                        $output['success'] = 1;
                        $output['message'] = "SUCCESSFUL, DA ALLOTTED SUCCESSFULLY";
                        
                        $dacodeallotted = 1;
                    } else {
                        $dacodeallotted = 0;
                    }
                }
                elseif ($row_main['from_court'] == '5') 
                {
                    $tribunal = '';

                    $builder = $this->db->table('ref_agency_code');

                    $query = $builder->select('agency_or_court')
                                    ->where('id', $row_main['ref_agency_code_id'])
                                    ->get();

                    if ($query->getNumRows() > 0) {
                        $tribunal_sec_arr = $query->getRowArray();
                        $tribunal = $tribunal_sec_arr['agency_or_court'];
                    }

                    // $forTribunalsXVII_A = array(40,109,114,169,10085,164,162,177,280,312,10003,10075,10076,10067,142,10095,10017,322,203,247,272,10086,1,190);
                    if ($tribunal == 5) 
                    {

                        $builder = $this->db->table('da_case_distribution_tri');

                        $currentYear = date('Y');

                        $query = $builder->select('dacode')
                                        ->where('case_type', $row_main['casetype_id'])
                                        ->where("$currentYear BETWEEN case_f_yr AND case_t_yr")
                                        ->where('ref_agency IS NOT NULL')
                                        ->where("ref_agency ILIKE '%{$row_main['ref_agency_code_id']}%'")
                                        ->where('display', 'Y')
                                        ->get();

                        if ($query->getNumRows() > 0) 
                        {
                            if ($query->getNumRows() > 1) 
                            {
                                $output['success'] = 0; 
                                $output['error'] = "ERROR, DA CAN NOT ALLOT BECAUSE MORE THAN ONE DA FOUND";
                                $dacodeallotted = 0;
                            } 
                            else 
                            {
                                $row_da = $query->getRowArray();

                                $result = $this->check_section($row_da['dacode'], $row_main['section_id']);

                                if($result === false) 
                                {
                                    $output['success'] = 0;
                                    $output['error'] = "DA already alloted";
                                    return false;
                                }

                                $this->updateMainTrack([
                                    'dacode' => $row_da['dacode'],
                                    'dairy_no' => $dairy_no,
                                ]);

                                $output['success'] = 1;
                                $output['message'] = "SUCCESSFUL, DA ALLOTTED SUCCESSFULLY";

                                $dacodeallotted = 1;
                            }
                        } else if (in_array($row_main['casetype_id'], $rcasetype)) {
 
                            $builder = $this->db->table('users');

                            $query = $builder->select('usercode')
                                            ->where('section', 82)
                                            ->where('usertype', 14)
                                            ->where('display', 'Y')
                                            ->get();

                            if ($query->getNumRows() > 0) {
                                $rw_bo = $query->getRowArray();
                                $bocode = $rw_bo['usercode'];

                                $result = $this->check_section($bocode, $row_main['section_id']);

                                if($result === false) 
                                {
                                    $output['success'] = 0;
                                    $output['error'] = "DA already alloted";
                                    return false;
                                }

                                $this->updateMainTrack([
                                    'dacode' => $bocode,
                                    'dairy_no' => $dairy_no,
                                ]);

                                $output['success'] = 1;
                                $output['message'] = "SUCCESSFUL, Branch officer Name Sucessfully Alloted as there is no DA";

                                $dacodeallotted = 1;
                            }
                        }
                    } 
                    else 
                    {

                        $builder = $this->db->table('da_case_distribution_tri');

                        $currentYear = date('Y');

                        $query = $builder->select('dacode')
                                        ->where('case_type', $row_main['casetype_id'])
                                        ->where("$currentYear BETWEEN case_f_yr AND case_t_yr")
                                        ->where('ref_agency IS NOT NULL')
                                        ->where("ref_agency ILIKE '%{$row_main['ref_agency_code_id']}%'")
                                        ->where('display', 'Y')
                                        ->get();

                        if ($query->getNumRows() > 0) 
                        {
                            if ($query->getNumRows() > 1) 
                            {
                                $output['success'] = 0; 
                                $output['error'] = "ERROR, DA CAN NOT ALLOT BECAUSE MORE THAN ONE DA FOUND";
                                $dacodeallotted = 0;
                            } 
                            else 
                            {
                                $row_da = $query->getRowArray();

                                $result = $this->check_section($row_da['dacode'], $row_main['section_id']);

                                if($result === false) 
                                {
                                    $output['success'] = 0;
                                    $output['error'] = "DA already alloted";
                                    return false;
                                }

                                $this->updateMainTrack([
                                    'dacode' => $row_da['dacode'],
                                    'dairy_no' => $dairy_no,
                                ]);

                                $output['success'] = 1;
                                $output['message'] = "SUCCESSFUL, DA ALLOTTED SUCCESSFULLY";

                                $dacodeallotted = 1;
                            }
                        } else if (in_array($row_main['casetype_id'], $rcasetype)) {
                            $builder = $this->db->table('users');

                            $query = $builder->select('usercode')
                                            ->where('section', 52)
                                            ->where('usertype', 14)
                                            ->where('display', 'Y')
                                            ->get();

                            if ($query->getNumRows() > 0) {
                                $rw_bo = $query->getRowArray();
                                $bocode = $rw_bo['usercode'];

                                $result = $this->check_section($bocode, $row_main['section_id']);

                                if($result === false) 
                                {
                                    $output['success'] = 0;
                                    $output['error'] = "DA already alloted";
                                    return false;
                                }

                                $this->updateMainTrack([
                                    'dacode' => $bocode,
                                    'dairy_no' => $dairy_no,
                                ]);

                                $output['success'] = 1;
                                $output['message'] = "SUCCESSFUL, Branch officer Name Sucessfully Alloted as there is no DA";

                                $dacodeallotted = 1;
                            }
                        }
                    }
                }

                //else
                if ($dacodeallotted == 0) {


                    if ($row_main['regyear'] < date("Y") && !in_array($row_main['section_id'], $sec_da_upto_disposal)) {
                        $row_main['regyear'] = date("Y");
                    }
                    
                    $builder = $this->db->table('main');
                    
                    $currentYear = date('Y');
                    
                    // $builder->select('diary_no, fil_dt')
                    //         ->select('ROW_NUMBER() OVER (ORDER BY fil_dt) AS rownum')
                    //         ->where('ref_agency_state_id', $row_main['ref_agency_state_id'])
                    //         ->where('active_casetype_id', $row_main['casetype_id'])
                    //         ->where("EXTRACT(YEAR FROM COALESCE(NULLIF(active_fil_dt, '0000-00-00 00:00:00'), diary_no_rec_date))", $row_main['regyear']);
                    $builder->select('diary_no, fil_dt')
                            ->select('ROW_NUMBER() OVER (ORDER BY fil_dt) AS rownum', false)
                            ->where('ref_agency_state_id', $row_main['ref_agency_state_id'])
                            ->where('active_casetype_id', $row_main['casetype_id'])
                            ->where("EXTRACT(YEAR FROM COALESCE(NULLIF(active_fil_dt::TEXT, '0000-00-00 00:00:00')::TIMESTAMP, diary_no_rec_date)) =", $row_main['regyear'], false);
                    //echo $builder->getCompiledSelect();die;
                    $query = $builder->get();
                    $current_no = 1;
                    
                    foreach ($query->getResultArray() as $row_number_for) {
                        if ($row_number_for['diary_no'] == $dairy_no) {
                            $current_no = $row_number_for['rownum'];
                        }
                    }

                    $builder = $this->db->table('da_case_distribution_new');

                    if (in_array($row_main['section_id'], $sec_da_upto_disposal)) {
                        $builder->select('dacode')
                                ->where('case_type', $row_main['casetype_id'])
                                ->where('$current_no BETWEEN case_from AND case_to', null, false)
                                ->where('$row_main[fildate] BETWEEN case_f_yr AND case_t_yr', null, false)
                                ->groupStart()
                                    ->where('state', $row_main['ref_agency_state_id'])
                                    ->orWhere('state', 0)
                                ->groupEnd()
                                ->where('display', 'Y');
                    } else {
                        $builder->select('dacode')
                                ->where('case_type', $row_main['casetype_id'])
                                ->where('$current_no BETWEEN case_from AND case_to', null, false)
                                ->where('$row_main[filregdate] BETWEEN case_f_yr AND case_t_yr', null, false)
                                ->groupStart()
                                    ->where('state', $row_main['ref_agency_state_id'])
                                    ->orWhere('state', 0)
                                ->groupEnd()
                                ->where('display', 'Y');
                        //echo $builder->getCompiledSelect();die;
                        $query = $builder->get();

                        if ($query->getNumRows() <= 0) {
                            $builder = $this->db->table('da_case_distribution');
                            
                            $builder->select('dacode')
                                    ->where('case_type', $row_main['casetype_id'])
                                    ->where('$current_no BETWEEN case_from AND case_to', null, false)
                                    ->where('$row_main[regyear] BETWEEN case_f_yr AND case_t_yr', null, false)
                                    ->groupStart()
                                        ->where('state', $row_main['ref_agency_state_id'])
                                        ->orWhere('state', 0)
                                    ->groupEnd()
                                    ->where('display', 'Y');
                        }
                    }

                    $query = $builder->get();

                    if ($query->getNumRows() > 0) {
                        if ($query->getNumRows() > 1) {
                            
                            $output['success'] = 0;
                            $output['message'] = "ERROR, DA CAN NOT ALLOT BECAUSE MORE THAN ONE DA FOUND";

                        } else {
                            $row_da = $query->getRowArray();

                            $result = $this->check_section($row_da['dacode'], $row_main['section_id']);

                            if($result === false) 
                            {
                                $output['success'] = 0;
                                $output['error'] = "DA already alloted";
                                return false;
                            }

                            $this->updateMainTrack([
                                'dacode' => $row_da['dacode'],
                                'dairy_no' => $dairy_no,
                            ]);

                            $output['success'] = 1;
                            $output['message'] = "SUCCESSFUL, DA ALLOTTED SUCCESSFULLY";

                        }
                    } else {
                        $output['success'] = 0;
                        $output['message'] = "SORRY, DA NOT FOUND";
                    }
                }
            }
        } else {
            $output['success'] = 0;
            $output['message'] = "SORRY, DIARY NUMBER NOT FOUND";
        }

        return $output;
    }

    function getRegistrationNumberDisplay($diaryNo, $registrationNumber, $registrationYear)
    {
        $previousRegistrationNumber = $regNoDisplay = "";
        $caseType = substr($registrationNumber, 0, 2);
        $reg1 = substr($registrationNumber, 3, 6);

        if (strlen($registrationNumber) > 9)
            $reg2 = substr($registrationNumber, 10, 6);
        else
            $reg2 = substr($registrationNumber, 3, 6);

        $row = $this->getCaseType($caseType);
        $res_ct_typ = $row['short_description'];

        if ($caseType == 9 || $caseType == 10 || $caseType == 19 || $caseType == 20 || $caseType == 25 || $caseType == 26 || $caseType == 39) {
            $builder = $this->db->table('main m');

            $subquery = $this->db->table('lowerct')
                ->select('lct_casetype, lct_caseno AS caseNumber, lct_caseyear AS caseYear')
                ->where('diary_no', $diaryNo)
                ->where('ct_code', 4);

            $builder->select('m.reg_no_display')
                ->join("($subquery->getQueryString()) ld", 'active_casetype_id = ld.lct_casetype', 'inner');

            $builder->where('CHAR_LENGTH(m.active_fil_no) > 10')
                ->where('CAST(ld.caseNumber AS INTEGER) BETWEEN 
                            CAST(SPLIT_PART(m.active_fil_no, \'-\', 2) AS INTEGER) AND 
                            CAST(SPLIT_PART(m.active_fil_no, \'-\', 3) AS INTEGER)')
                ->where('active_reg_year', 'ld.caseYear');

            $builder->orWhere('CHAR_LENGTH(m.active_fil_no) = 9')
                ->where('active_casetype_id', 'ld.lct_casetype')
                ->where('CAST(ld.caseNumber AS INTEGER) = 
                            CAST(SPLIT_PART(m.active_fil_no, \'-\', 2) AS INTEGER)')
                ->where('active_reg_year', 'ld.caseYear');

            $query1 = $builder->getQueryString(); // Get the first part of the union

            // Now for the second part of the union
            $builder2 = $this->db->table('main_casetype_history mch')
                ->join('main', 'mch.diary_no = main.diary_no');

            $subquery2 = $this->db->table('lowerct')
                ->select('lct_casetype, lct_caseno AS caseNumber, lct_caseyear AS caseYear')
                ->where('diary_no', $diaryNo)
                ->where('ct_code', 4);

            $builder2->join("($subquery2->getQueryString()) ld", '1=1', 'inner'); // Join the subquery

            // Repeat similar conditions for the second part
            $builder2->where('CHAR_LENGTH(mch.old_registration_number) > 10')
                ->where('ref_old_case_type_id', 'ld.lct_casetype')
                ->where('CAST(ld.caseNumber AS INTEGER) BETWEEN 
                            CAST(SPLIT_PART(mch.old_registration_number, \'-\', 2) AS INTEGER) AND 
                            CAST(SPLIT_PART(mch.old_registration_number, \'-\', 3) AS INTEGER)')
                ->where('old_registration_year', 'ld.caseYear');

            $builder2->orWhere('CHAR_LENGTH(mch.old_registration_number) = 9')
                ->where('ref_old_case_type_id', 'ld.lct_casetype')
                ->where('CAST(ld.caseNumber AS INTEGER) = 
                            CAST(SPLIT_PART(mch.old_registration_number, \'-\', 2) AS INTEGER)')
                ->where('old_registration_year', 'ld.caseYear');

            // Add other conditions similarly for new_registration_number...
            // Then combine the queries with a UNION

            $query2 = $builder2->getQueryString(); // Get the second part

            // Combine the two queries with UNION
            $final_query = "$query1 UNION $query2";

            $result = $this->db->query($final_query);

            $row_result = $result->getRowArray(); // Fetch as associative array


            // $query = "select m.reg_no_display from main m,
            // (select lct_casetype,lct_caseno as caseNumber,lct_caseyear as caseYear from lowerct where diary_no=" . $diaryNo . " and ct_code=4) ld      
            // where (char_length(m.active_fil_no) >10 and (active_casetype_id=ld.lct_casetype and CAST(ld.caseNumber AS UNSIGNED)
            // BETWEEN (SUBSTRING_INDEX( SUBSTRING_INDEX(active_fil_no, '-', 2), '-', -1 )) AND
            // (SUBSTRING_INDEX(active_fil_no, '-', -1)) and active_reg_year=ld.caseYear))
            // or ((char_length(m.active_fil_no) =9 and (active_casetype_id=ld.lct_casetype and CAST(ld.caseNumber AS UNSIGNED)
            // = (SUBSTRING_INDEX( SUBSTRING_INDEX(active_fil_no, '-', 2), '-', -1 )) and active_reg_year=ld.caseYear)))
            // union 
            // select main.reg_no_display from main_casetype_history mch,main,
            // (select lct_casetype,lct_caseno as caseNumber,lct_caseyear as caseYear from lowerct where diary_no=" . $diaryNo . " and ct_code=4) ld 
            // where mch.diary_no=main.diary_no and ((char_length(mch.old_registration_number) >10 and (ref_old_case_type_id=ld.lct_casetype and CAST(ld.caseNumber AS UNSIGNED)
            // BETWEEN (SUBSTRING_INDEX( SUBSTRING_INDEX(old_registration_number, '-', 2), '-', -1 )) AND
            // (SUBSTRING_INDEX(old_registration_number, '-', -1)) and old_registration_year=ld.caseYear))
            // or ((char_length(old_registration_number) =9 and (ref_old_case_type_id=ld.lct_casetype and CAST(ld.caseNumber AS UNSIGNED)
            // = (SUBSTRING_INDEX( SUBSTRING_INDEX(old_registration_number, '-', 2), '-', -1 )) and old_registration_year=ld.caseYear)))
            // or
            // (char_length(mch.new_registration_number) >10 and (ref_new_case_type_id=ld.lct_casetype and CAST(ld.caseNumber AS UNSIGNED)
            // BETWEEN (SUBSTRING_INDEX( SUBSTRING_INDEX(new_registration_number, '-', 2), '-', -1 )) AND
            // (SUBSTRING_INDEX(new_registration_number, '-', -1)) and new_registration_year=ld.caseYear))
            // or ((char_length(new_registration_number) =9 and (ref_new_case_type_id=ld.lct_casetype and CAST(ld.caseNumber AS UNSIGNED)
            // = (SUBSTRING_INDEX( SUBSTRING_INDEX(new_registration_number, '-', 2), '-', -1 )) and new_registration_year=ld.caseYear))))";

            // //echo $query;

            // $result = mysql_query($query) or die("Error" . __LINE__ . mysql_error());
            // $row_result = mysql_fetch_array($result);

            $previousRegistrationNumber = $row_result['reg_no_display'];
        }

        if ($reg1 == $reg2)
            $regNoDisplay = $res_ct_typ . " " . (int)$reg1 . '/' . $registrationYear;
        else
            $regNoDisplay = $res_ct_typ . " " . (int)$reg1 . '-' . (int)$reg2 . '/' . $registrationYear;

        if ($previousRegistrationNumber != "" && $previousRegistrationNumber != null) {
            $regNoDisplay .= " in " . $previousRegistrationNumber;
        }

        return $regNoDisplay;
    }

    function getCaseDetails($dairy_no = 0)
    {
        // $dairy_no = 12024;

        $builder = $this->db->table('main m');
        $builder->select([
            'm.case_grp',
            'm.diary_no',
            'm.pet_name',
            'm.res_name',
            'm.pet_adv_id AS pet_adv',
            'm.res_adv_id AS res_adv',
            'm.c_status',
            'm.lastorder',
            "(CASE 
                WHEN (m.conn_key != '' AND m.conn_key IS NOT NULL) THEN 
                    (CASE WHEN m.conn_key = m.diary_no::text THEN 'N' ELSE 'Y' END) 
                ELSE 'NA' 
            END) AS ccdet",
            'm.conn_key AS connto',
            'm.fil_no',
            'm.fil_no_fh',
            'm.fil_dt',
            "TO_CHAR(m.fil_dt, 'DD-MM-YYYY HH12:MI AM') AS fil_dt_f",
            "TO_CHAR(m.fil_dt_fh, 'DD-MM-YYYY HH12:MI AM') AS fil_dt_fh",
            "(CASE 
                WHEN (m.fil_no IS NOT NULL AND m.fil_no != '') THEN SPLIT_PART(m.fil_no, '-', 1) 
                ELSE '' 
            END) AS ct1",
            "(CASE 
                WHEN m.fil_no != '' THEN SPLIT_PART(m.fil_no, '-', 2) 
                ELSE '' 
            END) AS crf1",
            "(CASE 
                WHEN m.fil_no != '' THEN SPLIT_PART(m.fil_no, '-', 3) 
                ELSE '' 
            END) AS crl1",
            "(CASE 
                WHEN m.fil_no_fh != '' THEN SPLIT_PART(m.fil_no_fh, '-', 1) 
                ELSE '' 
            END) AS ct2",
            "(CASE 
                WHEN m.fil_no_fh != '' THEN SPLIT_PART(m.fil_no_fh, '-', 2) 
                ELSE '' 
            END) AS crf2",
            "(CASE 
                WHEN m.fil_no_fh != '' THEN SPLIT_PART(m.fil_no_fh, '-', 3) 
                ELSE '' 
            END) AS crl2"
        ]);

        $builder->where('m.diary_no', $dairy_no);

        $query = $builder->get();

        // Check if any rows were returned
        if ($query->getNumRows() > 0) {
            return $query->getRowArray();
        }

        return false;
    }

    function getOrderDate($dairy_no = 0)
    {
        $order_date = '';

        $builder = $this->db->table('heardt h');

        // Select statement with date formatting and grouping
        $builder->select('h.diary_no, TO_CHAR(h.next_dt, \'DD-MM-YYYY\') AS next_dt, STRING_AGG(crm.r_head::TEXT, \', \') AS Disp_Remarks');
        $builder->join('case_remarks_multiple crm', 'crm.diary_no = h.diary_no::varchar(150) AND crm.cl_date = h.next_dt', 'inner');
        $builder->join('master.case_remarks_head crh', 'crh.sno = crm.r_head AND (crh.display = \'Y\' OR crh.display IS NULL)', 'inner');

        $builder->where('h.diary_no', $dairy_no);
        $builder->where('h.clno !=', 0);
        $builder->where('h.brd_slno !=', 0);
        $builder->where('h.brd_slno IS NOT NULL');
        $builder->where('h.roster_id !=', 0);
        $builder->where('h.roster_id IS NOT NULL');
        $builder->whereIn('h.main_supp_flag', [1, 2]);
        $builder->where('h.next_dt <=', date('Y-m-d'));
        $builder->where('h.next_dt = (SELECT MAX(next_dt) FROM heardt b WHERE b.diary_no = h.diary_no AND b.clno != 0 AND b.brd_slno != 0 AND b.brd_slno IS NOT NULL AND b.roster_id != 0 AND b.roster_id IS NOT NULL AND main_supp_flag IN (1,2))', null);
        $builder->whereIn('crm.r_head', [181, 182, 3, 183, 184, 1, 41, 176, 177, 178, 27, 196, 200, 201]);
        $builder->groupBy('h.diary_no, h.next_dt');

        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            $order_dt = $query->getRowArray();
            $order_date = $order_dt['next_dt'];
        } else {

            $builder = $this->db->table('last_heardt h');

            // Select statement with date formatting and grouping
            $builder->select('h.diary_no, TO_CHAR(h.next_dt, \'DD-MM-YYYY\') AS next_dt, STRING_AGG(crm.r_head::TEXT, \', \') AS Disp_Remarks');
            $builder->join('case_remarks_multiple crm', 'crm.diary_no = h.diary_no::varchar(150) AND crm.cl_date = h.next_dt', 'inner');
            $builder->join('master.case_remarks_head crh', 'crh.sno = crm.r_head AND (crh.display = \'Y\' OR crh.display IS NULL)', 'inner');

            $builder->where('h.diary_no', $dairy_no);
            $builder->where('h.clno !=', 0);
            $builder->where('h.brd_slno !=', 0);
            $builder->where('h.brd_slno IS NOT NULL');
            $builder->where('h.roster_id !=', 0);
            $builder->where('h.roster_id IS NOT NULL');
            $builder->where('(h.bench_flag IS NULL OR h.bench_flag = \'\')', null);
            $builder->whereIn('h.main_supp_flag', [1, 2]);
            $builder->where('h.next_dt <=', date('Y-m-d'));
            $builder->where('h.next_dt = (SELECT MAX(next_dt) FROM last_heardt b WHERE b.diary_no = h.diary_no AND b.clno != 0 AND b.brd_slno != 0 AND b.brd_slno IS NOT NULL AND b.roster_id != 0 AND b.roster_id IS NOT NULL AND (b.bench_flag IS NULL OR b.bench_flag = \'\') AND main_supp_flag IN (1,2))', null);
            $builder->whereIn('crm.r_head', [181, 182, 3, 183, 184, 1, 41, 176, 177, 178, 27, 196, 200, 201]);
            $builder->groupBy('h.diary_no, h.next_dt');

            $query = $builder->get();

            if ($query->getNumRows() > 0) {
                $order_dt = $query->getRowArray();
                $order_date = $order_dt['next_dt'];
            }
        }

        return $order_date;
    }

    function getTotalFiles($dairy_no = 0)
    {
        $builder = $this->db->table('main');

        $builder->select("NULLIF(fil_no, '') AS fil_no,
                        SPLIT_PART(fil_no, '-', 1) AS ct,
                        SPLIT_PART(fil_no, '-', 2) AS cl1,
                        reverse(SPLIT_PART(reverse(fil_no), '-', 1)) AS cl2,
                        ((reverse(SPLIT_PART(reverse(fil_no), '-', 1)))::int - 
                        CAST(SPLIT_PART(fil_no, '-', 2) AS INTEGER) + 1) AS ttl_files");

        $builder->where('diary_no', $dairy_no);
        $query = $builder->get();

        $db = db_connect();

        $db->getLastQuery();
        
        // echo $this->db->error();
        // echo $this->db->get_compiled_select();

        // Check if any rows were returned
        if ($query->getNumRows() > 0) {
            return $query->getRowArray();
        }

        return false;
    }

    function getActiveCaseType($dairy_no = 0)
    {
        $results = [];

        $builder = $this->db->table('main');

        $builder->select('active_casetype_id');
        $builder->where('diary_no', $dairy_no);

        $query = $builder->get();


        if ($query->getNumRows() > 0) {
            $casetype = $query->getRowArray();

            if ($casetype['active_casetype_id'] == 1) {
                $conversion_type = 3;
            } else if ($casetype['active_casetype_id'] == 2) {
                $conversion_type = 4;
            } else if ($casetype['active_casetype_id'] == 7) {
                $conversion_type = 11;
            } else {
                $conversion_type = 0;
            }


            $builder = $this->db->table('master.casetype');

            $builder->select('casecode, skey, casename, short_description');
            $builder->where('casecode', $conversion_type);
            $builder->where('display', 'Y');
            $builder->where('casecode !=', 9999);
            $builder->where('cs_m_f', 'F');
            $builder->orderBy('short_description');

            $caseTypeQuery = $builder->get();

            if ($caseTypeQuery->getNumRows() > 0) {
                $results = $caseTypeQuery->getResultArray();
            }
        }

        return ['conversion_type' => $conversion_type, 'results' => $results];
    }

    /**
     * Get the case details
     */
    function getCaseType($casecode = 0)
    {
        // Prepare the query using Query Builder for case type
        $builder = $this->db->table('master.casetype');
        $builder->select('short_description');
        $builder->where('display', 'Y');
        $builder->where('casecode', $casecode);

        // Execute the query
        $casetypeQuery = $builder->get();

        // Check if any rows were returned
        if ($casetypeQuery->getNumRows() > 0) {
            return $casetypeQuery->getRowArray();
        }

        return false;
    }

    /**
     * Get the case details
     */
    function getCaseNature($casecode = 0)
    {
        // Prepare the query using Query Builder for case type
        $builder = $this->db->table('master.casetype');
        $builder->select('casecode, nature');
        $builder->where('display', 'Y');
        $builder->where('casecode', $casecode);

        // Execute the query
        $casetypeQuery = $builder->get();

        // Check if any rows were returned
        if ($casetypeQuery->getNumRows() > 0) {
            return $casetypeQuery->getRowArray();
        }

        return false;
    }

    function cancelRegistration($dairy_no = 0)
    {
        $user_ip = get_client_ip();
        $ucode = session()->get('login')['usercode'];

        // Prepare the query using Query Builder
        $builder = $this->db->table('main');
        $builder->select(
            'diary_no, active_fil_no, fil_no, fil_no_old, pet_name, res_name, res_name_old, pet_adv_id, res_adv_id, actcode, claim_amt, 
                            bench, fixed, c_status, fil_dt, active_fil_dt, case_pages, relief, usercode, last_usercode, dacode, old_dacode, old_da_ec_case, last_dt, 
                            conn_key, case_grp, lastorder, fixeddet, bailno, prevno, head_code, scr_user, scr_time, scr_type, prevno_fildt, ack_id, ack_rec_dt, admitted, 
                            outside, diary_no_rec_date, diary_user_id, ref_agency_state_id, ref_agency_state_id_old, ref_agency_code_id, ref_agency_code_id_old, from_court, 
                            is_undertaking, undertaking_doc_type, undertaking_reason, casetype_id, active_casetype_id, padvt, radvt, total_court_fee, court_fee, valuation, 
                            case_status_id, brief_description, nature, fil_no_fh, fil_no_fh_old, fil_dt_fh, mf_active, active_reg_year, reg_year_mh, reg_year_fh, reg_no_display, 
                            pno, rno, if_sclsc, section_id, unreg_fil_dt, EXTRACT(YEAR FROM fil_dt) AS registration_year'
        );
        $builder->where('diary_no', $dairy_no);

        // Execute the query
        $query = $builder->get();

        // Check if any rows were returned
        if ($query->getNumRows() > 0) {
            $row = $query->getRowArray(); // Fetch the first row as an associative array

            // pr($row);

            // Check conditions
            if (
                $row['active_fil_no'] == $row['fil_no']
                && $row['active_fil_dt'] == $row['fil_dt']
                && $row['mf_active'] == 'M'
            ) {
                $active_year = substr($row['active_fil_dt'], 0, 4);

                $this->db->transStart();

                // Update registered_cases
                $builder = $this->db->table('registered_cases');
                $builder->set('display', 'N');
                $builder->where('diary_no', $dairy_no);
                $builder->where('display', 'Y');
                $builder->update();

                // Update main_casetype_history
                $builder2 = $this->db->table('main_casetype_history');
                $builder2->set('is_deleted', 't');
                $builder2->set('remark', 'Cancelled');
                $builder2->set('updated_on', 'now()', false); // Use false to prevent escaping
                $builder2->where('diary_no', $dairy_no);
                $builder2->where('is_deleted', 'f');
                $builder2->where('new_registration_number', $row['active_fil_no']);
                $builder2->where('new_registration_year', $active_year);
                $builder2->update();

                // Insert into main_cancel_reg
                $cancel_log_builder = $this->db->table('main_cancel_reg');
                $cancel_log_data = [
                    'diary_no' => $dairy_no,
                    'active_fil_no' => $row['active_fil_no'],
                    'fil_no' => $row['fil_no'],
                    'fil_no_old' => $row['fil_no_old'],
                    'pet_name' => $row['pet_name'],
                    'res_name' => $row['res_name'],
                    'res_name_old' => $row['res_name_old'],
                    'pet_adv_id' => $row['pet_adv_id'],
                    'res_adv_id' => $row['res_adv_id'],
                    'actcode' => $row['actcode'],
                    'claim_amt' => $row['claim_amt'],
                    'bench' => $row['bench'],
                    'fixed' => $row['fixed'],
                    'c_status' => $row['c_status'],
                    'fil_dt' => $row['fil_dt'],
                    'active_fil_dt' => $row['active_fil_dt'],
                    'case_pages' => $row['case_pages'],
                    'relief' => $row['relief'],
                    'usercode' => $ucode,
                    'last_usercode' => $row['last_usercode'],
                    'dacode' => $row['dacode'],
                    'old_dacode' => $row['old_dacode'],
                    'old_da_ec_case' => $row['old_da_ec_case'],
                    'last_dt' => $row['last_dt'],
                    'conn_key' => $row['conn_key'],
                    'case_grp' => $row['case_grp'],
                    'lastorder' => $row['lastorder'],
                    'fixeddet' => $row['fixeddet'],
                    'bailno' => $row['bailno'],
                    'prevno' => $row['prevno'],
                    'head_code' => $row['head_code'],
                    'scr_user' => $row['scr_user'],
                    'scr_time' => $row['scr_time'],
                    'scr_type' => $row['scr_type'],
                    'prevno_fildt' => $row['prevno_fildt'],
                    'ack_id' => $row['ack_id'],
                    'ack_rec_dt' => $row['ack_rec_dt'],
                    'admitted' => $row['admitted'],
                    'outside' => $row['outside'],
                    'diary_no_rec_date' => $row['diary_no_rec_date'],
                    'diary_user_id' => $row['diary_user_id'],
                    'ref_agency_state_id' => $row['ref_agency_state_id'],
                    'ref_agency_state_id_old' => $row['ref_agency_state_id_old'],
                    'ref_agency_code_id' => $row['ref_agency_code_id'],
                    'ref_agency_code_id_old' => $row['ref_agency_code_id_old'],
                    'from_court' => $row['from_court'],
                    'is_undertaking' => $row['is_undertaking'],
                    'undertaking_doc_type' => $row['undertaking_doc_type'],
                    'undertaking_reason' => $row['undertaking_reason'],
                    'casetype_id' => $row['casetype_id'],
                    'active_casetype_id' => $row['active_casetype_id'],
                    'padvt' => $row['padvt'],
                    'radvt' => $row['radvt'],
                    'total_court_fee' => $row['total_court_fee'],
                    'court_fee' => $row['court_fee'],
                    'valuation' => $row['valuation'],
                    'case_status_id' => $row['case_status_id'],
                    'brief_description' => $row['brief_description'],
                    'nature' => $row['nature'],
                    'fil_no_fh' => $row['fil_no_fh'],
                    'fil_no_fh_old' => $row['fil_no_fh_old'],
                    'fil_dt_fh' => $row['fil_dt_fh'],
                    'mf_active' => $row['mf_active'],
                    'active_reg_year' => $row['active_reg_year'],
                    'reg_year_mh' => $row['reg_year_mh'],
                    'reg_year_fh' => $row['reg_year_fh'],
                    'reg_no_display' => $row['reg_no_display'],
                    'pno' => $row['pno'],
                    'rno' => $row['rno'],
                    'if_sclsc' => $row['if_sclsc'],
                    'section_id' => $row['section_id'],
                    'unreg_fil_dt' => $row['unreg_fil_dt'],
                    'cancel_by' => $ucode,
                    'cancel_on' => 'now()',
                    'cancel_ip' => $user_ip,
                ];

                $cancel_log_builder->insert($cancel_log_data);

                // Update main
                $builder3 = $this->db->table('main');
                $builder3->set('fil_no', '');
                $builder3->set('fil_dt', null);
                $builder3->set('usercode', $ucode);
                $builder3->set('mf_active', '');
                $builder3->set('active_fil_no', '');
                $builder3->set('active_fil_dt', null);
                $builder3->set('reg_no_display', '');
                $builder3->set('reg_year_mh', 0);
                $builder3->set('active_reg_year', 0);
                $builder3->set('active_casetype_id', 0);
                $builder3->where('diary_no', $dairy_no);

                $builder3->update();

                $this->db->transComplete();

                return $this->db->transStatus();
            }
        }

        return false;
    }
}
