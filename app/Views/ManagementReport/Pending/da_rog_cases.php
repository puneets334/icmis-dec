<?= view('header') ?>
<link rel="stylesheet" href="<?= base_url() ?>/assets/css/skins/_all-skins.min.css">
<link rel="stylesheet" href="<?= base_url() ?>/assets/plugins/datepicker/datepicker3.css">
<link rel="stylesheet" href="<?= base_url() ?>/assets/css/Reports.css">
<script src="<?= base_url() ?>/assets/js/Reports.js"></script>
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Reports</h3>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <span class="alert-danger"><?= \Config\Services::validation()->listErrors() ?></span>

                            <?php if (session()->getFlashdata('error')) { ?>
                                <div class="alert alert-danger">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    <?= session()->getFlashdata('error') ?>
                                </div>
                            <?php } else if (session("message_error")) { ?>
                                <div class="alert alert-danger text-danger" style="color: red;">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    <?= session("message_error") ?>
                                </div>
                            <?php } else { ?>

                            <?php } ?>
                        </div>
                        <div class="col-md-12">
                            <div class="card-header p-2" style="background-color: #fff; border-bottom:none;">
                                    <h4 class="basic_heading">
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
                                    <h3><?php echo @$heading;?>  </h3>
                                    </h4>
                                </div>
                            <div class="card-body">
                               <?php
                                if(isset($da_cases) && sizeof($da_cases)>0)
                                {?>
                                    <div id="printable">
                                            <table class="table table-striped table-hover ">
                                            <thead>
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
                                                            <td><?php echo @$result['section'];?></td>
                                                            <td><?php echo $result['name'];?></td>
                                                            <?php
                                                                if($result['tentative']=='' || $result['tentative']==null || $result['tentative']=="0000-00-00 00:00:00")
                                                                    $result['tentative']='';
                                                                else
                                                                    $result['tentative']=date('d-m-Y',strtotime($result['tentative']));

                                                                
                                                                $result_tentative_date = get_display_status_with_date_differences_new($result['tentative']);
                                                                $result_next_date = get_display_status_with_date_differences_new($result['next']);

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
                                                            <td><?php echo @$result['rmrk_disp']?></td>
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

                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>