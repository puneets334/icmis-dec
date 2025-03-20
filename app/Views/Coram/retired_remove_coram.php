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
                                                <h3 class="basic_heading"> Remove Coram on Retirement of Judge MODULE </h3><br>
                                            <div class="row ">

                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        <label class="form-label">Judge</label>
                                                        <!-- <div class="col-sm-10"> -->
                                                            <select id="judge" name="judge" class="custom-select rounded-0">
                                                                <option value="">Select Judge</option>
                                                                <?php foreach($judge_list as $judge_val): ?>
                                                                    <option value="<?php echo $judge_val['jcode'];?>"><?php echo $judge_val['jname']." (".$judge_val['abbreviation'].")";?></option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        <!-- </div> -->
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group row">
                                                        <label class="form-label">Include/Exclude Connected</label>
                                                        <!-- <div class="col-sm-6"> -->
                                                            <select id="conn_i_e" name="conn_i_e" class="custom-select rounded-0">
                                                                <option value="1" selected>Include Connected</option>
                                                                <option value="2">Exclude Connected</option>
                                                            </select>
                                                        <!-- </div> -->
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group row">
                                                        <label class="form-label">Detail</label>
                                                        <!-- <div class="col-sm-8"> -->
                                                            <select id="crm_dtl" name="crm_dtl" class="custom-select rounded-0">
                                                                <option value="0">ALL</option>                    
                                                                <option value="1">Coram Given by CJI</option>
                                                                <option value="2">Special Bench Coram Given by CJI</option>
                                                                <option value="3">Special Bench</option>
                                                                <option value="4">Part Heard</option>
                                                                <option value="5">Other</option>
                                                            </select>
                                                        <!-- </div> -->
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <center><input type="button" name="btngetr" id="btngetr" class="btn btn-primary" value="Get"></center>
                                                </div>
                                            </div>
                                            </div>

                                            <hr><br>
                                            <div id class="">
                                                <!-- <h3 class="basic_heading"> ADVOCATE ON RECORD NOT GO BEFORE JUDGE<br>(As on <?php echo date('d-m-Y');?>) </h3><br> -->
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <span id="show_btn" style="display:none;"><input type="button" name="rem_corm" id="rem_corm" class="btn btn-info" value="Remove Coram"></span><br>
                                                        <div id="show_tbl">
                                                            
                                                        </div>
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

        $(document).on("click","#btngetr",function(){
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var csrf = $("input[name='CSRF_TOKEN']").val();

            var judge = $("#judge").val();
            var conn_i_e = $("#conn_i_e").val();
            var crm_dtl = $("#crm_dtl").val();

            if(judge == 0){
                alert("Please Select Judge Name")
                $("#judge").focus()
                return false
            }

            $.ajax({
                url:"<?php echo base_url('Coram/Retired_remove_coram/remove_coram/');?>",
                type: "post",
                data: {CSRF_TOKEN:csrf,judge:judge,crm_dtl:crm_dtl,conn_i_e:conn_i_e},
                success:function(result){

                    if(result=='No Recrods Found'){
                        alert(result);
                        window.location.href='';
                    }else{
                        $('#show_btn').css({"display":"block"});
                        $('#show_tbl').html(result);
                        $("#example1").DataTable({
                          "responsive": true, "lengthChange": false, "autoWidth": false,
                          "buttons": ["copy", "csv", "excel", "pdf", "print"]
                        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
                    }
                    updateCSRFToken();
                },
                error: function () {
                    alert('Error while getting data.');
                    updateCSRFToken();
                }
            });
        });

        $(document).on("click","#rem_corm",function(){
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var csrf = $("input[name='CSRF_TOKEN']").val();

            var judge = $("#judge").val();
            var crm_dtl = $("#crm_dtl").val();

            if(judge == 0){
                alert("Please Select Judge Name")
                $("#judge").focus()
                return false
            }

            $.ajax({
                url:"<?php echo base_url('Coram/Retired_remove_coram/do_remove_coram/');?>",
                type: "post",
                data: {CSRF_TOKEN:csrf,judge:judge,crm_dtl:crm_dtl},
                success:function(result){

                    alert(result)
                    window.location.href='';
                    updateCSRFToken();
                },
                error: function () {
                    alert('Error while getting data.');
                    updateCSRFToken();
                }
            });
        });

        $(function () {
            $("#example1").DataTable({
              "responsive": true, "lengthChange": false, "autoWidth": false,
              "buttons": ["copy", "csv", "excel", "pdf", "print"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        });


    </script>


 <?//=view('sci_main_footer') ?>