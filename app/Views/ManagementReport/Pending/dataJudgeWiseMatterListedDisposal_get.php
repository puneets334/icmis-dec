<style>
     table.dataTable>thead .sorting,
    table.dataTable>thead {
        background-color: #0d48be !important;
        color: #fff !important;
    }

    table.dataTable>thead .sorting_disabled,
    table.dataTable>thead {
        background-color: #0d48be !important;
        color: #fff !important;
    }
    .dataTables_filter
    {
        margin-top: -48px;
    }
    div.dataTables_wrapper {
    width: 100%;
}

</style>


<!-- Report Div Start -->

<div class="wrapper" >
    <div class="content-wrapper" style="margin-left:0px!important">
        <div class="container-fluid">
            <!-- Main content -->
            <section class="content">

                <?php
                // pr($reports);
                // die();
                //var_dump($mentioningReports);
                $other_disposal = 0;
                $total_listed = 0;
                $total_disposed = 0;
                $total_misc_listed=0;
                $total_regular_listed=0;
                $total_misc_disposed=0;
                $total_regular_disposed=0;
                if(is_array($reports) && count($reports)>0) 
                {
                    if(count($reports['disposal'])>0){
                ?>
                <div id="printable" class="box box-danger">

                        <table width="100%" id="reportTable" class="table table-striped table-hover"">
                <thead>
                <?php
                if($app_name=='JudgeWiseMatterListedDisposal')
                {
                    
                if($jCode=='0') { ?>
                    <h3 style="text-align: center;"> Hon'ble Judge wise Matters Listed and Disposed between <strong><?=$from_date?></strong> and <strong><?=$to_date?></strong></h3>
                <?php } else {
                    ?>
                    <h3 style="text-align: center;"><strong><?=$reports['disposal'][0]['jname']?> </strong>Matters Listed and Disposed between <strong><?=$from_date?></strong> and <strong><?=$to_date?></strong></h3>
                <?php } ?>
                <tr>
                    <th>Hon'ble Judge Name</th>
                    <th>Listed <br/>Misc </th>
                    <th>Listed <br/> Regular</th>

                    <th>Listed <br/> Total </th>

                    <th>Disposed <br/> Misc</th>

                    <th>Disposed <br/> Regular </th>

                    <th>Disposed <br/> Total </th>


                </tr>
                </thead>
                <!--<tfoot>
                <tr>
                    <th colspan="5" style="text-align:right">Total:</th>
                </tr>
                </tfoot> -->
                <tbody>
                <?php
                $total_listed_misc_main=$total_listed_misc_conn=$total_listed_regular_main=$total_listed_regular_conn=0;
                $total_disposed_misc_main=$total_disposed_misc_conn=$total_disposed_regular_main=$total_disposed_regular_conn=0;
                $total_listed=$total_disposed=0;
                $s_no=1;
               
                $other_disposal = $reports['other_disposal'][0]['other_disp'];
                foreach ($reports['disposal'] as $result)
                {
                   // pr($result);
                    $total_listed_judge_wise=$total_disposed_judge_wise=0;
                    $total_listed_misc_main+=$result['listed_misc_main'];
                    $total_listed_misc_conn+=$result['listed_misc_conn'];
                    $total_listed_regular_main+=$result['listed_regular_main'];
                    $total_listed_regular_conn+=$result['listed_regular_conn'];

                    $total_listed_judge_wise=$result['listed_total_main']+$result['listed_total_conn'];

                    $total_disposed_misc_main+=$result['disposed_misc_main'];
                    $total_disposed_misc_conn+=$result['disposed_misc_conn'];
                    $total_disposed_regular_main+=$result['disposed_regular_main'];
                    $total_disposed_regular_conn+=$result['disposed_regular_conn'];

                    $total_disposed_judge_wise=$result['disposed_total_main']+$result['disposed_total_conn'];
                    ?>
                    <tr>
                        <td><?php echo $result['jname'];?> (<?php echo $result['jcode'];?>) </td>
                        <td><a href="8/LMM/<?=$from_date?>/<?=$to_date?>/<?=$result['jcode']?>" target="_blank"><?php echo $result['listed_misc_main'];?></a>
                        (+ <a href="8/LMC/<?=$from_date?>/<?=$to_date?>/<?=$result['jcode']?>" target="_blank"><?php echo $result['listed_misc_conn'];?></a>)</td>

                        <td><a href="8/LRM/<?=$from_date?>/<?=$to_date?>/<?=$result['jcode']?>" target="_blank"><?php echo $result['listed_regular_main'];?></a>
                        (+<a href="8/LRC/<?=$from_date?>/<?=$to_date?>/<?=$result['jcode']?>" target="_blank"><?php echo $result['listed_regular_conn'];?></a>)</td>

                        <td><a href="8/LTM/<?=$from_date?>/<?=$to_date?>/<?=$result['jcode']?>" target="_blank"><?php echo $result['listed_total_main'];?></a>
                        (+ <a href="8/LTC/<?=$from_date?>/<?=$to_date?>/<?=$result['jcode']?>" target="_blank"><?php echo $result['listed_total_conn'];?></a>) = <?php echo $total_listed_judge_wise;?></td>

                        <td><a href="8/DMM/<?=$from_date?>/<?=$to_date?>/<?=$result['jcode']?>" target="_blank"><?php echo $result['disposed_misc_main'];?></a>
                        (+ <a href="8/DMC/<?=$from_date?>/<?=$to_date?>/<?=$result['jcode']?>" target="_blank"><?php echo $result['disposed_misc_conn'];?></a>)</td>
                        
                        <td><a href="8/DRM/<?=$from_date?>/<?=$to_date?>/<?=$result['jcode']?>" target="_blank"><?php echo $result['disposed_regular_main'];?></a>
                        (+ <a href="8/DRC/<?=$from_date?>/<?=$to_date?>/<?=$result['jcode']?>" target="_blank"><?php echo $result['disposed_regular_conn'];?></a>)</td>
                     
                        <td><a href="8/DTM/<?=$from_date?>/<?=$to_date?>/<?=$result['jcode']?>" target="_blank"><?php echo $result['disposed_total_main'];?></a>
                        (+<a href="8/DTC/<?=$from_date?>/<?=$to_date?>/<?=$result['jcode']?>" target="_blank"><?php echo $result['disposed_total_conn'];?></a>)= <?php echo $total_disposed_judge_wise;?></td>
                        <!-- <th><a target="_blank" onclick="return confirm('Delete this record?')" href="#"><i class="glyphicon glyphicon-trash"></i></a></th>-->

                    </tr>
                    
                    <?php
                    $s_no++;
                }   //for each
                $total_misc_listed=$total_listed_misc_main+$total_listed_misc_conn;
                $total_regular_listed=$total_listed_regular_main+$total_listed_regular_conn;

                $total_misc_disposed=$total_disposed_misc_main+$total_disposed_misc_conn;
                $total_regular_disposed=$total_disposed_regular_main+$total_disposed_regular_conn;

                $total_listed=$total_listed_misc_main+$total_listed_misc_conn+$total_listed_regular_main+$total_listed_regular_conn;
                $total_disposed=$total_disposed_misc_main+$total_disposed_misc_conn+$total_disposed_regular_main+$total_disposed_regular_conn;
                ?>
                </tbody>
                <tfoot style="text-align:left" id="sum">
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        

                        <table width="100%" style="font-size: 20px;">
                            <tr>
                                <th colspan="3">Other Disposal :</th>
                                <th colspan="3"><?=$other_disposal;?></div></th>
                    </tr>
                            <tr>
                                <th colspan="3">Total Listed :</th>
                                <th colspan="3"><div id="gListedTotal"></div></th>
                                <th colspan="3">Total Disposed :</th>
                                <th colspan="3"><div  id="gDisposedTotal"></div></th>
                            </tr>

                        </table>
                    </tr>

                </tfoot>
            </table>

            <?php
            }
        }else{?>
        <table width="100%" id="reportTable" class="table table-striped table-hover"">
                <thead>
                    <h3 style="text-align: center;"> Hon'ble Judge wise Matters Listed and Disposed between <strong><?=$from_date?></strong> and <strong><?=$to_date?></strong></h3>
                
                <tr>
                    <th>Hon'ble Judge Name</th>
                    <th>Listed <br/>Misc </th>
                    <th>Listed <br/> Regular</th>

                    <th>Listed <br/> Total </th>

                    <th>Disposed <br/> Misc</th>

                    <th>Disposed <br/> Regular </th>

                    <th>Disposed <br/> Total </th>


                </tr>
                </thead>
                <tbody><tr><td colspan="7" align="center">No Records Found</td></tr></tbody>
        </table>

        <?php }
                    }
                    ?>
                </div>
            </section>
            <!-- Report Div End -->
        </div>
    </div>
</div>


<script src="<?=base_url()?>assets/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="<?=base_url()?>assets/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<script src="<?=base_url()?>assets/js/bootstrap.min.js"></script>
<script src="<?=base_url()?>assets/plugins/fastclick/fastclick.js"></script>
<script src="<?=base_url()?>assets/js/app.min.js"></script>
<script src="<?=base_url()?>assets/js/Reports.js"></script>
<script src="<?=base_url()?>assets/jsAlert/dist/sweetalert.min.js"></script>
<script src="<?=base_url()?>assets/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?=base_url()?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?=base_url()?>assets/plugins/datatables/dataTables.buttons.min.js"></script>
<script src="<?=base_url()?>assets/plugins/datatables/buttons.print.min.js"></script>
<script>


    $(document).ready(function()
    {
        var reportTitle = "Hon'ble Judge wise Matters Listed and Disposed between <?= $from_date ?> and <?= $to_date ?>";
        $(function () {
            $('.datepick').datepicker({
                format: 'dd-mm-yyyy',
                todayHighlight: true,
                autoclose:true
            });
        });
        $('#reportTable').DataTable( {
            "bSort": false,
            dom: 'Bfrtip',
            "scrollX": true,
            iDisplayLength: 30,

            buttons: [
                {
                    extend:'print',
                    footer:true,
                    orientation: 'landscape',
                    pageSize: 'A4',
                    title: reportTitle,

                    customize: function ( win ) {
                        $(win.document.body)
                            .css( 'font-size', '10pt' )
                            .append(
                                    '<p style="float:left;width=50%;font-weight: bold;font-size: 20px;word-spacing: 5px;letter-spacing: 1px;color: Blue;margin-left: 150px;">Other Disposal :<?=$other_disposal?></p>'+
                                    '<br/>'+
                                    '<p style="float:left;width=50%;font-weight: bold;font-size: 20px;word-spacing: 5px;letter-spacing: 1px;color: Blue;margin-left: 150px;">Total Listed :<?=$total_listed?></p>' +
                                    '<p style="float:right;width=50%;font-weight: bold;font-size: 20px;word-spacing: 5px;letter-spacing: 1px;color: Blue;margin-right: 150px;">Total Disposed :<?=$total_disposed+$other_disposal?></p>'
                            );

                        $(win.document.body).find( 'table' )
                            .addClass( 'compact' )
                            .css( 'font-size', 'inherit' );
                    }
                }
            ],
           /* lengthMenu: [
                [ 10, 25, 50, -1 ],
                [ '10 rows', '25 rows', '50 rows', 'Show all' ]
            ],*/

            "footerCallback": function ( row, data, start, end, display ) {
                var api = this.api(), data;
                // Remove the formatting to get integer data for summation
                var intVal = function ( i ) {
                    return typeof i === 'string' ?
                        i = $(i).text().replace(/[^\d.-]/g, '') * 1:
                        typeof i === 'number' ?
                            i : 0;
                };

                // Total over all pages


                $( api.column(0).footer('#sum') ).html('Total');
                $( api.column(1).footer('#sum')).html('<?=$total_misc_listed?>');
                $( api.column(2).footer('#sum')).html('<?=$total_regular_listed?>');
                $( api.column(3).footer('#sum')).html('<?=$total_listed?>');
                $( api.column(4).footer('#sum')).html('<?=$total_misc_disposed?>');
                $( api.column(5).footer('#sum')).html('<?=$total_regular_disposed?>');
                $( api.column(6).footer('#sum')).html('<?=$total_disposed?>');


              /* gradTotalListed=total5+total6;
               grandTotalDisposed=total11+total12;*/

                var secondRow = $(row).next()[0];

                //$('#gsum').html('Grand Total');

                al="8/AL/<?=$from_date?>/<?=$to_date?>/<?=$jCode?>";

                $('#gListedTotal').html('<a href="' + al + '" target="_blank"><?=$total_listed?></a>');
                ad="8/AD/<?=$from_date?>/<?=$to_date?>/<?=$jCode?>";
                $('#gDisposedTotal').html('<a href="' + ad + '" target="_blank"><?=$total_disposed+$other_disposal?></a>');
            }
        } );
    } );

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