
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

        .table thead th,
        .table th {
            width: 50%;
        }
        .basic_heading {
            text-align: center;
            color: #31B0D5;
        }
    </style>

    <?php $short_description_details='';
    if (!empty($flag) && $flag=='D'){
        $petitioner='Petitioner';
        $respondent='Respondent';
        $flag_value='Diary';
      }else{ $flag_value='Caveat';
        $petitioner='Caveator';
        $respondent='Caveatee';
    }

                        $caveat_details= session()->get('caveat_details'); $caveat_no=$caveat_year='';
                        //echo '<pre>';print_r($caveat_details);
                        if (!empty($caveat_details)){
                            $caveat_no=substr($caveat_details['caveat_no'], 0, -4);
                            $caveat_year=substr($caveat_details['caveat_no'],-4);
                        }else{
                            if (!empty($param)){
                                $caveat_no=trim($param['caveat_number']);
                                $caveat_year=trim($param['caveat_year']);
                            }
                        }
                        $caveat_number=$caveat_no.$caveat_year;

    if (!empty($mainCaveat)){ $caveat_rec_dt=$mainCaveat['diary_no_rec_date']; ?>
                            <div>
                                <div style="text-align: center">
                                    <b><span><?php echo $mainCaveat['pet_name'];?></span></b>
                                    <b><span style="color: red">Vs</span></b>
                                    <b><span><?php echo $mainCaveat['res_name'];?></span></b>
                                </div>
                                <div style="text-align: center;margin-top: 10px">
                                    <?=$flag_value;?> Receiving Date <span style="color: black"><?php echo date('d-m-Y',strtotime($mainCaveat['diary_no_rec_date'])); ?></span>
                                    Status- <?php
                                    if($mainCaveat['no_of_days']>90){?>
                                        <span style="color:red"><?php echo "Expired";?></span> <?php
                                    }else { ?>
                                        <span style="color:green"><?php echo "Active";?></span> <?php
                                    }
                                    ?>
                                </div>
                                <input type="hidden" name="hd_rec_date" id="hd_rec_date" value="<?php echo $mainCaveat['diary_no_rec_date']; ?>"/>
                            </div>

                            <br/>
                            <div class="row">
                                <div class="col-12">
                                    <h4 class="basic_heading">1. Similarities based on State, Bench, Case No. and Judgement Date </h4>
                                    <div class="similarity">
                                        <table id="similarity" class="table table-bordered table-striped table-responsive">
                                            <thead>
                                            <tr>
                                                <th style="width: 5%;">S.No. </th>
                                                <th style="width: 5%;"> Link </th>
                                                <th>Diary No./<br/>Receiving Date/<br/>Disposed/AfterNotice/Section</th>
                                                <th> Registration No.</th>
                                                <th> Petitioner<br/>Vs<br/>Respondent</th>
                                                <th>From Court</th>
                                                <th> State</th>
                                                <th>Bench</th>
                                                <th>Case No.</th>
                                                <th> Judgement Date</th>
                                                <th style="width: 20%;">Linked with Caveat and Date</th>
                                                <th>Status</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php $s_no=1; $chk_status=0;$rep_date_diff=0;$diary_no='';
                                            foreach ($caveatSBCJ as $row ){
                                                if($row['lct_casetype']==50) {
                                                    $row['type_sname']= "WNN";
                                                }
                                                if($row['lct_casetype']==51){
                                                    $row['type_sname']= "ARN";
                                                }
                                                $diary_no=(!empty($row['diary_no']) && $row['diary_no']!=null) ? substr($row['diary_no'],0,-4).'-'.  substr($row['diary_no'],-4) : '';
                                                $r_main=array();
                                                if (!empty($row['sub_details'])) {
                                                    $r_main = $row['sub_details'];
                                                    if (!empty($row['casetype_details'])) {
                                                        $res_case_type_c = $row['casetype_details']['short_description'];
                                                        $short_description_details = $res_case_type_c . '-' . intval(substr($r_main['active_fil_no'], 3)) . '-' . $r_main['active_fil_dt'];
                                                    }
                                                    if (strtotime($r_main['diary_no_rec_date']) >= strtotime($caveat_rec_dt)) {
                                                        $date1 = date_create($r_main['diary_no_rec_date']);
                                                        $date2 = date_create($caveat_rec_dt);
                                                        $diff = date_diff($date2, $date1);
                                                        $date_diff = $diff->format("%R%a days");
                                                        $rep_date_diff = intval(str_replace('+', '', $date_diff));
                                                        if ($rep_date_diff <= 90) {
                                                            $chk_status = 1;
                                                        }
                                                    } else {
                                                        $chk_status = 1;
                                                    }
                                                }
                                                ?>
                                                <tr>
                                                    <td><?=$s_no;?></td>
                                                    <td> <?php if($chk_status==1) { ?>
                                                            <input type="button" name="btnlink_<?php echo $s_no; ?>" id="btnlink_<?php echo $s_no; ?>" value="Link" class="cl_link btn btn-primary"/>
                                                        <?php } ?>
                                                        <input type="hidden" name="hd_caveat_rec_dt<?php echo $s_no; ?>" id="hd_caveat_rec_dt<?php echo $s_no; ?>" value="<?php echo $r_main['diary_no_rec_date'];  ?>"/>
                                                    </td>
                                                    <td>
                                                        <span id="sp_diary_no<?php echo $s_no; ?>" class="cl_c_diary"><?=$diary_no;?></span>
                                                        <input type="hidden" name="hd_caveat_no<?php echo $s_no; ?>" id="hd_caveat_no<?php echo $s_no; ?>" value="<?php echo $row['diary_no']; ?>"/>  <br/>
                                                        <span style="color: black" id="sp_diary_no<?php echo $s_no; ?>"><?=!empty($r_main['diary_no_rec_date']) ? date('d-m-Y',strtotime($r_main['diary_no_rec_date'])).'<br/>': '';?></span>
                                                        <?php  if($r_main['c_status']=='D'){
                                                            echo "<b><font color='red'>Disposed</font></b>";
                                                        }else{
                                                            echo "Pending";
                                                            if($r_main['r_head']!=null && $r_main['r_head']!=''){ echo "<br><b><font color='#006400'>After Notice</font></b>"; }
                                                        }
                                                        ?>
                                                        <br/><?php if($r_main['da_section']=='') echo "<font color='blue'>".$r_main['sectionname']."</font>";
                                                        else echo "<font color='blue'>".$r_main['da_section']."</font>";?>
                                                    </td>
                                                    <td><?=$short_description_details;?> </td>
                                                    <td><?=!empty($r_main) ? $r_main['pet_name'].'<br/>Vs<br/>'.$r_main['res_name'] : ''; ?> </td>
                                                    <td><span id="sp_court_name<?php echo $s_no; ?>"><?php echo $row['court_name']; ?></span> </td>
                                                    <td><span id="sp_Name<?php echo $s_no; ?>"><?php  echo $row['name'];?></span> </td>
                                                    <td><span id="sp_agency_name<?php echo $s_no; ?>"><?php echo $row['agency_name'];?></span> </td>
                                                    <td><span id="sp_case_name<?php echo $s_no; ?>"><?php echo $row['type_sname'].'-'.$row['lct_caseno'].'-'.$row['lct_caseyear'];?></span> </td>
                                                    <td><?=!empty($row['lct_dec_dt']) ? date('d-m-Y',strtotime($row['lct_dec_dt'])) : '';?> </td>
                                                    <td>
                                                        <?php if (!empty($row['caveat_diary_matching'])){
                                                            foreach ($row['caveat_diary_matching'] as $row1){
                                                                $caveat_no=(!empty($row1['caveat_no']) && $row1['caveat_no']!=null) ? substr($row1['caveat_no'],0,-4).'-'.  substr($row1['caveat_no'],-4) : '';
                                                                ?>
                                                                <table style="width: 100%;">
                                                                    <tr>
                                                                        <td><?=$caveat_no;?></td>
                                                                        <td>
                                                                            <?php if($row1['caveat_no']==$caveat_number){?>
                                                                                <span id="sp_cav_diary_lnl_dt<?php echo $s_no; ?>"><?php echo $row1['link_dt']; ?></span>
                                                                            <?php } else{ echo $row1['link_dt'];
                                                                            }  ?>
                                                                        </td>
                                                                        <td>
                                                                            <?php if($row1['caveat_no']==$caveat_number) {?>
                                                                                <input type="hidden" name="hd_linked_no<?php echo $s_no; ?>" id="hd_linked_no<?php echo $s_no; ?>" value="<?php echo $row1['caveat_no']; ?>"/>
                                                                                <input type="button" name="hd_unlink<?php echo $s_no; ?>" id="hd_unlink<?php echo $s_no; ?>" value="Unlink" class="cl_unlink btn btn-primary"/>
                                                                            <?php } ?>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            <?php } } ?>
                                                    </td>
                                                    <td><?php
                                                        if($rep_date_diff<=90 && $chk_status==1){ ?>
                                                            <span style="color: green">Active</span>
                                                        <?php }else{ ?>
                                                            <span style="color: red">Expired</span>
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                            <?php $s_no++; } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!--similarity-1 end-->

                            <!--similarity-2 -->
                            <div class="row">
                                <div class="col-12">
                                    <h4 class="basic_heading">2. Similarities based on  State, Bench, Case No.</h4>
                                    <div class="similarity2">
                                        <table id="similarity2" class="table table-bordered table-striped table-responsive">
                                            <thead>
                                            <tr>
                                                <th style="width: 5%;">S.No. </th>
                                                <th style="width: 5%;"> Link </th>
                                                <th style="width: 10%;">Diary No. /<br/>Receiving Date</th>
                                                <th style="width: 10%;"> Petitioner<br/>Vs<br/>Respondent</th>
                                                <th style="width: 10%;">From Court</th>
                                                <th style="width: 10%;">State</th>
                                                <th style="width: 10%;">Bench</th>
                                                <th style="width: 10%;">Case No.</th>
                                                <th style="width: 10%;"> Judgement Date</th>
                                                <th style="width: 20%;">Linked with Caveat and Date</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php //$s_no=1;
                                            $chk_status=0;$rep_date_diff=0;$diary_no='';
                                            foreach ($caveatSBC as $row ){
                                                if($row['lct_casetype']==50) {
                                                    $row['type_sname']= "WNN";
                                                }
                                                if($row['lct_casetype']==51){
                                                    $row['type_sname']= "ARN";
                                                }
                                                $diary_no=(!empty($row['diary_no']) && $row['diary_no']!=null) ? substr($row['diary_no'],0,-4).'-'.  substr($row['diary_no'],-4) : '';
                                                $r_main=array();
                                                if (!empty($row['sub_details'])) {
                                                    $r_main = $row['sub_details'];
                                                    $res_case_type_c=$row['casetype_details']['short_description'];
                                                    $short_description_details=$res_case_type_c.'-'.intval(substr($r_main['active_fil_no'],3)).'-'.$r_main['active_fil_dt'];
                                                    if (strtotime($r_main['diary_no_rec_date']) >= strtotime($caveat_rec_dt)) {
                                                        $date1 = date_create($r_main['diary_no_rec_date']);
                                                        $date2 = date_create($caveat_rec_dt);
                                                        $diff = date_diff($date2, $date1);
                                                        $date_diff = $diff->format("%R%a days");
                                                        $rep_date_diff = intval(str_replace('+', '', $date_diff));
                                                        if ($rep_date_diff <= 90) {
                                                            $chk_status = 1;
                                                        }
                                                    } else {
                                                        $chk_status = 1;
                                                    }
                                                }
                                                ?>
                                                <tr>
                                                    <td><?=$s_no;?></td>
                                                    <td>
                                                        <?php /*if($chk_status==1) { */?><!--
                                                            <input type="button" name="btnlink_<?php /*echo $s_no; */?>" id="btnlink_<?php /*echo $s_no; */?>" value="Link" class="cl_link"/>
                                                        <?php /*} */?>
                                                        <input type="hidden" name="hd_caveat_rec_dt<?php /*echo $s_no; */?>" id="hd_caveat_rec_dt<?php /*echo $s_no; */?>" value="<?php /*echo $r_main['diary_no_rec_date'];  */?>"/>-->
                                                    </td>

                                                    <td><?=$diary_no;?>
                                                        <input type="hidden" name="hd_caveat_no<?php echo $s_no; ?>" id="hd_caveat_no<?php echo $s_no; ?>" value="<?php echo $row['diary_no']; ?>"/>  <br/>
                                                        <span style="color: black" id="sp_diary_no<?php echo $s_no; ?>"><?=!empty($r_main['diary_no_rec_date']) ? date('d-m-Y',strtotime($r_main['diary_no_rec_date'])).'<br/>': '';?></span>
                                                        <?php  if($r_main['c_status']=='D') echo "<b><font color='red'>Disposed</font></b>";
                                                        else{
                                                            echo "Pending";
                                                            if($r_main['r_head']!=null && $r_main['r_head']!=''){
                                                                echo "<br><b><font color='#006400'>After Notice</font></b>";
                                                            }
                                                        }
                                                        ?>
                                                        <br/><?php if($r_main['da_section']=='') echo "<font color='blue'>".$r_main['sectionname']."</font>";
                                                        else echo "<font color='blue'>".$r_main['da_section']."</font>";?>
                                                    </td>

                                                    <td><?=!empty($r_main) ? $r_main['pet_name'].'<br/>Vs<br/>'.$r_main['res_name'] : ''; ?> </td>

                                                    <td><span id="sp_court_name<?php echo $s_no; ?>"><?php echo $row['court_name']; ?></span> </td>
                                                    <td><span id="sp_Name<?php echo $s_no; ?>"><?php echo $row['name'];?></span> </td>
                                                    <td> <span id="sp_agency_name<?php echo $s_no; ?>"><?php echo $row['agency_name']; ?></span> </td>
                                                    <td><span id="sp_case_name<?php echo $s_no; ?>"><?php echo $row['type_sname'].'-'.$row['lct_caseno'].'-'.$row['lct_caseyear'];?></span> </td>
                                                    <td><span id="sp_lct_dec_dt<?php echo $s_no; ?>"> <?=!empty($row['lct_dec_dt']) ? date('d-m-Y',strtotime($row['lct_dec_dt'])) : '';?></span> </td>
                                                    <td>
                                                        <?php if (!empty($row['caveat_diary_matching'])){
                                                            foreach ($row['caveat_diary_matching'] as $row1){
                                                                $caveat_no=(!empty($row1['caveat_no']) && $row1['caveat_no']!=null) ? substr($row1['caveat_no'],0,-4).'-'.  substr($row1['caveat_no'],-4) : '';
                                                                ?>
                                                                <table style="width: 100%;">
                                                                    <tr>
                                                                        <td><?=$caveat_no;?></td>
                                                                        <td>
                                                                            <?php if($row1['caveat_no']==$caveat_number){?>
                                                                                <span id="sp_cav_diary_lnl_dt<?php echo $s_no; ?>"><?php echo $row1['link_dt']; ?></span>
                                                                            <?php } else{ echo $row1['link_dt'];
                                                                            }  ?>
                                                                        </td>
                                                                        <td>
                                                                            <?php if($row1['caveat_no']==$caveat_number) {?>
                                                                                <input type="hidden" name="hd_linked_no<?php echo $s_no; ?>" id="hd_linked_no<?php echo $s_no; ?>" value="<?php echo $row1['caveat_no']; ?>"/>
                                                                                <input type="button" name="hd_unlink<?php echo $s_no; ?>" id="hd_unlink<?php echo $s_no; ?>" value="Unlink" class="cl_unlink btn btn-primary"/>
                                                                            <?php } ?>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            <?php } } ?>
                                                    </td>
                                                </tr>
                                            <?php $s_no++; } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!--similarity-2 end-->

                            <!--similarity-3 -->
                            <div class="row">
                                <div class="col-12">
                                    <h4 class="basic_heading">3. Similarities based on  State, Bench, Judgement Date</h4>
                                    <div class="similarity3">
                                        <table id="similarity3" class="table table-bordered table-striped table-responsive">
                                            <thead>
                                            <tr>
                                                <th style="width: 5%;">S.No. </th>
                                                <th style="width: 5%;"> Link </th>
                                                <th style="width: 10%;">Diary No.</th>
                                                <th style="width: 10%;"> Petitioner<br/>Vs<br/>Respondent</th>
                                                <th style="width: 10%;">From Court</th>
                                                <th style="width: 10%;">State</th>
                                                <th style="width: 10%;">Bench</th>
                                                <th style="width: 10%;">Case No.</th>
                                                <th style="width: 10%;"> Judgement Date</th>
                                                <th style="width: 10%;">Linked with Caveat and Date</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php //$s_no=1;
                                            $chk_status=0;$rep_date_diff=0;$diary_no='';
                                            foreach ($caveatSBJ as $row ){
                                                if($row['lct_casetype']==50) {
                                                    $row['type_sname']= "WNN";
                                                }
                                                if($row['lct_casetype']==51){
                                                    $row['type_sname']= "ARN";
                                                }
                                                $diary_no=(!empty($row['diary_no']) && $row['diary_no']!=null) ? substr($row['diary_no'],0,-4).'-'.  substr($row['diary_no'],-4) : '';
                                                $r_main=array();
                                                if (!empty($row['sub_details'])) {
                                                    $r_main = $row['sub_details'];
                                                    if (strtotime($r_main['diary_no_rec_date']) >= strtotime($caveat_rec_dt)) {
                                                        $date1 = date_create($r_main['diary_no_rec_date']);
                                                        $date2 = date_create($caveat_rec_dt);
                                                        $diff = date_diff($date2, $date1);
                                                        $date_diff = $diff->format("%R%a days");
                                                        $rep_date_diff = intval(str_replace('+', '', $date_diff));
                                                        if ($rep_date_diff <= 90) {
                                                            $chk_status = 1;
                                                        }
                                                    } else {
                                                        $chk_status = 1;
                                                    }
                                                }
                                                ?>
                                                <tr>
                                                    <td><?=$s_no;?></td>
                                                    <td><?php $chk_status=0;?> </td>
                                                    <td><?php echo substr($row['diary_no'],0,-4).'-'.  substr($row['diary_no'],-4); ?>
                                                        <input type="hidden" name="hd_caveat_no<?php echo $s_no; ?>" id="hd_caveat_no<?php echo $s_no; ?>" value="<?php echo $row['diary_no']; ?>"/>  <br/>
                                                        <span style="color: black" id="sp_diary_no<?php echo $s_no; ?>"><?=!empty($r_main['diary_no_rec_date']) ? date('d-m-Y',strtotime($r_main['diary_no_rec_date'])).'<br/>': '';?></span>
                                                         <?php  if(isset($r_main['c_status']) && $r_main['c_status']=='D'){ echo "<b><font color='red'>Disposed</font></b>"; }else{
                                                                echo "Pending";
                                                                if(isset($r_main['r_head']) && $r_main['r_head']!=null && $r_main['r_head']!=''){
                                                                    echo "<br><b><font color='#006400'>After Notice</font></b>";
                                                                }   } ?>
                                                        <br/>
                                                        <?php 
                                                        if(isset($r_main['da_section'])) {
                                                            if($r_main['da_section']=='') echo "<font color='blue'>".$r_main['sectionname']."</font>";
                                                            else echo "<font color='blue'>".$r_main['da_section']."</font>";
                                                        }?>

                                                    </td>
                                                    <td><?=!empty($r_main) ? $r_main['pet_name'].'<br/>Vs<br/>'.$r_main['res_name'] : ''; ?> </td>
                                                    <td><span id="sp_court_name<?php echo $s_no; ?>"><?php echo $row['court_name']; ?></span> </td>
                                                    <td><span id="sp_Name<?php echo $s_no; ?>"><?php echo $row['name']; ?></span> </td>
                                                    <td><span id="sp_agency_name<?php echo $s_no; ?>"><?php echo $row['agency_name']; ?></span> </td>
                                                    <td><?php echo $row['type_sname'].'-'.$row['lct_caseno'].'-'.$row['lct_caseyear'];?> </td>
                                                    <td><span id="sp_lct_dec_dt<?php echo $s_no; ?>"><?=!empty($row['lct_dec_dt']) ? date('d-m-Y',strtotime($row['lct_dec_dt'])) : '';?></span> </td>
                                                    <td>
                                                        <?php if (!empty($row['caveat_diary_matching'])){
                                                            foreach ($row['caveat_diary_matching'] as $row1){
                                                                $caveat_no=(!empty($row1['caveat_no']) && $row1['caveat_no']!=null) ? substr($row1['caveat_no'],0,-4).'-'.  substr($row1['caveat_no'],-4) : '';
                                                                ?>
                                                                <table>
                                                                    <tr>
                                                                        <td><?=$caveat_no;?></td>
                                                                        <td>
                                                                            <?php if($row1['caveat_no']==$caveat_number){?>
                                                                                <span id="sp_cav_diary_lnl_dt<?php echo $s_no; ?>"><?php echo $row1['link_dt']; ?></span>
                                                                            <?php } else{ echo $row1['link_dt'];
                                                                            }  ?>
                                                                        </td>
                                                                        <td>
                                                                            <?php if($row1['caveat_no']==$caveat_number) {?>
                                                                                <input type="hidden" name="hd_linked_no<?php echo $s_no; ?>" id="hd_linked_no<?php echo $s_no; ?>" value="<?php echo $row1['caveat_no']; ?>"/>
                                                                                <input type="button" name="hd_unlink<?php echo $s_no; ?>" id="hd_unlink<?php echo $s_no; ?>" value="Unlink" class="cl_unlink btn btn-primary"/>
                                                                            <?php } ?>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            <?php } } ?>
                                                    </td>
                                                </tr>
                                                <?php $s_no++; } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!--similarity-3 end-->

                            <!--similarity-4 -->
                            <div class="row">
                                <div class="col-12">
                                    <h4 class="basic_heading">4.Similarities based on  State, Caveator and Caveatee</h4>
                                    <div class="similarity4">
                                        <table id="similarity4" class="table table-bordered table-striped table-responsive">
                                            <thead>
                                            <tr>
                                                <th style="width: 5%;">S.No. </th>
                                                <th style="width: 5%;"> Link </th>
                                                <th style="width: 10%;">Diary No.</th>
                                                <th style="width: 10%;"> Petitioner<br/>Vs<br/>Respondent</th>
                                                <th style="width: 10%;">State</th>
                                                <th style="width: 10%;">Linked with Caveat and Date</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php //$s_no=1;
                                            $chk_status=0;$rep_date_diff=0;$diary_no='';
                                            foreach ($caveatSCC as $row ){
                                                $diary_no=(!empty($row['diary_no']) && $row['diary_no']!=null) ? substr($row['diary_no'],0,-4).'-'.  substr($row['diary_no'],-4) : '';
                                                $r_main=array();
                                                if (!empty($row['sub_details'])) {
                                                    $r_main = $row['sub_details'];
                                                    if (strtotime($r_main['diary_no_rec_date']) >= strtotime($caveat_rec_dt)) {
                                                        $date1 = date_create($r_main['diary_no_rec_date']);
                                                        $date2 = date_create($caveat_rec_dt);
                                                        $diff = date_diff($date2, $date1);
                                                        $date_diff = $diff->format("%R%a days");
                                                        $rep_date_diff = intval(str_replace('+', '', $date_diff));
                                                        if ($rep_date_diff <= 90) {
                                                            $chk_status = 1;
                                                        }
                                                    } else {
                                                        $chk_status = 1;
                                                    }
                                                }
                                                ?>
                                                <tr>
                                                    <td><?=$s_no;?></td>
                                                    <td><?php $chk_status=0;?>
                                                        <input type="button" name="btnlink_<?php echo $s_no; ?>" id="btnlink_<?php echo $s_no; ?>" value="Link" class="cl_link btn btn-primary"/>
                                                        <input type="hidden" name="hd_caveat_rec_dt<?php echo $s_no; ?>" id="hd_caveat_rec_dt<?php echo $s_no; ?>" value="<?php echo $r_main['diary_no_rec_date'];  ?>"/>
                                                    </td>
                                                    <td> <?=(!empty($row['diary_no']) && $row['diary_no'] !=null) ? substr($row['diary_no'],0,-4).'-'.  substr($row['diary_no'],-4) : ''; ?>
                                                        <input type="hidden" name="hd_caveat_no<?php echo $s_no; ?>" id="hd_caveat_no<?php echo $s_no; ?>" value="<?php echo $row['diary_no']; ?>"/> </td>
                                                    <td><?=(!empty($row['pet_name']) && !empty($row['res_name'])) ? $row['pet_name'].'<br/>Vs<br/>'.$row['res_name'] : $row['caveat_res_name'].'<br/>Vs<br/>'.$row['caveat_res_name'] ;?>  <!--echo $row['pet_name'].'<br/>Vs<br/>'.$row['res_name'];--> </td>
                                                    <td> <?php echo $row['name'];?> </td>
                                                    <td>
                                                        <?php if (!empty($row['caveat_diary_matching'])){
                                                            foreach ($row['caveat_diary_matching'] as $row1){
                                                                $caveat_no=(!empty($row1['caveat_no']) && $row1['caveat_no']!=null) ? substr($row1['caveat_no'],0,-4).'-'.  substr($row1['caveat_no'],-4) : '';
                                                                ?>
                                                                <table>
                                                                    <tr>
                                                                        <td><?=$caveat_no;?></td>
                                                                        <td>
                                                                            <?php if($row1['caveat_no']==$caveat_number){?>
                                                                                <span id="sp_cav_diary_lnl_dt<?php echo $s_no; ?>"><?php echo $row1['link_dt']; ?></span>
                                                                            <?php } else{ echo $row1['link_dt'];
                                                                            }  ?>
                                                                        </td>
                                                                        <td>
                                                                            <?php if($row1['caveat_no']==$caveat_number) {?>
                                                                                <input type="hidden" name="hd_linked_no<?php echo $s_no; ?>" id="hd_linked_no<?php echo $s_no; ?>" value="<?php echo $row1['caveat_no']; ?>"/>
                                                                                <input type="button" name="hd_unlink<?php echo $s_no; ?>" id="hd_unlink<?php echo $s_no; ?>" value="Unlink" class="cl_unlink btn btn-primary"/>
                                                                            <?php } ?>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            <?php } } ?>
                                                    </td>
                                                </tr>
                                                <?php $s_no++; } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!--similarity-4 end-->

                            <!--similarity-5 -->
                            <div class="row">
                                <div class="col-12">
                                    <h4 class="basic_heading">5. Similarities based on  Arbitration Ref. no., Arbitration date and Arbitrator</h4>
                                    <div class="similarity5">
                                        <table id="similarity5" class="table table-bordered table-striped table-responsive">
                                            <thead>
                                            <tr>
                                                <th style="width: 5%;">S.No. </th>
                                                <th style="width: 5%;"> Link </th>
                                                <th style="width: 10%;">Diary No./<br/>Receiving Date</th>
                                                <th style="width: 10%;"> Registration No.</th>
                                                <th style="width: 10%;"> Petitioner<br/>Vs<br/>Respondent</th>
                                                <th style="width: 10%;">From Court</th>
                                                <th style="width: 10%;">State</th>
                                                <th style="width: 10%;">Bench</th>
                                                <th style="width: 10%;">Case No.</th>
                                                <th style="width: 10%;"> Judgement Date</th>
                                                <th style="width: 10%;"> Judge Name</th>
                                                <th style="width: 20%;">Linked with Caveat and Date</th>
                                                <th style="width: 10%;">Status</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php //$s_no=1;
                                            $chk_status=0;$rep_date_diff=0;$diary_no='';
                                            foreach ($arbitration as $row ){
                                                if($row['lct_casetype']==50) {
                                                    $row['type_sname']= "WNN";
                                                }
                                                if($row['lct_casetype']==51){
                                                    $row['type_sname']= "ARN";
                                                }
                                                $diary_no=(!empty($row['diary_no']) && $row['diary_no']!=null) ? substr($row['diary_no'],0,-4).'-'.  substr($row['diary_no'],-4) : '';
                                                $r_main=array();
                                                if (!empty($row['sub_details'])) {
                                                    $r_main = $row['sub_details'];
                                                    $res_case_type_c=$row['casetype_details']['short_description'];
                                                    $short_description_details=$res_case_type_c.'-'.intval(substr($r_main['active_fil_no'],3)).'-'.$r_main['active_fil_dt'];
                                                    if (strtotime($r_main['diary_no_rec_date']) >= strtotime($caveat_rec_dt)) {
                                                        $date1 = date_create($r_main['diary_no_rec_date']);
                                                        $date2 = date_create($caveat_rec_dt);
                                                        $diff = date_diff($date2, $date1);
                                                        $date_diff = $diff->format("%R%a days");
                                                        $rep_date_diff = intval(str_replace('+', '', $date_diff));
                                                        if ($rep_date_diff <= 90) {
                                                            $chk_status = 1;
                                                        }
                                                    } else {
                                                        $chk_status = 1;
                                                    }
                                                }
                                                ?>
                                                <tr>
                                                    <td><?=$s_no;?></td>
                                                    <td><?php
                                                        if($chk_status==1) { ?>
                                                            <input type="button" name="btnlink_<?php echo $s_no; ?>" id="btnlink_<?php echo $s_no; ?>" value="Link" class="cl_link btn btn-primary"/>
                                                        <?php } ?>
                                                        <input type="hidden" name="hd_caveat_rec_dt<?php echo $s_no; ?>" id="hd_caveat_rec_dt<?php echo $s_no; ?>" value="<?php echo $r_main['diary_no_rec_date'];  ?>"/>

                                                    </td>
                                                    <td> <span id="sp_diary_no<?php echo $s_no; ?>" class="cl_c_diary"><?=$diary_no;?></span>
                                                        <input type="hidden" name="hd_caveat_no<?php echo $s_no; ?>"
                                                               id="hd_caveat_no<?php echo $s_no; ?>" value="<?php echo $row['diary_no']; ?>"/>  <br/>
                                                        <span style="color: black" id="sp_diary_no<?php echo $s_no; ?>"><?=!empty($r_main['diary_no_rec_date']) ? date('d-m-Y',strtotime($r_main['diary_no_rec_date'])).'<br/>': '';?></span>
                                                        <?php  if($r_main['c_status']=='D'){ echo "<b><font color='red'>Disposed</font></b>"; } else{
                                                               echo "Pending";
                                                            if($r_main['r_head']!=null && $r_main['r_head']!=''){
                                                                echo "<br><b><font color='#006400'>After Notice</font></b>";
                                                            } }  ?>
                                                            <br/><?php if($r_main['da_section']=='') echo "<font color='blue'>".$r_main['sectionname']."</font>";
                                                            else echo "<font color='blue'>".$r_main['da_section']."</font>";?>
                                                    </td>
                                                    <td><?=$short_description_details;?> </td>
                                                    <td><?=!empty($r_main) ? $r_main['pet_name'].'<br/>Vs<br/>'.$r_main['res_name'] : ''; ?> </td>
                                                    <td><span id="sp_court_name<?php echo $s_no; ?>"><?php echo $row['court_name']; ?></span> </td>
                                                    <td><span id="sp_Name<?php echo $s_no; ?>"><?php echo $row['name'];?></span> </td>
                                                    <td><span id="sp_agency_name<?php echo $s_no; ?>"><?php echo $row['agency_name'];?></span> </td>
                                                    <td><span id="sp_case_name<?php echo $s_no; ?>"><?php echo $row['type_sname'].'-'.$row['lct_caseno'].'-'.$row['lct_caseyear'];?></span> </td>
                                                    <td><?=!empty($row['lct_dec_dt']) ? date('d-m-Y',strtotime($row['lct_dec_dt'])) : '';?> </td>
                                                    <td><?php echo $row['judgename']; ?> </td>
                                                    <td>
                                                        <?php if (!empty($row['caveat_diary_matching'])){
                                                            foreach ($row['caveat_diary_matching'] as $row1){
                                                                $caveat_no=(!empty($row1['caveat_no']) && $row1['caveat_no']!=null) ? substr($row1['caveat_no'],0,-4).'-'.  substr($row1['caveat_no'],-4) : '';
                                                                ?>
                                                                <table>
                                                                    <tr>
                                                                        <td><?=$caveat_no;?></td>
                                                                        <td>
                                                                            <?php if($row1['caveat_no']==$caveat_number){?>
                                                                                <span id="sp_cav_diary_lnl_dt<?php echo $s_no; ?>"><?php echo $row1['link_dt']; ?></span>
                                                                            <?php } else{ echo $row1['link_dt'];
                                                                            }  ?>
                                                                        </td>
                                                                        <td>
                                                                            <?php if($row1['caveat_no']==$caveat_number) {?>
                                                                                <input type="hidden" name="hd_linked_no<?php echo $s_no; ?>" id="hd_linked_no<?php echo $s_no; ?>" value="<?php echo $row1['caveat_no']; ?>"/>
                                                                                <input type="button" name="hd_unlink<?php echo $s_no; ?>" id="hd_unlink<?php echo $s_no; ?>" value="Unlink" class="cl_unlink btn btn-primary"/>
                                                                            <?php } ?>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            <?php } } ?>
                                                    </td>
                                                    <td><?php if($rep_date_diff<=90 && $chk_status==1){ ?>
                                                            <span style="color: green">Active</span>
                                                        <?php  } else { ?>
                                                            <span style="color: red">Expired</span>
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                                <?php $s_no++; } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!--similarity-5 end-->
                            <!--similarity-6 -->
                            <div class="row">
                                <div class="col-12">
                                    <h4 class="basic_heading">6. Similarities based on  Arbitration Ref. no. and Arbitration date</h4>
                                    <div class="similarity6">
                                        <table id="similarity6" class="table table-bordered table-striped table-responsive">
                                            <thead>
                                            <tr>
                                                <th style="width: 5%;">S.No. </th>
                                                <th style="width: 5%;"> Link </th>
                                                <th style="width: 10%;">Diary No./<br/>Receiving Date</th>
                                                <th style="width: 10%;"> Registration No.</th>
                                                <th style="width: 10%;"> Petitioner<br/>Vs<br/>Respondent</th>
                                                <th style="width: 10%;">From Court</th>
                                                <th style="width: 10%;">State</th>
                                                <th style="width: 10%;">Bench</th>
                                                <th style="width: 10%;">Case No.</th>
                                                <th style="width: 10%;"> Judgement Date</th>
                                                <th style="width: 10%;"> Judge Name</th>
                                                <th style="width: 10%;">Linked with Caveat and Date</th>
                                                <th style="width: 10%;">Status</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php //$s_no=1;
                                            $chk_status=0;$rep_date_diff=0;$diary_no='';
                                            foreach ($arbitration_ref_date as $row ){
                                                if($row['lct_casetype']==50) {
                                                    $row['type_sname']= "WNN";
                                                }
                                                if($row['lct_casetype']==51){
                                                    $row['type_sname']= "ARN";
                                                }
                                                $diary_no=(!empty($row['diary_no']) && $row['diary_no']!=null) ? substr($row['diary_no'],0,-4).'-'.  substr($row['diary_no'],-4) : '';
                                                $r_main=array();
                                                if (!empty($row['sub_details'])) {
                                                    $r_main = $row['sub_details'];
                                                    $res_case_type_c=$row['casetype_details']['short_description'];
                                                    $short_description_details=$res_case_type_c.'-'.intval(substr($r_main['active_fil_no'],3)).'-'.$r_main['active_fil_dt'];
                                                    if (strtotime($r_main['diary_no_rec_date']) >= strtotime($caveat_rec_dt)) {
                                                        $date1 = date_create($r_main['diary_no_rec_date']);
                                                        $date2 = date_create($caveat_rec_dt);
                                                        $diff = date_diff($date2, $date1);
                                                        $date_diff = $diff->format("%R%a days");
                                                        $rep_date_diff = intval(str_replace('+', '', $date_diff));
                                                        if ($rep_date_diff <= 90) {
                                                            $chk_status = 1;
                                                        }
                                                    } else {
                                                        $chk_status = 1;
                                                    }
                                                }
                                                ?>
                                                <tr>
                                                    <td><?=$s_no;?></td>
                                                    <td><?php
                                                        if($chk_status==1) { ?>
                                                            <input type="button" name="btnlink_<?php echo $s_no; ?>" id="btnlink_<?php echo $s_no; ?>" value="Link" class="cl_link btn btn-primary"/>
                                                        <?php } ?>
                                                        <input type="hidden" name="hd_caveat_rec_dt<?php echo $s_no; ?>" id="hd_caveat_rec_dt<?php echo $s_no; ?>" value="<?php echo $r_main['diary_no_rec_date'];  ?>"/>
                                                    </td>

                                                    <td>
                                                        <span id="sp_diary_no<?php echo $s_no; ?>" class="cl_c_diary"><?=$diary_no;?></span>
                                                        <input type="hidden" name="hd_caveat_no<?php echo $s_no; ?>" id="hd_caveat_no<?php echo $s_no; ?>" value="<?php echo $row['diary_no']; ?>"/>  <br/>
                                                        <span style="color: black" id="sp_diary_no<?php echo $s_no; ?>"><?=!empty($r_main['diary_no_rec_date']) ? date('d-m-Y',strtotime($r_main['diary_no_rec_date'])).'<br/>': '';?></span>
                                                        <?php  if($r_main['c_status']=='D'){
                                                            echo "<b><font color='red'>Disposed</font></b>";
                                                        }else{
                                                            echo "Pending";
                                                            if($r_main['r_head']!=null && $r_main['r_head']!=''){ echo "<br><b><font color='#006400'>After Notice</font></b>"; }
                                                        }
                                                        ?>
                                                        <br/><?php if($r_main['da_section']=='') echo "<font color='blue'>".$r_main['sectionname']."</font>";
                                                        else echo "<font color='blue'>".$r_main['da_section']."</font>";?>
                                                    </td>
                                                    <td><?=$short_description_details;?> </td>
                                                    <td><?=!empty($r_main) ? $r_main['pet_name'].'<br/>Vs<br/>'.$r_main['res_name'] : ''; ?> </td>
                                                    <td><span id="sp_court_name<?php echo $s_no; ?>"><?php echo $row['court_name']; ?></span> </td>
                                                    <td><span id="sp_Name<?php echo $s_no; ?>"><?php echo $row['name'];?></span> </td>
                                                    <td><span id="sp_agency_name<?php echo $s_no; ?>"><?php echo $row['agency_name'];?></span> </td>
                                                    <td><span id="sp_case_name<?php echo $s_no; ?>"><?php echo $row['type_sname'].'-'.$row['lct_caseno'].'-'.$row['lct_caseyear'];?></span> </td>
                                                    <td><?=!empty($row['lct_dec_dt']) ? date('d-m-Y',strtotime($row['lct_dec_dt'])) : '';?> </td>
                                                    <td><?php echo $row['judgename']; ?> </td>
                                                    <td>
                                                        <?php if (!empty($row['caveat_diary_matching'])){
                                                            foreach ($row['caveat_diary_matching'] as $row1){
                                                                $caveat_no=(!empty($row1['caveat_no']) && $row1['caveat_no']!=null) ? substr($row1['caveat_no'],0,-4).'-'.  substr($row1['caveat_no'],-4) : '';
                                                                ?>
                                                                <table>
                                                                    <tr>
                                                                        <td><?=$caveat_no;?></td>
                                                                        <td>
                                                                            <?php if($row1['caveat_no']==$caveat_number){?>
                                                                                <span id="sp_cav_diary_lnl_dt<?php echo $s_no; ?>"><?php echo $row1['link_dt']; ?></span>
                                                                            <?php } else{ echo $row1['link_dt'];
                                                                            }  ?>
                                                                        </td>
                                                                        <td>
                                                                            <?php if($row1['caveat_no']==$caveat_number) {?>
                                                                                <input type="hidden" name="hd_linked_no<?php echo $s_no; ?>" id="hd_linked_no<?php echo $s_no; ?>" value="<?php echo $row1['caveat_no']; ?>"/>
                                                                                <input type="button" name="hd_unlink<?php echo $s_no; ?>" id="hd_unlink<?php echo $s_no; ?>" value="Unlink" class="cl_unlink btn btn-primary"/>
                                                                            <?php } ?>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            <?php } } ?>
                                                    </td>
                                                    <td><?php if($rep_date_diff<=90 && $chk_status==1){ ?>
                                                            <span style="color: green">Active</span>
                                                        <?php  } else { ?>
                                                            <span style="color: red">Expired</span>
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                                <?php $s_no++; } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!--similarity-6 end-->


                            <!--similarity-7 -->
                            <div class="row">
                                <div class="col-12">
                                    <h4 class="basic_heading">7.Similarities Based on Arbitration date</h4>
                                    <div class="similarity7">
                                        <table id="similarity7" class="table table-bordered table-striped table-responsive">
                                            <thead>
                                            <tr>
                                                <th style="width: 5%;">S.No. </th>
                                                <th style="width: 5%;"> Link </th>
                                                <th style="width: 10%;">Diary No./<br/>Receiving Date</th>
                                                <th style="width: 10%;"> Registration No.</th>
                                                <th style="width: 10%;"> Petitioner<br/>Vs<br/>Respondent</th>
                                                <th style="width: 10%;">From Court</th>
                                                <th style="width: 10%;">State</th>
                                                <th style="width: 10%;">Bench</th>
                                                <th style="width: 10%;">Case No.</th>
                                                <th style="width: 10%;"> Judgement Date</th>
                                                <th style="width: 10%;"> Judge Name</th>
                                                <th style="width: 10%;">Linked with Caveat and Date</th>
                                                <th style="width: 10%;">Status</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php //$s_no=1;
                                            $chk_status=0;$rep_date_diff=0;$diary_no=$short_description_details='';
                                            foreach ($arbitration_date as $row ){
                                                if($row['lct_casetype']==50) {
                                                    $row['type_sname']= "WNN";
                                                }
                                                if($row['lct_casetype']==51){
                                                    $row['type_sname']= "ARN";
                                                }
                                                $diary_no=(!empty($row['diary_no']) && $row['diary_no']!=null) ? substr($row['diary_no'],0,-4).'-'.  substr($row['diary_no'],-4) : '';
                                                $r_main=array();
                                                if (!empty($row['sub_details'])) {
                                                    $r_main = $row['sub_details'];
                                                    if (!empty($row['casetype_details']) && $row['casetype_details'] !=null){
                                                        $res_case_type_c=$row['casetype_details']['short_description'];
                                                        $short_description_details=$res_case_type_c.'-'.intval(substr($r_main['active_fil_no'],3)).'-'.$r_main['active_fil_dt'];
                                                    }

                                                    if (strtotime($r_main['diary_no_rec_date']) >= strtotime($caveat_rec_dt)) {
                                                        $date1 = date_create($r_main['diary_no_rec_date']);
                                                        $date2 = date_create($caveat_rec_dt);
                                                        $diff = date_diff($date2, $date1);
                                                        $date_diff = $diff->format("%R%a days");
                                                        $rep_date_diff = intval(str_replace('+', '', $date_diff));
                                                        if ($rep_date_diff <= 90) {
                                                            $chk_status = 1;
                                                        }
                                                    } else {
                                                        $chk_status = 1;
                                                    }
                                                }
                                                ?>
                                                <tr>
                                                    <td><?=$s_no;?></td>
                                                    <td><?php
                                                        if($chk_status==1) { ?>
                                                            <input type="button" name="btnlink_<?php echo $s_no; ?>" id="btnlink_<?php echo $s_no; ?>" value="Link" class="cl_link btn btn-primary"/>
                                                        <?php } ?>
                                                        <input type="hidden" name="hd_caveat_rec_dt<?php echo $s_no; ?>" id="hd_caveat_rec_dt<?php echo $s_no; ?>" value="<?php echo $r_main['diary_no_rec_date'];  ?>"/>
                                                    </td>
                                                    <td><span id="sp_diary_no<?php echo $s_no; ?>" class="cl_c_diary"><?php echo substr($row['diary_no'],0,-4).'-'.  substr($row['diary_no'],-4); ?></span>
                                                        <input type="hidden" name="hd_caveat_no<?php echo $s_no; ?>"
                                                               id="hd_caveat_no<?php echo $s_no; ?>" value="<?php echo $row['diary_no']; ?>"/>  <br/>
                                                        <span style="color: black" id="sp_diary_no<?php echo $s_no; ?>"><?=!empty($r_main['diary_no_rec_date']) ? date('d-m-Y',strtotime($r_main['diary_no_rec_date'])).'<br/>': '';?></span>
                                                        <?php  if($r_main['c_status']=='D'){ echo "<b><font color='red'>Disposed</font></b>"; }else{
                                                                echo "Pending";
                                                                if($r_main['r_head']!=null && $r_main['r_head']!=''){
                                                                    echo "<br><b><font color='#006400'>After Notice</font></b>";
                                                                }
                                                            }
                                                            ?>
                                                            <br/><?php if($r_main['da_section']=='') echo "<font color='blue'>".$r_main['sectionname']."</font>";
                                                            else echo "<font color='blue'>".$r_main['da_section']."</font>";?>
                                                    </td>
                                                    <td><?=$short_description_details;?> </td>
                                                    <td><?=!empty($r_main) ? $r_main['pet_name'].'<br/>Vs<br/>'.$r_main['res_name'] : ''; ?> </td>
                                                    <td><span id="sp_court_name<?php echo $s_no; ?>"><?php echo $row['court_name']; ?></span> </td>
                                                    <td><span id="sp_Name<?php echo $s_no; ?>"><?php echo $row['name'];?></span> </td>
                                                    <td><span id="sp_agency_name<?php echo $s_no; ?>"><?php echo $row['agency_name'];?></span> </td>
                                                    <td><span id="sp_case_name<?php echo $s_no; ?>"><?php echo $row['type_sname'].'-'.$row['lct_caseno'].'-'.$row['lct_caseyear'];?></span> </td>
                                                    <td><?=!empty($row['lct_dec_dt']) ? date('d-m-Y',strtotime($row['lct_dec_dt'])) : '';?> </td>
                                                    <td><?php echo $row['judgename']; ?> </td>
                                                    <td>
                                                        <?php if (!empty($row['caveat_diary_matching'])){
                                                            foreach ($row['caveat_diary_matching'] as $row1){
                                                                $caveat_no=(!empty($row1['caveat_no']) && $row1['caveat_no']!=null) ? substr($row1['caveat_no'],0,-4).'-'.  substr($row1['caveat_no'],-4) : '';
                                                                ?>
                                                                <table>
                                                                    <tr>
                                                                        <td><?=$caveat_no;?></td>
                                                                        <td>
                                                                            <?php if($row1['caveat_no']==$caveat_number){?>
                                                                                <span id="sp_cav_diary_lnl_dt<?php echo $s_no; ?>"><?php echo $row1['link_dt']; ?></span>
                                                                            <?php } else{ echo $row1['link_dt'];
                                                                            }  ?>
                                                                        </td>
                                                                        <td>
                                                                            <?php if($row1['caveat_no']==$caveat_number) {?>
                                                                                <input type="hidden" name="hd_linked_no<?php echo $s_no; ?>" id="hd_linked_no<?php echo $s_no; ?>" value="<?php echo $row1['caveat_no']; ?>"/>
                                                                                <input type="button" name="hd_unlink<?php echo $s_no; ?>" id="hd_unlink<?php echo $s_no; ?>" value="Unlink" class="cl_unlink btn btn-primary"/>
                                                                            <?php } ?>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            <?php } } ?>
                                                    </td>
                                                    <td><?php if($rep_date_diff<=90 && $chk_status==1){ ?>
                                                            <span style="color: green">Active</span>
                                                        <?php  } else { ?>
                                                            <span style="color: red">Expired</span>
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                                <?php $s_no++; } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!--similarity-7 end-->

                        <?php }else{
                            echo '<center><span class="text-danger">No Record Found !!</span></center>';
                        } ?>


    <script>
        $(function() {
            $(".table").DataTable({
                "paging": false,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": true,
                "responsive": true,
            });

        });
    </script>