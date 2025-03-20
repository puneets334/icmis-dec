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
                                    if(is_array($reports))
                                    {
                                        //var_dump($param);
                                    ?>
                                    <div id="printable" class="box box-danger">
                                        <caption>
                                        <?php
                                       // var_dump($reports);<caption>
                                            if($param[4]=='F11')
                                            {
                                             ?>
                                                <h3 style="text-align: center;"> F1.1 - Returned to Advocates/not re-filed Filed on/before 18.08.2014 </h3>
                                            <?php
                                            }
                                            elseif($param[4]=='F12')
                                            {
                                              ?>
                                                <h3 style="text-align: center;">F1.2 - Returned to Advocates/not re-filed Filed After 18.08.2014 </h3>

                                            <?php
                                            }
                                            elseif($param[4]=='F21')
                                            {
                                                ?>
                                                <h3 style="text-align: center;">F2.1 - Defects notified and delay > 60 days </h3>
                                            <?php
                                            }
                                            elseif($param[4]=='F22')
                                            {
                                                ?>
                                                <h3 style="text-align: center;">F2.2 - Defects notified and delay <= 60 days </h3>

                                            <?php
                                            }
                                            ?>
                                        </caption>
                                        <?php
                                        if($app_name=='FClassificationDetailed')
                                        {
                                            ?>
                                        <table id="reportTable1" class="table table-striped table-hover">
                                            <thead>
                                            <tr>
                                                <th width="2%">S.No.</th>
                                                <th width="10%" style="text-align: left;">Diary No<br/>#Diary Date</th>
                                                <?php if($param[4]=='A' || $param[4]=='G') { ?>
                                                    <th width="10%">Registration <br> No</th>
                                                <?php } ?>
                                                <th style="width:15% !important;">Cause <br> Title</th>
                                                <th width="6%">Section</th>
                                                <th width="7%" >Next <br> Date</th>
                                                <th width="7%">Dealing <br> Assistant</th>
                                                <th width="6%" style="text-align: right;">Misc <br> /Regular</th>
                                                <th width="6%" style="text-align: right;">Main<br>/Connected</th>

                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            $s_no=1;
                                            foreach ($reports as $result)
                                            {//echo $s_no;

                                                ?>
                                            <tr>
                                                <td><?php echo $s_no;?></td>
                                                <td><?php echo $result['diary_no'];?> / <?php echo $result['diary_year'];?> # <?php echo date('d-m-Y',strtotime($result['diary_date']));?></td>
                                                <?php if($param[4]=='A' || $param[4]=='G') { ?>
                                                    <td><?php echo $result['reg_no_display'];?></td>
                                                <?php } ?>
                                                <td><?php echo $result['pet_name'].' Vs. '. $result['res_name'];?></td>
                                                <td><?php echo $result['user_section'];?></td>
                                                <td>
                                                    <?php
                                                    if (!empty($result['next_dt']))
                                                    {
                                                        echo date('d-m-Y', strtotime($result['next_dt']));
                                                    }
                                                    else
                                                    {
                                                        echo '';
                                                    }
                                                    ?>
                                                </td>
                                                <td><?php echo $result['alloted_to_da'];?></td>
                                                <td><?php echo $result['mf_active'];?></td>
                                                <td><?php echo $result['mainorconn'];?></td>
                                                </tr>
                                                <?php
                                                $s_no++;
                                            }   //for each
                                            ?>
                                            </tbody>
                                            </table>
                                        <?php
                                        }?>

                                    </div>
                                    <?PHP
                                    }
                                   ?>
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


    $(document).ready(function()
    {
        $(function () {
            // Initialize Select2 Elements
            $(".select2").select2();
        });

        $(function () {
            // Initialize datepicker
            $('.datepick').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true
            });
        });

        $('#reportTable1').DataTable({
            dom: 'Bfrtip',
            "scrollX": true,
            "pageLength": 15,
            buttons: [
                {
                    extend: 'print',
                    text: 'Print', // Button text
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
                },
                'pageLength'  // Show page length options
            ],
            lengthMenu: [
                [10, 25, 50, -1],
                ['10 rows', '25 rows', '50 rows', 'Show all']
            ],
            "footerCallback": function (row, data, start, end, display) {
                var api = this.api(), data;

                // Remove the formatting to get integer data for summation
                var intVal = function (i) {
                    return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : (typeof i === 'number' ? i : 0);
                };

                // Total over all pages
                total = api
                    .column(4)
                    .data()
                    .reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                // Total over this page
                pageTotal = api
                    .column(4, { page: 'current' })
                    .data()
                    .reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                // Update footer
                $(api.column(4).footer()).html(pageTotal + ' (' + total + ' Total)');
            }
        });
    });

    $(".alert").delay(4000).slideUp(200, function() {
        $(this).alert('close');
    });

    $('a.delete').on('click', function() {
        var choice = confirm('Do you really want to delete this record?');
        if(choice === true) {
            return true;
        }
        return false;
    });
</script>