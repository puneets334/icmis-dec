<?php 
$cdate = $res_p_r = '';
echo " <br><center><b><span style='color: black;'><u> Supreme Court of India </u></span></b></center>";
    echo " <center><b><span style='color: black;'><u> Section I-B </u></span></b></center>"; 
    echo " <br><center><b><span style='color: green;'><u> Limitation Report  - Generated on : ".$cur_date ." </u></span><div align=right>".$cdate."</b></center><br>";
   echo $asdesc= "Diary No.-".substr( $diary_no, 0, strlen( $diary_no ) -4 ).'-'.substr( $diary_no , -4 ).'<br/>' ;
   $res_p_r.="<b><font color = blue>".$res_p_r_s['pet_name']."</b> Vs <b>". $res_p_r_s['res_name']."</b></font><br/>";
?>


   <table border="1" width="100%" align="center" id="customers">
        <tr><td>Cause Title :</td><td> <?php echo $res_p_r;  ?></td></tr>
        <tr><td>Date of Order: <b> (A)</b>:</td><td> <?php echo $order_dt;  ?></td></tr>
        <tr><td>Date of Filing: <b>(B)</b></td><td> <?php echo $filing_dt;  ?></td></tr>
        <tr><td>Total Days  : <b> (C)  = (B-A)</b></td><td> <font color="blue"><?php echo $days;  ?></font></td></tr>
        <tr><td>Copy Ready on  : <b> (D) </b></td><td> <?php echo $copy_dlvr_dt;  ?></td></tr>
         <tr><td>Copy Applied on  : <b> (E) </b></td><td> <?php echo $copy_dlvr_dt;  ?></td></tr>
         <tr><td>Total Days (D-E)  : <b> (F) </b></td><td> <font color=red><?php echo $cdays;  ?></font></td></tr>
        <tr><td>Total Holidays  : <b> (G) </b></td><td> <?php echo $leaves;  ?></td></tr>
        <tr><td>limitation days   : <b> (H) </b></td><td><font color ="red"> <?php echo $climit;  ?></font></td></tr>
   
        
        
    
       
    
<?php
//echo "days are =".$days;
    $totdays=$days - $tcdays - $climit - (int)$cdays;
    ?>
    <?php
   
    if($copy_dlvr_dt=='1970-01-01')
        $copy_dlvr_dt='';
    if($copy_aply_dt=='1970-01-01')
        $copy_aply_dt='';
    if($txt_attestation=='1970-01-01')
        $txt_attestation='';
    $d_copy_dlvr_dt='';
    $d_copy_aply_dt='';
    if($copy_dlvr_dt !='' && $copy_aply_dt!='')
        $d_copy_dlvr_dt=" (Copy ready on)".$copy_dlvr_dt."- (Copy applied on)".$copy_aply_dt;



    if($txt_attestation!='')
     {

        $f_order_dt=date_create($txt_attestation);

        $cop_doa="(Date of Attestation)".$txt_attestation."- (Date of Filing)".$filing_dt."   =".$days." days";  // not to use
     }
    else {
        $cop_doa = '(Date of Order)' . $order_dt . '   -  (Date of Filing)' . $filing_dt . '   =' . $days . ' days';
    }

    /* conditions due to covid */
  

    $day=date_diff($f_order_dt,$f_filing_dt);
    $day= $day->format('%R%a');
    // echo $day;  // total number of days between order date and filing date;

    $asdesc.=$cop_doa.$d_copy_dlvr_dt;
    $asdesc.=' (Total Holidays)= '.$leaves.' days '.$ext_days.'  Total Days ='.$days.'  -'.$cdays.'   -'.$leaves.'-'.$climit.'   = '.$totdays.' DAYS ';

    $totaldays = $days - $tcdays - $climit - $cdays;
    
    if($totaldays >0)
    { ?>
       
  <tr><td> <B>Total Days : </b><b> [ C-(F+G+H)]   </b></td><td><font color ="red"> <?php echo $totaldays;  ?></font></td></tr>
      
  <?php
    
  //echo " f order date ".$f_order_dt->format('Y-m-d');
   $d1=clone $f_order_dt;
  //echo "ist   ".$f_order_dt->format('Y-m-d');
   date_add($d1,date_interval_create_from_date_string("$climit days"));
   //echo "/n 2nd   ".$f_order_dt->format('Y-m-d');
  // echo "/n initial".$d1->format('Y-m-d'); exit(0);
   $limit_end= date_format($d1,"Y-m-d");
  //echo " sfsfs f order date ".$f_order_dt->format('Y-m-d');
 // echo "limit end is ".$limit_end;
  
   if(date_create($limit_end) >=date_create('2020-03-08') && date_create($limit_end) <=date_create('2022-02-28'))
   {
    if($f_order_dt < date_create('2020-03-08')) {
        if($f_filing_dt <= date_create('2022-07-11'))
        {
            // echo "preeti = ".$totaldays;
            $corona_start_date=date_create('2020-03-07');
            $order_days=date_diff($f_order_dt,$corona_start_date);
            $cv_days=  $order_days->format('%R%a')+1 ;
            $totaldays=($cv_days) -$climit;
           echo "<tr><td> * Pre Covid Delay days <b>(J)</b><br> ".$_REQUEST['order_dt']." to 06.03.2020</td><td>  <font color=blue>".$cv_days.  " days </font> </td></tr>";   
           echo "<tr><td> * Covid Relief <b> [DEAD PERIOD]  <b><br> (08-03-2020 to 28-02-2022)<br>** not considered while calculating limitation</td><td>  722 days </td></tr>";
          
           
            //$asdesc.="<br>Limitation period of 133 days ie from 01-03-2022 to ".$_REQUEST['filing_dt'].' Added';
        }
        else{
            $corona_start_date=date_create('2020-03-07');
            $order_days=date_diff($f_order_dt,$corona_start_date);
            $cv_days=  $order_days->format('%R%a')+1 ;
            $after_corona_date=date_create('2022-03-01');
            $after_corona_days=date_diff($after_corona_date,$f_filing_dt);
            $after_corona_days=  $after_corona_days->format('%R%a')+1 ;
            $totaldays=($cv_days+$after_corona_days)-$climit;
            echo "<tr><td> * Covid Relief <b> [DEAD PERIOD]  <b><br> (08-03-2020 to 28-02-2022)<br>** not considered while calculating limitation</td><td>  722 days </td></tr>";
            
            $asdesc.="<br> * Corona benefit of 722 days ie from 08-03-2020 to 28-02-2022 is given <br>";
        }


    }   
//    if($f_order_dt >= date_create('2020-03-08') && $f_order_dt<=date_create('2022-02-28') && ($f_filing_dt >= date_create('2022-07-11')))
    if($f_order_dt >= date_create('2020-03-08') && $f_order_dt<=date_create('2022-02-28') )     
    {   
           echo "<tr><td> * Covid Relief <b> [DEAD PERIOD]  <b><br> (08-03-2020 to 28-02-2022)<br>** not considered while calculating limitation</td><td>  722 days </td></tr>";
           $asdesc.="<br> * Corona benefit of 722 days ie from 08-03-2020 to 28-02-2022 is given <br>";
        if($f_filing_dt >= date_create('2022-03-01') && $f_filing_dt<=date_create('2022-07-11'))
        {
            echo "<tr><td>Limitation period <br> <b>(".$_REQUEST['filing_dt'] .' to  01-03-2022 ) <br> * considered while caulculating the limitation</b></td><td> 133 days</td></tr>';
            $asdesc.="<br>Limitation period <br> of 133 days ie from ".$_REQUEST['filing_dt']. ' 01-03-2022  </td><td>133 DAYS </td> <br>';
            $totaldays=0;
        }
        else {   // if limitation expired before 28 feburary then              
            $days_diff= date_diff(date_create('2022-03-01'),$f_filing_dt);
            $cv_days=  $days_diff->format('%R%a') ;
            echo "cv days = ".$cv_days;
            $totaldays=($cv_days +1) -$climit;   
            echo "c limit is ".$climit;
           echo "<tr><td> Days Calculated <b>(J)</b> :<br> <b><br> <br</td><td> ".$totaldays."  </td></tr>";         
        }
    }    
    /* code to check limitation expiry   */    
   }
}
    /* end of the code  */
    $descr = '';
    if($totaldays <= 0) {
        echo "<tr><td> <B>Total Days : </b><b> [ C-(F+G+H)]   </b></td><td><font color ='red'> ". $totaldays."</font></td></tr>";
        echo "<tr><td><b>Limitation</b> </td><td><font color=green> <b>PETITION HAS BEEN FILED WITHIN ".$climit." DAYS</b></font></td></tr>";
        echo'<blink><b style="color: green"></b><br/>';
        $descr="<U>PETITION HAS BEEN FILED WITHIN".' '.$climit.' '."DAYS".' '."PETITION IS WITHIN TIME</U>";
    } 
    else
    {      
        echo "<tr><td> <b>(J) - (H)</b></td><td><font color=red><b>  ".$totaldays. "  days </b></font></font> </td></tr>";   
        echo'<blink><b style="color: red"></b><br/>';
        echo "<tr><td> <font color= red><b>Limitation </b> </font ></td><td colspan=2><font color= red> <B>PETITION IS TIME BARRED BY ".$totaldays." DAYS</b></font></td></tr>";      
    }
   $descr=$asdesc.' - '.$descr.' - Limitation Period for this case is = '.$climit.' days ';

    /*     end of the code       */


    ?>
    
    </table>
     <br><br><br><br>
    <table>
        
        <div align="left">( Dealing Assistant) </div><div align="left"><?php echo  ($cur_date) ?></div><br><Br> <div align="right">(Branch Officer)</div>
 
    </table>
    <div style="text-align: center">Page 'A' <input type="button" name="hd_print" id="hd_print" value="Print" onclick="print_data()"/></div>

<script>
    function print_data() {

        var prtContent=$('#d4').html();
        var WinPrint = window.open('','','letf=100,top=0,width=800,height=1200,toolbar=1,scrollbars=1,status=1,menubar=1');
        WinPrint.document.write(prtContent);
        WinPrint.document.getElementById('hd_print').style.display='none';
        WinPrint.document.getElementById('dv_res_data').style.display='none';
        // WinPrint.focus();
        WinPrint.print();
    }
</script>
