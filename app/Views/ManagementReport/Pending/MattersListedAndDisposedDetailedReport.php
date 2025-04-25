

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
.dataTables_wrapper .dataTables_paginate {
    float: right;
    text-align: right;
    padding-top: 0.25em;
}
</style>

            <?php
            //var_dump($mentioningReports);
            $head ='';
            if(is_array($reports))
            {
            ?>
            <div>
            <?php
            
            if($param[2]=='AL')
            {
                if($param[3]=='0') { ?>
            <h3 id="heading" style="text-align: center;">Matters Listed between <strong><?=$param[0]?></strong> and <strong><?=$param[1]?></strong><BR> To All Hon'ble Judges </h3>
                <?php $head = "Matters Listed between ".$param[0]." and ".$param[1]." To All Hon'ble Judges"; } else {
                        $head = "Matters Listed between ".$param[0]." and ".$param[1]." To ".$reports[0]['jname']." (".$param[3]."Court";
                    ?>
                    <h3 id="heading" style="text-align: center;"> Matters Listed  between <strong><?=$param[0]?></strong> and <strong><?=$param[1]?></strong><BR> To <strong><?=$reports[0]['jname']?>(<?=$param[3]?>)</strong> Court</h3>
                <?php }
            }
            if($param[2]=='AD')
            {
                if($param[3]=='0') { ?>
                    <h3 id="heading" style="text-align: center;"> Matters Disposed between <strong><?=$param[0]?></strong> and <strong><?=$param[1]?></strong><BR> By All Hon'ble Judges</h3>
                <?php $head = "Matters Disposed between ".$param[0]." and ".$param[1]." To All Hon'ble Judges"; } else {
                        $head = "Matters Disposed between ".$param[0]." and ".$param[1]." To ".$reports[0]['jname']." (".$param[3]."Court";
                    ?>
                    <h3 id="heading" style="text-align: center;"> Matters  Disposed between <strong><?=$param[0]?></strong> and <strong><?=$param[1]?></strong><BR> By <strong><?=$reports[0]['jname']?>(<?=$param[3]?>)</strong> Court</h3>
                <?php }
            }
            if($param[2]=='LMM')
            {
                if($param[3]=='0')
                { ?>
                    <h3 id="heading" style="text-align: center;">Misc Main Matters Listed between <strong><?=$param[0]?></strong> and <strong><?=$param[1]?></strong> <BR>To All Hon'ble Judges</h3>
                    <?php $head = "Misc Main Matters Listed between ".$param[0]." and ".$param[1]." To All Hon'ble Judges"; } else {
                        $head = "Misc Main Matters Listed between ".$param[0]." and ".$param[1]." To ".$reports[0]['jname']." (".$param[3]."Court";
                        ?>
                        <h3 id="heading" style="text-align: center;">Misc Main Matters  Listed between <strong><?=$param[0]?></strong> and <strong><?=$param[1]?></strong> <BR>To <strong><?=$reports[0]['jname']?>(<?=$param[3]?>)</strong> Court</h3>
                    <?php
                }
            }
            if($param[2]=='LMC')
            {
                if($param[3]=='0')
                { ?>
                    <h3 id="heading" style="text-align: center;">Misc Connected Matters Listed between <strong><?=$param[0]?></strong> and <strong><?=$param[1]?></strong> <BR>To All Hon'ble Judges</h3>
                <?php $head = "Misc Connected Matters Listed between ".$param[0]." and ".$param[1]." To All Hon'ble Judges"; } else {
                   $head = "Misc Connected Matters Listed between ".$param[0]." and ".$param[1]." By ".$reports[0]['jname']."(".$param[3].") Court"; 
                    ?>
                    <h3 id="heading" style="text-align: center;"> Misc Connected Matters  Listed between <strong><?=$param[0]?></strong> and <strong><?=$param[1]?></strong> <BR>To <strong><?=$reports[0]['jname']?>(<?=$param[3]?>)</strong> Court</h3>
                <?php
                }
            }
            if($param[2]=='LRM')
            {
                if($param[3]=='0')
                { ?>
                    <h3 id="heading" style="text-align: center;"> Regular Main Matters Listed between <strong><?=$param[0]?></strong> and <strong><?=$param[1]?></strong> <BR>To All Hon'ble Judges</h3>
                <?php $head = "Regular Main Matters Listed between ".$param[0]." and ".$param[1]." To All Hon'ble Judges"; } else {
                    $head = "Regular Main Matters Listed between ".$param[0]." and ".$param[1]." By ".$reports[0]['jname']."(".$param[3].") Court"; 
                    ?>
                    <h3 id="heading" style="text-align: center;"> Regular Main Matters Listed between <strong><?=$param[0]?></strong> and <strong><?=$param[1]?></strong> <BR>To <strong><?=$reports[0]['jname']?>(<?=$param[3]?>)</strong> Court</h3>
                <?php
                }
            }
            if($param[2]=='LRC')
            {
                if($param[3]=='0')
                { ?>
                    <h3 id="heading" style="text-align: center;"> Regular Connected Matters Listed between <strong><?=$param[0]?></strong> and <strong><?=$param[1]?></strong> <BR>To All Hon'ble Judges</h3>
                <?php $head = "Regular Connected Matters Listed between ".$param[0]." and ".$param[1]." To All Hon'ble Judges"; } else {
                    $head = "Regular Connected Matters Listed between ".$param[0]." and ".$param[1]." By ".$reports[0]['jname']."(".$param[3].") Court"; 
                    ?>
                    <h3 id="heading" style="text-align: center;"> Regular Connected Matters  Listed between <strong><?=$param[0]?></strong> and <strong><?=$param[1]?></strong> <BR>To <strong><?=$reports[0]['jname']?>(<?=$param[3]?>)</strong> Court</h3>
                <?php
                }
            }
            if($param[2]=='LTM')
            {
                if($param[3]=='0')
                { ?>
                    <h3 id="heading" style="text-align: center;"> Total  Main Matters Listed between <strong><?=$param[0]?></strong> and <strong><?=$param[1]?></strong> <BR>To All Hon'ble Judges</h3>
                <?php $head = "Total  Main Matters Listed between ".$param[0]." and ".$param[1]." To All Hon'ble Judges"; } else { 
                    $head = "Total  Main Matters Listed between ".$param[0]." and ".$param[1]." By ".$reports[0]['jname']."(".$param[3].") Court"; 
                    ?>
                    <h3 id="heading" style="text-align: center;"> Total  Main Matters Listed between <strong><?=$param[0]?></strong> and <strong><?=$param[1]?></strong> <BR>To <strong><?=$reports[0]['jname']?>(<?=$param[3]?>)</strong> Court</h3>
                <?php
                }
            }
            if($param[2]=='LTC')
            {
                if($param[3]=='0')
                { ?>
                    <h3 id="heading" style="text-align: center;"> Total Connected Matters Listed between <strong><?=$param[0]?></strong> and <strong><?=$param[1]?></strong> <BR>To All Hon'ble Judges</h3>
                <?php $head = "Total Connected Matters Listed between ".$param[0]." and ".$param[1]." To All Hon'ble Judges"; } else {
                    $head = "Total Connected Matters Listed between ".$param[0]." and ".$param[1]." By ".$reports[0]['jname']."(".$param[3].") Court"; 
                    ?>
                    <h3 id="heading" style="text-align: center;"> Total Connected Matters Listed between <strong><?=$param[0]?></strong> and <strong><?=$param[1]?></strong> <BR>To <strong><?=$reports[0]['jname']?>(<?=$param[3]?>)</strong> Court</h3>
                <?php
                }
            }

            if($param[2]=='DMM')
            {
                if($param[3]=='0')
                { ?>
                    <h3 id="heading" style="text-align: center;">Misc Main Matters Disposed between <strong><?=$param[0]?></strong> and <strong><?=$param[1]?></strong> <BR>By All Hon'ble Judges</h3>
                <?php $head = "Misc Main Matters Disposed between ".$param[0]." and ".$param[1]." To All Hon'ble Judges"; } else {
                    $head = "Misc Main Matters Disposed between ".$param[0]." and ".$param[1]." By ".$reports[0]['jname']."(".$param[3].") Court"; 
                    ?>
                    <h3 id="heading" style="text-align: center;">Misc Main Matters  Disposed between <strong><?=$param[0]?></strong> and <strong><?=$param[1]?></strong> <BR>By <strong><?=$reports[0]['jname']?>(<?=$param[3]?>)</strong> Court</h3>
                <?php
                }
            }
            if($param[2]=='DMC')
            {
                if($param[3]=='0')
                { ?>
                    <h3 id="heading" style="text-align: center;">Misc Connected Matters Disposed between <strong><?=$param[0]?></strong> and <strong><?=$param[1]?></strong> <BR>By All Hon'ble Judges</h3>
                <?php $head = "Misc Connected Matters Disposed between ".$param[0]." and ".$param[1]." To All Hon'ble Judges"; } else {
                    $head = "Misc Connected Matters Disposed between ".$param[0]." and ".$param[1]." By ".$reports[0]['jname']."(".$param[3].") Court"; 
                    ?>
                    <h3 id="heading" style="text-align: center;"> Misc Connected Matters  Disposed between <strong><?=$param[0]?></strong> and <strong><?=$param[1]?></strong> <BR>By <strong><?=$reports[0]['jname']?>(<?=$param[3]?>)</strong> Court</h3>
                <?php
                }
            }
            if($param[2]=='DRM')
            {
                if($param[3]=='0')
                { ?>
                    <h3 id="heading" style="text-align: center;"> Regular Main Matters Disposed between <strong><?=$param[0]?></strong> and <strong><?=$param[1]?></strong> <BR>By All Hon'ble Judges</h3>
                <?php $head = "Regular Main Matters Disposed between ".$param[0]." and ".$param[1]." To All Hon'ble Judges"; } else {
                    $head = "Regular Main Matters Disposed between ".$param[0]." and ".$param[1]." By ".$reports[0]['jname']."(".$param[3].") Court"; 
                    ?>
                    <h3 id="heading" style="text-align: center;"> Regular Main Matters Disposed between <strong><?=$param[0]?></strong> and <strong><?=$param[1]?></strong> <BR>By <strong><?=$reports[0]['jname']?>(<?=$param[3]?>)</strong> Court</h3>
                <?php
                }
            }
            if($param[2]=='DRC')
            {
                if($param[3]=='0')
                { ?>
                    <h3 id="heading" style="text-align: center;"> Regular Connected Matters Disposed between <strong><?=$param[0]?></strong> and <strong><?=$param[1]?></strong> <BR>By All Hon'ble Judges</h3>
                <?php $head = "Regular Connected Matters Disposed between ".$param[0]." and ".$param[1]." To All Hon'ble Judges"; } else {
                    $head = "Regular Connected Matters Disposed between ".$param[0]." and ".$param[1]." By ".$reports[0]['jname']."(".$param[3].") Court"; 
                    ?>
                    <h3 id="heading" style="text-align: center;"> Regular Connected Matters  Disposed between <strong><?=$param[0]?></strong> and <strong><?=$param[1]?></strong> <BR>By <strong><?=$reports[0]['jname']?>(<?=$param[3]?>)</strong> Court</h3>
                <?php
                }
            }
            if($param[2]=='DTM')
            {
                if($param[3]=='0')
                { ?>
                    <h3 id="heading" style="text-align: center;"> Total  Main Matters Disposed between <strong><?=$param[0]?></strong> and <strong><?=$param[1]?></strong> <BR>By All Hon'ble Judges</h3>
                <?php $head = "Total  Main Matters Disposed between ".$param[0]." and ".$param[1]." To All Hon'ble Judges"; } else {
                    $head = "Total  Main Matters Disposed between ".$param[0]." and ".$param[1]." By ".$reports[0]['jname']."(".$param[3].") Court"; 
                    ?>
                    <h3 id="heading" style="text-align: center;"> Total  Main Matters Disposed between <strong><?=$param[0]?></strong> and <strong><?=$param[1]?></strong> <BR>By <strong><?=$reports[0]['jname']?>(<?=$param[3]?>)</strong> Court</h3>
                <?php
                }
            }
            if($param[2]=='DTC')
            {
                if($param[3]=='0')
                { ?>
                    <h3 id="heading" style="text-align: center;"> Total Connected Matters Disposed between <strong><?=$param[0]?></strong> and <strong><?=$param[1]?></strong> <BR>By All Hon'ble Judges</h3>
                <?php $head = "Total Connected Matters Disposed between ".$param[0]." and ".$param[1]." By All Hon'ble Judges"; } else {
                    $head = "Total Connected Matters Disposed between ".$param[0]." and ".$param[1]." By ".$reports[0]['jname']."(".$param[3].") Court"; 
                    ?>
                    <h3 id="heading" style="text-align: center;"> Total Connected Matters Disposed between <strong><?=$param[0]?></strong> and <strong><?=$param[1]?></strong> By <strong><?=$reports[0]['jname']?>(<?=$param[3]?>)</strong> Court</h3>
                <?php
                }
            }

            ?> </div>
                        <div id="printable" class="box box-danger">
                        
                    
               
                <?php
                if($app_name=='JudgesWiseMattersListedAndDisposedDetailed')
                {
                   // var_dump($param);
                    foreach ($reports as $result)
                    {
                        $jname=$result['jname'];
                        $jcode=$result['jcode'];
                    }?>
            <table class="table table-striped custom-table" id="example1">
                
                <thead>
                <tr>
                    <th width="5%">S.No.</th>
                    <th width="10%" style="text-align: left;">Diary No<br/>#Diary Date</th>
                    <th width="10%">Registration <br> No</th>
                    <th width="7%" >List <br> Date</th>
                    <th width="7%" >Court No / Item No </th>
                    <th width="20%">Cause <br> Title</th>
                    <th width="6%">Section</th>
                    <th width="7%">Dealing <br> Assistant</th>
                    <th width="6%" style="text-align: right;">Misc <br> /Regular</th>
                    <th width="6%" style="text-align: right;">Main<br>/Connected</th>

                </tr>
                </thead>
                <tbody>
                <?php
                $s_no=1;
                foreach ($reports as $result)
                {
                    ?>
                    <tr>
                        <td><?php echo $s_no;?></td>
                        <td><?php echo $result['diary_no'];?> / <?php echo $result['diary_year'];?> # <?php echo date('d-m-Y',strtotime($result['diary_date']));?></td>
                        <td><?php echo $result['reg_no_display'];?> </td>
                        <td><?php echo date('d-m-Y',strtotime($result['next_dt']));?></td>
                        <td><?php echo $result['courtno'].' / '.$result['brd_slno'];?></td>
                        <td><?php echo $result['pet_name'];?> <strong> Vs.</strong><?php echo $result['res_name'];?></td>
                        <td><?php echo $result['user_section'];?></td>
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
            }
            }
                else
                {
                    ?>
                    <div class="alert alert-success">
                        <strong>No </strong> Record Found.
                    </div>
                <?php
                }
            ?>
                
                <link rel="stylesheet" href="<?php echo base_url('assets/vendor/datatables-bs4/css/dataTables.bootstrap4.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/vendor/datatables-responsive/css/responsive.bootstrap4.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/vendor/datatables-buttons/css/buttons.bootstrap4.min.css'); ?>">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo base_url('assets/libs/css/admin.min.css'); ?>">


    <!-- <link rel="stylesheet" href="<?php echo base_url('assets/libs/css/style.css'); ?>"> -->
    <link rel="stylesheet" href="<?php echo base_url('assets/libs/css/mystyle.css'); ?>">
    <script src="<?php echo base_url('assets/vendor/jquery/jquery.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>


    <script src="<?php echo base_url('assets/vendor/moment/moment.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/vendor/inputmask/jquery.inputmask.min.js'); ?>"></script>
    <!-- date-range-picker -->

    <!-- bootstrap color picker -->
    <script src="<?php echo base_url('assets/vendor/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/vendor/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js'); ?>"></script>
    <!-- Bootstrap Switch -->
    <script src="<?php echo base_url('assets/vendor/bootstrap-switch/js/bootstrap-switch.min.js'); ?>"></script>
    <!-- BS-Stepper -->
    <script src="<?php echo base_url('assets/vendor/bs-stepper/js/bs-stepper.min.js'); ?>"></script>
    <!-- dropzonejs -->
    <script src="<?php echo base_url('assets/vendor/dropzone/min/dropzone.min.js'); ?>"></script>
    <script src="<?php echo base_url('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js'); ?>"></script>

    <script src="<?=base_url('js/app.min.js')?>"></script>

    <script src="<?=base_url('js/angular.min.js')?>"></script>
    <script src="<?php echo base_url('assets/vendor/datatables/jquery.dataTables.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/vendor/datatables-bs4/js/dataTables.bootstrap4.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/vendor/datatables-responsive/js/dataTables.responsive.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/vendor/datatables-responsive/js/responsive.bootstrap4.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/vendor/datatables-buttons/js/dataTables.buttons.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/vendor/datatables-buttons/js/buttons.bootstrap4.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/vendor/jszip/jszip.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/vendor/pdfmake/pdfmake.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/vendor/pdfmake/vfs_fonts.js'); ?>"></script>
    <script src="<?php echo base_url('assets/vendor/datatables-buttons/js/buttons.html5.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/vendor/datatables-buttons/js/buttons.print.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/vendor/datatables-buttons/js/buttons.colVis.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/vendor/datatables-buttons/js/buttons.colVis.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/vendor/datatables-buttons/js/buttons.colVis.min.js'); ?>"></script>
    <script src="<?php echo base_url('js/nav_link.js'); ?>"></script>
    <script src="<?php echo base_url('js/customize_style.js'); ?>"></script>
    <script>
        var filename = "<?php echo $head; ?>";
        var title = "<?php echo $head; ?>";

        $(document).ready(function() {
            $('#example1').DataTable( {
                dom: 'Bfrtip',
                buttons: [
                    
                    {
                        extend: 'print',className: 'btn btn-primary glyphicon glyphicon-print',
                        //filename: filename,
                        title: title,
                        pageSize: 'A4',
                        // orientation: 'landscape',
                        text: 'Print',
                        autoWidth: false,
                        columnDefs: [{
                            "width": "20px", "targets":[0] }],

                        customize: function ( win )
                            {
                                $(win.document.body).find('h1').css('font-size', '20px');
                                $(win.document.body).find('h1').css('text-align', 'left');
                                $(win.document.body).find('tab').css('width', 'auto');

                                var last = null;
                                var current = null;
                                var bod = [];

                                var css = '@page { size: landscape; }',
                                    head = win.document.head || win.document.getElementsByTagName('head')[0],
                                    style = win.document.createElement('style');

                                style.type = 'text/css';
                                style.media = 'print';

                                if (style.styleSheet)
                                {
                                    style.styleSheet.cssText = css;
                                }
                                else
                                {
                                    style.appendChild(win.document.createTextNode(css));
                                }

                                head.appendChild(style);

                            }

                    }
                ],

                paging: true,
                ordering: false,
                info: false,
                // columnDefs: [{"width": "20px", "targets": [0]},
                //                 {"width": "40px", "targets": [1]},
                //                 {"width": "250px", "targets": [2]}],
                searching: true,


            } );
        } );


</script>