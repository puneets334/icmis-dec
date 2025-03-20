
<div id="dv_gdcs">
    <!-- <table width="100%" class="table_tr_th_w_clr c_vertical_align"> -->
    <table width="100%" align="center" style="margin-top: 30px" id="tb_nmsz" class="c_vertical_align tbl_border" cellpadding="5" cellspacing="5">
    <tr>
         <th width="10%">
        Case Nature
    </th>
         <th width="9%">
        Case Type
    </th>
    <th width="49%">Heading</th>
     <th width="32%">Category</th>
    </tr>
<tr >
    <td>
        <select name="ddl_cas_natures" id="ddl_cas_natures" onchange="get_nat_types()" style="width: 100%">
            <option value="0">Select</option>
            <?php
            // $sq_cs_na=  mysql_query("SELECT distinct nature from  casetype where display='Y' order by nature");
            // while ($row3 = mysql_fetch_array($sq_cs_na))
            foreach ($case_type as $row3)
            {
                ?>
            <option value="<?php echo $row3['nature']; ?>"><?php if($row3['nature']=='C') { ?>Civil <?php ;} else if($row3['nature']=='R') { ?>Criminal <?php ;} else if($row3['nature']=='W') { ?>Writ <?php } ?></option>
            
             <?php
            }
            ?>
        </select>
    </td>
    <td>
        
        <select name="ddl_mn_cs_tys" id="ddl_mn_cs_tys" multiple="multiple" class="input_style form-control multipleselect" style="height: 200px;width: 100%">
            <option value="">Select</option>
      
       
              </select>
    </td>
    
    <td>
          <select name="srcList_mons" id="srcList_mons" class="input_style form-control multipleselect" multiple="multiple" size="6" style=" height: 200px;width: 100%">                        
                              <option value="">Select</option>
                          
                          </select>
    </td>
    <td>
        
         <select name="rdn_before_cks" id="rdn_before_cks" >
             <option value="">Select</option>
            <option value="B">Before</option>
            <option value="N">Not Before</option>
        </select> <br/><br/>
       <select name="m_cats" id="m_cats" onChange="getcats(this.value)" style="width:200px">
	<option value="">Select</option>
	<?php
	// $quer2=mysql_query("select  id,sub_name1,flag,category_sc_old  from submaster where   display='Y' and subcode2=0 and subcode3=0 and subcode4=0 order by subcode1,subcode2, subcode3, subcode4"); 
	// while($row1= mysql_fetch_array($quer2)) 
    foreach ($sub_master as $row1)
    { 
	?>
        <option  value="<?php echo $row1['id'];?>" class="<?php if($row1['flag']=='s'){ echo 'cl_supreme';}else{ echo 'cl_other';}?>"><?php if($row1['flag']=='s'){ echo $row1['category_sc_old'].'-'; } echo $row1['sub_name1'] ?></option>
	 
 <?php   }?>
</select>
        <br/><br/>
         <select name="cats" id="cats" style="width:200px" onchange="getsubcats(m_cats.value,this.value)">
       <option value="">Select Category</option> 
     
   </select>
            <br/><br/>
             <select name="subcat" id="subcats" style="width:200px" onchange="get_sub_sub_cat(this.value,this.id)">
	<option value="">Select Category First</option>
       
        </select>
        <br/><br/>
             <select name="sub_sub_cats" id="sub_sub_cats"  style="width: 200px">
	<option value="">Select Category First</option>
       
        </select>
    </td>
</tr>
<tr>
    <td colspan="6" style="text-align: center">
        <input type="button" name="btnAdd_dts" class="btn btn-primary" id="btnAdd_dts" value="ADD" onclick="add_mo_recs()"/>
    </td>
</tr>
<tr>
    <td colspan="6" style="text-align: center">
        <table id="tb_new_mos" width="100%" style="background-color: white"></table>
    </td>
</tr>
</table>
<input type="hidden" name="hd_tb_new_mos" id="hd_tb_new_mos"/>
</div>
