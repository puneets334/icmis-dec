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
                                <h3 class="card-title">CAUSE LIST SECTION WISE (ONLY PUBLISHED LIST)</h3>
                            </div>
                        </div>
                    </div>
                    <form method="post">
                        <?php
                        $attribute = array('class' => 'form-horizontal', 'name' => 'freeze', 'id' => 'freeze', 'autocomplete' => 'off');
                        echo form_open(base_url('#'), $attribute);
                        ?>
                        <div id="dv_content1">

                            <div style="text-align: center">
                                <span style="font-weight: bold; color:#4141E0; text-decoration: underline;">CAUSE LIST SECTION WISE</span>

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
                                                    <select class="form-control" name="listing_dts" id="listing_dts">
                                                        <option value="-1" selected>SELECT</option>
                                                        <?php if (!empty($listingDates)) : ?>
                                                            <?php foreach ($listingDates as $date) : ?>
                                                                <option value="<?= $date->next_dt; ?>">
                                                                    <?= date("d-m-Y", strtotime($date->next_dt)); ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        <?php else : ?>
                                                            <option value="-1">EMPTY</option>
                                                        <?php endif; ?>
                                                    </select>
                                                </fieldset>

                                            </td>
                                            <td id="rs_jg">
                                                <fieldset>
                                                    <legend><b>Board Type</b></legend>
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
                                                    <legend>Court No.</legend>
                                                    <select class="ele" name="courtno" id="courtno">
                                                        <option value="0">-ALL-</option>
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
                                                        <option value="21">21 (Registrar)</option>
                                                        <option value="22">22 (Registrar)</option>
                                                    </select>
                                                </fieldset>
                                            </td>
                                            <td style="width: 312px;">
                                                <fieldset>
                                                    <legend><b>Purpose of Listing</b></legend>
                                                    <select class="ele" name="listing_purpose" id="listing_purpose">
                                                        <option value="all" selected="selected">-ALL-</option>
                                                        <?php if (!empty($f_listorder)) : ?>
                                                            <?php foreach ($f_listorder as $row) : ?>
                                                                <option value="<?= $row->code; ?>"><?= $row->code; ?>. <?= $row->purpose; ?></option>
                                                            <?php endforeach; ?>
                                                        <?php else : ?>
                                                            <option value="-1">EMPTY</option>
                                                        <?php endif; ?>

                                                    </select>
                                                </fieldset>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <fieldset>
                                                    <legend><b>Main/Suppl.</b></legend>
                                                    <select class="ele" name="main_suppl" id="main_suppl">
                                                        <option value="0">-ALL-</option>
                                                        <option value="1">Main</option>
                                                        <option value="2">Suppl.</option>
                                                    </select>
                                                </fieldset>
                                            </td>
                                            <td>
                                                <fieldset>
                                                    <legend><b>Section Name</b></legend>
                                                    <select class="ele" name="sec_id" id="sec_id">
                                                        <option value="0">-ALL-</option>

                                                        <?php if (!empty($userSection)) : ?>
                                                            <?php foreach ($userSection as $row) : ?>
                                                                <option value="<?= $row['id']; ?>"><?= $row['section_name']; ?></option>
                                                            <?php endforeach; ?>
                                                        <?php else : ?>
                                                            <option value="-1">EMPTY</option>
                                                        <?php endif; ?>

                                                    </select>
                                            </td>
                                            <td>
                                                <fieldset>
                                                    <legend><b>Order By</b></legend>
                                                    <select class="ele" name="orderby" id="orderby">
                                                        <option value="0">-ALL-</option>
                                                        <option value="1">Court Wise</option>
                                                        <option value="2">Section Wise</option>
                                                    </select>
                                            </td>

                                            <td id="rs_actio_btn1" style="text-align:center;">
                                                <fieldset>
                                                    <legend>Action</legend>
                                                    <input class="ele" type="button" name="btn1" id="btn1" value="Submit" />
                                                </fieldset>

                                            </td>
                                        </tr>
                                    </table>
                                    <div id="res_loader"></div>
                                </div>

                                <div id="dv_res1" class="p-4"> </div>
                            </div>
                        </div>

                        <?php form_close(); ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    $(document).on("change", "input[name='mainhead']", async function() {
        await updateCSRFTokenSync();
        var mainhead = get_mainhead();
        var board_type = $("#board_type").val();
        var CSRF_TOKEN = ' CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $("input[name='CSRF_TOKEN' ]").val();
        $.ajax({
            url: '<?php echo base_url('Listing/PrintAdvance/get_cl_print_mainhead'); ?>',
            cache: false,
            async: true,
            type: 'POST',
            data: {
                mainhead: mainhead,
                board_type: board_type,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            beforeSend: function() {
                $('#res_loader').html('<table widht="100%" align="center"><tr><td class = "text-center"><img src = "<?php echo base_url('images/load.gif'); ?>" /></td></tr></table>');
            },
            success: function(data, status) {
                $('#listing_dts').html(data);
            },
            complete: function(){
                $('#res_loader').html('');
            },
            error: function(xhr) {
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
    });
    $(document).on("click", "#btn1", async function() {
        await updateCSRFTokenSync();
        var CSRF_TOKEN = ' CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $("input[name='CSRF_TOKEN' ]").val();
        var mainhead = get_mainhead();
        var list_dt = $("#listing_dts").val();
        var courtno = $("#courtno").val();
        var lp = $("#listing_purpose").val();
        var board_type = $("#board_type").val();
        var orderby = $("#orderby").val();
        var sec_id = $("#sec_id").val();
        var main_suppl = $("#main_suppl").val();
        var list_dt = $("#listing_dts").val();
        $.ajax({
            type: 'POST',
            url: '<?php echo base_url('Listing/PrintAdvance/get_cause_list_section'); ?>',
            cache: false,
            async: true,
            data: {
                list_dt: list_dt,
                mainhead: mainhead,
                lp: lp,
                courtno: courtno,
                board_type: board_type,
                orderby: orderby,
                sec_id: sec_id,
                main_suppl: main_suppl,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            beforeSend: function() {
                $('#dv_res1').html('<table widht="100%" align="center"><tr><td class = "text-center"><img src = "<?php echo base_url('images/load.gif'); ?>" /></td></tr></table>');
            },
            success: function(data, status) {
                $('#dv_res1').html(data);
            },
            complete: function(){
                $('#res_loader').html('');
            },
            error: function(xhr) {
                alert("Error: " + xhr.status + " " + xhr.statusText);
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

    //function CallPrint(){
    $(document).on("click", "#prnnt1", function() {
        var prtContent = $("#prnnt").html();
        var temp_str = prtContent;
        var WinPrint = window.open('', '', 'left=100,top=0,align=center,width=800,height=1200,menubar=1,toolbar=1,scrollbars=1,status=1');
        WinPrint.document.write(temp_str);
        WinPrint.document.close();
        WinPrint.focus();
        WinPrint.print();
    });
</script>