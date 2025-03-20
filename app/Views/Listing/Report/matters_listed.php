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
    function get_DA() { // Call to ajax function
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
                updateCSRFToken();
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
                updateCSRFToken();
            }
        });
        updateCSRFToken();
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


    /*var rad = document.myform.daysRange;
    var prev = null;
    for (var i = 0; i < rad.length; i++) {
        rad[i].addEventListener('change', function() {
            (prev) ? console.log(prev.value): null;
            if (this !== prev) {
                prev = this;
            }
            alert(this.value);
            console.log(this.value);
        });
    }*/

    // $(document).ready(function() {

    //     $(function() {
    //         $('.datepick').datepicker({
    //             format: 'dd-mm-yyyy',
    //             autoclose: true
    //         });
    //     });
    //     document.getElementById('fromDaysRow').style.display = 'none';
    //     document.getElementById('yearRow').style.display = 'none';
    //     $('#reportTable1').DataTable({
    //         /* dom: 'Bfrtip',
    //          buttons: [
    //              'excelHtml5',
    //              'pdfHtml5'
    //          ]*/

    //         "bProcessing": true,
    //         dom: 'Bfrtip',
    //         buttons: [
    //             'excelHtml5',
    //             {
    //                 extend: 'pdfHtml5',
    //                 pageSize: 'A3',
    //                 customize: function(doc) {
    //                     doc.content.splice(0, 0, {
    //                         margin: [0, 0, 0, 5],
    //                         alignment: 'center',
    //                         image: 'data:image/png;base64,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'
    //                     });
    //                     doc.watermark = {
    //                         text: 'SUPREME COURT OF INDIA',
    //                         color: 'blue',
    //                         opacity: 0.05
    //                     }
    //                 }
    //             }

    //         ]

    //     });
    // });
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
    $(document).on("click", "#btngetr", function() {
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
            updateCSRFToken();
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
        updateCSRFToken();
    });

   
</script>