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
                    <div class="card-body">
                        <div class="mt-2">
                            <div class="col-12 col-md-12">
                                <section class="content">
                                    <?php
                                    if(is_array($data) )
                                    {
                                    ?>
                                    <?php
                                    if($data['app_name'] == 'FClassification')
                                    {
                                        $f1_total = (int)$data['reportsF11'][0]['total']+(int)$data['reportsF12'][0]['total'];
                                        $f2_total = (int)$data['reportsF21'][0]['total']+(int)$data['reportsF22'][0]['total'];
                                        $f_total = $f1_total+$f2_total;
                                    ?>
                                    <div id="printable" class="box box-danger">
                                        <h3 style="text-align: center;"> F - Classification AS ON <?PHP echo date("d-m-Y h:m:s A")?> </h3>
                                        <table width="100%" id="reportTable1" class="table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Columns</th>
                                                    <th width="70%" style="text-align: left;">Classification</th>
                                                    <th width="20%">Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>F</td>
                                                    <td>Unregistered matters <BR>[ Defective & Non-Defective]
                                                    </td>
                                                    <td><!--<a href="<?/*=base_url();*/?>index.php/Reports/pendency_reports/34/F" target="_blank">--><?php echo $f_total ?><!--</a>--></td>
                                                </tr>
                                                <tr>
                                                    <td>F1</td>
                                                    <td>Unregistered matters <BR>[Defective & Non-Defective]<BR>
                                                        ie., Total unregistered "4 IAs before Court" 5 IAs before Chamber<br>
                                                        = Total unregistered non-defective " b - c
                                                    </td>
                                                    <td><!--<a href="<?/*=base_url();*/?>index.php/Reports/pendency_reports/35/F1" target="_blank">--><?php echo $f1_total?><!--</a>--></td>
                                                </tr>
                                                <tr>
                                                    <td>F1.1</td>
                                                    <td>Returned to Advocates/not re-filed Filed on/before 18.08.2014
                                                    </td>
                                                    <td>
                                                        <a href="<?= base_url('Reports/PendencyReport/DetailedPendency/detailed_pendency_report/36/F11') ?>" target="_blank">
                                                            <?php echo $data['reportsF11'][0]['total']?>
                                                        </a>
                                                    </td>                        
                                                </tr>
                                                <tr>
                                                    <td>F1.2</td>
                                                    <td>Returned to Advocates/not re-filed Filed After 18.08.2014
                                                    </td>
                                                    <td>
                                                        <a href="<?= base_url('Reports/PendencyReport/DetailedPendency/detailed_pendency_report/36/F12') ?>" target="_blank">
                                                            <?php echo $data['reportsF12'][0]['total']?>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>F2</td>
                                                    <td>Unregistered Matters <BR> [ Defective, Not Listed Before  Registrar / Chamber / Court]
                                                        <BR>= Total unregistered Defective " Listed before Registrar/chamber/Court
                                                        <BR>= Total unregistered defective " D " E (e1+e2)
                                                    </td>
                                                    <td><!--<a href="<?/*=base_url();*/?>index.php/Reports/pendency_reports/35/F2" target="_blank">--><?php echo $f2_total?><!--</a>--><BR>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>F2.1</td>
                                                    <td>Defects notified and delay  > 60 days</td>
                                                    <td>
                                                        <a href="<?= base_url('Reports/PendencyReport/DetailedPendency/detailed_pendency_report/36/F21') ?>" target="_blank">
                                                            <?php echo $data['reportsF21'][0]['total']?>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>F2.2</td>
                                                    <td>Defects notified and delay  <= 60 days</td>
                                                    <td>
                                                        <a href="<?= base_url('Reports/PendencyReport/DetailedPendency/detailed_pendency_report/36/F22') ?>" target="_blank">
                                                            <?php echo $data['reportsF22'][0]['total']?>
                                                        </a>
                                                    </td>
                                                </tr>
                                            </tbody>

                                            <tfoot>
                                                <!--<th>&nbsp;</th>
                                                <th>â€œA + Gâ€? (Pendency of Registered Cases) <BR/>
                                                    â€œA + B + C + D + E + Gâ€? (Registered Cases + Unregistered Cases Listed due to IA's )</th>
                                                <?php
                            /*
                                                */?>
                                                <th><?php
                            /*                        echo  $regsum.'<BR/>';
                                                    echo $sum; */?></th>-->
                                            </tfoot>
                                        </table>

                                    <?php
                                    }
                                    }
                                    ?>
                                    </div>
                                <br>
                                <br>
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
        $('#reportTable1').DataTable({
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'print',
                    footer: true,
                    orientation: 'landscape',
                    pageSize: 'A4',
                    exportOptions: {
                        stripHtml: false
                    },
                    customize: function(win) {
                        // Customizing the print window
                        $(win.document.body).find('h1').remove();
                        var printableHeader = $('#printable').find('h3').html(); // Get the header from the #printable div
                        var printableTable = $('#printable').find('table').clone(); // Clone the table

                        // Append the header to the printed page
                        $(win.document.body)
                            .css('font-size', '12pt')
                            .prepend('<h3 style="text-align: center;">' + printableHeader + '</h3>') // Adding the header before the table
                            .append('<hr>'); // Optional: Adding a horizontal line for separation

                        // Append the table after the header
                        $(win.document.body)
                            .find('table')
                            .replaceWith(printableTable); // Replace the original table in the print window

                        // Optionally add additional styling to the table
                        $(win.document.body).find('table')
                            .addClass('compact')
                            .css('font-size', 'inherit');
                    }
                }
            ],
            lengthMenu: [
                [10, 25, 50, -1],
                ['10 rows', '25 rows', '50 rows', 'Show all']
            ],

            "footerCallback": function(row, data, start, end, display) {
                var api = this.api(), data;

                // Remove the formatting to get integer data for summation
                var intVal = function(i) {
                    return typeof i === 'string' ?
                        i.replace(/[\$,]/g, '') * 1 :
                        typeof i === 'number' ? i : 0;
                };
                // Total over all pages
                total = api
                    .column(4)
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                // Total over this page
                pageTotal = api
                    .column(4, { page: 'current' })
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                // Update footer
                $(api.column(4).footer()).html(pageTotal + ' (' + total + ' Total)');
            }
        });
    });
</script>