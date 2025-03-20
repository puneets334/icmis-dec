<?php
$text_ch=[];
$text_checked=[];

if(!empty($checkbox_text))
{
    //                    var_dump($checkbox_text);die;
    $text_ch = explode("_",$checkbox_text);
}
if(!empty($checkbox_checked_value))
{
    //                    var_dump($checkbox_text);die;
    $text_checked = explode("_",$checkbox_checked_value);
}
$text_ch = array_filter($text_ch);
$text_checked = array_filter($text_checked);
//                echo "<pre>";
//                print_r(count($text_checked));die;


if(!empty($text_ch))
{
    //                    echo "<pre>";
    //                    print_r($connected_cases);die;
    $p_cnt=1;
    ?>
    <div class="cl_center" style="text-align: center">
        <b>Connected Cases</b></div>
    <?php
    for ($i = 0; $i < count($text_ch); $i++)
    {
        ?>

        <div>
        <div class="cl_center" style="text-align: center">


        <table align="center">
        <tbody>

        <tr>
            <td>
                <input type="checkbox" name="chk_cnt_case<?php echo $p_cnt; ?><!--" id="chk_cnt_case<?php echo $p_cnt; ?>" value="<?php if(!empty($connected_cases))echo $connected_cases[$i]; ?>"
                       class="cl_chk_cnt_case"<?php if(!empty($text_checked)) { ?> checked="checked" <?php } ?>
                <!--                        <input type="checkbox" name="chk_cnt_case--><?php //echo $p_cnt; ?><!--" id="chk_cnt_case--><?php //echo $p_cnt; ?><!--" value="--><?php // ?><!--"-->
                <!--                               class="cl_chk_cnt_case"  />-->
            </td>
            <td>
                        <span id="sp_dname<?php echo $p_cnt; ?>">
                            <?php
                            print_r($text_ch[$i]);
                            ?>
                        </span>
            </td>
        </tr>
        <?php
        $p_cnt++;
    }


    ?>

    </tbody>
    </table>
    </div>
    </div>
    <?php
}
?>
<br>
<div class="col-sm-5">
    <textarea placeholder="Enter Summary" class="btn-block summary" cols="24" rows="4" maxlength="500" style=" color:red;" name="summary" id="summary"></textarea></div>
<br>
<div style="text-align: center;background-color: white;clear: both;" id="dv_edi">
    <!--         <script src="--><?php //echo base_url('caveat/editor_tools.js'); ?><!--"></script>-->
    <!--         --><?//= view('Common/Editor/editor') ?>
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
        <input type="button" name="btnJustify" id="btnJustify" value="Center" onclick="jus_cen()"/>
        <input type="button" name="btnAliLeft" id="btnAliLeft" value="Align Left" onclick="jus_left()"/>
        <input type="button" name="btnAliRight" id="btnAliRight" value="Align Right" onclick="jus_right()"/>
        <input type="button" name="btnFull" id="btnFull" value="Justify" onclick="jus_full()"/>

        <input type="button" name="btnPrintable" id="btnPrintable" value="Print and Save" onclick="save_caveat_notice()"/>

        <select name="ddlFontFamily" id="ddlFontFamily" onchange="getFonts(this.value)">
            <option value="Times New Roman">Times New Roman</option>
            <option value="'Kruti Dev 010'">Kruti Dev</option>
        </select>
        <input type="button" name="btnIndent" id="btnIndent" value="Indent" onclick="get_intent()"/>
        <input type="button" name="btnsupScr" id="btnsupScr" value="Superscript" onclick="get_supScr()"/>

        <input type="button" name="txtRedo" id="txtRedo" onclick="gt_redo()" value="Redo"/>
        <input type="text" name="txtReplace" id="txtReplace" />
        <input type="button" name="btnReplace" id="btnReplace" onclick="fin_rep()" value="Replace All"/>

        <input type="button" name="btn_sign" id="btn_sign" value="Sign" onclick="sign()" style="display:none"/>

        <input type="button" name="btn_publish" id="btn_publish" value="Publish" onclick="publish_fun()" />

        <input type="button" name="btn_prnt" id="btn__prnt" value="Print"  onclick="draft_record1()"/>


    </div>
</div>

<input type="hidden" name="hd_next_dt" id="hd_next_dt" value="<?php if(!empty($heardt_date)) print_r($heardt_date); ?>"/>
<input type="hidden" name="dno" id="dno" value="<?php if(!empty($d_no)) print_r($d_no); ?>"/>

<div contenteditable="true" style="width: auto;margin-left: 40px;margin-right: 40px;margin-bottom: 25px;margin-top: 10px;padding-left: 10px;padding-right: 10px;word-wrap: break-word;border: 1px solid black" id="ggg" onkeypress="return  nb(event)" onmouseup="checkStat()">


        <div style="padding-left: 2px;padding-right: 2px;margin-left: 48px" width="100%">


            <p align="center" style="margin: 0px;padding: 2px 0px 0px 0px;">
                <b> <font style="font-size: 13pt" face="Times New Roman">IN THE SUPREME COURT OF INDIA</font> </b>

            </p>
            <p align="center" style="margin: 0px;padding: 2px 0px 0px 0px;">
                        <?php
                        if(!empty($casetype_detail))
                        {?>
                <b><font style="font-size: 13pt" face="Times New Roman "><?= $casetype_detail;?> </font></b>
                      <?php
                        }
                       ?>
            </p>

            <p align="center" style="margin: 0px;padding: 10px 0px 0px 0px;">

                <u><b><font style="font-size: 13pt" face="Times New Roman" >PETITION FOR <?php if(!empty($pet_line_casename))echo $pet_line_casename;else echo ''; ?> No. <?php if(!empty($pet_line_case_range))echo $pet_line_case_range;else echo '';  ?> OF <?php if(!empty($pet_line_year))echo $pet_line_year;else echo '';  ?></font></b></u>

            </p>
            <p align="center" style='margin: 0px;padding: 10px 0px 0px 0px;' >
                <u><b><font style="font-size: 13pt"  face= "Times New Roman " id="append_data"></font></b></u>
            </p>

            <?php
            $lw_cur=$judgement_dt_lw=$judgement_dt=$lw_cur_yr=$casetypeid='';
            $lw_case_code=$lw_cur_no=$lw_cur_lw=$skey=$lct_caseno=$lct_caseyear=$lct_casetype='';
            if(!empty($lower_court))
            {

                if(!empty($casetype_id))
                {
                    $casetypeid=$casetype_id;
                }
//                  echo "<Pre>";
//                  print_r(count($lower_court));die;
            for ($i = 0; $i < count($lower_court); $i++) {

                $agency_name=$lower_court[$i][2];
//               die;

                $skey=$lower_court[$i][3];
                $lct_caseno=$lower_court[$i][4];
                $lct_caseyear=$lower_court[$i][5];
                $lct_casetype=$lower_court[$i][6];
                if($casetypeid=='26' && $lct_casetype=='10')
                {
                    $judgement_dt=$new_date = date('dS F, Y', strtotime($lower_court[$i][0]));
                    $lw_cur= $lower_court[$i][3];
                }
                if($casetypeid=='26' && $lct_casetype!='10')
                {
                    $judgement_dt_lw=$new_date = date('dS F, Y', strtotime($lower_court[$i][0]));
                    $lw_cur_lw= $lower_court[$i][3];
                    $lw_cur_no=$lower_court[$i][4];
                    $lw_cur_yr=$lower_court[$i][5];
                    $lw_case_code =$lower_court[$i][5];
                }

                ?>
                <p align="center" style='margin: 0px;padding: 1px 0px 0px 0px;' >
                    <u style="font-size: 13pt" face="Times New Roman">IN</u>
                </p>
                <p align="center" style='margin: 0px;padding: 1px 0px 0px 0px;' >

                    <u style="font-size: 13pt" face="Times New Roman"><b style="font-size: 13pt" face="Times New Roman"><?php echo $skey ?> </b>
                        <b style="font-size: 13pt" face="Times New Roman"><?php echo $lct_caseno; ?></b> OF
                        <b style="font-size: 13pt" face="Times New Roman"><?php echo $lct_caseyear; ?></b></u>

                </p>
                <?php
                 }
                }
               ?>



            <p align="left" style="margin: 0px;padding: 0px 0px 0px 2px;width: 50%;float: left" ><b><font  style="font-size: 13pt" face="Times New Roman">Process Id: <?php if(!empty($process_id)) print_r($process_id); ?></font></b></p>


            <div align="center" style="width: 100%;clear: both">
                <table cellpadding="10" cellspacing="10" style="width: 100%">
                    <tbody><tr>
                        <td rowspan="2" style="vertical-align: middle; font-size: 13pt" face="Times New Roman ;text-align: center; width: 45%">
                            <?php if(!empty($pet_name)) print_r($pet_name); else echo ''; ?></td>
                        <td rowspan="2" style="vertical-align: middle; font-size: 13pt" face="Times New Roman ;text-align: center; width: 45%">
                            Versus
                        </td>
                        <td style="font-size: 13pt" face="Times New Roman">
                            ... Petitioner
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size: 13pt" face="Times New Roman">
                            <?php if(!empty($res_name)) print_r($res_name); else echo ''; ?></td>

                        <td style="font-size: 13pt" face="Times New Roman">
                            ... Respondent
                        </td>
                    </tr>
                    </tbody></table>

            </div>
            <p style="color: #000000;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px;clear: both;text-align: center" align="justify">
                <font>
                    <b style="font-size: 13pt" face="Times New Roman">OFFICE REPORT</b>
                </font>
            </p>
            <p style="color: #000000;text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px;clear: both" align="justify">
                <font style="font-size: 13pt" face="Times New Roman">
                    It is submitted that <b style="font-size: 13pt" face="Times New Roman"><?php if(!empty($pet_adv_title_name)) print_r($pet_adv_title_name); else echo ' '; ?></b> ,
                    advocate/party in-person has filed <b style="font-size: 13pt" face="Times New Roman"><?php if(!empty($get_misc_re)) print_r($get_misc_re[3]); ?></b> on
                    behalf of the appellant/petitioner/respondent, in the matter above-mentioned,
                    consequent upon the dismissal of the <b style="font-size: 13pt" face="Times New Roman"><?php echo $lw_cur; ?></b> on <b style="font-size: 13pt" face="Times New Roman"><?php echo $judgement_dt; ?></b>.


                </font>
            </p>

            <p style="text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px" align="justify">
                <font  style="font-size: 13pt" face="Times New Roman">
                    The <b style="font-size: 13pt" face="Times New Roman"><?php echo $lw_cur_lw; ?></b>
                    <b style="font-size: 13pt" face="Times New Roman"><?php echo $lw_cur_no; ?></b> of
                    <b style="font-size: 13pt" face="Times New Roman"><?php echo $lw_cur_yr; ?></b> had been
                    <b style="font-size: 13pt" face="Times New Roman"><?php
                       if(!empty($dispname))
                           print_r($dispname);
                       else echo ' ';  ?></b>
                    of by this Hon'ble Court on <b style="font-size: 13pt" face="Times New Roman"><?php echo $judgement_dt_lw; ?></b>.


                </font>
            </p>

            <p style="text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px" align="justify">
                <font  style="font-size: 13pt" face="Times New Roman">
                    In terms of Order XLVIII Rule 2 of the Supreme Court Rules, 2013, Mr.  __________________, Sr. Advocate,
                    has furnished a certificate that the curative petition meets the requirements delineated in the judgment
                    of this Hon'ble Court, dated ***10th April, 2002***, in ***Writ Petition (Civil) No. 509 of 1997
                    (Rupa Ashok Hurrah vs. Ashok Hurrah & Ors.)***, reported in ***2002 (4) SCC 388***.
                </font>
            </p>
            <p style="text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px" align="justify">
                <font  style="font-size: 13pt" face="Times New Roman">
                    The paper books of the appeal/special leave petition/review petition, having been destroyed after due
                    preservation in accordance with the procedure, the advocate for the appellant/petitioner/respondent/party
                    in-person has, therefore, furnished fresh paper books.
                </font>
            </p>

            <p style="text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px" align="justify">
                <font  style="font-size: 13pt" face="Times New Roman">
                    The curative petition along with paper books is circulated for kind consideration.
                </font>
            </p>

            <p align="right" style="padding: 16px 2px 0px 0px;margin: 0px"><b><font style="font-size: 13pt" face="Times New Roman" >ASSISTANT REGISTRAR</font></b></p>
            <p style="padding: 4px 0px 0px 2px;margin: 0px;" align="justify"><font style="font-size: 13pt" face="Times New Roman" >
                    Copy to :-
                </font></p>

            <p style="text-indent: 40px;padding: 4px 0px 0px 2px;margin: 0px;" align="justify"><font style="font-size: 13pt" face="Times New Roman" >

            <div style="margin-left: 30px">
          <font style="font-size: 13pt" face="Times New Roman">
                  <table>

                        <tbody>
                        <?php
                        $c_sno=1;
                        if(!empty($send_to_advocate_z))
                        {
                         for ($i = 0; $i < count($send_to_advocate_z); $i++) {
                             $title='';
                             $t_name='';
                             $t_address='';
                             $city='';

                             $title=$send_to_advocate_z[$i][0];
                             $t_name=$send_to_advocate_z[$i][1];
                             $t_address=$send_to_advocate_z[$i][2];
                             $city=$send_to_advocate_z[$i][3];

                        ?>
                        <tr>
                            <td style="font-size: 13pt ;vertical-align: top" face="Times New Roman"><?=  $c_sno++ ."." ;?></td>
                            <td>
                                <div style="font-size: 13pt" face="Times New Roman">
                                    <?php

                                    echo $title.' '. ucwords(strtolower($t_name)).' '.ucwords(strtolower($t_address)).' '.ucwords(strtolower($city));

                                    ?>

                                </div>

                            </td>
                        </tr>
                        <?php
                        }
                        }
                        ?>
                     </tbody>
                  </table>
          </font>
            </div>

            <p align="right" style="padding: 16px 2px 0px 0px;margin: 0px"><b><font style="font-size: 13pt" face="Times New Roman" >ASSISTANT REGISTRAR</font></b></p>

        </div>

    </div>


</div>
<input type="hidden" name="hd_or_id" id="hd_or_id" value=" <?= !(empty($res_max_o_r)) ? $res_max_o_r : '' ?> ">
<input type="hidden" name="hd_chk_status" id="hd_chk_status" value=""  />
<script src="<?php echo base_url('plugins/jquery-validation/jquery.validate.js'); ?>"></script>
<script src="<?php echo base_url('plugins/jquery-validation/additional-methods.js'); ?>"></script>
<script>
    $(document).on('click','.cl_chk_cnt_case',function(){
        var idd=$(this).attr('id');
        var sp_idd=idd.split('chk_cnt_case');

        var cnt_checked_case=0;
        $('.cl_chk_cnt_case').each(function(){
            if($(this).is(':checked'))
            {
                cnt_checked_case++;
            }
        });
        if($(this).is(':checked'))
        {

            var sp_dname=$('#sp_dname'+sp_idd[1]).html();

            $('#append_data').append('<p id="dvytr_'+sp_idd[1]+'" style="margin-top:0px;text-align:center;">'+sp_dname+'</p>');
        }
        else if ($(this).is(':not(:checked)'))
        {
            $('#dvytr_'+sp_idd[1]).remove();
        }



    });

</script>
<script>

    function updateCSRFToken() {
        $.getJSON("<?php echo base_url('Csrftoken'); ?>", function (result) {
            $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
        });
    }

    // updateCSRFToken();
    function publish_fun()
    {
        updateCSRFToken();
        // alert("RRR");
        // console.log("AAA");
        // return false;
        var dno = $('#dno').val();
        var hd_next_dt=$('#hd_next_dt').val();
        var connected_case='';
        $('.cl_chk_cnt_case').each(function(){

            if($(this).is(':checked'))
            {
                // alert($(this).val());
                if(connected_case=='')
                    connected_case=$(this).val();
                else
                    connected_case=connected_case+','+$(this).val();
            }
        });



        setTimeout(function(){
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

            $.ajax({
                type: "POST",
                data: {
                    CSRF_TOKEN: CSRF_TOKEN_VALUE,
                    connected_case:connected_case,
                    hd_next_dt: hd_next_dt,
                    dno:dno
                },
                url: "<?php echo base_url('Extension/OfficeReport/publish_office_report'); ?>",
                success: function (data) {
                    updateCSRFToken();
                    console.log(data);
                    // return false;
                    if(data==1)
                    {
                        alert("Record Publish Successfully");
                    }
                    else if(data==2)
                    {
                        alert("Please save data before Publishing record");
                    }
                    else
                    {
                        alert("Problem in publishing Record");
                    }

                },
                error: function (data) {
                    alert(data);
                    updateCSRFToken();
                }
            });
        }, 1500)


    }


    function save_caveat_notice() {



        var dno = $('#dno').val()
        var dyr = dno.substr(dno.length - 4);
        let d_no = dno.replace(dyr, "");

        var t_h_cno = d_no;
        var t_h_cyt = dyr;
        var hd_or_id=$('#hd_or_id').val();
        var sp_listed_on=$('#ddl_ord_date').val();
        var ggg=encodeURIComponent($('#ggg').html());
        var hd_next_dt=$('#hd_next_dt').val();
        var ddl_rt = $('#ddl_rt').val();
        ddl_rt = ddl_rt.replace(/^\s+/g, '')
        var connected_case='';
        var summary=$('#summary').val();
        $('.cl_chk_cnt_case').each(function(){
            if($(this).is(':checked'))
            {
                if(connected_case=='')
                    connected_case=$(this).val();
                else
                    connected_case=connected_case+','+$(this).val();
            }
        });

        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        $.ajax({
            url: "<?php echo base_url('Extension/OfficeReport/save_office_report'); ?>",
            cache: false,
            async: true,
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, d_no: t_h_cno, d_yr: t_h_cyt,hd_or_id:hd_or_id,sp_listed_on:sp_listed_on,ggg:ggg,hd_next_dt:hd_next_dt,ddl_rt:ddl_rt,connected_case:connected_case,summary:summary},

            type: 'POST',
            success: function(data, status) {
                updateCSRFToken();
                // console.log("data: ", JSON.parse(data)  )
                // return
                data = JSON.parse(data)
                if(data.msg != '1' && data.msg != 2){
                    alert(data.msg)
                }else{

                    data = data['msg']
                    $('#hd_chk_status').val(data)

                    // $('#chk_status').html(data);
                    // var hd_chk_status=$('#hd_chk_status').val();
                    var hd_chk_status = data
                    if(hd_chk_status=='1'){
                        alert('Record Save Successfully');
                    }
                    else if(hd_chk_status=='2'){
                        alert('Record Update Successfully');
                        var prtContent = document.getElementById('ggg');

                        var WinPrint = window.open('','','letf=100,top=0,width=800,height=1200,toolbar=1,scrollbars=1,status=1,menubar=1');
                        WinPrint.document.write('<link rel="stylesheet" href="../css/menu_css.css">'+'<style>'+$('#pnt_rec').html()+'</style>'+prtContent.innerHTML);
                        WinPrint.print();
                        get_report();
                    }
                }


                updateCSRFToken();
            },
            error: function(xhr) {
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }

        });
    }


</script>

