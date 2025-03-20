<?= view('header') ?>
 
<style>
 
</style>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">

                        <div class="row">
                            <div class="col-sm-10"> <h3 class="card-title">Caveat >> Search</h3></div>
                            <div class="col-sm-2"> </div>
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
                                    echo form_open(base_url('Caveat/Search/'), $attribute);
                                    ?>
                                    <div class="row">
                                        <div class="col-md-2"></div>
                                      <div class="col-md-3">
                                            <div class="form-group row">
                                                <label for="inputEmail3" class="col-sm-5 col-form-label">Caveat No</label>
                                                <div class="col-sm-7">
                                                    <input type="text" class="form-control" id="caveat_number" name="caveat_number" placeholder="Enter Caveat No" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group row">
                                                <label for="inputEmail3" class="col-sm-5 col-form-label">Caveat Year</label>
                                                <div class="col-sm-5">
                                                    <?php $year = 1950;
                                                    $current_year = date('Y');
                                                    ?>
                                                    <select name="caveat_year" id="caveat_year" class="custom-select" required>
                                                        <?php for ($x = $current_year; $x >= $year; $x--) { ?>
                                                            <option><?php echo $x; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                         </div>

                                        <div class="col-md-2">
                                            <button type="submit" class="btn btn-primary" id="submit">Search</button>
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
 <?=view('sci_main_footer') ?>