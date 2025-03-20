<!DOCTYPE html>
<html lang="en">
<head>
    <title>Bootstrap Example</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>

<!--<div class="container">-->
<div class="container-fluid">
    <h2>ICMIS Data Comparison with NJDG</h2>
      <hr/>
    <h4><b>Pendency Statement :</b></h4>
    <hr/>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Case Type</th>
            <th>Registered</th>
            <th>Unregistered</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>Civil</td>
            <td><?=$pendency[2]['civil_registered'];?></td>
            <td><?=$pendency[0]['civil_unregistered'];?></td>
        </tr>
        <tr>
            <td>Criminal</td>
            <td><?=$pendency[3]['criminal_registered'];?></td>
            <td><?=$pendency[1]['criminal_unregistered'];?></td>
        </tr>
        <tr>
            <td>Total</td>
            <td><?=$pendency[5]['total_registered'];?></td>
            <td><?=$pendency[4]['total_unregistered'];?></td>
        </tr>
        </tbody>
    </table>
    <hr/>
    <h4><b>Instituted Last Month :</b></h4>
    <hr/>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Case Type</th>
            <th>Total no of Instituted Last Month</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>Civil</td>
            <td><?=$pendency[6]['civil_instituted_last_month'];?></td>
        </tr>
        <tr>
            <td>Criminal</td>
            <td><?=$pendency[7]['Criminal_instituted_last_month'];?></td>
        </tr>
        </tbody>
    </table>

    <hr/>
    <h4><b>Disposed Last Month :</b></h4>
    <hr/>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Case Type</th>
            <th>Total no of Disposed Last Month</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>Civil</td>
            <td><?=$pendency[8]['civil_disposed_last_month'];?></td>
        </tr>
        <tr>
            <td>Criminal</td>
            <td><?=$pendency[9]['criminal_disposed_last_month'];?></td>
        </tr>
        </tbody>
    </table>

    <hr/>
    <h4><b>Instituted Last Year :</b></h4>
    <hr/>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Case Type</th>
            <th>Total no of Instituted Last Year</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>Civil</td>
            <td><?=$pendency[10]['civil_instituted_last_year'];?></td>
        </tr>
        <tr>
            <td>Criminal</td>
            <td><?=$pendency[11]['criminal_instituted_last_year'];?></td>
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
            <td><?=$pendency[12]['civil_disposed_last_year'];?></td>
        </tr>
        <tr>
            <td>Criminal</td>
            <td><?=$pendency[13]['criminal_disposed_last_year'];?></td>
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
