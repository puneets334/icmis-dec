<div class="header mb-3">
    <h3 style="text-align: center;color:#ECEEF2;">Partial Court Working Days Advance Matters List Year <?= date('Y') ?></h3>
    <div align="left">
            <div>
                <input type="radio" name="mainhead" class="mainhead" value="M" title="Miscellaneous" <?= $mainhead == 'M' ? 'checked' : '' ?>>Miscellaneous&nbsp;
                <input type="radio" name="mainhead" class="mainhead" value="F" title="Regular" <?= $mainhead == 'F' ? 'checked' : '' ?>>Regular&nbsp;
            </div>
        </div>
        <h3 style="text-align: center;">
            <?php
            if (isset($mainhead)) {
                if ($mainhead == 'M') {
                    echo "Miscellaneous Stage Cases";
                } else if ($mainhead == 'F') {
                    echo "Regular Stage Cases";
                } else {
                    echo "Please select a case type";
                }
            }
            ?>
        </h3>
        <?php if (isset($mainhead)) { ?>
    <div align="center">
        <button type="button" id="declineButton" class="btn btn-primary btn-lg" onclick="javascript:confirmBeforeDecline();"><strong>Decline Selected Case(s)</strong></button>
    </div>
    <div class="end-buttons">
        <button type="button" class='print quick-btn' id="print" onclick="printPage()" title='Print Report'>
            <i class="fa fa-print" style="font-size:16px"></i> PDF
        </button>
    </div>
    <?php } ?>
</div>
<div id="prnnt" style="font-size:12px;">
    
    <table class="table table-striped table-bordered">
    <?php if (isset($mainhead)) { ?>
        <thead>
            <tr>
                <th><b>#</b></th>
                <th><b>Case No @ Diary No.</b></th>
                <th><b>Cause Title</b></th>
                <th><b>Advocate</b></th>
                <th width="15%" style="text-align: center;"><b>Advocate Declined %</b></th>
                <th width="15%" style="text-align: center;"><b>Decline/Listed</b></th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th><b>#</b></th>
                <th><b>Case No @ Diary No.</b></th>
                <th><b>Cause Title</b></th>
                <th><b>Advocate</b></th>
                <th width="15%" style="text-align: center;"><b> Advocate Declined %</b></th>
                <th width="15%" style="text-align: center;"><b>Decline/Listed</b></th>
            </tr>
        </tfoot>
        <tbody>
            <?php
            $psrno = 1;
            $srNo = 0;
            if($cases){
                foreach ($cases as $r) {
                
                    $con_no = 0;
                    $is_connected = '';
                    if ($r['diary_no'] == $r['conn_key'] || $r['conn_key'] == 0) {
                        $print_brdslno = $psrno;
                        $print_srno = $psrno;
                    } elseif ($r['main_or_connected'] == 1) {
                        $print_brdslno = "&nbsp;" . $print_srno . "." . ++$con_no;
                        $is_connected = "<span style='color:red;'>Connected</span><br/>";
                    }
                ?>
                    <tr>
                        <td><?= $print_brdslno; ?></td>
                        <td><?= $r['case_no']; ?><br><?= $is_connected; ?></td>
                        <td><?= esc($r['cause_title']); ?></td>
                        <!-- <td>
                            <?php
                            // $advocates = $caseAddModel->getVacationAdvocates($r['diary_no']);
                            // echo $advocates['advocate'] ?? '';
                            ?>
                        </td> -->
                        <?php
                        $advocates = $caseAddModel->getVacationAdvocates($r['diary_no']);
                        if (!empty($advocates)) {
                            foreach ($advocates as $r_adv) {
                                ?>
                                <td>
                                    <?php echo $r_adv['advocate'];
                                    ?>
                                </td>
                                <?php

                                ?>
                                <td>
                                    <?php $total_declined = $r_adv['total_declined'];
                                    $adv_count = $r_adv['adv_count'];
                                    $dec_per = $total_declined * 100 / $adv_count;
                                    if(intval($dec_per) < 50)
                                        echo "<span style='color:red';>".intval($dec_per)."%</span>";
                                    else
                                        echo "<span style='color:green';>".intval($dec_per)."%</span>";
                                    
                                    ?>
                                </td> <?php

                            }
                        }
                        else{
                            ?>
                            <td></td><td></td>
                            <?php
                        }

                        ?>
                        <td style="text-align: center;" id="d_<?= esc($r['diary_no']); ?>">
    
                            <a>
                                <?php if ($r['declined_by_admin'] == 't'): ?>
                                    
                                    <a class='btn btn-xs btn-danger' onclick="confirmBeforeList(<?= esc($r['diary_no']); ?>);">
                                        <span id="deleteButton" class="ui-icon ui-icon-closethick"></span> Declined
                                    </a>
                                <?php else: ?>
                                    
                                    <?php if ($r['is_fixed'] != 'Y'): ?>
                                        
                                        <input type='checkbox' name='vacationList' id='vacationList'  value='<?= esc($r['diary_no']); ?>'>
                                    <?php else: ?>
                                        
                                        <span style='color:green;'>Fixed For <br> Partial Court Working Days</span><br />
                                    <?php endif; ?>
                                <?php endif; ?>
                            </a>
                        </td>
                    </tr>
                <?php
                    $psrno++;
                }  
            } else { ?>
                <tr ><td  colspan="5"><?= "No Record Found" ?></td></tr>
            <?php }
            }
            
            ?>
        </tbody>
    </table>
</div>
<script>
     function get_mainhead() {
        var mainhead = "";
        $('input[type=radio]').each(function () {
            if ($(this).attr("name") == "mainhead" && this.checked)
                mainhead = $(this).val();
        });
        return mainhead;
    }
    $('input[type=radio][name=mainhead]').change(function () {
        var mainhead = get_mainhead();
        var CSRF_TOKEN = 'CSRF_TOKEN';

        $.ajax({
            url: 'getVacationAdvanceList.php',
            type: "POST",
            data: {
                action: 'get_flag',
                mainhead: mainhead,
                CSRF_TOKEN: csrf,
                userID: $('#user_code').val()
            },
            cache: false,

            success: function (data) {
                updateCSRFToken();
                $('#example20').html(data);
            },
            error: function (xhr, status, error) {
                updateCSRFToken();
                console.error("Error fetching data:", status, error);
                alert("Error loading cases.");
            }

        });
    });
    function confirmBeforeDecline() {
        var allVals = [];
        var noOfCases;
        $("input:checkbox[name=vacationList]:checked").each(function() {
            allVals.push($(this).val());
        });
        noOfCases = allVals.length;
        
        if (noOfCases < 1) {
            alert('Please select atleast one Case which need to be Decline');
            return false;
        } else {
            var choice = confirm('Do you really want to decline the case.....?');
            if (choice == true) {
                declineVacationCase(allVals);
            } else {
                return false;
            }
        }
    }

    function confirmBeforeList(diary_no) {
        var choice = confirm('Do you really want to List the Selected Case.....?');
        if (choice == true) {
            ListVacationCase(diary_no);
        }

    }

    function declineVacationCase(allVals) {
        var empID = $('#empid').val();
        var userID = $('#user_code').val();
        var mainhead = get_mainhead();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var csrf = $("input[name='CSRF_TOKEN']").val();
        $.ajax({
            url: '<?php echo base_url('Listing/VacationAdvanceList/declineVacationListCases'); ?>',
            type: "POST",
            data: {
                diary_no: allVals,
                empID: empID,
                userID: userID,
                mainhead: mainhead,
                CSRF_TOKEN: csrf,
            },
            cache: false,
            success: function(r) {
                updateCSRFToken();
                if (r != '') {
                    alert('Selected Case with Diary No:(' + allVals + ') Successfully Declined');
                    //var data = JSON.parse(r);
                    $.each(r, function(index, el) {
                        $('#d_' + index).html(el);
                    });
                } else {
                    alert("Invalid Diary No. !! Please try again...");
                }
            },
            error: function() {
                updateCSRFToken();
                alert('ERROR');
            }
        });
    }

    function ListVacationCase(diary_no) {
        var empID = $('#empid').val();
        var userID = $('#user_code').val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var mainhead = get_mainhead();
        var csrf = $("input[name='CSRF_TOKEN']").val();
        $.ajax({
            url: '<?php echo base_url('Listing/VacationAdvanceList/restoreVacationAdvanceList'); ?>',
            type: "POST",
            data: {
                diary_no: diary_no,
                empID: empID,
                userID: userID,
                mainhead: mainhead,
                CSRF_TOKEN: csrf,
            },
            cache: false,
            success: function(r) {
                updateCSRFToken();
                if (r != '') {
                    alert('Selected Case with Diary NO:' + diary_no + ' Successfully Listed');
                    $('#d_' + diary_no).html(r.html);
                } else {
                    alert("Invalid Diary No .!! Please try again...");
                }
            },
            error: function() {
                updateCSRFToken();
                alert('ERROR');
            }
        });
    }



    function printPage() {
        window.print();
    }
</script>