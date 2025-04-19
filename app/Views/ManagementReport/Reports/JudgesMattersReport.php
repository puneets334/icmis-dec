<?= view('header'); ?>
<section class="content">
    <div class="container-fluid">
        <div id="res_loader"></div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-12">
                            <h3 class="card-title">Management Reports >> Statistical Information of Judges</h3>        
                            </div>
                        </div>
                        
                    </div>
                    <div class="card-body">
                     <h2 align="center">Final Disposal Report</h2>        
                     <form  action="<?= site_url(uri_string()) ?>">
                            <?php echo csrf_field(); ?>
                            <section class="content">
                                
                                    <div class="box-body">
                                        <br/>
                                        <div class="row">
                                            <div class="col-md-2">&nbsp;</div>
                                            <div class="col-md-2 mt-2"><b>Select Hon’ble Judge :</b> </div> 
                                            <div class="col-md-3">
                                                <select name="judges_list" id="judges_list" style=""  class="form-control input-sm filter_select_dropdown" required>
                                                    <option value="" title="Select">Select Hon’ble Judge </option>
                                                    <?php foreach ($judge_list as $dataRes) { ?>
                                                        <option  value="<?php echo ($dataRes['jcode'].'|'. $dataRes['jname']); ?>"><?php echo $dataRes['jname']; ?> </option>
                                                    <?php } ?>
                                                </select>
                                                <div id="judgeselect_err" class="text-danger text-center"></div>
                                            </div>
                                                <div class="col-md-2">
                                                    <button type="submit" id="btn1" class="btn btn-block btn-primary btn-flat pull-left ml-3" >
                                                        <i class="fa fa-save"></i> Submit 
                                                    </button>
                                                </div>
                                        </div>
                                        <br/>

                                    </div>


                                
                                <br/><br/>

                            </section>
                        </form>
                        <div id="dv_res1"></div>
                    </div>
                    </div>
<script>
    $(document).on('change', '#judges_list', function(){
        var judges_list = $('#judges_list').val(); 
        if(judges_list == ''){            
            $('#judgeselect_err').text('Please Select a Judge');             
        }
        else{            
            $('#judgeselect_err').text('');            
        }
    });
 $(document).on("click", "#btn1", async function(e) {
    e.preventDefault(); 
        await updateCSRFTokenSync();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        var judges_list = $('#judges_list').val(); 
        //var newWin = window.open('', '_blank');   
        var judges_list = $('#judges_list').val();
        if(judges_list == ''){            
            $('#judgeselect_err').text('Please Select a Judge'); 
            return false;
        }
        else{            
            $('#judgeselect_err').text('');            
        }

        

        $.ajax({
            url: "<?php echo base_url('ManagementReports/JudgesMatters/judges_matter_list_ajax'); ?>",
            method: 'POST',
            beforeSend: function() {
                $("#btn1").attr("disabled", true);                
                //$("#dv_res1").html('');
                $('#dv_res1').html("<div style='margin:0 auto;margin-top:20px;width:28%'><span>Please wait It can be take few mins....</span><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
            },
            data: {
                judges_list:judges_list,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            cache: false,
            success: function(data) {
                //alert();
                window.open("<?php echo base_url('/ManagementReports/JudgesMatters/judges_matter_list_ajax') ?>", "_blank");
                //newWin.location.href = "<?php //echo base_url('/ManagementReports/JudgesMatters/judges_matter_list_ajax') ?>";                                
                $("#dv_res1").html('');
                $("#btn1").attr("disabled", false);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                //updateCSRFToken();
                alert("Error: " + jqXHR.status + " " + errorThrown);                
            }
        });
    });
    

</script>