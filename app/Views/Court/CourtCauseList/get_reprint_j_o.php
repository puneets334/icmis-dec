<?php

$ucode = $_SESSION['login']['usercode'];
$fromDate = date('Y-m-d',  strtotime($_REQUEST['txt_o_frmdt']));
$toDate = date('Y-m-d',  strtotime($_REQUEST['txt_o_todt']));

if ($_REQUEST['order_upload'] == 'O') {
    $o_o = 'orderdate';
} else if ($_REQUEST['order_upload'] == 'U') {
    $o_o = 'date(ent_dt)';
}
$usercode = '';
if ($ucode != '1') {
    $usercode = " and usercode='$ucode'";
}

 


?>
<table width="100%" class="table table-striped custom-table table_tr_th_w_clr c_vertical_align">
    <thead><tr>
        <th>
            S.No.
        </th>
        <th>
            Diary No.
        </th>
        <th>
            Case No.
        </th>
        <th>
            Petitioner<br />Vs<br />Respondent
        </th>
        <th>
            Bench
        </th>
        <th>
            <?php if ($_REQUEST['order_upload'] == 'O') { ?>
                Uploaded Date
            <?php } else if ($_REQUEST['order_upload'] == 'U') {
            ?>
                Order Date
            <?php
            }
            ?>
        </th>
        <th>
            Type
        </th>

        <th>
            Show
        </th>

    </tr></thead>
    <?php
    
     
    if (!empty($result)) {
        $sno = 1;
        foreach ($result as $row) {
    ?>
            <tr>
                <td>
                    <?php echo $sno; ?>
                </td>
                <td>
                    <?php echo substr($row['diary_no'], 0, -4) . '-' .  substr($row['diary_no'], -4); ?>
                </td>
                <td>

                    <?php echo $row['reg_no_display'] ?>
                </td>
                <td>
                    <?php echo $row['pet_name']; ?><br />Vs<br /> <?php echo $row['res_name']; ?>
                </td>
                <td>
                    <?php
                   
                    $jud_name = '';
                    if(!empty($row['roster_id']))
                    {
                        $judge =    $CourtCausesListModel->getJudgeName($row['roster_id']);
                        
                        if(!empty($judge))
                        {                      
                            foreach ($judge as $row1) {
                                if ($jud_name == '')
                                    $jud_name = $row1['jname'];
                                else
                                    $jud_name = $jud_name . ', ' . $row1['jname'];
                            }                       
                        }
                    }
                    echo $jud_name;
                    ?>
                </td>

                <td>
                    <?php
                    if ($_REQUEST['order_upload'] == 'U')
                        echo date('d-m-Y',  strtotime($row['orderdate']));
                    else if ($_REQUEST['order_upload'] == 'O')
                        echo date('d-m-Y',  strtotime($row['ent_dt']));
                    ?>
                </td>
                <td>
                    <?php
                    if ($row['type'] == 'J')
                        $type = 'Judgement';
                    else if ($row['type'] == 'O')
                        $type = 'Order';
                    else if ($row['type'] == 'FO')
                        $type = 'Final Order';

                    ?>
                    <?php echo $type; ?>
                </td>
                <td>
                    <span id="sp_upd<?php echo $sno; ?>">
                        <input type="button" name="btn_upd<?php echo $sno; ?>" id="btn_upd<?php echo $sno; ?>"
                            value="Show" onclick="save_upload('<?php echo $row['id'] ?>')" />
                    </span>
                </td>

            </tr>
        <?php
            $sno++;
        }
    } else {
        ?>
        <tr>
            <td colspan="9">
                <div style="text-align: center">No Record Found</div>
            </td>
        </tr>
    <?php
    }
    ?>
</table>
<div id="dv_sh_hd" style="display: none;position: fixed;top: 0;width: 100%;height: 100%;background-color: black;opacity: 0.6;left: 0;overflow: hidden;z-index: 103">
    &nbsp;
</div>
<div id="dv_fixedFor_P" style="position: fixed;top:0;display: none;
	left:0;
	width:100%;
	height:100%;z-index: 105">
    <div id="sp_close" style="text-align: right;cursor: pointer;width: 40px;float: right" onclick="closeData()"><b><img src="<?php echo base_url()?>/images/close_btn.png" style="width:30px;height:30px" /></b></div>
    <?php   //include('editor.php'); 
    ?>

    <div style="text-align: center;background-color: white;clear: both;font-size: 12px;" id="dv_edi">
        <input type="button" name="btnItalic" id="btnItalic" value="I" onclick="getItalic()" />
        <input type="button" name="btnBold" id="btnBold" value="B" onclick="getBold()" />
        <input type="button" name="btnUnderline" id="btnUnderline" value="U" onclick="getUnderline()" />
        <select name="ddlFS" id="ddlFS" onchange="getFS(this.value)">
            <?php
            for ($i = 1; $i <= 50; $i++) {
            ?>
                <option value="<?php echo $i; ?>" <?php if ($i == 15) { ?> selected="selected" <?php } ?>><?php echo $i; ?></option>
            <?php
            }
            ?>
        </select>
        <!--<img src="../images/download.jpg"  onclick="jus_cen()" style="width: 20px;height: 20px" />-->
        <input type="button" name="btnJustify" id="btnJustify" value="Center" onclick="jus_cen()" />
        <input type="button" name="btnAliLeft" id="btnAliLeft" value="Align Left" onclick="jus_left()" />
        <input type="button" name="btnAliRight" id="btnAliRight" value="Align Right" onclick="jus_right()" />
        <input type="button" name="btnFull" id="btnFull" value="Justify" onclick="jus_full()" />
        <input type="button" name="btnPrintable" id="btnPrintable" value="Print and Save" onclick="get_set_prt()" />
        <select name="ddlFontFamily" id="ddlFontFamily" onchange="getFonts(this.value)">
            <option value="Times New Roman">Times New Roman</option>
            <option value="'Kruti Dev 010'">Kruti Dev</option>
            <!--         <option value="Arial">Arial</option>
      <option value="Verdana">Verdana</option>-->
        </select>
        <input type="button" name="btnIndent" id="btnIndent" value="Indent" onclick="get_intent()" />
        <input type="button" name="btnsupScr" id="btnsupScr" value="Superscript" onclick="get_supScr()" />

        <input type="button" name="txtRedo" id="txtRedo" onclick="gt_redo()" value="Redo" />
        <input type="text" name="btnFind" id="btnFind" onclick="fin_find()" />
        <input type="text" name="txtReplace" id="txtReplace" />
        <input type="button" name="btnReplace" id="btnReplace" onclick="fin_rep()" value="Replace All" />
        <input type="button" name="tb_l" id="tb_l" value="table" onclick="tb_create()" />
        <input type="text" name="tb_row" id="tb_row" size="4" />
        <input type="text" name="tb_column" id="tb_column" size="4" />
        <input type="button" name="btnunOrdList" id="btnunOrdList" value="Unordered Bullet" onclick="un_ord_bu()" />
        <input type="button" name="btnOrdList" id="btnOrdList" value="Ordered Bullet" onclick="ord_bu()" />
        <!--<input type="button" name="btn_in_lr" id="btn_in_lr" value="left/right margin" onclick="re_in_lr()"/>-->

        <select name="btn_in_lr" id="btn_in_lr" onchange="re_in_lr()">
            <option value="">Select</option>
            <?php
            for ($i = 0.1; $i <= 4; $i = $i + 0.1) {
            ?>
                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
            <?php
            }
            ?>

        </select>

        <input type="button" name="btn_re_fmt" id="btn_re_fmt" value="clear formatting" onclick="re_fmt()" />

        <input type="text" name="txt_btn_lnk" id="txt_btn_lnk" size="1" />
        <input type="button" name="btn_lnk" id="btn_lnk" value="Link" onclick="create_link()" />
        <input type="file" name="ddl_img" id="ddl_img" />
        <input type="button" name="btn_imgs" id="btn_imgs" value="Image" onclick="insert_image('0')" />
        <input type="button" name="btn_pdf" id="btn_pdf" value="pdf" onclick="insert_pdf('0')" />
        <input type="button" name="btn_template" id="btn_template" value="Template" onclick="get_template()" />
        <input type="button" name="tmp_sub" iud="tmp_sub" value="Submit" onclick="save_temp()" />
        <!--<select name="ddl_l_s" id="ddl_l_s" onchange="get_l_s(this.value)">
    <option value="1.0">1.0</option>
    <option value="1.5" selected="selected">1.5</option>
    <option value="2.0">2.0</option>
    <option value="2.5">2.5</option>
    <option value="3.0">3.0</option>
</select>-->


        <!--<input type="button" name="btnRePrint" id="btnRePrint" value="RePrint&Save" onclick="get_set_re_prt()"/>-->
    </div>

    <div contenteditable="true" style="width: 530px;background-color: white;overflow: scroll;height: 500px;margin-left: 50px;margin-right: 50px;margin-bottom: 25px;margin-top: 1px;word-wrap: break-word;clear: both;line-height: 1.25;font-family: Times New Roman;" id="ggg" onkeypress="return  nb(event)" onkeyup="return ent_dt(event)" onmouseup="checkStat()">


    </div>

</div>