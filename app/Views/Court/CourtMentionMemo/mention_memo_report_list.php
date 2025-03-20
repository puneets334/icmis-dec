<?php if (is_array($mentioningReports) && !empty($mentioningReports)) {
                                            if ($app_name == 'DecidedDatewise') {
                                                /*foreach ($mentioningReports as $result) {
                                                     $forDecidedDate = $result['date_for_decided'];
                                                }*/
                                            ?>

                                                <div id="printable" class="table-responsive">
                                                    <h3 style="text-align: center;"> Decided Date Wise Listed</h3>
                                                    <!--<h3 style="text-align: center;"> Mentiong Matter Listed for Date : <strong>[<?//= $forDecidedDate ?>]</strong> As on <?php echo date("d-m-Y"); ?></h3>-->
                                                    <table id="example1" class="ttable table-striped custom-table mt-2">
                                                        <thead>
                                                            <tr>
                                                                <th>S.No.</th>
                                                                <th>Case No</th>
                                                                <th>Entry Date</th>
                                                                <th>Decided Date</th>
                                                                <th>Court No.</th>
                                                                <th>Item No.</th>
                                                                <th>Remarks</th>
                                                                <th>Updated By</th>
                                                                <th>Mentioning Type</th>
                                                                <th>Updated At</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                                $s_no = 1;
                                                                foreach ($mentioningReports as $result) {
                                                            ?>
                                                                <tr>
                                                                    <td><?php echo $s_no; ?></td>
                                                                    <td><?php echo $result['reg_no_display']; ?></td>
                                                                    <td><?php echo $result['diary_no']; ?> / <?php echo $result['diary_year']; ?> # <?php echo (!empty($result['diary_date'])) ?  date('d-m-Y', strtotime($result['diary_date'])) : ''; ?></td>
                                                                    <td><?php echo $result['pet_name']; ?> <strong> Vs.</strong><?php echo $result['res_name']; ?></td>
                                                                    <td><?php echo $result['date_on_decided']; ?></td>
                                                                    <td><?php echo $result['date_for_decided']; ?></td>
                                                                    <td><?php echo $result['for_court']; ?></td>
                                                                    <td><?php echo $result['spl_remark']; ?></td>
                                                                    <td><?php echo $result['user_id']; ?></td>
                                                                    <td><?php echo $result['update_time']; ?></td>
                                                                </tr>
                                                            <?php
                                                                $s_no++;
                                                            } //End Of For Each
                                                            ?>
                                                        </tbody>
                                                    </table>

                                                <?php
                                            } else if ($app_name == 'OnDatewise') {
                                                // var_dump($mentioningReports);
                                                // foreach ($mentioningReports as $result)
                                                // {
                                                //     $for_OnDate=$result['date_of_entry'];

                                                // }
                                                ?>

                                                    <h3 style="text-align: center;"> <!-- Mentioning Date Wise Listed -->
                                                          Mentioning Matter as on Date:  <?php echo date("d-m-Y",strtotime($dateForDecided)); ?>  
                                                    </h3>

                                                    <table id="example2" class="table table-bordered table-striped mt-2">
                                                        <thead>
                                                            <tr>
                                                                <th>S.No.</th>
                                                                <th>Case No</th>
                                                                <th>Entry Date</th>
                                                                <th>Decided Date</th>
                                                                <th>Court No.</th>
                                                                <th>Item No.</th>
                                                                <th>Remarks</th>
                                                                <th>Updated By</th>
                                                                <th>Mentioning Type</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $s_no = 1;
                                                            foreach ($mentioningReports as $result) {
                                                            ?>
                                                                <tr>
                                                                    <td><?php if ($result['main_connected'] == 'M') {
                                                                            echo $s_no;
                                                                            $s_no++;
                                                                        } else {
                                                                            echo "<font color='red'>Connected</font>";
                                                                        } ?></td>
                                                                    <td>
                                                                        <strong><?php echo $result['reg_no_display']; ?></strong><br>
                                                                        Diary No:<?php echo $result['diary_no']; ?> / <?php echo $result['diary_year']; ?> # <?php echo (!empty($result['diary_date'])) ?  date('d-m-Y', strtotime($result['diary_date'])) : ''; ?><br>
                                                                        <?php echo $result['pet_name']; ?> <strong> Vs.</strong><?php echo $result['res_name']; ?><br>
                                                                        <span style="color: darkolivegreen">Section: <strong><?php echo $result['section']; ?></strong></span><br>
                                                                        <span style="color: midnightblue">Stage: <strong><?php echo $result['stagename']; ?></strong></span>
                                                                    </td>
                                                                    <td><?php echo $result['update_time']; ?></td>
                                                                    <td><?php echo date('d-m-Y', strtotime($result['date_for_decided'])); ?></td>
                                                                    <td><?php echo $result['courtno']; ?></td>
                                                                    <td><?php echo $result['m_brd_slno']; ?></td>
                                                                    <td><?php echo $result['spl_remark']; ?></td>
                                                                    <td>
                                                                        <?php echo $result['entryBy']; ?>(
                                                                        <?php echo $result['user_id']; ?>)
                                                                    </td>
                                                                    <td><?php echo isset($result['mentiontype']) ? $result['mentiontype'] :'';?></td>

                                                                </tr>
                                                            <?php

                                                            }   //for each
                                                            ?>
                                                        </tbody>
                                                    </table>
                                            <?php
                                            }
                                        }else{
                                            echo '<center style="color:red;">No data Found</center>';
                                        }
                                            ?>