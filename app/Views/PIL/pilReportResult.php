<div class="row">
    <div class="col-md-12">


        <?php
        $reportText = "Received";
        if (isset($reportType)) {
            if ($reportType == "D") {
                $reportText = "Destroyed";
            } elseif ($reportType == "P") {
                $reportText = "Petition Date";
            }
        }
        ?>
        <br>

        <h2 align="center">PIL <?= $reportText ?> Between <?php echo !empty($first_date) ? date('d-m-Y', strtotime($first_date)) : ''; ?> to <?php echo !empty($to_date) ? date('d-m-Y', strtotime($to_date)) : ''; ?></h2>
        <br>
        <div id="query_builder_wrapper" class="dataTables_wrapper dt-bootstrap4 query_builder_wrapper">
            <table id="reportTable1" style="width: 100%" class="table table-bordered table-striped datatable_report">
                <thead>
                    <tr>
                        <th width="7%">SNo.</th>
                        <th width="7%">Inward Number</th>
                        <th width="15%">Address To</th>
                        <th width="25%">Received From</th>
                        <th width="7%">Received On</th>
                        <th width="6%">Petition Date</th>
                        <th width="24%">Status</th>
                        <th width="16%">Updated By</th>

                    </tr>
                </thead>
                <tbody>
                    <?php
                  
                    if (!empty($pil_result)) {
                        $i = 1;
                        foreach ($pil_result as $result) {
                            

                    ?>
                            <tr>
                                <td><?= $i++; ?></td>

                                <td>
                                    <a href="<?= base_url(); ?>/PIL/PilController/rptPilCompleteData/<?= $result['id'] ?>" target="_blank">
                                        <?= $result['pil_diary_number']; ?>
                                    </a>
                                </td>
                                <td><?= $result['address_to']; ?></td>
                                <td><?= $result['received_from']; ?><br /><?= $result['address']; ?>
                                    <?php
                                    if (!empty($result['state_name'])) {
                                        echo " ,State: " . $result['state_name'];
                                    }
                                    if (!empty($result['email'])) {
                                        echo "<br/> Email: " . $result['email'];
                                    }
                                    if (!empty($result['mobile'])) {
                                        echo "<br/> Mobile: " . $result['mobile'];
                                    }
                                    ?>
                                </td>
                                <td><?= !empty($result['received_on']) ? date("d-m-Y", strtotime($result['received_on'])) : null ?></td>
                                <td><?= !empty($result['petition_date']) ? date("d-m-Y", strtotime($result['petition_date'])) : null ?></td>
                                <td><?php
                                    if (!empty($result['action_taken'])) {
                                        switch (trim($result['action_taken'])) {
                                            case "L": {
                                                    $actionTakenText = "No Action Required";
                                                    break;
                                                }
                                            case "W": {
                                                    $written_on = (!empty($result['written_on'])) ?  date('d-m-Y', strtotime($result['written_on'])) : '';
                                                    $actionTakenText = "Written Letter to " . $result['written_to'] . " on " . $written_on;
                                                    break;
                                                }
                                            case "R": {
                                                    $return_date = (!empty($result['return_date'])) ?  date('d-m-Y', strtotime($result['return_date'])) : '';
                                                    $actionTakenText = "Letter Returned to Sender on " . $return_date;
                                                    break;
                                                }
                                            case "S": {
                                                    $sent_on = (!empty($result['sent_on'])) ? date('d-m-Y', strtotime($result['sent_on'])) : '';
                                                    $actionTakenText = "  " . $result['sent_to'] . " on " . $sent_on;
                                                    break;
                                                }
                                            case "T": {
                                                    if ($result['transfered_on'] !== null)
                                                        $result['transfered_on'] = date('d-m-Y', strtotime($result['transfered_on']));
                                                    $actionTakenText = "Letter Transferred To " . $result['transfered_to'] . " on " . $result['transfered_on'];
                                                    break;
                                                }
                                            case "I": {
                                                    $actionTakenText = "Letter Converted To Writ";
                                                    break;
                                                }
                                            case "O": {
                                                    $actionTakenText = "Other Remedy";
                                                    break;
                                                }
                                            default: {
                                                    $actionTakenText = "UNDER PROCESS";
                                                    break;
                                                }
                                        }
                                        echo $actionTakenText;
                                    } else {
                                        $actionTakenText = "UNDER PROCESS";
                                        echo $actionTakenText;
                                    }

                                    ?>
                                </td>
                                <td><?= $result['username'] . '(' . $result['empid'] . ')' ?>
                                    <br /> At: <?= date('d-m-Y h:i:s A', strtotime($result['updated_on'])) ?>
                                </td>
                            </tr>

                        <?php
                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="100%">No record found...</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <?//= $pager ?>
        </div>

    </div>
</div>
<script>
 


        $(document).ready(function() {
    $('#reportTable1').DataTable({
        dom: 'Bfrtip',
        "pageLength": 15,
        buttons: [
            {
                extend: 'print',
                text: 'Print',
                title: 'PIL <?= $reportText ?> Between <?php echo !empty($first_date) ? date('d-m-Y', strtotime($first_date)) : ''; ?> to <?php echo !empty($to_date) ? date('d-m-Y', strtotime($to_date)) : ''; ?>', // Ensuring no unwanted title appears
                customize: function (win) {
                    $(win.document.body).css('text-align', 'center'); // Align all content centrally
                     
                }
            },
            'pageLength'
        ],
        lengthMenu: [
            [10, 25, 50, -1],
            ['10 rows', '25 rows', '50 rows', 'Show all']
        ]
    });
});



        $(document).ready(function() {
            // Setup - add a text input to each footer cell
            $('#reportTable1 thead tr').clone(true).appendTo( '#reportTable1 thead' );
            $('#reportTable1 thead tr:eq(1) th').each( function (i) {
                var title = $(this).text();
                var width = $(this).width();
                if(width>260){
                    width=width-80;
                }
                else if(width<100){
                    width=width+20;
                }
                $(this).html( '<input type="text" style="width: '+width+'px" placeholder="'+title+'" />' );

                $( 'input', this ).on( 'keyup change', function () {
                    if ( table.column(i).search() !== this.value ) {
                        table
                            .column(i)
                            .search( this.value )
                            .draw();
                    }
                } );
            } );

        });
</script>