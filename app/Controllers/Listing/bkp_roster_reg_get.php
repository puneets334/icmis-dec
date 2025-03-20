<table border="1" width="100%" id="example" class="display" cellspacing="0" width="100%" style="font-family: verdana; font-size:8px;">
        
<tr style="background: #918788;">
    <td width="3%" style="text-align: center; font-weight: bold; color: #dce38d;">SrNo.</td>   
      <!--<td width="6%" style="text-align: center; font-weight: bold; color: #dce38d;">Category Code</td>-->
    <td width="49%" style="text-align: center; font-weight: bold; color: #dce38d;">Category</td>
    <td width="3%" style="text-align: center; font-weight: bold; color: #dce38d;">Ready</td>        
    <td width="3%" style="text-align: center; font-weight: bold; color: #dce38d;">Not Ready</td>
    <td width="3%" style="text-align: center; font-weight: bold; color: #dce38d;">Total</td>
        
        <?php foreach ($getRosterRegData as $roz){?>
        <?php if($roz['courtno'] == 1){ ?><td width="3%" style="text-align: center; font-weight: bold; color: #dce38d;">CJI</td>
         <?php }if($roz['courtno'] == 2){ ?><td width="3%" style="text-align: center; font-weight: bold; color: #dce38d;"><?php echo $roz['jjj']; ?></td> 
         <?php }if($roz['courtno'] == 3){ ?><td width="3%" style="text-align: center; font-weight: bold; color: #dce38d;"><?php echo $roz['jjj']; ?></td> 
         <?php }if($roz['courtno'] == 4){ ?><td width="3%" style="text-align: center; font-weight: bold; color: #dce38d;"><?php echo $roz['jjj']; ?></td> 
         <?php }if($roz['courtno'] == 5){ ?><td width="3%" style="text-align: center; font-weight: bold; color: #dce38d;"><?php echo $roz['jjj']; ?></td> 
         <?php }if($roz['courtno'] == 6){ ?><td width="3%" style="text-align: center; font-weight: bold; color: #dce38d;"><?php echo $roz['jjj']; ?></td> 
         <?php }if($roz['courtno'] == 7){ ?><td width="3%" style="text-align: center; font-weight: bold; color: #dce38d;"><?php echo $roz['jjj']; ?></td> 
         <?php }if($roz['courtno'] == 8){ ?><td width="3%" style="text-align: center; font-weight: bold; color: #dce38d;"><?php echo $roz['jjj']; ?></td> 
         <?php }if($roz['courtno'] == 9){ ?><td width="3%" style="text-align: center; font-weight: bold; color: #dce38d;"><?php echo $roz['jjj']; ?></td> 
         <?php }if($roz['courtno'] == 10){ ?><td width="3%" style="text-align: center; font-weight: bold; color: #dce38d;"><?php echo $roz['jjj']; ?></td> 
         <?php }if($roz['courtno'] == 11){ ?><td width="3%" style="text-align: center; font-weight: bold; color: #dce38d;"><?php echo $roz['jjj']; ?></td> 
         <?php }if($roz['courtno'] == 12){ ?><td width="3%" style="text-align: center; font-weight: bold; color: #dce38d;"><?php echo $roz['jjj']; ?></td> 
         <?php }if($roz['courtno'] == 13){ ?><td width="3%" style="text-align: center; font-weight: bold; color: #dce38d;"><?php echo $roz['jjj']; ?></td> 
         <?php }if($roz['courtno'] == 14){ ?><td width="3%" style="text-align: center; font-weight: bold; color: #dce38d;"><?php echo $roz['jjj']; ?></td> 
         <?php }if($roz['courtno'] == 15){ ?><td width="3%" style="text-align: center; font-weight: bold; color: #dce38d;"><?php echo $roz['jjj']; ?></td> 
         <?php } } ?>
         </tr>

         <tr>
            <td colspan="5" align="center"> Court No. => </td>
            <td width="3%" style="text-align: center; font-weight: bold; color: #dce38d;">CJI</td>        
            <td width="3%" style="text-align: center; font-weight: bold; color: #dce38d;">2</td>        
            <td width="3%" style="text-align: center; font-weight: bold; color: #dce38d;">3</td>        
            <td width="3%" style="text-align: center; font-weight: bold; color: #dce38d;">4</td>        
            <td width="3%" style="text-align: center; font-weight: bold; color: #dce38d;">5</td>        
            <td width="3%" style="text-align: center; font-weight: bold; color: #dce38d;">6</td>        
            <td width="3%" style="text-align: center; font-weight: bold; color: #dce38d;">7</td>        
            <td width="3%" style="text-align: center; font-weight: bold; color: #dce38d;">8</td>        
            <td width="3%" style="text-align: center; font-weight: bold; color: #dce38d;">9</td>        
            <td width="3%" style="text-align: center; font-weight: bold; color: #dce38d;">10</td>        
            <td width="3%" style="text-align: center; font-weight: bold; color: #dce38d;">11</td>        
            <td width="3%" style="text-align: center; font-weight: bold; color: #dce38d;">12</td>        
            <td width="3%" style="text-align: center; font-weight: bold; color: #dce38d;">13</td>        
            <td width="3%" style="text-align: center; font-weight: bold; color: #dce38d;">14</td>        
            <td width="3%" style="text-align: center; font-weight: bold; color: #dce38d;">15</td>            
        </tr>
        <?php
      $sno = 1;
      $tot_ready_m = 0;
      $tot_not_ready_m = 0;
      $tot_of_tot_m_ready_not_redy = 0;
        foreach($getrosereg as $ro){
            $sno1 = $sno % 2;
          
            if($sno1 == '1'){ ?> 
            <tr style="padding: 10px; background: #ececec;">        
            <?php } else { ?>
            <tr style="padding: 10px; background: #f6e0f3;">
            <?php        
            }
         $tot_ready_with_cn += $ro['ready_with_cn'];
         $tot_not_ready_with_cn += $ro['not_ready_with_cn'];
         $tot_of_tot_cases += $ro['tot_cases'];
                  
            ?>  
                <td align="right" style='vertical-align: top;'><?php echo $sno; ?></td> 
                 
                <td align="left" style='vertical-align: top;'><?php echo $ro['sub_cat']." (".str_replace(",",", ", $ro['sccat']).")"; ?></td>                                                                       
                <td align="right" style='vertical-align: top;'><?php $tot_ready_m += $ro['ready_m']; if($ro['ready_m']) { echo $ready_m = $ro['ready_m']; } else {echo 0;} ?></td>                                                                       
                <td align="right" style='vertical-align: top;'><?php $tot_not_ready_m += $ro['not_ready_m']; if($ro['not_ready_m']) { echo $not_ready_m = $ro['not_ready_m']; } else { echo 0;} ?></td>                                                                       
                <td align="right" style='vertical-align: top;'><?php $tot_of_tot_m_ready_not_redy += $ro['tot_m_ready_not_redy']; if($ro['tot_m_ready_not_redy']){ echo $tot_m_ready_not_redy = $ro['tot_m_ready_not_redy']; } else { echo 0; } ?></td>                                                                       
                <td align="center" style='vertical-align: top;'><?php echo $ro['cji']; ?></td>                                                                       
                <td align="center" style='vertical-align: top;'><?php echo $ro['court_2']; ?></td>                                                                       
                <td align="center" style='vertical-align: top;'><?php echo $ro['court_3']; ?></td>                                                                       
                <td align="center" style='vertical-align: top;'><?php echo $ro['court_4']; ?></td>                                                                       
                <td align="center" style='vertical-align: top;'><?php echo $ro['court_5']; ?></td>                                                                       
                <td align="center" style='vertical-align: top;'><?php echo $ro['court_6']; ?></td>                                                                       
                <td align="center" style='vertical-align: top;'><?php echo $ro['court_7']; ?></td>                                                                       
                <td align="center" style='vertical-align: top;'><?php echo $ro['court_8']; ?></td>                                                                       
                <td align="center" style='vertical-align: top;'><?php echo $ro['court_9']; ?></td>                                                                       
                <td align="center" style='vertical-align: top;'><?php echo $ro['court_10']; ?></td>                                                                       
                <td align="center" style='vertical-align: top;'><?php echo $ro['court_11']; ?></td>                                                                       
                <td align="center" style='vertical-align: top;'><?php echo $ro['court_12']; ?></td>                                                                       
                <td align="center" style='vertical-align: top;'><?php echo $ro['court_13']; ?></td>                                                                       
                <td align="center" style='vertical-align: top;'><?php echo $ro['court_14']; ?></td>                                                                       
                <td align="center" style='vertical-align: top;'><?php echo $ro['court_15']; ?></td>                                                                       
                </tr>
                <?php
        }
        ?>
                <tr><td align="right" colspan="3">Total</td>
                    <td align="right"><?php echo $tot_ready_m; ?></td>
                    <td align="right"><?php echo $tot_not_ready_m; ?>
                    <td align="right"><?php echo $tot_of_tot_m_ready_not_redy; ?>
                    <td colspan="15">Note : (1) With Connected Ready : <?php echo $tot_ready_with_cn; ?> 
                        (2) With Connected Not Ready <?php echo $tot_not_ready_with_cn; ?> 
                        (3) Total With Connected <?php echo $tot_of_tot_cases;                        
                        $sql  = mysql_query("SELECT COUNT(*) FROM  main m INNER JOIN heardt h ON h.diary_no = m.diary_no WHERE m.c_status = 'P' AND h.mainhead = 'F'") or die(mysql_error()); 
                        $ros = mysql_fetch_row($sql);
                        echo " (4) Pendency : ".$ros[0];
                        
                        ?>
                    
                    </td>
                </tr>