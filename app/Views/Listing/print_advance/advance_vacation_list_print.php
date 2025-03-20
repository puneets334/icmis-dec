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
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">

                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">ADVANCE VACATION LIST PRINT MODULE</h3>
                            </div>


                        </div>
                    </div>
                    <form method="post">
                        <?= csrf_field() ?>
                        <div id="dv_content1">

                            <div style="text-align: center">
                                <span style="font-weight: bold; color:#4141E0; text-decoration: underline;">ADVANCE VACATION LIST PRINT MODULE</span>

                                <div class="col-md-12">
                                    <table class="table table-bordered mt-4">
                                        <tr>
                                            <td>
                                                <fieldset>
                                                    <legend>Advance Vacation Year</legend>
                                                    <select class="ele" name="vac_yr" id="vac_yr">
                                                        <?php
                                                        for ($i = 2018; $i <= date('Y'); $i++) {
                                                        ?>
                                                            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                                        <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </fieldset>

                                            </td>
                                            <td>
                                            <fieldset>
                                                <legend>Select Records</legend>
                                                <select class="ele" name="select_record" id="select_record">
                                                    <option value="ALL">ALL</option>
                                                    <?php
                                                    // Loop from 100 to 5000 with steps of 100
                                                    for ($i = 100; $i <= 5000; $i += 100) {
                                                    ?>
                                                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                                    <?php
                                                    }
                                                    ?>
                                                </select>

                                            </td>

                                            <td id="rs_actio_btn1" style="text-align:center">
                                            </fieldset>
                                                <fieldset>
                                                    <legend>Action</legend>
                                                    <button class="btn btn-primary" type="button" name="bt1" id="bt1">Submit</button>
                                                </fieldset>

                                            </td>
                                        </tr>
                                    </table>
                                    <div id="res_loader"></div>
                                </div>
                                <div id="dv_res1" class="p-4"></div>
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
    $(document).on("click", "#bt1", function() {
        get_cl_1();
    });

    async function get_cl_1() {
        await updateCSRFTokenSync();
        var vac_yr = $("#vac_yr").val();
        var vac_record = $("#select_record").val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $("input[name='CSRF_TOKEN']").val();
        $.ajax({
            type: 'POST',
            url: '<?php echo base_url('Listing/PrintAdvance/get_cause_list_vacation'); ?>',
            cache: false,
            async: true,
            data: {
                vac_yr: vac_yr,
                vac_record: vac_record,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            beforeSend: function() {
                toggleButtonState('bt1', 'disable');
                $('#dv_res1').html('<table widht="100%" align="center"><tr><td class="text-center"><img src="<?php echo base_url('images/load.gif'); ?>"/></td></tr></table>');
            },
            success: function(data, status) {
                toggleButtonState('bt1', 'enable');
                $('#dv_res1').html(data);

                if (data)
                    $('#res_on_off').show();
            },
            error: function(xhr) {
                toggleButtonState('bt1', 'enable');
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
    }

    $(document).on("click", "#ebublish",  async function() {
        await updateCSRFTokenSync();
        var prtContent = $("#dv_res1").html();
        var encprtContent = JSON.stringify(prtContent);
        var vac_yr = $("#vac_yr").val();
        var vac_record = $("#select_record").val(); 
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $("input[name='CSRF_TOKEN']").val();
        if(vac_record === 'ALL'){
            $('#res_loader').html('<div class="text-danger pb-3">Please Select All records! Please try again.</div>');
            //alert("Please Select All records! Please try again.");
        }
        $.ajax({
            type: 'POST',
            url: '<?php echo base_url('Listing/PrintAdvance/clPrintSaveVactions'); ?>',
            cache: false,
            async: true,
            data: {
                vac_yr: vac_yr,
                encprtContent: encprtContent,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            beforeSend: function() {
                toggleButtonState('ebublish', 'disable');
                $('#res_loader').html('<table widht="100%" align="center"><tr><td class="text-center"><img src="<?php echo base_url('images/load.gif'); ?>"/></td></tr></table>');
            },
            type: 'POST',
            success: function(response, status) {
                if (response.status === 'success') {
                    $('#res_loader').html('<div class="text-success pb-3">' + response.message + '</div>');

                    // Add a button to open the generated PDF
                    const openPdfButton = `
                        <div class="text-center">
                            <button id="openPdfBtn" class="btn btn-success" data-path="${response.filePath}">
                                Open Generated PDF
                            </button>
                        </div>`;
                    //$('#res_loader').append(openPdfButton);

                    alert(response.message);
                    toggleButtonState('ebublish', 'enable');
                    get_cl_1();
                } else {
                    // If an error occurs, display the error message
                    $('#res_loader').html('<div class="text-danger pb-3">Something went wrong! Please try again.</div>');
                    alert("Something went wrong! Please try again.");
                    toggleButtonState('ebublish', 'enable');
                }
            },
            
            error: function(xhr) {
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
    });
    $(document).on('click', '#openPdfBtn', function () {
        const pdfPath = $(this).data('path'); // Get the file path from the data-path attribute
        if (pdfPath) {
            window.open(pdfPath, '_blank'); // Open the PDF in a new tab
        } else {
            alert('File path is not available!');
        }
    });


    //function CallPrint(){
        $(document).on("click", "#prnnt1", function () {
            var prtContent = $("#dv_res1").html();
            var vac_yr = $("#vac_yr").val();
            var temp_str = `
                <style>
                    .ignore_in_print {
                        display: none !important;
                    }
                </style>
                ${prtContent}
            `;
            var WinPrint = window.open('', '', 'left=100,top=0,align=center,width=800,height=1200,menubar=1,toolbar=1,scrollbars=1,status=1');
            WinPrint.document.write(temp_str);
            WinPrint.document.close();
            WinPrint.focus();
            WinPrint.print();
        });

</script>