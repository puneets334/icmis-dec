<?php  
 
if(!empty($result)){
    ?>
<div class="table-responsive">	
<table class="table table-striped table-bordered custom-table" border="1" cellspacing="2" cellpadding="4">
   <thead>
    <tr><th rowspan="2">SNo.</th><th rowspan="2">Section</th><th rowspan="2">Name</th><th rowspan="2">Designation</th>
        <th colspan="2">Document</th>
        <th colspan="2">Cases Updated for Listing</th>
        <th rowspan="2">No. of Office<br>Report Prepared</th>
        <th colspan="3">Notice</th>
        <th rowspan="2">Red Category Cases</th>
    </tr>
    <tr>
        <th class="no-border-radius">Filed</th>
        <th>Pending for verification as on <?php echo $_REQUEST['date']; ?></th>
        <th>By DA</th>
        <th>By Supuser</th>
        <th>Prepared</th>
        <th>Not Prepared (Pending)</th>
        <th class="no-border-radius">Not Prepared (Disposed)</th>
    </tr>
	</thead>
	<tbody>
    <?php
    $sno=1;
    foreach($result as $row){
        ?>
    <tr><td><?php echo $sno;?></td><td><?php echo $row['section_name']; ?></td>
        <td><?php echo "<span id='name_$row[usercode]'>".$row['name'].'/'.$row['empid']."</span>";?></td>
        <td><?php echo $row['type_name'];?></td>
        <td><?php echo "<span style='cursor:pointer' id='doc_$row[usercode]'>".$row['totdoc']."</span>"; ?></td>
        <td style="background:#F08080 !important;">
            <span style='cursor:pointer' id='notvdoc_<?php echo $row['usercode']?>'><?php echo (!empty($row['totdoc_not'])) ?  $row['totdoc_not'] : "" ?></span> 
        </td>
        <td><?php echo "<span style='cursor:pointer' id='totup_$row[usercode]'>".$row['totup']."</span>"; ?></td>
        <td>
            <?php echo "<span style='cursor:pointer' id='supuser_$row[usercode]'>".$row['supuser']."</span>"; ?>
        </td>
        <td><?php echo $row['totoff']; ?></td>
        <td><?php echo $row['totnot']; ?></td>
        <td style="background:#F08080;"><?php echo $row['p_notice_not_made'] ?? '-'; ?></td>
        <td style="background:#F08080;"><?php echo $row['d_notice_not_made'] ?? '-'; ?></td>
        <td style="background:#F08080;"><?php echo $row['red'] ?? '-'; ?></td>
    </tr>
            <?php
            $sno++;
    }
    ?>
	</tbody>
</table>
</div>
        <?php
}
else{
    ?>
<div style="text-align:center;color:red">SORRY, NO RECORD FOUND!!!</div>
        <?php
}

 
?>

<div id="dv_sh_hd" style="display: none;position: fixed;top: 0;width: 100%;height: 100%;background-color: black;opacity: 0.6;left: 0;overflow: hidden;z-index: 103">
</div>
<div id="dv_fixedFor_P" style="display: none;position: fixed;top: 0;left: 0;width: 100%;height: 100%;z-index: 105">
    <!--<div id="sp_close" style="text-align: right;cursor: pointer;width: 40px;float: right" onclick="closeData()">
        <img src="close_btn.png" style="width: 30px;height: 30px;">
    </div>-->
    <div id="sar1" style="background-color: white;overflow: auto;margin: 60px 250px 30px 250px;height: 80%;">
        <div id="sp_close" style="text-align: right;cursor: pointer;float: right" >
            <img src="../images/close_btn.png" style="width: 20px;height: 20px;">
        </div>
        <div id="sar" style="border: 0px solid red"></div>
    </div>
</div>
