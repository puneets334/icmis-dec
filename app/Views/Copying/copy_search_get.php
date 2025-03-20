<?php if(!empty($result)){

$row = $result[0];
$disposed_flag = array('F', 'R', 'D', 'C', 'W'); 
$case_no='';
if ($row['reg_no'] != '') {
    $case_no = $row['reg_no'];
}
$case_no .= ' DNo. ' . substr($row['diary'], 0, -4) . '-' . substr($row['diary'], -4);
if ($row['case_status'] == 'P') {
    $case_status = 'Pending';
} else {
    $case_status = 'Disposed';
}

echo ">>".$row['id'].'>>'.$row['email'].'>>>'.$row['mobile'];

?>
    <div class="card mt-2">
        <div class="card-header bg-info  font-weight-bolder" style="color:black !important">Details</div>
        <div class="card-body">
            <div style="border-radius: 15px 15px 15px 15px;" class="p-2 m-1">
                <div class="row">
                        <div class="col-md-4">Application No.: <span class="font-weight-bold text-gray"><?= $application_request == 'application' ? $row['application_number_display'] : 'NA'; ?></span></div>
                        <div class="col-md-4">CRN: <span class="font-weight-bold text-gray"><?= $row['crn'] == '0' ? '' : $row['crn']; ?></span></div>
                        <div class="col-md-4">Date: <span class="font-weight-bold text-gray"><?= date("d-m-Y", strtotime($row['application_receipt'])); ?></span></div>
                </div>
                <div class="row">
                    <div class="col-md-12">Address: <span class="font-weight-bold text-gray"><?= $row['address']; ?></span></div>
                </div>
                <div class="row">
                    <div class="col-md-4">Source: <span class="font-weight-bold text-gray"><?= $row['description']; ?></span></div>
                    <div class="col-md-4">Applied By: <span class="font-weight-bold text-gray"><?php
                            if ($row['filed_by'] == 1) {
                                echo "AOR";
                            }
                            if ($row['filed_by'] == 2) {
                                echo "Party";
                            }
                            if ($row['filed_by'] == 3) {
                                echo "Appearing Counsel";
                            }
                            if ($row['filed_by'] == 4) {
                                echo "Third Party";
                            }
                            if ($row['filed_by'] == 6) {
                                echo "Authenticated By AOR";
                            }
                            ?></span></div>
                    <div class="col-md-4">Applicant Name: <span class="font-weight-bold text-gray"><?= $row['name']; ?></span></div>


                </div>
                <div class="row">
                    <div class="col-md-4">Application Status: <span class="font-weight-bold text-gray"><?= (in_array($row['application_status'], $disposed_flag)) ?  "Action Completed" : "Pending" ?></span></div>
                    <div class="col-md-4">Delivery Mode: <span class="font-weight-bold text-gray">
                    <?php
                            if ($row['delivery_mode'] == 1) {
                                echo "Post";
                                
                                if (!empty($row_barcode)) {
                                    if(is_array($postage_response)){?>
                                         <a href="#" onclick="mytrack_record()" id='myBtn'>Click to Track</a>
                                   <?php }

                                        $track_rpt_arr = array();
                                        foreach ($postage_response as $value) {
                                            array_push($track_rpt_arr, array(
                                                "DATE" => date('d-M-Y', strtotime($value['event_date'])), "TIME" => date('h:i A', strtotime($value['event_time'])),
                                                "EVENT" => $value['event_type'], "OFFICE" => $value['office']
                                            ));
                                        }

                                 
                                }
                             }

                            if ($row['delivery_mode'] == 2) {
                                echo "Counter";
                            }
                            if ($row['delivery_mode'] == 3) {
                                echo "Email";
                            }
                            ?></span>

                    </div>
                    <?php
                    if ($application_request == 'application') {
                    ?>
                        <div class="col-md-4">Fee: <span class="font-weight-bold text-gray">Rs. <?= $row['court_fee'] . " + Postal Rs. " . $row['postal_fee'] ?></span></div>
                    <?php } ?>
                </div>
                <div class="row">
                    <div class="col-md-12">Case No.: <span class="font-weight-bold text-gray"><?= $case_no; ?></span></div>
                </div>
                
            </div>
        </div>
    </div>

<?php }else{
     echo "No Record Found";
}
?>