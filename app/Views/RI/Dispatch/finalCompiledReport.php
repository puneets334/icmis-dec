<?php

// Check if the variable is set and is an array
if (isset($dataToPrintAddressSlip) && is_array($dataToPrintAddressSlip) && count($dataToPrintAddressSlip) > 0) {
?>

<style>
    td a {
        display:inline-block;
        min-height:100%;
        width:100%;
        color: #0c0c0c;
    }
    @media print {
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

<table id="reportTable1" style="width: 95%" class="table table-striped table-hover">
    <thead>
        <tr>
            <th width="4%">#</th>
            <th width="46%">Process ID/ Case No / Fees/ Address</th>
            <th width="50%">Barcode</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $s_no = 1;
        foreach ($dataToPrintAddressSlip as $case) {
        ?>
            <tr>
                <td><?= $s_no ?></td>
                <td style="text-align: left !important;">
                    <a href="<?= base_url();?>index.php/RIController/getCompleteDispatchTransaction/<?=$case['ec_postal_dispatch_id']?>" target="_blank">
                        <?php if ($case['is_with_process_id'] == 1) { ?>
                            <b>Process Id: <?= $case['process_id'] ?>/<?= $case['process_id_year'] ?></b>
                        <?php } else if ($case['is_case'] == 1) {
                            echo "<b>Decree</b>";
                        } else { ?>
                            <b>Reference No.: <?= $case['reference_number'] ?></b>
                        <?php } ?>
                        <br/>
                        <?php if ($case['is_case'] == 1) { ?>
                            <?= $case['case_no'] ?>/<?= $case['section_name'] ?>
                            &nbsp;&nbsp;<?= ($case['postal_charges'] != '' && $case['postal_charges'] != '0') ? '(Rs.' . $case['postal_charges'] . ')' : '' ?>
                            <br/>
                        <?php } ?>
                        <?= ($case['send_to_name']) ?><br/>
                        <?= (($case['send_to_address']) != '') ? '<b>Address: </b>' . ($case['send_to_address']) : '' ?>
                        <?= (($case['district_name']) != '') ? ' ,' . ($case['district_name']) : '' ?>
                        <?= (($case['state_name']) != '') ? ' ,' . ($case['state_name']) : '' ?>
                        <?= ($case['pincode'] != 0) ? ' ,' . $case['pincode'] : '' ?>
                        <?= ($case['doc_type'] != '') ? '<br/><b>Document Type: </b>' . $case['doc_type'] : '' ?>
                    </a>
                </td>
                <td><?= $case['waybill_number'] ?></td>
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
<?php
}
?>

<script>
$(document).ready(function() {
    var t = $('#reportTable1').DataTable({
        "order": [[ 1, 'asc' ]],
        "ordering": false,
        fixedHeader: true,
        scrollX: true,
        autoFill: true,
        dom: 'Bfrtip',
        "pageLength": 15,
        buttons: [
            {
                extend: 'pdfHtml5',
                orientation: 'portrait',
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
    });

    t.on('order.dt search.dt', function () {
        t.column(0, { search: 'applied', order: 'applied' }).nodes().each(function (cell, i) {
            cell.innerHTML = i + 1;
            t.cell(cell).invalidate('dom');
        });
    }).draw();
});
</script>
