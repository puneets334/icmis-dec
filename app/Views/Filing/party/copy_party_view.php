<?= view('header'); ?>
<style>
    #wrapper_1:after {
        content: "";
        background-color: #000;
        position: absolute;
        width: 0.2%;
        height: 100%;
        top: 0;
        left: 100%;
        display: block;
    }

    #wrapper_2:after {
        content: "";
        background-color: #000;
        position: absolute;
        width: 0.2%;
        height: 100%;
        top: 0;
        left: 100%;
        display: block;
    }
</style>
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
					<?=view('Filing/filing_breadcrumb');?>
                    <!-- /.card-header -->

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header p-2" style="background-color: #fff;">
                                    <?=view('Filing/party/party_breadcrumb');?>
                                    <?php
$filing_details= session()->get('filing_details');
$allow_user=0; 
$ucode=  $_SESSION['login']['usercode'];
$check_if_fil_user = is_data_from_table('fil_trap_users', " usertype=101 AND display='Y'  and usercode=$ucode ", '*', $row = 'N');

if($check_if_fil_user > 0 ){
    $allow_user=1;
}

                                    $attribute = array('class' => 'form-horizontal', 'name' => 'party_view_form', 'id' => 'party_view_form', 'autocomplete' => 'off');
                                    echo form_open('Filing/Party/save_party_details', $attribute);
                                    ?>
                                </div><!-- /.card-header -->
                                <div class="">
                                    <div class="tab-content">

                                        

                                        <div class="tab-pane active" id="copy_party_tab_panel">
                                            <!-- <h4 class="basic_heading mt-3"> <strong>Copy Party Details</strong> </h4> -->

                                            <div class="row mt-5 mb-3">
                                                <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                                                    <div class="form-group row clearfix">
                                                        <label class="col-form-label"><strong>From Diary No.</strong></label>
                                                        <input type="text" class="form-control frmCpy" id="dno" onkeyup="copyFrom(this);" name="dno" placeholder="Enter From Diary Number">
                                                    </div>
                                                </div>

                                                <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                                                    <div class="form-group row clearfix">
                                                        <label class="col-form-label"><strong>From Year</strong></label>
                                                        <input type="text" class="form-control frmCpy" id="dyr" name="dyr" onkeyup="copyFrom(this);" placeholder="Enter From Year">
                                                    </div>
                                                </div>


                                                <div id="div_result" class="col-md-12 text-center"></div>
                                            </div>
                                            <div class="row mt-3 mb-3">
                                               
                                                <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                                                    <div class="form-group row clearfix">
                                                        <label class="col-form-label"><strong>To Diary No.</strong></label>

                                                        <input type="text" class="form-control toCpy" id="dno1" name="dno1" onkeyup="copyTo(this);" placeholder="Enter To Diary Number">

                                                    </div>
                                                </div>

                                                <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                                                    <div class="form-group row clearfix">
                                                        <label class="col-form-label"><strong>To Year</strong></label>

                                                        <input type="text" class="form-control toCpy" id="dyr1" name="dyr1" onkeyup="copyTo(this);" placeholder="Enter Enter To Year">

                                                    </div>
                                                </div>

                                                <div id="div_result1" class="col-md-12 text-center"></div>
                                            </div>

                                            <div class="col-md-12 mb-3 mt-5 text-center">
                                                <button class="btn btn-primary" onclick="copy_details()" type="button">Copy Party Details</button>
                                            </div>

                                            <div id="div_result" class="col-md-12 text-center"></div>
                                        </div>
										
										 
										
                                        <!-- /.copy_party_tab_panel -->

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

<script>
    $('.col-sm-12.col-form-label.ml-4').hide()

    

   


    ////// Copy Party Details ////////
    // function gct(){
    
    //$('.frmCpy').on("keyup", function() {
    async function copyFrom($this)
    {
        await updateCSRFTokenSync();
        dno = document.getElementById('dno').value;
        dyr = document.getElementById('dyr').value;

        if (dno != '' && dyr != '') {
            d1 = dno + dyr;

            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

            let obj = {
                d_no: dno,
                d_yr: dyr
            };
            $.ajax({
                url: "<?php echo base_url('Filing/Party/get_cause_title'); ?>",
               // cache: false,
               // async: true,
                data: {
                    CSRF_TOKEN: CSRF_TOKEN_VALUE,
                    data: obj
                },
                type: 'POST',
                success: function(data, status) {
                    $('#div_result').html(data);
                    //updateCSRFToken();
                },
                error: function(xhr) {
                    alert("Error");
                    //updateCSRFToken();
                }
            });
        }

    }


    // function gct1(){
    //$('.toCpy').on("keyup", function() {
    async function copyTo($this)
     {
        await updateCSRFTokenSync();
        dno = document.getElementById('dno1').value;
        dyr = document.getElementById('dyr1').value;

        if (dno != '' && dyr != '') {
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            let obj = {
                d_no: dno,
                d_yr: dyr
            };

            $.ajax({
                url: "<?php echo base_url('Filing/Party/get_cause_title'); ?>",
                //cache: false,
                //async: true,
                data: {
                    CSRF_TOKEN: CSRF_TOKEN_VALUE,
                    data: obj
                },
                type: 'POST',
                success: function(data, status) {
                    $('#div_result1').html(data);
                    //updateCSRFToken();
                },
                error: function(xhr) {
                    alert("Error ");
                    //updateCSRFToken();
                }
            });
        }

    }


    async function copy_details() {

        await updateCSRFTokenSync();
        var dno1 = document.getElementById('dno').value;
        var dyr1 = document.getElementById('dyr').value;
        var d1 = dno1 + dyr1;
        var dno2 = document.getElementById('dno1').value;
        var dyr2 = document.getElementById('dyr1').value;
        var d2 = dno2 + dyr2;
       

        if ((dno1 == '') || (dyr1 == '')) {
            alert("diary no/ diary year can't be blank");
            document.getElementById('dno').focus();
            return;
        }
        if ((dno2 == '') || (dyr2 == '')) {
            alert("diary no/ diary year can't be blank");
            document.getElementById('dno1').focus();
            return;
        }

        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        
        let obj = {
            d1,
            d2
        };

        $.ajax({
            url: "<?php echo base_url('Filing/Party/copy_party_details'); ?>",
            cache: false,
            async: true,
            data: {
                CSRF_TOKEN: CSRF_TOKEN_VALUE,
                data: obj
            },
            type: 'POST',
            beforeSend: function (xhr) {
                $("#div_result").html('<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>');
            },
            success: function(data, status) {
                updateCSRFToken();
                alert(data);                
                location.reload()
            },
            error: function(xhr) {
                updateCSRFToken();
                alert("Error ");
                $("#div_result").html('');
                
            }
        });

    }
	
  
 

 


</script>