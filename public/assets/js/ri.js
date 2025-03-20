/**
 * Created by aktripathi on 6/6/19.
 */

function addEditReceiptDetail(ecRIId, basePath){
    //alert(basePath);
    window.location.href = basePath+"index.php/RIController/editReceiptData/"+ ecRIId ;
    //$("select#actionTaken").change();
}
function convertToUpperCase(field){
    field.value=field.value.toUpperCase();
}

function checkDiarynumber()
{
    var diaryNo=document.getElementById("diaryNo").value;
    var diaryYear=document.getElementById("diaryYear").value;
    if(diaryNo==""){
        alert("Please Enter Diary Number");
        document.getElementById("diaryNo").focus();
        return false;
    }
    if(diaryYear==""){
        alert("Please Enter Diary Year");
        document.getElementById("diaryYear").focus();
        return false;
    }
    //alert("Test");
    document.getElementById("frmGetRIDetail").submit();
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
    window.location.href = basePath+"index.php/RIController/get_session/"+ userid ;

}
function selectallMe() {
    var checkBoxList=$('[name="daks[]"]');

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
function selectDak() {
    var checkBoxList=document.getElementsByName("daks[]");
    for (var i1 = 0; i1<checkBoxList.length; i1++){
        checkBoxList[i1].checked=false;
    }
    var l=document.getElementById("snoselect").value;
    temp = l.split(",");
    var t;
    var p;
    var s=new Array();
    var j=0;
    for (a in temp )
    {
        t=temp[a].split("-");
        var k=parseInt(t.length);
        if(k==2)
        {
            var f=parseInt(t[0]);
            var f1=parseInt(t[1]);
            for(var h=f;h<=f1;h++)
            {
                s[j]=h;
                j=j+1;
            }
        }
        else
        {
            s[j]=temp[a];
            j=j+1;
        }
    }
    for (l=0;l<s.length;l++)
    {
        var checkitemno="";
        var i=0;
        var selectedCases = [];
        var tab = document.getElementById ( "tblCasesForGeneration" ); // table with id tbl1
        var elems = tab.getElementsByTagName ( "input" );
        var len = elems.length;

        for ( var ai = 0; ai < len; ai++ )
        {
            if ( elems[ai].type == "checkbox" )
            {
                selectedCases.push(elems[ai].value);
            }
        }
        if(selectedCases.length>0){
            for(var eleentno=0; eleentno<selectedCases.length;eleentno++){
                filename=selectedCases[eleentno];
                checkitemno=filename.substring(filename.lastIndexOf('#')+1);
                checkitemno=parseInt(checkitemno);
                if(checkitemno==parseInt(s[l]))
                {
                    checkBoxList[i-1].checked=true;
                }
                i=i+1;
            }
        }
    }
}

function saveRIData(basePath,userid){
    var receiptMode=document.getElementById("receiptMode").value;
    var caseType=document.getElementById("caseType").value;
    var sentToUserType=$("#sentToUserType").val();
    var isOpenable = $("input[name='isOpenable']:checked").val();
    var isOriginal = $("input[name='isOriginal']:checked").val();
    if(receiptMode==0){
        alert("Please select Parcel Receipt mode.");
        return false;
    }
    if(caseType!="0"){
        if($("#caseNumber").val().trim()==""){
            alert("Please enter case number");
            $("#caseNumber").focus();
            return false;
        }
    }
    if(!sentToUserType){
        alert("Please select Letter For..");
        $("#sentToUserType").focus();
        return false;
    }
    else if(sentToUserType=='s' && $("#dealingSection").val()==0){
        alert("Please select Section Name..");
        $("#dealingSection").focus();
        return false;
    }
    else if(sentToUserType=='o' && $("#officer").val()==0){
        alert("Please select Officer Name..");
        $("#officer").focus();
        return false;
    }
    else if(sentToUserType=='j' && $("#judge").val()==0){
        alert("Please select Hon'ble Judge Name..");
        $("#judge").focus();
        return false;
    }
    /*if(!isOpenable){
        alert("Please select openable or not.");
        return false;
    }
    if(!isOriginal){
        alert("Please select parcel is original recoed or not.");
        return false;
    }*/

    $('#btnSave').val('Please wait ...').attr('disabled','disabled');
    $.ajax({
        type: 'POST',
        url: basePath+'index.php/RIController/saveReceiptData',
        data: $("#frmRIAddEdit").serialize(),
        success: function (result) {
            if (result == 'Success') {
                alert("Saved Successfully");
                goBack(basePath,userid);
            }
            else if (result != 'Error') {
                alert("Saved Successfully as Diary Number "+result);
                addEditReceiptDetail(0, basePath);
            } else {
                alert("There is some problem.");
            }
        }
    });
    return false;
}
function showHideDiv(id)
{
    if(id=='0'){
        //alert("Inside 0");
        document.getElementById("divJudge").style.display = "none";
        document.getElementById("divOfficer").style.display="none";
        document.getElementById("divSection").style.display="none";
    }
    if(id=='a'){
        //alert("Inside 0");
        document.getElementById("divJudge").style.display = "none";
        document.getElementById("divOfficer").style.display="none";
        document.getElementById("divSection").style.display="none";
    }
    if(id=='j'){
        document.getElementById("divJudge").style.display = "";
        document.getElementById("divOfficer").style.display="none";
        document.getElementById("divSection").style.display="none";
    }
    else if(id=='o'){
        document.getElementById("divJudge").style.display = "none";
        document.getElementById("divOfficer").style.display="";
        document.getElementById("divSection").style.display="none";
    }else if(id=='s'){
        document.getElementById("divJudge").style.display = "none";
        document.getElementById("divOfficer").style.display="none";
        document.getElementById("divSection").style.display="";
    }
}
function showHideSentToUserType(id)
{
    //alert("Hello "+id);
    if(id==''){
        //alert("Inside 0");
        document.getElementById("divOther").style.display = "none";
        document.getElementById("divJudge").style.display = "none";
        document.getElementById("divOfficer").style.display="none";
        document.getElementById("divSection").style.display="none";
    }
    else if(id=='ot'){
        document.getElementById("divOther").style.display = "block";
        document.getElementById("divJudge").style.display = "none";
        document.getElementById("divOfficer").style.display="none";
        document.getElementById("divSection").style.display="none";
    }
    else if(id=='j'){
        document.getElementById("divOther").style.display = "none";
        document.getElementById("divJudge").style.display = "block";
        document.getElementById("divOfficer").style.display="none";
        document.getElementById("divSection").style.display="none";
    }
    else if(id=='o'){
        document.getElementById("divOther").style.display = "none";
        document.getElementById("divJudge").style.display = "none";
        document.getElementById("divOfficer").style.display="block";
        document.getElementById("divSection").style.display="none";
    }else if(id=='s'){
        document.getElementById("divOther").style.display = "none";
        document.getElementById("divJudge").style.display = "none";
        document.getElementById("divOfficer").style.display="none";
        document.getElementById("divSection").style.display="block";
    }
}
function dispatchDak(){
    alert("I am here");
    return true;
}
function ifValidCase(basePath){
    $("#divCaseDetails").empty();
    var isExecutable=false;
    var searchType='';
    var caseType= $("#caseType").val();
    var caseNumber= $("#caseNumber").val();
    var caseYear= $("#caseYear").val();

    var caseDiaryNo= $("#caseDiaryNo").val();
    var caseDiaryYear= $("#caseDiaryYear").val();
    if(caseType!=0 && caseNumber && caseYear!=0){
        isExecutable=true;
        $("#caseDiaryNo").val("");
        $("#caseDiaryYear").val("0");
        searchType='c';
    }
    else if(caseDiaryNo && caseDiaryYear!=0){
        isExecutable=true;
        searchType='d';
    }
    var myData = { 'caseType' : caseType, 'caseNumber' : caseNumber, 'caseYear' : caseYear, 'caseDiaryNo' : caseDiaryNo, 'caseDiaryYear' : caseDiaryYear, 'searchType' : searchType };
    if(isExecutable){
        $.ajax({
            type: 'POST',
            url: basePath+'index.php/RIController/ifValidCaseNumber',
            data: myData,
            success: function (result) {
                $("#divCaseDetails").html(result);
            }
        });
    }

}






