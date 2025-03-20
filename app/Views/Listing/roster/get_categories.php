
<?php
$sss = 0;
$ex_cat1 = $ex_cat2 = [];
$ck_cl_open_dt = '';
$ck_close_button = '';
$i = 1;
$ex_cat2[$i] = $ex_cat1[$i] = '';
$str_to_display = '';

foreach ($catData as $key => $row1) {
    $n_b_blank = '';

    if ($row1['b_n'] == 'N')
        $n_b_blank = 'Not Before';
    else if ($row1['b_n'] == 'B')
        $n_b_blank = 'Before';

    $str_to_display1 = '';
    $matter_idsss = '';
    ?>
        
    <input type="hidden" name="hd_cat_id<?php echo $ct_total; ?>" id="hdcatid_<?php echo $ct_total; ?>" value="<?php echo $row1['stage_code']; ?>"/>
                
    <?php
        
    $res_mattr = $thiss->getSubHeading($row1['stage_code']);
    if(!empty($res_mattr)){
        $res_mattr = $res_mattr[0];
    }else{
        $res_mattr['stagename'] = '-';
    }
    //  pr($res_mattr);
    $mn_fis = '';

        
    if ($sss == 0) 
    {
        $sno = 1;

        if ($row1['m_f'] == '1')
            $row_matr['ck'] = 'Motion';
        else if($row1['m_f']=='2') 
            $row_matr['ck']='Final';
        else if($row1['m_f']=='3') 
            $row_matr['ck']='Lok Adalat';
        else if($row1['m_f']=='4') 
            $row_matr['ck']='Mediation';

        $str_to_display1 = "<tr>
            <td style=' text-align:center;'>" . $row_matr['ck'] . "</td> 
            <td colspan='6' style=' text-align:center;' ><input type='button' name=btnAdd_" . $ct_total . " id=btnAdd_" . $ct_total . " value='Add' class='btn btn-primary bk_out' onclick='add_records(this.id)'" . $ck_cl_open_dt . "/>&nbsp;&nbsp;<input type='button' name=btnClose_" . $ct_total . " id=btnClose_" . $ct_total . " value='Close' class='btn btn-primary bk_out' onclick='close_records(this.id)' " . $ck_cl_open_dt . $ck_close_button . "/>&nbsp;&nbsp;<input type='button' name=btnDeletes_" . $ct_total . " id=btnDeletes_" . $ct_total . " value='Delete' class='btn btn-primary bk_out' onclick='deletes_records(this.id)' /> &nbsp;&nbsp;<input type='button' name=btnTransfer_" . $ct_total . " id=btnTransfer_" . $ct_total . " value='Transfer' class='btn btn-primary bk_out' onclick='transfer_records(this.id)' />&nbsp;&nbsp;<input type='button' name=btnPrint_" . $ct_total . " id=btnPrint_" . $ct_total . " value='Print' class='btn btn-primary bk_out' onclick='print_records(this.id)' />&nbsp;&nbsp;<input type='button' name=btnext_" . $ct_total . " id=btnext_" . $ct_total . " value='Extend' class='btn btn-primary bk_out' onclick='extend_records(this.id)' /> <input type='hidden' name=btnroster_" . $ct_total . " id=btnroster_" . $ct_total . " value=" . $row1['id'] . " /> <input type='hidden' name=btnbench_id" . $ct_total . " id=btnbench_id" . $ct_total . " value=" . $row1['bench_id'] . " /> 
            </td>
        </tr>";
    }

        $case_nat_nm = '';
        $ex_stage_nature[$i]= $row1['stage_nature'];

        if ($ex_stage_nature[$i] == 'C')
            $case_nat_nm = 'Civil';
        else if ($ex_stage_nature[$i] == 'R')
            $case_nat_nm = 'Criminal';
        else if ($ex_stage_nature[$i] == 'W')
            $case_nat_nm = 'Writ';
        else
            $case_nat_nm = '-';

        $ex_case_type[$i]=$row1['case_type'];
        $res_sql_ck_cas_ty = $thiss->getCasetypeKey($row1['case_type']);

        if(!empty($res_sql_ck_cas_ty)){
            $res_sql_ck_cas_ty = $res_sql_ck_cas_ty[0];
        }
        else{
            $res_sql_ck_cas_ty['skey'] = '-';
        }

        $ex_submaster_id[$i]=$row1['submaster_id'];
        $res_quer2 = $thiss->getSubMaster($ex_submaster_id[$i]);

        if(!empty($res_quer2)){
            $res_quer2 = $res_quer2[0];
        }
        else{
            $res_quer2 = [];
        }

        $br='';
        $br1='';

        if(isset($res_quer2['sub_name2']) && $res_quer2['sub_name2']!='')
            $br='<br/>';
        else
            $res_quer2['sub_name2'] = '';
                
        if(isset($res_quer2['sub_name3']) && $res_quer2['sub_name3']!='')
            $br1='<br/>';
        else
            $res_quer2['sub_name3'] = '';

    // if ($res_quer2 == '')
    //  $res_quer2 = '-';

        if(isset($res_quer2['sub_name1']) && isset($res_quer2['sub_name4']))
        {
            if($res_quer2['sub_name1'] == $res_quer2['sub_name4'])
            {
                $res_quer2['sub_name4'] = '';
            }
        }

        if(!isset($res_quer2['sub_name1']))
            $res_quer2['sub_name1'] = '';

        if(!isset($res_quer2['sub_name3']))
            $res_quer2['sub_name3'] = '';

        if(!isset($res_quer2['sub_name4']))
            $res_quer2['sub_name4'] = '';

        $category_sc_old = isset($res_quer2['category_sc_old']) ? $res_quer2['category_sc_old'] : '';

        $str_to_display .= $str_to_display1 . "<tr>
        <td width='5%' style='text-align:center;'>" . $sno++ . ". </td>
        <td  style='text-align:left;'><span id=spcsnature_" . $ct_total . ">" . $case_nat_nm ."</span></td>   
        <td  style='text-align:left;'><span id=spcstype_" . $ct_total . ">" . $res_sql_ck_cas_ty['skey'] ."</span></td> 
        <td  style='text-align:left;'><span id=spcatname_" . $ct_total . ">" . $res_mattr['stagename'] ."</span></td>
        <td  style='text-align:left;'>" .$category_sc_old."</td>
        <td  style='text-align:left;'>
            <span style=color:red>" . $n_b_blank . "</span><br/><span id=spcat1_" . $ct_total . ">" .  $res_quer2['sub_name1'] ."</span>$br
            <span id=spcat2_" . $ct_total . ">" . $res_quer2['sub_name2'] ."</span>$br1
            <span id=spcat3_" . $ct_total . ">" . $res_quer2['sub_name3'] ."</span><br/>
            <span id=spcat4_" . $ct_total . ">" . $res_quer2['sub_name4'] ."</span>         
        </td> 
        <td>
            <input type='button' name=btnDelete" . $ct_total . " id=btnDelete" . $ct_total . " value='Delete' class='btn btn-primary bk_out' onclick='delete_recordss(this.id)' class='bk_out'/>
        </td>
    </tr>";
    ?>
    <input type="hidden" name="hd_roster_id<?php echo $ct_total; ?>" id="hd_rosterid_<?php echo $ct_total; ?>" value="<?php echo $row1['id'] ?>"/>
    <input type="hidden" name="hd_cat_id<?php echo $ct_total; ?>" id="hdcatid_<?php echo $ct_total; ?>" value="<?php echo $row1['stage_code']; ?>"/>
    <input type="hidden" name="hd_stage_nature<?php echo $ct_total; ?>" id="hd_stage_nature<?php echo $ct_total; ?>" value="<?php echo $ex_stage_nature[$i]; ?>"/>
    <input type="hidden" name="hd_case_type<?php echo $ct_total; ?>" id="hd_case_type<?php echo $ct_total; ?>" value="<?php echo $ex_case_type[$i]; ?>"/>
    <input type="hidden" name="hd_cat1<?php echo $ct_total; ?>" id="hd_cat1<?php echo $ct_total; ?>" value="<?php echo $ex_cat1[$i]; ?>"/>
    <input type="hidden" name="hd_cat2<?php echo $ct_total; ?>" id="hd_cat2<?php echo $ct_total; ?>" value="<?php echo $ex_cat2[$i]; ?>"/>
    <input type="hidden" name="hd_cat3<?php echo $ct_total; ?>" id="hd_cat3<?php echo $ct_total; ?>" value="<?php echo $ex_submaster_id[$i]; ?>"/>
    <?php
        $sss++;
        $mn_fis = $row_matr['ck'];
        $ct_total++;
    }
    echo $str_to_display;
    ?>