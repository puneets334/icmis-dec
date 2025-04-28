<style>
    .main_block_hed_before_print{position: absolute;
    div.dataTables_wrapper {
    position: relative;
    z-index: 91;
}
.bsubmit_cls{
    position: relative;
    z-index: 92;
    margin-right: 17%;}
</style>
<div class="card-body">
    <!--<div class="d-flex justify-content-start mb-3">
        <button type="button" class="btn btn-primary" id="bsubmit" onclick="addRecord()">Submit</button>
    </div>-->
    <div id="prnnt" style="font-size:12px;">
        
    <div class="w-100 inline-block main_block_hed_before_print">
            <div class="row">
                <div class="col-md-4"></div>
                <div class="col-md-4"><h3 id="caption"><?= $mainhead == 'M' ? "Misc. Hearing" : "Regular Hearing"; ?></h3></div>
                <div class="col-md-4 text-right">
                    <button type="button" class="btn btn-primary bsubmit_cls" id="bsubmit" onclick="addRecord()" style="position:relative;z-index:92;">Submit</button>
                </div>
            </div>
        </div>


        <div class="table-responsive">
            <?php if ($records) { ?>
                <table id="example1" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th scope="col" width="5%">SNo.</th>
                            <th scope="col" width="25%">Diary/Reg No</th>
                            <th scope="col" width="25%">Petitioner / Respondent</th>
                            <th scope="col" width="15%">Last Order</th>
                            <th scope="col" width="12%">Remark</th>
                            <th scope="col" width="13%">Section/ DA</th>
                            <th scope="col" width="5%">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="chkall2" id="chkall2" value="ALL" onclick="all_case_v(this);">
                                    <label class="form-check-label" style="margin-left:10px;" for="chkall2">All</label>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sno = 1;
                        foreach ($records as $record) {
                        ?>
                            <tr>
                                <th scope="row"><?= $sno++; ?></th>
                                <td><?= substr_replace($record['diary_no'], '-', -4, 0) . "<br>" . $record['reg_no_display']; ?></td>
                                <td><?= $record['pet_name'] . "<br/>Vs<br/>" . $record['res_name']; ?></td>
                                <td><?= $record['lastorder']; ?></td>
                                <td><?= $record['purpose']; ?></td>
                                <td><?= $record['sec'] . "<br>" . $record['name']; ?></td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="chkeeed2" name="chk2" value="<?= $record['diary_no'] . "@" . $record['submaster_id']; ?>">
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php } else { ?>
                <div class="mt-3 text-danger text-center">SORRY, NO RECORD FOUND!!!</div>
            <?php } ?>
        </div>
    </div>
    <!--<button type="button" class="btn btn-primary mt-3" id="prnnt1">Print</button>-->
</div>

<script>
    $("#example1").DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
        "dom": 'Bfrtip',
        "bProcessing": true,
        //"buttons": ['print'],
        "searching": false,
        buttons: [
            {
                extend: 'print',
                text: 'Print',
                className: 'btn-primary',
                title: '',
                /*customize: function(win) {
                    $(win.document.body).css('text-align', 'center'); // Align all content centrally
                }*/
                customize: function (win) {
                    $(win.document.body).css( 'font-size', '12pt');
                    const captionHTML = $('#caption').html();
                    $(win.document.body).find('table').before('<h3 style="text-align: center;">' + captionHTML + '</h3>');
                },
            }
        ],
        
    });
    
    $('.buttons-print').removeClass('btn-secondary');
    $('.btn-group').removeClass('dt-buttons');
</script>