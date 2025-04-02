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
        a {color:darkslategrey}      /* Unvisited link  */

        a:hover {color:black}    /* Mouse over link */
        a:active {color:#0000FF;}  /* Selected link   */
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
                                    <h3 class="card-title">PIL(E) >> PIL Entry</h3>
                                </div>


                            </div>
                  

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

                        <?= view('PIL/pilEntryHeading'); ?>

                        <?php
                        $attribute = array('class' => 'form-horizontal', 'name' => 'frmAddToPilGroup', 'id' => 'frmAddToPilGroup', 'autocomplete' => 'off', 'method' => 'POST');
                        echo form_open(base_url('PIL/PilController/addToPilGroupShow'), $attribute);
//                        echo "dfghdf";
//                        echo "TTT".$ecPilGroupId;
//                        var_dump($pilGroup[0]['id']);
//                        echo "<pre>";
//                        print_r($casesInPilGroup);
                        ?>


                        <div class="row col-md-12 ">
                            <label for="pilGroup" class="control-label">Select Group</label>
                            <div class="col-md-3">
                                <select  class="form-control" name="ecPilGroupId" id="ecPilGroupId" onchange="showGroupCases()">
                                    <option value="0">Select</option>
                                    <?php
                                    if (is_array($pilGroup)) {
                                    if(count($pilGroup) > 0) {

                                        //echo "ecid".$ecPilGroupId.">>".$pilGroup['id'];
                                          foreach ($pilGroup as $pilGrp){

                                            if ($ecPilGroupId == $pilGrp['id']) {
                                                echo '<option value="' . $pilGrp['id'] . '" selected="selected">' . $pilGrp['group_file_number'] . '</option>';
                                            } else {
                                                echo '<option value="' . $pilGrp['id'] . '">' . $pilGrp['group_file_number'] . '</option>';
                                            }
                                        }
                                    }}
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-3" id="divDiaryNo">
                                <input type="number" class="form-control" placeholder="Inward No"  id="diaryNo" name="diaryNo">
                            </div>

                            <div class="col-md-3">
                                <select class="form-control" id="diaryYear" name="diaryYear">
                                    <?php
                                    if(!empty($searchedYear)) {
                                        for ($year = date('Y'); $year >= 1950; $year--)
                                            if ($searchedYear == $year) {
                                                echo '<option value="' . $year . '" selected="selected">' . $year . '</option>';
                                            } else {
                                                echo '<option value="' . $year . '">' . $year . '</option>';
                                            }
                                    }
                                    ?>

                                </select>
                            </div>

                            <div class="input-group-btn" style="text-align:center ">
                                <button type="button" name="search" id="search-btn" class="btn btn-primary" onclick="checkDiarynumberToAddInGroup();">Search </button>
                            </div>


                        </div>
                      

                        <?php form_close(); ?>


                        <br><br>


                        <br>
                        <br>
                        
                        <div id="div_result"></div>

                        



                    </div> 
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
        // function updateCSRFToken() {
        //     $.getJSON("<?php //echo base_url('Csrftoken'); ?>", function (result) {
        //         $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
        //     });
        // }

        function showGroupCases(){
            var diaryNo=document.getElementById("diaryNo").value;
            var diaryYear=document.getElementById("diaryYear").value;
            var ecPilGroupId=document.getElementById("ecPilGroupId").value;
           var CSRF_TOKEN = 'CSRF_TOKEN';
           var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
           $.ajax({
                    type: 'POST',
                    url: base_url+'/PIL/PilController/addToPilGroupResult',
                    data: {
                        CSRF_TOKEN: CSRF_TOKEN_VALUE,                         
                        'ecPilGroupId': ecPilGroupId,
                        'diaryNo' : diaryNo,
                        'diaryYear' : diaryYear
                    },
                        beforeSend: function () {
                            $('#div_result').html('<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>');
                        },
                       
                        success: function(data, status) {
                            updateCSRFToken();
                            $('#div_result').html(data);
                            $('#btn_submit').attr('disabled',false);
                            
                        },
                        error: function(xhr) {
                            updateCSRFToken();
                            alert("Error: " + xhr.status + " " + xhr.statusText);
                        }

                });
        }

         

        function checkDiarynumberToAddInGroup()
        {
            var diaryNo=document.getElementById("diaryNo").value;
            var diaryYear=document.getElementById("diaryYear").value;
            var ecPilGroupId=document.getElementById("ecPilGroupId").value;
            if(ecPilGroupId==0){
                alert("Please Select Group to add.");
                document.getElementById("ecPilGroupId").focus();
                return false;
            }
            if(diaryNo==""){
                alert("Please Enter Inward Number");
                document.getElementById("diaryNo").focus();
                return false;
            }
            if(diaryYear==""){
                alert("Please Enter Inward Year");
                document.getElementById("diaryYear").focus();
                return false;  alert(id);
            }
            //alert("Test");

            var CSRF_TOKEN = 'CSRF_TOKEN';
		    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

            $.ajax({
                    url: base_url+'/PIL/PilController/addToPilGroupResult',
                    data: {
                        CSRF_TOKEN: CSRF_TOKEN_VALUE,
                        'ecPilGroupId': ecPilGroupId,
                        'diaryNo' : diaryNo,
                        'diaryYear' : diaryYear
                    },
                        beforeSend: function () {
                            $('#div_result').html('<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>');
                        },
                        type: 'POST',
                        success: function(data, status) {
                            updateCSRFToken();
                            $('#div_result').html(data);
                            $('#msg').html();
                            $('#btn_submit').attr('disabled',false);
                            
                        },
                        error: function(xhr) {
                            updateCSRFToken();
                            alert("Error: " + xhr.status + " " + xhr.statusText);
                        }

                });
           // document.getElementById("frmAddToPilGroup").submit();
        }



    </script>

    <script>
        function pilRemove(id)
        {
           
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            var ecPilGroupId=$("#ecPilGroupId").val();

            if (confirm('Do you really want to remove this PIL from PIL Group?'))
            {
                $.ajax({
                    type:"POST",
                    data: {
                        CSRF_TOKEN: CSRF_TOKEN_VALUE,
                        'id':id,
                        'pilgpid':ecPilGroupId,
                    },
                    url: "<?php echo base_url('/PIL/PilController/removeCaseFromPilGroup/'); ?>",
                    success: function(data) {
                        // alert(data);
                        if(data === '1')
                        {
                            alert("PIL removed from this PIL Group."+ecPilGroupId);
                            $('#remove_'+id).remove();
                            //window.location.reload();
                        }else{
                            alert("There is some problem while removing PIL from this PIL Group");
                        }
                        updateCSRFToken();
                    },
                    error: function(data) {
                        alert(data);
                        updateCSRFToken();
                    }
                });
            }else{

            }

        }
    </script>

    <script>
        $(function() {
            $("#example1").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false
                
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
            // $('#example2').DataTable({
            //     "paging": true,
            //     "lengthChange": false,
            //     "searching": false,
            //     "ordering": true,
            //     "info": true,
            //     "autoWidth": false,
            //     "responsive": true,
            // });
        });
    </script>

     

