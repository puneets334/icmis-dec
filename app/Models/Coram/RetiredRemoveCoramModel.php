<?php

namespace App\Models\Coram;

use CodeIgniter\Model;
use CodeIgniter\Database\RawSql;

class RetiredRemoveCoramModel extends Model
{

    public function __construct()
    {
        parent::__construct();
        $this->db = db_connect();
    }

    public function get_judge()
    {
        $builder = $this->db->table("master.judge j");
        $builder->select("jcode, jname, abbreviation");
        $builder->where("is_retired", "Y");
        $builder->where("length(CAST(jcode AS TEXT)) >= 3");
        $builder->where("jname not like '%Migration%'");
        $builder->orderBy("judge_seniority");
        $query = $builder->get();

        if ($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        } else {
            return [];
        }
    }

    public function get_data($crm_dtl, $judge, $exclude_conn)
    {

        $tbl_heardt = 'heardt';
        $tbl_main = 'main';
        $tbl_mul_category = 'mul_category';

        if ($crm_dtl == 1) {

            $builder = $this->db->table("public." . $tbl_heardt . " h,public." . $tbl_main . " m");
            $builder->select("m.reg_no_display, CAST(RIGHT(m.diary_no::TEXT, 4) AS INTEGER) AS dyr, CAST(LEFT(m.diary_no::TEXT, LENGTH(m.diary_no::TEXT) - 4) AS INTEGER) AS dno, h.diary_no, h.coram, TRIM(BOTH ',' FROM REPLACE(REPLACE(h.coram, CAST(" . $judge . " AS TEXT), ''),',,',',')) AS new_coram, m.lastorder, m.conn_key");
            $builder->where("m.diary_no = h.diary_no");
            $builder->where("c_status", "P");
            if ($exclude_conn != '') {
                $builder->where($exclude_conn);
            }
            $builder->where("coram LIKE '%" . $judge . "%'");
            $builder->where("h.list_before_remark", 11);
            $builder->orderBy("CAST(RIGHT(m.diary_no::TEXT,4) AS INTEGER) ASC, CAST(LEFT(m.diary_no::TEXT,LENGTH(m.diary_no::TEXT) - 4) AS INTEGER) ASC");
        } elseif ($crm_dtl == 2 || $crm_dtl == 3) {

            $subquery = $this->db->table("public." . $tbl_heardt . " h");
            $subquery->select("m.reg_no_display, CAST(RIGHT(m.diary_no::TEXT, 4) AS INTEGER) AS dyr, CAST(LEFT(m.diary_no::TEXT,LENGTH(m.diary_no::TEXT)-4) AS INTEGER) AS dno,h.diary_no, m.lastorder, m.conn_key");
            $subquery->join("public." . $tbl_main . " m", "m.diary_no = CAST(h.diary_no as BIGINT)", "INNER");
            $subquery->join("public.not_before n", "m.diary_no = CAST(n.diary_no as BIGINT)", "INNER");
            $subquery->where("c_status", "P");
            if ($exclude_conn != '') {
                $subquery->where($exclude_conn);
            }
            $subquery->where("n.notbef", "B");
            if ($crm_dtl == 2) {
                $subquery->where("n.res_id", "11");
            }
            if ($crm_dtl == 3) {
                $subquery->where("n.res_id != 11");
            }
            $subquery->where("n.j1", $judge);

            $builder  = $this->db->newQuery()->select("u.name, u.empid, n.ent_dt, m.*, STRING_AGG(n.j1::TEXT, ',') AS coram, TRIM(BOTH ',' FROM REPLACE(REPLACE(STRING_AGG(n.j1::TEXT, ','), '" . $judge . "', ''), ',,', ',')) AS new_coram")->fromSubquery($subquery, "m")
                ->join("public.not_before n", "m.diary_no =  CAST(n.diary_no as BIGINT)", "INNER")
                ->join("master.users u", "u.usercode = n.usercode", "LEFT")
                ->groupBY("m.diary_no,u.name ,u.empid,n.ent_dt,m.reg_no_display,m.dyr ,m.dno,m.lastorder,m.conn_key")
                ->orderBy("CAST(RIGHT(m.diary_no::TEXT, 4) AS INTEGER) ASC, CAST(LEFT(m.diary_no::TEXT,LENGTH(m.diary_no::TEXT)-4) AS INTEGER) ASC");
        } elseif ($crm_dtl == 4) {

            $builder = $this->db->table("public." . $tbl_heardt . " h");
            $builder->select("m.reg_no_display, CAST(RIGHT(m.diary_no::TEXT, 4) AS INTEGER) AS dyr, CAST(LEFT(m.diary_no::TEXT,LENGTH(m.diary_no::TEXT)-4) AS INTEGER) AS dno, h.diary_no, h.coram, TRIM(BOTH ',' FROM REPLACE(REPLACE(h.coram, CAST(" . $judge . " AS TEXT), ''),',,',',')) new_coram, m.lastorder, m.conn_key");
            $builder->join("public." . $tbl_main . " m", "m.diary_no = h.diary_no", "INNER");
            $builder->join("public." . $tbl_mul_category . " mc", "mc.diary_no = m.diary_no AND mc.display = 'Y'", "LEFT");
            $builder->where("c_status", "P");
            if ($exclude_conn != '') {
                $builder->where($exclude_conn);
            }
            $builder->where("coram LIKE '%" . $judge . "%'");
            $builder->where("(h.subhead = '824' OR mc.submaster_id = '913')");
            $builder->groupBy("m.diary_no,h.diary_no");
            $builder->orderBy("CAST(RIGHT(m.diary_no::TEXT,4) AS INTEGER) ASC, CAST(LEFT(m.diary_no::TEXT,LENGTH(m.diary_no::TEXT) - 4) AS INTEGER) ASC");
        } elseif ($crm_dtl == 5) {

            $builder = $this->db->table("public." . $tbl_heardt . " h");
            $builder->select("m.reg_no_display, CAST(RIGHT(m.diary_no::TEXT, 4) AS INTEGER) AS dyr, CAST(LEFT(m.diary_no::TEXT,LENGTH(m.diary_no::TEXT)-4) AS INTEGER) AS dno, h.diary_no, h.coram, TRIM(BOTH ',' FROM REPLACE(REPLACE(h.coram, CAST(" . $judge . " AS TEXT), ''),',,',',')) new_coram, m.lastorder, m.conn_key");
            $builder->join("public." . $tbl_main . " m", "m.diary_no = h.diary_no", "INNER");
            $builder->join("public." . $tbl_mul_category . " mc", "mc.diary_no = m.diary_no AND mc.display = 'Y'", "LEFT");
            $builder->join("public.not_before n", "n.diary_no = CAST(m.diary_no as text) AND n.notbef = 'B' AND n.j1 = " . $judge . "", "LEFT");
            $builder->where("n.j1 IS NULL");
            $builder->where("c_status", "P");
            if ($exclude_conn != '') {
                $builder->where($exclude_conn);
            }
            $builder->where("coram LIKE '%" . $judge . "%'");
            $builder->where("(h.subhead != '824' OR mc.submaster_id != '913')");
            $builder->where("h.list_before_remark != '11'");
            $builder->groupBy("m.diary_no,h.diary_no");
            $builder->orderBy("CAST(RIGHT(m.diary_no::TEXT,4) AS INTEGER) ASC, CAST(LEFT(m.diary_no::TEXT,LENGTH(m.diary_no::TEXT) - 4) AS INTEGER) ASC");
        } else {

            $builder = $this->db->table("public." . $tbl_heardt . " h,public." . $tbl_main . " m");
            $builder->select("m.reg_no_display, CAST(RIGHT(m.diary_no::TEXT, 4) AS INTEGER) AS dyr, CAST(LEFT(m.diary_no::TEXT, LENGTH(m.diary_no::TEXT) - 4) AS INTEGER) AS dno, h.diary_no, h.coram, TRIM(BOTH ',' FROM REPLACE(REPLACE(h.coram, CAST(" . $judge . " AS TEXT), ''),',,',',')) AS new_coram, m.lastorder, m.conn_key");
            $builder->where("m.diary_no = h.diary_no");
            $builder->where("c_status", "P");
            if ($exclude_conn != '') {
                $builder->where($exclude_conn);
            }
            $builder->where("coram LIKE '%" . $judge . "%'");
            $builder->orderBy("CAST(RIGHT(m.diary_no::TEXT,4) AS INTEGER) ASC, CAST(LEFT(m.diary_no::TEXT,LENGTH(m.diary_no::TEXT) - 4) AS INTEGER) ASC");
        }
        // pr($builder->getCompiledSelect());
        $query = $builder->get();

        if ($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        } else {
            return [];
        }
    }


    public function get_coram_update_info($diary_no, $coram)
    {

        $tbl_heardt = is_table_a('heardt');
        $tbl_last_heardt = is_table_a('last_heardt');

        $builder = $this->db->table("public." . $tbl_heardt);
        $builder->select("coram, usercode, ent_dt");
        $builder->where("diary_no", $diary_no);

        $builder1 = $this->db->table("public." . $tbl_last_heardt);
        $builder1->select("coram, usercode, ent_dt");
        $builder1->where("diary_no", $diary_no);
        $builder1->orderBy("ent_dt", "DESC");

        $finalQuery = $builder->union($builder1);

        $query = $finalQuery->get();

        if ($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        } else {
            return [];
        }
    }

    public function get_user_info($usercode)
    {

        $builder = $this->db->table("master.users");
        $builder->select("name, empid");
        $builder->where("usercode", $usercode);

        $query = $builder->get();

        if ($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        } else {
            return [];
        }
    }

    public function get_judge_names_inshort($chk_jud_id)
    {

        $chk_jud_arr = explode(',', $chk_jud_id);
        $builder = $this->db->table("master.judge");
        $builder->select("abbreviation");
        $builder->where("is_retired != 'Y'");
        $builder->whereIn("jcode", $chk_jud_arr);
        $query = $builder->get();

        if ($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        } else {
            return [];
        }
    }

    public function remove_coram_given_by_cji($crm_dtl, $judge)
    {

        $tbl_heardt = is_table_a('heardt');
        $tbl_last_heardt = is_table_a('last_heardt');
        $tbl_main = is_table_a('main');
        $tbl_mul_category = is_table_a('mul_category');

        $usercode = session()->get('login')['usercode'];
        $ipAddress = getClientIP();
        $result = '';

        if ($crm_dtl == 1) {

            $subquery = $this->db->table("public." . $tbl_heardt . " h");
            $subquery->select("m.diary_no, m.conn_key, h.next_dt, h.mainhead, h.subhead, h.clno, h.brd_slno, h.roster_id, h.judges, h.coram, h.board_type, h.usercode, h.ent_dt, h.module_id, h.mainhead_n, h.subhead_n, h.main_supp_flag, h.listorder, h.tentative_cl_dt, m.lastorder, h.listed_ia, h.sitting_judges,h.list_before_remark,h.is_nmd,h.no_of_time_deleted");
            $subquery->select("(select '' as bench_flag)");
            $subquery->select("(select '' as coram_del_res)");
            $subquery->join("public." . $tbl_main . " m", "m.diary_no = h.diary_no", "INNER");
            $subquery->where("c_status", "P");
            $subquery->where("coram LIKE '%" . $judge . "%'");
            $subquery->where("h.list_before_remark", "11");

            $builder  = $this->db->newQuery()->select("j.*")->fromSubquery($subquery, "j")
                ->join("public." . $tbl_last_heardt . " l", "j.diary_no = l.diary_no AND l.next_dt = j.next_dt AND l.listorder = j.listorder AND l.mainhead = j.mainhead AND l.subhead = j.subhead AND l.judges = j.judges AND l.roster_id = j.roster_id AND l.coram = j.coram AND l.clno = j.clno AND l.main_supp_flag = j.main_supp_flag AND (l.bench_flag = '' OR l.bench_flag IS NULL)", "LEFT")
                ->where("l.diary_no IS NULL");

            $query = $builder->get();

            if ($query->getNumRows() >= 1) {
                $insertData = $query->getResultArray();
                $res = $this->db->table('public.' . $tbl_last_heardt)->insertBatch($insertData);
                if ($res > 0) {
                    //Below Update query are not converting Query Builder
                    $sql = "UPDATE public.heardt SET coram = TRIM(BOTH ',' FROM REPLACE(REPLACE(coram, cast($judge as text), ''),',,',',')), module_id = 21, usercode = $usercode, ent_dt = NOW() from public.main where main.diary_no = heardt.diary_no and c_status = 'P' AND coram LIKE '%$judge%' AND list_before_remark = 11";

                    $this->db->query($sql);
                    $result = "Success";
                } else {
                    $result = "Error";
                }
            } else {
                $result = "Error";
            }
        } elseif ($crm_dtl == 2 || $crm_dtl == 3) {

            $builder = $this->db->table("public." . $tbl_heardt . " h");
            $builder->select("n.diary_no, n.j1, n.notbef, n.usercode, n.ent_dt, (select n.u_ip as old_u_ip),(select '" . $ipAddress . "' as cur_u_ip), (select '1' as cur_ucode), (select NOW() as c_dt), (select 'delete' as action), (select n.res_add as old_res_add), (select n.res_id as old_res_id), (select 'Retired' as del_reason)");
            $builder->select("(select '' as old_u_mac),(select '' as cur_u_mac)");
            $builder->join("public." . $tbl_main . " m", "m.diary_no = h.diary_no", "INNER");
            $builder->join("public.not_before n", "m.diary_no = n.diary_no", "INNER");
            $builder->where("c_status", "P");
            $builder->where("n.notbef", "B");
            if ($crm_dtl == 2) {
                $builder->where("n.res_id", "11");
            }
            if ($crm_dtl == 3) {
                $builder->where("n.res_id != 11");
            }
            $builder->where("n.j1", $judge);

            $query = $builder->get();

            if ($query->getNumRows() >= 1) {
                $insertData = $query->getResultArray();

                $res = $this->db->table('public.not_before_his')
                    ->ignore(true)
                    ->insertBatch($insertData);

                if ($res > 0) {

                    if ($crm_dtl == 2) {
                        $contion = "notbef = 'B' AND res_id = 11 AND j1 = " . $judge;
                    }
                    if ($crm_dtl == 3) {
                        $contion = "notbef = 'B' AND res_id != 11 AND j1 = " . $judge;
                    }

                    delete('public.not_before', $contion);
                    $result = "Success";
                } else {
                    $result = "Error";
                }
            } else {
                $result = "Error";
            }
        } elseif ($crm_dtl == 4) {

            $subquery = $this->db->table("public." . $tbl_heardt . " h");
            $subquery->select("m.diary_no, m.conn_key, h.next_dt, h.mainhead, h.subhead, h.clno, h.brd_slno, h.roster_id, h.judges, h.coram, h.board_type, h.usercode, h.ent_dt, h.module_id, h.mainhead_n, h.subhead_n, h.main_supp_flag, h.listorder, h.tentative_cl_dt, m.lastorder, h.listed_ia, h.sitting_judges,h.list_before_remark,h.is_nmd,h.no_of_time_deleted");
            $subquery->select("(select '' as bench_flag)");
            $subquery->select("(select '' as coram_del_res)");
            $subquery->join("public." . $tbl_main . " m", "m.diary_no = h.diary_no", "INNER");
            $subquery->join("public." . $tbl_mul_category . " mc", "mc.diary_no = m.diary_no AND mc.display = 'Y'", "LEFT");
            $subquery->where("c_status", "P");
            $subquery->where("coram LIKE '%" . $judge . "%'");
            $subquery->where("(h.subhead = '824' OR mc.submaster_id = '913')");
            $subquery->groupBY("m.diary_no,h.next_dt,h.mainhead,h.subhead,h.clno,h.brd_slno,h.roster_id,h.judges,h.coram,h.board_type,h.usercode,h.ent_dt,h.module_id,h.mainhead_n,h.subhead_n,h.main_supp_flag,h.listorder,h.tentative_cl_dt,h.listed_ia,h.sitting_judges,h.list_before_remark,h.is_nmd,h.no_of_time_deleted");

            $builder  = $this->db->newQuery()->select("j.*")->fromSubquery($subquery, "j")
                ->join("public." . $tbl_last_heardt . " l", "j.diary_no = l.diary_no AND l.next_dt = j.next_dt AND l.listorder = j.listorder AND l.mainhead = j.mainhead AND l.subhead = j.subhead AND l.judges = j.judges AND l.roster_id = j.roster_id AND l.coram = j.coram AND l.clno = j.clno AND l.main_supp_flag = j.main_supp_flag AND (l.bench_flag = '' OR l.bench_flag IS NULL)", "LEFT")
                ->where("l.diary_no IS NULL");

            $query = $builder->get();

            if ($query->getNumRows() >= 1) {
                $insertData = $query->getResultArray();
                $res = $this->db->table('public.' . $tbl_last_heardt)->insertBatch($insertData);
                if ($res > 0) {
                    //Below Update query are not converting Query Builder
                    $sql = "UPDATE public.heardt SET coram = TRIM(BOTH ',' FROM REPLACE(REPLACE(coram, cast($judge as text), ''),',,',',')), module_id = 21, usercode = $usercode, ent_dt = NOW() from public.main,public.mul_category where main.diary_no = heardt.diary_no and mul_category.diary_no = main.diary_no AND mul_category.display = 'Y' AND c_status = 'P' AND coram LIKE '%$judge%' AND (heardt.subhead = '824' AND mul_category.submaster_id = '913')";

                    $this->db->query($sql);
                    $result = "Success";
                } else {
                    $result = "Error";
                }
            } else {
                $result = "Error";
            }
        } elseif ($crm_dtl == 5) {

            $subquery = $this->db->table("public." . $tbl_heardt . " h");
            $subquery->select("m.diary_no, m.conn_key, h.next_dt, h.mainhead, h.subhead, h.clno, h.brd_slno, h.roster_id, h.judges, h.coram, h.board_type, h.usercode, h.ent_dt, h.module_id, h.mainhead_n, h.subhead_n, h.main_supp_flag, h.listorder, h.tentative_cl_dt, m.lastorder, h.listed_ia, h.sitting_judges,h.list_before_remark,h.is_nmd,h.no_of_time_deleted");
            $subquery->select("(select '' as bench_flag)");
            $subquery->select("(select '' as coram_del_res)");
            $subquery->join("public." . $tbl_main . " m", "m.diary_no = h.diary_no", "INNER");
            $subquery->join("public." . $tbl_mul_category . " mc", "mc.diary_no = m.diary_no AND mc.display = 'Y'", "LEFT");
            $subquery->join("public.not_before n", "n.diary_no = m.diary_no AND n.notbef = 'B' AND n.j1 = " . $judge . "", "LEFT");
            $subquery->where("n.j1 IS NULL");
            $subquery->where("c_status", "P");
            $subquery->where("coram LIKE '%" . $judge . "%'");
            $subquery->where("(h.subhead != '824' OR mc.submaster_id != '913')");
            $subquery->where("h.list_before_remark != '11'");
            $subquery->groupBY("m.diary_no,h.next_dt,h.mainhead,h.subhead,h.clno,h.brd_slno,h.roster_id,h.judges,h.coram,h.board_type,h.usercode,h.ent_dt,h.module_id,h.mainhead_n,h.subhead_n,h.main_supp_flag,h.listorder,h.tentative_cl_dt,h.listed_ia,h.sitting_judges,h.list_before_remark,h.is_nmd,h.no_of_time_deleted");

            $builder  = $this->db->newQuery()->select("j.*")->fromSubquery($subquery, "j")
                ->join("public." . $tbl_last_heardt . " l", "j.diary_no = l.diary_no AND l.next_dt = j.next_dt AND l.listorder = j.listorder AND l.mainhead = j.mainhead AND l.subhead = j.subhead AND l.judges = j.judges AND l.roster_id = j.roster_id AND l.coram = j.coram AND l.clno = j.clno AND l.main_supp_flag = j.main_supp_flag AND (l.bench_flag = '' OR l.bench_flag IS NULL)", "LEFT")
                ->where("l.diary_no IS NULL");

            $query = $builder->get();

            if ($query->getNumRows() >= 1) {
                $insertData = $query->getResultArray();
                $res = $this->db->table('public.' . $tbl_last_heardt)->insertBatch($insertData);
                if ($res > 0) {
                    //Below Update query are not converting Query Builder
                    $sql = "UPDATE public.heardt SET coram = TRIM(BOTH ',' FROM REPLACE(REPLACE(coram, cast($judge as text), ''),',,',',')), module_id = 21, usercode = $usercode, ent_dt = NOW() from public.main,public.mul_category,public.not_before where main.diary_no = heardt.diary_no and mul_category.diary_no = main.diary_no AND mul_category.display = 'Y' AND not_before.diary_no = main.diary_no AND not_before.notbef = 'B' AND not_before.j1 = " . $judge . " AND not_before.j1 IS NULL AND c_status = 'P' AND coram LIKE '%$judge%' AND (heardt.subhead != '824' AND mul_category.submaster_id != '913') AND list_before_remark != 11";

                    $this->db->query($sql);
                    $result = "Success";
                } else {
                    $result = "Error";
                }
            } else {
                $result = "Error";
            }
        } else {

            $subquery = $this->db->table("public." . $tbl_heardt . " h");
            $subquery->select("m.diary_no, m.conn_key, h.next_dt, h.mainhead, h.subhead, h.clno, h.brd_slno, h.roster_id, h.judges, h.coram, h.board_type, h.usercode, h.ent_dt, h.module_id, h.mainhead_n, h.subhead_n, h.main_supp_flag, h.listorder, h.tentative_cl_dt, m.lastorder, h.listed_ia, h.sitting_judges,h.list_before_remark,h.is_nmd,h.no_of_time_deleted");
            $subquery->select("(select '' as bench_flag)");
            $subquery->select("(select '' as coram_del_res)");
            $subquery->join("public." . $tbl_main . " m", "m.diary_no = h.diary_no", "INNER");
            $subquery->where("c_status", "P");
            $subquery->where("coram LIKE '%" . $judge . "%'");

            $builder  = $this->db->newQuery()->select("j.*")->fromSubquery($subquery, "j")
                ->join("public." . $tbl_last_heardt . " l", "j.diary_no = l.diary_no AND l.next_dt = j.next_dt AND l.listorder = j.listorder AND l.mainhead = j.mainhead AND l.subhead = j.subhead AND l.judges = j.judges AND l.roster_id = j.roster_id AND l.coram = j.coram AND l.clno = j.clno AND l.main_supp_flag = j.main_supp_flag AND (l.bench_flag = '' OR l.bench_flag IS NULL)", "LEFT")
                ->where("l.diary_no IS NULL");

            $query = $builder->get();

            if ($query->getNumRows() >= 1) {
                $insertData = $query->getResultArray(); // Get multiple rows

                // Loop through each row and modify data
                foreach ($insertData as &$row) {
                    if (isset($row['conn_key']) && $row['conn_key'] === '') {
                        $row['conn_key'] = 0; // Set empty conn_key to 0
                    }

                    foreach ($row as $key => $value) {
                        if ($value === '') {
                            $row[$key] = null; // Convert other empty values to NULL
                        }
                    }
                }
                unset($row);

                // Print the compiled insert query for debugging
                $insertBuilder = $this->db->table('public.' . $tbl_last_heardt);
                // echo $insertBuilder->set($insertData[0])->getCompiledInsert(); // Show query for first row
                // exit();

                // Insert data in batch
                $res = $insertBuilder->insertBatch($insertData);

                if ($res > 0) {
                    //Below Update query are not converting Query Builder
                    $sql = "UPDATE public.heardt SET coram = TRIM(BOTH ',' FROM REPLACE(REPLACE(coram, cast($judge as text), ''),',,',',')), module_id = 21, usercode = $usercode, ent_dt = NOW() from public.main where main.diary_no = heardt.diary_no and c_status = 'P' AND coram LIKE '%$judge%'";

                    $this->db->query($sql);
                    $result = "Success";
                } else {
                    $result = "Error";
                }
            } else {
                $result = "Error";
            }
        }

        echo $result;
    }
}
