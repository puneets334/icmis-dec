<?= view('header') ?>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Fixed Date Matters</h3>
                            </div>
                            <div class="col-sm-2">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card-body">
                                <form method="post" action="">
                                    <?= csrf_field() ?>
                                    <div class="row justify-content-center">
                                        <div class="col-md-2"></div>    
                                        <div class="col-md-10">
                                            <div class="form-group row">
                                                <label for="wday" class="col-form-label">Showing Fixed Date Matters For</label>
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <input type="date" name="wday" id="wday" disabled autocomplete="off" class="form-control" value="<?php echo $ss; ?>">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <input type="button" class="btn btn-primary quick-btn" name='display' id='display' onClick='getdata()' value="Get Details" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </form>
                                <hr>
                                <div id="txtHint"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script type="text/javascript">
    $(document).ready(function(){
        $("#display").click(function(){
            var str = (document.getElementById('wday').value);
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $.ajax({
                type: 'GET',
                url: base_url + "/PaperBook/FixedDateMatters/get_fd_matters?q="+ str,
                beforeSend: function (xhr) {
                    $("#txtHint").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='../../images/load.gif'></div>");
                },
            })
            .done(function(msg_new){
                updateCSRFToken();
                $("#txtHint").html(msg_new);
                document.getElementById('print1').disabled = false;
            })
            .fail(function(){
                updateCSRFToken();
                alert("ERROR, Please Contact Server Room"); 
            });
        });
    });
</script>