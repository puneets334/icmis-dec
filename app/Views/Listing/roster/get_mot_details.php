<style>
.cl_other {
    -webkit-appearance: none;
    -moz-appearance: none !important;
    padding: 4px 8px !important;
    border: none !important;
    box-shadow: none !important;
    color: blue !important;
}
.cl_supreme {
    -webkit-appearance: none;
    -moz-appearance: none !important;
    padding: 4px 8px !important;
    border: none !important;
    box-shadow: none !important;
    color: green !important;
}
.select2-search__field{
    width: 85% !important;
}
table.tbl_border.c_vertical_align td {
    margin-top: 2px;
    vertical-align: top;
}
.select2-container .select2-search--dropdown .select2-search__field {
  background: url('./images/select2.png') right top no-repeat;
  background-position: right -22px;
}

@media only screen and (-webkit-min-device-pixel-ratio: 1.5), only screen and (min-resolution: 2dppx)  {
   .select2-container--default .select2-search--dropdown .select2-search__field {
      background-image: url(select2x2.png);
      background-repeat: no-repeat;
      background-size: 60px 40px;
      background-position: 100% -21px;
   }
}
</style>

<div id="dv_gdc">
<table width="100%" class="tbl_border c_vertical_align">
    <tr>
         <th width="10%">
        Case<br/> Nature
    </th>
         <th width="9%">
        Case Type
    </th>
    <th width="49%">Heading</th>
     <th width="32%">Category</th>
    </tr>
<tr >
    <td>
        <select name="ddl_cas_nature" id="ddl_cas_nature" class="form-control" onchange="get_nat_type()" style="width: 100%;">
            <option value="0">Select</option>
            <?php
			 $db = \Config\Database::connect();
            $query=  $db->query("SELECT distinct nature from  master.casetype where display='Y' order by nature");
			$sq_cs_na = $query->getResultArray();
			//$sq_cs_na = is_data_from_table('master.casetype',  " display='Y' order by nature ", " DISTINCT nature ", 'A');
			if(!empty($sq_cs_na))
			{
				foreach($sq_cs_na as $row3)
				{
					?>
				<option value="<?php echo $row3['nature']; ?>"><?php if($row3['nature']=='C') { ?>Civil <?php ;} else if($row3['nature']=='R') { ?>Criminal <?php ;} else if($row3['nature']=='W') { ?>Writ <?php } ?></option>
				
				 <?php
				}
			}
            ?>
        </select>
    </td>
    <td>
        
        <select name="ddl_mn_cs_ty" id="ddl_mn_cs_ty" multiple="multiple" class="form-control multipleselect" style="height: 200px; min-width:120px !important;">
            <option value="" disabled >Select</option>
        </select>
    </td>
    
    <td>
        <select name="srcList_mon" id="srcList_mon" class="input_style form-control multipleselect" multiple="multiple" size="6" style=" height: 200px;width: 100%;">                        
            <option value="" disabled>Select</option>
        </select>
    </td>
    <td>
        <select name="rdn_before_ck" id="rdn_before_ck" class="form-control">
            <option value="">Select</option>
            <option value="B">Before</option>
            <option value="N">Not Before</option>
        </select>
        <br/><br/>
        <select name="m_cat" id="m_cat" onChange="getcat(this.value)" style="width: 100%" class="form-control">
	        <option value="">Select</option>
	<?php
    /* 	$quer2=mysql_query("select  id,sub_name1,flag,category_sc_old   from submaster where   display='Y' and subcode2=0 and 
            subcode3=0 and subcode4=0 order by subcode1,subcode2, subcode3, subcode4");  */
			
	$quer2 = is_data_from_table('master.submaster'," display = 'Y' and subcode2 = '0' and 
            subcode3=0 and subcode4=0 order by subcode1,subcode2, subcode3, subcode4 ", " id,sub_name1,flag,category_sc_old ", 'A');
		 
	if(!empty($quer2))
	{
		foreach($quer2 as $row1) 
		 { 		 
			?>
			<option  value="<?php echo $row1['id'];?>" class="<?php if($row1['flag']=='s'){ echo 'cl_supreme'; }else{ echo 'cl_other';} ?>"><?php if($row1['flag']=='s'){ echo $row1['category_sc_old'].'-'; }  echo $row1['sub_name1']; ?></option>
		 
	 <?php }
	 }?>
</select>
        <br/><br/>
         <select name="cat" id="cat"  onchange="getsubcat(m_cat.value,this.value)" class="form-control" style="width: 100%">
       <option value="">Select Category</option> 
     
   </select>
            <br/><br/>
            <select name="subcat" id="subcat"  style="width: 100%" class="form-control" onchange="get_sub_sub_cat(this.value,this.id)">
	<option value="">Select Category First</option>
       
        </select>
        <br/><br/>
             <select name="sub_sub_cat" id="sub_sub_cat"  style="width: 100%" class="form-control">
	<option value="">Select Category First</option>
       
        </select>
    </td>
</tr>
<tr>
    <td colspan="6" style="text-align: center">
        <input type="button" name="btnAdd_dt" id="btnAdd_dt" value="ADD" onclick="add_mo_rec()"/>
    </td>
</tr>
<tr>
    <td colspan="6" style="text-align: center">
        <table id="tb_new_mo" width="100%" style="background-color: white" class="tbl_border c_vertical_align" border="0"></table>
    </td>
</tr>
</table>
<input type="hidden" name="hd_tb_new_mo" id="hd_tb_new_mo"/>
</div>
 