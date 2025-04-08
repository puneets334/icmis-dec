<?php

namespace App\Models\ManagementReport;

use CodeIgniter\Model;

class PerformanceModel extends Model
{

    public function __construct()
    {
        parent::__construct();
        $this->db = db_connect();
    }

    public function module_name()
    {
        $builder = $this->db->table('master.module_table');
        $builder->select('*');
        $builder->where('display', 'Y');
        $query = $builder->get();
        $result = $query->getResultArray();
        return $result;
    }

    public function users()
    {
        $builder = $this->db->table('master.users');
        $builder->select('name, empid');
        $builder->where('display', 'Y');
        $query = $builder->get();
        $result = $query->getResultArray();
        return $result;
    }

    public function diary_entry_session_data($fromDate, $user_id, $module_id)
    {
        if ($user_id != null) {
            $user = " AND mes.user_id = '$user_id' ";
        } else {
            $user = '';
        }
        if ($module_id != null) {
            $module = " AND mes.module_id = '$module_id' ";
        } else {
            $module = '';
        }
        $sql = "SELECT m.pet_name,
            m.res_name,
            m.diary_no_rec_date,
            m.reg_no_display,
            mes.*
        FROM main m
        INNER JOIN module_entry_session mes
            ON m.diary_no = mes.diary_no
        WHERE '$fromDate'::date BETWEEN mes.entry_time::date AND m.diary_no_rec_date::date $user $module order by mes.entry_time";
        $query = $this->db->query($sql);
        $result = $query->getResultArray();
        return $result;
    }

}