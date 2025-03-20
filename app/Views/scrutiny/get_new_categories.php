<?php
  
   /*  $sql = "SELECT id, subject_sc_old,category_sc_old, sub_name1, sub_name4 , sub_name2, sub_name3,
              subcode2, subcode3,flag,subcode2_hc,subcode1_hc,subcode3_hc, mapping_id
        FROM submaster WHERE display = 'Y' and is_old = 'N' AND sub_name4 is not null AND sub_name4 != '' ORDER BY id";
       // echo $sql;
        
    $sql = mysql_query($sql) or die("Error: " . __LINE__ . mysql_error()); */
?>
    <table width="100%" border="1" class="table_tr_th_w_clr">
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
		$results = $CaveatModel->getNewCategorySubmaster();
		if(!empty($results))
		{
        foreach($results as $row) {
           
        ?>
            <tr id="<?php echo $row['mapping_id']?>">
                <td>
                    <input type="checkbox" name="chk_sno_new<?php echo $sno ?>" id="chk_sno_new<?php echo $sno ?>" onclick="getSlideNew(this.id,1)" old_cat="<?php echo $row['mapping_id']?>" mp_id="<?php echo $row['id']?>"/>
                    <input type="hidden" name="hd_subcode_new<?php echo $sno ?>" id="hd_subcode_new<?php echo $sno ?>" value="<?php echo $row['category_sc_old'];  ?>" />
                    <input type="hidden" name="hd_subcodes_new<?php echo $sno ?>" id="hd_subcodes_new<?php echo $sno ?>" value="<?php echo $row['subcode2'];  ?>" />
                    <input type="hidden" name="hd_subcodess_new<?php echo $sno ?>" id="hd_subcodess_new<?php echo $sno ?>" value="<?php echo $row['subcode2'];  ?>" />
                    <input type="hidden" name="hd_id_new<?php echo $sno ?>" id="hd_id_new<?php echo $sno ?>" value="<?php echo $row['id'];  ?>" />
                    <input type="hidden" name="hd_color_new<?php echo $sno; ?>" id="hd_color_new<?php echo $sno; ?>" value="<?php echo $row['flag'] ?>" />
                    <input type="hidden" name="mpnidd<?php echo $sno; ?>" id="mpnidd<?php echo $sno; ?>" value="<?php echo $row['id'] ?>" />

                </td>
                <td>
                    <span id="sp_subcategory_new<?php echo $sno ?>" class="<?php if ($row['flag'] == 's') { ?>cl_text<?php
                                                                                                            } else { ?>cl_other<?php
                                                                                                                                    } ?>"><?php

                                                                                                                                                
                                                                                                                                                    echo $row['category_sc_old'];
                                                                                                                                                
                                                                                                                                                ?></span>
                </td>
                <td>
                    <span id="sp_subject_new<?php echo $sno ?>" class="<?php if ($row['flag'] == 's') { ?>cl_text<?php
                                                                                                        } else { ?>cl_other<?php
                                                                                                                                } ?>"><?php

                                                                                                                                            echo $row['sub_name1'];
                                                                                                                                            ?></span>
                </td>
                <td>
                    <span id="sp_category_new<?php echo $sno ?>" class="<?php if ($row['flag'] == 's') { ?>cl_text<?php
                                                                                                        } else { ?>cl_other<?php
                                                                                                                                } ?>"><?php
                                                                                                                                                if ($row['sub_name4'] == $row['sub_name1'])
                                                                                                                                                    echo '-';
                                                                                                                                                else {
                                                                                                                                                    echo $row['sub_name4'];
                                                                                                                                                }
                                                                                                                                                ?>
                    </span>
                </td>
                <td>
                    <span id="sp_sub_category_new<?php echo $sno ?>" class="<?php if ($row['flag'] == 's') { ?>cl_text<?php
                                                                                                            } else { ?>cl_other<?php
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
                    <span id="sp_sub_sub_category_new<?php echo $sno ?>" class="<?php if ($row['flag'] == 's') { ?>cl_text<?php
                                                                                                                } else { ?>cl_other<?php
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
		}
        ?>
    </table>
 