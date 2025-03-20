<?= view('header') ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">

                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">SEC LIST</h3>
                            </div>
                           
                        </div>
                    </div>



                    <?php
                    echo form_open();
                    csrf_token();
                    ?>
                    <div id="dv_content1" class="container mt-4">

                        <div class="text-center">
                            <form>
                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <fieldset class="p-2">
                                            <label for="sec_id">Board Type</label>
                                            <select class="form-control" name="board_type" id="board_type">
                                                <option value="J">Court</option>
                                            </select>
                                        </fieldset>
                                    </div>

                                    <div class="col-md-3">
                                        <fieldset class="p-2">
                                            <label for="sec_id">Tentative Listing Date</label>
                                            <?php 
                                                if(count($listing_date)>0){
                                                ?>
                                                Date 
                                                <select name='ldates' id='ldates'>
                                                    <?php
                                                    foreach($listing_date as $row){
                                                        $working_date = date("d-m-Y", strtotime($row["working_date"]));
                                                    ?>
                                                    <option value="<?php echo $row["working_date"]; ?>"><?php echo $row['working_date']; ?></option>
                                                    <?php
                                                    }
                                                    ?>
                                                </select>
                                                <?php
                                                }
                                                else{
                                                    echo "No Records Found.";
                                                }

                                                ?>
                                        </fieldset>
                                    </div>
                                    <div class="col-md-2">
                                        <fieldset class="p-2"><br/>
                                            <input type="button" name="btn1" id="btn1" value="Submit" class="btn btn-primary" />
                                        </fieldset>
                                    </div>

                                    <div class="col-md-3">
                                        <label for="sec_id">Section Name</label>
                                        <select class="form-control" name="sec_id" id="sec_id">
                                            <option value="0">-ALL-</option>
                                            <?php foreach ($section_name as $ro_u) {
                                                $ro_id = $ro_u['id'];
                                                $ro_name = $ro_u['section_name'];
                                            ?>
                                                <option value="<?php echo $ro_id; ?>"><?php echo $ro_name; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    
                                </div>

                              
                            </form>

                            <div id="res_loader" class="text-center"></div>
                        </div>

                        <div id="dv_res1"></div>
                    </div>


                    <?php echo form_close(); ?>




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
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var csrf = $("input[name='CSRF_TOKEN']").val();
        var ldates = $("#ldates").val();
        var board_type = $("#board_type").val();
        var sec_id = $("#sec_id").val();
        $.ajax({
            url: '<?php echo base_url('Listing/Report/get_spread_out'); ?>',
            cache: false,
            async: true,
            type: 'POST',
            data: {
                CSRF_TOKEN: csrf,
                from_dt: ldates,
                board_type: board_type,
                sec_id: sec_id
            },
            beforeSend: function() {
                $('#dv_res1').html('<table widht="100%" align="center"><tr><td><img src="../../images/load.gif"/></td></tr></table>');
            },

            success: function(data, status) {
                $('#dv_res1').html(data);
                updateCSRFToken();
            },
            error: function(xhr) {
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
        updateCSRFToken();
    }

    //function CallPrint(){
    $(document).on("click", "#prnnt1", function() {
        var prtContent = $("#prnnt").html();
        var temp_str = prtContent;
        var WinPrint = window.open('', '', 'left=100,top=0,align=center,width=800,height=1200,menubar=1,toolbar=1,scrollbars=1,status=1');
        WinPrint.document.write(temp_str);
        WinPrint.document.close();
        WinPrint.focus();
        WinPrint.print();
    });
</script>