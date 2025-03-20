
<center><span style="font-weight: bold; color:#4141E0; text-decoration: underline;">SECTION WISE LIST OF MATTERS  - CONSOLIDATED REPORT  </center>
                    <div class="row g-2">
<div class="col-sm-6">
<form id="aor_wise_form">
<INPUT TYPE ="TEXT" NAME ='aorcode' id = 'aorcode' class="form-control" placeholder="AOR Code "> </div>
<INPUT TYPE="button" name='show'  id='aor_wise' class="btn btn-primary float-right" value = "submit"> 
</div>
       </form>
 </div>
         <div id="result_data"></div>

<script>
    $('#aor_wise').on('click', function () {
        //alert('hi');


            var form_data = $('#aor_wise_form').serialize();
            if(form_data){ //alert('readt post form');
                ///var CSRF_TOKEN = 'CSRF_TOKEN';
                //var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                $('.alert-error').hide();
                $.ajax({
                    type: "GET",
                    url: "<?php echo base_url('Reports/Judicial/Report/Aor_wise_matters'); ?>",
                    data: form_data,
                    beforeSend: function () {
                        $('#aor_wise').val('Please wait...');
                        $('#aor_wise').prop('disabled', true);
                    },
                    success: function (data) {
                        //alert(data);
                        $('#aor_wise').prop('disabled', false);
                        $('#aor_wise').val('Submit');
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