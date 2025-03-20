 
 <p style="text-align:center;vertical-align: middle;"><h2 align="center">MISCELLANEOUS DAK COUNTER</h2>
<h2 align="center">Documents received from <?=date('d-m-Y',strtotime($first_date))?> to <?=date('d-m-Y',strtotime($to_date))?></h2></p>
    <table class="table table-striped custom-table table-hover dt-responsive" style="width: 100%;">
        <thead>        
        <tr>
            <th>Sr.No.</th>
            <th>Date</th>
            <th>Total No. of Documents Received</th>
        </tr>
        </thead>
        <tbody>
        <?php
        if(isset($loose_document_result) && sizeof($loose_document_result)>0 ) 
        {
                $i=0;
                $total=0;
                foreach ($loose_document_result as $result)
                {$i++;
                    ?>
                    <tr>
                        <td><?php echo $i;?></td>
                        <td><?php echo  date('d-m-Y',strtotime($result['date1']));?></td>
                        <td><a target="_blank" href="<?php echo base_url();?>/Reports/Filing/Report/loose_document_detail?date=<?= $result['date1'];?>"> <?php echo $result['documents'];?></a></td>
                    </tr>

                    <?php
                    $total+=$result['documents'];
                }
                ?>
                <tr style="font-weight: bold;"><td colspan="2">Total</td><td><?= $total?></tr>
        <?php }else{?>
                <tr style="font-weight: bold;"><td colspan="100%">No Record found...</td></tr>

                <?php }?>
            
        </tbody>
    </table>

 