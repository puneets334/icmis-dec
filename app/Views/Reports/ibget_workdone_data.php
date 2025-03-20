<center> LIST OF OFFICE REPORT PREPARED BY OFFICIALS OF SECTION I-B EXTENSION </center>
<BR>
<?= csrf_field() ?>
<div class="table-responsive">
  <table id="example1" class="table table-striped custom-table">
    <thead>
      <tr>
        <th>S.No</th>
        <th>Empid</th>
        <th>Name</th>
        <th>Designation</th>
        <th width="7%">Office Report Prepared</th>
      </tr>

    </thead>
    <tbody>


      <?php
      $sno = 1;
      $total_sum = 0;
      foreach ($result as $row) {
        $empid = $row['empid'];
        $name = $row['name'];
        $type_name = $row['type_name'];
        // $total = $row['total_no'];
        $usercode = $row['usercode'];

      ?>
        <tr>
          <td><?php echo $sno; ?></td>
          <td><?php echo $empid;  ?></td>
          <td><?php echo $name; ?></td>
          <td><?php echo $type_name;  ?></td>
          <td>
            <?php
            $rs_or = $ReportModel->get_sql_or($usercode, $_REQUEST['date']);
            $rs_or = $rs_or !== null ? $rs_or : 0;

            $total_sum = $total_sum + $rs_or;
            ?>
            <?php echo "<span style='cursor:pointer' onclick='off_report_detail(\"off_$usercode\")' id='off_$usercode'>" . $rs_or . "</span>"; ?></td>

        </tr>
      <?php
        $sno = $sno + 1;
      }  // end of while loop
      ?>
      <tr>
        <td align="center" colspan="4"><B>TOTAL OFFICE REPORT PREPARED </CENTER>
        </td>
        <td><B><?php echo $total_sum; ?></td></B>
      </tr>
  </table>
  </tbody>
</div>
<div id="dv_sh_hd" style="display: none;position: fixed;top: 0;width: 100%;height: 100%;background-color: black;opacity: 0.6;left: 0;overflow: hidden;z-index: 103">
</div>
<div id="dv_fixedFor_P" style="display: none;position: fixed;top: 0;left: 0;width: 100%;height: 100%;z-index: 105">
  <!--<div id="sp_close" style="text-align: right;cursor: pointer;width: 40px;float: right" onclick="closeData()">
        <img src="close_btn.png" style="width: 30px;height: 30px;">
    </div>-->
  <div id="sar1" style="background-color: white;overflow: auto;margin: 60px 250px 30px 250px;height: 80%;">
    <div id="sp_close" style="text-align: right;cursor: pointer;float: right">
      <img src="../images/close_btn.png" style="width: 20px;height: 20px;">
    </div>
    <div id="sar" style="border: 0px solid red"></div>
  </div>
</div>


<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
 <div class="modal-dialog modal-xl" role="document">
 <div class="modal-content">
 <div class="modal-header" style="position: relative;border:1px solid #ccc;padding: 0px 12px;">
 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
 <span aria-hidden="true">&times;</span>
 </button>
 </div>
 <div class="modal-body" id="modal_id">
 
 </div>
 <div class="modal-footer">
 <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> 
 </div>
 </div>
 </div>
</div>


<script>
  $("#example1").DataTable({
    "responsive": true,
    "lengthChange": false,
    "autoWidth": false,
    "dom": 'Bfrtip',
    "bProcessing": true,
    "buttons": ["excel", "pdf"]
  });

  $(document).on("click", "#sp_close", function() {
    $('#dv_fixedFor_P').css("display", "none");
    $('#dv_sh_hd').css("display", "none");
  });

function off_report_detail(tempid)
{
  var CSRF_TOKEN = 'CSRF_TOKEN';
  var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
   // $('#dv_sh_hd').css("display", "block");
    //$('#dv_fixedFor_P').css("display", "block");
    var tempid = tempid.split('_');
    $.ajax({
        type: 'POST',
        url: "<?php echo base_url('Reports/Filing/Report/ibget_workdone_full');?>",
        beforeSend: function(xhr) {
          $("#sar").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
        },
        data: {
          date: $("#date_for").val(),
          type: tempid[0],
          id: tempid[1],
          name: $("#name_" + tempid[1]).html(),
          CSRF_TOKEN: CSRF_TOKEN_VALUE
        }
      })
      .done(function(msg_new) {
        updateCSRFToken();
        //   alert(msg_new);
        //$("#sar").html(msg_new);
        $("#modal_id").html(msg_new);
      $('#exampleModal').modal('show');
      })
      .fail(function() {
        updateCSRFToken();
        $("#sar").html("<div style='margin:0 auto;margin-top:20px;text-align:center'>ERROR, PLEASE CONTACT SERVER ROOM</div>");
      });
}

</script>