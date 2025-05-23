<?php

namespace App\Controllers\Court;

use App\Controllers\BaseController;
use CodeIgniter\Controller;
use App\Models\Court\CourtMasterModel;
use App\Libraries\phpqrcode\Qrlib;
use App\Libraries\Fpdf;
use ZipArchive;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use App\Models\Common\Dropdown_list_model;
use App\Controllers\Court\LIVE_URL;
use DirectoryIterator;
use FilesystemIterator;

class CourtMasterController extends BaseController
{
    public $model;
    public $diary_no;
    public $qrlib;
    public $Fpdf;
    public $Dropdown_list_model;

    function __construct()
    {

        $this->model = new CourtMasterModel();
        $this->qrlib = new Qrlib();
        $this->Fpdf = new Fpdf();
        $this->Dropdown_list_model = new Dropdown_list_model();
		$this->request = service('request');
		$uri = $this->request->uri;

        $segment1 = $uri->getSegment(1); // First segment
        $segment2 = $uri->getSegment(2); // Second segment
        $segment3 = $uri->getSegment(3); // Third segment (if exists)
      
    }

    public function index()
    {
        $diary_no = $this->diary_no;
        $getCaseType = $this->model->getCaseType();
        $usercode = session()->get('login')['usercode'];
        $data['caseTypes'] = $getCaseType;
        $data['usercode'] = $usercode;


        return view('Court/CourtMaster/showEmbedQR', $data);
    }

    public function proceedings()
    {
        $diary_no = $this->diary_no;

        $judge = $this->model->getJudge();
        $usercode = session()->get('login')['usercode'];

        $data['app_name'] = 'Generate ROP';
        $data['judge'] = $judge;
        $data['usercode'] = $usercode;

        return view('Court/CourtMaster/selectDetailForROP', $data);
    }

    public function getBench()
    {
        extract($this->request->getGet());
        $main_head = '';
        $main_supp_flag = '';
        $board_type = '';
        switch ($causelistType) {
            case 1:
                $main_head = 'F';
                $main_supp_flag = '1';
                $board_type = 'J';
                break;
            case 2:
                $main_head = 'F';
                $main_supp_flag = '2';
                $board_type = 'J';
                break;
            case 3:
                $main_head = 'M';
                $main_supp_flag = '1';
                $board_type = 'J';
                break;
            case 4:
                $main_head = 'M';
                $main_supp_flag = '2';
                $board_type = 'J';
                break;
            case 5:
                //$main_head='M';
                $main_supp_flag = '1';
                $board_type = 'C';
                break;
            case 6:
                //$main_head='M';
                $main_supp_flag = '2';
                $board_type = 'C';
                break;
            case 7:
                $main_head = 'M';
                $main_supp_flag = '1';
                $board_type = 'R';
                break;
            case 8:
                $main_head = 'M';
                $main_supp_flag = '2';
                $board_type = 'R';
                break;
            case 9:
                $main_head = 'M';
                $main_supp_flag = '1';
                $board_type = 'CC';
                break;
            case 11:
                $main_head = 'M';
                $main_supp_flag = '1';
                $board_type = 'S';
                break;
        }
        $benches = $this->model->getBenchByJudge($main_head, $board_type, $causelistDate, $pJudge);
        $result = "<option value=''>Select Bench</option>";
        $bench_desc = "";
        foreach ($benches as $bench) {
            // pr($bench);
            $court = "";
            if ($bench['courtno'] == 21)
                $court = "R1";
            else if ($bench['courtno'] == 22)
                $court = "R2";
            else if ($bench['courtno'] > 30 && $bench['courtno'] <= 60) {
                $court = "VC- " . ($bench['courtno'] - 30);
            } else if ($bench['courtno'] > 60 && $bench['courtno'] <= 62) {
                $court = "RVC- " . ($bench['courtno'] - 60);
            } else
                $court = $bench['courtno'];
            if ($bench['session'] == 'Whole Day' && $bench['courtno'] != "" && $bench['courtno'] != 0 && $bench['courtno'] != null) {
                $bench_desc = $bench['session'] . ' in Court ' . $court;
            } else if ($bench['courtno'] != "" && $bench['courtno'] != 0 && $bench['courtno'] != null) {
                $bench_desc = $bench['session'] . ' @ ' . $bench['frm_time'] . ' in Court ' . $court;
            } else {
                $bench_desc = $bench['session'];
            }
            $result .= "<option value='" . $bench['roster_id'] . "'>" . $bench_desc . "</option>";
        }
        echo $result;
    }
	
	public function getBenchMM()
    {
        extract($this->request->getGet());
        $main_head = '';
        $main_supp_flag = '';
        $board_type = '';
        switch ($causelistType) {
            case 1:
                $main_head = 'F';
                $main_supp_flag = '1';
                $board_type = 'J';
                break;
            case 2:
                $main_head = 'F';
                $main_supp_flag = '2';
                $board_type = 'J';
                break;
            case 3:
                $main_head = 'M';
                $main_supp_flag = '1';
                $board_type = 'J';
                break;
            case 4:
                $main_head = 'M';
                $main_supp_flag = '2';
                $board_type = 'J';
                break;
            case 5:
                //$main_head='M';
                $main_supp_flag = '1';
                $board_type = 'C';
                break;
            case 6:
                //$main_head='M';
                $main_supp_flag = '2';
                $board_type = 'C';
                break;
            case 7:
                $main_head = 'M';
                $main_supp_flag = '1';
                $board_type = 'R';
                break;
            case 8:
                $main_head = 'M';
                $main_supp_flag = '2';
                $board_type = 'R';
                break;
            case 9:
                $main_head = 'M';
                $main_supp_flag = '1';
                $board_type = 'CC';
                break;
                case 1:
                    $main_head = 'F';
                    $main_supp_flag = '1';
                    $board_type = 'J';
                    break;
                case 2:
                    $main_head = 'F';
                    $main_supp_flag = '2';
                    $board_type = 'J';
                    break;
                case 3:
                    $main_head = 'M';
                    $main_supp_flag = '1';
                    $board_type = 'J';
                    break;
                case 4:
                    $main_head = 'M';
                    $main_supp_flag = '2';
                    $board_type = 'J';
                    break;
                case 5:
                    //$main_head='M';
                    $main_supp_flag = '1';
                    $board_type = 'C';
                    break;
                case 6:
                    //$main_head='M';
                    $main_supp_flag = '2';
                    $board_type = 'C';
	   case 11:
                $main_head = 'M';
                $main_supp_flag = '1';
                $board_type = 'S';
                break;

        }
        //$data['bench']=$this->CourtMasterModel->getBenchByCourt();
        $benches = $this->model->getBenchByJudgeModified($causelistDate, $pJudge, $main_head,$board_type);
        //var_dump($city);
        $result = "<option value=''>Select Bench</option>";
        $bench_desc = "";
        foreach ($benches as $bench) {
            $court = "";
            if ($bench['courtno'] == 21)
                $court = "R1";
            else if ($bench['courtno'] == 22)
                $court = "R2";
            else if ($bench['courtno'] > 30 && $bench['courtno'] <= 60) {
                $court = "VC- ".($bench['courtno']-30);
            }
            else if ($bench['courtno'] > 60 && $bench['courtno'] <= 62) {
                $court = "RVC- ".($bench['courtno']-60);
            }
            else
                $court = $bench['courtno'];
            if ($bench['session'] == 'Whole Day' && $bench['courtno'] != "" && $bench['courtno'] != 0 && $bench['courtno'] != null) {
                $bench_desc = $bench['session'] . ' in Court ' . $court;
            } else if ($bench['courtno'] != "" && $bench['courtno'] != 0 && $bench['courtno'] != null) {
                $bench_desc = $bench['session'] . ' @ ' . $bench['frm_time'] . ' in Court ' . $court;
            } else {
                $bench_desc = $bench['session'];
            }
            /*if($bench['session']=='Whole Day' && $bench['courtno']!="" && $bench['courtno']!=0 && $bench['courtno']!=null){
                $bench_desc= $bench['session'].' in Court '.$bench['courtno'];
            }
            else if($bench['courtno']!="" && $bench['courtno']!=0 && $bench['courtno']!=null){
                $bench_desc= $bench['session'].' @ '.$bench['frm_time'].' in Court '.$bench['courtno'];
            }
            else{
                $bench_desc= $bench['session'];
            }*/
            $result .= "<option value='" . $bench['roster_id'] . "'>" . $bench_desc . "</option>";
            //$result.="<option value='".$bench['roster_id']."'>".$bench['judgename']."</option>";
        }
        echo $result;

    }

	
    public function getCasesForGeneration()
    {

        extract($this->request->getGet());
        $main_head = '';
        $main_supp_flag = '';
        $board_type = '';
        switch ($causelistType) {
            case 1:
                $main_head = 'F';
                $main_supp_flag = '1';
                $board_type = 'J';
                break;
            case 2:
                $main_head = 'F';
                $main_supp_flag = '2';
                $board_type = 'J';
                break;
            case 3:
                $main_head = 'M';
                $main_supp_flag = '1';
                $board_type = 'J';
                break;
            case 4:
                $main_head = 'M';
                $main_supp_flag = '2';
                $board_type = 'J';
                break;
            case 5:
                //$main_head='M';
                $main_supp_flag = '1';
                $board_type = 'C';
                break;
            case 6:
                //$main_head='M';
                $main_supp_flag = '2';
                $board_type = 'C';
                break;
            case 7:
                $main_head = 'M';
                $main_supp_flag = '1';
                $board_type = 'R';
                break;
            case 8:
                $main_head = 'M';
                $main_supp_flag = '2';
                $board_type = 'R';
                break;
            case 9:
                $main_head = 'M';
                $main_supp_flag = '1';
                $board_type = 'C';
                break;
            case 11:
                $main_head = 'M';
                $main_supp_flag = '1';
                $board_type = 'S';
                break;
        }
        $data['judge'] = $this->model->getJudge();
        $data['cmnsh'] = $this->model->getCmNsh();
        $userdetail = $this->model->getUserDetail($usercode);
        $courtJudges = $this->model->getCoramInCourt($bench);
        $data['caseList'] = $this->model->getCaseGenerationList($bench, $main_head, $main_supp_flag, $board_type, $causelistDate);

        $judgeinitial = array('0', '0', '0', '0', '0');
        $judgedetail = explode(",", $courtJudges[0]['coram']);
        $courtno = $courtJudges[0]['courtno'];
        $courtNoDisplay = "";
        if ($courtno == 21)
            $courtNoDisplay = "R1";
        else if ($courtno == 22)
            $courtNoDisplay = "R2";
        else if ($courtno > 30 && $courtno <= 60) {
            $courtNoDisplay = "Virtual Court " . ($courtno - 30);
        } else if ($courtno > 60 && $courtno <= 62) {
            $courtNoDisplay = "Registrar Virtual Court- " . ($courtno - 60);
        } else
            $courtNoDisplay = $courtno;

        $username = $userdetail[0]['name'];
        $judgesize = count($judgedetail);
        if ($judgesize > 0) {
            for ($i = 0; $i < $judgesize; $i++) {
                $judgeinitial[$i] = $judgedetail[$i];
            }
        }

        $data['judgeinitial'] = $judgeinitial;
        $data['courtNoDisplay'] = $courtNoDisplay;
        $data['username'] = $username;
        $data['courtno'] = $courtno;

        return view('Court/CourtMaster/casesForROPGeneration', $data);
    }

    public function generateRop()
    {
        extract($this->request->getPost());

        $diary_no = $roster_id = $court_no = $item_number = "";
        $checkedCases = "";
        $reg = [];
        foreach (array_keys($judge, '0') as $key) {
            unset($judge[$key]);
        }
        $coram = rtrim(implode(',', $judge), ',');

        //echo '<pre>';
        foreach ($proceeding as $case) {

            $reportable = 0;
            $reportable = $this->request->getPost($case);

            $docDataString = "";
            $datatest = explode('#', $case);

            $diary_no = $datatest[0];
            $roster_id = $datatest[1];
            $court_no = $datatest[2];
            $courtNoDisplay = 0;

            if ($court_no == 21) {
                $courtNoDisplay = 1;
            } elseif ($court_no == 22) {
                $courtNoDisplay = 2;
            } elseif ($court_no > 30 && $court_no <= 60) {
                $courtNoDisplay = "Court " . ($court_no - 30) . " (Video Conferencing)";
            } elseif ($court_no > 60 && $court_no <= 62) {
                $courtNoDisplay = "" . ($court_no - 60) . " THROUGH VIDEO CONFERENCE";
            }

            $item_number = $datatest[3];
            $caseDetails = $this->model->getCaseDetails($diary_no, $roster_id, $causelistDate);
            $connectedCaseDetails = $this->model->connectedCaseDetails($diary_no, $roster_id, $causelistDate);

            $connectedDiaries = [];
            foreach ($connectedCaseDetails as $connC) {
                array_push($connectedDiaries, $connC['diary_no']);
            }
            array_push($connectedDiaries, $diary_no);

            $pAdvDetails = $this->model->getAdvocateDetails($connectedDiaries, 'P');
            $rAdvDetails = $this->model->getAdvocateDetails($connectedDiaries, 'R');

            $lowerCourtDetails = $this->model->getLowerCourtDetails($diary_no);

            if ($caseDetails !== false) {
                $section_name = $caseDetails->section_name;
                if ($caseDetails->section_name == null || $caseDetails->section_name == '') {
                    //$tentativeSection = $this->model->getTentativeSection($diary_no);
                    //$section_name = $tentativeSection->section;
                }

                $docDataString .= "</w:t> <w:cr/><w:t>" . "</w:t> <w:cr/><w:t>";
                $docDataString .= "</w:t> <w:cr/><w:t>" . "</w:t> <w:cr/><w:t>";
                $docDataString .= "</w:t> <w:cr/><w:t>" . "</w:t> <w:cr/><w:t>";

                $docDataString .= "ITEM NO." . $caseDetails->item_number;

                if ($causelistType == 9)
                    $docDataString .= "                        " . "               SECTION " . $section_name;
                else if ($causelistType == 7)
                   $docDataString .= "        REGISTRAR COURT           SECTION " . $section_name;
                else if ($court_no >= 31 && $court_no <= 47)
                    $docDataString .= "     " . $courtNoDisplay . "          SECTION " . $section_name;
                else
                    $docDataString .= "               COURT NO." . $court_no . "               SECTION " . $section_name;
                $docDataString .= "</w:t> <w:cr/><w:t>" . "</w:t> <w:cr/><w:t>" . "               S U P R E M E  C O U R T  O F  I N D I A" . "</w:t> <w:cr/><w:t>" . "                       RECORD OF PROCEEDINGS" . "</w:t> <w:cr/><w:t></w:t> <w:cr/><w:t>";

                if ($causelistType == 7) {
                    $judges = $this->model->getJudgeName($coram);
                    foreach ($judges as $j) {
                        $docDataString .= "</w:t> <w:cr/><w:t>" . "                     BEFORE THE REGISTRAR " . $j['first_name'] . " " . $j['sur_name'] . "</w:t> <w:cr/><w:t>" . "</w:t> <w:cr/><w:t>";
                    }
                }

                /* if (($caseDetails->casetype_id != 13) && ($caseDetails->casetype_id != 14) && $caseDetails->ia != null && !($caseDetails->ia == "") && !($caseDetails->ia == "null")) {
                    $docDataString .= 'IA ' . $caseDetails->ia . " in ";
                } */
                $regNoDisplay = substr($caseDetails->reg_no_display, (strripos($caseDetails->reg_no_display, " ")));

                if ($caseDetails->registration_number == "" || $caseDetails->registration_number == null) {
                    $diarynumber = $caseDetails->diary_no;
                    $docDataString .= $caseDetails->diary_casetype . " Diary No(s). " . substr($diarynumber, 0, -4) . "/" . substr($diarynumber, -4);
                } else if (explode('-', $caseDetails->registration_number)[0] == 31) {
                    $docDataString .= $caseDetails->reg_no_display;
                } else {
                    if ($caseDetails->casetype_id == 1)
                        $docDataString .= "Petition(s) for Special Leave to Appeal (C) ";
                    else if ($caseDetails->casetype_id == 2)
                        $docDataString .= "Petition(s) for Special Leave to Appeal (Crl.) ";
                    else if ($caseDetails->casetype_id == 3)
                        $docDataString .= "Civil Appeal ";
                    else if ($caseDetails->casetype_id == 4)
                        $docDataString .= "Criminal Appeal ";
                    else if ($caseDetails->casetype_id == 5)
                        $docDataString .= "Writ Petition(s)(Civil) ";
                    else if ($caseDetails->casetype_id == 6)
                        $docDataString .= "Writ Petition(s)(Criminal) ";
                    else if ($caseDetails->casetype_id == 7)
                        $docDataString .= "Transfer Petition(s)(Civil) ";
                    else if ($caseDetails->casetype_id == 8)
                        $docDataString .= "Transfer Petition(s)(Criminal) ";
                    else if ($caseDetails->casetype_id == 9)
                        $docDataString .= " " . $caseDetails->reg_no_display;
                    else if ($caseDetails->casetype_id == 10)
                        $docDataString .= " " . $caseDetails->reg_no_display;
                    else if ($caseDetails->casetype_id == 11)
                        $docDataString .= "Transfer Case (Civil) ";
                    else if ($caseDetails->casetype_id == 12)
                        $docDataString .= "Transfer Case (Criminal) ";
                    else if ($caseDetails->casetype_id == 13)
                        $docDataString .= "Petition(s) for Special Leave to Appeal (C)......CC ";
                    else if ($caseDetails->casetype_id == 14)
                        $docDataString .= "Petition(s) for Special Leave to Appeal (Crl.)...... CRLMP ";
                    else if ($caseDetails->casetype_id == 15)
                        $docDataString .= "Petition(s) for Writ (Civil)........CC ";
                    else if ($caseDetails->casetype_id == 16)
                        $docDataString .= "Petition(s) for Writ (Criminal)..........CRLMP ";
                    else if ($caseDetails->casetype_id == 17)
                        $docDataString .= "Original Suit (s).";
                    else if ($caseDetails->casetype_id == 18)
                        $docDataString .= "Petition(s) for Death Reference Case ";
                    else if ($caseDetails->casetype_id == 19)
                        $docDataString .= " " . $caseDetails->reg_no_display;
                    else if ($caseDetails->casetype_id == 20)
                        $docDataString .= " " . $caseDetails->reg_no_display;
                    else if ($caseDetails->casetype_id == 21)
                        $docDataString .= "Petition(s) for Tax Reference Case ";
                    else if ($caseDetails->casetype_id == 22)
                        $docDataString .= "Petition(s) for Special Reference Case ";
                    else if ($caseDetails->casetype_id == 23)
                        $docDataString .= "Petition(s) for Election (Civil) ";
                    else if ($caseDetails->casetype_id == 24)
                        $docDataString .= "Petition(s) for Arbitration ";
                    else if ($caseDetails->casetype_id == 25)
                        $docDataString .= " " . $caseDetails->reg_no_display;
                    else if ($caseDetails->casetype_id == 26)
                        $docDataString .= " " . $caseDetails->reg_no_display;
                    else if ($caseDetails->casetype_id == 27)
                        $docDataString .= "Petition(s) for REF. U/A 317(1) ";
                    else if ($caseDetails->casetype_id == 39) {
                        //$docDataString .="Miscellaneous Application ";
                        $docDataString .= $this->str_replace_once('MA', 'Miscellaneous Application No. ', $caseDetails->reg_no_display);
                    } else if ($caseDetails->casetype_id == 28)
                        $docDataString .= "Petition(s) for Motion ";
                    else if ($caseDetails->casetype_id == 29)
                        $docDataString .= "Petition(s) for Diary ";
                    else if ($caseDetails->casetype_id == 30)
                        $docDataString .= "Petition(s) for File ";
					else if ($caseDetails->casetype_id == 32)
					$docDataString .= "SUO MOTO WRIT PETITION(CIVIL) ";
					else if ($caseDetails->casetype_id == 33)
					$docDataString .= "SUO MOTO WRIT PETITION(CRIMINAL) ";
					else if ($caseDetails->casetype_id == 34)
					$docDataString .= "SUO MOTO CONTEMPT PETITION(CIVIL) ";
					else if ($caseDetails->casetype_id == 35)
					$docDataString .= "SUO MOTO CONTEMPT PETITION(CRIMINAL) ";
					else if ($caseDetails->casetype_id == 36)
					$docDataString .= "REF. U/S 143 ";
					else if ($caseDetails->casetype_id == 37)
					$docDataString .= "REF. U/S 14 RTI ";
					else if ($caseDetails->casetype_id == 38)
					$docDataString .= "REF. U/S 17 RTI ";
					else if ($caseDetails->casetype_id == 40)
					$docDataString .= "SUO MOTO TRANSFER PETITION(CIVIL) ";
					else if ($caseDetails->casetype_id == 41)
					$docDataString .= "SUO MOTO TRANSFER PETITION(CRIMINAL) ";

                    if ($caseDetails->casetype_id != 39 && $caseDetails->casetype_id != 9 && $caseDetails->casetype_id != 10 && $caseDetails->casetype_id != 19 && $caseDetails->casetype_id != 20 && $caseDetails->casetype_id != 25 && $caseDetails->casetype_id != 26 && $caseDetails->registration_number != "" && $caseDetails->registration_number != null) {
                        $docDataString .= " No(s). " . $regNoDisplay;
                    }
                }

                $docDataString .= "</w:t> <w:cr/><w:t></w:t> <w:cr/><w:t>";

                if ($causelistType != 5 && $causelistType != 7) {
                    if (($caseDetails->casetype_id != 3) && ($caseDetails->casetype_id != 4) && ($caseDetails->casetype_id != 5) && ($caseDetails->casetype_id != 6) && ($caseDetails->casetype_id != 7) && ($caseDetails->casetype_id != 8) && ($caseDetails->casetype_id != 19) && ($caseDetails->casetype_id != 20)) {
                        $lowerCourtCaseNumber = $judgementdt = $agencyname = "";
                        if (count($lowerCourtDetails) > 0) {
							$docDataString .= "[Arising out of impugned final judgment and order dated ";
                            foreach ($lowerCourtDetails as $lowerDetail) {
                                if ($lowerDetail['casetype'] != null && $lowerDetail['lct_caseno'] != null && $lowerDetail['casetype'] != "" && $lowerDetail['lct_caseno'] != "" && $lowerDetail['lct_dec_dt'] != null) {
                                    $lowerCourtCaseNumber = $lowerDetail['casetype'] . " No. " . $lowerDetail['lct_caseno'] . "/" . $lowerDetail['lct_caseyear'];
                                    $judgementdt = date("d-m-Y", strtotime($lowerDetail['lct_dec_dt']));
                                    $docDataString .= " " . $judgementdt . " in " . $lowerCourtCaseNumber . ",";
                                    $agencyname = $lowerDetail['agency_name'];
                                    $docDataString .= ",";
                                }
                                $docDataString = substr($docDataString, 0, strlen($docDataString) - 2);
                            }
                            // $agencyname = $this->CourtMasterModel->convertToTitleCase($agencyname);
                            $docDataString .= " passed by the " . $agencyname . "]</w:t> <w:cr/><w:t></w:t> <w:cr/><w:t>";
                        } else {
                            $docDataString .= " ";
                        }
                    }
                }

                $petitionername = "";
                if ($caseDetails->pno > 2)
                    $petitionername = $caseDetails->pet_name . " & ORS.";
                else if ($caseDetails->pno == 2)
                    $petitionername = $caseDetails->pet_name . " & ANR.";
                else
                    $petitionername = $caseDetails->pet_name;


                $applength = strlen($petitionername);
                $linelength = (51 - $applength);
                $docDataString .= $petitionername;
                for ($l = 1; $l <= $linelength; $l++) {
                    $docDataString .= " ";
                }
                if ($caseDetails->casetype_id == 3 || $caseDetails->casetype_id == 4) {
                    $docDataString .= "Appellant(s)" . "</w:t> <w:cr/><w:t></w:t> <w:cr/><w:t>";
                } else {
                    $docDataString .= "Petitioner(s)" . "</w:t> <w:cr/><w:t></w:t> <w:cr/><w:t>";
                }
                $docDataString .= "                                VERSUS</w:t> <w:cr/><w:t></w:t> <w:cr/><w:t>";


                $respondentname = "";
                if ($caseDetails->rno > 2)
                    $respondentname = $caseDetails->res_name . " & ORS.";
                else if ($caseDetails->rno == 2)
                    $respondentname = $caseDetails->res_name . " & ANR.";
                else
                    $respondentname = $caseDetails->res_name;

                $applength = strlen($respondentname);
                $linelength = (51 - $applength);
                $docDataString .= $respondentname;
                for ($l = 1; $l <= $linelength; $l++) {
                    $docDataString .= " ";
                }
                $docDataString .= "Respondent(s)" . "</w:t> <w:cr/><w:t></w:t> <w:cr/><w:t>";

                //For fetching IA details
                $listed_ia = "";
                $doc_desrip = "";
                $iaDetails = [];
                $listed_ia = (is_null($caseDetails->ia)) ? $caseDetails->ia : rtrim(trim($caseDetails->ia), ",");
                if ($listed_ia != null && $listed_ia != "")
                    $iaDetails = $this->model->getIADetails($listed_ia, $diary_no);


                if (count($iaDetails) > 0) {
                    foreach ($iaDetails as $iaDetail) {
                        $doc_desrip .= "</w:t><w:cr/> <w:t>";
                        $doc_desrip .= "IA No. " . $iaDetail['docnum'] . "/" . $iaDetail['docyear'] . " - " . $iaDetail['docdesp'];
						$doc_desrip .= "</w:t><w:t>";
                    }
                }

                if ($caseDetails->remark != null && $caseDetails->remark != "") {
                    $remarks = strip_tags($caseDetails->remark);
                    $docDataString .= $remarks . " ";
                }

                $docDataString .= $doc_desrip . "</w:t> <w:cr/><w:t> ";

                //For Conncted matters and their Applications
                if (count($connectedCaseDetails) > 0) {
                    $docDataString .= "</w:t> <w:cr/><w:t>WITH</w:t> <w:cr/><w:t>";
                }

                foreach ($connectedCaseDetails as $connDetails) {
                    $connCaseSection = "";
                    $connCaseSection = $connDetails['section_name'];
                    if ($connDetails['section_name'] == null || $connDetails['section_name'] == '') {
                        //$tentativeSectionConn = $this->model->getTentativeSection($connDetails['diary_no']);
                        //$connCaseSection = $tentativeSectionConn->section;
                    }
                    if ($connDetails['reg_no_display'] != null)
                        $docDataString .= $connDetails['reg_no_display'] . " (" . $connCaseSection . ")</w:t> <w:cr/><w:t>";
                    else
                        $docDataString .= "Diary No(s). " . substr($connDetails['diary_no'], 0, -4) . "/" . substr($connDetails['diary_no'], -4) . " (" . $connCaseSection . ")</w:t> <w:cr/><w:t>";

                    //For fetching IA details
                    $conn_listed_ia = "";
                    $conn_doc_desrip = "";
                    $connIaDetails = [];

                    $conn_listed_ia = (is_null($connDetails['ia'])) ? $connDetails['ia'] : rtrim(trim($connDetails['ia']), ",");

                    if ($conn_listed_ia != null && $conn_listed_ia != "")
                        $connIaDetails = $this->model->getIADetails($conn_listed_ia, $connDetails['diary_no']);
                    if (count($connIaDetails) > 0) {
                        foreach ($connIaDetails as $connIaDetail) {
                            $conn_doc_desrip .= "</w:t><w:cr/> <w:t>";
                            $conn_doc_desrip .= "IA No. " . $connIaDetail['docnum'] . "/" . $connIaDetail['docyear'] . " - " . $connIaDetail['docdesp'];
                            $conn_doc_desrip .= "</w:t> <w:t>";
                        }
                    }
                    //END 


                    if (($connDetails['remark'] != null && $connDetails['remark'] != "") ||($conn_doc_desrip != null && $conn_doc_desrip != "")) {
						$docDataString .=  $connDetails['remark']. $conn_doc_desrip . "</w:t> <w:cr/><w:t> ";

                    }

                }

                $listingdate = date("d-m-Y", strtotime($causelistDate));

                if ($caseDetails->ia != null && $caseDetails->ia != "" && $caseDetails->ia != 'null') {
                    $iaArray = explode(',', $caseDetails->ia);
                    if (count($connectedCaseDetails) > 0) {
                        $docDataString .= "</w:t> <w:cr/><w:t>Date : " . $listingdate . " These matters were called on for hearing today.</w:t> <w:cr/><w:t></w:t> <w:cr/><w:t>";
                    } else {
                        $docDataString .= "</w:t> <w:cr/><w:t>Date : " . $listingdate . " This matter was called on for hearing today.</w:t> <w:cr/><w:t></w:t> <w:cr/><w:t>";
                    }
                } else if ($caseDetails->casetype_id == 3 || $caseDetails->casetype_id == 4) {
                    if ($caseDetails->registration_number != null) {
                        $reg = explode("-", $caseDetails->registration_number);
                        if (count($reg) > 2) {
                            if ($reg[1] == $reg[2])
                                $docDataString .= "</w:t> <w:cr/><w:t>Date : " . $listingdate . " This appeal was called on for hearing today.</w:t> <w:cr/><w:t></w:t> <w:cr/><w:t>";
                            else
                                $docDataString .= "</w:t> <w:cr/><w:t>Date : " . $listingdate . " These appeals were called on for hearing today.</w:t> <w:cr/><w:t></w:t> <w:cr/><w:t>";
                        } else if (count($reg) > 1) {
                            $docDataString .= "</w:t> <w:cr/><w:t>Date : " . $listingdate . " This appeal was called on for hearing today.</w:t> <w:cr/><w:t></w:t> <w:cr/><w:t>";
                        } else {
                            $docDataString .= "</w:t> <w:cr/><w:t>Date : " . $listingdate . " This appeal was called on for hearing today.</w:t> <w:cr/><w:t></w:t> <w:cr/><w:t>";
                        }
                    } else {
                        $docDataString .= "</w:t> <w:cr/><w:t>Date : " . $listingdate . " This appeal was called on for hearing today.</w:t> <w:cr/><w:t></w:t> <w:cr/><w:t>";
                    }
                } else if ($caseDetails->casetype_id == 9 || $caseDetails->casetype_id == 10 || $caseDetails->casetype_id == 25 || $caseDetails->casetype_id == 26) {
                    $docDataString .= "</w:t> <w:cr/><w:t>Date : " . $listingdate . " This petition was circulated today.</w:t> <w:cr/><w:t></w:t> <w:cr/><w:t>";
                } else if ($caseDetails->casetype_id != 3 && $caseDetails->casetype_id != 4 && $caseDetails->casetype_id != 9 && $caseDetails->casetype_id != 10) {
                    if ($caseDetails->registration_number != null) {
                        $reg = explode("-", $caseDetails->registration_number);
                        if (count($reg) > 2) {
                            if ($reg[1] == $reg[2])
                                $docDataString .= "</w:t> <w:cr/><w:t>Date : " . $listingdate . " This petition was called on for hearing today.</w:t> <w:cr/><w:t></w:t> <w:cr/><w:t>";
                            else
                                $docDataString .= "</w:t> <w:cr/><w:t>Date : " . $listingdate . " These petitions were called on for hearing today.</w:t> <w:cr/><w:t></w:t> <w:cr/><w:t>";
                        } else if (count($reg) > 1) {
                            $docDataString .= "</w:t> <w:cr/><w:t>Date : " . $listingdate . " This petition was called on for hearing today.</w:t> <w:cr/><w:t></w:t> <w:cr/><w:t>";
                        } else {
                            $docDataString .= "</w:t> <w:cr/><w:t>Date : " . $listingdate . " This petition was called on for hearing today.</w:t> <w:cr/><w:t></w:t> <w:cr/><w:t>";
                        }
                    } else {
                        $docDataString .= "</w:t> <w:cr/><w:t>Date : " . $listingdate . " This petition was called on for hearing today.</w:t> <w:cr/><w:t></w:t> <w:cr/><w:t>";
                    }
                }

                //Printing Coram Data here
                if ($causelistType != 7) {
                    $docDataString .= "CORAM : ";

                    $judges = $this->model->getJudgeName($coram);
                    //var_dump($judges);
                    foreach ($judges as $judge) {
                        if ($judge['jname'] != null && $judge['jname'] != "") {
                            $docDataString .= "</w:t> <w:cr/><w:t>         " . strtoupper($judge['jname']);
                        }
                    }
                    if ($causelistType == 5)
                        $docDataString .= "</w:t> <w:cr/><w:t>                           [IN CHAMBER]</w:t> <w:cr/><w:t>";

                    $docDataString .= "</w:t> <w:cr/><w:t>";
                }
                //END of Coram

                //For Petitioner advocate
                if ($causelistType != 9) {
                    if ($caseDetails->casetype_id == 3 || $caseDetails->casetype_id == 4)
                         $docDataString .= "</w:t> <w:cr/><w:t>For Appellant(s) : ";
                    else
                         $docDataString .= "</w:t> <w:cr/><w:t></w:t> <w:cr/><w:t>For Petitioner(s) : ";

                    if (count($pAdvDetails) > 0) {
                        $pAdvFinal = $pAdv = "";
                        foreach ($pAdvDetails as $pAdvd) {
                            if ($pAdvd['advoate_code'] == 584) {
                                $pAdvFinal .= $pAdvd['title'] . ' ' . $this->convertToTitleCase($pAdvd['advocate_name']) . "</w:t> <w:cr/><w:t>";
                            } else {
                                $addedForAppearance = [];
                                //$addedForAppearance = $this->model->getAdvocateAppearanceDetails($connectedDiaries, $pAdvd['pet_res'], $causelistDate, $pAdvd['aor_code']);
                                if (count($addedForAppearance) > 0) {
                                    /*$pAdvFinal .= "</w:t> <w:cr/><w:t>                   ";
                                    $AORtoIncludeORexclude = $this->model->getAdvocateAppearanceAORIncludeORExclude($connectedDiaries, $pAdvd['pet_res'], $causelistDate, $pAdvd['aor_code']);
                                    if(count($AORtoIncludeORexclude)==0){
                                        $pAdvFinal .= $pAdvd['title'] . ' ' .$this->convertToTitleCase($pAdvd['advocate_name']) . ", AOR</w:t> <w:cr/><w:t>                   ";
                                    }
                                    foreach ($addedForAppearance as $addedForAppearance) {
                                        $pAdvFinal .= $addedForAppearance['advocate_title'] . ' ' .$this->convertToTitleCase($addedForAppearance['advocate_name']) . ", ".$addedForAppearance['advocate_type']."</w:t> <w:cr/><w:t>                   ";
                                    }
                                    $pAdvFinal .= "</w:t> <w:cr/><w:t>                   ";*/

                                    //sql getAdvocateAppearanceDetails inside this table appearing_in_diary is not exist & not used.
                                } else {
                                    $pAdvFinal .= $pAdvd['title'] . ' ' . $this->convertToTitleCase($pAdvd['advocate_name']) . ", AOR</w:t> <w:cr/><w:t>";
                                }
                            }
                        }
                        $docDataString .= $pAdvFinal;
                    }
                     $docDataString .= "</w:t> <w:cr/><w:t>For Respondent(s) : ";

                    if (count($rAdvDetails) > 0) {
                        $rAdvFinal = $rAdv = "";
                        foreach ($rAdvDetails as $rAdvd) {

                            if ($rAdvd['advoate_code'] == 585) {
                                $rAdvFinal .= $rAdvd['title'] . ' ' . $this->convertToTitleCase($rAdvd['advocate_name']) . "</w:t> <w:cr/><w:t>";
                            } else {
                                $addedForAppearance = [];
                                //$addedForAppearance = $this->model->getAdvocateAppearanceDetails($connectedDiaries, $rAdvd['pet_res'], $causelistDate, $rAdvd['aor_code']);

                                if (count($addedForAppearance) > 0) {

                                    /*$rAdvFinal .= "</w:t> <w:cr/><w:t>                   ";

                                    $AORtoIncludeORexclude = $this->model->getAdvocateAppearanceAORIncludeORExclude($connectedDiaries, $rAdvd['pet_res'], $causelistDate, $rAdvd['aor_code']);

                                    if(count($AORtoIncludeORexclude)==0){
                                        $rAdvFinal .= $rAdvd['title'] . ' ' .$this->model->convertToTitleCase($rAdvd['advocate_name']) . ", AOR</w:t> <w:cr/><w:t>                   ";
                                    }
                                    foreach ($addedForAppearance as $addedForAppearance) {
                                        $rAdvFinal .= $addedForAppearance['advocate_title'] . ' ' .$this->model->convertToTitleCase($addedForAppearance['advocate_name']) . ", ".$addedForAppearance['advocate_type']."</w:t> <w:cr/><w:t>                   ";
                                    }
                                    $rAdvFinal .= "</w:t> <w:cr/><w:t>                   ";*/

                                    //sql getAdvocateAppearanceDetails inside this table appearing_in_diary is not exist & not used.
                                } else {
                                    $rAdvFinal .= $rAdvd['title'] . ' ' . $this->convertToTitleCase($rAdvd['advocate_name']) . ", AOR</w:t> <w:cr/><w:t>";
                                }
                            }
                        }
                        $docDataString .= $rAdvFinal;
                    }
                    //END

                    if ($causelistType == 9) {
                        $docDataString .= "</w:t> <w:cr/><w:t></w:t> <w:cr/><w:t>                    By Circulation</w:t> <w:cr/><w:t></w:t> <w:cr/><w:t>" . "</w:t> <w:cr/><w:t></w:t> <w:cr/><w:t>          UPON perusing papers the Court made the following";
                    } else if ($causelistType == 7) {
                        $docDataString .= "</w:t> <w:cr/><w:t></w:t> <w:cr/><w:t>          UPON hearing the counsel the Court made the following";
                    } else {
                        $docDataString .= "</w:t> <w:cr/><w:t></w:t> <w:cr/><w:t>          UPON hearing the counsel the Court made the following";
                    }
                    $docDataString .= "</w:t> <w:cr/><w:t>                             O R D E R</w:t> <w:cr/><w:t></w:t> <w:cr/><w:t></w:t> <w:cr/><w:t>";

                    if ($causelistType != 7) {
                        $signatory1 = $this->model->getUserNameAndDesignation($usercode);
                        $signatory2 = $this->model->getUserNameAndDesignation($user2);
                        $linelength = strlen($signatory1->name);
                        $docDataString .= "(" . $signatory1->name . ")";
                        for ($l = 1; $l <= (46 - $linelength); $l++)
                            $docDataString .= " ";
                        $docDataString .= "(" . $signatory2->name . ")" . "</w:t> <w:cr/><w:t>";
                        $designation1 = $signatory1->type_name;
                        $linelength = strlen($designation1);
                        $docDataString .= $designation1;
                        for ($l = 1; $l <= (45 - $linelength); $l++)
                            $docDataString .= " ";
                        $docDataString .= " " . $signatory2->type_name . "</w:t> <w:cr/><w:t>";
                    } else if ($causelistType == 7) {
                        $judges = $this->model->getJudgeName($coram);
                        foreach ($judges as $j) {
                            $registrarName = str_replace('MR. ', '', $j['first_name']) . " " . $j['sur_name'];
                            $registrarName = str_replace('SH. ', '', $registrarName);
                            $registrarName = str_replace('MS. ', '', $registrarName);
                            $judgeslength = strlen($registrarName);
                            $judgeslinelength = (62 - $judgeslength);
                            $docDataString .= "</w:t> <w:cr/><w:t>";
                            for ($l = 1; $l <= $judgeslinelength; $l++)
                                $docDataString .= " ";
                            $docDataString .= $registrarName . "</w:t> <w:cr/><w:t>                                                     Registrar";
                        }
                    }

                    $filename = "";
                    $fileNameDetail = $this->model->getFileName($diary_no, date("Y-m-d", strtotime($listingdate)), $roster_id, $item_number);

                    if ($fileNameDetail->file_name != null && $fileNameDetail->file_name != "") {
                        $filename = $fileNameDetail->file_name;
                    } else {
                        if ($fileNameDetail->registration_number != null && $fileNameDetail->registration_number != "" && explode('-', $caseDetails->registration_number)[0] != 31) {
                            $regNo = explode("-", $fileNameDetail->registration_number);
                            if (count($regNo) > 2) {
                                if ($regNo[1] == $regNo[2]) {
                                    $filename = $fileNameDetail->casetype_desc . (int)$regNo[1] . $fileNameDetail->registration_year;
                                } else {
                                    $filename = $fileNameDetail->casetype_desc . (int)$regNo[1] . '-' . (int)$regNo[2] . $fileNameDetail->registration_year;
                                }
                            } else if (count($reg) > 1) {
                                $filename = $fileNameDetail->casetype_desc . (int)$regNo[1] . $fileNameDetail->registration_year;
                            } else {
                                $filename = 'DI' . $fileNameDetail->diary_no;
                            }
                        } else {
                            $filename = 'DI' . $fileNameDetail->diary_no;
                        }
                        $filename .= '#' . $roster_id . '#' . $court_no . '#' . $item_number;
                    }

                    $docDataString = str_replace('&amp;', '&', $docDataString);
                    $docDataString = str_replace('&AMP;', '&', $docDataString);
                    $docDataString = str_replace('&', '&amp;', $docDataString);
                    $dir = "rop_" . $listingdate . "_" . $usercode;
                    //$this->create_docx($dir, $filename, $docDataString);
                    $dataForUpdate = array('order_date' => date("Y-m-d", strtotime($listingdate)), 'court_number' => $court_no, 'item_number' => $item_number, 'diary_no' => $diary_no, 'order_details' => $docDataString, 'generated_by' => $usercode, 'file_name' => $filename, 'order_type' => 'O', 'is_oral_mentioning' => 0, 'roster_id' => $roster_id, 'is_reportable' => $reportable);
                    $this->model->updateProceedingsDetail($dataForUpdate);
                }
            }
        }

        $zip_file = $dir . '.zip';
        $path = "uploaded_documents/assets/courtMaster";
        $rootPath = $path . '/' . $dir;
        if (!file_exists($rootPath)) {
            mkdir($rootPath, 0755, true);
        }
        $zip = new ZipArchive();
        $zip->open($rootPath . '/' . $zip_file, ZipArchive::CREATE | ZipArchive::OVERWRITE);
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($rootPath, FilesystemIterator::SKIP_DOTS),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $name => $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                // $relativePath = substr($filePath, $rootPath);
                $zip->addFile($filePath, $rootPath);
            }
        }
        $zip->close();
        ob_clean();

        $data = file_get_contents($rootPath . '/' . $zip_file);
        $name = $zip_file;
        force_download($name, $data);
        if (file_exists($rootPath . '/' . $zip_file)) {
            //var_dump($path . '/' . $zip_file);
            try {
                array_map('unlink', glob($path . '/' . "$dir/*.*"));
                rmdir($path . '/' . $dir);
                unlink($rootPath . '/' . $zip_file);
            } catch (Exception $e) {
                echo $e . message();
            }
        }
    }

    private function str_replace_once($str_pattern, $str_replacement, $string)
    {

        if (strpos($string, $str_pattern) !== false) {
            $occurrence = strpos($string, $str_pattern);
            return substr_replace($string, $str_replacement, strpos($string, $str_pattern), strlen($str_pattern));
        }

        return $string;
    }

    public function convertToTitleCase($str)
    {
        return str_replace('\' ', '\'', ucwords(str_replace('\'', '\' ', strtolower($str))));
    }

    private function create_docx($folder, $fileName, $text)
    {
        $path = $_SERVER['DOCUMENT_ROOT'] . '/supreme_court/Copying/assets/courtMaster';
        $template_file = $path . '/courtmasterTemplate.docx';
        $fileName = $fileName . ".docx";
        $fullpath = $path . '/' . $folder . '/' . $fileName;
        if (!file_exists($path . '/courtmasterTemplate.docx')) {
            copy($path . '/template/courtmasterTemplate.docx', $path . '/courtmasterTemplate.docx');
        }
        try {
            //Create the Result Directory if Directory is not created already
            if (!file_exists($path . '/' . $folder)) {
                mkdir($path . '/' . $folder);
            }
            //Copy the Template file to the Result Directory
            copy($template_file, $fullpath);
            $zip = new ZipArchive;
            //Docx file is nothing but a zip file. Open this Zip File
            if ($zip->open($fullpath) == true) {
                //In the Open XML Wordprocessing format content is stored
                //in the document.xml file located in the word directory.

                $key_file_name = 'word/document.xml';
                $message = $zip->getFromName($key_file_name);

                //Replace the Placeholders with actual values
                $message = str_replace("myText", $text, $message);


                //Replace the content with the new content created above.
                $zip->addFromString($key_file_name, $message);
                $zip->close();
            }
        } catch (Exception $e) {
            $error_message = "Error creating the Word Document";
            //TODO : Handle the error message
        }
    }

    public function generate()
    {
        $diary_no = $this->diary_no;
        $number = uniqid();
        extract($this->request->getPost());
        if (isset($causelistDate) && empty($causelistDate)) {
            return $this->index();
        }

        if (!empty($this->request->getFiles('fileROPList'))) {

            $fileROPList = $this->request->getFiles('fileROPList');

            $desired_dir = "uploaded_documents/qr_assets/" . $number;

            // $desired_dir = "/home/reports/supremecourt/qr_assets/" . uniqid();

            foreach ($fileROPList['fileROPList'] as $key => $fileROPListVal) {

                $file_name = $fileROPListVal->getName();
                $file_tmp = $fileROPListVal->getTempName();
                $fileNameWithoutExtension = pathinfo($file_name, PATHINFO_FILENAME);
                $fileNameWithoutExtensionList = explode('_', $fileNameWithoutExtension);
                $fileNameWithoutExtension = $fileNameWithoutExtensionList[0];
                $fileExtension = pathinfo($file_name, PATHINFO_EXTENSION);

                $causelistDate = date('Y-m-d', strtotime($causelistDate));

                $res = $this->model->getFileProceedingDetail($fileNameWithoutExtension, $causelistDate);

                if ($fileExtension == "pdf") {
                    if ($res) {
                        $fileProceedingDetail = $res[0];
                        if ($fileProceedingDetail != "" && $fileProceedingDetail != null) {
                            $diarynumber = $fileProceedingDetail->diary_no;
                            $diary_number_only = substr($diarynumber, 0, -4);
                            $diary_year = substr($diarynumber, -4);


                            $orderDateFile = date_create($fileProceedingDetail->order_date);
                            $orderDateFile = date_format($orderDateFile, 'd-M-Y');

                            $desired_dir_in_db = "supremecourt/" . $diary_year . "/" . $diary_number_only;

                            $uploadedFileName = $diary_number_only . "_" . $diary_year . "_" . $fileProceedingDetail->court_number . "_" . $fileProceedingDetail->item_number . "_" . $fileProceedingDetail->roster_id . "_Order_" . $orderDateFile . "." . $fileExtension;


                            $file_url_on_web = env('LIVE_URL') . $desired_dir_in_db . "/" . $uploadedFileName;


                            if (is_dir($desired_dir) == false) {
                                mkdir($desired_dir, 0755, true); // Create directory if it does not exist
                            }
                            if (is_dir("$desired_dir/" . $file_name) == false) {


                                $fileROPListVal->move("$desired_dir/after_qr_embed/", $file_name);

                                $this->generateQR($file_url_on_web, $fileNameWithoutExtension, $desired_dir);
                            }
                        }
                    }
                }
            }


            ////////Code for making zip file and force download
            if (!is_dir($desired_dir)) {
                if (!mkdir($desired_dir, 0755, true)) {
                    die('Failed to create directories...');
                }
            }
            if (is_dir($desired_dir)) {
                // pr($desired_dir."/" . $number . ".pdf");
                $moveFile = move_uploaded_file($file_tmp, $desired_dir . "/" . $number . ".pdf");
                if (is_dir($desired_dir . "/after_qr_embed") == false) {
                    mkdir($desired_dir . "/after_qr_embed", 0755, true);
                    // pr($desired_dir."/" ."after_qr_embed/" . $number . ".pdf");
                    move_uploaded_file($file_tmp, $desired_dir . "/" . "after_qr_embed/" . $number . ".pdf");
                }

                if (!$moveFile) {
                    die('Failed to move uploaded file...');
                }
                $file_url_on_web = $desired_dir . "/" . $number . ".pdf";
                $this->generateQR($file_url_on_web, $number, $desired_dir);
            } else {
                die('Failed to create directory...');
            }

            $data = file_get_contents($desired_dir . '/' . 'after_qr_embed/' . $number . '.pdf');
            $name = $file_name;
            // $this->delete_directory($desired_dir);
            force_download($name, $data);


            // $rootPath = $desired_dir . "/after_qr_embed/";
            // pr($rootPath);
            // $zip = new ZipArchive();
            // $zip->open($desired_dir . '/after_qr_embed.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE);
            // if (!is_dir($rootPath)) {
            //     echo "Directory does not exist.";
            // }
            // $files = new RecursiveIteratorIterator(
            //     new RecursiveDirectoryIterator($rootPath),
            //     RecursiveIteratorIterator::LEAVES_ONLY
            // );

            // foreach ($files as $name => $file) {
            //     if (!$file->isDir()) {
            //         $filePath = $file->getRealPath();
            //         $relativePath = substr($filePath, strlen($rootPath));
            //         $zip->addFile($filePath, $relativePath);
            //     }
            // }
            // $zip->close();
            // ob_clean();
            // $data = file_get_contents($desired_dir . '/after_qr_embed.zip'); //assuming my file is on localhost
            // $name = $causelistDate . "_withqr.zip";
            // // $this->delete_directory($desired_dir);
            // force_download($name, $data);

            //after that code level is not cleared
        }
    }

    private function delete_directory($folderName)
    {
        // Load the file helper
        helper('file');

        // Check if the directory exists
        if (is_dir($folderName)) {
            // Delete all files in the directory
            delete_files($folderName, true);

            // Delete the directory itself
            rmdir($folderName);

            return true;
        }
        return false;
    }
    private function delete_directory2($folderName)
    {
        helper('file');
        if (is_dir($folderName)) {
            delete_files($folderName, true); // Delete files into the folder
            rmdir($folderName); // Delete the folder
            return true;
        }
        return false;
    }

    private function generateQR($file_url_on_web, $file_name, $desired_dir)
    {
        if (is_dir($desired_dir) == false) {
            mkdir($desired_dir, 0755, true);  // Create directory if it does not exist

        }
        if (is_dir($desired_dir . "/after_qr_embed") == false) {
            mkdir($desired_dir . "/after_qr_embed", 0777, true);
        }
        ini_set('display_errors', 1);
        $finalfile = $desired_dir . "/after_qr_embed/" . $file_name . ".pdf";
        $file = $desired_dir . "/" . $file_name . "_qr.png";
        $qr_file_url = $desired_dir . "/" . $file_name . "_qr.pdf";
        $pdf_file = $desired_dir . "/" . $file_name . ".pdf";
        $ecc = 'L';
        $pixel_Size = 10;
        $frame_Size = 10;
        $this->qrlib->QRcodePng($file_url_on_web, $file, $ecc, $pixel_Size, $frame_Size);
        $this->Fpdf->AddPage();
        $this->Fpdf->Image($file, $this->Fpdf->Getx() + 140, $this->Fpdf->GetY() - 5, 25.00); //For TOP right
        $this->Fpdf->Output($qr_file_url, 'F');

        $nm_s =  shell_exec('pdftk ' . $pdf_file . ' dump_data | grep NumberOfPages');
        $NumberOfPages = !empty($nm_s) ? str_replace('NumberOfPages: ', '', $nm_s) : 0;
        if ($NumberOfPages == 1) {
            shell_exec("pdftk " . $pdf_file . " background " . $qr_file_url . " output " . $finalfile . " ");
        } else {
            $page_one_file_name = $desired_dir . "/after_qr_embed/page_1_" . $file_name . ".pdf";
            $page_one_file_name_with_qr = $desired_dir . "/after_qr_embed/page_1_with_qr_" . $file_name . ".pdf";
            shell_exec("pdftk " . $pdf_file . " cat 1 output " . $page_one_file_name . " ");
            shell_exec("pdftk " . $page_one_file_name . " background " . $qr_file_url . " output " . $page_one_file_name_with_qr . " ");
            shell_exec("pdftk A=" . $page_one_file_name_with_qr . " B=" . $pdf_file . " cat A1 B2-end output " . $finalfile . " ");

            if (is_dir($page_one_file_name != false)) {
                unlink($page_one_file_name);
            }

            if (is_dir($page_one_file_name != false)) {
                unlink($page_one_file_name_with_qr);
            }
        }
        move_uploaded_file($pdf_file, $desired_dir . "/" . "after_qr_embed/" . $file_name . ".pdf");
    }


    public function rePrint()
    {
        $diary_no = $this->diary_no;
        return view('Court/CourtMaster/rePrint');
    }

    public function getReprintJO()
    {

        $diary_no = $this->diary_no;

        $txt_o_frmdt = date('Y-m-d', strtotime($this->request->getGet('txt_o_frmdt')));
        $txt_o_todt = date('Y-m-d', strtotime($this->request->getGet('txt_o_todt')));

        if ($this->request->getGet('order_upload') == 'O') {
            $order_upload = 'orderdate';
        } elseif ($this->request->getGet('order_upload') == 'U') {
            $order_upload = 'date(ent_dt)';
        }

        $data['getOrdernet'] = $this->model->getOrdernet($order_upload, $txt_o_frmdt, $txt_o_todt);
        $data['txt_o_frmdt'] = $txt_o_frmdt;
        $data['txt_o_todt'] = $txt_o_todt;
        $data['order_upload'] = $order_upload;
        $data['getRosterJudge'] =  new CourtMasterModel();
        return view('Court/CourtMaster/getReprintJOData', $data);
    }

    public function getRosterJudge($roster_id)
    {

        $getRosterJudge = $this->model->getRosterJudge($roster_id);

        $jud_name = '';
        foreach ($getRosterJudge as $getRosterJudgeVal):
            if ($jud_name == '') {
                $jud_name = $getRosterJudgeVal['jname'];
            } else {
                $jud_name = $jud_name . ',' . $getRosterJudgeVal['jname'];
            }
        endforeach;

        return $jud_name;
    }

    public function getPdfName()
    {

        $docid = $this->request->getGet('docid');

        $getPdfname = $this->model->getPdfname($docid);

        if (sizeof($getPdfname) > 0) {
            $path = $getPdfname[0]['pdfname'];
            echo '../jud_ord_html_pdf/' . $path;
        } else {
            echo '0';
        }
    }

    public function UploadOneByOne()
    {
        $diary_no = $this->diary_no;
        $getCaseDetailsForReplace = $this->model->getCaseDetailsForReplace($diary_no);
        $data['caseDetails'] = $getCaseDetailsForReplace;
        $usercode = session()->get('login')['usercode'];
        $data['usercode'] = $usercode;
        $getCaseType = $this->model->getCaseType();
        $data['caseTypes'] = $getCaseType;

        return view('Court/CourtMaster/replaceProceedings', $data);
    }

    public function getListedDetails()
    {

        extract($this->request->getPost());
        $usercode = session()->get('login')['usercode'];
        $listedInfo = explode('#', $id);
        $diary_no = $listedInfo[0];
        $listing_date = $listedInfo[1];
        $roster_id = $listedInfo[2];
        $court_number = $listedInfo[3];
        $item_number = $listedInfo[4];

        $data['languages'] = $this->model->getLanguage($usercode);
        $data['judge'] = $this->model->getAllJudge($usercode);
        $courtJudges = $this->model->getCoramInCourt($roster_id);
        $judges = explode(',', $courtJudges[0]['coram']);
        $coram = $this->model->getJudge($judges);

        $judgesInCoram = "";
        foreach ($coram as $index => $j) {
            if ($index == 0) {
                $firstJudge = $j['jcode'];
                $judgesInCoram = $j['jname'];
            } else {
                $judgesInCoram = $judgesInCoram . ',' . $j['jname'];
            }
        }

        $data['firstJudge'] = $firstJudge;
        $data['judgesInCoram'] = $judgesInCoram;

        return view('Court/CourtMaster/showReplaceProceedings', $data);
    }


    public function embedQRCaseWise()
    {
        // pr($_FILES);
        // var_dump($this->request->getPost());
        // var_dump($_FILES['fileROPList']);

        extract($this->request->getPost());
        $data = explode('#', $listingDates);
        if (!isset($is_reportable)) {
            $is_reportable = 0;
        }
        $diary_number = $data[0];
        $order_date = $data[1]; // This is a string
        $roster_id = $data[2];
        $court_number = $data[3];
        $item_number = $data[4];
        $number = uniqid();
        $desired_dir = "uploaded_documents/qr_assets/" . $number;
        if (isset($_FILES['fileROPList'])) {
            $file_name = $_FILES['fileROPList']['name'];
            $file_size = $_FILES['fileROPList']['size'];
            $file_tmp = $_FILES['fileROPList']['tmp_name'];
            $file_type = $_FILES['fileROPList']['type'];
            $fileNameWithoutExtension = pathinfo($file_name, PATHINFO_FILENAME);
            $fileNameWithoutExtensionList = explode('_', $fileNameWithoutExtension);
            $fileNameWithoutExtension = $fileNameWithoutExtensionList[0];
            $fileExtension = pathinfo($file_name, PATHINFO_EXTENSION);
            $diary_number_only = substr($diary_number, 0, -4);
            $diary_year = substr($diary_number, -4);
            $orderDateFile = date_create($order_date);
            $orderDateFile = date_format($orderDateFile, 'd-M-Y');
            //$desired_dir = "/home/reports/supremecourt/qr_assets/".uniqid();
            $desired_dir_in_db = "uploaded_documents/" . $diary_year . "/" . $diary_number_only;
            $uploadedFileName = $diary_number_only . "_" . $diary_year . "_" . $court_number . "_" . $item_number . "_" . $roster_id . "_" . $orderType . "_" . $orderDateFile . "." . $fileExtension;
            // $file_url_on_web = LIVE_URL . $desired_dir_in_db . "/" . $uploadedFileName;
            if (!is_dir($desired_dir)) {
                if (!mkdir($desired_dir, 0755, true)) {
                    die('Failed to create directories...');
                }
            }
            if (is_dir($desired_dir)) {
                // pr($desired_dir."/" . $number . ".pdf");
                $moveFile = move_uploaded_file($file_tmp, $desired_dir . "/" . $number . ".pdf");
                if (is_dir($desired_dir . "/after_qr_embed") == false) {
                    mkdir($desired_dir . "/after_qr_embed", 0755, true);
                    // pr($desired_dir."/" ."after_qr_embed/" . $number . ".pdf");
                    move_uploaded_file($file_tmp, $desired_dir . "/" . "after_qr_embed/" . $number . ".pdf");
                }

                if (!$moveFile) {
                    die('Failed to move uploaded file...');
                }
                $file_url_on_web = $desired_dir . "/" . $number . ".pdf";
                $this->generateQR($file_url_on_web, $number, $desired_dir);
            } else {
                die('Failed to create directory...');
            }
        }
        $data = file_get_contents($desired_dir . '/' . 'after_qr_embed/' . $number . '.pdf');
        $name = $file_name;
        // $this->delete_directory($desired_dir);
        force_download($name, $data);
    }

    public function replaceROP()
    {

        extract($this->request->getPost());

        $data = explode('#', $listingDates);
        if (!isset($is_reportable)) {
            $is_reportable = 0;
        }
        $diary_number = $data[0];
        $order_date = $data[1];
        $roster_id = $data[2];
        $court_number = $data[3];
        $item_number = $data[4];
        $orderTextData = "";
        $fileListStatus = array();

        $usercode = session()->get('login')['usercode'];

        if (!empty($this->request->getFiles('fileROPList'))) {

            $fileROPList = $this->request->getFiles('fileROPList');

            $file_name = $fileROPList['fileROPList']->getName();
            $file_size = $fileROPList['fileROPList']->getSize();
            $file_tmp = $fileROPList['fileROPList']->getTempName();
            $file_type = $fileROPList['fileROPList']->getMimeType();

            $fileListStatus += array($file_name => "");
            $fileProceedingDetail = "";
            $fileNameWithoutExtension = pathinfo($file_name, PATHINFO_FILENAME);
            $fileNameWithoutExtensionList = explode('_', $fileNameWithoutExtension);
            $fileNameWithoutExtension = $fileNameWithoutExtensionList[0];
            $fileExtension = pathinfo($file_name, PATHINFO_EXTENSION);

            $orderTypeShort = 'O';
            if ($orderType == 'Judgement') {
                $orderTypeShort = 'J';
            } else if ($orderType == 'FinalOrder') {
                $orderTypeShort = 'FO';
            }

            $c_type = null;

            $languages = explode('#', $language);
            $languageId = $languages[0];
            $languageName = $languages[1];
            $nc_status = 1;      // added on 8.6.23

            $res = $this->model->getDiaryProceedingDetail($diary_number, $order_date, $court_number, $item_number, $roster_id, $orderType);

            if ($orderType == 'Judgement') { //added on 8.6.23

                $nc_res = $this->model->getNCDetails($diary_number, $order_date);
                if (!$nc_res) {
                    $nc_status = 0;
                }
            }
            //added on 8.6.23 ends

            $resVernacular = $this->model->getVernacularJudgmentDetail($diary_number, $order_date, $orderTypeShort, $languageId);

            if ($fileExtension == "pdf") {
                if ($res) {
                    $fileProceedingDetail = $res[0];
                    if ($fileProceedingDetail != "" && $fileProceedingDetail != null) {
                        $orderTextData = "";
                        if ($language == 1)
                            $orderTextData = $this->getTextFromPDF($file_tmp);

                        $diarynumber = $fileProceedingDetail->diary_no;
                        $diary_number_only = substr($diarynumber, 0, -4);
                        $diary_year = substr($diarynumber, -4);
                        $desired_dir = "/home/reports/supremecourt/" . $diary_year . "/" . $diary_number_only;
                        $desired_dir_in_db = "supremecourt/" . $diary_year . "/" . $diary_number_only;
                        $orderDateFile = date_create($fileProceedingDetail->order_date);
                        $orderDateFile = date_format($orderDateFile, 'd-M-Y');
                        $uploadedFileName = $diary_number_only . "_" . $diary_year . "_" . $fileProceedingDetail->court_number . "_" . $fileProceedingDetail->item_number . "_" . $fileProceedingDetail->roster_id . "_" . $orderType . "_" . $orderDateFile . "." . $fileExtension;

                        if (($fileProceedingDetail->pdfname == '' || $fileProceedingDetail->pdfname == null) && $languageId == 1) {
                            if ($fileProceedingDetail->upload_flag == 0 || $fileProceedingDetail->upload_flag == null || $fileProceedingDetail->type == null) {
                                if (is_dir($desired_dir) == false) {
                                    mkdir("$desired_dir", 0755, true); // Create directory if it does not exist
                                }
                                if (is_dir("$desired_dir/" . $uploadedFileName) == false) {
                                    $fileROPList['fileROPList']->move("$desired_dir/", $uploadedFileName);
                                }
                                $c_type = null;
                                if ($fileProceedingDetail->registration_number != null && $fileProceedingDetail->registration_number != "") {
                                    $c_type = substr($fileProceedingDetail->registration_number, 0, 2);
                                }
                                if ($orderType == 'Judgement') {
                                    $dataForUpdate = array('diary_no' => $diarynumber, 'order_date' => date_format(date_create($fileProceedingDetail->order_date), 'Y-m-d'), 'pdf_name' => $desired_dir_in_db . '/' . $uploadedFileName, 'usercode' => $usercode, 'type' => 'J', 'roster_id' => $fileProceedingDetail->roster_id, 'case_type' => (int)$c_type, 'registration_number' => $fileProceedingDetail->registration_number, 'registration_year' => $fileProceedingDetail->registration_year, 'filename' => $fileNameWithoutExtension, 'orderTextData' => $orderTextData, 'is_reportable' => $is_reportable, 'presiding_judge' => $presiding_judge);
                                } elseif ($orderType == 'FinalOrder') {
                                    $dataForUpdate = array('diary_no' => $diarynumber, 'order_date' => date_format(date_create($fileProceedingDetail->order_date), 'Y-m-d'), 'pdf_name' => $desired_dir_in_db . '/' . $uploadedFileName, 'usercode' => $usercode, 'type' => 'FO', 'roster_id' => $fileProceedingDetail->roster_id, 'case_type' => (int)$c_type, 'registration_number' => $fileProceedingDetail->registration_number, 'registration_year' => $fileProceedingDetail->registration_year, 'filename' => $fileNameWithoutExtension, 'orderTextData' => $orderTextData, 'is_reportable' => $is_reportable, 'presiding_judge' => $presiding_judge);
                                } else {
                                    $dataForUpdate = array('diary_no' => $diarynumber, 'order_date' => date_format(date_create($fileProceedingDetail->order_date), 'Y-m-d'), 'pdf_name' => $desired_dir_in_db . '/' . $uploadedFileName, 'usercode' => $usercode, 'type' => 'O', 'roster_id' => $fileProceedingDetail->roster_id, 'case_type' => (int)$c_type, 'registration_number' => $fileProceedingDetail->registration_number, 'registration_year' => $fileProceedingDetail->registration_year, 'filename' => $fileNameWithoutExtension, 'orderTextData' => $orderTextData, 'is_reportable' => $fileProceedingDetail->is_reportable, 'presiding_judge' => $presiding_judge);
                                }

                                if ($nc_status == 1) {
                                    $result = $this->model->insertProceedingsInOrderNet($dataForUpdate);

                                    if ($result == 1) {
                                        $fileListStatus[$file_name] = "Uploaded Successfully.";
                                    } else {
                                        $fileListStatus[$file_name] = "File is already uploaded using one-by-one option.";
                                    }
                                } else {
                                    $fileListStatus[$file_name] = "Please first generate Neutral Citation number and QR Code for the case.";
                                }
                            } else {
                                $fileListStatus[$file_name] = "ROP for this case was already uploaded.";
                            }
                        } elseif (($fileProceedingDetail->pdfname == '' || $fileProceedingDetail->pdfname == null) && $languageId != 1) {
                            $fileListStatus[$file_name] = "English Version Judgment is not uploaded. Please contact concerned Court Master.";
                        } else {

                            if ($languageId == 1) {
                                $new_replaced_name = explode('.', $fileProceedingDetail->pdfname);
                                $new_replaced_name = $new_replaced_name[0] . '_' . date('d-M-Y_h_i_s_A', strtotime($fileProceedingDetail->ent_dt)) . '_' . $fileProceedingDetail->usercode . '.pdf';

                                $success = rename("/home/reports/" . $fileProceedingDetail->pdfname, "/home/reports/" . $new_replaced_name);
                                if ($success) {
                                    $fileROPList['fileROPList']->move("$desired_dir/", $uploadedFileName);

                                    if ($orderType == 'Judgement') {
                                        $dataForUpdate = array('diary_no' => $diarynumber, 'order_date' => date_format(date_create($fileProceedingDetail->order_date), 'Y-m-d'), 'pdf_name' => $desired_dir_in_db . '/' . $uploadedFileName, 'usercode' => $usercode, 'type' => 'J', 'roster_id' => $fileProceedingDetail->roster_id, 'case_type' => (int)$c_type, 'registration_number' => $fileProceedingDetail->registration_number, 'registration_year' => $fileProceedingDetail->registration_year, 'filename' => $fileNameWithoutExtension, 'orderTextData' => $orderTextData, 'is_reportable' => $is_reportable, 'presiding_judge' => $presiding_judge);
                                    } elseif ($orderType == 'FinalOrder') {
                                        $dataForUpdate = array('diary_no' => $diarynumber, 'order_date' => date_format(date_create($fileProceedingDetail->order_date), 'Y-m-d'), 'pdf_name' => $desired_dir_in_db . '/' . $uploadedFileName, 'usercode' => $usercode, 'type' => 'FO', 'roster_id' => $fileProceedingDetail->roster_id, 'case_type' => (int)$c_type, 'registration_number' => $fileProceedingDetail->registration_number, 'registration_year' => $fileProceedingDetail->registration_year, 'filename' => $fileNameWithoutExtension, 'orderTextData' => $orderTextData, 'is_reportable' => $is_reportable, 'presiding_judge' => $presiding_judge);
                                    } else {
                                        $dataForUpdate = array('diary_no' => $diarynumber, 'order_date' => date_format(date_create($fileProceedingDetail->order_date), 'Y-m-d'), 'pdf_name' => $desired_dir_in_db . '/' . $uploadedFileName, 'usercode' => $usercode, 'type' => 'O', 'roster_id' => $fileProceedingDetail->roster_id, 'case_type' => (int)$c_type, 'registration_number' => $fileProceedingDetail->registration_number, 'registration_year' => $fileProceedingDetail->registration_year, 'filename' => $fileNameWithoutExtension, 'orderTextData' => $orderTextData, 'is_reportable' => $fileProceedingDetail->is_reportable, 'presiding_judge' => $presiding_judge);
                                    }

                                    if ($nc_status == 1) {
                                        $result = $this->model->insertProceedingsInOrderNet($dataForUpdate);

                                        if ($result == 1) {
                                            $fileListStatus[$file_name] = "Uploaded and replaced successfully.";
                                        }
                                    } else {
                                        $fileListStatus[$file_name] = "Please first generate Neutral Citation number and QR Code for the case.";
                                    }
                                } else {
                                    $fileListStatus[$file_name] = "Error while replacing uploaded file.";
                                }
                            } elseif ($language != 1) {

                                $desired_dir_vernacular = "/home/reports/supremecourt_vernacular/" . $diary_year . "/" . $diary_number_only;
                                $desired_dir_in_db_vernacular = "supremecourt_vernacular/" . $diary_year . "/" . $diary_number_only;
                                $ifExist = 0;
                                if ($resVernacular) {
                                    $resVernacularDetail = $resVernacular[0];
                                    if ($resVernacularDetail->pdf_name != '' && $resVernacularDetail->pdf_name != null) {
                                        $ifExist = 1;
                                        $new_replaced_vernacular_name = explode('.', $resVernacularDetail->pdf_name);
                                        $new_replaced_vernacular_name = $new_replaced_vernacular_name[0] . '_' . date('d-M-Y_h_s_i_A', strtotime($resVernacularDetail->entry_date)) . '_' . $resVernacularDetail->user_code . '.pdf';

                                        $success = rename("/home/reports/" . $resVernacularDetail->pdf_name, "/home/reports/" . $new_replaced_vernacular_name);
                                        if ($success) {
                                            $fileROPList['fileROPList']->move("/home/reports/", $resVernacularDetail->pdf_name);
                                            $dataForUpdate = array('diary_no' => $diarynumber, 'order_date' => date_format(date_create($fileProceedingDetail->order_date), 'Y-m-d'), 'ref_vernacular_languages_id' => $languageId, 'pdf_name' => $resVernacularDetail->pdf_name, 'user_code' => $usercode, 'entry_date' => date('Y-m-d h:i:s'), 'order_type' => $orderTypeShort, 'display' => 'Y', 'web_status' => '0');

                                            $result = $this->model->insertVernacularOrdersJudgments($dataForUpdate);
                                            if ($result == 1) {
                                                $fileListStatus[$file_name] = "Vernacular File Uploaded and replaced successfully.";
                                            }
                                        } else {
                                            $fileListStatus[$file_name] = "Error while replacing uploaded Vernacular file.";
                                        }
                                    }
                                }
                                if ($ifExist == 0) {
                                    if (is_dir($desired_dir_vernacular) == false) {
                                        mkdir($desired_dir_vernacular, 0755, true);
                                    }
                                    $uploadedFileName = $diary_number_only . "_" . $diary_year . "_" . $fileProceedingDetail->court_number . "_" . $fileProceedingDetail->item_number . "_" . $fileProceedingDetail->roster_id . "_" . $orderType . "_" . $orderDateFile . "_" . $languageName . "." . $fileExtension;

                                    $fileROPList['fileROPList']->move("$desired_dir_vernacular/", $uploadedFileName);
                                    $dataForUpdate = array('diary_no' => $diarynumber, 'order_date' => date_format(date_create($fileProceedingDetail->order_date), 'Y-m-d'), 'ref_vernacular_languages_id' => $languageId, 'pdf_name' => $desired_dir_in_db_vernacular . '/' . $uploadedFileName, 'user_code' => $usercode, 'entry_date' => date('Y-m-d h:i:s'), 'order_type' => $orderTypeShort, 'display' => 'Y', 'web_status' => '0');

                                    $result = $this->model->insertVernacularOrdersJudgments($dataForUpdate);
                                    if ($result == 1) {
                                        $fileListStatus[$file_name] = "Uploaded successfully.";
                                    }
                                }
                            }
                        }
                    } else {
                        $fileListStatus[$file_name] = "ROP is not generated for this case yet.";
                    }
                } else {
                    $fileListStatus[$file_name] = "ROP is not generated for this case yet.";
                }
            } else {
                $fileListStatus[$file_name] = "Only PDF is permitted";
            }

            echo '<script>alert("' . $fileListStatus[$file_name] . '")</script>';
            echo '<script>window.location.href="' . base_url('Court/CourtMasterController/UploadOneByOne') . '"</script>';
        }
    }


    function getTextFromPDF($file)
    {
        $parser = new \Smalot\PdfParser\Parser();
        $pdf = $parser->parseFile($file);
        $text = $pdf->getText();
        return $text;
    }

    //........New added 29-08-2024.....//
    public function get_session_upload()
    {
        // $this->session->set_userdata('dcmis_user_idd', $session);

        return view('Court/CourtMaster/UploadProceedings/upload');
    }

    // public function getUploadPage()
    // {
    //     //$this->session->set_userdata('dcmis_user_idd', $session);
    //     $this->load->view('CourtMaster/uploadProceedings.php');
    // }

    public function getCasesForUploading()
    {
        extract($_GET);
        $usercode = session()->get('login')['usercode'];
        $cause_list_date = $causelistDate;
        $data['cmaDol'] = $cause_list_date;
        $data['cmaU'] = $usercode;
        $data['cmaUn'] = $this->model->getUserNameAndDesignation($usercode);
        $data['caseList'] = $this->model->getCaseUploadingList($cause_list_date, $usercode);

        return view('Court/CourtMaster/UploadProceedings/casesForROPUploading', $data);
    }

    public function uploadROP()
    {
        extract($_POST);
        $usercode = session()->get('login')['usercode'];
        $orderTextData = "";
        $fileListStatus = array();
        if (isset($_FILES['fileROPList'])) {
            //var_dump($_FILES['fileROPList']);
            //$data += array($category => $question);
            foreach ($_FILES['fileROPList']['tmp_name'] as $key => $tmp_name) {
                $file_name = $_FILES['fileROPList']['name'][$key];
                $file_size = $_FILES['fileROPList']['size'][$key];
                $file_tmp = $_FILES['fileROPList']['tmp_name'][$key];
                $file_type = $_FILES['fileROPList']['type'][$key];

                $myhash = md5_file($file_tmp);

                $myhashWithDateTime = md5(file_get_contents($file_tmp) . date('Y-m-d h:i:s'));

                $fileListStatus += array($file_name => "");
                $fileProceedingDetail = "";
                $fileNameWithoutExtension = pathinfo($file_name, PATHINFO_FILENAME);
                $fileNameWithoutExtensionList = explode('_', $fileNameWithoutExtension);
                $fileNameWithoutExtension = $fileNameWithoutExtensionList[0];
                $fileExtension = pathinfo($file_name, PATHINFO_EXTENSION);
                //$res=$this->model->getFileProceedingDetail($fileNameWithoutExtension);
                $causelistDate = date('Y-m-d', strtotime($causelistDate));
                $res = $this->model->getFileProceedingDetail($fileNameWithoutExtension, $causelistDate);
                if ($fileExtension == "pdf") {
                    if ($res) {
                        $fileProceedingDetail = $res[0];
                        if ($fileProceedingDetail != "" && $fileProceedingDetail != null) {
                            $generatedByList = explode(',', $fileProceedingDetail->generated_by);
                            if (in_array($usercode, $generatedByList)) {
                                if ($fileProceedingDetail->upload_flag == 0 || $fileProceedingDetail->upload_flag == null) {
                                    $orderTextData = $this->getTextFromPDF($file_tmp);
                                    $diarynumber = $fileProceedingDetail->diary_no;
                                    $diary_number_only = substr($diarynumber, 0, -4);
                                    $diary_year = substr($diarynumber, -4);
                                    //$desired_dir="/home/ubuntu/reports/supremecourt/".$diary_year."/".$diary_number_only;
                                    $desired_dir = getBasePath() . "/reports/supremecourt/" . $diary_year . "/" . $diary_number_only;
                                    $desired_dir_in_db = "supremecourt/" . $diary_year . "/" . $diary_number_only;
                                    $orderDateFile = date_create($fileProceedingDetail->order_date);
                                    $orderDateFile = date_format($orderDateFile, 'd-M-Y');
                                    //$uploadedFileName = $diary_number_only . "_" . $diary_year . "_Order_" . $orderDateFile . "." . $fileExtension;
                                    $uploadedFileName = $diary_number_only . "_" . $diary_year . "_" . $fileProceedingDetail->court_number . "_" . $fileProceedingDetail->item_number . "_" . $fileProceedingDetail->roster_id . "_Order_" . $orderDateFile . "." . $fileExtension;
                                    //echo $uploadedFileName;
                                    /*if($this->isStringInFile($file_tmp,"adbe.pkcs7.sha1")){*/
                                    if (is_dir($desired_dir) == false) {
                                        //echo "Inside to create directory: ".$desired_dir;
                                        mkdir("$desired_dir", 0755, true);        // Create directory if it does not exist
                                    }
                                    if (is_dir("$desired_dir/" . $uploadedFileName) == false) {
                                        move_uploaded_file($file_tmp, "$desired_dir/" . $uploadedFileName);
                                    }
                                    //mysql_query($query);
                                    $c_type = null;
                                    if ($fileProceedingDetail->registration_number != null && $fileProceedingDetail->registration_number != "") {
                                        $c_type = substr($fileProceedingDetail->registration_number, 0, 2);
                                    }
                                    $dataForUpdate = array('diary_no' => $diarynumber, 'order_date' => date_format(date_create($fileProceedingDetail->order_date), 'Y-m-d'), 'pdf_name' => $desired_dir_in_db . '/' . $uploadedFileName, 'usercode' => $usercode, 'type' => 'O', 'roster_id' => $fileProceedingDetail->roster_id, 'case_type' => (int)$c_type, 'registration_number' => $fileProceedingDetail->registration_number, 'registration_year' => $fileProceedingDetail->registration_year, 'filename' => $fileNameWithoutExtension, 'orderTextData' => $orderTextData, 'is_reportable' => $fileProceedingDetail->is_reportable);
                                    $result = $this->model->insertProceedingsInOrderNet($dataForUpdate, $myhash, $myhashWithDateTime);
                                    if ($result == 1) {
                                        $fileListStatus[$file_name] = "Uploaded Successfully.";
                                    } else {
                                        $fileListStatus[$file_name] = "File is already uploaded using one-by-one option.";
                                    }
                                    /*}
                                    else
                                        $fileListStatus[$file_name]="PDF File is not digitally signed";*/
                                } else {
                                    $fileListStatus[$file_name] = "ROP for this case was already uploaded.";
                                }
                            } else {
                                $fileListStatus[$file_name] = "You are not authorise to upload this file.";
                            }
                        } else {
                            $fileListStatus[$file_name] = "ROP is not generated for this case yet.";
                        }
                    } else {
                        $fileListStatus[$file_name] = "ROP is not generated for this case yet.";
                    }
                } else {
                    $fileListStatus[$file_name] = "Only PDF is permitted";
                }
            }
        }
        $data['fileListStatus'] = $fileListStatus;
        return view('Court/CourtMaster/UploadProceedings/uploadProceedingsStatus', $data);
    }

   
    function vernacularJudgmentReport(){
        $data['app_name'] = 'vernacularJudgmentReport';
        $data['uploadedVernacularJudgmentsReport'] = '';
        $data['param'] = '';
        $data['msg'] = '';

        if ($_POST) {
            $fromDate = date('Y-m-d', strtotime($_POST['fromDate']));
            $toDate = date('Y-m-d', strtotime($_POST['toDate']));
            $data['uploadedVernacularJudgmentsReport'] = $this->model->getUploadedVernacularJudgmentsList($fromDate, $toDate);
            $data['param'] = array($fromDate, $toDate);
        }
		
        if (is_array($data['uploadedVernacularJudgmentsReport'])) {
            $data['msg'] = '';
        } else {
            $data['msg'] = 'Record Not Found';
        }
        // $this->load->view('Court/VernacularJudgments/uploadedVernacularJudgmentReport',$this->data);
        return view('Court/CourtMaster/VernacularJudgments/uploadedVernacularJudgmentReport', $data);
    }

    public function getCaseListingDetails()
    {
        // Use the request object to get POST data
        $request = $this->request;

        // Extract POST data
        $search_type = $request->getPost('optradio');
        $case_type = $request->getPost('caseType');
        $case_number = $request->getPost('caseNo');
        $case_year = $request->getPost('caseYear');
        $diary_number = $request->getPost('diaryNumber');
        $diary_year = $request->getPost('diaryYear');
        $msg = $request->getPost('msg');

        // Initialize the variable for diary number search
        $diaryNumberForSearch = null;

        if (!empty($search_type) && $search_type != null) {
            if ($search_type == 'D') {

                $data = [
                    'search_type' => $search_type,
                    'diary_number' => $diary_number,
                    'diary_year' => $diary_year,
                ];
            } else {

                $data = [
                    'search_type' => $search_type,
                    'case_type' => $case_type,
                    'case_number' => $case_number,
                    'case_year' => $case_year,
                ];
            }
        } else {
            $data = [
                'search_type' => $search_type
            ];
        }


        if ($search_type == 'D') {
            $diary_no = $diary_number . $diary_year;
            $get_main_table = $this->Dropdown_list_model->get_diary_details_by_diary_no($diary_no);
        } elseif ($search_type == 'C') {
            $diary_no = get_diary_case_type($case_type, $case_number, $case_year);
            if (!empty($diary_no)) {
                $get_main_table = $this->Dropdown_list_model->get_diary_details_by_diary_no($diary_no);
            } else {
                $get_main_table = array();
            }
        }

        if (!empty($get_main_table)) {
            $diaryNumberForSearch = $get_main_table['diary_no'];
        }
        $data = [];
        $data['usercode'] = session()->get('login')['usercode'];

        if ($diaryNumberForSearch !== null) {
            $data['caseDetails'] = $this->model->getCaseDetailsForReplace($diaryNumberForSearch);
        } elseif (empty($msg)) {
            $data['msg'] = "No record found!!";
        }

        // Return the view with the data
        return view('Court/CourtMaster/detailsforQRUpdate', $data);
    }
}
