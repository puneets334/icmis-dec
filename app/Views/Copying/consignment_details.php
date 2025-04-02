<?php
if ($response == 'Y') {
?>
    <style>
        #radioBtn .notActive {
            color: #3276b1;
            background-color: #fff;
        }

        /*timelime css*/

        ul.timeline {
            list-style-type: none;
            position: relative;
        }

        ul.timeline:before {
            content: ' ';
            background: #d4d9df;
            display: inline-block;
            position: absolute;
            left: 29px;
            width: 2px;
            height: 100%;
            z-index: 400;
        }

        ul.timeline>li {
            margin: 20px 0;
            padding-left: 60px;
        }

        ul.timeline>li:before {
            content: ' ';
            background: white;
            display: inline-block;
            position: absolute;
            border-radius: 50%;
            border: 3px solid #22c0e8;
            left: 20px;
            width: 20px;
            height: 20px;
            z-index: 400;
        }
    </style>
    <div class="card col-12 mt-2 pl-0 pr-0">
        <div class="card-header bg-info text-white font-weight-bolder">Consignment No. : <?= $cn_no ?>
        </div>
        <div class="card-body">

            <div id="radioBtn" class="btn-group pb-2">
                <a class="btn btn-primary btn-sm active" data-toggle="timeline_table_toggle" data-title="timeline_show">Timeline</a>
                <a class="btn btn-primary btn-sm notActive" data-toggle="timeline_table_toggle" data-title="table_show">Table</a>
            </div>
            <table class="table table-bordered table-striped d-none show_table_data">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Event</th>
                        <th>Office</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                    $track_rpt_arr = array();
                    foreach ($barcode_details as $value) {

                        array_push($track_rpt_arr, array(
                            "DATE" => date('d-M-Y', strtotime($value['event_date'])), "TIME" => date('h:i A', strtotime($value['event_time'])),
                            "EVENT" => $value['event_type'], "OFFICE" => $value['office']
                        ));
                    ?>

                        <tr>
                            <td><?= date('d-M-Y', strtotime($value['event_date'])) ?></td>
                            <td><?= date('h:i A', strtotime($value['event_time'])) ?></td>
                            <td><?= $value['event_type'] ?></td>
                            <td><?= $value['office'] ?></td>
                        </tr>


                    <?php } ?>
                </tbody>
            </table>


            <!--//xxxxx TRACKING START xxxxx-->
            <div class="container  mt-2 mb-2" id="show_tracking">
                <div class="row">
                    <div class="col-md-12 ">

                        <ul class="timeline">
                            <?php
                            foreach ($track_rpt_arr as $val_track_rpt) {
                                $date = $val_track_rpt['DATE'];
                                $time = $val_track_rpt['TIME'];
                                $event = $val_track_rpt['EVENT'];
                                $office = $val_track_rpt['OFFICE'];

                                /*echo  $date . '/ ' . $time . '/ ' . $event . '/ ' . $office ;
                                echo "<br>"; */
                            ?>
                                <li>
                                    <p style="color: blueviolet; "><?= $date . ' ( ' . $time . ' )' ?> </p>
                                    <p><b>Event : </b><?= $event . ' / ' ?> <b>Office : </b><?= $office; ?></p>
                                </li>
                            <?php }  ?>
                        </ul>
                    </div>
                </div>
            </div>
            <!--//xxxxxx TRACKING END xxxxx-->

        </div>
    </div>
<?php
} else {
?>
    <div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong><?php echo $message; ?></strong></div>
<?php
}
?>