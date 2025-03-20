<?php

namespace App\Models\Listing;

use CodeIgniter\Model;

class AdvanceClPrinted extends Model
{
    protected $table = 'advance_cl_printed';
    protected $primaryKey = 'id';

    // protected $allowedFields = ['fil_no', 'fil_dt', 'lastorder', 'pet_name', 'res_name', 'c_status'];
    protected $allowedFields = [
        'next_dt',
        'part',
        'board_type',
        'usercode',
        'ent_time',
        'display'
    ];
    public function isPrinted($list_dt, $board_type)
    {

        return $this->where('next_dt', $list_dt)
            ->where('board_type', $board_type)
            ->where('display', 'Y')
            ->countAllResults() > 0;
    }

  

    public function isPrintedCase($date)
    {
        //$date='2018-11-02';
        $builder = $this->db->table('advance_cl_printed');
        $builder->select('id')
        ->where('next_dt', $date)
            ->where('display', 'Y');
        $query = $builder->get();
        return $query->getNumRows() > 0 ? 1 : 0;
    }


    public function isPublishPrinted($list_dt, $board_type, $part_no)
    {

        if (!$this->validateDate($list_dt)) {
            return false;
        }

        return $this->where('next_dt', $list_dt)
            ->where('board_type', $board_type)
            //->where('part', $part_no)
            ->where('display', 'Y')
            ->countAllResults() > 0;
    }

    private function validateDate($date)
    {

        $d = \DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') === $date;
    }

    public function isAlreadyPrinted($list_dt, $board_type, $part_no)
    {
        return $this->where('next_dt', $list_dt)
            ->where('board_type', $board_type)
            ->where('part', $part_no)
            ->where('display', 'Y')
            ->countAllResults() > 0;
    }
    public function insertPrintedCauseList($list_dt, $part_no, $board_type, $ucode, $now)
    {
        $builder = $this->db->table('advance_cl_printed');
        $data = [
            'next_dt' => $list_dt,
            'part' => $part_no,
            'board_type' => $board_type,
            'usercode' => $ucode,
            'ent_time' => $now,


        ];
        $builder->insert($data);
        if ($builder) {
            return 1;
        } else {
            return 0;
        }
    }
}
