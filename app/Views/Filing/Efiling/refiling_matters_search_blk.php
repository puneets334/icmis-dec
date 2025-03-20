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
        .row {
             margin-right: 15px;
             margin-left: 15px;
         }
         
         .modal .modal-header
         {
            position: relative;
         }
         .modal .modal-body {
            padding: 9px 13px 0px 12px !important;
            border-top: 1px solid #ccc;
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
                                     <span class="alert alert-error" style="display: none; color: red;">
                                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                        <span class="form-response"> </span>
                                    </span>
                                    <span class="alert-danger"><?=\Config\Services::validation()->listErrors()?></span>

                                    <?php if(session()->getFlashdata('error')){ ?>
                                        <div class="alert alert-danger text-white ">
                                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                            <?= session()->getFlashdata('error')?>
                                        </div>
                                    <?php } else if(session("message_error")){ ?>
                                        <div class="alert alert-danger">
                                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                            <?=session()->getFlashdata("message_error")?>
                                        </div>
                                    <?php }else{?>
                                        <br/>
                                    <?php }?>
                                   <h5 class="box-title">E-filed Applications- Refiling Report + Additional Documents report for Scrutiny Assistants (Dealing Assistant wise) </h5>
                                    <br/>
                                    <?php
                                    $attribute = array('class' => 'form-horizontal','name' => 'sensitive_report', 'id' => 'sensitive_report', 'autocomplete' => 'off');
                                    echo form_open(base_url('#'), $attribute);
                                    ?>

                                    <div class="row">
                                        <div class="form-group col-sm-2">
                                            <label for="causelistDate">From Date:</label>
                                            <div class="input-group">
                                                <!--<div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>-->
                                                <input type="date" class="form-control pickDate" id="from_date" ng-model="fields.from_date" placeholder="dd-mm-yyyy" autocomplete="off" required>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-2">
                                            <label for="causelistDate">To Date:</label>
                                            <div class="input-group">
                                                <!--<div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>-->
                                                <input type="date" class="form-control pickDate" id="to_date" ng-model="fields.to_date" placeholder="dd-mm-yyyy" autocomplete="off" required>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-2">
                                            <label for="causelistDate">Transaction Status:</label>
                                            <div class="input-group">
                                                <select class="form-control" id="status" ng-model="fields.status" required="true">
                                                    <option value="">Select</option>
                                                    <option value="1">Complete</option>
                                                    <option value="2">Failed Transactions</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-2">
                                            <label for="causelistDate">Document Type:</label>
                                            <div class="input-group">
                                                <select class="form-control" id="app_type" ng-model="fields.app_type" required="true">
                                                <option value="0">Select</option>
                                                <option value="1">Refiling Documents</option>
                                                <option value="2">Additional Documents</option>

                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-2">
                                            <label>&nbsp;</label>
                                            <button type="button" id="btn-shift-assign" class="btn btn-block  btn-flat pull-right btn btn-primary" onclick="get_transaction_details()"><i class="fa fa-save"></i> Search </button>
                                            </button>
                                        </div>

                                    </div>




                                    <?php form_close();?>
                                      <br/>
                                    <div id="result_data"></div>

                                    <center><span id="loader"></span> </center>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<!--  Start Popup  -->

<div class="modal fade" id="diaryModal" role="dialog">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header" style="position: relative;">                   
                    <h4 id="diaryModal_title">Ref ID </h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="org_diary_no">Diary Number</label>
                            <input type="text" class="form-control" id="org_diary_no" placeholder="Diary No." name="org_diary_no">
                        </div>
                        <div class="form-group">
                            <label for="org_diary_no">Diary Year</label>
                            <select class="form-control" id="org_diary_yr" name="org_diary_yr">
                            <option value="">Select Year</option>
                            <?php
                            for($yr=date('Y'); $yr>=1950; $yr--)
                                echo "<option value=$yr>$yr</option>";
                            ?>
                            </select>
                        </div>
                        <div class="form-group">
                        <button type="button" class="btn btn-primary"  onclick="update_org_diary()" >Submit</button>
                        <input type="hidden" name="ack_id" id="ack_id" >
                        <input type="hidden" name="ack_year" id="ack_year" >
                        <input type="hidden" name="ack_dno" id="ack_dno" >
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>



    <div class="modal fade" id="addDocUpdateModal" role="dialog">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4>Update Document Number</h4>
                </div>

                <div class="modal-body">
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
                                for($yr=date('Y'); $yr>=1950; $yr--)
                                    echo "<option value=$yr>$yr</option>";
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <button type="button" class="btn btn-default" data-dismiss="modal" ng-click="update_org_doc_no()" >Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="docModal" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <button type="button" id="Print" class="btn pull-right" ng-click="print_doc()">Print</button>
                    <h4 class="modal-title" id="modal_head" ng-if="postData.diary_no">Diary No: {{postData.diary_no}}/{{postData.diary_year}}</h4>
                    <h4 class="modal-title" id="modal_head" ng-if="postData.ack_id">Ref ID: {{postData.ack_id}}/{{postData.ack_year}}</h4>
                </div>
                + x.org_diary_no.substring(x.org_diary_no.length-4)'];
                <div id="printThis" class="modal-body">
                   <div ng-if=" transactions_list[doc_index]['app_flag'] == 'Deficit'">
                       <p><b>Provisional ID No: </b> {{transactions_list[doc_index].ack_id + "/" + transactions_list[doc_index].ack_year }} </p></br>
                   </div>
                     <div ng-if="transactions_list[doc_index]['app_flag'] != 'Deficit'">
                   <!-- <p><b>Diary No: </b> {{transactions_list[doc_index].d_no.substring(0,transactions_list[doc_index].d_no.length-4)+"/"+ transactions_list[doc_index].d_no.substring(transactions_list[doc_index].d_no.length-4) }} </br>-->
                     </div>
                    <p><b>Case Type: </b>{{transactions_list[doc_index]['casename']}}</br>
                    <b>CauseTitle: </b>{{transactions_list[doc_index]['pet_name']}} vs {{transactions_list[doc_index]['res_name']}}</br></p>

                    + x.org_diary_no.substring(x.org_diary_no.length-4)'];

                 <div ng-if="transactions_list[doc_index]['app_flag'] == 'Deficit_DN' || transactions_list[doc_index]['app_flag'] == 'Deficit' ">

                    <!-- If block -->
                        <b>Matter filed by : </b>{{transactions_list[doc_index]['adv_name']}}</br>
                        <b>Mobile : </b>{{transactions_list[doc_index]['mobile']}}</br>
                        <b>Email Id : </b>{{transactions_list[doc_index]['email']}}</br>

                    </div>
                    <div ng-if="transactions_list[doc_index]['app_flag'] != 'Deficit_DN' &&  transactions_list[doc_index]['app_flag'] != 'Deficit'">
                    <!-- Your Else Block -->
                    <b>Applied By: </b>{{doc_list[0]['name']}}</br>
                    <b>Email: </b>{{doc_list[0]['email']}}</br>
                    <b>Mobile: </b>{{doc_list[0]['mobile_no']}}</br></p>






                                            <div class="box-header with-border">
                                                    <h3 class="box-title" id="form-title">E-filed Applications -Refiling Report</h3><span style="float: right"><input type="text" class="form-control" ng-model="searchText" placeholder="Search"></span><span style="float: right"><button type="button" class="btn bg-purple btn-flat" ng-click="print_table()">Print</button></span><br/>
                                                </div>
                                                <p class="text-red">{{loadingData ? "Please wait. Loading Data...":""}}</p>
                                                <div id="printTable">
                                                if="x.ack_id==0"                          <table class="table table-striped table-hover">
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
                                                            <td style="cursor:pointer;" ng-click="get_docs($index)"  data-toggle="modal" data-target="#docModal" ng-if="x.org_diary_no!=0 && x.app_flag!='Deficit_DN'">{{ x.org_diary_no.substring(0, x.org_diary_no.length-4) + "/" + x.org_diary_no.substring(x.org_diary_no.length-4) }}</td>
                                                            <td style="cursor:pointer;" ng-if="(x.org_diary_no==0 && x.d_no==0) || (x.org_diary_no==0 && x.app_flag!='Deficit_DN')">-</td>

                                                            <td style="cursor:pointer;" ng-if="x.app_flag=='Deficit_DN'">{{ x.pet_name }} vs {{ x.res_name }}</td>
                                                            <td style="cursor:pointer;" ng-if="x.app_flag=='Deficit_DN'">{{ x.transaction_id }}</td>
                                                            <td style="cursor:pointer;" ng-if="x.app_flag=='Deficit_DN'">{{ x.amount }}</td>
                                                            <td style="cursor:pointer;" ng-if="x.app_flag=='Deficit_DN'">{{ x.transaction_datetime }}</td>
                                                            <!--<td style="cursor:pointer;" ng-if="x.app_flag=='Deficit_DN'">{{ x.app_flag }}</td>-->

                                                            <td style="cursor:pointer;" ng-if="x.app_flag!='Deficit_DN'" ng-click="get_docs(transactions_list.indexOf(x))"  data-toggle="modal" data-target="#docModal" data-backdrop="static" data-keyboard="false">{{ x.pet_name }} vs {{ x.res_name }}</td>
                                                            <td style="cursor:pointer;" ng-if="x.app_flag!='Deficit_DN'" ng-click="get_docs(transactions_list.indexOf(x))"  data-toggle="modal" data-target="#docModal" data-backdrop="static" data-keyboard="false">{{ x.transaction_id }}</td>
                                                            <td style="cursor:pointer;" ng-if="x.app_flag!='Deficit_DN'" ng-click="get_docs(transactions_list.indexOf(x))"  data-toggle="modal" data-target="#docModal" data-backdrop="static" data-keyboard="false">{{ x.amount }}</td>
                                                            <td style="cursor:pointer;" ng-if="x.app_flag!='Deficit_DN'" ng-click="get_docs(transactions_list.indexOf(x))"  data-toggle="modal" data-target="#docModal" data-backdrop="static" data-keyboard="false">{{ x.transaction_datetime }}</td>
                                                            <td style="cursor:pointer;"  ng-click="get_docs(transactions_list.indexOf(x))"  data-toggle="modal" data-target="#docModal" data-backdrop="static" data-keyboard="false">{{ x.app_flag }}</td>

                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
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
                        <tbody >
                        <p class="text-red">{{loadingData ? "Please wait. Loading Data...":""}}</p>
                        <tr ng-repeat="x in doc_list">
                            <td>{{ $index+1 }}.</td>
                            <td style="cursor:pointer;" ng-if="!x.docdesc">{{ x.main_doc }} ( {{ x.sub_doc }} )</td>
                            <td style="cursor:pointer;" ng-if="x.docdesc">{{ x.docdesc }} </td>
                            <td style="cursor:pointer;" ><a href="{{ x.pdf_file }}" target="_blank"> View </a></td>
                            <td style="cursor:pointer;" >{{ x.np }}</td>


                            <td style="cursor:pointer;" ng-if="x.source_flag=='Additional_docs'" ng-click="setAddDocId(x.id)" data-toggle="modal" data-target="#addDocUpdateModal">{{ x.source_flag }}</td>
                            <td style="cursor:pointer;" ng-if="x.source_flag!='Additional_docs' ">{{ x.source_flag }}</td>
                            <td style="cursor:pointer;" >{{ x.org_docnum}}/{{x.org_docyear}}</td>
                       </div> </tr>
                        </tbody>
                    </table>
                    </div>

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
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>


<script>
function get_transaction_details()
{
    var CSRF_TOKEN = 'CSRF_TOKEN';
    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
    var from_date  = $('#from_date').val();
    var to_date  = $('#to_date').val();
    var status  = $('#status').val();
    var app_type = $('#app_type').val();
    if(from_date == "")
    {
        alert('Please input From Date!!');
        $('#from_date').focus();
        return false;
    }
    if(to_date == "")
    {
        alert('Please input To Date!!');
        $('#to_date').focus();
        return false;
    }
    if(status == "")
    {
        alert('Please select Transaction Status!!');
        $('#status').focus();
        return false;
    }
    if(app_type == "")
    {
        alert('Please select Document Type!!');
        $('#app_type').focus();
        return false;
    }
    $.ajax({
        url: baseURL + "/Filing/Efiling/refiled_documents",
        //cache: false,
        //async: true,
        data: { from_date: from_date, to_date:to_date,status:status,app_type:app_type },
        headers: {
        'X-CSRF-Token': CSRF_TOKEN_VALUE  
        },
        type: "POST",
        
        success: function (data, status) {
            updateCSRFToken();
            $("#result_data").html(data);
        },
        error: function (xhr) {
            updateCSRFToken();
            alert("Error: " + xhr.status + " " + xhr.statusText);
        
        },
    });
}


 function get_ack_no(id, year, efile_diary_no)
 {
    
    $('#diaryModal').modal('show');
    $('#diaryModal_title').html('Ref ID '+ id+'/'+year);
    $('#ack_id').val(id);
    $('#ack_year').val(year);
    $('#ack_dno').val(efile_diary_no);
}

function update_org_diary()
{
    var CSRF_TOKEN = 'CSRF_TOKEN';
    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
    var ack = $('#ack_id').val();
    var ack_year = $('#ack_year').val();
    var efile_diary = $('#ack_dno').val();
    var org_diary_no = $('#org_diary_no').val();
    var org_diary_yr = $("#org_diary_yr").val();

    if(org_diary_no == "")
    {
        alert('Please input Diary No!!');
        $('#org_diary_no').focus();
        return false;
    }

    if(org_diary_yr == "")
    {
        alert('Please select Diary year!!');
        $('#org_diary_yr').focus();
        return false;
    }

    $.ajax({
        url: baseURL + "/Filing/Efiling/set_actual_diary_no",
        //cache: false,
        //async: true,
        data: { ack: ack, ack_year:ack_year,efile_diary:efile_diary,org_diary_no:org_diary_no,org_diary_yr:org_diary_yr },
        headers: {
        'X-CSRF-Token': CSRF_TOKEN_VALUE  
        },
        type: "POST",
        
        success: function (data, status) {
            updateCSRFToken();
            if(data == 1)
            {
                alert('Record Updated');
                $('#diaryModal').modal('hide');
            } 
        },
        error: function (xhr) {
            updateCSRFToken();
            alert("Error: " + xhr.status + " " + xhr.statusText);
        
        },
    });
 
}

$scope.get_docs = function(index){
            $scope.doc_list = {};
            $scope.postData={};
            $scope.doc_index = index;
            var url='';

            if($scope.transactions_list[index]['app_flag'] == 'Add Doc' || $scope.transactions_list[index]['app_flag'] == 'Add Doc Sp' || $scope.transactions_list[index]['app_flag'] == 'Refiling' || $scope.transactions_list[index]['app_flag'] == 'ReFiling'){
                url = '<?=base_url(); ?>index.php/Efiling/docs_from_sc_diary_no';
                var diary = $scope.transactions_list[index]['org_diary_no'];
                $scope.postData.diary_no=diary.substring(0, diary.length-4);
                $scope.postData.diary_year=diary.substring(diary.length-4);
                $scope.postData.transaction_id=$scope.transactions_list[index]['transaction_id'];
            }
            else if($scope.transactions_list[index]['app_flag'] == 'Filing'){
                url = '<?=base_url(); ?>index.php/Efiling/check_documents';
                $scope.postData.ack_id=$scope.transactions_list[index]['ack_id'];
                $scope.postData.ack_year=$scope.transactions_list[index]['ack_year'];
                $scope.postData.transaction_id=$scope.transactions_list[index]['transaction_id'];
            }

            $scope.loadingData = true;
            $http.post(url, {
                data    : $scope.postData
            })
                .then(function successCallback(response) {
                    $scope.doc_list = response.data;
                    $scope.loadingData = false;
                    console.log(response.data);
                }, function errorCallback(response) {
                    $scope.loadingData = false;
                });
        }

</script>

