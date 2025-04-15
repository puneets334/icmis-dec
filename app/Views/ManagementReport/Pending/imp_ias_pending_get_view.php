<style>
    .dataTables_filter{padding-right: 55px;}
    .table-striped tr:nth-child(odd) td {background: #ECEEF2 !important;}
    .table-striped tr:nth-child(even) td{background: #ffffff;}
    table.dataTable thead th, table.dataTable tfoot th {font-weight: bold !important;}
    div.dt-buttons {float: left;margin-top: 0px;}
</style>
<?php if (count($data) > 0) { ?>
    <h3 style="text-align:center;" id="caption">IMPORTANT IAs PENDING As On <?php echo date("d-m-Y h:i:s a"); ?></h3>
    <div class="table-responsive">
        <table id="customers" class="table table-striped custom-table">
            <!--<table align="left" width="100%" border="0px;" style=" padding: 10px; font-size:13px; table-layout: fixed;">-->
            <thead>
                <tr style="background: #918788;">
                    <th width="7%">SrNo.</th>
                    <th width="16%">Case No. @ Diary No.</th>
                    <th width="20%">Cause Title</th>
                    <!--<th width="10%">Next_DOL</th>-->
                    <th width="20%">IA Name</th>
                    <th width="10%">IA Date</th>
                    <th width="10%">Main Case DNo.</th>
                    <th width="7%">Section</th>
                    <th width="10%">DA</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sno = 1;
                foreach ($data as $ro) {
                    $sno1 = $sno % 2;
                    if ($sno1 == '1') { ?>
                        <tr>
                        <?php } else { ?>
                        <tr>
                        <?php
                    }
                        ?>
                        <td align="left"><?php echo $ro['sno']; ?></td>
                        <td align="left"><?php echo $ro['case_no'] . " @ " . $ro['diary_no']; ?></td>
                        <td align="left"><?php echo $ro['cause_title']; ?></td>
                        <!--<td align="left"><?php /*echo $ro['Next_Listing_Dt']; */ ?></td>-->
                        <td align="left"><?php echo $ro['docdesc']; ?></td>
                        <td align="left"><?php echo $ro['ia_date1']; ?></td>
                        <td align="left"><?php echo $ro['main_case_diary']; ?></td>
                        <td align="left"><?php echo $ro['section'];  ?></td>
                        <td align="left"><?php echo $ro['da']; ?></td>
                        </tr>
                    <?php
                    $sno++;
                }
                    ?>
            </tbody>
        </table>
    </div>
    
<?php
} else {
    echo "No Recrods Found";
}
?>

<script>
    $("#customers").DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
        "dom": 'Bfrtip',
        "bProcessing": true,
        "buttons": [
            {
                extend: 'print',
                text: 'Print',
                title:'',
                customize: function (win) {
                    $(win.document.body).css( 'font-size', '12pt');
                    const captionHTML = $('#caption').html();
                    $(win.document.body).find('table').before('<h3 style="text-align: center;">' + captionHTML + '</h3>');
                },
            }
        ]
    });
</script>