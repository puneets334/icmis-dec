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

        .table thead th,
        .table th {
            width: 50%;
        }
        .basic_heading {
            text-align: center;
            color: #31B0D5;
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
                                <div class="col-sm-9">
                                    <h3 class="card-title">Caveat >> Similarities</h3>
                                </div>
                                <div class="col-sm-3">
                                    <a href="<?=base_url('Caveat/Generation');?>"><button class="btn btn-success btn-sm" type="button"><i class="fa fa-plus-circle" aria-hidden="true"></i></button></a>
                                    <a href="<?=base_url('Caveat/Search');?>"><button class="btn btn-info btn-sm" type="button"><i class="fa fa-search-plus	" aria-hidden="true"></i></button></a>
                                </div>
                                
                            </div>
                        </div>
                        <?=view('Caveat/caveat_breadcrumb');?>
                        <br/>
                        <?php
                        if (!empty($flag) && $flag=='D'){
                            $flag_value='Diary';
                        }else{ $flag_value='Caveat'; }
                        $caveat_details= session()->get('caveat_details'); $caveat_no=$caveat_year='';
                        //echo '<pre>';print_r($caveat_details);
                        if (!empty($caveat_details)){
                            $caveat_no=substr($caveat_details['caveat_no'], 0, -4);
                            $caveat_year=substr($caveat_details['caveat_no'],-4);
                        }else{
                            if (!empty($param)){
                                $caveat_no=trim($param['caveat_number']);
                                $caveat_year=trim($param['caveat_year']);
                            }
                        }
                        $caveat_number=$caveat_no.$caveat_year;
                        $attribute = array('class' => 'form-horizontal','name' => 'diary_search', 'id' => 'diary_search', 'autocomplete' => 'off');
                        //echo form_open(base_url('Caveat/Similarity'), $attribute);
                        echo form_open(base_url('#'), $attribute);
                        ?>
                        <div class="row d-none">
                            <div class="col-md-2"></div>
                            <div class="col-md-3">
                                <div class="form-group row">
                                    <label for="caveat_number" class="col-sm-5 col-form-label"> <?=$flag_value;?> No</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="caveat_number" name="caveat_number" value="<?=$caveat_no;?>" placeholder="Enter <?=$flag_value;?> No" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group row">
                                    <label for="caveat_year" class="col-sm-5 col-form-label"><?=$flag_value;?> Year</label>
                                    <div class="col-sm-7">
                                        <?php $sel = ''; $year = 1950;
                                        $current_year = date('Y');
                                        ?>
                                        <select name="caveat_year" id="caveat_year" class="custom-select" required>
                                            <?php for ($x = $current_year; $x >= $year; $x--) { if ((!empty($caveat_year) &&  $x==$caveat_year)) {  $sel = 'selected=selected';  }else{$sel='';} ?>
                                                <option <?=$sel?> value="<?=$x?>"><?php echo $x; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <!--<button type="submit" class="btn btn-primary" id="submit">Search</button>-->
                                <button type="button" class="btn btn-primary" id="submit" onclick="getDetails()">Search</button>
                            </div>
                            <div class="col-md-2"></div>
                        </div>
                        <?php form_close();?>

                        <div class="row">
                            <center>
                                <span class="alert-danger"><?=\Config\Services::validation()->listErrors()?></span>

                                <?php if(session()->getFlashdata('error')){ ?>
                                    <div class="alert alert-danger text-white ">
                                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                        <?= session()->getFlashdata('error')?>
                                    </div>
                                <?php } else if(session("message_error")){ ?>
                                    <div class="alert alert-danger">
                                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                        <?=session()->getFlashdata("message_error")?>
                                    </div>
                                <?php }else{?>
                                    <br/>
                                <?php }?>
                            </center>
                        </div>
                        <div id="div_result"></div>

                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
    </section>
<?php if (!empty($flag) && $flag=='D'){?>
    <script src="<?php echo base_url('filing/similarity_caveat_report_d.js'); ?>"></script>
    <?php }else{?>
    <script src="<?php echo base_url('filing/similarity_caveat_report.js'); ?>"></script>
    <?php }?>
    <script>
        <?php if (!empty($caveat_number) && $caveat_number !=null){?>
        getDetails();
        <?php } ?>
        $(function() {
            $(".table").DataTable({
                "paging": false,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": true,
                "responsive": true,
            });

        });
    </script>
 <? //=view('sci_main_footer') ?>