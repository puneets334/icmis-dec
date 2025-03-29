<?php  $uri = current_url(true); ?>
<?=view('header'); ?>
<section class="content " >
    <div class="container-fluid">
        <div class="row" >
        <div class="col-12" >
        <div class="card" >
        <div class="card-body" >
<form class="form-horizontal" id="push-form"  method="post" action = "<?php htmlspecialchars($_SERVER['PHP_SELF']);?>" >
    <?=csrf_field(); ?>

<div id="diaryNoWise" class="col-sm-12">
<div class="row">
    <div class="col-sm-2">
        <label for="orderDate">Order Date</label>
        <div class="input-group">
            <div class="input-group-addon">
                <!-- <i class="fa fa-calendar"></i> -->
            </div>
            <input required type="date" class="form-control" id="orderDate" name="orderDate" placeholder="dd/mm/yyyy" value="<?php if(!empty($_POST['orderDate'])){ echo $_POST['orderDate']; } ?>">
            
        </div>
    </div>
    <div class="col-sm-1">
        <label for="btnFetchRecords">&nbsp;</label>
        <input type="submit"  id="btnFetchRecords" name="btnFetchRecords" class="btn btn-block btn-gray" value="Submit">
    </div>
</div>
</div>
</form>
<!-- Modal -->
<div class="modal fade" id="dataModal" >
 <div class="modal-dialog">
  <!-- Modal content-->
  <div class="modal-content">
  </div>
 </div>
</div>  

<div id="tableData">
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
</div>

        </div>
        </div>
    </div>
        </div>
    </div>
</section>
<script>

async function updateCSRFTokenSyncN() {
        try {
            const response = await $.ajax({
                url: "<?php echo base_url('Csrftoken'); ?>",
                dataType: 'json'
            });

            return response.CSRF_TOKEN_VALUE; // Correctly extracting CSRF token
        } catch (error) {
            console.error("Error fetching CSRF token:", error);
            return null;
        }
    }
 
    $(document).ready(function(){        

        $('.infodata').click(async function(){
            var dataid = $(this).data('id');
            var session_user = '<?php //echo $uri->segment(3, 0) ?>';
            var CSRF_TOKEN_VALUE = await updateCSRFTokenSyncN();
            //   alert(dataid);
            $.ajax({
                url: '<?=base_url()?>/Faster/FasterController/startsendtoFasterWithId',
                type: 'post',
                data: {CSRF_TOKEN: CSRF_TOKEN_VALUE,dataid: dataid,session_user: session_user},
                beforeSend: function(){
                    /* Show image container */
                    $("#loader").show();
                },
                success: function(response){
                    // Add response in Modal body
                    $('.modal-content').html(response);

                    // Display Modal
                    $('#dataModal').modal('show');
                },
                complete:function(data){
                    /* Hide image container */
                    $("#loader").hide();
                }
            });
           
        });


    });
  




</script>

