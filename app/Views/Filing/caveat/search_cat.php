<?php
 

    $request_category_type = $cl_rdn_supreme;
    $search_by_field = $txt_search;
    // if($request_category_type == 'N'){
    //     $search_by_field = $_REQUEST['txt_search'];
    // }else{
    //     $search_by_field = $_REQUEST['txt_search'];
    // }

    $disabled = "";
    
        
        $allowedSectionsArray = [19,12,13,14,64];
        $logged_in_usersection = $_SESSION['login']['section'];

        if (!in_array($logged_in_usersection,$allowedSectionsArray)) {
            $disabled = "disabled";
        }

        

    ?>
        <table width="100%" border="1" style="border-collapse: collapse" class="table table-striped custom-table table_tr_th_w_clr">
            <thead><tr>
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
            </tr></thead>

            <?php
            $sno = 0;
            foreach ($caveat_cat_list as $row) {

                if ($row['flag'] == 's') {
                    $class_name = "cl_supreme";
                } else {
                    $class_name = "cl_other";
                }
                //pr($row);
            ?>
                <tr>
                    <td>
                           <input type="checkbox" name="chk_sno<?php echo $sno ?>" id="chk_sno<?php echo $sno ?>" onclick="getSlide(this.id)" new_cat="<?php echo $row['mapping_id']?>" <?php echo  $disabled;?>  />
                    <input type="hidden" name="hd_subcode<?php echo $sno ?>" id="hd_subcode<?php echo $sno ?>" value="<?php echo $row['subcode1'];  ?>" />
                    <input type="hidden" name="hd_subcodes<?php echo $sno ?>" id="hd_subcodes<?php echo $sno ?>" value="<?php echo $row['subcode2'];  ?>" />
                    <input type="hidden" name="hd_subcodess<?php echo $sno ?>" id="hd_subcodess<?php echo $sno ?>" value="<?php echo $row['category_sc_old'];  ?>" />
                    <input type="hidden" name="hd_id<?php echo $sno ?>" id="hd_id<?php echo $sno ?>" value="<?php echo $row['id'];  ?>" />
                    <input type="hidden" name="hd_color<?php echo $sno; ?>" id="hd_color<?php echo $sno; ?>" value="<?php echo $row['flag'] ?>" />
                    <input type="hidden" name="mp_new_cat<?php echo $sno; ?>" id="mp_new_cat<?php echo $sno; ?>" value="<?php echo $row['mapping_id'] ?>" />

                    </td>
                    <td>
                        <span id="sp_subcategory<?php echo $sno ?>" class="<?php echo $class_name; ?>"><?php
                                                                                                        if ($row['flag'] == 's')
                                                                                                            echo $row['category_sc_old'];
                                                                                                        else
                                                                                                            echo $row['subcode1_hc'] . $row['subcode2_hc'] . $row['subcode3_hc'];
                                                                                                        ?></span>
                    </td>
                    <td>
                        <span id="sp_subject<?php echo $sno ?>" class="<?php echo $class_name; ?>"><?php

                                                                                                    echo $row['sub_name1'];
                                                                                                    ?></span>
                    </td>
                    <td>
                        <span id="sp_category<?php echo $sno ?>" class="<?php echo $class_name; ?>"><?php
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
                        <span id="sp_sub_category<?php echo $sno ?>" class="<?php echo $class_name; ?>"><?php


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
                        <span id="sp_sub_sub_category<?php echo $sno ?>" class="<?php echo $class_name; ?>"><?php


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
 