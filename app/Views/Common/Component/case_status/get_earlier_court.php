<div width="100%">
    <?php if(!empty($earlier_court)):?>
    <table  border="0" align="left" width="100%" id="lowercourtdata" class="table table-bordered table-striped">
        <thead><tr>
            <th>
                S.No.
            </th>
            <th>
                Court
            </th>
            <th>
                Agency State
            </th>
            <th>
                Agency Code
            </th>
            <th>
                Case No.
            </th>
            <th>
                Order Date
            </th>
            <th>
                CNR No. /
                Designation
            </th>
            <th>
                Judge1/ Judge2/ Judge3
            </th>
            <th>
                Police Station
            </th>
            <th>
                Crime No./ Year
            </th>
            <th>
                Authority / Organisation / Impugned Order No.
            </th>
            <th>
                Judgement Challanged
            </th>
            <th>
                Judgement Type
            </th>
            <th>
                Judgement Covered in
            </th>
            <th>
                Vehicle Number
            </th>
            <th>
                Reference court / State / District / No.
            </th>
            <th>
                Relied Upon court / State / District / No.
            </th>
            <th>
                Transfer To State / District / No.
            </th>
            <th>
                Government Notification State / No. / Date
            </th>
        </tr>
        </thead>
        <tbody>
        <?php
        $sn = 0; //print_r($result);
        foreach ($earlier_court as $lower_court_details) {
        $cnr_designation = "";
        $case_no = $lower_court_details['type_sname'];
        if ($lower_court_details['lct_casetype'] == 50) {
            $case_no = $case_no . "WNN";
        }
        if ($lower_court_details['lct_casetype'] == 51) {
            $case_no = $case_no . "ARN";
        }
        $case_no = $case_no . "-" . $lower_court_details['lct_caseno'] . "-" . $lower_court_details['lct_caseyear'];

        if ($lower_court_details['cnr_no'] != "") {
            $cnr_designation = $lower_court_details['cnr_no'] . '/';
        }

        $cnr_designation = $cnr_designation . $lower_court_details['post_name'];

        if ($lower_court_details['full_interim_flag'] == 'I')
            $full_interim_flag = 'Interim';
        else if ($lower_court_details['full_interim_flag'] == 'F')
            $full_interim_flag = 'Final';
        else
            $full_interim_flag = '-';

        if ($lower_court_details['ct_code'] == '4')
            $court_name = "Supreme Court";
        else if ($lower_court_details['ct_code'] == '1')
            $court_name = "High Court";
        else if ($lower_court_details['ct_code'] == '3')
            $court_name = "District Court";
        else if ($lower_court_details['ct_code'] == '2')
            $court_name = "Other";
        else if ($lower_court_details['ct_code'] == '5')
            $court_name = "State Agency";

        $cno = $lower_court_details['lct_caseno'];
        $cy = $lower_court_details['lct_caseyear'];
        ++$sn;

        ?>
        <tr>
            <td><?php echo $sn;?>
            </td>
            <td><?php echo $court_name ?? '';?></td>
            <td><?php echo $lower_court_details['name'] ?></td>
            <td><?php echo $lower_court_details['agency_name'] ?></td>
            <td><?php echo $case_no?></td>
            <td><?php  if(!empty($lower_court_details['lct_dec_dt'])) echo date('d-m-Y',strtotime($lower_court_details['lct_dec_dt']));?></td>
            <td><?php echo $cnr_designation ?></td><td><?php
                if (!empty($judges_details)) {
                    if (!empty($judges_details[$lower_court_details['lower_court_id']])) {
                        $judgesArray = $judges_details[$lower_court_details['lower_court_id']];
                        foreach ($judgesArray as $jud) {
                            echo $judge_name = $jud['judge_name'] . '<br>';
                        }
                    } else {
                        echo '-';
                    }
                }
                ?></td>
            <td><?php echo $lower_court_details['policestndesc'] ?></td>
            <td> <?php echo $lower_court_details['crimeno'] . '/' . $lower_court_details['crimeyear'] ?>
            </td>
            <td>-//</td><td><?php echo $lower_court_details['is_order_challenged'] == 'Y' ? 'Yes' : 'No'; ?></td>
            <td><?php echo $full_interim_flag ?> </td>
            <td><?php echo $lower_court_details['judgement_covered_in'] ?></td>
            <td>
            <?php echo $lower_court_details['code'] . ' ' . $lower_court_details['vehicle_no'] ?>
           
            </td>
            <td><?php
                if (!empty($all_ref_details[$lower_court_details['lower_court_id']])) {
                    $ref_data = $all_ref_details[$lower_court_details['lower_court_id']];
                    // print_r($all_ref_details[$lower_court_details['lower_court_id']]);
                    echo $ref_data['court_name'] . '/' . $ref_data['name'] . '/' . $ref_data['reference_name'] . '/' . $ref_data['case_name'];
                } else {
                    echo ' / - ' . ' / - ' . ' / - ' . ' / -';
                }
                ?></td>
            <td><?php
                if (!empty($all_relied_details[$lower_court_details['lower_court_id']])) {
                    $relied_data = $all_relied_details[$lower_court_details['lower_court_id']];
                    // print_r($all_ref_details[$lower_court_details['lower_court_id']]);
                    echo $relied_data['court_name'] . '/' . $relied_data['name'] . '/' . $relied_data['reference_name'] . '/' . $relied_data['case_name'];
                } else {
                    echo ' / - ' . ' / - ' . ' / - ' . ' / -';
                }
                ?></td>
            <td><?php
                if (!empty($all_transfer_details[$lower_court_details['lower_court_id']])) {
                    $transfer_data = $all_transfer_details[$lower_court_details['lower_court_id']];
                    // print_r($all_ref_details[$lower_court_details['lower_court_id']]);
                    echo $transfer_data['court_name'] . '/' . $transfer_data['name'] . '/' . $transfer_data['reference_name'] . '/' . $transfer_data['case_name'];
                } else {
                    echo ' / - ' . ' / - ' . ' / - ' . ' / -';
                }
                ?></td>
            <td><?php
                if (!empty($all_gov_not_details[$lower_court_details['lower_court_id']])) {
                    $govt_data = $all_gov_not_details[$lower_court_details['lower_court_id']];
                    // print_r($all_ref_details[$lower_court_details['lower_court_id']]);
                    $g_n_date = date_create($govt_data['gov_not_date']);
                    $govt_data['gov_not_date'] = date_format($g_n_date, "d-m-Y");

                    echo $govt_data['name'] . '/' . $govt_data['case_name'] . '/' . $govt_data['gov_not_date'];
                } else {
                    echo ' / - ' . ' / - ' . ' / - ' . ' / -';
                }
                ?></td>
        </tr>
        <?php }?>
        </thead></table>
<?php endif;?>
</div>
<script>
    $(function() {
        $("#lowercourtdata").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["csv", "excel"]
        }).buttons().container().appendTo('#lowercourtdata_wrapper .col-md-6:eq(0)');

    });
</script>