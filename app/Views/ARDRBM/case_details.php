<div align="center" style="background-color:mintcream; border: 1px solid #5AFFAC;">
    <br>
    <table bgcolor="#FBFFFD" class="tbl_hr" width="90%" border="0" cellpadding="3" align="center">
        <tr>
            <td width="150px" bgcolor='#F4F5F5'>Diary No.</td>
            <td><b><span id="cs<?= $diaryno ?>"><?= get_real_diaryno($case_t) ?></span></b></td>
        </tr>
        <tr>
            <td>Case No.</td>
            <td><div width='100%'><b><?= get_case_nos($diaryno, '&nbsp;&nbsp;&nbsp;', '') ?></b></div></td>
        </tr>
        <tr>
            <td bgcolor='#F4F5F5'>Petitioner</td>
            <td><b><?= $p ?></b></td>
        </tr>
        <tr>
            <td bgcolor='#F4F5F5'>Respondent</td>
            <td><b><?= $r ?></b></td>
        </tr>
        <tr>
            <td bgcolor='#F4F5F5'>Petitioner Advocate(s)</td>
            <td><b><?= $padv ?></b></td>
        </tr>
        <tr>
            <td bgcolor='#F4F5F5'>Respondent Advocate(s)</td>
            <td><b><?= $radv ?></b></td>
        </tr>
        <?php $category = $modelIA->get_mul_category($diaryno); ?>
        <tr>
            <td bgcolor='#F4F5F5'>Case Category</td>
            <td><b><?= $category[0] ?></b></td>
        </tr>
        <tr>
            <td bgcolor='#F4F5F5'>Status</td>
            <td><b><?= $cstatus ?></b></td>
        </tr>
        <tr>
            <td bgcolor='#F4F5F5'>Last Order</td>
            <td><b><font color='blue'><?= $lastorder ?></font></b></td>
        </tr>
        <?php if ($status != "D"): ?>
            <?php $return_bfnbf = $modelIA->getBfNbf($diaryno); ?>
            <?php $t_return_bfnbf = explode('^|^', $return_bfnbf); ?>

            <?php if (!empty($t_return_bfnbf[0])): ?>
                <tr>
                    <td bgcolor='#F4F5F5'>LIST BEFORE</td>
                    <td><font color='green'><b><?= $t_return_bfnbf[0] ?></b></font></td>
                </tr>
            <?php endif; ?>
            <?php if (!empty($t_return_bfnbf[1])): ?>
                <tr>
                    <td bgcolor='#F4F5F5'>NOT LIST BEFORE</td>
                    <td><font color='red'><b><?= $t_return_bfnbf[1] ?></b></font></td>
                </tr>
            <?php endif; ?>
        <?php endif; ?>
    </table>
    <br>
</div>
