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

                    <br> <h4 class="basic_heading"> Defect Details </h4>
                    <br><br>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">

                                <div class="card-body">
                                    <div class="tab-content">
                                    <input type="text" id="t_h_cno" name="t_h_cno"  size="5" value="<?php echo isset($_SESSION['filing_details']) ? $_SESSION['filing_details']['diary_number'] : ''; ?>" style="display: none;"/>
                                    <input type="text" id="t_h_cyt" name="t_h_cyt"  size="5" value="<?php echo isset($_SESSION['filing_details']) ? $_SESSION['filing_details']['diary_year'] : ''; ?>" style="display: none;"/>
                                        <!--   <form action=<?php /*echo htmlspecialchars($_SERVER['PHP_SELF']);*/?>" method="POST">-->
                                        <?php  //echo $_SESSION["captcha"];
                                        $attribute = array('class' => 'form-horizontal','name' => 'diary_search', 'id' => 'diary_search', 'autocomplete' => 'off');
                                        echo form_open(base_url('#'), $attribute);
                                        ?>


                                        <span class="alert-danger"><?= \Config\Services::validation()->listErrors() ?></span>



                                        <!--                                        --><?php //if (session()->has('success_message')): ?>
                                        <!--                                            <div class="alert alert-success">-->
                                        <!--                                                --><?//= session('success_message') ?>
                                        <!--                                            </div>-->
                                        <!--                                        --><?php //endif; ?>
                                        <!---->
                                        <!--                                        --><?php //if (session()->has('error_message')): ?>
                                        <!--                                            <div class="alert alert-info">-->
                                        <!--                                                --><?//= session('error_message') ?>
                                        <!--                                            </div>-->
                                        <!--                                        --><?php //endif; ?>


                                        <?php

                                        $filing_details = session()->get('filing_details');
                                        $user_details = session()->get('login');
                                        //                print_r( $filing_details); exit;

                                        ?>
                                        <div class="success-msg" id="message_display">


                                        </div>
                                        <!--  <div class="formessage" id="message_display"> </div>-->

                                        <div class="card-header">
                                            <h3 class="card-title" style="color:red">Already Marked Defects</h3>
                                        </div>

                                        <table id="defect_table" class="table table-bordered table-striped">
                                            <thead>
                                            <tr>
                                                <th>Defect</th>
                                                <th>Remarks</th>
                                                <th>Action</th>

                                            </tr>
                                            </thead>
                                            <tbody>


                                            <?php
                                            if(!empty($old_defect))
                                            {

                                                foreach($old_defect as $old_row)
                                                {
                                                    $id = $old_row['id'];
                                                    ?>
                                                    <tr>
                                                        <td style="width:50%"><?php echo $old_row['obj_name'];?></td>
                                                        <td><input type="text" name ="already_remark" id="already_remark_<?php echo $id;?>" value="<?php echo $old_row['remark'];?>" readonly></td>
                                                        <td><?php
                                                            if($old_row['rm_dt']==null)
                                                            {?>
                                                                <button type="button" style="display: block;"  name="edit" id="edit_<?php echo $id;?>" onclick="edit_function(<?=$id;?>)" >Edit</button>
                                                                <button type="button"  name="update" id="update_<?php echo $id;?>" style="display: none;" onclick="update_function(<?=$id?>)">Update</button>
                                                                <input type="hidden" name="def_name" id="def_name_<?php echo $id;?>" value="<?php echo $old_row['obj_name'];?>" >
                                                                <?php
                                                            }else{
                                                                echo "<span style='color:green;'>Removed</span>";
                                                            }?>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }

                                            }  ?>



                                            </tbody>

                                        </table>
                                        <br><br>

                                        <div class="card-header">
                                            <h3 class="card-title" style="color:red">To Add More Objections</h3>
                                        </div>
                                        <br><br>

                                        <!--extra address respondend-->
                                        <div id="add_adres" style=" width:100%; display:block;" class="multi-field-wrapper mb-4">
                                            <div class="multi-fields" >
                                                <div class="multi-field">
                                                    <span id="addRowOther" class="add-field_heading btn btn-success float-sm-right mt-4"><i class='fas fa-plus-circle'></i></span>
                                                    <!-- <button type="button"  class="remove_in_row_heading remove-field_heading d-none btn btn-outline-danger mt-4 float-sm-right" value="1"><i class='fas fa-minus-circle'></i></button> -->
                                                    <div class="row all_data">
                                                        <div class="col-md-6 div1">
                                                            <label>Defect</label>
                                                            <select class="form-control custom-select rounded-0 select2" name="defect" id="defect1" style="width:100%" onchange="checkDuplicateDefectId(this.id,this.value)" required="required">
                                                                <option value="" title="Select"></option>
                                                                <?php
                                                                foreach($all_defect as $row) { ?>
                                                                    <option value="<?= $row['org_id']; ?>" ><?= $row['obj_name']; ?> </option>
                                                                    <?php
                                                                }
                                                                ?>

                                                            </select>
                                                        </div>
                                                        <div class="col-md-6 div2">
                                                            <label>Remark</label>

                                                            <input type="text" name="remark_add" id="remark_add" class="form-control" >
                                                        </div>
                                                    </div>



                                                </div>
                                            </div>

                                            <!-- <div class="11uploadedFile" style="display: inline-grid;width: 100%;margin-bottom: 1%;"></div> -->
                                            <div id="newRowOther"></div>

                                        </div>
                                        <!--end extra Address respondent-->

                                        <br>

                                    </div>

                                </div>
                            </div>
                            </br>

                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <button type="button" name="save" id ="save" class="btn btn-primary" value="save_data"  onclick='save_data()' >Submit</button>                                    &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;

                                    <span style="color: red">Please click SMS button after adding all defects.</span>  <input type="button" class="btn btn-primary" name="btn_sms" id="btn_sms" onclick="sendSMS()" value="SMS & Email"/>
                                    <div id="sp_sms_status" style="text-align: center"></div>


                                </div>
                            </div>
                            <br>

                            <?php form_close();?>
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
    function edit_function(id)
    {
        // alert("jj");
        //  alert(id);
        $('#already_remark_'+id).attr("readonly", false);
        document.getElementById("edit_"+id).style.display='none';
        document.getElementById("update_"+id).style.display='block';

    }

    function update_function(id)
    {
        //  alert(id);
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        // var dName = document.getElementById("def_name_"+id).value;
        var newRemark = document.getElementById("already_remark_"+id).value;
        var dno ='<?= $filing_details['diary_no'] ?>';
        var ucode ='<?= $user_details['usercode'] ?>';
        //  alert(newRemark);

        // alert(dName+">>>>"+newRemark+">>>"+id);

        $.ajax({
            type:"POST",
            data: {
                CSRF_TOKEN: CSRF_TOKEN_VALUE,
                // 'def_name': dName,
                'new_remark':newRemark,
                'diaryno':dno,
                'ucode':ucode,
                'defid':id
            },
            url: "<?php echo base_url('Filing/Defect/update_function'); ?>",
            success: function(data) {
                //    alert(data);
                if(data)
                {
                    alert(data);
                    window.location.reload();
                }

                //console.log("SSSS"+data);
                //   $('.message_display').append(data);
                $('#sub_category').html(data);
                updateCSRFToken();
            },
            error: function(data) {
                alert(data);
                updateCSRFToken();
            }
        });





    }

</script>

<script>
    var arr=[];
    function checkDuplicateDefectId(rowid,defectid)
    {
        var rowSelectId = rowid;
        var defectValue = defectid;
        arr.push(defectValue);
        // console.log(arr);
        $("#"+rowSelectId+ "option[value='"+defectValue+"']").remove();

    }
</script>

<script>
    var addrowCount = 1
    var defectArr = <?= json_encode($all_defect, true)  ?>;
    // console.log("defectArr:: ", defectArr)

    $("#addRowOther").click(function () {


        addrowCount = addrowCount + 1
        var html = '';
        html += '<div class="multi-fields" id="inputFormRow">'
        html += '<div class="multi-field">'
        html += '<span class="remove_in_row_heading remove-field_heading btn btn-danger mt-4 float-sm-right removeRowOther"><i class="fas fa-minus-circle"></i></span>'
        html += '<div class="row all_data">'
        html += '<div class="col-md-6 div1">'
        html += '<label>Defect</label>'
        html += '<select class="duplicate form-control custom-select rounded-0 select2" name="defect" id="defect'+addrowCount+'"  style="width:100%" onchange="checkDuplicateDefectId(this.id,this.value)" required="required">'
        html += '<option value="" title="Select" ></option>';

        defectArr = defectArr.filter(item=> !arr.includes(item.org_id.toString()) );
        // console.log(newArr);

        defectArr.forEach(e =>{
            html += '<option value="'+e.org_id+'" >'+ e.obj_name +'</option>'
        });

        html += '</select></div>'
        html += '<div class="col-md-6 div2">'
        html += '<label>Remark</label>'
        html += '<input type="text" name="remark_add" id="remark_add" class="form-control" >'

        html += '</div></div>'
        $('#newRowOther').append(html);
        $('.select2').select2();

    });

    $(document).on('click', '.removeRowOther', function () {
        $(this).closest('#inputFormRow').remove();
    });
</script>





<script>
    function updateCSRFToken() {
        $.getJSON("<?php echo base_url('Csrftoken'); ?>", function (result) {
            $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
        });
    }

    function save_data()
    {
        //  alert("sdf");
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        var dno ='<?= $filing_details['diary_no'] ?>';
        var ucode ='<?= $user_details['usercode'] ?>';

        var bigdiv = $('.all_data');
        var dataArr = [];
        var dataArr1 = [];
        bigdiv.each(function(v, k)
        {
            var defeId = $(k).children().closest(".div1").children().closest('select').val();
            // if(defeId !== null)
            dataArr.push(defeId);

        })
        // console.log(dataArr);
        bigdiv.each(function(v, k)
        {
            var remark = $(k).children().closest(".div2").children().closest('input').val();
            // alert(remark);
            if(remark == '')
            {
                remark ='';
            }
            dataArr1.push(remark)
        })
        // console.log(dataArr);
        if(dataArr !='') {

            $.ajax({
                type: "POST",
                data: {
                    CSRF_TOKEN: CSRF_TOKEN_VALUE,
                    'diary_no': dno,
                    'ucode': ucode,
                    'defid': dataArr,
                    'remark': dataArr1

                },
                url: "<?php echo base_url('Filing/Defect/insert_function'); ?>",
                success: function (data) {
                    if (data) {
                        alert(data);
                        window.location.reload();
                    }
                    $('#sub_category').html(data);
                    updateCSRFToken();
                },
                error: function (data) {
                    alert(data);
                    updateCSRFToken();
                }
            });
        }else
        {
            alert('Please Select The Defects First!!!!!!!');
        }

    }

</script>

<script>
    $(function () {
        //Initialize Select2 Elements
        $('.select2').select2()

        //Initialize Select2 Elements
        $('.select2bs4').select2({
            theme: 'bootstrap4'
        })
    })
</script>



<script>

    //Datemask dd/mm/yyyy
    $('#datemask').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' })
    //Datemask2 mm/dd/yyyy
    $('#datemask2').inputmask('mm/dd/yyyy', { 'placeholder': 'mm/dd/yyyy' })
    //Money Euro
    $('[data-mask]').inputmask()

    //Date picker
    $('#reservationdate').datetimepicker({
        format: 'L'
    });

    //Date and time picker
    $('#reservationdatetime').datetimepicker({ icons: { time: 'far fa-clock' } });

    //Date range picker
    $('#reservation').daterangepicker()
    //Date range picker with time picker
    $('#reservationtime').daterangepicker({
        timePicker: true,
        timePickerIncrement: 30,
        locale: {
            format: 'MM/DD/YYYY hh:mm A'
        }
    })
    //Date range as a button
    $('#daterange-btn').daterangepicker(
        {
            ranges   : {
                'Today'       : [moment(), moment()],
                'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month'  : [moment().startOf('month'), moment().endOf('month')],
                'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            },
            startDate: moment().subtract(29, 'days'),
            endDate  : moment()
        },
        function (start, end) {
            $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
        }
    )

    //Timepicker
    $('#timepicker').datetimepicker({
        format: 'LT'
    })


    //Colorpicker
    $('.my-colorpicker1').colorpicker()
    //color picker with addon
    $('.my-colorpicker2').colorpicker()

    $('.my-colorpicker2').on('colorpickerChange', function(event) {
        $('.my-colorpicker2 .fa-square').css('color', event.color.toString());
    })

    $("input[data-bootstrap-switch]").each(function(){
        $(this).bootstrapSwitch('state', $(this).prop('checked'));
    })

    //  })
    // BS-Stepper Init
    document.addEventListener('DOMContentLoaded', function () {
        window.stepper = new Stepper(document.querySelector('.bs-stepper'))
    })

    // DropzoneJS Demo Code Start
    Dropzone.autoDiscover = false

    // Get the template HTML and remove it from the doumenthe template HTML and remove it from the doument
    var previewNode = document.querySelector("#template")
    previewNode.id = ""
    var previewTemplate = previewNode.parentNode.innerHTML
    previewNode.parentNode.removeChild(previewNode)

    var myDropzone = new Dropzone(document.body, { // Make the whole body a dropzone
        url: "/target-url", // Set the url
        thumbnailWidth: 80,
        thumbnailHeight: 80,
        parallelUploads: 20,
        previewTemplate: previewTemplate,
        autoQueue: false, // Make sure the files aren't queued until manually added
        previewsContainer: "#previews", // Define the container to display the previews
        clickable: ".fileinput-button" // Define the element that should be used as click trigger to select files.
    })

    myDropzone.on("addedfile", function(file) {
        // Hookup the start button
        file.previewElement.querySelector(".start").onclick = function() { myDropzone.enqueueFile(file) }
    })

    // Update the total progress bar
    myDropzone.on("totaluploadprogress", function(progress) {
        document.querySelector("#total-progress .progress-bar").style.width = progress + "%"
    })

    myDropzone.on("sending", function(file) {
        // Show the total progress bar when upload starts
        document.querySelector("#total-progress").style.opacity = "1"
        // And disable the start button
        file.previewElement.querySelector(".start").setAttribute("disabled", "disabled")
    })

    // Hide the total progress bar when nothing's uploading anymore
    myDropzone.on("queuecomplete", function(progress) {
        document.querySelector("#total-progress").style.opacity = "0"
    })

    // Setup the buttons for all transfers
    // The "add files" button doesn't need to be setup because the config
    // `clickable` has already been specified.
    document.querySelector("#actions .start").onclick = function() {
        myDropzone.enqueueFiles(myDropzone.getFilesWithStatus(Dropzone.ADDED))
    }
    document.querySelector("#actions .cancel").onclick = function() {
        myDropzone.removeAllFiles(true)
    }
    // DropzoneJS Demo Code End

    // $(document).on('click','#btn_sms',function(){
    function sendSMS(){
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        var t_h_cno=$('#t_h_cno').val();
        var t_h_cyt=$('#t_h_cyt').val();
        // send_email(t_h_cno,t_h_cyt);
        $.ajax({
            url: base_url+'/SmsController',
            cache: false,
            async: true,
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, d_no: t_h_cno, d_yr: t_h_cyt,sms_status:'D'},
            beforeSend: function() {
                $('#sp_sms_status').html('<table widht="100%" align="center"><tr><td><img src="../images/load.gif"/></td></tr></table>');
            },
            type: 'POST',
            success: function(data, status) {
                $('#sp_sms_status').html(data);
                updateCSRFToken();
            },
            error: function(xhr) {
                alert("Error: " + xhr.status + " " + xhr.statusText);
                updateCSRFToken();
            }

        });
    }


function send_email(t_h_cno,t_h_cyt){
    var CSRF_TOKEN = 'CSRF_TOKEN';
    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
    $.ajax({
        url: base_url+'/SendEmailController',
        cache: false,
        async: true,
        data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, d_no: t_h_cno, d_yr: t_h_cyt},
        beforeSend: function() {
            $('#sp_sms_status').html('<table widht="100%" align="center"><tr><td><img src="../images/load.gif"/></td></tr></table>');
        },
        type: 'POST',
        success: function(data, status) {
            $('#sp_sms_status').html(data);
            alert(data);
            updateCSRFToken();
        },
        error: function(xhr) {
            alert("Error: " + xhr.status + " " + xhr.statusText);
            updateCSRFToken();
        }

    });
}
</script>
