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
    <div class="py-3">
    <div class="card p-3 mb-3" id="dv_edi">
        <div class="row g-2 mb-2 row-new">
            <div class="col-auto-new">
                <button class="btn btn-primary" id="btnItalic" onclick="getItalic()">I</button>
                <button class="btn btn-primary" id="btnBold" onclick="getBold()">B</button>
                <button class="btn btn-primary" id="btnUnderline" onclick="getUnderline()">U</button>
            </div>
        </div>
        <div class="row g-2 mb-2 row-new">
            <div class="col-auto-new">
                <label for="ddlFS" class="form-label mb-0"><b>Font Size</b></label>
                <select class="form-select form-select-sm" style="width: auto !important;" id="ddlFS" onchange="getFS(this.value)">
                    <?php for ($i = 1; $i <= 6; $i++) { ?>
                        <option value="<?= $i ?>"><?= $i ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
        <div class="row g-2 mb-2 row-new">
            <div class="col-auto-new">
                <button class="btn btn-primary" onclick="jus_cen()">Center</button>
                <button class="btn btn-primary" onclick="jus_left()">Align Left</button>
                <button class="btn btn-primary" onclick="jus_right()">Align Right</button>
                <button class="btn btn-primary" onclick="jus_full()">Justify</button>
            <!-- </div>
        </div>
        <div class="row g-2 mb-2 row-new">
            <div class="col-auto-new"> -->
                <button class="btn btn-success" onclick="get_set_prt()">Print and Save</button>
            </div>
            <div class="col-auto-new">
                <select class="form-select form-select-sm" style="width: auto !important;" id="ddlFontFamily" onchange="getFonts(this.value)">
                    <option value="Times New Roman">Times New Roman</option>
                    <option value="'Kruti Dev 010'">Kruti Dev</option>
                </select>
            </div>
        </div>
        <div class="row g-2 mb-2 row-new">
            <div class="col-auto-new">
                <button class="btn btn-primary" onclick="get_intent()">Indent</button>
                <button class="btn btn-primary" onclick="get_supScr()">Superscript</button>
                <button class="btn btn-primary" onclick="gt_redo()">Redo</button>
            </div>
        </div>
        <div class="row g-2 mb-2 row-new">
            <div class="col-auto" style="text-align: center;display:flex;">
                <input type="text" class="form-control form-control-sm me-1" style="width: 50% !important ;" id="txtReplace" placeholder="Replace text"/>
                <button class="btn btn-primary btn-sm" style="display: block;" onclick="fin_rep()">Replace All</button>
            </div>
        </div>
        <div class="row g-2 mb-2 row-new">
            <div class="col-auto-new">
                <button class="btn btn-warning" id="btn_sign" onclick="sign()" style="display:none">Sign</button>
                <button class="btn btn-primary" onclick="publish_record()">Publish</button>
                <button class="btn btn-secondary" onclick="draft_record1()">Print</button>
            </div>
        </div>
    </div>

    <div id="ggg"
         contenteditable="true"
         class="border p-3"
         style="background-color: white; overflow-y: auto; height: 500px; word-wrap: break-word;"
         onkeypress="return nb(event)"
         onmouseup="checkStat()">
    </div>
</div>
</div>