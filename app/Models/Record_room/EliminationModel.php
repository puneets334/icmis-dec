<?php

namespace App\Models\Record_room;

use CodeIgniter\Model;
use App\Models\Entities\Model_Ac;

class EliminationModel extends Model
{
    protected $table = 'main';

    public function eliminationdatatoshow($casetype = 0, $caseno = 0, $caseyear = 0, $diary_number = 0, $diary_year = 0)
    {
       
        $db = \Config\Database::connect();
        $builder = $db->table('main m');

        // Define the select fields
        $builder->select([
            'm.diary_no',
            'm.reg_no_display',
            "CASE 
                WHEN LENGTH(m.diary_no::char) > 4 
                THEN SUBSTRING(m.diary_no::char, 1, LENGTH(m.diary_no::char) - 4) 
                ELSE m.diary_no::char 
            END AS dno",
            "CASE 
                WHEN LENGTH(m.diary_no::char) >= 4 
                THEN SUBSTRING(m.diary_no::char, -4) 
                ELSE NULL 
            END AS dyear",
            'm.pet_name',
            'm.res_name',
            'm.c_status',
            'dis.ord_dt',
            'dis.year',
            'dis.month',
            'dis.disp_dt',
            'dis.disp_type',
            'dis.jud_id',
            'dis.camnt',
            'dis.crtstat',
            'dis.dispjud',
            'eli.ele_dt',
            'eli.remark',
            'weeded_by'
        ]);

        // Subquery for elimination
        $builder->join(
            '(SELECT fil_no, MAX(ent_dt) AS max_ent_dt FROM elimination GROUP BY fil_no) eli_max',
            'm.diary_no = eli_max.fil_no',
            'LEFT'
        );

        $builder->join('elimination eli', 'eli.fil_no = eli_max.fil_no AND eli.ent_dt = eli_max.max_ent_dt', 'LEFT');
        $builder->join('dispose dis', 'dis.diary_no = m.diary_no', 'LEFT');

        // Add debugging output
        // log_message('info', "Casetype: $casetype, Caseno: $caseno, Caseyear: $caseyear, Diary Number: $diary_number, Diary Year: $diary_year");

        if ($casetype != 0 && $caseno != 0) {
            $builder->where('CAST(SUBSTRING(m.active_fil_no FROM 1 FOR 2) AS INTEGER)', $casetype)
                ->where('m.active_reg_year', $caseyear)
                ->groupStart()
                ->where('CAST(SUBSTRING(m.active_fil_no FROM 4 FOR 6) AS INTEGER)', $caseno)
                ->orWhere("$caseno BETWEEN CAST(SUBSTRING(m.active_fil_no FROM 4 FOR 6) AS INTEGER) AND CAST(SUBSTRING(m.active_fil_no FROM 11 FOR 6) AS INTEGER)")
                ->groupEnd();
        } elseif ($diary_number != 0 && $diary_year != 0) {
            $diaryFullNumber = $diary_number . str_pad($diary_year, 4, '0', STR_PAD_LEFT);
            $builder->where('m.diary_no', $diaryFullNumber);
        }

        $query = $builder->get();

        return $query->getNumRows() > 0 ? $query->getResultArray() : false; // Return false if no records found
    }

    public function caseType()
    {
        $builder = $this->db->table('master.casetype');
        $query = $builder->where('is_deleted', 'f')
            ->where('casecode !=', 9999)
            ->orderBy('casecode', 'ASC')
            ->get();

        return $query->getResultArray();
    }

    public function getCaseRemarksHead()
    {
        $builder = $this->db->table('master.case_remarks_head');
        $query = $builder->where('side', 'D')
            ->orderBy('head', 'ASC')
            ->get();

        return $query->getResultArray();
    }

    public function getWeededBy()
    {
        $empIds = implode(',', [736, 681, 716, 961, 433, 749, 1116, 721, 669, 696, 563, 689, 699, 738, 661, 1118, 674, 1076, 1063, 4310, 1109, 1080, 1223]);

        $builder = $this->db->table('master.users');
        $query = $builder->distinct()
            ->select('name, usercode, empid')
            ->whereIn('empid', explode(',', $empIds))
            ->orderBy('name')
            ->get();

        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    public function getJudge()
    {
        $builder = $this->db->table('master.judge');
        $builder->select('jcode, jname');
        $query = $builder->where('display', 'Y')->get();

        return $query->getResultArray();
    }

    public function insertElimination($data)
{
    if (empty($data['weeded_by'])) {
        $data['weeded_by'] = null;
    }
    $builder = $this->db->table('elimination');
    $builder->insert($data);
    $insertId = $this->db->insertID();

    return $insertId;
}

public function updateElimination($fil_no, $data)
{
    $builder = $this->db->table('elimination');
    $builder->where('fil_no', $fil_no);
    $builder->update($data);
    return $this->db->affectedRows();
}

public function updateDisposal($diaryno, $data)
{
    $builder = $this->db->table('dispose');
    $builder->where('diary_no', $diaryno);
    $builder->update($data);
    return $this->db->affectedRows();
}



}
