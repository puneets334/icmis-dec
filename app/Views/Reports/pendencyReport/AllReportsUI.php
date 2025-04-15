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

    .dataTables_filter
    {
        padding-right: 55px;
    }

    .table-striped tr:nth-child(odd) td
    {
        background: #ECEEF2 !important;
    }

    .table-striped tr:nth-child(even) td
    {
        background: #ffffff;
    }
    
    table.dataTable thead th, table.dataTable tfoot th {
        font-weight: bold !important;
    }

    div.dt-buttons {
        float: left;
        margin-top: 0px;
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
                                <h3 class="card-title">Detailed Pendency</h3>
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
                                'id' => 'coramDelFormId',
                                'autocomplete' => 'off',
                                'enctype' => 'multipart/form-data',
                                'method' => 'post',
                                'target' => '_blank'
                            );
                            echo form_open(base_url('#'), $attribute);
                            ?>
                            <input type="hidden" name="usercode" id="usercode" value="<?php echo session()->get('login')['usercode']; ?>"/>
                            <div class="form-group row">
                                <?php
                                if(is_array($data))
                                {
                                    ?>
                                    <?php
                                    if($data['app_name'] == 'CurrentPendency')
                                    {
                                    ?>
                                    <div id="printable" class="w-100">
                                        <h3 style="text-align: center;"> PENDENCY STATEMENT AS ON <?PHP echo date("d-m-Y h:m:s A")?> </h3>
                                        <table width="100%" id="reportTable1" class="table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Columns</th>
                                                    <th width="70%" style="text-align: left;">Classification</th>
                                                    <th width="20%">Total</th>
                                                </tr>
                                            </thead>
                                            <?php
                                                $regsum = (int)$data['reportsAG'][0]['misc_side_pendency']+(int)$data['reportsAG'][0]['regular_side_pendency'];

                                                $unregsum = (int)$data['reportsB'][0]['total']+(int)$data['reportsC'][0]['total']+(int)$data['reportsD'][0]['dtotal']+(int)$data['reportsE1'][0]['total']+(int)$data['reportsE2'][0]['total'];

                                                /*$sum = (int)$data['reportsAG'][0]['misc_side_pendency']+(int)$data['reportsB'][0]['total']+(int)$data['reportsC'][0]['total']+(int)$data['reportsD'][0]['dtotal']+(int)$data['reportsE1'][0]['total']+(int)$data['reportsE2'][0]['total']+(int)$data['reportsAG'][0]['regular_side_pendency'];*/
                                                $sum = 0;
                                                $sum += isset($data['reportsAG'][0]['misc_side_pendency']) ? (int)$data['reportsAG'][0]['misc_side_pendency'] : 0;
                                                $sum += isset($data['reportsB'][0]['total']) ? (int)$data['reportsB'][0]['total'] : 0;
                                                $sum += isset($data['reportsC'][0]['total']) ? (int)$data['reportsC'][0]['total'] : 0;
                                                $sum += isset($data['reportsD'][0]['dtotal']) ? (int)$data['reportsD'][0]['dtotal'] : 0;
                                                $sum += isset($data['reportsE1'][0]['total']) ? (int)$data['reportsE1'][0]['total'] : 0;
                                                $sum += isset($data['reportsE2'][0]['total']) ? (int)$data['reportsE2'][0]['total'] : 0;
                                                $sum += isset($data['reportsAG'][0]['regular_side_pendency']) ? (int)$data['reportsAG'][0]['regular_side_pendency'] : 0;

                                                /*$gsum = (int)$sum + (int)$data['reportsC1'][0]['total'] + (int)$data['reportsC2'][0]['total'];*/
                                                $gsum = (int)$sum + (!empty($data['reportsC1']) && isset($data['reportsC1'][0]['total']) ? (int)$data['reportsC1'][0]['total'] : 0) + (isset($data['reportsC2'][0]['total']) ? (int)$data['reportsC2'][0]['total'] : 0);

                                                //$fTotal=((int)$reportsTotal[0]['total']-(int)$sum)-(int)$reportsE1[0]['total']-(int)$reportsE2[0]['total'];
                                                $fTotal = ((int)$data['reportsTotal'][0]['total'] - (int)$sum);
                                            ?>
                                            <tbody>
                                                <tr>
                                                    <td>A</td>
                                                    <td>Registered Matters</td>
                                                    <td><?php echo $regsum;?></td>
                                                </tr>
                                                <tr>
                                                    <td>B</td>
                                                    <td>Unregistered Matters (Listed / To be listed )

                                                    </td>
                                                    <td><?php echo $unregsum;?><BR>


                                                    </td>

                                                </tr>
                                                <tr>
                                                    <td>C</td>
                                                    <td>Unregistered Matters<br>
                                                        [ Non-Defective & Listed Before Chamber]<br>
                                                       (based on IAs Withdrawal Of Case, Exemption From Paying Court Fee, Exemption From Surrendering, Exemption From Filing Separate Certificate Of Surrender)

                                                    </td>
                                                    <td><?php echo '' ?>
                                                        <a href="<?= base_url('Reports/PendencyReport/DetailedPendency/detailed_pendency_report/34/C') ?>" target="_blank">
                                                            <?php echo $data['reportsC'][0]['total']?>
                                                        </a><br>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>D</td>
                                                    <td>Unregistered Matters <BR>
                                                        [ Defective & Listed before Court due to special directions / Orders ]</td>
                                                    <td>
                                                        <a href="<?= base_url('Reports/PendencyReport/DetailedPendency/detailed_pendency_report/34/D') ?>" target="_blank">
                                                            <?php echo $data['reportsD'][0]['dtotal']?>
                                                        </a>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>E</td>
                                                    <td>Unregistered Matters [ Defective, I.A. for c/delay in refilling filed & Listed ] <BR>
                                                        1. before Chamber (delay  > 60 days) <BR>
                                                        2. before Registrar (delay <= 60 days)
                                                    </td>
                                                    <td><?php /*echo ((int)$reportsE1[0]['total']+(int)$reportsE2[0]['total']) ;*/

                                                        ?><br>
                                                        <a href="<?= base_url('Reports/PendencyReport/DetailedPendency/detailed_pendency_report/34/E1') ?>" target="_blank">
                                                            <?php echo $data['reportsE1'][0]['total']?>
                                                        </a><br>
                                                        <a href="<?= base_url('Reports/PendencyReport/DetailedPendency/detailed_pendency_report/34/E2') ?>" target="_blank">
                                                            <?php echo $data['reportsE2'][0]['total']?>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>F</td>
                                                    <td>Unregistered Matters [ Defective & Non-Defective]<BR>
                                                        1. Files Under Scrutiny and in Filing Counter Section  <BR>
                                                        2. (returned to Advocates/not re-filed etc before 18.08.2014)<BR>
                                                        3. (returned to Advocates/not re-filed etc after 18.08.2014 and delay>90)
                                                    </td>
                                                    <td>
                                                        <a href="<?= base_url('Reports/PendencyReport/DetailedPendency/detailed_pendency_report/35/F') ?>" target="_blank">
                                                            <?php echo $fTotal?>
                                                        </a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                            <tfoot>
                                                <th>&nbsp;</th>
                                                <th>A + B  (Pendency including Un-registered matters) </th>
                                                <?php ?>
                                                <th><?php echo $sum; ?></th>
                                            </tfoot>
                                        </table>
                                    <?php
                                    }
                                    }
                                    ?>
                                    </div>
                            <?= form_close()?>
                        </div>
                    </div>
                    <center><span id="loader"></span></center>
                    <div class="row mt-2">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <div id="dv_res1"></div>
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


    $('#reportTable1').DataTable({
        dom: 'Bfrtip',
        buttons: [
            {
                extend:'print',
                footer:true,
                orientation: 'landscape',
                pageSize: 'A4',
                exportOptions: {
                    stripHtml: false
                },
                title:'',

                customize: function ( win ) {
                    $(win.document.body).css( 'font-size', '12pt');
                    $(win.document.body).find('table').before(
                        '<h3 style="text-align: center;">PENDENCY STATEMENT AS ON  <?php echo date("d-m-Y h:m:s A")?></h3>'
                    );
                    
                        /*.append(
                            '<p style="float:left;width=50%;font-weight: bold;font-size: 20px;word-spacing: 5px;letter-spacing: 1px;color: Blue;margin-left: 150px;">Total Listed :'+gradTotalListed+'</p>' +
                                '<p style="float:right;width=50%;font-weight: bold;font-size: 20px;word-spacing: 5px;letter-spacing: 1px;color: Blue;margin-right: 150px;">Total Disposed :'+grandTotalDisposed+'</p>'
                        );*/

                    $(win.document.body).find( 'table' )
                        .addClass( 'compact' )
                        .css( 'font-size', 'inherit' );
                }
            }
        ],
        lengthMenu: [
            [ 10, 25, 50, -1 ],
            [ '10 rows', '25 rows', '50 rows', 'Show all' ]
        ],

        "footerCallback": function ( row, data, start, end, display ){
            var api = this.api(), data;

            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
            // Total over all pages
            total = api
                .column( 4 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

            // Total over this page
            pageTotal = api
                .column( 4, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            // Update footer
            $( api.column( 4 ).footer() ).html(pageTotal +' ('+ total +' Total)');
        }
    });
</script>