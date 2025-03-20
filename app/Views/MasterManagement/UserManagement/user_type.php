<?= view('header') ?>

<style>
    #btnCan,
    #btnUp {
        display: none;
    }

    #descp {
        width: 200px;
    }

    .showSweetAlert {
        top: 326px;
    }
</style>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title"> USER TYPE </h3>
                            </div>
                        </div>
                    </div>
                    <!-- Main content start -->
                    <div class="col-md-12">
                        <div class="card-body">
                            <form method="post" action="">
                                <?= csrf_field(); ?>
                                <input type="hidden" id="hd_id_for_usertype">
                                <div class="row align-items-center">
                                    <div class="col-md-1.2">
                                        <div class="form-group">
                                            <label for="id_utype" class="col-form-label">UserType ID</label>
                                            <input type="text" id="id_utype" class="form-control" value="<?php echo $get_Open_id; ?>" size="3" disabled />
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="name_utype" class="col-form-label">UserType Name</label>
                                            <input type="text" id="name_utype" class="form-control" maxlength="30" />
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="dflag_utype" class="col-form-label">Dispatch Flag</label>
                                            <input type="text" id="dflag_utype" class="form-control" maxlength="10" size="10" />
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="mflag_utype" class="col-form-label">Mgmt. Flag</label>
                                            <input type="text" id="mflag_utype" class="form-control" maxlength="10" size="10" />
                                        </div>
                                    </div>
                                    <div class="col-md-2 mt-3">
                                        <input type="button" class="btn btn-primary" value="Add New" id="btnMain" />
                                        <input type="button" class="btn btn-primary" value="Update" id="btnUp" />
                                        <input type="button" class="btn btn-primary" value="Cancel" id="btnCan" />
                                    </div>
                                </div>
                                <hr>


                                <div class="table-responsive">
                                    <table id="result_main" class="table table-striped custom-table">
                                        <thead>
                                            <tr>
                                                <th>S.No.</th>
                                                <th>ID</th>
                                                <th>UserType Name</th>
                                                <th>Dispatch Flag</th>
                                                <th>Management Flag</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php
                                            if (!empty($results)) {
                                                foreach ($results as $index => $result) { ?>
                                                    <tr>
                                                        <td><?= $index + 1;  ?></td>
                                                        <td><?= $result['id'];  ?></td>
                                                        <td><?= $result['type_name'];  ?></td>
                                                        <td><?= $result['disp_flag'];  ?></td>
                                                        <td><?= $result['mgmt_flag'];  ?></td>
                                                        <td class="text-center">
                                                            <input type="button" class="btn btn-primary btn-sm" id="btnEdit<?php echo $result['id']; ?>" value="Edit" />
                                                            <input type="button" class="btn btn-primary btn-sm" id="btnDelete<?php echo $result['id']; ?>" value="Remove" />
                                                        </td>
                                                    </tr>
                                                <?php  }
                                            } else {
                                                ?>
                                                <div class="sorry">SORRY, NO RECORD FOUND!!!</div>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>
<script src="<?= base_url('/user_management/user_type.js') ?>"></script>
<script>
    $(function() {
        $("#result_main").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", {
                    extend: 'pdfHtml5',
                    orientation: 'landscape',
                    pageSize: 'LEGAL'
                },
                {
                    extend: 'colvis',
                    text: 'Show/Hide'
                }
            ],
            "bProcessing": true,
            "extend": 'colvis',
            "text": 'Show/Hide'
        }).buttons().container().appendTo('#query_builder_wrapper .col-md-6:eq(0)');
    });
</script>