<?php

namespace App\Models\Listing;

use CodeIgniter\Model;

class SectionListModel extends Model
{
    protected $table = 'draft_list';
    protected $allowedFields = ['diary_no', 'next_dt_old', 'conn_key', 'list_type', 'board_type', 'usercode', 'ent_time'];


    public function insertDraftList($list_dt, $board_type, $mainhead, $ucode)
    {
        $sql = "INSERT INTO draft_list (diary_no, next_dt_old, conn_key, list_type, board_type, usercode, ent_time)
                SELECT DISTINCT
                    m.diary_no, h.next_dt, m.conn_key::INT, 1, '$board_type', $ucode, CURRENT_TIMESTAMP
                FROM
                    heardt h
                    LEFT JOIN main m ON m.diary_no = h.diary_no
                    LEFT JOIN master.casetype c1 ON m.active_casetype_id = c1.casecode
                    LEFT JOIN master.listing_purpose l ON l.code = h.listorder
                    LEFT JOIN master.users u ON u.usercode = m.dacode AND u.display = 'Y'
                    LEFT JOIN master.usersection us ON us.id = u.section
                    LEFT JOIN mul_category c2 ON c2.diary_no = h.diary_no AND c2.display = 'Y'
                WHERE
                    c2.diary_no IS NOT NULL
                    AND l.display = 'Y'
                    AND h.listorder IN (4, 5, 25, 32, 24, 7, 8, 21, 48, 2, 16, 49)
                    AND (TRIM(LEADING '0' FROM SPLIT_PART(m.fil_no, '-', 1))::INTEGER IN (3, 15, 19, 31, 23, 24, 40, 32, 34, 22, 39, 11,
                    17, 13, 1, 7, 37, 9999, 38, 5, 21, 27, 4, 16, 20, 18, 33, 41, 35, 36, 28, 12, 14, 2, 8, 6)
                    OR (m.active_fil_no = ''
                    OR m.active_fil_no IS NULL))
                    AND h.board_type = '$board_type'
                    AND m.c_status = 'P'
                    AND h.main_supp_flag = '0'
                    AND h.next_dt = '$list_dt'
                    AND h.mainhead = '$mainhead'
                    AND (
                        (m.diary_no::char = m.conn_key OR m.conn_key = '0' OR m.conn_key = '' OR m.conn_key IS NULL)
                        OR EXISTS (
                            SELECT 1
                            FROM conct c
                            WHERE c.diary_no = m.diary_no
                            AND c.conn_key IN (
                                SELECT diary_no
                                FROM heardt
                                WHERE listorder IN (4, 5, 25, 32, 24, 7, 8, 21, 48, 2, 16, 49)
                                AND board_type = '$board_type'
                                AND main_supp_flag = '0'
                                AND next_dt = '$list_dt'
                                AND mainhead = '$mainhead'
                            )
                        )
                    )";
    
        $this->db->query($sql);
        return true;
    }
    

    public function getPublicationTime($listDt, $board_type)
    {
        $builder = $this->db->table('draft_list');
        //$builder->selectMin('ent_time', 'min_tm')
        $builder->select('MIN(ent_time) AS min_tm')
            ->where('next_dt_old', $listDt)
            ->where('board_type', $board_type)
            ->where('display', 'Y');

        return $builder->get()->getRow();
    }
}
