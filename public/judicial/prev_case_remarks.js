var hd_folder = $('#hd_folder').val();
var hd_ud = $('#hd_ud').val();
function remarks_input_validate(evt, rid) {
    if (rid == 'NUM') {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;

        if (charCode == 46 || charCode == 8 || charCode == 9 || charCode == 37 || charCode == 39 || (charCode >= 48 && charCode <= 57)) {
            return true;
        }
        return false;

    }
}
function enable_buttons() {
    $("input.pdbutton").attr("disabled", false);
    //$("input[type='button'][name^='btnpnd_']").each(function () {
    //$(this).attr('disabled', false); 
    //});    
}

function disable_buttons() {
    $("input.pdbutton").attr("disabled", true);
    //$("input[type='button'][name^='btnpnd_']").each(function () {
    //$(this).attr('disabled', true); 
    //});    
}
function get_heading_types(str) {
    var mn = document.getElementById('mn').value;
    var cs_tp = document.getElementById('cs_tp').value;
    var cs_tp_x = cs_tp;
    if (cs_tp.length == 2) {
        cs_tp = '0' + cs_tp;
    }
    var txtFNo = document.getElementById('txtFNo').value;
    var txtYear = document.getElementById('txtYear').value;
    var flno = mn + cs_tp + txtFNo + txtYear;
    var ddl_m_f = $('#ddl_m_f').val();
    var ddl_bench = $('#ddl_bench').val();
    if (ddl_bench == 'S') {
        $('#jud2').val('0');
        $('#jud2').attr('disabled', true);
        //             $('#jud1').val('0');
        $('#jud1').attr('disabled', false);
    }
    else if (ddl_bench == 'D') {
        //            $('#jud2').val('0');
        $('#jud2').attr('disabled', false);
        //             $('#jud1').val('0');
        $('#jud1').attr('disabled', false);
    }
    if (str == 'M') {
        var xmlhttp;
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        }
        else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById('ddl_m').innerHTML = xmlhttp.responseText;
                $('#ddl_m').css('display', 'block');
                $('#dv_motion_final').css('display', 'none');

            }
        }
        xmlhttp.open("GET", hd_folder + "/get_heading_type.php?flno=" + flno + "&str=" + str, true);
        xmlhttp.send(null);
    }
    else if (str == 'F') {
        if (ddl_m_f != '' && ddl_bench != '') {
            get_stagecode();
        }
        else {
            $('#ddl_m').html('<option value="">Select</option>')
        }
    }

}

function com_filingNo() {
    var txtNo = document.getElementById('txtFNo').value;
    if (txtNo.length == "1") {
        txtNo = "0000" + txtNo;
    }
    else if (txtNo.length == "2") {
        txtNo = "000" + txtNo;
    }
    else if (txtNo.length == "3") {
        txtNo = "00" + txtNo;
    }
    else if (txtNo.length == "4") {
        txtNo = "0" + txtNo;
    }
    document.getElementById('txtFNo').value = txtNo;
}

function get_list_date() {
    var mn = document.getElementById('mn').value;
    var cs_tp = document.getElementById('cs_tp').value;
    var cs_tp_x = cs_tp;
    if (cs_tp.length == 2) {
        cs_tp = '0' + cs_tp;
    }
    var txtFNo = document.getElementById('txtFNo').value;
    var txtYear = document.getElementById('txtYear').value;
    var filling_no = mn + cs_tp + txtFNo + txtYear;
    $.ajax({
        url: hd_folder + '/get_prev_case_remarks.php',
        cache: false,
        data: { filling_no: filling_no, hd_ud: hd_ud },
        type: 'POST',
        success: function (data, status) {
            //           alert(data);
            $('#dv_res_x').html(data);

            var fno = filling_no;
            //      if(document.getElementById('ian_cx'+fno).value=='')
            //             document.getElementById('dv_hd_ia').style.display='none';
            //         else
            document.getElementById('sp_ffno' + fno).innerHTML = document.getElementById('ian_cx' + fno).value;

            var curr_date = document.getElementById("curr_date").value;
            var date = new Date(curr_date);
            date.setDate(date.getDate() + 1);
            var date1 = new Date(curr_date);
            date1.setDate(date1.getDate());
            /* $("#hdremp21").datepicker({dateFormat: "dd-mm-yy", numberOfMonths: 2, minDate: date});
             $("#hdremp24").datepicker({dateFormat: "dd-mm-yy", numberOfMonths: 2, minDate: date});
 //            $("#hdremp180").datepicker({dateFormat: "dd-mm-yy", numberOfMonths: 2, minDate: date});
             $("#hdremp59").datepicker({dateFormat: "dd-mm-yy", numberOfMonths: 2, minDate: date});
             $("#hdremp91").datepicker({dateFormat: "dd-mm-yy", numberOfMonths: 2, minDate: date1});
             $("#hdremp129").datepicker({dateFormat: "dd-mm-yy", numberOfMonths: 2, minDate: date});
             $("#hdremp131").datepicker({dateFormat: "dd-mm-yy", numberOfMonths: 2, minDate: date});
             $("#hdremp70").datepicker({dateFormat: "dd-mm-yy", numberOfMonths: 2, minDate: date});
 //$("#hdate").datepicker({ dateFormat: "dd-mm-yy", numberOfMonths: 2, minDate: new Date() });
             $("#hdate").datepicker({dateFormat: "dd-mm-yy", numberOfMonths: 2, maxDate: new Date(curr_date)});
 
             $("#hdremp21").keypress(function (e) {
                 e.preventDefault();
             });
             $("#hdremp24").keypress(function (e) {
                 e.preventDefault();
             });
 //            $("#hdremp180").keypress(function (e) {
 //                e.preventDefault();
 //            });            
             $("#hdremp59").keypress(function (e) {
                 e.preventDefault();
             });
             $("#hdremp129").keypress(function (e) {
                 e.preventDefault();
             });
             $("#hdremp131").keypress(function (e) {
                 e.preventDefault();
             });
             $("#hdremp70").keypress(function (e) {
                 e.preventDefault();
             });
             $("#hdate").keypress(function (e) {
                 e.preventDefault();
             });*/
            $("#hdremp21").addClass("dtp");
            $("#hdremp24").addClass("dtp");
            //    $("#hdremp180").addClass( "dtp" );
            $("#hdremp59").addClass("dtp");
            $("#hdremp91").addClass("dtp");
            $("#hdremp131").addClass("dtp");
            $("#hdremp70").addClass("dtp");
            $("#hdremp153").addClass("dtp");
            $("#hdate").addClass("dtp");
        },
        error: function (xhr) {
            alert("Error: " + xhr.status + " " + xhr.statusText);
        }
    });
    enable_buttons();
}

function make_conct_div(jcodes, cln, cn, divv, cnt, connected) {

    //alert($('#connected').val());
    if (cnt == 1) {
        var div3 = "chkcnp";

    }
    else {
        var div3 = "chkcnd";
    }

    if (connected.length) {
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
            var cntr = 5;
            div_output += '<tr style="padding:0;"><td><input class="cls_chkdn" type="checkbox" name="allchkdn" id="allchkdn" value="all" onclick="chk_all_cn(' + cnt + ');"><label style="font-size:8pt;font-weight:bold;" for="allchkdn">CHECK ALL CONNECTED CASES</label></td><td colspan=' + (cntr - 1) + ' align=center><b>CONNECTED CASES</b></td></tr>';

            for (var i = 0; i < (data1.length); i++) {
                snoo++;
                conct = data1[i].split(";")[0];

                conct1 = conct.slice(0, -4) + "/" + conct.substr(-4) + "     " + data1[i].split(";")[1];
                bgc = "#F8F9FC";
                if ((snoo % cntr) == 1) {
                    div_output1 += '<tr bgcolor="' + bgc + '" style="padding:0;">';
                }
                div_output1 += '<td style="padding:0;"><input class="cls_chkdn" type="checkbox" name="' + div3 + conct + '" id="' + div3 + conct + '" value="' + conct + '"/><label style="font-size:8pt;" for="' + div3 + conct + '">' + conct1 + '</label></td>';
                if ((snoo % cntr) == 0)
                    div_output1 += '</tr>';
                //                }

            }
            for (var z = (snoo % cntr); z <= 5; z++) {
                div_output1 += '<td style="padding:0;"></td>';
            }

            if (div_output1 != "")
                div_output = div_output + div_output1 + '</table>';
            else
                div_output = '';
        }
        document.getElementById(divv).innerHTML = div_output;

    }
    else {
        document.getElementById(divv).innerHTML = '';
    }
    //    }// inner function end
    //    xhr2.send(null);
}

function chk_all_cn(cnt) {
    if (cnt == 1) {
        var div3 = "chkcnp";
    }
    else {
        var div3 = "chkcnd";
    }
    $("input[type='checkbox'][name^='" + div3 + "']").each(function () {
        if (document.getElementById('allchkdn').checked)
            $(this).prop('checked', true);
        else
            $(this).prop('checked', false);
    });
}


function call_div(cln, cn, e, cnt, nxt_dt, mul_cx, jcodes, mainhead, hl, connected, sno) {
    load_defaults();
    disable_buttons();
    $('#old_new').val(hl);
    //    alert(cln);
    //alert(jcodes);
    //var mf="";
    //if(document.frm.mf[0].checked)
    //mf=document.frm.mf[0].value;
    //if(document.frm.mf[1].checked)
    //mf=document.frm.mf[1].value;
    //if(document.frm.mf[2].checked)
    //mf=document.frm.mf[2].value;
    //if(mf=="1" || mf=="5")
    //    document.getElementById("hdate").value = nxt_dt;
    document.getElementById("dtd").value = nxt_dt;
    //    $('#caseval' + cn).val(mul_cx);
    //    document.getElementById("o_d").value = nxt_dt;
    //    document.getElementById("jcodes" + cn).innerHTML = jcodes;

    document.getElementById("jcodes").value = jcodes;
    document.getElementById("clno").value = cln;
    document.getElementById("sno").value = sno;
    //document.getElementById("prvCourt").value = bt1;
    //    document.getElementById("mainhead" + cn).innerHTML = mainhead;
    document.getElementById("mh").value = mainhead;
    //    document.getElementById("brd" + cn).value = cln;

    //else
    //document.getElementById("hdate").value=date;
    //alert(cln);
    //alert($('#cln'+cln).html(cln));
    //var str1=cln.split('#cln');
    //alert(str1[1]);+
    /*  if(bt1=='R') {
          document.getElementById('R').style.display = "block";
          document.getElementById('C').style.display = "none";
      }
      else if(bt1=='C') {
          document.getElementById('C').style.display = "block";
          document.getElementById('R').style.display = "none";
      }
      else {
          document.getElementById('C').style.display = "none";
          document.getElementById('R').style.display = "none";
      }*/

    if (cnt == 1)
        make_conct_div(jcodes, cln, cn, 'newb111', cnt, connected);
    if (cnt == 2)
        make_conct_div(jcodes, cln, cn, 'newc111', cnt, connected);

    if (cnt == 1) {
        var div1 = "chkp";
        var div2 = "hdremp";
        $('#tmp_casenop').val(cn);
        $('#connected').val(connected);
        $('#listing_date').val(nxt_dt);
        $("#partybutton").attr('disabled', true);
        $("#partybutton1").attr('disabled', true);
        $('#psn').html('<font color=black>Cause List No.' + $('#cln' + cln).html() + '&nbsp;&nbsp;&nbsp;</font>');
        //        $('#pend_head').html('<font color=red>' + $('#cs' + cn).html() + '</font>');
        //        $('#pend_head1').html('<font color=blue>' + $('#pn' + cn).html() + '</font><font color=grey>' + ' vs. ' + '</font><font color=blue>' + $('#rn' + cn).html() + '</font>');
        $('#hdremp22_div').html('');
        $('#hdremp26_div').html('');
        $('#hdremp95_div').html('');
        $('#hdremp142_div').html('');
        //if(mf == "2"){
        if (mainhead == "F") {
            var rfinal = $('#rfinal' + cn).val();
            if (rfinal == "" || rfinal == '151') {
                $('#chkp150').attr('disabled', false);
                $('#chkp151').attr('disabled', true);
                $('#hdremp150').attr('disabled', false);
                $('#hdremp151').attr('disabled', true);
            }
            else {
                $('#chkp150').attr('disabled', true);
                $('#chkp151').attr('disabled', false);
                $('#hdremp150').attr('disabled', true);
                $('#hdremp151').attr('disabled', false);
            }
        }
        if ($('#ian' + cn).val() != "") {
            var t_var = $('#ian' + cn).val().split(",");
            for (i = 0; i < (t_var.length - 1); i++) {
                if (t_var[i] != "") {
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
        else {
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
    else {
        var div1 = "chkd";
        var div2 = "hdremd";
        $('#tmp_casenod').val(cn);
        $('#hdate').val(nxt_dt);
        $('#connected_d').val(connected);
        //        $('#tmp_casenosub').val(subh);
        $('#psn1').html('<font color=black>Cause List No.' + $('#cln' + cln).html() + '&nbsp;&nbsp;&nbsp;</font>');
        $('#disp_head').html('<font color=red>' + $('#cs' + cn).html() + '</font>');
        $('#disp_head1').html('<font color=blue>' + $('#pn' + cn).html() + '</font><font color=grey>' + ' vs. ' + '</font><font color=blue>' + $('#rn' + cn).html() + '</font>');
    }

    var csval = mul_cx;
    var csvalspl = csval.split("^^");
    //    alert(csval);
    var t_val;
    var chk_val;
    $("input[type='checkbox'][name^='" + div1 + "']").each(function () {
        chk_val = $(this).val().split("|");
        int_chk = 0;
        for (i = 0; i < (csvalspl.length - 1); i++) {
            t_val = csvalspl[i].split("|");
            if (t_val[0] == chk_val[0]) {
                document.getElementById(div1 + chk_val[0]).checked = true;
                if (t_val[0] == 190 || t_val[0] == 181 || t_val[0] == 204 || t_val[0] == 205) {
                    var t_var = t_val[1].replace('D:', '');
                    t_var = t_var.replace('W:', '');
                    t_var = t_var.replace('M:', '');
                    var new_var = t_var.split(',');
                    $("#" + div2 + chk_val[0] + "_1").val(new_var[0]);
                    $("#" + div2 + chk_val[0] + "_1").attr('readonly', false);
                    $("#" + div2 + chk_val[0] + "_1").css('background-color', '#FFF');
                    $("#" + div2 + chk_val[0] + "_1").css('border', '1px solid #ccc');

                    $("#" + div2 + chk_val[0] + "_2").val(new_var[1]);
                    $("#" + div2 + chk_val[0] + "_2").attr('readonly', false);
                    $("#" + div2 + chk_val[0] + "_2").css('background-color', '#FFF');
                    $("#" + div2 + chk_val[0] + "_2").css('border', '1px solid #ccc');

                    $("#" + div2 + chk_val[0] + "_3").val(new_var[2]);
                    $("#" + div2 + chk_val[0] + "_3").attr('readonly', false);
                    $("#" + div2 + chk_val[0] + "_3").css('background-color', '#FFF');
                    $("#" + div2 + chk_val[0] + "_3").css('border', '1px solid #ccc');
                    int_chk = 1;
                }
                else {
                    $("#" + div2 + chk_val[0]).val(t_val[1]);
                    $("#" + div2 + chk_val[0]).attr('readonly', false);
                    $("#" + div2 + chk_val[0]).css('background-color', '#FFF');
                    $("#" + div2 + chk_val[0]).css('border', '1px solid #ccc');
                }
                if (chk_val[0] == 22 || chk_val[0] == 26 || chk_val[0] == 95 || chk_val[0] == 142) {
                    $("input[type='checkbox'][id^='" + "hdremp" + chk_val[0] + "_divcb" + "']").each(function () {
                        $(this).attr('disabled', false);
                        if (parseInt(t_val[1].indexOf($(this).val())) >= 0)
                            $(this).attr('checked', true);
                    });
                }
                if (chk_val[0] == 91) {
                    $("#partybutton").attr('disabled', false);
                }
                if (chk_val[0] == 149) {
                    $("#partybutton1").attr('disabled', false);
                    make_party_div1();
                }
                int_chk = 1;
            }
        }
        if (int_chk == 0) {
            document.getElementById(div1 + chk_val[0]).checked = false;
            $("#" + div2 + chk_val[0]).val('');
            $("#" + div2 + chk_val[0]).attr('readonly', true);
            $("#" + div2 + chk_val[0]).css('background-color', '#F5F5F5');
            $("#" + div2 + chk_val[0]).css('border', '1px solid #ccc');
            if (chk_val[0] == 22 || chk_val[0] == 26 || chk_val[0] == 95 || chk_val[0] == 142) {
                $("input[type='checkbox'][id^='" + "hdremp" + chk_val[0] + "_divcb" + "']").each(function () {
                    $(this).attr('disabled', true);
                    $(this).attr('checked', false);
                });
            }
            if (chk_val[0] == 91)
                $("#partybutton").attr('disabled', true);
        }
        else {
            if (chk_val[0] == 91)
                $("#partybutton").attr('disabled', false);
        }
    });

    //make_party_div(2);
    call_f1(cnt);
}

function load_defaults() 
{
    var curr_date = $("#curr_date").value;
    var date = new Date(curr_date);
    date.setDate(date.getDate() + 1);
    var date1 = new Date(curr_date);
    date1.setDate(date1.getDate());
    
    $("#hdremp21").addClass( "dtp" );
    $("#hdremp24").addClass( "dtp" );
    $("#hdremp59").addClass( "dtp" );
    $("#hdremp91").addClass( "dtp" );
    $("#hdremp131").addClass( "dtp" );
    $("#hdremp70").addClass( "dtp" );
    $("#hdremp153").addClass( "dtp" );
    $("#hdate").addClass( "dtp" );
}

function call_f1(cnt) {
    var divname = "";
    if (cnt == 1) {
        $('#model-pending-remarks').modal({backdrop: 'static', keyboard: false});
        $('#model-pending-remarks').modal('show');
        // divname = "newb";
        //$('#' + divname).width($(window).width() - 150);
        //$('#' + divname).height($(window).height() - 120);

        // $('#' + divname).width(window.frameElement.offsetWidth - 50);
        // $('#' + divname).height(window.frameElement.offsetHeight - 100);
        // $('#newb123').height($('#newb').height() - $('#newb1').height() - 50);
        //$('#overlay').height($(window).height()); 
        //document.getElementById('overlay').style.display='block';
    }
    else {
        $('#model-dispose').modal({backdrop: 'static', keyboard: false});
        $('#model-dispose').modal('show');
        // divname = "newc";
        // $('#' + divname).width($(window).width() - 150);
        // $('#' + divname).height($(window).height() - 100);
        // $('#newc123').height($('#newc').height() - $('#newc1').height() - 50);
        $('#paps123d').append($('#newa123'));
    }

    // var newX = ($('#' + divname).width() / 2);
    // var newY = ($('#' + divname).height() / 2);

    // document.getElementById(divname).style.marginLeft = "-" + newX + "px";
    // document.getElementById(divname).style.marginTop = "-" + newY + "px";
    // document.getElementById(divname).style.display = 'block';
    // document.getElementById(divname).style.zIndex = 10;
}

function addCheckbox(containern, name) {
    var container = $('#' + containern);
    var inputs = container.find('input');
    var id = inputs.length + 1;
    if (id > 1)
        container.append("<br>");
    $('<input />', { type: 'checkbox', id: containern + 'cb' + id, value: name, disabled: true, onclick: 'chk_ia("' + containern + '",' + id + ')' }).appendTo(container);
    $('<label />', { 'for': containern + 'cb' + id, text: name }).appendTo(container);
}

function close_w(cnt) {
    var divname = "";
    if (cnt == 1) {
        divname = "newb";
        $('#model-pending-remarks').modal('hide');
    } else {
        divname = "newc";
        $('#model-dispose').modal('hide');
    }

        // document.getElementById(divname).style.display = 'none';
    if (cnt == 1) {
        check_parties();

    }
    enable_buttons();

}

//function to clear radio button
/*function clearAllRadios() {
    var radList = document.getElementsByName('nextCourt');
    for (var i = 0; i < radList.length; i++) {
        if(radList[i].checked) radList[i].checked = false;
    }
}*/


async function save_rec(cnt) {
    await updateCSRFTokenSync();

    var CSRF_TOKEN = 'CSRF_TOKEN';
    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

    var old_new = $('#old_new').val();
    var cn = "";
    var stat = "";
    var cr_head = "";
    var check_NMD = 0;
    var check_NMD_var = '';
    var concstr = '';
    var statusSide = cnt;
    var options = '';
    if (cnt == 1) {   /*if(checkNextCourt()==false)
        exit(0);*/
        var div1 = "chkp";
        var div2 = "hdremp";
        cn = $('#tmp_casenop').val();
        stat = "P";
        cr_head = '<b><font color="blue">';
        var div3 = "chkcnp";
        /*nextCourt=$('input[name=nextCourt]:checked').val();
        if(nextCourt===undefined)
            nextCourt='J';*/
        //alert(nextCourt);
    }
    else {
        var div1 = "chkd";
        var div2 = "hdremd";
        cn = $('#tmp_casenod').val();
        subh = $('#tmp_casenosub').val();
        stat = "D";
        cr_head = '<b><font color="red">';
        var div3 = "chkcnd";
    }

    //        var div1 = "chkp";
    //        var div2 = "hdremp";
    //        cn = $('#tmp_casenop').val();
    //        stat = "P";
    //        cr_head = '<b><font color="blue">';
    if ($('#connected').length) {
        var data = $('#connected').val();
        data = data.replace(/,\s*$/, "");

        if (data != "") {
            $("input[type='checkbox'][name^='" + div3 + "']").each(function () {
                if ($(this).is(':checked')) {
                    concstr += $(this).val() + ',';
                }

            });
        }
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
            check_NMD++;
            // alert(document.getElementById($(this).attr('id')));
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
                //Added by preeti on 29.05.2019 so that user cannot select list on fixed month and week commencing at a time in any matter
                options = options + "," + cval[0];
                //end
                if (cval[0] == 180) {
                    check_NMD_var = 'yes';
                }
            }
            if (chk_val[0] == 190 || chk_val[0] == 181 || chk_val[0] == 204 || chk_val[0] == 205) {
                var t_d = $("#" + div2 + chk_val[0] + "_1").val();
                var t_m = $("#" + div2 + chk_val[0] + "_2").val();
                var t_y = $("#" + div2 + chk_val[0] + "_3").val();
                if (t_d == '') t_d = 0;
                if (t_m == '') t_m = 0;
                if (t_y == '') t_y = 0;
                crem = "D:" + t_d + ",W:" + t_m + ",M:" + t_y;
            } else {
                crem = $("#" + div2 + chk_val[0]).val();
            }
            str_new += cval[0] + "|" + crem + "!";
            str_caseval += cval[0] + "|" + crem + "^^";
            cr_head += cval[1];
            if (crem != "")
                cr_head += ' [' + crem + ']';
            cr_head += '<br>';
        }
    });
    //Added by preeti on 29.05.2019 so that user cannot select list on fixed month and week commencing at a time in any matter
    options = options.substring(1);
    options1 = options.split(",");
    for (var i = 0; i < options1.length; i++) {
        if ("68" == options1[i]) {
            for (var j = i + 1; j < options1.length; j++) {
                if ("21" == options1[j]) {
                    alert("Please select only one option from List on fixed month and Week Commencing.");
                    isfalse = 1;
                }
            }
        }
    }
    //end
    if (str_new == "") {
        isfalse = 1;
        if (stat == 'D')
            var status1 = 'Disposal';
        if (stat == 'P')
            var status1 = 'Pending';
        alert("Select atleast one " + status1 + " Case Remark");
    }
    // if(check_NMD_var=='yes' && check_NMD==1){ alert('Please select other remark alongwith List on NMD.'); isfalse = 1; }    
    cr_head += '</font></b>';
    //str2=str2+"<tr><td>"+cntr+"</td><td>"+document.getElementById("brd"+filno).innerHTML+"</td><td>"+document.getElementById("cs"+filno).innerHTML+"</td><td>"+document.getElementById("pn"+filno).innerHTML+"</td><td>"+document.getElementById("rn"+filno).innerHTML+"</td><td>"+document.getElementById("pad"+filno).innerHTML+"</td><td>"+document.getElementById("rad"+filno).innerHTML+"</td></tr>";
    if (isfalse == 0) {
        $("input.pdbutton").attr("disabled", true);
        
        

        var url = base_url + "/Judicial/PrevCaseRemarks/insert_rec_an";
        var http = new getXMLHttpRequestObject();
        //var xhr2=getXMLHTTP();
        var str1 = "";
        var dt = document.getElementById("dtd").value;
        //  var hdt = document.getElementById("hdate").value;
        //  var ucode = document.getElementById('hd_ud').value;
        //  var uip = document.getElementById('hd_ipadd').value;
        //  var umac = document.getElementById('hd_macadd').value;
        //var msg_t=document.getElementById("msgbox").value;
        var dt1 = dt.split("-");
        var dt_new = dt1[2] + "-" + dt1[1] + "-" + dt1[0];
        // var nextCourt=nextCourt;
        //        var hdt1 = hdt.split("-");
        //        var hdt_new = hdt1[2] + "-" + hdt1[1] + "-" + hdt1[0];
        //var str="insert_rec_an.php?str="+str1+"&dt="+dt_new;
        //alert(str);
        str1 = document.getElementById("jcodes").value + "|" + document.getElementById("mh").value + "|" + document.getElementById("clno").value + "|";
        str_new = cn + "#" + stat + "#" + str_new;
        //alert(str_new);
        //alert(str1);
        var parameters = "str=" + str_new;
        parameters += "&str1=" + str1;
        parameters += "&dt=" + dt_new;
        parameters += "&hdt=" + dt_new;
        //parameters += "&concstr=" + concstr;
        //        parameters += "&uip=" + uip;
        //        parameters += "&umac=" + umac;
        parameters += "&old_new=" + old_new;
        // parameters+="nextCourt="+nextCourt;
        parameters += "statusSide=" + statusSide;
        parameters += "&sno=" + document.getElementById("sno").value;
        //alert("param"+parameters);
        //        http.open("POST", url, true);
        //Send the proper header information along with the request

        $.ajax({
            type: "POST",
            url: url,
            data: {
                str: str_new,
                str1: str1,
                dt: dt_new,
                hdt: dt_new,
                old_new: old_new,
                concstr: concstr,
                //nextCourt:nextCourt,
                statusSide: statusSide,
                sno: document.getElementById("sno").value,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },

            success: function (msg) {
                if (msg == '') {
                    //                    document.getElementById("caseval" + cn).value = str_caseval;
                    //                    document.getElementById("cr_span" + cn).innerHTML = cr_head;
                    //                   save_rec1(cn, dt_new, jc);
                    //save_parties1();
                    $("input.pdbutton").attr("disabled", false);
                }
                else {
                    //   alert(data);
                    enable_buttons();
                    $("input.pdbutton").attr("disabled", false);
                }

                // Reload the page
                window.location.reload();
                // $("input[name=btnGetR]").click();
            },

            error: function () {
                alert("ERROR");
            }

        });

        //        http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        //        http.setRequestHeader("Content-length", parameters.length);
        //        http.setRequestHeader("Connection", "close");
        //        http.onreadystatechange = function () {//Handler function for call back on state change.
        //            if (http.readyState == 4) {
        //                var data = http.responseText;
        //                //alert(data);
        //                if (data != ""){
        //                    alert(data);
        //                    enable_buttons();
        //                }
        //                else
        //                {
        //                    alert("Data save Successfully");
        ////                      document.getElementById("caseval"+cn).value=str_caseval;
        //                    get_list_date();
        ////                      document.getElementById("cr_span"+cn).innerHTML=cr_head;
        //                }
        //            }
        //        };
        //        http.send(parameters);
        close_w(cnt);
    }
}
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

function noinput(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    //alert(charCode);
    if (charCode == 9) {
        return true;
    }
    return false;
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
    //    alert(containern);
    //    alert(id);
}
// $(".cls_chkd").click(function(){

$(document).on('click', '.cls_chkd', function () {
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

function setFocusToTextBox(cb) {
    var textbox = document.getElementById('hdremp' + cb);
    $("#hdremp" + cb).focus();
    textbox.scrollIntoView();
}

function textformate(cb) {
    var msg = 'Please Enter Numeric Value';
    if (cb == 144)
        var y = document.getElementById('hdremd' + cb).value;
    else if (cb == 190 || cb == 181 || cb == 204 || cb == 205) {
        document.getElementById('hdremp' + cb + "_1").value = document.getElementById('hdremp' + cb + "_1").value.trim();
        document.getElementById('hdremp' + cb + "_2").value = document.getElementById('hdremp' + cb + "_2").value.trim();
        document.getElementById('hdremp' + cb + "_3").value = document.getElementById('hdremp' + cb + "_3").value.trim();
        if (document.getElementById('hdremp' + cb + "_1").value == '') document.getElementById('hdremp' + cb + "_1").value = '0';
        if (document.getElementById('hdremp' + cb + "_2").value == '') document.getElementById('hdremp' + cb + "_2").value = '0';
        if (document.getElementById('hdremp' + cb + "_3").value == '') document.getElementById('hdremp' + cb + "_3").value = '0';
        if (isNaN(document.getElementById('hdremp' + cb + "_1").value) || isNaN(document.getElementById('hdremp' + cb + "_2").value) || isNaN(document.getElementById('hdremp' + cb + "_3").value)) {
            alert('Please Enter DAYS or WEEK or MONTH in Numbers!');
            setFocusToTextBox(cb);
            return false;
        }
        var y = "D:" + document.getElementById('hdremp' + cb + "_1").value + "W:" + document.getElementById('hdremp' + cb + "_2").value + "M:" + document.getElementById('hdremp' + cb + "_3").value;
    }
    else
        var y = document.getElementById('hdremp' + cb).value;
    //         alert(y);
    x = y.split(",");
    if (cb == 72) {
        for (var i = 0; i < x.length; i++) {
            var iChars = "~`!#$%^&*+=[]\\\';{}|\":<>?";
            for (var j = 0; j < x[i].length; j++) {
                if (iChars.indexOf(x[i].charAt(j)) != -1) {
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
            //                    break;
            //                case 'AC':
            //                    break;
            //                case 'AR':
            //                    break;
            //                case 'ARBA':
            //                    break;
            //                case 'ARBC':
            //                    break;
            //                case 'CA':
            //                    break;
            //                case 'CEA':
            //                    break;
            //                case 'CER':
            //                    break;
            //                case 'CESR':
            //                    break;
            //                case 'COMA':
            //                    break;
            //                case 'COMP':
            //                    break;
            //                case 'COMPA':
            //                    break;
            //                case 'CONA':
            //                    break;
            //                case 'CONC':
            //                    break;
            //                case 'CONCR':
            //                    break;
            //                case 'CONT':
            //                    break;
            //                case 'CONTR':
            //                    break;
            //                case 'CR':
            //                    break;
            //                case 'CRA':
            //                    break;
            //                case 'CRR':
            //                    break;
            //                case 'CRRE':
            //                    break;
            //                case 'CRRF':
            //                    break;
            //                case 'CRRFC':
            //                    break;
            //                case 'CS':
            //                    break;
            //                case 'EP':
            //                    break;
            //                case 'FA':
            //                    break;
            //                case 'FEMA':
            //                    break;
            //                case 'GTR':
            //                    break;
            //                case 'ITA':
            //                    break;
            //                case 'ITR':
            //                    break;
            //                case 'LPA':
            //                    break;
            //                case 'MA':
            //                    break;
            //                case 'MACE':
            //                    break;
            //                case 'MACOM':
            //                    break;
            //                case 'MACTR':
            //                    break;
            //                case 'MAIT':
            //                    break;
            //                case 'MAVAT':
            //                    break;
            //                case 'MCC':
            //                    break;
            //                case 'MCOMA':
            //                    break;
            //                case 'MCP':
            //                    break;
            //                case 'MCRC':
            //                    break;
            //                case 'MCRP':
            //                    break;
            //                case 'MP':
            //                    break;
            //                case 'MWP':
            //                    break;
            //                case 'OTA':
            //                    break;
            //                case 'RP':
            //                    break;
            //                case 'SA':
            //                    break;
            //                case 'SLP':
            //                    break;
            //                case 'STR':
            //                    break;
            //                case 'TR':
            //                    break;
            //                case 'VATA':
            //                    break;
            //                case 'WA':
            //                    break;
            //                case 'WP':
            //                    break;
            //                case 'WPS':
            //                    break;
            //                case 'WTA':
            //                    break;
            //                case 'WTR':
            //                    break;
            //                default:
            //                {
            //                    alert("Please Enter proper Case ");
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
        switch (cb) {
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
                break;
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

        if (y == '' || isNaN(y)) {
            alert(msg);
            setFocusToTextBox(cb);
            return false;
        }
        if (cb == 53 || cb == 25 || cb == 149) {
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
            if (y > 12 && y !== 0) {
                alert('Please Enter Numeric Value Between 1 TO 12 Which Is Month Of The Year');
                setFocusToTextBox(cb);
                return false;
            }
        }
    }
    var chk_ia = false;
    var textempty = false;
    if (cb == 22 || cb == 26 || cb == 95 || cb == 142) {
        $("input[type='checkbox'][id^='" + "hdremp" + cb + "_divcb" + "']").each(function () {
            var isChecked = document.getElementById($(this).attr('id')).checked;
            if (isChecked) {
                chk_ia = true;
            }
            var textvalue = document.getElementById("hdremp" + cb).value;
            //alert("text "+textvalue);
            if (textvalue == '' || textvalue == null) {
                textempty = true;
            }
        });
        if (!(chk_ia) && textempty) {
            alert('Please Select IA from the list');
            return false;
        }
    }
    return true;
}

$(document).on('click', '.cls_chkp', function () {
    //function cls_chkp()
    //{
    //  alert('chk_val');
    var chk_val = $(this).val().split("|");
    //   alert(chk_val);
    var isChecked = document.getElementById($(this).attr('id')).checked;
    // alert(isChecked);
    // debugger;
    if (isChecked) {
        if (chk_val[0] == 22 || chk_val[0] == 26 || chk_val[0] == 95 || chk_val[0] == 142) {
            $("input[type='checkbox'][id^='" + "hdremp" + chk_val[0] + "_divcb" + "']").each(function () {
                $(this).attr('disabled', false);
                $("#hdremp" + chk_val[0]).attr('readonly', false);
            });
        }
        else {
            if (chk_val[0] !== 21 && chk_val[0] !== 24 && chk_val[0] !== 59 && chk_val[0] !== 131 && chk_val[0] !== 129 && chk_val[0] !== 70 && chk_val[0] !== 91)
                if (chk_val[0] == 190 || chk_val[0] == 181 || chk_val[0] == 204 || chk_val[0] == 205) {
                    $("#hdremp" + chk_val[0] + "_1").attr('readonly', false);
                    $("#hdremp" + chk_val[0] + "_1").css('background-color', '#fff');
                    $("#hdremp" + chk_val[0] + "_1").css('border', '1px solid #ccc');
                    $("#hdremp" + chk_val[0] + "_2").attr('readonly', false);
                    $("#hdremp" + chk_val[0] + "_2").css('background-color', '#fff');
                    $("#hdremp" + chk_val[0] + "_2").css('border', '1px solid #ccc');
                    $("#hdremp" + chk_val[0] + "_3").attr('readonly', false);
                    $("#hdremp" + chk_val[0] + "_3").css('background-color', '#fff');
                    $("#hdremp" + chk_val[0] + "_3").css('border', '1px solid #ccc');
                }
                else if (chk_val[0] == 70 || chk_val[0] == 24 || chk_val[0] == 21) {
                    $("#hdremp" + chk_val[0]).attr('readonly', true);
                }
                else {
                    $("#hdremp" + chk_val[0]).attr('readonly', false);
                    $("#hdremp" + chk_val[0]).css('background-color', '#fff');
                    $("#hdremp" + chk_val[0]).css('border', '1px solid #ccc');
                    $("#hdremp" + chk_val[0]).focus();
                    if (chk_val[0] == 91) {
                        $("#partybutton").attr('disabled', false);
                    }
                }
        }
        //
        //               if(chk_val[0]==21 || chk_val[0]==24 || chk_val[0]==59 || chk_val[0]==70)  
        //               {
        //                var thisele = document.getElementById('hdremp'+chk_val[0]);
        //               fnInitCalendar(thisele, 'hdremp', 'style=calendar.css,expiry=true,instance=single,close=true');
        //               _fnSetCalendar(thisele, 'hdremp', 'style=calendar.css,expiry=true,instance=single,close=true'); 
        //               }
    }
    else {
        if (chk_val[0] == 22 || chk_val[0] == 26 || chk_val[0] == 95 || chk_val[0] == 142) {
            $("input[type='checkbox'][id^='" + "hdremp" + chk_val[0] + "_divcb" + "']").each(function () {
                $(this).attr('disabled', true);
                $("#hdremp" + chk_val[0]).attr('readonly', true);
            });
        }
        else {
            if (chk_val[0] == 190 || chk_val[0] == 181 || chk_val[0] == 204 || chk_val[0] == 205) {
                $("#hdremp" + chk_val[0] + "_1").attr('readonly', true);
                $("#hdremp" + chk_val[0] + "_1").css('background-color', '#F5F5F5');
                $("#hdremp" + chk_val[0] + "_1").css('border', '1px solid #ccc');
                $("#hdremp" + chk_val[0] + "_2").attr('readonly', true);
                $("#hdremp" + chk_val[0] + "_2").css('background-color', '#F5F5F5');
                $("#hdremp" + chk_val[0] + "_2").css('border', '1px solid #ccc');
                $("#hdremp" + chk_val[0] + "_3").attr('readonly', true);
                $("#hdremp" + chk_val[0] + "_3").css('background-color', '#F5F5F5');
                $("#hdremp" + chk_val[0] + "_3").css('border', '1px solid #ccc');
            }
            else {
                $("#hdremp" + chk_val[0]).attr('readonly', true);
                $("#hdremp" + chk_val[0]).css('background-color', '#F5F5F5');
                $("#hdremp" + chk_val[0]).css('border', '1px solid #ccc');
            }
        }
        if (chk_val[0] == 91) {
            $("#partybutton").attr('disabled', true);
        }
    }
    //}
});

function get_srllist() {
    $.ajax({
        url: 'load_data.php',
        cache: false,
        async: true,
        beforeSend: function () {
            $('#ggg').html('<table widht="100%" align="center"><tr><td><img src="preloader.gif"/></td></tr></table>');
        },
        data: { f_no: f_no, opt: opt, lst_case: lst_case, txtno: txtno, txtyear: txtyear },
        type: 'POST',
        success: function (data, status) {
            //           alert(data);
            $('#ggg').html(data);
            $("#tabViewdhtmlgoodies_tabView1_0").show();
        },
        error: function (xhr) {
            alert("Error: " + xhr.status + " " + xhr.statusText);
        }
    });
}

function get_stagecode() {
    var cs_tp = $('#cs_tp').val();
    var hd_nature = $('#hd_nature').val();
    if (hd_nature == 'W') {
        if (cs_tp != '57') {
            hd_nature = 'WC';
            $('#btnAll7').css('display', 'none');
            $('#btnAll5').css('display', 'inline');
            $('#btnAll4').css('display', 'inline');
        }
        else {
            hd_nature = 'WA';
            $('#btnAll5').css('display', 'none');
            $('#btnAll4').css('display', 'none');
            $('#btnAll7').css('display', 'inline');
        }
    }
    else if (hd_nature == 'C') {
        if (cs_tp != '20') {
            $('#btnAll2').css('display', 'none');
            $('#btnAll1').css('display', 'inline');
        }
        else {
            hd_nature = 'EP';
            $('#btnAll2').css('display', 'inline');
            $('#btnAll1').css('display', 'none');
        }
    }
    var ddl_bench = $('#ddl_bench').val();
    //    alert(hd_nature);
    //    alert(ddl_bench);
    //     alert(hd_nature);
    $.ajax({
        url: hd_folder + '/stagecode.php',
        cache: false,
        async: true,
        data: { hd_nature: hd_nature, ddl_bench: ddl_bench },
        type: 'POST',
        success: function (data, status) {
            $('#srcList').html(data);
            $('#ddl_m').css('display', 'none');
            $('#dv_motion_final').css('display', 'block');
            if (hd_nature == 'R') {
                $('#btnAll3').css('background-color', 'black');
                $('#btnAll3').css('color', 'white');
            }
            else if (hd_nature == 'C') {
                $('#btnAll1').css('background-color', 'black');
                $('#btnAll1').css('color', 'white');
            }
            else if (hd_nature == 'WC') {
                $('#btnAll4').css('background-color', 'black');
                $('#btnAll4').css('color', 'white');
            }
            else if (hd_nature == 'WA') {
                $('#btnAll7').css('background-color', 'black');
                $('#btnAll7').css('color', 'white');
            }
            else if (hd_nature == 'EP') {
                $('#btnAll2').css('background-color', 'black');
                $('#btnAll2').css('color', 'white');
            }

            $('#ddl_stage_c1').html('<option value="">Select</option>');
            $('#ddl_stage_c2').html('<option value="">Select</option>');
            $('#ddl_stage_c3').html('<option value="">Select</option>');
        },
        error: function (xhr) {
            alert("Error: " + xhr.status + " " + xhr.statusText);
        }
    });
}

function get_stagecode1() {
    var hd_nature = $('#hd_nature').val();
    var ddl_bench = $('#ddl_bench').val();
    var srcList = $('#srcList').val();
    $.ajax({
        url: hd_folder + '/stagecode1.php',
        cache: false,
        async: true,
        data: { srcList: srcList, hd_nature: hd_nature, ddl_bench: ddl_bench },
        type: 'POST',
        success: function (data, status) {
            $('#ddl_stage_c1').html(data);
        },
        error: function (xhr) {
            alert("Error: " + xhr.status + " " + xhr.statusText);
        }
    });
}

function get_cor_cat(str) {
    //     alert(str);
    if (str == 'btnAll3') {
        $('#hd_nature').val('R');
    }
    else if (str == 'btnAll6') {
        $('#hd_nature').val('PIL');
    }
    else if (str == 'btnAll1') {
        $('#hd_nature').val('C');
    }
    else if (str == 'btnAll4') {
        $('#hd_nature').val('WC');
    }
    else if (str == 'btnAll5') {
        $('#hd_nature').val('WR');
    }
    else if (str == 'btnAll2') {
        $('#hd_nature').val('EP');
    }
    $('#' + str).css({ "background-color": "black", "color": "white" });
    var ddl_m_f = $('#ddl_m_f').val();

    for (var i = 1; i < 8; i++) {
        //              alert($('#btnAll'+i)) ;
        if (('btnAll' + i) != str) {
            $('#btnAll' + i).css('background-color', 'white');
            $('#btnAll' + i).css('color', 'black');
        }
    }
    $('#ddl_stage_c1').html('<option value="">Select</option>');
    $('#ddl_stage_c2').html('<option value="">Select</option>');
    $('#ddl_stage_c3').html('<option value="">Select</option>');
    //               $('#btnAll'+i).css('background-color','black');
    //                $('#btnAll'+i).css('color','white');
    get_heading_types(ddl_m_f);
}

function get_stagecode2() {
    var hd_nature = $('#hd_nature').val();
    var ddl_bench = $('#ddl_bench').val();
    var srcList = $('#srcList').val();
    var ddl_stage_c1 = $('#ddl_stage_c1').val();
    $.ajax({
        url: hd_folder + '/stagecode2.php',
        cache: false,
        async: true,
        data: { srcList: srcList, hd_nature: hd_nature, ddl_bench: ddl_bench, ddl_stage_c1: ddl_stage_c1 },
        type: 'POST',
        success: function (data, status) {
            $('#ddl_stage_c2').html(data);
        },
        error: function (xhr) {
            alert("Error: " + xhr.status + " " + xhr.statusText);
        }
    });
}

function get_stagecode3() {
    var hd_nature = $('#hd_nature').val();
    var ddl_bench = $('#ddl_bench').val();
    var srcList = $('#srcList').val();
    var ddl_stage_c1 = $('#ddl_stage_c1').val();
    var ddl_stage_c2 = $('#ddl_stage_c2').val();
    //      alert(srcList);
    //          alert(ddl_stage_c1);
    //          alert(ddl_stage_c2);
    $.ajax({
        url: hd_folder + '/stagecode3.php',
        cache: false,
        async: true,
        data: { srcList: srcList, hd_nature: hd_nature, ddl_bench: ddl_bench, ddl_stage_c1: ddl_stage_c1, ddl_stage_c2: ddl_stage_c2 },
        type: 'POST',
        success: function (data, status) {
            $('#ddl_stage_c3').html(data);
        },
        error: function (xhr) {
            alert("Error: " + xhr.status + " " + xhr.statusText);
        }
    });
}

function call_div_ks(fil_no, idd) {
    var ddl_d_a = $('#ddl_d_a').val();
    var txt_cl_dt = $('#txt_cl_dt').val();
    var jud1 = $('#jud1').val();
    var jud2 = $('#jud2').val();
    var ddl_m_f = $('#ddl_m_f').val();
    var listorder = $('#listorder').val();
    var hd_hdt_hear = $('#hd_hdt_hear').val();
    var ddl_bench = $('#ddl_bench').val();
    var hd_fil_dt_dt = $('#hd_fil_dt_dt').val();
    var ddl_d_a = $('#ddl_d_a').val();

    var ddl_m = '';
    if (ddl_m_f == 'M') {
        ddl_m = $('#ddl_m').val()
    }
    else if (ddl_m_f == 'F') {
        var srcList = $('#srcList').val();
        var ddl_stage_c1 = $('#ddl_stage_c1').val();
        var ddl_stage_c2 = $('#ddl_stage_c2').val();
        var ddl_stage_c3 = $('#ddl_stage_c3').val();
        if (srcList != '' && ddl_stage_c1 != '' && ddl_stage_c2 != '' && ddl_stage_c3 != '')
            ddl_m = ddl_stage_c3;
        else if (srcList != '' && ddl_stage_c1 != '' && ddl_stage_c2 != '' && ddl_stage_c3 == '')
            ddl_m = ddl_stage_c2;
        else if (srcList != '' && ddl_stage_c1 != '' && ddl_stage_c2 == '' && ddl_stage_c3 == '')
            ddl_m = ddl_stage_c1;
        else if (srcList != '' && ddl_stage_c1 == '' && ddl_stage_c2 == '' && ddl_stage_c3 == '')
            ddl_m = srcList;
    }
    var hd_cur_dt1 = $('#hd_fil_dt_dt').val();
    //         alert(hd_cur_dt1);
    var dt4 = parseInt(hd_cur_dt1.substring(0, 2), 10);
    var mon4 = parseInt(hd_cur_dt1.substring(3, 5), 10) - 1;
    var yr4 = parseInt(hd_cur_dt1.substring(6, 10), 10);
    var date4 = new Date(yr4, mon4, dt4);
    var dt3 = parseInt(txt_cl_dt.substring(0, 2), 10);
    var mon3 = parseInt(txt_cl_dt.substring(3, 5), 10) - 1;
    var yr3 = parseInt(txt_cl_dt.substring(6, 10), 10);
    var date3 = new Date(yr3, mon3, dt3);
    //alert(date3);
    //alert(date4);
    var str_nxt_dt = 0;
    var tot_next_dt = $('#tot_next_dt').val();
    //        alert(tot_next_dt);
    var ex_tot_next_dt = tot_next_dt.split(',');

    for (var i = 0; i < ex_tot_next_dt.length; i++) {
        if (ex_tot_next_dt[i] == txt_cl_dt) {
            str_nxt_dt = 1;
        }
    }
    if (str_nxt_dt == 1) {
        alert("Cause list Date Already entered");
    }
    else {
        var hd_ad_x = '';
        if (ddl_d_a == '') {
            alert("Please select Heldup/Admit");
        }
        else if (txt_cl_dt == '') {
            alert("Please select Causelist Date");
        }
        else if (date4 >= date3) {
            alert("Next Date Should be greather than filing Date");
        }
        else if (ddl_m_f == '') {
            alert("Please select Motion or Final");
        }
        else if (ddl_bench == '') {
            alert("Please select Bench");
        }
        else if (ddl_bench == '') {
            alert("Please select Bench");
        }
        else if (ddl_m_f == 'M' && ddl_m == '') {
            alert("Please select Stage");
        }
        else if (ddl_m_f == 'F' && srcList == '') {
            alert("Please select Stage");
        }
        else if (ddl_bench == 'S' && jud1 == '0') {
            alert("Please select Judge");
        }
        else if (ddl_bench == 'D' && (jud1 == '0' || jud2 == '0')) {
            alert("Please select both Judge");
        }

        else {
            $('#' + idd).attr('disabled', true);

            if (ddl_d_a == 0) {
                hd_ad_x = '/save_upd_mod_old.php';
            }
            else {
                hd_ad_x = '/save_upd_mod_new.php';
            }
            //                  alert(ddl_bench); 
            $.ajax({

                url: hd_folder + hd_ad_x,
                cache: false,
                async: true,
                data: { fil_no: fil_no, jud1: jud1, jud2: jud2, ddl_m_f: ddl_m_f, ddl_m: ddl_m, txt_cl_dt: txt_cl_dt, listorder: listorder, hd_ud: hd_ud, hd_hdt_hear: hd_hdt_hear, ddl_bench: ddl_bench },
                type: 'POST',
                success: function (data, status) {
                    //    var judges=jud1+','+jud2;
                    alert(data);
                    //      call_div('999',fil_no,idd,'1',txt_cl_dt,'',judges,ddl_m_f,'1','999');
                    get_list_date();
                },
                error: function (xhr) {
                    alert("Error: " + xhr.status + " " + xhr.statusText);
                }
            });
        }
    }
}
function show_party_div() {
    //if(document.getElementById("newparty").innerHTML=='')
    make_party_div(1);
    // document.getElementById("newparty").style.display = 'block';  
}
function make_party_div(chk) {

    //   $('#newb').width($(window).width() - 150);
    //   $('#newb').height($(window).height() - 150);

    var divname = "newb";
    //        $('#' + divname).width($(window).width() - 150);
    //        $('#' + divname).height($(window).height() - 120);
    //        $('#newb123').height($('#newb').height() - $('#newb1').height() - 50);


    var newX = ($('#' + divname).width() / 2);
    var newY = ($('#' + divname).height() / 2);

    //    document.getElementById('newparty').style.marginLeft = "-" + newX + "px";
    document.getElementById('newparty').style.marginTop = "-" + newY + "px";
    //    document.getElementById(divname).style.display = 'block';
    document.getElementById('newparty').style.zIndex = 10;
    $('#newparty').height($('#newb').height() - 100);

    var filno = $('#tmp_casenop').val();
    var cldt = document.getElementById("dtd").value;
    var dt1 = cldt.split("-");
    var dt_new = dt1[2] + "-" + dt1[1] + "-" + dt1[0];
    var xhr2 = getXMLHTTP();
    var str = "../reader/get_parties.php?filno=" + filno + "&cldt=" + dt_new;
    // alert(str);
    xhr2.open("GET", str, true);
    xhr2.onreadystatechange = function () {
        if (xhr2.readyState == 4 && xhr2.status == 200) {
            var data = xhr2.responseText;
            // document.getElementById('paps').value=data;
            //    alert(data);
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
                var cntr = 2;
                for (var i = 0; i < (p1.length - 1); i++) {
                    snoo++;
                    p2 = p1[i].split("|");
                    bgc = "#F8F9FC";
                    if ((snoo % cntr) != 0) {
                        div_output += '<tr bgcolor="' + bgc + '" style="padding:0;">';
                    }
                    if (p2[3] == "F") {
                        div_output += '<td style="padding:0;" width="5%" align=center><b>' + p2[1] + p2[2] + '</b></td><td align="left" style="padding:0;" width="45%"><input class="cls_party" type="checkbox" name="party' + p2[1] + p2[2] + '" id="party' + p2[1] + p2[2] + '" value="' + p1[i] + '" checked="checked"/><label style="font-size:8pt;" for="party' + p2[1] + p2[2] + '">' + p2[0] + '</label></td>';
                    }
                    else {
                        div_output += '<td style="padding:0;" width="5%" align=center><b>' + p2[1] + p2[2] + '</b></td><td align="left" style="padding:0;" width="45%"><input class="cls_party" type="checkbox" name="party' + p2[1] + p2[2] + '" id="party' + p2[1] + p2[2] + '" value="' + p1[i] + '"/><label style="font-size:8pt;" for="party' + p2[1] + p2[2] + '">' + p2[0] + '</label></td>';
                    }
                    if ((snoo % cntr) == 0)
                        div_output += '</tr>';
                }
                div_output += '</table><div id="buttonbottom" style="width: 100%; position:absolute;bottom:0; text-align:center;"><input name="sparty" type="button" value="SAVE" onclick="save_parties();"/>&nbsp;<input name="cparty" type="button" value="CLOSE" onclick="close_party();"/></div>';
            }
            //alert(div_output);
            document.getElementById("newparty").innerHTML = div_output;
            //    document.getElementById("newparty").style.display = 'block';
            //alert(chk);
            //    if(chk==1)
            document.getElementById("newparty").style.display = 'block';
            //else
            //    document.getElementById("newparty").style.display = 'none';
        }
    }// inner function end
    xhr2.send(null);
}

function close_party() {
    document.getElementById("newparty").style.display = 'none';
}


async function check_parties() {

    await updateCSRFTokenSync();

    var CSRF_TOKEN = 'CSRF_TOKEN';
    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

    var url = base_url + "/Judicial/PrevCaseRemarks/check_parties";
    var http = new getXMLHttpRequestObject();
    var cn = $('#tmp_casenop').val();
    var dt = document.getElementById("dtd").value;
    var dt1 = dt.split("-");
    var dt_new = dt1[2] + "-" + dt1[1] + "-" + dt1[0];
    //        var parameters = "dt=" + dt_new;
    //        parameters += "&cn=" + cn;
    $.ajax({
        type: "POST",
        url: url,
        data: {
            dt: dt_new,
            cn: cn,
            CSRF_TOKEN: CSRF_TOKEN_VALUE
        },
        success: function (msg) {
        },
        error: function () {
            alert("ERROR");
        }
    });
    //        http.open("POST", url, true);
    ////Send the proper header information along with the request
    //        http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    //        http.setRequestHeader("Content-length", parameters.length);
    //        http.setRequestHeader("Connection", "close");
    //        http.onreadystatechange = function () {//Handler function for call back on state change.
    //            if (http.readyState == 4) {
    //                var data = http.responseText;
    //               // alert(data);
    //        }
    //        
    //    };
    //    http.send(parameters);
}

function save_parties() {
    var str = "";
    $("input[type='checkbox'][name^='party']").each(function () {
        var isChecked = document.getElementById($(this).attr('id')).checked;
        if (isChecked) {
            var strnew = $(this).val().split("|");

            str += strnew[1] + "|" + strnew[2] + "^^";
        }
    });

    if (str != "") {
        var url = "../reader/insert_parties.php";
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
        http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        http.setRequestHeader("Content-length", parameters.length);
        http.setRequestHeader("Connection", "close");
        http.onreadystatechange = function () {//Handler function for call back on state change.
            if (http.readyState == 4) {
                var data = http.responseText;
                if (data == "") {
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

/*
function checkNextCourt() {
    var nextCourt = $('input[name=nextCourt]:checked').val();
    var prevCourt = document.getElementById("prvCourt").value;
    if (prevCourt == 'C' || prevCourt == 'R')
    {
        if(nextCourt===undefined)
        {
            alert("Select One Option for Proposed to be listed in");
            return false;
        }
    }
    return true;
}*/


$(document).ready(function () {
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
        }
        else {
            alert('Please Select Any Option');
            return false;
        }

        $.ajax({
            type: 'POST',
            url: "get_prev_case_remarks.php",
            beforeSend: function (xhr) {
                $("#dv_res1").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='../images/load.gif'></div>");
            },
            data: { d_no: diaryno, d_yr: diaryyear, ct: cstype, cn: csno, cy: csyr }
        })
            .done(function (msg) {
                $("#dv_res1").html(msg);
                //    var fno = filling_no;
                //      if(document.getElementById('ian_cx'+fno).value=='')
                //             document.getElementById('dv_hd_ia').style.display='none';
                //         else
                //            document.getElementById('sp_ffno' + fno).innerHTML = document.getElementById('ian_cx' + fno).value;

                var curr_date = document.getElementById("curr_date").value;
                var date = new Date(curr_date);
                date.setDate(date.getDate() + 1);
                var date1 = new Date(curr_date);
                date1.setDate(date1.getDate());
                /* $("#hdremp21").datepicker({dateFormat: "dd-mm-yy", numberOfMonths: 2, minDate: date});
                 $("#hdremp24").datepicker({dateFormat: "dd-mm-yy", numberOfMonths: 2, minDate: date});
     //            $("#hdremp180").datepicker({dateFormat: "dd-mm-yy", numberOfMonths: 2, minDate: date});
                 $("#hdremp59").datepicker({dateFormat: "dd-mm-yy", numberOfMonths: 2, minDate: date});
                 $("#hdremp91").datepicker({dateFormat: "dd-mm-yy", numberOfMonths: 2, minDate: date1});
                 $("#hdremp129").datepicker({dateFormat: "dd-mm-yy", numberOfMonths: 2, minDate: date});
                 $("#hdremp131").datepicker({dateFormat: "dd-mm-yy", numberOfMonths: 2, minDate: date});
                 $("#hdremp70").datepicker({dateFormat: "dd-mm-yy", numberOfMonths: 2, minDate: date});
     //$("#hdate").datepicker({ dateFormat: "dd-mm-yy", numberOfMonths: 2, minDate: new Date() });
                 $("#hdate").datepicker({dateFormat: "dd-mm-yy", numberOfMonths: 2, maxDate: new Date(curr_date)});
     
                 $("#hdremp21").keypress(function (e) {
                     e.preventDefault();
                 });
                 $("#hdremp24").keypress(function (e) {
                     e.preventDefault();
                 });
     //            $("#hdremp180").keypress(function (e) {
     //                e.preventDefault();
     //            });            
                 $("#hdremp59").keypress(function (e) {
                     e.preventDefault();
                 });
                 $("#hdremp129").keypress(function (e) {
                     e.preventDefault();
                 });
                 $("#hdremp131").keypress(function (e) {
                     e.preventDefault();
                 });
                 $("#hdremp70").keypress(function (e) {
                     e.preventDefault();
                 });
                 $("#hdate").keypress(function (e) {
                     e.preventDefault();
                 });*/
                $("#hdremp21").addClass("dtp");
                $("#hdremp24").addClass("dtp");
                //    $("#hdremp180").addClass( "dtp" );
                $("#hdremp59").addClass("dtp");
                $("#hdremp91").addClass("dtp");
                $("#hdremp131").addClass("dtp");
                $("#hdremp70").addClass("dtp");
                $("#hdremp153").addClass("dtp");
                $("#hdate").addClass("dtp");
                //  get_subheading();
                //            $("#result2").html("");
            })
            .fail(function () {
                alert("ERROR, Please Contact Server Room");
            });
    });
});
