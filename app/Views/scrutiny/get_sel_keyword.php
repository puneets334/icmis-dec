<?php 
    if($hd_max_keyword=='0')
    {
        ?>
<table id="tb_a_keyword" class="table_tr_th_w_clr" align="center" style="width: 50%">
    <tr>
        <th style="width: 10%">
            Check
        </th>
        <th>
            Description
        </th>
    </tr>
<?php
    }
    ?>
    <tr id="tr_a_keyword<?php echo $hd_max_keyword; ?>">
        <td>
            <input type="checkbox" name="chk_a_keyword<?php echo $hd_max_keyword; ?>" id="chk_a_keyword<?php echo $hd_max_keyword; ?>"
                   value="<?php echo $v_val; ?>" checked="checked" class="added_keyword"/>
        </td>
        <td>
            <?php echo $sp_k_des; ?>
        </td>
    </tr>
    <?php
     if($hd_max_keyword=='0')
    {
         ?>
    </table>
    <?php
    }
//}
?>