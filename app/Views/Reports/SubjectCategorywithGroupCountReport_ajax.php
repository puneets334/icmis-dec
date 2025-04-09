<style>
     table.dataTable>thead .sorting,
    table.dataTable>thead {
        background-color: #0d48be !important;
        color: #fff !important;
    }

    table.dataTable>thead .sorting_disabled,
    table.dataTable>thead {
        background-color: #0d48be !important;
        color: #fff !important;
    }
    .dataTables_filter
    {
        margin-top: -48px;
    }
    div.dataTables_wrapper {
    width: 100%;
}
</style>
<?php
if (is_array($reports)) {

    if ($app_name == 'SubjectCategoryWiseGroupCount') {
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
        <h3 style="text-align: center;"> List of <strong><?= $status . " " . $type ?></strong> 
        Cases of Subject Category: <strong><?php if (strcasecmp($code, '0') == 0)  echo "All";
        else echo $forCategory; ?></strong> with <strong><?= $groupCount; ?></strong> or more Connected Matters as on 
        <?php echo date("d-m-Y H:i:s"); ?></h3>
        <div class="row table-responsive" style="width:98% !important">
        <table id="reportTable1" class="table table-striped table-hover table-bordered" >
            <?php if (strcasecmp($code, '0') == 0) { ?>
                <thead>
                    <tr>
                        <th>S.No.</th>
                        <th>Diary No #Diary Date</th>
                        <th>Registration No</th>
                        <th>Cause Title</th>
                        <th>Subject Category</th>
                        <th>Next Date</th>
                        <th>Dealing Assistant Section</th>
                        <th>Misc/Regular</th>
                        <th>Main/Connected</th>
                        <th>Number of Tagged Matters</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    $s_no = 1;
                    foreach ($reports as $result) {
                    ?>
                        <tr>
                            <td><?php echo $s_no; ?></td>
                            <td><?php echo $result['diary_no']; ?> / <?php echo $result['diary_year']; ?> # <?php echo date('d-m-Y', strtotime($result['diary_date'])); ?></td>
                            <td><?php echo $result['reg_no_display']; ?></td>
                            <td><?php echo $result['pet_name']; ?> <strong> Vs.</strong><?php echo $result['res_name']; ?></td>
                            <td><?php echo $result['sub_name1']; ?></td>
                            <td><?php echo $result['next_dt'] != '' ? date('d-m-Y', strtotime($result['next_dt'])) : ''; ?></td>
                            <td><?php echo $result['alloted_to_da']; ?>-<?php echo $result['user_section']; ?></td>
                            <td><?php echo $result['mf_active']; ?></td>
                            <td><?php echo $result['mainorconn']; ?></td>
                            <td><?php echo $result['group_count']; ?></td>
                            <!-- <th><a target="_blank" onclick="return confirm('Delete this record?')" href="<?php //echo base_url() 
                                                                                                                ?>index.php/Mentioning/deleteMentioningData?diaryNo=<?php echo @$result['diary_nos'] ?? ''; ?>&enterDate=<?php echo @$result['date_of_received']; ?>&presentedDate=<?php echo @$result['date_on_decided']; ?>&decidedDate=<?php echo @$result['date_for_decided']; ?>"><i class="glyphicon glyphicon-trash"></i></a></th>-->
                        </tr>
                    <?php
                        $s_no++;
                    }   //for each
                    ?>
                </tbody>
            <?php } else { ?>
                <thead>
                    <tr>
                        <th>S.No.</th>
                        <th>Diary No #Diary Date</th>
                        <th>Registration No</th>
                        <th>Cause Title</th>
                        <th>Section</th>
                        <th>Next Date</th>
                        <th>Dealing Assistant</th>
                        <th>Misc/Regular</th>
                        <th>Main/Connected</th>
                        <th>Number of Tagged Matters</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    $s_no = 1;
                    foreach ($reports as $result) {
                    ?>
                        <tr>
                            <td><?php echo $s_no; ?></td>
                            <td><?php echo $result['diary_no']; ?> / <?php echo $result['diary_year']; ?> # <?php echo date('d-m-Y', strtotime($result['diary_date'])); ?></td>
                            <td><?php echo $result['reg_no_display']; ?></td>
                            <td><?php echo $result['pet_name']; ?> <strong> Vs.</strong><?php echo $result['res_name']; ?></td>
                            <td><?php echo $result['user_section']; ?></td>
                            <td><?php echo $result['next_dt'] != '' ? date('d-m-Y', strtotime($result['next_dt'])) : ''; ?></td>
                            <td><?php echo $result['alloted_to_da']; ?></td>
                            <td><?php echo $result['mf_active']; ?></td>
                            <td><?php echo $result['mainorconn']; ?></td>
                            <td><?php echo $result['group_count']; ?></td>
                            <!--
                            <td>
                                <a target="_blank" onclick="return confirm('Delete this record?')" 
                                    href="<?= base_url() ?>index.php/Mentioning/deleteMentioningData?diaryNo=<?= @$result['diary_nos'] ?? '' ?>
                                    &enterDate=<?= @$result['date_of_received'] ?? '' ?>&presentedDate=<?= @$result['date_on_decided'] ?? '' ?>&decidedDate=<?= @$result['date_for_decided'] ?? '' ?>">
                                    <i class="glyphicon glyphicon-trash"></i>
                                </a>
                            </td>-->
                        </tr>
                    <?php
                        $s_no++;
                    }   //for each
                    ?>
                </tbody>
            <?php } ?>
        </table>
        </div>
<?php
    }
}
?>

<script>
    $(document).ready(function() {       
        var t = $('#reportTable1').DataTable({
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'print',
                    title: "List of <?= $status . " " . $type ?> Cases of Subject Category: <?php if (strcasecmp($code, '0') == 0) 
                     echo "All"; else echo $forCategory; ?> with <?= $groupCount; ?> or more Connected Matters as on <?= date("d-m-Y H:i:s"); ?>",
                },
                'pageLength'
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
                var api = this.api();
                var intVal = function(i) {
                    return typeof i === 'string' ?
                        i.replace(/[\$,]/g, '') * 1 :
                        typeof i === 'number' ?
                        i : 0;
                };
                var total = api
                    .column(9)
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                var pageTotal = api
                    .column(9, {
                        page: 'current'
                    })
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                $(api.column(9).footer()).html(pageTotal + ' (' + total + ' Total)');
            }
        });

        t.on('order.dt search.dt', function() {
            t.column(0, {
                search: 'applied',
                order: 'applied'
            }).nodes().each(function(cell, i) {
                cell.innerHTML = i + 1;
            });
        }).draw();
    });
</script>