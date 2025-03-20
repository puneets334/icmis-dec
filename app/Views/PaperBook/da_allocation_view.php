<?= view('header'); ?>
<section class="content">
    <div class="container-fluid">
        <div id="res_loader"></div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header heading row align-items-center">
                        <h3 class="mb-0">Godown User Allocation Report</h3>
                    </div>
                 
                    <div class="card-body">
                        <table class="align-items-center table table-hover table-striped">
                            <thead class="thead-light">
                                <th scope="col">#</th>
                                <th scope="col"><strong>Employee Code</strong></th>
                                <th scope="col"><strong>Usercode</strong></th>
                                <th scope="col"><strong>Name</strong></th>
                                <th scope="col"><strong>Cases Allotted</strong></th>
                                <th scope="col"><strong>Total Cases</strong></th>
                            </thead>
                            <tbody>
                                <?php if (!empty($allocatedUsers)): $sno = 1; ?>
                                    <?php foreach ($allocatedUsers as $user): ?>
                                        <tr>
                                            <td><?= $sno ?></td>
                                            <td><?= $user['empid'] ?></td>
                                            <td><?= $user['usercode'] ?></td>
                                            <td><?= $user['name'] ?></td>
                                            <td>
                                                <?php if (!empty($user['cases'])): ?>
                                                    <?php foreach ($user['cases'] as $case): 
                                                        $param = $case['case_group'] . '/' . $case['year'];
                                                    ?>
                                                      <a href="viewUserInformation?str=<?php echo $param; ?>" target='blank'>
                                                        <?= $case['case_group'] . ' - ' . $case['year'] . ' - ' . $case['total'] ?><br>
                                                        </a>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <span>UNALLOCATED DIARY MATTERS</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if (!empty($user['totalCases'])): ?>
                                                    <?php foreach ($user['totalCases'] as $totalCase): 
                                                         $param = $totalCase['casetype_id'] . '/' 
                                                         . $totalCase['caseyear'] . '/' . $totalCase['case_from'] . '/' . $totalCase['case_to'];
                                                    ?>
                                                        <a href="viewUserInformation?str=<?php echo $param; ?>" target='blank'>
                                                        <?= $totalCase['cases'] . ' - ' . $totalCase['caseyear'] . ' - ' . $totalCase['t'] ?><br>
                                                    </a>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <span>0</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php  $sno++; endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="3"></td>
                                        <td colspan="3"></td>
                                        <td colspan="3"></td>
                                        <td> <a href="viewUserInformation?str=all/2016" target='blank'><strong>UNALLOCATED DIARY MATTERS</strong></a></td>
                                        <td><?= $unallocatedDiaryMatters ? implode('<br>', array_column($unallocatedDiaryMatters, 'total')) : 'UNALLOCATED DIARY MATTERS'; ?></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3"></td>
                                        <td colspan="3"></td>
                                        <td colspan="3"></td>
                                        <td><a href="viewUserInformation?str=0/0" target='blank'><strong>UNALLOCATED REGISTERED MATTERS</strong></a></td>
                                        <td><?= $unallocatedRegisteredMatters ? implode('<br>', array_column($unallocatedRegisteredMatters, 'total')) : 'UNALLOCATED REGISTERED MATTERS'; ?></td>
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