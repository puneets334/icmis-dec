
<?php if(isset($result) && !empty($result)){ ?>
                <div  class="dataTables_wrapper dt-bootstrap4 query_builder_wrapper_dataTable">

                    <span style="text-align: center;">
                       E-filed Applications -Refiling Report
                    </span>
                    <br/><br/>
                <table  class="table table-bordered table-striped datatablereport">
                    <thead>
                    <tr>
                        <th>S.N.</th>
                        <th>Delayed Transaction</th>
                        <th>Ref ID</th>
                        <th>SC D.No.</th>
                        <th>Cause Title</th>
                        <th>Transaction ID</th>
                        <th width="15%">Total Amount <br>{C.Fees + Printing Charges}</th>
                        <th>Receipt</th>
                        <th>Date</th>
                        <th>Source</th>
                        <th>Action Completed</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $i=1; $cause_title='';
                    foreach ($result as $row) {
                    if ((!empty($row['scheduler_datetime']) || $row['scheduler_datetime'] !=null)){
                        $scheduler_datetime_color='text-danger';
                    }else{
                        $scheduler_datetime_color='text-success';
                    }
                        if ($row['app_flag']=='Deficit_DN'){
                            $cause_title=$row['pet_name'].' vs '.$row['res_name'];
                        }else{
                            $cause_title=$row['pet_name'].' vs '.$row['res_name'];
                        }
                        ?>
                        <tr>
                            <td><?= $i++;?></td>
                            <td class="text-danger"><?=(!empty($row['scheduler_datetime'])) || $row['scheduler_datetime'] !=null ? 'YES' : '';?></td>
                            <td><?=(!empty($row['ack_id'])) ? $row['ack_id'].'/'.$row['ack_year'] : '-';?></td>
                            <td>
                                <?=(!empty($row['d_no']) && $row['d_no'] !=null) ? substr($row['d_no'], 0, -4).'/'.substr($row['d_no'],-4) : '';  ?>
                                <?=(!empty($row['org_diary_no']) && $row['org_diary_no'] !=null) ? substr($row['org_diary_no'], 0, -4).'/'.substr($row['org_diary_no'],-4) : '';  ?>
                            </td>
                            <td><?=$cause_title;?></td>
                            <td><?=(!empty($row['transaction_id'])) ? $row['transaction_id'] : '';?></td>
                            <td><?php if ((!empty($row['scheduler_datetime']) && !empty($row['endpoint_transaction_id']))){
                                      echo '<span class="text-danger">'.$row['amount'].' = ('.$row['udf4'].' + '.$row['udf5'].')</span>';
                                }else{
                                    echo '<span class="text-success">'.$row['amount'].' = ('.$row['udf4'].' + '.$row['udf5'].')</span>';
                               }?>

                                </td>
                            <td>
                            <?php if ((!empty($row['scheduler_datetime']) && !empty($row['endpoint_transaction_id']))){ ?>
                                <a class="text-danger" href="https://www.shcileservices.com/OnlineE-Payment/sEpsPaymentChallan?userid=dlsupcourt&shcilrefno=<?=$row['endpoint_transaction_id'];?>" target="_blank">Download</a></td>
                            <?php }else if ((empty($row['scheduler_datetime']) && !empty($row['endpoint_transaction_id']))){ ?>
                                <a class="text-success" href="https://www.shcileservices.com/OnlineE-Payment/sEpsPaymentChallan?userid=dlsupcourt&shcilrefno=<?=$row['endpoint_transaction_id'];?>" target="_blank">Download</a></td>
                            <?php }?>
                            </td>
                            <td><?=(!empty($row['transaction_datetime']) && $row['transaction_datetime'] !=null) ? date('d-m-Y H:i:s', strtotime($row['transaction_datetime'])) : '';?></td>
                            <td  data-toggle="modal" data-target="#docModal">
                                <span class="get_docs" data-app_flag="<?=$row['app_flag'];?>" data-transaction_id="<?=$row['transaction_id'];?>" data-org_diary_no="<?=$row['org_diary_no'];?>" data-ack_id="<?=$row['ack_id'];?>"  data-ack_year="<?=$row['ack_year'];?>" ><?=(!empty($row['app_flag'])) ? $row['app_flag'] : '';?><span>
                            </td>
                            <td>
                                <span id="transaction_id_<?=$row['transaction_id'];?>"> <button  data-transaction_id="<?=$row['transaction_id'];?>" data-action_update="<?=$row['action_update'];?>"  type="button" class="upd_action btn btn-block bg-olive btn-flat pull-right" ><i class="fa fa-save"></i> &nbsp;<?=$row['action_update'];?></button></span>
                            </td>
                        </tr>
                        <?php }?>
                    </tbody>

                </table>
        </div>
    <script>
        $(function () {
            $(".datatablereport").DataTable({
                "responsive": true, "lengthChange": false, "autoWidth": false,
                "buttons": ["copy", "csv", "excel",{extend: 'pdfHtml5',orientation: 'landscape',pageSize: 'LEGAL' },
                    { extend: 'colvis',text: 'Show/Hide'}],"bProcessing": true,"extend": 'colvis',"text": 'Show/Hide'
            }).buttons().container().appendTo('.query_builder_wrapper_dataTable .col-md-6:eq(0)');

        });
    </script>
          <?php } ?>
<?php if(isset($result) && empty($result)){ ?>
    <div class="text-center align-items-center"><i class="fas fa-info"> </i> No Record Found</div>
<?php } ?>


