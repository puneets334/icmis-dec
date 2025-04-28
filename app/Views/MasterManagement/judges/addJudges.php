<?= view('header') ?>

<style>
    a:hover {
        color: red;
    }

    a:visited {
        color: green;
    }

    @media only screen and (max-width: 600px) {
        .gridbox {
            background: red;
        }
    }

    /* Small devices (portrait tablets and large phones, 600px and up) */
    @media only screen and (min-width: 600px) {
        .gridbox {
            background: green;
        }
    }

    /* Medium devices (landscape tablets, 768px and up) */
    @media only screen and (min-width: 768px) {
        .gridbox {
            background: blue;
        }
    }

    /* Large devices (laptops/desktops, 992px and up) */
    @media only screen and (min-width: 992px) {
        .gridbox {
            background: orange;
        }
    }

    /* Extra large devices (large laptops and desktops, 1200px and up) */
    @media only screen and (min-width: 1200px) {
        .gridbox {
            background: pink;
        }
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
                                <h3 class="card-title">Master Management >> Judges</h3>
                            </div>
                            <div class="col-sm-2"> </div>
                        </div>
                    </div>
                    <br /><br />
                    <!--start menu programming-->
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12"> <!-- Right Part -->
                                <div class="form-div">
                                    <div class="d-block text-center">


                                        <!-- Main content -->
                                        <form class="form-horizontal" id="push-form" method="POST" action="<?= base_url(); ?>/MasterManagement/JudgesController">
                                            <?= csrf_field() ?>
                                            <div class="box-body">
                                                <!--<input type="hidden" name="usercode" id="usercode" value="<?/*=$this->session->userdata('dcmis_user_idd');*/ ?>" >-->
                                                <input type="hidden" name="usercode" id="usercode" value="<?= $usercode ?>" />
                                                <div class="form-group mt-3">
                                                    <label for="reportType" class="col-sm-2 col-md-8 col-lg-12 " style="text-align:center;">
                                                        <h2>Insert Judges Information </h2>
                                                    </label>
                                                </div>
                                                <div id="row1" class="row required mt-5">
                                                    <div class="control-label col-md-4  requiredField">
                                                        <label class="col-sm-6">JTYPE <font color="red">*</font>:</label>
                                                        <select class="form-control" id="jtype" name="jtype" required="required">
                                                            <option value="">---select---</option>
                                                            <option value="R">R</option>
                                                            <option value="J">J</option>
                                                        </select>
                                                    </div>
                                                    <div class="control-label col-md-4  requiredField">
                                                        <label class="col-sm-6">
                                                           JCODE <font color="red">*</font>:
                                                        </label>
                                                        <input class="form-control" type="number" id="jcode" name="jcode" placeholder="JCODE" required="required" readonly>
                                                    </div>

                                                    <!--                            <div class="col-sm-1 col-md-1 col-lg-3 ">-->
                                                    <!--                                <label class="col-sm-6">JNAME:</label>-->
                                                    <!--                                <input class="form-control" type="TEXT" id="jname" name="jname" required="required">-->
                                                    <!--                            </div>-->

                                                    <div class="control-label col-md-4  requiredField">
                                                        <label class="col-sm-6">TITLE:</label>
                                                        <select class="form-control" id="title" name="title" required="required">
                                                            <option value="">---select---</option>
                                                            <option value="REGISTRAR (J-I)">REGISTRAR (J-I)</option>
                                                            <option value="REGISTRAR (J-II)">REGISTRAR (J-II)</option>
                                                            <option value="REGISTRAR (J-III)">REGISTRAR (J-III)</option>
                                                            <option value="REGISTRAR (J-IV)">REGISTRAR (J-IV)</option>
                                                            <option value="REGISTRAR (J-V)">REGISTRAR (J-V)</option>
                                                            <option value="REGISTRAR (J-VI)">REGISTRAR (J-VI)</option>
                                                            <option value="REGISTRAR (OSD)">REGISTRAR (OSD)</option>

                                                            <option value="HON'BLE MR. JUSTICE">HON'BLE MR. JUSTICE</option>
                                                            <option value="HON'BLE MRS. JUSTICE">HON'BLE MRS. JUSTICE</option>
                                                            <option value="HON'BLE MS. JUSTICE">HON'BLE MS. JUSTICE</option>
                                                            <option value="HON'BLE KUMARI JUSTICE">HON'BLE KUMARI JUSTICE</option>
                                                            <option value="HON'BLE DR. JUSTICE">HON'BLE DR. JUSTICE</option>

                                                        </select>
                                                    </div>
                                                </div>
                                                <div id="row2" class="row required mt-5">

                                                    <div class="control-label col-md-4  requiredField" >
                                                        <div id="gen">
                                                            <label class="col-sm-6">Sh./Smt.<font color="red">*</font></label>
                                                            <select class="form-control" id="gender" name="gender" required>
                                                                <option value="">---select---</option>
                                                                <option value="Sh.">Sh.</option>
                                                                <option value="Smt.">Smt.</option>

                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="control-label col-md-4  requiredField">
                                                <label class=" col-sm-6">FIRST NAME<font color="red">*</font>:</label>
                                                        <input class="form-control" type="TEXT" id="first_name" name="first_name" required="required" max="30">
                                                    </div>

                                                    <div class="control-label col-md-4  requiredField"">
                                                <label class=" col-sm-6">SURNAME <font color="red">*</font>:</label>
                                                        <input class="form-control" type="TEXT" id="sur_name" name="sur_name" required="required" max="30">
                                                    </div>

                                                </div>

                                                <div id="row3" class="row required mt-5">

                                                    <div class="control-label col-md-4  requiredField">
                                                        <label class="col-sm-6">JCOURT:</label>
                                                        <select class="form-control" id="jcourt" name="jcourt">
                                                            <option value="0">---select---</option>
                                                            <?php
                                                            for ($x = 1; $x <= 15; $x++) {
                                                                echo '<option value="' . $x . '">' . $x . '</option>';
                                                            }
                                                            ?>
                                                            <option value=21>21</option>
                                                            <option value=22>22</option>
                                                        </select>
                                                    </div>
                                                    <div class="control-label col-md-4  requiredField">
                                                        <label class="col-sm-6">ABBREVIATION <font color="red">*</font>:</label>
                                                        <input class="form-control" type="TEXT" id="abbreviation" name="abbreviation" required="required">
                                                    </div>
                                                    <div class="control-label col-md-4  requiredField">
                                                        <label class="col-sm-6">APOINTMENT_DATE:</label>
                                                        <input type="text" id="from_date" value="<?php if (isset($_POST['from_date'])) ?>" name="from_date" class="form-control datepick" placeholder="From Date" required="required" readonly>
                                                    </div>
                                                </div>
                                                <!--<div class="col-sm-1 col-md-1 col-lg-3 ">-->
                                                <!-- <label class="col-sm-6">TO_DATE:</label>-->
                                                <!--  <input type="text" id="to_date" value="--><?php //if(isset($_POST['to_date']))
                                                                                                ?><!--" name="to_date" class="form-control datepick"  placeholder="to Date" required="required" readonly>-->
                                                <!--  </div>-->

                                                <div id="row4" class="row required mt-5">
                                                    <div class="control-label col-md-4  requiredField">
                                                        <label class="col-sm-6">TO_DATE:</label>
                                                        <input type="text" id="to_date" name="to_date" class="form-control datepick" placeholder="to Date">
                                                    </div>

                                                    <div class="control-label col-md-4  requiredField">
                                                        <label class="col-sm-6">JUDGE_SENIORITY:</label>
                                                        <input class="form-control" type="number" id="judge_seniority" name="judge_seniority" required="required" placeholder="SENIORITY" readonly>
                                                    </div>

                                                    <div class="control-label col-md-2 mt-4" style="margin-left: auto;">
                                                        <button type="submit" id="view" name="view" class="btn btn-block btn-primary">INSERT</button>
                                                    </div>
                                                </div>

                                            </div>
                                        </form>
                                        <?php
                                        if (!empty($rev_result)) {

                                            if ($rev_result == "inserted")
                                                echo "<script type='text/javascript'>alert('Inserted Successfully.');</script>";
                                            elseif ($rev_result == "not inserted")
                                                echo "<script type='text/javascript'>alert('Not Inserted.');</script>";
                                            else
                                                echo "<script type='text/javascript'>alert('Please insert value');</script>";
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>


<script type="text/javascript">
    $(document).ready(function() {
        $('#reportTable1').DataTable().destroy();
        $('#reportTable1 tbody').empty();
        getAllNotices();
        $("#display").hide();

        $(function() {
            //debugger;
            $('#from_date, #to_date,#cji_date').datepicker({
                format: 'dd-mm-yyyy',
                startDate: '1996/01/01',
                autoclose: true

            });
        });
        $('#info-alert').hide();
    });

    $("#jtype").change(function() {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        var jtypeid = $("#jtype").val();
        if (jtypeid == 'R') {
            $("#gender").prop('required', true);
        } else {
            $("#gender").prop('required', false);

        }
        //alert(jcodeid);
        //var bunch_type = $("#bunch_type").val();
        $.ajax({
            type: 'GET',
            url: '<?php echo base_url(); ?>/MasterManagement/JudgesController/Getjcodejs',
            cache: false,
            async: true,
            dataType: 'json',
            data: {
                jtypeid: jtypeid,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            
            success: function(data) {
                updateCSRFToken();
                console.log(data);

                $('#dv_res1').html(data);
                $("#jcode").val(data[0].jcode);
                $("#judge_seniority").val(data[0].sen);


            },
            error: function(xhr) {
                updateCSRFToken();
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
    });



    function getAllNotices() {

        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            url: '<?php echo base_url(); ?>/MasterManagement/JudgesController/display_Latest_Updates',
            data: {},
            cache: false,
            dataType: 'json',
            type: "POST",
            headers: {
                'X-CSRF-Token': CSRF_TOKEN_VALUE
            },
            success: function(data) {

                if (data.length > 0) {
                    $("#display").show();
                    $('#reportTable1 tbody').empty();
                    sno = 1;
                    $.each(data, function(index) {

                        $('#reportTable1 tbody').append("<tr><td>" + sno + "</td><td>" + data[index].menu_name + "</td><td>" + data[index].title_en + "</td><td>" + data[index].f_date + "</td><td>" + data[index].t_date + "</td></tr>");
                        sno++;
                    });

                    $('#reportTable1').DataTable({
                        "bSort": true,
                        dom: 'Bfrtip',
                        "scrollX": true,
                        iDisplayLength: 8,

                        buttons: [{
                            extend: 'print',
                            orientation: 'landscape',
                            pageSize: 'A4'
                        }]
                    });
                } else {
                    $("#display").hide();
                    $("#info-alert").show();
                    $("#info-alert").fadeTo(2000, 500).slideUp(500, function() {
                        $("#info-alert").slideUp(500);
                    });
                }

            },
            error: function(xhr, status, error) {
                updateCSRFToken();
                console.log(xhr);
                if (xhr == 'undefined' || xhr == undefined) {
                    alert('undefined');
                } else {
                    alert('object is there');
                }
                alert(status);
                alert(error);
            }
            //error: function(ts) { alert(ts.responseText) }
        });
    }



    $('#jtype').on('change', function() {
        //$('#title').html('');

        if ($('#jtype').val() == 'J') {
            // $("#title option[value*='1']").hide();
            document.getElementById("gen").style.display = "none";


            $('#title option[value*="HON\'BLE MR. JUSTICE"]').prop('selected', true);
            $('#title option[value*="HON\'BLE MRS. JUSTICE"]').show();
            $('#title option[value*="HON\'BLE MS. JUSTICE"]').show();
            $('#title option[value*="HON\'BLE KUMARI JUSTICE"]').show();
            $('#title option[value*="HON\'BLE DR. JUSTICE"]').show();
            $("#title option[value*='REGISTRAR (J-I)']").hide();
            $("#title option[value*='REGISTRAR (J-II)']").hide();
            $("#title option[value*='REGISTRAR (J-III)']").hide();
            $("#title option[value*='REGISTRAR (J-IV)']").hide();
            $("#title option[value*='REGISTRAR (J-V)']").hide();
            $("#title option[value*='REGISTRAR (J-VI)']").hide();
            $("#title option[value*='REGISTRAR (OSD)']").hide();

            // $("#title option[value*='REGISTRAR']").prop('disabled',true);
            //  $("#title option[value*='REGISTRAR']").hide();
        } else {
            document.getElementById("gen").style.display = "block";

            // alert("Hello");
            // alert($('#title option').val());
            //  $('#title option[value*="HON\'BLE MR. JUSTICE"]').prop('disabled',true);
            $('#title option[value*="HON\'BLE MR. JUSTICE"]').hide();
            $('#title option[value*="HON\'BLE MRS. JUSTICE"]').hide();
            $('#title option[value*="HON\'BLE MS. JUSTICE"]').hide();
            $('#title option[value*="HON\'BLE KUMARI JUSTICE"]').hide();
            $('#title option[value*="HON\'BLE DR. JUSTICE"]').hide();
            $("#title option[value*='REGISTRAR (J-I)']").prop('selected', true);
            $("#title option[value*='REGISTRAR (J-II)']").show();
            $("#title option[value*='REGISTRAR (J-III)']").show();
            $("#title option[value*='REGISTRAR (J-IV)']").show();
            $("#title option[value*='REGISTRAR (J-V)']").show();
            $("#title option[value*='REGISTRAR (J-VI)']").show();
            $("#title option[value*='REGISTRAR (OSD)']").show();

        }
    });
</script>