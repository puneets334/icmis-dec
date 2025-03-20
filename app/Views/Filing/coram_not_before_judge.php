<?=view('header'); ?>
 
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header heading">

                            <div class="row">
                                <div class="col-sm-10">
                                    <h3 class="card-title">Filing</h3>
                                </div>
                                 <?=view('Filing/filing_filter_buttons'); ?>
                            </div>
                        </div>
                        <?=view('Filing/filing_breadcrumb'); ?>
                        <!-- /.card-header -->

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header p-2" style="background-color: #fff;">
                                        <!-- <ul class="nav nav-pills">
                                            <li class="nav-item"><a id="coramA" class="nav-link active" href="#coram" data-toggle="tab">Coram</a></li>
                                            <li class="nav-item"><a id="nbj" class="nav-link" href="#not_before_judge" data-toggle="tab">Not before judges</a></li>
                                        </ul> -->                                        
                                        <?php
                                            $attribute = array('class' => 'form-horizontal', 'name' => 'subordinate_court_details', 'id' => 'subordinate_court_details', 'autocomplete' => 'off');
                                            echo form_open('#', $attribute);

                                        ?>
                                    </div><!-- /.card-header -->
                                    <div class="card-body">
                                        <!-- <div class="tab-content">

                                        
                                            </div> -->

                                            <div class="active tab-pane" id="not_before_judge">
                                                <h4 class="basic_heading"> Not before judge </h4><br>

                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <table class="table table-hover showData">
                                                            <thead>
                                                              <tr>
                                                                <th>S.No.</th>
                                                                <th>Court</th>
                                                                <th>State</th>
                                                                <th>Bench</th>
                                                                <th>Case No.</th>
                                                                <th>Order Date</th>
                                                                <th>Not List Before</th>
                                                                <th>Update</th>
                                                              </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                    if(!empty($not_before_judge)){
                                                                        $i=1; 
                                                                        foreach($not_before_judge as $not_before_judge_val):
                                                                ?>  
                                                                  <tr id="tr_<?php echo $i; ?>">
                                                                    <td><?php echo $i; ?></td>
                                                                    <td><?php echo $not_before_judge_val['court_name']; ?></td>
                                                                    <td><?php echo $not_before_judge_val['name']; ?></td>
                                                                    <input type="hidden" id="hd_jud_id<?php echo $i;?>" value="<?php echo $not_before_judge_val['supreme_court_jud_id'];?>">
                                                                    <td><?php echo $not_before_judge_val['agency_name']; ?></td>
                                                                    <td><?php echo $not_before_judge_val['type_sname'].'-'.$not_before_judge_val['lct_caseno'].'-'.$not_before_judge_val['lct_caseyear']; ?></td>
                                                                    <td><?php echo date('d-m-Y',strtotime($not_before_judge_val['lct_dec_dt'])); ?></td>
                                                                    <td><span id="sp_names<?php echo $i; ?>"><?php echo $not_before_judge_val['first_name'].' '.$not_before_judge_val['sur_name']; ?></span></td>
                                                                    <td><button type="button" id="<?php echo $i; ?>" class="btn btn-default cl_submit">Submit</button></td>
                                                                  </tr>
                                                                <?php
                                                                        $i++;
                                                                        endforeach;
                                                                    }else{
                                                                ?>        
                                                                    <tfoot>
                                                                      <tr>
                                                                          <td colspan="8"><center>No Record Found</center></td>
                                                                      </tr>
                                                                    </tfoot>
                                                                <?php
                                                                    }
                                                                ?>
                                                            </tbody>
                                                          </table>
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

        $(function () {
            $("#example1").DataTable({
              "responsive": true, "lengthChange": false, "autoWidth": false,
              "buttons": ["copy", "csv", "excel", "pdf", "print"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        });


        $('.cl_submit').click(function(){

            var CSRF_TOKEN = 'CSRF_TOKEN';
            var csrf = $("input[name='CSRF_TOKEN']").val();

            var diary_no = "<?php echo $diary_no; ?>";
            var idd = $(this).attr('id');
            var hd_jud_id=$('#hd_jud_id'+idd).val();
            var sp_names=$('#sp_names'+idd).val();
            
            var cnf_r=confirm("Are you sure you want to add Diary No. "+diary_no+" not before "+sp_names);

            if(cnf_r==true){

                $.ajax({
                    url:"<?php echo base_url('Filing/Coram/not_before_judge_sub/');?>",
                    type: "post",
                    data: {CSRF_TOKEN:csrf,diary_no: diary_no,hd_jud_id: hd_jud_id },
                    success:function(result){
                        
                        var obj = JSON.parse(result);
                        
                        if(obj.nbj_inserted){
                            alert(obj.nbj_inserted);
                            window.location.href='';
                        }

                        if(obj.nbj_already_inserted){
                            alert(obj.nbj_already_inserted);
                            window.location.href='';
                        }

                        $.getJSON("<?php echo base_url('Csrftoken'); ?>", function (result) {
                            $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                        });
                    },
                    error: function () {
                        $.getJSON("<?php echo base_url('Csrftoken'); ?>", function (result) {
                            $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                        });
                    }
                });

            }
        });


    </script>