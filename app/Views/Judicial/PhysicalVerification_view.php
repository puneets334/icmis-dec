<?= view('header') ?>


<style>
    table,
    th,
    td {
        border: 1px solid black;
    }.modalXl{
        max-width: 888px;
    }
</style>
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                
                <div class="card">
                    <div class="card-header heading">

                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Judicial > Data Updation - Physical Verification</h3>
                            </div>

                        </div>
                    </div>
                    <? //view('Filing/filing_breadcrumb'); 
                    ?>
                    <!-- /.card-header -->

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header p-2" style="background-color: #fff;">
                                <div id="loader"></div>
                                </div><!-- /.card-header -->
                                <div class="card-body">
                                    <div class="tab-content">

                                        <?php
                                        $attribute = array('class' => 'form-horizontal', 'name' => 'physicalVerf', 'id' => 'physicalVerf', 'autocomplete' => 'off');
                                        echo form_open(base_url(''), $attribute);
                                        ?>
                                        <div class="active tab-pane" id="">

                                            <div>

                                                <?php
                                                if (!empty($physical_details)) { ?>
                                                    <div class="mb-5" style="text-align: center;font-size: 30px">
                                                        <h3>Physical Verification Report of the cases of <?php echo $physical_details['result'][0]['da_name'] . ' [' . $physical_details['result'][0]['da_empid'] . '] of Section : ' . $physical_details['result'][0]['da_section_name']; ?>, Total Cases :<?= $physical_details['total_cases'] ?></h3>
                                                    </div>

                                                    <!-- <div class="mb-5" id="data_update1" style="float:right">
                                                            <div  id="ajax_result" class="alert alert-danger" role="alert">

                                                            </div>
                                                        </div> -->

                                                    <table id="example" class="table table-hover">
                                                        <thead>
                                                            <tr>
                                                                <td width="5%">SNo</td>
                                                                <td width="25%">Reg No. & Date</td>
                                                                <td width="20%">Diary No. & Date</td>
                                                                <td width="15%">Petitioner / Respondent</td>
                                                                <td width="15%">High Court Details</td>
                                                                <td width="20%">#</td>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            foreach ($physical_details['result'] as $sno => $row) {
                                                                // echo "<pre>";
                                                                // print_r($row); die;
                                                                $diary_no = $row['dno'];
                                                                $ddt = date('d-m-Y', strtotime($row['diary_no_rec_date']));
                                                                $regdt = $row['active_fil_dt'] != '' ? date('d-m-Y', strtotime($row['active_fil_dt'])) : '';
                                                                $comlete_fil_no_prt = $row['reg_no_display'] . " @ Diary No. " . substr_replace($diary_no, '/-', -4, 0);
                                                            ?>

                                                                <td align="center" style='vertical-align: top;'>
                                                                    <strong><?php echo $sno + 1; ?></strong>
                                                                </td>
                                                                <td align="left" style='vertical-align: top;'><?php echo $comlete_fil_no_prt;
                                                                                                                ?>
                                                                </td>
                                                                <td align="left" style='vertical-align: top;'><?php echo substr_replace($diary_no, '/-', -4, 0) . " Dt. " . $ddt;

                                                                                                                ?></td>
                                                                <td align="left" style='vertical-align: top;'><?php echo $row['cause_title']; ?></td>


                                                                <td align="left" style='vertical-align: top;'><?php echo $row['agency_state'] . "<br>" . $row['agency_name']; ?></td>
                                                                <td>
                                                                    <?php
                                                                    if (empty($row['is_verify']) || $row['is_verify'] != 'Y') { ?>

                                                                        <span id="update<?php echo $row['dno']; ?>">
                                                                            <button onclick="updateCaseDetails('<?= $row['dno']; ?>')" type="button"
                                                                                data-button_id="update<?php echo $row['dno']; ?>"
                                                                                class="btn btn-info btn-md mb-2">Update
                                                                            </button>
                                                                        </span>
                                                                        <?php if ($row['is_verify'] == 'N') { ?>
                                                                            <div class="alert alert-danger p-2 text-center" role="alert">
                                                                                <strong>Earlier Updated as not with you </strong>
                                                                            </div>
                                                                        <?php } else { ?>
                                                                            <span id="notwithme<?php echo $row['dno']; ?>">
                                                                                <button type="button" class="btn  btn-secondary update-physical_verfiy btn-sm"
                                                                                    data-button_id="notwithme<?php echo $row['dno']; ?>"
                                                                                    data-diary_no="<?php echo $row['dno']; ?>">Not With Me </button>
                                                                            </span>

                                                                        <?php }
                                                                    } else { ?>

                                                                        <div class="alert alert-success" role="alert">
                                                                            <strong>Already Verified</strong>
                                                                        </div>
                                                                    <?php } ?>

                                                                </td>
                                                                </tr>
                                                            <?php
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                <?php
                                                } else {
                                                    echo "No Record Found";
                                                }
                                                ?>
                                            </div>

                                        </div>

                                        <!-- Modal -->
                                         
                                        <!-- <div id="physicalVerificationModal" class="modal fade" role="dialog">
                                            <div class="modal-dialog" style="margin: 2rem 2rem;">
                                                <div class="modal-content" style="width: 96rem;">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        <h4 class="modal-title"></h4>
                                                    </div>
                                                    <div class="modal-body" id="modData">

                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>

                                            </div>
                                        </div> -->

                                        <?php form_close(); ?>
                                    </div>
                                    <!-- /.tab-content -->
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                    </div>


                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
</section>
<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" id="physicalVerificationModal" aria-labelledby="physicalVerificationModalLabel" aria-hidden="true">
  <div class="modal-dialog modalXl modal-xl">
    <div class="modal-content p-5" id="modData">
    </div>
  </div>
</div>

<!-- /.content -->

<script>
   $(document).ready(function () {
        $('.select-box').select2({
            selectOnClose: true
        });

        $(document).on('select2:open', '.select-box', function () {
            $(this).select2({
                selectOnClose: true
            });
        });
    });
    var filename = 'physical_verification_report';
    var title = 'physical_verification_report';

    function updateCSRFToken() {
        $.getJSON("<?php echo base_url('Csrftoken'); ?>", function(result) {
            $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
        });
    }

    function string_replace(haystack) {
        let dNo = String(haystack)
        var lastFour = dNo.substr(dNo.length - 4);
        let d1 = dNo.replace(lastFour, "-");
        let diaryId = d1.concat(lastFour)
        return diaryId
    }


    function updateCaseDetails(diary_no) {

        updateCSRFToken()
        //   alert(diary_no)          

        if (diary_no) {
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

            $.ajax({
                url: "<?php echo base_url('Judicial/PhysicalVerification/wrong_updated_get'); ?>",
                cache: false,
                async: true,
                data: {
                    CSRF_TOKEN: CSRF_TOKEN_VALUE,
                    dno: diary_no
                },
                type: 'POST',
                // beforeSend: function () {
                //     $('#ajax_result').html('<table widht="100%" align="center"><tr><td><img src="../images/load.gif"/></td></tr></table>');
                // },
                beforeSend: function() {
                    $("#loader").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                },
                success: function(data) {
                    $('#modData').html(' ');
                    if (data != '') {
                        console.log(JSON.parse(data))
                        let resData = JSON.parse(data);
                        let row_avl = resData.chk_avl;
                        let active_casetype_id = row_avl['active_casetype_id'];

                        let c_status = ''
                        if (row_avl['c_status'] == "P") {
                            c_status = "Pending";
                        } else {
                            c_status = "Disposed";
                        }

                        let reg_case = ''
                        if (row_avl['reg_no_display']) {
                            reg_case = row_avl['reg_no_display'];
                        } else {
                            reg_case = "Registrered";
                        }

                        let pet_name = ''
                        if (Number(row_avl['pno']) == 2) {
                            pet_name = row_avl['pet_name'] + " AND ANR.";
                        } else if (Number(row_avl['pno']) > 2) {
                            pet_name = row_avl['pet_name'] + " AND ORS.";
                        } else {
                            pet_name = row_avl['pet_name'];
                        }

                        let res_name = ''
                        if (Number(row_avl['rno']) == 2) {
                            res_name = row_avl['res_name'] + " AND ANR.";
                        } else if (Number(row_avl['rno']) > 2) {
                            res_name = row_avl['res_name'] + " AND ORS.";
                        } else {
                            res_name = row_avl['res_name'];
                        }
                        let pt_rs = pet_name + " <b>Vs</b>. " + res_name;

                        let reg_hear = ''
                        if (Object.keys(resData.chk_avl5).length > 0) {
                            let row_avl5 = resData.chk_avl5;
                            if (row_avl5['mainhead'] == 'F') {
                                reg_hear = "<br><span style='color: darkred; font-weight: bold;'>Regular Hearing</span>"
                            }
                        }

                        let misc_hear = ''
                        if (Object.keys(resData.chk_avl55).length > 0) {
                            let row_avl5 = resData.chk_avl55;
                            if (row_avl5['mainhead'] == 'M') {
                                misc_hear = "<br><span style='color: darkred; font-weight: bold;'>Misc. Hearing</span> -" + row_avl5['stagename']
                            }
                        }

                        let ddt_date = row_avl['diary_no_rec_date'].split(" ")
                        ddt_date = ddt_date[0]
                        ddt_date = ddt_date.split('-').reverse().join('-');

                        let row_res6 = resData.res6;
                        let cat_det = ''
                        let isCategoryPresent = ''
                        if (row_res6.length == 1) {
                            rowes = row_res6
                            rowes.forEach(function(row) {
                                retn = row["sub_name1"];
                                if (row["sub_name2"])
                                    retn += " - " + row["sub_name2"];
                                if (row["sub_name3"])
                                    retn += " - " + row["sub_name3"];
                                if (row["sub_name4"])
                                    retn += " - " + row["sub_name4"];
                                cat_det = "<br><span style='color:blue; font-weight: bold;'>Category - [" + row["category_sc_old"] + "] " + retn + " </span>";
                            })
                            isCategoryPresent = 'Y';
                        } else {
                            isCategoryPresent = 'N';

                            let htmlInnr = ''
                            htmlInnr += '<div class="row">'
                            htmlInnr += '<div class="col-md-6" id="mainsubjectCategory">'
                            htmlInnr += '<label for="category" id="lbl_McategoryCode" class="">Main Subject Category:<span class="required"></span></label>'
                            htmlInnr += '<select class="form-control select-box" id="mainCategory" name="mainCategory" required>'
                            htmlInnr += '<option value="">---Select Main Category---</option>'
                            let row_res5 = resData.res5
                            if (row_res5.length > 0) {
                                row_res5.forEach(function(result) {
                                    htmlInnr += '<option value="' + result['id'] + '/' + result['subcode1'] + '">' + result['subcode1'] + '- ' + result['sub_name1'] + ' -Id-' + result['id']  + '</option>'
                                })
                            }
                            htmlInnr += '</select>'
                            htmlInnr += '</div>'
                            htmlInnr += '<div class="col-md-6" id="subjectCategory" >'
                            htmlInnr += '<label for="category" id="lbl_categoryCode" class="">Sub Subject Category:</label>'
                            htmlInnr += '<select class="form-control select-box"  id="categoryCode" name="categoryCode"  placeholder="Subject Category" required>'
                            htmlInnr += '</select>'
                            htmlInnr += '</div>'
                            htmlInnr += '</div>'

                            cat_det = htmlInnr
                        }


                        let resitration_arr = resData.res_reg_form
                        let reg_html = ''
                        if (resitration_arr.length > 0) {
                            reg_html += '<table class="table_tr_th_w_clr c_vertical_align" width="100%">'
                            reg_html += '<tr>'
                            reg_html += '<td align="center"><b>Registration No.</b></td>'
                            reg_html += '<td align="center"><b>Order Date</b></td>'
                            reg_html += '</tr>'
                            resitration_arr.forEach(function(row) {
                                if (row['split_caseno1'] == row['split_caseno2']) {
                                    casenoo = row['split_caseno1'];
                                } else {
                                    casenoo = row['split_caseno1'] + ' - ' + row['split_caseno2'];
                                }
                                regno = row['short_description'] + ' - ' + casenoo + ' / ' + row['new_registration_year'];
                                // order_dt = date('d/m/Y', strtotime(row[order_date]));
                                let order_dt = row['order_date'].split(" ")
                                order_dt = order_dt[0]
                                order_dt = order_dt.split('-').reverse().join('-');
                                verify_str = row['id'] + '_' + row['diary_no'];

                                reg_html += '<tr id="' + verify_str + '">'
                                reg_html += '<td align="center"> ' + regno + '</td>'
                                reg_html += '<td align="center">'
                                reg_html += '<span class="required"></span>'
                                reg_html += '<input type="text" class="order_date" onblur="checkDate(this.value)" placeholder="dd/mm/yyyy" size="10" class="dtp" name="orderdt_' + verify_str + '" id="orderdt_' + verify_str + '" value="' + order_dt + '">'
                                '</td>'
                                '</tr>'
                            })
                            reg_html += '</table>';

                        } else {
                            reg_html += '<span style="color:red;">Unregistred</span>'
                        }


                        let partycntr = 1;
                        let result_party_arr = resData.result_party
                        let res_party_html = ''
                        res_party_html += '<table class="table_tr_th_w_clr c_vertical_align" width="100%">'
                        if (result_party_arr.length > 0) {
                            res_party_html += '<tr>'
                            res_party_html += '<td align="center"><b>Sno</b></td>'
                            res_party_html += '<td align="center" width="30%"><b>Party Name</b></td>'
                            res_party_html += '<td align="center" width="40%"><b>Address</b></td>'
                            res_party_html += '<td align="center" width="20%"><b>Contact</b></td>'
                            res_party_html += '<td align="center" width="10%"><b>Gender<br/>Age</b></td>'
                            res_party_html += '</tr>'
                            result_party_arr.forEach(function(row_party) {
                                res_party_html += '<tr>'
                                res_party_html += '<td align="center"> ' + partycntr + '  </td>'
                                res_party_html += '<td> ' + row_party['partyname'] + ' (' + row_party['pet_res'] + ')' + '</td>'
                                res_party_html += '<td><b>Address:</b>' + row_party['addr1'] + " " + row_party['addr2']
                                res_party_html += '<br/><br/><b>State:</b>' + row_party['state_name']
                                res_party_html += '<br/><br/><b>District:</b>' + row_party['district_name']
                                res_party_html += '<br/><br/><b>Pin Code:</b>' + row_party['pin']
                                res_party_html += '</td>'
                                res_party_html += '<td>'
                                res_party_html += '<b>Mobile:</b>' + row_party['mobile']
                                res_party_html += '<br/>'
                                res_party_html += '<hr/>'
                                res_party_html += '<b>E-mail:</b>' + row_party['email']
                                res_party_html += '</td>'
                                res_party_html += '<td><b>Gender :</b>'
                                if (row_party['sex'] == '') res_party_html += '';
                                else if (row_party['sex'] == 'M') res_party_html += 'Male';
                                else if (row_party['sex'] == 'F') res_party_html += 'Female';
                                else if (row_party['sex'] == 'N') res_party_html += 'N.A.';
                                res_party_html += '<br/><br/>'
                                res_party_html += 'Age: ' + row_party['age']
                                res_party_html += '</td>'
                                res_party_html += '</tr>'

                                partycntr = partycntr + 1;
                            })
                            res_party_html += '</table>'
                        }


                        let html = ''
                        html += '<div style="text-align:left;">'
                        html += '<div class="modal-title" style="text-align: center;"><h4> Case No. : ' + reg_case + ' <br>Diary No. : ' + string_replace(row_avl['diary_no']) + ' <span style="color:blue; font-weight: bold;">' + c_status + '</span></h4>'
                        html += pt_rs
                        html += reg_hear
                        html += misc_hear
                        html += '</div>'
                        html += '<div style="border-radius: 10px; padding:40px; vertical-align: center; width: 98%">'
                        html += '<form name="updation_form">'
                        html += '<input name="valid_dno" type="hidden" id="valid_dno" value="' + row_avl['diary_no'] + '">'
                        html += '<div class="main_div">'
                        html += '<div class="card">'
                        html += '<div class="card-body">'
                        html += '<span>'
                        html += '<span>'
                        html += '<label class="sub_div1 ">Diary Date : <span class="required"></span>'
                        html += '</label>'
                        html += '</span>'
                        html += '<span class="sub_div2">'
                        html += '<input type="text" onblur="' + checkDate(ddt_date) + '" size="10" class="dtp" name="ddt" id="ddt" value="' + ddt_date + '" placeholder="dd/mm/yyyy" required="">'
                        html += '<span id="ddt_result"></span>'
                        html += '</span>'
                        html += '</span>'


                        if (active_casetype_id != 0 && active_casetype_id != 9 && active_casetype_id != 10 && active_casetype_id != 19 && active_casetype_id != 20 && active_casetype_id != 25 && active_casetype_id != 26 && active_casetype_id != 39) {
                            html += '<span style="margin-left: 50px">'
                            html += '<span>'
                            html += '<label class="sub_div1">Registration/Leave Grant Date :<span class="required"></span></label>'
                            html += '</span>'
                            html += '<span class="sub_div2">'
                            let filt_date = ''
                            if (row_avl['fil_dt_fh'] == '0000-00-00 00:00:00' || row_avl['fil_dt_fh'] == null) {
                                filt_date = '00/00/0000';
                            } else {
                                let ddt_date1 = row_avl['fil_dt_fh'].split(" ")
                                ddt_date1 = ddt_date1[0]
                                ddt_date1 = ddt_date1.split('-').reverse().join('-');
                                filt_date = ddt_date1
                            }
                            html += '<input type="text" placeholder="dd/mm/yyyy" size="10" class="dtp" name="fhdt" id="fhdt" value="' + filt_date + '" required/>'
                            html += '<span id="fhdt_result"></span>'
                        }
                        html += '</span>'
                        html += '</span>'

                        html += '<span style="margin-left: 50px">'
                        html += '<span>'
                        html += '<label class="sub_div1">Nature : <span class="required"></span>'
                        html += '</label>'
                        html += '</span>'
                        html += '<span class="sub_div2">'
                        html += '<select name="case_group" class="select-box" id="case_group" required="">'
                        if (row_avl['case_grp'].trim() == 'C') {
                            html += '<option value="C" selected >Civil</option>'
                            html += '<option value="R" >Criminal</option>'
                        } else if (row_avl['case_grp'].trim() == 'R') {
                            html += '<option value="C" >Civil</option>'
                            html += '<option value="R" selected >Criminal</option>'
                        } else {
                            html += '<option value="C" >Civil</option>'
                            html += '<option value="R" >Criminal</option>'
                        }
                        html += '</select>'
                        html += '</span>'
                        html += '</span>'
                        html += '</div>'
                        html += '</div>'
                        html += '</div>'
                        html += '<div class="main_div"></div>'
                        html += '<div class="main_div" style="margin-top:10px;font-weight:bold">'
                        html += '<div class="card">'
                         html += '<div class="card-header"><h5 class="mb-0">Subject Category :</h5></div>'
                        html += '<div class="card-body">'
                        html += cat_det
                        html += '</div>'
                        html += '</div>'
                        html += '</div>'
                        html += '<div class="main_div">'
                        html += '<div class="card">'
                         html += '<div class="card-header"><h5 class="mb-0">Registration :</h5></div>'
                        html += '<div class="card-body">'
                        html += reg_html
                        html += '</div>'
                        html += '</div>'
                        html += '</div>'
                        html += '<div class="main_div ">'
                        html += '<div class="sub_div1">Party : <span class="form-check form-check-inline bg-info">'
                        html += '<input class="form-check-input check_Box" type="checkbox" name="partyData" id="partyData">'
                        html += '<label class="form-check-label" for="partyData"> Yes, I have verified Case Party(s) Data</label>'
                        html += '</span>'
                        html += '</div>'
                        html += '<div class="card">'
                        html += '<div class="card-body" style="max-height:400px;overflow:auto">'
                        html += res_party_html
                        html += '</div>'
                        html += '</div>'
                        html += '</div>'
                        html += '<div class="main_div">'
                        html += '<div class="sub_div1">Lower Court Details : '
                        html += '<span class="form-check form-check-inline bg-info">'
                        html += '<input class="form-check-input check_Box" type="checkbox" name="listingData" id="listingData">'
                        html += '<label class="form-check-label" for="listingData"> Yes, I have verified Case Lower Court data</label>'
                        html += '</span>'
                        html += '</div>'
                        html += '<div class="card">'
                        html += '<div class="card-body" style="max-height:400px;overflow:auto">'
                        html += '<div id="lowerCourtDetails">'
                        html += '<div width="100%">'
                        html += '<table width="100%" border="1" style="border-collapse: collapse;" id="tr_id" class="table_tr_th_w_clr table_small_fomt">'
                        html += '<tbody>'
                        html += '<tr>'
                        html += '<th> S.No. </th>'
                        html += '<th> Court </th>'
                        html += '<th> Agency State </th>'
                        html += '<th> Agency Code </th>'
                        html += '<th> Case No. </th>'
                        html += '<th> Order Date </th>'
                        html += '<th> CNR No. / Designation </th>'
                        html += '<th> Judge1/ Judge2/ Judge3 </th>'
                        html += '<th> Police Station </th>'
                        html += '<th> Crime No./ Year </th>'
                        html += '<th> Authority / Organisation / Impugned Order No. </th>'
                        html += '<th> Judgement Challanged </th>'
                        html += '<th> Judgement Type </th>'
                        html += '<th> Judgement Covered in </th>'
                        html += '<th> Vehicle Number </th>'
                        html += '<th> Reference court / State / District / No. </th>'
                        html += '<th> Relied Upon court / State / District / No. </th>'
                        html += '<th> Transfer To State / District / No. </th>'
                        html += '<th> Government Notification State / No. / Date </th>'
                        html += '</tr>'

                        let earlier_court = resData.get_earlier_court
                        let cntr = 0
                        earlier_court.forEach(function(row5) {
                            html += '<tr>'
                            cntr = cntr + 1
                            html += '<td>' + cntr + '</td>'
                            html += '<td>' + row5.ct_code + '</td>'
                            html += '<td>' + row5.name + '</td>'
                            html += '<td>' + row5.agency_name + '</td>'
                            html += '<td>' + row5.type_sname_lct_caseno + '</td>'
                            html += '<td>' + row5.lct_dec_dt + '</td>'
                            html += '<td>' + row5.cnr_no + (row5['cnr_no'] != '' ? ' /' : '') + ((row5.post_name != null && row5.post_name != '') ? row5.post_name : '') + '</td>'
                            html += '<td>' + row5.jud_name + '</td>'
                            html += '<td>' + ((row5.policestndesc != null && row5.policestndesc != '') ? row5.policestndesc : '') + '</td>'
                            html += '<td>' + row5.crime_desc + '</td>'
                            html += '<td>' + row5.l_inddep + '/' + row5.auth_org + '</td>'
                            html += '<td>' + row5.is_order_challenged + '</td>'
                            html += '<td>' + row5.full_interim_flag + '</td>'
                            html += '<td>' + row5.judgement_covered_in + '</td>'
                            html += '<td>' + row5.code_vehicle_no + '</td>'

                            html += '<td>'
                            if (row5['ref_case_no'] == 0 || row5['ref_case_no'] == '') {
                                html += "-"
                            } else {
                                html += row5['ref_case_no']
                            }
                            html += ' / '
                            if (row5['ref_state'] == 0 || row5['ref_state'] == '') {
                                html += '-'
                            } else {
                                html += row5['ref_state']
                            }
                            html += ' / '
                            if (row5['ref_district'] == 0 || row5['ref_district'] == '') {
                                html += '-'
                            } else {
                                html += row5['ref_district']
                            }
                            html += ' / '
                            if (row5['ref_case_type'] == 0 || row5['ref_case_type'] == '') {
                                html += ''
                            } else {
                                html += row5['ref_case_type']
                            }
                            html += '-'
                            if (row5['ref_case_no'] == 0 || row5['ref_case_no'] == '') {
                                html += ""
                            } else {
                                html += row5['ref_case_no']
                            }
                            html += '-'
                            if (row5['ref_case_year'] == 0 || row5['ref_case_year'] == '') {
                                html += ''
                            } else {
                                html += row5['ref_case_year']
                            }
                            html += '</td>'

                            html += '<td>'
                            if (row5['relied_court'] == 0 || row5['relied_court'] == '') {
                                html += "-"
                            } else {
                                html += row5['relied_court']
                            }
                            html += ' / '
                            if (row5['relied_state'] == 0 || row5['relied_state'] == '') {
                                html += '-'
                            } else {
                                html += row5['relied_state'];
                            }
                            html += ' / '
                            if (row5['relied_district'] == 0 || row5['relied_district'] == '') {
                                html += '-'
                            } else {
                                html += row5['relied_district'];
                            }
                            html += ' / '
                            if (row5['relied_case_type'] == 0 || row5['relied_case_type'] == '') {
                                html += ''
                            } else {
                                html += row5['relied_case_type'];
                            }
                            html += '-'
                            if (row5['relied_case_no'] == 0 || row5['relied_case_no'] == '' || row5['relied_case_no'] == null) {
                                html += ""
                            } else {
                                html += row5['relied_case_no'];
                            }
                            html += '-'
                            if (row5['relied_case_year'] == 0 || row5['relied_case_year'] == '' || row5['relied_case_year'] == null) {
                                html += ''
                            } else {
                                html += row5['relied_case_year'];
                            }
                            html += '</td>'

                            html += '<td>'
                            if (row5['transfer_court'] == 0 || row5['transfer_court'] == '') {
                                html += '-'
                            } else {
                                html += row5['transfer_court']
                            }
                            html += ' / '
                            if (row5['transfer_state'] == 0 || row5['transfer_state'] == '') {
                                html += '-'
                            } else {
                                html += row5['transfer_state']
                            }
                            html += ' / '
                            if (row5['transfer_district'] == 0 || row5['transfer_district'] == '') {
                                html += '-'
                            } else {
                                html += row5['transfer_district']
                            }
                            html += ' / '
                            if (row5['transfer_case_type'] == 0 || row5['transfer_case_type'] == '') {
                                html += ''
                            } else {
                                html += row5['transfer_case_type']
                            }
                            html += '-'
                            if (row5['transfer_case_no'] == '0' || row5['transfer_case_no'] == '' || row5['transfer_case_no'] == null || row5['transfer_case_no'] == '') {
                                html += ""
                            } else {
                                html += row5['transfer_case_no']
                            }
                            html += '-'
                            if (row5['transfer_case_year'] == 0 || row5['transfer_case_year'] == '' || row5['transfer_case_year'] == null) {
                                html += ''
                            } else {
                                html += row5['transfer_case_year']
                            }
                            html += '</td>'

                            html += '<td>'
                            if (row5['gov_not_state_id'] == 0 || row5['gov_not_state_id'] == '') {
                                html += "-"
                            } else {
                                html += row5['gov_not_state_id']
                            }
                            html += ' / '
                            if (row5['gov_not_case_type'] == '') {
                                ''
                            } else {
                                html += row5['gov_not_case_type']
                            }
                            html += '-'
                            if (row5['gov_not_case_no'] == 0 || row5['gov_not_case_no'] == '') {
                                ''
                            } else {
                                html += row5['gov_not_case_no']
                            }
                            html += '-'
                            if (row5['gov_not_case_year'] == 0 || row5['gov_not_case_year'] == '') {
                                ''
                            } else {
                                html += row5['gov_not_case_year']
                            }
                            html += ' / '
                            if (row5['gov_not_date'] == '0000-00-00' || row5['gov_not_date'] == '') {
                                '-'
                            } else {
                                html += row5['gov_not_date']
                            }
                            html += '</td>'

                            html += '</tr>'

                        })

                        html += '</tbody>'
                        html += '</table>'
                        html += '</div>'
                        html += '</div>'
                        html += '</div>'
                        html += '</div>'
                        html += '</div>'
                        html += '<div class="main_div">'
                        html += '<div class="sub_div1">Listing History : <span class="form-check form-check-inline bg-info">'
                        html += '<input class="form-check-input check_Box" type="checkbox" name="listingData" id="listingData">'
                        html += '<label class="form-check-label" for="listingData"> Yes, I have verified Case Listing data</label>'
                        html += '</span>'
                        html += '</div>'
                        html += '<div class="card">'
                        html += '<div class="card-body"> Matter is Listed for dated: '

                        let row_listing_status = resData.result_listing_status
                        let row_rgo_default = resData.result_rgo_default
                        var today = new Date();
                        var dd = String(today.getDate()).padStart(2, '0');
                        var mm = String(today.getMonth() + 1).padStart(2, '0');
                        var yyyy = today.getFullYear();
                        today_date = yyyy + '-' + mm + '-' + dd;

                        if (row_listing_status.length) {
                            row_listing_status = row_listing_status[0]
                            if ((row_listing_status['mainhead'] == 'M') &&
                                (
                                    (row_listing_status['subhead'] == 0 || row_listing_status['subhead'] == '') ||
                                    (row_listing_status['next_dt'] < today_date &&
                                        (row_listing_status['main_supp_flag'] == 0 || row_listing_status['main_supp_flag'] == 1 || row_listing_status['main_supp_flag'] == 2)
                                    )
                                ) &&
                                (row_rgo_default.length)
                            ) {
                                html += "<span style='color:red'>Updation Awaited!! First update listing details using DA=>Proposal module</span>"
                                // listing_info = 0;
                            } else {
                                let ddt_date1 = row_listing_status['next_dt'].split(" ")
                                ddt_date1 = ddt_date1[0]
                                ddt_date1 = ddt_date1.split('-').reverse().join('-');
                                // html += "Matter is Listed for dated :" + ddt_date1
                                html += ddt_date1
                            }
                        }

                        html += '</div>'
                        html += '</div>'
                        html += '</div>'

                        if (row_avl['c_status'] == 'D') {
                            if (results_disp.length) {
                                results_disp = results_disp[0]
                                html += '<div class="main_div">'
                                html += '<div class="sub_div1">Dispose Date :</div>'
                                html += '<div class="sub_div2">'
                                let ddt_date1 = results_disp['ord_dt'].split(" ")
                                ddt_date1 = ddt_date1[0]
                                ddt_date1 = ddt_date1.split('-').reverse().join('-');
                                html += '<input type="text" size="10" class="dtp" name="dispdt" id="dispdt" value="' + ddt_date1 + '" readonly/>'
                                html += '<span id="dispdt_result"></span>'
                                html += '</div>'
                                html += '</div>'
                                html += '<hr style="color: #ABC4D4;">'
                            }
                        }

                        html += '<div class="main_div">'
                        html += '<div class="sub_div1">IAs (Pending) : <span class="form-check form-check-inline bg-info">'
                        html += '<input class="form-check-input check_Box" type="checkbox" name="iaCheckedAndVerified" id="iaCheckedAndVerified">'
                        html += "<label class='form-check-label' for='iaCheckedAndVerified'> Yes, I have verified IA's Data</label>"
                        html += '</span>'
                        html += '</div>'
                        html += '<div class="card">'
                        html += '<div class="card-body" style="max-height:400px;overflow:auto">'
                        html += '<table class="table_tr_th_w_clr c_vertical_align" width="100%">'
                        html += '<tbody>'
                        html += '<tr>'
                        html += '<td align="center">'
                        html += '<b>Sno</b>'
                        html += '</td>'
                        html += '<td align="center">'
                        html += '<b>IA.No.</b>'
                        html += '</td>'
                        html += '<td>'
                        html += '<b>Particular</b>'
                        html += '</td>'
                        html += '<td align="center">'
                        html += '<b>Date</b>'
                        html += '</td>'
                        html += '</tr>'

                        let res_ian = resData.results_ian
                        let iancntr = 1
                        res_ian.forEach(function(row_ian) {
                            html += '<tr>'
                            html += '<td align="center">' + iancntr + '</td>'
                            html += '<td align="center">' + row_ian["docnum"] + "/" + row_ian["docyear"] + '</td>'
                            html += '<td align="left">'
                            if (row_ian["other1"] != "") {
                                t_part = row_ian["docdesc"] + " [" + row_ian["other1"] + "]"
                            } else {
                                t_part = row_ian["docdesc"]
                            }
                            html += t_part.replace("XTRA", "")
                            html += '</td>'
                            let ddt_date1 = row_ian["ent_dt"].split(" ")
                            ddt_date1 = ddt_date1[0]
                            ddt_date1 = ddt_date1.split('-').reverse().join('-');
                            html += '<td align="center">' + ddt_date1 + '</td>'
                            html += '</tr>'
                            iancntr = iancntr + 1
                        })
                        html += '</tbody>'
                        html += '</table>'
                        html += '</div>'
                        html += '</div>'
                        html += '</div>'
                        html += '<div class="main_div">'
                        html += '<div class="sub_div1">Act/Section(s): </div>'
                        html += '<div class="card">'
                        html += '<div class="card-body">'

                        let act = resData.act
                        if (act.length > 0) {
                            act_section = '';
                            act.forEach(function(row1) {
                                if (act_section == '') {
                                    act_section = row1['act_name'] + '-' + row1['section']
                                } else {
                                    act_section = act_section + ', ' + row1['act_name'] + '-' + row1['section'];
                                }
                            })
                            html += act_section
                        } else {
                            html += '<div class="row col-sm-12">'
                            html += '<div class="col-sm-4" id="actDiv" style="margin-left: 15px;;">'
                            html += '<label for="act" id="lbl_Act" class="">Act:</label>'
                            html += '<select class="form-control select-box" class="select-box" id="act" name="act" required>'
                            html += '<option value="">---Select Act---</option>'
                            let actList = resData.res13
                            if (actList.length > 0) {
                                actList.forEach(function(result) {
                                    html += '<option value="' + result['id'] + '/' + result['actno'] + '">' + result['act_name'] + '- ' + result['act_name_h'] + '</option>'
                                })
                            }
                            html += '</select>'
                            html += '</div>'
                            html += '<div class="col-sm-4" id="sectionDiv" >'
                            html += '<label for="category" id="lbl_section" class="select-box">Select Section:</label>'
                            html += '<select class="form-control select-box"  id="section" name="section"  placeholder="Select Section" required>'
                            html += '</select>'
                            html += '</div>'
                            html += '</div>'
                        }
                        html += '</div>'
                        html += '</div>'
                        html += '</div>'
                        html += '</form>'
                        html += '</div>'
                        html += '<div>'
                        html += '<p id="ajax_result" style="font-size: 20px;text-align:center;"></p>'
                        html += '</div>'
                        html += '<div style="width: 100%; padding-bottom:1px; background-color: #B5BBFF; text-align: center; ">'
                        html += '<input type="button" name="data_update" id="data_update" value="PHYISICAL VERIFICATION OF CASE DONE BY ME" style="">'
                        html += '</div>'
                        html += '</div>'

                        $('#modData').append(html);
                        $('#physicalVerificationModal').modal('toggle')
                        $("#data_update").hide();
                        // $('#physicalVerificationModal').show();



                        $(".check_Box").change(function() {
                            // console.log( $('.check_Box:checked').length , $('.check_Box').length )
                            if ($('.check_Box:checked').length != $('.check_Box').length) {
                                $("#data_update").hide();
                            } else {
                                $("#data_update").show();
                            }
                        });


                        $('#act').on('change', function(e) {
                            updateCSRFToken()
                            setTimeout(() => {
                                var CSRF_TOKEN = 'CSRF_TOKEN';
                                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                                // console.log("val:: ", e.target.value)
                                let act_val = e.target.value
                                var act = $.trim(act_val);
                                var act_id = act.split('/')[0];
                                if (act_id) {
                                    $.ajax({
                                        url: "<?php echo base_url('Judicial/PhysicalVerification/get_sections_by_act'); ?>",
                                        cache: false,
                                        async: true,
                                        type: 'POST',
                                        dataType: 'html',
                                        data: {
                                            CSRF_TOKEN: CSRF_TOKEN_VALUE,
                                            act_id
                                        },
                                        // beforeSend: function () {
                                        //     $('#section').html('<table widht="100%" align="center"><tr><td><img src="../images/load.gif"/></td></tr></table>');
                                        // },
                                        success: function(res) {
                                            console.log("res:: ", res)
                                            $("#section").html('');
                                            $("#section").html(res);

                                            updateCSRFToken()
                                        },
                                        error: function(xhr) {
                                            alert("Error: " + xhr.status + " " + xhr.statusText);
                                            updateCSRFToken()
                                        }
                                    });
                                } else {
                                    alert("Please select main category.")
                                    $("#mainCategory").focus();
                                    $("#mainCategory").css({
                                        'border-color': 'red'
                                    });
                                    return false;
                                }
                            }, 400);
                        })


                        $('#mainCategory').on('change', function(e) {
                            updateCSRFToken()
                            let main_cat = e.target.value
                            var mainCat = $.trim(main_cat);
                            var mainCategory_id = mainCat.split('/')[0];
                            var mainCategory = mainCat.split('/')[1];

                            if (mainCategory) {
                                setTimeout(() => {

                                    var CSRF_TOKEN = 'CSRF_TOKEN';
                                    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                                    $.ajax({
                                        url: "<?php echo base_url('Judicial/PhysicalVerification/get_sub_category_by_main_catId'); ?>",
                                        cache: false,
                                        async: true,
                                        type: 'POST',
                                        dataType: 'html',
                                        data: {
                                            CSRF_TOKEN: CSRF_TOKEN_VALUE,
                                            mainCategory
                                        },
                                        // beforeSend: function () {
                                        //     $('#categoryCode').html('<table widht="100%" align="center"><tr><td><img src="../images/load.gif"/></td></tr></table>');
                                        // },
                                        success: function(res) {
                                            $('#dv_res1').html('');
                                            $("#categoryCode").html('');
                                            $("#categoryCode").html(res);
                                            updateCSRFToken()
                                        },
                                        error: function(xhr) {
                                            alert("Error: " + xhr.status + " " + xhr.statusText);
                                            updateCSRFToken()
                                        }
                                    });
                                }, 200);

                            } else {
                                alert("Please select main category.")
                                $("#mainCategory").focus();
                                $("#mainCategory").css({
                                    'border-color': 'red'
                                });
                                return false;
                            }


                        });

                        $("#data_update").click(function() {
                            updateCSRFToken()
                            var isCategoryPresent = isCategoryPresent;
                            var callFunction = '';
                            if (isCategoryPresent == 'N') {
                                callFunction = validateData();
                            } else {
                                callFunction = validateDataWithoutSubject(active_casetype_id);
                            }
                            if (callFunction) {
                                var form_data = JSON.stringify($("form").serializeArray());
                                // console.log("form_data:: ", form_data)
                                // return
                                setTimeout(() => {
                                    var CSRF_TOKEN = 'CSRF_TOKEN';
                                    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                                    $.ajax({
                                        url: "<?php echo base_url('Judicial/PhysicalVerification/physical_verification_data_updation'); ?>",
                                        cache: false,
                                        async: true,
                                        data: {
                                            CSRF_TOKEN: CSRF_TOKEN_VALUE,
                                            form_data: form_data
                                        },
                                        // beforeSend: function () {
                                        //     $('#ajax_result').html('<table widht="100%" align="center"><tr><td><img src="../images/load.gif"/></td></tr></table>');
                                        // },
                                        type: 'POST',
                                        success: function(data, status) {
                                            if (data == 1) {
                                                $('#ajax_result').css({
                                                    "color": "green"
                                                });
                                                $('#ajax_result').html("Data Updated Successfully");
                                                updateCSRFToken()
                                            } else {
                                                $('#ajax_result').css({
                                                    "color": "red"
                                                });
                                                $('#ajax_result').html("Try Again! Unable to update data");
                                                updateCSRFToken()
                                            }

                                        },
                                        error: function(xhr) {
                                            alert("Error: " + xhr.status + " " + xhr.statusText);
                                            updateCSRFToken()
                                        }
                                    });
                                }, 200);

                            } else {
                                alert('Please fill the required information');
                            }
                        });
                    } else {
                        // $('#physicalVerificationModal').hide();
                    }
                    $("#loader").html('');
                    updateCSRFToken()
                },
                error: function(xhr) {
                    alert("Error: " + xhr.status + " " + xhr.statusText);
                    updateCSRFToken()
                }
            });
        }
    }

    $(document).ready(function() {

        // $("#data_update").hide();
        // $(".check_Box").change(function(){
        //     if ($('.check_Box:checked').length != $('.check_Box').length) {
        //         $("#data_update").hide();
        //     }
        //     else {
        //         $("#data_update").show();
        //     }
        // });

        $("#example").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print"]
        }).buttons().container().appendTo('#example_wrapper .col-md-6:eq(0)');



    });

    $(".update-physical_verfiy").click(function() {

        updateCSRFToken()

        $("#data_update").hide();
        var valid_dno = $(this).attr("data-diary_no");
        var click_button_id = $(this).attr("data-button_id");
        var a = confirm("Are you sure that Physical Judicial File is not available with you?");
        if (a == true) {
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

            setTimeout(() => {
                $.ajax({
                    url: "<?php echo base_url('Judicial/PhysicalVerification/wrong_updated_get_response'); ?>",
                    cache: false,
                    async: true,
                    data: {
                        CSRF_TOKEN: CSRF_TOKEN_VALUE,
                        valid_dno: valid_dno
                    },
                    beforeSend: function() {
                        $('#di_rslt_sucs').html('<table widht="100%" align="center"><tr><td><img src="../images/load.gif"/></td></tr></table>');
                    },
                    type: 'POST',
                    success: function(data, status) {
                        if (data) {
                            alert(data)
                            $('#' + click_button_id).css('display', 'none');
                            // $("#data_update1").show();
                            $('#ajax_result').css({
                                "color": "green"
                            });
                            $('#ajax_result').html(data);
                        } else {
                            alert(data)
                            $('#ajax_result').css({
                                "color": "red"
                            });
                            $('#ajax_result').html(data);
                            // $("#data_update1").show();
                        }
                        updateCSRFToken()

                    },
                    error: function(xhr) {
                        alert("Error: " + xhr.status + " " + xhr.statusText);
                        updateCSRFToken()
                    }
                });
            }, 200);

        }

    });


    function checkDate(currVal) {
        if (currVal == "")
            isCorrectDateFormat = false;
        if ($.isNumeric(currVal)) {
            isCorrectDateFormat = false;
        } else {
            //Declare Regex
            var rxDatePattern = /^(\d{1,2})(\/|-)(\d{1,2})(\/|-)(\d{4})$/;
            var dtArray = currVal.match(rxDatePattern); // is format OK?

            var isCorrectDateFormat = '';

            if (dtArray == null || dtArray == " ")
                isCorrectDateFormat = false;
            else {
                isCorrectDateFormat = true;

                //Checks for mm/dd/yyyy format.
                dtDay = dtArray[1];
                dtMonth = dtArray[3];
                dtYear = dtArray[5];

                if (dtYear < 1950 || dtYear > 2023) {
                    isCorrectDateFormat = false;
                } else if (dtMonth < 1 || dtMonth > 12) {
                    isCorrectDateFormat = false;
                } else if (dtDay < 1 || dtDay > 31) {
                    isCorrectDateFormat = false;
                } else if ((dtMonth == 4 || dtMonth == 6 || dtMonth == 9 || dtMonth == 11) && dtDay == 31) {
                    isCorrectDateFormat = false;
                } else if (dtMonth == 2) {
                    var isleap = (dtYear % 4 == 0 && (dtYear % 100 != 0 || dtYear % 400 == 0));
                    if (dtDay > 29 || (dtDay == 29 && !isleap)) {
                        isCorrectDateFormat = false;
                    }
                } else
                    isCorrectDateFormat = true;
                //alert(isCorrectDateFormat);
            }
        }

        if (isCorrectDateFormat == true) {
            $("#data_update").show();
            return true;
        } else {
            $("#data_update").hide();
            $(this.focus());
            alert('Please Enter valid date');
            return false;

        }
    }

    function validateDataWithoutSubject(active_casetype_id) {
        var casetype_id = active_casetype_id;
        var diaryDate = $("#ddt").val();
        var leaveGrantDate = $("#fhdt").val();
        var isValidDate = '';

        if (diaryDate.length == 0 || diaryDate == '') {
            alert("Please enter valid diary date.");
            $("#ddt").focus();
            $("#ddt").css({
                'border-color': 'red'
            });
            return false;
        } else {
            if ((diaryDate.length != 0 && diaryDate != '')) {
                isValidDate = checkDate(diaryDate);
                if (isValidDate == 'false')
                    return false;

            }
            if ((casetype_id != 0 && casetype_id != 9 && casetype_id != 10 && casetype_id != 25 && casetype_id != 26 && casetype_id != 19 && casetype_id != 20 && casetype_id != 39)) {
                if ((leaveGrantDate.length != 0 && leaveGrantDate != '')) {
                    if (isValidDate == 'false')
                        return false;
                }
            }
            var countNumChangeHistory = $(".order_date:input").length;
            var count = 0;
            $(".order_date:input").each(function() {
                count++;
                isValidDate == 'false'
                var isValidDate = checkDate($(this).val());
                if (isValidDate == 'true' && (count == countNumChangeHistory))
                    return true;
                else
                    return false;
            });


            if ($('.check_Box:checked').length != $('.check_Box').length) {
                alert('Please checked all the Check boxed, if the data is verified');
                $("#data_update").hide();
                return false
            } else {
                $("#data_update").show();
            }

            return true;

        }
    }

    function validateData() {
        var casetype_id = active_casetype_id;
        var mainCategory = $("select#mainCategory option:selected").val();
        var subCatIdArr = $("select#categoryCode option:selected").val();
        var diaryDate = $("#ddt").val();
        var leaveGrantDate = $("#fhdt").val();
        var isValidDate = '';

        //alert('subCatIdArr='+subCatIdArr);// return false;
        if (mainCategory == '') {
            alert("Please select main category.");
            $("#mainCategory").focus();
            $("#mainCategory").css({
                'border-color': 'red'
            });
            return false;
        } else if (subCatIdArr.length == 0 || subCatIdArr == '') {
            alert("Please select sub category .");
            $("#categoryCode").focus();
            $("#categoryCode").css({
                'border-color': 'red'
            });
            return false;
        } else if (diaryDate.length == 0 || diaryDate == '') {
            alert("Please enter valid diary date.");
            $("#ddt").focus();
            $("#ddt").css({
                'border-color': 'red'
            });
            return false;
        } else {
            if ((diaryDate.length != 0 && diaryDate != '')) {
                isValidDate = checkDate(diaryDate);
                if (isValidDate == 'false')
                    return false;

            }
            if (casetype_id != 0 && casetype_id != 9 && casetype_id != 10 && casetype_id != 25 && casetype_id != 26 && casetype_id != 19 && casetype_id != 20 && casetype_id != 39) {
                if (leaveGrantDate.length != 0 && leaveGrantDate != '') {
                    if (isValidDate == 'false')
                        return false;
                }
            }
            var countNumChangeHistory = $(".order_date:input").length;
            var count = 0;
            $(".order_date:input").each(function() {
                count++;
                isValidDate == 'false'
                var isValidDate = checkDate($(this).val());
                if (isValidDate == 'true' && (count == countNumChangeHistory))
                    return true;
                else
                    return false;
            });

            if ($('.check_Box:checked').length != $('.check_Box').length) {
                alert('Please checked all the Check boxed, if the data is verified');
                $("#data_update").hide();
                return false
            } else {
                $("#data_update").show();
            }

            return true;

        }
    }

    function isEmpty(xx) {
        var yy = xx.value.replace(/^\s*/, "");
        if (yy == "") {
            xx.focus();
            return true;
        }
        return false;
    }
</script>
