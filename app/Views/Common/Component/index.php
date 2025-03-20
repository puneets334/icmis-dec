<?php if (!empty($component) && $component == 'component_diary_with_case') { ?>
    <?php $case_checked = '';
    $diary_checked = '';
    if ($component_type == 'C') {
        $case_checked = 'checked';
    } else if ($component_type == 'D') {
        $diary_checked = 'checked';
    } else {
        $diary_checked = 'checked';
    }
    ?>
    <div class="row">
        <div class="col-md-2 diary_section_action">
            <div class="form-group clearfix">
                <div class="icheck-primary d-inline">
                    <input type="radio" class="search_type" id="search_type_d" name="search_type" value="D" <?= $diary_checked; ?>>
                    <label for="search_type_d">Diary Detail</label>
                </div>
            </div>
        </div>
        <div class="col-md-2 casetype_section_action">
            <div class="form-group clearfix">
                <div class="icheck-primary d-inline">
                    <input type="radio" class="search_type" id="search_type_c" name="search_type" value="C" <?= $case_checked; ?>>
                    <label for="search_type_c">Case Detail</label>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 diary_section">
            <div class="form-group">
                <label for="diary_number" class="col-form-label">Diary No</label>
                <input type="number" class="form-control <?= \Config\Services::validation()->getError('diary_number') ? 'is-invalid' : '' ?>" id="diary_number" name="diary_number"
                value="<?= old('diary_number') ?>" placeholder="Enter Diary No">
                <?php if (\Config\Services::validation()->hasError('diary_number')) : ?>
                    <div class="invalid-feedback d-block">
                        <?= \Config\Services::validation()->getError('diary_number') ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="col-md-4 diary_section">
            <div class="form-group">
                <label for="diary_year" class="col-form-label">Diary Year</label>
                <?php $year = 1950;
                $current_year = date('Y');
                ?>
                <select name="diary_year" id="diary_year" class="custom-select rounded-0">
                    <option value="">--Select--</option>
                    <?php for ($x = $current_year; $x >= $year; $x--) { ?>
                        <option <?php echo ($x == $current_year) ? 'selected' : ''; ?> value="<?php echo $x; ?>"><?php echo $x; ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 casetype_section">
            <div class="form-group">
                <label for="case_type" class="col-sm-5 col-form-label">Case type</label>
                <select name="case_type" id="case_type" class="custom-select rounded-0select2" style="width: 100%;">
                    <option value="">Select case type</option>
                    <?php $casetype_arrya = get_from_table_json('casetype');
                    $casetype = array_SORT_ASC_DESC($casetype_arrya, 'casecode');
                    foreach ($casetype as $row) {
                        echo '<option value="' . sanitize(($row['casecode'])) . '">' . sanitize(strtoupper($row['casename'])) . '</option>';
                    }
                    ?>
                </select>
                <?php if (\Config\Services::validation()->hasError('case_type_casecode')) : ?>
                    <div class="invalid-feedback d-block">
                        <?= \Config\Services::validation()->getError('case_type_casecode') ?>
                    </div>
                <?php endif; ?>
            </div>

        </div>
        <div class="col-md-4 casetype_section">
            <div class="form-group">
                <label for="case_number" class="col-sm-5 col-form-label">Case No. </label>
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

        </div>
        <div class="col-md-4 casetype_section">
            <div class="form-group">
                <label for="case_year" class="col-sm-5 col-form-label">Case Year</label>
                <?php $year = 1950;
                $current_year = date('Y');
                ?>
                <select name="case_year" id="case_year" class="custom-select rounded-0">
                    <option value="">--Select--</option>
                    <?php for ($x = $current_year; $x >= $year; $x--) { ?>
                        <option><?php echo $x; ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
    </div>
    <script>
        search_type('<?= $component_type; ?>');

        function search_type(search_type) {
            if (search_type == 'C') {
                $('.casetype_section_action').show();
                $('.diary_section_action').hide();
                $('.casetype_section').show();
                $('.diary_section').hide();
            } else if (search_type == 'D') {
                $('.casetype_section_action').hide();
                $('.diary_section_action').show();
                $('.casetype_section').hide();
                $('.diary_section').show();
            } else {
                $('.casetype_section_action').show();
                $('.diary_section_action').show();
                $('.casetype_section').hide();
                $('.diary_section').show();
            }
        }
    </script>
    <script>
        $(document).ready(function() {
            $(document).on('click', '.search_type', function() {
               // alert('dddd');
                var search_type = $("input[name=search_type]:checked").val();
                if (search_type == 'C') {
                    $('.casetype_section').show();
                    $('.diary_section').hide();
                    $('.diary_section input').val('');
                    $('.diary_section select').val('');
                    $('#case_year').prop('selectedIndex', 1);

                } else {
                    $('#report_result').show('');
                    $('#report_result').html('');
                    $('.casetype_section').hide();
                    $('.diary_section').show();
                    $('.casetype_section input').val('');
                    $('.casetype_section select').val('');
                    $('#diary_year').prop('selectedIndex', 1);
                }
                //alert('search_type='+search_type);
            });
        });
    </script>
    <!--end  component_diary_with_case-->
<?php } ?>