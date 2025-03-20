<?= view('header') ?>
 
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header heading">

                            <div class="row">
                                <div class="col-sm-10">
                                    <h3 class="card-title">Coram</h3>
                                </div>
                                <div class="col-sm-2">
                                    <div class="custom_action_menu">
                                        <a href="<?= base_url() ?>/Filing/Diary"><button class="btn btn-success btn-sm" type="button"><i class="fa fa-plus-circle" aria-hidden="true"></i></button></a>
                                        <a href="<?= base_url() ?>/Filing/Diary/search"><button class="btn btn-primary btn-sm" type="button"><i class="fas fa-pencil" aria-hidden="true"></i></button></a>
                                        <a href="<?= base_url() ?>/Filing/Diary/deletion"><button class="btn btn-danger btn-sm" type="button"><i class="fa fa-trash" aria-hidden="true"></i></button></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?=view('Coram/coram_breadcrumb'); ?>
                        <!-- /.card-header -->

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header p-2" style="background-color: #fff;">                                        
                                        <?php
                                            $attribute = array('class' => 'form-horizontal', 'name' => 'coram', 'id' => 'coram', 'autocomplete' => 'off');
                                            echo form_open('#', $attribute);

                                        ?>
                                    </div><!-- /.card-header -->
                                    <div class="card-body">
                                        <div class="tab-content">

                                            <div class="active tab-pane">
                                                <h3 class="basic_heading"> Registrar Coram Shifting Module </h3><br>
                                            <div class="row">

                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        <label class="col-sm-2 col-form-label">Registrar</label>
                                                        <div class="col-sm-10">
                                                            <select id="judge" name="judge" class="custom-select rounded-0">
                                                                <option value="">Select Judge</option>
                                                                <?php foreach($judge_list as $judge_val): ?>
                                                                    <option value="<?php echo $judge_val['jcode'];?>"><?php echo $judge_val['jname']." (".$judge_val['first_name']." ".$judge_val['sur_name'].") - Total Cases ".$judge_val['total_cases']; ?></option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        <label class="col-sm-2 col-form-label">Judge To</label>
                                                        <div class="col-sm-10">
                                                            <select id="judge_to" name="judge_to" class="custom-select rounded-0">
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <center><input type="button" name="btngetr" id="btngetr" class="btn btn-primary" value="Submit"></center>
                                                </div>
                                            </div>
                                            </div>
                                            
                                        </div>
                                        <!-- /.tab-content -->
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                                <!-- /.card -->
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


    <script type="text/javascript">

        $(document).on("change","#judge",function(){
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var csrf = $("input[name='CSRF_TOKEN']").val();

            var judge = $("#judge").val();

            if(judge == 0){
                alert("Please Select Judge Name")
                $("#judge").focus()
                return false
            }

            $.ajax({
                url:"<?php echo base_url('Coram/Registrar_coram_shifting/reg_remove_coram/');?>",
                type: "post",
                data: {CSRF_TOKEN:csrf,judge: judge},
                success:function(result){
                    $('#judge_to').html(result);
                    updateCSRFToken();
                },
                error: function () {
                    alert('Error while saving data.');
                    updateCSRFToken();
                }
            });
        });

        $(document).on("click","#btngetr",function(){
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var csrf = $("input[name='CSRF_TOKEN']").val();

            var judge = $("#judge").val();
            var judge_to = $("#judge_to").val();

            if(judge == 0){
                alert("Please Select Judge Name")
                $("#judge").focus()
                return false
            }

            if(judge_to == 0){
                alert("Please Select Judge To")
                $("#judge_to").focus()
                return false
            }

            $.ajax({
                url:"<?php echo base_url('Coram/Registrar_coram_shifting/reg_do_remove_coram/');?>",
                type: "post",
                data: {CSRF_TOKEN:csrf,judge: judge,judge_to: judge_to},
                success:function(result){
                    if(result > 0){
                        alert('Successfully shifted');
                    }else{
                        alert('Not shifted');
                    }
                    updateCSRFToken();
                    window.location.href='';
                },
                error: function () {
                    alert('Error while saving data.');
                    updateCSRFToken();
                }
            });
        });

    </script>
