<?php

namespace App\Models\Copying;

use CodeIgniter\Model;

class CopyRequestModel extends Model
{
    protected $table = 'copying_request_verify a';
    protected $primaryKey = 'id';
    protected $eservicesdb;
    public function __construct(){
        parent::__construct();
        $this->eservicesdb = \Config\Database::connect('eservices');
    }
    public function getRequests($request)
    {
        $session = session();
        $builder = $this->db->table('copying_request_verify a');
        $builder->select('tentative_section(m.diary_no::text) AS tentative_section, m.reg_no_display, m.c_status, a.*');
        $builder->join('main m', 'm.diary_no = a.diary', 'left');

        // Add conditions based on the previous logic
        if ($request->getPost('copy_status') == 'P') {
            $builder->where('a.application_status', 'P');
        } elseif ($request->getPost('copy_status') == 'D') {
            $builder->where('a.application_status !=', 'P');
            $from_date = date("Y-m-d", strtotime($request->getPost('from_date')));
            $to_date = date("Y-m-d", strtotime($request->getPost('to_date')));
            $builder->where('DATE(a.updated_on) BETWEEN', [$from_date, $to_date]);
        } else {
            return redirect()->back()->with('error', 'Select Request Status');
        }

        // Additional conditions
        if ($request->getPost('isda') == 'Y') {
            if (in_array($session->get('dcmis_usertype'), [17, 50, 51])) {
                $builder->where('m.dacode', $session->get('dcmis_user_idd'));
            } else {
                $session_multi_section = "'" . implode("', '", $session->get('dcmis_multi_section_name')) . "'";
                $builder->where("tentative_section(m.diary_no) IN ($session_multi_section)");
            }
        }

        // Concerned section documents
        $concerned_section_docs = implode(",", $request->getPost('usersection'));
        //$builder->where("b.current_section IN ($concerned_section_docs)");

        // Applicant type
        if ($request->getPost('applicant_type') != 'all' && $request->getPost('applicant_type') != '') {
            $applicant_type = implode(",", $request->getPost('applicant_type'));
            $builder->where("a.filed_by IN ($applicant_type)");
        }
        // Execute the query
        $query = $builder->orderBy('a.application_receipt')->get();
        return $query->getResultArray();
    }
    public function getcopying_request_verify_documents($id){
        $builder = $this->db->table('public.copying_request_verify_documents b');
        $builder->select('r.order_type AS order_name, b.*');
        $builder->join('master.ref_order_type r', 'b.order_type = r.id');
        $builder->where('b.copying_order_issuing_application_id',$id);
        
        // If $concerned_section_docs is a condition, you can add it here
        if (!empty($concerned_section_docs)) {
            $builder->where($concerned_section_docs);
        }
        //echo $builder->getCompiledSelect();
        //die;
        // Execute the query
        $result1 = $builder->get();
        
        // Fetch the results
        $data = $result1->getResultArray();
        return $data;
    }
public function getUserAssets($row){
      // Prepare the mobile and email values
$mobile = $row['mobile'];
$email = $row['email'];

// Define the asset types to query
$asset_types = [1, 2, 3];

// Initialize an array to hold the results
$results = [];

// Create a subquery for each asset type
$subqueries = [];
   foreach ($asset_types as $asset_type) {
    $subquery = $this->eservicesdb->table('user_assets u')
        ->select('u.id, u.asset_type, a.asset_name, u.id_proof_type, i.id_name, u.file_path, u.verify_status, u.verify_on, u.video_random_text, u.verify_remark')
        ->join('user_asset_type_master a', 'a.id = u.asset_type')
        ->join('id_proof_master i', 'i.id = u.id_proof_type', 'left')
        ->where('u.mobile', $mobile)
        ->where('i.display','Y')
        ->where('u.email', $email)
        ->where('u.asset_type', $asset_type)
        ->where('u.diary_no', 0)
        ->orderBy('u.ent_time', 'DESC')
        ->limit(1)
        ->getCompiledSelect(); // Get the compiled select query

    $subqueries[] = "($subquery)"; // Add the subquery to the array
       }

// Combine the subqueries using UNION
$sql_asset = implode(' UNION ', $subqueries);

// Execute the final query
return $finalResult = $this->eservicesdb->query($sql_asset)->getResultArray();  
 }
public function getUserAssetsWithRelation($row){
$mobile = $row['mobile'];
$email = $row['email'];
if($row['filed_by'] == 2){
    $asset_type_flag = 5;//party
}
if($row['filed_by'] == 3){
    $asset_type_flag = 6;//appearing counsel
}
if($row['filed_by'] == 4){
    $asset_type_flag = 4;//affidavit
} // Assuming this is already defined
$diary_no = $row['diary'];

// Build the query using Query Builder
$builder = $this->eservicesdb->table('user_assets u');
$builder->select('u.id, u.asset_type, a.asset_name, u.id_proof_type, i.id_name, u.file_path, u.verify_status, u.verify_on, u.video_random_text, u.verify_remark');
$builder->join('user_asset_type_master a', 'a.id = u.asset_type');
$builder->join('id_proof_master i', 'i.id = u.id_proof_type', 'left');
$builder->where('u.mobile', $mobile);
$builder->where('i.display','Y');
$builder->where('u.email', $email);
$builder->where('u.asset_type', $asset_type_flag);
$builder->where('u.diary_no', $diary_no);
$builder->orderBy('u.ent_time', 'DESC');
$builder->limit(1);

// Execute the query
$query = $builder->get();
// Fetch the result as an associative array
$result = $query->getResultArray();
  }
public function getSectionDetailRecievedAndSent($row1){
$copying_request_verify_documents_id = $row1['id'];
// Build the main query
$builder = $this->db->table('public.copying_request_movement c');
$builder->select('c.from_section_sent_on, us.section_name');
$builder->join('master.usersection us', 'us.id = c.from_section', 'left');
$builder->where('c.copying_request_verify_documents_id', $copying_request_verify_documents_id);
$builder->where('c.display', 'Y');
$builder->where('c.from_section !=', 73);
$builder->where('c.to_section', 10);

// Subquery to get the maximum from_section_sent_on
$subquery = $this->db->table('copying_request_movement')
    ->select('MAX(from_section_sent_on) as max_sent_on')
    ->where('copying_request_verify_documents_id', $copying_request_verify_documents_id)
    ->where('display', 'Y');

// Add the subquery condition to the main query
$builder->where('c.from_section_sent_on = (' . $subquery->getCompiledSelect() . ')');

// Execute the query
$query = $builder->get();

// Fetch the result as an associative array
return $result = $query->getRowArray();
  }
  public function getDiaryDetail($row) {
    $diary_no = $row['diary'];

    // Build the query using Query Builder
    $builder = $this->db->table('main m');
    $builder->select([
        "SUBSTRING(m.diary_no::text FROM 1 FOR LENGTH(m.diary_no::text) - 4) AS d_no",
        "SUBSTRING(m.diary_no::text FROM LENGTH(m.diary_no::text) - 3 FOR 4) AS d_year",
        "m.pet_name",
        "m.res_name",
        "m.bench",
        "m.c_status",
        "mch.new_registration_number",
        "CASE 
            WHEN (m.reg_year_mh = 0 OR m.fil_dt > DATE '2017-05-10') 
            THEN EXTRACT(YEAR FROM m.fil_dt) 
            ELSE m.reg_year_mh 
        END AS case_year",
        "CASE 
            WHEN (mch.new_registration_number IS NOT NULL AND mch.new_registration_number != '0') 
            THEN SUBSTRING(mch.new_registration_number FROM '^[^-]+') 
            ELSE CASE 
                WHEN m.active_fil_no != '' 
                THEN SUBSTRING(m.active_fil_no FROM '^[^-]+') 
                ELSE '' 
            END 
        END AS casetype_id",
        "CASE 
            WHEN (mch.new_registration_number IS NOT NULL AND mch.new_registration_number != '0') 
            THEN SUBSTRING(mch.new_registration_number FROM '^[^-]+-([^-]+)') 
            ELSE '' 
        END AS case_no_from",
        "CASE 
            WHEN (mch.new_registration_number IS NOT NULL AND mch.new_registration_number != '0') 
            THEN SUBSTRING(mch.new_registration_number FROM '[^-]+$') 
            ELSE '' 
        END AS case_no_to",
        "TO_CHAR
        (mch.order_date, 'DD-MM-YYYY HH12:MI AM') AS fil_dt",
        "mch.new_registration_year"
    ]);
    $builder->join('main_casetype_history mch', 'CAST(m.diary_no AS TEXT)= CAST(mch.diary_no AS TEXT)', 'left');
    $builder->where('m.diary_no', (string)$diary_no);
    //echo $builder->getCompiledSelect();
    //die;
    // Uncomment the following line to see the compiled SQL query
    // echo $builder->getCompiledSelect();
    // die;

    // Execute the query
    $query = $builder->get();

    // Fetch the result as an associative array
    $result = $query->getResultArray();
    return $result;
}
public function request_accept_save($request){
       $session = session();
       $ucode = $session->get('dcmis_user_idd');
  
        $applicationId = $this->request->getPost('application_id');
        $crn = $this->request->getPost('crn');

        // Update the copying_request_verify_documents table
        $builder =$this->db->table('copying_request_verify_documents');
        $builder->set([
            'request_status' => 'D',
            'updated_on' => date('Y-m-d H:i:s'),
            'updated_by' => $ucode
        ]);
        $builder->where('path IS NOT NULL');
        $builder->where('request_status', 'P');
        $builder->where('id', $applicationId);
        $builder->update();

        // Check if any rows were affected
        if ($this->db->affectedRows() > 0) {
            // Check if there are any records in copying_request_verify
            $builder = $this->db->table('copying_request_verify a');
            $builder->select('a.crn');
            $builder->join('copying_request_verify_documents b', 'a.id = b.copying_order_issuing_application_id');
            $builder->where('b.request_status', 'P');
            $builder->where('a.crn', $crn);
            $builder->limit(1);
            $query = $builder->get();

            if ($query->getNumRows() == 0) {
                // Update the copying_request_verify table
                $builder = $this->db->table('copying_request_verify');
                $builder->set([
                    'application_status' => 'D',
                    'updated_on' => date('Y-m-d H:i:s'),
                    'adm_updated_by' => $ucode
                ]);
                $builder->where('application_status', 'P');
                $builder->where('crn', $crn);
                $builder->update();
            }

            return 1;
        } else {
            return 0;
        }
}
public function request_reject_save(){
        $session = session();
        $ucode = $session->get('dcmis_user_idd');

        // Get POST data
        $applicationId = $this->request->getPost('application_id');
        $crn = $this->request->getPost('crn');
        $rejectDetail = $this->request->getPost('copy_reject_detail');

        // Load the database connection
        $db = \Config\Database::connect();

        // Update the copying_request_verify_documents table
        $builder = $db->table('copying_request_verify_documents');
        $builder->set([
            'reject_cause' => $rejectDetail,
            'request_status' => 'F',
            'updated_on' => date('Y-m-d H:i:s'),
            'updated_by' => $ucode
        ]);
        $builder->where('request_status', 'P');
        $builder->where('id', $applicationId);
        $builder->update();

        // Check if any rows were affected
        if ($db->affectedRows() > 0) {
            // Check if there are any records in copying_request_verify
            $builder = $db->table('copying_request_verify a');
            $builder->select('a.crn');
            $builder->join('copying_request_verify_documents b', 'a.id = b.copying_order_issuing_application_id');
            $builder->where('b.request_status', 'P');
            $builder->where('a.crn', $crn);
            $builder->limit(1);
            $query = $builder->get();

            if ($query->getNumRows() == 0) {
                // Update the copying_request_verify table
                $builder = $db->table('copying_request_verify');
                $builder->set([
                    'application_status' => 'D',
                    'updated_on' => date('Y-m-d H:i:s'),
                    'adm_updated_by' => $ucode
                ]);
                $builder->where('application_status', 'P');
                $builder->where('crn', $crn);
                $builder->update();
            }

            echo 1; // Success
        } else {
            return 0;//Failed
        }

}
public function request_send_to_section(){
    $session = session();
    if ($session->get('dcmis_section')){
        $ucode = $session->get('dcmis_user_idd');
        $applicationId = $this->request->getPost('application_id');
        $rdtnSectionSendTo = $this->request->getPost('rdtn_section_send_to');
        $sectionRemark = $this->request->getPost('section_remark');

        // Initialize the database connection
        $db = \Config\Database::connect();
        $builder = $db->table('copying_request_movement');

        // Prepare data for inserting into copying_request_movement
        $data = [
            'copying_request_verify_documents_id' => $applicationId,
            'from_section' => $_SESSION['dcmis_section'],
            'from_section_sent_by' => $ucode,
            'from_section_sent_on' => date('Y-m-d H:i:s'),
            'to_section' => $rdtnSectionSendTo,
            'remark' => $sectionRemark
        ];

        // Insert into copying_request_movement
        if ($builder->insert($data)) {
            // Get the affected rows
            $afros = $db->affectedRows();

            if ($afros > 0) {
                // Prepare update data
                $updateData = [
                    'current_section' => $rdtnSectionSendTo,
                    'updated_on' => date('Y-m-d H:i:s'),
                    'updated_by' => $ucode
                ];

                // Update copying_request_verify_documents
                $builder = $db->table('copying_request_verify_documents b');
                $builder->join('copying_request_verify a', 'a.id = b.copying_order_issuing_application_id');
                $builder->set($updateData);
                $builder->where('b.request_status', 'P');
                $builder->where('b.id', $applicationId);
                $builder->update();

                return 1; // Success
            }
        }
    } else {
        return 0;
    }
}
 public function request_fee_clc_for_certification_save($application_id,$data){
    $db = \Config\Database::connect();

    // Update the copying_request_verify_documents table
    $builder = $db->table('public.copying_request_verify_documents');
    $builder->set($data);
    //$builder->where('request_status', 'P');
    $builder->where('id',$application_id);
    $builder->update();
    return $db->affectedRows();
}
public function getrequestedCertification($application_id){
    $builder = $this->db->table('public.copying_request_verify_documents');
    $builder->select('*');
    $builder->where('request_status', 'P');
    $builder->where('id',$application_id);
    $builder->limit(1);
    $query = $builder->get();
    return $query->getRowArray();
}
public function copying_reasons_for_rejection(){
    $db = \Config\Database::connect();

    // Use the Query Builder to select the data
    $builder = $db->table('copying_reasons_for_rejection');
    $builder->select('reasons, id');
    $builder->where('is_active', 'T');
    $builder->orderBy('reasons');
    
    // Execute the query and get the results
    $query = $builder->get();
    return $query->getResultArray();   
}
public function get_sections() {
    // Use Query Builder to build the query
    $db = \Config\Database::connect();
    $sec_list=array();
    // Use the Query Builder to select the data
    $builder = $db->table('usersection');
    $sec_list = $builder->select('id, section_name, display, isda')
                            ->where('display', 'Y')
                            ->orderBy('IF(id IN (10, 61), 1, 999)', 'ASC')
                            ->orderBy('IF(isda = "Y", 2, 999)', 'ASC')
                            ->orderBy('section_name', 'ASC')
                            ->get()
                            ->getResultArray();

        // Now $sec_list contains the result
        return $sec_list; // or pass it to a view, etc // or you can load a view and pass the data
}
public function getSectionByDiaryNo(){
         $diary_no = $this->request->getPost('diary_no');
         $db = \Config\Database::connect();
         $sec_list=array();
        // Build the custom query using Query Builder
        $builder = $db->table('usersection');

        // Execute the query
        $sec_data = $builder->select('id, section_name, display, isda')
                            ->where('section_name', "tentative_section($diary_no)", false) // Use false to prevent escaping
                            ->get()
                            ->getRowArray(); // Use getRowArray() to get a single row as an associative array

        // Check if data is found
        if ($sec_data) {
            $section_id = $sec_data['id'];
            $tentative_section_name = $sec_data['section_name'];
            // You can now use $section_id and $tentative_section_name as needed
        } else {
            // Handle the case where no data is found
            $section_id = null;
            $tentative_section_name = null;
        }

        // Return or process the data as needed
        return [
            'section_id' => $section_id,
            'tentative_section_name' => $tentative_section_name,
        ];
    
}
public function getSectionByEmpID(){
    // Get the employee ID from the session
    $empid = session()->get('icmic_empid');

    // Use Query Builder to construct the query
    $builder = $this->db->table('user_sec_map');
    $builder->select('us.section_name, us.isda');
    $builder->join('usersection us', 'us.id = user_sec_map.usec');
    $builder->where('user_sec_map.empid', $empid);
    $builder->where('user_sec_map.display', 'Y');
    $builder->where('us.isda', 'Y');

    // Execute the query
    $query = $builder->get();

    // Check if any rows were returned
    if ($query->getNumRows() > 0) {
        $row_sn = $query->getRowArray();
        return $row_sn['isda'];
    } else {
        return 'N';
    }
}

public function getCrmList(){
    $crn = $this->request->getPost('crn'); // Get the CRN from POST data
    // Build the query using Query Builder
    $builder = $this->db->table('copying_request_verify crv');
    $builder->select('r.order_type as order_name, crvd.order_date, 
        a.id, a.remark, a.from_section_sent_on, u.name as sended_by, 
        usf.section_name as from_section_name, ust.section_name as to_section_name, 
        crv.name, crv.crn, crv.diary');
    $builder->join('copying_request_verify_documents crvd', 'crv.id = crvd.copying_order_issuing_application_id');
    $builder->join('copying_request_movement a', 'crvd.id = a.copying_request_verify_documents_id');
    $builder->join('ref_order_type r', 'crvd.order_type = r.id');
    $builder->join('usersection usf', 'usf.id = a.from_section');
    $builder->join('usersection ust', 'ust.id = a.to_section');
    $builder->join('users u', 'u.usercode = a.from_section_sent_by');
    $builder->where('crv.crn', $crn);
    $builder->where('a.from_section_sent_by !=', 0);
    $builder->where('a.display', 'Y');
    $builder->orderBy('order_name');
    $builder->orderBy('order_date');
    $builder->orderBy('from_section_sent_on');
    // Execute the query
    $query = $builder->get();
    $rowcountCRM = $query->getNumRows();
    
    $crm_list = [];
    if ($rowcountCRM > 0) {
        $crm_list = $query->getResultArray(); // Fetch results as an array
    }
    return $crm_list;
    // Now you can use $crm_list as needed
}
public function getuploaded_previous_pdf_files(){
    // Load the database library
$crn = $this->request->getPost('crn'); // Get the 'crn' from POST data

// Use the Query Builder
$builder = $this->db->table('copying_request_verify_documents_log a');
$builder->select('r.order_type as order_name, a.order_date, a.id, a.path, a.created_on, u.name as created_by, crv.name, crv.crn, crv.diary');
$builder->join('copying_request_verify crv', 'crv.id = a.copying_order_issuing_application_id');
$builder->join('ref_order_type r', 'a.order_type = r.id');
$builder->join('users u', 'u.usercode = a.creaded_by');
$builder->where('crv.crn', $crn);
$builder->orderBy('a.created_on');

// Execute the query
$query = $builder->get();
$rowcountCRM = $query->getNumRows();

if ($rowcountCRM > 0) {
    $crm_list = $query->getResultArray(); // Fetch results as an array
} else {
    $crm_list = []; // No results found
}
return $crm_list;
// Now you can use $crm_list as needed
}
public function getPartyDetailsByDiaryNo($diary_no){
    $diary_no = $this->db->escape($diary_no); // Escape the variable to prevent SQL injection

    // Build the query using Query Builder
    $query = $this->db->table('party')
        ->select('partyname, addr1, addr2, sonof, prfhname, age, state, city, pet_res, sr_no')
        ->where('diary_no', $diary_no)
        ->where('pflag', 'P')
        ->orderBy('pet_res')
        ->orderBy('sr_no')
        ->get();
    
    // Get the result set
    $ctnt_tot = $query->getNumRows();
    
    // If you need to fetch the results as an array
    $results = $query->getResultArray();
    return $data=array('ctnt_tot'=>$ctnt_tot,'results'=>$results);
}
public function getPartyCount($diary_no,$pet_res){

$diary_no = $this->db->escape($diary_no); // Escape the variable to prevent SQL injection
// Build the query to count the records
$count = $this->db->table('party')
    ->where('diary_no', $diary_no)
    ->where('pet_res',$pet_res)
    ->where('pflag', 'P')
    ->countAllResults();
    return $count;
}
/*public function getDataList(){
    $condition = $this->request->getPost('diary_no');
     $OLD_ROP = $old_rop_db_name; // Assuming this is defined somewhere in your code

// Prepare the condition for the IN clause
$diary_no_array = explode(',', $condition);
$diary_no_array = array_map('trim', $diary_no_array); // Trim whitespace

// Start building the query
$db = \Config\Database::connect();
$builder = $db->table('(' . 
    'SELECT 
        vd.path AS pdfname, 
        vd.order_date AS orderdate, 
        0 AS s, 
        ot.order_type AS judgement_order, 
        ot.id AS judgement_order_code, 
        vd.order_type_remark,
        vd.fee_clc_for_certification_no_doc, 
        vd.fee_clc_for_certification_pages, 
        vd.fee_clc_for_uncertification_no_doc, 
        vd.fee_clc_for_uncertification_pages
    FROM 
        copying_request_verify u
    INNER JOIN 
        copying_request_verify_documents vd ON u.id = vd.copying_order_issuing_application_id
    INNER JOIN 
        ref_order_type ot ON ot.id = vd.order_type
    WHERE 
        u.diary IN (' . implode(',', array_map([$db->getConnection()->quote, $diary_no_array])) . ') 
        AND u.application_status != "P" 
        AND vd.request_status = "D" 
        AND vd.path != "" 
        AND vd.path IS NOT NULL 
        AND ot.is_deleted = "f" 
        AND u.allowed_request = "request_to_available" 

    UNION

    SELECT 
        pdfname, 
        orderdate, 
        "1" AS s, 
        CASE 
            WHEN type = "O" THEN "Record of Proceedings" 
            WHEN type = "J" THEN "Judgement" 
        END AS judgement_order,
        CASE 
            WHEN type = "O" THEN 1 
            WHEN type = "J" THEN 3 
        END AS judgement_order_code, 
        "" AS order_type_remark,
        "" AS fee_clc_for_certification_no_doc, 
        "" AS fee_clc_for_certification_pages, 
        "" AS fee_clc_for_uncertification_no_doc, 
        "" AS fee_clc_for_uncertification_pages
    FROM 
        ordernet 
    WHERE 
        diary_no IN (' . implode(',', array_map([$db->getConnection()->quote, $diary_no_array])) . ') 
        AND display = "Y" 

    UNION 

    SELECT 
        jm AS pdfname,
        dated AS orderdate,
        "2" AS s, 
        CASE 
            WHEN jt = "rop" THEN "Record of Proceedings" 
            WHEN jt = "judgment" THEN "Judgement" 
        END AS judgement_order,
        CASE 
            WHEN jt = "rop" THEN 1 
            WHEN jt = "judgment" THEN 3 
        END AS judgement_order_code, 
        "" AS order_type_remark,
        "" AS fee_clc_for_certification_no_doc, 
        "" AS fee_clc_for_certification_pages, 
        "" AS fee_clc_for_uncertification_no_doc, 
        "" AS fee_clc_for_uncertification_pages
    FROM 
        tempo 
    WHERE 
        diary_no IN (' . implode(',', array_map([$db->getConnection()->quote, $diary_no_array])) . ') 
        AND (jt = "rop" OR jt = "judgment") 

    UNION 

    SELECT 
        CONCAT("ropor/rop/all/", pno, ".pdf") AS pdfname,
        orderDate AS orderdate,
        "3" AS s, 
        "Record of Proceedings" AS judgement_order, 
        "1" AS judgement_order_code, 
        "" AS order_type_remark, 
        "" AS fee_clc_for_certification_no_doc, 
        "" AS fee_clc_for_certification_pages, 
        "" AS fee_clc_for_uncertification_no_doc, 
        "" AS fee_clc_for_uncertification_pages
    FROM 
        ' . $OLD_ROP . '.old_rop 
    WHERE 
        dn IN (' . implode(',', array_map([$db->getConnection()->quote, $diary_no_array])) . ') 

    UNION 

    SELECT 
        CONCAT("judis/", filename, ".pdf") AS pdfname,
        juddate AS orderdate,
        "4" AS s, 
        "Judgement" AS judgement_order, 
        "3" AS judgement_order_code, 
        "" AS order_type_remark,
        "" AS fee_clc_for_certification_no_doc, 
        "" AS fee_clc_for_certification_pages, 
        "" AS fee_clc_for_uncertification_no_doc, 
        "" AS fee_clc_for_uncertification_pages
    FROM 
        scordermain 
    WHERE 
        dn IN (' . implode(',', array_map([$db->getConnection()->quote, $diary_no_array])) . ') 

    UNION 

    SELECT 
        CONCAT("bosir/orderpdf/", pno, ".pdf") AS pdfname,
        orderdate AS orderdate,
        "5" AS s, 
        "Record of Proceedings" AS judgement_order, 
        "1" AS judgement_order_code, 
        "" AS order_type_remark,
        "" AS fee_clc_for_certification_no_doc, 
        "" AS fee_clc_for_certification_pages, 
        "" AS fee_clc_for_uncertification_no_doc, 
        "" AS fee_clc_for_uncertification_pages
    FROM 
        ' . $OLD_ROP . '.ordertext 
    WHERE 
        dn IN (' . implode(',', array_map([$db->getConnection()->quote, $diary_no_array])) . ') 

    UNION 

    SELECT 
        CONCAT("bosir/orderpdfold/", pno, ".pdf") AS pdfname,
        orderdate AS orderdate,
        "6" AS s, 
        "Record of Proceedings" AS judgement_order, 
        "1" AS judgement_order_code, 
        "" AS order_type_remark, 
        "" AS fee_clc_for_certification_no_doc, 
        "" AS fee_clc_for_certification_pages, 
        "" AS fee_clc_for_uncertification_no_doc, 
        "" AS fee_clc_for_uncertification_pages
    FROM 
        ' . $OLD_ROP . '.oldordtext 
    WHERE 
        dn IN (' . implode(',', array_map([$db->getConnection()->quote, $diary_no_array])) . ')
) zz', false);

// Order by orderdate
$builder->orderBy('orderdate');

// Execute the query
$query = $builder->get();

if ($query->getNumRows() > 0) {
    $data_list = $query->getResultArray();
} else {
    $data_list = [];
}
return $data_list;
}*/
public function getDataList(){
    // Assuming $condition is a comma-separated string of diary numbers
$condition = $this->request->getPost('diary_no');
$old_rop_db_name='rop_text_web_ecp';
$OLD_ROP = $old_rop_db_name; // Assuming this is defined somewhere in your code

// Prepare the condition for the IN clause
$diary_no_array = explode(',', $condition);
$diary_no_array = array_map('trim', $diary_no_array); // Trim whitespace

// Connect to the database
$db = \Config\Database::connect();

// Start building the query
$subquery1 = $db->table('copying_request_verify u')
    ->select('vd.path AS pdfname, vd.order_date AS orderdate, 0 AS s, ot.order_type AS judgement_order, ot.id AS judgement_order_code, vd.order_type_remark, vd.fee_clc_for_certification_no_doc, vd.fee_clc_for_certification_pages, vd.fee_clc_for_uncertification_no_doc, vd.fee_clc_for_uncertification_pages')
    ->join('copying_request_verify_documents vd', 'u.id = vd.copying_order_issuing_application_id')
    ->join('ref_order_type ot', 'ot.id = vd.order_type')
    ->whereIn('u.diary', $diary_no_array)
    ->where('u.application_status !=', 'P')
    ->where('vd.request_status', 'D')
    ->where('vd.path !=', '')
    ->where('vd.path IS NOT NULL')
    ->where('ot.is_deleted', 'f')
    ->where('u.allowed_request', 'request_to_available');

$subquery2 = $db->table('ordernet')
    ->select("pdfname, orderdate, '1' AS s, CASE WHEN type='O' THEN 'Record of Proceedings' WHEN type='J' THEN 'Judgement' END AS judgement_order, CASE WHEN type='O' THEN 1 WHEN type='J' THEN 3 END AS judgement_order_code, '' AS order_type_remark, '' AS fee_clc_for_certification_no_doc, '' AS fee_clc_for_certification_pages, '' AS fee_clc_for_uncertification_no_doc, '' AS fee_clc_for_uncertification_pages")
    ->whereIn('diary_no', $diary_no_array)
    ->where('display', 'Y');

$subquery3 = $db->table('tempo')
    ->select("jm AS pdfname, dated AS orderdate, '2' AS s, CASE WHEN jt='rop' THEN 'Record of Proceedings' WHEN jt='judgment' THEN 'Judgement' END AS judgement_order, CASE WHEN jt='rop' THEN 1 WHEN jt='judgment' THEN 3 END AS judgement_order_code, '' AS order_type_remark, '' AS fee_clc_for_certification_no_doc, '' AS fee_clc_for_certification_pages, '' AS fee_clc_for_uncertification_no_doc, '' AS fee_clc_for_uncertification_pages")
    ->whereIn('diary_no', $diary_no_array)
    ->whereIn('jt', ['rop', 'judgment']);

$subquery4 = $db->table("$OLD_ROP.old_rop")
    ->select("CONCAT('ropor/rop/all/', pno, '.pdf') AS pdfname, orderDate AS orderdate, '3' AS s, 'Record of Proceedings' AS judgement_order, '1' AS judgement_order_code, '' AS order_type_remark, '' AS fee_clc_for_certification_no_doc, '' AS fee_clc_for_certification_pages, '' AS fee_clc_for_uncertification_no_doc, '' AS fee_clc_for_uncertification_pages")
    ->whereIn('dn', $diary_no_array);

$subquery5 = $db->table('scordermain')
    ->select("CONCAT('judis/', filename, '.pdf') AS pdfname, juddate AS orderdate, '4' AS s, 'Judgement' AS judgement_order, '3' AS judgement_order_code, '' AS order_type_remark, '' AS fee_clc_for_certification_no_doc, '' AS fee_clc_for_certification_pages, '' AS fee_clc_for_uncertification_no_doc, '' AS fee_clc_for_uncertification_pages")
    ->whereIn('dn', $diary_no_array);

$subquery6 = $db->table("$OLD_ROP.ordertext")
    ->select("CONCAT('bosir/orderpdf/', pno, '.pdf') AS pdfname, orderdate AS orderdate, '5' AS s, 'Record of Proceedings' AS judgement_order, '1' AS judgement_order_code, '' AS order_type_remark, '' AS fee_clc_for_certification_no_doc, '' AS fee_clc_for_certification_pages, '' AS fee_clc_for_uncertification_no_doc, '' AS fee_clc_for_uncertification_pages")
    ->whereIn('dn', $diary_no_array);

$subquery7 = $db->table("$OLD_ROP.oldordtext")
    ->select("CONCAT('bosir/orderpdfold/', pno, '.pdf') AS pdfname, orderdate AS orderdate, '6' AS s, 'Record of Proceedings' AS judgement_order, '1' AS judgement_order_code, '' AS order_type_remark, '' AS fee_clc_for_certification_no_doc, '' AS fee_clc_for_certification_pages, '' AS fee_clc_for_uncertification_no_doc, '' AS fee_clc_for_uncertification_pages")
    ->whereIn('dn', $diary_no_array);

// Combine all subqueries using UNION
$finalQuery = $db->query("
    SELECT * FROM (
        $subquery1
        UNION
        $subquery2
        UNION
        $subquery3
        UNION
        $subquery4
        UNION
        $subquery5
        UNION
        $subquery6
        UNION
        $subquery7
    ) AS zz
    ORDER BY orderdate
");

// Fetch results
$data_list = $finalQuery->getResultArray();
return $data_list;
}
public function get_state($state_code) {
    $state_name = "";

    if (!empty($state_code)) {
        // Build the query to select the state name
        $query = $this->db->table('state')
            ->select('Name')
            ->where('id_no', $state_code)
            ->get();

        // Check if any rows were returned
        if ($query->getNumRows() > 0) {
            // Fetch the result as an associative array
            $row = $query->getRowArray();
            $state_name = $row['Name']; // Access the 'Name' field
        }
    }

    return $state_name;
}
public function getKycData($mobile, $email) {
    // Build the query to select from the uidai_offline_kyc table
    $query = $this->eservicesdb->table('uidai_offline_kyc')
        ->where('mobile', $mobile)
        ->where('email', $email)
        ->orderBy('ent_time', 'DESC')
        ->limit(1)
        ->get();

    // Check if any rows were returned
    if ($query->getNumRows() > 0) {
        // Fetch the result as an associative array
        return $query->getRowArray();
    }

    // Return null or an empty array if no results found
    return null; // or return [];
}
public function getActiveRejectionReasons(){
    // Build the query to select from the copying_reasons_for_rejection table
    $query = $this->db->table('copying_reasons_for_rejection')
        ->select('reasons, id')
        ->where('is_active', 'T')
        ->orderBy('reasons')
        ->get();

    // Check if any rows were returned
    if ($query->getNumRows() > 0) {
        // Fetch the result as an associative array
        return $query->getResultArray();
    }

    // Return an empty array if no results found
    return [];
}
public function updateAssets($data_to_update){
    $updated=$this->db->table('user_assets')
    ->where('id', $this->request->getPost('asset_table_id'))
    ->update($data_to_update);
    if($this->db->affectedRows() > 0){
    return true;
    }else{
    return false;
    }   
}
public function updateCopyingRequestVerify($data_to_update_verify){
    $updateCopyingRequestVerify=$this->db->table('copying_request_verify')
    ->where('application_status', 'P')
    ->where('crn', $this->request->getPost('crn'))
    ->update($data_to_update_verify);
    if($this->db->affectedRows() > 0){
        return true;
        }else{
        return false;
        }   
}
public function getcopying_request_verify_documentsByPrimaryID($id){
    $builder = $this->db->table('public.copying_request_verify_documents b');
    $builder->select('r.order_type AS order_name, b.*');
    $builder->join('master.ref_order_type r', 'b.order_type = r.id');
    $builder->where('b.id',$id);
    
    // If $concerned_section_docs is a condition, you can add it here
    if (!empty($concerned_section_docs)) {
        $builder->where($concerned_section_docs);
    }
    //echo $builder->getCompiledSelect();
    //die;
    // Execute the query
    $result1 = $builder->get();
    
    // Fetch the results
    $data = $result1->getRow();
    return $data;
}
public function copying_order_issuing_application_new(){
        $application_id = $this->request->getPost('application_id');
        // Use Query Builder to update the record
       
        $builder = $this->db->table('copying_order_issuing_application_new');

        $update=$builder->set('is_id_checked', 1)
        ->where('is_id_checked', 0)
        ->where('application_status', 'P')
        ->where('id',$application_id)
        ->update();
        if($this->db->affectedRows() > 0){
            return true;
            }else{
            return false;
            }   
}
public function getCopyingRequestVerifyByCrn($crn){
    $db = \Config\Database::connect();
    $sql_verify = $db->table('copying_request_verify a')
                ->join('copying_request_verify_documents b', 'a.id = b.copying_order_issuing_application_id')
                ->where('b.request_status', 'P')
                ->where('a.crn', $crn)
                ->limit(1)
                ->get();
    return $sql_verify->getNumRows();

}
public function updateCopyingRequestVerifyDocuments($data){
$applicationId = $_POST['application_id'];
$builder = $this->db->table('copying_request_verify_documents');
$builder->where('request_status', 'P');
$builder->where('id', $applicationId);
$builder->update($data);
if($this->db->affectedRows() > 0){
    return true;
    }else{
    return false;
    }   
}
public function updateCopyingRequestVerifyByCrn($updateVerifyData){
    //$applicationId = $_POST['application_id'];
    $crn = $this->request->getPost('crn');

    $builder = $this->db->table('copying_request_verify');
    $builder->where('application_status', 'P');
    $builder->where('id_proof_verify_status !=', 'P');
    $builder->where('crn',$crn);
    $builder->update($updateVerifyData);
    if($this->db->affectedRows() > 0){
        return true;
        }else{
        return false;
        }   
    }
  public function updateCopyingApplicationDocument($data,$document_id){
    $this->db->table('copying_application_documents')
                ->where('id', $document_id)
                ->update($data);
                if($this->db->affectedRows() > 0){
                    return true;
                    }else{
                    return false;
                    }   
  }
 public function getUserCodes() {
    // Ensure the database connection is available
    $db = \Config\Database::connect(); // Make sure to connect to the database

    // Get the employee ID from the session
    $empId =session()->get('icmic_empid');

    // Build the query using Query Builder
    $builder = $db->table('user_sec_map usm');
    $builder->select('GROUP_CONCAT(u.usercode) as usercode');
    $builder->join('usersection us', 'us.id = usm.usec');
    $builder->join('users u', 'u.section = us.id');
    $builder->where('usm.empid', $empId);
    $builder->where('usm.display', 'Y');
    $builder->where('us.display', 'Y');
    $builder->where('us.isda', 'Y');
    $builder->whereIn('u.usertype', [17, 50, 51]);

    // Execute the query
    $query = $builder->get();
    $result = '';

    // Check if any rows were returned
    if ($query->getNumRows() > 0) {
        $row = $query->getRow();
        $result = $row->usercode; // Get the concatenated user codes
    }

    // Return the result or an empty string if no codes were found
    return $result;
}
public function getUnavailableDocRequests($from_date, $to_date, $request_status_qry = '', $section_qry = '') {
    // Ensure the database connection is available
    $db = \Config\Database::connect(); // Make sure to connect to the database

    // Start building the query
    $builder = $db->table('copying_unavailable_doc_request a');
    $builder->select('u.name as updated_by_name, m.reg_no_display, CONCAT(pet_name, " Vs. ", res_name) as cause_title, m.c_status, ot.order_type as order_type_name, a.*');
    $builder->join('main m', 'm.diary_no = a.diary_no', 'left');
    $builder->join('ref_order_type ot', 'ot.id = a.order_type', 'left');
    $builder->join('users u', 'u.usercode = a.updated_by', 'left');
    
    // Add the date range condition
    $builder->where('DATE(a.ent_dt) >=', $from_date);
    $builder->where('DATE(a.ent_dt) <=', $to_date);
    
    // Add additional conditions if provided
    if (!empty($request_status_qry)) {
        $builder->where($request_status_qry);
    }
    
    if (!empty($section_qry)) {
        $builder->where($section_qry);
    }

    // Order by the entry date
    $builder->orderBy('a.ent_dt');

    // Execute the query
    $query = $builder->get();
    
    // Check if any rows were returned
    if ($query->getNumRows() > 0) {
        return $query->getResultArray(); // Return the result set
    } else {
        return []; // Return an empty array if no results found
    }
}
public function update_unavailable_copy_upload($copyId,$data){
    $db = \Config\Database::connect();
    $db->table('copying_unavailable_doc_request')
        ->where('id', $copyId)
        ->update($data);
    return $db->affectedRows();
}
public function insert_copying_request_movement($movementData){
    $db = \Config\Database::connect();
    $db->table('copying_request_movement')->insert($movementData);
    return $db->affectedRows();
}
public function copying_request_verify_documents_log($data){
    $db = \Config\Database::connect();
    $db->table('copying_request_verify_documents_log')->insert($data);
    return $db->affectedRows();  
}
public function getRequestVerifyStatusByCrn($crn){
    $sql_verify3 = $this->db->table('copying_request_verify a')
                    ->select('GROUP_CONCAT(DISTINCT b.request_status) AS distinct_verify_status')
                    ->join('copying_request_verify_documents b', 'a.id = b.copying_order_issuing_application_id')
                    ->where('a.crn', $crn)
                    ->limit(1)
                    ->get();
    
    $data3 = $sql_verify3->getRowArray();
    return $data3;
}
public function getApplicationData($applicationType, $applicationNo, $applicationYear)
    {
        return $this->db->table('copying_order_issuing_application_new a')
            ->select('application, m.reg_no_display, m.c_status, a.id, a.application_number_display, a.diary, a.crn, a.application_receipt, 
                      a.name, a.application_status, a.filed_by, a.court_fee, a.postal_fee, a.delivery_mode, r.description, s.status_description')
            ->join('main m', 'm.diary_no = a.diary', 'left')
            ->join('ref_copying_source r', 'r.id = a.source', 'left')
            ->join('ref_copying_status s', 's.status_code = a.application_status', 'left')
            ->where('copy_category', $applicationType)
            ->where('application_reg_number', $applicationNo)
            ->where('application_reg_year', $applicationYear)
            ->limit(1)
            ->get()
            ->getRowArray();
    }

    public function getRequestData($crn)
    {
         $row=$this->db->table('copying_order_issuing_application_new a')
            ->select("'request' AS application_request, m.reg_no_display, m.c_status, a.id, a.application_number_display, a.diary, a.crn, a.application_receipt, 
                      a.name, a.application_status, a.filed_by, a.court_fee, a.postal_fee, a.delivery_mode, r.description, s.status_description")
            ->join('main m', 'm.diary_no = a.diary', 'left')
            ->join('master.ref_copying_source r', 'r.id = a.source', 'left')
            ->join('master.ref_copying_status s', 's.status_code = a.application_status', 'left')
            ->where('crn', $crn)
            ->where('crn !=', '0')
            ->limit(1)
            ->get()
            ->getRowArray();
            return $row;
    }   
}