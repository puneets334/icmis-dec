<?=view('header') ?>
<style>
 
</style>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">

                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">
                                Add Case in Single Judge Advance List</h3>
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
                                    <?php form_close();?>
                                    <br>

                                     <div id="report_result" class="center blue-text"></div>
                                     

                                  
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

        

    $("#search_type_d").click(function()
    {
        
        
       
        $("#report_result").val("");
        $("#case_type").val("");
        $("#case_number").val("");
        $("#case_year").val("");
       // location.reload();
        
    });
    
    $("#search_type_c").click(function()
    {
        $("#report_result").val("");
       
        $("#diary_number").val("");
        $("#diary_year").val("");
      
        
    });
     $('#component_search').on('submit', function ()
    {

                var search_type = $("input[name='search_type']:checked").val();
                if (search_type.length == 0)
                {
                    alert("Please select case type");
                    validationError = false;
                    return false;
                }
                var diary_number = $("#diary_number").val();
                var diary_year =$('#diary_year :selected').val();
                var case_type =$('#case_type :selected').val();
                var case_number = $("#case_number").val();
                var case_year =$('#case_year :selected').val();

                if (search_type=='D')
                {
                
                    if (diary_number.length == 0) {
                        alert("Please enter diary number");
                        validationError = false;
                        return false;
                    }else if (diary_year.length == 0) {
                        alert("Please select diary year");
                        validationError = false;
                        return false;
                    }
                }
                else if (search_type=='C')
                {
                    //alert('Case details');

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

                if ($('#component_search').valid())
                {
                    //updateCSRFToken();
                    var validateFlag = true;
                    var form_data = $(this).serialize();
                    if(validateFlag)
                    {
                        //var CSRF_TOKEN = 'CSRF_TOKEN';
                       // var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                        $('.alert-error').hide(); $(".form-response").html("");
                        $("#loader").html('');
                       
                       getCaseDetails(form_data);
                        return false;
                    }
                } else
                {
                    return false;
                }

                 function getCaseDetails(form_data)
                {
                   
                    //await updateCSRFTokenSync();
                    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                    $.ajax({
                            type: "POST",
                            url: "<?php echo base_url('Listing/SingleJudgeAdvance/get_case_details/'); ?>",
                            data: form_data,
                            beforeSend: function () {
                               
                                $("#loader").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                            },
                              success: function (data) {
                              // console.log(data);
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
                }
    });

   


$(document).on("click","input[name=savebutton]",function()
{
    // var CSRF_TOKEN = 'CSRF_TOKEN';
    // var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
    // updateCSRFToken();
    
   

   if(!$('#advance_list_date').length || $('#advance_list_date :selected').val()==''){
       alert("Select Advance List Date!");
       return false;
   }
   var advance_list_date=$('#advance_list_date :selected').val();
   checkIfPublishedSingleJudge(advance_list_date);

  
    
});

 function checkIfPublishedSingleJudge(advance_list_date)
{
    //await updateCSRFTokenSync();
    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
    $.ajax({
        type: 'POST',
       
        url: "<?php echo base_url('Listing/SingleJudgeAdvance/checkIfPublishedSingleJudge'); ?>",
        data: {date:$('#advance_list_date :selected').val(),CSRF_TOKEN: CSRF_TOKEN_VALUE}
    })
    .done(function(response)
    {
        //alert(response.msg);
        
        if(response.msg==0){
            var dno = document.getElementById('fil_hd').value;
            var listingDate = $('#advance_list_date :selected').val();
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            //alert(listingDate);
            saveData(dno, listingDate);
        
    } else {
       // updateCSRFToken();
        alert("This Part is Printed");
    }
    
    })
    .fail(function(){
        alert("Error, Please Contact Server-Room");
    });
}

async function saveData(dno, listingDate)
{
    await updateCSRFTokenSync();
    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
    $.ajax({
            
            type: 'POST', 
            url: "<?php echo base_url('Listing/SingleJudgeAdvance/saveCaseToAdvanceList'); ?>",
            data: { 
                    dno: dno,
                    listing_date: listingDate,
                    CSRF_TOKEN: CSRF_TOKEN_VALUE
                    },
            success: function(response)
            {
                updateCSRFToken();
                
                document.getElementById('report_result').innerHTML = response;

            },
            error: function()
            {
                alert("Error, Please Contact Server-Room");
            }
        });
}


    </script>
