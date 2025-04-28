<?php

namespace App\Models\Judicial\DirectDisposalCase;

use CodeIgniter\Model;

class RemoveConditionDispose extends Model
{

    public function __construct()
    {
        parent::__construct();
        $this->db = db_connect();
    }

    function case_types()
    {
        // $query = $this->db->where('is_deleted', 'f')->where('casecode!=9999')->order_by('casecode', 'ASC')->get('master.casetype');

        $builder2 = $this->db->table("master.casetype");
        $builder2->select("*");
        $builder2->where('casecode !=', '9999');
        $builder2->where('is_deleted', 'f');
        $builder2->orderBy('casecode', 'ASC');
        $query2 = $builder2->get();
        $result = $query2->getResultArray();

        return $result;
    }

    function get_prev_cases($usercode)
    {
        // $sql="select if(r.conn_key is null, r.fil_no,r.conn_key) as listafter,m.reg_no_display as reg_no_after,
        //       concat(m.pet_name,' Vs ',m.res_name) as title_after,concat(m2.pet_name,' Vs ',m2.res_name) as title_first,
        //       if(r.conn_key is not null, group_concat(r.fil_no),'') as connected,r.fil_no2 as disposefirst,m2.reg_no_display as reg_no_first,
        //       r.ent_dt,u.name as user_after,us.section_name as section_after,u2.name as user_first,us2.section_name as section_first 
        //       from rgo_default r left join main m on if(r.conn_key is null, r.fil_no,r.conn_key)=m.diary_no 
        //       left join main m2 on r.fil_no2=m2.diary_no left join users u on m.dacode=u.usercode 
        //       left join usersection us on u.section=us.id left join users u2 on m2.dacode=u2.usercode 
        //       left join usersection us2 on u2.section=us2.id where rgo_updated_by=$usercode and remove_def='N' 
        //       group by r.conn_key";
        // $query = $this->db->query($sql);

        // $builder = $this->db->table('rgo_default r');
        // $builder->select("CASE WHEN r.conn_key IS NULL THEN r.fil_no ELSE r.conn_key END AS listafter,
        //         m.reg_no_display AS reg_no_after,
        //         CONCAT(m.pet_name, ' Vs ', m.res_name) AS title_after,
        //         CONCAT(m2.pet_name, ' Vs ', m2.res_name) AS title_first,
        //         CASE WHEN r.conn_key IS NOT NULL THEN STRING_AGG(r.fil_no, ',') ELSE '' END AS connected,
        //         r.fil_no2 AS disposefirst,
        //         m2.reg_no_display AS reg_no_first,
        //         r.ent_dt,
        //         u.name AS user_after,
        //         us.section_name AS section_after,
        //         u2.name AS user_first,
        //         us2.section_name AS section_first", false);
        // $builder->join('main m', "CASE WHEN r.conn_key IS NULL THEN r.fil_no ELSE r.conn_key END = m.diary_no", 'left');
        // $builder->join('main m2', 'r.fil_no2 = m2.diary_no', 'left');
        // $builder->join('master.users u', 'm.dacode = u.usercode', 'left');
        // $builder->join('master.usersection us', 'u.section = us.id', 'left');
        // $builder->join('master.users u2', 'm2.dacode = u2.usercode', 'left');
        // $builder->join('master.usersection us2', 'u2.section = us2.id', 'left');
        // $builder->where('rgo_updated_by', $usercode);
        // $builder->where('remove_def', 'N');
        // $builder->groupBy('r.conn_key');
        // $query2 = $builder->get();
        // $res = $query2->getResultArray();

        $sql = "SELECT 
        CASE WHEN (r.conn_key IS NULL or r.conn_key::text = '0')  THEN r.fil_no ELSE r.conn_key END AS listafter,
        m.reg_no_display AS reg_no_after,
        CONCAT(m.pet_name, ' Vs ', m.res_name) AS title_after,
        CONCAT(m2.pet_name, ' Vs ', m2.res_name) AS title_first,
        CASE WHEN r.conn_key IS NOT NULL THEN STRING_AGG(r.fil_no::text, ',') ELSE '' END AS connected,
        r.fil_no2 AS disposefirst,
        m2.reg_no_display AS reg_no_first,
        r.ent_dt,
        u.name AS user_after,
        us.section_name AS section_after,
        u2.name AS user_first,
        us2.section_name AS section_first, rgo_updated_by 
        FROM 
            rgo_default r 
        LEFT JOIN 
            main m ON CASE WHEN (r.conn_key IS NULL or r.conn_key::text = '0') THEN r.fil_no ELSE r.conn_key END = m.diary_no 
        LEFT JOIN 
            main m2 ON r.fil_no2 = m2.diary_no 
        LEFT JOIN 
            master.users u ON m.dacode = u.usercode 
        LEFT JOIN 
            master.usersection us ON u.section = us.id 
        LEFT JOIN 
            master.users u2 ON m2.dacode = u2.usercode 
        LEFT JOIN 
            master.usersection us2 ON u2.section = us2.id 
        WHERE 
            rgo_updated_by = '$usercode' 
            --AND remove_def = 'N' 
        GROUP BY 
            r.conn_key, m.reg_no_display, m.pet_name, m.res_name, m2.pet_name, m2.res_name, r.fil_no2, m2.reg_no_display, r.ent_dt, u.name, us.section_name, u2.name, us2.section_name, r.fil_no, rgo_updated_by";

        $result_party = $this->db->query($sql);
        $res = $result_party->getResultArray();

        // echo "<pre>";
        // print_r($res); die;

        if (count($res) >= 1) {
            return $res;
        } else {
            return false;
        }
    }


    function get_case_details(
        $case_type_list = 0,
        $case_number_list = 0,
        $case_year_list = 0,
        $diary_number_list = 0,
        $diary_year_list = 0,
        $case_type_disp = 0,
        $case_number_disp = 0,
        $case_year_disp = 0,
        $diary_number_disp = 0,
        $diary_year_disp = 0
    ) { 

        $builder2 = $this->db->table("main m");
        $builder2->select("m.diary_no as case_diary, m.c_status, concat(m.pet_name,' Vs ',m.res_name) as case_title,dacode");

        if (($case_type_list != 0 && $case_type_list != '') && ($case_number_list != 0 && $case_number_list != '') && ($case_year_list !== 0 && $case_year_list != '')) {

            $builder2->where("NULLIF(split_part(ACTIVE_FIL_NO, '-', 1), '')::INTEGER", $case_type_list);
            $builder2->where('active_reg_year', $case_year_list);
            $builder2->groupStart();
            $builder2->where("NULLIF(split_part(ACTIVE_FIL_NO, '-', 2), '')::INTEGER", $case_number_list);
            $builder2->orWhere("$case_number_list BETWEEN NULLIF(split_part(ACTIVE_FIL_NO, '-', 2), '')::INTEGER AND NULLIF(split_part(ACTIVE_FIL_NO, '-', -1), '')::INTEGER", null, false);
            $builder2->groupEnd();
        } else if (($diary_number_list != 0 && $diary_number_list != '') && ($diary_year_list != 0 && $diary_year_list != '')) {
            // $condition=" m.diary_no=".$diary_number_list.$diary_year_list;
            $builder2->where("m.diary_no", $diary_number_list . $diary_year_list);
        } else if (($case_type_disp != 0 && $case_type_disp != '') && ($case_number_disp != 0 && $case_number_disp != '') && ($case_year_disp != 0 && $case_year_disp != '')) {
        

            $builder2->where("NULLIF(split_part(ACTIVE_FIL_NO, '-', 1), '')::INTEGER", $case_type_disp);
            $builder2->where('active_reg_year', $case_year_disp);
            $builder2->groupStart();
            $builder2->where("NULLIF(split_part(ACTIVE_FIL_NO, '-', 2), '')::INTEGER", $case_number_disp);
            $builder2->orWhere("$case_number_disp BETWEEN NULLIF(split_part(ACTIVE_FIL_NO, '-', 2), '')::INTEGER AND NULLIF(split_part(ACTIVE_FIL_NO, '-', -1), '')::INTEGER", null, false);
            $builder2->groupEnd();
        } else if (($diary_number_disp != 0 && $diary_number_disp != '') && ($diary_year_disp != 0 && $diary_year_disp != '')) {
            // $condition=" m.diary_no=".$diary_number_disp.$diary_year_disp;
            $builder2->where("m.diary_no", $diary_number_disp . $diary_year_disp);
        }

        // echo $builder2->getCompiledSelect(); die();

        // echo $compiledQuery; // Print the full SQL query
        // die(); // Stop further execution to focus on the query
        

        $query2 = $builder2->get();
        $result = $query2->getResultArray();
        if (count($result) >= 1) {
            return $result;
        } else {
            return false;
        }
    }


    function get_conn_details($diarynumber)
    {
        $builder2 = $this->db->table("main");
        $builder2->select("count(diary_no) as conn");
        $builder2->where('conn_key', $diarynumber);
        $builder2->where("diary_no != conn_key::int");
        $query2 = $builder2->get();
        $result = $query2->getResultArray();

        if (count($result) >= 1) {
            return $result;
        } else {
            return false;
        }
    }

    function check_case($list_diary)
    {
        // $sql="select * from rgo_default where fil_no=$list_diary and remove_def='N' group by ent_dt  order by ent_dt desc limit 1";
        // $query = $this->db->query($sql);

        $builder = $this->db->table('rgo_default');
        $builder->where('fil_no', $list_diary);
        $builder->where('remove_def', 'N');
        // $builder->groupBy('ent_dt, fil_no, conn_key');
        $builder->orderBy('ent_dt', 'desc');
        $builder->limit(1);
        $query2 = $builder->get();
        $result = $query2->getResultArray();

        if (count($result) >= 1) {
            return $result;
        } else {
            return false;
        }
    }


    function update_case_HighCourt($list_diary, $dispose_hCourt, $court_type, $connected, $usercode)
    {
        if ($this->check_case($list_diary) == true) {
            //   $sql_history = "insert into rgo_default_history select fil_no,conn_key,reason,fil_no2,remove_def,remove_def_dt,ent_dt,rgo_updated_by,$usercode,now(),hCourt_no,Court_type from rgo_default where fil_no=$list_diary;";

            $subquery = $this->db->table("rgo_default");
            $subquery->select("fil_no,conn_key,reason,fil_no2,remove_def,remove_def_dt,ent_dt,rgo_updated_by,$usercode,now(),hCourt_no,Court_type");
            $subquery->where("fil_no", $list_diary);
            $query = $subquery->get();
            if ($query->getNumRows() >= 1) {
                $insertData = $query->getResultArray();
                $sql_history = $this->db->table('rgo_default_history')->insertBatch($insertData);
            }


            //   $sql_delete = "delete from rgo_default where fil_no=$list_diary;";
            $builder1 = $this->db->table('rgo_default');
            $builder1->where('fil_no', $list_diary);
            $sql_delete = $builder1->delete();
        }

        if ($court_type == 'S') {
            if ($connected == '0') {
                // $sql = "insert into rgo_default(fil_no,fil_no2,ent_dt,rgo_updated_by,court_type) select $list_diary,$dispose_hCourt,now(),$usercode, '$court_type';";
                $data = [
                    // 'fil_no' => $list_diary,
                    // 'fil_no2' => $dispose_hCourt,
                    // 'ent_dt' => 'NOW()',
                    // 'rgo_updated_by' => $usercode,
                    // 'court_type' => $court_type,

                    'fil_no' => (int) $list_diary,
                    'conn_key' => 0,
                    'reason' => '',
                    'fil_no2' => (int)$dispose_hCourt,
                    'remove_def' => '',
                    'remove_def_dt' => 'NOW()',
                    'ent_dt' => 'NOW()',
                    'rgo_updated_by' => (int)$usercode,
                    'hcourt_no' => '',
                    'court_type' => $court_type,
                    'create_modify' => 'NOW()',
                    'updated_on' => 'NOW()',
                    'updated_by' => (int)$usercode,
                    'updated_by_ip' => ''
                ];
                $sql = $this->db->table('rgo_default')->insert($data);
            } else {
                // $sql="insert into rgo_default(fil_no,conn_key,fil_no2,ent_dt,rgo_updated_by,court_type) select diary_no,$list_diary,$dispose_hCourt,now(),$usercode,'$court_type' from main where conn_key=$list_diary;";
                $query = $this->db->table('rgo_default');
                $query->select("diary_no, $list_diary, $dispose_hCourt, NOW(), $usercode, $court_type", false);
                $query->from('main');
                $query->where('conn_key', $list_diary);
                $sql = $this->db->query("INSERT INTO rgo_default(fil_no, conn_key, fil_no2, ent_dt, rgo_updated_by, court_type) " . $query->getCompiledSelect());
            }
        } else {
            if ($connected == '0') {
                //    $sql = "insert into rgo_default(fil_no,hCourt_no,ent_dt,rgo_updated_by,court_type) select $list_diary,'$dispose_hCourt',now(),$usercode,'$court_type';";
                $data = [
                    // 'fil_no' => $list_diary,
                    // 'hCourt_no' => $dispose_hCourt,
                    // 'ent_dt' => 'NOW()',
                    // 'rgo_updated_by' => $usercode,
                    // 'court_type' => $court_type,

                    'fil_no' => (int) $list_diary,
                    'conn_key' => 0,
                    'reason' => '',
                    'fil_no2' => (int)$dispose_hCourt,
                    'remove_def' => '',
                    'remove_def_dt' => 'NOW()',
                    'ent_dt' => 'NOW()',
                    'rgo_updated_by' => (int)$usercode,
                    'hcourt_no' => '',
                    'court_type' => $court_type,
                    'create_modify' => 'NOW()',
                    'updated_on' => 'NOW()',
                    'updated_by' => (int)$usercode,
                    'updated_by_ip' => ''
                ];
                $sql = $this->db->table('rgo_default')->insert($data);
            } else {
                // $sql = "insert into rgo_default(fil_no,conn_key,hCourt_no,ent_dt,rgo_updated_by,court_type) select diary_no,$list_diary,'$dispose_hCourt',now(),$usercode,'$court_type' from main where conn_key=$list_diary;";
                $query = $this->db->table('rgo_default');
                $query->select("diary_no, $list_diary, $dispose_hCourt, NOW(), $usercode, $court_type", false);
                $query->from('main');
                $query->where('conn_key', $list_diary);
                $sql = $this->db->query("INSERT INTO rgo_default(fil_no, conn_key, hCourt_no, ent_dt, rgo_updated_by, court_type) " . $query->getCompiledSelect());
            }
        }

        // $this->db->trans_start();
        if ($this->check_case($list_diary) == true) {
            // $this->db->query($sql_history);
            if ($sql_history < 1) {
                echo "Unable to process the Request. Please contact Computer Cell.";
            }
            // $this->db->query($sql_delete);
            if ($sql_delete < 1) {
                echo "Unable to process the Request. Please contact Computer Cell.";
            }
        }

        // $this->db->query($sql);
        // $this->db->last_query();
        if ($sql >= 1) {
            echo "Case Updated.";
        } else {
            echo "There is some problem. Please contact Computer-Cell.";
        }
        // echo $connected." ".$list_diary." ".$dispose_diary;
    }

    function get_District_State($dstate_id)
    {
        // $sql="SELECT id_no id, Name agency_name FROM state WHERE State_code = (SELECT State_code FROM state WHERE id_no = $dstate_id AND display = 'Y' ) AND display = 'Y' AND Sub_Dist_code =0 AND Village_code =0 AND District_code !=0 order by Name";
        // $query = $this->db->query($sql);

        $builder = $this->db->table('master.state');
        $builder->select('id_no as id, name as agency_name');
        $builder->where('state_code', function ($subquery) use ($dstate_id) {
            $subquery->select('state_code')
                ->from('master.state')
                ->where('id_no', $dstate_id)
                ->where('display', 'Y');
        });
        $builder->where('display', 'Y');
        $builder->where('sub_dist_code', 0);
        $builder->where('village_code', 0);
        $builder->where('district_code !=', 0);
        $builder->orderBy('name');
        $query2 = $builder->get();
        $result = $query2->getResultArray();

        if (count($result) >= 1) {
            return $result;
        } else {
            return false;
        }
    }

    function get_CaseType_Tribunal($state_id)
    {
        // $sql="SELECT lccasecode,type_sname lccasename FROM lc_hc_casetype WHERE display = 'Y' AND cmis_state_id = '$state_id' AND ref_agency_code_id !=0  and  type_sname!='' order by type_sname";
        // $query = $this->db->query($sql);

        $builder = $this->db->table('master.lc_hc_casetype');
        $builder->select('lccasecode,type_sname lccasename');
        $builder->where('display', 'Y');
        $builder->where('cmis_state_id', $state_id);
        $builder->where('type_sname !=', 0);
        $builder->where('ref_agency_code_id !=', 0);
        $builder->orderBy('type_sname');
        $query2 = $builder->get();
        $result = $query2->getResultArray();

        if (count($result) >= 1) {
            return $result;
        } else {
            return false;
        }
    }

    function get_HighCourt_State_bench($state_id, $agency_court)
    {
        // $sql="SELECT id, agency_name, short_agency_name FROM ref_agency_code WHERE is_deleted = 'f' AND agency_or_court = $agency_court AND cmis_state_id = $state_id";
        // $query = $this->db->query($sql);

        $builder = $this->db->table('master.ref_agency_code');
        $builder->select('id, agency_name, short_agency_name');
        $builder->where('is_deleted', 'f');
        $builder->where('cmis_state_id', $state_id);
        $builder->where('agency_or_court', $agency_court);
        $query2 = $builder->get();
        $result = $query2->getResultArray();

        if (count($result) >= 1) {
            return $result;
        } else {
            return false;
        }
    }

    function get_CaseType_State_bench($state_id, $court_type)
    {
        // $sql="SELECT lccasecode, type_sname lccasename FROM lc_hc_casetype WHERE display = 'Y' AND cmis_state_id = $state_id AND corttyp = '$court_type' and  type_sname!='' order by lccasename";
        // $query = $this->db->query($sql);


        $builder = $this->db->table('master.lc_hc_casetype');
        $builder->select('lccasecode,type_sname lccasename');
        $builder->where('display', 'Y');
        $builder->where('cmis_state_id', $state_id);
        $builder->where('type_sname !=', '');
        $builder->where('corttyp', $court_type);
        $builder->orderBy('lccasename');
        $query2 = $builder->get();
        $result = $query2->getResultArray();

        if (count($result) >= 1) {
            return $result;
        } else {
            return false;
        }
    }

    function get_Restrict_Cases_History($list_diary)
    {

        $builder = $this->db->table('rgo_default rg');
        $builder->select([
            'm.diary_no',
            'rg.fil_no2',
            "CONCAT(m.reg_no_display, '@ D.No.', SUBSTRING(m.diary_no::text FROM 1 FOR LENGTH(m.diary_no::text) - 4), '/', SUBSTRING(m.diary_no::text FROM -4)) AS mcaseno",
            'rg.court_type',
            'rg.hcourt_no',
            "CASE WHEN rg.court_type != 'S' OR rg.court_type IS NOT NULL THEN SPLIT_PART(rg.hcourt_no, '~', 4) ELSE NULL END AS lc_caseno",
            "CASE WHEN rg.court_type != 'S' OR rg.court_type IS NOT NULL THEN SPLIT_PART(rg.hcourt_no, '~', 5) ELSE NULL END AS lc_caseyear",
            "CASE WHEN rg.court_type != 'A' THEN
                    (SELECT type_sname
                    FROM master.lc_hc_casetype
                    WHERE display = 'Y'
                    AND corttyp = rg.court_type
                    AND type_sname != ''
                    ORDER BY type_sname limit 1)
                ELSE
                    (SELECT type_sname
                    FROM master.lc_hc_casetype
                    WHERE display = 'Y'
                    AND ref_agency_code_id != 0
                    AND type_sname != ''
                    ORDER BY type_sname limit 1)
                END AS lc_casetype",
            "CASE WHEN rg.court_type = 'S' OR rg.court_type IS NULL THEN
                    (SELECT CONCAT(reg_no_display, '@ D.No.', SUBSTRING(diary_no::text FROM 1 FOR LENGTH(diary_no::text) - 4), '/', SUBSTRING(diary_no::text FROM -4))
                    FROM main
                    WHERE diary_no = fil_no2)
                ELSE NULL
                END AS sc_caseno"
        ]);
        $builder->join('main m', 'm.diary_no = rg.fil_no');
        $builder->where('rg.fil_no', $list_diary);
        $builder->where('rg.remove_def', 'N');

        // $compiledQuery = $builder->getCompiledSelect();
        // echo $compiledQuery;
        // die(); 

        $query2 = $builder->get();
        $result = $query2->getResultArray();

        if (count($result) >= 1) {
            return $result;
        } else {
            return false;
        }
    }

    function delete_Restricted_Case($list_diary, $usercode)
    {
        $del_return = 'F';
        if ($this->check_case($list_diary) == true) {

            $subquery = $this->db->table("rgo_default");
            // $subquery->select("fil_no,conn_key,reason,fil_no2,remove_def,remove_def_dt,ent_dt,rgo_updated_by,$usercode,now(),hcourt_no,court_type");
            $subquery->select('*');
            $subquery->where("fil_no", $list_diary);
            // $queryString = $subquery->getCompiledSelect();
            // echo $queryString;
            // exit();
            $query = $subquery->get();
            if ($query->getNumRows() >= 1) {
                $insertData = $query->getResultArray();
                $insData = [];
                foreach ($insertData as $val) {
                    $insData[] = [
                        'fil_no' => (int) $insertData[0]['fil_no'],
                        'conn_key' => (int) $insertData[0]['conn_key'],
                        'reason' => $insertData[0]['reason'],
                        'fil_no2' => (int)$insertData[0]['fil_no2'],
                        'remove_def' => $insertData[0]['remove_def'],
                        'remove_def_dt' => $insertData[0]['remove_def_dt'],
                        'ent_dt' => $insertData[0]['ent_dt'],
                        'rgo_updated_by' => (int)$insertData[0]['rgo_updated_by'],
                        'hcourt_no' => $insertData[0]['hcourt_no'],
                        'court_type' => $insertData[0]['court_type'],
                        'create_modify' => $insertData[0]['create_modify'],
                        'removed_by' => (int)$usercode,
                        'removed_on' => 'NOW()',
                        'updated_on' => $insertData[0]['updated_on'],
                        'updated_by' => (int)$insertData[0]['updated_by'],
                        'updated_by_ip' => $insertData[0]['updated_by_ip'],

                    ];
                }
                // echo "<pre>"; print_r($insData); die;
                $sql_history = $this->db->table('rgo_default_history')->insertBatch($insData);
            }

            $builder1 = $this->db->table('rgo_default');
            $builder1->where('fil_no', $list_diary);
            $sql_delete = $builder1->delete();
        }

        if ($this->check_case($list_diary) == true) {
            if ($sql_history < 1) {
                echo "Unable to process the Request. Please contact Computer Cell.";
            } else {
                $del_return = 'T';
            }
        } else {
            // $this->db->query($sql_delete);
            if ($sql_delete < 1) {
                echo "Unable to process the Request. Please contact Computer Cell.";
            } else {
                $del_return = 'T';
            }
        }
        // echo $del_return; die;
        return $del_return;
    }

    function get_HighCourt_State()
    {
        // $sql="SELECT id_no, Name FROM state WHERE District_code =0 AND Sub_Dist_code =0 AND Village_code =0 AND display = 'Y' AND sci_state_id !=0 ORDER BY Name";
        // $query = $this->db->query($sql);

        $builder = $this->db->table('master.state');
        $builder->select('id_no, name');
        $builder->where('display', 'Y');
        $builder->where('sub_dist_code', 0);
        $builder->where('village_code', 0);
        $builder->where('district_code', 0);
        $builder->where('sci_state_id !=', 0);
        $builder->orderBy('name');
        $query2 = $builder->get();
        $result = $query2->getResultArray();

        if (count($result) >= 1) {
            return $result;
        } else {
            return false;
        }
    }
}
