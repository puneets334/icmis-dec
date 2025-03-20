
<?php if(isset($result) && !empty($result)){ ?>
                <div id="query_builder_wrapper" class="dataTables_wrapper dt-bootstrap4 query_builder_wrapper">

                    <span style="text-align: center;">
                       E-filed Applications -Refiling Report
                    </span>
                    <br/><br/>
                <table  id="datatable_report" class="table table-bordered table-striped datatable_report">
                    <thead>
                    <tr>
                        <th>S.N.</th>
                        <th>Ref ID</th>
                        <th>SC D.No.</th>
                        <th>CauseTitle</th>
                        <th>Transaction ID</th>
                        <th width="25%">Total Amount <br>{C.Fees + Printing Charges}</th>
                        <th>Receipt</th>
                        <th>Date</th>
                        <th>Source</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $i=1; $cause_title='';
                    foreach ($result as $row) {
                        if ($row['app_flag']=='Deficit_DN'){
                            $cause_title=$row['pet_name'].' vs '.$row['res_name'];
                        }else{
                            $cause_title=$row['pet_name'].' vs '.$row['res_name'];
                        }
                        ?>
                        <tr>
                            <td><?= $i++;?></td>
                            <td><?=$row['ack_id'].'/'.$row['ack_year'];?></td>
                            <td>

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
                            <td><?=(!empty($row['app_flag'])) ? $row['app_flag'] : '';?></td>
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


