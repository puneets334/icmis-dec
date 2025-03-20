<?= view('header') ?>
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
            var category_exclude = $("#category_exclude").is(":checked") ? 'y' : 'n';
            var case_types = $("#case_type").val();
            var case_type_exclude = $("#case_type_exclude").is(":checked") ? 'y' : 'n';
            var sections = $("#section").val();
            var section_exclude = $("#section_exclude").is(":checked") ? 'y' : 'n';
            var das = $("#da").val();
            var da_exclude = $("#da_exclude").is(":checked") ? 'y' : 'n';
            var coram_by_cji = $("input[name='coram_by_cji']:checked").val();
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
                category_exclude,
                case_types,
                case_type_exclude,
                sections,
                section_exclude,
                das,
                da_exclude,
                coram_by_cji,
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

</script>