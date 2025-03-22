
function get_record(str) {
    // alert(str);
    var f_noo = '';
    var idn_id = '';
    var str1 = str.split('_');
    document.getElementById('hd_hd_spl').value = str1[1];
    var ck_id = str1[0];
    var str_res = '';
    //                           var str_p=str1[1];
    //                           var str_count=str1[1].length;
    //                           alert(str_count);
    //                           for(var yu=0;yu<(str_count-1);yu++)
    //                           {
    //                             if(str_res=='')
    //                                 str_res=str_p[yu];
    //                             else
    //                                   str_res=str_res+str_p[yu];
    //                           }

    str1 = str1[1].split('^');
    str_res = str1[0];
    //  alert(str_res);
    //   var hd_checke=document.getElementById('hd_checke'+str_res).value;


    // var hd_fc=  document.getElementById('hd_fc'+str_res).value;
    //    if(f_noo=='')
    //    f_noo= document.getElementById('hd_checke'+str_res).value-1;
    //else 
    //    f_noo=f_noo-1;
    //alert('hd_fil_no'+str_res)


    //alert(f_noo);
    if (document.getElementById('delete' + str1[0] + '^' + str1[1]).checked == false) {
        //            alert("Please Check Default To Remove");
        if (ck_id == 'btnRemove')
            alert("Please Check Default To Remove");
        else if (ck_id == 'btnIgnore')
            alert("Please Check Default To Ignore");
    }
    else if (document.getElementById('delete' + str1[0] + '^' + str1[1]).checked == true) {
        if (ck_id == 'btnRemove')
            idn_id = '0';
        else if (ck_id == 'btnIgnore')
            idn_id = '1';
        var fil_no = document.getElementById('hd_fil_no' + str_res).value;
        //alert(f_noo);
        // var hd_fc=document.getElementById('hd_fc'+str1[1]).value;
        if (document.getElementById('hd_fc' + str_res).value == '') {
            document.getElementById('hd_fc' + str_res).value = document.getElementById('hd_checke' + str_res).value - 1;
            f_noo = document.getElementById('hd_fc' + str_res).value;
        }
        else {
            document.getElementById('hd_fc' + str_res).value = document.getElementById('hd_fc' + str_res).value - 1
            f_noo = document.getElementById('hd_fc' + str_res).value;
        }
        //                if(f_noo<='0')
        //                   {
        ////                   document.getElementById('dv_sh_hd').style.display='block';
        ////                   document.getElementById('dv_fixedFor').style.marginTop='250px';
        //                   document.getElementById('hd_hd_sh_fno').value=fil_no;
        ////                  document.getElementById('dv_fixedFor').style.display='block';
        //                 //  document.getElementById('dv_fixedFor').style.marginLeft='50px';  
        //                  // alert(document.getElementById('dv_fixedFor'));
        //                  upd_fil_for(fil_no);
        //               }
        //               else
        //                   {
        var id_id = document.getElementById('hdId_' + str1[0] + str1[1]).value;

        var xmlhttp;
        if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        }
        else {// code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        //  document.getElementById('div_result').innerHTML = '<table widht="100%" align="center"><tr><td><img src="images/loading_new.gif"/></td></tr></table>';

        //  alert(i);
        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                // var txtAdeshika="txtAdeshika"+rowCount;
                document.getElementById('sData').innerHTML = xmlhttp.responseText;
                var hd_id_id = document.getElementById('hd_id_id').value;
                if (hd_id_id == 0) {
                    //                                alert("Default Not Removed");
                    //                            }
                    //                            else   if(hd_id_id==1)
                    //                                 alert("Default Removed Sucessfully");

                    if (idn_id == '0')
                        alert("Default Not Removed");
                    else if (idn_id == '1')
                        alert("Default Not Ignored");
                }
                else if (hd_id_id == 1) {
                    if (idn_id == '0')
                        alert("Default Removed Sucessfully");
                    else if (idn_id == '1')
                        alert("Default Ignored Sucessfully");
                }
                //                       $("#td_obj_name"+str1[1]).hide(600);
                //                       $("#td_ck_n"+str1[1]).hide(600);
                //                         $("#td_Remove"+str1[1]).hide(600);
                //alert(str1[1]);
                document.getElementById('td_obj_name' + str1[0] + str1[1]).style.color = 'red';
                document.getElementById('delete' + str1[0] + '^' + str1[1]).checked = false;
                document.getElementById('delete' + str1[0] + '^' + str1[1]).disabled = true;
                document.getElementById('btnRemove_' + str1[0] + '^' + str1[1]).style.display = 'none';
                document.getElementById('btnIgnore_' + str1[0] + '^' + str1[1]).disabled = true;
                document.getElementById('td_Remove' + str1[0] + str1[1]).innerHTML = "<span style='color:red'>Removed</span>";
            }
        }

        var url = base_url + "/Judicial/Proposal/get_result_1?id_id=" + id_id + "&f_noo=" + f_noo + "&fil_no=" + fil_no + "&idn_id=" + idn_id;
        // xmlhttp.open("GET","getReport51-II.php?seljudgename="+seljudgename+"&frm="+frm+"&toDate="+toDate,true);
        xmlhttp.open("GET", url, true);
        xmlhttp.send(null);
        //            }
    }
}

function cn_data() {
    document.getElementById('dv_sh_hd').style.display = 'none';
    document.getElementById('dv_fixedFor').style.display = 'none';
    //  document.getElementById('m_fixed').value='';
}

function upd_fil_for(str) {
    var fil_no = document.getElementById('hd_hd_sh_fno').value;
    //  alert(fil_no);
    //    var m_fixed=document.getElementById('m_fixed').value;
    //    if(m_fixed=='')
    //        alert("Plese Select Fixed For");


    var str1 = document.getElementById('hd_hd_spl').value;
    // alert(str1);
    str1 = str1.split('^');
    // alert(str1[1]);
    var id_id = document.getElementById('hdId_' + str1[0] + str1[1]).value;

    var xmlhttp;
    if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    }
    else {// code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    //  document.getElementById('div_result').innerHTML = '<table widht="100%" align="center"><tr><td><img src="images/loading_new.gif"/></td></tr></table>';

    //  alert(i);
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            // var txtAdeshika="txtAdeshika"+rowCount;
            document.getElementById('sData').innerHTML = xmlhttp.responseText;

            var hd_id_id = document.getElementById('hd_id_id').value;
            if (hd_id_id == 0) {
                alert("Default Not Removed");
            }
            else if (hd_id_id == 1) {
                var hd_stat_inf = $('#hd_stat_inf').val();
                var thdate = $('#hd_next_dt').val();

                var hd_ord_co = $('#hd_ord_co').val();
                var hd_subhead_gs = $('#hd_mul_recds').val();
                //                             alert(thdate);
                alert("Default Removed Sucessfully");
                //                               document.getElementById('btnListing_'+str1[0]).disabled=true;
                document.getElementById('RemoveAll_' + str1[0]).disabled = true;
                document.getElementById('td_obj_name' + str1[0] + str1[1]).style.color = 'red';
                document.getElementById('delete' + str1[0] + '^' + str1[1]).checked = false;
                document.getElementById('delete' + str1[0] + '^' + str1[1]).disabled = true;
                document.getElementById('btnRemove_' + str1[0] + '^' + str1[1]).style.display = 'none';
                document.getElementById('td_Remove' + str1[0] + str1[1]).innerHTML = "<span style='color:red'>Removed</span>";
                if (($('#hd_prop_not').val() == 1 && hd_ord_co == 1) || hd_ord_co == 2)
                    fsubmit(str, hd_stat_inf, thdate, hd_ord_co, hd_subhead_gs);
                else {
                    alert("Cant make proposal")
                }
            }

        }
    }


    // xmlhttp.open("GET","getReport51-II.php?seljudgename="+seljudgename+"&frm="+frm+"&toDate="+toDate,true);
    xmlhttp.open("GET", hd_folder + "/get_result_1.php?id_id=" + id_id + "&f_noo=0" + "&fil_no=" + fil_no, true);
    xmlhttp.send(null);


}

function call_listing(filno) {
    //alert(filno)
    var xmlhttp;
    if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    }
    else {// code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    //document.getElementById('div_result').innerHTML = '<table widht="100%" align="center"><tr><td>Loading..</td></tr></table>';

    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            // var txtAdeshika="txtAdeshika"+rowCount;
            //document.getElementById('div_result').innerHTML=xmlhttp.responseText;
            call_prop_s(filno);
        }
    }
    // xmlhttp.open("GET","getReport51-II.php?seljudgename="+seljudgename+"&frm="+frm+"&toDate="+toDate,true);
    xmlhttp.open("GET", "call_listing.php?fil_no=" + filno + "&objrem=Y" + "&ip=" + $("#hd_ipadd").val() + "&mac=" + $("#hd_macadd").val(), true);
    xmlhttp.send(null);
}

function call_prop_s(filno, check_ia_not) {
    var xmlhttp;
    if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    }
    else {// code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    //document.getElementById('div_result').innerHTML = '<table widht="100%" align="center"><tr><td>Loading..</td></tr></table>';

    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            // var txtAdeshika="txtAdeshika"+rowCount;
            document.getElementById('dv_fixedFor_P').style.marginTop = '50px';
            document.getElementById('dv_sh_hd').style.display = 'block';
            document.getElementById('dv_fixedFor_P').style.display = 'block';
            document.getElementById('sp_mnb_p').innerHTML = xmlhttp.responseText;
            document.getElementById('tb_clr').style.backgroundColor = 'white';
            if (document.getElementById('tb_clr_n'))
                document.getElementById('tb_clr_n').style.backgroundColor = 'white';
            document.getElementById('del_prop').style.display = 'none';
        }
    }
    // xmlhttp.open("GET","getReport51-II.php?seljudgename="+seljudgename+"&frm="+frm+"&toDate="+toDate,true);
    xmlhttp.open("GET", "get_proposal.php?fno=" + filno, true);
    xmlhttp.send(null);
}

function save_proposal() {
    if (document.getElementById('heading').value == '') {
        alert('Please Select Heading');
        document.getElementById('heading').focus();
        return false;
    }
    if (document.getElementById('subhead').value == '') {
        alert('Please Select SubHeading');
        document.getElementById('subhead').focus();
        return false;
    }

    var xmlhttp;
    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    }
    else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            document.getElementById('sp_mnb_p').innerHTML = xmlhttp.responseText;
            document.getElementById('sp_close').style.display = 'block';
            //            if( document.getElementById('dv_fixedFor_P'))
            //                 document.getElementById('dv_fixedFor_P').style.display='none';
            document.getElementById('sp_mnb_p').style.width = 'auto';
            document.getElementById('sp_mnb_p').style.height = 'auto';
            document.getElementById('sp_mnb_p').style.overflow = 'hidden';
        }
    }

    var bnb = document.getElementById('before_nbefore').value;
    var jud1 = document.getElementById('bef_nbef_j1').value;
    var jud2 = document.getElementById('bef_nbef_j2').value;
    if (bnb != '') {
        if (jud1 == '' && jud2 == '') {
            alert('Please Select Atleast One Judge');
            document.getElementById('bef_nbef_j1').focus();
            return false;
        }
        else if (jud1 == '' && jud2 != '') {
            alert('First Select J1 then J2');
            document.getElementById('bef_nbef_j1').focus();
            return false;
        }
        else if (jud1 == jud2) {
            alert('J1 and J2 Could Not Be Equal');
            document.getElementById('bef_nbef_j1').focus();
            return false;
        }
    }

    var url = "save_proposal.php?fil_no=" + document.getElementById('fil_hd').value + "&thd=" + document.getElementById('thd').value
        + "&heading=" + document.getElementById('heading').value + "&subhead=" + document.getElementById('subhead').value + "&purList="
        + document.getElementById('purList').value + "&sinfo=" + document.getElementById('sinfo').value + "&ldir=" + document.getElementById('ldir').value + "&lcc=" + document.getElementById('lcc').value;

    var ben = document.getElementById('listbench').value;
    if (ben == 'S')
        url = url + "&listbench=" + ben + "&j1=" + document.getElementById('txtJudgeName1').value;
    else if (ben == 'D')
        url = url + "&listbench=" + ben + "&j1=" + document.getElementById('txtJudgeName1').value + "&j2=" + document.getElementById('txtJudgeName2').value;
    else if (ben == 'F')
        url = url + "&listbench=" + ben + "&j1=" + document.getElementById('txtJudgeName1').value + "&j2=" + document.getElementById('txtJudgeName2').value +
            "&j3=" + document.getElementById('txtJudgeName3').value + "&j4=" + document.getElementById('txtJudgeName4').value + "&j5=" + document.getElementById('txtJudgeName5').value;
    else if (ben == 'L')
        url = url + "&listbench=" + ben + "&j1=" + document.getElementById('txtJudgeName1').value + "&j2=" + document.getElementById('txtJudgeName2').value;
    else if (ben == 'R')
        url = url + "&listbench=" + ben;

    url = url + "&ip=" + $("#hd_ipadd").val() + "&mac=" + $("#hd_macadd").val();


    if (bnb == 'B') {
        url = url + "&lb=Y&lb1=" + jud1 + "&lb2=" + jud2;
    }
    else if (bnb == 'N') {
        url = url + "&nlb=Y&nlb1=" + jud1 + "&nlb2=" + jud2;
    }
    //alert(url);
    xmlhttp.open("GET", url, true);
    xmlhttp.send(null);
}


function getJud(val) {
    if (val != '') {
        document.getElementById('bef_nbef_j1').disabled = false;
        document.getElementById('bef_nbef_j2').disabled = false;
        if (val == 'B') {
            document.getElementById('bef_nbef_j1').style.color = 'Green';
            document.getElementById('bef_nbef_j2').style.color = 'Green';
        }
        else if (val == 'N') {
            document.getElementById('bef_nbef_j1').style.color = 'Red';
            document.getElementById('bef_nbef_j2').style.color = 'Red';
        }
    }
    else if (val == '') {
        document.getElementById('bef_nbef_j1').disabled = true;
        document.getElementById('bef_nbef_j1').value = '';
        document.getElementById('bef_nbef_j1').style.color = 'Black';
        document.getElementById('bef_nbef_j2').disabled = true
        document.getElementById('bef_nbef_j2').value = '';
        document.getElementById('bef_nbef_j2').style.color = 'Black';
    }
    var xmlhttp;
    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    }
    else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            document.getElementById('bef_nbef_j1').innerHTML = xmlhttp.responseText;
            document.getElementById('bef_nbef_j2').innerHTML = xmlhttp.responseText;
        }
    }
    var url = "get_judname.php?variable=2&val=" + val;

    xmlhttp.open("GET", url, true);
    if (val != '')
        xmlhttp.send(null);
}



function closeData() {
    if (document.getElementById('overlay'))
        document.getElementById('overlay').style.display = "none";
    document.getElementById('dv_fixedFor_P').style.display = "none";
    document.getElementById('dv_sh_hd').style.display = "none";
    document.getElementById('sp_close').style.display = 'none';
}

function closeData_cx() {
    document.getElementById('dv_fixedFor_P_cx').style.display = "none";
    document.getElementById('dv_sh_hd_cx').style.display = "none";
    document.getElementById('sp_close_cx').style.display = 'none';
}

function slt_rj1_ct(str) {
    var ck_tr_n = '0';
    var ck_tr_n_kl = '0';
    var str1 = str.split('_');
    var hd_tot_no = document.getElementById('hd_tot_no' + str1[1]).value;
    for (var i_p = 0; i_p < hd_tot_no; i_p++) {
        if (document.getElementById('delete' + str1[1] + '^' + i_p).checked == true || document.getElementById('delete' + str1[1] + '^' + i_p).disabled == true) {
            ck_tr_n = 1;
            ck_tr_n_kl++;
            //                      if(spo_obj_name_tot=='')
            //                    spo_obj_name_tot=document.getElementById('hdId_'+str1[1]+i_p).value+'!'+ document.getElementById('spo_obj_name'+str1[1]+i_p).innerHTML;
            //                else
            //                     spo_obj_name_tot=spo_obj_name_tot+'!!'+document.getElementById('hdId_'+str1[1]+i_p).value+'!'+ document.getElementById('spo_obj_name'+str1[1]+i_p).innerHTML; 
        }
    }
    if (ck_tr_n == '0') {
        alert("Please Check Default having IA for Listing");
    }
    else if ((hd_tot_no) != ck_tr_n_kl) {
        alert("Please Select All Default for Listing");
    }
    else {

        document.getElementById('dv_sh_hd').style.display = 'block';
        //document.getElementById('dv_fixedFor_P_x').style.marginTop='50px';
        // document.getElementById('hd_hd_sh_fno').value=fil_no;
        //  document.getElementById('hd_hd_category').value=hd_category;
        document.getElementById('dv_fixedFor_P_x').style.display = 'block';
        var xmlhttp;
        if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        }
        else {// code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }



        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {

                document.getElementById('sp_mnb_p_x').innerHTML = xmlhttp.responseText;


            }
        }



        xmlhttp.open("GET", "get_result_ct_rj.php?str=" + str, true);
        xmlhttp.send(null);
    }
}




function get_Listing(str, ck_c_r) {
    //  alert(str);
    var f_noo = '';
    var ck_tr_n = '0';
    var ck_tr_n_kl = '0';
    var str1 = str.split('_');
    document.getElementById('hd_hd_spl').value = str1[1];
    var spo_obj_name_tot = '';
    //  var spo_status='';
    var hd_tot_no = document.getElementById('hd_tot_no' + str1[1]).value;
    //      for(var i_p=0;i_p<hd_tot_no;i_p++)
    //          {
    //              if(document.getElementById('delete'+str1[1]+i_p).checked==true || document.getElementById('delete'+str1[1]+i_p).disabled==true)
    //                  {
    //                      ck_tr_n=1;
    //                      ck_tr_n_kl++;
    ////                      if(spo_obj_name_tot=='')
    ////                    spo_obj_name_tot=document.getElementById('hdId_'+str1[1]+i_p).value+'!'+ document.getElementById('spo_obj_name'+str1[1]+i_p).innerHTML;
    ////                else
    ////                     spo_obj_name_tot=spo_obj_name_tot+'!!'+document.getElementById('hdId_'+str1[1]+i_p).value+'!'+ document.getElementById('spo_obj_name'+str1[1]+i_p).innerHTML; 
    //            }
    //          }
    //          if(ck_tr_n=='0')
    //              {
    //                    alert("Please Check Default having IA for Listing");
    //              }
    //              else if((hd_tot_no)!=ck_tr_n_kl)
    //                  {
    //                       alert("Please Select All Default for Listing");
    //                  }
    //              else
    //                  {
    var str_res = '';


    str2 = str1[1].split('^');
    str_res = str2[0];



    var fil_no = document.getElementById('hd_fil_no' + str_res).value;
    var hd_category = document.getElementById('hd_category' + str_res).value;
    //   if(document.getElementById('hd_fc'+str_res).value=='')
    //       {
    //   document.getElementById('hd_fc'+str_res).value= document.getElementById('hd_checke'+str_res).value-1;
    //   f_noo= document.getElementById('hd_fc'+str_res).value;
    //       }
    //else
    //    {
    //       document.getElementById('hd_fc'+str_res).value=document.getElementById('hd_fc'+str_res).value-1
    //       f_noo=document.getElementById('hd_fc'+str_res).value;
    //    }

    //alert(f_noo);
    //    if(document.getElementById('delete'+str1[0]+str1[1]).checked==false)
    //        {
    //            alert("Please Check Default having IA for Listing");
    //        }
    //        else if(document.getElementById('delete'+str1[0]+str1[1]).checked==true)
    //            {
    for (var i_p = 0; i_p < hd_tot_no; i_p++) {
        if (document.getElementById('delete' + str1[1] + '^' + i_p).checked == true) {
            // ck_tr_n=1;
            if (spo_obj_name_tot == '')
                spo_obj_name_tot = document.getElementById('hdId_' + str1[1] + i_p).value + '!' + document.getElementById('spo_obj_name' + str1[1] + i_p).innerHTML + '^' + document.getElementById('hdstatus_' + str1[1] + i_p).value;
            else
                spo_obj_name_tot = spo_obj_name_tot + '!!' + document.getElementById('hdId_' + str1[1] + i_p).value + '!' + document.getElementById('spo_obj_name' + str1[1] + i_p).innerHTML + '^' + document.getElementById('hdstatus_' + str1[1] + i_p).value;

            //                if(spo_status=='')
            //                      spo_status=document.getElementById('hdstatus_'+str1[1]+i_p).value;
            //                  else
            //                        spo_status=spo_status+'^'+document.getElementById('hdstatus_'+str1[1]+i_p).value;

        }
    }

    //    alert(spo_obj_name_tot);            

    var xmlhttp;
    if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    }
    else {// code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }



    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {

            document.getElementById('sp_mnb_p_x_dum').innerHTML = xmlhttp.responseText;
            var hd_id_id = document.getElementById('hd_hd_id_id').value;
            if (hd_id_id == 0) {
                alert("No IA Present");
            }
            else if (hd_id_id == 1) {
                document.getElementById('dv_sh_hd').style.display = 'block';
                //document.getElementById('dv_fixedFor_P_x').style.marginTop='50px';
                document.getElementById('hd_hd_sh_fno').value = fil_no;
                document.getElementById('hd_hd_category').value = hd_category;
                document.getElementById('dv_fixedFor_P_x').style.display = 'block';
                //  document.getElementById('sp_iaNumber').style.display='block';
            }
            //                      

        }
    }


    // xmlhttp.open("GET","getReport51-II.php?seljudgename="+seljudgename+"&frm="+frm+"&toDate="+toDate,true);
    //                 xmlhttp.open("GET","get_result_Ia.php?fil_no="+fil_no+"&spo_obj_name_tot="+spo_obj_name_tot+"&str="+str+"&ck_c_r="+ck_c_r,true);
    //          xmlhttp.send(null);

    xmlhttp.open("POST", "get_result_Ia.php", true);



    xmlhttp.setRequestHeader("Content-Type", "text/html;charset=utf-8;");
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.setRequestHeader("accept-charset", "UTF-8");
    xmlhttp.send("fil_no=" + fil_no + "&spo_obj_name_tot=" + spo_obj_name_tot + "&str=" + str + "&ck_c_r=" + ck_c_r);

    //   }
    //  } 

}

function upd_fil_for_x() {
    var cn_data = 0;
    var snoq = 0;
    var chk_rd_rj = 0;
    var check_ia_not = 0;
    // var cnt_ia_no=0;
    var fil_no = document.getElementById('hd_hd_sh_fno').value;
    var hd_category = document.getElementById('hd_hd_category').value;
    //    var m_fixed=document.getElementById('m_fixed_x').value;
    // alert(fil_no);
    var m_fixed = document.getElementById('m_fixed_x').value;
    //  alert(m_fixed);
    var hd_hd_ssno = document.getElementById('hd_hd_ssno').value;
    var hd_snoq = document.getElementById('hd_snoq').value;

    var hd_snoq = document.getElementById('hd_snoq').value;
    //           if(document.getElementById('rd_rj').checked==true)
    //       {
    ////      if(hd_category!='139')
    ////      {
    ////     for(var ty=1;ty<hd_hd_ssno;ty++)
    ////           {
    ////               if(document.getElementById('chk_Ia'+ty).checked==true)
    ////                   {
    ////                       cn_data=1;
    ////                   }
    ////           }
    ////            for(var tyzx=1;tyzx<hd_snoq;tyzx++)
    ////           {
    ////               if(document.getElementById('chk_def'+tyzx).checked==true)
    ////                   {
    ////                       snoq=1;
    ////                   }
    ////           }
    ////            }
    //           //  else
    //        //   {
    //                for(var tyzx=1;tyzx<hd_snoq;tyzx++)
    //           {
    //               if(document.getElementById('chk_def'+tyzx).checked==true)
    //                   {
    //                       snoq=1;
    //                   }
    //           }
    //           if(snoq==1)
    //               {
    //                    for(var ty=1;ty<hd_hd_ssno;ty++)
    //           {
    //               if(document.getElementById('chk_Ia'+ty).checked==true)
    //                   {
    //                       cn_data=1;
    //                   }
    //           }
    //               }
    //               else
    //                   {
    //                       snoq=1;
    //                       cn_data=1;
    //                   }
    //         //  }
    //       }
    //       else if(document.getElementById('rd_C').checked==true)
    //           {
    //                 for(var tyzx=1;tyzx<hd_snoq;tyzx++)
    //           {
    //               if(document.getElementById('chk_def'+tyzx).checked==true)
    //                   {
    //                       snoq=1;
    //                   }
    //           }
    //           if(snoq==1)
    //               {
    //                    for(var ty=1;ty<hd_hd_ssno;ty++)
    //           {
    //               if(document.getElementById('chk_Ia'+ty).checked==true)
    //                   {
    //                       cn_data=1;
    //                   }
    //           }
    //               }
    //               else
    //                   {
    //                       snoq=1;
    //                       cn_data=1;
    //                   }
    //           }
    if (document.getElementById('rd_rj').checked == true || document.getElementById('rd_C').checked == true) {
        chk_rd_rj = 1;
    }

    // if(cn_data==0 || m_fixed=='' || snoq==0 || chk_rd_rj==0)
    if (m_fixed == '' || chk_rd_rj == 0) {
        // if(cn_data==0)
        //  alert("Please Select IA for Defaults");
        if (chk_rd_rj == 0)
            alert("Please Select Court Or RJ-I");
        else if (m_fixed == '')
            alert("Plese Select Fixed For");
        //   if(snoq==0)
        //  alert("Please Select Default having IA");

    }
    else {

        var hd_snoq = document.getElementById('hd_snoq').value;
        for (var hdq = 1; hdq < hd_snoq; hdq++) {
            //   alert(hdq);
            // cnt_ia_no++;
            //                         var sta_y='';
            //                         if(document.getElementById('chk_def'+hdq).checked==true)
            //                   {
            //                       sta_y='0';
            //                   }
            //                   else if(document.getElementById('chk_def'+hdq).checked==false)
            //                       {
            //                            sta_y='1';
            //                       }
            var sp_iaNY = '';
            var hd_sno = document.getElementById('hd_sno' + hdq).value;
            var hd_status = document.getElementById('hd_status' + hdq).value;

            //                       for(var ty=1;ty<hd_hd_ssno;ty++)
            //           {
            //               if(document.getElementById('chk_Ia'+ty).checked==true)
            //                   {
            //                       if(sp_iaNY=='')
            //                       sp_iaNY=document.getElementById('sp_iaNY'+ty).innerHTML.trim();
            //                   else
            //                      sp_iaNY=sp_iaNY+','+document.getElementById('sp_iaNY'+ty).innerHTML.trim();
            //                      
            //                   }
            //           }
            var hd_subSno = document.getElementById('hd_subSno' + hdq).value;

            for (var ty = 0; ty < hd_subSno; ty++) {
                if (document.getElementById('chk_Ia' + hdq + '^' + ty).checked == true) {
                    if (sp_iaNY == '')
                        sp_iaNY = document.getElementById('spIANo_' + hdq + '^' + ty).innerHTML.trim();
                    else
                        sp_iaNY = sp_iaNY + ',' + document.getElementById('spIANo_' + hdq + '^' + ty).innerHTML.trim();
                    check_ia_not = 1;
                }
            }
            //     alert(sp_iaNY);
            var xmlhttp;
            if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();
            }
            else {// code for IE6, IE5
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }


            if (hdq == (hd_snoq - 1)) {
                xmlhttp.onreadystatechange = function () {
                    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {

                        document.getElementById('sData1').innerHTML = xmlhttp.responseText;
                        var rd_RJC = '';
                        if (document.getElementById('hd_send_ia').value == '1') {
                            alert("Default Moved");
                            document.getElementById('dv_fixedFor_P_x').style.display = 'none';
                            if (document.getElementById('rd_rj').checked == true) {
                                if (document.getElementById('m_fixed_x').value == 'E')
                                    rd_RJC = 'Z';
                                else
                                    rd_RJC = 'Y';
                            }
                            else if (document.getElementById('rd_C').checked == true) {
                                if (document.getElementById('m_fixed_x').value == 'E')
                                    rd_RJC = 'Z';
                                else
                                    rd_RJC = 'N';
                            }
                            document.getElementById('sp_mnb_p').style.height = ' 500px';
                            document.getElementById('sp_mnb_p').style.overflow = 'scroll';
                            //  margin-left: 50px;margin-right: 50px;margin-bottom: 25px;margin-top: 1px;
                            document.getElementById('sp_mnb_p').style.marginLeft = '50px';
                            document.getElementById('sp_mnb_p').style.marginRight = '50px';
                            document.getElementById('sp_mnb_p').style.marginBottom = '25px';
                            document.getElementById('sp_mnb_p').style.marginTop = '1px';
                            call_listing1(fil_no, rd_RJC, check_ia_not);
                        }
                    }

                }

            }

            if (document.getElementById('rd_rj').checked == true) {
                //                 xmlhttp.open("GET","send_Ia_de.php?fil_no="+fil_no+"&sp_iaNY="+sp_iaNY+"&hd_sno="+hd_sno+
                //                     "&m_fixed="+m_fixed+"&hd_snoq="+hd_snoq+"&hdq="+hdq+"&hd_status="+hd_status,false);


                xmlhttp.open("POST", "send_Ia_de.php", false);



                xmlhttp.setRequestHeader("Content-Type", "text/html;charset=utf-8;");
                xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xmlhttp.setRequestHeader("accept-charset", "UTF-8");
                xmlhttp.send("fil_no=" + fil_no + "&sp_iaNY=" + sp_iaNY + "&hd_sno=" + hd_sno +
                    "&m_fixed=" + m_fixed + "&hd_snoq=" + hd_snoq + "&hdq=" + hdq + "&hd_status=" + hd_status);

            }
            else if (document.getElementById('rd_C').checked == true) {
                //                              xmlhttp.open("GET","send_Ia_de_court.php?fil_no="+fil_no+"&sp_iaNY="+sp_iaNY+"&hd_sno="+hd_sno+
                //                     "&m_fixed="+m_fixed+"&hd_snoq="+hd_snoq+"&hdq="+hdq+"&hd_status="+hd_status,false);

                xmlhttp.open("POST", "send_Ia_de_court.php", false);



                xmlhttp.setRequestHeader("Content-Type", "text/html;charset=utf-8;");
                xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xmlhttp.setRequestHeader("accept-charset", "UTF-8");
                xmlhttp.send("fil_no=" + fil_no + "&sp_iaNY=" + sp_iaNY + "&hd_sno=" + hd_sno +
                    "&m_fixed=" + m_fixed + "&hd_snoq=" + hd_snoq + "&hdq=" + hdq + "&hd_status=" + hd_status);

            }

            // xmlhttp.send(null);

            var hd_str = document.getElementById('hd_str').value;

            var str1 = hd_str.split('_');
            document.getElementById('hd_hd_spl').value = str1[1];
            var spo_obj_name_tot = '';
            var hd_tot_no = document.getElementById('hd_tot_no' + str1[1]).value;
            document.getElementById('btnListing_' + str1[1]).disabled = true;
            document.getElementById('RemoveAll_' + str1[1]).disabled = true;
            for (var i_pyy = 0; i_pyy < hd_tot_no; i_pyy++) {
                if (document.getElementById('delete' + str1[1] + '^' + i_pyy).checked == true) {
                    document.getElementById('td_obj_name' + str1[1] + i_pyy).style.color = 'maroon';
                    document.getElementById('delete' + str1[1] + '^' + i_pyy).checked = false;
                    document.getElementById('delete' + str1[1] + '^' + i_pyy).disabled = true;
                    document.getElementById('btnRemove_' + str1[1] + '^' + i_pyy).style.display = 'none';
                    document.getElementById('td_Remove' + str1[1] + i_pyy).innerHTML = "<span style='color:maroon'>-</span>";
                    //                           document.getElementById('dv_sh_hd').style.display='none';    
                    //                            document.getElementById('dv_fixedFor').style.display='none';
                    //                              document.getElementById('m_fixed').value='';
                }
            }


        }
    }

}
function call_listing1(filno, str, check_ia_not) {
    //alert(filno)
    var xmlhttp;
    if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    }
    else {// code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    //document.getElementById('div_result').innerHTML = '<table widht="100%" align="center"><tr><td>Loading..</td></tr></table>';

    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            // var txtAdeshika="txtAdeshika"+rowCount;
            //document.getElementById('div_result').innerHTML=xmlhttp.responseText;
            call_prop_s(filno, check_ia_not);
        }
    }
    // xmlhttp.open("GET","getReport51-II.php?seljudgename="+seljudgename+"&frm="+frm+"&toDate="+toDate,true);
    xmlhttp.open("GET", "call_listing.php?fil_no=" + filno + "&list=" + str + "&check_ia_not=" + check_ia_not + "&ip=" + $("#hd_ipadd").val() + "&mac=" + $("#hd_macadd").val(), true);
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

function displayJud(val) {
    var str = ''
    if (val == '' || val == 'R')
        str = '';
    else if (val == "S")
        str = 1;
    else if (val == "D" || val == "L")
        str = 2;
    else if (val == "F")
        str = 5;

    var xmlhttp;
    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    }
    else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            document.getElementById('judgearea').innerHTML = xmlhttp.responseText;
        }
    }
    xmlhttp.open("GET", "getJudge_fixed.php?str=" + str + "&v=" + val + "&sh=" + document.getElementById('subhead').value, true);
    xmlhttp.send(null);
}


function del_pro() {
    var xmlhttp;
    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    }
    else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            alert(xmlhttp.responseText);
            call_getDetails(document.getElementById('fil_hd').value);
        }
    }
    var url = "del_proposal.php?fil_no=" + document.getElementById('fil_hd').value + "&ip=" + $("#hd_ipadd").val() + "&mac=" + $("#hd_macadd").val();
    xmlhttp.open("GET", url, true);
    xmlhttp.send(null);
}

function get_def_rec() {
    // alert("zxzx");
    var s_nm = 1;
    var mn = document.getElementById('mn').value;
    var cs_tp = document.getElementById('cs_tp').value;
    if (cs_tp.length == 2) {
        cs_tp = '0' + cs_tp;
    }
    var txtFNo = document.getElementById('txtFNo').value;
    var txtYear = document.getElementById('txtYear').value;
    var total = mn + cs_tp + txtFNo + txtYear;
    // alert(cs_tp);
    if (cs_tp == '') {
        txtFNo = document.getElementById('txtFNo').value = '';
        txtYear = document.getElementById('txtYear').value = '';
        s_nm = 0;
    }
    if ((txtFNo.length == '5' && txtYear.length == '4') || (cs_tp == '' && txtFNo == '' && txtYear == '')) {
        var xmlhttp;
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        }
        else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        document.getElementById('dv_dup').innerHTML = '<table widht="100%" align="center"><tr><td><img src="preloader.gif"/></td></tr></table>';

        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {

                document.getElementById('dv_dup').innerHTML = xmlhttp.responseText;

            }
        }


        // xmlhttp.open("GET","getReport51-II.php?seljudgename="+seljudgename+"&frm="+frm+"&toDate="+toDate,true);
        xmlhttp.open("GET", "get_obj_cl_ret_dup.php?total=" + total + "&s_nm=" + s_nm, true);
        xmlhttp.send(null);
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

function RemoveAll(str) {
    var ck_tr_n = '0';
    var ck_jk = '0';
    var f_noo = '';
    var str1 = str.split('_');

    var hd_tot_no = document.getElementById('hd_tot_no' + str1[1]).value;
    var str_res = str1[1];
    for (var i_p = 0; i_p < hd_tot_no; i_p++) {
        //              if(document.getElementById('delete'+str1[1]+i_p).checked==true || document.getElementById('delete'+str1[1]+i_p).disabled==true)
        if (document.getElementById('delete' + str1[1] + '^' + i_p).checked == true) {
            ck_tr_n++;

        }
    }
    if (ck_tr_n == '0') {
        alert("Please Check atleast one Default");
    }
    else {
        for (var i_p = 0; i_p < hd_tot_no; i_p++) {

            var fil_no = document.getElementById('hd_fil_no' + str_res).value;
            if (document.getElementById('delete' + str1[1] + '^' + i_p).checked == true) {
                ck_jk++;
                if (document.getElementById('hd_fc' + str_res).value == '') 
                {
                    document.getElementById('hd_fc' + str_res).value = document.getElementById('hd_checke' + str_res).value - 1;
                    f_noo = document.getElementById('hd_fc' + str_res).value;
                } 
                else 
                {
                    document.getElementById('hd_fc' + str_res).value = document.getElementById('hd_fc' + str_res).value - 1
                    f_noo = document.getElementById('hd_fc' + str_res).value;
                }



                //     if(f_noo<='0')
                //                   {
                //                   document.getElementById('hd_hd_spl').value=str1[1]+'^'+i_p;
                ////                  document.getElementById('dv_sh_hd').style.display='block';
                ////                   document.getElementById('dv_fixedFor').style.marginTop='250px';
                //                   document.getElementById('hd_hd_sh_fno').value=fil_no;
                ////                  document.getElementById('dv_fixedFor').style.display='block';
                //                  
                //                   upd_fil_for(fil_no);
                //                 //  document.getElementById('dv_fixedFor').style.marginLeft='50px';  
                //                  // alert(document.getElementById('dv_fixedFor'));
                //               }
                //               else
                //                   {
                var id_id = document.getElementById('hdId_' + str1[1] + i_p).value;

                var xmlhttp;
                if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
                    xmlhttp = new XMLHttpRequest();
                }
                else {// code for IE6, IE5
                    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                }
                //  document.getElementById('div_result').innerHTML = '<table widht="100%" align="center"><tr><td><img src="images/loading_new.gif"/></td></tr></table>';

                //  alert(i);
                //  if(i_p==(ck_tr_n-1))
                document.getElementById('td_obj_name' + str1[1] + i_p).style.color = 'red';
                document.getElementById('delete' + str1[1] + '^' + i_p).checked = false;
                document.getElementById('delete' + str1[1] + '^' + i_p).disabled = true;
                document.getElementById('btnRemove_' + str1[1] + '^' + i_p).style.display = 'none';
                document.getElementById('td_Remove' + str1[1] + i_p).innerHTML = "<span style='color:red'>Removed</span>";
                if (ck_tr_n == ck_jk) {
                    xmlhttp.onreadystatechange = function () {
                        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                            // var txtAdeshika="txtAdeshika"+rowCount;
                            document.getElementById('sData').innerHTML = xmlhttp.responseText;
                            var hd_id_id = document.getElementById('hd_id_id').value;
                            if (hd_id_id == 0) {
                                alert("Default Not Removed");
                            }
                            else if (hd_id_id == 1)
                                alert("Default Removed Sucessfully");
                            //                       $("#td_obj_name"+str1[1]).hide(600);
                            //                       $("#td_ck_n"+str1[1]).hide(600);
                            //                         $("#td_Remove"+str1[1]).hide(600);
                            //alert(str1[1]);

                        }
                    }

                }

                var url = base_url + "/Judicial/Proposal/get_result_1?id_id=" + id_id + "&f_noo=" + f_noo + "&fil_no=" + fil_no + "&idn_id=0";
                // xmlhttp.open("GET","getReport51-II.php?seljudgename="+seljudgename+"&frm="+frm+"&toDate="+toDate,true);
                xmlhttp.open("GET", url, false);
                xmlhttp.send(null);


                //                   }
            }
        }
    }
}

function get_cco_ret(str) {

    var xmlhttp;
    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    }
    else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }

    document.getElementById('dv_dup').innerHTML = '<table widht="100%" align="center"><tr><td><img src="preloader.gif"/></td></tr></table>';

    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {

            document.getElementById('dv_dup').innerHTML = xmlhttp.responseText;

        }
    }


    // xmlhttp.open("GET","getReport51-II.php?seljudgename="+seljudgename+"&frm="+frm+"&toDate="+toDate,true);
    xmlhttp.open("GET", "get_cco_ret_ur.php?str=" + str, true);
    xmlhttp.send(null);
    //}
}




function fsubmit(str, hd_stat_inf, thdate, hd_ord_co, hd_subhead_gs) {

    //   alert(str);
    $('#sp_close').css('display', 'block');

    $('#hd_f_ex').val(str);

    var ct = str.substr(2, 3);
    var caseno = str.substr(5, 5);
    var year = str.substr(10, 4);

    document.getElementById('ggg').style.width = 'auto';
    document.getElementById('ggg').style.height = ' 500px';
    document.getElementById('ggg').style.overflow = 'scroll';

    document.getElementById('ggg').style.marginLeft = '18px';
    document.getElementById('ggg').style.marginRight = '18px';
    document.getElementById('ggg').style.marginBottom = '25px';
    document.getElementById('ggg').style.marginTop = '30px';
    document.getElementById('dv_sh_hd').style.display = 'block';
    document.getElementById('dv_fixedFor_P').style.display = 'block';
    document.getElementById('dv_fixedFor_P').style.marginTop = '3px';
    //    var hd_ud=document.getElementById('hd_ud').value;
    document.getElementById("ggg").innerHTML = '<table align=center><tr><td><img src="ajax-preloader.gif"/></td></tr></table>';
    var ajaxRequest; // The variable that makes Ajax possible!
    try {
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e) {
        // Internet Explorer Browsers
        try {
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {

            try {
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }

    // Create a function that will receive data sent from the server
    ajaxRequest.onreadystatechange = function () {
        if (ajaxRequest.readyState == 4) {
            //                document.getElementById("hint").innerHTML = '';
            $("#ggg").html(ajaxRequest.responseText);

            set_ele(hd_stat_inf, thdate, hd_ord_co, hd_subhead_gs);

            //               document.getElementById("lst_subcat").value ='';
            //                document.getElementById("cat").value = '';
        }
    }
    var utype = document.getElementById("hd_ud_dg").value;
    var url = "case_entry_new_da_process.php";
    url = url + "?ct=" + ct + "&caseno=" + caseno + "&year=" + year + "&utype=" + utype + "&hd_ud=" + hd_ud;
    // alert(url);
    ajaxRequest.open("GET", url, true);
    ajaxRequest.send(null);
}



function set_ele(hd_stat_inf, thdate, hd_ord_co, hd_subhead_gs) {
    var ed = document.getElementById("editopen").value;
    $("#hdremp21").datepicker({ dateFormat: "dd/mm/yy", numberOfMonths: 2 });
    $("#hdremp24").datepicker({ dateFormat: "dd/mm/yy", numberOfMonths: 2 });
    $("#hdremp59").datepicker({ dateFormat: "dd/mm/yy", numberOfMonths: 2 });
    $("#hdremp91").datepicker({ dateFormat: "dd/mm/yy", numberOfMonths: 2 });
    //$("#hdremp129").datepicker({dateFormat: "dd/mm/yy", numberOfMonths: 2});
    $("#hdremp131").datepicker({ dateFormat: "dd/mm/yy", numberOfMonths: 2 });
    $("#hdremp70").datepicker({ dateFormat: "dd/mm/yy", numberOfMonths: 2 });
    $("#hdate").datepicker({ dateFormat: "dd/mm/yy", numberOfMonths: 2 });
    $("#thdate").datepicker({ dateFormat: "dd-mm-yy", changeMonth: true, changeYear: true });
    $("#hdremp21").keypress(function (e) {
        e.preventDefault();
    });
    $("#hdremp24").keypress(function (e) {
        e.preventDefault();
    });
    $("#hdremp59").keypress(function (e) {
        e.preventDefault();
    });
    $("#hdremp91").keypress(function (e) {
        e.preventDefault();
    });
    //$("#hdremp129").keypress(function (e){e.preventDefault();});
    $("#hdremp131").keypress(function (e) {
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
    var lo = document.getElementById("listorder").value;
    var tdt = $('#thdate').val();
    d11 = (document.getElementById('thdate_nm').value).split("-");
    d22 = (document.getElementById('thdate_h').value).split("-");
    d1 = new Date(d11[2], (d11[1] - 1), d11[0]);
    d2 = new Date(d22[2], (d22[1] - 1), d22[0]);
    /////DISABLE EDIT DATE
    //if(lo=="47" || lo=="1" || lo=="4" || lo=="5" || lo=="17" || lo=="9" || lo=="18" || lo=="50" || ed==0)
    if ((lo == "16" || lo == "2") && $("#mf_select").val() == "M" && d1.getTime() > d2.getTime()) {
        document.getElementById("thdate").value = document.getElementById('thdate_nm').value;
    }
    else {
        document.getElementById("thdate").value = document.getElementById('thdate_h').value;
    }
    //lo == "49" ||
    if (lo == "47" || tdt == "" || tdt == "00-00-0000")
        $('#thdate').prop('disabled', false);
    else {
        //   document.getElementById('thdate').value=document.getElementById('thdate_h').value;
        $('#thdate').prop('disabled', true);
    }

    /////DISABLE EDIT DATE

    get_subheading(hd_stat_inf, thdate, hd_ord_co, hd_subhead_gs);

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
            if (chk_val[0] !== 21 && chk_val[0] !== 24 && chk_val[0] !== 59 && chk_val[0] !== 131 && chk_val[0] !== 70 && chk_val[0] !== 91)
                $("#hdremp" + chk_val[0]).attr('readonly', false);
            $("#hdremp" + chk_val[0]).css('background-color', '#fff');
            $("#hdremp" + chk_val[0]).css('border', '1px solid #ccc');
            $("#hdremp" + chk_val[0]).focus();
            if (chk_val[0] == 91) {
                $("#partybutton").attr('disabled', false);
            }
        }
        else {
            $("#hdremp" + chk_val[0]).attr('readonly', true);
            $("#hdremp" + chk_val[0]).css('background-color', '#F5F5F5');
            $("#hdremp" + chk_val[0]).css('border', '1px solid #ccc');
            if (chk_val[0] == 91) {
                $("#partybutton").attr('disabled', true);
            }
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
        //if($('.tbl_hr').hasClass('shclass'))
    });
    //setTimeout(function() {change_judge();    }, 1000);
    $('.shclass').hide();
}


function get_subheading(hd_stat_inf, thdate, hd_ord_co, hd_subhead_gs) {
    var jj = 0;
    var sh = $('#sh_hidden').val();
    var admt = $('#admitted').val();
    var lo = $('#listorder').val();
    jj = $('#mf_select').val();
    var flno = $('#fil_no').val();
    if (jj != "L" && jj != "S") {
        $('#bench').prop('disabled', false);
    }
    else {
        $('#bench').prop('disabled', true);
    }
    if (jj == "M" || jj == "L" || jj == "S") {
        if (lo == 1) {
            $('#subhead_select').prop('disabled', true);
            $('#subhead_select1').prop('disabled', true);
            $('#subhead_select2').prop('disabled', true);
            $('#subhead_select3').prop('disabled', true);
            $('#btnSlide').prop('disabled', true);
        }
        document.getElementById("final_select").style.display = "none";
        document.getElementById("final_select1").style.display = "none";
        var xhr2 = getXMLHTTP();
        var str = "get_mf_subhead.php?mf=" + jj + "&sh=" + sh + "&flno=" + flno + "&admt=" + admt;
        xhr2.open("GET", str, true);
        xhr2.onreadystatechange = function () {
            if (xhr2.readyState == 4 && xhr2.status == 200) {
                var data = xhr2.responseText;
                var arr = data.split("|");
                if (data == "ERROR") {
                    //document.getElementById('subhead_select').innerHTML=xhr2.responseText;
                }
                else {
                    data = "<option value=''>SELECT</option>" + data;
                    //document.getElementById('mf_box').innerHTML=arr[0];
                    document.getElementById('subhead_select').innerHTML = data;
                    feed_rmrk(hd_stat_inf, thdate, hd_ord_co, hd_subhead_gs);
                }
            }
        }// inner function end
        xhr2.send(null);
    }
    else {
        if (lo == 1) {
            $('#subhead_select').prop('disabled', false);
            $('#subhead_select1').prop('disabled', false);
            $('#subhead_select2').prop('disabled', false);
            $('#subhead_select3').prop('disabled', false);
            $('#btnSlide').prop('disabled', false);
        }
        document.getElementById("final_select").style.display = "block";
        document.getElementById("final_select1").style.display = "block";
        if (document.getElementById('btn_c').className == "active")
            get_final_sh('C', 1);
        else if (document.getElementById('btn_r').className == "active")
            get_final_sh('R', 1);
        else if (document.getElementById('btn_wc').className == "active")
            get_final_sh('WC', 1);
        else if (document.getElementById('btn_wr').className == "active")
            get_final_sh('WR', 1);
        else if (document.getElementById('btn_wa').className == "active")
            get_final_sh('WA', 1);
        else if (document.getElementById('btn_ep').className == "active")
            get_final_sh('EP', 1);
        else if (document.getElementById('btn_pil').className == "active")
            get_final_sh('PIL', 1);
        else {
            if (!($('#btn_c').is(':disabled')))
                $('#btn_c').click();
            else if (!($('#btn_r').is(':disabled')))
                $('#btn_r').click();
            else if (!($('#btn_wc').is(':disabled')))
                $('#btn_wc').click();
            else if (!($('#btn_wr').is(':disabled')))
                $('#btn_wr').click();
            else if (!($('#btn_wa').is(':disabled')))
                $('#btn_wa').click();
            else if (!($('#btn_ep').is(':disabled')))
                $('#btn_ep').click();
            else if (!($('#btn_pil').is(':disabled')))
                $('#btn_pil').click();
        }
    }
    var tdt = $('#thdate').val();
    if (jj == "L" || jj == "S") {
        $('#thdate').prop('disabled', false);
        $('#listorder').val(16);
    }
    else {
        if (tdt == "" || tdt == "00-00-0000")
            $('#thdate').prop('disabled', false);
        else
            $('#thdate').prop('disabled', true);
        d11 = (document.getElementById('thdate_nm').value).split("-");
        d22 = (document.getElementById('thdate_h').value).split("-");
        d1 = new Date(d11[2], (d11[1] - 1), d11[0]);
        d2 = new Date(d22[2], (d22[1] - 1), d22[0]);
        // d1=new Date(document.getElementById('thdate_nm').value);
        //d2=new Date(document.getElementById('thdate_h').value);   
        if ((lo == "16" || lo == "2") && $("#mf_select").val() == "M" && d1.getTime() > d2.getTime())
            document.getElementById('thdate').value = document.getElementById('thdate_nm').value;
        else
            document.getElementById('thdate').value = document.getElementById('thdate_h').value;
    }
}

function feed_rmrk(hd_stat_inf, thdate, hd_ord_co, hd_subhead_gs) {
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

    if (hd_ord_co == 1)
        getDone_upd_cat('hd_chk_add1');
    call_f1(3, hd_stat_inf, thdate, hd_ord_co, hd_subhead_gs);
}

function call_f1(cnt, hd_stat_inf, thdate, hd_ord_co, hd_subhead_gs) {
    var divname = "";
    if (cnt == 1) {
        divname = "newb";
        $('#' + divname).width($(window).width() - 150);
        $('#' + divname).height($(window).height() - 120);
        $('#newb123').height($('#newb').height() - $('#newb1').height() - 50);
    }
    if (cnt == 2) {
        divname = "newc";
        $('#' + divname).width($(window).width() - 150);
        $('#' + divname).height($(window).height() - 120);
        $('#newc123').height($('#newc').height() - $('#newc1').height() - 50);
    }
    if (cnt == 3) {
        divname = "newp";
        $('#' + divname).width($(window).width() - 150);
        $('#' + divname).height($(window).height() - 120);
        $('#newp123').height($('#newp').height() - $('#newp1').height() - 50);
    }

    if (cnt == 4) {
        divname = "newadv";
        $('#' + divname).width('600px');
        $('#' + divname).height($(window).height() - 150);
        $('#newadv123').height($('#newadv').height() - $('#newadv1').height() - 50);
    }

    var newX = ($('#' + divname).width() / 2);
    var newY = ($('#' + divname).height() / 2);
    document.getElementById(divname).style.marginLeft = "-" + newX + "px";
    document.getElementById(divname).style.marginTop = "-" + newY + "px";
    document.getElementById(divname).style.display = 'block';
    document.getElementById(divname).style.zIndex = 10;

    if (hd_ord_co == 1) {


        $('#listorder').val('24');
        $('#mf_select').val('M');
        //    alert(hd_subhead_gs);
        $('#subhead_select').val(hd_subhead_gs);
        //    alert(hd_stat_inf);
        $('#brdrem').val(hd_stat_inf);
        //    alert(thdate);
        $('#thdate').val(thdate);
        getSlide(hd_ord_co);
        save_rec_prop(0, hd_ord_co);
    }
    $('#overlay').height($(window).height());
    document.getElementById('overlay').style.display = 'block';


    //    alert($('#listorder').val());
}

function escapeRegExp(string) {
    return string.replace(/([.*+?^=!:${}()|\[\]\/\\])/g, "\\$1");
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
    //if(cnt==3)
    //    fsubmit();  
    $('#dv_fixedFor_P,#dv_sh_hd').css('display', 'none');
    //$('#dv_sh_hd').css('display','none');
}


function save_rec_prop(s_t, hd_ord_co) {
    var curr_date = document.getElementById("curr_date").value;
    var case_receive_da = 0;
    if ($("#da_case_rec_chkbx").is(':checked'))
        case_receive_da = 1;

    var qte_array = new Array();
    var today = new Date(curr_date);
    var url = "insert_rec_prop.php";
    //var url = "plist.php";
    var http = new getXMLHttpRequestObject();
    var str1 = "";
    var flno = document.getElementById("fil_no").value;
    var thdt = document.getElementById("thdate").value;
    var thdt1 = thdt.split("-");
    var thdt_new = thdt1[2] + "-" + thdt1[1] + "-" + thdt1[0];
    var hdate = new Date(thdt.replace("-", "/"));
    var lo = document.getElementById('listorder').value;
    if (lo == "" || lo == "32" || lo == "48") {
        if (lo == "")
            alert("Enter Purpose of Listing");
        if (lo == "32")
            alert("Enter Purpose of Listing other than FRESH");
        if (lo == "48")
            alert("Enter Purpose of Listing other than NOT REACHED");
        return false;
    }
    var curdate = new Date(curr_date); // get system date
    curdate_utc = Date.UTC(curdate.getFullYear(), curdate.getMonth(), curdate.getDate(), 0, 0, 0, 0);
    hdate_utc = Date.UTC(thdt1[2], thdt1[1] - 1, thdt1[0], 0, 0, 0, 0);  // month is 0 to 11 not 1 to 12
    if (curdate_utc > hdate_utc) {
        // alert("Sorry! Date of proposal cannot be before todays date!");
        // return false;
    }
    var ytq = '0';
    var hd_co_tot = document.getElementById('hd_co_tot').value;


    // var hd_co_tot=document.getElementById('hd_co_tot').value;
    for (var itt = 1; itt <= hd_co_tot; itt++) {
        if (document.getElementById('hd_sp_b' + itt)) {

            ytq = 1;

        }
    }

    if (ytq == '0') {
        alert("Please Add atleast one Sub Heading")
    }
    else {


        var subhead_select = '';
        for (var itt = 1; itt <= hd_co_tot; itt++) {
            //  var main_cat= document.getElementById('hd_sp_a'+itt).value;
            if (document.getElementById('hd_sp_b' + itt)) {
                if (subhead_select == '')
                    subhead_select = document.getElementById('hd_sp_b' + itt).value;
                else
                    subhead_select = subhead_select + ',' + document.getElementById('hd_sp_b' + itt).value;
            }
        }
        var mf = document.getElementById('mf_select').value;
        var sh = document.getElementById('subhead_select').value;
        var bnch = document.getElementById('bench').value;

        var br = document.getElementById('brdrem').value;
        var rem = document.getElementById('rem').value;
        var conncs = document.getElementById('conncs').value;
        var ucode = document.getElementById('hd_ud').value;
        var dacode = document.getElementById('da_hidden').value;
        var sbj = document.getElementById('sbj').value;
        var dbj1 = document.getElementById('dbj1').value;
        var dbj2 = document.getElementById('dbj2').value;
        var legalaid = "";
        if (document.getElementById("legalaid").checked && mf == "L") {
            legalaid = document.getElementById("legalaid").value;
        } else {
            legalaid = "";
        }
        //var str="insert_rec_an.php?str="+str1+"&dt="+dt_new;
        //alert(str);
        ////str1=flno+"|"+thdt_new+"|"+mf+"|"+sh+"|"+bnch+"|"+lo+"|"+br+"|"+rem+"|"+conncs;


        //alert(str_new);
        //alert(str1);
        var ccstr = "";
        var tcntr = 0;
        $("input[type='checkbox'][name^='ccchk']").each(function () {
            var isChecked = document.getElementById($(this).attr('id')).checked;
            if (isChecked) {
                qte_array[tcntr] = new Array(3);
                qte_array[tcntr][0] = $(this).val();
                qte_array[tcntr][1] = '';
                qte_array[tcntr][2] = '';
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
                tcntr++;
            }
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
                flno: flno,
                thdt_new: thdt_new,
                mf: mf,
                sh: sh,
                bnch: bnch,
                lo: lo,
                br: br,
                rem: rem,
                conncs: conncs,
                ccstr: ccstr,
                ucode: ucode,
                dacode: dacode,
                ias: ccstr1,
                sbj: sbj,
                dbj1: dbj1,
                dbj2: dbj2,
                connlist: qte_array,
                subhead_select: subhead_select,
                legalaid: legalaid,
                case_receive_da: case_receive_da
            },
            success: function (msg) {
                if (msg == '') {
                    //                    alert("DONE");
                    if (s_t == 1) {
                        closeData();
                    }
                    if (hd_ord_co == 1) {
                        alert("Proposal already made if you want to change then click on save and exit otherwise exit");
                    }
                }
                else {
                    alert(msg);


                }
            },
            error: function () {
                alert("ERROR");
            }
        });
        //var parameters="flno="+flno;
        //parameters += "&thdt_new="+thdt_new;
        //parameters += "&mf="+mf;
        //parameters += "&sh="+sh;
        //parameters += "&bnch="+bnch;
        //parameters += "&lo="+lo;
        //parameters += "&br="+br;
        //parameters += "&rem="+rem;
        //parameters += "&conncs="+conncs;
        //parameters += "&ccstr="+ccstr;
        //parameters += "&ucode="+ucode;
        //parameters += "&ias="+ccstr1;
        //parameters += "&sbj="+sbj;
        //parameters += "&dbj1="+dbj1;
        //parameters += "&dbj2="+dbj2;
        //parameters += "&connlist="+qte_array;
        //
        ////document.getElementById("proc").innerHTML="<img src='saving.gif'/>";
        //http.open("POST", url, true);
        // //Send the proper header information along with the request
        //http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        //http.setRequestHeader("Content-length", parameters.length);
        //http.setRequestHeader("Connection", "close");
        // 
        //http.onreadystatechange = function() {//Handler function for call back on state change.
        //    if(http.readyState == 4) {
        //		//document.getElementById("proc").innerHTML="";
        //		  var data=http.responseText;
        //                  if(data!="")
        //		  alert(data);
        //                  else
        //                  {
        //            fsubmit();    
        //            //$("#submit").click();
        //                  }
        //                  //document.getElementById("caseval"+cn).value=str_caseval;
        //                  //document.getElementById("cr_span"+cn).innerHTML=cr_head;
        //	}
        //}
        //http.send(parameters);
        if (s_t == 1)
            close_w(3);
    }
}



var cnt_data1 = 1;
var ck_subhead = 0;
var ck_subhead_s = 0;
function getSlide(hd_ord_co) {
    var ck_ca_sb = 0;
    if (document.getElementById('hd_ssno').value != '0') {
        cnt_data1 = parseInt(document.getElementById('hd_ssno').value) + 1;
        document.getElementById('hd_ssno').value = '0';
    }
    var mf_select_nm = document.getElementById('mf_select').options[document.getElementById('mf_select').selectedIndex].innerHTML;
    jj = $('#mf_select').val();
    if (jj == "F") {
        if (document.getElementById('subhead_select').value == "" && $('select#subhead_select option').length > 1) {
            alert("PLEASE SELECT FIRST SUBCATEGORY");
            return false;
        }
        if (document.getElementById('subhead_select1').value == "" && $('select#subhead_select1 option').length > 1) {
            alert("PLEASE SELECT SECOND SUBCATEGORY");
            return false;
        }
        if (document.getElementById('subhead_select2').value == "" && $('select#subhead_select2 option').length > 1) {
            alert("PLEASE SELECT THIRD SUBCATEGORY");
            return false;
        }
        if (document.getElementById('subhead_select3').value == "" && $('select#subhead_select3 option').length > 1) {
            alert("PLEASE SELECT FOURTH SUBCATEGORY");
            return false;
        }
        if (document.getElementById('subhead_select3').value != "") {
            var subhead_select_nm = document.getElementById('subhead_select').options[document.getElementById('subhead_select').selectedIndex].innerHTML;
            subhead_select_nm += " : " + document.getElementById('subhead_select1').options[document.getElementById('subhead_select1').selectedIndex].innerHTML;
            subhead_select_nm += " : " + document.getElementById('subhead_select2').options[document.getElementById('subhead_select2').selectedIndex].innerHTML;
            subhead_select_nm += " : " + document.getElementById('subhead_select3').options[document.getElementById('subhead_select3').selectedIndex].innerHTML;
            var subhead_select = document.getElementById('subhead_select3').value;
        }
        else if (document.getElementById('subhead_select2').value != "") {
            var subhead_select_nm = document.getElementById('subhead_select').options[document.getElementById('subhead_select').selectedIndex].innerHTML;
            subhead_select_nm += " : " + document.getElementById('subhead_select1').options[document.getElementById('subhead_select1').selectedIndex].innerHTML;
            subhead_select_nm += " : " + document.getElementById('subhead_select2').options[document.getElementById('subhead_select2').selectedIndex].innerHTML;
            var subhead_select = document.getElementById('subhead_select2').value;
        }
        else if (document.getElementById('subhead_select1').value != "") {
            var subhead_select_nm = document.getElementById('subhead_select').options[document.getElementById('subhead_select').selectedIndex].innerHTML;
            subhead_select_nm += " : " + document.getElementById('subhead_select1').options[document.getElementById('subhead_select1').selectedIndex].innerHTML;
            var subhead_select = document.getElementById('subhead_select1').value;
        }
        else {
            var subhead_select_nm = document.getElementById('subhead_select').options[document.getElementById('subhead_select').selectedIndex].innerHTML;
            var subhead_select = document.getElementById('subhead_select').value;
        }
    }
    else {
        var subhead_select_nm = document.getElementById('subhead_select').options[document.getElementById('subhead_select').selectedIndex].innerHTML;
        var subhead_select = document.getElementById('subhead_select').value;
        //        alert(subhead_select);
        if (document.getElementById('subhead_select').value == "" && $('select#subhead_select option').length > 1) {
            alert("PLEASE SELECT SUBCATEGORY");
            return false;
        }
    }
    var mf_select = document.getElementById('mf_select').value;
    var hd_co_tot = document.getElementById('hd_co_tot').value;
    for (var i = 1; i <= hd_co_tot; i++) {
        if (document.getElementById('hd_sp_a' + i)) {
            if (document.getElementById('hd_sp_b' + i).value == '804' || document.getElementById('hd_sp_b' + i).value == '805' || document.getElementById('hd_sp_b' + i).value == '806' || document.getElementById('hd_sp_b' + i).value == '850' || document.getElementById('hd_sp_b' + i).value == '801' || document.getElementById('hd_sp_b' + i).value == '851' || document.getElementById('hd_sp_b' + i).value == '849') {
                if (document.getElementById('hd_sp_b' + i).value == '804' || document.getElementById('hd_sp_b' + i).value == '805' || document.getElementById('hd_sp_b' + i).value == '806' || document.getElementById('hd_sp_b' + i).value.trim() == '850' || document.getElementById('hd_sp_b' + i).value.trim() == '801' || document.getElementById('hd_sp_b' + i).value.trim() == '849') {
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
        // cnt_data1++;
    }
    else {
        var row0 = document.createElement("tr");
        row0.setAttribute('id', 'tr_uo' + cnt_data1);
        var column0 = document.createElement("td");
        var column1 = document.createElement("td");
        var hd_chk_add = document.createElement('input');
        column1.setAttribute('align', 'left');
        column0.setAttribute('width', '20px');
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

        //                 var hd_3=document.createElement('input');
        //               hd_3.setAttribute('type', 'hidden');
        //                hd_3.setAttribute('id', 'hd_sp_c'+cnt_data1);
        column0.appendChild(hd_chk_add);
        column0.appendChild(hd_1);
        column0.appendChild(hd_2);
        //  column0.appendChild(hd_3);
        column1.appendChild(sp);
        row0.appendChild(column0);
        row0.appendChild(column1);

        var tb_res = document.getElementById('tb_new');
        tb_res.appendChild(row0);
        document.getElementById('hd_chk_add' + cnt_data1).checked = true;
        document.getElementById('sp_c' + cnt_data1).innerHTML = subhead_select_nm;
        document.getElementById('hd_sp_a' + cnt_data1).value = mf_select;
        document.getElementById('hd_sp_b' + cnt_data1).value = subhead_select;
        // if(document.getElementById("subhead_select").value=="850")
        if (subhead_select.trim() == '804' || subhead_select.trim() == '805' || subhead_select.trim() == '806')
            $("#editopen").val("0");
        else
            $("#editopen").val("1");
        if (subhead_select.trim() == '804' || subhead_select.trim() == '805' || subhead_select.trim() == '806' || subhead_select.trim() == '850' || subhead_select.trim() == '801' || subhead_select.trim() == '851' || subhead_select.trim() == '849') {
            var ed = document.getElementById("editopen").value;
            var lo = document.getElementById("listorder").value;
            /////DISABLE EDIT DATE
            //if(subhead_select.trim()=='804' || subhead_select.trim()=='805' || subhead_select.trim()=='806' || subhead_select.trim()=='850' || subhead_select.trim()=='801' || lo=="1" || lo=="4" || lo=="5" || lo=="17" || lo=="47" || lo=="9" || lo=="18" || lo=="50" || ed==0 || subhead_select.trim()=='849')
            //|| lo == "49"
            if (lo == "47")
                $('#thdate').prop('disabled', false);
            else {
                d11 = (document.getElementById('thdate_nm').value).split("-");
                d22 = (document.getElementById('thdate_h').value).split("-");
                d1 = new Date(d11[2], (d11[1] - 1), d11[0]);
                d2 = new Date(d22[2], (d22[1] - 1), d22[0]);
                //     d1=new Date(document.getElementById('thdate_nm').value);
                //d2=new Date(document.getElementById('thdate_h').value);
                // document.getElementById('thdate').value=document.getElementById('thdate_h').value;
                if (hd_ord_co != 1) {
                    if ((lo == "16" || lo == "2") && $("#mf_select").val() == "M" && d1.getTime() > d2.getTime())
                        document.getElementById('thdate').value = document.getElementById('thdate_nm').value;
                    else
                        document.getElementById('thdate').value = document.getElementById('thdate_h').value;
                    $('#thdate').prop('disabled', true);
                }
            }
            /////DISABLE EDIT DATE
            if ($("#bench").val() == "S") {
                $("#sbj").val("514");
            }
            if ($("#bench").val() == "D") {
                $("#dbj1").val("514");
                $("#dbj2").val("999");
            }
            if (subhead_select.trim() == '804' || subhead_select.trim() == '805' || subhead_select.trim() == '806' || subhead_select.trim() == '850' || subhead_select.trim() == '801' || subhead_select.trim() == '849') {
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
        //  document.getElementById('hd_sp_c'+cnt_data1).value=subhead_select;
        // document.getElementById('mf_select').disabled=true
        document.getElementById('hd_co_tot').value = cnt_data1;
        cnt_data1++;
    }
    //     save_rec_prop(0,hd_ord_co);
}

function getDone_upd_cat(str) {
    var str1 = str.split('hd_chk_add');
    var tb = 0;
    var hd_co_tot = document.getElementById('hd_co_tot').value;
    //alert(document.getElementById('hd_sp_b'+str1[1]).value);
    if (document.getElementById('hd_sp_b' + str1[1]).value == '804' || document.getElementById('hd_sp_b' + str1[1]).value == '805' || document.getElementById('hd_sp_b' + str1[1]).value == '806')
        $("#editopen").val("1");

    if (document.getElementById('hd_sp_b' + str1[1]).value == '804' || document.getElementById('hd_sp_b' + str1[1]).value == '805' || document.getElementById('hd_sp_b' + str1[1]).value == '806' || document.getElementById('hd_sp_b' + str1[1]).value == '850' || document.getElementById('hd_sp_b' + str1[1]).value == '851' || document.getElementById('hd_sp_b' + str1[1]).value == '801' || document.getElementById('hd_sp_b' + str1[1]).value == '849') {
        var ed = document.getElementById("editopen").value;
        var lo = document.getElementById("listorder").value;
        if (document.getElementById('hd_sp_b' + str1[1]).value == '804' || document.getElementById('hd_sp_b' + str1[1]).value == '805' || document.getElementById('hd_sp_b' + str1[1]).value == '806') {
            ed = 1;
        }
        /////DISABLE EDIT DATE
        //if((document.getElementById('hd_sp_b'+str1[1]).value=='804' || document.getElementById('hd_sp_b'+str1[1]).value=='805' || document.getElementById('hd_sp_b'+str1[1]).value=='806' || document.getElementById('hd_sp_b'+str1[1]).value=='850' || document.getElementById('hd_sp_b'+str1[1]).value=='801' || document.getElementById('hd_sp_b'+str1[1]).value=='849') && !(lo=="1" || lo=="4" || lo=="5" || lo=="17" || lo=="47" || lo=="9" || lo=="18" || lo=="50" || ed==0))
        //|| lo == "49"
        if (lo == "47") {
            document.getElementById('thdate').value = document.getElementById('thdate_h').value;
            $('#thdate').prop('disabled', true);
        }
        else
            $('#thdate').prop('disabled', false);
        /////DISABLE EDIT DATE
        if (document.getElementById('hd_sp_b' + str1[1]).value == '804' || document.getElementById('hd_sp_b' + str1[1]).value == '805' || document.getElementById('hd_sp_b' + str1[1]).value == '806' || document.getElementById('hd_sp_b' + str1[1]).value == '850' || document.getElementById('hd_sp_b' + str1[1]).value == '801' || document.getElementById('hd_sp_b' + str1[1]).value == '849')
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
        //  var main_cat= document.getElementById('hd_sp_a'+itt).value;
        if (document.getElementById('hd_sp_b' + itt)) {
            tb++;
        }
    }
    //    var hd_sp_a_rem='';
    //    var hd_sp_b_rem='';
    //     var hd_sp_c_rem='';
    //    if( document.getElementById('hd_sp_a_rem').value=='')
    //    document.getElementById('hd_sp_a_rem').value=document.getElementById('hd_sp_a'+str1[1]).value;
    //else
    //    {
    //     document.getElementById('hd_sp_a_rem').value=document.getElementById('hd_sp_a_rem').value+'^'+
    //      document.getElementById('hd_sp_a'+str1[1]).value;
    //    }
    // //document.getElementById('hd_sp_a_rem').value=hd_sp_a_rem;
    //   if(document.getElementById('hd_sp_b_rem').value=='')
    //  document.getElementById('hd_sp_b_rem').value=document.getElementById('hd_sp_b'+str1[1]).value;
    //else
    //      document.getElementById('hd_sp_b_rem').value=document.getElementById('hd_sp_b_rem').value+'^'+document.getElementById('hd_sp_b'+str1[1]).value;
    // // document.getElementById('hd_sp_b_rem').value=hd_sp_b_rem;
    //  if(document.getElementById('hd_sp_c_rem').value=='')
    //   document.getElementById('hd_sp_c_rem').value=document.getElementById('hd_sp_c'+str1[1]).value;
    //else
    //  document.getElementById('hd_sp_c_rem').value=document.getElementById('hd_sp_c_rem').value+'^'+document.getElementById('hd_sp_c'+str1[1]).value;
    //document.getElementById('hd_sp_c_rem').value=hd_sp_c_rem;
    // var str1=str.split('hd_chk_add') ;
    $("#tr_uo" + str1[1]).remove();
    //  if(tb==1)
    // document.getElementById('mf_select').disabled=false;
}

function get_def_rec() {
    // alert("zxzx");

    var t_h_cno = $('#t_h_cno').val();
    var t_h_cyt = $('#t_h_cyt').val();

    if (t_h_cno != '' && (t_h_cyt == '' || t_h_cyt.length != 4)) {
        alert("Please enter year");
        $('#t_h_cyt').focus();
    }
    else if ((t_h_cyt != '' && t_h_cyt.length == 4) && t_h_cno == '') {
        alert("Please enter Diary No.");
        $('#t_h_cno').focus();
    }
    else {
        var xmlhttp;
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        }
        else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        document.getElementById('sData').innerHTML = '<table widht="100%" align="center"><tr><td><img src="../images/load.gif"/></td></tr></table>';

        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {

                document.getElementById('sData').innerHTML = xmlhttp.responseText;

            }
        }


        // xmlhttp.open("GET","getReport51-II.php?seljudgename="+seljudgename+"&frm="+frm+"&toDate="+toDate,true);
        xmlhttp.open("GET", "get_obj_cl_da.php?d_no=" + t_h_cno + "&d_yr=" + t_h_cyt, true);
        xmlhttp.send(null);
    }
}



