<?= view('header') ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Judicial >> Sentence Status >> Sentence Details</h3>
                            </div>
                            <div class="col-sm-2"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mt-3">
                            <?php if(isset($result) && !empty($result)){ ?>
                                <div id="query_builder_wrapper" class="dataTables_wrapper dt-bootstrap4 query_builder_wrapper">
                                    <br/><center><b style="text-align: center;"> <h3> List of Pending matters (Sentence Undergone) as on <?=date('d-m-Y h:i:s A');?></h3></b> </center><br/>
                                    <table  id="datatable_report" class="table table-bordered table-striped datatable_report">
                                        <thead>
                                        <tr>
                                            <th>SNo.</th>
                                            <th>Case No.@ Diary_no</th>
                                            <th>Cause Title</th>
                                            <th>Accused</th>
                                            <th>Section</th>
                                            <th>Sentence Awarded</th>
                                            <th>Sentence Undergone(DAYS)</th>
                                            <th>Dealing Assistant</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php $sno=1;
                                        foreach ($result as $row) { ?>
                                            <tr>
                                                <td><?= $sno++;?></td>

                                                <td><?php echo $row['reg'];?>@<?php echo $row['diary_number'];?>/<?php echo $row['diary_year'];?></td>
                                                <td><?php echo $row['cause'];?></td>
                                                <td><?php echo $row['accused'];?></td>
                                                <td><?php echo $row['section'];?></td>
                                                <td><?php echo $row['awarded'];?></td>
                                                <!--<td><?php /*echo $row['undergone'];*/?></td>-->
                                                <td><?php echo $row['sum'];?></td>
                                                <td><?php echo $row['da'];?></td>
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
                </div>
            </div>
        </div>
    </div>
</section>