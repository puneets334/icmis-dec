<?= view('header') ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">

                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Listed Not Listed Cases Report</h3>
                            </div>
                            <?= view('Filing/filing_filter_buttons'); ?>
                        </div>
                    </div>
                    <?php
                    $attributes = 'id="frm"';
                    $action = '';
                    echo form_open($action, $attributes);
                    csrf_field();
                    ?>
                    <div class="container mt-4">
                        <div class="row">
                            <div class="col-md-12">
                                <form id="reportForm">

                                    <div class="form-row align-items-center">
                                        <div class="col-auto my-1">
                                            <label for="from_dt">From Date:</label>
                                            <input type="text" name="from_dt" class="form-control dtp" id="from_dt"
                                                value="<?php echo date('d-m-Y'); ?>" maxlength="10" style="width: 100px;">
                                        </div>
                                        <div class="col-auto my-1">
                                            <label for="to_dt">To Date:</label>
                                            <input type="text" name="to_dt" class="form-control dtp" id="to_dt"
                                                value="<?php echo date('d-m-Y'); ?>" maxlength="10" style="width: 100px;">
                                        </div>
                                        <div class="col-auto my-1">
                                            <label for="mc">Case Type:</label>
                                            <select name="mc" id="mc" class="custom-select">
                                                <option value="m">Main case</option>
                                                <option value="c">Connected case</option>
                                                <option value="all">All</option>
                                            </select>
                                        </div>
                                        <div class="col-auto my-1">
                                            <label for="purpose">Purpose:</label>
                                            <select name="purpose" id="purpose" class="custom-select">
                                                <option value="all" selected>ALL Purpose</option>
                                                <?php foreach ($getlistedData as $row) {
                                                    echo "<option value='" . $row['code'] . "' style='font-size:9px'>" . $row['purpose'] . "</option>";
                                                } ?>
                                            </select>
                                        </div>
                                        <div class="col-auto my-1">
                                            <button type="button" class="btn btn-primary" id="show"
                                                onClick="return get_listed_notlisted();">Show Report</button>
                                        </div>
                                    </div>
                                </form>
                                <div id="cntnt_pending_app" name="cntnt_pending_app" class="mt-4 text-center"></div>
                            </div>
                        </div>
                    </div>

                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
</section>
<script type="text/javascript" language="javascript">
    var hd_folder = document.getElementById('hd_folder').value;
    var hd_ud = document.getElementById('hd_ud').value;

    function getXMLHTTP() {
        var xmlhttp = false;
        try {
            xmlhttp = new XMLHttpRequest();
        } catch (e) {
            try {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                try {
                    xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
                } catch (e1) {
                    xmlhttp = false;
                }
            }
        }

        return xmlhttp;
    }

    function get_subhead() {
        var xhr3 = getXMLHTTP();
        var str = "get_subhead.php?m_f=" + document.getElementById('mf').value;
        xhr3.open("GET", str, true);
        xhr3.onreadystatechange = function() {
            if (xhr3.readyState == 4 && xhr3.status == 200) {
                var data = xhr3.responseText;
                document.getElementById('subhead_div').innerHTML = data;
            }
        }
        xhr3.send(null);
    }

    function get_listed_notlisted() {
        // $('#cntnt_pending_app').html('<img src="' + base_url + '/images/load.gif"/>');
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var csrf = $("input[name='CSRF_TOKEN']").val();
        var csrfName = $('input[name="CSRF_TOKEN"]').attr('name');
        var csrfHash = $('input[name="CSRF_TOKEN"]').val();

        var from_dt = $('input[name="from_dt"]').val();
        var to_dt = $('input[name="to_dt"]').val();
        var mc = $('select[name="mc"]').val();
        var purpose = $('select[name="purpose"]').val();

        var data = {
            CSRF_TOKEN: csrf,
            // list_dt: list_dt,
            from_dt: from_dt,
            to_dt: to_dt,
            mc: mc,
            purpose: purpose
        };
        data[csrfName] = csrfHash;
        $.ajax({
            url: '<?php echo base_url('report/get_listed_notlisted'); ?>?',
            type: 'GET',
            data: data,
            success: function(response) {
                $('#cntnt_pending_app').html(response);

                var newCsrfHash = $('input[name="CSRF_TOKEN"]').val();
                $('input[name="CSRF_TOKEN"]').val(newCsrfHash);
            },
            error: function(xhr, status, error) {

                $('#cntnt_pending_app').html('An error occurred: ' + error);
            }
        });
    }
</script>