<link rel="stylesheet" href="<?php echo base_url('Ajaxcalls/menu_assign/googlefonticon.css'); ?>">
    <!-- BS Stepper -->
    <!-- DataTables -->
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
<?php
if (count($result_array) > 0) {
?>
<div id="prnnt" style="text-align: center;">
    <h3 style="text-align:center;">Pre Notice and After Notice matters as on <?php echo date("d-m-Y h:i:s A"); ?></h3>
    <table id="tab" class="table table-striped custom-table">
        <thead>
            <tr>
                <th>Notice</th>
                <th>Fix dt</th>
                <th>Mentioning</th>
                <th>Week Commencing</th>
                <th>Freshly Filed</th>
                <th>Freshly Filed Adj</th>
                <th>Part Heard</th>
                <th>Inperson</th>
                <th>Bail</th>
                <th>After Week</th>
                <th>Imp IA</th>
                <th>Other IA</th>
                <th>Not reached/Adj</th>
                <th>ADM order</th>
                <th>Ordinary</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php
             $tot_fx_dt = 0;
             $tot_mentioning = 0;
             $tot_week_commencing = 0;
             $tot_freshly_filed = 0;
             $tot_freshly_filed_adj = 0;
             $tot_part_heard = 0;
             $tot_inperson = 0;
             $tot_bail = 0;
             $tot_after_week = 0;
             $tot_imp_ia = 0;
             $tot_other_ia = 0;
             $tot_nradj_not_list = 0;
             $tot_adm_order = 0;
             $tot_ordinary = 0;
             $tot_tot = 0;
            $sno = 1;
            foreach ($result_array as $data) {
                if($data['notice'] == 'Pre_Notice_Ready')
             {
                 $data['notice'] = 'Pre Notice Ready';
             }
             elseif($data['notice'] == 'Pre_Notice_Updation_Awaited')
             {
                 $data['notice'] = 'Pre Notice Updation Awaited';
             }
             elseif($data['notice'] == 'Pre_Notice_Not_Ready')
             {
                 $data['notice'] = 'Pre Notice Not Ready';
             }
             elseif($data['notice'] == 'After_Notice_Ready')
             {
                 $data['notice'] = 'After Notice Ready';
             }
             elseif($data['notice'] == 'After_Notice_Not_Ready')
             {
                 $data['notice'] = 'After Notice Not Ready';
             }



             $tot_fx_dt = $tot_fx_dt + $data['fix_dt'];
             $tot_mentioning = $tot_mentioning + $data['mentioning'];
             $tot_week_commencing = $tot_week_commencing + $data['week_commencing'];
             $tot_freshly_filed = $tot_freshly_filed + $data['freshly_filed'];
             $tot_freshly_filed_adj = $tot_freshly_filed_adj + $data['freshly_filed_adj'];
             $tot_part_heard = $tot_part_heard + $data['part_heard'];
             $tot_inperson = $tot_inperson + $data['inperson'];
             $tot_bail = $tot_bail + $data['bail'];
             $tot_after_week = $tot_after_week + $data['after_week'];
             $tot_imp_ia = $tot_imp_ia + $data['imp_ia'];
             $tot_other_ia = $tot_other_ia + $data['ia_other_than_imp_ia'];
             $tot_nradj_not_list = $tot_nradj_not_list + $data['nradj_not_list'];
             $tot_adm_order = $tot_adm_order + $data['adm_order'];
             $tot_ordinary = $tot_ordinary + $data['ordinary'];
             $tot_tot = $tot_tot + $data['total'];
                $sno1 = $sno % 2;
                if ($sno1 == '1') { ?>
                    <tr>
                    <?php } else { ?>
                    <tr>
                    <?php
                }
                    ?>
                    
                        <td style="padding: 6px 1px 6px 5px;"><?php echo $data['notice']; ?></td>
                        <td style="text-align:center;"><?php echo $data['fix_dt']; ?></td>
                        <td style="text-align:center;"><?php echo $data['mentioning']; ?></td>
                        <td style="text-align:center;"><?php echo $data['week_commencing']; ?></td>
                        <td style="text-align:center;"><?php echo $data['freshly_filed']; ?></td>
                        <td style="text-align:center;"><?php echo $data['freshly_filed_adj']; ?></td>
                        <td style="text-align:center;"><?php echo $data['part_heard']; ?></td>
                        <td style="text-align:center;"><?php echo $data['inperson']; ?></td>
                        <td style="text-align:center;"><?php echo $data['bail']; ?></td>
                        <td style="text-align:center;"><?php echo $data['after_week']; ?></td>
                        <td style="text-align:center;"><?php echo $data['imp_ia']; ?></td>
                        <td style="text-align:center;"><?php echo $data['ia_other_than_imp_ia']; ?></td>
                        <td style="text-align:center;"><?php echo $data['nradj_not_list']; ?></td>
                        <td style="text-align:center;"><?php echo $data['adm_order']; ?></td>
                        <td style="text-align:center;"><?php echo $data['ordinary']; ?></td>
                        <td style="text-align:center;"><?php echo $data['total']; ?></td>
                    </tr>
                <?php
                $sno++;
            }
                ?>
                <tr><th style="padding: 6px 1px 6px 5px;">Total</th>
                        <th style="text-align:center;"><?php echo $tot_fx_dt; ?></th>
                        <th style="text-align:center;"><?php echo $tot_mentioning; ?></th>
                        <th style="text-align:center;"><?php echo $tot_week_commencing; ?></th>
                        <th style="text-align:center;"><?php echo $tot_freshly_filed; ?></th>
                        <th style="text-align:center;"><?php echo $tot_freshly_filed_adj; ?></th>
                        <th style="text-align:center;"><?php echo $tot_part_heard; ?></th>
                        <th style="text-align:center;"><?php echo $tot_inperson; ?></th>
                        <th style="text-align:center;"><?php echo $tot_bail; ?></th>
                        <th style="text-align:center;"><?php echo $tot_after_week; ?></th>
                        <th style="text-align:center;"><?php echo $tot_imp_ia; ?></th>
                        <th style="text-align:center;"><?php echo $tot_other_ia; ?></th>
                        <th style="text-align:center;"><?php echo $tot_nradj_not_list; ?></th>
                        <th style="text-align:center;"><?php echo $tot_adm_order; ?></th>
                        <th style="text-align:center;"><?php echo $tot_ordinary; ?></th>
                        <th style="text-align:center;"><?php echo $tot_tot; ?></th></tr>
        </tbody>
    </table>
</div>
<?php
} else {
    echo "No Recrods Found";
}
?>

<script>

        var filename = "<?php echo 'Pre_and_after_notice_matters_as_on_'.date("d-m-Y h:i:sA");?>";
        var title = "<?php echo 'Pre Notice and After Notice Matters as on '.date("d-m-Y h:i:s A"); ?>";

        $(document).ready(function() {
            $('#tab').DataTable( {
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'excel', className: 'btn btn-primary glyphicon glyphicon-list-alt',
                        filename: filename,
                        title:title,
                        text: 'Export to Excel',
                        autoFilter: true,
                        sheetName: 'Sheet1'

                    },

                    {
                        extend: 'pdf', className: 'btn btn-primary glyphicon glyphicon-file',
                        filename: filename,
                        title: title,
                        pageSize: 'A4',
                        orientation: 'landscape',
                        text: 'Save as Pdf',
                        customize: function(doc) {
                            doc.styles.title = {

                                fontSize: '18',
                                alignment: 'left'

                            },
                                doc.styles.tableBodyEven.alignment = 'center';
                                doc.styles.tableBodyOdd.alignment = 'center';
                            // doc.content[1].table.widths = [25,88,230,130]; Width of Column in PDF
                        }

                    },

                    {
                        extend: 'print',className: 'btn btn-primary glyphicon glyphicon-print',
                        // filename: filename,
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

                paging: false,
                ordering: false,
                info: false,
                // columnDefs: [{"width": "20px", "targets": [0]},
                //                 {"width": "40px", "targets": [1]},
                //                 {"width": "250px", "targets": [2]}],
                searching: false,


            } );
        } );



    </script>
