
<!-- <center><span style="font-weight: bold; color:#4141E0; text-decoration: underline;">Defect Report  </center> -->
<div class="form-check">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input class="form-check-input" type="radio" name="reg_or_def" value="rd_dm" checked>
<label class="form-check-label">Defect Reports</label>
</div>
<br>
<div class="row g-2">
<div class="col-sm-3">
<form id="defectreport_form">
<INPUT TYPE ="Date" NAME ='on_date' id = 'on_date' class="form-control" placeholder="AOR Code "> </div>
<INPUT TYPE="button" name='show'  id='defectreport' class="btn btn-primary float-right" value = "submit"> 
</div>
       </form>
 </div>
         <div id="result_data"></div>

<script>
    $('#defectreport').on('click', function () {
        //alert('hi');
            var form_data = $('#defectreport_form').serialize();
            if(form_data){ //alert('readt post form');
                ///var CSRF_TOKEN = 'CSRF_TOKEN';
                //var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                $('.alert-error').hide();
                $.ajax({
                    type: "GET",
                    url: "<?php echo base_url('Reports/Filing/Filing_Reports/get_user_defect_reports'); ?>",
                    data: form_data,
                    beforeSend: function () {
                        $('#defectreport').val('Please wait...');
                        $('#defectreport').prop('disabled', true);
                    },
                    success: function (data) {
                        //alert(data);
                        $('#defectreport').prop('disabled', false);
                        $('#defectreport').val('Submit');
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