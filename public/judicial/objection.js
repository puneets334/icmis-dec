
var filling_no='';
var ui='';
function getDetails()
{

    com_data='';
    nm_cnt='';
    cnt_data=1;
    cntRem='';
    filling_no='';
    cn_upd_dt='';
    ck_totals='';
    ck_hd_id='';
    hd_show_dt='';
    ans='';
    ui='';
//  var hd_getReq=document.getElementById('hd_getReq').value;
    //alert(hd_getReq);
//   document.getElementById('div_show').innerHTML='';
//   var mn=document.getElementById('mn').innerHTML;
//var mn=document.getElementById('mn').value;
//    var cs_tp=document.getElementById('cs_tp').value;
//    if(cs_tp.length==2)
//        {
//            cs_tp='0'+cs_tp;
//        }
//    var txtFNo=document.getElementById('txtFNo').value;
//    var txtYear=document.getElementById('txtYear').value;
//    var total=mn+cs_tp+txtFNo+txtYear;
//    filling_no=total;
    // alert(total);
    $('#div_show').html('');
    var t_h_cno=$('#t_h_cno').val();
    var t_h_cyt=$('#t_h_cyt').val();
    var xmlhttp;
    if (window.XMLHttpRequest)
    {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
    }
    else
    {// code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    document.getElementById('div_result').innerHTML = '<table widht="100%" align="center"><tr><td><img src="../images/load.gif"/></td></tr></table>';

    xmlhttp.onreadystatechange=function()
    {
        if (xmlhttp.readyState==4 && xmlhttp.status==200)
        {
            // var txtAdeshika="txtAdeshika"+rowCount;
            document.getElementById('div_result').innerHTML=xmlhttp.responseText;
            if(document.getElementById('hdChk_num_row'))
            {
                var hdChk_num_row=document.getElementById('hdChk_num_row').value;
                if(hdChk_num_row<=0)
                    document.getElementById('fiOD').style.display='none';
            }
            if(document.getElementById('hdChk_num_row_j'))
            {
                var hdChk_num_row_j=document.getElementById('hdChk_num_row_j').value;

                if(hdChk_num_row_j<=0)
                {
                    document.getElementById('fdDR').style.display='none';
                    document.getElementById('ftAO').style.display='block';
                }
                else  if(hdChk_num_row_j>0)
                {
//                         document.getElementById('sp_amo').style.display='none';
                    if(document.getElementById('ftAO'))
                        document.getElementById('ftAO').style.display='none';
                }
            }
            if(document.getElementById('hd_bnb'))
            {
                if(document.getElementById('hd_bnb').value=='2')
                {
                    document.getElementById('ftAO').style.display='none';
                }
            }
//                     if( document.getElementById('hd_kl').value==1)
//                             document.getElementById('fd_md').style.display='none';
        }
    }
    // xmlhttp.open("GET","getReport51-II.php?seljudgename="+seljudgename+"&frm="+frm+"&toDate="+toDate,true);
    //xmlhttp.open("GET","get_data?d_no="+t_h_cno+"&d_yr="+t_h_cyt,true);
    //xmlhttp.send(null);
}


function getDetailsNew()
{

    // alert('fdsf');

    com_data='';
    nm_cnt='';
    cnt_data=1;
    cntRem='';
    filling_no='';
    cn_upd_dt='';
    ck_totals='';
    ck_hd_id='';
    hd_show_dt='';
    ans='';
    ui='';
    var t_h_cno = document.getElementById('t_h_cno').value;
    if(t_h_cno == ''){
        alert("Please enter Diary No.");
        return false;
    }    
//  var hd_getReq=document.getElementById('hd_getReq').value;
    //alert(hd_getReq);
//   document.getElementById('div_show').innerHTML='';
//   var mn=document.getElementById('mn').innerHTML;
//var mn=document.getElementById('mn').value;
//    var cs_tp=document.getElementById('cs_tp').value;
//    if(cs_tp.length==2)
//        {
//            cs_tp='0'+cs_tp;
//        }
//    var txtFNo=document.getElementById('txtFNo').value;
//    var txtYear=document.getElementById('txtYear').value;
//    var total=mn+cs_tp+txtFNo+txtYear;
//    filling_no=total;
    // alert(total);
    $('#div_show').html('');
    var t_h_cno=$('#t_h_cno').val();
    var t_h_cyt=$('#t_h_cyt').val();
    var xmlhttp;
    if (window.XMLHttpRequest)
    {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
    }
    else
    {// code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    document.getElementById('div_result').innerHTML = '<table widht="100%" align="center"><tr><td><img src="../images/load.gif"/></td></tr></table>';

    xmlhttp.onreadystatechange=function()
    {
        if (xmlhttp.readyState==4 && xmlhttp.status==200)
        {
            // var txtAdeshika="txtAdeshika"+rowCount;
            document.getElementById('div_result').innerHTML=xmlhttp.responseText;
            if(document.getElementById('hdChk_num_row'))
            {
                var hdChk_num_row=document.getElementById('hdChk_num_row').value;
                if(hdChk_num_row<=0)
                    document.getElementById('fiOD').style.display='none';
            }
            if(document.getElementById('hdChk_num_row_j'))
            {
                var hdChk_num_row_j=document.getElementById('hdChk_num_row_j').value;

                if(hdChk_num_row_j<=0)
                {
                    document.getElementById('fdDR').style.display='none';
                    document.getElementById('ftAO').style.display='block';
                }
                else  if(hdChk_num_row_j>0)
                {
//                         document.getElementById('sp_amo').style.display='none';
                    if(document.getElementById('ftAO'))
                        document.getElementById('ftAO').style.display='none';
                }
            }
            if(document.getElementById('hd_bnb'))
            {
                if(document.getElementById('hd_bnb').value=='2')
                {
                    document.getElementById('ftAO').style.display='none';
                }
            }
//                     if( document.getElementById('hd_kl').value==1)
//                             document.getElementById('fd_md').style.display='none';
        }
    }
    // xmlhttp.open("GET","getReport51-II.php?seljudgename="+seljudgename+"&frm="+frm+"&toDate="+toDate,true);
    xmlhttp.open("GET","da_defect_getData?d_no="+t_h_cno+"&d_yr="+t_h_cyt,true);
    xmlhttp.send(null);
}
var com_data='';
var cnt_data=1;
var nm_cnt='';
var cntRem='';
var cn_upd_dt='';
var ck_totals='';
var ck_hd_id='';
var hd_show_dt='';
function checkRecords(str)
{
    nm_cnt='';

    var sp_amo=document.getElementById('sp_amo').style.display='none';
    var hdTotal_u= document.getElementById('hdTotal').value;
    cnt_data=parseInt (hdTotal_u)+1;

    document.getElementById('fiOD').style.display='block';

    // if(document.getElementById('hdChk_num_row_j'))
    if(document.getElementById('hdChk_num_row').value>'0')
        document.getElementById('btnModify').style.display='inline';
    

    var str1=str.split('_');

    var tb_nm=document.getElementById('tb_nm');
    var row0=document.createElement("tr");
    row0.setAttribute('id', 'tr_td'+cnt_data);
    var column1=document.createElement("td");
    var column2=document.createElement("td");
    var column3=document.createElement("td");
    var column4=document.createElement("td");
    var spAddObj= document.getElementById('spAddObj');
    var hd_id=document.createElement('input');
    hd_id.setAttribute('type', 'hidden');
    hd_id.setAttribute('id', 'hd_id'+cnt_data);
    spAddObj.appendChild(hd_id);


    var sp=document.createElement('span');
    sp.setAttribute('id', 'spAddObj'+cnt_data);

    var txtRem=document.createElement('input');
    txtRem.setAttribute('type', 'text');
    txtRem.setAttribute('id', 'txtRem'+cnt_data);
    txtRem.setAttribute('style', 'width:300px;');
    txtRem.setAttribute('onblur', 'getUppercase(this.id);');

    txtRem.setAttribute('onkeyup', 'obj_auto_com(this.id)');

    var txtRem_mul=document.createElement('input');
    txtRem_mul.setAttribute('type', 'text');
    txtRem_mul.setAttribute('id', 'txtRem_mul'+cnt_data);
    txtRem_mul.setAttribute('style', 'width:200px');

    txtRem_mul.setAttribute('onblur', 'getUppercase(this.id);');


    var chkbx=document.createElement('input');
    chkbx.setAttribute('type', 'checkbox');
    chkbx.setAttribute('id', 'chkbox_obj'+cnt_data);
    chkbx.setAttribute('onclick', 'getDone_upd(this.id);');
    var sp1=document.createElement('span');
    sp1.setAttribute('id', 'spAddObjjjj'+cnt_data);

    column1.appendChild(sp1);
    column1.appendChild(chkbx);
    row0.appendChild(column1);
    //spAddObj.appendChild(chkbx);
    //chkbx.setAttribute('checked', 'checked');
    column2.appendChild(sp);
    row0.appendChild(column2);
    column3.appendChild(txtRem);
    column4.appendChild(txtRem_mul);
    row0.appendChild(column3);
    row0.appendChild(column4);
    //spAddObj.appendChild(txtRem);
    tb_nm.appendChild(row0);
    spAddObj.appendChild(tb_nm);
    document.getElementById('spAddObjjjj'+cnt_data).style.display='none';

    document.getElementById('chkbox_obj'+cnt_data).checked=true;

    document.getElementById('hd_id'+cnt_data).value=str1[1];
    document.getElementById('spAddObjjjj'+cnt_data).innerHTML=cnt_data;
    document.getElementById('spAddObj'+cnt_data).innerHTML=document.getElementById('spObj_'+str1[1]).innerHTML;
    document.getElementById('hdTotal').value=cnt_data;
    document.getElementById('chkCheck_'+str1[1]).checked=false;
    for(var yy=1;yy<=cnt_data;yy++)
    {
        if(nm_cnt=='')
        {
            if(document.getElementById('hd_id'+yy))
                nm_cnt=document.getElementById('hd_id'+yy).value;
        }
        else
        {
            if(document.getElementById('hd_id'+yy))
                nm_cnt=nm_cnt+','+ document.getElementById('hd_id'+yy).value;

        }
    }
    cnt_data++;
    var f_noo='';
    if(document.getElementById('hd_fc').value=='')
    {
        document.getElementById('hd_fc').value= document.getElementById('hdTotal').value;
        f_noo= document.getElementById('hd_fc').value;
    }
    else
    {
        document.getElementById('hd_fc').value=parseInt(document.getElementById('hd_fc').value)+1;
        f_noo=document.getElementById('hd_fc').value;
    }

    document.getElementById('txtAuCom').value='';

    getRelRc('','');



}
//&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&

/*function getUpdateObj(nm_cnt)
 {
 //alert(nm_cnt);
 if(document.getElementById('txtAuCom'))
 var txtAuCom= document.getElementById('txtAuCom').value;
 //alert(txtAuCom);
 var hd_ci_cri= document.getElementById('hd_ci_cri').value;
 var se=nm_cnt;
 var xmlhttp;
 if (window.XMLHttpRequest)
 {// code for IE7+, Firefox, Chrome, Opera, Safari
 xmlhttp=new XMLHttpRequest();
 }
 else
 {// code for IE6, IE5
 xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
 }
 //  document.getElementById('div_result').innerHTML = '<table widht="100%" align="center"><tr><td><img src="images/loading_new.gif"/></td></tr></table>';

 xmlhttp.onreadystatechange=function()
 {
 if (xmlhttp.readyState==4 && xmlhttp.status==200)
 {
 // var txtAdeshika="txtAdeshika"+rowCount;
 document.getElementById('tbData').innerHTML=xmlhttp.responseText;
 //  document.getElementById('btnModify').style.display='inline';


 }
 }
 // xmlhttp.open("GET","getReport51-II.php?seljudgename="+seljudgename+"&frm="+frm+"&toDate="+toDate,true);
 xmlhttp.open("GET","get_upd_obj.php?se="+se+"&hd_ci_cri="+hd_ci_cri+"&txtAuCom="+txtAuCom,true);
 xmlhttp.send(null);
 } */
//****************************************************************************************************
function getDone()
{
    // alert(str);

    var cn_to_y=0;
    var cn_to_y_ck=0;
    var total=document.getElementById('hdTotal').value;
//alert(total);
//var mn=document.getElementById('mn').value;
//    var cs_tp=document.getElementById('cs_tp').value;
//    if(cs_tp.length==2)
//        {
//            cs_tp='0'+cs_tp;
//        }
//    var txtFNo=document.getElementById('txtFNo').value;
//    var txtYear=document.getElementById('txtYear').value;
//    var filling_no=mn+cs_tp+txtFNo+txtYear;
//
//var hd_ud=  document.getElementById('hd_ud').value;
    var d_no=$('#t_h_cno').val();
    var d_yr=$('#t_h_cyt').val();
    var txtRem1='';
    var spAddObj='';
    for(var yy=1;yy<=total;yy++)
    {
        if(document.getElementById('chkbox_obj'+yy))
        {
            var chkbox_obj=document.getElementById('chkbox_obj'+yy);
            if(chkbox_obj.checked==true)
            {
                ck_hd_id=document.getElementById('hd_id'+yy).value;
                txtRem=document.getElementById('txtRem'+yy).value;
                spAddObj= document.getElementById('spAddObj'+yy).innerHTML.trim();
                for(var zz=1;zz<=total;zz++)
                {
                    if(document.getElementById('chkbox_obj'+zz))
                    {
                        var chkbox_obj1=document.getElementById('chkbox_obj'+zz);
                        if(chkbox_obj1.checked==true)
                        {
                            if(yy!=zz)
                            {
                                ck_hd_id1=document.getElementById('hd_id'+zz).value;
                                txtRem1=document.getElementById('txtRem'+zz).value;
                                spAddObj1= document.getElementById('spAddObj'+zz).innerHTML;

                                if(ck_hd_id==ck_hd_id1 && txtRem==txtRem1)
                                {
                                    if(txtRem!='')
                                        txtRem=' ['+txtRem+'] ';

                                    alert('Defect '+spAddObj+txtRem+" already selected");
                                    return false;
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    $('#btnAdd').attr('disabled',true);
    for(var y=1;y<=total;y++)
    {
        if(document.getElementById('chkbox_obj'+y))
        {
            var chkbox_obj=document.getElementById('chkbox_obj'+y);
            if(chkbox_obj.checked==true)
            {
                cn_to_y_ck++;
            }
        }
    }
    var getText='';
    var ct_Text=1;
    for(var i=1;i<=total;i++)
    {
        if(document.getElementById('chkbox_obj'+i))
        {
            var chkbox_obj=document.getElementById('chkbox_obj'+i);
            if(chkbox_obj.checked==true)
            {
                //  alert("ans");
                if(getText=='')
                {
                    getText='<b>('+ct_Text+')</b>'+ document.getElementById('spAddObj'+i).innerHTML.trim()+'<b>-</b><u>' +document.getElementById('txtRem'+i).value.trim()+' '+document.getElementById('txtRem_mul'+i).value.trim()+'</u>';
                }
                else
                {
                    getText=getText+'<b>('+ct_Text+')</b>'+ document.getElementById('spAddObj'+i).innerHTML.trim()+'<b>-</b><u>'+ document.getElementById('txtRem'+i).value.trim()+' '+document.getElementById('txtRem_mul'+i).value.trim()+'</u>';
                }
                getText=getText.replace(/<br>/g,'').trim();
                //  getText=getText.replace(//g,'');
                getText=getText.replace(/  /g,'');
                // alert(getText);
                chkbox_obj.setAttribute('onclick', '');
                document.getElementById('chkbox_obj'+i).checked=false;
                cn_to_y++;
                var txtRem=document.getElementById('txtRem'+i).value;
                var txtRem_mul=document.getElementById('txtRem_mul'+i).value;
                var hd_id=document.getElementById('hd_id'+i).value;
                if(ck_hd_id=='')
                    ck_hd_id=document.getElementById('hd_id'+i).value;
                else
                    ck_hd_id=ck_hd_id+','+document.getElementById('hd_id'+i).value;
                document.getElementById('txtRem'+i).disabled=true;
                document.getElementById('txtRem_mul'+i).disabled=true;
                var xmlhttp;
                if (window.XMLHttpRequest)
                {// code for IE7+, Firefox, Chrome, Opera, Safari
                    xmlhttp=new XMLHttpRequest();
                }
                else
                {// code for IE6, IE5
                    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
                }
                document.getElementById('div_show').innerHTML = '<table widht="100%" align="center"><tr><td><img src="../images/load.gif"/></td></tr></table>';
                if(cn_to_y==cn_to_y_ck)
                {
                    //  alert(i);
                    xmlhttp.onreadystatechange=function()
                    {
                        if (xmlhttp.readyState==4 && xmlhttp.status==200)
                        {
                            // var txtAdeshika="txtAdeshika"+rowCount;
                            document.getElementById('div_show').innerHTML=xmlhttp.responseText;
                            document.getElementById('btnAdd').style.display='none';
//                        document.getElementById('btnModify').style.display='inline';
                            document.getElementById('sp_amo').style.display='inline';
                            //   document.getElementById('btnRemove').style.display='inline';
                            document.getElementById('ftAO').style.display='none';
                            document.getElementById('hdChk_num_row').value=1;
                            //   get_bb_ref(filling_no);
                            // alert(ck_hd_id);
                            //   getDone_upd(ck_hd_id);
                        }
                    }
                }
                // document.getElementById('chkbox_obj'+i).checked=false;
                // xmlhttp.open("GET","getReport51-II.php?seljudgename="+seljudgename+"&frm="+frm+"&toDate="+toDate,true);
                xmlhttp.open("GET","save_data?hd_id="+hd_id+"&d_no="+d_no+"&i="+cn_to_y+"&total="+cn_to_y_ck+"&getText="+getText+"&d_yr="+d_yr+"&txtRem="+txtRem+"&iu=0"+"&txtRem_mul="+txtRem_mul,false);
                xmlhttp.send(null);
            }

            else
            {
                document.getElementById('spAddObjjjj'+i).style.display='none';
                document.getElementById('chkbox_obj'+i).style.display='none';
                document.getElementById('spAddObj'+i).style.display='none';
                //document.getElementById('chkbox_obj'+i).checked=false;
                if(ui=='')
                    ui=  document.getElementById('hd_id'+i).value;
                else
                    ui=ui+','+ document.getElementById('hd_id'+i).value;

//        alert(ck_hd_id);
//        alert(document.getElementById('hd_id'+i).value);
//    var  ck_hd_id_ck=ck_hd_id.split(',');
//                        for(var b=0;b<ck_hd_id_ck.length;b++)
//                        {
//                           // alert(hd_show_dt);
//                            if(ck_hd_id_ck[b]==document.getElementById('hd_id'+i).value)
//                             ck_hd_id= ck_hd_id.replace(','+ck_hd_id_ck[b],'ghghghghghgh')
//                        }
//                            alert(ck_hd_id);


//       if(hd_show_dt=='')
//       hd_show_dt=  document.getElementById('hd_id'+i).value;
//   else
//     hd_show_dt=hd_show_dt+','+ document.getElementById('hd_id'+i).value;
            }
            ct_Text++;
        }

    }
}

//function get_bb_ref(filling_no)
//{
//   alert(filling_no);
//   var xmlhttp;
//                if (window.XMLHttpRequest)
//                {// code for IE7+, Firefox, Chrome, Opera, Safari
//                    xmlhttp=new XMLHttpRequest();
//                }
//                else
//                {// code for IE6, IE5
//                    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
//                }
//             //  document.getElementById('div_result').innerHTML = '<table widht="100%" align="center"><tr><td><img src="images/loading_new.gif"/></td></tr></table>';
//
//             //  alert(i);
//               xmlhttp.onreadystatechange=function()
//                {
//                    if (xmlhttp.readyState==4 && xmlhttp.status==200)
//                    {
//                       // var txtAdeshika="txtAdeshika"+rowCount;
//                        document.getElementById('spAddObj').innerHTML=xmlhttp.responseText;
////
//
//
//                    }
//                }
//
//
//               // xmlhttp.open("GET","getReport51-II.php?seljudgename="+seljudgename+"&frm="+frm+"&toDate="+toDate,true);
//                 xmlhttp.open("GET","get_ob_n.php?total="+filling_no,true);
//            xmlhttp.send(null);
//}

function rem_obj()
{
    //alert("ans");
    var mn=document.getElementById('mn').value;
    var cs_tp=document.getElementById('cs_tp').value;
    if(cs_tp.length==2)
    {
        cs_tp='0'+cs_tp;
    }
    var txtFNo=document.getElementById('txtFNo').value;
    var txtYear=document.getElementById('txtYear').value;
    var filling_no=mn+cs_tp+txtFNo+txtYear;
    //  filling_no=total;



    var cn_to_y=0;
    var cn_to_y_ck=0;
    var total=document.getElementById('hdTotal').value;
    for(var y=1;y<=total;y++)
    {
        if(document.getElementById('chkbox_obj'+y))
        {
            var chkbox_obj=document.getElementById('chkbox_obj'+y);
            if(chkbox_obj.checked==true)
            {
                cn_to_y_ck++;
            }
        }
    }

    for(var i=1;i<=total;i++)
    {
        if(document.getElementById('chkbox_obj'+i))
        {
            var chkbox_obj=document.getElementById('chkbox_obj'+i);
            if(chkbox_obj.checked==true)
            {
                document.getElementById('sp_amo').style.display='none';
                cn_to_y++;
                var hd_id=document.getElementById('hd_id'+i).value;

                var xmlhttp;
                if (window.XMLHttpRequest)
                {// code for IE7+, Firefox, Chrome, Opera, Safari
                    xmlhttp=new XMLHttpRequest();
                }
                else
                {// code for IE6, IE5
                    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
                }
                //  document.getElementById('div_result').innerHTML = '<table widht="100%" align="center"><tr><td><img src="images/loading_new.gif"/></td></tr></table>';
                if(cn_to_y==cn_to_y_ck)
                {
                    //  alert(i);
                    xmlhttp.onreadystatechange=function()
                    {
                        if (xmlhttp.readyState==4 && xmlhttp.status==200)
                        {
                            // var txtAdeshika="txtAdeshika"+rowCount;
                            document.getElementById('div_show').innerHTML=xmlhttp.responseText;
//                          document.getElementById('btnAdd').style.display='none';
//                        document.getElementById('btnModify').style.display='inline';
//                          document.getElementById('btnRemove').style.display='inline';
                            document.getElementById('ftAO').style.display='none';
                            document.getElementById('fdDR').style.display='block';
                            show_fdDR();


                        }
                    }
                }
                document.getElementById('chkbox_obj'+i).checked=false;
                document.getElementById('chkbox_obj'+i).style.display='none';
                document.getElementById('spAddObjjjj'+i).style.display='none';
                document.getElementById('spAddObj'+i).style.display='none';
                if(document.getElementById('sp_hide'+i))
                    document.getElementById('sp_hide'+i).style.display='none';
                // xmlhttp.open("GET","getReport51-II.php?seljudgename="+seljudgename+"&frm="+frm+"&toDate="+toDate,true);
                //xmlhttp.open("GET","modify_data?hd_id="+hd_id+"&filling_no="+filling_no+"&i="+cn_to_y+"&total="+cn_to_y_ck,false);
                //xmlhttp.send(null);
            }
            else
            {
//     document.getElementById('spAddObjjjj'+i).style.display='none';
//      document.getElementById('chkbox_obj'+i).style.display='none';
//       document.getElementById('spAddObj'+i).style.display='none';
            }
        }
    }
}

function show_fdDR()
{
    var mn=document.getElementById('mn').value;
    var cs_tp=document.getElementById('cs_tp').value;
    if(cs_tp.length==2)
    {
        cs_tp='0'+cs_tp;
    }
    var txtFNo=document.getElementById('txtFNo').value;
    var txtYear=document.getElementById('txtYear').value;
    var filling_no=mn+cs_tp+txtFNo+txtYear;

    var xmlhttp;
    if (window.XMLHttpRequest)
    {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
    }
    else
    {// code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    //  document.getElementById('div_result').innerHTML = '<table widht="100%" align="center"><tr><td><img src="images/loading_new.gif"/></td></tr></table>';

    //  alert(i);
    xmlhttp.onreadystatechange=function()
    {
        if (xmlhttp.readyState==4 && xmlhttp.status==200)
        {
            // var txtAdeshika="txtAdeshika"+rowCount;
            document.getElementById('sp_fdDR').innerHTML=xmlhttp.responseText;
//


        }
    }


    // xmlhttp.open("GET","getReport51-II.php?seljudgename="+seljudgename+"&frm="+frm+"&toDate="+toDate,true);
    //xmlhttp.open("GET","getFDdr?total="+filling_no,true);
    //xmlhttp.send(null);
}

function getDone_upd(str)
{
    // cn_upd_dt=''
    // alert(str);
    // var str1=str.split('chkbox_obj');
    var f_noo='';
    var str1=str.split('chkbox_obj');
//  alert("llll");
    var str_res=str1[1];
    if(document.getElementById(str).checked==true)
    {
        //  var hd_check=document.getElementById('hd_check').value;


//  var str1=str.split('chkbox_obj');
//  if(cn_upd_dt=='')
//  cn_upd_dt= document.getElementById('hd_id'+str1[1]).value;
//else
//    {
//
//////   cn_upd_dt=cn_upd_dt+','+document.getElementById('hd_id'+str1[1]).value;
////   var cn_upd_dt1=cn_upd_dt.split(',');
////   var cs_ck=0;
////   for(var i=0;i<cn_upd_dt1.length;i++)
////   {
////       if(cn_upd_dt1[i]==document.getElementById('hd_id'+str1[1]).value)
////          {
////              cs_ck=1;
////          }
////   }
////   if(cs_ck==0)
//       cn_upd_dt=cn_upd_dt+','+document.getElementById('hd_id'+str1[1]).value;
//  // else if(cs_ck==1)
//
//    }
        // alert(cn_upd_dt);
        // getUpdateObj(tyu);
    }
    else
    {
        // alert(cn_upd_dt);
//     var spAddObj= document.getElementById('spAddObj');
//    var t= document.getElementById('hd_id'+str1[1]).value;
//    spAddObj.removeChild(t);
        document.getElementById('hd_fc').value=document.getElementById('hd_fc').value-1;
        if(document.getElementById('hd_fc').value==0)
        {
            //alert("ans");
            document.getElementById('fiOD').style.display='none';
            document.getElementById('ftAO').style.display='block';
        }
        var tr_td=document.getElementById('tr_td'+str1[1]);

        var t=document.getElementById('spAddObj'+str1[1]);

        var t1=document.getElementById('spAddObjjjj'+str1[1]);
        var t2=document.getElementById('chkbox_obj'+str1[1]);
        var t3=document.getElementById('hd_id'+str1[1]);

//var spAddObj=document.getElementById('spAddObj');
        var spAddObj=document.getElementById('tb_nm');
        spAddObj.removeChild(tr_td);
//spAddObj.removeChild(t);
//spAddObj.removeChild(t1);
// spAddObj.removeChild(t2);
// spAddObj.removeChild(t3);
        var hdTotal=document.getElementById('hdTotal').value;
        var tyu='';
        //alert(tyu);
        var z=0;
        for(var hj=1;hj<=hdTotal;hj++)
        {
            if(document.getElementById('chkbox_obj'+hj))
            {
                if(document.getElementById('chkbox_obj'+hj).checked==true)

                {
                    z=1;
                }
                if(tyu=='')
                    tyu= document.getElementById('hd_id'+hj).value;
                else
                    tyu=tyu+',' +document.getElementById('hd_id'+hj).value;
                //  document.getElementById('chkbox_obj'+hj).disabled=false;
                // }
            }
        }

        if(z==0)
        {
            for(var hj=1;hj<=hdTotal;hj++)
            {
                if(document.getElementById('chkbox_obj'+hj))
                    document.getElementById('chkbox_obj'+hj).disabled=false;
            }


            //document.getElementById('btnRemove').disabled=false;
            document.getElementById('sp_amo').style.display='inline';
            document.getElementById('btnModify').style.display='none';
            document.getElementById('ftAO').style.display='none';
        }
        if(tyu=='')
            tyu=0;
        //&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&
        //getUpdateObj(tyu)
        //******************************************************************************************
    }
}
//ans='';
function go_other()
{

    document.getElementById('div_show').innerHTML='';
    ans='';
    if(document.getElementById('ftAO'))
        document.getElementById('ftAO').style.display='block';
    document.getElementById('btnClose').style.display='inline';
    document.getElementById('sp_amo').style.display='none';
    var hdTotal_s=document.getElementById('hdTotal').value;
    for(var t=1;t<=hdTotal_s;t++)
    {
        if(document.getElementById('chkbox_obj'+t))
        {
           
            if(ans=='')
                ans= document.getElementById('hd_id'+t).value;
            else
            {

                ans=ans+','+ document.getElementById('hd_id'+t).value;

            }
        }
       
        if( document.getElementById('chkbox_obj'+t))
        {
            document.getElementById('chkbox_obj'+t).disabled=true;
            document.getElementById('chkbox_obj'+t).checked=false;
        }
    }
    
    getRelRc('','')
  
}

function getDone1()
{
    
    
    // alert(str);
    //cn_upd_dt='';
    // var confirmSave = confirm("Do you want to save the record?");
    
    // // If the user chooses 'No', cancel the operation
    // if (!confirmSave) {
    //     return false;
    // }

    $('#btnAdd').attr('disabled',true);
    var cn_to_y=0;
    var cn_to_y_ck=0;
    var total=document.getElementById('hdTotal').value;

    var d_no=$('#t_h_cno').val();
    var d_yr=$('#t_h_cyt').val();

    var txtRem1='';
    var spAddObj='';
    for(var yy=1;yy<=total;yy++)
    {
        if(document.getElementById('chkbox_obj'+yy))
        {
            var chkbox_obj=document.getElementById('chkbox_obj'+yy);

            ck_hd_id=document.getElementById('hd_id'+yy).value;
            if($('#txtRem'+yy).length)
                txtRem=document.getElementById('txtRem'+yy).value;
            else
                txtRem=document.getElementById('spRema'+yy).innerHTML;
            spAddObj= document.getElementById('spAddObj'+yy).innerHTML.trim();
            for(var zz=1;zz<=total;zz++)
            {
                if(document.getElementById('chkbox_obj'+zz))
                {
                    var chkbox_obj1=document.getElementById('chkbox_obj'+zz);

                    if(yy!=zz)
                    {
                        ck_hd_id1=document.getElementById('hd_id'+zz).value;
                        if($('#txtRem'+zz).length)
                            txtRem1=document.getElementById('txtRem'+zz).value;
                        else
                            txtRem1=document.getElementById('spRema'+zz).innerHTML;
                        spAddObj1= document.getElementById('spAddObj'+zz).innerHTML;

                        if(ck_hd_id==ck_hd_id1 && txtRem==txtRem1)
                        {
                            if(txtRem!='')
                                txtRem=' ['+txtRem+'] ';

                            alert('Defect '+spAddObj+txtRem+" already selected");
                            return false;
                        }
                    }
                }
            }
        }
    }
    for(var y=1;y<=total;y++)
    {
        if(document.getElementById('chkbox_obj'+y))
        {
            var chkbox_obj=document.getElementById('chkbox_obj'+y);
            if(chkbox_obj.checked==true)
            {
                cn_to_y_ck++;
            }
        }
    }
    var getText='';
    var ct_Text=1;
    for(var i=1;i<=total;i++)
    {
        if(document.getElementById('chkbox_obj'+i))
        {
            var chkbox_obj=document.getElementById('chkbox_obj'+i);
            if(chkbox_obj.checked==true)
            {
                //  alert("ans");
                if(getText=='')
                {
                    getText='<b>('+ct_Text+')</b>'+ document.getElementById('spAddObj'+i).innerHTML.trim()+ '<b>-</b><u>'+document.getElementById('txtRem'+i).value.trim()+' '+document.getElementById('txtRem_mul'+i).value.trim()+'</u>';
                }
                else
                {
                    getText=getText+'<b>('+ct_Text+')</b>'+ document.getElementById('spAddObj'+i).innerHTML.trim()+'<b>-</b><u>'+ document.getElementById('txtRem'+i).value.trim()+' '+document.getElementById('txtRem_mul'+i).value.trim()+'</u>';
                }
                getText=getText.replace(/<br>/g,'').trim();
                getText=getText.replace(/  /g,'');
                chkbox_obj.setAttribute('onclick', '');
                cn_to_y++;
                var hd_id=document.getElementById('hd_id'+i).value;
                var txtRem=document.getElementById('txtRem'+i).value;
                var txtRem_mul=document.getElementById('txtRem_mul'+i).value;
                if(ck_hd_id=='')
                    ck_hd_id=document.getElementById('hd_id'+i).value;

                else
                    ck_hd_id=ck_hd_id+','+document.getElementById('hd_id'+i).value;

                var xmlhttp;
                if (window.XMLHttpRequest)
                {// code for IE7+, Firefox, Chrome, Opera, Safari
                    xmlhttp=new XMLHttpRequest();
                }
                else
                {// code for IE6, IE5
                    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
                }
                document.getElementById('div_show').innerHTML = '<table widht="100%" align="center"><tr><td><img src="../images/load.gif"/></td></tr></table>';
                document.getElementById('txtRem'+i).disabled=true;
                document.getElementById('txtRem_mul'+i).disabled=true;
                if(cn_to_y==cn_to_y_ck)
                {
                    xmlhttp.onreadystatechange=function()
                    {
                        if (xmlhttp.readyState==4 && xmlhttp.status==200)
                        {
                            document.getElementById('div_show').innerHTML=xmlhttp.responseText;
                            //  document.getElementById('btnAdd').style.display='none';
                            document.getElementById('btnModify').style.display='none';
                            document.getElementById('sp_amo').style.display='inline';
                            //  document.getElementById('btnRemove').style.display='inline';
                            document.getElementById('ftAO').style.display='none';
                            //   document.getElementById('btnRemove').disabled=false;
                            var hdTotal_s=document.getElementById('hdTotal').value;
                            for(var t=1;t<=hdTotal_s;t++)
                            {
                                if(document.getElementById('chkbox_obj'+t))
                                    document.getElementById('chkbox_obj'+t).disabled=false;
                            }

                        }
                    }
                }
                if(document.getElementById('chkbox_obj'+i))
                    document.getElementById('chkbox_obj'+i).checked=false;
                // xmlhttp.open("GET","getReport51-II.php?seljudgename="+seljudgename+"&frm="+frm+"&toDate="+toDate,true);
                xmlhttp.open("GET","save_data?hd_id="+hd_id+"&d_no="+d_no+"&i="+cn_to_y+"&total="+cn_to_y_ck+"&d_yr="+d_yr+"&txtRem="+txtRem+"&getText="+getText+"&iu=1"+"&txtRem_mul="+txtRem_mul,false);
                xmlhttp.send(null);
            }
            else
            {
                if(document.getElementById('chkbox_obj'+i))
                {
                    if(document.getElementById('chkbox_obj'+i).disabled==false)
                    {
                        document.getElementById('spAddObjjjj'+i).style.display='none';
                        document.getElementById('chkbox_obj'+i).style.display='none';
                        document.getElementById('spAddObj'+i).style.display='none';
                    }
                }
            }
            ct_Text++;
        }

    }
}

function closeData()
{
    document.getElementById('ftAO').style.display="none";
    document.getElementById('sp_amo').style.display="inline";
    document.getElementById('btnClose').style.display="none";
    //  document.getElementById('btnRemove').disabled=false;
    var hdTotal_s=document.getElementById('hdTotal').value;
    for(var t=1;t<=hdTotal_s;t++)
    {
        if(document.getElementById('chkbox_obj'+t))
            document.getElementById('chkbox_obj'+t).disabled=false;
    }
}



function getRelRc(str,strVal)
{
    var hdTotal=document.getElementById('hdTotal').value;
    var allow_entry_in_registered_matter=document.getElementById('allow_entry_in_registered_matter').value;
    var tyu='';
    // alert(hdTotal);
    var z=0;
    for(var hj=1;hj<=hdTotal;hj++)
    {
        if(document.getElementById('chkbox_obj'+hj))
        {
            if(document.getElementById('chkbox_obj'+hj).checked==true)

            {
                z=1;
            }
            if(tyu=='')
                tyu= document.getElementById('hd_id'+hj).value;
            else
                tyu=tyu+',' +document.getElementById('hd_id'+hj).value;
            //  document.getElementById('chkbox_obj'+hj).disabled=false;
            // }
        }
    }


    var se=tyu;
 
    var xmlhttp;
    if (window.XMLHttpRequest)
    {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
    }
    else
    {// code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }

    xmlhttp.onreadystatechange=function()
    {
        if (xmlhttp.readyState==4 && xmlhttp.status==200)
        {
            document.getElementById('tbData').innerHTML=xmlhttp.responseText;


        }
    }
    xmlhttp.open("GET","get_upd_obj_u?strVal="+strVal+"&se="+se+"&allow="+allow_entry_in_registered_matter,true);
    xmlhttp.send(null);

}

function getUppercase(str)
{
    // document.getElementById(str).style.textDecoration='underline';
    document.getElementById(str).value= document.getElementById(str).value.toUpperCase();



}


$(document).ready(function () {
    $(document).on('click', '#btn_sms', async function () {
        var t_h_cno = $('#t_h_cno').val();
        var t_h_cyt = $('#t_h_cyt').val();
        var requestData = t_h_cno + t_h_cyt;

        try {
            // Step 1: Send email
            await send_email(t_h_cno, t_h_cyt);

            // Step 2: Refresh CSRF before defect check
            await updateCSRFTokenSync();
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

            // Step 3: Check for defects using async/await
            const defectResponse = await $.ajax({
                url: 'obj_save_get',
                type: 'POST',
                data: {
                    diary_no: requestData,
                    [CSRF_TOKEN]: CSRF_TOKEN_VALUE
                }
            });

            //alert(defectResponse); // This will now work

            if (defectResponse === 'no_entries') {
                alert('Kindly add defects.');
                return;
            }

            var confirmSend = confirm("Further defects will not be added, Do you want to proceed?");
            if (!confirmSend) return;

            // Step 4: Refresh CSRF before final SMS call
            await updateCSRFTokenSync();
            CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

            $.ajax({
                url: 'save_sms_det',
                type: 'POST',
                cache: false,
                async: true,
                data: {
                    d_no: t_h_cno,
                    d_yr: t_h_cyt,
                    sms_status: 'D',
                    [CSRF_TOKEN]: CSRF_TOKEN_VALUE
                },
                beforeSend: function () {
                    $('#sp_sms_status').html('<table width="100%" align="center"><tr><td><img src="../images/load.gif"/></td></tr></table>');
                },
                success: function (data) {
                    $('#sp_sms_status').html(data);
                    //location.reload();
                },
                error: function (xhr) {
                    alert("Error: " + xhr.status + " " + xhr.statusText);
                }
            });

        } catch (err) {
            alert("Failed: " + err);
        }
    });
});




async function send_email(t_h_cno, t_h_cyt) {
    await updateCSRFTokenSync();
    
    var CSRF_TOKEN = 'CSRF_TOKEN';
    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

    return $.ajax({
        url: 'send_email',
        type: 'POST',
        cache: false,
        async: true,
        data: {
            d_no: t_h_cno,
            d_yr: t_h_cyt,
            [CSRF_TOKEN]: CSRF_TOKEN_VALUE
        },
        beforeSend: function () {
            $('#sp_sms_status').html('<table width="100%" align="center"><tr><td><img src="../images/load.gif"/></td></tr></table>');
        },
        success: function (data) {
            $('#sp_sms_status').html(data);
            alert(data);
        }
    });
}


 