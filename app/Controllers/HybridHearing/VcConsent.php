<?php

namespace App\Controllers\HybridHearing;

use App\Controllers\BaseController;
use CodeIgniter\Controller;
use App\Models\Listing\Heardt;
use App\Models\Listing\Judge;
use App\Models\HybridHearing\VcConsentModal;
use App\Models\HybridHearing\ConsentThroughEmailModel;

class VcConsent extends BaseController
{

    public $Heardt;
    public $Judge;
    public $vcconsent;
    public $ctem;

    function __construct()
    {

        $this->Heardt = new Heardt();
        $this->Judge = new Judge();
        $this->vcconsent = new VcConsentModal();
        $this->ctem = new ConsentThroughEmailModel();
    }

    public function getCSRF()
    {
        return $this->response->setJSON([
            'csrf_token' => csrf_hash()
        ]);
    }


    public function aorCaseRecord()
    {
        $data['listing_dates'] = $this->Heardt->getListingDates();
        $data['judges'] = $this->Judge->getJudgesList();

        return view('hybrid_hearing/vc_consent/entry', $data);
    }

    public function getAorCaseData()
    {
        $request = service('request');

        // Check if the request method is POST
        if ($request->getMethod() !== 'post') {
            return $this->response->setJSON(['status' => '0', 'message' => 'Invalid request method.']);
        }

        // Get input data with validation
        $listingDts = date('Y-m-d', strtotime(trim($request->getPost('listing_dts'))));
        $listType = trim($request->getVar('list_type'));
        $judgeCode = trim($request->getVar('judge_code'));
        $courtNo = trim($request->getVar('court_no'));

        try {
            // Fetch cases data from the model or service
            $data = $this->vcconsent->getCasesData($listingDts, $listType, $judgeCode, $courtNo);

            // Check if data is not empty
            if (empty($data)) {
                return $this->response->setJSON(['status' => '0', 'message' => 'No data found.']);
            }

            // Generate HTML for the response
            $html = $this->generateHtmlTable($data);

            // Return JSON response with HTML
            return $this->response->setJSON(['status' => '1', 'html' => $html, 'message' => 'Data found successfully']);
        } catch (\Exception $e) {
            log_message('error', "Error retrieving cases: " . $e->getMessage());
            return $this->response->setJSON(['status' => '0', 'message' => 'An error occurred while retrieving cases.']);
        }
    }

    // Helper function to generate the HTML table
    private function generateHtmlTable($data)
    {
        $html = '<div align="center">
                    <table class="align-items-center table table-hover table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col"><strong>S No.</strong></th>
                                <th scope="col"><strong>Item No.</strong></th>
                                <th scope="col"><strong>Case Details</strong></th>
                                <th scope="col"><strong>Court Details</strong></th>
                                <th scope="col"><strong>AOR/Party In Person</strong></th>
                                <th scope="col"><strong>Action</strong></th>
                            </tr>
                        </thead>
                        <tbody>';

        $sno = 1;
        foreach ($data as $row) {
            $html .= $this->generateHtmlRow($row, $sno);
            $sno++;
        }

        $html .= '</tbody></table></div>';

        return $html;
    }

    // Helper function to generate a single row of the HTML table
    private function generateHtmlRow($row, $sno)
    {
        // Initialize connection case details
        $is_connected = '';
        $conn_case_count = 0;

        // Determine connection case details
        if ($row['diary_no'] == $row['conn_key'] || $row['conn_key'] == 0 || empty($row['conn_key'])) {
            $print_brdslno = $row['brd_slno'];
        } else {
            $print_brdslno = "&nbsp;" . $row["brd_slno"] . "." . $conn_case_count++;
            $is_connected = "<br/><span style='color:red;'>Conn.</span>";
        }

        // Count advocates based on connection key
        $count_advocates = ($row['conn_key'] != null && $row['conn_key'] > 0)
            ? f_get_advocate_count_with_connected($row["conn_key"], $row['next_dt'])
            : $row["total_advocates"];

        // Count connected cases if necessary
        if ($row['conn_key'] == $row['diary_no']) {
            $conn_case_count = f_connected_case_count_listed($row["conn_key"], $row['next_dt']);
        }

        $getJudgeNamesByJcode = $this->vcconsent->getJudgesNamesByJcode($row['judges']);

        // Generate AOR data result
        $aorResult = $this->getAorDataLoop($row['diary_no'], $row['next_dt'], $row['roster_id'], $row['advocate_ids'], $row);

        // Build the HTML for the row
        return "<tr>
                    <td>{$sno}</td>
                    <td>{$row['brd_slno']}</td>
                    <td>{$row['reg_no_display']} @ {$row['diary_no']}
                        <br>{$row['pet_name']} <span>Vs.</span> {$row['res_name']}
                        " . ($conn_case_count > 0 ? "<br><span class='text-danger'>(Connected Cases : {$conn_case_count})</span>" : '') .
            ($count_advocates > 20 ? "<br><span style='color:red;'><b>*** (More than 20 Advocates)</b></span>" : '') . "
                    </td>
                    <td>
                        {$getJudgeNamesByJcode}<br>
                        <span class='badge badge-primary mb-2'>Court No.: " . $this->formatCourtNumber($row['courtno']) . "</span>
                        <span class='badge badge-secondary'>" . $this->formatListType($row) . "</span>
                        " . ($row['main_supp_flag'] == 2 ? '<span class="badge badge-secondary">Supplementary</span>' : '') . "
                    </td>
                    <td>{$aorResult}</td>
                    <td class='text-end' id='d_{$row['diary_no']}'>
                            " . ($row['is_printed'] != null ? "<span class='text-success'>List Already Published</span>" : "") . "
                            <button data-updation_method='single'
                                    data-diary_no='{$row['diary_no']}'
                                    data-conn_key='{$row['conn_key']}'
                                    data-next_dt='{$row['next_dt']}'
                                    data-roster_id='{$row['roster_id']}'
                                    data-judges='{$row['judges']}'
                                    data-clno='{$row['clno']}'
                                    data-main_supp_flag='{$row['main_supp_flag']}'
                                    data-action='save'
                                    class='btn btn-block btn-primary btn-sm mt-2 save_modify' type='button' 
                                    name='save_{$row['diary_no']}' id='save_{$row['diary_no']}'>Save</button>

                            <button data-updation_method='single'
                                    data-diary_no='{$row['diary_no']}'
                                    data-conn_key='{$row['conn_key']}'
                                    data-next_dt='{$row['next_dt']}'
                                    data-roster_id='{$row['roster_id']}'
                                    data-clno='{$row['clno']}'
                                    data-main_supp_flag='{$row['main_supp_flag']}'
                                    data-action='modify'
                                    class='btn btn-block btn-secondary btn-sm btn-warning save_modify' type='button' 
                                    name='modify_{$row['diary_no']}' id='modify_{$row['diary_no']}'>Revert</button>
                    </td>
                </tr>";
    }

    // Helper function to format the court number
    private function formatCourtNumber($courtNo)
    {
        return $courtNo > 60 ? "VC R " . ($courtNo - 60) : ($courtNo > 30 ? "VC " . ($courtNo - 30) : ($courtNo > 20 ? "R " . $courtNo : $courtNo));
    }

    // Helper function to format the list type
    private function formatListType($row)
    {
        return $row['mainhead'] == 'F' ? 'Regular List' : ($row['mainhead'] == 'M' && $row['board_type'] == 'J' ? 'Misc. List' : ($row['mainhead'] == 'M' && $row['board_type'] == 'C' ? 'Chamber List' : ($row['mainhead'] == 'M' && $row['board_type'] == 'R' ? 'Registrar List' : '')));
    }

    private function getAorDataLoop($diary_no, $next_dt, $roster_id, $advocate_ids, $row)
    {
        $aorResult = $this->vcconsent->getAorData($diary_no, $next_dt, $roster_id, $advocate_ids);

        $aorData = '';

        if (!empty($aorResult['aorData']) || !empty($aorResult['partyData'])) {
            // Start building the AOR data with the new card structure
            $aorData .= '<div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="align-items-center table table-hover table-striped">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th scope="col" style="min-width: 0">
                                                    <div class="form-check">
                                                        <input class="form-check-input" style="margin-top: 5px;" type="checkbox" name="all_' . $row['diary_no'] . '" 
                                                               id="all_' . $row['diary_no'] . '" class="aorCheckboxAll" 
                                                               data-diaryid="' . $row['diary_no'] . '" value="ALL"/>
                                                        <label class="form-check-label text-white" style="margin-left: 35px;" for="all_' . $row['diary_no'] . '">ALL</label>
                                                    </div>
                                                </th>
                                                <th scope="col">Name</th>
                                                <th scope="col">Email</th>
                                                <th scope="col">Mobile</th>
                                            </tr>
                                        </thead>
                                        <tbody>';

            $ctn = 1;

            // Loop through AOR data
            foreach ($aorResult['aorData'] as $aorPpData) {
                $applicant_selected = ($aorPpData['id'] != null) ? "checked" : "";
                $checkbox_string = ($aorPpData['entry_source'] == 2)
                    ? "Online"
                    : ((strlen($aorPpData['email']) > 5 || strlen($aorPpData['mobile']) == 10)
                        ? '<div class="form-check">
                              <input class="form-check-input" type="checkbox" data-applicant_type="1" data-applicant_id="' . $aorPpData['bar_id'] . '" 
                                     name="' . $row['diary_no'] . '" id="' . $row['diary_no'] . '_' . $ctn . '" ' . $applicant_selected . ' />
                              <label class="form-check-label" for="' . $row['diary_no'] . '_' . $ctn . '"></label>
                          </div>'
                        : "");

                $aorData .= '<tr>
                                <td style="min-width: 0">' . $checkbox_string . '</td>
                                <td>' . $aorPpData['name'] . ' (AOR - ' . $aorPpData['aor_code'] . ')</td>
                                <td>' . $aorPpData['email'] . '</td>
                                <td>' . $aorPpData['mobile'] . '</td>
                            </tr>';
                $ctn++;
            }

            // Loop through Party data
            foreach ($aorResult['partyData'] as $ppData) {
                $applicant_selected = ($ppData['id'] != null) ? "checked" : "";
                $checkbox_string = ($ppData['entry_source'] == 2)
                    ? "Online"
                    : ((strlen($ppData['email']) > 5 || strlen($ppData['contact']) == 10)
                        ? '<div class="form-check">
                              <input class="form-check-input" type="checkbox" data-applicant_type="2" data-applicant_id="' . $ppData['auto_generated_id'] . '" 
                                     name="' . $row['diary_no'] . '" id="' . $row['diary_no'] . '_' . $ctn . '" ' . $applicant_selected . ' />
                              <label class="form-check-label" for="' . $row['diary_no'] . '_' . $ctn . '"></label>
                          </div>'
                        : "");

                $aorData .= '<tr>
                                <td style="min-width: 0">' . $checkbox_string . '</td>
                                <td>' . $ppData['partyname'] . ' (' . $ppData['name'] . ')</td>
                                <td>' . $ppData['email'] . '</td>
                                <td>' . $ppData['contact'] . '</td>
                            </tr>';
                $ctn++;
            }
            $aorData .= "</tbody></table></div></div></div>";
        }


        return $aorData;
    }

    public function saveAorCaseData()
    {
        $response = [];
        $request = service('request');
    
        $updation_method = !empty($request->getVar('updation_method')) ? trim($request->getVar('updation_method')) : null;
        $action = !empty($request->getVar('action')) ? trim($request->getVar('action')) : null;
        $userArr = !empty($request->getVar('userArr')) ? $request->getVar('userArr') : null;
    
        if (!empty($updation_method)) {
            $diary_no = !empty($request->getVar('diary_no')) ? (int)trim($request->getVar('diary_no')) : null;
            $conn_key = !empty($request->getVar('conn_key')) ? (int)trim($request->getVar('conn_key')) : 0;
            $roster_id = !empty($request->getVar('roster_id')) ? (int)trim($request->getVar('roster_id')) : null;
            $part = !empty($request->getVar('clno')) ? (int)trim($request->getVar('clno')) : null;
            $main_supp_flag = !empty($request->getVar('main_supp_flag')) ? (int)trim($request->getVar('main_supp_flag')) : null;
            $current_date = date('Y-m-d H:i:s');
            $next_dt = !empty($request->getVar('next_dt')) ? date('Y-m-d', strtotime(trim($request->getVar('next_dt')))) : null;
            $user_id = $_SESSION['dcmis_user_idd'];
            $user_ip = $this->request->getIPAddress();
    
            $count_success = 0;
            $count_error = 0;
    
            switch ($updation_method) {
                case 'single':
                    if (!empty($diary_no) && isset($userArr) && !empty($userArr) && count($userArr) > 0) {
                        foreach ($userArr as $v) {
                            $applicant_id = !empty($v['applicant_id']) ? (int)$v['applicant_id'] : null;
                            $applicant_type = !empty($v['applicant_type']) ? $v['applicant_type'] : null;
                            $where = $applicant_type == 1 ? "advocate_id = $applicant_id" : "party_id = $applicant_id";
    
                            // Delete existing record using the model
                            $this->ctem ->deleteConsent($diary_no, $next_dt, $where);
    
                            // Prepare new data for insertion
                            $data = [
                                'diary_no' => $diary_no,
                                'conn_key' => $conn_key,
                                'next_dt' => $next_dt,
                                'roster_id' => $roster_id,
                                'part' => $part,
                                'main_supp_flag' => $main_supp_flag,
                                'applicant_type' => $applicant_type,
                                'party_id' => $applicant_type == 1 ? $applicant_id : null,
                                'advocate_id' => $applicant_type == 1 ? null : $applicant_id,
                                'user_id' => $user_id,
                                'entry_date' => $current_date,
                                'user_ip' => $user_ip
                            ];
    
                            // Insert new record using the model
                            $this->ctem ->createConsent($data);
    
                            if ($this->vcconsent->affectedRows() > 0) {
                                $count_success++;
                            } else {
                                $count_error++;
                            }
                        }
    
                        $response = [
                            "status" => "success",
                            "count_success" => $count_success,
                            "count_error" => $count_error
                        ];
                    } else {
                        $response = ["status" => "Error: No diary number or users found."];
                    }
                    break;
    
                case 'modify':
                    if (!empty($diary_no) && isset($userArr) && !empty($userArr) && count($userArr) > 0) {
                        foreach ($userArr as $v) {
                            $applicant_id = !empty($v['applicant_id']) ? (int)$v['applicant_id'] : null;
                            $applicant_type = !empty($v['applicant_type']) ? $v['applicant_type'] : null;
                            $where = $applicant_type == 1 ? "advocate_id = $applicant_id" : "party_id = $applicant_id";
    
                            // Delete existing record using the model
                            $this->ctem ->deleteConsent($diary_no, $next_dt, $where);
    
                            if ($this->vcconsent->affectedRows() > 0) {
                                $count_success++;
                            } else {
                                $count_error++;
                            }
                        }
    
                        $response = [
                            "status" => "success",
                            "count_success" => $count_success,
                            "count_error" => $count_error
                        ];
                    } else {
                        $response = ["status" => "Error: No diary number or users found."];
                    }
                    break;
    
                case 'bulk':
                        $response = ["status" => "No logic found for Bulk update"];
                    break;
    
                default:
                    $response = ["status" => "Error: Invalid updation method."];
            }
        } else {
            $response = ["status" => "Error: No updation method provided."];
        }
    
        return $this->response->setJSON($response);
    }
    


}
