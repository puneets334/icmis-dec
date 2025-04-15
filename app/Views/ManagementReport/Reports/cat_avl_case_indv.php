<?= view('header') ?>
<section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header heading">
                    <div class="row">
                        <div class="col-sm-10">
                            <h3 class="card-title">Category wise cases available with roster</h3>
                        </div>
                    </div>
                </div>
				<div class="card-body">
                        <?php
                        echo form_open();
                        csrf_token();
                        ?>
                        <div class="row">
                            <div class="col-md-1 mt-4">
                                <label for=""><b>Board Type </b></label>
                            </div>
						    <div class="col-md-2">
                                 <select class="form-control" name="board_type" id="board_type">
                                        <option value="J">Court</option>
                                 </select>
                            </div>
						    <div class="col-md-1 mt-4">
                                <label for=""><b>Date :</b></label>
                            </div>
                            <div class="col-md-2">
									<?php
                                    $cur_ddt = date('Y-m-d', strtotime(' +1 day'));
                                    $next_court_work_day = date("d-m-Y", strtotime($cur_ddt));
                                    ?>
                                <input type="text" class="form-control dtp" name='ldates' id='ldates' value="<?= esc($next_court_work_day) ?>"  readonly   >
                                <input type="hidden" name="hd_from_dt1" id="hd_from_dt1" value="1" />
                            </div>
							 <div class="col-md-1 mt-4">
                                <label for=""><b>Data Save </b></label>
                            </div>
						    <div class="col-md-2">
                                <select class="form-control" name="data_save" id="data_save">
                                        <option value="No">No</option>
                                        <option value="Yes">Yes</option>
                                 </select>
                            </div>
                            <div class="col-md-2">
                                <input type="button" id="btn1" class="btn btn-block_ btn-primary" value="Submit" />
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                        <br>
                        <div id="dv_content1">
                            <div id="dv_res1" style="align-content: center"></div>
                            <div id="ank"></div>
                        </div>
                    </div>
			</div>
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

    $(document).on("click", "#btn1", function() {
        get_cl_1();
    });

    function get_cl_1() {
        var ldates = $("#ldates").val();
        var board_type = $("#board_type").val();
        var court_no = $("#court_no").val();
        var data_save = $("#data_save").val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var csrf = $("input[name='CSRF_TOKEN']").val();
        $.ajax({
            //url: 'cat_avl_case_indv_get.php',
            url: '<?php echo base_url('ManagementReports/Report/catAvlCaseIndvGet'); ?>',
            cache: false,
            async: true,
			beforeSend: function() {
                $('#dv_res1').html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
            },
            data: {
                list_dt: ldates,
                board_type: board_type,
                court_no: court_no,
                data_save: data_save,
                CSRF_TOKEN: csrf,
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
    //function CallPrint(){
    $(document).on("click", "#prnnt1", function() {
        var prtContent = $("#prnnt1").html();
        var temp_str = prtContent;
        var WinPrint = window.open('', '', 'left=100,top=0,align=center,width=800,height=1200,menubar=1,toolbar=1,scrollbars=1,status=1,cellspacing=1');
        WinPrint.document.write(temp_str);
        WinPrint.document.close();
        WinPrint.focus();
        WinPrint.print();
    });
</script>