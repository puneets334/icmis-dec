
// var f_noo='';
function get_record(str)
{

    var f_noo = '';
    var str1 = str.split('_');
    document.getElementById('hd_hd_spl').value = str1[1];
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
    if (document.getElementById('delete' + str1[0] + '^' + str1[1]).checked == false)
    {
        alert("Please Check Default To Remove");
    }
    else if (document.getElementById('delete' + str1[0] + '^' + str1[1]).checked == true)
    {
        // alert(f_noo);
//$('#btnRemove_' + str1[0] + '^' + str1[1]).attr('disabled',true);
        document.getElementById('btnRemove_' + str1[0] + '^' + str1[1]).disabled = true;
        var fil_no = document.getElementById('hd_fil_no' + str_res).value;
//alert(f_noo);
        // var hd_fc=document.getElementById('hd_fc'+str1[1]).value;
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

        //   alert(f_noo);
//             if(f_noo<='0')
//                   {
//                   document.getElementById('dv_sh_hd').style.display='block';
//                   document.getElementById('dv_fixedFor').style.marginTop='250px';
//                   document.getElementById('hd_hd_sh_fno').value=fil_no;
//                  document.getElementById('dv_fixedFor').style.display='block';
//                 //  document.getElementById('dv_fixedFor').style.marginLeft='50px';  
//                  // alert(document.getElementById('dv_fixedFor'));
//               }
//               else
//                   {
        var id_id = document.getElementById('hdId_' + str1[0] + str1[1]).value;

        var xmlhttp;
        if (window.XMLHttpRequest)
        {// code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        }
        else
        {// code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        //  document.getElementById('div_result').innerHTML = '<table widht="100%" align="center"><tr><td><img src="images/loading_new.gif"/></td></tr></table>';

        //  alert(i);
        xmlhttp.onreadystatechange = function()
        {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
            {
                // var txtAdeshika="txtAdeshika"+rowCount;
                document.getElementById('sData').innerHTML = xmlhttp.responseText;
                var hd_id_id = document.getElementById('hd_id_id').value;
                if (hd_id_id == 0)
                {
                    alert("Default Not Removed");
                }
                else if (hd_id_id == 1)
                {
                    alert("Default Removed Sucessfully");
                    if(f_noo<='0')
                    {
                        $('#RemoveAll_' + str1[0]).attr('disabled',true);
                        $('#btnListing_' + str1[0]).attr('disabled',true);
//                        get_fixed_for_display(fil_no);
                        //get_report(fil_no);

                    }
                }
//                       $("#td_obj_name"+str1[1]).hide(600);
//                       $("#td_ck_n"+str1[1]).hide(600);
//                         $("#td_Remove"+str1[1]).hide(600);
//alert(str1[1]);
                document.getElementById('td_obj_name' + str1[0] + str1[1]).style.color = 'green';
                document.getElementById('delete' + str1[0] + '^' + str1[1]).checked = false;
                document.getElementById('delete' + str1[0] + '^' + str1[1]).disabled = true;
                document.getElementById('btnRemove_' + str1[0] + '^' + str1[1]).style.display = 'none';
                document.getElementById('td_Remove' + str1[0] + str1[1]).innerHTML = "<span style='color:green'>Removed</span>";
            }
        }


        // xmlhttp.open("GET","getReport51-II.php?seljudgename="+seljudgename+"&frm="+frm+"&toDate="+toDate,true);
        xmlhttp.open("GET", "get_result_1.php?id_id=" + id_id + "&f_noo=" + f_noo + "&fil_no=" + fil_no, true);
        xmlhttp.send(null);
//            }
    }
}

function cn_data()
{
    document.getElementById('dv_sh_hd').style.display = 'none';
    document.getElementById('dv_fixedFor').style.display = 'none';
    document.getElementById('m_fixed').value = '';
}


function cn_data_x()
{
    document.getElementById('dv_sh_hd').style.display = 'none';
    document.getElementById('dv_fixedFor_P').style.display = 'none';
}
function upd_fil_for()
{
    var fil_no = document.getElementById('hd_hd_sh_fno').value;
    // alert(fil_no);
    var m_fixed = document.getElementById('m_fixed').value;
    if (m_fixed == '')
        alert("Plese Select Fixed For");
    else
    {
        var str1 = document.getElementById('hd_hd_spl').value;
        // alert(str1);
        str1 = str1.split('^');
        // alert(str1[1]);
        var id_id = document.getElementById('hdId_' + str1[0] + str1[1]).value;

        var xmlhttp;
        if (window.XMLHttpRequest)
        {// code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        }
        else
        {// code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        //  document.getElementById('div_result').innerHTML = '<table widht="100%" align="center"><tr><td><img src="images/loading_new.gif"/></td></tr></table>';

        //  alert(i);
        xmlhttp.onreadystatechange = function()
        {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
            {
                // var txtAdeshika="txtAdeshika"+rowCount;
                document.getElementById('sData').innerHTML = xmlhttp.responseText;
                var hd_id_id = document.getElementById('hd_id_id').value;
                if (hd_id_id == 0)
                {
                    alert("Default Not Removed");
                }
                else if (hd_id_id == 1)
                    alert("Default Removed Sucessfully");
//                       $("#td_obj_name"+str1[1]).hide(600);
//                       $("#td_ck_n"+str1[1]).hide(600);
//                         $("#td_Remove"+str1[1]).hide(600);
//alert(str1[1]);
                document.getElementById('btnListing_' + str1[0]).disabled = true;
                document.getElementById('RemoveAll_' + str1[0]).disabled = true;
                document.getElementById('td_obj_name' + str1[0] + str1[1]).style.color = 'green';
                document.getElementById('delete' + str1[0] + '^' + str1[1]).checked = false;
                document.getElementById('delete' + str1[0] + '^' + str1[1]).disabled = true;
                document.getElementById('btnRemove_' + str1[0] + '^' + str1[1]).style.display = 'none';
                document.getElementById('td_Remove' + str1[0] + str1[1]).innerHTML = "<span style='color:green'>Removed</span>";
                document.getElementById('dv_sh_hd').style.display = 'none';
                document.getElementById('dv_fixedFor').style.display = 'none';
                document.getElementById('m_fixed').value = '';
                document.getElementById('sp_mnb_p').style.width = 'auto';
                document.getElementById('sp_mnb_p').style.height = ' 500px';
                document.getElementById('sp_mnb_p').style.overflow = 'scroll';
                //  margin-left: 50px;margin-right: 50px;margin-bottom: 25px;margin-top: 1px;
                document.getElementById('sp_mnb_p').style.marginLeft = '50px';
                document.getElementById('sp_mnb_p').style.marginRight = '50px';
                document.getElementById('sp_mnb_p').style.marginBottom = '25px';
                document.getElementById('sp_mnb_p').style.marginTop = '1px';
                call_listing(fil_no);
            }
        }


        // xmlhttp.open("GET","getReport51-II.php?seljudgename="+seljudgename+"&frm="+frm+"&toDate="+toDate,true);
        xmlhttp.open("GET", "get_result_1.php?id_id=" + id_id + "&f_noo=0" + "&fil_no=" + fil_no + "&m_fixed=" + m_fixed, true);
        xmlhttp.send(null);
    }

}

function call_listing(filno)
{
    //alert(filno)
    var xmlhttp;
    if (window.XMLHttpRequest)
    {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    }
    else
    {// code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    //document.getElementById('div_result').innerHTML = '<table widht="100%" align="center"><tr><td>Loading..</td></tr></table>';

    xmlhttp.onreadystatechange = function()
    {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
        {
            // var txtAdeshika="txtAdeshika"+rowCount;
            //document.getElementById('div_result').innerHTML=xmlhttp.responseText;
            call_prop_s(filno);
        }
    }
    // xmlhttp.open("GET","getReport51-II.php?seljudgename="+seljudgename+"&frm="+frm+"&toDate="+toDate,true);
    xmlhttp.open("GET", "call_listing.php?fil_no=" + filno + "&ud=" + document.getElementById('hd_ud').value + "&objrem=Y" + "&ip=" + $("#hd_ipadd").val() + "&mac=" + $("#hd_macadd").val(), true);
    xmlhttp.send(null);
}

function call_prop_s(filno, check_ia_not)
{
    var xmlhttp;
    if (window.XMLHttpRequest)
    {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    }
    else
    {// code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    //document.getElementById('div_result').innerHTML = '<table widht="100%" align="center"><tr><td>Loading..</td></tr></table>';

    xmlhttp.onreadystatechange = function()
    {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
        {
            // var txtAdeshika="txtAdeshika"+rowCount;
            document.getElementById('dv_fixedFor_P').style.marginTop = '50px';
            document.getElementById('dv_sh_hd').style.display = 'block';
            document.getElementById('dv_fixedFor_P').style.display = 'block';
            document.getElementById('sp_mnb_p').innerHTML = xmlhttp.responseText;
            document.getElementById('tb_clr').style.backgroundColor = 'white';
            if (document.getElementById('tb_clr_n'))
                document.getElementById('tb_clr_n').style.backgroundColor = 'white';
            if (document.getElementById('del_prop'))
                document.getElementById('del_prop').style.display = 'none';
        }
    }
    // xmlhttp.open("GET","getReport51-II.php?seljudgename="+seljudgename+"&frm="+frm+"&toDate="+toDate,true);
    xmlhttp.open("GET", "get_proposal.php?fno=" + filno + "&check_ia_not=" + check_ia_not, true);
    xmlhttp.send(null);
}

function save_proposal()
{
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
    if (window.XMLHttpRequest)
    {
        xmlhttp = new XMLHttpRequest();
    }
    else
    {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function()
    {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
        {
            document.getElementById('sp_mnb_p').innerHTML = xmlhttp.responseText;
            document.getElementById('sp_close').style.display = 'block';

            document.getElementById('sp_mnb_p').style.width = 'auto';
            document.getElementById('sp_mnb_p').style.height = 'auto';
            document.getElementById('sp_mnb_p').style.overflow = 'hidden';

            //  document.getElementById('sp_close').style.display='block';
//            if( document.getElementById('dv_fixedFor_P'))
//                 document.getElementById('dv_fixedFor_P').style.display='none';
        }
    }

    var bnb = document.getElementById('before_nbefore').value;
    var jud1 = document.getElementById('bef_nbef_j1').value;
    var jud2 = document.getElementById('bef_nbef_j2').value;
    var val_reason = document.getElementById('reason_entry').value;
    if (bnb != '')
    {
        if (jud1 == '' && jud2 == '')
        {
            alert('Please Select Atleast One Judge');
            document.getElementById('bef_nbef_j1').focus();
            return false;
        }
        else if (jud1 == '' && jud2 != '')
        {
            alert('First Select J1 then J2');
            document.getElementById('bef_nbef_j1').focus();
            return false;
        }
        else if (jud1 == jud2)
        {
            alert('J1 and J2 Could Not Be Equal');
            document.getElementById('bef_nbef_j1').focus();
            return false;
        }

        if (val_reason == '') {
            alert('Please Select the Reason');
            document.getElementById('reason_entry').focus();
            return false;
        }
    }

    var url = "save_proposal.php?fil_no=" + document.getElementById('fil_hd').value + "&thd=" + document.getElementById('thd').value
        + "&heading=" + document.getElementById('heading').value + "&subhead=" + document.getElementById('subhead').value + "&purList="
        + document.getElementById('purList').value + "&sinfo=" + document.getElementById('sinfo').value + "&ldir=" + document.getElementById('ldir').value
        + "&lcc=" + document.getElementById('lcc').value + "&reason=" + val_reason + "&enter=FOBJ";

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

    url = url + "&ud=" + document.getElementById('hd_ud').value + "&ip=" + $("#hd_ipadd").val() + "&mac=" + $("#hd_macadd").val();


    if (bnb == 'B')
    {
        url = url + "&lb=Y&lb1=" + jud1 + "&lb2=" + jud2;
    }
    else if (bnb == 'N')
    {
        url = url + "&nlb=Y&nlb1=" + jud1 + "&nlb2=" + jud2;
    }
    //alert(url);
    xmlhttp.open("GET", url, true);
    xmlhttp.send(null);
}

function getJud(val)
{
    if (val != '')
    {
        document.getElementById('bef_nbef_j1').disabled = false;
        document.getElementById('bef_nbef_j2').disabled = false;
        document.getElementById('reason_entry').disabled = false;
        if (val == 'B')
        {
            document.getElementById('bef_nbef_j1').style.color = 'Green';
            document.getElementById('bef_nbef_j2').style.color = 'Green';
            document.getElementById('reason_display').innerHTML = "BEFORE";
            document.getElementById('reason_display').style.color = 'Green';
            document.getElementById('reason_entry').style.color = 'Green';
            document.getElementById('reason_entry').innerHTML = "<option value='' style='color:black'>Choose</option>\n\
            <option value='JUDICIAL ORDER'>Judicial Order</option>\n\
            <option value='ADMINISTRATIVE ORDER'>Administrative Order</option>\n\
            <option value='SAME CRIME NO.'>Same Crime No.</option>\n\
            <option value='COVERED MATTERS'>Covered Matters</option>\n\
            <option value='ORDER REVIEW/MOD/COR'>Order Review/MOD/COR</option>";
        }
        else if (val == 'N')
        {
            document.getElementById('bef_nbef_j1').style.color = 'Red';
            document.getElementById('bef_nbef_j2').style.color = 'Red';
            document.getElementById('reason_display').innerHTML = "NOT BEFORE";
            document.getElementById('reason_display').style.color = 'Red';
            document.getElementById('reason_entry').style.color = 'Red';
            document.getElementById('reason_entry').innerHTML = "<option value='' style='color:black'>Choose</option>\n\
            <option value='JUDICIAL ORDER'>Judicial Order</option>\n\
            <option value='ADMINISTRATIVE ORDER'>Administrative Order</option>\n\
            <option value='RELATION(FAMILY OR BUSINESS)'>Relation(Family or Business)</option>\n\
            <option value='ORDER CHALLENGED'>Order Challenged</option>";
        }
    }
    else if (val == '')
    {
        document.getElementById('reason_display').innerHTML = "NONE";
        document.getElementById('reason_display').style.color = 'Black';
        document.getElementById('reason_entry').disabled = true;
        document.getElementById('reason_entry').innerHTML = "<option value=''>Choose</option>";
        document.getElementById('reason_entry').value = '';
        document.getElementById('reason_entry').style.color = 'Black';
        document.getElementById('bef_nbef_j1').disabled = true;
        document.getElementById('bef_nbef_j1').value = '';
        document.getElementById('bef_nbef_j1').style.color = 'Black';
        document.getElementById('bef_nbef_j2').disabled = true
        document.getElementById('bef_nbef_j2').value = '';
        document.getElementById('bef_nbef_j2').style.color = 'Black';
    }
    var xmlhttp;
    if (window.XMLHttpRequest)
    {
        xmlhttp = new XMLHttpRequest();
    }
    else
    {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function()
    {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
        {
            document.getElementById('bef_nbef_j1').innerHTML = xmlhttp.responseText;
            document.getElementById('bef_nbef_j2').innerHTML = xmlhttp.responseText;
        }
    }
    var url = "get_judname.php?variable=2&val=" + val;

    xmlhttp.open("GET", url, true);
    if (val != '')
        xmlhttp.send(null);
}


function closeData()
{
    document.getElementById('dv_fixedFor_P').style.display = "none";
    document.getElementById('dv_sh_hd').style.display = "none";
    document.getElementById('sp_close').style.display = 'none';
}
function closeData1()
{
    document.getElementById('dv_fixedFor_P_x').style.display = "none";
    document.getElementById('dv_sh_hd').style.display = "none";
    document.getElementById('sp_close').style.display = 'none';
}

function slt_rj1_ct(str)
{
    var ck_tr_n = '0';
    var ck_tr_n_kl = '0';
    var str1 = str.split('_');
    var hd_tot_no = document.getElementById('hd_tot_no' + str1[1]).value;
    for (var i_p = 0; i_p < hd_tot_no; i_p++)
    {
        if (document.getElementById('delete' + str1[1] + '^' + i_p).checked == true || document.getElementById('delete' + str1[1] + '^' + i_p).disabled == true)
        {
            ck_tr_n = 1;
            ck_tr_n_kl++;
//                      if(spo_obj_name_tot=='')
//                    spo_obj_name_tot=document.getElementById('hdId_'+str1[1]+i_p).value+'!'+ document.getElementById('spo_obj_name'+str1[1]+i_p).innerHTML;
//                else
//                     spo_obj_name_tot=spo_obj_name_tot+'!!'+document.getElementById('hdId_'+str1[1]+i_p).value+'!'+ document.getElementById('spo_obj_name'+str1[1]+i_p).innerHTML; 
        }
    }
    if (ck_tr_n == '0')
    {
        alert("Please Check Default having IA for Listing");
    }
    else if ((hd_tot_no) != ck_tr_n_kl)
    {
        alert("Please Select All Default for Listing");
    }
    else
    {

//        document.getElementById('dv_sh_hd').style.display = 'block';
//        //document.getElementById('dv_fixedFor_P_x').style.marginTop='50px';
//        // document.getElementById('hd_hd_sh_fno').value=fil_no;
//        //  document.getElementById('hd_hd_category').value=hd_category;
//        document.getElementById('dv_fixedFor_P_x').style.display = 'block';
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
        $('#sp_close').css('display','block');
        var xmlhttp;
        if (window.XMLHttpRequest)
        {// code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        }
        else
        {// code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }



        xmlhttp.onreadystatechange = function()
        {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
            {

                document.getElementById('ggg').innerHTML = xmlhttp.responseText;


            }
        }



        xmlhttp.open("GET", "get_result_ct_rj.php?str=" + str, true);
        xmlhttp.send(null);
    }
}




function get_Listing(str, ck_c_r)
{
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
//    var hd_category = document.getElementById('hd_category' + str_res).value;
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
    for (var i_p = 0; i_p < hd_tot_no; i_p++)
    {
        if (document.getElementById('delete' + str1[1] + '^' + i_p).checked == true)
        {
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

//        alert(spo_obj_name_tot);            

    var xmlhttp;
    if (window.XMLHttpRequest)
    {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    }
    else
    {// code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }



    xmlhttp.onreadystatechange = function()
    {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
        {

            document.getElementById('sp_mnb_p_x_dum').innerHTML = xmlhttp.responseText;
//            alert(xmlhttp.responseText);
            var hd_id_id = document.getElementById('hd_hd_id_id').value;
            if (hd_id_id == 0)
            {
                alert("No IA Present");
            }
            else if (hd_id_id == 1)
            {
//                document.getElementById('dv_sh_hd').style.display = 'block';
//                //document.getElementById('dv_fixedFor_P_x').style.marginTop='50px';
                document.getElementById('hd_hd_sh_fno').value = fil_no;
//                document.getElementById('hd_hd_category').value = hd_category;
//                document.getElementById('dv_fixedFor_P_x').style.display = 'block';
                //  document.getElementById('sp_iaNumber').style.display='block';
            }
//                      

        }
    }


    // xmlhttp.open("GET","getReport51-II.php?seljudgename="+seljudgename+"&frm="+frm+"&toDate="+toDate,true);
    xmlhttp.open("GET", "get_result_Ia.php?fil_no=" + fil_no + "&spo_obj_name_tot=" + spo_obj_name_tot + "&str=" + str + "&ck_c_r=" + ck_c_r, true);
    xmlhttp.send(null);
    //   }
    //  } 

}

function upd_fil_for_x()
{
    var cn_data = 0;
    var snoq = 0;
    var chk_rd_rj = 0;
    var check_ia_not = 0;
    // var cnt_ia_no=0;
    var fil_no = document.getElementById('hd_hd_sh_fno').value;
//    var hd_category = document.getElementById('hd_hd_category').value;
    //    var m_fixed=document.getElementById('m_fixed_x').value;
    // alert(fil_no);
    var m_fixed = document.getElementById('m_fixed_x').value;
    //  alert(m_fixed);
    //  var hd_hd_ssno=document.getElementById('hd_hd_ssno').value;
    var hd_snoq = document.getElementById('hd_snoq').value;

    //  var hd_snoq=document.getElementById('hd_snoq').value;

//   if(document.getElementById('rd_rj').checked==true)
//       {
////  if(hd_category!='139')
////      {
////  for(var ty=1;ty<hd_hd_ssno;ty++)
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
////       }
//      // else
//         //  {
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
//          // }
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
    if (document.getElementById('rd_rj').checked == true || document.getElementById('rd_C').checked == true || document.getElementById('rd_cj').checked == true)
    {
        chk_rd_rj = 1;
    }

//           if(cn_data==0 || m_fixed=='' || snoq==0 || chk_rd_rj==0)
    if (m_fixed == '' || chk_rd_rj == 0)
    {
        // if(cn_data==0)
        //  alert("Please Select IA for Defaults");
        if (chk_rd_rj == 0)
            alert("Please Select Court Or RJ-I");
        else if (m_fixed == '')
            alert("Plese Select Fixed For");
        //   if(snoq==0)
        //  alert("Please Select Default having IA");

    }
    else
    {
        $('#btnSave_x').attr('disabled',true);
        var hd_snoq = document.getElementById('hd_snoq').value;
        for (var hdq = 1; hdq < hd_snoq; hdq++)
        {
            //   alert(hdq);
            // cnt_ia_no++;
            //   var sta_y='';
            //  if(document.getElementById('chk_def'+hdq).checked==true)
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

            var hd_subSno = document.getElementById('hd_subSno' + hdq).value;

            for (var ty = 0; ty < hd_subSno; ty++)
            {
                if (document.getElementById('chk_Ia' + hdq + '^' + ty).checked == true)
                {
                    if (sp_iaNY == '')
                        sp_iaNY = document.getElementById('spIANo_' + hdq + '^' + ty).innerHTML.trim();
                    else
                        sp_iaNY = sp_iaNY + ',' + document.getElementById('spIANo_' + hdq + '^' + ty).innerHTML.trim();
                    check_ia_not = 1;
                }
            }
            //     alert(sp_iaNY);
            var xmlhttp;
            if (window.XMLHttpRequest)
            {// code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();
            }
            else
            {// code for IE6, IE5
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }


            if (hdq == (hd_snoq - 1))
            {
                xmlhttp.onreadystatechange = function()
                {
                    if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
                    {

                        document.getElementById('sData1').innerHTML = xmlhttp.responseText;
//                        alert(xmlhttp.responseText);
                        var rd_RJC = '';
                        if (document.getElementById('hd_send_ia').value == '1')
                        {
                            alert("Default Moved");
                            document.getElementById('dv_fixedFor_P_x').style.display = 'none';
                            if (document.getElementById('rd_rj').checked == true)
                            {
                                rd_RJC = 'Y';
                            }
                            else if (document.getElementById('rd_C').checked == true)
                            {
                                rd_RJC = 'N';
                            }
                            else if (document.getElementById('rd_cj').checked == true)
                            {
                                rd_RJC = 'Z';
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

            if (document.getElementById('rd_rj').checked == true)
            {
                xmlhttp.open("GET", "send_Ia_de.php?fil_no=" + fil_no + "&sp_iaNY=" + sp_iaNY + "&hd_sno=" + hd_sno +
                    "&m_fixed=" + m_fixed + "&hd_snoq=" + hd_snoq + "&hdq=" + hdq + "&hd_status=" + hd_status , false);
            }
            else if (document.getElementById('rd_C').checked == true)
            {
                xmlhttp.open("GET", "send_Ia_de_court.php?fil_no=" + fil_no + "&sp_iaNY=" + sp_iaNY + "&hd_sno=" + hd_sno +
                    "&m_fixed=" + m_fixed + "&hd_snoq=" + hd_snoq + "&hdq=" + hdq + "&hd_status=" + hd_status , false);
            }
            else if (document.getElementById('rd_cj').checked == true)
            {
                xmlhttp.open("GET", "send_Ia_de_chamber_jug.php?fil_no=" + fil_no + "&sp_iaNY=" + sp_iaNY + "&hd_sno=" + hd_sno +
                    "&m_fixed=" + m_fixed + "&hd_snoq=" + hd_snoq + "&hdq=" + hdq + "&hd_status=" + hd_status , false);
            }

            xmlhttp.send(null);

            var hd_str = document.getElementById('hd_str').value;

            var str1 = hd_str.split('_');
            document.getElementById('hd_hd_spl').value = str1[1];
            var spo_obj_name_tot = '';
            var hd_tot_no = document.getElementById('hd_tot_no' + str1[1]).value;
            document.getElementById('btnListing_' + str1[1]).disabled = true;

            document.getElementById('RemoveAll_' + str1[1]).disabled = true;
            for (var i_pyy = 0; i_pyy < hd_tot_no; i_pyy++)
            {
                if (document.getElementById('delete' + str1[1] + '^' + i_pyy).checked == true)
                {
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
function call_listing1(filno, str, check_ia_not)
{
    //alert(filno)
    var xmlhttp;
    if (window.XMLHttpRequest)
    {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    }
    else
    {// code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    //document.getElementById('div_result').innerHTML = '<table widht="100%" align="center"><tr><td>Loading..</td></tr></table>';

    xmlhttp.onreadystatechange = function()
    {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
        {
            // var txtAdeshika="txtAdeshika"+rowCount;
            //document.getElementById('div_result').innerHTML=xmlhttp.responseText;
            call_prop_s(filno, check_ia_not);
        }
    }
    // xmlhttp.open("GET","getReport51-II.php?seljudgename="+seljudgename+"&frm="+frm+"&toDate="+toDate,true);
    xmlhttp.open("GET", "call_listing.php?fil_no=" + filno + "&ud=" + document.getElementById('hd_ud').value + "&list=" + str + "&check_ia_not=" + check_ia_not + "&ip=" + $("#hd_ipadd").val() + "&mac=" + $("#hd_macadd").val(), true);
    xmlhttp.send(null);
}

function onlynumbers(evt)
{
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    //alert(charCode);
    if ((charCode >= 48 && charCode <= 57) || charCode == 9 || charCode == 8) {
        return true;
    }
    return false;
}

function displayJud(val)
{
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
    if (window.XMLHttpRequest)
    {
        xmlhttp = new XMLHttpRequest();
    }
    else
    {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function()
    {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
        {
            document.getElementById('judgearea').innerHTML = xmlhttp.responseText;
        }
    }
    xmlhttp.open("GET", "getJudge_fixed.php?str=" + str + "&v=" + val + "&sh=" + document.getElementById('subhead').value, true);
    xmlhttp.send(null);
}


function del_pro()
{
    var xmlhttp;
    if (window.XMLHttpRequest)
    {
        xmlhttp = new XMLHttpRequest();
    }
    else
    {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function()
    {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
        {
            alert(xmlhttp.responseText);
            call_getDetails(document.getElementById('fil_hd').value);
        }
    }
    var url = "del_proposal.php?ud=" + document.getElementById('hd_ud').value + "&fil_no=" + document.getElementById('fil_hd').value + "&ip=" + $("#hd_ipadd").val() + "&mac=" + $("#hd_macadd").val();
    xmlhttp.open("GET", url, true);
    xmlhttp.send(null);
}

function get_def_rec() {
    var t_h_cno = $('#t_h_cno').val();
    var t_h_cyt = $('#t_h_cyt').val();

    if (t_h_cno != '' && (t_h_cyt == '' || t_h_cyt.length != 4)) {
        alert("Please enter year");
        $('#t_h_cyt').focus();
    } else if ((t_h_cyt != '' && t_h_cyt.length == 4) && t_h_cno == '') {
        alert("Please enter Diary No.");
        $('#t_h_cno').focus();
    } else {
        // Disable the button
        $('#btnSubmit').prop('disabled', true);

        var xmlhttp;
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        document.getElementById('dv_dup').innerHTML = '<table width="100%" align="center" style="text-align:center"><tr><td><img src="' + base_url + '/images/load.gif"/></td></tr></table>';                   
        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState == 4) {
                // Re-enable the button when request is done
                $('#btnSubmit').prop('disabled', false);

                if (xmlhttp.status == 200) {
                    document.getElementById('dv_dup').innerHTML = xmlhttp.responseText;
                } else {
                    document.getElementById('dv_dup').innerHTML = 'Error retrieving data.';
                }
            }
        };

        xmlhttp.open("GET", "get_obj_cl_dup?d_no=" + t_h_cno + "&d_yr=" + t_h_cyt, true);
        xmlhttp.send(null);
    }
}


function get_refil_report(){

    var t_h_cno = $('#t_h_cno').val();
    var t_h_cyt = $('#t_h_cyt').val();

    if (t_h_cno != '' && (t_h_cyt == '' || t_h_cyt.length != 4))
    {
        alert("Please enter year");
        $('#t_h_cyt').focus();
    }
    else if ((t_h_cyt != '' && t_h_cyt.length == 4) && t_h_cno == '')
    {
        alert("Please enter Diary No.");
        $('#t_h_cno').focus();
    }
    else
    {
        var xmlhttp;
        if (window.XMLHttpRequest)
        {
            xmlhttp = new XMLHttpRequest();
        }
        else
        {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        document.getElementById('dv_dup').innerHTML = '<table widht="100%" align="center"><tr><td><img src="../images/load.gif"/></td></tr></table>';

        xmlhttp.onreadystatechange = function()
        {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
            {

                document.getElementById('dv_dup').innerHTML = xmlhttp.responseText;

            }
        }


        // xmlhttp.open("GET","getReport51-II.php?seljudgename="+seljudgename+"&frm="+frm+"&toDate="+toDate,true);
        // xmlhttp.open("GET", "get_refiling_report.php?d_no=" + t_h_cno + "&d_yr=" + t_h_cyt, true);
        // xmlhttp.send(null);
    }

}

function RemoveAll(str)
{
    // alert(str);
    var ck_tr_n = '0';
    var ck_jk = '0';
    var f_noo = '';
    var str1 = str.split('_');

    var hd_tot_no = document.getElementById('hd_tot_no' + str1[1]).value;
    var str_res = str1[1];
    for (var i_p = 0; i_p < hd_tot_no; i_p++)
    {
//              if(document.getElementById('delete'+str1[1]+i_p).checked==true || document.getElementById('delete'+str1[1]+i_p).disabled==true)
        if (document.getElementById('delete' + str1[1] + '^' + i_p).checked == true)
        {
            ck_tr_n++;

        }
    }
    if (ck_tr_n == '0')
    {
        alert("Please Check atleast one Default");
    }
    else
    {
        $('#RemoveAll_' + str1[1]).attr('disabled',true);
        $('#btnListing_' + str1[1]).attr('disabled',true);
        for (var i_p = 0; i_p < hd_tot_no; i_p++)
        {

            var fil_no = document.getElementById('hd_fil_no' + str_res).value;
            if (document.getElementById('delete' + str1[1] + '^' + i_p).checked == true)
            {
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
//                  document.getElementById('dv_sh_hd').style.display='block';
//                   document.getElementById('dv_fixedFor').style.marginTop='250px';
//                   document.getElementById('hd_hd_sh_fno').value=fil_no;
//                  document.getElementById('dv_fixedFor').style.display='block';
//                 //  document.getElementById('dv_fixedFor').style.marginLeft='50px';  
//                  // alert(document.getElementById('dv_fixedFor'));
//               }
//               else
//                   {
                var id_id = document.getElementById('hdId_' + str1[1] + i_p).value;

                var xmlhttp;
                if (window.XMLHttpRequest)
                {// code for IE7+, Firefox, Chrome, Opera, Safari
                    xmlhttp = new XMLHttpRequest();
                }
                else
                {// code for IE6, IE5
                    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                }
                //  document.getElementById('div_result').innerHTML = '<table widht="100%" align="center"><tr><td><img src="images/loading_new.gif"/></td></tr></table>';

                //  alert(i);
                //  if(i_p==(ck_tr_n-1))
                document.getElementById('td_obj_name' + str1[1] + i_p).style.color = 'green';
                document.getElementById('delete' + str1[1] + '^' + i_p).checked = false;
                document.getElementById('delete' + str1[1] + '^' + i_p).disabled = true;
                document.getElementById('btnRemove_' + str1[1] + '^' + i_p).style.display = 'none';
                document.getElementById('td_Remove' + str1[1] + i_p).innerHTML = "<span style='color:green'>Removed</span>";
                if (ck_tr_n == ck_jk)
                {
                    xmlhttp.onreadystatechange = function()
                    {
                        if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
                        {
                            // var txtAdeshika="txtAdeshika"+rowCount;
                            document.getElementById('sData').innerHTML = xmlhttp.responseText;
                            var hd_id_id = document.getElementById('hd_id_id').value;
                            if (hd_id_id == 0)
                            {
                                alert("Default Not Removed");
                            }
                            else if (hd_id_id == 1)
                            {
                                alert("Default Removed Sucessfully");

                                send_sms();
//                                get_fixed_for_display(fil_no);
                                //get_report(fil_no);
                            }
//                       $("#td_obj_name"+str1[1]).hide(600);
//                       $("#td_ck_n"+str1[1]).hide(600);
//                         $("#td_Remove"+str1[1]).hide(600);
//alert(str1[1]);

                        }
                    }

                }
                // xmlhttp.open("GET","getReport51-II.php?seljudgename="+seljudgename+"&frm="+frm+"&toDate="+toDate,true);
                xmlhttp.open("GET", "get_result_1.php?id_id=" + id_id + "&f_noo=" + f_noo + "&fil_no=" + fil_no, false);
                xmlhttp.send(null);


//                   }
            }
        }
    }
}

/*###################ONLY FOR VACATION PERIOD##########################################################*/
function set_date(val)
{
    if (val == '49')
    {
        document.getElementById('hd_thd').value = document.getElementById('thd').value;
        var xmlhttp;
        if (window.XMLHttpRequest)
        {
            xmlhttp = new XMLHttpRequest();
        }
        else
        {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function()
        {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
            {
                //alert(xmlhttp.responseText);
                document.getElementById('thd').value = xmlhttp.responseText;
            }
        }
        var url = "get_date_vacation.php";
        xmlhttp.open("GET", url, true);
        xmlhttp.send(null);
    }
    else if (val != '49')
    {
        if (document.getElementById('hd_thd').value != '')
            document.getElementById('thd').value = document.getElementById('hd_thd').value;
    }
}

function get_fixed_for_display(fil_no){
    //var d_no=document.getElementById('t_h_cno').value;
    //var d_yr=document.getElementById('t_h_cyt').value;
    //var dno=d_no+d_yr;
    $.ajax({
        type: 'POST',
        url:"show_fixed_for.php",
        /*beforeSend: function (xhr) {
         $("#result1").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='../images/load.gif'></div>");
         },*/
        data:{dno:fil_no}
    })
        .done(function(msg){
            //alert(msg);
            document.getElementById('ggg').style.width = 'auto';
            document.getElementById('ggg').style.height = 'auto';
            document.getElementById('ggg').style.overflow = 'scroll';
            //  margin-left: 50px;margin-right: 50px;margin-bottom: 25px;margin-top: 1px;
            document.getElementById('ggg').style.marginLeft = '50px';
            document.getElementById('ggg').style.marginRight = '50px';
            document.getElementById('ggg').style.marginBottom = '25px';
            document.getElementById('ggg').style.marginTop = '50px';
            document.getElementById('dv_fixedFor_P').style.display = 'block';
            document.getElementById('dv_fixedFor_P').style.marginTop = '3px';
            document.getElementById('dv_sh_hd').style.display = "block";
            document.getElementById('sp_close').style.display = 'none';
            $('#ggg').html(msg);
        })
        .fail(function(){
            alert("ERROR, Please Contact Server Room");
        });
}

function save_fixedfor(fil_no){
    var fixed = $("#fixed_cl").val();
    if(fixed==''){
        alert('Please Select Fixed For');
        $("#fixed_cl").focus();
        return false;
    }
    $.ajax({
        type: 'POST',
        url:"save_fixed_for.php",
        /*beforeSend: function (xhr) {
         $("#result1").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='../images/load.gif'></div>");
         },*/
        data:{dno:fil_no,fixed:fixed}
    })
        .done(function(msg){
            //alert(msg);
            closeData();
            get_report(fil_no);
        })
        .fail(function(){
            alert("ERROR, Please Contact Server Room");
        });
}

function get_report(fil_no)
{
//   var d_no=document.getElementById('hd_fil_no').value;
    $('#hd_f_no').val(fil_no);
    $('#sp_close').css('display','none');
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
    document.getElementById('sp_close').style.display = 'none';

    $.ajax({
        url: '../scrutiny/get_lower_report.php',
        cache: false,
        async: true,
        data: {d_no: fil_no},
        beforeSend:function(){
            $('#ggg').html('<table widht="100%" align="center"><tr><td><img src="../images/load.gif"/></td></tr></table>');
        },
        type: 'POST',
        success: function(data, status) {
            $('#ggg').html(data);
        },
        error: function(xhr) {
            alert("Error: " + xhr.status + " " + xhr.statusText);
        }
    });
}

$(document).ready(function(){
    $(document).on('click','#btn_generate',function(){
        var chk_clk=0;
        $case_type=$('#hd_casetype_id').val();
        if(!$('#casetype').is(':checked')){
            alert("Please Check Case Type");
            $('#casetype').focus();
            exit();
        }
        $reg_chk=0;
        if(hd_casetype_id=='5'||hd_casetype_id=='6'||hd_casetype_id=='17'||hd_casetype_id=='24'||hd_casetype_id=='32'||hd_casetype_id=='33'||hd_casetype_id=='34'||hd_casetype_id=='35'){
            $reg_chk=1;
        }
        if((!$('#regnocount').is(':checked'))&& $reg_chk==0 ){
            alert("Please Confirm total registration no. to be generated");
            $('#regnocount').focus();
            exit();
        }
        var hd_casetype_id=$('#hd_casetype_id').val();

        if(hd_casetype_id==7 || hd_casetype_id==8)
        {
            chk_clk=1;
        }
        else
        {
            $('.cl_chk_jug_clnged').each(function(){
                if($(this).is(':checked'))
                {
                    chk_clk=1;
                }
            });
        }
        if(chk_clk==0)
        {
            alert("Atleast one judgement should be challenged before registration");
        }
        else
        {

            var confirmation = confirm("Are you sure you want to register case");

            if (confirmation == false) {
                return false;
            }
            else
            {
                $('#btn_generate').attr('disabled',true);
                var d_no= $('#hd_f_no').val();
//  alert(d_no);
//    var hd_casetype_id=$('#hd_casetype_id').val();
                var fn_val='';
                $('.cl_chk_jug_clnged').each(function(){
                    var chk_jug_clnged=$(this).attr('id');
                    var sp_jug_clnged=chk_jug_clnged.split('chk_jug_clnged');
                    var hd_lower_id=$('#hd_lower_id'+sp_jug_clnged[1]).val();
                    var ck_chd='N';
                    if(hd_casetype_id==7 || hd_casetype_id==8)
                    {
                        ck_chd='Y';
                    }
                    else
                    {
                        if($(this).is(':checked'))
                        {
                            ck_chd='Y';
                        }
                    }
                    if(fn_val=='')
                        fn_val=hd_lower_id+'!'+ck_chd;
                    else
                        fn_val=fn_val+'@'+hd_lower_id+'!'+ck_chd;
                });
                $.ajax({
                    url: '../scrutiny/register_case.php',
                    cache: false,
                    async: true,
                    data: {d_no: d_no,fn_val:fn_val,hd_casetype_id:hd_casetype_id},
                    beforeSend:function(){
                        $('#dv_load').html('<table widht="100%" align="center"><tr><td><img src="../images/load.gif"/></td></tr></table>');
                    },
                    type: 'POST',
                    success: function(data, status) {

                        $('#dv_load').html(data);
                        $('#sp_close').css('display','block');
//               call_listing();
                    },
                    error: function(xhr) {
                        alert("Error: " + xhr.status + " " + xhr.statusText);
                    }

                });
            }}
    });

    $(document).on('click','#btn_generate_s',function(){
        var d_no=document.getElementById('t_h_cno').value;
        var d_yr=document.getElementById('t_h_cyt').value;
        var hd_casetype_id=$('#hd_casetype_id').val();
        var txt_order_dt=$('#txt_order_dt').val();
        $case_type=$('#hd_casetype_id').val();
        if(!$('#casetype').is(':checked')){
            alert("Please Check Case Type");
            $('#casetype').focus();
            exit();
        }
        $reg_chk=0;
        if(hd_casetype_id=='5'||hd_casetype_id=='6'||hd_casetype_id=='17'||hd_casetype_id=='24'||hd_casetype_id=='32'||hd_casetype_id=='33'||hd_casetype_id=='34'||hd_casetype_id=='35'){
            $reg_chk=1;
        }
        if((!$('#regnocount').is(':checked'))&& $reg_chk==0 ){
            alert("Please Confirm total registration no. to be generated");
            $('#regnocount').focus();
            exit();
        }
        var confirmation = confirm("Are you sure you want to register case");

        if (confirmation == false) {
            return false;
        }
        else
        {

            $('#btn_generate_s').attr('disabled',true);
//          alert(d_no+'##'+d_yr+'##'+hd_casetype_id+'##'+txt_order_dt);
            $.ajax({
                url: '../scrutiny/register_case_supreme.php',
                cache: false,
                async: true,
                data: {d_no: d_no,d_yr:d_yr,hd_casetype_id:hd_casetype_id,txt_order_dt:txt_order_dt},
                beforeSend:function(){
                    $('#dv_load_default').html('<table widht="100%" align="center"><tr><td><img src="../images/load.gif"/></td></tr></table>');
                },
                type: 'POST',
                success: function(data, status) {

                    $('#dv_load_default').html(data);
                    $('#sp_close').css('display','block');
                    //call_listing(d_no,d_yr);

                },
                error: function(xhr) {
                    alert("Error: " + xhr.status + " " + xhr.statusText);
                }

            }); }
    });

    $(document).on('click', '#btn_sms', function () {
        var hd_fil_no = $('#hd_fil_no0').val();
        var t_h_cno = hd_fil_no.substr(0, (hd_fil_no.length) - 4);
        var t_h_cyt = hd_fil_no.substr(-4);
        //        alert(t_h_cno);
        $.ajax({
            url: '../sms/send_sms.php',
            cache: false,
            async: true,
            data: { d_no: t_h_cno, d_yr: t_h_cyt, sms_status: 'refiling' },
            beforeSend: function () {
                $('#sp_sms_status').html('<table widht="100%" align="center"><tr><td><img src="../images/load.gif"/></td></tr></table>');
            },
            type: 'POST',
            success: function (data, status) {

                $('#sp_sms_status').html(data);

            },
            error: function (xhr) {
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }

        });
    });
    $(document).on('click', '#btn_sms1', function () {
        var hd_fil_no = $('#hd_fil_no0').val();
        var t_h_cno = hd_fil_no.substr(0, (hd_fil_no.length) - 4);
        var t_h_cyt = hd_fil_no.substr(-4);
        //        alert(t_h_cno);
        $.ajax({
            url: '../judicial_defects/send_sms.php',
            cache: false,
            async: true,
            data: { d_no: t_h_cno, d_yr: t_h_cyt, sms_status: 'refiling' },
            beforeSend: function () {
                $('#sp_sms_status').html('<table widht="100%" align="center"><tr><td><img src="../images/load.gif"/></td></tr></table>');
            },
            type: 'POST',
            success: function (data, status) {

                $('#sp_sms_status').html(data);

            },
            error: function (xhr) {
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }

        });
    });
});

function send_sms() {
    var hd_fil_no = $('#hd_fil_no0').val();
    var t_h_cno = hd_fil_no.substr(0, (hd_fil_no.length) - 4);
    var t_h_cyt = hd_fil_no.substr(-4);
    //        alert(t_h_cno);
    $.ajax({
        url: '../sms/send_sms.php',
        cache: false,
        async: true,
        data: { d_no: t_h_cno, d_yr: t_h_cyt, sms_status: 'refiling' },
        beforeSend: function () {
            $('#sp_sms_status').html('<table widht="100%" align="center"><tr><td><img src="../images/load.gif"/></td></tr></table>');
        },
        type: 'POST',
        success: function (data, status) {

            $('#sp_sms_status').html(data);

        },
        error: function (xhr) {
            alert("Error: " + xhr.status + " " + xhr.statusText);
        }

    });
}

function print_data()
{
    var prtContent = document.getElementById('divprint');
    var WinPrint = window.open('','','letf=100,top=0,width=800,height=1200,toolbar=1,scrollbars=1,status=1,menubar=1');
    WinPrint.document.write(prtContent.outerHTML);
    WinPrint.document.close();
    WinPrint.focus();
    WinPrint.print();
}

$(document).on('click', '#all', function(){

    $("input[type='checkbox'][name^='delete']").each(function(){
        chk_val=$(this).val();
        if(document.getElementById("all").checked==true)
        //  $(this).attr('checked','checked');
            $(this).prop('checked',true);
        else if(document.getElementById("all").checked==false)
        //$(this).removeAttr('checked');
            $(this).prop('checked',false);
    });
});


