<style>
fieldset {
  background-color: #eeeeee;
}

legend {
  /* background-color: gray; */
  /* color: white; */
  padding: 5px 10px;
}

input {
  margin: 5px;
}
</style>
<!-- <title>Report of Work Done of DA | INTEGRATED CASE MANAGEMENT INFORMATION SYSTEM</title> -->
<br>
<form method="post" action="" id="work_done_form" class="form-inline">
                <div id="dv_content1">
            <div style="text-align: center;font-weight: bold">
                REPORT OF WORK DONE OF DA 
                <span style="color: #35b9cd">
                FOR ALL DA    
                </span>
                FOR THE DATE OF <input type="date" id="date_for" size="10" value="" name="date_for" class="form-control">
                <select name="ddl_all_blank" id="ddl_all_blank" class="form-control">
                    <option value="1">All</option>
                    <option value="2">Blank</option>
                    <option value="3">Atleast One</option>
                </select>
                <input type="button"  id="work_done" value="Submit" class="btn btn-primary float-right">
            </div>
        <div id="result_main">
           
            
        </div>
        </div>
        </form>
<!-- <input type="button" id="daily_remarks" value="Submit" class="btn btn-primary float-right" style="margin-top:-2px"></div> -->
                    <!--<div id="messagepost" style="position:absolute; top:110px; right:10px;" align="right"><a href='#' onClick="call_mg();" alt="Message to Display Board"><img src="../images/chat.png"/></a></div>-->    
<!--                    <div id="intabdiv3" style="display:none; width:100%;">
                        <table border="0">
                            <tr>
                                <td valign="top"><b>Message</b></td>
                                <td><textarea name="msgbox" id="msgbox" rows="1" cols="80"></textarea></td>
                                <td align="center" valign="top">
                                    <input type="button" style="width:80px;" name="bt1" id="bt1" value="Send" onClick="return save_r1(0)">
                                    <input type="button" style="width:130px;" name="btnClearMsg" id="btnClearMsg" value="Clear Message" onClick="return save_r1(1)">
                                    <input type="button" name="bt2" id="bt2" value="Cancel" style="width:80px;" onClick="call_mg();"></td>
                            </tr>
                        </table>
                    </div>-->
                </div>
                </form>

                <div id="result_data"></div>

                                </div>
                                </div>
                <script>
                    $('#courtno').change(function(){
                       $('#judge_name').html('<option value=""> Judge Name </option>');
                    });
                    $('#judge_name').change(function(){
                       $('#courtno').html('<option value=""> Court No. </option>');
                    });

    $('#work_done').on('click', function () {
        //alert('hi');

            var form_data = $('#work_done_form').serialize();
            if(form_data){ //alert('readt post form');
                var CSRF_TOKEN = 'CSRF_TOKEN';
                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                $('.alert-error').hide();
                $.ajax({
                    type: "GET",
                    url: "<?php echo base_url('Reports/Judicial/Report/work_done'); ?>",
                    data: form_data,
                    beforeSend: function () {
                        $('#work_done').val('Please wait...');
                        $('#work_done').prop('disabled', true);
                    },
                    success: function (data) {
                        //alert(data);
                        $('#work_done').prop('disabled', false);
                        $('#work_done').val('Submit');
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

           