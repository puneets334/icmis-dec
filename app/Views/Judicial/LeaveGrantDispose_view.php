<?= view('header') ?>
 

 <!-- Main content -->
 <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header heading">

                            <div class="row">
                                <div class="col-sm-10">
                                    <h3 class="card-title">Judicial > DISPOSAL OF LEAVE GRANTED CASES</h3>
                                </div>
                                <div class="col-sm-2">
                                    <div class="custom_action_menu">                                        
                                        <a href="<?= base_url() ?>/Judicial/LeaveGrantDispose"><button class="btn btn-primary btn-sm" type="button"><i class="fas fa-pen	" aria-hidden="true"></i></button></a>                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        <? //view('Filing/filing_breadcrumb'); ?>
                        <!-- /.card-header -->

                        <style>
                            table {
                                font-family: arial, sans-serif;
                                border-collapse: collapse;
                                width: 100%;
                            }

                            td, th {
                                border: 1px solid #dddddd;
                                text-align: left;
                                padding: 8px;
                            }
                        </style>
                       
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="tab-content">

                                            <?php
                                                // $attribute = array('class' => 'form-horizontal','name' => 'desposeview', 'id' => 'desposeview', 'autocomplete' => 'off');
                                                // echo form_open(base_url(''), $attribute);
                                            ?>
                                            <div class="active tab-pane" id="">

                                            <div id="dv_res1">


                                                <input type="hidden" name="diaryno<?php print $opt;?>" id="diaryno<?php print $opt;?>" value="<?php echo $diaryno;?>"/>
                                                <div class="container_cs<?php print $opt;?>">
                                                    <?php
                                                        if(isset($casedesc['notfound'])){
                                                            echo $casedesc['notfound'];
                                                        }else{
                                                            echo $casedesc['get_bunch_cases'];
                                                        }
                                                     ?>
                                                </div>

                                                <div id="newb" style="display:none;">
                                                    <table width="100%" border="0" style="border-collapse: collapse">
                                                        <tr style="background-color: #A9A9A9;">
                                                            <td align="center">
                                                                <b><font color="black" style="font-size:14px;">Case Status</font></b>
                                                            </td>
                                                            <td>
                                                            <input style="float:right;" type="button" name="close_b" id="close_b" value="CLOSE WINDOW" onclick="close_w();"/>
                                                            </td>
                                                            
                                                        </tr>
                                                    </table>
                                                    <div id="newb123" style="overflow:auto; background-color: #FFF;"> </div>
                                                    <div id="newb1" align="center">
                                                        <table border="0" width="100%">
                                                            <tr>
                                                                <td align="center" width="250px">
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </div> 

                                            </div>
                                               
                                                    
                                            </div>

                                            

                                            <?php //form_close();?>
                                        </div>
                                        <!-- /.tab-content -->
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                                <!-- /.card -->
                            </div>
                        </div>


                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <div id="overlay" style="display: none; height: 1891px;">&nbsp;</div>
            <!-- /.container-fluid -->
    </section>
    <!-- /.content -->


<script>
       
    function updateCSRFToken() {
        $.getJSON("<?php echo base_url('Csrftoken'); ?>", function (result) {
            $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
        });
    }




    function openPDF(urlToPdfFile) {
        window.open(urlToPdfFile, 'pdf');
    }

    function getSlide(){
        var cnt_data = parseInt(document.getElementById('djcnt').value);
        var cnt_data1 = cnt_data + 1;
        var mf_select = document.getElementById('djudge').value;
        var mf_select1 = mf_select.split("||")[1];
        for (var i = 1; i <= cnt_data; i++)
        {
            if (document.getElementById('hd_chk_jd' + i))
            {
                if (document.getElementById('hd_chk_jd' + i).value == mf_select)
                {
                    alert("Already Selected");
                    return false;
                }
            }
        }
        var hd_chk_add = document.createElement('input');
        hd_chk_add.setAttribute('type', 'checkbox');
        hd_chk_add.setAttribute('id', 'hd_chk_jd' + cnt_data1);
        hd_chk_add.setAttribute('onclick', 'getDone_upd_cat(this.id);');
        hd_chk_add.setAttribute('value', mf_select);
        var row0 = document.createElement("tr");
        row0.setAttribute('id', 'hd_chk_jd_row' + cnt_data1);
        var column0 = document.createElement("td");
        column0.appendChild(hd_chk_add);
        column0.innerHTML = column0.innerHTML + '&nbsp;<font color=red><b>' + mf_select1 + '</b></font>';
        row0.appendChild(column0);
        var tb_res = document.getElementById('tb_new');
        tb_res.appendChild(row0);
        document.getElementById('hd_chk_jd' + cnt_data1).checked = true;
        document.getElementById('djcnt').value = cnt_data1;
    }

    function feed_rmrk(){
        var ccstr = "";
        var regex = "";
        var nstr = false;
        var obrdrem = document.getElementById("brdremh").value;
        document.getElementById("brdrem").value = '';
        ccstr = obrdrem;
        $("input[type='checkbox'][name^='iachbx']").each(function () {
            var isChecked = document.getElementById($(this).attr('id')).checked;
            if (isChecked)
            {
                var tval = $(this).val().split("|#|");
                regex = '\\b';
                regex += escapeRegExp(tval[0]);
                regex += '\\b';
                nstr = new RegExp(regex, "i").test(ccstr);
                if (!(nstr)) {
                    if (ccstr != '')
                        ccstr += " \nFOR " + tval[1] + " ON IA " + tval[0];
                    else
                        ccstr += " FOR " + tval[1] + "  ON IA " + tval[0];
                }
            }
        });
        //alert(ccstr);
        document.getElementById("brdrem").value = ccstr;
    }

    function escapeRegExp(string) {
        return string.replace(/([.*+?^=!:${}()|\[\]\/\\])/g, "\\$1");
    }

    function feed_rmrk_conn(fn){
        var ccstr = "";
        var t_ccstr = "";
        var obrdrem = document.getElementById("brdremh_" + fn).value;
        document.getElementById("brdrem_" + fn).value = '';
        ccstr = obrdrem;
        $("input[type='checkbox'][name^='cn_ia_" + fn + "']").each(function () {
            var isChecked = document.getElementById($(this).attr('id')).checked;
            if (isChecked){
                var tval = $(this).val().split("|#|");
                t_ccstr= "FOR " + tval[2] + " ON IA " + tval[1];
                if (ccstr != ''){
                    var n = ccstr.search(t_ccstr);
                    if(n<0)
                    ccstr += " \n" + t_ccstr;
                }
                else{
                    ccstr += " " + t_ccstr;
                }
            }
        });
        //alert(ccstr);
        document.getElementById("brdrem_" + fn).value = ccstr;
    }

    function save_rec_prop(){
        var err_msg='';
        var jrc = document.getElementById('jrc').value;
        var lo = document.getElementById('listorder').value;
        var thdt = document.getElementById("thdate").value;
        var mf = document.getElementById('mf_select').value;
        var sh = document.getElementById('subhead_select').value;      
        if(jrc=='')
            err_msg="Select Judge/Registrar/Chamber\n";
        if(lo=='')
            err_msg+="Select Purpose of Listing\n";
        if(thdt=='')
            err_msg+="Enter Proposed Listing Date\n";
        if(mf=='')
            err_msg+="Select Hearing Head\n";
        if(sh=='')
            err_msg+="Select Sub Heading\n";
        if(err_msg!=''){
            alert(err_msg);
            return false;
        }else{
            if (lo == "32" || lo == "48"){
                if (lo == "32"){
                    alert("Enter Purpose of Listing other than FRESH");
                }
                if (lo == "48"){
                    alert("Enter Purpose of Listing other than NOT REACHED");
                }
                return false;
            }
            var sj = document.getElementById('sj').value;
            var supp_flag =0;
            var qte_array = new Array();
            var url = "insert_rec_prop.php";
            var diaryno = document.getElementById("diaryno").value;
            var thdt1 = thdt.split("-");
            var thdt_new = thdt1[2] + "-" + thdt1[1] + "-" + thdt1[0];
            var subhead_select = '';
            var br = document.getElementById('brdrem').value;
            var ucode = document.getElementById('ucode').value;
            var dacode = document.getElementById('da_hidden').value;
            var ccstr = "";
            var tcntr = 0;
            $("input[type='checkbox'][name^='ccchk']").each(function () {
                var isChecked = document.getElementById($(this).attr('id')).checked;
                qte_array[tcntr] = new Array(3);
                    qte_array[tcntr][0] = $(this).val();
                    qte_array[tcntr][1] = '';
                    qte_array[tcntr][2] = '';
                    qte_array[tcntr][3] = 'N';
                if (isChecked)
                {
                    qte_array[tcntr][3] = 'Y';
                    $("input[type='checkbox'][name^='cn_ia_" + qte_array[tcntr][0] + "']").each(function () {
                        var isChecked1 = document.getElementById($(this).attr('id')).checked;
                        if (isChecked1)
                        {
                            var tval = $(this).val().split("|#|");
                            qte_array[tcntr][1] += tval[1] + ", ";
                        }
                    });
                    var obrdrem = document.getElementById("brdrem_" + qte_array[tcntr][0]).value;
                    qte_array[tcntr][2] = obrdrem;

                    ccstr += $(this).val() + ",";
                    
                } 
                tcntr++;
            });

            var ccstr1 = "";
            $("input[type='checkbox'][name^='iachbx']").each(function () {
                var isChecked = document.getElementById($(this).attr('id')).checked;
                if (isChecked)
                {
                    var tval = $(this).val().split("|#|");
                    ccstr1 += tval[0] + ",";
                }
            });

            $.ajax({
                type: "POST",
                url: url,
                data: {diaryno: diaryno,
                    thdt_new: thdt_new,
                    mf: mf,
                    sh: sh,
                    supp_flag: supp_flag,
                    lo: lo,
                    br: br,
                    jrc:jrc,
                    ccstr: ccstr,
                    ucode: ucode,
                    dacode:dacode,
                    ias: ccstr1,
                    sj: sj,
                    connlist: qte_array,
                    tcntr:tcntr
                },
                success: function (msg) {
                    
                    if (msg == '')
                    {
                        //  fsubmit();
                    }
                    else
                    {
                        alert(msg);
                        // fsubmit();
                    }
                        $("input[name=btnGetR]").click();
                },
                error: function () {
                    alert("ERROR");
                }
            });
            close_w(3);
            
        }
    }

    function save_rec(){

        var stat = "";
        var cr_head = "";
        var div1 = "chkd";
        stat = "D";
        cr_head = '<b><font color="red">';
        var chk_val;
        var cval = "";
        var str_new = "";
        var str_caseval = "";
        var isfalse = 0;
        var jcodes = "";
        var jcnt = 0;
        var chk_var = false;
        var concstr='';
        $("input[type='checkbox'][name^='chkbtn']").each(function () {
            if($(this).is(':checked')){
            concstr+= $(this).val()+'|@|';  
            }
        });
        if (concstr == ""){
            alert("Select atleast one Case");
            return false;
        }
        $("input[type='checkbox'][id^='hd_chk_jd']").each(function() {
            if (document.getElementById($(this).attr('id')).checked) {
                jcodes += $(this).val().split("||")[0] + ",";
                jcnt++;
            }
        });

        if (jcodes == "")
        {
            alert("Select Judge");
            return false;
        }

        $("input[type='checkbox'][name^='" + div1 + "']").each(function() {
            var isChecked = document.getElementById($(this).attr('id')).checked;
            if (isChecked)
            {
                chk_var = true;
                chk_val = $(this).val().split("|");
                cval = $("#" + div1 + chk_val[0]).val().split("|");
                str_new += cval[0] + "!";
                cr_head += cval[1];
                cr_head += '<br>';
            }
        });
        cr_head += '</font></b>';
        if (document.getElementById("cldate").value=="")
        {
            alert("Select CauseList Date!");
            return false;
        }
        if (document.getElementById("hdate").value=="")
        {
            alert("Select Hearing Date!");
            return false;
        }    
        if (!(chk_var))
        {
            alert("Select atleast one disposal type from the list.");
            return false;
        }

        if (isfalse == 0){
            var url = "leave_grant_disp.php";
            var http = new getXMLHttpRequestObject();
            var str1 = "";
            var dt = document.getElementById("cldate").value;
            var hdt = document.getElementById("hdate").value;

        
            var dt1 = dt.split("-");
            var dt_new = dt1[2] + "-" + dt1[1] + "-" + dt1[0];
            var hdt1 = hdt.split("-");
            var hdt_new = hdt1[2] + "-" + hdt1[1] + "-" + hdt1[0];

            str1 = jcodes;
            var cr_head_display=$(cr_head).text()
            if(confirm("Are you sure you want to  \n"+cr_head_display)) {
                $("#disp").prop('disabled',true);
        
                $.ajax({
                    type: 'POST',
                    url:url,
                    data:{cr:str_new,str1:str1,dt:dt_new,hdt:hdt_new,concstr:concstr}
                })
                .done(function(msg){
                    $('#output').html(msg);
                    //alert(msg);
                })
                .fail(function(){
                    alert("ERROR, Please Contact Server Room"); 
                });     
            
        }


        }
    }

    function chg_def1(){
        var lo = document.getElementById("listorder").value;
        var tdt = $('#thdate').val();
        if ($("#mf_select").val() == "L" || $("#mf_select").val() == "S"){
            ed = 0;
            document.getElementById("listorder").value = 16;
        }
    }

    function get_subheading(){
        var jj = 0;
        var sh = $('#sh').val();
        jj = $('#mf_select').val();
            var lo = document.getElementById("listorder").value;

        if (jj == "M" || jj == "L" || jj == "S"){
            $('#subhead_select').prop('disabled', false);
            
            $.ajax({
                type: 'POST',
                url:"get_mf_subhead.php",
                data:{mf:jj,sh:sh}
            })
            .done(function(msg){
                msg = "<option value=''>SELECT</option>" + msg;
                document.getElementById('subhead_select').innerHTML = msg;
            })
            .fail(function(){
                alert("ERROR, Please Contact Server Room"); 
            });         
            
            
        }else{
            $('#subhead_select').prop('disabled', true);
        }
        var tdt = $('#thdate').val();
        if (jj == "L" || jj == "S") {
            $('#thdate').prop('disabled', false);
            $('#listorder').val(16);
        }else{
            d11 = (document.getElementById('thdate_nm').value).split("-");
            d22 = (document.getElementById('thdate_h').value).split("-");
            d1 = new Date(d11[2], (d11[1] - 1), d11[0]);
            d2 = new Date(d22[2], (d22[1] - 1), d22[0]);
            if ((lo == "16" || lo == "2") && $("#mf_select").val() == "M" && d1.getTime() > d2.getTime())
                document.getElementById('thdate').value = document.getElementById('thdate_nm').value;
            else
                document.getElementById('thdate').value = document.getElementById('thdate_h').value;
        }
    }

    function call_f2(fno){
        $.ajax({
            type: 'POST',
            url:"./get_office_report_html.php",
            data:{fno:fno}
        })
        .done(function(msg){
            $("#newc123").html(msg);
        })
        .fail(function(){
            alert("ERROR, Please Contact Server Room"); 
        });
            
        var divname = "";
        divname = "newc";
        $('#' + divname).width($(window).width() - 150);
        $('#' + divname).height($(window).height() - 120);
        $('#newc123').height($('#newc').height() - $('#newc1').height() - 50);

        var newX = ($('#' + divname).width() / 2);
        var newY = ($('#' + divname).height() / 2);
        document.getElementById(divname).style.marginLeft = "-" + newX + "px";
        document.getElementById(divname).style.marginTop = "-" + newY + "px";
        document.getElementById(divname).style.display = 'block';
        document.getElementById(divname).style.zIndex = 10;
        $('#overlay').height($(window).height());
        document.getElementById('overlay').style.display = 'block';
    }

    function close_w2(){
        var divname = "";
            divname = "newc";
        document.getElementById(divname).style.display = 'none';
        document.getElementById('overlay').style.display = 'none';
    }

    function call_f1(d_no,d_yr,ct,cn,cy){
        var divname = "";
            divname = "newb";
            document.getElementById(divname).style.display = 'block';
            $('#' + divname).width($(window).width() - 150);
            $('#' + divname).height($(window).height() - 120);
            $('#newb123').height($('#newb').height() - $('#newb1').height() - 50);
        var newX = ($('#' + divname).width() / 2);
        var newY = ($('#' + divname).height() / 2);
        document.getElementById(divname).style.marginLeft = "-" + newX + "px";
        document.getElementById(divname).style.marginTop = "-" + newY + "px";
        document.getElementById(divname).style.display = 'block';
        document.getElementById(divname).style.zIndex = 10;
        $('#overlay').height($(window).height());
        document.getElementById('overlay').style.display = 'block';
            $.ajax({
                type: 'POST',
                url:"case_status_process.php",
                beforeSend: function (xhr) {
                    $("#newb123").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='../images/load.gif'></div>");
                },
                data:{d_no:d_no,d_yr:d_yr,ct:ct,cn:cn,cy:cy,tab:'Case Details',opt:2}
            })
            .done(function(msg){
                $("#newb123").html(msg);
            })
            .fail(function(){
                alert("ERROR, Please Contact Server Room"); 
            });
    }

    function close_w(){
        var divname = "";
        divname = "newb";
        document.getElementById(divname).style.display = 'none';
        document.getElementById('overlay').style.display = 'none';
    }

$(document).ready(function(){
    $(document).on('click','[id^=accordion] a',function (event) {
        var accname=$(this).attr('data-parent');
        if(typeof $(this).attr('data-parent') !== "undefined"){
            var url="";
            var href = this.hash;
            var depId = href.replace("#collapse",""); 
            var accname1=accname.replace("#accordion",""); 
            var acccnt=accname1*100;
            var diaryno = document.getElementById('diaryno'+accname1).value;
            if(depId!=(acccnt+1)){
                if(depId==(acccnt+2)) url="get_earlier_court.php";
                if(depId==(acccnt+3)) url="get_connected.php";
                if(depId==(acccnt+4)) url="get_listings.php";
                if(depId==(acccnt+5)) url="get_ia.php";
                if(depId==(acccnt+6)) url="get_court_fees.php";
                if(depId==(acccnt+7)) url="get_notices.php";
                if(depId==(acccnt+8)) url="get_default.php";
                if(depId==(acccnt+9)) url="get_judgement_order.php";
                if(depId==(acccnt+10)) url="get_adjustment.php";
                if(depId==(acccnt+11)) url="get_mention_memo.php";
                if(depId==(acccnt+12)) url="get_restore.php";
                if(depId==(acccnt+13)) url="get_drop.php";
                if(depId==(acccnt+14)) url="get_appearance.php";
                if(depId==(acccnt+15)) url="get_office_report.php";
                if(depId==(acccnt+16)) url="get_similarities.php";
                if(depId==(acccnt+17)) url="get_caveat.php";

                    $.ajax({
                        type: 'POST',
                        url:url,
                        beforeSend: function (xhr) {
                            $("#result"+depId).html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='../images/load.gif'></div>");
                        },
                        data:{diaryno:diaryno}
                    })
                    .done(function(msg){
                        $("#result"+depId).html(msg);
                    })
                    .fail(function(){
                        alert("ERROR, Please Contact Server Room"); 
                    });
            }
        }
    });
   
});


    
  


</script>


 <?=view('sci_main_footer') ?>