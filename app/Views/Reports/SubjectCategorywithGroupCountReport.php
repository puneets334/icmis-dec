<?= view('header') ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <!-- Main content -->
                    <div class="card-body">
                        <div class="row">
                            <?= session()->get('msg'); ?>
                        </div>
                        <div class="box box-info">
                            <form class="form-horizontal" id="push-form" method="post" action="<?= base_url(); ?>/Report/pendency_reports/5">
                                <?= csrf_field(); ?>
                                <div class="box-body col-12">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label for="category" class="col-sm-6">Select Subject Category:</label>
                                            <select class="form-control col-sm-6" id="categoryCode" name="categoryCode" placeholder="Subject Category">
                                                <option value="0">All</option>
                                                <?php
                                                $Reports_model = new \App\Models\Reports\PendencyReport\PendencyReportsModel();
                                                $SCategories = $Reports_model->getMainSubjectCategory();
                                                // pr(count($SCategories));
                                                if (!empty($SCategories) > 0) {
                                                    foreach ($SCategories as $SCategory) {
                                                        echo '<option value="' . $SCategory['subcode1'] . '">' . $SCategory['sub_name1'] . '</option>';
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="groupCount" class="col-sm-6 ">Enter Connected Matter in Nos.:</label>
                                            <input type="number" id="groupCount" name="groupCount" class="form-control col-sm-6" placeholder="Enter Group Count" required="required">
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="type" class="col-sm-6">Matter Type:</label>
                                            <select class="form-control col-sm-6" id="matterType" name="matterType">
                                                <option value="MF">All</option>
                                                <option value="M">Miscellaneous</option>
                                                <option value="F">Regular</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="type" class="col-sm-6">Matter Status:</label>
                                            <select class="form-control col-sm-6" id="matterStatus" name="matterStatus">
                                                <option value="NR">All</option>
                                                <option value="R">Ready</option>
                                                <option value="N">Not-Ready</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="box-footer mt-2">
                                        <button type="submit" style="width:15%;float:right" id="view" name="view" onclick="check(); " class="btn btn-block btn-primary">View</button>
                                    </div>
                                </div>
                        </div>
                        </form>
                    </div>
                    <?php
                    if (is_array($reports)) {
                    ?>
                        <div id="printable" class="box box-danger">
                            <?php
                            if ($app_name == 'SubjectCategoryWiseGroupCount') {
                                // var_dump($mentioningReports);
                                if (strcmp($matterType, 'M') == 0)
                                    $type = "Miscellaneous";
                                else if (strcmp($matterType, 'F') == 0)
                                    $type = "Regular";
                                else if (strcmp($matterType, 'MF') == 0)
                                    $type = "";
                                if (strcmp($matterStatus, 'N') == 0)
                                    $status = "Not-Ready";
                                else if (strcmp($matterStatus, 'R') == 0)
                                    $status = "Ready";
                                else if (strcmp($matterStatus, 'NR') == 0)
                                    $status = "";
                            ?>
                                <h3 style="text-align: center;"> List of <strong><?= $status . " " . $type ?></strong> Cases of Subject Category: <strong><?php if (strcasecmp($code, '0') == 0)  echo "All";
                                                                                                                                                            else echo $forCategory; ?></strong> with <strong><?= $_POST['groupCount'] ?></strong> or more Connected Matters as on <?php echo date("d-m-Y H:i:s"); ?></h3>
                                <table id="reportTable1" class="table table-striped table-hover">
                                    <?php if (strcasecmp($code, '0') == 0) { ?>
                                        <thead>
                                            <tr>
                                                <th width="5%">S.No.</th>
                                                <th width="10%" style="text-align: left;">Diary No<br />#Diary Date</th>
                                                <th width="10%">Registration <br> No</th>
                                                <th style="width:15% !important;">Cause <br> Title</th>
                                                <th width="6%">Subject Category</th>
                                                <th width="7%">Next <br> Date</th>
                                                <th width="7%">Dealing <br> Assistant<br>Section</th>
                                                <th width="6%" style="text-align: right;">Misc <br> /Regular</th>
                                                <th width="6%" style="text-align: right;">Main<br>/Connected</th>
                                                <th width="7%" style="text-align: right;">Number <br> of <br> Tagged <br> Matters</th>
                                            </tr>
                                        </thead>
                                        <!--<tfoot>
                <tr>
                    <th colspan="5" style="text-align:right">Total:</th>
                </tr>
                </tfoot> -->
                                        <tbody>
                                            <?php
                                            $s_no = 1;
                                            foreach ($reports as $result) {
                                            ?>
                                                <tr>
                                                    <td><?php //echo $s_no;
                                                        ?></td>
                                                    <td><?php echo $result['diary_no']; ?> / <?php echo $result['diary_year']; ?> # <?php echo date('d-m-Y', strtotime($result['diary_date'])); ?></td>
                                                    <td><?php echo $result['reg_no_display']; ?></td>
                                                    <td><?php echo $result['pet_name']; ?> <strong> Vs.</strong><?php echo $result['res_name']; ?></td>
                                                    <td><?php echo $result['sub_name1']; ?></td>
                                                    <td><?php echo date('d-m-Y', strtotime($result['next_dt'])); ?></td>
                                                    <td><?php echo $result['alloted_to_da']; ?>-<?php echo $result['user_section']; ?></td>
                                                    <td><?php echo $result['mf_active']; ?></td>
                                                    <td><?php echo $result['mainorconn']; ?></td>
                                                    <td><?php echo $result['group_count']; ?></td>
                                                    <!-- <th><a target="_blank" onclick="return confirm('Delete this record?')" href="<?php //echo base_url() 
                                                                                                                                        ?>index.php/Mentioning/deleteMentioningData?diaryNo=<?php echo $result['diary_nos'] ?? ''; ?>&enterDate=<?php echo $result['date_of_received']; ?>&presentedDate=<?php echo $result['date_on_decided']; ?>&decidedDate=<?php echo $result['date_for_decided']; ?>"><i class="glyphicon glyphicon-trash"></i></a></th>-->
                                                </tr>
                                            <?php
                                                $s_no++;
                                            }   //for each
                                            ?>
                                        </tbody>
                                    <?php } else { ?>
                                        <thead>
                                            <tr>
                                                <th width="5%">S.No.</th>
                                                <th width="10%" style="text-align: left;">Diary No<br />#Diary Date</th>
                                                <th width="10%">Registration <br> No</th>
                                                <th style="width:15% !important;">Cause <br> Title</th>
                                                <th width="6%">Section</th>
                                                <th width="7%">Next <br> Date</th>
                                                <th width="7%">Dealing <br> Assistant</th>
                                                <th width="6%" style="text-align: right;">Misc <br> /Regular</th>
                                                <th width="6%" style="text-align: right;">Main<br>/Connected</th>
                                                <th width="7%" style="text-align: right;">Number <br> of <br> Tagged <br> Matters</th>
                                            </tr>
                                        </thead>
                                        <!--<tfoot>
                        <tr>
                            <th colspan="5" style="text-align:right">Total:</th>
                        </tr>
                        </tfoot> -->
                                        <tbody>
                                            <?php
                                            $s_no = 1;
                                            foreach ($reports as $result) {
                                            ?>
                                                <tr>
                                                    <td><?php //echo $s_no;
                                                        ?></td>
                                                    <td><?php echo $result['diary_no']; ?> / <?php echo $result['diary_year']; ?> # <?php echo date('d-m-Y', strtotime($result['diary_date'])); ?></td>
                                                    <td><?php echo $result['reg_no_display']; ?></td>
                                                    <td><?php echo $result['pet_name']; ?> <strong> Vs.</strong><?php echo $result['res_name']; ?></td>
                                                    <td><?php echo $result['user_section']; ?></td>
                                                    <td><?php echo date('d-m-Y', strtotime($result['next_dt'])); ?></td>
                                                    <td><?php echo $result['alloted_to_da']; ?></td>
                                                    <td><?php echo $result['mf_active']; ?></td>
                                                    <td><?php echo $result['mainorconn']; ?></td>
                                                    <td><?php echo $result['group_count']; ?></td>
                                                    <!-- <th><a target="_blank" onclick="return confirm('Delete this record?')" href="<?php //echo base_url() 
                                                                                                                                        ?>index.php/Mentioning/deleteMentioningData?diaryNo=<?php echo $result['diary_nos'] ?? ''; ?>&enterDate=<?php echo $result['date_of_received'] ?? ''; ?>&presentedDate=<?php echo $result['date_on_decided'] ?? ''; ?>&decidedDate=<?php echo $result['date_for_decided'] ?? ''; ?>"><i class="glyphicon glyphicon-trash"></i></a></th>-->
                                                </tr>
                                            <?php
                                                $s_no++;
                                            }   //for each
                                            ?>
                                        </tbody>
                                    <?php } ?>
                                </table>
                        <?php
                            }
                        }
                        ?>
                        </div>
</section>
<!-- Report Div End -->
</div>
</div>
</div>

<script>
    $(document).ready(function() {
        $(function() {
            $('.datepick').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true
            });
        });
        var t = $('#reportTable1,#reportTable2,#reportTable3,#reportTable4').DataTable({
            dom: 'Bfrtip',
            "scrollX": true,
            buttons: [
                'print', 'pageLength'
            ],
            lengthMenu: [
                [10, 25, 50, -1],
                ['10 rows', '25 rows', '50 rows', 'Show all']
            ],
            "columnDefs": [{
                "searchable": false,
                "orderable": false,
                "targets": 0
            }],
            "order": [
                [1, 'asc']
            ],

            "footerCallback": function(row, data, start, end, display) {
                var api = this.api(),
                    data;

                // Remove the formatting to get integer data for summation
                var intVal = function(i) {
                    return typeof i === 'string' ?
                        i.replace(/[\$,]/g, '') * 1 :
                        typeof i === 'number' ?
                        i : 0;
                };
                // Total over all pages
                total = api
                    .column(4)
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                // Total over this page
                pageTotal = api
                    .column(4, {
                        page: 'current'
                    })
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                // Update footer
                $(api.column(4).footer()).html(pageTotal + ' (' + total + ' Total)');
            }

        });
        t.on('order.dt search.dt', function() {
            t.column(0, {
                search: 'applied',
                order: 'applied'
            }).nodes().each(function(cell, i) {
                cell.innerHTML = i + 1;
                t.cell(cell).invalidate('dom');
            });
        }).draw();
    });
</script>


</body>

</html>