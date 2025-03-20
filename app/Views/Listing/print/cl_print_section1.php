<?= view('header') ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">

                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">CAUSE LIST SECTION WISE (ONLY PUBLISHED LIST)</h3>
                            </div>
                           
                        </div>
                    </div>



                    <?php
                    // include('../../mn_sub_menu.php');
                    echo form_open();
                    csrf_field();
                    ?>
                    <div id="dv_content1" class="container mt-4">

                        <div class="text-center">
                            <form>
                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <fieldset class="p-2">
                                            <label for="sec_id">Mainhead</label>
                                            <div class="form-check form-check-inline">
                                                <input type="radio" name="mainhead" id="mainheadMisc" value="M" class="form-check-input" title="Miscellaneous" checked>
                                                <label class="form-check-label" for="mainheadMisc">Misc</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input type="radio" name="mainhead" id="mainheadRegular" value="F" class="form-check-input" title="Regular">
                                                <label class="form-check-label" for="mainheadRegular">Regular</label>
                                            </div>
                                        </fieldset>
                                    </div>

                                    <div class="col-md-3">
                                        <fieldset class="p-2">
                                            <label for="sec_id">Listing Dates</label>
                                            <input type="date" name="listing_dts" id="listing_dts" class="form-control">
                                        </fieldset>
                                    </div>

                                    <div class="col-md-2">
                                        <fieldset class="p-2">
                                            <label for="sec_id">Board Type</label>
                                            <select class="form-control" name="board_type" id="board_type">
                                                <option value="0">-ALL-</option>
                                                <option value="J">Court</option>
                                                <option value="S">Single Judge</option>
                                                <option value="C">Chamber</option>
                                                <option value="R">Registrar</option>
                                            </select>
                                        </fieldset>
                                    </div>

                                    <div class="col-md-2">
                                        <fieldset class="p-2">
                                            <label for="sec_id">Court No.</label>
                                            <select class="form-control" name="courtno" id="courtno">
                                                <option value="0">-ALL-</option>
                                                <?php for ($i = 1; $i <= 14; $i++) { ?>
                                                    <option value="<?= $i; ?>"><?= $i; ?></option>
                                                <?php } ?>
                                                <option value="21">21 (Registrar)</option>
                                                <option value="22">22 (Registrar)</option>
                                            </select>
                                        </fieldset>
                                    </div>

                                    <div class="col-md-2">
                                        <fieldset class="p-2">
                                            <label for="sec_id">Purpose of Listing</label>
                                            <select class="form-control" name="listing_purpose" id="listing_purpose">
                                                <option value="all" selected>-ALL-</option>
                                                <option value="4">4. Fixed Date by Court</option>
                                                <option value="5">5. Mention Memo</option>
                                                <option value="2">2. Administrative Order</option>
                                            </select>
                                        </fieldset>
                                    </div>

                                    <div class="col-md-2">
                                        <fieldset class="p-2">
                                            <label for="sec_id">Main/Suppl.</label>
                                            <select class="form-control" name="main_suppl" id="main_suppl">
                                                <option value="0">-ALL-</option>
                                                <option value="1">Main</option>
                                                <option value="2">Suppl.</option>
                                            </select>
                                        </fieldset>
                                    </div>

                                    <div class="col-md-3">
                                        <fieldset class="p-2">
                                            <label for="sec_id">Section Name</label>
                                            <select class="form-control" name="sec_id" id="sec_id">
                                                <option value="0">-ALL-</option>
                                                <?php foreach ($userSectionList as $ro_u) { ?>
                                                    <option value="<?= $ro_u['id']; ?>" <?= ($section_id[0]['section'] == $ro_u['id']) ? 'selected' : ''; ?>>
                                                        <?= $ro_u['section_name']; ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </fieldset>
                                    </div>

                                    <div class="col-md-3">
                                        <fieldset class="p-2">
                                            <label for="sec_id">Order By</label>
                                            <select class="form-control" name="orderby" id="orderby">
                                                <option value="0">-ALL-</option>
                                                <option value="1">Court Wise</option>
                                                <option value="2">Section Wise</option>
                                            </select>
                                        </fieldset>
                                    </div>

                                    <div class="col-md-3">
                                        <fieldset class="p-2"><br />
                                            <input type="button" name="btn1" id="btn1" value="Submit" class="btn btn-primary" />
                                        </fieldset>
                                    </div>
                                </div>

                                <div id="res_loader" class="text-center"></div>
                            </form>
                        </div>

                        <div id="dv_res1"></div>
                    </div>



                    <?php echo form_close(); ?>
                      
                    <div class="container">
                    <h3 id="titleid"></h3>
                            <div class="row">
                                            <div class="panel ">
                                        
                                                    <table id="reportTable1" class="table table-striped table-hover ">
                                                        <thead>
                                                            <tr>
                                                                <th>Sr No.</th>
                                                                <th>Court No.</th>
                                                                <th>Item No.</th>
                                                                <th>Diary No</th>
                                                                <th>Reg No.</th>
                                                                <th>Petitioner / Respondent</th>
                                                                <th>Advocate</th>
                                                                <th>DA Name</th>
                                                                <th>Purpose</th>
                                                                <th>Remarks</th>
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
        </div>
</section>


<script>
    /*$(document).on("click","input[name='mainhead']",function(){
            var mainhead = get_mainhead();
            var board_type = $("#board_type").val();
                $.ajax({
                    url: '../common/get_cl_print_mainhead.php',
                    cache: false,
                    async: true,
                    data: {mainhead:mainhead, board_type: board_type},
                    beforeSend:function(){
                       //$('#rs_jg').html('<table widht="100%" align="center"><tr><td><img src="../../images/load.gif"/></td></tr></table>');
                    },
                    type: 'POST',
                    success: function(data, status) {
                       $('#listing_dts').html(data);
                    },
                    error: function(xhr) {
                        alert("Error: " + xhr.status + " " + xhr.statusText);
                    }
                });
        });*/


    $(document).on("click", "#btn1", function() {
        get_cl_1();
    });

    function get_cl_1() {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var csrf = $("input[name='CSRF_TOKEN']").val();
        var mainhead = get_mainhead();
        var list_dt = $("#listing_dts").val();
        var courtno = $("#courtno").val();
        var lp = $("#listing_purpose").val();
        var board_type = $("#board_type").val();
        var orderby = $("#orderby").val();
        var sec_id = $("#sec_id").val();
        var main_suppl = $("#main_suppl").val();
        $.ajax({
            url: '<?php echo base_url('Listing/PrintController/get_cause_list_section1'); ?> ',
            cache: false,
            async: true,
            data: {
                CSRF_TOKEN: csrf,
                list_dt: list_dt,
                mainhead: mainhead,
                lp: lp,
                courtno: courtno,
                board_type: board_type,
                orderby: orderby,
                sec_id: sec_id,
                main_suppl: main_suppl
            },
            beforeSend: function() {
                $('#dv_res1').html('<table widht="100%" align="center"><tr><td><img src="../../images/load.gif"/></td></tr></table>');
            },
            type: 'POST',
            success: function(Resultdata, status) {
                        $('#dv_res1').html('');
               
                        updateCSRFToken();
                        console.log('Raw Resultdata:', Resultdata);
                        
                        // Parse the result data
                        var result = JSON.parse(Resultdata);
                        var rdata = result.cause_list;
                        var title = result.title;
                        $("#titleid").html(title);
                        console.log('Parsed Data:', rdata);

                        // Initialize DataTable (or reinitialize if needed)
                        var table = $('#reportTable1').DataTable({
                            "destroy": true, // Allow reinitialization
                            "paging": true,
                            "searching": true,
                            "lengthChange": true,
                            "data": rdata, // Pass the data to DataTable
                            "columns": [
                                { "data": "sno" },
                                { "data": "courtno" },
                                { "data": "itemno" },
                                { "data": "diaryno" },
                                { "data": "regno" },
                                { "data": "petitioner_respondent" },
                                { "data": "advocate" },
                                { "data": "daname" },
                                { "data": "purpose" },
                                { "data": "remarks" }
                            ],
                            
                            dom: 'Bfrtip',  // Add the button options
                            buttons: [
                            {
                                extend: 'excel', // Excel export button
                                text: 'Export to Excel', // Button text (customize as needed)
                                title: title, // Title for the Excel file (customize as needed)
                                filename: 'data_export', // Filename for the Excel file (customize as needed)
                                exportOptions: { // Optional: Customize the exported data
                                    columns: ':visible' // Example: Export only visible columns
                                }
                            }
                    ]
                });

                // Clear any loading text
                $("#result_dis").html('');
            },
            error: function(xhr) {
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
        updateCSRFToken();
    }



    function get_mainhead() {
        var mainhead = "";
        $('input[type=radio]').each(function() {
            if ($(this).attr("name") == "mainhead" && this.checked)
                mainhead = $(this).val();
        });
        return mainhead;
    }

    //function CallPrint(){
    $(document).on("click", "#prnnt1", function() {
        var prtContent = $("#prnnt").html();
        var temp_str = prtContent;
        var WinPrint = window.open('', '', 'left=100,top=0,align=center,width=800,height=1200,menubar=1,toolbar=1,scrollbars=1,status=1');
        WinPrint.document.write(temp_str);
        WinPrint.document.close();
        WinPrint.focus();
        WinPrint.print();
    });
</script>

<script>
    function exportTableToExcel(tableID, filename = '') {
        var downloadLink;
        var dataType = 'application/vnd.ms-excel';
        var tableSelect = document.getElementById(tableID);
        var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');

        // Specify file name
        filename = filename ? filename + '.xls' : 'cause_list_<?php echo time(); ?>.xls';

        // Create download link element
        downloadLink = document.createElement("a");

        document.body.appendChild(downloadLink);

        if (navigator.msSaveOrOpenBlob) {
            var blob = new Blob(['\ufeff', tableHTML], {
                type: dataType
            });
            navigator.msSaveOrOpenBlob(blob, filename);
        } else {
            // Create a link to the file
            downloadLink.href = 'data:' + dataType + ', ' + tableHTML;

            // Setting the file name
            downloadLink.download = filename;

            //triggering the function
            downloadLink.click();
        }
    }
</script>