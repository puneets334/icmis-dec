
            <div class="row">
                <div class="col-12">
 <?php if (!empty($dno_data)) { $sno = 0; ?>

     <div class="row">
         <div class="col-sm-1"></div>
         <div class="col-sm-8" style="text-align: left !important;">
             <p><b class="pdiv">Diary No. : </b> <?=substr($dno_data['diary_no'], 0, -4).'/'.substr($dno_data['diary_no'],-4);?></p>
             <p><b class="pdiv">Cause Title : </b> <?=$dno_data['pet_name'].'  <b>Vs</b>  '.$dno_data['res_name'];?></p>
             <p><b class="pdiv">Registration No. : </b> <?=$dno_data['reg_no_display'];?></p>
             <p><b class="pdiv">Status. :</b> <?php if($dno_data['c_status']=='D'){echo 'Disposed';}else{echo 'Pending';} ?></p>
         </div>

         <br/>
     </div>
            <div class="row">
                <div class="col-sm-1"></div>
                <div class="col-sm-10" style="text-align: left !important;">
                    <h2 class="page-header">List of I.A.(s)/Doc(s)</h2>
                </div>
            </div>
<hr/>
            <div class="row">
                <div class="col-sm-1 pdiv2"></div>
                <div class="col-sm-1 pdiv2">S.No.</div>
                <div class="col-sm-2 pdiv2">Select/Unselect</div>
                <div class="col-sm-2 pdiv2">I.A. No</div>
                <div class="col-sm-5 pdiv2">Remarks/Reason for transfer</div>
            </div>

            <?php
          foreach ($ia_res as $data){
                $sno = $sno + 1;
                ?>
                <br/>

                <div class="row">
                    <div class="col-sm-1"></div>
                    <div class="col-sm-1">
                        <p class="pdiv1"><?= $sno ?></p>
                    </div>
                    <div class="col-sm-2">
                        <input type="checkbox" id="chk" name="chk" value="<?= $sno . '-' . $data['docd_id'] ?>" onclick="active_inactive(<?=$sno?>)">
                    </div>
                    <div class="col-sm-2">
                        <p class="pdiv1">I.A. No. <?= $data['ia'] ?></p>
                    </div>
                    <div class="col-sm-5">
                        <input type="text" size="50" placeholder="Enter Remarks for restoration" name="remark<?php echo $sno; ?>" id="remark_<?php echo $sno; ?>" disabled></input>
                    </div>
                </div>
                <?php }?>

                    <?php if (!empty($ia_res)) { ?>
              <h2 class="page-header">Transfer to Diary No./ Case No.</h2> <hr/>

                    <!--start component_diary_with_case-->
                    <div class="row">
                        <div class="col-md-2 diary_section_action_ia">
                            <div class="form-group clearfix">
                                <div class="icheck-primary d-inline">
                                    <input type="radio" class="search_type_ia" id="search_type_d_ia" name="search_type_ia" value="D"  checked onclick="is_search_type('D')">
                                    <label for="search_type_d_ia">Diary Detail</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 casetype_section_action_ia">
                            <div class="form-group clearfix">
                                <div class="icheck-primary d-inline">
                                    <input type="radio" class="search_type" id="search_type_c_ia" name="search_type_ia" value="C" onclick="is_search_type('C')">
                                    <label for="search_type_c_ia">Case Detail</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-5 diary_section_ia">
                            <div class="form-group row">
                                <label for="diary_number_ia" class="col-sm-5 col-form-label">Diary No</label>
                                <div class="col-sm-7">
                                    <input type="number" class="form-control" id="diary_number_ia" name="diary_number_ia" placeholder="Diary No" >
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5 diary_section_ia">
                            <div class="form-group row">
                                <label for="diary_year_ia" class="col-sm-5 col-form-label">Diary Year</label>
                                <div class="col-sm-7">
                                    <?php $year = 1950;
                                    $current_year = date('Y');
                                    ?>
                                    <select name="diary_year_ia" id="diary_year_ia" class="custom-select rounded-0">
                                        <?php for ($x = $current_year; $x >= $year; $x--) { ?>
                                            <option><?php echo $x; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3 casetype_section_ia">
                            <div class="form-group row">
                                <label for="case_type" class="col-sm-5 col-form-label">Case type</label>
                                <div class="col-sm-7">
                                    <select name="case_type_ia" id="case_type_ia" class="custom-select rounded-0select2" style="width: 100%;">
                                        <option value="">Select case type</option>
                                        <?php $casetype_arrya=get_from_table_json('casetype');
                                        $casetype=array_SORT_ASC_DESC($casetype_arrya,'casecode');
                                        foreach ($casetype as $row) {
                                            echo'<option value="' . sanitize(($row['casecode'])) . '">' . sanitize(strtoupper($row['casename'])) . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                        </div>
                        <div class="col-sm-3 casetype_section_ia">

                            <div class="form-group row ">
                                <label for="case_number_ia" class="col-sm-5 col-form-label">Case No. </label>
                                <div class="col-sm-7">
                                    <input type="number" class="form-control" id="case_number_ia" name="case_number_ia" placeholder="Case No. From" >
                                </div>
                            </div>

                        </div>
                        <div class="col-sm-3 casetype_section_ia">
                            <div class="form-group row">
                                <label for="case_year_ia" class="col-sm-5 col-form-label">Case Year</label>
                                <div class="col-sm-7">
                                    <?php $year = 1950;
                                    $current_year = date('Y');
                                    ?>
                                    <select name="case_year_ia" id="case_year_ia" class="custom-select rounded-0">
                                        <?php for ($x = $current_year; $x >= $year; $x--) { ?>
                                            <option><?php echo $x; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>


                    </div>

                     <div class="row">

                         <div class="col-sm-4"></div>
                         <div class="col-sm-2">
                             <label for="btn">&nbsp</label>
                             <button type="button" id="search" class="form-control btn btn-primary" onclick="fetch_info();">Fetch Info
                         </div>
                     </div>
     <div id="load_information_diary"></div>


     <script>
         search_type_ia();
         function search_type_ia(search_type){
             if (search_type=='C'){
                 $('.casetype_section_action_ia').show();
                 $('.diary_section_action_ia').hide();
                 $('.casetype_section_ia').show();
                 $('.diary_section_ia').hide();
             }else if (search_type=='D'){
                 $('.casetype_section_action_ia').hide();
                 $('.diary_section_action_ia').show();
                 $('.casetype_section_ia').hide();
                 $('.diary_section_ia').show();
             }else {
                 $('.casetype_section_action_ia').show();
                 $('.diary_section_action_ia').show();
                 $('.casetype_section_ia').hide();
                 $('.diary_section_ia').show();
             }
         }
         function is_search_type(search_type){
             if (search_type=='C'){
                 $('.casetype_section_ia').show();
                 $('.diary_section_ia').hide();
                 $('#case_year').prop('selectedIndex', 1);
             }else {
                 $('.casetype_section_ia').hide();
                 $('.diary_section_ia').show();
                 $('#diary_year').prop('selectedIndex', 1);
             }
         }
         function fetch_info() {

             $('#load_information_diary').html('');
          var validationError=true;

             var search_type = $("input[name='search_type_ia']:checked").val();
             if (search_type.length == 0) {
                 alert("Please select case type");
                 validationError = false;
                 return false;
             }
             var diary_number = $("#diary_number_ia").val();
             var diary_year =$('#diary_year_ia :selected').val();

             var case_type =$('#case_type_ia :selected').val();
             var case_number = $("#case_number_ia").val();
             var case_year =$('#case_year_ia :selected').val();

             if (search_type=='D') {
                 if (diary_number.length == 0) {
                     alert("Please enter diary number");
                     $('#diary_number_ia').focus();
                     validationError = false;
                     return false;
                 }else if (diary_year.length == 0) {
                     alert("Please select diary year");
                     $('#diary_year_ia').focus();
                     validationError = false;
                     return false;
                 }
                 var diary_no= $('#diary_number').val()+$('#diary_year').val();
                 var tr_to_diary_no= $('#diary_number_ia').val()+$('#diary_year_ia').val();
                 if((diary_no==tr_to_diary_no)){
                     alert('Both Diary No./Case No. cannot be same');
                     validationError = false;
                     return false;
                 }
             }else if (search_type=='C') {

                 if (case_type.length == 0) {
                     alert("Please select case type");
                     $('#case_type_ia').focus();
                     validationError = false;
                     return false;
                 }else if (case_number.length == 0) {
                     alert("Please enter case number");
                     $('#case_number_ia').focus();
                     validationError = false;
                     return false;
                 }else if (case_year.length == 0) {
                     alert("Please select case year");
                     $('#case_year_ia').focus();
                     validationError = false;
                     return false;
                 }
                 var case_type1 =$('#case_type :selected').val();
                 var case_number1 = $("#case_number").val();
                 var case_year1 =$('#case_year :selected').val();

                 if(case_type1==case_type && case_number1==case_number && case_year1==case_year){
                     alert('Both Diary No./Case No. cannot be same!!');
                     validationError = false;
                     return false;
                 }
             }



             if(validationError){
                 var ia_search = "<?php echo base_url('ARDRBM/IA/get_information_diary'); ?>";
                 var CSRF_TOKEN = 'CSRF_TOKEN';
                 var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                 $.ajax({
                     type: "POST",
                     url: "<?php echo base_url('ARDRBM/IA/get_information_diary'); ?>",
                     data:{
                         search_type: search_type,
                         diary_number: diary_number,
                         diary_year: diary_year,
                         case_type: case_type,
                         case_number: case_number,
                         case_year: case_year,
                         option: 3,
                         CSRF_TOKEN:CSRF_TOKEN_VALUE,
                     },
                     beforeSend: function () {
                         $("#loader").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                     },
                     success: function (data) {
                         updateCSRFToken();
                         $("#loader").html('');
                         //alert(data);
                         $('#load_information_diary').html(data);
                     },
                     error: function() {
                         updateCSRFToken();
                         //alert('Something went wrong! please contact computer cell');
                     }
                 });

             }
         }

     </script>
 <?php } else { ?>
     <div class="alert alert-danger"><strong>Fail!</strong> No I.A.(s)/Doc(s) found.</div>
 <?php }?>

        <?php } else { ?>
            <div class="alert alert-danger"><strong>No record found!</strong></div>
        <?php }?>

                </div>
            </div>
