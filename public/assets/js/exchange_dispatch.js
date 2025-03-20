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
            url:"./get_s_file_rec.php",
            beforeSend: function (xhr) {
                $("#dv_res1").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='../images/load.gif'></div>");
            },
            data:{d_no:diaryno,d_yr:diaryyear,ct:cstype,cn:csno,cy:csyr,module:'dispatch'}
        })
        .done(function(msg){
            $("#dv_res1").html(msg);
    $("#dv_res2").html('');
                if($('input[name="chk[]"]:checked').length>0){
            $('#dispatch').prop('disabled',false);    
            }
            else{
            $('#dispatch').prop('disabled',true);    
            }
     get_user();  
//$("#user_div").show();
//            $("#result2").html("");
        })
        .fail(function(xhr, status, error){
            alert("ERROR, Please Contact Server Room"); 
        });
    });
$(document).on("click","#dispatch",function(){
    //debugger;
    var info_chk=[];
    var info_remarks=[];
    var chk_list='';
    var remarks_list='';
    var newArray=[];

    $('.chk').each(function (index, obj) {
        if (this.checked === true) {
            var id = $(this).attr("id");
            info_chk.push($(this).val());
           // info_remarks.push($(this).parents("tr").find(".txt_remarks").val());
        }
    });



    $('.chk').each(function() {
        if ($(this).is(':checked')) {
            var current = $(this).val();
            info_remarks.push($(this).parents("tr").find(".txt_remarks").val());
        }
    });

   // alert(info_remarks);

    $.each(info_chk , function(index, val) {
       chk_list += val;
    });




    $.each(info_remarks , function(index, val) {
        remarks_list+=val;
    });

     newArray = info_chk.map(function(value, index) {
        return value +'-'+ info_remarks[index];
    });


//debugger;
$.ajax({

    url: "save_record.php",
    type: "post",
    //data: $('.chk:checked').serialize() + "&module=dispatch" + "&user=" + $("#user").val(),
    data: "chk1= " + newArray + "&module=dispatch" + "&user=" + $("#user").val(),
    success: function(data) {
        //alert (data);
        if(data==''){
          $('#dv_res2').html('<p align=center><font color=red>Successfully Dispatched</font></p>');
          setTimeout(function() {
    $('input[name=btnGetR]').trigger('click');
}, 1000);
        }
        else
        {
    $('#dv_res2').html(data);
        }
    }
});
});
//});

$(document).on("change","#department",function(){

        var val = $("#department").val();
//        if(val == 'PAPS')
//            $("#for_judge_select").css("display","block");
//        else{
//            $("#for_judge_select").css("display","none");
//            $("#judges_id").val("");
//        }
        $.ajax({
            type: 'POST',
            url:"./user_mgmt_multiple.php?key=1",
            data:{deptname:val,cur_user_type:$("#cur_user_type").val()/*,auth:$("#authority").val(),auth_name:$("#auth_name").val()*/}
        })
        .done(function(msg){
            //alert(msg)
            var msgg = msg.indexOf("RESET");
            $("#section").html(msg);
            if(msgg>=0){
                if($("#cur_user_type").val()=='1')
                    $("#designation").html("<option value='ALL'>ALL</option>");
                else
                    $("#designation").html("<option value='0'>SELECT</option>");
            }
            else
                $("#designation").html("<option value='ALL'>ALL</option>");
            get_user();
        })
        .fail(function(){
            alert("ERROR, Please Contact Server Room");
        });
});

  $(document).on("change","#section", function(){
        var val = $("#section").val();
//        if($("#department").val() == 'PAPS')
//            $("#for_judge_select").css("display","block");
//        else{
//            $("#for_judge_select").css("display","none");
//            $("#judges_id").val("");
//        }

        $.ajax({
            type: 'POST',
            url: "./user_mgmt_multiple.php?key=2",
            data: {deptname:$('#department').val(),section:val,cur_user_type:$("#cur_user_type").val()/*,auth:$("#authority").val(),auth_name:$("#auth_name").val()*/}
        })
        .done(function(msg){
            //alert(msg);
            $("#designation").html(msg);
 get_user();            
        })
        .fail(function(){
            alert('Error, Please Contact Server Room');
        });
    });
    
 $(document).on("change","#designation",function(){
 get_user();  
        
//        if(value != 'ALL'){
//            $("#for_judge_select").css("display","none");
//            $("#judges_id").val("");
//        }
//        else{
//            if($("#department").val() == 'PAPS')
//                $("#for_judge_select").css("display","block");
//            else
//                $("#for_judge_select").css("display","none");
//        }
    });
});
    
    /*$("#caseno,#caseyear").keypress(function(evt){
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        //alert(charCode);
        if ((charCode >= 48 && charCode<= 57)||charCode==9||charCode==8||charCode==37||charCode==39) {
        return true;
        }
        return false; 
    });*/
function get_user(){
    var dept=$("#department").val();
    var sec=$("#section").val();
    var desig= $("#designation").val();
            $.ajax({
            type: 'POST',
            url:"./user_options.php",
            data:{dept:dept,sec:sec,desig:desig}
        }) 
        .done(function(msg){
$("#user").html(msg);
        }) 
        .fail(function(){
            $("#result").html("Error Occured, Please Contact server Room");
        });
} 
function receivethis(){
    if(confirm("Are You Sure to Receive this File")){
        var for_send_kb_cases = 0;
        if($("#chk_send_with_main")){
            if($("#chk_send_with_main").is(":checked")){
                for_send_kb_cases = $("#chk_send_with_main").val();
            }
            else
                for_send_kb_cases = 0;
        }
                
        $.ajax({
            type: 'POST',
            url: $("#hd_folder").val()+"/save_s_file_rec.php",
            data:{fil_no:$("#hd_fil_no").val(),user:$("#hd_ud").val(),kb_cases:for_send_kb_cases}
        }) 
        .done(function(msg){
            $("#result").css('text-align','center');
            $("#result").html(msg);
        }) 
        .fail(function(){
            $("#result").html("Error Occured, Please Contact server Room");
        });
    }
}


function OptionsSelected(me)
{
    if(me.id=="ckbCheckAll" && me.checked==true)
    {
        var inputs = document.getElementsByClassName("chk");
        for (var i = 0; i < inputs.length; i++) {
            if (inputs[i].disabled == false) {
                if (inputs[i].checked == false) {
                    inputs[i].checked = true;
                }

            }
        }
    }
    else if (me.id=="ckbCheckAll" && me.checked==false)
    {
        var inputs = document.getElementsByClassName("chk");
        for (var i = 0; i < inputs.length; i++) {
            if (inputs[i].disabled == false) {
                if (inputs[i].checked == true) {
                    inputs[i].checked = false;
                }

            }
        }
    }


    if(me.id=="ckbCheckSetA" && me.checked==true)
    {
       // $('.chk_2,.chk_3,.chk_4').attr('disabled','disabled');
        $('.chk').removeAttr('checked');

        var inputs = document.getElementsByClassName("chk_1");
        for (var i = 0; i < inputs.length; i++) {
            if (inputs[i].disabled == false) {
                if (inputs[i].checked == false) {
                    inputs[i].checked = true;
                }

            }
        }
    }
    else if (me.id=="ckbCheckSetA" && me.checked==false)
    {
        /*if ($('.chk_2,.chk_3,.chk_4').not(':disabled')) {
            $('.chk_2,.chk_3,.chk_4').removeAttr('disabled');
        }*/
       // $('.chk_2,.chk_3,.chk_4').removeAttr('disabled');
        var inputs = document.getElementsByClassName("chk_1");
        for (var i = 0; i < inputs.length; i++) {
            if (inputs[i].disabled == false) {
                if (inputs[i].checked == true) {
                    inputs[i].checked = false;
                }

            }
        }
    }

    if(me.id=="ckbCheckSetBCD" && me.checked==true)
    {
        //$('.chk_1').attr('disabled','disabled');
        $('.chk').removeAttr('checked');
        //document.getElementsByClassName("chk_1").setAttribute("disabled", "disabled");

        var inputs1 = document.getElementsByClassName("chk_2");
        var inputs2 = document.getElementsByClassName("chk_3");
        var inputs3 = document.getElementsByClassName("chk_4");
        for (var i = 0; i < inputs1.length; i++) {
            if (inputs1[i].disabled == false) {
                 if (inputs1[i].checked == false) {
                    inputs1[i].checked = true;

                }

            }
        }

        for (var i = 0; i < inputs2.length; i++) {
            if (inputs2[i].disabled == false) {
                 if (inputs2[i].checked == false) {
                    inputs2[i].checked = true;

                }

            }
        }

        for (var i = 0; i < inputs3.length; i++) {
            if (inputs3[i].disabled == false) {
                 if (inputs3[i].checked == false) {
                    inputs3[i].checked = true;

                }

            }
        }
    }

    else if(me.id=="ckbCheckSetBCD" && me.checked==false)
    {

       // $('.chk_1').removeAttr('disabled');

        var inputs1 = document.getElementsByClassName("chk_2");
        var inputs2 = document.getElementsByClassName("chk_3");
        var inputs3 = document.getElementsByClassName("chk_4");

        for (var i = 0; i < inputs1.length; i++) {
            if (inputs1[i].disabled == false) {
                if (inputs1[i].checked == true) {
                    inputs1[i].checked = false;
                }

            }
        }

        for (var i = 0; i < inputs2.length; i++) {
            if (inputs2[i].disabled == false) {
                if (inputs2[i].checked == true) {
                    inputs2[i].checked = false;
                }

            }
        }

        for (var i = 0; i < inputs3.length; i++) {
            if (inputs3[i].disabled == false) {
                if (inputs3[i].checked == true) {
                    inputs3[i].checked = false;
                }

            }
        }
    }

}

