<div style="text-align: center;background-color: white;clear: both;" id="dv_edi">
    <input type="button" name="btnItalic" id="btnItalic" value="I" onclick="getItalic()" class="btn btn-success"/>
    <input type="button" name="btnBold" id="btnBold" value="B" onclick="getBold()" class="btn btn-success" />
    <input type="button" name="btnUnderline" id="btnUnderline" value="U" onclick="getUnderline()" class="btn btn-success"/>
    <b>Font Size</b><select name="ddlFS" id="ddlFS" onchange="getFS(this.value)">
        <?php
        for ($i = 1; $i <= 6; $i++) {
            ?>
            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
            <?php
        }
        ?>
    </select>
    <input type="button" name="btnJustify" id="btnJustify" value="Center" onclick="jus_cen()" class="btn btn-success" />
    <input type="button" name="btnAliLeft" id="btnAliLeft" value="Align Left" onclick="jus_left()" class="btn btn-success" />
    <input type="button" name="btnAliRight" id="btnAliRight" value="Align Right" onclick="jus_right()" class="btn btn-success" />
    <input type="button" name="btnFull" id="btnFull" value="Justify" onclick="jus_full()" class="btn btn-success" />
    <input type="button" name="btnPrintable" id="btnPrintable" value="Print and Save" onclick="save_caveat_notice()" class="btn btn-success" />
    <select name="ddlFontFamily" id="ddlFontFamily" onchange="getFonts(this.value)">
        <option value="Times New Roman">Times New Roman</option>
        <option value="'Kruti Dev 010'">Kruti Dev</option>

    </select>
    <input type="button" name="btnIndent" id="btnIndent" value="Indent" onclick="get_intent()" class="btn btn-success" />
    <input type="button" name="btnsupScr" id="btnsupScr" value="Superscript" onclick="get_supScr()" class="btn btn-success" />

    <input type="button" name="txtRedo" id="txtRedo" onclick="gt_redo()" value="Redo" class="btn btn-success" />
    <!--
    <input type="button" name="btnFind" id="btnFind" onclick="fin_find()" value="Find"/>-->
    <input type="text" name="txtReplace" id="txtReplace"/>
    <input type="button" name="btnReplace" id="btnReplace" onclick="fin_rep()" value="Replace All" class="btn btn-success" />
    <!--<input type="button" name="btnRePrint" id="btnRePrint" value="RePrint&Save" onclick="get_set_re_prt()"/>-->
    <input type="button" name="btn_sign" id="btn_sign" value="Sign" onclick="sign()" style="display:none" class="btn btn-success" />



</div>
<?php if (!empty($caveat_data)) {
    $s_outer = 0;
    ?>
    <input type="hidden" name="hd_caveat_no" id="hd_caveat_no" value="<?php echo $param['caveat_number'].$param['caveat_year']; ?>" />
    <div contenteditable="true" style="width: auto;margin-left: 40px;margin-right: 40px;margin-bottom: 25px;margin-top: 10px;padding-left: 10px;padding-right: 10px;word-wrap: break-word;border: 1px solid black" id="noticecontent" onkeypress="return  nb(event)" onmouseup="checkStat()">
        <?php
        foreach ($caveat_data as $row1) {

            $dairy_no = $row1['caveat_diary_matching']['diary_no'];
            $c_sno = 1;
            $mul_diary = '';
            if (trim($row1['caveat_diary_matching']['notice_path']) == '') {
              if($is_main_table[0]['no_of_days']<=90){

                if ($mul_diary == '')
                    $mul_diary = $row1['caveat_diary_matching']['diary_no'];
                else
                    $mul_diary = $mul_diary . ',' . $row1['caveat_diary_matching']['diary_no'];
                ?>
                <div <?php if ($c_sno != 1) { ?> style="page-break-before: always" <?php } ?> id="<?php echo $dairy_no; ?>" class="cl_diary_no">

                    <div style="padding-left: 2px;padding-right: 2px;" width="100%">

                        <p style="text-indent: 40px;padding: 4px 0px 0px 2px;margin: 0px;" align="right">
                            <u><b>SECTION:<?php echo $row1['tentative_section']['section_name']; ?></b></u>
                        </p>
                        <p align="center" style="margin: 0px;padding: 2px 0px 0px 0px;">
                            <b>
                                <font style="font-size: 16px">SUPREME COURT OF INDIA</font>
                            </b>

                        </p>
                        <p align="center" style="margin: 0px;padding: 2px 0px 0px 0px;">

                            <font style="font-size: 14.5px"><u><b>CIVIL/CRIMINAL APPELLATE JURISDICTION</b></u></font>

                        </p>




                        <p align="center" style="margin: 0px;padding: 2px 0px 0px 0px;">

                            <u><b>
                                    <font style="font-size: 14.5px">PETITION FOR

                                        <?php if ($row1['casetype_details']['casename'] != '') {  ?> <?php echo $row1['casetype_details']['casename']; ?> No. <?php
                                            $ex_case_a = explode('-', substr($row1['sub_details']['active_fil_no'], 3));
                                            echo intval($ex_case_a[0]);
                                            if (!empty($ex_case_a[1])) {
                                                echo '-' . intval($ex_case_a[1]);
                                            } ?> OF <?php echo $row1['sub_details']['active_fil_dt'];
                                        } else {
                                            echo 'Diary No. ' . substr($dairy_no, 0, -4) . '-' .  substr($dairy_no, -4);
                                        } ?>
                                    </font>
                                </b></u>

                        </p>
                        <div align="center" style="width: 100%;clear: both">
                            <table cellpadding="5" cellspacing="5">
                                <tbody>
                                <tr>
                                    <td style="font-size: 14.5px">
                                        <?php echo $row1['sub_details']['pet_name']; ?> <?php if ($row1['sub_details']['pno'] == 2) { ?> AND ANR <?php } else if ($row1['sub_details']['pno'] > 1) { ?> AND ORS <?php } ?> </td>
                                    <td rowspan="2" style="vertical-align: middle;font-size: 14.5px">
                                        Versus
                                    </td>
                                    <td style="font-size: 14.5px">
                                        ... Petitioner
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-size: 14.5px">
                                        <?php echo $row1['sub_details']['res_name']; ?> <?php if ($row1['sub_details']['rno'] == 2) { ?> AND ANR <?php } else if ($row1['sub_details']['rno'] > 1) { ?> AND ORS <?php } ?> </td>

                                    <td style="font-size: 14.5px">
                                        ... Respondent
                                    </td>
                                </tr>
                                </tbody>
                            </table>

                        </div>
                        <p style="padding: 4px 0px 0px 2px;margin: 0px;" align="justify">
                            <font style="font-size: 14.5px">
                                TO
                            </font>
                        </p>

                        <p style="text-indent: 40px;padding: 4px 0px 0px 2px;margin: 0px;" align="justify">
                            <font style="font-size: 14.5px">

                                <?php

                                if (!empty($row1['caveat_advocate_details'])) {
                                $to_advocate = '';
                                $to_respondents = '';
                                foreach ($row1['caveat_advocate_details'] as $row2) {

                                if ($to_advocate == '')
                                    $to_advocate = $row2['title'] . ' ' . $row2['name'];
                                else
                                    $to_advocate = $to_advocate . ',' . $row2['title'] . ' ' . $row2['name'];
                                if ($to_respondents == '')
                                    $to_respondents = 'R' . '[' . $row2['pet_res_no'] . ']';
                                else
                                    $to_respondents = $to_respondents . ',' . 'R' . '[' . $row2['pet_res_no'] . ']';
                                ?>
                        <div style="margin-left: 30px;font-size: 14.5px"><?php echo $row2['aor_code'] . '-' . $row2['title'] . ' ' . $row2['name']; ?></div>
                        <div style="margin-left: 30px;margin-top: 10px;font-size: 14.5px">
                            <?php
                            if (stripos($row2['caddress'], 'Chamber') == true) {
                                echo "CHAMBER NO.: ";
                            }
                            echo strtoupper($row2['caddress'] . ' ' . $row2['ccity']); ?>
                        </div>
                        <div style="margin-left: 30px;margin-top: 10px;font-size: 14.5px">
                            Mob. No. <?php echo $row2['mobile']; ?>
                        </div>
                        <?php }
                        } ?>
                        </font>
                        </p>
                        <p style="color: #000000;text-indent: 40px;padding: 0px 2px 0px 2px;margin: 50px 0px 0px 0px;clear: both" align="justify">
                            <font style="font-size: 14.5px">
                                TAKE NOTICE THAT the petition for <?php echo $row1['casetype_details']['casename']; ?> above mentioned has been filed in the Registry.
                            </font>
                        </p>
                        <?php   if (!empty($row1['caveat_lowerct_data'])) {?>
                            <table width="100%" class="table_tr_th_w_clr c_vertical_align" style="margin-top: 10px;border-collapse: collapse;border: 1px solid lightgrey;" border="1">
                                <tbody>
                                <tr>
                                    <th>
                                        S.No.
                                    </th>
                                    <th>
                                        Diary Receiving Date
                                    </th>

                                    <th>
                                        From Court
                                    </th>
                                    <th>
                                        State
                                    </th>
                                    <th>
                                        Bench
                                    </th>
                                    <th>
                                        Case No.
                                    </th>
                                    <th>
                                        Judgement Date
                                    </th>
                                    <th>
                                        Advocate
                                    </th>
                                    <th>
                                        Linked Date
                                    </th>
                                </tr>
                                <?php
                                $s_no = 1;


                                foreach ($row1['caveat_lowerct_data'] as $row) {
                                    ?>

                                    <tr>
                                        <td>
                                            <?php echo $s_no; ?> </td>

                                        <td>
                                            <?php if (!empty($row1['main_data']['diary_no_rec_date'])) echo $row1['main_data']['diary_no_rec_date']; ?> </td>
                                        <!--        <td>
</td>-->
                                        <td>
                                            <span id="sp_court_name<?php echo $s_no; ?>"><?php echo $row['court_name']; ?></span>
                                        </td>
                                        <td>
                                                <span id="sp_Name<?php echo $s_no; ?>"><?php
                                                    echo $row['name'];
                                                    ?> </span>
                                        </td>
                                        <td>
                                                <span id="sp_agency_name<?php echo $s_no; ?>"><?php
                                                    echo $row['agency_name'];
                                                    ?></span>
                                        </td>
                                        <td>
                                                <span id="sp_case_name<?php echo $s_no; ?>"><?php
                                                    echo $row['type_sname'] . '-' . $row['lct_caseno'] . '-' . $row['lct_caseyear'];
                                                    ?></span>
                                        </td>
                                        <td>
                                            <?php echo date('d-m-Y', strtotime($row['lct_dec_dt'])); ?> </td>
                                        <td>
                                            <?php
                                            $adv_name = '';

                                            if (!empty($row1['diary_advocate_details'])) {

                                                foreach ($row1['diary_advocate_details'] as $row2) {
                                                    if ($adv_name == '') {
                                                        $adv_name = $row2['aor_code'] . '-' . $row2['name'];
                                                    } else {
                                                        $adv_name = $adv_name . ', ' . $row2['aor_code'] . '-' . $row2['name'];
                                                    }
                                                    $adv_ar[$s_outer][0] = $row2['title'] . '~' . $row2['aor_code'] . '~' . $row2['name'] . '~' . $row2['caddress'] . '~' . $row2['ccity'] . '~' . $row2['mobile'];
                                                    $s_outer++;
                                                }
                                                echo $adv_name;
                                            }
                                            ?> </td>
                                        <td>


                                            <?php echo $row1['linked_date_caveat']['linked_date']?>         </td>

                                    </tr>
                                    <?php
                                } ?>
                                </tbody>
                            </table>
                        <?php  }?>
                        <?php   if (!empty($row1['caveator_caveatee_data'])) {?>
                            <table width="100%" class="table_tr_th_w_clr c_vertical_align" style="border-collapse: collapse;border: 1px solid lightgrey;" border="1">
                                <tbody><tr>
                                    <th>
                                        S.No.
                                    </th>

                                    <th>
                                        Diary Receiving Date
                                    </th>
                                    <th>
                                        Petitioner<br>Vs<br>Respondent
                                    </th>

                                    <th>
                                        State
                                    </th>

                                    <th>
                                        Advocate
                                    </th>
                                    <th>
                                        Linked Date
                                    </th>
                                </tr>
                                <?php
                                $s_no = 1;


                                foreach ($row1['caveator_caveatee_data'] as $row) {
                                    ?>
                                    <tr>
                                        <td>
                                            <?php echo $s_no; ?>                                                   </td>
                                        <td>
                                            <?php if (!empty($row1['main_data']['diary_no_rec_date'])) echo $row1['main_data']['diary_no_rec_date']; ?>                                                    </td>


                                        <td>

                                            <?php echo $row['pet_name'] . '<br/>Vs<br/>' . $row['res_name'];        ?>                                               </td>

                                        <td>

                                            <?php
                                            echo $row['name'];
                                            ?>                                                                 </td>
                                        <td>
                                            <?php
                                            $adv_name = '';

                                            if (!empty($row1['diary_advocate_details'])) {

                                                foreach ($row1['diary_advocate_details'] as $row2) {
                                                    if ($adv_name == '') {
                                                        $adv_name = $row2['aor_code'] . '-' . $row2['name'];
                                                    } else {
                                                        $adv_name = $adv_name . ', ' . $row2['aor_code'] . '-' . $row2['name'];
                                                    }
                                                    $adv_ar[$s_outer][0] = $row2['title'] . '~' . $row2['aor_code'] . '~' . $row2['name'] . '~' . $row2['caddress'] . '~' . $row2['ccity'] . '~' . $row2['mobile'];
                                                    $s_outer++;
                                                }
                                                echo $adv_name;
                                            }
                                            ?>                                                      </td>
                                        <td>



                                            <?php echo $row1['linked_date_caveat']['linked_date']?>                                                     <table>
                                                <tbody><tr><td>
                                                    </td>

                                                </tr>
                                                </tbody></table>

                                        </td>
                                    </tr>
                                <?php }?>
                                </tbody></table>
                        <?php }?>
                        <font style="font-size: 14.5px">

                        </font>
                        <p></p>
                        <div style="color: #000000;text-indent: 40px;padding: 0px 2px 0px 2px;margin: 50px 0px 0px 0px;clear: both;width: 100%" align="justify">
                            <font style="font-size: 14.5px">
                                Dated this the <b><?php echo date('F d, Y'); ?></b>
                            </font>
                        </div>
                        <div align="right" style="padding:50px 2px 0px 0px;">
                            <span> <strong>
                                    <font style="font-size: 14.5px">BRANCH OFFICER</font>
                                </strong></span>
                        </div>


                        <p style="padding: 4px 0px 0px 2px;margin: 0px;" align="justify">
                            <font style="font-size: 14.5px">
                                Copy TO :-
                            </font>
                        </p>

                        <p style="text-indent: 40px;padding: 4px 0px 0px 2px;margin: 0px;" align="justify">
                            <font style="font-size: 14.5px">
                                <?php

                                for ($index = 0; $index < $s_outer; $index++) {
                                ?>

                                <?php
                                for ($index1 = 0; $index1 < 6; $index1++) {
                                if (!empty($adv_ar[$index][$index1])) {
                                $ex_adv_ar =  explode('~', $adv_ar[$index][$index1]);
                                if ($ex_adv_ar[2] != '') {
                                ?>
                        <div style="margin-top: 10px;font-size: 14.5px">
                            <?php
                            if ($ex_adv_ar[1] == '799') {

                                $r_party =  $row1['party_details_diary'];
                                echo $r_party['partyname'];
                            } else
                                echo $ex_adv_ar[1] . '-' . $ex_adv_ar[0] . ' ' . $ex_adv_ar[2];

                            ?>
                        </div>
                    <?php
                    }
                    if ($ex_adv_ar[1] == '799') {
                        $state = '';
                        $district = '';

                        /* $state = get_state_data($r_party['state'])[0]['name'];
$district = get_state_data($r_party['city'])[0]['name'];
*/
                        ?>
                        <div style="margin-top: 10px;font-size: 14.5px">
                            <?php

                            if ($r_party['address'])
                                echo $r_party['address'] . ' ' . $district . ' ' . $state;
                            ?>
                        </div>
                        <?php

                    } else {
                        if ($ex_adv_ar[3] != '') {
                            ?>
                            <div style="margin-top: 10px;font-size: 14.5px">



                                <?php

                                if (stripos($ex_adv_ar[3], 'Chamber') == true) {
                                    echo "CHAMBER NO.: ";
                                }


                                echo $ex_adv_ar[3] . ' ' . $ex_adv_ar[4]; ?>
                            </div>
                            <?php
                        }
                    }
                    if ($ex_adv_ar[1] == '799') {
                        ?>
                        <div style="margin-top: 10px;font-size: 14.5px">
                            Mob. No. <?php echo $r_party['contact']; ?>
                        </div>
                        <?php
                    } else {
                        if ($ex_adv_ar[5] != '') {
                            ?>
                            <div style="margin-top: 10px;font-size: 14.5px">
                                Mob. No. <?php echo $ex_adv_ar[5] ?>
                            </div>
                            <?php
                        }
                    }
                    }
                    }
                    ?>

                    <?php
                    }

                    ?>
                        </font>
                        <p></p>

                        <p style="padding: 4px 0px 0px 2px;margin: 0px;" align="justify">
                            <font style="font-size: 14.5px">
                                You are requested to serve copies of the necessary documents on
                                <b><?php
                                    echo $to_advocate
                                    ?></b> Advocate who has entered caveat on behalf of the Respondent(s) No <?php echo $to_respondents;  ?> in this matter and file proof of service of the same as per provisions of Supreme Court Rules, 2013 in the Registry within 28 days.
                            </font>
                        </p>


                        <p align="right" style="padding: 16px 2px 0px 0px;margin: 0px"><b>
                                <font style="font-size: 14.5px">BRANCH OFFICER(EXT. CELL I-B)</font>
                            </b></p>
                        <!--<p style="padding-left: 15px;"><b><font style="font-size: 14.5px"> NOTE</font><font size="3"> : The is directed to observe the strict compliance of this order.</font></b></p>-->
                    </div>
                </div>


                <!--<hr id="pba" style="padding: 0px;margin: 0px;"/>-->

                <input type="hidden" name="hd_diary_no" id="hd_diary_no" value="422212022">
            <?php }else{?>
                    <div style="text-align: center; color:red;">Caveat Expired</div>
                <?php }}else{
                $d_year = substr($dairy_no, -4);
                $d_no = substr($dairy_no, 0, -4);
                $fil_nm =  base_url("../caveat_records/" .$param['caveat_year'] . "/" . $param['caveat_number'] . "/" . $d_no . '_' . $d_year . '_' . $row1['caveat_diary_matching']['print_dt']. ".html") ;
                echo $file = file_get_contents($fil_nm, true);

                /* $ds = fopen($fil_nm, 'r');
                 $b_z = fread($ds, filesize($fil_nm));
                 fclose($ds);
                 echo utf8_encode($b_z);*/
            }
        } ?>
    </div>
<?php
}
else {

    ?>
    <div style="text-align: center; color:red;">No Record Found</div>
    <?php
}
?>