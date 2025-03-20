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
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-md-12">
                                <h3 class="card-title">Cases Not Verified</h3>
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
                                'id' => 'filingTrapComViewId',
                                'autocomplete' => 'off',
                                'enctype'=>'multipart/form-data',
                                'method' => 'post'
                            );
                            echo form_open(base_url('#'), $attribute);
                            ?>
                            <input type="hidden" name="usercode" id="usercode" value="<?php echo session()->get('login')['usercode']; ?>"/>

                            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="table-responsive" id="r_box" align="center" style="padding-left: 10px;">
                                    <table class='table table_tr_th_w_clr centerview' border=1>
                                        <tr>
                                            <td colspan="3" align="center">
                                                <h4>Bifurcation of Pending Matters Which are not verified as on <?= date('d-m-Y H:i:s A') ?></h4>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th align='center'>Sno.</th>
                                            <th align='center'>Description</th>
                                            <th align='center'>No. of Matters</th>
                                        </tr>
                                        <tr>
                                            <td>A</td>
                                            <td>Total No. of Matters not Verified by Branches and Pending in ICMIS</td>
                                            <td align='left'>
                                                <a class="textColor" href="<?= base_url('Reports/DefaultReports/DefaultReportsController/casesNotVerifiedDetails?reportType=A') ?>" target="_blank">
                                                    <?= esc($result_bifurcation['total']) ?>
                                                </a>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>B</td>
                                            <td>Out of “A” Total No. of Matters filed (after  Last Verification Date viz. May-2018) </td>
                                            <td align='left'>
                                                <a class="textColor" href="<?= base_url('Reports/DefaultReports/DefaultReportsController/casesNotVerifiedDetails?reportType=B') ?>" target="_blank">
                                                    <?= esc($result_bifurcation['filed_after_may_2018']) ?>
                                                </a>
                                            </td>
                                        </tr>

                                        
                                        <tr>
                                            <td>C</td>
                                            <td>Out of “A” Total No. of Matters filed before 18.08.2014 </td>
                                            <td align='left'>
                                                <a class="textColor" href="<?= base_url('Reports/DefaultReports/DefaultReportsController/casesNotVerifiedDetails?reportType=C') ?>" target="_blank">
                                                    <?= esc($result_bifurcation['filed_before_18_08_2014']) ?>
                                                </a>
                                            </td>
                                        </tr>

                                        
                                        <tr>
                                            <td>D</td>
                                            <td>Out of “A” Total No. of Matters filed after 18.08.2014 and Before May-2018 </td>
                                            <td align='left'>
                                                <a class="textColor" href="<?= base_url('Reports/DefaultReports/DefaultReportsController/casesNotVerifiedDetails?reportType=D') ?>" target="_blank">
                                                    <?= esc($result_bifurcation['filed_after_18_08_2014_before_may_2018']) ?>
                                                </a>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>E</td>
                                            <td>Out of “D” </td>
                                            <td align='left' style='font-weight: bold;'>&nbsp;</td>
                                        </tr>

                                        <tr>
                                            <td>&nbsp;</td>
                                            <td>Defects Not Notified (in process in 1B)</td>
                                            <td align='left'>
                                                <a class="textColor" href="<?= base_url('Reports/DefaultReports/DefaultReportsController/casesNotVerifiedDetails?reportType=E1') ?>" target="_blank">
                                                    <?= esc($result_bifurcation['defect_not_notified']) ?>
                                                </a>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>&nbsp;</td>
                                            <td>Delay in Refiling > 60 and < 90</td>
                                            <td align='left'>
                                                <a class="textColor" href="<?= base_url('Reports/DefaultReports/DefaultReportsController/casesNotVerifiedDetails?reportType=E2') ?>" target="_blank">
                                                    <?= esc($result_bifurcation['delay_in_refiling_60']) ?>
                                                </a>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>&nbsp;</td>
                                            <td>Delay in Refiling > 90</td>
                                            <td align='left'>
                                                <a class="textColor" href="<?= base_url('Reports/DefaultReports/DefaultReportsController/casesNotVerifiedDetails?reportType=E3') ?>" target="_blank">
                                                    <?= esc($result_bifurcation['delay_in_refiling_60']) ?>
                                                </a>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>&nbsp;</td>
                                            <td>Delay in Refiling < 60 (in process with adv)</td>
                                            <td align='left'>
                                                <a class="textColor" href="<?= base_url('Reports/DefaultReports/DefaultReportsController/casesNotVerifiedDetails?reportType=E4') ?>" target="_blank">
                                                    <?= esc($result_bifurcation['delay_in_refiling_60']) ?>
                                                </a>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
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