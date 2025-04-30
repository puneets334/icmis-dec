<style>
      table.dataTable>thead .sorting,
    table.dataTable>thead {
        background-color: #0d48be !important;
        color: #fff !important;
        white-space: nowrap;
    }

    table.dataTable>thead .sorting_disabled,
    table.dataTable>thead {
        background-color: #0d48be !important;
        color: #fff !important;
        white-space: nowrap;
    }
    table tfoot tr th {
        background-color: #0d48be !important;
        color: #fff !important;
        white-space: nowrap;
    }
    
.custom-table thead th{background-color: #0d48be !important;
        color: #fff !important;
        white-space: nowrap;}
.custom-table thead th:first-child,.custom-table thead th:last-child,.custom-table tbody td:last-child
{border-radius: 0px;border-left: #999 1px solid; }
.custom-table thead th,.custom-table tbody td,.custom-table tbody tr th { 
    border-right: #999 1px solid;
}
.custom-table tbody td:first-child,.custom-table tbody tr th:first-child {
    border-left: #999 1px solid;
    border-radius: 0px;
}   
/* .custom-table tbody td:last-child,.custom-table tbody tr th:last-child {
    border-bottom: #000 1px solid;
}   */
.custom-table tbody tr:last-child th{
    border-bottom: #999 1px solid;

}
</style>
<div id="prnnt" style="font-size:11px;">
    <table border="0" width="100%" style="font-size:11px; text-align: left; background: #ffffff;" cellspacing=0>
        <thead>
            <tr>
                <th colspan="4" style="text-align: center;"><b>
                    SUPREME COURT OF INDIA
                    <br><br>
                    CATEGORY WISE REPORT
                    <BR>CASE LOAD OF PRE-ADMISSION CASES TO BE LISTED BEFORE THE HON'BLE COURTS THE DAILY CAUSE LIST FOR
                    DATED : <?php echo date('d-m-Y', strtotime($_POST['list_dt'])); ?>
                    <br>BREAK-UP OF CASES INCLUDED IN LIST AND NOT INCLUDED IN THE LIST BEING SURPLUS MATTERS IS AS
                    FOLLOWS :</b>

                </th>
            </tr>
        </thead>
    </table>
    <?php
    if (count($result_array) > 0) {
    ?>
    <table class="table table-striped custom-table">
        <thead>
            <tr>
                <th rowspan="2">SNo</th>
                <th rowspan="2">HEAD</th>
                <th rowspan="2">Cat. Allotted to No. of Court</th>
                <th colspan="4">LISTED</th>
                <th colspan="4">NOT LISTED</th>
                <th>TOTAL</th>
            </tr>
            <tr>
                <th>Court Dt</th>
                <th>Comp Gen Fix Dt as per scheme</th>
                <th>Comp Gen</th>
                <th>TOTAL</th>
                <th>Court Dt</th>
                <th>Comp Gen Fix Dt as per scheme</th>
                <th>Comp Gen</th>
                <th>TOTAL</th>
                <th>GRAND TOTAL</th>
            </tr>
            <tr>
                <th>1</th>
                <th>2</th>
                <th>3</th>
                <th>4</th>
                <th>5</th>
                <th>6</th>
                <th>7</th>
                <th>8</th>
                <th>9</th>
                <th>10</th>
                <th>11</th>
                <th>12</th>
            </tr>
        </thead>
        <?php
            $sno = 1;
            $t_fd_list = 0;
            $t_imp_ia_list = 0;
            $t_oth_list = 0;
            $t_listed = 0;
            $t_fd_not_listed = 0;
            $t_imp_ia_not_listed = 0;
            $t_oth_not_listed = 0;
            $t_not_listed = 0;
            $gd = 0;
            foreach ($result_array as $row) {
                $sno1 = $sno % 2;
                if ($sno == 1 or $sno == 2) { ?>
        <tr>
            <?php } else if ($sno >= 3 and $sno <= 10) {
                    ?>
        <tr>
            <?php } else { ?>
        <tr>
            <?php
                }
                    ?>
            <td><?php echo $sno++; ?></td>
            <td><?php echo $row['sub_name1']; ?></td>
            <td><?php echo $row['nos_court']; ?></td>
            <td><?php $t_fd_list += $row['fd_list'];  echo $row['fd_list']; ?> </td>
            <td> <?php $t_imp_ia_list += $row['imp_ia_list']; echo $row['imp_ia_list']; ?></td>
            <td><?php $t_oth_list += $row['oth_list']; echo $row['oth_list']; ?></td>
            <td><?php $t_listed += $row['listed']; echo $row['listed']; ?></td>
            <td> <?php $t_fd_not_listed += $row['fd_not_listed']; echo $row['fd_not_listed']; ?></td>
            <td><?php $t_imp_ia_not_listed += $row['imp_ia_not_listed']; echo $row['imp_ia_not_listed']; ?></td>
            <td><?php $t_oth_not_listed += $row['oth_not_listed']; echo $row['oth_not_listed']; ?></td>
            <td><?php $t_not_listed += $row['not_listed']; echo $row['not_listed']; ?></td>
            <td><?php $gd += $row['listed'] + $row['not_listed']; echo $row['listed'] + $row['not_listed']; ?></td>
        </tr>
        <?php

            }
                ?>
        <tr>
            <td colspan="3" style="background-color: #0d48be !important;color: #fff !important;white-space: nowrap;"> TOTAL </td>
            <td style="background-color: #0d48be !important;color: #fff !important;white-space: nowrap;"><?php echo $t_fd_list; ?></td>
            <td style="background-color: #0d48be !important;color: #fff !important;white-space: nowrap;"><?php echo $t_imp_ia_list; ?></td>
            <td style="background-color: #0d48be !important;color: #fff !important;white-space: nowrap;"><?php echo $t_oth_list; ?></td>
            <td style="background-color: #0d48be !important;color: #fff !important;white-space: nowrap;"><?php echo $t_listed; ?></td>
            <td style="background-color: #0d48be !important;color: #fff !important;white-space: nowrap;"><?php echo $t_fd_not_listed; ?></td>
            <td style="background-color: #0d48be !important;color: #fff !important;white-space: nowrap;"><?php echo $t_imp_ia_not_listed; ?></td>
            <td style="background-color: #0d48be !important;color: #fff !important;white-space: nowrap;"><?php echo $t_oth_not_listed; ?></td>
            <td style="background-color: #0d48be !important;color: #fff !important;white-space: nowrap;"><?php echo $t_not_listed; ?></td>
            <td style="background-color: #0d48be !important;color: #fff !important;white-space: nowrap;"><?php echo $gd; ?></td>
        </tr>
    </table>
    <br />
    <br>
    <?php
    } else {
        echo "No Records Found";
    }
    ?>
    <br>
</div>
<input name="prnnt1" type="button" id="prnnt1" value="Print">