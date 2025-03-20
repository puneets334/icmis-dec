<?= view('header') ?>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title"> Add Drop Request</h3>
                            </div>

                            <div class="col-sm-2">
                                <div class="custom_action_menu">
                                    <a href="<?= base_url('Filing/Diary'); ?>"><button class="btn btn-success btn-sm" type="button"><i class="fa fa-plus-circle" aria-hidden="true"></i></button></a>
                                    <a href="<?= base_url('Filing/Diary/search'); ?>"><button class="btn btn-info btn-sm" type="button"><i class="fa fa-search-plus" aria-hidden="true"></i></button></a>
                                    <a href="<?= base_url('Filing/Diary/deletion'); ?>"><button class="btn btn-danger btn-sm" type="button"><i class="fa fa-trash" aria-hidden="true"></i></button></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Main content start -->
                    <div class="col-md-12">
                    <div class="card-body">
                        <h4 align="center">
                            Supreme Court of India
                        </h4>
                        <div style="text-align: center">
                            <h3>Diary No.- <?php echo $diary_number; ?> - <?php echo $diary_year; ?></h3>
                        </div>
                        <table align="center" width="100%" cellpadding="1" cellspacing="1" class="c_vertical_align tbl_border">
                            <tr>
                                <td>CASE TYPE : <strong><?php echo $case_info->short_description; ?></strong></td>
                                <td>CASE NUMBER: <strong><?php echo $fil_no; ?></strong></td>
                                <td>CASE YEAR : <strong><?php echo $fil_dt; ?></strong></td>
                                <td>Bench : <strong><?php echo $bench; ?></strong></td>
                            </tr>
                        </table>

                        <?php if (!empty($party_details)) { ?>
                            <div class="cl_center"><h3>Case Details</h3></div>
                            <table class="table_tr_th_w_clr c_vertical_align" width="100%">
                                <tr>
                                    <td style="width: 15%">Petitioner</td>
                                    <td><?php echo $pet_name; ?></td>
                                </tr>
                                <tr>
                                    <td style="width: 15%">Respondant</td>
                                    <td><?php echo $res_name; ?></td>
                                </tr>
                                <tr>
                                    <td style="width: 15%">Category</td>
                                    <td><?php echo $mul_category; ?></td>
                                </tr>
                                <tr>
                                    <td>Act</td>
                                    <td><?php echo $act_section; ?></td>
                                </tr>
                                <tr>
                                    <td>Provision of Law</td>
                                    <td><?php echo $law; ?></td>
                                </tr>
                                <tr>
                                    <td style="width: 15%">Petitioner Advocate</td>
                                    <td><?php echo $pet_adv; ?></td>
                                </tr>
                                <tr>
                                    <td>Respondant Advocate</td>
                                    <td><?php echo $res_adv; ?></td>
                                </tr>
                                <tr>
                                    <td>Last Order</td>
                                    <td><?php echo $lastorder; ?></td>
                                </tr>
                                <?php if ($c_status == 'P') { ?>
                                    <tr>
                                        <td>Tentative Date</td>
                                        <td><?php echo $tentative_date; ?></td>
                                    </tr>
                                <?php } else { ?>
                                    <tr>
                                        <td>Case Status</td>
                                        <td><?php echo 'Case is Disposed'; ?></td>
                                    </tr>
                                <?php } ?>
                            </table>
                        <?php } else { ?>
                            <div class="cl_center"><b>No Record Found</b></div>
                        <?php } ?>

                        <?php
                         
                        if (!empty($hearing_date_list) || !empty($hearing_last_date_list)) { 
                        ?>
                        <div class="cl_center"><h3>Listing Details</h3></div>
                            <table class="table_tr_th_w_clr c_vertical_align" width="100%">
                                <tr>
                                    <td align='center'><b>CL Date</b></td>
                                    <td><b>Misc./Regular</b></td>
                                    <td><b>Stage</b></td>
                                    <td><b>Purpose</b></td>
                                    <td align='center'><b>Bench</b></td>
                                    <td><b>Remarks</b></td>
                                </tr>
                                <?php foreach($hearing_date_list as $date_list) {
                                        if($date_list['mainhead']=="M")
                                        $t_mainhead="Misc.";
                                        if($date_list['mainhead']=="F")
                                        $t_mainhead="Regular" ;
                                        if($date_list['mainhead']=="L")
                                        $t_mainhead="Lok Adalat" ;
                                        if($date_list['mainhead']=="S")
                                        $t_mainhead="Mediation" ;
                                        $subhead=$date_list['subhead'];
                                        $next_dt=$date_list['next_dt'];
                                        $lo=$date_list['listorder'];
                                    ?>
                                <tr>
                                    <td align='center'><?php echo $date_list['next_date']; ?></td>
                                    <td><?php echo $t_mainhead; ?></td>
                                    <td><?php echo $date_list['t_stage']; ?></td>
                                    <td><?php echo $date_list['purpose']; ?></td>
                                    <td align='center'> <?php echo $date_list['roster_id'] ?></td>
                                    <td></td>
                                </tr>
                                <?php } ?>

                                <?php 
                                    $next_date = '';
                                    foreach($hearing_last_date_list as $last_date_list) {
                                        if($last_date_list['mainhead']=="M")
                                            $t_mainhead1="Misc.";
                                        if($last_date_list['mainhead']=="F")
                                            $t_mainhead1="Regular" ;
                                        if($last_date_list['mainhead']=="L")
                                            $t_mainhead1="Lok Adalat" ;
                                        if($last_date_list['mainhead']=="S")
                                            $t_mainhead1="Mediation" ;
                                    ?>
                                    <tr>
                                        <td align='center'><?php echo $last_date_list['next_date']; ?></td>
                                        <td><?php echo $t_mainhead1 ?></td>
                                        <td><?php echo $last_date_list['t_stage']; ?></td>
                                        <td><?php echo $last_date_list['purpose']; ?></td>
                                        <td align='center'><?php echo $last_date_list['roster_id'] ?></td>
                                        <td></td>
                                    </tr>
                                <?PHP } ?>
                            </table>
                            <?php } ?>

                            <?php if(!empty($get_interlocutary_app)) { ?>
                            <div class="cl_center"><h3>INTERLOCUTARY APPLICATIONS</h3></div>
                            <table class="table_tr_th_w_clr c_vertical_align" width="100%">
                            <?php 

                            $ian_p = "";
                            //print_r($get_interlocutary_app);
                            foreach($get_interlocutary_app as $iancntr => $row_ian) {
                                //print_r($row_ian);
                                //continue; 
                                $iancntr = $iancntr + 1;
                                if ($row_ian["other1"] != "")
                                    $t_part = $row_ian["docdesc"] . " [" . $row_ian["other1"] . "]";
                                else
                                    $t_part = $row_ian["docdesc"];


                                $t_ia = "";
                                if ($row_ian["iastat"] == "P")
                                    $t_ia = "<font color='blue'>" . $row_ian["iastat"] . "</font>";
                                if ($row_ian["iastat"] == "D")
                                    $t_ia = "<font color='red'>" . $row_ian["iastat"] . "</font>";


                                ?>
                                
                                <?php  if ($ian_p == "" and $row_ian["iastat"] == "P") { ?>
                                
                                    <tr>
                                        <td align='center'><b>S.N.</b></td>
                                        <td align='center'><b>Reg.No.</b></td>
                                        <td><b>Particular</b></td><td align='center'><b>Date</b></td>
                                    </tr>
                                <?php } ?>
                                <?php  if ($iancntr == 1) { ?>
                                    
                                        <tr>
                                            <td align='center' width='30px'><b>IA.NO.</b></td>
                                            <td align='center' width='120px'><b>Reg.No.</b></td>
                                            <td><b>Particular</b></td>
                                            <td><b>Filed By</b></td>
                                            <td align='center' width='120px'><b>Date</b></td>
                                            <td align='center' width='70px'><b>Status</b></td>
                                        </tr>
                                <?php } ?>
                                        <tr>
                                            <td align='center'><?php  echo $iancntr; ?></td>
                                            <td align='center'><b><?php echo $row_ian["docnum"] . "/" . $row_ian["docyear"] ; ?></b></td>
                                            <td><?php echo str_replace("XTRA", "", $t_part) ?></td>
                                            <td><?php echo $row_ian["filedby"] . "</td><td align='center'>" . date("d-m-Y", strtotime($row_ian["ent_dt"])) ?></td>
                                            <td align='center'><b><?php echo $t_ia ?></b></td>
                                        </tr>


                                        <?php if ($row_ian["iastat"] == "P") {
                                            $t_iaval = $row_ian["docnum"] . "/" . $row_ian["docyear"] . ",";
                                            if (strpos($row_ian["listedia"], $t_iaval) !== false)
                                                $check = "checked='checked'";
                                            else
                                                $check = "";
                                        ?>
                                            <tr>
                                                <td align='center'>
                                                    <input type='checkbox' name='iachbx" . $iancntr . "' id='iachbx" . $iancntr . "' value='" . $row_ian["docnum"] . "/" . $row_ian["docyear"] . "|#|" . str_replace("XTRA", "", $t_part) . "' onClick='feed_rmrk();' disabled=disabled checked=checked " . $check . ">
                                                </td>
                                                <td align='center'><?php echo $row_ian["docnum"] . "/" . $row_ian["docyear"] ?></td>
                                                <td align='left'><?php echo str_replace("XTRA", "", $t_part) ?></td>
                                                <td><?php echo $row_ian["filedby"]?></td>
                                                <td align='center'><?php echo date("d-m-Y", strtotime($fil_dt)) ?></td>
                                                <td align='center'><font color='blue'><?php echo $row_ian["iastat"] ?></font></td>
                                                
                                            </tr>
                                        <?php } ?>
                            <?php } ?>    
                            </table>


                            <?php if ($ian_p != "") { ?>
                                <br><span style='font-align:left;'><font size=+1 color=blue>If any disposed IA is listed here then disposed it off using IA UPDATE module before proposal updation</font></span>
                            <?php } ?>
                        <?php } ?>    


                            <!--IA END-->
                            <!-- OTHER DOCUMENTS -->
                            <?php if(!empty($get_other_docs)) { ?>        
                            <div class="cl_center"><h3>DOCUMENTS FILED</h3></div>
                            <table class="table_tr_th_w_clr c_vertical_align" width="100%">
                                <tr>
                                    <td align='center' width='30px'><b>S.N.</b></td><td align='center' width='120px'><b>Reg.No.</b></td>
                                    <td><b>Document Type</b></td><td><b>Filed By</b></td>
                                    <td align='center' width='120px'><b>Date</b></td>
                                    <td align='center'><b>Other</b></td>
                                </tr>
                            <?php foreach($get_other_docs as $odcntr => $row_od) { 
                                $odcntr = $odcntr + 1;
                                if (trim($row_od["docdesc"]) == 'OTHER')
                                    $docdesc = $row_od["other1"];
                                else
                                    $docdesc = $row_od["docdesc"];

                                if ($row_od["doccode"] == 7 and $row_od["doccode1"] == 0)
                                    $doc_oth = ' Fees Mode: ' . $row_od["feemode"] . ' For Resp: ' . $row_od["forresp"];
                                else
                                    $doc_oth = '';
                                ?>
                                <tr>
                                    <td align='center'><?php echo $odcntr; ?></td>
                                    <td align='center'><b><?php echo$row_od["docnum"] . "/" . $row_od["docyear"]; ?></b></td>
                                    <td><?php echo $docdesc; ?></td>
                                    <td><?php echo $row_od["filedby"]; ?></td>
                                    <td align='center'><?php echo date("d-m-Y", strtotime($row_od["ent_dt"])) ?></td>
                                    <td align='center'><?php echo $doc_oth; ?></td></tr>
                            <?php } ?>
                            </table>
                            <?php } ?>
                            <!--<input type="hidden" name="sh" id="sh" value="<?php //print $subhead;?>"/>-->
                        </div>
                        </div>
                        <!-- Main content end -->

                </div> <!--end dv_content1-->
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>