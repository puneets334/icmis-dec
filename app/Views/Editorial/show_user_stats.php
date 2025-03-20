
                            <?php

                            if (!empty($list_stats)) {
                                foreach ($list_stats as $result) {
                                    $name = $result['name'];
                                    $empid = $result['empid'];
                                    $desig = $result['type_name'];
                                }
                                if ($userrole == 1) {
                                    if ($from_date != '' && $from_date != '1970-01-01' && $to_date != '' && $to_date != '1970-01-01')
                                        $heading = "Statistics of gist Uploaded by " . $name . " [" . $empid . "], " . $desig . " between " . date('d-m-Y', strtotime($from_date)) . " and " . date('d-m-Y', strtotime($to_date));
                                    else if ($from_date == $to_date)
                                        $heading = "Statistics of gist Uploaded by " . $name . " [" . $empid . "]," . $desig . " and are pending for Verification";
                                } else {
                                    if ($from_date != '' && $from_date != '1970-01-01' && $to_date != '' && $to_date != '1970-01-01')
                                        $heading = "User wise Statistics of gist Uploaded between " . date('d-m-Y', strtotime($from_date)) . " and " . date('d-m-Y', strtotime($to_date));
                                    else if ($from_date == $to_date)
                                        $heading = "User wise Statistics of gist Uploaded and are pending for Verification";
                                }
                                ?>

                    <div><button type="button" id="print" name="print" style="width:10%" onclick="printDiv('printable')" class="btn btn-block btn-warning">Print</button></div>
                    <div id="printable" class="dataTables_wrapper dt-bootstrap4 query_builder_wrapper">
                        <br> <div style="text-align:center; font-weight:bold; font-size:24px;">  <?php echo $heading; ?> </div><br>
                        <table id="userwisereport" class="table table-bordered table-striped datatable_report">
                                        <thead>

                                        <th>S.No.</th>
                                        <th>Name</th>
                                        <th>Total Uploaded</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $i = 0;
                                        $total_uploaded = 0;
                                        foreach ($list_stats as $result) {
                                            $i++;
                                            ?>
                                            <tr>
                                                <td><?php echo $i; ?></td>
                                                <td><?php echo $result['name'] . " [", $result['empid'] . "]<br/>" . $result['type_name']; ?></td>
                                                <td><a target="_blank" href="<?php echo base_url() ?>/Editorial/ESCR/user_report_details?empid=<?php echo $result['empid']; ?>&from_date=<?php echo $from_date; ?>&to_date=<?php echo $to_date; ?>&userrole=<?php echo $userrole; ?>" > <?php echo $result['updated']; ?></td>
                                            </tr>

                                            <?php
                                            $total_uploaded += $result['updated'];
                                        }
                                        ?>
                                        <tr style="font-weight:bold;">
                                            <td colspan="2">Total</td>
                                            <td><?= $total_uploaded; ?></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <?php
                            } else {
                                echo "<center><span style='color:red;font-size:24px;'>Data not available for given dates</span></center>";
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



