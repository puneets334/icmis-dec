<style>
span {
    color: #0d48be;
    cursor: pointer;
}
.custom-table thead th{background:none;}
.custom-table thead th:first-child,.custom-table thead th:last-child,.custom-table tbody td:last-child
{border-radius: 0px;}
.custom-table thead th,.custom-table tbody td,.custom-table tbody tr th { 
    border-right: #999 1px solid;
}
</style>

<div class="table-responsive">
    <?php
    if (count($data) > 0) {
    ?>

        <table class="table table-striped custom-table" id="example1">
            <!--<tr><th colspan="9">CASES HAVING NO DA ALLOCATION</th></tr>-->
            <thead>
                <tr>
                    <th rowspan="2">SNo.</th>
                    <th rowspan="2">Section</th>
                    <th rowspan="2">Name</th>
                    <th rowspan="2">Designation</th>
                    <th rowspan="2">Total Cases</th>
                    <?php
                    if ($usertype == 1 or $usertype == 14 or $usertype == 9 or $usertype == 6 or $usertype == 4) {
                    ?>
                        <th colspan="2">Branch Officer</th>
                    <?php }
                    if ($usertype == 1 or $usertype == 9 or $usertype == 6 or $usertype == 4) {
                    ?>
                        <th colspan="2">Assistant Registrar</th>
                    <?php }
                    if ($usertype == 1 or $usertype == 6 or $usertype == 4) {
                    ?>
                        <th colspan="2">Additional / Deputy Registrar</th>
                    <?php }
                    /*        if($usertype == 1 OR $usertype == 4){
                                */ ?><!--
                <th colspan="2">Additional Registrar</th>
        --><?php /*} */ ?>
                </tr>
                <tr>
                    <?php
                    if ($usertype == 1 or $usertype == 14 or $usertype == 9 or $usertype == 6 or $usertype == 4) {
                    ?>
                        <th>Verified</th>
                        <th>Not Verified</th>
                    <?php }
                    if ($usertype == 1 or $usertype == 9 or $usertype == 6 or $usertype == 4) {
                    ?>
                        <th>Verified</th>
                        <th>Not Verified</th>
                    <?php }
                    if ($usertype == 1 or $usertype == 6 or $usertype == 4) {
                    ?>
                        <th>Verified</th>
                        <th>Not Verified</th>
                    <?php }
                    /*                if($usertype == 1 OR $usertype == 4){
            */ ?><!--
                <th>Verified</th>
                <th>Not Verified</th>
            --><?php /*} */ ?>
                </tr>
            </thead>
            <tbody>
                <?php
                $sno = 1;
                foreach ($data as $row) {
                ?>
                    <tr>
                        <th><?php echo $sno; ?></th>
                        <td><?php echo $row['section_name']; ?></td>
                        <td><?php echo "<span id='name_$row[usercode]' style='color:#000; cursor:none;'>" . $row['name'] . '/' . $row['empid'] . "</span>"; ?></td>
                        <td><?php echo $row['type_name']; ?></td>
                        <td><?php echo "<span style='cursor:pointer' id='dacase_$row[usercode]_1'>" . $row['da_case'] . "</span>"; ?></td>
                        <?php
                        if ($usertype == 1 or $usertype == 14 or $usertype == 9 or $usertype == 6 or $usertype == 4) {
                        ?>
                            <td><?php echo "<span style='cursor:pointer' id='dacase_$row[usercode]_2'>" . $row['bo_v'] . "</span>"; ?></td>
                            <td><?php echo "<span style='cursor:pointer' id='dacase_$row[usercode]_3'>" . $row['bo_nv'] . "</span>"; ?></td>
                        <?php }
                        if ($usertype == 1 or $usertype == 9 or $usertype == 6 or $usertype == 4) {
                        ?>
                            <td><?php echo "<span style='cursor:pointer' id='dacase_$row[usercode]_4'>" . $row['ar_v'] . "</span>"; ?></td>
                            <td><?php echo "<span style='cursor:pointer' id='dacase_$row[usercode]_5'>" . $row['ar_nv'] . "</span>"; ?></td>
                        <?php }
                        if ($usertype == 1 or $usertype == 6 or $usertype == 4) {
                        ?>
                            <td><?php echo "<span style='cursor:pointer' id='dacase_$row[usercode]_6'>" . $row['dy_v'] . "</span>"; ?></td>
                            <td><?php echo "<span style='cursor:pointer' id='dacase_$row[usercode]_7'>" . $row['dy_nv'] . "</span>"; ?></td>
                        <?php }
                        /*                    if($usertype == 1 OR $usertype == 4){
                                            */ ?><!--
                    <td><?php /*echo "<span style='cursor:pointer' id='dacase_$row[usercode]_8'>" . $row['adr_v'] . "</span>"; */ ?></td>
                    <td><?php /*echo "<span style='cursor:pointer' id='dacase_$row[usercode]_9'>" . $row['adr_nv'] . "</span>"; */ ?></td>
                    --><?php /*} */ ?>
                    </tr>
                <?php
                    $sno++;
                }
                ?>
            </tbody>
        </table>
    <?php
    } else {
    ?>
        <div style="text-align:center;color:red">SORRY, NO RECORD FOUND!!!</div>
    <?php
    } ?>
</div>


<div id="dv_sh_hd" style="display: none;position: fixed;top: 0;width: 100%;height: 100%;background-color: black;opacity: 0.6;left: 0;overflow: hidden;z-index: 103">
    &nbsp;
</div>


<div id="dv_fixedFor_P" style="text-align: center;position: fixed;top:0;display: none;
	left:0;
	width:100%;
	height:100%;z-index: 105">
    <span id="sp_close" style="display: none;text-align: right;cursor: pointer" onclick="closeData()"><b><img src="<?php echo base_url('images/close_btn.png') ?>" /></b></span>
    <div style="width: auto;background-color: white;overflow: scroll;height: 500px;margin-left: 50px;margin-right: 50px;margin-bottom: 25px;margin-top: 1px;word-wrap: break-word;" id="ggg" onkeypress="return  nb(event)">
    </div>
</div>
<script>
    /* $("#example1").DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
        "dom": 'Bfrtip',
        "bProcessing": true,
        "buttons": ["excel", "pdf"]
    }); */
</script>