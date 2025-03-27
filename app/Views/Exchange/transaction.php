<?= view('header') ?>
<link rel="stylesheet" href="<?= base_url() ?>/assets/plugins/datepicker/datepicker3.css">
<style type="text/css">
    .card-header
    {
        padding: .75rem 0;
    }
    .caseNum
    {
        display: none;
    }
    .diaryNum
    {
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
                            <div class="col-md-12">
                                <h3 class="card-title">Transaction Details</h3>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2" style="width: 100% !important;">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <?php if (session()->getFlashdata('msg')): ?>
                                <?= session()->getFlashdata('msg') ?>
                            <?php endif; ?>
                            
                            <?php
                            $attribute = array(
                                'class' => 'form-horizontal appearance_search_form',
                                'id' => 'transactionDetailForm',
                                'autocomplete' => 'off',
                                'enctype'=>'multipart/form-data',
                                'method' => 'post'
                            );
                            echo form_open(base_url('#'), $attribute);
                            ?>
                            <input type="hidden" name="usercode" id="usercode" value="<?php echo session()->get('login')['usercode']; ?>"/>
                            <div class="form-group row">
                                <div class="col-md-2">
                                    <label for="sa" class="text-right">Select</label>
                                    <select id="sa" name="sa" class="form-control" onclick="changediv()">
                                        <option value="">Select</option>
                                        <option value="1">Case Number</option>
                                        <option value="2">Diary Number</option>
                                    </select>
                                </div>

                                <div class="col-md-2 caseNum">
                                    <label for="aci">Case Type</label>
                                    <select class="form-control" id="caseType" name="caseType">
                                        <option value="">Select Case Type</option>
                                        <?php
                                        foreach ($cases as $case)
                                        {
                                            echo '<option value="' . $case["casecode"] . '">' . $case["casename"] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-2 caseNum">
                                    <label for="caseNo" class="text-right">Case No</label>
                                    <input type="number" id="caseNo" name="caseNo" placeholder="Case No" class="form-control" value="">
                                </div>
                                <div class="col-md-2 caseNum">
                                    <label for="caseYear" class="text-right">Case Year</label>
                                    <select id="caseYear" name="caseYear" class="form-control">
                                        <option value="">Select Year</option>
                                        <?php
                                        for ($i = date('Y'); $i > 1948; $i--) {
                                            echo "<option value='" . $i . "'>" . $i . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="col-md-2 diaryNum">
                                    <label for="dNo" class="text-right">Diary No</label>
                                    <input type="number" id="dNo" name="dNo" placeholder="Diary No" class="form-control" value="">
                                </div>

                                <div class="col-md-2 diaryNum">
                                    <label for="dYear" class="text-right">Year</label>
                                    <select id="dYear" name="dYear" class="form-control">
                                        <option value="">Select Year</option>
                                        <?php
                                        for ($i = date('Y'); $i > 1948; $i--)
                                        {
                                            echo "<option value='" . $i . "'>" . $i . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>


                                <div class="col-md-2">
                                    <label for="from" class="text-right">&nbsp;</label>
                                    <button type="button" id="btn" name="btn" class="btn btn-primary" onclick="data_fetch()" style="width: 100%; background-color: #072d74; color: white;">View
                                    </button>
                                </div>
                            </div>
                            <?= form_close()?>
                        </div>
                    </div>
                    <center>
                        <span id="loader"></span>
                    </center>
                    <div class="row mt-2">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <div id="printable"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script src="<?= base_url() ?>/assets/js/sweetalert-2.1.2.min.js"></script>
<script src="<?= base_url() ?>/assets/plugins/datepicker/bootstrap-datepicker.js"></script>
<script>
    function changediv()
    {
        var searchby = $('#sa').val();
        $('#printable').hide();
        if (searchby == 1)
        {
            $('.diaryNum').hide();
            $('#dNo').val('');
            
            $('.caseNum').show();
            $('#dYear').val('');
        }
        else if (searchby == 2)
        {
            $('.caseNum').hide();
            $('#caseType').val('');
            $('#caseNo').val('');
            $('#caseYear').val('');

            $('.diaryNum').show();
        }
        else if (searchby == '' || searchby == null)
        {
            $('.caseNum').hide();
            $('#caseType').val('');
            $('#caseNo').val('');
            $('#caseYear').val('');
            
            $('.diaryNum').hide();
            $('#dNo').val('');
            $('#dYear').val('');
        }
    }

    function data_fetch()
    {
        let CSRF_TOKEN = 'CSRF_TOKEN';
        let CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        var searchby = $('#sa').val();
        console.log(CSRF_TOKEN_VALUE);
        if(searchby == '' || searchby == null)
        {
            swal({
                title: "Warning",
                text: 'Please select search by',
                icon: "warning"
            }).then(() => {
                $('#sa').focus();
                return false;
            });
        }
        else if (searchby == 1)
        {
            var caseType = $('#caseType').val();
            var caseNo = $('#caseNo').val();
            var caseYear = $('#caseYear').val();
            if(caseType == '' || caseType == null)
            {
                alert('Please select case type');
                $('#caseType').focus();
                return false;
            }
            else if(caseNo == '' || caseNo == null)
            {
                alert('Please enter case number');
                $('#caseNo').focus();
                return false;
            }
            else if(caseYear == '' || caseYear == null)
            {
                alert('Please select case year');
                $('#caseYear').focus();
                return false;
            }
        }
        else if (searchby == 2)
        {
            var dNo = $('#dNo').val();
            var dYear = $('#dYear').val();
            if(dNo == '' || dNo == null)
            {
                alert('Please enter diary number');
                $('#dNo').focus();
                return false;
            }
            else if(dYear == '' || dYear == null)
            {
                alert('Please select year');
                $('#dYear').focus();
                return false;
            }
        }
        $.ajax({
            type: 'POST',
            data: 
            { 
                CSRF_TOKEN: CSRF_TOKEN_VALUE,
                searchby: searchby,
                caseType: caseType,
                caseNo: caseNo,
                caseYear: caseYear,
                dNo: dNo,
                dYear: dYear,
            },
            url: "<?= site_url('Exchange/causeListFileMovement/transactionProcess') ?>",
            beforeSend: function ()
            {
                $("#loader").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
            },
            success: function(result)
            {
                $("#loader").html('');
                $("#printable").html(result);
                $('#printable').show();
                
                updateCSRFToken();
            },
            error: function(xhr, status, error)
            {
                $("#loader").html('');
                $("#printable").html('');
                alert("Error: " + xhr.status + " " + xhr.statusText);
                updateCSRFToken();
            }
        });

        // $.ajax({
        //     type: "POST",
        //     url: url,
        //     data:
        //     {
        //         searchby: searchby,
        //         caseType: caseType,
        //         caseNo: caseNo,
        //         caseYear: caseYear,
        //         dNo: dNo,
        //         dYear: dYear,
        //     },
        //     success: function (data)
        //     {
        //         $('#printable').html(data);
        //         $('#printable').show();
        //     },
        //     error: function ()
        //     {
        //         alert("ERROR");
        //     }
        // });
    }























    $(function() {
        $('.datepick').datepicker({
            format: 'dd-mm-yyyy',
            todayHighlight: true,
            autoclose: true
        });
    });

    function check() {
        var causelistDate = $("#causelistDate").val();
        if (causelistDate === "")
        {
            alert("Select Causelist Date.");
            $("#causelistDate").focus();
            return false;
        }

        $(document).ready(function()
        {
            let CSRF_TOKEN = 'CSRF_TOKEN';
            let CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            let causelistDate = $('#causelistDate').val();
            // Set up AJAX request
            $.ajax({
                type: 'POST',
                data: 
                { 
                    CSRF_TOKEN: CSRF_TOKEN_VALUE,
                    causelistDate:causelistDate
                },
                url: "<?= site_url('Exchange/causeListFileMovement/casesToReceiveFromDA') ?>",
                beforeSend: function ()
                {
                    $("#printable").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                },
                success: function(result)
                {
                    $("#printable").html('');
                    $("#printable").html(result);
                    $('#tblDispatchDak').DataTable({
                        "bSort": false,
                        "bPaginate": false,
                        "bLengthChange": false,
                        dom: 'Bfrtip',
                        buttons: [
                            'print'
                        ],
                    });
                    updateCSRFToken();
                },
                error: function(xhr, status, error)
                {
                    $("#printable").html('');
                    alert("Error: " + xhr.status + " " + xhr.statusText);
                    updateCSRFToken();
                }
            });
        });

    }

    function doDispatch() {
        var selectedCases = [];
        $('#tblDispatchDak input:checked').each(function() {
            if ($(this).attr('name') !== 'allCheck') {
                selectedCases.push($(this).attr('value'));
            }
        });
        if (selectedCases.length <= 0) {
            alert("Please Select at least one dak for dispatch.");
            return false;
        }
        $.post("<?= site_url('RIController/doDispatchDak') ?>", {
            'selectedCases': selectedCases
        }, function(result) {
            $("#printable").html(result);
            $('#tblDispatchReport').DataTable({
                "bSort": false,
                "bPaginate": false,
                "bLengthChange": false,
                dom: 'Bfrtip',
                buttons: [
                    'print'
                ],
            });
        });
    }
</script>