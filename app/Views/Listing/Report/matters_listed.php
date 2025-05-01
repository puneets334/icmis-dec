<?= view('header'); ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title"> Heard Entry Details</h3>
                            </div>

                            <?= view('Filing/filing_filter_buttons'); ?>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php
                        $action = base_url('Listing/Repot/matters_listed');
                        $attribute = "method ='post' ";
                        echo form_open();
                        csrf_token();
                        ?>
                        <!-- <div class="box-body"> -->

                        <div class="row col-sm-12">
                            <div class="col-sm-12" id="daysOption">
                                <label for="fromDays" class="col-sm-4">Select:</label>
                                <input type="radio" name="daysRange" id="daysRange" value="D"
                                    onclick="changeDays();"> Days Range
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <input type="radio" name="daysRange" id="daysRange" value="Y"
                                    onclick="changeDays();">
                                Years&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <input type="radio" name="daysRange" id="daysRange" value="N"
                                    onclick="changeDays();" checked> Never Listed
                            </div>
                        </div>

                        <div class="row col-sm-12">
                            <div class="col-sm-8" id="fromDaysRow">
                                <label for="fromDays" class="col-sm-10">Enter days range:</label>
                                <div class="row">
                                    <div class="col-md-2">
                                        <input type="text" class="form-control datepick hasDatepicker" id="fromDays" name="fromDays" value="">
                                    </div>
                                    <label>-</label>
                                    <div class="col-md-2">
                                        <input type="text" class="form-control datepick hasDatepicker" id="toDays" name="toDays">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-8" id="yearRow">
                                <div class="col-md-3">
                                    <label for="fromDays" class="col-sm-10">Enter year (in days) :</label>
                                    <input type="text" id="year" class="form-control" name="year" value="">
                                </div>
                            </div>
                            <div class="col-sm-4" id="stage">
                                <label for="stage">Misc./Regular</label>
                                <select class="form-control col-sm-4 stage" id="stage" name="stage">
                                    <option value="M">Miscelleneous</option>
                                    <option value="F">Regular</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12"></div>
                        <div class="row col-sm-12">
                            <div class="col-sm-6" id="section">
                                <label for="section" class="col-sm-6">Select Section:</label>
                                <select class="form-control col-sm-6 section" id="section" name="section"
                                    placeholder="Section" onchange="get_DA()" required>
                                    <option value="0">All</option>
                                    <?php
                                    foreach ($section_name as $Section) {
                                        echo '<option value="' . $Section['id'] . '" ' . (isset($_POST['section']) && $_POST['section'] == $Section['id'] ? 'selected="selected"' : '') . '>' . $Section['section_name'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-sm-6" id="div_da">
                                <label for="Dealing Asstt" id="lbl_da" class="col-sm-6">Select DA:</label>
                                <select class="form-control col-sm-6" id="da" name="da"
                                    placeholder="Dealing Assistant">
                                    <option value="0">All</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div style="margin-top: 80px" class="box-footer">
                                    <input type="button" id="btngetr" class="btn btn-block_ btn-primary" name="btngetr" value=" View " />
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                        <div id="dv_res1"> </div>
                                
                        
                        <div id="printable" class="box box-danger">
                        <h3 id="headingid" style="text-align: center;"></h3></caption>

                        <div id="dv_res_no_record"> </div>
                                    <table id="reportTable1" class="table table-striped table-hover ">
                                        <thead>
                                            <tr>
                                                <th>SNo.</th>
                                                <th>Diary no.</th>
                                                <th>Case No.</th>
                                                <th>Cause Title</th>
                                                <th>Main/<br>Connected</th>
                                                <th>Diary On</th>
                                                <th>Registered On</th>
                                                <th>Last Listed On</th>
                                                <th>Ready/<br> Not-Ready</th>
                                                <th>Dealing Assistant</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                           
                                        </tbody>
                                    </table>
                                </div>

                       
                    </div>
                </div>
            </div>
        </div>
</section>

<script type="text/javascript">
    async function get_DA() { // Call to ajax function
        await updateCSRFTokenSync();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var csrf = $("input[name='CSRF_TOKEN']").val();
        var secId = $("#section option:selected").val();

        $.ajax({
            url: '<?= base_url('Listing/Report/get_DA_sectionwise'); ?>',
            type: "POST",
            data: {
                CSRF_TOKEN: csrf,
                secId: secId
            },
            cache: false,
            dataType: "json",

            success: function(data) {
               // updateCSRFToken();
                console.log(data);
                console.log(data.length);
                var options = '';
                options = '<option value="0">All</option>'
                for (var i = 0; i < data.length; i++) {

                    options += '<option value="' + data[i].usercode + '">' + data[i].name + '</option>';

                }
                $("#da").html(options);



            },
            error: function() {
                alert('ERRO');
               // updateCSRFToken();
            }
        });
        //updateCSRFToken();
    }

    function get_mainhead() {
        var mainhead = "";
        $('input[type=radio]').each(function() {
            if ($(this).attr("name") == "daysRange" && this.checked)
                mainhead = $(this).val();
        });
        return mainhead;
    }

    function changeDays() {
        var option = $("input[name='daysRange']:checked").val();
        if (option == 'D') {
            document.getElementById('fromDaysRow').style.display = 'block';
            document.getElementById('yearRow').style.display = 'none';
        } else if (option == 'Y') {
            document.getElementById('fromDaysRow').style.display = 'none';
            document.getElementById('yearRow').style.display = 'block';
        } else {
            document.getElementById('fromDaysRow').style.display = 'none';
            document.getElementById('yearRow').style.display = 'none';
        }

    }


  
</script>
<script>
   $(document).ready(function() {
        var option = $("input[name='daysRange']:checked").val();
        if (option == 'D') {
            document.getElementById('fromDaysRow').style.display = 'block';
            document.getElementById('yearRow').style.display = 'none';
        } else if (option == 'Y') {
            document.getElementById('fromDaysRow').style.display = 'none';
            document.getElementById('yearRow').style.display = 'block';
        } else {
            document.getElementById('fromDaysRow').style.display = 'none';
            document.getElementById('yearRow').style.display = 'none';
        }

    });
    $(document).on("click", "#btngetr", async function() {
        await updateCSRFTokenSync();
        $('#dv_res1').html("");
        $('#dv_res_no_record').html("");
        $('#reportTable1').show();
        $("#headingid").html(title);
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var csrf = $("input[name='CSRF_TOKEN']").val();
        var section = $(".section").val();
        var da = $("#da").val();
        var stage = $(".stage").val();
        var fromDays = $("#fromDays").val();
        var toDays = $("#toDays").val();
        var year = $("#year").val();
        var daysRange = get_mainhead();
        var title = '';
        $.ajax({
            url: '<?php echo base_url('Listing/Report/matters_listed_get'); ?>',
            cache: false,
            async: true,
            data: {
                CSRF_TOKEN: csrf,
                section: section,
                da: da,
                stage: stage,
                fromDays: fromDays,
                toDays: toDays,
                year: year,
                daysRange: daysRange
            },
            beforeSend: function() {
                $('#dv_res1').html('<table widht="100%" align="center"><tr><td><img src="../../images/load.gif"/></td></tr></table>');
            },
            type: 'POST',
            success: function(Resultdata, status) {
            //updateCSRFToken();
            console.log('Raw Resultdata:', Resultdata);

            try { // Wrap parsing in a try-catch block
                var result = JSON.parse(Resultdata);
                var rdata = result.data;
                var title = result.title;

                console.log('Parsed Data:', rdata);
                $("#headingid").html(title);

                if (rdata.length === 0) {
                    // Destroy DataTable if it exists
                    if ($.fn.DataTable.isDataTable('#reportTable1')) {
                        $('#reportTable1').DataTable().destroy();
                    }
                    $('#dv_res_no_record').html("<p>No records found.</p>");
                    $('#dv_res1').html(""); // Display "No records found" message
                    $('#reportTable1').hide(); // Hide the table if no data
                } else {
                    // Destroy DataTable if it exists before initializing a new one
                    if ($.fn.DataTable.isDataTable('#reportTable1')) {
                        $('#reportTable1').DataTable().destroy();
                    }

                    var table = $('#reportTable1').DataTable({
                        "paging": true,
                        "searching": true,
                        "lengthChange": true,
                        "data": rdata,
                        "columns": [
                            { "data": "SNO" },
                            { "data": "Diary_no" },
                            { "data": "Case_No" },
                            { "data": "Cause_Title" },
                            { "data": "Main_Connected" },
                            { "data": "Diary_On" },
                            { "data": "Registered_On" },
                            { "data": "Last_Listed_On" },
                            { "data": "Ready_Not_Ready" },
                            { "data": "Dealing_Assistant" },
                        ],
                        dom: 'Bfrtip',
                        buttons: [
                            {
                                extend: 'print',
                                text: 'Print All Data',
                                title: title,
                                customize: function(win) {
                                    $(win.document.body).css('font-size', '12pt');
                                    $(win.document.body).find('table').addClass('compact').css('font-size', 'inherit');
                                }
                            }
                        ]
                    });
                    $('#dv_res1').html(""); // Clear any previous messages
                    $('#reportTable1').show(); // Ensure the table is visible
                }
            } catch (error) {
                console.error("Error parsing JSON:", error);
                $('#dv_res1').html("<p>An error occurred while processing the data.</p>"); // Display a user-friendly error message
                if ($.fn.DataTable.isDataTable('#reportTable1')) {
                    $('#reportTable1').DataTable().destroy();
                }
                $('#reportTable1').hide();
            }
        },
            error: function(xhr) {
                $('#dv_res1').html("");
                console.log("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
        //updateCSRFToken();
    });

   
</script>