<?php

namespace App\Models\Filing;

use CodeIgniter\Model;

class DiaryentryModel extends Model
{
    protected $db;

    public function __construct(){
        parent::__construct();
        $db = \Config\Database::connect();
        $this->db = db_connect();
    }


    public function get_court_type(){
        $builder = $this->db->table("m_from_court");
        $builder->select("*");
        $builder->WHERE('display','Y');
        $query =$builder->get();
        if($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        }else{return false;}
    }


    public function get_state_list(){
        $builder = $this->db->table("state");
        $builder->select("id_no, name");
        $builder->WHERE('district_code','0');
        $builder->WHERE('sub_dist_code','0');
        $builder->WHERE('village_code','0');
        $builder->WHERE('sci_state_id !=', '0' );
        $builder->WHERE('display','Y');
        $query =$builder->get();
        if($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        }else{return false;}
    }

    /*public function get_bench_list($state_id, $court_type){

        if($court_type == 3){
            if( $state_id=='490506'){
                $sql="SELECT id_no id, court_name as agency_name,Name districtname 
                FROM state s join delhi_district_court d on s.state_code=d.state_code and s.district_code=d.district_code WHERE s.State_code = (SELECT State_code FROM state WHERE id_no =  '$state_id' AND display = 'Y' ) AND s.display = 'Y' AND s.Sub_Dist_code =0 AND s.Village_code =0 AND s.District_code !=0 order by trim(Name)";
            }else{
                $sql="SELECT id_no id, Name agency_name FROM state WHERE State_code = (SELECT State_code FROM state WHERE id_no =  '$state_id' AND display = 'Y' ) AND display = 'Y' AND Sub_Dist_code =0 AND Village_code =0 AND District_code !=0 order by trim(Name)";
            }
        }
        if($court_type=='1'){
            // $sql="SELECT id, agency_name, short_agency_name FROM ref_agency_code WHERE is_deleted = 'f' AND agency_or_court  in(1) AND cmis_state_id = '$state_id'  order by trim(agency_name)";
            $builder = $this->db->table("ref_agency_code");
            $builder->select("id, agency_name, short_agency_name");
            $builder->WHERE('is_deleted','f');
            $builder->WHERE('agency_or_court','1');
            $builder->WHERE('cmis_state_id', $state_id);
            $builder->WHERE('sci_state_id !=', '0' );
            $builder->WHERE('display','Y');
            $query =$builder->get();
        }
        if($court_type=='4'){
            $sql="SELECT id, agency_name, short_agency_name FROM ref_agency_code WHERE is_deleted = 'f' AND agency_or_court ='$court_type' AND cmis_state_id = '$state_id'  order by trim(agency_name)";
        }
        if($court_type=='5'){
            $sql="SELECT id, agency_name, short_agency_name FROM ref_agency_code WHERE is_deleted = 'f' AND agency_or_court  in(2,5,6) AND cmis_state_id = '$state_id'  order by trim(agency_name)";
        }


        $query =$builder->get();
        if($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        }else{return false;}


    }*/


    public function get_role($dcmis_user_idd){

        $builder = $this->db->table("fil_trap_users");
        $builder->select("*");
        $builder->WHERE('usercode',$dcmis_user_idd);
        $builder->WHERE('display','Y');
        $builder->WHERE('usertype','101');
        $query =$builder->get();
        if($query) {
            return $result = $query->getNumRows();
        }else{return false;}
    }


    public function get_bench_list($state_id, $court_type){


        // $builder1 = $this->db->table("state");
        // $builder1->select('State_code');
        // $builder1->where('id_no', $state_id);
        // $builder1->where('display', 'Y');
        // $qry =$builder1->get();
        // $where_clause = '';
        // if($qry->getNumRows() >= 1) {
        //      $wh1 = $qry->getResultArray();
        //      if(!empty($wh1)){
        //         $where_clause = $wh1[0]['State_code']; 
        //      }
        // }else{ $where_clause = ''; }

        // // echo "<pre>";
        // // print_r($where_clause); die;
        // // $this->db->select('State_code');
        // // $this->db->from('state');
        // // $this->db->where('id_no', $state_id);
        // // $this->db->where('display', 'Y');
        // // $where_clause = $this->db->get_compiled_select();

        // $builder = $this->db->table("ref_agency_code");
        // $builder->join('delhi_district_court as d', 's.state_code=d.state_code and s.district_code=d.district_code');
        // $builder->select("*, court_name as agency_name");
        // $builder->WHERE('s.State_code', $where_clause);
        // $builder->WHERE(['s.display' => 'Y', 's.Sub_Dist_code' => '0', 's.Village_code' => '0', 's.District_code !=' => 0 ])->orderBy("Name", "asc");
        // $query =$builder->get();
        // die;

        if($court_type == 3){
            if( $state_id=='490506'){
                $sql="SELECT id_no id, court_name as agency_name,Name districtname 
                 FROM state s join delhi_district_court d on s.state_code=d.state_code and s.district_code=d.district_code WHERE s.State_code = (SELECT State_code FROM state WHERE id_no =  '$state_id' AND display = 'Y' ) AND s.display = 'Y' AND s.Sub_Dist_code =0 AND s.Village_code =0 AND s.District_code !=0 order by trim(Name)";

                $query = $this->db->query($sql);
                $result=$query->getResultArray();
                $result_rows=$query->getNumRows();
                if($result_rows >= 1) {
                    return $result;
                } else {
                    return 0;
                }                

            }else{
                $sql="SELECT id_no id, Name agency_name FROM state WHERE State_code = (SELECT State_code FROM state WHERE id_no =  '$state_id' AND display = 'Y' ) AND display = 'Y' AND Sub_Dist_code =0 AND Village_code =0 AND District_code !=0 order by trim(Name)";


        $query = $this->db->query($sql);
                $result=$query->getResultArray();
                $result_rows=$query->getNumRows();
                if($result_rows >= 1) {
                    return $result;
                } else {
                    return 0;
                }
            }
        }
        if($court_type=='1'){
            $builder = $this->db->table("ref_agency_code");
            $builder->select("id, agency_name, short_agency_name");
            $builder->WHERE(['is_deleted' => 'f', 'agency_or_court' => '1', 'cmis_state_id' => $state_id ])->orderBy("agency_name", "asc");
            $query =$builder->get();
            if($query->getNumRows() >= 1) {
                return $result = $query->getResultArray();
            }else{return false;}
        }
        if($court_type=='4'){
            $builder = $this->db->table("ref_agency_code");
            $builder->select("id, agency_name, short_agency_name");
            $builder->WHERE(['is_deleted' => 'f', 'agency_or_court' => $court_type, 'cmis_state_id' => $state_id ])->orderBy("agency_name", "asc");
            $query =$builder->get();
            if($query->getNumRows() >= 1) {
                return $result = $query->getResultArray();
            }else{return false;}
        }
        if($court_type=='5'){
            $court_no = [2,5,6];
            $builder = $this->db->table("ref_agency_code");
            $builder->select("id, agency_name, short_agency_name");
            $builder->whereIn('agency_or_court', $court_no);
            $builder->WHERE(['cmis_state_id' => $state_id, 'is_deleted' => 'f'])->orderBy("agency_name", "asc");
            $query =$builder->get();
            if($query->getNumRows() >= 1) {
                return $result = $query->getResultArray();
            }else{return false;}
        }

    }


    public function get_district_list($state_id){

        
        // $this->db->select('State_code');
        // $this->db->from('state');
        // $this->db->where('id_no','1');
        // $this->db->where('display','Y');
        // $sub_query = $this->db->get_compiled_select();

        
        // $this->db->select('id_no District_code, Name');
        // $this->db->from('state');
        // $this->db->where("State_code IN ($sub_query)");
        // $this->db->where('District_code !=','0');
        // $this->db->where('Sub_Dist_code','0');
        // $this->db->where('Village_code','0');
        // $this->db->where('display','Y');
        // $query = $this->db->get()->result();
        // echo "<pre>";
        // print_r($query); die;

        $sql="SELECT id_no District_code, Name FROM state WHERE 
        State_code =  (SELECT State_code FROM state WHERE id_no = '$state_id' AND display = 'Y' )    
        AND District_code != 0 AND Sub_Dist_code = 0 AND Village_code = 0 AND display = 'Y' ORDER BY Name";

       $query = $this->db->query($sql);
       $result=$query->getResultArray();
       $result_rows=$query->getNumRows();
       if($result_rows >= 1) {
           return $result;
       } else {
           return 0;
       }  

    }

}