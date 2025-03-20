
<center><span style="font-weight: bold; color:#4141E0; text-decoration: underline;">OR Uploaded </center>
                    <div class="row g-2">
  <div class="col-sm-3">
    <form id="push_form">
<INPUT TYPE ="date" NAME ='on_date' id = 'on_date' class="form-control" placeholder="AOR Code "> </div>
<INPUT TYPE="button" name='show'  id='or_wise' class="btn btn-primary float-right" value = "submit"> 
</div>
       </form>
 </div>
         <div id="result_data"></div>

<script>
    $('#or_wise').on('click', function () {
        //alert('hi');


            var form_data = $('#push_form').serialize();
            if(form_data){ //alert('readt post form');
                ///var CSRF_TOKEN = 'CSRF_TOKEN';
                //var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                $('.alert-error').hide();
                $.ajax({
                    type: "GET",
                    url: "<?php echo base_url('Reports/Judicial/Report/getORuploded_status'); ?>",
                    data: form_data,
                    beforeSend: function () {
                        $('#orwise').val('Please wait...');
                        $('#orwise').prop('disabled', true);
                    },
                    success: function (data) {
                        //alert(data);
                        $('#orwise').prop('disabled', false);
                        $('#orwise').val('Submit');
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