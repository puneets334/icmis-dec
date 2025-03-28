<?php
if(count($transactionData)>0)
{
    ?>
    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
        <table id="reportTable1" class="table table-striped table-hover">
            <thead>
                <tr>
                    <th style="width: 8%;"><b>Listed On</b></th>
                    <th style="width: 20%;"><b>Info</th></b>
                    <th style="width: 8%;"><b>Transaction Date</b></th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $last_date = '';
                foreach ($transactionData as $data):
                    $curr_date = $data['next_dt'];
                ?>
                    <tr>
                        <?php if ($curr_date == $last_date): ?>
                            <td>&nbsp;</td>
                        <?php else: ?>
                            <td><?= date("d-m-Y", strtotime($data['next_dt'])) ?></td>
                        <?php endif; ?>
                        <td><?= $data['info'] ?></td>
                        <td><?= date("d-m-Y", strtotime($data['transaction_date'])) ?></td>
                    </tr>
                <?php
                    $last_date = $curr_date; 
                endforeach; 
                ?>
            </tbody>
        </table>
    </div>
    <?php
}
else
{
    ?>
    <div class="form-group col-md-12">
        <label class="text-danger">&nbsp;No Record Found!!</label>
    </div>
    <?php
}
?>