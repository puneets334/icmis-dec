<?= view('header') ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">

                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Judicial >> Search</h3>
                            </div>
                            <div class="col-sm-2">

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <?php  //echo $_SESSION["captcha"];
                                $attribute = array('class' => 'form-horizontal', 'name' => 'diary_search', 'id' => 'diary_search', 'autocomplete' => 'off');
                                echo form_open(base_url($formAction), $attribute);
                                ?>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <?php if (session()->getFlashdata('error')) { ?>
                                                <div class="alert alert-danger text-white ">
                                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                                    <?= session()->getFlashdata('error') ?>
                                                </div>
                                            <?php } else if (session("message_error")) { ?>
                                                <div class="alert alert-danger">
                                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                                    <?= session()->getFlashdata("message_error") ?>
                                                </div>
                                            <?php } else { ?>
                                                <br />
                                            <?php } ?>
                                            <span class="alert-danger"><?= \Config\Services::validation()->listErrors() ?></span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="form-group clearfix">
                                                <?php
                                                if(!isset($search_type)){
                                                    $search_type = old('search_type', 'D');                                                 
                                                }   
                                                
                                                ?>
                                                
                                                <div class="icheck-primary mb-2">
                                                    <input type="radio" class="search_type" id="search_type_d" name="search_type" value="D" <?= ($search_type == 'D') ? ' checked' : ''; ?>>
                                                    <label for="search_type_d">
                                                        Diary
                                                    </label>
                                                </div>
                                                <div class="icheck-primary mb-2">
                                                    <input type="radio" class="search_type" id="search_type_c" name="search_type" value="C" <?= ($search_type == 'C') ? ' checked' : ''; ?>>
                                                    <label for="search_type_c">
                                                        Case Type
                                                    </label>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-md-4 diary_section">
                                            <label for="diary_number" class="col-form-label">Diary No</label>
                                            <input type="number" class="form-control"
                                                id="diary_number" name="diary_number"
                                                value="<?= old('diary_number') ?>"
                                                placeholder="Enter Diary No">
                                            <?php if (\Config\Services::validation()->hasError('diary_number')) : ?>
                                                <div class="invalid-feedback d-block">
                                                    <?= \Config\Services::validation()->getError('diary_number') ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-md-4 diary_section">
                                            <label for="inputEmail3" class="col-sm-6 col-form-label">Diary Year</label>
                                            <?php 
                                            $year = 1950;
                                            $diary_year = old('diary_year');
                                            $current_year = date('Y');
                                            ?>
                                            <select name="diary_year" id="diary_year" class="custom-select rounded-0">
                                                <?php for ($x = $current_year; $x >= $year; $x--) { ?>
                                                    <option <?= ($diary_year == $x) ? ' selected' : ''; ?>><?php echo $x; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="col-md-3 casetype_section" style="display: none;">
                                            <label for="case_type_casecode" class="col-form-label">Case type</label>
                                            <select name="case_type_casecode" id="case_type_casecode" class="custom-select rounded-0 select2" style="width: 100%;">
                                                <option value="">Select case type</option>
                                                <?php
                                                $case_type_casecode = old('case_type_casecode');
                                                if(!empty($casetype)){
                                                        foreach ($casetype as $row) {
                                                            $selected_case_type_casecode =  ($case_type_casecode == $row['casecode']) ? ' selected' : '';
                                                            echo '<option value="' . sanitize(($row['casecode'])) . '" ' . $selected_case_type_casecode . '>' . sanitize(strtoupper($row['casename'])) . '</option>';
                                                        }
                                                    }
                                                ?>
                                            </select>

                                            <?php if (\Config\Services::validation()->hasError('case_type_casecode')) : ?>
                                                <div class="invalid-feedback d-block">
                                                    <?= \Config\Services::validation()->getError('case_type_casecode') ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-md-3 casetype_section" style="display: none;">
                                            <label for="inputEmail3" class="col-sm-5 col-form-label">Case No</label>
                                            <input type="number" class="form-control <?= \Config\Services::validation()->getError('case_number') ? 'is-invalid' : '' ?>"
                                                id="case_number" name="case_number"
                                                value="<?= old('case_number') ?>"
                                                placeholder="Enter Case No">
                                            <?php if (\Config\Services::validation()->hasError('case_number')) : ?>
                                                <div class="invalid-feedback d-block">
                                                    <?= \Config\Services::validation()->getError('case_number') ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-md-3 casetype_section" style="display: none;">
                                            <label for="inputEmail3" class="col-sm-5 col-form-label">Case Year</label>
                                            <?php $year = 1950;
                                            $case_year = old('case_year');
                                            $current_year = date('Y');
                                            ?>
                                            <select name="case_year" id="case_year" class="custom-select rounded-0">
                                                <?php for ($x = $current_year; $x >= $year; $x--) { ?>
                                                    <option <?= ($case_year == $x) ? ' selected' : ''; ?>><?php echo $x; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary" id="submit">Submit</button>
                                </div>
                                <?php form_close(); ?>
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
            if (search_type == 'C') {
                $('.casetype_section').show();
                $('.diary_section').hide();
                $('#case_type_casecode').prop('required', true);
                $('#diary_number').prop('required', false);
            } else {
                $('.casetype_section').hide();
                $('.diary_section').show();
                $('#case_type_casecode').prop('required', false).removeClass('is-invalid');
                $('#diary_number').prop('required', true);
            }
            //alert('search_type='+search_type);
        });
        $('form').on('submit', function(e) {
            
            $('#submit').html("Loading...").prop('disabled', true);

            var search_type = $("input[name=search_type]:checked").val();
            if (search_type == 'C') {
                var caseType = $('#case_type_casecode').val();
                if (!caseType) {
                    e.preventDefault(); // Prevent form submission
                    $('#case_type_casecode').addClass('is-invalid');
                    $('#case_type_casecode-error').remove(); // Remove existing error if any
                    $('#case_type_casecode').after('<div id="case_type_casecode-error" class="invalid-feedback">Please select a case type.</div>');
                }
            }
        });

        $("input[name=search_type]:checked").click();
    });
</script>