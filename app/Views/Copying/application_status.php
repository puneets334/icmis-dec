<?= view('header') ?>
 
<style>
    .item {
        border: 1px solid #eee;
        box-shadow: 0 0 10px -3px #ccc;
        border-radius: 5px;
        margin-bottom: 30px;
        padding: 25px;
    }
</style>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">

                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Application Status </h3>
                            </div>
                        </div>
                    </div>
                    <?= view('Copying/copying_registration_breadcrum'); ?>
                    <div class="card-header p-2" style="background-color: #fff; border-bottom:none;">
                        <h4 class="basic_heading">Application Status </h4>
                    </div>

                    <form class="form-horizontal" id="push-form" method="post" action="<?php echo base_url('Copying/Copying/application_status');?>">
                        <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card-primary">
                                    <div class="card-body">
                                        <span class="alert-danger"><?= \Config\Services::validation()->listErrors() ?></span>

                                        <?php if (session()->getFlashdata('error')) { ?>
                                            <div class="alert alert-danger">
                                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                                <strong> <?= session()->getFlashdata('error') ?></strong>
                                            </div>

                                        <?php } ?>
                                        <?php if (session()->getFlashdata('success_msg')) : ?>
                                            <div class="alert alert-success alert-dismissible">
                                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                                <strong> <?= session()->getFlashdata('success_msg') ?></strong>
                                            </div>
                                        <?php endif; ?>
                                        <span id="show_error" class="ml-4 mr-4"></span> <!-- This Segment Displays The Validation Rule -->
                                        <div class="row">
                                        <div class="col-sm-2"></div>
                                            <div class="col-sm-4">
                                                <div class="form-group row">
                                                    <label for="From" class="col-sm-4 col-form-label"> Application Number</label>
                                                    <div class="col-sm-8">
                                                        <select class="select2bs4" name="category" style="width: 100%;" id="category" data-placeholder="Select Category">
                                                            <?php
                                                            foreach ($copy_category as $category) {
                                                                if($category['code']==$category_view){
                                                                    echo '<option value="'.$category['code'].'" selected>'.$category['code'].'</option>';
                                                                }
                                                                else{
                                                                    echo '<option value="'.$category['code'].'">'.$category['code'].'</option>';
                                                                }?>
                                                            
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="col-sm-2">
                                                <div class="form-group row">

                                                    <input type="text" class="form-control" placeholder="Application Number" id="app_no" name="app_no" value="<?php echo $app_no;?>">

                                                </div>
                                            </div>&nbsp;&nbsp;
                                            <div class="col-sm-2">
                                                <div class="form-group row">

                                                    <select class="select2bs4" name="year" style="width: 100%;" id="year" data-placeholder="Select Year">
                                                        <?php
                                                        for ($i = date('Y'); $i > 1950; $i--) {
                                                            if($year == $i){
                                                                echo '<option value="'.$i.'" selected>'.$i.'</option>';
                                                            }else{
                                                                echo '<option value="'.$i.'">'.$i.'</option>';
                                                            }
                                                        } ?>
                                                    </select>

                                                </div>
                                            </div>
                                            <div class="col-sm-2"></div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                            </div>
                                            <div class="col-sm-6">
                                                <span class="input-group-append">
                                                    <input type="submit" name="view" id="view" onclick="return check();" class="application_search btn btn-primary" value="View">
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                                <!-- /.card -->
                            </div>

                        </div>
                    </form>
                </div>
                <div id="result_data"></div>
                <?php
                //var_dump($application_details);
                $deficit_defects=0;
                if(isset($application_details) && sizeof($application_details)>0){
                    ?>
                     <?php
                                                $attribute = array('class' => 'form-horizontal', 'name' => 'applicationDetails', 'id' => 'applicationDetails', 'autocomplete' => 'off');
                                                echo form_open(base_url('Copying/Copying/application_status_update'), $attribute);
                                                ?>

                    <!-- <form id="applicationDetails" method="post"> -->
                    <!-- <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>"> -->
                        <div class="card">
                            <div class="card-header">
                                <h4 class="basic_heading"><i class="fa fa-tag"></i> Application Details</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3"><b>Application Number</b></div>
                                    <div class="col-md-3"><b>Application Date</b></div>
                                    <div class="col-md-2"><b>Delivery Mode</b></div>
                                    <div class="col-md-2"><b>Court Fee</b></div>
                                    <div class="col-md-2"><b>Remarks</b></div>
                                </div>
                                <div class="row">
                                    <input type="hidden" name="app_id" value="<?=$copying_order_issuing_application_id?>">
                                    <div class="col-md-3"><a target="_blank" href="<?php echo base_url() ?>index.php/Application/trap?id=<?=$copying_order_issuing_application_id?>&num=<?=$application_details[0]['application_number_display']?>"><?=$application_details[0]['application_number_display']?></a> </div>
                                    <div class="col-md-3"><?=date('d-m-Y @ h:i A',strtotime($application_details[0]['application_receipt']))?> </div>

                                    <?php
                                    $delivery_mode = array(
                                        1=>'By Post',
                                        2=>'By Hand'
                                    );
                                    $defect_ids = array();
                                    $fee_deficit = '';
                                    $remark = '';
                                    $feePaid='';
                                    $deficit_defects=0;
                                    foreach($show_defects as $def){
                                        array_push($defect_ids, $def['ref_order_defect_id']);
                                        if($def['ref_order_defect_id'] == 1) {
                                            $fee_deficit = $def['remark'];
                                            $deficit_defects=1;
                                        }
                                        if($def['ref_order_defect_id']==12)
                                            $remark = $def['remark'];
                                    }
                                    if($fee_deficit!='')
                                        $feePaid.="+".$fee_deficit;
                                    if($application_details[0]['ready_remarks']!='')
                                        $feePaid.="+".$application_details[0]['ready_remarks'];

                                    if(in_array($application_details[0]['delivery_mode'],$delivery_mode)){
                                        $app_delivery_mode = $delivery_mode[$application_details[0]['delivery_mode']];
                                    }else{
                                        $app_delivery_mode = "";
                                    }
                                    ?>
                                    <div class="col-md-2"><?=$app_delivery_mode?> </div>
                                    <div class="col-md-2"><?=$application_details[0]['court_fee'].$feePaid;?> </div>
                                    <div class="col-md-2"><?=$application_details[0]['remarks']?> </div>
                                </div>
                                <br/><br/>
                                <div class="row">
                                    <div class="col-md-2"><b>Diary Number</b> </div>
                                    <div class="col-md-4"><b>Case Number</b> </div>
                                    <div class="col-md-4"><b>Cause Title</b> </div>
                                    <div class="col-md-2"><b>Section</b> </div>

                                </div>
                                <div class="row">
                                    <div class="col-md-2"><?php
                                    
                                    if(!empty($application_details[0]['diary'])){
                                        echo substr($application_details[0]['diary'],0,strlen($application_details[0]['diary'])-4).'/'.substr( $application_details[0]['diary'] , -4 );
                                    }
                                    ?> </div>
                                    <div class="col-md-4"><?=$application_details[0]['reg_no_display']?> </div>
                                    <div class="col-md-4"><?=$application_details[0]['title']?> </div>
                                    <div class="col-md-2"><?=$application_details[0]['section_name']?> </div>
                                </div>
                                <br/><br/>
                                    <div class="row">
                                    <div class="col-md-2"><b>Applied By</b></div>
                                    <div class="col-md-2"><b>Name</b></div>
                                    <div class="col-md-2"><b>Mobile</b> </div>
                                    <div class="col-md-6"><b>Address</b></div>
                                </div>
                                <div class="row">
                                    <?php
                                    $applied_by = array(
                                        1=>'Advocate',
                                        2=>'Party of the case',
                                        3=>'Appearing Council',
                                        4=>'Third Party'
                                    );
                                    ?>
                                    <div class="col-md-2"><?=$applied_by[$application_details[0]['filed_by']]?> </div>
                                    <div class="col-md-2"><?=$application_details[0]['name']?></div>
                                    <div class="col-md-2"><?=$application_details[0]['mobile']?></div>
                                    <div class="col-md-6"><?=$application_details[0]['address']?></div>
                                </div>
                                <br/><br/>

                                <div class="row">
                                    <div class="col-md-3"><b>S.No.</b> </div>
                                    <div class="col-md-3"><b>Document Type</b></div>
                                    <div class="col-md-3"><b>Date</b> </div>
                                    <div class="col-md-3"><b>Copies</b></div>
                                </div>
                                <?php
                                $i = 0;
                                if(!empty($app_documents)){
                                    foreach($app_documents as $app_document){
                                    $i++;
                                    ?>
                                    <div class="row">
                                        <div class="col-md-3"><?=$i?></div>
                                        <div class="col-md-3"><?=$order_type_display[$app_document['order_type']];?> </div>
                                        <div class="col-md-3"><?=(is_null($app_document['order_date']))?'-':date('d-m-Y',strtotime($app_document['order_date']))?> </div>
                                        <div class="col-md-3"><?=$app_document['number_of_copies']?> </div>
                                    </div>
                                <?php  }} ?>
                                <br/><br/>
                                <div class="row">
                                    <div class="col-md-2"><b> <label>Application Status</label> </b></div>
                                    <div class="col-md-2">
                                        <select class="form-control" placeholder="" id="application_status" name="application_status" onchange="defect()">
                                            <?php

                                        foreach($copy_status as $status) {
                                            if($status['status_code'] == $application_details[0]['application_status'])
                                                echo '<option value="'.$status['status_code'].'" selected>'.$status['status_description'].'</option>';
                                            else
                                            echo '<option value="' . $status['status_code'] . '">' . $status['status_description'] . '</option>';
                                        }
                                        ?>

                                        </select>
                                    </div>
                                </div><br/>
                                <div class="row" id="defect_list">
                                    <table class="table table-bordered">
                                        <tbody>
                                        <tr>
                                            <?php

                                            $i=0;
                                            foreach($defects as $defect){
                                                if(in_array($defect['code'], $defect_ids))
                                                    echo '<td><input class="ids" type="checkbox" checked onchange="defect_remark()" name="or_defects[]" value="'.$defect['code'].'">   '.$defect['description'].'</td>';
                                                else
                                                    echo '<td><input class="ids" type="checkbox"  onchange="defect_remark()" name="or_defects[]" value="'.$defect['code'].'">   '.$defect['description'].'</td>';
                                                $i++;
                                                if(($i%4)==0)
                                                    echo '</tr><tr>';
                                            }
                                            ?>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="row" id="deficit_fee">
                                    <label for="remark" class="col-md-2 control-label" id="remark_label">Court Fees Deposited</label><br/>
                                    <div class="col-md-2"><label for="reg" id="reg_courtfee">Filing</label>
                                        <input type="text" class="form-control" placeholder="" id="feeFiling" name="feeFiling" readonly value="<?=$application_details[0]['court_fee']?>"></div>
                                    <div class="col-md-2"><label for="reg" id="reg_courtfee">Deficit</label>
                                        <input type="text" class="form-control" placeholder="" id="feeDeficit" name="feeDeficit" readonly value="<?=$fee_deficit?>"></div>
                                    <div class="col-md-3"><label for="reg"  id="reg_courtfee">Fee to Pay</label>
                                        <input type="text" class="form-control" placeholder="" onchange="getTotal()" id="feePay" name="feePay" value="0"></div>
                                    <div class="col-md-2"><label for="reg" id="reg_courtfee">Total</label>
                                        <input type="text" class="form-control" placeholder="" id="totalCourtFee" name="totalCourtFee" readonly value=""></div>
                                </div>
                                <div class="row" id="fee_defecit_section">
                                    <label for="remark" class="col-md-2 control-label" id="remark_label">Court Fee:</label>
                                    <div class="col-md-10"><input type="number" class="form-control" placeholder="" id="fee_defecit" name="fee_defecit" value="<?=$fee_deficit?>"></div>
                                </div>
                                <div class="row" id="remark_section">
                                    <label for="remark" class="col-md-2 control-label" id="remark_label">Remark:</label>
                                    <div class="col-md-10"><input type="text" class="form-control" placeholder="" id="remark" name="remark" value="<?=$remark?>"></div>
                                </div><br/>
                                <div class="row">
                                <div class="col-sm-6">
                                            </div>
                                    <div class="col-sm-6"><button type="submit" class="btn btn-success btn-flat" onclick="status_update()"><span class="glyphicon glyphicon-floppy-disk"></span>  Save</button></div>
                                </div>
                            </div>
                        </div>
                        <?= form_close(); ?>
                    <!-- </form> -->
                    <div class="box box-success">
                        <div class="box-header with-border">
                            <div class="col-xs-10"><h3 class="box-title" id="form-title">Case Trap Record</h3></div>
                        </div>
                        <table class="table table-striped table-hover ">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Prev. Status</th>
                                <th>New Status</th>
                                <th>Updated By</th>
                                <th>Updated On</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                                $index=0;
                                foreach ($trap_list as $trap){
                                    $index++;
                                    echo "<tr>
                                            <td style='cursor:pointer;'>$index</td>
                                            <td style='cursor:pointer;'>".$trap['prev']."</td>
                                            <td style='cursor:pointer;'>".$trap['new']."</td>
                                            <td style='cursor:pointer;'>".$trap['name']." (".$trap['empid'].")</td>
                                            <td style='cursor:pointer;'>".date('d-m-Y', strtotime($trap['updated_on']))."</td>
                                          </tr>";
                                }
                            ?>
                            </tbody>
                        </table>
                    </div>
                <?php }
                else if(isset($application_details) && isset($_POST['btn_app_submit'])){
                    ?>
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h4><i class="icon fa fa-ban"></i> Alert!</h4>
                        Invalid Application Number.
                    </div>
                <?php } ?>
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
<script>
      $(function () {
        defect();
        defect_remark();
        if($( "#application_status" ).val() == "P")
            $("#remark_section").show();
        $('.datepick').datepicker({
            format: 'dd-mm-yyyy',
            autoclose:true
        });
    });

    function defect(){
        var defects='<?php echo $deficit_defects;?>';
        $("#remark_section").hide();
        $("#defect_list").hide();
        $("#fee_defecit_section").hide();
        $("#deficit_fee").hide();
        if($( "#application_status" ).val() == "F"){
            $("#defect_list").show();
        }
        else if($( "#application_status" ).val() == "R" && defects==1)
        {
            $("#deficit_fee").show();
        }
        else{
                if($("#application_status").val() == "P")
                    $("#remark_section").show();
                else
                    $("#remark_section").hide();
            $("#defect_list").hide();
            $("#fee_defecit_section").hide();
        }
    }

    function defect_remark(){
        $("#fee_defecit_section").hide();
        $("#remark_section").hide();
        $("#fee_defecit_section").hide();
        $("#remark_section").hide();
        $('.ids:checked').each(function() {
            console.log(this.value);
            if(this.value==1){
                $("#fee_defecit_section").show();
                $("#fee_defecit").show();
            }
            if(this.value==12){
                $("#remark_section").show();
                $("#remark").show();
            }
        });
    }
    function check() {
        var fromDate = document.getElementById('from_date').value;
        var toDate = document.getElementById('to_date').value;
        date1 = new Date(fromDate.split('-')[2], fromDate.split('-')[1] - 1, fromDate.split('-')[0]);
        date2 = new Date(toDate.split('-')[2], toDate.split('-')[1] - 1, toDate.split('-')[0]);
        if (date1 > date2) {
            alert("To Date must be greater than or equal to From date");
            return false;
        }
        return true;
    }

    function checkCheckbox() {
        if ($('input:checkbox').is(':checked') == false) {
            alert("Select atleast one checkbox!");
            return false;
        } else {
            idSelected = "";
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

            var userLoggedIn = $('#userLoggedIn').val();
            $('input.chkbox:checkbox:checked').each(function() {
                idSelected += $(this).val() + ","
            });
            idSelected = idSelected.replace(/,\s*$/, "");
            $.ajax({
                url: '<?php echo base_url('Copying/Copying/bulkStatusUpdate'); ?>',
                cache: false,
                async: true,
                data: {
                    idSelected: idSelected,
                    CSRF_TOKEN: CSRF_TOKEN_VALUE
                },
                type: 'POST',
                success: function(data) {
                    if (data.trim() == 'yes') {
                        alert("Data Updated Successfully");
                    } else {
                        alert("Data not Updated");
                    }
                    location.reload();
                    updateCSRFToken();
                },
                error: function(xhr) {
                    updateCSRFToken();
                }
            });
        }
    }

    function getTotal(){
        //if($("#feePay").val()=='' || $("#feePay").val())
        var sum=0;
        $("#totalCourtFee").val('');
        var amt1=$("#feeFiling").val();
        var amt2=$("#feeDeficit").val();
        var amt3=$("#feePay").val();
       if(isNaN(amt1) || amt1.length==0)
           amt1=0;
       if(isNaN(amt2) || amt2.length==0)
            amt2=0;
       if(isNaN(amt3) || amt3.length==0)
            amt3=0;
        sum=parseInt(amt1)+parseInt(amt2)+parseInt(amt3);
        $("#totalCourtFee").val(sum);
    }

    function status_update(){
        var applicationDetails = jQuery("#applicationDetails");
            jQuery.post("<?php echo base_url('Copying/Copying/application_status_update'); ?>", {
                    post_data: applicationDetails.serialize(),
                })
                .done(function(data) {
                    alert(data);
                });
            updateCSRFToken();
    }


        // $.ajax({
        //         url: '<?php echo base_url('Copying/Copying/application_status_update'); ?>',
        //         cache: false,
        //         async: true,
        //         data: {
        //             post_data: $("#applicationDetails").serialize(),
        //             CSRF_TOKEN: CSRF_TOKEN_VALUE
        //         },
        //         type: 'POST',
        //         success: function(data) {
        //             alert(data);
        //             updateCSRFToken();
        //         },
        //         error: function(xhr) {
        //             updateCSRFToken();
        //         }
        //     });

</script>