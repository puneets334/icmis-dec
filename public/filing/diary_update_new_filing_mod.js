//Function Autocomplete start pet post------------------------------------------------
$(document).on("focus","#pet_post",function(){
$("#pet_post").autocomplete({
    source:"../Common/Ajaxcalls/new_filing_autocomp_post",
width: 450,
matchContains: true,	
minChars: 1,
selectFirst: false,
autoFocus: true
});    
});

$(document).on("keyup","#padvno",function(){
    $("#padvno").autocomplete({
        source: "../Common/Ajaxcalls/get_aor_name",
        width: 450,
        matchContains: true,
        minLength: 1,
        selectFirst: true,
        autoFocus: false,
        select: function(event, ui) {
            //event.preventDefault();
            // $("#padvname").val( (ui.item.label).substring((ui.item.label).indexOf("-")+1)  );
            getAdvocate_for_main($('#padvno'),'P');
        },
        focus: function(event, ui) {
            //event.preventDefault();
            // $("#padvname").val( (ui.item.label).substring((ui.item.label).indexOf("-")+1)  );
            getAdvocate_for_main($('#padvno'),'P');
        }
    });
});
$(document).on("keyup","#radvno",function(){
    $("#radvno").autocomplete({
        source: "../Common/Ajaxcalls/get_aor_name",
        width: 450,
        matchContains: true,
        minLength: 1,
        selectFirst: true,
        autoFocus: false,
        select: function(event, ui) {
            //event.preventDefault();
            // $("#padvname").val( (ui.item.label).substring((ui.item.label).indexOf("-")+1)  );
            getAdvocate_for_main($('#radvno'),'R');
        },
        focus: function(event, ui) {
            //event.preventDefault();
            // $("#padvname").val( (ui.item.label).substring((ui.item.label).indexOf("-")+1)  );
            getAdvocate_for_main($('#radvno'),'R');
        }
    });
});
$(document).on("blur","#pet_post",function(){
    if(this.value.indexOf('~') != '-1'){
        var htht = this.value.split('~');
        $("#pet_post").val(htht[1]);
        $("#pet_post_code").val(htht[0]);
    }
});


$(document).on("focus","#pet_statename",function(){
$("#pet_statename").autocomplete({
    source: "../Common/Ajaxcalls/get_only_state_name",
    width: 450,
    matchContains: true,	
    minChars: 1,
    selectFirst: false,
    autoFocus: true
});    
});

$(document).on("blur","#pet_statename",function(){
    if(this.value.indexOf('~') != '-1'){
        var htht = this.value.split('~');
        $("#pet_statename").val(htht[1]);
        $("#pet_statename_hd").val(htht[0]);
    }
});


$(document).on("focus","#res_statename",function(){
$("#res_statename").autocomplete({
    source: "../Common/Ajaxcalls/get_only_state_name",
width: 450,
matchContains: true,	
minChars: 1,
selectFirst: false,
autoFocus: true
});    
});

$(document).on("blur","#res_statename",function(){
    if(this.value.indexOf('~') != '-1'){
        var htht = this.value.split('~');
        $("#res_statename").val(htht[1]);
        $("#res_statename_hd").val(htht[0]);
    }
});

//Function Autocomplete end--------------------------------------------------
            
//Function Autocomplete start pet deptt------------------------------------------------
$(document).on("focus","#pet_deptt",function(){
$("#pet_deptt").autocomplete({
    source:"../Common/Ajaxcalls/new_filing_autocomp_deptt?type="+$("#selpt").val(),
    width: 450,
    matchContains: true,	
    minChars: 1,
    selectFirst: false,
    autoFocus: true
});    
});

$(document).on("blur","#pet_deptt",function(){
    if(this.value.indexOf('~') != '-1'){
        var htht = this.value.split('~');
        $("#pet_deptt").val(htht[1]);
        $("#pet_deptt_code").val(htht[0]);
    }
});
//Function Autocomplete end--------------------------------------------------
    
//Function Autocomplete start res post------------------------------------------------
$(document).on("focus","#res_post",function(){
$("#res_post").autocomplete({
    source:"../Common/Ajaxcalls/new_filing_autocomp_post",
width: 450,
matchContains: true,	
minChars: 1,
selectFirst: false,
autoFocus: true
});    
});

$(document).on("blur","#res_post",function(){
    if(this.value.indexOf('~') != '-1'){
        var htht = this.value.split('~');
        $("#res_post").val(htht[1]);
        $("#res_post_code").val(htht[0]);
    }
});
//Function Autocomplete end--------------------------------------------------
            
//Function Autocomplete start res deptt------------------------------------------------
$(document).on("focus","#res_deptt",function(){
$("#res_deptt").autocomplete({
    source:"../Common/Ajaxcalls/new_filing_autocomp_deptt?type="+$("#selrt").val(),
    width: 450,
    matchContains: true,	
    minChars: 1,
    selectFirst: false,
    autoFocus: true
});    
});

$(document).on("blur","#res_deptt",function(){
    if(this.value.indexOf('~') != '-1'){
        var htht = this.value.split('~');
        $("#res_deptt").val(htht[1]);
        $("#res_deptt_code").val(htht[0]);
    }
});
//Function Autocomplete end--------------------------------------------------

//Function Autocomplete start pet address------------------------------------------------
/*$(document).on("focus","#paddi",function(){
$("#paddi").autocomplete({
    source:"new_filing_ac.php?ctrl=a",
width: 450,
matchContains: true,	
minChars: 1,
selectFirst: false,
autoFocus: true
});    
});
$(document).on("focus","#paddd",function(){
$("#paddd").autocomplete({
    source:"new_filing_ac.php?ctrl=a",
width: 450,
matchContains: true,	
minChars: 1,
selectFirst: false,
autoFocus: true
});    
});*/
//Function Autocomplete end--------------------------------------------------

//Function Autocomplete start pet place/city------------------------------------------------
/*$(document).on("focus","#pcityi",function(){
$("#pcityi").autocomplete({
    source:"new_filing_ac.php?ctrl=p",
width: 450,
matchContains: true,	
minChars: 1,
selectFirst: false,
autoFocus: true
});    
});
$(document).on("focus","#pcityd",function(){
$("#pcityd").autocomplete({
    source:"new_filing_ac.php?ctrl=p",
width: 450,
matchContains: true,	
minChars: 1,
selectFirst: false,
autoFocus: true
});    
});*/
//Function Autocomplete end--------------------------------------------------

//Function Autocomplete start res address------------------------------------------------
/*$(document).on("focus","#raddi",function(){
$("#raddi").autocomplete({
    source:"new_filing_ac.php?ctrl=a",
width: 450,
matchContains: true,	
minChars: 1,
selectFirst: false,
autoFocus: true
});    
});
$(document).on("focus","#raddd",function(){
$("#raddd").autocomplete({
    source:"new_filing_ac.php?ctrl=a",
width: 450,
matchContains: true,	
minChars: 1,
selectFirst: false,
autoFocus: true
});    
});*/
//Function Autocomplete end--------------------------------------------------

//Function Autocomplete start res place/city------------------------------------------------
/*$(document).on("focus","#rcityi",function(){
$("#rcityi").autocomplete({
    source:"new_filing_ac.php?ctrl=p",
width: 450,
matchContains: true,	
minChars: 1,
selectFirst: false,
autoFocus: true
});    
});
$(document).on("focus","#rcityd",function(){
$("#rcityd").autocomplete({
    source:"new_filing_ac.php?ctrl=p",
width: 450,
matchContains: true,	
minChars: 1,
selectFirst: false,
autoFocus: true
});    
});*/
//Function Autocomplete end--------------------------------------------------
$(document).on("change","#ddl_bench",function(){
    f();
});
function setCountry_state_dis(id,value){
    var string1 = id.split('_cont');
    if(value != "96"){
        $("#sel"+string1[0]+'st'+string1[1]).prop("disabled",true);
        $("#sel"+string1[0]+'dis'+string1[1]).prop("disabled",true);
        $("#sel"+string1[0]+'st'+string1[1]).val("");
        $("#sel"+string1[0]+'dis'+string1[1]).val("");
    }
    else{
        $("#sel"+string1[0]+'st'+string1[1]).removeProp("disabled");
        $("#sel"+string1[0]+'dis'+string1[1]).removeProp("disabled");
    }
}


function changeAdvocate(id,val)
{
    if(id=='padvt')
    {
        $('#ddl_pet_adv_state').val('');
        $('#padvno').val('');
        $('#padvyr').val('');
        $('#padvname').val('');
        $('#padvmob').val('');
        $('#padvemail').val('');
        if(val=='S')
        {
            document.getElementById('padvno').style.display='inline';
            document.getElementById('padvno_').style.display='inline';
            document.getElementById('padvyr').style.display='inline';
            document.getElementById('padvyr_').style.display='inline';
            // document.getElementById('padvname').disabled=true;
            document.getElementById('padvname').value='';
            document.getElementById('padvmob').value='';
            document.getElementById('padvmob').style.display='inline';
            document.getElementById('padvmob_').style.display='inline';
            document.getElementById('padvemail').value='';
            document.getElementById('padvemail').style.display='inline';
            document.getElementById('padvemail_').style.display='inline';
            $('#padvyr').attr('disabled',false);
            // $('#ddl_pet_adv_state').attr('disabled',false);

            document.getElementById('ddl_pet_adv_state').style.display = 'inline';
            document.getElementById('padv_state').style.display = 'inline';
            $('#ddl_pet_adv_state').attr('disabled',false);
        }
        if(val == 'N')
        {
            $('#ddl_pet_adv_state').attr('disabled',false);
            document.getElementById('padv_state').style.display = 'inline';
            document.getElementById('ddl_pet_adv_state').style.display = 'inline';

            document.getElementById('padvno').style.display = 'inline';
            document.getElementById('padvno_').style.display = 'inline';
            document.getElementById('padvyr').style.display = 'inline';
            document.getElementById('padvyr_').style.display = 'inline';
            document.getElementById('padvmob').style.display = "inline";
            document.getElementById('padvmob_').style.display = "inline";
            document.getElementById('padvemail').style.display = 'inline';
            document.getElementById('padvemail_').style.display = 'inline';
            // document.getElementById('padvname').disabled = true;
            document.getElementById('padvname').value = '';
            document.getElementById('padvmob').value = '';
            document.getElementById('padvemail').value = '';
            $('#padvyr').attr('disabled',false);
            $('#ddl_pet_adv_state').attr('disabled',false);
            $('#is_ac').attr('disabled',false);
            $('#is_ac').prop('checked',true);
        }
        else if(val=='A')
        {
            $('#padvyr').attr('disabled',true);
            document.getElementById('padvyr').style.display = 'none';
            document.getElementById('padvyr_').style.display = 'none';
            document.getElementById('padvyr').value = '';

            $('#ddl_pet_adv_state').val('');    
            $('#ddl_pet_adv_state').attr('disabled',true);
            $('#padvno').val('');
            $('#padvyr').val('');
            $('#padvno').attr('disabled',false);
            $('#padvyr').attr('disabled',true);
            document.getElementById('padvno').style.display='inline';
            document.getElementById('padvno_').style.display='inline';
            // document.getElementById('padvyr').style.display='inline';
            // document.getElementById('padvyr_').style.display='inline';
            document.getElementById('padvmob').style.display='inline';
            document.getElementById('padvmob_').style.display='inline';
            document.getElementById('padvemail').style.display='inline';
            document.getElementById('padvemail_').style.display='inline';
            $('#is_ac').attr('disabled',false);
            $('#is_ac').prop('checked',false);

            document.getElementById('ddl_pet_adv_state').style.display = 'none';
            document.getElementById('padv_state').style.display = 'none';
        }
        else if(val!='S')
        {
            if(val=='SS'){
                document.getElementById('padvno').style.display='none';
                document.getElementById('padvno_').style.display='none';
                document.getElementById('padvno').value='';
                document.getElementById('padvyr').style.display='none';
                document.getElementById('padvyr_').style.display='none';
                document.getElementById('padvyr').value='';
                // document.getElementById('padvname').disabled=false;
                document.getElementById('padvmob').style.display='none';
                document.getElementById('padvmob_').style.display='none';
                document.getElementById('padvemail').style.display='none';
                document.getElementById('padvemail_').style.display='none';
                
                if(document.getElementById('selpt').value=='I')
                    document.getElementById('padvname').value=document.getElementById('pet_name').value+" (SELF)";
            }

            if(val=='C')
            {
                
            }
            else if(val=='SS')
            {
                
            } 
            $('#ddl_pet_adv_state').attr('disabled',true);
        }
    }
    else if(id=='radvt')
    {   
        $('#hd_r_barid').val('0');
        $('#ddl_res_adv_state').val('');
        $('#radvno').val('');
        $('#radvyr').val('');
        $('#radvname').val('');
        $('#radvmob').val('');
        $('#radvemail').val('');
        if(val=='S')
        {
            document.getElementById('radvno').style.display='inline';
            document.getElementById('radvno_').style.display='inline';
            document.getElementById('radvyr').style.display='inline';
            document.getElementById('radvyr_').style.display='inline';
            // document.getElementById('radvname').disabled=true;
            document.getElementById('radvname').value='';
            document.getElementById('radvmob').value='';
            document.getElementById('radvmob').style.display='inline';
            document.getElementById('radvmob_').style.display='inline';
            document.getElementById('radvemail').value='';
            document.getElementById('radvemail').style.display='inline';
            document.getElementById('radvemail_').style.display='inline';
            $('#radvyr').attr('disabled',false);
            // $('#ddl_res_adv_state').attr('disabled',false);

            document.getElementById('ddl_res_adv_state').style.display = 'inline';
            document.getElementById('radv_state').style.display = 'inline';
            $('#ddl_res_adv_state').attr('disabled',false);
        }
        else if(val=='A')
        {
            $('#radvyr').attr('disabled',true);
            document.getElementById('radvyr').style.display = 'none';
            document.getElementById('radvyr_').style.display = 'none';
            document.getElementById('radvyr').value = '';

            $('#ddl_res_adv_state').val('');    
            $('#ddl_res_adv_state').attr('disabled',true);
            $('#radvno').val('');
            $('#radvyr').val('');
            $('#radvno').attr('disabled',false);
            $('#radvyr').attr('disabled',true);
            document.getElementById('radvno').style.display='inline';
            document.getElementById('radvno_').style.display='inline';
            // document.getElementById('radvyr').style.display='inline';
            // document.getElementById('radvyr_').style.display='inline';
            document.getElementById('radvmob').style.display='inline';
            document.getElementById('radvmob_').style.display='inline';
            document.getElementById('radvemail').style.display='inline';
            document.getElementById('radvemail_').style.display='inline';

            $('#ris_ac').attr('disabled',false);
            $('#ris_ac').prop('checked',false);

            document.getElementById('ddl_res_adv_state').style.display = 'none';
            document.getElementById('radv_state').style.display = 'none';
        }
        if(val == 'N')
        {
            $('#ddl_res_adv_state').attr('disabled',false);
            document.getElementById('radv_state').style.display = 'inline';
            document.getElementById('ddl_res_adv_state').style.display = 'inline';

            document.getElementById('radvno').style.display = 'inline';
            document.getElementById('radvno_').style.display = 'inline';
            document.getElementById('radvyr').style.display = 'inline';
            document.getElementById('radvyr_').style.display = 'inline';
            // document.getElementById('radvname').disabled = true;
            document.getElementById('radvname').value = '';
            document.getElementById('radvmob').value = '';
            document.getElementById('radvmob').style.display = 'inline';
            document.getElementById('radvmob_').style.display = 'inline';
            document.getElementById('radvemail').value = '';
            document.getElementById('radvemail').style.display = 'inline';
            document.getElementById('radvemail_').style.display = 'inline';
            $('#radvyr').attr('disabled',false);
            $('#ddl_res_adv_state').attr('disabled',false);
            $('#ris_ac').attr('disabled',false);
            $('#ris_ac').prop('checked',true);

        }
        else if(val!='S')
        {
            if(val=='SS'){
                document.getElementById('radvno').style.display='none';
                document.getElementById('radvno_').style.display='none';
                document.getElementById('radvno').value='';
                document.getElementById('radvyr').style.display='none';
                document.getElementById('radvyr_').style.display='none';
                document.getElementById('radvyr').value='';
                // document.getElementById('radvname').disabled=false;
                document.getElementById('radvmob').style.display='none';
                document.getElementById('radvmob_').style.display='none';
                document.getElementById('radvemail').style.display='none';
                document.getElementById('radvemail_').style.display='none';
                if(document.getElementById('selrt').value=='I')
                    document.getElementById('radvname').value=document.getElementById('res_name').value+" (SELF)";
            }

            if(val=='C')
            {
                
            }
            else if(val=='SS')
            {
                
            } 
            $('#ddl_res_adv_state').attr('disabled',true);
        }
    }
}
   
function call_getDetails()
{
    var d_no=$('#t_h_cno').val();
    var d_yr=$('#t_h_cyt').val();
    var xmlhttp;
    if (window.XMLHttpRequest)
    {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
    }
    else
    {// code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    document.getElementById('show_fil').innerHTML = '<table widht="100%" align="center"><tr><td><img src="../images/load.gif"/></td></tr></table>';
    xmlhttp.onreadystatechange=function()
    {
        if(xmlhttp.readyState==4 && xmlhttp.status==200)
        {
            document.getElementById('show_fil').innerHTML=xmlhttp.responseText;
        }
    }
    var url = "get_filing_mod.php?d_no="+d_no+"&d_yr="+d_yr;
    xmlhttp.open("GET",url,false);
    xmlhttp.send(null); 
}
            
            
function activate_main(id)
{    //alert('hi depart type='+id);
    if(id=="selpt")
    {
        if(document.getElementById(id).value=="I")
        {
            /*document.getElementById('pet_post').value="";
            document.getElementById('pet_deptt').value="";
            document.getElementById('paddd').value="";
            document.getElementById('pcityd').value="";
            document.getElementById('ppind').value="";
            document.getElementById('selpdisd').value="";
            document.getElementById('selpstd').value="23";
            document.getElementById('pmobd').value="";
            document.getElementById('pemaild').value="";*/
            document.getElementById('for_I_p').style.display='block';
            document.getElementById('for_D_p').style.display='none';
            $('#state_department_in_pet').val("");
        }
        else if(document.getElementById(id).value!="I")
        {
            /*document.getElementById('pet_name').value="";
            document.getElementById('selprel').value="";
            document.getElementById('prel').value="";
            document.getElementById('psex').value="";
            document.getElementById('page').value="";
            document.getElementById('pocc').value="";
            document.getElementById('paddi').value="";
            document.getElementById('pcityi').value="";
            document.getElementById('ppini').value="";
            document.getElementById('selpdisi').value="";
            document.getElementById('selpsti').value="23";
            document.getElementById('pmobi').value="";
            document.getElementById('pemaili').value="";*/
            document.getElementById('for_I_p').style.display='none';
            document.getElementById('for_D_p').style.display='block';
            if(document.getElementById(id).value == "D3"){
                document.getElementById('for_D_p_sn1').style.display = 'none';
                document.getElementById('for_D_p_sn2').style.display = 'none';
            }
            else{
                /*document.getElementById('for_D_p_sn1').style.display = 'table-cell';
                document.getElementById('for_D_p_sn2').style.display = 'table-cell';*/
                document.getElementById('for_D_p_sn1').style.display='block';
                document.getElementById('for_D_p_sn2').style.display='block';
            }
            
            /*if(document.getElementById(id).value=='D1')
                $(".state_p").css('display','table-cell');
            else
            {
                $(".state_p").css('display','none');
                $('#state_department_in_pet').val("");
            }*/
        }
    }
    else if(id=="selrt")
    {
        if(document.getElementById(id).value=="I")
        {
            /*document.getElementById('res_post').value="";
            document.getElementById('res_deptt').value="";
            document.getElementById('raddd').value="";
            document.getElementById('rcityd').value="";
            document.getElementById('rpind').value="";
            document.getElementById('selrdisd').value="";
            document.getElementById('selrstd').value="23";
            document.getElementById('rmobd').value="";
            document.getElementById('remaild').value="";*/
            document.getElementById('for_I_r').style.display='block';
            document.getElementById('for_D_r').style.display='none';
            $('#state_department_in_res').val("");
        }
        else if(document.getElementById(id).value!="I")
        {
            /*document.getElementById('res_name').value="";
            document.getElementById('selrrel').value="";
            document.getElementById('rrel').value="";
            document.getElementById('rsex').value="";
            document.getElementById('rage').value="";
            document.getElementById('rocc').value="";
            document.getElementById('raddi').value="";
            document.getElementById('rcityi').value="";
            document.getElementById('rpini').value="";
            document.getElementById('selrdisi').value="";
            document.getElementById('selrsti').value="23";
            document.getElementById('rmobi').value="";
            document.getElementById('remaili').value="";*/
            document.getElementById('for_I_r').style.display='none';
            document.getElementById('for_D_r').style.display='block';
            if(document.getElementById(id).value == "D3"){
                document.getElementById('for_D_r_sn1').style.display = 'none';
                document.getElementById('for_D_r_sn2').style.display = 'none';
            }
            else{
                /*document.getElementById('for_D_r_sn1').style.display = 'table-cell';
                document.getElementById('for_D_r_sn2').style.display = 'table-cell';*/
                document.getElementById('for_D_r_sn1').style.display = 'block';
                document.getElementById('for_D_r_sn2').style.display = 'block';
            }
            /*if(document.getElementById(id).value=='D1')
                $(".state_r").css('display','table-cell');
            else
            {
                $(".state_r").css('display','none');
                $('#state_department_in_res').val("");
            }*/
        }
    }
}
        
function getAdvocate_for_main(id,flag)
{

    if(flag=='P')
    {
        var ddl_pet_adv_state=$('#ddl_pet_adv_state').val();
         var padvt=$('#padvt').val();
        var ddl_pet_adv_no=$('#padvno').val();
        var ddl_pet_adv_yr=$('#padvyr').val();
        var ddl_pet_adv_isac='N';

//alert(document.getElementById("is_ac").checked );
        //alert(document.getElementById("padvt").value );
        if(document.getElementById("is_ac").checked == true)
            ddl_pet_adv_isac='Y';
        if(document.getElementById("is_ac").checked == true && document.getElementById("padvt").value=='N' && id=='padvyr'){
            if(document.getElementById("ddl_pet_adv_state").value==''){
                alert('Please Select State');
                document.getElementById("ddl_pet_adv_state").focus();
                return false;
            }
            if(document.getElementById("padvno").value==''){
                alert('Please enter Enrollment No.');
                document.getElementById("padvno").focus();
                return false;
            }
            if(document.getElementById("padvyr").value==''){
                alert('Please enter Enrollment Year');
                document.getElementById("padvyr").focus();
                return false;
            }

        }

        if(document.getElementById("padvno").value==''  && padvt=='A'){
            alert('Please enter AOR Code');
            //   document.getElementById("padvno").value='oo';
            //document.getElementById("padvno").focus();
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
                var vcal = xmlhttp.responseText;
                if(vcal!=0) {
                    vcal = vcal.split('~');
                    var padvname = '';
                    var padvmob = '';
                    if(vcal[0]!=0){
                        padvname = vcal[0]; 
                    }
                    if(vcal[1]!=0){
                        padvmob = vcal[1]; 
                    }
                    //document.getElementById('padvname').value= vcal[0];
                    //document.getElementById('padvmob').value=vcal[1];
                    document.getElementById('padvname').value = padvname;
                    document.getElementById('padvmob').value = padvmob;
                    document.getElementById('padvemail').value=vcal[2];
                    // $('#hd_p_barid').val(vcal[3]);
                     document.getElementById('hd_p_barid').value= vcal[3];
                     document.getElementById('padvyr').value= vcal[4];
                    
                } else {
                    //document.getElementById('padvname').value=vcal;
                    document.getElementById('padvname').value = "";
                    document.getElementById('padvmob').value ="";
                    document.getElementById('padvemail').value = "";
                    document.getElementById('hd_r_barid').value= "";
                    //  document.getElementById('radvyr').value= "";
                }
            }
        }
        var url = "Diary/get_adv_name"+"?advno="+document.getElementById('padvno').value+"&advyr="+
                document.getElementById('padvyr').value+"&ddl_pet_adv_state="+ddl_pet_adv_state+"&flag="+flag+'&padvt='+padvt;
        xmlhttp.open("GET",url,false);
//        if(document.getElementById('padvyr').value!='')
            xmlhttp.send(null); 
    }
    else if(flag=='R')
    {
         var ddl_res_adv_state=$('#ddl_res_adv_state').val();
          var radvt=$('#radvt').val();

        var ddl_res_adv_state=$('#ddl_res_adv_state').val();
        var ddl_res_adv_no=$('#radvno').val();
        var ddl_res_adv_yr=$('#radvyr').val();
        var ddl_pet_adv_isac='N';
        //alert(document.getElementById("is_ac").checked );
        //alert(document.getElementById("padvt").value );
        if(document.getElementById("ris_ac").checked == true)
            ddl_res_adv_isac='Y';
        if(document.getElementById("ris_ac").checked == true && document.getElementById("radvt").value=='N' && id=='radvyr'){
            if(document.getElementById("ddl_res_adv_state").value==''){
                alert('Please Select State');
                document.getElementById("ddl_res_adv_state").focus();
                return false;
            }
            if(document.getElementById("radvno").value==''){
                alert('Please enter Enrollment No.');
                document.getElementById("radvno").focus();
                return false;
            }
            if(document.getElementById("radvyr").value==''){
                alert('Please enter Enrollment Year');
                document.getElementById("radvyr").focus();
                return false;
            }

        }

        if(document.getElementById("radvno").value==''  && radvt=='A'){
            alert('Please enter AOR Code');
            //   document.getElementById("padvno").value='oo';
            //document.getElementById("radvno").focus();
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
                var vcal = xmlhttp.responseText;
                if(vcal!=0)
                {
                    vcal = vcal.split('~');
                    document.getElementById('radvname').value=vcal[0];
                    document.getElementById('radvmob').value=vcal[1];
                    document.getElementById('radvemail').value=vcal[2];
                     $('#hd_r_barid').val(vcal[3]);
                     document.getElementById('radvyr').value=vcal[4];
                }
                else{
                    document.getElementById('radvname').value = "";
                    document.getElementById('radvmob').value ="";
                    document.getElementById('radvemail').value = "";
                    // document.getElementById('radvyr').value= "";
                    document.getElementById('hd_r_barid').value= "";
                    //document.getElementById('radvname').value=vcal;
                }

            }
        }
        var url = "Diary/get_adv_name?advno="+document.getElementById('radvno').value+"&advyr="+
                document.getElementById('radvyr').value+"&ddl_res_adv_state="+ddl_res_adv_state+"&flag="+flag+'&radvt='+radvt;
        xmlhttp.open("GET",url,false);
//        if(document.getElementById('radvyr').value!='')
            xmlhttp.send(null); 
    }
}

function get_a_d_code(id)
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
            document.getElementById(id+"_code").value=xmlhttp.responseText;
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
    if ((charCode >= 48 && charCode<= 57)||charCode==9||charCode==8 || charCode == 37 || charCode == 39) {
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

function onlyalpha(evt) 
{
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    //alert(charCode);
    if ((charCode >= 65 && charCode<= 90)||(charCode >= 97 && charCode<= 122)||charCode==9||charCode==8||
            charCode==127||charCode==32||charCode==46||charCode==47||charCode==64 || charCode == 37 || charCode == 39) {
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
            ||charCode==9||charCode==8||charCode==127||charCode==32||charCode==46||charCode==47||charCode==64
            ||charCode==40||charCode==41|| charCode == 37 || charCode == 39) {
        return true;
    }
    return false;
}

function remove_apos(value,id){
    var string = value.replace("'","");
    string = string.replace("#","No");
    string = string.replace("&","and");
    $("#"+id).val(string);
}

function call_update_main()
{
//     alert("As per Hon'ble Court Order dated. 08-02-2018 in MA no. 1158/2017(Suraj India Trust Vs UOI), Registry is directed not to accept any application or petition on behalf of Suraj India Trust or Mr. Rajiv Daiya");
// alert("Registry is directed not to accept any application or petition on behalf of:- \n 1) Suraj India Trust or Mr. Rajiv Daiya as per Hon'ble Court Order dated. 08-02-2018 in MA no. 1158/2017(Suraj India Trust Vs UOI) \n 2) ASOK PANDE as per Hon'ble Court Order dated. 26-10-2018 in WP(C) No. 965/2018 (ASOK PANDE Vs UOI) \n 3) MANOHAR LAL SHARMA  as per Hon'ble Court Order dated. 07-12-2018 in WP(CRL) No. 315/2018 (MANOHAR LAL SHARMA Vs ARUN JAITLEY (AT PRESENT FINANCE MINISTER))");

alert("Registry is directed not to accept any application or petition on behalf of:- \n 1) Suraj India Trust or Mr. Rajiv Daiya as per Hon'ble Court Order dated. 08-02-2018 in MA no. 1158/2017(Suraj India Trust Vs UOI) \n 2) ASOK PANDE as per Hon'ble Court Order dated. 26-10-2018 in WP(C) No. 965/2018 (ASOK PANDE Vs UOI) \n 3) MANOHAR LAL SHARMA  as per Hon'ble Court Order dated. 07-12-2018 in WP(CRL) No. 315/2018 (MANOHAR LAL SHARMA Vs ARUN JAITLEY (AT PRESENT FINANCE MINISTER))\n" +
    " 4) P1-SURAJ MISHRA and P2-ROHIT GUPTA  as per Hon'ble Court Order dated. 08-05-2019 in WP(C) No. 1328/2018 (SURAJ MISHRA AND ANR VS. UNION OF INDIA AND ANR)");

    var ddl_nature=$('#ddl_nature').val();
    //alert(ddl_nature+' ddl nature value');

    var if_sclsc=0;
    if($("#if_sclsc").is(":checked"))
        if_sclsc=1;
//alert(if_sclsc);

    var if_efil=0;
    if($("#if_efil").is(":checked"))
        if_efil=1;
//    var fno = document.getElementById('fil_hd').value;
    var d_no=$('#t_h_cno').val();
    var d_yr=$('#t_h_cyt').val();
    var section=$('#section').val();
    var pet_type = document.getElementById('selpt').value;
    var pet_name,pet_rel,pet_rel_name,pet_sex,pet_age,pet_post,pet_deptt,pet_add,pcity,pdis,pst,tpet,pcont;
    
    var res_type = document.getElementById('selrt').value;
    var res_name,res_rel,res_rel_name,res_sex,res_age,res_post,res_deptt,res_add,rcity,rdis,rst,tres,rcont;
//    var chk_undertaking='N';
    var ddl_st_agncy=$('#ddl_st_agncy').val();
    var ddl_bench=$('#ddl_bench').val();
    var ddl_court=$('#ddl_court').val();
     var txt_doc_signed=$('#txt_doc_signed').val();
    var hd_current_date=$('#hd_current_date').val();
    var type_special_a=$('#type_special').val();
//    var ddl_doc_u=$('#ddl_doc_u').val();
//    var txt_undertakig=$('#txt_undertakig').val();

    var ddl_pet_adv_state=$('#ddl_pet_adv_state').val();
    var ddl_res_adv_state=$('#ddl_res_adv_state').val();
    if(ddl_court=='')
    {
       alert("Please select Court type");
        $('#ddl_court').focus();
        return false; 
    }
    if(ddl_st_agncy=='')
    {
        alert("Please select State");
        $('#ddl_st_agncy').focus();
        return false;
    }
    if(ddl_bench=='')
    {
        alert("Please select Bench");
        $('#ddl_bench').focus();
        return false; 
    }
    if($('#padvno').val()==887){
        if($('#txt_doc_signed').val()==''){
            alert("Please enter Date of document signed by jailer from Special type");
            $('#type_special').focus();
            return false;
        }
    }
    if(type_special_a=='6' && (txt_doc_signed=='' || txt_doc_signed.length<10))
 {
     alert("Please enter Date of document signed by jailer");
     $('#txt_doc_signed').focus();
       return false; 
      
 }
 if(type_special_a=='6' && txt_doc_signed!='' && txt_doc_signed.length==10)
 {
      var dt1 = parseInt(txt_doc_signed.substring(0, 2), 10);
    var mon1 = parseInt(txt_doc_signed.substring(3, 5), 10) - 1;
    var yr1 = parseInt(txt_doc_signed.substring(6, 10), 10);
    var date1 = new Date(yr1, mon1, dt1);
    var dt2 = parseInt(hd_current_date.substring(0, 2), 10);
    var mon2 = parseInt(hd_current_date.substring(3, 5), 10) - 1;
    var yr2 = parseInt(hd_current_date.substring(6, 10), 10);
    var date2 = new Date(yr2, mon2, dt2);
//    alert(date1+','+date2);
     if (date1 >date2) {
        alert(" Date of document signed by jailer should be less than current date");
        return false;
    }
 }
//if($('#chk_undertaking').is(':checked') && ddl_doc_u=='')
//    {
//        alert("Please select reason of Undertaking");
//        $('#txt_undertakig').focus();
//         return false; 
//    }
//if($('#chk_undertaking').is(':checked') && ddl_doc_u=='10' && txt_undertakig=='')
//    {
//        alert("Please enter reason of Undertaking");
//        $('#txt_undertakig').focus();
//         return false; 
//    }
    if(ddl_nature=='')
    {
        alert("Please select Case Type");
        $('#ddl_nature').focus();
        return false; 
    }
    if(section=='')
    {
        alert("Please select Section");
        $('#section').focus();
        return false;
    }

    txt_sclsc_no='';
    ddl_sclsc_yr='';
    if(if_sclsc==1)
    {
        var txt_sclsc_no=$('#txt_sclsc_no').val();
        var ddl_sclsc_yr=$('#ddl_sclsc_yr').val();
        if(txt_sclsc_no.trim()=='')
        {
            alert("Please enter SCLSC No.");
            $('#txt_sclsc_no').focus();
             return false; 
        }
        if(ddl_sclsc_yr=='')
        {
            alert("Please enter SCLSC Year");
            $('#ddl_sclsc_yr').focus();
             return false; 
        }
    }
    if(if_efil==1)
    {
        var txt_efil_no=$('#txt_efil_no').val();
        var ddl_efil_yr=$('#ddl_efil_yr').val();
        if(txt_efil_no.trim()=='')
        {
            alert("Please enter Efiling ack. No.");
            $('#txt_efil_no').focus();
            return false;
        }
        if(ddl_efil_yr=='')
        {
            alert("Please enter Efiling Year");
            $('#ddl_efil_yr').focus();
            return false;
        }
    }
//    if($('#chk_undertaking').is(':checked'))
//    {
//        chk_undertaking='Y';
//    }
var hd_mn='';
var cs_tp='';
var txtFNo='';
var txtYear='';
if($('#hd_mn').length && $('#cs_tp').length && $('#txtFNo').length && $('#txtYear').length)
    {
        hd_mn=$('#hd_mn').val();
        cs_tp=$('#cs_tp').val();
        txtFNo=$('#txtFNo').val();
         txtYear=$('#txtYear').val();
    }
    
    if(pet_type=="I")
    {
        pet_name = document.getElementById('pet_name');
        pet_rel = document.getElementById('selprel');
        pet_rel_name = document.getElementById('prel');
        pet_sex = document.getElementById('psex');
        pet_age = document.getElementById('page');
        pet_add = document.getElementById('paddi');
        pcity = document.getElementById('pcityi');
        pdis = document.getElementById('selpdisi');
        pst = document.getElementById('selpsti');
        pcont = document.getElementById('p_conti');
        tpet = document.getElementById('p_noi'); /*
        if(pet_name.value=='')
        {
            alert('Please Enter Petitioner Name');pet_name.focus();return false;
        } */
        /*if(pet_rel.value=='')
        {
            alert('Please Select Petitioner Relation');pet_rel.focus();return false;
        }
        if(pet_rel_name.value=='')
        {
            alert('Please Enter Petitioner Father/Husband Name');pet_rel_name.focus();return false;
        }
        if(pet_sex.value=='')
        {
            alert('Please Select Petitioner Sex');pet_sex.focus();return false;
        }
//        if(pet_age.value=='')
//        {
//            alert('Please Enter Petitioner Age');pet_age.focus();return false;
//        }*/ /*
        if(pet_add.value=='')
        {
            alert('Please Enter Petitioner Address');pet_add.focus();return false;
        }
        if(pcity.value=='')
        {
            alert('Please Enter Petitioner City');pcity.focus();return false;
        }
        if(pcont.value=='96'){
            if(pst.value=='')
            {
                alert('Please Select Petitioner State');pst.focus();return false;
            }
            if(pdis.value=='')
            {
                alert('Please Select Petitioner District');pdis.focus();return false;
            }
        }
        if(pcont.value=="")
        {
            alert('Please Enter Petitioner Country');pcont.focus();return false;
        }
        if(document.getElementById('pemaili').value!='')
        {
            var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
            if(document.getElementById('pemaili').value.match(mailformat))
            {
                //return true;
            }
            else
            {
                alert('Please enter valid email');
                document.getElementById('pemaili').focus();
                return false;
            }
        } */
        if(tpet.value==''||tpet.value==0)
        {
            alert('Total Pet(s) could not be null or zero');tpet.focus();return false;
        }
    }
    var pet_cause_title1=0; var pet_cause_title2=0; var pet_cause_title3=0;
    if(pet_type!="I")
    {
        pet_post = document.getElementById('pet_post');
        pet_deptt = document.getElementById('pet_deptt');
        pet_add = document.getElementById('paddd');
        pcity = document.getElementById('pcityd');
        pdis = document.getElementById('selpdisd');
        pst = document.getElementById('selpstd');
        tpet = document.getElementById('p_nod');
        pcont = document.getElementById('p_contd'); /*
        if($("#selpt").val()!='D3' && $("#pet_causetitle1").is(':checked')){
            pet_cause_title1=1;
            if($("#pet_statename").val() == '')
            {
                alert('Please Enter Petitioner Department State Name');
                $("#pet_statename").focus();
                return false;
            }
        }
        if($("#pet_causetitle2").is(':checked')){
            pet_cause_title2=1;
            if (pet_deptt.value == '')
            {
                alert('Please Enter Petitioner Department');
                pet_deptt.focus();
                return false;
            }
        }
        if($("#pet_causetitle3").is(':checked')){
            pet_cause_title3=1;
            if (pet_post.value == '')
            {
                alert('Please Enter Petitioner Post');
                pet_post.focus();
                return false;
            }
        }
        if(pet_cause_title1==0 && pet_cause_title2==0 && pet_cause_title3==0){
            alert('Select atleast One Cause Title for Petitioner');
            return false;
        }
        
        if(pet_add.value=='')
        {
            alert('Please Enter Petitioner Address');pet_add.focus();return false;
        }
        if(pcity.value=='')
        {
            alert('Please Enter Petitioner City');pcity.focus();return false;
        }
        if(pcont.value=='96'){
            if(pst.value=='')
            {
                alert('Please Select Petitioner State');pst.focus();return false;
            }
            if(pdis.value=='')
            {
                alert('Please Select Petitioner District');pdis.focus();return false;
            }
        }
        if(pcont.value=="")
        {
            alert('Please Enter Petitioner Country');pcont.focus();return false;
        }
        
        if(document.getElementById('pemaild').value!='')
        {
            var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
            if(document.getElementById('pemaild').value.match(mailformat))
            {
                //return true;
            }
            else
            {
                alert('Please enter valid email');
                document.getElementById('pemaild').focus();
                return false;
            }
        } */
        if(tpet.value==''||tpet.value==0)
        {
            alert('Total Pet(s) could not be null or zero');
            tpet.focus();return false;
        }
    }
    
    if(res_type=="I")
    {
        res_name = document.getElementById('res_name');
        res_rel = document.getElementById('selrrel');
        res_rel_name = document.getElementById('rrel');
        res_sex = document.getElementById('rsex');
        res_age = document.getElementById('rage');
        res_add = document.getElementById('raddi');
        rcity = document.getElementById('rcityi');
        rdis = document.getElementById('selrdisi');
        rst = document.getElementById('selrsti');
        tres = document.getElementById('r_noi');
        rcont = document.getElementById('r_conti'); /*
        if(res_name.value=='')
        {
            alert('Please Enter Respondent Name');res_name.focus();return false;
        } */
        /*if(res_rel.value=='')
        {
            alert('Please Select Respondent Relation');res_rel.focus();return false;
        }
        if(res_rel_name.value=='')
        {
            alert('Please Enter Respondent Father/Husband Name');res_rel_name.focus();return false;
        }
        if(res_sex.value=='')
        {
            alert('Please Select Respondent Sex');res_sex.focus();return false;
        }
//        if(res_age.value=='')
//        {
//            alert('Please Enter Respondent Age');res_age.focus();return false;
//        }*/
       /* if(res_add.value=='')
        {
            alert('Please Enter Respondent Address');res_add.focus();return false;
        }
        if(rcity.value=='')
        {
            alert('Please Enter Respondent City');rcity.focus();return false;
        }
        if(rcont.value=='96'){
            if(rst.value=='')
            {
                alert('Please Select Respondent State');rst.focus();return false;
            }
            if(rdis.value=='')
            {
                alert('Please Select Respondent District');rdis.focus();return false;
            }
        }
        if(rcont.value=="")
        {
            alert('Please Enter Respondent Country');rcont.focus();return false;
        }
        if(document.getElementById('remaili').value!='')
        {
            var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
            if(document.getElementById('remaili').value.match(mailformat))
            {
                //return true;
            }
            else
            {
                alert('Please enter valid email');
                document.getElementById('remaili').focus();
                return false;
            }
        } */
        if(tres.value==''||tres.value==0)
        {
            alert('Total Res(s) could not be null or zero');tres.focus();return false;
        }
    }
    var res_cause_title1=0; var res_cause_title2=0; var res_cause_title3=0;
    if(res_type!="I")
    {
        res_post = document.getElementById('res_post');
        res_deptt = document.getElementById('res_deptt');
        res_add = document.getElementById('raddd');
        rcity = document.getElementById('rcityd');
        rdis = document.getElementById('selrdisd');
        rst = document.getElementById('selrstd');
        tres = document.getElementById('r_nod');
        rcont = document.getElementById('r_contd'); /*
        if($("#selrt").val()!='D3' && $("#res_causetitle1").is(':checked')){
            res_cause_title1=1;
            if($("#res_statename").val() == '')
            {
                alert('Please Enter Respondent Department State Name');
                $("#res_statename").focus();
                return false;
            }
        }
        if($("#res_causetitle2").is(':checked')){
            res_cause_title2=1;
            if (res_deptt.value == '')
            {
                alert('Please Enter Respondent Department');
                res_deptt.focus();
                return false;
            }
        }
        if($("#res_causetitle3").is(':checked')){
            res_cause_title3=1;
            if (res_post.value == '')
            {
                alert('Please Enter Respondent Post');
                res_post.focus();
                return false;
            }
        }
        if(res_cause_title1==0 && res_cause_title2==0 && res_cause_title3==0){
            alert('Select atleast One Cause Title for Respondent');
            return false;
        }
        
        if(res_add.value=='')
        {
            alert('Please Enter Respondent Address');res_add.focus();return false;
        }
        if(rcity.value=='')
        {
            alert('Please Enter Respondent City');rcity.focus();return false;
        }
        if(rcont.value=='96'){
            if(rst.value=='')
            {
                alert('Please Select Respondent State');rst.focus();return false;
            }
            if(rdis.value=='')
            {
                alert('Please Select Respondent District');rdis.focus();return false;
            }
        }
        if(rcont.value=="")
        {
            alert('Please Enter Respondent Country');rcont.focus();return false;
        }
        
        if(document.getElementById('remaild').value!='')
        {
            var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
            if(document.getElementById('remaild').value.match(mailformat))
            {
                //return true;
            }
            else
            {
                alert('Please enter valid email');
                document.getElementById('remaild').focus();
                return false;
            }
        } */
        if(tres.value==''||tres.value==0)
        {
            alert('Total Res(s) could not be null or zero');tres.focus();return false;
        }
    }
    if ( document.getElementById('section').value == '')
        {
            alert("Please Select the Section");
            document.getElementById('section').focus();
            return false;
        }
    if(( document.getElementById('padvno').value=='' || document.getElementById('padvname').value=='') || document.getElementById('padvt').value!='SS' || document.getElementById('padvyr').value == ''){
        

        if ( document.getElementById('padvno').value == '')
        {
            alert("Please enter AOR code");
            //document.getElementById('padvno').focus();
            //$('#padvno').focus();
            return false;
        }
        if ( document.getElementById('padvname').value == '')
        {
            alert("Please enter valid AOR code");
            document.getElementById('padvno').focus();
            return false;
        }
        /*if(document.getElementById('ddl_res_adv_state').value == ''){
            alert("Please select the Enrol State ");
            document.getElementById('ddl_res_adv_state').focus();
            return false;
        }
        
        if ( document.getElementById('radvyr').value == '')
            {
                alert("Please enter Year");
                document.getElementById('radvyr').focus();
                return false;
            } */
                
    }

//    if(document.getElementById('padvname').value==''||document.getElementById('padvname').value==0)
//    {
//        alert('Please Enter Petitioner Advocate No and Year Properly');
//        document.getElementById('padvno').focus();return false;
//    }
//    if(document.getElementById('radvname').value==''||document.getElementById('radvname').value==0)
//    {
//        alert('Please Enter Respondent Advocate No and Year Properly');
//        document.getElementById('radvno').focus();return false;
//    }
    /*if(document.getElementById('padvemail').value!='')
    {
        var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
        if(document.getElementById('padvemail').value.match(mailformat))
        {
            //return true;
        }
        else
        {
            alert('Please enter valid email');
            document.getElementById('padvemail').focus();
            return false;
        }
    }
    if(document.getElementById('radvemail').value!='')
    {
        var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
        if(document.getElementById('radvemail').value.match(mailformat))
        {
            //return true;
        }
        else
        {
            alert('Please enter valid email');
            document.getElementById('radvemail').focus();
            return false;
        }
    }*/
    var hd_r_barid=$('#hd_r_barid').val();
var hd_p_barid= $('#hd_p_barid').val();

var chk_ext_entry=0;
var ext_address='';
$('.cl_add_P').each(function(){

    var idd= $(this).attr('id');
   var sp_idd=idd.split('txt_addressP');
   var txt_addressP=$('#txt_addressP'+sp_idd[1]).val();
   var txt_counrtyP=$('#txt_counrtyP'+sp_idd[1]).val();
    var txt_stateP=$('#txt_stateP'+sp_idd[1]).val();
     var txt_districtP=$('#txt_districtP'+sp_idd[1]).val();
    var  hd_main_id=0;
    if($('#hd_main_idP'+sp_idd[1]).length)
      hd_main_id=$('#hd_main_idP'+sp_idd[1]).val();

     /*
     if(txt_addressP!='')
     {
         if(txt_counrtyP=='')
         {
             alert("Please Select Country");
             $('#txt_counrtyP'+sp_idd[1]).focus();
             chk_ext_entry=1;
            return false;
         }
         if(txt_stateP=='' && txt_counrtyP=='96')
         {
             alert("Please Select State");
             $('#txt_stateP'+sp_idd[1]).focus();
             chk_ext_entry=1;
              return false;
         }
          if(txt_districtP=='' && txt_counrtyP=='96')
         {
             alert("Please Select District");
             $('#txt_districtP'+sp_idd[1]).focus();
             chk_ext_entry=1;
              return false;
         }
         if(ext_address=='')
         ext_address=txt_addressP+'~'+txt_counrtyP+'~'+txt_stateP+'~'+txt_districtP+'~'+hd_main_id;
     else 
          ext_address=ext_address+'^'+txt_addressP+'~'+txt_counrtyP+'~'+txt_stateP+'~'+txt_districtP+'~'+hd_main_id;
         
     } */
   
});
if(chk_ext_entry==1)
     return false;
 else 
 {
var ext_address_r='';
var chk_ext_entry_r=0;
$('.cl_add_R').each(function(){

    var idd= $(this).attr('id');
   var sp_idd=idd.split('txt_addressR');
   var txt_addressP=$('#txt_addressR'+sp_idd[1]).val();
   var txt_counrtyP=$('#txt_counrtyR'+sp_idd[1]).val();
    var txt_stateP=$('#txt_stateR'+sp_idd[1]).val();
     var txt_districtP=$('#txt_districtR'+sp_idd[1]).val();
      var  hd_main_id=0;
    if($('#hd_main_idR'+sp_idd[1]).length)
      hd_main_id=$('#hd_main_idR'+sp_idd[1]).val();/*
     if(txt_addressP!='')
     {
         if(txt_counrtyP=='')
         {
             alert("Please Select Country");
             $('#txt_counrtyR'+sp_idd[1]).focus();
             chk_ext_entry_r=1;
            return false;
         }
         if(txt_stateP=='' && txt_counrtyP=='96')
         {
             alert("Please Select State");
             $('#txt_stateR'+sp_idd[1]).focus();
             chk_ext_entry_r=1;
              return false;
         }
          if(txt_districtP=='' && txt_counrtyP=='96')
         {
             alert("Please Select District");
             $('#txt_districtR'+sp_idd[1]).focus();
             chk_ext_entry_r=1;
              return false;
         }
           if(ext_address_r=='')
         ext_address_r=txt_addressP+'~'+txt_counrtyP+'~'+txt_stateP+'~'+txt_districtP+'~'+hd_main_id;
     else 
          ext_address_r=ext_address_r+'^'+txt_addressP+'~'+txt_counrtyP+'~'+txt_stateP+'~'+txt_districtP+'~'+hd_main_id;
     } */
   
});

 }

    //priority added on 21-08-2023
    var priority_category=0;
    if($("select[name='ddl_priority'] option:selected").index()>0)
    {
        priority_category=$('#ddl_priority').val();
    }

//alert(ext_address+'$$'+ext_address_r);
if( chk_ext_entry_r==1)
    return false;
//    alert(hd_r_barid+'!'+hd_p_barid);
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
            document.getElementById('show_fil').innerHTML=res[1];
           // alert('Successfully updated data'+res[1]);
        }
    }
    var url = "controller=U&d_no="+d_no+"&d_yr="+d_yr+"&page="+document.getElementById('case_doc').value+
            "&ddl_st_agncy="+ddl_st_agncy+"&ddl_bench="+ddl_bench+"&hd_mn="+hd_mn+"&cs_tp="+cs_tp+"&txtFNo="+txtFNo+"&txtYear="+txtYear+
            "&ddl_court="+ddl_court+"&ddl_nature="+ddl_nature+"&ddl_pet_adv_state="+ddl_pet_adv_state+"&ddl_res_adv_state="+ddl_res_adv_state+"&hd_r_barid="+hd_r_barid+"&hd_p_barid="+hd_p_barid+"&ext_address="+ext_address+"&ext_address_r="+ext_address_r+
    "&txt_doc_signed="+txt_doc_signed+'&txt_sclsc_no='+txt_sclsc_no+'&ddl_sclsc_yr='+ddl_sclsc_yr+"&section="+section;
    
    
    if(pet_type=="I")
        url = url+"&pname="+pet_name.value+"&pet_rel="+pet_rel.value+"&pet_rel_name="+pet_rel_name.value+"&p_sex="+pet_sex.value
    +"&p_age="+pet_age.value+"&pocc="+document.getElementById('pocc').value+"&pp="+document.getElementById('ppini').value
    +"&pmob="+document.getElementById('pmobi').value+"&pemail="+document.getElementById('pemaili').value;
    
    if($("#pet_statename").val()=='' || $("#pet_statename").val()=='')
        $("#pet_statename_hd").val('0');
    if($("#res_statename").val()=='' || $("#res_statename").val()=='')
        $("#res_statename_hd").val('0');
    
    if(pet_type!="I")
        url = url+"&pet_post="+pet_post.value+"&pet_deptt="+pet_deptt.value+"&pp="+document.getElementById('ppind').value
    +"&pmob="+document.getElementById('pmobd').value+"&pemail="+document.getElementById('pemaild').value
    +"&pet_statename=" + document.getElementById('pet_statename').value+"&pet_statename_hd=" + document.getElementById('pet_statename_hd').value
    
    url = url+"&padd="+pet_add.value+"&pcity="+pcity.value+"&pdis="+pdis.value+"&pst="+pst.value+"&p_type="+pet_type+ "&p_cont=" + pcont.value;
    
    
    if(res_type=="I")
        url = url+"&rname="+res_name.value+"&res_rel="+res_rel.value+"&res_rel_name="+res_rel_name.value+"&r_sex="+res_sex.value
    +"&r_age="+res_age.value+"&rocc="+document.getElementById('rocc').value+"&rp="+document.getElementById('rpini').value
    +"&rmob="+document.getElementById('rmobi').value+"&remail="+document.getElementById('remaili').value;
    if(res_type!="I")
        url = url+"&res_post="+res_post.value+"&res_deptt="+res_deptt.value+"&rp="+document.getElementById('rpind').value
    +"&rmob="+document.getElementById('rmobd').value+"&remail="+document.getElementById('remaild').value
    +"&res_statename=" + document.getElementById('res_statename').value+"&res_statename_hd=" + document.getElementById('res_statename_hd').value;
    
    url = url+"&radd="+res_add.value+"&rcity="+rcity.value+"&rdis="+rdis.value+"&rst="+rst.value+"&r_type="+res_type+ "&r_cont=" + rcont.value;

    if(document.getElementById('padvt').value=='S' || document.getElementById('padvt').value=='C')
        url = url+"&padvno="+document.getElementById('padvno').value+"&padvyr="+document.getElementById('padvyr').value;
    
    url = url+"&padvname="+document.getElementById('padvname').value+"&padvmob="+document.getElementById('padvmob').value
    +"&padvemail="+document.getElementById('padvemail').value;
    
    if(document.getElementById('radvt').value=='S' || document.getElementById('radvt').value=='C')
        url = url+"&radvno="+document.getElementById('radvno').value+"&radvyr="+document.getElementById('radvyr').value;
    
    url = url+"&radvname="+document.getElementById('radvname').value+"&radvmob="+document.getElementById('radvmob').value
    +"&radvemail="+document.getElementById('radvemail').value;
    
    url = url+"&padtype="+document.getElementById('padvt').value+"&radtype="+document.getElementById('radvt').value;
    
    url = url+"&pp_code="+document.getElementById('pet_post_code').value+"&rp_code="+document.getElementById('res_post_code').value
    +"&t_pet="+tpet.value+"&t_res="+tres.value
    +"&type_special="+document.getElementById('type_special').value
    +"&pd_code=" + document.getElementById('pet_deptt_code').value + "&rd_code=" + document.getElementById('res_deptt_code').value;
    
    url = url+"&p_cause_t1="+pet_cause_title1+"&p_cause_t2="+pet_cause_title2+"&p_cause_t3="+pet_cause_title3
            +"&r_cause_t1="+res_cause_title1+"&r_cause_t2="+res_cause_title2+"&r_cause_t3="+res_cause_title3;
    
    url = url+"&if_sclsc="+if_sclsc;

    if(document.getElementById("is_ac").checked == true)
        url = url+"&is_ac="+'Y';
    else
        url = url+"&is_ac="+'';
    if(document.getElementById("ris_ac").checked == true)
        url = url+"&ris_ac="+'Y';
    else
        url = url+"&ris_ac="+'';

    if(if_efil) {
        url=url+'&if_efil='+if_efil+'&txt_efil_no=' + txt_efil_no + '&ddl_efil_yr=' + ddl_efil_yr ;
    }
    //if(priority_category!=0){
        url=url+'&priority_category='+priority_category;
    //}
    //var state_department_in_pet = $("#state_department_in_pet").val().split('->');
    //var state_department_in_res = $("#state_department_in_res").val().split('->');
    
    //url = url+"&pd_code="+state_department_in_pet[0]+"&rd_code="+state_department_in_res[0];
    
//    if(fno.substr(3,2)=='52')
//    {
//        url = url+"&bailno="+document.getElementById('bno').value+"&subcat1=";
//        if(document.getElementById('rbtn4').checked)
//            url = url+"4";
//        else if(document.getElementById('rbtn5').checked)
//            url = url+"5";
//        else
//            url = url+"0";
//    }
    
   // alert(url);

   var CSRF_TOKEN = 'CSRF_TOKEN';
   var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
   const myArray = url.split("&");
   
   var result = '';
   var  output = Object.create(null);
   $.each(myArray, function(i,value) {
        result = value.split("=");
        console.log(result);
        output[result[0]] = result[1];
        
   });
  
   output[CSRF_TOKEN] = CSRF_TOKEN_VALUE;

   let action = baseURL + "/Filing/Diary_modify/save_new_filing";
   if (confirm('Do you want to update data?')) {
        $.ajax({
            url: action,
            type: "POSt",
            data : output,
            success: function(response) {
                console.log(response);
                res = response.split('!~!');
                document.getElementById('show_fil').innerHTML=res[1];
                updateCSRFToken();
            },
            error: function(error) {
                updateCSRFToken();
                alert("ERROR, Please Contact Server Room"); 
            }
        });
    } else {
        return false;
    }

     
   //console.log(output)

    //alert('Do you want to update data');
    //url = encodeURI(url);
    //xmlhttp.open("POST",output,false);
    //xmlhttp.send(null); 
}

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

function getDistrict(side,id,val){
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
            if(side=='P'){
                if(id=='selpsti')
                    document.getElementById('selpdisi').innerHTML=xmlhttp.responseText;
                else if(id=='selpstd')
                    document.getElementById('selpdisd').innerHTML=xmlhttp.responseText;
            }
            else if(side=='R'){
                if(id=='selrsti')
                    document.getElementById('selrdisi').innerHTML=xmlhttp.responseText;
                else if(id=='selrstd')
                    document.getElementById('selrdisd').innerHTML=xmlhttp.responseText;
            }
        }
    }
    xmlhttp.open("GET","get_district.php?state="+val,true);
    xmlhttp.send(null);
}

$(document).ready(function() {

    $(document).on('change', '#ddl_nature', function()
    {
        var casetype=document.getElementById('ddl_nature').value;
        document.getElementById("c").checked = true;
        //   alert(casetype);
        //if(casetype==9 || casetype==10 ||casetype==19||casetype==25||casetype==26 || casetype==20 || casetype==39)
        if(casetype!=9 && casetype!=10 && casetype!=19 && casetype!=25 && casetype!=26 && casetype!=20 && casetype!=39)
        {
         f();
          
        }
        // alert("this is onchange function");
       

    });

    $(document).on('change', '#ddl_st_agncy,#ddl_court', function() {
        var ddl_st_agncy =$('#ddl_st_agncy :selected').val();
        var ddl_court =$('#ddl_court :selected').val();
        if(ddl_court ==4){get_benches('1');}else{get_benches('0');}
         //f();
    });
   /* $(document).on('change', '#ddl_bench', function() {
        var ddl_st_agncy = $('#ddl_st_agncy').val();
        var ddl_bench = $('#ddl_bench').val();
        var ddl_court=$('#ddl_court').val();
        $.ajax({
            url: 'get_case_strc.php',
            cache: false,
            async: true,
            data: {ddl_st_agncy: ddl_st_agncy, ddl_bench: ddl_bench,ddl_court:ddl_court},
            beforeSend: function() {
                $('#dv_ent_z').html('<table widht="100%" align="center"><tr><td><img src="../images/preloader.gif"/></td></tr></table>');
            },
            type: 'POST',
            success: function(data, status) {

                $('#dv_case_no').html(data);


            },
            error: function(xhr) {
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }

        });
        f();
    });*/

    $(document).on('click', '.cl_rdn_p', function() {

        var idd = $(this).attr('id');

        var sp_idd = idd.split('rdn_p');
        var rdn_p_r = $('#rdn_p' + sp_idd[1]).val();
//        alert('sdsdsd' + rdn_p_r);
        var f_no = $('#hd_fil_no' + sp_idd[1]).val();
        var hd_pet_res = $('#hd_pet_res' + sp_idd[1]).val();
        var hd_sr_no = $('#hd_sr_no' + sp_idd[1]).val();
        var sp_partyname = $('#sp_partyname' + sp_idd[1]).html();
        var hd_ind_dep = $('#hd_ind_dep' + sp_idd[1]).val();
        var hd_sonof = $('#hd_sonof' + sp_idd[1]).val();
        var hd_prfhname = $('#hd_prfhname' + sp_idd[1]).val();
        var hd_sex = $('#hd_sex' + sp_idd[1]).val();
        var hd_age = $('#hd_age' + sp_idd[1]).val();
        var hd_addr1 = $('#hd_addr1' + sp_idd[1]).val();
        var hd_addr2 = $('#hd_addr2' + sp_idd[1]).val();
        var hd_dstname = $('#hd_dstname' + sp_idd[1]).val();
        var hd_pin = $('#hd_pin' + sp_idd[1]).val();
        var hd_state = $('#hd_state' + sp_idd[1]).val();
        var hd_city = $('#hd_city' + sp_idd[1]).val();
        var hd_contact = $('#hd_contact' + sp_idd[1]).val();
        var hd_email = $('#hd_email' + sp_idd[1]).val();
          var hd_deptcode=$('#hd_deptcode'+ sp_idd[1]).val();
        $('.cl_rdn_p').each(function() {
            if (idd == $(this).attr('id'))
                $(this).prop('checked', true);
            else
                $(this).prop('checked', false);
        });
        get_part_detail(f_no, hd_pet_res, hd_sr_no, sp_partyname, rdn_p_r, hd_ind_dep, hd_sonof, hd_prfhname, hd_sex, hd_age, hd_addr1, hd_addr2,
                hd_dstname, hd_pin, hd_state, hd_city, hd_contact, hd_email,hd_deptcode);

    });
    $(document).on('click', '.cl_rdn_r', function() {
        var idd = $(this).attr('id');
        var sp_idd = idd.split('rdn_r');
        var rdn_p_r = $('#rdn_r' + sp_idd[1]).val();
        var f_no = $('#hd_fil_no' + sp_idd[1]).val();
        var hd_pet_res = $('#hd_pet_res' + sp_idd[1]).val();
        var hd_sr_no = $('#hd_sr_no' + sp_idd[1]).val();
        var sp_partyname = $('#sp_partyname' + sp_idd[1]).html();
        var hd_ind_dep = $('#hd_ind_dep' + sp_idd[1]).val();
        var hd_sonof = $('#hd_sonof' + sp_idd[1]).val();
        var hd_prfhname = $('#hd_prfhname' + sp_idd[1]).val();
        var hd_sex = $('#hd_sex' + sp_idd[1]).val();
        var hd_age = $('#hd_age' + sp_idd[1]).val();
        var hd_addr1 = $('#hd_addr1' + sp_idd[1]).val();
        var hd_addr2 = $('#hd_addr2' + sp_idd[1]).val();
        var hd_dstname = $('#hd_dstname' + sp_idd[1]).val();
        var hd_pin = $('#hd_pin' + sp_idd[1]).val();
        var hd_state = $('#hd_state' + sp_idd[1]).val();
        var hd_city = $('#hd_city' + sp_idd[1]).val();
        var hd_contact = $('#hd_contact' + sp_idd[1]).val();
        var hd_email = $('#hd_email' + sp_idd[1]).val();
        var hd_deptcode=$('#hd_deptcode'+ sp_idd[1]).val();
        $('.cl_rdn_r').each(function() {
            if (idd == $(this).attr('id'))
                $(this).prop('checked', true);
            else
                $(this).prop('checked', false);
        });
        get_part_detail(f_no, hd_pet_res, hd_sr_no, sp_partyname, rdn_p_r, hd_ind_dep, hd_sonof, hd_prfhname, hd_sex, hd_age, hd_addr1, hd_addr2,
                hd_dstname, hd_pin, hd_state, hd_city, hd_contact, hd_email,hd_deptcode);
    });
    
    
      $(document).on('change', '#ddl_court', function() {
      
          var idd= $(this).val();
            
         if(idd=='4')
             {
                 $('#ddl_st_agncy').val('490506');
                
                 get_benches('1');
             }
      });
//      $(document).on('click','#chk_undertaking',function(){
//       
//          if($(this).is(':checked'))
//              {
////                  $('#txt_undertakig').attr('disabled',false);
//                  $('#ddl_doc_u').attr('disabled',false);
//              }
//              else 
//                  {
////                       $('#txt_undertakig').attr('disabled',true);
//                        $('#ddl_doc_u').attr('disabled',true);
//                  }
//                  $('#txt_undertakig').val(''); 
//                  $('#ddl_doc_u').val('');
//      });
      
//      $(document).on('change','#ddl_doc_u',function(){
//          var ddl_doc_u=$('#ddl_doc_u').val();
//          if(ddl_doc_u=='10')
//              {
//              $('#txt_undertakig').attr('disabled',false);
//               $('#txt_undertakig').focus();
//              }
//          else 
//             $('#txt_undertakig').attr('disabled',true); 
//          $('#txt_undertakig').val('');
//      });
      $(document).on('change','#ddl_pet_adv_state,#ddl_res_adv_state',function(){
      var idd=  $(this).attr('id');
      if(idd=='ddl_pet_adv_state')
          {
          $('#padvno').val('');
       $('#padvyr').val('');
       $('#padvname').val('');
       $('#padvmob').val('');
        $('#padvemail').val('');
       if( $(this).val()=='')
           {
       $('#padvno').attr('disabled',true);
       $('#padvyr').attr('disabled',true);
       $('#padvname').attr('disabled',true);
       $('#padvmob').attr('disabled',true);
       $('#padvemail').attr('disabled',true);
           }
           else
               {
         $('#padvno').attr('disabled',false);
       $('#padvyr').attr('disabled',false);
       $('#padvname').attr('disabled',false);
       $('#padvmob').attr('disabled',false);
       $('#padvemail').attr('disabled',false);
               }
          }
      else if(idd=='ddl_res_adv_state')
          {
           $('#radvno').val('');
       $('#radvyr').val('');
       $('#radvname').val('');
       $('#radvmob').val('');
         $('#radvemail').val('');
          if( $(this).val()=='')
           {
       $('#radvno').attr('disabled',true);
       $('#radvyr').attr('disabled',true);
       $('#radvname').attr('disabled',true);
       $('#radvmob').attr('disabled',true);
        $('#radvemail').attr('disabled',true);
           }
           else 
               {
        $('#radvno').attr('disabled',false);
       $('#radvyr').attr('disabled',false);
       $('#radvname').attr('disabled',false);
       $('#radvmob').attr('disabled',false);
        $('#radvemail').attr('disabled',false);
               }
          }
      });
    
      $(document).on('click','#ad_address,#ad_address_r',function(){
          var CSRF_TOKEN = 'CSRF_TOKEN';
          var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

          var idd=$(this).attr('id');
//          alert(idd);
          var p_r='';
          if(idd=='ad_address')
          {
            $('#ad_address').attr('disabled',true);
              var hd_add_address=$('#hd_add_address').val();
              p_r='P';
          }
        else if(idd=='ad_address_r')
        {
            $('#ad_address_r').attr('disabled',true);
              var hd_add_address=$('#hd_add_address_r').val();
              p_r='R';
          }
          //alert('update additional_address');
          $.ajax({
            url: '/Filing/Diary/additional_address',
            cache: false,
            async: true,
            data: {hd_add_address: hd_add_address,p_r:p_r,CSRF_TOKEN: CSRF_TOKEN_VALUE},
            
            type: 'GET',
            success: function(data, status) {

                  if(idd=='ad_address')
                  {
                $('#dv_add_parties').append(data);
                $('#hd_add_address').val(parseInt(hd_add_address)+1);
               $('#ad_address').attr('disabled',false);   
                  }
                else if(idd=='ad_address_r')
                  {
                $('#dv_add_parties_r').append(data);
                $('#hd_add_address_r').val(parseInt(hd_add_address)+1);
               $('#ad_address_r').attr('disabled',false);   
                  }
                  
                   
               }

        });
      });
      $(document).on('click','.cl_deletesP,.cl_deletesR',function(){
          var cn_rec=confirm("Are you sure you want to delete record");
          if(cn_rec==true)
          {
              var CSRF_TOKEN = 'CSRF_TOKEN';
              var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
              var hd_main_idP = $(this).data("id");
              var type_p_r = $(this).data("type_p_r");
              var row=type_p_r+hd_main_idP
            //alert('hd_main_idP='+hd_main_idP+' type_p_r='+type_p_r);
              $.ajax({
            url: '/Filing/Diary_modify/delete_additional_address',
            cache: false,
            async: true,
            data: {hd_main_idP: hd_main_idP,CSRF_TOKEN: CSRF_TOKEN_VALUE},
            
            type: 'GET',
            success: function(data, status) {

                 alert(data);
                $('#row_additional_address_'+type_p_r+hd_main_idP).css('display','none');
                updateCSRFToken();
               }

        });
    }
      });
      
       $(document).on('change','#type_special',function(){
          var v_val=$(this).val();
          if(v_val==6)
              $('#sp_doc_signed').css('display','inline');
          else
               $('#sp_doc_signed').css('display','none');
           $('#txt_doc_signed').val('');
      });
});

function get_benches(str)
{
    var CSRF_TOKEN = 'CSRF_TOKEN';
    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
    var ddl_st_agncy =$('#ddl_st_agncy :selected').val();
    var ddl_court =$('#ddl_court :selected').val();
        if(ddl_st_agncy!='' && ddl_court!='')
            
               {
      
        $.ajax({
           // url: '/Common/Ajaxcalls/get_hc_bench_list',
            url: '/Filing/Diary_modify/get_hc_bench_list',
            cache: false,
            async: true,
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, high_court_id: ddl_st_agncy, court_type: ddl_court},
            type: 'GET',
            success: function(data, status) {
                updateCSRFToken();
                $('#ddl_bench').html(data);
                f();
                if(str==1)
                {
                    $('#ddl_bench').val('10000');
                    $('#ddl_st_agncy').attr('disabled',true);
                }
                else
                {
                    $('#ddl_bench').val('');
                    //   $('#ddl_st_agncy').val('')
                    $('#ddl_st_agncy').attr('disabled',false);
                }


            }

        });
               }
}

function getDetails()
{
//   call_fullReset_main();
    var hd_mn = $('#hd_mn').val();
    var cs_tp = $('#cs_tp').val();
    var txtFNo = $('#txtFNo').val();
    var txtYear = $("#txtYear").val();
    $.ajax({
        url: 'get_parties.php',
        cache: false,
        async: true,
        data: {hd_mn: hd_mn, txtFNo: txtFNo, txtYear: txtYear, cs_tp: cs_tp},
        beforeSend: function() {
            $('#dv_ent_z').html('<table widht="100%" align="center"><tr><td><img src="../images/preloader.gif"/></td></tr></table>');
        },
        type: 'POST',
        success: function(data, status) {

            $('#dv_parties').html(data);
          },
        error: function(xhr) {
            alert("Error: " + xhr.status + " " + xhr.statusText);
        }

    });
}

function f()

{
    //  alert("this is a function for showing tentative section of a matter when diarized");

    var court= document.getElementById('ddl_court').value;
    //alert('court type ='+court);
    if (court !=4) {
        var state = document.getElementById('ddl_st_agncy').value;
        var bench = document.getElementById('ddl_bench').value;
        var nature = document.getElementById('ddl_nature').value;
        var data = court + "-" + state + "-" + bench + "-" + nature;

        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        }
        else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                //alert(this.responseText);

                //    document.getElementById("sec").value = this.responseText;

                if (this.responseText.trim()!=0){
                    $('#section').val(this.responseText.trim());
                    //document.getElementById('section').value=this.responseText;
                }else{
                    $('#section').prop('selectedIndex',0);
                }


            }
        };
        var url = "/Filing/Diary/getsection?q=" + data;

        xmlhttp.open("GET", url, true);
        xmlhttp.send();
    }

}

function com_filingNo()
{
    var txtNo = document.getElementById('txtFNo').value;
    if (txtNo.length == "1")
    {
        txtNo = "0000" + txtNo;
    }
    else if (txtNo.length == "2")
    {
        txtNo = "000" + txtNo;
    }
    else if (txtNo.length == "3")
    {
        txtNo = "00" + txtNo;
    }
    else if (txtNo.length == "4")
    {
        txtNo = "0" + txtNo;
    }
    document.getElementById('txtFNo').value = txtNo;
}

function get_part_detail(f_no, hd_pet_res, hd_sr_no, sp_partyname, rdn_p_r, hd_ind_dep, hd_sonof, hd_prfhname, hd_sex,
        hd_age, hd_addr1, hd_addr2, hd_dstname, hd_pin, hd_state, hd_city, hd_contact, hd_email,hd_deptcode)
{

  
    if (rdn_p_r == 'P')
    {
     
        $('#selpt').val(hd_ind_dep);
         activate_main('selpt');
        if(hd_ind_dep=='I')
          {
        $('#pet_name').val(sp_partyname);
       
        $('#selprel').val(hd_sonof);
        $('#prel').val(hd_prfhname);
        $('#psex').val(hd_sex);
        $('#page').val(hd_age);
          $('#pocc').val(hd_addr1);
         
      
        $('#paddi').val(hd_addr2);
        $('#pcityi').val(hd_dstname);
        $('#ppini').val(hd_pin);
        $('#selpsti').val(hd_state);
        $('#selpdisi').val(hd_city);
        $('#pmobi').val(hd_contact);
        $('#pemaili').val(hd_email);
         }
         else  if(hd_ind_dep=='D1' || hd_ind_dep=='D2' || hd_ind_dep=='D3')
             {
                 $('#pet_deptt').val(sp_partyname);
                 if(hd_addr1=='')
                     hd_addr1=sp_partyname;
                 $('#pet_post').val(hd_addr1);
            //if(hd_ind_dep=='D1')  
            //$('#state_department_in_pet').val(hd_deptcode);
               $('#paddd').val(hd_addr2);
               $('#pcityd').val(hd_dstname);
               $('#ppind').val(hd_pin);
               $('#selpstd').val(hd_state);
               $('#selpdisd').val(hd_city);
               $('#pmobd').val(hd_contact);
               $('#pemaild').val(hd_email);
             }
    }
    else if (rdn_p_r == 'R')
    {
        $('#selrt').val(hd_ind_dep);
          activate_main('selrt');
          if(hd_ind_dep=='I')
          {
        $('#res_name').val(sp_partyname);
       
        $('#selrrel').val(hd_sonof);
        $('#rrel').val(hd_prfhname);
           $('#rsex').val(hd_sex);
        $('#rage').val(hd_age);
        $('#rocc').val(hd_addr1);
        $('#raddi').val(hd_addr2);
        $('#rcityi').val(hd_dstname);
        $('#rpini').val(hd_pin);
        $('#selrstd').val(hd_state);
        $('#selrdisi').val(hd_city);
        $('#rmobi').val(hd_contact);
        $('#remaili').val(hd_email);
          }
            else  if(hd_ind_dep=='D1' || hd_ind_dep=='D2' || hd_ind_dep=='D3')
             {
                 $('#res_deptt').val(sp_partyname);
                   if(hd_addr1=='')
                     hd_addr1=sp_partyname;
                      $('#res_post').val(hd_addr1);
                      if(hd_ind_dep=='D1')
                       $('#state_department_in_res').val(hd_deptcode);
                         $('#raddd').val(hd_addr2);
                          $('#rcityd').val(hd_dstname);
                             $('#rpind').val(hd_pin);
                                $('#selrstd').val(hd_state);
                                  $('#selrdisd').val(hd_city);
                                   $('#rmobd').val(hd_contact);
                                    $('#remaild').val(hd_email);
             }
    }
}

function call_fullReset_main()
{
    if (document.getElementById('selpt').value == 'I')
    {
        document.getElementById('pet_name').value = "";
        document.getElementById('selprel').value = "";
        document.getElementById('prel').value = "";
        document.getElementById('psex').value = "";
        document.getElementById('page').value = "";
        document.getElementById('pocc').value = "";
        document.getElementById('paddi').value = "";
        document.getElementById('pcityi').value = "";
        document.getElementById('ppini').value = "";
        document.getElementById('selpdisi').value = "";
        document.getElementById('selpsti').value = "23";
        document.getElementById('pmobi').value = "";
        document.getElementById('pemaili').value = "";
        document.getElementById('p_noi').value = "1";
    }
    else if (document.getElementById('selpt').value != 'I')
    {
        document.getElementById('pet_post').value = "";
        document.getElementById('pet_deptt').value = "";
        document.getElementById('paddd').value = "";
        document.getElementById('pcityd').value = "";
        document.getElementById('ppind').value = "";
        document.getElementById('selpdisd').value = "";
        document.getElementById('selpstd').value = "23";
        document.getElementById('pmobd').value = "";
        document.getElementById('pemaild').value = "";
        document.getElementById('p_nod').value = "1";
    }
    if (document.getElementById('selrt').value == 'I')
    {
        document.getElementById('res_name').value = "";
        document.getElementById('selrrel').value = "";
        document.getElementById('rrel').value = "";
        document.getElementById('rsex').value = "";
        document.getElementById('rage').value = "";
        document.getElementById('rocc').value = "";
        document.getElementById('raddi').value = "";
        document.getElementById('rcityi').value = "";
        document.getElementById('rpini').value = "";
        document.getElementById('selrdisi').value = "";
        document.getElementById('selrsti').value = "23";
        document.getElementById('rmobi').value = "";
        document.getElementById('remaili').value = "";
        document.getElementById('r_noi').value = "1";
    }
    else if (document.getElementById('selrt').value != 'I')
    {
        document.getElementById('res_post').value = "";
        document.getElementById('res_deptt').value = "";
        document.getElementById('raddd').value = "";
        document.getElementById('rcityd').value = "";
        document.getElementById('rpind').value = "";
        document.getElementById('selrdisd').value = "";
        document.getElementById('selrstd').value = "23";
        document.getElementById('rmobd').value = "";
        document.getElementById('remaild').value = "";
        document.getElementById('r_nod').value = "1";
    }
    document.getElementById('selpt').value = 'I';
    document.getElementById('selrt').value = 'I';
//    document.getElementById('selct').value='-1'
//    document.getElementById('case_doc').value='';
    document.getElementById('padvno').value = "";
    document.getElementById('padvyr').value = "";
    document.getElementById('padvname').value = "";
    document.getElementById('padvmob').value = "";
    document.getElementById('padvemail').value = "";
    document.getElementById('radvno').value = "";
    document.getElementById('radvyr').value = "";
    document.getElementById('radvname').value = "";
    document.getElementById('radvmob').value = "";
    document.getElementById('radvemail').value = "";
    document.getElementById('for_I_p').style.display = 'block';
    document.getElementById('for_D_p').style.display = 'none';
    document.getElementById('for_I_r').style.display = 'block';
    document.getElementById('for_D_r').style.display = 'none';
}
function check_country(idd,str_val)
{
    var idd=idd.split('txt_counrty');
    if(str_val=='96')
    {
        $('#txt_state'+idd[1]).attr('disabled',false);
        $('#txt_district'+idd[1]).attr('disabled',false);
    }
    else 
    {
        $('#txt_state'+idd[1]).attr('disabled',true);
        $('#txt_district'+idd[1]).attr('disabled',true);
    }
    $('#txt_state'+idd[1]).val('');
     $('#txt_district'+idd[1]).val('');
}
function get_additional_dis(idd,str_val)
{
    var idd=idd.split('txt_state');
    $.ajax({
            url: '../Common/Ajaxcalls/get_districts',
            cache: false,
            async: true,
            data: {state_id: str_val},
           
            type: 'GET',
            success: function(data, status) {

               $('#txt_district'+idd[1]).html(data);
                   
               },
            error: function(xhr) {
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }

        });
}

$(document).ready(function(){
    $(document).on('click','#if_sclsc',function(){
        if($(this).is(':checked'))
        {
            $('#sp_no_yr').css('display','inline');
        }
        else 
        {
              $('#sp_no_yr').css('display','none');
        }
        $('#txt_sclsc_no').val('');
        $('#ddl_sclsc_yr').val('');
    });
    $(document).on('click','#if_efil',function(){
        if($(this).is(':checked'))
        {
            $('#sp_efil_yr').css('display','inline');
        }
        else
        {
            $('#sp_efil_yr').css('display','none');
        }
        $('#txt_efil_no').val('');
        $('#ddl_efil_yr').val('');
    });
});

function is_changeAdvocate(id,val)
{
    if(id=='padvt')
    {

        if(val=='S')
        {
            document.getElementById('padvno').style.display='inline';
            document.getElementById('padvno_').style.display='inline';
            document.getElementById('padvyr').style.display='inline';
            document.getElementById('padvyr_').style.display='inline';
            document.getElementById('padvname').disabled=true;
            document.getElementById('padvname').value='';
            document.getElementById('padvmob').value='';
            document.getElementById('padvmob').style.display='inline';
            document.getElementById('padvmob_').style.display='inline';
            document.getElementById('padvemail').value='';
            document.getElementById('padvemail').style.display='inline';
            document.getElementById('padvemail_').style.display='inline';
            $('#padvyr').attr('disabled',false);
            $('#ddl_pet_adv_state').attr('disabled',false);

        }
        if(val == 'N')
        {
            document.getElementById('padvno').style.display = 'inline';
            document.getElementById('padvno_').style.display = 'inline';
            document.getElementById('padvyr').style.display = 'inline';
            document.getElementById('padvyr_').style.display = 'inline';
            document.getElementById('padvmob').style.display = "inline";
            document.getElementById('padvmob_').style.display = "inline";
            document.getElementById('padvemail').style.display = 'inline';
            document.getElementById('padvemail_').style.display = 'inline';
            document.getElementById('padvname').disabled = true;
            document.getElementById('padvname').value = '';
            document.getElementById('padvmob').value = '';
            document.getElementById('padvemail').value = '';
            $('#padvyr').attr('disabled',false);
            $('#ddl_pet_adv_state').attr('disabled',false);
            $('#is_ac').attr('disabled',false);
            $('#is_ac').prop('checked',true);
        }
        else if(val=='A')
        {

            document.getElementById('padvno').style.display='inline';
            document.getElementById('padvno_').style.display='inline';
            document.getElementById('padvyr').style.display='inline';
            document.getElementById('padvyr_').style.display='inline';
            document.getElementById('padvmob').style.display='inline';
            document.getElementById('padvmob_').style.display='inline';
            document.getElementById('padvemail').style.display='inline';
            document.getElementById('padvemail_').style.display='inline';
            $('#is_ac').attr('disabled',false);
            $('#is_ac').prop('checked',false);

        }
        else if(val!='S')
        {
            if(val=='SS'){
                document.getElementById('padvno').style.display='none';
                document.getElementById('padvno_').style.display='none';
                document.getElementById('padvno').value='';
                document.getElementById('padvyr').style.display='none';
                document.getElementById('padvyr_').style.display='none';
                document.getElementById('padvyr').value='';
                document.getElementById('padvname').disabled=false;
                document.getElementById('padvmob').style.display='none';
                document.getElementById('padvmob_').style.display='none';
                document.getElementById('padvemail').style.display='none';
                document.getElementById('padvemail_').style.display='none';

            }

            if(val=='C')
            {

            }
            else if(val=='SS')
            {

            }
            $('#ddl_pet_adv_state').attr('disabled',true);
        }
    }
    else if(id=='radvt')
    {
    //alert('val='+val);
        if(val=='S')
        {
            document.getElementById('radvno').style.display='inline';
            document.getElementById('radvno_').style.display='inline';
            document.getElementById('radvyr').style.display='inline';
            document.getElementById('radvyr_').style.display='inline';
            document.getElementById('radvname').disabled=true;
            document.getElementById('radvname').value='';
            document.getElementById('radvmob').value='';
            document.getElementById('radvmob').style.display='inline';
            document.getElementById('radvmob_').style.display='inline';
            document.getElementById('radvemail').value='';
            document.getElementById('radvemail').style.display='inline';
            document.getElementById('radvemail_').style.display='inline';
            $('#radvyr').attr('disabled',false);
            $('#ddl_res_adv_state').attr('disabled',false);
        }
        else if(val=='A')
        {
            //$('#ddl_res_adv_state').val('');
            $('#ddl_res_adv_state').attr('disabled',true);
            //$('#radvno').val('');
            //$('#radvyr').val('');
            $('#radvno').attr('disabled',false);
            $('#radvyr').attr('disabled',true);
            document.getElementById('radvno').style.display='inline';
            document.getElementById('radvno_').style.display='inline';
            document.getElementById('radvyr').style.display='inline';
            document.getElementById('radvyr_').style.display='inline';
            document.getElementById('radvmob').style.display='inline';
            document.getElementById('radvmob_').style.display='inline';
            document.getElementById('radvemail').style.display='inline';
            document.getElementById('radvemail_').style.display='inline';

            $('#ris_ac').attr('disabled',false);
            $('#ris_ac').prop('checked',false);

        }
        if(val == 'N')
        {
            document.getElementById('radvno').style.display = 'inline';
            document.getElementById('radvno_').style.display = 'inline';
            document.getElementById('radvyr').style.display = 'inline';
            document.getElementById('radvyr_').style.display = 'inline';
            document.getElementById('radvname').disabled = true;
            document.getElementById('radvname').value = '';
            document.getElementById('radvmob').value = '';
            document.getElementById('radvmob').style.display = 'inline';
            document.getElementById('radvmob_').style.display = 'inline';
            document.getElementById('radvemail').value = '';
            document.getElementById('radvemail').style.display = 'inline';
            document.getElementById('radvemail_').style.display = 'inline';
            $('#radvyr').attr('disabled',false);
            $('#ddl_res_adv_state').attr('disabled',false);
            $('#ris_ac').attr('disabled',false);
            $('#ris_ac').prop('checked',true);

        }
        else if(val!='S')
        {
            if(val=='SS'){
                document.getElementById('radvno').style.display='none';
                document.getElementById('radvno_').style.display='none';
                document.getElementById('radvno').value='';
                document.getElementById('radvyr').style.display='none';
                document.getElementById('radvyr_').style.display='none';
                document.getElementById('radvyr').value='';
                document.getElementById('radvname').disabled=false;
                document.getElementById('radvmob').style.display='none';
                document.getElementById('radvmob_').style.display='none';
                document.getElementById('radvemail').style.display='none';
                document.getElementById('radvemail_').style.display='none';

            }

            if(val=='C')
            {

            }
            else if(val=='SS')
            {

            }
            $('#ddl_res_adv_state').attr('disabled',true);
        }
    }
}