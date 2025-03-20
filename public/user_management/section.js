
$(document).ready(function(){
    
    $("#btnMain").click(function(){
        if($("#name_usec").val().trim() == ''){
            alert("Please Enter User Section Name");
            $("#name_usec").focus();
            return false;
        }
        if($("#descp").val().trim() == ''){
            alert("Please Enter Description");
            $("#descp").focus();
            return false;
        }
        var isda='N';
        if($("#if_da").is(':checked')==true){
            isda='Y';
        }
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            type: 'POST',
            url: base_url + "/MasterManagement/UserManagement/usersec_manage",
            async: false,
            data:{mat:1,func:1,name:$("#name_usec").val(),des:$("#descp").val(),isda:isda,CSRF_TOKEN:CSRF_TOKEN_VALUE}
        })
        .done(function(msg){
            updateCSRFToken();
            setTimeout(function(){
                
                var msg2 = msg.split('~');
                if(msg2[0] == 1){
                    swal("Success!", msg2[1], "success");
                    $("#name_usec").val("");
                    $("#descp").val("");
                    $("#if_da").removeProp('checked');
                    
                    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                    $.ajax({
                        type: 'POST',
                        url: base_url + "/MasterManagement/UserManagement/usersec_manage",
                        data:{mat:2,CSRF_TOKEN:CSRF_TOKEN_VALUE}
                    })
                    .done(function(msg_new){
                        //msg_new.success =2
                        updateCSRFToken();
                        if(msg_new.success == 1){
                            $("#result_main").html(msg_new.html);
                            $("#id_usec").val(msg_new.get_Open_id);
                        } else{
                            alert("ERROR, Please Contact Server Room"); 
                        }
                    })
                    .fail(function(){
                        alert("ERROR, Please Contact Server Room"); 
                    });
                }
                else{
                    swal("Error!", msg, "error");
                }
            }, 1500)    
        })
        .fail(function(){
            updateCSRFToken();
            alert("ERROR, Please Contact Server Room");
        });   
    });
    
    $("#btnCan").click(function(){
        $("#hd_id_for_usersec").val("");
        $("#name_usec").val("");
        $("#descp").val("");
        $("#if_da").removeProp('checked');
        
        
        $("#btnUp").css("display","none");
        $("#btnMain").css("display","inline");
        $("#btnCan").css("display","none");
        
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        $.ajax({
            type: 'POST',
            url: base_url + "/MasterManagement/UserManagement/usersec_manage",
            data:{mat:2,CSRF_TOKEN:CSRF_TOKEN_VALUE}
        })
        .done(function(msg_new){
            updateCSRFToken();
            if(msg_new.success == 1){
                $("#result_main").html(msg_new.html);
                $("#id_usec").val(msg_new.get_Open_id);
            } else{
                alert("ERROR, Please Contact Server Room"); 
            }
        })
        .fail(function(){
            updateCSRFToken();
            alert("ERROR, Please Contact Server Room"); 
        });
    });
    
    $("#btnUp").click(function(){
        if($("#name_usec").val().trim() == ''){
            alert("Please Enter User Section Name");
            $("#name_usec").focus();
            return false;
        }
        if($("#descp").val().trim() == ''){
            alert("Please Enter Description");
            $("#descp").focus();
            return false;
        }
        var isda='N';
        if($("#if_da").is(':checked')==true){
            isda='Y';
        }
        
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        $.ajax({
            type: 'POST',
            url: base_url + "/MasterManagement/UserManagement/usersec_manage",
            async: false,
            data:{mat:1,func:3,name:$("#name_usec").val(),des:$("#descp").val(),id:$("#hd_id_for_usersec").val(),isda:isda,CSRF_TOKEN:CSRF_TOKEN_VALUE
            }
        })
        .done(function(msg){
            updateCSRFToken();
            
            var msg2 = msg.split('~');
            if(msg2[0] == 1){
                swal("Success!", msg2[1], "success")
                
                $("#name_usec").val("");
                $("#descp").val("");
                $("#if_da").removeProp('checked');
                
                $("#btnCan").css("display","none");
                $("#btnUp").css("display","none");
                $("#btnMain").css("display","inline");

                setTimeout(function(){
                    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                    $.ajax({
                        type: 'POST',
                        url: base_url + "/MasterManagement/UserManagement/usersec_manage",
                        data:{mat:2,CSRF_TOKEN:CSRF_TOKEN_VALUE
                        }
                    })
                    .done(function(msg_new){
                        updateCSRFToken();
                        if(msg_new.success == 1){
                            $("#result_main").html(msg_new.html);
                            $("#id_usec").val(msg_new.get_Open_id);
                        } else{
                            alert("ERROR, Please Contact Server Room"); 
                        }
                    })
                    .fail(function(){
                        updateCSRFToken();
                        alert("ERROR, Please Contact Server Room 1"); 
                    });
                }, 1500)     
            }
            else{
                swal("Error!", msg, "error");
            }
        })
        .fail(function(){
            updateCSRFToken();
            alert("ERROR, Please Contact Server Room 2");
        }); 
    });
    
});

$(document).on("click","[id^='btnEdit']",function(){
    var num = this.id.split('btnEdit');
    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
    $.ajax({
        type: 'POST',
        url: base_url + "/MasterManagement/UserManagement/usersec_manage",
        data:{mat:3,id:num[1],CSRF_TOKEN:CSRF_TOKEN_VALUE}
    })
    .done(function(msg){
        updateCSRFToken();
        var msg2 = msg.split('~');
        $("#id_usec").val(msg2[0]);
        $("#hd_id_for_usersec").val(msg2[0]);
        $("#name_usec").val(msg2[1]);
        $("#descp").val(msg2[2]);
        
        if(msg2[3]=='Y'){
            $("#if_da").prop('checked',true);
        }
        else
            $("#if_da").prop('checked',false);
        
        $("#btnUp").css("display","inline");
        $("#btnCan").css("display","inline");
        $("#btnMain").css("display","none");
        $('#name_usec').focus();
    })
    .fail(function(){
        updateCSRFToken();
        alert("ERROR, Please Contact Server Room");
    });
});

$(document).on("click","[id^='btnDelete']",function(){
    var num = this.id.split('btnDelete');
    if(confirm("ARE YOU SURE TO REMOVE THIS USER SECTION") == true){
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        $.ajax({
            type: 'POST',
            url: base_url + "/MasterManagement/UserManagement/usersec_manage",
            data:{mat:1,func:2,id:num[1],CSRF_TOKEN:CSRF_TOKEN_VALUE}
        })
        .done(function(msg){
            updateCSRFToken();
            var msg2 = msg.split('~');
            if(msg2[0] == 1){
                swal("Success!", msg2[1], "success");

                $("#name_usec").val("");
                $("#descp").val("");
                
                setTimeout(function(){
                    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                    $.ajax({
                        type: 'POST',
                        url: base_url + "/MasterManagement/UserManagement/usersec_manage",
                        data:{mat:2,CSRF_TOKEN:CSRF_TOKEN_VALUE}
                    })
                    .done(function(msg_new){
                        updateCSRFToken();
                        if(msg_new.success == 1){
                            $("#result_main").html(msg_new.html);
                            $("#id_usec").val(msg_new.get_Open_id);
                        } else{
                            alert("ERROR, Please Contact Server Room"); 
                        }
                    })
                    .fail(function(){
                        updateCSRFToken();
                        alert("ERROR, Please Contact Server Room"); 
                    });
                }, 1500)    
            }
            else{
                swal("Error!", msg, "error");
            }
        })
        .fail(function(){
            updateCSRFToken();
            alert("ERROR, Please Contact Server Room");
        });
    }
});



