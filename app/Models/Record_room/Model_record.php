<?php

namespace App\Models\Record_room;

use CodeIgniter\Model;
use App\Models\Entities\Model_Ac;


class Model_record extends Model
{

    protected $table = 'ac';
    protected $allowedFields = [
        'aor_code',
        'cname',
        'cfname',
        'pa_line1',
        'pa_line2',
        'pa_district',
        'pa_pin',
        'ppa_line1',
        'ppa_line2',
        'ppa_district',
        'ppa_pin',
        'dob',
        'place_birth',
        'nationality',
        'cmobile',
        'eq_x',
        'eq_xii',
        'eq_ug',
        'eq_pg',
        'eino',
        'regdate',
        'status',
        'updated_by',
        'create_modify',
        'updated_on',
        'updated_by_ip',
        // 'updatedip',

    ];


    public function __construct()
    {
        parent::__construct();
        $db = \Config\Database::connect();
        $this->db = db_connect();
    }


    public function checkExistingData($aorcode, $eino)
    {
        $builder = $this->db->table('ac');
        $builder->select('COUNT(*) as count_rows');
        $builder->where('aor_code', $aorcode);
        $builder->where('eino', $eino);
        $result = $builder->get()->getRow();
        return $result->count_rows;
    }


    public function getClerkDetails()
    {
        $builder = $this->db->table('ac a');
        $builder->select('b.name, cname, cfname, eino, TO_CHAR(regdate, \'DD-MM-YYYY\') as formatted_regdate, a.aor_code, a.id, a.cmobile');
        $builder->join('master.bar b', 'b.aor_code = a.aor_code');
        $builder->orderBy('a.id');
        $query = $builder->get();
        $rows = $query->getResult();


        return $query->getResultArray();
    }
    public function getClerkDetails1($tvap)
    {
        $builder = $this->db->table('ac a');
        $builder->select('b.name, cname, cfname, eino, TO_CHAR(regdate, \'DD-MM-YYYY\') as formatted_regdate, a.aor_code, a.id, a.cmobile');
        $builder->join('master.bar b', 'b.aor_code = a.aor_code');
        $builder->where('a.aor_code', $tvap); // Add the condition to filter based on id

        $builder->orderBy('a.id');
        $query = $builder->get();
        $rows = $query->getResult();


        return $query->getResultArray();
    }
    public function getval($id)
    {
        $builder = $this->db->table('ac a');
        $builder->select("*,a.dob");
        $builder->join('master.bar b', 'b.aor_code = a.aor_code');
        $builder->where('a.id', $id); // Add the condition to filter based on id
        $query = $builder->get();

        // Check if any rows are returned
        if ($query->getNumRows() > 0) {
            return $query->getRowArray(); // Return a single row
        } else {
            return null; // No rows found
        }
    }
    public function updateAc($id, $data)
    {
        try {
            $response = $this->db->table('public.ac')->where('id', $id)->update($data);
            // echo $this->db->getLastQuery();
            // // var_dump($response);
            // exit;
            return true;
        } catch (\Exception $e) {
            // Log the error message
            log_message('error', 'Error updating data in the database: ' . $e->getMessage());
            // Also, you might want to echo the error message for debugging purposes
            echo 'Error: ' . $e->getMessage();
            return false;
        }
    }

    public function getadv_name1($tvap)
    {
        $builder = $this->db->table('master.bar');
        $builder->select('name');
        $builder->where('CAST(aor_code AS TEXT)', $tvap);
        $query = $builder->get();
        $row = $query->getRow();
        if ($row) {
            echo htmlspecialchars($row->name, ENT_QUOTES, 'UTF-8');
        }
    }

    // filter AORoption -----------
    public function getaoroption($tvap)
    {
        $builder = $this->db->table('ac a');
        $builder->select('b.aor_code, b.name');
        $builder->join('master.bar b', 'b.aor_code = a.aor_code');
        $builder->where('CAST(a.eino AS TEXT)', $tvap);
        $query = $builder->get();
        return $query->getResultArray();
    }



    public function getclerk($tvap)
    {
        $builder = $this->db->table('ac a');
        $builder->select('*');
        $builder->join('master.bar b', 'b.aor_code = a.aor_code');
        $builder->where('a.eino', $tvap);
        $query = $builder->get();

        return $query->getResultArray();
    }
    public function getclerk1($tvap)
    {
        $builder = $this->db->table('ac a');
        $builder->select("UPPER(CONCAT(cname, ' S/o ', cfname)) as clerk_name", false);
        $builder->where('a.eino', $tvap);
        $query = $builder->get();
        $row = $query->getRow();
        if ($row) {
            return htmlspecialchars($row->clerk_name, ENT_QUOTES, 'UTF-8');
        } else {
            return ""; // or handle the case when no row is found
        }
    }



    public function getaoroption1($tvap, $vadvc, $filters = [])
    {
        $checkBuilder = $this->builder('ac a');
        $checkBuilder->select('COUNT(*) AS total');
        $checkBuilder->where('a.eino', $tvap);
        $result = $checkBuilder->get()->getRow();

        $einoExists = ($result && $result->total > 0);
        $builder = $this->builder('ac a');

        $builder->select("CONCAT(a.aor_code, '#', b.name, '#', a.cname, '#', a.cfname, '#', a.pa_line1, '#', a.pa_line2,
         '#', a.pa_district, '#', a.pa_pin, '#', a.ppa_line1, '#', a.ppa_line2, '#', a.ppa_district, '#', a.ppa_pin, '#', a.dob, 
         '#', a.place_birth, '#', a.nationality, '#', a.cmobile, '#', a.eq_x, '#', a.eq_xii, '#', a.eq_ug, '#', a.eq_pg, '#', a.regdate, 
         '#', a.id) AS result");
        $builder->join('master.bar b', 'b.aor_code = a.aor_code', 'inner');

        $builder->where('a.eino', $tvap);

        if ($einoExists && $vadvc !== null) {
            $builder->where('a.aor_code', $vadvc);
        }

        foreach ($filters as $key => $value) {
            if (!empty($value)) {
                $builder->where('a.' . $key, $value);
            }
        }
        $query = $builder->get();
        $row = $query->getRow();

        if ($row) {
            return $row->result;
            // print_r($this->db->getLastQuery());
        } else {
            return null;
        }
    }


    public function getAORsWithMoreClerks()
    {
        return $this->db->table('ac')
            ->select('ac.aor_code, bar.name, COUNT(*) as clerk_count')
            ->join('master.bar', 'bar.aor_code = ac.aor_code')
            ->groupBy('ac.aor_code, bar.name')
            ->having('COUNT(*) >', 2)
            ->orderBy('ac.aor_code', 'DESC')
            ->get()
            ->getResultArray();
    }

    public function getAORClerks($aorCode)
    {
        return $this->db->table('ac')
            ->select('eino, cname, cfname, TO_CHAR(regdate, \'DD-MM-YYYY\') as formatted_regdate, id')
            ->where('aor_code', $aorCode)
            ->orderBy('eino')
            ->get()
            ->getResultArray();
    }


    public function getTransactions($acid)
    {
        return $this->db->table('transactions a')
            ->select('a.id, acid, event_name, TO_CHAR(event_date, \'DD-MM-YYYY\') as formatted_event_date, remarks')
            ->join('master.event_master b', 'b.event_code = a.event_code')
            ->where('acid', $acid)
            ->orderBy('a.event_code')
            ->get()
            ->getResultArray();
    }
    public function getCancelRecords()
    {
        return $this->db->table('ac')
            ->select('bar.name, ac.cname, ac.cfname, ac.eino, TO_CHAR(ac.regdate, \'DD-MM-YYYY\') as formatted_regdate, ac.aor_code, TO_CHAR(transactions.event_date, \'DD-MM-YYYY\') as formatted_event_date')
            ->join('master.bar', 'bar.aor_code = ac.aor_code')
            ->join('transactions', 'transactions.acid = ac.id', 'left')
            ->where('transactions.event_code', 3)
            ->orderBy('transactions.event_date', 'desc')
            ->orderBy('bar.name')
            ->get()
            ->getResultArray();
    }
    public function getDuplicateRecords()
    {
        $builder = $this->db->table('ac a');
        $builder->select('eino, aor_code, COUNT(*) as count');
        $builder->groupBy('eino, aor_code');
        $builder->having('COUNT(*) > 1');
        $builder->orderBy('eino', 'desc');
        return $builder->get()->getResultArray();
    }

    public function getClerksAttachedWithAORs($eino)
    {
        $builder = $this->db->table('ac');
        $builder->select('bar.name, ac.cname, ac.cfname, ac.eino, TO_CHAR(ac.regdate, \'DD-MM-YYYY\') as formatted_regdate, ac.aor_code');
        $builder->join('master.bar', 'bar.aor_code = ac.aor_code');
        $builder->where('ac.eino', $eino);
        $builder->orderBy('ac.aor_code');

        return $builder->get()->getResultArray();
    }
    public function getAORDetails()
    {
        return $this->db->table('master.bar')
            ->select('*')
            ->where(['isdead' => 'N', 'if_aor' => 'Y'])
            ->orderBy('aor_code')
            ->get()
            ->getResultArray();
    }

    public function getClerksWithMoreThan2AORs()
    {
        $query = $this->db->table('ac')
            ->select('eino, COUNT(*) as aor_count')
            ->groupBy('eino')
            ->having('COUNT(*) > 2')
            ->orderBy('eino', 'desc')
            ->get();

        return $query->getResultArray();
    }
    public function getClerkDetailsByEino($eino)
    {
        $query = $this->db->table('ac a')
            ->join('master.bar b', 'b.aor_code = a.aor_code')
            ->select('b.name, cname, cfname, eino, TO_CHAR(regdate, \'DD-MM-YYYY\') as regdate, a.aor_code, cmobile')
            ->where('eino', $eino)
            ->orderBy('a.aor_code')
            ->get();

        return $query->getResultArray();
    }
    public function getdept()
    {
        $query = $this->db->table('master.users a')
            ->select('dept_name, udept')
            ->join('master.userdept  b', 'a.udept=b.id')
            ->distinct('dept_name, udept')
            ->where('b.id', 3)
            ->get();

        return $query->getResultArray();
    }
    public function getuser($ucode)
    {

        $query = $this->db->table('master.users a')
            ->select('usertype, section, udept, usercode')
            ->where('usercode', $ucode)
            ->get();

        return $query->getRowArray();
    }
    public function insert1($data)
    {
        try {
            $this->db->table('public.ac')->insert($data);
            return true;
        } catch (\Exception $e) {
            // Log the error message
            log_message('error', 'Error inserting data into the database: ' . $e->getMessage());
            // Also, you might want to echo the error message for debugging purposes
            echo 'Error: ' . $e->getMessage();
            return false;
        }
    }


    // code by sandeep
    public function getData($tvap)
    {
        return $this->select("CONCAT(a.aor_code, '#', b.name, '#', a.cname, '#', a.cfname, '#', a.pa_line1, '#', a.pa_line2, '#', a.pa_district, '#', a.pa_pin, '#', a.ppa_line1, '#', a.ppa_line2, '#', a.ppa_district, '#', a.ppa_pin, '#', a.dob, '#', a.place_birth, '#', a.nationality, '#', a.cmobile, '#', a.eq_x, '#', a.eq_xii, '#', a.eq_ug, '#', a.eq_pg, '#', a.regdate, '#', a.id) as result")
            ->from('ac a')
            ->join('master.bar b', 'b.aor_code = a.aor_code', 'left')
            ->where('CAST(a.eino AS TEXT)', $tvap)
            ->get()
            ->getRowArray();
    }




    public function getInsertData($data)
    {
        return $this->insert($data);
    }

    public function getUserByCode($user_id)
    {
        $query = $this->db->table('master.users')
            ->select('usertype, section, udept, usercode')
            ->where('usercode', $user_id)
            ->get();
        return $query->getRowArray();
    }
    public function getUsersData()
    {
        $query = $this->db->table('master.users a')
            ->distinct()
            ->select('dept_name, udept')
            ->join('master.userdept b', 'a.udept = b.id', 'left')
            ->where('b.id', 3)
            ->get();
        return $query->getResultArray();
    }

    public function getRefHallData()
    {
        $builder = $this->db->table('master.ref_rr_hall h');
        $builder->select("a.hall_no, MAX(h.description) AS Description,MAX(valid_from) AS active_from,
        CASE WHEN h.display = 'Y' THEN 'A' ELSE 'N' END AS active_status,h.display ");
        $builder->join('master.rr_hall_case_distribution a', 'a.hall_no = h.hall_no', 'left');
        $builder->where('h.display', 'Y');
        $builder->groupBy('a.hall_no, h.display');
        $builder->orderBy('a.hall_no');
        $query = $builder->get();
        return $query->getResultArray();
    }


    public function getRefHallData_old()
    {
        $sql = "SELECT a.hall_no,MAX(h.description) AS Description,MAX(valid_from) AS active_from,
        CASE WHEN h.display = 'Y' THEN 'A' ELSE 'N' END AS active_status,
            h.display FROM master.ref_rr_hall h 
        LEFT JOIN master.rr_hall_case_distribution a ON a.hall_no = h.hall_no
        WHERE h.display = 'Y'
        GROUP BY a.hall_no, h.display
        ORDER BY a.hall_no";
        $query = $this->query($sql);
        return $query->getResultArray();
    }

    public function get_chk_case($hall_no)
    {
        // Use the query builder
        $builder = $this->db->table('master.rr_hall_case_distribution a');
        $builder->distinct();
        $builder->select('casetype, case_from, caseyear_from, case_to, caseyear_to, short_description, a.hall_no, a.is_diary_stage');
        $builder->join('master.casetype b', 'casetype = casecode', 'left');
        $builder->join('master.rr_user_hall_mapping m', 'CAST(a.hall_no AS VARCHAR) = m.ref_hall_no AND m.display = \'Y\'', 'left'); // Use single quotes for 'Y'
        $builder->where('a.hall_no', $hall_no); // Ensure hall_no is treated as an integer
        $builder->where('a.display', 'Y');
        $builder->orderBy('casetype, caseyear_from, hall_no');

        // Execute the query
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function getSections($cur_user_type, $deptname, $usercode)
    {
        $builder = $this->db->table('master.users a');
        $builder->join('master.usersection b', 'a.section = b.id', 'left');
        $builder->DISTINCT();
        $builder->select('a.section, b.section_name');

        if ($cur_user_type == 1) {
            $builder->where('b.id', 61);
        } elseif ($cur_user_type == 57) {
            $builder->where('udept', $deptname)
                ->where('usertype !=', 2);
        } else {
            $builder->where('udept', $deptname)
                ->where('usercode', $usercode);
        }
        $builder->orderBy('b.section_name');
        return $builder->get()->getResultArray();
    }

    public function getUserTypes($deptname)
    {
        $builder = $this->db->table('master.user_d_t_map')
            ->select('utype')
            ->where('udept', $deptname)
            ->orderBy('utype');
        return $builder->get()->getResultArray();
    }

    public function getUserTypesData($cur_user_type, $deptname, $section)
    {
        $builder = $this->db->table('master.users a');
        $builder->DISTINCT();
        $builder->select('a.usertype, b.type_name')
            ->join('master.usertype b', 'a.usertype = b.id', 'left')
            ->where('a.udept', $deptname)
            ->where('a.section', $section);
        if ($cur_user_type == 1) {
            $builder->orderBy('a.usertype');
        } else {
            $builder->where('a.usertype !=', 2)
                ->orderBy('a.usertype');
        }
        return $builder->get()->getResultArray();
    }

    public function getCurrentUser($userid)
    {
        $builder = $this->db->table('master.users a')
            ->select("a.usercode, a.name, a.empid, a.service, a.udept, a.section, a.usertype, a.log_in, a.jcode, a.attend, b.dept_name,
             c.section_name, d.type_name, a.entdt,  e.usertype AS fil_usertype,c.isda,e.user_type")
            ->join('master.userdept b', 'a.udept = b.id', 'left')
            ->join('master.usersection c', 'a.section = c.id', 'left')
            ->join('master.usertype d', 'a.usertype = d.id', 'left')
            ->join('fil_trap_users e', "a.usercode = e.usercode AND e.display = 'Y'", 'left')
            ->where('a.usercode', $userid);
        // echo $builder->getCompiledSelect();
        return $builder->get()->getRowArray();
    }

    public function getFilteredUserTypes()
    {
        $builder = $this->db->table('master.usertype')
            ->select('id, type_name')
            ->where('display', 'E');
        return $builder->get()->getResultArray();
    }

    public function getRoomHalls()
    {
        $builder = $this->db->table('master.ref_rr_hall')
            ->select("hall_no, description as Description")
            ->where('display', 'Y')
            ->orderBy('hall_no');
        return $builder->get()->getResultArray();
    }

    public function checkCase($userid)
    {
        $builder = $this->db->table('fil_trap_users a')
            ->select("b.type_name, b.disp_flag, m.ref_hall_no, h.description, d.casehead AS for_caseGroup")
            ->join('master.usertype b', 'a.usertype = b.id', 'inner')
            ->join('master.rr_user_hall_mapping m', 'a.usercode = m.usercode', 'inner')
            ->join('master.ref_rr_hall h', 'CAST(m.ref_hall_no AS INTEGER) = h.hall_no', 'inner')
            ->join('master.rr_da_case_distribution d', 'a.usercode = d.user_code', 'inner')
            ->where('a.usercode', $userid)
            ->where('a.display', 'Y')
            ->whereIn("b.display", ['E', 'Y']) // Corrected this line
            ->where('m.display', 'Y')
            ->where('d.display', 'Y')
            ->orderBy('m.ref_hall_no')
            ->orderBy('for_caseGroup');

        // echo $builder->getCompiledSelect();
        return $builder->get()->getResultArray();
    }

    public function checkCase_one($userid)
    {
        $builder = $this->db->table('fil_trap_users a')
            ->select("m.ref_hall_no, h.description, d.casehead AS for_caseGroup")
            ->join('master.usertype b', 'a.usertype = b.id', 'inner')
            ->join('master.rr_user_hall_mapping m', 'a.usercode = m.usercode', 'inner')
            ->join('master.ref_rr_hall h', 'CAST(m.ref_hall_no AS INTEGER) = h.hall_no', 'inner')
            ->join('master.rr_da_case_distribution d', 'a.usercode = d.user_code', 'inner')
            ->where('a.usercode', $userid)
            ->where('a.display', 'Y')
            ->where("b.display IN ('E', 'Y')")
            ->where('m.display', 'Y')
            ->where('d.display', 'Y')
            ->orderBy('m.ref_hall_no')
            ->orderBy('for_caseGroup');
        return $builder->get()->getResultArray();
    }

    public function checkCaseDistribution($usercode)
    {
        $builder = $this->db->table('master.rr_da_case_distribution a')
            ->select('a.casetype, a.case_from, a.caseyear_from, a.case_to, a.caseyear_to, b.short_description')
            ->join('master.casetype b', 'a.casetype = b.casecode', 'left')
            ->where('a.user_code', $usercode)
            ->where('a.display', 'Y')
            ->where('a.casetype !=', 0)
            ->orderBy('a.casetype')
            ->orderBy('a.caseyear_from');
        return $builder->get()->getResultArray();
    }

    public function displayLastLogin($usercode)
    {
        if ($usercode != 0 && $usercode != '') {
            $builder = $this->db->table('log_check')
                ->selectMax('logging')
                ->where('usercode', $usercode);
            $result = $builder->get()->getRowArray();
            // pr($result);
            if ($result && $result['logging'] !== null && $result['logging'] != '') {
                return $result['logging'];
            } else {
                return '0000-00-00';
            }
        } else {
            return '0000-00-00';
        }
    }

    public function displayPost($empid, $service)
    {
        if ($empid != 0 && $empid != '') {
            if ($service == 'E') {
                $builder = $this->db->table('emp_desg a')
                    ->select('desgname1')
                    ->join('emp_details_t b', 'a.post = b.desgcode', 'left')
                    ->where('empid', $empid)
                    ->where('b.display', 'Y');
                $result = $builder->get()->getRowArray();
                if ($result) {
                    echo $result->desgname1;
                }
            }
        }
    }

    public function getUsers($dept, $auth_name, $authValue, $secValue, $desg, $usercode, $cur_user_type, $judge_selector, $orderjud)
    {
        $builder = $this->db->table('master.users a')
            ->select('usercode, name, empid, service, udept, section, usertype, log_in, jcode, attend, dept_name, section_name, type_name, a.entdt, isda')
            ->join('master.userdept b', 'a.udept = b.id', 'left')
            ->join('master.usersection c', 'a.section = c.id', 'left')
            ->join('master.usertype d', 'a.usertype = d.id', 'left')
            ->where('a.display', 'Y');

        if ($dept == 'ALL') {
            if ($auth_name == '0') {
                $builder->orderBy('udept')
                    ->orderBy('section')
                    ->orderBy('usertype')
                    ->orderBy('a.entdt');

                if (!empty($judge_selector)) {
                    $builder->where($judge_selector);
                }
            } else {
                $auth_query_part = $authValue == 'F' ? "f_auth = $auth_name" : "a_auth = $auth_name";
                $auth_query_part2 = "t2.f_auth != $auth_name AND t2.a_auth != $auth_name";

                $subQuery = $this->db->table('user_l_map a')
                    ->select('a.udept AS udept_a, a.utype, a.ucode, a.l_type, a.f_auth, b.dept_name, c.*, d.name, from_date, to_date, Emp_id, service')
                    ->join('userdept b', 'a.udept = b.id', 'left')
                    ->join('users c', 'b.dept_name = c.udept AND a.utype = c.usertype AND (CASE WHEN a.ucode != 0 THEN a.ucode = c.usercode ELSE a.ucode != c.usercode END) AND c.display = "Y"', 'left')
                    ->join('users_history d', 'c.usercode = d.userid AND d.to_date = "0000-00-00" AND d.display = "Y"', 'left')
                    ->where($auth_query_part)
                    ->where('a.display', 'Y')
                    ->where('Emp_id !=', 0);

                $builder->join("($subQuery) f", 'f.usercode = a.usercode', 'left')
                    ->join("(
                            SELECT t2.*
                            FROM (
                                SELECT udept, utype, ucode, l_type, f_auth
                                FROM user_l_map
                                WHERE $auth_query_part AND display = 'Y'
                            ) t
                            INNER JOIN user_l_map t2 ON t.udept = t2.udept AND t.utype = t2.utype
                            WHERE t2.ucode != 0 AND $auth_query_part2 AND display = 'Y'
                        ) f2", 'f.usercode = f2.ucode', 'left')
                    ->where('f2.id IS NULL')
                    ->groupBy('usercode')
                    ->orderBy('LENGTH(f.udept)')
                    ->orderBy('f.udept')
                    ->orderBy('usertype')
                    ->orderBy('LENGTH(username)')
                    ->orderBy('username');
            }
        } else if ($secValue == 'ALL') {
            if ($auth_name == '0') {
                if ($cur_user_type == 1) {
                    $builder->where('udept', $dept);
                } elseif ($cur_user_type == 4) {
                    $builder->where('udept', $dept)
                        ->where('section', function ($query) use ($usercode) {
                            $query->select('section')->from('users')->where('usercode', $usercode);
                        })
                        ->where('a.usercode !=', $usercode);
                } else {
                    $builder->where('usertype !=', 2)
                        ->where('udept', $dept);
                }
            }
        } else {
            $builder->where('udept', $dept);
            if ($secValue != 'ALL') {
                $builder->where('section', $secValue);
                if ($desg != 'ALL') {
                    $builder->where('usertype', $desg);
                }
            }
        }
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function getFilTrapUsers($usercode)
    {
        $builder = $this->db->table('fil_trap_users a')
            ->select('b.type_name')
            ->join('master.usertype b', "a.usertype = b.id AND b.display = 'E'", 'left')
            ->where('a.usercode', $usercode)
            ->where('a.display', 'Y');
        // pr($builder->getCompiledSelect());
        $query = $builder->get();
        $result = $query->getRowArray();
        return $result;
    }

    public function getCaseType()
    {
        $builder = $this->db->table('master.casetype')
            ->select('casecode, skey, short_description')
            ->where('display', 'Y')
            ->where('casecode !=', 9999)
            ->orderBy('short_description');
        $query = $builder->get();
        $results = $query->getResultArray();

        return $results;
    }

    public function get_c_casetype()
    {
        $builder = $this->db->table('master.casetype')
            ->select('casecode, skey')
            ->where('display', 'Y')
            ->orderBy('skey');
        $query = $builder->get();
        $results = $query->getResultArray();
        return $results;
    }

    public function get_rkdcmpda_case($userId)
    {
        $builder = $this->db->table('rkdcmpda_case a')
            ->select('a.nature, b.skey')
            ->join('casetype b', 'a.nature = b.casecode', 'left')
            ->where('a.rkdcmpda', $userId)
            ->where('a.display', 'Y')
            ->orderBy('b.skey');
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function getState()
    {
        $builder = $this->db->table('master.state')
            ->select('state_code, name, id_no')
            ->where('district_code', 0)
            ->where('sub_dist_code', 0)
            ->where('village_code', 0)
            ->where('display', 'Y')
            ->where('state_code <', 100)
            ->where('state_code !=', 50)
            ->orderBy('name');
        $query = $builder->get();
        $results = $query->getResultArray();
        return $results;
    }

    public function getJudge()
    {
        $builder = $this->db->table('master.judge');
        $builder->select('jcode, jname');
        $builder->where('display', 'Y');
        //$builder->where('working', 'Y');
        //$builder->whereIn('bldg', ['HCJBP', 'HCIND', 'HCGWL']);
        $builder->orderBy('judge_seniority', 'ASC');
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function getUsersJcode($userid)
    {
        $builder = $this->db->table('master.users');
        $builder->select('jcode');
        $builder->where('usercode', $userid);
        $query = $builder->get();
        return $query->getRowArray();
    }

    public function getUsers1($deptname, $section, $userValue)
    {
        $builder = $this->db->table('master.users');
        $builder->select('usercode, username');
        $builder->where('udept', $deptname);
        $builder->where('usertype', $section);
        $builder->where('usercode !=', $userValue);
        $builder->orderBy('LENGTH(username)');
        $builder->orderBy('username');
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function get_users_history($userid)
    {
        $builder = $this->db->table('users_history');
        $builder->select('*');
        $builder->where('userid', $userid);
        $builder->where('to_date', '0000-00-00');
        $builder->where('display', 'Y');
        $builder->orderBy('username');
        $query = $builder->get();
        return $query->getRowArray();
    }



    public function updateUser($userValue, $data)
    {
        return $this->db->table('master.users')->update($data, ['usercode' => $userValue]);
    }

    public function checkFilTrapUser($userValue)
    {
        return $this->db->table('fil_trap_users')
            ->where(['usercode' => $userValue, 'display' => 'Y'])
            ->get();
    }

    public function updateFilTrapUser($userValue, $data)
    {
        return $this->db->table('fil_trap_users')
            ->update($data, ['usercode' => $userValue, 'display' => 'Y']);
    }

    public function insertFilTrapUser($data)
    {
        return $this->db->table('fil_trap_users')->insert($data);
    }

    public function checkFilTrapBefore($userValue, $fil_t)
    {
        return $this->db->table('fil_trap_users')
            ->where(['usercode' => $userValue, 'usertype' => $fil_t, 'display' => 'Y'])
            ->get();
    }

    public function updateDisplay($chk_code)
    {
        return $this->db->table('chk_case')->set('display', 'C')
            ->where(['chkcode' => $chk_code, 'display' => 'Y'])
            ->update();
    }

    public function insertCheckCase($data)
    {
        return $this->db->table('chk_case')->insert($data);
    }

    public function checkCaseExists($chk_code, $casecode)
    {
        return $this->db->table('chk_case')
            ->where(['chkcode' => $chk_code, 'casecode' => $casecode, 'display' => 'C'])
            ->get()
            ->getResultArray();
    }

    public function updateCaseDisplay($chk_code, $casecode)
    {
        return $this->db->table('chk_case')->set('display', 'Y')
            ->where(['chkcode' => $chk_code, 'casecode' => $casecode, 'display' => 'C'])
            ->update();
    }

    public function setDisplayToN($chk_code)
    {
        return $this->db->table('chk_case')->set('display', 'N')
            ->where(['chkcode' => $chk_code, 'display' => 'C'])
            ->update();
    }

    public function getEmployeeName($empid, $serviceValue)
    {
        return $this->db->table('master.emp_details_t')
            ->select('name')
            ->where('empid', $empid)
            ->where('service', $serviceValue)
            ->get()
            ->getRowArray();
    }

    public function checkEmployeeRegistered($empid)
    {
        $builder = $this->db->table('users_history');
        return $builder->where(['Emp_id' => $empid, 'display' => 'Y'])->get()->getRow();
    }

    public function insertNewUserHistory($userValue, $empname, $empid, $service)
    {
        $builder = $this->db->table('master.users');
        $user = $builder->select(['usercode', 'username'])->where('usercode', $userValue)->get()->getRow();

        if ($user) {
            $insertData = [
                'userid' => $user->usercode,
                'Username' => $user->username,
                'name' => trim($empname),
                'from_date' => date('Y-m-d'),
                'to_date' => '0000-00-00',
                'display' => 'Y',
                'date_of_entry' => date('Y-m-d H:i:s'),
                'Emp_id' => $empid,
                'service' => $service,
            ];

            $builder = $this->db->table('users_history');
            $builder->insert($insertData);
            $builder = $this->db->table('master.users');
            $builder->set(['pa_ps' => "SUBSTRING_INDEX(pa_ps, '_', 1)", 'display' => 'Y'], '', false)
                ->where('usercode', $userValue)
                ->update();
        }
    }

    public function checkReader($judge, $userValue)
    {
        if ($judge != 0) {
            $sql = "SELECT usercode FROM master.users WHERE jcode = ? AND usertype = 13";
            $query = $this->db->query($sql, [$judge]);

            if ($query->getNumRows() > 0) {
                $if_reader = $query->getRow()->usercode;
                if ($if_reader != $userValue) {
                    return "One Judge Can have One Reader Only";
                }
            }
        }
        return null;
    }

    public function getUserOptions($authValue)
    {
        if ($authValue == 'F') {
            $sql = "
                SELECT DISTINCT f_auth AS user, username,
                (SELECT name FROM users_history WHERE userid = f_auth AND display = 'Y' AND to_date = '0000-00-00') AS name
                FROM user_l_map a
                INNER JOIN users b ON f_auth = usercode
                WHERE a.display = 'Y'
                ORDER BY f_auth
            ";
        } elseif ($authValue == 'A') {
            $sql = "
                SELECT DISTINCT a_auth AS user, username,
                (SELECT name FROM users_history WHERE userid = a_auth AND display = 'Y' AND to_date = '0000-00-00') AS name
                FROM user_l_map a
                INNER JOIN users b ON a_auth = usercode
                WHERE a.display = 'Y'
                ORDER BY a_auth
            ";
        } else {
            return [];
        }

        $query = $this->db->query($sql);
        return $query->getResultArray();
    }

    public function getLeaveTypes()
    {
        $sql = "SELECT id, name, s_name FROM master.user_l_type WHERE display = 'Y' ORDER BY id";
        $query = $this->db->query($sql);
        return $query->getResultArray();
    }

    public function getUserDetails($deptValue, $section)
    {
        $sql = "
        SELECT usercode, a.name as username, a.name, a.empid as Emp_id 
        FROM master.users a 
        INNER JOIN users_history b ON usercode = userid 
        WHERE udept = (SELECT id FROM master.userdept WHERE dept_name = ?)
        AND usertype = ? 
        AND a.display = 'Y' 
        AND b.display = 'Y' 
        AND to_date IS NULL 
        AND Emp_id != '0' 
        ORDER BY LENGTH(a.name), a.name
    ";

        $query = $this->db->query($sql, [$deptValue, $section]);
        return $query->getResultArray();
    }


    public function getUsersByDepartment($deptValue)
    {
        $extra = '';

        if ($deptValue == 9) {
            $extra = " 
                UNION
                SELECT usercode, a.name as username, a.name, a.empid as Emp_id, a.usertype 
                FROM master.users a INNER JOIN users_history b ON usercode = userid 
                WHERE usertype = 2 AND udept = (SELECT dept_name FROM master.userdept WHERE id = 12) 
                AND a.display = 'Y' AND to_date = '0000-00-00' AND b.display = 'Y' AND Emp_id != 0";
        } elseif ($deptValue == 18 || $deptValue == 14) {
            $extra = " 
                UNION
                SELECT usercode, a.name as username, a.name, a.empid as Emp_id, a.usertype 
                FROM master.users a INNER JOIN users_history b ON usercode = userid 
                WHERE usertype = 2 AND udept = (SELECT dept_name FROM master.userdept WHERE id = 15) 
                AND a.display = 'Y' AND to_date = '0000-00-00' AND b.display = 'Y' AND Emp_id != 0";
        } elseif ($deptValue == 27 || $deptValue == 8 || $deptValue == 10) {
            $extra = " 
                UNION
                SELECT usercode, a.name as username, a.name, a.empid as Emp_id, a.usertype 
                FROM master.users a INNER JOIN users_history b ON usercode = userid 
                WHERE usertype = 2 AND udept = (SELECT dept_name FROM master.userdept WHERE id = 5) 
                AND a.display = 'Y' AND to_date = '0000-00-00' AND b.display = 'Y' AND Emp_id != 0";
        } elseif ($deptValue == 31) {
            $extra = " 
                UNION
                SELECT usercode, a.name as username, a.name, a.empid as Emp_id, a.usertype 
                FROM master.users a INNER JOIN users_history b ON usercode = userid 
                WHERE usercode = 446 
                AND a.display = 'Y' AND to_date = '0000-00-00' AND b.display = 'Y' AND Emp_id != 0";
        } elseif ($deptValue == 48) {
            $extra = " 
                UNION
                SELECT usercode, a.name as username, a.name, a.empid as Emp_id, a.usertype 
                FROM master.users a INNER JOIN users_history b ON usercode = userid 
                WHERE usertype = 91 AND udept = (SELECT dept_name FROM master.userdept WHERE id = 48) 
                AND a.display = 'Y' AND to_date = '0000-00-00' AND b.display = 'Y' AND Emp_id != 0";
        } elseif ($deptValue == 24) {
            $extra = " 
                UNION
                SELECT usercode, a.name as username, a.name, a.empid as Emp_id, a.usertype 
                FROM master.users a INNER JOIN users_history b ON usercode = userid 
                WHERE usertype = 2 AND udept = (SELECT dept_name FROM master.userdept WHERE id = 16) 
                AND a.display = 'Y' AND to_date = '0000-00-00' AND b.display = 'Y' AND Emp_id != 0";
        } elseif ($deptValue == 35) {
            $extra = " 
                UNION
                SELECT usercode, a.name as username, a.name, a.empid as Emp_id, a.usertype 
                FROM master.users a INNER JOIN users_history b ON usercode = userid 
                WHERE usertype = 2 AND udept = (SELECT dept_name FROM master.userdept WHERE id = 24) 
                AND a.display = 'Y' AND to_date = '0000-00-00' AND b.display = 'Y' AND Emp_id != 0";
        }

        $builder = $this->db->table('master.users a');
        $builder->select('usercode, a.name as username, a.name, a.empid as Emp_id, a.usertype');
        $builder->join('users_history b', 'usercode = userid');

        // Main conditions
        $builder->groupStart()
            ->where("udept = 'HCJUDICIAL' AND usertype IN (20,31,33,83)")
            ->orWhere("udept = 'HCADMIN' AND usertype IN (20,31,33,52)")
            ->orWhere("udept = 'SW' AND usertype = 16")
            ->orWhere("udept = 'JOTRI' AND usertype = 90")
            ->orWhere('usercode IN (888, 899, 999)')
            ->groupEnd()
            ->where('a.display', 'Y')
            ->where('to_date', '0000-00-00')
            ->where('b.display', 'Y')
            ->where('Emp_id !=', 0);

        // First UNION
        $builder->union($this->db->table('master.users a')
            ->select('usercode, a.name as username, a.name, a.empid as Emp_id, a.usertype')
            ->join('users_history b', 'usercode = userid')
            ->where('usertype', 2)
            ->where('udept', '(SELECT dept_name FROM master.userdept WHERE id = ' . $deptValue . ')', false)
            ->where('a.display', 'Y')
            ->where('to_date', '0000-00-00')
            ->where('b.display', 'Y')
            ->where('Emp_id !=', 0));

        // Second UNION
        $builder->union($this->db->table('master.users a')
            ->select('usercode, a.name as username, a.name, a.empid as Emp_id, a.usertype')
            ->join('users_history b', 'usercode = userid')
            ->where('usertype', 34)
            ->where('udept', '(SELECT dept_name FROM master.userdept WHERE id = ' . $deptValue . ')', false)
            ->where('a.display', 'Y')
            ->where('to_date', '0000-00-00')
            ->where('b.display', 'Y')
            ->where('Emp_id !=', 0));

        // Additional UNION if needed
        if ($extra) {
            $builder->union($this->db->table('master.users a')
                ->select('usercode, a.name as username, a.name, a.empid as Emp_id, a.usertype')
                ->join('users_history b', 'usercode = userid')
                ->groupStart()
                ->raw($extra) // Using raw to add the extra SQL
                ->groupEnd());
        }

        $builder->orderBy('usertype');

        // Get the results
        return $builder->get()->getResultArray();
    }

    public function getAuthorizedUsers()
    {
        $builder = $this->db->table('master.users a');
        $builder->select('usercode, a.name as username, a.name, a.empid as Emp_id');
        $builder->join('users_history b', 'usercode = userid');

        // Define conditions
        $builder->groupStart()
            ->where("udept = 'HCJUDICIAL' AND usertype IN (20, 83)")
            ->orWhere("udept = 'HCADMIN' AND usertype IN (20, 52)")
            ->orWhere("udept = 'JOTRI' AND usertype = 90")
            ->orWhere('usercode IN (888, 899, 999)')
            ->groupEnd()
            ->where('a.display', 'Y')
            ->where('to_date', '0000-00-00')
            ->where('b.display', 'Y')
            ->where('Emp_id !=', 0)
            ->orderBy('usercode');

        // Get the results
        return $builder->get()->getResultArray();
    }

    public function get_user_l_map($aname)
    {
        $builder = $this->db->table('master.user_l_map a');
        $builder->DISTINCT();
        $builder->select('a.udept, dept_name')
            ->join('master.users b', 'b.usercode = a.f_auth')
            ->join('master.userdept c', 'a.udept = c.id')
            ->where('a.f_auth', $aname)
            ->where('a.display', 'Y')
            ->orderBy('a.udept');
        return $builder->get()->getResultArray();
    }

    public function get_user_l_map2($aname)
    {
        $builder = $this->db->table('master.user_l_map a');
        $builder->DISTINCT();
        $builder->select('a.udept, dept_name')
            ->join('master.users b', 'b.usercode = a.a_auth')
            ->join('master.userdept c', 'a.udept = c.id')
            ->where('a.a_auth', $aname)
            ->where('a.display', 'Y')
            ->orderBy('a.udept');
        return $builder->get()->getResultArray();
    }

    public function getDeptBuilder()
    {
        $deptBuilder = $this->db->table('users');
        $deptBuilder->DISTINCT();
        $deptBuilder->select('udept')
            ->orderBy('usertype')
            ->orderBy('LENGTH(udept)')
            ->orderBy('udept');
        return $deptBuilder->get()->getResultArray();
    }

    public function update_display($da_code, $usercode)
    {
        $builder = $this->db->table('da_case_distribution');
        return $builder->set(['display' => 'C', 'upuser' => $usercode, 'updt' => date('Y-m-d H:i:s')])
            ->where('dacode', $da_code)
            ->where('display', 'Y')
            ->update();
    }

    public function insertCase($data)
    {
        return $this->db->table('da_case_distribution')->insert($data);
    }

    public function updateCase($da_code, $value, $value_up, $usercode)
    {
        $builder = $this->db->table('da_case_distribution');
        return $builder->set(['display' => 'Y', 'upuser' => $usercode, 'updt' => date('Y-m-d H:i:s')])
            ->where('dacode', $da_code)
            ->where('case_type', $value)
            ->where('case_from', $value_up[1])
            ->where('case_f_yr', $value_up[2])
            ->where('case_to', $value_up[3])
            ->where('case_t_yr', $value_up[4])
            ->where('state', $value_up[5])
            ->where('type', $value_up[6])
            ->where('display', 'C')
            ->update();
    }

    public function findCase($da_code, $value, $value_up)
    {
        $builder = $this->db->table('da_case_distribution');
        return $builder->where('dacode', $da_code)
            ->where('case_type', $value)
            ->where('case_from', $value_up[1])
            ->where('case_f_yr', $value_up[2])
            ->where('case_to', $value_up[3])
            ->where('case_t_yr', $value_up[4])
            ->where('state', $value_up[5])
            ->where('type', $value_up[6])
            ->where('display', 'C')
            ->get()
            ->getRowArray();
    }

    public function setDisplayToNo($rkds_code, $usercode)
    {
        $builder = $this->db->table('da_case_distribution');
        return $builder->set(['display' => 'N', 'upuser' => $usercode, 'updt' => date('Y-m-d H:i:s')])
            ->where('dacode', $rkds_code)
            ->where('display', 'C')
            ->update();
    }

    public function updateDisplayToC($rkdcmpda_code)
    {
        $builder = $this->db->table('rkdcmpda_case');
        return $builder->set(['display' => 'C'])
            ->where('rkdcmpda', $rkdcmpda_code)
            ->where('display', 'Y')
            ->update();
    }

    public function find_case($rkdcmpda_code, $value)
    {
        $builder = $this->db->table('rkdcmpda_case');
        return $builder->where('rkdcmpda', $rkdcmpda_code)
            ->where('nature', $value)
            ->where('display', 'C')
            ->get()
            ->getRowArray();
    }

    public function insert_case($data)
    {
        $builder = $this->db->table('rkdcmpda_case');
        return $builder->insert($data);
    }

    public function updateCaseToY($rkdcmpda_code, $value)
    {
        $builder = $this->db->table('rkdcmpda_case');
        return $builder->set(['display' => 'Y'])
            ->where('rkdcmpda', $rkdcmpda_code)
            ->where('nature', $value)
            ->where('display', 'C')
            ->update();
    }

    public function set_displayToN($rkdcmpda_code)
    {
        $builder = $this->db->table('rkdcmpda_case');
        return $builder->set(['display' => 'N'])
            ->where('rkdcmpda', $rkdcmpda_code)
            ->where('display', 'C')
            ->update();
    }

    public function get_current_user($hall_no)
    {
        $builder = $this->db->table('master.ref_rr_hall h');
        $builder->distinct();
        $builder->select("a.hall_no, h.description, h.updated_on AS active_from, 
            CASE WHEN h.display = 'Y' THEN 'A' ELSE 'N' END AS active_status, h.display");
        $builder->join('master.rr_hall_case_distribution a', 'a.hall_no = h.hall_no', 'left');
        $builder->where('h.display', 'Y');
        $builder->where('h.hall_no', $hall_no);
        $builder->orderBy('a.hall_no');
        $query = $builder->get();
        return $query->getRowArray();
    }


    public function updateDisplayToC2($hall_no, $usercode)
    {
        return $this->db->table('master.rr_hall_case_distribution')->set([
            'display' => 'C',
            'updated_by' => $usercode,
            'valid_to' => date('Y-m-d H:i:s'),
            'update_on' => date('Y-m-d H:i:s')
        ])
            ->where('hall_no', $hall_no)
            ->where('display', 'Y')
            ->update();
    }

    public function findCase2($hall_no, $value, $value_up)
    {
        return $this->db->table('master.rr_hall_case_distribution')
            ->where('hall_no', $hall_no)
            ->where('casetype', $value)
            ->where('case_from', $value_up[1])
            ->where('caseyear_from', $value_up[2])
            ->where('case_to', $value_up[3])
            ->where('caseyear_to', $value_up[4])
            // ->where('is_diary_stage', $value_up[5]) // due to unbility of clearification 
            ->where('display', 'C')
            ->get()
            ->getRowArray(); // Return a single row
    }

    public function insertCase2($data)
    {
        return $this->db->table('master.rr_hall_case_distribution')->insert($data);
    }

    public function updateCaseToY2($hall_no, $value, $value_up, $usercode)
    {
        return $this->db->table('master.rr_hall_case_distribution')->set([
            'display' => 'Y',
            'valid_to' => null,
            'updated_by' => $usercode,
            'update_on' => date('Y-m-d H:i:s')
        ])
            ->where('hall_no', $hall_no)
            ->where('casetype', $value)
            ->where('case_from', $value_up[1])
            ->where('caseyear_from', $value_up[2])
            ->where('case_to', $value_up[3])
            ->where('caseyear_to', $value_up[4])
            ->where('is_diary_stage', 'N')
            ->where('display', 'C')
            ->update();
    }

    public function setDisplayToN2($hall_no, $usercode)
    {
        return $this->db->table('master.rr_hall_case_distribution')->set([
            'display' => 'N',
            'valid_to' => date('Y-m-d H:i:s'),
            'updated_by' => $usercode,
            'update_on' => date('Y-m-d H:i:s')
        ])
            ->where('hall_no', $hall_no)
            ->where('display', 'C')
            ->update();
    }


    public function updateDisplayToC3($usercode)
    {
        return $this->db->table('master.rr_user_hall_mapping')->set([
            'display' => 'C',
            'to_date' => date('Y-m-d H:i:s')
        ])
            ->where('usercode', $usercode)
            ->where('display', 'Y')
            ->update();
    }

    public function insertMapping($data)
    {
        return $this->db->table('master.rr_user_hall_mapping')->insert($data);
    }

    public function updateMappingToY($usercode, $hallNo)
    {
        return $this->db->table('master.rr_user_hall_mapping')->set([
            'display' => 'Y',
            'to_date' => null
        ])
            ->where('usercode', $usercode)
            ->where('ref_hall_no', $hallNo)
            ->where('display', 'C')
            ->update();
        //      echo $this->db->getLastQuery();
        //  die(); 
    }

    public function findMapping($usercode, $hallNo,$display = 'C')
    {
        $query = $this->db->table('master.rr_user_hall_mapping')
            ->where('usercode', $usercode)
            ->where('ref_hall_no', $hallNo)
            ->where('display', $display)
            ->get()->getRowArray()?? NULL;
            return $query;
        //  echo $this->db->getLastQuery();
        //  die();   
    }

    public function deactivateMapping($usercode)
    {
        return $this->db->table('master.rr_user_hall_mapping')->set([
            'display' => 'N',
            'to_date' => date('Y-m-d H:i:s'),
            'updated_by' => $usercode,
            'update_on' => date('Y-m-d H:i:s')
        ])
            ->where('usercode', $usercode)
            ->where('display', 'C')
            ->update();
    }

    public function findCaseDistribution_new($da_code, $caseGroup, $value_up='C')
    {

        $builder = $this->db->table('master.rr_da_case_distribution');
        $builder->where('user_code', $da_code);
        $builder->where('casehead', $caseGroup);
        $builder->where('display', $value_up);
        // echo $builder->getCompiledSelect();
        return $builder->get()->getResultArray();

        // return $this->db->table('master.da_case_distribution')
        // ->where('dacode', $da_code)
        // ->where('CAST(case_type AS TEXT)', $value)
        // ->where('case_from', (int)$value_up[1]) 
        // ->orderBy('id', 'ASC')
        // ->get();
    }



    public function findCaseDistribution($da_code, $value, $value_up)
    {
        return $this->db->table('master.da_case_distribution')
        ->where('dacode', $da_code)
        ->where('CAST(case_type AS TEXT)', $value)
        ->where('case_from', (int)$value_up[1]) 
        ->orderBy('id', 'ASC')
        ->get();

        // return $this->db->table('master.da_case_distribution')->where('dacode', $da_code)
        //     ->where('case_type', $value)
        //     ->where('case_from', $value_up[1])
        //     // ->where('case_f_yr', $value_up[2])
        //     // ->where('case_to', $value_up[3])
        //     // ->where('case_t_yr', $value_up[4])
        //     // ->where('state', $value_up[5])
        //     // ->where('type', $value_up[6])
        //     ->where('display', 'C')
        //     ->orderBy('id','ASC')
        //     ->getRows();
    }

    public function insertCaseDistribution($data)
    {
        return $this->db->table('master.rr_da_case_distribution')->insert($data);
    }

    public function updateCaseToYDistribution($rkdcmpda_code, $value)
    {
        $res= $this->db->table('master.da_case_distribution')->set(['display' => 'Y'])
            ->where('dacode', $rkdcmpda_code)
            // ->where('nature', $value)
            ->where('display', 'C')
            ->update();
        return $res;
    }

    public function updateCaseToYDistribution_new($rkdcmpda_code, $value)
    {
        $res= $this->db->table('master.rr_da_case_distribution')->set(['display' => 'Y','valid_to'=> null])
            ->where('user_code', $rkdcmpda_code)
            ->where('casehead', $value)
            ->where('display', 'C')
            ->update();
        return $res;
    }

    public function deactivateCase($usercode)
    {
        return $this->db->table('master.rr_da_case_distribution')->set([
            'display' => 'N',
            'valid_to' => date('Y-m-d H:i:s'),
            'updated_by' => $usercode,
            'update_on' => date('Y-m-d H:i:s')
        ])
            ->where('user_code', $usercode)
            ->where('display', 'C')
            ->update();
    }

    public function getChkCase($userid)
    {
        return $this->db->table('chk_case a')
            ->select('a.casecode, skey')
            ->join('master.casetype b', 'a.casecode = b.casecode', 'left')
            ->where('chkcode', $userid)
            ->where('a.display', 'Y')
            ->get()
            ->getResultArray();
    }

    public function getCaseDistribution($userid)
{
    return $this->db->table('master.da_case_distribution a')
        ->select('a.case_type, a.case_from, a.case_f_yr, a.case_to, a.case_t_yr, a.state, a.type, b.short_description, c.name')
        ->join('master.casetype b', 'a.case_type = b.casecode', 'left')
        ->join('master.state c', 'a.state = c.id_no', 'left')
        ->where('a.dacode', $userid)
        ->where('a.display', 'Y')
        ->orderBy('a.case_f_yr', 'ASC')
        ->get()
        ->getResultArray();
}


}
