<?= view('header') ?>

<script type="text/javascript">
    $(document).ready(function() {
        $("#crd1").datepicker({
            dateFormat: "dd-mm-yy"
        });

        $('#mdd').hide();
        $('#data').hide();
        $('#sbtn1').hide();
        $('#register_rc').hide();
        $('#cein').focus();

        $("#crd1").change(function() {
            $('#register_rc').show();
        });

        $("#md").click(function() {
            $('#mdd').toggle();
        });

        $("#search").click(function() {
            var cein = $("#cein").val();
            if (!cein) {
                alert("Please Enter Mandatory Values");
                return false;
            }

            $.ajax({
                type: "get",
                url: "<?php echo base_url('Record_room/Record/getAorOptions'); ?>",
                data: {
                    tvap: cein
                },
                cache: false,
                success: function(result)
                {
                    console.log("Raw result:", result);

                    if (result.success) {
                        var rres = result.success.split("#");
                        $('#sbtn').hide();
                        $('#sbtn1').show();
                        $('#sbtn1').prop('disabled', false); // Enable sbtn1 if result is not empty
                        $('#data').hide();
                        $('#afc').html(rres[0]);
                        $('#vadvc').focus();
                        $('#rslt').html("");
                    } else {
                        var rres = result.error.split("#");
                        $('#afc').html(rres[0]);
                        $('#sbtn1').prop('disabled', false); // Disable sbtn1 if result is empty
                        $('#rslt').html("<h4>Record Not found</h4>");
                    }
                },
                error: function() {
                    $('#rslt').html("<h4>An error occurred while processing your request.</h4>");
                }
            });
            return false;
        });


        $("#search1").click(function() {
            var tvap = $("#cein").val();
            var vadvc = $("#vadvc").val();

            if (!tvap) {
                alert("Please Enter Mandatory Values");
                return false;
            }

            $.ajax({
                type: "get",
                url: "<?php echo base_url('Record_room/Record/getAorOptions1'); ?>",
                data: {
                    tvap: tvap,
                    vadvc: vadvc
                },
                dataType: "text", // Expecting text response, not JSON
                cache: false,
                success: function(result) {
                    console.log("Raw result:", result);

                    if (result) {
                        var rres = result.split("#");
                        // console.log("Split result:", rres);
                        $('#acn').val(rres[2] || "");
                        $('#cfn').val(rres[3] || "");
                        $('#cmobile').val(rres[15] || "");
                        $('#cpal1').val(rres[4] || "");
                        $('#cpal2').val(rres[5] || "");
                        $('#cpad').val(rres[6] || "");
                        $('#cpapin').val(rres[7] || "");
                        $('#cppal1').val(rres[8] || "");
                        $('#cppal2').val(rres[9] || "");
                        $('#cppad').val(rres[10] || "");
                        $('#cppapin').val(rres[11] || "");
                        $('#cdob').val(rres[12] || "");
                        $('#cpob').val(rres[13] || "");
                        $('#cn').val(rres[14] || "");
                        $('#cx').val(rres[16] || "");
                        $('#cxii').val(rres[17] || "");
                        $('#cug').val(rres[18] || "");
                        $('#cpg').val(rres[19] || "");
                        $('#crno').val(rres[21] || "");
                        $('#rslt').html("");
                        $('#data').show();
                        fetchtds();
                    } else {
                        $('#rslt').html("<h4>Record Not found</h4>");
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error("Error occurred:", textStatus, errorThrown);
                    $('#rslt').html("<h4>An error occurred while processing your request.</h4>");
                }
            });
            return false;
        });


        function fetchtds() {
            var tid = $('#crno').val();
            $.ajax({
                type: "get",
                url: "<?php echo base_url('Record_room/Record/fetchTds'); ?>",
                data: {
                    tid: tid
                },
                cache: false,
                success: function(result) {
                    if (result) {
                        $('#history').html("<span style='color:#E74C3C;'>" + result + "</span>");
                    } else {
                        $('#history').html("<h4>Record Not found</h4>");
                    }
                },
                error: function() {
                    $('#history').html("<h4>An error occurred while processing your request.</h4>");
                }
            });
        }

        $("#register_rc").click(async function() {
            await updateCSRFTokenSync();
            var action = $("#action").val();
            var crd1 = $("#crd1").val();
            var accr = $("#accr").val();
            var crno = $("#crno").val();
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            var tvap = action + ";" + crd1 + ";" + accr + ";" + crno;
            if (action > 0) {
                $.ajax({
                    type: "post",
                    url: "<?= base_url('Record_room/Record/RenewalRegister') ?>",
                    data: {
                        tvap: tvap,
                        CSRF_TOKEN: CSRF_TOKEN_VALUE,
                    },

                    cache: false,
                    beforeSend: function() {
                        $('#rs_loader').html('<div style="position: absolute; top: 50%; left: 50%; text-align: center; -webkit-transform: translate(-50%, -50%); transform: translate(-50%, -50%);"><img src="<?= base_url(); ?>/images/load.gif"/></div>');
                    },
                    success: function(result) {
                        $('#rs_loader').html('');
                        if (result.success) {
                            $('#rslt1').html("<div style='text-align: center; font-weight: bold; color: green;'>" + result.success + "</div>");
                        } else if (result.error) {
                            $('#rslt1').html("<div style='text-align: center; font-weight: bold; color: red;'>" + result.error + "</div>");
                        }
                        $('#crd1').val("");
                        $('#action').val(0);
                        $('#accr').val("");
                        $('#cein').focus();
                        $('#register_rc').hide();
                        fetchtds();
                    },
                    error: function() {
                        $('#rs_loader').html('');
                        $('#rslt1').html("<div style='text-align: center; font-weight: bold; color: red;'>" + result.error + "</div>");
                    }
                });
            } else {
                alert("Please Select Action");
                return false;
            }
            // return false;
        });
    });
</script>

<section class="content  mt-3">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12  mt-6">
                <div class="card">
                    <div class="card-header heading">

                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title"> Record Room >> Advocate Clerk >> Renewal/Cancellation </h3>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card mt-3">
                                <div class="card-body">
                                    <form class="form-horizontal" role="form" name="form1" autocomplete="off">
                                        <?= csrf_field() ?>
                                        <div class="row form-group">
                                            <label class="control-label col-sm-2 mt-3" for="anumber">Existing Icard No. *</label>
                                            <div class="col-sm-2">
                                                <input class="form-control" name="cein" type="text" id="cein" placeholder="ICard Number">
                                            </div>
                                            <div class="col-sm-2" id="sbtn">
                                                <button type="button" class="btn btn-primary" name="submit" id="search">
                                                    <span class="glyphicon glyphicon-plus"></span> Search
                                                </button>
                                            </div>
                                            <label class="control-label col-sm-2 mt-3" for="atitle">AOR/Firm Code *</label>
                                            <div class="col-sm-4" id="afc">
                                                <input class="form-control" name="aorn" type="text" id="aorn" placeholder="Name" disabled>
                                            </div>
                                            <div class="col-sm-2" id="sbtn1">
                                                <button type="button" class="btn btn-primary" name="submit" id="search1">
                                                    <span class="glyphicon glyphicon-plus"></span> Search
                                                </button>
                                            </div>
                                        </div>

                                        <div id="data">
                                            <div class="row form-group ">
                                                <label class="control-label col-sm-2" for="anumber">Name *</label>
                                                <div class="col-sm-4"><input class="form-control " name="acn" type="text" id="acn" placeholder="Name"
                                                        disabled> </div>
                                                <label class="control-label col-sm-2 mt-2" for="anumber">Father Name *</label>
                                                <div class="col-sm-4"> <input class="form-control " name="cfn" type="text" id="cfn" placeholder="Father Name" disabled> </div>
                                            </div>


                                            <div class="row form-group">
                                                <label class="control-label col-sm-2" for="anumber">Mobile Number</label>
                                                <div class="col-sm-4">
                                                    <input class="form-control" name="cmobile" type="text" id="cmobile" placeholder="Mobile Number"
                                                        disabled>
                                                </div>
                                                <label class="control-label col-sm-2 mt-2" for="anumber">Record Number</label>
                                                <div class="col-sm-4">
                                                    <input class="form-control " name="crno" type="text" id="crno" placeholder="Record Number" disabled>
                                                </div>
                                            </div>

                                            <div class="col-sm-12" id='md' style="text-align:right;color:blue;font:strong 10px Gothic;">view more
                                                details</div>
                                            <div id='mdd'>
                                                <div class="row form-group">
                                                    <label class="control-label col-sm-2" for="anumber">Present Address</label>
                                                    <div class="col-sm-3"><input class="form-control " name="cpal1" type="text" id="cpal1"
                                                            placeholder="Address Line1" disabled></div>
                                                    <div class="col-sm-3"><input class="form-control " name="cpal2" type="text" id="cpal2"
                                                            placeholder="Address Line2" disabled></div>
                                                    <div class="col-sm-2"><input class="form-control " name="cpad" type="text" id="cpad"
                                                            placeholder="District" disabled></div>
                                                    <div class="col-sm-2"><input class="form-control " name="cpapin" type="text" id="cpapin"
                                                            placeholder="Pincode" disabled></div>
                                                </div>

                                                <div class="row form-group">
                                                    <label class="control-label col-sm-2" for="anumber">Permanent Address</label>
                                                    <div class="col-sm-3"><input class="form-control " name="cppal1" type="text" id="cppal1"
                                                            placeholder="Address Line1" disabled></div>
                                                    <div class="col-sm-3"><input class="form-control " name="cppal2" type="text" id="cppal2"
                                                            placeholder="Address Line2" disabled></div>
                                                    <div class="col-sm-2"><input class="form-control " name="cppad" type="text" id="cppad"
                                                            placeholder="District" disabled></div>

                                                    <div class="col-sm-2"><input class="form-control " name="cppapin" type="text" id="cppapin"
                                                            placeholder="Pincode" disabled></div>
                                                </div>

                                                <div class="row form-group">
                                                    <label class="control-label col-sm-2" for="anumber">Date of Birth</label>
                                                    <div class="col-sm-2"><input class="form-control " name="cdob" type="text" id="cdob" placeholder="DOB"
                                                            disabled></div>

                                                    <label class="control-label col-sm-2 mt-3" for="anumber">Place of Birth</label>
                                                    <div class="col-sm-2"> <input class="form-control " name="cpob" type="text" id="cpob"
                                                            placeholder="Birth Place" disabled> </div>

                                                    <label class="control-label col-sm-2 mt-3" for="anumber">Nationality</label>
                                                    <div class="col-sm-2">
                                                        <input class="form-control " name="cn" type="text" id="cn" placeholder="Nationality" disabled>
                                                    </div>
                                                </div>

                                                <div class="row form-group">
                                                    <label class="control-label col-sm-2" for="anumber">Educational Qualifications</label>
                                                    <div class="col-sm-2"><input class="form-control " name="cx" type="text" id="cx" placeholder="X"
                                                            disabled></div>
                                                    <div class="col-sm-2"><input class="form-control " name="cxii" type="text" id="cxii" placeholder="XII"
                                                            disabled></div>
                                                    <div class="col-sm-3"><input class="form-control " name="cug" type="text" id="cug" placeholder="UG"
                                                            disabled></div>
                                                    <div class="col-sm-3"><input class="form-control " name="cpg" type="text" id="cpg" placeholder="PG"
                                                            disabled></div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-sm-2" style="padding-left: 20px;" for="anumber">History</label>
                                                <div class="col-sm-10 ml-3 text-bold" id="history"> </div>
                                            </div>

                                            <div class="row form-group">
                                                <label class="control-label col-sm-2" for="anumber">Action</label>
                                                <div class="col-sm-2">
                                                    <select class="form-control" id="action">
                                                        <option value=0>Action</option>
                                                        <option value=1>Register</option>
                                                        <option value=2>Renew</option>
                                                        <option value=3>Cancel</option>
                                                        <option value=4>Deletion</option>
                                                    </select>
                                                </div>
                                                <label class="control-label col-sm-2 mt-3" for="anumber">Dated: </label>
                                                <div class="col-sm-2"><input class="form-control datepicker" name="crd1" type="text" id="crd1" placeholder="Dated">
                                                </div>
                                                <label class="control-label col-sm-1  mt-3 mt-3" for="atitle">Remarks</label>
                                                <div class="col-sm-3 "><input class="form-control" name="accr" type="text" id="accr"
                                                        placeholder="Remarks"></div>
                                            </div>

                                            <div class="form-group">
                                                <div class="col-sm-offset-2 col-sm-10">
                                                    <button type="button" class="btn btn-info" name="submit" id="register_rc" onclick="">
                                                        <span class="glyphicon glyphicon-plus"></span> Submit
                                                    </button>
                                                </div>
                                            </div>

                                        </div>

                                        <div id="rslt1"></div>
                                        <div id="rs_loader"></div>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    $('.datepicker').datepicker({
        format: 'dd-mm-yyyy',
        changeMonth: true,
        changeYear: true,
        yearRange: '1950:2050'
    });
</script>