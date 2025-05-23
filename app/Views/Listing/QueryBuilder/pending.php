<?= view('header') ?>
<style>
    .btn-add-outline-secondary {
    color: rgb(255, 255, 255);
    background-color: rgb(108, 117, 125);
    border-color: rgb(108, 117, 125);
}

.btn-add {
    display: inline-block;
    transition: all 0.5s ease-in-out 0s;
    -webkit-transition: all 0.5s ease-in-out 0s;
    background-color: transparent;
    position: relative;
    overflow: hidden;
    color: #1f2d3d;
    padding: 3px 15px !important;
    line-height: 15px !important;
    font-size: 14px !important;    border: #706d6d 1px solid;
    font-family: 'noto_sansmedium' !important;    border-radius: 0px!important;
}
button.btn.btn-outline-secondary.remove-row {
    border: #706d6d 1px solid;
    border-radius: 0px!important;
    color: #000;
    padding: 0px 15px!important;
}  
button.btn.btn-outline-secondary.remove-row:hover {
    color: #fff;
}   
button.btn-add.btn-add-outline-secondary.add-more:hover {
    background: #022651;
    color: #fff;
} 
</style>
<link href="<?php echo base_url('assets/css/QueryBuilder.css'); ?>" rel="stylesheet">
<div class="wrapper">
    <nav id="sidebar">
        <div id="dismiss">
            <i class="fas fa-arrow-left"></i>
        </div>
        <div class="sidebar-header text-white bg-primary">
            <h4 class="mb-0">Pending Cases Filter</h4>
        </div>
        <form name="parent_form" id="parent_form">
            <?= csrf_field() ?>
            <div class="form-group row p-1">
                <label for="app_date_range" class="col-sm-2 col-form-label">Filing Date</label>
                <div class="col-sm-10 input-group input-daterange" id="app_date_range">
                    <div class="row g-3 align-items-center">
                        <div class="col-auto">
                            <input type="text" class="form-control bg-white" id="from_diary_date" name="from_diary_date"
                                placeholder="From Date..." readonly>
                        </div>
                        <div class="col-auto">
                            <span class="input-group-text">to</span>
                        </div>
                        <div class="col-auto">
                            <input type="text" class="form-control bg-white" id="to_diary_date" name="to_diary_date"
                                placeholder="To Date..." readonly>
                        </div>
                    </div>
                </div>
            </div>
            <fieldset class="form-group p-1">
                <div class="row">
                    <legend class="col-sm-2 col-form-label">Connected</legend>
                    <div class="col-sm-10  input-group">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="connected[]" id="connected_exclude" value="1">
                            <label class="form-check-label" for="connected_exclude">Exclude</label>
                        </div>
                    </div>
                </div>
            </fieldset>
            <fieldset class="form-group p-1">
                <div class="row">
                    <legend class="col-sm-2 col-form-label">Stage</legend>
                    <div class="col-sm-10  input-group">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="mainhead" id="stage_misc" value="M">
                            <label class="form-check-label" for="stage_misc">Misc.</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="mainhead" id="stage_regular" value="F">
                            <label class="form-check-label" for="stage_regular">Regular</label>
                        </div>
                    </div>
                </div>
            </fieldset>
            <fieldset class="form-group p-1">
                <div class="row">
                    <legend class="col-sm-2 col-form-label">Board Type</legend>
                    <div class="col-sm-10  input-group">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="board_type" id="board_type_court" value="J">
                            <label class="form-check-label" for="board_type_court">Court</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="board_type" id="board_type_single_judge" value="S">
                            <label class="form-check-label" for="board_type_single_judge">Single Judge</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="board_type" id="board_type_chamber" value="C">
                            <label class="form-check-label" for="board_type_chamber">Chamber</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="board_type" id="board_type_registrar" value="R">
                            <label class="form-check-label" for="board_type_registrar">Registrar</label>
                        </div>
                    </div>
                </div>
            </fieldset>
            <fieldset class="form-group p-1">
                <div class="row">
                    <legend class="col-sm-2 col-form-label">Status</legend>
                    <div class="col-sm-10  input-group">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="status[]" id="status_updated" value="1">
                            <label class="form-check-label" for="status_updated">Updated</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="status[]" id="status_updation_awaited" value="2">
                            <label class="form-check-label" for="status_updation_awaited">Updation Awaited</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="status[]" id="status_not_ready" value="3">
                            <label class="form-check-label" for="status_not_ready">Not Ready</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="status[]" id="status_listed" value="4">
                            <label class="form-check-label" for="status_listed">Listed</label>
                        </div>
                    </div>
                </div>
            </fieldset>
            <fieldset class="form-group p-1">
                <div class="row">
                   <div class="col-md-2">
                        <legend>Hon'ble Judge</legend>
                   </div>
                   <div class="col-md-3">
                        <div>
                            <input class="form-check-input" type="checkbox" name="only_presiding" id="only_presiding" value="y">
                            <label class="form-check-label" for="only_presiding">Only Presiding</label>
                        </div>
                   </div>
                   <div class="col-md-2">
                        <div>
                            <input class="form-check-input" type="checkbox" name="judge_exclude" id="judge_exclude" value="y">
                            <label class="form-check-label" for="judge_exclude">Exclude</label>
                        </div>
                   </div>
                   <div class="col-md-5">
                        <select class="form-control" multiple id="judge" name="judge[]">
                            <?php if (!empty($Judge)): ?>
                                <?php foreach ($Judge as $judge1): ?>
                                    <option value="<?= $judge1['jcode']; ?>"><?= $judge1['jname']; ?></option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="">No judges available</option>
                            <?php endif; ?>
                        </select>
                   </div>
                    
                </div>
            </fieldset>
            <fieldset class="form-group p-1">
                <div class="row">
                    <legend class="col-form-label col-sm-2 pt-0">Subhead</legend>
                    <div class="col-sm-10 input-group">
                        <div class="form-check pr-2">
                            <input class="form-check-input" type="checkbox" name="subhead_exclude" id="subhead_exclude" value="y">
                            <label class="form-check-label" for="subhead_exclude">
                                Exclude
                            </label>
                        </div>

                        <div class="col-12 col-md-4 pr-2">
                            <select class="form-control" name="subhead[]" id="subhead">
                                <?php if (!empty($getSubheading)) : ?>
                                    <?php foreach ($getSubheading as $row) : ?>
                                        <option value="<?= esc($row['stagecode']) ?>">
                                            <?= esc(trim(str_replace(['[', ']'], '', $row['stagename']))) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <option value="">No subheadings available</option>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                </div>
            </fieldset>
            <fieldset class="form-group p-1">
                <div class="row">
                    <legend class="col-form-label col-sm-2 pt-0">Listing Purpose</legend>
                    <div class="col-sm-10 input-group">
                        <div class="form-check pr-2">
                            <input class="form-check-input" type="checkbox" name="lp_exclude" id="lp_exclude" value="y">
                            <label class="form-check-label" for="lp_exclude">
                                Exclude
                            </label>
                        </div>

                        <div class="col-12 col-md-4 pr-2">
                            <select class="form-control" multiple="multiple" id="lp" name="lp[]">
                                <?php if (!empty($getListPurposes)) : ?>
                                    <?php foreach ($getListPurposes as $row) : ?>
                                        <option value="<?= esc($row['code']) ?>">
                                            <?= esc($row['purpose']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <option value="">No purposes available</option>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                </div>
            </fieldset>
            <div class="form-group row p-1">
                <label for="list_date_range" class="col-sm-2">Tentative Date</label>
                <div class="col-sm-10 input-group input-daterange" id="list_date_range">
                    <div class="row g-3 align-items-center">
                        <div class="col-auto">
                            <input type="text" class="form-control bg-white" id="from_list_date" name="from_list_date"
                                placeholder="From Date..." readonly>
                        </div>
                        <div class="col-auto">
                            <span class="px-2">to</span>
                        </div>
                        <div class="col-auto">
                            <input type="text" class="form-control bg-white" id="to_list_date" name="to_list_date"
                                placeholder="To Date..." readonly>
                        </div>
                    </div>
                </div>
            </div>
            <fieldset class="form-group p-1">
                <div class="row">
                    <div class="col-md-3">
                        <legend>Category</legend>
                    </div>
                    <div class="col-md-3">
                        <div>
                            <input class="form-check-input" type="checkbox" name="category_exclude"
                                id="category_exclude" value="y">
                            <label class="form-check-label" for="category_exclude">
                                Exclude
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <select class="form-control" multiple="multiple" id="category" name="category[]">
                            <?php foreach ($categories as $item) : ?>
                                <?php if (!empty($item['subcategories'])) : ?>
                                    <optgroup label="<?= esc($item['category']['old_sc_c_kk']) . " - " . esc($item['category']['sub_name4']) ?>">
                                        <?php foreach ($item['subcategories'] as $row2) : ?>
                                            <option value="<?= esc($row2['id']) ?>">
                                                <?= esc($row2['old_sc_c_kk']) . " - " . (strlen($row2['sub_name4']) > 40 ? substr(esc($row2['sub_name4']), 0, 40) . "..." : esc($row2['sub_name4'])) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </optgroup>
                                <?php else : ?>
                                    <option value="<?= esc($item['category']['id']) ?>">
                                        <?= esc($item['category']['old_sc_c_kk']) . " - " . esc($item['category']['sub_name4']) ?>
                                    </option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </div>

                </div>
            </fieldset>
            <fieldset class="form-group p-1">
                <div class="row">
                    <div class="col-md-3">
                        <legend>Case Type</legend>
                    </div>
                    <div class="col-md-4">
                        <input class="form-check-input" type="checkbox" name="case_type_exclude"
                            id="case_type_exclude" value="y">
                        <label class="form-check-label" for="case_type_exclude">
                            Exclude
                        </label>
                    </div>
                    <div class="col-md-5">
                    <select class="form-control" multiple="multiple" id="case_type" name="case_type[]">
                    <?php if (!empty($caseTypes)): ?>
                        <?php foreach ($caseTypes as $row): ?>
                            <option value="<?= esc($row['casecode']) ?>">
                                <?= esc(str_replace("No.", "", $row['short_description'])) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </select>
                    </div>
                </div>
            </fieldset>
            <fieldset class="form-group p-1">
                <div class="row">
                    <div class="col-md-3">
                        <legend>Section</legend>
                    </div>
                    <div class="col-md-4">
                        <input class="form-check-input" type="checkbox" name="section_exclude"
                            id="section_exclude" value="y">
                        <label class="form-check-label" for="section_exclude">
                            Exclude
                        </label>
                    </div>
                    <div class="col-md-5">
                        <select class="form-control" multiple="multiple" id="section" name="section[]">
                            <?php if (!empty($getJudicialSections)): ?>
                                <?php foreach ($getJudicialSections as $row): ?>
                                    <option value="<?= esc($row['id']) ?>">
                                        <?= esc($row['section_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
            </fieldset>
            <fieldset class="form-group p-1">
                <div class="row">
                    <div class="col-md-3">
                        <legend>Dealing Assistant</legend>
                    </div>
                    <div class="col-md-4">
                        <div>
                            <input class="form-check-input" type="checkbox" name="da_exclude"
                                id="da_exclude" value="y">
                            <label class="form-check-label" for="da_exclude">
                                Exclude
                            </label>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <select class="form-control" multiple="multiple" id="da" name="da[]">
                            <?php if (!empty($getUsers)): ?>
                                <?php foreach ($getUsers as $row): ?>
                                    <option value="<?= esc($row['usercode']) ?>">
                                        <?= esc($row['name']) . ' - ' . esc($row['empid']) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="">No users available</option>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
            </fieldset>
            <div class="form-group row p-1">
                <label for="list_date_range" class="col-sm-2">Number of Times Deleted</label>
                <div class="col-sm-10 input-group input-daterange" id="list_date_range">
                    <div class="row g-3 align-items-center">
                        <div class="col-auto">
                        <select class="form-control" id="belowfive" name="belowfive" style="width:170px !important;">
                                <?php for ($i = 0; $i <= 500; $i++) { ?>
                                    <option value="<?= $i; ?>">
                                        <?= $i; ?>
                                    </option>
                                <?php } ?>
                        </select>
                        </div>
                        <div class="col-auto">
                            <span class="px-2">to</span>
                        </div>
                        <div class="col-auto">
                        <select class="form-control" id="abovefive" name="abovefive">
                        <option value="">None selected</option>                     
                        </select>
                        </div>
                    </div>
                </div>
            </div>
            <fieldset class="form-group mt-2">
                            <div class="row" id="textbox-container">
                                <legend class="col-form-label col-sm-2 pt-0">Parties</legend>
                                <div class="col-sm-3 input-group">
                                    <select class="form-control" id="party_drop" name="party_drop[]">
                                        <option value="">Select</option>
                                        <option value="Petitioner">Petitioner</option>
                                        <option value="Respondent">Respondent</option>
                                        <option value="cause_title">Cause Title</option>
                                        <option value="all_party">All Party</option>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <select class="form-control" style="margin-left: -3%;" name="new_drop[]" id="new_drop">
                                            <option value="">Select</option>
                                            <option value="exa_sim">Exactly</option>
                                            <option value="like">Similar</option>
                                        </select> &nbsp;&nbsp;
                                        <input type="text" class="form-control small-input" name="parties[]" placeholder="Enter value here">
                                        <div class="input-group-append">&nbsp;&nbsp;
                                            <button class="btn-add btn-add-outline-secondary add-more" type="button">+</button>
                                            <button class="btn btn-outline-secondary remove-row ml-1" type="button" style="display: none;">-</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
            <div class="row py-1">
                <div class="col-sm-2 pl-4">Auxiliary</div>
                <div class=" col-sm-10 input-group">
                    <div class="form-check pr-2">
                        <input class="form-check-input" type="radio" name="coram_by_cji"
                            id="coram_by_cji_exclude" value="n">
                        <label class="form-check-label" for="coram_by_cji_exclude">
                            Exclude
                        </label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="coram_by_cji" id="coram_by_cji_include" value="y">
                        <label class="form-check-label" for="coram_by_cji_include">
                            Coram is given by Hon’ble the CJI
                        </label>
                    </div>
                </div>

            </div>
            <div class="row py-1">
                <div class="col-sm-2"></div>
                <div class="col-sm-10 input-group">
                    <div class="form-check pr-2">
                        <input class="form-check-input" type="radio" name="conditional_matter"
                            id="conditional_matter_exclude" value="n">
                        <label class="form-check-label" for="conditional_matter_exclude">
                            Exclude
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="conditional_matter"
                            id="conditional_matter_include" value="y">
                        <label class="form-check-label" for="conditional_matter_include">
                            Conditional matters
                        </label>
                    </div>
                </div>
            </div>
            <div class="row py-1">
                <div class="col-sm-2"></div>
                <div class="col-sm-10 input-group">
                    <div class="form-check pr-2">
                        <input class="form-check-input" type="radio" name="cav_matter"
                            id="cav_matter_exclude" value="n">
                        <label class="form-check-label" for="cav_matter_exclude">
                            Exclude
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="cav_matter"
                            id="cav_matter_include" value="y">
                        <label class="form-check-label" for="cav_matter_include">
                            CAV Matters
                        </label>
                    </div>
                </div>
            </div>

            <div class="row py-1">
                <div class="col-sm-2"></div>
                <div class="col-sm-10 input-group">
                    <div class="form-check pr-2">
                        <input class="form-check-input" type="radio" name="part_heard"
                            id="part_heard_exclude" value="n">
                        <label class="form-check-label" for="part_heard_exclude">
                            Exclude
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="part_heard"
                            id="part_heard_include" value="y">
                        <label class="form-check-label" for="part_heard_include">
                            Part Heard
                        </label>
                    </div>
                </div>
            </div>

            <div class="row py-1">
                <div class="col-sm-2"></div>
                <div class="col-sm-10 input-group">
                    <div class="form-check pr-2">
                        <input class=" form-check-input" type="radio" name="list_after_vacation"
                            id="list_after_vacation_exclude" value="n">
                        <label class="form-check-label" for="list_after_vacation_exclude">
                            Exclude
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="list_after_vacation"
                            id="list_after_vacation_include" value="y">
                        <label class="form-check-label" for="list_after_vacation_include">
                            List After Vacation
                        </label>
                    </div>
                </div>
            </div>
            <div class="row py-1">
                <div class="col-sm-2"></div>
                <div class="col-sm-10 input-group">
                    <div class="form-check pr-2">
                        <input class="form-check-input" type="radio" name="sensitive" id="sensitive_exclude"
                            value="n">
                        <label class="form-check-label" for="sensitive_exclude">
                            Exclude
                        </label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="sensitive" id="sensitive_include" value="y">
                        <label class="form-check-label" for="sensitive_include">
                            Sensitive
                        </label>
                    </div>
                </div>
            </div>
            <div class="row py-1">
                <div class="col-sm-2"></div>
                <div class="col-sm-10 input-group">
                    <div class="form-check pr-2">
                        <input class="form-check-input" type="radio" name="bail" id="bail_exclude" value="n">
                        <label class="form-check-label" for="bail_exclude">
                            Exclude
                        </label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="bail" id="bail_include" value="y">
                        <label class="form-check-label" for="bail_include">
                        Bail
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group row p-1">
                <div class="col-sm-10">
                    <button type="button" id="dismiss2" class="quick-btn get_pendency">Click to Get Pendency</button>
                </div>
            </div>
        </form>
    </nav>
    <div id="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-primary text-white font-weight-bolder">Pending Cases - Query Builder (For Listing Section Only)
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6 text-left">
                                <button type="button" id="sidebarCollapse" class="quick-btn gray-btn d-inline">
                                    <i class="fas fa-align-left"></i>
                                    <span>Filter</span>
                                </button>
                            </div>
                            <div class="col-6 text-right">
                                <button type="button" class="quick-btn get_pendency d-inline">
                                    Click to Get Pendency
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="pendency_result"></div>
                <div class="pendency_result_detail"></div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function() {
        $("#from_diary_date").datepicker();
    });

    $(function() {
        $("#to_diary_date").datepicker();
    });

    $(function() {
        $("#from_list_date").datepicker();
    });

    $(function() {
        $("#to_list_date").datepicker();
    });
    $(function() {
        $('#case_type, #subhead, #lp, #section, #da, #judge').select2({
            placeholder: "Select an option",
            allowClear: true,
            width: '225.417px',
            // Setting this to 0 will always show the search box
            minimumResultsForSearch: 0
        });
        
        // Initialize Select2 on the category select element
        $('#category').select2({
            placeholder: "Select a category",
            allowClear: true,
            // width: 'resolve',
            minimumResultsForSearch: 0
        });
    });

    $(document).ready(function() {
        $('#dismiss, #dismiss2, .overlay').on('click', function() {
            $('#sidebar').removeClass('active');
            $('.overlay').removeClass('active');
        });

        $('#sidebarCollapse').on('click', function() {
            $('#sidebar').addClass('active');
            $('.overlay').addClass('active');
            $('.collapse.in').toggleClass('in');
            $('a[aria-expanded=true]').attr('aria-expanded', 'false');
        });

        $(document).on("click", ".get_pendency", async function (e) {
            e.preventDefault();
            await updateCSRFTokenSync();
            var from_diary_date = $("#from_diary_date").val();
            var to_diary_date = $("#to_diary_date").val();
            var connected = $("#connected_exclude").is(":checked") ? 1 : 0;
            var mainhead = $("input[name='mainhead']:checked").val();
            var board_type = $("input[name='board_type']:checked").val();

            var status = [];
            $("input[name='status[]']:checked").each(function() {
                status.push($(this).val());
            });

            var only_presiding = $("#only_presiding").is(":checked") ? 'y' : 'n';
            var judge_exclude = $("#judge_exclude").is(":checked") ? 'y' : 'n';
            var judges = $("#judge").val();
            var subhead = $("#subhead").val();
            var subhead_exclude = $("#subhead_exclude").is(":checked") ? 'y' : 'n';
            var listing_purposes = $("#lp").val();
            var lp_exclude = $("#lp_exclude").is(":checked") ? 'y' : 'n';

            var from_list_date = $("#from_list_date").val();
            var to_list_date = $("#to_list_date").val();
            var categories = $("#category").val();
            var belowfive = $("#belowfive").val();
            var abovefive = $("#abovefive").val();
            var category_exclude = $("#category_exclude").is(":checked") ? 'y' : 'n';
            var case_types = $("#case_type").val();
            var case_type_exclude = $("#case_type_exclude").is(":checked") ? 'y' : 'n';
            var sections = $("#section").val();
            var section_exclude = $("#section_exclude").is(":checked") ? 'y' : 'n';
            var das = $("#da").val();
            var party_drop = $("#party_drop").val();
            var new_drop = $("#new_drop").val();
            var parties = $("#parties").val();
            var da_exclude = $("#da_exclude").is(":checked") ? 'y' : 'n';
            var coram_by_cji = $("input[name='coram_by_cji']:checked").val();
            var conditional_matter = $("input[name='conditional_matter']:checked").val();
            var cav_matter = $("input[name='cav_matter']:checked").val();
            var part_heard = $("input[name='part_heard']:checked").val();
            var list_after_vacation = $("input[name='list_after_vacation']:checked").val();
            var sensitive = $("input[name='sensitive']:checked").val();
            var bail = $("input[name='bail']:checked").val();
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

            var formValues = {
                from_diary_date,
                to_diary_date,
                connected,
                mainhead,
                board_type,
                status,
                only_presiding,
                judge_exclude,
                judges,
                subhead,
                subhead_exclude,
                listing_purposes,
                lp_exclude,
                from_list_date,
                to_list_date,
                categories,
                belowfive,
                abovefive,
                category_exclude,
                case_types,
                case_type_exclude,
                sections,
                section_exclude,
                das,
                da_exclude,
                coram_by_cji,
                conditional_matter,
                cav_matter,
                part_heard,
                list_after_vacation,
                sensitive,
                bail,
                CSRF_TOKEN: CSRF_TOKEN_VALUE,
                flag: 'report'
            };
            $.ajax({
                url: "<?php echo base_url('Listing/QueryBuilder/getResult'); ?>",
                type: "POST",
                data: formValues,
                beforeSend: function() {
                    $(".pendency_result_detail").html('');
                    $('.pendency_result').html('<table widht="100%" align="center"><tr><td class="text-center"><img src="<?php echo base_url('images/load.gif'); ?>"/></td></tr></table>');
                },
                success: function(data) {
                    $(".pendency_result").html(data);
                },
                error: function(xhr) {
                    alert("Error: " + xhr.status + " " + xhr.statusText); // Error handling
                }
            });
        });

    });
    $(document).ready(function () {
        $(document).on("click", "#diary_nos",  function (e) {
            e.preventDefault();
            $('#sort_by2 option').prop('selected', true);
        
            async function abc() {
                await updateCSRFTokenSync();
                var formValues = $("#child_form").serialize();
                var dnos = $(this).data('dnos');
                var number_of_rows = $("#number_of_rows").val();
                var CSRF_TOKEN = 'CSRF_TOKEN';
                var CSRF_TOKEN_VALUE = $("input[name='CSRF_TOKEN']").val();

                $.ajax({
                    url: "<?php echo base_url('Listing/QueryBuilder/getResultSec'); ?>",
                    cache: false,
                    async: true,
                    type: 'GET',
                    data: formValues + '&dnos=' + dnos + '&flag=report_detail&' + CSRF_TOKEN + '=' + CSRF_TOKEN_VALUE,
                    beforeSend: function () {
                    $('.pendency_result_detail').html('<table widht="100%" align="center"><tr><td class="text-center"><img src="<?php echo base_url('images/load.gif'); ?>"/></td></tr></table>');
                    },
                    success: function (data) {
                       $(".pendency_result_detail").html(data);
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                      console.error("AJAX error:", textStatus, errorThrown);
                    }
                });
            }

            if (parseInt(number_of_rows, 10) > 1000) {
                swal({
                    title: "Are you sure?",
                    text: number_of_rows + " records will take time to fetch. If not required, click on 'No, cancel it!' button",
                    icon: "warning",
                    buttons: [
                        'No, cancel it!',
                        'Yes, I am sure!'
                    ],
                    dangerMode: true,
                
                }).then(function(isConfirm) {
                    if (isConfirm) {     
                        abc();
                    } else {
                        swal("Cancelled", "Please try again :)", "error");
                    }
                });
            }else{
                abc();
            }
        });
    });

    $(document).ready(function() {
                $('#textbox-container').on('click', '.add-more', function() {
                    var newFields = `
                                <div class="input-group mt-2 party-row">

                     <div class="col-sm-3 input-group" style= "margin-left:17%">
                                    <select class="form-control" id="party_drop" name="party_drop[]">
                                        <option value="">Select</option>
                                        <option value="Petitioner">Petitioner</option>
                                        <option value="Respondent">Respondent</option>
                                        <option value="cause_title">Cause Title</option>
                                        <option value="all_party">All Party</option>
                                    </select>
                                    
                                </div>
                                <div class="col-sm-6 ">
                                   <div class="input-group ">
                <select class="form-control " name="new_drop[]" id="new_drop">
                    <option value="">Select</option>
                    <option value="exa_sim">Exactly</option>
                    <option value="like">Similar</option>
                </select>&nbsp;&nbsp;
                <input type="text" class="form-control small-input" name="parties[]" placeholder="Enter value here"> &nbsp;&nbsp;
                <div class="input-group-append">
                    <button class="btn btn-add btn-outline-secondary add-more" type="button">+</button>
                    <button class="btn btn-outline-secondary remove-row ml-1" type="button">-</button>
                </div>
                </div>
                </div>
            </div>`;
                    $('#textbox-container').append(newFields);
                });

                $('#textbox-container').on('click', '.remove-row', function() {
                    $(this).closest('.party-row').remove();
                });

                $('#textbox-container').on('click', '.add-more', function() {
                    $(this).closest('.input-group').find('.remove-row').show();
                });

                $('#textbox-container').on('click', '.add-more', function() {
                    $('#party_drop').closest('.input-group').show();
                });
            });

    document.getElementById('belowfive').addEventListener('change', function() {
                var belowfiveValue = parseInt(this.value);
                var abovefiveSelect = document.getElementById('abovefive');
                abovefiveSelect.innerHTML = '<option value="">Select</option>';
                if (!isNaN(belowfiveValue)) {
                    for (var i = belowfiveValue + 0; i <= 500; i++) {
                        var option = document.createElement('option');
                        option.value = i;
                        option.textContent = i;
                        abovefiveSelect.appendChild(option);
                    }
                }
            });

</script>