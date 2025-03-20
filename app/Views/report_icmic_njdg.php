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
<!--   https://njdg.ecourts.gov.in/scnjdg/  -->
<!--<div class="container-fluid">-->
<div class="container">
    <h2>ICMIS Data Comparison with NJDG As on : <?php echo date("d-m-Y H:i:s"); // time in India?></h2>
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
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>Un-Registered&nbsp;</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>

                                        <tr>

                                            <td>Total</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>

                                        <tr>
                                            <td rowspan="3"><br/><br/><b>Criminal</b></td>
                                            <td>Registered</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>Un-Registered&nbsp;</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>

                                        <tr>

                                            <td>Total</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>

                                        <tr>
                                            <td rowspan="3"><br/><br/><b>Total</b></td>
                                            <td>Registered</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>Un-Registered&nbsp;</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>

                                        <tr>

                                            <td>Total</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
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
                                            <td >Civil</td> <td >Criminal</td> <td >Total</td>
                                            <td >Civil</td> <td >Criminal</td> <td >Total</td>
                                            <td >Civil</td> <td >Criminal</td> <td >Total</td>
                                        </tr>
                                        <tr>
                                            <td>3 Judges</td>
                                            <td>ICMIS Civil</td>
                                            <td>ICMIS Criminal</td>
                                            <td>ICMIS total </td>

                                            <td></td>
                                            <td></td>
                                            <td></td>

                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>5 Judges</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>

                                            <td></td>
                                            <td></td>
                                            <td></td>

                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>7 Judges</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>

                                            <td></td>
                                            <td></td>
                                            <td></td>

                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>

                                        <tr>
                                            <td>9 Judges</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>

                                            <td></td>
                                            <td></td>
                                            <td></td>

                                            <td></td>
                                            <td></td>
                                            <td></td>

                                        </tr>

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
                                        <th>LOCAL NJDG</th>
                                        <th>Public NJDG</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>Civil</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>

                                    <tr>
                                        <td>Criminal</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>

                                    <tr>
                                        <td>Total</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
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
                                        <th>LOCAL NJDG</th>
                                        <th>Public NJDG</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>Civil</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>

                                    <tr>
                                        <td>Criminal</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>

                                    <tr>
                                        <td>Total</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
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
                                        <th>LOCAL NJDG</th>
                                        <th>Public NJDG</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>Civil</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>

                                    <tr>
                                        <td>Criminal</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>

                                    <tr>
                                        <td>Total</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
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
                                        <th>LOCAL NJDG</th>
                                        <th>Public NJDG</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>Civil</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>

                                    <tr>
                                        <td>Criminal</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>

                                    <tr>
                                        <td>Total</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
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
                                <tr>
                                    <td>1</td>
                                    <td>SPECIAL LEAVE PETITION (CIVIL)</td>
                                    <td>1</td>
                                    <td>2</td>
                                    <td>3</td>
                                </tr>
                                <tr>
                                    <td colspan="2">Total</td>
                                    <td></td>
                                    <td>1</td>
                                    <td>2</td>
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
                                    <tr>

                                        <td></td>
                                        <td></td>
                                        <td></td>


                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>


                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>

                                        <td></td>
                                        <td></td>
                                        <td></td>


                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>


                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>

                                        <td></td>
                                        <td></td>
                                        <td></td>


                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>


                                        <td></td>
                                        <td></td>
                                    </tr>

                                    <tr>

                                        <th>Total</th>
                                        <td></td>
                                        <td></td>


                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>


                                        <td></td>
                                        <td></td>

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
                                    <td>ICMIS 1</td>
                                    <td></td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>Disposal without Conversion</td>
                                    <td>ICMIS 2</td>
                                    <td></td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>Disposed after Conversion</td>
                                    <td>ICMIS 3</td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Total Disposal</td>
                                    <td>ICMIS 4</td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div> <!--panel-group end-->

    </div>

</body>
</html>
