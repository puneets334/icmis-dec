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

<?php
if(count($summary_details) >=1)
{
    ?>
  <div class="row table-responsive" style="width:97%">
  <table id="unverified_matters" class="table table-striped table-bordered table-hover table-sm" style="width:100%">
    <thead>
        <tr class="header-row">
            <th class="col-sno">SNo.</th>
            <th class="col-diary-no">Diary No.</th>
            <th class="col-diary-date">Diary Date</th>
            <th class="col-da-name">DA Name</th>
            <th class="col-section">Section</th>
            <th class="col-petitioner-name">Petitioner Name</th>
            <th class="col-respondent-name">Respondent Name</th>
        </tr>
    </thead>
    </thead>
    <tbody>
    
    <?php
    $sno=1;
   foreach($summary_details as $data)
    {
       ?>
       <tr>
           <td><?= $sno++ ?></td>
           <td><?= $data['diary_no'] ?></td>
           <td><?= $data['diary_date'] ?></td>
           <td><?= $data['daname'] ?></td>
           <td><?= $data['section'] ?></td>
           <td><?= $data['pet_name'] ?></td>
           <td><?= $data['res_name'] ?></td>
       </tr>
        
        <?php
    }
    ?>
    </tbody>
</table>
</div>
    <?php
}

else

{
    echo "<p id = 'para' style='text-align:center;color: #e53333;font-size: 19px;font-weight: 600;'>No data Available!!!</p>";
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
            "buttons": [
                {
                    extend: 'excel',
                    title: 'diarized_but_not_listed_process_data_<?= date("d-m-Y_h-i-s_A") ?>',
                    filename: 'diarized_but_not_listed_process_data_<?= date("d-m-Y_h-i-s_A") ?>'
                },
                {
                    extend: 'pdfHtml5',
                    orientation: 'Orientation',
                    pageSize: 'LEGAL',
                    title: 'diarized_but_not_listed_process_data_<?= date("d-m-Y h:i:s A") ?>',
                    filename: 'diarized_but_not_listed_process_data_<?= date("d-m-Y_h-i-s_A") ?>'
                }
            ],
            "processing": true,
            "ordering": true,
            "paging": true
        });

        table.buttons().container().appendTo('#unverified_matters_wrapper .col-md-6:eq(0)');
    });
</script>