<div class="col-12 m-0 p-0">
    <div class="list-group list-group-mine">
        <?php
        $session = session(); 
        if (!empty($requests)) : ?>
            <?php foreach ($requests as $srno => $row) :
            $case_no = "";
            if($row['reg_no_display'] != ''){
                $case_no = $row['reg_no_display'];
            }            
            $case_no .= ' DNo. '.substr($row['diary'], 0, -4).'-'.substr($row['diary'], -4);
            if($row['c_status'] == 'P'){
                $case_status = 'Pending';
            }
            else{
                $case_status = 'Disposed';
            }
            $documentResult=$copyRequestModel->getcopying_request_verify_documents($row['id']);
            if (count($documentResult) > 0 OR $row['allowed_request']=='party'): 
            ?>
                
                <div style="border-radius: 15px 15px 15px 15px;" id="row-crn_<?= $row['crn']; ?>" class="item_no list-group-item p-2 m-1" data-diary_no = "<?=$row['diary'];?>" data-crn="<?=$row['crn'];?>" data-mobile="<?=$row['mobile'];?>" data-email="<?=$row['email'];?>" data-case_status="<?=$row['c_status'];?>" data-applicant_name="<?=$row['name'];?>" data-copy_status="<?=$_POST['copy_status']; ?>" >
                <div class="row" >
                <nav class="navbar ">
                                <div class="col-md-2">
                                    <div  class="btn_tooltip pointer" onclick="call_tooltip('tooltip_<?= $row['crn']; ?>')">
                                <span class="rounded-circle bg-danger p-1" style="">
                                    <span class="p-1 text-white font-weight-bold">
                                       <?= $srno++; ?>
                                    </span></span>
                                    </div>
                                    <div id="tooltip_<?= $row['crn']; ?>" style="display:none; z-index: 1;" class="dropdown-menu">
                                    <?php
                                    
                                    //die;
                                    if($session->get('dcmis_section')==10 || $session->get('dcmis_user_idd') == 1) {
                                    ?>
                                        <a class="dropdown-item" href="#"><i class="fas fa-mobile-alt"></i> <?=$row['mobile']?></a>
                                        <a class="dropdown-item" href="#"><i class="fas fa-envelope-square"></i> <?=$row['email']?></a>
                                        <a class="dropdown-item" href="#"><i class="fas fa-map-marker-alt"></i> <?=$row['address']?></a>
                                        <a class="dropdown-item" href="#"><i class="fab fa-stripe-s"></i>ection : <?=$row['tentative_section']?></a>


                                        &nbsp;<button type="button" class="ml-3 p-0 btn inline request_to_avaialble" data-doc_action="list_DAA" data-diary_no="<?=$row['diary'];?>" title="Documents Available in ICMIS" ><i class="fas fa-file-alt" aria-hidden="true"></i> Documents Available in ICMIS</button>
                                        <br/>&nbsp;<button type="button" class="ml-3 p-0 btn inline request_to_avaialble" data-doc_action="uploaded_previous_pdf_files" data-application_id="<?=$row1['id'];?>" data-path="<?=$row1['path'];?>" title="Merge/Split Log" ><i class="fas fa-external-link-alt" aria-hidden="true"></i> Merge/Split Log</button>
                                    <?php } ?>
                                        <br>&nbsp;<button type="button" class="ml-3 p-0 btn inline request_to_avaialble"  data-doc_action="send_to_section_report" data-crn="<?=$row['crn'];?>" title="Send to section report" ><i class="fas fa-exchange-alt" aria-hidden="true"></i> Case Movement Report</button>
                                    </div>
                                </div>

                            <div class="col-md-6">CRN: <span class="font-weight-bold text-gray"><?= $row['crn']; ?></span></div>
                            <div class="col-md-4">Date: <span class="font-weight-bold text-gray"><?= date("d-m-Y", strtotime($row['application_receipt'])); ?></span></div>
                        </nav>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-2"><?= esc($srno + 1); ?></div>
                        <div class="col-md-6">CRN: <strong><?= esc($row['crn']); ?></strong></div>
                        <div class="col-md-4">Date: <strong><?= date("d-m-Y", strtotime($row['application_receipt'])); ?></strong></div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">Case Status: <strong class="<?= $row['c_status'] == 'P' ? 'text-success' : 'text-danger'; ?>"><?= $row['c_status'] == 'P' ? 'Pending' : 'Disposed'; ?></strong></div>
                    </div>
                    <div class="row" >
                        <div class="col-md-2"></div>


<!--                        <div class="col-md-5">Delivery: <span class="font-weight-bold text-gray"><?php
/*                                if($row['delivery_mode']==1){echo "Post";}
                                if($row['delivery_mode']==2){echo "Counter";}
                                if($row['delivery_mode']==3){echo "Email";}
                                */?></span>
                        </div>-->
                        <div class="col-md-10">Request Type: <span class="font-weight-bold text-gray"><?php
                        
                                if($row['allowed_request']=='appearing_counsel'){echo "Appearing Counsel";}
                                if($row['allowed_request']=='party'){echo "Party";}
                                if($row['allowed_request']=='request_to_available'){echo "Unavailable Doc";}
                                if($row['allowed_request']=='third_party'){echo "Third Party";}
                                ?></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-10">Case No.: <strong><?= esc($row['reg_no_display']); ?></strong></div>
                    </div>
                    <div class="row" >
                            <div class="col-md-2"></div>
                            <div class="col-md-10">Applicant Name: <span class="font-weight-bold text-gray"><?= $row['name']; ?></span>
                                <span class="ml-1 text-success font-weight-bold">
                        <?php
                        if($row['filed_by'] == 1){
                            echo "(AOR)";
                        }
                        if($row['filed_by'] == 6){
                            echo "(Auth. By AOR)";
                        }
                        if($row['filed_by'] == 2){
                            echo "(Party)";
                        }
                        if($row['filed_by'] == 3){
                            echo "(Arguing Counsel)";
                        }
                        if($row['filed_by'] == 4){
                            echo "(Third Party)";
                        }
                        ?>
                        </span>
                            </div>

                        </div>
                        <?php

if(($row['filed_by'] == 2 || $row['filed_by'] == 3 || $row['filed_by'] == 4) && $row['allowed_request']!='request_to_available' ){
    ?>
    <div class="row ml-1 pl-1">
        <div class="col-md-11"><u>User Verification:</u></div>
    </div>
    <?php
   //id proof, photo, video
   $userAssetsResult=$copyRequestModel->getUserAssets($row);    
   if (count($userAssetsResult) > 0){
    foreach ($userAssetsResult as $data_asset) {                              
                 ?>
                    <div class=" row ml-1 pl-1" >
                    <div class="col-md-12 d-flex justify-content-between">
                        <div>
                        <span style='cursor:pointer' class='inline uploaded_attachments text-primary' data-doc_action="view" data-mobile='<?=$row['mobile']?>' data-email='<?=$row['email']?>' data-attached_display_name='<?=$data_asset['asset_name'];?>' data-asset_type='<?=$data_asset['asset_type']?>' data-id_proof_masterid='<?=$data_asset['id_proof_type']?>' data-attached_path='<?= $data_asset['file_path']; ?>'  > <?=$data_asset['asset_type'] == 1 ? 'ID Proof '.$data_asset['id_name'] : $data_asset['asset_name']?>
                            <?=$data_asset['asset_type'] == 3 ? ': Random Text '.$data_asset['video_random_text'] : ''?>
                        </span>
                        </div>
                <?php
                 if($data_asset['verify_status'] == 1){//pending
                     ?>
                        <div class='attached_doc_action text-right inline' data-doc_id_action="<?=$data_asset['id'];?>">
                        <button type="button" class="p-0 btn btn-success inline uploaded_attachments" data-doc_action="accept" data-mobile='<?=$row['mobile']?>' data-email='<?=$row['email']?>' data-asset-table-id='<?=$data_asset['id'];?>' data-attached_display_name='<?=$data_asset['asset_name'];?>' data-asset_type='<?=$data_asset['asset_type']?>' data-attached_path='<?= $data_asset['file_path']; ?>' title="<?=$data_asset['asset_name'];?> Accept" ><i class="fa fa-check" aria-hidden="true"></i></button>
                        <button type="button" class="p-0 btn btn-danger inline uploaded_attachments" data-doc_action="reject" data-mobile='<?=$row['mobile']?>' data-email='<?=$row['email']?>' data-asset-table-id='<?=$data_asset['id'];?>' data-attached_display_name='<?=$data_asset['asset_name'];?>' data-asset_type='<?=$data_asset['asset_type']?>' data-attached_path='<?= $data_asset['file_path']; ?>' title="<?=$data_asset['asset_name'];?> Reject" ><i class="fa fa-times" aria-hidden="true"></i></button>
                        </div>
                        <?php
                 } else{
                     ?>
                        <div class='text-danger inline'>
                                <?php
                                if($data_asset['verify_status'] == 2){
                                    ?>
                                    <strong class="text-success">Success</strong>
                                    <?php
                                }
                                if($data_asset['verify_status'] == 3){
                                    ?>
                                    <strong class="text-danger">Rejected</strong>
                                    <?php
                                }
                                ?>
                            </div>
                     <?php
                 }
                 ?>                                     
                 </div>
                 </div>
            <?php
            if($data_asset['verify_remark'] != '' && $data_asset['verify_remark'] != null){
                echo "<div class='small text-muted text-right'>".$data_asset['verify_remark'] ."</div>";
            }

        }
    }
    //party, third party (affidavit), appearing_counsel
    if($row['filed_by'] == 2){
        $asset_type_flag = 5;//party
    }
    if($row['filed_by'] == 3){
        $asset_type_flag = 6;//appearing counsel
    }
    if($row['filed_by'] == 4){
        $asset_type_flag = 4;//affidavit
    }
    $getUserAssetsWithRelationResult=array();
    $getUserAssetsWithRelationResult=$copyRequestModel->getUserAssetsWithRelation($row);
    if (!empty($getUserAssetsWithRelationResult)) {
        foreach ($getUserAssetsWithRelationResult as $data_asset) {
                            
                 ?>
                <div class=" row ml-1 pl-1" >
                    <div class="col-md-12 d-flex justify-content-between">
                     <div>
                        <span style='cursor:pointer' class='text-primary inline uploaded_attachments' data-doc_action="view" data-attached_display_name='<?=$data_asset['asset_name'];?>' data-asset_type='<?=$data_asset['asset_type']?>' data-id_proof_masterid='<?=$data_asset['id_proof_type']?>' data-mobile='<?=$row['mobile']?>' data-email='<?=$row['email']?>' data-attached_path='<?= GET_SERVER_IP.$data_asset['file_path']; ?>'  > <?=$data_asset['asset_type'] == 1 ? 'ID Proof '.$data_asset['id_name'] : $data_asset['asset_name']?></span>
                     </div>
                <?php
                 if($data_asset['verify_status'] == 1){//not pending
                     ?>

                     <div class="attached_doc_action text-right inline" data-doc_id_action="<?=$data_asset['id'];?>">
                        <button type="button" class="p-0 btn btn-success inline uploaded_attachments" data-doc_action="accept" data-asset-table-id='<?=$data_asset['id'];?>' data-attached_display_name='<?=$data_asset['asset_name'];?>' data-asset_type='<?=$data_asset['asset_type']?>' data-mobile='<?=$row['mobile']?>' data-email='<?=$row['email']?>' data-attached_path='<?= $data_asset['file_path']; ?>' title="<?=$data_asset['asset_name'];?> Accept" ><i class="fa fa-check" aria-hidden="true"></i></button>
                        <button type="button" class="p-0 btn btn-danger inline uploaded_attachments" data-doc_action="reject" data-asset-table-id='<?=$data_asset['id'];?>' data-attached_display_name='<?=$data_asset['asset_name'];?>' data-asset_type='<?=$data_asset['asset_type']?>' data-mobile='<?=$row['mobile']?>' data-email='<?=$row['email']?>' data-attached_path='<?= $data_asset['file_path']; ?>' title="<?=$data_asset['asset_name'];?> Reject" ><i class="fa fa-times" aria-hidden="true"></i></button>
                     </div>
                        <?php
                 }
                 else{
                     ?>
                        <div class='text-danger inline'>
                            <?php
                            if($data_asset['verify_status'] == 2){
                                ?>
                                <strong class="text-success">Success</strong>
                                <?php
                            }
                            if($data_asset['verify_status'] == 3){
                                ?>
                                <strong class="text-danger">Rejected</strong>
                                <?php
                            }
                            ?>
                        </div>
                     <?php
                 }
                 ?>                                     
                    </div>
                 </div>                                         
                 <?php

            if($data_asset['verify_remark'] != '' && $data_asset['verify_remark'] != null){
                echo "<div class='small text-muted text-right'>".$data_asset['verify_remark'] ."</div>";
            }

        }
    }
}

foreach ($documentResult as $row1) {
    $data_recv_from_sec=$copyRequestModel->getSectionDetailRecievedAndSent($row1);
    
    
    if (!empty($data_recv_from_sec)) {
        $blink_me_class="blink_me";
        $show_text = "Received From ".$data_recv_from_sec['section_name']." On ".date("d-m-Y H:i:s", strtotime($data_recv_from_sec['from_section_sent_on']));
    }
    else{
        $blink_me_class = "";
        $show_text = "";
    }


    ?>
    <div class="row m-1 p-1 border-top" >
        <div class="col-md-6" id="splitRadio">
            <input type="radio" class="selectedRadio" name="<?=$row['diary'];?>" data-application_id="<?=$row1['id'];?>" data-crn="<?=$row['crn'];?>">
            <?= $row1['order_name']; ?>  <?= $row1['order_date'] != '1970-01-01 00:00:00' ? date("d-m-Y", strtotime($row1['order_date'])) : ''; ?>

        </div>
        <div class="col-md-3"> <?=$row1['order_type_remark']?> </div>
        <div class="col-md-3 float-right action_taken" data-crn_action="<?=$row['crn'];?>" data-application_id_action="<?=$row1['id'];?>">
        <?php

        if($row['allowed_request']=='appearing_counsel' OR $row['allowed_request']=='third_party'){

        ?>        
                <button type="button" class="p-0 btn btn-primary inline appearing_council" data-doc_action="view" data-application_id="<?=$row1['id'];?>" data-path="<?= 'http://'.$_SERVER['SERVER_ADDR'].'/'.$row1['path'];?>" title="View"><i class="fa fa-eye" aria-hidden="true"></i></button>
                <?php 
                if($row1['request_status'] == 'P'){
                ?>
                <button type="button" class="p-0 btn btn-success inline appearing_council" data-doc_action="accept" data-application_id="<?=$row1['id'];?>" data-path="<?=$row1['path'];?>" title="Accept"><i class="fa fa-check" aria-hidden="true"></i></button>
                <button type="button" class="p-0 btn btn-danger inline appearing_council" data-doc_action="reject" data-application_id="<?=$row1['id'];?>" data-path="<?=$row1['path'];?>" title="Reject"><i class="fa fa-times" aria-hidden="true"></i></button>
                <?php }
                else{
                    ?><div class='text-danger inline'><strong>D</strong></div>
                        <?php
                }
            }
        //if($row['allowed_request']=='request_to_available' AND ($_POST['copy_status'] == 'C' OR $_SESSION['dcmis_section'] == $row1['current_section'])){
        if($row['allowed_request']=='request_to_available' AND (in_array_any([10],$_POST['usersection']) OR $session->get('dcmis_section') == $row1['current_section'])){
             if($row1['path'] != null){
                 if(in_array_any([10],$_POST['usersection'])){
            //$_SERVER['SERVER_ADDR'] (view by http://172.17.0.5/) replaced to $_SERVER['SERVER_NAME'] (view by IP http://10.40.186.150/)
            ?>  <button type="button" class="p-0 btn btn-warning inline request_to_fee_clc_for_certification"  data-application_id="<?=$row1['id'];?>" data-request_status="<?=$row1['request_status'];?>" data-path='<?=$row1['path'];?>' title="Enter No. of documents for certification & un-certification and pages" ><i class="fas fa-calculator" aria-hidden="true"></i></button>
                     <?php } ?>

                 <!--<button type="button" class="p-0 btn btn-primary inline request_to_avaialble <?/*=$blink_me_class*/?>" data-doc_action="view" data-application_id="<?/*=$row1['id'];*/?>" data-path='<?/*='http://'.$_SERVER['SERVER_NAME'].'/'.$row1['path'];*/?>' title="View <?/*=' : '.$show_text*/?>"><i class="fa fa-eye" aria-hidden="true"></i></button>-->
                 <input type="hidden" id="data_crn_application_row_<?=$row['crn'].'_'.$row1['id'];?>" value="<?=$blink_me_class.'@@@ : '.$show_text;?>">
                 <span id="crn_application_row_<?=$row['crn'].'_'.$row1['id'];?>">
                    <button type="button" class="p-0 btn btn-primary inline request_to_avaialble <?=$blink_me_class?>" id="crn_application_row_button_<?=$row['crn'].'_'.$row1['id'];?>" data-action_type='pdf_split_merge' data-doc_action="view" data-path_file='<?=$row1['path'];?>' data-crn="<?=$row['crn'];?>" data-application_id="<?=$row1['id'];?>" data-path='<?='http://'.$_SERVER['SERVER_NAME'].'/'.$row1['path'];?>' title="View <?=' : '.$show_text?>"><i class="fa fa-eye" aria-hidden="true"></i></button>
                </span>


            <?php
             }
                if($row1['request_status'] == 'P'){
                    ?>
                    <button type="button" class="p-0 btn btn-info inline request_to_avaialble" data-doc_action="upload_copy" data-application_id="<?=$row1['id'];?>" data-path="<?=$row1['path'];?>" title="Upload" ><i class="fa fa-upload" aria-hidden="true"></i></button>
                    <?php                                   
                    //if($_POST['copy_status'] == 'C'){
                    if(in_array_any([10],$_POST['usersection'])){

                        if($row1['path'] != null){
                            ?>
                            <button type="button" class="p-0 btn btn-success inline request_to_avaialble" data-doc_action="accept" data-application_id="<?=$row1['id'];?>" data-path="<?=$row1['path']; ?>" title="Accept"><i class="fa fa-check" aria-hidden="true"></i></button>
                            <?php
                        }
                        ?>                                                
                            <button type="button" class="p-0 btn btn-danger inline request_to_avaialble" data-doc_action="reject_copy" data-application_id="<?=$row1['id'];?>" data-path="<?=$row1['path'];?>" title="Reject" ><i class="far fa-trash-alt"></i></button>
                        <?php
                    }
                ?>                                
            <button type="button" class="p-0 btn btn-primary inline request_to_avaialble" data-doc_action="sent_to_section_copy" data-application_id="<?=$row1['id'];?>" data-path="<?=$row1['path'];?>" title="Send to section" ><i class="fa fa-share" aria-hidden="true"></i></button>
            <?php }
            else{
                ?><div class='text-danger inline'><strong>D</strong></div><?php
            }
            } ?>
        
        </div>
    </div>
    <?php
    if($row1['reject_cause'] != '' && $row1['reject_cause'] != null){
        echo "<div class='small text-muted text-right'>".$row1['reject_cause'] ."</div>";
    }

}
?>
<?php

if(in_array_any( [10,71], $session->get('dcmis_multi_section_id') ) && ($row['allowed_request']=='appearing_counsel' || $row['allowed_request']=='party' || $row['allowed_request']=='request_to_available') && $_POST['copy_status'] != 'D')
{

    ?>
    <style>
        .dSpacePage table {
            word-wrap: break-word;
            table-layout:fixed;
            width: 100%;
        }
    </style>
    <div class="row p-1 col-12" >
    <div class="dspace_docs border-0 col-12 list-group-item p-0 m-1">
    <div class="rounded ml-3"
    style="cursor: pointer; text-align: center;background-color:darkgray;border-radius: 5px importaint; width:100%">
        <!--<div class="rounded ml-3" data-toggle="collapse" data-target="#collapse_<?=$row['crn'];?>"
             style="cursor: pointer; text-align: center;background-color:darkgray;border-radius: 5px importaint; width:100%">--><strong>Documents Available in the DSPACE</strong>
        </div>

        <div class="dSpacePage col-12 p-1 m-1 collapse" id="collapse_<?=$row['crn'];?>" >
        <?php
        $get_password_login_cookie='';
        $case_search_parameter_results=array();
        $case_search_parameter_results=$copyRequestModel->getDiaryDetail($row);
        
        $case_type_for_dspace_search='';  $response='';
        if (Count($case_search_parameter_results) > 0) {
           

            foreach ($case_search_parameter_results as $case_search_parameter_result) {
            //while ($case_search_parameter_result = $rs_case_search_parameter->fetch(PDO::FETCH_ASSOC)) {


                //echo $case_search_parameter_result['casetype_id'];

                switch ($case_search_parameter_result['casetype_id']) {
                    case "1":
                        $case_type_for_dspace_search="SPECIAL%20LEAVE%20PETITION%20(CIVIL)-SLPC";
                        break;
                    case "2":
                        $case_type_for_dspace_search="SPECIAL%20LEAVE%20PETITION%20(CRIMINAL)-SLPCR";
                        break;
                    case "3":
                        $case_type_for_dspace_search="CIVIL%20APPEAL-CA";
                        break;
                    case "4":
                        $case_type_for_dspace_search="CRIMINAL%20APPEAL-CRA";
                        break;
                    case "5":
                        $case_type_for_dspace_search="WRIT%20PETITION%20(CIVIL)-WPC";
                        //$case_type_for_dspace_search="WRIT%20PETITION%20(CIVIL)-WPC#WRIT PETITION-WP
                        break;
                    case "6":
                        $case_type_for_dspace_search="WRIT%20PETITION%20(CRIMINAL)-WPCR";
                        break;
                    case "7":
                        $case_type_for_dspace_search="TRANSFER%20PETITION%20(CIVIL)-TPC";
                        break;
                    case "8":
                        $case_type_for_dspace_search="TRANSFER%20PETITION%20(CRIMINAL)-TPCR";
                        break;
                    case "9": // REVIEW PETITION (CIVIL)
                        $case_type_for_dspace_search="";
                        break;
                    case "10": //REVIEW PETITION (CRIMINAL)
                        $case_type_for_dspace_search="";
                        break;
                    case "14": //REVIEW PETITION (CRIMINAL)
                        $case_type_for_dspace_search="CRIMINAL%20MISCELLANEOUS%20PETITION-CRLMP";
                        break;
                    case "11":
                        $case_type_for_dspace_search="TRANSFERRED%20CASE%20(CIVIL)-TCC";
                        break;
                    case "12":
                        $case_type_for_dspace_search="TRANSFERRED%20CASE%20(CRIMINAL)-TCCR";
                    case "13":
                        $case_type_for_dspace_search="CC%20SPECIAL%20LEAVE%20PETITION%20(CIVIL)-CCSLPC";
                        break;
                    case "17":
                        $case_type_for_dspace_search="ORIGINAL%20SUITE-OS";
                        break;
                    case "32":
                        $case_type_for_dspace_search="SUO%20MOTO%20WRIT%20PETITION(CIVIL)-SMWPC";
                        break;
                    case "33": //SUO MOTO WRIT PETITION(CRIMINAL)
                        $case_type_for_dspace_search="";
                        break;
                    case "34":
                        $case_type_for_dspace_search="SUO%20MOTO%20CONTEMPT%20PETITION(CIVIL)-SMCONPC";
                        break;
                    case "17":
                        $case_type_for_dspace_search="";
                        break;
                    case "18":
                        $case_type_for_dspace_search="";
                        break;
                    case "19":
                        $case_type_for_dspace_search="";
                        break;
                    case "27":
                        $case_type_for_dspace_search="REFERENCES-REF";
                        break;

                    default:
                        echo "Casetype not found";
                }
                //echo 'http://XXXX:91/dspace/DefaultController/search_cases_in_dspace4/'.$case_type_for_dspace_search.'/'.$case_search_parameter_result['case_no_from'].'/'.$case_search_parameter_result['case_year'];

              //echo 'http://XXXX:5008/index.php/Dspace/display_dspace4_bitstream_content/'.$case_type_for_dspace_search.'/'.$case_search_parameter_result['case_no_from'].'/'.$case_search_parameter_result['case_year'];
                //http://XXXX:5008/index.php/Dspace/search_cases_in_dspace4/SPECIAL%20LEAVE%20PETITION%20(CIVIL)-SLPC/9729/2015
                $response="";
                //$response=file_get_contents('http://XXXX:5008/index.php/Dspace/search_cases_in_dspace4/'.$case_type_for_dspace_search.'/'.$case_search_parameter_result['case_no_from'].'/'.$case_search_parameter_result['case_year'],false);
                if(empty($response))
                {
                    continue;
                }
                else
                {
                    echo $response;
                    break;

                }

            }

        }
        if(empty($response))
        {
            echo '<div class="alert alert-danger alert-dismissible"><strong>No Records Found.</strong></div>';
        }
        


   // echo "dc request".$row['diary'];
    ?>
        </div>

    </div>
    </div>
<?php }

?>
                </div>
<?php 
endif; 
endforeach; 
?>
        <?php else : ?>
            <div class="alert alert-danger alert-dismissible"><strong>No Records Found.</strong></div>
        <?php endif; ?>
    </div>
</div>