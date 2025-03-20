<?= view('header'); ?>

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


    #cover-spin::after {
        content: '';
        display: block;
        position: absolute;
        left: 48%;
        top: 40%;
        width: 40px;
        height: 40px;
        border-style: solid;
        border-color: black;
        border-top-color: transparent;
        border-width: 4px;
        border-radius: 50%;
        -webkit-animation: spin 0.8s linear infinite;
        animation: spin 0.8s linear infinite;
    }

    @-webkit-keyframes spin {
        from {
            -webkit-transform: rotate(0deg);
        }

        to {
            -webkit-transform: rotate(360deg);
        }
    }

    @keyframes spin {
        from {
            transform: rotate(0deg);
        }

        to {
            transform: rotate(360deg);
        }
    }
</style>
<div ng-app="eFilingApp" ng-controller="eFilingCtrl" data-ng-init="clearForm()">

    <div ng-show="loadingData === 1" id="cover-spin"></div>
    <!-- Full Width Column -->
    <section class="content">
        <div class="container-fluid">
            <!-- Main content -->
            <section class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header heading">
                            <div class="row">
                                <div class="col-sm-10">
                                    <h3 class="card-title">Filing >> Efiling >> Admin</h3>
                                </div>
                                <div class="col-sm-2">

                                </div>
                            </div>
                        </div>
                        <?= view('Filing/Efiling/Efiling_breadcrumb'); ?>


                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="box-body">
                                        <h5 class="box-title  pl-4">E-filed Applications- Refiling Report + Additional Documents report for Scrutiny Assistants (Dealing Assistant wise) </h5>
                                      
                                        <div class="box-body">
                                            <?= csrf_field() ?>
                                            <div class="row">
                                                <div class="form-group col-sm-2">
                                                    <label for="causelistDate">From Date:</label>
                                                    <div class="form-group">

                                                        <input type="text" class="form-control pickDate" id="from_date" ng-model="fields.from_date" placeholder="dd-mm-yyyy" autocomplete="off" required>
                                                    </div>
                                                </div>
                                                <div class="form-group col-sm-2">
                                                    <label for="causelistDate">To Date:</label>
                                                    <div class="form-group">

                                                        <input type="text" class="form-control pickDate" id="to_date" ng-model="fields.to_date" placeholder="dd-mm-yyyy" autocomplete="off" required>
                                                    </div>
                                                </div>
                                                <div class="form-group col-sm-2">
                                                    <label for="causelistDate">Transaction Status:</label>
                                                    <div class="form-group">
                                                        <select class="form-control" id="status" ng-model="fields.status" required="true">
                                                            <option value="">Select</option>
                                                            <option value="1">Complete</option>
                                                            <option value="2">Failed Transactions</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group col-sm-2">
                                                    <label for="causelistDate">Document Type:</label>
                                                    <div class="form-group">
                                                        <select class="form-control" id="app_type" ng-model="fields.app_type" required="true">
                                                            <option value="">Select</option>
                                                            <option value="1">Refiling Documents</option>
                                                            <option value="2">Additional Documents</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group col-sm-2">
                                                    <label>&nbsp;</label>
                                                    <button type="button" id="btn-shift-assign" class="btn btn-block bg-olive btn-flat pull-right mt-5" ng-click="get_transaction_details()"><i class="fa fa-save"></i> Search </button>
                                                    </button>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="box box-success" ng-if="transactions_list">
                            <div class="box-header with-border">
                                <h3 class="box-title" id="form-title">E-filed Applications -Refiling Report</h3><span style="float: right"><input type="text" class="form-control" ng-model="searchText" placeholder="Search"></span><span style="float: right"><button type="button" class="btn bg-purple btn-flat" ng-click="print_table()">Print</button></span><br />
                            </div>
                            <!-- <p class="text-red">{{loadingData ? "Please wait. Loading Data...":""}}</p> -->
                            <div id="printTable">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Ref ID</th>
                                            <th>SC D.No.</th>
                                            <th>CauseTitle</th>
                                            <th>Transaction ID</th>
                                            <th>Amount</th>
                                            <th>Date</th>
                                            <th>Source</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr ng-repeat="x in transactions_list | filter:searchText">
                                            <td>{{ $index+1 }}.</td>
                                            <td style="cursor:pointer;" ng-if="x.ack_id==0">-</td>
                                            <td style="cursor:pointer;" ng-click="get_ack_no(x.ack_id, x.ack_year, x.d_no)" data-toggle="modal" data-target="#diaryModal" ng-if="x.ack_id!=0">{{ x.ack_id }}/{{x.ack_year}}</td>

                                            <td style="cursor:pointer;" ng-if="x.d_no!=0 && x.app_flag=='Deficit_DN'">{{ x.d_no.substring(0, x.d_no.length-4) + "/" + x.d_no.substring(x.d_no.length-4) }}</td>
                                            <td style="cursor:pointer;" ng-click="get_docs($index)" data-toggle="modal" data-target="#docModal" ng-if="x.org_diary_no!=0 && x.app_flag!='Deficit_DN'">{{ x.org_diary_no.substring(0, x.org_diary_no.length-4) + "/" + x.org_diary_no.substring(x.org_diary_no.length-4) }}</td>
                                            <td style="cursor:pointer;" ng-if="(x.org_diary_no==0 && x.d_no==0) || (x.org_diary_no==0 && x.app_flag!='Deficit_DN')">-</td>

                                            <td style="cursor:pointer;" ng-if="x.app_flag=='Deficit_DN'">{{ x.pet_name }} vs {{ x.res_name }}</td>
                                            <td style="cursor:pointer;" ng-if="x.app_flag=='Deficit_DN'">{{ x.transaction_id }}</td>
                                            <td style="cursor:pointer;" ng-if="x.app_flag=='Deficit_DN'">{{ x.amount }}</td>
                                            <td style="cursor:pointer;" ng-if="x.app_flag=='Deficit_DN'">{{ x.transaction_datetime }}</td>
                                            <!--<td style="cursor:pointer;" ng-if="x.app_flag=='Deficit_DN'">{{ x.app_flag }}</td>-->

                                            <td style="cursor:pointer;" ng-if="x.app_flag!='Deficit_DN'" ng-click="get_docs(transactions_list.indexOf(x))" data-toggle="modal" data-target="#docModal" data-backdrop="static" data-keyboard="false">{{ x.pet_name }} vs {{ x.res_name }}</td>
                                            <td style="cursor:pointer;" ng-if="x.app_flag!='Deficit_DN'" ng-click="get_docs(transactions_list.indexOf(x))" data-toggle="modal" data-target="#docModal" data-backdrop="static" data-keyboard="false">{{ x.transaction_id }}</td>
                                            <td style="cursor:pointer;" ng-if="x.app_flag!='Deficit_DN'" ng-click="get_docs(transactions_list.indexOf(x))" data-toggle="modal" data-target="#docModal" data-backdrop="static" data-keyboard="false">{{ x.amount }}</td>
                                            <td style="cursor:pointer;" ng-if="x.app_flag!='Deficit_DN'" ng-click="get_docs(transactions_list.indexOf(x))" data-toggle="modal" data-target="#docModal" data-backdrop="static" data-keyboard="false">{{ x.transaction_datetime }}</td>
                                            <td style="cursor:pointer;" ng-click="get_docs(transactions_list.indexOf(x))" data-toggle="modal" data-target="#docModal" data-backdrop="static" data-keyboard="false">{{ x.app_flag }}</td>

                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- /.content -->
        </div>
        <!-- /.container -->

        <div class="modal fade" id="diaryModal" role="dialog">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header" style="position: relative;">
                        <h4>Ref ID {{ack}}/{{ack_year}}</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <div class="modal-body" style="padding-top: 10px !important;    border-top: 1px solid #ccc;">
                        <form>
                            <div class="form-group">
                                <label for="org_diary_no">Diary Number</label>
                                <input type="text" class="form-control" id="org_diary_no" placeholder="Diary No." ng-model="modal.org_diary_no">
                            </div>
                            <div class="form-group">
                                <label for="org_diary_no">Diary Year</label>
                                <select class="form-control" id="org_diary_yr" ng-model="modal.org_diary_yr">
                                    <option value="">Select Year</option>
                                    <?php
                                    for ($yr = date('Y'); $yr >= 1950; $yr--)
                                        echo "<option value=$yr>$yr</option>";
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <button ng-disabled="!modal.org_diary_yr || !modal.org_diary_no" type="button" class="btn btn-default" data-dismiss="modal" ng-click="update_org_diary()">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>



        <div class="modal fade" id="addDocUpdateModal" role="dialog">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header" style="position: relative;">
                        <h4>Update Document Number</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <div class="modal-body" style="padding-top: 10px !important;    border-top: 1px solid #ccc;">
                        <form>
                            <div class="form-group">
                                <label for="org_doc_no">Document Type</label>
                                <select class="form-control" id="doccode" ng-model="doc_modal.doccode" ng-options="docs.doccode as docs.docdesc for docs in docmaster | filter:{doccode1:0}">
                                    <option value="">Select Doc Type</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="org_doc_no">Sub-Document Type</label>
                                <select class="form-control" id="doccode1" ng-model="doc_modal.doccode1" ng-options="docs.doccode as docs.docdesc for docs in docmaster | filter:{doccode1:doc_modal.doccode}">
                                    <option value="">Select Doc Type</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="org_doc_no">Document Number</label>
                                <input type="text" class="form-control" id="org_doc_no" placeholder="Document No." ng-model="doc_modal.org_doc_no">
                            </div>
                            <div class="form-group">
                                <label for="org_doc_year">Document Year</label>
                                <select class="form-control" id="org_doc_year" ng-model="doc_modal.org_doc_year">
                                    <option value="">Select Year</option>
                                    <?php
                                    for ($yr = date('Y'); $yr >= 1950; $yr--)
                                        echo "<option value=$yr>$yr</option>";
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <button type="button" class="btn btn-default" data-dismiss="modal" ng-click="update_org_doc_no()">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <div class="modal fade" id="docModal" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header" style="position: relative;">

                        <h4 class="modal-title" id="modal_head" ng-if="postData.diary_no">Diary No: {{postData.diary_no}}/{{postData.diary_year}}</h4>
                        <h4 class="modal-title" id="modal_head" ng-if="postData.ack_id">Ref ID: {{postData.ack_id}}/{{postData.ack_year}}</h4>

                        <button type="button" id="Print" class="btn btn-primary pull-right" ng-click="print_doc()">Print</button>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body" style="padding-top: 10px !important;border-top: 1px solid #ccc;">
                        <!-- x.org_diary_no.substring(x.org_diary_no.length-4)']; -->
                        <div id="printThis" class="modal-body">
                            <div ng-if=" transactions_list[doc_index]['app_flag'] == 'Deficit'">
                                <p><b>Provisional ID No: </b> {{transactions_list[doc_index].ack_id + "/" + transactions_list[doc_index].ack_year }} </p></br>
                            </div>
                            <div ng-if="transactions_list[doc_index]['app_flag'] != 'Deficit'">
                                <!-- <p><b>Diary No: </b> {{transactions_list[doc_index].d_no.substring(0,transactions_list[doc_index].d_no.length-4)+"/"+ transactions_list[doc_index].d_no.substring(transactions_list[doc_index].d_no.length-4) }} </br>-->
                            </div>
                            <p><b>Case Type: </b>{{transactions_list[doc_index]['casename']}}</br>
                                <b>CauseTitle: </b>{{transactions_list[doc_index]['pet_name']}} vs {{transactions_list[doc_index]['res_name']}}</br>
                            </p>

                            <!-- + x.org_diary_no.substring(x.org_diary_no.length-4)']; -->

                            <div ng-if="transactions_list[doc_index]['app_flag'] == 'Deficit_DN' || transactions_list[doc_index]['app_flag'] == 'Deficit' ">

                                <!-- If block -->
                                <b>Matter filed by : </b>{{transactions_list[doc_index]['adv_name']}}</br>
                                <b>Mobile : </b>{{transactions_list[doc_index]['mobile']}}</br>
                                <b>Email Id : </b>{{transactions_list[doc_index]['email']}}</br>

                            </div>
                            <div ng-if="transactions_list[doc_index]['app_flag'] != 'Deficit_DN' &&  transactions_list[doc_index]['app_flag'] != 'Deficit'">
                                <b>Applied By: </b>{{doc_list[0]['name']}}</br>
                                <b>Email: </b>{{doc_list[0]['email']}}</br>
                                <b>Mobile: </b>{{doc_list[0]['mobile_no']}}</br></p>

                            </div>
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Document</th>
                                        <th>PDF <span class="glyphicon glyphicon-chevron-up"></span></th>
                                        <th>Pages</th>
                                        <th>Source</th>
                                        <th>Doc. No.</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- <p class="text-red">{{loadingData ? "Please wait. Loading Data...":""}}</p> -->
                                    <tr ng-repeat="x in doc_list">
                                        <td>{{ $index+1 }}.</td>
                                        <td style="cursor:pointer;" ng-if="!x.docdesc">{{ x.main_doc }} ( {{ x.sub_doc }} )</td>
                                        <td style="cursor:pointer;" ng-if="x.docdesc">{{ x.docdesc }} </td>
                                        <td style="cursor:pointer;"><a href="{{ x.pdf_file }}" target="_blank"> View </a></td>
                                        <td style="cursor:pointer;">{{ x.np }}</td>


                                        <td style="cursor:pointer;" ng-if="x.source_flag=='Additional_docs'" ng-click="setAddDocId(x.id)" data-toggle="modal" data-target="#addDocUpdateModal">{{ x.source_flag }}</td>
                                        <td style="cursor:pointer;" ng-if="x.source_flag!='Additional_docs' ">{{ x.source_flag }}</td>
                                        <td style="cursor:pointer;">{{ x.org_docnum}}/{{x.org_docyear}}</td>

                                    </tr>
                                </tbody>
                            </table>

                            <div ng-if="transactions_list[doc_index]['app_flag'] == 'Deficit_DN' || transactions_list[doc_index]['app_flag'] == 'Deficit' ">
                                <br>
                                <!-- If block -->
                                <p><b>Deficit Court Fees Paid: </b>Rs. {{transactions_list[doc_index]['amount']}}</p>
                            </div>

                            <div ng-if="transactions_list[doc_index]['app_flag'] != 'Deficit_DN' &&  transactions_list[doc_index]['app_flag'] != 'Deficit'">
                                <p><b>Printing Charges Paid: </b>Rs. {{transactions_list[doc_index]['amount']}}</p>
                                <input type="hidden" id="txn_date" value="{{transactions_list[doc_index]['transaction_datetime']}}">
                                <input type="hidden" id="txn_type" value="{{transactions_list[doc_index]['app_flag']}}">
                            </div>
                        </div>
                    </div>


                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                    </div>
                </div>

            </div>
        </div>
</section>

</div>


<script>
    $(function() {
        $(".pickDate").datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true
        });
    });
    $(document).on('show.bs.modal', '.modal', function() {
        var zIndex = 1040 + (10 * $('.modal:visible').length);
        $(this).css('z-index', zIndex);
        setTimeout(function() {
            $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
        }, 0);
    });
    //$('#docModal').modal({backdrop: 'static', keyboard: false});
    var app = angular.module('eFilingApp', []);
    var csrfName = 'CSRF_TOKEN';
    var csrfHash = $('[name="CSRF_TOKEN"]').val()
    app.controller('eFilingCtrl', function($scope, $http) {
        $scope.doc = {};
        $scope.postData = {};
        $scope.modal = {};
        $scope.doc_modal = {};
        $scope.doc_index = null;
        $scope.ack = 0;
        $scope.ack_year = 0;
        $scope.efile_diary = 0;
        $scope.addDocId = 0;
        $scope.docmaster = null;
        $scope.transactions_list = null;
        $scope.clearForm = function() {
            $scope.fields = {};
        }

        function isEmpty(str) {
            return (!str || 0 === str.length);
        }

        $scope.get_transaction_details = function() {
            //  alert("hello");

            $scope.loadingData = 1;
                if(isEmpty($scope.fields.from_date) ){
                    alert("Please Enter From Date");
                    document.getElementById('from_date').focus();
                    $scope.loadingData = 0;
                    return false;
                }
                if(isEmpty($scope.fields.to_date) ){
                    alert("Please Enter To Date");
                    document.getElementById('to_date').focus();
                    $scope.loadingData = 0;
                    return false;
                }
                if(isEmpty($scope.fields.status) ){
                    alert("Please Select Transaction Status");
                    document.getElementById('status').focus();
                    $scope.loadingData = 0;
                    return false;
                }
                if(isEmpty($scope.fields.app_type) ){
                    alert("Please Select Document Type");
                    document.getElementById('app_type').focus();
                    $scope.loadingData = 0;
                    return false;
                }

            if (!isEmpty($scope.fields.from_date) && !isEmpty($scope.fields.to_date) && !isEmpty($scope.fields.status) && !isEmpty($scope.fields.app_type)) {
                $http.get('<?= base_url(); ?>/Filing/Efiling/refiled_documents/<?php echo $_SESSION['login']['usercode'] ?>', {
                    params: $scope.fields,
                    // CSRF_TOKEN: csrfHash
                }).then(function successCallback(response) {
                    console.log(response.data);
                    $scope.loadingData = 0;
                    $scope.transactions_list = response.data['transactions'];
                    $scope.docmaster = response.data['docmaster'];
                }, function errorCallback(response) {
                    $scope.loadingData = 0;
                });
            } else
                alert("Please fill all details!");
                $scope.loadingData = 0;
        }

        $scope.get_ack_no = function(id, year, efile_diary_no) {
            $scope.ack = id;
            $scope.ack_year = year;
            $scope.efile_diary = efile_diary_no;
        }

        $scope.setAddDocId = function(id) {
            $scope.addDocId = id;
        }

        $scope.update_org_doc_no = function() {
            $scope.doc_modal.addDocId = $scope.addDocId;
            if (!isEmpty($scope.doc_modal.doccode) || !isEmpty($scope.doc_modal.doccode1) || !isEmpty($scope.doc_modal.org_doc_no) || !isEmpty($scope.doc_modal.org_doc_year)) {
                $http.post('<?= base_url(); ?>/Filing/Efiling/set_actual_document_no', {
                    data: $scope.doc_modal
                }).then(function successCallback(response) {
                    $scope.doc_modal = {};
                    $scope.addDocId = 0;
                }, function errorCallback(response) {});
            } else
                alert("Please fill all details!");
        }

        $scope.update_org_diary = function() {
            $scope.modal.ack = $scope.ack;
            $scope.modal.ack_year = $scope.ack_year;
            $scope.modal.efile_diary = $scope.efile_diary;
            if (!isEmpty($scope.modal.org_diary_no) || !isEmpty($scope.modal.org_diary_yr)) {
                alert('hfdhgkjdfhghdf');
                $http.get('<?= base_url(); ?>/Filing/Efiling/set_actual_diary_no', {
                    data: $scope.modal
                }).then(function successCallback(response) {
                    $scope.modal = {};
                    $scope.ack = 0;
                    $scope.ack_year = 0;
                    $scope.efile_diary = 0;
                }, function errorCallback(response) {});
            } else
                alert("Please fill all details!");
        }

        $scope.get_docs = function(index) {
            $scope.doc_list = {};
            $scope.postData = {};
            $scope.doc_index = index;
            var url = '';

            var csrfName = 'CSRF_TOKEN';
            var csrfHash = $('[name="CSRF_TOKEN"]').val();

            if ($scope.transactions_list[index]['app_flag'] == 'Add Doc' || $scope.transactions_list[index]['app_flag'] == 'Add Doc Sp' || $scope.transactions_list[index]['app_flag'] == 'Refiling' || $scope.transactions_list[index]['app_flag'] == 'ReFiling') {
                url = '<?= base_url(); ?>/Filing/Efiling/docs_from_sc_diary_no_for_refiled_documents';
                var diary = $scope.transactions_list[index]['org_diary_no'];
                $scope.postData.diary_no = diary.substring(0, diary.length - 4);
                $scope.postData.diary_year = diary.substring(diary.length - 4);
                $scope.postData.CSRF_TOKEN = csrfHash;
                $scope.postData.transaction_id = $scope.transactions_list[index]['transaction_id'];
            } else if ($scope.transactions_list[index]['app_flag'] == 'Filing') {
                url = '<?= base_url(); ?>/Filing/Efiling/check_documents';
                $scope.postData.ack_id = $scope.transactions_list[index]['ack_id'];
                $scope.postData.ack_year = $scope.transactions_list[index]['ack_year'];
                $scope.postData.transaction_id = $scope.transactions_list[index]['transaction_id'];
            } else {
                // For testing
                url = '<?= base_url(); ?>/Filing/Efiling/docs_from_sc_diary_no_for_refiled_documents';
                var diary = $scope.transactions_list[index]['org_diary_no'];
                $scope.postData.diary_no = diary.substring(0, diary.length - 4);
                $scope.postData.diary_year = diary.substring(diary.length - 4);
                $scope.postData.CSRF_TOKEN = csrfHash;
                $scope.postData.transaction_id = $scope.transactions_list[index]['transaction_id'];
            }

            $scope.loadingData = 1;
            $http.get(url, {
                    params: $scope.postData
                })
                .then(function successCallback(response) {
                    $scope.doc_list = response.data.result.old;
                    $scope.loadingData = 0;
                    //console.log(response.data.result);
                }, function errorCallback(response) {
                    $scope.loadingData = 0;
                });
        }

        function printElement(elem) {
            var mywindow = window.open();
            var title = $('#modal_head').val();
            mywindow.document.write('<html><body style="font-size: 14px;">');
            mywindow.document.write('<style> table {  border-collapse: collapse; font-size:14px;} table, td, th {border: 1px solid black;} a {text-decoration: none;}</style>');
            mywindow.document.write('<h2 style="text-align: center; font-size: 120%;">Supreme Court of India</br><u>E-Filing Report</u></h2>');
            mywindow.document.write('<div style="float: right">Generated Date: <?= date("d-m-Y") ?></div>');
            var txn_date = $('#txn_date').val();
            var d = new Date(txn_date);
            // var txn_date = ((d.getDate()<10)?'0'+d.getDate():d.getDate())+'-'+((d.getMonth()<10)?'0'+d.getMonth():d.getMonth())+'-'+d.getFullYear();
            var txn_date = ((d.getDate() < 10) ? '0' + d.getDate() : d.getDate()) + '-' + ((d.getMonth() + 1 < 10) ? '0' + (d.getMonth() + 1) : (d.getMonth() + 1)) + '-' + d.getFullYear();
            var txn_type = $('#txn_type').val();
            mywindow.document.write('<br/><div style="float: right">' + txn_type + ' Date: ' + txn_date + ' </div>');
            if (elem == "printThis")
                mywindow.document.write('<div style="margin: 0 auto; width: 100px;font-size: 110%;font-weight: bold;"><u>' + $('#modal_head').text() + '</u></div>');
            else
                mywindow.document.write('<br><br>');
            mywindow.document.write(document.getElementById(elem).innerHTML);
            if (elem == "printThis") {
                //if(txn_type=='Filing'){
                mywindow.document.write('</br></br><p style="text-align: right;"><u>Dealing Official, E-Filing</u></p>');
                mywindow.document.write('</br></br><p style="text-align: right;"><u>Branch Officer (CC)</u></p>');
                //}
                mywindow.document.write('</br></br><p style="text-align: left;"><u>Branch Officer (Sec-  )</u></p>');
            }
            mywindow.document.write('</body></html>');
            mywindow.document.close(); // necessary for IE >= 10
            mywindow.focus(); // necessary for IE >= 10*/

            mywindow.print();
            mywindow.close();

            return true;
        }

        $scope.print_doc = function() {
            printElement("printThis");
        }

        $scope.print_table = function() {
            printElement("printTable");
        }

    });
</script>