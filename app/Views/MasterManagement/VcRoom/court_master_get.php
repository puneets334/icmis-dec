<?= view('header') ?>

<div id="print_area" class="col-12 m-0 p-0">
    <h3 id="title">Court Listings</h3>
    <div class="box box-primary" id="tachelist">
        <div class="box-header ptbnull">
            <h3 class="box-title titlefix">Court Listings [List Date: <?= esc($next_dt) ?>]</h3>
        </div>
        <div class="box-body">
            <div class="table-responsive mailbox-messages">
                <table class="table table-striped table-bordered table-hover" id="example">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Court No.</th>
                            <th>Bench</th>
                            <th>Court Details</th>
                            <th>Room URL</th>
                            <th>Item Number(s)</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($data)): ?>
                            <?php $srno = 1; ?>
                            <?php foreach ($data as $row): ?>
                                <tr>
                                    <td><?= $srno++ ?></td>
                                    <td><?= getCourtNo($row['courtno']) ?></td>
                                    <td><?= nl2br(esc($row['judge_name'])) ?></td>
                                    <td><?= getCourtDetails($row) ?></td>
                                    <td>
                                        <?= esc($row['vc_url']) ?>
                                        <input type="hidden" class="form-control vc_url" data-roster_id="<?= esc($row['roster_id']) ?>" value="<?= esc($row['vc_url']) ?>">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control vc_item" data-roster_id="<?= esc($row['roster_id']) ?>" value="" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');">
                                    </td>
                                    <td class="action_save_sent" data-roster_id="<?= esc($row['roster_id']); ?>">
                                        <button type="button" class="btn btn-info open-modal" data-court_id="<?= esc($row['roster_id']); ?>">View Details</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center">No Records Found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
function getCourtNo($courtno) {
    if ($courtno > 60) return 'R-VC ' . ($courtno - 60);
    if ($courtno > 30) return 'VC ' . ($courtno - 30);
    if ($courtno > 20) return 'R ' . ($courtno - 20);
    return 'C ' . $courtno;
}

function getCourtDetails($row) {
    $details = '';
    if (!empty($row['frm_time'])) $details .= 'Time: ' . $row['frm_time'] . '<br>';
    $details .= ($row['m_f'] === 'M') ? "Misc. List " : "Regular List ";
    if ($row['board_type_mb'] === 'J') $details .= "<br>Before Court ";
    if ($row['board_type_mb'] === 'S') $details .= "<br>Before Single Judge ";
    if ($row['board_type_mb'] === 'C') $details .= "<br>Before Chamber ";
    if ($row['board_type_mb'] === 'R') $details .= "<br>Before Registrar Court ";
    return $details;
}
?>

<!-- Modal -->
<div class="modal fade" id="courtModal" tabindex="-1" role="dialog" aria-labelledby="courtModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="courtModalLabel">Court Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modalContent">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).on('click', '.open-modal', function() {
        var courtId = $(this).data('court_id');

        $.ajax({
            url: '<?= site_url("court/loadModal") ?>', 
            type: 'POST',
            data: { court_id: courtId },
            success: function(data) {
                $('#modalContent').html(data);
                $('#courtModal').modal('show');
            },
            error: function() {
                alert('Error loading court details.');
            }
        });
    });
</script>