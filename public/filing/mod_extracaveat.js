//Function Autocomplete start pet post------------------------------------------------
$(document).on("focus","#p_post",function(){
$("#p_post").autocomplete({
    source:"../filing/new_filing_autocomp_post.php",
width: 450,
matchContains: true,	
minChars: 1,
selectFirst: false,
autoFocus: true
});    
});
//Function Autocomplete end--------------------------------------------------
            
//Function Autocomplete start pet deptt------------------------------------------------
$(document).on("focus","#p_deptt",function(){
$("#p_deptt").autocomplete({
    source:"../filing/new_filing_autocomp_deptt.php?type="+$("#party_type").val(),
    width: 450,
    matchContains: true,	
    minChars: 1,
    selectFirst: false,
    autoFocus: true
});    
});

$(document).on("focus","#p_statename",function(){
$("#p_statename").autocomplete({
    source:base_url+"/Filing/Caveat/get_only_state_name",
width: 450,
matchContains: true,	
minChars: 1,
selectFirst: false,
autoFocus: true
});    
});
//Function Autocomplete end--------------------------------------------------

$(document).on("focus","#p_occ",function(){
$("#p_occ").autocomplete({
    source:"../addentry/get_occ.php",
width: 450,
matchContains: true,	
minChars: 1,
selectFirst: false,
autoFocus: true,
select: function (event, ui) {
    //alert(ui.item.label+'***'+ui.item.value);
    //var htht = ui.item.value.split('~');
    //alert(htht[0]);
}
});    
});

$(document).on("focus","#p_edu",function(){
$("#p_edu").autocomplete({
    source:"../addentry/get_edu.php",
width: 450,
matchContains: true,	
minChars: 1,
selectFirst: false,
autoFocus: true,
select: function (event, ui) {
    //alert(ui.item.label+'***'+ui.item.value);
    //var htht = ui.item.value.split('~');
    //alert(htht[0]);
}
});    
});

$(document).on("blur","#p_statename",function(){
    if(this.value.indexOf('~') != '-1'){
        var htht = this.value.split('~');
        $("#p_statename").val(htht[1]);
        $("#p_statename_hd").val(htht[0]);
    }
});

$(document).on("blur","#p_post",function(){
    if(this.value.indexOf('~') != '-1'){
        var htht = this.value.split('~');
        $("#p_post").val(htht[1]);
        $("#post_code").val(htht[0]);
    }
});

$(document).on("blur","#p_deptt",function(){
    if(this.value.indexOf('~') != '-1'){
        var htht = this.value.split('~');
        $("#p_deptt").val(htht[1]);
        $("#p_deptt_hd").val(htht[0]);
    }
});

$(document).on("blur","#p_occ",function(){
    if(this.value.indexOf('~') != '-1'){
        var htht = this.value.split('~');
        $("#p_occ").val(htht[1]);
        $("#p_occ_hd_code").val(htht[0]);
    }
});

$(document).on("blur","#p_edu",function(){
    if(this.value.indexOf('~') != '-1'){
        var htht = this.value.split('~');
        $("#p_edu").val(htht[1]);
        $("#p_edu_hd_code").val(htht[0]);
    }
});


function fillup(id)
{
    var val = document.getElementById(id).value;
    if(val.length==1)
        val = '0000'+val;
    else if(val.length==2)
        val = '000'+val;
    else if(val.length==3)
        val = '00'+val;
    else if(val.length==4)
        val = '0'+val;
    document.getElementById(id).value=val;
}

$(document).ready(function(){
    
    $("input[name=btnGetRMod]").click(function(){
        var diaryno, diaryyear;
		 
        var regNum = new RegExp('^[0-9]+$');
        diaryno = $("#dno").val();
        diaryyear = $("#dyr").val();
        if(!regNum.test(diaryno)){
            alert("Please Enter Caveat No in Numeric");
            $("#dno").focus();
            return false;
        }
        if(!regNum.test(diaryyear)){
            alert("Please Enter Caveat Year in Numeric");
            $("#dyr").focus();
            return false;
        }
        if(diaryno == 0){
            alert("Caveat No Can't be Zero");
            $("#dno").focus();
            return false;
        }
        if(diaryyear == 0){
            alert("Caveat Year Can't be Zero");
            $("#dyr").focus();
            return false;
        }
       var CSRF_TOKEN = 'CSRF_TOKEN';
 var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        $.ajax({
            type: 'POST',
            url: base_url+"/Filing/Caveat/get_extracaveat_mod",
            beforeSend: function (xhr) {
                $("#result1").html('<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>');
            },
            data:{dno:diaryno,dyr:diaryyear,CSRF_TOKEN: CSRF_TOKEN_VALUE}
        })
        .done(function(msg){
			updateCSRFToken();
            $("#result1").html(msg);
            $("#result2").html("");
        })
        .fail(function(){
            alert("ERROR, Please Contact Server Room"); 
        });
    });
});

$(document).on("change","#p_rel",function(){
    if(this.value == 'S' || this.value == 'F')
        $("#p_sex").val("M");
    else if(this.value == 'D' || this.value == 'W' || this.value == 'M')
        $("#p_sex").val("F");
    else if(this.value == '')
        $("#p_sex").val("");
});

$(document).on('change','#p_cont',function(){
    if(this.value != "96"){
        $("#p_st").prop("disabled",true);
        $("#p_st").val("");
        $("#p_dis").prop("disabled",true);
        $("#p_dis").val("");
    }
    else{
        $("#p_st").removeProp("disabled");
        $("#p_dis").removeProp("disabled");
    }
});

$(document).on("click","[name^='ExMod_']",function(){
    //alert('sd');
    var num8 = this.name.split('ExMod_');
    //alert(num[1]);
    var num = num8[1].split('_');
	var CSRF_TOKEN = 'CSRF_TOKEN';
	var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

    $.ajax({
        type: 'POST',
        url: base_url+"/Filing/Caveat/get_extracaveat_info",
        beforeSend: function (xhr) {
           // $("#result1").html('<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>');
        },
        data:{fno:$("#hdfno").val(),id:num[1],flag:num[0],type:num[2],CSRF_TOKEN: CSRF_TOKEN_VALUE}
    })
    .done(function(msg){
		updateCSRFToken();

        //alert(msg);
        //$("#result1").html(msg);
        msg = msg.split('~');
        //alert(msg[22]);
        if(msg[22]==0)
            msg[22]='';
        if(num[2]=='I')
        {
            activate_extra(num[2]);
            document.getElementById('party_type').value=num[2];
            if(num[0]=='P')
                document.getElementById('party_flag').innerHTML="<option value='P' selected>Caveator</option><option value='R'>Caveatee</option>";
            else if(num[0]=='R')
                document.getElementById('party_flag').innerHTML="<option value='P'>Caveator</option><option value='R' selected>Caveatee</option>";
            /*else if(num[0]=='I')
                document.getElementById('party_flag').innerHTML="<option value='P'>Petitioner</option><option value='R'>Respondent</option><option value='I' selected>Impleading</option>";*/

            document.getElementById('hd_party_flag').value=num[0];
            document.getElementById('pno').innerHTML=num[1];
            document.getElementById('p_name').value=msg[0];
            document.getElementById('p_rel').value=msg[1];
            document.getElementById('p_rel_name').value=msg[3];
            document.getElementById('p_sex').value=msg[5];
            document.getElementById('p_age').value=msg[4];
            document.getElementById('p_caste').value=msg[6];
            document.getElementById('p_occ').value=msg[7];
            document.getElementById('p_occ_hd_code').value=msg[18];
            document.getElementById('p_edu').value=msg[17];
            document.getElementById('p_edu_hd_code').value=msg[19];
            document.getElementById('p_add').value=msg[8];
            document.getElementById('p_city').value=msg[14];
            document.getElementById('p_cont').value=msg[15];
            document.getElementById('p_st').value=msg[9];
            getDistrict(msg[9]);
            document.getElementById('p_dis').value=msg[10];
            document.getElementById('p_pin').value=msg[11];
            document.getElementById('p_mob').value=msg[13];
            document.getElementById('p_email').value=msg[12];
            //document.getElementById('lower_case').value=msg[22].trim();
            document.getElementById('svbtn').disabled=false;
            document.getElementById('rstbtn').disabled=false;
            if(msg[15] != "96"){
                $("#p_st").prop("disabled",true);
                $("#p_st").val("");
                $("#p_dis").prop("disabled",true);
                $("#p_dis").val("");
            }
            else{
                $("#p_st").removeProp("disabled");
                $("#p_dis").removeProp("disabled");
            }
        }
        else
        {
            activate_extra(num[2]);
            document.getElementById('party_type').value=num[2];
            if(num[0]=='P')
                document.getElementById('party_flag').innerHTML="<option value='P' selected>Petitioner</option><option value='R'>Respondent</option><option value='I'>Impleading</option>";
            else if(num[0]=='R')
                document.getElementById('party_flag').innerHTML="<option value='P'>Petitioner</option><option value='R' selected>Respondent</option><option value='I'>Impleading</option>";
            else if(num[0]=='I')
                document.getElementById('party_flag').innerHTML="<option value='P'>Petitioner</option><option value='R'>Respondent</option><option value='I' selected>Impleading</option>";

            document.getElementById('hd_party_flag').value=num[0];
            document.getElementById('pno').innerHTML=num[1];
            
            //--to edit
            document.getElementById('p_post').value=msg[21];
            document.getElementById('post_code').value=msg[2];
            document.getElementById('p_deptt').value=msg[20];
            document.getElementById('p_deptt_hd').value=msg[16];
            
            document.getElementById('p_statename').value=msg[24];
            document.getElementById('p_statename_hd').value=msg[23];
            
            
            document.getElementById('p_add').value=msg[8];
            document.getElementById('p_city').value=msg[14];
            document.getElementById('p_cont').value=msg[15];
            document.getElementById('p_st').value=msg[9];
            getDistrict(msg[9]);
            document.getElementById('p_dis').value=msg[10];
            document.getElementById('p_pin').value=msg[11];
            document.getElementById('p_mob').value=msg[13];
            document.getElementById('p_email').value=msg[12];
            //document.getElementById('lower_case').value=msg[22].trim();
            document.getElementById('svbtn').disabled=false;
            document.getElementById('rstbtn').disabled=false;
            if(msg[15] != "96"){
                $("#p_st").prop("disabled",true);
                $("#p_st").val("");
                $("#p_dis").prop("disabled",true);
                $("#p_dis").val("");
            }
            else{
                $("#p_st").removeProp("disabled");
                $("#p_dis").removeProp("disabled");
            }
            /*if(type=='D1')
            {
                if(msg[11]!=0)
                    document.getElementById('state_department_in').value=msg[11]+'->'+msg[12];
            }*/
        }
    })
    .fail(function(){
		updateCSRFToken();
        alert("ERROR, Please Contact Server Room"); 
    });
});


function call_getDetails_extra()
{
    //alert('sdf');
}

function setPartiesinField(id,flag,type)
{
    //alert("id= "+id+" flag= "+flag+" type="+type);
    var xmlhttp;
    if (window.XMLHttpRequest)
    {
        xmlhttp=new XMLHttpRequest();
    }
    else
    {
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    
    xmlhttp.onreadystatechange=function()
    {
        if(xmlhttp.readyState==4 && xmlhttp.status==200)
        {
            var hello = xmlhttp.responseText;
            hello = hello.split('~');
            if(type=='I')
            {
                activate_extra(type);
                document.getElementById('party_type').value=type;
                if(flag=='P')
                    document.getElementById('party_flag').innerHTML="<option value='P' selected>Petitioner</option><option value='R'>Respondent</option>";
                else if(flag=='R')
                    document.getElementById('party_flag').innerHTML="<option value='P'>Petitioner</option><option value='R' selected>Respondent</option>";
                
                document.getElementById('hd_party_flag').value=flag;
                document.getElementById('pno').innerHTML=id;
                document.getElementById('p_name').value=hello[0];
                document.getElementById('p_rel').value=hello[12];
                document.getElementById('p_rel_name').value=hello[1];
                document.getElementById('p_sex').value=hello[3];
                document.getElementById('p_age').value=hello[2];
                document.getElementById('p_occ').value=hello[4];
                document.getElementById('p_add').value=hello[5];
                document.getElementById('p_city').value=hello[6];
                document.getElementById('p_pin').value=hello[9];
                document.getElementById('p_st').value=hello[7];
                getDistrict(hello[7]);
                document.getElementById('p_dis').value=hello[8];
                document.getElementById('p_mob').value=hello[11];
                document.getElementById('p_email').value=hello[10];
                document.getElementById('svbtn').disabled=false;
                document.getElementById('rstbtn').disabled=false;
            }
            else
            {
                activate_extra(type);
                document.getElementById('party_type').value=type;
                if(flag=='P')
                    document.getElementById('party_flag').innerHTML="<option value='P' selected>Petitioner</option><option value='R'>Respondent</option>";
                else if(flag=='R')
                    document.getElementById('party_flag').innerHTML="<option value='P'>Petitioner</option><option value='R' selected>Respondent</option>";
                
                document.getElementById('hd_party_flag').value=flag;
                document.getElementById('pno').innerHTML=id;
                document.getElementById('p_deptt').value=hello[0];
                document.getElementById('p_post').value=hello[1];
                document.getElementById('p_add').value=hello[3];
                document.getElementById('p_city').value=hello[4];
                document.getElementById('p_pin').value=hello[7];
                document.getElementById('p_st').value=hello[5];
                getDistrict(hello[5]);
                document.getElementById('p_dis').value=hello[6];
                document.getElementById('p_mob').value=hello[9];
                document.getElementById('p_email').value=hello[8];
                document.getElementById('post_code').value=hello[10];
                document.getElementById('svbtn').disabled=false;
                document.getElementById('rstbtn').disabled=false;
                /*if(type=='D1')
                {
                    if(hello[11]!=0)
                        document.getElementById('state_department_in').value=hello[11]+'->'+hello[12];
                }*/
            }
        }
    }
    var url = "get_extraparty_info.php?fno="+document.getElementById('hdfno').value+"&id="+id+"&flag="+flag+"&type="+type;
    //alert(url);
    xmlhttp.open("GET",url,false);
    xmlhttp.send(null);
}
            
            
function activate_extra(value)
{
    if(value=="I")
    {
        document.getElementById('p_post').value="";
        document.getElementById('p_deptt').value="";
        document.getElementById('p_statename').value="";
        document.getElementById('for_I_1').style.display='table-row';
        document.getElementById('for_I_2').style.display='table-row';
        document.getElementById('for_I_3').style.display='table-row';
        document.getElementById('for_I_4').style.display='table-row';
        document.getElementById('tr_d').style.display='none';
        document.getElementById('tr_d0').style.display='none';
        //document.getElementById('tr_d1').style.display='none';
        //document.getElementById('state_department_in').value='';
    }
    else if(value!="I")
    {
        document.getElementById('p_name').value="";
        document.getElementById('p_rel').value="";
        document.getElementById('p_rel_name').value="";
        document.getElementById('p_sex').value="";
        document.getElementById('p_age').value="";
        document.getElementById('p_occ').value="";
        document.getElementById('p_caste').value="";
        document.getElementById('p_edu').value="";
        document.getElementById('for_I_1').style.display='none';
        document.getElementById('for_I_2').style.display='none';
        document.getElementById('for_I_3').style.display='none';
        document.getElementById('for_I_4').style.display='none';
        document.getElementById('tr_d').style.display='table-row';
        if(value=='D3')
            document.getElementById('tr_d0').style.display='none';
        else
            document.getElementById('tr_d0').style.display='table-row';
        //document.getElementById('tr_d0').style.display='table-row';
        /*if(value=='D1')
            document.getElementById('tr_d1').style.display='table-row';
        else
        {
            document.getElementById('tr_d1').style.display='none';
            document.getElementById('state_department_in').value='';
        }*/
    }
}

function get_a_d_code(id)//get_authcode_extra
{
    var id2 = id.split("_");
    var xmlhttp;
    if (window.XMLHttpRequest)
    {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
    }
    else
    {// code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    //document.getElementById('container').innerHTML = '<table widht="100%" align="center"><tr><td style=color:red><blink>Please Wait<blink></td></tr></table>';
    xmlhttp.onreadystatechange=function()
    {
        if(xmlhttp.readyState==4 && xmlhttp.status==200)
        {
            document.getElementById(id2[1]+"_code").value=xmlhttp.responseText;
        }
    }
    var url = "new_filing_autocomp_"+id2[1]+".php?falagofpost=code&val="+document.getElementById(id).value;
    xmlhttp.open("GET",url,false);
    xmlhttp.send(null); 
}

function onlynumbers(evt) 
{
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    //alert(charCode);
    if ((charCode >= 48 && charCode<= 57)||charCode==9||charCode==8) {
        return true;
    }
    return false;
}

function onlyalpha(evt) 
{
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    //alert(charCode);
    if ((charCode >= 65 && charCode<= 90)||(charCode >= 97 && charCode<= 122)||charCode==9||charCode==8||
            charCode==127||charCode==32||charCode==46||charCode==47||charCode==64) {
        return true;
    }
    return false;
}


function onlyalphab(evt) 
{
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    //alert(charCode);
    if ((charCode >= 65 && charCode<= 90)||(charCode >= 97 && charCode<= 122)||(charCode >= 48 && charCode<= 57)
            ||charCode==9||charCode==8||charCode==127||charCode==32||charCode==46||charCode==47||charCode==64||
            charCode==40||charCode==41||charCode==37||charCode==44) {
        return true;
    }
    return false;
}

function stopinvertedcomma(evt)
{
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    //alert(charCode);
    if (charCode == 39 || charCode == 38) {
        return false;
    }
    return true;
}


function call_save_extra()
{
    var party_type = document.getElementById('party_type').value;
    var party_flag = document.getElementById('party_flag');
    
    var p_name,p_rel,p_rel_name,p_sex,p_age,p_post,p_deptt,p_occ,p_edu;
    
    if(party_flag.value=="")
    {
        alert('Please Select Party Type');party_flag.focus();return false;
    }
    if(party_type=="I")
    {
        p_name = document.getElementById('p_name');
        p_rel = document.getElementById('p_rel');
        p_rel_name = document.getElementById('p_rel_name');
        p_sex = document.getElementById('p_sex');
        p_age = document.getElementById('p_age');
        p_occ = document.getElementById('p_occ');
        p_edu = document.getElementById('p_edu');
        
        if(p_name.value=='')
        {
            alert('Please Enter Party Name');p_name.focus();return false;
        }
        /*if(p_rel.value=='')
        {
            alert('Please Select Party Relation');p_rel.focus();return false;
        }
        if(p_rel_name.value=='')
        {
            alert('Please Enter Party Father/Husband Name');p_rel_name.focus();return false;
        }
        if(p_sex.value=='')
        {
            alert('Please Select Party Gender');p_sex.focus();return false;
        }*/
//        if(p_age.value=='')
//        {
//            alert('Please Enter Party Age');p_age.focus();return false;
//        }
    }
    if(party_type!="I")
    {
        p_post = document.getElementById('p_post');
        p_deptt = document.getElementById('p_deptt');
        if(p_post.value=='')
        {
            alert('Please Enter Party Post');p_post.focus();return false;
        }
        if(p_deptt.value=='')
        {
            alert('Please Enter Party Department');p_deptt.focus();return false;
        }
    }
    if(document.getElementById('p_add').value=="")
    {
        alert('Please Enter Party Address');document.getElementById('p_add').focus();return false;
    }
    if(document.getElementById('p_city').value=="")
    {
        alert('Please Enter Party City');document.getElementById('p_city').focus();return false;
    }
    if(document.getElementById('p_st').value=="")
    {
        if($("#p_cont").val()=='96'){
            alert('Please Enter Party State');document.getElementById('p_st').focus();return false;
        }
    }
    if(document.getElementById('p_dis').value=="")
    {
        if($("#p_cont").val()=='96'){
            alert('Please Enter Party District');document.getElementById('p_dis').focus();return false;
        }
    }
    if(document.getElementById('p_cont').value=="")
    {
        alert('Please Enter Party Country');document.getElementById('p_cont').focus();return false;
    }
    if(document.getElementById('p_email').value!='')
    {
        var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
        if(document.getElementById('p_email').value.match(mailformat))
        {
            //return true;
        }
        else
        {
            alert('Please enter valid email');
            document.getElementById('p_email').focus();
            return false;
        }
    }
    
    var xmlhttp;
    if (window.XMLHttpRequest)
    {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
    }
    else
    {// code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    //document.getElementById('container').innerHTML = '<table widht="100%" align="center"><tr><td style=color:red><blink>Please Wait<blink></td></tr></table>';
    xmlhttp.onreadystatechange=function()
    {
        if(xmlhttp.readyState==4 && xmlhttp.status==200)
        {
            var res = xmlhttp.responseText;
            res = res.split('!~!');
            document.getElementById('result2').innerHTML=res[1];
            //document.getElementById('table_show').innerHTML="Please Wait While We Are Fatching Parties Information Again";
            call_fullReset_extra();
            call_fetch_infoAgain(document.getElementById('hdfno').value);
        }
    }
    var url = base_url+"/Filing/Caveat/save_new_caveat_extraparty?controller=U&fno="+document.getElementById('hdfno').value
            +"&p_f="+party_flag.value+"&hd_p_f="+document.getElementById('hd_party_flag').value;
    
    if(party_type=="I")
        url = url+"&p_type="+party_type+"&p_name="+p_name.value+"&p_rel="+p_rel.value+"&p_rel_name="+p_rel_name.value
    +"&p_sex="+p_sex.value+"&p_age="+p_age.value+"&p_occ="+document.getElementById('p_occ').value
    +"&p_edu="+document.getElementById('p_edu').value+"&p_caste="+document.getElementById('p_caste').value
    +"&p_occ_code="+document.getElementById('p_occ_hd_code').value+"&p_edu_code="+document.getElementById('p_edu_hd_code').value;
    
    if($("#p_statename").val()=='')
        $("#p_statename_hd").val('0');
    
    if(party_type!="I")
        url = url+"&p_type="+party_type+"&p_post="+p_post.value+"&p_deptt="+p_deptt.value+"&p_statename="+$("#p_statename").val()
        +"&p_statename_hd="+$("#p_statename_hd").val()+"&d_code="+$("#p_deptt_hd").val()+"&p_code="+document.getElementById('post_code').value;
    
    url = url+"&p_add="+document.getElementById('p_add').value+"&p_city="+document.getElementById('p_city').value
    +"&p_pin="+document.getElementById('p_pin').value+"&p_dis="+document.getElementById('p_dis').value
    +"&p_st="+document.getElementById('p_st').value+"&p_cont="+document.getElementById('p_cont').value+"&p_mob="+document.getElementById('p_mob').value
    +"&p_email="+document.getElementById('p_email').value+"&p_no="+document.getElementById('pno').innerHTML
    +"&p_sta="+document.getElementById('p_status').value;//+"&lowercase="+document.getElementById('lower_case').value;
    
    //var state_department_in = $("#state_department_in").val().split("->");
    //url = url +"&d_code="+state_department_in[0];
    //alert(url);
    url = encodeURI(url);
    xmlhttp.open("GET",url,false);
    xmlhttp.send(null); 
}

function call_fetch_infoAgain(value)
{
    var xmlhttp;
    if (window.XMLHttpRequest)
    {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
    }
    else
    {// code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    //document.getElementById('container').innerHTML = '<table widht="100%" align="center"><tr><td style=color:red><blink>Please Wait<blink></td></tr></table>';
    xmlhttp.onreadystatechange=function()
    {
        if(xmlhttp.readyState==4 && xmlhttp.status==200)
        {
            document.getElementById('table_show').innerHTML=xmlhttp.responseText;
        }
    }
    var url = base_url+"/Filing/Caveat/firse_caveatinfo_mod?fno="+value;
    xmlhttp.open("GET",url,false);
    xmlhttp.send(null);
}

function call_fullReset_extra()
{
    if(document.getElementById('party_type').value=='I')
    {
        document.getElementById('p_name').value="";
        document.getElementById('p_rel').value="";
        document.getElementById('p_rel_name').value="";
        document.getElementById('p_sex').value="";
        document.getElementById('p_age').value="";
        document.getElementById('p_caste').value="";
        document.getElementById('p_occ').value="";
        document.getElementById('p_edu').value="";
    }
    else if(document.getElementById('party_type').value!='I')
    {
        document.getElementById('p_post').value="";
        document.getElementById('p_deptt').value="";
    }
    document.getElementById('party_flag').innerHTML="<option value=''>Select</option>";
    document.getElementById('party_type').value="I";
    document.getElementById('p_add').value="";
    document.getElementById('p_city').value="";
    document.getElementById('p_pin').value="";
    document.getElementById('p_dis').innerHTML="<option value=''>Select</option>";
    document.getElementById('p_cont').value="96";
    document.getElementById('p_st').value="";
    document.getElementById('p_mob').value="";
    document.getElementById('p_email').value="";
    document.getElementById('pno').innerHTML="";
    document.getElementById('for_I_1').style.display='table-row';
    document.getElementById('for_I_2').style.display='table-row';
    document.getElementById('for_I_3').style.display='table-row';
    document.getElementById('for_I_4').style.display='table-row';
    document.getElementById('tr_d').style.display='none';
    document.getElementById('tr_d0').style.display='none';
    document.getElementById('p_status').value="P";
    document.getElementById('svbtn').disabled=true;
    document.getElementById('rstbtn').disabled=true;
    //document.getElementById('lower_case').value="";
    //document.getElementById('tr_d1').style.display='none';
    //document.getElementById('state_department_in').value='';
}

function check_for_right_selection(id)
{
    var input_string = $("#"+id).val().split('->');
    if(!isNaN(input_string[0]))
    {
        $("#"+id).focus();
        $("#"+id).get(0).setSelectionRange(0,0);
    }
    else
    {
        alert("Proper Department was Not Selected, the Box will gone Empty");
        $("#"+id).val("");
    }
}

function getDistrict(val){
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
            document.getElementById('p_dis').innerHTML=xmlhttp.responseText;
        }
    }
    xmlhttp.open("GET",base_url+"/Filing/Caveat/get_district?state="+val,false);
    xmlhttp.send(null);
}

   