
$(document).ready(function(){
    $("input[name=btnGetAdvUp]").click(function(){
        getDetails();
    });
});


function getDetails(val)
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
        url: base_url+"/Filing/Caveat/cav_adv_fetch_parties_first_up",
        beforeSend: function (xhr) {
            $("#result1").html('<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>');
        },
        data:{filno:$('#fil_hd').val(),dno:diaryno,dyr:diaryyear,CSRF_TOKEN: CSRF_TOKEN_VALUE}
    })
    .done(function(msg){
		updateCSRFToken();
        $("#result1").html(msg);
        $("#result2").html("");
        if(val=='D')
        {
            $('#suc_msg').show();
            //$('#suc_msg').hide(5000);
        }
    })
    .fail(function(){
		updateCSRFToken();
        alert("ERROR, Please Contact Server Room"); 
    });
}

$(document).on("click","input[name=updatebutton]",function(){
    //alert('ye mera india');
    saveAdv();
});

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

function saveAdv()
{
	//alert('sss')
    var save_ctrl=0;
    var no = document.getElementById('all').value;
    for(var i=1;i<no;i++)
    {
        if(document.getElementById('adv_no'+i) && document.getElementById('adv_no'+i).style.display!='none')
        {
            if(document.getElementById('adv_name_write'+i).style.display=='none'){
                /*if($("#adv_state"+i).val()==''){
                    alert('Please fill Advocate State');
                    document.getElementById('adv_state'+i).focus();
                    return false;
                }*/
            }
            
            /*if(document.getElementById('adv_no'+i).value==''||document.getElementById('adv_no'+i).value==0)
            {
                alert('Please fill Advocate Enroll Number');
                document.getElementById('adv_no'+i).focus();
                return false;
            }
            else if(document.getElementById('adv_yr'+i).value==''||document.getElementById('adv_yr'+i).value==0)
            {
                alert('Please fill Advocate Enroll Year');
                document.getElementById('adv_yr'+i).focus();
                return false;
            }*/
            if(document.getElementById('adv_aor'+i).value=='')
            {
                alert('Please fill Advocate AOR Code');
                document.getElementById('adv_aor'+i).focus();
                return false;
            }
            else if(document.getElementById('adv_name'+i).innerHTML==''||document.getElementById('adv_name'+i).innerHTML==0)
            {
                if(document.getElementById('adv_name_write'+i).style.display=='block'){
                    alert('Please Fill Advocate Name');
                    document.getElementById('adv_name_write'+i).focus();
                }
                else{
                    alert('Please fetch Proper Advocate Again');
                    document.getElementById('adv_aor'+i).focus();
                }
                //alert('Please fetch Proper Advocate Again');
                //document.getElementById('adv_no'+i).focus();
                return false;
            }
            /*if(document.getElementById('adv_email'+i).value!='')
            {
                var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
                if(document.getElementById('adv_email'+i).value.match(mailformat))
                {
                    //return true;
                }
                else
                {
                    alert('Please enter valid email');
                    document.getElementById('adv_email'+i).focus();
                    return false;
                }
            }*/
            if(document.getElementById('adv_p_no'+i).value==0)
            {
                /*if(document.getElementById('adv_no'+i).value=='9999' && document.getElementById('adv_yr'+i).value=='2014')
                {
                    //return true;
                }
                else*/ if(document.getElementById('adv_type'+i).value=='N')
                {
                    if(document.getElementById('adv_pet_res'+i).value=='R')
                    {
                        alert('Party No cannot be Zero');
                        document.getElementById('adv_p_no'+i).focus();
                        return false;
                    }
                }
            }
            /*if($("#adv_side"+i).val()==''){
                alert("Please Select Party Side");
                $("#adv_side"+i).focus();
                return false;
            }*/
        }
    }
    for(var i=1;i<no;i++)
    {
        /*if(document.getElementById('adv_no'+i) && document.getElementById('adv_no'+i).style.display=='none')
        {
            if(document.getElementById('adv_name_write'+i).value=='')
            {
                alert('Name can not be Blank, You Should Delete it Rather Updating Blank Record');
                document.getElementById('adv_name_write'+i).focus();
                return false;
            }
        }*/
    }
    //return false;
    for(var i=1;i<no;i++)
    {
        if(document.getElementById('adv_aor'+i))
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
                    //document.getElementById('result1').innerHTML += xmlhttp.responseText;
                    if(xmlhttp.responseText!=0){
                        //save_ctrl = xmlhttp.responseText;
                        save_ctrl += xmlhttp.responseText;
                    }
                }
            }
            //alert(document.getElementById('adv_no'+i).value);
            var url = base_url+"Filing/Caveat/cav_save_advnew_updated?val="+document.getElementById('adv_pet_res'+i).value+
                    //"&advstate="+document.getElementById('adv_state'+i).value+
                    //"&advno="+document.getElementById('adv_no'+i).value+
                    //"&advyr="+document.getElementById('adv_yr'+i).value+
                    "&advaor="+document.getElementById('adv_aor'+i).value+
                    "&advaor_hd="+document.getElementById('adv_aor_hd'+i).value+
                    "&adv_name="+document.getElementById('adv_name'+i).innerHTML+
                    "&adv_name_hd="+document.getElementById('adv_name_hd'+i).value+
                    //"&advstate_hd="+document.getElementById('adv_state_hd'+i).value+
                    //"&advno_hd="+document.getElementById('adv_no_hd'+i).value+
                    //"&advyr_hd="+document.getElementById('adv_yr_hd'+i).value+
                    "&party_hd="+document.getElementById('adv_p_no_hd'+i).value+
                    "&advtype="+document.getElementById('adv_type_hd'+i).value+
                    "&party="+document.getElementById('adv_p_no'+i).value+
                    "&fi="+document.getElementById('fil_hd').value+
                    "&adv_mob="+document.getElementById('adv_mob'+i).value+"&adv_email="+document.getElementById('adv_email'+i).value+
                    "&adv_type="+document.getElementById('adv_type'+i).value+"&ifag="+document.getElementById('ifag'+i).value+
                    "&stateadv="+document.getElementById('statepg'+i).value+"&stateadv_hd="+document.getElementById('statepg_hd'+i).value;
                    /*"&adv_side="+$("#adv_side"+i).val();*/
            //alert(url);
            xmlhttp.open("GET",url,false);
            xmlhttp.send(null);
        }
    }
    if(save_ctrl==0)
        getDetails('D');
    else
        document.getElementById('result1').innerHTML += save_ctrl;
}

function onlynumbers(evt,str) 
{
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    //alert(charCode);
    if ((charCode >= 48 && charCode<= 57)||charCode==9||charCode==8) {
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

function advName(evt)
{
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    //alert(charCode);
    if (charCode==8 ||(charCode >= 65 && charCode<= 90)||(charCode >= 97 && charCode<= 122)
            ||(charCode >= 48 && charCode<= 57)||charCode==127||charCode==32||(charCode >= 37 && charCode<= 41)) {
        return true;
    }
    return false;
}

function getAdvocate(no)
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
            var val = xmlhttp.responseText;
            //alert(val);
            val = val.split('~');
            document.getElementById('adv_name'+no).innerHTML=val[0];
            document.getElementById('adv_mob'+no).value=val[1];
            document.getElementById('adv_email'+no).value=val[2];
            if(document.getElementById('adv_name_write'+no).style.display=='block'){
                document.getElementById('adv_name_write'+no).style.display='none';
                document.getElementById('adv_name'+no).style.display='inline';
                document.getElementById('adv_mob'+no).style.display='inline';
                document.getElementById('adv_email'+no).style.display='inline';
            }
        }
    }
    
    var url = "get_adv_name.php"+"?advno="+document.getElementById('adv_no'+no).value+"&advyr="+
        document.getElementById('adv_yr'+no).value+"&advstate="+document.getElementById('adv_state'+no).value;
    xmlhttp.open("GET",url,false);
    if(document.getElementById('adv_yr'+no).value!='')
    {    
        /*if(document.getElementById('adv_no'+no).value=='9999' && document.getElementById('adv_yr'+no).value=='2014')
        {    
            if(no!=9999)
                activeAdvEntry(no);
        }
        else
        {
            if(no!=9999)
                deactiveAdvEntry(no);
            xmlhttp.send(null); 
        }*/
        xmlhttp.send(null); 
    }
}

function getAdvocateAOR(no)
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
            var val = xmlhttp.responseText;
            //alert(val);
            val = val.split('~');
            document.getElementById('adv_name'+no).innerHTML=val[0];
            document.getElementById('adv_mob'+no).value=val[1];
            document.getElementById('adv_email'+no).value=val[2];
            if(document.getElementById('adv_name_write'+no).style.display=='block'){
                document.getElementById('adv_name_write'+no).style.display='none';
                document.getElementById('adv_name'+no).style.display='inline';
                document.getElementById('adv_mob'+no).style.display='inline';
                document.getElementById('adv_email'+no).style.display='inline';
            }
        }
    }
    
    var url = "get_adv_name_aor.php?aorcode="+document.getElementById('adv_aor'+no).value;
    xmlhttp.open("GET",url,false);
    if(document.getElementById('adv_aor'+no).value!='')
    {    
        /*if(document.getElementById('adv_no'+no).value=='9999' && document.getElementById('adv_yr'+no).value=='2014')
        {    
            if(no!=9999)
                activeAdvEntry(no);
        }
        else
        {
            if(no!=9999)
                deactiveAdvEntry(no);
            xmlhttp.send(null); 
        }*/
        xmlhttp.send(null); 
    }
}

function activeAdvEntry(no)
{
    document.getElementById('adv_name_write'+no).style.display='block';
    document.getElementById('adv_name_write'+no).value='';
    document.getElementById('adv_name_write'+no).focus();
    document.getElementById('adv_name'+no).innerHTML='';
    document.getElementById('adv_mob'+no).value='';
    document.getElementById('adv_mob'+no).style.display='none';
    document.getElementById('adv_email'+no).value='';
    document.getElementById('adv_email'+no).style.display='none';
    
}

function deactiveAdvEntry(no)
{
    if(document.getElementById('adv_name_write'+no))
        document.getElementById('adv_name_write'+no).style.display='none';
    document.getElementById('adv_name'+no).style.display='inline';
    document.getElementById('adv_mob'+no).style.display='inline';
    document.getElementById('adv_email'+no).style.display='inline';
    //document.getElementById(type+'adv_name_write'+no).focus();
}

function copyToSpan(no)
{
    document.getElementById('adv_name'+no).style.display='none';
    document.getElementById('adv_name'+no).innerHTML = document.getElementById('adv_name_write'+no).value.toUpperCase();
}

$(document).on("click","input[name^=button_delete_]",function(){
    //alert('ye mera india');
    //alert('called');
    var id_ = this.name.split('button_delete_');
    //alert(id_[1]);
    del_adv(id_[1]);
});

function del_adv(id)
{
    var c = confirm("Are You Sure You Want to Delete This Advocate");
    if(c==true)
    {
        if(document.getElementById('adv_name_write'+id).style.display=='block'){
            /*if( (document.getElementById('adv_no'+id).value!=document.getElementById('adv_no_hd'+id).value)||
                (document.getElementById('adv_yr'+id).value!=document.getElementById('adv_yr_hd'+id).value)||
                (document.getElementById('adv_name'+id).innerHTML!=document.getElementById('adv_name_hd'+id).value)||
                (document.getElementById('adv_p_no'+id).value!=document.getElementById('adv_p_no_hd'+id).value))*/
            /*if( (document.getElementById('adv_aor'+id).value!=document.getElementById('adv_aor_hd'+id).value)||
                (document.getElementById('adv_name'+id).innerHTML!=document.getElementById('adv_name_hd'+id).value)||
                (document.getElementById('adv_p_no'+id).value!=document.getElementById('adv_p_no_hd'+id).value))*/            
            if( (document.getElementById('adv_aor'+id).value!=document.getElementById('adv_aor_hd'+id).value)||
                (document.getElementById('adv_p_no'+id).value!=document.getElementById('adv_p_no_hd'+id).value))
            {
                //alert(document.getElementById('adv_state'+id).value+'!='+document.getElementById('adv_state_hd'+id).value);
                alert('Record Changed, Could Not Delete, Please Fetch Record Again');
                return false;
            }
        }
        else{
            /*if( (document.getElementById('adv_state'+id).value!=document.getElementById('adv_state_hd'+id).value)||
                (document.getElementById('adv_no'+id).value!=document.getElementById('adv_no_hd'+id).value)||
                (document.getElementById('adv_yr'+id).value!=document.getElementById('adv_yr_hd'+id).value)||
                (document.getElementById('adv_name'+id).innerHTML!=document.getElementById('adv_name_hd'+id).value)||
                (document.getElementById('adv_p_no'+id).value!=document.getElementById('adv_p_no_hd'+id).value))*/
            /*if( (document.getElementById('adv_aor'+id).value!=document.getElementById('adv_aor_hd'+id).value)||
                (document.getElementById('adv_name'+id).innerHTML!=document.getElementById('adv_name_hd'+id).value)||
                (document.getElementById('adv_p_no'+id).value!=document.getElementById('adv_p_no_hd'+id).value))*/
            if( (document.getElementById('adv_aor'+id).value!=document.getElementById('adv_aor_hd'+id).value)||
                (document.getElementById('adv_p_no'+id).value!=document.getElementById('adv_p_no_hd'+id).value))
            {
                //alert(document.getElementById('adv_state'+id).value+'!='+document.getElementById('adv_state_hd'+id).value);
                alert('Record Changed, Could Not Delete, Please Fetch Record Again');
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
                alert(xmlhttp.responseText);
                if(xmlhttp.responseText==0)
                {
                    var r = '#row'+id;
                    var row = "<tr><td colspan='11' style='text-align:center;color:red;'><b>"+document.getElementById('adv_aor'+id).value+
                            " : "+document.getElementById('adv_name'+id).innerHTML+"</b> Deleted Successfully</td></tr>";
                    $(r).replaceWith(row);
                }
                else
                    document.getElementById('result1').innerHTML = xmlhttp.responseText;
            }
        }
        var url="cav_del_advocate.php?val="+document.getElementById('adv_pet_res'+id).value+
        //"&advstate="+document.getElementById('adv_state'+id).value+
        //"&advno="+document.getElementById('adv_no'+id).value+
        //"&advyr="+document.getElementById('adv_yr'+id).value+
        "&advaor="+document.getElementById('adv_aor'+id).value+
        "&adv_name="+document.getElementById('adv_name'+id).innerHTML+
        "&party="+document.getElementById('adv_p_no'+id).value+
        "&advtype="+document.getElementById('adv_type_hd'+id).value+
        "&fi="+document.getElementById('fil_hd').value+"&id="+id;
        //alert(url);
        xmlhttp.open("GET",url,false);
        xmlhttp.send(null); 
    }
}