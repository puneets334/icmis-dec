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

    .table3,
    .subct2,
    .subct3,
    .subct4,
    #res_on_off,
    #resh_from_txt {
        display: none;
    }

    .toggle_btn {
        text-align: left;
        color: #00cc99;
        font-size: 18px;
        font-weight: bold;
        cursor: pointer;
    }

    div,
    table,
    tr,
    td {
        font-size: 14px;
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
                                <h3 class="card-title">
                                    CAUSE LIST PRINT MODULE</h3>
                            </div>

                        </div>
                    </div>
                    <div id="dv_content1">
                        <form method="post">
                            <?= csrf_field() ?>
                            <div style="text-align: center">

                                <table border="0" align="center">
                                    <tr valign="middle">
                                        <td id="id_mf">
                                            <fieldset>
                                                <legend>Mainhead</legend>
                                                <input type="radio" name="mainhead" id="mainhead" value="F" title="Regular" checked="checked">R&nbsp;
                                            </fieldset>
                                        </td>
                                        <td id="id_dts">
                                            <fieldset>
                                                <legend>Listing Dates</legend>
                                                <div class="mb-3">
                                                    <label for="listing_dts" class="form-label">From</label>
                                                    <select class="form-select" name="listing_dts" id="listing_dts">
                                                        <option value="-1" selected>SELECT</option>
                                                        <?php if (!empty($dates)) : ?>
                                                            <?php foreach ($dates as $date) : ?>
                                                                <option value="<?= $date['next_dt']; ?>"><?= date("d-m-Y", strtotime($date['next_dt'])); ?></option>
                                                            <?php endforeach; ?>
                                                        <?php else : ?>
                                                            <option value="-1" selected>EMPTY</option>
                                                        <?php endif; ?>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="listing_dts_to" class="form-label">To</label>
                                                    <select class="form-select" name="listing_dts_to" id="listing_dts_to">
                                                        <option value="-1" selected>SELECT</option>
                                                        <?php if (!empty($dates)) : ?>
                                                            <?php foreach ($dates as $date) : ?>
                                                                <option value="<?= $date['next_dt']; ?>"><?= date("d-m-Y", strtotime($date['next_dt'])); ?></option>
                                                            <?php endforeach; ?>
                                                        <?php else : ?>
                                                            <option value="-1" selected>EMPTY</option>
                                                        <?php endif; ?>
                                                    </select>
                                                </div>
                                            </fieldset>
                                        </td>
                                        <td id="rs_jg">
                                            <fieldset>
                                                <legend>Court No.</legend>
                                                <select class="ele" name="courtno" id="courtno">
                                                    <option value="0" selected>SELECT</option>
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                    <option value="5">5</option>
                                                    <option value="6">6</option>
                                                    <option value="7">7</option>
                                                    <option value="8">8</option>
                                                    <option value="9">9</option>
                                                    <option value="10">10</option>
                                                    <option value="11">11</option>
                                                    <option value="12">12</option>
                                                    <option value="13">13</option>
                                                    <option value="14">14</option>
                                                    <option value="15">15</option>
                                                    <option value="16">16</option>
                                                    <option value="17">17</option>
                                                    <option value="31">1 (Virtual Court)</option>
                                                    <option value="32">2 (Virtual Court)</option>
                                                    <option value="33">3 (Virtual Court)</option>
                                                    <option value="34">4 (Virtual Court)</option>
                                                    <option value="35">5 (Virtual Court)</option>
                                                    <option value="36">6 (Virtual Court)</option>
                                                    <option value="37">7 (Virtual Court)</option>
                                                    <option value="38">8 (Virtual Court)</option>
                                                    <option value="39">9 (Virtual Court)</option>
                                                    <option value="40">10 (Virtual Court)</option>
                                                    <option value="41">11 (Virtual Court)</option>
                                                    <option value="42">12 (Virtual Court)</option>
                                                    <option value="43">13 (Virtual Court)</option>
                                                    <option value="44">14 (Virtual Court)</option>
                                                    <option value="45">15 (Virtual Court)</option>
                                                    <option value="46">16 (Virtual Court)</option>
                                                    <option value="47">17 (Virtual Court)</option>
                                                </select>
                                            </fieldset>
                                        </td>
                                        <td id="rs_actio_btn1">
                                            <fieldset>
                                                <legend>Action</legend>
                                                <button type="button" name="btn1" id="btn1" class="btn btn-primary">Submit</button>
                                            </fieldset>
                                        </td>
                                    </tr>
                                </table>
                                <div id="res_loader"></div>
                            </div>
                        </form>
                        <div id="dv_res1" class="p-4"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
     $(document).on("click", "#resf_span", function() {
        $("#resh_from_txt").toggle("slow", "linear");
    });
    function get_mainhead() {
        var mainhead = "";
        $('input[type=radio]').each(function() {
            if ($(this).attr("name") == "mainhead" && this.checked)
                mainhead = $(this).val();
        });
        return mainhead;
    }

    $(document).on("click", "#btn1", async function() {
        await updateCSRFTokenSync();
        var mainhead = get_mainhead();
        var list_dt = $("#listing_dts").val();
        var list_dt_to = $("#listing_dts_to").val();
        var courtno = $("#courtno").val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            url: "<?php echo base_url('Listing/PrintWeekly/get_causelist_weekly_verify'); ?>",
            cache: false,
            async: true,
            type: 'POST',
            data: {
                list_dt: list_dt,
                list_dt_to: list_dt_to,
                mainhead: mainhead,
                courtno: courtno,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            beforeSend: function() {
                $('#dv_res1').html('<table widht="100%" align="center"><tr><td class="text-center"><img src="<?php echo base_url('images/load.gif'); ?>"/></td></tr></table>'); 
            },
            success: function(data, status) {
                $('#dv_res1').html(data);
            },
            error: function(xhr) {
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
    });

    $(document).on("click", "#unpub", async function() {
        await updateCSRFTokenSync();
            var prtContent = $("#prnnt").html();
            var list_dt = $("#listing_dts").val();
            var list_dt_to = $("#listing_dts_to").val();
            var courtno = $("#courtno").val();
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $.ajax({
                url: "<?php echo base_url('Listing/PrintWeekly/cl_print_unpublish_wk'); ?>",
                //: 'cl_print_unpublish_wk.php',
                cache: false,
                async: true,
                type: 'POST',
                data: {
                    list_dt: list_dt,
                    list_dt_to: list_dt_to,
                    courtno: courtno,
                    prtContent: prtContent,
                    CSRF_TOKEN: CSRF_TOKEN_VALUE
                },
                beforeSend: function() {
                    $('#res_loader').html('<table widht="100%" align="center"><tr><td class="text-center"><img src="<?php echo base_url('images/load.gif'); ?>"/></td></tr></table>'); 
                },
       
                success: function(response, status) {
                    try {
                        // Ensure response is a proper JSON object
                        var data = typeof response === 'string' ? JSON.parse(response) : response;
                        
                        if (data.status === 'success') {
                            $('#res_loader').html(`<h3 class="bg-success p-2 text-center">${data.message}</h3>`);
                            alert(data.message); // Show an alert with the response message
                        } else {
                            alert("An error occurred. Please try again.");
                        }
                    } catch (error) {
                        console.error("JSON Parsing Error:", error);
                        alert("Invalid server response.");
                    }
                },


                error: function(xhr) {
                    alert("Error: " + xhr.status + " " + xhr.statusText);
                }
            });
        });
        $(document).on("click", "#ebublish", async function() {
            await updateCSRFTokenSync();
            var prtContent = $("#prnnt").html();
            var list_dt = $("#listing_dts").val();
            var list_dt_to = $("#listing_dts_to").val();
            var courtno = $("#courtno").val();
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $.ajax({
                url: "<?php echo base_url('Listing/PrintWeekly/cl_print_save_wk'); ?>",
                //url: 'cl_print_save_wk.php',
                cache: false,
                async: true,
                type: 'POST',
                data: {
                    list_dt: list_dt,
                    list_dt_to: list_dt_to,
                    courtno: courtno,
                    prtContent: prtContent,
                    CSRF_TOKEN: CSRF_TOKEN_VALUE
                },
                beforeSend: function() {
                    $('#res_loader').html('<table widht="100%" align="center"><tr><td class="text-center"><img src="<?php echo base_url('images/load.gif'); ?>"/></td></tr></table>'); 
                },
                success: function(response, status) {
					try {
                        // Ensure response is a proper JSON object
                        var data = typeof response === 'string' ? JSON.parse(response) : response;
                        alert(data.message);
                        if (data.status === 'success') {
                            $('#res_loader').html(`
                                <h3 class="bg-success p-2 text-center">${data.message}</h3>
                                <p class="text-center">
                                    <a href="${data.file_path}" class="btn btn-primary" download>Download File</a>
                                </p>
                            `);
                            alert(data.message); // Show an alert with the response message
                        } else {
                            alert("An error occurred. Please try again.");
                        }

                    } catch (error) {
                        console.log("JSON Parsing Error:", error);
                        alert("Invalid server response.");
                    }
                },
                error: function(xhr) {
                    alert("Error: " + xhr.status + " " + xhr.statusText);
                }
            });
        });

        $(document).on("click", "#mbublish", async function() {
            await updateCSRFTokenSync();
            var list_dt = $("#listing_dts").val();
            var list_dt_to = $("#listing_dts_to").val();
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $.ajax({
                url: "<?php echo base_url('Listing/PrintWeekly/cl_print_save_wk_merge'); ?>",
                //url: 'cl_print_save_wk_merge.php',
                cache: false,
                async: true,
                type: 'POST',
                data: {
                    list_dt: list_dt,
                    list_dt_to: list_dt_to,
                    CSRF_TOKEN: CSRF_TOKEN_VALUE
                },
                beforeSend: function() {
                    $('#res_loader').html('<table widht="100%" align="center"><tr><td class="text-center"><img src="<?php echo base_url('images/load.gif'); ?>"/></td></tr></table>'); 
                },
                success: function(response, status) {
                    try {
                        // Ensure response is a proper JSON object
                        var data = typeof response === 'string' ? JSON.parse(response) : response;
                        
                        if (data.status === 'success') {
                            $('#res_loader').html(`
                                <h3 class="bg-success p-2 text-center">${data.message}</h3>
                                <p class="bg-success p-2 text-center">${data.sms_status}</p>
                                <p class="text-center">
                                    <a href="${data.file_path}" class="btn btn-primary" download>Download File</a>
                                </p>
                            `);
                            alert(data.message); // Show an alert with the response message
                        } else {
                            alert("An error occurred. Please try again.");
                        }

                    } catch (error) {
                        console.log("JSON Parsing Error:", error);
                        alert("Invalid server response.");
                    }
            
                },
                error: function(xhr) {
                    alert("Error: " + xhr.status + " " + xhr.statusText);
                }
            });
        });

    $(document).on("click", "#prnnt1", function() {
        var prtContent = $("#prnnt").html();
        var mainhead = get_mainhead();
        var list_dt = $("#listing_dts").val();
        var jud_ros = $("#jud_ros").val();
        var part_no = $("#part_no").val();
        var temp_str = prtContent;
        var WinPrint = window.open('', '', 'left=100,top=0,align=center,width=800,height=1200,menubar=1,toolbar=1,scrollbars=1,status=1');
        WinPrint.document.write(temp_str);
        WinPrint.document.close();
        WinPrint.focus();
        WinPrint.print();
    });
</script>