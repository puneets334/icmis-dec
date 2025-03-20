<?= view('header') ?>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">

                    <div class="card-header heading">
                        <h5 class="font-weight-bold text-center mb-0">REGISTRAR VACATION ALLOCATION MODULE</h5>
                    </div>

                    <div class="container mt-5">
                        <form method="post">
                            <?= csrf_field() ?>
                            <div id="dv_content1">
                                <div class="row justify-content-center">
                                    <div class="col-12">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <fieldset class="fieldset-border">
                                                    <legend class="fieldset-legend">Available in Pool</legend>
                                                    <div class="text-center">
                                                        <span style="font-size: 24px; font-weight: bold; color: #1b6d85;">
                                                            <?= esc($totalInPool); ?>
                                                        </span>
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="col-md-3">
                                                <fieldset class="fieldset-border">
                                                    <legend class="fieldset-legend">Listing Date</legend>
                                                    <input type="text" class="form-control dtp" name="ldates" id="ldates" value="<?= esc($nextCourtWorkDay); ?>" readonly>
                                                </fieldset>
                                            </div>
                                            <div class="col-md-3 jud_all_al">
                                                <fieldset class="fieldset-border">
                                                    <legend class="fieldset-legend">Registrar</legend>
                                                    <?php if (!empty($registrars)): ?>
                                                        <?php foreach ($registrars as $registrar): ?>
                                                            <div class="form-check">
                                                                <input type="checkbox" class="form-check-input" id="chk<?= esc($registrar['jcode']); ?>" name="chk" value="<?= esc($registrar['jcode']); ?>">
                                                                <label class="form-check-label" for="chk<?= esc($registrar['jcode']); ?>"><?= esc($registrar['first_name'] . ' ' . $registrar['sur_name'] . ', ' . $registrar['jname']); ?></label>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        Registrar Record Not Found.
                                                    <?php endif; ?>
                                                </fieldset>
                                            </div>
                                            <div class="col-md-2">
                                                <fieldset class="fieldset-border">
                                                    <legend class="fieldset-legend">Number of Cases per Bench</legend>
                                                    <select name="noc" id="noc" class="form-control">
                                                        <?php for ($i = 1; $i <= 500; $i++): ?>
                                                            <option value="<?= $i; ?>" <?= $i == 100 ? 'selected' : ''; ?>><?= $i; ?></option>
                                                        <?php endfor; ?>
                                                    </select>
                                                </fieldset>
                                            </div>
                                            <div class="col-md-1">
                                                <fieldset class="fieldset-border">
                                                    <legend class="fieldset-legend">Action</legend>
                                                    <input type="button" name="allocate" id="allocate" value="Allocate" class="btn btn-primary">
                                                </fieldset>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="dv_res2" class="text-center mt-3"></div>
                                <div id="dv_res3" class="mt-3"></div>
                            </div>
                        </form>
                    </div>

                    <div id="jud_all_al">
                    </div>

                </div>
            </div>


        </div>
    </div>
</section>
<script src="<?php echo base_url('plugins/jquery-validation/jquery.validate.js'); ?>"></script>
<script src="<?php echo base_url('plugins/jquery-validation/additional-methods.js'); ?>"></script>

<script>
    $(document).on("focus", ".dtp", function() {
        $('.dtp').datepicker({
            dateFormat: 'dd-mm-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: '1950:2050'
        });
    });

    function chkall1(e) {
        var elm = e.name;
        if (document.getElementById(elm).checked) {
            $('input[type=checkbox]').each(function() {
                if ($(this).attr("name") == "chk")
                    this.checked = true;
            });
        } else {
            $('input[type=checkbox]').each(function() {
                if ($(this).attr("name") == "chk")
                    this.checked = false;
            });
        }

    }

    $(document).on("click", "#allocate", function() {
            var r = confirm("Do you want to list this case");
            if (r == true) {
                txt = "You pressed OK to Allocate!";

                var CSRF_TOKEN = 'CSRF_TOKEN';
                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

                $("#allocate").hide();
                var list_dt = $("#ldates").val();
                var noc = $("#noc").val();
                var cchk_sel = "";
                $('input[type=checkbox]').each(function() {                   
                    if ($(this).attr("name") == "chk" && this.checked) {
                        cchk_sel += $(this).val();
                    }                   
                });
                $('#dv_res2').html(cchk_sel);
                if (cchk_sel == "") {
                    $('#dv_res2').html('<table widht="100%" align="center"><tr><td class="class_red">Select atleast one bench</table>');
                    $("#allocate").show();
                    return false;
                } else if (isEmpty(document.getElementById('noc'))) {
                    $('#dv_res2').html('<table widht="100%" align="center"><tr><td class="class_red">Please Enter No. of Cases to list in each court</table>');
                    $("#allocate").show();
                    return false;
                } else {
                    $.ajax({
                        url: "<?php echo base_url('Listing/RegistrarVacation/registrar_vacation_allocate/'); ?>",
                        cache: false,
                        async: true,
                        data: {
                            list_dt: list_dt,
                            noc: noc,
                            chked_jud_sel: cchk_sel,
                            CSRF_TOKEN: CSRF_TOKEN_VALUE
                        },
                        beforeSend: function() {
                            $('#dv_res2').html('<table widht="100%" align="center"><tr><td><img src="../../images/load.gif"/></td></tr></table>');
                        },
                        type: 'POST',
                        success: function(data, status) {
                            updateCSRFToken();
                            $('#dv_res2').html(data);
                        },
                        error: function(xhr) {
                            updateCSRFToken();
                            alert("Error: " + xhr.status + " " + xhr.statusText);
                        }
                    });

                }

            } else {
                txt = "You pressed Cancel!";
            }
        });


    function isEmpty(xx) {
        var yy = xx.value.replace(/^\s*/, "");
        if (yy == "") {
            xx.focus();
            return true;
        }
        return false;
    }
</script>