<?= view('header'); ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Cause List - Ready / Not Ready Report</h3>
                            </div>
                            <?= view('Filing/filing_filter_buttons'); ?>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php
                        echo form_open();
                        csrf_token();
                        ?>
                        <div id="dv_content1">
                            <div class="row">
                            <div class="col-md-2">
                                        <label for="">Connected</label>
                                        <select class="ele form-control" name="connt" id="connt">
                                        <option value="1">With Connected</option>
                                        <option value="2">Only Main</option>
                                        </select>
                                    </div>
                                <div class="col-md-2 mt-4">
                                    <input type="button" id="btngetr" onclick="fetch_data();" class="btn btn-primary quick-btn" name="btngetr" value=" Get Records " />
                                </div>
                            </div>
                            <div id="dv_res1"></div>
                        </div>
                        <?php echo form_close(); ?>
                        <div class="center" id="record"></div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<script>

function fetch_data()
    {
        $('#dv_res1').html("");
        var connt = $('#connt').val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var csrf = $("input[name='CSRF_TOKEN']").val();
        $.ajax({
            url: '<?php echo base_url('/ManagementReports/Pending/ready_not_get'); ?>',
            cache: false,
            async: true,
            type: 'post',
            data: {
                CSRF_TOKEN: csrf,
                connt: connt
            },
            beforeSend: function() {
                $('#dv_res1').html('<table widht="100%" align="center"><tr><td><img src="../../images/load.gif"/></td></tr></table>');
            },
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


$(document).on("click","#prnnt1",function(){    
var prtContent = $("#prnnt").html();
var temp_str=prtContent;
var WinPrint = window.open('','','left=100,top=0,align=center,width=800,height=1200,menubar=1,toolbar=1,scrollbars=1,status=1,cellspacing=1');
 WinPrint.document.write(temp_str);
 WinPrint.document.close();
 WinPrint.focus();
 WinPrint.print();
});
   
</script>