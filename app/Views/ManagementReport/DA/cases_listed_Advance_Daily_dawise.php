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
                                <h3 class="card-title">Reports</h3>
                            </div>
                            <div class="col-sm-2">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header p-2" style="background-color: #fff; border-bottom:none;">
                                    <h4 class="basic_heading">Cases Listed In Advance And Daily List</h4>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="active tab-pane">
                                            <form method="POST">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="usercode" id="usercode" value="<?php echo $usercode; ?>" />
                                                <div class="row">
                                                    <div class="col-sm-12 col-md-3 mb-3">
                                                        <button type="submit" id="view" name="view" class="quick-btn mt-26">View REPORT</button>
                                                    </div>
                                                </div>
                                            </form>
                                            <?php
                                           if(COUNT($case_result)>0 && is_array($case_result)) {
                                            ?>
                                                <div class="table-responsive">
                                                    <table id="example1" class="table table-striped custom-table">
                                                        <thead>
                                                            <tr>
                                                                <th style="width: 5%;" rowspan='1'>SNo.</th>
                                                                <th style="width: 10%;" rowspan='1'>List Type</th>
                                                                <th style="width: 15%;" rowspan='1'>CL Date</th>
                                                                <th style="width: 5%;" rowspan='1'>Board Type</th>
                                                                <th style="width: 5%;" rowspan='1'>Court No.</th>
                                                                <th style="width: 10%;" rowspan='1'>Item No.</th>
                                                                <th style="width: 10%;" rowspan='1'>Case No.</th>
                                                                <th style="width: 40%;" rowspan='1'>Title As</th>
                                                                <th style="width: 20%;" rowspan='1'>DA</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $s_no = 1;
                                                            foreach ($case_result as $result) {
                                                            ?>
                                                                <tr>
                                                                    <td><?php echo $s_no; ?></td>
                                                                    <td><?php echo $result['listtype']; ?></td>
                                                                    <td><?php echo $result['cl_date']; ?></td>
                                                                    <td><?php echo $result['board_type']; ?></td>
                                                                    <td><?php echo $result['courtno']; ?></td>
                                                                    <td><?php echo $result['brd_slno']; ?></td>
                                                                    <td><?php echo $result['caseno']; ?></td>
                                                                    <td><?php echo $result['pet_name'] . ' Vs ' . $result['res_name']; ?></td>
                                                                    <td><?php echo $result['uid']; ?></td>
                                                                </tr>
                                                            <?php
                                                                $s_no++;
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            <?PHP
                                            }
                                            ?>
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
    $("#example1").DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
        "dom": 'Bfrtip',
        "bProcessing": true,
        "buttons": ["excel", "pdf"]
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