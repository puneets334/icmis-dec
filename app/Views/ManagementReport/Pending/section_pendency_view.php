<?= view('header') ?>
<link rel="stylesheet" href="<?= base_url() ?>/assets/css/skins/_all-skins.min.css">
<link rel="stylesheet" href="<?= base_url() ?>/assets/plugins/datepicker/datepicker3.css">
<link rel="stylesheet" href="<?= base_url() ?>/assets/css/Reports.css">
<script src="<?= base_url() ?>/assets/js/Reports.js"></script>
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Reports</h3>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <span class="alert-danger"><?= \Config\Services::validation()->listErrors() ?></span>

                            <?php if (session()->getFlashdata('error')) { ?>
                                <div class="alert alert-danger">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    <?= session()->getFlashdata('error') ?>
                                </div>
                            <?php } else if (session("message_error")) { ?>
                                <div class="alert alert-danger text-danger" style="color: red;">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    <?= session("message_error") ?>
                                </div>
                            <?php } else { ?>

                            <?php } ?>
                        </div>
                        <div class="col-md-12">
                            <div class="card-header p-2" style="background-color: #fff; border-bottom:none;">
                                    <h4 class="basic_heading">Sectionwise Pendency Not Ready Incomplete
                                    </h4>
                                </div>
                            <div class="card-body">
                                <div class="tab-content">
                                    <!-- Page Content Start -->

                                    <form>
                                        <button type="submit" style="width:15%;float:left" id="print" name="print" onclick="printDiv('printable')" class="btn btn-block btn-warning">Print</button>
                                    </form>
                                    <?php
                                    if (isset($da_rog_result) && sizeof($da_rog_result) > 0) {
                                    
                                    ?>
                                        <div id="printable">
                                            <h2 align="center">Pendency Report</h2>
                                            <table class="table table-striped table-hover ">
                                                <thead>
                                                    <tr>
                                                        <th rowspan='2'>#</th>
                                                        <th rowspan='2'>Dealing Assistant</th>
                                                        <th rowspan='2'>Total<br />Matters<br />Allocated</th>
                                                        <th colspan='4'>Matters under Specific Category</th>
                                                        <th rowspan='2'>Difference</th>
                                                    </tr>
                                                    <tr>
                                                        <th>Red</th>
                                                        <th>Orange</th>
                                                        <th>Green</th>
                                                        <th>Yellow</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                  
                                                    <?php
                                                        $i=0;
                                                        $total_total=0;
                                                        $total_red=0;
                                                        $total_orange=0;
                                                        $total_green=0;
                                                        $total_diff=0;
                                                        $total_yellow=0;
                                                        foreach ($da_rog_result as $result)
                                                        {$i++;
                                                        $diff=$result['total']-($result['red']+$result['orange']+$result['green']+$result['yellow']);
                                                            ?>
                                                            <tr>
                                                                <td><?php echo $i;?></td>
                                                                <td><?php echo $result['name']."(".$result['empid'].")<br/>".$result['type_name']." / ".$result['section_name'];?></td>
                                                                <td><a target="_blank" href="<?php echo base_url() ?>index.php/Reports/cases?category=t&dacode=<?php echo $result['dacode'];?>"><?php echo $result['total'];?></td>
                                                                <td><a target="_blank" href="<?php echo base_url() ?>index.php/Reports/cases?category=r&dacode=<?php echo $result['dacode'];?>"><?php echo $result['red'];?></td>
                                                                <td><a target="_blank" href="<?php echo base_url() ?>index.php/Reports/cases?category=o&dacode=<?php echo $result['dacode'];?>"><?php echo $result['orange'];?></td>
                                                                <td><a target="_blank" href="<?php echo base_url() ?>index.php/Reports/cases?category=g&dacode=<?php echo $result['dacode'];?>"><?php echo $result['green'];?></td>
                                                                <td><a target="_blank" href="<?php echo base_url() ?>index.php/Reports/cases?category=y&dacode=<?php echo $result['dacode'];?>"><?php echo $result['yellow'];?></td>
                                                                <td><a target="_blank" href="<?php echo base_url() ?>index.php/Reports/cases?category=d&dacode=<?php echo $result['dacode'];?>"><?php echo $diff;?></td>
                                                            </tr>

                                                            <?php
                                                            $total_total+=$result['total'];
                                                            $total_red+=$result['red'];
                                                            $total_orange+=$result['orange'];
                                                            $total_green+=$result['green'];
                                                            $total_yellow+=$result['yellow'];
                                                            $total_diff+=$diff;
                                                        }
                                                        ?>
                                                        <tr style="font-weight: bold;"><td colspan="2">Total</td><td><?= $total_total?></td><td><?= $total_red?></td>
                                                            <td><?= $total_orange?></td><td><?= $total_green?></td><td><?= $total_yellow?></td><td><?= $total_diff?></td></tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <?php
                                            }

                                            ?>
                                   
                                    <!-- Page Content End -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>