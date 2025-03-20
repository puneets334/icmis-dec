<?php
    if (!empty($cause_title)) {
foreach ($cause_title as $row){
            echo "</br><font style='text-align: center;font-size: 14px;color: red'>Caveat Info: </font></br>";
            echo "<font style='text-align: center;font-size: 14px;color: black'> CAVEATOR: </font><font style='text-align: center;font-size: 14px;color: blue'> " . $row['pet_name'] . "</font></br>";
            echo "<font style='text-align: center;font-size: 14px;color: black'> CAVEATEE:</font><font style='text-align: center;font-size: 14px;color: blue'> ". $row['res_name'] . "</font></br>";
            if($row['no_of_days']>90){ ?>
                <font style='text-align: center;font-size: 14px;color: black'> STATUS:</font><span style="color:red"><?php echo "Expired";?></span> <?php
            }  else {  ?>
                <font style='text-align: center;font-size: 14px;color: black'> STATUS:</font><span style="color:green"><?php echo "Active";?></span> <?php
            }  }
        if(!empty($is_renewed)){ ?>
            <br> <span style="text-align: center;font-size: 20px;color:green"><?php echo "Already Renewed";?></span>
            <?php  } } else {
        echo "<font style='text-align: center;font-size: 14px;color: red'>Case not found</font>";
    }
if(!empty($is_renewed)){ ?>
    <hr>
        <h3> <span style="text-align: center;font-size: 25px;color:green"><?php echo "Renewed History";?></span></h3>
        <input type="hidden" name="hd_renew" id="hd_renew" value="<?php echo count($is_renewed);; ?>"/>
        <?php  if(!empty($get_new_caveat)){ ?>
            <table bgcolor="#ffe4c4" border="1" class="table table-striped custom-table showData dataTable no-footer dtr-inline">
                <thead><tr><th>Old Caveat No.</th><th>Renewed Caveat No.</th><th>Renewed On</th></tr></thead>
                <?php foreach ($get_new_caveat as $info){?>
                    <tr>
                        <td><?php echo $info['old_caveat_no']; ?></td>
                        <td><?php echo $info['new_caveat_no']; ?></td>
                        <td><?php echo $info['renew_date']; ?></td>
                    </tr>
                <?php }?>
            </table>

        <?php }  }else{ ?>
    <style> input[id=button] { background-color: #017ebc; color: white; padding: 14px 20px;margin: 8px 0; border: none; border-radius: 4px;cursor: pointer; } </style>
    <br/>
    <input type="button" id="button" value="Renew Caveat" onclick="copy_details()" name="btn" <?php if(!empty($is_renewed) && count($is_renewed) > 0){ ?> disabled <?php }?> >
   <?php } ?>



