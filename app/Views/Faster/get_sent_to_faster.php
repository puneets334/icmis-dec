<div class="table table-striped table-bordered dt-bootstrap4">
<table id="example" class="table table-striped table-bordered dt-bootstrap4" style="width:90%">
<thead>
    <?php
    if(isset($orderDateResult)) { 
          if(sizeof($orderDateResult)>0 ){?>
`       <tr><th style="width: 5%">#</th><th style="width: 20%">Case Number</th><th style="width: 30%">Cause title</th>
        <th style="width: 5%">Order Date</th><th style="width: 10%">Action</th>
        </tr>
        </thead>
        <tbody>
        <?php  $i=0;
        foreach ($orderDateResult as $result)
        {  $i++;
            $statuslink="";
            if($result['is_sent_to_new_faster'] == 0){
                $statuslink='<button type="button" class="infodata btn btn-gray" data-toggle="modal" data-id=p_'.$result['id'].'_'.$result['sent_to_new_faster_agency'].' >Process</button>';
            }
            else{
                $statuslink='<button type="button" class="infodata btn btn-info" data-toggle="modal" data-id=r_'.$result['id'].'_'.$result['sent_to_new_faster_agency'].' >Update</button>';
            }
        ?>
        <tr>
        
        <td><?php echo $i;?></td>
        <td><?php echo $result['reg_no_display'];?><br/><?php echo $result['diary_no'];?></td>
        <td><?php echo $result['causetitle'];?></td>
        <td><?php echo date('d-m-Y',strtotime($result['next_dt']));?></td>

        <td><?php echo $statuslink;?></td>
        
        </tr>

        <?php     }  ?>
        </tbody>
        </table>
        <?php }  else  {    echo "<div class='col-sm-12' class='error-messages' align='center'>No Records Found</div>";   }
        }  ?>
</div>