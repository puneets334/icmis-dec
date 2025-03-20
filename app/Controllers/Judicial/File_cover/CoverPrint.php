<?php

namespace App\Controllers\Judicial\File_cover;

use App\Controllers\BaseController;
use App\Models\Casetype;
use App\Models\Common\Dropdown_list_model;
use App\Models\Judicial\FileCover\CoverPrintModel;

class CoverPrint extends BaseController
{
    public $diary_no;
    public $Casetype;
    public $CaseAdd;
    protected $coverPrintModel;
    protected $fileHeadingDetails;
    public $Dropdown_list_model;

    function __construct()
    {
        $this->Casetype = new Casetype();
        $this->coverPrintModel = new CoverPrintModel();
        $this->Dropdown_list_model = new Dropdown_list_model();
    }
    // public function index()
    // {
    //     $usercode = session()->get('login')['usercode'];
    //     $userTypeString = getUser_dpdg_full_2($usercode);
    //     $userType = explode('~', $userTypeString);
    //     if (($userType[0] != 1 && $userType[0] != 57 && $userType[0] != 3 && $userType[0] != 4) && ($userType[6] != 450)) {
    //         echo "YOU ARE NOT AUTHORISED";
    //         exit();
    //     }
    //     $data = [
    //         // 'caseTypes' => $caseTypes,
    //         'session_diary_no' => session()->get('session_diary_no'),
    //         'session_diary_yr' => session()->get('session_diary_yr'),
    //     ];
    //     $data['formAction'] = 'Judicial/File_cover/CoverPrint/handlePostRequest';
    //     return view('Judicial/File_cover/cover_print', $data);
    // }
    public function index()
    {
        $usercode = session()->get('login')['usercode'];
        $userTypeString = getUser_dpdg_full_2($usercode);
        //($userTypeString);

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
            'session_diary_no' => session()->get('session_diary_no'),
            'session_diary_yr' => session()->get('session_diary_yr'),
        ];
        $data['formAction'] = 'Judicial/File_cover/CoverPrint/handlePostRequest';

        return view('Judicial/File_cover/cover_print', $data);
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

        // pr($get_main_table);
        
        // Reassign Variables
        $dn = $diary_number;
        $dyr = $diary_year;
        $diaryNo = $get_main_table['diary_no'];
        $r_get_da_sec[0] = $get_main_table;

        $ia_court_details = $this->coverPrintModel->getIADetails($dn,$dyr);
        $aor_cor_details = $this->coverPrintModel->getAOR_COR_Details($dn,$dyr);  
        //echo "<pre>";print_r($aor_cor_details);die;

        // Call the function to get the lower court details
        //$lower_court = $this->coverPrintModel->getLowerCourtDetails($dn);
        $lower_court = $this->coverPrintModel->getLowerCourtDetails($dn,$dyr);
        //echo "<pre>";print_r($lower_court);die; 
        $lower_case_no = '';
        $bench = '';
        $chk_di_bn = '';
        $state = '';
        $chk_state = '';
        $impugnedOrderDate = '';

        // Process the lower court details
        foreach ($lower_court as $court) {
            $skey = $court['type_sname'];
            $lct_caseno = $court['lct_caseno'];
            $lct_caseyear = $court['lct_caseyear'];

            if ($lower_case_no == '') {
                $lower_case_no = ' dated <b>' . date('d-m-Y', strtotime($court['lct_dec_dt'])) . '</b> in <b>' . $skey . '-' . $lct_caseno . '-' . $lct_caseyear . '</b>';
                $chk_di_bn = $court['agency_name'];
                $bench = trim($court['agency_name']);
                $bench = rtrim($bench, ',');
                $chk_state = $state = $court['Name'];
            } else {
                $lower_case_no .= ', dated <b>' . date('d-m-Y', strtotime($court['lct_dec_dt'])) . '</b> in <b>' . $skey . '-' . $lct_caseno . '-' . $lct_caseyear . '</b>';
                if ($chk_di_bn != $court['agency_name']) {
                    $bench .= ', ' . trim($court['agency_name']);
                    $bench = rtrim($bench, ',');
                }
                if ($chk_state != $court['Name']) {
                    $state .= ', ' . $court['Name'];
                }
            }
            $judgement_dt = $new_date = date('dS F, Y', strtotime($court['lct_dec_dt']));
            $s_name = "in ".$court['type_sname']." No.".$court['lct_caseno']." of ".$court['lct_caseyear'];
            $impugnedOrderDate = $judgement_dt.' '.$s_name;
        }
        $fileHeading = $this->fileHeadingDetails($r_get_da_sec[0]);
        //$getLowerCourtConct = lower_court_conct($diaryNo);
        $getdairyRecDate = get_diary_rec_date($diaryNo);

        $data = [
            'diary_no' => $diaryNo,
            'r_get_da_sec' => $r_get_da_sec[0],
            'dn' => $dn,
            'dyr' => $dyr,
            'state' => $state,
            'bench' => $bench,
            'fileHeading' => $fileHeading,
            'getdairyRecDate' => $getdairyRecDate,
            'impugnedOrderDate'=>$impugnedOrderDate,
            'ia_court_details'=>$ia_court_details,
            'aor_cor_details'=>$aor_cor_details,
            'caseInfo'=>"",
        ];
        //print_r($data);
        return view('Judicial/File_cover/cover_print_info', $data);
    }

    private function fileHeadingDetails($data)
    {
        $db = \Config\Database::connect();
        $fil_no_yr = '';
        if ($data['fil_no'] != '' && $data['fil_no'] != NULL) {
            // Extract parts of the fil_no
            $c_code = substr($data['fil_no'], 0, 2);
            $fil_no_yr = ' ' . substr($data['fil_no'], 3) . '/' . substr($data['fil_dt'], 0, 4);
        } else {
            $c_code = $data['casetype_id'];
        }

        // Using CodeIgniter Query Builder to fetch case details
        $caseTypeDetails = $db->table('master.casetype')
            ->select('casename, nature')
            ->where('casecode', $c_code)
            ->where('display', 'Y')
            ->get()
            ->getRowArray();

        // Check if case type details were found
        if ($caseTypeDetails) {
            $r_c_type = $caseTypeDetails['casename'];
            $r_nature = $caseTypeDetails['nature'];
        } else {
            $r_c_type = '';
            $r_nature = '';
        }

        // Initialize case-related variables
        $c_r = '';
        $ia_crmp = '';

        // Set case type based on the nature
        if ($r_nature == 'C') {
            $c_r = "Civil";
            $ia_crmp = "I.A.No.";
        }
        if ($r_nature == 'R') {
            $c_r = "Criminal";
            $ia_crmp = "Cr.M.P.No.";
        }

        // Prepare the heading
        $heading = "<b>($c_r Appellate Jurisdiction)</b>";

        // Return the heading
        return $heading;
    }
}
