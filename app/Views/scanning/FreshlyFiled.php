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

    table.dataTable>thead .sorting,
    table.dataTable>thead {
        background-color: #0d48be !important;
        color: #fff !important;
    }

    .h5-title {
        /* font-style: italic; */
        font-weight: 600;
        text-align: center;
        /* text-decoration: underline; */
    }
    @media print {
            table thead tr th {
                font-weight: bold;
                color: black;  /* Set header color to black for print */
                background-color: #f1f1f1; /* Optionally set background color */
            }
            /* You can also hide unnecessary elements like pagination buttons during print */
            .dataTables_paginate {
                display: none;
            }
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
                                <h3 class="card-title">Scanning >> Freshly Filed - Verified Cases</h3>
                            </div>
                            <div class="col-sm-2">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="active tab-pane">
                                            <form id="push-form" method="POST" action="">
                                                <?= csrf_field(); ?>
                                                <div class="row">
                                                    <div class="col-sm-5">
                                                        <div class="form-group row">
                                                            <label for="from" class="col-sm-6 mt-3">From Date</label>
                                                            <div class="col-sm-6">
                                                                <input type="text" id="fromDate" value="<?= isset($fromDate) ? date("d-m-Y", strtotime($fromDate)) : '' ?>" name="fromDate" class="form-control datepicker" required="" placeholder="From Date">
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <div class="col-sm-5">

                                                        <div class="form-group row">
                                                            <label for="from" class="col-sm-6 mt-3">To Date</label>
                                                            <div class="col-sm-6">
                                                                <input type="text" id="toDate" name="toDate" value="<?= isset($toDate) ? date("d-m-Y", strtotime($toDate)) : '' ?>" class="form-control datepicker" required="" placeholder="To Date">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-2">

                                                        <div class="form-group row">

                                                            <div class="col-sm-7">
                                                                <button type="submit" id="view" name="view" value="date_wise" class="btn btn-block btn-primary">Search</button>


                                                            </div>
                                                        </div>
                                                    </div>
                                            </form>

                                        </div>
                                        <div id="dv_data" class="m-3">

                                            <?php
                                            if (!empty($title)) {
                                                echo "<h5 class='h5-title'>" . $title . "</h5>";
                                            }
                                            if (isset($FreshlyData) && count($FreshlyData) > 0) {
                                            ?>
                                                <table id="reportTable1" class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr style="background-color:darkgrey;">
                                                            <th style="width: 5%;">SNo</th>
                                                            <th style="width:25%;">Case No./Diary No.</th>
                                                            <th style="width: 10%;">Filed On</th>
                                                            <th style="width: 45%;">Cause Title</th>
                                                            <th style="width:15%;">Verified On</th>
                                                    </thead>
                                                    <?php
                                                    foreach ($FreshlyData as $k => $data) {
                                                    ?>
                                                        <tr>
                                                            <td><?= $k + 1; ?></td>
                                                            <td><?= $data->case_no; ?></td>
                                                            <td><?= $data->diary_dt; ?></td>
                                                            <td><?= $data->cause_title; ?></td>
                                                            <td><?= $data->verified_on; ?></td>
                                                        </tr>

                                                    <?php
                                                    }
                                                    ?>
                                                    <tbody>


                                                    </tbody>


                                                </table>
                                            <?php } ?>
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
    </div>
</section>

<script>
    $(document).ready(function() {
        $("#reportTable1").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "dom": 'Bfrtip',
            "bProcessing": true,
           "pageLength": 25,     
            "buttons": [{
                    extend: 'excelHtml5',
                    text: 'Export to Excel',
                    title: '<?= $title ?? 'Freshly-filed-verified-cases'?>',
                    filename: '<?= $title ?? 'Freshly-filed-verified-cases'?>'
                },
                {
                    extend: 'pdfHtml5',
                    text: 'Save as PDF',
                    title: '<?= $title ?? 'Freshly-filed-verified-cases'?>',
                    filename: '<?= $title ?? 'Freshly-filed-verified-cases'?>'
                },
                {
                    extend: 'print',
                    text: 'Print',
                    title: '<?= $title ?? 'Freshly-filed-verified-cases'?>',
                    filename: '<?= $title ?? 'Freshly-filed-verified-cases'?>'
                }
            ]
        });
    });
    $('.datepicker').datepicker({
        format: 'dd-mm-yyyy',
        changeMonth: true,
        changeYear: true,
        yearRange: '1950:2050'
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