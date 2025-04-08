<style>
    /* body {
            font-family: "Open Sans", helvetica, arial;
        }

        .css-serial {
            counter-reset: serial-number;
        }

        .css-serial td:first-child:before {
            counter-increment: serial-number;
            content: counter(serial-number);
        }

        #para {
            color: red;
            text-align: center;
            font-size: 20px;
            margin-left: 5px;
            margin-top: 5px;
        } */
</style>
<script>

</script>
<?php if ($Reportsget): ?>
    <h4 style="text-align:center;font-size: 16px;">
        <b>List of Pending/Disposed of Matters Filed by AOR-<?php echo $aorNameText; ?> as on <?php echo date("d-m-Y h:i:s A"); ?></b>
    </h4>
    <br>
    <div>
        <div class="table-responsive">
            <table id="tab" class="table table-striped custom-table">
                <thead style="color:red">
                    <th>SNo</th>
                    <th>Case</th>
                    <th>Cause Title</th>
                    <th>Main/Connected</th>
                    <th>Misc Regular</th>
                    <th>Ready NotReady</th>
                    <th>Section Name</th>
                    <th>Dealing Assistant</th>
                    <th>Status</th>
                </thead>
                <tbody>
                    <?php $no = 1; ?>
                    <?php foreach ($Reportsget as $row): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo $row['no']; ?></td>
                            <td><?php echo $row['causetitle']; ?></td>
                            <td><?php echo $row['main_connected']; ?></td>
                            <td><?php echo $row['misc_regular']; ?></td>
                            <td><?php echo $row['ready_notready']; ?></td>
                            <td><?php echo $row['section_name']; ?></td>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['status']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php else: ?>
    <p id="para">No data Available!!!</p>
<?php endif; ?>

<script>
    $(document).ready(function() {
        $('#tab').DataTable({
            dom: 'Bfrtip',
            "pageLength": 15,
            buttons: [{
                    extend: 'print',
                    text: 'Print',
                    title: 'List of Pending/Disposed of Matters Filed by AOR-<?php echo $aorNameText; ?> as on <?php echo date("d-m-Y h:i:s A"); ?>', // Ensuring no unwanted title appears
                    customize: function(win) {
                        $(win.document.body).css('text-align', 'center'); // Align all content centrally

                    }
                },
                {
                    extend: "excel",
                    title: "List of Pending/Disposed of Matters Filed by AOR-<?php echo $aorNameText; ?> as on <?php echo date("d-m-Y h:i:s A"); ?>"
                },
                {
                    extend: "pdf",
                    title: "List of Pending/Disposed of Matters Filed by AOR-<?php echo $aorNameText; ?> as on <?php echo date("d-m-Y h:i:s A"); ?>",
                    orientation: 'landscape',
                            pageSize: 'LEGAL',
                    customize: function(doc) {
                        doc.content.splice(0, 0, {
                            text: "List of Pending/Disposed of Matters Filed by AOR-<?php echo $aorNameText; ?> as on <?php echo date("d-m-Y h:i:s A"); ?>",
                            fontSize: 9,
                            
                            alignment: "center",
                             margin: [0, 0, 0, 12]
                        });
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
</script>