<?php

namespace App\Models\JudgesLibrary;

use CodeIgniter\Model;


class JudgesLibraryModel extends Model
{

    public function getCaseType()
    {
        $builder = $this->db->table('master.casetype');
        $builder->select('casecode, skey, casename, short_description');
        $builder->where('display', 'Y');
        $builder->where('casecode !=', '9999');
        $builder->orderBy('casecode');
        $query = $builder->get();
        $result = $query->getResultArray();
        return $result;
    }
    // public function getSearchDiaryAllFields($caseType, $caseNo, $caseYear, $diaryNo, $diaryYear, $searchType) {
    //     $parameters=[];
    // 	if ($searchType == 'C') {
    // 		$sql = "SELECT distinct m.* FROM
    //                  main_casetype_history h
    // 				inner join main m on m.diary_no = h.diary_no
    //                 WHERE (SUBSTRING_INDEX(h.new_registration_number, '-', 1) = cast(? as UNSIGNED) AND
    //                 CAST(? AS UNSIGNED) BETWEEN (SUBSTRING_INDEX(SUBSTRING_INDEX(h.new_registration_number, '-', 2),'-',-1))
    //                 AND (SUBSTRING_INDEX(h.new_registration_number, '-', -1))
    //                 AND h.new_registration_year=?) AND h.is_deleted=?";
    //                  $bindings = array($parameters,$caseType,$caseNo,$caseYear,'f');
    // 		// array_push($parameters,$caseTypeId,$caseNo,$caseYear,'f');
    //           // Print the final query before execution
    //             $finalQuery = vsprintf(str_replace("?", "'%s'", $sql), $bindings);
    //             pr($finalQuery); 
    // 	}
    // 	else if($searchType == 'D') {
    // 		$sql = "Select m.* from main m where substr( diary_no, 1, length( diary_no ) -4 )=? and substr( diary_no , -4 )=?";
    // 		array_push($parameters,$diaryNo,$diaryYear);
    // 	}

    // 	$query = $this->db->query($sql,$parameters);
    // 	//echo $this->db->last_query();
    // 	return $query->row();
    // 	//return $row->diary_no;
    // }

    public function getSearchDiaryAllFields($caseType, $caseNo, $caseYear, $diaryNo, $diaryYear, $searchType)
    {
        $parameters = [];

        if ($searchType == 'C') {
            $sql = "SELECT DISTINCT m.* 
                    FROM main_casetype_history h
                    INNER JOIN main m ON m.diary_no = h.diary_no
                    WHERE 
                        (SPLIT_PART(h.new_registration_number, '-', 1)::INTEGER = ?
                            AND ? BETWEEN 
                                SPLIT_PART(SPLIT_PART(h.new_registration_number, '-', 2), '-', -1)::INTEGER 
                                AND SPLIT_PART(h.new_registration_number, '-', -1)::INTEGER
                            AND h.new_registration_year = ?
                        ) 
                        AND h.is_deleted = ?";
            $parameters = [$caseType, $caseNo, $caseYear, 'f'];
        } else if ($searchType == 'D') {
            $sql = "SELECT m.* 
                    FROM main m 
                    WHERE 
                        LEFT(CAST(diary_no AS TEXT), LENGTH(CAST(diary_no AS TEXT)) - 4) = ?
                    AND RIGHT(CAST(diary_no AS TEXT), 4) = ?";

            $parameters = [$diaryNo, $diaryYear];
        }

        // Execute query
        $query = $this->db->query($sql, $parameters);

        return $query->getRowArray();
    }



    public function getWithAllConnected($main_diary)
    {
        $sql = "  SELECT STRING_AGG(diary_no::TEXT, ',') AS conn_list  FROM main WHERE conn_key = '$main_diary'";
        $query = $this->db->query($sql);
        $result = $query->getRowArray();
        return $result;
    }

    public function getCaseDetailsJudgementFlagChange($diaryNo)
    {
       
        $queryString = "";
        $rop_text_web = "rop_text_web";

        $queryString = "SELECT * FROM  (SELECT o.id, 'ordernet' as tbl_name, o.diary_no, SUBSTR(o.diary_no::text, 1, LENGTH(o.diary_no::text) - 4) AS d_no, SUBSTR(o.diary_no::text, - 4) AS d_year,
    pdfname file_address, TO_CHAR(o.orderdate, 'YYYY-MM-DD') order_date, o.type as order_type_short,
    case when o.type='O' then 'rop' when o.type='J' then 'judgment' when o.type='FO' then 'final order' END as order_type,nc_display
                FROM ordernet o left join neutral_citation n on o.diary_no=n.diary_no and n.dispose_order_date::DATE = o.orderdate and o.type='J'
                WHERE o.diary_no  in ($diaryNo)
                union 
                SELECT
                o.id, 'tempo' as tbl_name, o.diary_no, SUBSTR(o.diary_no::text, 1, LENGTH(o.diary_no::text) - 4) AS d_no, SUBSTR(o.diary_no::text, - 4) AS d_year,
    jm file_address, TO_CHAR(o.dated::DATE, 'YYYY-MM-DD') order_date,
    case when o.jt='rop' then 'O' when o.jt='judgment' then 'J' when o.jt='final order' then 'FO' END as order_type_short,
    jt as order_type ,nc_display
                FROM tempo o left join neutral_citation n on o.diary_no=n.diary_no and n.dispose_order_date::DATE = o.dated::DATE and o.jt='judgment'
                WHERE o.diary_no  in ($diaryNo) and jt != 'or'
                union
                SELECT
                 o.id_dn as id, 'scordermain' as tbl_name, o.dn as diary_no, SUBSTR(o.dn::text, 1, LENGTH(o.dn::text) - 4) AS d_no, SUBSTR(o.dn::text, - 4) AS d_year,
    concat('judis/',o.filename,'.pdf') file_address, TO_CHAR(o.juddate::DATE, 'YYYY-MM-DD') order_date,
    case when o.order_type='rop' then 'O' when o.order_type='judgment' then 'J' when o.order_type='final order' then 'FO' END as order_type_short,
    o.order_type as order_type ,nc_display
                FROM scordermain o left join neutral_citation n on o.dn=n.diary_no and n.dispose_order_date::DATE = o.juddate::DATE and o.order_type='judgment'
                WHERE o.dn  in ($diaryNo)
                union
                SELECT
                 o.pno as id, 'old_rop' as tbl_name, o.dn as diary_no, SUBSTR(o.dn::TEXT, 1, LENGTH(o.dn::TEXT) - 4) AS d_no,SUBSTR(o.dn::TEXT, -4) AS d_year,
    concat('ropor/rop/all/',o.pno,'.pdf') file_address, TO_CHAR(o.orderDate, 'YYYY-MM-DD') order_date,
    case when o.order_type='rop' then 'O' when o.order_type='judgment' then 'J' when o.order_type='final order' then 'FO' END as order_type_short,
    o.order_type as order_type     ,nc_display
                FROM rop_text_web.old_rop o left join neutral_citation n on o.dn=n.diary_no and n.dispose_order_date::DATE = o.orderDate::DATE and o.order_type='judgment'
                WHERE o.dn  in ($diaryNo)
                union
                SELECT
                  o.pno as id, 'ordertext' as tbl_name, o.dn as diary_no,  SUBSTR(o.dn::TEXT, 1, LENGTH(o.dn::TEXT) - 4) AS d_no,SUBSTR(o.dn::TEXT, -4) AS d_year,
    concat('bosir/orderpdf/', o.pno, '.pdf') AS file_address,
TO_CHAR(o.orderDate::DATE, 'YYYY-MM-DD') AS order_date,
    case when o.order_type='rop' then 'O' when o.order_type='judgment' then 'J' when o.order_type='final order' then 'FO' END as order_type_short,
    o.order_type as order_type    ,nc_display
                FROM rop_text_web.ordertext o left join neutral_citation n on o.dn=n.diary_no and n.dispose_order_date::DATE = o.orderDate::DATE and o.order_type='judgment'
                WHERE o.dn in ($diaryNo)
                union
                SELECT
                 o.pno as id, 'oldordtext' as tbl_name, o.dn as diary_no,  SUBSTR(o.dn::TEXT, 1, LENGTH(o.dn::TEXT) - 4) AS d_no,SUBSTR(o.dn::TEXT, -4) AS d_year,
    concat('bosir/orderpdfold/',o.pno,'.pdf') file_address, TO_CHAR(o.orderDate::DATE, 'YYYY-MM-DD') order_date,
    case when o.order_type='rop' then 'O' when o.order_type='judgment' then 'J' when o.order_type='final order' then 'FO' END as order_type_short,
    o.order_type as order_type  ,nc_display
                FROM rop_text_web.oldordtext o left join neutral_citation n on o.dn=n.diary_no and n.dispose_order_date::DATE = o.orderdate::DATE and o.order_type='judgment'
                WHERE o.dn in ($diaryNo)
                ) tbl1 order by order_date desc";
        $query = $this->db->query($queryString);
        return $query->getResultArray();
    }

    public function getDiaryJudmentFinalOrderDetail($id, $tbl_name)
    {
        $queryString = "";
        if ($tbl_name == 'ordernet')
            $queryString = "select o.* from ordernet o where o.id = $id and o.display = 'Y'";
        else if ($tbl_name == 'tempo')
            $queryString = "select o.* from tempo o where o.id = $id ";
        else if ($tbl_name == 'scordermain')
            $queryString = "select o.* from scordermain o where o.id_dn = $id ";
        else if ($tbl_name == 'old_rop')
            $queryString = "select o.* from rop_text_web.old_rop o where o.pno = $id ";
        else if ($tbl_name == 'ordertext')
            $queryString = "select o.* from rop_text_web.ordertext o where o.pno = $id ";
        else if ($tbl_name == 'oldordtext')
            $queryString = "select o.* from rop_text_web.oldordtext o where o.pno = $id ";

        $query = $this->db->query($queryString);
        $res = $query->getResultArray();
        return $res;
    }
    public function insertOrdernetDeleted($data)
    {
        if ($data['tbl_name'] == 'ordernet') {
            $queryString = "INSERT INTO order_type_changed_log
                            (tbl_id, tbl_name, user_id, ent_dt, order_type, modified_date, modified_by)
                            values ('" . $data['id'] . "','" . $data['tbl_name'] . "','" . $data['usercode'] . "','" . $data['ent_dt'] . "',
                            '" . $data['type'] . "','" . $data['modified_date'] . "','" . $data['modified_by'] . "')";
        } else if ($data['tbl_name'] == 'tempo') {
            $queryString = "INSERT INTO order_type_changed_log
                            (tbl_id, tbl_name, user_id, ent_dt, order_type, modified_date, modified_by)
                            values ('" . $data['id'] . "','" . $data['tbl_name'] . "','" . $data['usercode'] . "','" . $data['ent_dt'] . "',
                            '" . $data['jt'] . "','" . $data['modified_date'] . "','" . $data['modified_by'] . "')";
        } else if ($data['tbl_name'] == 'scordermain') {
            $queryString = "INSERT INTO order_type_changed_log
                            (tbl_id, tbl_name, user_id, ent_dt, order_type, modified_date, modified_by)
                            values ('" . $data['id_dn'] . "','" . $data['tbl_name'] . "','" . $data['usercode'] . "','" . $data['ent_dt'] . "',
                            '" . $data['order_type'] . "','" . $data['modified_date'] . "','" . $data['modified_by'] . "')";
        } else if ($data['tbl_name'] == 'old_rop' or $data['tbl_name'] == 'ordertext' or $data['tbl_name'] == 'oldordtext') {
            $queryString = "INSERT INTO order_type_changed_log
                            (tbl_id, tbl_name, user_id, ent_dt, order_type, modified_date, modified_by)
                            values ('" . $data['pno'] . "','" . $data['tbl_name'] . "','" . $data['usercode'] . "','" . $data['ent_dt'] . "',
                            '" . $data['order_type'] . "','" . $data['modified_date'] . "','" . $data['modified_by'] . "')";
        }
        $this->db->query($queryString);
    }

    public function updateOrdernetFlag($id, $type, $usercode, $tbl_name)
    {
        if ($type == 'J') {
            $type_long = "judgment";
        } else if ($type == 'FO') {
            $type_long = "finalorder";
        } else {
            $type_long = "rop";
        }


        if ($tbl_name == 'ordernet')
            $updateString = $this->db->query("update ordernet set type='" . $type . "', usercode = '" . $usercode . "', ent_dt=now() where id=" . $id . " ");
        else if ($tbl_name == 'tempo')
            $updateString = $this->db->query("update tempo set jt='" . $type_long . "', usercode = '" . $usercode . "', ent_dt=now() where id=" . $id . " ");
        else if ($tbl_name == 'scordermain')
            $updateString = $this->db->query("update scordermain set order_type='" . $type_long . "', usercode = '" . $usercode . "', ent_dt=now() where id_dn=" . $id . " ");
        else if ($tbl_name == 'old_rop' or $tbl_name == 'ordertext' or $tbl_name == 'oldordtext')
            $updateString = $this->rop_text_web->query("update $tbl_name set order_type='" . $type_long . "', usercode = '" . $usercode . "', ent_dt=now() where pno=" . $id . " ");

        return $updateString;
    }
}
