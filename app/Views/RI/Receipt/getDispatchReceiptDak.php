<?php
if (!empty($receiptData)) {
?>
    <div class="form-group col-sm-6 pull-right">
        <label>&nbsp;</label>
        <button type="button" id="btnDispatchTop" name="btnDispatch" class="btn btn-primary pull-right" onclick="doDispatch();"><i class="fa fa-fw fa-download"></i>&nbsp;Dispatch Dak</button>
    </div>
    <!--<table id="reportTable1" class="table table-striped table-hover">-->
    <div class="table-responsive">
        <table id="tblDispatchDak" class="table table-striped custom-table">
            <thead>
                <tr>
                    <th width="4%">#</th>
                    <th width="8%">Diary Number</th>
                    <th width="10%">Sent To</th>
                    <th width="15%">Postal Type, Number & Date</th>
                    <th width="20%">Sender Name & Address</th>
                    <th width="10%"><label><input type="checkbox" id="allCheck" name="allCheck" onclick="selectallMe()">Select All</label></th>
                </tr>

            </thead>
            <tbody>
                <?php
                $s_no = 1;
                foreach ($receiptData as $case) {
                ?>
                    <tr>
                        <td><?= $s_no ?></td>
                        <td><?= $case['diary'] ?></td>
                        <td>
                            <?= $case['address_to'] ?>
                        </td>
                        <td>
                            <?php
                            echo $case['postal_type'] . '&nbsp;' . $case['postal_number'] . '&nbsp;';

                            if (!empty($case['postal_date'])) {
                                echo date("d-m-Y", strtotime($case['postal_date']));
                            } else {
                                echo "N/A"; // Or any default message
                            }
                            ?>
                        </td>
                        <td><?php
                            echo $case['sender_name'] . '&nbsp;' . $case['address'];
                            ?>
                        </td>
                        <?php
                        $diarynumber = "";
                        if (!empty($case['diary_number'])) {
                            $diarynumber = $case['diary_number'];
                            $diarynumber = "Diary No. " . substr($diarynumber, 0, -4) . "/" . substr($diarynumber, -4) . "<br/>" . $case['reg_no_display'];;
                        }
                        ?>

                        <td>
                            <?php if (!empty($case['dispatched_on']) && empty($case['action_taken'])) { ?>
                                <?= $case['dispatched_by'] ?>&nbsp;On&nbsp;<?= date("d-m-Y h:i:s A", strtotime($case['dispatched_on'])) ?>
                            <?php } else { ?>
                                <input type="checkbox" id="daks" name="daks[]" value="<?= $case['id'] ?>">
                            <?php } ?>
                        </td>

                    </tr>
                <?php
                    $s_no++;
                }
                ?>
            </tbody>
        </table>
    </div>
<?php
} else {
?>
    <br>
    <div class="form-group col-sm-12">
        <h4 class="text-danger">&nbsp;No Record Found!!</h4>
    </div>

<?php
}
?>

<script>
    $(function() {
        $("#tblDispatchDak").DataTable({
            "responsive": true,
            "dom": 'Bfrtip',
            "lengthChange": false,
            "autoWidth": false,
            "buttons": [
                // {
                //     extend: 'csv',
                //     title: 'Dispatch to Officer/Section'
                // },
                // {
                //     extend: 'excel',
                //     title: 'Dispatch to Officer/Section'
                // },
                {
                    extend: 'print',
                    title: 'Dispatch to Officer/Section'
                },
                {
                    extend: 'pdfHtml5',
                    orientation: 'landscape',
                    pageSize: 'LEGAL',
                    autoPrint: true,
                    title: 'Dispatch to Officer/Section'
                }
                // ,
                // {
                //     extend: 'colvis',
                //     text: 'Show/Hide'
                // }
            ],
            "bProcessing": true,
            //"extend": 'colvis',
           // "text": 'Show/Hide'
        }).buttons().container().appendTo('.query_builder_wrapper .col-md-6:eq(0)');
    });
   
</script>