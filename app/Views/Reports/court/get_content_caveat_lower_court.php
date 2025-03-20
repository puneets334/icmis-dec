
        <div id="query_builder_wrapper" class="dataTables_wrapper dt-bootstrap4">
            <?php
            if($u_t==0){
                $s_no=1;
            }else if($u_t==1){
                $s_no=$inc_tot_pg;
            }

            if(!empty($reports)){?>

                <table  id="ReportsCAV" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>S.No.</th>
                        <th>Diary No.</th>
                        <th>Registration No. </th>
                        <th>Petitioner<br/>Vs<br/>Respondent</th>
                        <th>From Court</th>
                        <th>State</th>
                        <th>Bench</th>
                        <th>Case No.</th>
                        <th>Judgement Date</th>
                        <th>Caveat No.</th>
                       </tr>
                     </thead>
                    <tbody>
                    <?php $active_fil_no='';$active_fil_dt='';$short_description=$advocate_details=$reg_no_display='';
                    $cur_date=date('Y-m-d');
                    foreach($reports as $row){
                        //$caveat_date= !empty($row['diary_no_rec_date']) ? date('d-m-Y',strtotime($row['diary_no_rec_date'])): '';
                        $diary_no= !empty($row['diary_no']) ? substr($row['diary_no'],0,-4).'-'.  substr($row['diary_no'],-4): '';
                        $caveat_no= !empty($row['caveat_no']) ? substr($row['caveat_no'],0,-4).'-'.  substr($row['caveat_no'],-4): '';
                        //$advocate_details= !empty($row['aor_code']) ? $row['aor_code'].'-'.$row['advocate_name']: $row['advocate_name'];
                    if(!empty($row['reg_no_display'])){
                        $reg_no_display=$row['reg_no_display'];
                    }else{
                        if($row['active_fil_no']!=''){  $active_fil_no= '-'.intval(substr($row['active_fil_no'],3));  }
                        if($row['active_fil_dt']!=''){ $active_fil_dt= '/'.date('Y',strtotime($row['active_fil_dt'])); }
                        $reg_no_display= $row['short_description'].$active_fil_no.$active_fil_dt;
                    }


                        ?>
                        <tr>
                            <td><?=$s_no;?></td>
                            <td><?php echo $diary_no; ?></td>
                            <td><?php echo $reg_no_display; ?></td>
                            <td><?php echo $row['pet_name'].'<br/>Vs<br/>'.$row['res_name'];?></td>
                            <td><?php echo $row['court_name'];?></td>
                            <td><?php echo $row['name'];?></td>
                            <td><?php echo $row['agency_name'];?></td>
                            <td><?php echo $row['type_sname'].'-'.$row['lct_caseno'].'-'.$row['lct_caseyear'];?></td>
                            <td><?=!empty($row['lct_dec_dt']) ? date('d-m-Y',strtotime($row['lct_dec_dt'])) : '';?></td>
                            <td><?php echo $caveat_no; ?></td>
                         </tr>
                    <?php $s_no++; } ?>
                    </tbody>
                    </tfoot>
                </table>
                <input type="hidden" name="inc_tot_pg" id="inc_tot_pg" value="<?php echo $s_no; ?>" />
            <?php }else{ ?>
                <div class="text-center align-items-center"><i class="fas fa-info"> </i> No Record Found</div>
            <?php } ?>
            <!-- end of refiling search -->

        <script>

            $(function () {
                $("#ReportsCAV").DataTable({
                    "responsive": true, "lengthChange": false, "autoWidth": false,
                    "buttons": ["copy", "csv", "excel",{extend: 'pdfHtml5',orientation: 'landscape',pageSize: 'LEGAL' },
                        { extend: 'colvis',text: 'Show/Hide'}],"bProcessing": true,"extend": 'colvis',"text": 'Show/Hide'
                }).buttons().container().appendTo('#query_builder_wrapper .col-md-6:eq(0)');

            });

        </script>
        </div>