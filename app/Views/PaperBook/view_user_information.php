<?= view('header'); ?>
<section class="content">
    <div class="container-fluid">
        <div id="res_loader"></div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header heading row align-items-center">
                        <h3 class="mb-0">Case Year : UNALLOCATED DIARY MATTERS UPTO 2016</h3>
                    </div>
                 
                    <div class="card-body">
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
                                            <td><?= $sno ?></td>
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