<?php
//$sno=$_REQUEST['hd_tot'];
$c_case = getNotice($_REQUEST['hd_hd_res_ca_nt'], $_REQUEST['hd_hd_sec_id'], $_REQUEST['hd_n_status'], $_REQUEST['hd_casetype_id']);
$sno = $_REQUEST['hd_tot'];
$get_states = getState();
$ck_en_nt = '';
$db = \Config\Database::connect();
$builder = $db->table('master.ref_agency_code');
$query = $builder->select('agency_name, address, cmis_state_id, district_no')
->where("CAST(agency_or_court AS INTEGER)", 1)
  ->where('is_deleted', 'f')
  ->where('state_id !=', '9999')
  ->where('main_branch', '1')
  ->orderBy('state_id')
  ->get();
// if ($chk_status == 1) {
//     $diary_no = get_diary_case_type_notice($ct, $cn, $cy);
// } else {
//     $diary_no = $d_no . $d_yr;
// }
$date = date('Y-m-d');
$sql_party =  getParties($diary_no, $date);
$row_office = (is_array($sql_party) && !empty($sql_party)) ? $sql_party[0] : '';
if ($query->getNumRows() > 0) {
  foreach ($query->getResultArray() as $row) {
    // Process each row as needed
?>
    <tr>
      <td>

        <input type="checkbox" name="chk_id<?php echo $sno; ?>" id="chk_id<?php echo $sno; ?>" style="background-color:  black" checked="checked" />

      </td>
      <td style="width: 23%;" id="td_cell_s<?php echo $sno; ?>">

        <textarea id="sp_nm<?php echo $sno; ?>" class="form-control" style="resize:none;width: 80%" onfocus="clear_data(this.id)"><?php echo "THE REGISTRAR GENERAL"; ?></textarea>
        <input type="hidden" name="hd_sr_no<?php echo $sno; ?>" class="form-control" id="hd_sr_no<?php echo $sno; ?>" value="<?php if ($ck_en_nt == '1') { echo !empty($row_office) ?  $row_office['sr_no'] : ''; } ?>" />
        <input type="hidden" name="hd_pet_res<?php echo $sno; ?>" class="form-control" id="hd_pet_res<?php echo $sno; ?>" value="<?php if ($ck_en_nt == '1') { echo !empty($row_office) ?  $row_office['pet_res'] : ''; } ?>" />
      </td>
      <td style="width: 23%;">
        <textarea id="sp_add<?php echo $sno; ?>" class="form-control" style="resize:none;width: 80%" onfocus="clear_data(this.id)"><?php echo $row['agency_name']; ?></textarea>
      </td>
      <td style="width: 9%;">
        <div>
          <?php
          $get_state_id = $db->table('master.state')
            ->select('state_code')
            ->where('id_no', $row['cmis_state_id'])
            ->where('display', 'Y')
            ->get()
            ->getRow();

          $r_get_state_id = $get_state_id ? $get_state_id->state_code : null;
          $get_state_nm = $db->table('master.state')
            ->select('id_no')
            ->where('state_code', $r_get_state_id)
            ->where('district_code', '0')
            ->where('sub_dist_code', '0')
            ->where('village_code', '0')
            ->where('display', 'Y')
            ->get()
            ->getRow();

          $r_get_state_nm = $get_state_nm ? $get_state_nm->id_no : null;
          ?>

          <select name="ddlState<?php echo $sno; ?>" class="form-control" id="ddlState<?php echo $sno; ?>" onchange="getCity(this.value, this.id, '0')" style="width: 120px" onfocus="clear_data(this.id)">
            <option value="">Select</option>
            <?php
            if ($ck_en_nt == '1') {
              foreach ($get_states as $k2) {
                $key2 = explode('^', $k2);
                if (!empty($row_office) && preg_match('/[0-9]/', $row_office['state']) && $row_office['state'] != NULL && $row_office['state'] != '') {
            ?>
                  <option value="<?php echo $key2[0]; ?>" <?php if ($key2[0] == $row_office['state']) { ?> selected="selected" <?php } ?>><?php echo $key2[1]; ?></option>
                <?php
                } else {
                ?>
                  <option value="<?php echo $key2[0]; ?>"><?php echo $key2[1]; ?></option>
                <?php
                }
              }
              if (!empty($row_office) && ($row_office['state'] == NULL || $row_office['state'] == 0) && $ck_en_nt == '1') {
                ?>
                <option value="0" selected="selected">None</option>
              <?php
              }
            } else {
              foreach ($get_states as $k2) {
                $key2 = explode('^', $k2);
              ?>
                <option value="<?php echo $key2[0]; ?>" <?php if ($r_get_state_nm == $key2[0]) { ?> selected="selected" <?php } ?>><?php echo $key2[1]; ?></option>
              <?php
              }
              ?>
              <option value="0" <?php if ($ck_en_nt == '0') { ?> selected="selected" <?php } ?>>None</option>
            <?php } ?>

          </select>
        </div>
        <div style="margin-top: 10px">
          <select name="ddlCity<?php echo $sno; ?>" class="form-control" id="ddlCity<?php echo $sno; ?>" style="width: 100%" onfocus="clear_data(this.id)">
            <option value="">Select</option>
            <?php
            {
              $get_districts = get_citys($r_get_state_nm);
              foreach ($get_districts as $k2) {
                $key2 =  explode('^', $k2);
            ?>
                <option value="<?php echo $key2[0]; ?>" <?php if ($key2[0] == $row['district_no']) { ?> selected="selected" <?php }  ?>><?php echo $key2[1]; ?></option>
              <?php
              }

              if (isset($row1) && ($row1['city'] == NULL || $row1['city'] == 0) && $ck_en_nt == '1') {
              ?>
                <option value="0" selected="selected">None</option>
            <?php
              }
            }

            ?>
          </select>
        </div>

      </td>
      <td style="width: 30%;">
        <select name="ddl_nt<?php echo $sno; ?>" class="form-control" id="ddl_nt<?php echo $sno; ?>" style="width: 100%;" onfocus="clear_data(this.id)" <?php if ($ck_en_nt == '1' && preg_match('/,/', $ck_en_nt_x['nt_type'])) {  ?> multiple="multiple" <?php } ?> onchange="get_wh_p_r(this.value,this.id)">
          <option value="">Select</option>
          <?php
          foreach ($c_case as $k) {
            $key =  explode('^', $k);
          ?>
            <option value="<?php echo $key[0]; ?>" <?php if ($key[0] == '156') { ?> selected="selected" <?php } ?>><?php echo $key[1]; ?></option>
          <?php
          }
          ?>

        </select>
      </td>
      <td style="width: 6%;">
        <input type="text" size="9" class="form-control" name="txtAmount<?php echo $sno; ?>"
          id="txtAmount<?php echo $sno; ?>"
          onkeypress="return OnlyNumbersTalwana(event,this.id)" <?php if ($ck_en_nt == '1') { ?> value="<?php echo !empty($row_office) ? $row_office['amount'] : '';  ?>" <?php } ?> />
      </td>
    </tr>
    <tr style="border: 0px;border-color: white">
      <td colspan="7" style="border: 0px;border-color: white">
        <table style="width: 100%" class="c_vertical_align tbl_border">
          <tr>
            <th style="width: 10%">
              Delivery Mode
            </th>
            <th style="width: 45%">
              Send To / State / District
            </th>
            <th style="width: 45%">
              Copy Send To / State / District
            </th>

          </tr>
          <?php
          $del_modes = '';
          $del_tw_send_to = '';
          $del_tw_copysend_to = '';
          $ex_c_st = '';
          $db = \Config\Database::connect();
          $tw_o_r_s = $db->table('tw_o_r')
              ->select('del_type');

          if (!empty($row_office)) {
              $tw_o_r_s->where('tw_org_id', $row_office['id']);
          }

          $tw_o_r_s->where('display', 'Y');

          // Execute the query and retrieve results
          $result = $tw_o_r_s->get();

          if ($result->getNumRows() > 0) {
              foreach ($result->getResultArray() as $row3) {
                  $del_modes .= ($del_modes == '') ? $row3['del_type'] : $row3['del_type'];
              }
          }
          
          // Query for del_tw_send_to
          $tw_send_to = $db->table('tw_o_r a')
              ->select('a.id, del_type, tw_sn_to, sendto_state, sendto_district, copy_type, send_to_type')
              ->join('tw_comp_not b', 'a.id = b.tw_o_r_id');
              if(!empty($row_office)){
              $tw_send_to->where('tw_org_id', $row_office['id']);
              }
              $result = $tw_send_to->where('a.display', 'Y')
              ->where('b.display', 'Y')
              ->where('copy_type', 0)
              ->get();
          
          if ($result->getNumRows() > 0) {
              foreach ($result->getResultArray() as $row4) {
                  if ($del_tw_send_to == '') {
                      $del_tw_send_to = $row4['del_type'] . '~' . $row4['tw_sn_to'] . '~' . $row4['sendto_state'] . '~' . $row4['sendto_district'] . '~' . $row4['send_to_type'];
                  } else {
                      $del_tw_send_to .= '#' . $row4['del_type'] . '~' . $row4['tw_sn_to'] . '~' . $row4['sendto_state'] . '~' . $row4['sendto_district'] . '~' . $row4['send_to_type'];
                  }
              }
          }
          
          // Query for del_tw_copysend_to
          $tw_cp_send_to = $db->table('tw_o_r a')
              ->select('a.id, del_type, tw_sn_to, sendto_state, sendto_district, copy_type, send_to_type')
              ->join('tw_comp_not b', 'a.id = b.tw_o_r_id');
              if(!empty($row_office)){
              $tw_cp_send_to->where('tw_org_id',  !empty($row_office) ? $row_office['id'] : '');
              }
              $result = $tw_cp_send_to->where('a.display', 'Y')
              ->where('b.display', 'Y')
              ->where('copy_type', 1)
              ->orderBy('id, del_type, copy_type')
              ->get();
          
          if ($result->getNumRows() > 0) {
              $main_id = '';
              foreach ($result->getResultArray() as $row5) {
                  if ($main_id != $row5['id']) {
                      if ($del_tw_copysend_to == '') {
                          $del_tw_copysend_to = $row5['del_type'] . '~' . $row5['tw_sn_to'] . '~' . $row5['sendto_state'] . '~' . $row5['sendto_district'] . '~' . $row5['send_to_type'];
                      } else {
                          $del_tw_copysend_to .= '#' . $row5['del_type'] . '~' . $row5['tw_sn_to'] . '~' . $row5['sendto_state'] . '~' . $row5['sendto_district'] . '~' . $row5['send_to_type'];
                      }
                      $main_id = $row5['id'];
                  } else {
                      if ($ex_c_st == '') {
                          $ex_c_st = $row5['del_type'] . '~' . $row5['tw_sn_to'] . '~' . $row5['sendto_state'] . '~' . $row5['sendto_district'] . '~' . $row5['send_to_type'];
                      } else {
                          $ex_c_st .= '#' . $row5['del_type'] . '~' . $row5['tw_sn_to'] . '~' . $row5['sendto_state'] . '~' . $row5['sendto_district'] . '~' . $row5['send_to_type'];
                      }
                  }
              }
          }


          for ($q = 0; $q < 2; $q++) {
            $del_mode = '';
            $id_name = '';
            $mode = '';
            $sht_nm = '';

            if ($q == 0) {
              $del_mode = 'Ordinary';
              $id_name = 'chkOrd';
              $mode = 'o';
              $sht_nm = 'O';
            } else if ($q == 1) {
              $del_mode = 'Registry';
              $id_name = 'chkReg';
              $mode = 'r';
              $sht_nm = 'R';
            } else if ($q == 2) {
              $del_mode = 'Humdust';
              $id_name = 'chkAdvHum';
              $mode = 'h';
              $sht_nm = 'H';
            } else if ($q == 3) {
              $del_mode = 'Advocate Registry';
              $id_name = 'chkAdvReg';
              $mode = 'a';
              $sht_nm = 'A';
            }
            $ck_not = '';
            if ($del_modes != '') {
              //                            echo $del_modes.'#'.strlen($del_modes);
              for ($index2 = 0; $index2 < strlen($del_modes); $index2++) {
                //                               echo $del_modes[$index2].'!'.$sht_nm;
                if ($del_modes[$index2] == $sht_nm) {
                  $ck_not = " checked='checked'";
                }
              }
            }
            $tw_send_s = '';
            $sendto_state = '';
            $sendto_district = '';
            $sendto_type = '';
            if ($del_tw_send_to != '') {
              $ex_del_tw_send_to = explode('#', $del_tw_send_to);
              for ($index3 = 0; $index3 < count($ex_del_tw_send_to); $index3++) {
                $ex_in_exp = explode('~', $ex_del_tw_send_to[$index3]);
                if ($ex_in_exp[0] == $sht_nm) {
                  $tw_send_s = $ex_in_exp[1];
                  $sendto_state = $ex_in_exp[2];
                  $sendto_district = $ex_in_exp[3];
                  $sendto_type = $ex_in_exp[4];
                }
              }
            }

            $c_tw_send_s = '';
            $c_sendto_state = '';
            $c_sendto_district = '';
            $c_sendto_type = '';
            if ($del_tw_copysend_to != '') {
              $ex_del_c_tw_send_to = explode('#', $del_tw_copysend_to);
              for ($index4 = 0; $index4 < count($ex_del_c_tw_send_to); $index4++) {
                $ex_in_exp = explode('~', $ex_del_c_tw_send_to[$index4]);
                if ($ex_in_exp[0] == $sht_nm) {
                  $c_tw_send_s = $ex_in_exp[1];
                  $c_sendto_state = $ex_in_exp[2];
                  $c_sendto_district = $ex_in_exp[3];
                  $c_sendto_type = $ex_in_exp[4];
                }
              }
            }
          ?>
            <tr style="border: 0px;border-color: white">
              <td>
                <input class="cl_del_mod<?php echo $sno; ?>" value="<?php echo $sht_nm; ?>" title="<?php echo $del_mode; ?>" type="checkbox"
                  name="<?php echo $id_name . $sno; ?>" id="<?php echo $id_name . $sno; ?>"
                  onclick="show_hd(this.id)" <?php if ($ck_en_nt == 1) echo $ck_not; ?> <?php if ($ck_en_nt == 0 && $sht_nm == 'R') { ?> checked="checked" <?php } ?> />&nbsp;
                <span id="sp_ordinary_ck<?php echo $sno; ?>"><?php echo $sht_nm; ?></span>
              </td>
              <td>
                <select class="form-control" name="ddl_send_type<?php echo $mode . $sno; ?>" id="ddl_send_type<?php echo $mode . $sno; ?>" onchange="get_send_to_type(this.id,this.value,'1','<?php echo $mode; ?>')">
                  <option value="">Select</option>
                  <option value="2" <?php if ($sendto_type == 2 && $ck_en_nt == '1') { ?> selected="selected" <?php } ?>>Other</option>
                  <option value="1" <?php if ($sendto_type == 1 && $ck_en_nt == '1') { ?> selected="selected" <?php } ?>>Advocate</option>
                  <option value="3" <?php if ($sendto_type == 3 && $ck_en_nt == '1') { ?> selected="selected" <?php } else if ($ck_en_nt == '0' && $mode == 'o') { ?> selected="selected" <?php } ?>>Court</option>
                </select>
                <select class="form-control" name="ddlSendTo_<?php echo $mode . $sno; ?>" id="ddlSendTo_<?php echo $mode . $sno; ?>" onfocus="clear_data(this.id)" onchange="get_nms(this.value,this.id)" style="width: 130px">
                  <option value="">Select</option>
                  <?php
                  if ($ck_en_nt == '1' || $ck_en_nt == '0') {
                    $s_to_d = '';
                    $sen_cp_to = send_to();
                    $get_advocates =  get_advocates($diary_no);
                    $get_lc_highcourt = get_lc_highcourt($diary_no);
                    if ($sendto_type == 2)
                      $s_to_d = $sen_cp_to;
                    else  if ($sendto_type == 1)
                      $s_to_d = $get_advocates;
                    else  if ($sendto_type == 3 || ($ck_en_nt == '0' && $mode == 'o'))
                      $s_to_d = $get_lc_highcourt;

                    foreach ($s_to_d as $k1) {
                      $key1 =  explode('^', $k1);
                      if ($ck_en_nt == '0') {

                  ?>
                        <option value="<?php echo $key1[0]; ?>" <?php if ($row_crt_adv['id'] == $key1[0]) { ?> selected="selected" <?php } ?>><?php echo $key1[1]; ?></option>
                      <?php
                      } else if ($ck_en_nt == '1') {
                      ?>
                        <option value="<?php echo $key1[0]; ?>" <?php if ($tw_send_s == $key1[0]) { ?> selected="selected" <?php } ?>><?php echo $key1[1]; ?></option>
                  <?php
                      }
                    }
                  }
                  ?>
                </select>
                <?php
                $district_code = '';
                if ($ck_en_nt == '0' && $mode == 'o') {
                    if ($row_crt_adv['ct_code'] != '3') {
                        $dist_court = $db->table('master.state')
                                        ->select('id_no')
                                        ->where('ref_code_id', $row_crt_adv['l_dist'])
                                        ->where('display', 'Y')
                                        ->get();
                        if ($dist_court->getNumRows() > 0) {
                            $district_code = $dist_court->getRow()->id_no; // Fetch the first row's id_no
                        }
                    } else {
                        $district_code = $row_crt_adv['l_dist'];
                    }
                }
                ?>
                <select class="form-control" name="ddl_sndto_state_<?php echo $mode . $sno; ?>" id="ddl_sndto_state_<?php echo $mode . $sno; ?>" style="width: 100px" onchange="getCity(this.value,this.id,'1','<?php echo $mode; ?>')">
                  <option value="">Select</option>
                  <?php

                  foreach ($get_states as $k2) {
                    $key2 =  explode('^', $k2);
                  ?>
                    <option value="<?php echo $key2[0]; ?>" <?php if ($ck_en_nt == 1) { if ($sendto_state == $key2[0]) { ?> selected="selected" <?php } } else if ($ck_en_nt == 0 && $row_crt_adv['l_state'] == $key2[0] && $mode == 'o') { ?> selected="selected" <?php } ?>><?php echo $key2[1]; ?></option>
                  <?php
                  }
                  ?>
                </select>

                <select class="form-control" name="ddl_sndto_dst_<?php echo $mode . $sno; ?>" id="ddl_sndto_dst_<?php echo $mode . $sno; ?>" style="width: 100px">
                  <option value="">Select</option>
                  <?php
                  if ($ck_en_nt == '1' || ($ck_en_nt == '0' &&  $mode == 'o')) {
                    if ($ck_en_nt == '1')
                      $get_districts = get_citys($sendto_state);
                    else  if ($ck_en_nt == '0')
                      $get_districts = get_citys($row_crt_adv['l_state']);
                    foreach ($get_districts as $k2) {
                      $key2 =  explode('^', $k2);
                  ?>
                      <option value="<?php echo $key2[0]; ?>" <?php if ($sendto_district == $key2[0] && $ck_en_nt == '1') { ?> selected="selected" <?php } else if ($ck_en_nt == '0' && $district_code == $key2[0]) { ?> selected="selected" <?php }  ?>><?php echo $key2[1]; ?></option>
                    <?php
                    }

                    if (isset($row1) && ($row1['state'] == NULL || $row1['state'] == 0) && $ck_en_nt == '1') {
                    ?>
                      <option value="0" <?php if ($sendto_district == 0) { ?> selected="selected" <?php } ?>>None</option>
                  <?php
                    }
                  }
                  ?>
                </select>
              </td>
              <td>
                <div>

                  <select class="form-control" name="ddl_send_copy_type<?php echo $mode . $sno; ?>" id="ddl_send_copy_type<?php echo $mode . $sno; ?>" onchange="get_send_to_type(this.id,this.value,'2','<?php echo $mode; ?>')">
                    <option value="">Select</option>
                    <option value="2" <?php if ($c_sendto_type == 2 && $ck_en_nt == '1') { ?> selected="selected" <?php } ?>>Other</option>
                    <option value="1" <?php if ($c_sendto_type == 1 && $ck_en_nt == '1') { ?> selected="selected" <?php } else if ($ck_en_nt == '0' && $mode == 'o') { ?> selected="selected" <?php } ?>>Advocate</option>
                    <option value="3" <?php if ($c_sendto_type == 3 && $ck_en_nt == '1') { ?> selected="selected" <?php } ?>>Court</option>
                  </select>
                  <select class="form-control" name="ddlSendCopyTo_<?php echo $mode . $sno; ?>" id="ddlSendCopyTo_<?php echo $mode . $sno; ?>" onfocus="clear_data(this.id)" style="width: 130px">
                    <option value="">Select</option>
                    <?php
                    if ($ck_en_nt == '1' || $ck_en_nt == '0') {
                      $s_to_d = '';
                      if ($c_sendto_type == 2)
                        $s_to_d = $sen_cp_to;
                      else  if ($c_sendto_type == 1 || ($ck_en_nt == '0' && $mode == 'o'))
                        $s_to_d = $get_advocates;
                      else  if ($sendto_type == 3)
                        $s_to_d = $get_lc_highcourt;
                      $tot_advocates = array();
                      $t_sno = 0;
                      foreach ($s_to_d as $k1) {
                        $key1 =  explode('^', $k1);
                        if ($ck_en_nt == '0') {
                          if ($t_sno == 0)
                            $tot_advocates[$t_sno] = $key1[0];
                    ?>
                          <option value="<?php echo $key1[0]; ?>" <?php if ($ck_en_nt == '0' && $mode == 'o' && $key1[0] == $tot_advocates[$t_sno]) {  ?> selected="selected" <?php } ?>><?php echo $key1[1]; ?></option>
                        <?php
                        } else if ($ck_en_nt == '1') {
                        ?>
                          <option value="<?php echo $key1[0]; ?>" <?php if ($ck_en_nt == 1) { if ($c_tw_send_s == $key1[0]) { ?> selected="selected" <?php } } ?>><?php echo $key1[1]; ?></option>
                    <?php
                        }
                        $t_sno++;
                      }
                    }
                    ?>
                  </select>
                  <select class="form-control" name="ddl_cpsndto_state_<?php echo $mode . $sno; ?>" id="ddl_cpsndto_state_<?php echo $mode . $sno; ?>" style="width: 100px" onchange="getCity(this.value,this.id,'2','<?php echo $mode; ?>')">
                    <option value="">Select</option>
                    <?php
                    foreach ($get_states as $k2) {
                      $key2 =  explode('^', $k2);
                    ?>
                     <option value="<?php echo $key2[0]; ?>" <?php if ($ck_en_nt == 1) { if ($c_sendto_state == $key2[0]) { ?> selected="selected" <?php } } else if ($ck_en_nt == 0 && $mode == 'o' && $key2[0] == '490506') { ?> selected="selected" <?php } ?>><?php echo $key2[1]; ?></option>
                    <?php
                    }
                    ?>
                  </select>
                  <select class="form-control" name="ddl_cpsndto_dst_<?php echo $mode . $sno; ?>" id="ddl_cpsndto_dst_<?php echo $mode . $sno; ?>" style="width: 100px">
                    <option value="">Select</option>
                    <?php
                    if ($c_sendto_district != '' || ($ck_en_nt == 0 && $mode == 'o')) {
                      if ($c_sendto_district != '')
                        $get_districts = get_citys($c_sendto_state);
                      else if ($ck_en_nt == 0)
                        $get_districts = get_citys('490506');
                      foreach ($get_districts as $k2) {
                        $key2 =  explode('^', $k2);
                    ?>
                        <option value="<?php echo $key2[0]; ?>" <?php if ($ck_en_nt == 1) { if ($c_sendto_district == $key2[0]) { ?> selected="selected" <?php } } else if ($ck_en_nt == 0 && $mode == 'o' && $key2[0] == '490611') { ?> selected="selected" <?php } ?>><?php echo $key2[1]; ?></option>
                    <?php
                      }
                    }
                    ?>
                  </select>
                  <?php $ini_val = 0; ?>
                  <div id="dv_ext_cst<?php echo $mode . $sno; ?>">
                    <?php
                    $ini_val = 0;
                    if ($ex_c_st != '') {

                      $ex_ex_c_st = explode('#', $ex_c_st);

                      for ($index4 = 0; $index4 < count($ex_ex_c_st); $index4++) {
                        $ini_val++;
                        $ex_in_exp = explode('~', $ex_ex_c_st[$index4]);
                        if ($ex_in_exp[0] == $sht_nm) {
                          $c_tw_send_s = $ex_in_exp[1];
                          $c_sendto_state = $ex_in_exp[2];
                          $c_sendto_district = $ex_in_exp[3];
                          $c_sendto_type = $ex_in_exp[4];
                    ?>
                          <div style="margin-top: 10px">

                            <select class="form-control" name="ddl_send_copy_type<?php echo $mode . $sno; ?>_<?php echo $index4; ?>" id="ddl_send_copy_type<?php echo $mode . $sno; ?>_<?php echo $index4; ?>">
                              <option value="">Select</option>
                              <option value="2" <?php if ($c_sendto_type == 2 && $ck_en_nt == '1') { ?> selected="selected" <?php } ?>>Other</option>
                              <option value="1" <?php if ($c_sendto_type == 1 && $ck_en_nt == '1') { ?> selected="selected" <?php } ?>>Advocate</option>
                            </select>
                            <select class="form-control" name="ddlSendCopyTo_<?php echo $mode . $sno; ?>_<?php echo $index4; ?>" id="ddlSendCopyTo_<?php echo $mode . $sno; ?>_<?php echo $index4; ?>" onfocus="clear_data(this.id)" style="width: 130px;">
                              <option value="">Select</option>
                              <?php
                              if ($ck_en_nt == '1') {
                                $s_to_d = '';
                                if ($c_sendto_type == 2)
                                  $s_to_d = $sen_cp_to;
                                else  if ($c_sendto_type == 1)
                                  $s_to_d = $get_advocates;

                                foreach ($s_to_d as $k1) {
                                  $key1 =  explode('^', $k1);
                              ?>
                                  <option value="<?php echo $key1[0]; ?>" <?php if ($ck_en_nt == 1) { if ($c_tw_send_s == $key1[0]) { ?> selected="selected" <?php } } ?>><?php echo $key1[1]; ?></option>
                              <?php
                                }
                              }
                              ?>
                            </select>

                            <select class="form-control" name="ddl_cpsndto_state_<?php echo $mode . $sno; ?>_<?php echo $index4; ?>" id="ddl_cpsndto_state_<?php echo $mode . $sno; ?>_<?php echo $index4; ?>" style="width: 100px" onchange="getCity(this.value,this.id,'3','r')">
                              <option value="">Select</option>
                              <?php

                              foreach ($get_states as $k2) {
                                $key2 =  explode('^', $k2);
                              ?>
                                <option value="<?php echo $key2[0]; ?>" <?php if ($ck_en_nt == 1) { if ($c_sendto_state == $key2[0]) { ?> selected="selected" <?php } } ?>><?php echo $key2[1]; ?></option>
                              <?php
                              }
                              ?>
                            </select>

                            <select class="form-control" name="ddl_cpsndto_dst_<?php echo $mode . $sno; ?>_<?php echo $index4; ?>" id="ddl_cpsndto_dst_<?php echo $mode . $sno; ?>_<?php echo $index4; ?>" style="width: 100px">
                              <option value="">Select</option>
                              <?php
                              if ($c_sendto_district != '') {
                                $get_districts = get_citys($c_sendto_state);
                                foreach ($get_districts as $k2) {
                                  $key2 =  explode('^', $k2);
                              ?>
                                  <option value="<?php echo $key2[0]; ?>" <?php if ($ck_en_nt == 1) { if ($c_sendto_district == $key2[0]) { ?> selected="selected" <?php } } ?>><?php echo $key2[1]; ?></option>
                              <?php
                                }
                              }
                              ?>
                            </select>
                          </div>
                        <?php

                        }
                      }
                    } else if ($ck_en_nt == 0 && $mode == 'o') {
                      $ini_val = 0;
                      for ($index4 = 1; $index4 < $t_sno; $index4++) {
                        $ini_val++;
                        $indexx = $index4 - 1;
                        ?>
                        <div style="margin-top: 10px">
                          <select class="form-control" name="ddl_send_copy_type<?php echo $mode . $sno; ?>_<?php echo $indexx; ?>" id="ddl_send_copy_type<?php echo $mode . $sno; ?>_<?php echo $indexx; ?>">
                            <option value="">Select</option>
                            <option value="2">Other</option>
                            <option value="1" <?php if ($mode == 'o' && $ck_en_nt == '0') { ?> selected="selected" <?php } ?>>Advocate</option>
                          </select>
                          <select class="form-control" name="ddlSendCopyTo_<?php echo $mode . $sno; ?>_<?php echo $indexx; ?>" id="ddlSendCopyTo_<?php echo $mode . $sno; ?>_<?php echo $indexx; ?>" onfocus="clear_data(this.id)" style="width: 130px;">
                            <option value="">Select</option>
                            <?php
                            if ($ck_en_nt == '0') {
                              $s_to_d = '';

                              $s_to_d = $get_advocates;
                              $chk_sno = 0;
                              foreach ($s_to_d as $k1) {
                                $key1 =  explode('^', $k1);
                                if ($chk_sno != 0)
                                  $tot_advocates[$chk_sno] = $key1[0];
                            ?>
                                <option value="<?php echo $key1[0]; ?>" <?php if ($tot_advocates[$chk_sno] == $key1[0]) { ?> selected="selected" <?php } ?>><?php echo $key1[1]; ?></option>
                            <?php
                                $chk_sno++;
                              }
                            }
                            ?>
                          </select>

                          <select class="form-control" name="ddl_cpsndto_state_<?php echo $mode . $sno; ?>_<?php echo $indexx; ?>" id="ddl_cpsndto_state_<?php echo $mode . $sno; ?>_<?php echo $indexx; ?>" style="width: 100px" onchange="getCity(this.value,this.id,'3','r')">
                            <option value="">Select</option>
                            <?php

                            foreach ($get_states as $k2) {
                              $key2 =  explode('^', $k2);
                            ?>

                              <option value="<?php echo $key2[0]; ?>" <?php if ($ck_en_nt == 0) { if ($key2[0] == '490506') { ?> selected="selected" <?php } } ?>><?php echo $key2[1]; ?></option>
                            <?php
                            }
                            ?>
                          </select>

                          <select class="form-control" name="ddl_cpsndto_dst_<?php echo $mode . $sno; ?>_<?php echo $indexx; ?>" id="ddl_cpsndto_dst_<?php echo $mode . $sno; ?>_<?php echo $indexx; ?>" style="width: 100px">
                            <option value="">Select</option>
                            <?php
                            $get_districts = get_citys('490506');
                            foreach ($get_districts as $k2) {
                              $key2 =  explode('^', $k2);
                            ?>
                              <option value="<?php echo $key2[0]; ?>" <?php if ($ck_en_nt == 0) { if ('490611' == $key2[0]) { ?> selected="selected" <?php } } ?>><?php echo $key2[1]; ?></option>
                            <?php
                            }

                            ?>
                          </select>
                        </div>
                    <?php
                      }
                    }
                    ?>
                  </div>
                  <div style="text-align: center" id="dvad_<?php echo $mode . '_' . $sno; ?>" class="cl_add_cst">Add</div>
                  <input class="form-control" type="hidden" name="hd_Sendcopyto_<?php echo $mode . $sno; ?>" id="hd_Sendcopyto_<?php echo $mode . $sno; ?>" value="<?php echo $ini_val; ?>" />
                </div>
              </td>
            </tr>
          <?php } ?>
        </table>
        <input type="hidden" class="form-control" name="hd_new_upd<?php echo $sno; ?>" id="hd_new_upd<?php echo $sno; ?>" value="<?php echo 0; ?>" />
        <input type="hidden" class="form-control" name="hd_mn_id<?php echo $sno; ?>" id="hd_mn_id<?php echo $sno; ?>" value="<?php if ($ck_en_nt == '1') { echo $row_office['id']; } ?>" />
      </td>
    </tr>
<?php
    $sno++;
  }
} ?>
<input type="hidden" name="hd_tot_hcs" id="hd_tot_hcs" value="<?php echo $sno; ?>" />