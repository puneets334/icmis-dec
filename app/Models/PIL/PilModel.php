<?php
namespace App\Models\PIL;

use CodeIgniter\Model;


class PilModel extends Model
{
    function __construct()
    {
        parent::__construct();
        $db = \Config\Database::connect();
    }


    public function get_state_list()
    {
        $builder = $this->db->table('master.ref_state');
        $builder->select('id as state_code,state_name');
        $builder->orderBy('state_name');
        $query = $builder->get();
        $result = $query->getResultArray();
//        echo "<pre>";
//        print_r($result);
        if ($query->getNumRows() >= 1) {
            return $result;
        } else {
            return false;
        }


    }

    public function getPilId($diaryNo, $diaryYear)
    {
        $builder = $this->db->table('ec_pil');
        $builder->select('id');
        $builder->where('diary_number', $diaryNo);
        $builder->where('diary_year', $diaryYear);
        $query = $builder->get();
//        $query=$this->db->getLastQuery();echo (string) $query;exit();
        $result = $query->getResultArray();

        if ($query->getNumRows() >= 1) {
            return $result;
        } else {
            return 'false';
        }

    }

    public function transferPilDataToLogtable($pilid)
    {
        $id = $pilid[0];
        $builder = $this->db->table("ec_pil");
        $builder->select('*');
        $builder->where('id', $id);
        $ins_data_query = $builder->get();
        if ($ins_data_query->getNumRows() >= 1) {
            $insertData = $ins_data_query->getResultArray();
//pr($insertData);
        
            $query = $this->db->table('ec_pil_log')->insertBatch($insertData);
            return $query;
        } else {
            return 'false';
        }


    }

    function transferPilDataToLogtableUsingGroup($ecPilGroupId,$pilIds="")
    {
        $query="";
        if($pilIds==""){
            $query="insert into ec_pil_log select * from ec_pil where group_file_number=".$ecPilGroupId."";
        }
        else{
            $query="insert into ec_pil_log select * from ec_pil where group_file_number=".$ecPilGroupId." and id in ($pilIds)";
        }

       $result =  $this->db->query($query);
//        echo $result;die;
    }

    function performGroupUpdate($updatesql)
    {
//        echo $updatesql;die;
        $this->db->query($updatesql);
        $rowsAffected=$this->db->affectedRows();
//        echo ">>".$rowsAffected;die;
        if($rowsAffected)
        {
            return 1;
        }else{
            return 0;
        }

    }

    function getActionTakenInformation($ecPilId){
//        print_r($ecPilId);die;
        $queryString="select ep.*,rpat.pil_sub_action_code from ec_pil ep left outer join master.ref_pil_action_taken rpat on ep.ref_action_taken_id=rpat.id where ep.id=$ecPilId";
//        echo ">>>".$queryString;die;
        $query = $this->db->query($queryString);
        return $query->getResultArray();
    }


    public function addInPilGroup($ecPilGroupId, $ecPilId, $usercode)
    {
        $result = $this->transferPilDataToLogtable($ecPilId);
//        echo "<pre>";
//        print_r($ecPilGroupId);
//        echo "<br>";
//        print_r($ecPilId);
//        echo "<br>";
//        print_r($result);
//        die;
        $ecPilId = $ecPilId[0];
        if ($result) {
            $data = array(
                'group_file_number' => $ecPilGroupId,
                'adm_updated_by' => $usercode,
                'updated_on' => date('Y-m-d H:i:s'),
                'updated_by' => $usercode,
                'updated_by_ip' => getClientIP()
            );
            $builder = $this->db->table('ec_pil');
            $builder->where('id', $ecPilId)->where('is_deleted', 'f');
            $query = $builder->update($data);
//            $query=$this->db->getLastQuery();echo (string) $query;exit();
//            print_r($query);
//            die;
            if ($query) {
                return 1;
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }


    public function getCasesInPilGroup($ecPilGroupId)
    {

        $builder = $this->db->table('ec_pil')
            ->select('ec_pil.id,ec_pil.destroy_on,ec_pil.in_record_on, ec_pil.is_deleted, CONCAT(ec_pil.diary_number, \'/\', ec_pil.diary_year) as pil_diary_number, ec_pil.received_from, ec_pil.received_on, ec_pil.subject, rpc.pil_category, ec_pil.petition_date, epgf.group_file_number')
            ->join('master.ref_pil_category rpc', 'ec_pil.ref_pil_category_id = rpc.id', 'left outer')
            ->join('ec_pil_group_file epgf', 'ec_pil.group_file_number=epgf.id', 'left outer')
            ->where('ec_pil.group_file_number', $ecPilGroupId)
            ->orderBy('ec_pil.updated_on', 'DESC');
        $query1 = $builder->get();
//        $query=$this->db->getLastQuery();echo (string) $query;exit();
        $result = $query1->getResultArray();
//        echo "<pre>";
//        print_r($result);
//        die;
        if ($query1->getNumRows() >= 1) {
            return $result;
        } else {
            return 0;
        }


    }

    public function getCasesInPilGroup_asc($ecPilGroupId)
    {

        $builder = $this->db->table('ec_pil')
            ->select('ec_pil.id, CONCAT(ec_pil.diary_number, \'/\', ec_pil.diary_year) as pil_diary_number, ec_pil.received_from, ec_pil.received_on, ec_pil.subject, rpc.pil_category, ec_pil.petition_date, epgf.group_file_number')
            ->join('master.ref_pil_category rpc', 'ec_pil.ref_pil_category_id = rpc.id', 'left outer')
            ->join('ec_pil_group_file epgf', 'ec_pil.group_file_number=epgf.id', 'left outer')
            ->where('ec_pil.group_file_number', $ecPilGroupId)
            ->orderBy('ec_pil.updated_on', 'ASC');
        $query2 = $builder->get();
        $result = $query2->getResultArray();
        if ($query2->getNumRows() >= 1) {
            return $result;
        } else {
            return false;
        }

    }

    public function getPilCategory()
    {

        $builder = $this->db->table('master.ref_pil_category');
        $builder->orderBy('id', 'ASC');
        $builder->where('is_deleted', 'f');
        $query = $builder->get();
        $result = $query->getResultArray();
        if ($query->getNumRows() >= 1) {
//            echo "<pre>";
//            print_r($result);
//            die;
            return $result;
        } else {
            return false;
        }


    }

    public function getPilGroup()
    {

        $builder = $this->db->table('ec_pil_group_file');
        $builder->orderBy('updated_on', 'ASC');
        $builder->where('is_deleted', 'f');
        $query = $builder->get();
        $result = $query->getResultArray();
        if ($query->getNumRows() >= 1) {
//            echo "<pre>";
//            print_r($result);
//            die;
            return $result;
        } else {
            return 'false';
        }

    }

    public function getPilDataById($ecPilId)
    {


        $builder = $this->db->table('ec_pil');
        $builder->select('ec_pil.*,ref_pil_action_taken.pil_sub_action_code,ref_pil_action_taken.sub_action_description');
        $builder->where('ec_pil.id', $ecPilId);
        $builder->join('master.ref_pil_action_taken', 'ec_pil.ref_action_taken_id = ref_pil_action_taken.id', 'LEFT');
        $query = $builder->get();
        $result = $query->getResultArray();
        if ($query->getNumRows() >= 1) {
//            echo "<pre>";
//            print_r($result);
//            die;
            return $result;
        } else {
            return false;
        }

    }


    public function getPilData()
    {

        $builder = $this->db->table('ec_pil ep');
        $builder->select('ep.id,concat(ep.diary_number,\'/\',ep.diary_year) as pil_diary_number,ep.received_from,ep.received_on,ep.subject,
rpc.pil_category,ep.petition_date,epgf.group_file_number,ep.address,ep.mobile,ep.email,ep.other_text,ep.ref_pil_category_id,ep.request_summary');
        $builder->join('master.ref_pil_category rpc', 'ep.ref_pil_category_id=rpc.id', 'LEFT');
        $builder->join('ec_pil_group_file epgf', 'ep.group_file_number=epgf.id', 'LEFT');
        $builder->orderBy('ep.id', 'DESC');
        $builder->limit(30);
        $query = $builder->get();
//        $query=$this->db->getLastQuery();echo (string) $query;exit();
        $result = $query->getResultArray();
        if ($query->getNumRows() >= 1) {
//            echo "<pre>";
//            print_r($result);
//            die;
            return $result;
        } else {
            return false;
        }

    }

    public function savePilGroupData($pilGroupId, $groupFileNumber, $usercode)
    {
//        echo "P=".$pilGroupId."G".$groupFileNumber."U".$usercode;
//        die;

        $data = array(
            'group_file_number' => $groupFileNumber,
            'adm_updated_by' => $usercode,
            'updated_on' => date('Y-m-d H:i:s'),
            'create_modify' => date("Y-m-d H:i:s"),
            'updated_by' => $usercode,
            'updated_by_ip' => getClientIP()
        );
        if ($pilGroupId == 0 || $pilGroupId == null) {
            $builder = $this->db->table('ec_pil_group_file');
            $query = $builder->insert($data);
//            $query = $builder->set($data)->getCompiledInsert('ec_pil_group_file');
//            echo $query;
//            die;
            return $query;
        } else {
            $builder = $this->db->table('ec_pil_group_file');
            $builder->where('id', $pilGroupId);
            $query = $builder->update($data);
            return $query;

        }
        //echo $this->db->affected_rows() > 0;
//        return $this->db->affected_rows();

    }

    public function getActionReason($actionType)
    {
//        echo "RRR";
//        die;
//        $builder = $this->db->table('master.ref_pil_action_taken');
//        $builder->where('is_deleted','f');
//        $builder->like('pil_sub_action_code',$actionType);
//        $builder->orderBy('id', 'ASC');
//        $query = $builder->get();
//        $query=$this->db->getLastQuery();echo (string) $query;exit();
//        $result = $query->getResultArray();
//        if($query->getNumRows()>=1)
//        {
//            echo "<pre>";
//            print_r($result);
//            die;
//            return $result;
//        }else{
//            return false;
//        }
        $builder = $this->db->table('master.ref_pil_action_taken');
        $builder->where('is_deleted', 'f');
        $builder->where("LOWER(pil_sub_action_code) LIKE LOWER('%$actionType%')", null, false);
        $builder->orderBy('id', 'ASC');
        $query = $builder->get();
//        $queryString = $this->db->getLastQuery();
//        echo $queryString; // Print the query for debugging purposes
//        exit();
        $result = $query->getResultArray();
        if ($query->getNumRows() >= 1) {
//            echo "<pre>";
//            print_r($result);
//            die;
            return $result;
        } else {
            return false;
        }

    }

    public function getPilGroupDataById($ecPilGroupId)
    {
        $builder = $this->db->table('ec_pil_group_file');
        $builder->where('id', $ecPilGroupId);
        $query = $builder->get();
        $result = $query->getResultArray();
        if ($query->getNumRows() >= 1) {
//            echo "<pre>";
//            print_r($result);
//            die;
            return $result;
        } else {
            return false;
        }


    }

    public function getPilReportData($fromDate, $toDate, $reportType)
    {
//        echo $reportType.">>>".$fromDate.">>>$toDate";
//        die;
        $queryString = "";

        if ($reportType == "R") {
            $builder = $this->db->table('ec_pil ep');
            $builder->select('ep.*,rs.state_name,concat(ep.diary_number,\'/\',ep.diary_year) as pil_diary_number,rpat.pil_sub_action_code,
            rpat.sub_action_description,rpc.pil_code,rpc.pil_category,u.name as username,u.empid');

            $builder->join('master.ref_pil_action_taken rpat', 'ep.ref_action_taken_id=rpat.id', 'LEFT');
            $builder->join('master.ref_pil_category rpc', 'ep.ref_pil_category_id=rpc.id', 'LEFT');
            $builder->join('master.ref_state rs', 'ep.ref_state_id=rs.id', 'LEFT');
            $builder->join('master.users u', 'ep.adm_updated_by=u.usercode', 'LEFT');
            $builder->where("date(received_on) between '$fromDate' and '$toDate'");
            $builder->orderBy('received_on');
            $query = $builder->get();

//            $queryString="select ep.*,rs.state_name,concat(ep.diary_number,'/',ep.diary_year) as pil_diary_number,
// rpat.pil_sub_action_code,rpat.sub_action_description,rpc.pil_code,rpc.pil_category,
//  u.name as username,u.empid
//        from ec_pil ep
//        left join master.ref_pil_action_taken rpat on ep.ref_action_taken_id=rpat.id
//        left join master.ref_pil_category rpc on ep.ref_pil_category_id=rpc.id
//        left join master.ref_state rs on ep.ref_state_id=rs.id
//        left join master.users u on ep.adm_updated_by=u.usercode
//        where date(received_on) between ? and ? order by received_on";
        } elseif ($reportType == "D") {

            $builder = $this->db->table('ec_pil ep');
            $builder->select('ep.*,rs.state_name,concat(ep.diary_number,\'/\',ep.diary_year) as pil_diary_number,rpat.pil_sub_action_code,
            rpat.sub_action_description,rpc.pil_code,rpc.pil_category,u.name as username,u.empid');

            $builder->join('master.ref_pil_action_taken rpat', 'ep.ref_action_taken_id=rpat.id', 'LEFT');
            $builder->join('master.ref_pil_category rpc', 'ep.ref_pil_category_id=rpc.id', 'LEFT');
            $builder->join('master.ref_state rs', 'ep.ref_state_id=rs.id', 'LEFT');
            $builder->join('master.users u', 'ep.adm_updated_by=u.usercode', 'LEFT');
            $builder->where("date(destroy_on) between '$fromDate' and '$toDate'");
            $builder->where('ep.is_deleted', 't');
            $builder->orderBy('destroy_on');
            $query = $builder->get();

//            $queryString="select ep.*,rs.state_name,concat(ep.diary_number,'/',ep.diary_year) as pil_diary_number,
// rpat.pil_sub_action_code,rpat.sub_action_description,rpc.pil_code,rpc.pil_category,
//  u.name as username,u.empid
//        from ec_pil ep
//        left join master.ref_pil_action_taken rpat on ep.ref_action_taken_id=rpat.id
//        left join master.ref_pil_category rpc on ep.ref_pil_category_id=rpc.id
//        left join master.ref_state rs on ep.ref_state_id=rs.id
//        left join master.users u on ep.adm_updated_by=u.usercode
//        where date(destroy_on) between ? and ? and ep.is_deleted='t' order by destroy_on";
        } elseif ($reportType == "P") {

            $builder = $this->db->table('ec_pil ep');
            $builder->select("ep.*,rs.state_name,concat(ep.diary_number,'/',ep.diary_year) as pil_diary_number,
                      rpat.pil_sub_action_code,rpat.sub_action_description,rpc.pil_code,rpc.pil_category,u.name as username,u.empid");

            $builder->join('master.ref_pil_action_taken rpat', 'ep.ref_action_taken_id=rpat.id', 'LEFT');
            $builder->join('master.ref_pil_category rpc', 'ep.ref_pil_category_id=rpc.id', 'LEFT');
            $builder->join('master.ref_state rs', 'ep.ref_state_id=rs.id', 'LEFT');
            $builder->join('master.users u', 'ep.adm_updated_by=u.usercode', 'LEFT');
            $builder->where("date(petition_date) between '$fromDate' and '$toDate'");
            $builder->orderBy('petition_date');
            $query = $builder->get();

//            $queryString="select ep.*,rs.state_name,concat(ep.diary_number,'/',ep.diary_year) as pil_diary_number,
// rpat.pil_sub_action_code,rpat.sub_action_description,rpc.pil_code,rpc.pil_category,
//  u.name as username,u.empid
//        from ec_pil ep
//        left join master.ref_pil_action_taken rpat on ep.ref_action_taken_id=rpat.id
//        left join master.ref_pil_category rpc on ep.ref_pil_category_id=rpc.id
//        left join master.ref_state rs on ep.ref_state_id=rs.id
//        left join master.users u on ep.adm_updated_by=u.usercode
//        where date(petition_date) between ? and ? order by petition_date";
        }

//        $query=$this->db->getLastQuery();
//        echo (string) $query;exit();

        $result = $query->getResultArray();
//        echo "<pre>";
//        print_r($result);die;
        if ($query->getNumRows() >= 1) {
//            echo "<pre>";
//            print_r($result);
//            die;
            return $result;
        } else {
            return false;
        }


    }

    public function getUserWorkDone($fromDate, $toDate, $reportType)
    {
        $queryString = "";
        if ($reportType == "C") {

            $builder = $this->db->table('ec_pil ep');
            $builder->select('ep.adm_updated_by,date(ep.updated_on) ,u.empid,u.name,count(*) total_cases');
            $builder->join('master.users u', 'ep.adm_updated_by=u.usercode', 'INNER');
            $builder->where("date(ep.updated_on) between '$fromDate' and '$toDate'");
            $builder->groupBy('ep.adm_updated_by,date(ep.updated_on),u.empid,u.name');
            $builder->orderBy('date(ep.updated_on),u.name');
            $query = $builder->get();
        }
//        BELOW PART IS NOT USED AND WRONG ALSO IN OLD CODE
//        elseif ($reportType=="U"){
//            $builder = $this->db->table('ec_pil ep');
//            $builder->select('ep.*,rs.state_name,concat(ep.diary_number,'/',ep.diary_year) as pil_diary_number,rpat.pil_sub_action_code,rpat.sub_action_description,
//                             rpc.pil_code,rpc.pil_category');
//            $builder->join('ref_pil_action_taken rpat', 'ep.ref_action_taken_id=rpat.id','LEFT');
//            $builder->join('ref_pil_category rpc', 'ep.ref_pil_category_id=rpc.id','LEFT');
//            $builder->join('ref_state rs', 'ep.ref_state_id=rs.id','LEFT');
//            $builder->where("date(updated_on) between '$fromDate' and '$toDate'");
//            $builder->where("ep.adm_updated_by");
//            $builder->groupBy('ep.adm_updated_by,date(ep.updated_on),u.empid,u.name');
//            $builder->orderBy('date(ep.updated_on),u.name');
//            $query = $builder->get();
//
//            $queryString="select ep.*,rs.state_name,concat(ep.diary_number,'/',ep.diary_year) as pil_diary_number,
//        rpat.pil_sub_action_code,rpat.sub_action_description,rpc.pil_code,rpc.pil_category
//        from ec_pil ep
//        left join ref_pil_action_taken rpat on ep.ref_action_taken_id=rpat.id
//        left join ref_pil_category rpc on ep.ref_pil_category_id=rpc.id
//        left join ref_state rs on ep.ref_state_id=rs.id
//        where date(updated_on) between ? and ? and ep.adm_updated_by=?
//        order by ep.diary_year desc ,ep.diary_number desc";
//            $query = $this->db->query($queryString,array($fromDate,$toDate,$updatedBy));
//        }
//                $query=$this->db->getLastQuery();
//        echo (string) $query;exit();
        $result = $query->getResultArray();

        if ($query->getNumRows() >= 1) {
//            echo "<pre>";
//            print_r($result);
//            die;
            return $result;
        } else {
//            echo "RR";
            return "false";
        }
    }

    public function getWorkDone($dated, $updatedBy)
    {

        $builder = $this->db->table('ec_pil ep');
        $builder->select('ep.*,rs.state_name,concat(ep.diary_number,\'/\',ep.diary_year) as pil_diary_number,
        rpat.pil_sub_action_code,rpat.sub_action_description,rpc.pil_code,rpc.pil_category,
        u.name as username,u.empid');
        $builder->join('master.ref_pil_action_taken rpat', 'ep.ref_action_taken_id=rpat.id', 'LEFT');
        $builder->join('master.ref_pil_category rpc', 'ep.ref_pil_category_id=rpc.id', 'LEFT');
        $builder->join('master.ref_state rs', 'ep.ref_state_id=rs.id', 'LEFT');
        $builder->join('master.users u', 'ep.adm_updated_by=u.usercode', 'LEFT');
        $builder->where("date(ep.updated_on)", $dated);
        $builder->where("ep.adm_updated_by", $updatedBy);
        $builder->orderBy('ep.updated_on');
        $query = $builder->get();
//
//                $query=$this->db->getLastQuery();
//        echo (string) $query;exit();
        $result = $query->getResultArray();

        if ($query->getNumRows() >= 1) {
//            echo "<pre>";
//            print_r($result);
//            die;
            return $result;
        } else {
//            echo "RR";
            return "false";
        }


//        $queryString="select ep.*,rs.state_name,concat(ep.diary_number,'/',ep.diary_year) as pil_diary_number,
//        rpat.pil_sub_action_code,rpat.sub_action_description,rpc.pil_code,rpc.pil_category,
//        u.name as username,u.empid
//        from ec_pil ep
//        left join ref_pil_action_taken rpat on ep.ref_action_taken_id=rpat.id
//        left join ref_pil_category rpc on ep.ref_pil_category_id=rpc.id
//        left join ref_state rs on ep.ref_state_id=rs.id
//        left join users u on ep.adm_updated_by=u.usercode
//        where date(ep.updated_on) =? and ep.adm_updated_by=? order by ep.updated_on";
//        $query = $this->db->query($queryString,array($dated,$updatedBy));
//
//        return $query->result_array();
    }
    function savePilData($query,$ecPilId){
        return $this->db->query($query);
    }

    
    public function getLastDiaryNumber($diaryYear)
    {
        // Ensure you're using the correct table and query syntax for CI4
        $builder = $this->db->table('ec_pil'); // Set the table name
    
        // Query to get the maximum diary_number for the given year
        $builder->selectMax('diary_number');   // Select the maximum value of 'diary_number'
        $builder->where('diary_year', $diaryYear); // Add the condition on 'diary_year'
    
        // Execute the query and get the result
        $query = $builder->get();
        $result = $query->getRow(); // Get the first row of the result
    
        // Return the result (diary_number)
        return $result ? $result->diary_number : null; // Handle case where no row is returned
    }
    public function getQueryPilData($columnName, $text)
    {
//        echo $columnName.">>",$text;
//        die;
//    $db = \Config\Database::connect();

        if (!empty($columnName) && !empty($text)) {
            $builder = $this->db->table('ec_pil');
            $builder->select('ec_pil.*, concat(ec_pil.diary_number, \'/\', ec_pil.diary_year) as pil_diary_number,u.name as username,u.empid');
            $builder->join('master.ref_pil_action_taken rpat', 'ec_pil.ref_action_taken_id = rpat.id', 'left');
            $builder->join('master.ref_pil_category rpc', 'ec_pil.ref_pil_category_id = rpc.id', 'left');
            $builder->join('master.ref_state rs', 'ec_pil.ref_state_id = rs.id', 'left');
            $builder->join('master.users u', 'ec_pil.adm_updated_by = u.usercode', 'left');

            if ($columnName === 'd' || $columnName === 'm') {
                if ($columnName === 'd')
                {
                    $dy = substr($text,-4);
                    $dn = substr($text, 0,-4);
//                    echo $dn.">>".$dy;die;
                    $builder->where('ec_pil.diary_number ', $dn)->where('ec_pil.diary_year',$dy);
                } else{
                    $builder->where('ec_pil.mobile', $text);
                }


            } else {
                if ($columnName === 'n')
                    $builder->like("LOWER(ec_pil.received_from)", strtolower($text));
                elseif ($columnName === 'a')
                    $builder->like('LOWER(ec_pil.address)', strtolower($text));
                elseif ($columnName === 'e')
                    $builder->like('LOWER(ec_pil.email)', strtolower($text));

            }
            $builder->orderBy('ec_pil.diary_year', 'desc');
            $builder->orderBy('ec_pil.diary_number', 'desc');
            $query = $builder->get();
//        $query=$this->db->getLastQuery();echo (string) $query."<br>";exit;
            $result = $query->getResultArray();
//        echo "<pre>";
//        print_r($result);die;
            return $result;
        } else {
            return 0;
        }
    }

}


?>