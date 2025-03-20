<?php

namespace App\Controllers\Judicial\File_cover;

use App\Controllers\BaseController;
use App\Models\Casetype;
use App\Models\Common\Dropdown_list_model;
use App\Models\Judicial\FileCover\CoverPrintModel;

class PBCover extends BaseController
{
    public $diary_no;
    public $Casetype;
    public $CaseAdd;
    public $Dropdown_list_model;

    function __construct()
    {
        $this->Casetype = new Casetype();
        $this->Dropdown_list_model = new Dropdown_list_model();
    }

    public function index()
    {
        $usercode = session()->get('login')['usercode'];
        $userTypeString = getUser_dpdg_full_2($usercode);
        if ($userTypeString == 0) {
            echo "<div style='text-align: center;color: red;'><h3>YOU ARE NOT AUTHORISED</h3></div>";
            exit();
        }

        $userType = explode('~', $userTypeString);

        if (
            ($userType[0] != 1 && $userType[0] != 57 && $userType[0] != 3 && $userType[0] != 4) &&
            ($userType[6] != 450)
        ) {
            echo "<div style='text-align: center;color: red;'><h3>YOU ARE NOT AUTHORISED</h3></div>";
            exit();
        }
        $data = [
            // 'caseTypes' => $caseTypes,
            'session_diary_no' => session()->get('session_diary_no'),
            'session_diary_yr' => session()->get('session_diary_yr'),
        ];
        $data['formAction'] = 'Judicial/File_cover/PBCover/handlePostRequest';
        return view('Judicial/File_cover/pb_cover', $data);
    }

    public function handlePostRequest()
    {
        $request = \Config\Services::request();
        $search_type = $request->getPost('search_type');
            
        if ($search_type == 'D' && $this->validate([
            'diary_number' => ['label' => 'Diary Number', 'rules' => 'required|min_length[1]|max_length[8]'],
            'diary_year' => ['label' => 'Diary Year', 'rules' => 'required|min_length[4]'],
        ])) {
            $diary_number = $request->getPost('diary_number');
            $diary_year = $request->getPost('diary_year');
            $diary_no = $diary_number . $diary_year;
            $get_main_table = $this->Dropdown_list_model->get_diary_details_by_diary_no($diary_no);
        } elseif ($search_type == 'C' && $this->validate([
            'case_type' => ['label' => 'Case Type', 'rules' => 'required|min_length[1]|max_length[2]'],
            'case_number' => ['label' => 'Case Number', 'rules' => 'required|min_length[1]|max_length[8]'],
            'case_year' => ['label' => 'Case Year', 'rules' => 'required|min_length[4]'],
        ])) {

            $case_type = $request->getPost('case_type');
            $case_number = $request->getPost('case_number');
            $case_year = $request->getPost('case_year');
            
            $get_main_table = $this->Dropdown_list_model->get_case_details_by_case_no($case_type, $case_number, $case_year);

            if($get_main_table === false) {
                return $this->response->setJSON(['success' => 0, 'error' => 'Case not Found']);
            }

            $diary_info = get_diary_numyear($get_main_table['diary_no']);

            $diary_number = $diary_info[0];
            $diary_year = $diary_info[1];
        }

        if(empty($get_main_table)) {
            return $this->response->setJSON(['success' => 0, 'error' => 'Case not Found']);
        }

        $CoverPrintModel = new CoverPrintModel();
        $casetypeModel = new Casetype();
        // $ct = $this->request->getPost('case_type');
        // $cn = $this->request->getPost('case_number');
        // $cy = $this->request->getPost('case_year');
        // $dyr = $this->request->getPost('diary_year');
        // $dn = $this->request->getPost('diary_number');

        $db = \Config\Database::connect(); // Connect to the database
        
        $diaryNo = $get_main_table['diary_no'];
        $dn = $diary_number;
        $dyr = $diary_year;

        // $diaryNo = $dn . $dyr;
        // Fetch the main case details
        $builder = $db->table('main');
        $builder->select('section_id, dacode, diary_no_rec_date, fil_dt, pet_name, res_name, pno, rno, fil_no, casetype_id, active_casetype_id, active_fil_no, active_reg_year');
        $builder->where('diary_no', $diaryNo);
        $query = $builder->get();
        $r_get_da_sec = $query->getResultArray();// pr($r_get_da_sec);

        if (empty($r_get_da_sec)) {
            session()->setFlashdata('message_error', 'Diary Number Not Found');
            return '<h3 class="text-center text-danger">Diary number not found.</h3>';
        }

        $dacode = $r_get_da_sec[0]['dacode'] ?? null;

        // Fetch the section name
        $section_name = '';
        if ($dacode) {
            $builder_sec = $db->table('master.users a');
            $builder_sec->select('b.section_name')
                ->join('master.usersection b', 'a.section = b.id')
                ->where('a.usercode', $dacode)
                ->where('b.display', 'Y');
            $query_sec = $builder_sec->get();
            $section_result = $query_sec->getRowArray();

            $section_name = $section_result['section_name'] ?? '';
        } else {
            $builder_sec = $db->table('master.usersection');
            $builder_sec->select('section_name')
                ->where('id', $r_get_da_sec[0]['section_id'])
                ->where('display', 'Y');
            $query_sec = $builder_sec->get();
            $section_result = $query_sec->getRowArray();
            $section_name = $section_result['section_name'] ?? '';
        }

        // Fetch case details and construct the required information
        $c_code = $r_get_da_sec[0]['active_casetype_id'] ?? $r_get_da_sec[0]['casetype_id'];
        $active_fil_no = $r_get_da_sec[0]['active_fil_no'] ?? null;

        $builder_case = $db->table('master.casetype c');
        $builder_case->select('c.casename, c.short_description')
            ->where('c.casecode', $c_code)
            ->where('c.display', 'Y');
        $query_case = $builder_case->get();
        $case_result = $query_case->getRowArray();

        // Determine if active file number exists
        if (empty($active_fil_no)) {
            $active_fil_no = 'D.no.' . $dn . '/' . $dyr;
        } else {
            $a = explode('-', substr($active_fil_no, 3));

            // Check if $a has at least two elements
            if (isset($a[0], $a[1])) {
                $reg_no = ($a[0] == $a[1]) ? $a[0] : substr($active_fil_no, 3);
            } else {
                // Handle the case where $a does not have the expected elements
                $reg_no = substr($active_fil_no, 3); // Default behavior
            }

            $active_fil_no = 'NO. ' . $reg_no . '/' . $r_get_da_sec[0]['active_reg_year'];
        }

        // Fetch case nature details
        $r_c_type = $case_result['casename'] ?? '';
        $c_r = '';
        $ia_crmp = '';
        $casename = '';
        //$casename = $case_result['casename'];

        if (isset($r_get_da_sec[0]['nature']) && $r_get_da_sec[0]['nature'] == 'C') {
            $c_r = "Civil";
            $ia_crmp = "I.A.No.";
        } elseif (isset($r_get_da_sec[0]['nature']) && $r_get_da_sec[0]['nature'] == 'R') {
            $c_r = "Criminal";
            $ia_crmp = "Cr.M.P.No.";
        }

        // Prepare data to pass to the view
        $data = [
            'diaryNo' => $diaryNo,
            'r_get_da_sec' => $r_get_da_sec[0],
            'section_name' => $section_name,
            'fil_no_yr' => $active_fil_no,
            'c_r' => $c_r,
            'ia_crmp' => $ia_crmp,
            'dn' =>  $dn,
            'casename' => $casename,
            'dyr' => $dyr
        ];

        // Print or log data for debugging
        //$case_result = $query_case->getRowArray();
        //print_r($data);

        // Return the view with data
        return view('Judicial/File_cover/pb_cover_info', $data);
    }
    
    // public function pb_cover_info()
    // { 
    //     $CoverPrintModel = new CoverPrintModel();
    //     $casetypeModel = new Casetype();
    //     $ct = $this->request->getPost('case_type');
    //     $cn = $this->request->getPost('case_number');
    //     $cy = $this->request->getPost('case_year');
    //      $dyr = $this->request->getPost('diary_year');
    //      $dn = $this->request->getPost('diary_number'); 
    //     $db = \Config\Database::connect(); // Connect to the database

    //     $builder = $db->table('main');  
    //     $builder->select('section_id, dacode, diary_no_rec_date, fil_dt, pet_name, res_name, pno, rno, fil_no, casetype_id');
    //     $builder->where('diary_no', $dn);
    //      $query = $builder->get();
    //     $r_get_da_sec = $query->getResultArray(); 
    //      // Get dacode from the main case details
    //     $dacode = $r_get_da_sec[0]['dacode'] ?? null;
    //     // Fetch the section name from the 'users' and 'usersection' tables
    //     $section_name = '';
    //     if ($dacode) {
    //         $builder_sec = $db->table('master.users a');
    //         $builder_sec->select('b.section_name')
    //                     ->join('master.usersection b', 'a.section = b.id')
    //                     ->where('a.usercode', $dacode)
    //                     ->where('b.display', 'Y');
    //         $query_sec = $builder_sec->get();
    //         $section_result = $query_sec->getRowArray();

    //         $section_name = $section_result['section_name'] ?? '';
    //     }
    //     else
    //     {
    //         $builder_sec = $db->table('usersection');
    //         $builder_sec->select('section_name')
    //                     ->where('id', $r_get_da_sec['section_id'])
    //                     ->where('display', 'Y');
    //         $query_sec = $builder_sec->get();
    //         $section_result = $query_sec->getRowArray();
    //         $section_name = $section_result['section_name'] ?? '';
    //     }
    //      // Fetch case type details
    //         $fil_no_yr = '';
    //         $c_code = '';
    //         // if (!empty($r_get_da_sec[0]['fil_no'])) {
    //         //     $c_code = substr($r_get_da_sec[0]['fil_no'], 0, 2);
    //         //     $fil_no_yr = ' ' . substr($r_get_da_sec[0]['fil_no'], 3) . '/' . substr($r_get_da_sec[0]['fil_dt'], 0, 4);
    //         // } else {
    //             $c_code = $r_get_da_sec[0]['casetype_id'];
    //         // }
    //     // Get case name and nature from the 'casetype' table
    //         $builder_case = $db->table('master.casetype');
    //         $builder_case->select('casename, nature')
    //                     ->where('casecode', $c_code)
    //                     ->where('display', 'Y');
    //                     echo $builder_case->getCompiledSelect();
    //         $query_case = $builder_case->get();
    //         $case_result = $query_case->getRowArray();
    //         $r_c_type = $case_result['casename'] ?? '';
    //         $r_nature = $case_result['nature'] ?? '';
    //         // Determine civil or criminal nature
    //         $c_r = '';
    //         $ia_crmp = '';
    //         if ($r_nature == 'C') {
    //             $c_r = "Civil";
    //             $ia_crmp = "I.A.No.";
    //         } elseif ($r_nature == 'R') {
    //             $c_r = "Criminal";
    //             $ia_crmp = "Cr.M.P.No.";
    //         }

    //         // Prepare data to pass to the view
    //     //////////
    //     $data = [
    //         'r_get_da_sec' => $r_get_da_sec[0],
    //         'section_name' => $section_name,
    //         'fil_no_yr' => $fil_no_yr,
    //         'c_r' => $c_r,
    //         'ia_crmp' => $ia_crmp,
    //         'dn' =>  $dn,
    //         'dyr' => $dyr
    //     ]; 
    //     ////////
    //     print_r($data);
    //     return view('Judicial/File_cover/pb_cover_info', $data);
    // }
   
}
