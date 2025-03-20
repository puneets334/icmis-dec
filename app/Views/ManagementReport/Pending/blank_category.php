<?= view('header') ?>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Reports</h3>
                            </div>
                            <div class="col-sm-2">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header p-2" style="background-color: #fff; border-bottom:none;">
                                    <h4 class="basic_heading">No Subject Category</h4>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="active tab-pane">
                                            <?php echo form_open();
                                            csrf_token();
                                            ?>
                                            <div id="dv_content1">
                                                <div class="row">
                                                    <div class="col-md-2"></div>
                                                    <div class="col-md-1 mt-1">
                                                        <?php field_mainhead(); ?>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <label for="">Board Type</label>
                                                        <select class="form-control form-selectele" name="board_type" id="board_type">
                                                        <option value="0">-ALL-</option>
                                                        <option value="J">Court</option>
                                                        <option value="S">Single Judge</option>
                                                        <option value="C">Chamber</option>
                                                        <option value="R">Registrar</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label for="">Reg./Unreg.</label>
                                                        <select class="form-control" id="reg_unreg" name="reg_unreg">
                                                            <option value="0">-ALL-</option>        
                                                            <option value="1">Reg.</option>
                                                            <option value="2">Unreg.</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label for="">Purpose of Listing</label>
                                                        <select class="form-control" id="listorder" name="listorder">
                                                            <option value="0">Select</option>
                                                            <?php if(!empty($purpose_list)) { ?>
                                                            <?php foreach($purpose_list as $purpose){ ?>
                                                                <?php $temp_check = $lo =" ";
                                                                if ($lo == $purpose["code"])
                                                                    echo '<option value="' . $purpose["code"] . '" selected="selected" ' . $temp_check . '>' . $purpose["lp"] . '</option>';
                                                                else
                                                                    echo '<option value="' . $purpose["code"] . '"' . $temp_check . '>' . $purpose["lp"] . '</option>';
                                                                ?>
                                                                <?php }
                                                            } ?>   
                                                            
                                                        </select>
                                                    </div>
                                                    <div class="col-md-2 mt-26">
                                                        <input type="button" class="btn btn-primary quick-btn" value="Submit" id="btnSubmit"/>
                                                    </div>
                                                </div>
                                                <div id="res_loader"></div>

                                                <div id="dv_res1"></div>
                                            </div>
                                            <?php echo form_close(); ?>
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
<script>
   
$(document).on("click","#btnSubmit",function(){
   get_cl_1();
});  

function get_cl_1(){
    var CSRF_TOKEN_VALUE = $("input[name='CSRF_TOKEN']").val();
    var mainhead = get_mainhead();    
    var board_type = $("#board_type").val();    
    var reg_unreg = $("#reg_unreg").val();
    var listorder = $("#listorder").val();
    $.ajax({
            url: '<?php echo base_url('ManagementReports/Pending/blank_category_get');?>',   
            cache: false,
            async: true,
            data: {mainhead:mainhead, board_type: board_type, reg_unreg:reg_unreg, listorder:listorder, CSRF_TOKEN:CSRF_TOKEN_VALUE},
            beforeSend:function(){
               $("#dv_res1").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='../../images/load.gif'></div>");
            },
            type: 'POST',
            success: function(data, status) {                
               $('#dv_res1').html(data);
               updateCSRFToken();            
            },
            error: function(xhr) {
                alert("Error: " + xhr.status + " " + xhr.statusText);
                updateCSRFToken(); 
            }
        });
}



function get_mainhead(){
    var mainhead = "";
    $('input[type=radio]').each(function () {           
        if($(this).attr("name")=="mainhead" && this.checked)
        mainhead = $(this).val();
    });
    return mainhead;
} 

$(document).on("click","#prnnt1",function(){
    var prtContent = $("#prnnt").html();
    var temp_str=prtContent;
    var WinPrint = window.open('','','left=100,top=0,align=center,width=800,height=1200,menubar=1,toolbar=1,scrollbars=1,status=1,autosize=1');
    WinPrint.document.write(temp_str);
    WinPrint.document.close();
    WinPrint.focus();
    WinPrint.print();
});

</script>