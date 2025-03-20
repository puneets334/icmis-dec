<input type="hidden" name="hd_diary_no" id="hd_diary_no" value="<?php echo $diary_no; ?>" />
<?php
$ucode = $_SESSION['login']['usercode'];
if (!empty($result)) 
{
  ?>
    <fieldset id="fd_md">
      <legend><b>Main Party Details</b></legend>
      <?php
          
            $result_pet = $result["pet_name"] ?? '';
            $result_res = $result["res_name"] ?? '';
            $result_dt = $result["dt"] ?? '';
            $result_pending = $result["c_status"] ?? '';
            $cicri = $result["case_grp"] ?? '';
          ?>
          <input type="hidden" name="hd_ci_cri" id="hd_ci_cri" value="<?php echo $cicri; ?>" />
          <div class="table-responsive">
            <table id="t1" class="table table-striped custom-table">
              <thead>  
                <tr>                
                      <th>
                        Petitioner Name
                      </th>
                      <th>
                        Respondent Name
                      </th>
                      <th>
                        Receiving date
                      </th>
                    
                  </tr>
                </thead>
              <tbody>
                <tr>
                  <td style="width: 15%">
                    <?php echo $result_pet; ?>
                  </td>

                  <td style="width: 15%">
                    <?php echo $result_res; ?>
                  </td>

                  <td style="width: 10%">
                    <?php echo $result_dt; ?>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
    </fieldset>
    <?php if ($result_pending == 'D') 
        { ?>
          <div style="text-align: center;color: red">
            <h3 style='text-align:center;color:red'>Matter is Disposed!!!!</h3>
          </div>
          <?php
          exit(0);
        }  
 
    $sql_res = 0;
    $sql_jk = $dModel->getObjSaveData($diary_no);
    if (!empty($sql_jk)) {
      foreach ($sql_jk as $row3) {
        if (empty($row3['rm_dt']) && $row3['status'] == '0') {
          $sql_res = 1;
        }
      }
    }
    if ($sql_res == 0) {
      echo "<div style='text-align:center;color:red'><h3 style='text-align:center;color:red'>Matter has been refiled!!!</h3></div>";
    }
    if ($sql_res == 1) 
    {
      ?>
          <fieldset id="fiOD">
            <legend><b>Default Details</b></legend>

            <span id="spAddObj" style="font-size: small;text-transform: uppercase">
              <table id="tb_nm" class="table_tr_th_w_clr c_vertical_align" cellpadding="5" cellspacing="5"
                width="100%">
                <?php

                $sno = 1;
                $cn_c = '';
                $q_w = $dModel->getObjectionDetails($diary_no);
                ?>
                <input type="hidden" name="hdChk_num_row" id="hdChk_num_row"
                  value="<?php if (!empty($q_w)) {
                            echo count($q_w);
                          } else {
                            echo '0';
                          }; ?>" />
                <?php
                if (!empty($q_w)) {

                  foreach ($q_w as $row1) {
                    if ($cn_c == '')
                      $cn_c = $row1['org_id'];
                    else
                      $cn_c = $cn_c . ',' . $row1['org_id'];
                ?>
                    <tr>

                      <td class="c_vertical_align">

                        <span id="spAddObjjjj<?php echo $sno; ?>" style="display: none">

                          <?php echo $sno; ?>
                        </span>
                        <?php echo $sno; ?>
                      </td>
                      <td>
                        <span id="spAddObj<?php echo $sno; ?>"><?php echo $row1['obj_name']; ?></span>

                        <span id="sp_hide<?php echo $sno; ?>"><br /></span>
                      </td>
                      <td>
                        <span id="spRema<?php echo $sno; ?>"><?php echo $row1['remark'] ?></span>
                      </td>
                      <td>
                        <span id="spRem_mula<?php echo $sno; ?>"><?php $ex_ui = explode(',', $row1['mul_ent']);
                              $r = '';
                              for ($index = 0; $index < count($ex_ui); $index++) {
                                if (trim($ex_ui[$index] == '')) {
                                  $r = $r . '-' . ',';
                                } else {
                                  $r = $r . $ex_ui[$index] . ',';
                                }
                              }

                              echo substr($r, 0, -1);
                              ?></span>
                      </td>
                    </tr>
                  <?php
                    $sno++;
                  }
                  ?>

                <?php
                }
                ?>

              </table>
            </span>
            <div style="text-align: center">
              <?php

              $def_notify =  $dModel->def_notify($diary_no);
              if (!empty($def_notify)) {
                foreach ($def_notify as $result) {
                  $def_rm_date = $result['rm_dt'];
                }
              }
              if ($def_rm_date == '') {
                if ($ucode == 1  || $ucode == 1504 || $ucode == 94) {
              ?>

                  <span style="color: red">Back Date</span><input type="date" name="back_dt" id="back_dt" />
                  <input type="button" name="btn_backdate" id="btn_backdate" value="Save" />

              <?php }
              } ?>
              <div id="sp_sms_status" style="text-align: center"></div>
            </div>
          </fieldset> 
   
        <?php

    }

?>


<?php
  } else {
?>
  <div style="text-align: center">
    <h3>Diary No. Not Found</h3>
  </div>
<?php
  } die; ?>


</div>

<script>
  $(document).ready(function() {
    $(document).on('click', '#btn_backdate', function() {
      var hd_diary_no = $('#hd_diary_no').val();
      var back_date = $('#back_dt').val();
      if (back_date == '') {
        alert('Please enter date');
        $('#back_dt').focus();
        return;
      }

      var currentDate = new Date();
      var dateToCompare = new Date(back_date);
      if (dateToCompare > currentDate) {
        alert("Date cannot be greater than Today's Date ");
        $('#back_dt').focus();
        return;
      }
      var r = confirm("Are you sure to refile on back date?");
      if (r == false) {
        exit();
      }
      $.ajax({
        url: 'save_back_date.php',
        cache: false,
        async: true,
        data: {
          d_no: hd_diary_no,
          back_date: back_date
        },
        beforeSend: function() {
          $('#sp_sms_status').html('<table widht="100%" align="center"><tr><td><img src="../images/load.gif"/></td></tr></table>');
        },
        type: 'POST',
        success: function(data, status) {

          $('#sp_sms_status').html(data);
          $('#btn_backdate').attr("disabled", true);

        },
        error: function(xhr) {
          alert("Error: " + xhr.status + " " + xhr.statusText);
        }

      });
    });
  });
</script>