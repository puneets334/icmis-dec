<style>
    table tr:nth-of-type(2n+1) {
        background: none repeat scroll 0% 0% #EEE;
    }
    table td, table th {
        padding: 2px;
        padding-left:5px;
        border: 1px solid #CCC;
        text-align: left;
    }
</style>
<?php
 if(strpos($diary_no,',')==true)
 {
     $diarys = explode(',', $diary_no);
     foreach($diarys as $value)
     {
         $diary.=substr( $value, 0, strlen($value) -4 ).'/'.substr($value , -4 ).',';
     }
     $diary=trim($diary,',');
     
 }else
 {
    $diary=substr( $diary_no, 0, strlen($diary_no) -4 ).'/'.substr($diary_no , -4 );
 }
?>
<div style='text-wrap: normal;margin-left: auto;margin-right: auto; width:100%;'><center><b>Orders/ Judgments of Diary Number:<br><?php echo $diary?></b></center></div>

<?php

if (!empty($result_jo)){         
    ?>
    <br>
    <table style='margin-left: auto;margin-right: auto;width:50%'>
    <tr>
        <td>Date of Judgment/Order</td>
    </tr>
    <?php
    $chk_counter = 0;
    $temp_var="";
    foreach($result_jo as $row_jo) {
        $chk_counter++; $style='';
        $rjm=explode("/",$row_jo['jm']);
        if( $rjm[0]=='supremecourt') {
            $temp_var.='<a href="../jud_ord_html_pdf/'. $row_jo['jm'].'" target="_blank">'.date("d-m-Y", strtotime($row_jo['dated'])).' in D.No. '.$row_jo['d_no'].'/'.$row_jo['d_year'].'</a>&nbsp;&nbsp;['.$row_jo['jo'].']';
        } else {
            $temp_var.='<a href="../judgment/'. $row_jo['jm'].'" target="_blank">'.date("d-m-Y", strtotime($row_jo['dated'])).' in D.No. '.$row_jo['diary_no'].'/'.$row_jo['d_year'].'</a>&nbsp;&nbsp;['.$row_jo['jo'].']';
        }
        if($row_jo['main_or_connected']=='M')
            $temp_var.='-of Main Case<br>';
        else
            $temp_var.='<br>';


        ?>
        <?php

    }?>
    <tr style="height:100%;">
        <td><?php echo $temp_var; ?></td>
    </tr>
    <?php
    echo "</table>";
}

else {
    ?>
    <div class="col-xs-3">
        <div style="border-radius:3px; border:#cdcdcd solid 1px; padding:5px;text-align: center;"><?php echo 'No Record Found'; ?></div><br />
    </div>
    <?php
}?>