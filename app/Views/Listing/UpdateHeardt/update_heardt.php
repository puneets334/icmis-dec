<?=view('header') ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">UPDATE HEARDT TABLE</h3>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <span class="alert-danger"><?=\Config\Services::validation()->listErrors()?></span>
                                    <?php if(session()->getFlashdata('error')){ ?>
                                        <div class="alert alert-danger text-white ">
                                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                            <?= session()->getFlashdata('error')?>
                                        </div>
                                    <?php } else if(session("message_error")){ ?>
                                        <div class="alert alert-danger">
                                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                            <?=session()->getFlashdata("message_error")?>
                                        </div>
                                    <?php }else{?>
                                        <br/>
                                    <?php }?>

                                    <?php
                                    $attribute = array('class' => 'form-horizontal','name' => 'component_search', 'id' => 'component_search', 'autocomplete' => 'off');
                                    echo form_open(base_url('#'), $attribute);
                                    ?>
                                    <?php echo component_html();?>
                                        <center> <button type="submit" class="btn btn-primary" id="submit">Get Details</button></center>
                                        <div id="report_result"></div>
                                    <?php form_close();?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="<?php echo base_url('plugins/jquery-validation/jquery.validate.js'); ?>"></script>
<script src="<?php echo base_url('plugins/jquery-validation/additional-methods.js'); ?>"></script>
<script>

        
$(document).ready(function() {
    $("#search_type_d").click(function(){
        $("#report_result").val("");
        $("#case_type").val("");
        $("#case_number").val("");
        $("#case_year").val("");
       // location.reload();
        
    });
    
    $("#search_type_c").click(function(){
        $("#report_result").val("");
       
        $("#diary_number").val("");
        $("#diary_year").val("");
      
        
    });

    $('#component_search').on('submit', function () {
        var search_type = $("input[name='search_type']:checked").val();
        if (search_type.length == 0) {
            alert("Please select case type");
            validationError = false;
            return false;
        }
        var diary_number = $("#diary_number").val();
        var diary_year =$('#diary_year :selected').val();
        
        var case_type =$('#case_type :selected').val();
        var case_number = $("#case_number").val();
        var case_year =$('#case_year :selected').val();

        if (search_type=='D') {
            if (diary_number.length == 0) {
                alert("Please enter diary number");
                validationError = false;
                return false;
            }else if (diary_year.length == 0) {
                alert("Please select diary year");
                validationError = false;
                return false;
            }
        }else if (search_type=='C') {
            if (case_type.length == 0) {
                alert("Please select case type");
                validationError = false;
                return false;
            }else if (case_number.length == 0) {
                alert("Please enter case number");
                validationError = false;
                return false;
            }else if (case_year.length == 0) {
                alert("Please select case year");
                validationError = false;
                return false;
            }

        }

        if ($('#component_search').valid()) {
            var validateFlag = true;
            var form_data = $(this).serialize();
            //console.log(form_data);

            if(validateFlag){
                var CSRF_TOKEN = 'CSRF_TOKEN';
                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                $('.alert-error').hide(); $(".form-response").html("");
                $("#loader").html('');
                
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('Listing/UpdateHeardt/get_proposal_heardt/'); ?>",
                    data: form_data,
                    beforeSend: function () {
                        $("#loader").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                    },
                        success: function (data) {
                        console.log(data);
                        updateCSRFToken();
                        var resArr1 = data;
                        var resArr = data.split('@@@');
                        if (resArr1) {
                            $('.alert-error').hide();
                            $(".form-response").html("");
                            $('#report_result').html('<table widht="100%" align="center"><tr><td><img src="../../images/load.gif"/></td></tr></table>');
                            $('#report_result').html(resArr1);
                        } else{
                            $('#div_result').html('');
                            $('.alert-error').show();
                            $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "</p>");
                        }
                    },
                    error: function() {
                        updateCSRFToken();
                        alert('Something went wrong! please contact computer cell');
                    }
                });
                return false;
            }
        } else {
            return false;
        }
    });
});


// $(document).on("click","input[name=savebutton]",function()
//     {
       
//         if($("#tdt").val()=='//' || $("#tdt").val()=='00/00/0000'){
//             alert('Please Enter Tentative Cause List Date');
//             $("#tdt").focus();
//             return false;
//         }
//         if($("#ndt").val()=='//' || $("#ndt").val()=='00/00/0000'){
//             alert('Please Enter Next Date');
//             $("#ndt").focus();
//             return false;
//         }
//         if($("#subhead").val()==''){
//             alert('Please Select Subheading');
//             $("#subhead").focus();
//             return false;
//         }
//         if($("#sitt_jud").val()=='' || $("#sitt_jud").val()==0 || $("#sitt_jud").val()==' '){
//             alert('Sitting Judges Can Not Left Blank or Zero');
//             $("#sitt_jud").focus();
//             return false;
//         }
        
//         if($("#subhead").val()==848 || $("#subhead").val()==849 || $("#subhead").val()==850){
//             if($("#board_type").val()!='R'){
//                 alert('For Selected Subhead, Board Type Should be Registrar');
//                 $("#board_type").focus();
//                 return false;
//             } 
//         }
//         if($("#reason_md").val()==''){
//             alert('Please Entre Reason to use module.');
//             $("#reason_md").focus();
//             return false;
//         }
//         var reason_md_str = $("#reason_md").val();
//         var CSRF_TOKEN = 'CSRF_TOKEN';
//         var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
//         reason_md_str = reason_md_str.trim();
//         if(reason_md_str.length<20){
//             alert('Please Entre Reason with minimum 20 characters.');
//             $("#reason_md").focus();
//             return false;
//         }
//         $.ajax({
//             type: 'POST',
           
//             url: "<?php echo base_url('Listing/UpdateHeardt/new_up_he_check_part/'); ?>",
//             data: {date:document.getElementById('ndt').value,heading:document.getElementById('heading').value,coram:document.getElementById('coram').value,
//                 session:document.getElementById('session').value,main_supp_flag:document.getElementById('main_supp').value,is_nmd:document.getElementById('is_nmd').value,CSRF_TOKEN: CSRF_TOKEN_VALUE,

           
//             }
//         })
        
//         .done(function(msg){
//             console.log("First AJAX Call Successful:", msg); // Debugging
           

            
//                 updateCSRFToken();
//                 var xmlhttp;
//                 if (window.XMLHttpRequest)
//                 {
//                     xmlhttp=new XMLHttpRequest();
//                 }
//                 else
//                 {
//                     xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
//                 }
//                 xmlhttp.onreadystatechange=function()
//                 {
//                     if (xmlhttp.readyState==4 && xmlhttp.status==200)
//                     {
//                         document.getElementById('show_fil').innerHTML=xmlhttp.responseText;
//                     }
//                 }

//                 let objData = {
//                     'dno' : document.getElementById('fil_hd').value,
//                     'ndt' : document.getElementById('ndt').value,
//                     'tdt' : document.getElementById('tdt').value,
//                     'session' : document.getElementById('session').value,
//                     'brd_slno' : document.getElementById('brd_slno').value,
//                     'heading' : document.getElementById('heading').value,
//                     'subhead' : document.getElementById('subhead').value,
//                     'coram' : document.getElementById('coram').value,
//                     'main_supp_flag' : document.getElementById('main_supp').value,
//                     'sitting_jud' : document.getElementById('sitt_jud').value,
//                     'purList' : document.getElementById('purList').value,
//                     'sinfo' : document.getElementById('sinfo').value,
//                     'board_type' : document.getElementById('board_type').value,
//                     'hd_subhead' : document.getElementById('hd_subhead').value,
//                     'reason_md' : document.getElementById('reason_md').value,
//                     'is_nmd' : document.getElementById('is_nmd').value
//                 }

//                 $.ajax({
//                     type: 'POST',
                  
//                     url: "<?php echo base_url('Listing/UpdateHeardt/save_proposal_heardt/'); ?>",
//                     data: objData
//                 })
//                 .done(function(msg2){
//                     updateCSRFToken();
//                     if(msg2 != ''){
//                         alert(msg2)
//                         $("input[name=btnGetR]").click();
//                     }
//                 })



           
        
//         })
//         .fail(function(){
//             updateCSRFToken();

//             alert("Error, Please Contact Server-Room");
//         });
        
//     });

$(document).on("click", "input[name=savebutton]", function () {
    if ($("#tdt").val() == '//' || $("#tdt").val() == '00/00/0000') {
        alert('Please Enter Tentative Cause List Date');
        $("#tdt").focus();
        return false;
    }
    if ($("#ndt").val() == '//' || $("#ndt").val() == '00/00/0000') {
        alert('Please Enter Next Date');
        $("#ndt").focus();
        return false;
    }
    if ($("#subhead").val() == '') {
        alert('Please Select Subheading');
        $("#subhead").focus();
        return false;
    }
    if ($("#sitt_jud").val() == '' || $("#sitt_jud").val() == 0) {
        alert('Sitting Judges Can Not Left Blank or Zero');
        $("#sitt_jud").focus();
        return false;
    }

    if ($("#subhead").val() == 848 || $("#subhead").val() == 849 || $("#subhead").val() == 850) {
        if ($("#board_type").val() != 'R') {
            alert('For Selected Subhead, Board Type Should be Registrar');
            $("#board_type").focus();
            return false;
        }
    }
    if ($("#reason_md").val() == '') {
        alert('Please Enter Reason to use module.');
        $("#reason_md").focus();
        return false;
    }
    
    let reason_md_str = $("#reason_md").val().trim();
    if (reason_md_str.length < 20) {
        alert('Please Enter Reason with minimum 20 characters.');
        $("#reason_md").focus();
        return false;
    }

    const CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
    $.ajax({
        type: 'POST',
        url: "<?php echo base_url('Listing/UpdateHeardt/new_up_he_check_part/'); ?>",
        data: {
            date: $("#ndt").val(),
            heading: $("#heading").val(),
            coram: $("#coram").val(),
            session: $("#session").val(),
            main_supp_flag: $("#main_supp").val(),
            is_nmd: $("#is_nmd").val(),
            CSRF_TOKEN: CSRF_TOKEN_VALUE,
        },
        dataType: 'json' 
    })
    .done(function (msg) {
        //console.log("First AJAX Call Successful:", msg);
        //updateCSRFToken(); 
        
        if (msg.if_list_is_printed==0) {
            let objData = {
                dno: $("#fil_hd").val(),
                ndt: $("#ndt").val(),
                tdt: $("#tdt").val(),
                session: $("#session").val(),
                brd_slno: $("#brd_slno").val(),
                heading: $("#heading").val(),
                subhead: $("#subhead").val(),
                coram: $("#coram").val(),
                main_supp_flag: $("#main_supp").val(),
                sitting_jud: $("#sitt_jud").val(),
                purList: $("#purList").val(),
                sinfo: $("#sinfo").val(),
                board_type: $("#board_type").val(),
                hd_subhead: $("#hd_subhead").val(),
                reason_md: $("#reason_md").val(),
                is_nmd: $("#is_nmd").val()
            };

            save_proposal_heardt(objData);

            
        } else {
            alert("This Part is Printed.");
        }
    })
    .fail(function () {
        alert("Error occurred while checking if the list is printed.");
    });
});

async function save_proposal_heardt(objData){
    
    await updateCSRFTokenSync();
    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
    objData.CSRF_TOKEN = CSRF_TOKEN_VALUE;
    $.ajax({
        type: 'POST',
        url: "<?php echo base_url('Listing/UpdateHeardt/save_proposal_heardt/'); ?>",
        data: objData,
        dataType: 'json'
    })
    .done(function (msg2) {
        //updateCSRFToken();
        alert(msg2);
        if (msg2) {
            alert(msg2);
            $("input[name=btnGetR]").click(); 
        }
    })
    .fail(function () {
        alert("Error saving proposal. Please try again.");
    });
}



    </script>
