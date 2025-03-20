
//Function Autocomplete start pet post------------------------------------------------
$(document).on("focus","#p_post",function(){
    $("#p_post").autocomplete({
        source:"../filing/new_filing_autocomp_post.php",
        width: 450,
        matchContains: true,
        minChars: 1,
        selectFirst: false,
        autoFocus: false
    });
});


/*$(document).on("focus","#p_name",function(){
    $("#p_name").autocomplete({
    source:"./get_local_name.php?field_name=p_name",
    width: 450,
    matchContains: true,	
    minChars: 1,
    selectFirst: false,
    autoFocus: false,
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
});*/
//Function Autocomplete end--------------------------------------------------

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

$(document).on("focus","#p_statename",function(){
    $("#p_statename").autocomplete({
        source:"./get_only_state_name.php",
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

    $("input[name=btnGetRMod]").click(function(){

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

        $.ajax({
            type: 'POST',
            url:"./get_extraparty_mod.php",
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

$(document).on("change","#p_rel",function(){
    if(this.value == 'S' || this.value == 'F')
        $("#p_sex").val("M");
    else if(this.value == 'D' || this.value == 'W' || this.value == 'M')
        $("#p_sex").val("F");
    else if(this.value == '')
        $("#p_sex").val("");
});


/*$(document).on('change','#pri_action',function(){
    if($(this).val()=='P'){
        $("#for_selecting_lrs").css('display','none');
        $("#party_flag").removeProp('disabled');
        $("#p_lowercase").removeProp('disabled');
        $("#p_lowercase").val("");
    }
    else if($(this).val()=='L'){
        $("#for_selecting_lrs").css('display','inline');
        $("#party_flag").prop('disabled',true);
    }
    //call_fullReset_extra('F');
    $("#party_flag").val("");
    $("#pno").html("");
});

$(document).on('change','#for_selecting_lrs',function(){
    var totalval=$(this).val().split('~');
    $("#party_flag").val(totalval[0]);
    getParty_status(totalval[0]);
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

$(document).on("click","[name^='ExMod_']",function(){
    //alert('sd');
    var num8 = this.name.split('ExMod_');
    //alert(num[1]);
    var num = num8[1].split('_');
    $.ajax({
        type: 'POST',
        url:"./get_extraparty_info.php",
        beforeSend: function (xhr) {
            //$("#result1").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='../images/load.gif'></div>");
        },
        data:{fno:$("#hdfno").val(),id:num[1],flag:num[0],type:num[2]}
    })
        .done(function(msg){
            //alert(msg);
            //$("#result1").html(msg);
            msg = msg.split('~');
            /*alert(msg[21]);
            alert(msg[22])
            for(i=0;i<msg.length;i++){
                alert(i+'  '+msg[i]);
            } */
            if(msg[22]==0)
                msg[22]='';
            if(num[1].indexOf('.')>0){
                $("#pri_action").html("<option value='L'>LR's</option>");
                $("#for_selecting_lrs").css('display','inline');
                //$("#remark_lrs_row").css('display','table-row');
                var newNum = num[1].split('.');
                $("#for_selecting_lrs").val(num[0]+'~'+newNum[0]+'~'+num[1]);
                //alert(newNum.length);
                /*if(newNum.length==3){
                    //var newNum2 = newNum[1].split('.');
                    $("#sel_lrstolrs").val(num[0]+'~'+newNum[0]+'~'+num[1]);
                }
                else
                    $("#sel_lrstolrs").val("");*/
            }
            else{
                $("#pri_action").html("<option value='P'>Party</option>");
                $("#for_selecting_lrs").css('display','none');
                //$("#remark_lrs_row").css('display','none');
                //$("#sel_lrstolrs").val("");
            }

            if(num[2]=='I')
            {
                activate_extra(num[2]);
                document.getElementById('party_type').value=num[2];
                if(num[0]=='P')
                    document.getElementById('party_flag').innerHTML="<option value='P' selected>Petitioner</option><option value='R'>Respondent</option><option value='I'>Impleading</option><option value='N'>Intervenor</option>";
                else if(num[0]=='R')
                    document.getElementById('party_flag').innerHTML="<option value='P'>Petitioner</option><option value='R' selected>Respondent</option><option value='I'>Impleading</option><option value='N'>Intervenor</option>";
                else if(num[0]=='I')
                    document.getElementById('party_flag').innerHTML="<option value='P'>Petitioner</option><option value='R'>Respondent</option><option value='I' selected>Impleading</option><option value='N'>Intervenor</option>";
                else if(num[0]=='N')
                    document.getElementById('party_flag').innerHTML="<option value='P'>Petitioner</option><option value='R'>Respondent</option><option value='I'>Impleading</option><option value='N' selected>Intervenor</option>";

                document.getElementById('hd_party_flag').value=num[0];
                document.getElementById('pno').innerHTML=num[1];



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
                if(msg[15]!='96'){
                    $('#p_st').prop('disabled',true);
                    $('#p_dis').prop('disabled',true);
                }
                else{
                    $('#p_st').removeProp('disabled');
                    $('#p_dis').removeProp('disabled');
                }
                document.getElementById('p_pin').value=msg[11];
                document.getElementById('p_mob').value=msg[13];
                document.getElementById('p_email').value=msg[12];

                if(msg[29].trim()!=''){

                    document.getElementById('mask_check').checked=true;
                    document.getElementById('p_name').value=msg[29].trim();
                    //document.getElementById('span_mask_name').style.display='block';
                    document.getElementById('span_mask_name').style.display='inline-block';
                    document.getElementById('masked_name').value=msg[0];
                    document.getElementById('tr_for_individual').style.display='inline-block';
                    document.getElementById('tr_for_individual').style.display='table-row';
                }
                else{
                    document.getElementById('p_name').value=msg[0];
                    document.getElementById('tr_for_individual').style.display='inline-block';
                    document.getElementById('tr_for_individual').style.display='table-row';
                    document.getElementById('mask_check').checked=false;
                    document.getElementById('masked_name').value='';
                    document.getElementById('span_mask_name').style.display='none';


                }
                $("#lower_case option").each(function()
                {
                    $(this).removeProp('selected');
                });
                var lowerids = msg[22].split(',');
                //alert(lowerids.length);

                for(var i=0;i<lowerids.length;i++){
                    $("#lower_case option").each(function()
                    {
                        // Add $(this).val() to your list
                        if($(this).val()==lowerids[i])
                            $(this).prop('selected', true);
                    });

                }
                //document.getElementById('lower_case').value=msg[22];
                document.getElementById('svbtn').disabled=false;
                document.getElementById('rstbtn').disabled=false;
            }
            else
            {
                document.getElementById('tr_for_individual').style.display='none';

                activate_extra(num[2]);
                document.getElementById('party_type').value=num[2];
                if(num[0]=='P')
                    document.getElementById('party_flag').innerHTML="<option value='P' selected>Petitioner</option><option value='R'>Respondent</option><option value='I'>Impleading</option><option value='N'>Intervenor</option>";
                else if(num[0]=='R')
                    document.getElementById('party_flag').innerHTML="<option value='P'>Petitioner</option><option value='R' selected>Respondent</option><option value='I'>Impleading</option><option value='N'>Intervenor</option>";
                else if(num[0]=='I')
                    document.getElementById('party_flag').innerHTML="<option value='P'>Petitioner</option><option value='R'>Respondent</option><option value='I' selected>Impleading</option><option value='N'>Intervenor</option>";
                else if(num[0]=='N')
                    document.getElementById('party_flag').innerHTML="<option value='P'>Petitioner</option><option value='R'>Respondent</option><option value='I'>Impleading</option><option value='N' selected>Intervenor</option>";

                document.getElementById('hd_party_flag').value=num[0];
                document.getElementById('pno').innerHTML=num[1];
                /*  if(num[1]!=1) {
                      document.getElementById('s_causetitle').disabled = true;
                      document.getElementById('d_causetitle').disabled = true;
                      document.getElementById('p_causetitle').disabled = true;
                  }
                  else{
                      document.getElementById('s_causetitle').disabled = false;
                      document.getElementById('d_causetitle').disabled = false;
                      document.getElementById('p_causetitle').disabled = false;
                  } */
                //--to edit


                document.getElementById('party_name').value=msg[0];
                document.getElementById('p_post').value=msg[7];
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
                document.getElementById('s_causetitle').checked=false;
                document.getElementById('d_causetitle').checked=false;
                document.getElementById('p_causetitle').checked=false;
                $("#lower_case option").each(function()
                {
                    $(this).removeProp('selected');
                });
                var lowerids = msg[22].split(',');
                //alert(lowerids.length);
                for(var i=0;i<lowerids.length;i++){
                    $("#lower_case option").each(function()
                    {
                        // Add $(this).val() to your list
                        if($(this).val()==lowerids[i])
                            $(this).prop('selected', true);

                    });

                }
                //document.getElementById('lower_case').value=msg[22].trim();
                document.getElementById('svbtn').disabled=false;
                document.getElementById('rstbtn').disabled=false;
                if(msg[15]!='96'){
                    $('#p_st').prop('disabled',true);
                    $('#p_dis').prop('disabled',true);
                }
                else{
                    $('#p_st').removeProp('disabled');
                    $('#p_dis').removeProp('disabled');
                }
            }
            //alert(msg[28]);
            //$("#p_cntpro").val(msg[28]);
            document.getElementById('p_cntpro').value=msg[28].trim();
            if(num[0]=='P')
                $("#p_cntpro").prop('disabled',true);
            else
                $("#p_cntpro").removeProp('disabled');

            document.getElementById('remark_lrs').value=msg[25].trim();
            document.getElementById('hd_add_add_count').value=msg[26];
            $("#extra_address").html("");
            if(msg[26]>0){
                //var count = parseInt($("#hd_add_add_count").val())+1;
                $.ajax({
                    type: 'POST',
                    url:"./set_add_add_fields.php",
                    data:{id:msg[27]}
                })
                    .done(function(msg){
                        //alert(msg);
                        $("#extra_address").append(msg);
                        //$("#hd_add_add_count").val(count);
                    })
                    .fail(function(){
                        alert("ERROR, Please Contact Server Room");
                    });
            }

        })
        .fail(function(){
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
            var party_info = xmlhttp.responseText;
            party_info = party_info.split('~');
            /*  for(i=0;i<party_info.length;i++){
             alert(i+'  '+party_info[i]);
             } */
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
                document.getElementById('p_name').value=party_info[0];
                document.getElementById('p_rel').value=party_info[12];
                document.getElementById('p_rel_name').value=party_info[1];
                document.getElementById('p_sex').value=party_info[3];
                document.getElementById('p_age').value=party_info[2];
                document.getElementById('p_occ').value=party_info[4];
                document.getElementById('p_add').value=party_info[5];
                document.getElementById('p_city').value=party_info[6];
                document.getElementById('p_pin').value=party_info[9];
                document.getElementById('p_st').value=party_info[7];
                getDistrict(party_info[7]);
                document.getElementById('p_dis').value=party_info[8];
                document.getElementById('p_mob').value=party_info[11];
                document.getElementById('p_email').value=party_info[10];
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
                document.getElementById('party_name').value=party_info[0];
                document.getElementById('hd_party_flag').value=flag;
                document.getElementById('pno').innerHTML=id;
                document.getElementById('p_deptt').value=party_info[0];
                document.getElementById('p_post').value=party_info[1];
                document.getElementById('p_add').value=party_info[3];
                document.getElementById('p_city').value=party_info[4];
                document.getElementById('p_pin').value=party_info[7];
                document.getElementById('p_st').value=party_info[5];
                getDistrict(party_info[5]);
                document.getElementById('p_dis').value=party_info[6];
                document.getElementById('p_mob').value=party_info[9];
                document.getElementById('p_email').value=party_info[8];
                document.getElementById('post_code').value=party_info[10];
                document.getElementById('svbtn').disabled=false;
                document.getElementById('rstbtn').disabled=false;
                /*if(type=='D1')
                {
                    if(party_info[11]!=0)
                        document.getElementById('state_depar tment_in').value=party_info[11]+'->'+party_info[12];
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
        //document.getElementById('tr_for_individual').style.display='visible';
        document.getElementById('tr_for_individual').style.display='table-row';
        document.getElementById('span_mask_name').style.display='none';
        //document.getElementById('tr_d1').style.display='none';
        document.getElementById('mask_check').checked=false;
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
        document.getElementById('tr_for_individual').style.display='none';
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
    // alert("As per Hon'ble Court Order dated. 08-02-2018 in MA no. 1158/2017(Suraj India Trust Vs UOI), Registry is directed not to accept any application or petition on behalf of Suraj India Trust or Mr. Rajiv Daiya");

// alert("Registry is directed not to accept any application or petition on behalf of:- \n 1) Suraj India Trust or Mr. Rajiv Daiya as per Hon'ble Court Order dated. 08-02-2018 in MA no. 1158/2017(Suraj India Trust Vs UOI) \n 2) ASOK PANDE as per Hon'ble Court Order dated. 26-10-2018 in WP(C) No. 965/2018 (ASOK PANDE Vs UOI) \n 3) MANOHAR LAL SHARMA  as per Hon'ble Court Order dated. 07-12-2018 in WP(CRL) No. 315/2018 (MANOHAR LAL SHARMA Vs ARUN JAITLEY (AT PRESENT FINANCE MINISTER))");


    var party_type = document.getElementById('party_type').value;
    var party_flag = document.getElementById('party_flag');
    var party_no=document.getElementById('pno').innerHTML;
    // var case=document.getElementById("ddlList").value ;
    var p_name,p_rel,p_rel_name,p_sex,p_age,p_post,p_deptt,p_occ,p_edu,p_masked_name;



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
                alert(" Party name is masked. Dealing Assitant may take further action for masking of party details in ROP or Judgments accordingly!!!!");
            }
            else{
                alert('Please Enter Masked Name');masked_name.focus();return false;
            }
        }
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
        p_statename = document.getElementById('p_statename');
        p_post = document.getElementById('p_post');
        p_deptt = document.getElementById('p_deptt');

        //p_causetitle = document.getElementById('p_causetitle');
        //d_causetitle = document.getElementById('d_causetitle');
        //alert(document.getElementById('d_causetitle').checked );
        /*  if(party_no=='1'){
              if(!document.getElementById('s_causetitle').checked && !document.getElementById('d_causetitle').checked && !document.getElementById('p_causetitle').checked) {
                  alert('Please check at least one checkbox');
                  return false;
              }
          } */
//        if(p_statename.value=='' && document.getElementById('s_causetitle').checked  && party_no=='1')
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
    }
    if(document.getElementById('p_add').value=="")
    {
        alert('Please Enter Party Address');document.getElementById('p_add').focus();return false;
    }
    if(document.getElementById('p_city').value=="")
    {
        alert('Please Enter Party City');document.getElementById('p_city').focus();return false;
    }
    if(document.getElementById('p_cont').value=="96"){
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
    /*
        if(document.getElementById("lower_case").value == "" && (document.getElementById("hd_casetype").value!=5 && document.getElementById("hd_casetype").value!=6 && document.getElementById("hd_casetype").value!=24  && document.getElementById("hd_casetype").value!=17 ))
    */

    if(document.getElementById("lower_case").value == "" && (document.getElementById("hd_allow_user").value!=1 && document.getElementById("hd_casetype").value!=5 && document.getElementById("hd_casetype").value!=6 && document.getElementById("hd_casetype").value!=24  && document.getElementById("hd_casetype").value!=17 && document.getElementById("hd_casetype").value!='22' && document.getElementById("hd_casetype").value!='27' && document.getElementById("hd_casetype").value!='34'  && document.getElementById("hd_casetype").value!='35' && document.getElementById("hd_casetype").value!='37' && document.getElementById("hd_casetype").value!='36' && document.getElementById("hd_casetype").value!='38'  && document.getElementById("hd_casetype").value!='32' && document.getElementById("hd_casetype").value!='33'))
    {
        alert('Please Select Lower Court Case');
        lower_case.focus();return false;
    }
    var remark_del='';
    if($("#p_status").val()=='O' || $("#p_status").val()=='D'){
        remark_del = $("#remark_delete").val();
        if(remark_del==''){
            alert('Please Enter Remark for Deletion/Disposal of Party');
            $("#remark_delete").focus();
            return false;
        }
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

    if($("#party_flag")!='I'){
        if($("#order1").val() == $("#order2").val() == $("#order3").val()){
            alert('All Orders Can not be same');
            return false;
        }
        else{
            if(($("#order1").val() == $("#order2").val())&& ($("#order1").val()!='' && $("#order2").val()!='')){
                alert('Order1 and Order2 Can not be same **** !!! ');
                return false;
            }
            else if(($("#order2").val() == $("#order3").val())&& ($("#order2").val()!='' && $("#order3").val()!='')){
                alert('Order2 and Order3 Can not be same');
                return false;
            }
            else if(($("#order1").val() == $("#order3").val())&& ($("#order1").val()!='' && $("#order3").val()!='')){
                alert('Order1 and Order3 Can not be same');
                return false;
            }
        }
    }

    alert("Registry is directed not to accept any application or petition on behalf of:- \n 1) Suraj India Trust or Mr. Rajiv Daiya as per Hon'ble Court Order dated. 08-02-2018 in MA no. 1158/2017(Suraj India Trust Vs UOI) \n 2) ASOK PANDE as per Hon'ble Court Order dated. 26-10-2018 in WP(C) No. 965/2018 (ASOK PANDE Vs UOI) \n 3) MANOHAR LAL SHARMA  as per Hon'ble Court Order dated. 07-12-2018 in WP(CRL) No. 315/2018 (MANOHAR LAL SHARMA Vs ARUN JAITLEY (AT PRESENT FINANCE MINISTER))\n" +
        " 4) P1-SURAJ MISHRA and P2-ROHIT GUPTA  as per Hon'ble Court Order dated. 08-05-2019 in WP(C) No. 1328/2018 (SURAJ MISHRA AND ANR VS. UNION OF INDIA AND ANR)");

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
            //alert(res);
            if(res=="1"){
                alert('Please add more parties before deleting this party');
            }
            res = res.split('!~!');
            document.getElementById('result2').innerHTML=res[1];
            document.getElementById('table_show').innerHTML="Please Wait While We Are Fatching Parties Information Again";
            call_fullReset_extra();
            call_fetch_infoAgain(document.getElementById('hdfno').value);
            call_fetch_causetitle(document.getElementById('hdfno').value);
            //window.location.assign("../addentry/get_extraparty_mod.php?fno="+document.getElementById('hdfno').value);
        }
    }
    var url = "save_new_filing_extraparty.php?controller=U&fno="+document.getElementById('hdfno').value
        +"&p_f="+party_flag.value+"&hd_p_f="+document.getElementById('hd_party_flag').value;

    if(party_type=="I")
        url = url+"&p_type="+party_type+"&p_name="+p_name.value+"&p_rel="+p_rel.value+"&p_rel_name="+p_rel_name.value
            +"&p_sex="+p_sex.value+"&p_age="+p_age.value+"&p_occ="+document.getElementById('p_occ').value
            +"&p_edu="+document.getElementById('p_edu').value+"&p_caste="+document.getElementById('p_caste').value
            +"&p_occ_code="+document.getElementById('p_occ_hd_code').value+"&p_edu_code="+document.getElementById('p_edu_hd_code').value
            +"&p_masked_name="+document.getElementById('masked_name').value     ;

    if($("#p_statename").val()=='')
        $("#p_statename_hd").val('0');

    if(party_type!="I")
        url = url+"&p_type="+party_type+"&p_post="+p_post.value+"&p_deptt="+p_deptt.value+"&p_statename="+$("#p_statename").val()
            +"&p_statename_hd="+$("#p_statename_hd").val()+"&d_code="+$("#p_deptt_hd").val()+"&p_code="+document.getElementById('post_code').value;

    url = url + "&p_add=" + document.getElementById('p_add').value + "&p_city=" + document.getElementById('p_city').value
        + "&p_pin=" + document.getElementById('p_pin').value + "&p_dis=" + document.getElementById('p_dis').value
        + "&p_st=" + document.getElementById('p_st').value + "&p_cont=" + document.getElementById('p_cont').value + "&p_mob=" + document.getElementById('p_mob').value
        + "&p_email=" + document.getElementById('p_email').value + "&p_no=" + document.getElementById('pno').innerHTML
        + "&p_sta=" + document.getElementById('p_status').value + "&lowercase=" + $('#lower_case').val() + "&remark_lrs=" + remark_lrs + "&remark_del=" + remark_del
        + "&add_add=" + add_addresses + "&cont_pro_info=" + $("#p_cntpro").val();

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
            +"&p_causetitle="+p_ct+"&party_name="+document.getElementById('party_name').value
    }

    //var state_department_in = $("#state_department_in").val().split("->");
    //url = url +"&d_code="+state_department_in[0];
    url = encodeURI(url);
    xmlhttp.open("GET",url,false);
    xmlhttp.send(null);
}
function call_fetch_causetitle(value)
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
            document.getElementById('table_causetitle').innerHTML=xmlhttp.responseText;
        }
    }
    var url = "cause_title_mod.php?fno="+value;
    //var url = "get_extraparty_mod.php?fno="+value;
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
    var url = "firse_partyinfo_mod.php?fno="+value;
    //var url = "get_extraparty_mod.php?fno="+value;
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
        document.getElementById('mask_check').checked=false;
        document.getElementById('masked_name').value="";
        document.getElementById('span_mask_name').style.display='none';

    }
    else if(document.getElementById('party_type').value!='I')
    {
        document.getElementById('p_post').value="";
        document.getElementById('p_deptt').value="";
        document.getElementById('s_causetitle').checked=false;
        document.getElementById('d_causetitle').checked=false;
        document.getElementById('p_causetitle').checked=false;
        document.getElementById('tr_for_individual').style.display='none';

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
    document.getElementById('lower_case').value="";
    //$("#sel_lrstolrs").val("");
    $("#for_selecting_lrs").css('display','none');
    //$("#remark_lrs_row").css('display','none');
    $("#remark_delete").prop('disabled',true);
    $("#remark_delete").val("");
    $("#remark_lrs").val("");
    //document.getElementById('tr_d1').style.display='none';
    //document.getElementById('state_department_in').value='';
    if($("#hd_add_add_count").val()>0){
        for(var i=1;i<=$("#hd_add_add_count").val();i++){
            if($("#add-add_table_"+i)){
                $("#add-add_table_"+i).remove();
                $("#hr_"+i).remove();
            }
        }
        $("#hd_add_add_count").val(0);
    }
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
    xmlhttp.open("GET","../filing/get_district.php?state="+val,false);
    xmlhttp.send(null);
}


$(document).on("change","#p_status",function(){
    if($(this).val()=='O' || $(this).val()=='D')
        $("#remark_delete").removeProp('disabled');
    else if($(this).val()!='O')
        $("#remark_delete").prop('disabled',true);
});

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
        $("#st_"+num[1]).val("");
        $("#dis_"+num[1]).prop('disabled',true);
        $("#dis_"+num[1]).val("");
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
        document.getElementById('masked_name').value='';
        document.getElementById('span_mask_name').style.display='inline-block';
    }
    else{
        document.getElementById('masked_name').value='';
        document.getElementById('span_mask_name').style.display='none';
    }


}