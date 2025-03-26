<?= view('header') ?>
<?php if (count($records) > 0) { ?>
    <table id="tab" class="table" style="width: 100%">
        <thead>
            <tr><center>
                <th><b>Sno.</b></th>
                <th><b>Diary No.</b></th>
                <th><b>Case No.</b></th>
                <th><b>Title</b></th>
                <th><b>Status</b></th>
                <th><b>Dealing Assistant</b></th>
                <th><b>CM(NSH)</b></th>
            </center></tr>
        </thead>
        <tbody>
            <?php
            $s_no = 1;
            foreach ($records as $data) {
            ?>
                <tr>
                    <td><?= $s_no ?></td>
                    <td><?= $data['diary_no'] ?></td>
                    <td><?= $data['reg_no_display'] ?></td>
                    <td><?= $data['title'] ?></td>
                    <td><?= $data['movement_status'] ?></td>
                    <td><?= $data['da'] ?></td>
                    <td><?= $data['nsh'] ?></td>
                </tr>
            <?php
                $s_no = $s_no + 1;
            } ?>
        </tbody>
    </table>
<?php
} else {
    echo "<center><h2>No records found!</h2></center>";
} ?>

<script>   
    $(document).ready(function() {
        $('#tab thead tr').clone(true).prependTo('#tab thead');
        $('#tab thead tr:eq(0) th').each( function (i) {
            if(i!=0){
                var title = $(this).text();
                $(this).html( '<input type="text" placeholder="'+title+'" />' );
                $( 'input', this ).on( 'keyup change', function () {
                    if ( t.column(i).search() !== this.value) {
                        t.column(i).search(this.value).draw();
                    }
                } );
            }
        } );

        var t = $('#tab').DataTable( {
            "columnDefs": [ {
                "searchable": false,

            } ],
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