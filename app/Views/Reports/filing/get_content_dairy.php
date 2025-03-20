        <div id="query_builder_wrapper" class="query_builder_wrapper dataTables_wrapper dt-bootstrap4">
            <?php if(!empty($ReportsofDiary)):?>
                <table  id="query_builder_report" class="query_builder_report table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>SNo. </th>
                        <th>DiaryNo / Diary Date </th>
                        <th>Registration No. & Date</th>
                        <th>Cause Title </th>
                        <th>Petitioner Advocate </th>
                        <th>Diary User</th>
                        <th>State/Lower Court Information</th>
                        <th>Total Pet.</th>
                        <th>Total Res.</th>
                        <!-- <th>Section</th>     -->
                        <th>E-mail ID & Mobile No.</th>
                        <th>Status</th>
                    </tr>
                    </thead><tbody>
                    <?php
                    $sno = 1;
                    foreach($ReportsofDiary as $row):?>
                        <tr>
                            <td><?= $sno++ ?></td>
                            <td><?= $row->diary_no.'/'.$row->diary_year?><br><?php echo date('d-m-Y',strtotime($row->diary_no_rec_date));?></td>
                            <td><?= $row->fil_no ?><br><?php ($row->active_fil_dt) ? date('d-m-Y',strtotime($row->active_fil_dt)) : ''?></td>
                            <td><?= $row->pet_name ?> Vs. <?= $row->res_name ?></td>
                            <td><?= $row->pet_adv_id ?></td>
                            <td><?= $row->diary_user_id ?></td>            <td><?= $row->ref_agency_state_id?> # <?= $row->ref_agency_code_id?></td>
                            <td><?= $row->pno?></td>            <td><?= $row->rno?></td>
                            <!-- <td><?= $row->email?><br><?= $row->mobile?></td> -->
                            <td><?= $row->email?><br><?= $row->mobile?></td>
                            <td>
                               <?php
                                if($row->c_status =='P')
                                {?> <font style='text-align: center;font-size: 14px;color: black'> STATUS:</font><span style="color:red"><?php echo "Pending";?></span> <?php
                                }
                                else
                                { ?>
                                    <font style='text-align: center;font-size: 14px;color: black'> STATUS:</font><span style="color:green"><?php echo "Disposed";?></span> <?php
                                }
                                ?>
                            </td>

                        </tr>
                    <?php endforeach; ?>
                    </tbody>


                </table>
            <?php else : ?>
            <div class="text-center align-items-center"><i class="fas fa-info"> </i> No Record Found</div>
            <?php endif; ?>

        </div>
        <script>

            $(function () {
                $("#query_builder_report").DataTable({
                    "responsive": true, "lengthChange": false, "autoWidth": false,
                    "buttons": ["copy", "csv", "excel",{extend: 'pdfHtml5',orientation: 'landscape',pageSize: 'LEGAL' },
                        { extend: 'colvis',text: 'Show/Hide'}],"bProcessing": true,"extend": 'colvis',"text": 'Show/Hide'
                }).buttons().container().appendTo('#query_builder_wrapper .col-md-6:eq(0)');

            });


        </script>


