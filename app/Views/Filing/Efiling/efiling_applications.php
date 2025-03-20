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
<div ng-show="loader === 1" id="cover-spin"></div>
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
                        <?= view('Filing/Efiling/Efiling_breadcrumb'); ?>


                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body_">

                                        <!-- Main content -->
                                        <section class="row">
                                            <div class="col-md-12">
                                                 

                                                <div class="card-body">
                                                    <h5 class="box-title ">E-filed Applications</h5>
                                                    <?= csrf_field('csrf_field') ?>
                                                    <div class="form-row align-items-center">
                                                        <div class="col-md-1">
                                                            <label for="from_date">From date:</label>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <input type="text" class="form-control pickDate" autocomplete="off" id="from_date" ng-model="fields.from_date" placeholder="From Date" required>
                                                        </div>

                                                        <div class="col-md-1">
                                                            <label for="to_date">To date:</label>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <input type="text" class="form-control pickDate" autocomplete="off" id="to_date" ng-model="fields.to_date" placeholder="To Date" required>
                                                        </div>

                                                        <div class="col-md-1">
                                                            <label for="status">Case Status:</label>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <select class="form-control" id="status" ng-model="fields.status" required>
                                                                <option value="">Select</option>
                                                                <option value="1">Complete</option>
                                                                <option value="2">Failed Transactions</option>
                                                                <option value="0">Pending</option>
                                                            </select>
                                                        </div>

                                                        <div class="col-md-3">
                                                            <button type="button" class="btn btn-block btn-success" ng-click="get_application_list()">
                                                                <i class="fa fa-search"></i> Search
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="box box-success" ng-if="fields.status != 0 && app_list">
                                                <div class="box-header with-border">
                                                    <h3 class="box-title" id="form-title">E-filed Applications - Complete</h3><span style="float: right"><button type="button" class="btn bg-purple btn-flat" ng-click="print_table()">Print</button></span>
                                                </div>
                                                <div id="printTable">
                                                    <table class="table table-striped table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th style="width: 2%">#</th>
                                                                <th style="width: 8%">Ref. No</th>
                                                                <th style="width: 5%">Reciept. Date</th>
                                                                <th style="width: 10%">Txn. ID</th>
                                                                <th style="width: 5%">Case Type</th>
                                                                <th style="width: 30%">Cause Title</th>
                                                                <th style="width: 5%">App. Status</th>
                                                                <th style="width: 5%">Paid Amt.</th>
                                                                <th style="width: 5%">Source</th>
                                                                <th style="width: 15%">Reason</th>
                                                                <th style="width: 10%">Diary No <span class="glyphicon glyphicon-chevron-up"></span></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr ng-repeat="x in app_list track by $index">
                                                                <td>{{ $index+1 }}.</td>
                                                                <td style="cursor:pointer;" ng-click="get_document_list(x.ack_id, x.ack_year, $index)" data-toggle="modal" data-target="#docModal">{{ x.ack_id }}</td>
                                                                <td style="cursor:pointer;" ng-click="get_document_list(x.ack_id, x.ack_year, $index)" data-toggle="modal" data-target="#docModal">{{ x.ack_rec_dt }}</td>
                                                                <td style="cursor:pointer;" ng-click="get_document_list(x.ack_id, x.ack_year, $index)" data-toggle="modal" data-target="#docModal">{{ x.transaction_id }}</td>
                                                                <td style="cursor:pointer;" ng-click="get_document_list(x.ack_id, x.ack_year, $index)" data-toggle="modal" data-target="#docModal">{{ x.case_grp }}</td>
                                                                <td style="cursor:pointer;" ng-click="get_document_list(x.ack_id, x.ack_year, $index)" data-toggle="modal" data-target="#docModal">{{ x.pet_name }} vs {{ x.res_name }}</td>
                                                                <td style="cursor:pointer;" ng-click="get_document_list(x.ack_id, x.ack_year, $index)" data-toggle="modal" data-target="#docModal">{{ x.cnt_status }}</td>
                                                                <td style="cursor:pointer;" ng-click="get_document_list(x.ack_id, x.ack_year, $index)" data-toggle="modal" data-target="#docModal">{{ x.amount }}</td>
                                                                <td style="cursor:pointer;" ng-click="get_document_list(x.ack_id, x.ack_year, $index)" data-toggle="modal" data-target="#docModal">{{ x.app_source }}</td>
                                                                <td style="cursor:pointer;" ng-click="get_document_list(x.ack_id, x.ack_year, $index)" data-toggle="modal" data-target="#docModal">{{ x.reason }}</td>
                                                                <td style="cursor:pointer;" ng-if=" x.status_id==0">-</td>
                                                                <td style="cursor:pointer;" ng-if="!x.status_id">-</td> <!--ng-click="import_data(x.ack_id)"-->
                                                                <td style="cursor:pointer;" ng-if="x.org_diary_no==0 && x.status_id==1">Generate</td> <!--ng-click="import_data(x.ack_id)"-->
                                                                <td style="cursor:pointer;" ng-if="x.org_diary_no!=0 && x.status_id==1">{{ x.org_diary_no.substring(0, x.org_diary_no.length-4)+'/'+x.org_diary_no.substr(x.org_diary_no.length - 4) }} ( {{x.diary_no_rec_date}} )</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>


                                            <div class="box box-success" ng-if="fields.status == 0 && app_list">
                                                <div class="box-header with-border">
                                                    <h3 class="box-title" id="form-title">E-filed Applications - Pending</h3>
                                                </div>
                                                <table class="table table-striped table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Reciept. Date</th>
                                                            <th>Case Type</th>
                                                            <th>Cause Title</th>
                                                            <th>App. Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr ng-repeat="x in app_list">
                                                            <td>{{ $index+1 }}.</td>
                                                            <td>{{ x.diary_no_rec_date }}</td>
                                                            <td>{{ x.case_grp }}</td>
                                                            <td>{{ x.pet_name }} vs {{ x.res_name }}</td>
                                                            <td ng-if="x.cnt_status == 0">Filing</td>
                                                            <td ng-if="x.cnt_status == 1">Indexing</td>
                                                            <td ng-if="x.cnt_status == 2">Lower Court Details</td>
                                                            <td ng-if="x.cnt_status == 3">Additional Party</td>
                                                            <td ng-if="x.cnt_status == 4">Additional Advocate</td>
                                                            <td ng-if="x.cnt_status == 5">Categorization</td>
                                                            <td ng-if="x.cnt_status == 6">Limitation</td>
                                                            <td ng-if="x.cnt_status == 7">Court Fees</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </section>
                                        <!-- /.container -->

                                        <!-- Modal -->
                                        <div class="modal fade" id="docModal" role="dialog" ng-if="fields.status == 1">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header" style="position: relative;">
                                                    <h4 class="modal-title" id="modal_head">Ref. Id. {{app_list[doc_index]['ack_id']}}/{{app_list[doc_index]['ack_year']}}</h4>    
                                                        
                                                        <button type="button" id="calc" class="btn btn-primary pull-right" data-toggle="modal" data-target="#calcModal">Calculate Fee</button>
                                                        <button type="button" id="Print" class="btn btn-primary pull-right" ng-click="print_doc()">Print</button>
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    </div>

                                                    <div id="printThis" class="modal-body" style="padding-top: 10px !important;    border-top: 1px solid #ccc;">
                                                        <p><b>Case Type: </b>{{app_list[doc_index]['casename']}}</br>
                                                            <b>CauseTitle: </b>{{app_list[doc_index]['pet_name']}} vs {{app_list[doc_index]['res_name']}}</br>
                                                            <b>E-Filing Date: </b>{{app_list[doc_index]['ack_rec_dt']}}</br>
                                                            <b>Category: </b><span ng-repeat="y in category_list">{{y.sub_name1}}</span></br>
                                                        </p>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <p> <b><u><span ng-if="party_list[0]['ind_dep']=='I'">Petitioner-In-Person</span><span ng-if="party_list[0]['ind_dep']!='I'">Petitioner</span></u></b></br>
                                                                    <b>Name : </b>{{party_list[0]['name']}}</br>
                                                                    <b>Email : </b>{{party_list[0]['email']}}</br>
                                                                    <b>Mobile : </b>{{party_list[0]['mobile_no']}}
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <p> <b><u>Portal</u></b></br>
                                                                    <b>Court Fee : </b>{{app_list[doc_index]['court_fee']}}</br>
                                                                    <b>Printing Charges : </b>{{app_list[doc_index]['pages']*page_print_cost*4}}</br>
                                                                    <b>Amount Paid : </b>Rs. {{app_list[doc_index]['amount']}}
                                                                </p>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <p> <b><u>After Scrutiny</u></b></br>
                                                                    <b>Printing Charges : </b>{{pages}}1 * page_print_cost * 4 = {{printing_charges}}</br>
                                                                    <b>Court Fee Amount : </b>{{app_list[doc_index]['amount']}} - {{printing_charges}} = {{court_fee_paid}}</br>
                                                                    <b>Court Fee Deficit : </b> Rs. {{court_fee_deficit}}
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <table class="table table-striped table-hover">
                                                            <thead>
                                                                <tr>
                                                                    <th>#</th>
                                                                    <th>Doc</th>
                                                                    <th>Pages</th>
                                                                    <th>File <span class="glyphicon glyphicon-chevron-up"></span></th>
                                                                    <!--<th>Actual Pages</th>-->
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr ng-repeat="x in doc_list">
                                                                    <td>{{ $index+1 }}.</td>
                                                                    <td style="cursor:pointer;">{{ x.docdesc }}</td>
                                                                    <td style="cursor:pointer;">{{ x.np }}</td>
                                                                    <td style="cursor:pointer;"><a target="_blank" href="{{ x.pdf_file }}"> {{ x.pdf_name }} </a></td>
                                                                    <!--<td><?php
                                                                            /*                                    $file = "{{ x.pdf_file }}";
                                    $pdftext = file_get_contents(urlencode($file));
                                    //var_dump($file);
                                    echo $file;
                                    */ ?></td>-->
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="2"><b>Total Pages</b></td>
                                                                    <td colspan="2"><b>{{app_list[doc_index]['pages']}}</b></td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>


                                        <div class="modal fade" id="calcModal" role="dialog">
                                            <div class="modal-dialog modal-sm">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    </div>

                                                    <div class="modal-body">
                                                        <form class="form-inline">
                                                            <input type="text" class="form-control" id="pages" placeholder="Pages" ng-model="pages">
                                                            <button type="button" class="btn btn-primary" data-dismiss="modal" ng-click="calc_fee()">Submit</button>
                                                        </form>
                                                    </div>
                                                </div>
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
</div>
 
<script>
    $(function() {
        $(".pickDate").datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true
        });
    });

    var app = angular.module('eFilingApp', []);
    var csrfName = 'CSRF_TOKEN';
    var csrfHash = $('#csrf_field').val();
    app.controller('eFilingCtrl', function($scope, $http) {
        $scope.page_print_cost = 0.75;
        $scope.clearForm = function() {
            $scope.fields = {};
        }

        function isEmpty(str) {
            return (!str || 0 === str.length);
        }

        $scope.get_application_list = function() {
            $scope.app_list = null;
            $scope.loader = 1;
            console.log("Loader at start:", $scope.loader); // Debugging
            if (isEmpty($scope.fields.from_date))
                {
                   alert('Please add From Date');
                   document.getElementById('from_date').focus();
                   $scope.loader = 0;
                   return false; 
                }

                if (isEmpty($scope.fields.to_date))
                {
                   alert('Please add To Date');
                   document.getElementById('to_date').focus();
                   $scope.loader = 0;
                   return false; 
                }

                if (isEmpty($scope.fields.status))
                {
                   alert('Please select Status');
                   document.getElementById('status').focus();
                   $scope.loader = 0;
                   return false; 
                }


            //if (!isEmpty($scope.fields.from_date) && !isEmpty($scope.fields.to_date) && !isEmpty($scope.fields.status)) {
                $http.get('<?= base_url(); ?>/Filing/Efiling/efiling_applications', { params: $scope.fields }
                // , {
                //     data: $scope.fields,
                //     CSRF_TOKEN: csrfHash
                // }
            ).then(function successCallback(response) {
                    //console.log(response.data);
                    $scope.app_list = response.data;
                   $scope.loader = 0;
                  console.log("Loader at end:", $scope.loader); // Debugging
                }, function errorCallback(response) {
                    console.log("Loader at end:", $scope.loader); // Debugging
                });
            //}
        }

        $scope.get_document_list = function(id, year, index) {
            $scope.doc_index = index;
            $scope.page_print_cost = $scope.page_print_cost_method($scope.app_list[$scope.doc_index]['ack_rec_dt']);
            $scope.pages = $scope.app_list[$scope.doc_index]['pages'];
            $scope.printing_charges = $scope.pages * $scope.page_print_cost * 4;
            $scope.court_fee_paid = $scope.app_list[$scope.doc_index]['amount'] - $scope.printing_charges;
            $scope.court_fee_deficit = $scope.app_list[$scope.doc_index]['court_fee'] - $scope.court_fee_paid;
            if (!isEmpty(id)) {
                $http.get('<?= base_url(); ?>/Filing/Efiling/efiling_documents/' + id + '/' + year)
                    .then(function successCallback(response) {
                        $scope.doc_list = response.data['docs'];
                        $scope.category_list = response.data['category'];
                        $scope.party_list = response.data['parties'];
                        console.log(response.data);
                    }, function errorCallback(response) {});
            }
        }

        $scope.import_data = function(id) {
            if (!isEmpty(id)) {
                $http.get('<?= base_url(); ?>/Filing/Efiling/import_to_icmis/' + id)
                    .then(function successCallback(response) {
                        alert(response.data);
                        console.log(response.data);
                    }, function errorCallback(response) {});
            }
        }

        function printElement(elem) {
            var mywindow = window.open();
            var title = $('#modal_head').val();
            mywindow.document.write('<html><body style="font-size: 14px;">');
            mywindow.document.write('<style> table {  border-collapse: collapse; font-size:14px;} table, td, th {border: 1px solid black;} a {text-decoration: none;}</style>');
            mywindow.document.write('<h2 style="text-align: center; font-size: 120%;">Supreme Court of India</br><u>E-Filing Report</u></h2>');
            mywindow.document.write('<div style="float: right">Generated Date: <?= date("d-m-Y") ?></div>');
            if (elem == "printThis")
                mywindow.document.write('<div style="margin: 0 auto; width: 100px;font-size: 110%;font-weight: bold;"><u>' + $('#modal_head').text() + '</u></div>');
            else
                mywindow.document.write('<br><br>');
            mywindow.document.write(document.getElementById(elem).innerHTML);
            if (elem == "printThis") {
                mywindow.document.write('</br></br><p style="text-align: right;"><u>Dealing Official, E-Filing</u></p>');
                mywindow.document.write('</br></br><p style="text-align: right;"><u>Branch Officer (CC)</u></p>');
                //mywindow.document.write('</br></br><p style="text-align: left;"><u>Branch Officer (Sec-IB)</u></p>');
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

        $scope.page_print_cost_method = function(d1) {
            var d2 = '28/07/2020';
            d1 = d1.split("/").reverse().join("-");
            d2 = d2.split("/").reverse().join("-");
            var date1 = new Date(d1);
            var date2 = new Date(d2);

            if (date1 < date2)
                return 1.5;
            else
                return 0.75;
        }

        $scope.calc_fee = function() {
            //$scope.pages = $scope.pages;
            $scope.printing_charges = $scope.pages * 6;
            $scope.court_fee_paid = $scope.app_list[$scope.doc_index]['amount'] - $scope.printing_charges;
            $scope.court_fee_deficit = $scope.app_list[$scope.doc_index]['court_fee'] - $scope.court_fee_paid;
        }

    });
</script>