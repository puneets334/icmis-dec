/**
 * Created by aktripathi on 22/6/17.
 */
/**
 * Created by aktripathi on 5/6/17.
 */


/*function getCasesForGeneration() {
    //alert("1");
    var causelistDate = $('#causelistDate').val();
    var pJudge = $('#pJudge').val();
    var causelistType = $('#causelistType').val();
    var bench = $('#bench').val();
    var usercode=$('#usercode').val();
    if(causelistDate == ""){
        alert("Please fill Causelist Date..");
        $('#causelistDate').focus();
        return false;
    }
    if(pJudge == ""){
        alert("Please Select Presiding Judge..");
        $('#pJudge').focus();
        return false;
    }
    if(causelistType == ""){
        alert("Please Select Type of Causelist..");
        $('#causelistType').focus();
        return false;
    }
    if(bench == ""){
        alert("Please Select Bench..");
        return false;
    }

    if (causelistDate != "" && pJudge != "" && causelistType!="" & bench!=""){
        //alert("2");
        $.get("CourtMasterController/getCasesForGeneration", {causelistDate: causelistDate, pJudge:pJudge, causelistType:causelistType,bench:bench,usercode:usercode},function(result){

            alert(usercode);
            $("#divCasesForGeneration").html(result);
            $('#tblCasesForGeneration').DataTable({
                "bSort": false,
                "bPaginate": false,
                "bLengthChange": false,
                "bInfo": false
            } );
        });
    }
}*/
/*function getBenches() {
    var causelistDate = $('#causelistDate').val();
    var courtNo = $('#courtNo').val();
    var causelistType = $('#causelistType').val();
    if (causelistDate != "" && courtNo != ""){
        $.get("CourtMasterController/getBench", {causelistDate: causelistDate, courtNo:courtNo, causelistType:causelistType},function(result){
            $("#bench").empty();
            $("#bench").append(result);
        });
    }
}*/
/*function getBenches() {
    var causelistDate = $('#causelistDate').val();
    var pJudge = $('#pJudge').val();
    var causelistType = $('#causelistType').val();
    if(causelistDate == ""){
        alert("Please fill Causelist Date..");
        return false;
    }
    if(pJudge == ""){
        alert("Please Select Presiding Judge..");
        return false;
    }
    if(causelistType == ""){
        alert("Please Select Type of Causelist..");
        return false;
    }
    if (causelistDate != "" && pJudge != "" && causelistType!=""){
        $.get("CourtMasterController/getBench", {causelistDate: causelistDate, pJudge:pJudge, causelistType:causelistType},function(result){
            $("#divCasesForGeneration").html("");
            $("#bench").empty();
            $("#bench").append(result);
        });
    }
}*/

function selectallMe() {
    var checkBoxList=$('[name="proceeding[]"]');

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

function generateAndDownloadROP(){
    var causelistDate = $('#causelistDate').val();
    var pJudge = $('#pJudge').val();
    var causelistType = $('#causelistType').val();
    var bench = $('#bench').val();
    var usercode=$('#usercode').val();
    var user2=$('#user2').val();
    if(causelistDate == ""){
        alert("Please fill Causelist Date..");
        $('#causelistDate').focus();
        return false;
    }
    if(pJudge == ""){
        alert("Please Select Presiding Judge..");
        $('#pJudge').focus();
        return false;
    }
    if(causelistType == ""){
        alert("Please Select Type of Causelist..");
        $('#causelistType').focus();
        return false;
    }
    if(bench == ""){
        alert("Please Select Bench..");
        return false;
    }
   /* var checkBoxList=$('[name="proceeding"]');
    alert(checkBoxList.length);*/
    var selectedCases = [];
    $('#tblCasesForGeneration input:checked').each(function() {
        if($(this).attr('name')!='allCheck')
            selectedCases.push($(this).attr('value'));
    });
    if(selectedCases.length<=0){
        alert("Please Select at least one case for generation..");
        return false;
    }
    if(user2=="" ||user2=="0")
    {
        alert("Please Select name of Signatory Authority 2.");
        return false;
    }

}
function selectItem() {
    var checkBoxList=document.getElementsByName("proceeding[]");
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
/*
$(function() {
    alert(1);
    if ($('#cmaU')['length'] && $('#cmaUn')['length'] && $('#cmaDol')['length']) {
        alert('123');
        $('.image-inline-upward')['append']('<APPLET CODE="in/nic/sci/courtmaster/UploadApplet.class" ARCHIVE="CMAppletSP.jar,commons-io-2.0.1.jar,commons-lang-2.1.jar,java-json.jar,zxing-core-3.2.1.jar" WIDTH=250 HEIGHT=30><param name="user" value="' + $('#cmaU')['val']() + '"><param name="appletdate" value="' + $('#cmaDol')['val']() + '"><param name="username" value="' + $('#cmaUn')['val']() + '"><param name="uploadUrl" value="' + $('#cmaUUrl')['val']() + '"></APPLET>')
    }
});*/











