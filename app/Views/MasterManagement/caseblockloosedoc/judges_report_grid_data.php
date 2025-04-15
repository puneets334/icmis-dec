<?php
if (isset($result_array) && is_array($result_array) && count($result_array) > 0) {
  //  pr($result_array);
?><div class="table-responsive">
    <table id="reportTable1" class="table table-striped custom-table">
      <thead>
        <tr>
          <th>Jcode</th>
          <th>Jname</th>
          <th>Title</th>
          <th>Abbreviation</th>
          <th>Appointment Date</th>
          <th>TO DATE</th>
          <th>CJI DATE</th>
          <th>Jtype</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $i = 0;
        foreach ($result_array as $result) {
          $i++;
        ?>
          <tr>
            <td><?php echo $result->jcode; ?></td>
            <td><?php echo $result->jname; ?></td>
            <td><?php echo $result->title; ?></td>
            <td><?php echo $result->abbreviation; ?></td>
            <td><?php
                if ($result->appointment_date == '0000-00-00' || $result->appointment_date === null) {
                  echo "";
                } else {
                  $newformat = date('d-m-Y', strtotime($result->appointment_date));
                  echo $newformat;
                } ?></td>
            <td><?php
                if ($result->to_dt == '0000-00-00' || $result->to_dt === null) {
                  echo "";
                } else {
                  $newformat = date('d-m-Y', strtotime($result->to_dt));
                  echo $newformat;
                } ?></td>
            <td><?php
                if ($result->cji_date == '0000-00-00' || $result->cji_date === null) {
                  echo "";
                } else {
                  $date = $result->cji_date;
                  if ($date && strtotime($date) !== false) {
                    $newformat = date('d-m-Y', strtotime($date));
                    echo $newformat;
                  } else {
                    echo "";
                  }
                } ?></td>
            <td><?php echo $result->jtype; ?></td>
          </tr>
        <?php
        }
        ?>
      </tbody>

    </table>
  </div>
  </div>
<?php
} else if (isset($result_array)) {
?>
  <div class="alert alert-success" style="text-align: center;">
    <strong>No Record Found!</strong>
  </div>

<?php
}
?>
<script>
  $('#reportTable1').DataTable({
    "destroy": true,
    "bProcessing": true,
    "pageLength": 25,
    dom: 'Bfrtip',
    "buttons": [{
        extend: "copy",
        title: "List of Judges/Registrars",
        filename: "List of JudgesRegistrars"
      },
      {
        extend: "csv",
        title: "List of Judges/Registrars",
        filename: "List of JudgesRegistrars"
      },
      {
        extend: "excel",
        title: "List of Judges/Registrars",
        filename: "List of JudgesRegistrars"
      },
      {
        extend: "pdfHtml5",
        title: "List of Judges/Registrars",
        filename: "List of JudgesRegistrars",
        customize: function(doc) {
          doc.content.splice(0, 0, {
            text: "List of Judges/Registrars",
            fontSize: 12,
            alignment: "center",
            margin: [0, 0, 0, 12]
          });
        }
      },
      {
        extend: "print",
        title: "",
        messageTop: "<h5 style='text-align:center;'>List of Judges/Registrars</h5>"
      }
    ]
  });
</script>