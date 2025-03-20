<div align="right"><input name="print1" type="button" id="print1" value="Print"></div>
<div id="dv_sh_hd" style="display: none;position: fixed;top: 0;width: 100%;height: 100%;background-color: black;opacity: 0.6;left: 0;overflow: hidden;z-index: 103">
    &nbsp;
</div>


<div id="dv_fixedFor_P" style="text-align: center;position: fixed;top:0;display: none;
	left:0;
	width:100%;
	height:100%;z-index: 105">
    <span id="sp_close" style="display: none;text-align: right;cursor: pointer" onclick="closeData()"><b><img src="../images/close_btn.png" /></b></span>
    <div style="width: auto;background-color: white;overflow: scroll;height: 500px;margin-left: 50px;margin-right: 50px;margin-bottom: 25px;margin-top: 1px;word-wrap: break-word;" id="ggg" onkeypress="return  nb(event)">
    </div>
</div>
</div>
<div id="printDiv1" align="center">
    <?php
    $srno = 1;
    $total_filed = 0;
    $total_done = 0;
    $total_pending = 0;
    ?>

    <h2 style="text-align: center;text-transform: capitalize;color: blue;"> Fresh Scrutiny Matters between <?php echo $dateFrom ?> and <?php echo $dateTo ?><br>

    </h2>

    <table class="table table-striped custom-table">
        <thead>
            <tr bgcolor="#dcdcdc" class="inner-wrap">
                <th style="text-align: center;">Sr.No.</th>
                <th>Name</th>
                <th>Emp. No.</th>
                <th>Designation</th>
                <th>Total Matters</th>
                <th>Completed</th>
                <th>Pending</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (!empty($result_array)) {
                if (count($result_array) > 0) {
                    foreach ($result_array as $row) {
            ?>
                        <tr bgcolor="white" class="inner-wrap">
                            <td style="text-align: center;"><?php echo $srno ?></td>
                            <td><?php echo $row['name'] ?></td>
                            <td><?php echo $row['empid'] ?></td>
                            <td><?php echo $row['type_name'] ?></td>
                            <td style="text-align: center;">
                                <span id="spallot_<?php echo $row['empid']; ?>" class="cl_hover" onclick="get_rec(this.id)">
                                    <?php echo $row['total'] ?>
                                </span>
                            </td>
                            <td style="text-align: center;">
                                <span id="spcomp_<?php echo $row['empid']; ?>" class="cl_hover" onclick="get_rec(this.id)">
                                    <?php echo $row['completed'] ?>
                                </span>
                            </td>
                            <td style="text-align: center;">
                                <span id="spnotcomp_<?php echo $row['empid']; ?>" class="cl_hover" onclick="get_rec(this.id)">
                                    <?php echo $row['pending']; ?>
                                </span>
                            </td>

                        </tr>
            <?php $srno++;
                        $total_filed = $total_filed + $row['total'];
                        $total_done = $total_done + $row['completed'];
                        $total_pending = $total_pending + $row['pending'];
                    }
                }
            }
            ?>
            <tr style="text-align: center;">
                <td colspan="4"><b>Total Fresh Matters</b></td>
                <td> <b><span id="sptotal_total" class="cl_hover" onclick="get_rec(this.id)"><?php echo $total_filed; ?></span></b></td>
                <td><b><span id="spcomplete_total" class="cl_hover" onclick="get_rec(this.id)"><?php echo $total_done; ?></span></b></td>
                <td><b><span id="sppend_total" class="cl_hover" onclick="get_rec(this.id)"><?php echo $total_pending ?></span></b></td>
            </tr>
        </tbody>
    </table>
</div>
<br>
<br>