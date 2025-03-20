/**
 * Created by aktripathi on 17/5/17.
 */

/*$("input").keyup(function(){
 var txt = $("input").val();
 $.post("searchCaseForElimination", {suggest: txt}, function(result){
 $("span").html(result);
 });
 });*/

function getCaseForDisposal() {
    //alert("1");
    var caseType = $('#casetype').val();
    var caseNo = $('#caseno').val();
    var caseYear = $('#caseyear').val();
    if ($('#casetype').val() != "" && $('#caseno').val() != "" && $('#caseyear').val() != ""){
        //alert("2");
        $.post("searchCaseForElimination", {casetype: caseType, caseno:caseNo, caseyear:caseYear},function(result){
            //console.log(result);
            //alert(result);
            $("#eliminationdata").html(result);
            $('.myCheckbox').click(function() {
                $(this).closest('table').find('input:checkbox').not(this).prop('checked', false);
            });
        });
    }
}


function saveElimination() {

    /*var actionRequired = $('#actionRequired').val();
     var diary_no = $('#diary_no').val();
     var eliminationDate = $('#eliminationDate').val();
     var remark = $('#remark').val();

     var orderDate = $('#orderDate').val();
     var disposalDate = $('#disposalDate').val();
     var judge1 = $('#judge1').val();
     var judge2 = $('#judge2').val();
     var judge3 = $('#judge3').val();*/

    if (actionRequired != "" && diary_no != "" && eliminationDate != ""){
        //alert("2");
        $.post("updateElimination", $("#eliminationDetail").serialize(),function(result){
            //console.log(result);
            $("#eliminationdata").html(result);
            $(".alert").delay(4000).slideUp(200, function() {
                $(this).alert('close');
            });
        });
    }
    else{
        sweetAlert("Error!!", "Please select proper case", "error");
    }
}