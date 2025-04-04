<?php
namespace App\Models\Library;
use CodeIgniter\Model;
class RequisitionModel extends Model{
    
    private $db_table = "tbl_court_requisition";
    private $tables = "tbl_requisition_interactions";
    private $adv_table = "advocate_requisition_request";

    // Columns
    public $id;
    public $court_number;
    public $court_userName;
    public $remark1;
    public $remark2;
    public $current_status;
    public $section;
    public $created_on;
    public $created_by;
    public $updated_on;
    public $updated_by;
    public $user_ip;
    public $file;
    public $file_type;
    public $file_text;
    public $file_name;
    public $itemNo;
    public $itemDate;
    public $interaction_ip;
    public $requisition_id;
    public $interaction_remarks;
    public $interaction_status;
    public $user_type;
    public $phoneNo;
    public $urgent;
    public $court_bench;
    public $sortorder;
    public $diary_no;

    public $advocate_name;
    public $appearing_for;
    public $party_serial_no;
    public $role_id;

    public function __construct(){
        parent::__construct();
        $this->db = db_connect();         
    }
 

    public function create($data){

        // $this->remark1=addslashes($this->remark1);
        // if($this->user_type=="") $this->user_type=1;
        // $query = "INSERT INTO  " . $this->db_table . " SET "
        //     . "court_number='$this->court_number',"
        //     . "court_userName='$this->court_userName',"
        //     . "remark1='$this->remark1',"
        //     . "court_bench='$this->court_bench',"
        //     . "urgent='$this->urgent',"
        //     . "section='$this->section',"
        //     . "request_file='$this->file',"
        //     . "itemNo='$this->itemNo',"
        //     . "itemDate='$this->itemDate',"
        //     . "alternate_number='$this->phoneNo',"
        //     . "user_type='$this->user_type',"
        //     . "created_by='$this->created_by',"
        //     . "user_ip='$this->user_ip',"
        //     . "diary_no='$this->diary_no',"
        //     . "advocate_name='$this->advocate_name',"
        //     . "appearing_for='$this->appearing_for',"
        //     . "party_serial_no='$this->party_serial_no'";
        

        $this->db->table($this->db_table)->insert($data);   
        //$stmt = $this->conn->prepare($query);
        //$stmt->execute();
        //$stmt = $this->conn->lastInsertId();
        $stmt = $this->db->insertID();
        return $stmt;
    }


    public function Insert_Interaction($data)
    {
        // $this->interaction_remarks=htmlspecialchars(strip_tags($this->interaction_remarks));
        // $this->interaction_ip=$_SERVER['REMOTE_ADDR'];
        // $query = "INSERT INTO  " . $this->table . " SET "
        //     . "requisition_id='$this->requisition_id',"
        //     . "interaction_status='$this->interaction_status',"
        //     . "interaction_remarks='$this->interaction_remarks',"
        //     . "request_file='$this->file',"
        //     . "itemNo='$this->itemNo',"
        //     . "itemDate='$this->itemDate',"
        //     . "interaction_ip='$this->interaction_ip',"
        //     . "created_by='$this->created_by'
        //          ";
        // $stmt = $this->conn->prepare($query);
        // $stmt->execute();
       return  $this->db->table($this->tables)->insert($data);  
        

    }


    public function update_requistion($requisition_id,$updateData)
    {
        $this->db->table($this->tables)->where('id',$requisition_id)->update($updateData);  
    }

    public function unread_AOR_pending_interaction($role_id)
    {
        $datetoday = date("Y-m-d");

        $sqlQuery = "SELECT cr.id, cr.current_status, a.UserName, a.role_id, cr.itemDate  
                    FROM " . $this->db->escapeIdentifiers($this->db_table) . " AS cr  
                    LEFT JOIN admin AS a ON cr.updated_by = a.UserName
                    WHERE current_status = 'Interaction' 
                    AND current_status NOT IN ('closed', 'pending', 'cancel') 
                    AND DATE(itemDate) >= ?
                    AND cr.user_type = '2' 
                    AND a.role_id != ?";

        $query = $this->db->query($sqlQuery, [$datetoday, $role_id]);

        return $query->getNumRows(); // ✅ Correct CodeIgniter 4 method
    }

    public function dropdownItemDates(){
        $sqlQuery = "SELECT c.next_dt FROM cl_printed c where c.next_dt >= current_date and c.display = 'Y' group by c.next_dt order by c.next_dt;";
        $stmt = $this->db->query($sqlQuery);
        
        return $stmt->getResultArray();
    }

    public function view_library_section()
    {
        $sqlQuery = "SELECT *  FROM  tbl_library_section WHERE status=1 ORDER BY library_section_name ASC";
        $stmt = $this->db->query($sqlQuery);
        
        return $stmt->getResultArray();
    }

    public function read($court_number){


        $todayDate=date("Y-m-d");
        //AND current_status NOT IN('cancel','closed','received' )//AND court_userName='$this->court_userName'
        $query = "SELECT *  FROM " . $this->db_table . " where user_type='1' AND DATE(created_on)='$todayDate'      AND court_number='$court_number' 
                      ORDER BY CASE current_status
                      WHEN 'Pending' then 10
                      WHEN 'attending' then 20
                      WHEN 'Interaction' then 30
                      WHEN 'Sent' then 40
                      WHEN 'received' then 50
                      WHEN 'cancel' then 60
                      WHEN 'closed' then 70
                      ELSE 100 end ;
            ";
        $stmt = $this->db->query($query);
        return $stmt->getResultArray();
    }


    public function view_requistion_interactions($requisition_id,$sortorder='')
    {

        if($sortorder!="asc")
        {
            $this->sortorder= " DESC";
        }else{
            $this->sortorder= " ASC";
        }

        $sqlQuery = "SELECT *  FROM  ". $this->table ." WHERE requisition_id = '$requisition_id' ORDER BY id  $this->sortorder ";
        $stmt = $this->db->query($sqlQuery);
       // $stmt->bindParam(1, $this->requisition_id);
       return $stmt->getNumRows();
    }

    public function view_requistion_interactions_results($requisition_id,$sortorder='')
    {

        if($sortorder!="asc")
        {
            $this->sortorder= " DESC";
        }else{
            $this->sortorder= " ASC";
        }

        $sqlQuery = "SELECT *  FROM  ". $this->table ." WHERE requisition_id = '$requisition_id' ORDER BY id  $this->sortorder ";
        $stmt = $this->db->query($sqlQuery);
       // $stmt->bindParam(1, $this->requisition_id);
       return $stmt->getResultArray();
    }


    public function count_Interaction_read_assitant($requisition_id,$created_by)
    {
        $sqlQuery = "SELECT *  FROM  ". $this->table ." WHERE read_status=0 AND requisition_id='$requisition_id' AND created_by !='$created_by' ORDER BY id DESC";
        $stmt = $this->db->query($sqlQuery);        
        return  $stmt->getNumRows();
    }

    public function viewRequistionRequest($id){
        $sqlQuery = "SELECT *  FROM  ". $this->db_table ." WHERE id = $id ORDER BY id DESC LIMIT 0,1 ";
        $stmt = $this->db->query($sqlQuery);
        return $stmt->getRowArray();
    }

    public function view_today_ReqAdminData()
    {
        $todayDate=date("Y-m-d");
        $query="select * from ". $this->db_table ." where  user_type='1' AND DATE(created_on)='$todayDate' AND created_on <=(NOW() - INTERVAL 10 MINUTE) AND current_status IN('pending','Interaction','attending') ORDER BY id DESC";
        $stmt = $this->db->query($query);
        return $stmt->getNumRows();
    }


    public function getCaseNo($item_no,$court_no,$dateitem)
    {
        $sqlQuery = "SELECT m.diary_no, m.reg_no_display, m.pet_name, m.res_name, h.next_dt FROM main m
                        inner join heardt h on h.diary_no = m.diary_no
                        inner join master.roster r on r.id = h.roster_id 
                        inner join cl_printed c on c.next_dt = h.next_dt and c.part = h.clno and c.roster_id = h.roster_id and c.display = 'Y'
                        WHERE (m.diary_no = m.conn_key::BIGINT OR m.conn_key IS NULL OR m.conn_key = '' OR m.conn_key = '0')
                        and h.next_dt = '".$dateitem."' and clno > 0 and brd_slno = ".$item_no."
                        and r.courtno = ".$court_no."
                        group by m.diary_no,m.reg_no_display, m.pet_name, m.res_name, h.next_dt";
        $stmt = $this->db->query($sqlQuery);
        //$stmt->bindParam(1, $this->req_id);
        //$stmt->execute();
        return $stmt->getRowArray();
    }

    public function view_today_RequisitionData()
    {
        $todayDate=date("Y-m-d");
        $query="select * from ". $this->db_table ." where DATE(itemDate) >= '$todayDate'  AND current_status IN('pending') ORDER BY id DESC";
        $stmt = $this->db->query($query);
        return $stmt->getResultArray();
    }
    
    public function count_Interaction_read_libraian($requisition_id,$created_by)
    {
        $sqlQuery = "SELECT *  FROM  ". $this->table ." WHERE read_status_librarian=0 AND requisition_id='$requisition_id' AND created_by !='$created_by' ORDER BY id DESC";
        $stmt = $this->db->query($sqlQuery);
        return $stmt->getNumRows();
    }

    public function count_Interaction_By_Admin($requisition_id)
    {

        $sqlQuery="select r.requisition_id,r.read_status_librarian ,admin.UserName,admin.role_id from tbl_requisition_interactions As r  LEFT JOIN admin ON r.created_by=admin.UserName  where requisition_id='$requisition_id' AND role_id=6 AND read_status_librarian=0";
        $stmt = $this->db->query($sqlQuery);
        return $stmt->getNumRows();
    }


    public function view_requistion_status_cnt($current_status)
    {
        $todayDate=date("Y-m-d");
        $sqlQuery = "SELECT *  FROM  " . $this->db_table . "  WHERE  user_type='1' AND current_status='$current_status' AND DATE(created_on)='$todayDate'  ORDER BY id DESC";
        $stmt = $this->db->query($sqlQuery);
        return $stmt->getNumRows();
    }

    public function advCitationData($data){

        return  $this->db->table($this->adv_table)->insert($data);  
    }

 

     
    


}