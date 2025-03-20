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
                        <h3 class="card-title">WEEKLY PREVIOUS CAUSE LIST MODULE</h3>
                    </div>
                    <div class="col-md-12">
                        <table class="table table-bordered mt-4">
                            <tr>
                                <td>
                                    <?= csrf_field() ?>
                                    <fieldset style="padding:5px; background-color:#F5FAFF; border:1px solid #0083FF;">
                                        <legend style="background-color:#E2F1FF; width:100%; text-align:center; border:1px solid #0083FF;"><b>WEEKLY PREVIOUS CAUSE LIST</b></legend>
                                        <select class="ele" name="dp_wk" id="dp_wk">
                                            <option value="0">-ALL-</option>
                                            <?php if (!empty($weekly_files)) : ?>
                                                <?php foreach ($weekly_files as $file) : ?>
                                                    <option value="<?= esc($file['pdf_path']); ?>">
                                                        <?= esc($file['label']); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            <?php else : ?>
                                                <option value="">No files available</option>
                                            <?php endif; ?>
                                        </select>
                                    </fieldset>
                                </td>
                                <td id="rs_actio_btn1" style="text-align:center;">
                                    <fieldset>
                                        <legend>Action</legend>
                                        <button class="btn btn-primary" type="button" name="btn_get" id="btn_get">Click</button>
                                    </fieldset>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div id="dv_res1" class="p-4"></div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
     $(document).on("click", "#btn_get", async function() {
            await updateCSRFTokenSync();
            var list_dt = $("#dp_wk").val();
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $("input[name='CSRF_TOKEN']").val();
            $.ajax({
                type: 'POST',
                url: '<?php echo base_url('Listing/PrintAdvance/get_wk_prev_cl'); ?>',
                cache: false,
                async: true,
                data: {
                    list_dt: list_dt,
                    CSRF_TOKEN: CSRF_TOKEN_VALUE
                },
                beforeSend: function() {
                    $('#dv_res1').html('<table widht="100%" align="center"><tr><td class="text-center"><img src="<?php echo base_url('images/load.gif'); ?>"/></td></tr></table>');
                },
               
                success: function(data, status) {
                    if (data.status === 'success') {
                        $('#dv_res1').html(data.content); // Display the updated HTML content
                    } else {
                        $('#dv_res1').html('<div class="alert alert-warning alert-dismissible fade show" role="alert"><strong>' + data.message + '</strong><button type="button" class="btn-close close" data-dismiss="alert" aria-label="Close">x</button></div>');
             
                        
                    }
                },

                error: function(xhr) {
                    alert("Error: " + xhr.status + " " + xhr.statusText);
                }
            });
        });

</script>