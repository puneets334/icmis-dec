<div class="table-responsive">
  <table id="report" class="table table-striped custom-table">
    <thead>
      <tr>
        <th>S.No</th>
        <th>
          Dispatch Id/<br />
          Dispatch Date
        </th>
        <th>
          Delivery Type
        </th>
        <th>
          Process Id
        </th>
        <th>
          Case No.
        </th>
        <?php
        if ($ucode == '1') {
        ?>
          <th>
            User Id
          </th>
        <?php
        }
        ?>
        <th>
          Name
        </th>

        <th>
          Notice Type
        </th>
        <th>
          State / District
        </th>
        <th>
          Station
        </th>
        <?php
        if ($_REQUEST['ddlOR'] != 'A') {
        ?>
          <th>
            Weight
          </th>
        <?php } ?>
        <th>
          Stamp
        </th>
        <?php
        if ($_REQUEST['ddlOR'] == 'R' || $_REQUEST['ddlOR'] == '' || $_REQUEST['ddlOR'] == 'Z') {
        ?>
          <th>
            Barcode
          </th>
        <?php
        }
        ?>
        <th>
          Remark
        </th>
      </tr>
    </thead>
    <tbody>

      <?php
      $sno = 1;
      $get_dis_id = '';
      $tot_st = 0;
      if (count($result) > 0) {
        foreach ($result as $row) {
      ?>
          <tr>
            <?php if ($row['dispatch_id'] != $get_dis_id) { ?>
              <td rowspan="<?php echo $row['s'] ?>">
                <?php echo $sno; ?>
              </td>

              <td rowspan="<?php echo $row['s'] ?>">
                <b><?php
                    echo $row['dispatch_id']; ?></b>/<br /><span style="color: blue"><?php echo date('d-m-Y H:i:s',  strtotime($row['dispatch_dt'])) ?></span>

              </td>

              <td rowspan="<?php echo $row['s'] ?>">
                <?php
                if ($row['del_type'] == 'O')
                  echo "Ordinary";
                else if ($row['del_type'] == 'R')
                  echo "Registry";
                else if ($row['del_type'] == 'A')
                  echo "Humdust";
                else if ($row['del_type'] == 'Z')
                  echo "Adv. Registry";
                ?>
              </td>
            <?php } ?>
            <td>
              <?php
              echo $row['process_id']; ?>/<?php echo $row['rec_dt']; ?>

            </td>
            <td>
              <?php
              $get_case_details = get_case_details($row['diary_no']);
              echo $get_case_details[7] . ' ' . substr($get_case_details[0], 3) . '/' . $get_case_details[1];
              ?>
            </td>
            <?php
            if ($ucode == '1') {
            ?>
              <?php if ($row['dispatch_id'] != $get_dis_id) { ?>
                <td rowspan="<?php echo $row['s'] ?>">
                  <?php
                  $get_emp_details = is_data_from_table('master.users', "usercode = '$row[dispatch_user_id]' and display='Y'", "empid,name",'');
                 if(!empty($get_emp_details))
                 {  
                     echo $get_emp_details['empid'] . '-' . $get_emp_details['name']; 
                 } 
                  
                  ?>
                </td>
            <?php
              }
            }
            ?>
            <td>
              <div style="word-wrap:break-word;width: 90px">
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

                echo $send_to_name ?? '';
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
              <?php echo $row['nt_typ']; ?>
            </td>
            <td>
              <?php
              if ($row['tw_sn_to'] == 0) {
                $get_district = get_district($row['tal_state']);
                $get_state = get_state($row['tal_district']);
              } else {
                $get_district = get_district($row['sendto_district']);
                $get_state = get_state($row['sendto_state']);
              }
              echo $get_district; ?>/<br /><?php echo $get_state;
                                          ?>
            </td>
            <?php if ($row['dispatch_id'] != $get_dis_id) { ?>
              <!--         <td rowspan="<?php echo $row['s'] ?>">-->
              <td>
                <?php
                $get_tehsil = get_state($row['station']);
                echo $get_tehsil; ?>
              </td>
              <?php if ($_REQUEST['ddlOR'] != 'A') { ?>
                <td rowspan="<?php echo $row['s'] ?>">
                  <?php echo $row['weight']; ?>
                </td>
              <?php } ?>
              <td rowspan="<?php echo $row['s'] ?>">
                <?php echo $row['stamp'];
                $tot_st = $tot_st + $row['stamp'];
                ?>
              </td>
            <?php
              $sno++;
              $get_dis_id = $row['dispatch_id'];
            }

            if ($_REQUEST['ddlOR'] == 'R' || $_REQUEST['ddlOR'] == '' || $_REQUEST['ddlOR'] == 'Z') {

            ?>
              <td>
                <?php echo $row['barcode']; ?>
              </td>
            <?php } ?>
            <td>
              <?php echo $row['dis_remark']; ?>
            </td>
          </tr>
        <?php
       
        }
        ?>
        <tr>
          <td <?php if ($ucode == '1') {
                if ($_REQUEST['ddlOR'] == 'A') { ?>colspan="11" <?php } else { ?> colspan="11" <?php }
                                                                                          } else {
                                                                                            if ($_REQUEST['ddlOR'] == 'A') { ?>colspan="10" <?php } else { ?> colspan="10" <?php }
                                                                                                                                                                                          } ?>>
            Total
          </td>
          <td>
            <b><?php echo $tot_st; ?></b>
          </td>
          <td></td>
        </tr>
      <?php
      } else {
      ?>
        <tr>
          <td colspan="13" style="text-align: center">
            <div style="text-align: center"><b>No Record Found</b></div>
          </td>
        </tr>
      <?php
      }
      ?>
    </tbody>
  </table>
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
      "dom": 'Bfrtip', // Enables the Buttons extension
      "buttons": [{
        extend: 'print',
        text: 'Print',
        title: 'Report', // Change title in print view
        autoPrint: true, // Automatically trigger print dialog
        exportOptions: {
          columns: ':visible' // Only print visible columns
        }
      }]
    }).buttons().container().appendTo('#query_builder_wrapper .col-md-6:eq(0)');
  });
</script>