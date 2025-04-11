<link rel="stylesheet" href="<?=base_url()?>/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?=base_url()?>/assets/css/AdminLTE.min.css">
    <link rel="stylesheet" href="<?=base_url()?>/assets/css/Reports.css">
    <link rel="stylesheet" href="<?=base_url()?>/assets/css/skins/_all-skins.min.css">
    <link rel="stylesheet" href="<?=base_url()?>/assets/plugins/datepicker/datepicker3.css">
    <link rel="stylesheet" href="<?=base_url()?>/assets/plugins/datatables/jquery.dataTables.min.css">
    <link rel="stylesheet" href="<?=base_url()?>/assets/plugins/datatables/buttons.dataTables.min.css">
    <link rel="stylesheet" href="<?=base_url()?>/assets/plugins/select2/select2.min.css">
          
<div class="table-responsive">
    <?php
    if (count($get_pre_notice_data) > 0) {
    ?>
    <div align="center"><h3><?php echo $h3_head . "<br>"; ?></h3></div>
        
        <!-- <table class="table table-striped custom-table" id="example1"> -->
        <table id="reportTable1" class="table table-striped table-hover table-bordered">
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
                    if ($sno1 == '1') { ?>
                        <tr id="<?php echo $dno; ?>">
                        <?php } else { ?>
                        <tr id="<?php echo $dno; ?>">
                        <?php
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
            </tbody>
           
        <?php
                    $sno++;
                }
        ?>
        </table>
    <?php
    } else {
        echo "No Recrods Found";
    }
    ?>
</div>
<script src="//code.jquery.com/jquery-1.12.4.js"></script>
<script src="<?=base_url()?>/assets/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<script src="<?=base_url()?>/assets/js/bootstrap.min.js"></script>
<script src="<?=base_url()?>/assets/plugins/fastclick/fastclick.js"></script>
<script src="<?=base_url()?>/assets/plugins/select2/select2.full.min.js"></script>
<script src="<?=base_url()?>/assets/js/app.min.js"></script>
<script src="<?=base_url()?>/assets/js/Reports.js"></script>
<script src="<?=base_url()?>/assets/jsAlert/dist/sweetalert.min.js"></script>
<script src="<?=base_url()?>/assets/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?=base_url()?>/assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?=base_url()?>/assets/plugins/datatables/dataTables.buttons.min.js"></script>
<script src="<?=base_url()?>/assets/plugins/datatables/buttons.print.min.js"></script>
<script src="<?=base_url()?>/assets/plugins/datatables/pdfmake.min.js"></script>
<script src="<?=base_url()?>/assets/plugins/datatables/buttons.html5.min.js"></script>

<script>
$(document).ready(function() {

    var reportTitle = "<?php echo $h3_head;?>";
$('#reportTable1').DataTable( {
    "bProcessing"   :   true,
    dom: 'Bfrtip',
    "buttons": [
        {
            extend: 'excelHtml5',
            title: reportTitle
        },
        {
            extend: 'pdfHtml5',
            title: reportTitle
        },
        {
            extend: 'print',
            title: reportTitle
        }
    ]
   
});
});

</script>