<?= view('header'); ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Upload Old Judgments</h3>
                            </div>

                            
                        </div>
                    </div>
                    <div class="card-body">
                    <?= view('Library/court_notification'); ?><br>
        <div class="row">

            
            <div class="col-sm-9 " id="" > 
            <div class="table-responsive">
                <table class="table ">
                    <thead>
                        <tr>
                            <th>Court Number</th> 
                            <th>Cause List Item No</th> 
                            <th>Section</th>
                            <th>Urgent</th>
                            <!-- <th>Bench</th> -->
                            <th>User</th>
                            <!-- <th>Remark</th> -->
                            <th>Status</th> <th>Entry Time</th>
                            <th>Closed Time</th>  <th>Interaction History</th>
                        </tr>
                    </thead>
                    <tbody id="statusData">
                    <?php
                    $cnt = 1;
                    if (!empty($requistionData)) {
                    foreach ($requistionData as $result) 
                    {

                        if ($result->current_status == "pending" || $result->current_status == 'Interaction') {
                        $btnVal = '<button type="button" class="btn btn-danger">PENDING</button>';
                        }if ($result->current_status == "received") {
                        $btnVal = '<button type="button" class="btn btn-primary">' . strtoupper($result->current_status) . '</button>';
                        }if ($result->current_status == "Sent") {
                        $btnVal = '<button type="button" class="btn btn-info">' . strtoupper($result->current_status) . '</button>';
                        }if ($result->current_status == 'attending') {
                        $btnVal = '<button type="button" class="btn btn-warning">' . strtoupper($result->current_status) . '</button>';
                        }if ($result->current_status == 'closed') {
                        $btnVal = '<button type="button" class="btn btn-success">' . strtoupper($result->current_status) . '</button>';
                        }if ($result->current_status == 'cancel') {
                        $btnVal = '<button type="button" class="btn btn-secondary">' . strtoupper($result->current_status) . '</button>';
                        }
                    /*if ($result->current_status == 'Interaction') {
                    $btnVal = '<button type="button" class="btn btn-primary">' . strtoupper($result->current_status) . '</button>';
                    }*/
                        $requisition->requisition_id = $result->id;
                        $interactions = $requisition->view_requistion_interactions();
                        $interaction_count = $interactions->rowCount();
                        if($result->urgent=="Yes")
                        {
                            $urgentVal="<span class='badge bg-danger'>".strtoupper($result->urgent)."</span>";
                        }else{
                            $urgentVal="No";
                        }
                    ?>
                    <tr>   
                    <td>
                    <?php if ($result->current_status != 'closed' && $result->current_status != 'cancel' && $result->current_status != 'received') { ?>
                    <a href="#" onclick="view_requistion_result(<?php echo $result->id; ?>)"><b><?php echo $result->court_number; ?></b></a>
                    <?php
                    } else {
                    echo $result->court_number;
                    }
                    ?>
                    </td>

                    <td><b><?php echo $result->itemNo; ?></b></td>  
                    <td><?php echo ucwords($result->section); ?></td>
                    <td><?php echo $urgentVal; ?></td>
                    <!-- <td align="center"><?php //echo ucwords($result->court_bench); ?></td> -->
                    <td><?php echo ucwords($result->court_username); ?></td>
                    <!--  <td style="word-wrap:break-word;"><?php echo htmlentities($result->remark1); ?></td> -->  
                    <td><?php echo $btnVal; ?></td> 
                    <td><?php
                    $entry_on = explode(" ", $result->created_on);
                    echo $entry_on[1];
                    ?></td> 
                    <td><p><?php $closeDate = explode(" ", $result->request_close_datetime);
                    echo $closeDate[1];
                    ?></p>


                    </td> 
                    <td><?php if ($interaction_count) { ?>
                    <a href="#" onclick="openWin(<?php echo $result->id ?>);"><button type="button" class="btn btn-warning">View </button></a><?php } ?>
                    </td>
                    </tr>


                    <?php }//end of while
                    }
                    ?>


                    </tbody>
                </table>
                </div>
            </div>


            <!-- ./wrapper -->



            <div class="col-sm-3">
               
                <div class="container">
                    <div class="alert alert-danger" role="alert" id="errorMsg" style="display:none">                           
                    </div>
                    <div class="alert alert-success" role="alert" id="successMsg" style="display:none">                            
                    </div>

                    <form action="" name="frmrequistion" id="frmrequistion">
                    <?= csrf_field() ?>    
                        <input type="hidden" name="requisition_id" id="requisition_id" value="">
                        <input type="hidden" name="created_by" id="created_by" value=" <?php echo $_SESSION['username'] ?>">
                        <input type="hidden" name="mode" id="mode" value="ADD-INTERACTION">
                        <input type="hidden" name="court_number" id="court_number" value="" >
                        <input type="hidden" name="roleid" id="roleid" value="<?php echo $_SESSION['role_id'];?>">
                        <input type="hidden" name="section" id="section" value="">
                         <input type="hidden" name="token" id="token" value="<?php echo $_SESSION['token']; ?>">
                         <div class="col-sm-12" >  <center>
                            <h4><span id="courtview" style=""></span></h4></center></div>
                            <br>
                       <h5> <span id="remark1"></span></h5>
                       <!--<textarea id="remark1" name="remark1" placeholder="Write something.." style="height:150px;" disabled></textarea>-->
                        <a href="#" id="myAnchor"  target="_blank" style="float:right;display:none">View Attachment</a><br>
                        <!--<label for="current_status">Status</label>-->
                        <?php
                        if($_SESSION['role_id']!=6)
                        {
                        ?>
                        <select id="current_status" name="current_status" class="form-control" required> 
                            <option value="">Select</option>
                            <option value="pending">Pending</option>
                            <option value="attending">Attending</option>
                            <option value="Interaction">Interaction</option>
                            <option value="Sent">Sent</option>
                            <option value="received">Received</option>
                            <option value="closed">Closed</option>
                        </select>
                    <?php }?>

                        <!--<label for="remark">Interaction Remarks</label>-->
                        <textarea id="interaction_remarks" name="interaction_remarks" placeholder="Write something.." style="height:150px;"></textarea>

                        <input type="button" value="Add Intreaction" id="btn_interaction" onclick="addInteractions()" class="btn btn-success" >

                    </form>

                </div>  <br><br><canvas id="donutChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
            </div>



        </div>
        <!-- REQUIRED SCRIPTS -->


        </div>
                </div>
            </div>
        </div>

    </div>

    </section>

        <!-- jQuery -->
        <script src="plugins/jquery/jquery.min.js"></script>
        <!-- Bootstrap -->
        <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
        <!-- ChartJS -->
        <script src="<?php echo base_url();?>/requisition/Chart.min.js"></script>
       
       <script src="<?php echo base_url();?>/requisition/requistion.js">   </script>


        <script>
            
            var roletype = '<?php echo $_SESSION['role_id'] ?>';
            var dataArr =[0,0,0,0,0,0];
            $(document).ready(function () {
             
                $('#btn_interaction').attr('disabled', 'disabled');

                setInterval(function () {
                    var CSRF_TOKEN = 'CSRF_TOKEN';
		            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                    $.ajax({
                        type: 'POST',
                        url: '<?php echo base_url('Library/Requisition/frmusrLogin'); ?>',
                        data: {mode: "getReuqistionStatus",CSRF_TOKEN: CSRF_TOKEN_VALUE},
                        dataType: ' json',
                        async: false,
                        error: function () {
                            console.log("error");
                        },
                        success: function (response) {   
                            updateCSRFToken();                         
                             dataArr = [response.pending,response.attending,response.received,response.Interaction,response.cancel,response.closed,response.Sent];                           
                             var donutChartCanvas = $('#donutChart').get(0).getContext('2d')
                            var donutData = {
                                labels: [
                                    'Pending '+response.pending,
                                    'Attending '+response.attending,
                                    'Received '+response.received,
                                    'Interaction '+response.Interaction,
                                    'Cancel '+response.cancel,
                                    'Closed '+response.closed,
                                    'Sent '+response.Sent,
                                ],
                                
                                
                                datasets: [
                                    {
                                        data: dataArr,

                                        backgroundColor: ['#f56954',  '#f39c12','#d2d6de', '#00c0ef', '#3c8dbc', '#00a65a','#d0f0c0'],
                                    }
                                ]
                            }
                            var donutOptions = {
                                maintainAspectRatio: false,
                                responsive: true,
                                legend: {
                                    position: 'right',
                                    labels:{
                                         boxWidth: 10,
                                         padding: 12
                                         }
                                    },
                                    

                            }
                            //Create pie or douhnut chart
                            // You can switch between pie and douhnut using the method below.
                            new Chart(donutChartCanvas, {
                                type: 'doughnut',
                                data: donutData,
                                options: donutOptions
                            })                              
                        },
                    });
                }, 3000);


                if (roletype == 6)
                {

                    setInterval(function () {
                        var CSRF_TOKEN = 'CSRF_TOKEN';
		                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                        $.ajax({
                            type: 'POST',
                            url: '<?php echo base_url('Library/Requisition/frmusrLogin'); ?>',
                            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE,mode: "ReuqistionAlert"},
                            dataType: ' json',
                            async: false,
                            error: function () {
                                console.log("error");
                            },
                            success: function (response) {
                                updateCSRFToken();
                                if (response.total_pendingCase)
                                {
                                    play();
                                    alert("Total pending case is  " + response.total_pendingCase);
                                    //location.reload();return false;
                                }
                            },
                        });
                    }, 240000);

                }


                setInterval(function () {
                    var CSRF_TOKEN = 'CSRF_TOKEN';
		            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                    $.ajax({
                        type: 'POST',
                        url: '<?php echo base_url('Library/Requisition/frmusrLogin'); ?>',
                        data: {CSRF_TOKEN: CSRF_TOKEN_VALUE,mode: "getAutoRefresh_Admin", 'roletype':<?php echo $_SESSION['role_id'] ?>},
                        dataType: ' json',
                        async: false,
                        error: function () {
                            updateCSRFToken();
                            console.log("error");
                        },
                        success: function (response) {
                            updateCSRFToken();
                            $("#statusData").html(response.html);


                        },
                    });
                }, 2000);
                



            });



            function play() {
                var audio = new Audio(
                        'https://media.geeksforgeeks.org/wp-content/uploads/20190531135120/beep.mp3');
                audio.play();
            }

        </script>
 
