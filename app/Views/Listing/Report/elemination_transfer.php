<?= view('header') ?>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">ELEMINATION LIST</h3>
                            </div>
                            <?= view('Filing/filing_filter_buttons'); ?>
                        </div>
                    </div>


                    <div class="card-body">
    <?php
    echo form_open();
    csrf_token();
    ?>
    <div id="dv_content1">
        <div>
            <div class="row">

               
                <!-- Board Type Field -->
                <div class="col-md-3 mt-2">
                    <label for="board_type" class="form-label">Board Type</label>
                    <select class="form-control" name="board_type" id="board_type">
                        <option value="0">-ALL-</option>
                        <option value="J">Court</option>
                    </select>
                </div>

                 <!-- Listing Date Field -->
                 <div class="col-md-3 mt-2">
                    <fieldset class="p-3">
                        <legend class="w-auto">
                            Date
                        </legend>
                        <?php
                        // Generate next court work day
                        $cur_ddt = date('Y-m-d', strtotime(' +1 day'));
                        $next_court_work_day = date("d-m-Y", strtotime(chksDate($cur_ddt)));
                        ?>
                        <input type="text" size="10" class="form-control dtp" name="ldates" id="ldates" value="<?php echo $next_court_work_day; ?>" readonly />
                    </fieldset>
                    
                </div>


                <!-- Action Button -->
                <div class="col-md-2 mt-2">
                    <?php field_action_btn1(); ?>
                </div>
                <div class="col-md-2">
                <fieldset class="p-2">
                <label for="sec_id">Section Name</label>
                    <select class="form-control" name="sec_id" id="sec_id">
                        <option value="0">-ALL-</option>
                        <?php foreach ($section_name as $ro_u) { ?>
                            <option value="<?= $ro_u['id']; ?>" >
                                <?= $ro_u['section_name']; ?>
                            </option>
                        <?php } ?>
                    </select>
                </fieldset>
            </div>
            </div>

            <!-- Loading indicator -->
            <div id="res_loader" class="mt-3"></div>
        </div>

        <!-- Results Container -->
        <div id="dv_res1" class="mt-3"></div>
    </div>
    <?php echo form_close(); ?>
</div>


                </div>
            </div>
        </div>
    </div>
</section>

<script>
$(document).on("focus",".dtp",function(){   
$('.dtp').datepicker({dateFormat: 'dd-mm-yy', changeMonth : true,changeYear  : true,yearRange : '1950:2050'
});
});      

$(document).on("click","#btn1",function(){
    get_cl_1();
});  

function get_cl_1(){
    var CSRF_TOKEN = 'CSRF_TOKEN';
    var CSRF_TOKEN_VALUE = $("input[name='CSRF_TOKEN']").val();
    var ldates = $("#ldates").val();    
    var board_type = $("#board_type").val();        
    var sec_id = $("#sec_id").val();
    $.ajax({
            // url: "<?php echo base_url('listing/report/get_elemination_transfer');?>",
            url: "get_elemination_transfer",
            cache: false,
            async: true,
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE,list_dt: ldates, board_type: board_type, sec_id:sec_id},
            beforeSend:function(){
               $('#dv_res1').html('<table widht="100%" align="center"><tr><td><img src="../../images/load.gif"/></td></tr></table>');
            },
            type: 'POST',
            success: function(data, status) {   
                updateCSRFToken();              
               $('#dv_res1').html(data);           
            },
            error: function(xhr) {
                updateCSRFToken(); 
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
        updateCSRFToken(); 
}    
//function CallPrint(){
$(document).on("click","#prnnt1",function(){    
var prtContent = $("#prnnt").html();
var temp_str=prtContent;
var WinPrint = window.open('','','left=100,top=0,align=center,width=800,height=1200,menubar=1,toolbar=1,scrollbars=1,status=1');
 WinPrint.document.write(temp_str);
 WinPrint.document.close();
 WinPrint.focus();
 WinPrint.print();
});
</script>