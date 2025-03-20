<?= view('header') ?>
<link rel="stylesheet" href="<?= base_url() ?>/assets/plugins/select2/select2.min.css">
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Judicial / Report >> AOR Wise Matters</h3>
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
                            <?php } else if (session("warning")) { ?>
                                <div class="alert alert-warning text-center">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    <?= session("warning") ?>
                                </div>
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
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="container text-center">
                                                    <h3>SECTION WISE LIST OF MATTERS - CONSOLIDATED REPORT</h3>
                                                </div>

                                                <div class="container">
                                                    <form class="text-center" method="post" action="<?= base_url(); ?>/Judicial/Report/aor_wise_matters">
                                                        <?php echo csrf_field(); ?>
                                                        <table class="table table-bordered mx-auto" style="width: 50%;">
                                                            <tr>
                                                                <td>AOR Code:</td>
                                                                <td>
                                                                    <input type="text" name="aorcode" id="aorcode" value="<?= $aor_code ?>" class="form-control" />
                                                                </td>
                                                                <td>
                                                                    <button type="submit" name="show" id="show" class="btn btn-primary">Show</button>
                                                                </td>
                                                                <?php if(!empty($matter_results)) { ?>
                                                                <td>
                                                                    <button type="button" id='btn_pnt' class="btn btn-primary">Print</button>
                                                                </td>
                                                                <?php } ?>
                                                            </tr>
                                                        </table>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php if(!empty($matter_results)) { ?>
                                    <div class="container">
                                        <h1 class="text-center">Section Wise List of Matters</h1>
                                        <div id="dv_con">
                                        <div style="text-align: center;">MATTERS OF <b><?= $aor_name ?> (<?= $aor_code ?>)</b></div>
                                        <table class="table table-bordered" border="1" style="width: 100%; border-collapse: collapse;">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>S.NO</th>
                                                    <th>DIARY NO.</th>
                                                    <th>SECTION</th>
                                                    <th>CASE NO.</th>
                                                    <th>CAUSE TITLE</th>
                                                    <th>Dealing Assistant</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $sno = 1; 
                                                foreach ($matter_results as $row) {
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $sno; ?></td>
                                                        <td><?php echo substr($row["diary_no"], 0, -4) . "/" . substr($row["diary_no"], -4); ?></td>
                                                        <td><?php echo $row["section_name"]; ?></td>
                                                        <td>
                                                            <?php 
                                                            $case_no = $row["short_description"] . $row["fil_no"] . "/" . $row["fil_dt"];
                                                            echo ($case_no == '/0') ? '-' : $case_no; 
                                                            ?>
                                                        </td>
                                                        <td><?php echo $row["pet_name"] . " v/s " . $row["res_name"]; ?></td>
                                                        <td><?php echo $row["user_name"]; ?></td>
                                                    </tr>
                                                    <?php    
                                                    $sno++;
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                        </div>
                                    </div>
                                    <?php } ?>
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
<script>
    $(document).ready(function() {
        $(document).on('click','#btn_pnt',function(){
            var prtContent = document.getElementById('dv_con');
            var WinPrint = window.open('','','letf=100,top=0,width=800,height=1200,toolbar=1,scrollbars=1,status=1,menubar=1');
            WinPrint.document.write('<link rel="stylesheet" href="<?= base_url() ?>/css/menu_css.css">'+prtContent.innerHTML);
            WinPrint.print(); 
        });
    });
</script>