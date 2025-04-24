<?php

namespace App\Controllers\Library;

use App\Controllers\BaseController;
use Config\Database;


class ResourcesList extends BaseController
{
    protected $db;
    protected $session;
    public $e_services;

    public function __construct()
    {
        $this->db = Database::connect();
        $this->session = session();
        $this->e_services = \Config\Database::connect('eservices');
    }

    public function resourcesList_view()
    {
        $sessionData = $this->session->get();
        if (!isset($sessionData['login']['usercode'])) {
            return redirect()->to('/login');
        }

        return view('Library/resourcesList');
    }

    public function list_process()
    {
        $request = \Config\Services::request();
        $list_date = $request->getPost('list_date');
        $courtno = $request->getPost('courtno');
        $status = $request->getPost('status');

        $list_date_ymd = date("Y-m-d", strtotime($list_date));

        // Build query conditions
        $courtno_where = ($courtno > 0) ? " AND court_no = :courtno:" : "";
        $status_where = $status === 'Pending' ? " AND i_status = 'Pending' " : ($status === 'Completed' ? " AND i_status = 'Completed' " : "");

        // SQL Query
        $sql = "SELECT * FROM library_reference_material 
                WHERE is_active = 1 
                AND list_date = :list_date: 
                $courtno_where 
                $status_where 
                ORDER BY court_no, item_no, aor_code";

        // Execute the query
        $query = $this->e_services->query($sql, [
            'list_date' => $list_date_ymd,
            'courtno' => $courtno,
        ]);

        
        $courtno_string = ($courtno > 0) ? " COURT NO. $courtno" : "";
        $status_string = ($status === 'Pending') ? "(STATUS - PENDING)" : (($status === 'Completed') ? "(STATUS - COMPLETED)" : "");
        $data['title'] = "LIBRARY RESOURCES FOR CAUSE LIST DATE " . $list_date . $courtno_string . " $status_string (As on " . date('d-m-Y H:i A') . ")";
        $data['cases'] = $query->getResultArray();
        return view('Library/list_process_data', $data);

        
    }


    public function uploadFiles()
    {
        $request = \Config\Services::request();
        $files = $request->getFiles();
        $library_reference_material = $this->request->getPost('library_reference_material');
        $uploaded_files = [];

        // Handle file uploads
        $this->handleFileUpload($files['upload_document'], $library_reference_material, $uploaded_files);
        $this->handleFileUpload($files['upload_document_lib'], $library_reference_material, $uploaded_files);

        return json_encode(['status' => 'success', 'uploaded_files' => $uploaded_files]);
    }

    private function handleFileUpload($fileArray, $library_reference_material, &$uploaded_files)
    {
        if ($fileArray) {
            foreach ($fileArray as $file) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $file->move(WRITEPATH . 'uploads/', $file->getName());
                    $uploaded_files[] = $file->getName();
                    // Optionally log the file upload to the database
                }
            }
        }
    }

    function getAORName($aor_code)
    {
        $aor_query = $this->db->query("SELECT title, name FROM master.bar WHERE aor_code = ?", [$aor_code]);
        $aor_data = $aor_query->getRow();
        return $aor_data ? ucwords(strtolower($aor_data->title . ' ' . $aor_data->name)) : '';
    }

    function getCaseNumber($diary_no)
    {
        $diary_query = $this->db->query("SELECT reg_no_display FROM main WHERE diary_no = ?", [$diary_no]);
        $diary_data = $diary_query->getRow();
        return $diary_data ? ($diary_data->reg_no_display ?: 'Diary No. ' . $diary_no) : 'Diary No. ' . $diary_no; // Default if not found
    }

    function getCaseDetails($row)
    {
        $diary_no = $row['diary_no'];
        $diary_query = $this->db->query("SELECT pet_name, res_name, reg_no_display, pno, rno FROM main WHERE diary_no = ?", [$diary_no]);
        $diary = $diary_query->getRow();

        if (!$diary) {
            return 'Diary No. ' . $diary_no;
        }

        $pet_name = $diary->pet_name;
        $res_name = $diary->res_name;

        if ($diary->pno == 2) {
            $pet_name .= " AND ANR.";
        } elseif ($diary->pno > 2) {
            $pet_name .= " AND ORS.";
        }

        if ($diary->rno == 2) {
            $res_name .= " AND ANR.";
        } elseif ($diary->rno > 2) {
            $res_name .= " AND ORS.";
        }

        return $pet_name . ' Vs. ' . $res_name . '<br> Case No.: ' . ($diary->reg_no_display ?: 'Diary No. ' . $diary_no);
    }

    public function UploadModel()
    {

        $data = [
            'list_date' => $this->request->getPost('list_date'),
            'diary_no' => $this->request->getPost('diary_no'),
            'library_reference_material' => $this->request->getPost('library_reference_material'),
            'i_status' => $this->request->getPost('i_status'),
            'case_no' => $this->request->getPost('case_no'),
            'cause_title' => $this->request->getPost('cause_title'),
            'court_no' => $this->request->getPost('court_no'),
            'item_no' => $this->request->getPost('item_no'),
        ];

        $sql22 =   "select a.id, a.header_details, a.file_name, b.name_of_header, a.icmis_file_name 
        from library_referance_material_child a
        inner join library_master_headers as b on b.id = a.library_master_headers_id
        where a.library_reference_material_id = ".$_POST['library_reference_material']." and a.is_active = 1 order by a.id";
        $result_query = $this->e_services->query($sql22);
        $data['rs22'] = $result_query->getResultArray();
       
        // Load the view for the modal
        return view('Library/UploadModel', $data);
    }

    

    public function upload_modal_save()
    {
        $inserted_court = 0;
        $new_name = "";
        $ucode = session()->get('login')['usercode'];
        if(count($_POST['library_referance_material_child'])>0){
            for ($i = 0; $i < count($_POST['library_referance_material_child']); $i++) {
                if (empty($_FILES["upload_document"]["name"][$i])) {
                    //echo "Empty file can not be uploaded";
                    //exit;
                }
                if(!empty($_FILES["upload_document"]["name"][$i])){
                    $allowedExts = array("pdf");
                    $temp = explode(".", $_FILES["upload_document"]["name"][$i]);
                    $extension = strtolower(end($temp));
                    if (empty($_FILES["upload_document"]["name"][$i])) {
                        echo "Empty file can not be uploaded";
                        exit;
                    } else if ($_FILES["upload_document"]["error"][$i] > 0) {
                        echo "Return Code: " . $_FILES["file"]["error"][$i] . "<br>";
                        exit;
                    } else if ($_FILES["upload_document"]["size"][$i] > 200100000 && $_FILES["upload_document"]["type"][$i] == "application/pdf") {
                        echo "Not more then 20 mb allowed";
                        exit;
                    } else if (!(in_array($extension, $allowedExts) && ($_FILES["upload_document"]["type"][$i] == "application/pdf"))) {
                        echo "Only Pdf file allowed";
                        exit;
                    } else {
                        $master_to_path = "/var/www/html/supreme_court/library_resources_offline/files/library_aor_uploads/" . $_POST['library_reference_material']."/";
                        if (!file_exists($master_to_path)) {
                            //mkdir($master_to_path, 2770, true);
                            mkdir($master_to_path);
                        }
                        chdir($master_to_path);
                        getcwd();
                        $new_name = md5(uniqid(rand(), TRUE)) . '.' . $extension;
                        if (file_exists($new_name)) {
                            echo "Sorry File already exist. Try Again!";
                            exit;
                        } else if (move_uploaded_file($_FILES["upload_document"]["tmp_name"][$i], $master_to_path.$new_name)) {
        
                            $sql_insert = "insert into library_referance_material_child_log 
                                    (id, library_reference_material_id, library_master_headers_id, header_details, file_name, is_active, entry_date, updated_date, is_entry_source_online, file_retention_policy, usercode,icmis_file_name,icmis_entry_date,icmis_usercode, deleted_by, deleted_on) 
                                    select a.*, '$ucode' as deleted_by, NOW() as deleted_on from library_referance_material_child a where id = ".$_POST['library_referance_material_child'][$i]." and library_reference_material_id = ".$_POST['library_reference_material']." ";
                            $insert_rs = $this->e_services->query($sql_insert);
                           // $insert_rs->execute();
        
                            $sql_update = "update library_referance_material_child set
                                                    is_entry_source_online = 0, file_name = '" . $_POST['library_reference_material']."/".$new_name . "', 
                                                    usercode = $ucode, updated_date = NOW() 
                                                    where id = ".$_POST['library_referance_material_child'][$i]." and 
                                                    library_reference_material_id = ".$_POST['library_reference_material']." ";
                            $rs = $this->e_services->query($sql_update);
                            //$rs->execute();
                            //$last_id = $dbo->lastInsertId();
                            //$rs = mysql_query($sql) or die(mysql_error());
                            $inserted_court = $inserted_court + 1;
                        } else {
                            echo "Not Allowed, Check Permission";
                            exit;
                        }
        
        
                    }
        
                }
        
        
                //if(empty($_FILES["upload_document_lib"]["name"][$i])){
                    //echo "Empty file can not be uploaded";
                    //exit;
               // }
                if(!empty($_FILES["upload_document_lib"]["name"][$i])){
                    $allowedExts = array("pdf");
                    $temp = explode(".", $_FILES["upload_document_lib"]["name"][$i]);
                    $extension = strtolower(end($temp));
                    if (empty($_FILES["upload_document_lib"]["name"][$i])) {
                        echo "Empty file can not be uploaded";
                        exit;
                    } else if ($_FILES["upload_document_lib"]["error"][$i] > 0) {
                        echo "Return Code: " . $_FILES["file"]["error"][$i] . "<br>";
                        exit;
                    } else if ($_FILES["upload_document_lib"]["size"][$i] > 200100000 && $_FILES["upload_document_lib"]["type"][$i] == "application/pdf") {
                        echo "Not more then 20 mb allowed";
                        exit;
                    } else if (!(in_array($extension, $allowedExts) && ($_FILES["upload_document_lib"]["type"][$i] == "application/pdf"))) {
                        echo "Only Pdf file allowed";
                        exit;
                    } else {
                        $master_to_path = "/var/www/html/supreme_court/library_resources_offline/files/library_aor_uploads/" . $_POST['library_reference_material']."/";
                        if (!file_exists($master_to_path)) {
                            mkdir($master_to_path, 0777, true);
                            // mkdir($master_to_path);
                        }
                        chdir($master_to_path);
                        getcwd();
                        $new_name = md5(uniqid(rand(), TRUE)) . '.' . $extension;
                        if (file_exists($new_name)) {
                            echo "Sorry File already exist. Try Again!";
                            exit;
                        } else if (move_uploaded_file($_FILES["upload_document_lib"]["tmp_name"][$i], $master_to_path.$new_name)) {
        
                             $sql_insert = "insert into library_referance_material_child_log 
                                    (id, library_reference_material_id, library_master_headers_id, header_details, file_name, is_active, entry_date, updated_date, is_entry_source_online, file_retention_policy, usercode,icmis_file_name,icmis_entry_date,icmis_usercode, deleted_by, deleted_on) 
                                    select a.*, $ucode as deleted_by, NOW() as deleted_on from library_referance_material_child a where id = ".$_POST['library_referance_material_child'][$i]." and library_reference_material_id = ".$_POST['library_reference_material']." ";
                            $insert_rs = $this->e_services->query($sql_insert);
                            //$insert_rs->execute();
        
                            $sql_update = "update library_referance_material_child set
                                                    is_entry_source_online = 0, icmis_file_name = '" . $_POST['library_reference_material']."/".$new_name . "', 
                                                    icmis_usercode = $ucode, icmis_entry_date = NOW() 
                                                    where id = ".$_POST['library_referance_material_child'][$i]." and 
                                                    library_reference_material_id = ".$_POST['library_reference_material']." ";
                            $rs = $this->e_services->query($sql_update);
                            //$rs->execute();
                            //$last_id = $dbo->lastInsertId();
                            //$rs = mysql_query($sql) or die(mysql_error());
                            $inserted_court = $inserted_court + 1;
                        } else {
                            echo "Not Allowed, Check Permission";
                            exit;
                        }
        
        
                    }
        
                }
        
            }
        
        
            if($_REQUEST['status_option'] == 'Completed'){
                $sql = "update library_reference_material set i_updated_by = $ucode, i_updated_on = NOW(), i_status = 'Completed',
                file_retention_policy = '".$_POST['document_retain_option']."'
                where id = ".$_POST['library_reference_material'];
                $update = $this->e_services->query($sql);
                //$update->execute();
                echo "Uploaded successfully";
            }else{
                $sql = "update library_reference_material set i_updated_by = $ucode, i_updated_on = NOW(), i_status = 'Pending',
                file_retention_policy = '".$_POST['document_retain_option']."'
                where id = ".$_POST['library_reference_material'];
                $update = $this->e_services->query($sql);
               // $update->execute();
                echo "Uploaded successfully";
            }
        
        
        }
    }

    // public function upload()
    // {
      
    //     if ($this->request->getMethod() !== 'post') {
    //         return json_encode(['status' => 'Error: Invalid request method']);
    //     }

    //     $libraryReferenceMaterialId = $this->request->getPost('library_reference_material');
    //     $files = $this->request->getFiles('upload_document');

    //     $uploadedFiles = []; 

       
    //     foreach ($files['upload_document'] as $file) {
    //         if ($file->isValid()) {
              
    //             if ($file->getMimeType() === 'application/pdf') {
    //                 $newFileName = $file->getRandomName();  
    //                 $file->move(WRITEPATH . 'public/uploads/', $newFileName);
    //                 $uploadedFiles[] = $newFileName; 
    //             } else {
    //                 return json_encode(['status' => 'Error: Only PDF files are allowed']);
    //             }
    //         } else {
    //             return json_encode(['status' => 'Error: Invalid file upload']);
    //         }
    //     }

    //     return json_encode(['status' => 'Uploaded successfully', 'uploaded_files' => $uploadedFiles]);
    // }

    
}