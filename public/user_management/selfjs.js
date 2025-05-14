$(function () {
    var host_docroot='';
    //host_docroot='http://10.40.186.34/icmis_docroot/';

    $('.tree li:has(ul)').addClass('parent_li').find(' > span').attr('title', 'Collapse this branch');
    $('.tree li.parent_li > span').on('click', function (e) {
        var children = $(this).parent('li.parent_li').find(' > ul > li');
        if (children.is(":visible")) {
            children.hide('fast');
            $(this).attr('title', 'Expand this branch').find(' > i').addClass('fa-plus').removeClass('fa-minus');
            $(this).attr('title', 'Collapse this branch').prev("#menuIds").removeAttr('disabled');
        } else {
            children.show('fast');
            $(this).attr('title', 'Collapse this branch').find(' > i').addClass('fa-minus').removeClass('fa-plus');
            $(this).attr('title', 'Collapse this branch').prev("#menuIds").attr('disabled',true).prop('checked',false);
        }
        e.stopPropagation();
    });

    $('#smlv1, #smlv2, #smlv3, #smlv4, #smlv5, #smlv6').change(function(e) {
        e.preventDefault();
        debugger;
        var mnid=$(this).val(), select_menu_id=$(this).attr('id'), mnlevel=parseInt(select_menu_id.substring(select_menu_id.length -1)) + 1,
        next_child=select_menu_id.substring(0,(select_menu_id.length -1))+ mnlevel;

        for(var i=mnlevel; i<=6; i++){
            $('.smlv'+i).addClass('hide');
        }
        if(mnid.search('addNew') == -1 && mnid != '') {

            $.ajax({
                url: host_docroot+'addMenu.php',
                type: 'post',
                data: {mnid:mnid},
                beforeSend: function(){
                    $("#loadMe").modal({
                      backdrop: "static",
                      keyboard: false,
                      show: true
                    });
                },
                success: function(response,status){
                    if(status=='success') {
                        $('.'+next_child).removeClass('hide'),
                        $('#'+next_child).attr("required", true),
                        $('#'+next_child).html(response);
                    }
                },
                error: function(){
                    alert('Errror in ajax file');
                },
                complete: function() {
                    $("#loadMe").modal("hide");
                }
            });
        }
        else return false;
    });

    $('#addNewMenus').submit(function(e){
        e.preventDefault();
        var data, action='', chnageMenuLevel='';
        chnageMenuLevel=$('#smlv1').val();
        data=new FormData(this);
        action=$('.btn-success').val();
        if(action=='Update' && chnageMenuLevel=='') {
            data.append('action', 'menuUpdate');
        }
        $.ajax({
            url: host_docroot+'addMenu.php',
            type: 'POST',
            data: data,
            processData: false,
            contentType: false,
            dataType: 'text',
            beforeSend: function(){
                $("#loadMe").modal({
                    backdrop: "static", //remove ability to close modal with click
                    keyboard: false, //remove option to close with keyboard
                    show: true //Display loader!
                });
            },
            success: function(response,status){
                if(status=='success' && response=='Inserted') {
                    var msg='Record successfully inserted.';
                    alert(msg);
                    window.location.reload(true);
                    $('#msgDiv').removeClass('hide').find('strong').html('Success! ');
                    $('#msgDiv').find('strong').after(msg);

                    /*setTimeout(function() {
                     window.location.reload(true);
                     }, 3000);*/
                }
                else if(response=='Failed'){
                    var msg='Record not inserted.';
                    alert(msg);
                    $('#msgDiv').removeClass('alert-success hide').addClass('alert-danger').find('strong').html('Error! ');
                    $('#msgDiv').find('strong').after(msg);
                }
                else if(response == 'Updated'){
                    alert('Record updated successfully.');
                    window.location.reload(true);
                }
                else if(response == 'Transfer'){
                    var msg='Record moved successfully.';
                    alert(msg);
                    $('#msgDiv').removeClass('alert-success hide').addClass('alert-danger').find('strong').html('Error! ');
                    $('#msgDiv').find('strong').after(msg);
                    window.location.reload(true);
                }
            },
            error: function(){
                alert('Errror in ajax file');
            },
            complete: function() {
                $("#loadMe").modal("hide");
            }
        });
    });

    $('#example a#edit, a#upermission').click(function(e){
        e.preventDefault();
        var ucode=$(this).attr('data-id'), action=$(this).attr('id');
        if(action=='upermission'){

            $('#menuPerm').modal({
                  backdrop: "static",
                  keyboard: false,
                  show: true
            });
            $('input[name="menus"]').each(function() {
                this.checked = false;
            });
            debugger;
            $('#usercode').val(ucode);
        }
        else if(action=='edit'){

        }
    });


    $('#menu_perm').submit(function(e){

        e.preventDefault();
        var ck_string = "", usercode=$('#usercode').val(), action='';
        action=$('#action').val();

        $.each($("input[name='mRoleId']:checked"), function(){  
            ck_string += $(this).val()+',';  
        });
        if (ck_string ) {
            if(action != 'Update') var action='GrantPermission';
            $.ajax({
                url: host_docroot+'addMenu.php',
                type: 'post',
                data: {selected_menus:ck_string, usercode:usercode, action:action},
                success: function(response,status){
                    if(status=='success' && response=='Inserted') {
                        var msg='Permission has been granted of selected user(s).'
                        if(action !='Update'){ 
                            alert(msg);
                            window.location.reload(true);
                        }else {
                            alert(msg);
                            $('#menuPerm').modal("hide"),$('.msg').removeClass('hide'),
                            $('.msg > span').text(msg);
                        }
                    }
                    else if(response=='Failed') {
                        alert('Server busy, try later.');
                    }
                },
                error: function(){
                    alert('Errror in ajax file');
                }
            });
        }else{
            alert('Please choose atleast one value.');
        }
    });

    $('#addMenus, #usersList, #menusList').click(function(e){
        e.preventDefault();
        var action=$(this).attr('id');

        if(action=='addMenus'){
            /*$('#addMenusDiv').removeClass('hide');
            $('#listUsersDiv, #listMenusDiv').addClass('hide');
            $('#addMenus')[0].reset();*/
            window.location.reload(true);
        }
        else if(action=='menusList'){
            $('#listMenusDiv').removeClass('hide');
            $('#addMenusDiv, #listUsersDiv').addClass('hide');
        }
        else if(action=='usersList'){
            $('#listUsersDiv').removeClass('hide');
            $('#addMenusDiv, #listMenusDiv').addClass('hide');
        }
    });

    $('#uy, #un, #upermisn').click(function(e){
        e.preventDefault();
        var action='', id='', userIdMultiRole='';
        action=$(this).attr('id'), id=$(this).attr('data-id');
        if(action == 'upermisn') action='getAlotmentMenu';
        else action='UpdUserDisplay_'+action;
        userIdMultiRole= $('#multiSelect').val();

        $.ajax({
            type: 'post',
            url: host_docroot+'upd_umpermission.php',
            data: {menu_id:id, action:action},
            dataType: 'html',
            success: function(data, status){
                if(action == 'getAlotmentMenu') {

                    $('#menuPerm').modal({
                        backdrop: "static",
                        keyboard: false,
                        show: true
                    });
                    if(userIdMultiRole == ''){
                        $('#usercode').val(id);
                    }
                    else {
                        $('#usercode').val(userIdMultiRole);
                    }
                    $('#menuTbody').empty().append(data);
                }else if(action == 'UpdUserDisplay_uy' || action == 'UpdUserDisplay_un'){
                    alert('User record updated successfully');
                    window.location.reload(true);
                }
            },
            error: function(){
                alert('Error in function');
            }
        });

    });

    $("input[name='selectUser']").click(function(e){
        var chooseUser='', selectedUser='', finalUser='';
        chooseUser=$(this).val(), selectUser=$('#multiSelect').val();
        if($(this).prop("checked") == true) {
            if(selectUser!='')
                finalUser=selectUser+chooseUser+',';
            else finalUser=chooseUser+',';
            $('#multiSelect').val(finalUser);
        }
        else if($(this).prop("checked") == false) {
            finalUser=selectUser.replace(chooseUser+',','');
            $('#multiSelect').val(finalUser);
        }
    });

    $('#my, #mn, #editMenu').click(function(e){
        e.preventDefault();
        var action='', id='';
        action=$(this).attr('id'), id=$(this).attr('data-id');

        $.ajax({
            type: 'post',
            url: host_docroot+'upd_umpermission.php',
            data: {menu_id:id, action:action},
            dataType: 'json',
            success: function(resp, status){
                if(resp.data=='success' && action != 'editMenu') {
                    alert('Selected menu updated successfully');
                    window.location.reload(true);
                }
                else if(resp.data == 'failed' && action != 'editMenu') {
                    alert('Surver busy, try later');
                    //window.location.reload(true);
                }
                else if(action == 'editMenu'){
                    $('#listUsersDiv, #listMenusDiv').addClass('hide'),
                    $('#addMenusDiv').removeClass('hide');
                    $('#smlv1').attr('disabled', true).addClass('hide');
                    $('#caption').val(resp.data[1]), $('#url').val(resp.data[5]), 
                    $('#priority').val(resp.data[2]), $('.btn-success').val('Update'),
                    $('#oldsmid').val(resp.data[6]),
                    $('#addNewMenus').append('<input type="hidden" value="'+id+'" name="menu_id" readonly>');
                }
            },
            error: function(){
                alert('Error in function');
            }
        });            
    });

    //******** Add New Users ********//
    $('#addUsers').submit(function(e){
        e.preventDefault();
        var data_form, action='', action_type='';
        data_form=new FormData(this), action='UserProfileUpdate', action_type=$('input[type="submit"]').val();
        data_form.append('action',action);

        if(action_type == 'Create'){
           data_form.append('actionType', 'AddUser'); 
        }

        $.ajax({
            url: base_url +'/MasterManagement/RolesController/roleparameter',
            type: 'post',
            data:  data_form,
            contentType: false,
            cache: false,
            processData:false,
            dataType: 'json',
            beforeSend: function(){
                $('input[type="submit"]').attr('disabled', true).val('Request under proccess');
            },
            success: function(resp){
                updateCSRFToken();
              if(action_type != 'Create' && resp.data=='success') {
                alert('User profile updated successfully.');
                window.location.href='./';
              }
              else if(action_type == 'Create' && resp.data == 'success') {
                alert('User registerd successfully.');
                window.location.reload(true);
              }
              else if(resp.error != '0') {
                alert(resp.error);
                $('input[type="submit"]').removeAttr('disabled').val(action_type);
              }
            },
            error: function(){
                updateCSRFToken();
                alert('Ajax error found');
                $('input[type="submit"]').removeAttr('disabled').val(action_type);
            }             
        });
    });

    $('#upic').change(function(e){
        e.preventDefault();
        var file = this.files[0];
        var fileType = file["type"];
        var validImageTypes = ["image/gif", "image/jpeg", "image/jpg"];
        if ($.inArray(fileType, validImageTypes) < 0) {
             alert("Please choose valid photo \r\n (i.e. jpg, jpeg & gif only)");
             $(this).val('');
             return false;
        }
        var file_size = file.size;
        if(file_size>2097152) {
            alert("File size is greater than 2MB");
            $(this).val('');
            return false;
        } 
    });

    $('#empid').change(function(e){
        e.preventDefault();
        emp_id=$(this).val();
        $.ajax({
            url: base_url +'/MasterManagement/RolesController/roleparameter',
            type: 'post',
            data: { empid: emp_id, action: 'empVerify'},
            dataType: 'json',
            beforeSend: function(){
                $('input[type="submit"]').attr('disabled', true);
            },
            success: function(resp){
                updateCSRFToken();
                if(resp.data=='found'){
                    alert("Emp. id already exists,\r\nKindly verify it & tray again");
                    $('input[type="submit"]').removeAttr('disabled');
                    debugger;
                    $('#empid').val("");
                }
                else if(resp.data==''){
                    $('input[type="submit"]').removeAttr('disabled');                    
                }
            },
            error: function(){
                updateCSRFToken();
                alert('Ajax error');
            }
        });
    });
        
    //****** role Management *******//
    $('#addNewrole').submit(function(e){
        e.preventDefault();        
        var selected_smenus="", data_form, action='createRole', submitBtnType='', roleMid='';
        submitBtnType=$("input[type='submit']").val(), roleMid=$('#roleMid').val();
        data_form=new FormData(this);
        let checkedCount = $('input[name="menuIds"]:checked').length;
        if(checkedCount == 21){
            alert("Please select atleast one value in rolelist");
            return false;
        }
        if(submitBtnType=='Update') {
            action='updateRole';
            data_form.append('roleMid',roleMid);
        }
        data_form.append('action',action);
        $.each($(".tree.well input[name='menuIds']:checked"), function(){  
            selected_smenus += $(this).val()+',';  
        });
        

        data_form.append('menuIds',selected_smenus);
        if (selected_smenus) {
            $.ajax({
                url: base_url +'/MasterManagement/RolesController/roleparameter',
                type: 'post',
                data: data_form,                
                processData: false,
                contentType: false,
                dataType: 'json',
                beforeSend: function(){
                    $("input[type='submit']").val('Request under proccess').attr('disabled', true);
                },
                success: function(resp){
                    updateCSRFToken();
                    if(resp.data=='success') {
                        msg='Role created successfully';
                        if(action=='updateRole') {
                            msg='Role updated successfully';
                        }
                        alert(msg);
                        window.location.reload(true);
                    }else if(resp.data=='0') {
                        alert(resp.error);
                    }
                },
                error: function(){
                    updateCSRFToken();
                    $("input[type='submit']").val('Update').attr('disabled', false);
                    alert('Error in Ajax');
                }
            });
        }else{
            alert('Please choose atleast one value.');
        }
    });

    $('#roleList, #addrole').click(function(e){
        e.preventDefault();
        var activeid;
        activeid=$(this).attr('id');

        if(activeid=='roleList'){
            //debugger;
            $('#addroleDiv').addClass('hide');
            $('#roleListDiv').removeClass('hide');
        }
        else if(activeid=='addrole'){
            $('#roleListDiv').addClass('hide');            
            $('#addroleDiv').removeClass('hide');
        }
    });

	$(document).on('click', '#roleDelete', function() {
		var id = $(this).attr('data-id');
        var action = $(this).attr('id');
		var roleDesc = $(this).attr('role-desc');
        var postUrl= base_url +'/MasterManagement/RolesController/roleparameter'; 
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var csrf = $("#token_"+id).val();
		$.ajax({
                url: postUrl,
                type: 'post',
                data: {menu_id:id, action: action, CSRF_TOKEN: csrf},
                dataType: 'json',
                success: function(resp){
                    updateCSRFToken();
                    if(action == 'roleDelete') {
                        if(resp.data == 'success') {
                            alert('Selected role has been deleted successfully');
                            window.location.reload(true);
                        }
                        else if(resp.data == '0') {
                            alert(resp.error);
                        }
                    }
				},
                error: function(){
                    updateCSRFToken();
                    alert('Server busy, try later');
                }
            });
	});


    $('a#roleEdit').click(function(e){
		var id,
        action,
        rtnType='json', 
        postUrl= base_url +'/MasterManagement/RolesController/roleparameter', 
        roleDesc='';
        id=$(this).attr('data-id'), action=$(this).attr('id'), roleDesc=$(this).attr('role-desc');
        if(action == 'roleEdit') {
            action='GetAllMenus', rtnType='html', postUrl=host_docroot+'menusTreeView.php';
            $('#FormRoleEdit').submit();
        }
        else {
			$.ajax({
                url: postUrl,
                type: 'post',
                data: {menu_id:id, action: action},
                dataType: rtnType,
                success: function(resp){
                    updateCSRFToken();
                    if(action == 'roleDelete') {
                        if(resp.data == 'success') {
                            alert('Selected role has been deleted successfully');
                            window.location.reload(true);
                        }
                        else if(resp.data == '0') {
                            alert(resp.error);
                        }
                    }

                    else if(action == 'GetAllMenus') {
                        $("#roleModal").modal({
                          backdrop: "static",
                          keyboard: false,
                          show: true
                        });
                        $('#rheading').val(roleDesc), $('#roleTbody').html(resp);
                    }
                },
                error: function(){
                    updateCSRFToken();
                    alert('Server busy, try later');
                }
            });
        }
    });

    $('#roleUpdate').submit(function(e){
        e.preventDefault(); 
        var selected_smenus='', roleId=$('#roleId').val(), roleCaption=$('#rheading').val();
        $.each($("input[name='menuIds']:checked"), function(){  
            selected_smenus += $(this).val()+',';  
        });
        $.ajax({
            url: base_url +'/MasterManagement/RolesController/roleparameter',
            type: 'post',
            data: { menu_id:roleId, menuIds:selected_smenus, rheading: roleCaption, action: 'roleUpdate' },
            dataType: 'json',
            success: function(resp) {
                updateCSRFToken();
                if(resp.data=='success') {
                    alert('Role Updated successfully');
                    window.location.reload(true);                    
                }
                else if(resp.data=='0') {
                    alert('Server busy, try later');
                }
            },
            error: function(){
                updateCSRFToken();
                alert('URL not found.');
            }
        });

    });

    // Modal funcation
    /*$("#just_load_please").on("click", function(e) {
        e.preventDefault();
        $("#loadMe").modal({
          backdrop: "static", //remove ability to close modal with click
          keyboard: false, //remove option to close with keyboard
          show: true //Display loader!
        });
        setTimeout(function() {
          $("#loadMe").modal("hide");
        }, 3500);
    });*/


});

//******Alert Box Dismiss Automaticaly ******//
/*window.setTimeout(function() {
    $(".alert").fadeTo(500, 0).slideUp(500, function(){
        $(this).remove(); 
    });
}, 5000);*/


function check_uncheck_checkbox(isChecked) {
    if(isChecked) {
        $('input[name="menus"]').each(function() {
            this.checked = false;  
            $(this).attr('disabled',true);            
        });
        $('.all-menus-box').removeAttr('disabled').prop('checked',true);
    } else {
        $('input[name="menus"]').each(function() {
            $(this).removeAttr('disabled');
        });
    }
}

function moveMenu(chk) {
    if(chk==true) {
        $('#smlv1').removeClass('hide').removeAttr('disabled');
        $('input[type="submit"]').val('Move to another level').removeClass('btn-success').addClass('btn-danger');
    }
    else {
        $('#smlv1').val("").addClass('hide').attr('disabled', true);
        $('.smlv2, .smlv3, .smlv4, .smlv5, .smlv6').addClass('hide',true).find('select').removeAttr('required');
        $('input[type="submit"]').val('Update').removeClass('btn-danger').addClass('btn-success');
    }
}
