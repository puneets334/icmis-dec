<style type="text/css">
                .al_left
        {
            text-align: left;
        }
        </style>
        <style>
        #customers {
  font-family: Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

#customers td, #customers th {
  border: 1px solid #ddd;
  padding: 8px;
}

#customers tr:nth-child(even){background-color: #f2f2f2;}

#customers tr:hover {background-color: #ddd;}

#customers th {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: left;
  background-color: #04AA6D;
  color: white;}
            </style>


<?php
if(!empty($rs) && count($rs)>0){
    foreach($rs as $result){
        $cause_title=$result['cause_title'];
        $diary_date=$result['diary_date'];
        $def_notify_date=$result['defect_date'];
        $df=$result['df'];
    }
    if($def_notify_date!=null) 
    {
        $i=0;
       $c_date = date('Y-m-d');
       $def_rem_max_date=date(date("Y-m-d", strtotime($def_notify_date)) . " +".$i."days");
       $def_rem_max_date = date('Y-m-d', strtotime($def_notify_date . ' + ' . $res_no_of_days . ' days'));        

    }else{
        echo " <br><center><span style='color: red;'><b>No defects found <b></b></span></center>";
        exit();
    }

}
else{
    echo " <br><center><span style='color: red;'>No defects found </span></center>";
    exit();
}
$offset=5.5*60*60;
    $cur_date=gmdate('d-m-Y g:i a',time()+$offset);
 ?>


<div id="divprint">
<?php
    echo " <br><center><b><span style='color: red;'><u> Refiling Limitation Report </u></span></b></center>";
    echo " <br><center><span >Report Generated On"." $cur_date </span></center>";
?>
<table  id="customers" border =1 >
    <tr>
        <td>Diary No.</td>
        <td><?php echo $diary_no_display ?></td>
    </tr>
    <tr>
        <td>Cause Title</td>
        <td><?php echo @$causetitle; ?></td>
    </tr>
    <tr>
        <td>Filing Date</td>
        <td><?php echo $diary_date ?></td>
    </tr>
    <tr>
        <td>Defects Notified On :</td><td><?php echo date( 'd-m-Y',strtotime($def_notify_date))?></td>
    </tr>
    <tr>
        <td>Defects Notified On :</td><td><?php echo date( 'd-m-Y',strtotime($def_notify_date))?></td>
    </tr>
    <tr>
        <td>Refiling Date</td>           
        <td>
            <?php
            $refiling=0;
            if(!empty($ia) && count($ia)>0){
                foreach($ia as $row){
                    if($row['doccode1'] == 226){
                        $refiling = 1;
                        $refil_date = date('Y-m-d', strtotime($row['ent_dt']));
                        echo date('d-m-Y', strtotime($row['ent_dt']));
                    }
                }
            }
            
            if($refiling==0) {   
                $chk_if_defects_exists_query = "SELECT * 
                                                    FROM obj_save 
                                                    WHERE diary_no = '$diary_no' 
                                                    AND display = 'Y' 
                                                    AND rm_dt IS NULL
                                                ";
                $rs_chk_if_defects_exists = _getwhere($chk_if_defects_exists_query,'no');                                    
                if(!empty($rs_chk_if_defects_exists) && count($rs_chk_if_defects_exists)>0)
                {
                    $refil_date_get = "select max(rm_dt) as rm_dt from obj_save where diary_no='$diary_no' and display='Y' and rm_dt IS NOT NULL";
                    $refil_date = _getwhere($refil_date_get,'no');                                                        
                    if(!empty($refil_date) && count($refil_date)>0){
                        if ($refil_date == '0000-00-00 00:00:00') {
                            echo $refil_date = date('Y-m-d', strtotime($cur_date));

                        } else {
                        echo date('d-m-Y', strtotime($refil_date['rm_dt']));
                        $refil_date = date('Y-m-d', strtotime($refil_date['rm_dt']));
                        }

                    }                    
                    else
                    {
                        $refil_date= date('Y-m-d', strtotime($cur_date));
                        echo   date('d-m-Y', strtotime($cur_date));
                    }
                }
                else{
                    // process
                    $refil_date= date('Y-m-d', strtotime($cur_date));
                    echo   date('d-m-Y', strtotime($cur_date));
                }
                
            }
             ?>
        </td>
    </tr>
    <tr>
        <td>
            Last day of Refiling</td><td><?php echo date( 'd-m-Y',strtotime($nextdate)) ?>
        </td>
    </tr>
    <tr>
            <?php
                $bl=0;
                $last_day_of_refiling=date( 'Y-m-d',strtotime($nextdate)); 
                $total= get_defect_days1($df,$refil_date,$last_day_of_refiling,$diary_no);                
                
             ?>                
                <td>Delay in Refiling</td>
                <td>
                    <?php                    
                    
                     if($total<=0)
                            echo "<font color=green ><br>Refiling is within time</font>";
                        // else echo "Delay of ".$diff." days";
                        else
                        {                            
                         
                        
                                if($total < 0)
                                {
                                    echo "<font color=green ><br><b><font>Refiling is within time</b></font>";
                            
                                }
                                else
                                {
                                echo "<font color=red ><br><b><font>Total Delay of " . $total['days']. " days</b></font>";
                                }
                            
                            }
                    ?>
                </td>
        
    </tr>
</table>
 <br><br><br><br>
    <table>
        
        <div align="left">( Dealing Assistant) </div><div align="left"><?php echo  ($cur_date) ?></div><br><Br> <div align="right">(Branch Officer)</div>
 
    </table>

</div>

<div align="center">
    <input type="button" name="hd_print" id="hd_print" value="Print Refiling Limitation Report" onclick="print_data()"/>
</div>