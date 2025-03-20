<?php

/**
 * Created by PhpStorm.
 * User: Mohit Jain
 * Date: 31/8/17
 * Time: 12:29 PM
 */
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">

<head>
    <style>
        .red {
            color: red;
            cursor: pointer;
        }

        .green {
            color: green;
            cursor: pointer;
        }

        .pointer {
            cursor: pointer;
        }

        table,
        tr,
        td {
            cursor: pointer;
        }

        .table-overflow-auto {
            overflow: auto;
            -webkit-overflow-scrolling: touch;
        }

        .container {
            width: 100% !important;
        }

        /*start loader*/
        #cover-spin {
            position: fixed;
            width: 100%;
            left: 0;
            right: 0;
            top: 0;
            bottom: 0;
            background-color: rgba(255, 255, 255, 0.7);
            z-index: 9999;
            display: block;
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

        #cover-spin::after {
            content: '';
            display: block;
            position: absolute;
            left: 48%;
            top: 40%;
            width: 40px;
            height: 40px;
            border-style: solid;
            border-color: #e53935;
            border-top-color: transparent;
            border-width: 4px;
            border-radius: 50%;
            -webkit-animation: spin .8s linear infinite;
            animation: spin .8s linear infinite;
        }
        .err_msg_class {
            font-size: 11px;
        }
        /*end loader*/
    </style>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Efiling Transactions - Search by Ref. ID</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="<?= base_url() ?>/assets/css/bootstrap.min.css">
    <!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">-->
    <link rel="stylesheet" href="<?= base_url() ?>/assets/css/AdminLTE.min.css" media="all">
    <link rel="stylesheet" href="<?= base_url() ?>/assets/css/skins/_all-skins.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>/assets/plugins/datepicker/datepicker3.css">
    <link rel="stylesheet" href="<?= base_url() ?>/assets/jsAlert/dist/sweetalert.css">
    <script src="<?= base_url() ?>/assets/jsAlert/dist/sweetalert.min.js"></script>
</head>

<body class="hold-transition skin-blue layout-top-nav">
    <div class="wrapper" ng-app="eFilingApp" ng-controller="eFilingCtrl" data-ng-init="clearForm()">
        <!-- Full Width Column -->
        <div class="content-wrapper">
            <div class="container">
                <!-- Main content -->
                <section class="content">
                    <div ng-show="noDataFound==1" class="alert alert-warning text-center">No Data Found between selected Dates</div>
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <?php
                            //echo "the session vaisble is". $_SESSION['dcmis_user_idd'];

                            ?>
                            <h3 class="box-title">DAK/MISCELLENOUS DOCUMENTS REPORT ( INCLUDING E-FILED MATTERS ) </h3>
                        </div>

                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-sm-2">
                                    <label for="causelistDate">From Date:</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control pickDate" id="from_date" ng-model="fields.from_date" placeholder="dd-mm-yyyy" autocomplete="off" required>
                                    </div>
                                    <span class="from_date_err text-danger err_msg_class"></span>
                                </div>
                                <div class="form-group col-sm-2">
                                    <label for="causelistDate">To Date:</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control pickDate" id="to_date" ng-model="fields.to_date" placeholder="dd-mm-yyyy" autocomplete="off" required>
                                    </div>
                                    <span class="to_date_err text-danger err_msg_class"></span>
                                </div>


                                <div class="form-group col-sm-2">
                                    <label>&nbsp;</label>
                                    <?php echo csrf_field(); ?>
                                    <button type="button" id="btn-shift-assign" class="btn btn-block bg-olive btn-flat pull-right" ng-click="get_transaction_details()" ng-submit="callLoader()"><i class="fa fa-save"></i> Search </button>
                                    </button>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div ng-show="loader==1" id="cover-spin" align=center font-size="15px"></div>
                    <div class="box box-success" ng-if="transactions_list">
                        <div class="box-header with-border">
                            <h3 class="box-title" id="form-title">E-filed Applications - Loose Document Report</h3><span style="float: right"><input type="text" class="form-control" ng-model="searchText" placeholder="Search"></span><span style="float: right"><button type="button" class="btn bg-purple btn-flat" ng-click="print_table()">Print</button></span><br />
                        </div>
                        <p class="text-red">{{loadingData ? "Please wait. Loading Data...":""}}</p>
                        <div id="printTable" class="table-overflow-auto">
                            <table class="table table-striped table-hover display example " id="example">
                                <thead>

                                    <tr>
                                        <th>Sr.No.</th>
                                        <th>Diary Number</th>
                                        <th>Cause Title</th>
                                        <th>Section</th>
                                        <th>Dealing Assistant</th>
                                        <th>Document Number</th>
                                        <th>Description</th>
                                        <th>Filed By</th>
                                        <th>Filed On</th>
                                        <th>DAK DA</th>
                                        <th>Next Listing Date</th>
                                        <th>Pdf File link </th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <tr ng-repeat="result in transactions_list | filter:searchText">
                                        <td>{{ $index+1}}</td>
                                        <td><a>{{result.diary_no }} <br> {{ result.reg_no_display }}</a></td>
                                        <td>{{ result.causetitle }}</td>
                                        <td>{{ result.da_section }}</td>
                                        <td>{{ result.da_name }}</td>
                                        <td>{{ result.document }} <span ng-if="result.is_efiled=='Y'" class="red">{{ "e-Filed" }}</span><br /><b style="color:blue;"><a target="_blank" href="<?php echo E_FILING_URL ?>/efiling_search/DefaultController/?efiling_number={{result.efiling_no}}">{{result.efiling_no}}</a></b></td>
                                        <td>{{ result.docdesc}}</td>
                                        <td>{{ result.filedby }}</td>
                                        <td>{{ result.ent_dt}}</td>
                                        <td>{{result.dak_name }}( {{result.dak_empid }}) </td>
                                        <td><span ng-if="result.next_date!=null && result.next_date !='0000-00-00' && result.diff >0 && result.diff<=7"
                                                class="red"> {{result.next_date}}</span>


                                            <span ng-if="result.next_date!=null && result.next_date!='0000-00-00' && result.diff >7"
                                                class="green"> {{result.next_date}}</span>
                                        </td>


                                        <td><span ng-if="result.pdf_name!=null"><a ng-href="{{result.pdf_name}}" target="_blank"> {{ "View" }}</a></span></td>


                                    </tr>


                                </tbody>
                            </table>
                        </div>
                    </div>

                </section>
                <!-- /.content -->
                <!-- Image loader -->
                <!-- <div id='loader' class="whole-page-overlay" id="whole_page_loader">
                    <img class="center-loader"  style="height:100px;" src="<?php echo base_url('cgwbspin.gif'); ?>"/>
                </div> -->
                <div id='loader' style='display: none;'>
                    <img src='../../..//assets/load.gif' width='32px' height='32px'>
                </div>
                <!-- Image loader -->
            </div>

            <!-- /.container -->




            <!-- to be deleted -->
        </div>
        <!-- ./wrapper -->
    </div>
    <!-- SlimScroll -->
    <script src="<?= base_url() ?>/assets/plugins/jQuery/jquery-2.2.3.min.js"></script>
    <script src="<?= base_url() ?>/assets/plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <script src="<?= base_url() ?>/assets/js/bootstrap.min.js"></script>
    <script src="<?= base_url() ?>/assets/plugins/fastclick/fastclick.js"></script>
    <script src="<?= base_url() ?>/assets/js/app.min.js"></script>
    <script src="<?= base_url() ?>/assets/js/angular.min.js"></script>
    <script src="<?= base_url() ?>/assets/plugins/datepicker/bootstrap-datepicker.js"></script>
    <script type="text/javascript">
    var CSRF_TOKEN = 'CSRF_TOKEN';
    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
    
    function updateCSRFToken() {
        
        // console.log($('[name="CSRF_TOKEN"]').val());

        // Show the loader before the request
        // $('#whole_page_loader').show();

        // Make the AJAX request to get the CSRF token
        $.getJSON("<?php echo base_url('Csrftoken'); ?>")
        .done(function(result) {
            // Update the CSRF token value in the form
            $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
            CSRF_TOKEN_VALUE = result.CSRF_TOKEN_VALUE;
        })
        .fail(function() {
            // Optionally, handle the error here
            // alert('Failed to update CSRF token.');
        })
        .always(function() {
            // Hide the loader after the request completes
            // $('#whole_page_loader').hide();
        });
    }
    </script>
    <script>
        $(document).on('change', '#from_date', function(){ 
            validationFromDate();
        });
        $(document).on('change', '#to_date', function(){ 
            validationToDate();
        });
        $(function() 
        {
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
        app.controller('eFilingCtrl', function($scope, $http) {
            $scope.loader = 0;
            $scope.noDataFound = 0;
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

            $scope.clearForm = function() {
                $scope.fields = {};
            }
            $scope.callLoader = function() {
                $scope.loader = 1;
            }

            function isEmpty(str) {
                return (!str || 0 === str.length);
            }


            $scope.get_document_details = function() {
                $scope.doc_list = null;
                if (!isEmpty($scope.fields.ack_id)) {
                    $http.post('<?= base_url(); ?>index.php/Efiling/check_documents', {
                        data: $scope.fields,
                        CSRF_TOKEN:CSRF_TOKEN_VALUE
                    }).then(function successCallback(response) {
                        console.log(response.data);
                        $scope.doc_list = response.data;
                        alert($scope.doc_list);
                    }, function errorCallback(response) {});
                }
            }


            function validationFromDate(){
                var from_date = $('#from_date').val();        
                if(from_date == ''){       
                    //alert("req");                     
                    $('.from_date_err').text('Date is Required');
                    return false;
                }else{                              
                    $('.from_date_err').text('');
                    return true;
                }
            }

            function validationToDate(){
                var to_date = $('#to_date').val();        
                if(to_date == ''){                            
                    //alert("req");
                    $('.to_date_err').text('Date is Required');
                    return false;
                }else{          
                    //alert("req");   
                    $('.to_date_err').text('');
                    return true;
                }
            }

            $scope.get_transaction_details = function() 
            {
                solve1 =true;solve2 = true;
                solve1 = validationFromDate();
                solve2 = validationToDate();
                if(solve1 == true && solve2 ==true)
                {
                    var app = angular.module('tutorialWebApp', ['ngRoute']);
                    app.directive('loading', function() {
                        return {
                            restrict: 'E',
                            replace: true,
                            template: '<p><img src="images/load.gif"/></p>', // Define a template where the image will be initially loaded while waiting for the ajax request to complete
                            link: function(scope, element, attr) {
                                scope.$watch('loading', function(val) {
                                    val = val ? $(element).show() : $(element).hide(); // Show or Hide the loading image  
                                });
                            }
                        }
                    });
                    $scope.transactions_list = null;
                    $scope.loader = 1;
                    $scope.loadingData = 0;

                
                    
                    if (!isEmpty($scope.fields.from_date) || !isEmpty($scope.fields.to_date)) {
                        $http.get('<?= base_url(); ?>/Judicial/Report/loosedoc_getscope', {
                            params: { 
                                data: $scope.fields,
                                CSRF_TOKEN:CSRF_TOKEN_VALUE
                            }                        
                        }).then(function successCallback(response) {
                            $("#loader").hide();
                            $scope.loader = 0;
                            $scope.noDataFound = 0;
                            //  alert(response.data.transactions);
                            if (response.data.transactions == undefined) {
                                $scope.noDataFound = 1;
                                // alert("No records found !! ");
                            }
                            //console.log(response.data.transactions);
                            $scope.transactions_list = response.data.transactions;
                            //alert($scope.transactions_list);
                            // alert(response.data['transactions_list']);
                            // $scope.docmaster = response.data['docmaster'];
                        }, function errorCallback(response) {
                            // $scope.loadingData = false;
                        });
                    } else {
                        $scope.loader = 0;
                        $scope.loadingData = 0;
                        alert("Please fill all details!");
                        return false;
                    }

                }


                
            }

            $scope.get_ack_no = function(id, year, efile_diary_no) {
                $scope.ack = id;
                $scope.ack_year = year;
                $scope.efile_diary = efile_diary_no;
            }

            $scope.setAddDocId = function(id) {
                $scope.addDocId = id;
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
</body>

</html>