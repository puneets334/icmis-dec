<?= view('header'); ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Special Benches Report | INTEGRATED CASE MANAGEMENT INFORMATION SYSTEM</h3>
                            </div>
                            <?= view('Filing/filing_filter_buttons'); ?>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php
                        echo form_open();
                        csrf_token();
                        ?>
                        <div id="dv_content1"   >
                            <div style="text-align: center;font-weight: bold">REPORT OF SPECIAL BENCHES
                                <input type="button" id="btngetr" onclick="fetch_data();" class="btn btn-primary quick-btn" name="btngetr" value="SHOW REPORT" id="btnreport"/>
                                &nbsp; 
                                <input type="button" id="btngetr" onclick="fetch_count_data();" class="btn btn-primary quick-btn" name="btngetr" value="GET COUNT ONLY" id="btnreport_count"/>                
                            </div>
                            <div id="dv_res1">
                            
                                
                            </div>
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
        var mainhead = 'M';
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var csrf = $("input[name='CSRF_TOKEN']").val();
        $.ajax({
            url: '<?php echo base_url('/ManagementReports/Report/get_special_bench_report'); ?>',
            cache: false,
            async: true,
            type: 'post',
            data: {
                CSRF_TOKEN: csrf,
                part: 1
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
    function fetch_count_data()
    {
        $('#dv_res1').html("");
        var mainhead = 'M';
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var csrf = $("input[name='CSRF_TOKEN']").val();
        $.ajax({
            url: '<?php echo base_url('/ManagementReports/Report/get_special_bench_report'); ?>',
            cache: false,
            async: true,
            type: 'post',
            data: {
                CSRF_TOKEN: csrf,
                part: 2
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
    
    $(document).on("click",".sort",function(){
    var str = $(this).text();
    var sort = "";
    var order = "";
    str = "sarthak"+str;
    if(str.search("Judges"))
        sort = 'J';
    
    if(str.search("&#9650"))
        order = 'D';
    else if(str.search("&#9660"))
        order = 'A';
      
    //alert(sort+"<>"+order);
    var CSRF_TOKEN = 'CSRF_TOKEN';
        var csrf = $("input[name='CSRF_TOKEN']").val();
        $.ajax({
            url: '<?php echo base_url('/ManagementReports/Report/get_special_bench_report'); ?>',
            cache: false,
            async: true,
            type: 'post',
            data: {
                CSRF_TOKEN: csrf,
                part: 1,
                sort:sort,
                order:order
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
    
});

    $(document).on("click","#prnnt1",function(){    
var prtContent = $("#prnnt").html();
var temp_str=prtContent;
var WinPrint = window.open('','','left=100,top=0,align=center,width=800,height=1200,menubar=1,toolbar=1,scrollbars=1,status=1,autosize=1');
 WinPrint.document.write(temp_str);
 WinPrint.document.close();
 WinPrint.focus();
 WinPrint.print();
});
   
</script>