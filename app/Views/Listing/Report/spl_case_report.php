<div id="prnnt" style="text-align: center; font-size:10px; padding-bottom:50px;">
    <H3>Special Cases Available for Dated <?php echo  $list_dt; ?>   (<?php echo $mainhead_descri; ?> )</H3>
<?php
if(!empty($getlist)){
?>      
<table align="left" width="100%" border="0px;" style="font-size:10px; table-layout: fixed;">  
<tr style="background: #918788;">
    <td width="4%" style="font-weight: bold; color: #000;">SrNo.</td>     
    <td width="30%" style="font-weight: bold; color: #000;">Coram</td>
    <td width="4%" style="font-weight: bold; color: #000;">Total</td>
    <td width="25%" style="font-weight: bold; color: #000;">Reg No./ Diary No.</td>
    <td width="25%" style="font-weight: bold; color: #000;">Advocate</td>    
    <td width="12%" style="font-weight: bold; color: #000;">Action</td>
</tr>
<?php echo $getlist; ?>
</table>
</div>
<?php }else{
    echo 'No Recrods Found';
} ?>  
<br><br><br><br><br><br><br><br><br><br> 

<div >   
<input name="prnnt1" type="button" id="prnnt1" value="Print" >
</div> 