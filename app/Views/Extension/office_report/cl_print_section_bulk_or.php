<?= view('header') ?>
<style>
    div.dataTables_wrapper div.dataTables_filter label {
        display: flex;
        justify-content: end;
    }

    div.dataTables_wrapper div.dataTables_filter label input.form-control {
        width: auto !important;
        padding: 4px;
    }


    .col-sm-12.col-md-3.mb-3 div {
        display: flex;
        /* Use flexbox for horizontal layout */
        flex-wrap: wrap;
        /* Allow elements to wrap to the next line if necessary */
    }

    .col-sm-12.col-md-3.mb-3 div input[type="radio"] {
        margin-right: 10px;
        /* Add some spacing between radio buttons */
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
                                <h3 class="card-title">Office Report</h3>
                            </div>
                            <div class="col-sm-2">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header p-2" style="background-color: #fff; border-bottom:none;">
                                    <h4 class="basic_heading">CAUSE LIST SECTION WISE (ONLY PUBLISHED LIST)</h4>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="active tab-pane">
                                            <form method="post" action="<?= site_url(uri_string()) ?>">
                                                <?= csrf_field() ?>
                                                <div class="row">
                                                    <div class="col-sm-12 col-md-3 mb-3">
                                                        <label for="mainhead">Mainhead</label>
                                                        <div id="id_mf">
                                                            <input type="radio" name="mainhead" id="mainhead" value="M" title="Miscellaneous" checked="checked">
                                                            <label for="mainheadM">M</label>
                                                            <input type="radio" name="mainhead" id="mainhead" value="F" title="Regular">
                                                            <label for="mainheadF">R</label>
                                                            <input type="radio" name="mainhead" id="mainhead" value="L" title="Lok Adalat">
                                                            <label for="mainheadL">L</label>
                                                            <input type="radio" name="mainhead" id="mainhead" value="S" title="Mediation">
                                                            <label for="mainheadS">MD</label>
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-12 col-md-3 mb-3">
                                                        <label for="">Listing Dates</label>
                                                        <input type="text" name="listing_dts" id="listing_dts" class="dtp" value="<?php echo date('d-m-Y'); ?>" readonly/>
                                                    </div>

                                                    <div class="col-sm-12 col-md-3 mb-3">
                                                        <label for="">Board Type</label>
                                                        <select name="board_type" class="form-control" id="board_type">
                                                            <option value="0">-ALL-</option>
                                                            <option value="J">Court</option>
                                                            <option value="C">Chamber</option>
                                                            <option value="R">Registrar</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-sm-12 col-md-3 mb-3">
                                                        <label for="">Court No.</label>
                                                        <select class="form-control" name="courtno" id="courtno">
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
                                                            <option value="21">21 (Registrar)</option>
                                                            <option value="22">22 (Registrar)</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-sm-12 col-md-3 mb-3">
                                                        <label for="">Purpose of Listing</label>
                                                        <select class="form-control" name="listing_purpose" id="listing_purpose">
                                                            <?php $res = is_data_from_table('master.listing_purpose', " display='Y' and code != 99 ", '*', 'A');
                                                            if (!empty($res)) {
                                                            ?>
                                                                <option value="all" selected="selected">-ALL-</option>
                                                            <?php
                                                                foreach ($res as $row) {
                                                                    echo '<option value="' . $row["code"] . '">' . $row["code"] . '. ' . $row["purpose"] . '</option>';
                                                                }
                                                            } else {
                                                                echo "Error...";
                                                            } ?>
                                                        </select>
                                                    </div>

                                                    <div class="col-sm-12 col-md-3 mb-3">
                                                        <label for="">Main/Suppl</label>
                                                        <select class="form-control" name="main_suppl" id="main_suppl">
                                                            <option value="0">-ALL-</option>
                                                            <option value="1">Main</option>
                                                            <option value="2">Suppl.</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-sm-12 col-md-3 mb-3">
                                                        <label for="">Section Name</label>
                                                        <select class="form-control" name="sec_id" id="sec_id">
                                                            <option value="0">-ALL-</option>
                                                            <?php
                                                            $re_u = is_data_from_table('master.usersection', "display = 'Y'  and isda = 'Y'", '*', 'A');
                                                            foreach ($re_u as $ro_u) {
                                                                $ro_id = $ro_u['id'];
                                                                $ro_name = $ro_u['section_name'];
                                                            ?>
                                                                <option value="<?php echo $ro_id; ?>"> <?php echo $ro_name; ?></option>
                                                            <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>

                                                    <div class="col-sm-12 col-md-3 mb-3">
                                                        <label for="">Order By</label>
                                                        <select class="form-control" name="orderby" id="orderby">
                                                            <option value="0">-ALL-</option>
                                                            <option value="1">Court Wise</option>
                                                            <option value="2">Section Wise</option>
                                                        </select>
                                                    </div>


                                                    <div class="col-sm-12 col-md-3 mb-3">
                                                        <button type="button" name="btn1" id="btn1" class="quick-btn mt-26">Submit</button>
                                                    </div>

                                                </div>
                                                
                                            </form>
                                            <div id="dv_res1"></div>
                                        </div>
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

<script>
    function get_mainhead() {
        var mainhead = "";
        $('input[type=radio]').each(function() {
            if ($(this).attr("name") == "mainhead" && this.checked)
                mainhead = $(this).val();
        });
        return mainhead;
    }

    $(document).on("focus", ".dtp", function() {
        $('.dtp').datepicker({
            dateFormat: 'dd-mm-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: '1950:2050'
        });

    });

    $(document).on("click", "#btn1", function() {
        let CSRF_TOKEN = 'CSRF_TOKEN';
        let CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        let mainhead = get_mainhead();
        let list_dt = $("#listing_dts").val();
        let courtno = $("#courtno").val();
        let lp = $("#listing_purpose").val();
        let board_type = $("#board_type").val();
        let orderby = $("#orderby").val();
        let sec_id = $("#sec_id").val();
        let main_suppl = $("#main_suppl").val();

        $.ajax({
            url: "<?php echo base_url('Filing/OfficeReport/getCauseListSectionBulkOrUpload'); ?>",
            method: 'POST',
            beforeSend: function() {
                $('#dv_res1').html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
            },
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
            cache: false,
            success: function(response) {
                updateCSRFToken();
                $('#dv_res1').html(response);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                updateCSRFToken();
                alert("Error: " + jqXHR.status + " " + errorThrown);
            }
        });
    });

</script>