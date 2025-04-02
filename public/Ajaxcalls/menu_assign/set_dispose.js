var dtCh = "/";
var minYear = 1900;
var maxYear = 2100;
function getXMLHTTP() { //fuction to return the xml http object
    var xmlhttp = false;
    try {
        xmlhttp = new XMLHttpRequest();
    }
    catch (e) {
        try {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        catch (e) {
            try {
                xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
            }
            catch (e1) {
                xmlhttp = false;
            }
        }
    }
    return xmlhttp;
}

function getXMLHttpRequestObject() {
    var xmlhttp;
    if (!xmlhttp && typeof XMLHttpRequest != 'undefined') {
        try {
            xmlhttp = new XMLHttpRequest();
        } catch (e) {
            xmlhttp = false;
        }
    }
    return xmlhttp;
}

function isInteger(s) {
    var i;
    for (i = 0; i < s.length; i++) {
        var c = s.charAt(i);
        if (((c < "0") || (c > "9")))
            return false;
    }
    return true;
}
function chk_all_cn() {

    $("input[type='checkbox'][name^='conncchk']").each(function () {
        if (document.getElementById('connall').checked)
            $(this).prop('checked', true);
        else
            $(this).prop('checked', false);
    });
}
function textformate(cb) {
    var y = document.getElementById('hdremp' + cb).value;
    x = y.split(",");
    if (cb == 72) {
        for (var i = 0; i < x.length; i++) {
            var iChars = "~`!#$%^&*+=-[]\\\';/{}|\":<>?";
            for (var j = 0; j < x[i].length; j++) {
                if (iChars.indexOf(x[i].charAt(j)) !== -1) {
                    alert("Special characters ~`!#$%^&*+=-[]\\\';/{}|\":<>? \nThese are not allowed\n");
                    return false;
                }
            }
            casenoyr = (x[i].replace(/[^0-9]/g, "").length);
            casetyp = (x[i].replace(/[^a-zA-Z]/g, "").length);
            ctype = x[i].replace(/[^a-zA-Z]/g, "");
            ctyp = ctype.toUpperCase();
            var cpa = 0;
            switch (ctyp) {
                case 'AA':
                    break;
                case 'AC':
                    break;
                case 'AR':
                    break;
                case 'ARBA':
                    break;
                case 'ARBC':
                    break;
                case 'CA':
                    break;
                case 'CEA':
                    break;
                case 'CER':
                    break;
                case 'CESR':
                    break;
                case 'COMA':
                    break;
                case 'COMP':
                    break;
                case 'COMPA':
                    break;
                case 'CONA':
                    break;
                case 'CONC':
                    break;
                case 'CONCR':
                    break;
                case 'CONT':
                    break;
                case 'CONTR':
                    break;
                case 'CR':
                    break;
                case 'CRA':
                    break;
                case 'CRR':
                    break;
                case 'CRRE':
                    break;
                case 'CRRF':
                    break;
                case 'CRRFC':
                    break;
                case 'CS':
                    break;
                case 'EP':
                    break;
                case 'FA':
                    break;
                case 'FEMA':
                    break;
                case 'GTR':
                    break;
                case 'ITA':
                    break;
                case 'ITR':
                    break;
                case 'LPA':
                    break;
                case 'MA':
                    break;
                case 'MACE':
                    break;
                case 'MACOM':
                    break;
                case 'MACTR':
                    break;
                case 'MAIT':
                    break;
                case 'MAVAT':
                    break;
                case 'MCC':
                    break;
                case 'MCOMA':
                    break;
                case 'MCP':
                    break;
                case 'MCRC':
                    break;
                case 'MCRP':
                    break;
                case 'MP':
                    break;
                case 'MWP':
                    break;
                case 'OTA':
                    break;
                case 'RP':
                    break;
                case 'SA':
                    break;
                case 'SLP':
                    break;
                case 'STR':
                    break;
                case 'TR':
                    break;
                case 'VATA':
                    break;
                case 'WA':
                    break;
                case 'WP':
                    break;
                case 'WPS':
                    break;
                case 'WTA':
                    break;
                case 'WTR':
                    break;
                default:
                    {
                        alert("Please Enter proper Case ");
                        cpa++;
                        return false;
                    }
            }
            casetyp = x[i].slice(-casetyp);
            cnyr = x[i].slice(-casenoyr);
            var x1 = x[i].slice(-cnyr);
            if (casenoyr <= 4) {
                alert("Please Type Correct Case No And Year");
                return false;
            }
            if (casenoyr == 5)
                cnyr = '0000' + cnyr;
            if (casenoyr == 6)
                cnyr = '000' + cnyr;
            if (casenoyr == 7)
                cnyr = '00' + cnyr;
            if (casenoyr == 8)
                cnyr = '0' + cnyr;
            var yr = cnyr.slice(-4);
            var srvr = document.getElementById('srvr').value;
            if (yr <= 1959) {
                alert("Please Enter Correct Year Greater then 1959");
                return false;
            }
            if (yr > srvr) {
                alert("Please Enter Correct Year Less  then " + srvr);
                return false;
            }
        }
    }
    if (cb == 68 || cb == 23 || cb == 53 || cb == 54 || cb == 25 || cb == 122 || cb == 123) {
        if (isNaN(y)) {
            alert('Please Enter Numeric Value');
            setFocusToTextBox(cb);
            return false;
        }
    }
    if (cb == 53 || cb == 25) {
        if (y >= 31) {
            alert('Please Enter Numeric Value Between 1 TO 31 Which Is No Of Days In A Month');
            setFocusToTextBox(cb);
            return false;
        }
    }
    if (cb == 23 || cb == 122) {
        if (y >= 54) {
            alert('Please Enter Numeric Value Between 1 TO 52 Which Is Week No Of The Year');
            // y.focus();
            setFocusToTextBox(cb);
            return false;
        }
    }
    if (cb == 68 || cb == 123 || cb == 54) {
        if (y >= 12 && y !== 0) {
            alert('Please Enter Numeric Value Between 1 TO 12 Which Is Month Of The Year');
            setFocusToTextBox(cb);
            return false;
        }
    }
    return true;
}

function setFocusToTextBox(cb) {
    var textbox = document.getElementById('hdremp' + cb);
    $("#hdremp" + cb).focus();
    textbox.scrollIntoView();
}
function chg_def1() {
    var ck_cl_d = '0';
    var hd_co_tot = document.getElementById('hd_co_tot').value;
    for (var i = 1; i <= hd_co_tot; i++) {
        if (document.getElementById('hd_sp_a' + i)) {
            if (document.getElementById('hd_sp_b' + i).value == '850' || document.getElementById('hd_sp_b' + i).value == '851') {
                ck_cl_d = 1;
            }
        }
    }
    if (ck_cl_d == 1) {
        if (document.getElementById("listorder").value == "50") {
            $("#bench").val("D");
            $("#dbj1").val("514");
            $("#dbj2").val("999");
            document.getElementById("judge_sb").style.display = "none";
            document.getElementById("judge_db").style.display = "block";
        }
        else {
            if ($("#bench").val() == "S") {
                $("#sbj").val("514");
            }
            if ($("#bench").val() == "D") {
                $("#dbj1").val("514");
                $("#dbj2").val("999");
            }
        }
    }
    else {
        if (document.getElementById("listorder").value == "50") {
            $("#bench").val("D");
            $("#dbj1").val("200");
            $("#dbj2").val("999");
            document.getElementById("judge_sb").style.display = "none";
            document.getElementById("judge_db").style.display = "block";
        }
        else {
            if ($("#bench").val() == "S") {
                $("#sbj").val("250");
                document.getElementById("judge_sb").style.display = "block";
                document.getElementById("judge_db").style.display = "none";
            }
            if ($("#bench").val() == "D") {
                $("#dbj1").val("200");
                $("#dbj2").val("999");
                document.getElementById("judge_sb").style.display = "none";
                document.getElementById("judge_db").style.display = "block";
            }
        }
    }
}

function feed_rmrk() {
    var ccstr = "";
    var obrdrem = document.getElementById("brdremh").value;
    document.getElementById("brdrem").value = '';
    ccstr = obrdrem;
    $("input[type='checkbox'][name^='iachbx']").each(function () {
        var isChecked = document.getElementById($(this).attr('id')).checked;
        if (isChecked) {
            var tval = $(this).val().split("|#|");
            if (ccstr != '')
                ccstr += " \nFOR " + tval[1] + " ON IA " + tval[0];
            else
                ccstr += " FOR " + tval[1] + "  ON IA " + tval[0];
        }
    });
    document.getElementById("brdrem").value = ccstr;
}

function feed_rmrk_conn(fn) {
    var ccstr = "";
    var obrdrem = document.getElementById("brdremh_" + fn).value;
    document.getElementById("brdrem_" + fn).value = '';
    ccstr = obrdrem;
    $("input[type='checkbox'][name^='cn_ia_" + fn + "']").each(function () {
        var isChecked = document.getElementById($(this).attr('id')).checked;
        if (isChecked) {
            var tval = $(this).val().split("|#|");
            if (ccstr != '')
                ccstr += " \nFOR " + tval[2] + " ON IA " + tval[1];
            else
                ccstr += " FOR " + tval[2] + "  ON IA " + tval[1];
        }
    });
    document.getElementById("brdrem_" + fn).value = ccstr;
}

//function save_rec_prop()
//{
//    var curr_date=document.getElementById("curr_date").value;
//    var qte_array = new Array();
//    var today = new Date(curr_date);
//    var url = "insert_rec_prop.php";
//    var http = new getXMLHttpRequestObject();
//    var str1 = "";
//    var flno = document.getElementById("fil_no").value;
//    var thdt = document.getElementById("thdate").value;
//    var thdt1 = thdt.split("-");
//    var thdt_new = thdt1[2] + "-" + thdt1[1] + "-" + thdt1[0];
//    var hdate = new Date(thdt.replace("-", "/"));
//    var curdate = new Date(curr_date); // get system date
//    curdate_utc = Date.UTC(curdate.getFullYear(), curdate.getMonth(), curdate.getDate(), 0, 0, 0, 0);
//    hdate_utc = Date.UTC(thdt1[2], thdt1[1] - 1, thdt1[0], 0, 0, 0, 0);  // month is 0 to 11 not 1 to 12
//    if (curdate_utc > hdate_utc)
//    {
//        alert("Sorry! Date of proposal cannot be before todays date!");
//        return false;
//    }
//    var ytq = '0';
//    var hd_co_tot = document.getElementById('hd_co_tot').value;
//    for (var itt = 1; itt <= hd_co_tot; itt++)
//    {
//        if (document.getElementById('hd_sp_b' + itt))
//        {
//            ytq = 1;
//        }
//    }
//    if (ytq == '0')
//    {
//        alert("Please Add atleast one Sub Heading")
//    }
//    else
//    {
//        var subhead_select = '';
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
//        var mf = document.frm.mf_select.value;
//        var sh = document.frm.subhead_select.value;
//        var bnch = document.frm.bench.value;
//        var lo = document.frm.listorder.value;
//        var br = document.frm.brdrem.value;
//        var rem = document.frm.rem.value;
//        var conncs = document.frm.conncs.value;
//        var ucode = document.getElementById('hd_ud').value;
//        var sbj = document.frm.sbj.value;
//        var dbj1 = document.frm.dbj1.value;
//        var dbj2 = document.frm.dbj2.value;
//        var ccstr = "";
//        var tcntr = 0;
//        $("input[type='checkbox'][name^='ccchk']").each(function() {
//            var isChecked = document.getElementById($(this).attr('id')).checked;
//            if (isChecked)
//            {
//                qte_array[tcntr] = new Array(3);
//                qte_array[tcntr][0] = $(this).val();
//                qte_array[tcntr][1] = '';
//                qte_array[tcntr][2] = '';
//                $("input[type='checkbox'][name^='cn_ia_" + qte_array[tcntr][0] + "']").each(function() {
//                    var isChecked1 = document.getElementById($(this).attr('id')).checked;
//                    if (isChecked1)
//                    {
//                        var tval = $(this).val().split("|#|");
//                        qte_array[tcntr][1] += tval[1] + ", ";
//                    }
//                });
//                var obrdrem = document.getElementById("brdrem_" + qte_array[tcntr][0]).value;
//                qte_array[tcntr][2] = obrdrem;
//
//                ccstr += $(this).val() + ",";
//                tcntr++;
//            }
//        });
//        var ccstr1 = "";
//        $("input[type='checkbox'][name^='iachbx']").each(function() {
//            var isChecked = document.getElementById($(this).attr('id')).checked;
//            if (isChecked)
//            {
//                var tval = $(this).val().split("|#|");
//                ccstr1 += tval[0] + ",";
//            }
//        });
//        $.ajax({
//            type: "POST",
//            url: url,
//            data: {flno: flno,
//                thdt_new: thdt_new,
//                mf: mf,
//                sh: sh,
//                bnch: bnch,
//                lo: lo,
//                br: br,
//                rem: rem,
//                conncs: conncs,
//                ccstr: ccstr,
//                ucode: ucode,
//                ias: ccstr1,
//                sbj: sbj,
//                dbj1: dbj1,
//                dbj2: dbj2,
//                connlist: qte_array,
//                subhead_select: subhead_select
//            },
//            success: function(msg) {
//                if (msg == '')
//                {
//                    fsubmit();
//                }
//                else
//                {
//                    alert(msg);
//                    fsubmit();
//                }
//            },
//            error: function() {
//                alert("ERROR");
//            }
//        });
//        close_w(3);
//    }
//}

//function save_rec_adv()
//{
//    var url = "insert_rec_adv.php";
//    var http = new getXMLHttpRequestObject();
//    var str1 = "";
//    var flno = document.getElementById("fil_no").value;
//    var teno = document.getElementById("txt_e_no").value;
//    var teny = document.getElementById("txt_e_yr").value;
//    var advside = document.getElementById("advside").value;
//    var parameters = "flno=" + flno;
//    parameters += "&teno=" + teno;
//    parameters += "&teny=" + teny;
//    parameters += "&advside=" + advside;
//    http.open("POST", url, true);
//    http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
//    http.setRequestHeader("Content-length", parameters.length);
//    http.setRequestHeader("Connection", "close");
//    http.onreadystatechange = function() {//Handler function for call back on state change.
//        if (http.readyState == 4) {
//            //document.getElementById("proc").innerHTML="";
//            var data = http.responseText;
//            if (data != "")
//                alert(data);
//            else
//            {
//                fsubmit();
//            }
//        }
//    }
//    http.send(parameters);
//    close_w(4);
//}

function save_rec(cnt) {
    var cn = "";
    var stat = "";
    var cr_head = "";
    var rjdt = "00-00-0000";
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
    var jcodes = "";
    //var bench = document.getElementById("dbench").value;
    var jcnt = 0;
    var chk_var = false;
    var chk_var1 = false;
    $("input[type='checkbox'][id^='hd_chk_jd']").each(function () {
        if (document.getElementById($(this).attr('id')).checked) {
            jcodes += $(this).val().split("||")[0] + ",";
            jcnt++;
        }
    });
    //    if (bench == "")
    //    {
    //        alert("Select Bench");
    //        return false;
    //    }
    if (jcodes == "") {
        alert("Select Judge");
        return false;
    }
    //    if (bench == "S" && jcnt != 1)
    //    {
    //        alert("Select one Judge for Single Bench");
    //        return false;
    //    }
    //    if (bench == "D" && jcnt != 2)
    //    {
    //        alert("Select two Judge for Division Bench");
    //        return false;
    //    }
    //    if (bench == "F" && jcnt < 3)
    //    {
    //        alert("Select atleast three Judge for Full Bench");
    //        return false;
    //    }
    $("input[type='checkbox'][name^='" + div1 + "']").each(function () {
        var isChecked = document.getElementById($(this).attr('id')).checked;
        if (isChecked) {
            chk_var = true;
            chk_val = $(this).val().split("|");
            cval = $("#" + div1 + chk_val[0]).val().split("|");
            if (cnt == 1) {
                if (textformate(cval[0]) == false) {
                    isfalse = 1;
                }
                if (cval[0] == 24 || cval[0] == 21 || cval[0] == 70 || cval[0] == 59) {
                    if ($("#" + div2 + cval[0]).val() == '') {
                        alert('Please Enter Date');
                        setFocusToTextBox(cval[0]);
                        isfalse = 1;
                    }
                }
            }
            else {
                if (cval[0] == 37 || cval[0] == 78 || cval[0] == 73) {
                    chk_var1 = true;
                    rjdt = document.getElementById("rjdate").value;
                }
            }
            crem = $("#" + div2 + chk_val[0]).val();
            str_new += cval[0] + "|" + crem + "!";
            str_caseval += cval[0] + "|" + crem + "^^";
            cr_head += cval[1];
            if (crem != "")
                cr_head += ' [' + crem + ']';
            cr_head += '<br>';
        }
    });
    cr_head += '</font></b>';
    if (document.getElementById("cldate").value == "") {
        alert("Select CauseList Date!");
        return false;
    }
    if (document.getElementById("hdate").value == "") {
        alert("Select Hearing Date!");
        return false;
    }
    if (!(chk_var)) {
        alert("Select atleast one disposal type from the list.");
        return false;
    }
    if ((rjdt == "" || rjdt == "00-00-0000") && chk_var1 == true) {
        alert("Select RJ Date");
        return false;
    }
    if (isfalse == 0) {
        var url = `${base_url}/IB/FmdController/insert_rec_an_disp`;
        var str1 = "";
        var dt = $("#cldate").val();
        var hdt = $("#hdate").val();
        var subh = $("#tmp_casenosub").val();
        var concstr = "";

        // Collecting checkbox values
        $("input[type='checkbox'][name^='conncchk']:checked").each(function () {
            concstr += $(this).val() + ",";
        });

        // Date format transformation (dd-mm-yyyy to yyyy-mm-dd)
        var dt_new = dt.split("-").reverse().join("-");
        var hdt_new = hdt.split("-").reverse().join("-");
        var rjdt_new = rjdt.split("-").reverse().join("-");

        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        // Constructing the parameters
        str1 = jcodes;
        str_new = cn + "#" + stat + "#" + str_new + "#" + subh;
        var parameters = {
            str: str_new,
            str1: str1,
            dt: dt_new,
            hdt: hdt_new,
            rjdt: rjdt_new,
            concstr: concstr
        };

        // Making the AJAX call
        $.ajax({
            type: "POST",
            url: url,
            data: {parameters,CSRF_TOKEN: CSRF_TOKEN_VALUE},
            success: function (response) {
                
                if (response) {
                    updateCSRFToken();
                    alert(response);
                } else {
                    fsubmit();
                }
            },
            error: function (xhr, status, error) {
                updateCSRFToken();
                console.error("AJAX Error: " + status + " - " + error);
                alert("An error occurred while processing your request.");
            }
        });

        close_w(cnt);
    }

}

function call_div(cn, e, cnt, subh) {
    if (cnt == 1) {
        var div1 = "chkp";
        var div2 = "hdremp";
        $('#tmp_casenop').val(cn);
        $('#pend_head').html('<font color=red>' + $('#cs' + cn).html() + '</font>');
    }
    else {
        var div1 = "chkd";
        var div2 = "hdremd";
        $('#tmp_casenod').val(cn);
        $('#tmp_casenosub').val(subh);
        $('#disp_head').html('<font color=red>Diary No. : ' + $('#diaryno').val() + '</font>');
    }
    // var elementExists = document.getElementById("caseval" + cn);
    var csval = "";
    if (document.getElementById("caseval" + cn))
        csval = document.getElementById("caseval" + cn).value;
    // alert(csval);
    var csvalspl = csval.split("^^");
    var t_val;
    var chk_val;
    $("input[type='checkbox'][name^='" + div1 + "']").each(function () {
        chk_val = $(this).val().split("|");
        int_chk = 0;
    });
    call_f1(cnt);
}

function close_w(cnt) {
    var divname = "";
    if (cnt == 1)
        divname = "newb";
    if (cnt == 2)
        divname = "newc";
    if (cnt == 3)
        divname = "newp";
    if (cnt == 4)
        divname = "newadv";
    document.getElementById(divname).style.display = 'none';
    document.getElementById('overlay').style.display = 'none';
    if (cnt == 3)
        fsubmit();
}
function adv_popup(opt) {
    $('#adv_head').html('<font color=red>' + $('#advname' + opt).html() + '</font>');
    $('#txt_adv_name').val($('#advname' + opt).html());
    if (opt == 1)
        $('#advside').val('P');
    if (opt == 2)
        $('#advside').val('R');
    if ($('#advenroll' + opt).length) {
        $('#adv_head1').html('<font color=red>' + $('#advenroll' + opt).html() + '</font>');
        var en = $('#advenroll' + opt).html();
        if (en.indexOf("/") >= 0) {
            var en1 = en.split("/");
            $('#txt_e_no').val(en1[0]);
            $('#txt_e_yr').val(en1[1]);
        }
        else {
            $('#txt_e_no').val(en);
            $('#txt_e_yr').val('');
        }
    }
    else {
        $('#adv_head1').html('');
        $('#txt_e_no').val('');
        $('#txt_e_yr').val('');
    }
    call_f1(4);
}
function call_f1(cnt) {
    var divname = "";
    if (cnt == 1) {
        divname = "newb";
        $('#' + divname).width($(window).width() - 150);
        $('#' + divname).height('80%');
        $('#newb123').height($('#newb').height() - $('#newb1').height() - 50);
    }
    if (cnt == 2) {
        divname = "newc";
        $('#' + divname).width('85%');
        $('#' + divname).height('80%');
        $('#newc123').height($('#newc').height() - $('#newc1').height() - 50);
        $('#concasediv').height($('#newc').height() - $('#newc1').height() - 50);
    }
    if (cnt == 3) {
        divname = "newp";
        $('#' + divname).width($(window).width() - 150);
        $('#' + divname).height('80%');
        $('#newp123').height($('#newp').height() - $('#newp1').height() - 50);
    }
    if (cnt == 4) {
        divname = "newadv";
        $('#' + divname).width('600px');
        $('#' + divname).height('80%');
        $('#newadv123').height($('#newadv').height() - $('#newadv1').height() - 50);
    }
    var newX = ($('#' + divname).width() / 2);
    var newY = ($('#' + divname).height() / 2);
    document.getElementById(divname).style.marginLeft = "-" + newX + "px";
    document.getElementById(divname).style.marginTop = "-27" + "%";
    document.getElementById(divname).style.display = 'block';
    document.getElementById(divname).style.zIndex = 10;
    $('#overlay').height($(window).height());
    document.getElementById('overlay').style.display = 'block';
}
function set_ele() {
    var curr_date = document.getElementById("curr_date").value;
    var d = new Date(curr_date);
    $("#hdremp21").datepicker({ dateFormat: "dd/mm/yy", numberOfMonths: 2 });
    $("#hdremp24").datepicker({ dateFormat: "dd/mm/yy", numberOfMonths: 2 });
    $("#hdremp59").datepicker({ dateFormat: "dd/mm/yy", numberOfMonths: 2 });
    $("#hdremp70").datepicker({ dateFormat: "dd/mm/yy", numberOfMonths: 2 });
    $("#hdate").datepicker({ dateFormat: "dd-mm-yy", numberOfMonths: 2, changeMonth: true, changeYear: true, maxDate: d });
    $("#cldate").datepicker({ dateFormat: "dd-mm-yy", numberOfMonths: 2, changeMonth: true, changeYear: true, maxDate: d });
    $("#rjdate").datepicker({ dateFormat: "dd-mm-yy", changeMonth: true, changeYear: true, maxDate: d });
    //$( "#datepicker" ).datepicker({  maxDate: new Date() });
    $("#rjdate").keyup(function (e) {
        if (e.keyCode == 8 || e.keyCode == 46) {
            $.datepicker._clearDate(this);
        }
    });
    $("#thdate").datepicker({ dateFormat: "dd-mm-yy" });
    $("#hdremp21").keypress(function (e) {
        e.preventDefault();
    });
    $("#hdremp24").keypress(function (e) {
        e.preventDefault();
    });
    $("#hdremp59").keypress(function (e) {
        e.preventDefault();
    });
    $("#hdremp70").keypress(function (e) {
        e.preventDefault();
    });
    $("#thdate").keypress(function (e) {
        e.preventDefault();
    });
    $("#hdate").keypress(function (e) {
        e.preventDefault();
    });
    $("#cldate").keypress(function (e) {
        e.preventDefault();
    });
    $("#rjdate").keypress(function (e) {
        e.preventDefault();
    });
    get_subheading();
    $("#txt_adv_name").autocomplete({
        source: 'get_adv_from_bar.php',
        minLength: 2,
        select: function (evt, ui) {
            $("#txt_e_no").val(ui.item.eno);
            $("#txt_e_yr").val(ui.item.eyr);
        }
    });
    $(".cls_chkp").click(function () {
        var chk_val = $(this).val().split("|");
        var isChecked = document.getElementById($(this).attr('id')).checked;
        if (isChecked) {
            if (chk_val[0] !== 21 && chk_val[0] !== 24 && chk_val[0] !== 59 && chk_val[0] !== 70)
                $("#hdremp" + chk_val[0]).attr('readonly', false);
            $("#hdremp" + chk_val[0]).css('background-color', '#fff');
            $("#hdremp" + chk_val[0]).css('border', '1px solid #ccc');
            $("#hdremp" + chk_val[0]).focus();
        }
        else {
            $("#hdremp" + chk_val[0]).attr('readonly', true);
            $("#hdremp" + chk_val[0]).css('background-color', '#F5F5F5');
            $("#hdremp" + chk_val[0]).css('border', '1px solid #ccc');
        }
    });
    $(".cls_chkd").click(function () {
        var chk_val = $(this).val().split("|");
        var isChecked = document.getElementById($(this).attr('id')).checked;
        if (isChecked) {
            $("#hdremd" + chk_val[0]).attr('readonly', false);
            $("#hdremd" + chk_val[0]).css('background-color', '#fff');
            $("#hdremd" + chk_val[0]).css('border', '1px solid #ccc');
            $("#hdremd" + chk_val[0]).focus();
        }
        else {
            $("#hdremd" + chk_val[0]).attr('readonly', true);
            $("#hdremd" + chk_val[0]).css('background-color', '#F5F5F5');
            $("#hdremd" + chk_val[0]).css('border', '1px solid #ccc');
        }
    });
    $('#linkimg').click(function () {
        if ($('#linkimg').html() == 'SHOW ALL LISTINGS') {
            $('#linkimg').html('HIDE PREVIOUS LISTINGS');
            $('.shclass').show();
        }
        else {
            $('#linkimg').html('SHOW ALL LISTINGS');
            $('.shclass').hide();
        }
    });
    $('.shclass').hide();
}

function chk_conncase() {
    var conncs_var = document.frm.conncs.value;
    if (conncs_var == 'Y')
        document.getElementById("conncasediv").style.display = "block";
    else
        document.getElementById("conncasediv").style.display = "none";
}

function get_subheading() {
    var xhr2 = getXMLHTTP();
    var jj = 0;
    var sh = $('#sh_hidden').val();
    jj = $('#mf_select').val();
    var str = "get_mf_subhead.php?mf=" + jj + "&sh=" + sh;
    xhr2.open("GET", str, true);
    xhr2.onreadystatechange = function () {
        if (xhr2.readyState == 4 && xhr2.status == 200) {
            var data = xhr2.responseText;
            var arr = data.split("|");
            if (data == "ERROR") {
                //document.getElementById('subhead_select').innerHTML=xhr2.responseText;
            }
            else {
                document.getElementById('subhead_select').innerHTML = data;
            }
        }
    }// inner function end
    xhr2.send(null);
}

function get_max_fin_m(str) {
    var ct = document.getElementById("ct").value;
    var caseno = document.getElementById("caseno").value;
    var year = document.getElementById("year").value;
    if (ct.length == 2) {
        ct = '0' + ct;
    }
    if (caseno.length == 1) {
        caseno = '0000' + caseno;
    }
    else if (caseno.length == 2) {
        caseno = '000' + caseno;
    }
    else if (caseno.length == 3) {
        caseno = '00' + caseno;
    }
    else if (caseno.length == 4) {
        caseno = '0' + caseno;
    }
    var fil_no = '01' + ct + caseno + year;
    var xmlhttp;
    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    }
    else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            document.getElementById('dv_vc').innerHTML = xmlhttp.responseText;
            var hd_co_tot = document.getElementById('hd_co_tot').value;
            var ytq = 0;
            for (var itt = 1; itt <= hd_co_tot; itt++) {
                if (document.getElementById('hd_sp_b' + itt)) {
                    if ((document.getElementById('hd_sp_b' + itt).value == '850') || (document.getElementById('hd_sp_b' + itt).value == '851')) {
                        ytq = 1;
                    }
                }
            }
            if (ytq == '0') {
                if ($("#bench").val() == "S") {
                    document.getElementById("judge_sb").style.display = "block";
                    $("#sbj").val("250");
                }
                else
                    document.getElementById("judge_sb").style.display = "none";

                if ($("#bench").val() == "D") {
                    document.getElementById("judge_db").style.display = "block";
                    $("#dbj1").val("200");
                    $("#dbj2").val("999");
                }
                else
                    document.getElementById("judge_db").style.display = "none";
            }
            else {
                if ($("#bench").val() == "S") {
                    document.getElementById("judge_sb").style.display = "block";
                    $("#sbj").val("514");
                }
                else
                    document.getElementById("judge_sb").style.display = "none";

                if ($("#bench").val() == "D") {
                    document.getElementById("judge_db").style.display = "block";
                    $("#dbj1").val("514");
                    $("#dbj2").val("999");
                }
                else
                    document.getElementById("judge_db").style.display = "none";
            }
        }
    }
    xmlhttp.open("GET", "get_maxFinal.php?str=" + str + "&fil_no=" + fil_no, true);
    xmlhttp.send(null);
}

function change_judge() {
    if (document.getElementById("listorder").value == "50" && $("#bench").val() == "S") {
        alert("Related To PMT Scam.Can't Select Single Bench");
        $("#bench").val("D");
    }
    else {
        var hd_co_tot = document.getElementById('hd_co_tot').value;
        var ytq = 0;
        for (var itt = 1; itt <= hd_co_tot; itt++) {
            if (document.getElementById('hd_sp_b' + itt)) {
                if ((document.getElementById('hd_sp_b' + itt).value == '850') || (document.getElementById('hd_sp_b' + itt).value == '851')) {
                    ytq = 1;
                }
            }
        }
        if (ytq == '0') {
            if ($("#bench").val() == "S") {
                document.getElementById("judge_sb").style.display = "block";
                $("#sbj").val("250");
            }
            else
                document.getElementById("judge_sb").style.display = "none";

            if ($("#bench").val() == "D") {
                document.getElementById("judge_db").style.display = "block";
                $("#dbj1").val("200");
                $("#dbj2").val("999");
            }
            else
                document.getElementById("judge_db").style.display = "none";
        }
        else {
            if ($("#bench").val() == "S") {
                document.getElementById("judge_sb").style.display = "block";
                $("#sbj").val("514");
            }
            else
                document.getElementById("judge_sb").style.display = "none";

            if ($("#bench").val() == "D") {
                document.getElementById("judge_db").style.display = "block";
                $("#dbj1").val("514");
                $("#dbj2").val("999");
            }
            else
                document.getElementById("judge_db").style.display = "none";
        }
    }
}
async function fsubmit() {
    updateCSRFTokenSync() 
    var diaryno, diaryyear, cstype, csno, csyr;
    var regNum = new RegExp('^[0-9]+$');

    if ($("#radioct").is(':checked')) {
        cstype = $("#case_type").val();
        csno = $("#case_number").val();
        csyr = $("#case_year").val();

        if (!regNum.test(cstype)) {
            alert("Please Select Casetype");
            $("#case_type").focus();
            return false;
        }
        if (!regNum.test(csno)) {
            alert("Please Fill Case No in Numeric");
            $("#case_number").focus();
            return false;
        }
        if (!regNum.test(csyr)) {
            alert("Please Fill Case Year in Numeric");
            $("#case_year").focus();
            return false;
        }
        if (csno == 0) {
            alert("Case No Can't be Zero");
            $("#case_number").focus();
            return false;
        }
        if (csyr == 0) {
            alert("Case Year Can't be Zero");
            $("#case_year").focus();
            return false;
        }
        /*if(cstype.length==1)
            cstype = '00'+cstype;
        else if(cstype.length==2)
            cstype = '0'+cstype;*/
    }
    else if ($("#search_type_d").is(':checked')) {
        diaryno = $("#diary_number").val();
        diaryyear = $("#diary_year").val();
        if (!regNum.test(diaryno)) {
            alert("Please Enter Diary No in Numeric");
            $("#diary_number").focus();
            return false;
        }
        if (!regNum.test(diaryyear)) {
            alert("Please Enter Diary Year in Numeric");
            $("#diary_year").focus();
            return false;
        }
        if (diaryno == 0) {
            alert("Diary No Can't be Zero");
            $("#diary_number").focus();
            return false;
        }
        if (diaryyear == 0) {
            alert("Diary Year Can't be Zero");
            $("#diary_year").focus();
            return false;
        }
    }
    else {
        alert('Please Select Any Option');
        return false;
    }
    var CSRF_TOKEN = 'CSRF_TOKEN';
    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
    $.ajax({
        type: 'GET',
        url: base_url + "/IB/FmdController/set_dispose_process",
        beforeSend: function (xhr) {
            $("#dv_res1").html('<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>');
        },
        data: { d_no: diaryno, d_yr: diaryyear, ct: cstype, cn: csno, cy: csyr }
    })
        .done(function (msg) {
           
            $("#dv_res1").html(msg);
            // get_subheading();
            //            $("#result2").html("");
            //get_subheading();
        })
        .fail(function () {
           
            alert("ERROR, Please Contact Server Room");
        });
    //    document.getElementById("rslt").innerHTML = '';
    //    var ct = document.getElementById("ct").value;
    //    var caseno = document.getElementById("caseno").value;
    //    var year = document.getElementById("year").value;
    //    var hd_ud=document.getElementById('hd_ud').value;
    //    document.getElementById("hint").innerHTML = '<table align=center><tr><td><img src="ajax-preloader.gif"/></td></tr></table>';
    //    var ajaxRequest; // The variable that makes Ajax possible!
    //    try {
    //        // Opera 8.0+, Firefox, Safari
    //        ajaxRequest = new XMLHttpRequest();
    //    } catch (e)
    //    {
    //        // Internet Explorer Browsers
    //        try {
    //            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
    //        } catch (e)
    //        {
    //            try {
    //                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
    //            } catch (e)
    //            {
    //                alert("Your browser broke!");
    //                return false;
    //            }
    //        }
    //    }

    // Create a function that will receive data sent from the server
    //    ajaxRequest.onreadystatechange = function()
    //    {
    //        if (ajaxRequest.readyState == 4) {
    //            document.getElementById("hint").innerHTML = '';
    //            $("#rslt").html(ajaxRequest.responseText);
    //            set_ele();
    //        }
    //    }
    //    var url = "rmrk_case_entry_new_da_process.php";
    //    url = url + "?ct=" + ct + "&caseno=" + caseno + "&year=" + year + "&hd_ud=" + hd_ud;
    //    ajaxRequest.open("GET", url, true);
    //    ajaxRequest.send(null);
}
var cnt_data1 = 1;
var ck_subhead = 0;
var ck_subhead_s = 0;
function getSlide() {
    var ck_ca_sb = 0;
    if (document.getElementById('hd_ssno').value != '0') {
        cnt_data1 = parseInt(document.getElementById('hd_ssno').value) + 1;
        document.getElementById('hd_ssno').value = '0';
    }
    //  var mf_select_nm = document.getElementById('mf_select').options[document.getElementById('mf_select').selectedIndex].innerHTML;
    var subhead_select_nm = document.getElementById('subhead_select').options[document.getElementById('subhead_select').selectedIndex].innerHTML;
    var mf_select = document.getElementById('mf_select').value;
    var subhead_select = document.getElementById('subhead_select').value;
    var hd_co_tot = document.getElementById('hd_co_tot').value;
    for (var i = 1; i <= hd_co_tot; i++) {
        if (document.getElementById('hd_sp_a' + i)) {
            if (document.getElementById('hd_sp_b' + i).value == '850' || document.getElementById('hd_sp_b' + i).value == '851') {
                if (document.getElementById('hd_sp_b' + i).value.trim() == '850') {
                    ck_subhead = 1;
                    ck_subhead_s = 2;
                }
                else if (document.getElementById('hd_sp_b' + i).value.trim() == '851') {
                    ck_subhead_s = 1;
                    ck_subhead = 2;
                }
            }
            if ((mf_select.trim() == document.getElementById('hd_sp_a' + i).value.trim()) &&
                (subhead_select.trim() == document.getElementById('hd_sp_b' + i).value.trim())) {
                ck_ca_sb = 1;
            }
        }
    }
    if (ck_ca_sb == 1) {
        alert("Already Selected");
    }
    else {
        var row0 = document.createElement("tr");
        row0.setAttribute('id', 'tr_uo' + cnt_data1);
        var column0 = document.createElement("td");
        var column1 = document.createElement("td");
        var hd_chk_add = document.createElement('input');
        hd_chk_add.setAttribute('type', 'checkbox');
        hd_chk_add.setAttribute('id', 'hd_chk_add' + cnt_data1);
        hd_chk_add.setAttribute('onclick', 'getDone_upd_cat(this.id);');
        var sp = document.createElement('span');
        sp.setAttribute('id', 'sp_c' + cnt_data1);
        var hd_1 = document.createElement('input');
        hd_1.setAttribute('type', 'hidden');
        hd_1.setAttribute('id', 'hd_sp_a' + cnt_data1);
        var hd_2 = document.createElement('input');
        hd_2.setAttribute('type', 'hidden');
        hd_2.setAttribute('id', 'hd_sp_b' + cnt_data1);
        column0.appendChild(hd_chk_add);
        column0.appendChild(hd_1);
        column0.appendChild(hd_2);
        column1.appendChild(sp);
        row0.appendChild(column0);
        row0.appendChild(column1);
        var tb_res = document.getElementById('tb_new');
        tb_res.appendChild(row0);
        document.getElementById('hd_chk_add' + cnt_data1).checked = true;
        document.getElementById('sp_c' + cnt_data1).innerHTML = subhead_select_nm;
        document.getElementById('hd_sp_a' + cnt_data1).value = mf_select;
        document.getElementById('hd_sp_b' + cnt_data1).value = subhead_select;
        if (subhead_select.trim() == '850' || subhead_select.trim() == '851') {
            if ($("#bench").val() == "S") {
                $("#sbj").val("514");
            }
            if ($("#bench").val() == "D") {
                $("#dbj1").val("514");
                $("#dbj2").val("999");
            }
            if (subhead_select.trim() == '850') {
                ck_subhead = 1;
                ck_subhead_s = 2;
            }
            else if (subhead_select.trim() == '851') {
                ck_subhead_s = 1;
                ck_subhead = 2;
            }
        }
        if (ck_subhead == 0 || ck_subhead_s == 0) {
            if ($("#bench").val() == "S")
                $("#sbj").val("250");
            if ($("#bench").val() == "D") {
                $("#dbj1").val("200");
                $("#dbj2").val("999");
            }
        }
        document.getElementById('hd_co_tot').value = cnt_data1;
        cnt_data1++;
    }
}

function getDone_upd_cat(str) {
    var str1 = str.split('hd_chk_add');
    var tb = 0;
    var hd_co_tot = document.getElementById('hd_co_tot').value;
    if (document.getElementById('hd_sp_b' + str1[1]).value == '850' || document.getElementById('hd_sp_b' + str1[1]).value == '851') {
        if (document.getElementById('hd_sp_b' + str1[1]).value == '850')
            ck_subhead = 0;
        else if (document.getElementById('hd_sp_b' + str1[1]).value == '851')
            ck_subhead_s = 0;
        if ($("#bench").val() == "S")
            $("#sbj").val("250");

        if ($("#bench").val() == "D") {
            $("#dbj1").val("200");
            $("#dbj2").val("999");
        }
    }
    for (var itt = 1; itt <= hd_co_tot; itt++) {
        if (document.getElementById('hd_sp_b' + itt)) {
            tb++;
        }
    }
    $("#tr_uo" + str1[1]).remove();
}

function getSlide() {
    $('#btn_coram').hide();
    var cnt_data = parseInt(document.getElementById('djcnt').value);
    var cnt_data1 = cnt_data + 1;
    var mf_select = document.getElementById('djudge').value;
    // var bench_select = document.getElementById('dbench').value;
    //    if (bench_select == 'S' && cnt_data1 > 1) {
    //        alert("Judge for Single Bench is Already Selected");
    //        return false;
    //    }
    //    if (bench_select == 'D' && cnt_data1 > 2) {
    //        alert("Judges for Division Bench is Already Selected");
    //        return false;
    //    }
    //    if (cnt_data1 > 5) {
    //        alert("Please do not select more than 5 Judges");
    //        return false;
    //    }
    var mf_select1 = mf_select.split("||")[1];
    for (var i = 1; i <= cnt_data; i++) {
        if (document.getElementById('hd_chk_jd' + i)) {
            if (document.getElementById('hd_chk_jd' + i).value == mf_select) {
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

function getDone_upd_cat(str) {
    Element.prototype.remove = function () {
        this.parentElement.removeChild(this);
    };
    NodeList.prototype.remove = HTMLCollection.prototype.remove = function () {
        for (var i = 0, len = this.length; i < len; i++) {
            if (this[i] && this[i].parentElement) {
                this[i].parentElement.removeChild(this[i]);
            }
        }
    };
    var cnt_data = parseInt(document.getElementById('djcnt').value);
    var cnt_data1 = cnt_data - 1;
    var idnum = parseInt(str.replace('hd_chk_jd', ''));
    document.getElementById("hd_chk_jd_row" + idnum).remove();
    for (var i = (idnum + 1); i <= cnt_data; i++) {
        var old_id = document.getElementById('hd_chk_jd' + i);
        old_id.id = 'hd_chk_jd' + (i - 1);
        var old_id_row = document.getElementById('hd_chk_jd_row' + i);
        old_id_row.id = 'hd_chk_jd_row' + (i - 1);
    }
    document.getElementById('djcnt').value = cnt_data1;
}

function chk_checkbox() {
    var isfound = false;
    $('input:checkbox.cls_chkd').each(function () {
        if (this.checked) {
            var chkVal = (this.checked ? $(this).val() : "");
            var chkVal1 = parseInt(chkVal.split('||')[0]);
            if (chkVal1 == 37 || chkVal1 == 78 || chkVal1 == 73)
                isfound = true;
        }
    });
    if (isfound) {
        $("#rjdate").attr('readonly', false);
        $("#rjdate").css('background-color', '');
    } else {
        $("#rjdate").attr('readonly', true);
        $("#rjdate").css('background-color', '#CCC');
    }
}



$(document).ready(function () {
    $("#search_type_d").click(function () {
        $("#diary_number").removeProp('disabled');
        $("#diary_year").removeProp('disabled');
        $("#case_type").prop('disabled', true);
        $("#case_number").prop('disabled', true);
        $("#case_year").prop('disabled', true);
        $("#case_type").val("-1");
        $("#case_number").val("");
        $("#case_year").val("");
    });

    $("#radioct").click(function () {
        $("#diary_number").prop('disabled', true);
        $("#diary_year").prop('disabled', true);
        $("#diary_number").val("");
        $("#diary_year").val("");
        $("#case_type").removeProp('disabled');
        $("#case_number").removeProp('disabled');
        $("#case_year").removeProp('disabled');
    });

    $("input[name=btnGetR]").click(function () {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        var diaryno, diaryyear, cstype, csno, csyr;
        var regNum = new RegExp('^[0-9]+$');

        if ($("#radioct").is(':checked')) {
            cstype = $("#case_type").val();
            csno = $("#case_number").val();
            csyr = $("#case_year").val();

            if (!regNum.test(cstype)) {
                alert("Please Select Casetype");
                $("#case_type").focus();
                return false;
            }
            if (!regNum.test(csno)) {
                alert("Please Fill Case No in Numeric");
                $("#case_number").focus();
                return false;
            }
            if (!regNum.test(csyr)) {
                alert("Please Fill Case Year in Numeric");
                $("#case_year").focus();
                return false;
            }
            if (csno == 0) {
                alert("Case No Can't be Zero");
                $("#case_number").focus();
                return false;
            }
            if (csyr == 0) {
                alert("Case Year Can't be Zero");
                $("#case_year").focus();
                return false;
            }
            /*if(cstype.length==1)
                cstype = '00'+cstype;
            else if(cstype.length==2)
                cstype = '0'+cstype;*/
        }
        else if ($("#search_type_d").is(':checked')) {
            diaryno = $("#diary_number").val();
            diaryyear = $("#diary_year").val();
            if (!regNum.test(diaryno)) {
                alert("Please Enter Diary No in Numeric");
                $("#diary_number").focus();
                return false;
            }
            if (!regNum.test(diaryyear)) {
                alert("Please Enter Diary Year in Numeric");
                $("#diary_year").focus();
                return false;
            }
            if (diaryno == 0) {
                alert("Diary No Can't be Zero");
                $("#diary_number").focus();
                return false;
            }
            if (diaryyear == 0) {
                alert("Diary Year Can't be Zero");
                $("#diary_year").focus();
                return false;
            }
        }
        else {
            alert('Please Select Any Option');
            return false;
        }

        $.ajax({
            type: 'GET',
            url: base_url + "/IB/FmdController/set_dispose_process",
            beforeSend: function () {
                $('#dv_res1').html('<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>');
            },
            data: { d_no: diaryno, d_yr: diaryyear, ct: cstype, cn: csno, cy: csyr}
        })
            .done(function (msg) {
               
                $("#dv_res1").html(msg);
            })
            .fail(function () {
               
                alert("ERROR, Please Contact Server Room");
            });
    });
});

function get_coram(diary_no) {
    var cl_dt = $('#cldate').val();
    //alert (diary_no);
    //alert(cl_dt);
    $('#td_coram').hide();
    $.ajax({
        url: './get_coram.php',
        cache: false,
        async: true,
        data: { cl_dt: cl_dt, diary_no: diary_no },
        beforeSend: function () {
            //$('#rs_jg').html('<table widht="100%" align="center"><tr><td><img src="../../images/load.gif"/></td></tr></table>');
        },
        type: 'POST',
        success: function (data, status) {

            if (data != '') {
                $('#jud_coram').html(data);
            }
            else {
                alert("No Coram Found");
                location.reload();
            }
        },
        error: function (xhr) {
            alert("Error: " + xhr.status + " " + xhr.statusText);
        }
    });
}