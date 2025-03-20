<?= view('header'); ?>
<link rel="stylesheet" href="<?= base_url() ?>/assets/plugins/datepicker/datepicker3.css">
<link rel="stylesheet" href="<?= base_url() ?>/assets/vendor/sweetalert2/sweetalert2.css">
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Filing Trap</h3>
                            </div>
                            <div class="col-sm-2">

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header p-2" style="background-color: #fff; border-bottom:none;">
                                    <h4 class="basic_heading">Completed Matters</h4>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="active tab-pane">
                                            <?php
                                            $attributes = [
                                                'class' => 'form-horizontal diary_fil_trap_form',
                                                'name' => 'diary_fil_trap_form',
                                                'id' => 'diary_fil_trap_form',
                                                'autocomplete' => 'off'
                                            ];
                                            echo form_open('#', $attributes);
                                            ?>

                                            <?= form_open() ?>
                                            <?= csrf_field() ?>
                                            <div id="dv_content1">
                                                <div class="row">
                                                    <div class="col-12 col-sm-12 col-md-12 col-lg-">
                                                        <label for="">Complete Matters for <span style="color: #737add"><?php echo $_SESSION['login']['name']; ?></span></label>&nbsp<span style="color: #737add">[<?php echo $fil_trap_type_row['type_name']; ?>]</span>
                                                    </div>

                                                    <?php
                                                    $vfn = 0;
                                                    $ref = 0;
                                                    $cat = 0;
                                                    if (!empty($fil_trap_type_row)) {
                                                        if ($fil_trap_type_row['usertype'] == 104)
                                                            $ref = 1;
                                                        if ($fil_trap_type_row['usertype'] == 105)
                                                            $cat = 1;
                                                    ?>
                                                        
                                                    <?php
                                                    }
                                                    ?>
                                                    <div class="col-sm-12 col-md-3 mb-3">
                                                        <label for="date1">Between</label>
                                                        <input type="text" id="date1" size="8" maxlength="10" value="<?php echo date('d-m-Y'); ?>" class="dtp form-control" />
                                                    </div>

                                                    <div class="col-sm-12 col-md-3 mb-3">
                                                        <label for="date2"> &</label>
                                                        <input type="text" id="date2" size="8" maxlength="10" value="<?php echo date('d-m-Y'); ?>" class="dtp" />
                                                    </div>
                                                    <?php
                                                    if ($ref == 1 || $cat == 1) {
                                                    ?>
                                                        <select id="rep_type" name="rep_type">
                                                            <option value="C">CONSOLIDATED</option>
                                                            <option value="O">OWN</option>
                                                        </select> &nbsp;
                                                    <?php
                                                    }
                                                    ?>
                                                </div>

                                            </div>

                                            <div class="row mt-5">
                                                <div class="col-12 text-center">
                                                    <input type="button" id="getreport" value="SHOW" class="btn btn-primary" />
                                                </div>
                                            </div>
                                            <div id="result"></div>
                                        </div>
                                        <?= form_close() ?>
                                    </div>
                                    <!-- /.tab-content -->
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <!-- </div> -->
</section>
<script src="<?= base_url() ?>/assets/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?= base_url() ?>/assets/vendor/sweetalert2/sweetalert2.all.min.js"></script>
<script>
    $(function() {
        $("#example1").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        $('#example2').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
        });
    });
</script>
<script>
    function efiling_number(efiling_number) {
        var link = document.createElement("a")
        link.href = "<?php echo E_FILING_URL ?>/efiling_search/DefaultController/?efiling_number=" + efiling_number
        link.target = "_blank"
        link.click()
    }
</script>
<script>
    $(document).ready(function() {
        $('.dtp').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true
        });
        $("#getreport").click(function() {
            var rep_type = $("#rep_type").val() ?? '';
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

            $.ajax({
                    type: 'POST',
                    url: "<?= base_url('Filing/FileTrap/getFilTrapData') ?>",
                    beforeSend: function(xhr) {
                        $('#result').html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                    },
                    data: {
                        from: $("#date1").val(),
                        to: $("#date2").val(),
                        type_rep: rep_type,
                        rtypetext: $("#rep_type option:selected").text(),
                        CSRF_TOKEN: CSRF_TOKEN_VALUE
                    },
                })
                .done(function(msg) {
                    $("#result").html(msg);
                    updateCSRFToken();
                })
                .fail(function() {
                    updateCSRFToken();
                    alert("ERROR, Please Contact Server Room");
                });
        });
    });


    function printfun() {
        var prtContent = document.getElementById('result');
        var WinPrint = window.open('', '', 'left=100,top=0,width=800,height=1200,toolbar=1,scrollbars=1,status=1,menubar=1');

        var h = `
        <style>
            .notfor-print {
                display: none;
            }
            .for-print {
                display: inline;
            }
            .prtblack {
                color: black;
                font-weight: bold;
            }
        </style>
    `;

        WinPrint.document.write(h + prtContent.innerHTML);
        WinPrint.document.close(); // Close the document before printing

        // Adding the border to the 'mainbtl' element
        var mainbtl = WinPrint.document.createElement('div');
        mainbtl.style.border = '1px solid black';
        mainbtl.style.borderCollapse = 'collapse';
        mainbtl.innerHTML = prtContent.innerHTML; // Append the content

        WinPrint.document.body.appendChild(mainbtl); // Append to body

        WinPrint.focus();
        WinPrint.print();
    }
</script>