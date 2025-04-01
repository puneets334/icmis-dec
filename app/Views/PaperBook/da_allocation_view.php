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
                                <h3 class="card-title"> GODOWN USER ALLOCATION REPORT </h3>
                            </div>
                        </div>
                    </div>
                 
                    <div class="card-body">

                        <div class="col-md-12">
                            <div class="card-body">
                                <form name="alocatefrm" id="alocatefrm" method="post" action="<?= site_url('PaperBook/PaperBookController/allocationReport') ?>">
                                    <?= csrf_field() ?>                            
                                    <div id="dv_content1"   >                                     
                                        <TABLE align= center width=50% >
                                        <tr></tr>
                                        <tr><INPUT TYPE="submit" name='show'  id = 'show' value = "SHOW REPORT"> <td></TR>
                                        <hr>
                                        </TABLE>

                                        </div>

                                </form>
                                <div id="loader"></div>
                            </div>
                        </div>


                        <table class="align-items-center table table-hover table-striped">
                            <thead class="thead-light">
                                <th scope="col">#</th>
                                <th scope="col"><strong>Employee Code</strong></th>
                                <th scope="col"><strong>Usercode</strong></th>
                                <th scope="col"><strong>Name</strong></th>
                                <th scope="col" colspan="2"><strong>Cases Allotted</strong></th>
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
                                            <td></td>
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


<script>
        document.getElementById("alocatefrm").addEventListener("submit", function() {
            //document.getElementById("loader").style.display = "block";  
            $('#loader').html('<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>');
        });
    </script>