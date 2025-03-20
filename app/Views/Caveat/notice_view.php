<?= view('header') ?>
 
    <style>
        .custom-radio {
            float: left;
            display: inline-block;
            margin-left: 10px;
        }

        .custom_action_menu {
            float: left;
            display: inline-block;
            margin-left: 10px;
        }

        .table thead th,
        .table th {
            width: 50%;
        }
        .basic_heading {
            text-align: center;
            color: #31B0D5;
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
                                <div class="col-sm-9">
                                    <h3 class="card-title">Caveat >> Notice</h3>
                                </div>
                                <div class="col-sm-3">
                                    <a href="<?=base_url('Caveat/Generation');?>"><button class="btn btn-success btn-sm" type="button"><i class="fa fa-plus-circle" aria-hidden="true"></i></button></a>
                                    <a href="<?=base_url('Caveat/Search');?>"><button class="btn btn-info btn-sm" type="button"><i class="fa fa-search-plus	" aria-hidden="true"></i></button></a>
                                </div>
                            </div>
                        </div>
                        <?=view('Caveat/caveat_breadcrumb');?>
                        <br/>
                        <?php

                        $caveat_details= session()->get('caveat_details'); $caveat_no=$caveat_year='';
                        //echo '<pre>';print_r($caveat_details);
                        if (!empty($caveat_details)){
                            $caveat_no=substr($caveat_details['caveat_no'], 0, -4);
                            $caveat_year=substr($caveat_details['caveat_no'],-4);
                        }else{
                            if (!empty($param)){
                                $caveat_no=trim($param['caveat_number']);
                                $caveat_year=trim($param['caveat_year']);
                            }
                        }
                        $caveat_number=$caveat_no.$caveat_year;
                        $attribute = array('class' => 'form-horizontal','name' => 'diary_search', 'id' => 'diary_search', 'autocomplete' => 'off');
                        //echo form_open(base_url('Caveat/Similarity'), $attribute);
                        echo form_open(base_url('#'), $attribute);
                        ?>
                        <div class="row">
                            <div class="col-md-2"></div>
                            <div class="col-md-3">
                                <div class="form-group row">
                                    <label for="caveat_number" class="col-sm-5 col-form-label"> Caveat No</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="caveat_number" name="caveat_number" value="<?=$caveat_no;?>" placeholder="Enter Caveat No" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group row">
                                    <label for="caveat_year" class="col-sm-5 col-form-label">Caveat Year</label>
                                    <div class="col-sm-7">
                                        <?php $sel = ''; $year = 1950;
                                        $current_year = date('Y');
                                        ?>
                                        <select name="caveat_year" id="caveat_year" class="custom-select" required>
                                            <?php for ($x = $current_year; $x >= $year; $x--) { if ((!empty($caveat_year) &&  $x==$caveat_year)) {  $sel = 'selected=selected';  }else{$sel='';} ?>
                                                <option <?=$sel?> value="<?=$x?>"><?php echo $x; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <!--<button type="submit" class="btn btn-primary" id="submit">Search</button>-->
                                <button type="button" class="btn btn-primary" id="submit" onclick="getDetails()">Search</button>
                            </div>
                            <div class="col-md-2"></div>
                        </div>
                        <?php form_close();?>

                        <div class="row">
                            <center>
                                <span class="alert-danger"><?=\Config\Services::validation()->listErrors()?></span>

                                <?php if(session()->getFlashdata('error')){ ?>
                                    <div class="alert alert-danger text-white ">
                                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                        <?= session()->getFlashdata('error')?>
                                    </div>
                                <?php } else if(session("message_error")){ ?>
                                    <div class="alert alert-danger">
                                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                        <?=session()->getFlashdata("message_error")?>
                                    </div>
                                <?php }else{?>
                                    <br/>
                                <?php }?>
                            </center>
                        </div>
                        <div id="div_result"></div>

                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
    </section>
    <script src="<?php echo base_url('caveat/editor_tools.js'); ?>"></script>
    <script>
        <?php if (!empty($caveat_number) && $caveat_number !=null){?>
        getDetails();
        <?php } ?>

        function getDetails()
        {
            var caveat_number = $("#caveat_number").val();
            var caveat_year = $("#caveat_year :selected").val();
            if (caveat_number.length == 0) {
                alert("Please enter caveat number");
                $("#caveat_number").focus();
                validationError = false;
                return false;
            }else if (caveat_year.length == 0) {
                alert("Please select caveat year");
                $("#caveat_year").focus();
                validationError = false;
                return false;
            }
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $.ajax({
                //url: '/Caveat/Notice/get_notice',
                url: base_url+ '/Caveat/Notice/get_notice',
                cache: false,
                async: true,
                data: {CSRF_TOKEN: CSRF_TOKEN_VALUE,caveat_number: caveat_number,caveat_year:caveat_year,hd_link:'0'},
                beforeSend: function () {
                    //$('#div_result').html('<table widht="100%" align="center"><tr><td><img src="'<?php //echo base_url('images/load.gif'); ?>'"/></td></tr></table>');
                    $('#div_result').html('<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>');
                },
                type: 'POST',
                success: function(data, status) {
                    updateCSRFToken();
                    $('#div_result').html(data);
                },
                error: function(xhr) {
                    updateCSRFToken();
                    //alert("Error: " + xhr.status + " " + xhr.statusText);
                }

            });
        }

        function save_caveat_notice(){
            var hd_caveat_no = $("#hd_caveat_no").val();
            var noticecontent = $("#noticecontent").html();
            var sp_d_no = "";
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $(".cl_diary_no").each(function () {
                if (sp_d_no == "")
                    sp_d_no = $(this)[0].outerHTML + "~~~" + $(this).attr("id");
                else
                    sp_d_no =
                        sp_d_no + "~!@#$" + $(this)[0].outerHTML + "~~~" + $(this).attr("id");
            });
            $.ajax({
                url: '/Caveat/Notice/save_caveat_report',
                cache: false,
                async: true,
                data: { hd_caveat_no: hd_caveat_no, sp_d_no: sp_d_no ,CSRF_TOKEN: CSRF_TOKEN_VALUE},

                type: "POST",
                success: function (data, status) {
                    updateCSRFToken();
                  //  alert(data);
                    if (data.trim() == 1) {
                        alert("Record Updated Successfully");
                    } else {
                        alert("Problem in saving record");
                    }
                    //                $('#div_result').html(data);
                    var prtContent = document.getElementById("noticecontent");
                    var WinPrint = window.open(
                        "",
                        "",
                        "letf=100,top=0,width=800,height=1200,toolbar=1,scrollbars=1,status=1,menubar=1"
                    );
                    WinPrint.document.write(
                        prtContent.innerHTML
                    );
                    WinPrint.print();

                },
                error: function (xhr) {
                    updateCSRFToken();
                },
            });
        }

    </script>
 