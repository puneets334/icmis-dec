<?= view('header') ?>
<style>
    input[type=text],
    select {
        width: 10%;
        padding: 12px 20px;
        margin: 8px 0;
        display: inline-block;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }

    /* input[type=button] {
        width: 10%;
        background-color: #4CAF50;
        color: white;
        padding: 14px 20px;
        margin: 8px 0;
        border: none;
        border-radius: 4px;
        cursor: all-scroll;
    } */

    input[type=button]:hover {
        background-color: #223094;
        cursor: all-scroll;
    }

    .grid-item {
        background-color: rgba(255, 255, 255, 0.8);
        border: 1px solid rgba(0, 0, 0, 0.8);
        padding: 20px;
        font-size: 15px;
        text-align: center;
        cursor: all-scroll;
    }
    input#btn_search {
        border-radius: 20px!important;
    }

    grid-item:hover {
        background-color: #223094;
        cursor: all-scroll;
    }
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header heading">
                    <div class="row">
                        <div class="col-sm-10">
                            <h3 class="card-title">Report</h3>
                        </div>
                    </div>
                </div>

                <form method="post">
                    <?= csrf_field() ?>
                    <div class="card">
                        <div class="card-body">
                            

                            <div class="row">
                                <div class="col-sm-3 col-xs-12"></div>
                                
                                <div class="col-sm-6 col-xs-12">
                                        <div class="row ml-1">
                                            <div class="col-md-12">
                                                <div>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" style="margin:7px 0;">Select Date<span style="color:red;">*</span></span>
                                                        </div>                                                        
                                                        <input type="text" class="form-control datepick_" id="txtfuturedate" name="txtfuturedate" placeholder="Selcet Date">
                                                        <input id="btn_search" name="btn_search" type="button" class="btn btn-success" value="show Links" onClick="getvc_links()">
                                                    </div>
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </div>
                                <div class="col-sm-3 col-xs-12"></div>
                            </div>
                            <div id="result"></div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</section>



<script>
    function getvc_links() {
        var todayDate = new Date().toISOString().slice(0, 10);
        var today = new Date();
        var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
        var vc_date = $('#txtfuturedate').val();
        var x = vc_date.split("/");
        var n_vc_date = x[2] + '-' + x[0] + '-' + x[1];
        var csrf = $("input[name='CSRF_TOKEN']").val();

        if (todayDate === n_vc_date) {
            if (time < '09:00:00') {
                document.getElementById('result').innerHTML = "<center><font color=blue>LINKS ARE UNDER PREPARATION</font></center>";
                return;
            }
        }

        $.ajax({
            url: '<?php echo base_url('MasterManagement/VcRoom/getVcLinks'); ?>',
            data: {
                vc_date: n_vc_date,
                CSRF_TOKEN: csrf,
            },
            cache: false,
            type: 'POST',
            success: function(data) {
                updateCSRFToken();
               if (data.error) {
                    document.getElementById('result').innerHTML = "<center><font color=red>" + data.error + "</font></center>";
                } else {
                    document.getElementById('result').innerHTML = data;
                }
            },
            error: function(xhr) {
                document.getElementById('result').innerHTML = "<center><font color=red>Error occurred: " + xhr.status + "</font></center>";
            }
        });
    }


    function open_link(id) {
        //updateCSRFToken();

        // if(id != '')
        // {
        //     window.open(id, '_blank');
        // }else{
        //     alert('Please contact to Computer Cell!!');
        //     return false;
        // }
    }
    $("#txtfuturedate").datepicker({
        maxDate: 0
    });
</script>