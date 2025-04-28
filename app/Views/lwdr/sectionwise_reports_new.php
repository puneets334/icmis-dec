<?= view('header.php'); ?>
<style>
    #image {
        margin-left: 25%;
    }

    #display {
        margin-left: 5%;
    }

    input[type=submit] {
        margin: 8px 0 0 4%;
    }

    #di {
        border-radius: 5px;
        padding: 20px;
    }

    select {
        width: 10%;
        margin-left: 20px;
    }
</style>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 mt-3">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Section Wise Report</h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form class="form-horizontal" id="push-form" >
                            <?= csrf_field(); ?>
                            <div class="box-body col-12">
                                <div class="row">
                                    <div class="col-sm-12 row">
                                        <div class="col-sm-3">
                                            <label>
                                                <input type="radio" id="reportType" name="reportType" value="0" checked <?php if (isset($_POST['reportType']) && $param[0] == 0) echo "checked"; ?> />
                                                &nbsp;&nbsp;Particular Category
                                            </label>
                                        </div>
                                        <div class="col-sm-3">
                                            <label>
                                                <input type="radio" id="reportType" name="reportType" value="1" <?php if (isset($_POST['reportType']) && $param[0] == 1) echo "checked"; ?> />
                                                &nbsp;&nbsp;Pending Before
                                            </label>
                                        </div>
                                        <div class="col-sm-3">
                                            <label>
                                                <input type="radio" id="reportType" name="reportType" value="4" <?php if (isset($_POST['reportType']) && $param[0] == 4) echo "checked"; ?> />
                                                &nbsp;&nbsp;Disposed Matters
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-sm-4" id="section">
                                        <label for="section" id="lbl_section" class="col-sm-6">Select Section :</label>
                                        <select class="form-control sel_sec" id="section" name="section" placeholder="Section">
                                            <option value="">--Select Section--</option>
                                            <?php
                                            foreach ($Sections as $Section)
                                                echo '<option value="' . $Section['id'] . '^' . $Section['section_name'] . '" ' . (isset($_POST['section']) && $param[5] == $Section['section_name'] ? 'selected="selected"' : '') . '>' . $Section['section_name'] . '</option>';
                                            ?>
                                        </select>
                                        <input type='hidden' id='mysection' value=''>
                                    </div>
                                    <div class="col-sm-4" id="mainsubjectCategory">
                                        <label for="category" id="lbl_McategoryCode" class="col-sm-6">Select Main Subject Category:</label>
                                        <select class="form-control col-sm-12" id="McategoryCode" name="McategoryCode" onchange="get_sub_sub_cat()" required placeholder="Main Subject Category">
                                            <option value="0">--Select Main Category--</option>
                                            <option value="100" <?php if (isset($_POST['McategoryCode']) && $_POST['McategoryCode'] == "100") echo "selected"; ?>>All</option>
                                            <?php
                                            foreach ($MCategories as $MCategory)
                                                echo '<option value="' . $MCategory['subcode1'] . '" ' . (isset($_POST['McategoryCode']) && $_POST['McategoryCode'] == $MCategory['subcode1'] ? 'selected="selected"' : '') . '>' . $MCategory['subcode1'] . ' # ' . $MCategory['sub_name1'] . '</option>';
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-sm-4" id="subjectCategory">
                                        <label for="category" id="lbl_categoryCode" class="col-sm-6">Select Sub Subject Category:</label>
                                        <select class="form-control" id="categoryCode" name="categoryCode" placeholder="Subject Category">
                                            <option value="">All</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-6" id="courtType">
                                        <label for="status" id="lbl_CourtType" class="col-sm-4">Select Court Type:</label><br />
                                        <div class="radio">
                                            <label>
                                                <input checked type="radio" id="listCourtType" name="listCourtType" value="J" <?php if (isset($_POST['listCourtType']) && $param[1] == 'J') echo "checked"; ?> />
                                                Before Hon'ble Court
                                            </label>
                                            <label>
                                                <input type="radio" id="listCourtType" name="listCourtType" value="C" <?php if (isset($_POST['listCourtType']) && $param[1] == 'C') echo "checked"; ?> />
                                                Before Chamber
                                            </label>
                                            <label>
                                                <input type="radio" id="listCourtType" name="listCourtType" value="R" <?php if (isset($_POST['listCourtType']) && $param[1] == 'R') echo "checked"; ?> />
                                                Before Reg. Court
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 row" id="fromToDatePicker">
                                        <div class="col-sm-6">
                                            <label for="from_date" id="lbl_from_date">From Date:</label>
                                            <input type="text" id="from_date" autocomplete="off" value="<?php if (isset($_POST['from_date'])) echo date("d-m-Y", strtotime(strtr($param[3], '/', '-'))); ?>" name="from_date" class="form-control dtp" placeholder="From Date" required="required">
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="to_date" id="lbl_to_date">To Date:</label>
                                            <input type="text" id="to_date"  autocomplete="off"  value="<?php if (isset($_POST['from_date'])) echo date("d-m-Y", strtotime(strtr($param[4], '/', '-'))); ?>" name="to_date" class="form-control dtp" placeholder="To Date" required="required">
                                        </div>
                                    </div>
                                    <div class="col-sm-6" id="listDateType">
                                        <label for="date" id="lbl_dateType" class="col-sm-6">Select Listing Date Type:</label><br />
                                        <div class="radio">
                                            <label>
                                                <input checked type="radio" id="dateType" name="dateType" value="F" />
                                                Future Date
                                            </label>
                                            <label>
                                                <input type="radio" id="dateType" name="dateType" value="P" />
                                                Past Date
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 box-footer">
                                        <button type="submit" style="margin-top: 20px; width:15%;float:right" id="view" name="view" class="btn btn-block btn-primary">View</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <img id="image" src="<?= base_url(); ?>/images/load.gif" style="display: none;">
                        <div id="table_Result"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
$(document).on("focus", ".dtp", function() {
    $('.dtp').datepicker({
        dateFormat: 'YYYY-mm-dd',
        changeMonth: true,
        changeYear: true,
        yearRange: '1950:2050'
    });
});

    function get_sub_sub_cat() {
        var Mcat = $("#McategoryCode option:selected").val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
       
        $.ajax({
            url: '<?= base_url(); ?>/lwdr/get_Sub_Subject_Category',
            type: "POST",
            data: {
                Mcat: Mcat,
                CSRF_TOKEN: CSRF_TOKEN_VALUE,
            },
            cache: false,
            dataType: "json",
            success: function(data) {
                var options = '';
                options = '<option value="0">All</option>'
                for (var i = 0; i < data.length; i++)
                {
                    options += '<option value="' + data[i].id + '">' + data[i].dsc + '</option>';
                }
                $("#categoryCode").html(options);
            },
            complete: function() {
                updateCSRFToken();
            },
            error: function() {
                alert('ERRO');
            }
        });
    }


    $(document).ready(function()
    { 
        disableAllElements();
        enableElements($('#subjectCategory').children());
        $('#subjectCategory').show();
        enableElements($('#mainsubjectCategory').children());
        $('#mainsubjectCategory').show();

        if ($("input[name='reportType']:checked").val() == 0) {
            disableAllElements();
            enableElements($('#subjectCategory').children());
            $('#subjectCategory').show();
            enableElements($('#mainsubjectCategory').children());
            $('#mainsubjectCategory').show();
        } else if ($("input[name='reportType']:checked").val() == 1) {
            disableAllElements();

            enableElements($('#courtType').children());
            $('#courtType').show();
        } else if ($("input[name='reportType']:checked").val() == 2) {
            disableAllElements();

            enableElements($('#listDateType').children());
            $('#listDateType').show();
        } else if ($("input[name='reportType']:checked").val() == 4) {
            disableAllElements();

            enableElements($('#fromToDatePicker').children());
            $('#fromToDatePicker').show();
        }

        $("input[name$='reportType']").click(function() {

            var searchValue = $(this).val();
            if (searchValue == 0) {
                disableAllElements();
                enableElements($('#subjectCategory').children());
                $('#subjectCategory').show();
                enableElements($('#mainsubjectCategory').children());
                $('#mainsubjectCategory').show();

            } else if (searchValue == 1) {
                disableAllElements();

                enableElements($('#courtType').children());
                $('#courtType').show();
            } else if (searchValue == 2) {
                disableAllElements();

                enableElements($('#listDateType').children());
                $('#listDateType').show();
            } else if (searchValue == 3) {
                disableAllElements();
            } else if (searchValue == 4) {
                disableAllElements();

                enableElements($('#fromToDatePicker').children());
                $('#fromToDatePicker').show();
            } else {
                disableAllElements();
            }
        });


        $(function() {

            $('#section').change(function() {
                $('#mysection').val($("#section option:selected").text());
            });
        });

        $(function() {
            $(".select2").select2();
        });

        $('#push-form').on('submit', function(e) {

            var section = $('.sel_sec').val();
            if(section == '') {
                alert("Please Select Section");
                return false;
            }   
			
			
			if ($('#McategoryCode').val() == 0 && section != '') {
                alert("Please Select Main Category");
				return false;
            }
			
            e.preventDefault(); 
            var formData = $(this).serialize();            
            $.ajax({
            url: '<?= base_url(); ?>/lwdr/Sectionwise_report_data', // Replace with your form submission URL
            type: 'POST',
            data: formData,
            success: function(response) {
                // alert(response);
                $('#table_Result').html(response);
            },
            beforeSend: function() {
                $('#table_Result').html('');
                $('#view').attr('disabled', true);
                $('#image').show();
            },
            complete: function() {
                $('#view').attr('disabled', false);
                $('#image').hide();
                updateCSRFToken();
            },
            error: function(xhr, status, error) {
                console.error(error);
                alert('An error occurred while submitting the form.');
            }
            });
        });

    });

    function disableAllElements() {

        disableElements($('#subjectCategory').children());
        $('#subjectCategory').hide();

        disableElements($('#mainsubjectCategory').children());
        $('#mainsubjectCategory').hide();

        disableElements($('#fromToDatePicker').children());
        $('#fromToDatePicker').hide();

        disableElements($('#courtType').children());
        $('#courtType').hide();

        disableElements($('#listDateType').children());
        $('#listDateType').hide();

    }

    function enableAllElements() {
        enableElements($('#subjectCategory').children());
        $('#subjectCategory').show();

        enableElements($('#mainsubjectCategory').children());
        $('#mainsubjectCategory').show();

        enableElements($('#fromToDatePicker').children());
        $('#fromToDatePicker').show();

        enableElements($('#courtType').children());
        $('#courtType').show();

        enableElements($('#listDateType').children());
        $('#listDateType').show();

    }

    function disableElements(el) {
        for (var i = 0; i < el.length; i++) {
            el[i].disabled = true;
            disableElements(el[i].children);
        }
    }

    function enableElements(el) {
        for (var i = 0; i < el.length; i++) {
            el[i].disabled = false;
            enableElements(el[i].children);
        }
    }
</script>