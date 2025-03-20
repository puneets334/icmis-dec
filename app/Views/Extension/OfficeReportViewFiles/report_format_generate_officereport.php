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
             <div style="width: 30%;float: right">
                 <table>
                     <tbody><tr>
                         <td>
                             <b> <font style="font-size: 13pt" face="Times New Roman ">Listed On</font> </b>
                         </td>
                         <td>
                             <b> <font style="font-size: 13pt" face="Times New Roman "><?php if(!empty($listed_on)) echo date('d-m-Y',strtotime($listed_on)); else echo"-";  ?> </font> </b>
                         </td>
                     </tr>
                     <tr>
                         <td>
                             <b> <font style="font-size: 13pt" face="Times New Roman ">Court No.</font> </b>
                         </td>
                         <td>
                             <b> <font style="font-size: 13pt" face="Times New Roman "><?php if(!empty($court_no)) echo $court_no;  ?>  </font></b></td>
                     </tr>
                     <tr>
                         <td>
                             <b> <font style="font-size: 13pt" face="Times New Roman ">Item No.</font> </b>
                         </td>
                         <td>
                             <b> <font style="font-size: 13pt" face="Times New Roman "><?php if(!empty($item_no)) echo $item_no;  ?>  </font> </b>
                         </td>
                     </tr>
                     </tbody></table>
             </div>

             <div align="center"><u><h3>SUPREME COURT OF INDIA</h3></u></div>
             <div align="center" style="margin-top: 20px"><h3><u><?php if(!empty($description_or_dno)) echo $description_or_dno;  ?></u></h3></div>
             <p align="center" style='margin: 0px;padding: 10px 0px 0px 0px;' >
                 <u><b><font style="font-size: 13pt"  face= "Times New Roman " id="append_data"></font></b></u>
             </p>

             <div align="left" style="margin-top: 20px">
                 <b>Process Id-<?php if(!empty($process_id)) echo $process_id; ?></b>
             </div>
             <div align="center" style="margin-top: 20px">
                 <?php
                 if(!empty($pet_names) && !empty($res_names))
                 {
//                     echo "GFSDG";die;
                     print_r($pet_names);echo "<br>";
                     echo " Vs ";echo "<br>";
                     print_r($res_names);
                 }

                 ?>

             </div>
             <div align="center" style="margin-top: 20px">
                 <b>Listed on:-</b> <u><span id="sp_listed_on"><?php if(!empty($listed_on)) echo date('d-m-Y',strtotime($listed_on)); else echo"-";  ?></span></u>
                 <b> at Item No </b> <u><?php if(!empty($item_no)) echo $item_no; else echo $item_no;  ?></u>

             </div>

             <div align="center" style="margin-top: 20px"><u><b>Office Report of Fresh Cases</b></u></div>

             <div style="margin-top: 10px">
                 1. The limitation period of the appeal(s)/special leave petition(s) is as follows.

                 <table class="table_tr_th_w_clr c_vertical_align" border="1" style="border-collapse: collapse" cellpadding="5" cellspacing="5" width="100%">
                     <thead>
                     <tr>
                         <th>
                             S.No.
                         </th>
                         <th>
                             Court
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
                             Order Date
                         </th>
                         <th>

                             Petition in Time
                         </th>
                         <th>
                             Delay Days Filing
                         </th>
                         <th>
                             Delay Days Re-filing
                         </th>
                     </tr>
                     </thead>
                     <tbody>
                     <?php
                     $sno=1;
                     if(!empty($limitation_period))
                     {

                       ?>
                         <tr>
                             <td>
                                 <?php echo $sno++; ?>
                             </td>
                             <td>
                                 <?php
                                 if($limitation_period[0]['ct_code']=='1')
                                     echo "High Court";
                                 else if($limitation_period[0]['ct_code']=='2')
                                     echo "Other";
                                 else if($limitation_period[0]['ct_code']=='3')
                                     echo "District Court";
                                 else if($limitation_period[0]['ct_code']=='4')
                                     echo "Supreme Court";
                                 else if($limitation_period[0]['ct_code']=='5')
                                     echo "State Agency";
                                 ?>
                             </td>
                             <td>
                                 <?php
                                 echo $limitation_period[0]['name'];
                                 ?>

                             </td>
                             <td>
                                 <?php
                                 echo $limitation_period[0]['agency_name'];
                                 ?>
                             </td>
                             <td>
                                 <?php
                                 echo $limitation_period[0]['type_sname'].'-'.$limitation_period[0]['lct_caseno'].'-'.$limitation_period[0]['lct_caseyear'];
                                 ?>
                             </td>
                             <td>
                                 <span id="sp_lct_dec_dt<?php echo $sno; ?>"><?php if(!empty($limitation_period[0]['lct_dec_dt'])) echo date('d-m-Y',strtotime($limitation_period[0]['lct_dec_dt'])); ?></span>
                             </td>
                             <?php


                             if(!empty($limitation_period_petition['limit_days']))
                             {
//                                 echo "<pre>";
//                                 print_r($limitation_period_petition);die;
                                 if($limitation_period_petition['limit_days'] !='')
                                 {
                                     if ($limitation_period_petition['limit_days'] <= 0) { ?>
                                         <td><?php echo 'Yes'; ?> </td>
                                   <?php
                                     } elseif ($limitation_period_petition['limit_days'] > 0) { ?>
                                         <td><?php echo 'No'; ?> </td>
                                  <?php
                                     }
                                 }
                                 if($limitation_period_petition['limit_days'] !='')
                                 {
                                    if ($limitation_period_petition['limit_days'] > 0) { ?>
                                 <td><?php echo $limitation_period_petition['limit_days']; ?> </td>
                               <?php
                                    }
                                 }

                             }else{
                             ?>
                              <td><?php echo "-"; ?></td>
                              <td><?php echo "-"; ?></td>
                                <?php
                                }
                                ?>
                             <td> <?php if(!empty($delay_days)) echo $delay_days;else echo "h";?></td>

                             <?php
                             }
                             else
                             {
                             ?>

                                 <td colspan="7">
                                     <div style="text-align: center">
                                     No Information Available
                                 </div>
                                 </td>

                             <?php
                             }
                             ?>


                     </tbody></table>
             </div>
             <div style="margin-top: 10px">
                 2. The advocate has filed Document(s)/Interlocutory Application(s) as follows:-
                 <table width="100%" border="1" class="table_tr_th_w_clr c_vertical_align" cellpadding="5" cellspacing="5" style="border-collapse: collapse">
                     <thead>
                     <tr>
                         <th>
                             S.No.
                         </th>
                         <th>
                             Document No.
                         </th>
                         <th>
                             Name of Document
                         </th>
                         <th>
                             Filing date
                         </th>
                         <th>
                             Verification Status
                         </th>
                         <th>
                             Page No.
                         </th>
                     </tr>
                     </thead>
                     <tbody id="tb_docdetails">
                     <?php
                     if(!empty($doc_detail))
                     {
                         $d_sno=1;
//                         echo "<pre>";print_r($doc_detail);die;

                        foreach($doc_detail as $row)
                         {
//                             echo "<pre>";print_r($doc_detail[$i]['docnum']);die;
                     ?>
                     <tr>
                         <td>
                             <?php echo $d_sno++; ?>
                         </td>
                         <td>
                             <?php echo $row['docnum']; ?>-<?php echo $row['docyear']; ?>
                         </td>
                         <td>
                             <?php echo $row['docdesc'] ?> <?php if($row['other1']!='') { echo ' - '.$row[other1];} ?>
                         </td>
                         <td>
                             <?php echo date('d-m-Y',strtotime($row['ent_dt'])); ?>
                         </td>
                         <td>
                             <?php echo $row['verified']; ?>
                         </td>
                         <td>

                         </td>

                     </tr>
                     <?php
                         }
                     } else
                     {
                         ?>

                         <td colspan="7">
                             <div style="text-align: center">
                                 No Information Available
                             </div>
                         </td>
                   <?php
                     }
                     ?>

                     </tbody>
                 </table>
             </div>

             <div style="margin-top: 10px">

                 3. Similarity found in the present case is based on:

                 <table width="100%" border="1" class="table_tr_th_w_clr c_vertical_align" style="border-collapse: collapse">
                     <tbody><tr><th>
                             S.No.
                         </th>
                         <th>
                             Diary No.
                         </th>
                         <th>
                             Case No.
                         </th>
                         <th>
                             Petitioner/Respondent
                         </th>
                         <th>
                             Remarks
                         </th>
                         <th>
                             Status
                         </th>

                     </tr>
                     <?php
                     $sno=1;
                     if(!empty($linked_case))
                     {
//                         echo "<pre>";
//                         print_r($linked_case);die;
                         foreach($linked_case as $res_linked_case)
                         {

                     ?>

                     <tr>
                          <td>
                         <?php echo $sno++;?>
                         </td>
                         <td>
                             <?php if(!empty($res_linked_con_case_connkey)) { echo substr( $res_linked_con_case_connkey, 0, strlen( $res_linked_con_case_connkey ) -4 )  ; ?>-<?php echo substr( $res_linked_con_case_connkey , -4 ) ;} ?>
                         </td>
                         <td>
                             <?php
                             if($res_linked_case['active_fil_no']!=NULL && $res_linked_case['active_fil_no']!='')
                                 echo $res_linked_case['short_description'].'-'.intval(substr($res_linked_case['active_fil_no'],3)).'-'.$res_linked_case['active_fil_dt'];
                             else
                                 echo '-';
                             ?>
                         </td>
                         <td>
                             <?php echo $res_linked_case['pet_name']; if($res_linked_case['pno']>1) { " and others";}  ?><br/>Vs<br/><?php echo $res_linked_case['res_name']; if($res_linked_case['rno']>1) { " and others";}   ?>
                         </td>
                         <td>
                             <?php if($res_linked_case['c_status']=='P')
                                 echo "Pending";
                             else if($res_linked_case['c_status']=='D')
                                 echo "Disposed";
                             ?>
                         </td>
                         <td>

                         </td>

                     </tr>
                     <?php
                     }
                     }else
                     {
                         ?>

                         <tr>  <td colspan="7">
                             <div style="text-align: center">
                                 No Information Available
                             </div>
                         </td></tr>
                         <?php
                     }
                     ?>

                     </tbody></table>

             </div>


             <div style="margin-top: 20px">
                 4. It is submitted that, in terms of Order XV Rule 2, the status of proof of service upon the respondent(s)/caveator(s) is as follows:-
                 <table width="100%" border="1" class="table_tr_th_w_clr c_vertical_align" cellpadding="5" cellspacing="5" style="border-collapse: collapse">
                     <tbody><tr>


                         <th>
                             S.No.
                         </th>
                         <th>
                             Respondent(s)/Caveator(s)
                         </th>
                         <th>
                             Status of proof of service
                         </th>
                         <th>
                             Date of Service
                         </th>
                     </tr>
                   <?php

                   if(!empty($caveat_data))
                   {
                       $sno=1;
                       foreach($caveat_data as $row)
                       {

                    ?>
                     <tr>
                         <td><?php echo $sno++;?> </td>
                         <td><?php print_r($row['name']);?></td>
                         <?php
                         if($row['rec_dt'] !='')
                         {
                         ?>
                         <td><?php echo "Yes";?>  </td>
                         <td><?php print_r($row['rec_dt']);?> </td>
                         <?php
                         }else{ ?>
                             <td><?php echo "Awaited";?>  </td>
                             <td><?php echo "-"; ?> </td>
                        <?php
                         }
                        ?>
                           </tr>

                     <?php

                       }
                   }else
                   {
                       ?>
                       <tr>
                       <td colspan="7">
                           <div style="text-align: center">
                               No Information Available
                           </div>
                       </td></tr>
                       <?php
                   }
                   ?>

                     </tbody></table>
             </div>

             <?php
             $sno=1;
             if(!empty($claim_amt))
             {
             //                         echo "<pre>";
             //                         print_r($claim_amt);die;

             ?>
             <div style="margin-top: 20px">
                 5. Amount involved in tax matters is as follows:-
                 <table width="100%" border="1" class="table_tr_th_w_clr c_vertical_align" cellpadding="5" cellspacing="5" style="border-collapse: collapse">
                     <tbody><tr>

                         <th width="5%">
                             S.No.
                         </th>
                         <th>
                             Amount Involved
                         </th>
                     </tr>

                     <tr align="center">
                         <td width="5%"><?php echo $sno++;?></td>
                         <td><?php print_r($claim_amt); ?></td>

                     </tr>


                     </tbody></table>
             </div>
             <?php
             }
             ?>


             <div style="margin-top: 10px">
                 Note:-
             </div>
             <?php

             if(!empty($court_no))
             {
               if($court_no=='21' || $court_no=='22' || $court_no=='61' || $court_no=='62')
             {?>
             <p align="right" style="padding: 13px 2px 0px 0px;margin: 0px"><b><font style="font-size: 13pt"  face= "Times New Roman " >BRANCH OFFICER</font></b></p>
             <?php
             }
             }else {?>
             <p align="right" style="padding: 13px 2px 0px 0px;margin: 0px"><b><font style="font-size: 13pt"  face= "Times New Roman " >ASSISTANT REGISTRAR</font></b></p>
             <?php } ?>




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


        //$.ajax({
        //    type: "POST",
        //    url: "<?php //echo base_url('Extension/OfficeReport/publish_office_report'); ?>//",
        //    data: {
        //        CSRF_TOKEN: CSRF_TOKEN_VALUE,
        //        connected_case:connected_case
        //        hd_next_dt: hd_next_dt,
        //        dno:dno
        //    },
        //    success: function(data) {
        //        // updateCSRFToken();
        //        // if(data==1)
        //        // {
        //        //     alert("Record Publish Successfully");
        //        // }
        //        // else if(data==2)
        //        // {
        //        //     alert("Please save data before Publishing record");
        //        // }
        //        // else
        //        // {
        //        //     alert("Problem in publishing Record");
        //        // }
        //
        //    },
        //    error: function(xhr) {
        //        updateCSRFToken();
        //        alert("Error: " + xhr.status + " " + xhr.statusText);
        //    }
        //});

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

