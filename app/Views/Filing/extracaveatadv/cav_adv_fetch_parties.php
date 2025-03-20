<?php

//$temp_dno_fil = explode('~', $_REQUEST['filno']);
$fil_no_diary = " caveat_no= $_SESSION[caveat_no] ";
$_REQUEST['dno'] = $_SESSION['caveat_no'];

$ucode = $_SESSION['login']['usercode']; 

// $sql = "select partyname,sr_no from caveat_party where $fil_no_diary";
// $rs = mysql_query($sql) or die(__LINE__ . '->' . mysql_error());
// if (mysql_num_rows($rs) == 0)
$main_row = $CaveatModel->getCaveatPartyDetails($caveat_no);
if(empty($main_row))
{
    echo 'Record Not Found!!!';
}else {
?>
    <div style="color:blue;text-align: center;font-weight: bold">
        <?php

        //$casetype = "SELECT pet_name,res_name,pno,rno FROM caveat WHERE $fil_no_diary";
        //$casetype = mysql_query($casetype) or die(__LINE__ . '->' . mysql_query());
       //$casetype = mysql_fetch_array($casetype);

        $casetype = is_data_from_table('caveat'," $fil_no_diary ",' pet_name,res_name,pno,rno ','');

        echo "Diary No: ".session()->get('filing_details')['diary_number'].'/'.session()->get('filing_details')['diary_year']; 
        echo "<br><span style=color:black>Caveator:</span> " . $casetype['pet_name'];
        if ($casetype['pno'] == 2) echo " <span style='color:#72bcd4'>AND ANR</span>";
        else if ($casetype['pno'] > 2) echo " <span style='color:#72bcd4'>AND ORS</span>";
        //echo "<font style=color:black>&nbsp; Versus &nbsp;</font>"; 
        echo "&nbsp; &nbsp;";
        echo "<span style=color:black>Caveatee:</span> " . $casetype['res_name'];
        if ($casetype['rno'] == 2) echo " <span style='color:#72bcd4'>AND ANR</span>";
        else if ($casetype['rno'] > 2) echo " <span style='color:#72bcd4'>AND ORS</span>";
        ?>
    </div>
    <table align="center">
        <?php
        /*if($casetype['c_status']=='D')
{
?>
<tr><th style="color:red;">!!!The Case is Disposed!!!</th></tr>
<?php
}*/
        ?>
    </table>
    <table border="1" style="border-collapse: collapse" align="center">
        <thead>
        <tr>
            <th colspan="12">Caveator</th>
        </tr>
        <tr>
            <th>S.No.</th>
            <th></th><!--<th>State</th><th>Enroll No.</th><th>Enroll Year</th>-->
            <th>Category</th>
            <th>AOR Code</th>
            <th>Advocate Name</th>
            <th>Mobile</th>
            <th>Email</th>
            <th>Party No.</th>
            <th>Type</th>
            <th>If [AG]</th>
            <th>STATE ADV[Pri/Gov]</th>
        </tr>
    </thead>
        <?php
        /*    $i=1;
         include("../includes/db_inc_efiling.php");
         $sq_efil_fn=mysql_query("select fil_no from main where org_fil_no='$_REQUEST[fno]'") 
                 or die("Error: ".__LINE__.  mysql_error());
     $res_sq_efil=mysql_result($sq_efil_fn,0);
         $sql = "select a.*,b.mobile,email from
            (SELECT * FROM 
            (	
                    SELECT fil_no fno, partyname, sr_no FROM party WHERE fil_no = '$res_sq_efil' AND pet_res = 'P' and pflag='P'
            )a 
            right JOIN 
            ( 
                    SELECT fil_no, adv_code, adv_cd_yr, adv, pet_res_no FROM advocate WHERE pet_res = 'P' 
                    AND fil_no = '$res_sq_efil' AND display = 'Y' and adv_code!=0 and status=0
            )b 
            ON 
            a.fno = b.fil_no 
            AND a.sr_no = b.pet_res_no
            )a
            LEFT JOIN bar b
            ON a.adv_code=enroll_no
            and a.adv_cd_yr=year(enroll_date) 
            union all
            select fno,partyname,sr_no,fil_no,adv_code,adv_cd_yr,adv,pet_res_no,'0' mobile,'0' email 
            from
            (
                    SELECT fil_no fno, partyname, sr_no
                    FROM party
                    WHERE fil_no = '$res_sq_efil'
                    AND pet_res = 'P'
                    AND pflag = 'P'
            )a
            RIGHT JOIN 
            (
                    SELECT fil_no, adv_code, adv_cd_yr, adv, pet_res_no
                    FROM advocate
                    WHERE pet_res = 'P'
                    AND fil_no = '$res_sq_efil'
                    AND display = 'Y'	
                    and adv_code=0 and status=0
            )b 
            ON a.fno = b.fil_no
            AND a.sr_no = b.pet_res_no
            order by sr_no";
    $rs = mysql_query($sql) or die(mysql_error());
    if(mysql_num_rows($rs)>0)
    /*{
    while($row= mysql_fetch_array($rs))
    
   
    {
         $write='N';
            if($row['adv_code']=='9999' && $row['adv_cd_yr']=='2014')
                $write='Y';
        ?>
    <tr><td><?php echo $i;?></td>
        <td><input type="checkbox" id="p_adv_chk<?php echo $i;?>" checked/></td>0
                   onblur="getAdvocate(<?php echo $i;?>,'p_')" 
                   value="<?php echo $row['adv_code']?>"/>
         <input type="hidden" name="hd_p_adv_no<?php echo $i;?>" id="hd_p_adv_no<?php echo $i;?>" 
                   value="<?php echo $row['adv_code']?>"/>
          <input type="hidden" name="hd_p_adv_yr<?php echo $i;?>" id="hd_p_adv_yr<?php echo $i;?>" 
                   value="<?php echo $row['adv_cd_yr']?>"/>
           <input type="hidden" name="hd_p_efil<?php echo $i;?>" id="hd_p_efil<?php echo $i;?>" value="P"/>
        </td>
        <td><input type="text" maxlength="4" size="4" id="p_adv_yr<?php echo $i;?>" onkeypress="return onlynumbers(event,this.id)" onblur="getAdvocate(<?php echo $i;?>,'p_')" value="<?php echo $row['adv_cd_yr']?>"/></td>
        <td><span id="p_adv_name<?php echo $i;?>" <?php if($write=='Y') echo "style='display:none'";?>><?php echo $row['adv'];?></span><input type="text" id="p_adv_name_write<?php echo $i;?>" onkeypress="return advName(event)" style="display:<?php if($write=='Y') echo 'block';else if($write=='N') echo 'none';?>;text-transform:uppercase;" onblur="copyToSpan(<?php echo $i;?>,'p_')" /> </td>
        <td><input type="text" id="p_adv_mob<?php echo $i;?>" onkeypress="return onlynumbers(event,this.id)" maxlength="10" size="10" value="<?php echo $row['mobile']?>" <?php if($write=='Y') echo "style='display:none'"?>/></td>
        <td><input type="text" id="p_adv_email<?php echo $i;?>" value="<?php echo $row['email']?>" <?php if($write=='Y') echo "style='display:none'"?>></td>
        <td><input type="text" id="p_adv_for<?php echo $i;?>" onkeypress="return partynumbers(event,this.id)" value="<?php echo  $row['sr_no'] ?>"/></td>
        <td>
             <?php 
        $having_ag=0;
        if($row['adv_code']==0)
        {
            $type = explode('[', rtrim($row['adv'],']'));
        }
        else
        {
            $type = explode('[', rtrim($row['adv'],']'));
            
            for($kk=0;$kk<sizeof($type);$kk++)
            {
                if($type[$kk]=='AG')
                    $having_ag=1;
            }
            
            if($type[2]=='LR/S')
                $type[1]=$type[2];
        }
        ?>
            <select id="p_adv_type<?php echo $i;?>" <?php if($row['adv_code']==0){?> style="display: none" <?php }?>>
                <option value='N' <?php if($type[1]=='N'){?>selected <?php }?>>None</option>
                <!--<option value='OBJ' <?php if($type[1]=='OBJ'){?>selected <?php }?>>OBJ</option>-->
            <option value='SURITY' <?php if($type[1]=='SURITY'){?>selected <?php }?>>SURITY</option>
            <option value='P-PET' <?php if($type[1]=='P-PET'){?>selected <?php }?>>P-PET</option>
            <option value='INT' <?php if($type[1]=='INT'){?>selected <?php }?>>INT</option>
            <option value='LR/S' <?php if($type[1]=='LR/S'){?>selected <?php }?>>LR/S</option>
            </select></td>
        <td><select id='p_ifag<?php echo $i;?>' <?php if($row['adv_code']==0){?> style="display: none" <?php }?>>
                <option value='N' <?php if($having_ag==0) echo "selected";?>>No</option>
                <option value='AG'<?php if($having_ag==1) echo "selected";?>>AG</option>
            </select></td>
    </tr>
        <?php 
            $i++;
        }
        ?>
     
    <?php
    }
    else*/ {
            //include("../includes/db_inc.php");
            $i = 1;
            for ($in = 1; $in <= $_REQUEST['pt']; $in++) {
        ?>
                <tr>
                    <td style="border:none"><?php echo $i; ?></td>
                    <td style="border:none"><input type="checkbox" id="p_adv_chk<?php echo $i; ?>" checked /></td>
                     
                    <td><select disabled="">
                            <option>Main</option>
                            <option selected="">Additional</option>
                        </select></td>
                    <td style="border:none"><input type="text" maxlength="6" size="4" id="p_aor<?php echo $i; ?>" onkeypress="return onlynumbers(event,this.id)" onblur="getAdvocateAOR(<?php echo $i; ?>,'p_')" /></td>
                    <td style="border:none"><span id="p_adv_name<?php echo $i; ?>"></span><input type="text" id="p_adv_name_write<?php echo $i; ?>" onkeypress="return advName(event)" style="display: none;text-transform:uppercase;" onblur="copyToSpan(<?php echo $i; ?>,'p_')" /> </td>
                    <td><input type="text" id="p_adv_mob<?php echo $i; ?>" onkeypress="return onlynumbers(event,this.id)" maxlength="10" size="10"   /></td>
                    <td><input type="text" id="p_adv_email<?php echo $i; ?>"  ></td>
                    <td style="border:none"><input type="text" id="p_adv_for<?php echo $i; ?>" onkeypress="return partynumbers(event,this.id)" style="width: 100px" value="0"   /></td>
                    <td style="border:none"><select id="p_adv_type<?php echo $i; ?>"  >
                            <option value='N'>None</option>
                            <option value='SURETY'>SURETY</option>
                            <option value='INT'>INTERVENOR</option>
                            <option value='LR/S'>LR/S</option>
                            <option value='AMICUS CURIAE'>AMICUS CURIAE</option>
                            <option value='DRW'>DRAWNBY</option>
                        </select></td>
                    <td style="border:none"><select id='p_ifag<?php echo $i; ?>'>
                            <option value='N'>No</option>
                            <option value='AG'>ATTORNY GENERAL</option>
                        </select></td>
                    <td style="border:none"><select id='p_statepg<?php echo $i; ?>'>
                            <option value='N'>No</option>
                            <option value='P'>Private</option>
                            <option value='G'>Government</option>
                        </select></td>
                </tr>
        <?php
                $i++;
            }
        }
        echo "<input type='hidden' value='$i' id='p_adv_total'>";
        ?>
        </table>
       <table border="1" style="border-collapse: collapse" align="center">
       <thead>
        <tr>
            <th colspan="12">Caveatee</th>
        </tr>
        <tr>
            <th>S.No.</th>
            <th></th><!--<th>State</th><th>Enroll No.</th><th>Enroll Year</th>-->
            <th>Category</th>
            <th>AOR Code</th>
            <th>Advocate Name</th>
            <th>Mobile</th>
            <th>Email</th>
            <th>Party No.</th>
            <th>Type</th>
            <th>If [AG]</th>
            <th>STATE ADV[Pri/Gov]</th>
        </tr>
    </thead>
        <?php
        $i = 1;

        /*    include("../includes/db_inc_efiling.php");
       
    $sql = "select a.*,b.mobile,email from
            (SELECT * FROM 
            (	
                    SELECT fil_no fno, partyname, sr_no FROM party WHERE fil_no = '$res_sq_efil' AND pet_res = 'R' and pflag='P'
            )a 
            right JOIN 
            ( 
                    SELECT fil_no, adv_code, adv_cd_yr, adv, pet_res_no FROM advocate WHERE pet_res = 'R' 
                    AND fil_no = '$res_sq_efil' AND display = 'Y' and adv_code!=0 and status=0
            )b 
            ON 
            a.fno = b.fil_no 
            AND a.sr_no = b.pet_res_no
            )a
            LEFT JOIN bar b
            ON a.adv_code=enroll_no
            and a.adv_cd_yr=year(enroll_date) 
            union all
            select fno,partyname,sr_no,fil_no,adv_code,adv_cd_yr,adv,pet_res_no,'0' mobile,'0' email 
            from
            (
                    SELECT fil_no fno, partyname, sr_no
                    FROM party
                    WHERE fil_no = '$res_sq_efil'
                    AND pet_res = 'R'
                    AND pflag = 'P'
            )a
            RIGHT JOIN 
            (
                    SELECT fil_no, adv_code, adv_cd_yr, adv, pet_res_no
                    FROM advocate
                    WHERE pet_res = 'R'
                    AND fil_no = '$res_sq_efil'
                    AND display = 'Y'	
                    and adv_code=0 and status=0
            )b 
            ON a.fno = b.fil_no
            AND a.sr_no = b.pet_res_no
            order by sr_no";
    $rs = mysql_query($sql) or die(mysql_error());
     if(mysql_num_rows($rs)>0)
    {
    while($row= mysql_fetch_array($rs))
    {
        ?>
    <tr><td><?php echo $i;?></td>
        <td><input type="checkbox" id="r_adv_chk<?php echo $i;?>" checked/></td>
        <td>
            <input type="text" maxlength="6" size="5" id="r_adv_no<?php echo $i;?>" 
                   onkeypress="return onlynumbersadv(event,this.id)" 
                   onblur="getAdvocate(<?php echo $i;?>,'r_')" 
                   <?php if($row['adv_code']==0){?> style="display: none" <?php }?> 
                   value="<?php echo $row['adv_code']?>"/>
          <input type="hidden" name="hd_r_adv_no<?php echo $i;?>" id="hd_r_adv_no<?php echo $i;?>" 
                   value="<?php echo $row['adv_code']?>"/>
          <input type="hidden" name="hd_r_adv_yr<?php echo $i;?>" id="hd_r_adv_yr<?php echo $i;?>" 
                   value="<?php echo $row['adv_cd_yr']?>"/>
           <input type="hidden" name="hd_r_efil<?php echo $i;?>" id="hd_r_efil<?php echo $i;?>" value="R"/>
        </td>
        <td><input type="text" maxlength="4" size="4" id="r_adv_yr<?php echo $i;?>" onkeypress="return onlynumbers(event,this.id)" onblur="getAdvocate(<?php echo $i;?>,'r_')" <?php if($row['adv_code']==0){?> style="display: none" <?php }?> value="<?php echo $row['adv_cd_yr']?>"/></td>
        <td>
            <?php
            $write='N';
            if($row['adv_code']=='9999' && $row['adv_cd_yr']=='2014')
                $write='Y';
           
            ?>
    <span id="r_adv_name<?php echo $i;?>" <?php if($write=='Y') echo "style='display:none'"?>><?php echo $row['adv']?></span>
    <input type="text" id="r_adv_name_write<?php echo $i;?>" onkeypress="return advName(event)" style="display:<?php if($write=='Y') echo 'block';else if($write=='N') echo 'none';?>;text-transform:uppercase;" onblur="copyToSpan(<?php echo $i;?>,'r_')" /></td>
        <td><input type="text" id="r_adv_mob<?php echo $i;?>" onkeypress="return onlynumbers(event,this.id)" maxlength="10" size="10" <?php if($row['adv_code']==0){?> style="display: none" <?php }?> value="<?php echo $row['mobile']?>" <?php if($write=='Y') echo "style='display:none'"?>/></td>
        <td><input type="text" id="r_adv_email<?php echo $i;?>" <?php if($row['adv_code']==0){?> style="display: none" <?php }?> value="<?php echo $row['email']?>" <?php if($write=='Y') echo "style='display:none'"?>/></td>
        <td><input type="text" id="r_adv_for<?php echo $i;?>" onkeypress="return partynumbers(event,this.id)" value="<?php echo $row['sr_no']; ?>"/></td>
        <td>
             <?php
        $having_ag=0;
        if($row['sr_no']==0)
        {
            $type = explode('[', rtrim($row['adv'],']'));
        }
        else
        {
            $type = explode('[', rtrim($row['adv'],']'));
            for($kk=0;$kk<sizeof($type);$kk++)
            {
                if($type[$kk]=='AG')
                    $having_ag=1;
            }
            if($type[2]=='LR/S')
                $type[1]=$type[2];
        }
        ?>
            <select id="r_adv_type<?php echo $i;?>" <?php if($row['adv_code']==0){?> style="display: none" <?php }?>>
                <option value='N' <?php if($type[1]=='N'){?>selected <?php }?>>None</option>
                <option value='OBJ' <?php if($type[1]=='OBJ'){?>selected <?php }?>>OBJ</option>
            <option value='COMP' <?php if($type[1]=='COMP'){?>selected <?php }?>>COMP</option>
            <option value='SURITY' <?php if($type[1]=='SURITY'){?>selected <?php }?>>SURITY</option>
            <option value='P-RES' <?php if($type[1]=='P-RES'){?>selected <?php }?>>P-RES</option>
            <option value='INT' <?php if($type[1]=='INT'){?>selected <?php }?>>INT</option>
            <option value='LR/S'<?php if($type[1]=='LR/S'){?>selected <?php }?>>LR/S</option></select></td>
        <td><select id='r_ifag<?php echo $i;?>' <?php if($row['adv_code']==0){?> style="display: none" <?php }?>>
                <option value='N' <?php if($having_ag==0) echo "selected";?>>No</option>
                <option value='AG' <?php if($having_ag==1) echo "selected";?>>AG</option></select></td>
    </tr>
        <?php 
            $i++;
  
    }
    ?>
   
    <?php
    }
    else*/ {
            //include("../includes/db_inc.php");
            for ($in = 1; $in <= $_REQUEST['rt']; $in++) {
        ?>
                <tr>
                    <td style="border:none"><?php echo $i; ?></td>
                    <td style="border:none"><input type="checkbox" id="r_adv_chk<?php echo $i; ?>" checked /></td>
                     
                    <td><select disabled="">
                            <option>Main</option>
                            <option selected="">Additional</option>
                        </select></td>
                    <td style="border:none"><input type="text" maxlength="6" size="4" id="r_aor<?php echo $i; ?>" onkeypress="return onlynumbers(event,this.id)" onblur="getAdvocateAOR(<?php echo $i; ?>,'r_')" /></td>
                    <td style="border:none"><span id="r_adv_name<?php echo $i; ?>"></span><input type="text" id="r_adv_name_write<?php echo $i; ?>" onkeypress="return advName(event)" style="display: none;text-transform:uppercase;" onblur="copyToSpan(<?php echo $i; ?>,'r_')" /></td>
                    <td><input type="text" id="r_adv_mob<?php echo $i; ?>" onkeypress="return onlynumbers(event,this.id)" maxlength="10" size="10" disabled="" /></td>
                    <td><input type="text" id="r_adv_email<?php echo $i; ?>" disabled="" /></td>
                    <td style="border:none"><input type="text" id="r_adv_for<?php echo $i; ?>" onkeypress="return partynumbers(event,this.id)" style="width: 100px" /></td>
                    <td style="border:none"><select id="r_adv_type<?php echo $i; ?>">
                            <option value='N'>None</option>
                            <option value='OBJ'>OBJECTOR</option>
                            <option value='SURETY'>SURETY</option>
                            <option value='INT'>INTERVENOR</option>
                            <option value='IMPL'>IMPLEADER</option>
                            <option value='COMP'>COMPLAINANT</option>
                            <option value='DRW'>DRAWNBY</option>
                            <option value='LR/S'>LR/S</option>
                        </select></td>
                    <td style="border:none"><select id='r_ifag<?php echo $i; ?>'>
                            <option value='N'>No</option>
                            <option value='AG'>ATTORNY GENERAL</option>
                        </select></td>
                    <td style="border:none"><select id='r_statepg<?php echo $i; ?>'>
                            <option value='N'>No</option>
                            <option value='P'>Private</option>
                            <option value='G'>Government</option>
                        </select></td>
                </tr>
        <?php
                $i++;
            }
        }
        echo "<input type='hidden' value='$i' id='r_adv_total'>";
        ?>
        <tr>
            <td colspan="12" style="border-left:0px;">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="12" align="center"><input type="button" value="Save" name="advocatesaeve" /><!--onclick="saveAdv()"-->
                <!--<input type="button" onclick="h1_bak('spsubmenu_2','spsubsubmenu_1','<?php  //echo substr($_REQUEST[fno],3,2); 
                                                                                            ?>','<?php  //echo substr($_REQUEST[fno],5,5) 
                                                                                                                                        ?>','<?php  //echo substr($_REQUEST[fno],10,4); 
                                                                                                                                                                                        ?>','<?php  //echo $_REQUEST[fno] 
                                                                                                                                                                                                                                        ?>');" value="Cancel"/>-->
                <input type="button" value="New/Cancel" onclick="window.location.reload()" />
            </td>
        </tr>
    </table>
<?php
}

?>