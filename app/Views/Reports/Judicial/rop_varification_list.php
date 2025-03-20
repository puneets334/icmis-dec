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
<br>
<form name="frm" id="daily_remarks_form" class="form-inline">
      <input type="hidden" id="curr_date" value="<?php echo date('Y-m-d');?>"/>

    <div class="row g-3">
    
  <div class="col">    
            <input type="hidden" name="caseno" id="caseno">
            <input type="hidden" name="t_cs" id="t_cs">
            <input type="hidden" name="uid" id="uid" value="<?php //echo $_SESSION['userid']; ?>" >
            <input type="hidden" name="sid" id="sid" value="" >
            <input type="hidden" name="flnm" id="flnm" value="" >

                     
<select name="courtno" id="courtno" style="" class="form-control">
                                       <option value="">Court No.</option>
                                       <option value="1">Hon'ble Court No.1</option>
                                       <option value="2">Hon'ble Court No.2</option>
                                       <option value="3">Hon'ble Court No.3</option>
                                       <option value="4">Hon'ble Court No.4</option>
                                       <option value="5">Hon'ble Court No.5</option>
                                       <option value="6">Hon'ble Court No.6</option>
                                       <option value="7">Hon'ble Court No.7</option>
                                       <option value="8">Hon'ble Court No.8</option>
                                       <option value="9">Hon'ble Court No.9</option>
                                       <option value="10">Hon'ble Court No.10</option>
                                       <option value="11">Hon'ble Court No.11</option>
                                       <option value="12">Hon'ble Court No.12</option>
                                       <option value="13">Hon'ble Court No.13</option>
                                       <option value="14">Hon'ble Court No.14</option>
                                       <option value="15">Hon'ble Court No.15</option>
                                        <option value="16">Hon'ble Court No.16</option>
                                        <option value="17">Hon'ble Court No.17</option>
                                        <option value="31">Hon'ble Virtual Court No.1</option>
                                        <option value="32">Hon'ble Virtual Court No.2</option>
                                        <option value="33">Hon'ble Virtual Court No.3</option>
                                        <option value="34">Hon'ble Virtual Court No.4</option>
                                        <option value="35">Hon'ble Virtual Court No.5</option>
                                        <option value="36">Hon'ble Virtual Court No.6</option>
                                        <option value="37">Hon'ble Virtual Court No.7</option>
                                        <option value="38">Hon'ble Virtual Court No.8</option>
                                        <option value="39">Hon'ble Virtual Court No.9</option>
                                        <option value="40">Hon'ble Virtual Court No.10</option>
                                       <option value="101">Chamber</option>
                                       <option value="102">Registrar</option>
                   
                                        </select>                           
                                        </div>OR
<div class="col"> 
                                <select name="aw1" id="judge_name" style="" class="form-control"> 
                                <option value=""> Judge Name </option>
                                <?php
                                foreach($judges as $judge)://print_r($judge);
                                ?>
                                <option value="<?= $judge['jcode']?>"> <?= $judge['jname'] ?></option>
                                <?php
                                endforeach
                                ?>
                                </select> 
</div>
<div class="col" style="margin-top: -30px;"> 
                       
                               <label class="d-inline">Cause List Date :  </label>
                                <input style="width: -webkit-fill-available;" class="d-inline form-control" type="date" value="<?php //print $dtd; ?>" name="dtd" id="dtd" size="10" ></td>
                                <!--<input type="text" value="<?php //print $dtd; ?>" name="dtd" id="dtd" size="10" style="font-family:verdana; font-size:9pt;" readonly="readonly" ></td>-->
                                
</div>
<div class="col"> 

<input type="button" id="daily_remarks" value="Submit" class="btn btn-primary float-right" style="margin-top:-2px"></div>
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

           

    $('#daily_remarks').on('click', function () {
        //alert('hi');


            var form_data = $('#daily_remarks_form').serialize();
            if(form_data){ //alert('readt post form');
                var CSRF_TOKEN = 'CSRF_TOKEN';
                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                $('.alert-error').hide();
                $.ajax({
                    type: "GET",
                    url: "<?php echo base_url('Reports/Judicial/Report/Elimination_list'); ?>",
                    data: form_data,
                    beforeSend: function () {
                        $('#daily_remarks').val('Please wait...');
                        $('#daily_remarks').prop('disabled', true);
                    },
                    success: function (data) {
                        //alert(data);
                        $('#daily_remarks').prop('disabled', false);
                        $('#daily_remarks').val('Submit');
                        $("#result_data").html(data);

                        updateCSRFToken();
                    },
                    error: function () {
                        updateCSRFToken();
                    }

                });
                return false;
            }
    });

</script>

           