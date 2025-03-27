/**
 * Created by aktripathi on 5/6/17.
 */

function addEditPilDetail(ecPilId, basePath){
    window.location.href = basePath+"index.php/PilController/editPilData/"+ ecPilId ;
    $("select#actionTaken").change();
}
function convertToUpperCase(field){
    field.value=field.value.toUpperCase();
}
function todaysDate(){
    if($("#destroyOrKeepInDate").val()=="" || $("#destroyOrKeepInDate").val()==null){
        $("#destroyOrKeepInDate").datepicker().datepicker("setDate", new Date());
    }
}

function checkDiarynumber()
{
    var diaryNo=document.getElementById("diaryNo").value;
    var diaryYear=document.getElementById("diaryYear").value;
    if(diaryNo==""){
        alert("Please Enter Inward Number");
        document.getElementById("diaryNo").focus();
        return false;
    }
    if(diaryYear==""){
        alert("Please Enter Inward Year");
        document.getElementById("diaryYear").focus();
        return false;
    }
    //alert("Test");
    document.getElementById("frmGetPilDetail").submit();
}
function checkDiarynumberToAddInGroup()
{
    var diaryNo=document.getElementById("diaryNo").value;
    var diaryYear=document.getElementById("diaryYear").value;
    var ecPilGroupId=document.getElementById("ecPilGroupId").value;
    if(ecPilGroupId==0){
        alert("Please Select Group to add.");
        document.getElementById("ecPilGroupId").focus();
        return false;
    }
    if(diaryNo==""){
        alert("Please Enter Inward Number");
        document.getElementById("diaryNo").focus();
        return false;
    }
    if(diaryYear==""){
        alert("Please Enter Inward Year");
        document.getElementById("diaryYear").focus();
        return false;
    }
    //alert("Test");
    document.getElementById("frmAddToPilGroup").submit();
}
function showGroupCases(){
    document.getElementById("frmAddToPilGroup").submit();
}
function showgroup() {
    var str=document.getElementById("pilCategory").value;
    if(str==18){
        document.getElementById("otherGroup").style.display = "";
        document.getElementById("otherTextlabel").style.display = "";
    }
    else{
        document.getElementById("otherGroup").style.display = "none";
        document.getElementById("otherTextlabel").style.display = "none";
    }
}
function selectallMe() {
    var checkBoxList=$('[name="pils[]"]');

    if ($('#allCheck').is(':checked'))
    {

        for (var i1 = 0; i1<checkBoxList.length; i1++){
            checkBoxList[i1].checked=true;
        }

    }else{
        for (var i1 = 0; i1<checkBoxList.length; i1++){
            checkBoxList[i1].checked=false;
        }
    }
}

jQuery.fn.ForceNumericOnly =
    function()
    {
        return this.each(function()
        {
            $(this).keydown(function(e)
            {
                var key = e.charCode || e.keyCode || 0;
                // allow backspace, tab, delete, enter, arrows, numbers and keypad numbers ONLY
                // home, end, period, and numpad decimal
                return (
                key == 8 ||
                key == 9 ||
                key == 46 ||
                key == 13 ||
                (key >= 35 && key <= 40) ||
                (key >= 48 && key <= 57) ||
                (key >= 96 && key <= 105));
            });
        });
    };
$("#mobileno").ForceNumericOnly();
$("#convertedDiaryNumber").ForceNumericOnly();
$("#diaryNo").ForceNumericOnly();
$("#diaryYear").ForceNumericOnly();

function goBack(basePath,userid)
{
    //window.location.href = "../" ;
    //window.history.back();
    window.location.href = basePath+"/PIL/PilController/index" ;

}
function showHide1(id)
{
    var queryFields=document.getElementById("actionTaken").value;
    //alert('Selected Action: '+queryFields);
    if(queryFields=='0'){
        //alert("Inside 0");
        document.getElementById("divNoAction").style.display = "none";
        document.getElementById("divWrittenLetter").style.display="none";
        document.getElementById("divReturn").style.display="none";
        document.getElementById("divSentTo").style.display="none";
        document.getElementById("divTransferredTo").style.display="none";
        document.getElementById("divConvertTo").style.display="none";
        document.getElementById("divOtherRemedy").style.display="none";
    }

    if(queryFields=='L'){
        document.getElementById("divNoAction").style.display = "";
        document.getElementById("divWrittenLetter").style.display="none";
        document.getElementById("divReturn").style.display="none";
        document.getElementById("divSentTo").style.display="none";
        document.getElementById("divTransferredTo").style.display="none";
        document.getElementById("divConvertTo").style.display="none";
        document.getElementById("divOtherRemedy").style.display="none";
    }
    else if(queryFields=='W'){
        document.getElementById("divNoAction").style.display = "none";
        document.getElementById("divWrittenLetter").style.display="";
        document.getElementById("divReturn").style.display="none";
        document.getElementById("divSentTo").style.display="none";
        document.getElementById("divTransferredTo").style.display="none";
        document.getElementById("divConvertTo").style.display="none";
        document.getElementById("divOtherRemedy").style.display="none";
    }else if(queryFields=='R'){
        document.getElementById("divNoAction").style.display = "none";
        document.getElementById("divWrittenLetter").style.display="none";
        document.getElementById("divReturn").style.display="";
        document.getElementById("divSentTo").style.display="none";
        document.getElementById("divTransferredTo").style.display="none";
        document.getElementById("divConvertTo").style.display="none";
        document.getElementById("divOtherRemedy").style.display="none";
    }else if(queryFields=='S'){
        document.getElementById("divNoAction").style.display = "none";
        document.getElementById("divWrittenLetter").style.display="none";
        document.getElementById("divReturn").style.display="none";
        document.getElementById("divSentTo").style.display="";
        document.getElementById("divTransferredTo").style.display="none";
        document.getElementById("divConvertTo").style.display="none";
        document.getElementById("divOtherRemedy").style.display="none";
    }else if(queryFields=='T'){
        document.getElementById("divNoAction").style.display = "none";
        document.getElementById("divWrittenLetter").style.display="none";
        document.getElementById("divReturn").style.display="none";
        document.getElementById("divSentTo").style.display="none";
        document.getElementById("divTransferredTo").style.display="";
        document.getElementById("divConvertTo").style.display="none";
        document.getElementById("divOtherRemedy").style.display="none";
    }
    else if(queryFields=='I'){
        document.getElementById("divNoAction").style.display = "none";
        document.getElementById("divWrittenLetter").style.display="none";
        document.getElementById("divReturn").style.display="none";
        document.getElementById("divSentTo").style.display="none";
        document.getElementById("divTransferredTo").style.display="none";
        document.getElementById("divConvertTo").style.display="";
        document.getElementById("divOtherRemedy").style.display="none";
    }
    else if(queryFields=='O'){
        document.getElementById("divNoAction").style.display = "none";
        document.getElementById("divWrittenLetter").style.display="none";
        document.getElementById("divReturn").style.display="none";
        document.getElementById("divSentTo").style.display="none";
        document.getElementById("divTransferredTo").style.display="none";
        document.getElementById("divConvertTo").style.display="none";
        document.getElementById("divOtherRemedy").style.display="";
    }
    showgroup();
}
function submitMethod(basePath,userid){

    $('#btnSave').val('Please wait ...').attr('disabled','disabled');

    var mobile = document.getElementById("mobileno").value;
    if(mobile.length>1 && mobile.length <10)
    {
        alert("Please enter 10 digit mobile no");
        document.getElementById("mobileno").focus();
        return false;
    }
    /*var date1=document.getElementById("receivedOn").value;
    var date2=document.getElementById("petitionDate").value;
    var notificationDateCalendar = date1;
    var notificationDateArray = notificationDateCalendar.split("-");
    var commonDate1 = new Date("");
    commonDate1.setFullYear(notificationDateArray[2],notificationDateArray[1]-1,notificationDateArray[0]);
    notificationDateCalendar1 = date2;
    var notificationDateArray1 = notificationDateCalendar1.split("-");
    var commonDate2 = new Date();
    commonDate2.setFullYear(notificationDateArray1[2],notificationDateArray1[1]-1,notificationDateArray1[0]);*/



    if (document.getElementById("destroyOrKeepIn")=="Y")
    {

        var destroyOrKeepInDate=document.getElementById('destroyOrKeepInDate').value;
        if (destroyOrKeepInDate == "" || destroyOrKeepInDate == 0 || destroyOrKeepInDate==null)
        {
            alert("Please enter 'Destroy On '");
            document.getElementById('destroyOrKeepInDate').focus();
            return false;
        }
        var lodgementDate = document.getElementById("lodgementDate").value;
        var lodgementDateArray = lodgementDate.split("-");
        commonDate2.setFullYear(lodgementDateArray[2],lodgementDateArray[1]-1,lodgementDateArray[0]);
        //var notificationDateCalendar = destroyOrKeepInDate;
        var destroyOrKeepInDateArray = destroyOrKeepInDate.split("-");
        commonDate1.setFullYear(notificationDateArray[2],notificationDateArray[1]-1,notificationDateArray[0]);


        if (document.getElementById("reportReceived")=="Y")
        {

            var notificationDateCalendar2 = document.getElementById("reportDate").value;
            var notificationDateArray2 = notificationDateCalendar2.split("-");
            var commonDate3 = new Date();
            commonDate3.setFullYear(notificationDateArray2[2],notificationDateArray2[1]-1,notificationDateArray2[0]);
            if (days_between(commonDate1, commonDate3)<1095)
            {
                alert("PIL can be destroyed only after 3 years of Report Received");
                return false;
            }

        }
        if ((document.getElementById("reportReceived")=="N" || document.getElementById("reportReceived")=="") && ((days_between(commonDate1, commonDate2)<365)))
        {
            alert("PIL can be destroyed only after 1 year of lodgement");
            return false;

        }

    }


    if (document.getElementById("destroyOrKeepIn")=="N")
    {

        var J3=document.getElementById('destroyOrKeepInDate').value;
        if (J3 == "" || J3 == 0)
        {
            alert("Please enter 'Kept In Record  '");
            document.getElementById('destroyOrKeepInDate').focus();
            return false;
        }
    }
    savePilData(basePath,userid);
}
function addEditPilGroupDetail(ecPilGroupId, basePath){
    window.location.href = basePath+"/PIL/PilController/editPilGroupData/"+ ecPilGroupId ;
}
function goBackToPilGroup(){
    window.location.href = "../showPilGroup/" ;
}
function submitPilGroupMethod(basePath){
    var groupFileNumber = document.getElementById("groupFileNumber").value;
    if(groupFileNumber.length==0 || groupFileNumber.trim()=='')
    {
        alert("Please enter Group File Number");
        document.getElementById("groupFileNumber").focus();
        return false;
    }
    savePilGroupData(basePath);
}

    function savePilGroupData(basePath){
        $.ajax({
            type: 'POST',
            url: basePath+'index.php/PilController/savePilGroupData',
            data: $("#frmPilGroupAddEdit").serialize(),
            cache: false,
            success: function (result) {
                if (result == '1') {
                    alert("Saved Successfully.");
                    $("#success-msg").removeClass('hidden');
                    $('#success-msg').html("<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">×</button>\n" +
                        "                                <h4><i class=\"icon fa fa-check\"></i> Success!</h4>PIL File Group information Saved Successfully.");
                } else {
                    $("#error-msg").removeClass('hidden');
                    $('#error-msg').html(" <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">×</button>\n" +
                        "                                <h4><i class=\"icon fa fa-ban\"></i> Alert!</h4>There is some problem while saving data,Please Contact Computer Cell.");
                }
            }
        });

    }
function savePilData(basePath,userid){

  
    var mobile = document.getElementById("mobileno").value;
    
    if(mobile.length>1 && mobile.length <10)
    {
        alert("Please enter 10 digit mobile no");
        document.getElementById("mobileno").focus();
        return false;
    }
    if (document.getElementById("destroyOrKeepIn")=="Y")
    {
        var destroyOrKeepInDate=document.getElementById('destroyOrKeepInDate').value;
        if (destroyOrKeepInDate == "" || destroyOrKeepInDate == 0 || destroyOrKeepInDate==null)
        {
            alert("Please enter 'Destroy On '");
            document.getElementById('destroyOrKeepInDate').focus();
            return false;
        }
        var lodgementDate = document.getElementById("lodgementDate").value;
        var lodgementDateArray = lodgementDate.split("-");
        commonDate2.setFullYear(lodgementDateArray[2],lodgementDateArray[1]-1,lodgementDateArray[0]);
        //var notificationDateCalendar = destroyOrKeepInDate;
        var destroyOrKeepInDateArray = destroyOrKeepInDate.split("-");
        commonDate1.setFullYear(notificationDateArray[2],notificationDateArray[1]-1,notificationDateArray[0]);


        if (document.getElementById("reportReceived")=="Y")
        {

            var notificationDateCalendar2 = document.getElementById("reportDate").value;
            var notificationDateArray2 = notificationDateCalendar2.split("-");
            var commonDate3 = new Date();
            commonDate3.setFullYear(notificationDateArray2[2],notificationDateArray2[1]-1,notificationDateArray2[0]);
            if (days_between(commonDate1, commonDate3)<1095)
            {
                alert("PIL can be destroyed only after 3 years of Report Received");
                return false;
            }

        }
        if ((document.getElementById("reportReceived")=="N" || document.getElementById("reportReceived")=="") && ((days_between(commonDate1, commonDate2)<365)))
        {
            alert("PIL can be destroyed only after 1 year of lodgement");
            return false;

        }

    }


    if (document.getElementById("destroyOrKeepIn")=="N")
    {

        var J3=document.getElementById('destroyOrKeepInDate').value;
        if (J3 == "" || J3 == 0)
        {
            alert("Please enter 'Kept In Record  '");
            document.getElementById('destroyOrKeepInDate').focus();
            return false;
        }
    }
    //$('#btnSave').val('Please wait ...').attr('disabled','disabled');
    $("#btnSave").attr('disabled','disabled');
    $("#btnSave").text('Please wait..');
    $.ajax({
        type: 'POST',
        url: basePath+'/PIL/PilController/savePilData',
        data: $("#frmPilAddEdit").serialize(),
        success: function (result) {
            if (result == 'Success') {
                alert("Saved Successfully");
                goBack(basePath,userid);
            }
            else if (result != 'Error') {
                alert("Saved Successfully as Inward Number "+result);
                goBack(basePath,userid);
            } else {
                alert("There is some problem.");
            }
        }
    });
    return false;
}
function submitGroupUpdationMethod(basePath)    {
    var groupFileNumber = document.getElementById("groupFileNumber").value;

    if(groupFileNumber.length==0 || groupFileNumber.trim()=='')
    {
        alert("Please enter Group File Number");
        return false;
    }
    var selectedCases = [];
    $('#tblCasesForAction input:checked').each(function() {
        if($(this).attr('name')!='allCheck')
            selectedCases.push($(this).attr('value'));
    });
    if(selectedCases.length<=0){
        alert("Please Select at least one case for Action..");
        return false;
    }
    $.ajax({
        type: 'POST',
        url: basePath+'index.php/PilController/groupUpdate',
        data: $("#frmPilGroupAddEdit").serialize(),
        cache: false,
        success: function (result) {
            if (result == '1') {
                $("#success-msg").removeClass('hidden');
                $('#success-msg').html("<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">×</button>\n" +
                    "                                <h4><i class=\"icon fa fa-check\"></i> Seccess!</h4>All PILs in this Group Updated Successfully.");
            } else {
                $("#error-msg").removeClass('hidden');
                $('#error-msg').html(" <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">×</button>\n" +
                    "                                <h4><i class=\"icon fa fa-ban\"></i> Alert!</h4>There is some problem while saving data,Please Contact Computer Cell.");
            }
        }
    });

}






