<?php 

if(!empty($result)) {        
    $theadRowStyle = 'style="font-weight: bold; color: #fff; background-color: #0d48be;"';
?>
<input name="prnnt1" type="button" id="prnnt1" value="Print" class="btn btn-primary">
<div id="prnnt" style="text-align: center;">
<table align="left" width="100%" border="0px;" style="padding: 10px; font-size:13px;">
<tr>
    <td width="5%" <?= $theadRowStyle ?>>SrNo.</td>
    <td width="25%" <?= $theadRowStyle ?>>Case No. # Diary No.</td>
    <td width="40%" <?= $theadRowStyle ?>>Cause Title</td>
    <td width="10%" <?= $theadRowStyle ?>>Coram</td>
    <td width="20%" <?= $theadRowStyle ?>>Last order</td>    
</tr>
    <?php
        $sno = 1;
        foreach($result as $key => $ro) {
            $sno1 = $sno % 2;     
            if($sno1 == '1'){ ?> 
            <tr style="padding: 10px; background: #ececec;" >        
            <?php } else { ?>
            <tr style="padding: 10px; background: #f6e0f3;" >
            <?php        
            }
            ?>  
                <td align="left" style='vertical-align: top;'><?php echo $sno; ?></td>                                                                       
                <td align="left" style='vertical-align: top;'><?php echo $ro['reg_no_display'];
                    if($ro['reg_no_display'])
                        echo ' # ';
                    echo $ro['dno']."-".$ro['dyr'];  ?></td>
                <td align="left" style='vertical-align: top;'><?php echo $ro['pet_name'].' Vs'.$ro['res_name'];  ?></td>
                <td align="left" style='vertical-align: top;'><?php echo f_get_judge_names_inshort($ro['coram']);  ?></td>
                <td align="left" style='vertical-align: top;'><?php echo $ro['lastorder'];  ?></td>                                                    
                </tr>
                <?php             
            $sno++;
        }
        ?>
    </table>
     </div> 
    
    <?php
    } else {
        echo "No Recrods Found";
    }
    
    ?>
