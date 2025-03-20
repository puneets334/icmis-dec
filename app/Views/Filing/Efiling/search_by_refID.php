<?=view('header'); ?>
 
    <style>
        .custom-radio{float: left; display: inline-block; margin-left: 10px; }
        .custom_action_menu{float: left; display: inline-block; margin-left: 10px; }
        .basic_heading{text-align: center;color: #31B0D5}
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
        

    </style>
    <div ng-app="eFilingApp" ng-controller="eFilingCtrl" data-ng-init="clearForm()">
        <div ng-show="loader==1" id="cover-spin"></div>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
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
                            <?=view('Filing/Efiling/Efiling_breadcrumb');?>
                             
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-body">
                                        <h5 class="box-title  pl-4">Check Docs</h5>
                                      
                                            <form action="#" class="form-horizontal ng-pristine ng-invalid ng-invalid-required" name="myForm" id="myForm" autocomplete="off" method="post" accept-charset="utf-8" novalidate ng-submit="callLoader()">
                                                <?= csrf_field('csrf_field') ?>
                                                <div class="row">
                                                    <div class="col-md-2"></div>
                                                    <div class="col-md-4 diary_section">
                                                        <div class="form-group row">
                                                            <label for="ack_id" class="col-sm-5 col-form-label">Ref. No. </label>
                                                            <div class="col-sm-7">
                                                                <input type="number" class="form-control" id="ack_id" name="ack_id" ng-model="fields.ack_id" required="true" placeholder="Enter ACK. No." >
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 diary_section">
                                                        <div class="form-group row">
                                                            <label for="ack_year" class="col-sm-5 col-form-label">Select Year</label>
                                                            <div class="col-sm-5">
                                                                <?php $year = 1950;
                                                                $current_year = date('Y');
                                                                ?>
                                                                <select id="ack_year" name="ack_year" ng-model="fields.ack_year" required="true" class="custom-select rounded-0">
                                                                    <option value="">Select Year</option>
                                                                    <?php for ($x = $current_year; $x >= $year; $x--) { ?>
                                                                        <option <?php echo ($x == $current_year) ? 'selected' : ''; ?> value="<?php echo $x; ?>"><?php echo $x; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <center>
                                                    <button type="button" ng-disabled="myForm.ack_id.$dirty && myForm.ack_id.$invalid || myForm.ack_year.$dirty && myForm.ack_year.$invalid" id="btn-shift-assign" class="btn btn-primary btn-flat pull-right" ng-click="get_document_details()" ><i class="fa fa-save"></i> Search </button>

                                                </center>
                                                </div>


                                               


                                            <div class="box box-success" ng-if="doc_list">
                                                <div class="box-header with-border">
                                                    <h3 class="box-title" id="form-title">E-filed Applications - Complete</h3><span style="float: right"><button type="button" class="btn bg-purple btn-flat" ng-click="doc_for_refiling()">Submit</button></span>
                                                </div>
                                                <div id="printTable">
                                                    <table class="table table-striped table-hover">
                                                        <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Document</th>
                                                            <th>PDF</th>
                                                            <th>From Page</th>
                                                            <th>To Page</th>
                                                            <th>Pages</th>
                                                            <th>Source</th>
                                                            <th>Mark for Refiling</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr ng-repeat="x in doc_list">
                                                            <td>{{ $index+1 }}.</td>
                                                            <td style="cursor:pointer;" >{{ x.docdesc }}</td>
                                                            <td style="cursor:pointer;" ><a href="{{ x.pdf_file }}" target="_blank"> View </a></td>
                                                            <td style="cursor:pointer;" >{{ x.fp }}</td>
                                                            <td style="cursor:pointer;" >{{ x.tp }}</td>
                                                            <td style="cursor:pointer;" >{{ x.np }}</td>
                                                            <td style="cursor:pointer;" >{{ x.source_flag }}</td>
                                                            <td style="cursor:pointer;" ><input type="checkbox" id="mark" name="mark" ng-model="doc.mark[x.ind_id]" ng-value="{{x.ind_id}}" ></td><!--ng-checked="{{x.file_status!=1}}"-->
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <!-- {{doc.mark}} -->
                                        </div>
                                            </form>
                                            <center><span id="ajax_response"></span></center>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>


    <script>
        var app = angular.module('eFilingApp', []);
        app.controller('eFilingCtrl', function($scope, $http, $httpParamSerializer) {
            $http.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';
            $http.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
            $scope.loader = 0;
            // $scope.doc={};
            $scope.doc = {
                mark: {} // Initialize mark as an empty object
            };
            $scope.clearForm = function(){
                $scope.fields ={};
            }
            function isEmpty(str) {
                return (!str || 0 === str.length);
            }
            $scope.get_document_details = function() {
                var csrf = angular.element(document.querySelector('#csrf_field'));
                var ajax_response = angular.element(document.querySelector('#ajax_response'));
                var form_error = angular.element(document.querySelector('.form-response'));

                ajax_response.html('');
                form_error.html('');
                $scope.doc_list = null;

                if (isEmpty($scope.fields.ack_id)) {
                    alert("Please Enter Ack No.");
                    return false;
                }

                if (isEmpty($scope.fields.ack_year)) {
                    alert("Please Select Ack Year");
                    return false;
                }

                var csrfName = 'CSRF_TOKEN';
                var csrfHash = $('#csrf_field').val();
                var data = {
                    CSRF_TOKEN: csrfHash,
                    ack_id: $scope.fields.ack_id,
                    ack_year: $scope.fields.ack_year
                };

                $scope.loader = 1; // Show loading indicator

                $http.post("<?=base_url('Filing/Efiling/check_documents')?>", $httpParamSerializer(data), {
                    xsrfHeaderName: 'X-CSRF-TOKEN',
                    xsrfCookieName: 'csrf_cookie_name'
                })
                    .then(function successCallback(response) {
                        $scope.loader = 0; // Hide loading indicator
                        updateCSRFToken();
                        response = response.data;
                        csrf.val(response.token);
                        
                        if (response.response_code === 200) {
                            $scope.doc_list = response.data;
                        } else if (response.response_code === 404) {
                            ajax_response.html("<span class='text-danger'>" + response.message + "</span>");
                        } else if (response.response_code === 403) {
                            form_error.html(response.data);
                        } else {
                            ajax_response.html("<span class='text-danger'>An error occurred. Please try again later.</span>");
                        }
                    })
                    .catch(function errorCallback(response) {
                        updateCSRFToken();
                        $scope.loader = 0; // Hide loading indicator
                        ajax_response.html("<span class='text-danger'>An error occurred. Please try again later.</span>");
                    });
            };
            $scope.doc_for_refiling=function () {
                var csrf = angular.element( document.querySelector( '#csrf_field' ) );
                var ajax_response = angular.element( document.querySelector( '#ajax_response' ) );
                var form_error = angular.element( document.querySelector( '.form-response' ) );
                ajax_response.html('');
                form_error.html('');
                if(isEmpty($scope.fields.ack_id) ){
                    alert("Please Enter Ack No.");
                    return false;
                }
                if(isEmpty($scope.fields.ack_year) ){
                    alert("Please Select Ack Year");
                    return false;
                }
                var csrfName = 'CSRF_TOKEN';
                var csrfHash = $('[name="CSRF_TOKEN"]').val();
                var data = {
                    CSRF_TOKEN: csrfHash,
                    ack_id: $scope.fields.ack_id,
                    ack_year: $scope.fields.ack_year,
                    doc: $scope.doc.mark
                };
                if(!isEmpty($scope.fields.ack_id) ){
                    $scope.loader = 1;
                    $http.post("<?=base_url('Filing/Efiling/doc_for_refiling')?>", $httpParamSerializer(data), {
                        xsrfHeaderName:'X-CSRF-TOKEN',
                        xsrfCookieName:'csrf_cookie_name'
                    })
                        .then(function successCallback(response) {
                            updateCSRFToken();
                            response = response.data;
                            csrf.val(response.token);
                            $scope.loader = 0;
                            is_response_code=response.response_code;
                            if (is_response_code==200){
                                ajax_response.html("<span class='text-success'>"+response.message+"</span>");
                            }else if (is_response_code==404){
                                ajax_response.html("<span class='text-danger'>"+response.message+"</span>");
                            }else if (is_response_code==403){
                                form_error.html(response.data);
                            }

                        }, function errorCallback(response) {
                            updateCSRFToken();
                        });
                }
            }
            $scope.callLoader = function () {
                $scope.loader = 1;
            }
        });
    </script>
