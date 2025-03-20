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
                                <h3 class="card-title">FILED-LISTED-DISPOSED OFF Matters Report</h3>
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
                                'id' => 'IPDFormSubmit',
                                'autocomplete' => 'off',
                                'enctype' => 'multipart/form-data',
                                'method' => 'post',
                                'target' => '_blank'
                            );
                            echo form_open('Reports/ILDStats/Report_IPD/get_IPD', $attribute);
                            ?>
                            <input type="hidden" name="usercode" id="usercode" value="<?php echo session()->get('login')['usercode']; ?>"/>
                            <div class="form-group row">
                                <div class="col-md-3" style="max-width: 19.5%;">
                                    <label for="m_dept">Start Date:</label>
                                    <input class="form-control dtp" type="text" id="from_date" name="from_date" ng-model="from_date" placeholder="From Date">
                                </div>

                                <div class="col-md-3" style="max-width: 19.5%;">
                                    <label for="m_dept">End Date:</label>
                                    <input type="text" class="form-control dtp" id="to_date" name="to_date" ng-model="to_date" ng-change="check_date()" placeholder="To Date">
                                </div>
                                <div class="col-md-2" style="overflow: hidden;padding-top: 26px;">
                                    <input type="button" id="submit_button" value="Submit">
                                </div>
                            </div>
                            <?= form_close()?>
                        </div>
                    </div>
                    <center><span id="loader"></span></center>
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

    $(document).on('click', '#submit_button', function()
    {
        let from_date = $('#from_date').val();
        let to_date = $('#to_date').val();
        if(from_date == '' || to_date == '')
        {
            alert('Either From date or To date is empty');
            return false;
        }
        else
        {
            $('#IPDFormSubmit').submit();
        }
    });
</script>