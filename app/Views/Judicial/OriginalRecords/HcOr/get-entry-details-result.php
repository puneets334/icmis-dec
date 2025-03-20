<?php // pr($data); ?>
<div id="for_print" style="width: 100%">
    <table align="center" style="margin-top: 20px;" cellspacing="5" cellpadding="5" id="prt_tb" class="table_tr_th_w_clr c_vertical_align">

        <tr id="r1" style="display: none">
            <th colspan="7" style="font-size: 20px;">SUPREME COURT OF INDIA</th>
        </tr>
        <tr id="r2" style="display: none">
            <td colspan="7" style="text-align: center">Diary No - <strong><?php echo $data['d_no'] . '-' . $data['d_yr']; ?></strong></td>
        </tr>
        <tr id="r3" style="display: none">
            <td colspan="7"><u><?php //echo $main_row['pet_name']; 
                                ?><span style="float: right;text-decoration: underline">Petitioner</span></u></td>
        </tr>
        <tr id="r4" style="display: none">
            <td colspan="7"><u><?php //echo $main_row['res_name']; 
                                ?><span style="float: right;text-decoration: underline">Respondent</span></u></td>
        </tr>
        <tr>
            <th colspan="8" id='index_text'>INDEX</th>
        </tr>
        <tr id="r5" style="display: none">
            <th colspan="7"><span style="text-decoration: underline;font-style: italic">List of documents related to 'A1' file</span></th>
        </tr>
        <tr class="with_border">
            <th>SNo.</th>
            <th>From Court</th>
            <th>Particulars of Document</th>
            <th>From Page</th>
            <th>To Page</th>
            <th>No. of Pages</th>
            <th>Against case</th>
            <?php if (isset($data['dcmis_user_idd']) && !empty($data['row_lp123']['dacode'])) { ?>
                <?php if ($data['dcmis_user_idd'] == $data['row_lp123']['dacode'] || in_array($data['dcmis_user_idd'], $data['users_to_ignore'] ?? [])) { ?>
                    <th>Uploaded PDF</th>
                <?php } ?>
            <?php } ?>
        </tr>
        <?php
            if (!empty($data['resultLowerCourt'])) {
            $rows = json_decode($data['resultLowerCourt']);
                echo $rows;
            }
        ?>
    </table>
    <div id="dv_loading" class="cl_center"></div>
</div>


<div id="dv_sh_hd1" style="display: none;position: fixed;top: 0;width: 100%;height: 100%;background-color: black;opacity: 0.6;left: 0;overflow: hidden;z-index: 103">
    &nbsp;
</div>
<div id="dv_fixedFor_P1" style="position: fixed;top:0;display: none; left:0; width:100%; height:100%;z-index: 105">
    <div id="sp_close1" style="text-align: right;cursor: pointer;width: 40px;float: right" onclick="closeData1()"><b><img src="<?php echo base_url('images/close_btn.png'); ?>" style="width:30px;height:30px" /></b></div>
    <div style="width: auto;background-color: white;overflow: hidden;height: 550px;margin-left: 50px;margin-right: 50px;margin-bottom: 25px;margin-top: 1px;word-wrap: break-word;width: 100%" id="ggg1" onkeypress="return  nb(event)">
    <!-- <div style="width: auto;background-color: white;overflow: hidden;height: 550px;margin-left: 50px;margin-right: 50px;margin-bottom: 25px;margin-top: 1px;word-wrap: break-word;width: 100%" id="ggg1" onkeypress="return  nb(event)" onmouseup="checkStat()"> -->
        <object id="ob_shw" style="width: 100%;height: 550px" type="application/pdf"></object>
    </div>
</div>