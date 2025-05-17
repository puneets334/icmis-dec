<tr>
    <!--            <th >S.No</th>-->
    <th >Check <br/>To<br/> Add</th>
    <th >Defaults</th>
    <th >Rule</th>
</tr>

<?php 
if(!empty($sql_obj) && count($sql_obj)>0){
    $sno=1;
    foreach ($sql_obj as $row) {
        ?>

    <tr>
                <!--                     <td>
                         <span id="spSNO_<?php //echo $row['org_id'] ?>">
            <?php //echo $sno; ?>
                         </span>
                     </td>-->
                <td>
                    <input type="checkbox" name="chkCheck_<?php echo $row['org_id'] ?>" id="chkCheck_<?php echo $row['org_id'] ?>" onclick="checkRecords(this.id)"/>
                </td>
                <td style="text-align: justify;text-transform: uppercase">
                    <!--                    <option value="<?php //echo $row['org_id'] ?>"><?php //echo $row['obj_name'] ?></option>-->
                    <span id="spObj_<?php echo $row['org_id'] ?>" >
                        <?php echo $row['obj_name'] ?>
                    </span>
                </td>
                <td>
             <span id="spRule_<?php echo $row['org_id'] ?>">
                        <?php
                        if($row['ci_cri']==2)
                            echo $row['rule'] ;
                        else
                            echo "-";

                        ?>
                    </span>
                </td>
            </tr>

<?php 
    $sno++;
    } 
}
else{
?>
<tr><td colspan="4">
                <div style="text-align: center"><b>No Record found</b></div>
            </td>
</tr>

<?php } ?>