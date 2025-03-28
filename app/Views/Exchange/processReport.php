<?php if (count($records) > 0) { ?>
    <table id="table1" class="table" style="width: 100%">
        <thead>
            <tr>
                <th width="1%"><b>Sno.</b></th>
                <th width="2%"><b>Transaction_Date</b></th>
                <th width="6%"><b>Sent to CM(NSH)</b></th>
                <th width="6%"><b>Received by CM(NSH)</b></th>
                <th width="6%"><b>Refused to receive by CM(NSH)</b></th>
                <th width="6%"><b>Sent Back to Dealing Assistant</b></th>
                <th width="6%"><b>Received by dealing assistant</b></th>
            </tr>
        </thead>
        <tbody>
            <?php
            $s_no = 1;
            foreach ($records as $data) {
            ?>
                <tr>
                    <td><?= $s_no ?></td>
                    <td><?= $data['transaction_date'] ?></td>
                    <td>
                        <a id="1" href="<?php echo base_url() ?>/Exchange/causeListFileMovement/getSQLProcessReport?date=<?= $data['transaction_date'] ?>&id=1" target="_blank">
                            <?= $data['s1'] ?>
                        </a>
                    </td>    
                    <td>
                        <a id="2" href="<?php echo base_url() ?>/Exchange/causeListFileMovement/getSQLProcessReport?date=<?= $data['transaction_date'] ?>&id=2" target="_blank">
                            <?= $data['s2'] ?>
                        </a>
                    </td>
                    <td>    
                        <a id="3" href="<?php echo base_url() ?>/Exchange/causeListFileMovement/getSQLProcessReport?date=<?= $data['transaction_date'] ?>&id=3" target="_blank">
                            <?= $data['s3'] ?>
                        </a>
                    </td>
                    <td>    
                        <a id="4" href="<?php echo base_url() ?>/Exchange/causeListFileMovement/getSQLProcessReport?date=<?= $data['transaction_date'] ?>&id=4" target="_blank">
                            <?= $data['s4'] ?>
                        </a>
                    </td>
                    <td>    
                        <a id="5" href="<?php echo base_url() ?>/Exchange/causeListFileMovement/getSQLProcessReport?date=<?= $data['transaction_date'] ?>&id=5" target="_blank">
                            <?= $data['s5'] ?>
                        </a>
                    </td>    
                </tr>
            <?php
                $s_no = $s_no + 1;
            } ?>
        </tbody>
    </table>
<?php
} else {
    //echo "No Record Found!!";
    echo "<center><h2>No records found!</h2></center>";
} ?>

<script>   $
    (document).ready(function() {
        $('#table1 thead tr').clone(true).prependTo('#table1 thead');
        $('#table1 thead tr:eq(0) th').each( function (i) {
            if(i!=0){
                var title = $(this).text();
                var width = $(this).width();
                if(width>120) {
                    width=width-120;
                }

                $(this).html( '<input type="text" style="width:'+width+'px" placeholder="'+title+'" />' );
                $( 'input', this ).on( 'keyup change', function () {
                    if ( t.column(i).search() !== this.value) {
                        t.column(i).search(this.value).draw();
                    }
                } );
            }
        } );


        var t = $('#table1').DataTable( {
            "columnDefs": [ {
                "searchable": false,
                // "targets": 0,
                // "targets": [5,6,7], "visible": false
            } ],
            "order": [[ 1, 'asc' ]],
            "ordering": false,
            fixedHeader: true,
            scrollX: true,
            autoFill: true,
            dom: 'Bfrtip',
            "pageLength":25,
            buttons: [
                {
                    extend: 'pdfHtml5',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'print',
                    exportOptions: {
                        columns: ':visible'
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