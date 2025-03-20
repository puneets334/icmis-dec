<?php
 
    $disabled = "";
    /* $sql = "SELECT id, subject_sc_old,category_sc_old, sub_name1, sub_name4 , sub_name2, sub_name3,mapping_id,
              subcode2, subcode3,flag,subcode2_hc,subcode1_hc,subcode3_hc
        FROM submaster WHERE display = 'Y' and flag='s'  and (is_old is null OR is_old = 'Y') ORDER BY id";
       // echo $sql;
        
    $sql = mysql_query($sql) or die("Error: " . __LINE__ . mysql_error()); */

	

    /* $sql2= "select mapping_id, id from submaster where mapping_id is not null";
    $result_set = mysql_query($sql2) or die("Error: " . __LINE__ . mysql_error()); */
    $result_set = $CaveatModel->getSubMasterForMapping_id();
    foreach ($result_set as $row_set) {
        $Old_categories[$row_set['mapping_id']] =  $row_set['id'];
    }


    $allowedSectionsArray = [19,12,13,14,64];
    $logged_in_usersection = $_SESSION['dcmis_section'] ?? '';
    
    if (!in_array($logged_in_usersection,$allowedSectionsArray)) {
        $disabled = "disabled";
    }

?>
    <table width="100%" border="1" class="table_tr_th_w_clr" disabled= "disabled">
        <tr>
            <th>
                Check
            </th>
            <th>
                Category Code
            </th>
            <th>
                Main Category
            </th>
            <th>
                Sub Category 1
            </th>
            <th>
                Sub Category 2
            </th>
            <th>
                Sub Category 3
            </th>
        </tr>

        <?php
        $sno = 0;
		$sql = $CaveatModel->getSubMasterByFlag();
        foreach($sql as $row) {
        ?>
            <tr> 
                <td>
                    <input type="checkbox" name="chk_sno<?php echo $sno ?>" id="chk_sno<?php echo $sno ?>" onclick="getSlide(this.id)" new_cat="<?php echo $row['mapping_id']?>" <?php echo  $disabled;?>  />
                    <input type="hidden" name="hd_subcode<?php echo $sno ?>" id="hd_subcode<?php echo $sno ?>" value="<?php echo $row['subcode'] ?? '';  ?>" />
                    <input type="hidden" name="hd_subcodes<?php echo $sno ?>" id="hd_subcodes<?php echo $sno ?>" value="<?php echo $row['subcode1'] ?? '';  ?>" />
                    <input type="hidden" name="hd_subcodess<?php echo $sno ?>" id="hd_subcodess<?php echo $sno ?>" value="<?php echo $row['category_sc_old'] ?? '';  ?>" />
                    <input type="hidden" name="hd_id<?php echo $sno ?>" id="hd_id<?php echo $sno ?>" value="<?php echo $row['id'];  ?>" />
                    <input type="hidden" name="hd_color<?php echo $sno; ?>" id="hd_color<?php echo $sno; ?>" value="<?php echo $row['flag'] ?? '' ?>" />
                    <input type="hidden" name="mp_new_cat<?php echo $sno; ?>" id="mp_new_cat<?php echo $sno; ?>" value="<?php echo $row['mapping_id'] ?? '' ?>" />

                </td>
                <td>
                    <span id="sp_subcategory<?php echo $sno ?>" class="<?php if ($row['flag'] == 's') { echo 'cl_supreme';
                                                                                                            } else { echo 'cl_other';
                                                                                                                                    } ?>"><?php
                                                                                                                                                    echo  $row['category_sc_old'];
                                                                                                                                                ?></span>
                </td>
                <td>
                    <span id="sp_subject<?php echo $sno ?>" class="<?php if ($row['flag'] == 's') { echo 'cl_supreme';
                                                                                                            } else { echo 'cl_other';
                                                                                                                                    } ?>"><?php

                                                                                                                                            echo $row['sub_name1'];
                                                                                                                                            ?></span>
                </td>
                <td>
                    <span id="sp_category<?php echo $sno ?>" class="<?php if ($row['flag'] == 's') { echo 'cl_supreme';
                                                                                                            } else { echo 'cl_other';
                                                                                                                                    } ?>"><?php
                                                                                                                                                if ($row['sub_name4'] == $row['sub_name1'])
                                                                                                                                                    echo '-';
                                                                                                                                                else {
                                                                                                                                                    if ($row['sub_name2'] != '')
                                                                                                                                                        echo $row['sub_name2'];
                                                                                                                                                    else if ($row['sub_name3'] != '')
                                                                                                                                                        echo $row['sub_name3'];
                                                                                                                                                    else if ($row['sub_name4'] != '')
                                                                                                                                                        echo $row['sub_name4'];
                                                                                                                                                    else
                                                                                                                                                        echo '-';
                                                                                                                                                }
                                                                                                                                                ?>
                    </span>
                </td>
                <td>
                    <span id="sp_sub_category<?php echo $sno ?>" class="<?php if ($row['flag'] == 's') { echo 'cl_supreme';
                                                                                                            } else { echo 'cl_other';
                                                                                                                                    } ?>"><?php


                                                                                                                                                    if ($row['sub_name3'] == '' && $row['sub_name2'] != '')
                                                                                                                                                        echo $row['sub_name4'];
                                                                                                                                                    else if ($row['sub_name3'] != '' && $row['sub_name2'] != '')
                                                                                                                                                        echo $row['sub_name3'];
                                                                                                                                                    else
                                                                                                                                                        echo '-';

                                                                                                                                                    ?>
                    </span>
                </td>

                <td>
                    <span id="sp_sub_sub_category<?php echo $sno ?>" class="<?php if ($row['flag'] == 's') { echo 'cl_supreme';
                                                                                                            } else { echo 'cl_other';
                                                                                                                                    } ?>"><?php


                                                                                                                                                        if ($row['sub_name4'] != '' && $row['sub_name4'] != '' && $row['sub_name3'] != '' && $row['sub_name2'] != '')
                                                                                                                                                            echo $row['sub_name4'];
                                                                                                                                                        else
                                                                                                                                                            echo '-';

                                                                                                                                                        ?>
                    </span>
                </td>


            </tr>
        <?php
            $sno++;
        }
        ?>
    </table>
 