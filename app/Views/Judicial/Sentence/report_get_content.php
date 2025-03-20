<div class="row">
    <div class="col-12">
        <?php if(isset($result) && !empty($result)){ ?>
            <center><b style="text-align: center;"> <h3> Matters in which Accused is In Jail / On Bail as on <?=date('d-m-Y h:i:s A');?></h3></b> </center><br/>
            <div id="query_builder_wrapper" class="dataTables_wrapper dt-bootstrap4 query_builder_wrapper">
                <table  id="datatable_report" class="table table-bordered table-striped datatable_report">
                    <thead>
                    <tr>
                        <th>S.N.</th>
                        <th>Case No.</th>
                        <th>Cause Title</th>
                        <th>Case Status</th>
                        <th>In Jail/On Bail</th>
                        <th>Dealing Assistant</th>
                        <th>Section</th>
                        <th>Miscelleneous/Final</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $sno=1;
                    foreach ($result as $row) { ?>
                        <tr>
                            <td><?= $sno++;?></td>
                            <td><?=$row['reg_no_display']." @ ".substr($row['diary_no'],0,strlen($row['diary_no']) - 4)."/".substr($row['diary_no'], - 4);?></td>
                            <td><?=$row['cause_title'];?></td>
                            <td><?=$row['c_status'];?></td>
                            <td><?=$row['status'];?></td>
                            <td><?=$row['name'].'['.$row['empid'].']';?></td>
                            <td><?=$row['section_name'];?></td>
                            <td><?=$row['mf_active'];?></td>
                        </tr>
                    <?php }?>
                    </tbody>
                </table>
            </div>
            <script>
                $(function () {
                    $(".datatable_report").DataTable({
                        "responsive": true, "lengthChange": false, "autoWidth": false,
                        "buttons": ["copy", "csv", "excel",{extend: 'pdfHtml5',orientation: 'landscape',pageSize: 'LEGAL' },
                            { extend: 'colvis',text: 'Show/Hide'}],"bProcessing": true,"extend": 'colvis',"text": 'Show/Hide'
                    }).buttons().container().appendTo('.query_builder_wrapper .col-md-6:eq(0)');

                });
            </script>
        <?php } ?>
        <?php if(isset($result) && empty($result)){ ?>
            <div class="text-center align-items-center"><i class="fas fa-info"> </i> No Record Found</div>
        <?php } ?>
    </div>
</div>