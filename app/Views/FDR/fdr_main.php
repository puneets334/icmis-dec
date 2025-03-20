<?= view('header') ?>

<style>
    .login-box {
        margin: auto;
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
                                <h3 class="card-title">ENTRY</h3>
                            </div>
                            <div class="col-sm-2">

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

                                                    <div class="box-body">
                                                        <div class="row">
                                                            <div class="col-xs-2">
                                                                <div class="input-group input-group-sm"><span class="input-group-addon2">Type</span><select class="form-control" ng-model="type" id="type" ng-change="changeType()">
                                                                        <option value="">Select FD/BG</option>
                                                                        <option value="1" short-desc="FDR No.">Fixed Deposit</option>
                                                                        <option value="2" short-desc="BG No.">Bank Guarantee</option>
                                                                    </select></div>
                                                            </div>
                                                            <div class="col-xs-2">
                                                                <div class="input-group input-group-sm"><span class="input-group-addon2" id="typeNo">FDR No.</span><input type="text" class="form-control" ng-model="fdrNo" id="fdrNo" autocomplete="off" required></div>
                                                            </div>
                                                            <div class="col-xs-3">
                                                                <div class="input-group input-group-sm"><span class="input-group-addon2">A/c No.</span><input type="text" class="form-control" ng-model="acNo" id="acNo" autocomplete="off" required></div>
                                                            </div>
                                                            <div class="col-xs-2">
                                                                <div class="input-group input-group-sm">
                                                                    <span class="input-group-addon2">Amount</span>
                                                                    <input type="text" class="form-control" ng-model="amount" id="amount" autocomplete="off" format>
                                                                </div>
                                                            </div>
                                                            <div class="col-xs-3">
                                                                <div class="input-group input-group-sm"><span class="input-group-addon2">Bank</span><select class="form-control" ng-model="bank" id="bank" required>
                                                                        <option value="">Select Bank Name</option><?php foreach ($banks as $bank) {
                                                                                                                        $bank_arr[$bank['id']] = $bank['bank_name'];
                                                                                                                        echo "<option value='" . $bank['id'] . "'>" . $bank['bank_name'] . "</option>";
                                                                                                                    } ?>
                                                                    </select></div>
                                                            </div>
                                                        </div><br />
                                                        <div class="row">
                                                            <div class="col-xs-2">
                                                                <div class="input-group input-group-sm"><span class="input-group-addon2">Dep. Date</span><input type="date" class="form-control datepicker" ng-model="depositDate" id="depositDate" autocomplete="off" required placeholder="DD-MM-YYYY"></div>
                                                            </div>
                                                            <div class="col-xs-2">
                                                                <div class="input-group input-group-sm"><span class="input-group-addon2">Mat.Date</span><input type="date" class="form-control datepicker" ng-model="maturityDate" id="maturityDate" required autocomplete="off" placeholder="DD-MM-YYYY"></div>
                                                            </div>
                                                            <div class="col-xs-2">
                                                                <div class="input-group input-group-sm"><span class="input-group-addon2">order Date</span><input type="date" class="form-control datepicker" ng-model="orderDate" id="orderDate" autocomplete="off" placeholder="DD-MM-YYYY"></div>
                                                            </div>
                                                            <div class="col-xs-3">
                                                                <div class="input-group input-group-sm"><span class="input-group-addon2">Payment Mode</span><select class="form-control" ng-model="mode" id="mode">
                                                                        <option value="">Select Challan/DD</option>
                                                                        <option value="1">Challan</option>
                                                                        <option value="2">Demand Draft</option>
                                                                        <option value="3">RTGS</option>
                                                                        <option value="4">NEFT</option>
                                                                        <option value="5">None</option>
                                                                    </select></div>
                                                            </div>
                                                            <div class="col-xs-3">
                                                                <div class="input-group input-group-sm"><span class="input-group-addon2">Mode Doc. No.<No class=""></No></span><input type="text" class="form-control" ng-model="modeNo" id="modeNo" autocomplete="off"></div>
                                                            </div>
                                                        </div><br />
                                                        <div class="row">
                                                            <div class="col-xs-3">
                                                                <div class="input-group input-group-sm"><span class="input-group-addon2">Payment Status</span><select class="form-control" ng-model="payStatus" id="payStatus">
                                                                        <option value="">Select Payment Status</option><?php foreach ($status as $stat) {
                                                                                                                            $payStatus_arr[$stat['id']] = $stat['status'];
                                                                                                                            echo "<option value='" . $stat['id'] . "'>" . $stat['status'] . "</option>";
                                                                                                                        } ?>
                                                                    </select></div>
                                                            </div>
                                                            <div class="col-xs-3">
                                                                <div class="input-group input-group-sm"><span class="input-group-addon2">Rate of Interest</span><input type="text" class="form-control" ng-model="roi" id="roi" autocomplete="off" minlength="1"></div>
                                                            </div>
                                                            <div class="col-xs-6">
                                                                <div class="input-group input-group-sm">
                                                                    <span class="input-group-addon2">Remarks</span>
                                                                    <input type="text" class="form-control" ng-model="remarks" id="remarks" autocomplete="off">
                                                                </div>
                                                            </div>

                                                        </div><br />
                                                        <div class="row">
                                                            <div class="col-xs-12">
                                                                <fieldset class="fieldset">
                                                                    <legend class="legend">FDR Tenure</legend>
                                                                    <div class="row">
                                                                    <div class="col-md-3">
                                                                        <div class="input-group input-group-sm">
                                                                            <span class="input-group-addon2">Days</span>
                                                                            <input type="text" class="form-control" ng-model="days" id="days" autocomplete="off" required>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <div class="input-group input-group-sm">
                                                                            <span class="input-group-addon2">Month</span>
                                                                            <input type="text" class="form-control" ng-model="month" id="month" autocomplete="off" required>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <div class="input-group input-group-sm">
                                                                            <span class="input-group-addon2">Year</span>
                                                                            <input type="text" class="form-control" ng-model="year" id="year" autocomplete="off" required>
                                                                        </div>
                                                                    </div> </div>
                                                                </fieldset>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="box-footer">
                                                        <!-- <a id="btn-create-fdr" class="btn bg-olive btn-flat" ng-click="create_fdr()"><i class="fa fa-heart"> </i>Create</a> -->

                                                        <button type="submit" class="btn bg-olive btn-flat">Create</button>
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
                                <td style='cursor:pointer;' >" . $payStatus_arr[$row['ref_status_id']] . "</td>
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
                                <td style='cursor:pointer;' ng-click='deleteOne(" . $row['id'] . ")'><span style='color: red' class='glyphicon glyphicon-trash'></span></td>
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


        let csrfName = $("#csrf_token").attr('name');
        let csrfHash = $("#csrf_token").val();
        $('#fdrForm').on('submit', function(e) {
            e.preventDefault(); // Prevent the form from submitting the traditional way

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
                        alert('FDR created successfully!');
                    } else {
                        alert('Error creating FDR!');
                    }
                    updateCSRFToken()
                },
                error: function(xhr, status, error) {
                    console.log('AJAX Error:', error);
                    alert('Failed to create FDR!');
                    updateCSRFToken()
                }
            });
        });
    });
</script>