<?= view('header') ?>

<link rel="stylesheet" type="text/css" href="<?= base_url('/css/aor.css') ?>">
<?php //print_r($data); exit();
?>
<script>
    $(function() {
        $("#cdob").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: "dd-mm-yy",
            onSelect: function() {
                console.log('s');
            },
            onChangeMonthYear: function() {
                console.log('o');
            }
        })
        $("#crd").datepicker({
            dateFormat: "dd-mm-yy"
        }).val();
    });

    $(document).ready(function() {
        $('#cein').focus();
        $("#aorc").change(function() {
            var aorc = $("#aorc").val();
            var dataString = 'tvap=' + aorc;
            $.ajax({
                type: "get",
                url: "<?php echo base_url('Record_room/Record/getadv_name'); ?>",
                data: dataString,
                cache: false,
                success: function(result) {
                    if (result) {
                        $('#aorn').val(result);
                        $("#aorn").attr("disabled", "disabled");
                    } else {
                        $('#aorn').val("AOR CODE " + aorc + " NOT FOUND");
                        $("#aorn").attr("disabled", "disabled");
                        $('#aorc').val(" ");
                        $('#aorc').focus();
                    }
                }
            });
        });

        $("#modify").click(function() {
            var aorc = $("#aorc").val();
            var aorn = $("#aorn").val();
            aorn = aorn.replace(/[_\W]+/g, " ")
            var acn = $("#acn").val();
            var res = acn.split(" ");
            var cnf = acn;
            var cnm = '';
            var cnl = '';
            var cfn = $("#cfn").val();
            var cpal1 = $("#cpal1").val();
            var cpal2 = $("#cpal2").val();
            var cpad = $("#cpad").val();
            var cpapin = $("#cpapin").val();
            var cppal1 = $("#cppal1").val();
            var cppal2 = $("#cppal2").val();
            var cppad = $("#cppad").val();
            var cppapin = $("#cppapin").val();
            var cdob = $("#cdob").val();
            var cpob = $("#cpob").val();
            var cn = $("#cn").val();
            var cmobile = $("#cmobile").val();
            var cx = $("#cx").val();
            var cxii = $("#cxii").val();
            var cug = $("#cug").val();
            var cpg = $("#cpg").val();
            var cein = $("#cein").val();
            var crd = $("#crd").val();
            var cid = $("#cid").val();

            var tvap = '';
            tvap = aorc + ";" + aorn + ";" + cnf + ";" + cnm + ";" + cnl + ";" + cfn + ";" + cpal1 + ";" + cpal2 + ";" + cpad + ";" + cpapin + ";" + cppal1 + ";" + cppal2 + ";" + cppad + ";" + cppapin + ";" + cdob + ";" + cpob + ";" + cn + ";" + cmobile + ";" + cx + ";" + cxii + ";" + cug + ";" + cpg + ";" + cein + ";" + crd + ";" + cid;
            let missingFields = [];

            if (aorc.trim() === "") missingFields.push("AOR/Firm Code");
            if (cnf.trim() === "") missingFields.push("First Name");
            if (cpal1.trim() === "") missingFields.push("Present Address Line 1");
            if (cpapin.trim() === "") missingFields.push("Present Address Pincode");
            if (cppal1.trim() === "") missingFields.push("Permanent Address Line 1");
            if (cppapin.trim() === "") missingFields.push("Permanent Address Pincode");
            if (cein.trim() === "") missingFields.push("New Icard No");
            if (cpob.trim() === "") missingFields.push("Place of Birth");
            if (crd.trim() === "") missingFields.push("Registration Date");
            if (cmobile.trim() === "") missingFields.push("Mobile Number");


            if (missingFields.length > 0) {
                alert("Please Enter Mandatory Values : " + missingFields.join(", "));
                return false;
            }
            // if (cnf.trim() == '' || cfn.trim() == '' || cein.trim() == '' || aorc.trim() == '') {
            //     alert("Please Enter Mandatory Values");
            //     return false;
            // }
            // if ((!aorc && !cnf && !cpal1 && !cpapin && !cppal1 && !cppapin && !cdob && !cpob) && !crd) {
            //     alert("Please Enter Mandatory Values");
            //     return false;
            // }

            var dataString = 'tvap=' + tvap + '&id=' + cid;


            $('#rslt').html("<img src='<?php echo base_url('images/load.gif'); ?>'' width='50px' hight='50px' />");
            $.ajax({
                type: "get",
                url: "<?php echo base_url('Record_room/Record/AorUpdate'); ?>",

                data: dataString,
                cache: false,
                success: function(result) {
                    if (result.indexOf('Record Successfully Updated') !== -1) {
                        alert('Record Successfully Updated');
                        window.location.href = '<?php echo base_url('Record_room/Record/modify_details'); ?>';

                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
            return false;
        });


    });
</script>
</head>

<body>
    <?php {

    ?>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header heading">
                                <div class="row">
                                    <div class="col-sm-10">
                                        <h3 class="card-title">Record Room >> Advocate Clerk >> Modification</h3>
                                    </div>
                                    <div class="col-sm-2"></div>
                                </div>
                            </div>
                            <br><br>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="">
                                        <div class="panel panel-info">

                                            <div class="panel-heading">

                                                <input name="cid" type="hidden" id="cid" value="<?php echo !empty($val['id']) ? $val['id'] : ''; ?>">

                                                <!-- <h4><strong><span class="fas fa-search "></span>&nbsp; Modification >>&nbsp; Advocate Clerk Details</strong></h4> -->

                                            </div>
                                            <div class="panel-body" id="frm">
                                                <form class="form-horizontal" role="form" name="form1" autocomplete="off" id="fid">

                                                    <div class="form-group row">
                                                        <label class="control-label col-sm-2" for="atitle">Existing Icard No. *</label>
                                                        <div class="col-sm-2"><input class="form-control" name="cein" type="text" id="cein" value="<?php echo !empty($val['eino']) ? $val['eino'] : ''; ?>" placeholder="ICard Number"></div>

                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="control-label col-sm-2" for="atitle">AOR/Firm Code *</label>
                                                        <div class="col-sm-2"><input class="form-control" value="<?php echo !empty($val['aor_code']) ? $val['aor_code'] : ''; ?>" name="aorc" type="number" id="aorc" placeholder="Code"></div>
                                                        <div class="col-sm-6"><input class="form-control" value="<?php echo !empty($val['name']) ? $val['name'] : ''; ?>" name="aorn" type="text" id="aorn" placeholder="Name" readonly></div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label class="control-label col-sm-2" for="anumber">Name *</label>
                                                        <div class="col-sm-10">
                                                            <input class="form-control" name="acn"
                                                                value="<?php echo !empty($val['cname']) ? $val['cname'] : ''; ?>" type="text" id="acn" placeholder="Name">
                                                        </div>
                                                    </div>


                                                    <div class="form-group row">
                                                        <label class="control-label col-sm-2" for="anumber">Father Name *</label>
                                                        <div class="col-sm-10"> <input class="form-control " name="cfn" value="<?php echo !empty($val['cfname']) ? $val['cfname'] : ''; ?>" type="text" id="cfn" placeholder="Father Name"> </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label class="control-label col-sm-2" for="anumber">Present Address *</label>
                                                        <div class="col-sm-3"><input class="form-control " name="cpal1" value="<?php echo !empty($val['pa_line1']) ? $val['pa_line1'] : ''; ?>" type="text" id="cpal1" placeholder="Address Line1"></div>
                                                        <div class="col-sm-3"><input class="form-control " name="cpal2" value="<?php echo !empty($val['pa_line2']) ? $val['pa_line2'] : ''; ?>" type="text" id="cpal2" placeholder="Address Line2"></div>
                                                        <div class="col-sm-2"><input class="form-control " name="cpad" value="<?php echo !empty($val['pa_district']) ? $val['pa_district'] : ''; ?>" type="text" id="cpad" placeholder="District"></div>
                                                        <div class="col-sm-2"><input class="form-control " name="cpapin" value="<?php echo !empty($val['pa_pin']) ? $val['pa_pin'] : ''; ?>" type="number" maxlength="6" id="cpapin" placeholder="Pincode"></div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label class="control-label col-sm-2" for="anumber">Permanent Address *</label>
                                                        <div class="col-sm-3"><input class="form-control " name="cppal1" value="<?php echo !empty($val['ppa_line1']) ? $val['ppa_line1'] : ''; ?>" type="text" id="cppal1" placeholder="Address Line1"></div>
                                                        <div class="col-sm-3"><input class="form-control " name="cppal2" value="<?php echo !empty($val['ppa_line2']) ? $val['ppa_line2'] : ''; ?>" type="text" id="cppal2" placeholder="Address Line2"></div>
                                                        <div class="col-sm-2"><input class="form-control " name="cppad" value="<?php echo !empty($val['ppa_district']) ? $val['ppa_district'] : ''; ?>" type="text" id="cppad" placeholder="District"></div>
                                                        <div class="col-sm-2"><input class="form-control " name="cppapin" value="<?php echo !empty($val['ppa_pin']) ? $val['ppa_pin'] : ''; ?>" type="number" maxlength="6" id="cppapin" placeholder="Pincode"></div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label class="control-label col-sm-2" for="anumber">Date of Birth</label>
                                                        <div class="col-sm-3">
                                                            <input class="form-control" name="cdob" type="text" value="<?php echo !empty($val['dob']) ? date('d/m/Y', strtotime($val['dob'])) : ''; ?>" id="cdob">
                                                        </div>

                                                        <!-- </div>

                                                    <div class="form-group row"> -->
                                                        <label class="control-label col-sm-2 mt-3" for="anumber">Place of Birth</label>
                                                        <div class="col-sm-3"> <input class="form-control " name="cpob" value="<?php echo !empty($val['place_birth']) ? $val['place_birth'] : ''; ?>" type="text" id="cpob" placeholder="Birth Place"> </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label class="control-label col-sm-2" for="anumber">Nationality</label>
                                                        <div class="col-sm-3">
                                                            <input class="form-control " name="cn" type="text" id="cn" placeholder="Nationality" value="INDIAN" disabled>
                                                        </div>
                                                        <!-- </div>

                                                    <div class="form-group row"> -->
                                                        <label class="control-label col-sm-2 mt-3" for="anumber">Mobile Number *</label>
                                                        <div class="col-sm-3">
                                                            <input class="form-control " name="cmobile" value="<?php echo !empty($val['cmobile']) ? $val['cmobile'] : ''; ?>" type="number" maxlength="10" id="cmobile" placeholder="mobile" oninput="validateMobileLength(this)">
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label class="control-label col-sm-2" for="anumber">Educational Qualifications</label>
                                                        <div class="col-sm-2"><input class="form-control " value="<?php echo !empty($val['eq_x']) ? $val['eq_x'] : ''; ?>" name="cx" type="text" id="cx" placeholder="X"></div>
                                                        <div class="col-sm-2"><input class="form-control " value="<?php echo !empty($val['eq_xii']) ? $val['eq_xii'] : ''; ?>" name="cxii" type="text" id="cxii" placeholder="XII"></div>
                                                        <div class="col-sm-2"><input class="form-control " value="<?php echo !empty($val['eq_ug']) ? $val['eq_ug'] : ''; ?>" name="cug" type="text" id="cug" placeholder="UG"></div>
                                                        <div class="col-sm-2"><input class="form-control " value="<?php echo !empty($val['eq_pg']) ? $val['eq_pg'] : ''; ?>" name="cpg" type="text" id="cpg" placeholder="PG"></div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label class="control-label col-sm-2" for="anumber">Registration Date *</label>
                                                        <div class="col-sm-2">
                                                            <input class="form-control" value="<?php echo !empty($val['regdate']) ? date('d/m/Y', strtotime($val['regdate'])) : ''; ?>" name="crd" type="text" id="crd">
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <div class="col-sm-offset-2 col-sm-10">
                                                            <button type="button" class="btn btn-info" name="modify" id="modify" onclick="">
                                                                <i class="fas fa-plus"></i> modify

                                                            </button>
                                                        </div>
                                                    </div>

                                                </form>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>
    <?php

    }
    ?>
    <?= view('sci_main_footer') ?>