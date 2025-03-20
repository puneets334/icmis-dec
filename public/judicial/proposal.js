function feed_rmrk() {
    var ccstr = "";
    var regex = "";
    var nstr = false;
    var obrdrem = document.getElementById("brdremh").value;
    document.getElementById("brdrem").value = '';
    ccstr = obrdrem;
    $("input[type='checkbox'][name^='iachbx']").each(function () {
        var isChecked = document.getElementById($(this).attr('id')).checked;
        if (isChecked) {
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
function feed_rmrkia() {
    var ccstr = "";
    var regex = "";
    var nstr = false;
    //    var obrdrem = document.getElementById("brdremh").value;
    //    document.getElementById("brdrem").value = '';
    //    ccstr = obrdrem;
    $("#ianp_jshow").empty();
    $("input[type='checkbox'][name^='iachbx']").each(function () {

        if ($(this).prop('checked') == true) {
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

                var strrepll = tval[0].replace("/", "");
                $("#ianp_jshow").append("<span id='abc" + strrepll + "'>" + tval[0] + ", </span>");
            }
        }
        $("input:checkbox:not(:checked)").each(function () {
            var tval = $(this).val().split("|#|");
            regex = '\\b';
            regex += escapeRegExp(tval[0]);
            regex += '\\b';
            nstr = new RegExp(regex, "i").test(ccstr);
            var strrepll = tval[0].replace("/", "");
            $("#abc" + strrepll).remove();
        });
        //        if($(this).prop('checked') == false){
        //                var tval = $(this).val().split("|#|");
        //            regex = '\\b';
        //            regex += escapeRegExp(tval[0]);
        //            regex += '\\b';
        //            nstr = new RegExp(regex, "i").test(ccstr);            
        //                alert(ccstr);
        //                $("#abc"+tval[0]).remove();
        //            
        //        }
    });

    //document.getElementById("ianp_jshow").value = ccstr;
}
function escapeRegExp(string) {
    return string.replace(/([.*+?^=!:${}()|\[\]\/\\])/g, "\\$1");
}
function feed_rmrk_conn(fn) {
    var ccstr = "";
    var t_ccstr = "";
    var obrdrem = document.getElementById("brdremh_" + fn).value;
    document.getElementById("brdrem_" + fn).value = '';
    ccstr = obrdrem;
    $("input[type='checkbox'][name^='cn_ia_" + fn + "']").each(function () {
        var isChecked = document.getElementById($(this).attr('id')).checked;
        if (isChecked) {
            var tval = $(this).val().split("|#|");
            t_ccstr = "FOR " + tval[2] + " ON IA " + tval[1];
            if (ccstr != '') {
                var n = ccstr.search(t_ccstr);
                if (n < 0)
                    ccstr += " \n" + t_ccstr;
            }
            else {
                ccstr += " " + t_ccstr;
            }
        }
    });
    //alert(ccstr);
    document.getElementById("brdrem_" + fn).value = ccstr;
}
async function check_proposal() {
    await updateCSRFTokenSync();

    var diaryno = document.getElementById("diaryno").value;

    var url = base_url + "/Judicial/Proposal/check_proposal";
    var CSRF_TOKEN = 'CSRF_TOKEN';
    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

    $.ajax({
        type: "POST",
        url: url,
        data: { diaryno: diaryno, CSRF_TOKEN: CSRF_TOKEN_VALUE },
        success: function (msg) {
            if (msg == 'true')
                alert('Case is Listed in future date.');
            if (msg == 'false') {
                save_rec_prop();
            }
        },
        error: function () {
            alert("ERROR");
        }
    });
}

function chk_all_cn() {
    var regex = "";
    $("#ianp_jshow").empty();
    $("input[type='checkbox'][name^='iachbx']").each(function () {
        if (document.getElementById('allchkdn').checked) {

            $(this).prop('checked', true);
            var tval = $(this).val().split("|#|");
            regex = '\\b';
            regex += escapeRegExp(tval[0]);
            regex += '\\b';
            var strrepll = tval[0].replace("/", "");
            $("#ianp_jshow").append("<span id='abc" + strrepll + "'>" + tval[0] + ", </span>");
        }
        else {
            $(this).prop('checked', false);
            $("#ianp_jshow").empty();
        }
    });
}

async function save_rec_prop() {
    await updateCSRFTokenSync();

    var err_msg = '';
    var jrc = $("input[name='jrc']:checked").val();
    var lo = document.getElementById('listorder').value;
    var thdt = document.getElementById("thdate").value;
    var mf = document.getElementById('mf_select').value;
    var sh = document.getElementById('subhead_select').value;
    var r_nr = document.getElementById('r_nr').value;
    //var case_for_final = document.getElementById('case_for_final').value; 
    //var check_for_regular_case = document.getElementById('check_for_regular_case').value;      
    /* added on 30.11.2018 */
    if (thdt == '')
        // thdt='<?php echo $t_pdate;?>';
        thdt = document.getElementById('prev_thdate').value;
    /* end */
    if (jrc == '')
        err_msg = "Select Ready to list before\n";
    if (lo == '')
        err_msg += "Select Purpose of Listing\n";
    if (thdt == '')
        err_msg += "Enter Proposed Listing Date\n";
    if (mf == '')
        err_msg += "Select Hearing Head\n";
    if (sh == '' && mf != 'F')
        err_msg += "Select Sub Heading\n";
    //if(mf=='F' && check_for_regular_case=='' && case_for_final=='')
    //    err_msg+="Select Case Type for New Case no. for Regular Hearing.\n";
    if (err_msg != '') {
        alert(err_msg);
        return false;
    }
    else {
        if (mf == 'F')
            sh = '';
        //    if (lo == "48") {
        //      if (lo == "32") alert("Enter Purpose of Listing other than FRESH");
        //      if (lo == "48") alert("Enter Purpose of Listing other than NOT REACHED");
        //        return false;
        //    }
        var sj = document.getElementById('sj').value;
        //    var curr_date = document.getElementById("curr_date").value;

        //          var bnch = document.getElementById('bench').value;
        var supp_flag = 0;
        //    var case_receive_da = 0;
        //    if($("#da_case_rec_chkbx").is(':checked'))
        //        case_receive_da = 1;
        var qte_array = new Array();
        //    var today = new Date(curr_date);
        var url = base_url + "/Judicial/Proposal/insert_rec_prop";
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();


        //    var http = new getXMLHttpRequestObject();
        //    var str1 = "";
        var diaryno = document.getElementById("diaryno").value;
        var thdt1 = thdt.split("-");
        var thdt_new = thdt1[2] + "-" + thdt1[1] + "-" + thdt1[0];

        //    var hdate = new Date(thdt.replace("-", "/"));


        //    var curdate = new Date(curr_date); // get system date
        //    curdate_utc = Date.UTC(curdate.getFullYear(), curdate.getMonth(), curdate.getDate(), 0, 0, 0, 0);
        //    hdate_utc = Date.UTC(thdt1[2], thdt1[1] - 1, thdt1[0], 0, 0, 0, 0);  // month is 0 to 11 not 1 to 12
        //    if (curdate_utc > hdate_utc)
        //    {
        //        // alert("Sorry! Date of proposal cannot be before todays date!");
        //        // return false;
        //    }
        //    var ytq = '0';
        //     var ytq1 = '0';
        //    var hd_co_tot = document.getElementById('hd_co_tot').value;
        //    for (var itt = 1; itt <= hd_co_tot; itt++)
        //    {
        //        if(mf=='F'){
        //               if (document.getElementById('hd_sp_c' + itt))
        //        {
        //          if(bnch != document.getElementById('hd_sp_c' + itt).value){
        //               ytq1 = '1';
        //          }  
        //        }
        //    }
        //        if (document.getElementById('hd_sp_b' + itt))
        //        {
        //            ytq = 1;
        //        }
        //    }
        //if (ytq1 != '0')
        //        {
        //        alert("Please Check SUB HEADING, it must be selected for same Bench selected above")
        //    }
        //   else if (ytq == '0')
        //    {
        //        alert("Please Add atleast one Sub Heading")
        //    }
        //    else
        {
            var subhead_select = '';
            //        for (var itt = 1; itt <= hd_co_tot; itt++)
            //        {
            //            //  var main_cat= document.getElementById('hd_sp_a'+itt).value;
            //            if (document.getElementById('hd_sp_b' + itt))
            //            {
            //                if (subhead_select == '')
            //                    subhead_select = document.getElementById('hd_sp_b' + itt).value;
            //                else
            //                    subhead_select = subhead_select + ',' + document.getElementById('hd_sp_b' + itt).value;
            //            }
            //        }

            var br = document.getElementById('brdrem').value;
            //        var rem = document.getElementById('rem').value;
            //        var conncs = document.getElementById('conncs').value;
            var ucode = document.getElementById('ucode').value;
            var dacode = document.getElementById('da_hidden').value;
            //        var sbj = document.getElementById('sbj').value;
            //        var dbj1 = document.getElementById('dbj1').value;
            //        var dbj2 = document.getElementById('dbj2').value;
            //        var legalaid = "";
            //        if (document.getElementById("legalaid").checked && mf == "L") {
            //            legalaid = document.getElementById("legalaid").value;
            //        } else {
            //            legalaid = "";
            //        }
            var ccstr = "";
            var tcntr = 0;
            $("input[type='checkbox'][name^='ccchk']").each(function () {
                var isChecked = document.getElementById($(this).attr('id')).checked;
                qte_array[tcntr] = new Array(3);
                qte_array[tcntr][0] = $(this).val();
                qte_array[tcntr][1] = '';
                qte_array[tcntr][2] = '';
                qte_array[tcntr][3] = 'N';
                if (isChecked) {
                    qte_array[tcntr][3] = 'Y';
                    $("input[type='checkbox'][name^='cn_ia_" + qte_array[tcntr][0] + "']").each(function () {
                        var isChecked1 = document.getElementById($(this).attr('id')).checked;
                        if (isChecked1) {
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
                if (isChecked) {
                    var tval = $(this).val().split("|#|");
                    ccstr1 += tval[0] + ",";
                }
            });

            $.ajax({
                type: "POST",
                url: url,
                data: {
                    diaryno: diaryno,
                    thdt_new: thdt_new,
                    mf: mf,
                    sh: sh,
                    supp_flag: supp_flag,
                    lo: lo,
                    br: br,
                    jrc: jrc,
                    ccstr: ccstr,
                    ias: ccstr1,
                    sj: sj,
                    tcntr: tcntr,
                    r_nr: r_nr,
                    connlist: qte_array,
                    CSRF_TOKEN: CSRF_TOKEN_VALUE
                    // check_for_regular_case:check_for_regular_case,
                    //  case_for_final:case_for_final

                    //                subhead_select: subhead_select,
                    //                legalaid: legalaid
                    //                ,
                    //                case_receive_da: case_receive_da

                },
                success: function (msg) {

                    if (msg == '') {
                        //  fsubmit();
                    }
                    else {
                        alert(msg);

                        // fsubmit();
                    }
                    close_w2();
                    getDetails();
                    // $("input[name=btnGetR]").click();
                },
                error: function () {
                    alert("ERROR");
                    close_w2();
                }

            });
            close_w1();
        }

    }
}
function save_rec(cnt) {
    var cn = "";
    var stat = "";
    var cr_head = "";
    var subh = "";
    if (cnt == 1) {
        var div1 = "chkp";
        var div2 = "hdremp";
        cn = $('#tmp_casenop').val();
        stat = "P";
        cr_head = '<b><font color="blue">';
    }
    else {
        var div1 = "chkd";
        var div2 = "hdremd";
        cn = $('#tmp_casenod').val();
        subh = $('#tmp_casenosub').val();
        stat = "D";
        cr_head = '<b><font color="red">';
    }
    var chk_val;
    var cval = "";
    var crem = "";
    var str_new = "";
    var str_caseval = "";
    var isfalse = 0;
    $("input[type='checkbox'][name^='" + div1 + "']").each(function () {
        var isChecked = document.getElementById($(this).attr('id')).checked;
        if (isChecked) {
            chk_val = $(this).val().split("|");
            cval = $("#" + div1 + chk_val[0]).val().split("|");

            if (cnt == 1) {
                if (textformate(cval[0]) == false) {
                    isfalse = 1;
                }
                if (cval[0] == 24 || cval[0] == 21 || cval[0] == 70 || cval[0] == 59 || cval[0] == 131 || cval[0] == 91) {
                    // alert(cval[0]);
                    if ($("#" + div2 + cval[0]).val() == '') {
                        alert('Please Enter Date');
                        setFocusToTextBox(cval[0]);
                        isfalse = 1;
                    }
                    if (cval[0] == 91) {
                        var str91 = "";
                        $("input[type='checkbox'][name^='party']").each(function () {
                            var isChecked91 = document.getElementById($(this).attr('id')).checked;
                            if (isChecked91) {
                                var strnew91 = $(this).val().split("|");

                                str91 += strnew91[1] + "|" + strnew91[2] + "^^";
                            }
                        });

                        if (str91 == "") {
                            alert('Please Select Parties to appear before Registry');
                            isfalse = 1;
                        }
                    }



                }
                if (cval[0] == 149) {
                    if ($("#" + div2 + cval[0]).val() == '') {
                        alert('Please Enter Fresh PF (in days)');
                        //setFocusToTextBox(cval[0]);
                        isfalse = 1;
                    }
                    else {
                        var cntr = 0;
                        $("input[type='checkbox'][name^='tparty']").each(function () {
                            var isChecked = document.getElementById($(this).attr('id')).checked;
                            if (isChecked) {
                                cntr++;
                            }
                        });
                        if (cntr == 0) {
                            alert('Please Select Parties for Fresh PF');
                            setFocusToTextBox(cval[0]);
                            isfalse = 1;
                        } else {
                            var cntr1 = 0;
                            $("input[type='checkbox'][name^='ord']").each(function () {
                                var isChecked = document.getElementById($(this).attr('id')).checked;
                                if (isChecked) {
                                    cntr1++;
                                }
                            });
                            $("input[type='checkbox'][name^='reg']").each(function () {
                                var isChecked = document.getElementById($(this).attr('id')).checked;
                                if (isChecked) {
                                    cntr1++;
                                }
                            });
                            $("input[type='checkbox'][name^='hum']").each(function () {
                                var isChecked = document.getElementById($(this).attr('id')).checked;
                                if (isChecked) {
                                    cntr1++;
                                }
                            });
                            if (cntr1 == 0) {
                                alert('Please Select PF Type for Fresh PF');
                                setFocusToTextBox(cval[0]);
                                isfalse = 1;
                            }
                        }
                    }

                }
            }
            // alert(chk_val[0]);    
            crem = $("#" + div2 + chk_val[0]).val();
            str_new += cval[0] + "|" + crem + "!";
            str_caseval += cval[0] + "|" + crem + "^^";
            cr_head += cval[1];
            if (crem != "")
                cr_head += ' [' + crem + ']';
            cr_head += '<br>';
        }
    });
    //    if(str_new=="")
    //    {
    //       isfalse=1;  
    //       if(stat=='D')
    //           var status1='Disposal';
    //              if(stat=='P')
    //           var status1='Pending';
    //       alert("Select atleast one "+status1+" Case Remark");
    //    }    
    cr_head += '</font></b>';

    //str2=str2+"<tr><td>"+cntr+"</td><td>"+document.getElementById("brd"+filno).innerHTML+"</td><td>"+document.getElementById("cs"+filno).innerHTML+"</td><td>"+document.getElementById("pn"+filno).innerHTML+"</td><td>"+document.getElementById("rn"+filno).innerHTML+"</td><td>"+document.getElementById("pad"+filno).innerHTML+"</td><td>"+document.getElementById("rad"+filno).innerHTML+"</td></tr>";

    if (isfalse == 0) {
        var url = "insert_rec_an.php";
        var http = new getXMLHttpRequestObject();
        //var xhr2=getXMLHTTP();
        var str1 = "";
        var dt = document.getElementById("dtd").value;
        var hdt = document.getElementById("hdate").value;
        var ucode = document.getElementById('hd_ud').value;
        var uip = document.getElementById('hd_ipadd').value;
        var umac = document.getElementById('hd_macadd').value;
        var subh = document.getElementById("tmp_casenosub").value;

        //var msg_t=document.getElementById("msgbox").value;
        var dt1 = dt.split("-");
        var dt_new = dt1[2] + "-" + dt1[1] + "-" + dt1[0];

        var hdt1 = hdt.split("-");
        var hdt_new = hdt1[2] + "-" + hdt1[1] + "-" + hdt1[0];

        //var str="insert_rec_an.php?str="+str1+"&dt="+dt_new;
        //alert(str);
        str1 = document.getElementById("jcodes").value + "|" + document.getElementById("mh").value + "|" + document.getElementById("clno").value + "|" + subh;
        str_new = cn + "#" + stat + "#" + str_new;
        //alert(str_new);
        //alert(str1);
        var parameters = "str=" + str_new;
        parameters += "&str1=" + str1;
        parameters += "&dt=" + dt_new;
        parameters += "&hdt=" + hdt_new;
        parameters += "&ucode=" + ucode;
        parameters += "&uip=" + uip;
        parameters += "&umac=" + umac;
        //document.getElementById("proc").innerHTML="<img src='saving.gif'/>";
        http.open("POST", url, true);
        //Send the proper header information along with the request
        http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        http.setRequestHeader("Content-length", parameters.length);
        http.setRequestHeader("Connection", "close");
        http.onreadystatechange = function () {//Handler function for call back on state change.
            if (http.readyState == 4) {
                //document.getElementById("proc").innerHTML="";
                var data = http.responseText;
                if (data != "")
                    alert(data);
                else {
                    //$("#submit").click();
                    save_parties1();
                    fsubmit();
                }
                //document.getElementById("caseval"+cn).value=str_caseval;
                //document.getElementById("cr_span"+cn).innerHTML=cr_head;
            }
        }
        http.send(parameters);
        close_w(cnt);
    }
}
function chg_def1() {
    var tdt1 = $('#prev_thdate').val();
    var lo = document.getElementById("listorder").value;
    var tdt = $('#thdate').val();
    var usercode = document.getElementById('usercode').value;
    //  var ttd = $('#ttd').val();
    /* added on 30.11.2018 */
    if (tdt == '')
        // tdt='<?php echo $t_pdate;?>';
        tdt = document.getElementById('prev_thdate').value;
    /* end */

    if ($("#mf_select").val() == "L" || $("#mf_select").val() == "S") {
        ed = 0;
        document.getElementById("listorder").value = 16;
    }

    if (lo == "49" || lo == "4" || lo == "5" || ((lo == "7" || lo == "8") && usercode == "646") || tdt == "" || tdt == "00-00-0000") {
        $('#thdate').prop('disabled', false);
    }
    else {
        $('#thdate').val(tdt1);
        $('#thdate').prop('disabled', true);
    }
}

/* Added on 07.01.2020 */
async function get_subheading() {
    await updateCSRFTokenSync();

    var jj = 0;
    var sh = $('#sh').val();
    var jrc = $("input[name='jrc']:checked").val();
    jj = $('#mf_select').val();
    var remarks = document.getElementById('last_remarks').value;
    var cl_date = document.getElementById('last_cl_date').value;
    var notice_remarks = "3,182,183,184,203";
    var usercode = document.getElementById('usercode').value;
    var notice_remarks_id = notice_remarks.split(",");
    var today = new Date().toISOString().slice(0, 10);
    var diff = Math.floor((Date.parse(today) - Date.parse(cl_date)) / 86400000);
    var noticeissued = 0;
    for (var i = 0; i <= notice_remarks_id.length; i++) {
        if (remarks == notice_remarks_id[i] && diff < 38 && usercode != 1 && usercode != 146 && usercode != 559) {
            noticeissued = 1;
        }

    }
    if (document.getElementById("listorder"))
        var lo = document.getElementById("listorder").value;
    else
        var lo = 0;

    if (jj == "M" || jj == "L" || jj == "S") {
        if (noticeissued == 1)
            $("input[name='jrc'][value='R']").prop('disabled', true);
        else {
            $("input[name='jrc'][value='R']").prop('disabled', false);
            $("input[name='jrc'][value='C']").prop('disabled', false);
        }
        $('#subhead_select').prop('disabled', false);
        $('.fh_error').hide();
        $('#insert1').prop('disabled', false);

        var url = base_url + "/Judicial/Proposal/get_mf_subhead";
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        $.ajax({
            type: 'POST',
            url: url,
            data: { mf: jj, sh: sh, jrc: jrc, CSRF_TOKEN: CSRF_TOKEN_VALUE }
        })
            .done(function (msg) {
                // updateCSRFToken();

                msg = "<option value=''>SELECT</option>" + msg;
                //document.getElementById('mf_box').innerHTML=arr[0];
                document.getElementById('subhead_select').innerHTML = msg;
            })
            .fail(function () {
                // updateCSRFToken();
                alert("Please try again or contact server room");
            });


    }
    else {

        $("input[name='jrc'][value='J']").prop('checked', true);
        $("input[name='jrc'][value='R']").prop('disabled', true);
        $("input[name='jrc'][value='C']").prop('disabled', true);

        $('#subhead_select').prop('disabled', true);
        $('.fh_error').show();

    }
    var tdt = $('#thdate').val();
    /* added on 30.11.2018 */
    if (tdt == '')
        tdt = document.getElementById('prev_thdate').value;
    /* end */
    if (jj == "L" || jj == "S") {
        $('#thdate').prop('disabled', false);
        $('#listorder').val(16);
    }
    else {
        d11 = (document.getElementById('thdate_nm').value).split("-");
        d22 = (document.getElementById('thdate_h').value).split("-");
        d1 = new Date(d11[2], (d11[1] - 1), d11[0]);
        d2 = new Date(d22[2], (d22[1] - 1), d22[0]);
    }
}


//function changed on 28012020 to propose the matter before registrar court if IA for c/d in filing spare copies is filled
function check_notice() {
    var remarks = document.getElementById('last_remarks').value;
    var cl_date = document.getElementById('last_cl_date').value;
    var pendingIA = document.getElementById('pendingIAs').value;
    var IA_to_allow_flag = false;
    var IA_TO_Allow = "197";     //allow IA in Registrar Court even if Notice is issued
    var notice_remarks = "3,182,183,184,203";
    var jrc = $("input[name='jrc']:checked").val();
    var notice_remarks_id = notice_remarks.split(",");
    var pendingIAs = pendingIA.split(",");
    var today = new Date().toISOString().slice(0, 10);
    var diff = Math.floor((Date.parse(today) - Date.parse(cl_date)) / 86400000);
    for (var i = 0; i <= pendingIAs.length; i++) {
        if (IA_TO_Allow == pendingIAs[i])
            IA_to_allow_flag = true;

    }
    for (var i = 0; i <= notice_remarks_id.length; i++) {
        if (remarks == notice_remarks_id[i] && diff < 38 && IA_to_allow_flag == false) {
            $("input[name='jrc'][value='R']").prop('disabled', true);
            alert("In the last hearing, notice was issued so matter cannot be proposed for Ld. Registrar Court for 38days from date of listing.")
        }
        else if (remarks == notice_remarks_id[i] && diff < 38 && IA_to_allow_flag == true) {
            $("input[name='jrc'][value='R']").prop('disabled', false);
        }
    }

}


/*
// new function added by preeti on 24102019 to check whether notice was issued in the last hearing
function check_notice()
{
 var remarks=document.getElementById('last_remarks').value;
 var cl_date=document.getElementById('last_cl_date').value;
 var notice_remarks="3,182,183,184,203";
    var jrc=$("input[name='jrc']:checked").val();
 var notice_remarks_id=notice_remarks.split(",");
 var today=new Date().toISOString().slice(0,10);
 var diff=Math.floor((Date.parse(today) - Date.parse(cl_date)) / 86400000);
    for (var i = 0; i <= notice_remarks_id.length; i++) {
        if (remarks == notice_remarks_id[i] && diff < 38) {
            $("input[name='jrc'][value='R']").prop('disabled', true);
          alert("In the last hearing, notice was issued so matter cannot be proposed for Ld. Registrar Court for 38days from date of listing.")
        }

   }

}
*/

async function call_f1() {
    $('#model-proposal-form').modal({ backdrop: 'static', keyboard: false });
    $('#model-proposal-form').modal('show');

    check_notice();  //new function added by preeti on 24102019
    // var divname = "";
    // divname = "newb";
    // // $('#' + divname).width($(window).width() - 150);
    // // $('#' + divname).height($(window).height() - 120);

    // $('#' + divname).width(window.frameElement.offsetWidth - 150);
    // $('#' + divname).height(window.frameElement.offsetHeight- 120);

    // $('#newb123').height($('#newb').height() - $('#newb1').height() - 50);

    // var newX = ($('#' + divname).width() / 2);
    // var newY = ($('#' + divname).height() / 2);
    // document.getElementById(divname).style.marginLeft = "-" + newX + "px";
    // document.getElementById(divname).style.marginTop = "-" + newY + "px";
    // document.getElementById(divname).style.display = 'block';
    // document.getElementById(divname).style.zIndex = 10;
    // $('#overlay').height($(window).height());
    // document.getElementById('overlay').style.display = 'block';
    await get_tentative_date();
    await get_subheading();
}

function close_w() {
    $('#model-proposal-form').modal('hide');

    // var divname = "";
    // divname = "newb";
    // document.getElementById(divname).style.display = 'none';
    // document.getElementById('overlay').style.display = 'none';
}

function close_wcs() {
    var divname = "";
    divname = "newcs";
    document.getElementById(divname).style.display = 'none';
    document.getElementById('overlay').style.display = 'none';
}

function close_w1() {
    $('#model-proposal-form').modal('hide');
    // var divname = "";
    // divname = "newb";
    // document.getElementById(divname).style.display = 'none';
}
function close_w2() {
    $('#model-proposal-form').modal('hide');

    // document.getElementById('overlay').style.display = 'none';
}

function call_fcs(d_no, d_yr, ct, cn, cy) {
    var divname = "";
    divname = "newcs";
    document.getElementById(divname).style.display = 'block';
    $('#' + divname).width($(window).width() - 150);
    $('#' + divname).height($(window).height() - 120);
    $('#newcs123').height($('#newcs').height() - $('#newcs1').height() - 50);
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
        url: "../case_status/case_status_process.php",
        beforeSend: function (xhr) {
            $("#newcs123").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='../images/load.gif'></div>");
        },
        data: { d_no: d_no, d_yr: d_yr, ct: ct, cn: cn, cy: cy, tab: 'Case Details', opt: 2 }
    })
        .done(function (msg) {
            $("#newcs123").html(msg);
        })
        .fail(function () {
            alert("ERROR, Please Contact Server Room");
        });
}

$(document).ready(function () {
    $(document).on('click', '[id^=accordion] a', function (event) {
        var accname = $(this).attr('data-parent');

        if (typeof $(this).attr('data-parent') !== "undefined") {
            //var collapse_element = event.target;
            var url = "";
            //    alert(collapse_element);
            var href = this.hash;
            var depId = href.replace("#collapse", "");
            var accname1 = accname.replace("#accordion", "");
            var acccnt = accname1 * 100;
            var diaryno = document.getElementById('diaryno' + accname1).value;
            if (depId != (acccnt + 1)) {
                if (depId == (acccnt + 2)) url = "../case_status/get_earlier_court.php";
                if (depId == (acccnt + 3)) url = "../case_status/get_connected.php";
                if (depId == (acccnt + 4)) url = "../case_status/get_listings.php";
                if (depId == (acccnt + 5)) url = "../case_status/get_ia.php";
                //    if(depId==6) url="get_earlier_court.php";
                if (depId == (acccnt + 6)) url = "../case_status/get_court_fees.php";
                if (depId == (acccnt + 7)) url = "../case_status/get_notices.php";
                if (depId == (acccnt + 8)) url = "../case_status/get_default.php";
                if (depId == (acccnt + 9)) url = "../case_status/get_judgement_order.php";
                if (depId == (acccnt + 10)) url = "../case_status/get_adjustment.php";
                if (depId == (acccnt + 11)) url = "../case_status/get_mention_memo.php";
                if (depId == (acccnt + 12)) url = "../case_status/get_restore.php";
                if (depId == (acccnt + 13)) url = "../case_status/get_drop.php";
                if (depId == (acccnt + 14)) url = "../case_status/get_appearance.php";
                if (depId == (acccnt + 15)) url = "../case_status/get_office_report.php";
                if (depId == (acccnt + 16)) url = "../case_status/get_similarities.php";
                if (depId == (acccnt + 17)) url = "../case_status/get_caveat.php";
                // var dataString = 'depId='+ depId + '&do=getDepUsers';
                $.ajax({
                    type: 'POST',
                    url: url,
                    beforeSend: function (xhr) {
                        $("#result" + depId).html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='../images/load.gif'></div>");
                    },
                    data: { diaryno: diaryno }
                })
                    .done(function (msg) {
                        $("#result" + depId).html(msg);
                    })
                    .fail(function () {
                        alert("ERROR, Please Contact Server Room");
                    });
            }
        }
    });
    $("#radiodn").click(function () {
        $("#dno").removeProp('disabled');
        $("#dyr").removeProp('disabled');
        $("#selct").prop('disabled', true);
        $("#case_no").prop('disabled', true);
        $("#case_yr").prop('disabled', true);
        $("#selct").val("-1");
        $("#case_no").val("");
        $("#case_yr").val("");
    });

    $("#radioct").click(function () {
        $("#dno").prop('disabled', true);
        $("#dyr").prop('disabled', true);
        $("#dno").val("");
        $("#dyr").val("");
        $("#selct").removeProp('disabled');
        $("#case_no").removeProp('disabled');
        $("#case_yr").removeProp('disabled');
    });
    $(document).on('click', "input[type='checkbox'][name^='ccchk']", function () {
        if ($('#ttlconn').length) {
            var cntr = $('#ttlconn').html();
            var cntr1 = parseInt(cntr);
            if ($(this).is(':checked')) {
                cntr1++;
            }
            else {
                cntr1--;
            }
            $('#ttlconn').html(cntr1);
        }
    });
    $(document).on('click', '#checkall', function () {

        $("input[type='checkbox'][name^='ccchk']").each(function () {
            if ($("#checkall").is(':checked')) {
                $(this).prop("checked", true);
            }
            else {
                $(this).prop("checked", false);
            }
        });
    });

    $("input[name=btnGetR]").click(function () {
        var diaryno, diaryyear, cstype, csno, csyr;
        var regNum = new RegExp('^[0-9]+$');

        if ($("#radioct").is(':checked')) {
            cstype = $("#selct").val();
            csno = $("#case_no").val();
            csyr = $("#case_yr").val();

            if (!regNum.test(cstype)) {
                alert("Please Select Casetype");
                $("#selct").focus();
                return false;
            }
            if (!regNum.test(csno)) {
                alert("Please Fill Case No in Numeric");
                $("#case_no").focus();
                return false;
            }
            if (!regNum.test(csyr)) {
                alert("Please Fill Case Year in Numeric");
                $("#case_yr").focus();
                return false;
            }
            if (csno == 0) {
                alert("Case No Can't be Zero");
                $("#case_no").focus();
                return false;
            }
            if (csyr == 0) {
                alert("Case Year Can't be Zero");
                $("#case_yr").focus();
                return false;
            }
            /*if(cstype.length==1)
                cstype = '00'+cstype;
            else if(cstype.length==2)
                cstype = '0'+cstype;*/
        }
        else if ($("#radiodn").is(':checked')) {
            diaryno = $("#dno").val();
            diaryyear = $("#dyr").val();
            if (!regNum.test(diaryno)) {
                alert("Please Enter Diary No in Numeric");
                $("#dno").focus();
                return false;
            }
            if (!regNum.test(diaryyear)) {
                alert("Please Enter Diary Year in Numeric");
                $("#dyr").focus();
                return false;
            }
            if (diaryno == 0) {
                alert("Diary No Can't be Zero");
                $("#dno").focus();
                return false;
            }
            if (diaryyear == 0) {
                alert("Diary Year Can't be Zero");
                $("#dyr").focus();
                return false;
            }
        } else {
            alert('Please Select Any Option');
            return false;
        }

        $.ajax({
            type: 'POST',
            url: "./get_report.php",
            data: { d_no: diaryno, d_yr: diaryyear, ct: cstype, cn: csno, cy: csyr },
            beforeSend: function (xhr) {
                $("#dv_res1").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='../images/load.gif'></div>");
            }
        }).done(function (msg) {
            $("#dv_res1").html(msg);
            // get_subheading();
        }).fail(function () {
            alert("ERROR, Please Contact Server Room");
        });
    });
});

async function get_tentative_date() {
    await updateCSRFTokenSync();

    var board_type = $("input[name='jrc']:checked").val();
    var listorder = $('#listorder').val();
    var next_dt = $('#thdate').val();
    var mainhead = $('#mf_select').val();
    var diaryno = $('#diaryno').val();
    var prev_board_type = $('#lastProposed').val();
    var lastListedOn = $('#lastListedOn').val();
    var lastSubHead = $('#lastSubHead').val();
    /* if((board_type=='C' || board_type=='R') && (prev_board_type=='J'))
    {
      document.getElementById('listorder').disabled=true;
    }
    else if(board_type=='J')
    {
        document.getElementById('listorder').disabled=false;
    }
    */

    var url = base_url + "/Judicial/Proposal/get_tentative_date";

    var CSRF_TOKEN = 'CSRF_TOKEN';
    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

    $.ajax({
        type: 'POST',
        url: url,
        data: { board_type: board_type, listorder: listorder, next_dt: next_dt, mainhead: mainhead, diaryno: diaryno, prev_board_type: prev_board_type, lastListedOn: lastListedOn, CSRF_TOKEN: CSRF_TOKEN_VALUE }
    })
        .done(function (msg) {
            // updateCSRFToken();

            //alert(msg);
            $('#thdate').val(msg);
            /*if(listorder == 4 || listorder == 5){
                $('#thdate').prop('enabled', true);
            }*/
        })
        .fail(function () {
            // updateCSRFToken();

            alert("ERROR, Please Contact Server Room");
        });
}
