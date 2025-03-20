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
                        <!-- /.card heading -->
<!--                        --><?//=view('Filing/filing_breadcrumb'); ?>


                        <div class="row">
                            <div class="col-md-12">
                                    <div class="card-header p-2" style="background-color: #fff;"><br>
                                        <h4 class="basic_heading"> File Dispatch Receive </h4>
                                    </div>

                                <br><br>
                                <?php  //echo $_SESSION["captcha"];
                                $attribute = array('class' => 'form-horizontal','name' => 'file_dispatch_receive', 'id' => 'file_dispatch_receive', 'autocomplete' => 'off');
                                echo form_open(base_url('#'), $attribute);
                                ?>
<!--                                <div class="card">-->
                                <div class="col-sm-12">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class="form-group row">
                                                <label for="search" class="col-sm-5 col-form-label">Search For :</label>
                                                <div class="col-sm-7">
                                                    <select class="form-control" id="stype" onChange="showDiv()" >
                                                        <option value="select_dno" selected>Diary No.</option>
                                                        <option value="all_dno"> All Matters</option>
                                                    </select>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-sm-3 span_dno" >
                                            <div class="form-group row">
                                                <label for="diaryno" class="col-sm-5 col-form-label">Diary No :</label>
                                                <div class="col-sm-6">
                                                    <input type="text" class="form-control"  id="dno" maxlength="6" size="5" autofocus name="dno" placeholder="Diary No"  >
                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-sm-3 span_dno">
                                            <div class="form-group row">
                                                <label for="year" class="col-sm-3 col-form-label">Year :</label>
                                                <div class="col-sm-6">
                                                    <input type="text" class="form-control" id="dyr" maxlength="4" size="4" value="<?php echo date('Y'); ?>"/>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="col-sm-3" >
                                            <button type="button" id="button" class="btn btn-primary"  onClick="showMatters()">SHOW</button>
                                            <!--                                                <input type="button" class="form-control" value="SHOW" id="showbutton" />-->
                                        </div>


                                    </div>
                                </div><br><br><br>


<!--                                            <center>-->

<!--                                            </center>-->

<!--                                </div>-->

                                <?php form_close();?>

                               <table style="margin-left: auto;margin-right: auto;" align="center" width="100%">
                                    <tr><th>Incomplete Matters for <span style="color: #d73d5a">
                                            <?php
                                            if(!empty($record))
                                            {
//                                                echo "<pre>";
//                                                print_r($record);die;

                                            ?>
                                            <span style="color: #737add">[<?= $record['type_name']; ?>]</span>
                                        </th></tr>

                                    <tr><th><span style="color: #d73d5a; align-items: center;font-size: larger">New SC-EFM matters will not be displayed in this Report</span></th></tr>
                                            <?php
                                            }
                                            ?>
                                </table></center>

                                <br><br>


                                <div id="table_display"> </div>




                            </div>
                            <!-- /.card-body -->
                        </div><br><br><br>
                        <!-- /.card -->
                    </div>
                    <!-- /.card -->
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

    function showDiv()
    {
        var selectElement = document.querySelector('#stype');
        var output = selectElement.options[selectElement.selectedIndex].value;
        //document.querySelector('.output').textContent = output;
        if(output=='all_dno') {
            $(".span_dno").hide();
            //  document.getElementById('newresult').innerText='';

        }

        if(output == 'select_dno')
        {
            $(".span_dno").show();
        }
        // alert("RRRRRRR");
        // return false;

    }

    function showMatters()
    {
        updateCSRFToken();
        var selectElement = document.querySelector('#stype');
        var output = selectElement.options[selectElement.selectedIndex].value;
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        data=[];
        if(output=='all_dno') {
           data={ };
        }
        if(output == 'select_dno')
        {
            // var d_no = $('#dno').val();
            // var d_yr = $('#dyr').val();

            var regNum = new RegExp('^[0-9]+$');
            var d_no = $("#dno").val();
            var d_yr = $("#dyr").val()
            if (!regNum.test(d_no)) {
                alert("Please Enter Diary No in Numeric");
                $("#dno").focus();
                return false;
            }
            if (!regNum.test(d_yr)) {
                alert("Please Enter Diary Year in Numeric");
                $("#dyr").focus();
                return false;
            }
            if (d_no == 0) {
                alert("Diary No Can't be Zero");
                $("#dno").focus();
                return false;
            }
            if (d_yr == 0) {
                alert("Diary Year Can't be Zero");
                $("#dyr").focus();
                return false;
            }
            data={
                'dno':d_no,
                'dyr':d_yr,
            }

         }
        // alert(data['dno']);
        // return false;
        $.ajax({
            url:'<?=base_url('Filing/File_trap_dispatch_receive/display_matters');?>',
            cache: false,
            async: true,
            context: this,
            data:{
                CSRF_TOKEN: CSRF_TOKEN_VALUE,
                record:data,
                type:output

            },
            type: 'POST',
            success: function(data) {
                updateCSRFToken();
                $('#table_display').html(data);

            },
            error: function(xhr) {
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
    }




</script>

 <?=view('sci_main_footer');?>