<?= view('header') ?>
<style>
    .rowmargin-0 {
        margin: 0 !important;
    }

    table {
        margin: auto;
        text-align: center;
    }

    table.table_tr_th_w_clr.c_vertical_align {
        border: 1px solid lightgray;
        border-collapse: collapse;
    }

    table.table_tr_th_w_clr.c_vertical_align th,
    table.table_tr_th_w_clr.c_vertical_align td {
        border: 1px solid lightgray;
    }

    table.table_tr_th_w_clr.c_vertical_align th {
        font-weight: 600;
    }

    textarea {
        height: 60px;
    }

    .form-group {
        margin-bottom: 10px;
    }

    label {
        padding-bottom: 0px;
    }
</style>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Add Case in Vacation List</h3>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <span class="alert-danger"><?= \Config\Services::validation()->listErrors() ?></span>
                                    <?php if (session()->getFlashdata('error')) { ?>
                                        <div class="alert alert-danger text-white ">
                                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                            <?= session()->getFlashdata('error') ?>
                                        </div>
                                    <?php } else if (session("message_error")) { ?>
                                        <div class="alert alert-danger">
                                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                            <?= session()->getFlashdata("message_error") ?>
                                        </div>
                                    <?php } else { ?>
                                        <br />
                                    <?php } ?>

                                    <form>
                                        <?= csrf_field() ?>
                                        <input type="hidden" id="side_hd" value="<?= $caseDetails['case_grp'] ?? '' ?>">
                                        <input type="hidden" id="fil_hd" value="<?php echo $diary_no; ?>">
                                        <input type="hidden" id="usercode" name="usercode" value="<?= $usercode?>">
                                        <table align="center" width="100%">
                                            <tr align="center" style="color:blue">
                                                <th>
                                                    <?php
                                                    echo "Case No.-";
                                                    if (isset($casetype['fil_no']) && !empty($casetype['fil_no'])) {
                                                        echo '[M]' . $casetype['short_description'] . SUBSTR($casetype['fil_no'], 3) . '/' . $casetype['m_year'];
                                                    }

                                                    if (isset($casetype['fil_no_fh']) && !empty($casetype['fil_no_fh'])) {

                                                        echo ',[R]' . $r_case['short_description'] . SUBSTR($casetype['fil_no_fh'], 3) . '/' . $casetype['f_year'];
                                                    }
                                                    echo ", Diary No: " . substr($diary_no, 0, -4) . '/' . substr($diary_no, -4);
                                                    ?>
                                                </th>
                                            </tr>
                                        </table>

                                        <table align="center" id="tb_clr" cellspacing="3" cellpadding="2" style="width:80%">
                                            <?php if (isset($caseDetails['c_status']) && ($caseDetails['c_status'] == 'D')): ?>
                                                <tr>
                                                    <th colspan="4" class="highlight">The Case is Disposed!!!</th>
                                                </tr>
                                            <?php endif; ?>
                                            <tr>
                                                <th colspan="4" style="color:blue">
                                                    <?php
                                                    $res_name = isset($caseDetails['res_name']) ? $caseDetails['res_name'] : '';
                                                    echo (isset($caseDetails['pet_name']) ? $caseDetails['pet_name'] : '') . " <span style='color:black'> - Vs - </span> " . $res_name;
                                                    ?>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th colspan="4">
                                                    <i><b>Category:</b></i>
                                                    <span style="font-size:14px;color:brown">
                                                        <?php foreach ($categories as $category): ?>
                                                            <?= $category['sub_name1'] . '-' . $category['sub_name2'] . '-' . $category['sub_name3'] . '-' . $category['sub_name4'] ?><br>
                                                        <?php endforeach; ?>
                                                    </span>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th colspan="4" style="text-align: center;font-size: 14px;">
                                                    <?php if (!empty($main_case)): ?>
                                                        <?php if ($main_case['conn_key'] == $diary_no): ?>
                                                            This is Main Diary No
                                                            <input type="hidden" id="fil_hd" value="<?= $main_case['conn_key']; ?>">
                                                        <?php else: ?>
                                                            This is Connected Diary No, Main Diary No is
                                                            <span style='color:red'><?= substr($main_case['conn_key'], 0, -4) . '/' . substr($main_case['conn_key'], -4); ?></span>
                                                            <input type="hidden" id="fil_hd" value="<?= $main_case['conn_key']; ?>">
                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        <span style='color:red'>No case found for the provided diary number.</span>
                                                        <input type="hidden" id="fil_hd" value="<?= $diary_no; ?>"> <!-- Fallback to the original dno -->
                                                    <?php endif; ?>
                                                </th>
                                            </tr>
                                            </table>
                                            <div class="container">
                                                <div class="row">
                                                    <div class="col-md-2"></div>
                                                    <div class="col-md-5">
                                                        <div class="form-group">
                                                            <label>Filing Date:</label>
                                                            <?php if($caseDetails['diary_no_rec_date']!='') echo date('d-M-Y',strtotime($caseDetails['diary_no_rec_date'])).' on '.date('h:i A',strtotime($caseDetails['diary_no_rec_date'])); else echo '--';?>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <div class="form-group">
                                                            <label> Registration Date:</label>
                                                            <?php if($caseDetails['fil_dt']!='') echo date('d-M-Y',strtotime($caseDetails['fil_dt'])).' on '.date('h:i A',strtotime($caseDetails['fil_dt'])); else echo '--';?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-2"></div>
                                                    <div class="col-md-10">
                                                        <div class="form-group">
                                                            <label>Last Order:</label>
                                                            <?= !empty($caseDetails['lastorder']) ? $caseDetails['lastorder'] : '--'; ?>
                                                        </div>
                                                    </div>
                                                </div>

                                                <?php if(trim($caseDetails['c_status']) == 'P') {
                                                    if($isInVacationList){ ?>
                                                        <div class="row mb-3 mt-5">
                                                            <div class="col-12 text-center">
                                                                <span style="color:red;font-weight: bold;">This case is already added to the vacation list</span>
                                                            </div>
                                                        </div>
                                                    <?php } else { ?>
                                                        <div class="row mb-3 mt-5">
                                                            <div class="col-12 text-center">
                                                                <input type="button" value="Add in Vacation List" name="savebutton" id="savebutton" class="btn btn-primary" />
                                                            </div>
                                                        </div>
                                                    <?php }
                                                } ?>
                                            </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script src="<?php echo base_url('listing/vacation/addCase.js'); ?>"></script>