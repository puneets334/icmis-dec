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
 <?php if(!empty($sql_get_oc)){?>
     <tr><th colspan="10">
				<?php
				foreach($sql_get_oc as $ro_oc){
					if(($flag == 2 OR $flag == 3) AND $ro_oc[usertype] == 14){
						echo $ro_oc['name'].', Branch Officer';
					}
					if(($flag == 4 OR $flag == 5) AND $ro_oc[usertype] == 9){
						echo $ro_oc['name'].', Assistant Registrar';
					}
					if(($flag == 6 OR $flag == 7 OR $flag == 8 OR $flag == 9) AND ($ro_oc['usertype'] == 6 OR $ro_oc['usertype'] == 4)){
						echo $ro_oc['name'];
					if($ro_oc['usertype'] == 6)
							echo ", Deputy Registrar";
					if($ro_oc['usertype'] == 4)
							echo ", Additional Registrar";
					}
				}
				?>
      </th></tr>
 <?php }?>
      <tr><th colspan="11" style="text-align: center;"><b>Case Verification for updated on <?php echo $date;?> For DA- <?php echo $name;?></b></th></tr>
      <tr>
			<th>SNo.</th><th>Case No / Diary No</th><th>Next Dt</th><th>Listable</th>
			<th>Head</th><th>Purpose of List</th><th>Previous Remark</th><th>Statutary</th>
			<th>ROP</th><th>Entry Dt/Updated By</th>
			<?php if($_SESSION['login']['usertype'] == 1 OR $flag == 3 OR $flag == 5 OR $flag == 7 OR $flag == 9) {  ?>
			<th>Action</th>
				<?php } ?>
       </tr>
	   <?php
           $sno=1;
                foreach($result_array as $row){
                    $verify_str = $row['diary_no']."_".$row['board_type']."_".$row['mainhead']."_".$row['next_dt'];
                ?>
                    <tr id="<?php echo $verify_str; ?>" >
                        <td><?php echo $sno;?></td>
                        <td><?php echo $row['reg_no_display']." @ ".substr($row['diary_no'],0,-4).'/'.substr($row['diary_no'],-4); ?></td>
                        <td><?php echo $row['next_dt']!=''?date('d-m-Y', strtotime($row['next_dt'])):'-'; ?></td>
                        <td><?php if($row['mainhead'] == 'M') { echo "Misc."; } else { echo "Regular"; }
                        echo " / ";
                            if($row['board_type'] == 'J') { echo "Court"; }
                            if($row['board_type'] == 'C') { echo "Chamber"; }
                            if($row['board_type'] == 'R') { echo "Registrar"; }
                        ?></td>
                        <td><?php echo $row['stagename']; ?></td>
                        <td><?php echo $row['purpose']; ?></td>
                        <td><?php echo $row['lastorder']; ?></td>
                        <td><?php echo $row['remark']; ?></td>
                        <td> <?php  $ro_rop_details = case_verification_report_popup_inside_details($row['diary_no']);
                                  if(!empty($ro_rop_details)){
									   echo "<span class='tooltip' style='color:blue;'>VIEW<span class='tooltiptext'>";
									   foreach($ro_rop_details as $ro_rop){
												$rjm= explode("/",$ro_rop['pdfname']);
												if( $rjm[0]=='supremecourt') {
													echo '<a href="'.base_url().'/jud_ord_html_pdf/'. $ro_rop['pdfname'].'" target="_blank">'.date("d-m-Y", strtotime($ro_rop['orderdate'])).'</a><br>';
												} else {
													echo '<a href="'.base_url().'/judgment/'. $ro_rop['pdfname'].'" target="_blank">'.date("d-m-Y", strtotime($ro_rop['orderdate'])).'</a><br>';
												}
                                    }
											echo "</span></span>";
								}?>
						</td>
						<td><?php echo date('d-m-Y H:i:s', strtotime($row['ent_dt'])).'<br>BY '.$row['updatedby'];?></td>
                        <td>
                            <?php
                            if($_SESSION['login']['usertype'] == 1 OR $flag == 3 OR $flag == 5 OR $flag == 7 OR $flag == 9) {?>
                                <input type='button' name='bsubmit' id='bsubmit' value='Verify' onClick='javascript:addRecord("<?php echo $verify_str; ?>")'/>
							<?php  } ?>
                        </td>
                    </tr>
                  <?php
                    $sno++;
                }?>
	</table>
</div>	
<?php  }else {?>
            <div style="text-align: center;color: red; margin-top: 20px;font-size: 18px;">SORRY, NO RECORD FOUND!!!</div>
<?php } ?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
function addRecord(dno){
	var r = confirm("Are you Verfied this case");
    if (r == true) {
        txt = "You pressed OK!";
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
			type: "POST",
			url: "<?php echo base_url('ManagementReports/DA/DA/workdone_verify_response'); ?>",
			data: {
				CSRF_TOKEN: CSRF_TOKEN_VALUE,
        		dno:dno
			},
			cache: false,
			success: function(data){
				updateCSRFToken();
					if(data == 1){
						var r = "#"+dno;
						var row = "<tr><td colspan='11' style='text-align:center;color:red;'>Verfied Successfully</td></tr>";
						$(r).replaceWith(row);
					}
					else{
						alert("Not Verified.");
					}
		    }
		});
	} else {
		    updateCSRFToken();
			txt = "You pressed Cancel!";
    }
}
</script>
	   
   