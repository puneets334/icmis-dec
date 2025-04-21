<?= view('header') ?>
<link rel="stylesheet" href="<?= base_url() ?>/assets/plugins/datepicker/datepicker3.css">
<style type="text/css">
    .card-header
    {
        padding: .75rem 0;
    }
    .centerview
    {
        margin-left: auto;
        margin-right: auto;
        border-collapse: collapse;
    }
    .nofound
    {
        text-align: center;
        color: red;
        font-size: 17px;
    }

    .table_tr_th_w_clr td
    {
        padding:10px;
    }

    @media print
    {
        #cmdPrnRqs2
        {
            display: none;
        }
    }

    #newb
    {
        position: fixed; padding: 12px; left: 50%; top: 50%; display: none; color: black; background-color: #D3D3D3; border: 2px solid lightslategrey; height:100%;
    }
    #newc
    {
        position: fixed; padding: 12px; left: 50%; top: 50%; display: none; color: black; background-color: #D3D3D3; border: 2px solid lightslategrey; height:100%;
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

    .textColor
    {
        color: #072c76;
    }
    .table-striped tr:nth-child(odd) td {
        background: #ECEEF2 !important;
    }
    .table-striped tr:nth-child(even) td {
        background: #ffffff;
    }
    .dataTables_filter
    {
        padding-right: 55px;
    }

    div.dt-buttons {
        float: left;
        margin-top: 0px;
    }
    table.dataTable thead th, table.dataTable tfoot th {
        font-weight: bold !important;
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
                                <h3 class="card-title">Disposal Order Date</h3>
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
                                'class' => 'form-horizontal disposalAsPerOrderDate',
                                'id' => 'disposalAsPerOrderDate',
                                'autocomplete' => 'off',
                                'enctype' => 'multipart/form-data',
                                'method' => 'post'
                            );
                            /*echo form_open(base_url('#'), $attribute);*/
                            echo form_open(current_url(), $attribute);
                            $from_date = isset($from_date) ? $from_date : '';
                            $to_date = isset($to_date) ? $to_date : '';
                            $today = date('d-m-Y h:m:s A');
                            
                            ?>
                            <input type="hidden" name="usercode" id="usercode" value="<?php echo session()->get('login')['usercode']; ?>"/>
                            <div class="form-group row">
                                <div class="col-sm-2">
                                    <label for="from_date">From Date :</label>
                                    <input class="form-control dtp" type="text" id="from_date" name="from_date" class="form-control datepick1" placeholder="From Date" required="required" value="<?= $from_date ?>">
                                </div>
                                <div class="col-sm-2">
                                    <label for="to_date">To Date :</label>
                                    <input class="form-control dtp" type="text" id="to_date" name="to_date" class="form-control datepick2" placeholder="To Date" required="required" value="<?= $to_date ?>">
                                </div>
                                <div class="col-md-2" style="padding-top: 25px;">
                                    <button type="submit" id="view" name="view" class="btn btn-primary" readonly>View</button>
                                </div>
                                <?= form_close()?>
                            </div>
                        </div>
                    </div>
                    <div id="loader" align="center"></div>
                    <div class="card-body">
                        <div class="mt-2">
                            <div class="col-12 col-md-12">
                               <section class="content">
                                    <?php
                                        if (isset($case_result1) && isset($case_result2) && is_array($case_result1) && sizeof($case_result1) > 0) {
                                    ?>
                                    
                                        <table width="100%" id="reportTable1" class="table table-striped table-hover mrgT20">
                                            <h3 style="text-align: center;">Total Disposal of Matters as per Order date between <?php echo $from_date;?> And <?php echo $to_date;?> As on <?php echo date('d-m-Y h:m:s A')?></h3>
                                            <thead>
                                                <tr>
                                                    <th>Description</th>
                                                    <th>Value</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php 
                                            $opening_pendency=0;
                                            foreach ($case_result1 as $result) {
                                                if($from_date == '28-08-2017') {
                                                    $opening_pendency = 58920;
                                                } else {
                                                    $opening_pendency=(int)$result['current_pendency']+(int)$result['total_disposal']-(int)$result['diary_disposal']-(int)$result['institution'];
                                                }
                                                ?>
                                                <tr>
                                                    <td>Opening Pendency (as on <?php echo date("d-m-Y",$opening_date);?>) </td>
                                                    <td><?php echo $opening_pendency;?></td>
                                                </tr>
                                                <tr>
                                                    <td>(+) Institution (Registered cases)</td>
                                                    <td>
                                                        <a href="<?= base_url('Reports/PendencyReport/DetailedPendency/Disposal_AsPer_Order_Details/' . urlencode($from_date) . '/' . urlencode($to_date) . '/3'); ?>" target="_blank"><?php echo $result['institution']; ?>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>(+) Institution of cases which are disposed between above dates*</td>
                                                    <td><a href="<?= base_url('Reports/PendencyReport/DetailedPendency/Disposal_AsPer_Order_Details/' . urlencode($from_date) . '/' . urlencode($to_date) . '/4'); ?>" target="_blank"><?php echo $result['diary_disposal']; ?></a></td>
                                                </tr>
                                                <tr>
                                                    <td>(-) Total Disposal (Registered  cases + Diary no + un-registered cases (IA's))</td>
                                                    <td><a href="<?= base_url('Reports/PendencyReport/DetailedPendency/Disposal_AsPer_Order_Details/' . urlencode($from_date) . '/' . urlencode($to_date) . '/5'); ?>" target="_blank"><?php echo $result['total_disposal']; ?></a></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>(=) Pendency as on <?php echo date('d-m-Y h:m:s A')?></strong></td>
                                                    <td><strong><?php echo $result['current_pendency'];?></strong></td>
                                                </tr>
                                                <tr>
                                                    <td><hr></td>
                                                    <td><hr></td>
                                                </tr>
                                                <tr>
                                                    <td>*Note :- The cases which are listed due to special direction of Diary no and IA's.</td>
                                                    <td></td>
                                                </tr>
                                                <?php
                                            }
                                            ?>
                                            </tbody>
                                        </table>
                                        <BR><BR><BR><BR>
                                        <table width="100%" id="reportTable2" class="table table-striped table-hover">
                                            <thead>
                                            <tr>
                                                <th>Description</th>
                                                <th>Value</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td>
                                                    <h3 style="text-align: center;">Bifurcation  of Pending matters As on <?php echo date('d-m-Y h:m:s A')?></h3>
                                                </td>
                                                <td>&nbsp;</td>
                                            </tr>

                                            <?php foreach ($case_result2 as $result)
                                            {
                                                /*$opening_pendency=(int)$result['current_pendency']+(int)$result['total_disposal']-(int)$result['diary_disposal']-(int)$result['institution'];*/

                                                $opening_pendency = (int)($result['current_pendency'] ?? 0) + (int)($result['total_disposal'] ?? 0) - (int)($result['diary_disposal'] ?? 0) - (int)($result['institution'] ?? 0);
                                                ?>
                                                <tr>
                                                    <td>Number of Admission hearing matters </td>
                                                    <td><?php echo $result['admission_matter'];?></td>
                                                </tr>
                                                <tr>
                                                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Complete</td>
                                                    <td><?php echo $result['total_complete'];?></td>
                                                </tr>
                                                <tr>
                                                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Incomplete</td>
                                                    <td><?php echo $result['total_incomplete'];?></td>
                                                </tr>
                                                <tr>
                                                    <td>Number of Regular hearing matters</td>
                                                    <td><?php echo $result['final_matter'];?></td>
                                                </tr>
                                                <tr>
                                                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Ready</td>
                                                    <td><?php echo $result['total_ready'];?></td>
                                                </tr>
                                                <tr>
                                                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Not Ready</td>
                                                    <td><?php echo $result['total_notready'];?></td>
                                                </tr>
                                                <tr>
                                                    <td>Number of Civil matters</td>
                                                    <td><?php echo $result['civil_pendency'];?></td>
                                                </tr>
                                                <tr>
                                                    <td>Number of Criminal matters</td>
                                                    <td><?php echo $result['criminal_pendency'];?></td>
                                                </tr>
                                                <tr>
                                                    <td>More than 1 year old matters</td>
                                                    <td><?php echo $result['more_than_one_year_old'];?></td>
                                                </tr>
                                                <tr>
                                                    <td>Less than 1 year old matters</td>
                                                    <td><?php echo $result['less_than_one_year_old'];?></td>
                                                </tr>
                                            <?php  }?>
                                            </tbody>
                                        </table>
                                        <?php } ?>
                                    </section>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script src="<?= base_url() ?>/assets/js/sweetalert-2.1.2.min.js"></script>
<script src="<?= base_url() ?>/assets/plugins/datepicker/bootstrap-datepicker.js"></script>
<script>
    $(function()
    {
        $('.dtp').datepicker({
            format: 'dd-mm-yyyy',
            todayHighlight: true,
            autoclose: true,
            changeMonth: true,
            changeYear: true,
            // yearRange: '1950:2050'
        });
    });


    $(document).ready(function() {
        var fromDate = "<?php echo $from_date; ?>";
        var toDate = "<?php echo $to_date; ?>";
        var today = "<?php echo $today; ?>";
        
        $('#reportTable1').DataTable({
            "bSort": false,
            dom: 'Bfrtip',
            "scrollX": true,
            iDisplayLength: 20,
            buttons: [
                {
                    extend: 'print',
                    orientation: 'landscape',
                    pageSize: 'A4',
                    customize: function (win) {
                        $(win.document.body).find('h1').remove();
                        var printHeading = '<h5 style="text-align: center;">Total Disposal of Matters as per Order date between ' + fromDate + ' And ' + toDate + ' As on ' + today + '</h5>';
                        $(win.document.body).find('table').before(printHeading);
                    }
                }
            ]
        });

        $('#reportTable2').DataTable({
            "bSort": false,
            dom: 'Bfrtip',
            "scrollX": true,
            iDisplayLength: 20,
            buttons: [
                {
                    extend: 'print',
                    orientation: 'landscape',
                    pageSize: 'A4',
                    customize: function (win) {
                        $(win.document.body).find('h1').remove();
                        $(win.document.body).find('table').before(
                            '<h3 style="text-align: center;">Bifurcation of Pending matters As on ' + today + '</h3>'
                        );
                    }
                }
            ]
        });
    } );

    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('disposalAsPerOrderDate');
        const submitBtn = document.getElementById('view');
        $('#loader').html();
        form.addEventListener('submit', function () {
            submitBtn.disabled = true;
            submitBtn.innerText = 'View';
            $('#loader').html('<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>');
        });
    });
</script>
