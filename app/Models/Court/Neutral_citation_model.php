<?php

namespace App\Models\Court;

use CodeIgniter\Model;

class Neutral_citation_model extends Model
{

    public function __construct(){
        parent::__construct();
        $this->db = db_connect();
    }

    public function getJudges(){

        $builder = $this->db->table("master.judge");
        $builder->select("*");
        $builder->where("jtype","J");
        //$builder->where("is_retired","N");
        $builder->where("display","Y");
        $builder->orderBy('is_retired, judge_seniority');
        $query =$builder->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        }else{
            return [];
        } 
    }

    public function get_neutral_citation_details($diary_no){

        $builder = $this->db->table("public.neutral_citation");
        $builder->select("*");
        $builder->where("diary_no",$diary_no);
        $query =$builder->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        }else{
            return [];
        }
    }

    public function getListedDate($diary_no){

        $tbl_heardt = is_table_a('heardt');
        $tbl_last_heardt = is_table_a('last_heardt');

        $builder1 = $this->db->table("public.".$tbl_heardt." h");
        $builder1->select("h.next_dt");
        $builder1->join("public.cl_printed cl", "cl.next_dt=h.next_dt AND cl.m_f=h.mainhead AND cl.part=h.clno AND cl.main_supp=h.main_supp_flag AND cl.roster_id=h.roster_id");
        $builder1->where("diary_no",$diary_no);
        $builder1->whereIn("h.main_supp_flag",[1,2]);
        $builder1->where("h.judges!=CAST(0 AS TEXT)");
        $builder1->where("h.judges!=''");
        $builder1->where("h.clno!=0");
        $builder1->where("h.brd_slno!=0");
        $builder1->where("cl.next_dt IS NOT NULL");


        $builder2 = $this->db->table("public.".$tbl_last_heardt." h");
        $builder2->select("h.next_dt");
        $builder2->join("public.cl_printed cl", "cl.next_dt=h.next_dt AND cl.m_f=h.mainhead AND cl.part=h.clno AND cl.main_supp=h.main_supp_flag AND cl.roster_id=h.roster_id");
        $builder2->where("diary_no",$diary_no);
        $builder2->whereIn("h.main_supp_flag",[1,2]);
        $builder2->where("h.judges!=CAST(0 AS TEXT)");
        $builder2->where("h.judges!=''");
        $builder2->where("(h.bench_flag='' or h.bench_flag is null)");
        $builder2->where("h.clno!=0");
        $builder2->where("h.brd_slno!=0");
        $builder2->where("cl.next_dt IS NOT NULL");

        $subquery = $builder1->union($builder2);

        $finalQuery  = $this->db->newQuery()->select('next_dt')->fromSubquery($subquery, 'a')->orderBy('next_dt','DESC')->limit(1);

        $query =$finalQuery->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        }else{
            return [];
        }
    }

    public function getDetails($diary_no){

        $tbl_main = is_table_a('main');

        $builder = $this->db->table("public.".$tbl_main." m");
        $builder->select("SUBSTRING(diary_no::TEXT,1,length(diary_no::TEXT)-4) AS case_no, m.diary_no, m.active_casetype_id, m.active_fil_no, m.active_reg_year, m.pet_name, m.res_name, m.reg_no_display");
        $builder->where("m.diary_no",$diary_no);
        $query =$builder->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        }else{
            return [];
        }
    }

    public function getNcNumber($year){

        $builder = $this->db->table("public.neutral_citation_deleted");
        $builder->select("min(nc_number) as min_nc_no, id");
        $builder->where("is_used","N");
        $builder->where("nc_year",$year);
        $builder->groupBy("id");
        $query =$builder->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        }else{
            return [];
        }
    }

    public function updateNcNumber($id){

        $builder = $this->db->table('public.neutral_citation_deleted');
        $builder->where('id',$id);

        $query = $builder->update(['is_used' => 'Y']);

        if($query) {
            return 1;
        }else
        {
            return 0;
        }
    }

    public function getNcNumberForNeutralCitation($year){

        $builder = $this->db->table("public.neutral_citation");
        $builder->select("max(nc_number)+1 as max_nc_no");
        $builder->where("nc_year",$year);
        $query =$builder->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        }else{
            return [];
        }
    }

    public function getDetailsDispose($diary_no){

        $tbl_main = is_table_a('main');

        $builder = $this->db->table("public.".$tbl_main." m");
        $builder->select("substr( m.diary_no::TEXT, 1, length( m.diary_no::TEXT ) -4 ) AS case_no,m.diary_no,m.active_casetype_id,m.active_fil_no,m.active_reg_year,m.pet_name,m.res_name,m.reg_no_display, nc.dispose_order_date");
        $builder->join("public.neutral_citation nc", "nc.diary_no=m.diary_no");
        $builder->where("m.diary_no",$diary_no);
        // pr($builder->getCompiledSelect());
        $query =$builder->get();
        if($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        }else{
            return [];
        }
    }

    public function deleteandUpdate($diary_number,$case_no,$date_judgment, $reason, $ip_address, $ucode){

        $builder = $this->db->table("public.neutral_citation");
        $builder->select("*");
        $builder->where("is_deleted","f");
        $builder->where("diary_no",$diary_number);
        $builder->where("dispose_order_date",$date_judgment);
        $query =$builder->get();

        if($query->getNumRows() >= 1) {
            $last_data = $query->getResultArray();

            $dataSet = [
                'diary_no' =>  $last_data[0]['diary_no'],
                'nc_number' =>  $last_data[0]['nc_number'],
                'nc_year' =>  $last_data[0]['nc_year'],
                'nc_display' =>  $last_data[0]['nc_display'],
                'updated_by' =>  $last_data[0]['updated_by'],
                'updated_on' =>  $last_data[0]['updated_on'],
                'is_deleted' =>  $last_data[0]['is_deleted'],
                'active_casetype_id' =>  $last_data[0]['active_casetype_id'],
                'active_fil_no' =>  $last_data[0]['active_fil_no'],
                'active_reg_year' =>  $last_data[0]['active_reg_year'],
                'pet_name' =>  $last_data[0]['pet_name'],
                'res_name' =>  $last_data[0]['res_name'],
                'dispose_order_date' =>  $last_data[0]['dispose_order_date'],
                'reg_no_display' =>  $last_data[0]['reg_no_display'],
                'order_type' =>  $last_data[0]['order_type'],
                'coram' =>  $last_data[0]['coram'],
                'no_of_judges' =>  $last_data[0]['no_of_judges'],
                'judgment_pronounced_by'=>$last_data[0]['judgment_pronounced_by'],
                'deleted_on' => date('Y-m-d H:i:s'),
                'deleted_by' => $ucode,
                'reason_for_deletion' => $reason,
                'deleted_by_ip' => $ip_address,
                'is_used' => 'N',
                'create_modify' => date("Y-m-d H:i:s"),
                'updated_by_ip' => getClientIP()
            ];

            $insert_id = insert('public.neutral_citation_deleted',$dataSet);
            
            if($insert_id == 1){

                $builder1 = $this->db->table('public.neutral_citation');
                $builder1->where('diary_no',$diary_number);
                $builder1->where('dispose_order_date',$date_judgment);
                $builder1->where('is_deleted','f');
                $del_query = $builder1->delete();

                echo $del_query;
            }
            else{
                return 'Error while deleting record';
            }

        }else{
            return 'Record not found';
        }
    }

}