<?= view('header') ?>

<style>
    .login-box {
        margin: auto;
    }
</style>
<div id="error-message" class="alert alert-danger" style="position: fixed; z-index: 10000;"></div>
<div id="success-message" class="alert alert-success" style="position: fixed; z-index: 10000;"></div>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">

                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">ENTRY</h3>
                            </div>
                            <div class="col-sm-2">
                                <div class="custom_action_menu">
                                    <a href="<?= base_url() ?>/CashAccounts/Fdr"><button class="btn btn-info btn-sm" type="button" title="Diary Search"><i class="fa fa-search-plus" aria-hidden="true"></i></button></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">


                                    <div class="wrapper">
                                        <section class="content" ng-app="fdrApp" ng-controller="fdrCtrl" data-ng-init="showCreateForm()">
                                            <?php
                                            $bank_arr = array();
                                            $payStatus_arr = array();
                                            ?>
                                            <ol class="breadcrumb">
                                                <li><b><?= $caseInfo[0]['registration_number_display'] ?></b></li>
                                                <li><b><?= $caseInfo[0]['petitioner_name'] . ' vs ' . $caseInfo[0]['respondent_name'] ?></b></li>
                                                <li><b><?= $caseInfo[0]['section_name'] ?></b></li>
                                            </ol>
                                            <div class="box box-primary">
                                                <div class="box-header with-border">
                                                    <h3 class="box-title" id="form-title">FDR</h3>
                                                </div>
                                                <form id="fdrForm">
                                                    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" id="csrf_token" />
                                                    <input type="hidden" name="" id="ec_case_id" value="123" />
                                                    <input type="hidden" name="" id="petitioner_name" value="KANWAR SAIN" />
                                                    <input type="hidden" name="" id="section_id" value="123" />
                                                    <input type="hidden" name="" id="respondent_name" value="STATE OF HARYANA " />


                                                    <div class="">
                                                        <div class="box-body">
                                                            <div class="row mb-3">
                                                                <div class="col-md-2">
                                                                    <label for="type">Type</label>
                                                                    <select class="form-control form-control-sm" id="type" ng-model="type" ng-change="changeType()">
                                                                        <option value="">Select FD/BG</option>
                                                                        <option value="1">Fixed Deposit</option>
                                                                        <option value="2">Bank Guarantee</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <label for="fdrNo">FDR No.</label>
                                                                    <input type="text" class="form-control form-control-sm" id="fdrNo" ng-model="fdrNo" required>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <label for="acNo">A/c No.</label>
                                                                    <input type="text" class="form-control form-control-sm" id="acNo" ng-model="acNo" required>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <label for="amount">Amount</label>
                                                                    <input type="text" class="form-control form-control-sm" id="amount" ng-model="amount" format>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <label for="bank">Bank</label>
                                                                    <select class="form-control form-control-sm" id="bank" ng-model="bank" required>
                                                                        <option value="">Select Bank Name</option>
                                                                        <?php foreach ($banks as $bank) { 
                                                                                $bank_arr[$bank['id']] = $bank['bank_name'];
                                                                                echo "<option value='" . $bank['id'] . "'>" . $bank['bank_name'] . "</option>"; 
                                                                            } ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <div class="col-md-2">
                                                                    <label for="depositDate">Dep. Date</label>
                                                                    <input type="text" class="form-control form-control-sm datepicker" id="depositDate" ng-model="depositDate" placeholder="DD-MM-YYYY" required>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <label for="maturityDate">Mat. Date</label>
                                                                    <input type="text" class="form-control form-control-sm datepicker" id="maturityDate" ng-model="maturityDate" placeholder="DD-MM-YYYY" required>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <label for="orderDate">Order Date</label>
                                                                    <input type="text" class="form-control form-control-sm datepicker" id="orderDate" ng-model="orderDate" placeholder="DD-MM-YYYY">
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <label for="mode">Payment Mode</label>
                                                                    <select class="form-control form-control-sm" id="mode" ng-model="mode">
                                                                        <option value="">Select Challan/DD</option>
                                                                        <option value="1">Challan</option>
                                                                        <option value="2">Demand Draft</option>
                                                                        <option value="3">RTGS</option>
                                                                        <option value="4">NEFT</option>
                                                                        <option value="5">None</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <label for="modeNo">Mode Doc. No.</label>
                                                                    <input type="text" class="form-control form-control-sm" id="modeNo" ng-model="modeNo">
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <div class="col-md-3">
                                                                    <label for="payStatus">Payment Status</label>
                                                                    <select class="form-control form-control-sm" id="payStatus" ng-model="payStatus">
                                                                        <option value="">Select Payment Status</option>
                                                                        <?php foreach ($status as $stat) { echo "<option value='" . $stat['id'] . "'>" . $stat['status'] . "</option>"; } ?>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <label for="roi">Rate of Interest</label>
                                                                    <input type="text" class="form-control form-control-sm" id="roi" ng-model="roi" maxlength="10">
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label for="remarks">Remarks</label>
                                                                    <input type="text" class="form-control form-control-sm" id="remarks" ng-model="remarks">
                                                                </div>
                                                            </div>
                                                            <fieldset class="border p-3">
                                                                <legend class="w-auto">FDR Tenure</legend>
                                                                <div class="row">
                                                                    <div class="col-md-3">
                                                                        <label for="days">Days</label>
                                                                        <input type="text" class="form-control form-control-sm" id="days" ng-model="days" required>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <label for="month">Month</label>
                                                                        <input type="text" class="form-control form-control-sm" id="month" ng-model="month" required>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <label for="year">Year</label>
                                                                        <input type="text" class="form-control form-control-sm" id="year" ng-model="year" required>
                                                                    </div>
                                                                </div>
                                                            </fieldset>
                                                        </div>
                                                    </div>


                                                    <div class="box-footer">
                                                        <!-- <a id="btn-create-fdr" class="btn bg-olive btn-flat" ng-click="create_fdr()"><i class="fa fa-heart"> </i>Create</a> -->

                                                        <a id="btn-create-fdr" class="btn bg-olive btn-flat" ng-click="createFdr()" >Create</a>
                                                        <a id="btn-update-fdr" class="btn bg-purple btn-flat" ng-click="updateFdr()"> Save Changes</a>
                                                        <a id="btn-updateCancel-fdr" class="btn btn-primary" ng-click="showCreateForm()">Cancel Editing</a>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="box box-success">
                                                <div class="box-header with-border">
                                                    <h3 class="box-title" id="form-title">FDR records</h3>
                                                </div>
                                                <table class="table table-striped table-hover ">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Type</th>
                                                            <th>FDR/BG No.</th>
                                                            <th>A/C No.</th>
                                                            <th>Amount</th>
                                                            <th>Bank</th>
                                                            <th>Deposit_Date</th>
                                                            <th>Maturity/Expiry Date <span class="glyphicon glyphicon-chevron-up"></span></th>
                                                            <th>Order_Date</th>
                                                            <th>Payment Status</th>
                                                            <th>Rate of Interest</th>
                                                            <th>Tenure</th>
                                                            <th>Remarks</th>
                                                            <th>Delete</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php

                                                        // echo "<pre>";
                                                        // print_r($result); 
                                                        // echo "</pre>";
                                                        $sNo = 1;
                                                        foreach ($result as $row) {
                                                            switch ($row['type']) {
                                                                case "1":
                                                                    $type = "Fixed Deposit";
                                                                    break;
                                                                case "2":
                                                                    $type = "Bank Guarantee";
                                                                    break;
                                                                default:
                                                                    $type = "";
                                                                    break;
                                                            }
                                                            echo "<tr ng-click='readOne(" . $row['id'] . ")'>
                                                                    <td style='cursor:pointer;' >$sNo</td>
                                                                    <td style='cursor:pointer;' >" . $type . "</td>
                                                                    <td style='cursor:pointer;' >" . $row['document_number'] . "</td>
                                                                    <td style='cursor:pointer;' >" . $row['account_number'] . "</td>
                                                                    <td>" . $row['amount'] . "</td>
                                                                    <td style='cursor:pointer;' >" . $bank_arr[$row['ref_bank_id']] . "</td>
                                                                    <td style='cursor:pointer;' >" . date('d-m-Y', strtotime($row['deposit_date'])) . "</td>
                                                                    <td style='cursor:pointer;' >" . date('d-m-Y', strtotime($row['maturity_date'])) . "</td>
                                                                    <td style='cursor:pointer;' >" . date('d-m-Y', strtotime($row['order_date'])) . "</td>
                                                                    <td style='cursor:pointer;' >" . $row['ref_status_id'] . "</td>
                                                                    <td style='cursor:pointer;'>" . $row['roi'] . "</td>
                                                                    <td style='cursor:pointer;'> ";
                                                            if ($row['days'] != 0 && $row['days'] != " ") {
                                                                echo $row['days'] . " days";
                                                            } else if ($row['month'] != 0 && $row['month'] != " ") {
                                                                echo $row['month'] . " month";
                                                            } else  if ($row['year'] != 0 && $row['year'] != " ") {
                                                                echo $row['year'] . " year";
                                                            }
                                                            echo "</td>
                                                                <td style='cursor:pointer;' >" . $row['remarks'] . "</td>
                                                                <td style='cursor:pointer;' ng-click='deleteOne(" . $row['id'] . ")'><span style='color: red' class='fa fa-trash'></span></td>
                                                            </tr>";
                                                            $sNo++;
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </section>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    $(document).ready(function() {

        $('#fdrForm').on('submit', async function(e) {
            e.preventDefault(); // Prevent the form from submitting the traditional way

            await updateCSRFTokenSync();

            let csrfName = $("#csrf_token").attr('name');
            let csrfHash = $("#csrf_token").val();

            // Prepare form data for submission
            var formData = {
                type: $('#type').val(),
                fdrNo: $('#fdrNo').val(),
                acNo: $('#acNo').val(),
                amount: $('#amount').val(),
                bank: $('#bank').val(),
                ec_case_id: $('#ec_case_id').val(),
                petitioner_name: $('#petitioner_name').val(),
                respondent_name: $('#respondent_name').val(),
                section_id: $('#section_id').val(),
                depositDate: $('#depositDate').val(),
                maturityDate: $('#maturityDate').val(),
                orderDate: $('#orderDate').val(),
                mode: $('#mode').val(),
                modeNo: $('#modeNo').val(),
                payStatus: $('#payStatus').val(),
                roi: $('#roi').val(),
                remarks: $('#remarks').val(),
                days: $('#days').val(),
                month: $('#month').val(),
                year: $('#year').val(),
                [csrfName]: csrfHash

            };

            $.ajax({
                url: '<?= base_url('CashAccounts/Fdr/create_fdr'); ?>',
                type: 'POST',
                data: JSON.stringify(formData),
                contentType: 'application/json',
                success: function(response) {
                    var data = JSON.parse(response);
                    if (data.status === 'success') {
                        display_success("FDR created successfully!");
                        // alert('FDR created successfully!');
                    } else {
                        display_error("Error creating FDR!");
                        alert('Error creating FDR!');
                    }
                },
                error: function(xhr, status, error) {
                    console.log('AJAX Error:', error);
                    display_error("Failed to create FDR!");
                    // alert('Failed to create FDR!');
                }
            });
        });
    });
</script>
<script>
    $('.datepicker').datepicker({
        format: 'dd-mm-yyyy',
        autoclose:'true'
    });

 $('#amoun').keyup(function(event) {

        // skip for arrow keys
        if(event.which >= 37 && event.which <= 40) return;

        // format number
        $(this).val(function(index, value) {
            return value
                .replace(/\D/g, "")
                .replace(/\B(?=(\d{3})+(?!\d))/g, ",")
                ;
        });
    });

    $(document).ready(function(){
        $('#error-message').hide();
        $('#success-message').hide();
    });

    function display_success(message, focus_element='')
    {
        $('#success-message').text(message).show().delay(3000).fadeOut(500);
        if(focus_element != '') {
            $('#'+focus_element).trigger('focus');
        }
    }

    function display_error(message, focus_element='')
    {
        $('#error-message').text(message).show().delay(3000).fadeOut(500);
        if(focus_element != '') {
            $('#'+focus_element).trigger('focus');
        }
    }


</script>
<script>
    var app = angular.module('fdrApp', []);

    /*
         app.filter('INR', function () {
         return function (input) {
         if (! isNaN(input)) {
         var currencySymbol = '₹';
         //var output = Number(input).toLocaleString('en-IN');   <-- This method is not working fine in all browsers!
         var result = input.toString().split('.');

         var lastThree = result[0].substring(result[0].length - 3);
         var otherNumbers = result[0].substring(0, result[0].length - 3);
         if (otherNumbers != '')
         lastThree = ',' + lastThree;
         var output = otherNumbers.replace(/\B(?=(\d{2})+(?!\d))/g, ",") + lastThree;

         if (result.length > 1) {
         output += "." + result[1];
         }

         return currencySymbol + output;
         }
         }
         });
     */
    app.directive('format', function ($filter) {
        'use strict';

        return {
            require: '?ngModel',
            link: function (scope, elem, attrs, ctrl) {
                if (!ctrl) {
                    return;
                }

                ctrl.$formatters.unshift(function () {
                    return $filter('number')(ctrl.$modelValue);
                });


                ctrl.$parsers.unshift(function (viewValue) {
                    var plainNumber = viewValue.replace(/[\,\.]/g, ''), b = $filter('number')(plainNumber);
                    elem.val(b);

                    return plainNumber;
                });
            }
        };
    });

    app.controller('fdrCtrl', function($scope, $http) {
        $scope.showCreateForm = function () {
            // clear form
            $scope.clearForm();
            // change form title
            $('#form-title').text("Enter New FDR/BG");
            // hide update product button
            $('#btn-update-fdr').hide();
            $('#btn-updateCancel-fdr').hide();
            // show create product button
            $('#btn-create-fdr').show();
        }

        $scope.changeType = function () {
            $('#typeNo').html($('#type option[value="' + $scope.type + '"]').attr('short-desc'));
        }

        $scope.clearForm = function () {
            $scope.type = "";
            $scope.fdrNo = "";
            $scope.acNo = "";
            $scope.amount = "";
            $scope.bank = "";
            $scope.depositDate = "";
            $scope.maturityDate = "";
            $scope.orderDate = "";
            $scope.mode = "";
            $scope.modeNo = "";
            $scope.payStatus = "";
            $scope.roi = "";
            $scope.days = "";
            $scope.month = "";
            $scope.year = "";
            $scope.remarks = "";
        }

        // create new FDR
        $scope.createFdr = async function () {

            // fields in key-value pairs
            if ($scope.type == '') {
                display_error("Invalid Type!");
            } else if ($scope.fdrNo == '') {
                display_error("Invalid FDR No!");
            } else if ($scope.acNo == '') {
                display_error("Invalid A/c No!");
            } else if ($scope.amount == '') {
                display_error("Invalid Amount!");
            } else if ($scope.bank == '') {
                display_error("Invalid Bank!");
            } else if ($scope.depositDate == '') {
                display_error("Invalid Dep. Date!");
            } else if ($scope.maturityDate == '') {
                display_error("Invalid Mat.Date!");
            } else if ($scope.orderDate == '') {
                display_error("Invalid order Date!");
            } else if ($scope.mode == '') {
                display_error("Invalid Payment Mode!");
            } else if ($scope.modeNo == '' && $scope.mode < 5) {
                display_error("Invalid Mode Doc. No!");
            } else if ($scope.payStatus == '') {
                display_error("Invalid Payment Status!");
            } else if ($scope.roi != '' && $scope.roi >= 10) {
                display_error("Invalid Rate of Interest can't be more then 9.");
            } else if ($scope.days == '' || $scope.month > 31) {
                display_error("Invalid FDR Tenure Days!");
            } else if ($scope.month == '' || $scope.month > 12) {
                display_error("Invalid FDR Tenure Month!");
            } else if ($scope.year == '' || $scope.month > 9999) {
                display_error("Invalid FDR Tenure Year!");
            } else if ($scope.type != '' && $scope.fdrNo != '' && $scope.bank != '' 
                        && $scope.amount != '' && $scope.orderDate != '' && $scope.depositDate != '' 
                        && $scope.maturityDate != '' && $scope.acNo != ''
            ) {
                await updateCSRFTokenSync();

                var CSRF_TOKEN = 'CSRF_TOKEN';
                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

                $http.post('<?=base_url('CashAccounts/Fdr/create_fdr'); ?>', {
                        'type': $scope.type,
                        'fdrNo': $scope.fdrNo,
                        'acNo': $scope.acNo,
                        'amount': $scope.amount,
                        'bank': $scope.bank,
                        'ec_case_id': "<?=$caseInfo[0]['id']?>",
                        'depositDate': $scope.depositDate,
                        'maturityDate': $scope.maturityDate,
                        'orderDate': $scope.orderDate,
                        'mode': $scope.mode,
                        'modeNo': $scope.modeNo,
                        'payStatus': $scope.payStatus,
                        'roi': $scope.roi,
                        'days': $scope.days,
                        'month': $scope.month,
                        'year': $scope.year,
                        'remarks': $scope.remarks,
                        'id': <?=$caseInfo[0]['id']?>,
                        'petitioner_name': "<?=$caseInfo[0]['petitioner_name']?>",
                        'respondent_name': "<?=$caseInfo[0]['respondent_name']?>",
                        'caseNoDisplay': "<?=$caseInfo[0]['registration_number_display']?>",
                        'section_id': <?=$caseInfo[0]['org_dealing_section_id']?>,
                        CSRF_TOKEN: CSRF_TOKEN_VALUE
                    }
                ).then(function successCallback(response) {

                    display_success("Record added successfully.");
                    $scope.clearForm();
                    $scope.getAll();
                });
            }
        }

        $scope.getAll = function () {
            
            setTimeout(() => {
                window.location.href = base_url + '/CashAccounts/Fdr/reload?caseType=<?php echo $caseType; ?>&caseNo=<?php echo $caseNo; ?>&caseYear=<?php echo $caseYear; ?>';
            }, 3000);

            // window.location.reload();
        }

        // retrieve record to fill out the form
        $scope.readOne = async function (id) {
            await updateCSRFTokenSync();

            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

            // change form title
            $('#form-title').text("Edit FDR/BG");
            // show udpate product button
            $('#btn-update-fdr').show();
            $('#btn-updateCancel-fdr').show();
            // show create product button
            $('#btn-create-fdr').hide();

            // post id of product to be edited
            $http.post('<?=base_url('CashAccounts/Fdr/readOneRecord'); ?>', {
                'id': id,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            })
                .then(function successCallback(response) {
                    
                    // console.log(response);

                    // put the values in form
                    $scope.id = response.data[0]["id"];
                    $scope.type = response.data[0]["type"];
                    $scope.fdrNo = response.data[0]["document_number"];
                    $scope.acNo = response.data[0]["account_number"];
                    $scope.amount = response.data[0]["amount"].toLocaleString('en-IN');
                    $scope.bank = response.data[0]["ref_bank_id"];
                    $scope.depositDate = response.data[0]["deposit_date"];
                    $scope.maturityDate = response.data[0]["maturity_date"];
                    $scope.orderDate = response.data[0]["order_date"];
                    $scope.mode = response.data[0]["mode_code"];
                    $scope.modeNo = response.data[0]["mode_document_number"];
                    $scope.payStatus = response.data[0]["ref_status_id"];
                    $scope.roi = response.data[0]["roi"];
                    $scope.days = response.data[0]["days"];
                    $scope.month = response.data[0]["month"];
                    $scope.year = response.data[0]["year"];
                    $scope.remarks = response.data[0]["remarks"];
                    $scope.changeType();
                }, function errorCallback(response) {
                    display_error("Unable to retrieve record.");
                    // sweetAlert("Oops...", "Unable to retrieve record.", "error");
                });
        }

        // delete record to fill out the form
        $scope.deleteOne = async function (id) {

            // ask the user if he is sure to delete the record
            if (confirm("Are you sure?")) {

                await updateCSRFTokenSync();

                var CSRF_TOKEN = 'CSRF_TOKEN';
                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

                // post the id of product to be deleted
                $http.post('<?=base_url('CashAccounts/Fdr/deleteFdr'); ?>', {
                    'id': id,
                    CSRF_TOKEN: CSRF_TOKEN_VALUE
                }).then(function successCallback(response) {

                    // tell the user product was deleted
                    display_success("Record deleted successfully.");
                    $scope.getAll();

                    // sweetAlert({
                    //         title: "Success!",
                    //         text: "Record deleted successfully.",
                    //         type: "success"
                    //     },
                    //     function () {
                    //         // refresh the list
                    //         $scope.getAll();
                    //     });
                });
            }
        }

        // update fdr record / save changes
        $scope.updateFdr = async function () {
            if ($scope.type != '' && $scope.fdrNo != '' && $scope.bank != '' && $scope.amount != '') {
                await updateCSRFTokenSync();

                var CSRF_TOKEN = 'CSRF_TOKEN';
                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

                $http.post('<?=base_url('CashAccounts/Fdr/updateFdr'); ?>', {

                    'id': $scope.id,
                    'type': $scope.type,
                    'fdrNo': $scope.fdrNo,
                    'acNo': $scope.acNo,
                    'amount': $scope.amount,
                    'bank': $scope.bank,
                    'depositDate': $scope.depositDate,
                    'maturityDate': $scope.maturityDate,
                    'orderDate': $scope.orderDate,
                    'mode': $scope.mode,
                    'modeNo': $scope.modeNo,
                    'payStatus': $scope.payStatus,
                    'roi': $scope.roi,
                    'days': $scope.days,
                    'month': $scope.month,
                    'year': $scope.year,
                    'remarks': $scope.remarks,
                    CSRF_TOKEN: CSRF_TOKEN_VALUE
                })
                    .then(function successCallback(response) {
                        // clear modal content
                        $scope.clearForm();
                        // tell the user fdr record was updated
                        
                        display_success("Record updated successfully.");
                        $scope.getAll();

                        // sweetAlert({
                        //         title: "Success!",
                        //         text: "Record updated successfully.",
                        //         type: "success"
                        //     },
                        //     function () {
                        //         // refresh the list
                        //         $scope.getAll();
                        //     });
                    });
            }
    else
        {
            display_error("Please enter some value.");
            $scope.getAll();
            // sweetAlert({
            //         title: "Error!",
            //         text: "Please enter some value.",
            //         type: "error"
            //     },
            //     function () {
            //         // refresh the product list
            //         $scope.getAll();
            //     });
        }
    }
    });
</script>