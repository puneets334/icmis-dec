<?= view('header') ?>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <p id="show_error"></p> <!-- This Segment Displays The Validation Rule -->
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
                                    <h4 class="basic_heading">Cases Listed/To be listed in future dates</h4>
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
                                                    <div class="col-md-1.1">
                                                        <label for="grp_hv">List Type</label>
                                                        <div class="input-group">
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="radio" name="mainhead_select" id="radio_m" value="M" title="Miscellaneous" checked>
                                                                <label class="form-check-label" for="mainheadM">Misc.</label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="radio" name="mainhead_select" id="radio_f" value="F" title="Regular">
                                                                <label class="form-check-label" for="mainheadF">Regular</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    

                                                    <div class="col-md-2">
                                                        <label for="">List Date (upto)</label>
                                                        <input type="text" class="form-control bg-white list_date dtp" aria-describedby="list_date_addon" placeholder="Date..." readonly>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label for="">Sort By</label>
                                                        <select class="form-control sortby" aria-describedby="sortby_addon">
                                                            <option value="2">As per Paper Book requirement</option>
                                                            <option value="1">As per Listing Priority</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label for="">Limit</label>
                                                        <input type="number" class="form-control bg-white limit_number" aria-describedby="limit_number_addon" value="2000">
                                                    </div>
                                                    <div class="col-md-2 mt-26">
                                                        <!--<input type="button" class="btn btn-primary quick-btn" value="Search" id="btn_search" name="btn_search" />-->
                                                        <button id="btn_search" name="btn_search" type="button" class="btn btn-primary quick-btn btn-block">Search</button>
                                                    </div>
                                                </div>
                                                <div id="res_loader"></div>

                                                <div id="result"></div>
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
    $(document).on("click", "#btn_search", function() {
        $("#result").html(""); $('#show_error').html("");
        var list_date = $(".list_date").val();
        var sortby = $(".sortby").val();
        var limit_number = $(".limit_number").val();

        if ($("#radio_m").is(':checked')) {
            var mainhead = $("#radio_m").val();
        }
        if ($("#radio_f").is(':checked')) {
            var mainhead = $("#radio_f").val();
        }

        if (list_date.length == 0) {
            $('#show_error').append('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Please select cause list date</strong></div>');
            $("#list_date").focus();
            return false;
        }
        else{
            var CSRF_TOKEN_VALUE = $("input[name='CSRF_TOKEN']").val();
            $.ajax({
                url: '<?php echo base_url('ManagementReports/Report/to_be_list_priority_process'); ?>',
                cache: false,
                async: true,
                data: {list_date:list_date,mainhead:mainhead,sortby:sortby,limit_number:limit_number,CSRF_TOKEN: CSRF_TOKEN_VALUE},
                beforeSend:function(){
                    $("#btn_search").html('Loading <i class="fa fa-refresh fa-spin"></i>');
                },
                type: 'POST',
                success: function(data, status) {
                    updateCSRFToken();
                    $("#btn_search").html('Search');
                    $("#result").html(data);
                },
                error: function(xhr) {
                    updateCSRFToken();
                    alert("Error: " + xhr.status + " " + xhr.statusText);
                }
            });
        }
    });

    function get_cl_1() {
        var CSRF_TOKEN_VALUE = $("input[name='CSRF_TOKEN']").val();
        var mainhead = get_mainhead();
        var board_type = $("#board_type").val();
        var reg_unreg = $("#reg_unreg").val();
        var listorder = $("#listorder").val();
        $.ajax({
            url: '<?php echo base_url('ManagementReports/Pending/blank_category_get'); ?>',
            cache: false,
            async: true,
            data: {
                mainhead: mainhead,
                board_type: board_type,
                reg_unreg: reg_unreg,
                listorder: listorder,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            beforeSend: function() {
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



    function get_mainhead() {
        var mainhead = "";
        $('input[type=radio]').each(function() {
            if ($(this).attr("name") == "mainhead" && this.checked)
                mainhead = $(this).val();
        });
        return mainhead;
    }

    $(document).on("click", "#prnnt1", function() {
        var prtContent = $("#prnnt").html();
        var temp_str = prtContent;
        var WinPrint = window.open('', '', 'left=100,top=0,align=center,width=800,height=1200,menubar=1,toolbar=1,scrollbars=1,status=1,autosize=1');
        WinPrint.document.write(temp_str);
        WinPrint.document.close();
        WinPrint.focus();
        WinPrint.print();
    });
</script>