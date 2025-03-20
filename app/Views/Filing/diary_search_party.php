<?=view('header'); ?>
 
<style>
 
</style>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">

                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Filing >> Diary Search</h3>
                            </div>
                            <div class="col-sm-2">
                              
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
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

                                    <?php  //echo $_SESSION["captcha"];
                                    $attribute = array('class' => 'form-horizontal','name' => 'diary_search', 'id' => 'diary_search', 'autocomplete' => 'off');
                                    echo form_open(base_url($formAction), $attribute);
                                    ?>

                                    <div class="row">
                                        <div class="col-md-1"></div>
                                        <div class="col-md-2">
                                            <div class="form-group clearfix">
                                                <div class="icheck-primary d-inline">
                                                    <input type="radio" class="search_type" id="search_type_d" name="search_type" value="D" checked>
                                                    <label for="search_type_d">
                                                        Diary
                                                    </label>
                                                </div>
                                                <div class="icheck-primary d-inline">
                                                    <input type="radio" class="search_type" id="search_type_c" name="search_type" value="C">
                                                    <label for="search_type_c">
                                                        Registration
                                                    </label>
                                                </div>

                                            </div>
                                        </div>
                                      <div class="col-md-3 diary_section">
                                            <div class="form-group row">
                                                <label for="inputEmail3" class="col-sm-5 col-form-label">Diary No</label>
                                                <div class="col-sm-7">
                                                    <input type="number" class="form-control" id="diary_number" name="diary_number" placeholder="Enter Diary No" >
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 diary_section">
                                            <div class="form-group row">
                                                <label for="inputEmail3" class="col-sm-5 col-form-label">Diary Year</label>
                                                <div class="col-sm-5">
                                                    <?php $year = 1950;
                                                    $current_year = date('Y');
                                                    ?>
                                                    <select name="diary_year" id="diary_year" class="custom-select rounded-0">
                                                        <?php for ($x = $current_year; $x >= $year; $x--) { ?>
                                                            <option><?php echo $x; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                         </div>
                                        <div class="col-md-3 casetype_section" style="display: none;">
                                            <div class="form-group row">
                                                <label for="inputEmail3" class="col-sm-5 col-form-label">Case type</label>
                                                <div class="col-sm-7">
                                                    <select name="case_type_casecode" id="case_type_casecode" class="custom-select rounded-0 select2" style="width: 100%;">
                                                        <option value="">Select case type</option>
                                                        <?php
                                                        foreach ($casetype as $row) {
                                                            echo'<option value="' . sanitize(($row['casecode'])) . '">' . sanitize(strtoupper($row['casename'])) . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                        </div>
                                            <div class="col-md-2 casetype_section" style="display: none;">

                                                <div class="form-group row ">
                                                    <label for="inputEmail3" class="col-sm-5 col-form-label">Case No</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" id="case_number" name="case_number" placeholder="Enter Case No" >
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2 casetype_section" style="display: none;">
                                                <div class="form-group row">
                                                    <label for="inputEmail3" class="col-sm-5 col-form-label">Case Year</label>
                                                    <div class="col-sm-5">
                                                        <?php $year = 1950;
                                                        $current_year = date('Y');
                                                        ?>
                                                        <select name="case_year" id="case_year" class="custom-select rounded-0">
                                                            <?php for ($x = $current_year; $x >= $year; $x--) { ?>
                                                                <option><?php echo $x; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                        <div class="col-md-2">
                                            <button type="submit" class="btn btn-primary" id="submit">Submit</button>
                                        </div>
                                        <div class="col-md-2"></div>
                                    </div>
                                    <?php form_close();?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
    <script>
        $(document).ready(function() {
            $(document).on('click', '.search_type', function() {
            //alert('dddd');
            var search_type = $("input[name=search_type]:checked").val();
            if (search_type=='C'){
                $('.casetype_section').show();
                $('.diary_section').hide();
            }else {
                $('.casetype_section').hide();
                $('.diary_section').show();
            }
            //alert('search_type='+search_type);
        });
        });
        </script>