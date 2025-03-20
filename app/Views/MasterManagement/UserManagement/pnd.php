<?= view('header') ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">

                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Master Management >> User Management >> PEON & DRIVER LIST</h3>
                            </div>
                            <div class="col-sm-2"> </div>
                        </div>
                    </div>

                    <form method="post" action="">
                        <?= csrf_field(); ?>
                        <div class="container mt-4">
                            <div id="dv_content1">
                                <div class="top1 mb-3">
                                    <?php
                                    $permit = 0;

                                    if (($name[0] == 1) || ($name[0] == 2 && $name[1] == 'PROTOCOL')) {
                                        $permit = 1;
                                    }

                                    if ($permit == 0) {
                                        exit();
                                    }
                                    ?>
                                </div>
                                <div class="add_result mb-3"></div>
                                <div id="result_main">
                                    <?php if ($result != 0): ?>
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th scope="col">SNo.</th>
                                                    <th scope="col">ID</th>
                                                    <th scope="col">Department</th>
                                                    <th scope="col">Section</th>
                                                    <th scope="col">Designation/User</th>
                                                    <th scope="col">Forwarding Authority</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $sno = 1;
                                                foreach ($result as $select_type_row): ?>
                                                    <tr>
                                                        <th scope="row"><?= $sno; ?></th>
                                                        <td><?= $select_type_row['id']; ?></td>
                                                        <td>PROTOCOL</td>
                                                        <td><?= $select_type_row['utype']; ?></td>
                                                        <td>
                                                            <?= ($select_type_row['perticular_user'] == NULL) ? "ALL" : $select_type_row['perticular_user'] . ' - ' . $select_type_row['part_user_name']; ?>
                                                        </td>
                                                        <td><?= $select_type_row['faname']; ?></td>
                                                    </tr>
                                                    <?php $sno++; ?>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    <?php else: ?>
                                        <div class="alert alert-warning" role="alert">
                                            SORRY, NO RECORD FOUND!!!
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </form>





                </div>
            </div>
        </div>
</section>