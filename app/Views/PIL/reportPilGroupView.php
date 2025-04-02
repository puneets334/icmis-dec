<?= view('header') ?>
 
    <style>
        .custom-radio {
            float: left;
            display: inline-block;
            margin-left: 10px;
        }

        .custom_action_menu {
            float: left;
            display: inline-block;
            margin-left: 10px;
        }

        .basic_heading {
            text-align: center;
            color: #31B0D5
        }

        .btn-sm {
            padding: 0px 8px;
            font-size: 14px;
        }

        .card-header {
            padding: 5px;
        }

        h4 {
            line-height: 0px;
        }

        .row {
            margin-right: 15px;
            margin-left: 15px;
        }
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
                                    <h3 class="card-title">PIL(E) >> Pil Report</h3>
                                </div>


                            </div>
                            <br><br>

                            <?php if (session()->getFlashdata('infomsg')) { ?>
                                <div class="alert alert-success">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    <strong> <?= session()->getFlashdata('infomsg') ?></strong>
                                </div>

                            <?php } ?>
                            <?php if (session()->getFlashdata('success_msg')) : ?>
                                <div class="alert alert-danger alert-dismissible">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    <strong> <?= session()->getFlashdata('success_msg') ?></strong>
                                </div>
                            <?php endif; ?>



                        </div>


                        <span class="alert alert-error" style="display: none;">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                <span class="form-response"> </span>
                                </span>

                        <?= view('PIL/pilReportHeading'); ?>

                            <?php
                            $attribute = array('class' => 'form-horizontal', 'name' => 'reportpilgroup', 'id' => 'frmPilGroup', 'autocomplete' => 'off', 'method' => 'POST');
                            echo form_open(base_url('PIL/PilController/downloadGeneratedReport/With_Brief_History/0/0'), $attribute);
                            ?>
                        <div class="row">
                            <div class="col-md-4">
                              <label><h5>Select Group :</h5></label>
                                    <select  class="form-control" name="ecPilGroupId" id="ecPilGroupId" >
                                        <option value="0">Select</option>
                                        <?php
                                        if(!empty($pilGroup)) {
                                            foreach($pilGroup as $pilGrp){
                                                if($ecPilGroupId==$pilGrp['id']){
                                                    echo '<option value="' . $pilGrp['id'] . '" selected="selected">' . $pilGrp['group_file_number'] . '</option>';
                                                }
                                                else{
                                                    echo '<option value="' . $pilGrp['id'] . '">' . $pilGrp['group_file_number'] . '</option>';
                                                }
                                            }
                                        }

                                        ?>
                                    </select>
                            </div>
                            <div class="col-md-4">
                                 <button type="button" name="search" id="search-btn" class="btn bg-red" style="margin-top: 8%;" onclick="checkGroup();">Click Here</button>

                            </div>


                            </div>

                            </form>


                            <br><br>


                                                    
                   
                        <?php
                        $attribute = array('class' => 'form-horizontal', 'name' => 'frmPilGroupPdf', 'id' => 'frmPilGroupPdf', 'autocomplete' => 'off', 'method' => 'POST','target' => '_BLANK');
                        echo form_open(base_url('PIL/PilController/downloadGeneratedReport/With_Brief_History/0/0'), $attribute);
                        ?>
                         <div class="row">
                            <div class="col-sm-12">
                                <label ><h5>Brief History of the case and relief sought:</h5></label> 
                            </div>                
                            <div class="col-sm-3">

                                <textarea class="form-control" rows="5" cols="10" name="comment" id="comment"></textarea>
                            </div>

                            <div class="col-sm-5">
                                <span class="input-group-btn">

                                <button type="submit" name="generate" id="generate-btn" class="btn bg-blue">Generate Report</button>
                                </span>
                            </div>
                        </div>                  

                        <?php form_close(); ?>


                      <br><br><br>

                        <div id="tabledata" style="display: none;">
                            <h4 align="center">PILs in Group</h4>
                            <br><br>
                            <table class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Inward No/Year</th>
                                    <th>Received From</th>
                                    <th>Received On</th>
                                    <th>Petition Date</th>
                                    </tr>
                                </thead>
                                <tbody id="data_set">
                                </tbody>
                            </table>
                        </div>

                   </div><br><br>


                    </div>



                    </div> <!-- card div -->



                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->




        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.section -->



<script>

    function updateCSRFToken() {
        $.getJSON("<?php echo base_url('Csrftoken'); ?>", function (result) {
            $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
        });
    }

    function checkGroup() {

        var ecPilGroupId = $("#ecPilGroupId").val();
        if (ecPilGroupId == 0) {
            alert("Please Select The Group");
            document.getElementById("ecPilGroupId").focus();
            return false;
        } else {

            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

            $.ajax({
                type: "POST",
                dataType: 'json',
                data: {
                    CSRF_TOKEN: CSRF_TOKEN_VALUE,
                    'dt': ecPilGroupId

                },
                url: "<?php echo base_url('PIL/PilController/addToPilGroupReport'); ?>",
                success: function (data) {
                    updateCSRFToken();
                    
                    var dataArray = data.casesInPilGroup;
                  
                    var html = "";
                    if (dataArray !== undefined && dataArray !== null) {
                        var i = 1;
                        dataArray.forEach(dt => {

                           
                            html += '<tr>'
                            html += '<td>' + i++ + '</td>'
                            html += '<td>' + dt['pil_diary_number'] + '</td>'
                            html += '<td>' + dt['received_from'] + '</td>'
                            html += '<td>' + (dt['received_on'] ? (new Date(dt['received_on']).toLocaleDateString('en-GB').replace(/\//g, '-')) : null) + '</td>'
                            html += '<td>' + (dt['petition_date'] ? (new Date(dt['petition_date']).toLocaleDateString('en-GB').replace(/\//g, '-')) : null) + '</td>'
                            html += '</tr>';

                        })
                        console.log(html);
                        $('#data_set').append(html);

                        $('#tabledata').css("display", "block");
                        // window.location.reload();
                    } else {
                        alert("No record Found");
                    }

                   
                },
                error: function (data) {
                    updateCSRFToken();
                    alert(data);
                    
                }
            });


        }
    }

    function generateReport()
    {
        var ecPilGroupId = $("#ecPilGroupId").val();
        var comment = $("#comment").val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        var ty = 'With_Brief_History';
        $.ajax({
            type: "POST",
            dataType: 'json',
            data: {
                CSRF_TOKEN: CSRF_TOKEN_VALUE,
                'dt': ecPilGroupId,
                'type':ty,
                'comment':comment

            },
            url: "<?php echo base_url('PIL/PilController/downloadGeneratedReport'); ?>",
            success: function (data) {
                console.log(data);
                 updateCSRFToken();
            },
            error: function (data) {
                alert(data);
                updateCSRFToken();
            }
        });


    }
</script>



 <?=view('sci_main_footer') ?>