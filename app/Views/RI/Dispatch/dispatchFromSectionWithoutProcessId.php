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
 <!-- <div class="container-fluid" ng-app="rIDispatchWithoutProcessIdApp"  ng-controller="rIDispatchWithoutProcessIdController"> -->
 <section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Dispatch Letter to R&I Without Process ID</h3>
                            </div>
                        </div>
                    </div></br>
                    <form id="dispatchDakToRIWithoutProcessId" method="post">
                        <!-- <meta name="csrf-token" content="<?= csrf_hash() ?>">
                        <input type="hidden" name="CSRF_TOKEN" value="<?= csrf_hash() ?>"> -->
                        <?= csrf_field(); ?>
                        <div class="row">
                            <div class="form-group col-sm-3">
                                <label for="docType">Document Type</label>
                                <select class="form-control" id="docType" >
                                    <option value="L">Letter</option>
                                    <option value="D">Decree</option>
                                </select>
                            </div>
                        </div>
                        <!--divCaseNumber Start-->
                        <div class="row">
                            <div class="form-group col-sm-12">
                                <div id="divLetter">
                                    <div class="form-group col-sm-3">
                                        <label for="referenceNumber">Reference Number</label>
                                        <input type="text" id="referenceNumber" class="form-control" placeholder="Reference Number">    
                                    </div>
                                </div>
                                <div id="divDecree" style="display:none;">
                                    <div class="form-group col-sm-12">
                                        <label class="radio-inline"><input type="radio" name="optradio" value="1" checked>Case Number</label>
                                        <label class="radio-inline"><input type="radio" name="optradio" value="2">Diary No.</label>
                                    </div>
                                    <div id="divCaseTypeWise">
                                        <div class="row">
                                            <div class="form-group col-sm-3">
                                                <label for="caseType">Case Type</label>
                                                <select class="form-control" id="caseType">
                                                    <option value="">Select</option>
                                                    <?php foreach ($caseTypes as $caseType): ?>
                                                        <option value="<?= $caseType['casecode'] ?>"><?= $caseType['short_description'] ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="form-group col-sm-3">
                                                <label for="caseNo">Case Number</label>
                                                <input type="number" id="caseNo" class="form-control" placeholder="Case Number">
                                            </div>
                                            <div class="form-group col-sm-3">
                                                <label for="caseYear">Case Year</label>
                                                <select id="caseYear" class="form-control">
                                                    <?php for ($i = date("Y"); $i > 1949; $i--): ?>
                                                        <option value="<?= $i ?>"><?= $i ?></option>
                                                    <?php endfor; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="divDiaryNoWise">
                                        <div class="row">
                                            <div class="form-group col-sm-3">
                                                <label for="diaryNumber">Diary Number</label>
                                                <input type="number" id="diaryNumber" class="form-control" placeholder="Diary Number">
                                            </div>
                                            <div class="form-group col-sm-3">
                                                <label for="diaryYear">Diary Year</label>
                                                <select id="diaryYear" class="form-control">
                                                    <?php for ($i = date("Y"); $i > 1949; $i--): ?>
                                                        <option value="<?= $i ?>"><?= $i ?></option>
                                                    <?php endfor; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="margin-top:2.5rem;">
                            <div class="form-group col-sm-3">
                                <label for="from">From Section</label>
                                <select class="form-control" id="dealingSection" <?php //if($userData[0]['section'] != 68) echo  "disabled"; ?>>
                                    <option value="">Select</option>
                                    <?php foreach ($dealingSections as $dealingSection): ?>
                                        <option value="<?= $dealingSection['id'] ?>"><?= $dealingSection['section_name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group col-sm-3">
                                <label for="from">Dispatch Mode</label>
                                <select class="form-control" id="dispatchMode">
                                    <option value="">Select Mode</option>
                                    <?php foreach($dispatchModes as $mode): ?>
                                        <option value="<?= $mode['id'] ?>"><?= $mode['postal_type_description'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-3">
                                <label for="sendTo">Send To(Name)</label>
                                <input type="Text" id="sendTo" class="form-control" placeholder="Send To">
                            </div>
                            <div class="form-group col-sm-3">
                                <label for="address">Address</label>
                                <textarea class="form-control" id="address" rows="2" placeholder="Address ..."></textarea>
                            </div>
                            <div class="form-group col-sm-3">
                                <label for="Pincode">Pincode</label>
                                <input type="text" id="pincode" class="form-control number" placeholder="pincode" maxlength="6">
                            </div>
                        </div>
                        <div class="formgroup col-sm-12">
                            <button type="button" id="btnDispatchLetter" class="btn btn-primary form-control">Dispatch to R&I</button>
                        </div>
                    </form>
                    <br>
                    <div id="dataForReceiving" style="text-align: center;padding: 13px;font-size: 20px;">

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
     function updateCSRFToken() {
        $.getJSON("<?php echo base_url('Csrftoken'); ?>", function(result) {
            $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
        });
      }
        // document.addEventListener('DOMContentLoaded', function () {
        //     var csrfToken = document.querySelector('input[name="CSRF_TOKEN"]').value;
        //     var form = document.getElementById('dispatchDakToRIWithoutProcessId');
        //     var btnDispatchLetter = document.getElementById('btnDispatchLetter');
        //     var dataForReceiving = document.getElementById('dataForReceiving');
        //     var docType = document.getElementById('docType').value;
        //     if (docType === 'L') {
        //                 divDecree.style.display = 'none'; // Hide the div
        //             } else {
        //                 divDecree.style.display = 'block'; // Show the div
        //             }
        //     btnDispatchLetter.addEventListener('click', check);
        // });
        // document.addEventListener('DOMContentLoaded', function () {
        //     var docType = document.getElementById('docType').value;
        //     var divDecree = document.getElementById('divDecree');
        //     if (docType === 'L') {
        //         divDecree.style.display = 'none'; // Hide the div
        //     } else {
        //         divDecree.style.display = 'block'; // Show the div
        //     }
        //     docType.addEventListener('change', function () {
        //         updateVisibility(); // Call the method whenever the selection changes
        //     });
        // });
        // function updateVisibility() {
        //     var docType = document.getElementById('docType').value;
        //     if (docType === 'L') {
        //         divDecree.style.display = 'none'; // Hide the div
        //     } else {
        //         divDecree.style.display = 'block'; // Show the div
        //     }
        // }
    document.addEventListener('DOMContentLoaded', function () {
    var docTypeSelect = document.getElementById('docType');
    var divDecree = document.getElementById('divDecree');
   // var csrfToken = document.querySelector('input[name="CSRF_TOKEN"]').value;
    var form = document.getElementById('dispatchDakToRIWithoutProcessId');
    var btnDispatchLetter = document.getElementById('btnDispatchLetter');
    var dataForReceiving = document.getElementById('dataForReceiving');

    // Function to update the visibility of divDecree
    function updateVisibility() {
        var docType = docTypeSelect.value;  // Get the selected value
        if (docType === 'L') {
            divDecree.style.display = 'none'; // Hide divDecree for 'Letter'
        } else {
            divDecree.style.display = 'block'; // Show divDecree for 'Decree'
        }
    }

    // Call the function once when the page loads to set the initial state
    updateVisibility();

    // Add event listener to call the function when the dropdown value changes
    docTypeSelect.addEventListener('change', function () {
        updateVisibility();
    });
    btnDispatchLetter.addEventListener('click', check);
});


    function check() {
        var docType = document.getElementById('docType').value;
        //alert(docType);
        var referenceNumber = document.getElementById('referenceNumber').value;
        var optradio = document.querySelector('input[name="optradio"]:checked').value;
        var caseType = document.getElementById('caseType').value;
        var caseNo = document.getElementById('caseNo').value;
        var caseYear = document.getElementById('caseYear').value;
        var diaryNumber = document.getElementById('diaryNumber').value;
        var diaryYear = document.getElementById('diaryYear').value;
        var dealingSection = document.getElementById('dealingSection').value;
        var dispatchMode = document.getElementById('dispatchMode').value;
        var sendTo = document.getElementById('sendTo').value;
        var address = document.getElementById('address').value;
        var pincode = document.getElementById('pincode').value;

        if (docType == "L" && !referenceNumber) {
            alert("Enter Letter Reference Number!");
            document.getElementById('referenceNumber').focus();
            return false;
        }

        if (docType == "D") {
            if (optradio == 1) {
                if (!caseType) {
                    alert("Select Case Type.");
                    document.getElementById('caseType').focus();
                    return false;
                }
                if (!caseNo) {
                    alert("Enter Case Number.");
                    document.getElementById('caseNo').focus();
                    return false;
                }
            } else if (optradio == 2) {
                if (!diaryNumber) {
                    alert("Enter Diary Number.");
                    document.getElementById('diaryNumber').focus();
                    return false;
                }
            }
        }

        if (!dealingSection) {
            alert("Select Section.");
            document.getElementById('dealingSection').focus();
            return false;
        }
        if (!dispatchMode) {
            alert("Select Dispatch Mode.");
            document.getElementById('dispatchMode').focus();
            return false;
        }
        if (!sendTo) {
            alert("Send To (Name) should not be blank.");
            document.getElementById('sendTo').focus();
            return false;
        }
        if (!address) {
            alert("Address should not be blank.");
            document.getElementById('address').focus();
            return false;
        }
        if (pincode && pincode.length != 6) {
            alert("Pincode should not be less than 6 digits.");
            document.getElementById('pincode').focus();
            return false;
        }updateCSRFToken();
       // btnDispatchLetter.disabled = true;
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        var formData = {
            'docType' :docType,
            'referenceNumber': referenceNumber,
            'optradio': optradio,
            'caseType': caseType,
            'caseNo': caseNo,
            'caseYear': caseYear,
            'diaryNumber': diaryNumber,
            'diaryYear': diaryYear,
            'dealingSection': dealingSection,
            'dispatchMode': dispatchMode,
            'sendTo': sendTo,
            'address': address,
            'pincode': pincode,
            CSRF_TOKEN: CSRF_TOKEN_VALUE
        };
        //var dataToSend = new FormData(form);
        // dataToSend.append('CSRF_TOKEN', csrfToken);
        // alert(dataToSend);

        var dynamicUrl = "<?php echo  base_url() ?>/RI/DispatchController/doDispatchFromSectionToRIWithoutProcessId'";
        $.ajax({
        url: dynamicUrl, 
        type: "POST",
        data: formData, 
         success: function(data) {
         updateCSRFToken();
         $('#dispatchDakToRIWithoutProcessId').trigger("reset");
         $("#dataForReceiving").html(data);
         //$("#dispatchDakToRI").hide(); 
        },
        error: function(xhr, status, error) {
            updateCSRFToken();
            //$('#dispatchDakToRIWithoutProcessId').trigger("reset");
            console.log("An error occurred: " + error);
        }
    });

       
    }

  
</script>

<script>
    //ngScope=angular.element($('[ng-app="rIDispatchWithoutProcessIdApp"]')).scope();
    $(function () {
        $('.datepick').datepicker({
            format: 'dd-mm-yyyy',
            todayHighlight: true,
            autoclose: true,
            autocomplete: false
        });
    });
    $('.number').keypress(function (event) {
        if (event.which == 8 || event.keyCode == 37 || event.keyCode == 39 || event.keyCode == 46)
            return true;

        else if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57))
            event.preventDefault();
    });

</script>
 <?//=view('sci_main_footer') ?>