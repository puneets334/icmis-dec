<?= view('header') ?>

<link rel="stylesheet" href="<?php echo base_url('Ajaxcalls/menu_assign/menu_assign.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('Ajaxcalls/menu_assign/style.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('Ajaxcalls/menu_assign/all.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('assets/vendor/fontawesome-free/css/all.min.css'); ?>">

<style>
    .card-title {
        text-align: center;
        margin-bottom: 20px;
    }

    th {
        font-weight: bold;
    }

    #datatavles {
        border: 1px solid black;
        border-collapse: collapse;
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
                                <h3 class="card-title">Master Management >> Case Block for Loose Doc</h3>
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
                                        <section class="content">
                                            <div class="box-heading">
                                                <div class="box-title" style="background-color: #537881;"><b>
                                                        <h2 style="background-color: #537881; color: white; text-align: center; font-size: 20px;font-weight: bold">List of Judges/Registrar</h2>
                                                    </b></div>
                                            </div>

                                            <div id="printable" class="box box-success">
                                                <div style="margin-top: 30px;margin-left: 15px;" id="row1" class="row">

                                                    <div class="col-sm-1 col-md-3 col-lg-3 ">
                                                        <label for="judges">Select Type:</label><br>
                                                    </div>

                                                    <div class="col-sm-1 col-md-3 col-lg-3 ">
                                                        <input type="radio" id="judges" name="judges" value="J">
                                                        <label for="judges">Judges</label><br>
                                                    </div>
                                                    <div class="col-sm-1 col-md-3 col-lg-3 ">
                                                        <input type="radio" id="judges" name="judges" value="R">
                                                        <label for="judges">Registrar</label><br>
                                                    </div>
                                                </div>

                                                <hr>

                                                <div style="margin-top: 30px;margin-left: 15px;" id="row2" class="row">

                                                    <div class="col-sm-1 col-md-3 col-lg-3 ">
                                                        <label for="is_retired">Select Status:</label><br>
                                                    </div>

                                                    <div class="col-sm-1 col-md-3 col-lg-3 ">
                                                        <input type="radio" id="is_retired" name="is_retired" value="Y">
                                                        <label for="is_retired">Retired</label><br>
                                                    </div>
                                                    <div class="col-sm-1 col-md-3 col-lg-3 ">
                                                        <input type="radio" id="is_retired" name="is_retired" value="N">
                                                        <label for="judges">Not Retired</label><br>
                                                    </div>
                                                </div>

                                            </div>
                                            <div id="printable" class="box box-success">
                                                <table  id="reportTable1"  class="table table-striped custom-table">

                                                </table>
                                            </div>
                                        </section>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>

<script src="<?= base_url('/Ajaxcalls/menu_assign/menu_assign.js') ?>"></script>
<script src="<?= base_url() ?>assets/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script>
    $(document).ready(function() {
        updateCSRFToken();
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({

            url: "<?= base_url('MasterManagement/CaseBlockLooseDoc/judges_report_grid'); ?>",
            data: {
                judges: judges,
                is_retired: is_retired
            },
            cache: false,
            type: 'POST',
            headers: {
                'X-CSRF-Token': CSRF_TOKEN_VALUE
            },
            success: function(data) {
                updateCSRFToken();
                $("#reportTable1").html(data);
                $('#reportTable1').DataTable({
                    "destroy": true,
                    "bProcessing": true,
                    "pageLength": 10,
                    dom: 'Bfrtip',
                    "buttons": [{
                            extend: "copy",
                            title: "List of Judges/Registrars",
                            filename: "List of JudgesRegistrars"
                        },
                        {
                            extend: "csv",
                            title: "List of Judges/Registrars",
                            filename: "List of JudgesRegistrars"
                        },
                        {
                            extend: "excel",
                            title: "List of Judges/Registrars",
                            filename: "List of JudgesRegistrars"
                        },
                        {
                            extend: "pdfHtml5",
                            title: "List of Judges/Registrars",
                            filename: "List of JudgesRegistrars",
                            customize: function(doc) {
                                doc.content.splice(0, 0, {
                                    text: "List of Judges/Registrars",
                                    fontSize: 12,
                                    alignment: "center",
                                    margin: [0, 0, 0, 12]
                                });
                            }
                        },
                        {
                            extend: "print",
                            title: "",
                            messageTop: "<h5 style='text-align:center;'>List of Judges/Registrars</h5>"
                        }
                    ]
                });
            },
            error: function() {
                alert('No List Found');
                updateCSRFToken();
            }
        });

    });


    $('#judges, #is_retired').on('change', function() {

        //var selectedVal = "";
        var is_retired = ($("input[type='radio'][name='is_retired']:checked")).val();
        var judges = ($("input[type='radio'][name='judges']:checked")).val();
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        updateCSRFToken();
        $.ajax({
            url: "<?= base_url('MasterManagement/CaseBlockLooseDoc/judges_report_grid'); ?>",
            data: {
                judges: judges,
                is_retired: is_retired
            },
            cache: false,
            type: 'POST',
            headers: {
                'X-CSRF-Token': CSRF_TOKEN_VALUE
            },
            success: function(data) {
                updateCSRFToken();
                $("#reportTable1").html(data);
                $('#reportTable1').DataTable({
                    "destroy": true,
                    "bProcessing": true,
                    "pageLength": 25,
                    dom: 'Bfrtip',
                    "buttons": [{
                            extend: "copy",
                            title: "List of Judges/Registrars",
                            filename: "List of JudgesRegistrars"
                        },
                        {
                            extend: "csv",
                            title: "List of Judges/Registrars",
                            filename: "List of JudgesRegistrars"
                        },
                        {
                            extend: "excel",
                            title: "List of Judges/Registrars",
                            filename: "List of JudgesRegistrars"
                        },
                        'pageLength',
                        {
                            extend: "pdfHtml5",
                            title: "List of Judges/Registrars",
                            filename: "List of JudgesRegistrars",
                            customize: function(doc) {
                                doc.content.splice(0, 0, {
                                    text: "List of Judges/Registrars",
                                    fontSize: 12,
                                    alignment: "center",
                                    margin: [0, 0, 0, 12]
                                });
                            }
                        },
                        {
                            extend: "print",
                            title: "",
                            messageTop: "<h5 style='text-align:center;'>List of Judges/Registrars</h5>"
                        }
                    ]
                });
            },
            error: function() {
                alert('No List Found');
                updateCSRFToken();
            }
        });

    });
</script>