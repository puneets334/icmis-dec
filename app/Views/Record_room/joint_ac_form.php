<?= view('header') ?>

<script type="text/javascript">
    $(function() {
        $("#crd1").datepicker({
            dateFormat: "dd-mm-yy"
        }).val();
    });

    $(document).ready(function() {
        $('#data').hide();
        $('#register').hide();
        $('#cein').focus();

        $("#crd1").change(function() {
            $('#register').show();
        });


        $("#aorc1").change(function() {
            var aorc = $("#aorc1").val();
            if (aorc.trim() === "") {
                // If input is empty, exit early
                $('#aorn1').val("AOR Code is required");
                $("#aorn1").attr("readonly", "readonly");
                return;
            }

            var dataString = 'tvap=' + encodeURIComponent(aorc);

            $.ajax({
                type: "get",
                url: "<?php echo base_url('Record_room/Record/getadv_name'); ?>",
                data: dataString,
                cache: false,
                success: function(result) {
                    console.log("AJAX Success: ", result); // Debugging line
                    if (result) {
                        $('#aorn1').val(result);
                        $("#aorn1").attr("readonly", "readonly");
                        $('#crd1').focus();
                    } else {
                        $('#aorn1').val(aorc + " AOR NOT FOUND");
                        $("#aorn1").attr("readonly", "readonly");
                        $('#aorc1').val("");
                        $('#aorc1').focus();
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error: ", status, error);
                    $('#aorn1').val("Error fetching data");
                }
            });
        });



        $("#register").click(function() {
            var aorc = $("#aorc1").val();
            var aorn = $("#aorn1").val();
            var acn = $("#acn").val();
            var res = acn.split(" ");
            var cnf = res[0];
            var cnm = res[2];
            var cnl = res[3];
            var cfn = $("#cfn").val();
            var cpal1 = $("#cpal1").val();
            var cpal2 = $("#cpal2").val();
            var cpad = $("#cpad").val();
            var cpapin = $("#cppapin").val();
            var cppal1 = $("#cppal1").val();
            var cppal2 = $("#cppal2").val();
            var cppad = $("#cppad").val();
            var cppapin = $("#cppapin").val();
            var cdob = $("#cdob").val();
            var cdob1 = cdob.split("-");
            var cdob = cdob1[2] + "-" + cdob1[1] + "-" + cdob1[0];

            var cpob = $("#cpob").val();
            var cn = $("#cn").val();
            var cx = $("#cx").val();
            var cxii = $("#cxii").val();
            var cug = $("#cug").val();
            var cpg = $("#cpg").val();
            var cein = $("#cein").val();
            var crd = $("#crd1").val(); // Ensure crd is defined here
            var tvap = '';

            tvap = aorc + ";" + aorn + ";" + cnf + ";" + cnm + ";" + cnl + ";" + cfn + ";" + cpal1 + ";" + cpal2 + ";" + cpad + ";" + cpapin + ";" + cppal1 + ";" + cppal2 + ";" + cppad + ";" + cppapin + ";" + cdob + ";" + cpob + ";" + cn + ";" + cx + ";" + cxii + ";" + cug + ";" + cpg + ";" + cein + ";" + crd;

            if (!aorc && !cnf && !cpal1 && !cpapin && !cppal1 && !cppapin && !cdob && !cpob && !crd) {
                alert("Please Enter Mandatory Values");
                return false;
            } else {
                var dataString = 'tvap=' + tvap;
                $('#rslt').html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                $.ajax({
                    type: "get",
                    url: "<?php echo base_url('record_room/Record/ac_register1'); ?>",
                    data: dataString,
                    cache: false,
                    success: function(result) {
                        $('#rslt').html(result);
                    }
                });
            }
            return false;
        });

        $("#search").click(function() {
            var cein = $("#cein").val();
            var crd = $("#crd1").val();


            var dataString = 'tvap=' + cein;

            $.ajax({
                type: "get",
                url: "<?php echo base_url('record_room/Record/join_ac_search'); ?>",
                data: dataString,
                cache: false,
                success: function(result) {
                    if (result.includes("No data found")) {
                        $('#data').hide(); // Hide form data
                        $('#rslt').html("<h4>Record Not found</h4>");
                    } else {
                        var rres = result.split("#");
                        $('#sbtn').hide(); // Hide search button
                        $('#data').show(); // Show form data
                        $('#aorc').val(rres[0]);
                        $('#aorn').val(rres[1]);
                        $('#acn').val(rres[2]);
                        $('#cfn').val(rres[3]);
                        $('#cpal1').val(rres[4]);
                        $('#cpal2').val(rres[5]);
                        $('#cpad').val(rres[6]);
                        $('#cpapin').val(rres[7]);
                        $('#cppal1').val(rres[8]);
                        $('#cppal2').val(rres[9]);
                        $('#cppad').val(rres[10]);
                        $('#cppapin').val(rres[11]);
                        $('#cdob').val(rres[12]);
                        $('#cpob').val(rres[13]);
                        $('#cn').val(rres[14]);
                        $('#cmobile').val(rres[15]);
                        $('#cx').val(rres[16]);
                        $('#cxii').val(rres[17]);
                        $('#cug').val(rres[18]);
                        $('#cpg').val(rres[19]);
                        $('#crd').val(rres[20]);
                        $('#aorc1').focus();
                        $('#rslt').html("<h4> </h4>");
                    }
                }
            });
            return false;
        });
    });
</script>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12  mt-6">
                <div class="card">
                    <div class="card-header heading">

                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Joint Registration >> Advocate Clerk (Joint)</h3>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card mt-3">
                                <div class="card-body">

                                    <form class="form-horizontal" role="form" name="form1" autocomplete="off">
                                    <div class="row ">
                                        <label class="control-label col-sm-2" for="anumber">Existing Icard No. *</label>
                                        <div class="col-sm-6">
                                            <input class="form-control " name="cein" type="text" id="cein" placeholder="ICard Number">
                                        </div>
                                        <div class="col-sm-4" id="sbtn">
                                           <button type="submit" class="btn btn-primary" name="submit" id="search" onclick="">Search</button>
                                        </div>
                                    </div>
                                    
                                        <div id="data" >
                                            <div class="row form-group">
                                                <label class="control-label col-sm-2" for="atitle">AOR/Firm Code *</label>
                                                <div class="col-sm-5"><input class="form-control" name="aorc" type="text" id="aorc" placeholder="Code"
                                                        readonly></div>
                                                <div class="col-sm-5"><input class="form-control" name="aorn" type="text" id="aorn" placeholder="Name"
                                                        readonly></div>
                                            </div>

                                            <div class="row form-group ">
                                                <label class="control-label col-sm-2" for="anumber">Name *</label>
                                                <div class="col-sm-10"><input class="form-control " name="acn" type="text" id="acn" placeholder="Name"
                                                        readonly> </div>
                                            </div>

                                            <div class="row form-group ">
                                                <label class="control-label col-sm-2" for="anumber">Father Name *</label>
                                                <div class="col-sm-10"> <input class="form-control " name="cfn" type="text" id="cfn" placeholder="Father Name" readonly> </div>
                                            </div>

                                            <div class="row form-group ">
                                                <label class="control-label col-sm-2" for="anumber">Present Address</label>
                                                <div class="col-sm-3"><input class="form-control " name="cpal1" type="text" id="cpal1"
                                                        placeholder="Address Line1" readonly></div>
                                                <div class="col-sm-3"><input class="form-control " name="cpal2" type="text" id="cpal2"
                                                        placeholder="Address Line2" readonly></div>
                                                <div class="col-sm-2"><input class="form-control " name="cpad" type="text" id="cpad"
                                                        placeholder="District" readonly></div>
                                                <div class="col-sm-2"><input class="form-control " name="cpapin" type="text" id="cpapin"
                                                        placeholder="Pincode" readonly></div>
                                            </div>

                                            <div class="row form-group ">
                                                <label class="control-label col-sm-2" for="anumber">Permanent Address</label>
                                                <div class="col-sm-3"><input class="form-control " name="cppal1" type="text" id="cppal1"
                                                        placeholder="Address Line1" readonly></div>
                                                <div class="col-sm-3"><input class="form-control " name="cppal2" type="text" id="cppal2"
                                                        placeholder="Address Line2" readonly></div>
                                                <div class="col-sm-2"><input class="form-control " name="cppad" type="text" id="cppad"
                                                        placeholder="District" readonly></div>

                                                <div class="col-sm-2"><input class="form-control " name="cppapin" type="text" id="cppapin"
                                                        placeholder="Pincode" readonly></div>
                                            </div>

                                            <div class="row form-group ">
                                                <label class="control-label col-sm-2" for="anumber">Date of Birth</label>
                                                <div class="col-sm-4"><input class="form-control " name="cdob" type="text" id="cdob" placeholder="DOB"
                                                        readonly></div>
                                           
                                                <label class="control-label col-sm-2 mt-3" for="anumber">Place of Birth</label>
                                                <div class="col-sm-4"> <input class="form-control " name="cpob" type="text" id="cpob"
                                                        placeholder="Birth Place" readonly> </div>
                                            </div>

                                            <div class="row form-group ">
                                                <label class="control-label col-sm-2" for="anumber">Nationality</label>
                                                <div class="col-sm-4">
                                                    <input class="form-control " name="cn" type="text" id="cn" placeholder="Nationality" readonly>
                                                </div>
                                                <label class="control-label col-sm-2 mt-3" for="anumber">Moible No.</label>
                                                <div class="col-sm-4">
                                                    <input class="form-control " name="cmobile" type="text" id="cmobile" placeholder="mobile Number"
                                                        readonly>
                                                </div>
                                            </div>

                                            <div class="row form-group ">
                                                <label class="control-label col-sm-2" for="anumber">Educational Qualifications</label>
                                                <div class="col-sm-2"><input class="form-control " name="cx" type="text" id="cx" placeholder="X" readonly>
                                                </div>
                                                <div class="col-sm-2"><input class="form-control " name="cxii" type="text" id="cxii" placeholder="XII"
                                                        readonly></div>
                                                <div class="col-sm-2"><input class="form-control " name="cug" type="text" id="cug" placeholder="UG"
                                                        readonly></div>
                                                <div class="col-sm-2"><input class="form-control " name="cpg" type="text" id="cpg" placeholder="PG"
                                                        readonly></div>
                                            </div>



                                            <div class="row form-group ">
                                                <label class="control-label col-sm-2" for="anumber">Registration Date: </label>
                                                <div class="col-sm-2">
                                                    <input class="form-control " name="crd" type="text" id="crd" placeholder="Registration Date" readonly>
                                                </div>
                                            </div>

                                            <div class="row form-group">
                                                <label class="control-label col-sm-2" for="atitle">Extra AOR/Firm Code *</label>
                                                <div class="col-sm-1"><input class="form-control" name="aorc1" type="text" id="aorc1" placeholder="Code">
                                                </div>
                                                <div class="col-sm-6"><input class="form-control" name="aorn1" type="text" id="aorn1" placeholder="Name">
                                                </div>
                                            </div>

                                            <div class="row form-group ">
                                                <label class="control-label col-sm-2" for="anumber">Registration Date: *</label>
                                                <div class="col-sm-2">
                                                    <input class="form-control " name="crd1" type="text" id="crd1" placeholder="Registration Date">
                                                </div>
                                            </div>

                                            <div class="row form-group">
                                                <div class="col-sm-offset-2 col-sm-10">
                                                    <button type="submit" class="btn btn-primary" name="submit" id="register" onclick="">
                                                        <span class="glyphicon glyphicon-plus"></span> Register
                                                    </button>
                                                </div>
                                            </div>

                                        </div>

                                    </form>
                                    <div class="panel-footer" id="rslt"></div>
                                    <div class='table-responsive' id="rslt"></div>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>