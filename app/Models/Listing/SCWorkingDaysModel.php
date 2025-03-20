<?php

namespace App\Models\Listing;

use CodeIgniter\Model;

class SCWorkingDaysModel extends Model
{

    protected $table = 'master_list_type';
    protected $db;

    public function __construct() {
        parent::__construct();
        $this->db = \Config\Database::connect();
    }
    
    public function getWorkingDaysDetails($is_working) {
        $builder = $this->db->table('master.sc_working_days');
        $res_wd = $builder->select('id, working_date, is_nmd, is_holiday, holiday_description, nmd_dt, misc_dt1, sec_list_dt, holiday_for_registry')->where('working_date', $is_working)->limit(1)->get();
        $result = $res_wd->getResultArray();
        return $result;
    }

    public function insertWorkingDay() {
        $data = [
            "working_date" => date('Y-m-d', strtotime($_POST['working_date'])),
            "is_nmd" => $_POST['is_nmd'],
            "is_holiday" => $_POST['is_holiday'],
            "holiday_description" => $_POST['holiday_description'],
            "nmd_dt" => date('Y-m-d', strtotime($_POST['nmd_dt'])),
            "misc_dt1" => date('Y-m-d', strtotime($_POST['misc_dt1'])),
            "sec_list_dt" => date('Y-m-d', strtotime($_POST['sec_list_dt'])),
            "holiday_for_registry" => $_POST['holiday_for_registry'],
            'create_modify' => date('Y-m-d H:i:s'),
            "updated_by_ip" => get_client_ip()
        ];
        $builder = $this->db->table('master.sc_working_days');        
        if ($builder->insert($data)) {
            echo "<span class='text-success'>Data Inserted Successfully</span>";
        } else {
            echo "<p class='text-danger'>Insertion Failed <br/></p>";
        }        
    }

    public function updateWorkingDay() {
        $data = [
            'is_nmd' => $_POST['is_nmd'],
            'is_holiday' => $_POST['is_holiday'],
            'holiday_description' => $_POST['holiday_description'],
            'nmd_dt' => date('Y-m-d', strtotime($_POST['nmd_dt'])),
            'misc_dt1' => date('Y-m-d', strtotime($_POST['misc_dt1'])),
            'sec_list_dt' => date('Y-m-d', strtotime($_POST['sec_list_dt'])),
            'holiday_for_registry' => $_POST['holiday_for_registry'],
            'updated_on' => date('Y-m-d H:i:s'),
            "updated_by_ip" => get_client_ip()
        ];
        $builder = $this->db->table('master.sc_working_days');        
        if ($builder->where('working_date', date('Y-m-d', strtotime($_POST['working_date'])))
            ->update($data)) {
            echo "<span class='text-success'>Record Updated Successfully</span>";
        } else {
            echo "<p class='text-danger'>Updation Failed <br/></p>";
        }        
    }

    public function getWorkingDay() {
        if(isset($_POST['submit'])) {
            $fromDate = $_POST['From_date'];
            $toDate = $_POST['To_date'];
            $formCalendar = $_POST['form_calender'];
            $builder = $this->db->table('master.sc_working_days');
            $builder->select('*');
            $builder->select("CASE WHEN is_nmd = 0 THEN 'Miscellaneous' WHEN is_nmd = 1 THEN 'RegularDay' END as NMDFlag");
            $builder->where('working_date >=', $fromDate);
            $builder->where('working_date <=', $toDate);
            $builder->where('display', 'Y');
            $input = '';
            $input1 = '';
            if ($formCalendar =="All") {
                $input = "";
                $input1 = "";
            } elseif ($formCalendar == "Court Working Day") {
                $input = "is_holiday = 0";
                $input1 = "holiday_for_registry = 0";
            } elseif ($formCalendar == "Registry Working Day") {
                $input1 = "holiday_for_registry = 0";
            } elseif ($formCalendar == "Court Holiday") {
                $input = "is_holiday = 1";
            } elseif ($formCalendar == "Registry Holiday") {
                $input = "is_holiday = 1";
                $input1 = "holiday_for_registry = 1";
            }
            if (!empty($input)) {
                $builder->where($input);
            }
            if (!empty($input1)) {
                $builder->where($input1);
            }
            $builder->orderBy('working_date', 'ASC');
            $query = $builder->get();
            $result = $query->getResultArray();
            // echo  $this->db->getLastQuery();
            return $result;
        }
    }

}