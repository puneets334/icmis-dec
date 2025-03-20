
  $(document).ready(function(){
    $("input[name=btnGetAdv]").click(function(){
        getDetails();
    });
});  

$(document).on("click","input[name=setPageBtn]",function(){
    setPage();
});

function getDetails()
{   
    var diaryno = $("#dno").val();
    var diaryyear = $("#dyr").val();
    var regNum = new RegExp('^[0-9]+$');
    
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
    $('#fil_hd').val(diaryno+'~'+diaryyear);
    var CSRF_TOKEN = 'CSRF_TOKEN';
 var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

    $.ajax({
        type: 'POST',
        url: base_url+"/Filing/Caveat/cav_adv_fetch_parties_first",
        beforeSend: function (xhr) {
            $("#result1").html('<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>');
        },
        data:{filno:$('#fil_hd').val(),dno:diaryno,dyr:diaryyear,CSRF_TOKEN: CSRF_TOKEN_VALUE}
    })
    .done(function(msg){
		updateCSRFToken();
        $("#result1").html(msg);
        $("#result2").html("");

        var p_adv_total=document.getElementById('p_adv_total').value;
        var r_adv_total=document.getElementById('r_adv_total').value;
        if(p_adv_total!=0 || r_adv_total!=0)
        {
            
            setPage();

        }
    })
    .fail(function(){
        updateCSRFToken();
        alert("ERROR, Please Contact Server Room"); 
    }); 
}

function setPage()
{
    
    var p_ttl = parseInt(document.getElementById('p_adv_total').value);
    var r_ttl = parseInt(document.getElementById('r_adv_total').value);
    var i_ttl = '';
    var n_ttl = '';
    if(document.getElementById('i_adv_total'))
        i_ttl = parseInt(document.getElementById('i_adv_total').value);
    else
        i_ttl = parseInt('0');
    
    if(document.getElementById('n_adv_total'))
        n_ttl = parseInt(document.getElementById('n_adv_total').value);
    else
        n_ttl = parseInt('0');
    //alert(i_ttl);
    
    if(p_ttl==0 && r_ttl==0 && i_ttl==0 && n_ttl==0)
        return false;
    
    if((p_ttl===''||p_ttl>10)||(r_ttl===''||r_ttl>10)||(i_ttl===''||i_ttl>10)||(n_ttl===''||n_ttl>10))
    {
        //alert("("+p_ttl+"==''||"+p_ttl+">10)||("+r_ttl+"==''||"+r_ttl+">10)||("+i_ttl+"==''||"+i_ttl+">10)");
        alert('No. of Advocate(s) could not be blank or greater than 10');
        if(p_ttl==''||p_ttl>10)
            document.getElementById('p_adv_total').focus();
        else if(r_ttl==''||r_ttl>10)
            document.getElementById('r_adv_total').focus();
        
        /*if(document.getElementById('i_adv_total')){
            if(i_ttl==''||i_ttl>10)
                document.getElementById('i_adv_total').focus();
        }*/
        
        return false;
    }
    
    
    var CSRF_TOKEN = 'CSRF_TOKEN';
    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
    
    $.ajax({
        type: 'POST',
        url: base_url+"/Filing/Caveat/cav_adv_fetch_parties",
        beforeSend: function (xhr) {
            $("#result1").html('<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>');
        },
        data:{filno:$("#fil_hd").val(),pt:p_ttl,rt:r_ttl,it:i_ttl,nt:n_ttl, CSRF_TOKEN : CSRF_TOKEN_VALUE}
    })
    .done(function(msg){
        updateCSRFToken();
        $("#result1").html(msg);
        $("#result2").html("");
    })
    .fail(function(){
        updateCSRFToken();
        alert("ERROR, Please Contact Server Room"); 
    });
}

function fillUp(id)
{
    var fno = document.getElementById(id).value;
    if(fno.length=="1")
    {
        fno="0000"+fno;
    }
    else if(fno.length=="2")
    {
        fno="000"+fno; 
    }
    else if(fno.length=="3")
    {
        fno="00"+fno; 
    }
    else if(fno.length=="4")
    {
        fno="0"+fno; 
    }
    document.getElementById(id).value=fno;
}

$(document).on("click","input[name=advocatesaeve]",function(){
    //alert('ye mera india');
    saveAdv();
});

function saveAdv()
{
    var p_no = document.getElementById('p_adv_total').value;
    var r_no = document.getElementById('r_adv_total').value;
    var pet='';
    var res='';
    var imp='';
    var int='';
    for(var i=1;i<p_no;i++)
    {
        var chk = document.getElementById('p_adv_chk'+i);
        if(null != chk && true==chk.checked)
        {   
            if(document.getElementById('p_adv_name_write'+i).style.display=='none'){
                /*if($("#p_adv_state"+i).val()==''){
                    alert('Please fill Advocate State');
                    document.getElementById('p_adv_state'+i).focus();
                    return false;
                }*/
            }
            
            /*if(document.getElementById('p_adv_no'+i).value==''||document.getElementById('p_adv_no'+i).value==0)
            {
                alert('Please fill Advocate Enroll Number');
                document.getElementById('p_adv_no'+i).focus();
                return false;
            }
            else if(document.getElementById('p_adv_yr'+i).value==''||document.getElementById('p_adv_yr'+i).value==0)
            {
                alert('Please fill Advocate Enroll Year');
                document.getElementById('p_adv_yr'+i).focus();
                return false;
            }*/
            if(document.getElementById('p_aor'+i).value=='')
            {
                alert('Please fill Advocate AOR Code');
                document.getElementById('p_aor'+i).focus();
                return false;
            }
            else if(document.getElementById('p_adv_name'+i).innerHTML==''||document.getElementById('p_adv_name'+i).innerHTML==0)
            {
                if(document.getElementById('p_adv_name_write'+i).style.display=='block'){
                    alert('Please Fill Advocate Name');
                    document.getElementById('p_adv_name_write'+i).focus();
                }
                else{
                    alert('Please fetch Proper Advocate Again');
                    //document.getElementById('p_adv_no'+i).focus();
                    document.getElementById('p_aor'+i).focus();
                }
                return false;
            }
            
            /*if(document.getElementById('p_adv_email'+i).value!='')
            {
                var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
                if(document.getElementById('p_adv_email'+i).value.match(mailformat))
                {
                    //return true;
                }
                else
                {
                    alert('Please enter valid email');
                    document.getElementById('p_adv_email'+i).focus();
                    return false;
                }
            }*/
            if(document.getElementById('p_adv_for'+i).value=='')
            {
                alert('Please fill Party No');
                document.getElementById('p_adv_for'+i).focus();
                return false;
            }
            /*if(document.getElementById('p_adv_for'+i).value==0)
            {
                if(document.getElementById('p_adv_type'+i).value=='N')
                {
                    alert('Please fill Party No');
                    document.getElementById('p_adv_for'+i).focus();
                    return false;
                }
            }
            /*pet=pet+document.getElementById('p_adv_no'+i).value+'~'+document.getElementById('p_adv_yr'+i).value+
                '~'+document.getElementById('p_adv_name'+i).innerHTML+'~'+document.getElementById('p_adv_mob'+i).value+
                '~'+document.getElementById('p_adv_email'+i).value+'~'+document.getElementById('p_adv_for'+i).value+
                '~'+document.getElementById('p_adv_type'+i).value+'~'+document.getElementById('p_ifag'+i).value+
                '~'+document.getElementById('p_adv_state'+i).value+'~'+document.getElementById('p_statepg'+i).value+'||';*/
            pet=pet+document.getElementById('p_aor'+i).value+'~'+document.getElementById('p_adv_name'+i).innerHTML+
                '~'+document.getElementById('p_adv_mob'+i).value+'~'+document.getElementById('p_adv_email'+i).value+
                '~'+document.getElementById('p_adv_for'+i).value+'~'+document.getElementById('p_adv_type'+i).value+
                '~'+document.getElementById('p_ifag'+i).value+'~'+document.getElementById('p_statepg'+i).value+'||';
        }
    }
    
    for(var i=1;i<r_no;i++)
    {
        var chk = document.getElementById('r_adv_chk'+i);
        if(null != chk && true==chk.checked)
        {
            if(document.getElementById('r_adv_name_write'+i).style.display=='none'){
                /*if($("#r_adv_state"+i).val()==''){
                    alert('Please fill Advocate State');
                    document.getElementById('r_adv_state'+i).focus();
                    return false;
                }*/
            }
            
            /*if(document.getElementById('r_adv_no'+i).value==''||document.getElementById('r_adv_no'+i).value==0)
            {
                alert('Please fill Advocate Enroll Number');
                document.getElementById('r_adv_no'+i).focus();
                return false;
            }
            else if(document.getElementById('r_adv_yr'+i).value==''||document.getElementById('r_adv_yr'+i).value==0)
            {
                alert('Please fill Advocate Enroll Year');
                document.getElementById('r_adv_yr'+i).focus();
                return false;
            }*/
            if(document.getElementById('r_aor'+i).value=='')
            {
                alert('Please fill Advocate AOR Code');
                document.getElementById('r_aor'+i).focus();
                return false;
            }
            else if(document.getElementById('r_adv_name'+i).innerHTML==''||document.getElementById('r_adv_name'+i).innerHTML==0)
            {
                if(document.getElementById('r_adv_name_write'+i).style.display=='block'){
                    alert('Please Fill Advocate Name');
                    document.getElementById('r_adv_name_write'+i).focus();
                }
                else{
                    alert('Please fetch Proper Advocate Again');
                    document.getElementById('r_aor'+i).focus();
                }
                return false;
            }
            /*if(document.getElementById('r_adv_email'+i).value!='')
            {
                var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
                if(document.getElementById('r_adv_email'+i).value.match(mailformat))
                {
                    //return true;
                }
                else
                {
                    alert('Please enter valid email');
                    document.getElementById('r_adv_email'+i).focus();
                    return false;
                }
            }*/
            if(document.getElementById('r_adv_for'+i).value=='')
            {
                alert('Please fill Party No');
                document.getElementById('r_adv_for'+i).focus();
                return false;
            }
            if(document.getElementById('r_adv_for'+i).value==0)
            {
                if(document.getElementById('r_adv_type'+i).value=='N')
                {
                    alert('Please fill Party No');
                    document.getElementById('r_adv_for'+i).focus();
                    return false;
                }
            }
            /*res=res+document.getElementById('r_adv_no'+i).value+'~'+document.getElementById('r_adv_yr'+i).value+
                '~'+document.getElementById('r_adv_name'+i).innerHTML+'~'+document.getElementById('r_adv_mob'+i).value+
                '~'+document.getElementById('r_adv_email'+i).value+'~'+document.getElementById('r_adv_for'+i).value+
                '~'+document.getElementById('r_adv_type'+i).value+'~'+document.getElementById('r_ifag'+i).value+
                '~'+document.getElementById('r_adv_state'+i).value+'~'+document.getElementById('r_statepg'+i).value+'||';*/
            res=res+document.getElementById('r_aor'+i).value+'~'+document.getElementById('r_adv_name'+i).innerHTML+
                '~'+document.getElementById('r_adv_mob'+i).value+'~'+document.getElementById('r_adv_email'+i).value+
                '~'+document.getElementById('r_adv_for'+i).value+'~'+document.getElementById('r_adv_type'+i).value+
                '~'+document.getElementById('r_ifag'+i).value+'~'+document.getElementById('r_statepg'+i).value+'||';
        }
    }
    
    var CSRF_TOKEN = 'CSRF_TOKEN';
    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
    $.ajax({
        type: 'POST',
        url: base_url+"/Filing/Caveat/cav_save_advnew",
        beforeSend: function (xhr) {
            $("#result2").html('<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>');
        },
        data:{filno:$("#fil_hd").val(),pet:pet,res:res,CSRF_TOKEN: CSRF_TOKEN_VALUE}
    })
    .done(function(msg){
        updateCSRFToken();
        $("#result2").html(msg);
        //$("#result2").html("");
    })
    .fail(function(){
        updateCSRFToken();
        alert("ERROR, Please Contact Server Room"); 
    });
    
    setTimeout(getDetails(),10000);
}

function onlynumbers(evt) 
{
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    //alert(charCode);
    if ((charCode >= 48 && charCode<= 57)||charCode==9||charCode==8||charCode==37||charCode==39) {
    return true;
    }
    return false;
}

function advName(evt)
{
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    //alert(charCode);
    if (charCode==8 ||(charCode >= 65 && charCode<= 90)||(charCode >= 97 && charCode<= 122)
            ||(charCode >= 48 && charCode<= 57)||charCode==127||charCode==32||charCode==9||(charCode >= 37 && charCode<= 41)) {
        return true;
    }
    return false;
}

function partynumbers(evt,id) 
{
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    //alert(charCode);
    if ((charCode >= 48 && charCode<= 57)||charCode==9||charCode==8||charCode==45||
            charCode==44||charCode==37||charCode==39||charCode==3||charCode==99||charCode==118||charCode==67||charCode==86) {
        
        var str = document.getElementById(id).value;
        //var str_id = id;//.toString();
        var str_id = id.substr(0,id.length-1);
        //alert('id->'+str_id.length+' and length->'+str_id.length);
        if((str.charAt(str.length-1)=='-' && charCode==44)||(str.charAt(str.length-1)=='-' && charCode==45))
            return false;
        else if((str.charAt(str.length-1)==',' && charCode==45)||(str.charAt(str.length-1)==',' && charCode==44))
            return false;
        else if(str.charAt(str.length-2)=='-' && charCode==45)
            return false;
        else if(str.charAt(str.length-3)=='-' && charCode==45)
            return false;
        else if((str.length==0 && charCode==45)||(str.length==0 && charCode==39)||(str.length==0 && charCode==44))
            document.getElementById(id).value='';
        else if(str_id=='p_adv_for')
        {
            //alert(charCode);
            if(str=='%' && charCode==8 || str=='%' && charCode==9)
                return true;
            else if(str=='%')
                return false;
            else 
                return true;
        }
        else if(str_id=='r_adv_for' || str_id=='i_adv_for')
        {
            if(str.length==0 && charCode==37)
                return false;
            else 
                return true;
        }
        else
            return true;
    }
    return false;
}

function onlynumbersadv(evt) 
{
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    //alert(charCode);
    if ((charCode >= 48 && charCode<= 57)||(charCode >= 65 && charCode<= 90)||(charCode >= 97 && charCode<= 122)
            ||charCode==9||charCode==8||charCode==45) {
        return true;
    }
    return false;
}

function getAdvocate(no,type)
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
            //alert(xmlhttp.responseText);
            var val = xmlhttp.responseText;
            val = val.split('~');
            document.getElementById(type+'adv_name'+no).innerHTML=val[0];
            document.getElementById(type+'adv_mob'+no).value=val[1];
            document.getElementById(type+'adv_email'+no).value=val[2];
        }
    }
    var url = base_url+"/Filing/Caveat/get_adv_name?advno="+document.getElementById(type+'adv_no'+no).value+"&advyr="+
            document.getElementById(type+'adv_yr'+no).value+"&advstate="+document.getElementById(type+'adv_state'+no).value;
    xmlhttp.open("GET",url,false);
    if(document.getElementById(type+'adv_yr'+no).value!='')
    {
//        /xmlhttp.send(null); 
        /*if(document.getElementById(type+'adv_no'+no).value=='9999' && document.getElementById(type+'adv_yr'+no).value=='2014')
            activeAdvEntry(no,type);
        else
        {
            deactiveAdvEntry(no,type);
            xmlhttp.send(null); 
        }*/
        xmlhttp.send(null); 
    } 
}


function getAdvocateAOR(no,type)
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
            //alert(xmlhttp.responseText);
            var val = xmlhttp.responseText;
            val = val.split('~');
            document.getElementById(type+'adv_name'+no).innerHTML=val[0];
            document.getElementById(type+'adv_mob'+no).value=val[1];
            document.getElementById(type+'adv_email'+no).value=val[2];
        }
    }
    var url = base_url+"/Filing/Caveat/get_adv_name_aor?aorcode="+document.getElementById(type+'aor'+no).value;
    xmlhttp.open("GET",url,false);
    if(document.getElementById(type+'aor'+no).value!='')
    {
//        /xmlhttp.send(null); 
        /*if(document.getElementById(type+'adv_no'+no).value=='9999' && document.getElementById(type+'adv_yr'+no).value=='2014')
            activeAdvEntry(no,type);
        else
        {
            deactiveAdvEntry(no,type);
            xmlhttp.send(null); 
        }*/
        xmlhttp.send(null); 
    } 
}

function activeAdvEntry(no,type)
{
    document.getElementById(type+'adv_name_write'+no).style.display='block';
    document.getElementById(type+'adv_name_write'+no).focus();
    document.getElementById(type+'adv_name'+no).innerHTML='';
    //document.getElementById(type+'adv_mob'+no).value='';
    //document.getElementById(type+'adv_mob'+no).style.display='none';
    //document.getElementById(type+'adv_email'+no).value='';
    //document.getElementById(type+'adv_email'+no).style.display='none';
}

function deactiveAdvEntry(no,type)
{
    if(document.getElementById(type+'adv_name_write'+no))
        document.getElementById(type+'adv_name_write'+no).style.display='none';
    document.getElementById(type+'adv_name'+no).style.display='inline';
    document.getElementById(type+'adv_mob'+no).style.display='inline';
    document.getElementById(type+'adv_email'+no).style.display='inline';
    //document.getElementById(type+'adv_name_write'+no).focus();
}

function copyToSpan(no,type)
{
    document.getElementById(type+'adv_name'+no).style.display='none';
    document.getElementById(type+'adv_name'+no).innerHTML = document.getElementById(type+'adv_name_write'+no).value.toUpperCase();
}

function del_adv(id)
{
    var c = confirm("Are You Sure You Want to Delete This Advocate");
    if(c==true)
    {
        if((document.getElementById('adv_no'+id).value!=document.getElementById('adv_no_hd'+id).value)||
            (document.getElementById('adv_yr'+id).value!=document.getElementById('adv_yr_hd'+id).value)||
            (document.getElementById('adv_name'+id).innerHTML!=document.getElementById('adv_name_hd'+id).value)||
            (document.getElementById('adv_p_no'+id).value!=document.getElementById('adv_p_no_hd'+id).value))
        {
            alert('Record Changed, Could Not Delete, Please Fetch Record Again');
            return false;
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
                if(xmlhttp.responseText==0)
                {
                    var r = '#row'+id;
                    var row = "<tr><td colspan='8' style='text-align:center;color:red;'><b>"+document.getElementById('adv_no'+id).value+
                            "/"+document.getElementById('adv_yr'+id).value+" : "+document.getElementById('adv_name'+id).innerHTML+"</b> Deleted Successfully</td></tr>";
                    $(r).replaceWith(row);
                }
                else
                    document.getElementById('result1').innerHTML = xmlhttp.responseText;
            }
        }
        var url=base_url+"/Filing/Caveat/del_advocate?val="+document.getElementById('adv_pet_res'+id).value+
        "&advno="+document.getElementById('adv_no'+id).value+
        "&advyr="+document.getElementById('adv_yr'+id).value+
        "&adv_name="+document.getElementById('adv_name'+id).innerHTML+
        "&party="+document.getElementById('adv_p_no'+id).value+
        "&fi="+document.getElementById('fil_hd').value+"&ud="+document.getElementById('hd_ud').value+"&id="+id;
        //alert(url);
        xmlhttp.open("GET",url,false);
        xmlhttp.send(null); 
    }
}

function upd_efil_add_adv(p_no,r_no)
{
//      alert("anshul");
      var pet='';
       var res='';
     for(var i=1;i<p_no;i++)
    {
        var chk = document.getElementById('p_adv_chk'+i);
      
        if(null != chk && true==chk.checked)
        {   
            
            
           if(pet=='')
               pet=document.getElementById('hd_p_adv_no'+i).value+'~'+document.getElementById('hd_p_adv_yr'+i).value+'~'+
                document.getElementById('hd_p_efil'+i).value;
            else
            pet=pet+'||'+document.getElementById('hd_p_adv_no'+i).value+'~'+document.getElementById('hd_p_adv_yr'+i).value+'~'+
                document.getElementById('hd_p_efil'+i).value;
        }
    }
 
    for(var i=1;i<r_no;i++)
    {
        var chk = document.getElementById('r_adv_chk'+i);
        if(null != chk && true==chk.checked)
        {
            if(res=='')
                res=document.getElementById('hd_r_adv_no'+i).value+'~'+document.getElementById('hd_r_adv_yr'+i).value+'~'+
               document.getElementById('hd_r_efil'+i).value;
           else
            res=res+'||'+document.getElementById('hd_r_adv_no'+i).value+'~'+document.getElementById('hd_r_adv_yr'+i).value+'~'+
               document.getElementById('hd_r_efil'+i).value;
        }
    }
//       alert(pet);
//          alert(res);
     var ct=document.getElementById('selct').value;
    var cn= document.getElementById('case_no').value;
    if(ct.length=='1')
        ct = '00'+ct;
    else if(ct.length=='2')
        ct = '0'+ct;
    var cy= document.getElementById('case_yr').value;
    var fno = document.getElementById('bench').value+ct+cn+cy;
    
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
//             alert(xmlhttp.responseText);
document.getElementById('hd_mn_efil_p').value='';
          document.getElementById('hd_mn_efil_r').value='';
             setTimeout(getDetails(),10000);
        }
    }
    var url = base_url+"/Filing/Caveat/update_advnew_efil"+"?fi="+fno+'&pet='+pet+'&res='+res;
   
    //alert(url);
    xmlhttp.open("GET",url,false);
    xmlhttp.send(null);
}