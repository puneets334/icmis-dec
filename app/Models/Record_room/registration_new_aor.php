<?php

namespace App\Models\Record_room;

use CodeIgniter\Model;

class registration_new_aor extends Model
{

    // columns in bar table updated as null 
    // 'paddress',
    //  'pcity',
    //  'phno',
    //  'bupuser',
    //  'cmis_state_id',
    //  'agency_code',

    protected $table = 'master.bar';
    protected $allowedFields = ['state_id',
                    'if_aor',
                    'enroll_no',
                    'enroll_date',
                    'title',
                    'name',
                    'fname',
                    'rel',
                    'mname',
                    'sex',
                    'cast',
                    'passing_year',
                    'dob',
                    'pp',
                    'caddress',
                    'ccity',
                    'mobile',
                    'email',
                    'if_sen',
                    'bentdt',
                    'aor_code',
                    'paddress',
                    'pcity',
                    'phno',
                    'bupuser',
                    'cmis_state_id',
                    'agency_code',
                    'bentuser']; 

    public function getStateName()
    {
        return $this->db->table('master.state')
            ->select("id_no,name,state_code")
            ->where('district_code', 0)->where('sub_dist_code', 0)
            ->where(['village_code' => 0, 'display' => 'Y', 'state_code <' => 100])
            ->orderBy('name')
            ->get()->getResultArray();
    }

    public function getnewaorcode()
    {
        $db = \Config\Database::connect();

        $builder = $db->table('master.bar');
        $builder->select('MAX(aor_code) + 1 AS new_aor_code');
        $builder->where('if_aor', 'Y');
        $builder->where('aor_code !=', 4075);

        $query = $builder->get();
        $result = $query->getRow();

        $newAorCode = $result->new_aor_code ?? null;
        return $newAorCode;
    }

    public function check_aor_registration($advEnrollNo, $advState, $year)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('master.bar');
        $builder->select('name');
        $builder->where('enroll_no', $advEnrollNo);
        $builder->where('YEAR(enroll_date)', $year);
        $builder->where('state_id', $advState);
        $query = $builder->get();
        return $query->getNumRows();
    }
}
