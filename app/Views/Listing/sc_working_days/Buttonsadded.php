<?php
include('../includes/db_inc.php');
include ('../extra/lg_out_script.php');
{
?>


    <!DOCTYPE html>
    <html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Supreme Court Calendar as on <?=date('d-m-Y h:i:sA')?></title>
        <link rel="stylesheet" href="../css/menu_css.css">
        <script src="../js/menu_js.js"></script>
        <script src="../js/jquery3.3.1.js"></script>
        <script src="../js/datatable.min.js"></script>
        <script src="../js/datatables.buttons.min.js"></script>
        <script src="../js/buttons.flash.min.js"></script>
        <script src="../js/jszip.min.js"></script>
        <script src="../js/pdfmake.min.js"></script>
        <script src="../js/vfs_fonts.js"></script>
        <script src="../js/buttons.html5.min.js"></script>
        <script src="../js/buttons.print.min.js"></script>
<style>
        table {
        border-collapse: collapse;
        margin: 30px 0px 30px;
        background-color: #fff;
        font-size: 14px;
        }

        table tr {
        height: 40px;
        }

        table th {
        color: #111;
        font-weight: bold;
        font-size: 14px;
        text-align: center;
        padding: 6px 1px 6px 5px;
        }

        table td, th {
        border: 1px solid #ccc;
        text-align: center;
        padding-top:8px;
        font-size:15px;
        width:150px
        }
        table tr:nth-of-type(odd) {
        background: #eee;
        }
</style>
</head>
      
<body>

    <?php
    include('../mn_sub_menu.php');
    ?>
    <div id="dv_content1"  >
        <form method="post" action="<?php echo $PHP_SELF; ?>">
    From_Date :
    <input type="date" name="From_date" id="myDate" value="<?=$_POST['From_date'];?>" onkeydown="return false">
    
    To_Date:
    <input type="date" name="To_date" id="myDate1" value="<?=$_POST['To_date'];?>" onkeydown="return false">
   
    
    <select name="form_calender">
        <option value="All" <?=$_POST['form_calender'] == 'All' ? 'selected':''; ?> selected>All</option>
        <option value="Court Working Day" <?=$_POST['form_calender'] == 'Court Working Day' ? 'selected':''; ?> >Court Working</option>
        <option value="Registry Working Day" <?=$_POST['form_calender'] == 'Registry Working Day' ? 'selected':''; ?> >Registry Working</option>
        <option value="Court Holiday" <?=$_POST['form_calender'] == 'Court Holiday' ? 'selected':''; ?> >Court Holiday</option>
        <option value="Registry Holiday" <?=$_POST['form_calender'] == 'Registry Holiday' ? 'selected':''; ?> >Registry Holiday</option>
    </select>
    <button type="submit" name="submit" value="submit">Get</button><br><br>
        


</form>



<?php


if(isset($_POST['submit'])) { // Fetching variables of the form which travels in URL
    $From_date      = $_POST['From_date'];
    $To_date        = $_POST['To_date'];
    $form_calender = $_POST['form_calender'];


if ($form_calender =="All") {
    //  $form_calender= "Holiday' OR 'Working Day";
    $sql = "SELECT *,CASE WHEN is_nmd='0' THEN 'Miscellaneous'WHEN is_nmd='1' THEN 'RegularDay' END as NMDFlag FROM sc_working_days 
WHERE working_date BETWEEN '$From_date' AND '$To_date' and display = 'Y'";
}
else if($form_calender =="Court Working Day")
{
    $input=" AND is_holiday=0 ";
    $input1=" AND holiday_for_registry=0 ";
}
else if($form_calender =="Registry Working Day")
{
    $input=" ";
    $input1=" AND holiday_for_registry=0 ";
}
else  if($form_calender=="Court Holiday")
{
    $input=" AND is_holiday=1 ";
    $input1=" ";
}
else if($form_calender =="Registry Holiday")
{
    $input=" AND is_holiday=1 ";
    $input1=" AND holiday_for_registry=1 ";
}


$sql = "SELECT *,CASE WHEN is_nmd='0' THEN 'Miscellaneous'WHEN is_nmd='1' THEN 'RegularDay' END as NMDFlag FROM sc_working_days 
WHERE working_date BETWEEN '$From_date' AND '$To_date'
 $input
 $input1
 and display = 'Y'";

//$result = $db1->query($sql);
$result = mysql_query($sql) or die(mysql_error());
//echo " no. of rows ".mysql_num_row($result);
$num_rows = mysql_num_rows($result);

if ($num_rows > 0) { ?>

<div>

<table id="tab">
		<thead><tr><th>SNo</th><th>Listing/Verification Dt</th><th>Is Regular Day</th><th>Sec List Dt</th>
            <!--<th>updated_by</th><th>updated_on</th>-->
            <th>Misc. Dt (Fresh)</th><th>Regular Day Dt (Fresh)</th><th>Holiday Description</th></tr></thead><tbody>

    <?php
    $sno=1;
    // output data of each row
    while ($row = mysql_fetch_array($result)) {
        //print_r($row);
     
        $temp_date='';
        $date_day='';
        if($row["working_date"]!= '0000-00-00'){
        $temp_date = strtotime($row["working_date"]);
        $temp_date = date('d-m-Y',$temp_date);
        $date_day = date("l",strtotime($row["working_date"]));
        $date_day = $temp_date.'<br> '.$date_day;
        }
        
        $miscellaneous_date='';
        
        if($row["misc_dt1"]!= '0000-00-00'){
         $miscellaneous_date = date_create($row["misc_dt1"]);
         $miscellaneous_date = date_format($miscellaneous_date,'d-m-Y');
        }
        
        $nmd='';
        if($row["nmd_dt"]!= '0000-00-00'){
        $nmd = date_create($row["nmd_dt"]);
        $nmd = date_format($nmd,'d-m-Y');
        }
        
         $sec_list_release_dt='';
        if($row["sec_list_dt"]!= '0000-00-00'){
        $sec_list_release_dt = date_create($row["sec_list_dt"]);
        $sec_list_release_dt = date_format($sec_list_release_dt,'d-m-Y');
        }
        //<td>" . $row["updated_on"] . "</td><td>" . $row["updated_by"] . "</td>
        echo "<tr><td>" .$sno++. "</td><td>" . $date_day ."</td><td>" . $row["NMDFlag"] . "</td><td>" .$sec_list_release_dt. "</td><td>" .$miscellaneous_date. "</td><td>" .$nmd. "</td><td>" . $row["holiday_description"] . "</td></tr>";
    }

    ?>
    </tbody></table></div>
    <?php
} else {
    echo "0 results";
}
}

//$conn->close();
?>


<script>
    var filename = "<?php echo 'Calender_Report as on ' . date("d-m-Y h:i:sA");?>";
    var title = "<?php echo 'Calender Report '.$_POST['form_calender'].' From '.date('d-m-Y', strtotime($_POST['From_date'])).' To '.date('d-m-Y', strtotime($_POST['From_date'])).' as on ' . date("d-m-Y h:i:s A"); ?>";

    $(document).ready(function () {
        $('#tab').DataTable({
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'excel', className: 'btn btn-primary glyphicon glyphicon-list-alt',
                    filename: filename,
                    title: title,
                    text: 'Export to Excel',
                    autoFilter: true,
                    sheetName: 'Sheet1'
                },

                {
                    extend: 'pdf', className: 'btn btn-primary glyphicon glyphicon-file',
                    filename: filename,
                    title: title,
                    pageSize: 'A4',
                    orientation: 'landscape',
                    text: 'Save as Pdf',
                    customize: function (doc) {
                        doc.styles.title = {

                            fontSize: '18',
                            alignment: 'left'
                        },
                            doc.styles.tableBodyEven.alignment = 'center';
                        doc.styles.tableBodyOdd.alignment = 'center';
                        //doc.content[1].table.widths = [40, 355, 62, 62, 62, 62, 62, 62]; //Width of Column in PDF
                    }
                },

                {
                    extend: 'print', className: 'btn btn-primary glyphicon glyphicon-print',
                    title: title,
                    pageSize: 'A4',
                    text: 'Print',
                    autoWidth: false,
                    columnDefs: [
                        {"width": "40%", "targets":1},
                    ],
                    customize: function (win) {
                        $(win.document.body).find('h1').css('font-size', '20px');
                        $(win.document.body).find('h1').css('text-align', 'left');
                        $(win.document.body).find('tab').css('width', 'auto');

                        var last = null;
                        var current = null;
                        var bod = [];

                        var css = '@page { size: landscape; }',
                            head = win.document.head || win.document.getElementsByTagName('head')[0],
                            style = win.document.createElement('style');

                        style.type = 'text/css';
                        style.media = 'print';

                        if (style.styleSheet) {
                            style.styleSheet.cssText = css;
                        }
                        else {
                            style.appendChild(win.document.createTextNode(css));
                        }

                        head.appendChild(style);

                    }

                }
            ],

            paging: false,
            ordering: false,
            info: false,
            searching: false
        });
    });
</script>


    </div>

</body>
</html>
<?php
}
?>
