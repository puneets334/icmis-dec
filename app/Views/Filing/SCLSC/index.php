<?= view('header'); ?>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <form method="post">
                        <p id="show_error"></p>
                        <div class="card-header heading">

                            <div class="row">
                                <div class="col-sm-10">
                                    <h3 class="card-title">SCLSC | Cases Pending For Filing</h3>
                                </div>

                            </div>
                        </div>
                        <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />


                        <div class="card-body">
                            <div class="form-row">
                                <input id="btn_search" name="btn_search" type="button" class="btn btn-success ml-2" value="Click">
                            </div>
                        </div>

                    </form>
                </div>

                <div class="row col-md-12 m-2 p-2" id="result"></div>

                <div>
                    <div class="modal fade" id="myModal">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content myModal_content">

                            </div>
                        </div>
                    </div>
                </div>


                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>
<!-- /.content -->

  
<script>
    var image_loader_url = "<?php echo base_url('assets/images/load.gif'); ?>";

    $(document).on('click', '#btn_search', function(e) {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            url: '<?= base_url(); ?>/Filing/Sclsc/UnFiledCases',
            cache: false,
            async: true,
            //data: {next_dt:next_dt},
            data: {
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            beforeSend: function() {
                $("#btn_search").prop('disabled',true);
                $('#result').html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
            },
            type: 'POST',
            success: function(data, status) {
                $("#result").html(data);
            },
            complete: function() {
                updateCSRFToken();
                $("#btn_search").prop('disabled',false);

            },
            
            error: function(xhr) {
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });

    });


    $(document).on('click', '.unfiled_case_details_modal', function(e) {
        var diary_no = $(this).data('diary_no');
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $("#myModal").modal({
            backdrop: false
        });
        $.ajax({
            url: "<?php echo base_url('Filing/Sclsc/UnFiledCaseDetails'); ?>",
            cache: false,
            async: true,
            data: {
                diary_no: diary_no,CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            beforeSend: function() {
                //$('.myModal_content').html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
            },
            type: 'POST',
            success: function(data, status) {
                updateCSRFToken();
                $(".myModal_content").html(data);
            },
            error: function(xhr) {
                updateCSRFToken();
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
    });


 
     $(document).on('click', '#generate_diary_no', function(e) {
        // alert('133ashu');
        var diary_no = $(this).data('diary_no');
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        //$("#myModal").modal({backdrop: false});
        $.ajax({
            url: "<?php echo base_url('Filing/Sclsc/generateDiary'); ?>",
            cache: false,
            async: true,
            data: {
                diary_no: diary_no, CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            beforeSend: function() {
                //$('.myModal_content').html('<table widht="100%" align="center"><tr><td><img src="'+image_loader_url+'"/></td></tr></table>');
            },
            type: 'POST',
            dataType: "JSON",
            success: function(data, status) {
                updateCSRFToken();
                console.log(data);
                if (data.status == 'SUCCESS') {
                    alert(data.status);
                    $("#d_" + diary_no).html("<span class='text-success font-weight-bolder'>Success</span>");
                    // swal({
                    //     title: "Success!",
                    //     text: "Generated Diary No. " + data.diary_no,
                    //     icon: "success",
                    //     button: "success!"
                    // });
                    $("#myModal .close").click();
                } else {
                    alert(data.status);
                    // swal({
                    //     title: "Error!",
                    //     text: data.status,
                    //     icon: "error",
                    //     button: "error!"
                    // });
                    //$("#d_"+diary_no).children(".delete_action").html('Delete');
                }
            },
            complete: function() {
                updateCSRFToken();
            },
            error: function(xhr) {
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
    });
</script>