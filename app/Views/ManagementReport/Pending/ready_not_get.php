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

<input name="prnnt1" type="button" id="prnnt1" value="Print">
<div id="prnnt" style="text-align: center;">
<table border="0" width="100%" style="font-size:11px; text-align: left; background: #ffffff;" cellspacing=0>
        <thead>
            <tr>
                <th colspan="4" style="text-align: center;"><b>
                    SUPREME COURT OF INDIA
                    <br><br>
                    Ready / Not Ready Matters
                  </b>

                </th>
            </tr>
        </thead>
    </table>
<?php
if (count($back_date) > 0) {
?>

    <h3 style="text-align:left;">BACK DATE MATTERS</h3>
    <table id="tab" class="table table-striped custom-table">
        <thead>
                <tr>
                    <th rowspan="2" width="5%" style="text-align: center; font-weight: bold;">SNo</th>
                    <th rowspan="2" width="15%" style="text-align: center; font-weight: bold;">Date</th>
                    <th colspan="3" width="20%" style="text-align: center; font-weight: bold;">Court</th>
                    <th colspan="3" width="20%" style="text-align: center; font-weight: bold;">Chamber</th>
                    <th colspan="3" width="20%" style="text-align: center; font-weight: bold;">Registrar</th>
                    <th colspan="3" width="20%" style="text-align: center; font-weight: bold;">Total</th>
                </tr>
                <tr>
                    <th style="text-align: center; font-weight: bold;">R</th>
                    <th style="text-align: center; font-weight: bold;">NR</th>
                    <th style="text-align: center; font-weight: bold;">Total</th>
                    <th style="text-align: center; font-weight: bold;">R</th>
                    <th style="text-align: center; font-weight: bold;">NR</th>
                    <th style="text-align: center; font-weight: bold;">Total</th>
                    <th style="text-align: center; font-weight: bold;">R</th>
                    <th style="text-align: center; font-weight: bold;">NR</th>
                    <th style="text-align: center; font-weight: bold;">Total</th>
                    <th style="text-align: center; font-weight: bold;">R</th>
                    <th style="text-align: center; font-weight: bold;">NR</th>
                    <th style="text-align: center; font-weight: bold;">Total</th>
                </tr>

        </thead>
        <tbody>
            <?php 
            $sno = 1;
            $bk_tot_court_r = 0;
            $bk_tot_court_nr = 0;
            $bk_tot_court_tot = 0;

            $bk_tot_chamber_r = 0;
            $bk_tot_chamber_nr = 0;
            $bk_tot_chamber_tot = 0;

            $bk_tot_registrar_r = 0;
            $bk_tot_registrar_nr = 0;
            $bk_tot_registrar_tot = 0;

            $bk_tot_ready_r = 0;
            $bk_tot_ready_nr = 0;
            $bk_tot_ready_tot = 0;
            foreach ($back_date as $row) {
                $sno1 = $sno % 2;
                
                if($row['next_dt'] != null){
                    $bk_tot_court_r = $bk_tot_court_r + $row['court_r'];
                    $bk_tot_court_nr = $bk_tot_court_nr + $row['court_nr'];
                    $bk_tot_court_tot = $bk_tot_court_tot + $row['court'];

                    $bk_tot_chamber_r = $bk_tot_chamber_r + $row['chamber_r'];
                    $bk_tot_chamber_nr = $bk_tot_chamber_nr + $row['chamber_nr'];
                    $bk_tot_chamber_tot = $bk_tot_chamber_tot + $row['chamber'];

                    $bk_tot_registrar_r = $bk_tot_registrar_r + $row['reg_r'];
                    $bk_tot_registrar_nr = $bk_tot_registrar_nr + $row['reg_nr'];
                    $bk_tot_registrar_tot = $bk_tot_registrar_tot + $row['reg'];

                    $bk_tot_ready_r = $bk_tot_ready_r + $row['ready'];
                    $bk_tot_ready_nr = $bk_tot_ready_nr + $row['not_ready'];
                    $bk_tot_ready_tot = $bk_tot_ready_tot + $row['total'];
                    ?>
                    
                    <tr>
                    <td align="center" style='vertical-align: top;'><?php echo $sno++; ?></td>
                    <td align="center" style='text-align: left; vertical-align: top;'><?php echo date('d-m-Y', strtotime($row['next_dt'])); ?></td>
                    <td align="center" style='vertical-align: top;'>
                        <a href='<?php echo "not_ready_get_detail?list_dt=$row[next_dt]&flag=b&ltype=court_r&ct=$connt'";?> target='_blank'>
                        <?php  echo $row['court_r']; ?>
                        </a>
                    </td>
                    <td align="center" style='vertical-align: top;'>
                        <a href='<?php echo "not_ready_get_detail?list_dt=$row[next_dt]&flag=b&ltype=court_nr&ct=$connt'";?> target='_blank'>
                        <?php  echo $row['court_nr']; ?>
                        </a>
                    </td>
                    <td align="center" style='vertical-align: top;'>
                        <a href='<?php echo "not_ready_get_detail?list_dt=$row[next_dt]&flag=b&ltype=court&ct=$connt'";?> target='_blank'>
                        <?php  echo $row['court']; ?>
                        </a>
                    </td>
                    <td align="center" style='vertical-align: top;'>
                        <a href='<?php echo "not_ready_get_detail?list_dt=$row[next_dt]&flag=b&ltype=chamber_r&ct=$connt'";?> target='_blank'>
                        <?php  echo $row['chamber_r']; ?>
                        </a>

                    </td>
                    <td align="center" style='vertical-align: top;'>
                        <a href='<?php echo "not_ready_get_detail?list_dt=$row[next_dt]&flag=b&ltype=chamber_nr&ct=$connt'";?> target='_blank'>
                        <?php  echo $row['chamber_nr']; ?>
                        </a>

                    </td>
                    <td align="center" style='vertical-align: top;'>
                        <a href='<?php echo "not_ready_get_detail?list_dt=$row[next_dt]&flag=b&ltype=chamber&ct=$connt'";?> target='_blank'>
                        <?php  echo $row['chamber']; ?>
                        </a>

                    </td>
                    <td align="center" style='vertical-align: top;'>
                        <a href='<?php echo "not_ready_get_detail?list_dt=$row[next_dt]&flag=b&ltype=reg_r&ct=$connt'";?> target='_blank'>
                        <?php  echo $row['reg_r']; ?>
                        </a>

                    </td>
                    <td align="center" style='vertical-align: top;'>
                        <a href='<?php echo "not_ready_get_detail?list_dt=$row[next_dt]&flag=b&ltype=reg_nr&ct=$connt'";?> target='_blank'>
                        <?php  echo $row['reg_nr']; ?>
                        </a>

                    </td>
                    <td align="center" style='vertical-align: top;'>
                        <a href='<?php echo "not_ready_get_detail?list_dt=$row[next_dt]&flag=b&ltype=reg&ct=$connt'";?> target='_blank'>
                        <?php  echo $row['reg']; ?>
                        </a>

                    </td>
                    <td align="center" style='vertical-align: top;'>
                        <a href='<?php echo "not_ready_get_detail?list_dt=$row[next_dt]&flag=b&ltype=ready&ct=$connt'";?> target='_blank'>
                        <?php  echo $row['ready']; ?>
                        </a>

                    </td>
                    <td align="center" style='vertical-align: top;'>
                        <a href='<?php echo "not_ready_get_detail?list_dt=$row[next_dt]&flag=b&ltype=not_ready&ct=$connt'";?> target='_blank'>
                        <?php  echo $row['not_ready']; ?>
                        </a>

                    </td>
                    <td align="center" style='vertical-align: top;'>
                        <a href='<?php echo "not_ready_get_detail?list_dt=$row[next_dt]&flag=b&ltype=Total&ct=$connt'";?> target='_blank'>
                        <?php  echo $row['total']; ?>
                        </a>

                    </td>
                    </tr>
                <?php }
                //else{
                   
                    ?>
                    

                    <?php
                //}
                }//END OF WHILE LOOP
                ?>
               
                <tr ><td style="background-color: #0d48be !important;color: #fff !important;white-space: nowrap;" colspan="2" align="right"> TOTAL </td>
                    <td align="center" style='vertical-align: top;background-color: #0d48be !important;white-space: nowrap;'>
                            <a style="color: #fff !important;" href='<?php echo "not_ready_get_detail?list_dt=0&flag=b&ltype=court_r&ct=$connt'";?> target='_blank'>
                            <?php  echo $bk_tot_court_r; ?>
                            </a>
                        </td>
                        <td align="center" style='vertical-align: top;background-color: #0d48be !important;white-space: nowrap;'>
                            <a style="color: #fff !important;" href='<?php echo "not_ready_get_detail?list_dt=0&flag=b&ltype=court_nr&ct=$connt'";?> target='_blank'>
                            <?php  echo $bk_tot_court_nr; ?>
                            </a>
                        </td>
                        <td align="center" style='vertical-align: top;background-color: #0d48be !important;white-space: nowrap;'>
                            <a style="color: #fff !important;" href='<?php echo "not_ready_get_detailp?list_dt=0&flag=b&ltype=court&ct=$connt'";?> target='_blank'>
                            <?php  echo $bk_tot_court_tot; ?>
                            </a>
                        </td>
                        <td align="center" style='vertical-align: top;background-color: #0d48be !important;white-space: nowrap;'>
                            <a style="color: #fff !important;" href='<?php echo "not_ready_get_detail?list_dt=0&flag=b&ltype=chamber_r&ct=$connt'";?> target='_blank'>
                            <?php  echo $bk_tot_chamber_r; ?>
                            </a>

                        </td>
                        <td align="center" style='vertical-align: top;background-color: #0d48be !important;white-space: nowrap;'>
                            <a style="color: #fff !important;" href='<?php echo "not_ready_get_detail?list_dt=0&flag=b&ltype=chamber_nr&ct=$connt'";?> target='_blank'>
                            <?php  echo $bk_tot_chamber_nr; ?>
                            </a>

                        </td>
                        <td align="center" style='vertical-align: top;background-color: #0d48be !important;white-space: nowrap;'>
                            <a style="color: #fff !important;" href='<?php echo "not_ready_get_detail?list_dt=0&flag=b&ltype=chamber&ct=$connt'";?> target='_blank'>
                            <?php  echo $bk_tot_chamber_tot; ?>
                            </a>

                        </td>
                        <td align="center" style='vertical-align: top;background-color: #0d48be !important;white-space: nowrap;'>
                            <a style="color: #fff !important;" href='<?php echo "not_ready_get_detail?list_dt=0&flag=b&ltype=reg_r&ct=$connt'";?> target='_blank'>
                            <?php  echo $bk_tot_registrar_r; ?>
                            </a>

                        </td>
                        <td align="center" style='vertical-align: top;background-color: #0d48be !important;white-space: nowrap;'>
                            <a style="color: #fff !important;" href='<?php echo "not_ready_get_detail?list_dt=0&flag=b&ltype=reg_nr&ct=$connt'";?> target='_blank'>
                            <?php  echo $bk_tot_registrar_nr; ?>
                            </a>

                        </td>
                        <td align="center" style='vertical-align: top;background-color: #0d48be !important;white-space: nowrap;'>
                            <a style="color: #fff !important;" href='<?php echo "not_ready_get_detail?list_dt=0&flag=b&ltype=reg&ct=$connt'";?> target='_blank'>
                            <?php  echo $bk_tot_registrar_tot; ?>
                            </a>

                        </td>
                        <td align="center" style='vertical-align: top;background-color: #0d48be !important;white-space: nowrap;'>
                            <a style="color: #fff !important;" href='<?php echo "not_ready_get_detail?list_dt=0&flag=b&ltype=ready&ct=$connt'";?> target='_blank'>
                            <?php  echo  $bk_tot_ready_r; ?>
                            </a>

                        </td>
                        <td align="center" style='vertical-align: top;background-color: #0d48be !important;white-space: nowrap;'>
                            <a style="color: #fff !important;" href='<?php echo "not_ready_get_detail?list_dt=0&flag=b&ltype=not_ready&ct=$connt'";?> target='_blank'>
                            <?php  echo $bk_tot_ready_nr; ?>
                            </a>

                        </td>
                        <td align="center" style='vertical-align: top;background-color: #0d48be !important;white-space: nowrap;'>
                            <a style="color: #fff !important;" href='<?php echo "not_ready_get_detail?list_dt=0&flag=b&ltype=Total&ct=$connt'";?> target='_blank'>
                            <?php  echo $bk_tot_ready_tot; ?>
                            </a>
                        </td>
                    </tr>


        </tbody>
    </table>
<?php
} else {
    echo "No Back Date Records Found";
}
?>
<?php
if (count($future_date) > 0) {
?>
    <h3 style="text-align:center;">FUTURE DATE MATTERS</h3>
    <table id="tab" class="table table-striped custom-table">
        <thead>
        <tr>
    <th rowspan="2" width="5%" style="text-align: center; font-weight: bold;">SNo</th>
    <th rowspan="2" width="15%" style="text-align: center; font-weight: bold;">Date</th>
    <th colspan="3" width="20%" style="text-align: center; font-weight: bold;">Court</th>
    <th colspan="3" width="20%" style="text-align: center; font-weight: bold;">Chamber</th>
    <th colspan="3" width="20%" style="text-align: center; font-weight: bold;">Registrar</th>
    <th colspan="3" width="20%" style="text-align: center; font-weight: bold;">Total</th>
</tr>
<tr>


    <th style="text-align: center; font-weight: bold;">R</th>
    <th style="text-align: center; font-weight: bold;">NR</th>
    <th style="text-align: center; font-weight: bold;">Total</th>
    <th style="text-align: center; font-weight: bold;">R</th>
    <th style="text-align: center; font-weight: bold;">NR</th>
    <th style="text-align: center; font-weight: bold;">Total</th>
    <th style="text-align: center; font-weight: bold;">R</th>
    <th style="text-align: center; font-weight: bold;">NR</th>
    <th style="text-align: center; font-weight: bold;">Total</th>
    <th style="text-align: center; font-weight: bold;">R</th>
    <th style="text-align: center; font-weight: bold;">NR</th>
    <th style="text-align: center; font-weight: bold;">Total</th>
</tr>
        </thead>
        <tbody>
            <?php 
            $sno = 1;
            $fr_tot_court_r = 0;
            $fr_tot_court_nr = 0;
            $fr_tot_court_tot = 0;

            $fr_tot_chamber_r = 0;
            $fr_tot_chamber_nr = 0;
            $fr_tot_chamber_tot = 0;

            $fr_tot_registrar_r = 0;
            $fr_tot_registrar_nr = 0;
            $fr_tot_registrar_tot = 0;

            $fr_tot_ready_r = 0;
            $fr_tot_ready_nr = 0;
            $fr_tot_ready_tot = 0;
            foreach ($future_date as $row) {
                $sno1 = $sno % 2;
                $fr_tot_court_r = $fr_tot_court_r + $row['court_r'];
                $fr_tot_court_nr = $fr_tot_court_nr + $row['court_nr'];
                $fr_tot_court_tot = $fr_tot_court_tot + $row['court'];

                $fr_tot_chamber_r = $fr_tot_chamber_r + $row['chamber_r'];
                $fr_tot_chamber_nr = $fr_tot_chamber_nr + $row['chamber_nr'];
                $fr_tot_chamber_tot = $fr_tot_chamber_tot + $row['chamber'];

                $fr_tot_registrar_r = $fr_tot_registrar_r + $row['reg_r'];
                $fr_tot_registrar_nr = $fr_tot_registrar_nr + $row['reg_nr'];
                $fr_tot_registrar_tot = $fr_tot_registrar_tot + $row['reg'];

                $fr_tot_ready_r = $fr_tot_ready_r + $row['ready'];
                $fr_tot_ready_nr = $fr_tot_ready_nr + $row['not_ready'];
                $fr_tot_ready_tot = $fr_tot_ready_tot + $row['total'];
                if($row['next_dt'] != null){
                    if($row['is_holiday'] == 1){
                        $holiday_css = "style='background-color: #FF7979;'";
                    }
                    else{
                        $holiday_css = "";
                    }
                    ?>
                    
                    <tr <?=$holiday_css?>>
                    <td align="center" style='vertical-align: top;'><?php echo $sno++; ?></td>
                    <td align="center" style='text-align: left; vertical-align: top;'><?php echo date('d-m-Y', strtotime($row['next_dt'])); ?></td>
                    <td align="center" style='vertical-align: top;'>
                        <a href='<?php echo "not_ready_get_detail?list_dt=$row[next_dt]&flag=f&ltype=court_r&ct=$connt'";?> target='_blank'>
                        <?php  echo $row['court_r']; ?>
                        </a>
                    </td>
                    <td align="center" style='vertical-align: top;'>
                        <a href='<?php echo "not_ready_get_detail?list_dt=$row[next_dt]&flag=f&ltype=court_nr&ct=$connt'";?> target='_blank'>
                        <?php  echo $row['court_nr']; ?>
                        </a>
                        </td>
                    <td align="center" style='vertical-align: top;'>
                        <a href='<?php echo "not_ready_get_detail?list_dt=$row[next_dt]&flag=f&ltype=court&ct=$connt'";?> target='_blank'>
                        <?php  echo $row['court']; ?>
                        </a>
                    </td>
                    <td align="center" style='vertical-align: top;'>
                        <a href='<?php echo "not_ready_get_detail?list_dt=$row[next_dt]&flag=f&ltype=chamber_r&ct=$connt'";?> target='_blank'>
                        <?php  echo $row['chamber_r']; ?>
                        </a>

                    </td>
                    <td align="center" style='vertical-align: top;'>
                        <a href='<?php echo "not_ready_get_detail?list_dt=$row[next_dt]&flag=f&ltype=chamber_nr&ct=$connt'";?> target='_blank'>
                        <?php  echo $row['chamber_nr']; ?>
                        </a>

                    </td>
                    <td align="center" style='vertical-align: top;'>
                        <a href='<?php echo "not_ready_get_detail?list_dt=$row[next_dt]&flag=f&ltype=chamber&ct=$connt'";?> target='_blank'>
                        <?php  echo $row['chamber']; ?>
                        </a>

                    </td>
                    <td align="center" style='vertical-align: top;'>
                        <a href='<?php echo "not_ready_get_detail?list_dt=$row[next_dt]&flag=f&ltype=reg_r&ct=$connt'";?> target='_blank'>
                        <?php  echo $row['reg_r']; ?>
                        </a>

                    </td>
                    <td align="center" style='vertical-align: top;'>
                        <a href='<?php echo "not_ready_get_detail?list_dt=$row[next_dt]&flag=f&ltype=reg_nr&ct=$connt'";?> target='_blank'>
                        <?php  echo $row['reg_nr']; ?>
                        </a>

                    </td>
                    <td align="center" style='vertical-align: top;'>
                        <a href='<?php echo "not_ready_get_detail?list_dt=$row[next_dt]&flag=f&ltype=reg&ct=$connt'";?> target='_blank'>
                        <?php  echo $row['reg']; ?>
                        </a>

                    </td>
                    <td align="center" style='vertical-align: top;'>
                        <a href='<?php echo "not_ready_get_detail?list_dt=$row[next_dt]&flag=f&ltype=ready&ct=$connt'";?> target='_blank'>
                        <?php  echo $row['ready']; ?>
                        </a>

                    </td>
                    <td align="center" style='vertical-align: top;'>
                        <a href='<?php echo "not_ready_get_detail?list_dt=$row[next_dt]&flag=f&ltype=not_ready&ct=$connt'";?> target='_blank'>
                        <?php  echo $row['not_ready']; ?>
                        </a>

                    </td>
                    <td align="center" style='vertical-align: top;'>
                        <a href='<?php echo "not_ready_get_detail?list_dt=$row[next_dt]&flag=f&ltype=Total&ct=$connt'";?> target='_blank'>
                        <?php  echo $row['total']; ?>
                        </a>

                    </td>
                    </tr>
                <?php }
                }
               // else{
                    ?>
                    <tr ><td style="background-color: #0d48be !important;color: #fff !important;white-space: nowrap;" colspan="2" align="right"> TOTAL </td>
                    <td align="center" style='vertical-align: top;background-color: #0d48be !important;white-space: nowrap;'>
                            <a style="color: #fff !important;" href='<?php echo "not_ready_get_detail?list_dt=0&flag=f&ltype=court_r&ct=$connt'";?> target='_blank'>
                            <?php  echo $fr_tot_court_r; ?>
                            </a>
                        </td>
                        <td align="center" style='vertical-align: top;background-color: #0d48be !important;white-space: nowrap;'>
                            <a href='<?php echo "not_ready_get_detail?list_dt=0&flag=f&ltype=court_nr&ct=$connt'";?> target='_blank'>
                            <?php  echo $fr_tot_court_nr; ?>
                            </a>
                        </td>
                        <td align="center" style='vertical-align: top;background-color: #0d48be !important;white-space: nowrap;'>
                            <a style="color: #fff !important;" href='<?php echo "not_ready_get_detail?list_dt=0&flag=f&ltype=court&ct=$connt'";?> target='_blank'>
                            <?php  echo $fr_tot_court_tot; ?>
                            </a>
                        </td>
                        <td align="center" style='vertical-align: top;background-color: #0d48be !important;white-space: nowrap;'>
                            <a style="color: #fff !important;" href='<?php echo "not_ready_get_detail?list_dt=0&flag=f&ltype=chamber_r&ct=$connt'";?> target='_blank'>
                            <?php  echo $fr_tot_chamber_r; ?>
                            </a>

                        </td>
                        <td align="center" style='vertical-align: top;background-color: #0d48be !important;white-space: nowrap;'>
                            <a style="color: #fff !important;" href='<?php echo "not_ready_get_detail?list_dt=0&flag=f&ltype=chamber_nr&ct=$connt'";?> target='_blank'>
                            <?php  echo $fr_tot_chamber_nr; ?>
                            </a>

                        </td>
                        <td align="center" style='vertical-align: top;background-color: #0d48be !important;white-space: nowrap;'>
                            <a style="color: #fff !important;" href='<?php echo "not_ready_get_detail?list_dt=0&flag=f&ltype=chamber&ct=$connt'";?> target='_blank'>
                            <?php  echo $fr_tot_chamber_tot; ?>
                            </a>

                        </td>
                        <td align="center" style='vertical-align: top;background-color: #0d48be !important;white-space: nowrap;'>
                            <a style="color: #fff !important;" href='<?php echo "not_ready_get_detail?list_dt=0&flag=f&ltype=reg_r&ct=$connt'";?> target='_blank'>
                            <?php  echo $fr_tot_registrar_r; ?>
                            </a>

                        </td>
                        <td align="center" style='vertical-align: top;background-color: #0d48be !important;white-space: nowrap;'>
                            <a style="color: #fff !important;" href='<?php echo "not_ready_get_detail?list_dt=0&flag=f&ltype=reg_nr&ct=$connt'";?> target='_blank'>
                            <?php  echo $fr_tot_registrar_nr; ?>
                            </a>

                        </td>
                        <td align="center" style='vertical-align: top;background-color: #0d48be !important;white-space: nowrap;'>
                            <a style="color: #fff !important;" href='<?php echo "not_ready_get_detail?list_dt=0&flag=f&ltype=reg&ct=$connt'";?> target='_blank'>
                            <?php  echo $fr_tot_registrar_tot; ?>
                            </a>

                        </td>
                        <td align="center" style='vertical-align: top;background-color: #0d48be !important;white-space: nowrap;'>
                            <a style="color: #fff !important;" href='<?php echo "not_ready_get_detail?list_dt=0&flag=f&ltype=ready&ct=$connt'";?> target='_blank'>
                            <?php  echo  $fr_tot_ready_r; ?>
                            </a>

                        </td>
                        <td align="center" style='vertical-align: top;background-color: #0d48be !important;white-space: nowrap;'>
                            <a style="color: #fff !important;" href='<?php echo "not_ready_get_detail?list_dt=0&flag=f&ltype=not_ready&ct=$connt'";?> target='_blank'>
                            <?php  echo $fr_tot_ready_nr; ?>
                            </a>

                        </td>
                        <td align="center" style='vertical-align: top;background-color: #0d48be !important;white-space: nowrap;'>
                            <a style="color: #fff !important;" href='<?php echo "not_ready_get_detail?list_dt=0&flag=f&ltype=Total&ct=$connt'";?> target='_blank'>
                            <?php  echo $fr_tot_ready_tot; ?>
                            </a>
                        </td>
                    </tr>


                    <?php
                //}
                //END OF WHILE LOOP
                ?>

        </tbody>
    </table>
<?php
} else {
    echo "No Future Date Records Found";
}
?>
</div>

