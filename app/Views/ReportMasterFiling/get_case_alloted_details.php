<style>
.table-striped tr:nth-child(odd) td {
    background: #fff !important;
    box-shadow: none;
    border: 1px solid #8080805e;
    text-align: center;
}

.table-striped tr:nth-child(even) td {
    background: #f5f5f5;
	border: 1px solid #8080805e;
    text-align: center;
}
</style>

<?php 
if(!empty($result_array)){ ?>
<div class="table-responsive">
 <table class="table table-striped custom-table" id="example1">
  <thead>
   <tr>
        <th <?php if($ddl_users=='103' || $ddl_users=='108'){ ?> rowspan="3" <?php } else {  ?> rowspan="2" <?php } ?>>S.No.</th>
        <th  <?php  if($ddl_users=='103' || $ddl_users=='108'){ ?> rowspan="3" <?php } else {  ?> rowspan="2" <?php } ?>>Employee Name</th>
        <th <?php  if($ddl_users=='103' || $ddl_users=='108'){ ?> colspan="6" <?php } else {  ?> colspan="3" <?php } ?>>Total Cases</th>
        <?php
        if($ddl_users=='103' || $ddl_users=='108'){
        ?>  
        <th  colspan="2">Total Pendency till <?php echo date('d-m-Y',strtotime(date('d-m-Y'))); ?> from 01-06-2018</th>
        <?php }
        else if($ddl_users!='101' && $ddl_users!='109'){
        ?>  
        <th rowspan="2">Total Pendency till <?php echo date('d-m-Y'); ?></th>
        <?php }
        if($ddl_users!='109')
        {
        ?>
        <th <?php if($ddl_users=='103' || $ddl_users=='108'){ ?> colspan="4" <?php } else {?> colspan="2" <?php } ?> >
          Total Work Done  
        </th>
        <?php } ?>
    </tr>
	<?php
        if($ddl_users=='103'){?>  
			<tr>
				<th colspan="3">Filing</th>
				 <th colspan="3">Re-Filing</th>
				   <th rowspan="2">Filing</th>
				 <th rowspan="2">Re-Filing</th>
				 <th colspan="2">Filing</th>
				  <th colspan="2">Re-Filing</th>
			</tr>
     <?php } else if($ddl_users=='108'){ ?>  
			<tr>
				<th colspan="3">Dispatched</th>
				 <th colspan="3">Received</th>
				   <th rowspan="2">Dispatched</th>
				 <th rowspan="2">Received</th>
				  <th colspan="2">Dispatched</th>
				  <th colspan="2">Received</th>
			</tr>
    <?php } ?>
    <tr>
			<th>Alloted</th>
			<th>Completed</th>
			<th>Remaining</th>
          <?php if($ddl_users=='103' || $ddl_users=='108'){ ?>  
				 <th>Alloted</th>
				 <th>Completed</th>
				 <th>Remaining</th>
				   <th>Received</th>
				<th>Dispatched</th>
          <?php } if($ddl_users!='109') {?>
			<th>Received</th>
			<th>Dispatched</th>
        <?php } ?>
    </tr>
</thead>	
<tbody>
   <?php
$sno=1;
$tot_alloted=0;
$tot_comp=0;
$tot_rem=0;
$tot_s=0;
$tot_ref=0;
$tot_ss=0;
$tot_ssr=0;
$tot_ssrs=0;
$tot_sss=0;
$tot_allotedr=0;
$tot_compr=0;
$tot_remr=0;
     if($ddl_users == '105' || $ddl_users == '108'){
		  $record = $result_array['result1'];
	 } else{
		$record = $result_array;
	 } 
	  foreach($record as $row){
	 ?>
	 <tr>
        <td>
            <?php echo $sno;  ?>
        </td>
        <td>
            <?php echo get_user_name_info($row['d_to_empid']);?>
        </td>
        <td>
            <?php
            if($ddl_users=='101' ||  $ddl_users=='109')
            {
                $row['s']=$row['ss'];
            }
            ?>
            <span id="spallot_<?php echo $sno; ?>" class="cl_hover" onclick="get_rec(this.id)"><?php $tot_alloted=$tot_alloted+$row['s']; echo $row['s']; ?></span>
         </td>
         <td>
            <span id="spcomp_<?php echo $sno; ?>" class="cl_hover" onclick="get_rec(this.id)"><?php $tot_comp=$tot_comp+$row['ss']; echo $row['ss']; ?></span>
        </td>
        <td>
            <span id="spnotcomp_<?php echo $sno; ?>" class="cl_hover" onclick="get_rec(this.id)">
			<?php  $tot_rem=$tot_rem+$row['s']-$row['ss'];  echo $row['s']-$row['ss']; ?></span>
        </td>
        <?php  if($ddl_users=='103' || $ddl_users=='108'){ ?>
         <td>
            <span id="spallotr_<?php echo $sno; ?>" class="cl_hover" onclick="get_rec(this.id)"><?php $tot_allotedr=$tot_allotedr+$row['r_s']; echo $row['r_s']; ?></span>
        </td>
         <td>
            <span id="spcompr_<?php echo $sno; ?>" class="cl_hover" onclick="get_rec(this.id)"><?php $tot_compr=$tot_compr+$row['r_ss']; echo $row['r_ss']; ?></span>
        </td>
        <td>
            <span id="spnotcompr_<?php echo $sno; ?>" class="cl_hover" onclick="get_rec(this.id)"><?php
                $tot_remr=$tot_remr+$row['r_s']-$row['r_ss'];  echo $row['r_s']-$row['r_ss'];
            ?></span>
        </td>
        <td>
            <span id="sptotpen_<?php echo $sno; ?>" class="cl_hover" onclick="get_rec(this.id)"><?php $tot_s=$tot_s+$row['sss']; echo $row['sss']; ?></span>
           
        </td>
        <td>
             <span id="sptotref_<?php echo $sno; ?>" class="cl_hover" onclick="get_rec(this.id)"><?php $tot_ref=$tot_ref+$row['r_sss']; echo $row['r_sss']; ?></span>
        </td>
        <td>
          <span id="sptotpenr_<?php echo $sno; ?>" class="cl_hover" onclick="get_rec(this.id)"><?php $tot_ssrs=$tot_ssrs+$row['sssss']; echo $row['sssss']; ?></span>
        </td>
         <td>
            <span id="sptwd_<?php echo $sno; ?>" class="cl_hover" onclick="get_rec(this.id)"><?php $tot_ss=$tot_ss+$row['ssss']; echo $row['ssss']; ?></span>
        </td>
        <td>
             <span id="sptwdr_<?php echo $sno; ?>" class="cl_hover" onclick="get_rec(this.id)"><?php $tot_sss=$tot_sss+$row['r_sssss']; echo $row['r_sssss']; ?></span>
        </td>
         <td>
            <span id="sptwdd_<?php echo $sno; ?>" class="cl_hover" onclick="get_rec(this.id)"><?php $tot_ssr=$tot_ssr+$row['r_ssss']; echo $row['r_ssss']; ?></span>
        </td>
        <?php }else if($ddl_users!='101' && $ddl_users!='109'){?>
        <td>
            <span id="sptotpen_<?php echo $sno; ?>" class="cl_hover" onclick="get_rec(this.id)"><?php $tot_s=$tot_s+$row['sss']; echo $row['sss']; ?></span>
           
        </td>
        <td>
            <span id="sptotpenr_<?php echo $sno; ?>" class="cl_hover" onclick="get_rec(this.id)"><?php $tot_ssrs=$tot_ssrs+$row['sssss']; echo $row['sssss']; ?></span></td>
        <td>
            <span id="sptwd_<?php echo $sno; ?>" class="cl_hover" onclick="get_rec(this.id)"><?php $tot_ss=$tot_ss+$row['ssss']; echo $row['ssss']; ?></span>
        </td>
        <?php }?>
         <input type="hidden" name="hd_nm_id<?php echo $sno; ?>" id="hd_nm_id<?php echo $sno; ?>" value="<?php echo $row['d_to_empid']; ?>"/>
    </tr>
<?php
$sno++;
}
	 if($ddl_users == '105' || $ddl_users == '108'){
			foreach($result_array['result2'] as $row){?>
			<tr>
				<th colspan="6">
					Cases alloted and pending in <?php if($ddl_users=='105') { ?> CATEGORIZATION SUB-SECTION <?php } 
					else if($ddl_users=='108') { ?> FILING DISPATCH RECEIVE <?php } ?>
				</th>
			</tr>
			<tr>
				<td>
					<?php echo $sno;  ?>
				</td>
			<td>
				 <?php echo get_user_name_info($row['d_to_empid']);?>
			</td>
            <td>
               <span id="spallot_<?php echo $sno; ?>" class="cl_hover" onclick="get_rec(this.id)"><?php $tot_alloted=$tot_alloted+$row['s']; echo $row['s']; ?></span>
            </td>
         <td> </td>
			<td>
			   <span id="spnotcomp_<?php echo $sno; ?>" class="cl_hover" onclick="get_rec(this.id)"><?php
			  $tot_rem=$tot_rem+$row['s'];  echo $row['s'];
				?></span>
			</td>
     <?php  if($ddl_users=='108'){  ?>
         <td>
            <span id="spallotr_<?php echo $sno; ?>" class="cl_hover" onclick="get_rec(this.id)"><?php $tot_allotedr=$tot_allotedr+$row['r_s']; echo $row['r_s']; ?></span>
         </td>
         <td>
            <span id="spcompr_<?php echo $sno; ?>" class="cl_hover" onclick="get_rec(this.id)"><?php $tot_compr=$tot_compr+$row['r_ss']; echo $row['r_ss']; ?></span>
        </td>
        <td>
            <span id="spnotcompr_<?php echo $sno; ?>" class="cl_hover" onclick="get_rec(this.id)"><?php
          $tot_remr=$tot_remr+$row['r_s']-$row['r_ss'];  echo $row['r_s']-$row['r_ss'];
            ?></span>
        </td>
        <td>
            <span id="sptotpen_<?php echo $sno; ?>" class="cl_hover" onclick="get_rec(this.id)"><?php $tot_s=$tot_s+$row['sss']; echo $row['sss']; ?></span>
           
        </td>
        <td>
             <span id="sptotref_<?php echo $sno; ?>" class="cl_hover" onclick="get_rec(this.id)"><?php $tot_ref=$tot_ref+$row['r_sss']; echo $row['r_sss']; ?></span>
        </td>
        <?php } else { ?>
        <td>
            <span id="sptotpen_<?php echo $sno; ?>" class="cl_hover" onclick="get_rec(this.id)"><?php $tot_s=$tot_s+$row['sss']; echo $row['sss']; ?></span>
           
        </td>
         <?php } ?>
        <input type="hidden" name="hd_nm_id<?php echo $sno; ?>" id="hd_nm_id<?php echo $sno; ?>" value="<?php echo $row['d_to_empid']; ?>"/>
    </tr>
 <?php }} ?>		
      <tr>
         <th colspan="2" style="text-align: center; border: 1px solid #8080805e;">Total</th>
         <td><span id="spallot_0" class="cl_hover" onclick="get_rec(this.id)"><?php echo $tot_alloted; ?></span></td>
         <td><span id="spcomp_0" class="cl_hover" onclick="get_rec(this.id)"><?php echo $tot_comp; ?></span></td>
         <td><span id="spnotcomp_0" class="cl_hover" onclick="get_rec(this.id)"><?php echo $tot_rem; ?></span></td>
           <?php if($ddl_users=='103' || $ddl_users=='108'){  ?>
			<td><span id="spallotr_0" class="cl_hover" onclick="get_rec(this.id)"><?php echo $tot_allotedr; ?></span></td>
			<td> <span id="spcompr_0" class="cl_hover" onclick="get_rec(this.id)"><?php echo $tot_compr; ?></span></td>
            <td> <span id="spnotcompr_0" class="cl_hover" onclick="get_rec(this.id)"><?php echo $tot_remr; ?></span></td>
			<td> <span id="sptotpen_0" class="cl_hover" onclick="get_rec(this.id)"><?php echo $tot_s; ?></span></td>
			<td> <span id="sptotref_0" class="cl_hover" onclick="get_rec(this.id)"><?php echo $tot_ref; ?></span></td>
			<td>
				<span id="sptotpenr_0" class="cl_hover" onclick="get_rec(this.id)"><?php echo $tot_ssrs; ?></span>
			</td>
			<td>
				<span id="sptwd_0" class="cl_hover" onclick="get_rec(this.id)"><?php echo $tot_ss; ?></span>
			</td>
			<td>
				<span id="sptwdr_0" class="cl_hover" onclick="get_rec(this.id)"><?php echo $tot_sss; ?></span>
			</td>
			 <td>
				<span id="sptwdd_0" class="cl_hover" onclick="get_rec(this.id)"><?php echo $tot_ssr; ?></span>
			</td>
        <?php } else if($ddl_users!='101' && $ddl_users!='109'){ ?>
			<td> <span id="sptotpen_0" class="cl_hover" onclick="get_rec(this.id)"><?php echo $tot_s; ?></span></td>
			<td><span id="sptotpenr_0" class="cl_hover" onclick="get_rec(this.id)"><?php echo $tot_ssrs; ?></span></td>
           <td>
             <span id="sptwd_0" class="cl_hover" onclick="get_rec(this.id)"><?php echo $tot_ss; ?></span>
			</td>
         <?php } ?>
    </tr>
</tbody>   
</table>

<?php } ?>
<div id="dv_sh_hd" style="display: none;position: fixed;top: 0;width: 100%;height: 100%;background-color: black;opacity: 0.6;left: 0;overflow: hidden;z-index: 103">
    &nbsp;
</div>


<div id="dv_fixedFor_P" style="text-align: center;position: fixed;top:0;display: none;
	left:0;
	width:100%;
	height:100%;z-index: 105">
    <span id="sp_close" style="display: none;text-align: right;cursor: pointer" onclick="closeData()"><b><img src="../images/close_btn.png" /></b></span>
    <div style="width: auto;background-color: white;overflow: scroll;height: 500px;margin-left: 50px;margin-right: 50px;margin-bottom: 25px;margin-top: 1px;word-wrap: break-word;" id="ggg" onkeypress="return  nb(event)">
    </div>
</div>