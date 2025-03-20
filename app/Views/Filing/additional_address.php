
    <table>
        <tr class="tr_<?php echo $p_r; ?><?php echo $sno; ?>">
            <td> Address:</td>
            <td>
                <input type="text"  class="form-control  cl_add_<?php echo $p_r; ?>" name="txt_address<?php echo $p_r; ?><?php echo $sno; ?>" id="txt_address<?php echo $p_r; ?><?php echo $sno; ?>" style="width: 400px" >
            </td>
            <td> Country: </td>
            <td>
                <select class="form-control" name="txt_counrty<?php echo $p_r; ?><?php echo $sno; ?>" id="txt_counrty<?php echo $p_r; ?><?php echo $sno; ?>" style="width:200px;" onchange="check_country(this.id,this.value)">
                    <?php
                    foreach ($country as $row) {?>
                        <option value="<?php echo $row['id']; ?>" <?php  if($row['id']=='96'){ echo "Selected"; }  ?>><?php echo $row['country_name']; ?></option>
                    <?php   }   ?>
                </select>
            </td>
            <td>State:</td>
            <td>
                <select class="form-control" id="txt_state<?php echo $p_r; ?><?php echo $sno; ?>" name="txt_state<?php echo $p_r; ?><?php echo $sno; ?>" style="width:204px" onchange="get_additional_dis(this.id,this.value)" ><option value="">Select</option>
                    <?php
                    $sel ='';
                    foreach ($state_list as $row) { if (isset($row['cmis_state_id'])) {
                            echo '<option value="' . sanitize(($row['cmis_state_id'])) . '">' . sanitize(strtoupper($row['agency_state'])) . '</option>';
                        } ?>
                    <?php } ?>
                </select>
            </td>
            <td> District:</td>
            <td><select class="form-control" id="txt_district<?php echo $p_r; ?><?php echo $sno; ?>" name="txt_district<?php echo $p_r; ?><?php echo $sno; ?>" style="width:203px" ><option value="">Select</option>
                    <option value="">Select</option>
                </select>
            </td>
        </tr>
    </table>

