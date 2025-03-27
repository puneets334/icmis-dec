<?php
namespace App\Models;
use CodeIgniter\Model;
class StakeHolder_model extends Model
{
    protected $db;
    public function __construct()
    {
        parent::__construct();
        $this->db = db_connect();
    }
    public function getStakeHolderType(){
        $builder = $this->db->table('master.master_stakeholder_type');
        $builder->select('id,description');
        $builder->where('is_active',1);
        $builder->orderBy('description','ASC');
        $query = $builder->get();
        return $query->getResult();
    }
    public function getState(){
        $builder = $this->db->table('master.state');
        $builder->select('state_code,name,id_no,sci_state_id');
        $builder->where('display','Y');
        $builder->where('sci_state_id !=',0);
        $builder->where('district_code',0);
        $builder->where('sub_dist_code',0);
        $builder->orderBy('name','ASC');

        $query = $builder->get();
        return $query->getResult();
    }
    public function getDistrict($stateId){
        $output = false;
        if(isset($stateId) && !empty($stateId)){
            $builder = $this->db->table('master.state');
            $builder->select('state_code, district_code, name, id_no');
            $builder->where('display', 'Y');
            $builder->where('district_code !=', 0);
            $builder->where('sub_dist_code', 0);
            $builder->where('village_code', 0);
            $builder->where('state_code', (int)$stateId);
            $builder->orderBy('name', 'ASC');

            $query = $builder->get();
            $output = $query->getResult();

        }
        return $output;
    }
    public function getDesignation($params = array()){
        $output = false;
        if(isset($params['authtype']) && !empty($params['authtype'])){
            $builder = $this->db->table('master.authority');
            $builder->select('authcode, authdesc, authtype');
            $builder->where('display', 'Y');
            $builder->whereIn('authtype', $params['authtype']);
            $builder->orderBy('authdesc', 'ASC');

            $query = $builder->get();
            $output = $query->getResult();
        }
        return $output;
    }

    public function insertData($table,$insertArr){
        $output = false;
        if(isset($table) && !empty($table) && isset($insertArr) && !empty($insertArr)){
            $builder = $this->db->table($table);
            $builder->insert($insertArr);
            $output = $this->db->insertID();
        }
        return $output;
    }

    public function updateData($table, $id, $updateArr){
        $output = false;
        if(isset($table) && !empty($table) && isset($id) && !empty($id) && isset($updateArr) && !empty($updateArr)){
            $builder = $this->db->table($table);
            $query = $builder->where('id', (int)$id)->update($updateArr);
            if(!empty($query)){
                $output = true;
            }
        }
        return $output;
    }

    public function getBench($params = array()){
        $builder = $this->db->table('master.ref_agency_code')
            ->select(['id', 'agency_name', 'short_agency_name', 'state_id'])
            ->where('cmis_state_id', (int)$params['cmis_state_id'])
            ->where('agency_or_court', (int)$params['court_type'])
            ->where('is_deleted', 'f')
            ->orderBy('agency_name', 'ASC');

        $query = $builder->get();
        return $query->getResult();
    }

    public function getJailStateList(){
        $builder = $this->db->table('master.jail_master')
            ->select(['State_Code', 'Police_state', 'cmis_state'])
            ->where('Police_state !=', '')
            ->where('District_Code !=', 0)
            ->groupBy('Police_state_code')
            ->orderBy('Police_state', 'ASC');

        $query = $builder->get();
        return $query->getResult();

    }

    public function getJailDistrictList($state_code){
        $output = false;
        if(isset($state_code) && !empty($state_code)){
            $builder = $this->db->table('master.jail_master');
            $builder->select('State_Code, District_Code, Police_district, cmis_district_id');
            $builder->where('State_Code', (int)$state_code);
            $builder->where('police_station_name !=', '');
            $builder->where('District_Code !=', 0);
            $builder->groupBy('Police_district_code');
            $builder->orderBy('Police_district', 'ASC');
            $query = $builder->get();
            $output = $query->getResult();

        }
        return $output;
    }

    public function getJailListByStateDistrictId($params = array()){
        $output = false;
        if(isset($params['stateId']) && !empty($params['stateId']) && isset($params['districtId']) && !empty($params['districtId'])){
            $builder = $this->db->table('master.jail_master');
            $builder->select('Loc_Id, Loc_Det, jail_name');
            $builder->where('State_Code', (int)$params['stateId']);
            $builder->where('District_Code', (int)$params['districtId']);
            $builder->orderBy('Loc_Det', 'ASC');
            $query = $builder->get();
            $output = $query->getResult();

        }
         return $output;
    }

    public function getTribunalData($params = array()){
        $output = false;
        if(isset($params['id_no']) && !empty($params['id_no']) && isset($params['agency_or_court']) && !empty($params['agency_or_court'])){
            $builder = $this->db->table('master.ref_agency_code')
                ->select(['id', 'agency_name'])
                ->where('cmis_state_id', (int)$params['id_no'])
                ->whereIn('agency_or_court', $params['agency_or_court'])
                ->where('is_deleted', 'f')
                ->orderBy('agency_name', 'ASC');

            $query = $builder->get();
            $output = $query->getResult();
        }
        return $output;
    }

    public function getStakeholderData($params = array()){
        $builder = $this->db->table('master.stakeholder_details st')
            ->select([
                'st.id',
                'st.nodal_officer_name',
                'st.jcn_email_id',
                'st.official_email_id',
                'st.mobile_number',
                "TO_CHAR(st.used_from, 'DD-MM-YYYY') AS used_from",
                "TO_CHAR(st.created_on, 'DD-MM-YYYY') AS created_on",
                'mst.description AS stakeholder_type',
                'a.authdesc AS designation'
            ])
            ->join('master.master_stakeholder_type mst', 'st.stakeholder_type_id = mst.id', 'inner')
            ->join('master.authority a', 'st.nodal_officer_designation = a.authcode', 'inner')
            ->where('st.is_deleted', 0)
            ->orderBy('st.created_on', 'DESC');

        if (!empty($params['stakeHolderType'])) {
            $builder->where('st.stakeholder_type_id', (int) $params['stakeHolderType']);
        }

        $query = $builder->get();
        return $query->getResult();

    }

    public function getDataById($id){
        $output = false;
        if(isset($id) && !empty($id)){
            $builder = $this->db->table('master.stakeholder_details')
                ->select('*')
                ->where('id', (int) $id)
                ->where('is_deleted', 0);

            $query = $builder->get();
            return $query->getResultArray();
        }
        return $output;
    }
    public function getReportData($params = array()){
        if(isset($params['stakeholder']) && !empty($params['stakeholder'])){
            $stakeholderTypeId ='';
            $stakeholderTypeId = !empty($params['stakeholder']) ? (int)trim($params['stakeholder']) : NULL;
            $state_id =!empty($params['state_id']) ? (int)trim($params['state_id']) : NULL;
            $district_id =!empty($params['district_id']) ? (int)trim($params['district_id']) : NULL;
            $designation_type =!empty($params['designation_type']) ? (int)trim($params['designation_type']) : NULL;
            $nodal_officer_name =!empty($params['nodal_officer_name']) ? strtolower(trim($params['nodal_officer_name'])) : NULL;
            $jcn_email_id =!empty($params['jcn_email_id']) ? strtolower(trim($params['jcn_email_id'])) : NULL;
            $official_email_id =!empty($params['official_email_id']) ? strtolower(trim($params['official_email_id'])) : NULL;
            $mobile_number =!empty($params['mobile_number']) ? (int)trim($params['mobile_number']) : NULL;
            $used_from =!empty($params['used_from']) ? trim($params['used_from']) : NULL;
            $address =!empty($params['address']) ? strtolower(trim($params['address'])) : NULL;
            $pincode =!empty($params['pincode']) ? trim($params['pincode']) : NULL;
            $phone_no=!empty($params['phone_no']) ? trim($params['phone_no']) : NULL;
            $bench_id =!empty($params['bench_id']) ? (int)trim($params['bench_id']) : NULL;
            $jail_state_id =!empty($params['jail_state_id']) ? (int)trim($params['jail_state_id']) : NULL;
            $jail_district_id =!empty($params['jail_district_id']) ? (int)trim($params['jail_district_id']) : NULL;
            $jail_id =!empty($params['jail_id']) ? trim($params['jail_id']) : NULL;
            $tribunal_id =!empty($params['tribunal_id']) ? (int)trim($params['tribunal_id']) : NULL;
            $cmis_state_id =!empty($params['cmis_state_id']) ? (int)trim($params['cmis_state_id']) : NULL;
            $sql='';$stateWhere ='';$districtWhere ='';$designationWhere ='';$nodalofficernameWhere ='';$jcnemailidWhere ='';$officialemailidWhere='';$mobileWhere ='';$jailWhere='';
            $usedfromWhere ='';$addressWhere ='';$pincodeWhere ='';$phone_noWhere ='';$benchWhere='';$benchName='';$benchJoin='';$districtColumns='';
            $jailJoin='';$jail_name='';$tribunalWhere ='';$tribunalStateWhere ='';$tribunal_name='';$stateJoin='';$districtJoin='';$tribunalJoin='';$orderBy='';
            if(isset($designation_type) && !empty($designation_type)){
                $designationWhere ="and sd.nodal_officer_designation = $designation_type ";
            }
            if(isset($nodal_officer_name) && !empty($nodal_officer_name)){
                $nodalofficernameWhere = "and sd.nodal_officer_name like '$nodal_officer_name%' ";
            }
            if(isset($jcn_email_id) && !empty($jcn_email_id)){
                $jcnemailidWhere = "and sd.jcn_email_id = '$jcn_email_id'";
            }
            if(isset($official_email_id) &&!empty($official_email_id)){
                $officialemailidWhere = "and sd.official_email_id = '$official_email_id'";
            }
            if(isset($mobile_number) && !empty($mobile_number)){
                $mobileWhere = "and sd.mobile_number = $mobile_number";
            }
            if(isset($used_from) && !empty($used_from)) {
                $usedDate = date('Y-m-d',strtotime($used_from));
                $usedfromWhere ="and date(sd.used_from) = '$usedDate' ";
            }
            if(isset($address) && !empty($address)) {
                $addressWhere = "and sd.address like '$address%' ";
            }
            if(isset($pincode) && !empty($pincode)) {
                $pincodeWhere = "and sd.pincode = $pincode";
            }
            if(isset($phone_no) && !empty($phone_no)) {
                $phone_noWhere = "and sd.phone_no = '$phone_no' ";
            }
            $orderBy =" order by sd.updated_on DESC";
            switch ($stakeholderTypeId){
                case 1://jail
                    $stateJoin='';$sql='';$jail_name='';$jailWhere='';$districtWhere='';$districtColumns='';$districtJoin='';$jailJoin='';$stateWhere='';
                    $state_name='';
                    if(isset($jail_state_id) && !empty($jail_state_id)){
                        $stateJoin = "inner join (select State_Code, Police_state, cmis_state from jail_master j where
                                   j.Police_state != '' and j.District_Code !=0   group by Police_state_code
                               ) jm
                        on sd.jail_state_id = jm.State_Code";
                        $state_name =",jm.Police_state as state_name";
                        $stateWhere = "and sd.jail_state_id= $jail_state_id";
                        $districtJoin = "inner join (select State_Code, District_Code, Police_district from jail_master as j1
                                 where j1.police_station_name != '' AND j1.District_Code !=0  and j1.State_Code=$jail_state_id
                                  group by j1.Police_district_code
                                ) j11     
                            on sd.jail_district_id = j11.District_Code";
                        $districtColumns = ",j11.Police_district as district_name";
                        $jailJoin ="inner join (select Loc_Id, Loc_Det, jail_name from jail_master as j2 
		                where   j2.State_code = $jail_state_id) as j22
                            on sd.jail_id = j22.Loc_Id ";
                          $jail_name = ",j22.Loc_Det as jail_name";
                    }
                    if(isset($jail_district_id) && !empty($jail_district_id)){
                        $districtJoin = "inner join (select State_Code, District_Code, Police_district from jail_master as j1
                                 where j1.police_station_name != '' AND j1.District_Code !=0  and j1.State_Code=$jail_state_id
                                  group by j1.Police_district_code
                                ) j11     
                            on sd.jail_district_id = j11.District_Code and sd.jail_district_id=$jail_district_id";
                        $districtColumns = ",j11.Police_district as district_name";
                        $districtWhere = "and sd.jail_district_id= $jail_district_id";
                    }
                    if(isset($jail_id) && !empty($jail_id)){
                        $jailJoin ="inner join (select Loc_Id, Loc_Det, jail_name from jail_master as j2 
		                where   j2.State_code = $jail_state_id) as j22
                            on sd.jail_id = j22.Loc_Id ";
                        $jail_name = ",j22.Loc_Det as jail_name";
                        $jailWhere = "and sd.jail_id= '$jail_id' ";
                    }
                    if(empty($jail_state_id) && empty($jail_district_id) && empty($jail_id)){
                        $sql ="select sd.nodal_officer_name,sd.jcn_email_id,sd.official_email_id,sd.mobile_number,DATE_FORMAT(sd.used_from,'%d-%m-%Y') as used_from,DATE_FORMAT(sd.created_on,'%d-%m-%Y') as created_on,sd.address,
                            sd.pincode,sd.phone_no,mst.description as stakeholder_type,
                            a.authdesc as designation, j.Police_state as state_name, j.Police_district as district_name, j.Loc_Det as jail_name
                            from stakeholder_details as sd 
                            inner join master_stakeholder_type as mst
                            on sd.stakeholder_type_id = mst.id inner join authority as a 
                            on sd.nodal_officer_designation = a.authcode  inner join jail_master j
                            on sd.jail_state_id = j.State_Code and sd.jail_district_id =j.District_Code and sd.jail_id = j.Loc_Id 
                            where sd.stakeholder_type_id = $stakeholderTypeId $designationWhere $nodalofficernameWhere $jcnemailidWhere $officialemailidWhere $mobileWhere $usedfromWhere
                            $addressWhere $pincodeWhere $phone_noWhere $orderBy";
                    }
                    else{
                           $sql ="select sd.nodal_officer_name,sd.jcn_email_id,sd.official_email_id,sd.mobile_number,DATE_FORMAT(sd.used_from,'%d-%m-%Y') as used_from,DATE_FORMAT(sd.created_on,'%d-%m-%Y') as created_on,sd.address,
                            sd.pincode,sd.phone_no,mst.description as stakeholder_type,
                            a.authdesc as designation $state_name $districtColumns $jail_name
                            from stakeholder_details as sd 
                            inner join master_stakeholder_type as mst
                            on sd.stakeholder_type_id = mst.id inner join authority as a 
                            on sd.nodal_officer_designation = a.authcode $stateJoin  $districtJoin $jailJoin
                            where sd.stakeholder_type_id = $stakeholderTypeId
                            $stateWhere $districtWhere $jailWhere $designationWhere $nodalofficernameWhere $jcnemailidWhere $officialemailidWhere $mobileWhere $usedfromWhere
                            $addressWhere $pincodeWhere $phone_noWhere $orderBy
                            ";

                    }
                    break;
                case 2://hc
                    $stateJoin='';$sql='';$benchWhere='';$benchJoin='';$stateWhere='';
                    if(isset($state_id) && !empty($state_id)){
                        $stateWhere = "and sd.state_id= $state_id";
                        $stateJoin = "inner join state s 
                            on sd.state_id = s.State_code and s.display = 'Y' AND s.sci_state_id !=0 AND s.District_code =0 AND 
                            s.Sub_Dist_code =0 and s.State_code= $state_id";
                        $benchJoin ="inner join ref_agency_code rac 
                        on sd.bench_id = rac.id and rac.is_deleted = 'f' and rac.agency_or_court = 1";
                        $benchName =",rac.agency_name";
                    }
                    else{
                        $stateJoin = "inner join state s 
                            on sd.state_id = s.State_code and s.display = 'Y' AND s.sci_state_id !=0 AND s.District_code =0 AND 
                            s.Sub_Dist_code =0";
                        $benchJoin ="inner join ref_agency_code rac 
                        on sd.bench_id = rac.id and rac.is_deleted = 'f' and rac.agency_or_court = 1";
                        $benchName =",rac.agency_name";
                    }
                    if(isset($bench_id) && !empty($bench_id)){
                        $benchWhere ="and sd.bench_id = $bench_id";
                    }
                    $sql ="select sd.nodal_officer_name,sd.jcn_email_id,sd.official_email_id,sd.mobile_number,DATE_FORMAT(sd.used_from,'%d-%m-%Y') as used_from,DATE_FORMAT(sd.created_on,'%d-%m-%Y') as created_on,sd.address,
                            sd.pincode,sd.phone_no,mst.description as stakeholder_type,
                            a.authdesc as designation ,s.name as state_name $benchName
                            from stakeholder_details as sd 
                            inner join master_stakeholder_type as mst
                            on sd.stakeholder_type_id = mst.id inner join authority as a 
                            on sd.nodal_officer_designation = a.authcode $stateJoin  $benchJoin
                            where sd.stakeholder_type_id = $stakeholderTypeId
                            $stateWhere $benchWhere  $designationWhere $nodalofficernameWhere $jcnemailidWhere $officialemailidWhere $mobileWhere $usedfromWhere
                            $addressWhere $pincodeWhere $phone_noWhere $orderBy
                            ";
                    break;
                case 3: //state
                    $sql='';$stateWhere='';$stateJoin='';
                    if(isset($state_id) && !empty($state_id)){
                        $stateWhere = "and sd.state_id= $state_id";
                        $stateJoin = "inner join state s 
                            on sd.state_id = s.State_code and s.display = 'Y' AND s.sci_state_id !=0 AND s.District_code =0 AND 
                            s.Sub_Dist_code =0 and s.State_code= $state_id";
                    }
                    else{
                        $stateJoin = "inner join state s 
                            on sd.state_id = s.State_code and s.display = 'Y' AND s.sci_state_id !=0 AND s.District_code =0 AND 
                            s.Sub_Dist_code =0";
                    }
                    $sql ="select sd.nodal_officer_name,sd.jcn_email_id,sd.official_email_id,sd.mobile_number,DATE_FORMAT(sd.used_from,'%d-%m-%Y') as used_from,DATE_FORMAT(sd.created_on,'%d-%m-%Y') as created_on,sd.address,
                            sd.pincode,sd.phone_no,mst.description as stakeholder_type,
                            a.authdesc as designation ,s.name as state_name 
                            from stakeholder_details as sd 
                            inner join master_stakeholder_type as mst
                            on sd.stakeholder_type_id = mst.id inner join authority as a 
                            on sd.nodal_officer_designation = a.authcode $stateJoin 
                            where sd.stakeholder_type_id = $stakeholderTypeId
                            $stateWhere $designationWhere $nodalofficernameWhere $jcnemailidWhere $officialemailidWhere $mobileWhere $usedfromWhere
                            $addressWhere $pincodeWhere $phone_noWhere $orderBy
                            ";
                    break;
                case 4://dc
                    $sql='';$stateJoin='';$stateWhere='';$districtJoin='';$districtColumns='';$districtWhere='';
                    if(isset($state_id) && !empty($state_id)){
                        $stateWhere = "and sd.state_id= $state_id";
                        $stateJoin = "inner join state s 
                            on sd.state_id = s.State_code and s.display = 'Y' AND s.sci_state_id !=0 AND s.District_code =0 AND 
                            s.Sub_Dist_code =0 and s.State_code= $state_id";
                        $districtJoin = "inner join state as ss
                            on sd.district_id = ss.District_code and ss.display = 'Y' AND ss.District_code !=0 AND ss.Sub_Dist_code =0
                            AND ss.Village_code =0 and ss.State_code = $state_id ";
                        $districtColumns = ",ss.name as district_name";
                    }
                    else{
                        $stateJoin = "inner join state s 
                            on sd.state_id = s.State_code and s.display = 'Y' AND s.sci_state_id !=0 AND s.District_code =0 AND 
                            s.Sub_Dist_code =0";
                        $districtJoin = "inner join state as ss
                            on sd.district_id = ss.District_code and ss.display = 'Y' AND ss.District_code !=0 AND ss.Sub_Dist_code =0
                            AND ss.Village_code =0 and ss.id_no = sd.cmis_state_id ";
                        $districtColumns = ",ss.name as district_name";
                    }
                    if(isset($district_id) && !empty($district_id)){
                        $districtWhere ="and sd.district_id = $district_id";
                    }
                    $sql ="select sd.nodal_officer_name,sd.jcn_email_id,sd.official_email_id,sd.mobile_number,DATE_FORMAT(sd.used_from,'%d-%m-%Y') as used_from,DATE_FORMAT(sd.created_on,'%d-%m-%Y') as created_on,sd.address,
                            sd.pincode,sd.phone_no,mst.description as stakeholder_type,
                            a.authdesc as designation ,s.name as state_name $districtColumns
                            from stakeholder_details as sd 
                            inner join master_stakeholder_type as mst
                            on sd.stakeholder_type_id = mst.id inner join authority as a 
                            on sd.nodal_officer_designation = a.authcode $stateJoin $districtJoin
                            where sd.stakeholder_type_id = $stakeholderTypeId
                            $stateWhere $districtWhere $designationWhere $nodalofficernameWhere $jcnemailidWhere $officialemailidWhere $mobileWhere $usedfromWhere
                            $addressWhere $pincodeWhere $phone_noWhere $orderBy
                            ";
                    break;
                case 5://tribunal
                    $sql=''; $stateWhere='';$stateJoin='';$tribunalJoin='';$tribunal_name='';$tribunalWhere='';
                    if(isset($state_id) && !empty($state_id)){
                        $stateWhere = "and sd.tribunal_state_id= $state_id";
                        $stateJoin = "inner join state s 
                            on sd.tribunal_state_id = s.State_code and s.display = 'Y' AND s.sci_state_id !=0 AND s.District_code =0 AND 
                            s.Sub_Dist_code =0 and s.State_code= $state_id";
                        $tribunalJoin = "inner join ref_agency_code as rac
                            on sd.cmis_state_id = rac.cmis_state_id and sd.tribunal_id=rac.id and rac.is_deleted = 'f'  and rac.agency_or_court IN(2, 5, 6) ";
                        $tribunal_name = ", rac.agency_name";
                    }
                    else{
                        $stateJoin = "inner join state s 
                            on sd.tribunal_state_id = s.State_code and s.display = 'Y' AND s.sci_state_id !=0 AND s.District_code =0 AND 
                            s.Sub_Dist_code =0";
                        $tribunalJoin = "inner join ref_agency_code as rac
                            on sd.cmis_state_id = rac.cmis_state_id and sd.tribunal_id=rac.id and rac.is_deleted = 'f'  and rac.agency_or_court IN(2, 5, 6) ";
                        $tribunal_name = ", rac.agency_name";
                    }
                    if(isset($tribunal_id) && !empty($tribunal_id)){
                        $tribunalWhere ="and sd.tribunal_id = $tribunal_id  and rac.agency_or_court IN(2, 5, 6)";
                        $tribunal_name = ", rac.agency_name";
                    }
                    $sql ="select sd.nodal_officer_name,sd.jcn_email_id,sd.official_email_id,sd.mobile_number,DATE_FORMAT(sd.used_from,'%d-%m-%Y') as used_from,DATE_FORMAT(sd.created_on,'%d-%m-%Y') as created_on,sd.address,
                            sd.pincode,sd.phone_no,mst.description as stakeholder_type ,
                            a.authdesc as designation ,s.name as state_name $tribunal_name
                            from stakeholder_details as sd 
                            inner join master_stakeholder_type as mst
                            on sd.stakeholder_type_id = mst.id inner join authority as a 
                            on sd.nodal_officer_designation = a.authcode $stateJoin $tribunalJoin
                            where sd.stakeholder_type_id = $stakeholderTypeId
                            $stateWhere $tribunalWhere $designationWhere $nodalofficernameWhere $jcnemailidWhere $officialemailidWhere $mobileWhere $usedfromWhere
                            $addressWhere $pincodeWhere $phone_noWhere $orderBy
                            ";
                    break;
                default:
            }
            //echo $sql; exit;
           // $result = $this->db->query($sql)->result();
           // echo '<pre>'; print_r($result); exit;
            $result = $this->db->query($sql)->getResult();
            return $result;
        }
    }

}