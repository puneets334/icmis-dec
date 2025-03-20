<div id="prnnt">
    <?= isset($content) ? $content : ''; ?>
</div>
<?php
if (!empty($content)) {
?>
    <span id="toggle_hw"></span>
    <input name="prnnt1" type="button" id="prnnt1" value="Print">
<?php } ?>
<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?= $error; ?></div>
<?php endif; ?>