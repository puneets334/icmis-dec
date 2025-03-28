<?= view('header'); ?>
<section class="content">
    <div class="container-fluid">
        <div id="res_loader"></div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                   
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">GODOWN USER ALLOCATION REPORT (<?php echo $case_year;?>) </h3>
                            </div>
                        </div>
                    </div>
                 
                    <div class="card-body">
                    <h3 style="font-size: 1.2em;text-align: center"> <?php echo $des.   "    Case Year :  ". $case_year ?> </h3>
                  <!--  <p style="font-size: 1.2em;text-align: center">Total Matters :<?php //echo $count ?></p> -->

                        <table class="align-items-center table table-hover table-striped">
                            <thead class="thead-light">
                                <th scope="col">Sr.No.</th>
                                <th scope="col"><strong>Diary No</strong></th>
                                <th scope="col"><strong>Registration No.</strong></th>
                                <th scope="col"><strong>Cause Title</strong></th>
                                <th scope="col"><strong>Section</strong></th>
                                <th scope="col"><strong>Dealing Assistant</strong></th>
                            </thead>
                            <tbody>
                                <?php if (!empty($results)): $sno = 1; ?>
                                    <?php foreach ($results as $user): ?>
                                        <tr>
                                            <td><?= $currentPage + $sno ?></td>
                                            <td><?= $user['dno'] ?></td>
                                            <td><?= $user['reg_no_display'] ?></td>
                                            <td><?= $user['cause_title'] ?></td>
                                            <td><?= $user['sectionName'] ?></td>
                                            <td><?= $user['tentativeDA'] ?></td>
                                        </tr>
                                    <?php  $sno++; endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6">No Record Found</td> <!-- Spanning columns for better alignment -->
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer text-center">
                        <?= $pager ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>