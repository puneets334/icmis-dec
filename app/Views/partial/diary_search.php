<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">

                        <div class="row">
                            <div class="col-md-10">
                                <h3 class="card-title"><?php echo $sectionHeading; ?></h3>
                            </div>
                            <div class="col-md-2">
                              
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <span class="alert-danger"><?=\Config\Services::validation()->listErrors()?></span>
                                    
                                    <div class="alert alert-danger d-none" id="errors">
                                        <a href="#" class="close" aria-label="close" onclick="$('#errors').toggleClass('d-none');">&times;</a>
                                        <div id="error_text"></div>
                                    </div>

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
                                        <div class="col-md-12 h-100 d-flex">
                                            <div class="form-group clearfix">
                                                <div class="icheck-primary d-inline" style="margin-right:50px">
                                                    <input type="radio" class="search_type" id="search_type_d" name="search_type" value="D" checked>
                                                    <label for="search_type_d">
                                                        Diary
                                                    </label>
                                                </div>
                                                <div class="icheck-primary d-inline" style="margin-left:50px">
                                                    <input type="radio" class="search_type" id="search_type_c" name="search_type" value="C">
                                                    <label for="search_type_c">
                                                        Case Type
                                                    </label>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-80">
                                      <div class="col-md-3 diary_section">
                                            <div class="form-group">
                                                <label for="inputEmail3" class="">Diary No</label>
                                                <div class="">
                                                    <input type="number" class="form-control" id="diary_number" name="diary_number" placeholder="Enter Diary No" >
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 diary_section">
                                            <div class="form-group">
                                                <label for="inputEmail3" class="">Diary Year</label>
                                                <div class="">
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
                                            <div class="form-group">
                                                <label for="inputEmail3" class="">Case type</label>
                                                <div class="">
                                                    <select name="case_type_casecode" id="case_type_casecode" class="custom-select rounded-0 select2" style="width: 100%;">
                                                        <option value="">Select case type</option>
                                                        <?php
                                                        if(!empty($casetype)){
                                                            foreach ($casetype as $row) {
                                                                echo'<option value="' . sanitize(($row['casecode'])) . '">' . sanitize(strtoupper($row['casename'])) . '</option>';
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                        </div>
                                            <div class="col-md-2 casetype_section" style="display: none;">

                                                <div class="form-group ">
                                                    <label for="inputEmail3" class="">Case No</label>
                                                    <div class="">
                                                        <input type="number" class="form-control" id="case_number" name="case_number" placeholder="Enter Case No" >
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2 casetype_section" style="display: none;">
                                                <div class="form-group">
                                                    <label for="inputEmail3" class="">Case Year</label>
                                                    <div class="">
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

                                        <div class="col-md-2 pt-4">
                                            <button type="button" class="btn btn-primary" id="submit" onclick="getDetails()">Get Details</button>
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
        var search_type = $("input[name=search_type]:checked").val();
        if (search_type=='C'){
            $('.casetype_section').show();
            $('.diary_section').hide();
        }else {
            $('.casetype_section').hide();
            $('.diary_section').show();
        }
    });
});
</script>