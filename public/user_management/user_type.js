
$(document).ready(function(){
    
    // $("#btnMain").click(function(){
    //     if($("#name_utype").val().trim() == ''){
    //         alert("Please Enter UserType Name");
    //         $("#name_utype").focus();
    //         return false;
    //     }
    //     var dflag = $("#dflag_utype").val().trim();
    //     var mflag = $("#mflag_utype").val().trim();
    //     var regABC = new RegExp('^[A-z]+$');
        
    //     if(!regABC.test(dflag)){
    //         alert("Please Enter Disaptch Flag in Alphabets Only");
    //         $("#dflag_utype").focus();
    //         return false;
    //     }
    //     if(!regABC.test(mflag)){
    //         alert("Please Enter Management Flag in Alphabets Only");
    //         $("#mflag_utype").focus();
    //         return false;
    //     }
    //     var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        
    //     $.ajax({
    //         type: 'POST',
    //         url: base_url + "/MasterManagement/UserManagement/usertype_manage",
    //         async: false,
    //         data:{mat:1,func:1,name:$("#name_utype").val(),flag:dflag,mflag:mflag,CSRF_TOKEN:CSRF_TOKEN_VALUE}
    //     })
    //     .done(function(msg){
    //         updateCSRFToken();
    //         var msg2 = msg.split('~');
    //         if(msg2[0] == 1){
    //             swal("Success!", msg2[1], "success");
    //             //$(".add_result").css("display","block");
    //             //$(".add_result").css("color","green");
    //             //$(".add_result").html(msg2[1]);
    //             $("#name_utype").val("");
    //             $("#dflag_utype").val("");
    //             $("#mflag_utype").val("");
    //             //$(".add_result").slideUp(3000);
                
                
    //             setTimeout(function(){
    //                 var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
    //                 $.ajax({
    //                     type: 'POST',
    //                     //url:"./usertype_manage",
    //                     url: base_url + "/MasterManagement/UserManagement/usertype_manage",
    //                     data:{mat:2,CSRF_TOKEN:CSRF_TOKEN_VALUE}
    //                 })
    //                 .done(function(msg_new){
    //                     updateCSRFToken();
    //                     if(msg_new.success == 1){
    //                         $("#result_main").html(msg_new.html);
    //                         $("#id_utype").val(msg_new.get_Open_id);
    //                     } else{
    //                         //$("#result_main").html('');
    //                         alert("ERROR, Please Contact Server Room"); 
    //                     }
    //                     //var msg3 = msg_new.split("<>><<>><><>");
    //                     //$("#result_main").html(msg3[0]);
    //                     //$("#id_utype").val(msg3[1]);


    //                 })
    //                 .fail(function(){
    //                     updateCSRFToken();
    //                     alert("ERROR, Please Contact Server Room"); 
    //                 });
    //             }, 1500)     
    //         }
    //         else{
    //             swal("Error!", msg, "error");

    //             //$(".add_result").css("display","block");
    //             //$(".add_result").css("color","red");
    //             //$(".add_result").html(msg);
    //         }
    //     })
    //     .fail(function(){
    //         updateCSRFToken();
    //         alert("ERROR, Please Contact Server Room");
    //     });   
    // });
    
    $("#btnMain").click(async function () {
        if ($("#name_utype").val().trim() === '') {
            alert("Please Enter UserType Name");
            $("#name_utype").focus();
            return false;
        }
    
        const dflag = $("#dflag_utype").val().trim();
        const mflag = $("#mflag_utype").val().trim();
        const regABC = new RegExp('^[A-Za-z]+$');
    
        if (!regABC.test(dflag)) {
            alert("Please Enter Dispatch Flag in Alphabets Only");
            $("#dflag_utype").focus();
            return false;
        }
    
        if (!regABC.test(mflag)) {
            alert("Please Enter Management Flag in Alphabets Only");
            $("#mflag_utype").focus();
            return false;
        }
    
        try {
            await updateCSRFTokenSync();
            let CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
    
            const saveResponse = await $.ajax({
                type: 'POST',
                url: base_url + "/MasterManagement/UserManagement/usertype_manage",
                data: {
                    mat: 1,
                    func: 1,
                    name: $("#name_utype").val(),
                    flag: dflag,
                    mflag: mflag,
                    CSRF_TOKEN: CSRF_TOKEN_VALUE
                }
            });
    
            const msg2 = saveResponse.split('~');
            if (msg2[0] == 1) {
                //swal("Success!", msg2[1], "success");
                $("#name_utype").val("");
                $("#dflag_utype").val("");
                $("#mflag_utype").val("");
    
                await new Promise(resolve => setTimeout(resolve, 1500)); // Wait 1.5 seconds
    
                await updateCSRFTokenSync();
                CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
    
                const listResponse = await $.ajax({
                    type: 'POST',
                    url: base_url + "/MasterManagement/UserManagement/usertype_manage",
                    data: {
                        mat: 2,
                        CSRF_TOKEN: CSRF_TOKEN_VALUE
                    },
                    dataType: "json"
                });
    
                if (listResponse.success == 1) {
                    if ($.fn.DataTable.isDataTable('#result_main')) {
                        $('#result_main').DataTable().destroy();
                    }
                    $("#result_main").html(listResponse.html);
                    $("#id_utype").val(listResponse.get_Open_id);
                    $('#result_main').DataTable();
                    alert("User Type Added Successfully");
                } else {
                    alert("ERROR, Please Contact Server Room");
                }
            } else {
                swal("Error!", saveResponse, "error");
            }
    
        } catch (err) {
            console.error(err);
            alert("ERROR, Please Contact Server Room");
        }
    });

    $("#btnCan").click(async function(){
        $("#hd_id_for_usertype").val("");
        $("#name_utype").val("");
        $("#dflag_utype").val("");
        $("#mflag_utype").val("");
        //$("#btnUp").val("Add New");
        //$("#btnUp").prop("id","btnMain");
        $("#btnUp").css("display","none");
        $("#btnMain").css("display","inline");
        $("#btnCan").css("display","none");
        //$(".add_result").slideUp();
        await updateCSRFTokenSync();
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        $.ajax({
            type: 'POST',
            //url:"./usertype_manage",
            url: base_url + "/MasterManagement/UserManagement/usertype_manage",
            data:{mat:2,CSRF_TOKEN:CSRF_TOKEN_VALUE}
        })
        .done(function(msg_new){
            //updateCSRFToken();
            if(msg_new.success == 1){
                if ($.fn.DataTable.isDataTable('#result_main')) {
                    $('#result_main').DataTable().destroy();
                }
                $("#result_main").html(msg_new.html);
                $("#id_utype").val(msg_new.get_Open_id);
                $('#result_main').DataTable();
            } else{
                //$("#result_main").html('');
                alert("ERROR, Please Contact Server Room"); 
            }
            //var msg3 = msg_new.split("<>><<>><><>");
            //$("#result_main").html(msg3[0]);
            //$("#id_utype").val(msg3[1]);
        })
        .fail(function(){
            //updateCSRFToken();
            alert("ERROR, Please Contact Server Room"); 
        });
    });
    
    // $("#btnUp").click(function(){
    //     if($("#name_utype").val().trim() == ''){
    //         alert("Please Enter UserType Name");
    //         $("#name_utype").focus();
    //         return false;
    //     }
    //     var dflag = $("#dflag_utype").val().trim();
    //     var mflag = $("#mflag_utype").val().trim();
    //     var regABC = new RegExp('^[A-z]+$');
        
    //     if(!regABC.test(dflag)){
    //         alert("Please Enter Disaptch Flag in Alphabets Only");
    //         $("#dflag_utype").focus();
    //         return false;
    //     }
    //     if(!regABC.test(mflag)){
    //         alert("Please Enter Management Flag in Alphabets Only");
    //         $("#mflag_utype").focus();
    //         return false;
    //     }
    //     var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        
    //     $.ajax({
    //         type: 'POST',
    //         //url:"./usertype_manage",
    //         url: base_url + "/MasterManagement/UserManagement/usertype_manage",
    //         async: false,
    //         data:{mat:1,func:3,name:$("#name_utype").val(),flag:dflag,mflag:mflag,id:$("#hd_id_for_usertype").val(),CSRF_TOKEN:CSRF_TOKEN_VALUE}
    //     })
    //     .done(function(msg){
    //         updateCSRFToken();
    //         var msg2 = msg.split('~');
    //         if(msg2[0] == 1){
    //             swal("Success!", msg2[1], "success");
    //             //$(".add_result").css("display","block");
    //             //$(".add_result").css("color","green");
    //             //$(".add_result").html(msg2[1]);
    //             $("#name_utype").val("");
    //             $("#dflag_utype").val("");
    //             $("#mflag_utype").val("");
    //            // $(".add_result").slideUp(3000);
                
    //             $("#btnCan").css("display","none");
    //             $("#btnUp").css("display","none");
    //             $("#btnMain").css("display","inline");
                
    //             setTimeout(function(){
    //                 var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
    //                 $.ajax({
    //                     type: 'POST',
    //                     //url:"./usertype_manage",
    //                     url: base_url + "/MasterManagement/UserManagement/usertype_manage",
    //                     data:{mat:2,CSRF_TOKEN:CSRF_TOKEN_VALUE}
    //                 })
    //                 .done(function(msg_new){
    //                     updateCSRFToken();
    //                     if(msg_new.success == 1){
    //                         $("#result_main").html(msg_new.html);
    //                         $("#id_utype").val(msg_new.get_Open_id);
    //                     } else{
    //                         //$("#result_main").html('');
    //                         alert("ERROR, Please Contact Server Room"); 
    //                     }
    //                     //var msg3 = msg_new.split("<>><<>><><>");
    //                     //$("#result_main").html(msg3[0]);
    //                     //$("#id_utype").val(msg3[1]);
    //                 })
    //                 .fail(function(){
    //                     updateCSRFToken();
    //                     alert("ERROR, Please Contact Server Room"); 
    //                 });
    //             }, 1500)     
    //         }
    //         else{
    //             swal("Error!", msg, "error");

    //             //$(".add_result").css("display","block");
    //             //$(".add_result").css("color","red");
    //             //$(".add_result").html(msg);
    //         }
    //     })
    //     .fail(function(){
    //         updateCSRFToken();
    //         alert("ERROR, Please Contact Server Room");
    //     }); 
    // });
    
    $("#btnUp").click(async function () {
        if ($("#name_utype").val().trim() === '') {
            alert("Please Enter UserType Name");
            $("#name_utype").focus();
            return false;
        }
    
        const dflag = $("#dflag_utype").val().trim();
        const mflag = $("#mflag_utype").val().trim();
        const regABC = new RegExp('^[A-z]+$');
    
        if (!regABC.test(dflag)) {
            alert("Please Enter Dispatch Flag in Alphabets Only");
            $("#dflag_utype").focus();
            return false;
        }
    
        if (!regABC.test(mflag)) {
            alert("Please Enter Management Flag in Alphabets Only");
            $("#mflag_utype").focus();
            return false;
        }
    
        try {
            await updateCSRFTokenSync();
            let CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
    
            const saveResponse = await $.ajax({
                type: 'POST',
                url: base_url + "/MasterManagement/UserManagement/usertype_manage",
                data: {
                    mat: 1,
                    func: 3,
                    name: $("#name_utype").val(),
                    flag: dflag,
                    mflag: mflag,
                    id: $("#hd_id_for_usertype").val(),
                    CSRF_TOKEN: CSRF_TOKEN_VALUE
                }
            });
    
            const msg2 = saveResponse.split('~');
            if (msg2[0] == 1) {
                //swal("Success!", msg2[1], "success");
    
                $("#name_utype").val("");
                $("#dflag_utype").val("");
                $("#mflag_utype").val("");
    
                $("#btnCan").hide();
                $("#btnUp").hide();
                $("#btnMain").show();
    
                await new Promise(resolve => setTimeout(resolve, 1500));
    
                await updateCSRFTokenSync();
                CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
    
                const listResponse = await $.ajax({
                    type: 'POST',
                    url: base_url + "/MasterManagement/UserManagement/usertype_manage",
                    data: { mat: 2, CSRF_TOKEN: CSRF_TOKEN_VALUE },
                    dataType: 'json'
                });
    
                if (listResponse.success == 1) {
                    if ($.fn.DataTable.isDataTable('#result_main')) {
                        $('#result_main').DataTable().destroy();
                    }
                    $("#result_main").html(listResponse.html);
                    $("#id_utype").val(listResponse.get_Open_id);
                    $('#result_main').DataTable();
                    alert("User Type Updated Successfully");
                } else {
                    alert("ERROR, Please Contact Server Room");
                }
            } else {
                swal("Error!", saveResponse, "error");
            }
    
        } catch (err) {
            console.error(err);
            alert("ERROR, Please Contact Server Room");
        }
    });

});

$(document).on("click","[id^='btnEdit']",async function(){
    var num = this.id.split('btnEdit');
    await updateCSRFTokenSync();
    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

    $.ajax({
        type: 'POST',
        //url:"./usertype_manage",
        url: base_url + "/MasterManagement/UserManagement/usertype_manage",
        data:{mat:3,id:num[1],CSRF_TOKEN:CSRF_TOKEN_VALUE}
    })
    .done(function(msg){
        //updateCSRFToken();
        var msg2 = msg.split('~');
        $("#id_utype").val(msg2[0]);
        $("#hd_id_for_usertype").val(msg2[0]);
        $("#name_utype").val(msg2[1]);
        $("#dflag_utype").val(msg2[2]);
        $("#mflag_utype").val(msg2[3]);
        
        $("#btnUp").css("display","inline");
        $("#btnCan").css("display","inline");
        $("#btnMain").css("display","none");
        $('#name_utype').focus();
    })
    .fail(function(){
        //updateCSRFToken();
        alert("ERROR, Please Contact Server Room");
    });
});

// $(document).on("click","[id^='btnDelete']",function(){
//     var num = this.id.split('btnDelete');
//     if(confirm("ARE YOU SURE TO REMOVE THIS USERTYPE") == true){
//         var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

//         $.ajax({
//             type: 'POST',
//             url: base_url + "/MasterManagement/UserManagement/usertype_manage",
//             data:{mat:1,func:2,id:num[1],CSRF_TOKEN:CSRF_TOKEN_VALUE}
//         })
//         .done(function(msg){
//             updateCSRFToken();
//             var msg2 = msg.split('~');
//             if(msg2[0] == 1){
//                 swal("Success!", msg2[1], "success");
//                 $("#name_utype").val("");
//                 $("#dflag_utype").val("");
//                 $("#mflag_utype").val("");
                
//                 setTimeout(function(){
//                     var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
//                     $.ajax({
//                         type: 'POST',
//                         url: base_url + "/MasterManagement/UserManagement/usertype_manage",
//                         data:{mat:2,CSRF_TOKEN:CSRF_TOKEN_VALUE}
//                     })
//                     .done(function(msg_new){
//                         updateCSRFToken();
//                         if(msg_new.success == 1){
//                             $("#result_main").html(msg_new.html);
//                             $("#id_utype").val(msg_new.get_Open_id);
//                         } else{
//                             //$("#result_main").html('');
//                             alert("ERROR, Please Contact Server Room"); 
//                         }
//                         //var msg3 = msg_new.split("<>><<>><><>");
//                         //$("#result_main").html(msg3[0]);
//                         //$("#id_utype").val(msg3[1]);
//                     })
//                     .fail(function(){
//                         updateCSRFToken();
//                         alert("ERROR, Please Contact Server Room"); 
//                     });
//                 }, 1500);    
//             }
//             else{
//                 swal("Error!", msg, "error");
//             }
//         })
//         .fail(function(){
//             updateCSRFToken();
//             alert("ERROR, Please Contact Server Room");
//         });
//     }
// });
$(document).on("click", "[id^='btnDelete']", async function () {
    const num = this.id.split('btnDelete')[1];

    if (confirm("ARE YOU SURE TO REMOVE THIS USERTYPE")) {
        try {
            await updateCSRFTokenSync();
            let CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

            const deleteResponse = await $.ajax({
                type: 'POST',
                url: base_url + "/MasterManagement/UserManagement/usertype_manage",
                data: { mat: 1, func: 2, id: num, CSRF_TOKEN: CSRF_TOKEN_VALUE }
            });

            const msg2 = deleteResponse.split('~');
            if (msg2[0] == 1) {
                //swal("Success!", msg2[1], "success");

                $("#name_utype").val("");
                $("#dflag_utype").val("");
                $("#mflag_utype").val("");

                await new Promise(resolve => setTimeout(resolve, 1500));

                await updateCSRFTokenSync();
                CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

                const listResponse = await $.ajax({
                    type: 'POST',
                    url: base_url + "/MasterManagement/UserManagement/usertype_manage",
                    data: { mat: 2, CSRF_TOKEN: CSRF_TOKEN_VALUE },
                    dataType: "json"
                });

                if (listResponse.success == 1) {
                    if ($.fn.DataTable.isDataTable('#result_main')) {
                        $('#result_main').DataTable().destroy();
                    }
                    $("#result_main").html(listResponse.html);
                    $("#id_utype").val(listResponse.get_Open_id);
                    $('#result_main').DataTable();
                    alert("User Type Deleted Successfully");
                } else {
                    alert("ERROR, Please Contact Server Room");
                }
            } else {
                swal("Error!", deleteResponse, "error");
            }
        } catch (err) {
            console.error(err);
            alert("ERROR, Please Contact Server Room");
        }
    }
});


