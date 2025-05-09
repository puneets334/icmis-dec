<?php
$dairy_no=$_REQUEST['d_no'].$_REQUEST['d_yr'];
$date=date('Y-m-d');

$db = \Config\Database::connect();
$builder = $db->table('tw_tal_del');

$builder->select('process_id, name, nt_type, diary_no');
$builder->where('diary_no', $dairy_no);
$builder->where('rec_dt', $date);
$builder->where('display', 'Y');
$builder->where('print', 0);

// Custom ordering using raw SQL for the ORDER BY clause
$builder->orderBy('CAST(sr_no AS INTEGER) = 0', 'ASC');
$builder->orderBy('CAST(sr_no AS INTEGER)', 'ASC');
$builder->orderBy('pet_res', 'ASC');
$builder->orderBy('process_id', 'ASC');

$query = $builder->get();
$results = $query->getResultArray();
$sq_rows = count($results);
?>

<table align="center" style="margin-top: 10px;" class="tbl_border">
    <tr>
        <th>Process Id</th>
        <th>
            Name
        </th>
        <th>
            Notice Type
        </th>
        <th>
         Generate Notice
        </th>
    </tr>
<?php
$rw_fnm='';
foreach($results AS $row)
// while ($row = mysql_fetch_array($sql))
    {
    
    ?>

    
    <tr>
        <td>
            <?php echo $row['process_id']; ?>
        </td>
        <td>
            <?php echo $row['name']; ?>
        </td>
       
        <td>
            <?php
             $nt_type=  explode(',', $row['nt_type']);
             $res_ct='';
     for ($index = 0; $index < count($nt_type); $index++)
     {
        //  $sql_nm=mysql_query("Select name from  tw_notice where id='$nt_type[$index]'");
        $builder = $db->table('master.tw_notice');

        $builder->select('name');
        $builder->where('id', $nt_type[$index]);

        $query = $builder->get();
        $sql_nm = $query->getResultArray();
        
        // $res_nm=mysql_result($sql_nm, 0);
        foreach ($sql_nm AS $row1) {
            if($res_ct=='')
                $res_ct=$row1['name'];
            else
                $res_ct=$res_ct.','.$row1['name'];
            }
        }
        echo $res_ct;  
     ?>
            
        </td>
         <?php
       if($row['diary_no']!=$rw_fnm)
       {
        ?>
        <td rowspan="<?php echo $sq_rows; ?>">
            <input type="button" name="btnPrint" id="btnPrint" value="Generate" onclick="dummy('<?php echo $dairy_no; ?>','<?php echo $date; ?>')"/>
        </td>
        <?php
        $rw_fnm=$row['diary_no'];
        }
        ?>
    </tr>

<?php
    }
?>
</table>
<div id="dv_sh_hd" style="display: none;position: fixed;top: 0;width: 100%;height: 100%;background-color: black;opacity: 0.6;left: 0;overflow: hidden;z-index: 103" >
       &nbsp;
</div>
<div id="dv_fixedFor_P" style="position: fixed;top:0;display: none; left:0;	width:100%;	height:100%;z-index: 105;">
         <div id="sp_close" style="text-align: right;cursor: pointer;width: 40px;float: right" onclick="closeData()" ><b><img src="../../images/close_btn.png" style="width:30px;height:30px"/></b></div>
         
<div style="text-align: center;background-color: white;clear: both;" id="dv_edi" >
<input type="button" name="btnItalic" id="btnItalic" value="I" onclick="getItalic()"/>
<input type="button" name="btnBold" id="btnBold" value="B" onclick="getBold()"/>
<input type="button" name="btnUnderline" id="btnUnderline" value="U" onclick="getUnderline()"/>
<b>Font Size</b><select name="ddlFS" id="ddlFS" onchange="getFS(this.value)">
    <?php
    for($i=1;$i<=6;$i++)
    {
        ?>
    <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
    <?php
    }
    ?>
</select>
<!--<img src="../images/download.jpg"  onclick="jus_cen()" style="width: 20px;height: 20px" />-->
<input type="button" name="btnJustify" id="btnJustify" value="Center" onclick="jus_cen()"/>
<input type="button" name="btnAliLeft" id="btnAliLeft" value="Align Left" onclick="jus_left()"/>
<input type="button" name="btnAliRight" id="btnAliRight" value="Align Right" onclick="jus_right()"/>
<input type="button" name="btnFull" id="btnFull" value="Justify" onclick="jus_full()"/>
<input type="button" name="btnPrintable" id="btnPrintable" value="Print and Save" onclick="get_set_prt()"/>
<select name="ddlFontFamily" id="ddlFontFamily" onchange="getFonts(this.value)">
    <option value="Times New Roman">Times New Roman</option>
       <option value="'Kruti Dev 010'">Kruti Dev</option>
     
</select>
<input type="button" name="btnIndent" id="btnIndent" value="Indent" onclick="get_intent()"/>
<input type="button" name="btnsupScr" id="btnsupScr" value="Superscript" onclick="get_supScr()"/>

<input type="button" name="txtRedo" id="txtRedo" onclick="gt_redo()" value="Redo"/>
<!--
<input type="button" name="btnFind" id="btnFind" onclick="fin_find()" value="Find"/>-->
<input type="text" name="txtReplace" id="txtReplace" />
<input type="button" name="btnReplace" id="btnReplace" onclick="fin_rep()" value="Replace All"/>
<!--<input type="button" name="btnRePrint" id="btnRePrint" value="RePrint&Save" onclick="get_set_re_prt()"/>-->
<input type="button" name="btn_sign" id="btn_sign" value="Sign" onclick="sign()" style="display:none"/>
<input type="button" name="btn_publish" id="btn_publish" value="Publish" onclick="publish_record()"/>
<!-- input type="button" name="btn_draft_pnt" id="btn_draft_pnt" value="Save Draft"  onclick="draft_record()"/ -->
<input type="button" name="btn_prnt" id="btn__prnt" value="Print"  onclick="draft_record1()"/>


</div>
         <div contenteditable="true"  style="width: auto;background-color: white;overflow: scroll;height: 500px;margin-left: 50px;margin-right: 50px;margin-bottom: 25px;margin-top: 1px;word-wrap: break-word;" id="ggg" onkeypress="return  nb(event)" onmouseup="checkStat()">
       </div>
        </div>