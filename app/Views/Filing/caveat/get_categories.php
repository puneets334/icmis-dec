<?php
//include("../includes/db_inc.php");
?>
<div style="width: 100%;max-height: 200px;overflow: auto;margin-top: 20px">
    <table id="tb_new" border="1" style="border-collapse: collapse;padding-top: 0;width: 100%;" class="rgv table_tr_th_w_clr">
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
        $exist_cat = '';
        /* $sql_po =  mysql_query("Select submaster_id ,od_cat,subject_description,category_description,subject_sc_old,category_sc_old ,
        sub_name1, sub_name4 , sub_name2, sub_name3,flag,subcode2_hc,subcode1_hc,subcode3_hc,mapping_id from mul_category a join submaster b on a.submaster_id=b.id 
        where diary_no = '$_REQUEST[hd_diary_nos]' and a.display='Y' and b.display='Y'  and (b.is_old is null OR is_old = 'Y')")
            or die("Error: " . __LINE__ . mysql_error()); */
		 
		$sql_po = $CaveatModel->getMulCategoryByDiaryNo($hd_diary_nos);	
		 
        $snos = 1;
        foreach ($sql_po as $row1) 
		{
        ?>
            <tr id="tr_uo<?php echo $snos; ?>">
                <td>
                    <input type="checkbox" name="hd_chk_add<?php echo $snos; ?>" id="hd_chk_add<?php echo $snos; ?>" onclick="getDone_upd_cat(this.id);" checked="true" mpid="<?php echo $row1['mapping_id'];?>" />
                    <input type="hidden" name="hd_color<?php echo $snos; ?>" id="hd_color<?php echo $snos; ?>" value="<?php echo $row1['flag'] ?>" />
                </td>
                <td>
                    <span class="<?php if ($row1['flag'] == 's') { echo 'cl_supreme';
                                                                            } else { echo 'cl_other'; } ?>">
                        <?php
                        if ($row1['flag'] == 's')
                            echo $row1['category_sc_old'];
                        else
                            echo $row1['subcode1_hc'] . $row1['subcode2_hc'] . $row1['subcode3_hc'];
                        ?>
                        <input id="hd_sp_c<?php echo $snos; ?>" type="hidden" value="<?php  ?>" />
                        <input id="hd_sp_d<?php echo $snos; ?>" type="hidden" value="<?php echo $row1['submaster_id']  ?>" /></span>
                </td>
                <td>
                    <span class="<?php if ($row1['flag'] == 's') { ?>cl_supreme<?php } else { ?>cl_other<?php } ?>"><?php echo $row1['sub_name1']; ?></span>
                    <input id="hd_sp_a<?php echo $snos; ?>" type="hidden" value="<?php echo $row1['subcode'] ?? '' ?>" />
                </td>
                <td>
                    <span class="<?php if ($row1['flag'] == 's') { ?>cl_supreme<?php } else { ?>cl_other<?php } ?>"><?php
                                                                                                                    if ($row1['sub_name4'] == $row1['sub_name1'])
                                                                                                                        echo '-';
                                                                                                                    else {
                                                                                                                        if ($row1['sub_name2'] != '')
                                                                                                                            echo $row1['sub_name2'];
                                                                                                                        else if ($row1['sub_name3'] != '')
                                                                                                                            echo $row1['sub_name3'];
                                                                                                                        else if ($row1['sub_name4'] != '')
                                                                                                                            echo $row1['sub_name4'];
                                                                                                                        else
                                                                                                                            echo '-';
                                                                                                                    }
                                                                                                                    ?></span>
                    <input id="hd_sp_b<?php echo $snos; ?>" type="hidden" value="  <?php echo $row1['subcode1'] ?? '' ?>" />
                </td>
                <td>
                    <span class="<?php if ($row1['flag'] == 's') { ?>cl_supreme<?php } else { ?>cl_other<?php } ?>"><?php

                                                                                                                    if ($row1['sub_name3'] == '' && $row1['sub_name2'] != '')
                                                                                                                        echo $row1['sub_name4'];
                                                                                                                    else if ($row1['sub_name3'] != '' && $row1['sub_name2'] != '')
                                                                                                                        echo $row1['sub_name3'];
                                                                                                                    else
                                                                                                                        echo '-';
                                                                                                                    ?></span>
                </td>
                <td>
                    <span class="<?php if ($row1['flag'] == 's') { ?>cl_supreme<?php } else { ?>cl_other<?php } ?>"><?php
                                                                                                                    if ($row1['sub_name4'] != '' && $row1['sub_name4'] != '' && $row1['sub_name3'] != '' && $row1['sub_name2'] != '')
                                                                                                                        echo $row1['sub_name4'];
                                                                                                                    else
                                                                                                                        echo '-';
                                                                                                                    ?></span>
                </td>
            </tr>
        <?php
            $exist_cat = $row1['submaster_id'];
            $snos++;
        }
        ?>
        <input type="hidden" name="hd_ssno" id="hd_ssno" value="<?php echo $snos - 1; ?>" />
        <input type="hidden" name="hd_co_tot" id="hd_co_tot" value="<?php echo $snos - 1; ?>" />
    </table>
</div>

<b>Search Category</b> &nbsp;&nbsp;
<input type="text" name="txt_search" id="txt_search" style="width: 50%" />
<div id="sp_mul_rec" style="margin: 10px 0px 10px 0px;max-height: 200px;overflow: auto">
    <?php
    $id_val = 'S';
    //include(APPPATH . 'views/scrutiny/get_categories.php');
    $this->include('scrutiny/get_categories');
    ?>

</div>
<div style="width: 100%;max-height: 200px;overflow: auto;margin-top: 20px">
    <table id="tb_new_cat" border="1" style="border-collapse: collapse;padding-top: 0;width: 100%;" class="table_tr_th_w_clr">
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

        $snos_new = 1;

        ?>


        <?php
        
       
        /* $sql_po_new =  mysql_query("Select new_submaster_id ,od_cat,subject_description,category_description,subject_sc_old,category_sc_old ,
sub_name1, sub_name4 , sub_name2, sub_name3,flag,subcode2_hc,subcode1_hc,subcode3_hc from mul_category a join submaster b on a.new_submaster_id=b.id
where diary_no = '$_REQUEST[hd_diary_nos]' and a.display='Y' and b.is_old = 'N' and b.display='Y'")

            or die("Error: " . __LINE__ . mysql_error()); */
			
		$sql_po_new = $CaveatModel->getMulCategoriesBy($hd_diary_nos);
       
		if(!empty($sql_po_new))
		{
		
        foreach ($sql_po_new as $row_new) {
            
        ?>

            <tr id="tr_uo_new<?php echo $snos_new; ?>">
                <td>
                    <input type="checkbox" name="hd_chk_add_new<?php echo $snos_new; ?>" class="chkItem" id="hd_chk_add_new<?php echo $snos_new; ?>" onclick="getDone_upd_cat(this.id);" checked="true" mpn_idd="<?php echo $row_new['new_submaster_id'];?>"/>
                    <input type="hidden" name="hd_color_new<?php echo $snos_new; ?>" id="hd_color_new<?php echo $snos_new; ?>" value="<?php echo $row_new['flag'] ?>" />
                </td>
                <td>
                    <span class="<?php if ($row_new['flag'] == 's') { ?>cl_text<?php
                                                                            } else { ?>cl_other<?php
                                                                                                                        } ?>">
                        <?php
                        //if ($row_new['flag'] == 's')
                        echo $row_new['category_sc_old'];
                        //else
                        // echo $row_new['subcode1_hc'] . $row_new['subcode2_hc'] . $row_new['subcode3_hc'];

                        ?>
                        <input id="hd_sp_c_new<?php echo $snos_new; ?>" type="hidden" value="<?php  ?>" />
                        <input id="hd_sp_d_new<?php echo $snos_new; ?>" type="hidden" value="<?php echo $row_new['new_submaster_id']  ?>" /></span>
                </td>
                <td>
                    <span class="<?php if ($row_new['flag'] == 's') { ?>cl_text<?php
                                                                            } else { ?>cl_other<?php
                                                                                                                        } ?>"><?php echo $row_new['sub_name1']; ?></span>
                    <input id="hd_sp_a_new<?php echo $snos_new; ?>" type="hidden" value="<?php echo $row_new['subcode'] ?>" />
                </td>
                <td>
                    <span class="<?php if ($row_new['flag'] == 's') { ?>cl_text<?php
                                                                            } else { ?>cl_other<?php
                                                                                                                        } ?>"><?php
                                                                                                                if ($row_new['sub_name4'] == $row_new['sub_name1'])
                                                                                                                    echo '-';
                                                                                                                else {
                                                                                                                    echo $row_new['sub_name4'];
                                                                                                                }
                                                                                                                ?></span>
                    <input id="hd_sp_b_new<?php echo $snos_new; ?>" type="hidden" value="  <?php echo $row_new['subcode1'] ?>" />
                </td>
                <td>
                    <span class="<?php if ($row_new['flag'] == 's') { ?>cl_text<?php
                                                                            } else { ?>cl_other<?php
                                                                                                                        } ?>"><?php

                                                                                                                if ($row_new['sub_name3'] == '' && $row_new['sub_name2'] != '')
                                                                                                                    echo $row_new['sub_name4'];
                                                                                                                else if ($row_new['sub_name3'] != '' && $row_new['sub_name2'] != '')
                                                                                                                    echo $row_new['sub_name3'];
                                                                                                                else
                                                                                                                    echo '-';
                                                                                                                ?></span>
                </td>
                <td>
                    <span class="<?php if ($row_new['flag'] == 's') { echo 'cl_text';
                                                                            } else { echo 'cl_other';
                                                                                                                        } ?>"><?php
                                                                                                                if ($row_new['sub_name4'] != '' && $row_new['sub_name4'] != '' && $row_new['sub_name3'] != '' && $row_new['sub_name2'] != '')
                                                                                                                    echo $row_new['sub_name4'];
                                                                                                                else
                                                                                                                    echo '-';
                                                                                                                ?></span>
                </td>
            </tr>
        <?php
            $snos_new++;
        }}
        ?>
        <input type="hidden" name="hd_ssno_new" id="hd_ssno_new" value="<?php echo $snos_new - 1; ?>" />
        <input type="hidden" name="hd_co_tot_new" id="hd_co_tot_new" value="<?php echo $snos_new - 1; ?>" />
    </table>
</div>

<b>Search Category New</b> &nbsp;&nbsp;
<input type="text" name="txt_search_new" id="txt_search_new" style="width: 50%" />
<div id="sp_mul_rec_new" style="margin: 10px 0px 10px 0px;max-height: 200px;overflow: auto">
    <?php
    $id_val = 'S';
    //include('../scrutiny/get_new_categories.php');
	//include(APPPATH . 'views/scrutiny/get_new_categories.php');
    $this->include('scrutiny/get_new_categories');
    ?>

</div>
<!-- <div style="vertical-align: center; display:none;" align="center" id="otherdiv" > -->

<div style="vertical-align: center;display: none" align="center" id="otherdiv">

    <b>Remarks for Sub-Category-Others:</b>
    <textarea rows="4" cols="50" id="ortext" style="text-align: left;" onkeypress="return onlyalphabet(event)"><?php
                /* $check_dno_qr = "select remarks from other_category where diary_no='$_REQUEST[hd_diary_nos]' and display='Y'";
                $check_dno_rs = mysql_query($check_dno_qr)   or die("Error: " . __LINE__ . mysql_error());
				 */
				$check_dno_rs = $CaveatModel->getOtherCategoryRemarks($hd_diary_nos);
				 
				if(!empty($check_dno_rs))
				{
					echo $check_dno_rs['remarks'];
				}
                /* if (mysql_num_rows($check_dno_rs) > 0) {
                    $row = mysql_fetch_array($check_dno_rs);
                    echo $row[0];
                } */
                ?></textarea>

    <font color="red">Note: Special Characters(!@#$'^& not allowed to enter)</font><br>

</div>


<div style="text-align: center">
    <input type="button" name="btn_save_cat" id="btn_save_cat" value="Submit" onclick="sav_mul_cat()" />
</div>
<script>
    function onlyalphabet(evt) {

        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        //alert(charCode);
        if ((charCode >= 65 && charCode <= 90) || (charCode >= 97 && charCode <= 122) || charCode == 9 || charCode == 8 ||
            charCode == 127 || charCode == 32 || charCode == 46 || charCode == 47 || charCode == 64 || charCode == 37 ||
            (charCode >= 40 && charCode <= 64)
        ) {
            return true;
        }
        return false;
    }

    $(document).ready(function() {
        var cat = '<?= $exist_cat ?>';
        //alert(cat);
        //var other_catg = ['10','20','46','75','87','101','115','129','141','151','163','182','201','215','227','250','259','262','270','276','289','295','300','304','311'];
        var exist = other_catg.includes(cat);
        //alert("exist"+exist);
        if (exist == true)
            document.getElementById('otherdiv').style.display = 'block';
        else
            document.getElementById('otherdiv').style.display = 'none';
    });
</script>