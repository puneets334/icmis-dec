
    <?php
    if(!empty($pil_result)) {

//                                echo !empty($column_name)?$column_name:'';die;
//            if((!empty($column_name)) && (!empty($text)))
//            {
//                if($column_name === 'n')
//                {
//                    $heading = " Search By Applicant Name And Text is $text";
//                }elseif ($column_name === 'a')
//                {
//                    $heading = " Search By Address And Text is $text";
//                }elseif ($column_name === 'm')
//                {
//                    $heading = " Search By Mobile And Text is $text";
//                }elseif ($column_name === 'e')
//                {
//                    $heading = " Search By Email And Text is $text";
//                }elseif ($column_name === 'd')
//                {
//                    $heading = " Search By Inward Number And Text is $text";
//                }
//            }
            ?>
            <br>
<!--            <center><h3><b>--><?//= $heading; ?><!--</b></h3></center>-->
            <br><br>

            <div id="query_builder_wrapper" class="dataTables_wrapper dt-bootstrap4 query_builder_wrapper">

                <table id="reportTable1" class="table table-bordered table-striped datatable_report">
                    <!--                                <table id="reportTable1" style="width: 100%" class="table table-striped table-hover">-->
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
                    $i = 1;
                    foreach ($pil_result as $result) {
//                                echo "<pre>";
//                                print_r($result);die;
                        ?>
                        <tr>
                            <td><?= $i++;?></td>
                            <td> <a href="<?=base_url();?>/PIL/PilController/rptPilCompleteData/<?=$result['id']?>" target="_blank">
                                    <?= $result['pil_diary_number']; ?></a></td>
                            <td><?= $result['address_to']; ?></td>
                            <td><?= $result['received_from']; ?><br><?= $result['address']; ?>
                                <?php
                                if (!empty($result['state_name'])) {
                                    echo " ,State: " . $result['state_name'];
                                }
                                if (!empty($result['email'])) {
                                    echo "<br/> Email: " . $result['email'];
                                }
                                if (!empty($result['mobile'])) {
                                    echo "<br/> Mobile: " . $result['mobile'];
                                }
                                ?>
                            </td>
                            <td><?= !empty($result['received_on']) ? date("d-m-Y", strtotime($result['received_on'])) : null ?></td>
                            <td><?= (!empty($result['petition_date']) && $result['petition_date']!=null && $result['petition_date']!='30-11--0001')? date("d-m-Y", strtotime($result['petition_date'])) : null ?> </td>
                            <td>
                                <?php
                                if(!empty($result['action_taken']))
                                {
                                    switch (trim($result['action_taken'])) {
                                        case "L":
                                        {
                                            $actionTakenText = "No Action Required";
                                            break;
                                        }
                                        case "W":
                                        {
                                            if(!empty($result['written_on']))
                                                $result['written_on']=  date("d-m-Y", strtotime($result['written_on']));
                                            else
                                                $result['written_on']=null;
                                            $actionTakenText = "Written Letter to " . $result['written_to'] . " on " . $result['written_on'];

                                            break;
                                        }
                                        case "R":
                                        {
                                            if(!empty($result['return_date']) )
                                                $result['return_date']=date('d-m-Y', strtotime($result['return_date']));
                                            else
                                                $result['return_date']=null;
                                            $actionTakenText = "Letter Returned to Sender on " . $result['return_date'];
                                            break;
                                        }
                                        case "S":
                                        {
                                            if(!empty($result['sent_on']))
                                                $result['sent_on'] = date('d-m-Y', strtotime($result['sent_on']));
                                            else
                                                $result['sent_on'] =null;
                                            $actionTakenText = "Letter Sent To " . $result['sent_to'] . " on " . $result['sent_on'];
                                            break;
                                        }
                                        case "T":
                                        {
                                            if(!empty($result['transfered_on']))
                                                $result['transfered_on']=  date('d-m-Y', strtotime($result['transfered_on']));
                                            else
                                                $result['transfered_on']=null;

                                            $actionTakenText = "Letter Transferred To " . $result['transfered_to']." on ".$result['transfered_on'] ;
                                            break;
                                        }
                                        case "I":
                                        {
                                            $actionTakenText = "Letter Converted To Writ";
                                            break;
                                        }
                                        case "O":
                                        {
                                            $actionTakenText = "Other Remedy";
                                            break;
                                        }
                                        default:
                                        {
                                            $actionTakenText = "UNDER PROCESS";
                                            break;
                                        }
                                    }
                                    echo $actionTakenText;
                                }else{
                                    $actionTakenText = "UNDER PROCESS";
                                    echo $actionTakenText;
                                }

                                ?>
                            </td>
                            <td><?= $result['username'].'('.$result['empid'].')'?>
                                <br/> At: <?= date('d-m-Y h:i:s A', strtotime($result['updated_on'])) ?> </td>
                        </tr>

                        <?php
                    }
                    ?>
                    </tbody>
                </table>
            </div>

            <?php


    }else{?>
        <center><h4><span style="color: red; font-weight: bold">No Record Found!!</span></h4></center>
    <?php }

    ?>
    <script>
        $(function () {
            $(".datatable_report").DataTable({
                "responsive": true, "lengthChange": false, "autoWidth": false,
                "buttons": ["copy", "csv", "excel",{extend: 'pdfHtml5',orientation: 'landscape',pageSize: 'LEGAL' },
                    { extend: 'colvis',text: 'Show/Hide'}],"bProcessing": true,"extend": 'colvis',"text": 'Show/Hide'
            }).buttons().container().appendTo('.query_builder_wrapper .col-md-6:eq(0)');

        });
    </script>