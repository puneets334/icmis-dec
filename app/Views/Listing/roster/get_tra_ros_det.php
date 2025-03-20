<br/>
<input type="hidden" name="hd_fn_sn" id="hd_fn_sn" value="<?php echo $request['str1']; ?>"/>
<input type="hidden" name="hd_s_d_ben" id="hd_s_d_ben" value="<?php echo $res_sql; ?>"/>
<table align="center" class="c_vertical_align tbl_border" cellpadding="5" cellspacing="5" style="width: 60%">
    
    <tr>
        <th style="width: 100px">
            Bench 
        </th>
        <td>
            <select name="sp_bnch_nm" id="sp_bnch_nm" onchange="get_ben_no_s(this.value)">
                <?php 
                foreach($all_bench as $row1)
                {
                    if($request['hd_ud']!='990')
                    {
                ?>
                    <option value="<?php echo $row1['id'] ?>" <?php if($st_bench==$row1['id']) { ?> selected="selected" <?php } ?>><?php echo $row1['bench_name'] ?></option>
                <?php
                    }
                    else 
                    {
                        if($row1['id']==5 || $row1['id']==6) 
                        {
                        ?>
                            <option value="<?php echo $row1['id'] ?>"><?php echo $row1['bench_name'] ?></option>
                        <?php
                        }
                    }
                }
                ?>
            </select>
            </td>
            <th>
                Bench No. 
            </th>
            <td>
                <select name="bench_names" id="bench_names" >
                    <option value="">Select</option>
                    <?php
                    $request['str'] = $res_sql;
                    foreach ($res_roster as $val_roster) {
                        echo "<option value='".$val_roster['id']."'>".$val_roster['bench_no']."</option>";
                    }
                    ?>
                </select> 
            </td>
        </tr>
        <tr>
            <th>
                Judge Name :
            </th> 
            <td colspan="5">
                <select name="judge_codes" id="judge_codes"  <?php if($res_sql!=9) { ?> multiple="multiple" style="width:300px;height: 150px;"  <?php } else { ?> style="width:300px;height: 20px;" <?php } ?> >
                    <option value="0">Select</option>
                    <?php
                            
                        $ex_jud_ids=explode(',',$jud_ids);     
            
                    if($res_sql==9 )  
                    {
                        foreach ($one as $key) 
                        {
                            $k1=  explode('^', $key);                          
                        ?>
                            <option value="<?php echo $k1[0]; ?>" <?php for ($index = 0; $index < count($ex_jud_ids); $index++) { if($ex_jud_ids[$index]==$k1[0]) { ?> selected="selected" <?php } } ?>><?php echo $k1[1]; ?></option>  
                        <?php
                        }
                    }
                    else
                    {
                        $two = $jcode_name;
                        foreach ($two as $key) 
                        {                            
                            $k1=  explode('^', $key);
                        
                        ?>
                            <option value="<?php echo $k1[0]; ?>" <?php if($k1[0]>=9001) { ?> style="color: blue" <?php } ?> <?php for ($index = 0; $index < count($ex_jud_ids); $index++) { if($ex_jud_ids[$index]==$k1[0]) { ?> selected="selected" <?php } } ?>><?php echo $k1[1]; ?></option>  
                            
                        <?php
                        }
                    }
                    ?>
                </select>                        
            </td>
        </tr>
            <tr>
                <th>Session</th>
                <td style="ext-align: right;" colspan="3">
                    <select name="sesss" id="sesss" >
                        <option value="">-Select-</option>
                        <option value="Whole Day" <?php if($res_sq_roster['session']=='Whole Day') { ?> selected="selected" <?php } ?>>Whole Day</option>
                        <option value="Before Lunch" <?php if($res_sq_roster['session']=='Before Lunch') { ?> selected="selected" <?php } ?>>Before Lunch</option>                        
                        <option value="After Lunch"  <?php if($res_sq_roster['session']=='After Lunch') { ?> selected="selected" <?php } ?>>After Lunch</option>            
                        <option value="After Regular Bench"  <?php if($res_sq_roster['session']=='After Regular Bench') { ?> selected="selected" <?php } ?>>After Regular Bench</option>
                         <option value="After DB" <?php if($res_sq_roster['session']=='After DB') { ?> selected="selected" <?php } ?>>After DB</option>
                        <option value="After SPL. DB" <?php if($res_sq_roster['session']=='After SPL. DB') { ?> selected="selected" <?php } ?>>After SPL. DB</option>
                    </select>
                </td>
            </tr> 
            <tr>
                <th>
                    Sitting :
                </th>
                <td colspan="3">
                    <input type="radio" name="rdn_court_hls" id="rdn_courts" value="0" <?php if($res_sq_roster['courtno']!='999') { ?> checked="checked" <?php } ?>/>Court &nbsp;&nbsp;
                    <input type="radio" name="rdn_court_hls" id="rdn_hls" value="1"  <?php if($res_sq_roster['courtno']=='999') { ?> checked="checked" <?php } ?>/>Hall 
                    &nbsp;&nbsp;<br><br>
                   Court No. <input type="text" name="txt_court_nos" id="txt_court_nos" size="6"/>
                </td>
            </tr>
            <tr>
                <th>
                    Timing :
                </th>
                <td colspan="3">
                <?php
                    $frm_time = $res_sq_roster['frm_time'];
                    $ex_frm_time = explode(':', $frm_time);
                    $ex_min=  explode(' ', $ex_frm_time[1]);
                    ?>
                    <select name="ddl_hrss" id="ddl_hrss" onchange="set_min_s(this.value)">
                        <option value="">Select</option>
                        <?php
                        for($j=1;$j<=12;$j++)
                        {
                        ?>
                            <option value="<?php echo $j; ?>" <?php if($ex_frm_time[0]==$j) { ?> selected="selected" <?php } ?>><?php echo $j; ?></option>
                        <?php
                        }
                        ?>
                     </select> &nbsp;<b> : </b>&nbsp;
                     
                     <select name="ddl_mins" id="ddl_mins" <?php if($ex_frm_time[0]=='') { ?> disabled="true" <?php } ?>>
                        <option value="">Select</option>
                        <?php
                        for($k=0;$k<=60;$k++)
                        {
                            if(strlen($k)==1)
                            {
                                $k='0'.$k;
                            }
                             ?>
                        <option value="<?php echo $k; ?>" <?php if($ex_min[0]==$k) { ?> selected="selected" <?php } ?>><?php echo $k; ?></option>
                        <?php
                        }
                        ?>
                    </select>
                    <select name="ddl_am_pms" id="ddl_am_pms" <?php if($ex_frm_time[0]=='') { ?> disabled="true" <?php } ?>>
                        <option value="">Select</option>
                        <option value="AM" <?php if($ex_min[1]=='AM') { ?> selected="selected" <?php } ?>>AM</option>
                        <option value="PM" <?php if($ex_min[1]=='PM') { ?> selected="selected" <?php } ?>>PM</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th>
                    No. of cases
                </th>
                <td colspan="3">
                    <input type="text" name="txt_no_cases" id="txt_no_cases" size="4" onkeypress="return OnlyNumbersTalwana(event,this.id)" maxlength="4" value="<?php echo $res_sq_roster['tot_cases']; ?>"/>                  
                </td>
            </tr>
            <tr>                
                <td align="center" colspan="6"><input type="button" name="btnsaves" id="btnsaves" value="Transfer" class="btn btn-primary" onclick="get_ts_real()"/></td>
            </tr>
</table>