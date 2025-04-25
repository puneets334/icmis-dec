<style>
    th {
    display: table-cell;
    vertical-align: inherit;
    font-weight: bold;
    text-align: center !important;
    unicode-bidi: isolate;
}
</style>

<?php if (count($cases) > 0) {
    $md_name = $params['md_name'];
    if ($md_name == "pool") {
        $sno = 1;
?>

        <div id="prnnt" style="font-size:12px;">
            <center>
                <h3>CASES AVAILABLE IN POOL</h3>
            </center>
            <div class="card-body">
            <table border="1" width="100%" id="example" class="display table table-bordered table-striped" cellspacing="0">
                <thead>
                    <tr>
                        <th>sno.</th>
                        <th>Diary / Reg. / Tag</th>
                        <th>Proposed Date / Head</th>
                        <th>Sub Head</th>
                        <th>Sub. Category</th>
                        <th>Purpose of Listing</th>
                        <th>Before/ Not Before Judge</th>
                        <th>DA/Last Updated</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                }else { ?>
                    <!--<table border="0" width="100%" id="example" class="border" cellspacing="0">
                    <tbody>-->
                <?php }
                $diaryNos = array();
                $sno = 1;
                $show_chkall_tr = 0;
                foreach ($cases as $row) {
                    if ($md_name == "pool") {
                        $diaryNos[] = $row['diary_no'];
                    ?>
                        <tr>
                            <td rowspan="2"><?= $sno++ ?></td>
                            <td rowspan="2">
                                <?= substr_replace($row['diary_no'], '-', -4, 0) ?><br />
                                <?php
                                //$coram = ($this->request->getPost('bench') == 'R') ? $row['r_coram'] : $row['coram'];
                                $coram = ($params['bench'] == 'R') ? $row['r_coram'] : $row['coram'];
                                //$coram = '';
                                $m_f_filno = $row['active_fil_no'];
                                $m_f_fil_yr = $row['active_reg_year'];
                                $filno_array = explode("-", $m_f_filno);
                                //$fil_no_print = ($filno_array[1] == $filno_array[2]) ? ltrim($filno_array[1], '0') : ltrim($filno_array[1], '0') . "-" . ltrim($filno_array[2], '0');

                                $fil_no_print = '';
                                $comlete_fil_no_prt = ($row['reg_no_display'] == "") ? "Diary No. " . substr_replace($row['diary_no'], '-', -4, 0) : $row['reg_no_display'];
                                echo $comlete_fil_no_prt;
                                if ($row['child_case'] != "") {
                                    echo "<br/><span style='font-weight:bold;'>";
                                    $chil_ar = explode(",", $row['child_case']);
                                    foreach ($chil_ar as $child) {
                                        echo nl2br(substr_replace($child, '-', -4, 0) . "\n");
                                    }
                                    echo "</span>";
                                }
                                $diaryDt = isset($row['diary_no_rec_date']) ? date("d-m-y", strtotime($row['diary_no_rec_date'])) : '';
                                $regDt = isset($row['fil_dt']) ? date("d-m-y", strtotime($row['fil_dt'])) : '';
                                echo "<br/>Diarydt " . $diaryDt;
                                echo "<br/>Reg dt " . $regDt;
                                ?>
                            </td>
                            <!--<td><? //= date("d-m-Y", strtotime($row['next_dt'])) . " " . $row['mainhead'] 
                                    ?></td>-->
                            <td><?= isset($row['next_dt']) ? date("d-m-Y", strtotime($row['next_dt'])) . " " . $row['mainhead'] : '' ?></td>
                            <td <?= ($params['mainhead'] == 'M' && $params['bench'] == "R" && $row['subhead'] != '849' && $row['subhead'] != '850') ? 'style="background-color:#ff1e2c;"' : '' ?>>
                                <?php if ($params['mainhead'] != 'F') {
                                    echo f_get_subhead_basis($row['subhead']);
                                    //echo isset($row['subhead']) ? $row['subhead'] : '';
                                } ?>
                            </td>
                            <td
                                <?php if (isset($row['cat1'])) { ?>
                                <?= empty($row['cat1']) || $row['cat1'] == 331 ? 'style="background-color: #ff1e2c;"' : '' ?>>
                                <?php if ($row['cat1']) {
                                        echo f_get_cat_diary_basis($row['cat1']);
                                    } ?>
                            <?php } ?>

                            </td>
                            <td><?= isset($row['purpose']) ? $row['purpose'] : '' ?></td>
                            <td>
                                <?php
                                if ($coram != 0) {
                                    echo "CORAM : " . f_get_judge_names_inshort($coram);
                                }
                                
                                echo f_get_ntl_judge($row['diary_no']);
                                echo f_get_ndept_judge($row['diary_no']);
                                echo f_get_category_judge($row['diary_no']);
                                echo f_get_not_before($row['diary_no']);
                                $rgo_default = f_cl_rgo_default($row['diary_no']);
                                if ($rgo_default != 0) {
                                    echo "<br/>Not to list till dispose of $rgo_default";
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                f_get_user_name_fdno($row['diary_no']);
                                echo date("d-m-Y H:i:s", strtotime($row['ent_dt']));
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="6">
                                <?php
                                // Handle ROP Orders
                                $orders = $caseAddModel->getRopDetails($row['diary_no']);
                                $orders = [];
                                if (count($orders) > 0) {
                                    echo "<span class='tooltip'>ROP<span class='tooltiptext'>";
                                    //foreach ($orders->getResultArray() as $order) {
                                    foreach ($orders as $order) {
                                        $rjm = explode("/", $order['pdfname']);
                                        $link = ($rjm[0] == 'supremecourt') ? '../../jud_ord_html_pdf/' . $order['pdfname'] : '../../judgment/' . $order['pdfname'];
                                        echo '<a href="' . $link . '" target="_blank">' . date("d-m-Y", strtotime($order['orderdate'])) . '</a><br>';
                                    }
                                    echo "</span></span>";
                                }
                                echo $row['lastorder'];
                                echo f_get_brdrem($row['diary_no']);
                                echo f_get_kword($row['diary_no']);
                                echo f_get_docdetail($row['diary_no']);
                                if (!empty($row['ia_filing_dt'])) {
                                    echo "<br/>EARLY HEARING APPLICATION Filed On: " . date("d-m-Y H:i:s A", strtotime($row['ia_filing_dt']));
                                }
                                echo f_get_act_main($row['diary_no']);
                                //echo $row['diary_no'];
                                ?>
                            </td>
                        </tr>
                        <?php } elseif ($md_name == "transfer") {
                        if ($params['bench'] == 'R') {
                            $coram = $row['r_coram'];
                        } else {
                            $coram = $row['coram'];
                        }
                        $show_chkall_tr++;
                        if ($show_chkall_tr == 1) {
                        ?>
                        <table border="0" width="100%" id="example" class="border" cellspacing="0">
                    <tbody>
                            <tr>
                                <td colspan="8">
                                    <legend style="text-align:center;color:#4141E0; font-weight:bold;">Available Cases</legend>
                                    
                                </td>
                            </tr>
                            <tr>
                                <td colspan="8">
                                    <input type="checkbox" name="chkall_tr" id="chkall_tr" value="ALL" onClick="chkall_trans(this);">All<br />
                                </td>
                            </tr>      
                            <?php } ?>
                            
                                <tr>
                                    <td>
                                        <input type="checkbox" id="chk_tr" name="chk_tr" value="<?PHP echo $row['diary_no']; ?>">
                                    </td>
                                    <td>
                                        <?php echo $row['brd_slno'] . "."; ?>
                                    </td>
                                    <td>
                                        <?php
                                        echo "Diary " . substr_replace($row['diary_no'], '-', -4, 0) . " Reg. ";
                                        echo $row['short_description'] . "-";
                                        //$filno_array = explode("-", $row['fil_no']);
                                        //if(!empty($filno_array[1]) && !empty($filno_array[2])){
                                            if(!empty($row['fil_no'])){
                                            $filno_array = isset($row['fil_no']) && is_string($row['fil_no']) ? explode("-", $row['fil_no']) : [];
                                            $fill_index2 = isset($filno_array[2]) ? $filno_array[2] : 0;
                                            if ($filno_array[1] == $fill_index2) {
                                                echo ltrim($filno_array[1], '0');
                                            } else {
                                                echo ltrim($filno_array[1], '0') . "-" . ltrim($fill_index2, '0');
                                            }
                                            echo "-" . $row['fil_year'] . " ";
                                        }
                                            
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        if ($coram != 0) {
                                            echo "&nbsp;&nbsp; <font color=green>CORAM : " . f_get_judge_names_inshort($coram) . "</font>";
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        echo "&nbsp;&nbsp;" . f_get_ntl_judge($row['diary_no']);
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        echo "&nbsp;&nbsp;" . f_get_ndept_judge($row['diary_no']);
                                        ?>
                                    </td>
                                    <td>
                                        <?php echo "&nbsp;&nbsp;" . f_get_not_before($row['diary_no']); ?>
                                    </td>
                                    <td><?php echo $row['lastorder']; ?></td>
                                </tr>
                            
                        <?php } else { ?>
                            <!--<div class="form-group border p-3">
                            <fieldset>
                                <legend style="text-align:center;color:#4141E0; font-weight:bold;">Allotment of Cases</legend>
                                Total Cases Available : <font color="red"><span id="tot_case_avl"><?php echo $row['avl_rc']; ?></span></font>
                                <br />
                                Number of Cases per Bench : <input type="text" name="noc" id="noc" value="<?php echo $row['avl_rc']; ?>" size="5">
                                <br />
                                Part No.:<input type="text" name="partno" id="partno" value="1" size="5">
                                <br />
                                <br />
                                <input type="button" name="doa" id="doa" value=" Do Allottment " class="btn btn-primary">
                            </fieldset>
                            </div>-->
                            <div class="form-group border p-3">
                                <fieldset>
                                    <legend class="text-center text-primary font-weight-bold">Allotment of Cases</legend>
                                    <!--<p>Total Cases Available: <span class="text-danger" id="tot_case_avl"><?php echo $row['avl_rc']; ?></span></p>-->
                                    <div class="row mb-3">
                                        <div class="form-inline">
                                        <label for="tot_case_avl">Total Cases Available: </label>
                                            <span class="text-danger ml-1" id="tot_case_avl"><?php echo $row['avl_rc']; ?></span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-inline">
                                            <label for="noc">Number of Cases per Bench:</label>
                                            <input type="text" class="form-control" name="noc" id="noc" value="<?php echo $row['avl_rc']; ?>" size="5">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-inline">
                                            <label for="partno">Part No.:</label>
                                            <input type="text" class="form-control" name="partno" id="partno" value="1" size="5">
                                        </div>
                                    </div>

                                    <div class="mt-3">
                                        <input type="button" name="doa" id="doa" value="Do Allotment" class="btn btn-primary">
                                    </div>
                                </fieldset>
                            </div>

                        <?php } ?>
                    <?php } ?>
                </tbody>
            </table>
            </div>                             

            <?php if ($md_name == "transfer") { ?>
                </fieldset>
                <table border="0" width="100%" style="border-spacing: 10px;">
                    <tr>
                        <td width="70%" class="border">
                            <fieldset>
                                <legend style="text-align:center;color:#4141E0; font-weight:bold;">Transfer To</legend>
                                Listing Dt. <input type="text" size="10" class="dtp" name='tans_to_date' id='tans_to_date' value="" readonly />
                                <table>
                                    <tr>
                                        <td style="width: 500px;" align="left" class="jud_all_tran">
                                            <?php
                                            $cur_ddt = date('Y-m-d', strtotime(' +1 day'));
                                            $next_court_work_day = date("d-m-Y", strtotime(chksDate($cur_ddt)));
                                            get_judge_rost_for_trans($params['mainhead'], $next_court_work_day, null, null);
                                            ?>
                                        </td>
                                    </tr>
                                </table>
                                Part No.:<input type="text" name="partno" id="partno" value="1" size="5">
                                <br />
                                <input type="radio" name="main_supp" id="main_supp" value="1" title="Main Cause List" checked="checked">Main Cause List&nbsp;
                                <input type="radio" name="main_supp" id="main_supp" value="2" title="Supplementary Cause List">Supplementary Cause List&nbsp;
                                <br />
                                <input type="button" name="do_trans" id="do_trans" value=" Do Transfer" class="btn btn-primary">
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <input type="button" name="do_trans_w" id="do_trans_w" value=" Transfer (Without Checking Coram)" class="btn btn-primary">
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <input type="button" name="do_trans_asitis" id="do_trans_asitis" value=" Transfer As It Is " class="btn btn-primary">
                            </fieldset>
                        </td>
                        <td width="30%" class="border">
                            <fieldset>
                                <legend style="text-align:center;color:#4141E0; font-weight:bold;">Send to Pool</legend>
                                <?php
                                $cur_ddt = date('Y-m-d', strtotime(' +1 day'));
                                $next_court_work_day = date("d-m-Y", strtotime(chksDate($cur_ddt)));
                                ?>
                                <input type="text" size="10" class="dtp" name='pool_date' id='pool_date' value="<?php echo $next_court_work_day; ?>" readonly />
                                <input type="button" name="btn_send_pool" id="btn_send_pool" value=" Send to Pool " class="btn btn-primary">
                            </fieldset>
                        </td>
                    </tr>
                </table>
            <?php } ?>
            </div>    
            <?php if ($md_name == "pool") { ?>
                <div class="row">
                    <div class="col d-flex justify-content-between">
                        <input name="prnnt1" type="button" id="prnnt1" value="Print" class="btn btn-primary">
                        <form id="" method="POST" action="<?php echo base_url('Listing/Pool/cl'); ?>" target= '_blank'>
                            <?= csrf_field() ?>
                            <button type="button" style="" id="footerButton" class="btn btn-primary" data-diary-nos='<?= json_encode($diaryNos) ?>'>Generate in Causelist format</button>
                        </form>
                    </div>
                </div>
            <?php } ?>    
        <!--</div>-->
    <?php } else {
    echo "No Records Found.";
} ?>

<script>
    var leavesOnDates = <?= next_holidays_new(); ?>;

$(function() {
    //var date = new Date();
    //date.setDate(date.getDate());
    $('.dtp').datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true,
        todayHighlight: true,
        changeMonth : true, 
        changeYear : true,
        yearRange : '1950:2050',
        datesDisabled: leavesOnDates,
        isInvalidDate: function(date) {
            return (date.day() == 0 || date.day() == 6);
        },
    });
});

$(document).on('click', '#footerButton', async function() {    
    await updateCSRFTokenSync();
    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
    var diaryNos = $(this).data('diary-nos');
    $('<input>').attr({
        type: 'hidden',
        name: 'diaryNos',
        value: JSON.stringify(diaryNos)
    }).appendTo('form');
    $('form').submit();
});    

</script>