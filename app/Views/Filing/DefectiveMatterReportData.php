<?php
if (!empty($result)) {
  echo "<br><br><div style='text-align: center;font-weight: bold;color: red;'>Defective Paperbook Record entered between $date1 and $date2</div><br>";
?>
  <div class="table-responsive">
    <table id="customers" class="table table-striped custom-table">
      <thead>
        <tr>
          <th>SNo.</th>
          <th>Diary No.</th>
          <th>Section</th>
          <th>Court Fees</th>
          <th>Defect Notify Date</th>
          <th>Rack No.</th>
          <th>Shelf No.</th>
          <th>Entered On</th>
          <th>Entered By</th>
          <th>Sequence No.</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $sno = 1;
        // echo mysql_num_rows($result);
        foreach ($result as $row) {
        ?>
          <tr>
            <td><?php echo $sno; ?></td>
            <td><?php echo substr($row['diary_no'], 0, -4) . '/' . substr($row['diary_no'], -4); ?></td>
            <td><?php echo $row['section_name']; ?></td>
            <td><?php echo $row['court_fees']; ?></td>
            <td><?php echo $row['defect_notify_date']; ?></td>
            <td><?php echo $row['rack_no']; ?></td>
            <td><?php echo $row['shelf_no']; ?></td>
            <td><?php echo date('d-m-Y h:i:s A', strtotime($row['ent_dt'])); ?></td>
            <td><?php echo $row['name'] ?></td>
            <td><?php echo $row['id'] ?></td>
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
?>
  <div style="color: red;font-size: 15px;text-align: center">SORRY, NO RECORD FOUND!!!</div>
<?php
}
?>
<script>
     $("#customers").DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
        "dom": 'Bfrtip',
        "bProcessing": true,
        "buttons": ["excel", "pdf"]
    });
</script>
