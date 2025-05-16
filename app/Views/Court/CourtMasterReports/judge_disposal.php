<style>
.table-striped tr:nth-child(odd) td {
    background: #fff !important;
    box-shadow: none;
    border: 1px solid #8080805e;
    text-align: center;
}

.table-striped tr:nth-child(odd) th {
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

span {
    color: #0d48be;
    cursor: pointer;
}

h3 {
    font-family: 'noto_sanssemibold';
    color: #363636;
    font-size: 20px;
    margin-bottom: 10px;
    text-align: center;
    line-height: 1.6;
}
</style>

<div>
<?php 
if(!empty($judge_result)){ 
    $curdate=date('d-m-Y');
	$cur_time=date('h:i A');
?>
<div id="loaderDivloader"></div>
<h3><?php echo "Information regarding cases disposed off from <b>".date('d-m-Y',strtotime($fromDate))."</b> to <b>". date('d-m-Y',strtotime($toDate)) ."</b> <br>by <b>".$judgename ."</b>"." as on ".$curdate." at ".$cur_time; ?></h3>
<button type="submit"  style="float:left;background-color: #0d48be !important;margin-bottom: 12px;" id="print" name="print"  onclick="printDiv('printable')" class="btn btn-block btn-warning">Print</button>
   <div class="table-responsive">
        <table class="table table-striped custom-table" id="example1">
        <thead>
           <tr>
                <th><b>S.No.</b></th>
                <th><b>Details</b></th>
                <th><b>Count of matters</b></th>
			</tr>
        </thead>
        <tbody>
                   <tr>
                        <td>1.</td>
                        <td>Total number of matters disposed off by the bench in which lordship/ladyship was participant </td>
                        <td onclick="getDetails('1');"><span><?=$judge_result['point1']?> </span></td>
                    </tr>
                    <tr>
                        <td>2.</td>
                        <td>Out of total disposed matters (S No.-1)</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>Reportable Matters</td>
                        <td  onclick="getDetails('2a');"> <span><?=$judge_result['point2a']?> </span></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>Non-Reportable matters</td>
                        <td onclick="getDetails('2b');"><span><?=$judge_result['point2b']?></span></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>#Information not available</td>
                        <td onclick="getDetails('2c');"> <span><?=$judge_result['point2c']?> </span></td>
                    </tr>
                    <tr>
                        <td>3.</td>
                        <td>Total matters disposed off by his lordship/her ladyship as a Presiding Judge</td>
                        <td  onclick="getDetails('3');"><span> <?=$judge_result['point3']?></span></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>Reportable Matters</td>
                        <td onclick="getDetails('3a');"> <span><?=$judge_result['point3a']?></span></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>Non-reportable Matters</td>
                        <td onclick="getDetails('3b');"><span> <?=$judge_result['point3b']?></span></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>#Information not available</td>
                        <td onclick="getDetails('3c');"> <span><?=$judge_result['point3c']?></span></td>
                    </tr>
						<tr>
							<td></td>
							<td>Judgments Pronounced</td>
							<td onclick="getDetails('judgment');"><span> <?=$judge_result['judgment']?></span></td>
						</tr>
						<tr>
							<td>4.</td>
							<td>Total After Notice matters disposed off by his lordship/her ladyship as a Presiding Judge</td>
							<td onclick="getDetails('notice_disposal');"> <span><?=$judge_result['notice_disposal']?></span></td>
						</tr>
						<tr>
							<td></td>
							<td>Misc. Matters</td>
							<td onclick="getDetails('notice_disposal_misc');"> <span><?=$judge_result['notice_disposal_misc']?></span></td>
						</tr>
						<tr>
							<td></td>
							<td>Regular Matters</td>
							<td onclick="getDetails('notice_disposal_regular');"><span> <?=$judge_result['notice_disposal_regular']?></span></td>
						</tr>
            </tbody>
        </table>
        <p><b>Note:</b> <span1> # Record of Proceeding not available in the database or may be connected matters.</span1></p>   
        </div>
</div>
<?php }?>
</div>

<script>
	function getDetails(point){
		$('#loaderDivloader').html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
		var fromDate='<?=$fromDate?>';
		var toDate='<?=$toDate?>';
		var jcode='<?=$jcode?>';
		var judgename="<?=$judgename?>";
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var csrf = $("input[name='CSRF_TOKEN']").val();
		$.post("<?php echo base_url('Court/CourtMasterReports/detailed_result') ?>",{
					point_no: point,
					fromDate: fromDate,
					toDate: toDate,
					jcode:jcode,
					judgename:judgename,
                    CSRF_TOKEN: csrf,
				}
				, function (data) {
                    updateCSRFToken();
					var w = window.open('about:blank');
					w.document.open();
					w.document.write(data);
					w.document.close();
				});
	}
</script>
</body>
</html>
