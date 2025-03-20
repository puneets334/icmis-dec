<?php

namespace App\Models\Judicial;

use CodeIgniter\Model;

class UpdateDACodeModel extends Model
{

    public function __construct()
    {
        parent::__construct();
        $this->db = db_connect();
    }


    public function get_casedesc($diary_no)
    {

        $main_q = "SELECT pet_name,res_name,c_status,name,section_name,a.dacode,empid,section_id FROM main a LEFT JOIN master.users b ON a.dacode=b.usercode LEFT JOIN master.usersection c ON b.section=c.id WHERE diary_no=$diary_no";
        $result = $this->db->query($main_q);
        $result = $result->getResultArray();

        if (!empty($result)) {
            $result = $result[0];
        } else {
            $result = [];
        }
        // echo "<pre>";
        // print_r($result); die;
        return $result;
    }


    public function set_dacode($data)
    {
        $ucode = $_SESSION['login']['usercode'];

        // $update = "UPDATE main SET old_dacode=dacode,dacode='$data[dacode]',last_usercode='$ucode',last_dt=now() WHERE diary_no=$data[dno]";

        $builder15 = $this->db->table("main");
        $builder15->select("dacode");
        $builder15->where('diary_no', $data['dno']);
        $query15 = $builder15->get();
        $resdaCode = $query15->getResultArray();
        if (!empty($resdaCode)) {
            $daCode = $resdaCode[0]['dacode'];
            $updateData = [
                'old_dacode' => $daCode,
                'dacode' => $data['dacode'],
                'last_usercode' => $ucode,
                'last_dt' => 'NOW()'
            ];

            $builder = $this->db->table("main");
            $builder->where('diary_no', $data['dno']);
            if ($builder->update($updateData)) {
                return 1;
            } else {
                return 0;
            }
        }
    }


    public function getDiaryDetails($diary_no)
    {
        $builder2 = $this->db->table("main a");
        $builder2->select("pet_name, res_name, c_status, name, section_name, a.dacode, empid, section_id, fil_no, fil_dt, fil_no_fh, fil_dt_fh,
            short_description, CASE WHEN reg_year_mh = 0 THEN EXTRACT(YEAR FROM a.fil_dt) ELSE reg_year_mh END AS m_year,
            CASE WHEN reg_year_fh = 0 THEN EXTRACT(YEAR FROM a.fil_dt_fh) ELSE reg_year_fh END AS f_year, pno, rno");

        $builder2->join('users b', 'a.dacode = b.usercode', 'left');
        $builder2->join('usersection c', 'b.section = c.id', 'left');
        $builder2->where('a.diary_no', $diary_no);
        $query1 = $builder2->get();
        
        $data = $query1->getResultArray();

        return $data;
    }

     

}
