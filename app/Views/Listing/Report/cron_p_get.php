<script>
    var excluded_dates = <?php echo json_encode($holiday_dates) ?>;
    $(document).on("focus", ".dtp", function() {
        $('.dtp').datepicker({
            dateFormat: 'dd-mm-yy',
            changeMonth: true,
            changeYear: true,
            minDate: '+1',
            yearRange: '-0:+1',
            beforeShowDay: function(date) {
                date = $.datepicker.formatDate('yy-mm-dd', date);
                var excluded = $.inArray(date, excluded_dates) > -1;
                return [!excluded, ''];
            }
        });
    });
</script>
<?php $t = ''; ?>
<script src="<?php echo base_url('listing/proposal.js?cache_buster='.@$t.''); ?>"></script>
<div class="container mt-5">
    <form>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <!-- Card to hold the form -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title text-center">D.A. : Proposal</h5>
                        <div class="form-group">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="rdbtn_select" id="radioct">
                                <label class="form-check-label" for="radioct">Case Type:</label>
                            </div>
                            <select id="selct" class="form-control form-control-sm" disabled>
                                <option value="-1">Select</option>
                                <?php foreach ($ct_q as $ct_rw) { ?>
                                    <option value="<?php echo $ct_rw['casecode']; ?>"><?php echo $ct_rw['short_description']; ?></option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="form-group row">
                            <label for="case_no" class="col-sm-3 col-form-label">Case No.:</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control form-control-sm" id="case_no" maxlength="5" disabled />
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="case_yr" class="col-sm-3 col-form-label">Year:</label>
                            <div class="col-sm-9">
                                <?php
                                $currently_selected = date('Y');
                                $earliest_year = 1950;
                                $latest_year = date('Y');
                                ?>
                                <select id="case_yr" class="form-control form-control-sm" disabled>
                                    <?php foreach (range($latest_year, $earliest_year) as $i) { ?>
                                        <option value="<?php echo $i; ?>" <?php echo ($i === $currently_selected ? 'selected' : ''); ?>><?php echo $i; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="rdbtn_select" id="radiodn" checked>
                            <label class="form-check-label" for="radiodn">OR</label>
                        </div>

                        <div class="form-group row">
                            <label for="dno" class="col-sm-3 col-form-label">Diary No.:</label>
                            <div class="col-sm-9">
                                <input type="text" id="dno" class="form-control form-control-sm" size="4" value="" />
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="dyr" class="col-sm-3 col-form-label">Year:</label>
                            <div class="col-sm-9">
                                <?php
                                $currently_selected = date('Y');
                                $earliest_year = 1950;
                                $latest_year = date('Y');
                                ?>
                                <select id="dyr" class="form-control form-control-sm">
                                    <?php foreach (range($latest_year, $earliest_year) as $i) { ?>
                                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <!-- Button to submit form -->
                        <div class="text-center">
                            <input type="button" class="btn btn-primary btn-sm" name="btnGetR" value="GET DETAILS">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Result display area -->
        <div id="dv_res1" class="mt-4"></div>
    </form>
</div>