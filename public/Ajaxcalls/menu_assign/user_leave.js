
function dChange(val,section,desiguser,leavet,facode,aacode){
    var setter_val = 'L';
    $.ajax({
        type: 'GET',
        url: base_url + "/MasterManagement/UserManagement/user_mgmt_multiple?key=1",
        data:{deptname:val,setter:setter_val}
    })
    .done(function(msg){
        //var msgg = msg.indexOf("RESET");
        $("#sec").html(msg);
        /*if(val == 0)*/{
            $("#leave").html("<option value='0'>SELECT</option>");
            $("#for_auth").html("<option value='0'>SELECT</option>");
            $("#app_auth").html("<option value='0'>SELECT</option>");
            $("#desguser").html("<option value='0'>ALL</option>");
        }
        
        if(section != ''){
            $("#sec").val(section);
            sChange(section,desiguser,leavet,facode,aacode);
        }
        /*if(msgg>=0){
            if($("#cur_user_type").val()=='1')
                $("#designation").html("<option value='ALL'>ALL</option>");
            else
                $("#designation").html("<option value='0'>SELECT</option>");
        }
        else
            $("#designation").html("<option value='ALL'>ALL</option>");*/
    })
    .fail(function(){
        alert("ERROR, Please Contact Server Room");
    });
}

function sChange(val,desiguser,leavet,facode,aacode){
    $.ajax({
        type: 'GET',
        url: base_url + "/MasterManagement/UserManagement/user_mgmt_multiple?key=13",
        data:{dept:$("#dept").val(),section:val}
    })
    .done(function(msg){
        //alert(msg);
        var msgg = msg.split('<<<<>>>>');
        $("#leave").html(msgg[0]);
        $("#desguser").html(msgg[1]);
        /*if(val == 0)*/{
            $("#for_auth").html("<option value='0'>SELECT</option>");
            $("#app_auth").html("<option value='0'>SELECT</option>");
        }
        
        if(desiguser != ''){
            $("#desguser").val(desiguser);
            $("#leave").val(leavet);
            lChange(leavet,facode,aacode);
        }
        
        /*if(msgg>=0){
            if($("#cur_user_type").val()=='1')
                $("#designation").html("<option value='ALL'>ALL</option>");
            else
                $("#designation").html("<option value='0'>SELECT</option>");
        }
        else
            $("#designation").html("<option value='ALL'>ALL</option>");*/
    })
    .fail(function(){
        alert("ERROR, Please Contact Server Room");
    });
}

function lChange(val,facode,aacode){
    //alert(val+'<>'+$("#dept").val());
    $.ajax({
        type: 'GET',
        url: base_url + "/MasterManagement/UserManagement/user_mgmt_multiple?key=14",
        data:{ltype:val,dept:$("#dept").val()}
    })
    .done(function(msg){
        //alert(msg);
        //var msgg = msg.indexOf("RESET");
        $("#for_auth").html(msg);
        /*if(val == 0)*/{
            $("#app_auth").html("<option value='0'>SELECT</option>");
        }
        
        if(facode != ''){
            $("#for_auth").val(facode);
            faChange(facode,aacode);
        }
        /*if(msgg>=0){
            if($("#cur_user_type").val()=='1')
                $("#designation").html("<option value='ALL'>ALL</option>");
            else
                $("#designation").html("<option value='0'>SELECT</option>");
        }
        else
            $("#designation").html("<option value='ALL'>ALL</option>");*/
    })
    .fail(function(){
        alert("ERROR, Please Contact Server Room");
    });
}

function faChange(val,aacode){
    //alert(val+'<>'+$("#dept").val());
    $.ajax({
        type: 'GET',
        url: base_url + "/MasterManagement/UserManagement/user_mgmt_multiple?key=15",
        data:{f_auth:val}
    })
    .done(function(msg){
        //alert(msg);
        //var msgg = msg.indexOf("RESET");
        $("#app_auth").html(msg);
        if(aacode != '')
            $("#app_auth").val(aacode);
        /*if(msgg>=0){
            if($("#cur_user_type").val()=='1')
                $("#designation").html("<option value='ALL'>ALL</option>");
            else
                $("#designation").html("<option value='0'>SELECT</option>");
        }
        else
            $("#designation").html("<option value='ALL'>ALL</option>");*/
    })
    .fail(function(){
        alert("ERROR, Please Contact Server Room");
    });
}

function add_userLeave(){
    if($("#leave_mapping").val() == ""){
        return false;
    }
    
    $.ajax({
        type: 'POST',
        url:$("#hd_folder").val()+"/userleave_manage.php",
        async: false,
        data:{mat:1,func:1,total:$("#leave_mapping").val(),user:$("#hd_ud").val()}
    })
    .done(function(msg){
        var msg2 = msg.split('~');
        if(msg2[0] == 1){
            $(".add_result").css("display","block");
            $(".add_result").css("color","green");
            $(".add_result").html(msg2[1]);
            $("#dept").val("0");
            $("#sec").html("<option value='0'>SELECT</option>");
            $("#desguser").html("<option value='0'>ALL</option>");
            $("#leave").html("<option value='0'>SELECT</option>");
            $("#for_auth").html("<option value='0'>SELECT</option>");
            $("#app_auth").html("<option value='0'>SELECT</option>");
            $("#leave_mapping").val("");
            $("#l_type_area").html("");
            $(".add_result").slideUp(3000);

            $.ajax({
                type: 'POST',
                url:$("#hd_folder").val()+"/userleave_manage.php",
                data:{mat:2}
            })
            .done(function(msg_new){
                //var msg3 = msg_new.split("<>><<>><><>");
                $("#result_main").html(msg_new);
                //$("#id_udept").val(msg3[1]);
            })
            .fail(function(){
                alert("ERROR, Please Contact Server Room"); 
            });
        }
        else{
            $(".add_result").css("display","block");
            $(".add_result").css("color","red");
            if(msg[0] == 2)
                $(".add_result").html("ALL DESIGNATION ALREADY ADDED FOR CURRENT USERTYPE FOR SAME AUTHORITIES");
            else if(msg[0] == 3)
                $(".add_result").html("PLEASE REMOVE SINGLE ADDED DESIGNATION FIRST FOR CURRENT USERTYPE FOR SAME AUTHORITIES");
            else
                $(".add_result").html(msg);
        }
    })
    .fail(function(){
        alert("ERROR, Please Contact Server Room");
    });   
}

function del_user_leave_click_fun(val){
    if(confirm("ARE YOU SURE TO REMOVE THIS AUTHORITY MAPPING") == true){
        $("#hd_id_for_userleave").val("");
        $("#dept").val("");
        $("#sec").html("<option value='0'>SELECT</option>");
        $("#desguser").html("<option value='0'>ALL</option>");
        $("#leave").html("<option value='0'>SELECT</option>");
        $("#for_auth").html("<option value='0'>SELECT</option>");
        $("#app_auth").html("<option value='0'>SELECT</option>");
        $("#leave_mapping").val("");
        $("#l_type_area").html("");
        $("#btnMainLeave").val("Save Data");
        $("#btnMainLeave").attr("onclick","add_userLeave()");
        $("#btnCanLeave").css("display","none");
        $("#update_section").css("display","none");
        $("#update_id").html("");
        
        $.ajax({
            type: 'POST',
            url:$("#hd_folder").val()+"/userleave_manage.php",
            data:{mat:1,func:2,id:val,user:$("#hd_ud").val()}
        })
        .done(function(msg){
            var msg2 = msg.split('~');
            if(msg2[0] == 1){
                $(".add_result").css("display","block");
                $(".add_result").css("color","#90C695");
                $(".add_result").html(msg2[1]);
                $(".add_result").slideUp(3000);
                $.ajax({
                    type: 'POST',
                    url:$("#hd_folder").val()+"/userleave_manage.php",
                    data:{mat:2}
                })
                .done(function(msg_new){
                    //var msg3 = msg_new.split("<>><<>><><>");
                    $("#result_main").html(msg_new);
                    //$("#id_udept").val(msg3[1]);
                })
                .fail(function(){
                    alert("ERROR, Please Contact Server Room"); 
                });
            }
            else{
                $(".add_result").css("display","block");
                $(".add_result").css("color","red");
                $(".add_result").html(msg);
            }
        })
        .fail(function(){
            alert("ERROR, Please Contact Server Room");
        });
    }
}

function edit_user_leave_click_fun(val){
    $.ajax({
        type: 'POST',
        url:$("#hd_folder").val()+"/userleave_manage.php",
        data:{mat:3,id:val}
    })
    .done(function(msg){
        //alert(msg);
        $("#leave_mapping").val("");
        $("#l_type_area").html("");
        var msg2 = msg.split('@');
        $("#btnMainLeave").val("Update");
        $("#btnMainLeave").attr("onclick","edit_userleave()");
        $("#btnCanLeave").css("display","inline");
        $("#update_section").css("display","inline");
        $("#update_id").html(val);
        $("#hd_id_for_userleave").val(val);
        
        var userdesg = '';
        var val_id = msg2[0]+'-'+msg2[1]+'-'+msg2[2]+'-'+msg2[3]+'-'+msg2[4]+'-'+msg2[5];
        
        if((msg2[8] == null) || (msg2[8] == ''))
            userdesg = 'ALL';
        else
            userdesg = msg2[8]+'-'+msg2[9];
            
        var maked_cont = "<div class='leave_map' id='l_l_"+val_id+"'>"+msg2[6]+' | '+msg2[7]+' | '+userdesg+' | '+msg2[10]+"=[FA]"+msg2[11]+"=[AA]"+msg2[12]+"\
        <img style='width:7px;height:7px;margin-top:0px;margin-bottom:4px;cursor:pointer' src='usermgmt/close-button.gif' onclick=removeCase_leave('"+val_id+"')></div>";

        $("#leave_mapping").val(','+val_id);
        $("#l_type_area").append(maked_cont);
        
        $("#dept").val(msg2[0]);
        
        dChange(msg2[0],msg2[1],msg2[2],msg2[3],msg2[4],msg2[5]);
        
    })
    .fail(function(){
        alert("ERROR, Please Contact Server Room");
    });
}

function edit_userleave(){
    if($("#leave_mapping").val() == ""){
        return false;
    }
    
    $.ajax({
        type: 'POST',
        url:$("#hd_folder").val()+"/userleave_manage.php",
        async: false,
        data:{mat:1,func:3,id:$("#hd_id_for_userleave").val(),total:$("#leave_mapping").val(),user:$("#hd_ud").val()}
    })
    .done(function(msg){
        //alert(msg);
        var msg2 = msg.split('~');
        if(msg2[0] == 1){
            $(".add_result").css("display","block");
            $(".add_result").css("color","green");
            $(".add_result").html(msg2[1]);
            $("#dept").val("0");
            $("#sec").html("<option value='0'>SELECT</option>");
            $("#desguser").html("<option value='0'>ALL</option>");
            $("#leave").html("<option value='0'>SELECT</option>");
            $("#for_auth").html("<option value='0'>SELECT</option>");
            $("#app_auth").html("<option value='0'>SELECT</option>");
            $("#leave_mapping").val("");
            $("#l_type_area").html("");
            $(".add_result").slideUp(3000);
            $("#btnMainLeave").val("Save Data");
            $("#btnMainLeave").attr("onclick","add_userLeave()");
            $("#btnCanLeave").css("display","none");
            $("#update_section").css("display","none");
            $("#update_id").html("");
            $.ajax({
                type: 'POST',
                url:$("#hd_folder").val()+"/userleave_manage.php",
                data:{mat:2}
            })
            .done(function(msg_new){
                /*var msg3 = msg_new.split("<>><<>><><>");
                $("#result_main").html(msg3[0]);
                $("#id_udept").val(msg3[1]);*/
                $("#result_main").html(msg_new);
            })
            .fail(function(){
                alert("ERROR, Please Contact Server Room"); 
            });
        }
        else{
            $(".add_result").css("display","block");
            $(".add_result").css("color","red");
            if(msg[0] == 2)
                $(".add_result").html("ALL DESIGNATION ALREADY ADDED FOR CURRENT USERTYPE FOR SAME AUTHORITIES");
            else if(msg[0] == 3)
                $(".add_result").html("PLEASE REMOVE SINGLE ADDED DESIGNATION FIRST FOR CURRENT USERTYPE FOR SAME AUTHORITIES");
            else
                $(".add_result").html(msg);
        }
    })
    .fail(function(){
        alert("ERROR, Please Contact Server Room");
    }); 
}

function cancel_op_leave(){
    $("#hd_id_for_userleave").val("");
    $("#dept").val("");
    $("#sec").html("<option value='0'>SELECT</option>");
    $("#desguser").html("<option value='0'>ALL</option>");
    $("#leave").html("<option value='0'>SELECT</option>");
    $("#for_auth").html("<option value='0'>SELECT</option>");
    $("#app_auth").html("<option value='0'>SELECT</option>");
    $("#leave_mapping").val("");
    $("#l_type_area").html("");
    $("#btnMainLeave").val("Save Data");
    $("#btnMainLeave").attr("onclick","add_userLeave()");
    $("#btnCanLeave").css("display","none");
    $("#update_section").css("display","none");
    $("#update_id").html("");
    $(".add_result").slideUp();
    $.ajax({
        type: 'POST',
        url:$("#hd_folder").val()+"/userleave_manage.php",
        data:{mat:2}
    })
    .done(function(msg_new){
        $("#result_main").html(msg_new);
        /*var msg3 = msg_new.split("<>><<>><><>");
        $("#result_main").html(msg3[0]);
        $("#id_udept").val(msg3[1]);*/
    })
    .fail(function(){
        alert("ERROR, Please Contact Server Room"); 
    });
}

function press_add_leave(){
    
    var st1 = $("#dept option:selected").text();
    var st2 = $("#sec option:selected").text();
    var st3 = $("#leave option:selected").text();
    var st4 = $("#desguser option:selected").text();
    st3 = st3.split('-');
    var setter = 0;
    var val_id = $("#dept").val()+'-'+$("#sec").val()+'-'+$("#desguser").val()+'-'+$("#leave").val()+'-'+$("#for_auth").val()+'-'+$("#app_auth").val();
    if($("#dept").val() == '0' || $("#sec").val() == '0' || $("#leave").val() == '0' || $("#for_auth").val() == '0' || $("#app_auth").val() == '0')
        val_id = '';
    
    var maked_cont = "<div class='leave_map' id='l_l_"+val_id+"'>"+st1+' | '+st2+' | '+st4+' | '+st3[1]+"=[FA]"+$("#for_auth option:selected").text()+"=[AA]"+$("#app_auth option:selected").text()+"\
<img style='width:7px;height:7px;margin-top:0px;margin-bottom:4px;cursor:pointer' src='usermgmt/close-button.gif' onclick=removeCase_leave('"+val_id+"')></div>";
    
    
    var total = new Array();
    total = ($("#leave_mapping").val()).split(',');
    //total = total.split(',');
    for(var i=0;i<total.length;i++){
        if(total[i] == '')
            continue;
        
        var innerString = total[i].split('-');
        if((innerString[0] == $("#dept").val()) && (innerString[1] == $("#sec").val()) && (innerString[2] == $("#desguser").val()) && (innerString[3] == $("#leave").val()))
            setter = 1;
        
        if(setter != 1){
            if((innerString[0] == $("#dept").val()) && (innerString[1] == $("#sec").val()) && (innerString[2] == 0) && (innerString[3] == $("#leave").val()) && (innerString[4] == $("#for_auth").val()) && (innerString[5] == $("#app_auth").val())){
                alert('ALL DESIGNATION ALREADY ADDED FOR CURRENT USERTYPE FOR SAME AUTHORITIES');
                setter = 1;
            }
            /*if((innerString[0] == $("#dept").val()) && (innerString[1] == $("#sec").val()) && (innerString[2] != 0) && (innerString[3] == $("#leave").val()) && (innerString[4] == $("#for_auth").val()) && (innerString[5] == $("#app_auth").val())){
                alert('PLEASE REMOVE SINGLE ADDED DESIGNATION FIRST FOR CURRENT USERTYPE FOR SAME AUTHORITIES');
                setter = 1;
            }*/
        }
    }
    
    //var index_val = total.indexOf(val_id);
    //alert(val_id+'**'+index_val);
    //if(index_val < 0){
    //alert(val_id);
    if(setter == 0 && val_id != ''){
        $("#leave_mapping").val($("#leave_mapping").val()+','+val_id);
        $("#l_type_area").append(maked_cont);
    }
}

function removeCase_leave(val){
    var total = ($("#leave_mapping").val()).split(',');
    var index_val = total.indexOf(val);
    total.splice(index_val,1);
    $("#leave_mapping").val(total);
    $("#l_l_"+val).remove();
    /*if($("#leave_mapping").val() == ''){
        $("#btnMainLeave").val("Save Data");
        $("#btnMainLeave").attr("onclick","add_userLeave()");
        $("#btnCanLeave").css("display","none");
        $("#update_section").css("display","none");
        $("#update_id").html("");
    }*/
}

