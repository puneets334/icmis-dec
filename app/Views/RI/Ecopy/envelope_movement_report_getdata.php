<?php
    $srno = 1;
    ?>
    <div id="query_builder_wrapper" class="dataTables_wrapper dt-bootstrap4">
    <div style="align:center;">
        <h2><?php  if(!empty($title)) print_r($title);else echo '';?></h2>
        <table id="tab" class="table table-bordered table-striped">
            <thead><tr style="background-color:darkgrey;">
                <th >SNo</th>
                <th>Application Details</th>
                <th >Applicant Details</th>
                <th>Barcode</th>
                <th>Received By</th>


            </thead><tbody>

            <?php
            if(!empty($envelopeReport))
            {
            foreach($envelopeReport as $row) {
                ?>
                <tr>
                    <td><?= $srno++; ?></td>
                    <td><?= $row['application_number_display']."<br>CRN:".$row['crn']."<br>SP Charges:".$row['postal_fee']."<br>Weight:".$row['envelope_weight']; ?></td>
                    <td><?= $row['name']."<br><u>Address</u>:".$row['address']."<br><u>Mobile</u>:".$row['mobile']."<br><u>Email</u>:".$row['email']; ?></td>
                    <td><?= $row['barcode']; ?></td>
                    <td><?= $row['username']." [".$row['empid']."]<br>".date("d-m-Y H:i:s", strtotime($row['received_on'])); ?></td>
                </tr>
                <?php
            }
            }
            ?>
            </tbody>
        </table>
    </div>
    </div>

    <script>

        $(function () {
            $("#tab").DataTable({
                "responsive": true, "lengthChange": false, "autoWidth": false,
                "buttons": ["copy", "csv", "excel",{extend: 'pdfHtml5',orientation: 'landscape',pageSize: 'LEGAL' },
                    { extend: 'colvis',text: 'Show/Hide'}],"bProcessing": true,"extend": 'colvis',"text": 'Show/Hide'
            }).buttons().container().appendTo('#query_builder_wrapper .col-md-6:eq(0)');

        });


    </script>

