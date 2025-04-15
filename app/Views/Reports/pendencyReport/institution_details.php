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
                    <!-- <div class="card-header heading">
                        <div class="row">
                            <div class="col-md-12">
                                <h3 class="card-title">Disposal Order Date</h3>
                            </div>
                        </div>
                    </div> -->
                    <div class="card-body">
                        <div class="mt-2">
                            <div class="col-12 col-md-12">
                                <section class="content">
                                    <?php
                                    if(is_array($case_result))
                                    {
                                        //var_dump($param);
                                    ?>
                                    <div id="printable" class="box box-danger">
                                        <div id="caption">
                                                <h3 style="text-align: center;">Total Institution matters between <?php echo date('d-m-Y', strtotime($param[0])); ?> and <?php echo date('d-m-Y', strtotime($param[1])); ?></h3>
                                        </div>

                                        <?php
                                        if($app_name=='Institution')
                                        {
                                            ?>
                                        <table width="100%"  id="reportTable1" class="table table-striped table-hover">
                                            <thead>
                                            <tr>
                                                <th width="5%">S.No.</th>
                                                <th width="15%" style="text-align: left;">Diary No<br/>#Diary Year</th>
                                               <th width="20%">Registration <br> No</th>
                                                <th width="40%">Cause <br> Title</th>
                                                <th width="15%">file date</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            $s_no=1;
                                            foreach ($case_result as $result)
                                            {
                                                ?>
                                            <tr>
                                                <td><?php echo $s_no;?></td>
                                                <td><?php echo $result['diary_no'];?> / <?php echo $result['diary_year'];?> </td>
                                                <td><?php echo $result['reg_no_display'];?></td>
                                                <td><?php echo $result['pet_name'].' Vs. '. $result['res_name'];?></td>
                                                <td><?php echo date('d-m-Y',strtotime($result['fil_dt']));?></td>
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
                                    } else {
                                        echo "No Data Found";   
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

    $(document).ready(function() {
        $(function () {
            //Initialize Select2 Elements
            $(".select2").select2();
        });
        $(function () {
            $('.datepick').datepicker({
                format: 'dd-mm-yyyy',
                autoclose:true
            });
        });
        var t =$('#reportTable1').DataTable( {
            dom: 'Bfrtip',
            "scrollX": true,
            "pageLength":15,
            buttons: [
                {   
                    extend:'print',
                    title:'',
                    customize: function ( win ) {
                        $(win.document.body).css('font-size', '12pt');
                        const captionHTML = $('#caption').html();
                        $(win.document.body).find('table').before('<div style="text-align:center; margin-bottom:20px;">' + captionHTML + '</div>');
                    }
                },
                'pageLength'
            ],
            lengthMenu: [
                [ 10, 25, 50, -1 ],
                [ '10 rows', '25 rows', '50 rows', 'Show all' ]
            ],

            "footerCallback": function ( row, data, start, end, display ) {
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
            },
            "columnDefs": [ {
            "searchable": false,
            "orderable": false,
            "targets": 0
        } ],
            "order": [[ 1, 'asc' ]]

        } );

        t.on( 'order.dt search.dt', function () {
            t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = i+1;
                t.cell(cell).invalidate('dom');
            } );
        } ).draw();
    } );
</script>