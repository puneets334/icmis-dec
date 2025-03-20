<?php
 //require_once './includes/db_inc.php';
//require_once './includes/functions.php';
/*$unique_id_processed_active = base64_decode($_REQUEST['id']);
$content_url = base64_decode($_REQUEST['url']);
$pageTitle = base64_decode($_REQUEST['title']);
$old_smenu = $_REQUEST['old_smenu'];*/
/* echo 'unique_id_processed_active='.$unique_id_processed_active.' content_url='.$content_url.' pageTitle='.$pageTitle.' old_smenu='.$old_smenu;
 exit();*/
$script = array();
$display_url = $content_url;
$is_external_url = "NO";
switch($old_smenu){
    case 50:{
        $url = "Copying/index.php/Application/application/".$_SESSION[dcmis_user_idd];
        break;
    }
    case 81021:{
        $url = "Copying/index.php/Elimination/get_session/".$_SESSION[dcmis_user_idd];
        break;
    }
    case 81020:{
        $url = "Copying/index.php/Application/application_status/".$_SESSION[dcmis_user_idd];
        break;
    }
    case 81031:{
        $url = "Copying/index.php/Application/orders/";
        break;
    }
    case 81032:{
        $url = "Copying/index.php/Application/copy_report/";
        break;
    }
    case 81033:{
        $url = "Copying/index.php/Application/da_cases/";
        break;
    }

    case 81037:{
        $url = "Copying/index.php/Reports/da_rog/";
        break;
    }
    case 81043:{
        $url = "Copying/index.php/Reports/da_wise_report/".$_SESSION[icmic_empid];
        break;

    }
    case 81058:{
        $url = "Copying/index.php/Recall/get_session/".$_SESSION[dcmis_user_idd];
        break;
    }
    case 81059:{
        $url = "Copying/index.php/Dispose_before_other_case/get_session/".$_SESSION[dcmis_user_idd];
        break;
    }
    case 81060:{
        $url = "Copying/index.php/Restoration/get_session/".$_SESSION[dcmis_user_idd];
        break;
    }
    case 81066:{
        $url = "Copying/index.php/Application/user_report";
        break;
    }

    case 81067:{
        $url = "Copying/index.php/Application/pending_documents/0/".$_SESSION[dcmis_user_idd];
        break;
    }

    case 81077:{
        $url = "Copying/index.php/CourtMasterController/getSession/".$_SESSION[dcmis_user_idd];
        break;
    }

    case 43:{
        $url = "Copying/index.php/Mentioning/getSession/".$_SESSION[dcmis_user_idd];
        break;
    }

    case 81084:{
        $url = "Copying/index.php/Mentioning/MentioningReport/getSession/".$_SESSION[dcmis_user_idd];
        break;
    }
    case 81089:{

        $url = "Copying/index.php/CourtMasterController/get_session_upload/".$_SESSION[dcmis_user_idd];
        break;
    }
    case 81090:{
        $url = "Copying/index.php/Reports/loose_document/1";
        break;
    }

    case 81096:{
        $url="Copying/index.php/Reports/loose_document_da/".$_SESSION[dcmis_user_idd];
        break;
    }
    case 81107:{
        $url="Copying/index.php/Reports/section_pendency/".$_SESSION[dcmis_section];
        break;
    }
    case 81109:{
        $url="Copying/index.php/Reports/causelist_info";
        break;
    }
    case 81122:{
        $url="Copying/index.php/Efiling/efiling_applications";
        break;
    }
    case 81190:{
        $url="Copying/index.php/Record_keeping/get_session/".$_SESSION[dcmis_user_idd];
        break;
    }
    case 81192:{
        $url="Copying/index.php/Reports_chamber/aor_detail/";
        break;
    }
    case 81193:{
        $url="Copying/index.php/Reports_chamber/aor_detail_new/";
        break;
    }

    case 81158:{
        $url="Copying/index.php/Reports/SCLSC_Pending_Report";
        break;
    }
    case 81168:{
        $url="Copying/index.php/Application/action_pending_report/".$_SESSION[icmic_empid];
        break;
    }
    case 81108:{
        $url="Copying/index.php/Reports/listed_matter";
        break;
    }
    case 81145:{
        $url="Copying/index.php/Application/diaryNumberSearch";
        break;
    }

    case 81181:{
        $url="Copying/index.php/Efiling/check_documents";
        break;
    }

    case 81185:{
        $url="Copying/index.php/Efiling/transactions_by_refID";
        break;
    }

    case 81186:{
        $url="Copying/index.php/Efiling/transactions_by_date/".$_SESSION[dcmis_user_idd];
        break;
    }


    case 81187:{
        $url="Copying/index.php/Efiling/docs_from_sc_diary_no";
        break;
    }

    case 81196:{
        $url = "Copying/index.php/Application/specimen_signature";
        break;
    }

    case 81197:{
        $url="Copying/index.php/Law_point/get_session/".$_SESSION[dcmis_user_idd];
        break;
    }

    case 81227:{//For Entry
        $url="Copying/index.php/Latest_Updates/get_Latest_updates/";
        break;
    }

    case 81228:{//For Report
        $url="Copying/index.php/Latest_Updates/get_updates/";
        break;
    }
    case 81230:{//For Godown SMS
        $url="Copying/index.php/PaperBooksSMS/get_session/".$_SESSION[dcmis_user_idd];
        break;
    }
    case 81231:{//For Godown SMS
        $url="Copying/index.php/PaperBooksSMS/displayRISMSPage/".$_SESSION[dcmis_user_idd];
        break;
    }
    case 81232:{
        $url ="Copying/index.php/Sensitive_info/get_session/".$_SESSION[dcmis_user_idd];
        break;
    }

    case 81277:{
        $url = "Copying/index.php/FileTrap/get_session/".$_SESSION[dcmis_user_idd];
        break;
    }
    case 81278:{
        $url = "Copying/index.php/FileTrap/index1/".$_SESSION[dcmis_user_idd];
        break;
    }
    case 81280:{
        $url = "Copying/index.php/FileTrap/rrUsersCaseMapping/".$_SESSION[dcmis_user_idd];
        break;
    }
    case 81282:{
        $url="Copying/index.php/OriginalRecords/getSession/".$_SESSION[dcmis_user_idd];
        break;
    }

    case 81285:{
        $url="Copying/index.php/Matrix/Judges_Entry/".$_SESSION[dcmis_user_idd];
        break;
    }
    case 81286:{
        $url="Copying/index.php/Matrix/Judges_Update/".$_SESSION[dcmis_user_idd];
        break;
    }

    case 81287:{
        $url="Copying/index.php/Matrix/Judges_Report/".$_SESSION[dcmis_user_idd];
        break;
    }

    case 81292:{
        $url="Copying/index.php/Reports/getORuploded_status/".$_SESSION[dcmis_user_idd];
        break;
    }

    case 81290:{
        $url="Copying/index.php/Dispose_before_other_case/Remove_index/".$_SESSION[dcmis_user_idd];
        break;
    }

    case 81297:{
        $url="Copying/index.php/Application/bar_list";
        break;
    }

    case 81302:{
        $url="Copying/index.php/Matrix/Menu_List";
        break;
    }
    case 81303:{
        $url="Copying/index.php/Application/action_pending_report_da/".$_SESSION[icmic_empid];
        break;
    }

    case 81310:{
        $url="Copying/index.php/Matrix/Sentence_undergone/".$_SESSION[icmic_empid];
        break;
    }

    case 81311:{
        $url="Copying/index.php/JudgeMaster/getSession/".$_SESSION[dcmis_user_idd];
        break;
    }
    case 81312:{
        $url = "Copying/index.php/JudgeMaster/judgeCategory/".$_SESSION[dcmis_user_idd];
        break;
    }
    case 81314:{
        $url = "Copying/index.php/JudgeMaster/judgeCategoryUpdate/".$_SESSION[dcmis_user_idd];
        break;
    }

    case 81313:{
        $url = "Copying/index.php/JudgeMaster/judgeCategoryReport";
        break;
    }

    case 81326:{
        $url = "Copying/index.php/JudgeMaster/update_judge_bulkcategory/".$_SESSION[dcmis_user_idd];
        break;
    }
    case 81327:{
        $url = "Copying/index.php/Causelist/dateWise";
        break;
    }
    case 81328:{
        $url = "Copying/index.php/Causelist/judgeWise";
        break;
    }

    case 81329:{
        $url = "Copying/index.php/JudgeMaster/transfer_Judge_Category/".$_SESSION[dcmis_user_idd];
        break;
    }

    case 81334:{
        $url = "Copying/index.php/Application/bulkStatusGet/".$_SESSION[dcmis_user_idd];
        break;
    }
    case 81336:{
        $url = "Copying/index.php/Listing/showHeardt";
        break;
    }
    case 81338:{
        $url="Copying/index.php/Reports/monitoring_Error";
        break;
    }
    case 81339:{
        $url="Copying/index.php/Reports/monitoring_Error_Dawise_count";
        break;
    }
    case 81341:{
        $url="Copying/index.php/Reports/get_loosedoc_verify_Nverify/".$_SESSION[dcmis_user_idd];
        break;
    }

    case 81342:{
        $url="Copying/index.php/Reports/get_loosedoc_verify_Nverify/".$_SESSION[dcmis_user_idd];
        break;
    }

    case 81343:{
        $url="Copying/index.php/Reports/get_loosedoc_verify_Nverify/".$_SESSION[dcmis_user_idd];
        break;
    }
    case 81349:{
        $url="Copying/index.php/Reports/CtRemarks_Changeby_user/";
        break;
    }
    case 81350:{
        $url="Copying/index.php/Reports/case_listed_Advance_Daily_dawise/".$_SESSION[dcmis_user_idd];
        break;
    }
    //PIL(E) menu
    case 81375:{
        $url="Copying/index.php/PilController/get_session/".$_SESSION[dcmis_user_idd];
        break;
    }
    case 81377:{
        $url="Copying/index.php/PilController/getPilReport/";
        break;
    }
    case 81376:{
        $url="Copying/index.php/PilController/showPilGroup/";
        break;
    }
    case 81379:{
        $url="Copying/index.php/PilController/getPilUserWise/";
        break;
    }
    case 81381:{
        $url="Copying/index.php/PilController/queryPilData/";
        break;
    }
    //PIL(E) END
    case 81382:{
        $url="Copying/index.php/R_and_I/getSession/".$_SESSION[dcmis_user_idd];
        break;
    }
    case 81385:{
        $url="Copying/index.php/PilController/addToPilGroupShow/";
        break;
    }
    case 59:{
        $url = "Copying/index.php/CourtMasterController/getReplacePage/".$_SESSION[dcmis_user_idd];
        break;
    }
    case 81396:{
        $url = "Copying/index.php/Fdr/bankMaster/".$_SESSION[dcmis_user_idd];
        break;
    }
    case 81395:{
        $url = "Copying/index.php/Fdr/caseInfo/".$_SESSION[dcmis_user_idd];
        break;
    }

    case 81397:{
        $url = "Copying/index.php/Fdr/fdr_search/".$_SESSION[dcmis_user_idd];
        break;
    }
    case 81398:{
        $url="Copying/index.php/RIController/get_session/".$_SESSION[dcmis_user_idd];
        break;
    }
    case 81401:{
        $url="Copying/index.php/RIController/getDispatch";
        break;
    }
    case 81402:{
        $url="Copying/index.php/RIController/getDakDataForReceive/".$_SESSION[dcmis_user_idd];
        break;
    }
//Dispatch Module Start
    case 81411:{
        $url="Copying/index.php/RIController/getDataToDispatchWithProcessId/".$_SESSION[dcmis_user_idd];
        break;
    }
    case 81412:{
        $url="Copying/index.php/RIController/receiveDakToDispatchInRIWithProcessId/".$_SESSION[dcmis_user_idd];
        break;
    }
    case 81413:{
        $url="Copying/index.php/RIController/getAddressSlip/";
        break;
    }
    case 81414:{
        $url="Copying/index.php/RIController/dispatchDakFromRI/".$_SESSION[dcmis_user_idd];
        break;
    }
    case 81416:{
        $url="Copying/index.php/RIController/dispatchToRIWithoutProcessId/".$_SESSION[dcmis_user_idd];
        break;
    }
    case 81409:{
        $url="Copying/index.php/RIController/showServeUnServe/".$_SESSION[dcmis_user_idd];
        break;
    }
    case 81410:{
        $url="Copying/index.php/RIController/getADToDispatch/".$_SESSION[dcmis_user_idd];
        break;
    }
    case 81415:{
        $url="Copying/index.php/RIController/reDispatchDakFromRI/".$_SESSION[dcmis_user_idd];
        break;
    }
    case 81417:{
        $url="Copying/index.php/RIController/showCreateLetterGroup/".$_SESSION[dcmis_user_idd];
        break;
    }
    //R&I End
    //CaseList File Movement Start
    case 81428:{
        $url = "Copying/index.php/CauseListFileMovementController/getSession/".$_SESSION[dcmis_user_idd];
        break;
    }
    case 81431:{
        $url = "Copying/index.php/CauseListFileMovementController/receiveFromCM/".$_SESSION[dcmis_user_idd];
        break;
    }
    case 81429:{
        $url = "Copying/index.php/CauseListFileMovementController/receiveFromDA/".$_SESSION[dcmis_user_idd];
        break;
    }
    case 81430:{
        $url = "Copying/index.php/CauseListFileMovementController/sendBackToDA";
        break;
    }
    //END
    case 81438:{
        $url="Copying/index.php/ROP_Uploaded/show_count_between_dates";
        break;
    }

    case 81448:{    // id of menu
        $url = "Copying/index.php/Reports/matters_listed";
        break;
    }
    case 81446:{    // id of update Gist menu
        $url = "Copying/index.php/ESCR/getSession/".$_SESSION[dcmis_user_idd];
        break;
    }

    case 81447:{     // id of Report
        $url = "Copying/index.php/ESCR/show_count";
        break;
    }
    case 81457:{
        $url = "Copying/index.php/PilController/getPilDetailByDiaryNumberForLetterGeneration";
        break;
    }
    case 81458:{
        $url = "Copying/index.php/PilController/reportPilGroup";
        break;
    }
//R&I forwad letter started
    case 81459:{
        $url = "Copying/index.php/RIController/forwardOrInitiateLetter/".$_SESSION[dcmis_user_idd];
        break;
    }
    //R&I forwad letter report
    case 81460:{
        $url = "Copying/index.php/RIController/dateWiseForwardedInitiated/".$_SESSION[dcmis_user_idd];
        break;
    }
    //END
    /*REPORTS SECTION*/
    case 81045:{
        $url = "Copying/index.php/Reports/tagged_matter_report";
        break;
    }
    case 81046:{
        $url = "Copying/index.php/Reports/pendency_reports/1";
        break;
    }
    case 81047:{
        $url = "Copying/index.php/Reports/pendency_reports/2";
        break;
    }
    case 81153:{
        $url = "Copying/index.php/Reports/pendency_reports/5";
        break;
    }
    case 81154:{
        $url = "Copying/index.php/Reports/pendency_reports/6";
        break;
    }
    case 81160:{
        $url = "Copying/index.php/Reports/current_pendency_report/34";
        break;
    }
    case 81170:{
        $url = "Copying/index.php/Reports/Disposal_AsPer_Updation";
        break;
    }
    case 81171:{
        $url = "Copying/index.php/Reports/Disposal_AsPer_Orderdate";
        break;
    }
    case 81182:{
        $url = "Copying/index.php/Reports/aor_detail/1";
        break;
    }
    case 81183:{
        $url = "Copying/index.php/Reports/aor_detail/2";
        break;
    }
    case 81184:{
        $url = "Copying/index.php/Reports/aor_detail2";
        break;
    }
    case 81188:{
        $url = "Copying/index.php/Reports/ctMaster_ason_disposal_remarks";
        break;
    }

    case 81189:{
        $url = "Copying/index.php/Reports/case_type_wise_pendency";
        break;
    }
    case 81190:{
        $url = "Copying/index.php/Record_keeping";
        break;
    }
    case 81192:{
        $url="Copying/index.php/Reports_chamber/aor_detail/";
        break;
    }
    case 81193:{
        $url="Copying/index.php/Reports_chamber/aor_detail_new/";
        break;
    }
    case 81194:{
        $url = "Copying/index.php/Reports/defectiveMattersNotListed/".$_SESSION[dcmis_user_idd];
        break;
    }
    case 81191:{
        $url = "Copying/index.php/Record_keeping/ConsignmentReport/".$_SESSION[dcmis_user_idd];
        break;
    }
    case 81198:{
        $url = "Copying/index.php/Law_point/LawPointReport/".$_SESSION[dcmis_user_idd];
        break;
    }
    case 81200:{
        $url = "Copying/index.php/Reports/main_subject_categorywise_pendency";
        break;
    }
    case 81207:{
        $url = "Copying/index.php/Matrix/Caveat_List";
        break;
    }
    case 81213:{
        $url="Copying/index.php/Law_point/VerifyLawPoint/".$_SESSION[dcmis_user_idd];
        break;
    }
    case 81221:{
        $url="Copying/index.php/Filing_Reports/dak_date_wise/";
        break;
    }
    case 81223:{
        $url="Copying/index.php/Filing_Reports/diary_user_wise/";
        break;
    }
    case 81224:{
        $url="Copying/index.php/Filing_Reports/lowerct_user_wise/";
        break;
    }
    case 81225:{
        $url="Copying/index.php/Filing_Reports/scrutiny_user_wise/";
        break;
    }
    case 81226:{
        $url="Copying/index.php/Filing_Reports/dak_section_wise_summary/";
        //echo "testing";
        break;
    }
    case 81233:{
        $url="Copying/index.php/Sensitive_info/SensitiveCasesReport";
        break;
    }
    case 81237:{
        $url="Copying/index.php/Reports/vacationAdvancedList/";
        break;
    }
    case 81254:{
        $url="Copying/index.php/VacationAdvanceReport/";
        break;
    }
    case 81279:{
        $url = "Copying/index.php/FileTrap/receiveDispatchReport/";
        break;
    }
    case 81283:{
        $url="Copying/index.php/OriginalRecords/originalRecordReport/".$_SESSION[dcmis_user_idd];
        break;
    }
    case 81359:{
        $url = "Copying/index.php/Reports/UploadedJudgmentOrdersList/";
        break;
    }
    case 81388:{
        $url = "Copying/index.php/FileTrap/getAlreadyConsignedRestoredCaseList/";
        break;
    }

    case 81399:{
        $url="Copying/index.php/RIController/dateWiseReceived";
        break;
    }
    case 81403:{
        $url="Copying/index.php/RIController/dateWiseDispatched";
        break;
    }
    case 81404:{
        $url="Copying/index.php/RIController/dateWiseReceivedByConcern";
        break;
    }
    case 81407:{
        $url = "Copying/index.php/CourtMasterController/vernacularJudgmentReport/";
        break;
    }
    //R&I Dispatch Report Start
    case 81418:{
        $url="Copying/index.php/RIController/dateWiseDispatchedFromSection/".$_SESSION[dcmis_user_idd];
        break;
    }
    case 81419:{
        $url="Copying/index.php/RIController/dateWiseReceivedInRIFromSection/".$_SESSION[dcmis_user_idd];
        break;
    }
    case 81420:{
        $url="Copying/index.php/RIController/showDispatchQueryPage/";
        break;
    }
    case 81421:{
        $url="Copying/index.php/RIController/dispatchQuery/".$_SESSION[dcmis_user_idd];
        break;
    }
    //END
    case 81426:{
        $url="Copying/index.php/RIController/receivedQuery/".$_SESSION[dcmis_user_idd];
        break;
    }
    case 81456:
    {
        $url = "Copying/index.php/Reports/matters";
        break;
    }
    case 81489:{   ///For record keeping
        $url = "Copying/index.php/Record_keeping/RipeCasesReport/".$_SESSION[dcmis_user_idd];
        break;
    }
    case 81500:
    {
        $url = "Copying/index.php/FasterController/index/".$_SESSION[dcmis_user_idd];
        break;
    }
    case 81560:{
        //for faster reports
        $url="Copying/index.php/DocumentDashboard";
        break;
    }
    case 81864:{
        $url="Copying/index.php/CourtMasterController/embedQRAndDownload/".$_SESSION[dcmis_user_idd];
        break;
    }
    case 81861:{
        //to send case for faster
        $url="Copying/index.php/FasterController/sendForFaster/".$_SESSION[dcmis_user_idd];
        break;
    }
    case 81862:{
        $url="Copying/index.php/IP_Controller/courtsip/".$_SESSION[dcmis_user_idd];
        break;
    }
// For SCLSC diary Generation
    case 81863:{
        $url="Copying/index.php/SCLSC/index/".$_SESSION[dcmis_user_idd];
        break;
    }
    case 81865:{
        $url="Copying/index.php/SCLSC/diaryGeneratedByAPI/".$_SESSION[dcmis_user_idd];
        break;
    }
case 81870:{
   $url="Copying/index.php/Causelist/singleJudgeNominateModuleStart/".$_SESSION[dcmis_user_idd];
   break;
}
case 81871:{
   $url="Copying/index.php/Causelist/singleJudgeAdvanceAllocationIndex/".$_SESSION[dcmis_user_idd];
   break;
}
case 81872:{
   $url="Copying/index.php/Causelist/singleJudgeFinalAllocationIndex/".$_SESSION[dcmis_user_idd];
   break;
}
    case 90000:{
        $url="Copying/index.php/JudgesRosterController/getSession/".$_SESSION[dcmis_user_idd];
        break;
    }

    case 90001:{
        $url="Copying/index.php/FileUpload/upload/".$_SESSION[dcmis_user_idd];
        break;
    }

    case 90002:{     // id of date wise Report
        $url = "Copying/index.php/ESCR/show_count";
        break;
    }
    case 90003:{  //id of user wise report
        $url = "Copying/index.php/ESCR/userwise_count/".$_SESSION[dcmis_user_idd];
        break;
    }
    case 90004:{
        $url = "web_casting";
        break;
    }
    case 90005:{
        $url = "web_casting/Home/Court_no";
        break;
    }

    case 90006:
    {
        $url="Copying/index.php/VC_Detail";
        break;
    }

    case 90007:
    {
        $url="Copying/index.php/VC_Report";
        break;
    }

    case 90008:
    {
        $url="Copying/index.php/Judges_disp_matter_details/getSession/".$_SESSION[dcmis_user_idd];
        break;
    }

    case 90009:
    {
        $url="Copying/index.php/Judges_Matters_Contr";
        break;
    }

    case 90010:{
        $url="Copying/index.php/Dynamic_report";
        break;
    }
    case 90013:{
        $url="Copying/index.php/CourtMasterController/ropNotUploaded/".$_SESSION[dcmis_user_idd];
        break;
    }
    case 90014:{
        $url="Copying/index.php/Efiling/refiled_documents/".$_SESSION[dcmis_user_idd];
        break;
    }
    /*start external urls series from 999001 to 999999*/
    case 999001:{
        $is_external_url = "YES";
        $url = "https://10.25.:44434/digitization/reports/pending";
        break;
    }
    case 999002:{
        $is_external_url = "YES";
        //$url = "https://10.25:44434/shift_eligible_rop";
        $url = "https://10.2:44434/shift_eligible_rop";
        break;
    }
   case 999003:{
           $url="Copying/index.php/Efiling/loosedoc/".$_SESSION[dcmis_user_idd];
           break;
    }

    case 999005:{
        $url="Copying/index.php/Amicus_curiae/AllocateAC";
        break;
    }

    case 999006:{
        $url="Copying/index.php/Amicus_curiae/getSession";
        break;
    }

    case 999007:{
        $url="Copying/index.php/Sclsc/sclsc_refiled_documents/".$_SESSION[dcmis_user_idd];
        break;
    }

  case 999008:{
        $url="Copying/index.php/Efiling/pipreport/".$_SESSION[dcmis_user_idd];
        break;
    }

  case 999009:{
        $url="Copying/index.php/Report_IPD/";
        break;
    }

  case 90015:{
        $url="Copying/index.php/Mentioning_disposed";
        break;
    }

 case 90016:{
        $url="Copying/index.php/CourtMasterController/uploadNewOrder/".$_SESSION[dcmis_user_idd];
        break;
    }

 case 999010:{
?>
        <script type="text/javascript">
            window.open('<?php echo E_FILING_URL ?>/efiling_search', '_blank');
        </script>
<?php
}
  case 999011:{
        $is_external_url = "YES";
        ?>
        <script type="text/javascript">
            window.open('http://10.25/library', '_blank');
        </script>
<?php
        break;
    }
case 90017:{
     $url="Copying/index.php/Neutral_citation/NeutralCitation/".$_SESSION[dcmis_user_idd];
     break;
 }

  case 90018:{
      $url="Copying/index.php/Neutral_citation/get_NeutralCitation/".$_SESSION[dcmis_user_idd];
      break;
  }

case 90019:{
        $url="Copying/index.php/Scefm_matters/get_session/".$_SESSION[dcmis_user_idd];
        break;
    }



case 81874:{
        $url="Copying/index.php/CourtMasterController/changeJudgmentFlagModule/".$_SESSION[dcmis_user_idd];
        break;
    }

   /*    case 999003:{/var/www/html/sci/supreme_court/content.php
        $is_external_url = "YES";
        //$url = "https://10.25.:44434/shift_eligible_rop";
        $url = "http://10.40.186.14:8087/login/";
        break;
    }*/
    /*end external urls series from 999001 to 999999*/

    default:{
        $url=$content_url;
       //echo 'anshu default manu';
    }
}

$content_url=$url;

/*echo 'unique_id_processed_active='.$unique_id_processed_active.' content_url='.$content_url.' pageTitle='.$pageTitle.' old_smenu='.$old_smenu;
exit();*/

//require_once './includes/template.php';
?>
<style>

    .iFrameWrapper {
        position: relative;
        padding-bottom: 56.25%; /* 16:9 */
        padding-top: 5px;
        height: 0;
        /*background-color: #ffffff !important;*/
    }
    .iFrameWrapper iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: 0;
    }
</style>
<!--style="margin:72px 1px 0 304px;"-->
<section class="content" style="margin:0 0 0 0;" >
    <div class="iFrameWrapper">
        <?php
        if($is_external_url == "YES"){
            ?>
            <iframe src="<?php echo $content_url;?>" allowfullscreen></iframe>
            <?php
        }
        else{
           /* echo 'www unique_id_processed_active='.$unique_id_processed_active.' content_url='.$content_url.' pageTitle='.$pageTitle.' old_smenu='.$old_smenu;
            exit();*/
            ?>
            <iframe src="<?php echo base_url($content_url);?>" allowfullscreen></iframe>
            <?php
        }
        ?>
    </div>

    <!--<iframe src="<?php /*echo WEB_ROOT.$content;*/?>" style="margin: 0px; padding: 0px; border: none; width: 100%; height: 100%">
                </iframe>-->
</section>
