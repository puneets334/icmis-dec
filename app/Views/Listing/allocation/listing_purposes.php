<?php if (!empty($purposes)): ?>
    <?php if ($main_supp == 1): ?>
        <option value="all" selected="selected">-ALL-</option>
    <?php endif; ?>
    <?php foreach ($purposes as $purpose): ?>
        <option value="<?= esc($purpose['code']); ?>">
            <?= esc($purpose['code']) . '. ' . esc($purpose['purpose']); ?>
        </option>
    <?php endforeach; ?>
<?php else: ?>
    <option value="">No purposes found</option>
<?php endif; ?>
