<?php
///var/www/scicmis/app/Models/Reports/Listing/ProposalModel.php
namespace App\Models\Reports\Listing;

use CodeIgniter\Model;
//use CodeIgniter\Database\BaseBuilder;
use CodeIgniter\Database\RawSql;
use DateTime; // You probably already have this
use DateTimeZone; // Add this line to import DateTimeZone


class ProposalModel extends Model
{

    public function __construct(){
        parent::__construct();
        $this->db = db_connect();
    }

        public function case_remark_heard_insert(){//27(done)
            $insertQuery = "INSERT INTO last_heardt (diary_no,conn_key,next_dt,mainhead,subhead,clno,brd_slno,roster_id,judges,coram,board_type,usercode,ent_dt,module_id, mainhead_n, subhead_n,
                                main_supp_flag,listorder,tentative_cl_dt,lastorder,listed_ia,sitting_judges,list_before_remark,is_nmd,no_of_time_deleted)
                        SELECT j.* FROM (
                        SELECT j.diary_no::bigint, j.conn_key, j.next_dt, j.mainhead, j.subhead, j.clno, j.brd_slno, j.roster_id, j.judges, j.coram, j.board_type, j.usercode, j.ent_dt, j.module_id,
                            j.mainhead_n, j.subhead_n, j.main_supp_flag, j.listorder, j.tentative_cl_dt, j.lastorder, j.listed_ia, j.sitting_judges,
                            j.list_before_remark,j.is_nmd,j.no_of_time_deleted
                        FROM (
                            SELECT c.diary_no, h.conn_key, h.next_dt, h.mainhead, h.subhead, h.clno, h.brd_slno, h.roster_id, h.judges, h.coram, h.board_type, h.usercode, h.ent_dt, h.module_id,
                                h.mainhead_n, h.subhead_n, h.main_supp_flag, h.listorder, h.tentative_cl_dt, m.lastorder, h.listed_ia, h.sitting_judges,
                                h.list_before_remark,h.is_nmd,h.no_of_time_deleted, 
                                (CASE     
                                    WHEN (split_part((json_agg(c.r_head ORDER BY ch.priority)->>0)::text,',',1) IN ('24','207','59')) THEN 4
                                    WHEN (split_part((json_agg(c.r_head ORDER BY ch.priority)->>0)::text,',',1) IN ('8','21','68')) THEN 7      
                                    WHEN (split_part((json_agg(c.r_head ORDER BY ch.priority)->>0)::text,',',1) IN ('23','12','53','54')) THEN 8
                                    WHEN h.listorder != 32 AND h.listorder != 25 AND (split_part((json_agg(c.r_head ORDER BY ch.priority)->>0)::text,',',1) IN ('5','190','19','125','132','145','146')) THEN 48
                                    WHEN (h.listorder = 32 OR h.listorder = 25) AND (split_part((json_agg(c.r_head ORDER BY ch.priority)->>0)::text,',',1) IN ('5','190','19','125','132','145','146')) THEN 25
                                END) AS listorder_new
                            FROM case_remarks_multiple c 
                            INNER JOIN (SELECT c.diary_no, MAX(cl_date) AS max_cl_dt FROM case_remarks_multiple c GROUP BY c.diary_no) AS c1 ON 
                                c1.diary_no = c.diary_no AND c1.max_cl_dt = c.cl_date 
                            INNER JOIN heardt h ON h.diary_no::bigint = c.diary_no::bigint
                            INNER JOIN main m ON m.diary_no = h.diary_no
                            LEFT JOIN master.case_remarks_head ch ON ch.sno = c.r_head
                            WHERE
                                h.tentative_cl_dt > CURRENT_DATE AND (length(c.jcodes) >= 6 OR (length(c.jcodes) = 3 AND (h.board_type = 'J' OR h.board_type = 'S') AND h.clno > 0 ))
                                AND c.status = 'P' AND (c.mainhead = 'M' OR c.mainhead = 'F') AND (board_type = 'J' OR board_type = 'S') AND 
                                CASE WHEN c.mainhead = 'F' THEN 
                                    c.r_head IN (24, 207, 59, 8, 21, 23, 12, 53, 54, 68)
                                ELSE 
                                    c.r_head IN (24, 207, 59, 8, 21, 23, 12, 53, 54, 68, 5, 190, 19, 125, 132, 146, 145)
                                END
                                AND NOT (h.next_dt > CURRENT_DATE AND roster_id > 0 AND (main_supp_flag IN (1, 2)) AND h.clno > 0 AND h.brd_slno > 0)
                                AND NOT (h.listorder IN (4,5,2,16,24,21,32,49) AND h.clno = 0 AND h.brd_slno = 0)  
                            GROUP BY c.diary_no, h.conn_key, h.next_dt, h.mainhead, h.subhead, h.clno, h.brd_slno, h.roster_id, h.judges, h.coram, h.board_type, h.usercode, h.ent_dt, h.module_id,
                                h.mainhead_n, h.subhead_n, h.main_supp_flag, h.listorder, h.tentative_cl_dt, m.lastorder, h.listed_ia, h.sitting_judges,
                                h.list_before_remark,h.is_nmd,h.no_of_time_deleted
                        ) j
                        WHERE (j.listorder != j.listorder_new OR tentative_cl_dt != next_dt)
                        ) j
                            LEFT JOIN last_heardt l ON j.diary_no::bigint = l.diary_no::bigint
                            AND l.next_dt = j.next_dt
                            AND l.listorder = j.listorder
                            AND l.mainhead = j.mainhead
                            AND l.subhead = j.subhead
                            AND l.roster_id = j.roster_id
                            AND l.judges = j.judges
                            AND l.clno = j.clno
                            AND l.main_supp_flag = j.main_supp_flag
                            AND l.ent_dt = j.ent_dt    
                            AND (l.bench_flag = '' OR l.bench_flag IS NULL)
                            WHERE l.bench_flag = '' OR l.diary_no IS NULL";
                    // Subquery 1 (innermost) - Fetch latest remarks
            
            $result = $this->db->query($insertQuery);
            // Check if insertion was successful
            if ($result) {
                $affectedRows = $this->db->affectedRows();
                return "<tr><td>Inserted successfully</td><td>".$affectedRows."</td></tr>";  
            } else {
                return "<tr><td>Failed to Inserted</td><td>0</td></tr>";
            }
        }

    public function case_remark_heard_update(){//26(done)
        $sql12 = "UPDATE heardt h
                        SET next_dt = t0.tentative_cl_dt, 
                            tentative_cl_dt = t0.tentative_cl_dt, 
                            roster_id = 0, 
                            judges = 0,
                            main_supp_flag = 0, 
                            clno = 0, 
                            brd_slno = 0, 
                            usercode = '1', 
                            ent_dt = NOW(), 
                            listorder = t0.listorder_new, 
                            module_id = '18'
                        FROM (
                            SELECT j.* 
                            FROM (
                                SELECT c.diary_no,
                                    (CASE
                                        WHEN (split_part((json_agg(c.r_head ORDER BY ch.priority)->>0)::text,',',1) IN ('24','207','59')) THEN 4
                                        WHEN (split_part((json_agg(c.r_head ORDER BY ch.priority)->>0)::text,',',1) IN ('8','21','68')) THEN 7
                                        WHEN (split_part((json_agg(c.r_head ORDER BY ch.priority)->>0)::text,',',1) IN ('23','12','53','54')) THEN 8
                                        WHEN h1.listorder != 32 AND h1.listorder != 25 AND (split_part((json_agg(c.r_head ORDER BY ch.priority)->>0)::text,',',1) IN ('5','190','19','125','132','145','146')) THEN 48
                                        WHEN (h1.listorder = 32 OR h1.listorder = 25) AND (split_part((json_agg(c.r_head ORDER BY ch.priority)->>0)::text,',',1) IN ('5','190','19','125','132','145','146')) THEN 25
                                    END) AS listorder_new,
                                    h1.tentative_cl_dt,
                                    c.head_content, m.bench, c.cl_date, c.r_head, h1.subhead, h1.listorder, h1.next_dt
                                FROM case_remarks_multiple c
                                LEFT JOIN master.case_remarks_head ch ON ch.sno = c.r_head
                                INNER JOIN (SELECT c2.diary_no, MAX(cl_date) AS max_cl_dt FROM case_remarks_multiple c2 GROUP BY c2.diary_no) AS c1 ON
                                    c1.diary_no = c.diary_no AND c1.max_cl_dt = c.cl_date
                                INNER JOIN heardt h1 ON h1.diary_no::text = c.diary_no::text  -- Alias added here
                                INNER JOIN main m ON m.diary_no = h1.diary_no
                                WHERE
                                    h1.tentative_cl_dt > CURRENT_DATE AND (length(c.jcodes) >= 6 OR (length(c.jcodes) = 3 AND (h1.board_type = 'J' OR h1.board_type = 'S') AND h1.clno > 0 ))
                                    AND c.status = 'P' AND (c.mainhead = 'M' OR c.mainhead = 'F') AND (board_type = 'J' OR board_type = 'S') AND
                                    CASE WHEN c.mainhead = 'F' THEN
                                        c.r_head IN (24, 59, 8, 21, 23, 12, 53, 54, 68)
                                    ELSE
                                        c.r_head IN (24, 59, 8, 21, 23, 12, 53, 54, 68, 5, 190, 19, 125, 132, 146, 145)
                                    END
                                    AND NOT (h1.next_dt > CURRENT_DATE AND roster_id > 0 AND (main_supp_flag IN (1, 2)) AND h1.clno > 0 AND h1.brd_slno > 0)
                                    AND NOT (h1.listorder IN (4,5,2,16,24,21,32,49) AND h1.clno = 0 AND h1.brd_slno = 0)
                                GROUP BY c.diary_no, h1.tentative_cl_dt, c.head_content, m.bench, c.cl_date, c.r_head, h1.subhead, h1.listorder, h1.next_dt
                                LIMIT 1  -- Limit clause moved inside the subquery
                            ) j
                            WHERE (j.listorder != j.listorder_new OR j.tentative_cl_dt != j.next_dt) -- Added j. prefix
                        ) t0
                        WHERE t0.diary_no::text = h.diary_no::text";
        $result = $this->db->query($sql12);
        if ($result) {
            $affectedRows = $this->db->affectedRows();
            return "<tr><td>Updated (Motion):</td><td>".$affectedRows."</td></tr>";  
        } else {
            return "<tr><td>Unable to Update (Motion) :</td><td>0</td></tr>";
        }
    }

    public function part_heard_insert(){//25(done)
        $sql11 = "INSERT INTO last_heardt (diary_no, conn_key, next_dt, mainhead, subhead, clno, brd_slno, roster_id, judges, coram, board_type, usercode, ent_dt, module_id, mainhead_n, subhead_n, main_supp_flag, listorder, tentative_cl_dt, lastorder, listed_ia, sitting_judges, list_before_remark, is_nmd, no_of_time_deleted)
                    SELECT j.*
                    FROM (
                        SELECT c.diary_no::bigint, h.conn_key, h.next_dt, h.mainhead, h.subhead, h.clno, h.brd_slno, h.roster_id, h.judges, h.coram, h.board_type, h.usercode, h.ent_dt, h.module_id,
                            h.mainhead_n, h.subhead_n, h.main_supp_flag, h.listorder, h.tentative_cl_dt, m.lastorder, h.listed_ia, h.sitting_judges,
                            h.list_before_remark, h.is_nmd, h.no_of_time_deleted
                        FROM case_remarks_multiple c
                        INNER JOIN (SELECT c2.diary_no, MAX(cl_date) AS max_cl_dt FROM case_remarks_multiple c2 GROUP BY c2.diary_no) AS c1 ON
                            c1.diary_no = c.diary_no AND c1.max_cl_dt = c.cl_date
                        INNER JOIN heardt h ON h.diary_no::bigint = c.diary_no::bigint
                        INNER JOIN main m ON m.diary_no = h.diary_no
                        WHERE
                            c.status = 'P' AND (h.board_type = 'J' OR h.board_type = 'S') AND
                            c.r_head IN (6, 195, 17, 3, 62, 181, 182, 183, 184)
                            AND h.next_dt > c.cl_date AND h.usercode = 1 AND h.module_id = '18' AND h.listorder IN (4,7,8,25,48)
                            AND h.mainhead = 'M' AND h.main_supp_flag = 0 AND h.clno = 0 AND h.brd_slno = 0
                            AND NOT (h.next_dt > CURRENT_DATE AND h.roster_id > 0 AND (h.main_supp_flag IN (1, 2)) AND h.clno > 0 AND h.brd_slno > 0)
                            AND NOT (h.listorder = 5 AND h.clno = 0 AND h.brd_slno = 0)
                        GROUP BY c.diary_no, h.conn_key, h.next_dt, h.mainhead, h.subhead, h.clno, h.brd_slno, h.roster_id, h.judges, h.coram, h.board_type, h.usercode, h.ent_dt, h.module_id,
                            h.mainhead_n, h.subhead_n, h.main_supp_flag, h.listorder, h.tentative_cl_dt, m.lastorder, h.listed_ia, h.sitting_judges,
                            h.list_before_remark, h.is_nmd, h.no_of_time_deleted
                    ) j
                    LEFT JOIN last_heardt l ON j.diary_no::text = l.diary_no::text
                        AND l.next_dt = j.next_dt
                        AND l.listorder = j.listorder
                        AND l.mainhead = j.mainhead
                        AND l.subhead = j.subhead
                        AND l.roster_id = j.roster_id
                        AND l.judges = j.judges
                        AND l.clno = j.clno
                        AND l.main_supp_flag = j.main_supp_flag
                        AND l.ent_dt = j.ent_dt
                        AND l.bench_flag IS NULL  -- Corrected line
                    WHERE l.diary_no IS NULL";
        $result = $this->db->query($sql11);
        // Check if rows are affected
        if ($result) {
            return "<tr><td>Inserted (part heard):</td><td> " . $this->db->affectedRows(). "</td></tr>";
        } else {
            $error = $this->db->error();
            return "<tr><td>Unable to Insert (part heard)</td><td>0</td></tr>";
        }
        
        
    }

    public function part_heard_update(){//24(done)
                $sql12 = "UPDATE heardt h
                    SET subhead = CASE 
                        WHEN t0.grp_head = '6' THEN '824'
                        WHEN t0.grp_head = '195' THEN '810'
                        WHEN t0.grp_head = '17' AND t0.case_grp = 'C' THEN '816'
                        WHEN t0.grp_head = '17' AND t0.case_grp = 'R' THEN '815'
                        WHEN t0.grp_head IN ('3','62','181','182','183','184') AND t0.case_grp = 'C' THEN '813'
                        WHEN t0.grp_head IN ('3','62','181','182','183','184') AND t0.case_grp = 'R' THEN '814'
                        ELSE h.subhead 
                    END
                    FROM (
                        SELECT m.case_grp, string_agg(c.r_head::text, ',') AS grp_head, c.diary_no, h1.conn_key, h1.next_dt, h1.mainhead, h1.subhead, h1.clno, h1.brd_slno, h1.roster_id, h1.judges, h1.coram, h1.board_type, h1.usercode, h1.ent_dt, h1.module_id,
                            h1.mainhead_n, h1.subhead_n, h1.main_supp_flag, h1.listorder, h1.tentative_cl_dt, m.lastorder, h1.listed_ia, h1.sitting_judges
                        FROM case_remarks_multiple c
                        INNER JOIN (SELECT c2.diary_no, MAX(cl_date) AS max_cl_dt FROM case_remarks_multiple c2 GROUP BY c2.diary_no) AS c1 ON
                            c1.diary_no = c.diary_no AND c1.max_cl_dt = c.cl_date
                        INNER JOIN heardt h1 ON h1.diary_no::bigint = c.diary_no::bigint  -- Alias added here
                        INNER JOIN main m ON m.diary_no = h1.diary_no
                        WHERE
                            c.status = 'P' AND (h1.board_type = 'J' OR h1.board_type = 'S') AND
                            c.r_head IN (6, 195, 17, 3, 62, 181, 182, 183, 184)
                            AND h1.next_dt > c.cl_date AND h1.usercode = 1 AND h1.module_id = '18' AND h1.listorder IN (4,7,8,25,48)
                            AND h1.mainhead = 'M' AND h1.main_supp_flag = 0 AND h1.clno = 0 AND h1.brd_slno = 0
                            AND NOT (h1.next_dt > CURRENT_DATE AND roster_id > 0 AND (h1.main_supp_flag IN (1, 2)) AND h1.clno > 0 AND h1.brd_slno > 0)
                            AND NOT (h1.listorder = 5 AND h1.clno = 0 AND h1.brd_slno = 0)
                        GROUP BY m.case_grp, c.diary_no, h1.conn_key, h1.next_dt, h1.mainhead, h1.subhead, h1.clno, h1.brd_slno, h1.roster_id, h1.judges, h1.coram, h1.board_type, h1.usercode, h1.ent_dt, h1.module_id,
                            h1.mainhead_n, h1.subhead_n, h1.main_supp_flag, h1.listorder, h1.tentative_cl_dt, m.lastorder, h1.listed_ia, h1.sitting_judges
                    ) t0
                    WHERE t0.diary_no::text = h.diary_no::text";
                    $result = $this->db->query($sql12);
        
                    // Check if rows are affected
                    if ($result) {
                        return "<tr><td>Updated (part heard):</td><td> " . $this->db->affectedRows(). "</td></tr>";
                    } else {
                        $error = $this->db->error();
                        return "<tr><td>Unable to Update (part heard)</td><td>0</td></tr>";
                    }
        
    }

    public function final_hearing_cases(){//23
        
        $subquery = $this->db->table('master.holidays h2')
                    ->select("CURRENT_DATE + (2 - extract(dow FROM CURRENT_DATE)) * interval '1 day' AS MondayOfWeek")
                    ->union($this->db->table('master.holidays h3')->select("CURRENT_DATE + (3 - extract(dow FROM CURRENT_DATE)) * interval '1 day'"))
                    ->union($this->db->table('master.holidays h4')->select("CURRENT_DATE + (4 - extract(dow FROM CURRENT_DATE)) * interval '1 day'"))
                    ->union($this->db->table('master.holidays h5')->select("CURRENT_DATE + (5 - extract(dow FROM CURRENT_DATE)) * interval '1 day'"), false);

        $potentialMondaysQuery =  "({$subquery->getCompiledSelect()}) AS sub"; // Store the subquery string

        $builder = $this->db->table($potentialMondaysQuery); // Treat the subquery as a table

        $builder->selectMax('mondayofweek', 'last_day_week')
            ->whereNotIn('mondayofweek', function ($builder) { // The crucial part
                $builder->select('hdate')
                    ->from('master.holidays');
            });

        $result = $builder->get()->getRow();
        // print_r($result->last_day_week);
        // die();
        $row32[0] = '2025-02-08 00:00:00';
        if ($row32[0] == date('Y-m-d')) {
            $sql_fh_pro_i = "INSERT INTO last_heardt (diary_no, conn_key, next_dt, mainhead, subhead, clno, brd_slno, roster_id, judges, coram, board_type, usercode, ent_dt, module_id, mainhead_n, subhead_n, main_supp_flag, listorder, tentative_cl_dt, lastorder, listed_ia, sitting_judges, list_before_remark, is_nmd, no_of_time_deleted)
                                SELECT j.* FROM (
                                    SELECT
                                        c.diary_no, h.conn_key, h.next_dt, h.mainhead, h.subhead, h.clno, h.brd_slno, h.roster_id, h.judges, h.coram, h.board_type, h.usercode, h.ent_dt, h.module_id,
                                        h.mainhead_n, h.subhead_n, h.main_supp_flag, h.listorder, h.tentative_cl_dt, m.lastorder, h.listed_ia, h.sitting_judges,
                                        h.list_before_remark, h.is_nmd, h.no_of_time_deleted
                                    FROM (
                                        SELECT
                                            c.diary_no, c.cl_date, c.r_head, c.head_content
                                        FROM case_remarks_multiple c
                                        WHERE c.cl_date BETWEEN date_trunc('week', CURRENT_DATE)::date + interval '2 day' AND date_trunc('week', CURRENT_DATE)::date + interval '7 day'
                                        AND c.status = 'P' AND c.mainhead = 'F' AND
                                        (c.r_head = 24 OR c.r_head = 59 OR c.r_head = 8 OR c.r_head = 21 OR c.r_head = 23 OR c.r_head = 12 OR c.r_head = 53 OR c.r_head = 54 OR c.r_head = 68)
                                        GROUP BY c.diary_no, c.cl_date, c.r_head, c.head_content
                                    ) c
                                    JOIN heardt h ON h.diary_no::bigint = c.diary_no::bigint
                                    JOIN main m ON m.diary_no::bigint = c.diary_no::bigint
                                    WHERE c.cl_date = h.next_dt AND h.tentative_cl_dt > CURRENT_DATE AND m.c_status = 'P'
                                    AND h.judges::int > 0 AND h.roster_id::int > 0 AND (main_supp_flag = 1 OR main_supp_flag = 2) AND clno::int > 0 AND brd_slno::int > 0 AND (board_type = 'J' OR board_type = 'S')
                                ) j
                                LEFT JOIN last_heardt l ON j.diary_no = l.diary_no
                                AND l.next_dt = j.next_dt
                                AND l.listorder = j.listorder
                                AND l.mainhead = j.mainhead
                                AND l.subhead = j.subhead
                                AND l.roster_id = j.roster_id
                                AND l.judges = j.judges
                                AND l.clno = j.clno
                                AND l.main_supp_flag = j.main_supp_flag
                                AND l.ent_dt = j.ent_dt
                                AND (l.bench_flag = '' OR l.bench_flag IS NULL)
                                WHERE l.diary_no IS NULL";
            $sql_fh_pro_ii = $this->db->query($sql_fh_pro_i);
            if ($sql_fh_pro_ii > 0) {
                $affectedRows = $this->db->affectedRows();
                return "<tr><td>Inserted (F)</td><td>".$affectedRows."</td></tr>";  
            } else {
                return "<tr><td>Unable to Insert (F)</td><td>0</td></tr>";
            }
            
            //done20
            $sql_fh_pro_u = "UPDATE heardt h
                                SET next_dt = t0.tentative_cl_dt,
                                    tentative_cl_dt = t0.tentative_cl_dt,
                                    roster_id = 0,
                                    judges = 0,
                                    main_supp_flag = 0,
                                    clno = 0,
                                    brd_slno = 0,
                                    usercode = '1',
                                    ent_dt = NOW(),
                                    listorder = t0.listorder_new,
                                    module_id = '18'
                                FROM (
                                    SELECT c.diary_no,
                                        (CASE
                                            WHEN c.r_head IN (24, 59) THEN 4
                                            WHEN c.r_head IN (8, 21, 68) THEN 7
                                            WHEN c.r_head IN (23, 12, 53, 54) THEN 8
                                        END) AS listorder_new,
                                        h.tentative_cl_dt,
                                        c.head_content,
                                        m.bench,
                                        c.cl_date,
                                        c.r_head,
                                        h.subhead,
                                        h.listorder
                                    FROM (
                                        SELECT c.diary_no, c.cl_date, c.r_head, c.head_content
                                        FROM case_remarks_multiple c
                                        WHERE c.cl_date BETWEEN date_trunc('week', CURRENT_DATE)::date + interval '2 day' AND date_trunc('week', CURRENT_DATE)::date + interval '7 day'
                                        AND c.status = 'P'
                                        AND c.mainhead = 'F'
                                        AND c.r_head IN (24, 59, 8, 21, 23, 12, 53, 54, 68)
                                        GROUP BY c.diary_no, c.cl_date, c.r_head, c.head_content
                                    ) c
                                    JOIN heardt h ON h.diary_no::bigint = c.diary_no::bigint
                                    JOIN main m ON m.diary_no::bigint = c.diary_no::bigint
                                    WHERE c.cl_date = h.next_dt
                                    AND h.tentative_cl_dt > CURRENT_DATE
                                    AND m.c_status = 'P'
                                    AND h.judges::int > 0  -- Cast to integer
                                    AND h.roster_id::int > 0 -- Cast to integer
                                    AND (main_supp_flag = 1 OR main_supp_flag = 2)
                                    AND h.clno::int > 0    -- Cast to integer
                                    AND h.brd_slno::int > 0 -- Cast to integer
                                    AND (board_type = 'J' OR board_type = 'S')
                                ) AS t0
                                WHERE t0.diary_no::text = h.diary_no::text";

            $sql_fh_pro_uu = $this->db->query($sql_fh_pro_u);
            if ($sql_fh_pro_uu > 0) {
                $affectedRows = $this->db->affectedRows();
                return "<tr><td>Updated (F)</td><td>".$affectedRows."</td></tr>";  
            } else {
                return "<tr><td>Unable to update (F)</td><td>0</td></tr>";
            }
        } else {
            return "<tr><td>Final - This will run on last working day of week</td><td>0</td></tr>"; 
        }
     
    }

    public function misc_days(){//22(done)
        // Get the max printed date
        $subQuery = $this->db->table('cl_printed c')
        ->select('MAX(c.next_dt) as printed_max_dt')
        ->join('master.roster r', 'c.roster_id = r.id')
        ->join('master.roster_bench b', 'r.bench_id = b.id')
        ->join('master.master_bench mb', 'mb.id = b.bench_id')
        ->where([
            'mb.board_type_mb' => 'C',
            'c.display' => 'Y',
            'c.m_f' => 'M'
        ])
        ->getCompiledSelect();

        // Get the next court working day
        $query = $this->db->table('master.sc_working_days s')
        ->select('s.working_date as misc_dt')
        ->join("($subQuery) a", 's.working_date > a.printed_max_dt', 'inner', false)
        ->where([
            's.is_holiday' => 0,
            's.is_nmd' => 0,
            's.display' => 'Y'
        ])
        ->where('s.working_date > CURRENT_DATE', null, false)
        ->orderBy('s.working_date')
        ->limit(1)
        ->get();
        

        if (!empty($query)) { 
        $row = $query->getRowArray();
        if (!empty($row['misc_dt'])) {
           return $next_court_work_day_ymd = $row['misc_dt'];
        }
        else{
            return 0;
        }
        }
    }
    public function chamber_defect_not_remove_90_I(){//21
        $sql_bench_flag = "INSERT INTO last_heardt (diary_no, conn_key, next_dt, mainhead, subhead, clno, brd_slno, roster_id, judges, coram, board_type, usercode, ent_dt, module_id, mainhead_n, subhead_n, main_supp_flag, listorder, tentative_cl_dt, lastorder, listed_ia, sitting_judges, list_before_remark, is_nmd, no_of_time_deleted)
                            SELECT j.*
                            FROM (
                                SELECT h.diary_no::bigint,
                                    h.conn_key,
                                    h.next_dt,
                                    h.mainhead,
                                    h.subhead,
                                    h.clno,
                                    h.brd_slno,
                                    h.roster_id,
                                    h.judges,
                                    h.coram,
                                    h.board_type,
                                    h.usercode,
                                    h.ent_dt,
                                    18 AS module_id,  -- Add module_id here
                                    h.mainhead_n,
                                    h.subhead_n,
                                    h.main_supp_flag,
                                    h.listorder,
                                    h.tentative_cl_dt,
                                    b.lastorder,
                                    h.listed_ia,
                                    h.sitting_judges,
                                    h.list_before_remark,
                                    h.is_nmd,
                                    h.no_of_time_deleted
                                FROM obj_save a
                                INNER JOIN main b ON a.diary_no = b.diary_no
                                LEFT JOIN heardt h ON h.diary_no = b.diary_no
                                LEFT JOIN last_heardt l ON l.diary_no = h.diary_no
                                    AND l.judges::bigint > 0
                                    AND (l.board_type = 'J' OR l.board_type = 'S')
                                    AND (l.bench_flag = '' OR l.bench_flag IS NULL)
                                    AND l.clno > 0
                                WHERE l.diary_no IS NULL
                                AND (h.diary_no IS NULL OR h.next_dt IS NULL)  -- Handle bad date
                                AND rm_dt IS NULL
                                AND display = 'Y'
                                AND b.diary_no_rec_date::date < '2018-10-14'
                                AND current_date - a.save_dt::date > 93  -- Date difference
                                AND b.c_status = 'P'
                                AND b.active_fil_no = ''
                                GROUP BY h.diary_no, h.conn_key, h.next_dt, h.mainhead, h.subhead, h.clno, h.brd_slno, h.roster_id, h.judges, h.coram, h.board_type, h.usercode, h.ent_dt, b.lastorder, h.listed_ia, h.sitting_judges, h.list_before_remark, h.is_nmd, h.no_of_time_deleted, h.mainhead_n, h.subhead_n, h.main_supp_flag, h.listorder, h.tentative_cl_dt -- Corrected GROUP BY clause
                            ) AS j
                            LEFT JOIN last_heardt l ON j.diary_no = l.diary_no
                                AND l.next_dt = j.next_dt
                                AND l.listorder = j.listorder
                                AND l.mainhead = j.mainhead
                                AND l.subhead = j.subhead
                                AND l.roster_id = j.roster_id
                                AND l.judges = j.judges
                                AND l.clno = j.clno
                                AND l.main_supp_flag = j.main_supp_flag
                                AND l.ent_dt = j.ent_dt
                                AND (l.bench_flag = '' OR l.bench_flag IS NULL)
                            WHERE l.diary_no IS NULL AND j.next_dt IS NOT NULL";
        $result = $this->db->query($sql_bench_flag);
        // echo $this->db->getLastQuery();
        // die();
        if ($result) {
            $affectedRows = $this->db->affectedRows();
            return "<tr><td>Chamber Defect Not Removed above 90 days (I):</td><td>".$affectedRows."</td></tr>";  
        } else {
            return "<tr><td>Unable to Insert Chamber Defect Not Removed above 90 days (I) :</td><td>0</td></tr>";
        }
    }

    public function chamber_defectNot_remove_90_I_2($misc_days){//20(done)
        // Define the next court workday
        
        $sql = "INSERT INTO heardt (diary_no, next_dt, mainhead, subhead, board_type, usercode, ent_dt, module_id, mainhead_n, subhead_n, main_supp_flag, listorder, tentative_cl_dt)
                SELECT a.diary_no,
                    '$misc_days',
                    'M',
                    '850',
                    'C',
                    '1',
                    NOW(),
                    '18',
                    'M',
                    '850',
                    '0',
                    '32',
                    '$misc_days'
                FROM obj_save a
                INNER JOIN main b ON a.diary_no = b.diary_no
                LEFT JOIN heardt h ON h.diary_no = b.diary_no
                LEFT JOIN last_heardt l ON l.diary_no = h.diary_no
                    AND l.judges::bigint > 0
                    AND (l.board_type = 'J' OR l.board_type = 'S')
                    AND (l.bench_flag = '' OR l.bench_flag IS NULL)
                    AND l.clno > 0
                WHERE l.diary_no IS NULL
                AND (h.diary_no IS NULL OR h.next_dt IS NULL)  -- Handle zero date
                AND rm_dt IS NULL
                AND display = 'Y'
                AND b.diary_no_rec_date::date < '2018-10-14'
                AND current_date - a.save_dt::date > 93  -- Date difference
                AND b.c_status = 'P'
                AND b.active_fil_no = ''
                GROUP BY a.diary_no
                HAVING a.diary_no IS NULL";
         $result = $this->db->query($sql);
         // echo $this->db->getLastQuery();
         // die();
         
         if ($result) {
            $affectedRows = $this->db->affectedRows();
            return "<tr><td>Chamber Defect Not Removed above 90 days (I2):</td><td>".$affectedRows."</td></tr>";  
        } else {
            return "<tr><td>Unable to Insert Chamber Defect Not Removed above 90 days (I2):</td><td>0</td></tr>";
        }
       
    }

    public function chamber_defectNot_remove_90_U($next_court_work_day_ymd){//19(done)
        $sql1 = "UPDATE heardt h
                    SET next_dt = '$next_court_work_day_ymd',
                        tentative_cl_dt = '$next_court_work_day_ymd',
                        mainhead = 'M',
                        subhead = 850,
                        clno = 0,
                        brd_slno = 0,
                        roster_id = 0,
                        judges = 0,
                        board_type = 'C',
                        usercode = '1',
                        ent_dt = NOW(),
                        module_id = 18,
                        mainhead_n = 'M',
                        subhead_n = 850,
                        main_supp_flag = 0,
                        listorder = 32
                    FROM (
                        SELECT b.diary_no
                        FROM obj_save a
                        INNER JOIN main b ON a.diary_no = b.diary_no
                        LEFT JOIN heardt h2 ON h2.diary_no = b.diary_no  -- Alias heardt as h2 in subquery
                        LEFT JOIN last_heardt l ON l.diary_no = h2.diary_no AND l.judges::bigint > 0 AND (l.board_type = 'J' OR l.board_type = 'S') AND (l.bench_flag = '' OR l.bench_flag IS NULL) AND l.clno > 0
                        WHERE l.diary_no IS NULL
                        AND (h2.diary_no IS NULL OR h2.next_dt IS NULL)
                        AND rm_dt IS NULL
                        AND display = 'Y'
                        AND b.diary_no_rec_date::date < '2018-10-14'  -- Cast to date
                        AND current_date - a.save_dt::date > 93  -- Date difference calculation
                        AND b.c_status = 'P'
                        AND b.active_fil_no = ''
                        GROUP BY b.diary_no
                    ) AS t0
                    WHERE t0.diary_no = h.diary_no";
        $result = $this->db->query($sql1);
        // echo $this->db->getLastQuery();
        // die();
        if ($result) {
            $affectedRows = $this->db->affectedRows();
            return "<tr><td>Chamber Defect Not Removed above 90 days (U):</td><td>".$affectedRows."</td></tr>";  
        } else {
            return "<tr><td>Unable to Update Chamber Defect Not Removed above 90 days (U) :</td><td>0</td></tr>";
        }

    }

    public function nmd_updation(){//18(done)
        $sql1 = "UPDATE heardt h
                SET is_nmd = 'Y'
                FROM (
                    SELECT m.diary_no
                    FROM case_remarks_multiple c
                    INNER JOIN main m ON m.diary_no::bigint = c.diary_no::bigint
                    INNER JOIN heardt h ON h.diary_no::text = m.diary_no::text
                    WHERE c_status = 'P'
                    AND h.mainhead = 'M'
                    AND r_head = 180
                    AND (h.is_nmd = 'N' OR h.is_nmd IS NULL OR h.is_nmd = '')
                    GROUP BY m.diary_no
                ) AS a
                WHERE a.diary_no = h.diary_no";
        $result = $this->db->query($sql1);

        if ($result) {
            $affectedRows = $this->db->affectedRows();
            return "<tr><td>Updated (NMD):</td><td>".$affectedRows."</td></tr>";  
        } else {
            return "<tr><td>Unable to Update (NMD) :</td><td>0</td></tr>";
        }
    }

    public function nmd_removal(){//17(done)

        $sql1 = "UPDATE heardt h
                SET is_nmd = 'N'
                FROM main m
                LEFT JOIN case_remarks_multiple crm ON crm.diary_no::bigint = m.diary_no::bigint AND r_head = 180
                WHERE m.diary_no::bigint = h.diary_no::bigint  -- Join condition moved to FROM clause
                AND crm.diary_no IS NULL
                AND m.c_status = 'P'
                AND h.is_nmd = 'Y'";
        $result = $this->db->query($sql1);

        if ($result) {
            $affectedRows = $this->db->affectedRows();
            return "<tr><td>REMOVAL (NMD):</td><td>".$affectedRows."</td></tr>";  
        } else {
            return "<tr><td>Unable to Update REMOVAL (NMD) :</td><td>0</td></tr>";
        }
    }

    public function no_of_part_heard(){//16(done)
                   
            $builder = $this->db->table('case_remarks_multiple c');
            $builder->select("c.diary_no, c.jcodes,
                (SELECT string_agg(cast(j1 as TEXT), ',' ORDER BY j.judge_seniority)
                FROM not_before nb
                INNER JOIN master.judge j ON nb.j1 = j.jcode
                WHERE cast(nb.diary_no as TEXT)= cast(c.diary_no as TEXT) AND nb.notbef = 'B') AS bef_j,
                (SELECT string_agg(cast(is_retired as TEXT), ',')
                FROM master.judge jj
                WHERE EXISTS (SELECT 1 FROM not_before nbb WHERE cast(nbb.diary_no as TEXT) = cast(c.diary_no as TEXT) AND nbb.j1 = jj.jcode AND nbb.notbef = 'B')) AS is_judge_retired");

            $builder->join('(SELECT c.diary_no, MAX(cl_date) AS max_cl_dt FROM case_remarks_multiple c GROUP BY c.diary_no) AS c1', 'c1.diary_no = c.diary_no AND c1.max_cl_dt = c.cl_date', 'inner');

            $builder->where('c.r_head', 6);
            $builder->groupBy('c.diary_no, c.jcodes');

            $subquery = $builder->getCompiledSelect();

            // Outer query – Cast jcodes in the subquery and use an alias
            $builder_outer = $this->db->table("($subquery) AS a");
            $builder_outer->select('diary_no, jcodes'); // No need to cast again here
            $builder_outer->where("(jcodes != bef_j OR bef_j IS NULL)"); // Compare directly
            $builder_outer->limit(1);
            // echo $builder_outer->getCompiledSelect();
            // die();
            $query = $builder_outer->get();
            $res_row = $query->getResultArray();

            if (!empty($res_row)) {
                foreach ($res_row as $row) {
                    if (empty($row['is_judge_retired'])) {
                        $diary_no = $row['diary_no'];
                        $judges = $row['jcodes'];
                        $judgesArray = explode(',', $judges);
            
                        $sql1 = "INSERT INTO not_before_his (diary_no, j1, notbef, usercode, ent_dt, old_u_ip,cur_u_ip, cur_u_mac, old_u_mac, cur_ucode, c_dt, action, old_res_add, old_res_id, del_reason)
                                SELECT n.diary_no::bigint, n.j1, n.notbef, n.usercode, n.ent_dt, n.u_ip, 0,0,0,'1', NOW(), 'delete', n.res_add, n.res_id, 'PART HEARD'
                                FROM heardt h
                                INNER JOIN main m ON cast(m.diary_no as text) = cast(h.diary_no as text)
                                INNER JOIN not_before n ON cast(m.diary_no as bigint) = cast(n.diary_no as bigint)
                                WHERE c_status = 'P' AND n.notbef = 'B' AND m.diary_no = '$diary_no' AND n.j1 IN ($judges)";
                        $res = $this->db->query($sql1);
                        if ($res == 1) {
                            $deleteResult = $this->db->table('not_before')
                                            ->where('notbef', 'B')
                                            ->where('diary_no', $diary_no)
                                            ->whereIn('j1', $judgesArray)
                                            ->delete();
                            
                            $res2 = $deleteResult;
                        }
                        //  echo "<br><br>";
                        $sql56 = "INSERT INTO not_before(diary_no,j1,notbef,usercode,ent_dt,enterby,res_id,u_ip,u_mac,res_add)
                                    SELECT '$diary_no', jcode,'B','1',NOW(),'1','12','','','' FROM master.judge WHERE jcode IN ($judges)";
                    
                        $res = $this->db->query($sql56);

                    }
                }
                return "<tr><td>Records found for part heard</td><td></td></tr>";
            }
            else {
                return "<tr><td>No records found for part heard</td><td></td></tr>";
            }
            
    }

    public function remove_part_heard_coram_judge_retired(){//15(done)
        
        $diaryNumbers = $this->db->table('not_before n')
            ->select('n.diary_no')
            ->join('master.judge j', 'j.jcode = n.j1', 'inner')
            ->where('n.res_id', 12)
            ->where('n.notbef', 'B')
            ->where('j.display', 'Y')
            ->where('j.is_retired', 'Y')
            ->groupBy('n.diary_no')
            ->get()
            ->getResultArray(); // Use getResultArray() for an array of arrays
        
        if (!empty($diaryNumbers)) {
            foreach ($diaryNumbers as $row) {
                $diary_no = $row['diary_no'];

                $sql78 = "INSERT INTO not_before_his (diary_no, j1, notbef, usercode, ent_dt, old_u_ip, cur_u_ip, cur_u_mac, old_u_mac, cur_ucode, c_dt, action, old_res_add, old_res_id, del_reason)
                            SELECT cast(n.diary_no as bigint), n.j1, n.notbef, n.usercode, n.ent_dt, n.u_ip, '0.0.0.0','', '', '1', NOW(), 'delete', n.res_add, n.res_id, 'PART HEARD RELEASED'
                            FROM not_before n
                            WHERE n.notbef = 'B' AND cast(n.diary_no as bigint) = '$diary_no'";

               
                $insertResult = $this->db->query($sql78);
             
               
                if ($insertResult) {
                    // Delete from not_before
                    $deleteResult = $this->db->table('not_before')
                        ->where('notbef', 'B')
                        ->where('diary_no', $diary_no)
                        ->delete();

                    if (!$deleteResult) {
                        $error = $this->db->error();
                        return '<tr><td>Delete Error: </td><td>' . $error['message']."</td></tr>";  
                    } else {
                        return "<tr><td>Delete Successfully:</td><td>0</td></tr>";
                    }

                } else {
                    $error = $this->db->error();
                    return '<tr><td>Insert Error: </td><td>' . $error['message']."</td></tr>";
                }
            }
        }
    }

    public function inserted_coram_notice(){//14(done)

        $sql11 = "INSERT INTO last_heardt (diary_no, conn_key, next_dt, mainhead, subhead, clno, brd_slno, roster_id, judges, coram, board_type, usercode, ent_dt, module_id, mainhead_n, subhead_n, main_supp_flag, listorder, tentative_cl_dt, lastorder, listed_ia, sitting_judges, list_before_remark, is_nmd, no_of_time_deleted)
                    SELECT j.*
                    FROM (
                        SELECT h.diary_no, h.conn_key, h.next_dt, h.mainhead, h.subhead, h.clno, h.brd_slno, h.roster_id, h.judges, h.coram, h.board_type, h.usercode, h.ent_dt, h.module_id,
                            h.mainhead_n, h.subhead_n, h.main_supp_flag, h.listorder, h.tentative_cl_dt, h.lastorder, h.listed_ia, h.sitting_judges,
                            h.list_before_remark, h.is_nmd, h.no_of_time_deleted
                        FROM (
                            SELECT j.jcode AS new_coram,
                                cast(c.diary_no as bigint), h.conn_key, h.next_dt, h.mainhead, h.subhead, h.clno, h.brd_slno, h.roster_id, h.judges, h.coram, h.board_type, h.usercode, h.ent_dt, h.module_id,
                                h.mainhead_n, h.subhead_n, h.main_supp_flag, h.listorder, h.tentative_cl_dt, m.lastorder, h.listed_ia, h.sitting_judges,
                                h.list_before_remark, h.is_nmd, h.no_of_time_deleted
                            FROM case_remarks_multiple c
                            INNER JOIN (
                                SELECT c.diary_no, MIN(cl_date) AS max_cl_dt
                                FROM case_remarks_multiple c
                                WHERE length(c.jcodes) > 4
                                AND c.r_head IN (3, 62, 181, 182, 183, 184)
                                GROUP BY c.diary_no
                            ) AS c1 ON c1.diary_no = c.diary_no AND c1.max_cl_dt = c.cl_date
                            INNER JOIN heardt h ON h.diary_no::text = c.diary_no::text
                            INNER JOIN main m ON m.diary_no = h.diary_no
                            LEFT JOIN case_remarks_multiple admited ON admited.diary_no = c.diary_no AND length(admited.jcodes) > 4 AND admited.r_head = 1
                            INNER JOIN master.judge j ON CAST(j.jcode AS TEXT) = ANY(string_to_array(c.jcodes, ',')) AND j.is_retired = 'N' AND j.display = 'Y'
                            LEFT JOIN master.sc_working_days sw ON sw.working_date = c.cl_date AND sw.display = 'Y' AND sw.holiday_description LIKE '%Summer Vacation%'
                            WHERE admited.diary_no IS NULL
                            AND h.list_before_remark != 11
                            AND h.list_before_remark != 14
                            AND c.cl_date > '2023-10-01'
                            AND sw.working_date IS NULL
                            AND CASE
                                WHEN c.jcodes LIKE '%258%' THEN c1.max_cl_dt > '2019-04-07' AND c.r_head IN (3, 62, 181, 182, 183, 184)
                                ELSE c.r_head IN (3, 62, 181, 182, 183, 184)
                            END
                            AND h.mainhead = 'M'
                            AND h.clno = 0
                            AND h.brd_slno = 0
                            AND m.c_status = 'P'
                            AND c.jcodes != '0'
                            AND c.jcodes != ''
                            AND (c.jcodes != h.coram OR h.coram IS NULL OR h.coram = '' OR h.coram = '0')
                            AND m.casetype_id NOT IN (39, 19, 20, 34, 35)
                            AND m.active_casetype_id NOT IN (39, 19, 20, 34, 35)
                            GROUP BY j.jcode, c.diary_no, h.conn_key, h.next_dt, h.mainhead, h.subhead, h.clno, h.brd_slno, h.roster_id, h.judges, h.coram, h.board_type, h.usercode, h.ent_dt, h.module_id,
                                    h.mainhead_n, h.subhead_n, h.main_supp_flag, h.listorder, h.tentative_cl_dt, m.lastorder, h.listed_ia, h.sitting_judges,
                                    h.list_before_remark, h.is_nmd, h.no_of_time_deleted  -- Corrected GROUP BY
                        ) AS h
                        WHERE (h.new_coram::TEXT != h.coram::TEXT OR (h.new_coram IS NULL AND h.coram IS NOT NULL AND h.coram != '')) -- Cast to TEXT
                    ) AS j
                    LEFT JOIN last_heardt l ON j.diary_no::text = l.diary_no::text
                        AND l.next_dt = j.next_dt
                        AND l.listorder = j.listorder
                        AND l.mainhead = j.mainhead
                        AND l.subhead = j.subhead
                        AND l.roster_id = j.roster_id
                        AND l.judges = j.judges
                        AND l.clno = j.clno
                        AND l.main_supp_flag = j.main_supp_flag
                        AND l.ent_dt = j.ent_dt
                        AND (l.bench_flag = '' OR l.bench_flag IS NULL)
                    WHERE l.diary_no IS NULL";


        
        $result = $this->db->query($sql11);
        if ($result) {
            $affectedRows = $this->db->affectedRows();
            return "<tr><td>Inserted Coram(Notice):</td><td>".$affectedRows."</td></tr>";  
        } else {
            return "<tr><td>Unable to Insert Coram (Notice) :</td><td>0</td></tr>";
        }
        
    }
    public function updated_coram_notice(){//13(done)
        $sql12 = "UPDATE heardt h
                    SET coram = t0.new_coram,
                        list_before_remark = CASE WHEN t0.r_head = 1 THEN 15 ELSE 14 END,
                        usercode = '1',
                        ent_dt = NOW(),
                        module_id = '18'
                    FROM (
                        SELECT c.diary_no,
                            c.jcodes,
                            j.jcode AS new_coram,
                            h.list_before_remark,
                            c.r_head,
                            h.coram
                        FROM case_remarks_multiple c
                        INNER JOIN (
                            SELECT c.diary_no, MIN(cl_date) AS max_cl_dt
                            FROM case_remarks_multiple c
                            WHERE length(c.jcodes) > 4
                            AND c.r_head IN (3, 62, 181, 182, 183, 184)
                            GROUP BY c.diary_no
                        ) AS c1 ON c1.diary_no = c.diary_no AND c1.max_cl_dt = c.cl_date
                        INNER JOIN heardt h ON h.diary_no::text = c.diary_no::text
                        INNER JOIN main m ON m.diary_no = h.diary_no
                        INNER JOIN master.judge j ON CAST(j.jcode AS TEXT) = ANY(string_to_array(c.jcodes, ',')) AND j.is_retired = 'N' AND j.display = 'Y'
                        LEFT JOIN case_remarks_multiple admited ON admited.diary_no = c.diary_no AND length(admited.jcodes) > 4 AND admited.r_head = 1
                        LEFT JOIN master.sc_working_days sw ON sw.working_date = c.cl_date AND sw.display = 'Y' AND sw.holiday_description LIKE '%Summer Vacation%'
                        WHERE admited.diary_no IS NULL
                        AND h.list_before_remark != 11
                        AND h.list_before_remark != 14
                        AND c.cl_date > '2023-10-01'
                        AND sw.working_date IS NULL
                        AND CASE
                            WHEN c.jcodes LIKE '%258%' THEN c1.max_cl_dt > '2019-04-07' AND c.r_head IN (3, 62, 181, 182, 183, 184)
                            ELSE c.r_head IN (3, 62, 181, 182, 183, 184)
                        END
                        AND h.mainhead = 'M'
                        AND h.clno = 0
                        AND h.brd_slno = 0
                        AND m.c_status = 'P'
                        AND c.jcodes != '0'
                        AND c.jcodes != ''
                        AND (c.jcodes != h.coram OR h.coram IS NULL OR h.coram = '' OR h.coram = '0')
                        AND m.casetype_id NOT IN (39, 19, 20, 34, 35)
                        AND m.active_casetype_id NOT IN (39, 19, 20, 34, 35)
                        GROUP BY c.diary_no, c.jcodes, j.jcode, h.list_before_remark, c.r_head, h.coram  -- Add missing GROUP BY columns
                    ) AS t0
                    WHERE (t0.new_coram::TEXT != h.coram::TEXT OR (t0.new_coram IS NULL AND h.coram IS NOT NULL AND h.coram != '')); -- Cast to TEXT for comparison";
             
        $result = $this->db->query($sql12);
        
        if ($result) {
            $affectedRows = $this->db->affectedRows();
            return "<tr><td>Updated Coram (Notice):</td><td>".$affectedRows."</td></tr>";  
        } else {
            return "<tr><td>Unable to Update Coram (Notice) :</td><td>0</td></tr>";
        }

        
    }
    public function inserted_coram_given_by_chief(){//12(done)

        $sql11c = "INSERT INTO last_heardt (diary_no, conn_key, next_dt, mainhead, subhead, clno, brd_slno, roster_id, judges, coram, board_type, usercode, ent_dt, module_id, mainhead_n, subhead_n, main_supp_flag, listorder, tentative_cl_dt, lastorder, listed_ia, sitting_judges, list_before_remark, is_nmd, no_of_time_deleted)
                    SELECT j.* FROM (
                        SELECT h.diary_no, h.conn_key, h.next_dt, h.mainhead, h.subhead, h.clno, h.brd_slno, h.roster_id, h.judges, h.coram, h.board_type, h.usercode, h.ent_dt, h.module_id,
                            h.mainhead_n, h.subhead_n, h.main_supp_flag, h.listorder, h.tentative_cl_dt, m.lastorder, h.listed_ia, h.sitting_judges,
                            h.list_before_remark, h.is_nmd, h.no_of_time_deleted
                        FROM case_remarks_multiple c
                        INNER JOIN (
                            SELECT c.diary_no, MAX(cl_date) AS max_cl_dt
                            FROM case_remarks_multiple c
                            WHERE length(c.jcodes) > 4
                            GROUP BY c.diary_no
                        ) AS c1 ON c1.diary_no = c.diary_no AND c1.max_cl_dt = c.cl_date
                        INNER JOIN heardt h ON h.diary_no::text = c.diary_no::text
                        INNER JOIN main m ON m.diary_no = h.diary_no
                        LEFT JOIN master.judge j ON CAST(j.jcode AS TEXT) = ANY(string_to_array(c.jcodes, ',')) AND j.is_retired = 'N' AND j.display = 'Y'
                        LEFT JOIN master.sc_working_days sw ON sw.working_date = c.cl_date AND sw.display = 'Y' AND sw.holiday_description LIKE '%Summer Vacation%'
                        WHERE h.list_before_remark != 11
                        AND c.cl_date > '2017-05-08'
                        AND sw.working_date IS NULL
                        AND (
                            CASE
                            WHEN c.jcodes LIKE '%258%' THEN c1.max_cl_dt > '2019-04-07' AND r_head IN (1)
                            ELSE r_head IN (1)
                            END
                        )
                        AND h.mainhead = 'M'
                        AND h.clno = 0
                        AND h.brd_slno = 0
                        AND m.c_status = 'P'
                        AND (c.jcodes != '0' AND c.jcodes != '')
                        AND (c.jcodes != h.coram OR h.coram IS NULL OR h.coram = '' OR h.coram = '0')
                        GROUP BY h.diary_no, h.conn_key, h.next_dt, h.mainhead, h.subhead, h.clno, h.brd_slno, h.roster_id, h.judges, h.coram, h.board_type, h.usercode, h.ent_dt, h.module_id, h.mainhead_n, h.subhead_n, h.main_supp_flag, h.listorder, h.tentative_cl_dt, m.lastorder, h.listed_ia, h.sitting_judges, h.list_before_remark, h.is_nmd, h.no_of_time_deleted, c.jcodes -- Added missing GROUP BY columns
                    ) j
                    LEFT JOIN last_heardt l ON j.diary_no = l.diary_no
                                        AND l.next_dt = j.next_dt
                                        AND l.listorder = j.listorder
                                        AND l.mainhead = j.mainhead
                                        AND l.subhead = j.subhead
                                        AND l.roster_id = j.roster_id
                                        AND l.judges = j.judges
                                        AND l.clno = j.clno
                                        AND l.main_supp_flag = j.main_supp_flag
                                        AND l.ent_dt = j.ent_dt
                                        AND (l.bench_flag = '' OR l.bench_flag IS NULL)
                    WHERE l.diary_no IS NULL";
        $result = $this->db->query($sql11c);
        
        if ($result) {
            $affectedRows = $this->db->affectedRows();
            return "<tr><td>Inserted Coram(Given By Chief):</td><td>".$affectedRows."</td></tr>";  
        } else {
            return "<tr><td>Unable to Inserted Coram(Given By Chief) :</td><td>0</td></tr>";
        }
    }
    public function updated_coram_given_by_chief(){ //11(done)
    

        $sql = "UPDATE heardt h
                SET coram = t0.new_coram,
                    list_before_remark = CASE WHEN r_head = 1 THEN 15 ELSE 14 END,
                    usercode = '1',
                    ent_dt = NOW(),
                    module_id = '18'
                FROM (
                    SELECT *
                    FROM (
                        SELECT c.diary_no,
                            c.jcodes,
                            (
                                SELECT jcode
                                FROM master.judge j
                                WHERE j.jcode::TEXT = ANY(string_to_array(c.jcodes, ',')) AND j.is_retired = 'N' AND j.display = 'Y'
                                LIMIT 1
                            ) AS new_coram,
                            h.list_before_remark,
                            c.r_head,
                            h.coram
                        FROM case_remarks_multiple c
                        INNER JOIN (
                            SELECT c.diary_no, MAX(cl_date) AS max_cl_dt
                            FROM case_remarks_multiple c
                            WHERE length(c.jcodes) > 4
                            GROUP BY c.diary_no
                        ) AS c1 ON c1.diary_no = c.diary_no AND c1.max_cl_dt = c.cl_date
                        INNER JOIN heardt h ON h.diary_no = c.diary_no::bigint 
                        INNER JOIN main m ON m.diary_no = h.diary_no
                        LEFT JOIN master.judge j ON j.jcode::TEXT = ANY(string_to_array(c.jcodes, ',')) AND j.is_retired = 'N' AND j.display = 'Y'  -- Use string_to_array and ANY
                        LEFT JOIN master.sc_working_days sw ON sw.working_date = c.cl_date AND sw.display = 'Y' AND sw.holiday_description LIKE '%Summer Vacation%'
                        WHERE h.list_before_remark != 11
                        AND c.cl_date > '2017-05-08'
                        AND sw.working_date IS NULL
                        AND (
                            (c.jcodes LIKE '%258%' AND c1.max_cl_dt > '2019-04-07' AND c.r_head IN (1)) OR
                            c.r_head IN (1)
                        )
                        AND h.mainhead = 'M'
                        AND h.clno = 0
                        AND h.brd_slno = 0
                        AND m.c_status = 'P'
                        AND c.jcodes != '0'
                        AND c.jcodes != ''
                        AND (c.jcodes != h.coram OR h.coram IS NULL OR h.coram = '' OR h.coram = '0')
                        GROUP BY c.diary_no, c.jcodes, h.list_before_remark, c.r_head, h.coram
                    ) AS t
                    WHERE (new_coram::TEXT != coram::TEXT) OR (new_coram IS NULL AND coram IS NOT NULL AND coram != '')  -- Explicit cast to TEXT for comparison
                ) AS t0
                WHERE h.diary_no = t0.diary_no::bigint";

        $updateQuery = $this->db->query($sql);
       
        if ($updateQuery) {
            $affectedRows = $this->db->affectedRows();
            return "<tr><td>Updated Coram (MA CONT):</td><td>".$affectedRows."</td></tr>";  
        } else {
            return "<tr><td>Unable to Updated Coram (MA CONT) :</td><td>0</td></tr>";
        }
         
    }

    
    public function inserted_coram_ma_count(){//10(done)
        $builder = $this->db->table('last_heardt');

        $subQuery1 = $this->db->table('lowerct l')
        ->select('h.diary_no, h.conn_key, h.next_dt, h.mainhead, h.subhead, h.clno, h.brd_slno, h.roster_id, h.judges, h.coram, h.board_type, h.usercode, h.ent_dt, h.module_id, h.mainhead_n, h.subhead_n, h.main_supp_flag, h.listorder, h.tentative_cl_dt, m.lastorder, h.listed_ia, h.sitting_judges, h.list_before_remark, h.is_nmd, h.no_of_time_deleted, MAX(l.lower_court_id) AS lct_crt_id')
        ->join('main m', 'm.diary_no = l.diary_no')
        ->join('heardt h', 'h.diary_no = m.diary_no')
        ->where('m.c_status', 'P')
        ->where('h.clno', 0)
        ->where('h.main_supp_flag', 0)
        ->whereIn('m.active_casetype_id', [19, 20, 39])
        ->orWhereIn('m.casetype_id', [19, 20, 39])
        ->groupStart()
        ->where('h.coram', '0')
        ->orWhere('h.coram IS NULL')
        ->orWhere('h.coram', '')
        ->groupEnd()
        ->groupBy('h.diary_no, h.conn_key, h.next_dt, h.mainhead, h.subhead, h.clno, h.brd_slno, h.roster_id, h.judges, h.coram, h.board_type, h.usercode, h.ent_dt, h.module_id, h.mainhead_n, h.subhead_n, h.main_supp_flag, h.listorder, h.tentative_cl_dt, m.lastorder, h.listed_ia, h.sitting_judges, h.list_before_remark, h.is_nmd, h.no_of_time_deleted');

        $subQuery2 = $this->db->table('(' . $subQuery1->getCompiledSelect() . ') AS t1')
        ->select('COUNT(lj.lowerct_id) AS count_judges, t1.*')
        ->join('lowerct_judges lj', 'lj.lowerct_id = t1.lct_crt_id AND lj.lct_display = \'Y\'', 'left')
        ->join('master.judge j', 'j.jcode = lj.judge_id AND j.jtype = \'J\' AND j.is_retired != \'Y\'', 'left')
        ->where('j.jcode IS NOT NULL')
        ->groupBy('t1.lct_crt_id, t1.diary_no, t1.conn_key, t1.next_dt, t1.mainhead, t1.subhead, t1.clno, t1.brd_slno, t1.roster_id, t1.judges, t1.coram, t1.board_type, t1.usercode, t1.ent_dt, t1.module_id, t1.mainhead_n, t1.subhead_n, t1.main_supp_flag, t1.listorder, t1.tentative_cl_dt, t1.lastorder, t1.listed_ia, t1.sitting_judges, t1.list_before_remark, t1.is_nmd, t1.no_of_time_deleted');

        $subQuery3 = $this->db->table('heardt h')
            ->select('m.diary_no, h.next_dt, h.clno')
            ->join('main m', 'm.diary_no = h.diary_no')
            ->where('h.mainhead', 'M')
            ->whereIn('h.board_type', ['J', 'S'])
            ->where('h.clno > 0')
            ->where('m.c_status', 'P')
            ->whereIn('m.active_casetype_id', [19, 20, 39])
            ->orWhereIn('m.casetype_id', [19, 20, 39])
            ->union($this->db->table('last_heardt h')
                ->select('m.diary_no, h.next_dt, h.clno')
                ->join('main m', 'm.diary_no = h.diary_no')
                ->where('h.mainhead', 'M')
                ->whereIn('h.board_type', ['J', 'S'])
                ->where('h.clno > 0')
                ->where('m.c_status', 'P')
                ->whereIn('m.active_casetype_id', [19, 20, 39])
                ->orWhereIn('m.casetype_id', [19, 20, 39])
                ->where('h.bench_flag', '')
                ->orWhere('h.bench_flag IS NULL'));


                $mainQuery = $this->db->table('(' . $subQuery2->getCompiledSelect() . ') AS j') // Alias here too!
                ->select('j.*')
                ->join('last_heardt l', 'j.diary_no = l.diary_no AND j.next_dt = l.next_dt AND j.listorder = l.listorder AND j.mainhead = l.mainhead AND j.subhead = l.subhead AND j.roster_id = l.roster_id AND j.judges = l.judges AND j.clno = l.clno AND j.main_supp_flag = l.main_supp_flag AND j.ent_dt = l.ent_dt AND (l.bench_flag = \'\' OR l.bench_flag IS NULL)', 'left')
                ->join('(' . $subQuery3->getCompiledSelect() . ') AS t3', 't3.diary_no = j.diary_no', 'left') // And here!
                ->where('l.diary_no IS NULL')
                ->where('t3.diary_no IS NULL')
                ->where('count_judges >= 2');
  
        // Get the data to be inserted
        $recordsToInsert = $mainQuery->get()->getResultArray();
        //  echo $this->db->getLastQuery();
        //     die();
        if (!empty($recordsToInsert)) {
            $builder->insertBatch($recordsToInsert); // Use insertBatch for efficiency
            $affectedRows = $this->db->affectedRows();
            return "<tr><td>Inserted Coram(MA CONT):</td><td>".$affectedRows."</td></tr>";  
        } else {
            $error = $this->db->error();
            return "<tr><td>Unable to Insert Coram (MA CONT) :</td><td>0</td></tr>";
        }
        
        
    }
    public function Updated_coram_ma_count(){//9(done)
             // Subquery t3
         $subquery1 = $this->db->table('lowerct l')
             ->select('l.diary_no, MAX(m.reg_no_display) AS reg_no_display, MAX(l.lower_court_id) AS lct_crt_id, MAX(h.coram) AS coram')
             ->join('main m', 'm.diary_no = l.diary_no', 'inner')
             ->join('heardt h', 'h.diary_no = m.diary_no', 'inner')
             ->where('c_status', 'P')
             ->where('h.clno', 0)
             ->where('h.main_supp_flag', 0)
             ->whereIn('m.active_casetype_id', [19, 20, 39])
             ->orWhereIn('casetype_id', [19, 20, 39])
             ->where('h.coram', '0')
             ->orWhere('h.coram IS NULL')
             ->orWhere('h.coram', '')
             ->groupBy('l.diary_no')
             ->getCompiledSelect(); // Get compiled select for use in subquery
 
         $subquery2 = $this->db->table("($subquery1) t")
             ->select('t.diary_no, MAX(t.reg_no_display) AS reg_no_display, COUNT(lj.lowerct_id) AS count_judges, STRING_AGG(cast(lj.judge_id as TEXT), \',\') AS jid')
             ->join('lowerct_judges lj', 'lj.lowerct_id = t.lct_crt_id AND lj.lct_display = \'Y\'', 'left')
             ->join('master.judge j', 'j.jcode = lj.judge_id AND j.jtype = \'J\' AND j.is_retired != \'Y\'', 'left')
             ->where('j.jcode IS NOT NULL')
             ->groupBy('t.diary_no, t.reg_no_display')
             ->getCompiledSelect();
 
         $subquery3_union1 = $this->db->table('heardt h')
             ->select('m.diary_no, next_dt, clno')
             ->join('main m', 'm.diary_no = h.diary_no', 'inner')
             ->where('h.mainhead', 'M')
             ->whereIn('h.board_type', ['J', 'S'])
             ->where('clno >', 0)
             ->where('c_status', 'P')
             ->whereIn('m.active_casetype_id', [19, 20, 39])
             ->orWhereIn('casetype_id', [19, 20, 39])
             ->getCompiledSelect();
 
         $subquery3_union2 = $this->db->table('last_heardt h')
             ->select('m.diary_no, next_dt, clno')
             ->join('main m', 'm.diary_no = h.diary_no', 'inner')
             ->where('h.mainhead', 'M')
             ->whereIn('h.board_type', ['J', 'S'])
             ->where('clno >', 0)
             ->where('c_status', 'P')
             ->whereIn('m.active_casetype_id', [19, 20, 39])
             ->orWhereIn('casetype_id', [19, 20, 39])
             ->where('bench_flag', '')
             ->orWhere('bench_flag IS NULL')
             ->getCompiledSelect();
 
        $subquery3 = $this->db->table("($subquery3_union1 UNION $subquery3_union2) t3")
             ->select('t3.diary_no, t3.next_dt, t3.clno')
             ->getCompiledSelect();
             
             $builder = $this->db->table("($subquery2) t2")
             ->select('t2.diary_no') // Select ONLY diary_no
             ->join("($subquery3) t3", 't3.diary_no = t2.diary_no', 'left')
             ->where('t3.diary_no IS NULL')
             ->where('count_judges >=', 2);
 
         $data = [
             'coram' => 't0.jid',  // Note: This is a string, not a direct value
             'list_before_remark' => 17,
             'usercode' => '1',
             'ent_dt' => date('Y-m-d H:i:s'), // Current timestamp
             'module_id' => '18',
         ];
 
         $updateQuery = $this->db->table('heardt h')
             ->set($data, false) // Prevent CodeIgniter from escaping the string 't0.jid'
             ->whereIn('diary_no', $builder) // Use whereIn with the subquery
             ->update();
            // echo $this->db->getLastQuery();
            // die();
           
            if ($updateQuery) {
                $affectedRows = $this->db->affectedRows();
                return "<tr><td>Updated Coram (MA CONT):</td><td>".$affectedRows."</td></tr>";  
            } else {
                return "<tr><td>Unable to Updated Coram (MA CONT) :</td><td>0</td></tr>";
            }
    }

    public function updated_unreg_fil_dt(){//8(done)

        $builder = $this->db->table('main AS a');

        // Inner subquery (same as before):
        $innerSubquery = $this->db->table('main AS m')
            ->select("m.diary_no as diarynumber, m.fil_dt, c_status, ht.next_dt || '/' || ht.board_type,
                CASE
                    WHEN (ARRAY_AGG(lt.next_dt || '/' || lt.board_type ORDER BY lt.next_dt))[1] IS NULL THEN ht.next_dt || '/' || ht.board_type
                    ELSE (ARRAY_AGG(lt.next_dt || '/' || lt.board_type ORDER BY lt.next_dt))[1]
                END AS ss,
                CASE
                    WHEN (ARRAY_AGG(lt.next_dt ORDER BY lt.next_dt))[1] IS NULL THEN ht.next_dt
                    ELSE (ARRAY_AGG(lt.next_dt ORDER BY lt.next_dt))[1]
                END AS ss2")
            ->join('docdetails AS e', 'e.diary_no = m.diary_no AND e.display = \'Y\'')
            ->join('master.docmaster AS f', 'f.doccode = e.doccode AND f.doccode1 = e.doccode1 AND f.display = \'Y\'', 'left')
            ->join('heardt AS ht', 'ht.diary_no = m.diary_no AND ht.clno != 0', 'left')
            ->join('last_heardt AS lt', 'lt.diary_no = m.diary_no AND lt.clno != 0 AND (lt.bench_flag IS NULL OR lt.bench_flag = \'\')', 'left')
            ->where('m.c_status', 'P')
            ->where('m.diary_no_rec_date <= CURRENT_DATE', NULL, FALSE)
            ->where('m.fil_dt = \'0001-01-01\'', NULL, FALSE) // Or IS NULL if that's the intent
            ->where('m.unreg_fil_dt = \'0001-01-01\'', NULL, FALSE)
            ->groupBy('m.diary_no, ht.next_dt, ht.board_type')
            ->getCompiledSelect();

        $builder->set('unreg_fil_dt', "CASE WHEN (SELECT ss2 FROM ({$innerSubquery}) AS b WHERE diary_no = a.diary_no AND b.ss IS NOT NULL AND b.ss LIKE '%/J%') IS NOT NULL THEN (SELECT cast(ss2 as date) FROM ({$innerSubquery}) AS b WHERE diary_no = a.diary_no AND b.ss IS NOT NULL AND b.ss LIKE '%/J%') ELSE NULL END", FALSE);


        $builder->where("EXISTS (
            SELECT 1
            FROM ({$innerSubquery}) AS b
            WHERE a.diary_no = b.diarynumber AND b.ss IS NOT NULL AND b.ss LIKE '%/J%'
        )");


        $result = $builder->update();
        // echo $this->db->getLastQuery();
        // die();
            if ($result) {
                $affectedRows = $this->db->affectedRows();
                return "<tr><td>Updated Unreg fil dt :</td><td>".$affectedRows."</td></tr>";  
            } else {
                return "<tr><td>Unable to Updated Unreg fil dt :</td><td>0</td></tr>";
            }
            
    }

    public function tp_head_changed(){//7(done)

        $builder = $this->db->table('heardt AS h');

        $builder->set('subhead', '829'); // Or 829 if subhead is an integer

        $builder->where('h.mainhead', 'M');
        $builder->where('h.board_type', 'J');
        $builder->where('h.main_supp_flag', 0);
        $builder->where('h.subhead !=', 829); // Or 829 if subhead is an integer

        // Subquery with joins and conditions:
        $subquery = $this->db->table('main AS m')
            ->select('m.diary_no') // Select only what's needed for the correlation
            ->join('mul_category AS mc', 'mc.diary_no = m.diary_no AND mc.display = \'Y\'')
            ->where('m.c_status', 'P')
            ->where('mc.submaster_id IN (176, 222)')
            ->getCompiledSelect();

        // WHERE clause with the IN operator for the correlation:
        $builder->where("h.diary_no IN ({$subquery})");

        $result = $builder->update();
        // echo $this->db->getLastQuery();
        // die();
        if ($result) {
            $affectedRows = $this->db->affectedRows();
            return "<tr><td>TP Head changed :</td><td>".$affectedRows."</td></tr>";  
        } else {
            return "<tr><td>Unable to TP Head changed :</td><td>0</td></tr>";
        }
    }
    public function bail_category_head_changed(){//6(done)
        $builder = $this->db->table('heardt AS h');

        $builder->set('subhead', '804'); // Or 804 if subhead is an integer

        $builder->where('h.mainhead', 'M');
        $builder->where('h.board_type', 'J');
        $builder->where('h.main_supp_flag', 0);
        $builder->where('h.subhead !=', 804); // Or 804 if subhead is an integer

        // Subquery with joins and conditions:
        $subquery = $this->db->table('main AS m')
            ->select('m.diary_no') // Select only what's needed for the correlation
            ->join('mul_category AS mc', 'mc.diary_no = m.diary_no AND mc.display = \'Y\'')
            ->where('m.c_status', 'P')
            ->where('mc.submaster_id IN (173)')
            ->getCompiledSelect();

        // WHERE clause with the IN operator for the correlation:
        $builder->where("h.diary_no IN ({$subquery})");
        $result = $builder->update();
        // echo $this->db->getLastQuery();
        // die();
        if ($result) {
            $affectedRows = $this->db->affectedRows();
            return "<tr><td>Bail Category Head changed :</td><td>".$affectedRows."</td></tr>";  
        } else {
            return "<tr><td>Unable to Bail Category Head changed :</td><td>0</td></tr>";
        }
       
    }
    public function tp_bail_ia(){//5(done)
        $builder = $this->db->table('heardt AS h');

        $builder->set('subhead', '804'); // Or 804 if subhead is an integer
        $builder->where('h.mainhead', 'M');
        $builder->where('h.board_type', 'J');
        $builder->where('h.main_supp_flag', 0);
        $builder->where('h.subhead !=', 804); // Or '804' if subhead is a text type

        // Correlated subquery for the joins:
            $builder->where("EXISTS (
                SELECT 1
                FROM main AS m
                INNER JOIN mul_category AS mc ON mc.diary_no = m.diary_no AND mc.display = 'Y'
                INNER JOIN docdetails AS d ON d.diary_no = m.diary_no AND d.display = 'Y' AND d.iastat = 'P' AND d.doccode = 8
                WHERE m.diary_no = h.diary_no and m.c_status = 'P' and d.doccode1 IN (40, 41, 48, 49, 71, 72, 118, 131, 211, 309)
            )");


        $result = $builder->update();
        // echo $this->db->getLastQuery();
        // die();
        if ($result) {
            $affectedRows = $this->db->affectedRows();
            return "<tr><td>TP Bail IA :</td><td>".$affectedRows."</td></tr>";  
        } else {
            return "<tr><td>Unable to TP Bail IA :</td><td>0</td></tr>";
        }
    
        
    }
    public function freshly_adjourned_head_changed(){//4(done)
        $builder = $this->db->table('public.heardt AS h'); // Alias the heardt table as 'h'

        $builder->set(['subhead' => 830]); // Or 830 if subhead is an integer
        $builder->where('mainhead', 'M');
        $builder->where('board_type', 'J');
        $builder->where('main_supp_flag', 0);
        $builder->where('listorder', 25);
        $builder->where('subhead !=', 830); // Or 830 if subhead is an integer

        // Correlated subquery in the WHERE clause using EXISTS:
        $builder->where("EXISTS (
            SELECT 1
            FROM public.main AS m
            WHERE m.diary_no = h.diary_no and c_status = 'P'
        )");
    // echo $this->db->getLastQuery();

        $result = $builder->update(); // Execute the update
        
        if ($result) {
            $affectedRows = $this->db->affectedRows();
            return "<tr><td>Freshly Adjourned Head changed:</td><td>".$affectedRows."</td></tr>";  
        } else {
            return "<tr><td>Unable to Freshly Adjourned Head changed :</td><td>0</td></tr>";
        }
    }

    public function last_heardt_bench_flag(){//3(done)
      
        $builder = $this->db->table('last_heardt');
        $builder->set(['bench_flag' => 'X']); // Set the value
        $builder->where('next_dt > CURRENT_DATE', null, false); // next_dt > CURRENT_DATE  (using date() for current date)
        $builder->where('clno >', 0);
        $builder->where('brd_slno >', 0);
        $builder->where("COALESCE(cast(bench_flag as TEXT), '') = ''");// Handle both NULL and empty string
        
        $result = $builder->update(); // Execute the update
       // echo $this->db->getLastQuery();
       // die();
        if ($result) {
            $affectedRows = $this->db->affectedRows();
            return "<tr><td>last_heardt Bench Flag X :</td><td>".$affectedRows."</td></tr>";  
        } else {
            return "<tr><td>Unable to last_heardt Bench Flag X :</td><td>0</td></tr>";
        }
    }

    public function old_after_notice(){//2(done)
        $builder = $this->db->table('heardt AS h');

        $builder->set('subhead', "CASE WHEN (SELECT case_grp FROM main WHERE diary_no = h.diary_no) = 'R' THEN 814 ELSE 813 END", FALSE);

        $builder->where("EXISTS (
            SELECT 1
            FROM main AS m
            INNER JOIN case_remarks_multiple AS crm ON crm.diary_no = cast(m.diary_no as text) AND crm.cl_date >= '2024-01-16'
            WHERE m.c_status = 'P' AND m.diary_no = h.diary_no
        )");

        $builder->where('h.subhead', 831);
        $builder->where('h.usercode', 1);
        $builder->where('h.module_id', '18');
        $builder->where('h.main_supp_flag', 0);
        $result = $builder->update();
        //echo $this->db->getLastQuery();
        if ($result) {
            $affectedRows = $this->db->affectedRows();
            return "<tr><td>Old After Notice Changed:</td><td>".$affectedRows."</td></tr>";  
        } else {
            return "<tr><td>Unable to Old After Notice Changed :</td><td>0</td></tr>";
        }
    }

    public function not_before_judge_entry(){//1(done)
       $remarks = $this->db->table('case_remarks_multiple')
            ->select(['diary_no', 'cl_date', 'head_content'])
            ->where('r_head', 186)
           // ->where('DATE(e_date)', date('Y-m-d')) // Important: Use date() for current date string
            ->where('DATE(e_date)', '2023-01-06') // Important: Use date() for current date string
            ->orderBy('e_date', 'DESC')
            ->get()
            ->getResultArray();
        if ($remarks) {
            foreach ($remarks as $row) {
                $head_content_explode = explode(",", $row['head_content']);
                foreach ($head_content_explode as $val) {
                    $filtered_judge_id = ltrim(preg_replace('/[^0-9]/', '', $val), "0");
                    if ($filtered_judge_id > 0) {
                        $not_before_exists = $this->db->table('not_before')
                            ->where('cast(diary_no as TEXT)', $row['diary_no'])
                            ->where('cast(j1 as INT)', $filtered_judge_id)
                            ->where('notbef', 'N')
                            ->countAllResults() > 0; // More efficient check
                           
                        if (!$not_before_exists) {
                            $insertData = [
                                'diary_no' => $row['diary_no'],
                                'j1' => $filtered_judge_id,
                                'notbef' => 'N',
                                'usercode' => 1,
                                'ent_dt' => new RawSql('NOW()'), // Use RawSql for NOW()
                                'enterby' => 1,
                                'res_id' => 0,
                                'u_ip' => 0,
                                'u_mac' => 0,
                                'res_add' => 0
                            ];
        
                            $this->db->table('not_before')->insert($insertData);
                        }
                    }
                }
            }
        }
        

    }

    public function vc_content(){
        //physical_hearing_advocate_vc_consent(table not exists)
        // echo $cur_time = date('H:i:s');
        // if ($cur_time > '08:05:00') {
        //     //if($cur_time > '13:00:05'){
        //     echo "CONDITION FOR VC : greater than 8:05 PM<BR>";
        //     //todo::DATE_ADD(curdate(), INTERVAL 1 DAY)
        //     $ph_stmt = $dbo_ph->prepare("select p.* from physical_hearing_advocate_consent p where p.is_deleted = 'f' and p.consent = 'V' and p.next_dt = curdate() ");
        //     //$ph_stmt = $dbo_ph->prepare("select p.* from physical_hearing_advocate_vc_consent p where p.is_deleted = 'f' and p.consent = 'V' and p.next_dt = DATE_ADD(curdate(), INTERVAL 4 DAY) ");
        //     //   $ph_stmt = $dbo_ph->prepare("select p.* from physical_hearing_advocate_vc_consent p where p.is_deleted = 'f' and p.consent = 'V' and p.next_dt = '2022-02-15' ");
        //     $ph_stmt->execute();
        //     if ($ph_stmt->rowCount() > 0) {
        //         $ph_row = $ph_stmt->fetchAll(PDO::FETCH_ASSOC);
        //         foreach ($ph_row as $row => $data) {
        //             $vc_consent = "insert into consent_through_email (diary_no, conn_key, next_dt, roster_id, part, main_supp_flag, applicant_type, party_id, advocate_id, entry_source, user_id)
        // SELECT h.diary_no, h.conn_key, h.next_dt, h.roster_id, h.clno as part, h.main_supp_flag, '1' as applicant_type, '0' as party_id, '" . $data['advocate_id'] . "', '2' as entry_source, '1' as user_id
        // from heardt h
        // inner join main m on m.diary_no = h.diary_no 
        // left join consent_through_email c on c.diary_no = m.diary_no and c.next_dt = h.next_dt and c.next_dt = '" . $data['next_dt'] . "' and c.advocate_id = '" . $data['advocate_id'] . "'
        // where find_in_set(m.diary_no,  '" . $data['consent_for_diary_nos'] . "') and c.diary_no is null and h.next_dt = curdate() and h.clno > 0 ";
        //             //TODO::DATE_ADD(curdate(), INTERVAL 3 DAY) REPLACE 3 WITH 1 DAY
        //             $vc_consent_result = mysql_query($vc_consent) or die(mysql_error());
        //             if (mysql_affected_rows() > 0) {
        //                 echo " VC Consent : " . mysql_affected_rows();
        //             }
        //         }
        //     }
        // }
        $current_time = date('H:i:s');
        
        if ($current_time > '08:05:00') {
            echo "CONDITION FOR VC : greater than 8:05 PM<BR>";
        
            $ph_rows = $this->db->table('physical_hearing_advocate_consent')
                ->where('is_deleted', 'f')
                ->where('consent', 'V')
                ->where('next_dt', date('Y-m-d')) // Use CodeIgniter's date function for current date
                ->get()
                ->getResultArray();
        
            if (!empty($ph_rows)) {
                foreach ($ph_rows as $data) {
                    $builder = $this->db->table('heardt h');
                    $builder->select([
                        'h.diary_no',
                        'h.conn_key',
                        'h.next_dt',
                        'h.roster_id',
                        'h.clno as part',
                        'h.main_supp_flag',
                        new RawSql("'1' as applicant_type"), // Use RawSql for literal values
                        new RawSql("'0' as party_id"),
                        new RawSql("'" . $data['advocate_id'] . "' as advocate_id"),
                        new RawSql("'2' as entry_source"),
                        new RawSql("'1' as user_id"),
                    ]);
        
                    $builder->join('main m', 'm.diary_no = h.diary_no', 'inner');
                    $builder->join('consent_through_email c', function ($join) use ($data) {
                        $join->on('c.diary_no = m.diary_no');
                        $join->on('c.next_dt = h.next_dt');
                        $join->on("c.next_dt = '" . $data['next_dt'] . "'");
                        $join->on("c.advocate_id = '" . $data['advocate_id'] . "'");
                    }, 'left');
        
                    $builder->where(new RawSql("FIND_IN_SET(m.diary_no, '" . $data['consent_for_diary_nos'] . "')")); // Use RawSql for FIND_IN_SET
                    $builder->where('c.diary_no is null');
                    $builder->where('h.next_dt', date('Y-m-d')); // Current date
                    $builder->where('h.clno > 0');
        
                    $insert_query = "INSERT INTO consent_through_email " . $builder->getCompiledSelect();
                    $vc_consent_result = $this->db->query($insert_query);
        
        
                    if ($this->db->affectedRows() > 0) {
                        echo " VC Consent : " . $this->db->affectedRows();
                    }
                }
            }
        }
    }


}