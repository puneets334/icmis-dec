<fieldset>
    <legend style="text-align:center;color:#4141E0; font-weight:bold;">TRANSFER FROM</legend>
    <?php if (!empty($roster_judges)): ?>
        <table border="0" width="100%" style="text-align: left; background:#f6fbf0;" cellspacing="1">
            <tr>
                <td>
                    <select name="croam_from" id="coram_from">
                        <option value="-1">Select</option>
                        <?php foreach ($roster_judges as $row): ?>
                            <option value="<?= esc($row['jcd']) . '|' . esc($row['id']) ?>">
                                <?= esc($row['courtno']) . " -> " . str_replace(",", " & ", esc($row['jnm'])) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
        </table>
    <?php else: ?>
        <center>No Records Found</center>
    <?php endif; ?>
</fieldset>
