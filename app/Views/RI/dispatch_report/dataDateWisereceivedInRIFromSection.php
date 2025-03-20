<?= view('header') ?>
 <style>
    td a {
        display:inline-block;
        min-height:100%;
        width:100%;
        color: #0c0c0c;
    }
    @media print
    {
        td a {
            display:inline-block;
            min-height:100%;
            width:100%;
            color: #0c0c0c;
        }
        a[href]:after {
            content: none !important;
        }
    }
</style>
<?php
if(isset($_POST['fromDate']) && isset($_POST['toDate'])) {
    if (isset($receivedInRIFromSectionData) && sizeof($receivedInRIFromSectionData) > 0) {
        ?>
        <table id="reportTable1" style="width: 95%" class="table table-striped table-hover">
            <thead>
            <tr>
                <th width="4%">#</th>
                <th width="35%">Letter Detail</th>
                <th width="5%">From Section</th>
                <?php if($letterStatusId==2){ ?>
                    <th width="10%">Received By</th>
                    <th width="5%">Received On</th>
               <?php }
               elseif ($letterStatusId==3){ ?>
                   <th width="10%">Dispatched By</th>
                   <th width="5%">Dispatched On</th>
                   <?php
               }
                elseif ($letterStatusId==6){ ?>
                    <th width="10%">Re-Dispatched By</th>
                    <th width="5%">Re-Dispatched On</th>
                    <?php
                }
                elseif ($letterStatusId==4 || $letterStatusId==5){ ?>
                    <th width="10%">Serve/Unserve Updated By</th>
                    <th width="5%">Serve/Unserve Updated On</th>
                    <?php
                }
                elseif ($letterStatusId==7){ ?>
                    <th width="10%">AD Dispatched By</th>
                    <th width="5%">AD Dispatched On</th>
                    <?php
                }?>
                <th width="15%">Dispatch Mode/ Amount/ Waybill No.</th>
                <th width="10%">Current Stage</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $s_no = 1;
            foreach ($receivedInRIFromSectionData as $case) {
                ?>
                <tr>
                    <td></td>
                    <td style="text-align: left !important;">
                        <a href="<?=base_url();?>index.php/RIController/getCompleteDispatchTransaction/<?=$case['ec_postal_dispatch_id']?>" target="_blank">
                        <?php if ($case['is_with_process_id'] == 1) { ?>
                            <b>Process Id: <?= $case['process_id'] ?>/<?= $case['process_id_year'] ?></b>
                        <?php }
                        else if($case['is_case'] == 1){
                            echo "<b>Decree</b>";
                        }
                        else { ?>
                            <b>Reference No.: <?= $case['reference_number'] ?></b>
                        <?php } ?>
                        <br/>
                        <?php if ($case['is_case'] == 1) { ?>
                            <?= $case['case_no'] ?><br/>
                        <?php } ?>
                        <?= isset($case['send_to_name']) ? trim($case['send_to_name']) : '' ?><br/>
                        <?= (isset($case['send_to_address']) && trim($case['send_to_address']) != '') ? '<b>Address: </b>' . trim($case['send_to_address']) : '' ?>
                        <?= (isset($case['district_name']) && trim($case['district_name']) != '') ? ' ,' . trim($case['district_name']) : '' ?>
                        <?= (isset($case['state_name']) && trim($case['state_name']) != '') ? ' ,' . trim($case['state_name']) : '' ?>
                        <?= (isset($case['pincode']) && $case['pincode'] != 0) ? ' ,' . $case['pincode'] : '' ?>
                        <?= (isset($case['doc_type']) && $case['doc_type'] != '') ? '<br/><b>Document Type: </b>' . $case['doc_type'] : '' ?>
                        </a>
                    </td>
                    <td><?= $case['send_to_section'] ?></td>
                    <td><?= $case['sent_by'] ?></td>
                    <td><?= date("d-m-Y h:i:s A", strtotime($case['sent_on'])) ?></td>
                    <td><?=$case['postal_type_description']?>
                        <?=($case['postal_charges'] !='' && $case['postal_charges'] !='0') ? '<br/><b>Amount: </b>'.$case['postal_charges'] : ''?>
                        <?=($case['waybill_number'] !='') ? '<br/><b>Postal No.: </b>'.$case['waybill_number'] : ''?>
                    </td>
                    <td><?= $case['current_status'] ?></td>
                </tr>
                <?php
                $s_no++;
            }
            ?>
            </tbody>
        </table>
        <?php
    } else {
        ?>
        <div class="form-group col-sm-12">
            <label class="text-danger">&nbsp;No Record Found!!</label>
        </div>

    <?php }
}

?>
<script>
    $(document).ready(function() {
        $('#reportTable1 thead tr').clone(true).prependTo( '#reportTable1 thead' );
        $('#reportTable1 thead tr:eq(0) th').each( function (i) {
            if(i!=0){
                var title = $(this).text();
                var width = $(this).width();
                if(width>260){
                    width=width-100;
                }
                else if(width<100){
                    width=width+20;
                }
                $(this).html( '<input type="text" style="width: '+width+'px" placeholder="'+title+'" />' );

                $( 'input', this ).on( 'keyup change', function () {
                    if ( t.column(i).search() !== this.value) {
                        t
                            .column(i)
                            .search( this.value )
                            .draw();
                    }
                } );
            }
        } );


        var t = $('#reportTable1').DataTable( {
            "order": [[ 1, 'asc' ]],
            "ordering": false,
            fixedHeader: true,
            scrollX: true,
            autoFill: true,
            dom: 'Bfrtip',
            "pageLength":15,
            buttons: [
                {
                    extend: 'pdfHtml5',
                    orientation: 'landscape',
                    pageSize: 'A4',
                    exportOptions: {
                        columns: ':visible',
                        stripNewlines: false
                    }
                },
                {
                    extend: 'print',
                    exportOptions: {
                        columns: ':visible',
                        stripHtml: false
                    }
                },
                'pageLength',
                {
                    extend: 'colvis',
                    columns: ':gt(1)'
                },
            ],
            lengthMenu: [
                [ 10, 25, 50, -1 ],
                [ '10 rows', '25 rows', '50 rows', 'Show all' ]
            ]
        } );

        t.on( 'order.dt search.dt', function () {
            t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = i+1;
                t.cell(cell).invalidate('dom');
            } );
        } ).draw();
    } );
</script>
