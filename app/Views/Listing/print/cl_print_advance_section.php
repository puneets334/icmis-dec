<?= view('header'); ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-md-10">
                                <h3 class="card-title">Draft / Advance List Section Wise </h3>
                            </div>
                            <?= view('Filing/filing_filter_buttons'); ?>
                        </div>
                    </div>
                    <div class="card-body">
                   
                        <?php
                        echo form_open();
                        csrf_token();
                        ?>
                        <div id="dv_content1">
                            <div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="">List Type</label>
                                        <div class="row">
                                            <div><input type="radio" name="listtype" id="listtype" value="D" title="Draft" checked="checked">Draft&nbsp;</div>
                                            <div><input type="radio" name="listtype" id="listtype" value="A" title="Advance">Advance&nbsp;</div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="">Listing Dates</label>
                                        <select class="form-control ele" name="listing_dts" id="listing_dts">
                                            <?php
                                            if (count($listing_dates) > 0) {
                                            ?>
                                                <!-- <select class="form-contro ele" name="listing_dts" id="listing_dts"> -->
                                                <option value="-1" >EMPTY</option>
                                                    <?php
                                                    foreach ($listing_dates as $row) {
                                                    ?>
                                                        <option selected value="<?php echo $row['next_dt']; ?>">
                                                            <?php echo date("d-m-Y", strtotime($row['next_dt'])); ?>
                                                        </option>
                                                    <?php
                                                    }
                                                } else {
                                                    ?>
                                                    
                                                <?php
                                                }
                                                ?>
                                            </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="">Purpose of Listing</label>
                                        <select class="form-control ele" name="listing_purpose" id="listing_purpose">
                                            <option value="all" selected="selected">-ALL-</option>
                                            <?php if (count($purpose_of_listing) > 0) {
                                                foreach ($purpose_of_listing as $row) {
                                                    echo '<option value="' . $row["code"] . '">' . $row["code"] . '. ' . $row["purpose"] . '</option>';
                                                }
                                            } ?>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="">Section Name</label>
                                        <select class="form-control ele" name="sec_id" id="sec_id">
                                            <option value="all" selected="selected">-ALL-</option>
                                            <?php if (count($section_name) > 0) {
                                                foreach ($section_name as $ro_u) {
                                                    $ro_id = $ro_u['id'];
                                                    $ro_name = $ro_u['section_name'];
                                            ?>
                                                    <option value="<?php echo $ro_id; ?>"> <?php echo $ro_name; ?>
                                                    </option>
                                            <?php
                                                }
                                            } ?>
                                        </select>
                                    </div>
                                    <div class="col-md-2 mt-4">
                                        <input type="button" name="btn1" id="btn1" value="Submit" />
                                    </div>
                                </div>
                                <div id="res_loader"></div>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                    
                       
                            <div id="prnnt" style="text-align: center; font-size:12px;" class="p-3">
                                    <h3 id="title"></h3>
                                <table id="reportTable1" class="table table-striped table-hover ">
                                    <thead>
                                    <tr>
                                            <td width="5%">SrNo.</td>
                                            <td width="5%">Item No.</td>
                                            <td width="7%">Diary No</td>
                                            <td width="15%">Reg No.</td>
                                            <td width="15%">Petitioner / Respondent</td>
                                            <td width="15%">Advocate</td>
                                            <td width="5%">Section Name</td>
                                            <td width="10%">DA Name</td>
                                            <td width="20%">Statutory Info.</td>
                                            <td width="7%">Listed Before</td>
                                            <td width="8%">Purpose</td>
                                            <td width="10%">Trap</td>
                                        </tr>
                                    </thead>
                                    <tbody id="dv_res1">
                                    </tbody>
                                </table>
                                <div id="dv_res11" style="display:none;">    
                                No Records Found
                                </div>
                            </div>
                    
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    $('#reportTable1').hide();
    $("#dv_res11").hide();
    $(document).on("click", "#btn1", function() {
        get_cl_1();
        
    });

    function get_cl_1() {
        $('#reportTable1').show();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var csrf = $("input[name='CSRF_TOKEN']").val();
        var listtype = get_listtype();
        var list_dt = $("#listing_dts").val();
        var courtno = $("#courtno").val();
        var lp = $("#listing_purpose").val();
        var board_type = $("#board_type").val();
        var orderby = $("#orderby").val();
        var sec_id = $("#sec_id").val();
        var main_suppl = $("#main_suppl").val();
        var mainhead_descri ='';
        if(listtype == 'D'){
             mainhead_descri = "Draft List Cause List for Dated " +list_dt;
        }
        else {
            mainhead_descri = "Advance List Cause List for Dated " +list_dt;
        }
        var title = mainhead_descri;
        $("#title").html(title);
        $.ajax({
            url: '<?php echo base_url('Listing/PrintController/get_advance_cause_list_section_data'); ?>',
            cache: false,
            async: true,
            data: {
                CSRF_TOKEN: csrf,
                list_dt: list_dt,
                listtype: listtype,
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
                //$('#dv_res1').html(data);
                updateCSRFToken();
                console.log('Raw Resultdata:', Resultdata);
                
                // Parse the result data
                var rdata = JSON.parse(Resultdata);
                console.log('Parsed Data:', rdata);
                if (rdata.length === 0) {
                    $('#reportTable1').hide();
                    if ($.fn.DataTable.isDataTable('#reportTable1')) {
                        $('#reportTable1').DataTable().destroy();
                    }
                    $("#dv_res11").show();
                    

                } else {
                // Initialize DataTable (or reinitialize if needed)
                var table = $('#reportTable1').DataTable({
                    "destroy": true, // Allow reinitialization
                    "paging": true,
                    "searching": true,
                    "lengthChange": true,
                    "data": rdata, // Pass the data to DataTable
                    
                    "columns": [
                        { "data": "SNO" },
                        { "data": "Item_No" },
                        { "data": "Diary_No" },
                        { "data": "Reg_No" },
                        { "data": "Petitioner" },
                        { "data": "Advocate" },
                        { "data": "Section_Name" },
                        { "data": "DA_Name" },
                        { "data": "Statutory_Info" },
                        { "data": "Listed_Before" },
                        { "data": "Purpose" },
                        { "data": "Trap" }
                    ],
                    dom: 'Bfrtip',  // Add the button options
                    buttons: [
                    {
                        extend: 'print',  // Print button
                        text: 'Print All Data',  // Button text
                        title: title,  // Title for the printed page
                        customize: function (win) {
                            $(win.document.body).css('font-size', '12pt');
                            $(win.document.body).find('table').addClass('compact').css('font-size', 'inherit');
                        }
                    }
            ]
        });
    }
            },
            error: function(xhr) {
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
        updateCSRFToken();
    }

    function get_listtype() {
        var mainhead = "";
        $('input[type=radio]').each(function() {
            if ($(this).attr("name") == "listtype" && this.checked)
                listtype = $(this).val();
        });
        return listtype;
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