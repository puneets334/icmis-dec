<div class="card">
    <div class="card-body" >
        <div id="query_builder_wrapper" class="dataTables_wrapper dt-bootstrap4">
            <?php  if(!empty($reports)){?>
                <table  id="ReportsCAV" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>S.No.</th>
                        <th>Caveat No. /<br/>Receiving Date</th>
                        <th>Petitioner<br/>Vs<br/>Respondent</th>
                        <th>Advocate</th>
                        <th>From Court</th>
                        <th>State</th>
                        <th>Bench</th>
                        <th>Case No.</th>
                        <th>Judgement Date</th>
                        <th>Status</th>
                       </tr>
                     </thead><tbody>
                    <?php
                   $sno = 1; $cur_date=date('Y-m-d');
                    foreach($reports as $row){
                        $caveat_date= !empty($row['diary_no_rec_date']) ? date('d-m-Y',strtotime($row['diary_no_rec_date'])): '';
                        $caveat_no= !empty($row['caveat_no']) ? substr($row['caveat_no'],0,-4).'-'.  substr($row['caveat_no'],-4): '';
                        $advocate_details= !empty($row['aor_code']) ? $row['aor_code'].'-'.$row['advocate_name']: $row['advocate_name'];
                        ?>
                        <tr>
                            <td><?=$sno;?></td>
                            <td><?php echo $caveat_no; ?>
                                <span style="color: red"><?php echo $caveat_date;?></span>
                            </td>
                            <td><?php echo $row['pet_name'].'<br/>Vs<br/>'.$row['res_name'];?></td>
                            <td><?php echo $advocate_details;?></td>
                            <td><?php echo $row['court_name'];?></td>
                            <td><?php echo $row['state_name'];?></td>
                            <td><?php echo $row['agency_name'];?></td>
                            <td><?php echo $row['type_sname'].'-'.$row['lct_caseno'].'-'.$row['lct_caseyear'];?></td>
                            <td><?=!empty($row['lct_dec_dt']) ? date('d-m-Y',strtotime($row['lct_dec_dt'])) : '';?></td>
                            <td>
                                <?php
                                if (!empty($caveat_date)){
                                $date1=date_create($caveat_date);
                                $date2=date_create($cur_date);
                                $diff=date_diff($date1,$date2);
                                $date_diff= $diff->format("%R%a days");
                                $rep_date_diff= intval(str_replace('+','', $date_diff));
                                if($rep_date_diff<=90){?>
                                    <span style="color: green">Active</span>
                                    <?php }else{ ?>
                                    <span style="color: red">Expired</span>
                                    <?php } } ?>
                            </td>
                         </tr>
                    <?php $sno++; } ?>
                    </tbody>
                    </tfoot>
                </table>
            <?php }else{ ?>
                <div class="text-center align-items-center"><i class="fas fa-info"> </i> No Record Found</div>
            <?php } ?>
            <!-- end of refiling search -->
        </div>
        <script>

            $(function () {
                $("#ReportsCAV").DataTable({
                    "responsive": true, "lengthChange": false, "autoWidth": false,
                    "buttons": ["copy", "csv", "excel",{extend: 'pdfHtml5',orientation: 'landscape',pageSize: 'LEGAL' },
                        { extend: 'colvis',text: 'Show/Hide'}],"bProcessing": true,"extend": 'colvis',"text": 'Show/Hide'
                }).buttons().container().appendTo('#query_builder_wrapper .col-md-6:eq(0)');

            });

        </script>
