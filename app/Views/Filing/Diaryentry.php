<?php
//include ('../extra/lg_out_script.php');
{
//include("../index11.php");
//include("../includes/db_inc.php");
//include("../extra/casetype_diary_no.php");
$t=time();

//Check if user is assigned role of Diary
    /*$sql_role="SELECT * from fil_trap_users where usercode='$_SESSION[dcmis_user_idd]' and display='Y' and usertype='101'";
    $sql_role=mysql_query($sql_role) or die("Error: ".__LINE__.mysql_error());
    $role="filing";
   if(mysql_num_rows($sql_role)<=0 and $_SESSION[dcmis_user_idd]!=1)
   {
       $role="";
   }*/

   ?>

    <?php

function get_state()
{
       /*$sql_state="SELECT id_no, Name FROM state WHERE District_code =0 AND Sub_Dist_code =0 AND Village_code =0 AND display = 'Y'
                            AND sci_state_id !=0 ORDER BY Name";
                $sql_state=mysql_query($sql_state) or die("Error: ".__LINE__.mysql_error());
                $st_nm=array();
                 while ($row = mysql_fetch_array($sql_state)) 
                {
                    $st_nm[]= $row['id_no'].'!'.$row['Name'];
                }
                return $st_nm;*/
}
?>
<html>
    <head>
        <title>Diary No. Addition</title>
        <link rel="stylesheet" href="<?php echo base_url();?>/css/menu_css.css">
        <!--<script language="JavaScript" src="../includes/unicode.js" type="text/javascript"></script>-->
        <script src="<?php echo base_url();?>/js/menu_js.js"></script>
        <script src="<?php echo base_url();?>/jquery/jquery-1.9.1.js"></script>

        <!--<link href="<?php /*echo base_url();*/?>/css/jquery-ui.css" rel="stylesheet">
        <script src="<?php /*echo base_url();*/?>/js/jquery-ui.js"></script>-->

        <link rel="stylesheet" href="<?php echo base_url();?>/dp/jquery-ui.css" type="text/css"/>
        <script src="<?php echo base_url();?>/dp/jquery-ui.js"></script>
        <script type="text/javascript" src="<?php echo base_url();?>/filing/new_filing_so.js?version=<?php echo $t;?>"></script>
        <style>
            .state_p, .state_r{ display: none}
            .cl_add_address
            {
                color: blue;
            }
            .cl_add_address:hover
            {
                cursor: pointer;
            }
        </style>
        <script>
$(document).on("focus",".dtp",function(){   
       
$('.dtp').datepicker({dateFormat: 'dd-mm-yy', changeMonth : true,changeYear  : true,yearRange : '1950:2050'
});
        });
           </script>
    </head>
    <body>
        <?php
        //include ('../mn_sub_menu.php');
        ?>
        <div id="dv_content1"   >
        <input type="hidden" id="fil_hd"/>

        <!--<div id="show_fil_efil">-->

        <div id="show_fil">

            <div style="text-align: center;margin:10px"> <?php //if($role==""){?>
                <!-- font style="color: red;font-size: 16px; text-align: center"> <b><?php // echo "Diary Role is not assigned. Only MA can be filed."; ?></b></font></br></br -->
                <font style="color: red;font-size: 16px; text-align: center"> <b><?php echo "Diary Role is not assigned. Only MA/Review/Curative/Contempt Petition can be filed."; ?></b></font></br></br>
                <?php }?>
                 <b>Court</b>
                <select name="ddl_court" id="ddl_court">
                    <option value="">Select</option>
                  <?php
                 	foreach($court_type as $court_type_value):
                     ?>
					<option value="<?php echo $court_type_value['id'] ?>" <?php if($court_type_value['id']=='1') { ?> selected="selected" <?php } ?>><?php echo $court_type_value['court_name'] ?></option>
               <?php
           			endforeach;
                  ?>
                </select>
                &nbsp;&nbsp;
                <b>State</b>
                <?php
                //$state=array();
                //$state=get_state();
              
//                $sql_state="SELECT id_no, Name FROM state WHERE District_code =0 AND Sub_Dist_code =0 AND Village_code =0 AND display = 'Y'
//                            AND sci_state_id !=0 ORDER BY Name";
//                $sql_state=mysql_query($sql_state) or die("Error: ".__LINE__.mysql_error());
                
                ?>
                <select name="ddl_st_agncy" id="ddl_st_agncy">
                    <option value="">Select</option>
                    <?php

                      foreach ($state_list as $state_list_value):
               
                           ?>
                      <option value="<?php echo $state_list_value['id_no']; ?>"><?php echo $state_list_value['name'] ?></option>
                    <?php
                     endforeach;

                    ?>
                </select> &nbsp;&nbsp;
               
                     <b>Bench</b>
                     <select name="ddl_bench" id="ddl_bench" style="width: 20%">
                         <option value="">Select</option>
                     </select>
                   
                    
<!--                     &nbsp;&nbsp;
                     <input type="button" name="btn_p_yr" id="btn_p_yr" value="Submit" onclick="get_case_no()"/>-->
            </div>
            <div id="dv_case_no" style="text-align: center"></div>
            <div id="dv_parties"></div>
<!--        <div id="show_fil">-->
            <!--<div style="color: red;font-size: 16px; text-align: center">PLEASE USE 7777/2014 FOR ADVOCATE GENERAL</div>-->
       <table align="center" cellspacing="1" cellpadding="2" border="0" width="100%">
            <!--<tr><th>Case Filing - Addition</th></tr>-->
            <tr align="center">
                <th>
                    
<!--                    <select id="bench" >
                        <?php
//                        $ben_q="select * from bench where display='Y'";
//                        $ben_rs = mysql_query($ben_q);
//                        while($ben_rw = mysql_fetch_array($ben_rs))
//                        {
                        ?>
                        <option value="<?php //echo $ben_rw['b_code'];?>"><?php //echo $ben_rw['b_name'];?></option>        
                        <?php
//                        }
                        ?>
                    </select>
                      Case Type:
                    <select id="selct" onchange="chkM(this.value)"><option value="-1">Select</option><?php
//              echo  $ct_q = "SELECT casecode, skey, casename
//                    FROM casetype
//                    WHERE display = 'Y'
//                    ORDER BY skey";
//                $ct_rs = mysql_query($ct_q) or die(mysql_error());
//                while($ct_rw = mysql_fetch_array($ct_rs))
//                {
                    ?>
                        <option value="<?php //echo $ct_rw['casecode']?>" title="<?php //echo $ct_rw['casename'] ?>"><?php //echo $ct_rw['skey'].' - '.$ct_rw['casename']?></option>    
                    <?php
//                }
                ?></select> -->

<!-- <input type="checkbox" name="chk_undertaking" id="chk_undertaking"/><b>Undertaking</b>-->
                     <?php
//                     $documents="SELECT doccode, docdesc FROM docmaster WHERE doccode1 =0 AND display = 'Y' AND doccode !=8 ORDER BY docdesc";
//                     $documents=mysql_query($documents) or die("Error: ".__LINE__.mysql_error());
                     ?>
<!--                     <select name="ddl_doc_u" id="ddl_doc_u" disabled="true">
                         <option value="">Select</option>
                         <?php
//                           while ($row_d = mysql_fetch_array($documents))
//                              {
                                       ?>
                         <option value="<?php //echo $row_d['doccode'] ?>"><?php //echo $row_d['docdesc'] ?></option>
                         <?php
//                              }
                         ?>
                     </select>-->
                     <!--<input type="text" name="txt_undertakig" id="txt_undertakig" disabled="true" maxlength="100"/>-->
 <b>Case Type</b>
                     <?php


                     /*$nature="";
                     if($role=='filing') {
                         $nature = "Select casecode,casename from casetype where display='Y' and casecode not in (13,14,9999,15,16) order by casecode,casename";
                     }
                     else if($role==""){
                         // $nature = "Select casecode,casename from casetype where display='Y' and casecode not in (1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,40,41,9999) order by casecode,casename";
                            $nature = "Select casecode,casename from casetype where display='Y' and casecode not in (1,2,3,4,5,6,7,8,11,12,13,14,15,16,17,18,21,22,23,24,27,28,29,30,31,32,33,34,35,36,37,38,40,41,9999) order by casecode,casename";
                     }
                     $nature=mysql_query($nature) or die("Error: ".__LINE__.mysql_error());*/
                     ?>
                     <select name="ddl_nature" id="ddl_nature">
                         <option value="">Select</option>
                       <?php
                     /*while ($r_nature = mysql_fetch_array($nature))
                     {*/
                         ?>
                         <option value="<?php //echo $r_nature['casecode']; ?>"><?php //echo $r_nature['casename']; ?></option>   
                         <?php
                     //}
                       ?>
                     </select>&nbsp;&nbsp;
  <b>Section</b>
                      <select name="section" id="section" style="width: 10%" >
                        <option value="">Select</option>
                        <?php
                        /*$sql_chk="select id,section_name from usersection where (isda='Y' and id not in(76,10)) or id in(40) order by id";
                        $rs_chk=mysql_query($sql_chk);
                        while($row=mysql_fetch_array($rs_chk))
                        {*/
                            ?>

                            <option value=<?php //echo $row[0]; ?>><?php //echo $row[1]; ?></option>
                            <?php

                        //}

                        ?>
                    </select>


Special Type: <select id="type_special"><option value="1">None</option>
                    <option value="6">Jail Petition</option><option value="7">PUD</option></select>
<span id="sp_doc_signed" style="display: none"><br/><br/>
Date of document signed by jailer 
<input type="text" name="txt_doc_signed" id="txt_doc_signed" class="dtp" size="9" maxlength="10"/></span>
<!--&nbsp;-->
<br/><br/>


                    &nbsp;
                     &nbsp;

                    <!----   code for  lowercourt information to be saved when ma,rp,curative,contempt petition is filed   ----->




                    <div id="lct_casetype">

                        <table ><tr><td>  <input type="radio" name ="sel" id="c" onclick="check(this.id)" checked="checked"><b>Case Type</b>
                        <?php
                        //$nature="Select casecode,casename from casetype where display='Y' and casecode not in (9999,15,16) order by casecode,casename";
                        //$nature=mysql_query($nature) or die("Error: ".__LINE__.mysql_error());
                        ?>
                        <select name="ddl_nature_sci" id="ddl_nature_sci">
                            <option value="">Select</option>
                            <?php
                            /*while ($r_nature = mysql_fetch_array($nature))
                            {*/
                                ?>
                                <option value="<?php //echo $r_nature['casecode']; ?>"><?php //echo $r_nature['casename']; ?></option>
                                <?php
                            //}
                            ?>
                        </select>&nbsp;&nbsp;</td>


                                <td><b>Case No.</b><input type="text" name="no" id="no" size="10">
                                    <!-- case year code -->

                        <!--<input type="text" id="t_h_cyt" name="t_h_cyt" maxlength="4" size="4" value="<?php // echo date('Y'); ?>"/>-->
                        <?php   $currently_selected = date('Y'); $earliest_year = 1950; $latest_year = date('Y');
                        print '<select id="t_h_cyt">';
                       ?>
                                    <option value=0>Year</option>
                                    <?php

                        foreach ( range( $latest_year, $earliest_year ) as $i ) {
                            print '<option value="'.$i.'"';
                            /*if($_SESSION['session_diary_yr']){
                                if($i == $_SESSION['session_diary_yr']){

                                }
                            }
                            else{
                                if($i == date('Y')){
                                    print 'selected="selected"';
                                }
                            }*/
                            print '>'.$i.'</option>';

                        }
                        print '</select>'; ?>


          <!-- <input type="button" value="Submit" onclick="call_save_main('0')" id="svbtn" onkeydown="if (event.keyCode == 13) document.getElementById('svbtn').click()"/> &nbsp;-->
                                </td><td><b>OR   <input type="radio" name ="sel" id="d" onclick="check(this.id)"> Diary No. </b><input type="text" name="diary_no" size="5" id="diary_no" ></td><td>  <?php   $currently_selected = date('Y'); $earliest_year = 1950; $latest_year = date('Y');
                                    print '<select id="dyr">';
                                    ?>
                                    <option value=0>Year</option>
                                    <?php

                                    foreach ( range( $latest_year, $earliest_year ) as $i ) {
                                        print '<option value="'.$i.'"';
                                        /*if($_SESSION['session_diary_yr']){
                                            if($i == $_SESSION['session_diary_yr']){

                                            }
                                        }
                                        else{
                                            if($i == date('Y')){
                                                print 'selected="selected"';
                                            }
                                        }*/
                                        print '>'.$i.'</option>';

                                    }
                                    print '</select>'; ?></td><td><input type="button" value="Submit" onclick="f1()" id="sbtn" /> &nbsp;
                                </td>  </tr>
                        </table>
                   <!-- end of code -->


                    </div>

                    <!----         end of the Code for Saving Lower court information          -->
                    <div id="dv_sc_parties"></div>
                    Total No. of Pages in File: <input type="text" size="4" maxlength="4" id="case_doc" onkeypress="return onlynumbers(event)"/>

                    IF SCLSC: <input type="checkbox" name="if_sclsc" id="if_sclsc" />
<span id="sp_no_yr" style="display: none">
<b>No.</b><input type="text" name="txt_sclsc_no" id="txt_sclsc_no" size="4" onkeypress="return onlynumbers(event)"/>
<b>Year</b>
<select name="ddl_sclsc_yr" id="ddl_sclsc_yr">
    <option value="">Select</option>
    <?php
    $yr_sclsc=date('Y');                  
    for ($index = $yr_sclsc; $index >=1930; $index--) {
                     ?>
    <option value="<?php echo $index; ?>"><?php echo $index; ?></option>
    <?php
                      }
    ?>
</select></span>
                    IS Efiling: <input type="checkbox" name="if_efil" id="if_efil" />
                    <span id="sp_efil" style="display: none">
<b>No.</b><input type="text" name="txt_efil_no" id="txt_efil_no" size="8" onkeypress="return onlynumbers(event)"/>
<b>Year</b>
<select name="ddl_efil_yr" id="ddl_efil_yr">
    <option value="">Select</option>
    <?php
    $yr_efil=date('Y');
    for ($index = $yr_efil; $index >=1930; $index--) {
        ?>
        <option value="<?php echo $index; ?>"><?php echo $index; ?></option>
        <?php
    }
    ?>
</select></span>


                </th>
            </tr>
             <tr style="display: none" id="mcrc_rw" valign="middle"><th valign="middle">Section: <input type="radio" name="rbtn" id="rbtn4"/>438 
                    <input type="radio" name="rbtn" id="rbtn5"/>439
                    &nbsp;Bail No: <input type="text" id="bno" size="2" maxlength="2" onkeypress="return onlynumbers(event)"/>
                </th></tr>
            <tr><th ><hr></th></tr>
            <tr><td ><b>Petitioner Individual / Dept.:</b> &nbsp;
                    <select id="selpt" name="selpt" onchange="activate_main(this.id)">
                        <option value="I">Individual</option>
                        <option value="D1">State Department</option>
                        <option value="D2">Central Department</option>
                        <option value="D3">Other Organization</option>
                    </select></td>
            </tr>
            <tr><th >
            <div id="for_I_p">
                <table border="0" style="border-collapse: collapse" width="100%" >
                    <tr align="left"><td>Name:</td><td><input type="text" id="pet_name" style="width:200px" onkeypress="return onlyalphab(event)" onblur="remove_apos(this.value,this.id)"/></td>
                        <td>Relation:</td><td >
                            <select id="selprel" onchange="setSex(this.value,this.id)">
                                <option value="">Select</option>
                                <option value="S" >Son of</option>
                                <option value="D" >Daughter of</option>
                                <option value="W" >Wife of</option>
                            </select>
                        </td>
                        <td>Father/Husband:</td>
                        <td><input type="text" id="prel" style="width:200px" onkeypress="return onlyalpha(event)" onblur="remove_apos(this.value,this.id)"/></td>
                    </tr>
                    <tr align="left"><td>Gender :</td><td><select id="psex"><option value="">Select</option>
                                <option value="M" >Male</option>
                                <option value="F" >Female</option>
                                <option value="N" >N.A.</option>
                            </select></td>
                            <td>Age:</td><td><input type="text" id="page" size="3" maxlength="3" onkeypress="return onlynumbers(event)" /></td>
                    <td>Occupation/Dept:</td><td><input type="text" id="pocc" style="width:200px" onblur="remove_apos(this.value,this.id)"/></td></tr>
                    <tr align="left"><td>Address:</td><td><input type="text" id="paddi" style="width:200px" onblur="remove_apos(this.value,this.id)"/></td>
                        <td>Tehsil/City:</td><td><input type="text" id="pcityi" style="width:200px" onkeypress="return onlyalpha(event)" onblur="remove_apos(this.value,this.id)"/></td>
                        <td>Pin :</td><td><input type="text" id="ppini" style="width:200px" maxlength="6" onkeypress="return onlynumbers(event)" onblur="remove_apos(this.value,this.id)"/></td></tr>
                    <tr align="left">
                        <td style="border:none">Country:</td><td style="border:none">
                            <?php
                            //$country = "SELECT country_name,id FROM country WHERE display='Y' ORDER BY country_name";
                            //$country = mysql_query($country) or die(__LINE__.'->'.mysql_error());
                            ?>
                            <select id="p_conti" style="width:200px;" onchange="setCountry_state_dis(this.id,this.value)">
                            <?php 
                            //while($country_row = mysql_fetch_array($country)){
                                ?>
                                <option value="<?php //echo $country_row['id']; ?>" <?php //if($country_row['id']=='96') echo "Selected"; ?>><?php //echo $country_row['country_name']; ?></option>
                                    <?php
                            //}
                            ?>  
                            </select>
                        </td>
                        <td>State:</td><td><select id="selpsti" style="width:204px" onchange="getDistrict('P',this.id,this.value)"><option value="">Select</option>
                            <?php
                            /*$st_q = "SELECT id_no State_code, Name
                                    FROM `state`
                                    WHERE District_code =0
                                    AND Sub_Dist_code =0
                                    AND Village_code =0
                                    AND display = 'Y'
                                    AND State_code < 100
                                    ORDER BY Name";
                            $st_rs = mysql_query($st_q);
                            while($st_row = mysql_fetch_array($st_rs))
                            {*/
                                ?>
                                <option value="<?php //echo $st_row['State_code']?>"><?php //echo $st_row['Name']?></option>    
                                <?php
                            //}
                            ?>
                            </select></td>
                            <td>District:</td><td><select id="selpdisi" style="width:203px"><option value="">Select</option>
                        </select></td>
                    </tr>
                    <tr align="left">
                        <td>Phone/Mobile:</td><td><input type="text" id="pmobi" style="width:200px" maxlength="14" onkeypress="return onlynumbers(event)" onblur="remove_apos(this.value,this.id)"/></td>
                        <td>Email Id:</td><td><input type="text" id="pemaili" style="width:200px" onblur="remove_apos(this.value,this.id)"/></td>
                        <td>Total Pet(s):</td><td><input type="text" id="p_noi" size="3" maxlength="4" onkeypress="return onlynumbers(event)" value="1"/></td></tr>
                </table>
              
            </div>
            
            <div id="for_D_p" style="display: none">
                <table border="0" style="border-collapse: collapse" width="100%">
                    <tr align="left">
                        <td id='for_D_p_sn1'>State Name:<input type="checkbox" id="pet_causetitle1" checked=""/></td><td id='for_D_p_sn2'>
                        <input type="text" id="pet_statename" style="width:200px" onkeypress="return onlyalphab(event)" onblur="remove_apos(this.value,this.id)"/>
                        <input type="hidden" id="pet_statename_hd"/></td>
                        <td>Department:<input type="checkbox" id="pet_causetitle2" checked=""/></td><td><input type="text" id="pet_deptt" style="width:200px" onkeypress="return onlyalphab(event)" onblur="remove_apos(this.value,this.id)"/>
                        <input type="hidden" id="pet_deptt_code"/></td>
                        <td>Post:<input type="checkbox" id="pet_causetitle3"/></td><td><input type="text" id="pet_post" style="width:200px" onkeypress="return onlyalphab(event)" onblur="remove_apos(this.value,this.id)"/><!--onblur="get_a_d_code(this.id)"--></td>
                        <input type="hidden" id="pet_post_code"/>
                        <?php
                        $select_deptcode1 = "select deptcode,deptname from deptt where deptype='S' and dm!=0 and display='Y' order by dm,d1,d2";
                        //$select_deptcode1 = mysql_query($select_deptcode1) or die(__LINE__.'->'.  mysql_error());
                        ?>
                        <!--<td class="state_p">State Dept.:</td><td class="state_p">
                        <input list="state_pet_department" style="width:200px;" id="state_department_in_pet" onblur="check_for_right_selection(this.id)" />
                        <datalist id="state_pet_department" >
                        <?php
                        /*while($ro_for_department = mysql_fetch_array($select_deptcode1))
                        {*/
                            ?>
                            <option value="<?php //echo $ro_for_department['deptcode'].'->'.$ro_for_department['deptname'];?>"><?php //echo $ro_for_department['deptname'];?></option>
                                <?php
                            
                        //}
                        ?>
                        </datalist>
                        </td>-->
                    </tr>
                    <tr align="left"><td>Address:</td><td><input type="text" id="paddd" style="width:200px" onblur="remove_apos(this.value,this.id)"/></td>
                        <td>Tehsil/City:</td><td><input type="text" id="pcityd" style="width:200px" onkeypress="return onlyalpha(event)" onblur="remove_apos(this.value,this.id)"/></td>
                        <td>Pin:</td><td><input type="text" id="ppind" style="width:200px" maxlength="6" onkeypress="return onlynumbers(event)" onblur="remove_apos(this.value,this.id)"/></td></tr>
                    <tr align="left">
                        <td style="border:none">Country:</td><td style="border:none">
                            <?php
                            //$country = "SELECT country_name,id FROM country WHERE display='Y' ORDER BY country_name";
                            //$country = mysql_query($country) or die(__LINE__.'->'.mysql_error());
                            ?>
                            <select id="p_contd" style="width:200px;" onchange="setCountry_state_dis(this.id,this.value)">
                            <?php 
                            //while($country_row = mysql_fetch_array($country)){
                                ?>
                                <option value="<?php //echo $country_row['id']; ?>" <?php //if($country_row['id']=='96') echo "Selected"; ?>><?php //echo $country_row['country_name']; ?></option>
                                    <?php
                            //}
                            ?>  
                            </select>
                        </td>
                        <td>State:</td><td><select id="selpstd" style="width:204px" onchange="getDistrict('P',this.id,this.value)"><option value="">Select</option>
                            <?php
                            /*$st_q = "SELECT id_no State_code, Name
                                    FROM `state`
                                    WHERE District_code =0
                                    AND Sub_Dist_code =0
                                    AND Village_code =0
                                    AND display = 'Y'
                                    AND State_code < 100
                                    ORDER BY Name";
                            $st_rs = mysql_query($st_q);
                            while($st_row = mysql_fetch_array($st_rs))
                            {
                                if($st_row['State_code']=='23')
                                {*/
                                    ?>
                                <option value="<?php //echo $st_row['State_code']?>" selected><?php //echo $st_row['Name']?></option>    
                                    <?php
                                /*}
                                else{*/
                                ?>
                                <option value="<?php //echo $st_row['State_code']?>"><?php //echo $st_row['Name']?></option>    
                                <?php
                                //}
                            //}
                            ?>
                            </select></td>
                        <td>District:</td><td><select id="selpdisd" style="width:203px"><option value="">Select</option>
                               
                        </select></td>
                    </tr>
                    <tr align="left">
                        <td>Phone/Mobile:</td><td><input type="text" id="pmobd" style="width:200px" maxlength="14" onkeypress="return onlynumbers(event)" onblur="remove_apos(this.value,this.id)"/></td>
                        <td>Email Id:</td><td><input type="text" id="pemaild" style="width:200px" onblur="remove_apos(this.value,this.id)"/></td>
                        <td>Total Pet(s):</td><td><input type="text" id="p_nod" size="3" maxlength="4" onkeypress="return onlynumbers(event)" value="1"/></tr>
       </table>
        </div>
              
                <input type="hidden" name="hd_add_address" id="hd_add_address" value="0"/>
                <div id="dv_add_parties"></div>
                <div class="cl_center cl_add_address" id="ad_address">Add Additional Petitioner Address</div>
        </th></tr>
        <tr><th ><hr></th></tr>
        <tr><td ><b>Respondent Individual / Dept.:</b> &nbsp;     
                    <select id="selrt" name="selrt" onchange="activate_main(this.id)">
                        <option value="I">Individual</option>
                        <option value="D1">State Department</option>
                        <option value="D2">Central Department</option>
                        <option value="D3">Other Organization</option>
                    </select> &nbsp; &nbsp; &nbsp; 
                     Address same as above<input type="checkbox" name="copy_add" id='copy' onclick="copy_address()" ></td></tr>
            <tr><th>
            <div id="for_I_r">
                <table border="0" style="border-collapse: collapse" width="100%">
                    <tr align="left"><td>Name:</td><td><input type="text" id="res_name" style="width:200px" onkeypress="return onlyalphab(event)" onblur="remove_apos(this.value,this.id)"/></td>
                        <td>Relation:</td><td>
                            <select id="selrrel" onchange="setSex(this.value,this.id)">
                                <option value="">Select</option>
                                <option value="S" >Son of</option>
                                <option value="D" >Daughter of</option>
                                <option value="W" >Wife of</option>
                            </select>
                        </td>
                        <td>Father/Husband:</td>
                        <td><input type="text" id="rrel" style="width:200px" onkeypress="return onlyalpha(event)" onblur="remove_apos(this.value,this.id)"/></td>
                    </tr>
                    <tr align="left"><td>Gender:</td><td><select id="rsex"><option value="">Select</option>
                                <option value="M" >Male</option>
                                <option value="F" >Female</option>
                                <option value="N" >N.A.</option>
                            </select></td>
                            <td>Age:</td><td><input type="text" id="rage" size="3" maxlength="3" onkeypress="return onlynumbers(event)"/></td>
                    <td>Occupation/Dept:</td><td><input type="text" id="rocc" style="width:200px" onblur="remove_apos(this.value,this.id)"/></td></tr>
                    <tr align="left"><td>Address:</td><td><input type="text" id="raddi" style="width:200px" onblur="remove_apos(this.value,this.id)"/></td>
                        <td>Tehsil/City:</td><td><input type="text" id="rcityi" style="width:200px" onkeypress="return onlyalpha(event)" onblur="remove_apos(this.value,this.id)"/></td>
                        <td>Pin:</td><td><input type="text" id="rpini" style="width:200px" maxlength="6" onkeypress="return onlynumbers(event)" onblur="remove_apos(this.value,this.id)"/></td></tr>
                    <tr align="left">
                        <td style="border:none">Country:</td><td style="border:none">
                            <?php
                            //$country = "SELECT country_name,id FROM country WHERE display='Y' ORDER BY country_name";
                            //$country = mysql_query($country) or die(__LINE__.'->'.mysql_error());
                            ?>
                            <select id="r_conti" style="width:200px;" onchange="setCountry_state_dis(this.id,this.value)">
                            <?php 
                            //while($country_row = mysql_fetch_array($country)){
                                ?>
                                <option value="<?php //echo $country_row['id']; ?>" <?php //if($country_row['id']=='96') echo "Selected"; ?>><?php //echo $country_row['country_name']; ?></option>
                                    <?php
                            //}
                            ?>  
                            </select>
                        </td>
                        <td>State:</td><td><select id="selrsti" style="width:204px" onchange="getDistrict('R',this.id,this.value)"><option value="">Select</option>
                            <?php
                            /*$st_q = "SELECT id_no State_code, Name
                                    FROM `state`
                                    WHERE District_code =0
                                    AND Sub_Dist_code =0
                                    AND Village_code =0
                                    AND display = 'Y'
                                    AND State_code < 100
                                    ORDER BY Name";
                            $st_rs = mysql_query($st_q);
                            while($st_row = mysql_fetch_array($st_rs))
                            {
                                if($st_row['State_code']=='23')
                                {*/
                                ?>
                                <option value="<?php //echo $st_row['State_code']?>" selected><?php //echo $st_row['Name']?></option>    
                                <?php    
                                /*}
                                else
                                {*/
                                ?>
                                <option value="<?php //echo $st_row['State_code']?>"><?php //echo $st_row['Name']?></option>    
                                <?php
                                /*}
                            }*/
                            ?>
                            </select></td>
                        <td>District:</td><td><select id="selrdisi" style="width:203px"><option value="">Select</option>
                               
                            </select></td>
                    </tr>
                    <tr align="left">
                        <td>Phone/Mobile:</td><td><input type="text" id="rmobi" style="width:200px" maxlength="14" onkeypress="return onlynumbers(event)" onblur="remove_apos(this.value,this.id)"/></td>
                        <td>Email Id:</td><td><input type="text" id="remaili" style="width:200px" onblur="remove_apos(this.value,this.id)"/></td>
                        <td>Total Res(s):</td><td><input type="text" id="r_noi" size="3" maxlength="4" onkeypress="return onlynumbers(event)" value="1"/></tr>
                </table>
            </div>
            
            <div id="for_D_r" style="display: none">
                <table border="0" style="border-collapse: collapse" width="100%">
                    <tr align="left">
                        <td id='for_D_r_sn1'>State Name:<input type="checkbox" id="res_causetitle1" checked=""/></td>
                        <td id='for_D_r_sn2'><input type="text" id="res_statename" style="width:200px" onkeypress="return onlyalphab(event)" onblur="remove_apos(this.value,this.id)"/>
                            <input type="hidden" id="res_statename_hd"/></td>
                        <td>Department:<input type="checkbox" id="res_causetitle2" checked=""/></td><td><input type="text" id="res_deptt" style="width:200px" onkeypress="return onlyalphab(event)" onblur="remove_apos(this.value,this.id)"/>
                            <input type="hidden" id="res_deptt_code"/></td>
                        <td>Post:<input type="checkbox" id="res_causetitle3"/></td><td><input type="text" id="res_post" style="width:200px" onkeypress="return onlyalphab(event)" onblur="remove_apos(this.value,this.id)"/><!--onblur="get_a_d_code(this.id)"--></td>
                        <input type="hidden" id="res_post_code"/>
                        <?php
                        //$select_deptcode2 = "select deptcode,deptname from deptt where deptype='S' and dm!=0 and display='Y' order by dm,d1,d2";
                        //$select_deptcode2 = mysql_query($select_deptcode2) or die(__LINE__.'->'.  mysql_error());
                        ?>
                        <!--<td class="state_r">State Dept.:</td><td class="state_r">
                            <input list="state_res_department" style="width:200px" id="state_department_in_res" onblur="check_for_right_selection(this.id)" />
                        <datalist id="state_res_department" >
                        <?php
                        /*while($ro_for_department = mysql_fetch_array($select_deptcode2))
                        {*/
                            ?>
                            <option value="<?php //echo $ro_for_department['deptcode'].'->'.$ro_for_department['deptname'];?>"><?php //echo $ro_for_department['deptname'];?></option>
                                <?php
                            
                        //}
                        ?>
                        </datalist>
                        </td>-->
                    </tr>
                    <tr align="left"><td>Address:</td><td><input type="text" id="raddd" style="width:200px" onblur="remove_apos(this.value,this.id)"/></td>
                        <td>Tehsil/City:</td><td><input type="text" id="rcityd" style="width:200px" onkeypress="return onlyalpha(event)" onblur="remove_apos(this.value,this.id)"/></td>
                        <td>Pin:</td><td><input type="text" id="rpind" style="width:200px;" maxlength="6" onkeypress="return onlynumbers(event)" onblur="remove_apos(this.value,this.id)"/></td></tr>
                    <tr align="left">
                        <td style="border:none">Country:</td><td style="border:none">
                            <?php
                            //$country = "SELECT country_name,id FROM country WHERE display='Y' ORDER BY country_name";
                            //$country = mysql_query($country) or die(__LINE__.'->'.mysql_error());
                            ?>
                            <select id="r_contd" style="width:200px;" onchange="setCountry_state_dis(this.id,this.value)">
                            <?php 
                            //while($country_row = mysql_fetch_array($country)){
                                ?>
                                <option value="<?php //echo $country_row['id']; ?>" <?php //if($country_row['id']=='96') echo "Selected"; ?>><?php //echo $country_row['country_name']; ?></option>
                                    <?php
                            //}
                            ?>  
                            </select>
                        </td>
                        <td>State:</td><td><select id="selrstd" style="width:204px;" onchange="getDistrict('R',this.id,this.value)"><option value="">Select</option>
                            <?php
                            /*$st_q = "SELECT id_no State_code, Name
                                    FROM `state`
                                    WHERE District_code =0
                                    AND Sub_Dist_code =0
                                    AND Village_code =0
                                    AND display = 'Y'
                                    AND State_code < 100
                                    ORDER BY Name";
                            $st_rs = mysql_query($st_q);
                            while($st_row = mysql_fetch_array($st_rs))
                            {
                                if($st_row['State_code']=='23')
                                {*/
                                ?>
                                <option value="<?php //echo $st_row['State_code']?>" selected><?php //echo $st_row['Name']?></option>    
                                <?php    
                                /*}
                                else
                                {*/
                                ?>
                                <option value="<?php //echo $st_row['State_code']?>"><?php //echo $st_row['Name']?></option>    
                                <?php
                                /*}
                            }*/
                            ?>
                            </select></td>
                        <td>District:</td><td><select id="selrdisd" style="width:203px;"><option value="">Select</option>
                               
                            </select></td>
                    </tr>
                    <tr align="left">
                        <td>Phone/Mobile:</td><td><input type="text" id="rmobd" style="width:200px;" maxlength="14" onkeypress="return onlynumbers(event)" onblur="remove_apos(this.value,this.id)"/></td>
                        <td>Email Id:</td><td><input type="text" id="remaild" style="width:200px" onblur="remove_apos(this.value,this.id)"/></td>
                        <td>Total Res(s):</td><td><input type="text" id="r_nod" size="3" maxlength="4" onkeypress="return onlynumbers(event)" value="1"/></tr>
                </table>
            </div>
            <input type="hidden" name="hd_add_address_r" id="hd_add_address_r" value="0"/>
                <div id="dv_add_parties_r"></div>
                <div class="cl_center cl_add_address" id="ad_address_r">Add Additional Respondent Address</div>
                </th></tr>
        <tr><th ><hr></th></tr>
        <tr><td align="center">
                <table align="center" cellspacing="3" cellpadding="2" width="100%" border="0">
                    <tr><td align="left" >Main Pet. Adv.
                            <select id="padvt" onchange="changeAdvocate(this.id,this.value)">
                                <option value="A">AOR</option>
                                <option value="N">Non-AOR</option>
                                <option value="S">State</option>
                                <!--<option value="C">Central</option>-->
                                <option value="SS">Petitioner In Person</option>
                            </select>
                            <span id="padv_is_ac">Is Amicus Curiae:</span><input type="checkbox" id="is_ac" name="is_ac"/>
                            <span id="padv_state">Enrol State:</span><select name="ddl_pet_adv_state" id="ddl_pet_adv_state" disabled="true">
                                <option value="">Select</option>
                                <?php
                                /*foreach ($state as $value) {
                                $ex_explode=explode('!',$value);*/
                                ?>
                                <option value="<?php //echo $ex_explode[0]; ?>"><?php //echo $ex_explode[1] ?></option>
                                <?php
                                //}
                            ?>
                            </select>


                        &nbsp;  <span id="padvno_">AOR code/Name:/Enrol No.(Non-AOR)</span> <input type="text" id="padvno" size="25"   onchange="getAdvocate_for_main(this.id,'P')" />
                                &nbsp; <span id="padvyr_">Year:</span> <input type="text" id="padvyr" size="4" maxlength="4" onblur="getAdvocate_for_main(this.id,'P')" onkeypress="return onlynumbers(event)" disabled="true"/>
                            &nbsp; Name: <input type="text" id="padvname" size="30" disabled/>
                            &nbsp; <span id="padvmob_">Mob:</span> <input type="text" id="padvmob" size="10" maxlength="10" onkeypress="return onlynumbers(event)" disabled="true"/>
                            &nbsp; <span id="padvemail_">Email Id:</span> <input style="width:180px" type="text" id="padvemail" size="30" disabled="true"/>
                        </td></tr>
                    <tr> <td align="left" >Main Res. Adv. 
                            <select id="radvt" onchange="changeAdvocate(this.id,this.value)">
                                <option value="A">AOR</option>
                                <option value="N">Non-AOR</option>
                                <option value="S">State</option>
                                <!--<option value="C">Central</option>-->
                                <option value="SS">Respondent In Person</option>
                            </select>&nbsp;&nbsp;
                            <span id="radv_is_ac">Is Amicus Curiae:</span><input type="checkbox" id="ris_ac" name="ris_ac"/>
                            <span id="radv_state">Enrol State:</span><select name="ddl_res_adv_state" id="ddl_res_adv_state" disabled="true">
                                <option value="">Select</option>
                                  <?php
                                    /*foreach ($state as $value) {
                                    $ex_explode=explode('!',$value);*/
                                    ?>
                                    <option value="<?php //echo $ex_explode[0]; ?>"><?php //echo $ex_explode[1] ?></option>
                                    <?php
                                    //}
                                    ?>
                            </select>

                        &nbsp; <span id="radvno_">AOR code/Name:/Enrol No.(Non-AOR)</span> <input type="text" id="radvno" size="25"  onchange="getAdvocate_for_main(this.id,'R')" />
                            &nbsp; <span id="radvyr_">Year:</span> <input type="text" id="radvyr" size="4" maxlength="4" onblur="getAdvocate_for_main(this.id,'R')" onkeypress="return onlynumbers(event)" disabled="true"/>
                            &nbsp; Name: <input style="width:180px; background-color: white;color:black" type="text" id="radvname" size="30" disabled/>
                            &nbsp; <span id="radvmob_">Mob:</span> <input type="text" id="radvmob" size="10" maxlength="10" onkeypress="return onlynumbers(event)" disabled="true"/>
                            &nbsp; <span id="radvemail_">Email Id:</span> <input style="width:180px" type="text" id="radvemail" size="30" disabled="true"/>
                        </td></tr>
                </table>
            </td></tr>
         
        <tr><th ><hr></th></tr>
        <tr><th >
                <input type="button" value="Save" onclick="call_save_main('0')" id="svbtn" onkeydown="if (event.keyCode == 13) document.getElementById('svbtn').click()"/> &nbsp;
                <input type="button" value="Reset" id="rstbtn" onclick="call_fullReset_main()" onkeydown="if (event.keyCode == 13) document.getElementById('rstbtn').click()" /></th></tr>
        </table>
            <input type="hidden" name="hd_p_barid" id="hd_p_barid"/>
            <input type="hidden" name="hd_r_barid" id="hd_r_barid"/>
    </div>
           <input type="hidden" name="hd_current_date" id="hd_current_date" value="<?php echo date('d-m-Y') ?>"/>
        </div>
    </body>
</html>
<?php
//mysql_close();
?>
<?php

//}
?>

<script>
    function check(browser) {
      // // document.getElementById("answer").value=browser;
      // alert(browser);

        if(browser=='c')   // case type is selected
        {
            document.getElementById('diary_no').value='';
            document.getElementById('dyr').value='';


            document.getElementById('diary_no').disabled=true;
            document.getElementById('dyr').disabled=true;


            document.getElementById('no').disabled = false;
            document.getElementById('ddl_nature_sci').disabled=false;

            document.getElementById('t_h_cyt').disabled = false;


        }
        else
        {


            document.getElementById('no').value = '';
            document.getElementById('ddl_nature_sci').value = '';

            document.getElementById('t_h_cyt').value = '';
            document.getElementById('no').disabled = true;
            document.getElementById('ddl_nature_sci').disabled=true;

            document.getElementById('t_h_cyt').disabled = true;
            document.getElementById('diary_no').disabled=false;
            document.getElementById('dyr').disabled=false;


        }

    }
</script>
