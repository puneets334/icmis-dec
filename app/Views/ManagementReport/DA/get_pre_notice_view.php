
<div class="table-responsive">
    <?php
    if (count($get_pre_notice_data) > 0) {
    ?>
    <div align="center"><h3><?php echo $h3_head . "<br>"; ?></h3></div>
        
        <table class="table table-striped custom-table" id="example1">
        <!-- <table id="reportTable1" class="table table-striped table-hover table-bordered"> -->
            <thead>
                <tr>
                    <th width="5%">SrNo.</th>
                    <th width="15%">Reg No. / Diary No</th>
                    <th width="18%">Petitioner / Respondent</th>
                    <th width="18%">Advocate</th>
                    <th width="10%">Subhead</th>
                    <th width="10%">Purpose</th>
                    <th width="15%">Category</th>
                    <th width="5%">Status</th>
                    <th width="9%">Section</td>
                </tr>
            </thead>
            <tbody>
                <?php
                $sno = 1;
                foreach ($get_pre_notice_data as $ro) {
                    $advsql = $model->get_advocate_data($ro["diary_no"]);
                    // pr($advsql);
                    $sno1 = $sno % 2;
                    $dno = $ro['diary_no'];
                    $conn_no = $ro['conn_key'];
                    if ($ro['board_type'] == "J") {
                        $board_type1 = "Court";
                    }
                    if ($ro['board_type'] == "C") {
                        $board_type1 = "Chamber";
                    }
                    if ($ro['board_type'] == "R") {
                        $board_type1 = "Registrar";
                    }
                    $filno_array = explode("-", $ro['active_fil_no']);

                    if ($ro['reg_no_display']) {
                        $fil_no_print = $ro['reg_no_display'];
                    } else {
                        $fil_no_print = "Unregistred";
                    }
                    

                    if ($ro['pno'] == 2) {
                        $pet_name = $ro['pet_name'] . " AND ANR.";
                    } else if ($ro['pno'] > 2) {
                        $pet_name = $ro['pet_name'] . " AND ORS.";
                    } else {
                        $pet_name = $ro['pet_name'];
                    }
                    if ($ro['rno'] == 2) {
                        $res_name = $ro['res_name'] . " AND ANR.";
                    } else if ($ro['rno'] > 2) {
                        $res_name = $ro['res_name'] . " AND ORS.";
                    } else {
                        $res_name = $ro['res_name'];
                    }
                    $padvname = "";
                    $radvname = "";

                    $advsql = $model->get_advocate_data($ro["diary_no"]);

                    if (count($advsql) > 0) {
                        $radvname =  $advsql[0]["r_n"];
                        $padvname =  $advsql[0]["p_n"];
                    }

                    if (($ro['section_name'] == null or $ro['section_name'] == '') and $ro['ref_agency_state_id'] != '' and $ro['ref_agency_state_id'] != 0) {
                        if ($ro['active_reg_year'] != 0)
                            $ten_reg_yr = $ro['active_reg_year'];
                        else
                            $ten_reg_yr = date('Y', strtotime($ro['diary_no_rec_date']));
                        if ($ro['active_casetype_id'] != 0)
                            $casetype_displ = $ro['active_casetype_id'];
                        else if ($ro['casetype_id'] != 0)
                            $casetype_displ = $ro['casetype_id'];
                        $section_ten_q = $model->get_advocate_data($ro['ref_agency_state_id'], $casetype_displ, $ten_reg_yr);

                        if (count($section_ten_q) > 0) {
                            $ro['section_name'] = $section_ten_q["section_name"];
                        }
                    } 
                    $trimmedPadvname = is_null($padvname) ? "" : trim($padvname, ",");
                    $trimmedRadvname = is_null($radvname) ? "" : trim($radvname, ",");
                    $advocate = str_replace(",", ", ", $trimmedPadvname) . "<br/>Vs<br/>" . str_replace(",", ", ", $trimmedRadvname);
                    
                    ?>
                    <tr>
                        <td><?php echo $sno; ?></td>
                        <td><?php echo $fil_no_print . "<br>Diary No. " . substr_replace($ro['diary_no'], '-', -4, 0); ?></td>
                        <td><?php echo $pet_name . "<br/>Vs<br/>" . $res_name; ?></td>
                        <td><?php echo $advocate; ?></td>
                        <td><?php echo $ro['stagename']; ?></td>
                        <td><?php echo $ro['purpose']; ?></td>
                        <td><?php if($ro['submaster_id'] == 0 or $ro['submaster_id'] == '' or $ro['submaster_id'] == null) {} else {f_get_cat_diary_basis($ro['submaster_id']);} ?></td>
                        <td><?php echo $ro['r_n_r']; ?></td>
                        <td><?php echo $ro['section_name'] . "<br/>" . $ro['name']; ?></td>

                        </tr>
            
           
        <?php
                    $sno++;
                }
        ?></tbody>
        </table>
    <?php
    } else {
        echo "No Recrods Found";
    }
    ?>
</div>
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
        var filename = "<?php echo $h3_head;?>";
        var title = "<?php echo $h3_head;?>";

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