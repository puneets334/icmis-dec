<?php
if (!empty($getOrdernet)) { ?>
  <div class="table-responsive">
    <table id="rePrintData" class="table table-striped custom-table">
      <thead>
        <tr>
          <th>SrNo.</th>
          <th>Diary No.</th>
          <th>Case No.</th>
          <th>Petitioner<br />Vs<br />Respondent</th>
          <th>Bench</th>
          <th>
            <?php echo ($order_upload == 'O') ? 'Uploaded Date' : 'Order Date'; ?>
          </th>
          <th>Type</th>
          <th>Show</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $sno = 1;
        foreach ($getOrdernet as $ordernet) { ?>
          <tr>
            <td><?php echo $sno; ?></td>
            <td><?php echo substr($ordernet['diary_no'], 0, -4) . '-' .  substr($ordernet['diary_no'], -4); ?></td>
            <td><?php echo $ordernet['reg_no_display']; ?></td>
            <td><?php echo $ordernet['pet_name'] . '<br/>Vs<br/>' . $ordernet['res_name']; ?></td>
            <td>
              <?php
              // Fetch Roster Judge Names and format properly
              $rosterJudgeData = $getRosterJudge->getRosterJudge($ordernet['roster_id']);
              $jud_names = array_map(fn($row) => $row['jname'], $rosterJudgeData);
              echo implode(', ', $jud_names); // Join names with comma
              ?>
            </td>
            <td>
              <?php
              $dateField = ($order_upload == 'U') ? 'orderdate' : 'ent_dt';
              echo date('d-m-Y', strtotime($ordernet[$dateField]));
              ?>
            </td>
            <td>
              <?php
              $type = match ($ordernet['type']) {
                'J' => 'Judgement',
                'O' => 'Order',
                'FO' => 'Final Order',
                default => 'Unknown',
              };
              echo $type;
              ?>
            </td>
            <td>
              <button class="btn btn-block btn-primary" onclick="save_upload('<?php echo $ordernet['id']; ?>')">Show</button>
            </td>
          </tr>
        <?php $sno++;
        } ?>
      </tbody>
    </table>
  </div>

<?php } else { ?>
  <div style="text-align: center">No Record Found</div>
<?php } ?>

<script>
 $(document).ready(function() {
    $("#rePrintData").DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
        "dom": 'Bfrtip',
        "bProcessing": true,
        "buttons": [
            {
                extend: "excel",
                text: "Export Excel",
                pageSize: 'LEGAL',
                orientation: 'landscape',
                exportOptions: {
                    columns: ':not(:last-child)' // Exclude last column (Show)
                }
            },
            {
                extend: "pdf",
                text: "Export PDF",
                pageSize: 'LEGAL',
                orientation: 'landscape',
                exportOptions: {
                    columns: ':not(:last-child)' // Exclude last column (Show)
                },
                customize: function(doc) {
                    doc.styles.tableHeader.alignment = 'center'; // Center headers
                    doc.styles.tableBodyEven.alignment = 'center'; // Center table data
                    doc.styles.tableBodyOdd.alignment = 'center';

                    // Fix Bench Data Alignment
                    doc.content[1].table.body.forEach(function(row) {
                        row[4].alignment = 'left'; // Ensures Bench data aligns correctly
                        row[4].margin = [5, 5, 5, 5]; // Adds padding to prevent text cutting
                    });

                    // Increase font size for readability
                    doc.defaultStyle.fontSize = 10;
                    doc.styles.tableHeader.fontSize = 12;
                }
            }
        ],
        "columnDefs": [
            { "orderable": false, "targets": -1 } // Disable sorting on last column
        ]
    });
});
</script>