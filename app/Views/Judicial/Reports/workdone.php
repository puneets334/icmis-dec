<?= view('header') ?>
<style>
    #listingTable_wrapper {
        width: 97%;
    }
    .err_msg_class{font-size:11px;}
    .rpe_custom_msg{font-size:10px!important;}
</style>
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Judicial / Report >> Work Done </h3>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <span class="alert-danger"><?= \Config\Services::validation()->listErrors() ?></span>

                            <?php if (session()->getFlashdata('error')) { ?>
                                <div class="alert alert-danger">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    <?= session()->getFlashdata('error') ?>
                                </div>
                            <?php } else if (session("warning")) { ?>
                                <div class="alert alert-warning text-center">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    <?= session("warning") ?>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="col-md-12">
                            <div class="card-header p-2" style="background-color: #fff;">
                                <?= view('Judicial/Reports/menu') ?>
                            </div>
                            <div class="card-body">
                                <div class="tab-content">
                                    <!-- Page Content Start -->
                                    <form method="post" action="<?= site_url('report/show'); ?>">
                                        <?php echo csrf_field(); ?>
                                        <div id="dv_content1">
                                            <div class="col-md-12">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <div class="container text-center">
                                                            <h3>REPORT OF WORK DONE OF DA
                                                                <span style="color: #35b9cd">
                                                                    <?= ($usertype == 1) ? "FOR ALL DA" : "FOR SECTION " . $section; ?>
                                                                </span>
                                                            </h3>
                                                        </div>

                                                        <div class="container">
                                                            <form class="text-center" method="post" action="<?= base_url(); ?>/Judicial/Report/aor_wise_matters">
                                                                <?php //echo csrf_field(); ?>
                                                                <table class="table table-bordered mx-auto" style="width: 70%;">
                                                                    <tr>
                                                                        <td>FOR THE DATE OF:</td>
                                                                        <td>
                                                                            <input type="date" id="date_for" size="10" value="<?= date('d-m-Y'); ?>" />
                                                                            <span class="from_date_err text-danger err_msg_class"></span>
                                                                        </td>
                                                                        <td>
                                                                            <select name="ddl_all_blank" id="ddl_all_blank">
                                                                                <option value="1">All</option>
                                                                                <option value="2">Blank</option>
                                                                                <option value="3">Atleast One</option>
                                                                            </select>
                                                                        </td>
                                                                        <td>
                                                                        <!-- <button type="button" id="btnreport" class="btn btn-primary">SHOW REPORT</button> -->
                                                                        <input type="button" name="btnreport" id="btnreport" value="SHOW REPORT" class="btn btn-primary">
                                                                        <label class="rpe_custom_msg"></label>

                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- <div style="text-align: center; font-weight: bold;">
                                                
                                                FOR THE DATE OF <input type="text" id="date_for" class="dtp" size="10" value="<?= date('d-m-Y'); ?>" />
                                                <select name="ddl_all_blank" id="ddl_all_blank">
                                                    <option value="1">All</option>
                                                    <option value="2">Blank</option>
                                                    <option value="3">Atleast One</option>
                                                </select>
                                                <button type="button" id="btnreport">SHOW REPORT</button>
                                            </div> -->
                                            <div id="result_main"></div>
                                        </div>
                                    </form>
                                    <!-- Page Content End -->
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

    function refereshTable()
    {
        $("#listingTable").DataTable({
            "responsive": false,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    }

    function validationFromDate(){
            var from_date = $('#date_for').val();        
            if(from_date == ''){                            
                $('.from_date_err').text('Date is Required');
                return false;
            }else{          
                
                $('.from_date_err').text('');
                return true;
            }
    }

    $(document).ready(function() {
        $("#btnreport").click(function()
        {
            solve1 =true;
            solve1 = validationFromDate();            
            if(solve1 == false ){
                return false;                
            }            
            //var CSRF_TOKEN = 'CSRF_TOKEN';
            //var CSRF_TOKEN_VALUE = $("input[name='CSRF_TOKEN']").val();
            var ddl_all_blank = $('#ddl_all_blank').val();
            $.ajax({
                    type: 'get',
                    url: "<?= base_url(); ?>/Judicial/Report/get_workdone_withget",
                    beforeSend: function(xhr) {
                        $('#btnreport').val('Please wait...');
                        $('#btnreport').prop('disabled', true);
                        $('.rpe_custom_msg').text('It is take few sec or mins. generate report'); 
                        $("#result_main").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?= base_url(); ?>/images/load.gif'></div>");
                    },
                    data: {
                        date: $("#date_for").val(),
                        ddl_all_blank: ddl_all_blank,
                        //CSRF_TOKEN: CSRF_TOKEN_VALUE
                    }
                })
                .done(function(msg_new) {
                    //console.log(msg_new);
                    //updateCSRFToken();
                    $("#result_main").html(msg_new);
                    //$("#result_main").html(msg_new);                    
                    $('#btnreport').prop('disabled', false);
                    $('#btnreport').val('Show Report');      
                    $('.rpe_custom_msg').text('');                  
                    // refereshTable();
                })
                .fail(function() {
                    //updateCSRFToken();
                    // alert("ERROR, Please Contact Server Room");
                    $('.rpe_custom_msg').text('');
                });
        });
    });
    $(document).on("focus", ".dtp", function() {
        $('.dtp').datepicker({
            dateFormat: 'dd-mm-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: '1950:2050'
        });
    });

    $(document).on("click", "[id^='doc_']", function() {
        var tempid = this.id.split('_');
        //alert("type="+tempid[0]+", id="+tempid[1]);
        //return false;
        $('#dv_sh_hd').css("display", "block");
        $('#dv_fixedFor_P').css("display", "block");
        $.ajax({
                type: 'POST',
                url: "<?= base_url(); ?>/get_workdone_full.php",
                beforeSend: function(xhr) {
                    $("#sar").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?= base_url(); ?>/images/load.gif'></div>");
                },
                data: {
                    date: $("#date_for").val(),
                    type: tempid[0],
                    id: tempid[1],
                    name: $("#name_" + tempid[1]).html()
                }
            })
            .done(function(msg_new) {
                $("#sar").html(msg_new);
            })
            .fail(function() {
                $("#sar").html("<div style='margin:0 auto;margin-top:20px;text-align:center'>ERROR, PLEASE CONTACT SERVER ROOM</div>");
            });
    });


    $(document).on("click", "[id^='notvdoc_']", function() {
        var tempid = this.id.split('_');
        //alert("type="+tempid[0]+", id="+tempid[1]);
        //return false;
        $('#dv_sh_hd').css("display", "block");
        $('#dv_fixedFor_P').css("display", "block");
        $.ajax({
                type: 'POST',
                url: "./get_workdone_full.php",
                beforeSend: function(xhr) {
                    $("#sar").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?= base_url(); ?>/images/load.gif'></div>");
                },
                data: {
                    date: $("#date_for").val(),
                    type: tempid[0],
                    id: tempid[1],
                    name: $("#name_" + tempid[1]).html()
                }
            })
            .done(function(msg_new) {
                $("#sar").html(msg_new);
            })
            .fail(function() {
                $("#sar").html("<div style='margin:0 auto;margin-top:20px;text-align:center'>ERROR, PLEASE CONTACT SERVER ROOM</div>");
            });
    });

    $(document).on("click", "[id^='totup_']", function() {
        var tempid = this.id.split('_');
        //alert("type="+tempid[0]+", id="+tempid[1]);
        //return false;
        $('#dv_sh_hd').css("display", "block");
        $('#dv_fixedFor_P').css("display", "block");
        $.ajax({
                type: 'POST',
                url: "./get_workdone_full.php",
                beforeSend: function(xhr) {
                    $("#sar").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?= base_url(); ?>/images/load.gif'></div>");
                },
                data: {
                    date: $("#date_for").val(),
                    type: tempid[0],
                    id: tempid[1],
                    name: $("#name_" + tempid[1]).html()
                }
            })
            .done(function(msg_new) {
                $("#sar").html(msg_new);
            })
            .fail(function() {
                $("#sar").html("<div style='margin:0 auto;margin-top:20px;text-align:center'>ERROR, PLEASE CONTACT SERVER ROOM</div>");
            });
    });

    $(document).on("click", "[id^='supuser_']", function() {
        var tempid = this.id.split('_');
        //alert("type="+tempid[0]+", id="+tempid[1]);
        //return false;
        $('#dv_sh_hd').css("display", "block");
        $('#dv_fixedFor_P').css("display", "block");
        $.ajax({
                type: 'POST',
                url: "./get_workdone_full.php",
                beforeSend: function(xhr) {
                    $("#sar").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?= base_url(); ?>/images/load.gif'></div>");
                },
                data: {
                    date: $("#date_for").val(),
                    type: tempid[0],
                    id: tempid[1],
                    name: $("#name_" + tempid[1]).html()
                }
            })
            .done(function(msg_new) {
                $("#sar").html(msg_new);
            })
            .fail(function() {
                $("#sar").html("<div style='margin:0 auto;margin-top:20px;text-align:center'>ERROR, PLEASE CONTACT SERVER ROOM</div>");
            });
    });

    $(document).on("click", "#sp_close", function() {
        $('#dv_fixedFor_P').css("display", "none");
        $('#dv_sh_hd').css("display", "none");
    });
</script>