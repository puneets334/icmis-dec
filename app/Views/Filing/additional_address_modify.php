
    <table id="row_additional_address_<?php echo $p_r; ?><?php echo $row1['id']; ?>">
        <tr class="tr_<?php echo $p_r; ?><?php echo $sno; ?>">
            <td> Address:</td>
            <td>
                <input type="text"  class="form-control  cl_add_<?php echo $p_r; ?>" name="txt_address<?php echo $p_r; ?><?php echo $sno; ?>" id="txt_address<?php echo $p_r; ?><?php echo $sno; ?>" value="<?php echo $row1['address']; ?>" style="width: 400px" >
            </td>
            <td> Country: </td>
            <td>
                <select class="form-control" name="txt_counrty<?php echo $p_r; ?><?php echo $sno; ?>" id="txt_counrty<?php echo $p_r; ?><?php echo $sno; ?>" style="width:200px;" onchange="check_country(this.id,this.value)">
                    <?php
                    foreach ($country as $country_row) {?>
                        <option value="<?php echo $country_row['id']; ?>" <?php if(!empty($row1['country'])) { if($country_row['id']==$row1['country']) { echo "Selected"; } } else { if($country_row['id']=='96') echo "Selected"; } ?>><?php echo $country_row['country_name']; ?></option>
                    <?php   }   ?>
                </select>
            </td>
            <td>State:</td>
            <td>
                <select class="form-control" id="txt_state<?php echo $p_r; ?><?php echo $sno; ?>" name="txt_state<?php echo $p_r; ?><?php echo $sno; ?>" style="width:204px" onchange="get_additional_dis(this.id,this.value)" <?php if($row1['country']!='96') { ?> disabled="disabled" <?php } ?>><option value="">Select</option>
                    <?php
                    foreach ($state_list as $st_row) { if (isset($st_row['cmis_state_id'])) {?>
                            <option value="<?php echo $st_row['cmis_state_id']?>" <?php if($st_row['cmis_state_id']==$row1['state']) { echo 'selected';} ?> ><?php echo $st_row['agency_state']?></option>
                    <?php } }?>
                </select>
            </td>
            <td> District:</td>
            <td><select class="form-control" id="txt_district<?php echo $p_r; ?><?php echo $sno; ?>" name="txt_district<?php echo $p_r; ?><?php echo $sno; ?>" style="width:203px" <?php if($row1['country']!='96') { ?> disabled="disabled" <?php } ?>><option value="">Select</option>
                    <option value="">Select</option>
                    <?php
                    foreach ($dist_list as $district_row) { ?>
                    <option value="<?php echo $district_row['id_no'];?>" <?php if($district_row['id_no']==$row1['district']) { echo 'selected';} ?>><?php echo $district_row['name'];?></option>
                    <?php  }?>
                    <option value="0" <?php if($row1['district']==0) { echo 'selected';}  ?>>Not Mention</option>
                </select>
            </td>
            <td>
                <input type="hidden" name="hd_main_id<?php echo $p_r; ?><?php echo $sno; ?>" id="hd_main_id<?php echo $p_r; ?><?php echo $sno; ?>" value="<?php echo $row1['id']; ?>"/>
                <span id="btn_delete<?php echo $p_r; ?><?php echo $sno; ?>" class="btn btn-danger  cl_deletes<?php echo $p_r; ?>" data-id="<?php echo $row1['id']; ?>" data-type_p_r="<?php echo $p_r; ?>" ><i class="fa fa-trash" aria-hidden="true"></i></span>
            </td>
        </tr>
    </table>

