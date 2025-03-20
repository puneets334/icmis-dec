<?= view('header') ?>
<link rel="stylesheet" href="<?= base_url() ?>/assets/plugins/datepicker/datepicker3.css">
<style type="text/css">
    .card-header
    {
        padding: .75rem 0;
    }

    #overlay
    {
        background-color: #000;
        opacity: 0.7;
        filter:alpha(opacity=70);
        position: fixed;
        top: 0px;
        left: 0px;
        width: 100%;
        height: 100%;
    }

    #newb
    { 
        position: fixed; padding: 12px; left: 50%; top: 50%; display: none; color: black; background-color: #D3D3D3; border: 2px solid lightslategrey; height:100%;
    }
</style>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-md-12">
                                <h3 class="card-title">Dispatch</h3>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2" style="width: 100% !important;">
                       <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <?php if (session()->getFlashdata('msg')): ?>
                                <?= session()->getFlashdata('msg') ?>
                            <?php endif; ?>
                            
                            <?php
                            $attribute = array(
                                'class' => 'form-horizontal appearance_search_form',
                                'id' => 'dispatchClCasesId',
                                'autocomplete' => 'off',
                                'enctype'=>'multipart/form-data',
                                'method' => 'post'
                            );
                            echo form_open(base_url('#'), $attribute);
                            ?>
                            <input type="hidden" name="usercode" id="usercode" value="<?php echo session()->get('login')['usercode']; ?>"/>
                            <div class="table-responsive" style="padding-right: 30px;">
                                <table id="reportTable1" class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>S.No.</th>
                                            <th>Case No.</th>
                                            <th>Diary No.</th>
                                            <th>Connected With</th>
                                            <th>Listing date</th>
                                            <th>Hon'ble Judges</th>
                                            <th>File Location</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (count($dispatch_data) > 0): ?>
                                        <?php
                                        foreach ($dispatch_data as $key => $data):
                                        ?>
                                            <tr>
                                                <td><?php echo $key+1 ?></td>
                                                <td><?php echo get_case_nos($data['diary_no'],'&nbsp;&nbsp;') ?></td>
                                                <td><?php echo get_real_diaryno($data["diary_no"]) ?></td>
                                                <td><?php echo get_real_diaryno($data["conn_key"]) ?></td>
                                                <?php if (empty($data["next_dt"])): ?>
                                                    <td><?php echo '' ?></td>
                                                <?php else: ?>
                                                    <td><?php echo $data["next_dt"] ?></td>
                                                <?php endif; ?>
                                                <td><?php echo get_judges($data['judges']) ?></td>
                                                <td><?php echo get_diaryA_fm($data["diary_no"],'dispatch') ?></td>
                                            </tr>
                                        <?php
                                        endforeach; 
                                        ?>
                                        <?php else: ?>
                                        <tr><td colspan="7"><center><strong>No record Found</strong></center></td></tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                            <?= form_close()?>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div id="dv_content1">
                                <div id="dv_res1"> </div>
                                <div id="dv_res2"> </div>
                            </div>
                            <div id="overlay" style="display:none;">&nbsp;</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script src="<?= base_url() ?>/assets/js/exchange_dispatch.js"></script>
<script src="<?= base_url() ?>/assets/js/sweetalert-2.1.2.min.js"></script>
<script src="<?= base_url() ?>/assets/plugins/datepicker/bootstrap-datepicker.js"></script>