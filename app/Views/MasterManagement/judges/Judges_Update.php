<?= view('header') ?>

<link rel="stylesheet" href="<?php echo base_url('Ajaxcalls/menu_assign/menu_assign.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('Ajaxcalls/menu_assign/style.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('Ajaxcalls/menu_assign/all.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('assets/vendor/fontawesome-free/css/all.min.css'); ?>">

<style> 
     a:hover{
            color: red;
        }
        a:visited{
            color:green;
        }
        @media only screen and (max-width: 600px) {
            .gridbox {background: red;}
        }

        /* Small devices (portrait tablets and large phones, 600px and up) */
        @media only screen and (min-width: 600px) {
            .gridbox {background: green;}
        }

        /* Medium devices (landscape tablets, 768px and up) */
        @media only screen and (min-width: 768px) {
            .gridbox {background: blue;}
        }

        /* Large devices (laptops/desktops, 992px and up) */
        @media only screen and (min-width: 992px) {
            .gridbox {background: orange;}
        }

        /* Extra large devices (large laptops and desktops, 1200px and up) */
        @media only screen and (min-width: 1200px) {
            .gridbox {background: pink;}
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
                    <input type="hidden" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>">
                    <!--start menu programming-->
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12"> <!-- Right Part -->
                                <div class="form-div">
                                    <div class="d-block text-center">


                                     <!-- Main content -->                           
                                 <form class="form-horizontal" id="push-form"  method="post" action="<?=base_url();?>/MasterManagement/JudgesController/Judges_Update" >
                                  <?= csrf_field() ?>
                                    <div class="box-body">
                                        <!--<input type="hidden" name="usercode" id="usercode" value="<?/*=$this->session->userdata('dcmis_user_idd');*/?>" >-->
                                        <input type="hidden" name="usercode" id="usercode" value="<?=$usercode?>"/>
                                        <div class="form-group">
                                            <label for="reportType" class="col-sm-2 col-md-8 col-lg-12 " style="text-align:center;"><h2>Update Judges Information </h2></label>
                                        </div>

                                        <div id="row1" class="row required">

                                            <div class="control-label col-md-4  requiredField">
                                                <label class="col-sm-6">Catg.</label>
                                                <select class="form-control" id="jtype" name="jtype">
                                                    <option value="">--Select--</option>
                                                    <option value="J">Justice</option>
                                                    <option value="R">Registrar</option>

                                                </select>
                                            </div>

                                            <div class="control-label col-md-4  requiredField">
                                            <label class="col-sm-6">JNAME:</label>
                                            <select class="form-control" id="jname" name="jname" required>
                                                <option value="">--Select Name--</option>
                                        <!--   --><?php
                                        //   foreach($judges_name as $dd) {
                                        //   echo '<option value= ' . $dd['jcode'] . '>' . $dd['jname']. '</option>';
                                        //                                }
                                        //     ?>
                                            </select>
                                            </div>
                                            <div class="control-label col-md-4  requiredField">
                                            <label class="col-sm-6"><font color="red">*</font>JCODE:</label>
                                            <input class="form-control" type="number" id="jcode" name="jcode" placeholder="JCODE" required="required" readonly>
                                            </div>
                                        </div>

                                        <div id="row2" class="row required mt-4">

                                            <div class="control-label col-md-4  requiredField">
                                                <label class="col-sm-6">TITLE:</label>
                                                <select class="form-control" id="title" name="title" required="required">
                                                    <option value="">---select---</option>
                                                    <option value="1">REGISTRAR</option>
                                                    <option value="2">HON'BLE MR. JUSTICE</option>
                                                    <option value="3">HON'BLE MRS. JUSTICE</option>
                                                    <option value="4">HON'BLE MS. JUSTICE</option>
                                                    <option value="5">HON'BLE KUMARI JUSTICE</option>
                                                    <option value="6">HON'BLE DR. JUSTICE</option>
                                                    <option value="7">REGISTRAR (J-I)</option>
                                                    <option value="8">REGISTRAR (J-II)</option>
                                                    <option value="9">REGISTRAR (J-III)</option>
                                                    <option value="10">REGISTRAR (J-IV)</option>
                                                    <option value="11">REGISTRAR (J-V)</option>
                                                    <option value="12">REGISTRAR (J-VI)</option>
                                                    <option value="13">REGISTRAR (OSD)</option>
                                                    <option value="14">HON'BLE THE CHIEF JUSTICE</option>
                                                </select>
                                            </div>

                                            <div class="control-label col-md-4  requiredField">
                                                <label class="col-sm-6">FIRST NAME:</label>
                                                <input class="form-control" type="TEXT" maxlength="50" id="first_name" name="first_name" required="required">
                                            </div>


                                            <div class="control-label col-md-4  requiredField">
                                            <label class="col-sm-6">SURNAME:</label>
                                            <input class="form-control" type="TEXT" id="sur_name" name="sur_name" required="required">
                                            </div>
                                        </div>

                                        <div id="row3" class="row required mt-4">

                                            <div class="control-label col-md-4  requiredField">
                                                <label class="col-sm-6">JCOURT:</label>
                                                <select class="form-control" id="jcourt" name="jcourt" required>
                                                    <option value="">---select---</option>
                                                    <?php
                                                    for ($x = 1; $x <= 15; $x++) {
                                                        echo '<option value="'. $x.'">'. $x.'</option>';
                                                    }
                                                    ?>
                                                    <option value=21>21</option>
                                                    <option value=22>22</option>
                                                </select>
                                            </div>

                                            <div class="control-label col-md-4  requiredField">
                                            <label class="col-sm-6">ABBREVIATION:</label>
                                            <input class="form-control" type="TEXT" id="abbreviation" name="abbreviation" required="required">
                                            </div>

                                            <div class="control-label col-md-4  requiredField">
                                            <label class="col-sm-6">IS_RETIRED:</label>
                                            <select class="form-control" id="retired" name="retired" required="required">
                                                <option value="">---select---</option>
                                                <option value="Y">Y</option>
                                                <option value="N">N</option>
                                            </select>
                                        </div>


                                        </div>

                                        <div  id="row4" class="row required mt-4">
                                            <div class="control-label col-md-4  requiredField">
                                                <label class="col-sm-6">DISPLAY:</label>
                                                <select class="form-control" id="display" name="display" required="required">
                                                    <option value="">---select---</option>
                                                    <option value="Y">Y</option>
                                                    <option value="N">N</option>
                                                </select>
                                            </div>

                                            <div class="control-label col-md-4  requiredField">
                                            <label class="col-sm-6">APOINTMENT_DATE:</label>
                                            <input type="text" id="from_date" value="<?php if(isset($_POST['from_date']))?>" name="from_date" class="form-control datepick"  placeholder="From Date" required="required">
                                            </div>

                                            <div class="control-label col-md-4  requiredField">
                                            <label class="col-sm-6">TO_DATE:</label>
                                            <input type="text" id="to_date" value="<?php if(isset($_POST['to_date']))?>" name="to_date" class="form-control datepick"  placeholder="to Date" required="required">
                                            </div>
                                        </div>

                                        <!--                            <div class="col-sm-1 col-md-1 col-lg-3 ">-->
                                        <!--                                <label class="col-sm-6">JTYPE:</label>-->
                                        <!--                                <select class="form-control" id="jtype" name="jtype" required="required">-->
                                        <!--                                    <option value="">---select---</option>-->
                                        <!--                                    <option value="J">J</option>-->
                                        <!--                                    <option value="R">R</option>-->
                                        <!--                                </select>-->
                                        <!--                            </div>-->
                                        <div id="row5" class="row required mt-4">

                                            <div class="control-label col-md-4  requiredField">
                                                <label class="col-sm-6">CJI_DATE:</label>
                                                <input type="text" id="cji_date" value="<?php if(isset($_POST['cji_date']))?>" name="cji_date" class="form-control datepick"  placeholder="Optional">
                                            </div>

                                            <div class="control-label col-md-4  requiredField">
                                            <label class="col-sm-6">JUDGE_SENIORITY:</label>
                                            <input class="form-control" type="number" id="judge_seniority" name="judge_seniority" required="required" placeholder="SENIORITY">
                                             </div>

                                             <div class="control-label col-md-2 mt-4" style="margin-left: auto;">
                                             <button type="submit" id="update" name="update" class="btn btn-block btn-primary">UPDATE</button>
                                            </div>

                                        </div>

                                    </div>
                                </form>
                                      <?php
                                        if(!empty($up_result))
                                        {
                                            if ($up_result == "updated")
                                                echo "<script type='text/javascript'>alert('Updated Successfully.');</script>";
                                            elseif ($up_result == "not updated")
                                                echo "<script type='text/javascript'>alert('Not Updated.');</script>";
                                            else
                                                echo "<script type='text/javascript'>alert('Please try again');</script>";
                                        }
                                        ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>

<script src="<?= base_url('/Ajaxcalls/menu_assign/menu_assign.js') ?>"></script>
<script src="<?=base_url()?>/assets/plugins/datatables/pdfmake.min.js"></script>
<!-- <script src="<?=base_url()?>/assets/plugins/datepicker/bootstrap-datepicker.js"></script> -->
<script src="<?=base_url()?>/assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?=base_url()?>/assets/plugins/datatables/dataTables.buttons.min.js"></script>
<script src="<?=base_url()?>/assets/plugins/datatables/buttons.print.min.js"></script>
<script src="<?=base_url()?>/assets/plugins/datatables/buttons.html5.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

<script type="text/javascript">
     
     $(document).ready(function() {
        $('#reportTable1').DataTable().destroy();
        $('#reportTable1 tbody').empty();
        getAllNotices();
        $("#display").hide();

        $(function () {
            //debugger;
            $('#from_date, #to_date,#cji_date').datepicker({
                format: 'dd-mm-yyyy',
                // startDate: new Date(),
                startDate: '1996/01/01',
                autoclose:true

            });
        });

        $('#info-alert').hide();

    } );

    $('#jtype').on('change', async function(){
        //updateCSRFToken();
        await updateCSRFTokenSync();
        var selectedValue = $(this).val();
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            url: "<?=base_url('/MasterManagement/JudgesController/get_name'); ?>",
            data: {selectedValue:selectedValue} ,
            cache: false,
            type: 'POST',
            headers: {
                    'X-CSRF-Token': CSRF_TOKEN_VALUE  
                    },
            success: function (data) {
                updateCSRFToken();
                //alert(data);
                $("#jname").html(data);
            },
            error: function () {
                updateCSRFToken();
                alert('Select Valid Option');
            }
        });

    });


    $("#button_id").click(function()
    {
        if($('#updated_for').val() == 0){
            alert("Please select Updated for.");
        }
        // debugger;
        $("#display").show();
        var updated_for = $('select[name=updated_for]').val();
        var from_date = $('input[name=from_date]').val();
        var to_date = $('input[name=to_date]').val();
        var dsc=$('#description').val();
        var usercode=$('#usercode').val();
        if(from_date!='' && to_date!='' && dsc!='') {
            
            updateCSRFToken();
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $.ajax({
                url: '<?=base_url();?>index.php/Latest_Updates/insert_Latest_updates',
                data: {
                    updated_for: updated_for,
                    from_date: from_date,
                    to_date: to_date,
                    dsc: dsc,
                    usercode: usercode
                },
                cache: false,
                dataType: 'json',
                type: "POST",
                headers: {
                        'X-CSRF-Token': CSRF_TOKEN_VALUE  
                        },
                success: function (data) {
                    updateCSRFToken();
                    //$('#reportTable1').DataTable().destroy();
                    // $('#reportTable1 tbody').empty();
                    getAllNotices();
                },
                error: function (ts) {
                    updateCSRFToken();
                    /* $("#info-alert").show();
                     $("#info-alert").fadeTo(2000, 500).slideUp(500, function(){
                         $("#info-alert").slideUp(500);
                     });*/
                    //alert(ts.responseText)
                }
            });
        }
        else
        {
            alert("Blank Data can't be inserted !");
        }
    });
    
    let alerted = false;
    
    $('#first_name').on('input', function() {
        let value = $(this).val();

        if (value.length > 50) {
            $(this).val(value.substring(0, 49));
        } else if (value.length >= 49 && !alerted) {
            alert("Please enter first name with maximum 50 characters");
            alerted = true;
        } else if (value.length < 49) {
            alerted = false;
        }
    });
    $('#abbreviation').on('input', function() {
        let value = $(this).val();
        if (value.length > 15) {
            $(this).val(value.substring(0, 14));
        } else if (value.length >= 14 && !alerted) {
            alert("Please enter first name with maximum 15 characters");
            alerted = true;
        } else if (value.length < 14) {
            alerted = false;
        }
    });
    $('#judge_seniority').on('input', function() {
        let value = $(this).val();
        if (value.length > 5) {
            $(this).val(value.substring(0, 5));
        } else if (value.length >= 5 && !alerted) {
            alert("Please enter first name with maximum 5 characters");
            alerted = true;
        } else if (value.length < 5) {
            alerted = false;
        }
    });


    // $("#jname").change(function(){
    //     var jcode = $("#jname").val();
    //     document.getElementById('jcode').value=jcode;
    //     //alert(jname);
    // });

    //$("#jname").change(function(){
    $('#jname').on('change', async function(){    
        //debugger
        await updateCSRFTokenSync();
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        var CSRF_TOKEN = 'CSRF_TOKEN';        
        var jcodeid = $("#jname").val();
        if(jcodeid == '' ){
            alert("Please Select Jname");
            return false;
        }
        document.getElementById('jcode').value=jcodeid;
        $.ajax
        ({
            url: '<?php echo base_url();?>/MasterManagement/JudgesController/GetDet',
            cache: false,
            async: true,
            dataType: 'json',
            data: {jcodeid: jcodeid,
                CSRF_TOKEN: CSRF_TOKEN_VALUE 
            },
            type: 'POST',
            success: function (data) {
                updateCSRFToken();
                //debugger;
                console.log(data);

                $('#dv_res1').html(data);
                //$("#jname").val(data[0].jname);
                $("#first_name").val(data[0].first_name);
                console.log(data[0].title);
                $("#title").find("option[value="+data[0].head+"]").prop("selected", "selected");
                //$("#title").val(data[0].title);
                $("#sur_name").val(data[0].sur_name);
                $("#jcourt").find("option[value="+data[0].jcourt+"]").prop("selected", "selected");
                // $("#jcourt").val(data[0].jcourt);
                $("#abbreviation").val(data[0].abbreviation);
                $("#retired").find("option[value="+data[0].is_retired+"]").prop("selected", "selected");
                //$("#retired").val(data[0].retired);
                $("#display").val(data[0].display);

                $("#from_date").val(data[0].appointment_date);
                $("#to_date").val(data[0].to_dt);
                $("#cji_date").val(data[0].cji_date);
                $("#judge_seniority").val(data[0].judge_seniority);




            },
            error: function (xhr) {
                updateCSRFToken();
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
    });


    function getAllNotices()
    {
      
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            url: '<?php echo base_url();?>/MasterManagement/JudgesController/display_Latest_Updates',
            data: {},
            cache:  false,
            dataType: 'json',
            type: "POST",
            headers: {
                    'X-CSRF-Token': CSRF_TOKEN_VALUE  
                    },
            success: function(data){
                if(data.length > 0)
                {
                    $("#display").show();
                    $('#reportTable1 tbody').empty();
                    sno = 1;
                    $.each(data, function (index) {

                        $('#reportTable1 tbody').append("<tr><td>" + sno + "</td><td>" + data[index].menu_name + "</td><td>" + data[index].title_en + "</td><td>" + data[index].f_date + "</td><td>" + data[index].t_date + "</td></tr>");
                        sno++;
                    });

                    $('#reportTable1').DataTable({
                        "bSort": true,
                        dom: 'Bfrtip',
                        "scrollX": true,
                        iDisplayLength: 8,

                        buttons: [
                            {
                                extend: 'print',
                                orientation: 'landscape',
                                pageSize: 'A4'
                            }
                        ]
                    });
                }
                else

                {
                    $("#display").hide();
                    $("#info-alert").show();
                    $("#info-alert").fadeTo(2000, 500).slideUp(500, function(){
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




         
</script>
