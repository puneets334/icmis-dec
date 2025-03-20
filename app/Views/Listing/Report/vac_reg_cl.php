<?= view('header') ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">

                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">VACATION REGISTRAR CAUSE LIST</h3>
                            </div>
                           
                        </div>
                    </div>
                    <?php
                    echo form_open();
                    csrf_token();
                    ?>
                     <div id="dv_content1" class="container mt-4">

<div class="text-center">
    <form>
        <div class="row mb-2">
            <div class="col-md-2">
                <fieldset class="p-2">
                    <label for="sec_id">Date</label>
                    <?php
        $cur_ddt = date('Y-m-d', strtotime(' +1 day'));
        $next_court_work_day = date("d-m-Y", strtotime(chksDate($cur_ddt)));    
        ?>
        <input type="text" size="10" class="dtp" name='ldates' id='ldates' value="<?php echo $next_court_work_day; ?>" readonly />
                </fieldset>
            </div>

            <div class="col-md-2">
                <fieldset class="p-2">
                    <label for="sec_id">Registrar</label>
                    <legend></legend>
                    <select class="form-control" name="reg_code" id="reg_code">
                        <option value="0">-ALL-</option>
                        <?php
                        
                        foreach($registeredName as $row){
                            ?>
                            <option value="<?php echo $row['jcode']; ?>" > <?php echo $row['first_name'].' '.$row['sur_name'].', '.$row['jname']; ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </fieldset>
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
            <div class="col-md-3">
                <fieldset class="p-2"><br />
                    <input type="button" name="btn1" id="btn1" value="Submit" class="btn btn-primary" />
                </fieldset>
            </div>

            

            
        </div>

        <div id="res_loader" class="text-center"></div>
    </form>
</div>

<div id="dv_res1"></div>
</div>


                    <?php echo form_close(); ?>

                </div>
            </div>
        </div>
</section>
        
    
<script>


$(document).on("focus", ".dtp", function() {
        $('.dtp').datepicker({
            dateFormat: 'dd-mm-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: '1950:2050'
        });
    });

$(document).on("click","#btn1",function(){
   get_cl_1();
});  

function get_cl_1(){
    var CSRF_TOKEN = 'CSRF_TOKEN';
    var CSRF_TOKEN_VALUE = $("input[name='CSRF_TOKEN']").val();
    var ldates = $("#ldates").val();
    var reg_code = $("#reg_code").val();
    var sec_id = $("#sec_id").val();
      $.ajax({
            url: 'vac_reg_cl_get',
            cache: false,
            async: true,
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE,ldates:ldates,reg_code:reg_code, sec_id: sec_id},
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

