
$(document).ready(function(){
    $('#name_usec').on('input', function () {
        if (this.value.length > 20) {
          this.value = this.value.slice(0, 20); // Limit to 20 characters
        }
    });
    
    // $("#btnMain").click(async function(){
    //     if($("#name_usec").val().trim() == ''){
    //         alert("Please Enter User Section Name");
    //         $("#name_usec").focus();
    //         return false;
    //     }
    //     if($("#descp").val().trim() == ''){
    //         alert("Please Enter Description");
    //         $("#descp").focus();
    //         return false;
    //     }
    //     var isda='N';
    //     if($("#if_da").is(':checked')==true){
    //         isda='Y';
    //     }
    //     await updateCSRFTokenSync();
    //     var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
    //     $.ajax({
    //         type: 'POST',
    //         url: base_url + "/MasterManagement/UserManagement/usersec_manage",
    //         async: false,
    //         data:{mat:1,func:1,name:$("#name_usec").val(),des:$("#descp").val(),isda:isda,CSRF_TOKEN:CSRF_TOKEN_VALUE}
    //     })
    //     .done(function(msg){
    //         //updateCSRFToken();
            
                
    //             var msg2 = msg.split('~');                
    //             if(msg2[0] == 1){                    
    //                 //swal("Success!", msg2[1], "success");
    //                 //location.reload();
    //                 $("#name_usec").val("");
    //                 $("#descp").val("");
    //                 $("#if_da").removeProp('checked');
    //                 updateCSRFTokenSync();                    
    //                 var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
    //                 $.ajax({
    //                     type: 'POST',
    //                     url: base_url + "/MasterManagement/UserManagement/usersec_manage",
    //                     data:{mat:2,CSRF_TOKEN:CSRF_TOKEN_VALUE}
    //                 })
    //                 .done(function(msg_new){
    //                     //msg_new.success =2
    //                     //updateCSRFToken();
    //                     if(msg_new.success == 1){
    //                         if ($.fn.DataTable.isDataTable('#result_table')) {
    //                             $('#result_table').DataTable().destroy();
    //                         }
    //                         $("#result_main").html(msg_new.html);
    //                         $("#id_usec").val(msg_new.get_Open_id);
    //                         $('#result_table').DataTable();
    //                     } else{
    //                         alert("ERROR, Please Contact Server Room"); 
    //                     }
    //                 })
    //                 .fail(function(){
    //                     alert("ERROR, Please Contact Server Room"); 
    //                 });
    //             }
    //             else{
    //                 swal("Error!", msg, "error");
    //             }
                
    //     })
    //     .fail(function(){
    //         updateCSRFToken();
    //         alert("ERROR, Please Contact Server Room");
    //     });   
    // });

    $("#btnMain").click(async function () {
        if ($("#name_usec").val().trim() === '') {
            alert("Please Enter User Section Name");
            $("#name_usec").focus();
            return false;
        }
        if ($("#descp").val().trim() === '') {
            alert("Please Enter Description");
            $("#descp").focus();
            return false;
        }
    
        let isda = $("#if_da").is(':checked') ? 'Y' : 'N';
    
        try {
            await updateCSRFTokenSync();
            let CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
    
            const saveResponse = await $.ajax({
                type: 'POST',
                url: base_url + "/MasterManagement/UserManagement/usersec_manage",
                data: {
                    mat: 1,
                    func: 1,
                    name: $("#name_usec").val(),
                    des: $("#descp").val(),
                    isda: isda,
                    CSRF_TOKEN: CSRF_TOKEN_VALUE
                }
            });
    
            const msg2 = saveResponse.split('~');
    
            if (msg2[0] == 1) {
                // Success
                // alert("");
                // location.reload();
                $("#name_usec").val("");
                $("#descp").val("");
                $("#if_da").prop('checked', false);
    
                await updateCSRFTokenSync();
                CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
    
                const listResponse = await $.ajax({
                    type: 'POST',
                    url: base_url + "/MasterManagement/UserManagement/usersec_manage",
                    data: { mat: 2, CSRF_TOKEN: CSRF_TOKEN_VALUE },
                    dataType: "json"
                });    
                if (listResponse.success == 1) {
                    if ($.fn.DataTable.isDataTable('#result_main')) {
                        $('#result_main').DataTable().destroy();
                    }
                    $("#result_main").html(listResponse.html);
                    $("#id_usec").val(listResponse.get_Open_id);
                    $('#result_main').DataTable();
                    alert("User Section Added Successfully");
                } else {
                    alert("ERROR, Please Contact Server Room");
                }    
            }
            else if (msg2[0] == 2) {
                alert(msg2[1]);                
            }
             else {
                swal("Error!", saveResponse, "error");
            }    
        } catch (err) {
            console.error(err);
            alert("ERROR, Please Contact Server Room");
        }
    });
    
    $("#btnCan").click(async function(){
        $("#hd_id_for_usersec").val("");
        $("#name_usec").val("");
        $("#descp").val("");
        $("#if_da").removeProp('checked');
        
        
        $("#btnUp").css("display","none");
        $("#btnMain").css("display","inline");
        $("#btnCan").css("display","none");
        await updateCSRFTokenSync();
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        $.ajax({
            type: 'POST',
            url: base_url + "/MasterManagement/UserManagement/usersec_manage",
            data:{mat:2,CSRF_TOKEN:CSRF_TOKEN_VALUE}
        })
        .done(function(msg_new){
            //updateCSRFToken();
            if(msg_new.success == 1){
                
                if ($.fn.DataTable.isDataTable('#result_main')) {
                    $('#result_main').DataTable().destroy();
                }
                $("#result_main").html(msg_new.html);
                $("#id_usec").val(msg_new.get_Open_id);
                $('#result_main').DataTable();
                

            } else{
                alert("ERROR, Please Contact Server Room"); 
            }
        })
        .fail(function(){
            updateCSRFToken();
            alert("ERROR, Please Contact Server Room"); 
        });
    });
    
    // $("#btnUp").click(function(){
    //     if($("#name_usec").val().trim() == ''){
    //         alert("Please Enter User Section Name");
    //         $("#name_usec").focus();
    //         return false;
    //     }
    //     if($("#descp").val().trim() == ''){
    //         alert("Please Enter Description");
    //         $("#descp").focus();
    //         return false;
    //     }
    //     var isda='N';
    //     if($("#if_da").is(':checked')==true){
    //         isda='Y';
    //     }
        
    //     var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

    //     $.ajax({
    //         type: 'POST',
    //         url: base_url + "/MasterManagement/UserManagement/usersec_manage",
    //         async: false,
    //         data:{mat:1,func:3,name:$("#name_usec").val(),des:$("#descp").val(),id:$("#hd_id_for_usersec").val(),isda:isda,CSRF_TOKEN:CSRF_TOKEN_VALUE
    //         }
    //     })
    //     .done(function(msg){
    //         updateCSRFToken();
            
    //         var msg2 = msg.split('~');
    //         if(msg2[0] == 1){
    //             swal("Success!", msg2[1], "success")
                
    //             $("#name_usec").val("");
    //             $("#descp").val("");
    //             $("#if_da").removeProp('checked');
                
    //             $("#btnCan").css("display","none");
    //             $("#btnUp").css("display","none");
    //             $("#btnMain").css("display","inline");

    //             setTimeout(function(){
    //                 var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
    //                 $.ajax({
    //                     type: 'POST',
    //                     url: base_url + "/MasterManagement/UserManagement/usersec_manage",
    //                     data:{mat:2,CSRF_TOKEN:CSRF_TOKEN_VALUE
    //                     }
    //                 })
    //                 .done(function(msg_new){
    //                     updateCSRFToken();
    //                     if(msg_new.success == 1){
    //                         $("#result_main").html(msg_new.html);
    //                         $("#id_usec").val(msg_new.get_Open_id);
    //                     } else{
    //                         alert("ERROR, Please Contact Server Room"); 
    //                     }
    //                 })
    //                 .fail(function(){
    //                     updateCSRFToken();
    //                     alert("ERROR, Please Contact Server Room 1"); 
    //                 });
    //             }, 1500)     
    //         }
    //         else{
    //             swal("Error!", msg, "error");
    //         }
    //     })
    //     .fail(function(){
    //         updateCSRFToken();
    //         alert("ERROR, Please Contact Server Room 2");
    //     }); 
    // });

    $("#btnUp").click(async function () {
        if ($("#name_usec").val().trim() === '') {
            alert("Please Enter User Section Name");
            $("#name_usec").focus();
            return false;
        }
    
        if ($("#descp").val().trim() === '') {
            alert("Please Enter Description");
            $("#descp").focus();
            return false;
        }
    
        let isda = $("#if_da").is(':checked') ? 'Y' : 'N';
    
        try {
            await updateCSRFTokenSync();
            let CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
    
            const saveResponse = await $.ajax({
                type: 'POST',
                url: base_url + "/MasterManagement/UserManagement/usersec_manage",
                data: {
                    mat: 1,
                    func: 3,
                    name: $("#name_usec").val(),
                    des: $("#descp").val(),
                    id: $("#hd_id_for_usersec").val(),
                    isda: isda,
                    CSRF_TOKEN: CSRF_TOKEN_VALUE
                }
            });
    
            const msg2 = saveResponse.split('~');
            if (msg2[0] == 1) {
                //swal("Success!", msg2[1], "success");
                
                // Reset fields
                $("#name_usec").val("");
                $("#descp").val("");
                $("#if_da").prop('checked', false);
                $("#btnCan").hide();
                $("#btnUp").hide();
                $("#btnMain").show();
    
                await updateCSRFTokenSync();
                CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
    
                const listResponse = await $.ajax({
                    type: 'POST',
                    url: base_url + "/MasterManagement/UserManagement/usersec_manage",
                    data: { mat: 2, CSRF_TOKEN: CSRF_TOKEN_VALUE },
                    dataType: "json"
                });
    
                if (listResponse.success == 1) {
                    if ($.fn.DataTable.isDataTable('#result_main')) {
                        $('#result_main').DataTable().destroy();
                    }
                    $("#result_main").html(listResponse.html);
                    $("#id_usec").val(listResponse.get_Open_id);
                    $('#result_main').DataTable();
                    alert("User Section Updated Successfully");
    
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
        url: base_url + "/MasterManagement/UserManagement/usersec_manage",
        data:{mat:3,id:num[1],CSRF_TOKEN:CSRF_TOKEN_VALUE}
    })
    .done(function(msg){
        //updateCSRFToken();
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
        //updateCSRFToken();
        alert("ERROR, Please Contact Server Room");
    });
});

// $(document).on("click","[id^='btnDelete']",async function(){
//     var num = this.id.split('btnDelete');
//     if(confirm("ARE YOU SURE TO REMOVE THIS USER SECTION") == true){
//         await updateCSRFTokenSync();
//         var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

//         $.ajax({
//             type: 'POST',
//             url: base_url + "/MasterManagement/UserManagement/usersec_manage",
//             data:{mat:1,func:2,id:num[1],CSRF_TOKEN:CSRF_TOKEN_VALUE}
//         })
//         .done(function(msg){
//             //updateCSRFToken();
//             var msg2 = msg.split('~');
//             if(msg2[0] == 1){
//                 // swal("Success!", msg2[1], "success");

//                 $("#name_usec").val("");
//                 $("#descp").val("");
                
                
//                     updateCSRFTokenSync();
//                     var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
//                     $.ajax({
//                         type: 'POST',
//                         url: base_url + "/MasterManagement/UserManagement/usersec_manage",
//                         data:{mat:2,CSRF_TOKEN:CSRF_TOKEN_VALUE}
//                     })
//                     .done(function(msg_new){
//                         //updateCSRFToken();
//                         if(msg_new.success == 1){
//                             if ($.fn.DataTable.isDataTable('#result_table')) {
//                                 $('#result_table').DataTable().destroy();
//                             }
//                             $("#result_main").html(msg_new.html);
//                             $("#id_usec").val(msg_new.get_Open_id);
//                             $('#result_table').DataTable();
//                         } else{
//                             alert("ERROR, Please Contact Server Room"); 
//                         }
//                     })
//                     .fail(function(){
//                         //updateCSRFToken();
//                         alert("ERROR, Please Contact Server Room"); 
//                     });
                
//                 //location.reload();
  
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

    if (confirm("ARE YOU SURE TO REMOVE THIS USER SECTION")) {
        await updateCSRFTokenSync();
        const CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        try {
            const deleteResponse = await $.ajax({
                type: 'POST',
                url: base_url + "/MasterManagement/UserManagement/usersec_manage",
                data: { mat: 1, func: 2, id: num, CSRF_TOKEN: CSRF_TOKEN_VALUE }
            });

            const msg2 = deleteResponse.split('~');
            if (msg2[0] == 1) {
                //location.reload();
                $("#name_usec").val("");
                $("#descp").val("");

                await updateCSRFTokenSync();
                const CSRF_TOKEN_VALUE2 = $('[name="CSRF_TOKEN"]').val();

                const listResponse = await $.ajax({
                    type: 'POST',
                    url: base_url + "/MasterManagement/UserManagement/usersec_manage",
                    data: { mat: 2, CSRF_TOKEN: CSRF_TOKEN_VALUE2 },
                    dataType: "json"
                });

                if (listResponse.success == 1) {
                    if ($.fn.DataTable.isDataTable('#result_main')) {
                        $('#result_main').DataTable().destroy();
                    }
                    $("#result_main").html(listResponse.html);
                    $("#id_usec").val(listResponse.get_Open_id);
                    $('#result_main').DataTable();
                    alert("User Section Deleted Successfully");
                } else {
                    alert("ERROR, Please Contact Server Room");
                }

            } else {
                //swal("Error!", deleteResponse, "error");
            }
        } catch (err) {
            console.error(err);
            alert("ERROR, Please Contact Server Room");
        }
    }
});


