<?= view('header') ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">

                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Judicial > Update DA Code</h3>
                            </div>
                            <div class="col-sm-2">
                                <div class="custom_action_menu">
                                    <a href="<?= base_url() ?>/Judicial/UpdateDACode"><button class="btn btn-primary btn-sm" type="button"><i class="fas fa-pen	" aria-hidden="true"></i></button></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <? // view('Filing/filing_breadcrumb'); 
                    ?>
                    <!-- /.card-header -->

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header p-2" style="background-color: #fff;">

                                </div>
                                <div class="card-body">
                                    <div class="p-5">
                                        <?php if (!empty($casedesc)) {

                                            $filing_details = session()->get('filing_details');
                                            if (!empty($filing_details)) { ?>
                                                <div class="col-md-12">
                                                    <label class="col-form-label">
                                                        <b>Diary Number :</b> <?= substr($filing_details['diary_no'], 0, -4) . '/' . substr($filing_details['diary_no'], -4); ?> &nbsp;&nbsp;&nbsp;
                                                        <?php if (!empty($filing_details['reg_no_display'])) { ?><b>Case Number :</b> <?= $filing_details['reg_no_display']; ?> <?php } ?> &nbsp;&nbsp;&nbsp;
                                                        <b>Case Title :</b> <?= $filing_details['pet_name'] . '  <b>Vs</b>  ' . $filing_details['res_name']; ?> &nbsp;&nbsp;&nbsp;
                                                        <b>Filing Date : </b><?= (!empty($filing_details['diary_no_rec_date'])) ? date('d-m-Y', strtotime($filing_details['diary_no_rec_date'])) : NULL ?> &nbsp;&nbsp;&nbsp;
                                                        <?php if ($filing_details['c_status'] == 'P') {
                                                            echo '<span class="text-blue">Pending</span>';
                                                        } else {
                                                            echo '<span class="text-red">Disposed</span>';
                                                        } ?>
                                                    </label>
                                                </div>
                                            <?php } ?>

                                            <div class="col-md-8">
                                                <strong>Current DA:</strong>&nbsp; <?= (!empty($casedesc['empid'])) ? $casedesc['name'] . ' [Empid- ' . $casedesc['empid'] . '] [Section- ' . $casedesc['section_name'] . ']' : 'No DA'; ?>


                                                <?php  //echo $_SESSION["captcha"];
                                                /*$attribute = array('class' => 'form-horizontal', 'name' => 'updateDaCode', 'id' => 'updateDaCode', 'autocomplete' => 'off');
                                                echo form_open(base_url('Judicial/UpdateDACode/set_dacode'), $attribute);*/
                                                ?>
                                                <div class="form-group mb-2">
                                                <strong>New DA:</strong> <input type="text" id="newdacode" name="newdacode" placeholder="TYPE NAME OR EMPID" />
                                                    <input type="hidden" id="newdacode_hd">
                                                    <input type="hidden" value="<?= $dno ?>" id="hdfno">
                                                </div>
                                                <div class="text-center">
                                                   
                                                    <button type="button" onclick="save_code()" id="savebtn_Da" class="btn btn-primary">Update </button>
                                                </div>

                                                <?php // form_close(); ?>
                                            </div>

                                        <?php } else { ?>
                                            <p style="text-align: center;color: red;font-size: 20px;">Case Not Found</p>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>
<?= csrf_field() ?>
<!-- /.content -->

<!-- <link href="<?php echo base_url('autocomplete/autocomplete.css'); ?>" rel="stylesheet">
    <script src="<?php echo base_url('autocomplete/autocomplete-ui.min.js'); ?>"></script> -->
<script src="<?php echo base_url('filing/diary_add_filing.js'); ?>"></script>


<!-- new added on 12-09-2024 -->
<!-- jQuery Library -->
<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- jQuery UI Library (for autocomplete) -->
<!-- <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script> -->

<script>
    function updateCSRFToken() {
        $.getJSON("<?php echo base_url('Csrftoken'); ?>", function(result) {
            $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
        });
    }

    $(document).on("focus", "#newdacode", function() {
        $("#newdacode").autocomplete({
            source: "../../Common/Ajaxcalls/get_da_name",
            width: 450,
            matchContains: true,
            minChars: 1,
            selectFirst: false,
            select: function(event, ui) {
                // Set autocomplete element to display the label
                this.value = ui.item.label;
                // Store value in hidden field
                $('#newdacode_hd').val(ui.item.value);
                // Prevent default behaviour
                return false;
            },
            focus: function(event, ui) {
                $("#newdacode").val(ui.item.label);
                return false;
            }
        });
    });

    /*$(document).on("focus", "#newdacode", function() {
        $("#newdacode").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "<?= base_url('Common/Ajaxcalls/get_da_name') ?>",
                    type: "GET",
                    dataType: "json",
                    data: {
                        term: request.term // Pass the input value to the server
                    },
                    success: function(data) {
                        response($.map(data, function(item) {
                            return {
                                label: item.label, // Label to be shown in autocomplete
                                value: item.value  // Value to be stored (ID, code, etc.)
                            };
                        }));
                    }
                });
            },
            minLength: 1, // Minimum characters to start autocomplete
            select: function(event, ui) {
                // Set the input field with the selected label
                this.value = ui.item.label;
                // Store the selected value in a hidden field
                $('#newdacode_hd').val(ui.item.value);
                // Prevent default behavior
                return false;
            },
            focus: function(event, ui) {
                // When focusing on an item, display the label
                $("#newdacode").val(ui.item.label);
                return false;
            }
        });
    });*/


    async function save_code() {
        updateCSRFTokenSync();

        var dacode = $("#newdacode_hd").val();

        if (dacode == '') {
            $('#newdacode').addClass('is-invalid');
            alert('Please select DA code to update');
            $("#newdacode").focus;
            return false;
        }

        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        $.ajax({
            type: 'POST',
            data: {
                CSRF_TOKEN: CSRF_TOKEN_VALUE,
                dacode,
                dno: $("#hdfno").val()
            },
            url: "<?php echo base_url('Judicial/UpdateDACode/set_dacode'); ?>",
            success: function(data) {
                // console.log("data:: ", data)
                // return
                if (data == '1') {
                    // data = JSON.parse(data);
                    alert('DACODE CHANGE SUCCESSFULLY')
                    location.reload();
                } else {
                    alert("Error while saving data.")
                }
                updateCSRFToken();
            },
            error: function() {
                alert("Error while saving data.")
                updateCSRFToken();
            }
        })

    }
</script>