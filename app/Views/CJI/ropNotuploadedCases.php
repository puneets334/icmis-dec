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
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">ROP not uploaded</h3>
                            </div>
                            <div class="col-sm-2">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header p-2" style="background-color: #fff; border-bottom:none;">
                                    <h4 class="basic_heading">ROP Not Uploaded</h4>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="active tab-pane">
                                            <form id="" method="POST" action="">
                                                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" id="csrf_token" />
                                                <input type="hidden" id="hdnJudgeName" name="hdnJudgeName" value="">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <label for="">Causelist From Date</label>
                                                        <input type="text" class="form-control dtp" name="causelistFromDate" id="causelistFromDate"
                                                            placeholder="dd-mm-yyyy" autocomplete="off" >
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label for="">To Date</label>
                                                        <input type="text" class="form-control dtp" name="causelistToDate" id="causelistToDate"
                                                            placeholder="dd-mm-yyyy" autocomplete="off">
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label for="">Hon'ble Judge</label>
                                                        <select class="form-control" id="pJudge" name="pJudge" placeholder="pJudge">
                                                            <option value="0">Select Presiding Judge</option>
                                                            <?php
                                                            foreach ($judge as $j1) {
                                                                echo '<option value="' . $j1['jcode'] . '" >' . $j1['jname'] . '</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>

                                                    <div>
                                                        <button type="button" id="btnGetCases" class="btn btn-success ml-3 mt-4">Get Cases
                                                        </button>
                                                    </div>


                                                </div>
                                                <div id="divCasesForGeneration"></div>
                                            </form>
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
    $(document).on('click', '#btnGetCases', function() {
        var causelistFromDate = $('#causelistFromDate').val();
        var causelistToDate = $('#causelistToDate').val();
        var pJudge = $('#pJudge').val();

        // Converting the dates to Date objects for comparison
        var date1 = new Date(causelistFromDate.split('-')[2], causelistFromDate.split('-')[1] - 1, causelistFromDate.split('-')[0]);
        var date2 = new Date(causelistToDate.split('-')[2], causelistToDate.split('-')[1] - 1, causelistToDate.split('-')[0]);

        // Calculating the difference in days
        var timeDiff = Math.abs(date2.getTime() - date1.getTime());
        var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24));


        // Validation checks
        if (diffDays > 15) {
            alert("Please Select a time period of 15 days!");
            return false;
        }
        if (causelistFromDate == "") {
            alert("Please Select Causelist From Date.");
            $('#causelistFromDate').focus();
            return false;
        }
        if (causelistToDate == "") {
            alert("Please Select Causelist To Date.");
            $('#causelistFromDate').focus();
            return false;
        }
        if (pJudge == "" || pJudge == "0") {
            alert("Please Select Presiding Judge.");
            $('#pJudge').focus();
            return false;
        }

        var judgeName = $("#pJudge option:selected").text();
        $("#hdnJudgeName").val(judgeName);


        if (causelistFromDate != "" && causelistToDate != "" && pJudge != "") {
            $('#loadingmessage').show();
            let csrfName = $("#csrf_token").attr('name');
            let csrfHash = $("#csrf_token").val();
            $.ajax({
                url: "<?php echo base_url('Cji/CauseList/getROPNotUploaded'); ?>",
                headers: {
                    'X-CSRF-Token': $("#csrf_token").val()
                },
                cache: false,
                async: true,
                beforeSend: function() {
                    $('#dv_data').html('<table widht="100%" align="center"><tr><td><img src="<?php echo base_url('/images/load.gif'); ?>"/></td></tr></table>');
                },
                data: {
                    causelistFromDate: causelistFromDate,
                    causelistToDate: causelistToDate,
                    pJudge: pJudge,
                    [csrfName]: csrfHash,
                },
                type: 'POST',
                success: function(response) {
                    if (response.success) {
                        let html = '<div class="table-responsive"><table id="example1" class="table table-striped custom-table">';
                        html += '<thead><tr><th>S.No.</th><th>Listing Date</th><th>Board Type</th><th>Court No</th><th>Item No</th><th>Generated By</th><th>Case Number</th><th>Causetitle</th></tr></thead><tbody>';

                        response.data.forEach((row, index) => {
                            let boardType = '';
                            switch (row.board_type) {
                                case 'J':
                                    boardType = 'Court';
                                    break;
                                case 'C':
                                    boardType = 'Chamber';
                                    break;
                                case 'R':
                                    boardType = 'Registrar';
                                    break;
                                case 'CC':
                                    boardType = 'By Circulation';
                                    break;
                            }

                            let courtNoDisplay = row.court_number;
                            if (courtNoDisplay == 21) courtNoDisplay = "R-1";
                            else if (courtNoDisplay == 22) courtNoDisplay = "R-2";
                            else if (courtNoDisplay > 30 && courtNoDisplay <= 60) courtNoDisplay = "V-" + (courtNoDisplay - 30);
                            else if (courtNoDisplay > 60 && courtNoDisplay <= 62) courtNoDisplay = "VR-" + (courtNoDisplay - 60);

                            let diaryNumber = "Diary No. " + row.diary_no.substr(0, row.diary_no.length - 4) + "/" + row.diary_no.substr(-4);

                            html += `<tr>`;
                            html += `<td>${index + 1}</td>`;
                            html += `<td>${new Date(row.listing_date).toLocaleDateString('en-GB')}</td>`;
                            html += `<td>${boardType}</td>`;
                            html += `<td>${courtNoDisplay}</td>`;
                            html += `<td>${row.item_number}</td>`;
                            html += `<td>${row.called_by}</td>`;
                            html += `<td>${diaryNumber}<br/>${row.registration_number_desc}</td>`;
                            html += `<td>${row.petitioner_name}<br/><center>Vs.</center><br/>${row.respondent_name}</td>`;
                            html += `</tr>`;
                        });

                        html += '</tbody>';
                        html += '</table></div>';

                        $('#divCasesForGeneration').html(html);

                        $("#csrf_token").val(response.csrfHash);
                        $("#csrf_token").attr('name', response.csrfName);

                        $("#example1").DataTable({
                            "responsive": true,
                            "lengthChange": false,
                            "autoWidth": false,
                            "dom": 'Bfrtip',
                            "bProcessing": true,
                            "buttons": ["excel", "pdf"]
                        });
                    } else {
                        $('#divCasesForGeneration').html('<div style="text-align: center"><b>No Record Found</b></div>');
                        $("#csrf_token").val(response.csrfHash);
                        $("#csrf_token").attr('name', response.csrfName);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert("Error: " + jqXHR.status + " " + errorThrown);
                }
            });
        }
    });


    $(document).on("focus", ".dtp", function() {
        $('.dtp').datepicker({
            dateFormat: 'dd-mm-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: '1950:2050'
        });
    });
</script>