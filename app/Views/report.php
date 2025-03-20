<!DOCTYPE html>
<html lang="en">
<head>
    <title>ICMIS Data Comparison with NJDG</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>

<!--<div class="container">-->
<div class="container">
    <h2>ICMIS Data Comparison with NJDG</h2>
      <hr/>
    <div class="row">
        <h4><b><u>Pendency Both</u></b></h4>
        <div class="col-sm-12">
            <hr/>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>Nature</th>
                    <th>ICMIS</th>
                    <th>NJDG</th>
                    <th>Difference</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $icmis_both_pend_civil_total=0; $njdg_both_pend_criminal_total =0;$icmis_both_pend_civil_diff=0; $njdg_both_pend_criminal_diff=0; $icmis_both_list_civil_when_null=0;
                $icmis_both_pend_civil_total= ($pendency['icmis_both_list'][1]['icmis_both'] + $pendency['icmis_both_list'][0]['icmis_both'] +$icmis_both_list_civil_when_null);
                $njdg_both_pend_criminal_total= ($pendency['njdg_both_list'][1]['njdg_both'] + $pendency['njdg_both_list'][0]['njdg_both']);
                if (!empty($pendency['icmis_both_list'][2]['icmis_both'])){$icmis_both_list_civil_when_null=$pendency['icmis_both_list'][2]['icmis_both'];}
                ?>
                <tr>
                    <td>Civil</td>
                    <td><?=$pendency['icmis_both_list'][1]['icmis_both'] + $icmis_both_list_civil_when_null;?></td>
                    <td><?=$pendency['njdg_both_list'][1]['njdg_both'];?></td>
                    <td><?=$icmis_both_pend_civil_diff=(($pendency['icmis_both_list'][1]['icmis_both'] + $icmis_both_list_civil_when_null) - $pendency['njdg_both_list'][1]['njdg_both']);?></td>
                </tr>

                <tr>
                    <td>Criminal</td>
                    <td><?=$pendency['icmis_both_list'][0]['icmis_both'];?></td>
                    <td><?=$pendency['njdg_both_list'][0]['njdg_both'];?></td>
                    <td><?=$njdg_both_pend_criminal_diff=($pendency['icmis_both_list'][0]['icmis_both'] - $pendency['njdg_both_list'][0]['njdg_both']);?></td>
                </tr>
                <tr>
                    <td>Total</td>
                    <td><?=($pendency['icmis_both_list'][1]['icmis_both'] + $icmis_both_list_civil_when_null + $pendency['icmis_both_list'][0]['icmis_both']);?></td>
                    <td><?=($pendency['njdg_both_list'][1]['njdg_both'] + $pendency['njdg_both_list'][0]['njdg_both']);?></td>
                    <td><?=($icmis_both_pend_civil_diff + $njdg_both_pend_criminal_diff);?></td>
                </tr>

                </tbody>
            </table>
    </div>
    </div>
    <div class="row">
        <h4><b><u>Pendency</u></b></h4>
        <div class="col-sm-6">
    <h4><b> Registered :</b></h4>
    <hr/>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Nature</th>
            <th>ICMIS</th>
            <th>NJDG</th>
            <th>Difference</th>
        </tr>
        </thead>
        <tbody>
        <?php $pend_civilR_diff=0; $pend_criminalR_diff=0; $pend_R_diff=0; ?>
        <tr>
            <td>Civil</td>
            <td><?=$pendency['civil_registered'];?></td>
            <td><?=$pendency['njdg_civil_registered'];?></td>
            <td><?=$pend_civilR_diff=($pendency['civil_registered'] - $pendency['njdg_civil_registered']);?></td>
        </tr>
        <tr>
            <td>Criminal</td>
            <td><?=$pendency['criminal_registered'];?></td>
            <td><?=$pendency['njdg_criminal_registered'];?></td>
            <td><?=$pend_criminalR_diff=($pendency['criminal_registered'] - $pendency['njdg_criminal_registered']);?></td>
        </tr>
        <tr>
            <td>Total</td>
            <td><?=($pendency['civil_registered'] + $pendency['criminal_registered']);?></td>
            <td><?=($pendency['njdg_civil_registered'] + $pendency['njdg_criminal_registered']);?></td>
            <td><?=$pend_R_diff=($pend_civilR_diff + $pend_criminalR_diff);?></td>
        </tr>

        </tbody>
    </table>
        </div>
        <div class="col-sm-6">
            <h4><b>Un-Registered :</b></h4>
            <hr/>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>Nature</th>
                    <th>ICMIS</th>
                    <th>NJDG</th>
                    <th>Difference</th>
                </tr>
                </thead>
                <tbody>
                <?php $pend_civil_diff=0; $pend_criminal_diff=0; $pend_UR_diff=0; ?>
                <tr>
                    <td>Civil</td>
                    <td><?=$pendency['civil_unregistered'];?></td>
                    <td><?=$pendency['njdg_civil_unregistered'];?></td>
                    <td><?=$pend_civil_diff=($pendency['civil_unregistered'] - $pendency['njdg_civil_unregistered']);?></td>
                </tr>
                <tr>
                    <td>Criminal</td>
                    <td><?=$pendency['criminal_unregistered'];?></td>
                    <td><?=$pendency['njdg_criminal_unregistered'];?></td>
                    <td><?=$pend_criminal_diff=($pendency['criminal_unregistered'] - $pendency['njdg_criminal_unregistered']);?></td>
                </tr>
                <tr>
                    <td>Total</td>
                    <td><?=($pendency['civil_unregistered'] + $pendency['criminal_unregistered']);?></td>
                    <td><?=($pendency['njdg_civil_unregistered'] + $pendency['njdg_criminal_unregistered']);?></td>
                    <td><?=$pend_UR_diff=($pend_civil_diff + $pend_criminal_diff);?></td>
                </tr>
                <?php  ?>

                </tbody>
            </table>
        </div>
    </div>
    <hr/>
    <h4><b>Instituted Last Month :</b></h4>
    <hr/><?php // exit();?>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Nature</th>
            <th>Total no of Instituted Last Month</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>Civil</td>
            <td><?=$pendency['civil_instituted_last_month'];?></td>
        </tr>
        <tr>
            <td>Criminal</td>
            <td><?=$pendency['Criminal_instituted_last_month'];?></td>
        </tr>
        </tbody>
    </table>

    <hr/>
    <h4><b>Disposed Last Month :</b></h4>
    <hr/>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Nature</th>
            <th>Total no of Disposed Last Month</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>Civil</td>
            <td><?=$pendency['civil_disposed_last_month'];?></td>
        </tr>
        <tr>
            <td>Criminal</td>
            <td><?=$pendency['criminal_disposed_last_month'];?></td>
        </tr>
        </tbody>
    </table>

    <hr/>
    <h4><b>Instituted Last Year :</b></h4>
    <hr/>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Nature</th>
            <th>Total no of Instituted Last Year</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>Civil</td>
            <td><?=$pendency['civil_instituted_last_year'];?></td>
        </tr>
        <tr>
            <td>Criminal</td>
            <td><?=$pendency['criminal_instituted_last_year'];?></td>
        </tr>
        </tbody>
    </table>

    <hr/>
    <h4><b>Disposed Last Year :</b></h4>
    <hr/>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Case Type</th>
            <th>Total no of Disposed Last Year</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>Civil</td>
            <td><?=$pendency['civil_disposed_last_year'];?></td>
        </tr>
        <tr>
            <td>Criminal</td>
            <td><?=$pendency['criminal_disposed_last_year'];?></td>
        </tr>
        </tbody>
    </table>
    <hr/>
    <h4><b>Year wise Instituted and Disposed :</b></h4>
    <hr/>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Year</th>
            <th>Instituted</th>
            <th>Disposed in this year</th>
        </tr>
        </thead>
        <tbody>

                <?php $i=0;
                $grand_institution_total_count=0; $grand_disposal_total_count=0;
                foreach($disposal['institution'] as $row){
                $grand_institution_total_count=$grand_institution_total_count + $row['institution_total_count'];
                //foreach($disposal['disposal'] as $row_disposal){
                if($disposal['disposal'][$i]['ord_dt']==$row['active_fil_dt']){
                    //if ($row_disposal['ord_dt']==$row['active_fil_dt']){
                        $grand_disposal_total_count=$grand_disposal_total_count + $disposal['disposal'][$i]['disposal_total_count'];
                        ?>
                <tr>
                       <td><?=$row['active_fil_dt'];?></td>
                       <td><?=$row['institution_total_count'];?></td>
                        <td><?=$disposal['disposal'][$i]['disposal_total_count'];?></td>
                </tr>

                <?php }  $i++; } ?>



            <tr>
                <td>Total</td>
                <td><?=$grand_institution_total_count;?></td>
                <td><?=$grand_disposal_total_count;?></td>
            </tr>
        </tbody>
    </table>

    <hr/>
    <h4><b>Case Type Wise Pendency :</b></h4>
    <hr/>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>S.N.</th>
            <th>Active Casetype Id</th>
            <th>Case Type</th>
            <th>Total number of pending matters</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $slno=1;
        foreach($case_typewise_pendency as $row):?>
        <tr>
            <td><?= $slno++;?></td>
            <td><?=$row['active_casetype_id'];?></td>
            <td><?=$row['casename'];?></td>
            <td><?=$row['total_count'];?></td>
        </tr>
        <?php endforeach;  ?>
        </tbody>
    </table>

</div>

</body>
</html>
