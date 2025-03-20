        <!--<div class="container">-->
        <!-- Main content -->

        <form name="frm" id="frm">
        <input type="hidden" name="empid" id="empid" value="<?php //echo $empid;?> ">
                                <input type="hidden" name="desig" id="desig" value="<?php //echo $desig;?> ">

                    <!--<div id="reg_date_grp">-->
                    <div class="row g-3">
                    <div class="col"> 
                     
                                <label for="from_date" class="">From date</label>
                                <input type="date" class="form-control datepick" id="from_date" ng-model="from_date" placeholder="From Date">
                                
                        </div>
                  
                        <div class="col"> 
                                <label for="to_date" class="">To date</label>
                                
                                    <input type="date" class="form-control datepick" id="to_date" ng-model="to_date" ng-change="check_date()" placeholder="To Date">
                                
                    </div>
                
                    <div class="col"> 
                
                                <label for="from_date" class="">Delivery Mode</label>
                               
                                    <select class="form-control" id="deliver_mode" ng-model="deliver_mode">
                                        <option value="">Select Delivery Mode</option>
                                        <option value="1">By Post</option>
                                        <option value="2">By Hand</option>
                                    </select>
                                
                        </div>

                        <div class="col"> 
                                <label for="to_date" class="">Document</label>
                               
                                    <select class="form-control" id="order_type" ng-model="order_type">
                                        <option value="">Select Document Type</option>
                                        <?php
                                        // foreach($order_type as $doc)
                                        //     echo '<option value="'.$doc['id'].'">'.$doc['order_type'].'</option>';
                                        ?>
                                    </select>
                              
                    </div>

                    
                    <div class="col">  <button type="submit" class="btn btn-primary float-right" > Submit </button></div>
                    
                    <!-- <i class="fa fa-save"></i> -->
            </div>
    </form>
    
            <div class="col-md-12" ng-if="action_pending_list.length > 0">
                <div class="well">
                    <div class="box">
                        <div class="box-header">

                            <div class="col-xs-6"><button type="submit"  style="width:15%;float:left" id="print" name="print"  onclick="printDiv('printable')" class="btn btn-block btn-warning">Print</button></div>
                            <div id="printable">
                                <div class="col-xs-6"><h3 class="box-title"><?php echo "Pending Copying Requests"; ?></h3></div>
                                <div class="box-body no-padding">
                                    <table class="table table-striped">
                                        <tbody>
                                        <tr>
                                            <th style="width: 10px">#</th>
                                            <th>Application Number</th>
                                            <th>Case No</th>
                                            <th>Documents</th>
                                            <th>Remarks</th>
                                            <th>Registered On</th>
                                            <th>Pendency<br>(in days)</th>
                                            <th>D.A.</th>
                                            <th>Case Status</th>
                                            <th>Disposal Date<br>Consignment Date</th>
                                            <th>Last Updated By</th>
                                        </tr>
                                        <tr ng-repeat="x in action_pending_list | filter:projectList.search">
                                            <td></td>
                                            <td></td>
                                            <td ng-if="x.diary_no_display!='/'"></td>

                                            <td ng-if="x.diary_no_display=='/'"></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td ng-if="x.c_status=='P' && x.section_name"></td>
                                            <td ng-if="x.c_status!='P' && x.section_name" ></td>

                                            <td ng-if="!x.section_name && (x.tentative_da || x.sec) "></td>
                                            <td ng-if="!x.sec && !x.section_name"></td>
                                            <td ng-if="x.c_status=='P'" style="color:green !Important;"></td>
                                            <td ng-if="x.c_status!='P'" style="color:red !Important;"></td>
                                            <td ng-if="x.disposal_dt || x.consignment_date"></td>
                                            <td ng-if="!x.disposal_dt && !x.consignment_date"></td>
                                            <td ng-if="x.updatedby"></td>
                                            <td ng-if="!x.updatedby"></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

        </section>
        <!--</div>-->
    </div>
</div>

<script>
    $(function () {
        $('.datepick').datepicker({
            format: 'dd-mm-yyyy',
            autoclose:true
        });
    });

    var app = angular.module('copyApp', []);
    app.filter("jsDate", function() {
        return function(x) {
            return new Date(x);
        };
    });
    app.controller('copyCtrl', function($scope, $http) {
        $scope.check_date = function(){
            var fromDate = $('#from_date').val();
            var toDate = $('#to_date').val();
            date1 = new Date(fromDate.split('-')[2], fromDate.split('-')[1] - 1, fromDate.split('-')[0]);
            date2 = new Date(toDate.split('-')[2], toDate.split('-')[1] - 1, toDate.split('-')[0]);
            if (date1 > date2) {
                alert("To Date must be greater than From date");
                return false;
            }
        }


        function isEmpty(str) {
            return (!str || 0 === str.length);
        }

        $scope.getReport = function(){
            $scope.action_pending_list = [];
            var empid=$('#empid').val();
            if( !isEmpty($scope.from_date) && !isEmpty($scope.to_date) ){
                $http.post('<?=base_url();?>index.php/Application/getActionPendingReportDA', {
                        empid        :empid,
                        from_date    : $scope.from_date,
                        to_date      : $scope.to_date,
                        deliver_mode : $scope.deliver_mode,
                        order_type   : $scope.order_type
                    }
                ).then(function successCallback(response) {
                    var data = response.data;
                    if(data.length==0){
                    }
                    else{
                        $scope.action_pending_list = response.data;
                    }
                }, function errorCallback(response) {
                });
            }

        }


    });
</script>
</body>
</html>
