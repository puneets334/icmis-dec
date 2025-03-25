<?php

namespace App\Controllers\Scanning;

use App\Controllers\BaseController;
use App\Models\Menu_model;
use CodeIgniter\Controller;
use CodeIgniter\Model;
use App\Models\Scanning\ScaningModel;

class SaccaningController extends BaseController
{
  protected $ScaningModel;
  protected $db;

  public function __construct()
  {
    $this->ScaningModel = new ScaningModel();
    $this->db = \Config\Database::connect();
  }

  public function FreshlyFiled()
  {
    $data = [];
    $title = '';
    if ($this->request->getMethod() == 'post') {
      $fromDate = date("Y-m-d", strtotime($this->request->getPost('fromDate')));
      $toDate = date("Y-m-d", strtotime($this->request->getPost('toDate')));

      
      // $toDate = $this->request->getPost('toDate');
      $title = "Verification of Freshly Filed Cases Completed From " . date("d-m-Y", strtotime($fromDate)) . " To " . date("d-m-Y", strtotime($toDate)) . " (As On " . date('d-m-Y H:i:s') . ")";
      $FreshlyData = $this->ScaningModel->getDataByDateRange($fromDate, $toDate);
      $title = (empty($FreshlyData)) ? 'No Records Found' : $title;

      $data = [
        'title' => $title,
        'FreshlyData' => $FreshlyData,
        'fromDate' => $fromDate,
        'toDate' => $toDate
      ];
    }
    return view('scanning/FreshlyFiled', $data);
  }

  public function addUpdateindexing()
  {
    // $this->session->set_userdata('dcmis_user_idd', $usercode);
    // $data['usercode'] = $usercode;
    
    $result = $courtDetails = $getIndexDocs = $diaryDocumentsArray = [];
    $diary_number = $diary_year = '';
    $all_doc = $this->ScaningModel->getAllDocuments();
    if ($this->request->getMethod() == 'post') {
      $diary_number = $this->request->getPost('diary_number');
      $diary_year = $this->request->getPost('diary_year');
      $dairy_no = $diary_number . $diary_year;
      $org_fil_no = '';
     
      $courtDetails = $this->ScaningModel->getCourtDetails($dairy_no);   
      // echo "<pre>";   print_r($courtDetails);die;
      $getIndexDocs = $this->ScaningModel->getIndexDocs($dairy_no);     
      $diaryDocumentsArray = $this->ScaningModel->getDiaryDocumentsDetails($dairy_no);
   
      $builder = $this->db->table('public.main');
      $builder->select('pet_name, res_name, c_status, fil_no');
      $builder->where('diary_no', $dairy_no);
      $query = $builder->get();
      $result = $query->getRow();
      $data = [
        'result' => $result ?: '',
      ];
    }

    return view('scanning/indexing/addUpdateIndexing', compact('all_doc', 'result', 'courtDetails', 'diary_number', 'diary_year','getIndexDocs', 'diaryDocumentsArray'));
  }

  public function listingAssets()
  {
    $caseType = $this->ScaningModel->getCaseType();
    return view('scanning/report/listingAssests', ['caseType' => $caseType]);
  }

  public function receiveReturnMovement()
  {
    $data['caseType'] = $this->ScaningModel->getCaseType();
    // echo "<pre>";print_r($data);die;
    return view('scanning/movement/receiveReturn', $data);
  }

  public function movementReport()
  {
    return view('scanning/movement/reportMovement');
  }



  public function search_rpt()
  {
      if ($this->request->getPost('search_rpt') == 'Rpt_search') {
      $to_dt = date("Y-m-d", strtotime($this->request->getPost('To_date')));
      // $from_dt = date("Y-m-d", strtotime($this->request->getPost('From_date'))); // Uncomment if needed
      $movement_flag_type = $this->request->getPost('movement_flag_type');

      // Initialize the query
      $builder = $this->db->table('public.scan_movement sm');
      $builder->select('sm.*, u.name');
      $builder->join('master.users u', 'sm.user_id = u.usercode', 'left');

      // Add where conditions based on the provided data
      if ($this->request->getPost('To_date') != '') {
        $builder->where('sm.list_dt', $to_dt);
      } else {
        $builder->where('sm.list_dt >=', date('Y-m-d')); // Current date
      }

      if ($movement_flag_type == 'ALL') {
        $builder->where('sm.is_active', 'T');
      } else {
        $builder->where('sm.movement_flag', $movement_flag_type);
        $builder->where('sm.is_active', 'T');
      }
      // echo $builder->getCompiledSelect(); 
      $query = $builder->get();
      // exit();

      $output = [];
      foreach ($query->getResultArray() as $key=>$row) {
        $output[] = [
          'id'             => $key+1,
          'item_no'        => $row['item_no'],
          'dairy_no'       => $row['dairy_no'],
          'list_dt'        => $row['list_dt'],
          'movement_flag'  => ucfirst($row['movement_flag']),
          'event_type'     => $row['event_type'],
          'entry_date_time' => date('Y-m-d H:i A', strtotime($row['entry_date_time'])),
          'name'           => $row['name']
        ];
      }
      return $this->response->setJSON([
        'data' => $output,
        'csrf_token' => csrf_hash()
      ]);
      // echo $output;
    }
  }

  public function scanMoveProcess()
  {
    $ScaningModel = new ScaningModel();
    $list_date = $this->request->getPost('list_date');
    $mainhead = $this->request->getPost('mainhead');
    $courtno = $this->request->getPost('courtno');
    $movement_flag_type = $this->request->getPost('movement_flag_type');

    // Process date formats
    $list_date_dmy = date("d-m-Y", strtotime($list_date));
    $list_date = date("Y-m-d", strtotime($list_date));

    $stg = $mainhead == 'M' ? 1 : 2;
  
    // Build the condition
    $t_cn= "r.courtno = '$courtno' AND ( to_date IS NULL OR '$list_date' BETWEEN from_date AND to_date )";
    $roster_ids = $ScaningModel->getRosterIds($stg, $t_cn);    
    // echo "<pre>";print_r($roster_ids);exit();
    // Prepare additional WHERE conditions based on movement_flag_type
    $where_condition = $ScaningModel->buildWhereCondition($movement_flag_type);
    if(empty($roster_ids))
    {
      $results = [];
    }else{  
    $results = $ScaningModel->getCaseDetails($list_date, $mainhead, $roster_ids, $where_condition);
    }

    // echo "<pre>";print_r($results);exit();

    if ($results) {
      return view('scanning/movement/receive_return_view', ['results' => $results, 'list_date_dmy' => $list_date_dmy, 'courtno' => $courtno, 'mainhead' => $mainhead]);
    } else {
      return 'No Records Found';
    }
  }


  public function add_update_scanning_movement()
  {
    return true;
    // echo "<pre>";
    // print_r($this->request->getPost());
    // exit();
    if ($this->request->getPost('action') == 'save_record') {
      $dairyno = $this->request->getPost('diaryno');
      $cause_title = $this->request->getPost('cause_title');
      $movement_flag = $this->request->getPost('movement');
      $event_type = ($movement_flag == 'receive') ? $this->request->getPost('event') : '';

      $dateDefult = new \DateTime();
      $date_time_defult = $dateDefult->format('Y-m-d H:i:s');
      $clientIP = $this->request->getPost('clientIP');
      $roster_id = $this->request->getPost('roster_id');
      $list_date = $this->request->getPost('list_dt');
      $item_no = $this->request->getPost('item_no');
      $user_id = session()->get('login')['usercode'];;

      $builder = $this->db->table('scan_movement');
      $builder->where('dairy_no', $dairyno);
      $builder->where('is_active', 'T');
      $row_chk = $builder->countAllResults();

      if ($row_chk == 0) {
        $data = [
          'dairy_no' => $dairyno,
          'list_dt' => $list_date,
          'roster_id' => $roster_id,
          'item_no' => $item_no,
          'movement_flag' => $movement_flag,
          'event_type' => $event_type,
          'user_id' => $user_id,
          'ip_address' => $clientIP,
          'is_active' => 'T',
          'entry_date_time' => $date_time_defult
        ];
        $builder->insert($data);
        $last_id = $this->db->insertID();

        if ($last_id > 0) {
          echo "1"; // INSERT SUCCESSFULLY..
          exit();
        } else {
          echo "0"; // NOT INSERT..
          exit();
        }
      } else {
        $builder = $this->db->table('scan_movement_history');
        $builder->insertSelect('scan_movement', '*', ['dairy_no' => $dairyno, 'is_active' => 'T']);
        $last_insrt_his_id = $this->db->insertID();

        if ($last_insrt_his_id > 0) {
          $data = [
            'list_dt' => $list_date,
            'roster_id' => $roster_id,
            'item_no' => $item_no,
            'movement_flag' => $movement_flag,
            'event_type' => $event_type,
            'user_id' => $user_id,
            'ip_address' => $clientIP,
            'is_active' => 'T',
            'entry_date_time' => $date_time_defult
          ];
          $builder = $this->db->table('scan_movement');
          $builder->where('dairy_no', $dairyno);
          $builder->update($data);
          $affected_rows = $this->db->affectedRows();

          if ($affected_rows > 0) {
            echo "1"; // RETURN SUCCESSFULLY..
            exit();
          } else {
            echo "0";
            exit();
          }
        }
      }
    }
  }

  public function listTypeAssetsSearch()
  {
    if ($this->request->getPost('search_flag') == 'list_detail') {

      $to_dt = date("Y-m-d", strtotime($this->request->getPost('list_date')));
      $courtno = $this->request->getPost('courtno');
      $mainhead = $this->request->getPost('mainhead');

      // Initialize the query
      // $builder = $this->db->table('public.scan_movement sm');
      // $builder->select('sm.*, u.name');

      $output = "Result Not Found ";
      return $this->response->setJSON([
        'html' => $output,
        'csrf_token' => csrf_hash() // if you need to return a new CSRF token
      ]);
    }
  }

  public function searchTypeAssetsSearch()
  {
    $scaningModel = new ScaningModel();

    // Fetch the POST data
    $d_no = $this->request->getPost('d_no');
    $d_yr = $this->request->getPost('d_yr');
    $fno = $this->request->getPost('fno');
    $ct = $this->request->getPost('ct');
    $cn = $this->request->getPost('cn');
    $cy = $this->request->getPost('cy');
    $chk_status = $this->request->getPost('chk_status');

    $result = $scaningModel->getAssetsSearch($d_no, $d_yr, $fno, $ct, $cn, $cy, $chk_status);
    // Initialize the query
    // $builder = $this->db->table('public.scan_movement sm');
    // $builder->select('sm.*, u.name');
    if ($result) {

      $output = "Result Not Found ";
    }

    return $this->response->setJSON([
      'html' => $output,
      'csrf_token' => csrf_hash() // if you need to return a new CSRF token
    ]);
  }
  /* 21 Oct 2024 */
  public function getIndexing()
  {
    if ($this->request->getPost('search_flag') == 'get_index_data') {

      $diary_number = $this->request->getPost('diary_number');
      $diary_year = $this->request->getPost('diary_year');
      $dairy_no = $diary_number . $diary_year;
      $org_fil_no = '';
      $builder = $this->db->table('public.main');
      $builder->select('pet_name, res_name, c_status, fil_no');
      $builder->where('diary_no', $dairy_no);
      $query = $builder->get();
      if ($query->getNumRows() > 0) {
        $result = $query->getResult();
      } else {
        echo "No records found";
      }
    }
  }

  public function addUpdateScanningDoc()
  {

    $diary_number = $this->request->getPost('diary_number');
    $diary_year = $this->request->getPost('diary_year');
    $dairy_no = $diary_number . $diary_year;
    $from_page_start = $this->ScaningModel->getTotalIndexingPage($dairy_no);
    echo $from_page_start;
  }

  public function saveIndexingData()
  {

    $usercode = session()->get('login')['usercode'];

    $data['usercode'] = $usercode;
    $ucode = $usercode;
    $date_new = " '0000-00-00' ";
    $id_new = "0";
    $handle = $this->request->getPost('handle');
    $fil_no = $this->request->getPost('fil_no');
    $doccode = $this->request->getPost('doccode');
    $doccode1 = $this->request->getPost('doccode1');
    $other_desc = $this->request->getPost('other_desc');
    $fp = $this->request->getPost('fp');
    $tp = $this->request->getPost('tp');
    $np = $this->request->getPost('np');
    $itype = $this->request->getPost('itype');
    $m_doc_nm = $this->request->getPost('m_doc_nm');
    $s_doc_nm = $this->request->getPost('s_doc_nm');
    $upd_file = $this->request->getPost('upd_file');
    $ddl_case_no = $this->request->getPost('ddl_case_no');
    $hd_docd_ids = $this->request->getPost('hd_docd_ids');

    $diary_number = $this->request->getPost('diary_number');
    $diary_year = $this->request->getPost('diary_year');
    $dairy_no = $diary_number . $diary_year;
    $dairy_no = (int)$dairy_no;

    if ($handle == 'S') {
      if ($doccode == '16') {
        $check = $this->db->table('public.indexing')  // Specify the table name directly
          ->where('diary_no', $dairy_no)
          ->where('i_type', $itype)
          ->where('doccode', $doccode)
          ->where('doccode1', $doccode1)
          ->where('display', 'Y')
          ->get()
          ->getFirstRow();  // Get the first row

        // If record is found, proceed with further checks
        if ($check) {
          $docDesc = $this->db->table('master.docmaster')  // Specify the table name directly
            ->select('docdesc')
            ->where('doccode', $doccode)
            ->where('doccode1', $doccode1)
            ->get()
            ->getFirstRow();  // Get the first row
          if ($docDesc) {
            $name = $docDesc->docdesc;  // Get 'docdesc'
            echo "<p style='text-align:center;color:red;font-size:16px;'>This ANNEXURE $name is Already Taken</p>";
            exit();  // Exit after the message
          }
        }
      }

      if ($doccode == '100' || $doccode == '101') {
        $check = $this->db->table('public.indexing')
          ->where('diary_no', $dairy_no)
          ->where('i_type', $itype)
          ->where('doccode', $doccode)
          ->where('doccode1', $doccode1)
          ->where('display', 'Y')
          ->get();

        if ($check->getNumRows() > 0) {
          $query = $this->db->table('master.docmaster')
            ->select('docdesc')
            ->where('doccode', $doccode)
            ->where('doccode1', $doccode1)
            ->get();

          if ($query->getNumRows() > 0) {
            $name = $query->getRow()->docdesc; // Get the docdesc from the result

            echo "<p style='text-align:center;color:red;font-size:16px;'>This Doc {$name} is Already Taken</p>";
            return; // Exit the function after outputting the message
          }
        }
      }



      $query = $this->db->table('public.indexing')
        ->selectCount('diary_no')
        ->where('diary_no', $dairy_no)
        ->where('i_type', $itype)
        ->where('display', 'Y')
        ->groupStart() // Start grouping for the OR conditions
        ->where('fp <=', $fp)
        ->where('tp >=', $fp)
        ->orWhere('fp <=', $tp)
        ->where('tp >=', $tp)
        ->orWhere('fp >=', $fp)
        ->where('tp <=', $tp)
        ->groupEnd() // End grouping
        ->get();
      $res_sql = $query->getNumRows();


      if ($res_sql <= 0) {
        // Handle file upload
        if ($upd_file != '') {
          if ($_FILES["file"]["type"] !== "application/pdf") {
            return "Sorry, only PDF files are allowed.";
          } else {
            $doc_unq_col = '';
            $doc_unq_val = '';
            if ($hd_docd_ids != '') {
              $doc_unq_col = ",src_of_ent";
              $doc_unq_val = ",'$hd_docd_ids'";
            }

            // Save data
            $data = [
              'doccode' => $doccode,
              'doccode1' => $doccode1,
              'other' => $other_desc,
              'i_type' => $itype,
              'fp' => $fp,
              'tp' => $tp,
              'np' => $np,
              'entdt' => date('Y-m-d H:i:s'),
              'ucode' => $ucode,
              'diary_no' => $dairy_no,
              'lowerct_id' => $ddl_case_no,
            ];

            if ($hd_docd_ids != '') {
              $data['src_of_ent'] = $hd_docd_ids;
            }

            // Insert data and check the result
            if ($this->ScaningModel->insertData($data)) {
              // Successfully inserted
              return "Data saved successfully.";
            } else {
              // Handle failure
              return "Failed to save data.";
            }

            // Retrieve the recently inserted record
            $res_sel = $indexingModel->where([
              'doccode' => $doccode,
              'doccode1' => $doccode1,
              'other' => $other_desc,
              'i_type' => $itype,
              'fp' => $fp,
              'tp' => $tp,
              'np' => $np,
              'diary_no' => $dairy_no
            ])->first();

            $entdt = $res_sel['entdt'];
            $entdt_d = date('d-m-Y', strtotime($entdt));
            $ex_text = ($doccode1 != 0) ? '-' . $s_doc_nm : '';
            $fil_nm = $diary_no . '_' . $year . '_' . $m_doc_nm . $ex_text . '_' . $res_sel['ind_id'] . '_' . $entdt_d;
            $new_name = $fil_nm . '.pdf';

            // Move the uploaded file
            if (move_uploaded_file($_FILES["file"]["tmp_name"], $new_name)) {
              // Update the record with the new PDF name
              $indexingModel->update($res_sel['ind_id'], ['pdf_name' => $new_name]);

              return "<p style='text-align:center'>Saved Successfully</p>";
            } else {
              return "Error in Uploading PDF file";
            }
          }
        } else {
          return "<p style='text-align:center'>Saved Successfully</p>";
        }
      } else {
        return "Record already exists.";
      }
    } else if ($handle == 'U') {

      if ($doccode == '16') {
        // Check if the record exists
        $check = $this->db->table('public.indexing')  // Specify the table name directly
          ->where('diary_no', $dairy_no)
          ->where('i_type', $itype)
          ->where('doccode', $doccode)
          ->where('doccode1', $doccode1)
          ->where('display', 'Y')
          ->get()
          ->getFirstRow();

        if ($check->getNumRows() > 0) {
          $query = $this->db->table('master.docmaster')
            ->select('docdesc')
            ->where('doccode', $doccode)
            ->where('doccode1', $doccode1)
            ->get();

          if ($query->getNumRows() > 0) {
            $name = $query->getRow()->docdesc; // Get the docdesc from the result

            echo "<p style='text-align:center;color:red;font-size:16px;'>This Doc {$name} is Already Taken</p>";
            return; // Exit the function after outputting the message
          }
        }
      }

      $data = [
        'doccode' => $doccode,
        'doccode1' => $doccode1,
        'other' => $other_desc,
        'tp' => $tp,
        'np' => $np,
        'entdt' => date('Y-m-d H:i:s'), // Current timestamp
        'ucode' => $ucode,
        'lowerct_id' => $ddl_case_no,
      ];
      // Update the record using Query Builder with table name
      $builder = $db->table('indexing'); // Specify the table name

      // Update the record based on conditions
      $updateStatus = $builder->where([
        'diary_no' => $dairy_no,
        'fp' => $fp,
        'i_type' => $itype,
        'display' => 'Y'
      ])->set($data)->update();

      if ($request->getPost('upd_file') != '') {
        // Get the database connection
        $db = \Config\Database::connect();

        // Prepare the query to fetch data
        $builder =  $this->db->table('public.indexing');
        $builder->select('ind_id, entdt');
        $builder->where('doccode', $request->getPost('doccode'));
        $builder->where('doccode1', $request->getPost('doccode1'));
        $builder->where('other', strtoupper(trim($request->getPost('other_desc'))));
        $builder->where('i_type', $request->getPost('itype'));
        $builder->where('fp', $request->getPost('fp'));
        $builder->where('tp', $request->getPost('tp'));
        $builder->where('np', $request->getPost('np'));
        $builder->where('diary_no', $dairy_no);
        $query = $builder->get();

        // Fetch the results
        if ($query->getNumRows() > 0) {
          $row = $query->getRow();
          $res_sel = $row->ind_id;
          $entdt = $row->entdt;
          $entdt_d = date('d-m-Y', strtotime($entdt));

          $ex_text = '';
          if ($request->getPost('doccode1') != 0) {
            $ex_text = '-' . $request->getPost('s_doc_nm');
          }
          $fil_nm = $request->getPost('diary_number') . '_' . date('Y') . '_' . $request->getPost('m_doc_nm') . $ex_text . '_' . $res_sel . '_' . $entdt_d;
          $new_name = $fil_nm . '.pdf';

          // Move the uploaded file
          if ($request->getFile('file')->isValid() && !$request->getFile('file')->hasMoved()) {
            $file = $request->getFile('file');
            $file->move(WRITEPATH . 'uploads/', $new_name);

            // Update the database with the new PDF name
            $builder =  $this->db->table('public.indexing');
            $builder->set('pdf_name', $new_name);
            $builder->where('ind_id', $res_sel);
            $builder->where('display', 'Y');
            $builder->update();

            echo "PDF File Uploaded Successfully";
            $sq_fkx = 1;
          } else {
            echo "Error in Uploading PDF file";
            $sq_fkx = 0;
          }
        } else {
          echo "No records found.";
          $sq_fkx = 0;
        }
      } else {
        $sq_fkx = 1; // No file to upload
      }

      $total_pages = $builder =  $this->db->table('public.indexing')
        ->select('fp, tp, np, entdt')
        ->where('diary_no', $dairy_no)
        ->where('fp >', $_REQUEST['fp'])
        ->where('display', 'Y')
        ->get();

      if ($total_pages->getNumRows() > 0) {
        $first_row = $total_pages->getRowArray();
        $tp++;
        $diff = $tp - $first_row['fp'];

        if ($diff > 0) {
          // Update records where fp matches
          $total_pages = $builder =  $this->db->table('public.indexing')
            ->select('fp, entdt')
            ->where('diary_no', $dairy_no)
            ->where('fp >', $_REQUEST['fp'])
            ->where('display', 'Y')
            ->get();

          foreach ($total_pages->getResultArray() as $row__up) {
            $builder = $this->db->table('public.indexing')
              ->set('fp', "fp + {$diff}", false)  // false to prevent escaping
              ->set('tp', "tp + {$diff}", false)
              ->set('upd_tif_dt', $date_new)
              ->set('upd_tif_id', $id_new)
              ->where('diary_no', $dairy_no)
              ->where('fp', $row__up['fp'])
              ->where('entdt', $row__up['entdt'])
              ->where('display', 'Y')
              ->update();
          }
        } elseif ($diff < 0) {
          // Update records where fp matches
          $total_pages = $builder = $db->table('public.indexing')
            ->select('fp, entdt')
            ->where('diary_no', $dairy_no)
            ->where('fp >', $_REQUEST['fp'])
            ->where('display', 'Y')
            ->get();

          foreach ($total_pages->getResultArray() as $row__up) {
            $builder = $db->table('public.indexing')
              ->set('fp', "fp - " . abs($diff), false)  // false to prevent escaping
              ->set('tp', "tp - " . abs($diff), false)
              ->set('upd_tif_dt', $date_new)
              ->set('upd_tif_id', $id_new)
              ->where('diary_no', $dairy_no)
              ->where('fp', $row__up['fp'])
              ->where('entdt', $row__up['entdt'])
              ->where('display', 'Y')
              ->update();
          }
        }
        // Optionally, you can echo a success message
        // echo "<p style='text-align:center'>Updated Successfully</p>";
      }
    }
  }

  public function getSubDocuments()
  {
    $doccode = $this->request->getPost('doccode');
    $doccodeData = $this->ScaningModel->getSubDocsByDoccode($doccode);
    $output = "<option value='0'>Select</option>";
    foreach ($doccodeData as $row) {
      $output .= "<option value='{$row['doccode1']}'>{$row['docdesc']}</option>";
    }

    return $output;
  }

  public function getStateName()
  {
    $ddl_court = $this->request->getPost('ddl_court');
    $diary_number = $this->request->getPost('diary_number');
    $diary_year = $this->request->getPost('diary_year');

    $dairy_no = $diary_number . $diary_year;

    // dd($dairy_no);

    $statDataArray = $this->ScaningModel->getStateNameData($ddl_court, $dairy_no);
    $output = "<option value='0'>Select</option>";
    foreach ($statDataArray as $row) {
      $output .= "<option value='{$row['id_no']}'>{$row['name']}</option>";
    }

    return $output;
  }
}
