<?php $count = count($result); ?>
<div id="divprint">
  <h1 style="color: blue;font-size: 1.2em;text-align: center">Total Refiling:<?php echo $count ?></h1>
  <h2 style="text-align: center;text-transform: capitalize;color: blue;"> Matters Refiled on <?php echo $dateFrom; ?> <br></h2>

  <div class="table-responsive">
    <table id="diaryReport" class="table table-striped custom-table">
      <thead>
        <tr>
          <th>Sr.No.</th>
          <th>Diary No</th>
          <th>FDR User</th>
          <th>Refiling Date</th>
          <th>Scrutiny User</th>
          <th>Token No.</th>
          <th>Remarks</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $dateFromFormatted = date('Y-m-d', strtotime($dateFrom));
        $srno = 1;
        if (!empty($result)) {
          if (count($result) > 0) {
            foreach ($result as $row) {
        ?>
              <tr>
                <td style="text-align: center;"><?php echo $srno ?></td>
                <td> <?php echo $row['diary_no']; ?>-<?php echo $row['diary_year']; ?></td>
                <td><?php echo $row['dispatch_by']; ?></td>
                <td><?php echo $_POST['dateFrom']; ?></td>
                <td><?php echo $row['dispatch_to'];
                    if ($row['attend'] == 'A') echo "<font color='red'> [Absent]</font>";
                    ?></td>
                <td style="text-align: center;"><?php
                    if ($row['token_no'] == null or $row['token_no'] == 0) {
                      $row['token_no'] = $model->RefilingReportData($row['diary_no'], $row['diary_year'], $dateFromFormatted);
                    }
                    echo $row['token_no'];
                    ?>
                </td>
                <td></td>
              </tr>
        <?php $srno++;
            }
          }
        }
        ?>
      </tbody>
    </table>
  </div>

  <script>
 $(function() {
        $("#diaryReport").DataTable({
            "responsive": true,
            "lengthChange": true,
            "ordering": false,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", {
                    extend: 'pdfHtml5',
                    orientation: 'landscape',
                    pageSize: 'LEGAL'
                },
                {
                    extend: 'colvis',
                    text: 'Show/Hide'
                }
            ],
            "bProcessing": true,
            "extend": 'colvis',
            "text": 'Show/Hide'
        }).buttons().container().appendTo('#query_builder_wrapper .col-md-6:eq(0)');

    });
</script>