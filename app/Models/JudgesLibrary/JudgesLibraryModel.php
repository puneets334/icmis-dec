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
            $sql = "SELECT m.* FROM main m WHERE 
                    SUBSTR(diary_no, 1, LENGTH(diary_no) - 4) = ? 
                    AND SUBSTR(diary_no, -4) = ?";

            $parameters = [$diaryNo, $diaryNo];
        }

        // Debugging query before execution
        $finalQuery = vsprintf(str_replace("?", "'%s'", $sql), $parameters);
        pr($finalQuery);

        // Execute query
        $query = $this->db->query($sql, $parameters);

        return $query->row(); // Return the first row found
    }



    public function getWithAllConnected($main_diary)
    {
        $sql = "  SELECT STRING_AGG(diary_no::TEXT, ',') AS conn_list  FROM main WHERE conn_key = '$main_diary'";
        $query = $this->db->query($sql);
        $result = $query->getResultArray();
        return $result;
    }
    public function getCaseDetailsJudgementFlagChange() {}
}
