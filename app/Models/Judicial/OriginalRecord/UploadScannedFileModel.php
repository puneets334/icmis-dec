<?php

namespace App\Models\Judicial\OriginalRecord;

use CodeIgniter\Model;

class UploadScannedFileModel extends Model
{
    function __construct()
    {
        parent::__construct();
        $this->db = db_connect();
    }


    public function checkIfFileExist($diaryNo)
    {

        $builder = $this->db->table('original_records_file orf');

        // Select the required columns
        $builder->select("orf.*");

        $builder->where('orf.diary_no', $diaryNo);
        $builder->where('orf.display', 'Y');

        // Execute the query and fetch the result
        $query = $builder->get();
        $result = $query->getResultArray(); // Fetch as an associative array

        return $result;
    }


    public function insertOriginalRecords($data)
    {
        $builder = $this->db->table('original_records_file');
    
        // Check if the record already exists
        $existingRecord = $builder->where('diary_no', $data['diary_no'])
                                  ->where('display', 'Y')
                                  ->get()
                                  ->getResult();
    
        if (empty($existingRecord)) {
            // Prepare the data for insertion
            $insertData = [
                'diary_no' => $data['diary_no'],
                'file_name' => $data['file_name'],
                'usercode' => $data['usercode'],
                'updated_on' => date('Y-m-d H:i:s'), // Equivalent to `NOW()` in SQL
                'display' => 'Y'
            ];
    
            // Insert the record
            $builder->insert($insertData);
            return 1; // Return success
        }
    
        return 0; // Return failure if record already exists
    }
    

    public function updateOriginalRecords($data)
    {
        $builder = $this->db->table('original_records_file');

        $updateData = [
            'file_name' => $data['file_name'],
            'display' => 'N'
        ];

        $builder->where('id', $data['id']);
        $result = $builder->update($updateData);

        return $result ? 1 : 0;
    }


    public function getUploadedOriginalRecord($fromDate, $toDate, $usercode)
    {

        $builder = $this->db->table('original_records_file orf');

        // Select the required columns
        $builder->select("
            CONCAT(m.reg_no_display, ' @ ', 
                CONCAT(SUBSTRING(CAST(m.diary_no AS TEXT) FROM 1 FOR LENGTH(CAST(m.diary_no AS TEXT)) - 4), 
                ' / ', SUBSTRING(CAST(m.diary_no AS TEXT) FROM LENGTH(CAST(m.diary_no AS TEXT)) - 3))) AS case_no,
            CONCAT(m.pet_name, ' Vs. ', m.res_name) AS causetitle,
            orf.file_name,orf.diary_no,
            u.name AS uploaded_by,
            orf.updated_on
        ", false);

        // Join with 'main' and 'users' tables
        $builder->join('main m', 'orf.diary_no = m.diary_no');
        $builder->join('master.users u', 'u.usercode = orf.usercode');

        // Add WHERE conditions
        $builder->where('orf.display', 'Y');
        $builder->where("DATE(orf.updated_on) BETWEEN '$fromDate' AND '$toDate'", null, false);

        // Execute the query and fetch the result
        $query = $builder->get();
        $result = $query->getResultArray(); // Fetch as an associative array
        //echo "Query = ".$this->db->getLastQuery()->getQuery()."<br>";die;
        // pr($result);
        return $result;
    }

    // function getDiaryDetails(
    //     $optradio = null,
    //     $caseType = null,
    //     $caseNo = null,
    //     $caseYear = null,
    //     $diaryNo = null,
    //     $diaryYear = null
    // ) {
    //     if ($optradio === '1') {
    //         $sql = "SELECT h.diary_no,
    //             SUBSTRING(CAST(h.diary_no AS TEXT), 1, LENGTH(CAST(h.diary_no AS TEXT)) - 4) AS dn,
    //             SUBSTRING(CAST(h.diary_no AS TEXT), LENGTH(CAST(h.diary_no AS TEXT)) - 3, 4) AS dy
    //             FROM main_casetype_history h
    //             WHERE 
    //             (NULLIF(SPLIT_PART(h.new_registration_number, '-', 1), '') IS NOT NULL
    //             AND NULLIF(SPLIT_PART(h.new_registration_number, '-', 2), '') IS NOT NULL
    //             AND NULLIF(SPLIT_PART(h.new_registration_number, '-', 3), '') IS NOT NULL
    //             AND CAST(NULLIF(SPLIT_PART(h.new_registration_number, '-', 1), '') AS INTEGER) = ?
    //             AND CAST(? AS INTEGER) BETWEEN 
    //             CAST(NULLIF(SPLIT_PART(h.new_registration_number, '-', 2), '') AS INTEGER)
    //             AND CAST(NULLIF(SPLIT_PART(h.new_registration_number, '-', 3), '') AS INTEGER)
    //             AND CAST(h.new_registration_year AS INTEGER) = ?)
    //             AND h.is_deleted = 'f'";

    //         $query = $this->db->query($sql, [$caseType, $caseNo, $caseYear]);
    //     } elseif ($optradio === '2') {

    //         $sql = "SELECT diary_no, 
    //             SUBSTRING(CAST(diary_no AS TEXT), 1, GREATEST(LENGTH(CAST(diary_no AS TEXT)) - 4, 0)) AS dn, 
    //             RIGHT(CAST(diary_no AS TEXT), 4) AS dy 
    //             FROM main 
    //             WHERE SUBSTRING(CAST(diary_no AS TEXT), 1, GREATEST(LENGTH(CAST(diary_no AS TEXT)) - 4, 0)) = :diaryNo: 
    //             AND RIGHT(CAST(diary_no AS TEXT), 4) = :diaryYear:";
    //         $query = $this->db->query($sql, [
    //             'diaryNo'   => $diaryNo,
    //             'diaryYear' => $diaryYear
    //         ]);
    //         //echo $this->db->getLastQuery();
    //     } else {
    //         return false;
    //     }

    //     // Return the results or false if no rows found
    //     if ($query->getNumRows() >= 1) {
    //         return $query->getResultArray();
    //     } else {
    //         return false;
    //     }
    // }

    function is_diaryno_already_added_today($dno)
    {
        $sql = "SELECT diary_no 
                FROM mention_memo 
                WHERE diary_no = :dno: 
                AND date_of_received = CURRENT_DATE 
                AND date_on_decided = CURRENT_DATE 
                AND display = 'Y'";

        $query = $this->db->query($sql, ['dno' => $dno]);

        if ($query->getNumRows() >= 1) {
            session()->setFlashdata('msg', '<div class="alert alert-warning text-center">Mention Memo Already added for this case for the Given Date</div>');
            return redirect()->to('Mentioning');
        } else {
            return false;
        }
    }

    function getCaseDetails(
        $caseType,
        $caseNo,
        $caseYear
    ) {
      
        //SQL Model for refrence
        // $sql="SELECT h.diary_no,
        // SUBSTR( h.diary_no, 1, LENGTH( h.diary_no ) -4 ) AS dn,
        // SUBSTR( h.diary_no , -4 ) AS dy FROM
        //  main_casetype_history h
        // WHERE
        // (SUBSTRING_INDEX(h.new_registration_number, '-', 1) = $caseTypeId AND
        // CAST($caseNo AS UNSIGNED) BETWEEN (SUBSTRING_INDEX(SUBSTRING_INDEX(h.new_registration_number, '-', 2),'-',-1))
        // AND (SUBSTRING_INDEX(h.new_registration_number, '-', -1))
        // AND h.new_registration_year=$caseYear) AND h.is_deleted='f'";

        $sql = "SELECT h.diary_no,
        SUBSTRING(CAST(h.diary_no AS TEXT), 1, LENGTH(CAST(h.diary_no AS TEXT)) - 4) AS dn,
        SUBSTRING(CAST(h.diary_no AS TEXT), LENGTH(CAST(h.diary_no AS TEXT)) - 3, 4) AS dy
        FROM main_casetype_history h
        WHERE 
        ( 
            CAST(SPLIT_PART(h.new_registration_number, '-', 1) AS INTEGER) = $caseType

            AND CAST($caseNo AS INTEGER) BETWEEN 
                CAST(SPLIT_PART(h.new_registration_number, '-', 2) AS INTEGER)
                AND CAST(SPLIT_PART(h.new_registration_number, '-', -1) AS INTEGER)
            AND CAST(h.new_registration_year AS INTEGER) = $caseYear
        )
        AND h.is_deleted = 'f'";

        $query = $this->db->query($sql, [$caseType, $caseNo, $caseYear]);
         //echo $this->db->getLastQuery(); die();
        return ($query->getNumRows() >= 1) ? $query->getResultArray() : false;
    }

    function getDiaryDetails(
        $diaryNumber,
        $diaryYear
    ) {
        $sql = "SELECT diary_no, 
                    SUBSTRING(CAST(diary_no AS TEXT), 1, GREATEST(LENGTH(CAST(diary_no AS TEXT)) - 4, 0)) AS dn, 
                    RIGHT(CAST(diary_no AS TEXT), 4) AS dy 
                    FROM main 
                    WHERE SUBSTRING(CAST(diary_no AS TEXT), 1, GREATEST(LENGTH(CAST(diary_no AS TEXT)) - 4, 0)) = :diaryNo: 
                    AND RIGHT(CAST(diary_no AS TEXT), 4) = :diaryYear:";

        $query = $this->db->query($sql, [
            'diaryNo'   => $diaryNumber,
            'diaryYear' => $diaryYear
        ]);
        //echo $this->db->getLastQuery(); die();
        return ($query->getNumRows() >= 1) ? $query->getResultArray() : false;
    }

    function getCaseDetailsData($diaryNumber = null)
    {
        if (!($this->is_diaryno_already_added_today($diaryNumber))) {
            $sql = "SELECT s.section_name AS user_section,
                    s.id,
                    SUBSTRING(CAST(b.diary_no AS TEXT) FROM 1 FOR LENGTH(CAST(b.diary_no AS TEXT)) - 4) AS diary_no,
                    SUBSTRING(CAST(b.diary_no AS TEXT) FROM LENGTH(CAST(b.diary_no AS TEXT)) - 3 FOR 4) AS diary_year,
                    TO_CHAR(b.diary_no_rec_date, 'YYYY-MM-DD') AS diary_date,
                    b.c_status,
                    b.pet_name,
                    b.res_name,
                    b.active_fil_no,
                    b.reg_no_display,
                    b.dacode,
                    c.stagename,
                    a.next_dt,
                    a.mainhead,
                    a.subhead,
                    a.brd_slno,
                    a.ent_dt,
                    mms.descrip,
                    a.tentative_cl_dt,
                    a.usercode,
                    a.conn_key,
                    a.main_supp_flag,
                    a.listorder,
                    u.name AS alloted_to_da,
                    
                    u1.name AS updated_by,
                    
                    br1.name AS pet_adv_name,
                    br2.name AS res_adv_name,
                    br1.aor_code AS pet_aor_code,
                    br2.aor_code AS res_aor_code,
                    sb.sub_name1,
                    sb.sub_name4,
                    sb.category_sc_old
                    FROM main b
                    LEFT OUTER JOIN heardt a ON a.diary_no = b.diary_no
                    LEFT OUTER JOIN master.subheading c ON a.subhead = c.stagecode AND c.display = 'Y'
                    LEFT OUTER JOIN master.users u ON u.usercode = b.dacode AND u.display = 'Y'
                    LEFT OUTER JOIN master.users u1 ON u1.usercode = a.usercode AND u1.display = 'Y'
                    LEFT OUTER JOIN master.master_main_supp mms ON mms.id = a.main_supp_flag
                    LEFT OUTER JOIN master.listing_purpose lp ON lp.code = a.listorder AND lp.display = 'Y'
                    LEFT OUTER JOIN master.usersection s ON s.id = u.section AND s.display = 'Y'
                    LEFT OUTER JOIN master.bar br1 ON b.pet_adv_id = br1.bar_id
                    LEFT OUTER JOIN master.bar br2 ON b.res_adv_id = br2.bar_id
                    LEFT OUTER JOIN mul_category mc ON a.diary_no = mc.diary_no AND mc.display = 'Y'
                    LEFT OUTER JOIN master.submaster sb ON mc.submaster_id = sb.id
                    WHERE b.diary_no = :diaryNumber:";

            // Execute the query using query builder and bind parameters
            $query = $this->db->query($sql, ['diaryNumber' => $diaryNumber]);
            //echo $this->db->getLastQuery(); die();
            // Check if rows are returned
            if ($query->getNumRows() >= 1) {
                return $query->getResultArray();
            } else {
                return false;
            }
        }
    }
}
