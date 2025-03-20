<style>
    fieldset{
        padding:5px !important; background-color:#F5FAFF !important; border:1px solid #0083FF !important; 
    }
    legend{
        background-color:#E2F1FF !important; width:100% !important; text-align:center !important; border:1px solid #0083FF !important; font-weight: bold !important;
    }
    .table3, .subct2, .subct3, .subct4, #res_on_off, #resh_from_txt{
        display:none;
    }
    .toggle_btn{
        text-align: left; color: #00cc99; font-size:18px; font-weight: bold; cursor: pointer;
    }
</style>
<?= view('header') ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title"> CAUSE LIST DROP NOTE PRINT </h3>
                            </div>
                        </div>
                    </div>
                    <!-- Main content start -->
                    <div class="col-md-12">
                    <div class="card-body">
                    <form method="post">
                        <?= csrf_field() ?>
                        <div id="dv_content1">
                            <div style="text-align: center">
                                <table border="0" align="center">
                                <tr valign="middle">
                                    <td id="id_mf">
                                        <fieldset>
                                            <legend>Mainhead</legend>
                                            <input type="radio" name="mainhead" id="mainhead" value="M" title="Miscellaneous" checked="checked" >M&nbsp;
                                            <input type="radio" name="mainhead" id="mainhead" value="F" title="Regular">R&nbsp;
                                        </fieldset>
                                    </td>
                                    <td id="id_dts">
                                        <fieldset>
                                                <legend>Listing Dates</legend>
                                                <select class="ele" name="listing_dts" id="listing_dts">
                                                    <option value="-1" selected>SELECT</option>
                                                    <?php if(!empty($judge_list)) { ?>
                                                    <?php foreach($listing_dates as $date){?>
                                                        <option value="<?php echo $date['next_dt']; ?>"><?php echo date("d-m-Y", strtotime($date['next_dt'])); ?></option>
                                                        <?php }
                                                    } else { ?>
                                                        <option value="-1" selected>EMPTY</option>
                                                    <?php } ?>
                                                </select>
                                        </fieldset>
                                    </td>
                                    <td>
                                    <fieldset>
                                        <legend><b>Board Type</b></legend>
                                        <select class="ele" name="board_type" id="board_type">
                                            <option value="0">-ALL-</option>
                                            <option value="J">Court</option>
                                            <option value="S">Single Judge</option>
                                            <option value="C">Chamber</option>
                                            <option value="R">Registrar</option>
                                        </select>
                                        </fieldset>
                                    </td>
                                    <td id="rs_jg">
                                        <fieldset>
                                            <legend>Benches</legend>
                                            <select class="ele" name="jud_ros" id="jud_ros">
                                                <option value="-1" selected>SELECT</option>
                                                <?php if(!empty($judge_list)) { ?>
                                                    <?php foreach($judge_list as $judge) { ?>
                                                        <option value="<?php echo $judge["judges"]."|".$judge["roster_id"]; ?>" ><?php echo $judge['jnm']; ?></option>
                                                    <?php }
                                                } else { ?>
                                                    <option value="-1" selected>EMPTY</option>
                                                <?php } ?>
                                            </select>
                                        </fieldset>
                                    </td>
                                    <td id="rs_partno">
                                        <fieldset>
                                            <legend>Part No.</legend>
                                            <select class="ele" name="part_no" id="part_no">
                                                <option value="-1" selected>EMPTY</option>
                                            </select>
                                        </fieldset>
                                    </td>
                                    <td id="rs_actio_btn1">
                                        <fieldset>
                                            <legend>Action</legend>
                                            <input type="button" name="btn1" id="btn1" value="Submit"/>
                                        </fieldset>
                                    </td>
                                </tr>
                                </table>
                            </div>
                            <div id="dv_res1" style="text-align: left;"></div>
                            <div id="dv_res2" style="text-align: left;"></div>
                        </div>
                    </form>
                    </div>
                    </div>
                    <!-- Main content end -->

                </div> <!--end dv_content1-->
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>

<script>
    $(document).on("change","input[name='mainhead']",function(){
        var mainhead = get_mainhead();
        var board_type = $("#board_type").val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $.ajax({
                url: "<?php echo base_url('Listing/CaseDrop/get_cl_print_mainhead'); ?>",
                cache: false,
                async: true,
                data: {mainhead:mainhead, board_type: board_type, CSRF_TOKEN:CSRF_TOKEN_VALUE},
                type: 'POST',
                success: function(data, status) {
                    updateCSRFToken();
                $('#listing_dts').html(data);
                $('#jud_ros').html("<option value='-1' selected>EMPTY</option>");
                $('#part_no').html("<option value='-1' selected>EMPTY</option>");
                },
                error: function(xhr) {
                    updateCSRFToken();
                    alert("Error: " + xhr.status + " " + xhr.statusText);
                }
            });
    });

    $(document).on("change","#listing_dts",function(){
        var mainhead = get_mainhead();
        var list_dt = $("#listing_dts").val();
        var board_type = $("#board_type").val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $.ajax({
                url: "<?php echo base_url('Listing/CaseDrop/get_cl_print_benches'); ?>",
                cache: false,
                async: true,
                data: {list_dt: list_dt, mainhead:mainhead, board_type:board_type, CSRF_TOKEN:CSRF_TOKEN_VALUE},
                type: 'POST',
                success: function(data, status) {
                    updateCSRFToken();
                $('#jud_ros').html(data);
                },
                error: function(xhr) {
                    updateCSRFToken();
                    alert("Error: " + xhr.status + " " + xhr.statusText);
                }
            });
    });

    $(document).on("change","#jud_ros",function(){
        var mainhead = get_mainhead();
        var list_dt = $("#listing_dts").val();   
        var jud_ros = $("#jud_ros").val();
        var board_type = $("#board_type").val();    
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $.ajax({
                url: "<?php echo base_url('Listing/CaseDrop/get_cl_print_partno'); ?>",
                cache: false,
                async: true,
                data: {list_dt: list_dt, mainhead:mainhead,jud_ros:jud_ros,board_type:board_type, CSRF_TOKEN:CSRF_TOKEN_VALUE},
                beforeSend:function(){
                },
                type: 'POST',
                success: function(data, status) {
                    updateCSRFToken();
                $('#part_no').html(data);
                },
                error: function(xhr) {
                    updateCSRFToken();
                    alert("Error: " + xhr.status + " " + xhr.statusText);
                }
            });
    });



    $(document).on("change","#board_type",function(){
        var mainhead = get_mainhead();
        var list_dt = $("#listing_dts").val();
        var board_type = $("#board_type").val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $.ajax({
                url: "<?php echo base_url('Listing/CaseDrop/get_cl_print_benches'); ?>",
                cache: false,
                async: true,
                data: {list_dt: list_dt, mainhead:mainhead, board_type:board_type, CSRF_TOKEN:CSRF_TOKEN_VALUE},
                beforeSend:function(){
                },
                type: 'POST',
                success: function(data, status) {
                    updateCSRFToken();
                $('#jud_ros').html(data);
                },
                error: function(xhr) {
                    updateCSRFToken();
                    alert("Error: " + xhr.status + " " + xhr.statusText);
                }
            });
    });

    $(document).on("click","#btn1",function(){
        var mainhead = get_mainhead();
        var list_dt = $("#listing_dts").val();
        var jud_ros = $("#jud_ros").val();
        var part_no = $("#part_no").val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        if(list_dt == "-1"){ return false; }
        if(jud_ros == "-1"){ return false; }
        if(part_no == "-1"){ return false; }
        $.ajax({
                url: "<?php echo base_url('Listing/CaseDrop/note_field'); ?>",
                cache: false,
                async: true,
                data: {list_dt: list_dt, mainhead:mainhead,jud_ros:jud_ros,part_no:part_no, CSRF_TOKEN:CSRF_TOKEN_VALUE},
                beforeSend:function(){
                    $("#dv_res1").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
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
    });

    function get_mainhead(){
        var mainhead = "";
        $('input[type=radio]').each(function () {
            if($(this).attr("name")=="mainhead" && this.checked)
            mainhead = $(this).val();
        });
        return mainhead;
    }

    $(document).on("click","#prnnt1",function() {    
        var prtContent = $("#prnnt").html();
        var temp_str=prtContent;
        var WinPrint = window.open('','','left=100,top=0,align=center,width=800,height=1200,menubar=1,toolbar=1,scrollbars=1,status=1');
        WinPrint.document.write(temp_str);
        WinPrint.document.close();
        WinPrint.focus();
        WinPrint.print();
    });
    
</script>