<?php


$dt1=$dt1;
$tdt1=date('d-m-Y', strtotime($dt1));
$for_date = date('Y-m-d', strtotime($dt1));



 // q1

$result_bifurcation = $model->getPending($for_date);


$incomplete = (int)$result_bifurcation['misc_incomplete'];
$not_ready = (int)$result_bifurcation['final_not_ready'];
$pendency_head1 = (int)$result_bifurcation['pending'];
$pendency_difference = (int)$result_bifurcation['pending'] - (int)$pendency_head1;
$totalNotReady=$incomplete+$not_ready;
$percentage=((int)$totalNotReady*100)/(int)$pendency_head1;

// q2
$result_constitution =$model->getTotConstitution($for_date);

// q3

$result_referred = $model->getReferred($for_date);



 
// q4

$result_connected = $model->getPendingConnected($for_date);
$pending_connected = (int)$result_connected['pending_connected'];
$pending_main_exluded_connected = ($result_connected['pending_main']);

$result_bifurcation['misc_incomlete_not_updated']='' ;// remove  



date_default_timezone_set("Asia/Kolkata");
echo "<br>";
echo "<table class='table_tr_th_w_clr c_vertical_align col-md-10' border=1><tr><td colspan='4' align='center'><h4>Bifurcation of Pending Registered matters as On  ".$tdt1."</h4></td></tr>";
echo "<tr><td colspan='4' align='right'>[generated on".date("d-m-Y h:i:s A")." ]</td></tr>";
echo "<tr><td>Number of Admission hearing matters</td><td align='right' style='font-weight: bold;'><a style='text-decoration: none;' href='pendency_bifurcation_process_detail?ason=$for_date&flag=Number_of_Admission_hearing_matters' target='_blank'>".($result_bifurcation['misc_pending']-$pendency_difference)."</a></td></tr>";


echo "<tr style='padding:10px;'><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  Complete</td><td align='right'><a style='text-decoration: none;' href='pendency_bifurcation_process_detail?ason=$for_date&flag=complete_court' target='_blank'>".($result_bifurcation['complete_court']-$pendency_difference) ."</a></td></tr>";
echo "<tr style='padding:10px;'><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  InComplete</td><td align='right'><a style='text-decoration: none;' href='pendency_bifurcation_process_detail?ason=$for_date&flag=misc_incomplete' target='_blank'>".($result_bifurcation['misc_incomplete']-$pendency_difference) ."</a></td></tr>";

echo "<tr style='padding:10px;'><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Chamber</td><td align='right'><a style='text-decoration: none;' href='pendency_bifurcation_process_detail?ason=$for_date&flag=incomplete_chamber' target='_blank'>".($result_bifurcation['incomplete_chamber']-$pendency_difference) ."</a></td></tr>";
echo "<tr style='padding:10px;'><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Registrar</td><td align='right'><a style='text-decoration: none;' href='pendency_bifurcation_process_detail?ason=$for_date&flag=incomplete_registrar' target='_blank'>".($result_bifurcation['incomplete_registrar']-$pendency_difference) ."</a></td></tr>";

echo "<tr style='padding:10px;'>
<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Not Updated</td>
<td align='right'>
<a style='text-decoration: none;' href='pendency_bifurcation_process_detail?ason=$for_date&flag=incomplete_not_updated' target='_blank'>"
. ((int)$result_bifurcation['misc_incomlete_not_updated'] - $pendency_difference)
. "</a></td></tr>";


echo "<tr><td>Number of Regular hearing matters</td><td align='right' style='font-weight: bold;'><a style='text-decoration: none;' href='pendency_bifurcation_process_detail?ason=$for_date&flag=final_pending' target='_blank'>".$result_bifurcation['final_pending']."</a></td></tr>";
echo "<tr style='padding:10px;'><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Ready</td><td align='right'><a style='text-decoration: none;' href='pendency_bifurcation_process_detail?ason=$for_date&flag=Regular_Ready' target='_blank'>".$result_bifurcation['ready'] ."</a></td></tr>";
echo "<tr style='padding:10px;'><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Not Ready</td><td align='right'><a style='text-decoration: none;' href='pendency_bifurcation_process_detail?ason=$for_date&flag=Regular_Not_Ready' target='_blank'>".$not_ready."</a></td></tr>";
echo "<tr><td>Number of Civil matters</td><td align='right'><a style='text-decoration: none;' href='pendency_bifurcation_process_detail?ason=$for_date&flag=civil_pendency' target='_blank'>".($result_bifurcation['civil_pendency']-$pendency_difference)."</a></td></tr>";
echo "<tr><td>Number of Criminal matters</td><td align='right'><a style='text-decoration: none;' href='pendency_bifurcation_process_detail?ason=$for_date&flag=criminal_pendency' target='_blank'>".$result_bifurcation['criminal_pendency']."</a></td></tr>";
echo "<tr><td>More than 1 year old matters</td><td align='right'><a style='text-decoration: none;' href='pendency_bifurcation_process_detail?ason=$for_date&flag=more_than_one_year_old' target='_blank'>".($result_bifurcation['more_than_one_year_old']-$pendency_difference)."</a></td></tr>";
echo "<tr><td>Less than 1 year old matters</td><td align='right'><a style='text-decoration: none;' href='pendency_bifurcation_process_detail?ason=$for_date&flag=less_than_one_year_old' target='_blank'>".$result_bifurcation['less_than_one_year_old']."</a></td></tr>";
echo "<tr><td>Total Pendency</td><td align='right' style='font-weight: bold;'><a style='text-decoration: none;' href='pendency_bifurcation_process_detail?ason=$for_date&flag=total_pending' target='_blank'>".($result_bifurcation['pending']-$pendency_difference)."</a></td></tr>";

echo "<tr><td>Total Connected</td><td align='right' ><a style='text-decoration: none;' href='pendency_bifurcation_process_detail?ason=$for_date&flag=Total_Connected' target='_blank'>".$pending_connected."</a></td></tr>";
echo "<tr><td>Pendency after excluding connected matters </td><td align='right' ><a style='text-decoration: none;' href='pendency_bifurcation_process_detail?ason=$for_date&flag=Pendency_after_excluding_connected' target='_blank'>".(int)$pending_main_exluded_connected."</a></td></tr>";

echo "<tr><td>More than 5 years old matters</td><td align='right'><a style='text-decoration: none;' href='pendency_bifurcation_process_detail?ason=$for_date&flag=more_than_five_year_old' target='_blank'>".($result_bifurcation['more_than_five_year_old']-$pendency_difference)."</a></td></tr>";
echo "<tr><td>More than 10 years old matters</td><td align='right'><a style='text-decoration: none;' href='pendency_bifurcation_process_detail?ason=$for_date&flag=more_than_ten_year_old' target='_blank'>".($result_bifurcation['more_than_ten_year_old']-$pendency_difference)."</a></td></tr>";
echo "<tr><td>More than 15 years old matters</td><td align='right'><a style='text-decoration: none;' href='pendency_bifurcation_process_detail?ason=$for_date&flag=more_than_fifteen_year_old' target='_blank'>".($result_bifurcation['more_than_fifteen_year_old']-$pendency_difference)."</a></td></tr>";
echo "<tr><td>More than 20 years old matters</td><td align='right'><a style='text-decoration: none;' href='pendency_bifurcation_process_detail?ason=$for_date&flag=more_than_twenty_year_old' target='_blank'>".($result_bifurcation['more_than_twenty_year_old']-$pendency_difference)."</a></td></tr>";
echo "<tr><td>Constitution matters (Subject Cat. 20,21,22,23)</td><td align='right'><a style='text-decoration: none;' href='pendency_bifurcation_process_detail?ason=$for_date&flag=tot_constitution' target='_blank'>".$result_constitution['tot_constitution']."</a></td></tr>";
echo "<tr><td>Referred matters (Reffered to Larger Bench)</td><td align='right'><a style='text-decoration: none;' href='pendency_bifurcation_process_detail?ason=$for_date&flag=referred' target='_blank'>".$result_referred['referred']."</a></td></tr>";
echo "<tr><td>Total (Incomplete + Not Ready)</td><td align='right'><a style='text-decoration: none;' href='pendency_bifurcation_process_detail?ason=$for_date&flag=Incomplete_Not_Ready' target='_blank'>".$totalNotReady."</a></td></tr>";
echo "<tr><td>Percentage of (Incomplete + Not Ready) with Total Pendency </td><td align='right'><a style='text-decoration: none;' href='pendency_bifurcation_process_detail?ason=$for_date&flag=Incomplete_Not_Ready' target='_blank'>".round($percentage,2)."</a></td></tr>";
echo "</table> ";

$first_date=date('01-m-Y', strtotime($dt1));
$last_date= date('t-m-Y', strtotime($dt1));
?>
<br/>

<table cellpadding=1 cellspacing=0 border=1 class="col-md-10">
    <tr >
        <th colspan="4"> Constitution Bench Matters Classification</th>
    </tr>
    <tr><td>&nbsp;</td><td>Total</td><td>Main</td><td>Connected</td></tr>
    <?php

    // q5



    $res_constitutionBench = $model->getConstitutionBench($for_date);
    foreach( $res_constitutionBench as $row){
        echo "<tr><td>".$row['sub_name1']."</td><td><a style='text-decoration: none;' href='pendency_bifurcation_process_detail?ason=$for_date&flag=Total_$row[subcode1]' target='_blank'>".$row['tot_constitution']."</a></td><td><a style='text-decoration: none;' href='pendency_bifurcation_process_detail?ason=$for_date&flag=Main_$row[subcode1]' target='_blank'>".$row['main_constitution']."</a></td><td><a style='text-decoration: none;' href='pendency_bifurcation_process_detail?ason=$for_date&flag=conn_$row[subcode1]' target='_blank'>".$row['connected_constitution']."</a></td></tr>";
    }
    ?>
</table>
<br>

<table cellpadding=1 cellspacing=0 border=1 class="col-md-10" >
    <tr >
        <th colspan="2"> <?php echo "Total Cases between $first_date and $last_date";?></th>
    </tr>
    <?php

    // q6


$res_notice = $model->getRHead($first_date,$last_date);
    foreach($res_notice as $row){
        echo "<tr><td>".$row['head']."</td>";
        echo "<td><a style='text-decoration: none;' href='pendency_bifurcation_process_detail?ason=$for_date&flag=Notice_$row[r_head]' target='_blank'>".$row['tot_cases']."</a></td></tr>";
    }
 // q7



    $result_inlimine = $model->getTotMatters($dt1,$first_date,$last_date);
    echo "<tr><td>In Limine Cases</td><td><a style='text-decoration: none;' href='pendency_bifurcation_process_detail?ason=$for_date&flag=In_Limine' target='_blank'>".$result_inlimine['tot_matters']."</a></td></tr>";
    echo "</table>";


    ?>
    <div align="center"><input name="cmdPrnRqs2" type="button" id="cmdPrnRqs2" onClick="CallPrint('r_box');" value="PRINT"></div>
    <p>*Accurate bifurcation figures are only possible for pendency as on date</p>

