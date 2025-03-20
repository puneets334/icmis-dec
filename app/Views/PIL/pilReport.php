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
        .box.box-danger {
            border-top-color: #dd4b39;
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
        .row {
            margin-right: 15px;
            margin-left: 15px;
        }
    </style>


    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header heading">

                            <div class="row">
                                <div class="col-sm-10">
                                    <h3 class="card-title">PIL(E) >> Pil Report</h3>
                                </div>


                            </div>
    

                            <?php if (session()->getFlashdata('infomsg')) { ?>
                                <div class="alert alert-success">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    <strong> <?= session()->getFlashdata('infomsg') ?></strong>
                                </div>

                            <?php } ?>
                            <?php if (session()->getFlashdata('success_msg')) : ?>
                                <div class="alert alert-danger alert-dismissible">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    <strong> <?= session()->getFlashdata('success_msg') ?></strong>
                                </div>
                            <?php endif; ?>

                           </div>

                                <span class="alert alert-error" style="display: none;">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                <span class="form-response"> </span>
                                </span>

                           <?= view('PIL/pilReportHeading'); ?>


                            <?php
                            $attribute = array('class' => 'form-horizontal', 'name' => 'push-form', 'id' => 'push-form', 'autocomplete' => 'off', 'method' => 'POST');
                            echo form_open(base_url('PIL/PilController/getPilReport'), $attribute);
                            ?>
                           </br></br>

                                  <div class="row col-md-12 ">

                                      <div class="col-md-3">
                                          <label class="control-label"><h5>Report As</h5></label>
                                            <select class="form-control" name="reportType" id="reportType" >
                                                <option value="R">Received Date</option>
                                                <option value="D">Destroy Date</option>
                                                <option value="P">Petition Date</option>
                                            </select>
                                        </div>
                                      <div class="col-md-3">
                                          <label class="control-label"><h5>From Date</h5></label>
                                            <input type="date" id="from_date" name="from_date" class="form-control" required placeholder="From Date">
                                       </div>
                                      <div class="col-md-3">
                                          <label class="control-label"><h5>To Date</h5></label>
                                            <input type="date" class="form-control" id="to_date" required name="to_date" placeholder="To Date">
                                        </div>
                                        <div class="col-md-3">
                                            <button type="button" style="margin-left: 7%;margin-top: 11%;" id="view" name="save" value="submit" onclick="checkDates()" class="btn btn-primary">View</button>
                                        </div>
                                      <?php form_close(); ?>

                                    </div> <br><br> <br>

                        <div id="printable" class="box box-danger">
                        <?php
//                        print_r($reportType);
//                        print_r($to_date);
//                        print_r($first_date);
//                        print_r($pil_result);
//                        die;

                        $reportText="Received";
                        if(isset($reportType)){
                            if($reportType=="D"){
                                $reportText="Destroyed";
                            }
                            elseif ($reportType=="P"){
                                $reportText="Petition Date";
                            }
                        }
                        ?>
                            <br>


                        <?php
                        if(!empty($pil_result)) {
//                            echo "<pre>";
//                            print_r($pil_result);
//                            die;

                        ?>
                     <h2 align="center">PIL <?=$reportText?>  Between <?php echo !empty($first_date)? date('d-m-Y',strtotime($first_date)):'';?> to <?php echo !empty($to_date)?date('d-m-Y',strtotime($to_date)):'';?></h2>
                            <br>
                    <div id="query_builder_wrapper" class="dataTables_wrapper dt-bootstrap4 query_builder_wrapper">
                        <table  id="reportTable1" style="width: 100%" class="table table-bordered table-striped datatable_report">
                            <thead>
                            <tr>
                                <th width="7%">SNo.</th>
                                <th width="7%">Inward Number</th>
                                <th width="15%">Address To</th>
                                <th width="25%">Received From</th>
                                <th width="7%">Received On</th>
                                <th width="6%">Petition Date</th>
                                <th width="24%">Status</th>
                                <th width="16%">Updated By</th>

                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $i=1;
//                            echo "<pre>";
//                            print_r($pil_result);
//                            die;

                            foreach ($pil_result as $result)
                            {


                                ?>
                                <tr>
                                    <td><?= $i++; ?></td>

                                    <td>
                                        <a href="<?=base_url();?>/PIL/PilController/rptPilCompleteData/<?=$result['id']?>" target="_blank">
                                            <?=$result['pil_diary_number'];?>
                                        </a>
                                    </td>
                                    <td><?=$result['address_to'];?></td>
                                    <td><?=$result['received_from'];?><br/><?=$result['address'];?>
                                        <?php
                                        if(!empty($result['state_name'])){
                                            echo " ,State: ".$result['state_name'];
                                        }
                                        if(!empty($result['email'])){
                                            echo "<br/> Email: ".$result['email'];
                                        }
                                        if(!empty($result['mobile'])){
                                            echo "<br/> Mobile: ".$result['mobile'];
                                        }
                                        ?>
                                    </td>
                                    <td><?=!empty($result['received_on'])?date("d-m-Y", strtotime($result['received_on'])):null?></td>
                                    <td><?=!empty($result['petition_date'])?date("d-m-Y", strtotime($result['petition_date'])):null?></td>
                                    <td><?php
                                        if(!empty($result['action_taken']))
                                        {
                                            switch (trim($result['action_taken'])){
                                                case "L":{
                                                    $actionTakenText = "No Action Required"; break;
                                                }
                                                case "W":{
                                                    $actionTakenText = "Written Letter to ".$result['written_to']. " on ".date('d-m-Y', strtotime($result['written_on'])) ; break;
                                                }
                                                case "R":{
                                                    $actionTakenText = "Letter Returned to Sender on ".date('d-m-Y', strtotime($result['return_date'])) ; break;
                                                }
                                                case "S":{
                                                    $actionTakenText = "  ".$result['sent_to']. " on ".date('d-m-Y', strtotime($result['sent_on'])); break;
                                                }
                                                case "T":{
                                                    if($result['transfered_on']!== null)
                                                        $result['transfered_on'] = date('d-m-Y', strtotime($result['transfered_on']));
                                                    $actionTakenText = "Letter Transferred To ".$result['transfered_to']." on ".$result['transfered_on']; break;
                                                }
                                                case "I":{
                                                    $actionTakenText = "Letter Converted To Writ"; break;
                                                }
                                                case "O":{
                                                    $actionTakenText = "Other Remedy"; break;
                                                }
                                                default:{
                                                    $actionTakenText = "UNDER PROCESS"; break;
                                                }
                                            }
                                            echo $actionTakenText;

                                        }else{
                                            $actionTakenText = "UNDER PROCESS";
                                            echo $actionTakenText;
                                        }

                                        ?>
                                    </td>
                                    <td><?=$result['username'].'('.$result['empid'].')'?>
                                        <br/> At: <?=date('d-m-Y h:i:s A', strtotime($result['updated_on']))?></td>
                                </tr>

                                <?php
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                        <?php
                          }
                        ?>
                        </div>
                    </div>

                    </div>


                </div>



            </div> <!-- card div -->



        </div>
        <!-- /.col -->
        </div>
        <!-- /.row -->




        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.section -->
    <script>

        function checkDates() {
            var fromDate = document.getElementById('from_date').value;
            var toDate = document.getElementById('to_date').value;
            // console.log(typeof (fromDate));return false;

            if( (fromDate == '') && (toDate == ''))
            {
                alert("Please select the from date and to date also !!!!");
                document.getElementById('from_date').focus();
                // document.getElementById('to_date').focus();

            }else{
                if (fromDate > toDate) {
                    alert("To Date must be greater than From date");
                    return false;
                }
                document.getElementById('push-form').submit();
            }


        }

        $(function () {
            $(".datatable_report").DataTable({
                "responsive": true, "lengthChange": false, "autoWidth": false,
                "buttons": ["copy", "csv", "excel",{extend: 'pdfHtml5',orientation: 'landscape',pageSize: 'LEGAL' },
                    { extend: 'colvis',text: 'Show/Hide'}],"bProcessing": true,"extend": 'colvis',"text": 'Show/Hide'
            }).buttons().container().appendTo('.query_builder_wrapper .col-md-6:eq(0)');

        });
    </script>
