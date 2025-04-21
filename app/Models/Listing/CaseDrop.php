<?php

namespace App\Models\Listing;

use CodeIgniter\Model;

class CaseDrop extends Model
{
    protected $table = 'main';


    public function getDiaryNumber($ct, $cn, $cy)
    {
        $sql = "SELECT 
                    SUBSTRING(CAST(diary_no AS TEXT), 1, LENGTH(CAST(diary_no AS TEXT)) - 4) AS dn, 
                    SUBSTRING(CAST(diary_no AS TEXT), -4) AS dy
                FROM main
                WHERE 
                    SPLIT_PART(fil_no, '-', 1) = ? 
                    AND CAST(? AS INTEGER) BETWEEN 
                        CAST(SPLIT_PART(fil_no, '-', 2) AS INTEGER) 
                        AND CAST(SPLIT_PART(fil_no, '-', -1) AS INTEGER)
                    AND ( 
                        (reg_year_mh = 0 AND EXTRACT(YEAR FROM fil_dt) = ?) 
                        OR (reg_year_mh = ?)
                    )";

        return $this->db->query($sql, [$ct, $cn, $cy, $cy])->getRowArray();
    }



    public function getCaseDetails($dno)
    {
        if (empty($dno)) {
            return null;
        }
        $dno = (string)$dno;
        $builder = $this->db->table('main m');
        $builder->select('m.pet_name, m.res_name, m.pno, m.rno,m.rno, h.*, a.display, a.cl_date');
        $builder->join('advance_allocated h', ' CAST(m.diary_no AS BIGINT) = CAST(h.diary_no AS BIGINT) ', 'left');
        $builder->join('advanced_drop_note a', ' CAST(a.diary_no AS BIGINT) =  CAST(h.diary_no AS BIGINT) AND a.display = \'R\' AND a.cl_date = h.next_dt', 'left');
        $builder->where('a.diary_no IS NULL', null, false);
        $builder->where('m.diary_no', (int)$dno);
        $builder->where('h.next_dt >= CURRENT_DATE', null, false);
        $builder->where('(h.main_supp_flag = 1 OR h.main_supp_flag = 2)', null, false);
        $query = $builder->get();
        // echo $this->db->getLastQuery();die;
        return $query->getRowArray();
    }


    public function checkDropNote($next_dt, $brd_slno, $jcode, $dno)
    {
        if (empty($dno) || empty($next_dt) || empty($brd_slno) || empty($jcode)) {
            return null;
        }

        $builder = $this->db->table('advanced_drop_note');
        $builder->select('COUNT(diary_no) as count');
        $builder->where('diary_no', $dno);
        $builder->where('clno', $brd_slno);
        $builder->where('roster_id', $jcode);
        $builder->where('(display=\'Y\' OR display=\'R\')', null, false);
        $builder->where('cl_date', $next_dt);

        $query = $builder->get();
        //echo $this->db->getLastQuery();die;
        return $query->getRowArray();
    }


    function advancedDropNoteIns($ucode, $next_dt, $brd_slno, $drop_diary, $roster_id, $drop_rmk, $mainhead, $partno)
    {
        $result = 0;
        $sql="INSERT INTO advanced_drop_note (cl_date, clno, diary_no, roster_id, nrs, usercode, ent_dt, mf, part, update_user, so_user) VALUES 
          ('$next_dt','$brd_slno','$drop_diary','$roster_id','$drop_rmk', '$ucode', NOW(),'$mainhead','$partno', '$ucode', '$ucode');";
        $query = $this->db->query($sql);
        if ($this->db->affectedRows() > 0) {
            $result = 1;
        }
        return $result;
    }

    function fAdvanceClDropCase($dno, $ucode, $ldates)
    {
        $result = 0;
        if($dno == 0 OR $dno == ''){
            echo "0";
        } else {
            $sql_conn = "INSERT INTO advanced_drop_note (cl_date, clno, diary_no, roster_id, nrs, usercode, ent_dt, display, mf, part, board_type, update_user, so_user)  
            select next_dt, brd_slno, diary_no, j1, 'Released before advance list printed', '$ucode' as usercode, NOW(), 'R', 'M', clno, h.board_type, '$ucode' as update_user, '$ucode' as so_user from advance_allocated h WHERE (CASE WHEN h.conn_key = '$dno' THEN h.diary_no = '$dno' OR h.conn_key = '$dno' ELSE h.diary_no = '$dno' END) and h.next_dt = '$ldates' and CAST(h.diary_no AS bigint) > 0 and clno > 0 and brd_slno > 0 and (main_supp_flag = 1 or main_supp_flag = 2)";
            $query = $this->db->query($sql_conn);
            if ($this->db->affectedRows() > 0) {
                $result = 1;
            }
        }
        return $result;
    }

    function advance_cl_printed($q_next_dt)
    {
        $result = 0;
        $sql = "select * from advance_cl_printed where next_dt = '$q_next_dt' and board_type = 'J' and display='Y'";
        $query = $this->db->query($sql);
        if ($query->getNumRows() >= 1) {
            $result = 1;
        }
        return $result;
    }

    function f_get_judge_names($chk_jud_id)
    {
        $chk_jud_id = rtrim($chk_jud_id, ',');
        $sql = "SELECT first_name, sur_name FROM master.judge WHERE is_retired != 'Y' AND jcode IN ($chk_jud_id)";
        $query = $this->db->query($sql);
        $result = $query->getResultArray();
        $jname = "";
        if (!empty($result)) {
            foreach ($result as $row) {
                $jname .= $row['first_name'] . " " . $row['sur_name'] . ", ";
            }
            $jname = rtrim($jname, ", ");
        }

        return $jname;
    }

    
}
