<?= $this->extend('header') ?>
<?= $this->section('content') ?>
    <style>
    </style>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header heading">

                            <div class="row">
                                <div class="col-sm-10">
                                    <h3 class="card-title">Office Reports </h3>
                                </div>
                                <div class="col-sm-2">
                                    <div class="custom_action_menu">
                                        <a href="<?=base_url('Extension/OfficeReport');?>"><button class="btn btn-primary btn-sm" type="button"><i class="fas fa-pen	" aria-hidden="true"></i></button></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <?= view('Extension/OfficeReportViewFiles/office_report_menus'); ?>


                                        <?php
                                        if($_SESSION['filing_details']['c_status']=='D')
                                            echo '<br/><br/><span class="text-red">The searched case is disposed. Office report in disposed case is not allowed.</span>';
                                        else{
                                            $attribute = array('class' => 'form-horizontal reprint','name' => 'reports', 'id' => 'reports', 'autocomplete' => 'off');
                                            echo form_open(base_url('#'), $attribute);
                                            ?>

                                            <br><br><br>

                                            <div class="col-sm-12">
                                                <div class="row">
                                                    <div class="col-sm-3">
                                                        <div class="form-group row " >
                                                            <label for="nature" class="col-sm-3 col-form-label">Nature: </label>
                                                            <div class="col-sm-7">
                                                                <select  class="form-control" name="ddl_nature" id="ddl_nature" onChange="selectNature()" >
                                                                    <option value="0">Select</option>
                                                                    <?php
                                                                    if(!empty($nature))
                                                                    {
                                                                        foreach($nature as $row)
                                                                        {
                                                                            ?>
                                                                            <option value="<?php echo $row['nature'] ?>">
                                                                                <?php if($row['nature']=='R') { ?> Criminal <?php  } else if($row['nature']=='C') { ?> Civil <?php } ?>
                                                                            </option>
                                                                            <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!--</div>

                                                      <div class="row">-->
                                                    <div class="col-sm-5">
                                                        <div class="form-group row " >
                                                            <label for="reporttype" class="col-sm-3 col-form-label">Report Type: </label>
                                                            <div class="col-sm-7">

                                                                <select  class="form-control" name="ddl_rt" id="ddl_rt" >

                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-3">
                                                        <!--                                                        <div class="form-group row " >-->
                                                        <div class="col-sm-2">
                                                            <button type="button" name="reportBtnSubmit" id="reportBtnSubmit" onClick="submitFunc()" class="btn btn-primary" >Submit</button>
                                                        </div>
                                                        <!--                                                        </div>-->
                                                    </div>

                                                </div>

                                            </div>
                                            <br><br>


                                            <?php form_close();
                                        }
                                        ?>

                                        <!--                                        --><?//= view('Common/Editor/editor'); ?>
                                        <div id="mess_display" align='center' style='color: red'></div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="<?php echo base_url('plugins/jquery-validation/jquery.validate.js'); ?>"></script>
    <script src="<?php echo base_url('plugins/jquery-validation/additional-methods.js'); ?>"></script>

    <script>


        function selectNature()
        {
            var ddl_nature= $('#ddl_nature').val();
            // console.log(ddl_nature);
            // return false;
            $('#ddl_rt').html('');
            get_report_type(ddl_nature);
        }
        var validationError = false;
        function get_report_type(ddl_nature)
        {
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $.ajax({
                type: 'POST',
                url: '<?php echo base_url('Extension/OfficeReport/get_report_type'); ?>',
                cache: false,
                async: true,
                // dataType: 'JSON',
                data: {
                    ddl_nature: ddl_nature,
                    CSRF_TOKEN: CSRF_TOKEN_VALUE
                },
                success: function(data) {
                    updateCSRFToken();
                    var html='';
                    var dataArr = JSON.parse(data);
                    // alert(">>>>"+data[6].r_nature);
                    // console.log(data[6].id)
                    // return false;
                    html += '<option value="" >'+ 'Select' +'</option>';
                    dataArr.forEach(e =>{
                        html += '<option value="'+e.id+'" >'+ e.r_nature +'</option>';
                    });
                    $('#ddl_rt').append(html);


                },
                error: function(data) {
                    updateCSRFToken();
                    alert(data);
                }

            });
        }

        function submitFunc()
        {
            var validationError = false;
            var reportType = $('#ddl_rt').val();
            var nature = $('#ddl_nature').val();
            // condition for nature and type of notice//
            // console.log(reportType+">>"+nature);
            // return false;
            if(nature.trim()=='')
            {
                alert("Please Select Nature ");
                $('#ddl_nature').focus();
                validationError = true;
                return false;
            }
            if(reportType.trim()=='')
            {
                alert("Please Select Type Of Office Report You Want To Generate");
                $('#ddl_rt').focus();
                validationError = true;
                return false;
            }
            if(validationError==false) {
                var CSRF_TOKEN = 'CSRF_TOKEN';
                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('Extension/OfficeReport/display_office_report'); ?>",
                    data: {
                        CSRF_TOKEN: CSRF_TOKEN_VALUE,
                        rtype: reportType,
                        nature: nature,
                        dno:'<?php echo $_SESSION['filing_details']['diary_no']; ?>'
                    },
                    success: function (data) {
                        updateCSRFToken();
                        // alert(typeof(data));
                        if(typeof(data) == "string")
                        {
                            $('#mess_display').html(data);
                        }

                    },
                    error: function (data) {
                        updateCSRFToken();
                        alert(data);
                    }

                });
            }else {
                return false;
            }


        }


    </script>
<?= $this->endSection() ?>