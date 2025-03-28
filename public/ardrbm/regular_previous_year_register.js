var dtCh= "/";
var minYear=1900;
var maxYear=2100;

function getXMLHTTP()
{ //fuction to return the xml http object
    var xmlhttp=false;
    try{
        xmlhttp=new XMLHttpRequest();
    }
    catch(e)	{
        try{
            xmlhttp= new ActiveXObject("Microsoft.XMLHTTP");
        }
        catch(e){
            try{
                xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
            }
            catch(e1){
                xmlhttp=false;
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



function isInteger(s){
    var i;
    for (i = 0; i < s.length; i++)
    {
        var c = s.charAt(i);
        if (((c < "0") || (c > "9"))) return false;
    }
    return true;
}

function textformate(cb){
    var y=document.getElementById('hdremp'+cb).value;
    x=y.split(",");
    if(cb == 72){
        for (var i = 0; i < x.length; i++)
        {
            var iChars = "~`!#$%^&*+=-[]\\\';/{}|\":<>?";
            for (var j = 0; j < x[i].length; j++)
            {
                if (iChars.indexOf(x[i].charAt(j)) !== -1)
                {
                    alert ("Special characters ~`!#$%^&*+=-[]\\\';/{}|\":<>? \nThese are not allowed\n");
                    return false;
                }
            }
            casenoyr=(x[i].replace(/[^0-9]/g,"").length);
            casetyp=(x[i].replace(/[^a-zA-Z]/g,"").length);
            ctype=x[i].replace(/[^a-zA-Z]/g,"");
            ctyp=ctype.toUpperCase();
            var cpa=0;
            switch(ctyp)
            {
                case 'AA':break;
                case 'AC':break;
                case 'AR':break;
                case 'ARBA':break;
                case 'ARBC':break;
                case 'CA':break;
                case 'CEA':break;
                case 'CER':break;
                case 'CESR':break;
                case 'COMA':break;
                case 'COMP':break;
                case 'COMPA':break;
                case 'CONA':break;
                case 'CONC':break;
                case 'CONCR':break;
                case 'CONT':break;
                case 'CONTR':break;
                case 'CR':break;
                case 'CRA':break;
                case 'CRR':break;
                case 'CRRE':break;
                case 'CRRF':break;
                case 'CRRFC':break;
                case 'CS':break;
                case 'EP':break;
                case 'FA':break;
                case 'FEMA':break;
                case 'GTR':break;
                case 'ITA':break;
                case 'ITR':break;
                case 'LPA':break;
                case 'MA':break;
                case 'MACE':break;
                case 'MACOM':break;
                case 'MACTR':break;
                case 'MAIT':break;
                case 'MAVAT':break;
                case 'MCC':break;
                case 'MCOMA':break;
                case 'MCP':break;
                case 'MCRC':break;
                case 'MCRP':break;
                case 'MP':break;
                case 'MWP':break;
                case 'OTA':break;
                case 'RP':break;
                case 'SA':break;
                case 'SLP':break;
                case 'STR':break;
                case 'TR':break;
                case 'VATA':break;
                case 'WA':break;
                case 'WP':break;
                case 'WPS':break;
                case 'WTA':break;
                case 'WTR':break;
                default:{
                    alert("Please Enter proper Case ");
                    cpa++;
                    return false;
                }
            }

            casetyp = x[i].slice( - casetyp);
            cnyr = x[i].slice( - casenoyr);
            var x1 = x[i].slice( - cnyr);
            if (casenoyr <= 4) {
                alert("Please Type Correct Case No And Year");
                return false;
            }
            if (casenoyr == 5) cnyr = '0000' + cnyr;
            if (casenoyr == 6) cnyr = '000' + cnyr;
            if (casenoyr == 7) cnyr = '00' + cnyr;
            if (casenoyr == 8) cnyr = '0' + cnyr;
            var yr = cnyr.slice( - 4);
            var srvr = document.getElementById('srvr').value;
            if (yr <= 1959 )
            {
                alert("Please Enter Correct Year Greater then 1959");
                return false;
            }
            if(yr >srvr){
                alert("Please Enter Correct Year Less  then "+srvr);
                return false;
            }
        }
    }

    if (cb == 68  || cb == 23  || cb == 53 || cb == 54 || cb == 25 || cb == 122 || cb == 123){
        if (isNaN(y))
        {
            alert('Please Enter Numeric Value');
            setFocusToTextBox(cb);
            return false;
        }
    }
    if(cb == 53 || cb == 25 )
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
            // y.focus();
            setFocusToTextBox(cb);
            return false;
        }
    }
    if (cb == 68 || cb == 123 || cb == 54 )
    {
        if (y >= 12 && y!==0)
        {
            alert('Please Enter Numeric Value Between 1 TO 12 Which Is Month Of The Year');
            setFocusToTextBox(cb);
            return false;
        }
    }
    return true;
}

function setFocusToTextBox(cb){
    var textbox = document.getElementById('hdremp'+cb);
    $("#hdremp"+cb).focus();
    textbox.scrollIntoView();
}
function chg_def()
{
    if(document.getElementById("subhead_select").value=="850")
    {
        if($("#bench").val()=="S")
            $("#sbj").val("514");

        if($("#bench").val()=="D")
            $("#dbj1").val("514");
    }
    else
    {
        if($("#bench").val()=="S")
            $("#sbj").val("250");

        if($("#bench").val()=="D")
            $("#dbj1").val("200");

    }
}

function feed_rmrk()
{
    var ccstr="";
    var obrdrem=document.getElementById("brdremh").value;
    document.getElementById("brdrem").value='';
    ccstr=obrdrem;
    $("input[type='checkbox'][name^='iachbx']").each(function(){
        var isChecked = document.getElementById($(this).attr('id')).checked;
        if(isChecked)
        {
            var tval= $(this).val().split("|#|");
            if(ccstr!='')
                ccstr+=" \nFOR " + tval[1] + " ON IA "+tval[0];
            else
                ccstr+=" FOR " + tval[1] + "  ON IA "+tval[0];
        }
    });
    //alert(ccstr);
    document.getElementById("brdrem").value=ccstr;
}
//function save_rec_prop()
//{
//var today = new Date();
//var url = "insert_rec_prop.php";
//var http = new getXMLHttpRequestObject();
//var str1 = "";
//var flno=document.getElementById("fil_no").value;
//var thdt=document.getElementById("thdate").value;
//var thdt1=thdt.split("-");
//var thdt_new= thdt1[2] + "-" + thdt1[1] + "-" + thdt1[0];
//var hdate = new Date(thdt.replace("-","/"));
//var curdate = new Date(); // get system date
//curdate_utc = Date.UTC( curdate.getFullYear (), curdate.getMonth(), curdate.getDate(),0,0,0,0);
//hdate_utc = Date.UTC(thdt1[2], thdt1[1]-1, thdt1[0],0,0,0,0);  // month is 0 to 11 not 1 to 12
//if (curdate_utc>= hdate_utc)
//     {
//         alert("Sorry! Date of proposal cannot be before todays date!");
//         return false;
//     }
//var mf=document.frm.mf_select.value;
//var sh=document.frm.subhead_select.value;
//var bnch=document.frm.bench.value;
//var lo=document.frm.listorder.value;
//var br=document.frm.brdrem.value;
//var rem=document.frm.rem.value;
//var conncs=document.frm.conncs.value;
//var ucode=document.getElementById('hd_ud').value;
//var sbj=document.frm.sbj.value;
//var dbj1=document.frm.dbj1.value;
//var dbj2=document.frm.dbj2.value;
//
////var str="insert_rec_an.php?str="+str1+"&dt="+dt_new;
////alert(str);
//////str1=flno+"|"+thdt_new+"|"+mf+"|"+sh+"|"+bnch+"|"+lo+"|"+br+"|"+rem+"|"+conncs;
//
//
////alert(str_new);
////alert(str1);
//var ccstr="";
//    $("input[type='checkbox'][name^='ccchk']").each(function(){
//         var isChecked = document.getElementById($(this).attr('id')).checked;
//         if(isChecked)
//             {
//                  ccstr+=$(this).val()+",";
//             }
//    });
//var ccstr1="";
//    $("input[type='checkbox'][name^='iachbx']").each(function(){
//         var isChecked = document.getElementById($(this).attr('id')).checked;
//         if(isChecked)
//             {
//                  ccstr1+=$(this).val()+",";
//             }
//    });
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
//close_w(3);
//
//}

//function save_rec_adv()
//{
//var url = "insert_rec_adv.php";
//var http = new getXMLHttpRequestObject();
//var str1 = "";
//var flno=document.getElementById("fil_no").value;
//var teno=document.getElementById("txt_e_no").value;
//var teny=document.getElementById("txt_e_yr").value;
//var advside=document.getElementById("advside").value;
//var parameters="flno="+flno;
//parameters += "&teno="+teno;
//parameters += "&teny="+teny;
//parameters += "&advside="+advside;
//
//
////document.getElementById("proc").innerHTML="<img src='saving.gif'/>";
//http.open("POST", url, true);
////Send the proper header information along with the request
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
//                      fsubmit();
//                  //$("#submit").click();
//                  }
//                  //document.getElementById("caseval"+cn).value=str_caseval;
//                  //document.getElementById("cr_span"+cn).innerHTML=cr_head;
//	}
//}
//http.send(parameters);
//close_w(4);
//
//}

//function save_rec(cnt)
//{
//    var cn="";var stat="";var cr_head="";
//    if(cnt==1)
//    {  var div1="chkp"; var div2="hdremp"; cn=$('#tmp_casenop').val(); stat="P"; cr_head='<b><font color="blue">'; }
//else
//    {  var div1="chkd"; var div2="hdremd"; cn=$('#tmp_casenod').val(); stat="D"; cr_head='<b><font color="red">'; }
//var chk_val;
//var cval="";
//var crem="";
//var str_new="";
//var str_caseval="";
//var isfalse=0;
//    $("input[type='checkbox'][name^='"+div1+"']").each(function(){
//          var isChecked = document.getElementById($(this).attr('id')).checked;
//         if(isChecked)
//             {
//                  chk_val=$(this).val().split("|");
//                  cval=$("#"+div1+chk_val[0]).val().split("|");
//
//                  if(cnt==1)
//                      {
//                      if(textformate(cval[0])==false)
//                      {
//                          isfalse=1;
//                      }
//                      if(cval[0]==24 || cval[0]==21 || cval[0]==70  || cval[0]==59)
//                        {
//                           // alert(cval[0]);
//                        if($("#"+div2+cval[0]).val()=='')
//                        {
//                            alert('Please Enter Date');
//                            setFocusToTextBox(cval[0]);
//                            isfalse=1;
//                        }
//                        }
//                      }
//                     // alert(chk_val[0]);
//                  crem=$("#"+div2+chk_val[0]).val();
//                  str_new+=cval[0]+"|"+crem+"!";
//                  str_caseval+=cval[0]+"|"+crem+"^^";
//                  cr_head+=cval[1];
//                   if(crem!="")
//                   cr_head+=' ['+crem+']';
//                 cr_head+='<br>';
//             }
//    });
//    cr_head+='</font></b>';
//
////str2=str2+"<tr><td>"+cntr+"</td><td>"+document.getElementById("brd"+filno).innerHTML+"</td><td>"+document.getElementById("cs"+filno).innerHTML+"</td><td>"+document.getElementById("pn"+filno).innerHTML+"</td><td>"+document.getElementById("rn"+filno).innerHTML+"</td><td>"+document.getElementById("pad"+filno).innerHTML+"</td><td>"+document.getElementById("rad"+filno).innerHTML+"</td></tr>";
//
//if(isfalse==0)
//{
//var url = "insert_rec_an.php";
//var http = new getXMLHttpRequestObject();
////var xhr2=getXMLHTTP();
//var str1 = "";
//var dt=document.getElementById("dtd").value;
//var hdt=document.getElementById("hdate").value;
////var msg_t=document.getElementById("msgbox").value;
//var dt1=dt.split("-");
//var dt_new= dt1[2] + "-" + dt1[1] + "-" + dt1[0];
//
//var hdt1=hdt.split("-");
//var hdt_new= hdt1[2] + "-" + hdt1[1] + "-" + hdt1[0];
//
////var str="insert_rec_an.php?str="+str1+"&dt="+dt_new;
////alert(str);
//str1=document.getElementById("jcodes").value+"|"+document.getElementById("mh").value+"|"+document.getElementById("clno").value;
//
//str_new=cn+"#"+stat+"#"+str_new;
////alert(str_new);
////alert(str1);
//
//var parameters="str="+str_new;
//parameters += "&str1="+str1;
//parameters += "&dt="+dt_new;
//parameters += "&hdt="+hdt_new;
//
////document.getElementById("proc").innerHTML="<img src='saving.gif'/>";
//http.open("POST", url, true);
//
////Send the proper header information along with the request
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
//                 //$("#submit").click();
//                 fsubmit();
//                  }
//                  //document.getElementById("caseval"+cn).value=str_caseval;
//                  //document.getElementById("cr_span"+cn).innerHTML=cr_head;
//	}
//}
//http.send(parameters);
//
//close_w(cnt);
//}
//}

function call_div(cn,e,cnt)
{
    if(cnt==1)
    {  var div1="chkp"; var div2="hdremp"; $('#tmp_casenop').val(cn); $('#pend_head').html('<font color=red>' + $('#cs'+cn).html() + '</font>'); }
    else
    { var div1="chkd"; var div2="hdremd"; $('#tmp_casenod').val(cn); $('#disp_head').html('<font color=red>' + $('#cs'+cn).html() + '</font>'); }
    var csval=document.getElementById("caseval"+cn).value;
    var csvalspl=csval.split("^^");
    var t_val;
    var chk_val;
    $("input[type='checkbox'][name^='"+div1+"']").each(function(){
        chk_val=$(this).val().split("|");
        int_chk=0;
        for(i=0; i<(csvalspl.length-1);i++)
        {
            t_val=csvalspl[i].split("|");
            if(t_val[0]==chk_val[0])
            {
                document.getElementById(div1+chk_val[0]).checked=true;
                $("#"+div2+chk_val[0]).val(t_val[1]);
                $("#"+div2+chk_val[0]).attr('readonly', false);
                $("#"+div2+chk_val[0]).css('background-color', '#FFF');
                $("#"+div2+chk_val[0]).css('border', '1px solid #ccc');
                int_chk=1;
            }
        }
        if(int_chk==0)
        {
            document.getElementById(div1+chk_val[0]).checked=false;
            $("#"+div2+chk_val[0]).val('');
            $("#"+div2+chk_val[0]).attr('readonly', true);
            $("#"+div2+chk_val[0]).css('background-color', '#F5F5F5');
            $("#"+div2+chk_val[0]).css('border', '1px solid #ccc');
        }
    });
    call_f1(cnt);
}

function close_w(cnt)
{
    var divname="";
    if(cnt==1)
        divname="newb";
    if(cnt==2)
        divname="newc";
    if(cnt==3)
        divname="newp";
    if(cnt==4)
        divname="newadv";
    document.getElementById(divname).style.display='none';
}
function adv_popup(opt)
{
    $('#adv_head').html('<font color=red>' + $('#advname'+opt).html() + '</font>');
    $('#txt_adv_name').val($('#advname'+opt).html());
    if(opt==1)
        $('#advside').val('P');
    if(opt==2)
        $('#advside').val('R');

    if ($('#advenroll'+opt).length)
    {
        $('#adv_head1').html('<font color=red>' + $('#advenroll'+opt).html() + '</font>');
        var en=$('#advenroll'+opt).html();
        if(en.indexOf("/")>=0)
        {
            var en1=en.split("/");
            $('#txt_e_no').val(en1[0]);
            $('#txt_e_yr').val(en1[1]);
        }
        else
        {
            $('#txt_e_no').val(en);
            $('#txt_e_yr').val('');
        }


    }
    else
    {
        $('#adv_head1').html('');
        $('#txt_e_no').val('');
        $('#txt_e_yr').val('');
    }



    call_f1(4);
}
$(document).on('click', '#all', function(){
    $("input[type='checkbox'][name^='ccchk']").each(function(){
        chk_val=$(this).val();
        if(document.getElementById("all").checked){
            if(!($(this).is(':disabled'))){
                $(this).prop('checked',true);
            }}
        else
            $(this).prop('checked',false);
    });
});

function call_f1(cnt)
{

    var divname="";
    if(cnt==1)
    {
        divname="newb";
        $('#'+divname).width('800px');
        $('#'+divname).height('600px');
        //$('#'+divname).width( $(window).width()-150);
        // $('#'+divname).height( $(window).height()-120);
        // $('#newb123').height($('#newb').height()-$('#newb1').height()-50);
        $('#newb123').height($('#newb').height()-50);
        $('#ccdiv').height($('#newb').height()-120);
    }
    if(cnt==2)
    {
        divname="newc";
        $('#'+divname).width( $(window).width()-150);
        $('#'+divname).height( $(window).height()-120);
        $('#newc123').height($('#newc').height()-$('#newc1').height()-50);
    }
    if(cnt==3)
    {
        divname="newp";
        $('#'+divname).width( $(window).width()-150);
        $('#'+divname).height( $(window).height()-120);
        $('#newp123').height($('#newp').height()-$('#newp1').height()-50);
    }

    if(cnt==4)
    {
        divname="newadv";
        $('#'+divname).width('600px');
        $('#'+divname).height( $(window).height()-150);
        $('#newadv123').height($('#newadv').height()-$('#newadv1').height()-50);
    }

    var newX = ($('#'+divname).width()/2);
    var newY = ($('#'+divname).height()/2);
    document.getElementById(divname).style.marginLeft = "-" + newX + "px";
    document.getElementById(divname).style.marginTop = "-" + newY + "px";
    document.getElementById(divname).style.display='block';
    document.getElementById(divname).style.zIndex  = 10;

}

function set_ele() {
    $("#hdremp21").datepicker({ dateFormat: "dd/mm/yy", numberOfMonths: 2 });
    $("#hdremp24").datepicker({ dateFormat: "dd/mm/yy", numberOfMonths: 2 });
    $("#hdremp59").datepicker({ dateFormat: "dd/mm/yy", numberOfMonths: 2 });
    $("#hdremp70").datepicker({ dateFormat: "dd/mm/yy", numberOfMonths: 2 });
    $("#hdremp21").keypress(function (e){ e.preventDefault(); });
    $("#hdremp24").keypress(function (e){ e.preventDefault(); });
    $("#hdremp59").keypress(function (e){ e.preventDefault(); });
    $("#hdremp70").keypress(function (e){ e.preventDefault(); });
    get_subheading();

    $("#txt_adv_name").autocomplete({
        source:'get_adv_from_bar.php',
        minLength:2,
        select:function(evt, ui)
        {
            $("#txt_e_no").val(ui.item.eno);
            $("#txt_e_yr").val(ui.item.eyr);
        }
    });
    $(".cls_chkp").click(function(){
        var chk_val=$(this).val().split("|");
        var isChecked = document.getElementById($(this).attr('id')).checked;
        if(isChecked)
        {
            if(chk_val[0]!==21 && chk_val[0]!==24 && chk_val[0]!==59 && chk_val[0]!==70)
                $("#hdremp"+chk_val[0]).attr('readonly', false);
            $("#hdremp"+chk_val[0]).css('background-color', '#fff');
            $("#hdremp"+chk_val[0]).css('border', '1px solid #ccc');
            $("#hdremp"+chk_val[0]).focus();
        }
        else
        {
            $("#hdremp"+chk_val[0]).attr('readonly', true);
            $("#hdremp"+chk_val[0]).css('background-color', '#F5F5F5');
            $("#hdremp"+chk_val[0]).css('border', '1px solid #ccc');
        }
    });

    $(".cls_chkd").click(function(){
        var chk_val=$(this).val().split("|");
        var isChecked = document.getElementById($(this).attr('id')).checked;
        if(isChecked)
        {
            $("#hdremd"+chk_val[0]).attr('readonly', false);
            $("#hdremd"+chk_val[0]).css('background-color', '#fff');
            $("#hdremd"+chk_val[0]).css('border', '1px solid #ccc');
            $("#hdremd"+chk_val[0]).focus();
        }
        else
        {
            $("#hdremd"+chk_val[0]).attr('readonly', true);
            $("#hdremd"+chk_val[0]).css('background-color', '#F5F5F5');
            $("#hdremd"+chk_val[0]).css('border', '1px solid #ccc');
        }
    });
    $('#linkimg').click(function() {
        if($('#linkimg').html()=='SHOW ALL LISTINGS')
        {
            $('#linkimg').html('HIDE PREVIOUS LISTINGS');
            $('.shclass').show();
        }
        else
        {
            $('#linkimg').html('SHOW ALL LISTINGS');
            $('.shclass').hide();
        }

//if($('.tbl_hr').hasClass('shclass'))

    });

    //setTimeout(function() {change_judge();    }, 1000);
    $('.shclass').hide();
}

function chk_conncase()
{
    var conncs_var=document.frm.conncs.value;
    if(conncs_var=='Y')
        document.getElementById("conncasediv").style.display="block";
    else
        document.getElementById("conncasediv").style.display="none";
}


function get_subheading()
{
    var xhr2=getXMLHTTP();
    var jj=0;
    var sh=$('#sh_hidden').val();
    jj=$('#mf_select').val();
    var str="get_mf_subhead.php?mf="+jj+"&sh="+sh;
    xhr2.open("GET",str,true);
    xhr2.onreadystatechange=function()
    {
        if(xhr2.readyState==4  && xhr2.status==200)
        {	var data=xhr2.responseText;
            var arr=data.split("|");
            if(data=="ERROR")
            {
                //document.getElementById('subhead_select').innerHTML=xhr2.responseText;
            }
            else
            {
                //document.getElementById('mf_box').innerHTML=arr[0];
                document.getElementById('subhead_select').innerHTML=data;
            }
        }
    }// inner function end
    xhr2.send(null);
}

function change_judge()
{
    if($("#bench").val()=="S")
    {
        document.getElementById("judge_sb").style.display="block";
        if($("#subhead_select").val()=="850")
            $("#sbj").val("514");
        else
            $("#sbj").val("250");
    }
    else
        document.getElementById("judge_sb").style.display="none";

    if($("#bench").val()=="D")
    {
        //     alert($("#subhead_select").val());
        document.getElementById("judge_db").style.display="block";
        if($("#subhead_select").val()=="850")
            $("#dbj1").val("514");
        else
            $("#dbj1").val("200");
    }
    else
        document.getElementById("judge_db").style.display="none";

}

function fsubmit()
{   
    document.getElementById("dv_res1").innerHTML = '';
    var diaryno, diaryyear, cstype, csno, csyr;
    var regNum = new RegExp('^[0-9]+$');
    var radio = $("input[type='radio'][name='search_type']:checked").val();
    var CSRF_TOKEN = 'CSRF_TOKEN';
    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
    if (radio == 'C') {
        cstype = $("#case_type").val();
        csno = $("#case_number").val();
        csyr = $("#case_year").val();

        if(!regNum.test(cstype)){
            alert("Please Select Casetype");
            $("#case_type").focus();
            return false;
        }
        if(!regNum.test(csno)){
            alert("Please Fill Case No in Numeric");
            $("#case_number").focus();
            return false;
        }
        if(!regNum.test(csyr)){
            alert("Please Fill Case Year in Numeric");
            $("#case_year").focus();
            return false;
        }
        if(csno == 0){
            alert("Case No Can't be Zero");
            $("#case_number").focus();
            return false;
        }
        if(csyr == 0){
            alert("Case Year Can't be Zero");
            $("#case_year").focus();
            return false;
        }
        /*if(cstype.length==1)
            cstype = '00'+cstype;
        else if(cstype.length==2)
            cstype = '0'+cstype;*/
    } else if (radio == 'D') {
        diaryno = $("#diary_number").val();
        diaryyear = $("#diary_year").val();
        if(!regNum.test(diaryno)){
            alert("Please Enter Diary No in Numeric");
            $("#diary_number").focus();
            return false;
        }
        if(!regNum.test(diaryyear)){
            alert("Please Enter Diary Year in Numeric");
            $("#diary_year").focus();
            return false;
        }
        if(diaryno == 0){
            alert("Diary No Can't be Zero");
            $("#diary_number").focus();
            return false;
        }
        if(diaryyear == 0){
            alert("Diary Year Can't be Zero");
            $("#diary_year").focus();
            return false;
        }

    }
    else{
        alert('Please Select Any Option');
        return false;
    }

    $.ajax({
        type: 'POST',
        url: base_url+"/ARDRBM/IA/prevRegularRegistrationProcess",
        beforeSend: function (xhr) {
            //$("#dv_res1").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='../images/load.gif'></div>");
            $('#search').prop('disabled',true);            
            $('#dv_res1').html('<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>');
        },
        data:{d_no:diaryno,d_yr:diaryyear,ct:cstype,cn:csno,cy:csyr,tab:'Case Details',CSRF_TOKEN: CSRF_TOKEN_VALUE}
    })
        .done(function(msg){
            updateCSRFToken();
            $("#dv_res1").html(msg);
            $('#search').prop('disabled',false);
        })
        .fail(function(){
            updateCSRFToken();
            alert("ERROR, Please Contact Server Room");
            $('#search').prop('disabled',false);
        });
//
//    document.getElementById("dv_res1").innerHTML = '';
//    var dno = document.getElementById("dno").value;
//    var dyr = document.getElementById("dyr").value;
////    var year = document.getElementById("year").value;
//    document.getElementById("dv_res1").innerHTML = "<table align=center><tr><td><img src='../images/load.gif'></td></tr></table>";
//        var ajaxRequest; // The variable that makes Ajax possible!
//        try {
//            // Opera 8.0+, Firefox, Safari
//            ajaxRequest = new XMLHttpRequest();
//        } catch (e)
//        {
//            // Internet Explorer Browsers
//            try {
//                ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
//            } catch (e)
//            {
//
//                try {
//                    ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
//                } catch (e)
//                {
//                    // Something went wrong
//                    alert("Your browser broke!");
//                    return false;
//                }
//            }
//        }
//
//        // Create a function that will receive data sent from the server
//        ajaxRequest.onreadystatechange = function()
//        {
//            if (ajaxRequest.readyState == 4) {
//                document.getElementById("dv_res1").innerHTML = '';
//                $("#dv_res1").html(ajaxRequest.responseText);
//                ///////////set_ele();
//                //               document.getElementById("lst_subcat").value ='';
//                //                document.getElementById("cat").value = '';
//            }
//        }
//
//        var url = "regular_registration_process.php";
//        url=url+"?dno="+dno+"&dyr="+dyr;
//       // alert(url);
//        ajaxRequest.open("GET", url, true);
//        ajaxRequest.send(null);
}

function get_case_status()
{
    //document.getElementById("ccdiv").innerHTML = '';
    var dno = document.getElementById("dno").value;
    var dno1 = document.getElementById("dno_add_c").value;
    var dyr = document.getElementById("dyr").value;
    var dyr1 = document.getElementById("dyr_add_c").value;

    if(dno1==dno && dyr==dyr1)
        alert("Add different case no from Main Case");
    else
    {
        document.getElementById("ccdiv").innerHTML = "<table align=center><tr><td><img src='../images/load.gif'></td></tr></table>";
        var ajaxRequest; // The variable that makes Ajax possible!
        try {
            // Opera 8.0+, Firefox, Safari
            ajaxRequest = new XMLHttpRequest();
        } catch (e)
        {
            // Internet Explorer Browsers
            try {
                ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
            } catch (e)
            {

                try {
                    ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
                } catch (e)
                {
                    // Something went wrong
                    alert("Your browser broke!");
                    return false;
                }
            }
        }
        // Create a function that will receive data sent from the server
        ajaxRequest.onreadystatechange = function()
        {
            if (ajaxRequest.readyState == 4) {
                //document.getElementById("hint").innerHTML = '';
                $("#ccdiv").html(ajaxRequest.responseText);
                set_ele();
                //               document.getElementById("lst_subcat").value ='';
                //                document.getElementById("cat").value = '';
            }
        }
        var url = "conn_case_status.php";
        url=url+"?dno="+dno+"&dno1="+dno1+"&dyr="+dyr+"&dyr1="+dyr1;
        // alert(url);
        ajaxRequest.open("GET", url, true);
        ajaxRequest.send(null);
    }
}

function save_rec(opt,cl)
{
    var url = "conn_case_update.php";
    var qte_array = new Array();
    var dno = document.getElementById("dno").value;
    var dyr = document.getElementById("dyr").value;
    var filnoadd=document.getElementById("fil_noadd").value;
    qte_array.push(filnoadd);
    if(opt==1)
    {
        $("input[type='checkbox'][name^='ccchkadd']").each(function(){
            var isChecked = document.getElementById($(this).attr('id')).checked;
            if(isChecked)
            {
                qte_array.push($(this).val());
            }
        });
    }

//    $('input[name="fld[]"]').each(function(){
//       qte_array.push($(this).val());
//    });
    if(qte_array.length== 0)
    {
        alert("NO ELEMENT FOUND");
    }
    else
    {
        if(confirm("Are you sure you want to Add Connected Records?")) {
            $.ajax({
                type : "POST",
                url: url,
                data: {qte:qte_array,dno:dno,dyr:dyr,cl:cl},
                success : function(msg){
                    if(msg=='')
                        alert("DONE");
                    else
                        alert(msg);
                },
                error: function(){
                    alert("ERROR");
                }
            });
            fsubmit();
        }
    }
}
function generate_case(){
    var url = "generate_case_no.php";

    var qte_array = new Array();
    var cn="";
    var ct= $('#selct1').val();
    var dtd=$('#dtd').val();


    var reg_for_year=0;

    // $("input[type='checkbox'][name^='ccchk']").each(function(){
    //     var isChecked = document.getElementById($(this).attr('id')).checked;
    //     if(isChecked)
    //     {
    //         cn+=$('#cn'+$(this).val()).html()+", ";
    //         qte_array.push($(this).val());
    //     }
    // });
        
    $("input[type='checkbox'][name^='ccchk']").each(function(){
        if ($(this).prop('checked')) { // Use .prop('checked') instead of document.getElementById
            cn += $('#cn' + $(this).val()).html() + ", ";
            qte_array.push($(this).val());
        }
    });
    if(qte_array.length== 0)
    {
        alert("SELECT ATLEAST ONE CASE");
        return;
    }

    else if($('#selct1').val()<=0){
        alert("Select Case Type");
        return;
    }
   else if(dtd==''){
        alert("Select Order Date");
        $('#dtd').focus();
        return;
    }
    else if($('#previous_year').val()==''){
        alert('Please Select registration year');
        $('#previous_year').focus();
        return;
    }
    else{
        reg_for_year=$('#previous_year').val();

        var year = dtd.substring(6, 10);

        if(reg_for_year!=year){
            alert("Registration year and Registration order date year should be same");
            $('#previous_year').focus();
            return;
        }
            //alert(dtd);
        compareDate(dtd);
        cn=cn.substr(0,cn.length-2);

        if(confirm("Are you sure you want to generate case no. \n"+cn)) {
            $("#add").prop('disabled',true);
            $.ajax({
                type : "POST",
                url: url,
                data: {qte:qte_array,ct:ct,dtd:dtd,reg_for_year:reg_for_year },
                beforeSend: function () {
                    $('#dv_load').html('<table widht="100%" align="center"><tr><td><img src="../images/load.gif"/></td></tr></table>');
                },
                success : function(msg){
//                if(msg=='')
//                {
                    $("#add").prop('disabled',false);
                    //alert(msg);
                    //fsubmit();
                    $('#dv_res1').html(msg);
//                }
//                else
//               alert(msg);
                },
                error: function(){
                    alert("ERROR");
                }
            });

        }
    }
}
function save_rec_to_main()
{
    var url = "conn_case_update_to_main.php";
    var qte_array = new Array();
    var dno = document.getElementById("dno").value;
    var dyr = document.getElementById("dyr").value;
    var cn="";
    $("input[type='checkbox'][name^='ccchk']").each(function(){
        var isChecked = document.getElementById($(this).attr('id')).checked;
        if(isChecked)
        {
            cn+=$('#cn'+$(this).val()).html()+", ";
            qte_array.push($(this).val());
        }
    });
    if(qte_array.length== 0)
    {
        alert("SELECT ATLEAST ONE CONNECTED CASE TO DELINK IT");
    }
    else
    {
        cn=cn.substr(0,cn.length-2);
        if(confirm("Are you sure you want to delink \n"+cn)) {
            $.ajax({
                type : "POST",
                url: url,
                data: {qte:qte_array},
                success : function(msg){
                    if(msg=='')
                    {
                        alert("DONE");fsubmit();
                    }
                    else
                        alert(msg);
                },
                error: function(){
                    alert("ERROR");
                }
            });

        }
    }
}


function save_rec_to_main1()
{
    var url = "conn_case_update_to_main1.php";
    var qte_array = new Array();
    var uid=document.getElementById("hd_ud").value;
    var filno= document.getElementById("fil_no").value;
    var cn="";
    $("input[type='checkbox'][name^='ccchk']").each(function(){
        var isChecked = document.getElementById($(this).attr('id')).checked;
        if(isChecked)
        {
            cn=$('#cn'+$(this).val()).html();
            qte_array.push($(this).val());
        }
    });
    if(qte_array.length== 0)
    {
        alert("SELECT ONE CONNECTED CASE FOR CONVERTING IT INTO MAIN CASE");
    }
    else if(qte_array.length>1)
    {

        alert("SELECT ONLY ONE CONNECTED CASE FOR CONVERTING IT INTO MAIN CASE");
    }
    else
    {
        if(confirm("Are you sure you want to make "+cn+" into main case?")) {
            $.ajax({
                type : "POST",
                url: url,
                data: {qte:qte_array,uid:uid,filno:filno},
                success : function(msg){
                    if(msg=='')
                    {
                        alert("DONE");fsubmit();
                    }
                    else
                        alert(msg);
                },
                error: function(){
                    alert("ERROR");
                }
            });

        }
    }
}

function delink_main(ttl,ttlp,ttld)
{
    var url = "conn_case_delink_main.php";
    var dno = document.getElementById("dno").value;
    var dyr = document.getElementById("dyr").value;
    if(confirm("Are you sure you want to DELINK main case?")) {
        $.ajax({
            type : "POST",
            url: url,
            data: {dno:dno,dyr:dyr,ttl:ttl,ttlp:ttlp,ttld:ttld},
            success : function(msg){
                if(msg=='')
                {
                    alert("DONE");fsubmit();
                }
                else
                    alert(msg);
            },
            error: function(){
                alert("ERROR");
            }
        });

    }

}
$(document).ready(function(){

    $("#search_type_d").click(function(){
        $("#dno").removeProp('disabled');
        $("#dyr").removeProp('disabled');
        $("#selct").prop('disabled',true);
        $("#case_no").prop('disabled',true);
        $("#case_yr").prop('disabled',true);
        $("#selct").val("-1");
        $("#case_no").val("");
        $("#case_yr").val("");
    });

    $("#radioct").click(function(){
        $("#dno").prop('disabled',true);
        $("#dyr").prop('disabled',true);
        $("#dno").val("");
        $("#dyr").val("");
        $("#selct").removeProp('disabled');
        $("#case_no").removeProp('disabled');
        $("#case_yr").removeProp('disabled');
    });


})

function compareDate(txt_order_dt){
    //var disposeDate = document.getElementById('hd_disp_dt').value;
    //alert(disposeDate);
    // alert(txt_order_dt);
    var date = txt_order_dt.substring(0, 2);
    var month = txt_order_dt.substring(3, 5);
    var year = txt_order_dt.substring(6, 10);
//alert ('order date'+txt_order_dt);
//alert('disposed date'+ disposeDate);


    var dateToCompare = new Date(year, month - 1, date);
    // alert(dateToCompare);
    var currentDate = new Date();

    if (dateToCompare > currentDate) {
        alert("Registration Order date cannot be greater than Today's Date ");
        $('#txt_order_dt').focus();
        exit();
    }
    /* if(disposeDate!=''){
         var date = disposeDate.substring(0, 2);
         var month = disposeDate.substring(3, 5);
         var year = disposeDate.substring(6, 10);
         var disposeDate = new Date(year, month - 1, date);
        // var dat=document.getElementById('dtd').value;
       //  alert ('order date'+txt_order_dt);
   //alert('disposed date'+ disposeDate);

        // alert(dat);
        // alert(disposeDate);
         if (dateToCompare > disposeDate) {
             alert("Registration Order date cannot be greater than matter disposal Date ");
             $('#txt_order_dt').focus();
             exit();
         }
     }*/
}