<?= view('header'); ?>
<style>
    button#btn-shift-assign {
        margin-top: 26px;
    }
</style>
<style>
    .red {
        color: red;
        cursor: pointer;
    }

    .green {
        color: black;
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
</style>
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

    /*end loader*/
</style>
<div class="wrapper" ng-app="sclscApp" ng-controller="sclscCtrl" data-ng-init="clearForm()">
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">

                    <div class="card">
                        <div class="card-header p-2" style="background-color: #fff; border-bottom:none;">

                            <?php
                            $user_idd = session()->get('login')['usercode'];
                            ?>
                            <h4 class="basic_heading">SCLSC Refiled Documents</h4>
                        </div>

                        <div class="card-body">
                        <?php
                                $attribute = array('class' => 'form-horizontal diary_generation_form', 'name' => 'diary_generation_form', 'id' => 'diary_generation_form', 'autocomplete' => 'off');
                                echo form_open('#', $attribute);

                                ?>
                            <div class="row">
                                <div class="form-group col-sm-2">
                                    <label for="from_date">From Date:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                        </div>
                                        <input type="text" class="form-control pickDate" id="from_date" ng-model="fields.from_date" placeholder="dd-mm-yyyy" autocomplete="off" required>
                                    </div>
                                </div>
                                <div class="form-group col-sm-2">
                                    <label for="to_date">To Date:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                        </div>
                                        <input type="text" class="form-control pickDate" id="to_date" ng-model="fields.to_date" placeholder="dd-mm-yyyy" autocomplete="off" required>
                                    </div>
                                </div>

                                <div class="form-group col-sm-2">
                                    <label>&nbsp;</label>
                                    <button type="button" id="btn-shift-assign" class="btn btn-block btn-success btn-flat" onclick="get_transaction_details()" >
                                        <i class="fa fa-search"></i> Search
                                    </button>
                                </div>
                            </div>
                            <?=form_close(); ?>
                        </div>
                    </div>


                    <div id="result1" ></div>
                     



                    <div class="modal fade" id="docModal" role="dialog">
                        <!-- <div class="modal-dialog modal-lg"> -->
                        <div class="modal-content">
                            <div class="modal-header">

                                <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
                                <button type="button" id="Print" class="btn pull-right" ng-click="print_doc()">Print</button>

                            </div>

                            <div class="modal-header with-border">
                                <!-- <h3 class="modal-title" id="form-title">SCLSC Refiled Documents List </h3> -->
                            </div>
                            <div id="printThis" class="modal-body">
                                <div class="table-responsive">
                                    <table class="table table-striped custom-table">
                                        <tbody>
                                            <tr ng-repeat="x in doc_list_sclsc|limitTo:1">

                                                <td align="left"> Diary No:{{ x.diary_no}} <br>
                                                    Casetype : {{ x.casename }} <br>
                                                    Diarised On: {{ x.diary_on  }}
                                                </td>

                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-striped custom-table">
                                        <thead>
                                            <tr>
                                                <th>#</th>

                                                <th>Document Name</th>
                                                <th>Filed on</th>
                                                <th>Pages</th>
                                                <th>PDF <span class="glyphicon glyphicon-chevron-up"></span></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <p class="text-red">{{loadingData ? "Please wait. Loading Data...":""}}</p>
                                            <tr ng-repeat="x in doc_list_sclsc">
                                                <td>{{$index +1}}.</td>

                                                <td>{{ x.document_name }} </td>
                                                <td>{{ x.filing_date  }}</td>
                                                <td>{{ x.total_pages }} </td>
                                                <td style="cursor:pointer;"><a href="{{ x.paperbook_url }}" target="_blank"> View </a></td>

                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div> -->
                        </div>

                        <!-- </div> -->
                    </div>



                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
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

    function get_transaction_details() {         
          
         var from_date = $('#from_date').val();
         var to_date = $('#to_date').val();
         // Input Validation
         if (from_date == '') {
             alert("Please select the 'From Date'.");
             $('#from_date').focus();
             
             return false;
         }

         if (to_date == '') {
             alert("Please select the 'To Date'.");
             $('#to_date').focus();              
             return false; 
         }

         var CSRF_TOKEN = 'CSRF_TOKEN';
         var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

             $.ajax({
                 type: 'POST',
                 url: "<?= base_url(); ?>/Filing/Sclsc/refiledDocumentsReportLIst",
                 beforeSend: function (xhr) {
                     $("#result1").html('<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>');
                 },
                 data:{from_date:from_date,to_date:to_date,CSRF_TOKEN: CSRF_TOKEN_VALUE}
             })
             .done(function(msg){
                 updateCSRFToken();
                 $("#result1").html(msg);
                  
             })
             .fail(function(){
                 updateCSRFToken();
                 alert("ERROR, Please Contact Server Room"); 
             }); 
        
      
     }


     function get_docs(index) {
            $scope.doc_list_sclsc = {};
            $scope.postData = {};
            $scope.doc_index_sclsc = index;
            var url = '';


            url = '<?= base_url(); ?>/Filing/Sclsc/sclsc_get_documents';


            $scope.loadingData = true;
            $http.post(url, {
                    data: index
                })
                .then(function successCallback(response) {

                    $scope.doc_list_sclsc = response.data;
                    //  alert(doc_list_sclsc);
                    $scope.loadingData = false;
                    console.log(response.data);
                }, function errorCallback(response) {
                    $scope.loadingData = false;
                });
        }

        function printElement(elem) {
            var mywindow = window.open();
            var title = $('#modal_head').val();
            mywindow.document.write('<html><body style="font-size: 14px;">');
            mywindow.document.write('<style> table {  border-collapse: collapse; font-size:14px;} table, td, th {border: 1px solid black;} a {text-decoration: none;}</style>');
            mywindow.document.write('<h2 style="text-align: center; font-size: 120%;">Supreme Court of India</br><u>SCLSC Refiling Report</u></h2>');
            mywindow.document.write('<div style="float: right">Generated Date: <?= date("d-m-Y") ?></div>');
            //var txn_date = $('#txn_date').val();
            var d = new Date(txn_date);
            // var txn_date = ((d.getDate()<10)?'0'+d.getDate():d.getDate())+'-'+((d.getMonth()<10)?'0'+d.getMonth():d.getMonth())+'-'+d.getFullYear();
            var txn_date = ((d.getDate() < 10) ? '0' + d.getDate() : d.getDate()) + '-' + ((d.getMonth() + 1 < 10) ? '0' + (d.getMonth() + 1) : (d.getMonth() + 1)) + '-' + d.getFullYear();
            var txn_type = $('#txn_type').val();
            // mywindow.document.write('<br/><div style="float: right">'+txn_type+' Date: '+txn_date+' </div>');
            if (elem == "printThis")
                mywindow.document.write('<div style="margin: 0 auto; width: 100px;font-size: 110%;font-weight: bold;"><u>' + $('#modal_head').text() + '</u></div>');
            else
                mywindow.document.write('<br><br>');
            mywindow.document.write(document.getElementById(elem).innerHTML);
            if (elem == "printThis") {
                //if(txn_type=='Filing'){
                mywindow.document.write('</br></br><p style="text-align: right;"><u>Dealing Official, SCLSC</u></p>');
                //}
                mywindow.document.write('</br></br><p style="text-align: left;"><u>Branch Officer (Sec- I-B)</u></p>');
            }
            mywindow.document.write('</body></html>');
            mywindow.document.close(); // necessary for IE >= 10
            mywindow.focus(); // necessary for IE >= 10*/

            mywindow.print();
            mywindow.close();

            return true;
        }

       function print_doc() {
            printElement("printThis");
        }

        function print_table() {
            printElement("printTable");
        }

    //$('#docModal').modal({backdrop: 'static', keyboard: false});
    var app = angular.module('sclscApp', []);
    app.controller('sclscCtrl', function($scope, $http) {
        $scope.loader = 0;
        $scope.doc = {};
        $scope.tid = 0;
        $scope.postData = {};
        $scope.modal = {};
        $scope.doc_modal = {};
        $scope.doc_index = null;
        $scope.ack = 0;
        $scope.ack_year = 0;
        $scope.efile_diary = 0;
        $scope.addDocId = 0;
        $scope.docmaster = null;
        $scope.upd_val = '';
        $scope.action_modal = '';
        // $scope.action_type='';
        $scope.clearForm = function() {
            $scope.fields = {};
        }
        $scope.callLoader = function() {
            $scope.loader = 1;
        }

        function isEmpty(str) {
            return (!str || 0 === str.length);
        }

        



        $scope.get_docs = function(index) {
            $scope.doc_list_sclsc = {};
            $scope.postData = {};
            $scope.doc_index_sclsc = index;
            var url = '';


            url = '<?= base_url(); ?>/Filing/Sclsc/sclsc_get_documents';


            $scope.loadingData = true;
            $http.post(url, {
                    data: index
                })
                .then(function successCallback(response) {

                    $scope.doc_list_sclsc = response.data;
                    //  alert(doc_list_sclsc);
                    $scope.loadingData = false;
                    console.log(response.data);
                }, function errorCallback(response) {
                    $scope.loadingData = false;
                });
        }

        function printElement(elem) {
            var mywindow = window.open();
            var title = $('#modal_head').val();
            mywindow.document.write('<html><body style="font-size: 14px;">');
            mywindow.document.write('<style> table {  border-collapse: collapse; font-size:14px;} table, td, th {border: 1px solid black;} a {text-decoration: none;}</style>');
            mywindow.document.write('<h2 style="text-align: center; font-size: 120%;">Supreme Court of India</br><u>SCLSC Refiling Report</u></h2>');
            mywindow.document.write('<div style="float: right">Generated Date: <?= date("d-m-Y") ?></div>');
            //var txn_date = $('#txn_date').val();
            var d = new Date(txn_date);
            // var txn_date = ((d.getDate()<10)?'0'+d.getDate():d.getDate())+'-'+((d.getMonth()<10)?'0'+d.getMonth():d.getMonth())+'-'+d.getFullYear();
            var txn_date = ((d.getDate() < 10) ? '0' + d.getDate() : d.getDate()) + '-' + ((d.getMonth() + 1 < 10) ? '0' + (d.getMonth() + 1) : (d.getMonth() + 1)) + '-' + d.getFullYear();
            var txn_type = $('#txn_type').val();
            // mywindow.document.write('<br/><div style="float: right">'+txn_type+' Date: '+txn_date+' </div>');
            if (elem == "printThis")
                mywindow.document.write('<div style="margin: 0 auto; width: 100px;font-size: 110%;font-weight: bold;"><u>' + $('#modal_head').text() + '</u></div>');
            else
                mywindow.document.write('<br><br>');
            mywindow.document.write(document.getElementById(elem).innerHTML);
            if (elem == "printThis") {
                //if(txn_type=='Filing'){
                mywindow.document.write('</br></br><p style="text-align: right;"><u>Dealing Official, SCLSC</u></p>');
                //}
                mywindow.document.write('</br></br><p style="text-align: left;"><u>Branch Officer (Sec- I-B)</u></p>');
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