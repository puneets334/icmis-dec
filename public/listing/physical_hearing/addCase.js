$(document).ready(function(){
    $("#radiodn").click(function(){
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

    $("input[name=btnGetR]").click(function(){
        call_getDetails(0);
    });
});

function call_getDetails(fno_from_del)
{
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
        url:"get_addCase_details.php",
        beforeSend: function (xhr) {
            $("#show_fil").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='../../images/load.gif'></div>");
        },
        data:{dno:diaryno,dyr:diaryyear,ct:cstype,cn:csno,cy:csyr,fno:fno_from_del}
    })
        .done(function(msg){
            $("#show_fil").html(msg);
        })
        .fail(function(){
            alert("ERROR, Please Contact Server Room");
        });
}

function onlynumbers(evt)
{
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    //alert(charCode);
    if ((charCode >= 48 && charCode<= 57)||charCode==9||charCode==8||charCode==37||charCode==39||charCode==46) {
        return true;
    }
    return false;
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


$(document).on('click','#savebutton',function(e) {
    var diary_no = $("#fil_hd").val();
    var usercode = $("#usercode").val();
    //var dataString = 'fil_no='+diary_no+'&usercode='+usercode;
    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
    updateCSRFToken();
    $.ajax({
        //data: dataString,
        type: "post",
        //url: "save_case_in_vacation_list.php",
        url: base_url+'/Listing/PhysicalHearing/save_case_in_vacation_list',
        data: {
            fil_no: diary_no,
            usercode: usercode,
            CSRF_TOKEN:CSRF_TOKEN_VALUE
        },
        success: function(data){
            updateCSRFToken();
            if(data.trim() == '1'){
                $("#show_fil").html("<center><span style='color:green;'>Record added to consent for physical hearing Pool</span></center>");
            }
            else{
                alert("Said case is already added in Pool.");
            }
        }
    });
});

