<?= view('header'); ?>
<section class="content">
  <div class="container">
    <div id="dv_content1">
      <form method="post" action="">
        <div id="dv_content1">
          <table align="center" cellspacing="1" cellpadding="2" border="0" width="100%">
            <tr>
              <th>Incomplete Matters for <span style="color: #d73d5a"><?= session()->get('emp_name_login') ?></span>
                <?php if (isset($userTypeRow)): ?>
                  <?php if ($userTypeRow['usertype'] == 104): $ref = 1; ?>
                  <?php elseif ($userTypeRow['usertype'] == 108): $ref = 2; ?>
                  <?php elseif ($userTypeRow['usertype'] == 105 || $userTypeRow['usertype'] == 106): $cat = 1; ?>
                    <select id="type_report" onChange="get_list(this.value)">
                      <option value="">Select</option>
                      <option value="<?= session()->get('dcmis_user_idd') ?>"><?= session()->get('emp_name_login') ?></option>
                      <option value="<?= $userTypeRow['usertype'] ?>">Pending Matters of <?= $userTypeRow['usertype'] == 105 ? 'Category' : 'Tagging' ?></option>
                    </select>
                  <?php endif; ?>
                  <span style="color: #737add">[<?= $userTypeRow['type_name'] ?>]</span>
                  <div id="txtHint"></div>
                <?php else: ?>
                  <p>No record found!</p>
                <?php endif; ?>
              </th>
            </tr>
            <tr>
              <th>
                <hr>
              </th>
            </tr>
          </table>

          <div id="result">
            <table class="centerview" border="1" cellspacing="4" cellpadding="5">
              <tr style="background-color: lightgrey">
                <th>S.No.</th>
                <th>Diary No.</th>
                <?php if ( $userTypeRow != 107): ?>
                  <th>Parties</th>
                <?php endif; ?>
                <th>Dispatch By</th>
                <th>Dispatch On</th>
                <th>Remarks</th>
                <?php if ( $userTypeRow == 107): ?>
                  <th>Tentative Listing Date[Listed For]</th>
                <?php endif; ?>
                <?php if ( $userTypeRow != 108): ?>
                  <th>Receive</th>
                <?php endif; ?>
                <?php if ( $userTypeRow == 103): ?>
                  <th>Type</th>
                <?php endif; ?>
                <?php if ( $userTypeRow != 107): ?>
                  <th>Dispatch</th>
                <?php endif; ?>
                <th>eFiling View</th>
              </tr>
              <?php $sno = 1; ?>
              <?php foreach ($trapData as $row): ?>
                <tr style="<?php echo ($row['remarks'] == 'FDR -> AOR' || $row['remarks'] == 'AOR -> FDR') ? 'background-color: #cccccc' : ''; ?>">
                  <th><?php echo $sno++; ?></th>
                  <td><?php echo substr($row['diary_no'], 0, -4) . '/' . substr($row['diary_no'], -4); ?></td>
                  <?php if ( $userTypeRow != 107): ?>
                    <td><?php echo $row['pet_name'] . ' <b>V/S</b> ' . $row['res_name']; ?></td>
                  <?php endif; ?>
                  <td><?php echo $row['d_by_name']; ?></td>
                  <td><?php echo date('d-m-Y h:i:s A', strtotime($row['disp_dt'])); ?></td>
                  <td><?php echo $row['remarks']; ?></td>
                  <?php if ( $userTypeRow == 107): ?>
                    <td>
                      <?php echo $row['next_dt'] . ' [' . $row['board_type'] . ']'; ?>
                    </td>
                  <?php endif; ?>
                </tr>
              <?php endforeach; ?>
            </table>
          </div>
        </div>
</section>


<script type="text/javascript">
  function get_list(value1) {
    var str = value1;

    $.ajax({
      url: "<?php echo base_url('Filing/IncompleteNew/getMatters'); ?>",
      type: "GET",
      data: {
        q: str
      },
      success: function(response) {
        $("#txtHint").html(response);
      },
      error: function(xhr, status, error) {
        console.error("An error occurred: " + status + " - " + error);
      }
    });
  }

  function receive_file(iss) {
    var idd = iss.split('rece');
    $(this).attr('disabled', true);
    var type = 'R';
    $.ajax({
      type: 'POST',
      url: "<?php echo base_url('Filing/IncompleteNew/receive'); ?>",
      beforeSend: function(xhr) {
        $("#result1").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='../images/load.gif'></div>");
      },
      data: {
        id: idd[1],
        value: type
      },
      success: function(response) {
        if (response.status) {
          get_list($('#type_report').val());
        }
        if (response.message) {
          alert(response.message);
        }
      },
      error: function() {
        alert("ERROR, Please Contact Server Room");
      }
    });
  }


  $("[id^='tag']").click(function() {
    var tag = 'Y';
    var idd = $(this).attr('id').split('tag');
    $(this).attr('disabled', true);
    var type = 'C';

    $.ajax({
        type: 'POST',
        url: "<?php echo base_url('Filing/IncompleteNew/handleReceiveButtonClick'); ?>",
        beforeSend: function(xhr) {
          $("#result1").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='../images/load.gif'></div>");
        },
        data: {
          id: idd[1],
          value: type,
          tag: tag
        }
      })
      .done(function(msg) {
        alert(msg);
        window.location.reload();
        return;
      })
      .fail(function() {
        alert("ERROR, Please Contact Server Room");
      });
  });

  $("[id^='comp']").click(function() {
    var c = confirm("Are You Sure You Want to Dispatch");
    if (c == true) {
      var idd = $(this).attr('id').split('comp');
      $(this).attr('disabled', true);
      var type = 'C';

      $.ajax({
          type: 'POST',
          url: "<?php echo base_url('Filing/IncompleteNew/handleReceiveButtonClick'); ?>",
          beforeSend: function(xhr) {
            $("#result1").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='../images/load.gif'></div>");
          },
          data: {
            id: idd[1],
            value: type,
            nature: idd[2]
          }
        })
        .done(function(msg) {
          alert(msg);
          window.location.reload();
          return;
        })
        .fail(function() {
          alert("ERROR, Please Contact Server Room");
        });
    }
  });


  $("[id^='rece']").click(function() {
    var idd = $(this).attr('id').split('rece');
    $(this).attr('disabled', true);
    var type = 'R';

    $.ajax({
        type: 'POST',
        url: "<?php echo base_url('Filing/IncompleteNew/handleReceiveButtonClick'); ?>", 
        beforeSend: function(xhr) {
          $("#result1").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='../images/load.gif'></div>");
        },
        data: {
          id: idd[1],
          value: type
        }
      })
      .done(function(msg) {
        alert(msg);
        window.location.reload();
        return;
      })
      .fail(function() {
        alert("ERROR, Please Contact Server Room");
      });
  });


  $("[id^='tag']").click(function() {
    var tag = 'Y';
    var idd = $(this).attr('id').split('tag');
    $(this).attr('disabled', true);
    var type = 'C';

    $.ajax({
        type: 'POST',
        url: "path/to/controller/tag",
        beforeSend: function(xhr) {
          $("#result1").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='../images/load.gif'></div>");
        },
        data: {
          id: idd[1],
          value: type,
          tag: tag
        }
      })
      .done(function(msg) {
        alert(msg);
        window.location.reload();
        return;
      })
      .fail(function() {
        alert("ERROR, Please Contact Server Room");
      });
  });
</script>