<style>
      table.dataTable>thead .sorting,
    table.dataTable>thead {
        background-color: #0d48be !important;
        color: #fff !important;
        white-space: nowrap;
    }

    table.dataTable>thead .sorting_disabled,
    table.dataTable>thead {
        background-color: #0d48be !important;
        color: #fff !important;
        white-space: nowrap;
    }
    table tfoot tr th {
        background-color: #0d48be !important;
        color: #fff !important;
        white-space: nowrap;
    }
    /* .dataTables_filter
    {
        margin-top: -48px;
    } */
</style>
<?php
                                            //if(is_array($reports))
                                            if (count($case_result) > 0) {
                                            ?>
                                            <div align="center"><h3>Verify-Not Verify Loose Documents</h3></div>
                                                <div id="printable1" class="table-responsive">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                                <table id="example1" class="table table-striped ">
                                                                    <thead>
                                                                        <tr>
                                                                            <th style="width: 5%;" rowspan='1'>SNo.</th>
                                                                            <th style="width: 5%;" rowspan='1'>Date</th>
                                                                            <!--   <th style="width: 5%;" rowspan='1'>Section</th>-->
                                                                            <th style="width: 5%;" rowspan='1'>Total</th>

                                                                            <th style="width: 5%;" rowspan='1'>Verfiy</th>
                                                                            <th style="width: 5%;" rowspan='1'>Not Verify</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php
                                                                        $s_no = 1;
                                                                        foreach ($case_result as $result) {
                                                                        ?>
                                                                            
                                                                            <tr>
                                                                                <td><?php echo $s_no; ?></td>
                                                                                <td><?php echo date('d-m-Y',strtotime($result['date1'])); ?></td>
                                                                                <td><?php echo $result['total']; ?></td>
                                                                                <?php if(!empty($result['verify'])){ ?>
                                                                                <td><button class="btn btn-secondary" data-toggle="modal" data-target="#modal-default" onclick="get_detail('<?php echo $result['date1']; ?>','V','<?php echo $result['sec_id']; ?>','<?php echo $_POST['usercode']; ?>');"> <?php echo $result['verify']; ?></button></td>
                                                                                <?php 
                                                                                } else {
                                                                                    echo '<td>0</td>';
                                                                                }
                                                                                ?>
                                                                                <?php if(!empty($result['not_verify'])){ ?>
                                                                                    <td><button class="btn btn-secondary" data-toggle="modal" data-target="#modal-default" onclick="get_detail('<?php echo $result['date1']; ?>','N','<?php echo $result['sec_id']; ?>','<?php echo $_POST['usercode']; ?>');"> <?php echo $result['not_verify']; ?></button></td>
                                                                                <?php 
                                                                                } else {
                                                                                    echo '<td>0</td>';
                                                                                }
                                                                                ?>
                                                                                
                                                                                

                                                                            </tr>
                                                                        <?php
                                                                            $s_no++;
                                                                        }
                                                                        ?>
                                                                    </tbody>
                                                                </table>
                                                        </div>       
                                                    </div>
                                                    
                                                </div>
                                            <?PHP
                                            }
                                            else{  if($value == 1){?>
                                                <div id="printable1" class="table-responsive">
                                                
                                                <table id="example1" class="table table-striped custom-table">
                                                        <thead>
                                                            <tr>
                                                                <th style="width: 5%;" rowspan='1'>SNo.</th>
                                                                <th style="width: 5%;" rowspan='1'>Date</th>
                                                                <!--   <th style="width: 5%;" rowspan='1'>Section</th>-->
                                                                <th style="width: 5%;" rowspan='1'>Total</th>

                                                                <th style="width: 5%;" rowspan='1'>Verfiy</th>
                                                                <th style="width: 5%;" rowspan='1'>Not Verify</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <td colspan="5">NO RECORD FOUND</td>
                                                        </tbody>
                                                    </table>
                                            </div> 
                                            <?php
                                            }
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
        var filename = "Verify-Not Verify Loose Documents";
        var title = "Verify-Not Verify Loose Documents";

        $(document).ready(function() {
            $('#example1').DataTable( {
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