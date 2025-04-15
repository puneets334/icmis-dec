<?= view('header') ?>
<style>
    table.dataTable>thead .sorting,
    table.dataTable>thead {
        background-color: #0d48be !important;
        color: #fff !important;
        white-space: nowrap;
    }

    table.dataTable>thead .sorting_disabled,
    table.dataTable>thead {
        background-color: #0d48be !important;
        color: #fff !important;
        white-space: nowrap;
    }
    table tfoot tr th {
        background-color: #0d48be !important;
        color: #fff !important;
        white-space: nowrap;
    }
    .dataTables_filter
    {
        margin-top: -48px;
    }
</style>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <!-- Main content -->
                    <div class="card-body">
                        <?php
                        // echo $app_name;
                        if (isset($reports) && sizeof($reports) > 0 && (!empty($reports))) {

                            if ($app_name == 'JudgeWise') {
                        ?>
                                <div id="printable" class="table-responsive">
                                    <div class="card-header heading">

                                        <div class="row">
                                            <div class="col-sm-10">
                                                <h3 class="card-title">Judges Wise Pendency as on <?php echo date("d-m-Y"); ?></h3>
                                            </div>
                                        </div>
                                    </div>
                                    <table id="reportTable1" class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>S.No.</th>
                                                <th width="50%">Judge Name</th>
                                                <th>Main Case Count</th>
                                                <th>Connected Case Count</th>
                                                <th>Total Pendency</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th colspan="5" style="text-align:right">Total:</th>
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                            <?php
                                            $s_no = 1;
                                            foreach ($reports as $row) {
                                            ?>
                                                <tr>
                                                    <td><?php //echo $s_no; 
                                                        ?></td>

                                                    </td>
                                                    <td>
                                                        <!-- <a target="_blank" href="<?php //echo base_url() 
                                                                                        ?>index.php/Reports/pendency_reports/3?jcode=<?php //echo $row['jcode']; 
                                                                                                                                        ?>">
                                                        <?php //echo $row['jname']; 
                                                        ?>
                                                    </a> -->
                                                        <?php echo $row['jname']; ?>
                                                        (<?php if ($row['is_retired'] == 1) { ?> <span style="color: #c23321">Retired </span> <?php } else { ?><span style="color:#008d4c">Sitting Judge </span><?php } ?>)
                                                    </td>

                                                    <td>
                                                        <?php
                                                        echo $row['MainCaseCount'] ?? 0;
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        echo $row['ConnectedCaseCount'] ?? 0;
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $row['judge_wise_pendency'] ?? 0; ?>
                                                    </td>
                                                </tr>
                                            <?php
                                                $s_no++;
                                            }   //for each
                                            ?>
                                        </tbody>
                                    </table>
                                <?php
                            }

                            if ($app_name == 'CategoryWise') {
                                // var_dump($reports);
                                ?>
                                    
                                        <div class="card-header heading">

                                            <div class="row">
                                                <div class="col-sm-10">
                                                    <h3 class="card-title">Management Report >> Pending >> Subject Category-wise Pendency As On <?php echo date("d-m-Y"); ?></h3>
                                                </div>
                                            </div>
                                        </div>
                                        <table id="reportTable2" class="table table-striped table-hover table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>S.No.</th>
                                                    <th>Category Code</th>
                                                    <th>Main Category</th>
                                                    <th>Sub. Category</th>
                                                    <th>Total Pendency</th>
                                                    <th>Misc. Ready</th>
                                                    <th>Misc. Not Ready</th>
                                                    <th>Regular Ready</th>
                                                    <th>Regular Not Ready</th>
                                                </tr>
                                            </thead>
                                           
                                            <tbody>
                                                <?php
                                                $s_no = 1;
                                                foreach ($reports as $row) {
                                                ?>
                                                    <tr>
                                                        <td><?php echo $s_no ,'.'; ?></td>
                                                        <td>
                                                            <!-- <a target="_blank" href="<?php //echo base_url() 
                                                                                            ?>index.php/Reports/pendency_reports/4?categoryCode=<?php //echo $row['category_sc_old']; 
                                                                                                                                                ?>">
                                                            <?php //echo $row['category_sc_old']; 
                                                            ?>
                                                        </a> -->
                                                            <?php echo $row['category_sc_old']; ?>
                                                        </td>
                                                        <td><?php echo $row['sub_name1']; ?></td>
                                                        <td>
                                                            <!-- <a target="_blank" href="<?php //echo base_url() 
                                                                                            ?>index.php/Reports/pendency_reports/4?categoryCode=<?php //echo $row['category_sc_old']; 
                                                                                                                                                ?>">
                                                            <?php //echo $row['sub_name4']; 
                                                            ?>
                                                        </a> -->
                                                            <?php echo $row['sub_name4']; ?></td>
                                                        <td><?php echo $row['total_pendency'];?></td>
                                                        <td><?php echo $row['misc_ready']; ?></td>
                                                        <td><?php echo $row['misc_not_ready'];?></td>
                                                        <td><?php echo $row['regular_ready']; ?></td>
                                                        <td><?php echo $row['regular_not_ready'];?></td>
                                                    </tr>
                                                <?php
                                                    $s_no++;
                                                }   //for each
                                                ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                <th colspan="4" style="text-align:right">Total :</th>
                                                    <th colspan="5" >Total:</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    <?php
                                }

                                if ($app_name == 'JudgeWiseDetails') {
                                    foreach ($reports as $result) {
                                        $jname = $result['jname'];
                                        $jcode = $result['jcode'];
                                    }
                                    ?>
                                        <div id="printable">
                                            <div class="card-header heading">

                                                <div class="row">
                                                    <div class="col-sm-10">
                                                        <h3 class="card-title">PENDING CASES IN WHICH <?= $jname ?>(<?= $jcode ?>)<br />
                                                            WAS UPDATED AS CORAM AS ON <?php echo date("d-m-Y"); ?></h3>
                                                    </div>
                                                </div>
                                            </div>
                                            <table width="90%" id="reportTable3" class="table table-striped table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>S.No.</th>
                                                        <th>Section</th>
                                                        <th width="17%" style="text-align: left;">Diary No<br />#Diary Date</th>
                                                        <th>Next Date</th>
                                                        <th>Active File No</th>
                                                        <th>Cause Title</th>
                                                        <th width="30%">Case Stage </th>
                                                        <th>Allotted to DA</th>
                                                        <th>Description</th>
                                                        <th>Updated By</th>
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
                                                            <td><?php echo $result['user_section']; ?></td>
                                                            <td><?php echo $result['diary_no']; ?> / <?php echo $result['diary_year']; ?> # <?php echo date('d-m-Y', strtotime($result['diary_date'])); ?></td>
                                                            <td><?php echo $result['next_dt']; ?></td>
                                                            <td><?php echo $result['active_fil_no']; ?></td>
                                                            <td><?php echo $result['pet_name']; ?> <strong> Vs.</strong><?php echo $result['res_name']; ?></td>
                                                            <td><?php echo $result['stagename']; ?></td>
                                                            <td><?php echo $result['alloted_to_da']; ?></td>
                                                            <td><?php echo $result['descrip']; ?></td>
                                                            <td><?php echo $result['updated_by']; ?></td>
                                                        </tr>
                                                    <?php
                                                        $s_no++;
                                                    }   //for each
                                                    ?>
                                                </tbody>
                                            </table>
                                        <?php
                                    }

                                    if ($app_name == 'CategoryWiseDetails') {
                                        foreach ($reports as $result) {
                                            $categoryCode = $result['category_sc_old'];
                                            $SubCategory = $result['sub_name4'];
                                            $mainCatgeory = $result['sub_name1'];
                                        }
                                        ?>
                                            <div id="printable">

                                                <table width="90%" id="reportTable4" class="table table-striped table-hover">
                                                    <caption>
                                                        <h3 style="text-align: center;"> PENDING CASES IN WHICH <strong>[Sub Category:<?= $categoryCode ?>-<?= $SubCategory ?>][Main Category:<?= $mainCatgeory ?>]</strong><br />
                                                            AS ON <?php echo date("d-m-Y"); ?></h3>
                                                    </caption>
                                                    <thead>
                                                        <tr>
                                                            <th>S.No.</th>
                                                            <th>Section</th>
                                                            <th width="17%" style="text-align: left;">Diary No<br />#Diary Date</th>
                                                            <th>Next <BR> Date</th>
                                                            <th>Registration <BR> No</th>
                                                            <th>Connected <BR> With</th>
                                                            <th width="30%">Cause<BR> Title</th>
                                                            <th width="30%">Case<BR> Stage </th>
                                                            <th>Allotted<BR> to DA</th>
                                                            <th>Description</th>
                                                            <th>Misc. or Regular</th>
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
                                                                <td><?php echo $result['user_section']; ?></td>
                                                                <td><?php echo $result['diary_no']; ?> / <?php echo $result['diary_year']; ?> # <?php echo date('d-m-Y', strtotime($result['diary_date'])); ?></td>
                                                                <td><?php echo $result['next_dt']; ?></td>
                                                                <td><?php echo $result['reg_no_display']; ?></td>
                                                                <td><?php echo $result['connected_with']; ?></td>
                                                                <td><?php echo $result['pet_name']; ?> <strong> Vs.</strong><?php echo $result['res_name']; ?></td>
                                                                <td><?php echo $result['stagename']; ?></td>
                                                                <td><?php echo $result['alloted_to_da']; ?></td>
                                                                <td><?php echo $result['descrip']; ?></td>
                                                                <td><?php echo $result['mf_active']; ?></td>
                                                            </tr>
                                                        <?php
                                                            $s_no++;
                                                        }   //for each
                                                        ?>
                                                    </tbody>
                                                </table>
                                            <?php
                                        }
                                            ?>
                                            <br><br>
                                            <!-- <button type="submit"  style="width:15%;float:right;" id="print" name="print"  onclick="printDiv('printable')" class="btn btn-block btn-warning">Print</button>-->
                                        <?php } else {
                                        ?>
                                            <div class="cl_center"><b>No Record Found.</b></div>
                                        <?php
                                    }
                                        ?>

                                            </div> <!-- Report Div End -->
                                        </div>
                                    </div>
                                </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script src="<?= base_url() ?>assets/js/Reports.js"></script>
<script>
    $(document).ready(function()
    {
        
        //var reportTitle = "Cases Listed in Advance and Daily List";
        var t = $('#reportTable1,#reportTable2,#reportTable3,#reportTable4').DataTable({
            dom: 'Bfrtip',
            pageLength: 25,
            buttons: [
                'print', 'pageLength'
            ],
            lengthMenu: [
                [10, 25, 50, -1],
                ['10 rows', '25 rows', '50 rows', 'Show all']
            ],

            "footerCallback": function(row, data, start, end, display) {
                var api = this.api(),
                    data;

                
                var intVal = function(i) {
                    return typeof i === 'string' ?
                        i.replace(/[\$,]/g, '') * 1 :
                        typeof i === 'number' ?
                        i : 0;
                };
                total = api
                    .column(4)
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                pageTotal = api
                    .column(4, {
                        page: 'current'
                    })
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                $(api.column(4).footer()).html(pageTotal + ' (' + total + ' Total)');
            },
            "columnDefs": [{
                "searchable": false,
                "orderable": false,
                "targets": 0
            }],
            "order": [
                [1, 'asc']
            ]

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