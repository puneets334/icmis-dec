

<?php
if((isset($receivedBySectionDetails) && sizeof($receivedBySectionDetails)>0 ) || (isset($initiatedReceivedBySectionDetails) && sizeof($initiatedReceivedBySectionDetails)>0 ))
{
?>
<div class="row"><button type="button" id="print" name="print" style="width:8%" onclick="printDiv('printable')" class="btn btn-block btn-warning">Print</button></div>
<div id="printable" class="dataTables_wrapper dt-bootstrap4 query_builder_wrapper">

    <table  id="tblActionTakenReport" class="table table-bordered table-striped datatable_report">

        <thead>
        <tr>
            <th width="4%">#</th>
            <th width="12%">Diary Number / Initiated Letter No.</th>
            <th width="10%">Sent To</th>
            <th width="15%">Postal Type, Number & Date</th>
            <th width="20%">Sender Name & Address</th>
            <th width="12%">Dispatched By/ Dispatched on</th>
            <th width="10%">Forwarded By/ Forwarded on</th>
            <th width="12%">Action Taken By/ Action Taken on</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $s_no=1;
        if(!empty($initiatedReceivedBySectionDetails))
        {
        foreach ($initiatedReceivedBySectionDetails as $forwardedCase)
        {

            ?>
            <tr>
                <td><?=$s_no?></td>
                <td>
                    <?php

                    if(!empty($forwardedCase['letter_no'])){
                        echo "<b>Internally Initiated - </b><br>Letter No: ".$forwardedCase['letter_no'];
                    }
                    ?>
                </td>
                <td>
                    <?=$forwardedCase['address_to']?>

                </td>
                <td>
                </td>
                <td>
                </td>
                <td>
                    <?php if($forwardedCase['is_forwarded']=='f'){ echo $forwardedCase['dispatched_by']; echo "&nbsp;On&nbsp;"; echo date("d-m-Y h:i:s A", strtotime($forwardedCase['dispatched_on']));}?>
                </td>
                <td>
                    <?php if($forwardedCase['is_forwarded']=='t'){ echo $forwardedCase['dispatched_by']; echo "&nbsp;On&nbsp;"; echo date("d-m-Y h:i:s A", strtotime($forwardedCase['dispatched_on']));}?>
                </td>
                <td>
                    <?=$forwardedCase['action_taken']?> By <?=$forwardedCase['action_taken_by']?>&nbsp;On&nbsp;<?=date("d-m-Y h:i:s A", strtotime($forwardedCase['action_taken_on']))?>
                    <?=$forwardedCase['return_reason']!=''?' Return Reason: '.$forwardedCase['return_reason']:''?>
                </td>
            </tr>
            <?php
            $s_no++;
        }
        }
        if(!empty($receivedBySectionDetails))
        {
        foreach ($receivedBySectionDetails as $case)
        {
            ?>
            <tr>
                <td><?=$s_no?></td>
                <td><?=$case['diary']?></td>
                <td>
                    <?=$case['address_to']?>
                    <?php /*if(!empty($case['judgename'])){
                            echo $case['judgename'];
                        }
                        elseif (!empty($case['officer_name'])){
                            echo $case['officer_name'];
                        }
                        else{
                            echo $case['section_name'];
                        }
                        */?>
                </td>
                <td><?php
                    echo $case['postal_type'].'&nbsp;'.$case['postal_number'].'&nbsp;'.date("d-m-Y", strtotime($case['postal_date']));
                    ?>
                </td>
                <td><?php
                    echo $case['sender_name'].'&nbsp;'.$case['address'];
                    ?>
                </td>
                <?php
                $diarynumber="";
                if(!empty($case['diary_number'])){
                    $diarynumber=$case['diary_number'];
                    $diarynumber="Diary No. ".substr($diarynumber, 0, -4)."/".substr($diarynumber, -4)."<br/>".$case['reg_no_display'];;
                }
                ?>
                <!--<td><?/*=$diarynumber;*/?></td>-->
                <td>
                    <?php if($case['is_forwarded']=='f'){ echo $case['dispatched_by']; echo "&nbsp;On&nbsp;"; echo date("d-m-Y h:i:s A", strtotime($case['dispatched_on']));}?>
                </td>
                <td>
                    <?php if($case['is_forwarded']=='t'){ echo $case['dispatched_by']; echo "&nbsp;On&nbsp;"; echo date("d-m-Y h:i:s A", strtotime($case['dispatched_on']));}?>
                </td>
                <td>
                    <?=$case['action_taken']?> By <?=$case['action_taken_by']?>&nbsp;On&nbsp;<?=date("d-m-Y h:i:s A", strtotime($case['action_taken_on']))?>
                    <?=$case['return_reason']!=''?' Return Reason: '.$case['return_reason']:''?>
                </td>
            </tr>
            <?php
            $s_no++;
        }
        }
        ?>
        </tbody>
    </table>
</div>
    <?php
}
else{
    ?>
    <div class="form-group col-sm-12">
        <label class="text-danger">&nbsp;No Record Found!!</label>
    </div>

<?php
}
?>

<script>

    $(function () {
        $(".datatable_report").DataTable({
            "responsive": true, "lengthChange": false, "autoWidth": false,
            "buttons": ["copy", "csv", "excel",{extend: 'pdfHtml5',orientation: 'landscape',pageSize: 'LEGAL' },
                { extend: 'colvis',text: 'Show/Hide'}],"bProcessing": true,"extend": 'colvis',"text": 'Show/Hide'
        }).buttons().container().appendTo('.query_builder_wrapper .col-md-6:eq(0)');

    });

    function printDiv(printable) {
        // alert(printable);return false;
        var printContents = document.getElementById('printable').innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }

</script>