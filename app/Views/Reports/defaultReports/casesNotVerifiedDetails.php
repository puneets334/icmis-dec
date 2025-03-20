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
</style>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <!-- <div class="card-header heading">
                        <div class="row">
                            <div class="col-md-12">
                                <h3 class="card-title">Cases Not Verified Details</h3>
                            </div>
                        </div>
                    </div> -->
                    <div class="row mt-2" style="width: 100% !important;">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <?php if (session()->getFlashdata('msg')): ?>
                                <?= session()->getFlashdata('msg') ?>
                            <?php endif; ?>
                            
                            <?php
                            $attribute = array(
                                'class' => 'form-horizontal appearance_search_form',
                                'id' => 'filingTrapComViewId',
                                'autocomplete' => 'off',
                                'enctype'=>'multipart/form-data',
                                'method' => 'post'
                            );
                            echo form_open(base_url('#'), $attribute);
                            ?>
                            <input type="hidden" name="usercode" id="usercode" value="<?php echo session()->get('login')['usercode']; ?>"/>

                            <div class="col-md-12 table-responsive" id="r_box" align="center" style="padding-left: 10px;">
                                <table class='table table_tr_th_w_clr centerview' border=1>
                                    <thead>
                                        <tr >
                                            <th colspan="4" style="text-align: center;"><?=$reportHeading?> as on <?=date('d-m-Y H:i:s A')?></th>
                                        </tr>
                                        <tr>
                                            <th>Sno.</th>
                                            <th>Case No.</th>
                                            <th>Causetitle</th>
                                            <th>Filing Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $sno = 1; ?>
                                            <?php foreach ($queryResult as $row): ?>
                                                <tr>
                                                    <td width='5%'><?= $sno; ?></td>
                                                    <td width='25%'><?= $row['case_no']; ?></td>
                                                    <td width='35%'><?= $row['cause_title']; ?></td>
                                                    <?php if (empty($row['filing_date'])): ?>
                                                        <td><?php echo '' ?></td>
                                                    <?php else: ?>
                                                        <td><?php echo date('Y-m-d', strtotime($row['filing_date'])) ?></td>
                                                    <?php endif; ?>
                                                </tr>
                                            <?php $sno++; ?>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <div align="center" class="mt-3 mb-3">
                                <input name="cmdPrnRqs2" type="button" id="cmdPrnRqs2" onClick="CallPrint('r_box');" value="PRINT" style="background-color: #072c76;">
                            </div>
                            <?= form_close()?>
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

    function CallPrint(strid)
    {
        document.getElementById('cmdPrnRqs2').style.display= 'none';
        var prtContent = document.getElementById(strid);
        var WinPrint = window.open('','','letf=100,top=0,width=800,height=1200,toolbar=1,scrollbars=1,status=1,menubar=1');

        WinPrint.document.write(prtContent.innerHTML);
        WinPrint.document.close();
        WinPrint.focus();
        WinPrint.print();
        document.getElementById('cmdPrnRqs2').style.display= 'block';
        //WinPrint.close();
        //prtContent.innerHTML=strOldOne;
    }
</script>