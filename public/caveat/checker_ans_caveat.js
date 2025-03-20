$(document).on("focus", "#m_act", function() {
    $("#m_act").autocomplete({
        source: "auto_checker.php?ctrl=1&ud=" + document.getElementById('hd_ud').value,
        width: 450,
        matchContains: true,
        minChars: 1,
        selectFirst: false,
        autoFocus: true
    });
});

$(document).on("focus", "#section", function() {
    $("#section").autocomplete({
        source: "auto_checker.php?ctrl=2&ud=" + document.getElementById('hd_ud').value,
        width: 450,
        matchContains: true,
        minChars: 1,
        selectFirst: false,
        autoFocus: true
    });
});

$(document).on("focus", "#order", function() {
    $("#order").autocomplete({
        source: "auto_checker.php?ctrl=3&ud=" + document.getElementById('hd_ud').value,
        width: 450,
        matchContains: true,
        minChars: 1,
        selectFirst: false,
        autoFocus: true
    });
});

$(document).on("focus", "#m_relief", function() {
    $("#m_relief").autocomplete({
        source: "auto_checker.php?ctrl=4&ud=" + document.getElementById('hd_ud').value,
        width: 450,
        matchContains: true,
        minChars: 1,
        selectFirst: false,
        autoFocus: true
    });
});




function getFileNo(str) {
    if (str.id == "txtcaseno")
        c = document.getElementById("txtcaseno");
    else
        c = document.getElementById("txtcaseno2");
    var e = c.value;
    var f = e.length;
    //alert(f);
    var f5;
    //alert(DV);
    //alert('length:'+ f);
    if (f == 1) {
        f5 = '0000' + e;
        c.value = f5;
    }
    if (f == 2) {
        f5 = '000' + e;
        c.value = f5;
    }
    if (f == 3) {
        f5 = '00' + e;
        c.value = f5;
    }
    if (f == 4) {
        f5 = '0' + e;
        c.value = f5;
    }

}

function get_detail_for_checker() {
    document.getElementById('hd_sp_a_rem').value = '';
    document.getElementById('hd_sp_b_rem').value = '';
    document.getElementById('hd_sp_c_rem').value = '';



    var d_no = document.getElementById('t_h_cno').value;
    var d_yr = document.getElementById('t_h_cyt').value;
    if (d_no == '') {
        alert("Please Enter Diary No.");
    } else if (d_yr == '') {
        alert("Please Enter Diary year");
    } else {

        var xmlhttp;
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        document.getElementById('dv_res1').innerHTML = '<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>';
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById('dv_res1').innerHTML = xmlhttp.responseText;

            }
        }

        xmlhttp.open("GET", base_url + "/Filing/Caveat/get_detail_for_checker_caveat?d_no=" + d_no + "&d_yr=" + d_yr, true);
        xmlhttp.send(null);
    }
}

function getcat(subjectId) {
    //alert(subjectId);
    chk_SCSC();
    if (subjectId == '171') {
        document.getElementById('hd_cat_t').style.display = 'table-cell';
    } else {
        document.getElementById('hd_cat_t').style.display = 'none';
        document.getElementById('m_ssub').value = '';
    }
    var xmlhttp;
    if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else { // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }


    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            // var txtAdeshika="txtAdeshika"+rowCount;
            document.getElementById('cat').innerHTML = xmlhttp.responseText;
            document.getElementById('subcat').innerHTML = "<option value='0'>Select</option>";
        }
    }
    // xmlhttp.open("GET","getReport51-II.php?seljudgename="+seljudgename+"&frm="+frm+"&toDate="+toDate,true);
    xmlhttp.open("GET", "getcat_ans.php?subjectId=" + subjectId, true);
    xmlhttp.send(null);


} //function

function getsubcat(subjectId, catId) {
    chk_SCSC();
    var xmlhttp;
    if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else { // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }


    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            // var txtAdeshika="txtAdeshika"+rowCount;
            document.getElementById('subcat').innerHTML = xmlhttp.responseText;

        }
    }
    // xmlhttp.open("GET","getReport51-II.php?seljudgename="+seljudgename+"&frm="+frm+"&toDate="+toDate,true);
    xmlhttp.open("GET", "getsubcat_ans.php?subject=" + subjectId + "&cat=" + catId, true);
    xmlhttp.send(null);



} //function getsubca

function get_cpc(str) {
    if (str == 'PIL') {
        document.getElementById('td_hd_sh').style.display = 'table-cell';
    } else {
        document.getElementById('td_hd_sh').style.display = 'none';
        document.getElementById('m_wptyp2').value = '';
    }
}

function chk_SCSC() {
    var m_cat = document.getElementById('m_cat').value;
    var cat = document.getElementById('cat').value;
    var subcat = document.getElementById('subcat').value;
    if ((m_cat == '103' && cat == '2' && subcat == '1') || (m_cat == '128' && cat == '2') || (m_cat == '113' && cat == '1' && subcat == '13')) {
        document.getElementById('sh_hd_ms').style.display = 'table-row';
    } else
        document.getElementById('sh_hd_ms').style.display = 'none';
}

function get_m_act1(str) {
    if (str == '99999') {
        document.getElementById('hd_m_act').style.display = 'table-row';
    } else {
        document.getElementById('hd_m_act').style.display = 'none';
        document.getElementById('m_act').value = '';
    }
}

function get_p_id(str) {
    if (str == 'X') {
        document.getElementById('tr_p_id').style.display = 'table-row';
    } else {
        document.getElementById('tr_p_id').style.display = 'none';
        document.getElementById('m_impgn').value = '';
    }
    var m_orgcode = document.getElementById('m_orgcode').value;
    if ((str == 'D1' || str == 'D2' || str == 'D3') && m_orgcode == '999') {
        get_m_orgcode(m_orgcode);
    }

    var xmlhttp;
    if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else { // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }


    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            // var txtAdeshika="txtAdeshika"+rowCount;
            document.getElementById('m_impg').innerHTML = xmlhttp.responseText;

        }
    }
    // xmlhttp.open("GET","getReport51-II.php?seljudgename="+seljudgename+"&frm="+frm+"&toDate="+toDate,true);
    xmlhttp.open("GET", "p_id_id.php?str=" + str, true);
    xmlhttp.send(null);


}

function get_m_orgcode(str) {

    var hd_p_id = document.getElementById('p_id').value;
    //alert(hd_p_id);
    var xmlhttp;
    if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else { // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }


    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            // var txtAdeshika="txtAdeshika"+rowCount;
            document.getElementById('dv_nb').innerHTML = xmlhttp.responseText;
            // alert( document.getElementById('m_org').innerHTML);
            // if(document.getElementById('m_org').innerHTML=='0')
            //  {
            // alert(document.getElementById('hd_deptname').value);
            if (document.getElementById('hd_deptname').value == '-1') {
                window.open('http://172.16.180.26/cishcbom/filing/know_code.php?tabb=deptt&dtype=S', 'RIC', 'width=800,height=400');
                document.getElementById('m_org').value = '';
            } else if (document.getElementById('hd_deptname').value == '-2') {
                window.open('http://172.16.180.26/cishcbom/filing/know_code.php?tabb=deptt&dtype=C', 'RIC', 'width=800,height=400');
                document.getElementById('m_org').value = '';
            } else if (document.getElementById('hd_deptname').value == '-3') {
                window.open('http://172.16.180.26/cishcbom/filing/know_code.php?tabb=deptt&dtype=O', 'RIC', 'width=800,height=400');
                document.getElementById('m_org').value = '';
            } else
                document.getElementById('m_org').value = document.getElementById('hd_deptname').value;
            //     }
            //  else if(document.getElementById('m_org').innerHTML=='-1')
            //    {
            //  window.open('http://172.16.180.26/cishcbom/filing/know_code.php?tabb=deptt&dtype=S','RIC','width=800,height=400');
            //  }
            // document.getElementById('m_org').value=document.getElementById('m_org').innerHTML;
        }
    }
    // xmlhttp.open("GET","getReport51-II.php?seljudgename="+seljudgename+"&frm="+frm+"&toDate="+toDate,true);
    xmlhttp.open("GET", "m_orgcode_n.php?m_orgcode=" + str + "&hd_p_id=" + hd_p_id, true);
    xmlhttp.send(null);


}

function fil12(main_cat, main_subcat, main_sub_subcat) {
    var chk_pmt = '';
    /*if(document.getElementById('chk_pmt').checked==true)
        {
            chk_pmt=1;
        }*/
    //	chk_pmt = document.getElementById('chk_pmt').value;
    //   var lst_case=document.getElementById('lst_case').value;
    // alert(lst_case);
    var t_h_cno = document.getElementById('t_h_cno').value;
    var t_h_cyr = document.getElementById('t_h_cyt').value;
    //    var txtcaseno=document.getElementById('txtcaseno').value;
    //    var txtyear=document.getElementById('txtyear').value;
    //   var m_cat=document.getElementById('m_cat').value;
    var m_fixed = document.getElementById('m_fixed').value;
    //   if(lst_case=='')
    //       {
    //           alert("Please Enter Case Type ");
    //       }
    //      if(t_h_cno=='')
    //       {
    //             alert("Please Enter Diary No.");
    //       }
    //      else if(t_h_cyr=='')
    //         {
    //             alert("Please Enter Diary Year");
    //         }
    //      else if(m_cat=='0')
    //       {
    //             alert("Please select SUBJECT");
    //      } 
    //      else if(m_fixed=='')
    //          {
    //               alert("Please select Fixed For");
    //          }
    //      else
    {

        var m_wptyp = '';
        var m_wptyp2 = '';
        if (document.getElementById('m_wptyp'))
            m_wptyp = document.getElementById('m_wptyp').value;
        if (document.getElementById('m_wptyp2'))
            m_wptyp2 = document.getElementById('m_wptyp2').value;
        //    var m_cat=document.getElementById('m_cat').value;
        //    var m_ssub=document.getElementById('m_ssub').value;
        //    var cat=document.getElementById('cat').value;
        //    var subcat=document.getElementById('subcat').value;
        //    var m_act1=document.getElementById('m_act1').value;  
        //    var m_act=document.getElementById('m_act').value;  
        //    var section=document.getElementById('section').value;  
        var order = document.getElementById('order').value;
        // var m_dtdesc1=document.getElementById('m_dtdesc1').value;  
        //  var m_pordno=document.getElementById('m_pordno').value; 
        //  var law=document.getElementById('law').value; 
        var m_camt = document.getElementById('m_camt').value;
        var m_relief = document.getElementById('m_relief').value;
        // var p_id=document.getElementById('p_id').value; 
        // var m_impg=document.getElementById('m_impg').value; 
        // var m_impgn=document.getElementById('m_impgn').value; 
        // var m_orgcode=document.getElementById('m_orgcode').value; 
        // var m_org=document.getElementById('m_org').value; 
        var m_fixed = document.getElementById('m_fixed').value;
        if (document.getElementById('m_bail'))
            var m_bail = document.getElementById('m_bail').value;
        else
            var m_bail = '';
        //    var m_fixed1=document.getElementById('m_fixed1').value; 

        var m_fbench = document.getElementById('m_fbench').value;
        //    var lst_case=document.getElementById('lst_case').value;
        //       if(lst_case.length==2)
        //         lst_case='0'+lst_case;
        //       var fil_no='01'+lst_case+document.getElementById('txtcaseno').value+
        //                   document.getElementById('txtyear').value;
        //    var hd_ud=  document.getElementById('hd_ud').value;
        //    var hd_m_impg=document.getElementById('hd_m_impg').value; 
        //    var hd_m_ssub=document.getElementById('hd_m_ssub').value; 
        //    var hd_m_kept=document.getElementById('hd_m_kept').value; 
        // var cs_nm=document.getElementById('lst_case').options[document.getElementById('lst_case').selectedIndex].innerHTML;
        //var hd_m_lok1=document.getElementById('hd_m_lok1').value; 
        //var hd_m_lok2=document.getElementById('hd_m_lok2').value; 
        //var hd_m_fixed=document.getElementById('hd_m_fixed').value;

        var act_sec = new Array();
        var total_acts = document.getElementById('kakshammoolyam').value;
        for (var ijk = 1; ijk <= total_acts; ijk++) {
            var string_to_s = '';
            if (document.getElementById('act' + ijk)) {
                string_to_s += document.getElementById('act' + ijk).value + "~";
                string_to_s += document.getElementById('sec_1' + ijk).value + "~";
                string_to_s += document.getElementById('sec_2' + ijk).value + "~";
                string_to_s += document.getElementById('sec_3' + ijk).value + "~";
                string_to_s += document.getElementById('sec_4' + ijk).value;
                act_sec.push(string_to_s);
            }
        }
        //        var txt_rule_des=$('#txt_rule_des').val();
        //        var txt_rule_code=$('#txt_rule_code').val();
        //        var txt_sub_rule=$('#txt_sub_rule').val();
        //        var txt_rule_clause=$('#txt_rule_clause').val();
        var txt_pol = $('#txt_pol').val();
        var key_id = '';

        $('.added_keyword').each(function() {
            if (key_id == '')
                key_id = $(this).val();
            else
                key_id = key_id + '!' + $(this).val();
        });
        //        alert(key_id);
        var hd_rem_keyword = $('#hd_rem_keyword').val();

        var txt_court_fee = 0;
        var txt_valuation = 0;
        var txt_court_fee_tot = 0;
        if ($('#tr_court_fee').css('display') == 'table-row')
            txt_court_fee = $("#txt_court_fee").val();
        if ($('#tr_court_fee_tot').css('display') == 'table-row')
            txt_court_fee_tot = $("#txt_court_fee_tot").val();
        if ($('#tr_val').css('display') == 'table-row')
            txt_valuation = $('#txt_valuation').val();
        //        var txt_j_c_i=$('#txt_j_c_i').val();
        //alert("dfdf"+txt_court_fee_tot);
        var sensitive_case = 0;
        var txt_sen_case = '';
        if ($('#chk_sen_cs').is(':checked')) {
            txt_sen_case = $('#txt_sen_case').val();
            sensitive_case = 1;
        }


        var xmlhttp;
        if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else { // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }


        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                // var txtAdeshika="txtAdeshika"+rowCount;
                document.getElementById('dv_res1').innerHTML = xmlhttp.responseText;
                //                        var ck= confirm("Move To Lower Court");
                //                        if(ck==true)
                //                            {
                //                             //   alert("Ans");
                //                             chk_cat_low(lst_case,txtcaseno,txtyear,fil_no,cs_nm);
                //                                  
                //                               // k_bak('spsubsubmenu_1','/cishcbom/Demo/lowercourt.php');
                ////                               h1_bak('spsubmenu_6','spsubsubmenu_1',lst_case,txtcaseno,txtyear,fil_no);
                //                           }
                //                           else if(ck==false)
                //                               {
                ////                                    // alert("Rak");
                //                               }
                //    setInterval(function(){window.location="http://localhost/cishcbom/Demo/checker_ans.php"},3000);
                // window.location="http://localhost/cishcbom/Demo/checker_ans.php"
            }
        }
        // xmlhttp.open("GET","getReport51-II.php?seljudgename="+seljudgename+"&frm="+frm+"&toDate="+toDate,true);
        //                 xmlhttp.open("GET","save_dt.php?m_wptyp="+m_wptyp+"&m_wptyp2="+m_wptyp2+"&m_cat="+m_cat
        //                 +"&m_ssub="+m_ssub+"&cat="+cat+"&subcat="+subcat+"&m_act1="+m_act1
        //             +"&m_act="+m_act +"&section="+section+"&order="+order+"&m_dtdesc1="+m_dtdesc1
        //         +"&m_pordno="+m_pordno +"&law="+law+"&m_camt="+m_camt+"&m_relief="+m_relief
        //     +"&p_id="+p_id +"&m_impg="+m_impg+"&m_impgn="+m_impgn+"&m_orgcode="+m_orgcode
        // +"&m_org="+m_org+"&m_fixed="+m_fixed+"&m_bail="+m_bail+"&m_fixed1="+m_fixed1+"&m_fbench="+m_fbench+"&fil_no="+fil_no
        //+"&cs_nm="+cs_nm,true);

        xmlhttp.open("GET", "save_dt.php?m_wptyp=" + m_wptyp + "&m_wptyp2=" + m_wptyp2 + "&m_camt=" + m_camt + "&m_relief=" + m_relief + "&m_fixed=" + m_fixed + "&m_bail=" + m_bail +
            "&t_h_cno=" + t_h_cno + "&t_h_cyr=" + t_h_cyr + "&main_cat=" + main_cat + "&main_subcat=" + main_subcat + "&main_sub_subcat=" + main_sub_subcat + "&order=" + order +
            "&act_sec=" + act_sec + "&m_fbench=" + m_fbench + '&key_id=' + key_id + '&hd_rem_keyword=' + hd_rem_keyword + '&txt_pol=' + txt_pol + '&txt_court_fee=' + txt_court_fee +
            '&txt_valuation=' + txt_valuation + '&txt_court_fee_tot=' + txt_court_fee_tot + '&sensitive_case=' + sensitive_case + '&txt_sen_case=' + txt_sen_case, true);
        xmlhttp.send(null);

    }
}

function chk_cat_low(lst_case, txtcaseno, txtyear, fil_no, cs_nm) {
    var hd_sc = '';
    var xmlhttp;
    if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else { // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }


    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            // var txtAdeshika="txtAdeshika"+rowCount;
            document.getElementById('hdd_low_cc').innerHTML = xmlhttp.responseText;
            var s_op = '';
            var ccl = document.getElementById('hd_low_ca').value;
            if (ccl <= 0) {
                s_op = 'spsubsubmenu_1';
            } else if (ccl > 0) {
                s_op = 'spsubsubmenu_2';
            }
            h1_bak('spsubmenu_2', s_op, lst_case, txtcaseno, txtyear, fil_no, cs_nm, hd_sc);
        }
    }
    // xmlhttp.open("GET","getReport51-II.php?seljudgename="+seljudgename+"&frm="+frm+"&toDate="+toDate,true);
    xmlhttp.open("GET", "get_chk_cat_low.php?fil_no_bak_cc=" + fil_no, true);
    xmlhttp.send(null);
}

var cnt_data1 = 1;
var cnt_sno = 1;

function getSlide(str) {
    //  cnt_sno=1;
    var ck_ca_sb = 0;

    if (document.getElementById('hd_ssno').value != '0') {
        cnt_data1 = parseInt(document.getElementById('hd_ssno').value) + 1;
        document.getElementById('hd_ssno').value = '0';
    }
    var hd_co_tot = document.getElementById('hd_co_tot').value;
    var idd = str.split('chk_sno');

    var subject = document.getElementById('hd_subcode' + idd[1]).value;
    var cat = document.getElementById('hd_subcodes' + idd[1]).value;
    var subcat = document.getElementById('hd_subcodess' + idd[1]).value;

    var main_id = document.getElementById('hd_id' + idd[1]).value;

    for (var i = 1; i <= hd_co_tot; i++) {

        if (document.getElementById('hd_sp_a' + i)) {
            //           alert(document.getElementById('hd_sp_a'+i).value);
            //            alert(subject);
            //             alert(document.getElementById('hd_sp_b'+i).value.trim());
            //                alert(cat);
            //               alert(document.getElementById('hd_sp_c'+i).value);
            //                 alert(subcat);
            //                if((subject.trim()==document.getElementById('hd_sp_a'+i).value.trim() ) && 
            //                    (cat.trim()==document.getElementById('hd_sp_b'+i).value.trim() ) &&
            //                (subcat.trim()==document.getElementById('hd_sp_c'+i).value.trim() ))
            if (main_id.trim() == document.getElementById('hd_sp_d' + i).value.trim()) {
                ck_ca_sb = 1;
                //  alert("A");
            }
        }
    }
    if (ck_ca_sb == 1) {
        alert("Already Selected");
        // cnt_data1++;
    } else {
        //        var subject=document.getElementById('sp_subject'+idd[1]).options[document.getElementById('sp_subject'+idd[1]).selectedIndex].innerHTML;
        //               var cat=document.getElementById('sp_category'+idd[1]).options[document.getElementById('sp_category'+idd[1]).selectedIndex].innerHTML;
        //           // alert(cat);
        //            var subcat=document.getElementById('sp_subcategory'+idd[1]).options[document.getElementById('sp_subcategory'+idd[1]).selectedIndex].innerHTML;
        //alert(subject);
        var subject = document.getElementById('sp_subject' + idd[1]).innerHTML;
        var cat = document.getElementById('sp_category' + idd[1]).innerHTML;
        // alert(cat);
        var sp_sub_category = document.getElementById('sp_sub_category' + idd[1]).innerHTML;

        var sp_sub_sub_category = document.getElementById('sp_sub_sub_category' + idd[1]).innerHTML;

        var subcat = document.getElementById('sp_subcategory' + idd[1]).innerHTML;
        var sub_id = document.getElementById('hd_subcode' + idd[1]).value;
        var cat_id = document.getElementById('hd_subcodes' + idd[1]).value;
        var subcat_id = document.getElementById('hd_subcodess' + idd[1]).value;
        var hd_id_z = document.getElementById('hd_id' + idd[1]).value;

        var hd_color = document.getElementById('hd_color' + idd[1]).value;

        //             if(sub_id=='0')
        //                 {
        //                      alert("Pease Select Subject");
        //                 }
        //             else
        {
            var row0 = document.createElement("tr");
            row0.setAttribute('id', 'tr_uo' + cnt_data1);
            var column0 = document.createElement("td");
            var column1 = document.createElement("td");
            var column2 = document.createElement("td");
            var column3 = document.createElement("td");
            var column4 = document.createElement("td");
            var column5 = document.createElement("td");
            var column6 = document.createElement("td");
            var spAddObj = document.getElementById('tb_new');

            var hd_chk_add = document.createElement('input');
            hd_chk_add.setAttribute('type', 'checkbox');
            hd_chk_add.setAttribute('id', 'hd_chk_add' + cnt_data1);
            hd_chk_add.setAttribute('onclick', 'getDone_upd_cat(this.id);');
            // var hd_id=document.createElement('span');
            //     hd_id.setAttribute('type', 'hidden');
            //   hd_id.setAttribute('id', 'sp_a'+cnt_data1);
            var colors = '';
            if (hd_color == 's')
                colors = 'cl_supreme';
            else
                colors = 'cl_other';
            var hd_id_txtcnt = document.createElement('span');
            //  hd_id_txtcnt.setAttribute('type', 'hidden');
            hd_id_txtcnt.setAttribute('id', 'sp_b' + cnt_data1);
            hd_id_txtcnt.setAttribute('class', colors);
            var sp = document.createElement('span');
            //sp.setAttribute('id', 'spAddObj'+str1[1]);
            sp.setAttribute('id', 'sp_c' + cnt_data1);
            sp.setAttribute('class', colors);
            var sp_e = document.createElement('span');
            sp_e.setAttribute('id', 'sp_e' + cnt_data1);
            sp_e.setAttribute('class', colors);
            var sp_f = document.createElement('span');
            sp_f.setAttribute('id', 'sp_f' + cnt_data1);
            sp_f.setAttribute('class', colors);
            var chkbx = document.createElement('span');
            // chkbx.setAttribute('type', 'checkbox');
            chkbx.setAttribute('id', 'sp_d' + cnt_data1);


            chkbx.setAttribute('class', colors);
            //  chkbx.setAttribute('onclick', 'getDone_upd(this.id);');

            var hd_1 = document.createElement('input');
            hd_1.setAttribute('type', 'hidden');
            hd_1.setAttribute('id', 'hd_sp_a' + cnt_data1);

            var hd_2 = document.createElement('input');
            hd_2.setAttribute('type', 'hidden');
            hd_2.setAttribute('id', 'hd_sp_b' + cnt_data1);

            var hd_3 = document.createElement('input');
            hd_3.setAttribute('type', 'hidden');
            hd_3.setAttribute('id', 'hd_sp_c' + cnt_data1);
            column0.appendChild(hd_chk_add);

            var hd_4 = document.createElement('input');
            hd_4.setAttribute('type', 'hidden');
            hd_4.setAttribute('id', 'hd_sp_d' + cnt_data1);
            column0.appendChild(hd_chk_add);


            column0.appendChild(hd_1);
            column0.appendChild(hd_2);
            column0.appendChild(hd_3);
            column0.appendChild(hd_4);

            row0.appendChild(column0);
            // table1.appendChild(row0); 
            //    column1.appendChild(hd_id); 
            //  row0.appendChild(column1);
            column4.appendChild(chkbx);
            row0.appendChild(column4);
            column2.appendChild(hd_id_txtcnt);
            row0.appendChild(column2);
            column3.appendChild(sp);
            row0.appendChild(column3);
            column5.appendChild(sp_e);
            row0.appendChild(column5);
            column6.appendChild(sp_f);
            row0.appendChild(column6);

            var tb_res = document.getElementById('tb_new');
            tb_res.appendChild(row0);
            //                  if(document.getElementById('hd_ck_tot').value=='')
            //                     document.getElementById('hd_ck_tot').value= document.getElementById('hd_co_tot').value;
            //                 else
            //                        document.getElementById('hd_ck_tot').value= document.getElementById('hd_ck_tot').value-1;
            // document.getElementById('sp_a'+cnt_data1).innerHTML= cnt_data1;
            //                   document.getElementById('tr_uo'+cnt_data1).style.borderWidth='1px';
            //                      document.getElementById('tr_uo'+cnt_data1).style.borderColor='black';
            //                        document.getElementById('tr_uo'+cnt_data1).style.borderStyle='solid';
            document.getElementById('sp_b' + cnt_data1).innerHTML = subject;
            if (cat_id == '0')
                document.getElementById('sp_c' + cnt_data1).innerHTML = '-';
            else
                document.getElementById('sp_c' + cnt_data1).innerHTML = cat;
            if (subcat_id == '0')
                document.getElementById('sp_d' + cnt_data1).innerHTML = '-';
            else
                document.getElementById('sp_d' + cnt_data1).innerHTML = subcat;

            document.getElementById('sp_e' + cnt_data1).innerHTML = sp_sub_category;
            document.getElementById('sp_f' + cnt_data1).innerHTML = sp_sub_sub_category;

            document.getElementById('hd_sp_a' + cnt_data1).value = sub_id;
            document.getElementById('hd_sp_b' + cnt_data1).value = cat_id;
            document.getElementById('hd_sp_c' + cnt_data1).value = subcat_id;
            document.getElementById('hd_sp_d' + cnt_data1).value = hd_id_z;
            document.getElementById('hd_chk_add' + cnt_data1).checked = true;;
            // spAddObj.appendChild(table1);

            //spAddObj.appendChild(sp);
            //spAddObj.appendChild(sp1);
            //spAddObj.appendChild(sp2);
            ////document.getElementById('hd_id'+cnt_data).value='hdSH'+str1[1];
            //document.getElementById('hd_id_txtcnt'+cnt_data).value=document.getElementById('hdSH'+str1[1]).value;
            //document.getElementById('spAddObj'+cnt_data).innerHTML=document.getElementById('spInner'+str1[1]).innerHTML;
            //document.getElementById('hd_tot').value=cnt_data;
            document.getElementById('hd_co_tot').value = cnt_data1;
            cnt_data1++;
            cnt_sno++;
            var hd_ck_cf_natue = $('#hd_ck_cf_natue').val();
            if (hd_ck_cf_natue == 0)
                get_court_fee();
        }
    }
}

function getDone_upd_cat(str) {
    var str1 = str.split('hd_chk_add');
    var hd_sp_a_rem = '';
    var hd_sp_b_rem = '';
    var hd_sp_c_rem = '';
    var hd_sp_d_id = '';
    if (document.getElementById('hd_sp_a_rem').value == '')
        document.getElementById('hd_sp_a_rem').value = document.getElementById('hd_sp_a' + str1[1]).value;
    else {
        document.getElementById('hd_sp_a_rem').value = document.getElementById('hd_sp_a_rem').value + '^' +
            document.getElementById('hd_sp_a' + str1[1]).value;
    }
    //document.getElementById('hd_sp_a_rem').value=hd_sp_a_rem;
    if (document.getElementById('hd_sp_b_rem').value == '')
        document.getElementById('hd_sp_b_rem').value = document.getElementById('hd_sp_b' + str1[1]).value;
    else
        document.getElementById('hd_sp_b_rem').value = document.getElementById('hd_sp_b_rem').value + '^' + document.getElementById('hd_sp_b' + str1[1]).value;
    // document.getElementById('hd_sp_b_rem').value=hd_sp_b_rem;
    if (document.getElementById('hd_sp_c_rem').value == '')
        document.getElementById('hd_sp_c_rem').value = document.getElementById('hd_sp_c' + str1[1]).value;
    else
        document.getElementById('hd_sp_c_rem').value = document.getElementById('hd_sp_c_rem').value + '^' + document.getElementById('hd_sp_c' + str1[1]).value;

    if (document.getElementById('hd_sp_d_id').value == '')
        document.getElementById('hd_sp_d_id').value = document.getElementById('hd_sp_d' + str1[1]).value;
    else
        document.getElementById('hd_sp_d_id').value = document.getElementById('hd_sp_d_id').value + '^' + document.getElementById('hd_sp_d' + str1[1]).value;
    //document.getElementById('hd_sp_c_rem').value=hd_sp_c_rem;
    // var str1=str.split('hd_chk_add') ;


    $("#tr_uo" + str1[1]).remove();
    var hd_ck_cf_natue = $('#hd_ck_cf_natue').val();
    if (hd_ck_cf_natue == 0)
        get_court_fee();
}


//    function sav_mul_cat()
//    {
//   if(document.getElementById('dv_jb'))
//   document.getElementById('dv_jb').style.display='none';
//  //   alert(hd_sp_a_rem);
//        var ytq='0';
//             var ytq1='0';
//             var ent_ft='';
//         var m_fbench=document.getElementById('m_fbench').value; 
//    var lst_case=document.getElementById('lst_case').value;
//       if(lst_case.length==2)
//         lst_case='0'+lst_case;
//       var fil_no='01'+lst_case+document.getElementById('txtcaseno').value+
//                   document.getElementById('txtyear').value;
//          del_mul_cat(fil_no);
//        
//        
//        var hd_co_tot=document.getElementById('hd_co_tot').value;
//         for(var itt=1;itt<=hd_co_tot;itt++)
//                 {
//                      if(document.getElementById('hd_chk_add'+itt))
//         {
//            if(document.getElementById('hd_chk_add'+itt).checked==true)
//                        {
//                            ytq++;
//                        }
//                 }
//                 }
//                 
//                 if(ytq=='0')
//                     {
//                         alert("Please Add atleast one subject")
//                     }
//                     else
//                         {
//               //  alert(ytq);
//           for(var itt=1;itt<=hd_co_tot;itt++)
//                 {
//    
//        if(document.getElementById('hd_chk_add'+itt))
//         {
//            if(document.getElementById('hd_chk_add'+itt).checked==true)
//                        {
//                                ytq1++;
//                                if(ytq1=='1')
//                                    {
//                                        var main_cat= document.getElementById('hd_sp_a'+itt).value;
//                                     var main_subcat= document.getElementById('hd_sp_b'+itt).value;
//                                     var main_sub_subcat= document.getElementById('hd_sp_c'+itt).value;
//                                    }
//                                    var hd_sp_a= document.getElementById('hd_sp_a'+itt).value;
//                                     var hd_sp_b= document.getElementById('hd_sp_b'+itt).value;
//                                     var hd_sp_c= document.getElementById('hd_sp_c'+itt).value;
//                    var xmlhttp4;
//                    if (window.XMLHttpRequest)
//                    {
//                        xmlhttp4=new XMLHttpRequest();
//                    }
//                    else
//                    {// code for IE6, IE5
//                        xmlhttp4=new ActiveXObject("Microsoft.XMLHTTP");
//                    }
//                if(ytq1==ytq)
//                    {
//                  // alert(ytq);
//                 xmlhttp4.onreadystatechange=function()
//                    {
//                        if (xmlhttp4.readyState==4 && xmlhttp4.status==200)
//                        {
//                           
//                            document.getElementById('dv_mul_cat').innerHTML=xmlhttp4.responseText;
//                           fil12(main_cat,main_subcat,main_sub_subcat);
//
//                        }
//                    }
//                    }
//
//                 
//                     xmlhttp4.open("GET","insert_mul_cat.php?fil_no="+fil_no+"&hd_sp_a="+hd_sp_a+
//                         "&hd_sp_b="+hd_sp_b+"&hd_sp_c="+hd_sp_c+"&ytq1="+ytq1,false);
//                xmlhttp4.send(null);
//
//                          }
//                 }
//          }
//                         }
//    }
//    
//    function del_mul_cat(fil_no)
//    {
//         var  hd_sp_a_rem= document.getElementById('hd_sp_a_rem').value;
//     var  hd_sp_b_rem=document.getElementById('hd_sp_b_rem').value;
//     var  hd_sp_c_rem=document.getElementById('hd_sp_c_rem').value;
//    
//    var xmlhttp;
//                if (window.XMLHttpRequest)
//                {// code for IE7+, Firefox, Chrome, Opera, Safari
//                    xmlhttp=new XMLHttpRequest();
//                }
//                else
//                {// code for IE6, IE5
//                    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
//                }
//            
//            
//                xmlhttp.onreadystatechange=function()
//                {
//                    if (xmlhttp.readyState==4 && xmlhttp.status==200)
//                    {
//                       // var txtAdeshika="txtAdeshika"+rowCount;
//                       // document.getElementById('hdd_low_cc').innerHTML=xmlhttp.responseText;
//                    
//                    }
//                }
//               // xmlhttp.open("GET","getReport51-II.php?seljudgename="+seljudgename+"&frm="+frm+"&toDate="+toDate,true);
//                 xmlhttp.open("GET","del_mul_cat.php?fil_no="+fil_no+"&hd_sp_a_rem="+hd_sp_a_rem+
//                         "&hd_sp_b_rem="+hd_sp_b_rem+"&hd_sp_c_rem="+hd_sp_c_rem,true);
//               xmlhttp.send(null);
//    }
//  

    function sav_mul_cat()
    {
       // var other_catg = ['10','20','46','75','87','101','115','129','141','151','163','182','201','215','227','250','259','262','270','276','289','295','300','304','311'];
        var exist=false;
        if(document.getElementById('dv_jb'))
        document.getElementById('dv_jb').style.display='none';
  //   alert(hd_sp_a_rem);
        var ytq='0';
             var ytq1='0';
             var ytq_new = "0";

             var ytq2_new = "0";

             var ent_ft='';
//         var m_fbench=document.getElementById('m_fbench').value; 
//    var lst_case=document.getElementById('lst_case').value;
//       if(lst_case.length==2)
//         lst_case='0'+lst_case;
//       var fil_no='01'+lst_case+document.getElementById('txtcaseno').value+
//                   document.getElementById('txtyear').value;
//          del_mul_cat(fil_no);
       
        var sensitive_case=0;
        var txt_sen_case='';
        if($('#chk_sen_cs').is(':checked'))
        {
               txt_sen_case= $('#txt_sen_case').val();
               sensitive_case=1;
        }
   
      
   // alert(lst_case);
   var cl_rdn_supreme=0;
    $('.cl_rdn_supreme').each(function(){
         if($(this).is(':checked'))
           {
               cl_rdn_supreme=1;
           }
     });
    var hd_diary_nos=$('#hd_diary_nos').val();
    
//    var d_yr=hd_diary_nos.substr(-4);
//        var d_no=hd_diary_nos.substr(0,(hd_diary_nos.length)-4);
            
   var t_h_cno=hd_diary_nos.substr(0,(hd_diary_nos.length)-4);
    var t_h_cyr=hd_diary_nos.substr(-4);
    
//   var m_cat=document.getElementById('m_cat').value;
//      var m_fixed=document.getElementById('m_fixed').value;
   
       
        
        var hd_co_tot=document.getElementById('hd_co_tot').value;
        //var hd_co_tot_new = document.getElementById("hd_co_tot_new").value;

         for(var itt=1;itt<=hd_co_tot;itt++)
         {
            if(document.getElementById('hd_chk_add'+itt))
            {
                if(document.getElementById('hd_chk_add'+itt).checked==true)
                {
                    ytq++;
                    var cat=document.getElementById('hd_sp_d'+itt).value;
                    //exist = other_catg.includes(cat);
                }
            }
         }

         
         $('.chkItem').each(function (index, obj) {
            if (this.checked === true) {
                ytq2_new++;
                ytq_new++;
                console.log(obj);
                hd_sp_d_new = obj.getAttribute("mpn_idd");
             }
         });

        //console.log("saving mul"+hd_co_tot_new);
        //  for (var itt1 = 1; itt1 <= hd_co_tot_new; itt1++) {
        //     if (document.getElementById("hd_chk_add_new" + itt1)) {
        //       if (document.getElementById("hd_chk_add_new" + itt1).checked == true) {
        //         ytq_new++;
        //         hd_sp_d_new = document.getElementById("hd_sp_d_new" + itt1).value;
        //       }
        //     }
        //   }



          console.log("saving mul ytq"+ytq_new);
        //alert("New---"+ytq_new);
        if(cl_rdn_supreme==0 && ytq=='0')
         {
             alert("Please select Category");
             return false;
         }
         if(ytq=='0')
         {
             alert("Please Add atleast one subject")
         }

         if(ytq>1)
         {
             alert("Only one category can be updated!!");
             return false;
         }

        if (ytq2_new == "0") {
            alert("Please select atleast one new category");
            return false;
        }
        if (ytq2_new > 1) {
            alert("Only one new category can be updated!!");
            return false;
        }

        var var_ortext=document.getElementById('ortext').value.trim();
        if(var_ortext=='' && exist == true)
        {
            alert("Please enter Other category remarks");
            return false;
        }
//                 else if(lst_case=='')
//       {
//           alert("Please Enter Case Type ");
//          
//       }
     else if(t_h_cno=='')
       {
             alert("Please Enter Diary No.");
             $('#t_h_cno').focus();
             
       }
      else if(t_h_cyr=='')
         {
             alert("Please Enter Diary Year");
               $('#t_h_cyr').focus();
               
         }
//      else if(m_cat=='0')
//       {
//             alert("Please select SUBJECT");
//          
//      } 
//      else if(m_fixed=='')
//          {
//               alert("Please select Fixed For");
//                
//          }
        else if($('#tr_val').css('display')=='table-row' && $('#txt_valuation').val()=='')
            {
                alert("Please enter valuation");
                $('#txt_valuation').focus();
            }
        else if($('#tr_court_fee').css('display')=='table-row' && $('#txt_court_fee').val()=='')
            {
                alert("Please enter court fee");
                $('#txt_court_fee').focus();
            } 
         else  if(sensitive_case==1 && txt_sen_case=='')
        {
            alert("Please enter reason of case to be sensitive");
            $('#txt_sen_case').focus();
           
        }
                     else
                         {
               //  alert(ytq);
               
               $('#ok2').attr('disabled',true);
               del_mul_cat(hd_co_tot, ytq, ytq1, t_h_cno, t_h_cyr,hd_sp_d_new,ytq_new);
           
                         }
    }
	
	    function del_mul_cat(hd_co_tot, ytq, ytq1, t_h_cno, t_h_cyr,hd_sp_d_new,ytq_new) 
        {
    var other_cat = document.getElementById('ortext').value;
    //alert(other_cat);
    var  hd_sp_a_rem= document.getElementById('hd_sp_a_rem').value;
    var  hd_sp_b_rem=document.getElementById('hd_sp_b_rem').value;
    var  hd_sp_c_rem=document.getElementById('hd_sp_c_rem').value;
    var hd_sp_d_id=document.getElementById('hd_sp_d_id').value;

    //var xmlhttp;
    //if (window.XMLHttpRequest)
    // {// code for IE7+, Firefox, Chrome, Opera, Safari
    //   xmlhttp=new XMLHttpRequest();
    //}
    // else
    // {// code for IE6, IE5
    // xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    // }


    // xmlhttp.onreadystatechange=function()
    // {
    // if (xmlhttp.readyState==4 && xmlhttp.status==200)
    //{
//                  alert(xmlhttp.responseText);
    for(var itt=1;itt<=hd_co_tot;itt++)
    {

        if(document.getElementById('hd_chk_add'+itt))
        {
            if(document.getElementById('hd_chk_add'+itt).checked==true)
            {
                ytq1++;
                if(ytq1=='1')
                {
                    var main_cat= document.getElementById('hd_sp_a'+itt).value;
                    var main_subcat= document.getElementById('hd_sp_b'+itt).value;
                    var main_sub_subcat= document.getElementById('hd_sp_c'+itt).value;
                }
                var hd_sp_a= document.getElementById('hd_sp_a'+itt).value;
                var hd_sp_b= document.getElementById('hd_sp_b'+itt).value;
                var hd_sp_c= document.getElementById('hd_sp_c'+itt).value;
                var hd_sp_d= document.getElementById('hd_sp_d'+itt).value;
                var xmlhttp4;
                if (window.XMLHttpRequest)
                {
                    xmlhttp4=new XMLHttpRequest();
                }
                else
                {// code for IE6, IE5
                    xmlhttp4=new ActiveXObject("Microsoft.XMLHTTP");
                }
                if(ytq1==ytq)
                {

                    xmlhttp4.onreadystatechange=function()
                    {
                        if (xmlhttp4.readyState==4 && xmlhttp4.status==200)
                        {
                            if(xmlhttp4.responseText=='')
                            {
                                alert("Category Updated Successfully");
                                get_def_rec();
                            }
                            else
                            {
                                alert(xmlhttp4.responseText);
                            }

                        }
                    }
                }


//                xmlhttp4.open("GET","../scrutiny/insert_mul_cat.php?t_h_cno="+t_h_cno+"&hd_sp_a="+hd_sp_a+
//                    "&hd_sp_b="+hd_sp_b+"&hd_sp_c="+hd_sp_c+"&ytq1="+ytq1+"&t_h_cyr="+t_h_cyr+"&hd_sp_d="+hd_sp_d+"&other_cat="+other_cat,false);
                  xmlhttp4.open("GET",base_url + "/Filing/Caveat/insertMulCat?hd_sp_d_new="+hd_sp_d_new+"&total_old_cat="+ytq+"&total_new_cat="+ytq_new+"&t_h_cno="+t_h_cno+"&hd_sp_a="+hd_sp_a+"&hd_sp_b="+hd_sp_b+"&hd_sp_c="+hd_sp_c+"&ytq1="+ytq1+"&t_h_cyr="+t_h_cyr+"&hd_sp_d="+hd_sp_d+"&other_cat="+other_cat+"&verify_req_page="+"Y",false);

                xmlhttp4.send(null);

            }
        }
    }

    // }
    // }
    // xmlhttp.open("GET","getReport51-II.php?seljudgename="+seljudgename+"&frm="+frm+"&toDate="+toDate,true);
    // xmlhttp.open("POST","../scrutiny/del_mul_cat.php?t_h_cno="+t_h_cno+"&hd_sp_a_rem="+hd_sp_a_rem+
    // "&hd_sp_b_rem="+hd_sp_b_rem+"&hd_sp_c_rem="+hd_sp_c_rem+"&t_h_cyr="+t_h_cyr+"&hd_sp_d_id="+hd_sp_d_id,true);
    // xmlhttp.send(null);
}
	
	
/* 
///

function del_mul_cat(hd_co_tot, ytq, ytq1, t_h_cno, t_h_cyr) {
	
	
	var CSRF_TOKEN = 'CSRF_TOKEN';
    var csrf = $("input[name='CSRF_TOKEN']").val();
	
    var hd_sp_a_rem = document.getElementById('hd_sp_a_rem').value;
    var hd_sp_b_rem = document.getElementById('hd_sp_b_rem').value;
    var hd_sp_c_rem = document.getElementById('hd_sp_c_rem').value;
    var hd_sp_d_id = document.getElementById('hd_sp_d_id').value;

    //    alert(hd_sp_d_id);
    //    alert(hd_sp_b_rem);
    //    alert(hd_sp_c_rem);
    var xmlhttp;
    if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else { // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }


    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            //                  alert(xmlhttp.responseText);
            for (var itt = 1; itt <= hd_co_tot; itt++) {

                if (document.getElementById('hd_chk_add' + itt)) {
                    if (document.getElementById('hd_chk_add' + itt).checked == true) {
                        ytq1++;
                        if (ytq1 == '1') {
                            var main_cat = document.getElementById('hd_sp_a' + itt).value;
                            var main_subcat = document.getElementById('hd_sp_b' + itt).value;
                            var main_sub_subcat = document.getElementById('hd_sp_c' + itt).value;
                        }
                        var hd_sp_a = document.getElementById('hd_sp_a' + itt).value;
                        var hd_sp_b = document.getElementById('hd_sp_b' + itt).value;
                        var hd_sp_c = document.getElementById('hd_sp_c' + itt).value;
                        var hd_sp_d = document.getElementById('hd_sp_d' + itt).value;
                        var xmlhttp4;
                        if (window.XMLHttpRequest) {
                            xmlhttp4 = new XMLHttpRequest();
                        } else { // code for IE6, IE5
                            xmlhttp4 = new ActiveXObject("Microsoft.XMLHTTP");
                        }
                        if (ytq1 == ytq) {
                            // alert(ytq);
                            xmlhttp4.onreadystatechange = function() {
                                if (xmlhttp4.readyState == 4 && xmlhttp4.status == 200) {

                                    document.getElementById('dv_mul_cat').innerHTML = xmlhttp4.responseText;
                                    fil12(main_cat, main_subcat, main_sub_subcat);

                                }
                            }
                        }


                        xmlhttp4.open("GET", base_url + "/Filing/Caveat/insertMulCat?t_h_cno=" + t_h_cno + "&hd_sp_a=" + hd_sp_a +
                            "&hd_sp_b=" + hd_sp_b + "&hd_sp_c=" + hd_sp_c + "&ytq1=" + ytq1 + "&t_h_cyr=" + t_h_cyr + "&hd_sp_d=" + hd_sp_d +'&'+ CSRF_TOKEN+'='+csrf, false);
                        xmlhttp4.send(null);

                    }
                }
            }

        }
    }
    // xmlhttp.open("GET","getReport51-II.php?seljudgename="+seljudgename+"&frm="+frm+"&toDate="+toDate,true);
    xmlhttp.open("POST", base_url + "/Filing/Caveat/delMulCat?t_h_cno=" + t_h_cno + "&hd_sp_a_rem=" + hd_sp_a_rem +
        "&hd_sp_b_rem=" + hd_sp_b_rem + "&hd_sp_c_rem=" + hd_sp_c_rem + "&t_h_cyr=" + t_h_cyr + "&hd_sp_d_id=" + hd_sp_d_id +'&'+ CSRF_TOKEN+'='+csrf, false);
    xmlhttp.send(null);
} */


//    function sav_mul_cat()
//    {
//   if(document.getElementById('dv_jb'))
//   document.getElementById('dv_jb').style.display='none';
//  //   alert(hd_sp_a_rem);
//        var ytq='0';
//             var ytq1='0';
//             var ent_ft='';
//         var m_fbench=document.getElementById('m_fbench').value; 
//    var lst_case=document.getElementById('lst_case').value;
//       if(lst_case.length==2)
//         lst_case='0'+lst_case;
//       var fil_no='01'+lst_case+document.getElementById('txtcaseno').value+
//                   document.getElementById('txtyear').value;
////          del_mul_cat(fil_no);
//        
//        
//        var hd_co_tot=document.getElementById('hd_co_tot').value;
//         for(var itt=1;itt<=hd_co_tot;itt++)
//                 {
//                      if(document.getElementById('hd_chk_add'+itt))
//         {
//            if(document.getElementById('hd_chk_add'+itt).checked==true)
//                        {
//                            ytq++;
//                        }
//                 }
//                 }
//                 
//                 if(ytq=='0')
//                     {
//                         alert("Please Add atleast one subject")
//                     }
//                     else
//                         {
//               //  alert(ytq);
//               $('#ok2').attr('disabled',true);
//                del_mul_cat(fil_no,hd_co_tot,ytq,ytq1);
//           
//                         }
//    }
//    
//    function del_mul_cat(fil_no,hd_co_tot,ytq,ytq1)
//    {
//         var  hd_sp_a_rem= document.getElementById('hd_sp_a_rem').value;
//     var  hd_sp_b_rem=document.getElementById('hd_sp_b_rem').value;
//     var  hd_sp_c_rem=document.getElementById('hd_sp_c_rem').value;
////    alert(hd_sp_a_rem);
////    alert(hd_sp_b_rem);
////    alert(hd_sp_c_rem);
//    var xmlhttp;
//                if (window.XMLHttpRequest)
//                {// code for IE7+, Firefox, Chrome, Opera, Safari
//                    xmlhttp=new XMLHttpRequest();
//                }
//                else
//                {// code for IE6, IE5
//                    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
//                }
//            
//            
//                xmlhttp.onreadystatechange=function()
//                {
//                    if (xmlhttp.readyState==4 && xmlhttp.status==200)
//                    {
//                      for(var itt=1;itt<=hd_co_tot;itt++)
//                 {
//    
//        if(document.getElementById('hd_chk_add'+itt))
//         {
//            if(document.getElementById('hd_chk_add'+itt).checked==true)
//                        {
//                                ytq1++;
//                                if(ytq1=='1')
//                                    {
//                                        var main_cat= document.getElementById('hd_sp_a'+itt).value;
//                                     var main_subcat= document.getElementById('hd_sp_b'+itt).value;
//                                     var main_sub_subcat= document.getElementById('hd_sp_c'+itt).value;
//                                    }
//                                    var hd_sp_a= document.getElementById('hd_sp_a'+itt).value;
//                                     var hd_sp_b= document.getElementById('hd_sp_b'+itt).value;
//                                     var hd_sp_c= document.getElementById('hd_sp_c'+itt).value;
//                    var xmlhttp4;
//                    if (window.XMLHttpRequest)
//                    {
//                        xmlhttp4=new XMLHttpRequest();
//                    }
//                    else
//                    {// code for IE6, IE5
//                        xmlhttp4=new ActiveXObject("Microsoft.XMLHTTP");
//                    }
//                if(ytq1==ytq)
//                    {
//                  // alert(ytq);
//                 xmlhttp4.onreadystatechange=function()
//                    {
//                        if (xmlhttp4.readyState==4 && xmlhttp4.status==200)
//                        {
//                           
//                            document.getElementById('dv_mul_cat').innerHTML=xmlhttp4.responseText;
//                           fil12(main_cat,main_subcat,main_sub_subcat);
//
//                        }
//                    }
//                    }
//
//                 
//                     xmlhttp4.open("GET","insert_mul_cat.php?fil_no="+fil_no+"&hd_sp_a="+hd_sp_a+
//                         "&hd_sp_b="+hd_sp_b+"&hd_sp_c="+hd_sp_c+"&ytq1="+ytq1,false);
//                xmlhttp4.send(null);
//
//                          }
//                 }
//          }
//                    
//                    }
//                }
//               // xmlhttp.open("GET","getReport51-II.php?seljudgename="+seljudgename+"&frm="+frm+"&toDate="+toDate,true);
//                 xmlhttp.open("POST","del_mul_cat.php?fil_no="+fil_no+"&hd_sp_a_rem="+hd_sp_a_rem+
//                         "&hd_sp_b_rem="+hd_sp_b_rem+"&hd_sp_c_rem="+hd_sp_c_rem,true);
//               xmlhttp.send(null);
//    }
function chk_cf_low(lst_case, txtcaseno, txtyear, fil_no) {

    var xmlhttp;
    if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else { // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }


    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            // var txtAdeshika="txtAdeshika"+rowCount;
            document.getElementById('hdd_low_cc').innerHTML = xmlhttp.responseText;
            var s_op = '';
            var ccl = document.getElementById('hd_low_ca').value;
            if (ccl <= 0) {
                s_op = 'spsubsubmenu_1';
            } else if (ccl > 0) {
                s_op = 'spsubsubmenu_2';
            }
            h1_bak('spsubmenu_3', s_op, lst_case, txtcaseno, txtyear, fil_no);
        }
    }
    // xmlhttp.open("GET","getReport51-II.php?seljudgename="+seljudgename+"&frm="+frm+"&toDate="+toDate,true);
    xmlhttp.open("GET", "get_chk_cf_low.php?fil_no_bak_cc=" + fil_no, true);
    xmlhttp.send(null);
}

function onlynumbers(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    //alert(charCode);
    if ((charCode >= 48 && charCode <= 57) || charCode == 9 || charCode == 8) {
        return true;
    }
    return false;
}

function new_act_button() {
    /*var table = document.getElementById('main_table');
    var body = table.getElementsByTagName('tbody')[0];
    var crw = trow.parentNode.parentNode.rowIndex;
    crw += 1;
    alert(crw+'th row');*/
    //var b_row = table.getElementsByTagName('tr')[crw];
    //body.insertBefore(tr,b_row);

    var pa = document.getElementById('kakshammoolyam').value;
    var tr = document.createElement('tr');
    tr.setAttribute('id', 'actsec' + (parseInt(pa) + 1));
    var td = document.createElement('td');
    td.setAttribute('colspan', '4');
    var span1 = document.createElement('span');
    span1.innerHTML = "Act ";
    var select = document.createElement('select');
    select.setAttribute('id', 'act' + (parseInt(pa) + 1));
    select.setAttribute('style', 'width:400px;');
    var option1 = document.createElement('option');
    option1.innerHTML = "Select";
    option1.setAttribute('value', "");
    select.appendChild(option1);

    var all_act = get_act_detailAll();
    //alert(all_act.length);
    for (var j = 0; j < all_act.length; j++) {
        var tukde = all_act[j].split('~');
        //tukde = tukde.split('~');
        var optn = document.createElement('option');
        optn.setAttribute('value', tukde[0]);
        optn.innerHTML = tukde[1];
        select.appendChild(optn);
    }

    var span2 = document.createElement('span');
    span2.innerHTML = "&nbsp;&nbsp; Section ";
    var input1 = document.createElement('input');
    input1.setAttribute('type', 'text');
    input1.setAttribute('id', 'sec_1' + (parseInt(pa) + 1));
    input1.setAttribute('size', '3');
    input1.setAttribute('maxlength', '3');
    input1.setAttribute('onkeypress', 'return onlynumbers(event)');
    input1.setAttribute('style', 'text-align: center');
    var span3 = document.createElement('span');
    span3.innerHTML = '&nbsp;(';
    var input2 = document.createElement('input');
    input2.setAttribute('type', 'text');
    input2.setAttribute('id', 'sec_2' + (parseInt(pa) + 1));
    input2.setAttribute('size', '3');
    input2.setAttribute('maxlength', '3');
    input2.setAttribute('onkeypress', 'return slashnot(event)');
    input2.setAttribute('style', 'text-align: center');
    var span4 = document.createElement('span');
    span4.innerHTML = ')';
    var span5 = document.createElement('span');
    span5.innerHTML = '&nbsp;(';
    var input3 = document.createElement('input');
    input3.setAttribute('type', 'text');
    input3.setAttribute('id', 'sec_3' + (parseInt(pa) + 1));
    input3.setAttribute('size', '3');
    input3.setAttribute('maxlength', '3');
    input3.setAttribute('onkeypress', 'return slashnot(event)');
    input3.setAttribute('style', 'text-align: center');
    var span6 = document.createElement('span');
    span6.innerHTML = ')';
    var span7 = document.createElement('span');
    span7.innerHTML = '&nbsp;(';
    var input4 = document.createElement('input');
    input4.setAttribute('type', 'text');
    input4.setAttribute('id', 'sec_4' + (parseInt(pa) + 1));
    input4.setAttribute('size', '3');
    input4.setAttribute('maxlength', '3');
    input4.setAttribute('onkeypress', 'return slashnot(event)');
    input4.setAttribute('style', 'text-align: center');
    var span8 = document.createElement('span');
    span8.innerHTML = ')&nbsp;&nbsp;';

    var btn1 = document.createElement('input');
    var btn2 = document.createElement('input');
    btn1.setAttribute('type', "button");
    btn2.setAttribute('type', "button");
    btn1.setAttribute('onclick', "new_act_button()");
    btn2.setAttribute('onclick', "new_sec_button('" + (parseInt(pa) + 1) + "')");
    btn1.setAttribute('value', 'New Act');
    btn2.setAttribute('value', 'New Section');
    btn1.setAttribute('id', 'btnAddAct' + (parseInt(pa) + 1));
    btn2.setAttribute('id', 'btnAddSec' + (parseInt(pa) + 1));
    td.appendChild(span1);
    td.appendChild(select);
    td.appendChild(span2);
    td.appendChild(input1);
    td.appendChild(span3);
    td.appendChild(input2);
    td.appendChild(span4);
    td.appendChild(span5);
    td.appendChild(input3);
    td.appendChild(span6);
    td.appendChild(span7);
    td.appendChild(input4);
    td.appendChild(span8);
    td.appendChild(btn1);
    var p = document.createElement('span');
    p.innerHTML = "&nbsp;&nbsp;";
    td.appendChild(p);
    td.appendChild(btn2);
    tr.appendChild(td);
    $(tr).insertAfter('#actsec' + pa);
    document.getElementById('kakshammoolyam').value = ++pa;
}

function new_sec_button(pa2) {
    var pa = document.getElementById('kakshammoolyam').value;
    var tr = document.createElement('tr');
    tr.setAttribute('id', 'actsec' + (parseInt(pa) + 1));
    var td = document.createElement('td');
    td.setAttribute('colspan', '4');
    var span1 = document.createElement('span');
    span1.innerHTML = "Act ";
    var select = document.createElement('select');
    select.setAttribute('id', 'act' + (parseInt(pa) + 1));
    select.setAttribute('style', 'width:400px;');
    var option1 = document.createElement('option');
    option1.innerHTML = "Select";
    option1.setAttribute('value', "");
    select.appendChild(option1);

    var all_act = get_act_detailAll();
    //alert(all_act.length);
    for (var j = 0; j < all_act.length; j++) {
        var tukde = all_act[j].split('~');
        //tukde = tukde.split('~');
        var optn = document.createElement('option');
        optn.setAttribute('value', tukde[0]);
        optn.innerHTML = tukde[0] + ' - ' + tukde[1];
        select.appendChild(optn);
    }


    var span2 = document.createElement('span');
    span2.innerHTML = "&nbsp;&nbsp; Section ";
    var input1 = document.createElement('input');
    input1.setAttribute('type', 'text');
    input1.setAttribute('id', 'sec_1' + (parseInt(pa) + 1));
    input1.setAttribute('size', '3');
    input1.setAttribute('maxlength', '3');
    input1.setAttribute('onkeypress', 'return onlynumbers(event)');
    input1.setAttribute('style', 'text-align: center');
    var span3 = document.createElement('span');
    span3.innerHTML = '&nbsp;(';
    var input2 = document.createElement('input');
    input2.setAttribute('type', 'text');
    input2.setAttribute('id', 'sec_2' + (parseInt(pa) + 1));
    input2.setAttribute('size', '3');
    input2.setAttribute('maxlength', '3');
    input2.setAttribute('onkeypress', 'return slashnot(event)');
    input2.setAttribute('style', 'text-align: center');
    var span4 = document.createElement('span');
    span4.innerHTML = ')';
    var span5 = document.createElement('span');
    span5.innerHTML = '&nbsp;(';
    var input3 = document.createElement('input');
    input3.setAttribute('type', 'text');
    input3.setAttribute('id', 'sec_3' + (parseInt(pa) + 1));
    input3.setAttribute('size', '3');
    input3.setAttribute('maxlength', '3');
    input3.setAttribute('onkeypress', 'return slashnot(event)');
    input3.setAttribute('style', 'text-align: center');
    var span6 = document.createElement('span');
    span6.innerHTML = ')';
    var span7 = document.createElement('span');
    span7.innerHTML = '&nbsp;(';
    var input4 = document.createElement('input');
    input4.setAttribute('type', 'text');
    input4.setAttribute('id', 'sec_4' + (parseInt(pa) + 1));
    input4.setAttribute('size', '3');
    input4.setAttribute('maxlength', '3');
    input4.setAttribute('onkeypress', 'return slashnot(event)');
    input4.setAttribute('style', 'text-align: center');
    var span8 = document.createElement('span');
    span8.innerHTML = ')&nbsp;&nbsp;';

    var btn1 = document.createElement('input');
    var btn2 = document.createElement('input');
    btn1.setAttribute('type', "button");
    btn2.setAttribute('type', "button");
    btn1.setAttribute('onclick', "new_act_button()");
    btn2.setAttribute('onclick', "new_sec_button('" + (parseInt(pa) + 1) + "')");
    btn1.setAttribute('value', 'New Act');
    btn2.setAttribute('value', 'New Section');
    btn1.setAttribute('id', 'btnAddAct' + (parseInt(pa) + 1));
    btn2.setAttribute('id', 'btnAddSec' + (parseInt(pa) + 1));
    td.appendChild(span1);
    td.appendChild(select);
    td.appendChild(span2);
    td.appendChild(input1);
    td.appendChild(span3);
    td.appendChild(input2);
    td.appendChild(span4);
    td.appendChild(span5);
    td.appendChild(input3);
    td.appendChild(span6);
    td.appendChild(span7);
    td.appendChild(input4);
    td.appendChild(span8);
    td.appendChild(btn1);
    var p = document.createElement('span');
    p.innerHTML = "&nbsp;&nbsp;";
    td.appendChild(p);
    td.appendChild(btn2);
    tr.appendChild(td);
    $(tr).insertAfter('#actsec' + pa2);
    document.getElementById('act' + (parseInt(pa) + 1)).value = document.getElementById('act' + (parseInt(pa2))).value;
    document.getElementById('kakshammoolyam').value = ++pa;
}


/*
$(document).on("click",".btnAddAct",function(){
    //alert('punch');
    var pa = document.getElementById('kakshammoolyam').value;
    var tr = document.createElement('tr');
    var td = document.createElement('td');
    var btn1 = document.createElement('button');
    btn1.setAttribute('class','btnAddAct');
    btn1.setAttribute('value','New Act');
    btn1.setAttribute('id','btnAddAct'+(pa+1));
    td.appendChild(btn1);
    tr.appendChild(td);
    $(tr).insertAfter('.actsec'+pa);
});*/


function get_act_detailAll() {
    var array1 = new Array();
    /*array1.push("1~sarthak gupta");
    array1.push("2~high court of madhya pradesh");
    return array1;
    */
    var xmlhttp;
    if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else { // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
     
    var url = base_url + "/Filing/Caveat/getActDetail?handler=G";
    xmlhttp.open("GET", url, false);
    xmlhttp.send(null);

    var full = xmlhttp.responseText;	 
    full = full.split('#');
	
    for (var k = 0; k < full.length; k++) {
        array1.push(full[k]);
    }
    return array1;
}

function del_act_sec(fil_no, d_yr, row) {
    var act = document.getElementById('act' + row).value;
    var sec_1 = document.getElementById('sec_1' + row).value;
    var sec_2 = document.getElementById('sec_2' + row).value;
    var sec_3 = document.getElementById('sec_3' + row).value;
    var sec_4 = document.getElementById('sec_4' + row).value;
    var xmlhttp;
    if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else { // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            //            /alert(xmlhttp.responseText);
            if (xmlhttp.responseText == '1') {
                var r = '#actsec' + row;
                var row3 = "<tr><td colspan='4' style='text-align:center;color:red;'><b>Act - ";
                var content = '';
                if (act != '')
                    content += act;
                if (sec_1 != '')
                    content += '/' + sec_1;
                if (sec_2 != '')
                    content += '(' + sec_2 + ')';
                if (sec_3 != '')
                    content += '(' + sec_3 + ')';
                if (sec_4 != '')
                    content += '(' + sec_4 + ')';

                row3 += content + "</b> Deleted Successfully</td></tr>";
                $(r).replaceWith(row3);
            } else {
                var r = '#actsec' + row;
                var row3 = "<tr><td colspan='4' style='text-align:center;color:red;'><b>Error - " + xmlhttp.responseText + "</b></td></tr>";
                $(r).replaceWith(row3);
            }

        }
    }
    var url = base_url + "/Filing/Caveat/getActDetail?handler=D&fil_no=" + fil_no + "&act=" + act + "&sec_1=" + sec_1 + "&sec_2=" + sec_2 + "&sec_3=" + sec_3 + "&sec_4=" + sec_4 + "&d_yr=" + d_yr;
    xmlhttp.open("GET", url, true);
    xmlhttp.send(null);
}

function slashnot(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    //alert(charCode);
    if (charCode == 47)
        return false;
    else
        return true;
}

function set_db(str) {
    if (str == '50') {
        $('#m_fbench').val('D');
    }

}

/*function chk_bnch_s(str)
{
    if($('#chk_pmt').val()=='50' && str!='D')
        {
            alert("Vyapam case should always be listable before DB");
             $('#m_fbench').val('D');
        }
}*/
$(document).ready(function() {
    $(document).on('keyup', '#txt_search', function() {
        var txt_search = $('#txt_search').val();
        var cl_rdn_supreme = '';
        $('.cl_rdn_supreme').each(function() {
            if ($(this).is(':checked')) {
                cl_rdn_supreme = $(this).val();
            }
        });

        var CSRF_TOKEN = 'CSRF_TOKEN';
        var csrf = $("input[name='CSRF_TOKEN']").val();

        //     alert(cl_rdn_supreme);
        $.ajax({
            url: base_url + "/Filing/Caveat/search_cat",
            //cache: false,
            //async: true,
            data: {
                CSRF_TOKEN: csrf,
                txt_search: txt_search,
                cl_rdn_supreme: cl_rdn_supreme
            },
            beforeSend: function() {
                //updateCSRFToken();
                $('#sp_mul_rec').html('<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>');
            },
            type: 'POST',
            success: function(data, status) {
                updateCSRFToken();
                $('#sp_mul_rec').html(data);


            },
            error: function(xhr) {
                updateCSRFToken();
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }

        });
    });
    $(document).on('click', '.cl_rdn_supreme', function() {
        var id_val = $(this).val();
        var hd_diary_nos = $('#hd_diary_nos').val();
        $('#txt_search').attr('disabled', false);
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var csrf = $("input[name='CSRF_TOKEN']").val();
        updateCSRFToken();
        $.ajax({
            url: base_url + '/Filing/Caveat/getCategories',
            cache: false,
            async: true,
            data: {
                CSRF_TOKEN: csrf,
                id_val: id_val,
                hd_diary_nos: hd_diary_nos
            },
            beforeSend: function() {
                updateCSRFToken();
                $('#sp_mul_rec').html('<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>');
            },
            type: 'POST',
            success: function(data, status) {

                $('#sp_mul_rec').html(data);
                updateCSRFToken();

            },
            error: function(xhr) {
                updateCSRFToken();
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }

        });
    });
    $(document).on('keyup', '#txt_src_key', function() {
		updateCSRFToken();
        var txt_src_key = $('#txt_src_key').val();
        if (txt_src_key != '') {
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var csrf = $("input[name='CSRF_TOKEN']").val();
            updateCSRFToken();
            $.ajax({
                url: base_url + '/Filing/Caveat/getSearchKeyword',
                cache: false,
                async: true,
                data: {
                    CSRF_TOKEN: csrf,
                    txt_src_key: txt_src_key
                },
                beforeSend: function() {
                    $('#dv_src_keyword').html('<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>');
                },
                type: 'POST',
                success: function(data, status) {
					updateCSRFToken();
                    $('#dv_src_keyword').html(data);


                },
                error: function(xhr) {
                    alert("Error: " + xhr.status + " " + xhr.statusText);
                }

            });
        }
    });
    $(document).on('click', '.cl_keyword', function() {
		updateCSRFToken();
        var idd = $(this).attr('id');
        var sp_idd = idd.split('chk_keyword');
        var v_val = $(this).val();
        var sp_k_des = $('#sp_k_des' + sp_idd[1]).html();
        var hd_max_keyword = $('#hd_max_keyword').val();
        var ck_ext_kw = 0;
        for (var i = 0; i < hd_max_keyword; i++) {

            var chk_a_keyword = $('#chk_a_keyword' + i).val();
            if (v_val == chk_a_keyword) {
                ck_ext_kw = 1;
                break;
            }

        }
        if (ck_ext_kw == 0) {
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var csrf = $("input[name='CSRF_TOKEN']").val();
          
            $.ajax({
                url: base_url + '/Filing/Caveat/getSelKeyword',
                cache: false,
                async: true,
                data: {
                    CSRF_TOKEN: csrf,
                    v_val: v_val,
                    sp_k_des: sp_k_des,
                    hd_max_keyword: hd_max_keyword
                },

                type: 'POST',
                success: function(data, status) {
					updateCSRFToken();
                    if (hd_max_keyword == 0)
                        $('#dv_sel_keyword').html(data);
                    else
                        $('#tb_a_keyword').append(data);
                    $('#hd_max_keyword').val(parseInt($('#hd_max_keyword').val()) + 1);
					
                },
                error: function(xhr) {
					updateCSRFToken();
                    alert("Error: " + xhr.status + " " + xhr.statusText);
                }

            });
        } else {
			updateCSRFToken();
            alert("Keyword already selected");
        }
    });
    $(document).on('click', '.added_keyword', function() {
        var rem_key_id = '';
        var idd = $(this).attr('id');
        var hd_rem_keyword = $('#hd_rem_keyword').val();
        //          alert(hd_rem_keyword);
        if (hd_rem_keyword == '')
            rem_key_id = $(this).val();
        else
            rem_key_id = hd_rem_keyword + '!' + $(this).val();

        //            alert(rem_key_id);
        $('#hd_rem_keyword').val(rem_key_id);
        var sp_id = idd.split('chk_a_keyword');
        $('#tr_a_keyword' + sp_id[1]).remove();
    });

    $(document).on('blur', '#txt_valuation', function() {
        get_tot_amt();
    });

    $(document).on('click', '#chk_sen_cs', function() {
        if ($(this).is(':checked'))
            $('#txt_sen_case').css('display', 'inline');
        else
            $('#txt_sen_case').css('display', 'none');
        $('#txt_sen_case').val('');
    });
});


function get_court_fee(str) {

    var hd_co_tot = document.getElementById('hd_co_tot').value;
    var lst_case = document.getElementById('lst_case').value;
    var d_no = document.getElementById('t_h_cno').value;
    var d_yr = document.getElementById('t_h_cyt').value;
    var chk_bench = '';
    for (var i = 1; i <= hd_co_tot; i++) {
        if (document.getElementById('hd_sp_a' + i)) {
            var hd_sp_a = document.getElementById('hd_sp_a' + i).value.trim();
            var hd_sp_b = document.getElementById('hd_sp_b' + i).value.trim();
            var hd_sp_c = document.getElementById('hd_sp_c' + i).value.trim();
            var hd_sp_c = document.getElementById('hd_sp_c' + i).value.trim();
            var hd_sp_d = document.getElementById('hd_sp_d' + i).value.trim();
            if (hd_sp_a == '')
                hd_sp_a = 0;
            if (hd_sp_b == '')
                hd_sp_b = 0;
            if (hd_sp_c == '')
                hd_sp_c = 0;

            if (chk_bench == '')
                chk_bench = hd_sp_a + '!' + hd_sp_b + '!' + hd_sp_c + '!' + hd_sp_d;
            else
                chk_bench = chk_bench + '#' + hd_sp_a + '!' + hd_sp_b + '!' + hd_sp_c + '!' + hd_sp_d;
        }
    }
    //              alert(chk_bench+'@'+lst_case);

    var CSRF_TOKEN = 'CSRF_TOKEN';
    var csrf = $("input[name='CSRF_TOKEN']").val();
    //updateCSRFToken();
	
    $.ajax({
        url: base_url + '/Filing/Caveat/chkValuation',
        cache: false,
        async: false,
        data: {
            CSRF_TOKEN: csrf,
            chk_bench: chk_bench,
            lst_case: lst_case
        },

        type: 'POST',
        success: function(data, status) {
            updateCSRFToken();
            $('#dv_c_fee_d').html(data);
            var hd_chk_status = $('#hd_chk_status').val();
            if (hd_chk_status == 1) {
                if (str == 1) {
                    get_res_cond();
                } else {
                    $('#txt_valuation').val('');
                    $('#txt_court_fee').attr('disabled', false);
                    $('#tr_val').css('display', 'table-row');
                    $('#tr_court_fee').css('display', 'none');
                }
            } else {
									updateCSRFToken();
				window.setTimeout(function () {

					 
					//                          $('#txt_court_fee').atrr('disabled',false);
					//                    $('#tr_val').css('display','none');   
					var CSRF_TOKEN = 'CSRF_TOKEN';
					var csrf = $("input[name='CSRF_TOKEN']").val();
					 
					$.ajax({
						url: base_url + '/Filing/Caveat/getCourtFee',
						cache: false,
						async: false,
						data: {
							CSRF_TOKEN: csrf,
							chk_bench: chk_bench,
							lst_case: lst_case,
							d_no: d_no,
							d_yr: d_yr
						},

						type: 'POST',
						 beforeSend: function() {
							 updateCSRFToken();
							$('#dv_src_keyword').html('<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>');
						},
						success: function(data, status) {
							//              alert(data);
							updateCSRFToken();
							$('#txt_court_fee_tot').val(data);
							$('#txt_court_fee').val(data);
							if (data == 0) {
								$('#tr_court_fee').css('display', 'none');
								$('#tr_court_fee_tot').css('display', 'none');
							} else {
								$('#tr_court_fee').css('display', 'table-row');
								$('#tr_court_fee_tot').css('display', 'table-row');
							}
							$('#txt_valuation').val('');
							$('#tr_val').css('display', 'none');
						},
						error: function(xhr) {
							alert("Error: " + xhr.status + " " + xhr.statusText);
						}

					});
				}, 2000);
			}
        },
        error: function(xhr) {
            alert("Error: " + xhr.status + " " + xhr.statusText);
        }

    });
}

function get_tot_amt() {

    if ($("#hd_m_court_fee_id").length) {

        get_res_cond();
    } else {
        get_court_fee('1');
    }
    //            alert(hd_m_court_fee_id);

}

function get_res_cond() {
    var hd_m_court_fee_id = $("#hd_m_court_fee_id").val();
    var txt_valuation = $('#txt_valuation').val();
    var d_no = document.getElementById('t_h_cno').value;
    var d_yr = document.getElementById('t_h_cyt').value;
    if (txt_valuation == '') {
        alert("Please enter valuation");
        $('#txt_valuation').focus();
    } else {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var csrf = $("input[name='CSRF_TOKEN']").val();

        $.ajax({
            url: 'get_tot_amt.php',
            cache: false,
            async: true,
            data: {
                CSRF_TOKEN: csrf,
                hd_m_court_fee_id: hd_m_court_fee_id,
                txt_valuation: txt_valuation,
                d_no: d_no,
                d_yr: d_yr
            },

            type: 'POST',
            success: function(data, status) {
                //             alert(data);
                updateCSRFToken();
                $('#txt_court_fee').val(data);
                $('#txt_court_fee').attr('disabled', false);
                $('#tr_court_fee').css('display', 'table-row');

            },
            error: function(xhr) {
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }

        });
    }
}