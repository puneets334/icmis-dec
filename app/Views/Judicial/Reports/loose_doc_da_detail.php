<?= view('header') ?>
<link rel="stylesheet" href="<?= base_url() ?>/assets/css/skins/_all-skins.min.css">
<link rel="stylesheet" href="<?= base_url() ?>/assets/plugins/datepicker/datepicker3.css">
<link rel="stylesheet" href="<?= base_url() ?>/assets/css/Reports.css">
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Judicial / Report >> Dak Counter-Report </h3>
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
                            <div class="card-header p-2" style="background-color: #fff;">
                                <?= view('Judicial/Reports/menu') ?>
                            </div>
                            <div class="card-body">
                                <div class="tab-content">
                                    <!-- Page Content Start -->
                                    <div class="col-md-12">
                                    <form class="form-horizontal" id="push-form" method="post" action="<?php echo base_url() ?>/Judicial/Report/loose_document_da">
                                    
                                    <?php echo csrf_field(); ?>
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row align-items-center">
                                                    <div class="col-md-3" id="fromDate">
                                                        <div class="form-group">
                                                            <label for="from">From Date</label>
                                                            
                                                            <input type="date" id="from_date" name="from_date" class="form-control"  autocomplete="off" value="<?php if(isset($_POST['from_date']) && !empty(isset($_POST['from_date'])) ) { echo $_POST['from_date']; } ?>" required placeholder="From Date">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3" id="toDate">
                                                        <div class="form-group">
                                                            <label for="to">To Date</label>
                                                            <input type="date" class="form-control" id="to_date" required name="to_date"  autocomplete="off" placeholder="To Date" value="<?php if(isset($_POST['to_date']) && !empty(isset($_POST['to_date'])) ) { echo $_POST['to_date']; } ?>" /> 
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                            <button type="submit" id="view" name="view" onclick="check(); " class="btn btn-block btn-primary">View</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        </form>
                                    </div>
                                    <form>
                                        <button type="submit" style="width:15%;float:left" id="print" name="print" onclick="printDiv('printable')" class="btn btn-block btn-warning">Print</button>
                                    </form>
                                    <div id="printable">
                                        <br /><br />
                                        <?php
                                        $name = '';
                                        $section = '';
                                        $empid = '';
                                        foreach ($loose_document_da_detail as $result) {
                                            $name = $result['name'];
                                            $empid = $result['empid'];
                                            $section = $result['section_name'];
                                            $type = trim($result['usertype']);
                                            //    echo $user;

                                            // $sql_bo = "select usertype from users where usercode=$user";
                                            // $query = $this->db->query($sql_bo);
                                            // // $rs_section=mysql_query($da_section);

                                            // $s = $query->result_array();
                                            // foreach ($s as $result) {

                                            //     $type = trim($result['usertype']);
                                            // }
                                            // echo $type;

                                            if (($type == 14) || ($type == 9)) {

                                                //   echo " branch officer login";
                                                if ($first_date == $to_date)
                                                    $heading = "Documents received by DAK Counter in the cases allocated to  Section-" . $section . " on " . date('d-m-Y', strtotime($first_date)) . " as on " . date('d-m-Y') . " at " . date("H:i:s");
                                                else if ($first_date != $to_date)
                                                    $heading = "Documents received by DAK Counter in the cases allocated to Section-" . $section . " from " . date('d-m-Y', strtotime($first_date)) . " to " . date('d-m-Y', strtotime($to_date)) . " as on " . date('d-m-Y') . " at " . date("H:i:s", time());
                                            } else {
                                                if ($first_date == $to_date)
                                                    $heading = "Documents received by DAK Counter in the cases allocated to " . $name . "[" . $empid . "] of Section-" . $section . " on " . date('d-m-Y', strtotime($first_date)) . " as on " . date('d-m-Y') . " at " . date("H:i:s");
                                                else if ($first_date != $to_date)
                                                    $heading = "Documents received by DAK Counter in the cases allocated to " . $name . "[" . $empid . "] of Section-" . $section . " from " . date('d-m-Y', strtotime($first_date)) . " to " . date('d-m-Y', strtotime($to_date)) . " as on " . date('d-m-Y') . " at " . date("H:i:s", time());
                                            }
                                        }



                                        ?>
                                        <h2 align="center"><?= $heading; ?></h2>
                                        <?php
                                        if (isset($loose_document_da_result) && sizeof($loose_document_da_result) > 0) {
                                        ?>

                                            <table class="table table-striped table-hover ">
                                                <thead>
                                                    <br />
                                                    <tr>
                                                        <th>Sr.No.</th>
                                                        <th>Diary Number</th>
                                                        <th>Cause Title</th>
                                                        <th>Section</th>
                                                        <th>Dealing Assistant</th>
                                                        <th>Document Number</th>
                                                        <th>Description</th>
                                                        <th>Filed By</th>
                                                        <th>Filed On</th>
                                                        <th>DAK DA</th>
                                                        <th>Next Listing Date </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $i = 0;
                                                    $total = 0;
                                                    foreach ($loose_document_da_result as $result) {
                                                        $i++;
                                                    ?>
                                                        <tr>
                                                            <td><?php echo $i; ?></td>
                                                            <td><a><?php echo  $result['diary_no']; ?> <br> <?php echo  $result['reg_no_display'];  ?></a></td>
                                                            <td><?php echo  $result['causetitle']; ?></td>
                                                            <td><?php echo  $result['da_section']; ?></td>
                                                            <td><?php echo  $result['da_name']; ?></td>
                                                            <td><?php echo  $result['document']; ?></td>
                                                            <td><?php echo  $result['docdesc']; ?></td>
                                                            <td><?php echo  $result['filedby']; ?></td>
                                                            <td><?php echo  date('d-m-Y H:i:s', strtotime($result['ent_dt'])); ?></td>
                                                            <td><?php echo  $result['dak_name'] . "(" . $result['dak_empid'] . ")"; ?></td>
                                                            <td><?php if ($result['next_date'] != null && $result['next_date'] != '0000-00-00' && $result['diff'] > 0 && $result['diff'] <= 7)
                                                                    echo "<font color='red'>" . date('d-m-Y', strtotime($result['next_date'])) . "</font>";
                                                                else if ($result['next_date'] != null && $result['next_date'] != '0000-00-00' && $result['diff'] > 7)
                                                                    echo "<font color='green'>" . date('d-m-Y', strtotime($result['next_date'])) . "</font>";
                                                                ?></td>
                                                        </tr>

                                                    <?php
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                    </div>
                                <?php
                                        } else {

                                            if ($type == 14) {


                                                echo '<br/><br/><br/>';
                                                echo "<font size='10px'; color='red'; align='center';>No Document Received By DAK Counter in the cases allocated to this section.</font>";
                                            } else {
                                                echo '<br/><br/><br/>';
                                                echo "<font size='10px'; color='red'; align='center';>No Document Received By DAK Counter in the cases allocated to you.</font>";
                                            }
                                        }

                                ?>

                                <!-- Page Content End -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Main content End -->
<script src="<?= base_url() ?>/assets/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<script src="<?= base_url() ?>/assets/plugins/fastclick/fastclick.js"></script>
<script src="<?= base_url() ?>/assets/js/app.min.js"></script>
<script src="<?= base_url() ?>/assets/js/Reports.js"></script>
<script src="<?= base_url() ?>/assets/jsAlert/dist/sweetalert.min.js"></script>
<script src="<?= base_url() ?>/assets/plugins/datepicker/bootstrap-datepicker.js"></script>
<script>
    $(function() {
        $('.datepick').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true
        });
    });

    // function check() {
    //     var fromDate = document.getElementById('from_date').value;
    //     var toDate = document.getElementById('to_date').value;
        
    //     date1 = new Date(fromDate.split('-')[2], fromDate.split('-')[1] - 1, fromDate.split('-')[0]);
    //     date2 = new Date(toDate.split('-')[2], toDate.split('-')[1] - 1, toDate.split('-')[0]);
        
    //     if (date1 > date2) {
    //         alert("To Date must be greater than From date");

    //         return false;
    //     }
    // }

    function check() {
        var fromDate = document.getElementById('from_date').value;
        var toDate = document.getElementById('to_date').value;        

        // Correct year, month, day mapping
        var fromParts = fromDate.split('-'); // [yyyy, mm, dd]
        var toParts = toDate.split('-');     // [yyyy, mm, dd]

        var date1 = new Date(fromParts[0], fromParts[1] - 1, fromParts[2]); // year, month (0-indexed), day
        var date2 = new Date(toParts[0], toParts[1] - 1, toParts[2]);        

        if (date1 > date2) {
            alert("To Date must be greater than or equal to From Date");
            return false;
        }
        return true;
    }

</script>