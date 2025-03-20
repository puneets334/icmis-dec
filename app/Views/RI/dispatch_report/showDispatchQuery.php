<?= view('header') ?>
 
    <style>
        .custom-radio {
            float: left;
            display: inline-block;
            margin-left: 10px;
        }

        .custom_action_menu {
            float: left;
            display: inline-block;
            margin-left: 10px;
        }

        .basic_heading {
            text-align: center;
            color: #31B0D5
        }

        .btn-sm {
            padding: 0px 8px;
            font-size: 14px;
        }

        .card-header {
            padding: 5px;
        }

        h4 {
            line-height: 0px;
        }

        .row {
            margin-right: 15px;
            margin-left: 15px;
        }
        a {color:darkslategrey}      /* Unvisited link  */

        a:hover {color:black}    /* Mouse over link */
        a:active {color:#0000FF;}  /* Selected link   */

        .box.box-success {
            border-top-color: #00a65a;
        }
        .box {
            position: relative;
            border-radius: 3px;
            background: #ffffff;
            border-top: 3px solid #d2d6de;
            margin-bottom: 20px;
            width: 100%;
            box-shadow: 0 1px 1px rgba(0,0,0,0.1);
        }

        .box-header {
            color: #444;
            display: block;
            padding: 10px;
            position: relative;
        }
        .box-header.with-border {
            border-bottom: 1px solid #f4f4f4;
        }
        .box.box-danger {
            border-top-color: #dd4b39;
        }
       
</style>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">R & I >> Dispatch Query</h3>
                            </div>
                        </div>
                    </div>
                    <br><br>
                    <div class="container-fluid">
                        <form id="dispatchQuery" method="post">
                            <?= csrf_field(); ?> 
                            <div class="col-sm-12">
                                <div class="form-group col-sm-2">
                                    <label for="searchBy" class="text-right">Search By</label>
                                    <select class="form-control" id="searchBy">
                                        <!-- Loop through search types -->
                                        <option value="">Select</option>
                                        <?php foreach($searchType as $type): ?>

                                            <option value="<?= $type['id'] ?>"><?= $type['name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <!-- Case Type and Case Number Search -->
                                
                                    <div id="divCaseTypeWise" style="display:none;">
                                        <div class="row">
                                        <div class="form-group col-sm-2">
                                            <label for="caseType">Case Type</label>
                                            <select class="form-control" id="caseType">
                                                <option value="">Select</option>
                                                <?php foreach($caseTypes as $type): ?>
                                                    <option value="<?= $type['casecode'] ?>"><?= $type['short_description'] ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-sm-2">
                                            <label for="caseNo">Case Number</label>
                                            <input type="text" id="caseNo" class="form-control number" placeholder="Case Number">
                                        </div>
                                        <div class="form-group col-sm-2">
                                            <label for="caseYear">Case Year</label>
                                            <select id="caseYear" class="form-control"></select>
                                        </div>
                                    </div>
                                    </div>
                               

                                <!-- Diary Number Search -->
                                <div id="divDiaryNoWise" style="display:none;">
                                    <div class="row">
                                    <div class="form-group col-sm-2">
                                        <label for="diaryNumber">Diary Number</label>
                                        <input type="text" id="diaryNumber" class="form-control number" placeholder="Diary Number">
                                    </div>
                                    <div class="form-group col-sm-2">
                                        <label for="diaryYear">Diary Year</label>
                                        <select id="diaryYear" class="form-control"></select>
                                    </div>
                                    </div>
                                </div>

                                <!-- Process ID Search -->
                                <div id="divProcessIdWise" style="display:none;">
                                    <div class="row">
                                    <div class="form-group col-sm-2">
                                        <label for="processId">Process Id</label>
                                        <input type="text-align" id="processId" class="form-control" placeholder="Process Id">
                                    </div>
                                    <div class="form-group col-sm-2">
                                        <label for="processYear">Process Year</label>
                                        <select id="processYear" class="form-control"></select>
                                    </div>
                                    </div>
                                </div>

                                <!-- Free Text Search -->
                                <div id="divtextSearch" style="display:none;">
                                    <div class="form-group col-sm-4">
                                        <label for="freeText">Enter Text</label>
                                        <input type="text" id="freeText" class="form-control" placeholder="text here">
                                    </div>
                                </div>

                                <!-- Way Bill Number Search -->
                                <div id="divWayBillNumber" style="display:none;">
                                    <div class="form-group col-sm-4">
                                        <label for="wayBillNumber">Way Bill Number</label>
                                        <input type="text" id="wayBillNumber" class="form-control" placeholder="Way Bill Number">
                                    </div>
                                </div>

                                <div class="form-group col-sm-2">
                                    <label for="btnSearch">&nbsp;</label>
                                    <button type="button" id="btnSearch" class="btn btn-primary form-control">Search</button>
                                </div>  
                            </div>
                        </form>

                        <div id="printable" style="margin-top: 120px!important;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Add JavaScript -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Populate years dynamically
       
        var yearSelects = ['caseYear', 'diaryYear', 'processYear'];
        var currentYear = new Date().getFullYear();
        var selectOptions = '';
        for (var i = currentYear; i >= 1950; i--) {
            selectOptions += `<option value="${i}">${i}</option>`;
        }
        yearSelects.forEach(function(selectId) {
            document.getElementById(selectId).innerHTML = selectOptions;
        });

        // Handle Search Type Change
        document.getElementById('searchBy').addEventListener('change', function() {
            var searchBy = this.value;
            document.getElementById('divCaseTypeWise').style.display = (searchBy == '3') ? 'block' : 'none';
            document.getElementById('divDiaryNoWise').style.display = (searchBy == '2') ? 'block' : 'none';
            document.getElementById('divProcessIdWise').style.display = (searchBy == '1') ? 'block' : 'none';
            document.getElementById('divtextSearch').style.display = (['4', '5', '6'].indexOf(searchBy) >= 0) ? 'block' : 'none';
            document.getElementById('divWayBillNumber').style.display = (searchBy == '7') ? 'block' : 'none';
        });

        // Handle Search Button Click
        document.getElementById('btnSearch').addEventListener('click', function() {
            var searchBy = document.getElementById('searchBy').value;
            var formData = new FormData();
            var isValid = true;
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            
            if (searchBy === '1') {
                var processId = document.getElementById('processId').value;
                if (!processId) {
                    alert('Enter Process Id!');
                    isValid = false;
                }
                var processYear = document.getElementById('processYear').value;
                if (!processYear) {
                    alert('Enter Process Year!');
                    isValid = false;
                }
                formData.append('searchBy', searchBy);
                formData.append('processId', processId);
                formData.append('processYear', processYear);
                formData.append(CSRF_TOKEN, CSRF_TOKEN_VALUE);

                
            } 
             if (searchBy === '2') {
                var diaryNumber = document.getElementById('diaryNumber').value;
                if (!diaryNumber) {
                    alert('Enter diaryNumber !');
                    isValid = false;
                }
                var diaryYear = document.getElementById('diaryYear').value;
                if (!diaryYear) {
                    alert('Enter diaryYear!');
                    isValid = false;
                }
                formData.append('diaryNumber', diaryNumber);
                formData.append('diaryYear', diaryYear);
            }
            if (isValid) {
             
            $.ajax({
                    type: "POST",
                    url: "<?= base_url() ?>/RI/DispatchController/getQueryData", // Adjust this URL as needed
                    data: formData,
                    contentType: false,
                    processData: false,
                    dataType: 'html',
                    beforeSend: function() {
                        $('#printable').html('<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>');
                    },
                    success: function(data) {
                        updateCSRFToken();  // Make sure this function updates the CSRF token as needed
                        $("#printable").html(data);  // Assuming the server returns HTML
                       
                    },
                    error: function() {
                        updateCSRFToken();  // Ensure CSRF token is refreshed even on error
                        alert('No Data Found');                        
                    }
                });
         
                //  $.ajax({
                //     type: "POST",
                //     data: $("#dispatchedFromSection").serialize(), 
                //     dataType: 'html', 
                //     url: "<?php echo base_url('RI/DispatchController/getDateWiseDispatchedFromSection'); ?>",
                //     success: function(data) {
                //         alert("Success!");
                //        // $('.card-title').hide();
                //        // $('.page-header').hide();
                //         $("#printable").html(data);  // Assuming the server returns HTML
                //         //$("#receiveDakToRI").hide(); 
                //         updateCSRFToken();  // Make sure this function updates the CSRF token as needed
                //     },
                //     error: function() {
                //         alert('No Data Found');
                //         updateCSRFToken();  // Ensure CSRF token is refreshed even on error
                //     }
                // });
            }
        });
    });

    $(document).ready(function() {
    setupDispatchQuery();
});

// function setupDispatchQuery() {
//     // Populate years dynamically
//     var yearSelects = ['caseYear', 'diaryYear', 'processYear'];
//     var currentYear = new Date().getFullYear();
//     var selectOptions = '';
//     for (var i = currentYear; i >= 1950; i--) {
//         selectOptions += `<option value="${i}">${i}</option>`;
//     }
//     yearSelects.forEach(function(selectId) {
//         $('#' + selectId).html(selectOptions);
//     });

//     // Handle Search Type Change
//     $('#searchBy').on('change', function() {
//         var searchBy = $(this).val();
//         $('#divCaseTypeWise').toggle(searchBy == '3');
//         $('#divDiaryNoWise').toggle(searchBy == '2');
//         $('#divProcessIdWise').toggle(searchBy == '1');
//         $('#divtextSearch').toggle(['4', '5', '6'].includes(searchBy));
//         $('#divWayBillNumber').toggle(searchBy == '7');
//     });

//     // Handle Search Button Click
//     $('#btnSearch').on('click', function() {
//         var searchBy = $('#searchBy').val();
//         var formData = new FormData();
//         var isValid = true;

//         if (searchBy === '1') {
//             var processId = $('#processId').val();
//             if (!processId) {
//                 alert('Enter Process Id!');
//                 $('#processId').focus();
//                 isValid = false;
//             } else {
//                 formData.append('processId', processId);
//             }
//         } else if (searchBy === '2') {
//             var diaryNumber = $('#diaryNumber').val();
//             if (!diaryNumber) {
//                 alert('Enter Diary Number!');
//                 $('#diaryNumber').focus();
//                 isValid = false;
//             } else {
//                 formData.append('diaryNumber', diaryNumber);
//             }
//         } else if (searchBy === '3') {
//             var caseType = $('#caseType').val();
//             var caseNo = $('#caseNo').val();
//             if (!caseType) {
//                 alert('Select Case Type.');
//                 $('#caseType').focus();
//                 isValid = false;
//             } else {
//                 formData.append('caseType', caseType);
//             }
//             if (!caseNo) {
//                 alert('Enter Case Number.');
//                 $('#caseNo').focus();
//                 isValid = false;
//             } else {
//                 formData.append('caseNo', caseNo);
//             }
//         } else if (['4', '5', '6', '7'].indexOf(searchBy) < 0) {
//             var freeText = $('#freeText').val();
//             if (!freeText) {
//                 alert('Enter Text to Search.');
//                 $('#freeText').focus();
//                 isValid = false;
//             } else if (freeText.length < 4) {
//                 alert('Entered Text length should not be less than 4 characters!');
//                 $('#freeText').focus();
//                 isValid = false;
//             } else {
//                 formData.append('freeText', freeText);
//             }
//         } else if (searchBy === '7') {
//             var wayBillNumber = $('#wayBillNumber').val();
//             if (!wayBillNumber) {
//                 alert('Enter Way Bill Number!');
//                 $('#wayBillNumber').focus();
//                 isValid = false;
//             } else {
//                 formData.append('wayBillNumber', wayBillNumber);
//             }
//         }

//         if (isValid) {
//             // Perform AJAX request to getQueryData
//             $.ajax({
//                 type: 'POST',
//                 url: '<?= base_url() ?>index.php/RIController/getQueryData',
//                 data: formData,
//                 contentType: false,
//                 processData: false,
//                 success: function(data) {
//                     $('#printable').html(data);
//                 },
//                 error: function(error) {
//                     console.error('Error:', error);
//                 }
//             });
//         }
//     });
// }

</script>




