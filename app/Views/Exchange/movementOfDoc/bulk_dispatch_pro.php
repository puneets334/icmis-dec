<form>
<?php csrf_field();
?>
    <div id="dv_content1" class="container">
        <div class="text-center">
            <?php if (count($select_rs) > 0) { ?>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th colspan="7" class="text-center">
                                RECORDS TO BE DISPATCH
                                <span id="enable-in-print">FOR <?php echo get_user_details($ucode); ?></span>
                            </th>
                        </tr>
                        <tr>
                            <th>SNo.</th>
                            <th>Document No.</th>
                            <th>Document Type</th>
                            <th class="text-center">Diary No.</th>
                            <th>Case Nos.</th>
                            <th>DA</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sno = 1;
                        foreach ($select_rs as $row) {
                        ?>
                            <tr>
                                <td class="text-center"><?php echo $sno; ?></td>
                                <td><?php echo '<span style="color:blue;">' . $row['kntgrp'] . '</span>- ' . $row['docnum'] . '/' . $row['docyear']; ?></td>
                                <td>
                                    <?php echo $row['docdesc'];
                                    if ($row['other1'] != '' || $row['other1'] != NULL) {
                                        echo ' - ' . $row['other1'];
                                    }
                                    ?>
                                </td>
                                <td class="text-center"><?php echo get_real_diaryno($row['diary_no']); ?></td>
                                <td><?php echo get_casenos_comma($row['diary_no']); ?></td>
                                <td>
                                    <?php
                                    if ($row['dacode'] == 0 || $row['dacode'] == '' || $row['dacode'] == NULL) {
                                    ?>
                                        <span class="text-danger">DA NOT FOUND</span>
                                    <?php
                                    } else {
                                        echo get_user_details($row['dacode']);
                                    }
                                    ?>
                                </td>
                                <td class="text-center">
                                    <?php
                                    if (!($row['dacode'] == 0 || $row['dacode'] == '' || $row['dacode'] == NULL)) {
                                    ?>
                                        <input type="checkbox" name="chk<?php echo $sno; ?>" id="chk<?php echo $sno; ?>" value="<?php echo $row['diary_no'] . '-' . $row['doccode'] . '-' . $row['doccode1'] . '-' . $row['docnum'] . '-' . $row['docyear'] . '-' . $row['dacode']; ?>" />
                                    <?php
                                    }
                                    ?>
                                </td>
                            </tr>
                        <?php
                            $sno++;
                        }
                        ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="7" class="text-center">
                                <button type="button" class="btn btn-primary" id="btnrece" onclick="dispatchFunction()">Dispatch</button>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            <?php
            } else {
            ?>
                <div class="alert alert-warning text-center">SORRY!!!, NO RECORD FOUND</div>
            <?php
            }
            ?>
        </div>
    </div>
</form>