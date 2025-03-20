<?=view('header'); ?>
 
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header heading">

                            <div class="row">
                                <div class="col-sm-10">
                                    <h3 class="card-title">Filing</h3>
                                </div>
                                <?=view('Filing/filing_filter_buttons'); ?>
                            </div>
                        </div>
                        <?=view('Filing/filing_breadcrumb'); ?>
                        <!-- /.card-header -->

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header p-2" style="background-color: #fff;"><br>
                                        <h4 class="basic_heading"> Registration Details </h4>
                                        <?php
                                            $attribute = array('class' => 'form-horizontal', 'name' => 'registration', 'id' => 'registration', 'autocomplete' => 'off');
                                            echo form_open('#', $attribute);

                                        ?>
                                    </div><!-- /.card-header -->
                                    <div class="card-body">
                                        <div class="tab-content">

                                            <div class="row">
                                                <div class="col-md-12" id="dv_res1">
                                                    <?php
                                                        if(!empty($res_p_r[0]['c_status'])):
                                                        if($res_p_r[0]['c_status']=='D'){
                                                    ?>
                                                        <div style="text-align: center;color: red;font-weight: bold"><b>Cannot Register matter as Matter is Disposed</b></div>
                                                    <?php
                                                        }
                                                        endif;

                                                        if($res_p_r[0]['c_status']!='D'){ //start c_status condition

                                                        if(!empty($res_p_r[0]['fil_no'])):
                                                        if($res_p_r[0]['fil_no']!=null || $res_p_r[0]['fil_no']!="")
                                                        {
                                                            if((intval(substr($res_p_r[0]['fil_no'],0,2))!=13)&&(intval(substr($res_p_r[0]['fil_no'],0,2))!=14)&&(intval(substr($res_p_r[0]['fil_no'],0,2))!=15)&&(intval(substr($res_p_r[0]['fil_no'],0,2))!=16)&&(intval(substr($res_p_r[0]['fil_no'],0,2))!=31)) {
                                                                ?>
                                                                <div style="text-align: center"><b>Registration already done</b></div>
                                                                <?php
                                                            }
                                                        }
                                                        endif;

                                                        if($res_p_r[0]['fil_no']==null || $res_p_r[0]['fil_no']==""){ //start fil_no condition

                                                        if($category<=0){
                                                            ?>
                                                            <div style="text-align: center"><b>Category not updated!!!</b></div>
                                                            <?php
                                                        }
                                                        else{ //start category condition
                                                        ?>
                                                            <center>
                                                                <div class="cl_center" style="color: red;font-weight: bold"><?php 
																		if(!empty($res_casetype_added[0]['short_description'])) 
																		{
																			echo "Is ".$res_casetype_added[0]['short_description']." ?" ;
																		}else{
																				echo '';
																		}																				?>
                                                                    <input type='checkbox' name="casetype" id="casetype" value="<?php echo $res_p_r[0]['casetype_id'] ?? '';?>" required>
                                                                </div>
                                                            </center><br>
                                                            <input type="hidden" value="<?php echo $res_p_r[0]['casetype_id'];?>" id="hd_casetype_id">

                                                        <?php
                                                        if($res_p_r[0]['casetype_id']=='5' || $res_p_r[0]['casetype_id']=='6' || $res_p_r[0]['casetype_id']=='17'
                                                            || $res_p_r[0]['casetype_id']=='24' || $res_p_r[0]['casetype_id']=='32' || $res_p_r[0]['casetype_id']=='33' || $res_p_r[0]['casetype_id']=='34'
                                                            || $res_p_r[0]['casetype_id']=='35' || $res_p_r[0]['casetype_id']=='27' || $res_p_r[0]['casetype_id']=='40' || $res_p_r[0]['casetype_id']=='41')
                                                        {
                                                            if($res_ck_def[0]['count']>0){
                                                            ?>
                                                            <div style="text-align: center"><b>Please remove defects before generating Registration No.</b></div>
                                                            <?php
                                                            }
                                                            else{

                                                                if($check_ia){

                                                                    $ia_details='';
                                                                    foreach($check_ia as $check_ia_val):

                                                                        $others='';
                                                                        if($check_ia_val['other1']!=''){
                                                                            $others='- '.$check_ia_val['other1'];
                                                                        }
                                                                        if($ia_details==''){
                                                                            $ia_details=$check_ia_val['docnum'].'/'.$check_ia_val['docyear'].'- '.$check_ia_val['docdesc'].$others;
                                                                        }
                                                                        else{
                                                                            $ia_details=$ia_details.', '.$check_ia_val['docnum'].'/'.$check_ia_val['docyear'].'- '.$check_ia_val['docdesc'].$others;
                                                                        }
                                                                    endforeach;
                                                                    ?>
                                                                        <div class="cl_center"><center><b>Can't register case because IA(s) <span style="color:red"><?php echo $ia_details  ?></span> is still pending.</b></center></div>
                                                                    <?php

                                                                }else{
                                                                    ?>

                                                                    <div style="text-align: center">
                                                                        <h3 style="font-size:20px;"><b><?php echo $get_causetitle; ?></b></h3>
                                                                        <input type="hidden" name="hd_casetype_id" id="hd_casetype_id" value="<?php echo $res_p_r[0]['casetype_id']; ?>"/>
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <center>
                                                                            <label class="col-sm-2 col-form-label"><b>Court/Registration Order Date</b></label>
                                                                            <div class="col-sm-2">
                                                                                <input type="date" name="txt_order_dt" id="txt_order_dt" class="form-control" <?php if($order_date!=''){?>value="<?php echo date('Y-m-d',strtotime($order_date));?>" disabled <?php }?>>
                                                                            </div>
                                                                        </center>
                                                                    </div>

                                                                    <?php if($order_date!=''){ ?>
                                                                        <div style="text-align: center;margin-top: 20px">
                                                                            <font style="color: darkgreen;font-weight: bold">
                                                                                Note: If actual order date doesn't match with the date displayed above, Please update Previous Court Remarks in the actual Order date.
                                                                            </font>
                                                                        </div>
                                                                    <?php } ?>

                                                                    <div class="col-md-12">
                                                                        <center><input type="button" name="btn_generate_s" id="btn_generate_s" class="btn btn-info" value="Generate"></center>
                                                                    </div>

                                                                <?php
                                                                }
                                                            }
                                                        }
                                                        else{

                                                            if($get_lowerct){

                                                                if($res_ck_def[0]['count']>0){
                                                                ?>
                                                                <div style="text-align: center"><b>Please remove defects before generating Registration No.</b></div>
                                                                <?php
                                                                }
                                                                else{
                                                                    ?>
                                                                    <div style="text-align: center">
                                                                        <h3 style="font-size:20px;"><b><?php echo $get_causetitle; ?></b></h3>
                                                                        <input type="hidden" name="hd_casetype_id" id="hd_casetype_id" value="<?php echo $res_p_r[0]['casetype_id']; ?>"/>
                                                                    </div>

                                                                    <table id="example1" class="table table-hover showData">
                                                                        <thead>
                                                                          <tr>
                                                                            <th>S.No.</th>
                                                                            <th>Court</th>
                                                                            <th>State</th>
                                                                            <th>Bench</th>
                                                                            <th>Case No.</th>
                                                                            <th>Order Date</th>
                                                                            <?php
                                                                                if($res_p_r[0]['casetype_id']=='7' || $res_p_r[0]['casetype_id']=='8'){
                                                                            ?>
                                                                                <th>Transfer To</th>
                                                                            <?php
                                                                                }else{
                                                                            ?>
                                                                                <th>Judgement Challenged</th>
                                                                            <?php
                                                                                }
                                                                            ?>
                                                                            
                                                                          </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                          <?php
                                                                              $sno=0;
                                                                              $countchallange=0;
                                                                              foreach($get_lowerct as $get_lowerct_val):
                                                                          ?>  
                                                                              <tr>
                                                                                <td><?php echo $sno+1; ?></td>
                                                                                <td>
                                                                                    <?php
                                                                                        if($get_lowerct_val['ct_code']=='1'){
                                                                                            echo "High Court";
                                                                                        }
                                                                                        else if($get_lowerct_val['ct_code']=='2'){
                                                                                            echo "Other";
                                                                                        }
                                                                                        else if($get_lowerct_val['ct_code']=='3'){
                                                                                            echo "District Court";
                                                                                        }
                                                                                        else if($get_lowerct_val['ct_code']=='4'){
                                                                                            echo "Supreme Court";
                                                                                        }
                                                                                        else if($get_lowerct_val['ct_code']=='5'){
                                                                                            echo "State Agency";
                                                                                        }
                                                                                    ?>
                                                                                </td>
                                                                                <td>
                                                                                    <?php
                                                                                        echo $get_lowerct_val['name'];
                                                                                    ?>
                                                                                    <input type="hidden" name="hd_lower_id<?php echo $sno; ?>" id="hd_lower_id<?php echo $sno; ?>" value="<?php echo $get_lowerct_val['lower_court_id'];  ?>"/>
                                                                                </td>
                                                                                <td><?php echo $get_lowerct_val['agency_name'];?></td>
                                                                                <td><?php echo $get_lowerct_val['type_sname'].'-'.$get_lowerct_val['lct_caseno'].'-'.$get_lowerct_val['lct_caseyear'];?></td>
                                                                                <td>
                                                                                    <span id="sp_lct_dec_dt<?php echo $sno; ?>"><?php if($get_lowerct_val['lct_dec_dt']==NULL) {echo '-';} else { echo date('d-m-Y',strtotime($get_lowerct_val['lct_dec_dt']));} ?></span>
                                                                                </td>
                                                                                <td>
                                                                                <?php if($res_p_r[0]['casetype_id']=='7' || $res_p_r[0]['casetype_id']=='8'){ ?>    
                                                                                    <?php if(!empty($get_lowerct_val['transfer_court'])): ?>
                                                                                    <span class="cl_chk_jug_clnged" id="chk_jug_clnged<?php echo $sno; ?>"><?php if($get_lowerct_val['transfer_court']==0) { echo "-";} else { echo $get_lowerct_val['res_court'] ;} ?> /<?php if($get_lowerct_val['transfer_state']==0) { echo '-';} else { echo $get_lowerct_val['res_state']; } ?> / <?php if($get_lowerct_val['transfer_district']==0) { echo '-';} else { echo $get_lowerct_val['res_district'] ;} ?></span>
                                                                                    <?php endif; ?>
                                                                                    <?php if($get_lowerct_val['is_order_challenged']=='Y') { $countchallange++; ?>  <?php } ?>
                                                                                <?php }else{ ?>

                                                                                    <!-- else part -->
                                                                                    <input type="checkbox" name="chk_jug_clnged<?php echo $sno; ?>" id="chk_jug_clnged<?php echo $sno; ?> "
                                                                                    <?php if($get_lowerct_val['is_order_challenged']=='Y') { $countchallange++; ?> checked="checked" <?php ;} ?> class="cl_chk_jug_clnged" disabled/>
                                                                                    <!-- else part -->
                                                                                <?php } ?>

                                                                                </td>
                                                                              </tr>
                                                                          <?php $sno++; endforeach; ?>
                                                                        </tbody>
                                                                      </table>

                                                                      <div style="text-align: center;margin-top: 20px">

                                                                        <?php
                                                                                if($check_ia){

                                                                                    $ia_details='';
                                                                                    foreach($check_ia as $check_ia_val):

                                                                                        $others='';
                                                                                        if($check_ia_val['other1']!=''){
                                                                                            $others='- '.$check_ia_val['other1'];
                                                                                        }
                                                                                        if($ia_details==''){
                                                                                            $ia_details=$check_ia_val['docnum'].'/'.$check_ia_val['docyear'].'- '.$check_ia_val['docdesc'].$others;
                                                                                        }
                                                                                        else{
                                                                                            $ia_details=$ia_details.', '.$check_ia_val['docnum'].'/'.$check_ia_val['docyear'].'- '.$check_ia_val['docdesc'].$others;
                                                                                        }
                                                                                    endforeach;
                                                                                    ?>
                                                                                        <div class="cl_center"><b>Can't register case because IA(s) <span style="color:red"><?php echo $ia_details  ?></span> is still pending.</b></div>
                                                                                    <?php

                                                                                    $ia_pending=1;
                                                                                }
                                                                                else{ //start check_ia condition
                                                                            ?>

                                                                            <?php

                                                                                if($countchallange<=0){
                                                                                echo "<font style='color: red;font-weight: bold'>No. Previous Court details are challenged.Please Check Previous Court Details !!!!</font>";
                                                                                }
                                                                                else{
                                                                                    ?>

                                                                                    <b><font style="color: red;font-weight: bold">Total registration no. to be generated <?php if($countchallange<=1) echo "is"; else echo "are"; ?> &nbsp;<?php echo $countchallange;?>&nbsp;?</font>&nbsp;&nbsp;
                                                                                        <input type='checkbox' name='regnocount' id='regnocount' value='$totalnogen' required></b>

                                                                                <?php    
                                                                                }
                                                                            ?>

                                                                                <div class="form-group">
                                                                                    <center>
                                                                                        <label class="col-sm-2 col-form-label"><b>Court/Registration Order Date</b></label>
                                                                                        <div class="col-sm-2">
                                                                                            <input type="date" name="txt_order_dt" id="txt_order_dt" class="form-control" <?php if($order_date!=''){?>value="<?php echo date('Y-m-d',strtotime($order_date));?>" disabled <?php }?>>
                                                                                        </div>
                                                                                    </center>
                                                                                </div>

                                                                                <?php if($order_date!=''){ ?>
                                                                                    <div style="text-align: center;margin-top: 20px">
                                                                                        <font style="color: darkgreen;font-weight: bold">
                                                                                            Note: If actual order date doesn't match with the date displayed above, Please update Previous Court Remarks in the actual Order date.
                                                                                        </font>
                                                                                    </div>
                                                                                <?php } ?>

                                                                                <div style="text-align: center;margin-top: 20px">
                                                                                    <input type="button" name="btn_generate_r" id="btn_generate_r" name="" class="btn btn-info" value="Generate">
                                                                                </div>

                                                                            <?php } //end check_ia condition ?>

                                                                      </div>

                                                                    <?php
                                                                }

                                                            }
                                                            elseif(($res_p_r[0]['casetype_id']=='19') || ($res_p_r[0]['casetype_id']=='20')){
                                                            ?>

                                                            <br>
                                                                <div style="text-align: center" ><b>In case of Criminal Contempt under Section 2(C) of Contempt of Courts Act 1971 <br><center> OR</center> In case of Criminal Contempt, on Certificate of Atorney General or Solicitor General</b> <input type='checkbox' id='direct' onClick="f()">
                                                                </div><br>

                                                                <input size="1"  value= '1' type="hidden" name="num" id="num" >
                                                                <div class="form-group">
                                                                    <center>
                                                                        <label class="col-sm-2 col-form-label"><b>Court/Registration Order Date</b></label>
                                                                        <div class="col-sm-2">
                                                                            <input type="date" name="txt_order_dt" id="txt_order_dt" class="form-control" <?php if($order_date!=''){?>value="<?php echo date('Y-m-d',strtotime($order_date));?>" disabled <?php }?>>
                                                                        </div>
                                                                    </center>
                                                                </div>

                                                                <div style="text-align: center;margin-top: 20px">
                                                                    <input type="button" name="btn_generate" id="btn_generate_r" name="" class="btn btn-info" value="Generate">
                                                                </div>  

                                                            <?php
                                                            }
                                                            else{
                                                                ?>

                                                                <div style="text-align: center" ><b>No. Previous Court details are challenged!</b></div>

                                                            <?php
                                                            } //end c_status condition
                                                            } //end fil_no condition
                                                            } //end category condition
                                                            }  
                                                        }
                                                    ?>
                                                </div>
                                            </div>

                                            <hr><br>

                                        </div>
                                        <!-- /.tab-content -->
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                                <!-- /.card -->
                            </div>
                        </div>


                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
    </section>
    <!-- /.content -->


    <!-- modal start -->
    <div class="modal fade" id="modal-default">
            <div class="modal-dialog modal-xl">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <div id="res"></div>
                </div>
                <div class="modal-footer justify-content-between">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
              </div>
              <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
          </div>
          <!-- /.modal -->
    <!-- modal end -->





<script type="text/javascript">
    function updateCSRFToken() {
        $.getJSON("<?php echo base_url('Csrftoken'); ?>", function (result) {
            $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
        });
    }

    $(document).on('click','#btn_generate_r',function(){

        if($(this).attr('name') == 'btn_generate'){
            var num=document.getElementById('num').value;
        }else{
            var num=0;
        }
        
        var chk_clk=0;
        $reg_chk=0;

        var hd_casetype_id=$('#hd_casetype_id').val();

        if(!$('#casetype').is(':checked')){
            alert("Please Check Case Type");
            $('#casetype').focus();
            exit();
        }

        if(hd_casetype_id=='5'||hd_casetype_id=='27'  || hd_casetype_id=='20'  || hd_casetype_id=='19'||hd_casetype_id=='6'||hd_casetype_id=='17'||hd_casetype_id=='24'||hd_casetype_id=='32'||hd_casetype_id=='33'||hd_casetype_id=='34'||hd_casetype_id=='35' || hd_casetype_id=='40'||hd_casetype_id=='41' )
        {
            $reg_chk=1;
        }

        if((!$('#regnocount').is(':checked'))&& $reg_chk==0 ){
            alert("Please Confirm total registration no. to be generated");
            $('#regnocount').focus();
            exit();
        }

        var txt_order_dt = $('#txt_order_dt').val();

        if(txt_order_dt==''){
            alert("Please enter registeration order date");
            $('#txt_order_dt').focus();
            exit();
        }
        else{
            var txt_order_dt1 = moment(txt_order_dt).format('DD-MM-YYYY');
            compareDate(txt_order_dt1);
        }

        if(hd_casetype_id==7 || hd_casetype_id==8  || hd_casetype_id==11 || hd_casetype_id==12 || hd_casetype_id==19 || hd_casetype_id==20 ){

            chk_clk=1;
        }else{

            $('.cl_chk_jug_clnged').each(function(){

              if($(this).is(':checked'))
                {
                      chk_clk=1;

                }
            });
        }

        if(chk_clk==0){
            alert("Atleast one judgement should be challenged before registration");
        }
        else{

            var confirmation = confirm("Are you sure you want to register case");

            if (confirmation == false) {
                return false;
            }else{
                //$('#btn_generate_r').attr('disabled', true);
                var fn_val = '';

                $('.cl_chk_jug_clnged').each(function () {

                    var chk_jug_clnged = $(this).attr('id');
                    var sp_jug_clnged = chk_jug_clnged.split('chk_jug_clnged');
                    var hd_lower_id = $('#hd_lower_id' + sp_jug_clnged[1]).val();
                    var ck_chd = 'N';

                    if (hd_casetype_id == 7 || hd_casetype_id == 8 ||

                    hd_casetype_id == 11 || hd_casetype_id == 12) {
                    ck_chd = 'Y';
                    }
                    else {
                        if ($(this).is(':checked')) {
                            ck_chd = 'Y';
                        }
                    }
                    if (fn_val == '')
                    {
                        fn_val = hd_lower_id + '!' + ck_chd;
                    }
                   
                    else
                    {
                        fn_val = fn_val + '@' + hd_lower_id + '!' + ck_chd;
                    }

                });

                var CSRF_TOKEN = 'CSRF_TOKEN';
                var csrf = $("input[name='CSRF_TOKEN']").val();

                $.ajax({
                    url:"<?php echo base_url('Filing/Registration/register_case/');?>",
                    type: "post",
                    data: {CSRF_TOKEN:csrf,fn_val: fn_val,hd_casetype_id: hd_casetype_id,txt_order_dt: txt_order_dt,num: num },
                    success:function(result){
                        
                        var obj = JSON.parse(result);

                        updateCSRFToken();

                        if(hd_casetype_id==9||hd_casetype_id==10||hd_casetype_id==19||hd_casetype_id==20||hd_casetype_id==25||hd_casetype_id==26||hd_casetype_id==39){

                            setTimeout(() => {
                                find_and_set_da()
                            }, 150);
                        }
                        else{
                            setTimeout(() => {
                                check_if_listed()
                            }, 150);
                        }

                        if(obj.track_inserted){
                            var track_inserted_msg = obj.track_inserted+'<br>';
                        }else{
                            var track_inserted_msg = '';
                        }

                        $('#dv_res1').html('<center><b>'+obj.registration+'<br>'+track_inserted_msg+obj.err_msg+'</b></center>');
                        
                        updateCSRFToken();
                    },
                    error: function () {
                        updateCSRFToken();
                    }
                });
            }

        }

    });

    
    $(document).on('click','#btn_generate_s',function(){

        var hd_casetype_id=$('#hd_casetype_id').val();
        var txt_order_dt = $('#txt_order_dt').val();

        $reg_chk=0;

        if(!$('#casetype').is(':checked')){
            alert("Please Check Case Type");
            $('#casetype').focus();
            exit();
        }

        if(hd_casetype_id=='5'||hd_casetype_id=='27'  || hd_casetype_id=='6'||hd_casetype_id=='17'||hd_casetype_id=='24'||hd_casetype_id=='32'||hd_casetype_id=='33'||hd_casetype_id=='34'||hd_casetype_id=='35' || hd_casetype_id=='40'||hd_casetype_id=='41' )
        {
            $reg_chk=1;
        }

        if((!$('#regnocount').is(':checked'))&& $reg_chk==0 ){
            alert("Please Confirm total registration no. to be generated");
            $('#regnocount').focus();
            exit();
        }

        if(txt_order_dt==''){
            alert("Please enter registeration order date");
            $('#txt_order_dt').focus();
            exit();
        }
        else{
            var txt_order_dt1 = moment(txt_order_dt).format('DD-MM-YYYY');
            compareDate(txt_order_dt1);
        }


        var confirmation = confirm("Are you sure you want to register case");

        if (confirmation == false) {
            return false;
        }else{
            //$('#btn_generate_s').attr('disabled', true);

            var CSRF_TOKEN = 'CSRF_TOKEN';
            var csrf = $("input[name='CSRF_TOKEN']").val();

            $.ajax({
                url:"<?php echo base_url('Filing/Registration/register_case_supreme/');?>",
                type: "post",
                data: {CSRF_TOKEN:csrf,hd_casetype_id: hd_casetype_id,txt_order_dt: txt_order_dt },
                success:function(result){
                    
                    var obj = JSON.parse(result);

                    updateCSRFToken();

                    if(hd_casetype_id==9||hd_casetype_id==10||hd_casetype_id==19||hd_casetype_id==20||hd_casetype_id==25||hd_casetype_id==26||hd_casetype_id==39){

                        setTimeout(() => {
                            find_and_set_da()
                        }, 150);
                    }
                    else{
                        setTimeout(() => {
                            check_if_listed()
                        }, 150);
                    }

                    if(obj.track_inserted){
                        var track_inserted_msg = obj.track_inserted+'<br>';
                    }else{
                        var track_inserted_msg = '';
                    }

                    $('#dv_res1').html('<center><b>'+obj.registration+'<br>'+track_inserted_msg+obj.err_msg+'</b></center>');

                    updateCSRFToken();
                },
                error: function () {

                    updateCSRFToken();
                }
            });
        }

    });


    function compareDate(txt_order_dt){

        var date = txt_order_dt.substring(0, 2);
        var month = txt_order_dt.substring(3, 5);
        var year = txt_order_dt.substring(6, 10);

        var dateToCompare = new Date(year, month - 1, date);
        var currentDate = new Date();

        if (dateToCompare > currentDate) {
            alert("Registration Order date cannot be greater than Today's Date ");
            $('#txt_order_dt').focus();
            exit();
        }
    }

    function check_if_listed(){

        var CSRF_TOKEN = 'CSRF_TOKEN';
        var csrf = $("input[name='CSRF_TOKEN']").val();

        $.ajax({
            url:"<?php echo base_url('Filing/Registration/check_listing/');?>",
            type: "post",
            data: {CSRF_TOKEN:csrf},
            success:function(msg){

                updateCSRFToken();
                
                if(msg=="listed"){
                    setTimeout(() => {
                        find_and_set_da('check_is_listed')
                    }, 150);
                }
                 else{
                    alert(msg);
                }
                //updateCSRFToken();
            },
            error: function () {
                //updateCSRFToken();
            }
        });
    }

    function find_and_set_da(listed_cond){

        var CSRF_TOKEN = 'CSRF_TOKEN';
        var csrf = $("input[name='CSRF_TOKEN']").val();

        $.ajax({
            url:"<?php echo base_url('Filing/Registration/find_and_set_da/');?>",
            type: "post",
            data: {CSRF_TOKEN:csrf},
            success:function(msg){
                
                alert(msg);
                updateCSRFToken();

                if(listed_cond=='check_is_listed'){

                }else{

                    setTimeout(() => {
                        call_prop_s()
                    }, 150);
                }
                //updateCSRFToken();
            },
            error: function () {
                updateCSRFToken();
            }
        });
    }

    function call_prop_s(){
        
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var csrf = $("input[name='CSRF_TOKEN']").val();

        $.ajax({
            url:"<?php echo base_url('Filing/Registration/show_proposal/');?>",
            type: "post",
            data: {CSRF_TOKEN:csrf},
            success:function(msg){
                
                $('#modal-default').modal('toggle');
                $('#res').html(msg);
                updateCSRFToken();
            },
            error: function () {
                updateCSRFToken();
            }
        });
    }

</script>
