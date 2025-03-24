<?php if ($without_header != 'without_header') { ?>
    <?=view('header'); ?>
     
<?php  } ?>

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
</style>
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <?php if ($without_header != 'without_header') { ?>

                        <div class="card-header heading">

                            <div class="row">
                                <div class="col-sm-10">
                                    <h3 class="card-title">Filing</h3>
                                </div>
                                <?= view('Filing/filing_filter_buttons'); ?>
                                
                            </div>
                        </div>
                        <?= view('Filing/filing_breadcrumb',['show' => 'N']); ?>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header p-2" style="background-color: #fff;">
                                        <h4 class="basic_heading"> Similarities </h4><span style="display: none" id="m_diary_n"><?php echo $m_diary_no; ?></span>
                                        <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                                        <div class="cl_center" style="text-align: center;"><?php if ($main_diary_number != '') { ?>Main Case- <?php } ?><b><?php echo  substr($main_diary_number, 0, -4) . '-' .  substr($main_diary_number, -4); ?><span style="display: none" id="dv_mn_case"><?php echo $main_diary_number; ?></span></b></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                
                <?php if(!empty($current_filing_detail)){?>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="pg-breif-sec">
                                <div class="row ">
                                    <div class="col-md-4">
                                        <div class="breif-detlais-inner">
                                            <label><b>Diary Number :</b> </label>
                                            <label class="lable-rslt"> <?=substr($current_filing_detail['diary_no'], 0, -4).'/'.substr($current_filing_detail['diary_no'],-4);?> </label>
                                        </div>
                                    </div>
                                    <?php if (!empty($current_filing_detail['reg_no_display'])){?>
                                    <div class="col-md-4">
                                        <div class="breif-detlais-inner">
                                            <label><b>Case Number :</b></label>
                                            <label class="lable-rslt"> <?=$current_filing_detail['reg_no_display'];?></label>
                                        </div>
                                    </div>
                                    <?php } ?>
                                    <div class="col-md-4">
                                        <div class="breif-detlais-inner">
                                            <label><b>Case Title :</b></label>
                                            <label class="lable-rslt"> <?=$current_filing_detail['pet_name'].'  <b>Vs</b>  '.$current_filing_detail['res_name'];?> </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="breif-detlais-inner">
                                            <label><b>Filing Date :</b></label>
                                            <label class="lable-rslt"><?=(!empty($current_filing_detail['diary_no_rec_date'])) ? date('d-m-Y',strtotime($current_filing_detail['diary_no_rec_date'])): NULL ?>
                                            <span class="text-blue"><?php if ($current_filing_detail['c_status'] =='P'){ echo '<span class="text-blue">Pending</span>';}else{echo '<span class="text-red">Disposed</span>';} ?></span> </label>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>

                    <?php }?>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="p-2" style="background-color: #fff;">
                                <h4 class="basic_heading"> Similarities based on State, Bench, Case No. and Judgement Date </h4>
                            </div>
                            <div class="card-body">
                                <?php if (!empty($state_bench_pending) || !empty($state_bench_disposed)) : ?>

                                    <table id="lowercourtdata2" class="table table-bordered table-striped table-responsive">
                                        <thead>
                                            <tr>
                                                <th style="width:10%;">
                                                    S.No.
                                                </th>
                                                <th style="width:10%;">
                                                    Link To Diary No
                                                </th>
                                                <th style="width:15%;">
                                                    Diary No./
                                                    Registration No.
                                                </th>
                                                <th style="width:25%;">
                                                    From Court /<br>
                                                    State /<br>
                                                    Bench<br>
                                                </th>
                                                <th style="width:15%;">
                                                    Case No.
                                                </th>
                                                <th style="width:15%;">
                                                    Judgement Date<br>
                                                    / Judgement Challenged<br>
                                                    / Judgement Type
                                                </th>
                                                <th style="width:10%;">
                                                    Status
                                                </th>
                                                <th style="width:15%;">
                                                    Stage
                                                </th>
                                                <th style="width:15%;">
                                                    Linked with Case and Date
                                                </th>
                                                <th style="width:15%;">
                                                    Connected with Case and Date
                                                </th>
                                                <th style="width:20%;">
                                                    Taging Remark
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $sn1 = 0;
                                            if (!empty($state_bench_pending)) {
                                               // pr($state_bench_pending);
                                                foreach ($state_bench_pending as $row) {
                                                    $sn1++;
                                            ?>
                                                    <tr>
                                                        <td><?php echo $sn1; ?></td>
                                                        <td>
                                                            <?php if ($row['res_connect_case'] == '' && $res_listed <= 0 && $row['res_listed1'] <= 0 && $row['c_status'] == 'P') { ?>
                                                                <input title="Based on State, Bench, Case No. and Judgement Date" type="button" name="btnlink_<?php echo $sn1; ?>" id="btnlink_<?php echo $sn1; ?>" value="Link" class="cl_link btn-success btn" disabled />
                                                            <?php } ?>
                                                        </td>
                                                        <td>
                                                            <div class="btn btn-success btn-sm" type="button" onclick="viewcasestatus('<?php echo $row['c_diary']; ?>')"> <?php echo substr($row['c_diary'], 0, -4) . '-' .  substr($row['c_diary'], -4); ?></div>
                                                            <input type="hidden" name="hd_link<?php echo $sn1; ?>" id="hd_link<?php echo $sn1; ?>" value="<?php echo $row['c_diary']; ?>" disabled />
                                                            <?php
                                                            echo '<br>';
                                                            $case_no = $row['short_description'];
                                                            if ($row['fil_no'] != '')
                                                                $case_no = $case_no . '/' . $row['fil_no'];
                                                            if ($row['fil_dt'] != '')
                                                                $case_no = $case_no . '/' . $row['fil_dt'];
                                                            if ($case_no == '')
                                                                echo "-";
                                                            else
                                                                echo $case_no;
                                                            ?>
                                                        </td>
                                                        <td><?php echo $row['court_name']; ?>/
                                                            <?php echo '<br>' . $row['name']; ?>/
                                                            <?php echo '<br>' . $row['agency_name']; ?>
                                                        </td>

                                                        <td><?php echo $row['type_sname'] . '-' . $row['lct_caseno'] . '-' . $row['lct_caseyear']; ?></td>
                                                        <td><?php echo date('d-m-Y', strtotime($row['lct_dec_dt'])); ?>/<?php
                                                                                                                        echo $row['is_order_challenged'];
                                                                                                                        ?>/<?php
                                                                                                                            if ($row['full_interim_flag'] == 'F')
                                                                                                                                echo "Final";
                                                                                                                            else 
                                                                    if ($row['full_interim_flag'] == 'I')
                                                                                                                                echo "Interim";
                                                                                                                            ?></td>

                                                        <td> <?php

                                                                if ($row['c_status'] == 'P')
                                                                    echo "Pending";
                                                                else
                                                                    echo "Disposed";

                                                                ?></td>
                                                        <td><?php if (!empty($current_stage))
                                                                echo $current_stage['description'];
                                                            else {
                                                                echo '';
                                                            }; ?></td>
                                                        <td><?php if ($row['res_linked'] == 0) echo '-';
                                                            else echo (!empty($row['res_linked'])) ?  substr(trim($row['res_linked']), 0, -4) . '-' .  substr(trim($row['res_linked']), -4) : ''; ?></td>
                                                        <td><?php echo (!empty($row['res_connect_case'])) ? substr($row['res_connect_case'], 0, -4) . '-' .  substr($row['res_connect_case'], -4) : ''; ?></td>
                                                        <td><?php if (!empty($row['linking_reson'])) {
                                                            } else { ?>Based on <?php echo $row['court_name']; ?>, <?php echo ($row['name']); ?>, <?php echo ($row['agency_name']); ?>, <?php echo ($row['type_sname']) . '-' . ($row['lct_caseno']) . '-' . ($row['lct_caseyear']); ?>, <?php echo (date('d-m-Y', strtotime($row['lct_dec_dt']))); ?>
                                                        <?php } ?>
                                                        </td>
                                                    </tr>
                                            <?php }
                                            } ?>
                                            <?php if (!empty($state_bench_disposed)) {
                                                // if(empty($sn1)){
                                                //     $sn1="0";
                                                // }
                                                foreach ($state_bench_disposed as $row) {
                                                    $sn1++;
                                            ?>
                                                    <tr>
                                                        <td><?php echo $sn1; ?></td>
                                                        <td>
                                                            <?php if ($row['res_connect_case'] == '' && $res_listed <= 0 && $row['res_listed1'] <= 0 && $row['c_status'] == 'P') { ?>
                                                                <input title="Based on State, Bench, Case No. and Judgement Date" type="button" name="btnlink_<?php echo $sn1; ?>" id="btnlink_<?php echo $sn1; ?>" value="Link" class="cl_link btn-success btn" disabled />
                                                            <?php } ?>
                                                        </td>
                                                        <td>
                                                            <div class="btn btn-success btn-sm" type="button" onclick="viewcasestatus('<?php echo $row['c_diary']; ?>')"> <?php echo substr($row['c_diary'], 0, -4) . '-' .  substr($row['c_diary'], -4); ?></div>
                                                            <input type="hidden" name="hd_link<?php echo $sn1; ?>" id="hd_link<?php echo $sn1; ?>" value="<?php echo $row['c_diary']; ?>" />
                                                            <?php
                                                            echo '<br>';
                                                            $case_no = $row['short_description'];
                                                            if ($row['fil_no'] != '')
                                                                $case_no = $case_no . '/' . $row['fil_no'];
                                                            if ($row['fil_dt'] != '')
                                                                $case_no = $case_no . '/' . $row['fil_dt'];
                                                            if ($case_no == '')
                                                                echo "-";
                                                            else
                                                                echo $case_no;
                                                            ?>
                                                        </td>
                                                        <td><?php echo $row['court_name']; ?>/
                                                            <?php echo '<br>' . $row['name']; ?>/
                                                            <?php echo '<br>' . $row['agency_name']; ?>
                                                        </td>
                                                        <td><?php echo $row['type_sname'] . '-' . $row['lct_caseno'] . '-' . $row['lct_caseyear']; ?></td>
                                                        <td><?php echo date('d-m-Y', strtotime($row['lct_dec_dt'])); ?>/<?php
                                                                                                                        echo $row['is_order_challenged'];
                                                                                                                        ?>/<?php
                                                                                                                            if ($row['full_interim_flag'] == 'F')
                                                                                                                                echo "Final";
                                                                                                                            else 
                                                                    if ($row['full_interim_flag'] == 'I')
                                                                                                                                echo "Interim";
                                                                                                                            ?></td>

                                                        <td> <?php

                                                                if ($row['c_status'] == 'P')
                                                                    echo "Pending";
                                                                else
                                                                    echo "Disposed";

                                                                ?></td>
                                                        <td><?php if (!empty($current_stage))
                                                                echo $current_stage['description'];
                                                            else {
                                                                echo '';
                                                            }; ?></td>
                                                        <td><?php if ($row['res_linked'] == 0) echo '-';
                                                            else echo (!empty($row['res_linked'])) ?  substr(trim($row['res_linked']), 0, -4) . '-' .  substr(trim($row['res_linked']), -4) : ''; ?></td>
                                                        <td><?php echo (!empty($row['res_connect_case'])) ? substr($row['res_connect_case'], 0, -4) . '-' .  substr($row['res_connect_case'], -4) : ''; ?></td>
                                                        <td><?php if (!empty($row['linking_reson'])) {
                                                            } else { ?>Based on <?php echo $row['court_name']; ?>, <?php echo ($row['name']); ?>, <?php echo ($row['agency_name']); ?>, <?php echo ($row['type_sname']) . '-' . ($row['lct_caseno']) . '-' . ($row['lct_caseyear']); ?>, <?php echo (date('d-m-Y', strtotime($row['lct_dec_dt']))); ?>
                                                        <?php } ?></td>
                                                    </tr>
                                            <?php }
                                            } ?>
                                        </tbody>
                                    </table>
                                <?php else : ?>
                                    <p style="text-align: center; color:red">No records found.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="p-2" style="background-color: #fff;">
                                <h4 class="basic_heading"> Similarities based on Crime No/Year and Police Station </h4>
                            </div>
                            <div class="card-body">
                                <?php if (!empty($police_station_data) || !empty($police_station_data_disposed)) : ?>

                                    <table id="lowercourtdata3" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th style="width:10%;">
                                                    S.No.
                                                </th>
                                                <th style="width:10%;">
                                                    Link To Diary No
                                                </th>
                                                <th>
                                                    Diary No.
                                                    /
                                                    Registration No.
                                                </th>
                                                <th>
                                                    From Court
                                                    /State
                                                    /Bench
                                                </th>
                                                <th>
                                                    Case No.
                                                </th>
                                                <th>
                                                    Judgement Date
                                                    /Judgement Challenged
                                                    /Judgement Type
                                                </th>
                                                <th>
                                                    Police Station
                                                </th>
                                                <th>
                                                    Crime No/Year
                                                </th>
                                                <th>
                                                    Status
                                                </th>
                                                <th>
                                                    Stage
                                                </th>
                                                <th>
                                                    Linked with Case and Date
                                                </th>
                                                <th>
                                                    Connected with Case and Date
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $sn2 = 0;
                                            if (!empty($police_station_data)) {

                                                foreach ($police_station_data as $row) {
                                                    $sn2++;
                                            ?>
                                                    <tr>
                                                        <td><?php echo $sn2; ?></td>
                                                        <td>
                                                            <?php if ($row['res_connect_case'] == '' && $res_listed <= 0 && $row['res_listed1'] <= 0 && $row['c_status'] == 'P') { ?>
                                                                <input type="button" name="btnlink_<?php echo $sn2; ?>" id="btnlink_<?php echo $sn2; ?>" value="Link" class="cl_link btn-success btn" disabled />
                                                            <?php } ?>
                                                        </td>
                                                        <td>
                                                            <div class="btn btn-success btn-sm" type="button" onclick="viewcasestatus('<?php echo $row['c_diary']; ?>')"> <?php echo substr($row['c_diary'], 0, -4) . '-' .  substr($row['c_diary'], -4); ?></div>
                                                            

                                                            <?php
                                                            $case_no = $row['short_description'];
                                                            if ($row['fil_no'] != '')
                                                                $case_no = $case_no . '/' . $row['fil_no'];
                                                            if ($row['fil_dt'] != '')
                                                                $case_no = $case_no . '/' . $row['fil_dt'];
                                                            // echo $row['short_description'].'/'.$row['fil_no'].'/'.$row['fil_dt'];
                                                            if ($case_no == '')
                                                                echo "/ -";
                                                            else
                                                                echo '/ '.$case_no;
                                                            ?>


                                                        </td>
                                                        <input type="hidden" name="hd_link<?php echo $sn2; ?>" id="hd_link<?php echo $sn2; ?>" value="<?php echo $row['c_diary']; ?>" />

                                                        <td> <?php echo $row['court_name'] ?> /
                                                            <?php
                                                            echo $row['name'];
                                                            ?>
                                                            <?php
                                                            echo $row['agency_name'];
                                                            ?>
                                                        </td>
                                                        <td> 
                                                            <?php
                                                                echo $row['type_sname'] . '-' . $row['lct_caseno'] . '-' . $row['lct_caseyear'];
                                                            ?>
                                                        </td>
                                                        <td> <?php if ($row['lct_dec_dt'] != '') echo date('d-m-Y', strtotime($row['lct_dec_dt'])). ' / '; ?>
                                                            <?php
                                                                echo $row['is_order_challenged'];
                                                                ?>
                                                            <?php
                                                            if ($row['full_interim_flag'] == 'F')
                                                                echo "Final";
                                                            else 
                                                                if ($row['full_interim_flag'] == 'I')
                                                                    echo "Interim";
                                                            ?>

                                                        </td>
                                                        <td> <?php echo $row['policestndesc']; ?></td>
                                                        <td> <?php echo $row['crimeno'] . '/' . $row['crimeyear'] ?></td>
                                                        <td> <?php

                                                                if ($row['c_status'] == 'P')
                                                                    echo "Pending";
                                                                else
                                                                    echo "Disposed";

                                                                ?></td>
                                                        <td><?php if (!empty($current_stage))
                                                                echo $current_stage['description'];
                                                            else {
                                                                echo '';
                                                            }; ?></td>
                                                        <td><?php if ($row['res_linked'] == 0) echo '-';                                                             
                                                                else echo (!empty($row['res_linked'])) ?  substr(trim($row['res_linked']), 0, -4) . '-' .  substr(trim($row['res_linked']), -4) : ''; ?></td>
                                                        <td><?php echo (!empty($row['res_connect_case'])) ? substr($row['res_connect_case'], 0, -4) . '-' .  substr($row['res_connect_case'], -4) : ''; ?></td>
                                                    </tr>
                                            <?php }
                                            }
                                            ?>
                                            <?php if (!empty($police_station_data_disposed)) {
                                                foreach ($police_station_data_disposed as $row) {
                                                    $sn2++;
                                            ?>
                                                    <tr>
                                                        <td><?php echo $sn2; ?></td>
                                                        <td> <?php if ($row['res_connect_case'] == '' && $res_listed <= 0 && $row['res_listed1'] <= 0 && $row['c_status'] == 'P') { ?>
                                                                <input type="button" name="btnlink_<?php echo $sn2; ?>" id="btnlink_<?php echo $sn2; ?>" value="Link" class="cl_link btn-success btn" disabled />
                                                            <?php } ?>
                                                        </td>
                                                        <td>
                                                            <div class="btn btn-success btn-sm" type="button" onclick="viewcasestatus('<?php echo $row['c_diary']; ?>')"> <?php echo substr($row['c_diary'], 0, -4) . '-' .  substr($row['c_diary'], -4); ?></div>/
                                                            <?php
                                                            $case_no = $row['short_description'];
                                                            if ($row['fil_no'] != '')
                                                                $case_no = $case_no . '/' . $row['fil_no'];
                                                            if ($row['fil_dt'] != '')
                                                                $case_no = $case_no . '/' . $row['fil_dt'];
                                                            //            echo $row['short_description'].'/'.$row['fil_no'].'/'.$row['fil_dt'];
                                                            if ($case_no == '')
                                                                echo "-";
                                                            else
                                                                echo $case_no;
                                                            ?>
                                                        </td><input type="hidden" name="hd_link<?php echo $sn2; ?>" id="hd_link<?php echo $sn2; ?>" value="<?php echo $row['c_diary']; ?>" />
                                                        <td> <?php echo $row['court_name'] ?> /
                                                            <?php
                                                            echo $row['name'];
                                                            ?>
                                                            <?php
                                                            echo $row['agency_name'];
                                                            ?>
                                                        </td>
                                                        <td> <?php
                                                                echo $row['type_sname'] . '-' . $row['lct_caseno'] . '-' . $row['lct_caseyear'];
                                                                ?></td>
                                                        <td> <?php if ($row['lct_dec_dt'] != '') echo date('d-m-Y', strtotime($row['lct_dec_dt'])); ?>
                                                            / <?php
                                                                echo $row['is_order_challenged'];
                                                                ?>
                                                            <?php
                                                            if ($row['full_interim_flag'] == 'F')
                                                                echo "Final";
                                                            else 
                                                    if ($row['full_interim_flag'] == 'I')
                                                                echo "Interim";
                                                            ?>
                                                        </td>
                                                        <td><?php echo $row['policestndesc']; ?></td>
                                                        <td><?php echo $row['crimeno'] . '/' . $row['crimeyear'] ?></td>
                                                        <td><?php
                                                            if ($row['c_status'] == 'P')
                                                                echo "Pending";
                                                            else
                                                                echo "Disposed";

                                                            ?></td>
                                                        <td><?php if (!empty($current_stage))
                                                                echo $current_stage['description'];
                                                            else {
                                                                echo '';
                                                            }; ?></td>
                                                        <td><?php if ($row['res_linked'] == 0) echo '-';
                                                            else echo (!empty($row['res_linked'])) ?  substr(trim($row['res_linked']), 0, -4) . '-' .  substr(trim($row['res_linked']), -4) : ''; ?></td>
                                                        <td><?php echo (!empty($row['res_connect_case'])) ? substr($row['res_connect_case'], 0, -4) . '-' .  substr($row['res_connect_case'], -4) : ''; ?></td>                                                             
                                                    </tr>
                                            <?php }
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                <?php else : ?>
                                    <p style="text-align: center; color:red">No records found.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="p-2" style="background-color: #fff;">
                                <h4 class="basic_heading"> Similarities based on Vehicle No. </h4>
                            </div>
                            <div class="card-body">
                            <?php if (!empty($vehicle_data_pending) || !empty($vehicle_data_disposed)) : ?>

                                <table id="lowercourtdata4" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th style="width:10%;">
                                                S.No.
                                            </th>
                                            <th style="width:10%;">
                                                Link To Diary No
                                            </th>
                                            <th>
                                                Diary No.
                                            </th>
                                            <th>
                                                From Court
                                                / State
                                                / Bench
                                            </th>
                                            <th>
                                                Case No.
                                            </th>
                                            <th>
                                                Judgement Date
                                                / Judgement Challenged
                                                / Judgement Type
                                            </th>
                                            <th>
                                                Vehicle No.
                                            </th>
                                            <th>
                                                Status
                                            </th>
                                            <th>
                                                Stage
                                            </th>
                                            <th>
                                                Linked with Case and Date
                                            </th>
                                            <th>
                                                Connected with Case and Date
                                            </th>

                                        </tr>

                                    </thead>
                                    <tbody>
                                        <?php $sn3 = 0;
                                        if (!empty($vehicle_data_pending)) {
                                            foreach ($vehicle_data_pending as $row) {
                                                $sn3++;
                                        ?>
                                                <tr>
                                                    <input type="hidden" name="hd_link<?php echo $sn3; ?>" id="hd_link<?php echo $sn3; ?>" value="<?php echo $row['c_diary']; ?>" />
                                                    <td><?php echo $sn3; ?></td>
                                                    <td> <?php if ($row['res_connect_case'] == '' && $res_listed <= 0 && $row['res_listed1'] <= 0 && $row['c_status'] == 'P') { ?>
                                                            <input type="button" name="btnlink_<?php echo $sn3; ?>" id="btnlink_<?php echo $sn3; ?>" value="Link" class="cl_link btn-success btn" disabled />
                                                        <?php } ?>
                                                    </td>
                                                    <td>
                                                        <div class="btn btn-success btn-sm" type="button" onclick="viewcasestatus('<?php echo $row['c_diary']; ?>')"> <?php echo substr($row['c_diary'], 0, -4) . '-' .  substr($row['c_diary'], -4); ?></div>
                                                        <?php
                                                        $case_no = $row['short_description'];
                                                        if ($row['fil_no'] != '')
                                                            $case_no = $case_no . '/' . $row['fil_no'];
                                                        if ($row['fil_dt'] != '')
                                                            $case_no = $case_no . '/' . $row['fil_dt'];
                                                        //            echo $row['short_description'].'/'.$row['fil_no'].'/'.$row['fil_dt'];
                                                        if ($case_no == '')
                                                            echo "-";
                                                        else
                                                            echo $case_no;
                                                        ?>
                                                    </td><input type="hidden" name="hd_link<?php echo $sn3; ?>" id="hd_link<?php echo $sn3; ?>" value="<?php echo $row['c_diary']; ?>" />
                                                    <td> <?php echo $row['court_name'] ?> /
                                                        <?php
                                                        echo $row['name'];
                                                        ?>
                                                        <?php
                                                        echo $row['agency_name'];
                                                        ?>
                                                    </td>
                                                    <td> <?php
                                                            echo $row['type_sname'] . '-' . $row['lct_caseno'] . '-' . $row['lct_caseyear'];
                                                            ?></td>
                                                    <td> <?php if ($row['lct_dec_dt'] != '') echo date('d-m-Y', strtotime($row['lct_dec_dt'])); ?>
                                                        / <?php
                                                            echo $row['is_order_challenged'];
                                                            ?>
                                                        <?php
                                                        if ($row['full_interim_flag'] == 'F')
                                                            echo "Final";
                                                        else 
                                                    if ($row['full_interim_flag'] == 'I')
                                                            echo "Interim";
                                                        ?>
                                                    </td>
                                                    <td><?php echo $row['code'] . '-' . $row['vehicle_no']; ?></td>
                                                    <td> <?php

                                                            if ($row['c_status'] == 'P')
                                                                echo "Pending";
                                                            else
                                                                echo "Disposed";

                                                            ?></td>
                                                    <td><?php if (!empty($current_stage))
                                                            echo $current_stage['description'];
                                                        else {
                                                            echo '';
                                                        }; ?></td>
                                                    <td><?php if ($row['res_linked'] == 0) echo '-';
                                                        else echo (!empty($row['res_linked'])) ?  substr(trim($row['res_linked']), 0, -4) . '-' .  substr(trim($row['res_linked']), -4) : ''; ?></td>
                                                        <td><?php echo (!empty($row['res_connect_case'])) ? substr($row['res_connect_case'], 0, -4) . '-' .  substr($row['res_connect_case'], -4) : ''; ?></td>                                                        
                                                </tr>
                                        <?php }
                                        }
                                        ?>
                                        <?php if (!empty($vehicle_data_disposed)) {
                                            foreach ($vehicle_data_disposed as $row) {
                                                $sn3++;
                                        ?>
                                                <tr>
                                                    <td><?php echo $sn3; ?></td>
                                                    <td> <?php if ($row['res_connect_case'] == '' && $res_listed <= 0 && $row['res_listed1'] <= 0 && $row['c_status'] == 'P') { ?>
                                                            <input type="button" name="btnlink_<?php echo $sn3; ?>" id="btnlink_<?php echo $sn3; ?>" value="Link" class="cl_link btn-success btn" disabled />
                                                        <?php } ?>
                                                    </td>
                                                    <td>
                                                        <div class="btn btn-success btn-sm" type="button" onclick="viewcasestatus('<?php echo $row['c_diary']; ?>')"> <?php echo substr($row['c_diary'], 0, -4) . '-' .  substr($row['c_diary'], -4); ?></div>
                                                        <?php
                                                        $case_no = $row['short_description'];
                                                        if ($row['fil_no'] != '')
                                                            $case_no = $case_no . '/' . $row['fil_no'];
                                                        if ($row['fil_dt'] != '')
                                                            $case_no = $case_no . '/' . $row['fil_dt'];
                                                        //            echo $row['short_description'].'/'.$row['fil_no'].'/'.$row['fil_dt'];
                                                        if ($case_no == '')
                                                            echo "-";
                                                        else
                                                            echo $case_no;
                                                        ?>
                                                    </td><input type="hidden" name="hd_link<?php echo $sn3; ?>" id="hd_link<?php echo $sn3; ?>" value="<?php echo $row['c_diary']; ?>" />
                                                    <td> <?php echo $row['court_name'] ?> /
                                                        <?php
                                                        echo $row['name'];
                                                        ?>
                                                        <?php
                                                        echo $row['agency_name'];
                                                        ?>
                                                    </td>
                                                    <td> <?php
                                                            echo $row['type_sname'] . '-' . $row['lct_caseno'] . '-' . $row['lct_caseyear'];
                                                            ?></td>
                                                    <td> <?php if ($row['lct_dec_dt'] != '') echo date('d-m-Y', strtotime($row['lct_dec_dt'])); ?>
                                                        / <?php
                                                            echo $row['is_order_challenged'];
                                                            ?>
                                                        <?php
                                                        if ($row['full_interim_flag'] == 'F')
                                                            echo "Final";
                                                        else 
                                                    if ($row['full_interim_flag'] == 'I')
                                                            echo "Interim";
                                                        ?>
                                                    </td>
                                                    <td><?php echo $row['code'] . '-' . $row['vehicle_no']; ?></td>
                                                    <td> <?php

                                                            if ($row['c_status'] == 'P')
                                                                echo "Pending";
                                                            else
                                                                echo "Disposed";

                                                            ?></td>
                                                    <td><?php if (!empty($current_stage))
                                                            echo $current_stage['description'];
                                                        else {
                                                            echo '';
                                                        }; ?></td>
                                                    <td><?php if ($row['res_linked'] == 0) echo '-';
                                                        else echo (!empty($row['res_linked'])) ?  substr(trim($row['res_linked']), 0, -4) . '-' .  substr(trim($row['res_linked']), -4) : ''; ?></td>
                                                        <td><?php echo (!empty($row['res_connect_case'])) ? substr($row['res_connect_case'], 0, -4) . '-' .  substr($row['res_connect_case'], -4) : ''; ?></td>                                                        
                                                </tr>
                                        <?php }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                                <?php else : ?>
                                    <p style="text-align: center; color:red">No records found.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="p-2" style="background-color: #fff;">
                                <h4 class="basic_heading"> Similarities based on Court, State, District and Reference No. </h4>
                            </div>
                            <div class="card-body">
                                <?php if (!empty($reference_similarity) || !empty($reference_similarity_disposed)) : ?>

                                    <table id="lowercourtdata5" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th style="width:10%;">
                                                    S.No.
                                                </th>
                                                <th style="width:10%;">
                                                    Link To Diary No
                                                </th>
                                                <th>
                                                    Diary No.
                                                    /
                                                    Registration No.
                                                </th>
                                                <th>
                                                    From Court/
                                                    State /
                                                    Bench
                                                </th>
                                                <th>
                                                    Case No.
                                                </th>
                                                <th>
                                                    Judgement Date
                                                    /Judgement Challenged
                                                    /Judgement Type
                                                </th>
                                                <th>
                                                    Reference Court / State / District / No.
                                                </th>
                                                <th>
                                                    Status
                                                </th>
                                                <th>
                                                    Stage
                                                </th>
                                                <th>
                                                    Linked with Case and Date
                                                </th>
                                                <th>
                                                    Connected with Case and Date
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $sn4 = 0;
                                            if (!empty($reference_similarity)) {

                                                foreach ($reference_similarity as $row) {
                                                    $sn4++;
                                            ?>
                                                    <tr>
                                                        <input type="hidden" name="hd_link<?php echo $sn4; ?>" id="hd_link<?php echo $sn4; ?>" value="<?php echo $row['c_diary']; ?>" />
                                                        <td> <?php echo $sn4; ?></td>
                                                        <td> <?php if ($row['res_connect_case'] == '' && $res_listed <= 0 && $row['res_listed1'] <= 0 && $row['c_status'] == 'P') { ?>
                                                                <input type="button" name="btnlink_<?php echo $sn4; ?>" id="btnlink_<?php echo $sn4; ?>" value="Link" class="cl_link btn-success btn" disabled />
                                                            <?php } ?>
                                                        </td>
                                                        <td>
                                                            <div class="btn btn-success btn-sm" type="button" onclick="viewcasestatus('<?php echo $row['c_diary']; ?>')"> <?php echo substr($row['c_diary'], 0, -4) . '-' .  substr($row['c_diary'], -4); ?></div>
                                                            <?php
                                                            $case_no = $row['short_description'];
                                                            if ($row['fil_no'] != '')
                                                                $case_no = $case_no . '/' . $row['fil_no'];
                                                            if ($row['fil_dt'] != '')
                                                                $case_no = $case_no . '/' . $row['fil_dt'];

                                                            if ($case_no == '')
                                                                echo "-";
                                                            else
                                                                echo $case_no;
                                                            ?>
                                                        </td>
                                                        <td> <?php echo $row['court_name']; ?>
                                                            /<?php
                                                                echo $row['name'];
                                                                ?>
                                                            /<?php
                                                                echo $row['agency_name'];
                                                                ?></td>
                                                        <td> <?php
                                                                echo $row['type_sname'] . '-' . $row['lct_caseno'] . '-' . $row['lct_caseyear'];
                                                                ?></td>
                                                        <td> <?php echo date('d-m-Y', strtotime($row['lct_dec_dt'])); ?>/<?php
                                                                                                                            echo $row['is_order_challenged'];
                                                                                                                            ?>/ <?php
                                                                                                                            if ($row['full_interim_flag'] == 'F')
                                                                                                                                echo "Final";
                                                                                                                            else 
                                                if ($row['full_interim_flag'] == 'I')
                                                                                                                                echo "Interim";
                                                                                                                            ?></td>
                                                        <td><?php echo $row['court_name']; ?>
                                                            /<?php
                                                                echo $row['name'];
                                                                ?>
                                                            /<?php
                                                                echo $row['agency_name'] . ' / ' . $row['type_sname'] . '-' . $row['lct_caseno'] . '-' . $row['lct_caseyear'];
                                                                ?></td>
                                                        <td><?php

                                                            if ($row['c_status'] == 'P')
                                                                echo "Pending";
                                                            else
                                                                echo "Disposed";

                                                            ?></td>
                                                        <td><?php if (!empty($current_stage))
                                                                echo $current_stage['description'];
                                                            else {
                                                                echo '';
                                                            }; ?></td>
                                                        <td><?php if ($row['res_linked'] == 0) echo '-';
                                                            else echo (!empty($row['res_linked'])) ?  substr(trim($row['res_linked']), 0, -4) . '-' .  substr(trim($row['res_linked']), -4) : ''; ?></td>
                                                        <td><?php echo (!empty($row['res_connect_case'])) ? substr($row['res_connect_case'], 0, -4) . '-' .  substr($row['res_connect_case'], -4) : ''; ?></td>                                                             

                                                    </tr>
                                            <?php }
                                            } ?>
                                            <?php if (!empty($reference_similarity_disposed)) {

                                                foreach ($reference_similarity_disposed as $row) {
                                                    $sn4++;
                                            ?>
                                                    <tr>
                                                        <input type="hidden" name="hd_link<?php echo $sn4; ?>" id="hd_link<?php echo $sn4; ?>" value="<?php echo $row['c_diary']; ?>" />
                                                        <td> <?php echo $sn4; ?></td>
                                                        <td> <?php if ($row['res_connect_case'] == '' && $res_listed <= 0 && $row['res_listed1'] <= 0 && $row['c_status'] == 'P') { ?>
                                                                <input type="button" name="btnlink_<?php echo $sn4; ?>" id="btnlink_<?php echo $sn4; ?>" value="Link" class="cl_link btn-success btn" disabled />
                                                            <?php } ?>
                                                        </td>
                                                        <td>
                                                            <div class="btn btn-success btn-sm" type="button" onclick="viewcasestatus('<?php echo $row['c_diary']; ?>')"> <?php echo substr($row['c_diary'], 0, -4) . '-' .  substr($row['c_diary'], -4); ?></div>
                                                            <?php
                                                            $case_no = $row['short_description'];
                                                            if ($row['fil_no'] != '')
                                                                $case_no = $case_no . '/' . $row['fil_no'];
                                                            if ($row['fil_dt'] != '')
                                                                $case_no = $case_no . '/' . $row['fil_dt'];

                                                            if ($case_no == '')
                                                                echo "-";
                                                            else
                                                                echo $case_no;
                                                            ?>
                                                        </td>
                                                        <td> <?php echo $row['court_name']; ?>/<?php
                                                                                                echo $row['name'];
                                                                                                ?>/
                                                            <?php
                                                            echo $row['agency_name'];
                                                            ?></td>
                                                        <td> <?php
                                                                echo $row['type_sname'] . '-' . $row['lct_caseno'] . '-' . $row['lct_caseyear'];
                                                                ?></td>
                                                        <td> <?php echo date('d-m-Y', strtotime($row['lct_dec_dt'])); ?>/
                                                            <?php
                                                            echo $row['is_order_challenged'];
                                                            ?>/ <?php
                                                            if ($row['full_interim_flag'] == 'F')
                                                                echo "Final";
                                                            else 
                                                    if ($row['full_interim_flag'] == 'I')
                                                                echo "Interim";
                                                            ?></td>
                                                        <td><?php echo $row['court_name']; ?>
                                                            /<?php
                                                                echo $row['name'];
                                                                ?>
                                                            /<?php
                                                                echo $row['agency_name'] . ' / ' . $row['type_sname'] . '-' . $row['lct_caseno'] . '-' . $row['lct_caseyear'];
                                                                ?></td>
                                                        <td><?php

                                                            if ($row['c_status'] == 'P')
                                                                echo "Pending";
                                                            else
                                                                echo "Disposed";

                                                            ?></td>
                                                        <td><?php if (!empty($current_stage))
                                                                echo $current_stage['description'];
                                                            else {
                                                                echo '';
                                                            }; ?></td>
                                                        <td><?php if ($row['res_linked'] == 0) echo '-';
                                                            else echo (!empty($row['res_linked'])) ? substr(trim($row['res_linked']), 0, -4) . '-' .  substr(trim($row['res_linked']), -4) : ''; ?></td>
                                                        <td><?php echo (!empty($row['res_connect_case'])) ? substr($row['res_connect_case'], 0, -4) . '-' .  substr($row['res_connect_case'], -4) : ''; ?></td>

                                                    </tr>
                                            <?php }
                                            } ?>
                                        </tbody>
                                    </table>
                                <?php else : ?>
                                    <p style="text-align: center; color:red">No records found.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="p-2" style="background-color: #fff;">
                                <h4 class="basic_heading"> Similarities based on Government Notification state, No., Date </h4>
                            </div>
                            <div class="card-body">
                                <?php if (!empty($govt_notification_similarity) || !empty($govt_notification_similarity_disposed)) : ?>

                                    <table id="lowercourtdata6" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th style="width:10%;">
                                                    S.No.
                                                </th>
                                                <th style="width:10%;">
                                                    Link To Diary No
                                                </th>
                                                <th>
                                                    Diary No.
                                                    / Registration No.
                                                </th>
                                                <th>
                                                    From Court
                                                    / State
                                                    / Bench
                                                </th>
                                                <th>
                                                    Case No.
                                                </th>
                                                <th>
                                                    Judgement date
                                                    / Judgement Challenged
                                                    / Judgement Type
                                                </th>
                                                <th>
                                                    Government Notification State / No. / Date
                                                </th>
                                                <th>
                                                    Status
                                                </th>
                                                <th>
                                                    Stage
                                                </th>
                                                <th>
                                                    Linked with Case and Date
                                                </th>
                                                <th>
                                                    Connected with Case and Date
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $sn5 = 0;
                                            if (!empty($govt_notification_similarity)) {

                                                foreach ($govt_notification_similarity as $row) {
                                                    $sn5++;
                                            ?>
                                                    <tr>
                                                        <td><?php echo $sn5; ?></td>
                                                        <input type="hidden" name="hd_link<?php echo $sn5; ?>" id="hd_link<?php echo $sn5; ?>" value="<?php echo $row['c_diary']; ?>" />
                                                        <td><?php if ($row['res_connect_case'] == '' && $res_listed <= 0 && $row['res_listed1'] <= 0 && $row['c_status'] == 'P') { ?>
                                                                <input type="button" name="btnlink_<?php echo $sn5; ?>" id="btnlink_<?php echo $sn5; ?>" value="Link" class="cl_link btn-success btn" disabled />
                                                            <?php } ?>
                                                        </td>
                                                        <td>
                                                            <div class="btn btn-success btn-sm" type="button" onclick="viewcasestatus('<?php echo $row['c_diary']; ?>')"> <?php echo substr($row['c_diary'], 0, -4) . '-' .  substr($row['c_diary'], -4); ?></div>
                                                            <?php
                                                            $case_no = $row['short_description'];
                                                            if ($row['fil_no'] != '')
                                                                $case_no = $case_no . '/' . $row['fil_no'];
                                                            if ($row['fil_dt'] != '')
                                                                $case_no = $case_no . '/' . $row['fil_dt'];

                                                            if ($case_no == '')
                                                                echo "-";
                                                            else
                                                                echo $case_no;
                                                            ?>
                                                        </td>
                                                        <td><?php echo $row['court_name']; ?><?php
                                                                                                echo $row['name'];
                                                                                                ?><?php
                                                                                                echo $row['agency_name'];
                                                                                                ?></td>
                                                        <td><?php
                                                            echo $row['type_sname'] . '-' . $row['lct_caseno'] . '-' . $row['lct_caseyear'];
                                                            ?></td>
                                                        <td> <?php echo date('d-m-Y', strtotime($row['lct_dec_dt'])); ?>
                                                            <?php
                                                            echo $row['is_order_challenged'];
                                                            ?>/<?php
                                                            if ($row['full_interim_flag'] == 'F')
                                                                echo "Final";
                                                            else 
                                                if ($row['full_interim_flag'] == 'I')
                                                                echo "Interim";
                                                            ?></td>
                                                        <td><?php if ($row['gov_not_state_id'] == 0) {
                                                                echo "-";
                                                            } else {
                                                                echo $row['govt_state_name'];
                                                            } ?> / <?php if ($row['gov_not_case_type'] == '') {
                                                                    echo '';
                                                                } else {
                                                                    echo $row['gov_not_case_type'];
                                                                } ?>-<?php if ($row['gov_not_case_no'] == 0) {
                                                                            echo '';
                                                                        } else {
                                                                            echo $row['gov_not_case_no'];
                                                                        } ?>-<?php if ($row['gov_not_case_year'] == 0) {
                                                                                                                                                                            echo '';
                                                                                                                                                                        } else {
                                                                                                                                                                            echo $row['gov_not_case_year'];
                                                                                                                                                                        } ?> / <?php if ($row['gov_not_date'] == '0000-00-00') {
                                                                                                                                                                                                                                                                            echo '-';
                                                                                                                                                                                                                                                                        } else {
                                                                                                                                                                                                                                                                            echo date('d-m-Y', strtotime($row['gov_not_date']));
                                                                                                                                                                                                                                                                        } ?></td>
                                                        <td><?php

                                                            if ($row['c_status'] == 'P')
                                                                echo "Pending";
                                                            else
                                                                echo "Disposed";

                                                            ?></td>
                                                        <td><?php if (!empty($current_stage))
                                                                echo $current_stage['description'];
                                                            else {
                                                                echo '';
                                                            }; ?></td>
                                                        <td><?php if ($row['res_linked'] == 0) echo '-';
                                                            else echo (!empty($row['res_linked'])) ?  substr(trim($row['res_linked']), 0, -4) . '-' .  substr(trim($row['res_linked']), -4) : ''; ?></td>
                                                        <td><?php echo (!empty($row['res_connect_case'])) ? substr($row['res_connect_case'], 0, -4) . '-' .  substr($row['res_connect_case'], -4) : ''; ?></td>                                                             
                                                    </tr>
                                            <?php }
                                            } ?>
                                            <?php if (!empty($govt_notification_similarity_disposed)) {

                                                foreach ($govt_notification_similarity_disposed as $row) {
                                                    $sn5++;
                                            ?>
                                                    <tr>
                                                        <td><?php echo $sn5; ?></td>
                                                        <input type="hidden" name="hd_link<?php echo $sn5; ?>" id="hd_link<?php echo $sn5; ?>" value="<?php echo $row['c_diary']; ?>" />
                                                        <td><?php if ($row['res_connect_case'] == '' && $res_listed <= 0 && $row['res_listed1'] <= 0 && $row['c_status'] == 'P') { ?>
                                                                <input type="button" name="btnlink_<?php echo $sn5; ?>" id="btnlink_<?php echo $sn5; ?>" value="Link" class="cl_link btn-success btn" disabled />
                                                            <?php } ?>
                                                        </td>
                                                        <td>
                                                            <div class="btn btn-success btn-sm" type="button" onclick="viewcasestatus('<?php echo $row['c_diary']; ?>')"> <?php echo substr($row['c_diary'], 0, -4) . '-' .  substr($row['c_diary'], -4); ?></div>
                                                            <?php
                                                            $case_no = $row['short_description'];
                                                            if ($row['fil_no'] != '')
                                                                $case_no = $case_no . '/' . $row['fil_no'];
                                                            if ($row['fil_dt'] != '')
                                                                $case_no = $case_no . '/' . $row['fil_dt'];

                                                            if ($case_no == '')
                                                                echo "-";
                                                            else
                                                                echo $case_no;
                                                            ?>
                                                        </td>
                                                        <td><?php echo $row['court_name']; ?><?php
                                                                                                echo $row['name'];
                                                                                                ?><?php
                                                                                                echo $row['agency_name'];
                                                                                                ?></td>
                                                        <td><?php
                                                            echo $row['type_sname'] . '-' . $row['lct_caseno'] . '-' . $row['lct_caseyear'];
                                                            ?></td>
                                                        <td> <?php echo date('d-m-Y', strtotime($row['lct_dec_dt'])); ?>
                                                            <?php
                                                            echo $row['is_order_challenged'];
                                                            ?>/<?php
                                                            if ($row['full_interim_flag'] == 'F')
                                                                echo "Final";
                                                            else 
                                                if ($row['full_interim_flag'] == 'I')
                                                                echo "Interim";
                                                            ?></td>
                                                        <td><?php if ($row['gov_not_state_id'] == 0) {
                                                                echo "-";
                                                            } else {
                                                                echo $row['govt_state_name'];
                                                            } ?> / <?php if ($row['gov_not_case_type'] == '') {
                                                                    echo '';
                                                                } else {
                                                                    echo $row['gov_not_case_type'];
                                                                } ?>-<?php if ($row['gov_not_case_no'] == 0) {
                                                                            echo '';
                                                                        } else {
                                                                            echo $row['gov_not_case_no'];
                                                                        } ?>-<?php if ($row['gov_not_case_year'] == 0) {
                                                                                                                                                                            echo '';
                                                                                                                                                                        } else {
                                                                                                                                                                            echo $row['gov_not_case_year'];
                                                                                                                                                                        } ?> / <?php if ($row['gov_not_date'] == '0000-00-00') {
                                                                                                                                                                                                                                                                            echo '-';
                                                                                                                                                                                                                                                                        } else {
                                                                                                                                                                                                                                                                            echo date('d-m-Y', strtotime($row['gov_not_date']));
                                                                                                                                                                                                                                                                        } ?></td>
                                                        <td><?php

                                                            if ($row['c_status'] == 'P')
                                                                echo "Pending";
                                                            else
                                                                echo "Disposed";

                                                            ?></td>
                                                        <td><?php if (!empty($current_stage))
                                                                echo $current_stage['description'];
                                                            else {
                                                                echo '';
                                                            }; ?></td>
                                                        <td><?php if ($row['res_linked'] == 0) echo '-';
                                                        else echo (!empty($row['res_linked'])) ?  substr(trim($row['res_linked']), 0, -4) . '-' .  substr(trim($row['res_linked']), -4) : ''; ?></td>
                                                        <td><?php echo (!empty($row['res_connect_case'])) ? substr($row['res_connect_case'], 0, -4) . '-' .  substr($row['res_connect_case'], -4) : ''; ?></td>                                                            
                                                    </tr>
                                            <?php }
                                            } ?>
                                        </tbody>
                                    </table>
                                <?php else : ?>
                                    <p style="text-align: center; color:red">No records found.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="p-2" style="background-color: #fff;">
                                <h4 class="basic_heading"> Similarities based on Relied Upon Court, State, District and No. </h4>
                            </div>
                            <div class="card-body">
                                <?php if (!empty($relied_details_pending) || !empty($relied_details_disposed)) : ?>

                                    <table id="lowercourtdata7" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th style="width: 10%;">
                                                    S.No.
                                                </th>
                                                <th style="width: 10%;">
                                                    Link To Diary No
                                                </th>
                                                <th>
                                                    Diary No.
                                                    / Registration No.
                                                </th>
                                                <th>
                                                    From Court
                                                    / State
                                                    / Bench
                                                </th>
                                                <th>
                                                    Case No.
                                                </th>
                                                <th>
                                                    Judgement Date
                                                    / Judgement Challenged
                                                    / Judgement Type
                                                </th>
                                                <th>
                                                    Relied Upon Court / State / District / No.
                                                </th>
                                                <th>
                                                    Status
                                                </th>
                                                <th>
                                                    Stage
                                                </th>
                                                <th>
                                                    Linked with Case and Date
                                                </th>
                                                <th>
                                                    Connected with Case and Date
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $sn6 = 0;
                                            if (!empty($relied_details_pending)) {

                                                foreach ($relied_details_pending as $row) {
                                                    $sn6++;
                                            ?>
                                                    <tr>
                                                        <input type="hidden" name="hd_link<?php echo $sn6; ?>" id="hd_link<?php echo $sn6; ?>" value="<?php echo $row['c_diary']; ?>" />
                                                        <td> <?php echo $sn6; ?></td>
                                                        <td> <?php if ($row['res_connect_case'] == '' && $res_listed <= 0 && $row['res_listed1'] <= 0 && $row['c_status'] == 'P') { ?>
                                                                <input type="button" name="btnlink_<?php echo $sn6; ?>" id="btnlink_<?php echo $sn6; ?>" value="Link" class="cl_link btn-success btn" disabled />
                                                            <?php } ?>
                                                        </td>
                                                        <td>
                                                            <div class="btn btn-success btn-sm" type="button" onclick="viewcasestatus('<?php echo $row['c_diary']; ?>')"> <?php echo substr($row['c_diary'], 0, -4) . '-' .  substr($row['c_diary'], -4); ?></div> /
                                                            <?php
                                                            $case_no = $row['short_description'];
                                                            if ($row['fil_no'] != '')
                                                                $case_no = $case_no . '/' . $row['fil_no'];
                                                            if ($row['fil_dt'] != '')
                                                                $case_no = $case_no . '/' . $row['fil_dt'];

                                                            if ($case_no == '')
                                                                echo "-";
                                                            else
                                                                echo $case_no;
                                                            ?>
                                                        </td>
                                                        <td> <?php echo $row['court_name']; ?>
                                                            /<?php
                                                                echo $row['name'];
                                                                ?>
                                                            /<?php
                                                                echo $row['agency_name'];
                                                                ?></td>
                                                        <td> <?php
                                                                echo $row['type_sname'] . '-' . $row['lct_caseno'] . '-' . $row['lct_caseyear'];
                                                                ?></td>
                                                        <td> <?php echo date('d-m-Y', strtotime($row['lct_dec_dt'])); ?>/<?php
                                                                                                                            echo $row['is_order_challenged'];
                                                                                                                            ?>/ <?php
                                                                                                                            if ($row['full_interim_flag'] == 'F')
                                                                                                                                echo "Final";
                                                                                                                            else 
                                                if ($row['full_interim_flag'] == 'I')
                                                                                                                                echo "Interim";
                                                                                                                            ?></td>
                                                        <td><?php echo $row['court_name']; ?>
                                                            /<?php
                                                                echo $row['name'];
                                                                ?>
                                                            /<?php
                                                                echo $row['agency_name'] . ' / ' . $row['type_sname'] . '-' . $row['lct_caseno'] . '-' . $row['lct_caseyear'];
                                                                ?></td>
                                                        <td><?php

                                                            if ($row['c_status'] == 'P')
                                                                echo "Pending";
                                                            else
                                                                echo "Disposed";

                                                            ?></td>
                                                        <td><?php if (!empty($current_stage))
                                                                echo $current_stage['description'];
                                                            else {
                                                                echo '';
                                                            }; ?></td>
                                                        <td><?php if ($row['res_linked'] == 0) echo '-';
                                                            else echo (!empty($row['res_linked'])) ?  substr(trim($row['res_linked']), 0, -4) . '-' .  substr(trim($row['res_linked']), -4) : ''; ?></td>
                                                        <td><?php echo (!empty($row['res_connect_case'])) ? substr($row['res_connect_case'], 0, -4) . '-' .  substr($row['res_connect_case'], -4) : ''; ?></td>                                                            

                                                    </tr>
                                            <?php }
                                            } ?>
                                            <?php
                                            if (!empty($relied_details_disposed)) {

                                                foreach ($relied_details_disposed as $row) {
                                                    $sn6++;
                                            ?>
                                                    <tr>
                                                        <input type="hidden" name="hd_link<?php echo $sn6; ?>" id="hd_link<?php echo $sn6; ?>" value="<?php echo $row['c_diary']; ?>" />
                                                        <td> <?php echo $sn6; ?></td>
                                                        <td> <?php if ($row['res_connect_case'] == '' && $res_listed <= 0 && $row['res_listed1'] <= 0 && $row['c_status'] == 'P') { ?>
                                                                <input type="button" name="btnlink_<?php echo $sn6; ?>" id="btnlink_<?php echo $sn6; ?>" value="Link" class="cl_link btn-success btn" disabled />
                                                            <?php } ?>
                                                        </td>
                                                        <td>
                                                            <div class="btn btn-success btn-sm" type="button" onclick="viewcasestatus('<?php echo $row['c_diary']; ?>')"> <?php echo substr($row['c_diary'], 0, -4) . '-' .  substr($row['c_diary'], -4); ?></div> /
                                                            <?php
                                                            $case_no = $row['short_description'];
                                                            if ($row['fil_no'] != '')
                                                                $case_no = $case_no . '/' . $row['fil_no'];
                                                            if ($row['fil_dt'] != '')
                                                                $case_no = $case_no . '/' . $row['fil_dt'];

                                                            if ($case_no == '')
                                                                echo "-";
                                                            else
                                                                echo $case_no;
                                                            ?>
                                                        </td>
                                                        <td> <?php echo $row['court_name']; ?>
                                                            /<?php
                                                                echo $row['name'];
                                                                ?>
                                                            /<?php
                                                                echo $row['agency_name'];
                                                                ?></td>
                                                        <td> <?php
                                                                echo $row['type_sname'] . '-' . $row['lct_caseno'] . '-' . $row['lct_caseyear'];
                                                                ?></td>
                                                        <td> <?php echo date('d-m-Y', strtotime($row['lct_dec_dt'])); ?>/<?php
                                                                                                                            echo $row['is_order_challenged'];
                                                                                                                            ?>/ <?php
                                                                                                                            if ($row['full_interim_flag'] == 'F')
                                                                                                                                echo "Final";
                                                                                                                            else 
                                                if ($row['full_interim_flag'] == 'I')
                                                                                                                                echo "Interim";
                                                                                                                            ?></td>
                                                        <td><?php echo $row['court_name']; ?>
                                                            /<?php
                                                                echo $row['name'];
                                                                ?>
                                                            /<?php
                                                                echo $row['agency_name'] . ' / ' . $row['type_sname'] . '-' . $row['lct_caseno'] . '-' . $row['lct_caseyear'];
                                                                ?></td>
                                                        <td><?php

                                                            if ($row['c_status'] == 'P')
                                                                echo "Pending";
                                                            else
                                                                echo "Disposed";

                                                            ?></td>
                                                        <td><?php if (!empty($current_stage))
                                                                echo $current_stage['description'];
                                                            else {
                                                                echo '';
                                                            }; ?></td>
                                                        <td><?php if ($row['res_linked'] == 0) echo '-';
                                                            else echo (!empty($row['res_linked'])) ?  substr(trim($row['res_linked']), 0, -4) . '-' .  substr(trim($row['res_linked']), -4) : ''; ?></td>
                                                        <td><?php echo (!empty($row['res_connect_case'])) ? substr($row['res_connect_case'], 0, -4) . '-' .  substr($row['res_connect_case'], -4) : ''; ?></td>                                                            

                                                    </tr>
                                            <?php }
                                            } ?>
                                        </tbody>
                                    </table>
                                <?php else : ?>
                                    <p style="text-align: center; color:red">No records found.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="p-2" style="background-color: #fff;">
                                <h4 class="basic_heading">Similarities based on Transfer Court, State, District and No. </h4>
                            </div>
                            <div class="card-body">
                                <?php if (!empty($transfer_details_pending) || !empty($transfer_details_disposed)) : ?>

                                    <table id="lowercourtdata1" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th style="width:10%">
                                                    S.No.
                                                </th>
                                                <th style="width:10%">
                                                    Link To Diary No
                                                </th>
                                                <th>
                                                    Diary No.
                                                    / Registration No.
                                                </th>
                                                <th>
                                                    From Court
                                                    / State
                                                    / Bench
                                                </th>
                                                <th>
                                                    Transfer from Case No.
                                                </th>
                                                <th>
                                                    Judgement Date
                                                    / Judgement Challenged
                                                    / Judgement Type
                                                </th>
                                                <th>
                                                    Transfer To State / District
                                                </th>
                                                <th>
                                                    Status
                                                </th>
                                                <th>
                                                    Stage
                                                </th>
                                                <th>
                                                    Linked with Case and Date
                                                </th>
                                                <th>
                                                    Connected with Case and Date
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $sn7 = 0;
                                            if (!empty($transfer_details_pending)) {

                                                foreach ($transfer_details_pending as $row) {
                                                    $sn7++;
                                            ?>
                                                    <tr>
                                                        <input type="hidden" name="hd_link<?php echo $sn7; ?>" id="hd_link<?php echo $sn7; ?>" value="<?php echo $row['c_diary']; ?>" />
                                                        <td> <?php echo $sn7; ?></td>
                                                        <td> <?php if ($row['res_connect_case'] == '' && $res_listed <= 0 && $row['res_listed1'] <= 0 && $row['c_status'] == 'P') { ?>
                                                                <input type="button" name="btnlink_<?php echo $sn7; ?>" id="btnlink_<?php echo $sn7; ?>" value="Link" class="cl_link btn-success btn" disabled />
                                                            <?php } ?>
                                                        </td>
                                                        <td>
                                                            <div class="btn btn-success btn-sm" type="button" onclick="viewcasestatus('<?php echo $row['c_diary']; ?>')"> <?php echo substr($row['c_diary'], 0, -4) . '-' .  substr($row['c_diary'], -4); ?></div> /
                                                            <?php
                                                            $case_no = $row['short_description'];
                                                            if ($row['fil_no'] != '')
                                                                $case_no = $case_no . '/' . $row['fil_no'];
                                                            if ($row['fil_dt'] != '')
                                                                $case_no = $case_no . '/' . $row['fil_dt'];

                                                            if ($case_no == '')
                                                                echo "-";
                                                            else
                                                                echo $case_no;
                                                            ?>
                                                        </td>
                                                        <td> <?php echo $row['court_name']; ?>
                                                            /<?php
                                                                echo $row['name'];
                                                                ?>
                                                            /<?php
                                                                echo $row['agency_name'];
                                                                ?></td>
                                                        <td> <?php
                                                                echo $row['type_sname'] . '-' . $row['lct_caseno'] . '-' . $row['lct_caseyear'];
                                                                ?></td>
                                                        <td> <?php echo date('d-m-Y', strtotime($row['lct_dec_dt'])); ?>/<?php
                                                                                                                            echo $row['is_order_challenged'];
                                                                                                                            ?>/ <?php
                                                                                                                            if ($row['full_interim_flag'] == 'F')
                                                                                                                                echo "Final";
                                                                                                                            else 
                                                if ($row['full_interim_flag'] == 'I')
                                                                                                                                echo "Interim";
                                                                                                                            ?></td>
                                                        <td><?php echo $row['court_name']; ?>
                                                            /<?php
                                                                echo $row['name'];
                                                                ?>
                                                            /<?php
                                                                echo $row['agency_name'] . ' / ' . $row['type_sname'] . '-' . $row['lct_caseno'] . '-' . $row['lct_caseyear'];
                                                                ?></td>
                                                        <td><?php

                                                            if ($row['c_status'] == 'P')
                                                                echo "Pending";
                                                            else
                                                                echo "Disposed";

                                                            ?></td>
                                                        <td><?php if (!empty($current_stage))
                                                                echo $current_stage['description'];
                                                            else {
                                                                echo '';
                                                            }; ?></td>
                                                        <td><?php if ($row['res_linked'] == 0) echo '-';
                                                            else echo (!empty($row['res_linked'])) ?  substr(trim($row['res_linked']), 0, -4) . '-' .  substr(trim($row['res_linked']), -4) : ''; ?></td>
                                                        <td><?php echo (!empty($row['res_connect_case'])) ? substr($row['res_connect_case'], 0, -4) . '-' .  substr($row['res_connect_case'], -4) : ''; ?></td>                                                            

                                                    </tr>
                                            <?php }
                                            } ?>
                                            <?php
                                            if (!empty($transfer_details_disposed)) {

                                                foreach ($transfer_details_disposed as $row) {
                                                    $sn7++;
                                            ?>
                                                    <tr>
                                                        <input type="hidden" name="hd_link<?php echo $sn7; ?>" id="hd_link<?php echo $sn7; ?>" value="<?php echo $row['c_diary']; ?>" />
                                                        <td> <?php echo $sn7; ?></td>
                                                        <td> <?php if ($row['res_connect_case'] == '' && $res_listed <= 0 && $row['res_listed1'] <= 0 && $row['c_status'] == 'P') { ?>
                                                                <input type="button" name="btnlink_<?php echo $sn7; ?>" id="btnlink_<?php echo $sn7; ?>" value="Link" class="cl_link btn-success btn" disabled />
                                                            <?php } ?>
                                                        </td>
                                                        <td>
                                                            <div class="btn btn-success btn-sm" type="button" onclick="viewcasestatus('<?php echo $row['c_diary']; ?>')"> <?php echo substr($row['c_diary'], 0, -4) . '-' .  substr($row['c_diary'], -4); ?></div> /
                                                            <?php
                                                            $case_no = $row['short_description'];
                                                            if ($row['fil_no'] != '')
                                                                $case_no = $case_no . '/' . $row['fil_no'];
                                                            if ($row['fil_dt'] != '')
                                                                $case_no = $case_no . '/' . $row['fil_dt'];

                                                            if ($case_no == '')
                                                                echo "-";
                                                            else
                                                                echo $case_no;
                                                            ?>
                                                        </td>
                                                        <td> <?php echo $row['court_name']; ?>
                                                            /<?php
                                                                echo $row['name'];
                                                                ?>
                                                            /<?php
                                                                echo $row['agency_name'];
                                                                ?></td>
                                                        <td> <?php
                                                                echo $row['type_sname'] . '-' . $row['lct_caseno'] . '-' . $row['lct_caseyear'];
                                                                ?></td>
                                                        <td> <?php echo date('d-m-Y', strtotime($row['lct_dec_dt'])); ?>/<?php
                                                                                                                            echo $row['is_order_challenged'];
                                                                                                                            ?>/ <?php
                                                                                                                            if ($row['full_interim_flag'] == 'F')
                                                                                                                                echo "Final";
                                                                                                                            else 
                                                if ($row['full_interim_flag'] == 'I')
                                                                                                                                echo "Interim";
                                                                                                                            ?></td>
                                                        <td><?php echo $row['court_name']; ?>
                                                            /<?php
                                                                echo $row['name'];
                                                                ?>
                                                            /<?php
                                                                echo $row['agency_name'] . ' / ' . $row['type_sname'] . '-' . $row['lct_caseno'] . '-' . $row['lct_caseyear'];
                                                                ?></td>
                                                        <td><?php

                                                            if ($row['c_status'] == 'P')
                                                                echo "Pending";
                                                            else
                                                                echo "Disposed";

                                                            ?></td>
                                                        <td><?php if (!empty($current_stage))
                                                                echo $current_stage['description'];
                                                            else {
                                                                echo '';
                                                            }; ?></td>
                                                        <td><?php if ($row['res_linked'] == 0) echo '-';
                                                            else echo (!empty($row['res_linked'])) ?  substr(trim($row['res_linked']), 0, -4) . '-' .  substr(trim($row['res_linked']), -4) : ''; ?></td>
                                                        <td><?php echo (!empty($row['res_connect_case'])) ? substr($row['res_connect_case'], 0, -4) . '-' .  substr($row['res_connect_case'], -4) : ''; ?></td>                                                             

                                                    </tr>
                                            <?php }
                                            } ?>
                                        </tbody>
                                    </table>
                                <?php else : ?>
                                    <p style="text-align: center; color:red">No records found.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="p-2" style="background-color: #fff;">
                                <h4 class="basic_heading">Similarities based on Cause Title</h4>
                            </div>
                            <div class="card-body">
                                <?php if (!empty($pending_cause_title) || !empty($disposed_cause_title)) : ?>

                                    <table id="causetitle" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th style="width: 10%" ;>
                                                    S.No.
                                                </th>
                                                <th style="width: 10%">
                                                    Link To Diary No
                                                </th>
                                                <th style="width: 10%">
                                                    Diary No.
                                                </th>
                                                <th style="width: 20%">
                                                    Petitioner<br />Vs<br />Respondent
                                                </th>
                                                <th style="width: 10%">
                                                    Status
                                                </th>
                                                <th style="width: 15%">
                                                    Linked with Case and Date
                                                </th>
                                                <th style="width: 15%">
                                                    Connected with Case and Date
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $sn8 = 0; ?>
                                            <?php if (!empty($pending_cause_title)) {

                                                foreach ($pending_cause_title as $row) {
                                                    $sn8++;
                                            ?>
                                                    <tr>
                                                        <td><?php echo $sn8; ?></td>
                                                        <td><?php if ($row['res_connect_case'] == '' && $res_listed <= 0 && $row['res_listed1'] <= 0 && $row['c_status'] == 'P') { ?>
                                                                <input type="button" name="btnlink_<?php echo $sn8; ?>" id="btnlink_<?php echo $sn8; ?>" value="Link" class="cl_link btn-success btn" disabled />
                                                            <?php } ?>
                                                        </td>
                                                        <td>
                                                            <div class="btn btn-success btn-sm" type="button" onclick="viewcasestatus('<?php echo $row['c_diary']; ?>')"> <?php echo substr($row['c_diary'], 0, -4) . '-' .  substr($row['c_diary'], -4); ?></div>
                                                        </td><input type="hidden" name="hd_link<?php echo $sn8; ?>" id="hd_link<?php echo $sn8; ?>" value="<?php echo $row['c_diary']; ?>" />
                                                        <td><?php echo $row['pet_name']; ?>
                                                            VS
                                                            <?php echo $row['res_name']; ?>
                                                        </td>
                                                        <td><?php

                                                            if ($row['c_status'] == 'P')
                                                                echo "Pending";
                                                            else
                                                                echo "Disposed";

                                                            ?></td>
                                                        <td><?php if ($row['res_linked'] == 0) echo '-';
                                                            else echo (!empty($row['res_linked'])) ?  substr(trim($row['res_linked']), 0, -4) . '-' .  substr(trim($row['res_linked']), -4) : ''; ?></td>
                                                        <td><?php echo (!empty($row['res_connect_case'])) ? substr($row['res_connect_case'], 0, -4) . '-' .  substr($row['res_connect_case'], -4) : ''; ?></td>                                                            
                                                    </tr>
                                            <?php   }
                                            } ?>

                                            <?php if (!empty($disposed_cause_title)) {

                                                foreach ($disposed_cause_title as $row) {
                                                    $sn8++;
                                            ?>
                                                    <tr>
                                                        <td><?php echo $sn8; ?></td>
                                                        <td><?php if ($row['res_connect_case'] == '' && $res_listed <= 0 && $row['res_listed1'] <= 0 && $row['c_status'] == 'P') { ?>
                                                                <input type="button" name="btnlink_<?php echo $sn8; ?>" id="btnlink_<?php echo $sn8; ?>" value="Link" class="cl_link btn-success btn" disabled />
                                                            <?php } ?>
                                                        </td>
                                                        <td>
                                                            <div class="btn btn-success btn-sm" type="button" onclick="viewcasestatus('<?php echo $row['c_diary']; ?>')"> <?php echo substr($row['c_diary'], 0, -4) . '-' .  substr($row['c_diary'], -4); ?></div>
                                                        </td><input type="hidden" name="hd_link<?php echo $sn8; ?>" id="hd_link<?php echo $sn8; ?>" value="<?php echo $row['c_diary']; ?>" />
                                                        <td><?php echo $row['pet_name']; ?>
                                                            VS
                                                            <?php echo $row['res_name']; ?>
                                                        </td>
                                                        <td><?php

                                                            if ($row['c_status'] == 'P')
                                                                echo "Pending";
                                                            else
                                                                echo "Disposed";

                                                            ?></td>
                                                        <td><?php if ($row['res_linked'] == 0) echo '-';
                                                            else echo (!empty($row['res_linked'])) ? substr(trim($row['res_linked']), 0, -4) . '-' .  substr(trim($row['res_linked']), -4) : ''; ?></td>
                                                        <td><?php echo (!empty($row['res_connect_case'])) ?  substr($row['res_connect_case'], 0, -4) . '-' .  substr($row['res_connect_case'], -4) : ''; ?></td>
                                                    </tr>
                                            <?php   }
                                            } ?>
                                        </tbody>
                                    </table>
                                <?php else : ?>
                                    <p style="text-align: center; color:red">No records found.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <div class="row" style="padding-left: 20px;padding-right: 20px; ">
            <div class="col-md-12">
                <div id="dv_sh_hd" style="display: none;position: fixed;top: 0;width: 100%;height: 100%;background-color: black;opacity: 0.6;left: 0;overflow: hidden;z-index: 103">
                    &nbsp;
                </div>
                <div id="dv_fixedFor_P" style="position: fixed;top:75px;display: none;left:10% !important;width:85%;height:100%;z-index: 105;">
                    <div id="sp_close" style="text-align: right;cursor: pointer;width: 40px;float: right" onclick="closeData()"><b><img src="<?php echo base_url('images/close_btn.png');?>" style="width:30px;height:30px" /></b></div>
                    <div style="width: auto;background-color: white;overflow: scroll;height: 90%;margin-left: 50px;margin-right: 50px;margin-bottom: 25px;margin-top: 1px;word-wrap: break-word;" id="ggg" onkeypress="return  nb(event)" onmouseup="checkStat()">
                    </div>
                </div>
            </div>
        </div>



        <!-- /.container-fluid -->
</section>
<script>
    $(function() {
        $(".table1").DataTable({
            "paging": false,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": true,
            "responsive": true,
        });

    });

    $(document).ready(function() {
        $(document).on('.cl_c_diary', function() {
            alert("FSDF");

            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

            document.getElementById('ggg').style.width = 'auto';
            document.getElementById('ggg').style.height = ' 500px';
            document.getElementById('ggg').style.overflow = 'scroll';

            document.getElementById('ggg').style.marginLeft = '18px';
            document.getElementById('ggg').style.marginRight = '18px';
            document.getElementById('ggg').style.marginBottom = '25px';
            document.getElementById('ggg').style.marginTop = '30px';
            document.getElementById('dv_sh_hd').style.display = 'block';
            document.getElementById('dv_fixedFor_P').style.display = 'block';
            document.getElementById('dv_fixedFor_P').style.marginTop = '3px';
            $.ajax({
                url: '<?php echo base_url('Common/case_status/case_status'); ?>',
                cache: false,
                async: true,
                data: {
                    d_no: '43452023'
                },
                beforeSend: function() {
                    $('#ggg').html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                },
                type: 'POST',
                success: function(data, status) {
                    updateCSRFToken();
                    $('#ggg').html(data);
                    add_button();
                },
                error: function(xhr) {
                    alert("Error: " + xhr.status + " " + xhr.statusText);
                }

            });
        });

    });

    function viewcasestatus(diary_no) {

        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        document.getElementById('ggg').style.width = 'auto';
        document.getElementById('ggg').style.height = ' 600px';
        document.getElementById('ggg').style.overflow = 'scroll';

        document.getElementById('ggg').style.marginLeft = '18px';
        document.getElementById('ggg').style.marginRight = '18px';
        document.getElementById('ggg').style.marginBottom = '25px';
        document.getElementById('ggg').style.marginTop = '30px';
        document.getElementById('dv_sh_hd').style.display = 'block';
        document.getElementById('dv_fixedFor_P').style.display = 'block';
        document.getElementById('dv_fixedFor_P').style.marginTop = '3px';
        document.getElementById('sp_close').style.display = 'block';

        $.ajax({
            type: "POST",
            data: {
                CSRF_TOKEN: CSRF_TOKEN_VALUE,
                diary_no: diary_no,

            },
            beforeSend: function() {
                $('#ggg').html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
            },
            url: "<?php echo base_url('Filing/Similarity/case_details'); ?>",
            success: function(data) {

                updateCSRFToken();
                $('#ggg').html(data);

                // location.reload();
            },
            error: function() {
                updateCSRFToken();
            }
        });



    }

    function closeData() {
        document.getElementById('dv_fixedFor_P').style.display = "none";
        document.getElementById('dv_sh_hd').style.display = "none";
        document.getElementById('sp_close').style.display = 'none';
    }
</script>