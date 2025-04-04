<?= view('header') ?>
<style>
    #newb {
        position: fixed;
        padding: 12px;
        left: 50%;
        top: 50%;
        display: none;
        color: black;
        background-color: #D3D3D3;
        border: 2px solid lightslategrey;
        height: 100%;
    }

    #newcs {
        position: fixed;
        padding: 12px;
        left: 50%;
        top: 50%;
        display: none;
        color: black;
        background-color: #D3D3D3;
        border: 2px solid lightslategrey;
        height: 100%;
    }

    #overlay {
        background-color: #000;
        opacity: 0.7;
        filter: alpha(opacity=70);
        position: fixed;
        top: 0px;
        left: 0px;
        width: 100%;
        height: 100%;
    }

    fieldset {
        padding: 5px;
        background-color: #F5FAFF;
        border: 1px solid #0083FF;
    }

    legend {
        background-color: #E2F1FF;
        width: 100%;
        text-align: center;
        border: 1px solid #0083FF;
        font-weight: bold;
    }

    .table3,
    .subct2,
    .subct3,
    .subct4,
    #res_on_off,
    #resh_from_txt {
        display: none;
    }

    .toggle_btn {
        text-align: left;
        color: #00cc99;
        font-size: 18px;
        font-weight: bold;
        cursor: pointer;
    }

    div,
    table,
    tr,
    td {
        font-size: 10px;
    }

    .tooltip {
        position: relative;
        display: inline-block;
        border-bottom: 1px dotted black;
    }

    .tooltip .tooltiptext {
        visibility: hidden;
        width: 120px;
        background-color: #d0f1af;
        color: #aa810a;
        text-align: center;
        border-radius: 6px;
        padding: 5px 0;
        /* Position the tooltip */
        position: absolute;
        z-index: 1;
    }

    .tooltip:hover .tooltiptext {
        visibility: visible;
    }
</style>
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
                                    <h4 class="basic_heading">Cases Verified By Monitoring Team</h4>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="active tab-pane">
                                            <form method="POST">
                                                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" id="csrf_token" />
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <label for="">To be Listed On</label>
                                                        <?php

                                                        $cur_ddt = date('Y-m-d');
                                                        $next_court_work_day = date("d-m-Y", strtotime(chksDate($cur_ddt)));
                                                        ?>
                                                        <input type="text" class="dtp" name='ldates' id='ldates' value="<?php echo $next_court_work_day; ?>" readonly />
                                                    </div>

                                                </div>

                                                <div class="col-md-4">
                                                    <label for=""></label>
                                                    <!-- <input type="button" name="btn_ft" id="btn_ft" value="Submit" onclick="sub_dt()" class="btn btn-primary mt-4" /> -->
                                                    <input type="button" name="btn1" id="btn1" value="Submit" />
                                                </div>
                                        </div>


                                        </form>
                                    </div>
                                    <div id="res_loader"></div>
                                </div>

                                <div id="dv_res1"></div>
                                <div id="overlay" style="display:none;">&nbsp;</div>

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
//    $(document).on("focus",".dtp",function()
// {   
// $('.dtp').datepicker({dateFormat: 'dd-mm-yy', changeMonth : true,changeYear  : true,yearRange : '1950:2050'
// });
// });      
$(document).on("click","#btn1",function(){
   get_cl_1();
});  

function get_cl_1(){
    var ldates = $("#ldates").val();   
    var CSRF_TOKEN = 'CSRF_TOKEN';
    var csrf = $("input[name='CSRF_TOKEN']").val();     
      $.ajax({
            url: "<?php echo base_url('Listing/Report/verify_detail_report_da_wise'); ?>",
            cache: false,
            async: true,
            data: {ldates: ldates ,CSRF_TOKEN: csrf},
            beforeSend:function(){
              
               $('#dv_res1').html('<table width="100%" style="margin: 0 auto;"><tr><td style="text-align: center;"><img src="../../images/load.gif"/></td></tr></table>');
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