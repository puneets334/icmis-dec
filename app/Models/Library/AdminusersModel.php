<?php
namespace App\Models\Library;
use CodeIgniter\Model;
class AdminusersModel extends Model{
    
    private $db_table = "admin";

    // Columns
    public $id;
    public $FullName;
    public $AdminEmail;
    public $UserName;
    public $Password;
    public $updationDate;
    public $user_type;
    public $role_id;
    public $phone_number;
    public $alternative_phone_no;
    public $created_on;  
    public $status;    

    public function __construct(){
        parent::__construct();
        $this->db = db_connect();         
    }

    // GET ALL
    public function getAdminUsers(){
        $sqlQuery = "SELECT * FROM ".$this->db_table."  ORDER BY  id DESC";
       $stmt = $this->db->query($sqlQuery);
       //$stmt->execute();
       return $stmt->getResultArray();
   }


  
   public function UniqueNameCheck($username){
       $sqlQuery = "SELECT count(*) as cntUser FROM ".$this->db_table."  Where username=:username ";            
       $stmt = $this->db->query($sqlQuery);
      // $stmt->bindParam('username', $this->username);
       //$stmt->execute();
       return $count = $stmt->getNumRows();
   }



   public function insertData()
   {
       $created_on=date("Y-m-d H:i:s");
       $query = "INSERT INTO  " . $this->db_table . " SET FullName='$this->FullName',AdminEmail='$this->AdminEmail',UserName='$this->UserName',Password='$this->Password',role_id='$this->role_id', user_type='$this->user_type',created_on='$created_on',phone_number='$this->phone_number',alternative_phone_no='$this->alternative_phone_no'";
       $stmt = $this->db->query($query);

       $stmt->execute();
       return $stmt;
   }
   public function updatea()
   {
       $query = "update   " . $this->db_table . " SET "
               . "FullName='$this->FullName',"
               . "AdminEmail='$this->AdminEmail',"
               . "role_id='$this->role_id',"
               . "phone_number='$this->phone_number',"
               . "alternative_phone_no='$this->alternative_phone_no',"
               . " user_type='$this->user_type' where id='$this->id' ";
       $stmt = $this->db->query($query);
       $stmt->execute();
       return $stmt;
   }
   public function updatePass(){
       $query = "update   " . $this->db_table . " SET "
               . " Password='$this->Password' where id='$this->id' ";
       $stmt = $this->db->query($query);
       $stmt->execute();
       return $stmt;
   }
   
   public function existingUsername($username){
        $query = "SELECT  *  FROM " . $this->db_table . " WHERE username = '$username' "; 
       $stmt = $this->db->query($query);
       //$stmt->execute();
       return $stmt->getRowArray();
   }
   public function deletea(){
       $query = "delete from " . $this->db_table . "  where id='$this->id'";
       $stmt = $this->db->query($query);
       $stmt->execute();
       return $stmt;
   }
   public function getDataForEdit() {
       $query = "select * from " . $this->db_table . "  where id='$this->id'";
       $stmt = $this->db->query($query);
       //$stmt->execute();
       return $stmt->getRowArray();
   }
   public function userStatus() {
       $query = "update   " . $this->db_table . " SET "
               . "status='$this->status' where id='$this->id' ";
       $stmt = $this->db->query($query);
       //$stmt->execute();
       return $stmt->getRowArray();
   }


   public function getRosterData($dtd, $judge_code = '')
{
    $sql = "
        SELECT 
            r.id,
            (
                SELECT STRING_AGG(jsub.jcode::TEXT, ',' ORDER BY jsub.judge_seniority)
                FROM (
                    SELECT DISTINCT j.jcode, j.judge_seniority
                    FROM master.judge j
                    JOIN master.roster_judge rj2 ON j.jcode = rj2.judge_id
                    WHERE rj2.roster_id = r.id
                ) AS jsub
            ) AS jcd,
            (
                SELECT STRING_AGG(jsub.jname, ', ' ORDER BY jsub.judge_seniority)
                FROM (
                    SELECT DISTINCT j.jname, j.judge_seniority
                    FROM master.judge j
                    JOIN master.roster_judge rj2 ON j.jcode = rj2.judge_id
                    WHERE rj2.roster_id = r.id
                ) AS jsub
            ) AS jnm,
            j.first_name,
            j.sur_name,
            j.title,
            r.courtno,
            rb.bench_no,
            mb.abbr,
            mb.board_type_mb,
            r.tot_cases,
            r.frm_time,
            r.session
        FROM master.roster r 
        LEFT JOIN master.roster_bench rb ON rb.id = r.bench_id 
        LEFT JOIN master.master_bench mb ON mb.id = rb.bench_id
        LEFT JOIN master.roster_judge rj ON rj.roster_id = r.id 
        LEFT JOIN master.judge j ON j.jcode = rj.judge_id
        LEFT JOIN cl_printed cp 
            ON cp.next_dt = ? 
            AND cp.roster_id = r.id 
            AND cp.display = 'Y'
        WHERE 
            cp.next_dt IS NOT NULL
            AND j.is_retired <> 'Y' 
            AND j.display = 'Y'
            AND rj.display = 'Y'
            AND rb.display = 'Y'
            AND mb.display = 'Y'
            AND r.display = 'Y'
            $judge_code
        GROUP BY r.id, j.first_name, j.sur_name, j.title, r.courtno, rb.bench_no, mb.abbr, mb.board_type_mb, r.tot_cases, r.frm_time, r.session
        ORDER BY r.id
    ";

    $query = $this->db->query($sql, [$dtd]);
    return $query->getRowArray();
}


public function getRosterJudgeData($t_cn = '')
{
    $sql = "
        SELECT * FROM (
            SELECT 
                rj.roster_id, 
                mb.board_type_mb,
                r.courtno,
                rj.judge_id
            FROM master.roster_judge rj 
            JOIN master.roster r ON rj.roster_id = r.id 
            JOIN master.roster_bench rb ON rb.id = r.bench_id AND rb.display = 'Y'
            JOIN master.master_bench mb ON mb.id = rb.bench_id AND mb.display = 'Y'
            WHERE r.m_f IN ('1', '2')
                AND rj.display = 'Y' 
                AND r.display = 'Y'
                $t_cn
        ) AS sub
        ORDER BY 
            CASE WHEN sub.courtno = 0 THEN 9999 ELSE sub.courtno END,
            CASE 
                WHEN sub.board_type_mb = 'J' THEN 1
                WHEN sub.board_type_mb = 'S' THEN 2
                WHEN sub.board_type_mb = 'C' THEN 3
                WHEN sub.board_type_mb = 'CC' THEN 4
                WHEN sub.board_type_mb = 'R' THEN 5
                ELSE 6
            END,
            sub.judge_id
    ";

    // Uncomment this to debug
    // echo $sql; exit;

    $query = $this->db->query($sql);
    return $query->getResultArray();
}



public function getCaseBoardList($tdt1, $result, $whereStatus = '')
{
    $sql = "
    SELECT 
        LEFT(m.diary_no::TEXT, LENGTH(m.diary_no::TEXT) - 4) AS case_no,
        RIGHT(m.diary_no::TEXT, 4) AS year,  
        m.diary_no,
        m.reg_no_display,    
        m.conn_key,   
        h.mainhead,
        h.judges,
        h.board_type,
        h.next_dt,   
        h.clno,
        h.brd_slno,
        m.pet_name,
        m.res_name,
        m.c_status,
        CASE
            WHEN cl.next_dt IS NULL THEN 'NA'
            ELSE h.brd_slno::text
        END AS brd_prnt,
        h.roster_id,    
        m.casetype_id,
        m.case_status_id,
        c.short_description,
        h.list_status
    FROM (
        SELECT 
            t1.diary_no,
            t1.next_dt,
            t1.judges,
            t1.roster_id,  
            t1.mainhead,
            t1.board_type,
            t1.clno,
            t1.brd_slno,  
            t1.main_supp_flag,
            'Heardt' AS list_status
        FROM heardt t1 
        WHERE 
            t1.next_dt = ? 
            AND t1.mainhead IN ('M', 'F')
            AND t1.roster_id = ANY(string_to_array(?, ',')::int[])
            AND t1.main_supp_flag IN (1, 2)

        UNION

        SELECT 
            t2.diary_no,
            t2.next_dt,
            t2.judges,
            t2.roster_id,  
            t2.mainhead,
            t2.board_type,
            t2.clno,
            t2.brd_slno,  
            t2.main_supp_flag,
            'Last_Heardt' AS list_status
        FROM last_heardt t2 
        WHERE 
            t2.next_dt = ? 
            AND t2.mainhead IN ('M', 'F')
            AND t2.roster_id = ANY(string_to_array(?, ',')::int[])
            AND t2.main_supp_flag IN (1, 2)
            AND (t2.bench_flag = '' OR t2.bench_flag IS NULL)

        UNION  

        SELECT 
            t3.diary_no,
            t3.cl_date AS next_dt,
            'Judges' AS judges,
            t3.roster_id,  
            t3.mf AS mainhead,
            'Board_Type' AS board_type,  
            t3.part AS clno,
            t3.clno AS brd_slno, 
            NULL AS main_supp_flag,
            'DELETED' AS list_status
        FROM drop_note t3 
        WHERE 
            t3.cl_date = ? 
            AND t3.mf IN ('M', 'F')
            AND t3.roster_id = ANY(string_to_array(?, ',')::int[])
    ) h
    INNER JOIN main m ON h.diary_no = m.diary_no   
    LEFT JOIN cl_printed cl 
        ON cl.next_dt = h.next_dt 
        AND cl.m_f = h.mainhead 
        AND cl.part = h.clno
        AND cl.roster_id = h.roster_id 
        AND cl.display = 'Y'
    LEFT JOIN master.casetype c ON m.casetype_id = c.casecode
    LEFT JOIN conct ct ON m.diary_no = ct.diary_no AND ct.list = 'Y'  
    WHERE cl.next_dt IS NOT NULL $whereStatus
    GROUP BY 
        m.diary_no, m.reg_no_display, m.conn_key, h.mainhead, h.judges, h.board_type, 
        h.next_dt, h.clno, h.brd_slno, m.pet_name, m.res_name, m.c_status, cl.next_dt, 
        h.roster_id, m.casetype_id, m.case_status_id, c.short_description, h.list_status
    ORDER BY 
        CASE WHEN cl.next_dt IS NULL THEN 2 ELSE 1 END,
        h.brd_slno,
        CASE
            WHEN m.conn_key IS NULL OR NULLIF(m.conn_key, '') IS NULL THEN '99'
            WHEN COALESCE(NULLIF(m.conn_key, '')::BIGINT, 0) = m.diary_no THEN ''
            ELSE '99'
        END ASC,
        CAST(SUBSTRING(m.diary_no::text FROM LENGTH(m.diary_no::text) - 3) AS INTEGER) ASC,
        CAST(SUBSTRING(m.diary_no::text FROM 1 FOR LENGTH(m.diary_no::text) - 4) AS INTEGER) ASC
 
    ";

    $query = $this->db->query($sql, [$tdt1, $result, $tdt1, $result, $tdt1, $result]);
    return $query->getResultArray();
}



public function getEservicesData($diary_no, $list_dt)
{
    // Connect to the e_services database (remote)
    $dbeservices = \Config\Database::connect('eservices');

    // Fetch from the remote library_reference_material table
    $libData = $dbeservices->table('library_reference_material')
        ->where('list_date', $list_dt)
        ->where('diary_no', $diary_no)
        ->where('is_active', 1)
        ->get()
        ->getResultArray();
    //pr($libData);
    // Enrich each row with local bar data
    foreach ($libData as &$row) {
        $bar = $this->db->table('master.bar')
            ->select('title, name')
            ->where('aor_code', $row['aor_code'])
            ->get()
            ->getRowArray();

        $row['title'] = $bar['title'] ?? '';
        $row['name'] = $bar['name'] ?? '';
    }

    // ✅ Return full data array
    return $libData;
}


public function getLibraryReferenceMaterialChild($reference_material_id)
{
    $deservicesb = \Config\Database::connect('eservices'); // use correct DB group if needed

    $builder = $deservicesb->table('library_referance_material_child a');
    $builder->select('a.id, a.header_details, a.file_name, a.icmis_file_name, b.name_of_header');
    $builder->join('library_master_headers b', 'b.id = a.library_master_headers_id');
    $builder->where('a.library_reference_material_id', $reference_material_id);
    $builder->where('a.is_active', 1);
    $builder->orderBy('a.id');

    $query = $builder->get();
    return $query->getResultArray();
}


public function getCourtRequisition($diary_no, $list_dt)
{
    $db = \Config\Database::connect(); // or use a specific connection if needed

    $builder = $db->table('tbl_court_requisition a');
    $builder->select('a.*, b.file_path');
    $builder->join('requistion_upload b', 'b.req_id = a.id', 'left');
    $builder->where('a.diary_no', $diary_no);
    $builder->where('a.itemdate', $list_dt);

    $query = $builder->get();
    return $query->getResultArray();
}











}