<?php
$bench_details = $bench_details ?? [];
$bench_no = $bench_details['bench_no'] ?? '';
$bench_time = $bench_details['frm_time'] ?? '';
$bench_judge_name = stripcslashes(str_replace(",", "<br/>", $bench_details['jnm'] ?? ''));
$bench_court = $bench_details['courtno'] ?? '';
$board_type_mb = $bench_details['board_type_mb'] ?? '';

if ($bench_court == "1") {
    $print_court_no = "CHIEF JUSTICE'S COURT";
} else if ($bench_court == "61") {
    $print_court_no = "Registrar Virtual Court No. : 1";
} else if ($bench_court == "62") {
    $print_court_no = "Registrar Virtual Court No. : 2";
} else if ($bench_court == "21") {
    $print_court_no = "Registrar Court No. : 1";
} else if ($bench_court == "22") {
    $print_court_no = "Registrar Court No. : 2";
} else {
    $print_court_no = "COURT NO. : " . $bench_court;
}
?>

<div id="prnnt" style="font-size:5px;">
<table border="0" width="100%" style="font-size:12px; text-align: left; background: #ffffff;" cellspacing=0> 
    <tr><th colspan="4" style="text-align: center;"><img src="<?= base_url('images/scilogo.png') ?>" width="50px" height="80px"/></th></tr>    
    <tr><th colspan="4" style="text-align: center;">SUPREME COURT OF INDIA</th></tr>
    <tr><th colspan="4" style="text-align: center;">DAILY CAUSE LIST FOR DATED : <?= date('d-m-Y', strtotime($list_dt)) ?> </th></tr>
    <tr><th colspan="4" style="text-align: center;"><?= $print_court_no ?> </th></tr>
    <tr><th colspan="4" style="text-align: center;"><?= $bench_judge_name ?></th></tr>
    <?php if ($bench_time): ?>
    <tr><th colspan="4" style="text-align: center;">(TIME : <?= $bench_time ?>)</th></tr>
    <?php endif; ?>
    <tr><td colspan='4' style='font-size:13px;font-weight:bold; text-decoration:underline; text-align:center;'>SUPPLEMENTARY LIST</td></tr>
   
   
</table>    
<?php
$timezone = 'Asia/Kolkata';
$date = new DateTime('now', new DateTimeZone($timezone));
$formattedDate = $date->format('d-m-Y H:i:s');
?>
<p align='left' style="font-size: 12px;"><b>NEW DELHI<BR/><?= $formattedDate ?></b>&nbsp; &nbsp;</p>

<br><p align='right' style="font-size: 12px;"><b>ADDITIONAL REGISTRAR</b>&nbsp; &nbsp;</p>
</div>
<div style="width: 100%; padding-bottom:1px; background-color: #ddf1f9; text-align: center; border-top: 1px solid #000; position: fixed; bottom: 0; left: 0; right: 0; z-index: 0; display:block;">   
<span id="toggle_hw" style="color: #0066cc; font-weight: bold; cursor: pointer; padding-right: 1px;">    
</span>
<input name="prnnt1" type="button" id="prnnt1" value="Print" >
</div>

<script>
    $(document).on("click", "#prnnt1", function() {
    var prtContent = $("#prnnt").html(); // Get the HTML content to print
    var temp_str = prtContent;

    var WinPrint = window.open('', '', 'left=100,top=0,align=center,width=800,height=1200,menubar=1,toolbar=1,scrollbars=1,status=1');
    WinPrint.document.write('<html><head><title>Print</title></head><body>');
    WinPrint.document.write(temp_str); // Write content to the new window
    WinPrint.document.write('</body></html>');
    WinPrint.document.close(); // Close the document to complete loading
    WinPrint.focus(); // Focus on the new window
    WinPrint.print(); // Trigger print dialog
    // WinPrint.close(); // Optionally close the window after printing
});

</script>
