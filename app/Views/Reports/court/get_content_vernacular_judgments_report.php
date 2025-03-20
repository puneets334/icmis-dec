<div class="card">
    <div class="card-body" >
        <div id="query_builder_wrapper" class="dataTables_wrapper dt-bootstrap4">
            <?php  if(!empty($vernacularjudgmentData)):?>
                <table  id="ReportsVJR" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                        <th>S.No.</th>
                        <th>High Court</th>
                        <th>State</th>
                        <th>Language</th>
                        <th>Case Details</th>
                        <th>Judgment Date</th>
                        <th>File</th>
                        <th>Uploaded By</th>
                        <th>Uploaded On</th>
                        </tr>

                    </thead><tbody>
                    <?php
                    $sno = 1;
                    foreach($vernacularjudgmentData as $row):?>
                        <tr>
                            <td><?= $sno++ ?></td>
                            <td><?= $row['highcourt'] ?></td>
                            <td><?= $row['statename'] ?></td>
                            <td><?= $row['language'] ?></td>
                            <td><?= $row['caseno'] ?><br><?= $row['causetitle'] ?></td>
                             <td><?= $row['judgmentdate'] ?></td>
                            <td><?php if(isset($row['filepath']))
                                {  ?><a href="/supreme_court/<?= $row['filepath'] ?>" target="_blank">
                                    <span class="glyphicon glyphicon-file"></span> <?php  echo 'Uploaded';  } else {  echo 'Not Uploaded';  } ?>
                                </a>
                            </td>
                            <td><?= $row['uploadedby'] ?></td>
                            <td><?= $row['uploadedon'] ?></td>



                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                    </tfoot>
                </table>
            <?php else : ?>
                <div class="text-center align-items-center"><i class="fas fa-info"> </i> No Record Found</div>
            <?php endif; ?>
            <!-- end of refiling search -->

        </div>
        <script>

            $(function () {
                $("#ReportsVJR").DataTable({
                    "responsive": true, "lengthChange": false, "autoWidth": false,
                    "buttons": ["copy", "csv", "excel",{extend: 'pdfHtml5',orientation: 'landscape',pageSize: 'LEGAL' },
                        { extend: 'colvis',text: 'Show/Hide'}],"bProcessing": true,"extend": 'colvis',"text": 'Show/Hide'
                }).buttons().container().appendTo('#query_builder_wrapper .col-md-6:eq(0)');

            });

        </script>
