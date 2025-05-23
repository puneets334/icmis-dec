<?= view('header') ?>
<div class="row col-sm-12 mt-3">
    <div class="col-sm-12">
        <h4>TRANSER ENTRY OF CONSENT CASES FROM ONE COURT TO ANOTHER COURT FOR LISTING DATE
            <?= date('d-m-Y', strtotime($listing_dts)); ?>
        </h4>
    </div>
</div>
<?php if (!empty($dates)): ?>
    <table class="table table-responsive">
        <thead>
            <tr>
                <th>S. No.</th>
                <th>From Previous Court</th>
                <th>Total Cases</th>
                <th>Total Consents</th>
                <th>To New Court Details</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php $sno = 1; foreach ($dates as $row): ?>
                <tr>
                    <td data-key="S. No."><?= $sno++ ?></td>
                    <td data-key="From Previous Court"><?= $row['old_coram'] ?? '' ?><br><span class="text-primary"><?= $row['mainhead'] == 'F' ? 'Regular List' : ($row['board_type'] == 'J' ? 'Misc. List' : ($row['board_type'] == 'C' ? 'Chamber List' : 'Registrar List')) ?></span></td>
                    <td data-key="Total Cases"><?= $row['total_cases'] ?></td>
                    <td data-key="Total Consents"><?= $row['total_concent'] ?></td>
                    <td data-key="To New Court Details"><?= $row['new_coram'] ?? '' ?></td>
                    <td data-key="Action" id="transfer_result_<?= $row['old_roster_id'] ?>">
                        <button data-next_dt="<?= $listing_dts ?>" data-old_roster_id="<?= $row['old_roster_id'] ?>" data-new_roster_id="<?= $row['new_roster_id'] ?>" data-action="save" class="btn btn-secondary btn-block btn_transfer" type="button" name="btn_transfer" id="btn_transfer_<?= $row['old_roster_id'] ?>">Transfer</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <div class="row p-2 align-items-center">No Records Found</div>
<?php endif; ?>
<style>
.aorDataDiv {
    border: 2px solid #ead5d5;
}
.ppDataDiv {
    border: 2px solid #078e7b;
}
</style>
<script>
    $(document).on('click', '.btn_transfer', function() {
        var next_dt = $(this).data('next_dt');
        var old_roster_id = $(this).data('old_roster_id');
        var new_roster_id = $(this).data('new_roster_id');
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        swal({
            title: "Are you sure?",
            text: "Do you want to transfer record(s) ",
            icon: "warning",
            buttons: [
                'No, cancel it!',
                'Yes, I am sure!'
            ],
            dangerMode: true,
        }).then(function(isConfirm) {
            if (isConfirm) {
                var postData = {};
                postData.next_dt = next_dt;
                postData.old_roster_id = old_roster_id;
                postData.new_roster_id = new_roster_id;
                postData.CSRF_TOKEN = CSRF_TOKEN_VALUE;            
                $.ajax({
                    url: "<?php echo  base_url('HybridHearing/Transfer_cases/transfer_cases_save'); ?>",
                    type: 'POST',
                    dataType: "json",
                    cache: false,
                    async: true,
                    data: postData,
                    beforeSend: function() {
                        $("#btn_transfer_" + old_roster_id).prop('disabled', true);
                        $("#btn_transfer_" + old_roster_id).html("Loading <i class='fas fa-sync fa-spin'></i>");
                    },
                    success: function(data) {
                        updateCSRFToken();
                        if (data.status == 'success') {
                            $("#transfer_result_" + old_roster_id).html(
                                "<span class='text-success'>SUCCESS</span>");
                            swal({
                                title: "Success!",
                                text: "Record(s) Successfully Transferred",
                                icon: "success",
                                button: "success!"
                            }).then(function() {});
                        } else {
                            $("#btn_transfer_" + old_roster_id).prop('disabled', false);
                            $("#btn_transfer_" + old_roster_id).html("Transfer");
                            swal({
                                title: "Error!",
                                text: data.status,
                                icon: "error",
                                button: "error!"
                            });
                        }
                    },
                    error: function(xhr) {
                        updateCSRFToken();
                        console.log("Error: " + xhr.status + " " + xhr.statusText);
                    }
                });
            } else {
                swal("Cancelled", "Please try again", "error");
            }
        });
    });
</script>