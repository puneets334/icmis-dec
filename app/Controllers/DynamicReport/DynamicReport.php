<?php

namespace App\Controllers\DynamicReport;

use App\Controllers\BaseController;
use CodeIgniter\Controller;
use App\Models\DynamicReport\DynamicReportModel;

class DynamicReport extends BaseController
{
    public $model;
    public $diary_no;
    public $csrfTokenName;
    public $csrfHash;

    function __construct()
    {
        $this->model = new DynamicReportModel();
    }

    public function index()
    {
        $data['app_name'] = "Advance Query";
        $data['caseTypes'] = $this->model->getCaseTypeList();
        $data['Sections'] = $this->model->getSections();
        $data['MCategories'] = $this->model->getMainSubjectCategory();
        $data['states'] = $this->model->getState();
        $data['judges'] = $this->model->getJudges();
        $data['aors'] = $this->model->getAor();
        return view('DynamicReport/inputScreen', $data);
    }

    public function getSubSubjectCategory()
    {
        ob_clean();
        $request = service('request');    
        $csrfTokenName = csrf_token();
        $csrfHash = csrf_hash();
        $Mcat = $request->getVar('Mcat');
        $data_array = $this->model->getSubSubjectCategory($Mcat);
        ob_end_flush();
        return $this->response->setJSON([
            'data' => $data_array,
            $csrfTokenName => $csrfHash
        ]);
    }

    public function getDa()
    {
        ob_clean();
        $csrfTokenName = csrf_token();
        $csrfHash = csrf_hash();
        $data_array =  $this->model->getDa($_POST['section']);
        ob_end_flush();
        return $this->response->setJSON([
            'data' => $data_array,
            $csrfTokenName => $csrfHash
        ]);
    }

    public function get_agency()
    {
        ob_clean();
        $csrfTokenName = csrf_token();
        $csrfHash = csrf_hash();
        $data_array = $this->model->get_agency_code($_POST['state'], isset($_POST['agency']) ? $_POST['agency'] : NULL);
        ob_end_flush();
        return $this->response->setJSON([
            'data' => $data_array,
            $csrfTokenName => $csrfHash
        ]);
    }

    public function get_casetype()
    {
        ob_clean();
        header("Content-Type: application/json;charset=utf-8");
        $data_array = $this->model->get_casetype($_POST['type']);
        echo json_encode($data_array);
        ob_end_flush();
    }

    public function getResult()
    {
        $casetype = "";
        $condition = "";
        $criteria = "";
        $option = "";
        $casetype_name = "";
        if (isset($_POST['figure']) && $_POST['figure'])
            $option = "1";
        else if (isset($_POST['full']) && $_POST['full'])
            $option = "2";
        $casestatus = isset($_POST['rbtCaseStatus']) ? $_POST['rbtCaseStatus'] : '';
        $pendencyOption = isset($_POST['rbtPendingOption']) ? $_POST['rbtPendingOption'] : '';
        $filingDateFrom = isset($_POST['filingDateFrom']) ? $_POST['filingDateFrom'] : '';
        $filingDateTo = isset($_POST['filingDateTo']) ? $_POST['filingDateTo'] : '';
        $registrationDateFrom = isset($_POST['registrationDateFrom']) ? $_POST['registrationDateFrom'] : '';
        $registrationDateTo = isset($_POST['registrationDateTo']) ? $_POST['registrationDateTo'] : '';
        $caseYear = isset($_POST['caseYear']) ? $_POST['caseYear'] : '';
        $disposalDateFrom = isset($_POST['disposalDateFrom']) ? $_POST['disposalDateFrom'] : '';
        $disposalDateTo = isset($_POST['disposalDateTo']) ? $_POST['disposalDateTo'] : '';
        $rbtCaseType = isset($_POST['rbtCaseType']) ? $_POST['rbtCaseType'] : '';
        $casetypeList = isset($_POST['caseType']) ? $_POST['caseType'] : '';
        if (is_array($casetypeList)) {
            foreach ($casetypeList as $casetype1) {
                $casetype1 = explode("^", $casetype1);
                $casetype .= $casetype1[0] . ",";
                $casetype_name .= $casetype1[1] . ",";
            }
        }
        $casetype = rtrim($casetype, ",");
        $casetype_name = rtrim($casetype_name, ",");
        $matterType = isset($_POST['matterType']) ? $_POST['matterType'] : '';
        $respondentName = isset($_POST['respondentName']) ? $_POST['respondentName'] : '';
        $por = isset($_POST['PorR']) ? $_POST['PorR'] : '';
        $diaryYear = isset($_POST['diaryYear']) ? $_POST['diaryYear'] : '';
        $subjectCategoryList = isset($_POST['subjectCategory']) ? $_POST['subjectCategory'] : '';
        $subjectCategoryList = !empty($subjectCategoryList) ? explode("^", $subjectCategoryList) : '';
        $subjectCategory = is_array($subjectCategoryList) ? $subjectCategoryList[0] : '';
        $subjectCategory_name = is_array($subjectCategoryList) ? $subjectCategoryList[1] : '';
        $subCategoryCodeList = isset($_POST['subCategoryCode']) ? $_POST['subCategoryCode'] : '';
        $subCategoryCodeList = explode("^", $subCategoryCodeList);
        $subCategoryCode = is_array($subCategoryCodeList) ? $subCategoryCodeList[0] : '';
        $subCategoryCode_name_list = (is_array($subCategoryCodeList) && isset($subCategoryCodeList[1])) ? explode("#-#", $subCategoryCodeList[1]) : '';
        $subCategoryCode_name = is_array($subCategoryCode_name_list) ? $subCategoryCode_name_list[1] . " (" . $subCategoryCode_name_list[0] . ")" : '';
        $sections = isset($_POST['section']) ? $_POST['section'] : '';
        $sections = !empty($sections) ? explode("^", $sections) : '';
        $section = is_array($sections) ? $sections[0] : '';
        $section_name = is_array($sections) ? $sections[1] : '';
        $da = isset($_POST['dealingAssistant']) ? $_POST['dealingAssistant'] : '';
        $das = !empty($da) ? explode("^", $da) : '';
        $dacode = is_array($das) ? $das[0] : '';
        $daname = is_array($das) ? $das[1] : '';
        $chkshowDA = isset($_POST['showDA']) ? $_POST['showDA'] : '';
        $agencyStateList = isset($_POST['agencyState']) ? $_POST['agencyState'] : '';
        $agencyStateList = !empty($agencyStateList) ? explode("^", $agencyStateList) : '';
        $agencyState = is_array($agencyStateList) ? $agencyStateList[0] : '';
        $state_name = is_array($agencyStateList) ? $agencyStateList[1] : '';
        $agencyCodeList = isset($_POST['agencyCode']) ? $_POST['agencyCode'] : '';
        $agencyCodeList = !empty($agencyCodeList) ? explode("^", $agencyCodeList) : '';
        $agencyCode = is_array($agencyCodeList) ? $agencyCodeList[0] : '';
        $code_name = is_array($agencyCodeList) ? $agencyCodeList[1] : '';
        $listingDate = isset($_POST['listingDate']) ? $_POST['listingDate'] : '';
        $coramList = isset($_POST['coram']) ? $_POST['coram'] : '';
        $coramList = !empty($coramList) ? explode("^", $coramList) : '';
        $coram = is_array($coramList) ? $coramList[0] : '';
        $judge_name = is_array($coramList) ? $coramList[1] : '';
        $rbtCoram = isset($_POST['rbtCoram']) ? $_POST['rbtCoram'] : '';
        $chkJailMatter = isset($_POST['chkJailMatter']) ? $_POST['chkJailMatter'] : '';
        $chkFDMatter = isset($_POST['chkFDMatter']) ? $_POST['chkFDMatter'] : '';
        $chkLegalAid = isset($_POST['chkLegalAid']) ? $_POST['chkLegalAid'] : '';
        $chkSpecificDate = isset($_POST['chkSpecificDate']) ? $_POST['chkSpecificDate'] : '';
        $chkPartHeard = isset($_POST['chkPartHeard']) ? $_POST['chkPartHeard'] : '';
        $aor = isset($_POST['advocate']) ? $_POST['advocate'] : '';
        $aors = !empty($aor) ? explode("^", $aor) : '';
        $bar_id = is_array($aors) ? $aors[0] : '';
        $aor_name = is_array($aors) ? $aors[1] : '';
        $sortList = isset($_POST['sort']) ? $_POST['sort'] : '';
        $sortList = !empty($sortList) ? explode("^", $sortList) : '';
        $sort = is_array($sortList) ? $sortList[0] : '';
        $sortOption = '';
        $sortOption2 = '';
        $sort_name = is_array($sortList) ? $sortList[1] : '';
        $sortOrder = isset($_POST['rbtSortOrder']) ? $_POST['rbtSortOrder'] : '';
        $joinCondition = '';
        $advPor = isset($_POST['advPorR']) ? $_POST['advPorR'] : '';
        if ($casestatus == 'f') {
            $criteria = "<b>Case Status :</b> Filing <br/>";
            $condition = " where 1=1";
        } else if ($casestatus == 'i') {
            $criteria = "<b>Case Status :</b> Registration <br/>";
            $condition = " where active_fil_no is not null and active_fil_no!='' ";
        } else if ($casestatus == 'p') {
            $criteria = "<b>Case Status :</b> Pending ";
            $condition = " where c_status='P'";
        } else if ($casestatus == 'd') {
            $criteria = "<b>Case Status :</b> Disposed <br/>";
            $condition = " where c_status='D'";
        }
        if ($pendencyOption == 'R' and $casestatus == 'p') {
            $criteria .= " -Registered Matters <br/>";
            $condition .= " and active_fil_no is not null and active_fil_no!=''";
        } else if ($pendencyOption == 'UR' and $casestatus == 'p') {
            $criteria .= " -Unregistered Matters <br/>";
            $condition .= " and (active_fil_no='' or active_fil_no is null)";
        } else if ($pendencyOption == 'b' and $casestatus == 'p') {
            $criteria .= " -All Matters <br/>";
        }
        if (!empty($filingDateFrom) and !empty($filingDateTo)) {
            $condition .= " and date(diary_no_rec_date) between '" . date('Y-m-d', strtotime($filingDateFrom)) . "' and '" . date('Y-m-d', strtotime($filingDateTo)) . "'";
            $criteria .= "<b>Filing Date From </b>" . $filingDateFrom . "<b> To </b>" . $filingDateTo . "<br>";
        }
        if (!empty($registrationDateFrom) and !empty($registrationDateTo)) {
            $condition .= " and date(active_fil_dt) between '" . date('Y-m-d', strtotime($registrationDateFrom)) . "' and '" . date('Y-m-d', strtotime($registrationDateTo)) . "'";
            $criteria .= "<b>Registration Date From </b>" . $registrationDateFrom . "<b> To </b>" . $registrationDateTo . "<br>";
        }
        if (!empty($disposalDateFrom) and !empty($disposalDateTo)) {
            $condition .= " and date(d.ord_dt) between '" . date('Y-m-d', strtotime($disposalDateFrom)) . "' and '" . date('Y-m-d', strtotime($disposalDateTo)) . "'";
            $criteria .= "<b>Disposal Date From </b>" . $disposalDateFrom . "<b> To </b>" . $disposalDateTo . "<br>";
        }
        if ($caseYear != 0) {
            $condition .= " and active_reg_year=$caseYear";
            $criteria .= "<b> Registration Year : </b>" . $caseYear . "<br>";
        }
        if ($rbtCaseType == 'C') {
            $condition .= " and case_grp='C'";
            $criteria .= "<b> Case Type : </b> Civil Matters<br>";
        } else if ($rbtCaseType == 'R') {
            $condition .= " and case_grp='R'";
            $criteria .= "<b> Case Type : </b> Criminal Matters<br>";
        } else if ($rbtCaseType == 'b') {
            $criteria .= "<b> Case Type : </b> All<br>";
        }
        if (!empty($casetype)) {
            $condition .= " and active_casetype_id in($casetype)";
            $criteria .= "<b> Case Type : </b>" . $casetype_name . "<br>";
        }
        if ($matterType == 'M') {
            $condition .= " and mf_active='M'";
            $criteria .= "<b> Matter Type : </b> Miscelleneous Matters <br>";
        } else if ($matterType == 'F') {
            $condition .= " and mf_active='F'";
            $criteria .= "<b> Matter Type : </b> Regular Matters <br>";
        } else if ($matterType == 'all') {
            $criteria .= "<b> Matter Type : </b> All <br>";
        }
        if (!empty($respondentName)) {
            if (!empty($diaryYear)) {
                if ($por == '1') {
                    $condition .= " and (partyname like '%$respondentName%' and year(diary_no_rec_date)=$diaryYear and p.pet_res='P' and p.pflag in('P','D'))";
                    $criteria .= "<b> Petitioner Name like </b>" . $respondentName . " <b> and Filed in the year </b>" . $diaryYear . "<br>";
                } else if ($por == '2') {
                    $condition .= " and (partyname like '%$respondentName%' and year(diary_no_rec_date)=$diaryYear and p.pet_res='R' and p.pflag in('P','D'))";
                    $criteria .= "<b> Respondent Name like </b>" . $respondentName . " <b> and Filed in the year </b>" . $diaryYear . "<br>";
                } else if ($por == '0') {
                    $condition .= " and (partyname like '%$respondentName%' and year(diary_no_rec_date)=$diaryYear and p.pet_res in('P','R') and p.pflag in('P','D'))";
                    $criteria .= "<b> Party Name like </b>" . $respondentName . " <b> and Filed in the year </b>" . $diaryYear . "<br>";
                }
            } else {
                if ($por == '1') {
                    $condition .= " and (partyname like '%$respondentName%' and p.pet_res='P' and p.pflag in('P','D'))";
                    $criteria .= "<b> Petitioner Name like </b>" . $respondentName . "<br>";
                } else if ($por == '2') {
                    $condition .= " and (partyname like '%$respondentName%' and p.pet_res='R' and p.pflag in('P','D'))";
                    $criteria .= "<b> Respondent Name like </b>" . $respondentName . "<br>";
                } else if ($por == '0') {
                    $condition .= " and (partyname like '%$respondentName%' and p.pet_res in('P','R') and p.pflag in('P','D'))";
                    $criteria .= "<b> Party Name like </b>" . $respondentName . "<br>";
                }
            }
        }
        if (!empty($section) && $section != 0) {
            $section = $section??'';
            $condition .= " and u.section=$section";
            $criteria .= "<b> Section : </b>" . $section_name . "<br>";
        }
        if (!empty($dacode) && $dacode != 0) {
            $dacode = $dacode??'';
            $condition .= " and u.usercode=$dacode";
            $criteria .= "Matters dealt with by <b>$daname</b><br>";
        }
        if (!empty($subjectCategory) && $subjectCategory != 0) {
            $subjectCategory = $subjectCategory??'';
            $condition .= " and s.subcode1=$subjectCategory";
            $criteria .= "<b> Main Subject Category : </b>" . $subjectCategory_name . "<br>";
        }
        if (!empty($subCategoryCode) && $subCategoryCode != 0) {
            $subCategoryCode = $subCategoryCode??'';
            $condition .= " and s.id=$subCategoryCode";
            $criteria .= "<b> Sub Subject Category : </b>" . $subCategoryCode_name . "<br>";
        } else
            $criteria .= "<b> Sub Subject Category : </b>" . "All<br>";
        if (!empty($agencyState) && $agencyState != 0) {
            $agencyState = $agencyState??'';
            $condition .= " and ref_agency_state_id=$agencyState";
            $criteria .= "<b> State : </b>" . $state_name . "<br>";
        }
        if (!empty($agencyCode) && $agencyCode != 0) {
            $agencyCode = $agencyCode??'';
            $condition .= " and ref_agency_code_id=$agencyCode";
            $criteria .= "<b> Agency : </b>" . $code_name . "<br>";
        } else
            $criteria .= "<b> Agency : </b>" . "All<br>";
        if ((int)$chkLegalAid == 1) {
            $condition .= " and if_sclsc=1";
            $criteria .= " Legal Aid Matters <br>";        }
        if ((int)$chkJailMatter == 1) {
            $joinCondition = "left join jail_petition_details jpd on m.diary_no=jpd.diary_no and jail_display='Y'
                left join brdrem brd on m.diary_no=brd.diary_no and (brd.remark like '%jail%' or brd.remark like '%Jail%')
                left join advocate adv1 on m.diary_no=adv1.diary_no and adv1.advocate_id=613 and adv1.pet_res='P' and adv1.pet_res_no=1 and adv1.display='Y'";
            $condition .= " and (nature::integer=6 or pet_adv_id=613)";
            $criteria .= " Jail Petition Matters <br>";
        }
        if ((int)$chkFDMatter == 1) {
            $condition .= " and h.subhead in(815,816)";
            $criteria .= " Final Disposal Matters <br>";
        }
        if ((int)$chkSpecificDate == 1) {
            $condition .= " and crm.r_head::integer=24";
            $criteria .= " Specific Date Matters <br>";
        }
        if ((int)$chkPartHeard == 1) {
            $condition .= " and h.subhead=824";
            $criteria .= " Part Heard Matters <br>";
        }
        if (!empty($bar_id) && $bar_id != 0) {
            if ($advPor == '1') {
                $condition .= " and adv1.advocate_id=$bar_id and p.pet_res='P'";
                $criteria .= "<b> Petitioner Advocate : </b>$aor_name<br>";
            } else if ($advPor == '2') {
                $condition .= " and adv1.advocate_id=$bar_id and p.pet_res='R'";
                $criteria .= "<b> Respondent Advocate : </b>$aor_name<br>";
            } else if ($advPor == '0') {
                $condition .= " and adv1.advocate_id=$bar_id";
                $criteria .= "<b> Advocate : </b>$aor_name<br>";
            }
        }
        if ($sort != 0) {
            if ($sort == 1) {
                $sortOption = "substring(m.diary_no::text,-4) as diaryNo1, substr(m.diary_no::text,1,length(m.diary_no::text)-4) as diaryNo2";
                $sortOption2 = "diaryNo1 " . $sortOrder . ", diaryNo2 " . $sortOrder;
            } else if ($sort == 2) {
                $sortOption = "active_reg_year as active_reg_year, substring(active_fil_no::text,1,2) as active_fil_no1, substring(active_fil_no::text,4,6) as active_fil_no2";
                $sortOption2 = "active_reg_year " . $sortOrder . ", active_fil_no1 " . $sortOrder . ", active_fil_no2 " . $sortOrder;
            } else if ($sort == 3) {
                $sortOption = "date(diary_no_rec_date) as rec_date";
                $sortOption2 = "rec_date " . $sortOrder;
            } else if ($sort == 4) {
                $sortOption = "date(active_fil_dt) as fil_dt";
                $sortOption2 = "fil_dt " . $sortOrder;
            } else if ($sort == 5) {
                $sortOption = "us.section_name as sec_name";
                $sortOption2 = "sec_name " . $sortOrder;
            } else if ($sort == 6) {
                $sortOption = "subcode1 as subcd1, category_sc_old as cat_sc_old";
                $sortOption2 = "subcd1 " . $sortOrder . ", cat_sc_old " . $sortOrder;
            } else if ($sort == 7) {
                $sortOption = "agency_state as ag_state";
                $sortOption2 = "ag_state " . $sortOrder;
            } else if ($sort == 8) {
                $sortOption = "c_status as c_stat";
                $sortOption2 = "c_stat " . $sortOrder;
            } else if ($sort == 9) {
                $sortOption = "h.next_dt as next_dt";
                $sortOption2 = "next_dt " . $sortOrder;
            }
            $criteria .= " <b> Sort by : </b>" . $sort_name . "<br>";
        }
        $data['option'] = $option;
        $data['criteria'] = $criteria;
        $data['showDA'] = $chkshowDA;
        $data['result'] = $this->model->get_result($option, $condition, $sortOption, $sortOption2, $joinCondition);
        return view('DynamicReport/result', $data);
    }

}