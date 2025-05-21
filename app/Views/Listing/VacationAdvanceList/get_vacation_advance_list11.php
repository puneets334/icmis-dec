<div class="header mb-3">
    <h3 style="text-align: center;color:#ECEEF2;">Vacation Advance Matters List Year <?= date('Y') ?></h3>
   
    
    <div align="center">
        <button type="button" id="declineButton" class="btn btn-primary btn-lg" onclick="javascript:confirmBeforeDecline();"><strong>Decline Selected Case(s)</strong></button>
    </div>
    <div class="end-buttons">
        <button type="button" class='print quick-btn' id="print" onclick="printPage()" title='Print Report'>
            <i class="fa fa-print" style="font-size:16px"></i> PDF
        </button>
    </div>
</div>
<div id="prnnt" style="font-size:12px;">
    
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th><b>#</b></th>
                <th><b>Case No @ Diary No.</b></th>
                <th><b>Cause Title</b></th>
                <th><b>Advocate</b></th>
                <th width="15%" style="text-align: center;"><b>Decline/Listed</b></th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th><b>#</b></th>
                <th><b>Case No @ Diary No.</b></th>
                <th><b>Cause Title</b></th>
                <th><b>Advocate</b></th>
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
                        <td>
                            <?php
                            $advocates = $caseAddModel->getVacationAdvocates($r['diary_no']);
                            echo $advocates['advocate'] ?? '';
                            ?>
                        </td>
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
                                        
                                        <span style='color:green;'>Fixed For <br> Vacation</span><br />
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
            
            ?>
        </tbody>
    </table>
</div>
<script>
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

    function printPage() {
        window.print();
    }
</script>