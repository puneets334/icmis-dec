<?php

namespace App\Models\Common;

use CodeIgniter\Model;

class Dropdown_list_model extends Model
{

    public function __construct()
    {
        parent::__construct();
    }


    public function get_diary_details_by_diary_no($diary_no)
    {
        $builder = $this->db->table("main");
        $builder->select("main.*");
        $builder->WHERE('diary_no', $diary_no);
        $query = $builder->get(1);
        // echo $this->db->getLastQuery();die;
        if ($query->getNumRows() >= 1) {
            $get_main_table = $query->getRowArray();
            return $get_main_table;
        } else {
            $builder2 = $this->db->table("main_a");
            $builder2->select("*");
            $builder2->WHERE('diary_no', $diary_no);
            $query2 = $builder2->get(1);
            if ($query2->getNumRows() >= 1) {
                $get_main_table2 = $query2->getRowArray();
                return $get_main_table2;
            } else {
                return false;
            }
        }
    }

    public function get_case_details_by_case_no($ct, $cn, $cy)
    {

        // $builder = $this->db->table('main');
        // $builder->select([
        //     "substring(diary_no::text, 1, char_length(diary_no::text) - 4) as dn",
        //     "substring(diary_no::text, char_length(diary_no::text) - 3, 4) as dy"
        // ]);
        // $builder->where("split_part(nullif(fil_no, ''), '-', 1)::INTEGER", $ct);
        // $builder->where("$cn BETWEEN split_part(nullif(fil_no, ''), '-', 2)::INTEGER AND split_part(nullif(fil_no, ''), '-', -1)::INTEGER");
        // $builder->groupStart()
        //     ->groupStart()
        //     ->where("reg_year_mh", 0)
        //     ->orWhere("fil_dt > '2017-05-10'")
        //     ->groupEnd()
        //     ->where("EXTRACT(YEAR FROM fil_dt)", $cy)
        //     ->groupEnd();

        // // Open group for the second OR condition
        // $builder->orGroupStart()
        //     ->where("split_part(nullif(fil_no_fh, ''), '-', 1)::INTEGER", $ct)
        //     ->where("$cn BETWEEN split_part(nullif(fil_no_fh, ''), '-', 2)::INTEGER AND split_part(nullif(fil_no_fh, ''), '-', -1)::INTEGER")
        //     ->where("reg_year_fh", 0)
        //     ->where("EXTRACT(YEAR FROM fil_dt_fh)", $cy)
        //     ->groupEnd();

        $builder = $this->db->table('main');
        // First condition group
        $builder->groupStart()
            ->where("(CASE 
                WHEN SPLIT_PART(fil_no, '-', 1) ~ '^[0-9]+$' 
                THEN SPLIT_PART(fil_no, '-', 1)::INTEGER 
                ELSE 0 
            END)", $ct)
            ->where("CAST('{$cn}' AS INTEGER) BETWEEN 
                (CASE 
                    WHEN SPLIT_PART(fil_no, '-', 2) ~ '^[0-9]+$' 
                    THEN SPLIT_PART(fil_no, '-', 2)::INTEGER 
                    ELSE 0 
                END) 
                AND 
                (CASE 
                    WHEN SPLIT_PART(fil_no, '-', -1) ~ '^[0-9]+$' 
                    THEN SPLIT_PART(fil_no, '-', -1)::INTEGER 
                    ELSE 0 
                END)")
            ->groupStart()
                ->where('reg_year_mh', 0)
                ->orWhere('fil_dt >', '2017-05-10')
            ->groupEnd()
            ->where("EXTRACT(YEAR FROM fil_dt) =", $cy)
        ->groupEnd();

        // Second condition group (OR condition)
        $builder->orGroupStart()
            ->where("(CASE 
                WHEN SPLIT_PART(fil_no_fh, '-', 1) ~ '^[0-9]+$' 
                THEN SPLIT_PART(fil_no_fh, '-', 1)::INTEGER 
                ELSE 0 
            END)", $ct)
            ->where("CAST('{$cn}' AS INTEGER) BETWEEN 
                (CASE 
                    WHEN SPLIT_PART(fil_no_fh, '-', 2) ~ '^[0-9]+$' 
                    THEN SPLIT_PART(fil_no_fh, '-', 2)::INTEGER 
                    ELSE 0 
                END) 
                AND 
                (CASE 
                    WHEN SPLIT_PART(fil_no_fh, '-', -1) ~ '^[0-9]+$' 
                    THEN SPLIT_PART(fil_no_fh, '-', -1)::INTEGER 
                    ELSE 0 
                END)")
            ->where('reg_year_fh', 0)
            ->where("EXTRACT(YEAR FROM fil_dt_fh) =", $cy)
        ->groupEnd();

        // Selecting specific columns
        $builder->select([
            "SUBSTRING(diary_no::TEXT FROM 1 FOR CHAR_LENGTH(diary_no::TEXT) - 4) AS dn",
            "SUBSTRING(diary_no::TEXT FROM CHAR_LENGTH(diary_no::TEXT) - 3 FOR 4) AS dy"
        ]);

        // pr($builder->getCompiledSelect());die;
        $result = $builder->get()->getRowArray();

        if (!empty($result)) {
            $diary_no = $result['dn'].$result['dy'];

            $get_diary =  $this->get_diary_details_by_diary_no($diary_no);

            $get_diary['dn'] = $result['dn'];
            $get_diary['dy'] = $result['dy'];
            return $get_diary;
        } else {

            $builder = $this->db->table('main_casetype_history h');
            $builder->select([
                "substring(h.diary_no::text, 1, char_length(h.diary_no::text) - 4) as dn",
                "substring(h.diary_no::text, char_length(h.diary_no::text) - 3, 4) as dy",
                "CASE WHEN h.new_registration_number != '' THEN split_part(h.new_registration_number, '-', 1) ELSE '' END as ct1",
                "CASE WHEN h.new_registration_number != '' THEN split_part(h.new_registration_number, '-', 2) ELSE '' END as crf1",
                "CASE WHEN h.new_registration_number != '' THEN split_part(h.new_registration_number, '-', -1) ELSE '' END as crl1"
            ]);
            $builder->where("((split_part(nullif(h.new_registration_number, ''), '-', 1)::int = $ct AND $cn BETWEEN split_part(nullif(h.new_registration_number, ''), '-', 2)::INTEGER AND split_part(nullif(h.new_registration_number, ''), '-', -1)::INTEGER AND h.new_registration_year = $cy) OR (split_part(nullif(h.old_registration_number, ''), '-', 1) = '$ct' AND $cn BETWEEN split_part(nullif(h.old_registration_number, ''), '-', 2)::INTEGER AND split_part(nullif(h.old_registration_number, ''), '-', -1)::INTEGER AND h.old_registration_year = $cy)) AND h.is_deleted = 'f'");

            // pr($builder->getCompiledSelect());

            $result = $builder->get()->getRowArray();

            if ($result) {

                $diary_no = $result['dn'].$result['dy'];

                $get_diary = $this->get_diary_details_by_diary_no($diary_no);

                $get_diary['dn'] = $result['dn'];
                $get_diary['dy'] = $result['dy'];
                return $get_diary;

                // Fetch casetype description
                // $builder = $this->db->table('casetype');
                // $builder->select('short_description');
                // $builder->where('casecode', $ct);
                // $builder->where('display', 'Y');
                // $casetype = $builder->get()->getRowArray();

                // if ($casetype) {
                //     $short_description = $casetype['short_description'];
                //     $t_slpcc = "{$short_description} {$result['crf1']} - {$result['crl1']} / $cy";
                // }
            } else {
                return false;
            }
        }
        
        return false;
    }

    public function getDiaryDetails($ct, $cn, $cy)
    {
        $builder = $this->db->table('main');
        $builder->select("SUBSTRING(CAST(diary_no AS VARCHAR), 1, LENGTH(CAST(diary_no AS VARCHAR)) - 4) as dn, 
                  SUBSTRING(CAST(diary_no AS VARCHAR), LENGTH(CAST(diary_no AS VARCHAR)) - 3, 4) as dy");

        // Main where condition
        $builder->groupStart()
            ->where("SPLIT_PART(fil_no, '-', 1)", $ct)
            ->where("CAST({$cn} AS INTEGER) BETWEEN SPLIT_PART(fil_no, '-', 2)::INT AND SPLIT_PART(fil_no, '-', 3)::INT")
            ->groupStart()
            ->where("(reg_year_mh = 0 AND EXTRACT(YEAR FROM fil_dt) = {$cy})", null, false)
            ->orWhere("reg_year_mh", $cy)
            ->groupEnd()
            ->groupEnd()

            // OR condition
            ->orGroupStart()
            ->where("SPLIT_PART(fil_no_fh, '-', 1)", $ct)
            ->where("CAST({$cn} AS INTEGER) BETWEEN SPLIT_PART(fil_no_fh, '-', 2)::INT AND SPLIT_PART(fil_no_fh, '-', 3)::INT")
            ->groupStart()
            ->where("(reg_year_fh = 0 AND EXTRACT(YEAR FROM fil_dt_fh) = {$cy})", null, false)
            ->orWhere("reg_year_fh", $cy)
            ->groupEnd()
            ->groupEnd();

        $query = $builder->get();
        // echo $this->db->getLastQuery();die;
        $result = $query->getRowArray();
        return $result;
        // if ($result) {
        //     $_REQUEST['dno'] = $result['dn'] . $result['dy'];
        // } else {
        //     $_REQUEST['dno'] = 0;
        // }
    }

    public function selectPassword($loginid)
    {
        $builder = $this->db->table("master.users");
        $builder->select("*");
        $builder->WHERE('empid', $loginid);
        $builder->WHERE('display', 'Y');
        $query = $builder->get(1);
        if ($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        } else {
            return false;
        }
    }
    public function high_courts($hc_id = null)
    {
        $table_name = 'm_tbl_high_courts_bench';
        $file = env('Json_master_table') . $table_name . '.json';
        if (file_exists($file)) {
            $url = base_url('/' . $file);
            $json = file_get_contents($url);
            $json = file_get_contents($url);
            $json_data = json_decode($json, true);
            $json_array = false;
            //  in_array($id,$json_data);
            if ($json_data) {
                if (!empty($hc_id) && $hc_id != null) {
                    foreach ($json_data[$table_name] as $subArray) {
                        if (isset($subArray['hc_id']) && $subArray['hc_id'] == $hc_id) {
                            $json_array = $subArray;
                            break;
                        }
                    }
                } else {
                    $json_array = $json_data;
                }
            } else {
                $json_array;
            }
            return $json_array;
        } else {
            echo $table_name . ' table does not exist';
            exit();
        }
    }
    function hc_bench($hc_id, $type = 1)
    {
        if ($type == 1) //ref_agency_code
            $table_name = 'm_tbl_high_courts_bench';
        $file = env('Json_master_table') . $table_name . '.json';
        if (file_exists($file)) {
            $url = base_url('/' . $file);
            $json = file_get_contents($url);
            $json_data = json_decode($json, true);
            $json_array = false;
            //  in_array($id,$json_data);
            if ($json_data) {
                if (!empty($hc_id) && $hc_id != null) {
                    foreach ($json_data[$table_name] as $subArray) {
                        if (isset($subArray['hc_id']) && $subArray['hc_id'] == $hc_id) {
                            $json_array = $subArray;
                            break;
                        }
                    }
                } else {
                    $json_array = $json_data;
                }
            } else {
                $json_array;
            }
            return $json_array;
        } else {
            echo $table_name . ' table does not exist';
            exit();
        }
    }
    function get_ref_agency_code($cmis_state_id = null, $court_type = null)
    {
        if (!empty($court_type) && $court_type != null && $court_type == 3) {
            return $this->get_district_court_bench($cmis_state_id, $court_type);
        }
        $table_name = 'ref_agency_code';
        $file = env('Json_master_table') . $table_name . '.json';
        if (file_exists($file)) {
            $url = base_url('/' . $file);
            $json = file_get_contents($url, true);
            $json_data = json_decode($json, true);
            $json_array = false;
            if ($json_data) {
                $json_array = array_filter($json_data, function ($item) use ($cmis_state_id, $court_type) {
                    return $this->filter_ref_agency_code($item, $cmis_state_id, $court_type);
                });
            }
        }
        return $json_array;
    }

    function filter_ref_agency_code($item, $cmis_state_id, $court_type)
    {
        if (!empty($cmis_state_id) && $cmis_state_id != null && !empty($court_type) && $court_type == 4) {
            $array = $item['cmis_state_id'] == $cmis_state_id && $item['agency_or_court'] == $court_type &&  $item['is_deleted'] === 'f' &&  $item['is_main'] === 'f';
        } else if (!empty($cmis_state_id) && $cmis_state_id != null && $court_type == 5) {
            $array = $item['cmis_state_id'] == $cmis_state_id && ($item['agency_or_court'] == 2 || $item['agency_or_court'] == 5 || $item['agency_or_court'] == 6) &&  $item['is_deleted'] === 'f' &&  $item['is_main'] === 'f';
        } else if (!empty($cmis_state_id) && $cmis_state_id != null && $court_type == 1) {
            $array = $item['cmis_state_id'] == $cmis_state_id && $item['agency_or_court'] == 1 &&  $item['is_deleted'] === 'f' &&  $item['is_main'] === 'f';
        } else {
            $array = $item['is_deleted'] === 'f' &&  $item['is_main'] === 'f';
        }
        return  $array;
    }


    function icmis_states($id_no = null)
    {
        $builder = $this->db->table("master.state");
        $builder->select("id_no, name");
        $builder->WHERE('district_code', 0);
        $builder->WHERE('sub_dist_code', 0);
        $builder->WHERE('village_code', 0);
        $builder->WHERE('sci_state_id !=', 0);
        $builder->WHERE('display', 'Y');
        if (!empty($id_no) && $id_no != null) {
            $builder->WHERE('id_no', $id_no);
        }
        $builder->orderBy('name', 'ASC');
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        } else {
            return false;
        }
    }
    function filterByMultipleCriteria($item, $id_no)
    {
        if (!empty($targetCmisStateId) && $targetCmisStateId != null) {
            return $item['district_code'] == 0 && $item['sub_dist_code'] == 0 && $item['village_code'] == 0 && $item['display'] == 'Y' && $item['sci_state_id'] != 0 && $item['id_no'] == $id_no;
        } else {
            $array = $item['district_code'] == 0 && $item['sub_dist_code'] == 0 && $item['village_code'] == 0 && $item['display'] == 'Y' && $item['sci_state_id'] != 0;
            return  $array;
        }
    }
    public function get_district_court_bench($id_no, $court_type)
    {
        if ($court_type == 3) {
            if ($id_no == '490506') {
                $sql = "SELECT id_no as id,name as agency_name
            FROM master.state s join master.delhi_district_court d on s.state_code=d.state_code and s.district_code=d.district_code WHERE s.State_code = (SELECT State_code FROM master.state WHERE 
                id_no =  '$id_no' AND display = 'Y' ) AND s.display = 'Y' AND s.sub_dist_code =0 AND s.village_code =0 AND s.district_code !=0 order by trim(name)";
            } else {
                $sql = "SELECT id_no as id, name as agency_name FROM master.state WHERE state_code = (SELECT state_code FROM master.state WHERE 
        id_no =  '$id_no' AND display = 'Y' ) AND display = 'Y'
          AND sub_dist_code =0 AND village_code =0 AND district_code !=0 order by trim(name)";
            }
            $query = $this->db->query($sql);
            if ($query->getNumRows() > 0) {
                $result = $query->getResultArray();
                //echo '<pre>';print_r($result);exit();
                return $result;
            } else {
                return 0;
            }
        }
    }
    function get_case_type($role = null, $nature_sci = null)
    {
        if (!empty($role) && $role != null && $role == 'filing') {
            if (!empty($nature_sci) && $nature_sci != null && $nature_sci == 'nature_sci') {
                $casecode = [9999, 15, 16];
            } else {
                $casecode = [13, 14, 9999, 15, 16];
            }
        } else {
            $casecode = [1, 2, 3, 4, 5, 6, 7, 8, 11, 12, 13, 14, 15, 16, 17, 18, 21, 22, 23, 24, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 40, 41, 9999];
        }
        $builder = $this->db->table("master.casetype");
        $builder->select("casecode,casename,skey");
        $builder->WHERE('display', 'Y');
        $builder->whereNotIn('casecode', $casecode);
        $builder->orderBy('casecode ASC,casename ASC');
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        } else {
            return false;
        }
    }
    function get_usersection($id = null)
    {
        $table_name = 'usersection';
        $file = env('Json_master_table') . $table_name . '.json';
        if (file_exists($file)) {
            // $url = base_url('/' . $file);
            $json = file_get_contents($file, true);
            $json_data = json_decode($json, true);
            $json_array = false;
            if ($json_data) {
                $json_array = array_filter($json_data, function ($item) use ($id) {
                    return $this->filter_usersection($item, $id);
                });
            }
        }
        // echo '<pre>';print_r($json_array);exit();
        return $json_array;
    }

    function filter_usersection($item, $id)
    {
        if (!empty($id) && $id != null) {
            $array = $item['id'] == $id && $item['isda'] == 'Y' && ($item['id'] != 10 || $item['id'] != 76);
        } else {
            $array = $item['isda'] == 'Y' && ($item['id'] != 10 || $item['id'] != 76) || $item['id'] == 40;
        }

        return  $array;
    }
    public  function get_next_date($diary_no)
    {
        $sql = "select next_dt from( select diary_no,next_dt from heardt where main_supp_flag in (1,2) and diary_no='$diary_no' 
union select diary_no,next_dt from last_heardt where main_supp_flag in(1,2) and (bench_flag is null or bench_flag='') 
and diary_no='$diary_no')aa where next_dt in(select listing_date from defective_chamber_listing where display='Y')";
        $query = $this->db->query($sql);
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return false;
        }
    }
    function getPincodeDetails($pincode)
    {
        $builder = $this->db->table("master.pincode_district_mapping");
        $builder->select("*");
        $builder->WHERE('pincode', $pincode);
        $builder->orderBy('taluk_name', 'ASC');
        $query = $builder->get(1);
        if ($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        } else {
            return false;
        }
    }
    public function getCountryList()
    {
        $builder = $this->db->table("master.country");
        $builder->select("id, country_name, country_code, short_description");
        $builder->WHERE('display', 'Y');
        $builder->orderBy('country_name', 'ASC');
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        } else {
            return false;
        }
    }
    function get_address_state_list()
    {
        $builder = $this->db->table("master.state");
        $builder->select("id_no as cmis_state_id, name as agency_state,state_code", false);
        $builder->WHERE('district_code =0 AND sub_dist_code =0 AND village_code =0 AND display = \'Y\' AND sci_state_id !=0', NULL, false);
        $builder->orderBy('name', 'ASC');
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        } else {
            return false;
        }
    }
    function get_districts_list($state_id)
    {
        $builder = $this->db->table("master.state st1");
        $builder->JOIN('master.state st2', 'st1.state_code = st2.state_code');
        $builder->select("st2.id_no,st2.name");
        $builder->WHERE('st1.id_no', $state_id);
        $builder->WHERE('st2.district_code !=0');
        $builder->WHERE('st2.sub_dist_code = 0');
        $builder->WHERE('st2.village_code = 0');
        $builder->WHERE('st2.display', 'Y');
        $builder->WHERE('st1.display', 'Y');
        $builder->WHERE('st2.display', 'Y');
        $builder->orderBy('st2.name', 'ASC');
        $query = $builder->get();
        //$query=$this->db->getLastQuery();echo (string) $query;exit();
        if ($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        } else {
            return false;
        }
    }

    function get_subcategory_list($main_category_id)
    {
        $builder = $this->db->table("master.submaster");
        $builder->select("id,sub_name4,category_sc_old");
        $builder->WHERE('subcode1', $main_category_id);
        $builder->WHERE("subcode2 != '0'");
        $builder->WHERE('flag', 's');
        $builder->WHERE('flag_use', 'S');
        $builder->WHERE('display', 'Y');
        $query = $builder->get();
        //$query=$this->db->getLastQuery();echo (string) $query;exit();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return false;
        }
    }

    function get_fixed_for_list()
    {
        $builder = $this->db->table("master.master_fixedfor");
        $builder->select("id,fixed_for_desc");
        $builder->WHERE("displayat LIKE '%SCR%'");
        $builder->orderBy('id', 'ASC');
        $query = $builder->get();
        //$query=$this->db->getLastQuery();echo (string) $query;exit();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return false;
        }
    }

    function get_listed_before($table_name, $column_name = null, $start_from = null, $end_to = null)
    {
        $file = env('Json_master_table') . $table_name . '.json';
        if (file_exists($file)) {
            $url = base_url('/' . $file);
            $json = file_get_contents($url, true);
            $json_data = json_decode($json, true);
            $json_array = false;
            if ($json_data) {
                $json_array = array_filter($json_data, function ($item) use ($column_name, $start_from, $end_to) {
                    return $this->filter_listed_before($item, $column_name, $start_from, $end_to);
                });
            }
        }
        return $json_array;
    }


    public function get_court_type_list($id = null)
    {
        $builder = $this->db->table("master.m_from_court");
        $builder->select("id,court_name");
        $builder->WHERE('display', 'Y');
        if (!empty($id) && $id != null) {
            $builder->WHERE('id', $id);
        }
        $builder->orderBy('order_by');
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    function get_adv_name($p_r_advt, $advno, $advyr = null, $state_id = null)
    {
        $builder = $this->db->table("master.bar");
        $builder->select("name,mobile,email,bar_id,DATE_PART('year',enroll_date) as enroll_year");
        if ($p_r_advt == 'A') {
            $builder->WHERE('aor_code', $advno);
            $builder->WHERE('if_aor', 'Y');
        } else {
            $builder->WHERE('enroll_no', $advno);
            if (!empty($advyr) && $advyr != null) {
                $builder->WHERE("DATE_PART('year',enroll_date)", $advyr);
            }
            if (!empty($state_id) && $state_id != null) {
                $builder->WHERE('state_id', $state_id);
            }
        }
        $builder->WHERE('isdead', 'N');
        $builder->orderBy('name', 'ASC');
        $query = $builder->get(1);
        if ($query->getNumRows() >= 1) {
            return $result = $query->getRowArray();
        } else {
            return false;
        }
    }

    function filter_listed_before($item, $column_name = null, $start_from = null, $end_to = null)
    {
        $array = $item[$column_name] >= $start_from && $item[$column_name] <= $end_to;
        return  $array;
    }

    function get_provision_of_law_list($case_group)
    {
        $case_group = trim($case_group);
        $table_name = 'caselaw';
        $file = env('Json_master_table') . $table_name . '.json';
        if (file_exists($file)) {
            $url = base_url('/' . $file);
            $json = file_get_contents($url, true);
            $json_data = json_decode($json, true);
            $json_array = false;
            if ($json_data) {
                $json_array = array_filter($json_data, function ($item) use ($case_group) {
                    return $item['display'] === 'Y' && trim($item['nature']) == $case_group;
                });
            }
        }
        return $json_array;
    }

    function get_police_station_list($state_id, $district_id)
    {

        $table_name = 'police';
        $file = env('Json_master_table') . $table_name . '.json';
        if (file_exists($file)) {

            $url = base_url('/' . $file);
            $json = file_get_contents($url, true);
            $json_data = json_decode($json, true);
            $json_array = false;
            if ($json_data) {
                $json_array = array_filter($json_data, function ($item) use ($state_id, $district_id) {
                    return $item['cmis_state_id'] == $state_id && trim($item['cmis_district_id']) == $district_id && $item['display'] === 'Y';
                });
            }
            return $json_array;
        } else {
            echo $table_name . ' table does not exist';
            exit();
        }
    }

    function get_case_type_court($state_id, $court_type)
    {
        $corType = "";
        if ($court_type == 4) { // supreme court
            $table_name = 'casetype';
        } else {
            if ($court_type == 1) {
                $corType = 'H';
            } elseif ($court_type == 3) {
                $corType = 'L';
            }

            $table_name = 'lc_hc_casetype';
        }
        $file = env('Json_master_table') . $table_name . '.json';
        if (file_exists($file)) {
            $url = base_url('/' . $file);
            $json = file_get_contents($url, true);
            $json_data = json_decode($json, true);
            $json_array = false;

            if ($json_data) {
                if ($court_type == 4) {
                    $json_array = array_filter($json_data, function ($item) use ($state_id, $court_type) {
                        return $item['display'] === 'Y';
                    });
                } else {
                    if ($court_type == 5) {
                        $json_array = array_filter($json_data, function ($item) use ($state_id) {
                            return $item['cmis_state_id'] == $state_id && trim($item['ref_agency_code_id']) != 0 && $item['display'] === 'Y' && $item['type_sname'] != "";
                        });
                    } else {
                        $json_array = array_filter($json_data, function ($item) use ($state_id, $corType) {
                            return $item['cmis_state_id'] == $state_id && trim($item['corttyp']) == $corType && $item['display'] === 'Y' && $item['type_sname'] != "";
                        });
                    }
                }
            }
            if ($court_type == 4) {
                $supreme_court_casetypes = array(
                    array('casecode' => 50, 'casename' => 'WRIT NOTIFICATION NO.(WNN)', 'skey' => 'WNN'),
                    array('casecode' => 51, 'casename' => 'ARBITRATION REFERENCE NO.(ARN)', 'skey' => 'ARN')
                );

                $json_array = array_merge($json_array, $supreme_court_casetypes);
            }

            return $json_array;
        } else {
            echo $table_name . ' table does not exist';
            exit();
        }
    }

    function get_all_judges($state_id, $court_type)
    {
        if ($court_type == 4) { // supreme court
            $table_name = 'judge';
        } else {
            $table_name = 'org_lower_court_judges';
        }
        $display = 'Y';
        $file = env('Json_master_table') . $table_name . '.json';
        if (file_exists($file)) {
            $url = base_url('/' . $file);
            $json = file_get_contents($url, true);
            $json_data = json_decode($json, true);
            $json_array = false;

            if ($json_data) {
                if ($court_type == 4) {
                    $json_array = array_filter($json_data, function ($item) use ($display) {
                        return $item['display'] === $display;
                    });
                } else {
                    $json_array = array_filter($json_data, function ($item) use ($state_id) {
                        return $item['cmis_state_id'] == $state_id &&  $item['is_deleted'] === 'f';
                    });
                }
            }
            return $json_array;
        } else {
            echo $table_name . ' table does not exist';
            exit();
        }
    }


    function get_all_rtocode($state_id)
    {
        $table_name = 'rto';
        $file = env('Json_master_table') . $table_name . '.json';
        if (file_exists($file)) {
            $url = base_url('/' . $file);
            $json = file_get_contents($url, true);
            $json_data = json_decode($json, true);
            $json_array = false;
            if ($json_data) {
                $json_array = array_filter($json_data, function ($item) use ($state_id) {
                    return $item['state'] == $state_id && $item['display'] === 'Y';
                });
            }
            return $json_array;
        } else {
            echo $table_name . ' table does not exist';
            exit();
        }
    }

    function get_databy_rto_id($rto_id)
    {
        $table_name = 'rto';
        $file = env('Json_master_table') . $table_name . '.json';
        if (file_exists($file)) {
            $url = base_url('/' . $file);
            $json = file_get_contents($url, true);
            $json_data = json_decode($json, true);
            $json_array = false;
            if ($json_data) {
                $json_array = array_filter($json_data, function ($item) use ($rto_id) {
                    return $item['id'] == $rto_id && $item['display'] === 'Y';
                });
            }
            return array_values($json_array);
        } else {
            echo $table_name . ' table does not exist';
            exit();
        }
    }

    public function get_all_court_type_list($id = null)
    {
        $builder = $this->db->table("master.m_from_court");
        $builder->select("id,court_name");

        if (!empty($id) && $id != null) {
            if ($id == 4) {
                $all_ids = ['4'];
            } elseif ($id == 1) {
                $all_ids = ['1', '4'];
            } elseif ($id == 3) {
                $all_ids = ['1', '4', '3'];
            } elseif ($id == 5) {
                $all_ids = ['1', '4', '5'];
            }
            $builder->whereIn('id', $all_ids);
        }
        //$builder->orderBy('order_by');
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }
    public function get_case_type_caveat($court_type = null)
    {
        $builder = $this->db->table("master.casetype");
        $builder->select("casecode,casename");
        $builder->WHERE('display', 'Y');
        if ((!empty($court_type) && $court_type != null) && $court_type == 4) {
            $builder->whereIn('casecode', [5, 6, 24]);
        } else {
            $builder->whereNotIn('casecode', [9999, 15, 16]);
        }
        $builder->orderBy('casecode ASC,casename ASC');
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }
    public function getHighCourtData($params = array())
    {
        $output = false;
        if (isset($params) && !empty($params) && is_array($params)) {
            if (isset($params['court_type']) && !empty($params['court_type'])) {
                $court_type = (int)$params['court_type'];
                switch ($court_type) {
                    case 3:
                        $output = $this->get_district_court_bench($params['cmis_state_id'], $court_type);
                        break;
                    case 1:
                        $builder = $this->db->table('master.ref_agency_code');
                        $builder->select('id,agency_name,short_agency_name');
                        $builder->where('is_deleted', 'f');
                        $builder->where('agency_or_court', '1');
                        $builder->where('cmis_state_id', $params['cmis_state_id']);
                        $builder->orderBy('agency_name', 'ASC');
                        $query = $builder->get();
                        $output = $query->getResultArray();
                        break;
                    case 4:
                        $builder = $this->db->table('master.ref_agency_code');
                        $builder->select('id,agency_name,short_agency_name');
                        $builder->where('is_deleted', 'f');
                        $builder->where('cmis_state_id', $params['cmis_state_id']);
                        $builder->where('agency_or_court', $params['court_type']);
                        $builder->orderBy('agency_name', 'ASC');
                        $query = $builder->get();
                        $output = $query->getResultArray();
                        break;
                    case 5:
                        $builder = $this->db->table('master.ref_agency_code');
                        $builder->select('id,agency_name,short_agency_name');
                        $builder->where('is_deleted', 'f');
                        $builder->whereIn('agency_or_court', ['2', '5', '6']);
                        $builder->where('cmis_state_id', $params['cmis_state_id']);
                        /*if (isset($params['bench_id']) && !empty($params['bench_id'])) {
                            $this->db->where('id', $params['bench_id']);
                        }*/
                        $builder->orderBy('agency_name', 'ASC');
                        $query = $builder->get();
                        $output = $query->getResultArray();
                        break;
                    default:
                        $output = false;
                }
            }
        }
        return $output;
    }


    function get_copy_category()
    {
        $table_name = 'copy_category';
        $file = env('Json_master_table') . $table_name . '.json';
        $to_date = "";
        if (file_exists($file)) {
            $url = base_url('/' . $file);
            $json = file_get_contents($url, true);
            $json_data = json_decode($json, true);
            $json_array = false;
            if ($json_data) {
                $json_array = array_filter($json_data, function ($item) use ($to_date) {
                    return $item['to_date'] == $to_date;
                });
            }
            return $json_array;
        } else {
            echo $table_name . ' table does not exist';
            exit();
        }
    }

    public function get_copying_users($user_section)
    {
        $table_name = 'users';
        $file = env('Json_master_table') . $table_name . '.json';
        $to_date = "";
        if (file_exists($file)) {
            $url = base_url('/' . $file);
            $json = file_get_contents($url, true);
            $json_data = json_decode($json, true);
            $json_array = false;
            if ($json_data) {
                $json_array = array_filter($json_data, function ($item) use ($user_section) {
                    return ($item['section'] == $user_section) && ($item['usertype'] == 50 || $item['usertype'] == 51 || $item['usertype'] == 17) && ($item['display'] == 'Y');
                });
            }
            return $json_array;
        } else {
            echo $table_name . ' table does not exist';
            exit();
        }
    }

    function get_case_type_list()
    {
        $builder = $this->db->table('master.casetype');
        $builder->select('casecode, skey, casename, short_description');
        $builder->where('display', 'Y');
        $builder->where('casecode !=', 9999);
        $builder->orderBy('short_description');

        return $result = $builder->get()->getResult();
    }

    function get_state()
    {
        $builder = $this->db->table('master.ref_agency_state');
        $builder->select('cmis_state_id, agency_state');
        $builder->where('id !=', 9999);
        $builder->orderBy('agency_state');

        return $result = $builder->get()->getResult();
    }


    function get_agency_code($state, $agency)
    {
        $builder = $this->db->table('master.ref_agency_code');
        $builder->select('*');
        $builder->where('cmis_state_id', $state);
        $builder->where('is_deleted', 'f');

        if ($agency == 1) {
            $builder->where('CAST(agency_or_court AS INTEGER) =', 1);
        } elseif ($agency == 2) {
            $builder->whereIn('CAST(agency_or_court AS INTEGER)', [5, 6]);
        }

        $result = $builder->orderBy('agency_or_court, agency_name')->get()->getResultArray();

        return $result;
    }



    function get_casetype($type)
    {
        $builder = $this->db->table('master.casetype');
        $builder->select('casecode, casename');
        $builder->where('display', 'Y');
        $builder->where('is_deleted', 'f');

        // Add additional condition if $type is not 'b'
        if ($type != 'b') {
            $builder->where('nature', $type);
        }

        return $result = $builder->orderBy('casecode')->get()->getResultArray();
    }

    function get_judges()
    {
        $builder = $this->db->table('master.judge');
        $builder->select('jcode, jname');
        $builder->where('is_retired', 'N');
        $builder->where('display', 'Y');
        $builder->where('jtype', 'J');
        $builder->orderBy('jcode');
        $result = $builder->get()->getResultArray();
        return $result;
    }
    function getSections()
    {
        $builder = $this->db->table('master.usersection');
        $builder->select('id, section_name');
        $builder->where('display', 'Y');
        $builder->where('isda', 'Y');
        $builder->orderBy('section_name');

        return  $result = $builder->get()->getResult();
    }
    function getMainSubjectCategory()
    {
        $builder = $this->db->table('master.submaster');
        $builder->select('subcode1, sub_name1');
        $builder->where('flag_use', 'S');
        $builder->orWhere('flag_use', 'L');
        $builder->where('display', 'Y');
        $builder->where('match_id !=', 0);
        $builder->where('flag', 'S');
        $builder->groupBy('subcode1, sub_name1');
        $builder->orderBy('subcode1');

        return $result = $builder->get()->getResult();
    }
    function get_da($section)
    {
        $builder = $this->db->table('master.users');
        $builder->select('usercode, CONCAT(name, \'(\', empid, \')\') as name');
        $builder->where('section', $section);
        $builder->where('display', 'Y');
        $builder->whereIn('usertype', [17, 50, 51]);

        return $result = $builder->get()->getResult();
    }
    function get_Sub_Subject_Category($Mcat)
    {

        $builder = $this->db->table('master.submaster');
        $builder->select('id, subcode1, category_sc_old, sub_name1, sub_name4');
        $builder->select("CASE WHEN (category_sc_old IS NOT NULL AND category_sc_old::text != '' AND category_sc_old::text != '0') THEN CONCAT('', category_sc_old, '#-#', sub_name4) ELSE CONCAT('', CONCAT(subcode1, '', subcode2), '#-#', sub_name4) END AS dsc", false);
        $builder->where('subcode1', $Mcat);
        $builder->where('subcode2 !=', 0);
        $builder->where('flag', 's');
        $builder->where('flag_use', 'S');
        $builder->groupBy('id, subcode1, category_sc_old, sub_name1, sub_name4');

        return $result = $builder->get()->getResult();
    }


    function get_aor()
    {
        $builder = $this->db->table('master.bar');
        $builder->select('bar_id, name, aor_code');
        $builder->select("CONCAT(name, '(', aor_code, ')') as name_display", false);
        $builder->where('isdead', 'N');
        $builder->where('if_aor', 'Y');
        $builder->where('if_sen', 'N');
        $builder->orderBy('aor_code');
        $result = $builder->get()->getResultArray();
        return $result;
    }
    /*Start By Anshu Gupta 22 Feb 2024*/
    public function get_lc_hc_casetype($state_id, $court_type, $lccasecode = null, $report = null)
    {
        //echo 'state_id='.$state_id;exit();
        if ($court_type == 1) {
            $str = 'H'; // High Court ok
            // $ct=1;
            $builder = $this->db->table("master.lc_hc_casetype");
            $builder->select("lccasecode,type_sname,corttyp,lccasename");
            if (!empty($lccasecode)) {
                $builder->where('lccasecode', $lccasecode);
            }
            if (!empty($state_id)) {
                $builder->where('cmis_state_id', $state_id);
            }
            $builder->where('corttyp', 'H');
            $builder->where('display', 'Y');
            $builder->orderBy('type_sname');
        } else if ($court_type == 3) {
            $str = 'rd_dc';  // District Court ok
            //$ct=3;

            $builder = $this->db->table("master.lc_hc_casetype");
            $builder->select("lccasecode,type_sname,corttyp,lccasename");
            if (!empty($lccasecode)) {
                $builder->where('lccasecode', $lccasecode);
            }
            if (!empty($state_id)) {
                $builder->where('cmis_state_id', $state_id);
            }
            $builder->where('corttyp', 'L');
            $builder->where('display', 'Y');
            $builder->where('type_sname is not null');
            $builder->orderBy('type_sname');
        } else if ($court_type == 4) {
            $str = 'rd_sc'; // Supreme Court ok
            //$ct=4;
            if (!empty($lccasecode) && $lccasecode == 50) {
                $caseTypes = [array('lccasecode' => 50, 'type_sname' => 'WNN', 'corttyp' => 'S', 'lccasename' => 'WRIT NOTIFICATION NO.')];
            } else if (!empty($lccasecode) && $lccasecode == 51) {
                $caseTypes = [array('lccasecode' => 51, 'type_sname' => 'ARN', 'corttyp' => 'S', 'lccasename' => 'ARBITRATION REFERENCE NO.')];
            } else {
                $caseTypes = [array('lccasecode' => 50, 'type_sname' => 'WNN', 'corttyp' => 'S', 'lccasename' => 'WRIT NOTIFICATION NO.'), array('lccasecode' => 51, 'type_sname' => 'ARN', 'corttyp' => 'S', 'lccasename' => 'ARBITRATION REFERENCE NO.')];
            }
            if (!empty($report)) {
                $builder = $this->db->table("master.casetype");
                $builder->select("casecode lccasecode,skey type_sname,casename lccasename,display corttyp");
                if (!empty($lccasecode)) {
                    $builder->where('casecode', $lccasecode);
                }
                $builder->where('display', 'Y');
                $builder->orderBy('type_sname');
                $query = $builder->get();
                $result_court_sc = $query->getResultArray();
                $result_court_sc_final_array = array_merge($result_court_sc, $caseTypes);
            } else {
                $result_court_sc_final_array = $caseTypes;
            }
            return $result_court_sc_final_array;
        }
        if ($court_type == 5) {
            $str = 'rd_sa';
            //$ct=5;  // State Agency ok
            $builder = $this->db->table("master.lc_hc_casetype");
            $builder->select("lccasecode,type_sname,corttyp,lccasename");
            if (!empty($lccasecode)) {
                $builder->where('lccasecode', $lccasecode);
            }
            if (!empty($state_id)) {
                $builder->where('cmis_state_id', $state_id);
            }
            $builder->where('display', 'Y');
            $builder->where('ref_agency_code_id is not null');
            $builder->where('type_sname is not null');
            $builder->orderBy('type_sname');
        }

        $query = $builder->get();

        //$caseTypes= $query->getResultArray();
        //echo 'court_type='.$court_type; echo '<pre>';print_r($caseTypes);exit();
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return array();
        }
    }
    function get_post_t($post_code = '')
    {
        $builder = $this->db->table('master.post_t');
        $builder->select('post_code,post_name');
        $builder->where('display', 'Y');
        if (!empty($post_code)) {
            $builder->where('post_code', $post_code);
        }
        $builder->orderBy('post_name');
        $result = $builder->get()->getResultArray();
        return $result;
    }
    /*end By Anshu Gupta 22 Feb 2024*/
    function get_work_done_ib_section($empid = null)
    {
        $builder = $this->db->table("master.user_sec_map usm");
        $builder->select('*');
        $builder->join('master.usersection b', 'usm.usec=b.id');
        $builder->WHERE('usm.display', 'Y');
        $builder->WHERE('b.display', 'Y');
        if (!empty($empid) && $empid != null) {
            $builder->WHERE('usm.empid', $empid);
        }
        $builder->orderBy('b.section_name', 'ASC');
        $query = $builder->get();
        return $result = $query->getResultArray();
    }
    function tentative_dates_list()
    {
        $builder = $this->db->table('master.sc_working_days s');
        $builder->select('s.working_date');
        $builder->join('advance_cl_printed a', "a.next_dt = s.working_date AND a.display = 'Y' AND a.board_type = 'J'", 'left');
        $builder->where('s.sec_list_dt <=', date('Y-m-d'));
        $builder->where('s.sec_list_dt IS NOT NULL', null, false); // Prevents escaping
        $builder->where('s.is_holiday', 0);
        $builder->where('s.display', 'Y');
        $builder->where('a.next_dt IS NULL', null, false); // Prevents escaping
        $builder->orderBy('s.working_date');
        return $result = $builder->get()->getResultArray();
    }

}
