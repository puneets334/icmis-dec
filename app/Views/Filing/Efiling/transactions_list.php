<div class="box box-success" if="transactions_list">
            <div class="box-header with-border">
                <h3 class="box-title" id="form-title">E-filed Applications -Refiling Report</h3><span style="float: right"><input type="text" class="form-control" model="searchText" placeholder="Search"></span><span style="float: right"><button type="button" class="btn bg-purple btn-flat" onclick="print_table()">Print</button></span><br/>
            </div>
            <div id="printTable">
                <table class="table table-striped table-hover">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Ref ID</th>
                        <th>SC D.No.</th>
                        <th>Diary No.</th>
                        <th>CauseTitle</th>
                        <th>Transaction ID</th>
                        <th>Amount</th>
                        <th>Date</th>
                        <th>Source</th>

                    </tr>
                    </thead>
                    <tbody>
                    <?php if(!empty($transactions))
                        { 
                        foreach($transactions as $key => $value)
                        {
                           
                            $diary_no = substr($value['d_no'], 0, strlen($value['d_no']) - 4);
                            $diary_year = substr($value['d_no'], -4);
                                ?>
                        <tr>                       
                            <td><?php echo $key +1;?></td>
                            <td style="cursor:pointer;">-</td>
                            <td style="cursor:pointer;" onclick="get_ack_no('<?php echo $value['ack_id']?>', '<?php echo $value['ack_year']?>', '<?php echo $value['d_no']?>')" ><?php echo $value['ack_id'];?>/<?php echo $value['ack_year'];?></td>
                            <td style="cursor:pointer;"><?php echo $diary_no. "/". $diary_year; ?></td>
                            <td style="cursor:pointer;"><?php echo $value['pet_name']; ?> vs <?php echo $value['res_name']; ?></td>
                            <td style="cursor:pointer;" onclick="get_docs($index)"  ><?php echo $value['transaction_id']; ?></td>
                            <td style="cursor:pointer;"><?php echo $value['amount']; ?></td>
                            <td style="cursor:pointer;"><?php echo $value['transaction_datetime']; ?></td>                            
                            <td style="cursor:pointer;" onclick="get_docs($index)"  data-toggle="modal" data-target="#docModal" if="x.org_diary_no!=0 && x.app_flag!='Deficit_DN'"><?php echo $value['app_flag']; ?></td>
                             
                        </tr>
                        <?php }
                        }else{?>
                             <tr>                       
                                <td colspan="100%">Record not found....</td>
                            </tr>
                        <?php }?>
                    </tbody>
                </table>
            </div>
        </div>