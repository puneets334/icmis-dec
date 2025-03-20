<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Dealing Assistant-Report</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="<?=base_url()?>/assets/css/bootstrap.min.css">
       <!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">-->
    <link rel="stylesheet" href="<?=base_url()?>/assets/css/AdminLTE.min.css">
    <link rel="stylesheet" href="<?=base_url()?>/assets/css/Reports.css">
    <link rel="stylesheet" href="<?=base_url()?>/assets/css/skins/_all-skins.min.css">
    <link rel="stylesheet" href="<?=base_url()?>/assets/plugins/datepicker/datepicker3.css">
</head>
<body class="hold-transition skin-blue layout-top-nav">
<div class="wrapper" >
    <div class="content-wrapper">
        <div class="container">
            <!-- Main content -->
            <section class="content">
                    <form>
                        <button type="submit"  style="width:15%;float:left" id="print" name="print"  onclick="printDiv('printable')" class="btn btn-block btn-warning">Print</button>
                    </form>

            <?php


                    if($dacode==0)
                    {
                        switch ($category) {
                            case 't': {
                                $heading = "Total Matters which are not allocated to any DA";
                                break;
                            }
                            case 'r': {
                                $heading = "Matters which are not allocated to any DA and are falling under <b><font color='red'> RED </font></b> Category";
                                break;
                            }
                            case 'o': {
                                $heading = "Matters which are not allocated to any DA and are falling under <b><font color='orange'> Orange </font></b> Category";
                                break;
                            }
                            case 'g': {
                                $heading = "Matters which are not allocated to any DA and are falling under <b><font color='green'> Green </font></b> Category";
                                break;
                            }
                            case 'y': {
                                $heading = "Matters which are not allocated to any DA and are falling under <b><font color='yellow'> Yellow </font></b> Category";
                                break;
                            }
                            case 'd': {
                                $heading = "Matters which are not allocated to any DA and are not updated";
                                break;
                            }
                            default: {
                            $heading = " ";
                            break;
                            }
                        }
                    }

                if(isset($da_details) && sizeof($da_details)>0) {

                    foreach ($da_details as $details) {
                        $name = $details['name'];
                        $empid = $details['empid'];
                        $designation = $details['type_name'];
                        $section = $details['section_name'];
                    }
                }
                else
                {
                    $name = "";
                    $empid = "";
                    $designation = "";
                    $section = "";
                }

                if($name!=""&&$empid!=""&&$designation!=""&&$section!="") {
                    switch ($category) {
                        case 't': {
                            $heading = "Total Matters dealt with by <b>" . $name . "(" . $empid . ")," .
                                $designation . "</b> of Section-<b>" . $section . "</b>";
                            break;
                        }
                        case 'r': {
                            $heading = "Matters dealt with by <b>" . $name . "(" . $empid . ")," .
                                $designation . "</b> of Section-<b>" . $section .
                                "</b> and are falling under <b><font color='red'> RED </font></b> Category";
                            break;
                        }
                        case 'o': {
                            $heading = "Matters dealt with by <b>" . $name . "(" . $empid . ")," .
                                $designation . "</b> of Section-<b>" . $section .
                                "</b> and are falling under <b><font color='orange'> Orange </font></b> Category";
                            break;
                        }
                        case 'g': {
                            $heading = "Matters dealt with by <b>" . $name . "(" . $empid . ")," .
                                $designation . "</b> of Section-<b>" . $section .
                                "</b> and are falling under <b><font color='green'> Green </font></b> Category";
                            break;
                        }
                        case 'y': {
                            $heading = "Matters dealt with by <b>" . $name . "(" . $empid . ")," .
                                $designation . "</b> of Section-<b>" . $section .
                                "</b> and are falling under <b><font color='yellow'> Yellow </font></b> Category";
                            break;
                        }
                        case 'd': {
                            $heading = "Matters dealt with by <b>" . $name . "(" . $empid . ")," .
                                $designation . "</b> of Section-<b>" . $section .
                                "</b> and are not updated";
                            break;
                        }
                        default: {
                            $heading = " ";
                            break;
                        }
                    }
                }
                ?>
                <br/>
                <div id="printable">
                    <table class="table table-striped table-hover ">
                        <thead>

                        <tr><h3><?php echo $heading;?>  </h3></tr>
                        <?php
                        if(isset($da_cases) && sizeof($da_cases)>0)
                        {?>
                        <tr>
                            <th>#</th>
                            <th>Case<br/>Number</th>
                            <th>Cause Title</th>
                            <th>Tentative Section</th>
                            <th>State</th>
                            <th>Tentative<br/>Listing Date</th>
                            <th>Next<br/>Listing Date</th>
                            <th>Court Type</th>
                            <th style="width: 40%">Previous Court Remarks</th>                            
                            
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $i=0;
                        foreach ($da_cases as $result)
                        {$i++;
                            ?>
                            <tr>
                                <td><?php echo $i;?></td>
                                <td><?php echo substr($result['diary_no'],0,strlen($result['diary_no']) - 4)."/".substr($result['diary_no'], - 4)."<br/>".$result['reg_no_display'];?></td>
                                <td><?php echo $result['pet_name']." Vs ".$result['res_name'];?></td>
                                <td><?php echo $result['section'];?></td>
                                <td><?php echo $result['name'];?></td>
                                <?php
                                if($result['tentative']=='' || $result['tentative']==null || $result['tentative']=="0000-00-00 00:00:00")
                                    $result['tentative']='';
                                else
                                    $result['tentative']=date('d-m-Y',strtotime($result['tentative']));

                                // $CI     = & get_instance();
                                // $this->load->model('Reports_model');
                                $result_tentative_date = (!empty($result['tentative'])) ? get_display_status_with_date_differnces($result['tentative']) : '';
                                $result_next_date = (!empty($result['next'])) ? get_display_status_with_date_differnces($result['next']) : '';
                                ?>
                                <td><?php
                                    if($result_tentative_date=='T')
                                        echo $result['tentative'];
                                    else
                                        echo '&nbsp';
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    if($result_next_date=='T')
                                        echo date('d-m-Y',strtotime($result['next']));
                                    else
                                        echo '&nbsp';
                                    ?>
                                </td>

								<td><?php echo $result['board_type']?></td>
                                <td><?php echo (!empty($result['Rmrk_Disp']) ? $result['Rmrk_Disp'] : '')?></td>
                            </tr>

                            <?php
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            <?php }
            else
            {
                echo '<br/><br/><br/>';
                    echo "<font size='18px'; color='red';>No case Found!</font>";
            }
            ?>
            </section>
        </div>
    </div>
</div>
<script src="<?=base_url()?>/assets/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="<?=base_url()?>/assets/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<script src="<?=base_url()?>/assets/js/bootstrap.min.js"></script>
<script src="<?=base_url()?>/assets/plugins/fastclick/fastclick.js"></script>
<script src="<?=base_url()?>/assets/js/app.min.js"></script>
<script src="<?=base_url()?>/assets/jsAlert/dist/sweetalert.min.js"></script>
<script src="<?=base_url()?>/assets/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?=base_url()?>/assets/js/Reports.js"></script>
</body>
</html>
