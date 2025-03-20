<div class="card">
    <div class="card-body" >
        <div id="query_builder_wrapper" class="dataTables_wrapper dt-bootstrap4">
            <?php if(!empty($ReportsoffileTrap)):?>
                <table  id="ReportFileTrap" class="table table-bordered table-striped">
                    <thead><tr><th>SNo.</th>
                        <th>Diary No.</th>
                        <th>Parties</th>
                        <th>Dispatch By</th>
                        <th>Dispatch On</th>
                        <th>Remarks</th>
                        <th>Receive On</th>
                        <th>Completed On</th>
                        <th>eFiling View</th>
                    </tr></thead><tbody>
                    <?php $sno = 1; foreach($ReportsoffileTrap as $row):?>
                        <tr><th><?php echo $sno++; ?></th>
                            <td><?php echo substr($row->diary_no,0,-4).'/'.substr($row->diary_no,-4); ?></td>
                            <td><?php echo $row->pet_name.' <b>Vs.</b>'.$row->res_name; ?></td>
                            <td><?php echo $row->d_by_name; ?></td>
                            <td><?php echo ($row->disp_dt) ? date('d-m-Y h:i:s A', strtotime($row->disp_dt)) :''; ?></td>
                            <td><?php echo $row->remarks; ?></td>
                            <td><?php echo ($row->rece_dt) ? date('d-m-Y h:i:s A', strtotime($row->rece_dt)) : ''; ?></td>
                            <td><?php echo ($row->comp_dt)? date('d-m-Y h:i:s A', strtotime($row->comp_dt)) : '';?></td>
                            <td> <?php if($row->efiling_no != ''){?>
                                    <button class="btn ui-button-text-icon-primary " style="background-color: #555555;color: #fff;cursor:pointer;font-size: large;" onclick="efiling_number('<?=$row->efiling_no?>')">View</button>
                                <?php } ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: { echo "Record Not Found"; } endif; ?>
            <!-- end of fileTrap -->
        </div>
        <script>
            $(function () {
                $("#ReportFileTrap").DataTable({
                    "responsive": true, "lengthChange": false, "autoWidth": false,
                    "buttons": ["copy", "csv", "excel",{extend: 'pdfHtml5',orientation: 'landscape',pageSize: 'LEGAL' },
                        { extend: 'colvis',text: 'Show/Hide'}],"bProcessing": true,"extend": 'colvis',"text": 'Show/Hide'
                }).buttons().container().appendTo('#query_builder_wrapper .col-md-6:eq(0)');

            });

                function efiling_number(efiling_number) {
                var link = document.createElement("a")
                link.href = "<?php echo E_FILING_URL ?>/efiling_search/DefaultController/?efiling_number="+efiling_number
                link.target = "_blank"
                link.click()
            }
        </script>
