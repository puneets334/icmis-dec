<?php if (count($result) > 0) {
?>

  <div id="td_zxx">
    <div style="text-align: center">Acknowledgement Received between <b>
        <?php echo date('d-m-Y', strtotime($from_date)); ?></b> and <b><?php echo date('d-m-Y', strtotime($todate)); ?></b></div>
    <div style="text-align: center"><b>Total Process Id: <span id="sp_ctt"></span></b></div>
    <!-- <div style="text-align: center">
      <input type="button" name="btn_print" id="btn_print" value="Print" onclick="pnt_dt('td_zxx')" />
    </div> -->
    <div class="table-responsive">
      <table id="report" class="table table-striped custom-table">
        <thead>
          <tr>
            <th width="4%">SNo.</th>

            <th width="20%">Case No.</th>
            <th width="5%">Section</th>

            <?php
            if ($ucode == '1') {
            ?>
              <th>User Id</th>
            <?php } ?>
            <th width="25%">
              Name
            </th>
            <th>
              Status/<br /> Serve Type
            </th>
            <th width="20%">
              DA Receiving Date & Sign
            </th>
          </tr>
        </thead>

        <?php
        $rw_fnm = '';
        $rec_dtt = '';
        $ack_id = '';
        $sno = 0;
        $ct_pr_id = 1;
        $send_to_name = '';
        foreach ($result as $row) {
          $ct_pr_id++;
        ?>


          <tr>
            <?php

            if ($row['diary_no'] != $rw_fnm || $row['ack_id'] != $ack_id) {
            ?>
              <td rowspan="<?php echo $row['s']; ?>"><?php echo $sno + 1 ?></td>
              <td rowspan="<?php echo $row['s']; ?>">
                <span id="sp_fl_no<?php echo $sno; ?>">
                  <?php
                  $get_case_details = get_case_details($row['diary_no']);
                  echo $get_case_details[7] . ' ' . substr($get_case_details[0], 3) . '/' . $get_case_details[1];
                  ?>
                </span>
              </td>

              <?php

              $rw_fnm = $row['diary_no'];
              $ack_id = $row['ack_id'];
              $sno++;
              ?>

            <?php
            }
            ?>
            <td>

              <?php echo $row['section']; ?>

            </td>

            <?php
            if ($ucode == '1') {
            ?>

              <td>
                <?php
                $ack_user_id = $row['ack_user_id'];
                $get_emp_details = is_data_from_table('master.users', "usercode = $ack_user_id AND display = 'Y' ", 'empid,name', 'A');
                // pr($get_emp_details);
                echo $get_emp_details[0]['empid'] . '-' . $get_emp_details[0]['name']; ?>

              </td>
            <?php } ?>
            <td>
              <div style="word-wrap:break-word;">
                <?php
                if ($row['name'] != '' && $row['copy_type'] == 0) {
                  echo $row['name'];
                }
                if ($row['name'] != '' && $row['tw_sn_to'] != 0 && $row['copy_type'] == 0) {
                  echo "<br/>Through ";
                }
                if ($row['tw_sn_to'] != 0) {
                  $send_to_name = send_to_name($row['send_to_type'], $row['tw_sn_to']);
                }
                echo $send_to_name;
                ?>
              </div>
              <div style="color: red">
                <?php
                if ($row['copy_type'] == 1) {
                  echo "Copy";
                }
                ?>
              </div>
            </td>
            <td>
              <?php
              $get_serve = get_serve_type($row['serve']);
              $get_serve_type = get_serve_type($row['ser_type']);
              echo $get_serve . ' / ' . $get_serve_type;
              ?>
            </td>
            <td>
              <?php

              if ($row['da_rec_dt'] != '') {
                echo date('d-m-Y H:i:s', strtotime($row['da_rec_dt']));
              } else {
                echo "-";
              } ?>

            </td>


          </tr>

        <?php
        }
        ?>
      </table>
    </div>
  </div>
  </div>
  <input type="hidden" name="hd_ct_pr_id" id="hd_ct_pr_id" value="<?php echo $ct_pr_id - 1; ?>" />
<?php
} else {
?>
  <div style="text-align: center"><b>No Record Found</b></div>
<?php
}
?>
</div>

<script>
  $(function() {
    $("#report").DataTable({
      "responsive": true,
      "lengthChange": false,
      "autoWidth": false,
      "bProcessing": true,
      "extend": 'colvis',
      "text": 'Show/Hide',
      "dom": 'Bfrtip',
    }).buttons().container().appendTo('#query_builder_wrapper .col-md-6:eq(0)');
  });
</script>