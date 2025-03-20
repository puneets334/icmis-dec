<div style="text-align:center;">
    <h3><?= esc($case['pet_name']) ?> Vs. <?= esc($case['res_name']) ?></h3>
    <p>Weekly Date From <?= date('d-m-Y', strtotime($case['from_dt'])) ?> To <?= date('d-m-Y', strtotime($case['to_dt'])) ?></p>

    <?php if ($chkDropNote == 0): ?>
        <p style="color:red;">Do Not Drop, Advance List Published, Drop Note Required before Case Drop</p>
        <form method="POST" action="<?= base_url('Listing/DropNoteAdvance/field_case_drop') ?>">
            <input type="hidden" name="next_dt" value="<?= esc($case['next_dt']) ?>">
            <input type="hidden" name="from_dt" value="<?= esc($case['from_dt']) ?>">
            <input type="hidden" name="to_dt" value="<?= esc($case['to_dt']) ?>">
            <input type="hidden" name="drop_diary" value="<?= esc($dno) ?>">
            <input type="text" name="drop_rmk" maxlength="75" placeholder="Enter Drop Remark (Max 75 characters)">
           
            <input name="drop_btn_note" type="button" id="drop_btn_note" value="Click to Drop">
        </form>
    <?php else: ?>
        <input name="drop_btn" type="button" id="drop_btn" value="Click to Drop"> 
    <input name="drop_diary" type="hidden" id="drop_diary" value="<?= esc($dno) ?>" >
        <input name="next_dt" type="hidden" id="next_dt" value="<?= esc($case['next_dt']) ?>" >
        <input type="hidden" size="10" name='from_dt' id='from_dt' value="<?= esc($case['from_dt']) ?>" readonly />
        <input type="hidden" size="10" name='to_dt' id='to_dt' value="<?= esc($case['to_dt']) ?>"  readonly />
    <?php endif; ?>
</div>
