<?php $did = explode('_', $dataid);?>
<div class="modal-header">
 <h4 class="modal-title ">Send to Faster 2.0</h4>
</div>
 <div class="modal-body">
    <form class="form-horizontal" id="push-form"  method="post" action = "<?php htmlspecialchars($_SERVER['PHP_SELF']);?>" >
        <table class='table-responsive table table-lg '>
        <tr><td>Select Hight Court:</td><td > 
        <select class="form-control" id="highcourt" name="highcourt" placeholder="High Court" required  >
        <?php
        $selected="";
        foreach ($getHighCourt as $rows){
            if(isset($did[1]) && ($rows['id'] == $did[1])) $selected= "selected"; else $selected="";  
        ?>

        <option <?php echo $selected ?> value=<?php echo $rows['id'] ?>><?php echo $rows['agency_name'] ?></option>;
        <?php  }   ?>
        </select></td>
        </tr>
        <tr>

        <tfoot><tr >
        <td class='text-wrap text-left' style='width: 35%'></td><td class='text-center'> 
        <input type="hidden" name="faster_id"  id = "faster_id"  value="<?php echo $dataid;?>"> 
        <input type="hidden" name="agency_or_court"  id = "agency_or_court"  value="<?php echo $agency_or_court;?>"> 
        <input type="hidden" name="session_user"  id = "session_user"  value="<?php echo $session_user;?>">    

        <?php 
         if(isset($did[0]) && ($did[0] == 'r')){
        ?> <button type="button" class="infodatastate btn btn-info" id="delete" >Delete</button>
        <button type="button" class="infodatastate btn btn-info" id="save" >Update</button></td>
        <?php }else { ?>
        <button type="button" class="infodatastate btn btn-info" id="save" >Save</button><?php } ?></td>
        </tfoot></tr>
        </table>
  </form>

 </div>
    <div class="modal-footer">
        <button type="button" class="btn-close btn btn-danger" data-dismiss="modal">Close</button>
    </div>

    <script>
 
    $(document).ready(function(){

        $('.infodatastate').click(async function(){
           var buttonID =  $(this).attr('id');
           var highCourtID =  $("#highcourt option:selected").val();
           var faster_id = $('#faster_id').val();
           var agency_or_court = $('#agency_or_court').val();
           var session_user = $('#session_user').val();
           var CSRF_TOKEN_VALUE = await updateCSRFTokenSyncN();
          // alert('hig'+highCourtID+'fid'+faster_id+'agency'+agency_or_court);
             $.ajax({
                url: '<?=base_url()?>/Faster/FasterController/startsendtoFasterWithId',
                type: 'post',
                data: {CSRF_TOKEN: CSRF_TOKEN_VALUE,highCourtID: highCourtID,faster_id: faster_id,agency_or_court: agency_or_court,buttonID: buttonID,session_user: session_user},
                beforeSend: function(){
                    /* Show image container */
                    $("#loader").show();
                },
                success: function(response){
                    // var orderDate = $('#orderDate').val();
                    // var orderDate = $('#orderDate').val();
                    // $.get("<?=base_url()?>/Faster/FasterController/get_send_to_faster",{orderDate:orderDate},function(result){
                    //     // response = $.parseJSON(result);
                    //     $('#tableData').html(result);

                    // });
                    // updateCSRFTokenSync();
                //  window.location.reload();
                    // $('#btnFetchRecords').click();
                    // var orderDate = $('#orderDate').val();
                    // $('#orderDate').val(orderDate);
                    // $('#btnFetchRecords').click();
                    // $('#push-form')[0].reset();
                    // alert(orderDate);
                    window.location.reload();
                    // $('#push-form').submit();
                },
                complete:function(data){
                    /* Hide image container */
                    $("#loader").hide();
                }
            });
           
        });


    });
</script>
  