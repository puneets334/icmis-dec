var dtCh = "/";
var minYear = 1900;
var maxYear = 2100;
function remarks_input_validate(evt,rid) {
    if(rid=='NUM') {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;

        if (charCode==46 || charCode==8 || charCode==9 || charCode==37 || charCode==39 || (charCode >= 48 && charCode <= 57)) {
            return true;
        }
        return false;

    }
}
function textformate(cb) {
    var msg = 'Please Enter Numeric Value';
    if (cb == 144)
        var y = document.getElementById('hdremd' + cb).value;
    else if(cb==190 || cb==181 || cb==204 || cb==205){
        document.getElementById('hdremp' + cb+"_1").value=document.getElementById('hdremp' + cb+"_1").value.trim();
        document.getElementById('hdremp' + cb+"_2").value=document.getElementById('hdremp' + cb+"_2").value.trim();
        document.getElementById('hdremp' + cb+"_3").value=document.getElementById('hdremp' + cb+"_3").value.trim();
if(document.getElementById('hdremp' + cb+"_1").value=='') document.getElementById('hdremp' + cb+"_1").value='0';
if(document.getElementById('hdremp' + cb+"_2").value=='') document.getElementById('hdremp' + cb+"_2").value='0';
if(document.getElementById('hdremp' + cb+"_3").value=='') document.getElementById('hdremp' + cb+"_3").value='0';
        if(isNaN(document.getElementById('hdremp' + cb+"_1").value) || isNaN(document.getElementById('hdremp' + cb+"_2").value) || isNaN(document.getElementById('hdremp' + cb+"_3").value))
        {
            alert('Please Enter DAYS or WEEK or MONTH in Numbers!');
            setFocusToTextBox(cb);
            return false;
        }
        var y = "D:"+document.getElementById('hdremp' + cb+"_1").value+"W:"+document.getElementById('hdremp' + cb+"_2").value+"M:"+document.getElementById('hdremp' + cb+"_3").value;
    }
    else
        var y = document.getElementById('hdremp' + cb).value;

    x = y.split(",");
    if (cb == 72) {
        for (var i = 0; i < x.length; i++)
        {
            var iChars = "~`!#$%^&*+=-[]\\\';{}|\":<>?";
            for (var j = 0; j < x[i].length; j++)
            {
                if (iChars.indexOf(x[i].charAt(j)) != -1)
                {
                    alert("Special characters ~`!#$%^&*+=-[]\\\';{}|\":<>? \nThese are not allowed\n");
                    return false;
                }
            }
//            casenoyr = (x[i].replace(/[^0-9]/g, "").length);
//            casetyp = (x[i].replace(/[^a-zA-Z]/g, "").length);
//            ctype = x[i].replace(/[^a-zA-Z]/g, "");
//            ctyp = ctype.toUpperCase();
//            var cpa = 0;
//            switch (ctyp)
//            {
//                case 'AA':
//                case 'AC':
//                case 'AR':
//                case 'ARBA':
//                case 'ARBC':
//                case 'CA':
//                case 'CEA':
//                case 'CER':
//                case 'CESR':
//                case 'COMA':
//                case 'COMP':
//                case 'COMPA':
//                case 'CONA':
//                case 'CONC':
//                case 'CONCR':
//                case 'CONT':
//                case 'CONTR':
//                case 'CR':
//                case 'CRA':
//                case 'CRR':
//                case 'CRRE':
//                case 'CRRF':
//                case 'CRRFC':
//                case 'CS':
//                case 'EP':
//                case 'FA':
//                case 'FEMA':
//                case 'GTR':
//                case 'ITA':
//                case 'ITR':
//                case 'LPA':
//                case 'MA':
//                case 'MACE':
//                case 'MACOM':
//                case 'MACTR':
//                case 'MAIT':
//                case 'MAVAT':
//                case 'MCC':
//                case 'MCOMA':
//                case 'MCP':
//                case 'MCRC':
//                case 'MCRP':
//                case 'MP':
//                case 'MWP':
//                case 'OTA':
//                case 'RP':
//                case 'SA':
//                case 'SLP':
//                case 'STR':
//                case 'TR':
//                case 'VATA':
//                case 'WA':
//                case 'WP':
//                case 'WPS':
//                case 'WTA':
//                case 'WTR':
//                    break;
//                default:
//                {
//                    alert("Please Enter proper Case");
//                    cpa++;
//                    return false;
//                }
//            }
//            casetyp = x[i].slice(-casetyp);
//            cnyr = x[i].slice(-casenoyr);
//            var x1 = x[i].slice(-cnyr);
//            if (casenoyr <= 4) {
//                alert("Please Type Correct Case No And Year");
//                return false;
//            }
//            if (casenoyr == 5)
//                cnyr = '0000' + cnyr;
//            if (casenoyr == 6)
//                cnyr = '000' + cnyr;
//            if (casenoyr == 7)
//                cnyr = '00' + cnyr;
//            if (casenoyr == 8)
//                cnyr = '0' + cnyr;
//            var yr = cnyr.slice(-4);
//            var srvr = document.getElementById('srvr').value;
//            if (yr <= 1959)
//            {
//                alert("Please Enter Correct Year Greater then 1959");
//                return false;
//            }
//            if (yr > srvr) {
//                alert("Please Enter Correct Year Less  then " + srvr);
//                return false;
//            }
        }
    }
    if (cb == 68 || cb == 23 || cb == 53 || cb == 54 || cb == 25 || cb == 122 || cb == 123 || cb == 133 || cb == 144 || cb == 149) {
        switch (cb)
        {
            case 68:
                msg = 'Please enter Fixed Month no. (i.e. 1 for January, 12 for December) on which case should be listed.';
                break;
            case 23:
                msg = 'Please enter no. of weeks after which case should be listed.';
                break;
            case 53:
                msg = 'Please enter no. of days after which case should be listed.';
                break;
            case 54:
                msg = 'Please enter no. of months after which case should be listed.';
                break;
            case 25:
                msg = 'Please enter no. of days within default should be removed.';
                break;frm
            case 122:
                msg = 'Please enter no. of weeks within default should be removed.';
                break;
            case 123:
                msg = 'Please enter no. of months within default should be removed.';
                break;
            case 133:
                msg = 'Please enter no. of days.';
                break;
            case 144:
                msg = 'Please enter Amount in Numbers';
                break;
            case 149:
                msg = 'Please enter no. of days for fresh PF.';
                break;
        }
        if (y == '' || isNaN(y))
        {
            alert(msg);
            setFocusToTextBox(cb);
            return false;
        }
        if (cb == 53 || cb == 25 || cb == 149)
        {
            if (y >= 31)
            {
                alert('Please Enter Numeric Value Between 1 TO 31 Which Is No Of Days In A Month');
                setFocusToTextBox(cb);
                return false;
            }
        }
        if (cb == 23 || cb == 122)
        {
            if (y >= 54)
            {
                alert('Please Enter Numeric Value Between 1 TO 52 Which Is Week No Of The Year');
                setFocusToTextBox(cb);
                return false;
            }
        }
        if (cb == 68 || cb == 123 || cb == 54)
        {
            if (y > 12 || y == 0)
            {
                alert('Please Enter Numeric Value Between 1 TO 12 Which Is Month Of The Year');
                setFocusToTextBox(cb);
                return false;
            }
        }
    }
    var chk_ia = false;
    if (cb == 22 || cb == 26 || cb == 95 || cb == 142)
    {
        $("input[type='checkbox'][id^='" + "hdremp" + cb + "_divcb" + "']").each(function () {
            var isChecked = document.getElementById($(this).attr('id')).checked;
            if (isChecked)
            {
                chk_ia = true;
            }
        });
        if (!(chk_ia))
        {
            alert('Please Select IA from the list');
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

async function save_rec1(cn, cldt, court,cnt) 
{
    await updateCSRFTokenSync();
    var CSRF_TOKEN = 'CSRF_TOKEN';
    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

    var selected_paps = "";
    var paps_spancn = "";
    var papscn = "";
    var concstr='';  
    if (cnt == 1)
    {
        var div3="chkcnp";
    }
    else
    {
         var div3="chkcnd";
    }
        if($('#conc'+cn).length){
            var data = $('#conc'+cn).val();
            data = data.replace(/,\s*$/, "");
            if (data != "") {
                $("input[type='checkbox'][name^='"+div3+"']").each(function () {
                    if($(this).is(':checked')){
                    concstr+= $(this).val()+',';  
                    }

                });    
            }
        }
    $("input[type='checkbox'][name^='chks']").each(function () {
        var isChecked = document.getElementById($(this).attr('id')).checked;
        if (isChecked)
        {
            selected_paps += $(this).val() + "|";
            var chk_val = $("#paps").val().split("#");
            for (i = 0; i < (chk_val.length - 1); i++)
            {
                var chk_val1 = chk_val[i].split("|");
                t_val = chk_val1[0];
                if (t_val == $(this).val())
                {
                    paps_spancn += chk_val1[1] + ", ";
                    papscn += chk_val1[0] + "^" + chk_val1[1] + "|";
                }
            }
        }
    });
    var mh=document.getElementById("mainhead" + cn).innerHTML;
    var clno=document.getElementById("brd" + cn).value;
    var url = base_url + "/Court/CourtCauseListController/insert_rec_paps";
    
    $.ajax({
        type: "POST",
        url: url,
        data: {cn: cn,
            cldt: cldt,
            court: court,
            paps: selected_paps,
            mh: mh,
            clno:clno,
            concstr: concstr,
            CSRF_TOKEN: CSRF_TOKEN_VALUE
        },
        success: function (msg) {
            updateCSRFToken();
            if (msg == '')
            {
                if (paps_spancn != "")
                    paps_spancn = paps_spancn.slice(0, -2);
                document.getElementById("paps" + cn).innerHTML = papscn;
                document.getElementById("paps_span" + cn).innerHTML = paps_spancn;
                $("input.pdbutton").attr("disabled", false);
            }
            else
            {
                alert("Please RELOAD CAUSELIST BY CLICKING ON SUBMIT BUTTON");
                $("input.pdbutton").attr("disabled", false);
                // fsubmit();
            }
            $("input[name=btnGetR]").click();
        },
        error: function () {
            updateCSRFToken();
            alert("ERROR");
        }
    });


//    var http = new getXMLHttpRequestObject();
//   // var ucode = document.getElementById('hd_ud').value;
//    var parameters = "cn=" + cn;
//    parameters += "&cldt=" + cldt;
//    parameters += "&court=" + court;
////    parameters += "&ucode=" + ucode;
//    parameters += "&paps=" + selected_paps;
//    parameters += "&mh=" + mh;
//    parameters += "&clno=" + clno;
//    http.open("POST", url, true);
//Send the proper header information along with the request
//    http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
//    http.setRequestHeader("Content-length", parameters.length);
//    http.setRequestHeader("Connection", "close");
//    http.onreadystatechange = function () {//Handler function for call back on state change.
//        if (http.readyState == 4) {
//            var data = http.responseText;
//            if (data != "") {
//                alert("Please RELOAD CAUSELIST BY CLICKING ON SUBMIT BUTTON");
//            $("input.pdbutton").attr("disabled", false);
//            }
//            else
//            {
//                if (paps_spancn != "")
//                    paps_spancn = paps_spancn.slice(0, -2);
//                document.getElementById("paps" + cn).innerHTML = papscn;
//                document.getElementById("paps_span" + cn).innerHTML = paps_spancn;
//            $("input.pdbutton").attr("disabled", false);
//            }
//        }
//    };
//    http.send(parameters);
}

//function to clear radio button
/*function clearAllRadios() {
    var radList = document.getElementsByName('nextCourt');
    for (var i = 0; i < radList.length; i++) {
        if(radList[i].checked) radList[i].checked = false;
    }
}*/


function save_rec(cnt)
{

    var cn = "";
    var stat = "";
    var cr_head = "";
    var subh="";
    var concstr='';        
    var statusSide=cnt;
    var options='';
    var creg='';
    if (cnt == 1)
    {
        /*if(checkNextCourt()==false)
        exit(0);*/
        var div1 = "chkp";
        var div2 = "hdremp";
        cn = $('#tmp_casenop').val();
        stat = "P";
        cr_head = '<b><font color="blue">';
        var div3="chkcnp";
      //  nextCourt=$('input[name=nextCourt]:checked').val();
        //alert("hello");
       // alert("nextcrt"+nextCourt);
        /*if(nextCourt===undefined)
            nextCourt='J';*/
    }
    else
    {
        var div1 = "chkd";
        var div2 = "hdremd";
        cn = $('#tmp_casenod').val();
        subh=$('#tmp_casenosub').val();
        stat = "D";
        cr_head = '<b><font color="red">';
         var div3="chkcnd";
    }
    var chk_val;
    var cval = "";
    var crem = "";
    var str_new = "";
    var str_caseval = "";
    var isfalse = 0;
    if($('#connected').length){
        var data = $('#connected').val();
data = data.replace(/,\s*$/, "");

if (data != "") {
    $("input[type='checkbox'][name^='"+div3+"']").each(function () {
        if($(this).is(':checked')){
         concstr+= $(this).val()+',';  
        }

                            });    
}
}
var check_NMD=0;
var check_NMD_var='';
    $("input[type='checkbox'][name^='" + div1 + "']").each(function () {
        var isChecked = document.getElementById($(this).attr('id')).checked;
        if (isChecked)
        {
            check_NMD++;
            chk_val = $(this).val().split("|");
            cval = $("#" + div1 + chk_val[0]).val().split("|");
            if (cnt == 1)
            {
                if (textformate(cval[0]) == false)
                {
                    isfalse = 1;
                }
                if (cval[0] == 24 || cval[0] == 21 || cval[0] == 70 || cval[0] == 59 || cval[0] == 131 || cval[0] == 153 || cval[0] == 91)
                {
                    if ($("#" + div2 + cval[0]).val() == '')
                    {
                        alert('Please Enter Date');
                        setFocusToTextBox(cval[0]);
                        isfalse = 1;
                    }

                }
                if (cval[0] == 149){
                    if ($("#" + div2 + cval[0]).val() == '')
                    {
                        alert('Please Enter Fresh PF (in days)');
                        //setFocusToTextBox(cval[0]);
                        isfalse = 1;
                    }
                    else
                    {    var cntr=0;
                        $("input[type='checkbox'][name^='tparty']").each(function () {
                            var isChecked = document.getElementById($(this).attr('id')).checked;
                            if (isChecked)
                            {
                                cntr++;
                            }
                        });
                        if(cntr==0){
                            alert('Please Select Parties for Fresh PF');
                            setFocusToTextBox(cval[0]);
                            isfalse = 1;
                        }else{
                            var cntr1=0;
                            $("input[type='checkbox'][name^='ord']").each(function () {
                                var isChecked = document.getElementById($(this).attr('id')).checked;
                                if (isChecked)
                                {
                                    cntr1++;
                                }
                            });
                            $("input[type='checkbox'][name^='reg']").each(function () {
                                var isChecked = document.getElementById($(this).attr('id')).checked;
                                if (isChecked)
                                {
                                    cntr1++;
                                }
                            });
                            $("input[type='checkbox'][name^='hum']").each(function () {
                                var isChecked = document.getElementById($(this).attr('id')).checked;
                                if (isChecked)
                                {
                                    cntr1++;
                                }
                            });
                            if(cntr1==0){
                                alert('Please Select PF Type for Fresh PF');
                                setFocusToTextBox(cval[0]);
                                isfalse = 1;
                            }
                        }
                    }

                }
                if (cval[0] == 22 || cval[0] == 142 || cval[0] == 26 || cval[0] == 95 || cval[0] == 91)
                {
                    if(concstr!=''){
                        if(cval[0] == 91)
                        {
                          alert('Appearance before Registry is only set in one case at a time. Please deselect connected cases.');
                                setFocusToTextBox(cval[0]);
                                isfalse = 1;   
                        }
                        else{
                           alert('Remarks in IA is only set in one case at a time. Please deselect connected cases.');
                                setFocusToTextBox(cval[0]);
                                isfalse = 1;  
                        }
                    }
                    
                }

                //Added by preeti on 29.05.2019 so that user cannot select list on fixed month and week commencing at a time in any matter
                options=options+","+cval[0];
                //end
                
if(cval[0] == 180)
{
check_NMD_var='yes';                          
}          
                
            }
            
                  if(chk_val[0]==190 || chk_val[0]==181 || chk_val[0]==204 || chk_val[0]==205){
                  var t_d=$("#" + div2 + chk_val[0]+"_1").val();
                  var t_m=$("#" + div2 + chk_val[0]+"_2").val();
                  var t_y=$("#" + div2 + chk_val[0]+"_3").val();
                  if(t_d=='') t_d=0;
                  if(t_m=='') t_m=0;
                  if(t_y=='') t_y=0;
            crem = "D:"+t_d+",W:"+t_m+",M:"+t_y;
              }else{
            crem = $("#" + div2 + chk_val[0]).val();
              }
            str_new += cval[0] + "|" + crem + "!";
            str_caseval += cval[0] + "|" + crem + "^^";
            cr_head += cval[1];
            if (crem !== "")
                cr_head += ' [' + crem + ']';
            cr_head += '<br>';
        }
    });
/*
    if(check_NMD_var=='yes' && check_NMD==1){
   alert('Please select other remark alongwith List on NMD.');
                                isfalse = 1;          
    }
*/
//        if(str_new=="")
//    {
//       isfalse=1;
//       if(stat=='D')
//           var status1='Disposal';
//              if(stat=='P')
//           var status1='Pending';
//       alert("Select atleast one "+status1+" Case Remark");
//    }
    cr_head += '</font></b>';
//Added by preeti on 29.05.2019 so that user cannot select list on fixed month and week commencing at a time in any matter
    options=options.substring(1);
    options1=options.split(",");
    for (var i = 0; i < options1.length; i++) {
        if ("68" == options1[i]) {
            for (var j = i + 1; j < options1.length; j++) {
                if("21"==options1[j])
                {
                    alert("Please select only one option from List on fixed month and Week Commencing.");
                    isfalse=1;
                }
            }}
    }
    //end

    if (isfalse == 0)
    {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var csrf = $("input[name='CSRF_TOKEN']").val();
        
        $("input.pdbutton").attr("disabled", true);
        var url = base_url + "/Court/CourtCauseListController/insert_rec_an";
        var http = new getXMLHttpRequestObject();
        var str1 = "";
        var old_new='H';
        var dt = document.getElementById("dtd").value;
        var hdt = document.getElementById("hdate").value;
//        var ucode = document.getElementById('hd_ud').value;
//        var uip = document.getElementById('hd_ipadd').value;
//        var umac = document.getElementById('hd_macadd').value;
        var jc = document.getElementById("jcodes" + cn).innerHTML;
        var subh = document.getElementById("tmp_casenosub").value;
       // var nextCourt=nextCourt;
        var statusSide=statusSide;
        var dt1 = dt.split("-");
        var dt_new = dt1[2] + "-" + dt1[1] + "-" + dt1[0];
        var hdt1 = hdt.split("-");
        var hdt_new = hdt1[2] + "-" + hdt1[1] + "-" + hdt1[0];

        
        str1 = document.getElementById("jcodes" + cn).innerHTML + "|" + document.getElementById("mainhead" + cn).innerHTML + "|" + document.getElementById("brd" + cn).value + "|" + subh;
        str_new = cn + "#" + stat + "#" + str_new;

        $.ajax({
            type: "POST",
            url: url,
            data: {CSRF_TOKEN: csrf,
                str: str_new,
                str1: str1,
                dt: dt_new,
                hdt: hdt_new,
                old_new: old_new,
              //  nextCourt:nextCourt,
                statusSide:statusSide,
                concstr: concstr
            },
            success: function (msg) {
                
                
                if (msg == '')
                {
                    get_reg_no(cn,cr_head,str_caseval,concstr,dt_new,jc,cnt);
                  
                }
                else
                {
                    updateCSRFToken();
                    alert(msg);
                    $("input.pdbutton").attr("disabled", false);
                    // fsubmit();
                }
               // updateCSRFToken();
                $("input[name=btnGetR]").click();
            },
            error: function () {
                updateCSRFToken();
                $("input.pdbutton").attr("disabled", false);
                alert("ERROR");
            }
        });

 
        close_w(cnt);
    }
}

async function get_reg_no(cn,cr_head,str_caseval,concstr,dt_new,jc,cnt)
{
    await updateCSRFTokenSync();
    var CSRF_TOKEN = 'CSRF_TOKEN';
    var csrf = $("input[name='CSRF_TOKEN']").val();
    await $.ajax({
        type:"POST",
        url:base_url + "/Court/CourtCauseListController/get_reg_no",
        data:{CSRF_TOKEN: csrf,dno:cn},
        success:function(reg) {
             
                reg_data=reg.split('#');
                creg = reg_data[1];
                var display=cr_head+creg;
                document.getElementById("caseval" + cn).value = str_caseval;
                document.getElementById("cr_span" + cn).innerHTML = display;
                //document.getElementById("creg" + cn).innerHTML = creg;
                if(concstr!=''){
                    concstr = concstr.replace(/,\s*$/, "");
                    var conc_t = concstr.split(",");
                    for(i = 0; i < conc_t.length; i++)
                        {
                            get_reg_no_loop(conc_t[i],cr_head,str_caseval);                                           

                        }//end of for loop
                }
                //save_rec1(cn, dt_new, jc,cnt);
        }
    });

    save_rec1(cn, dt_new, jc,cnt);
    //clearAllRadios();
}

async function get_reg_no_loop(conc_t,cr_head,str_caseval)
{
    await updateCSRFTokenSync();
    var CSRF_TOKEN = 'CSRF_TOKEN';
    var csrf = $("input[name='CSRF_TOKEN']").val();
        $.ajax({
            type: "POST",
            url: base_url + "/Court/CourtCauseListController/get_reg_no",
            data: {CSRF_TOKEN: csrf,dno: conc_t},
            success: function (reg) {                
                reg_data=reg.split('#');
                creg = reg_data[1];
                var display = cr_head + creg;
                document.getElementById("caseval" + reg_data[0]).value = str_caseval;
                document.getElementById("cr_span" + reg_data[0]).innerHTML = display;
            }

        });
}



function save_parties1()
{
    var str = "";
    var checked149 = document.getElementById('chkp149').checked;
    if (checked149)
        str += "YES#";
    else
        str += "NO#";
    $("input[type='checkbox'][name^='tparty']").each(function () {
        var isChecked = document.getElementById($(this).attr('id')).checked;
        if (isChecked)
        {
            var strnew = $(this).val().split("|");
//str += strnew[1] + "|" + strnew[2] + "^^";
            str += strnew[1] + "|" + strnew[2];

            $("input[type='checkbox'][name^='ord"+strnew[1]+strnew[2]+"']").each(function () {
                var isChecked = document.getElementById($(this).attr('id')).checked;
                if (isChecked)
                {
                    str += "|" + $(this).val();
                }
                else
                {
                    str += "|";
                }
            });

            $("input[type='checkbox'][name^='reg"+strnew[1]+strnew[2]+"']").each(function () {
                var isChecked = document.getElementById($(this).attr('id')).checked;
                if (isChecked)
                {
                    str += "|" + $(this).val();
                }
                else
                {
                    str += "|";
                }
            });

            $("input[type='checkbox'][name^='hum"+strnew[1]+strnew[2]+"']").each(function () {
                var isChecked = document.getElementById($(this).attr('id')).checked;
                if (isChecked)
                {
                    str += "|" + $(this).val();
                }
                else
                {
                    str += "|";
                }
            });
            str += "^^";
        }
    });


    if(str != "")
    {
        var url = base_url + "/Court/CourtCauseListController/insert_parties1.php";
        var http = new getXMLHttpRequestObject();
        var cn = $('#tmp_casenop').val();
        var dt = document.getElementById("dtd").value;
        var dt1 = dt.split("-");
        var dt_new = dt1[2] + "-" + dt1[1] + "-" + dt1[0];
        var parameters = "str=" + str;
        parameters += "&dt=" + dt_new;
        parameters += "&cn=" + cn;

        http.open("POST", url, true);
//Send the proper header information along with the request
//        http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
//        http.setRequestHeader("Content-length", parameters.length);
//        http.setRequestHeader("Connection", "close");
        http.onreadystatechange = function () {//Handler function for call back on state change.
            if (http.readyState == 4) {
                var data = http.responseText;
                if(data==""){
//                alert("SUCCESSFULLY DONE");
//                close_party();
                }
                else
                    alert(data);
            }
        };
        http.send(parameters);
    }
}
function save_parties()
{
    var str = "";
    $("input[type='checkbox'][name^='party']").each(function () {
        var isChecked = document.getElementById($(this).attr('id')).checked;
        if (isChecked)
        {
            var strnew = $(this).val().split("|");

            str += strnew[1] + "|" + strnew[2] + "^^";
        }
    });

    if(str != "")
    {
        var url = base_url + "/Court/CourtCauseListController/insert_parties";
        var http = new getXMLHttpRequestObject();
        var cn = $('#tmp_casenop').val();
        var dt = document.getElementById("dtd").value;
        var dt1 = dt.split("-");
        var dt_new = dt1[2] + "-" + dt1[1] + "-" + dt1[0];
        var parameters = "str=" + str;
        parameters += "&dt=" + dt_new;
        parameters += "&cn=" + cn;

        http.open("POST", url, true);
//Send the proper header information along with the request
//        http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
//        http.setRequestHeader("Content-length", parameters.length);
//        http.setRequestHeader("Connection", "close");
        http.onreadystatechange = function () {//Handler function for call back on state change.
            if (http.readyState == 4) {
                var data = http.responseText;
                if(data==""){
                    alert("SUCCESSFULLY DONE");
                    close_party();
                }
                else
                    alert(data);
            }
        };
        http.send(parameters);
    }
}

function isInteger(s) {
    var i;
    for (i = 0; i < s.length; i++)
    {
        var c = s.charAt(i);
        if (((c < "0") || (c > "9")))
            return false;
    }
    return true;
}

function call_f1(cnt)
{
    var jj = 0;
    if (document.frm.mf[0].checked)
        jj = document.frm.mf[0].value;
    if (document.frm.mf[1].checked)
        jj = document.frm.mf[1].value;
    if (document.frm.mf[2].checked)
        jj = document.frm.mf[2].value;
    if (document.frm.mf[3].checked)
        jj = document.frm.mf[3].value;
    var divname = "";
    if (cnt == 1)
    {
        divname = "newb";
        $('#' + divname).width($(window).width() - 150);
        //$('#' + divname).height($(window).height() - 50);
        $('#' + divname).height(screen.height - 150);
        if($('#newb111').html()=='')
            $('#newb111').height(0);
        else
            $('#newb111').height(100);
         
        //$('#newb123').height($('#newb').height() - $('#newb1').height() - $('#newb111').height() - 2000);

        $('#newb123').height($('#newb').height() -  150); 
        $('#paps123p').append($('#newa123'));

        if(jj==2)
        {
            $('#chkp150').closest("tr").show();
            $('#chkp151').closest("tr").show();
        }
        else
        {
            $('#chkp150').closest("tr").hide();
            $('#chkp151').closest("tr").hide();
        }
    }
    else if (cnt == 2)
    {
        divname = "newc";
        $('#' + divname).width($(window).width() - 150);
        //$('#' + divname).height($(window).height() - 50);
        $('#' + divname).height(screen.height - 150);

        if($('#newc111').html()=='')
            $('#newc111').height(0);
        else
            $('#newc111').height(100);  

        //$('#newc123').height($('#newc').height() - $('#newc1').height() - $('#newc111').height() - 100);
        $('#newc123').height($('#newc').height() - 150);
        $('#paps123d').append($('#newa123'));
    }
    else
    {
        divname = "newa";
        $('#' + divname).width($(window).width() - 250);
        $('#' + divname).height($(window).height() - 250);
        $('#newa123').height($('#newa').height() - $('#newa1').height() - 100);
    }
    var newX = ($('#' + divname).width() / 2);
    var newY = ($('#' + divname).height() / 2);
    document.getElementById(divname).style.marginLeft = "-" + newX + "px";
   // document.getElementById(divname).style.marginTop = "-" + newY + "px";
    document.getElementById(divname).style.display = 'block';
    document.getElementById(divname).style.zIndex = 10;
}

function call_mg()
{
    $('#intabdiv3').toggle();
    $('#oralMentioningEntryDiv').hide();

}
function call_oral_mentioning()
{
    $('#intabdiv3').hide();
    $('#oralMentioningEntryDiv').toggle();
}

function close_w(cnt)
{
    var divname = "";
    if (cnt == 1)
        divname = "newb";
    if (cnt == 2)
        divname = "newc";
    if (cnt == 3)
        divname = "newa";
    document.getElementById(divname).style.display = 'none';
    $('#newa').append($('#newa123'));
    if (cnt == 1)
        check_parties();
}
async function check_parties(){
    await updateCSRFTokenSync();
    var CSRF_TOKEN = 'CSRF_TOKEN';
        var csrf = $("input[name='CSRF_TOKEN']").val();
    var url = base_url + "/Court/CourtCauseListController/check_parties";
    var http = new getXMLHttpRequestObject();
    var cn = $('#tmp_casenop').val();
    var dt = document.getElementById("dtd").value;
    var dt1 = dt.split("-");
    var dt_new = dt1[2] + "-" + dt1[1] + "-" + dt1[0];
    var parameters = "dt=" + dt_new;
    parameters += "&cn=" + cn+'&'+CSRF_TOKEN+'='+csrf;

    http.open("POST", url, true);
//Send the proper header information along with the request
//        http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
//        http.setRequestHeader("Content-length", parameters.length);
//        http.setRequestHeader("Connection", "close");
    http.onreadystatechange = function () {//Handler function for call back on state change.
        if (http.readyState == 4) {
            var data = http.responseText;
            // alert(data);
        }

    };
    http.send(parameters);
}

function fnSetDateFormat(oDateFormat)
{
    oDateFormat['FullYear'];		//Example = 2007
    oDateFormat['Year'];			//Example = 07
    oDateFormat['FullMonthName'];	//Example = January
    oDateFormat['MonthName'];		//Example = Jan
    oDateFormat['Month'];			//Example = 01
    oDateFormat['Date'];			//Example = 01
    oDateFormat['FullDay'];			//Example = Sunday
    oDateFormat['Day'];				//Example = Sun
    oDateFormat['Hours'];			//Example = 01
    oDateFormat['Minutes'];			//Example = 01
    oDateFormat['Seconds'];			//Example = 01
    var sDateString;
    //Example = 01/01/00  dd/mm/yy
    //sDateString = oDateFormat['Date'] +"/"+ oDateFormat['Month'] +"/"+ oDateFormat['Year'];
    //Example = 01/01/0000  dd/mm/yyyy
    sDateString = oDateFormat['Date'] + "/" + oDateFormat['Month'] + "/" + oDateFormat['FullYear'];
    //Example = 0000-01-01 yyyy/mm/dd
    //sDateString = oDateFormat['FullYear'] +"-"+ oDateFormat['Month'] +"-"+ oDateFormat['Date'];
    //Example = Jan-01-0000 Mmm/dd/yyyy
    //sDateString = oDateFormat['MonthName'] +"-"+ oDateFormat['Date'] +"-"+ oDateFormat['FullYear'];
    return sDateString;
}

function closeW()
{
    $('#fade').click();
}

function load_msg() {
    document.getElementById("mrq").innerHTML = document.getElementById("msg1").value;
}

$(document).ready(function () {
    // get_MF();
});

$(function () {
    var curr_date = document.getElementById("curr_date").value;
    var date = new Date(curr_date);
    date.setDate(date.getDate() + 1);
    $("#hdremp21").addClass( "dtp" );
    $("#hdremp24").addClass( "dtp" );
//    $("#hdremp180").addClass( "dtp" );    
    $("#hdremp59").addClass( "dtp" );
    $("#hdremp91").addClass( "dtp" );
    $("#hdremp131").addClass( "dtp" );
    $("#hdremp70").addClass( "dtp" );
    $("#hdremp153").addClass( "dtp" );
    $("#hdate").addClass( "dtp" );

//    $("#hdremp21").datepicker({dateFormat: "dd/mm/yy", numberOfMonths: 2, minDate: date, beforeShowDay: get_holidays});
//    $("#hdremp24").datepicker({dateFormat: "dd/mm/yy", numberOfMonths: 2, minDate: new Date(curr_date), beforeShowDay: get_holidays});
//    $("#hdremp59").datepicker({dateFormat: "dd/mm/yy", numberOfMonths: 2, minDate: date, beforeShowDay: get_holidays});
//    $("#hdremp91").datepicker({dateFormat: "dd/mm/yy", numberOfMonths: 2, minDate: date, beforeShowDay: get_holidays});
//    $("#hdremp131").datepicker({dateFormat: "dd/mm/yy", numberOfMonths: 2, minDate: date, beforeShowDay: get_holidays});
//    $("#hdremp70").datepicker({dateFormat: "dd/mm/yy", numberOfMonths: 2, minDate: date, beforeShowDay: get_holidays});
//    $("#hdremp153").datepicker({dateFormat: "dd/mm/yy", numberOfMonths: 2, beforeShowDay: get_holidays});
//    $("#hdate").datepicker({dateFormat: "dd-mm-yy", numberOfMonths: 2, maxDate: new Date(curr_date), beforeShowDay: get_holidays});
//    $("#hdremp21").keypress(function (e) {
//        e.preventDefault();
//    });
//    $("#hdremp24").keypress(function (e) {
//        e.preventDefault();
//    });
//    $("#hdremp59").keypress(function (e) {
//        e.preventDefault();
//    });
//        $("#hdremp91").keypress(function (e) {
//        e.preventDefault();
//    });
//   // $("#hdremp129").keypress(function (e) {
//   //     e.preventDefault();
//   // });
//    $("#hdremp131").keypress(function (e) {
//        e.preventDefault();
//    });
//        $("#hdremp153").keypress(function (e) {
//        e.preventDefault();
//    });
//    $("#hdremp70").keypress(function (e) {
//        e.preventDefault();
//    });
//    $("#hdate").keypress(function (e) {
//        e.preventDefault();
//    });

    $(".cls_chkp").click(function () {
        var chk_val = $(this).val().split("|");
        var isChecked = document.getElementById($(this).attr('id')).checked;
        if (isChecked)
        {
            if (chk_val[0] == 22 || chk_val[0] == 26 || chk_val[0] == 95 || chk_val[0] == 142)
            {
                $("input[type='checkbox'][id^='" + "hdremp" + chk_val[0] + "_divcb" + "']").each(function () {
                    $(this).attr('disabled', false);
                    $("#hdremp" + chk_val[0]).attr('readonly', false);
                });
            }
            else
            {

                if (chk_val[0] !== 21 && chk_val[0] !== 24 && chk_val[0] !== 59 && chk_val[0] !== 131 && chk_val[0] !== 70 && chk_val[0] !== 153 && chk_val[0] !== 91)
if(chk_val[0] == 190 || chk_val[0] == 181 || chk_val[0] == 204 || chk_val[0] == 205 ){
                 $("#hdremp" + chk_val[0]+"_1").attr('readonly', false);
            $("#hdremp" + chk_val[0]+"_1").css('background-color', '#fff');
            $("#hdremp" + chk_val[0]+"_1").css('border', '1px solid #ccc');   
                             $("#hdremp" + chk_val[0]+"_2").attr('readonly', false);
            $("#hdremp" + chk_val[0]+"_2").css('background-color', '#fff');
            $("#hdremp" + chk_val[0]+"_2").css('border', '1px solid #ccc');   
                             $("#hdremp" + chk_val[0]+"_3").attr('readonly', false);
            $("#hdremp" + chk_val[0]+"_3").css('background-color', '#fff');
            $("#hdremp" + chk_val[0]+"_3").css('border', '1px solid #ccc');   
}
else if(chk_val[0] == 70 || chk_val[0] == 24 || chk_val[0] == 21){
    $("#hdremp" + chk_val[0]).attr('readonly', true);
}
else{                
                $("#hdremp" + chk_val[0]).attr('readonly', false);
                $("#hdremp" + chk_val[0]).css('background-color', '#fff');
                $("#hdremp" + chk_val[0]).css('border', '1px solid #ccc');
                
                if (chk_val[0] !== 149)
                    $("#hdremp" + chk_val[0]).focus();
                if(chk_val[0] == 91){
                    $("#partybutton").attr('disabled', false);
                }
                if(chk_val[0] == 149){
                    make_party_div1();
                    $("#partybutton1").attr('disabled', false);
                }
                }
            }
        }
        else
        {
            if (chk_val[0] == 22 || chk_val[0] == 26 || chk_val[0] == 95 || chk_val[0] == 142)
            {
                $("input[type='checkbox'][id^='" + "hdremp" + chk_val[0] + "_divcb" + "']").each(function () {
                    $(this).attr('disabled', true);
                    $("#hdremp" + chk_val[0]).attr('readonly', true);
                });
            }
            else
            {
                if(chk_val[0] == 190 || chk_val[0] == 181 || chk_val[0] == 204 || chk_val[0] == 205 ){
                 $("#hdremp" + chk_val[0]+"_1").attr('readonly', true);
            $("#hdremp" + chk_val[0]+"_1").css('background-color', '#F5F5F5');
            $("#hdremp" + chk_val[0]+"_1").css('border', '1px solid #ccc');   
                             $("#hdremp" + chk_val[0]+"_2").attr('readonly', true);
            $("#hdremp" + chk_val[0]+"_2").css('background-color', '#F5F5F5');
            $("#hdremp" + chk_val[0]+"_2").css('border', '1px solid #ccc');   
                             $("#hdremp" + chk_val[0]+"_3").attr('readonly', true);
            $("#hdremp" + chk_val[0]+"_3").css('background-color', '#F5F5F5');
            $("#hdremp" + chk_val[0]+"_3").css('border', '1px solid #ccc');   
}
else{
                $("#hdremp" + chk_val[0]).attr('readonly', true);
                $("#hdremp" + chk_val[0]).css('background-color', '#F5F5F5');
                $("#hdremp" + chk_val[0]).css('border', '1px solid #ccc');
                }
                if(chk_val[0] == 91){
                    $("#partybutton").attr('disabled', true);
                }
                if(chk_val[0] == 149){
                    $("#partybutton1").attr('disabled', true);
                }
            }
        }
    });

    $(".cls_chkd").click(function () {
        var chk_val = $(this).val().split("|");
        var isChecked = document.getElementById($(this).attr('id')).checked;
        if (isChecked)
        {
            $("#hdremd" + chk_val[0]).attr('readonly', false);
            $("#hdremd" + chk_val[0]).css('background-color', '#fff');
            $("#hdremd" + chk_val[0]).css('border', '1px solid #ccc');
            $("#hdremd" + chk_val[0]).focus();
        }
        else
        {
            $("#hdremd" + chk_val[0]).attr('readonly', true);
            $("#hdremd" + chk_val[0]).css('background-color', '#F5F5F5');
            $("#hdremd" + chk_val[0]).css('border', '1px solid #ccc');
        }
    });
});

//<!-- Function for Creating XMLHTTP Request --->
function getXMLHTTP()
{ //fuction to return the xml http object
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

function getXMLHttpRequestObject()
{
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
//<!-- ***End of Creating Object --->

function get_MF()
{
    var xhr2 = getXMLHTTP();
    var jj = 0;
    if (document.frm.mf[0].checked)
        jj = document.frm.mf[0].value;
    if (document.frm.mf[1].checked)
        jj = document.frm.mf[1].value;
    if (document.frm.mf[2].checked)
        jj = document.frm.mf[2].value;
    if (document.frm.mf[3].checked)
        jj = document.frm.mf[3].value;
    var str = base_url + "/Court/CourtCauseListController/get_mf.php?mf=" + jj;
    xhr2.open("GET", str, true);
    xhr2.onreadystatechange = function ()
    {
        if (xhr2.readyState == 4 && xhr2.status == 200)
        {
            var data = xhr2.responseText;
            var arr = data.split("|");
            if (arr[0] == "ERROR")
            {
                document.getElementById('mf_box').innerHTML = xhr2.responseText;
            }
            else
            {
                document.getElementById('mf_box').innerHTML = arr[0];
                document.getElementById('mf1').value = "<?= $sh; ?>";
            }
        }
    }// inner function end
    xhr2.send(null);
}

function save_r1(action)
{
    var xhr2 = getXMLHTTP();
    var msg1 = document.getElementById("msg1").value;
    var str1 = "";
    if(action==1)
    {
        str1 = document.getElementById("msg2").value + "::M"+ ":0:0";
        document.getElementById("msgbox").value="";
        return false;
    }
    else
        // str1 = document.getElementById("msg2").value + ":" + document.getElementById("msgbox").value + ":M"+ ":0:0";
    {
        var messagetext=document.getElementById("msgbox").value;
        messagetext=messagetext.replace(/[^a-z\d\s\.]+/gi, "");
        str1 = document.getElementById("msg2").value + ":" + messagetext + ":M" + ":0:0";
    }

    var str = base_url + "/Court/CourtCauseListController/insert_show?str=" + encodeURIComponent(str1);
    xhr2.open("GET", str, true);
    xhr2.onreadystatechange = function ()
    {
        if (xhr2.readyState == 4 && xhr2.status == 200)
        {
            var data = xhr2.responseText;
           // alert('Message inserted successully!!');
            var inmsg = parseInt(msg1.indexOf("Message :"));
            if (inmsg >= 0)
                msg1 = msg1.substr(0, inmsg) + "Message : " + document.getElementById("msgbox").value;
            else
            {
                if (msg1 != "")
                    msg1 = msg1 + "; Message : " + document.getElementById("msgbox").value;
                else
                    msg1 = msg1 + "Message : " + document.getElementById("msgbox").value;
            }
            alert('Message inserted successully!!');
            document.getElementById("msg1").value = msg1;
        }
    }// inner function end
    xhr2.send(null);
}
function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}
function isCourtNoAndCLDateSelected() {
    var courtNo = document.getElementById("courtno").value;
    var causelistDate = document.getElementById("dtd").value;

    if(courtNo===undefined || courtNo=='' || causelistDate===undefined || causelistDate=='')
    {

        alert("Select Court No. and Cause List Date to continue..");
        return false;
    }
    return true;
}

function save_mentioning_cases_hearing_status(action)
{

    isCourtNoAndCLDateSelected();
    var xhr2 = getXMLHTTP();
    var msg1 = document.getElementById("mentioningNo").value;
    var str1 = "";
    if(action==1)
    {
        str1 = document.getElementById("msg2").value + "::M"+ ":0:0";
        document.getElementById("mentioningNo").value="";
        return false;
    }
    else
    // str1 = document.getElementById("msg2").value + ":" + document.getElementById("msgbox").value + ":M"+ ":0:0";
    {
        var messagetext=document.getElementById("mentioningNo").value;
        messagetext=messagetext.replace(/[^a-z\d\s\.]+/gi, "");
        str1 = document.getElementById("msg2").value + ":" + messagetext + ":M" + ":0:0";
    }

    var str = base_url + "/Court/CourtCauseListController/insert_mentioning_case_for_displayboard?str=" + encodeURIComponent(str1);
    xhr2.open("GET", str, true);
    xhr2.onreadystatechange = function ()
    {
        if (xhr2.readyState == 4 && xhr2.status == 200)
        {
            var data = xhr2.responseText;
            var inmsg = parseInt(msg1.indexOf("Message :"));
            //alert('Message inserted successully!!');
            if (inmsg >= 0) {
                msg1 = msg1.substr(0, inmsg) + "Message : " + document.getElementById("mentioningNo").value;
            }
            else
            {
                if (msg1 != "")
                    msg1 = msg1 + "; Message : " + document.getElementById("mentioningNo").value;
                else
                    msg1 = msg1 + "Message : " + document.getElementById("mentioningNo").value;
            }
            alert('Message inserted successully!!');
            document.getElementById("msg1").value = msg1;
        }
    }// inner function end
    xhr2.send(null);
}

function clrbrd1(crt, cdt) {
    $("input[type='radio'][name^='rbtn']").each(function () {  //alert("af");
        if ($(this).is(':checked')) {
            $(this).attr("checked", false);
        }
    });
    var xhr2 = getXMLHTTP();
    var str = base_url + "/Court/CourtCauseListController/delete_show.php?crt=" + crt + "&cdt=" + cdt;
    xhr2.open("GET", str, true);
    xhr2.onreadystatechange = function ()
    {
        if (xhr2.readyState == 4 && xhr2.status == 200)
        {
            var data = xhr2.responseText;
        }
    }// inner function end
    xhr2.send(null);
}
function insert_disp(str,filno,j1,jcodes,sbdb)
{
    //alert('fsdfd');
    var xhr2 = getXMLHTTP();
    var str1 = str;
    //document.getElementById("clrbrd").disabled = '';
    //alert("Here");
    str1 = str1 + ":" + document.getElementById("msgbox").value + ":D" + ":" + filno + ":" + j1 + ":" + jcodes + ":" + sbdb;
    var str = base_url + "/Court/CourtCauseListController/insert_show?str=" + encodeURIComponent(str1);
    //alert(str);
    xhr2.open("GET", str, true);
    xhr2.onreadystatechange = function ()
    {
        if (xhr2.readyState == 4 && xhr2.status == 200)
        {
            var data = xhr2.responseText;
            var parsedData = JSON.parse(data);
            // var inmsg = parseInt(msg1.indexOf("Message :"));

            if (inmsg >= 0)
                msg1 = msg1.substr(0, inmsg) + "Message : " + document.getElementById("msgbox").value;
            else
            {
                if (msg1 != "")
                    msg1 = msg1 + "; Message : " + document.getElementById("msgbox").value;
                else
                    msg1 = msg1 + "Message : " + document.getElementById("msgbox").value;
            }
            document.getElementById("msg1").value = msg1;
        }
    }// inner function end
    xhr2.send(null);
}
function chk_ia(containern, id) {
    if (document.getElementById(containern + 'cb' + id).checked == true) {
        if (containern == "hdremp22_div") {
            document.getElementById('hdremp26_divcb' + id).checked = false;
            document.getElementById('hdremp95_divcb' + id).checked = false;
            document.getElementById('hdremp142_divcb' + id).checked = false;
        }
        if (containern == "hdremp26_div") {
            document.getElementById('hdremp22_divcb' + id).checked = false;
            document.getElementById('hdremp95_divcb' + id).checked = false;
            document.getElementById('hdremp142_divcb' + id).checked = false;
        }
        if (containern == "hdremp95_div") {
            document.getElementById('hdremp26_divcb' + id).checked = false;
            document.getElementById('hdremp22_divcb' + id).checked = false;
            document.getElementById('hdremp142_divcb' + id).checked = false;
        }
        if (containern == "hdremp142_div") {
            document.getElementById('hdremp26_divcb' + id).checked = false;
            document.getElementById('hdremp22_divcb' + id).checked = false;
            document.getElementById('hdremp95_divcb' + id).checked = false;
        }
    }
    var v22 = "";
    $("input[type='checkbox'][id^='hdremp22_divcb']").each(function () {  //alert("af");
        if (document.getElementById($(this).attr('id')).checked == true) {
            if (v22 != "")
                v22 += ",";
            v22 += $(this).val();
        }
    });
    $('#hdremp22').val(v22);
    var v26 = "";
    $("input[type='checkbox'][id^='hdremp26_divcb']").each(function () {  //alert("af");
        if (document.getElementById($(this).attr('id')).checked == true) {
            if (v26 != "")
                v26 += ",";
            v26 += $(this).val();
        }
    });
    $('#hdremp26').val(v26);
    var v95 = "";
    $("input[type='checkbox'][id^='hdremp95_divcb']").each(function () {  //alert("af");
        if (document.getElementById($(this).attr('id')).checked == true) {
            if (v95 != "")
                v95 += ",";
            v95 += $(this).val();
        }
    });
    $('#hdremp95').val(v95);
    var v142 = "";
    $("input[type='checkbox'][id^='hdremp142_divcb']").each(function () {  //alert("af");
        if (document.getElementById($(this).attr('id')).checked == true) {
            if (v142 != "")
                v142 += ",";
            v142 += $(this).val();
        }
    });
    $('#hdremp142').val(v142);
}
function addCheckbox(containern, name) {
    var container = $('#' + containern);
    var inputs = container.find('input');
    var id = inputs.length + 1;
    if (id > 1)
        container.append("<br>");
    $('<input />', {type: 'checkbox', id: containern + 'cb' + id, value: name, disabled: true, onclick: 'chk_ia("' + containern + '",' + id + ')'}).appendTo(container);
    $('<label />', {'for': containern + 'cb' + id, text: name}).appendTo(container);
}

function call_div1(cln, cn, e, cnt) {
    var t_val = "";
    var chk_val = $("#paps" + cn).html().split("|");
// alert(eval(cln + cn + e + cnt ));
    $("input[type='checkbox'][name^='chks']").each(function () {
        //document.getElementById('chks' + $(this).val()).checked = false;
        for (i = 0; i < (chk_val.length - 1); i++)
        {
            var chk_val1 = chk_val[i].split("^");
            t_val = chk_val1[0];
            if(t_val == $(this).val())
            {
                //alert(t_val+"-"+$(this).val());
                // $(this).prop('checked', true);
                // $('#chks'+t_val).prop('checked', true)
                //$('#chks'+t_val).checked;
                $('#chks' + $(this).val()).attr('checked', true);
                //document.getElementById('chks' + $(this).val()).checked = true;
            }
        }

    });

    //alert($("#paps" + cn).html());
//    $("input[type='checkbox'][name^='chks']").each(function () {
//
//    });
    //pausecomp(1000);

}
/*function checkNextCourt() {

    var nextCourt = $('input[name=nextCourt]:checked').val();
    var prevCourt = document.getElementById("prvCourt").value;
    if (prevCourt == 101 || prevCourt == ''||prevCourt==22|| prevCourt==21)
    {
        if(nextCourt===undefined)
        {
            alert("Select One Option for Proposed to be listed in");
            return false;
        }
    }
    return true;
}*/


function call_div(cln, cn, e, cnt,subh,crt,connected) {
    var mf = "";
    var jcodes = document.getElementById("jcodes" + cn).innerHTML;
    $('#connected').val(connected);
   // document.getElementById("prvCourt").value = crt;
    make_paps_div(jcodes,cln, cn, e, cnt);
    if(cnt==1)
     make_conct_div(jcodes,cln, cn, 'newb111',cnt,connected);
     if(cnt==2)
     make_conct_div(jcodes,cln, cn, 'newc111',cnt,connected);
//    $("input[type='checkbox'][name^='chks']").each(function () {
//        document.getElementById('chks' + $(this).val()).checked = false;
//    });
    /*if(crt=='J') {
        document.getElementById('R').style.display = "none";
        document.getElementById('C').style.display = "none";
    }
    else if(crt=='C') {
        document.getElementById('C').style.display = "block";
        document.getElementById('R').style.display = "none";
    }
    else {
        document.getElementById('C').style.display = "none";
        document.getElementById('R').style.display = "block";
    }*/

    if (document.frm.mf[0].checked)
        mf = document.frm.mf[0].value;
    if (document.frm.mf[1].checked)
        mf = document.frm.mf[1].value;
    if (document.frm.mf[2].checked)
        mf = document.frm.mf[2].value;
    if (document.frm.mf[3].checked)
        mf = document.frm.mf[3].value;
    if (mf == "1" || mf == "5" || mf == "7")
        document.getElementById("hdate").value = document.getElementById("dtd").value;
    if (cnt == 1)
    {
        var div1 = "chkp";
        var div2 = "hdremp";
        $('#tmp_casenop').val(cn);

        $("#partybutton").attr('disabled', true);
        $("#partybutton1").attr('disabled', true);
        $('#psn').html('<font color=black>Cause List No.' + $('#cln' + cln).html() + '&nbsp;&nbsp;&nbsp;</font>');
        $('#pend_head').html('<font color=red>' + $('#cs' + cn).html() + '</font>');
        $('#pend_head1').html('<font color=blue>' + $('#pn' + cn).html() + '</font><font color=grey>' + ' vs. ' + '</font><font color=blue>' + $('#rn' + cn).html() + '</font>');
        $('#hdremp22_div').html('');
        $('#hdremp26_div').html('');
        $('#hdremp95_div').html('');
        $('#hdremp142_div').html('');
        if(mf == "2"){
            var rfinal=$('#rfinal' + cn).val();
            if(rfinal=="" || rfinal=='151'){
                $('#chkp150').attr('disabled', false);
                $('#chkp151').attr('disabled', true);
                $('#hdremp150').attr('disabled', false);
                $('#hdremp151').attr('disabled', true);
            }
            else
            {
                $('#chkp150').attr('disabled', true);
                $('#chkp151').attr('disabled', false);
                $('#hdremp150').attr('disabled', true);
                $('#hdremp151').attr('disabled', false);
            }
        }
        if ($('#ian' + cn).val() != "")
        {
            var t_var = $('#ian' + cn).val().split(",");
            for (i = 0; i < (t_var.length - 1); i++)
            {
                if (t_var[i] != "")
                {
                    addCheckbox('hdremp22_div', t_var[i]);
                    addCheckbox('hdremp26_div', t_var[i]);
                    addCheckbox('hdremp95_div', t_var[i]);
                    addCheckbox('hdremp142_div', t_var[i]);
                }
            }
            $('#chkp22').attr('disabled', false);
            $('#chkp22').attr('checked', false);
            $('#chkp26').attr('disabled', false);
            $('#chkp26').attr('checked', false);
            $('#chkp95').attr('disabled', false);
            $('#chkp95').attr('checked', false);
            $('#chkp142').attr('disabled', false);
            $('#chkp142').attr('checked', false);
        }
        else
        {
            $('#chkp22').attr('disabled', true);
            $('#chkp22').attr('checked', false);
            $('#chkp26').attr('disabled', true);
            $('#chkp26').attr('checked', false);
            $('#chkp95').attr('disabled', true);
            $('#chkp95').attr('checked', false);
            $('#chkp142').attr('disabled', true);
            $('#chkp142').attr('checked', false);
        }
    }
    else
    {
        var div1 = "chkd";
        var div2 = "hdremd";
        $('#tmp_casenod').val(cn);
        $('#tmp_casenosub').val(subh);
       // $('#connected_d').val(connected);
        $('#psn1').html('<font color=black>Cause List No.' + $('#cln' + cln).html() + '&nbsp;&nbsp;&nbsp;</font>');
        $('#disp_head').html('<font color=red>' + $('#cs' + cn).html() + '</font>');
        $('#disp_head1').html('<font color=blue>' + $('#pn' + cn).html() + '</font><font color=grey>' + ' vs. ' + '</font><font color=blue>' + $('#rn' + cn).html() + '</font>');
    }
    var csval = document.getElementById("caseval" + cn).value;
    var csvalspl = csval.split("^^");
    var t_val;
    var chk_val;
    $("input[type='checkbox'][name^='" + div1 + "']").each(function () {  //alert("af");
        chk_val = $(this).val().split("|");
        int_chk = 0;
        for (i = 0; i < (csvalspl.length - 1); i++)
        {
            t_val = csvalspl[i].split("|");
            if (t_val[0] == chk_val[0])
            {
                document.getElementById(div1 + chk_val[0]).checked = true;
                if(t_val[0]==190 || t_val[0] == 181 || t_val[0] == 204 || t_val[0] == 205){
                    var t_var=t_val[1].replace('D:', '');
                    t_var=t_var.replace('W:', '');
                    t_var=t_var.replace('M:', '');
                    var new_var=t_var.split(',');
                   $("#" + div2 + chk_val[0]+"_1").val(new_var[0]);
                $("#" + div2 + chk_val[0]+"_1").attr('readonly', false);
                $("#" + div2 + chk_val[0]+"_1").css('background-color', '#FFF');
                $("#" + div2 + chk_val[0]+"_1").css('border', '1px solid #ccc');
                
                $("#" + div2 + chk_val[0]+"_2").val(new_var[1]);
                $("#" + div2 + chk_val[0]+"_2").attr('readonly', false);
                $("#" + div2 + chk_val[0]+"_2").css('background-color', '#FFF');
                $("#" + div2 + chk_val[0]+"_2").css('border', '1px solid #ccc');
                
                                   $("#" + div2 + chk_val[0]+"_3").val(new_var[2]);
                $("#" + div2 + chk_val[0]+"_3").attr('readonly', false);
                $("#" + div2 + chk_val[0]+"_3").css('background-color', '#FFF');
                $("#" + div2 + chk_val[0]+"_3").css('border', '1px solid #ccc');
                int_chk = 1; 
                }
                else{
                $("#" + div2 + chk_val[0]).val(t_val[1]);
                $("#" + div2 + chk_val[0]).attr('readonly', false);
                $("#" + div2 + chk_val[0]).css('background-color', '#FFF');
                $("#" + div2 + chk_val[0]).css('border', '1px solid #ccc');
            }
                if (chk_val[0] == 22 || chk_val[0] == 26 || chk_val[0] == 95 || chk_val[0] == 142)
                {
                    $("input[type='checkbox'][id^='" + "hdremp" + chk_val[0] + "_divcb" + "']").each(function () {
                        $(this).attr('disabled', false);
                        if (parseInt(t_val[1].indexOf($(this).val())) >= 0)
                            $(this).attr('checked', true);
                    });
                }
                if(chk_val[0] == 91){
                    $("#partybutton").attr('disabled', false);
                }
                if(chk_val[0] == 149){
                    $("#partybutton1").attr('disabled', false);
                    make_party_div1();
                }
                int_chk = 1;
            }
        }
        if (int_chk == 0)
        {
            document.getElementById(div1 + chk_val[0]).checked = false;
            $("#" + div2 + chk_val[0]).val('');
            $("#" + div2 + chk_val[0]).attr('readonly', true);
            $("#" + div2 + chk_val[0]).css('background-color', '#F5F5F5');
            $("#" + div2 + chk_val[0]).css('border', '1px solid #ccc');
            if (chk_val[0] == 22 || chk_val[0] == 26 || chk_val[0] == 95 || chk_val[0] == 142)
            {
                $("input[type='checkbox'][id^='" + "hdremp" + chk_val[0] + "_divcb" + "']").each(function () {
                    $(this).attr('disabled', true);
                    $(this).attr('checked', false);
                });
            }
        }
    });
    call_f1(cnt);
    //call_div1(cln, cn, e, cnt);


}
if (document.location.protocol == 'file:') {
    alert("The examples might not work properly on the local file system due to security settings in your browser. Please use a real webserver.");
}

function fsubmit()
{
     
    var mf = '';
    if (document.frm.mf[0].checked)
        mf = document.frm.mf[0].value;
    if (document.frm.mf[1].checked)
        mf = document.frm.mf[1].value;
    if (document.frm.mf[2].checked)
        mf = document.frm.mf[2].value;
    if (document.frm.mf[3].checked)
        mf = document.frm.mf[3].value;

var courtno = document.getElementById("courtno").value;
var court_text = $("#courtno option:selected").text();

    //var aw1 = document.getElementById("aw1").value;
    var aw1 = "";

    var dtd = document.getElementById("dtd").value;
    document.getElementById("r_box").style.display = "none";
    document.getElementById("hint").innerHTML = '<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>';
    var ajaxRequest; // The variable that makes Ajax possible!
    try {
        ajaxRequest = new XMLHttpRequest(); // Opera 8.0+, Firefox, Safari
    } catch (e)
    {
        try {
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP"); // Internet Explorer Browsers
        } catch (e)
        {
            try {
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e)
            {
                alert("Your browser broke!"); // Something went wrong
                return false;
            }
        }
    }
    // Create a function that will receive data sent from the server
    ajaxRequest.onreadystatechange = function ()
    {
        if (ajaxRequest.readyState == 4) {
            document.getElementById("hint").innerHTML = '';
            document.getElementById("r_box").style.display = "block";
            document.getElementById("r_box").innerHTML = ajaxRequest.responseText;
            //make_paps_div();
        }
    }
    var url = base_url + "/Court/CourtCauseListController/reader_cl_process";


    url = url + "?aw1=" + aw1 + "&dtd=" + dtd + "&mf=" + mf + "&courtno=" + courtno + "&court_text=" + court_text;
    ajaxRequest.open("GET", url, true);
    ajaxRequest.send(null);
}


function make_paps_div(jcodes,cln, cn, e, cnt) {

    var xhr2 = getXMLHTTP();
    var str = base_url+"/Court/CourtCauseListController/get_steno?judges=" + jcodes;
    // alert(str);
    xhr2.open("GET", str, true);
    xhr2.onreadystatechange = function ()
    {
        if (xhr2.readyState == 4 && xhr2.status == 200)
        {
            var data = xhr2.responseText;
            document.getElementById('paps').value=data;
            //alert(data);
            var paps = document.getElementById('paps').value;
            var div_output = "";
            var paps1 = "";
            var paps2 = "";
            var snoo = 0;
            var bgc = "";
            if (paps != "") {
                div_output += '<table cellspacing=0 cellpadding=0 width="100%" border="1" style="border-collapse: collapse;padding:0;">';
                paps1 = paps.split("#");
                //var cntr = parseInt(paps1.length / 2);
                var cntr =  2;
                for (var i = 0; i < (paps1.length - 1); i++)
                {
                    snoo++;
                    paps2 = paps1[i].split("|");
                    bgc = "#F8F9FC";
                    if ((snoo % cntr) != 0) {
                        div_output += '<tr bgcolor="' + bgc + '" style="padding:0;">';
                    }
                    div_output += '<td style="padding:0;"><input class="cls_chks" type="checkbox" name="chks' + paps2[0] + '" id="chks' + paps2[0] + '" value="' + paps2[0] + '"/><label style="font-size:8pt;" for="chks' + paps2[0] + '">' + paps2[1] + '</label></td>';
                    if ((snoo % cntr) == 0)
                        div_output += '</tr>';
                }
                div_output += '</table>';
            }
            document.getElementById("newa123").innerHTML = div_output;

            call_div1(cln, cn, e, cnt);
        }
    }// inner function end
    xhr2.send(null);
}
function make_conct_div(jcodes, cln, cn, divv,cnt,connected) {
//    alert($('#conc'+cn).val());
    if (cnt == 1)
    {
        var div3="chkcnp";
    }
    else
    {
         var div3="chkcnd";
    }
//if($('#conc'+cn).length){
    
if(connected.length){

            //var data = $('#conc'+cn).val();F
           //alert(data);
            var data = connected;
            data = data.replace(/,\s*$/, "");
            var div_output = "";
            var div_output1 = "";
            var conct = "";
            var conct1 = "";
            var snoo = 0;
            var bgc = "";
            if (data != "") {
                div_output += '<table cellspacing=0 cellpadding=0 width="100%" border="1" style="border-collapse: collapse;padding:0;">';
                data1 = data.split(",");
                var cntr =  5;
                div_output += '<tr style="padding:0;"><td><input class="cls_chkdn" type="checkbox" name="allchkdn" id="allchkdn" value="all" onclick="chk_all_cn('+cnt+');"><label style="font-size:8pt;font-weight:bold;" for="allchkdn">CHECK ALL CONNECTED CASES</label></td><td colspan='+(cntr-1)+' align=center><b>CONNECTED CASES</b></td></tr>';

                for (var i = 0; i < (data1.length); i++)
                {
                    snoo++;
                    //conct = data1[i];
//if(document.getElementById("caseval" + conct).value==''  && document.getElementById("cr_span" + conct).innerHTML =='') 
//{                    
                    conct=data1[i].split(";")[0];

                    conct1=conct.slice(0,-4)+"/"+conct.substr(-4)+"     "+data1[i].split(";")[1];
                    bgc = "#F8F9FC";
                    if ((snoo % cntr) == 1) {
                        div_output1 += '<tr bgcolor="' + bgc + '" style="padding:0;">';
                    }
                    div_output1 += '<td style="padding:0;"><input class="cls_chkdn" type="checkbox" name="' + div3 + conct + '" id="' + div3 + conct + '" value="' + conct + '"/><label style="font-size:8pt;" for="' + div3 + conct + '">' + conct1 + '</label></td>';
                    if ((snoo % cntr) == 0)
                        div_output1 += '</tr>';
//                }
                
            }
             for(var z= (snoo % cntr); z<=5; z++){
                div_output1 += '<td style="padding:0;"></td>'; 
             }
            
            if(div_output1!= "")
            div_output = div_output + div_output1 + '</table>';
        else
            div_output='';
        }
            document.getElementById(divv).innerHTML = div_output;

        }
        else
        {
        document.getElementById(divv).innerHTML = '';
        }
//    }// inner function end
//    xhr2.send(null);
}
function make_party_div_popup(){
    document.getElementById("newparty1").style.display = 'block';

}
function make_party_div1() {

    $('#newb').width($(window).width() - 150);
    $('#newb').height($(window).height() - 50);
    $('#newparty1').height($('#newb').height() - 100);

    var filno= $('#tmp_casenop').val();
    var cldt = document.getElementById("dtd").value;
    var dt1 = cldt.split("-");
    var dt_new = dt1[2] + "-" + dt1[1] + "-" + dt1[0];
    var xhr2 = getXMLHTTP();
    var str = base_url + "/Court/CourtCauseListController/get_parties1.php?filno=" + filno + "&cldt=" + dt_new;
    // alert(str);
    xhr2.open("GET", str, true);
    xhr2.onreadystatechange = function ()
    {
        if (xhr2.readyState == 4 && xhr2.status == 200)
        {
            var data = xhr2.responseText;
            // document.getElementById('paps').value=data;
            //alert(data);
            // var paps = document.getElementById('paps').value;
            var div_output = "";
            var p = data;
            var p1 = "";
            var p2 = "";
            var snoo = 0;
            var bgc = "";
            if (p != "") {
                div_output += '<table cellspacing=0 cellpadding=0 width="100%" border="1" style="border-collapse: collapse;padding:0;">';
                div_output += '<tr><td colspan="10" align=center><b>SELECT PARTY(S) FOR FRESH PF (O=>ORDINARY, R=>REGISTRY AND H=>HUMDAST)</b></td></tr>';
                p1 = p.split("#");
                //var cntr = parseInt(paps1.length / 2);
                var cntr =  2;
                for (var i = 0; i < (p1.length - 1); i++)
                {
                    snoo++;
                    p2 = p1[i].split("|");
                    bgc = "#F8F9FC";
                    var ord="";
                    var reg="";
                    var hum="";
                    var sstr=p2[4];
                    if (sstr.indexOf("O")>=0) ord='checked="checked"';
                    if (sstr.indexOf("R")>=0) reg='checked="checked"';
                    if (sstr.indexOf("H")>=0) hum='checked="checked"';


                    if ((snoo % cntr) != 0) {
                        div_output += '<tr bgcolor="' + bgc + '" style="padding:0;">';
                    }
                    if(p2[3]=="F"){
                        div_output += '<td style="padding:0;background-color:#E6EAF4;" width="5%" align=center><b>'+ p2[1] + p2[2] +'</b></td><td style="padding:0;background-color:#E6EAF4;" width="30%"><input class="cls_party1" type="checkbox" name="tparty' + p2[1] + p2[2] + '" id="tparty' + p2[1] + p2[2] + '" value="' + p1[i] + '" checked="checked"/><label style="font-size:8pt;" for="tparty' + p2[1] + p2[2] + '">' + p2[0] + '</label></td>';

                        div_output += '<td style="padding:0;" width="5%" align=center><input class="cls_party_o1" type="checkbox" name="ord' + p2[1] + p2[2] + '" id="ord' + p2[1] + p2[2] + '" value="O" ' + ord + '/><label style="font-size:8pt;" for="ord' + p2[1] + p2[2] + '">O</label></td>';
                        div_output += '<td style="padding:0;" width="5%" align=center><input class="cls_party_r1" type="checkbox" name="reg' + p2[1] + p2[2] + '" id="reg' + p2[1] + p2[2] + '" value="R" ' + reg + '/><label style="font-size:8pt;" for="reg' + p2[1] + p2[2] + '">R</label></td>';
                        div_output += '<td style="padding:0;" width="5%" align=center><input class="cls_party_h1" type="checkbox" name="hum' + p2[1] + p2[2] + '" id="hum' + p2[1] + p2[2] + '" value="H" ' + hum + '/><label style="font-size:8pt;" for="hum' + p2[1] + p2[2] + '">H</label></td>';

                    }
                    else{
                        div_output += '<td style="padding:0;background-color:#E6EAF4;" width="5%" align=center><b>'+ p2[1] + p2[2] +'</b></td><td style="padding:0;background-color:#E6EAF4;" width="30%"><input class="cls_party1" type="checkbox" name="tparty' + p2[1] + p2[2] + '" id="tparty' + p2[1] + p2[2] + '" value="' + p1[i] + '"/><label style="font-size:8pt;" for="tparty' + p2[1] + p2[2] + '">' + p2[0] + '</label></td><td style="padding:0;" width="5%" align=center><input class="cls_party_o1" type="checkbox" name="ord' + p2[1] + p2[2] + '" id="ord' + p2[1] + p2[2] + '" value="O"/><label style="font-size:8pt;" for="ord' + p2[1] + p2[2] + '">O</label></td><td style="padding:0;" width="5%" align=center><input class="cls_party_r1" type="checkbox" name="reg' + p2[1] + p2[2] + '" id="reg' + p2[1] + p2[2] + '" value="R"/><label style="font-size:8pt;" for="reg' + p2[1] + p2[2] + '">R</label></td><td style="padding:0;" width="5%" align=center><input class="cls_party_h1" type="checkbox" name="hum' + p2[1] + p2[2] + '" id="hum' + p2[1] + p2[2] + '" value="H"/><label style="font-size:8pt;" for="hum' + p2[1] + p2[2] + '">H</label></td>';
                    }
                    if ((snoo % cntr) == 0)
                        div_output += '</tr>';
                }
                div_output += '</table><div id="buttonbottom" style="width: 100%; position:absolute;bottom:0; text-align:center;"><input name="cparty1" type="button" value="CLOSE" onclick="close_party1();"/></div>';
            }
            document.getElementById("newparty1").innerHTML = div_output;
//    document.getElementById("newparty1").style.display = 'block';
        }
    }// inner function end
    xhr2.send(null);
}
function close_party1(){
    document.getElementById("newparty1").style.display = 'none';
}
function make_party_div() {

    $('#newb').width($(window).width() - 150);
    $('#newb').height($(window).height() - 50);
    $('#newparty').height($('#newb').height() - 100);

    var filno= $('#tmp_casenop').val();
    var cldt = document.getElementById("dtd").value;
    var dt1 = cldt.split("-");
    var dt_new = dt1[2] + "-" + dt1[1] + "-" + dt1[0];
    var xhr2 = getXMLHTTP();
    var str = base_url + "/Court/CourtCauseListController/get_parties.php?filno=" + filno + "&cldt=" + dt_new;
    // alert(str);
    xhr2.open("GET", str, true);
    xhr2.onreadystatechange = function ()
    {
        if (xhr2.readyState == 4 && xhr2.status == 200)
        {
            var data = xhr2.responseText;
            // document.getElementById('paps').value=data;
            //alert(data);
            // var paps = document.getElementById('paps').value;
            var div_output = "";
            var p = data;
            var p1 = "";
            var p2 = "";
            var snoo = 0;
            var bgc = "";
            if (p != "") {
                div_output += '<table cellspacing=0 cellpadding=0 width="100%" border="1" style="border-collapse: collapse;padding:0;">';
                div_output += '<tr><td colspan="4" align=center><b>SELECT PARTY(S) TO BE APPEARED BEFORE REGISTRY</b></td></tr>';
                p1 = p.split("#");
                //var cntr = parseInt(paps1.length / 2);
                var cntr =  2;
                for (var i = 0; i < (p1.length - 1); i++)
                {
                    snoo++;
                    p2 = p1[i].split("|");
                    bgc = "#F8F9FC";
                    if ((snoo % cntr) != 0) {
                        div_output += '<tr bgcolor="' + bgc + '" style="padding:0;">';
                    }
                    if(p2[3]=="F"){
                        div_output += '<td style="padding:0;" width="5%" align=center><b>'+ p2[1] + p2[2] +'</b></td><td style="padding:0;" width="45%"><input class="cls_party" type="checkbox" name="party' + p2[1] + p2[2] + '" id="party' + p2[1] + p2[2] + '" value="' + p1[i] + '" checked="checked"/><label style="font-size:8pt;" for="party' + p2[1] + p2[2] + '">' + p2[0] + '</label></td>';
                    }
                    else{
                        div_output += '<td style="padding:0;" width="5%" align=center><b>'+ p2[1] + p2[2] +'</b></td><td style="padding:0;" width="45%"><input class="cls_party" type="checkbox" name="party' + p2[1] + p2[2] + '" id="party' + p2[1] + p2[2] + '" value="' + p1[i] + '"/><label style="font-size:8pt;" for="party' + p2[1] + p2[2] + '">' + p2[0] + '</label></td>';
                    }
                    if ((snoo % cntr) == 0)
                        div_output += '</tr>';
                }
                div_output += '</table><div id="buttonbottom" style="width: 100%; position:absolute;bottom:0; text-align:center;"><input name="sparty" type="button" value="SAVE" onclick="save_parties();"/>&nbsp;<input name="cparty" type="button" value="CLOSE" onclick="close_party();"/></div>';
            }
            document.getElementById("newparty").innerHTML = div_output;
            document.getElementById("newparty").style.display = 'block';
        }
    }// inner function end
    xhr2.send(null);
}

function close_party(){
    document.getElementById("newparty").style.display = 'none';
}

function insert_court_stat(crt, cdt, jcodes)
{
    var xhr2 = getXMLHTTP();
    var ucode = document.getElementById('hd_ud').value;
    var str = base_url + "/Court/CourtCauseListController/insert_court_status.php?crt=" + crt + "&cdt=" + cdt + "&jcodes=" + jcodes + "&eby=" + ucode;
    xhr2.open("GET", str, true);
    xhr2.onreadystatechange = function ()
    {
        if (xhr2.readyState == 4 && xhr2.status == 200)
        {
            var data = xhr2.responseText;
            alert(data);
        }
    }// inner function end
    xhr2.send(null);
}

function chk_all_cn(cnt){
        if (cnt == 1)
    {
        var div3="chkcnp";
    }
    else
    {
         var div3="chkcnd";
    }
    $("input[type='checkbox'][name^='"+div3+"']").each(function () {
        if(document.getElementById('allchkdn').checked)
        $(this).prop('checked',true);
    else
        $(this).prop('checked',false);
                            });
}
function check_select(para1){
    if(para1==1)
        document.getElementById("aw1").selectedIndex =0;

        if(para1==2)
        document.getElementById("courtno").selectedIndex =0;
}
