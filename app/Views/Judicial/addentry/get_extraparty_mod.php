<?= view('header'); ?>

<style>
    #wrapper_1:after {
        content: "";
        background-color: #000;
        position: absolute;
        width: 0.2%;
        height: 100%;
        top: 0;
        left: 100%;
        display: block;
    }

    #wrapper_2:after {
        content: "";
        background-color: #000;
        position: absolute;
        width: 0.2%;
        height: 100%;
        top: 0;
        left: 100%;
        display: block;
    }
 
    .custom_select_inline
    {
        width: 20% !important;
        display: inline-block;
    }
    </style>
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">

                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Filing</h3>
                            </div>
                             <?=view('Filing/filing_filter_buttons'); ?>
                        </div>
						
                    </div>
					<?=view('Filing/filing_breadcrumb');?>
                    <!-- /.card-header -->

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">

                            <form method="post" action="<?= site_url(uri_string()) ?>">
                            <?= csrf_field() ?>
                            
                                <?php
                                $db = \Config\Database::connect();
                                $session_user = $_SESSION['login']['usercode'];
                                
                                $_REQUEST['dno'] = $_SESSION['filing_details']['diary_no'];
                                $fil_no_diary = " diary_no=$_REQUEST[dno] ";
                                $hdfil = $_REQUEST['dno'];
                                $disabled = 0;

                                $allow_user=0;
                                    $check_if_fil_user = "SELECT a.usercode FROM fil_trap_users a JOIN master.users b ON a.usercode=b.usercode
                                    WHERE a.usertype=101 AND a.display='Y' AND b.display='Y' AND attend='P' and b.usercode=$session_user ORDER BY empid";
                                    
                                    $check_if_fil_user = $db->query($check_if_fil_user)->getRowArray();
                                    
                                    if(!empty($check_if_fil_user)){
                                        $allow_user=1;
                                    }
                                    
                                
                                $main_row = is_data_from_table('main', $fil_no_diary , ' pet_name,res_name,c_status ', $row = '');
                                if(!empty($main_row))
                                {
                                
                                ?>
                                
                                <input type='hidden' value="<?php echo $_REQUEST['dno']; ?>" id='hdfno'>
                                <input class="form-control" type='hidden' value="<?php echo $hdfil;?>" id='hdfno_update'>
                                <table class="table table-striped custom-table" align="center" width="100%" id="table_causetitle" cellpadding="10">
                                    <tr align="center" style="color:blue"><th><?php
                                    echo "Case No.-";
                                
                                    if($casetype['fil_no'] !='' || $casetype['fil_no'] !=NULL){
                                        echo '[M]'.$casetype['short_description'].SUBSTR($casetype['fil_no'],3).'/'.$casetype['m_year'];
                                    }

                                    if($casetype['fil_no_fh'] !='' || $casetype['fil_no_fh'] !=NULL){
                                    
                                    
                                        $fil_no_fh = SUBSTR($casetype['fil_no_fh'],0,2);		 
                                        $r_case = is_data_from_table('master.casetype', " casecode= $fil_no_fh " , ' short_description ', $row = '');
                                        if(!empty($r_case)){
                                            echo ',[R]'.$r_case['short_description'].SUBSTR($casetype['fil_no_fh'],3).'/'.$casetype['f_year'];
                                        }
                                    
                                    }
                                    echo ", Diary No: ".substr($_REQUEST['dno'],0,-4).'/'.substr($_REQUEST['dno'],-4); ?></th></tr>
                                    <tr  align="center" style="color:blue"><th><b><?php
                                    echo $main_row['pet_name'];
                                    if($casetype['pno']==2) echo " <span style='color:#72bcd4'>AND ANR</span>";
                                    else if($casetype['pno']>2) echo " <span style='color:#72bcd4'>AND ORS</span>";
                                    ?>
                                    </b><font style="color:black">&nbsp; Versus &nbsp;</font>
                                    <b><?php echo $main_row['res_name'];
                                    if($casetype['rno']==2) echo " <span style='color:#72bcd4'>AND ANR</span>";
                                    else if($casetype['rno']>2) echo " <span style='color:#72bcd4'>AND ORS</span>";
                                    ?></b></th></tr>
                                    <?php
                                    if($main_row['c_status']=='D')
                                    {
                                ?>
                                    <tr><th style="color:red;">!!!The Case is Disposed!!!</th></tr>
                                    <?php
                                    }
                                    ?>
                                </table>
                                <table width="100%" cellpadding="10">
                                    <tr>
                                        <td valign="top" width="70%" >
                                            <table border="1" style="border-collapse: collapse" width="100%" cellpadding="5">
                                                <tr>
                                                    <td style="border:none;padding-bottom: 10px;">Add - </td>
                                                    <td style="border:none" colspan="3">
                                                        <select id='pri_action1'  class="form-control custom_select_inline"><option value="">Select</option>
                                                        </select>
                                                        &nbsp;
                                                        
                                                        <select id='for_selecting_lrs' style="width: 40%;display: none" disabled="" class="form-control"><option value="">Select</option>
                                                        <?php
                                                        if(!empty($select_for_lrs_rs))
                                                        {
                                                            foreach($select_for_lrs_rs as $select_for_lrs_row){
                                                                
                                                                ?>
                                                                <option value="<?php echo $select_for_lrs_row['pet_res'].'~'.$select_for_lrs_row['sr_no'].'~'.$select_for_lrs_row['sr_no_show']; ?>"><?php echo $select_for_lrs_row['pet_res'].'-'.
                                                                        $select_for_lrs_row['sr_no_show'].' = '.$select_for_lrs_row['partyname']; ?></option>
                                                                    <?php
                                                            }
                                                        }
                                                        ?>
                                                        </select>
                                                        Order
                                                        <?php
                                                        $order1='S';
                                                        $order2='D';
                                                        $order3='P';
                                                        
                                                        $row_order = is_data_from_table('party_order', " user= $session_user " , ' o1,o2,o3 ', $row = '');
                                                        if(!empty($row_order)){
                                                            //$row_order = mysql_fetch_array($ordering);
                                                            $order1 = $row_order['o1'];
                                                            $order2 = $row_order['o2'];
                                                            $order3 = $row_order['o3'];
                                                        }
                                                        ?>
                                                        &nbsp;
                                                        1.<select id="update_order1" class="form-control custom_select_inline">
                                                            <option value="" >--Select--</option>
                                                            <option value="S" <?php if($order1=='S') echo "selected"; ?>>State</option>
                                                            <option value="D" <?php if($order1=='D') echo "selected"; ?>>Department</option>
                                                            <option value="P" <?php if($order1=='P') echo "selected"; ?>>Post</option></select>
                                                        2.<select id="update_order2" class="form-control custom_select_inline">
                                                            <option value="" >--Select--</option>
                                                            <option value="S" <?php if($order2=='S') echo "selected"; ?>>State</option>
                                                            <option value="D" <?php if($order2=='D') echo "selected"; ?>>Department</option>
                                                            <option value="P" <?php if($order2=='P') echo "selected"; ?>>Post</option></select>
                                                        3.<select id="update_order3" class="form-control custom_select_inline">
                                                            <option value="" >--Select--</option>
                                                            <option value="S" <?php if($order3=='S') echo "selected"; ?>>State</option>
                                                            <option value="D" <?php if($order3=='D') echo "selected"; ?>>Department</option>
                                                            <option value="P" <?php if($order3=='P') echo "selected"; ?>>Post</option></select>
                                                        
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="border:none">Party Type:</td>
                                                    <td style="border:none">
                                                    <select id="party_flag_update" disabled="" class="form-control">
                                                            <option value="">Select</option>
                                                </select>
                                                        <input class="form-control" type="hidden" id="hd_party_flag_update">
                                                            Party No:<span id="pno1"></span></td>
                                                    <td style="border:none">Individual/Dept.:</td><td style="border:none"><select class="form-control" id="update_party_type" onchange="activate_extra(this.value)">
                                                        <option value="I">Individual</option>
                                                        <option value="D1">State Department</option>
                                                        <option value="D2">Central Department</option>
                                                        <option value="D3">Other Organization</option>
                                                    </select></td></tr>
                                                    <tr ><td style="border:none;" >Con/Pro</td><td style="border:none;" colspan="3">
                                                        <select id="update_p_cntpro" class="form-control">
                                                            <option value="C">Contested</option>
                                                            <option value="P">Proforma</option>
                                                        </select>
                                                    </td></tr>
                                                
                                                <tr  id="tr_for_individual">
                                                    <td style="border:none">Mask Party Name :</td>
                                                    <td style="border:none"  colspan="3">
                                                        <input class="form-control_" type="checkbox" id="mask_check" onclick="f1()"/>
                                                        &nbsp;&nbsp;  <span id="span_mask_name" style="display:none;" >
                                                            Masked Name :
                                                        <input class="form-control" type="text" style="width:200px;" id="masked_name" onkeypress="return onlyalphab(event)" onblur="remove_apos(this.value,this.id)" sty/></span>
                                                    </td>
                                                </tr>

                                                    <tr id="for_I_1">
                                                        <td style="border:none">Name:</td><td style="border:none">
                                                            <input class="form-control" type="text" style="width:200px;" id="update_p_name" onkeypress="return onlyalphab(event)" onblur="remove_apos(this.value,this.id)"/>
                                                        </td>
                                                        <td style="border:none">Relation: </td><td style="border:none"><select id="update_p_rel" style="width:142px;" class="form-control">
                                                        <option value="">Select</option>
                                                        <option value="S" >Son of</option>
                                                        <option value="D" >Daughter of</option>
                                                        <option value="W" >Wife of</option>
                                                        <option value="F" >Father of</option>
                                                        <option value="M" >Mother of</option>
                                                    </select></td></tr>
                                                    <tr id="for_I_2"><td style="border:none">Father/Husb. Name:</td><td style="border:none"> <input class="form-control" style="width:200px;" type="text" id="update_p_rel_name" onkeypress="return onlyalphab(event)" onblur="remove_apos(this.value,this.id)"/></td>
                                                        <td style="border:none">Gender: </td><td style="border:none"><select id="update_p_sex" class="form-control"><option value="">Select</option>
                                                        <option value="M" >Male</option>
                                                        <option value="F" >Female</option>
                                                        <option value="N" >N.A.</option></select></td></tr>
                                                    <tr id="for_I_3"><td style="border:none">Age: </td><td style="border:none"><input class="form-control" maxlength="3" style="width:200px;" type="text" id="update_p_age" onkeypress="return onlynumbers(event)"/></td>
                                                        <td style="border:none">Caste:</td><td style="border:none"> <input class="form-control" style="width:200px;" type="text" id="update_p_caste" onblur="remove_apos(this.value,this.id)"/></td></tr>
                                                <tr style="display:none" id="tr_d0">
                                                    <td style="border:none">State Name:<input class="form-control_" type="checkbox" id="s_causetitle">  </td><td style="border:none" colspan="3">
                                                        <input class="form-control" type="text" id="p_statename" style="width:200px;" onkeypress="return onlyalpha(event)" onblur="remove_apos(this.value,this.id)"/>
                                                        <input class="form-control" type="hidden" id="p_statename_hd"/>
                                                    </td>
                                                </tr>
                                                <tr style="display:none" id="tr_d">
                                                    <td style="border:none">Department:<input class="form-control_" type="checkbox" id="d_causetitle" ></td>
                                                        <td style="border:none">
                                                            <input class="form-control" type="text" id="p_deptt" style="width:200px;" onkeypress="return onlyalphab(event)" onblur="remove_apos(this.value,this.id)"/><!-- onblur="get_a_d_code(this.id)"-->
                                                            <input class="form-control" type="hidden" id="p_deptt_hd"/>
                                                    </td>
                                                    <td style="border:none">Post:<input class="form-control_" type="checkbox" id="p_causetitle"></td><td style="border:none">
                                                            <input class="form-control" type="text" id="p_post" style="width:200px;" onkeypress="return onlyalphab(event)" onblur="remove_apos(this.value,this.id)"/><!-- onblur="get_a_d_code(this.id)"-->
                                                    <input class="form-control" type="hidden" id="post_code"/>
                                                        <input class="form-control" type="hidden" id="party_name"/></td>

                                                </tr>
                                                
                                                <tr id="for_I_4"><td style="border:none">Occupation: </td><td style="border:none"><input class="form-control" onkeypress="return onlyalphab(event)" type="text" id="update_p_occ" style="width:200px;" onblur="remove_apos(this.value,this.id)"/>
                                                        <input class="form-control" type="hidden" id="p_occ_hd_code"/></td>
                                                    <td style="border:none">Education/Qualification:  </td><td style="border:none"><input class="form-control" onkeypress="return onlyalphab(event)" type="text" id="update_p_edu" style="width:200px;" onblur="remove_apos(this.value,this.id)"/>
                                                        <input class="form-control" type="hidden" id="p_edu_hd_code"/></td></tr>
                                                <tr>
                                                    <td style="border:none">Address:</td>
                                                    <td style="border:none">
                                                        <textarea style="width:200px;" rows="4" id="update_p_add" onblur="remove_apos(this.value,this.id)" maxlength="250"/></textarea>
                                                    </td>
                                                    <td style="border:none">Tehsil/Place/City: </td>
                                                    <td style="border:none">
                                                        <input class="form-control" type="text" id="update_p_city" style="width:200px;" onblur="remove_apos(this.value,this.id)"/>
                                                    </td>
                                                </tr>
                                                <tr><td style="border:none">Country:</td><td style="border:none" colspan="3">
                                                        
                                                        <select id="p_cont_update" style="width:200px;" class="form-control">
                                                        <?php
                                                        $country = is_data_from_table('master.country', " display='Y' ORDER BY country_name " , ' country_name,id ', $row = 'A');
                                                        if(!empty($country))
                                                        {
                                                            foreach($country as $country_row){
                                                                ?>
                                                                <option value="<?php echo $country_row['id']; ?>" <?php if($country_row['id']=='96') echo "Selected"; ?>><?php echo $country_row['country_name']; ?></option>
                                                                    <?php
                                                            }
                                                        }
                                                        ?>
                                                        </select>
                                                    </td></tr>
                                                <tr><td style="border:none">State:</td><td style="border:none">
                                                        <select id="update_p_st" style="width:200px;" onchange="getDistrict(this.value)" class="form-control"><option value="">Select</option>
                                                    <?php 
                                                    $st_rs = is_data_from_table('master.state', " district_code =0
                                                                    AND sub_dist_code =0
                                                                    AND village_code =0
                                                                    AND display = 'Y'
                                                                    AND state_code < 100
                                                                    ORDER BY name " , ' id_no state_code, name ', $row = 'A');
                                                    if(!empty($st_rs))
                                                    {
                                                        foreach($st_rs as $st_row)
                                                        {
                                                            ?>
                                                            <option value="<?php echo $st_row['state_code']?>"><?php echo $st_row['name']?></option>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                    </select></td>
                                                    <td style="border:none">District:</td><td style="border:none"> <select id="p_dis1" style="width:200px;" class="form-control"><option value="">Select</option>
                                                        
                                                </select></td>
                                                    </tr>
                                                <tr><td style="border:none">Pin:</td><td style="border:none"> <input class="form-control" maxlength="6" type="text" style="width:200px;" id="p_pin" onkeypress="return onlynumbers(event)" onblur="remove_apos(this.value,this.id)"/></td>
                                                    <td style="border:none">Phone/Mobile:</td><td style="border:none"> <input class="form-control" style="width:200px;" type="text" id="p_mob" maxlength="10" onkeypress="return onlynumbers(event)" onblur="remove_apos(this.value,this.id)"/></td></tr>
                                                <tr><td style="border:none">Email Id:</td><td style="border:none"> <input class="form-control" type="text" id="p_email" style="width:200px;" onblur="remove_apos(this.value,this.id)"/></td>
                                                    <td style="border:none">Status:</td><td style="border:none">
                                                        <select id="p_status" style="width: 200px;" class="form-control"><option value="P">Pending</option>
                                                        <option value="T">Delete as Wrongly Entered [No. Will Shift]</option>
                                                        <option value="O">Delete by Order [No. Will Not Shift]</option><option value="D">Dispose</option></select></td></tr>
                                                <tr><td style="border:none">Lower Court Case:</td><td style="border:none">
                                                        
                                                        <select id="lower_case" style="width: 200px;" multiple="" size="6" class="form-control"><option value="">Select</option>
                                                        <?php
                                                        if(!empty($lowercase))
                                                        {
                                                            foreach($lowercase as $rowLower){
                                                                ?>
                                                                <option value="<?php echo $rowLower['lower_court_id']; ?>"><?php echo $rowLower['type_sname'].'/'.$rowLower['lct_caseno'].'/'.$rowLower['lct_caseyear'].' - '.$rowLower['agency_name']; ?></option>
                                                                    <?php
                                                            }
                                                        }
                                                        ?>
                                                        </select>
                                                        <input class="form-control" type="hidden" id="hd_casetype" value="<?php echo $casetype['casetype_id'];?>"/>
                                                        <input class="form-control" type="hidden" id="hd_allow_user" value="<?php echo $allow_user;?>"/>
                                                    </td>
                                                    <td style="border:none">Deletion/Disposal Remark:</td>
                                                    <td style="border:none"><input class="form-control" type="text" id="remark_delete" onblur="remove_apos(this.value,this.id)" style="width:200px;" disabled=""/></td>
                                                </tr>
                                                <tr ><td style="border: none;" >Remark For Update Party/LRs</td>
                                                    <td style="border: none;" colspan="3"><input class="form-control" type="text" id="remark_lrs" style="width:200px;" onblur="remove_apos(this.value,this.id)"  /></td>
                                                </tr>
                                                <tr><td colspan="4"><div style="text-align: center;font-weight: bold;cursor: pointer" id="sp_add_add">Add Additional Address</div>
                                                        <div id="extra_address"></div>
                                                    </td></tr>
                                                <input class="form-control" type="hidden" id="hd_add_add_count" value="0"/>
                                                <tr><td colspan="4" align="center">
                                                    <?php  
                                                    if($main_row['c_status']=='D'){
                                                        $disabled = 1;
                                                    }

                                                    if($disabled==1){
                                                                            if($session_user=='207' || $session_user=='167'||$session_user=='1')
                                                                            {

                                                                            if(($session_user=='207' || $session_user=='167'))
                                                                                {

                                                                        $diary_list = array(113112020,107912020,109782020,110242020,110042020,108432020,108442020,108232020,109432020,109772020,108632020,110272020,110122020,108582020,122232020,108672020,109072020,108402020,109132020,108932020,107952020,110562020,109082020,108962020,108862020,109142020,108012020,110162020,109472020,110222020,112702020,113082020,108472020,109492020,109552020,108942020,108892020,109272020,110022020,110142020,108612020,109942020,110312020,110032020,109712020,110232020); // list of diaary numbers

                                                                if (!in_array($_REQUEST['dno'], $diary_list))
                                                                            {
                                                    echo "<center><br><Br><div style=text-align: center><b> This matter is not in the list of disposed off matters during lockdown</b></div></center>";
                                                    exit();
                                                    }
                                                                                }

                                                                            ?>
                                                                            <input class="btn btn-primary" type="button" value="Save" onclick="call_save_extra()" id="svbtn" onkeydown="if (event.keyCode == 13) document.getElementById('svbtn').click()" disabled/>
                                                                            <input class="btn btn-danger" type="button" value="Reset/New" onclick="call_fullReset_extra()" id="rstbtn" onkeydown="if (event.keyCode == 13) document.getElementById('rstbtn').click()" disabled/>
                                                                    <?php
                                                                            }
                                                                            else
                                                                            {




                                                                            ?>
                                                                            <input class="btn btn-primary" type="button" value="Save" disabled/>
                                                    <?php } }
                                                    else {?>
                                                        <input class="btn btn-primary" type="button" value="Update" onclick="call_save_extra()" id="svbtn" onkeydown="if (event.keyCode == 13) document.getElementById('svbtn').click()" disabled/>
                                                        <input class="btn btn-danger" type="button" value="Reset/New" onclick="call_fullReset_extra()" id="rstbtn" onkeydown="if (event.keyCode == 13) document.getElementById('rstbtn').click()" disabled/>
                                                        
                                                    <?php }?>
                                                    </td></tr>
                                            </table>
                                            </td>
                                        <td valign="top" width="30%">
                                            <table width="100%" border="1" style="border-collapse: collapse" id="table_show" cellpadding="5">
                                                <thead>
                                                    <tr><th colspan="2">Petitioner Parties [with Lower Case]</th></tr>
                                                </thead>
                                                <?php
                                            
                                                
                                                if(!empty($p_pet_rs))
                                                {
                                                    foreach($p_pet_rs as $p_pet_row)
                                                    {
                                                    ?>
                                                    <tr><td align="center" style="width:10px"><?php /*if($p_pet_row['sr_no_show']==1){echo '1';}else*/{?>
                                                    
                                                    <?php if($p_pet_row['pflag']=='O' || $p_pet_row['pflag']=='D') { echo $p_pet_row['sr_no_show']; } else {?>
                                                    <input type="button" value="<?php echo $p_pet_row['sr_no_show']?>" name="ExMod_P_<?php echo $p_pet_row['sr_no_show'].'_'.trim($p_pet_row['ind_dep']); ?>"  /><?php }?>
                                                    <?php }?></td>
                                                    <td style="<?php if($p_pet_row['pflag']=='O') echo "color:red"; else if($p_pet_row['pflag']=='D') echo "color:#9932CC"; ?>"><?php echo $p_pet_row['partyname'];
                                                    if($p_pet_row['remark_lrs']!='' || $p_pet_row['remark_lrs']!=NULL)
                                                        echo "[".$p_pet_row['remark_lrs']."]";
                                                    if($p_pet_row['lct_casetype'] !='' || $p_pet_row['lct_casetype'] != NULL){
                                                        
                                                        echo "[".$p_pet_row['caseno']."]";
                                                    }
                                                    if($p_pet_row['pflag']=='O' || $p_pet_row['pflag']=='D') echo " [".$p_pet_row['remark_del']."]";
                                                    ?></td></tr>

                                                    <?php
                                                    }
                                                }
                                                ?>
                                                </table>
                                                <table width="100%" border="1" style="border-collapse: collapse" id="table_show" cellpadding="5">
                                                    <head>
                                                        <tr><th colspan="2">Respondent Parties [with Lower Case]</th></tr>
                                                    </thead>
                                                <?php
                                                
                                                
                                                if(!empty($p_res_rs))
                                                {
                                                foreach($p_res_rs as $p_res_row)
                                                {
                                                    ?>
                                                    <tr><td align="center" style="width:10px"><?php /*if($p_res_row['sr_no_show']==1){echo '1';}else*/{?>
                                                    <!--<input class="form-control" type="button" value="<?php //echo $p_res_row['sr_no']?>" style="width:25px;text-align: center" onclick="setPartiesinField(this.value,'R','<?php //echo $p_res_row['ind_dep'];?>')"/><?php //}?>-->
                                                    <?php if($p_res_row['pflag']=='O' || $p_res_row['pflag']=='D') { echo $p_res_row['sr_no_show']; } else {?>
                                                    <input  type="button" value="<?php echo $p_res_row['sr_no_show']?>" name="ExMod_R_<?php echo $p_res_row['sr_no_show'].'_'.trim($p_res_row['ind_dep']); ?>"  /><?php }?>
                                                    <?php }?></td>
                                                    <td style="<?php if($p_res_row['pflag']=='O') echo "color:red"; else if($p_res_row['pflag']=='D') echo "color:#9932CC"; ?>"><?php echo $p_res_row['partyname'];
                                                    if($p_res_row['remark_lrs']!='' || $p_res_row['remark_lrs']!=NULL)
                                                        echo "[".$p_res_row['remark_lrs']."]";
                                                    if($p_res_row['lct_casetype'] !='' || $p_res_row['lct_casetype'] != NULL){
                                                        //echo " [ ".$p_res_row['type_sname'].'/'.$p_res_row['lct_caseno'].'/'.$p_res_row['lct_caseyear'].' ]';
                                                        echo "[".$p_res_row['caseno']."]";
                                                    }
                                                    if($p_res_row['pflag']=='O' || $p_res_row['pflag']=='D') echo " [".$p_res_row['remark_del']."]";
                                                    ?></td></tr>
                                                    <?php
                                                    }
                                                }
                                                ?>
                                                </table>
                                                <table width="100%" border="1" style="border-collapse: collapse" id="table_show" cellpadding="5">
                                                    <thead>
                                                <tr><th colspan="2">Impeading Parties [with Lower Case]</th></tr>
                                            </thead>
                                                <?php
                                            
                                                if(!empty($p_imp_rs))
                                                {
                                                    foreach($p_imp_rs as $p_imp_row)
                                                    {
                                                    ?>
                                                    <tr><td align="center" style="width:10px"><?php /*if($p_res_row['sr_no']==1){echo '1';}else*/{?>
                                                    <!--<input class="form-control" type="button" value="<?php //echo $p_res_row['sr_no']?>" style="width:25px;text-align: center" onclick="setPartiesinField(this.value,'R','<?php //echo $p_res_row['ind_dep'];?>')"/><?php //}?>-->
                                                    <?php if($p_imp_row['pflag']=='O' || $p_imp_row['pflag']=='D') { echo $p_imp_row['sr_no_show']; } else {?>
                                                    <input  type="button" value="<?php echo $p_imp_row['sr_no_show']?>" name="ExMod_I_<?php echo $p_imp_row['sr_no_show'].'_'.$p_imp_row['ind_dep']; ?>"  /><?php }?>
                                                    <?php }?></td>
                                                    <td style="<?php if($p_imp_row['pflag']=='O') echo "color:red"; else if($p_imp_row['pflag']=='D') echo "color:#9932CC"; ?>"><?php echo $p_imp_row['partyname'];
                                                    if($p_imp_row['remark_lrs']!='' || $p_imp_row['remark_lrs']!=NULL)
                                                        echo "[".$p_imp_row['remark_lrs']."]";
                                                    if($p_imp_row['lct_casetype'] !='' || $p_imp_row['lct_casetype'] != NULL){
                                                        //echo " [ ".$p_imp_row['type_sname'].'/'.$p_imp_row['lct_caseno'].'/'.$p_imp_row['lct_caseyear'].' ]';
                                                        echo "[".$p_imp_row['caseno']."]";
                                                    }
                                                    if($p_imp_row['pflag']=='O' || $p_imp_row['pflag']=='D') echo " [".$p_imp_row['remark_del']."]";
                                                    ?></td></tr>

                                                    <?php
                                                    }
                                                }
                                                ?>
                                                <table width="100%" border="1" style="border-collapse: collapse" id="table_show" cellpadding="5">
                                                    <thead>
                                                        <tr><th colspan="2">Intervenor Parties [with Lower Case]</th></tr>
                                                    </thead>
                                                <?php
                                                
                                                
                                                if(!empty($p_int_rs))
                                                {
                                                    foreach($p_int_rs as $p_int_row)
                                                    {
                                                    ?>
                                                    <tr><td align="center" style="width:10px"><?php /*if($p_res_row['sr_no']==1){echo '1';}else*/{?>
                                                    <!--<input class="form-control" type="button" value="<?php //echo $p_res_row['sr_no']?>" style="width:25px;text-align: center" onclick="setPartiesinField(this.value,'R','<?php //echo $p_res_row['ind_dep'];?>')"/><?php //}?>-->
                                                    <?php if($p_int_row['pflag']=='O' || $p_int_row['pflag']=='D') { echo $p_int_row['sr_no_show']; } else {?>
                                                    <input  type="button" value="<?php echo $p_int_row['sr_no_show']?>" name="ExMod_N_<?php echo $p_int_row['sr_no_show'].'_'.$p_int_row['ind_dep']; ?>"  /><?php }?>
                                                    <?php }?></td>
                                                    <td style="<?php if($p_int_row['pflag']=='O') echo "color:red"; else if($p_int_row['pflag']=='D') echo "color:#9932CC"; ?>"><?php echo $p_int_row['partyname'];
                                                    if($p_int_row['remark_lrs']!='' || $p_int_row['remark_lrs']!=NULL)
                                                        echo "[".$p_int_row['remark_lrs']."]";
                                                    if($p_int_row['lct_casetype'] !='' || $p_int_row['lct_casetype'] != NULL){
                                                        //echo " [ ".$p_imp_row['type_sname'].'/'.$p_imp_row['lct_caseno'].'/'.$p_imp_row['lct_caseyear'].' ]';
                                                        echo "[".$p_int_row['caseno']."]";
                                                    }
                                                    if($p_int_row['pflag']=='O' || $p_int_row['pflag']=='D') echo " [".$p_int_row['remark_del']."]";
                                                    ?></td></tr>

                                                    <?php
                                                    }
                                                }
                                                ?>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                                <table style="margin-left: auto;margin-right: auto;border: 1px solid black" cellpadding="10">
                                    <tr><th colspan="2">EARLIER COURT-WISE DETAILS</th></tr>
                                    <tr>
                                        <td style="width: 50%">
                                            <?php
                                            
                                            
                                            if(!empty($result_p)){
                                                ?>
                                            <table border="1" style="border-collapse: collapse">
                                                <tr ><th colspan="4">Petitioner</th></tr>
                                                <tr ><th>Earlier No</th><th>SNo.</th><th>No</th><th>Partyname</th></tr>
                                            <?php
                                            $lowercourt_id=''; $ser_no=1;
                                            foreach($result_p as $row){
                                                if($lowercourt_id!=$row['lowercase_id'])
                                                    $ser_no=1;
                                                else
                                                    $ser_no++;
                                                ?>
                                                <tr>
                                                    <?php
                                                    if($lowercourt_id!=$row['lowercase_id']){

                                                    ?>
                                                    <td <?php if($lowercourt_id!=$row['lowercase_id']) echo "rowspan=$row[nos]";?>>
                                                        <?php echo $row['type_sname'].'/'.$row['lct_caseno'].'/'.$row['lct_caseyear']; ?></td>
                                                    <?php } ?>
                                                    <td><?php echo $ser_no;?></td>
                                                    <td><?php echo $row['sr_no_show']; ?></td>
                                                    <td style="<?php if($row['pflag']=='O') echo "color:red"; else if($row['pflag']=='D') echo "color:#9932CC"; ?>">
                                                        <?php echo $row['partyname']; ?></td>
                                                </tr>
                                                    <?php
                                                $lowercourt_id = $row['lowercase_id'];
                                                
                                            }
                                            ?>
                                            </table>
                                                    <?php
                                            }
                                            ?>
                                        </td>
                                        <td style="width: 50%">
                                            <?php
                                            
                                            
                                            if(!empty($result_r)){
                                                ?>
                                            <table border="1" style="border-collapse: collapse">
                                                <tr ><th colspan="4">Respondent</th></tr>
                                                <tr ><th>Earlier No</th><th>SNo.</th><th>No</th><th>Partyname</th></tr>
                                            <?php
                                            $lowercourt_id=''; $ser_no=1;
                                            foreach($result_r as $row){
                                                if($lowercourt_id!=$row['lowercase_id'])
                                                    $ser_no=1;
                                                else
                                                    $ser_no++;
                                                ?>
                                                <tr>
                                                    <?php
                                                    if($lowercourt_id!=$row['lowercase_id']){

                                                    ?>
                                                    <td <?php if($lowercourt_id!=$row['lowercase_id']) echo "rowspan=$row[nos]";?>>
                                                        <?php echo $row['type_sname'].'/'.$row['lct_caseno'].'/'.$row['lct_caseyear']; ?></td>
                                                    <?php } ?>
                                                    <td><?php echo $ser_no;?></td>
                                                    <td><?php echo $row['sr_no_show']; ?></td>
                                                    <td style="<?php if($row['pflag']=='O') echo "color:red"; else if($row['pflag']=='D') echo "color:#9932CC"; ?>">
                                                    <?php echo $row['partyname']; ?></td>
                                                </tr>
                                                    <?php
                                                $lowercourt_id = $row['lowercase_id'];
                                                
                                            }
                                            ?>
                                            </table>
                                                    <?php
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                </table>
                                <?php }
                                else
                                {?>
                                <table align="center"><tr><th style="color:red">Record Not Found!!!</th></tr></table>
                                <?php
                                }?> 
                        </form>    
                        </div>
                            <!-- /.card -->
                        </div>
                    </div>


                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
</section>
<!-- /.content -->


<script>

$(document).ready(function() {



 

$('#get_court_details').append('<hr />');
$('.aftersubm').append('<hr />');
$('.befSubmt').prepend('<hr />');


$('#sp_add_add').click(function() {
    $('#add_adres').show()

    $('#st_1').change(function() {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $('#dis_1').val('');

        var get_state_id = $(this).val();
        if (get_state_id != '') {
            $.ajax({
                type: "GET",
                data: {
                    CSRF_TOKEN: CSRF_TOKEN_VALUE,
                    state_id: get_state_id
                },
                url: "<?php echo base_url('Common/Ajaxcalls/get_districts'); ?>",
                success: function(data) {
                    $('#dis_1').html(data);
                    updateCSRFToken();
                },
                error: function() {
                    updateCSRFToken();
                }
            });
        }
    });

})

// pri_action
$("#pri_action").change(function() {
    let valOpt = $("option:selected", this).val();
    // alert(valOpt)
    if (valOpt == 'L') {
        $('.selectLR').show()
    } else {
        $('.selectLR').hide()
    }
})

$('#enable_party').click(function() {
    if ($('#enable_party').val() == 'ENABLE PARTY NO.') {
        $('#pno').removeAttr('disabled', true)
        $('#enable_party').val('DISABLE PARTY NO.')
    } else {
        $('#pno').attr('disabled', true)
        $('#enable_party').val('ENABLE PARTY NO.')
    }
})

$('#party_flag').change(function() {
    let valOpt = $("option:selected", this).val();
    // if (valOpt == 'P') {
    //     $('#p_cntpro').attr('disabled', true)
    // } else {
    //     $('#p_cntpro').removeAttr('disabled', true)
    // }
})


$('#update_party_type').change(function() {
    updateCSRFToken();
    let valOpt = $("option:selected", this).val();
   

    var CSRF_TOKEN = 'CSRF_TOKEN';
    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

    $.ajax({
        type: "POST",
        data: {
            CSRF_TOKEN: CSRF_TOKEN_VALUE,
            deptt: valOpt
        },
        url: "<?php echo base_url('Filing/Party/getDepttList'); ?>",
        success: function(data) {
            data = JSON.parse(data)
            // console.log(data)
            if (data.length) {
                let html = ''
                data.forEach(el => {
                    html += '<option value="' + el.value + '">'
                })
                $('#pDepttList').append(html)
            } else {
                $('#pDepttList').html('')
            }
            updateCSRFToken();

        },
        error: function() {
            updateCSRFToken();
        }
    });
})



//----------Get District List----------------------//
$('#p_st').change(function() {
    var CSRF_TOKEN = 'CSRF_TOKEN';
    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
    $('#p_dis').val('');

    var get_state_id = $(this).val();
    if (get_state_id != '') {
        $.ajax({
            type: "GET",
            data: {
                CSRF_TOKEN: CSRF_TOKEN_VALUE,
                state_id: get_state_id
            },
            url: "<?php echo base_url('Common/Ajaxcalls/get_districts'); ?>",
            success: function(data) {
                $('#p_dis').html(data);
                updateCSRFToken();
            },
            error: function() {
                updateCSRFToken();
            }
        });
    }
});


});


function activate_extra(value)
{

    if(value=="I")
    {
        document.getElementById('p_post').value="";
        document.getElementById('p_deptt').value="";
        document.getElementById('p_statename').value="";
        document.getElementById('for_I_1').style.display='table-row';
        document.getElementById('for_I_2').style.display='table-row';
        document.getElementById('for_I_3').style.display='table-row';
        document.getElementById('for_I_4').style.display='table-row';
        document.getElementById('tr_d').style.display='none';
        document.getElementById('tr_d0').style.display='none';
        //document.getElementById('tr_for_individual').style.display='visible';
        document.getElementById('tr_for_individual').style.display='table-row';
        document.getElementById('span_mask_name').style.display='none';
        //document.getElementById('tr_d1').style.display='none';
        document.getElementById('mask_check').checked=false;
    }
    else if(value!="I")
    {
        
        document.getElementById('update_p_name').value="";
        document.getElementById('update_p_rel').value="";
        document.getElementById('update_p_rel_name').value="";
        document.getElementById('update_p_sex').value="";
        document.getElementById('update_p_age').value="";
        document.getElementById('update_p_occ').value="";
        document.getElementById('update_p_caste').value="";
        document.getElementById('update_p_edu').value="";
        document.getElementById('for_I_1').style.display='none';
        document.getElementById('for_I_2').style.display='none';
        document.getElementById('for_I_3').style.display='none';
        document.getElementById('for_I_4').style.display='none';
        document.getElementById('tr_d').style.display='table-row';
        document.getElementById('tr_for_individual').style.display='none';
        if(value=='D3')
            document.getElementById('tr_d0').style.display='none';
        else
            document.getElementById('tr_d0').style.display='table-row';
        //document.getElementById('tr_d0').style.display='table-row';
        /*if(value=='D1')
            document.getElementById('tr_d1').style.display='table-row';
        else
        {
            document.getElementById('tr_d1').style.display='none';
            document.getElementById('state_department_in').value='';
        }*/
    }
}
function isFloat(n) {
        return Number(n) === n && n % 1 !== 0;
    }

    function onlynumbers(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        // alert(charCode);
        if ((charCode >= 48 && charCode <= 57) || charCode == 9 || charCode == 8 || charCode == 37 || charCode == 39 || charCode == 46) {
            return true;
        }
        return false;
    }

    function onlyalpha(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        //alert(charCode);
        if ((charCode >= 65 && charCode <= 90) || (charCode >= 97 && charCode <= 122) || charCode == 9 || charCode == 8 ||
            charCode == 127 || charCode == 32 || charCode == 46 || charCode == 47 || charCode == 64 || charCode == 37 || charCode == 39) {
            return true;
        }
        return false;
    }
function onlyalphabnum(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        // alert(charCode);
        if ((charCode >= 48 && charCode <= 57) || (charCode >= 65 && charCode <= 90) || (charCode >= 48 && charCode <= 57) || (charCode >= 97 && charCode <= 122) || charCode == 9 || charCode == 8 ||
            charCode == 127 || charCode == 32 || charCode == 46 || charCode == 47 || charCode == 64 || charCode == 40 || charCode == 41 ||
            charCode == 37 || charCode == 39 || charCode == 44) {
            return true;
        }
        return false;
    }

    function onlyalphab(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        //alert(charCode);
        if ((charCode >= 65 && charCode <= 90) || (charCode >= 97 && charCode <= 122) || (charCode >= 48 && charCode <= 57) ||
            charCode == 9 || charCode == 8 || charCode == 127 || charCode == 32 || charCode == 46 || charCode == 47 || charCode == 64 ||
            charCode == 40 || charCode == 41 || charCode == 37 || charCode == 39 || charCode == 44) {
            return true;
        }
        return false;
    }

    function remove_apos(value, id) {
         var string = value.replace("'", "");
         string = string.replace("#", "No");
         string = string.replace("&", "and");
         $("#" + id).val(string);
    }

function call_save_extra()
{
   
    var party_type = document.getElementById('update_party_type').value;
    var party_flag = document.getElementById('party_flag_update');
    var party_no=document.getElementById('pno1').innerHTML;
    
    var p_name,p_rel,p_rel_name,p_sex,p_age,p_post,p_deptt,p_occ,p_edu,p_masked_name;
 
    if(party_flag.value=="")
    {
        alert('Please Select Party Type');party_flag.focus();return false;
    }
    if(party_type=="I")
    {
        p_name = document.getElementById('update_p_name');
        p_rel = document.getElementById('update_p_rel');
        p_rel_name = document.getElementById('update_p_rel_name');
        p_sex = document.getElementById('update_p_sex');
        p_age = document.getElementById('update_p_age');
        p_occ = document.getElementById('update_p_occ');
        p_edu = document.getElementById('update_p_edu');
        if(document.getElementById('mask_check').checked){
            if(document.getElementById('masked_name').value!='') {
                p_masked_name=document.getElementById('masked_name').value;
                alert(" Party name is masked. Dealing Assitant may take further action for masking of party details in ROP or Judgments accordingly!!!!");
            }
            else{
                alert('Please Enter Masked Name');masked_name.focus();return false;
            }
        }
        if(p_name.value=='')
        {
            alert('Please Enter Party Name');p_name.focus();return false;
        }
        
    }
    if(party_type!="I")
    {
        p_statename = document.getElementById('p_statename');
        p_post = document.getElementById('p_post');
        p_deptt = document.getElementById('p_deptt');

         
        if(p_statename.value=='' && document.getElementById('s_causetitle').checked )
        {
            alert('Please Enter State Name');p_statename.focus();return false;
        }
		
		if(p_post.value=='' &&   document.getElementById('p_causetitle').checked )
        {
            alert('Please Enter Party Post');p_post.focus();return false;
        }
       
        if(p_deptt.value=='' &&  document.getElementById('d_causetitle').checked )
        {
            alert('Please Enter Party Department');p_deptt.focus();return false;
        }
    }
    if(document.getElementById('update_p_add').value=="")
    {
        alert('Please Enter Party Address');document.getElementById('update_p_add').focus();return false;
    }
    if(document.getElementById('update_p_city').value=="")
    {
        alert('Please Enter Party City');document.getElementById('update_p_city').focus();return false;
    }
    if(document.getElementById('p_cont_update').value=="96"){
        if(document.getElementById('update_p_st').value=="")
        {
            alert('Please Enter Party State');document.getElementById('update_p_st').focus();return false;
        }
        if(document.getElementById('p_dis1').value=="")
        {
            alert('Please Enter Party District');document.getElementById('p_dis1').focus();return false;
        }
    }
    if(document.getElementById('p_cont_update').value=="")
    {
        alert('Please Enter Party Country');document.getElementById('p_cont_update').focus();return false;
    }
    if(document.getElementById('p_email').value!='')
    {
        var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
        if(document.getElementById('p_email').value.match(mailformat))
        {
            //return true;
        }
        else
        {
            alert('Please enter valid email');
            document.getElementById('p_email').focus();
            return false;
        }
    }
     

    if(document.getElementById("lower_case").value == "" && (document.getElementById("hd_allow_user").value!=1 && document.getElementById("hd_casetype").value!=5 && document.getElementById("hd_casetype").value!=6 && document.getElementById("hd_casetype").value!=24  && document.getElementById("hd_casetype").value!=17 && document.getElementById("hd_casetype").value!='22' && document.getElementById("hd_casetype").value!='27' && document.getElementById("hd_casetype").value!='34'  && document.getElementById("hd_casetype").value!='35' && document.getElementById("hd_casetype").value!='37' && document.getElementById("hd_casetype").value!='36' && document.getElementById("hd_casetype").value!='38'  && document.getElementById("hd_casetype").value!='32' && document.getElementById("hd_casetype").value!='33'))
    {
        alert('Please Select Lower Court Case');
        lower_case.focus();return false;
    }
    var remark_del='';
    if($("#p_status").val()=='O' || $("#p_status").val()=='D'){
        remark_del = $("#remark_delete").val();
        if(remark_del==''){
            alert('Please Enter Remark for Deletion/Disposal of Party');
            $("#remark_delete").focus();
            return false;
        }
    }

    var remark_lrs = $("#remark_lrs").val();
    if($("#pri_action1").val()=='L'){
        if(remark_lrs==''){
            alert('Please Enter Remarks for Adding LRs');
            $("#remark_lrs").focus();
            return false;
        }
    }

    var add_add_count = $("#hd_add_add_count").val();
    var add_addresses = '';
    if(add_add_count>0){
        for(var i=1;i<=add_add_count;i++){
            if($("#add-add_table_"+i)){
                if($("#add_"+i).val()==''){
                    alert('Please Fill this Additional Address');
                    $("#add_"+i).focus();
                    return false;
                }
                if($("#cont_"+i).val()=='96'){
                    if($("#st_"+i).val()==''){
                        alert('Please Select Additional Address State');
                        $("#st_"+i).focus();
                        return false;
                    }
                    if($("#dis_"+i).val()==''){
                        alert('Please Select Additional Address District');
                        $("#dis_"+i).focus();
                        return false;
                    }
                }
                if($("#add_"+i).length > 0)
                    add_addresses = add_addresses+"^"+$("#add_"+i).val()+"~"+$("#cont_"+i).val()+"~"+$("#st_"+i).val()+"~"+$("#dis_"+i).val();
            }
        }
    }

    if($("#party_flag")!='I'){
        if($("#update_order1").val() == $("#update_order2").val() == $("#update_order3").val()){
            alert('All Orders Can not be same');
            return false;
        }
        else{
            if(($("#update_order1").val() == $("#update_order2").val())&& ($("#update_order1").val()!='' && $("#update_order2").val()!='')){
                alert('Order1 and Order2 Can not be same **** !!! ');
                return false;
            }
            else if(($("#update_order2").val() == $("#update_order3").val())&& ($("#update_order2").val()!='' && $("#update_order3").val()!='')){
                alert('Order2 and Order3 Can not be same');
                return false;
            }
            else if(($("#update_order1").val() == $("#update_order3").val())&& ($("#update_order1").val()!='' && $("#update_order3").val()!='')){
                alert('Order1 and Order3 Can not be same');
                return false;
            }
        }
    }

    /* alert("Registry is directed not to accept any application or petition on behalf of:- \n 1) Suraj India Trust or Mr. Rajiv Daiya as per Hon'ble Court Order dated. 08-02-2018 in MA no. 1158/2017(Suraj India Trust Vs UOI) \n 2) ASOK PANDE as per Hon'ble Court Order dated. 26-10-2018 in WP(C) No. 965/2018 (ASOK PANDE Vs UOI) \n 3) MANOHAR LAL SHARMA  as per Hon'ble Court Order dated. 07-12-2018 in WP(CRL) No. 315/2018 (MANOHAR LAL SHARMA Vs ARUN JAITLEY (AT PRESENT FINANCE MINISTER))\n" +
        " 4) P1-SURAJ MISHRA and P2-ROHIT GUPTA  as per Hon'ble Court Order dated. 08-05-2019 in WP(C) No. 1328/2018 (SURAJ MISHRA AND ANR VS. UNION OF INDIA AND ANR)");
 */
    //return false;
    var xmlhttp;
    if (window.XMLHttpRequest)
    {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
    }
    else
    {// code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    
	xmlhttp.onreadystatechange=function()
    {
        if(xmlhttp.readyState==4 && xmlhttp.status==200)
        {

            var res = xmlhttp.responseText;
            //alert(res);
            if(res=="1"){
                alert('Please add more parties before deleting this party');
            }
            res = res.split('!~!');
            document.getElementById('result2').innerHTML=res[1];
            document.getElementById('table_show').innerHTML="Please Wait While We Are Fatching Parties Information Again";
            call_fullReset_extra();
            call_fetch_infoAgain(document.getElementById('hdfno_update').value);
            call_fetch_causetitle(document.getElementById('hdfno_update').value);
            
		}
    }
    var url = base_url+"/Filing/Party/save_new_filing_extraparty?controller=U&fno="+document.getElementById('hdfno_update').value
        +"&p_f="+party_flag.value+"&hd_p_f="+document.getElementById('hd_party_flag_update').value;
        p_post
    if(party_type=="I")
        url = url+"&p_type="+party_type+"&p_name="+p_name.value+"&p_rel="+p_rel.value+"&p_rel_name="+p_rel_name.value
            +"&p_sex="+p_sex.value+"&p_age="+p_age.value+"&p_occ="+document.getElementById('update_p_occ').value
            +"&p_edu="+document.getElementById('update_p_edu').value+"&p_caste="+document.getElementById('update_p_caste').value
            +"&p_occ_code="+document.getElementById('p_occ_hd_code').value+"&p_edu_code="+document.getElementById('p_edu_hd_code').value
            +"&p_masked_name="+document.getElementById('masked_name').value     ;

    if($("#p_statename").val()=='')
        $("#p_statename_hd").val('0');

    if(party_type!="I")
        url = url+"&p_type="+party_type+"&p_post="+p_post.value+"&p_deptt="+p_deptt.value+"&p_statename="+$("#p_statename").val()
            +"&p_statename_hd="+$("#p_statename_hd").val()+"&d_code="+$("#p_deptt_hd").val()+"&p_code="+document.getElementById('post_code').value;

    url = url + "&p_add=" + document.getElementById('update_p_add').value + "&p_city=" + document.getElementById('update_p_city').value
        + "&p_pin=" + document.getElementById('p_pin').value + "&p_dis=" + document.getElementById('p_dis1').value
        + "&p_st=" + document.getElementById('update_p_st').value + "&p_cont=" + document.getElementById('p_cont_update').value + "&p_mob=" + document.getElementById('p_mob').value
        + "&p_email=" + document.getElementById('p_email').value + "&p_no=" + document.getElementById('pno1').innerHTML
        + "&p_sta=" + document.getElementById('p_status').value + "&lowercase=" + $('#lower_case').val() + "&remark_lrs=" + remark_lrs + "&remark_del=" + remark_del
        + "&add_add=" + add_addresses + "&cont_pro_info=" + $("#update_p_cntpro").val();

    url = url+"&order1="+$("#update_order1").val()+"&order2="+$("#update_order2").val()+"&order3="+$("#update_order3").val();
    if(party_type!="I") {
        s_ct=document.getElementById('s_causetitle').checked;
        d_ct=document.getElementById('d_causetitle').checked;
        p_ct=document.getElementById('p_causetitle').checked;
        
        url=url+"&s_causetitle="+s_ct
            +"&d_causetitle="+d_ct
            +"&p_causetitle="+p_ct+"&party_name="+document.getElementById('party_name').value
    }
    
    // url = encodeURI(url);
    xmlhttp.open("GET",url,true);
    xmlhttp.responseType = 'text';

    xmlhttp.onload = function() {
        if (xmlhttp.status === 200) {
            let responseText = xmlhttp.responseText;
            alert(responseText);
            location.reload();
        } else {
            console.error('Request failed.  Returned status of ' + xmlhttp.status);
        }
    };
    xmlhttp.send(null);
}


$(document).on("click","[name^='ExMod_']",function(){
    //alert('sd');
    var num8 = this.name.split('ExMod_');
    //alert(num[1]);
    var num = num8[1].split('_');
	var CSRF_TOKEN = 'CSRF_TOKEN';
	var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
	 
    $.ajax({
        type: 'POST',
        url: base_url+"/Filing/Party/get_extraparty_info",
        beforeSend: function (xhr) {
            //$("#result1").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='../images/load.gif'></div>");
        },
        data:{CSRF_TOKEN: CSRF_TOKEN_VALUE,fno:$("#hdfno").val(),id:num[1],flag:num[0],type:num[2]}
    })
        .done(function(msg){
			updateCSRFToken();
            //alert(msg);
            //$("#result1").html(msg);
            msg = msg.split('~');
            console.log(msg);
            /*alert(msg[21]);
            alert(msg[22])
            for(i=0;i<msg.length;i++){
                alert(i+'  '+msg[i]);
            } */
            if(msg[22]==0)
                msg[22]='';
            if(num[1].indexOf('.')>0){
                $("#pri_action1").html("<option value='L'>LR's</option>");
                $("#for_selecting_lrs").css('display','inline');
                //$("#remark_lrs_row").css('display','table-row');
                var newNum = num[1].split('.');
                $("#for_selecting_lrs").val(num[0]+'~'+newNum[0]+'~'+num[1]);
                //alert(newNum.length);
                /*if(newNum.length==3){
                    //var newNum2 = newNum[1].split('.');
                    $("#sel_lrstolrs").val(num[0]+'~'+newNum[0]+'~'+num[1]);
                }
                else
                    $("#sel_lrstolrs").val("");*/
            }
            else{
                $("#pri_action1").html("<option value='P'>Party</option>");
                $("#for_selecting_lrs").css('display','none');
                //$("#remark_lrs_row").css('display','none');
                //$("#sel_lrstolrs").val("");
            }
            
           
            if(num[2] == 'I' )
            {   
                activate_extra(num[2]);
                document.getElementById('update_party_type').value = num[2].trim();
                if(num[0]=='P')
                    document.getElementById('party_flag_update').innerHTML="<option value='P' selected>Petitioner</option><option value='R'>Respondent</option><option value='I'>Impleading</option><option value='N'>Intervenor</option>";
                else if(num[0]=='R')
                    document.getElementById('party_flag_update').innerHTML="<option value='P'>Petitioner</option><option value='R' selected>Respondent</option><option value='I'>Impleading</option><option value='N'>Intervenor</option>";
                else if(num[0]=='I')
                    document.getElementById('party_flag_update').innerHTML="<option value='P'>Petitioner</option><option value='R'>Respondent</option><option value='I' selected>Impleading</option><option value='N'>Intervenor</option>";
                else if(num[0]=='N')
                    document.getElementById('party_flag_update').innerHTML="<option value='P'>Petitioner</option><option value='R'>Respondent</option><option value='I'>Impleading</option><option value='N' selected>Intervenor</option>";

                document.getElementById('hd_party_flag_update').value=num[0];
                document.getElementById('pno1').innerHTML=num[1];



                document.getElementById('update_p_rel').value=msg[1].trim();
                document.getElementById('update_p_rel_name').value=msg[3].trim();
                document.getElementById('update_p_sex').value=msg[5];
                document.getElementById('update_p_age').value=msg[4];
                document.getElementById('update_p_caste').value=msg[6];
                document.getElementById('update_p_occ').value=msg[7];
                document.getElementById('p_occ_hd_code').value=msg[18];
                document.getElementById('update_p_edu').value=msg[17];
                document.getElementById('p_edu_hd_code').value=msg[19];
                document.getElementById('update_p_add').value=msg[8];
                document.getElementById('update_p_city').value=msg[14];
                document.getElementById('p_cont_update').value=msg[15];
                document.getElementById('update_p_st').value=msg[9];
                getDistrict(msg[9]);
                document.getElementById('p_dis1').value=msg[10];
                if(msg[15]!='96'){
                    $('#update_p_st').prop('disabled',true);
                    $('#p_dis1').prop('disabled',true);
                }
                else{
                    $('#update_p_st').removeProp('disabled');
                    $('#p_dis1').removeProp('disabled');
                }
                document.getElementById('p_pin').value=msg[11];
                document.getElementById('p_mob').value=msg[13];
                document.getElementById('p_email').value = msg[12];
                //alert(msg[12]);
                if(msg[29].trim()!=''){

                    document.getElementById('mask_check').checked=true;
                    document.getElementById('update_p_name').value=msg[29].trim();
                    //document.getElementById('span_mask_name').style.display='block';
                    document.getElementById('span_mask_name').style.display='inline-block';
                    document.getElementById('masked_name').value=msg[0];
                    document.getElementById('tr_for_individual').style.display='inline-block';
                    document.getElementById('tr_for_individual').style.display='table-row';
                }
                else{
                    document.getElementById('update_p_name').value=msg[0];
                    document.getElementById('tr_for_individual').style.display='inline-block';
                    document.getElementById('tr_for_individual').style.display='table-row';
                    document.getElementById('mask_check').checked=false;
                    document.getElementById('masked_name').value='';
                    document.getElementById('span_mask_name').style.display='none';


                }
                $("#lower_case option").each(function()
                {
                    $(this).removeProp('selected');
                });
                var lowerids = msg[22].split(',');
                //alert(lowerids.length);

                for(var i=0;i<lowerids.length;i++){
                    $("#lower_case option").each(function()
                    {
                        // Add $(this).val() to your list
                        if($(this).val()==lowerids[i])
                            $(this).prop('selected', true);
                    });

                }
                //document.getElementById('lower_case').value=msg[22];
                document.getElementById('svbtn').disabled=false;
                document.getElementById('rstbtn').disabled=false;
            }
            else
            {
                
                document.getElementById('tr_for_individual').style.display='none';

                activate_extra(num[2]);
                document.getElementById('update_party_type').value=num[2];
                if(num[0]=='P')
                    document.getElementById('party_flag_update').innerHTML="<option value='P' selected>Petitioner</option><option value='R'>Respondent</option><option value='I'>Impleading</option><option value='N'>Intervenor</option>";
                else if(num[0]=='R')
                    document.getElementById('party_flag_update').innerHTML="<option value='P'>Petitioner</option><option value='R' selected>Respondent</option><option value='I'>Impleading</option><option value='N'>Intervenor</option>";
                else if(num[0]=='I')
                    document.getElementById('party_flag_update').innerHTML="<option value='P'>Petitioner</option><option value='R'>Respondent</option><option value='I' selected>Impleading</option><option value='N'>Intervenor</option>";
                else if(num[0]=='N')
                    document.getElementById('party_flag_update').innerHTML="<option value='P'>Petitioner</option><option value='R'>Respondent</option><option value='I'>Impleading</option><option value='N' selected>Intervenor</option>";

                document.getElementById('hd_party_flag_update').value=num[0];
                document.getElementById('pno1').innerHTML=num[1];
                /*  if(num[1]!=1) {
                      document.getElementById('s_causetitle').disabled = true;
                      document.getElementById('d_causetitle').disabled = true;
                      document.getElementById('p_causetitle').disabled = true;
                  }
                  else{
                      document.getElementById('s_causetitle').disabled = false;
                      document.getElementById('d_causetitle').disabled = false;
                      document.getElementById('p_causetitle').disabled = false;
                  } */
                //--to edit

                
                document.getElementById('party_name').value=msg[0];
                document.getElementById('p_post').value=msg[7];
                document.getElementById('post_code').value=msg[2];
                document.getElementById('p_deptt').value=msg[20];
                document.getElementById('p_deptt_hd').value=msg[16];

                document.getElementById('p_statename').value=msg[24];
                document.getElementById('p_statename_hd').value=msg[23];

                document.getElementById('update_p_add').value=msg[8];
                document.getElementById('update_p_city').value=msg[14];
                document.getElementById('p_cont_update').value=msg[15];
                document.getElementById('update_p_st').value=msg[9];
                getDistrict(msg[9]);
                document.getElementById('p_dis1').value=msg[10];
                document.getElementById('p_pin').value=msg[11];
                document.getElementById('p_mob').value=msg[13];
                document.getElementById('p_email').value=msg[12];
                document.getElementById('s_causetitle').checked=false;
                document.getElementById('d_causetitle').checked=false;
                document.getElementById('p_causetitle').checked=false;
                $("#lower_case option").each(function()
                {
                    $(this).removeProp('selected');
                });
                var lowerids = msg[22].split(',');
                //alert(lowerids.length);
                for(var i=0;i<lowerids.length;i++){
                    $("#lower_case option").each(function()
                    {
                        // Add $(this).val() to your list
                        if($(this).val()==lowerids[i])
                            $(this).prop('selected', true);

                    });

                }
                //document.getElementById('lower_case').value=msg[22].trim();
                document.getElementById('svbtn').disabled=false;
                document.getElementById('rstbtn').disabled=false;
                if(msg[15]!='96'){
                    $('#update_p_st').prop('disabled',true);
                    $('#p_dis1').prop('disabled',true);
                }
                else{
                    $('#update_p_st').removeProp('disabled');
                    $('#p_dis1').removeProp('disabled');
                }
            }
            //alert(msg[28]);
            //$("#p_cntpro").val(msg[28]);
            document.getElementById('update_p_cntpro').value=msg[28].trim();
            if(num[0]=='P')
                $("#update_p_cntpro").prop('disabled',true);
            else
                $("#update_p_cntpro").removeProp('disabled');

            document.getElementById('remark_lrs').value=msg[25].trim();
            document.getElementById('hd_add_add_count').value=msg[26];
            $("#extra_address").html("");
            if(msg[26]>0){
                //var count = parseInt($("#hd_add_add_count").val())+1;
                $.ajax({
                    type: 'POST',
                    url:"./set_add_add_fields.php",
                    data:{id:msg[27]}
                })
                    .done(function(msg){
                        //alert(msg);
                        $("#extra_address").append(msg);
                        //$("#hd_add_add_count").val(count);
                    })
                    .fail(function(){
                        alert("ERROR, Please Contact Server Room");
                    });
            }

        })
        .fail(function(){
			updateCSRFToken();
            alert("ERROR, Please Contact Server Room");
        });
});


function call_fullReset_extra()
{
    if(document.getElementById('update_party_type').value=='I')
    {
        document.getElementById('update_p_name').value="";
        document.getElementById('update_p_rel').value="";
        document.getElementById('update_p_rel_name').value="";
        document.getElementById('update_p_sex').value="";
        document.getElementById('update_p_age').value="";
        document.getElementById('update_p_caste').value="";
        document.getElementById('update_p_occ').value="";
        document.getElementById('update_p_edu').value="";
        document.getElementById('mask_check').checked=false;
        document.getElementById('masked_name').value="";
        document.getElementById('span_mask_name').style.display='none';

    }
    else if(document.getElementById('update_party_type').value!='I')
    {
        document.getElementById('p_post').value="";
        document.getElementById('p_deptt').value="";
        document.getElementById('s_causetitle').checked=false;
        document.getElementById('d_causetitle').checked=false;
        document.getElementById('p_causetitle').checked=false;
        document.getElementById('tr_for_individual').style.display='none';

    }
    document.getElementById('party_flag').innerHTML="<option value=''>Select</option>";
    document.getElementById('update_party_type').value="I";
    document.getElementById('update_p_add').value="";
    document.getElementById('update_p_city').value="";
    document.getElementById('p_pin').value="";
    document.getElementById('p_dis1').innerHTML="<option value=''>Select</option>";
    document.getElementById('p_cont_update').value="96";
    document.getElementById('update_p_st').value="";
    document.getElementById('p_mob').value="";
    document.getElementById('p_email').value="";
    document.getElementById('pno1').innerHTML="";
    document.getElementById('for_I_1').style.display='table-row';
    document.getElementById('for_I_2').style.display='table-row';
    document.getElementById('for_I_3').style.display='table-row';
    document.getElementById('for_I_4').style.display='table-row';
    document.getElementById('tr_d').style.display='none';
    document.getElementById('tr_d0').style.display='none';
    document.getElementById('p_status').value="P";
    document.getElementById('svbtn').disabled=true;
    document.getElementById('rstbtn').disabled=true;
    document.getElementById('lower_case').value="";
    //$("#sel_lrstolrs").val("");
    $("#for_selecting_lrs").css('display','none');
    //$("#remark_lrs_row").css('display','none');
    $("#remark_delete").prop('disabled',true);
    $("#remark_delete").val("");
    $("#remark_lrs").val("");
    //document.getElementById('tr_d1').style.display='none';
    //document.getElementById('state_department_in').value='';
    if($("#hd_add_add_count").val()>0){
        for(var i=1;i<=$("#hd_add_add_count").val();i++){
            if($("#add-add_table_"+i)){
                $("#add-add_table_"+i).remove();
                $("#hr_"+i).remove();
            }
        }
        $("#hd_add_add_count").val(0);
    }
}

function getDistrict(val){
    var xmlhttp;
    if (window.XMLHttpRequest)
    {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
    }
    else
    {// code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange=function()
    {
        if (xmlhttp.readyState==4 && xmlhttp.status==200)
        {
            document.getElementById('p_dis1').innerHTML=xmlhttp.responseText;
        }
    }
    
    xmlhttp.open("GET", base_url+"/Common/Ajaxcalls/get_districts?state_id="+val,false);
    xmlhttp.send(null);
}

function f1(){
    if(document.getElementById('mask_check').checked){
        document.getElementById('masked_name').value='';
        document.getElementById('span_mask_name').style.display='inline-block';
    } else {
        document.getElementById('masked_name').value='';
        document.getElementById('span_mask_name').style.display='none';
    }
}
</script>