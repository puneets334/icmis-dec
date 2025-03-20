<!DOCTYPE html>
<html lang="en">
<head>
    <title>ICMIS Data Comparison with Local NJDG</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>
<!--   https://njdg.ecourts.gov.in/scnjdg/  -->
<!--<div class="container-fluid">-->
<div class="container">
    <h2>ICMIS Data Comparison with Local NJDG  As on : <?php echo date("d-m-Y H:i:s"); // time in India?></h2>
    <hr/>
    <!-- <button class="nav-link active" id="alerts-tab" data-bs-toggle="pill" data-bs-target="#pills-alerts" type="button" role="tab" aria-controls="pills-alerts" aria-selected="true" onclick="mainDashboard();"><i class="fas fa-bell"></i> At a Glance</button>-->
    <div class="row">
        <div class="col-sm-4"><a data-toggle="collapse" data-parent="#accordion" href="#collapse1">At a Glance</a></div>
        <div class="col-sm-4"><a data-toggle="collapse" data-parent="#accordion" href="#collapse2"> Pending Dashboard </a></div>
        <div class="col-sm-4"><a data-toggle="collapse" data-parent="#accordion" href="#collapse3"> Disposed Dashboard </a></div>

        <div class="panel-group" id="accordion">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <!--<a data-toggle="collapse" data-parent="#accordion" href="#collapse1">At a Glance</a>-->
                    </h4>
                </div>
                <div id="collapse1" class="panel-collapse collapse in">
                    <div class="panel-body">

                    </div> <!--end panel-body-->


                    <div class="row" style="padding-left: 5px;padding-right: 20px;">
                        <div class="col-sm-8">
                            <div class="row">
                                <div class="col-sm-12">
                                    <h4><b><u>Pendency</u></b></h4>
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th colspan="2">NATURE</th>
                                            <th>ICMIS</th>
                                            <th>LOCAL NJDG</th>
                                            <th>PUBLIC NJDG</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td rowspan="3"><br/><br/><b>Civil</b></td>
                                            <td>Registered</td>
                                            <td><?=$pendency['icmis_civil_registered'];?></td>
                                            <td><?=$pendency['njdg_civil_registered'];?></td>
                                        </tr>
                                        <tr>
                                            <td>Un-Registered&nbsp;</td>
                                            <td><?=$pendency['icmis_civil_unregistered'];?></td>
                                            <td><?=$pendency['njdg_civil_unregistered'];?></td>
                                            <td></td>
                                        </tr>

                                        <tr>

                                            <th>Total</th>
                                            <th><?=$pendency['icmis_civil_registered_unregistered_total'];?></th>
                                            <th><?=$pendency['njdg_civil_registered_unregistered_total'];?></th>
                                            <th></th>
                                        </tr>

                                        <tr>
                                            <td rowspan="3"><br/><br/><b>Criminal</b></td>
                                            <td>Registered</td>
                                            <td><?=$pendency['icmis_criminal_registered'];?></td>
                                            <td><?=$pendency['njdg_criminal_registered'];?></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>Un-Registered&nbsp;</td>
                                            <td><?=$pendency['icmis_criminal_unregistered'];?></td>
                                            <td><?=$pendency['njdg_criminal_unregistered'];?></td>
                                            <td></td>
                                        </tr>

                                        <tr>

                                            <th>Total</th>
                                            <th><?=$pendency['icmis_criminal_registered_unregistered_total'];?></th>
                                            <th><?=$pendency['njdg_criminal_registered_unregistered_total'];?></th>
                                            <th></th>
                                        </tr>

                                        <tr>
                                            <td rowspan="3"><br/><br/><b>Total</b></td>
                                            <td>Registered</td>
                                            <td><?=$pendency['icmis_registered_total'];?></td>
                                            <td><?=$pendency['njdg_registered_total'];?></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>Un-Registered&nbsp;</td>
                                            <td><?=$pendency['icmis_unregistered_total'];?></td>
                                            <td><?=$pendency['njdg_unregistered_total'];?></td>
                                            <td></td>
                                        </tr>

                                        <tr>

                                            <th>Total</th>
                                            <th><?=$pendency['icmis_civil_criminal_registered_unregistered_grand_total'];?></th>
                                            <th><?=$pendency['njdg_civil_criminal_registered_unregistered_grand_total'];?></th>
                                            <th></th>
                                        </tr>



                                        </tbody>
                                    </table>
                                </div>
                            </div>




                            <div class="row">
                                <div class="col-sm-12">
                                    <h4><b><u>Coram wise matters</u></b></h4>
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th>Nature</th>
                                            <th colspan="3" >ICMIS</th>
                                            <th colspan="3">LOCAL NJDG</th>
                                            <th colspan="3">Public NJDG</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td></td>
                                            <td >Civil</td> <td >Criminal</td> <th>Total</th>
                                            <td >Civil</td> <td >Criminal</td> <th>Total</th>
                                            <td >Civil</td> <td >Criminal</td> <th>Total</th>
                                        </tr>

                                        <?php

                                        foreach ($coram_wise_matters['icmis_njdg_coram_wise_matters'] as $row) {
                                            if(!empty($row) && !empty($row['bench_judges'])){
                                                $icmis_total_count_civil=!empty($row['icmis_total_count_civil']) ? $row['icmis_total_count_civil']: 0;
                                                $icmis_total_count_criminal=!empty($row['icmis_total_count_criminal']) ? $row['icmis_total_count_criminal']: 0;
                                                $icmis_total_count_civil_criminal=$icmis_total_count_civil + $icmis_total_count_criminal;

                                                $njdg_total_count_civil=!empty($row['njdg_total_count_civil']) ? $row['njdg_total_count_civil']: 0;
                                                $njdg_total_count_criminal=!empty($row['njdg_total_count_criminal']) ? $row['njdg_total_count_criminal']: 0;
                                                $njdg_total_count_civil_criminal=$njdg_total_count_civil + $njdg_total_count_criminal;
                                                ?>
                                                <tr>
                                                    <td><?=$row['bench_judges'];?></td>
                                                    <td><?=$icmis_total_count_civil;?></td>
                                                    <td><?=$icmis_total_count_criminal;?></td>
                                                    <th><?=$icmis_total_count_civil_criminal;?></th>

                                                    <td><?=$njdg_total_count_civil;?></td>
                                                    <td><?=$njdg_total_count_criminal;?></td>
                                                    <th><?=$njdg_total_count_civil_criminal;?></th>

                                                    <td></td>
                                                    <td></td>
                                                    <th></th>
                                                </tr>
                                            <?php  } } ?>

                                        </tbody>
                                    </table>
                                </div>
                            </div>




                        </div>
                        <div class="col-sm-4">
                            <div class="row">
                                <h4><b><u>Instituted in last month</u></b></h4>
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th>Nature</th>
                                        <th>ICMIS</th>
                                        <th colspan="2" style="padding: 0px !important;">LOCAL NJDG  <table class="table-bordered" style="width: 100%;"> <tr ><th style="width: 49%;">Without Conversion</th> <th style="width: 50.3%;">With Conversion</th> </tr></table> </th>
                                        <th>Public NJDG</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>Civil</td>
                                        <td><?=$pendency['icmis_civil_instituted_last_month'];?></td>
                                        <td><?=$pendency['njdg_civil_instituted_last_month_without_conversion'];?></td>
                                        <td><?=$pendency['njdg_civil_instituted_last_month'];?></td>
                                        <td></td>
                                    </tr>

                                    <tr>
                                        <td>Criminal</td>
                                        <td><?=$pendency['icmis_criminal_instituted_last_month'];?></td>
                                        <td><?=$pendency['njdg_criminal_instituted_last_month_without_conversion'];?></td>
                                        <td><?=$pendency['njdg_criminal_instituted_last_month'];?></td>
                                        <td></td>
                                    </tr>

                                    <tr>
                                        <th>Total</th>
                                        <th><?=$pendency['icmis_civil_criminal_instituted_last_month_total'];?></th>
                                        <th><?=$pendency['njdg_civil_criminal_instituted_last_month_total_without_conversion'];?></th>
                                        <th><?=$pendency['njdg_civil_criminal_instituted_last_month_total'];?></th>
                                        <th></th>
                                    </tr>

                                    </tbody>
                                </table>
                            </div>
                            <div class="row">
                                <h4><b><u>Disposal in last month</u></b></h4>
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th>Nature</th>
                                        <th>ICMIS</th>
                                        <th colspan="2" style="padding: 0px !important;">LOCAL NJDG  <table class="table-bordered" style="width: 100%;"> <tr ><th style="width: 49%;">Without Conversion</th> <th style="width: 50.3%;">With Conversion</th> </tr></table> </th>
                                        <th>Public NJDG</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>Civil</td>
                                        <td><?=$pendency['icmis_civil_disposed_last_month'];?></td>
                                        <td><?=$pendency['njdg_civil_disposed_last_month_without_conversion'];?></td>
                                        <td><?=$pendency['njdg_civil_disposed_last_month'];?></td>
                                        <td></td>
                                    </tr>

                                    <tr>
                                        <td>Criminal</td>
                                        <td><?=$pendency['icmis_criminal_disposed_last_month'];?></td>
                                        <td><?=$pendency['njdg_criminal_disposed_last_month_without_conversion'];?></td>
                                        <td><?=$pendency['njdg_criminal_disposed_last_month'];?></td>
                                        <td></td>
                                    </tr>

                                    <tr>
                                        <th>Total</th>
                                        <th><?=$pendency['icmis_civil_criminal_disposed_last_month_total'];?></th>
                                        <th><?=$pendency['njdg_civil_criminal_disposed_last_month_total_without_conversion'];?></th>
                                        <th><?=$pendency['njdg_civil_criminal_disposed_last_month_total'];?></th>
                                        <th></th>
                                    </tr>

                                    </tbody>
                                </table>
                            </div>
                            <div class="row">
                                <h4><b><u>Instituted in current year</u></b></h4>
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th>Nature</th>
                                        <th>ICMIS</th>
                                        <th colspan="2" style="padding: 0px !important;">LOCAL NJDG  <table class="table-bordered" style="width: 100%;"> <tr ><th style="width: 49%;">Without Conversion</th> <th style="width: 50.3%;">With Conversion</th> </tr></table> </th>
                                        <th>Public NJDG</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>Civil</td>
                                        <td><?=$pendency['icmis_civil_instituted_last_year'];?></td>
                                        <td><?=$pendency['njdg_civil_instituted_last_year_without_conversion'];?></td>
                                        <td><?=$pendency['njdg_civil_instituted_last_year'];?></td>
                                        <td></td>
                                    </tr>

                                    <tr>
                                        <td>Criminal</td>
                                        <td><?=$pendency['icmis_criminal_instituted_last_year'];?></td>
                                        <td><?=$pendency['njdg_criminal_instituted_last_year_without_conversion'];?></td>
                                        <td><?=$pendency['njdg_criminal_instituted_last_year'];?></td>
                                        <td></td>
                                    </tr>

                                    <tr>
                                        <th>Total</th>
                                        <th><?=$pendency['icmis_civil_criminal_instituted_last_year_total'];?></th>
                                        <th><?=$pendency['njdg_civil_criminal_instituted_last_year_total_without_conversion'];?></th>
                                        <th><?=$pendency['njdg_civil_criminal_instituted_last_year_total'];?></th>
                                        <th></th>
                                    </tr>

                                    </tbody>
                                </table>
                            </div>
                            <div class="row">
                                <h4><b><u>Disposal in current year</u></b></h4>
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th>Nature</th>
                                        <th>ICMIS</th>
                                        <th colspan="2" style="padding: 0px !important;">LOCAL NJDG  <table class="table-bordered" style="width: 100%;"> <tr ><th style="width: 49%;">Without Conversion</th> <th style="width: 50.3%;">With Conversion</th> </tr></table> </th>
                                        <th>Public NJDG</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>Civil</td>
                                        <td><?=$pendency['icmis_civil_disposed_last_year'];?></td>
                                        <td><?=$pendency['njdg_civil_disposed_last_year_without_conversion'];?></td>
                                        <td><?=$pendency['njdg_civil_disposed_last_year'];?></td>
                                        <td></td>
                                    </tr>

                                    <tr>
                                        <td>Criminal</td>
                                        <td><?=$pendency['icmis_criminal_disposed_last_year'];?></td>
                                        <td><?=$pendency['njdg_criminal_disposed_last_year_without_conversion'];?></td>
                                        <td><?=$pendency['njdg_criminal_disposed_last_year'];?></td>
                                        <td></td>
                                    </tr>

                                    <tr>
                                        <th>Total</th>
                                        <th><?=$pendency['icmis_civil_criminal_disposed_last_year_total'];?></th>
                                        <th><?=$pendency['njdg_civil_criminal_disposed_last_year_total_without_conversion'];?></th>
                                        <th><?=$pendency['njdg_civil_criminal_disposed_last_year_total'];?></th>
                                        <th></th>
                                    </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end  At a Glance-->

            <div class="panel panel-default">

                <div id="collapse2" class="panel-collapse collapse">
                    <div class="panel-body">
                        <div class="row" style="padding-left: 5px;padding-right: 5px;">
                            <h4><b><u>Case Type Wise Total number of Pending Matters</u></b></h4>
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>Active Casetype Id</th>
                                    <th>Case Type</th>
                                    <th>ICMIS</th>
                                    <th>LOCAL NJDG</th>
                                    <th>PUBLIC NJDG</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $slno=1; $icmis_case_typewise_pendency_total_count=0; $njdg_case_typewise_pendency_total_count=0;
                                foreach($case_typewise_pendency['icmis_case_typewise_pendency'] as $icmis) {

                                foreach($case_typewise_pendency['njdg_case_typewise_pendency'] as $njdg) {

                                    if($icmis['casename']==$njdg['regcase_type_name_in_est']){

                                    $icmis_case_typewise_pendency_total_count=$icmis_case_typewise_pendency_total_count + $icmis['total_count'];
                                    $njdg_case_typewise_pendency_total_count=$njdg_case_typewise_pendency_total_count + $njdg['total_count'];

                                    ?>
                                <tr>
                                    <td><?=$icmis['active_casetype_id'];?></td>
                                    <td><?=$icmis['casename'];?></td>
                                    <td><?=$icmis['total_count'];?></td>
                                    <td><?=$njdg['total_count'];?></td>
                                    <td></td>
                                </tr>
                                <?php   }?>
                                <?php   }?>
                                <?php   }?>
                                <tr>
                                    <th colspan="2">Total</th>
                                    <th><?=$icmis_case_typewise_pendency_total_count;?></th>
                                    <th><?=$njdg_case_typewise_pendency_total_count;?></th>
                                    <th></th>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel panel-default">

                <div id="collapse3" class="panel-collapse collapse">
                    <div class="panel-body">

                        <div class="row" style="padding-left: 5px;padding-right: 5px;">
                            <div class="col-sm-12">
                                <h4><b><u>Disposed Dashboard </u></b></h4>
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th colspan="3" >ICMIS</th>
                                        <th colspan="4">LOCAL NJDG</th>
                                        <th colspan="2">PUBLIC NJDG</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>

                                        <td>Year</td> <td>Institute</td> <td>Disposed</td>
                                        <td>Without Conversion Institute </td> <td>With Conversion Institute </td> <td> Without Conversion Disposed </td> <td>Without Conversion Disposed </td>
                                        <td>Institute</td> <td>Disposed</td>
                                    </tr>

                                    <?php $icmis_institution_total_count=0;$icmis_disposal_total_count=0;$njdg_institution_total_count=0;$njdg_disposal_total_count=0;
                                    $njdg_institution_total_count_without_conversion=0;$njdg_disposal_total_count_without_conversion=0;
                                    foreach($disposal['icmis_njdg_institution_disposal_year_wise'] as $row){
                                        $icmis_institution_total_count=$icmis_institution_total_count + $row['icmis_institution_total_count'];$icmis_disposal_total_count=$row['icmis_disposal_total_count'] +$icmis_disposal_total_count;
                                        $njdg_institution_total_count=$njdg_institution_total_count + $row['njdg_institution_total_count'];$njdg_disposal_total_count=$njdg_disposal_total_count + $row['njdg_disposal_total_count'];

                                        $njdg_institution_total_count_without_conversion=$njdg_institution_total_count_without_conversion + $row['njdg_institution_total_count_without_conversion'];$njdg_disposal_total_count_without_conversion=$njdg_disposal_total_count_without_conversion + $row['njdg_disposal_total_count_without_conversion'];
                                        ?>

                                        <tr>
                                            <td><?=$row['year'];?></td>
                                            <td><?=$row['icmis_institution_total_count'];?></td>
                                            <td><?=$row['icmis_disposal_total_count'];?></td>


                                            <td><?=$row['njdg_institution_total_count_without_conversion'];?></td>
                                            <td><?=$row['njdg_institution_total_count'];?></td>
                                            <td><?=$row['njdg_disposal_total_count_without_conversion'];?></td>
                                            <td><?=$row['njdg_disposal_total_count'];?></td>


                                            <td></td>
                                            <td></td>
                                        </tr>
                                    <?php } ?>


                                    <tr>

                                        <th>Total</th>
                                        <th><?=$icmis_institution_total_count;?></th>
                                        <th><?=$icmis_disposal_total_count;?></th>


                                        <th><?=$njdg_institution_total_count_without_conversion;?></th>
                                        <th><?=$njdg_institution_total_count;?></th>
                                        <th><?=$njdg_disposal_total_count_without_conversion;?></th>
                                        <th><?=$njdg_disposal_total_count;?></th>


                                        <th></th>
                                        <th></th>

                                    </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row" style="padding-left: 5px;padding-right: 5px;">
                            <h4><b><u>Disposed After 2018</u></b></h4>
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>Nature</th>
                                    <th>ICMIS</th>
                                    <th>LOCAL NJDG</th>
                                    <th>Public NJDG</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>Disposed as Unregistered</td>
                                    <td><?=$disposal['icmis_disposed_as_unregisterd'];?></td>
                                    <td><?=$disposal['njdg_disposed_as_unregisterd'];?></td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>Disposal without Conversion</td>
                                    <td><?=$disposal['icmis_disposed_without_conversion'];?></td>
                                    <td><?=$disposal['njdg_disposed_without_conversion'];?></td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>Disposed after Conversion</td>
                                    <td><?=$disposal['icmis_disposed_converted'];?></td>
                                    <td><?=$disposal['njdg_disposed_converted'];?></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <th>Total Disposal</th>
                                    <th><?=$disposal['icmis_disposed_after_total'];?></th>
                                    <th><?=$disposal['njdg_disposed_after_total'];?></th>
                                    <th></th>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>

        </div> <!--panel-group end-->

    </div>

    </div>

</body>
</html>
