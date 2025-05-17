//Function Autocomplete start pet post------------------------------------------------
$(document).on("focus","#p_post",function(){
$("#p_post").autocomplete({
    // source:"../filing/new_filing_autocomp_post.php",
    source: "/Common/Ajaxcalls/new_filing_autocomp_post",

    width: 450,
    matchContains: true,	
    minChars: 1,
    selectFirst: false,
    autoFocus: false
});    
});

/* $(document).on("focus","#p_name",function(){
    $("#p_name").autocomplete({
    source:"./get_local_name.php?field_name=p_name",
    width: 450,
    matchContains: true,	
    minChars: 1,
    selectFirst: false,
    autoFocus: false
    });    
});

$(document).on("focus","#p_rel_name",function(){
    $("#p_rel_name").autocomplete({
    source:"./get_local_name.php?field_name=p_rel_name",
    width: 450,
    matchContains: true,	
    minChars: 1,
    selectFirst: false,
    autoFocus: false
    });    
});

$(document).on("focus","#p_city",function(){
    $("#p_city").autocomplete({
    source:"./get_local_name.php?field_name=p_city",
    width: 450,
    matchContains: true,	
    minChars: 1,
    selectFirst: false,
    autoFocus: false
    });    
});

$(document).on("focus","#p_add",function(){
    $("#p_add").autocomplete({
    source:"./get_local_name.php?field_name=p_add",
    width: 450,
    matchContains: true,	
    minChars: 1,
    selectFirst: false,
    autoFocus: false
    });    
});
*/
//Function Autocomplete end--------------------------------------------------
       
$(document).on("focus","#p_statename",function(){
$("#p_statename").autocomplete({
    source: "/Common/Ajaxcalls/get_only_state_name",
    width: 450,
matchContains: true,	
minChars: 1,
selectFirst: false,
autoFocus: false
});    
});


//Function Autocomplete start pet deptt------------------------------------------------
$(document).on("focus","#p_deptt",function(){
$("#p_deptt").autocomplete({
    source:"../filing/new_filing_autocomp_deptt.php?type="+$("#party_type").val(),
    width: 450,
    matchContains: true,	
    minChars: 1,
    selectFirst: false,
    autoFocus: false
});    
});
//Function Autocomplete end--------------------------------------------------
$(document).on("focus","#p_occ",function(){
$("#p_occ").autocomplete({
    source:"get_occ.php",
width: 450,
matchContains: true,	
minChars: 1,
selectFirst: false,
autoFocus: false,
select: function (event, ui) {
    //alert(ui.item.label+'***'+ui.item.value);
    //var htht = ui.item.value.split('~');
    //alert(htht[0]);
}
});    
});

$(document).on("focus","#p_edu",function(){
$("#p_edu").autocomplete({
    source:"get_edu.php",
width: 450,
matchContains: true,	
minChars: 1,
selectFirst: false,
autoFocus: false,
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
    else {
        $("#p_deptt_hd").val('');
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
    $("#radiodn").click(function(){
        $("#dno").removeProp('disabled');
        $("#dyr").removeProp('disabled');
        $("#selct").prop('disabled',true);
        $("#case_no").prop('disabled',true);
        $("#case_yr").prop('disabled',true);
        $("#selct").val("-1");
        $("#case_no").val("");
        //$("#case_yr").val("");
    });
    
    $("#radioct").click(function(){
        $("#dno").prop('disabled',true);
        $("#dyr").prop('disabled',true);
        $("#dno").val("");
        //$("#dyr").val("");
        $("#selct").removeProp('disabled');
        $("#case_no").removeProp('disabled');
        $("#case_yr").removeProp('disabled');
    });
    
    $("input[name=btnGetR]").click(function(){
        var diaryno, diaryyear, cstype, csno, csyr;
        var regNum = new RegExp('^[0-9]+$');
         
        if($("#radioct").is(':checked')){
            cstype = $("#selct").val();
            csno = $("#case_no").val();
            csyr = $("#case_yr").val();
            
            if(!regNum.test(cstype)){
                alert("Please Select Casetype");
                $("#selct").focus();
                return false;
            }
            if(!regNum.test(csno)){
                alert("Please Fill Case No in Numeric");
                $("#case_no").focus();
                return false;
            }
            if(!regNum.test(csyr)){
                alert("Please Fill Case Year in Numeric");
                $("#case_yr").focus();
                return false;
            }
            if(csno == 0){
                alert("Case No Can't be Zero");
                $("#case_no").focus();
                return false;
            }
            if(csyr == 0){
                alert("Case Year Can't be Zero");
                $("#case_yr").focus();
                return false;
            }
            /*if(cstype.length==1)
                cstype = '00'+cstype;
            else if(cstype.length==2)
                cstype = '0'+cstype;*/
        }
        else if($("#radiodn").is(':checked')){
            diaryno = $("#dno").val();
            diaryyear = $("#dyr").val();
            if(!regNum.test(diaryno)){
                alert("Please Enter Diary No in Numeric");
                $("#dno").focus();
                return false;
            }
            if(!regNum.test(diaryyear)){
                alert("Please Enter Diary Year in Numeric");
                $("#dyr").focus();
                return false;
            }
            if(diaryno == 0){
                alert("Diary No Can't be Zero");
                $("#dno").focus();
                return false;
            }
            if(diaryyear == 0){
                alert("Diary Year Can't be Zero");
                $("#dyr").focus();
                return false;
            }
        }
        else{
            alert('Please Select Any Option');
            return false;
        }
        //alert('checked');
        $.ajax({
            type: 'POST',
            url:"./get_extraparty.php",
            beforeSend: function (xhr) {
                $("#result1").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='../images/load.gif'></div>");
            },
            data:{dno:diaryno,dyr:diaryyear,ct:cstype,cn:csno,cy:csyr}
        })
        .done(function(msg){
            $("#result1").html(msg);
            $("#result2").html("");
        })
        .fail(function(){
            alert("ERROR, Please Contact Server Room"); 
        });
    });
});

$(document).on('change','#party_flag',function(){
    getParty_status(this.value,'');
//    if(this.value == "")
//        $("#pno").html("");
//    else{
//        $.ajax({
//            type: 'POST',
//            url:"./set_party_status.php",
//            data:{fno:$("#hdfno").val(),val:this.value}
//        })
//        .done(function(msg){
//            $("#pno").html(msg);
//        })
//        .fail(function(){
//            alert("ERROR, Please Contact Server Room"); 
//        });
//    }
    $("#p_cntpro").val("C");
    if(this.value=='P')
        $("#p_cntpro").prop('disabled',true);
    else
        $("#p_cntpro").removeProp('disabled');
    
});

$(document).on('click','#enable_party',function(){
    if($(this).val()=='ENABLE PARTYNO.'){
        $("#pno").removeProp('disabled');
        $("#enable_party").val("DISABLE PARTYNO.");
    }
    else{
        $("#pno").prop('disabled',true);
        $("#enable_party").val("ENABLE PARTYNO.");
    }
    
});

$(document).on('change','#pri_action',function(){
    if($(this).val()=='P'){
        $("#for_selecting_lrs").css('display','none');
        $("#party_flag").removeProp('disabled');
        $("#p_lowercase").removeProp('disabled');
        //$("#p_lowercase").val("");
        $("#sel_lrstolrs").html("<option value=''>No Data For LRs to LRs</option>");
        //$("#remark_lrs_row").css('display','none');
    }
    else if($(this).val()=='L'){
        $("#for_selecting_lrs").css('display','inline');
        $("#for_selecting_lrs").val("");
        $("#party_flag").prop('disabled',true);
        //$("#remark_lrs_row").css('display','table-row');
    }
    $("#remark_lrs").val("");
    //call_fullReset_extra('F');
    $("#party_flag").val("");
    //$("#pno").html("");
    $("#pno").val("");
});

$(document).on('change','#for_selecting_lrs',function(){
    var totalval=$(this).val().split('~');
    $("#party_flag").val(totalval[0]);
    $("#party_flag").change();
    getParty_status(totalval[0],'');
});

/*$(document).on("change","#sel_lrstolrs",function(){
    var totalval=$(this).val().split('~');
    //$("#party_flag").val(totalval[0]);
    //alert(totalval[0]);
    getParty_status(totalval[0],'L');
});*/

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

$(document).on("change","#p_rel",function(){
    if(this.value == 'S' || this.value == 'F')
        $("#p_sex").val("M");
    else if(this.value == 'D' || this.value == 'W' || this.value == 'M')
        $("#p_sex").val("F");
});


function call_getDetails_extra()
{
    
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
        document.getElementById('tr_for_individual').style.display='block';
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
        document.getElementById('tr_for_individual').style.display='none';

        if(value=='D3')
            document.getElementById('tr_d0').style.display='none';
        else
            document.getElementById('tr_d0').style.display='table-row';
        
        document.getElementById('tr_d').style.display='table-row';
        /*if(value=='D1')
            document.getElementById('tr_d1').style.display='table-row';
        else
        {
            document.getElementById('tr_d1').style.display='none';
            document.getElementById('state_department_in').value='';
        }*/
    }
}

function getParty_status(value,flag)
{
    var totalval= '';
    if($("#pri_action").val()=='L' && flag==''){
        totalval=$("#for_selecting_lrs").val().split('~');
    }
    /*else if($("#pri_action").val()=='L' && flag=='L'){
        totalval=$("#sel_lrstolrs").val().split('~');
    }*/
    else{
        totalval = '';
    }
    //alert(totalval[0]+'~'+totalval[1]+'~'+totalval[2]);
    //alert(value);
    if(value == "" && flag==''){
        //$("#pno").html("");
        $("#pno").val("");
        //$("#sel_lrstolrs").html("<option value=''>No Data For LRs to LRs</option>");
    }
    else{
        $.ajax({
            type: 'POST',
            url:"./set_party_status.php",
            data:{fno:$("#hdfno").val(),val:value,add_selector:$("#pri_action").val(),srno:totalval[1],srnoshow:totalval[2],flag:flag}
        })
        .done(function(msg){
            if(msg.indexOf('~')>0){
                var msg2 = msg.split('~');
                $("#pno").val(msg2[0]);
                if(msg2[1] != 0 && msg2[1] != ''){
                    $("#p_lowercase option").each(function()
                    {
                        $(this).removeProp('selected');
                    });
                    var lowerids = msg2[1].split(',');

                    for(var i=0;i<lowerids.length;i++){
                        $("#p_lowercase option").each(function()
                        {
                            // Add $(this).val() to your list
                            if($(this).val()==lowerids[i])
                                $(this).prop('selected', true);


                        });

                    }
                    //$("#p_lowercase").val(msg2[1]);
                    $("#p_lowercase").prop("disabled",true);
                }
                else{
                    $("#p_lowercase").removeProp('disabled');
                    //$("#p_lowercase").val("");
                }
                
                /*if(flag=='')
                    activeLrtoLr($("#hdfno").val(),value,totalval[1]);*/
            }
            else{
               //
                //$("#pno").html(msg);
                $("#pno").val(msg);
                $("#p_lowercase").removeProp('disabled');
               // $("#p_lowercase").val("");
            }
        })
        .fail(function(){
            alert("ERROR, Please Contact Server Room"); 
        });
    }
}

function activeLrtoLr(diary_no,petres,no){
    $.ajax({
        type: 'POST',
        url:"./get_lrstolr.php",
        data:{dno:diary_no,val:petres,srno:no}
    })
    .done(function(msg){
        //alert(msg);
        //$("#sel_lrstolrs").html(msg);
    })
    .fail(function(){
        alert("ERROR, Please Contact Server Room"); 
    });
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
    if ((charCode >= 48 && charCode<= 57)||charCode==9||charCode==8|| charCode == 37 || charCode == 39) {
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
            charCode==127||charCode==32||charCode==46||charCode==47||charCode==64|| charCode == 37 || charCode == 39) {
        return true;
    }
    return false;
}

function onlyalphabnum(evt) 
{
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    //alert(charCode);
    if ((charCode >= 65 && charCode<= 90)||(charCode >= 48 && charCode<= 57)||(charCode >= 97 && charCode<= 122)||charCode==9||charCode==8||
            charCode==127||charCode==32||charCode==46||charCode==47||charCode==64||charCode==40||charCode==41
            || charCode == 37 || charCode == 39||charCode==44) {
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
            charCode==40||charCode==41|| charCode == 37 || charCode == 39||charCode==44) {
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

function call_save_extra()
{
//     alert("As per Hon'ble Court Order dated. 08-02-2018 in MA no. 1158/2017(Suraj India Trust Vs UOI), Registry is directed not to accept any application or petition on behalf of Suraj India Trust or Mr. Rajiv Daiya");

// alert("Registry is directed not to accept any application or petition on behalf of:- \n 1) Suraj India Trust or Mr. Rajiv Daiya as per Hon'ble Court Order dated. 08-02-2018 in MA no. 1158/2017(Suraj India Trust Vs UOI) \n 2) ASOK PANDE as per Hon'ble Court Order dated. 26-10-2018 in WP(C) No. 965/2018 (ASOK PANDE Vs UOI) \n 3) MANOHAR LAL SHARMA  as per Hon'ble Court Order dated. 07-12-2018 in WP(CRL) No. 315/2018 (MANOHAR LAL SHARMA Vs ARUN JAITLEY (AT PRESENT FINANCE MINISTER))");



    var party_type = document.getElementById('party_type').value;
    var party_flag = document.getElementById('party_flag');
    
    var p_name,p_rel,p_rel_name,p_sex,p_age,p_post,p_deptt,p_occ,p_edu,p_masked_name;
    
    if($("#pri_action").val()=='L'){
        if($("#for_selecting_lrs").val()==''){
            alert('Please Select Party to Insert LRs');
            $("#for_selecting_lrs").focus();
            return false;
        }
    }
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
        if(document.getElementById('mask_check').checked){
            if(document.getElementById('masked_name').value!='') {
                p_masked_name=document.getElementById('masked_name').value;
            }
            else{
                alert('Please Enter Masked Name');masked_name.focus();return false;
            }
        }

        if(p_name.value=='')
        {
            alert('Please Enter Party Name...');p_name.focus();return false;
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
        /*if(p_post.value=='' || p_post.value==' ')
        {
            alert('Please Enter Party Post');p_post.focus();return false;
        }
        if(p_deptt.value=='' || p_deptt.value==' ')
        {
            alert('Please Enter Party Department');p_deptt.focus();return false;
        } */
        if(p_statename.value=='' && document.getElementById('s_causetitle').checked )
        {
            alert('Please Enter State Name');p_statename.focus();return false;
        }
//        if(p_post.value=='' || (p_post.value==''&& document.getElementById('p_causetitle').checked ))
        //if(p_post.value=='' || ( p_post.value==''&& document.getElementById('p_causetitle').checked &&  party_no=='1' ))
        if(p_post.value=='' &&   document.getElementById('p_causetitle').checked )
        {
            alert('Please Enter Party Post');p_post.focus();return false;
        }
        //if(p_deptt.value=='' || (p_deptt.value=='' && document.getElementById('d_causetitle').checked &&  party_no=='1' ))
        if(p_deptt.value=='' &&  document.getElementById('d_causetitle').checked )
        {
            alert('Please Enter Party Department');p_deptt.focus();return false;
        }

        if((document.getElementById('p_statename').value=='' )&&(document.getElementById('p_post').value=='' )&& (document.getElementById('p_deptt').value==''))
        {
            alert('Please Enter either State/Department/Post');p_deptt.focus();return false;
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
    if(document.getElementById('p_cont').value=='96'){
        if(document.getElementById('p_st').value=="")
        {
            alert('Please Enter Party State');document.getElementById('p_st').focus();return false;
        }
        if(document.getElementById('p_dis').value=="")
        {
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
/*    if(document.getElementById("p_lowercase").value == "" && (document.getElementById("hd_casetype").value!=5 && document.getElementById("hd_casetype").value!=6 && document.getElementById("hd_casetype").value!='24'  && document.getElementById("hd_casetype").value!='17' )) */

if(document.getElementById("p_lowercase").value == "" && (document.getElementById("hd_allow_user").value!=1 && document.getElementById("hd_casetype").value!=5 && document.getElementById("hd_casetype").value!=6 && document.getElementById("hd_casetype").value!='24'  && document.getElementById("hd_casetype").value!='17' && document.getElementById("hd_casetype").value!='22' && document.getElementById("hd_casetype").value!='27' && document.getElementById("hd_casetype").value!='34'  && document.getElementById("hd_casetype").value!='35'&& document.getElementById("hd_casetype").value!='37' && document.getElementById("hd_casetype").value!='36' && document.getElementById("hd_casetype").value!='38' && document.getElementById("hd_casetype").value!='32' && document.getElementById("hd_casetype").value!='33' && document.getElementById("hd_casetype").value!='40' && document.getElementById("hd_casetype").value!='41'))
    {
        alert('Please Select Lower Court Case');
        p_lowercase.focus();return false;
    }
    var remark_lrs = $("#remark_lrs").val();
    if($("#pri_action").val()=='L'){
        if(remark_lrs==''){
            alert('Please Enter Remarks for Adding LRs');
            $("#remark_lrs").focus();
            return false;
        }
    }
    
    var add_add_count = $("#hd_add_add_count").val();
    var add_addresses = '';
    if(add_add_count>0){
        for(var i=1;i<=add_add_count;i++){
            if($("#add-add_table_"+i)){
                if($("#add_"+i).val()==''){
                    alert('Please Fill this Additional Address');
                    $("#add_"+i).focus(); 
                    return false;
                }
                if($("#cont_"+i).val()=='96'){
                    if($("#st_"+i).val()==''){
                        alert('Please Select Additional Address State');
                        $("#st_"+i).focus(); 
                        return false;
                    }
                    if($("#dis_"+i).val()==''){
                        alert('Please Select Additional Address District');
                        $("#dis_"+i).focus(); 
                        return false;
                    }
                }
                if($("#add_"+i).length > 0)
                    add_addresses = add_addresses+"^"+$("#add_"+i).val()+"~"+$("#cont_"+i).val()+"~"+$("#st_"+i).val()+"~"+$("#dis_"+i).val();
            }
        }
    }
    if($("#pno").val()=='0'){
        alert('Party No. Can not be 0');
        return false;
    }
    
    if($("#party_flag")!='I'){
        if($("#order1").val() == $("#order2").val() == $("#order3").val()){
            alert('All Orders Can not be same');
            return false;
        }
        else{
            if($("#order1").val() == $("#order2").val()){
                alert('Order1 and Order2 Can not be same');
                return false;
            }
            else if($("#order2").val() == $("#order3").val()){
                alert('Order2 and Order3 Can not be same');
                return false;
            }
            else if($("#order1").val() == $("#order3").val()){
                alert('Order1 and Order3 Can not be same');
                return false;
            }
        }
    }
    alert("Registry is directed not to accept any application or petition on behalf of:- \n 1) Suraj India Trust or Mr. Rajiv Daiya as per Hon'ble Court Order dated. 08-02-2018 in MA no. 1158/2017(Suraj India Trust Vs UOI) \n 2) ASOK PANDE as per Hon'ble Court Order dated. 26-10-2018 in WP(C) No. 965/2018 (ASOK PANDE Vs UOI) \n 3) MANOHAR LAL SHARMA  as per Hon'ble Court Order dated. 07-12-2018 in WP(CRL) No. 315/2018 (MANOHAR LAL SHARMA Vs ARUN JAITLEY (AT PRESENT FINANCE MINISTER))\n" +
        " 4) P1-SURAJ MISHRA and P2-ROHIT GUPTA  as per Hon'ble Court Order dated. 08-05-2019 in WP(C) No. 1328/2018 (SURAJ MISHRA AND ANR VS. UNION OF INDIA AND ANR)");


    var p_lowercase= $('#p_lowercase').val();
    //alert(add_addresses);
    //alert(document.getElementById('p_lowercase').value);
    //return false;
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
            document.getElementById('table_show').innerHTML="Please Wait While We Are Fatching Parties Information Again";
            
            /*if($("#sel_lrstolrs")){
                if($("#sel_lrstolrs").val()!="")
                    call_fullReset_extra('L');
                else
                    call_fullReset_extra('H');
            }
            else*/
                call_fullReset_extra('H');
            
            call_fetch_infoAgain(document.getElementById('hdfno').value);
              /*if(document.getElementById('pt_sno').value!='' && document.getElementById('pt_r_p').value!='')
                {
//                   alert("sdsdsdsd");
                   var pt_sno=document.getElementById('pt_sno').value;
                  var pt_r_p= document.getElementById('pt_r_p').value;
                 var hdfno= document.getElementById('hdfno').value;
                   update_ext_pty_efil(pt_sno,pt_r_p,hdfno);
                }*/
            $('#p_lowercase').val(p_lowercase);
        }
    }
    var url = "save_new_filing_extraparty.php?controller=I&fno="+document.getElementById('hdfno').value+"&p_f="+party_flag.value;
    
    if(party_type=="I")
        url = url+"&p_type="+party_type+"&p_name="+p_name.value+"&p_rel="+p_rel.value+"&p_rel_name="+p_rel_name.value
    +"&p_sex="+p_sex.value+"&p_age="+p_age.value+"&p_occ="+document.getElementById('p_occ').value
    +"&p_edu="+document.getElementById('p_edu').value+"&p_caste="+document.getElementById('p_caste').value
    +"&p_occ_code="+document.getElementById('p_occ_hd_code').value+"&p_edu_code="+document.getElementById('p_edu_hd_code').value
    +"&p_caste="+document.getElementById('p_caste').value
    +"&p_masked_name="+document.getElementById('masked_name').value     ;

    if(party_type!="I")
        url = url+"&p_type="+party_type+"&p_post="+p_post.value+"&p_deptt="+p_deptt.value+"&p_statename="+$("#p_statename").val()
        +"&p_statename_hd="+$("#p_statename_hd").val()+"&d_code="+$("#p_deptt_hd").val()+"&p_code="+document.getElementById('post_code').value;
    
    url = url+"&p_add="+document.getElementById('p_add').value+"&p_city="+document.getElementById('p_city').value
    +"&p_pin="+document.getElementById('p_pin').value+"&p_dis="+document.getElementById('p_dis').value
    +"&p_st="+document.getElementById('p_st').value+"&p_cont="+document.getElementById('p_cont').value+"&p_mob="+document.getElementById('p_mob').value
    +"&p_email="+document.getElementById('p_email').value;
    
    //url = url+"&p_no="+document.getElementById('pno').innerHTML;
    url = url+"&p_no="+document.getElementById('pno').value;
    
    url = url+"&lowercase="+$('#p_lowercase').val()
    +"&remark_lrs="+remark_lrs+"&add_add="+add_addresses+"&cont_pro_info="+$("#p_cntpro").val();
    
    url = url+"&order1="+$("#order1").val()+"&order2="+$("#order2").val()+"&order3="+$("#order3").val();

    if(party_type!="I") {
        s_ct=document.getElementById('s_causetitle').checked;
        d_ct=document.getElementById('d_causetitle').checked;
        p_ct=document.getElementById('p_causetitle').checked;
        /*url=url+"&s_causetitle="+document.getElementById('s_causetitle').checked
         +"&d_causetitle="+document.getElementById('d_causetitle').checked
         +"&p_causetitle="+document.getElementById('p_causetitle').checked;*/
        url=url+"&s_causetitle="+s_ct
            +"&d_causetitle="+d_ct
            +"&p_causetitle="+p_ct
    }
    /*if($("#state_department_in")){
        var state_department_in = $("#state_department_in").val().split("->");
        url = url +"&d_code="+state_department_in[0];
    }*/
    
    //alert(url);
    //alert(document.getElementById('pno').innerHTML);
    //return false;
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
    var url = "firse_partyinfo.php?fno="+value;
    xmlhttp.open("GET",url,false);
    xmlhttp.send(null);
}

function call_fullReset_extra(str)
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
        document.getElementById('mask_check').checked=false;
        document.getElementById('masked_name').value="";
        document.getElementById('tr_for_individual').style.display='block';
        document.getElementById('tr_for_individual').style.display='table-row';
    }
    else if(document.getElementById('party_type').value!='I')
    {
        document.getElementById('p_statename').value="";
        document.getElementById('p_post').value="";
        document.getElementById('p_deptt').value="";
        document.getElementById('s_causetitle').checked=false;
        document.getElementById('d_causetitle').checked=false;
        document.getElementById('p_causetitle').checked=false;
        document.getElementById('tr_for_individual').style.display='block';
        document.getElementById('tr_for_individual').style.display='table-row';
    }
    if(str=='F'){
        document.getElementById('party_flag').value="";
        if($("#pri_action").val()=='L'){
            $("#for_selecting_lrs").css('display','none');
            $("#for_selecting_lrs").val("");
        }
        $("#pri_action").val("P");
        $("#remark_lrs").val("");
        /*if($("#remark_lrs_row")){
            $("#remark_lrs_row").css('display','none');
            $("#remark_lrs").val("");
        }*/
    }
    $("#enable_party").val("ENABLE PARTYNO.");
    $("#pno").prop('disabled',true);
    
    document.getElementById('party_type').value="I";
    document.getElementById('p_add').value="";
    document.getElementById('p_city').value="";
    document.getElementById('p_pin').value="";
    document.getElementById('p_dis').innerHTML="<option value=''>Select</option>";
    document.getElementById('p_cont').value="96";
    document.getElementById('p_st').value="";
    $('#p_st').removeProp('disabled');
    $('#p_dis').removeProp('disabled');
    $('#p_lowercase').removeProp('disabled');
    document.getElementById('p_mob').value="";
    document.getElementById('p_email').value="";
    //document.getElementById('pno').innerHTML="";
    document.getElementById('pno').value="";
    document.getElementById('for_I_1').style.display='table-row';
    document.getElementById('for_I_2').style.display='table-row';
    document.getElementById('for_I_3').style.display='table-row';
    document.getElementById('for_I_4').style.display='table-row';
    document.getElementById('tr_d').style.display='none';
    document.getElementById('tr_d0').style.display='none';
    //document.getElementById('p_lowercase').value="";
    //alert( $('#p_lowercase option').length )
    if($('#p_lowercase option').length>2)
        document.getElementById('p_lowercase').value="";
    //document.getElementById('tr_d1').style.display='none';
    //document.getElementById('state_department_in').value='';
    
    if(str=='H')
        getParty_status(document.getElementById('party_flag').value,'');
    else if(str=='L')
        getParty_status(document.getElementById('party_flag').value,'L');
    
    
    if($("#hd_add_add_count").val()>0){
        for(var i=1;i<=$("#hd_add_add_count").val();i++){
            if($("#add-add_table_"+i)){
                $("#add-add_table_"+i).remove();
                $("#hr_"+i).remove();
            }
        }
        $("#hd_add_add_count").val(0);
    }
    
    //getDistrict('23');
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
    xmlhttp.open("GET","../filing/get_district.php?state="+val,true);
    xmlhttp.send(null);
}

function setPartiesinField(id,flag,type)
{
    //alert("id= "+id+" flag= "+flag+" type="+type);
   document.getElementById('pt_sno').value=id;
     document.getElementById('pt_r_p').value=flag;
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
        //   alert(hello);
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
                
                getParty_status(flag,'');
//                document.getElementById('pno').innerHTML=id;
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
//                getDistrict(hello[7]);
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
              getParty_status(flag,'');
             //   document.getElementById('pno').innerHTML=id;
                document.getElementById('p_deptt').value=hello[0];
                document.getElementById('p_post').value=hello[1];
                document.getElementById('p_add').value=hello[3];
                document.getElementById('p_city').value=hello[4];
                document.getElementById('p_pin').value=hello[7];
                document.getElementById('p_st').value=hello[5];
             //   getDistrict(hello[5]);
                document.getElementById('p_dis').value=hello[6];
                document.getElementById('p_mob').value=hello[9];
                document.getElementById('p_email').value=hello[8];
                document.getElementById('post_code').value=hello[10];
                document.getElementById('svbtn').disabled=false;
                document.getElementById('rstbtn').disabled=false;
                if(type=='D1')
                {
                    if(hello[11]!=0)
                        document.getElementById('state_department_in').value=hello[11]+'->'+hello[12];
                }
            }
        }
    }
    var url = "get_extraparty_info_efil.php?fno="+document.getElementById('hdfno').value+"&id="+id+"&flag="+flag+"&type="+type;
    //alert(url);
    xmlhttp.open("GET",url,false);
    xmlhttp.send(null);
}

function  update_ext_pty_efil(pt_sno,pt_r_p,hdfno)
{
    $.ajax({
                        url:'extraparty_update_efil.php',
                        type:'GET',
                        cache:false,
                        async:true,
                        data:{pt_sno:pt_sno,pt_r_p:pt_r_p,hdfno:hdfno},
                        success:function(data,status){
                        
                          document.getElementById('pt_sno').value='';
                           document.getElementById('pt_r_p').value='';
                 call_getDetails_extra();
                
                        },
                        error:function(xhr){
                            alert("Error: "+xhr.text+' '+xhr.statusText);
                        }
                    });
}

function get_ext_pty_mn()
{
     document.getElementById('pt_sno').value='';
     document.getElementById('pt_r_p').value='';
}

$(document).on("click","#sp_add_add",function(){
    var count = parseInt($("#hd_add_add_count").val())+1;
    $.ajax({
        type: 'POST',
        url:"./get_add_add_fields.php",
        data:{count:count}
    })
    .done(function(msg){
        //alert(msg);
        $("#extra_address").append(msg);
        $("#hd_add_add_count").val(count);
    })
    .fail(function(){
        alert("ERROR, Please Contact Server Room"); 
    });
});

$(document).on("click","[id^='add_add_cl_']",function(){
    var num = this.id.split('add_add_cl_');
    $("#add-add_table_"+num[1]).remove();
    $("#hr_"+num[1]).remove();
});

$(document).on("change","[id^='cont_']",function(){
    var num = this.id.split('cont_');
    if($(this).val()!=96){
        $("#st_"+num[1]).prop('disabled',true);
        $("#dis_"+num[1]).prop('disabled',true);
    }
    else{
        $("#st_"+num[1]).removeProp('disabled');
        $("#dis_"+num[1]).removeProp('disabled');
    }
});

function getdistrictforadd(val,cnt){
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
            document.getElementById('dis_'+cnt).innerHTML=xmlhttp.responseText;
        }
    }
    xmlhttp.open("GET","../filing/get_district.php?state="+val,true);
    xmlhttp.send(null);
}
function f1(){
    if(document.getElementById('mask_check').checked){
        document.getElementById('span_mask_name').style.display='inline-block';
    }
    else{
        document.getElementById('span_mask_name').style.display='none';
    }


}
