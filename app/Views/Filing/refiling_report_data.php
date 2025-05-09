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
        "responsive": false,
        "lengthChange": false,
        "autoWidth": false,
        "dom": 'Bfrtip',
        "buttons": [
            {
                extend: "pdfHtml5",
                title: "Matters Refiled on\n(As on <?php echo $dateFrom; ?>)",
                customize: function(doc) {
                    doc.content.splice(0, 0, {
                        text: "Matters Refiled on\n(As on <?php echo $dateFrom; ?>)",
                        fontSize: 12,
                        alignment: "center",
                        margin: [0, 0, 0, 12]
                    });
                }
            },
            {
                extend: "print",
                title: "",
                messageTop: "<h3 style='text-align:center;'>Matters Refiled on<br>(As on <?php echo $dateFrom; ?>)</h3>"
            }
        ]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
});

  </script>