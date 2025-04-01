var token = localStorage.getItem("token");
$(document).ready(function () {

    var maxDate = year + '-' + month + '-' + day;
    var dtToday = new Date();
    var month = dtToday.getMonth() + 1;
    var day = dtToday.getDate();
    var year = dtToday.getFullYear();
    if(month < 10)
        month = '0' + month.toString();
    if(day < 10)
        day = '0' + day.toString();
    var maxDate = year + '-' + month + '-' + day;
    $('#itemDate').attr('min', maxDate);
    ////////////////////////end before day date check

    var maxField = 20;
    var x = 1;
    var n = 0;
    $('.add_button_article').click(function () {
        //Check maximum number of input fields
        if (x < maxField) {
            x++;
            $("#addCitation").append('<div id="article_' + x + '">\n\
\n\
\n\
<div class="row"><div class="col-sm-3"><select name="fileType[]" class="form-control"><option value="0">Select File Type</option><option value="citation">Citation</option><option value="book">Book</option><option value="act">Act/Rules/Regulation</option><option value="other">Other</option></select></div><div class="col-sm-4"><input type="text"  class="form-control" name="citation[]" id="citation" ></div><div class="col-sm-4"><input type="file"  class="form-control" name="citationFile[]" id="citationFile" ></div><div class="col-sm-1"><i class="fa fa-minus remove_button" aria-hidden="true" style="margin-top: 10px;color:red" data-id="' + x + '"></i></div></div></div>'); //Add field html
        }
    });

    //Once remove button is clicked
    $('#addCitation').on('click', '.remove_button', function (e) {

        e.preventDefault();
        var len = $(this).data("id");
        //alert($(this).data("id"));
        if (len > 1) {
            $("#article_" + len).last().remove();
            x--;
        } else {
            alert('Not able to Delete');
        }

    });
});
var myWindow;
function openWin(id) {
    myWindow = window.open("admin/requisition/interaction_view.php?id=" + id, "myWindow", "width=600,height=800");
}



function call_AOR_interaction(url_link)
{
    //alert(url_link);
    window.location.href = "view_advocate_requistion.php?search_status=Interaction";



}


/***************Chnage status to receviec***********/
function changeRstatus(reqid)
{
    var requestID=reqid;
    var created_by = $("#court_userName").val();
    var status_val=$(this).prop('checked');
    console.log(requestID+" Val::"+status_val);
    var current_status="received";

    if ( confirm("Do you want to Change the status to recevied?"))
    {
        // If you pressed OK!";

        $.ajax({
            url: "admin/requisition/ajax.php",
            dataType: "json",
            type: "POST",
            data: { mode:'CHANGE-STAUS-RECEIVED',requestid: requestID,currentstatus:current_status,created_by:created_by},
            async: false,
            beforeSend:function(){
                return confirm("Are you sure?");
            },
            success: function (response) {
                if(response.status=="Success")
                {
                    setTimeout(
                        function ()
                        {
                            $('.successMsg').hide();
                            location.reload(true);   //do something special
                        }, 2000);

                }

            },
            error: function () {
                alert('Error while doing the request..!!');

            }
        });

    }else{
        location.reload(true);   //do something special
    }
}

/***************Chnage status to receviec***********/



function view_requistion_result(requestid)
{
    $('#btn_interaction').removeAttr('disabled');
    $('#interaction_remarks').val('').empty();
    $.ajax({
        type: 'POST',
        url: 'admin/requisition/ajax.php',
        data: {requestid: requestid, mode: 'REQUISTION-REQUEST'},
        dataType: ' json',
        async: false,
        error: function () {
            console.log("error");
        },
        success: function (response) {
            //  console.log(response);
            $("#court_number").val(response.court_number);
            $("#section").val(response.section);
            $("#current_status").val(response.current_status);
            $("#requisition_id").val(response.id);
            $("#urgent").val(response.urgent);
            $("#remark1").html(response.remark1);
            $("#remark1").css('color', '#800000');
            $("#courtview").text(response.court_number);

            if (response.request_file) {
                $("#myAnchor").show();
                document.getElementById("myAnchor").href = "../requisition/reqistionRequest/" + response.request_file;
            } else {
                $("#myAnchor").hide();
            }
        },
    });


}//END OF VIEW REQUISTION RESULT


function addInteractions()
{


    let formData = new FormData($("#frmrequistion")[0]);

    $.ajax({

        type: "POST",
        async: false,
        url: "admin/requisition/ajax.php",
        data: formData,
        contentType: false,
        processData: false,
        dataType: ' json',
        error: function () {
            console.log("error");
        },
        success: function (response) {


            console.log(response.msg);
            if (response.status == 'Success') {                       //
                $('#successMsg').text(response.msg);
                $('.alert-success').show();
                $('#interaction_remarks').val('').empty();
                $('#btn_interaction').removeAttr('disabled');
                setTimeout(
                    function ()
                    {
                        $('.successMsg').hide();
                        location.reload(true);   //do something special
                    }, 2000);


            } else {
                $('#errorMsg').text(response.msg);
                $('.alert-danger').show();
                setTimeout(
                    function ()
                    {
                        $('.alert-danger').fadeOut(3500);

                    }, 3000);
            }



        }

    });
}



function addAdvInteractions()
{

    let formData = new FormData($("#frmrequistionAdv")[0]);
    var current_status = $("#current_status").val();
    if (current_status == '') {
        $(".alert-danger").show();
        $("#errorMsg").text("Please Select Status");
        $("#current_status").focus();
        $('#errorMsg').fadeOut(3500);
    }else{

        $.ajax({

            type: "POST",
            async: false,
            url: "admin/requisition/ajax.php",
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            error: function () {
                console.log("error");
            },
            success: function (response) {


                console.log(response.msg);
                if (response.status == 'Success') {                       //
                    $('#successMsg').text(response.msg);
                    $('.alert-success').show();
                    $('#interaction_remarks').val('').empty();
                    $('#btn_interaction').removeAttr('disabled');
                    setTimeout(
                        function ()
                        {
                            $('.successMsg').hide();
                            location.reload(true);   //do something special
                        }, 2000);


                } else {
                    $('#errorMsg').text(response.msg);
                    $('.alert-danger').show();
                    setTimeout(
                        function ()
                        {
                            $('.alert-danger').hide();
                        }, 3000);
                }



            }

        });
    }//END OF ERROR COND
}




function getQueryData(id) {
    $("#uploadLabel").hide();
    $("#uploadCheck").hide();
    $.ajax({
        type: 'POST',
        url: 'admin/requisition/ajax.php',
        data: {mode: "REQUISTION-REQUEST", requestid: id},
        dataType: ' json',
        async: false,
        error: function () {
            console.log("error");
        },
        success: function (response) {
            $('#modelWindow').modal('show');
            $("#recvRemark").show();
            $("#addQuery").hide();
            $("#queryAdd").hide();
            $("#remark1").prop('disabled', true);
            $("#court_number").prop('disabled', true);
            $("#section").prop('disabled', true);
            $("#itemNo").prop('disabled', true);
            $("#itemNo").prop('disabled', true);
            $('input[name=urgent]').attr("disabled",true);

            if(response.urgent=="Yes")
            {
                $('#urgentY').attr('checked','checked');
                $('#urgentY').attr('checked', true);

            }else{
                $('#urgentN').attr('checked','checked');
                $('#urgentN').attr('checked', true);
            }
            $('#requestId').val(response.id);
            $('#court_number').val(response.court_number);
            $('#section').val(response.section);
            $('#remark1').summernote('code', response.remark1);
            $('#remark1').summernote('disable');


            $('#remark2').val(response.remark2);
            $('#itemNo').val(response.itemNo);
            if (response.request_file) {
                $("#myAnchor").show();
                document.getElementById("myAnchor").href = "../requisition/reqistionRequest/" + response.request_file;
            } else {
                $("#myAnchor").hide();
            }
        },
    });

}
function requestformValidation() {
    var asstStatus = $("#asstStatus").val();
    var asstRemark = $("#asstRemark").val();
    var requestId = $("#requestId").val();
    var userName = $("#court_userName").val();
    var fileval = $("#intractionImg").val();
    var token = $("#token").val();
    var urgent = $("input[name='urgent']:checked").val();
    // alert(urgent);
    var fd = new FormData();
    if (asstStatus == '') {
        $(".alert-danger").show();
        $("#errorMsg").text("Please Select Status");
        $("#asstStatus").focus();
        $('#errorMsg').fadeOut(3500);
    } else {
        var files = $('#intractionImg')[0].files;
        fd.append('mode', 'ADD-INTERACTION');
        fd.append('current_status', asstStatus);
        fd.append('interaction_remarks', asstRemark);
        fd.append('requisition_id', requestId);
        fd.append('urgent', urgent);
        fd.append('created_by', userName);
        fd.append('file', files[0]);
        fd.append('token', token);
        console.log("token**value********"+token);
        $.ajax({

            type: 'POST',
            url: 'admin/requisition/ajax.php',
            data: fd,
            dataType: ' json',
            async: false,
            processData: false,
            contentType: false,
            error: function () {
                console.log("error");
            },
            success: function (response) {
                console.log(response.msg);
                if (response.status == 'Success') {                       //
                    $('#successMsg').text(response.msg);
                    $('.alert-success').show();
                    setTimeout(
                        function ()
                        {
                            $('.successMsg').hide();
                            location.reload(true);   //do something special
                        }, 2000);


                } else {
                    $('#errorMsg').text(response.msg);
                    $('.alert-danger').show();
                    setTimeout(
                        function ()
                        {
                            $('.alert-danger').hide();
                        }, 3000);
                }

            },
        });
    }
}
function advformValidation() {
    var courtNo = $("#court_number").val();
    var itenNo = $("#itemNo").val();
    var itemDate = $("#itemDate").val();
    var remark = $("#remark1").val();
    var fileval = $("#intractionImg").val();
    var phoneNo = $("#phoneNo").val();
    if (courtNo == '') {
        $(".alert-danger").show();
        $("#errorMsg").text("Please Select Court Number");
        $("#court_number").focus();
        $('#errorMsg').fadeOut(3500);
    }else if(phoneNo == ''){
        $(".alert-danger").show();
        $("#errorMsg").text("Please Enter  Alternate Mobile Number");
        $("#phoneNo").focus();
        $('#errorMsg').fadeOut(3500);
    }else if(itenNo == ''){
        $(".alert-danger").show();
        $("#errorMsg").text("Please Enter Iten Number");
        $("#itemNo").focus();
        $('#errorMsg').fadeOut(3500);
    }else if(itemDate == ''){
        $(".alert-danger").show();
        $("#errorMsg").text("Please Select Item Date");
        $("#itemDate").focus();
        $('#errorMsg').fadeOut(3500);
    }else if(remark == ''){
        $(".alert-danger").show();
        $("#errorMsg").text("Please Enter  Requisition Request");
        $("#remark1").focus();
        $('#errorMsg').fadeOut(3500);
    } else {
        let data = new FormData($("#frmrequistionAdv")[0]);
        $("#addAdvQuery").prop('disabled', true);
        $.ajax({
            type: 'POST',
            url: 'admin/requisition/ajax.php',
            data: data,
            dataType: ' json',
            async: false,
            processData: false,
            contentType: false,
            error: function () {
                console.log("error");
            },
            success: function (response) {
                console.log(response.msg);
                if (response.status == 'Success') {                       //
                    $('#successMsg').text(response.msg);
                    $('.alert-success').show();
                    setTimeout(
                        function ()
                        {
                            $("#addAdvQuery").prop('disabled', false);
                            $('.successMsg').hide();
                            location.reload(true);   //do something special
                        }, 2000);


                } else {
                    $("#addAdvQuery").prop('disabled', false);
                    $('#errorMsg').text(response.msg);
                    $('.alert-danger').show();
                    setTimeout(
                        function ()
                        {
                            $('.alert-danger').hide();
                        }, 3000);
                }

            },
        });
    }
}



function validateForm() {
    var fd = new FormData();
    var role = $("#role_id").val();
    var errval = 0;
    if (role == '') {
        var errval = 1;
        alert("Please select role");
        $("#role_id").focus();
        return false;
    } else {
        if (role == 5 ) {
            var user_name = $("#user_name").val();
            var pass = $("#user_password").val();
            if (user_name == '') {
                var errval = 1;
                alert("Please enter username");
                $("#user_name").focus();
                return false;
            } else if (pass == '') {
                var errval = 1;
                alert("Please enter password");
                $("#user_password").focus();
                return false;
            }

        }else if (role == 6) {
            var user_name = $("#user_name_admin").val();
            var pass = $("#user_password").val();
            if (user_name == '') {
                var errval = 1;
                alert("Please enter username");
                $("#user_name").focus();
                return false;
            } else if (pass == '') {
                var errval = 1;
                alert("Please enter password");
                $("#user_password").focus();
                return false;
            }

        }else if (role == 7) {
            var user_name = $("#user_name_adv").val();
            var pass = $("#user_password").val();
            if (user_name == '') {
                var errval = 1;
                alert("Please enter username");
                $("#user_name").focus();
                return false;
            } else if (pass == '') {
                var errval = 1;
                alert("Please enter password");
                $("#user_password").focus();
                return false;
            }

        }
        else if (role == 4) {
            var court_number = $("#court_number").val();
            var user_name_a = $("#user_name_a").val();
            var user_name_other = $("#user_name_other").val();

            if (court_number == '') {
                var errval = 1;
                alert("Please enter court Number");
                $("#court_number").focus();
                return false;
            } else if (user_name_a == '') {
                var errval = 1;
                alert("Please enter username");
                $("#user_name_a").focus();
                return false;
            } else if (user_name_a != '') {
                if (user_name_a == "Other") {
                    if (user_name_other == '') {
                        var errval = 1;
                        alert("Please enter Other user Name");
                        $("#user_name_other").focus();
                        return false;
                    }
                }


            }

        }

    }//end of roleid cond


    if (errval == 0) {
        var values = $("#frmusrLogin").serialize();

        var CSRF_TOKEN = 'CSRF_TOKEN';
		    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

            values.append('CSRF_TOKEN', CSRF_TOKEN_VALUE);
        alert('fffff');
        $.ajax({
            url: base_url + '/Library/Requisition/frmusrLogin',  // ajax.php
            type: 'POST',
            dataType: "json",
            data: values,
            success: function (response) {
                updateCSRFToken();
                if (response.status == 'Success')
                    {
                        if (role == 5 || role == 6) {
                            window.location.href = 'view_court_requisition.php';
                        }
                        if (role == 4) {
                            window.location.href = base_url + '/Library/Requisition/court_dashboard';
                        }
                        if (role == 7) {
                            window.location.href = 'advocate_dashboard.php';
                        }
                    } else {
                        alert(response.msg);
                        return false;
                    }
            },
            error: function () {
                updateCSRFToken();
                alert("Failure");
            }
        });

 
         

    }


}//end of function


function getreqForm(){
    window.location.href = base_url + '/Library/Requisition/court_dashboard';
}

function getreqHome(){
    window.location.href = 'view_court_requisition.php'
}


function formValidation() {

    var fd = new FormData();
    var courtNo = $('#court_no').find(":selected").val()
    var section = $("#section").val();
    var request = $("#remark1").val();
    var userName = $("#court_userName").val();
    var userIp = $("#userIp").val();
    var urgent = $("input[name='urgent']:checked").val();
    var itemNo = $("#itemNo").val();
    var court_bench = $("#court_bench").val();
    var token = $("#token").val();
    var item_date = $('#itm_date').text();
    if(item_date == ''){
        $(".alert-danger").show();
        $("#errorMsg").text("No active Cause list found.");
        $("#court_no").focus();
        $('#errorMsg').fadeOut(3500);
        return false;
    }
    //var fileval = $("#remarkImg").val();
    //var ext = fileval.split('.').pop();
    var diary_no = $("#diary_no").val();

    var adv_name = $('#adv_name').val()
    var appering_for = $('#appearing_for').val()
    var party_sno = $('#party_sno').val()




    let remarksArr = []
    let totalRemCols = 0
    let remCount = 0
    $('.other_relevent_material').each(function(k,e){
        totalRemCols = totalRemCols +1
        if($(e).val() != ''){
            remarksArr.push($(e).val())
            remCount = remCount + 1
        }
    })

    let errCount = 0
    let fileUpCount = 0
    $('.upload_document').each(function(k,e){
        if($(e).prop('files').length > 0){
            let size = $(e).prop('files')[0].size
            if(size > 100000000){
                errCount =  errCount + 1
            }else{
                fd.append('file-'+k, $(e).prop('files')[0] );
                fileUpCount = fileUpCount +1
            }
        }
    })

    let user_role_id = $('#userrole_id').val()
    // console.log("user_role_id:: ", user_role_id, typeof user_role_id)
    // console.log(Number(fileUpCount) , Number(remCount))
    // return
    if(user_role_id == '5' || user_role_id == 5 ){
        if(Number(fileUpCount) != Number(remCount)){
            if(Number(remCount) < Number(fileUpCount)){
                alert("Remarks mandatory for uploaded document files.")
            }
            if(Number(fileUpCount) < Number(remCount)){
                alert("Document files are mandatory for Remarks.")
            }
            return false
        }
    }else if(user_role_id == '4' || user_role_id == 4){
        if(Number(totalRemCols) != Number(remCount)){
            alert("Remarks are mandatory.")
            return false
        }
    }


    if(errCount > 0){
        alert("Size not more then 100MB")
        return false
    }else{
        if (section == '') {
            $(".alert-danger").show();
            $("#errorMsg").text("Please Select Section");
            $("#section").focus();
            $('#errorMsg').fadeOut(3500);
            return false;
        }else if(itemNo == ''){
            $(".alert-danger").show();
            $("#errorMsg").text("Please Enter Item Number");
            $("#itemNo").focus();
            $('#errorMsg').fadeOut(3500);
        }else if (request == '') {
            $(".alert-danger").show();
            $("#errorMsg").text("Please Enter Request");
            $("#remark1").focus();
            $('#errorMsg').fadeOut(3500);
            return false;
        }else {
            //var files = $('#remarkImg')[0].files;
            fd.append('mode', 'addRequest');
            fd.append('courtNo', courtNo);
            fd.append('section', section);
            fd.append('request', request);
            fd.append('userName', userName);
            fd.append('userIp', userIp);
            fd.append('itemNo', itemNo);
            fd.append('urgent', urgent);
            fd.append('court_bench', court_bench);
            fd.append('token', token);
            fd.append('itemDate', item_date.split("-").reverse().join("-") )
            //fd.append('file', files[0]);
            fd.append('diary_no', diary_no);
            fd.append('remarks_arr', remarksArr);

            fd.append('advocate_name', adv_name);
            fd.append('appearing_for', appering_for);
            fd.append('party_serial_no', party_sno);
            
            var CSRF_TOKEN = 'CSRF_TOKEN';
		    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

            fd.append('CSRF_TOKEN', CSRF_TOKEN_VALUE);
           
            $.ajax({
                type: 'POST',
                url: 'admin/requisition/ajax.php',
                data: fd,
                dataType: 'json',
                async: false,
                processData: false,
                contentType: false,
                error: function () {
                    console.log("error");
                },
                success: function (response) {
                    console.log(response.msg);
                    if (response.status == 'Success') {                       //
                        $('#successMsg').text(response.msg);
                        $('.alert-success').show();
                        setTimeout(
                            function ()
                            {
                                $('.successMsg').hide();
                                location.reload(true);   //do something special
                            }, 2000);


                    } else {
                        $('#errorMsg').text(response.msg);
                        $('.alert-danger').show();
                        setTimeout(
                            function ()
                            {
                                $('.alert-danger').hide();
                            }, 3000);
                    }

                },
            });
        }
    }


}
function getToggleView(id) {
    $("#toggle_text" + id).toggle();
    $.ajax({
        type: 'POST',
        url: 'admin/requisition/ajax.php',
        data: {mode: "getReqIntractionReport", id: id},
        dataType: ' json',
        async: false,
        error: function () {
            console.log("error");
        },
        success: function (response) {
            var encode = atob(response.html);
            $("#getReport" + id).html(encode);
        },
    });
}
function show_role_div(roleid)
{

    $("#user_name").val('');
    $("#user_name_a").val('');
    $("#court_number").val('');
    $("#user_password").val('');
    $("#user_name_other").val('');
    if (roleid == 4)
    {

        $("#role_courtAssitant").show();
        $("#role_librarian").hide();$("#role_admin").hide();
        $("#role_Advocate").hide();

    }

    if (roleid == 5)
    {

        $("#role_courtAssitant").hide();
        $("#role_librarian").show();
        $("#role_admin").hide();
        $("#role_Advocate").hide();

    }


    if (roleid == 6)
    {

        $("#role_courtAssitant").hide();
        $("#role_librarian").hide();
        $("#role_Advocate").hide();
        $("#role_admin").show();

    }
    if (roleid == 7)
    {

        $("#role_courtAssitant").hide();
        $("#role_librarian").hide();
        $("#role_admin").hide();
        $("#role_Advocate").show();

    }
}
function showOtherUsrType(userType) {
    if (userType == "Other")
    {
        $("#other_user_div").show();
    } else {
        $("#other_user_div").hide();
    }
}
function getadvForm(){
    $("#advForm").show();
    $("#backList").show();
    $("#showForm").show();
    $("#tlData").hide();
}
function backList(){
    $("#advForm").hide();
    $("#backList").hide();
    $("#showForm").show();
    $("#tlData").show();
}

function getCaseNo(value){
    let dateitm = $('#dtd').find(":selected").val()
    let courtno = $('#court_no').find(":selected").val()
    $.ajax({
        type: 'POST',
        url: 'admin/requisition/ajax.php',
        data: {mode: "getCaseNo", item_no: value, 'dateitem': dateitm, court_no: courtno},
        dataType: 'json',
        async: false,
        error: function () {
            console.log("error");
        },
        success: function (response) {
            console.log(response);
            if(response){
                $('#dynamicDetails').show()
                $('#case_no').html(response.reg_no_display+' @ '+response.diary_no);
                $('#pet_res_name').html(response.pet_name+' Vs '+response.res_name);
                $('#diary_no').val(response.diary_no);
                $('#itm_date').html( response.next_dt.split("-").reverse().join("-") )
            }else{
                $('#dynamicDetails').hide()
                $('#case_no').html('');
                $('#pet_res_name').html('');
                $('#diary_no').val('');
                $('#itm_date').html('')
            }

        },
    });
}
