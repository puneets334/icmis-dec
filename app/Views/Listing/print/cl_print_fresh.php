<?= view('header') ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">

                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">FRESH CASES CAUSE LIST (ONLY PUBLISHED LIST)</h3>
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
            <div class="col-md-3">
                <fieldset class="p-2">
                    <label for="sec_id">Mainhead</label>
                    <div class="form-check form-check-inline">
                        <input type="radio" name="mainhead" id="mainheadMisc" value="M" class="form-check-input" title="Miscellaneous" checked>
                        <label class="form-check-label" for="mainheadMisc">Misc</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input type="radio" name="mainhead" id="mainheadRegular" value="F" class="form-check-input" title="Regular">
                        <label class="form-check-label" for="mainheadRegular">Regular</label>
                    </div>
                </fieldset>
            </div>

            <div class="col-md-2">
                <fieldset class="p-2">
                    <label for="sec_id">Listing Dates</label>
                    <select class="ele" name="listing_dts" id="listing_dts">       
                    <option value="-1" selected>SELECT</option>
                     <?php
                     if(count($listing_dates)>0){  
                        foreach($listing_dates as $row){
                        ?>
                                <option value="<?php echo $row['next_dt']; ?>"><?php echo date("d-m-Y", strtotime($row['next_dt'])); ?></option>
                        <?php
                        }
                    }
                    else{
                                    ?>
                        <option value="-1" selected>EMPTY</option>
                        <?php
                    }
                ?>
                    </select>   
                </fieldset>
            </div>

            <div class="col-md-2">
                <fieldset class="p-2">
                    <label for="sec_id">Board Type</label>
                    <select class="form-control" name="board_type" id="board_type">
                        <option value="0">-ALL-</option>
                        <option value="J">Court</option>
                        <option value="S">Single Judge</option>
                        <option value="C">Chamber</option>
                        <option value="R">Registrar</option>
                    </select>
                </fieldset>
            </div>

            <div class="col-md-2">
                <fieldset class="p-2">
                    <label for="sec_id">Court No.</label>
                    <select class="form-control" name="courtno" id="courtno">
                        <option value="0">-ALL-</option>
                        <?php for ($i = 1; $i <= 14; $i++) { ?>
                            <option value="<?= $i; ?>"><?= $i; ?></option>
                        <?php } ?>
                        <option value="21">21 (Registrar)</option>
                        <option value="22">22 (Registrar)</option>
                    </select>
                </fieldset>
            </div>
            <div class="col-md-3">
                <fieldset class="p-2"><br />
                    <input type="button" name="btn1" id="btn1" value="Submit" class="btn btn-primary" />
                </fieldset>
            </div>

            <div class="col-md-3">
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

            <div class="col-md-2">
                <fieldset class="p-2">
                    <label for="sec_id"> MA/CC/CRLM/<br>RP/Contempt/<br>Curative/Jail Petition</label>
                    <select class="form-control" name="ma_cc_crlm" id="ma_cc_crlm">
                           <option value="0">Exclude</option>
                            <option value="1">Include</option>  
                    </select>
                </fieldset>
            </div>

            <div class="col-md-2">
                <fieldset class="p-2">
                    <label for="sec_id">RECEIVED STATUS</label>
                    <select class="form-control" name="received" id="received">
                    <option value="0">ALL</option>
                    <option value="1">Received</option>
                    <option value="2">Not Received</option>
                    </select>
                </fieldset>
            </div>

            <div class="col-md-2">
                <fieldset class="p-2">
                    <label for="sec_id">SCAN STATUS</label>
                    <select class="form-control" name="scn_sts" id="scn_sts">
                    <option value="0">ALL</option>
                    <option value="1">SCANNED</option>
                    <option value="2">Not SCANNED</option>
                    </select>
                </fieldset>
            </div>

            <div class="col-md-2">
                <fieldset class="p-2">
                    <label for="sec_id">Main/Suppl.</label>
                    <select class="form-control" name="main_suppl" id="main_suppl">
                        <option value="0">-ALL-</option>
                        <option value="1">Main</option>
                        <option value="2">Suppl.</option>
                    </select>
                </fieldset>
            </div>

           

            <div class="col-md-3">
                <fieldset class="p-2">
                    <label for="sec_id">Order By</label>
                    <select class="form-control" name="orderby" id="orderby">
                        <option value="0">-ALL-</option>
                        <option value="1">Court Wise</option>
                        <option value="2">Section Wise</option>
                        <option value="3">Court & Item Wise</option>
                    </select>
                </fieldset>
            </div>

            <div class="col-md-3">
                <fieldset class="p-2">
                    <label for="sec_id">Limit By</label>
                    <select class="form-control" name="limit" id="limit">
                        <option value="0">-ALL-</option>
                        <option value="1">10</option>
                    </select>
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

$(document).on("change", "input[name='mainhead']", function() {
        var mainhead = get_mainhead();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $("input[name='CSRF_TOKEN']").val();
        var board_type = $("#board_type").val();
        $.ajax({

            url: '<?php echo base_url('Listing/PrintController/get_cl_print_fresh_mainhead'); ?>',
            type: 'POST',
            cache: false,
            async: true,
            data: {
                mainhead: mainhead,
                board_type: board_type,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            beforeSend: function() {
                $('#res_loader').html('<table widht="100%" align="center"><tr><td class="text-center"><img src="<?php echo base_url('images/load.gif'); ?>"/></td></tr></table>');
                //$('#rs_jg').html('<table widht="100%" align="center"><tr><td><img src="../../images/load.gif"/></td></tr></table>');
            },
            success: function(data, status) {
                updateCSRFToken();
                $('#res_loader').html('');
                if (data != '') {
                    $('#listing_dts').html(data);
                } else {
                    ('#listing_dts').html("<option value='-1' selected>EMPTY</option>");
                }
            },
            error: function(xhr) {
                updateCSRFToken();
                //alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
    });


$(document).on("click","#btn1",function(){
   get_cl_1();
});  

function get_cl_1(){
    var CSRF_TOKEN = 'CSRF_TOKEN';
    var CSRF_TOKEN_VALUE = $("input[name='CSRF_TOKEN']").val();
    var mainhead = get_mainhead();
    var list_dt = $("#listing_dts").val();
    var courtno = $("#courtno").val();
    var board_type = $("#board_type").val();
    var ma_cc_crlm = $("#ma_cc_crlm").val();
    var received = $("#received").val();
    var orderby = $("#orderby").val();
    var sec_id = $("#sec_id").val();
    var main_suppl = $("#main_suppl").val();
    var scn_sts = $("#scn_sts").val();
    var limit = $("#limit").val();
      $.ajax({
            url: 'get_cause_list_fresh',
            cache: false,
            async: true,
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE,limit:limit,list_dt: list_dt, mainhead:mainhead, courtno: courtno, board_type: board_type, ma_cc_crlm:ma_cc_crlm, received:received, orderby:orderby, sec_id:sec_id, main_suppl:main_suppl, scn_sts:scn_sts},
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



function get_mainhead(){
    var mainhead = "";
    $('input[type=radio]').each(function () {           
        if($(this).attr("name")=="mainhead" && this.checked)
        mainhead = $(this).val();
    });
    return mainhead;
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

