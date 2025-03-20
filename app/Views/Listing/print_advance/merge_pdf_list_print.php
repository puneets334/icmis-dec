<?= view('header') ?>
<style>
    fieldset {
        padding: 5px;
        background-color: #F5FAFF;
        border: 1px solid #0083FF;
    }

    legend {
        background-color: #E2F1FF;
        width: 100%;
        text-align: center;
        border: 1px solid #0083FF;
        font-weight: bold;
    }

    #customers {
        font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
        border-collapse: collapse;
    }

    #customers td,
    #customers th {
        border: 1px solid #ddd;
        padding: 8px;
    }

    #customers tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    #customers tr:hover {
        background-color: #ddd;
    }

    #customers th {
        padding-top: 12px;
        padding-bottom: 12px;
        text-align: left;
        background-color: #4CAF50;
        color: white;
    }

    .class_red {
        color: red;
    }

    .table3,
    .subct2,
    .subct3,
    .subct4 {
        display: none;
    }
</style>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">CAUSE LIST MERGING WITH FIRST PAGE</h3>
                            </div>
                        </div>
                    </div>
                    <form method="post">
                        <?= csrf_field() ?>
                        <div id="dv_content1">
                            <div style="text-align: center">
                                <span style="font-weight: bold; color:#4141E0; text-decoration: underline;"> MERGING WITH FIRST PAGE </span>
                                <div class="col-md-12">
                                    <table class="table table-bordered mt-4">
                                        <tr>
                                            <td id="id_mf">
                                                <fieldset>
                                                    <legend>Mainhead</legend>
                                                    <input type="radio" name="mainhead" id="mainhead" value="M" title="Miscellaneous" checked="checked">M&nbsp;
                                                    <input type="radio" name="mainhead" id="mainhead" value="F" title="Regular">R&nbsp;
                                                </fieldset>
                                            </td>
                                            <td id="id_dts">
                                                <fieldset>
                                                    <legend>Listing Dates</legend>
                                                    <select class="ele" name="listing_dts" id="listing_dts">
                                                        <option value="-1" selected>SELECT</option>
                                                        <?php foreach ($listing_date as $row) { ?>
                                                            <option value="<?php echo $row['next_dt']; ?>"><?php echo date("d-m-Y", strtotime($row['next_dt'])); ?></option>

                                                        <?php  } ?>
                                                    </select>
                                                </fieldset>
                                            </td>
                                            <td>
                                                <fieldset style="padding:5px; background-color:#F5FAFF; border:1px solid #0083FF;">
                                                    <legend style="background-color:#E2F1FF; width:100%; text-align:center; border:1px solid #0083FF;"><b>Board Type</b></legend>
                                                    <select class="ele" name="board_type" id="board_type">
                                                        <option value="0">-ALL-</option>
                                                        <option value="J">Court</option>
                                                        <option value="S">Single Judge</option>
                                                        <option value="C">Chamber</option>
                                                        <option value="R">Registrar</option>
                                                    </select>
                                                </fieldset>
                                            </td>
                                            <td id="rs_jg">
                                                <fieldset>
                                                    <legend>Final/Supply</legend>
                                                    <select class="ele" name="final_supply" id="final_supply">
                                                        <option value="0" selected>SELECT</option>
                                                        <option value="1">Final</option>
                                                        <option value="2">Supplementary</option>
                                                    </select>
                                                </fieldset>
                                            </td>
                                            <td>
                                                <fieldset>
                                                    <legend>Upload pdf file</legend>
                                                    <form class="form-horizontal" id="file-form" action="" method="POST" enctype="multipart/form-data">
                                                        <input type="file" id="uploadfile" name="uploadfile" />
                                                    </form>
                                                </fieldset>
                                            </td>

                                            <td id="rs_actio_btn1" style="text-align:center;">
                                                <fieldset>
                                                    <legend>Action</legend>
                                                    <button class="btn btn-primary" type="button" name="btn1" id="btn1">Submit</button>
                                                </fieldset>
                                            </td>

                                        </tr>
                                    </table>
                                    <div id="res_loader"></div>
                                </div>
                                <div id="dv_res1"></div>
                            </div>
                        </div>
                    </form>
                    <div id="jud_all_al"></div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    setTimeout(function() {
        $('#res_loader').html('');
        $('#dv_res1').html('');
    }, 500);


    $(document).on("change", "input[name='mainhead']", function() {
        var mainhead = get_mainhead();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $("input[name='CSRF_TOKEN']").val();
        var board_type = $("#board_type").val();
        $.ajax({
            url: '<?php echo base_url('Listing/PrintAdvance/get_cl_print_mainhead'); ?>',
            type: 'POST',
            cache: false,
            async: true,
            data: {
                mainhead: mainhead,
                board_type: board_type,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            beforeSend: function() {
                $('#dv_res1').html('<table widht="100%" align="center"><tr><td class="text-center"><img src="<?php echo base_url('images/load.gif'); ?>"/></td></tr></table>');
            },
            success: function(data, status) {
                $('#dv_res1').html("");
                updateCSRFToken();
                if (data != '') {
                    $('#listing_dts').html(data);
                } else {
                    $('#listing_dts').html("<option value='-1' selected>EMPTY</option>");
                }

                // $('#jud_ros').html("<option value='-1' selected>EMPTY</option>");
                // $('#part_no').html("<option value='-1' selected>EMPTY</option>");
            },
            error: function(xhr) {
                updateCSRFToken();
                //alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
    });



    function get_mainhead() {
        var mainhead = "";
        $('input[type=radio]').each(function() {
            if ($(this).attr("name") == "mainhead" && this.checked)
                mainhead = $(this).val();
        });
        return mainhead;
    }


    function get_cl_1() {

        var mainhead = get_mainhead();
        var list_dt = $("#listing_dts").val();
        var final_supply = $("#final_supply").val();
        var board_type = $("#board_type").val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $("input[name='CSRF_TOKEN']").val();
        $.ajax({
            url: '<?php echo base_url('Listing/PrintAdvance/cl_merge_first_page_get'); ?>',
            type: 'POST',
            cache: false,
            async: true,
            data: {
                list_dt: list_dt,
                mainhead: mainhead,
                final_supply: final_supply,
                board_type: board_type,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            beforeSend: function() {
                $('#dv_res1').html('<table widht="100%" align="center"><tr><td class="text-center"><img src="<?php echo base_url('images/load.gif'); ?>"/></td></tr></table>');
            },
            success: function(data, status) {
                $('#dv_res1').html(data);
                updateCSRFToken();
                if (data)
                    $('#res_on_off').show();
            },
            error: function(xhr) {
                updateCSRFToken();
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
    }

    $('#btn1').on('click', function() {
        var mainhead = get_mainhead();
        var list_dt = $("#listing_dts").val();
        var final_supply = $("#final_supply").val();
        var board_type = $("#board_type").val();

        var CSRF_TOKEN_VALUE = $("input[name='CSRF_TOKEN']").val(); 

        var formData = new FormData();
        formData.append('list_dt', list_dt);
        formData.append('mainhead', mainhead);
        formData.append('final_supply', final_supply);
        formData.append('board_type', board_type);
        formData.append('file', document.getElementById('uploadfile').files[0]);
        formData.append('CSRF_TOKEN', CSRF_TOKEN_VALUE);

        $.ajax({
            url: '<?php echo base_url('Listing/PrintAdvance/cl_merge_first_page_update'); ?>', 
            type: 'POST',
            data: formData,
            processData: false, // Required for file uploads
            contentType: false, // Required for file uploads
            cache: false, // Don't cache the request
            async: true,
            beforeSend: function() {
                $('#dv_res1').html('<table width="100%" align="center"><tr><td class="text-center"><img src="<?php echo base_url('images/load.gif'); ?>"/></td></tr></table>');
            },
            success: function(data, status) {
                updateCSRFToken(); 
                $('#dv_res1').html(''); 
                if (data.status === 'error') {
                    $('#dv_res1').html('<div class="alert alert-danger">' + data.message + '</div>');
                } else if (data.status === 'success') {
                    $('#dv_res1').html('<div class="alert alert-success">' + data.message + '</div>');
                }

                if (data && data.pdf_url) {
                    // Optional: Implement additional logic, e.g., show PDF preview
                    // Example: $('#res_on_off').show();
                }
            },
            error: function(xhr) {
                updateCSRFToken(); // Update the CSRF token on error as well
                alert("Error: " + xhr.status + " " + xhr.statusText); // Error handling
            }
        });
        updateCSRFToken();
    });

</script>