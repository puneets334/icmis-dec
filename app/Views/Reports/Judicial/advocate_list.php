
<center><span style="font-weight: bold; color:#4141E0; text-decoration: underline;">Cases Verified By Monitoring Team</center>
                    <div class="row g-2">
  <div class="col-sm-3">
  <form method="post" id="advocate_list_form">

  To be Listed On
  <INPUT TYPE ="date" NAME ='ldate' id = 'ldate' class="form-control" placeholder="AOR Code "> </div>

  <INPUT TYPE="button" name='show'  id='adv_list' class="btn btn-primary float-right" value = "submit" style="margin-top: 20px;"> 

  
</form>
 </div>
</div>
         <div id="result_data"></div>

<script>
    $('#adv_list').on('click', function () {
        //alert('hi');


            var form_data = $('#advocate_list_form').serialize();
            if(form_data){ //alert('readt post form');
                ///var CSRF_TOKEN = 'CSRF_TOKEN';
                //var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                $('.alert-error').hide();
                $.ajax({
                    type: "GET",
                    url: "<?php echo base_url('Reports/Judicial/Report/get_advocate_list'); ?>",
                    data: form_data,
                    beforeSend: function () {
                        $('#adv_list').val('Please wait...');
                        $('#adv_list').prop('disabled', true);
                    },
                    success: function (data) {
                        //alert(data);
                        $('#adv_list').prop('disabled', false);
                        $('#adv_list').val('Submit');
                        $("#result_data").html(data);

                        //updateCSRFToken();
                    },
                    error: function () {
                        //updateCSRFToken();
                    }

                });
                return false;
            }
    });

</script>



<!-- DEBUG-VIEW ENDED 1 APPPATH/Views/Reports/court/gist_module_search_view.php -->
</div>

   </div>