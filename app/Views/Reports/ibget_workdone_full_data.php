<?php
if (!empty($result)) {
?>
  <div class="table-responsive">
    <table id="customers" class="table table-striped custom-table">
      <thead>
        <tr>
          <th colspan="5">Report of Office Report prepared on <?php echo $date; ?> </th>
        </tr>

        <br><br>
        <tr>
          <th>SNo.</th>
          <th>Diary No</th>
          <th>Reg. No.</th>
          <th>Cause Title</th>
          <th>Cause List Date</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $sno = 1;
        foreach ($result as $row) {
        ?>
          <tr>
            <th><?php echo $sno; ?></th>
            <td><?php echo substr($row['diary_no'], 0, -4) . '/' . substr($row['diary_no'], -4); ?></td>

            <td> <?php echo $row['reg_no_display']; ?></td>
            <td><?php echo $row['ct']; ?></td>
            <td><?php echo $row['order_dt']; ?></td>
          </tr>
        <?php
          $sno++;
        }
        ?>
    </table>
  <?php
} else {
  ?>
    <div style="text-align: center;color: red">SORRY, NO RECORD FOUND!!!</div>
  <?php
} ?>
  </div>