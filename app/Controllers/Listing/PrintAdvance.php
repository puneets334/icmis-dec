<?php

namespace App\Controllers\Listing;

use App\Controllers\BaseController;
use App\Models\Listing\PrintAdvanceModel;
use App\Models\Listing\Heardt;
use App\Models\Listing\SectionListModel;
use App\Models\Listing\AllocationTp;
use Mpdf\Mpdf;


class PrintAdvance extends BaseController
{
    protected $PrintAdvanceModel;
    protected $SectionListModel;
    protected $AllocationTp;

    protected $Heardt;


    function __construct()
    {
        $this->PrintAdvanceModel = new PrintAdvanceModel();
        $this->Heardt = new Heardt();
        $this->AllocationTp = new AllocationTp();
        $this->SectionListModel = new SectionListModel();
    }

    public function advance_vacation_list()
    {
        return view('Listing/print_advance/advance_vacation_list_print');
    }

    public function elimination_list()
    {
        return view('Listing/print_advance/elimination_list_print');
    }

    public function sectionWise()
    {
        $data['listingDates']  = $this->Heardt->getListingDatesMV1();
        $data['benches'] = $this->Heardt->getBenchJudges();
        $data['f_listorder'] = $this->PrintAdvanceModel->getFListOrder();
        $data['userSection'] = $this->PrintAdvanceModel->getUserSection();

        return view('Listing/print_advance/section_wise', $data);
    }

    public function get_cause_list_section()
    {
        $request = \Config\Services::request();
        $list_dt = date('d-m-Y', strtotime($request->getPost('list_dt')));
        $mainhead = $request->getPost('mainhead');
        $lp = $request->getPost('lp');
        $courtno = $request->getPost('courtno');
        $board_type = $request->getPost('board_type');
        $orderby = $request->getPost('orderby');
        $sec_id = $request->getPost('sec_id');
        $main_suppl = $request->getPost('main_suppl');

        // **Session Data**
        $session = session()->get('login');
        $ucode = $session['usercode'];
        $usertype = $session['usertype'];
        $section1 = $session['section'];

        // **Define Hearing Type**
        $hearing_types = [
            'M' => "Miscellaneous Hearing",
            'F' => "Regular Hearing",
            'L' => "Lok Adalat"
        ];
        // if ($_POST['main_suppl'] == "0") {
        //     $main_suppl = "";
        // } else {
        //     $main_suppl = "AND h.main_supp_flag = '" . $_POST['main_suppl'] . "'";
        //     if ($_POST['main_suppl'] == "1") {
        //         $main_supl_head = "Main List";
        //     }
        //     if ($_POST['main_suppl'] == "2") {
        //         $main_supl_head = "Supplimentary List";
        //     }
        // }
        $main_suppl_types = [
            '0' => "",
            '1' => "Main List",
            '2' => "Supplimentary List"
        ];
        $data['list_dt'] = $list_dt;
        $data['mainhead_descri'] = $hearing_types[$mainhead] ?? '';
        $data['main_suppl_descri'] = $main_suppl_types[$main_suppl] ?? '';

        // **Query Condition Setup**
        $lp_condition = (!empty($lp) && $lp != "all") ? "h.listorder = '$lp'" : "";
        $main_suppl_condition = (!empty($main_suppl) && $main_suppl != "0") ? "AND h.main_supp_flag = '$main_suppl'" : "";
        $court_condition = (!empty($courtno) && $courtno != "0") ? "AND r.courtno = '$courtno'" : "";



        // **Set Sorting Order**
        /*$order_by_column = match ($orderby) {
            "1" => "r.courtno, ",
            "2" => "us.id, ",
            default => ""
        };*/

        // **Section Filtering**
        $sec_condition = "";
        $sec_id2 = "";
        if ($sec_id == "0") {
            $sec_id = "";
        } else {
            $sql_sec_name = $this->PrintAdvanceModel->getUserSection($sec_id);
            $sec_name = $sql_sec_name[0]['section_name'] ?? '';
            $sec_id = "AND (us.id ='$sec_id' OR tentative_section(h.diary_no) = '$sec_name')";

            $sec_id2 = "AND us.id IS NOT NULL";
        }

        // **Get User Section Details**
        $sql_sec_name = $this->PrintAdvanceModel->getUserSection($section1);

        //sectionDb for $session['section']; New Logic
        //$sectionDb = $sql_sec_name[0]['id'] ?? '';

        // **User Access Conditions**
        $mdacode = "";

        if ($usertype == '14' && $section1 != '77') {
            // **Get All Users in Same Section**
            $getUserDA = $this->PrintAdvanceModel->getUserDA($ucode);
            //pr($getUserDA);
            $all_da = $getUserDA->allda ?? '';
            if (!empty($all_da)) {
                $mdacode = "AND (m.dacode IN ($all_da) OR m.dacode = 0)";
            }
        } elseif (in_array($usertype, ['3', '4', '6', '9']) && $section1 != '77') {
            // **Get User Mapped Sections**

            $getUserMapped = $this->PrintAdvanceModel->getUserMappedSections($ucode);

            $uempid = $getUserMapped->empid ?? '';

            if ($uempid) {
                // **Check if Mapping Exists**
                $exists = $this->PrintAdvanceModel->getSectionAndUserData($uempid);

                if (!empty($exists)) {
                    $mdacode = $exists['mdacode'];
                    $sec_condition = $exists['sec_condition'];
                }
            }
        } elseif (in_array($usertype, ['17', '50', '51'])) {
            $mdacode = "AND m.dacode = '$ucode'";
        }
        // **Set Default Values**
        $cl_print_jo = "";
        $cl_print_jo2 = "";
        $section = "";

        // **Condition for Specific User Codes (1 & 469)**
        if ($ucode == '1' || $ucode == '469') {
            // If `ucode` is 1 or 469, do not set `cl_print_jo`
            $cl_print_jo2 = "";
        } else {
            // For other users, apply the condition
            $cl_print_jo = "IF('$mainhead' = 'F', p.id IS NOT NULL, 1=1) AND ";
        }

        // **Section Condition Based on User Type**
        if ($ucode == '1') {
            $section = ''; // Admin or unrestricted access



        } elseif (in_array($usertype, ['3', '4', '6', '9']) && $section1 != '77') {

            $getUserMapped = $this->PrintAdvanceModel->getUserMappedSections($ucode);

            $uempid = $getUserMapped->empid ?? '';

            if ($uempid) {
                // **Check if Mapping Exists**
                $exists = $this->PrintAdvanceModel->getSectionAndUserData($uempid);

                if (!empty($exists)) {
                    $mdacode = $exists['mdacode'];
                    $sec_condition = $exists['sec_condition'];
                    $sectionIds = $exists['sectionIds'];
                    $sectionNames = $exists['sectionNames'];
                    $allUsers = $exists['allUsers'];
                } else {
                    $sectionNames = "";
                }
            }

            // **If user is in specific types and section is not 77**
            $section = "AND (us.id IN ('$allUsers') OR tentative_section(h.diary_no) IN ('$sectionNames'))";
        } else {
            // **Default section condition**
            $section = "AND (us.id = '$section1' OR tentative_section(h.diary_no) = '$sec_name')";
        }

        $data['getCases'] = $this->PrintAdvanceModel->getCauseListSection($list_dt, $mainhead, $main_suppl_condition, $lp_condition, $board_type, $court_condition, $sec_id, $sec_id2, $section, $orderby, $cl_print_jo);

        if (!empty($data['getCases'])) {
            $today = date('Y-m-d');
            $case_type = array(39, 9, 10, 19, 20, 25, 26);

            // Process all transformations in a single array_map
            $data['getCases'] = array_map(function ($case) use ($today, $case_type) {
                $diary_no = $case['diary_no'];

                // Fetch advocate details
                $case['advocate'] = $this->Heardt->get_advocate_detailsWeekly($diary_no);

                // Ensure diary number exists before checking listing conditions
                if (!empty($diary_no) && strtotime($case['diary_no_rec_date']) >= strtotime('2017-05-08')) {
                    $times_listed = $this->PrintAdvanceModel->no_of_times_listed($diary_no);

                    $last_listed = $this->PrintAdvanceModel->last_listed_date($diary_no, 0);

                    // Check conditions to skip cases
                    if ($times_listed == 0 && !in_array($case['casetype_id'], $case_type) && $case['board_type'] != 'R' && $case['board_type'] != 'C') {
                        return null;
                    } elseif ($times_listed == 1 && !in_array($case['casetype_id'], $case_type) && $case['board_type'] != 'R' && $case['board_type'] != 'C') {
                        if (!empty($last_listed[1]) && strtotime($last_listed[1]) >= strtotime($today)) {
                            return null;
                        }
                    }
                }

                // Add Section 10 details
                if ((empty($case['section_name']) || $case['section_name'] === null) &&
                    !empty($case['ref_agency_state_id']) && $case['ref_agency_state_id'] != 0
                ) {

                    // Determine $ten_reg_yr based on active_reg_year or diary_no_rec_date
                    if (!empty($case['active_reg_year']) && $case['active_reg_year'] != 0) {
                        $ten_reg_yr = $case['active_reg_year'];
                    } else {
                        $ten_reg_yr = date('Y', strtotime($case['diary_no_rec_date']));
                    }

                    // Determine $casetype_displ based on active_casetype_id or casetype_id
                    if (!empty($case['active_casetype_id']) && $case['active_casetype_id'] != 0) {
                        $casetype_displ = $case['active_casetype_id'];
                    } elseif (!empty($case['casetype_id']) && $case['casetype_id'] != 0) {
                        $casetype_displ = $case['casetype_id'];
                    } else {
                        $casetype_displ = null; // Fallback in case both are missing
                    }

                    $case['sectionTen'] = $this->PrintAdvanceModel->getSectionTen($casetype_displ, $ten_reg_yr, $case['ref_agency_state_id']);
                }

                // Add Fil Trap details
                $case['filTrap'] = $this->PrintAdvanceModel->getSectionFilTrap($diary_no);

                // Add Office Report details
                $request = \Config\Services::request();
                $list_dt = date('d-m-Y', strtotime($request->getPost('list_dt')));
                $case['ord'] = $this->PrintAdvanceModel->getOfficeReportDetails($diary_no, $list_dt);

                return $case;
            }, $data['getCases']);

            // Remove null values (cases that were skipped)
            $data['getCases'] = array_filter($data['getCases']);
        }

        // Load view
        return view('Listing/print_advance/get_cause_list_section', $data);
    }

    public function get_cause_list_vacation()
    {

        $request = \Config\Services::request();
        $v_year =  $request->getPost('vac_yr');
        $vac_record =  $request->getPost('vac_record');
        $results = $this->PrintAdvanceModel->get_main_details($vac_record); //pr($results);

        $advocatesData = [];
        foreach ($results as $result) {
            $diary_no = $result['diary_no']; // Get the diary number

            // Fetch advocate details
            $advocateDetails = $this->PrintAdvanceModel->get_advocate($diary_no);

            // Ensure that get_advocate() returns a result
            if (!empty($advocateDetails)) {
                $advocatesData[] = [
                    'diary_no' => $diary_no,
                    'radvname' => $advocateDetails["r_n"] ?? '',
                    'padvname' => $advocateDetails["p_n"] ?? '',
                    'impldname' => $advocateDetails["i_n"] ?? '',
                    'intervenorname' => $advocateDetails["intervenor"] ?? ''
                ];
            } else {
                $advocatesData[] = [
                    'diary_no' => $diary_no,
                    'radvname' => '',
                    'padvname' => '',
                    'impldname' => '',
                    'intervenorname' => ''
                ];
            }
        }

        $tentativeSecData = [];

        foreach ($results as $result) {
            $diary_no = $result['diary_no'];

            $tentativeSection = $this->PrintAdvanceModel->getSectionName($diary_no);

            $tentativeSecData[] = [
                'diary_no' => $diary_no,
                'section_name' => $tentativeSection ?? '',
            ];
        }

        $rs_dc = [];

        foreach ($results as $result) {
            $diary_no = $result['diary_no'];

            $rs_dc = $this->PrintAdvanceModel->doccode($diary_no);
            if (!empty($rs_dc)) {
                $rs_dc[] = [
                    'diary_no' => $diary_no,
                    'docnum' => $rs_dc["docnum"] ?? '',
                    'docyear' => $rs_dc["docyear"] ?? '',
                    'docdesp' => $rs_dc["docdesp"] ?? '',
                ];
            } else {
                // Handle cases where no result is found
                $rs_dc[] = [
                    'diary_no' => $diary_no,
                    'docnum' => '',
                    'docyear' => '',
                    'docdesp' => '',
                ];
            }
        }

        $mulCategoryData  = [];

        foreach ($results as $result) {
            $diary_no = $result['diary_no'];

            $mulCategoryData  = $this->PrintAdvanceModel->get_mul_category($diary_no);
            // Check if the result is valid
            if (!empty($mulCategoryData)) {


                $mulCategoryData[] = [
                    'diary_no' => $diary_no,
                    'category_sc_old' => $res_sm->category_sc_old ?? '', // Access stdClass property
                ];
            } else {
                // Handle cases where no result is found
                $mulCategoryData[] = [
                    'diary_no' => $diary_no,
                    'category_sc_old' => '', // Default to empty string
                ];
            }
        }

        $subheading  = [];

        foreach ($results as $result) {
            $diary_no = $result['diary_no'];

            $subheadingData  = $this->PrintAdvanceModel->get_subheading($diary_no);

            // Check if the result is valid
            if (!empty($subheadingData)) {
                $subheading[] = [
                    'diary_no' => $diary_no,
                    'stagecode' => $subheadingData->stagecode ?? '', // Access stdClass property
                    'stagename' => $subheadingData->stagename ?? '', // Access stdClass property
                    'stagename_hindi' => $subheadingData->stagename_hindi ?? '', // Access stdClass property
                ];
            } else {
                // Handle cases where no result is found
                $subheading[] = [
                    'diary_no' => $diary_no,
                    'stagecode' => '', // Default to empty string
                    'stagename' => '', // Default to empty string
                    'stagename_hindi' => '', // Default to empty string
                ];
            }
        }

        $lowerctData  = [];

        foreach ($results as $result) {
            $diary_no = $result['diary_no'];

            $lowerctResult = $this->PrintAdvanceModel->get_lowerct($diary_no);

            // Check if the result is valid
            if (!empty($lowerctResult)) {

                $lowerctData[] = [
                    'diary_no' => $diary_no,
                    'lct_dec_dt' => $lowerctResult->lct_dec_dt ?? '', // Access stdClass property
                    'lct_caseno' => $lowerctResult->lct_caseno ?? '', // Access stdClass property
                    'lct_caseyear' => $lowerctResult->lct_caseyear ?? '', // Access stdClass property
                    'type_sname' => $lowerctResult->type_sname ?? '', // Access stdClass property
                ];
            } else {
                // Handle cases where no result is found
                $lowerctData[] = [
                    'diary_no' => $diary_no,
                    'lct_dec_dt' => '', // Default to empty string
                    'lct_caseno' => '', // Default to empty string
                    'lct_caseyear' => '', // Default to empty string
                    'type_sname' => '', // Default to empty string
                ];
            }
        }


        $str_brdrem  = [];

        foreach ($results as $result) {
            $diary_no = $result['diary_no'];

            $brdremData = $this->PrintAdvanceModel->get_cl_brd_remark($diary_no);
            // Check if the result is valid
            if (!empty($brdremData)) {

                $str_brdrem[] = [
                    'diary_no' => $diary_no,
                    'remark' => $brdremData->remark ?? '', // Access stdClass property
                ];
            } else {
                // Handle cases where no result is found
                $str_brdrem[] = [
                    'diary_no' => $diary_no,
                    'remark' => '', // Access stdClass property
                ];
            }
        }

        $data['results'] = $results;
        $data['str_brdrem'] = $str_brdrem;
        $data['v_year'] =  $request->getPost('vac_yr');
        $data['advocatesData'] =  $advocatesData;
        $data['tentativeSecData'] =  $tentativeSecData;
        $data['rs_dc'] =  $rs_dc;
        $data['rs_lct'] =  $lowerctData;
        $data['mulCategoryData'] =  $mulCategoryData;
        $data['subheading'] =  $subheading;

        return view('Listing/print_advance/get_cause_list_vacation', $data);
    }

    public function clPrintSaveVactions()
    {
        $request = \Config\Services::request();

        // Fetch the POST data
        $v_year = $request->getPost('vac_yr');
        //$prtContent = $request->getPost('encprtContent');
        $encprtContent = json_decode($request->getPost('encprtContent'), true); // Decodes to HTML
        $usercode = session()->get('login')['usercode'];

        // Validate input
        if (empty($encprtContent) || empty($v_year)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Invalid input data.'
            ]);
        }
        // Remove the div with the "ignore_in_print" class
        $prtContent = $this->removeIgnoreInPrintDiv($encprtContent);

        // Prepare the save path
        $savePath = WRITEPATH . 'judgment/cl/vacation/' . $v_year . '/';
        if (!is_dir($savePath)) {
            mkdir($savePath, 0777, true); // Create directory if it doesn't exist
        }

        $fileName = 'AV_' . $v_year . '.pdf';
        $filePath = $savePath . $fileName;

        ini_set('max_execution_time', '300');
        ini_set("pcre.backtrack_limit", "5000000");
        try {
            // Initialize Mpdf
            $mpdf = new Mpdf();
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->SetHTMLFooter('<div style="text-align: center; font-size: 12px;">Page {PAGENO} of {nbpg}</div>');
            //$mpdf->WriteHTML($prtContent);
            $chunks = explode("chunk", $prtContent);
            foreach ($chunks as $key => $val) {
                $mpdf->WriteHTML($val);
            }
            $mpdf->Output($filePath, 'F'); // Save the file to the specified path

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'PDF generated successfully!',
                //For lOcal Dir
                //'filePath' => $filePath
                // For Live Server dir
                'filePath' => base_url('writable/judgment/cl/vacation/' . $fileName)
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Failed to generate PDF: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Removes the div with the "ignore_in_print" class from the HTML content.
     */
    private function removeIgnoreInPrintDiv(string $html): string
    {
        // Load HTML into DOMDocument
        $dom = new \DOMDocument();
        @$dom->loadHTML($html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD); // Suppress warnings for malformed HTML

        // Find elements with the "ignore_in_print" class
        $xpath = new \DOMXPath($dom);
        $nodes = $xpath->query('//*[contains(@class, "ignore_in_print")]');

        // Remove the matching nodes
        foreach ($nodes as $node) {
            $node->parentNode->removeChild($node);
        }

        // Return the cleaned HTML
        return $dom->saveHTML();
    }
    /**
     * All old condition class from the HTML content.
     */

    public function getMainHead($row, $mainhead)
    {
        if ($mainhead == 'M') {
            return $row['board_type'] == 'C' ? "CHAMBER MATTERS" : "MISCELLANEOUS HEARING";
        } elseif ($mainhead == 'F') {
            return "REGULAR HEARING";
        } elseif ($mainhead == 'L') {
            return "LOK ADALAT HEARING";
        } elseif ($mainhead == 'S') {
            return "MEDIATION HEARING";
        }
        return "";
    }

    public function get_cause_list_elimination()
    {
        $request = service('request');
        $session = session();

        $raw_list_dt = $request->getPost('list_dt');
        if (!$raw_list_dt || !strtotime($raw_list_dt)) {
            return redirect()->back()->with('error', 'Invalid date format');
        }

        $list_dt = date('Y-m-d', strtotime($raw_list_dt));
        $mainhead = $request->getPost('mainhead');
        $ucode = $session->get('dcmis_user_idd') ?: ($session->get('login')['usercode'] ?? null);

        $data = [
            'list_dt' => $list_dt,
            'mainhead' => $mainhead,
            'psrno' => 1,
            'mnhead_print_once' => 1,
            'ucode' => $ucode,
            'list_year' => date('Y', strtotime($list_dt)),
            'jcd_rp'  => '',
            'subheading_rep' => '0',
        ];

        $data['getCases'] = $this->PrintAdvanceModel->getEliminationListPrint($list_dt, $mainhead, $sectionName = '');

        $data['getCases'] = array_map(function ($case) {
            $case['advocate'] = $this->Heardt->get_advocate_detailsWeekly($case['diary_no']);
            return $case;
        }, $data['getCases']);

        $data['isPrinted'] = $this->PrintAdvanceModel->f_cl_is_printed($list_dt, 0, $mainhead, 0);
        $data['cl_content'] = $this->PrintAdvanceModel->getClPrintedElimination($list_dt, $part_no = 0, $mainhead);
        return view('Listing/print_advance/get_causelist_elimination', $data);
    }

    public function cl_print_save_elimination()
    {
        $request = service('request');
        $session = session();
        $db = \Config\Database::connect();
        // Validate and sanitize input
        $raw_list_dt = $request->getPost('list_dt');
        if (!$raw_list_dt || !strtotime($raw_list_dt)) {
            return redirect()->back()->with('error', 'Invalid date format');
        }

        $list_dt = date('Y-m-d', strtotime($raw_list_dt));
        $mainhead = $request->getPost('mainhead');
        $prtContent = $request->getPost('prtContent');

        if (!$mainhead || !$prtContent) {
            return redirect()->back()->with('error', 'Missing required fields');
        }

        // Get user session data
        $ucode = $session->get('dcmis_user_idd') ?? $session->get('login')['usercode'] ?? null;

        if (!$ucode) {
            return redirect()->back()->with('error', 'User not authenticated');
        }

        // Encode content and replace image path
        $cntt = base64_encode($prtContent);
        $logo_url = base_url('images/scilogo.png');
        $pdf_cont = str_replace("http://localhost/icmis/public/images/scilogo.png", $logo_url, $prtContent);

        // File paths
        $file_name = "Elimination_List_{$mainhead}_{$list_dt}";
        $path_dir = WRITEPATH . "judgment/cl/elimination/{$list_dt}/";

        if (!is_dir($path_dir)) {
            mkdir($path_dir, 0777, true);
        }

        $data_file = $path_dir . $file_name . ".html";
        $data_file1 = $path_dir . $file_name . ".pdf";

        // Save HTML file
        file_put_contents($data_file, $pdf_cont);

        $inserted_id =  $this->PrintAdvanceModel->clPrintedInsert($list_dt, $mainhead, $ucode);

        if (!$inserted_id) {
            return redirect()->back()->with('error', 'Record Save error while inserting into New Record');
        }

        $clp_id = $db->insertID();

        // Insert into cl_text_save table

        $this->PrintAdvanceModel->clTextSaveInsert($clp_id, $cntt, $ucode);

        // Generate PDF using mPDF
        $mpdf = new \Mpdf\Mpdf();
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->showImageErrors = true;
        $mpdf->WriteHTML($pdf_cont);
        $mpdf->Output($data_file1, \Mpdf\Output\Destination::FILE);

        // Process section-wise elimination list
        $this->publishEliminationListSectionWise($list_dt, $mainhead);

        return "Cause List Ported/Published Successfully.";
    }

    /**
     * Generate and save section-wise elimination lists.
     */
    private function publishEliminationListSectionWise($list_dt, $mainhead)
    {
        $sections = $this->PrintAdvanceModel->getUserSection();
        //pr($sections);
        foreach ($sections as $row) {
            $section_name = $row['section_name'];
            $content = '';
            $content = $this->getSectionWiseContent($list_dt, $mainhead, $section_name);

            if (!$content) {
                continue;
            }

            $logo_url = base_url('images/scilogo.png');
            $pdf_cont = str_replace("http://localhost/icmis/public/images/scilogo.png", $logo_url, $content);

            // File paths
            $file_name = "Elimination_List_{$mainhead}_Section-{$section_name}_{$list_dt}";
            $path_dir = WRITEPATH . "judgment/cl/elimination/{$list_dt}/";

            if (!is_dir($path_dir)) {
                mkdir($path_dir, 0777, true);
            }

            $data_file = $path_dir . $file_name . ".html";
            $data_file1 = $path_dir . $file_name . ".pdf";

            // Save HTML file
            file_put_contents($data_file, $pdf_cont);

            // Generate PDF
            $mpdf = new \Mpdf\Mpdf();
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->SetHTMLFooter('<div style="text-align: center;font-size: 12px;">{PAGENO} of {nbpg}</div>');
            $mpdf->showImageErrors = true;
            $mpdf->WriteHTML($pdf_cont);
            $mpdf->Output($data_file1, \Mpdf\Output\Destination::FILE);
        }
    }

    private function getSectionWiseContent($list_dt, $mainhead, $section_name)
    {
        $outString = "<table border='0' width='100%' style='font-size:12px; text-align: left; background: #ffffff;' cellspacing=0>
        <thead>
        <tr>
            <th colspan='4' style='text-align: center;'>";

        // Fixing incorrect mainhead conditions
        $mainheadTitle = ($mainhead == 'M') ? "Misc." : (($mainhead == 'F') ? "Regular" : "");
        $outString .= "{$mainheadTitle} ELIMINATION LIST DATED: " . date('d-m-Y', strtotime($list_dt)) . " (Section {$section_name})</th></tr></thead>";

        // Fetching case data
        $getCases = $this->PrintAdvanceModel->getEliminationListPrint($list_dt, $mainhead, $section_name);

        // Attach advocate details to each case
        foreach ($getCases as &$case) {
            $case['advocate'] = $this->Heardt->get_advocate_detailsWeekly($case['diary_no']);
        }
        unset($case); // Avoid accidental reference modifications

        // Define initial serial number
        $serialNo = 1;
        $headerPrinted = false;
        foreach ($getCases as $row) {
            // Define `is_connected` status
            $is_connected = ($row['diary_no'] != $row['conn_key'] && $row['conn_key'] != 0 && $row['listed'] == 1)
                ? "<span style='color:red;'>Connected</span><br/>"
                : "";

            // If the case is connected, leave the serial number blank
            $printSerial = ($is_connected != '') ? "" : $serialNo++;

            // Format Case Number
            $caseNumber = $this->formatCaseNumber($row);

            // Fetch Advocate Details
            $advocateDetails = $this->getAdvocateNames($row['advocate']);

            // Fetch Petitioner & Respondent Names
            $petitionerName = $this->formatPartyName($row['pet_name'], $row['pno']);
            $respondentName = $this->formatPartyName($row['res_name'], $row['rno']);

            // Print table header once
            if (!$headerPrinted) {
                $outString .= "<tr style='font-weight: bold; background-color:#cccccc;'>
                    <td style='width:5%;'>SNo.</td>
                    <td style='width:20%;'>Case No.</td>
                    <td style='width:35%;'>Petitioner / Respondent</td>
                    <td style='width:40%;'>Petitioner/Respondent Advocate</td>
                </tr>";
                $headerPrinted = true;
            }

            // Build case row
            $outString .= "<tr>
                <td>{$printSerial}</td>
                <td rowspan='2'>{$is_connected}{$caseNumber}<br/>{$row['section_name']}</td>
                <td>{$petitionerName}</td>
                <td>{$advocateDetails['petitioner']}</td>
            </tr>
            <tr>
                <td></td>
                <td style='font-style: italic;'>Versus</td>
                <td style='font-style: italic;'></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>{$respondentName}</td>
                <td>{$advocateDetails['respondent']}";

            // If there are impleaded parties, add them
            if ($advocateDetails['impleaded']) {
                $outString .= "<br/>" . $advocateDetails['impleaded'];
            }

            $outString .= "</td></tr>";

            // Add remarks for "M" or "F" cases
            if (in_array($mainhead, ["M", "F"])) {
                $outString .= "<tr><td colspan='2'></td>
                    <td colspan='2' style='font-weight:bold; color:blue;'>";

                if (in_array($row['listorder'], ['4', '5'])) {
                    $outString .= "{" . $row['purpose'] . " for " . date('d-m-Y', strtotime($row['next_dt'])) . "} ";
                }

                $outString .= $this->getCaseRemarks($row['diary_no']) . "</td></tr>";
            }
        }

        $outString .= "</table>";
        return $outString;
    }

    private function formatCaseNumber($row)
    {
        if (empty($row['active_fil_no'])) {
            return "Diary No. " . substr_replace($row['diary_no'], '-', -4, 0);
        }

        $filno_array = explode("-", $row['active_fil_no']);
        $fil_no_print = ($filno_array[0] == $filno_array[1])
            ? ltrim($filno_array[0], '0')
            : ltrim($filno_array[0], '0') . "-" . ltrim($filno_array[1], '0');

        return "{$row['short_description']}-{$fil_no_print}/{$row['active_reg_year']}";
    }

    private function getAdvocateNames($advocate)
    {
        $padvname = "";
        $radvname = "";
        $impldname = "";

        if (!empty($advocate)) {
            //foreach ($advocates as $advocate) {
            $radvname .= $advocate["r_n"] . ", ";
            $padvname .= $advocate["p_n"] . ", ";
            $impldname .= $advocate["i_n"] . ", ";
            //}
        }

        return [
            'petitioner' => rtrim($padvname, ", "),
            'respondent' => rtrim($radvname, ", "),
            'impleaded'  => rtrim($impldname, ", ")
        ];
    }
    private function formatPartyName($name, $count)
    {
        if ($count == 2) {
            return "{$name} AND ANR.";
        } elseif ($count > 2) {
            return "{$name} AND ORS.";
        }
        return $name;
    }

    private function getCaseRemarks($diary_no)
    {
        return $this->PrintAdvanceModel->getClBrdRemark($diary_no);
    }



    public function freeze_un_freeze_list()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('heardt');
        $builder->select('next_dt')
            ->where('mainhead', 'M')
            ->where('next_dt >=', date('Y-m-d'))
            ->groupStart()
            ->where('main_supp_flag', '1')
            ->orWhere('main_supp_flag', '2')
            ->groupEnd()
            ->groupBy('next_dt');
        // ->orderBy('next_dt', 'ASC');
        $query = $builder->get();
        $result = $query->getResultArray();



        $data['listing_date'] = $result;
        return view('Listing/print_advance/freeze_un_freeze_list_print', $data);
    }

    public function get_cl_print_mainhead()
    {
        $request = \Config\Services::request();
        $db = \Config\Database::connect();

        $mainhead = $request->getPost('mainhead');
        $board_type = $request->getPost('board_type');

        $option = '';

        $m_f = null;
        if ($mainhead === 'M') {
            $m_f = '1';
        } elseif ($mainhead === 'F') {
            $m_f = '2';
        }

        $builder = $db->table('heardt c');
        $builder->select('c.next_dt')
            ->where('c.mainhead', $mainhead)
            ->where('c.next_dt >=', date('Y-m-d'))
            ->groupStart() // (c.main_supp_flag = '1' OR c.main_supp_flag = '2')
            ->where('c.main_supp_flag', '1')
            ->orWhere('c.main_supp_flag', '2')
            ->groupEnd()
            ->groupBy('c.next_dt')
            ->orderBy('c.next_dt', 'ASC');

        if ($board_type !== '0') {
            $builder->where('c.board_type', $board_type);
        }
        //echo $builder->getCompiledSelect(); die;
        // Get Data
        $query = $builder->get();
        $resultdata = $query->getResultArray();
        //echo $builder->getCompiledSelect(); die;

        //Generate `<option>` elements
        if (!empty($resultdata)) {
            $option .= '<option value="0" selected>SELECT</option>';
            foreach ($resultdata as $list) {
                $formattedDate = date("d-m-Y", strtotime($list['next_dt']));
                $option .= '<option value="' . $list['next_dt'] . '">' . $formattedDate . '</option>';
            }
        } else {
            $option .= '<option value="0" selected>EMPTY</option>';
        }

        return $option;
    }

    public function get_cl_freeze_partno()
    {

        $request = \Config\Services::request();
        $db = \Config\Database::connect();
        $option = '';
        $mainhead =  $request->getPost('mainhead');
        $board_type =  $request->getPost('board_type');
        $list_dt = $request->getPost('list_dt');
        $list_dts = date('Y-m-d', strtotime($list_dt));

        //$jud_ros = explode("|", $request->getPost('jud_ros'));
        //$roster_id = $jud_ros[1];

        if ($board_type == '0') {
            $board_type_in = "";
        } else {
            //$board_type_in = " and board_type = '$board_type'";
            $board_type_in = "board_type = '$board_type'";
        }



        // pr($sql);
        // $builder = $db->table('heardt');
        // $builder->select('clno')
        //     ->where('mainhead', $mainhead)
        //     ->where('next_dt', $list_dts)
        //     ->where('main_supp_flag', 1)
        //     ->orWhere('main_supp_flag', 2)
        //     ->groupBy('clno');
        // if ($board_type != '0') {
        //     $builder->where('board_type', $board_type);
        // }
        //$query = $builder->get();

        /*$sql = "SELECT clno FROM heardt 
        WHERE mainhead = '$mainhead' 
        AND next_dt ='$list_dt' $board_type_in 
        AND roster_id = '$roster_id' 
        AND (main_supp_flag = 1 or main_supp_flag = 2) 
        GROUP BY clno";*/
        //$sql="SELECT distinct clno FROM heardt WHERE mainhead = '$mainhead' AND next_dt ='$list_dts' $board_type_in and (main_supp_flag = 1 or main_supp_flag = 2) GROUP BY clno";
        //$query = $this->db->query($sql);
        //$res = $query->getResultArray();

        $builder = $db->table('heardt');
        $builder->distinct()->select('clno');
        $builder->where('mainhead', $mainhead);
        $builder->where('next_dt', $list_dts);
        $builder->whereIn('main_supp_flag', [1, 2]);
        if ($board_type_in) {
            $builder->where($board_type_in);
        }

        $builder->groupBy('clno');
        $query = $builder->get();
        $results = $query->getResultArray();

        //if (count($query->getResultArray()) > 0) {
        if (count($results) > 0) {
            $option .= '<option value="0" selected>SELECT</option>';
            foreach ($results as $row) {

                $option .= '<option value="' . esc($row['clno']) . '">' . esc($row['clno']) . '</option>';
            }
        } else {
            $option .= '<option value="0" selected>1 (empty)</option>';
        }
        return $option;
    }


    public function get_freeze_unfreeze()
    {
        $request = \Config\Services::request();
        $db = \Config\Database::connect();
        if (!empty(session()->get('dcmis_user_idd'))) {
            $ucode = session()->get('dcmis_user_idd');
        } else {
            $ucode = session()->get('login')['usercode'];
        }
        $list_dt = date('Y-m-d', strtotime($request->getPost('list_dt')));
        $mainhead = $request->getPost('mainhead');
        $part_no = $request->getPost('part_no');
        $board_type = $request->getPost('board_type');
        $option = '';

        $builder = $db->table('cl_freezed')
            ->where('next_dt', $list_dt)
            ->where('part', $part_no)
            ->where('m_f', $mainhead)
            ->where('display', 'Y');
        // if ($board_type != '0') {
        //     $builder->where('board_type',$board_type);
        // }
        $query = $builder->get();

        if (count($query->getResultArray()) > 0) {
            $option .= '<button class="btn btn-primary btnSubmit" data-action_type="u" name="unfreeze" type="button" id="unfreeze">UnFreeze</button>';
        } else {
            $option .= '<button class="btn btn-primary btnSubmit" data-action_type="f" name="freeze" type="button" id="freeze">Freeze</button>';
        }
        return $option;
    }

    // public function freeze_unfreeze_save()
    // {

    //     $request = \Config\Services::request();
    //     $db = \Config\Database::connect();
    //     // if (!empty(session()->get('dcmis_user_idd'))) {
    //     //     $ucode = session()->get('dcmis_user_idd');
    //     // } else {
    //     $ucode = session()->get('login')['usercode'];
    //     //}
    //     $list_dt = $request->getPost('list_dt');
    //     $mainhead = $request->getPost('mainhead');
    //     $part_no = $request->getPost('part_no');
    //     $board_type = $request->getPost('board_type');
    //     $action_type = $request->getPost('action_type');

    //     if ($action_type == 'f') {
    //         $builder = $db->table('cl_freezed')
    //             ->where('part', $part_no)
    //             ->where('m_f', $mainhead)
    //             ->where('display', 'Y');

    //         // Use a more robust check for $list_dt.  See explanation below.
    //         if ($list_dt !== null && $list_dt !== '') { // Or is_numeric($list_dt) if it should be a number.
    //             $builder->where('next_dt', $list_dt);
    //         }

    //         if ($board_type !== null && $board_type !== '') { // Or is_numeric($board_type) if it should be a number.
    //             $builder->where('board_type', $board_type);
    //         }

    //         $query = $builder->get();

    //         if (count($query->getResultArray()) === 0) { // Use getResultCount() for efficiency
    //             $insertdata = [
    //                 'next_dt' => $list_dt,
    //                 'm_f' => $mainhead,
    //                 'part' => $part_no,
    //                 'board_type' => $board_type,
    //                 'freezed_by' => $ucode,
    //                 'freezed_on' => date('Y-m-d H:i:s'),
    //                 'freezed_by_ip' => getenv('REMOTE_ADDR'), // Consider using a more reliable way to get IP
    //                 'unfreezed_by' => 0,
    //             ];
    //             // Important:  You've already built the query.  Don't reuse $builder for the insert.
    //             $insert_result = $db->table('cl_freezed')->insert($insertdata); // Create a new builder instance.

    //             if ($insert_result) { // Check if the insert was successful
    //                 return $this->response->setJSON(['message' => '<h3 class="bg-success p-2 text-center">Freezed Successfully</h3>']);
    //             } else {
    //                 // Handle insert error.  Log it!
    //                 log_message('error', 'Failed to insert data: ' . $db->error()); // Use CodeIgniter's logging
    //                 return $this->response->setJSON(['message' => '<h3 class="bg-danger p-2 text-center">Freezing Failed. Please Try Again.</h3>']); // More appropriate message
    //             }
    //         } else {
    //             return $this->response->setJSON(['message' => '<h3 class="bg-success p-2 text-center">Already freezed</h3>']);
    //         }
    //     } else if ($action_type == 'u') {
    //         $builder = $db->table('cl_freezed')
    //             // ->where('next_dt', $list_dt)
    //             ->where('part', $part_no)
    //             ->where('m_f', $mainhead)
    //             ->where('display', 'Y');
    //         if ($list_dt != 0) {
    //             $builder->where('next_dt', $list_dt);
    //         }
    //         if ($board_type != 0) {
    //             $builder->where('board_type', $board_type);
    //         }
    //         $query = $builder->get();
    //         if (count($query->getResultArray()) > 0) {

    //             $updatedata = [
    //                 'display' => 'N',
    //                 'unfreezed_by' => $ucode,
    //                 'unfreezed_on' => date('Y-m-d H:i:s'),
    //                 'unfreezed_by_ip' => getenv('REMOTE_ADDR'),
    //             ];

    //             $record_update = $builder->where('next_dt', $list_dt)
    //                 ->where('part', $part_no)
    //                 ->where('m_f', $mainhead)
    //                 ->where('board_type', $board_type)
    //                 ->where('display', 'Y')
    //                 ->set($updatedata)
    //                 ->update();

    //             return $this->response->setJSON(['message' => '<h3 class="bg-success p-2 text-center">Un-freezed Successfully</h3>']);
    //         } else {

    //             return $this->response->setJSON(['message' => '<h3 class="bg-warning p-2 text-center">Not freezed yet.</h3>']);
    //         }
    //     }
    // }

    public function freeze_unfreeze_save()
{
    $request = \Config\Services::request();
    $db = \Config\Database::connect();
    
    // Get the user code from session
    $ucode = session()->get('login')['usercode']; // Assuming usercode is stored in session under 'login'
    
    // Get the input parameters
    $list_dt = $request->getPost('list_dt');
    $mainhead = $request->getPost('mainhead');
    $part_no = $request->getPost('part_no');
    $board_type = $request->getPost('board_type');
    $action_type = $request->getPost('action_type');
    
    // Freeze action
    if ($action_type == 'f') {
        $builder = $db->table('cl_freezed')
            ->where('part', $part_no)
            ->where('m_f', $mainhead)
            ->where('display', 'Y');
        
        if ($list_dt !== null && $list_dt !== '') {
            $builder->where('next_dt', $list_dt);
        }
        
        if ($board_type !== null && $board_type !== '') {
            $builder->where('board_type', $board_type);
        }
        
        $query = $builder->get();
        
        if (count($query->getResultArray()) === 0) {
            // Insert freeze record if not already frozen
            $insertdata = [
                'next_dt' => $list_dt,
                'm_f' => $mainhead,
                'part' => $part_no,
                'board_type' => $board_type,
                'freezed_by' => $ucode,
                'freezed_on' => date('Y-m-d H:i:s'),  // Make sure freezed_on is not NULL
                'freezed_by_ip' => getenv('REMOTE_ADDR'),
                'unfreezed_by' => 0,  // Initially 0, since it's not yet unfrozen
                'unfreezed_on' => null, // Since it's not yet unfrozen, set to null
            ];
            
            $insert_result = $db->table('cl_freezed')->insert($insertdata);
            
            if ($insert_result) {
                return $this->response->setJSON(['message' => '<h3 class="bg-success p-2 text-center">Freezed Successfully</h3>']);
            } else {
                log_message('error', 'Failed to insert data: ' . $db->error());
                return $this->response->setJSON(['message' => '<h3 class="bg-danger p-2 text-center">Freezing Failed. Please Try Again.</h3>']);
            }
        } else {
            // Record already frozen
            return $this->response->setJSON(['message' => '<h3 class="bg-success p-2 text-center">Already freezed</h3>']);
        }
    } else if ($action_type == 'u') {
        // Unfreeze action
        $builder = $db->table('cl_freezed')
            ->where('part', $part_no)
            ->where('m_f', $mainhead)
            ->where('display', 'Y');
        
        if ($list_dt != 0) {
            $builder->where('next_dt', $list_dt);
        }
        
        if ($board_type != 0) {
            $builder->where('board_type', $board_type);
        }
        
        $query = $builder->get();
        
        if (count($query->getResultArray()) > 0) {
            // Update to unfreeze
            $updatedata = [
                'display' => 'N',  // Mark as not displayed (unfreeze)
                'unfreezed_by' => $ucode,
                'unfreezed_on' => date('Y-m-d H:i:s'),  // Ensure this field is set to the current timestamp
                'unfreezed_by_ip' => getenv('REMOTE_ADDR'),
            ];
            
            // Update the record to unfreeze it
            $record_update = $builder->set($updatedata)
                ->update();  // Ensure the record gets updated with the correct unfreezing info
            
            if ($record_update) {
                return $this->response->setJSON(['message' => '<h3 class="bg-success p-2 text-center">Un-freezed Successfully</h3>']);
            } else {
                return $this->response->setJSON(['message' => '<h3 class="bg-danger p-2 text-center">Un-freezing Failed. Please Try Again.</h3>']);
            }
        } else {
            // Record is not frozen, so cannot unfreeze
            return $this->response->setJSON(['message' => '<h3 class="bg-warning p-2 text-center">Not freezed yet.</h3>']);
        }
    }
}


    public function header_footer_list()
    {
        $listingDate = $this->PrintAdvanceModel->getHFlistingDate();

        $listingBenches = $this->PrintAdvanceModel->getHFlistingBenches();
        $data = [
            'listing_date' => $listingDate ?? null, // Use null if no results
            'benches' => $listingBenches ?? null,  // Use null if no results
        ];

        return view('Listing/print_advance/header_footer_list_print', $data);
    }


    public function get_cl_print_benches_from_roster()
    {
        $request = \Config\Services::request();
        $db = \Config\Database::connect();
        $option = '';

        $list_dt = date('Y-m-d', strtotime($request->getPost('list_dt')));
        $board_type = $request->getPost('board_type');
        $mainhead = $request->getPost('mainhead');

        // Determine m_f value
        $m_f = ($mainhead == 'M') ? '1' : (($mainhead == 'F') ? '2' : '');

        // Initialize Query Builder
        $builder = $db->table('master.roster r')
            ->select([
                'r.id',
                "STRING_AGG(j.jcode::TEXT, ',') AS jcd", // PostgreSQL equivalent of GROUP_CONCAT
                "STRING_AGG(CONCAT(j.first_name, ' ', j.sur_name), ',') AS jnm",
                'rb.bench_no',
                'mb.abbr',
                'r.tot_cases',
                'r.courtno',
                'mb.board_type_mb'
            ])
            ->join('master.roster_bench rb', 'rb.id = r.bench_id', 'left')
            ->join('master.master_bench mb', 'mb.id = rb.bench_id', 'left')
            ->join('master.roster_judge rj', 'rj.roster_id = r.id', 'left')
            ->join('master.judge j', 'j.jcode = rj.judge_id', 'left')
            ->where('j.is_retired !=', 'Y')
            ->where('j.display', 'Y')
            ->where('rj.display', 'Y')
            ->where('rb.display', 'Y')
            ->where('mb.display', 'Y')
            ->where('r.display', 'Y')
            ->where('r.m_f', $m_f);

        // Handle board type conditions
        if ($board_type == 'C') {
            $builder->whereIn('mb.board_type_mb', ['C', 'CC']);
        } else {
            $builder->where('mb.board_type_mb', $board_type);
        }

        // Handle date conditions
        if ($board_type == 'R') {
            $builder->where('r.to_date', NULL); // Modify for PGSQL if needed
        } else {
            $builder->where('r.from_date', $list_dt);
        }

        $builder->groupBy('r.id, rb.bench_no, mb.abbr, r.tot_cases, r.courtno, mb.board_type_mb')
            ->orderBy('r.courtno')
            ->orderBy('r.id');

        $query = $builder->get();
        $result = $query->getResultArray();

        // Build dropdown options
        if (!empty($result)) {
            $option .= '<option value="0" selected>SELECT</option>';
            foreach ($result as $row) {
                $option .= '<option value="' . htmlspecialchars($row["jcd"]) . "|" . htmlspecialchars($row["id"]) . '"> '
                    . htmlspecialchars($row["jnm"]) . ' </option>';
            }
        } else {
            $option .= '<option value="0" selected>EMPTY</option>';
        }

        return $option;
    }

    public function get_cl_print_partno()
    {
        $request = \Config\Services::request();
        $db = \Config\Database::connect();

        // Get POST parameters
        $mainhead   = $request->getPost('mainhead');
        //$list_dt    = $request->getPost('list_dt');
        $list_dt = date('Y-m-d', strtotime($request->getPost('list_dt')));
        if (!empty($request->getPost('roster_id'))) {
            $roster_id  = $request->getPost('roster_id');
        } else {
            $jud_ros = explode("|", $request->getPost('jud_ros'));
            $roster_id = $jud_ros[1];
        }

        $board_type = $request->getPost('board_type');

        // Build Query
        $builder = $db->table('heardt')
            ->select('clno')
            ->where('mainhead', $mainhead)
            ->where('next_dt', $list_dt)
            ->where('roster_id', $roster_id)
            ->groupStart()
            ->where('main_supp_flag', 1)
            ->orWhere('main_supp_flag', 2)
            ->groupEnd()
            ->groupBy('clno');

        // Apply board_type condition if it's not 0
        if ($board_type !== '0') {
            $builder->where('board_type', $board_type);
        }

        // Execute Query
        $query = $builder->get();
        $results = $query->getResultArray();

        // Generate <option> dropdown
        $output = '';
        if (!empty($results)) {
            $output = '<option value="0" selected>SELECT</option>';
            foreach ($results as $row) {
                $output .= '<option value="' . esc($row['clno']) . '">' . esc($row['clno']) . '</option>';
            }
        } else {
            $output .= '<option value="1" selected>1 (empty)</option>';
        }

        // Return Response as JSON
        return $this->response->setJSON(['status' => 'success', 'options' => $output]);
    }



    public function note_field()
    {

        $request = \Config\Services::request();
        $db = \Config\Database::connect();

        $list_dt = $request->getPost('list_dt');
        $mainhead = $request->getPost('mainhead');

        $jud_ros = explode("|", $request->getPost('jud_ros'));
        $roster_id = $jud_ros[1];
        $judges_id = $jud_ros[0];


        $builder = $db->table('headfooter hf');
        $builder->select('hf.*, r.courtno');
        $builder->join('master.roster r', 'r.id = hf.roster_id AND r.display = \'Y\'', 'left');
        //$builder->where('hf.next_dt', $list_dt);
        $builder->where('hf.mainhead', $mainhead);
        $builder->where('hf.roster_id', $roster_id);
        $builder->where('hf.next_dt', $list_dt);
        $builder->where('hf.display', 'Y');

        $query = $builder->get();
        $res = $query->getResultArray();

        $html = '';

        $html .= '<table border="0" align="center">    
                <tr>
                    <td>Flag</td>
                    <td>
                    <select class="ele" name="flag" id="flag">
                        <option value="-1" selected>Select</option>
                        <option value="H">Header</option>
                        <option value="F">Footer</option>                
                    </select>                
                    </td>
                </tr>
                <tr>
                    <td>
                        Note   
                    </td>
                    <td>
                        <input type="text" size="10" name="hf_not" id="hf_note" value=""/>     
                    </td>
                </tr>
                <tr>
                    <td colspan="2"><button class="btn btn-primary" type="button" name="n_btn" id="n_btn">Submit</button></td>
                </tr>
           </table>';

        if (($res)) {

            $html .= '<table border="1" style="font-size:14px; text-align: left; background: #ffffff;" cellspacing=0>
                <tr><td style="text-align:left" colspan="4"><U>Header Footer Note</U>:-</td></tr>
                <tr><td style="text-align:left">Court No.</td><td style="text-align:left">H/F</td><td style="text-align:left">Remark</td><td>Action</td></tr>';

            foreach ($res as $row) {

                $html .= '<tr data-id="tr_' . esc($row['hf_id']) . '">
                    <td style="text-align:left">' . esc($row['courtno']) . '</td>
                    <td style="text-align:left">' . esc($row['h_f_flag']) . '</td>
                    <td style="text-align:left">' . esc($row['h_f_note']) . '</td>
                    <td style="text-align:left">
                        <button type="button" class="btn btn-danger" name="del_' . esc($row['hf_id']) . '" id="del_' . esc($row['hf_id']) . '" onClick="del_head_foot(' . esc($row['hf_id']) . ')">Delete</button>
                    </td>
                </tr>';
            }

            $html .= '</table>';
        }

        return $html;
    }


    public function note_field_ins()
    {
        $request = \Config\Services::request();
        $db = \Config\Database::connect();
        if (!empty(session()->get('dcmis_user_idd'))) {
            $ucode = session()->get('dcmis_user_idd');
        } else {
            $ucode = session()->get('login')['usercode'];
        }
        // $list_dt = date('Y-m-d', strtotime($request->getPost('list_dt')));
        $list_dt = $request->getPost('list_dt');
        $mainhead = $request->getPost('mainhead') ?? 0;
        $part_no = $request->getPost('part_no')  ??  0;
        $jud_ros = explode("|", $request->getPost('jud_ros'));
        $roster_id = $jud_ros[1];
        $judges_id = $jud_ros[0];
        // $roster_id = 1;
        $judges_id = 1;
        $flag =  $request->getPost('flag') ?? 0;
        $hf_note = $request->getPost('hf_note') ?? 0;
        $data = [
            'next_dt' => $list_dt,
            'roster_id' => $roster_id,
            'h_f_note' => $hf_note,
            'h_f_flag' => $flag,
            'usercode' => $ucode,
            'ent_dt' => date('Y-m-d H:i:s'),
            'display' => 'Y',
            'part' => $part_no,
            'mainhead' => $mainhead,
        ];

        // $result = $db->table('headfooter')->insert($data);
        // Get the compiled insert query as a string
        $builder = $db->table('headfooter');
        // $compiledQuery = $builder->set($data)->getCompiledInsert();
        // echo $compiledQuery; // Print the query for debugging purposes

        // Execute the insert query
        $result = $builder->insert($data);


        if ($result == 1) {
            return $this->response->setJSON(['message' => 'Record Inserted Successfully']);
        } else {
            return $this->response->setJSON(['error' => 'Error:Record Not Inserted']);
        }
    }

    public function del_head_foot()
    {
        $request = \Config\Services::request();
        $db = \Config\Database::connect();
        $hfid = $request->getPost('del_hf');
        $data = [
            'display' => 'N'
        ];
        $updatedata = $db->table('headfooter')->where('hf_id', $hfid)->set($data)->update();
        return $this->response->setJSON(['message' => 'Deleted Successfully']);
    }


    public function make_unprint_list()
    {
        return view('Listing/print_advance/make_unprint_list_print');
    }

    //Old Controller for Make Unprint
    // public function make_unprint_list()
    // {

    //     $db = \Config\Database::connect();

    //     $subquery = $db->table('heardt')
    //         ->select('roster_id, judges')
    //         ->where('mainhead', 'M')
    //         ->where('board_type', 'J')
    //         ->where('next_dt >= CURRENT_DATE')
    //         ->whereIn('main_supp_flag', ['1', '2'])
    //         ->where('roster_id > 0')
    //         ->groupBy('roster_id, judges')
    //         ->getCompiledSelect();

    //     $builder = $db->table('heardt h');
    //     $builder->select('string_agg(j.first_name || \' \' || j.sur_name, \', \' ORDER BY j.judge_seniority) AS jnm, h.roster_id, h.judges');
    //     $builder->join('master.roster_judge rj', 'rj.roster_id = h.roster_id', 'left');
    //     $builder->join('master.judge j', 'j.jcode = rj.judge_id', 'left');
    //     $builder->join("($subquery) a", 'a.roster_id = h.roster_id', 'left');
    //     $builder->where('j.is_retired !=', 'Y')
    //         ->where('j.display', 'Y')
    //         ->where('rj.display', 'Y')
    //         ->groupBy('h.roster_id, h.judges');
    //     $query = $builder->get();
    //     $results = $query->getResultArray();
    //     $data['benches'] = $results;
    //     return view('Listing/print_advance/make_unprint_list_print');
    // }

    function get_cl_printed_roster_id()
    {
        $request = \Config\Services::request();
        $list_dt = $request->getPost('list_dt');
        $list_dts = date('Y-m-d', strtotime($list_dt));
        $mainhead = $request->getPost('mainhead');
        $board_type = $request->getPost('board_type');
        $options = $this->PrintAdvanceModel->get_cl_printed_roster_id($mainhead, $list_dts, $board_type);
        return $options;
    }


    public function get_make_unprint()
    {

        $request = \Config\Services::request();


        $get_list_dt = $request->getPost('list_dt');
        $list_dt = date('Y-m-d', strtotime($get_list_dt));
        $mainhead = $request->getPost('mainhead') ?? 0;
        $part_no = $request->getPost('part_no')  ??  0;
        $jud_ros = $request->getPost('jud_ros')  ??  0;

        $data['result'] = $this->PrintAdvanceModel->get_make_unprint($list_dt, $mainhead, $jud_ros, $part_no);
        return view('Listing/print_advance/get_make_unprint', $data);

        /*$db = \Config\Database::connect();
        $builder = $db->table('cl_printed c');
        $builder->select('id')
            ->where('c.m_f', $mainhead)
            ->where('c.next_dt', $list_dt)
            ->where('c.roster_id', $jud_ros)
            ->where('c.part', $part_no)
            ->where('c.display', 'Y');
        $query = $builder->get();
        $resultdata = $query->getResult();
        $html = '';

        if (!empty($resultdata)) {
            foreach ($resultdata as $row) {
                $html .= '<div style="text-align:center;" class="p-4">
                        <h3 class="text-success">Record Found</h3>
                        <p>Be Sure before making unprint</p>
                       <!--<p> Be sure making unprint will delete all drop notes and header footer notes in this regard.<br/>-->
                        Click on Make Unprint Button.</p>
                        <input name="del_cl_id" type="hidden" id="del_cl_id" value="' . $row->id . '">
                        <button class="btn btn-primary" name="del_btn" type="button" id="del_btn">Make Unprint</button>
                    </div>';
            }
        } else {
            $html .= '<div style="text-align:center;" class="p-4"><h3 class="text-danger">No Record Found</h3></div>';
        }
        echo $html;*/
    }


    public function get_make_unprint_updt()
    {

        $request = \Config\Services::request();
        //$db = \Config\Database::connect();
        if (!empty(session()->get('dcmis_user_idd'))) {
            $ucode = session()->get('dcmis_user_idd');
        } else {
            $ucode = session()->get('login')['usercode'];
        }
        $list_dt = $request->getPost('list_dt');
        $mainhead = $request->getPost('mainhead');
        $part_no = $request->getPost('part_no');
        $jud_ros = $request->getPost('jud_ros');
        $del_cl_id = $request->getPost('del_cl_id');
        $drop_note = $request->getPost('drop_note');
        $header_footer = $request->getPost('header_footer');


        $result = $this->PrintAdvanceModel->get_make_unprint_updt($del_cl_id, $ucode);
        //$result = 1;
        //$drop_note = '';
        //$header_footer = '';
        /*$data = [
            'display' => 'N',
            'deleted_by' => $ucode,
            'deleted_on' => date('Y-m-d H:i:s')
        ];
        $updatedata = $db->table('cl_printed')->where('id', $del_cl_id)->set($data)->update();
        */
        if ($result == 1) {

            if ($drop_note == 'true' or $header_footer == 'true') {
                $this->PrintAdvanceModel->del_drop_hfnote($list_dt, $mainhead, $part_no, $jud_ros, $drop_note, $header_footer);
            }

            /*if ($drop_note == 'true') {
                $db->table('drop_note')->update(['display' => 'N'], [
                    'cl_date' => $list_dt,
                    'part' => $part_no,
                    'roster_id' => $jud_ros,
                    'mf' => $mainhead,
                ]);
            }
            if ($header_footer == 'true') {
                $db->table('headfooter')->update(['display' => 'N'], [
                    'next_dt' => $list_dt,
                    'part' => $part_no,
                    'roster_id' => $jud_ros,
                    'mainhead' => $mainhead,
                ]);
            }*/

            // File Renaming with Timestamp
            $full_path = WRITEPATH . 'judgment/cl/' . $list_dt . '/';
            $path_dir_only = $full_path . "Unprint/";
            if (!is_dir($path_dir_only)) {
                mkdir($path_dir_only, 0777, true);
            }

            $filArr = [];
            $filnameArr = [];
            foreach (glob($full_path . '*.*') as $file) {
                $file_name = str_replace($full_path, "", $file);
                $filnameArr[] = $file_name;
                $file_arr = explode('_', $file_name);
                //$main_supp_chk_arry = explode('.', $file_arr[3]);
                //$main_supp_chk_arry[0];         
                if ($part_no == $file_arr[3]) {

                    foreach ($file_arr as $vae) {
                        if (strpos($vae, '.html') !== false || strpos($vae, '.csv') !== false || strpos($vae, '.json') !== false || strpos($vae, '.pdf') !== false) {
                            $fn1 = explode('.', $vae);
                            if ($jud_ros == $fn1[0]) {
                                $filename = implode('_', $file_arr);
                                $filArr[] = $filename;
                            }
                        }
                    }
                }
            }

            $baseFilArr = [];
            if (!empty($filArr)) {
                foreach ($filArr as $vue) {
                    $fl1 = explode('_', $vue);
                    $baseFilArr[] = $fl1[0] . '_' . $fl1[1] . '_' . $fl1[2];
                }

                $bn_FilArr = [];
                if (!empty($baseFilArr)) {
                    $baseFiles = array_unique($baseFilArr);
                    foreach ($filnameArr as $filn) {
                        $fn_name = explode('.', $filn);
                        if (in_array($fn_name[0], $baseFiles)) {
                            $bn_FilArr[] = $filn;
                        }
                    }
                }
                // Rename Process
                $newFilArr = [];
                foreach ($filArr as $val) {
                    $exVal = explode('.', $val);
                    $newFilArr[] = $path_dir_only . $exVal[0] . '_' . date('Y-m-d H:i:s') . '.' . $exVal[1];
                }
                if (!empty($bn_FilArr)) {
                    foreach ($bn_FilArr as $val) {
                        $exVal = explode('.', $val);
                        $newFilArr[] = $path_dir_only . $exVal[0] . '_' . date('Y-m-d H:i:s') . '.' . $exVal[1];
                    }
                }

                // previous Paths
                $prevFilArr = [];
                foreach ($filArr as $val) {
                    $prevFilArr[] = $full_path . $val;
                }
                if (!empty($bn_FilArr)) {
                    foreach ($bn_FilArr as $val) {
                        $prevFilArr[] = $full_path . $val;
                    }
                }

                foreach ($newFilArr as $k => $newFil) {
                    rename($prevFilArr[$k], $newFil);
                }
            }


            return $this->response->setJSON(['status' => 'green', 'message' => 'Successfully Unprinted']);
        } else {
            return $this->response->setJSON(['status' => 'red', 'message' => 'Error:Unable to Unprint']);
        }
    }


    public function merging_pdf_print()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('heardt');
        $builder->select('next_dt')
            ->where('mainhead', 'M')
            ->where('next_dt >=', date('Y-m-d', strtotime('-7 days'))) // Fix date condition
            ->groupStart() //Fix OR condition properly
            ->where('main_supp_flag', '1')
            ->orWhere('main_supp_flag', '2')
            ->groupEnd()
            ->groupBy('next_dt')
            ->orderBy('next_dt', 'ASC');

        $query = $builder->get();
        $result = $query->getResultArray();

        $data['listing_date'] = $result;
        return view('Listing/print_advance/merge_pdf_list_print', $data);
    }

    public function cl_merge_first_page_get()
    {
        //not found this page
    }

    //Old Merge Pdf function
    // public function cl_merge_first_page_update()
    // {

    //     $request = \Config\Services::request();
    //     $db = \Config\Database::connect();
    //     $first_page = "";

    //     $mainhead = $request->getPost('mainhead');
    //     $list_dt = $request->getPost('list_dt');
    //     $board_type = $request->getPost('board_type');
    //     $final_supply = $request->getPost('final_supply');

    //     $cause_list = "/home/judgment/cl/" . $list_dt . "/" . $mainhead . "_" . $board_type . "_" . $final_supply . ".pdf";
    //     $cause_list_backup = "/home/judgment/cl/" . $list_dt . "/" . $mainhead . "_" . $board_type . "_" . $final_supply . "_backup.pdf";
    //     if (!file_exists($cause_list)) {
    //         echo "List Not Published";
    //     } else {
    //         if (!empty($_FILES["file"]["name"])) {

    //             $temp = explode(".", $_FILES["file"]["name"]);
    //             $extension = end($temp);
    //             if ($extension != 'pdf') {
    //                 echo "Only pdf files allowed.";
    //             } else {
    //                 $first_page = "/home/judgment/cl/" . $list_dt . "/first_page_" . $mainhead . "_" . $board_type . "_" . $final_supply . "." . $extension;
    //                 /*   if (file_exists($first_page)) {
    //                     echo "Sorry File already exist.";
    //                 } else {*/
    //                 if (move_uploaded_file($_FILES["file"]["tmp_name"], $first_page)) {
    //                     $rename_cl_file = exec("cp $cause_list $cause_list_backup");
    //                     $delete_cl_file = exec("rm $cause_list");
    //                     $pdf = new PDFMerger;
    //                     $pdf->addPDF($first_page, 'all')
    //                         ->addPDF($cause_list_backup, 'all')
    //                         ->merge('file', $cause_list);

    //                     if (file_exists($cause_list)) {
    //                         echo "Success.<br>";
    //                         $embed_path = "http://164.52.201.69/ICMIS/public/Supreme_cour/t/judgment/cl/" . $list_dt . "/" . $mainhead . "_" . $board_type . "_" . $final_supply . ".pdf";
    //                         <embed src="" type='application/pdf' width="80%" height="700px" />
    //                 <?php
    //                     } else {
    //                         echo "Error!";
    //                     }
    //                 }
    //                 // }
    //             }
    //         }
    //     }
    // }

    public function cl_merge_first_page_update()
    {
        $request = \Config\Services::request();

        $mainhead = $request->getPost('mainhead');
        $list_dt = $request->getPost('list_dt');
        $board_type = $request->getPost('board_type');
        $final_supply = $request->getPost('final_supply');

        // Create the save path using WRITEPATH (a writable path defined in CI config)
        $savePath = WRITEPATH . 'judgment/cl/' . $list_dt . '/';

        // Check if the directory exists, if not, create it
        if (!is_dir($savePath)) {
            mkdir($savePath, 0777, true); // Create directory with proper permissions
        }

        // Secure file paths for cause list and backup
        $cause_list = $savePath . "{$mainhead}_{$board_type}_{$final_supply}.pdf";
        $cause_list_backup = $savePath . "{$mainhead}_{$board_type}_{$final_supply}_backup.pdf";

        // Check if the original cause list exists
        if (!file_exists($cause_list)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'List Not Published']);
        }

        // Handle file upload using CodeIgniter's file handling
        $file = $this->request->getFile('file');

        // Check if file is uploaded and is valid
        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Validate file type (PDF only)
            if ($file->getClientMimeType() !== 'application/pdf') {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Only PDF files are allowed.']);
            }

            // Generate the path to move the uploaded first page
            $first_page = $savePath . "first_page_{$mainhead}_{$board_type}_{$final_supply}.pdf";

            // Move the uploaded file securely to the target location
            $file->move($savePath, "first_page_{$mainhead}_{$board_type}_{$final_supply}.pdf");

            // Backup the original cause list and delete the original file
            copy($cause_list, $cause_list_backup);  // Create backup
            unlink($cause_list); // Delete the original cause list

            // Merge PDFs using mPDF or another PDF merging library
            try {
                // Assuming you are using mPDF library for merging
                $mpdf = new \Mpdf\Mpdf();

                // Import the first uploaded page (first page PDF)
                $mpdf->SetSourceFile($first_page);
                $tplId = $mpdf->ImportPage(1);
                $mpdf->UseTemplate($tplId);

                // Import the original (backup) PDF
                $mpdf->SetSourceFile($cause_list_backup);
                $pageCount = $mpdf->SetSourceFile($cause_list_backup);
                for ($i = 1; $i <= $pageCount; $i++) {
                    $tplId = $mpdf->ImportPage($i);
                    $mpdf->UseTemplate($tplId);
                    if ($i < $pageCount) {
                        $mpdf->AddPage();  // Add a new page after each page except the last
                    }
                }

                // Save the merged PDF
                $mpdf->Output($cause_list, \Mpdf\Output\Destination::FILE);  // Output merged file to cause list path

                // Check if the merged file exists
                if (file_exists($cause_list)) {
                    // Define embed paths for the merged PDF
                    $embed_path = base_url("judgment/cl/{$list_dt}/{$mainhead}_{$board_type}_{$final_supply}.pdf");
                    $embed_path_old = "http://164.52.201.69/ICMIS/public/Supreme_cour/t/judgment/cl/{$list_dt}/{$mainhead}_{$board_type}_{$final_supply}.pdf";

                    // Return success response with the PDF URL
                    return $this->response->setJSON([
                        'status' => 'success',
                        'message' => 'PDF Merged Successfully!',
                        'pdf_url' => $embed_path,
                        'embed_path_old' => $embed_path_old
                    ]);
                } else {
                    // Error if the merged PDF is not found
                    return $this->response->setJSON(['status' => 'error', 'message' => 'Error merging PDFs!']);
                }
            } catch (\Exception $e) {
                // Error handling if there is an issue with merging PDFs
                return $this->response->setJSON(['status' => 'error', 'message' => 'PDF Merge Error: ' . $e->getMessage()]);
            }
        } else {
            // Error handling if no valid file was uploaded
            return $this->response->setJSON(['status' => 'error', 'message' => 'No valid file uploaded.']);
        }
    }





    public function previous_cause_list()
    {
        return view('Listing/print_advance/previous_cause_list_print');
    }

    public function previousClWll()
    {
        $path_dir = WRITEPATH . 'judgment/cl/wk/';
        $weekly_files = [];

        if (is_dir($path_dir)) {
            $files = scandir($path_dir);

            foreach ($files as $file) {
                $pdf_path = $path_dir . $file . "/weekly.html";

                if ($file != '.' && $file != '..' && is_dir($path_dir . $file)) {
                    // Extract and format dates
                    $dir_expl = explode("_", $file);

                    if (count($dir_expl) === 2) {
                        $from_dt = explode("-", $dir_expl[0]);
                        $to_dt = explode("-", $dir_expl[1]);

                        if (count($from_dt) === 3 && count($to_dt) === 3) {
                            $fromdt = $from_dt[2] . "-" . $from_dt[1] . "-" . $from_dt[0];
                            $todt = $to_dt[2] . "-" . $to_dt[1] . "-" . $to_dt[0];

                            $weekly_files[] = [
                                'pdf_path' => base_url("writable/judgment/cl/wk/$file/weekly.html"),
                                'label' => "$fromdt to $todt"
                            ];
                        }
                    }
                }
            }
        }

        return view('Listing/print_advance/previous_cause_list_wl_print', ['weekly_files' => $weekly_files]);
    }

    public function get_wk_prev_cl()
    {
        $request = \Config\Services::request();
        $list_dt = $request->getPost('list_dt');

        if (!file_exists($list_dt)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'File not found!']);
        }

        $logo_url = base_url('images/scilogo.png');

        // Read file content
        $content = file_get_contents($list_dt);

        $updated_content = str_replace('/home/judgment/cl/scilogo.png', '<img src="' . $logo_url . '" width="50px" height="80px">', $content);


        return $this->response->setJSON(['status' => 'success', 'content' => $updated_content]);
    }


    public function previousCl()
    {

        return view('Listing/print_advance/previous_cl');
    }

    public function get_prev_cause_list_all()
    {

        $request = \Config\Services::request();
        $db = \Config\Database::connect();
        $mainhead = $request->getPost('mainhead');
        $list_dt = $request->getPost('list_dt');
        $list_dts = date('Y-m-d', strtotime($list_dt));
        $board_type = $request->getPost('board_type');
        $main_suppl = $request->getPost('main_suppl');

        $session = session();
        // Store values in the session
        $session->set([
            'save_all_mainhead' => $mainhead,
            'save_all_boardtype' => $board_type,
            'save_all_listdate' => $list_dts,
            'save_all_main_suppli' => $main_suppl,
        ]);

        $results = $this->PrintAdvanceModel->get_prev_cl_printed_all($list_dts, $mainhead, $board_type, $main_suppl);
        $output = '';

        if (!empty($results)) {
            $output .= '<div class="row"><div class="col-md-6">
                <button class="btn btn-primary" name="prnnt2" type="button" id="ebublish">e-Publish</button></div><div class="col-md-6">
                <button class="btn btn-primary" name="prnnt1" type="button" id="prnnt1">Print</button></div>
            </div>';

            $output .= '<div id="prnnt" style="font-size:12px;">';

            foreach ($results as $row) {
                $decoded_content = base64_decode($row['cl_content']);

                // Replace the src path for the image
                $updated_content = str_replace(
                    '<img src="scilogo.png" width="50px" height="80px">',
                    '<img src="' . base_url('images/scilogo.png') . '" width="50px" height="80px">',
                    $decoded_content
                );

                $output .= "<div style='page-break-after:always;'>";
                $output .= $updated_content;
                $output .= "<br/></div>";
            }

            $output .= '</div>'; // Closing the main div properly

        } else {
            $output .= '<div class="text-center p-3"><h3>No Data Found</h3></div>';
        }

        return $output;
    }

    public function get_prev_cl_printed()
    {

        $request = \Config\Services::request();


        $list_dt = date('Y-m-d', strtotime($request->getPost('list_dt')));
        $mainhead = $request->getPost('mainhead');
        $part_no = $request->getPost('part_no');
        $jud_ros = explode("|", $request->getPost('jud_ros'));

        $results = $this->PrintAdvanceModel->get_prev_cl_printed($list_dt, $mainhead, $part_no, $jud_ros[1]);
        $output = '';
        if (!empty($results)) {
            $output .= '<div class="row"><div class="col-md-6">
                <button class="btn btn-primary" name="prnnt1" type="button" id="prnnt1">Print</button></div>
            </div>';

            $output .= '<div id="prnnt" style="font-size:12px;">';

            foreach ($results as $row) {
                $decoded_content = base64_decode($row['cl_content']);

                // Replace the src path for the image
                $updated_content = str_replace(
                    '<img src="scilogo.png" width="50px" height="80px">',
                    '<img src="' . base_url('images/scilogo.png') . '" width="50px" height="80px">',
                    $decoded_content
                );

                $output .= "<div style='page-break-after:always;'>";
                $output .= $updated_content;
                $output .= "<br/></div>";
            }

            $output .= '</div>'; // Closing the main div properly

        } else {
            $output .= '<div class="text-center p-3"><h3>No Data Found</h3></div>';
        }

        return $output;
    }

    public function call_reshuffle_function()
    {
        // Get Post Data
        $list_dt = date('Y-m-d', strtotime($this->request->getPost('list_dt')));
        $mainhead = $this->request->getPost('mainhead');
        $part_no = $this->request->getPost('part_no');
        $jud_ros = explode("|", $this->request->getPost('jud_ros'));
        $judge_id = $jud_ros[0];
        $roster_id = $jud_ros[1];
        $from_cl_no = $this->request->getPost('from_cl_no');

        // Check if Cause List is already Printed
        $isPrinted = $this->PrintAdvanceModel->f_cl_is_printed($list_dt, $part_no, $mainhead, $roster_id);

        if ($isPrinted == 1) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Cause List already Printed. You cannot Reshuffle']);
        }

        if ($from_cl_no > 0) {
            $result = $this->PrintAdvanceModel->f_cl_reshuffle_from_desired_no($list_dt, $judge_id, $mainhead, $part_no, $roster_id, $from_cl_no);
        } else {
            $result = $this->PrintAdvanceModel->f_cl_reshuffle($list_dt, $judge_id, $mainhead, $part_no, $roster_id);
        }

        // Check Result
        if ($result == "1") {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Reshuffled Successfully']);
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Error: Reshuffling Failed']);
        }
    }

    public function cl_print_save_all()
    {

        $request = \Config\Services::request();
        $db = \Config\Database::connect();
        if (!empty(session()->get('dcmis_user_idd'))) {
            $ucode = session()->get('dcmis_user_idd');
        } else {
            $ucode = session()->get('login')['usercode'];
        }
        $mainhead = $request->getPost('mainhead');
        $list_dt = $request->getPost('list_dt');
        $list_dts = date('Y-m-d', strtotime($list_dt));
        $board_type = $request->getPost('board_type');
        $main_suppl = $request->getPost('main_suppl');
        $pdf_cont = str_replace("scilogo.png", "/home/judgment/cl/scilogo.png", $request->getPost('prtContent'));
        $session = session();
        // Store values in the session
        $session->set([
            'json_list_dt' => $list_dts,
            'json_main_suppl' => $main_suppl,
        ]);

        //START TO MAKE 
        $file_path = $mainhead . "_" . $board_type . "_" . $main_suppl;
        $path_dir = WRITEPATH . "home/judgment/cl/$list_dts/";

        // Create directory if it doesn't exist
        if (!is_dir($path_dir)) {
            mkdir($path_dir, 0777, true);
        }

        $path = $path_dir;
        $data_file = $path . $file_path . ".html";
        $data_file1 = $path . $file_path . ".pdf";
        file_put_contents($data_file, $pdf_cont);

        ///////// this is commentted for Class \"Mpdf\\Mpdf\" not found///////////////////////////    
        // try {
        //     $mpdf = new \Mpdf\Mpdf();
        //     //$mpdf = new Mpdf();
        //     $mpdf->SetDisplayMode('fullpage');
        //     $mpdf->showImageErrors = true;
        //     $mpdf->shrink_tables_to_fit = 0;
        //     $mpdf->keep_table_proportions = true;

        //     $mpdf->WriteHTML(file_get_contents($data_file));
        //     $mpdf->Output($data_file1, \Mpdf\Output\Destination::FILE);
        //     return true;
        // } catch (\Mpdf\MpdfException $e) {
        //     // Handle mPDF errors
        //     log_message('error', 'PDF generation failed: ' . $e->getMessage());
        //     return false;
        // }
        /////////////////////////////////////////////////////////////////////////////////////////////////////


        //END TO MAKE PDF
        //if($q_rs3 == 1){
        if ($mainhead == 'F') {
            $mf_roster_flag = 2;
        }
        if ($mainhead == 'M') {
            $mf_roster_flag = 1;
        }

        $after_allocation = $this->PrintAdvanceModel->after_allocation($list_dts, $board_type, $mf_roster_flag, $main_suppl, $mainhead, $ucode);

        if ($after_allocation) {
            // return $this->response->setJSON(['message' => 'Cause List Ported/Published Successfully']);
            echo 'Cause List Ported/Published Successfully';
            return;
        }

        $this->PrintAdvanceModel->cl_save_json();

        $main_suppl_1 = explode('_', $main_suppl)[0];
        $causelist_title = '';
        if ($board_type == 'J') {
            if ($mainhead == 'M') {
                $causelist_title .= 'Miscellaneous ';
            } else if ($mainhead == 'F') {
                $causelist_title .= 'Regular ';
            }
        } else if ($board_type == 'C') {
            $causelist_title .= 'Chamber ';
        } else if ($board_type == 'CC') {
            $causelist_title .= 'List of Curative & Review Petitions ';
        } else if ($board_type == 'R') {
            $causelist_title .= 'Registrar ';
        }
        if ($main_suppl_1 != '1') {
            $causelist_title .= 'Supplementary ';
        }

        $causelist_title .= 'List dated ' . $_POST['list_dt'] . ' [' . $file_path . ']';
        $sms_text = rawurlencode($causelist_title . ' List has been published on www.sci.gov.in at ' . date("d-m-Y H:i:s", time()) . ' - Supreme Court Of India');

        $sms_url = 'http://xxxx/eAdminSCI/a-push-sms-gw?mobileNos=' . '9630100950,9810884595,9319170909,9821411915,9868069855,9718009598,9818782386,9910727768,9968281944,9968319828,9968811042,9971685090,9999100724,9312570277,9910431438,9643323531,7838900365,8800307859,8800928316,9810855890,9711475023,9711475578,9810263541,9810464620,9810481741,9810485122,9810506860,9810594145,9811471402,9811904000,9818617598,9868186878,9868200903,9868216440,9868280279,9868281372,9868631191,9868996564,9811316333,9871922703,9868207383,9899016720,9899249150,9899518586,9899924364,9910431438,9911675788,9968281944,9968319828,9968811042,9971685090,8860012863,9810267531' . '&message=' . $sms_text . '&typeId=29&myUserId=NIC001001&myAccessId=root&authCode=' . SMS_KEY . '&templateId=' . SCISMS_list_publish;

        $sms_response = file_get_contents($sms_url);
        $json = json_decode($sms_response);
        if ($json->{'responseFlag'} == "success") {
            echo 'Success: Causelist Uploaded alert SMS sent.';
        } else {
            echo 'Error: Causelist Uploaded alert SMS could not be sent.';
        }


        //DISABLED ON 09-02-2022 : AS PER BOCC DIRECTIONS ON 08-02-2022 CAUSE LIST IN CSV FORMAT NOT REQUIRED FURTHER
        //This code resumed from 07-12-2023 as per directions of Ld. Reg (T) mail dated 06-12-2023
        //email csv
        if ($board_type != 'CC') {

            $print_CSV = $this->PrintAdvanceModel->print_prevoius_CSV($list_dts, $board_type, $mainhead, $main_suppl);


            $file_path = $mainhead . "_" . $board_type . "_" . $main_suppl;
            $path_dir = WRITEPATH . "home/judgment/cl/$list_dt/";
            $path = $path_dir;
            $filePath = $path . $file_path . ".json";
            $json_result = json_encode($data, JSON_PRETTY_PRINT);
            if (file_put_contents($filePath, $json_result) !== false) {
            } else {
            }



            $data_file_csv = $path . $file_path . ".csv";
            if (file_exists("$data_file_csv"))
                unlink("$data_file_csv");
            touch("$data_file_csv");
            $fp = file_put_contents($data_file_csv, "w+");
            // $fp=fopen($data_file_csv,"w+");
            if ($fp && $print_CSV) {
                $csv = "Mobile,Email,Advocate Name, Bench,Court No.,Item No.,Case No.,Petitioner,Respondent\n";
                foreach ($print_CSV as $row) {
                    $csv .= $row['mobile'] . "," . $row['email'] . "," . $row['advocate_name'] . "," . $row['bench'] . "," . $row['courtno'] . "," . $row['item_no'] . "," . $row['Case_No'] . "," . $row['petitioner'] . "," . $row['repondent'] . "\n";
                }
                fwrite($fp, $csv);
                fclose($fp);

                $str = "1";
                $from = 'sci@nic.in';
                $files = array();
                $subject = $causelist_title;
                $content = "Please find attached CSV. List has been published at " . date("d-m-Y H:i:s", time());
                $from_name = 'SCI - Computer Cell';
                $path_dir = $data_file_csv;
                if (file_exists("$path_dir")) {
                    $files[] = $path_dir;
                    if (count($files) > 0) {

                        $message = "";
                        $message .= "<html><body><div style='font-family:verdana; font-size:13px; font-weight:bold'>";
                        $message .= "<div>";
                        $message .= $content;
                        $message .= "</div>";
                        $message .= "<br/><div style='font-family:verdana; font-size:13px; font-weight:bold'><span style='color:#ffbb00;'>Thanks & Regards</span><BR/>SUPREME COURT OF INDIA<BR/></div>";
                        $message .= "</div>";
                        $message .= "<br/><br/><font color='#009900' face='Webdings' size='4'></font><font color='#009900' face='verdana,arial,helvetica' size='2'> <strong>Please consider the environment before printing this email<BR/>This is an electronic message. Please do not reply to this email.</strong></font>";
                        $message .= "<p><b>Total Attachments : </b>" . count($files) . " attachments</p></body></html>";
                        $email = "1@velocis.con.in, 2@velocis.con.in, 3@velocis.con.in";
                        $send_email = multi_attach_mail($email, $subject, $message, $from, $from_name, $files);
                        if ($send_email) {
                            // echo '<br/><span style="color:green;">Sent Sucess.</span><br/>';
                        } else {
                            // echo '<br/><span style="color:red;">Not Sent.</span><br/>';
                        }
                    } else {
                        echo "Attachment not found";
                    }
                } else {
                    echo "csv not available";
                }
            }
        }
    }

    public function cl_print_save()
    {
        $request = \Config\Services::request();

        // Get user code from session
        if (!empty(session()->get('dcmis_user_idd'))) {
            $ucode = session()->get('dcmis_user_idd');
        } else {
            $ucode = session()->get('login')['usercode'];
        }

        // Retrieve POST data
        $mainhead = $request->getPost('mainhead');
        $list_dt = $request->getPost('list_dt');
        $board_type = $request->getPost('board_type');
        $part_no = $request->getPost('part_no');
        $jud_ros = explode("|", $request->getPost('jud_ros'));
        $judge_id = $jud_ros[0];
        $roster_id = $jud_ros[1];
        $prtContent = $request->getPost('prtContent');
        // $cntt = base64_encode($request->getPost('prtContent')); 

        // $pdf_cont = str_replace("scilogo.png", "/home/judgment/cl/scilogo.png", $request->getPost('prtContent'));
        $pdf_cont = str_replace("scilogo.png", "scilogo.png", $request->getPost('prtContent'));

        // Fetch `main_supp_flag` from the database
        $minMax = $this->PrintAdvanceModel->getMainSuppFlag($list_dt, $part_no, $mainhead, $roster_id);

        if (!$minMax) {
            return "Error: No records found for the given criteria.";
        }


        $main_supp_flag = $minMax['main_supp_flags'];

        // Create data array properly
        $data = [
            'ucode'      => $ucode,
            'mainhead'   => $mainhead,
            'list_dt'    => $list_dt,
            'board_type' => $board_type,
            'part_no'    => $part_no,
            'judge_id'   => $judge_id,
            'roster_id'  => $roster_id,
            //'cntt'       => $cntt,
            'cntt'       => $prtContent,
            // 'usercode'   => $ucode,
            //  'pdf_cont' => $pdf_cont,
            'main_supp_flag' => $main_supp_flag, // Add `main_supp_flag` to the data array
            'min_brd_no'    => $minMax['min_brd_no'],
            'max_brd_no'    => $minMax['max_brd_no']
        ];


        // Fetch previous cause list print records
        $results = $this->PrintAdvanceModel->cl_print_save($data);
     
        if ($results == 1)
        {

            $file_path = $mainhead . "_" . $board_type . "_" . $main_supp_flag . "_" . $part_no . "_" . $roster_id;
            
            // $path_dir = WRITEPATH . '/home/judgment/cl/' . $list_dt . '/';
            $path_dir = './judgment/cl/' . $list_dt . '/';

            // pr('mkdir check UAT Serverr');
            if (!is_dir($path_dir)) {
                mkdir($path_dir, 0777, true);
            }

            $data_file = $path_dir . $file_path . ".html";
            $data_file1 = $path_dir . $file_path . ".pdf";

            if (file_exists($data_file)) {
                unlink($data_file);
            }

            file_put_contents($data_file, $pdf_cont);

            // Generate PDF using Mpdf
            $mpdf = new \Mpdf\Mpdf();
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->showImageErrors = true;
            $mpdf->shrink_tables_to_fit = 0;
            $mpdf->keep_table_proportions = true;
            $mpdf->WriteHTML($pdf_cont);
            $mpdf->Output($data_file1, 'F');
            
            return $this->response->setJSON(['message' => 'List Ported/Published Successfully.']);
        } else {
            return $this->response->setJSON(['message' => 'List Not Ported/Published.']);
        }
    }


    // Date 01-10-2024 by Ashutosh
    public function cl_print()
    {
        $data['benches'] = $this->PrintAdvanceModel->getBenches();
        $data['listingDates'] = $this->PrintAdvanceModel->getFieldSelRosterDts();


        return view('Listing/print_advance/cl_print', $data);
    }

    public function get_cause_list_advance_screen()
    {
        $session = session();
        // Get user code from session


        $list_dt = date('Y-m-d', strtotime($this->request->getPost('list_dt')));
        $mainhead = $this->request->getPost('mainhead');
        $part_no = $this->request->getPost('part_no');
        $jud_ros = explode("|", $this->request->getPost('jud_ros'));
        $board_type = $this->request->getPost('board_type');

        $board_type_in = ($board_type == '0') ? "" : " and h.board_type = '$board_type'";

        $roster_id = $jud_ros[1];
        $judges_id = $jud_ros[0];
        $exp_jcode = explode(",", $judges_id);
        $first_jcd_cc = $exp_jcode[0];


        $data = [
            // 'rosterData' => $rosterData,
            // 'causeListData' => $causeListData,
            'list_dt' => $list_dt,
            'mainhead' => $mainhead,
            'part_no' => $part_no,
            'board_type' => $board_type,
            'roster_id' => $roster_id,
            'judges_id' => $judges_id,
            'first_jcd_cc' => $first_jcd_cc,
        ];
        $data['model'] = $this->PrintAdvanceModel;
        return view('Listing/print_advance/cause_list_view', $data);
    }

    public function get_cause_list()
    {
        $data['benches'] = $this->PrintAdvanceModel->getBenches();
        return view('Listing/print_advance/get_cause_list', $data);
    }

    // public function get_cl_print_benches_from_roster_new() {
    //     $list_dt = date('Y-m-d', strtotime($_POST['list_dt']));
    //     $mainhead = $_POST['mainhead'];
    //     $board_type = $_POST['board_type'];
    //     echo get_cl_print_benches_from_roster_new($mainhead,$list_dt,$board_type);
    // }

    // public function get_cl_print_mainhead() {        
    //     $mainhead = $_POST['mainhead'];
    //     $board_type = $_POST['board_type'];
    //     get_cl_print_mainhead($mainhead, $board_type);
    // }




    //Section List Publish
    public function sec_list()
    {
        return view('Listing/print_advance/sec_list');
    }


    // This controller for SECTION LIST PRINT MODULE
    public function sec_list_get()
    {
        $request = \Config\Services::request();

        $listDt = $request->getPost('list_dt');
        $mainhead = $request->getPost('mainhead');
        $boardType = $request->getPost('board_type');

        // Convert Date Format
        $listDate = date('Y-m-d', strtotime($listDt));

        // Fetch Data
        $results = $this->Heardt->getSectionList($listDate, $mainhead, $boardType);

        // Define File Path
        $pathDir = WRITEPATH . "sectionlist/{$listDate}";
        $filePath = "{$pathDir}/sectionlist_M_{$boardType}_{$listDate}.html";

        // Ensure Directory Exists
        if (!is_dir($pathDir)) {
            mkdir($pathDir, 0777, true);
        }

        // Check if File Exists
        if (!is_file($filePath)) {
            $data = [
                'list_date'  => $listDate,
                'results'    => $results,
                'mainhead'   => $mainhead,
                'board_type' => $boardType,
                'filePath'   => $filePath
            ];

            return view('Listing/print_advance/sec_list_get', $data);
        } else {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Section list file already exists.',
                'file'    => $filePath
            ]);
        }
    }


    public function sec_list_save()
    {
        $request = service('request');
        $listDt = date('Y-m-d', strtotime($request->getPost('list_dt')));
        $mainhead = $request->getPost('mainhead');
        $boardType = $request->getPost('board_type');
        $ucode = session()->get('login')['usercode'];

        $inserData = $this->SectionListModel->insertDraftList($listDt, $boardType, $mainhead, $ucode);
        $rowTm =  $this->SectionListModel->getPublicationTime($listDt, $boardType);
        //pr($rowTm);
        if (!empty($rowTm) && isset($rowTm->min_tm) && !empty($rowTm->min_tm)) {
            $minTm = strtotime($rowTm->min_tm); // Convert time to timestamp
            if ($minTm !== false) {
                $pubTime = "Publication Time : " . date('d-m-Y h:i:s A', $minTm) . "<br>";
            } else {
                $pubTime = "Publication Time : Invalid time format<br>";
            }
        } else {
            $pubTime = "Publication Time : No publication time available<br>";
        }


        $printContent = $pubTime . $request->getPost('prtContent');
        $filePath = "sectionlist_{$mainhead}_{$boardType}_{$listDt}.html";
        $pathDir = WRITEPATH . "sectionlist/{$listDt}/";

        if (!is_dir($pathDir)) {
            mkdir($pathDir, 0777, true);
        }

        $dataFile = "{$pathDir}{$filePath}";
        file_put_contents($dataFile, $printContent);

        return "SECTION LIST Ported/Published Successfully.";
    }

    public function vacation_remain_cl_print()
    {
        $data['currentYear'] = date('Y');
        return view('Listing/print_advance/vacation_remain_cl_print', $data);
    }

    public function get_cause_list_vacation_remaining()
    {
        $request = service('request');
        $year = $request->getPost('vac_yr');

        $data['cases'] = $this->AllocationTp->getCases12($year);

        $data['year'] = $year;

        $data['fetchAdvocates'] = $this->AllocationTp->getAdvocates($year);

        $data['fetchCategoryOld'] = $this->AllocationTp->getCategoryOld($year);
        //pr($data['fetchCategoryOld']);
        return view('Listing/print_advance/get_cause_list_vacation_remaining', $data);
    }

    public function get_cl_printed_partno()
    {
        $request = \Config\Services::request();
        $mainhead =  $request->getPost('mainhead');
        $board_type =  $request->getPost('board_type');
        $get_list_dt = $request->getPost('list_dt');
        $list_dt = date('Y-m-d', strtotime($get_list_dt));
        $roster_id = $request->getPost('jud_ros');
        $options = $this->PrintAdvanceModel->get_cl_printed_partno_by_id($mainhead, $list_dt, $roster_id, $board_type);
        return $options;
    }
}
