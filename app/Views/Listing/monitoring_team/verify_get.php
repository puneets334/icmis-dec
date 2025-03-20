<head>
  <style>
    #newb {
      position: fixed;
      padding: 12px;
      left: 50%;
      top: 50%;
      display: none;
      color: black;
      background-color: #D3D3D3;
      border: 2px solid lightslategrey;
      height: 100%;
    }

    #newcs {
      position: fixed;
      padding: 12px;
      left: 50%;
      top: 50%;
      display: none;
      color: black;
      background-color: #D3D3D3;
      border: 2px solid lightslategrey;
      height: 100%;
    }

    #overlay {
      background-color: #000;
      opacity: 0.7;
      filter: alpha(opacity=70);
      position: fixed;
      top: 0px;
      left: 0px;
      width: 100%;
      height: 100%;
    }

    #case_to_be_verify tbody td {
    color: black !important; 
}
  </style>
</head>
<div style="text-align: center">
  <H3>CASES TO BE VERIFY </H3>
  <?php
  
  if (!empty($monitoring_data) && is_array($monitoring_data)) { ?>
    <div>
      <h3><?php echo $list_print_flag . "<br>Tentative Listing Date " . $list_dt;
          if ($mainhead = 'M') {
            echo " for Misc. Hearing";
          } else {
            echo " for Regular Hearing";
          }
          if ($board_type == 'J') {
            echo " before Court";
          }
          if ($board_type == 'C') {
            echo " before Chamber";
          }
          if ($board_type == 'R') {
            echo " before Registrar";
          }
          ?>
      </h3>
    </div>
    <table align="left" width="100%" border="0px;" style="table-layout: fixed;" id="case_to_be_verify" class="table table-striped">
      <tr style="background: #918788;color:black">
        <td width="10%" style="font-weight: bold; color: #dce38d;">SNo</td>
        <td width="12%" style="font-weight: bold; color: #dce38d;">Diary/Reg No</td>
        <td width="9%" style="font-weight: bold; color: #dce38d;">ROP</td>
        <td width="15%" style="font-weight: bold; color: #dce38d;">Petitioner / Respondent</td>
        <td width="10%" style="font-weight: bold; color: #dce38d;">Advocate</td>
        <td width="10%" style="font-weight: bold; color: #dce38d;">Heading/Category</td>
        <td width="15%" style="font-weight: bold; color: #dce38d;">LastOrder / Statutory</td>
        <td width="11%" style="font-weight: bold; color: #dce38d;">IA</td>
        <td width="6%" style="font-weight: bold; color: #dce38d;">Purpose</td>
      </tr>
      <?php
      $sno = 1;
      $psrno = 1;
      foreach ($monitoring_data as $row) {
        $sno1 = $sno % 2;
        $dno = $row['diary_no'];
        $next_dt = $row['next_dt'];
        $coram = $row['coram'];
        $coram_reason = $row['list_before_remark'];
        $purpose = $row['purpose'];
        $lastorder = $row['lastorder'];
        $stagename = $row['stagename'];
        $diary_no_rec_date = "Diary Dt " . date('d-m-Y', strtotime($row['diary_no_rec_date']));
        $fil_dt = "Reg Dt " . date('d-m-Y', strtotime($row['fil_dt']));

        $ro_earlier_verify_record = $max_edat = '';
        if (isset($max_entry_dt[$dno])) {
          $result = $max_entry_dt[$dno];
          $max_edat = $result['max_edt'];
        }


        $cat_code = "";
        if (isset($cat_codes[$dno])) {
          $result = $cat_codes[$dno];
          $cat_code = $result['od_cat'];
        }

        $verify_str = $dno . "_" . $board_type . "_" . $mainhead . "_" . $next_dt;

        if ($sno1 == '1') { ?>
          <tr style=" background: #ececec;" id="<?php echo $verify_str; ?>">
          <?php } else { ?>
          <tr style=" background: #f6e0f3;" id="<?php echo $verify_str; ?>">
          <?php
        }
        if ($row['diary_no'] == $row['main_key'] or $row['main_key'] == 0 or $row['main_key'] == "") {
          // $print_brdslno = $row['brd_slno'];
          $print_srno = $psrno;
          $con_no = "0";
          $is_connected = "";
          $is_main = "";
          if ($row['diary_no'] == $row['main_key']) {
            $print_srno = $psrno;
            $con_no = "0";
            $is_connected = "";
            $is_main = "<span style='color:blue;'>Main</span><br/>";
            //$is_connected = "<span style='color:red;'>Main</span><br/>";
          }
        } else if ($row['listed'] == 1 or ($row['diary_no'] != $row['main_key'] and $row['main_key'] != null)) {
          $is_main = "";
          $is_connected = "<span style='color:red;'>Connected</span><br/>";
        }
        $m_f_filno = $row['active_fil_no'];
        $m_f_fil_yr = $row['active_reg_year'];
        $filno_array = explode("-", $m_f_filno);
        $fil_no_print = ltrim($filno_array[1], '0');
        if (count($filno_array) > 2) {
          if ($filno_array[1] == $filno_array[2]) {
            $fil_no_print = ltrim($filno_array[1], '0');
          } else {
            $fil_no_print = ltrim($filno_array[1], '0') . "-" . ltrim($filno_array[2], '0');
          }
        }


        $diary_str = substr_replace($row['diary_no'], '-', -4, 0);
        $d_str = explode("-", $diary_str);
        $comlete_fil_no_prt = "Diary No. <a data-animation=\"fade\" data-reveal-id=\"myModal\" onclick=\"call_cs('$d_str[0]','$d_str[1]','','','');\" href='#'>" . $d_str[0] . '/' . $d_str[1] . "</a><br>" . $diary_no_rec_date;
        if (!empty($fil_no_print)) {
          $comlete_fil_no_prt .= "<br>" . $row['short_description'] . "-" . $fil_no_print . "/" . $m_f_fil_yr . "<br>" . $fil_dt;
        } else {
          $comlete_fil_no_prt .= "<br>Unreg.";
        }

        $padvname = $radvname = $impldname = "";
        if (isset($advocates[$dno])) {
          $result = $advocates[$dno];
          $radvname = !empty($result["r_n"]) ? str_replace(",", ", ", trim($result["r_n"], ",")) : '';
          $padvname = !empty($result["p_n"]) ? str_replace(",", ", ", trim($result["p_n"], ",")) : '';
          $impldname = !empty($result["i_n"]) ? str_replace(",", ", ", trim($result["i_n"], ",")) : '';
        }

        if ($row['pno'] == 2) {
          $pet_name = $row['pet_name'] . " AND ANR.";
        } else if ($row['pno'] > 2) {
          $pet_name = $row['pet_name'] . " AND ORS.";
        } else {
          $pet_name = $row['pet_name'];
        }
        if ($row['rno'] == 2) {
          $res_name = $row['res_name'] . " AND ANR.";
        } else if ($row['rno'] > 2) {
          $res_name = $row['res_name'] . " AND ORS.";
        } else {
          $res_name = $row['res_name'];
        }

        if ($is_connected != '') {
          $print_srno = "";
        } else {
          $print_srno = $print_srno;
          $psrno++;
        }

          ?>
          <td align="right" style='vertical-align: top;'>
            <?php
            if (isset($case_verify[$dno])) {
              foreach ($case_verify[$dno] as $row_verif) {
                echo "<span style='color:green;'><b>" . $row_verif['rem_dtl'] . "</b> at " . date('d-m-Y H:i:s', strtotime($row_verif['ent_dt'])) . "</span><br>";
              }
            }

            if (!empty($remarks_list)) {
            ?>
              <select class="ele" name="rremark_<?php echo $row['diary_no']; ?>" id="rremark_<?php echo $row['diary_no']; ?>"
                size=3; multiple="multiple" style="width: 135px;">
                <?php
                foreach ($remarks_list as $row_rem) {
                  if ($row_rem['id'] == '1') {
                    $sel_id = "selected='selected'";
                  } else {
                    $sel_id = "";
                  }
                ?>
                  <option value="<?php echo $row_rem['id']; ?>" <?php echo $sel_id; ?>> <?php echo $row_rem['remarks']; ?></option>
              <?php
                }
              }

              ?>
              </select>
              <input type='button' class="btn btn-primary btn-sm" name='bsubmit' id='bsubmit' value=' Verify ' onClick='javascript:addRecord("<?php echo $verify_str; ?>")' />
              <br><strong><?php echo $print_srno; ?></strong>
          </td>
          <td align="left" style='vertical-align: top;'>
            <?php
            echo $is_main . $is_connected . $comlete_fil_no_prt . "<br>(" . $row['section_name'] . ") " . $row['name'] . "<br/>";
            echo "<span class='tooltip'>" . $cat_code . "<span class='tooltiptext'>Tooltip text</span></span>";
            if ($coram != 0 and $coram != '') {
              $coram_reason_desp = "";
              if ($coram_reason == 1) {
                $coram_reason_desp = "(Judicial)";
              }
              if ($coram_reason == 2) {
                $coram_reason_desp = "(ADM)";
              }
              if ($coram_reason == 3) {
                $coram_reason_desp = "(CRIME)";
              }
              if ($coram_reason == 4) {
                $coram_reason_desp = "(LCT)";
              }
              if ($coram_reason == 5) {
                $coram_reason_desp = "(COVERED)";
              }
              if ($coram_reason == 6) {
                $coram_reason_desp = "(RP/MOD)";
              }
              if ($coram_reason == 10) {
                $coram_reason_desp = "(CHALLENGED)";
              }
              if ($coram_reason == 11) {
                $coram_reason_desp = "(BY CJI)";
              }
              if ($coram_reason == 12) {
                $coram_reason_desp = "(PART HEARD)";
              }

              echo "<br/>CORAM: <span style='color:green'>" . f_get_judge_names_inshort($coram) . " " . $coram_reason_desp . "</span>";
            }
            if (isset($not_before_details[$dno])) {
              $bef_jud = $not_before_details[$dno];
              $befjuddcod = $bef_jud['bef_jud'];
              echo "<br><span style='color:green'>Before " . f_get_judge_names_inshort($befjuddcod) . " Reason : " . $bef_jud['res_add'] . "</span>";
            }
            echo $ro_earlier_verify_record;
            ?>

          </td>

          <td align="left" style='vertical-align: top;'><?php

              $resus = '';
              if (isset($get_rop_data[$dno])) {
                $resus = $get_rop_data[$dno];
              }

              if (!empty($resus)) {
                echo "<span class='tooltips'>ROP<span class='tooltiptext'>";
                foreach ($resus as $ro_rop) {
                  $rjm = explode("/", $ro_rop['pdfname']);

                  if ($rjm[0] == 'supremecourt') {
                    echo '<a href="../../jud_ord_html_pdf/' . $ro_rop['pdfname'] . '" target="_blank">' . date("d-m-Y", strtotime($ro_rop['orderdate'])) . '</a><br>';
                  } else {
                    echo '<a href="../../judgment/' . $ro_rop['pdfname'] . '" target="_blank">' . date("d-m-Y", strtotime($ro_rop['orderdate'])) . '</a><br>';
                  }
                }
                echo "</span></span>";
              }
              ?>
            </td>
          <?php
            $remark_result = "";
            if (isset($get_cl_brd_remark[$dno])) {
              $remark_result = $get_cl_brd_remark[$dno];
              $remark = $remark_result['remark'];    
            }
            
            $dispaly_name = !empty($pet_name) ? $pet_name : '';
            $dispaly_name .= !empty($pet_name) && !empty($res_name) ? "<br/>Vs<br/>" : '';
            $dispaly_name .= !empty($res_name) ? $res_name : '';

            $advocate_name = !empty($padvname) ? $padvname : '';
            $advocate_name .= !empty($padvname) && !empty($radvname) ? "<br/>Vs<br/>" : '';
            $advocate_name .= !empty($radvname) ? $radvname : '';
          ?>    
          <td align="left" style='vertical-align: top;'><?php echo $dispaly_name ?: "NA"; ?></td>
          <td align="left" style='vertical-align: top;'><?php echo $advocate_name ?: "NA"; ?></td>
          <td align="left" style='vertical-align: top;'><?php echo $row['stagename']; ?></td>
          <td align="left" style='vertical-align: top;'><?php echo "<i>" . $lastorder . "</i><br>" . $remark ?></td>
          <td align="left" style='vertical-align: top;'>
            <?php 
            if (isset($f_get_docdetail[$dno])) {
              $doc_results = $f_get_docdetail[$dno];
              foreach($doc_results as $doc){
                echo nl2br("\n". $doc['docdesc']);    
              }
            }
    
            ?>
          </td>
          <td align="left" style='vertical-align: top;'><?php echo $purpose; ?></td>
          </tr>
        <?php
        $sno++;
      }
        ?>
    </table>
  <?php
  } else {
    echo "No Records Found";
  }
  ?>

</div>

<div id="dv_fixedFor_P" style="display: none;position: fixed;top:75px;left:10% !important;width:85%;height:100%;z-index: 105;">  
  <div id="close_s" style="text-align: right;cursor: pointer;width: 40px;float: right" onclick="close_cs()"><b><img src="<?php echo base_url('images/close_btn.png');?>" style="width:30px;height:30px" /></b></div>
  <div id="newcs123" style="width: auto;background-color: white;overflow: scroll;height: 500px;margin-left: 50px;margin-right: 50px;margin-bottom: 25px;margin-top: 1px;word-wrap: break-word;">
  </div>
  </div>
  <div id="dv_res1"></div>
<script src="<?php echo base_url('listing/monitoring_team/monitoring.js'); ?>"></script>
<script>
  function addRecord(dno) {
    var r = confirm("Are you Verfied this case");
    if (r == true) {
      txt = "You pressed OK!";
      var splt_str = dno.split("_");
      var rremark = $("#rremark_" + splt_str[0]).val();
      var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
      $.ajax({
        type: "POST",
        url: '<?php echo base_url('Listing/MonitoringTeam/response_verify'); ?>',
        data: {
            dno: dno,
            rremark: rremark,
            CSRF_TOKEN:CSRF_TOKEN_VALUE
        },
        cache: false,
        success: function(data) {
          updateCSRFToken();
          if (data == 1) {
            var r = "#" + dno;
            var row = "<tr><td colspan='3' style='text-align:center;color:red !important;'>Verfied Successfully</td></tr>";
            $(r).replaceWith(row);
          } else {
            alert("Not Verified.");
          }
          //document.location.reload();
        }
      });
    } else {
      txt = "You pressed Cancel!";
    }

  }
</script>