<?php
namespace App\Models\Filing;

use CodeIgniter\Model;

class FileDispatchReceiveModel extends Model
{
    public function __construct()
    {
        parent::__construct();

        $this->db = db_connect();
    }


    public function show_dispatch_receive_data($diary_no=0,$diary_yr=0)
    {



//       echo $diary_no.">>>".$diary_yr;die;
        $builder = $this->db->table('fil_trap a')
//        $builder->distinct('a.uid,a.diary_no,d_by_empid,d_to_empid,disp_dt,remarks,e.name d_by_name,pet_name,res_name,rece_dt,nature');
        ->DISTINCT();
        $builder->select(" a.uid, a.diary_no, d_by_empid, d_to_empid, disp_dt, remarks, e.name as d_by_name, pet_name, res_name, rece_dt, nature,
                        case when b.ack_id!='0' then 'Old E-filed' else 'Counter filed' end as filing_type");
        $builder->join('main b','a.diary_no = b.diary_no','LEFT',false);
        $builder->join('master.users e','e.empid = a.d_by_empid','LEFT',false);
        $builder->join('efiled_cases ne',"a.diary_no=ne.diary_no and ne.display='Y' and ne.efiled_type='new_case'",'LEFT',false);
        if(!empty($diary_no) && !empty($diary_yr))
        $builder->where("a.diary_no", $diary_no.$diary_yr );
        $builder->where("d_to_empid in
                (SELECT empid FROM master.users WHERE ((usertype=51 AND name LIKE '%FILING DISPATCH RECEIVE%') or (usertype=59 AND name LIKE '%ADVOCATE CHAMBER SUB-SECTION%')))
                AND comp_dt is null and b.c_status='P' and ne.diary_no is null" );
        $builder->orderBy("disp_dt",'DESC',false);
        $query = $builder->get();
        $result = $query->getResultArray();
//        $query=$this->db->getLastQuery();echo (string) $query;exit();
//        echo "<pre>";
//        print_r($result);die;
        if($result)
        {
            return $result;
        }else{
            return 0;
        }

    }

    public function receiveFDR_method($id)
    {
        $builder = $this->db->table('fil_trap');
        $builder->select('diary_no, remarks,r_by_empid,d_to_empid');
        $builder->where('uid', $id);
        $query = $builder->get();
//        $query=$this->db->getLastQuery();     echo (string) $query;exit();
        $result = $query->getResultArray();
//        echo "<pre>";
//        print_r($result);die;
//echo $query->getNumRows();die;
        if($result)
        {
            return $result[0];
        }else{
            return 0;
        }
    }

    public function update_file_trap($id,$rByEmpid,$other)
    {
//        echo "DDD";die;
        $columnsUpdate = array(
            'rece_dt' =>'NOW()',
            'r_by_empid' =>$rByEmpid,
            'create_modify' => date("Y-m-d H:i:s"),
            'updated_on' => date("Y-m-d H:i:s"),
            'updated_by' => session()->get('login')['usercode'],
            'updated_by_ip' => getClientIP()
        );

        $builder = $this->db->table('fil_trap');
        $builder->where('uid', $id);
        $query = $builder->update($columnsUpdate);
        if($query) {
            return 1;
        }else
        {
            return 0;
        }

    }

    public function update_file_trap_comp_other($id,$other)
    {
        $columnsUpdate = array(
            'comp_dt' =>'NOW()',
            'other' =>$other,
            'create_modify' => date("Y-m-d H:i:s"),
            'updated_on' => date("Y-m-d H:i:s"),
            'updated_by' => session()->get('login')['usercode'],
            'updated_by_ip' => getClientIP()
        );

        $builder = $this->db->table('fil_trap');
        $builder->where('uid', $id);
        $query = $builder->update($columnsUpdate);
        if($query) {
            return 1;
        }else
        {
            return 0;
        }

    }

    public function update_main($dno)
    {
        $columnsUpdate = array(
            'refiling_attempt' =>'NOW()',
            'create_modify' => date("Y-m-d H:i:s"),
            'updated_on' => date("Y-m-d H:i:s"),
            'updated_by' => session()->get('login')['usercode'],
            'updated_by_ip' => getClientIP()
        );
        $builder = $this->db->table('main');
        $builder->where('diary_no', $dno);
        $query = $builder->update($columnsUpdate);
        if($query) {
            return 1;
        }else
        {
            return 0;
        }

    }

    public function check_main_table($diary_no,$id)
    {
        $builder = $this->db->table('main');
        $builder->select('*');
        $builder->where('diary_no', $diary_no)->where('ack_id is not null');
        $query = $builder->get();
//        $query=$this->db->getLastQuery();     echo (string) $query;exit();
        $result = $query->getResultArray();
//        echo "<pre>";
//        print_r($result);die;
//echo $query->getNumRows();die;
        if($query->getNumRows()>0)
        {
            return 1;
        }else{
            return 0;
        }

    }

    public function check_remark_filtrap($id)
    {
        $builder = $this->db->table('fil_trap');
        $builder->select('remarks');
        $builder->where('uid', $id);
        $query = $builder->get();
//        $query=$this->db->getLastQuery();     echo (string) $query;exit();
        $result = $query->getResultArray();
//        echo "<pre>";
//        print_r($result);die;
//echo $query->getNumRows();die;
        if($result)
        {
            return $result[0];
        }else{
            return 0;
        }

    }



}






?>