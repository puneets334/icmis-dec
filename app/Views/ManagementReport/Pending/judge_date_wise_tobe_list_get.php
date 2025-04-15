<style>
    .custom-table thead th:last-child {
    border-radius: 28px 28px 28px 28px!important;
}
</style>
<br>
<input name="prnnt1" type="button" id="prnnt1" value="Print" class="btn btn-primary">
<div id="prnnt">
    <?php
    if (count($result_array) > 0) {
        foreach ($result_array as $ros12) { ?> 
        <div style="page-break-before:always;">
                <table border="0" width="100%" cellspacing=0>    
                    <thead>
                        <tr>
                            <th colspan="4" style="text-align: center; font-weight: bold;">
                                <?php echo $ros12['jname']; ?><br>
                                Misc. Date Wise Cases to be list
                            </th>
                        </tr>
                    </thead>
                </table>
                <?php
                             if (count($result_array2) > 0) {

                ?>
                <table class="table table-striped custom-table">
                    
                        <tr>
                            <td width="10%" style="text-align: center; font-weight: bold;">SNo</td>
                            <td width="40%" style="text-align: center; font-weight: bold;">Listing Date</td>            
                            <td width="10%" style="text-align: center; font-weight: bold;">Court Dt (FX)</td>
                            <td width="10%" style="text-align: center; font-weight: bold;">Court Dt (AW)</td>
                            <td width="20%" style="text-align: center; font-weight: bold;">Comp Gen Fix Dt as per scheme</td>    
                            <td width="10%" style="text-align: center; font-weight: bold;">Comp Gen</td>    
                            <td width="10%" style="text-align: center; font-weight: bold;">TOTAL</td>  
                        </tr>
                        <tr>
                            <td style="text-align: center; font-weight: bold; ">1</td>
                            <td style="text-align: center; font-weight: bold; ">2</td>        
                            <td style="text-align: center; font-weight: bold; ">3</td>    
                            <td style="text-align: center; font-weight: bold; ">4</td>
                            <td style="text-align: center; font-weight: bold; ">5</td>    
                            <td style="text-align: center; font-weight: bold;">6</td>
                            <td style="text-align: center; font-weight: bold; ">7</td> 
                        </tr>
                    
                    <tbody>
                        <?php
                        $sno = 1;
                        $t_fd_not_listed = 0;
                        $t_aw_not_listed = 0;
                        $t_imp_ia_not_listed = 0;
                        $t_oth_not_listed = 0;
                        $t_not_listed = 0;
                        foreach ($result_array2 as $row) {
                            $sno1 = $sno % 2;
                            if ($sno == 1 or $sno == 2) { 
                        ?>
                        <tr>
                            <?php } else if ($sno >= 3 and $sno <= 10) {
                                     
                            ?>
                        <tr>
                            <?php } else { 
                            ?>
                        <tr>
                            <?php
                              }
                            ?>
                            <td align="center" style='vertical-align: top;'><?php  echo $sno++; ?></td>
                            <td align="center" style='text-align: left; vertical-align: top;'><?php echo date('d-m-Y', strtotime($row['next_dt']));?></td>
                            <td align="center" style='vertical-align: top;'><?php $t_fd_not_listed += $row['fd_not_listed']; echo $row['fd_not_listed']; ?></td>
                            <td align="center" style='vertical-align: top;'><?php  $t_aw_not_listed += $row['aw_not_listed']; echo $row['aw_not_listed']; ?></td>
                            <td align="center" style='vertical-align: top;'><?php  $t_imp_ia_not_listed += $row['imp_ia_not_listed']; echo $row['imp_ia_not_listed']; ?></td>
                            <td align="center" style='vertical-align: top;'><?php $t_oth_not_listed += $row['oth_not_listed'];echo $row['oth_not_listed']; ?></td>
                            <td align="center" style='vertical-align: top;'><?php $t_not_listed += $row['not_listed']; echo $row['not_listed']; ?></td>
                        </tr>
                    <?php

                } 
             //} //END OF WHILE LOOP
                    ?>
                    <tr style="font-weight: bold; background: #918788;">
                        <td colspan="2" style="font-weight: bold; vertical-align: top;text-align:right"> TOTAL </td>
                        <td align="center" style='font-weight: bold; vertical-align: top;'><?php  echo $t_fd_not_listed; ?></td>
                        <td align="center" style='font-weight: bold; vertical-align: top;'><?php echo $t_aw_not_listed; ?></td>
                        <td align="center" style='font-weight: bold; vertical-align: top;'><?php  echo $t_imp_ia_not_listed; ?></td>
                        <td align="center" style='font-weight: bold; vertical-align: top;'><?php  echo $t_oth_not_listed; ?></td>
                        <td align="center" style='font-weight: bold; vertical-align: top;'><?php echo $t_not_listed; ?></td>
                    </tr>
                    </tbody>
                </table>
                <br />
            <?php
             } else {
             echo "No Records Found";
             }
        } 
            ?>
            </div> <?php
                    } //end of while loop judge
                    ?>

</div>
