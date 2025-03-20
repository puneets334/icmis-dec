<input type="hidden" name="ld" id="ld" value="<?php echo $list_dt1; ?>">
<div id="prnnt" style="text-align: center; font-size:10px;">
  <H3>Cause List for Dated <?php echo $list_dt; ?> (<?php echo $mainhead_descri; ?>)<br><?php echo $main_supl_head; ?> </H3>
  <?php if (!empty($res)) {
  ?>
    <table align="left" width="100%" border="0px;" style="font-size:10px; table-layout: fixed;">
      <thead>
        <tr style="background: #918788;">
          <th width="2%" style="font-weight: bold; color: #dce38d;">SrNo.</th>
          <th width="2%" style="font-weight: bold; color: #dce38d;">Court No.</th>
          <th width="2%" style="font-weight: bold; color: #dce38d;">Item No.</th>
          <th width="5%" style="font-weight: bold; color: #dce38d;">Diary No.</th>
          <th width="8%" style="font-weight: bold; color: #dce38d;">Reg No.</th>
          <th width="10%" style="font-weight: bold; color: #dce38d;">Petitioner vs Respondent</th>
          <th width="17%" style="font-weight: bold; color: #dce38d;">Advocate</th>
          <th width="3%" style="font-weight: bold; color: #dce38d;">Section Name</th>
          <th width="7%" style="font-weight: bold; color: #dce38d;">DA Name</th>
          <th width="9%" style="font-weight: bold; color: #dce38d;">Office<br> Report</th>
        </tr>
      </thead>
      <?php
      $sno = 1;

      foreach($res as $ro) {
        $remark = $ro['remark'];
        $sno1 = $sno % 2;
        $dno = $ro['diary_no'];
        $diary_no_rec_date = date('d-m-Y', strtotime($ro['diary_no_rec_date']));
        $active_fil_dt = date('d-m-Y', strtotime($ro['active_fil_dt']));
        $conn_no = $ro['conn_key'];
        $m_c = "";
        if ($conn_no == $dno) {
          $m_c = "Main";
        }
        if ($conn_no != $dno and $conn_no > 0) {
          $m_c = "Conn.";
        }
        $coram = $ro['coram'];
        if ($ro['board_type'] == "J") {
          $board_type1 = "Court";
        }
        if ($ro['board_type'] == "C") {
          $board_type1 = "Chamber";
        }
        if ($ro['board_type'] == "R") {
          $board_type1 = "Registrar";
        }
        $filno_array = explode("-", $ro['active_fil_no']);

        if (empty($ro['reg_no_display'])) {
          $fil_no_print = "Unregistred";
        } else {
          $fil_no_print = $ro['reg_no_display'];
        }
        if ($sno1 == '1') { ?>
          <tr style=" background: #ececec;" id="<?php echo $dno; ?>">
          <?php } else { ?>
          <tr style=" background: #f6e0f3;" id="<?php echo $dno; ?>">
          <?php
        }


        if ($ro['pno'] == 2) {
          $pet_name = $ro['pet_name'] . " AND ANR.";
        } else if ($ro['pno'] > 2) {
          $pet_name = $ro['pet_name'] . " AND ORS.";
        } else {
          $pet_name = $ro['pet_name'];
        }



        if ($ro['rno'] == 2) {
          $res_name = $ro['res_name'] . " AND ANR.";
        } else if ($ro['rno'] > 2) {
          $res_name = $ro['res_name'] . " AND ORS.";
        } else {
          $res_name = $ro['res_name'];
        }
        $padvname = "";
        $radvname = "";
        $impldname = "";
        $advsql = "";
        $rowadv = $model->advsql($ro["diary_no"]);
        if (!empty($rowadv)) {
          $radvname =  $rowadv["r_n"];
          $padvname =  $rowadv["p_n"];
          $impldname = $rowadv["i_n"];
        }


        if (($ro['section_name'] == null or $ro['section_name'] == '') and $ro['ref_agency_state_id'] != '' and $ro['ref_agency_state_id'] != 0) {

          if ($ro['active_reg_year'] != 0)
            $ten_reg_yr = $ro['active_reg_year'];
          else
            $ten_reg_yr = date('Y', strtotime($ro['diary_no_rec_date']));

          if ($ro['active_casetype_id'] != 0)
            $casetype_displ = $ro['active_casetype_id'];
          else if ($ro['casetype_id'] != 0)
            $casetype_displ = $ro['casetype_id'];

          $section_ten_row = $model->sectionTenRow($casetype_displ,$ten_reg_yr,$ro["ref_agency_state_id"]);
          if (!empty($section_ten_row)) {
            if ($ro['section_name'] == '') {
              $ro['section_name'] =  $ro['dno'];
            } else {
              echo $ro['section_name'] = $section_ten_row["section_name"];
          
            }
            if ($section_ten_row["dacode"] == 0)
              $ro['name'] = "no dacode";
          }
        }
          ?>
          <td align="left" style='vertical-align: top;'><?php echo $sno; ?></td>
          <td align="left" style='vertical-align: top;'><?php echo $ro['courtno'];?></td>
          <td align="left" style='vertical-align: top;'><?php echo $ro['brd_slno'] . "<br>" . $m_c; ?></td>

          <td align="left" style='vertical-align: top;'><?php echo substr_replace($ro['diary_no'], '/', -4, 0); ?></td>
          <input type='hidden' name='dno1' id='dno1' value='<?php echo $ro["diary_no"]; ?>'>
          <td align="left" style='vertical-align: top;'><?php echo $fil_no_print . "<br>Rdt " . $active_fil_dt; ?></td>
          <td align="left" style='vertical-align: top;'><?php echo $pet_name . "<br/>Vs<br/>" . $res_name; ?></td>
          <td align="left" style='vertical-align: top;'><?php echo str_replace(",", ", ", trim($padvname, ",")) . "<br/>Vs<br/>" . str_replace(",", ", ", trim($radvname, ",")) . " ", str_replace(",", ", ", trim($impldname, ",")); ?></td>
          <td align="left" style='vertical-align: top;'><?php echo $ro['section_name']; ?></td>
          <td align="left" style='vertical-align: top;'><?php echo $ro['name']; ?></td>

          <td id="fil">

            <?php
            $office_report = $model->officeReportData($ro["diary_no"],$list_dt1);
            if (!empty($office_report)) {
              $sno = 0;
              echo '<center><table>';

              foreach ($office_report as $row ) {
                $sno++;
                $res_office_report = $row['office_repot_name'];
                $res_max_o_r = $row['office_report_id'];
                if ($res_max_o_r == 0)
                  $res_max_o_r = "&nbsp;";
                $dno = $row['dno'];
            ?>

              <?php
                $d_yr = $row['d_yr'];
                $order_dt = $row['order_dt'];
                $rec_dt = $row['rec_dt'];
                $fil_nm = "../officereport/" . $d_yr . '/' . $dno . '/' . $res_office_report;
                $pos = stripos($res_office_report, '.pdf');
                if ($pos !== false) {
                  echo ' uploaded on <a href=' . $fil_nm . '>' . date('d-m-Y', strtotime($rec_dt)) . '</a>';
                } else {

                  echo ' uploaded on <a href=' . $fil_nm . '>' . date('d-m-Y', strtotime($rec_dt)) . '</a>';
                }
                if ($row['summary']) {
                  echo "<br>" . $row['summary'];
                }
              }
              echo '</table></center>';
            } else {
              ?>
              <input type="file" id="upd_file_<?php echo $dno; ?>" name="upd_file" class="abc" />
              <textarea placeholder="enter summary" class="btn-block summary" cols="8" rows="4" maxlength="500" style="width:100%; color:red;" name="summary" id="summary_<?= $dno ?>"></textarea>
              <input type="button" name="sub" id="<?php echo $dno; ?>" value='Upload' onclick='checkandupload(this)' />
              <div id="div_result"> </div>
            <?php
            }
            ?>
          </td>
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
  <div id='div_result'> </div>
  <BR /><BR /><BR /><BR /> <BR /><BR /><BR /><BR />
</div>