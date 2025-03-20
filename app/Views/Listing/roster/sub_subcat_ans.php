<?php

// while($subcat1=mysql_fetch_array($quer3)) 
if(!empty($catSubMaster))
{
	foreach ($catSubMaster as $subcat1)
	{ 
?>
	<option  value="<?php echo $subcat1['id']?>" class="<?php if($subcat1['flag']=='s'){?>cl_supreme<?php ;}else{?>cl_other<?php ;}?>"> <?php echo $subcat1['sub_name4'] ?></option>;
<?php
	}
}else{
	echo '<option value="">Select Category First</option>';
}
?>