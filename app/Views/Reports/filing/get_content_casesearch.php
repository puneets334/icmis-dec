<div class="card">
    <div class="card-body" >
        <div id="query_builder_wrapper" class="dataTables_wrapper dt-bootstrap4">
            <?php  if(empty($ddl_court)): ?>
                <table  id="ReportCaseSearch" class="responsive-table table table-bordered table-striped">
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
                        <th>E-mail ID & Mobile No.</th>
                         </tr>
                    </thead><tbody>
                    <?php
                    $sno = 1;
                    foreach($ReportsofCaseSearch as $row):?>
                        <tr>
                            <td><?= $sno++ ?></td>
                            <td><?= $row->diary_no.'/'.$row->diary_year ?><br><?php echo date('d-m-Y',strtotime($row->diary_date));?></td>
                            <td><?= $row->fil_no ?><br><?php ($row->active_fil_dt) ? date('d-m-Y',strtotime($row->active_fil_dt)) : ''?></td>
                            <td><?= $row->pet_name ?> Vs. <?= $row->res_name ?></td>
                            <td><?= $row->pet_adv_id ?></td>
                            <td><?= $row->diary_user_id ?></td>
                            <td><?= $row->ref_agency_state_id?> # <?= $row->ref_agency_code_id?></td>
                            <td><?= $row->pno?></td>
                            <td><?= $row->rno?></td>
                            <td><?= $row->email?><br><?= $row->mobile?></td>


                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
            <!-- end report of case search -->

            <?php  if(!empty($caveatCaseSearch) && !empty($ddl_court)):?>

                <table  id="ReportCaseSearch" class="responsive-table table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>SNo. </th>
                        <th>Caveat No. /<br/>Receiving Date </th>
                        <th>Petitioner<br/>Vs<br/>Respondent</th>
                        <th>From Court </th>
                        <th>State</th>
                        <th>Bench</th>
                        <th>Case No.</th>
                        <th>Judgement Date</th>
                    </tr>
                    </thead><tbody>
                    <?php
                    $sno = 1;
                    foreach($caveatCaseSearch as $row): ?>
                        <tr>
                            <td><?= $sno++ ?></td>
                            <td><?= $row->caveat_no ?><br><?=isset($row->diary_no_rec_date)?date('d-m-Y',strtotime($row->diary_no_rec_date)):'';?></td>
                            <td><?= $row->pet_name ?> Vs. <?= $row->res_name ?></td>
                            <td><?= $row->court_name ?></td>
                            <td><?= $row->name?></td>
                            <td><?= $row->agency_name?></td>
                            <td><?= $row->type_sname?>-<?= $row->lct_caseno?>-<?= $row->lct_caseyear?></td>
                            <td><?= isset($row->lct_dec_dt)?date('d-m-Y',strtotime($row->lct_dec_dt)):'';?></td>

                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>

            <?php endif; ?>
            <!-- end report of case search -->

            <?php  if(!empty($dairyCaseSearch) && !empty($ddl_court)):?>

                <table  id="ReportCaseSearch" class="responsive-table table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>SNo. </th>
                        <th>Diary No. /<br/>Receiving Date </th>
                        <th>Petitioner<br/>Vs<br/>Respondent</th>
                        <th>From Court </th>
                        <th>State</th>
                        <th>Bench</th>
                        <th>Case No.</th>
                        <th>Judgement Date</th>
                    </tr>
                    </thead><tbody>
                    <?php
                    $sno = 1;
                    foreach($dairyCaseSearch as $row):?>
                        <tr>
                            <td><?= $sno++ ?></td>
                            <td><?= $row->diary_no ?><br><?=isset($row->diary_no_rec_date)?date('d-m-Y',strtotime($row->diary_no_rec_date)):'';?></td>
                            <td><?= $row->pet_name ?> Vs. <?= $row->res_name ?></td>
                            <td><?= $row->court_name ?></td>
                            <td><?= $row->name?></td>
                            <td><?= $row->agency_name?></td>
                            <td><?= $row->type_sname?>-<?= $row->lct_caseno?>-<?= $row->lct_caseyear?></td>
                            <td><?= isset($row->lct_dec_dt)?date('d-m-Y',strtotime($row->lct_dec_dt)):'';?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>

            <?php endif; ?>
        </div>
        <script>

            $(function () {
                $("#ReportCaseSearch").DataTable({
                    "responsive": true, "lengthChange": false, "autoWidth": false,
                    "buttons": ["copy", "csv", "excel",{extend: 'pdfHtml5',orientation: 'landscape',pageSize: 'LEGAL' },
                        { extend: 'colvis',text: 'Show/Hide'}],"bProcessing": true,"extend": 'colvis',"text": 'Show/Hide'
                }).buttons().container().appendTo('#query_builder_wrapper .col-md-6:eq(0)');

            });


        </script>
