<?= view('header') ?>
<style>
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
</style>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">

                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Cases Listed Using Update Hearding Module</h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                    <form method="post">
                        <?= csrf_field() ?>
                        <div id="dv_content1">
                            <div class="">
                                <div class="row justify-content-center">
                                    <div class="col-md-3">
                                        <!--<fieldset class="border p-2">
                                            <legend class="w-auto">Date Type</legend>
                                            <select id="datetype" name="datetype" class="form-control">
                                                <option value="1" selected>Cause List Date</option>
                                                <option value="2">Entry Date</option>
                                            </select>
                                        </fieldset>-->
                                        <label for="w-auto" class="text-left">Date Type</label>
                                        <select id="datetype" name="datetype" class="form-control">
                                            <option value="1" selected>Cause List Date</option>
                                            <option value="2">Entry Date</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <!--<fieldset class="border p-2">
                                            <legend class="w-auto">Date</legend>
                                            <input type="text" class="form-control dtp" name="listing_dts" id="listing_dts" value="<?= date('d-m-Y'); ?>" />
                                        </fieldset>-->
                                        <label class="text-left">Date</label>
                                        <input type="text" class="form-control dtp" name="listing_dts" id="listing_dts" value="<?= date('d-m-Y'); ?>" />
                                    </div>
                                    <div class="col-md-3 pt-4">
                                        <!--<fieldset class="border p-2">
                                            <legend class="w-auto">Action</legend>
                                            <input type="button" name="btn1" id="btn1" class="btn btn-primary" value="Submit" />
                                        </fieldset>-->
                                        <input type="button" name="btn1" id="btn1" class="btn btn-primary" value="Submit" />
                                    </div>
                                </div>
                            </div>
                            <div id="res_loader"></div>
                        </div>
                        <div id="dv_res1"></div>
                    </form>
                    </div>
            </div>
        </div>
</section>


<script>
    $(function() {
        $("#listing_dts").datepicker();
    });

    $(document).on("click", "#btn1", function() {
       
        get_cl_1();
    });

    function get_cl_1() {

        //var mainhead = get_mainhead();
        var list_dt = $("#listing_dts").val();
        var datetype = $("#datetype").val();
        //var board_type = $("#board_type").val();  
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
           
            url: "<?php echo base_url('Listing/UpdateHeardt/update_heardt_report_get/'); ?>",
            cache: false,
            async: true,
            data: {
                list_dt: list_dt,
                datetype: datetype,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            beforeSend: function() {
                //$('#dv_res1').html('<table widht="100%" align="center"><tr><td><img src="../../images/load.gif"/></td></tr></table>');
                $("#dv_res1").html('<div style="margin:0 auto;margin-top:20px;width:5%"><img src="' + base_url + '/images/load.gif"/></div>'); 
            },
            type: 'POST',
            success: function(data, status) {
                updateCSRFToken();
                $('#dv_res1').html(data);
                if (data)
                    $('#res_on_off').show();
            },
            error: function(xhr) {
                updateCSRFToken();
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
    }

    function get_mainhead() {
        var mainhead = "";
        $('input[type=radio]').each(function() {
            if ($(this).attr("name") == "mainhead" && this.checked)
                mainhead = $(this).val();
        });
        return mainhead;
    }

    //function CallPrint(){
    $(document).on("click", "#prnnt1", function() {
        var prtContent = $("#prnnt").html();
        var mainhead = get_mainhead();
        var list_dt = $("#listing_dts").val();
        var jud_ros = $("#jud_ros").val();
        var part_no = $("#part_no").val();
        var temp_str = prtContent;
        var WinPrint = window.open('', '', 'left=100,top=0,align=center,width=800,height=1200,menubar=1,toolbar=1,scrollbars=1,status=1');
        WinPrint.document.write("<style> .bk_out {  display:none; } </style>" + prtContent.innerHTML);
        WinPrint.document.write(temp_str);
        WinPrint.document.close();
        WinPrint.focus();
        WinPrint.print();
    });
</script>