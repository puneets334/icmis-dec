<div class="card">
    <div class="card-body" >
        <div id="query_builder_wrapper" class="dataTables_wrapper dt-bootstrap4">

            <?php if(!empty($Reportsofdak)):?>
                <table  id="ReportDak" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>SNo.</th>
                        <th>Diary No </th>
                        <th>Case No.</th>
                        <th>Document No. &amp; Section </th>
                        <th>Doc Description </th>
                        <th>Cause Title</th>
                        <th>Remarks</th>

                    </tr>
                    </thead><tbody>
                    <?php
                    $sno = 1;
                    foreach($Reportsofdak as $row):?>
                           <tr>
                            <td><?= $sno++ ?></td>
                            <td><?= $row->diary_no?></td>
                            <td><?= $row->case_no ?></td>
                            <td><?= $row->document .'<br>'. $row->section ?></td>
                            <td><?= $row->docdesc?></td>
                            <td><?= $row->causetitle?></td>
                            <td><?= $row->remark?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>

                </table>
            <?php else: ?>
                <div class="text-center align-items-center"><i class="fas fa-info"> </i> No Record Found</div>
           <?php endif; ?>
            <?php if(!empty($Reportsofdakcb)):?>
                <table  id="ReportDak" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>SNo.</th>
                        <th>Diary No </th>
                        <th>Parties</th>
                        <th>Reason to Block </th>
                        <th>Entered by </th>
                        <th>Section</th>
                        <th>Date</th>
                    </tr>
                    </thead><tbody>
                    <?php
                    $sno = 1;
                    foreach($Reportsofdakcb as $row):?>
                        <tr>
                            <td><?= $sno++ ?></td>
                            <td><?= $row->diary_no.'/'.$row->diary_year?></td>
                            <td><?= $row->pet_name ?> Vs. <?= $row->res_name ?></td>
                            <td><?= $row->reason_blk ?></td>
                            <td><?= $row->name ?></td>
                            <td><?= $row->section_name ?></td>
                            <td><?= $row->ent_dt ?></td>

                        </tr>
                    <?php endforeach; ?>
                    </tbody>

                </table>
             <?php endif; ?>

        </div>
        <script>
            $(function () {
                $("#ReportDak").DataTable({
                    "responsive": true, "lengthChange": false, "autoWidth": false,
                    "buttons": ["copy", "csv", "excel",{extend: 'pdfHtml5',orientation: 'landscape',pageSize: 'LEGAL' },
                        { extend: 'colvis',text: 'Show/Hide'}],"bProcessing": true,"extend": 'colvis',"text": 'Show/Hide'
                }).buttons().container().appendTo('#query_builder_wrapper .col-md-6:eq(0)');

            });
        </script>
