<div id='report_result'>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="text-center my-3">
                    <button class="btn btn-primary" id="prnntCoverButton">Print</button>
                </div>
                <div id="prnntSection" contenteditable="false" class="container border p-4">
                    <!-- Header Section -->
                    <div class="text-center mb-4">
                        <h4>IN THE SUPREME COURT OF INDIA</h4>
                        <p><b>(<?= isset($fileHeading) ? $fileHeading : 'Appellate Jurisdiction'; ?>)</b></p>
                    </div>
                    <?php

                        $lower_court = lower_court_conct($diary_no); 
                        //pr($lower_court);
                        $lower_case_no = '';
                        $bench = '';
                        $chk_di_bn = '';
                        $state = '';
                        $chk_state = '';
                        for ($index1 = 0; $index1 < count($lower_court); $index1++) {
                            $skey = $lower_court[$index1][3];
                            $lct_caseno = $lower_court[$index1][4];
                            $lct_caseyear = $lower_court[$index1][5];
                            if ($lower_case_no == '') {
                                $lower_case_no = ' dated <b>' . date('d-m-Y', strtotime($lower_court[$index1][0])) . '</b> in <b>' . $skey . '-' . $lct_caseno . '-' . $lct_caseyear . '</b>';
                                $chk_di_bn = $lower_court[$index1][2];
                                $bench = trim($lower_court[$index1][2]);
                                $bench = rtrim($bench, ',');
                                $chk_state = $state = $lower_court[$index1][1];
                            } else {
                                $lower_case_no = $lower_case_no . ', dated <b>' . date('d-m-Y', strtotime($lower_court[$index1][0])) . '</b> in <b>' . $skey . '-' . $lct_caseno . '-' . $lct_caseyear . '</b>';
                                if ($chk_di_bn != $lower_court[$index1][2]) {
                                    $bench = $bench . ', ' . trim($lower_court[$index1][2]);
                                    $bench = $bench . ', ' . rtrim($bench, ',');
                                }
                                if ($chk_state != $lower_court[$index1][1])
                                    $state = $state . ', ' . $lower_court[$index1][1];
                            }
                        }
                       
                      ?>
                    <!-- Lower Court Data -->
                    <div class="mb-4 table-responsive">
                        <table class="table table-bordered">
                            <!-- Impugned Order Section -->
                            <tr>
                                <td colspan="4">
                                    <p>
                                        <?=
                                        
                                        $filing_date   = '';
                                        $filing_date = date('dS F, Y', strtotime(get_diary_rec_date($diary_no)));
                                        echo $filing_date;
                                        ?>
                                        Against the impugned order dated <b><?= isset($impugnedOrderDate) && !empty($impugnedOrderDate) ? $impugnedOrderDate : 'N/A';?></b> 
                                        <b><?//= isset($caseInfo) && !empty($caseInfo) ? $caseInfo : 'N/A';?> </b> 
                                    </p>
                                </td>
                            </tr>
                            <!-- Bench and State Info -->
                            <tr>
                                <td width="20%">Bench at:</td>
                                <td colspan="3"><b><?php echo $bench; ?></b></td>
                            </tr>
                            <tr>
                                <td>State:</td>
                                <td colspan="3"><b><?php echo $state; ?></b></b></td>
                            </tr>
                        </table>
                    </div>

                    <!-- Case Information Section -->
                    <div class="mb-4">
                        <table class="table table-bordered">
                            <tr>
                                <td>Diary No:</td>
                                <td width="30%"><b><?php echo $dn . '-' . $dyr; ?></b></td>
                                <td width="10%">Section:</td>
                                <?php
                                $getSecCode = '';
                                $dacode = $r_get_da_sec['dacode']; 

                                if ($dacode != 0) {
                                    $getSecCode = get_da_sec_code($dacode, 'users');
                                } else {
                                    $section_id = $r_get_da_sec['section_id'];
                                    $getSecCode = get_da_sec_code($section_id, 'usersection');
                                }
                                ?>


                                <td><b style="font-size: 20px"><?php echo $getSecCode; ?></b></td>
                            </tr>
                            <tr>
                                <td>Filed on:<?php ?></td>
                                <td>
                                    <b>
                                        <?php
                                        echo isset($r_get_da_sec['diary_no_rec_date']) && !is_null($r_get_da_sec['diary_no_rec_date'])
                                            ? date('d-m-Y', strtotime($r_get_da_sec['diary_no_rec_date']))
                                            : 'N/A';
                                        ?>
                                    </b>
                                </td>
                                <td>Regd. on:</td>
                                <td>
                                    <?php if (isset($r_get_da_sec['fil_dt']) && $r_get_da_sec['fil_dt'] != '0000-00-00 00:00:00') {
                                        echo date('d-m-Y', strtotime($r_get_da_sec['fil_dt']));
                                    } ?>

                            </tr>
                        </table>
                    </div>

                    <!-- Case Name and Number -->
                    <div class="text-center mb-4">
                        <h5>
                            <u>
                                <b>
                                <div style="font-size: 20px;margin-top: 20px;margin-top: 50px">
                                    <?php
                                        $type ='H';
                                        $getCaseDetailsHeading = get_case_detailsNew($diary_no, $dn, $dyr, $type);
                                        //pr($getCaseDetailsHeading);
                                        if(!empty($getCaseDetailsHeading)){
                                            echo $getCaseDetailsHeading;
                                        }
                                    ?>
                                </div>
                                <?php if ($r_get_da_sec['casetype_id'] == '1' || $r_get_da_sec['casetype_id'] == '2') {
                                        $c_cri = '';
                                        if ($r_get_da_sec['casetype_id'] == '1')
                                            $c_cri = "CIVIL APPEAL NO. ";
                                        else  if ($r_get_da_sec['casetype_id'] == '2')
                                            $c_cri = "CRIMINAL APPEAL NO. ";
                                    ?>
                                     <?php
                                        } else if ($r_get_da_sec['casetype_id'] == '6' || $r_get_da_sec['casetype_id'] == '5') {
                                        ?>
                                            <div>
                                                (Under Article 32 of the Constitution of India for the enforcement of fundamental right)
                                            </div>
                                        <?php
                                        } else  if ($r_get_da_sec['casetype_id'] == '11' || $r_get_da_sec['casetype_id'] == '12') {
                                        ?>

                                <?php 
                                    $lower_court = lower_court_conct($diary_no);
                                    for ($index1 = 0; $index1 < count($lower_court); $index1++) {
                                        $skey = $lower_court[$index1][3];
                                        $lct_caseno = $lower_court[$index1][4];
                                        $lct_caseyear = $lower_court[$index1][5];
                                    ?>
                                        <div style="font-size: 20px">
                                            ARISING OUT OF
                                        </div>
                                        <div style="font-size: 20px">
                                            <?php echo $skey ?> </b> <?php echo $lct_caseno; ?>/<?php  $lct_caseyear; ?>
                                        </div>
                               <?php 
                              }
                            }
                           ?>
                                    <?php
                                    echo $r_get_da_sec['pet_name'];
                                    $pno = '';

                                    if ($r_get_da_sec['pno'] == 2) {
                                        $pno = " AND ANOTHER";
                                    } else if ($r_get_da_sec['pno'] > 2) {
                                        $pno = " AND OTHERS";
                                    }

                                    echo $pno;
                                    ?>

                                </b>
                                VERSUS
                                <b><?php
                                    echo $r_get_da_sec['res_name'];
                                    $rno = '';
                                    if ($r_get_da_sec['rno'] == 2)
                                        $rno = " AND ANOTHER";
                                    else if ($r_get_da_sec['rno'] > 2)
                                        $rno = " AND OTHERS";
                                    echo $rno;
                                    ?>
                                </b>
                            </u>
                        </h5>
                    </div>
                    <div style="text-align: left">
                        <table>
                            <tr>
                                <td>
                                <?php
                                    $get_main_case = get_main_case_af_verify($diary_no);

                                    // Check if $get_main_case is not blank and contains 'conn_key'
                                    if (!empty($get_main_case)) {
                                        $data = $get_main_case['conn_key'];
                                        $lower_court2 = get_case_details($data);
                                    } else {
                                        $lower_court2 = null; 
                                    }
                                ?>

                                    Tagged with:
                                    <b><?php
                                        if (!empty($lower_court2)) {
                                         if ($lower_court2[7] != '') { ?> <?php echo $lower_court2[7] . ' ' . substr($lower_court2[0], 3) . '/' . $lower_court2[1]; ?> <?php } else {
                                        echo    substr($get_main_case, 0, -4) . '-' . substr($get_main_case, -4);
                                        } }  ?>
                                    </b>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <?php
                                    $get_rgo = get_rgo_default($diary_no); 
                                    if (!empty($get_rgo)) {
                                        $r_rgo = $get_rgo['fil_no2'];
                                        $lower_court1 = get_case_details($r_rgo); //pr($lower_court1);
                                    }

                                    // Check if $lower_court1 is set and not empty
                                    if (!empty($lower_court1)) { ?>
                                        List after disposal of: 
                                        <b>
                                            <?php 
                                            if (!empty($lower_court1[7])) { 
                                                // Display formatted data from $lower_court1
                                                echo $lower_court1[7] . ' ' . substr($lower_court1[0], 3) . '/' . $lower_court1[1]; 
                                            } else { 
                                                // Fallback if $lower_court1[7] is empty
                                                echo substr($r_rgo, 0, -4) . '-' . substr($r_rgo, -4); 
                                            } 
                                            ?>
                                        </b>
                                    <?php } ?>
                                </td>

                            </tr>
                        </table>
                    </div>
                    <div style="margin-top: 15px">
                        <?php
                            // Fetch document details
                            $docdetails = get_docdetails($diary_no);

                            // Check if the result is a valid array
                            if (!empty($docdetails)) {
                                // If it's a single record, wrap it in an array for uniform processing
                                if (isset($docdetails['docnum'])) {
                                    $docdetails = [$docdetails]; // Wrap the single record in an array
                                }
                            }
                        ?>
                        <table>
                            <?php
                            // Loop through the document details (whether one or multiple)
                            if (!empty($docdetails)) {
                                $sno = 1;
                                foreach ($docdetails as $row) { // Loop through each document
                            ?>
                                <tr>
                                    <td width='10%'>
                                        <?php if ($sno != 1) { ?>
                                            <!-- Logic for other rows, if needed -->
                                        <?php } ?>
                                        <?php echo $fileHeading; ?>
                                    </td>
                                    <td>
                                        <b><?php echo $row['docnum']; ?>/<?php echo $row['docyear']; ?></b> :
                                        <b><?php echo $row['docdesc'] . ' [' . $row['iastat'] . ']'; ?></b>
                                    </td>
                                </tr>
                            <?php
                                    $sno++; // Increment serial number
                                }
                            }
                            ?>
                        </table>
                    </div>


                    <!-- Document Preservation Note -->
                    <div class="text-center mb-4">
                        <h5>PART 1/2</h5>
                        <p>(This file must be preserved forever)</p>
                    </div>


                    <!-- Document Details -->
                    <div class="mb-4">
                        <table class="table">
                            <!-- Loop through document details here -->
                             <?php
                             if(!empty($ia_court_details)){
                                foreach($ia_court_details as $ia_court_row){                              
                              ?>
                            <tr>
                                <td width="10%">I.A.No.</td>
                                <td><b><?php echo $ia_court_row['docnum'].'/'.$ia_court_row['docyear'].' : '.$ia_court_row['docdesc'].' ['.$ia_court_row['iastat'].']'; ?></b></td>
                            </tr>
                            <?php } } ?>
                        </table>
                    </div>

                    <!-- Judgement and Consignment Dates -->
                    <div class="mb-4">
                        <table class="table table-bordered">
                            <tr>
                                <td>Date of Judgement:</td>
                                <td>Date of Consignment:</td>
                            </tr>
                        </table>
                    </div>
                    <div style="clear: both">
                        <table width='100%'>
                            <tr>
                                <td style="border-right: 1px solid black;vertical-align: top">
                                    <?php
                                        $pet_adv = get_pet_adv($diary_no); //pr($pet_adv);
                                    ?>
                                     <table>
                                        <tr>
                                            <td><u>Advocate for the Petitioner(s)/Appellant(s) (AOR Code):</u></td>
                                        </tr>

                                        <?php
                                        // Loop through the document details (whether one or multiple)
                                        if (!empty($pet_adv)) {
                                            $sno = 1;
                                            foreach ($pet_adv as $row) { // Loop through each document
                                        ?>
                                            <tr>
                                                <td width='10%'>
                                                    <?php if ($sno != 1) { ?>
                                                    <?php } ?>
                                                
                                                </td>
                                                <td>
                                                    <b><?php echo  $row['name'] . '(' . $row['aor_code'] . ')'; ?></b>
                                                </td>
                                            </tr>
                                        <?php
                                                $sno++; // Increment serial number
                                            }
                                        }
                                        ?>
                                        
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <!-- Advocates Section -->
                    <div class="row">
                        <div class="col-md-6 border-right">
                            <h6><u>Advocate for the Petitioner(s)/Appellant(s) (AOR Code):</u></h6>
                            <!-- Loop to display petitioner's advocates -->
                        </div>
                        <div class="col-md-6">
                            <h6><u>Advocate for the Respondent(s) (AOR Code):</u></h6>
                            <?php 
                            if(!empty($aor_cor_details)){
                                foreach($aor_cor_details as $aor_cor_row){
                                    echo $aor_cor_row['name'].'('.$aor_cor_row['aor_code'].')'. " ";
                                    if(!empty($aor_cor_row['tot_pet'])){
                                        echo "[" .trim($aor_cor_row['tot_pet'], '[]')."]";
                                    }
                                    
                                    echo "<br/>";
                                    
                                }
                            }

                            ?>
                            Ex parte against Respondent No(s):<br/>
                            Advocate for Caveator(AOR Code):<br/>
                        </div>
                        <div class="col-md-12">
                            <div style="font-size: 20px;margin-bottom: 5px;">
                                <?php
                                    $type ='AF';
                                    $getCaseDetailsHeading = get_case_detailsNew($diary_no, $dn, $dyr, $type);
                                    if(!empty($getCaseDetailsHeading)){
                                        echo $getCaseDetailsHeading;
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                   
                </div>
            </div>
        </div>
    </section>
    <script src="<?php echo base_url('plugins/jquery-validation/jquery.validate.js'); ?>"></script>
    <script src="<?php echo base_url('plugins/jquery-validation/additional-methods.js'); ?>"></script>
</div>
<script>
    $(document).on("click","#prnntCoverButton",function() {
        var prtContent = $("#prnntSection").html();
        var temp_str = prtContent;
        var mywindow = window.open('', '', 'left=100,top=0,align=center,width=800,height=1200,menubar=1,toolbar=1,scrollbars=1,status=1,cellspacing=5, cellpadding=5');
        var is_chrome = Boolean(mywindow.chrome);
        mywindow.document.write(prtContent);
        if (is_chrome) {
            setTimeout(function () { // wait until all resources loaded
                mywindow.document.close(); // necessary for IE >= 10
                mywindow.focus(); // necessary for IE >= 10
                mywindow.print();  // change window to winPrint
               
            }, 20);
        }
        else {
            mywindow.document.close(); // necessary for IE >= 10
            mywindow.focus(); // necessary for IE >= 10
            mywindow.print();
        }
    });

</script>