<?php
if (!empty($result)) {
?>

  <style>
    .efiling_no:link,
    .efiling_no:visited {
      background-color: #c4d9ff;
      color: black;
      border: 2px solid green;
      padding: 5px 5px;
      text-align: center;
      text-decoration: none;
      display: inline-block;
    }

    .efiling_no:hover,
    .efiling_no:active {
      background-color: green;
      color: white;
    }
  </style>
  <div id="query_builder_wrapper" class="dataTables_wrapper dt-bootstrap4">
  <table id="mainbtl" class="table table-striped custom-table">
    <?php
    if ($cat == 1) {
    ?>
      <tr>
        <td colspan="11" style="text-align: center">Report of Completed Matters for <span style="color: #d73d5a"><?php echo $_SESSION['login']['name']; ?></span>
          <span style="color: #737add">[Category]</span> Between <?php echo $_REQUEST['from'] . ' & ' . $_REQUEST['to'] . ' (' . $_REQUEST['rtypetext'] . ')'; ?>
        </td>
      </tr>
    <?php
    }
    ?>
    <thead>
    <tr>
      <th>SNo.</th>
      <th>Diary No.</th>
      <th>Parties</th>
      <th class="notfor-print">Dispatch By</th>
      <th class="notfor-print">Dispatch On</th>
      <th class="notfor-print">Remarks</th>
      <th class="notfor-print">Receive On</th>
      <th>Completed On</th>

      <?php
      if ($cat == 1) {
      ?>
        <th>Action</th>
        <th>Category</th>
        <th>N.B./B./C.</th>
      <?php
      }
      ?>
      <th>eFiling View</th>
    </tr>
    </thead>
   <tbody>
    <?php
    $sno = 1;
    foreach ($result as $row) {
    ?>
      <tr>
        <td><?php echo $sno; ?></td>
        <td><?php echo substr($row['diary_no'], 0, -4) . '/' . substr($row['diary_no'], -4); ?></td>
        <td><?php echo $row['pet_name'] . ' <b>V/S</b> ' . $row['res_name'] ?></td>
        <td class="notfor-print"><?php echo $row['d_by_name']; ?></td>
        <td class="notfor-print"><?php echo date('d-m-Y h:i:s A', strtotime($row['disp_dt'])); ?></td>
        <td class="notfor-print"><?php echo $row['remarks']; ?></td>
        <td class="notfor-print"><?php
                                  if ($fil == 1)
                                    echo " - ";
                                  else {
                                    if (!empty($row['rece_dt']))
                                      echo date('d-m-Y h:i:s A', strtotime($row['rece_dt']));
                                  }
                                  ?></td>
        <td><?php
            if ($fil == 1) {
              if (!empty($row['disp_dt']))
                echo date('d-m-Y h:i:s A', strtotime($row['disp_dt']));
            } else {
              if (!empty($row['comp_dt']))
                echo date('d-m-Y h:i:s A', strtotime($row['comp_dt']));
            }
            if (($ref == 1 || $cat == 1) && ($_REQUEST['type_rep'] == 'C')) {
              echo '<br> ' . $row['o_name'];
            }
            ?></td>
        <?php 
        if ($cat == 1) {
        ?>
          <td>
            <?php
            $rs = $model->sql_action_data($row['diary_no']);
            $rem = trim($rs['remarks']);

            if ($rem == 'AUTO -> CAT' || $rem == 'CAT -> SCN' || $rem == 'REE -> CAT' || $rem == 'SCR -> CAT') {
              echo "Accepted";
            } else {
              echo "Tagging";
            }
            ?></td>
          <td><?php echo $row['cat_name']; ?></td>
          <td><?php
              if ($row['beforejudgegrp'] != NULL)
                echo '<span style=color:green>BEFORE(special)-</span> ' . $row['beforejudgegrp'];

              if ($row['beforejudgegrp'] != NULL && $row['notbeforejudgegrp'] != NULL)
                echo "<br>";

              if ($row['notbeforejudgegrp'] != NULL)
                echo '<span style=color:red>NOT BEFORE- </span>' . $row['notbeforejudgegrp'];

              if (($row['beforejudgegrp'] != NULL || $row['notbeforejudgegrp'] != NULL) && $row['coramjudges'] != NULL)
                echo "<br>";

              if ($row['coramjudges'] != NULL)
                echo '<span style=color:#5d9c0a>BEFORE CORAM- </span>' . $row['coramjudges'];

              ?></td>
        <?php
        }
        ?>
        <td>
          <?php if ($row['efiling_no'] != '') { ?>
            <a class="btn ui-button-text-icon-primary " style="background-color: #555555;color: #fff;cursor:pointer;font-size: large;" href="<?php echo E_FILING_URL ?>/efiling_search/DefaultController/?efiling_number=<?= $row['efiling_no'] ?>" target="_BLANK" >View</a>
          <!--  <button class="btn ui-button-text-icon-primary " style="background-color: #555555;color: #fff;cursor:pointer;font-size: large;" onclick="efiling_number('<?= $row['efiling_no'] ?>')">View</button> -->
          <?php } ?>
        </td>
      </tr>
    <?php
      $sno++;
    }
    if ($cat == 1) {
    ?>
      <tr>
        <td colspan="11"><input type="button" value="Print" onclick="printfun()" class="notfor-print" /></td>
      </tr>
    <?php
    }
    ?>
    </tbody>
  </table>
  </div>
<?php
} else {
?>
  <div class="nofound">SORRY NO RECORD FOUND</div>
<?php
}

?>
<script>
        $(function () {
            $("#mainbtl").DataTable({
                "responsive": true, "lengthChange": false, "autoWidth": false,
                "buttons": ["copy", "csv", "excel",{extend: 'pdfHtml5',orientation: 'landscape',pageSize: 'LEGAL' },
                    { extend: 'colvis',text: 'Show/Hide'}],"bProcessing": true,"extend": 'colvis',"text": 'Show/Hide'
            }).buttons().container().appendTo('#query_builder_wrapper .col-md-6:eq(0)');

        });

    </script>