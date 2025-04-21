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

    /* .dataTables_filter {
        margin-top: -48px;
    } */
</style>

<?php
if (count($report) >= 1) {
?>
<h2 class="text-center" >LIST OF FIXED DEPOSIT PENDING CASES</h2><hr>
    <div class="row table-responsive">
        <table id="unverified_matters" class="table table-striped table-bordered table-hover table-sm" style="width:100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Case No</th>
                    <th>Petitioner Name</th>
                    <th>Respondent Name</th>
                    <th>Section</th>
                    <th>FDR/BG No.</th>
                    <th>A/C No.</th>
                    <th>Amount</th>
                    <th>Bank</th>
                    <th>Deposit Date</th>
                    <th>Maturity/Expiry Date</th>
                    <th>Payment Status</th>
                    <th>Rate of Interest</th>
                    <th>Dealing Assistant</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sNo = 1;
                foreach ($report as $row)
                {
                    ?>

                    <tr>
                    <td><?= $sNo++ ?></td>   
                    <td><?= $row['case_number_display'] ?></td>
                    <td><?= $row['petitioner_name'] ?></td>                  
                    <td><?= $row['respondent_name'] ?></td>
                    <td><?= $row['section_name'] ?></td>
                    <td><?= $row['document_number'] ?></td>
                    <td><?= $row['account_number'] ?></td>
                    <td><?= number_format($row['amount'], 2) ?></td>
                    <td><?= $row['bank_name'] ?></td>
                    <td><?= date('d-m-Y', strtotime($row['deposit_date'])) ?></td>
                    <td><?= date('d-m-Y', strtotime($row['maturity_date'])) ?></td> 
                    <td><?= $row['status'] ?></td>
                    <td><?= $row['roi'] ?></td>
                    <td><?= $row['da'] ?></td>
                    </tr>
            
                 <?php   
                }
                ?>
            </tbody>
        </table>
    </div>
<?php
} else {
    echo "<p id='para'>No data Available!!!</p>";
}
?>
<script>
    $(function() {
        var table = $("#unverified_matters").DataTable({
            "responsive": true,
            "searching": true,
            "lengthChange": false,
            "autoWidth": false,
            "pageLength": 20,
            "buttons": [{
                    extend: 'excel',
                    title: 'List of fixed deposit pending cases',
                    filename: 'List_of fixed_deposit_pending_cases_<?= date("d-m-Y_h-i-s_A") ?>'
                },
                {
                    extend: 'pdfHtml5',
                    orientation: 'landscape',
                    pageSize: 'LEGAL',
                    title: 'List of fixed deposit pending cases',
                    filename: 'List_of fixed_deposit_pending_cases_<?= date("d-m-Y_h-i-s_A") ?>'
                }
            ],
            "processing": true,
            "ordering": true,
            "paging": true
        });

        table.buttons().container().appendTo('#unverified_matters_wrapper .col-md-6:eq(0)');
    });
</script>