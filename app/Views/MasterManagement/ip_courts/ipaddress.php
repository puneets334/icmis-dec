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
</style>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">

                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Master Management >> List of IP Address in Courts</h3>
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
                                <div class="">
                                    <div class="d-block text-center">




                                        <!-- Main content -->
                                        <section class="content_">
                                            <div class="box-heading">
                                                <div class="box-title" style="background: #0d48be;"><b>
                                                        <h2 style="background: #0d48be; color: white; text-align: center; font-size: 20px;font-weight: bold">List of IP Address in Courts</h2>
                                                    </b></div>
                                            </div>

                                            <div class="">

                                                <form id="form1" class="form-horizontal" method="post" action="">
                                                    <div class="card-body">
                                                        <div class="row" style="justify-content: center;">
                                                            <div class="col-2">
                                                                <label for="html">Physical Court</label>
                                                            </div>
                                                            <div class="col-1">
                                                                <input type="radio" name="court" value="physical" onclick="showdiv('phcourt');" checked>
                                                            </div>
                                                            <div class="col-3">
                                                                <label for="css">Virtual Court</label>
                                                            </div>
                                                            <div class="col-1">
                                                                <input type="radio" name="court" value="virtual" onclick="showdiv('vrcourt');">
                                                            </div>
                                                            <div class="col-6">

                                                            </div>

                                                        </div>
                                                        <hr>
                                                        <?php if (isset($user)) { ?>
                                                            <input type="hidden" id="custId" name="custId" value="<?php echo $user; ?>">
                                                        <?php } ?>
                                                        <div id="phcourt" class="phcourt">
                                                            <div class="row" style="justify-content: center;">
                                                                <label for="court_number" class="control-label col-md-4  requiredField">Select Court<span class="asteriskField"></span> </label>
                                                                <div class="control-label col-md-8">

                                                                    <select class="select form-control" id="court_number" name="court_number">
                                                                        <option value=""> ----select Court----</option>
                                                                        <?php
                                                                        $value = "<p id='result'>" . "</p>";
                                                                        for ($x = 1; $x <= 17; $x++) {
                                                                            echo "<option value='$x'>" . "Court No." . $x . "</option>";
                                                                            echo $value;
                                                                        }
                                                                        ?>
                                                                        <option value="21"> Registrar Court 1</option>
                                                                        <option value="22"> Registrar Court 2</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div id="vrcourt" class="vrcourt" style="display: none">
                                                            <div class="row" style="justify-content: center;">
                                                                <label for="virtual_court_number" class="control-label col-md-4  requiredField">Select Virtual Court<span class="asteriskField"></span> </label>
                                                                <div class="control-label col-md-8">

                                                                    <select class="select form-control" id="virtual_court_number" name="virtual_court_number">
                                                                        <option value=""> ----select Court----</option>
                                                                        <?php
                                                                        $value = "<p id='result'>" . "</p>";
                                                                        for ($x = 31; $x <= 47; $x++) {
                                                                            echo "<option value='$x'>" . "Virtual Court No." . ($x - 30) . "</option>";
                                                                            echo $value;
                                                                        }
                                                                        ?>
                                                                        <option value="61"> Registrar Court 1</option>
                                                                        <option value="62"> Registrar Court 2</option>
                                                                        <option value="65"> Registrar Court 3</option>


                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <br>

                                                        <div class="row">
                                                            <label for="ip_address" class="control-label col-md-4  requiredField">Enter IP Address:<span class="asteriskField"></span> </label>
                                                            <div class="control-label col-md-8">
                                                                <input type="text" class="input-md textinput form-control" id="ip_address" name="ip_address" placeholder="xxx.xxx.xxx.xxx">
                                                            </div>
                                                        </div>


                                                        <div class="row mt-2" style="justify-content: center;">
                                                            <div class="col-2">
                                                                <!--                                <input type="button" id="save" name="save" value="Save Details" class="btn btn btn-primary"  onclick="deletedata(3);"/>-->
                                                                <input type="button" id="update" name="activate" value="Activate" class="btn btn btn-primary" onclick="deletedata(2);" />
                                                            </div>
                                                            <div class="col-2">
                                                                <input type="button" id="delete" name="deactivate" value="Deactivate" class="btn btn btn-primary" disabled="true" onclick="deletedata(1);" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="box box-info mt-4">
                                                <?php
                                                if (isset($ip_list)) {

                                                ?>
                                                    <caption>
                                                        <h2 style="background: #0d48be; color: white; text-align: center; font-size: 20px;font-weight: bold">
                                                            IP Address in Courts as on <?php echo date('d-m-Y h:m:s A') ?>
                                                        </h2>
                                                    </caption>
                                                    <div class="table-responsive">
                                                        <table id="grid" class="table table-striped custom-table">

                                                            <thead>
                                                                <tr>
                                                                    <th style="width: 5%;" rowspan='1'>SNo.</th>
                                                                    <th style="width: 5%;" rowspan='1'>Court No.</th>
                                                                    <th style="width: 10%;" rowspan='1'>IP Address</th>
                                                                    <th style="width: 30%;" rowspan='1'>IP Entered By</th>
                                                                    <th style="width: 30%;" rowspan='1'>IP Entered On</th>
                                                                    <th style="width: 10%;" rowspan='1'>IP Entered By IP</th>

                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                $s_no = 1;
                                                                $court = 0;
                                                                $virtualcourt = 0;
                                                                foreach ($ip_list as $result) {

                                                                ?>
                                                                    <tr>
                                                                        <td><?= $s_no; ?></td>
                                                                        <td><?php
                                                                            if ($result['court_no'] <= 17) {
                                                                                echo $result['court_no'];
                                                                            }
                                                                            if ($result['court_no'] >= 31 && $result['court_no'] <= 47) {
                                                                                echo "Virtual Court " . ($result['court_no'] - 30);
                                                                            }
                                                                            if ($result['court_no'] == 21 || $result['court_no'] == 22) {
                                                                                echo "Registrar Court " . ($result['court_no'] - 20);
                                                                            }
                                                                            if ($result['court_no'] == 61 || $result['court_no'] == 62) {
                                                                                echo "Virtual Registrar Court " . ($result['court_no'] - 60);
                                                                            }


                                                                            ?></td>
                                                                        <td><?php echo $result['ip_address']; ?></td>
                                                                        <td><?php echo $result['entered_by']; ?></td>
                                                                        <td><?php
                                                                            if (isset($result['entered_on']) && !empty($result['entered_on'])) {
                                                                                $newformat = date('d-m-Y', strtotime($result['entered_on']));
                                                                                if ($newformat == '30-11-0001') {
                                                                                    echo "";
                                                                                } else {
                                                                                    echo $newformat;
                                                                                }
                                                                            } else {
                                                                                echo "";
                                                                            }
                                                                            ?></td>
                                                                        <td><?php echo $result['entered_ip']; ?></td>
                                                                    </tr>
                                                                <?php
                                                                    $s_no++;
                                                                }
                                                                ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                <?php
                                                }
                                                ?>

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

<script>
    $(document).ready(function() {
        // Initial setup
        toggleCourtFields($('input[name="court"]:checked').val());

        // On radio button change
        $('input[name="court"]').change(function() {
            toggleCourtFields($(this).val());
        });

        // Form submit validation
        $('#form1').submit(function(e) {
            let courtType = $('input[name="court"]:checked').val();
            let isValid = true;
            let ip = $('#ip_address').val();

            if (!ip) {
                alert('Please enter IP Address.');
                $('#ip_address').focus();
                isValid = false;
            }

            if (courtType === 'physical') {
                let courtNum = $('#court_number').val();
                if (!courtNum) {
                    alert('Please select a Physical Court.');
                    $('#court_number').focus();
                    isValid = false;
                }
            } else if (courtType === 'virtual') {
                let virtualCourtNum = $('#virtual_court_number').val();
                if (!virtualCourtNum) {
                    alert('Please select a Virtual Court.');
                    $('#virtual_court_number').focus();
                    isValid = false;
                }
            }

            if (!isValid) {
                e.preventDefault(); // prevent form submission
            }
        });

        function toggleCourtFields(value) {
            if (value === 'physical') {
                $('#phcourt').show();
                $('#vrcourt').hide();
                $('#court_number').prop('required', true);
                $('#virtual_court_number').prop('required', false).val('');
            } else if (value === 'virtual') {
                $('#phcourt').hide();
                $('#vrcourt').show();
                $('#virtual_court_number').prop('required', true);
                $('#court_number').prop('required', false).val('');
            }
        }
    });



    $(function() {
        $("#grid").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "bProcessing": true,
            "extend": 'colvis',
            "text": 'Show/Hide',
            "dom": 'Bfrtip', // Enables the Buttons extension
            "buttons": [{
                    extend: "csv",
                    title: "IP Address in Courts as on\n(As on <?php echo date('d-m-Y'); ?>)"
                },
                {
                    extend: "excel",
                    title: "IP Address in Courts as on\n(As on <?php echo date('d-m-Y'); ?>)"
                },
                {
                    extend: "print",
                    title: "",
                    messageTop: "<h3 style='text-align:center;'>IP Address in Courts as on<br>(As on <?php echo date('d-m-Y'); ?>)</h3>"
                }
            ]
        }).buttons().container().appendTo('#query_builder_wrapper .col-md-6:eq(0)');
    });


    function showdiv(id) {
        if (id == 'vrcourt') {
            document.getElementById('phcourt').style.display = "none";
            document.getElementById(id).style.display = 'block';

        } else {
            document.getElementById('vrcourt').style.display = "none";
            document.getElementById(id).style.display = "block";
            //document.getElementById('post').value = null;
        }
    }


    $('#court_number,#virtual_court_number').change(function() {
        var selectedValue = $(this).val();
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        $.ajax({
            url: "<?= base_url('/MasterManagement/IPController/get_ip'); ?>",
            data: {
                selectedValue: selectedValue
            },
            cache: false,
            type: 'POST',
            headers: {
                'X-CSRF-Token': CSRF_TOKEN_VALUE
            },
            success: function(data) {
                updateCSRFToken();
                if (data != null && data != "") {
                    ip_address.value = data;
                    //     document.getElementById("save").disabled = true;
                    //     document.getElementById("update").disabled = false;
                    document.getElementById("delete").disabled = false;
                    //
                    //
                } else {
                    updateCSRFToken();
                    document.getElementById("delete").disabled = true;
                    //     document.getElementById("update").disabled = true;
                    //     document.getElementById("save").disabled = false;
                    //
                }
            },
            error: function() {
                updateCSRFToken();
                alert('ERROR');
            }
        });
    });



    var ipv4_address = $('#ip_address');
    ipv4_address.inputmask({
        alias: "ip",
        greedy: false //The initial mask shown will be "" instead of "-____".
    });

    function reload() {
        location.reload();
    }


    function deletedata(x) {
        // Basic validation
        let courtType = $('input[name="court"]:checked').val();
        let ip = $('#ip_address').val();
        let courtNum = $('#court_number').val();
        let virtualCourtNum = $('#virtual_court_number').val();

        if (!ip) {
            alert('Please enter IP Address.');
            $('#ip_address').focus();
            return;
        }

        if (courtType === 'physical' && !courtNum) {
            alert('Please select a Physical Court.');
            $('#court_number').focus();
            return;
        }

        if (courtType === 'virtual' && !virtualCourtNum) {
            alert('Please select a Virtual Court.');
            $('#virtual_court_number').focus();
            return;
        }

        // Proceed only if validation passes
        var myform = document.getElementById("form1");
        var fd = new FormData(myform);
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        if (x == 1) {
            $.ajax({
                url: "<?= base_url(); ?>/MasterManagement/IPController/delete_ip",
                data: fd,
                cache: false,
                processData: false,
                contentType: false,
                type: 'POST',
                headers: {
                    'X-CSRF-Token': CSRF_TOKEN_VALUE
                },
                success: function(data) {
                    updateCSRFToken();
                    alert(data);
                    reload();
                },
                error: function() {
                    updateCSRFToken();
                    alert('Something went wrong.');
                }
            });
        } else if (x == 2) {
            $.ajax({
                url: "<?= base_url(); ?>/MasterManagement/IPController/update_ip",
                data: fd,
                cache: false,
                processData: false,
                contentType: false,
                type: 'POST',
                headers: {
                    'X-CSRF-Token': CSRF_TOKEN_VALUE
                },
                success: function(data) {
                    updateCSRFToken();
                    alert(data);
                    reload();
                },
                error: function() {
                    updateCSRFToken();
                    alert('Something went wrong.');
                }
            });
        } else if (x == 3) {
            $.ajax({
                url: "<?= base_url(); ?>index.php/IP_Controller/save_ip",
                data: fd,
                cache: false,
                processData: false,
                contentType: false,
                type: 'POST',
                headers: {
                    'X-CSRF-Token': CSRF_TOKEN_VALUE
                },
                success: function(data) {
                    updateCSRFToken();
                    alert(data);
                    reload();
                },
                error: function() {
                    updateCSRFToken();
                    alert('Something went wrong.');
                }
            });
        }
    }
</script>