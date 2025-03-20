<?= view('header') ?>

<link rel="stylesheet" href="<?php echo base_url('Ajaxcalls/menu_assign/menu_assign.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('Ajaxcalls/menu_assign/style.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('Ajaxcalls/menu_assign/all.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('assets/vendor/fontawesome-free/css/all.min.css'); ?>">

<style> 
  .card-title {
            text-align: center;
            margin-bottom: 20px;
        }

        th{
            font-weight: bold;
        }

        #datatavles {
            border: 1px solid black;
            border-collapse: collapse;  
        }
</style>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">

                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Master Management >> Case Block for Loose Doc</h3>
                            </div>
                            <div class="col-sm-2"> </div>
                        </div>
                    </div>
                    <br /><br />
                    <input type="hidden" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>">
                    <!--start menu programming-->
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12"> <!-- Right Part -->
                                <div class="form-div">
                                    <div class="d-block text-center">

                                     <!-- Main content -->
                                   
                                        <div id="">
                                            <div class="row" style="justify-content: center;">
                                            <div class="card-title">
                                                <h2>CASE BLOCK TO RECEIVE MISC. DOCS</h2>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="table-responsive mt-3">
                                                <table class="table-bordered table table-striped " width="100%">
                                                <tbody>
                                                    <tr>
                                                        <td><label for="dno">Diary No.</label></td>
                                                        <td><input type="text" id="dno" class="form-control" name="dno" maxlength="6"/></td>
                                                        <td><label for="dyr">Year</label></td>
                                                        <td><input type="text" id="dyr" class="form-control" name="dyr" maxlength="4"/></td>
                                                        <td><label for="bl_reason">Reason to Block the Case</label></td>
                                                        <td><input type="text" id="bl_reason" class="form-control"  name="bl_reason" maxlength="100" onblur="remove_apos(this.value,this.id)"/></td>
                                                    </tr>     
                                                    <tr>
                                                        <td><button type="submit" class="btn btn-primary" id="btnMain">Add New</button></td>
                                                        <!-- <td><button type="button" class="btn btn-primary" value="Update" id="btnUp">Update</button></td> -->
                                                        <!-- <td> <input type="button" class="btn btn-success" value="Cancel" id="btnCan"/></td> -->
                                                    </tr>

                                                </tbody>
                                            </table>
                                                </div>
                                            </div>
                                            <div class="cl_center">
                                            <button type="button" class="btn btn-primary" id="btn_pnt" value="Print">Print</button>
                                                <!-- <input type="button" name="btn_pnt" id="btn_pnt" value="Print"/> -->
                                            </div>
                                            <br>
                                            <div class="add_result"></div>
                                            <div id="result_main">
                                            <?php if (!empty($cases)): ?>
                                                <div class="table-responsive">
                                                <table class="table table-bordered" width="100%" id="datatavles">
                                                    <tr>
                                                        <th>SNo.</th>
                                                        <th>Diary No</th>
                                                        <th>Parties</th>
                                                        <th>Reason to Block</th>
                                                        <th>Entered by</th>
                                                        <th>Section</th>
                                                        <th>Date</th>
                                                        <th>Action</th>
                                                    </tr>
                                                    <?php $sno = 1; foreach ($cases as $case): ?>
                                                        <tr>
                                                            <td><?= $sno ?></td>
                                                            <td><?= substr($case['diary_no'], 0, -4) . '/' . substr($case['diary_no'], -4) ?></td>
                                                            <td><?= $case['pet_name'] . ' <b>V/S</b> ' . $case['res_name'] ?></td>
                                                            <td><?= $case['reason_blk'] ?></td>
                                                            <td><?=   $case['username'] ?></td>
                                                            <td><?=  $case['section_name'] ?></td>
                                                            <td><?= date('d-m-Y h:i:s A', strtotime($case['ent_dt'])) ?></td>
                                                            <td><input type="button" id="btnDelete<?php echo $case['id']; ?>" value="Remove" /></td>
                                                        </tr>
                                                        <?php $sno++; endforeach; ?>
                                                </table>
                                                </div>
                                            <?php else: ?>
                                                <div class="sorry"><h3 style="color:red">SORRY, NO RECORD FOUND!!!</h3></div>
                                            <?php endif; ?>
                                            </div>
                                        </div>
                                   
 
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>

<script src="<?= base_url('/Ajaxcalls/menu_assign/menu_assign.js') ?>"></script>

<script>
   
  
$(document).ready(function(){
    $("#btnMain").click(function(){
        
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        var low = $("#dno").val().trim();
        var up = $("#dyr").val().trim();
        var reg123 = new RegExp('^[0-9]+$');
        if(!reg123.test(low)){
            alert("Please Enter Numeric Value Only");
            $("#dno").val('');
            return false;
        }
        if(!reg123.test(up)){
            alert("Please Enter Numeric Value Only");
            $("#dyr").val('');
            return false;
        }
        if($("#bl_reason").val()==""){
            alert("Please Enter Reason");
            $("#bl_reason").focus();
            return false;
        }
        
        $.ajax({
            type: 'POST',
            url: baseURL +"/MasterManagement/CaseBlockLooseDoc/SaveCaseBlock",
            async: false,
            data:{dno:low,dyr:up,reason:$("#bl_reason").val()},
            headers: {
            'X-CSRF-Token': CSRF_TOKEN_VALUE  
             },
        })
        .done(function(msg){
            updateCSRFToken();
            var msg2 = msg.split('~');
            if(msg2[0] == 1){
            //     function getCaseBlockss(){
            //     updateCSRFToken();
            //     var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                $(".add_result").css("display","block");
                $(".add_result").css("color","green");
                $(".add_result").html(msg2[1]);
                $("#dno").val("");
                $("#dyr").val("");
                $("#bl_reason").val("");
            //     $(".add_result").slideUp(3000);
            //     //$("#result_main").reload();
            //     $.ajax({
            //         type: 'POST',
            //         url: baseURL +"/MasterManagement/CaseBlockLooseDoc/getCaseBlock",
            //         headers: {
            //         'X-CSRF-Token': CSRF_TOKEN_VALUE  
            //         },
            //         data:{mat:2}
            //     })
            //     .done(function(msg_new){
            //         $("#result_main").html(msg_new);
            //     })
            //     .fail(function(){
            //         alert("ERROR, Please Contact Server Room"); 
            //     });

            // }
            
            alert('RECORD INSERTED SUCCESSFULLY');
            setTimeout(() => window.location.reload(), 2000)
            }
            else{
                updateCSRFToken();
                $(".add_result").css("display","block");
                $(".add_result").css("color","red");
                $(".add_result").html(msg);
            }
            
        })
        .fail(function(){
            alert("ERROR, Please Contact Server Room");
            updateCSRFToken();
        }); 
    });
});

$(document).on("click","[id^='btnDelete']",function(){
    var num = this.id.split('btnDelete');
    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

    if(confirm("ARE YOU SURE TO REMOVE THIS RECORD") == true){
        $.ajax({
            type: 'POST',
            url: baseURL +"/MasterManagement/CaseBlockLooseDoc/getdeleteCaseBlock",
            data:{id:num[1]},
            headers: {
                    'X-CSRF-Token': CSRF_TOKEN_VALUE  
                    },
        })
        .done(function(msg){
            var msg2 = msg.split('~');
            if(msg2[0] == 1){
                   updateCSRFToken();
                $(".add_result").css("display","block");
                $(".add_result").css("color","#90C695");
                $(".add_result").html(msg2[1]);
                $("#dno").val("");
                $("#dyr").val("");
                $("#bl_reason").val("");
                $(".add_result").slideUp(3000);
                setTimeout(() => window.location.reload(), 4000);
           
            }
            else{
                $(".add_result").css("display","block");
                $(".add_result").css("color","red");
                $(".add_result").html(msg);
            }
        })
        .fail(function(){
            updateCSRFToken();
            alert("ERROR, Please Contact Server Room");
        });
    }
});

function remove_apos(value,id){
    var string = value.replace("'","");
    string = string.replace("#","No");
    string = string.replace("&","and");
    $("#"+id).val(string);
}

$(document).on('click','#btn_pnt',function(){
    var prtContent = document.getElementById('result_main');
    var WinPrint = window.open('','','letf=100,top=0,width=800,height=1200,toolbar=1,scrollbars=1,status=1,menubar=1');
    WinPrint.document.write(prtContent.innerHTML);
    WinPrint.document.close();
    WinPrint.focus();
    WinPrint.print();
    //document.getElementById('btn_pnt').style.display= 'block';
});  

    
 
</script>
