<?= view('header') ?>

<style>
    a:hover {
        color: red;
    }

    a:visited {
        color: green;
    }

    #reportTable1_filter {
        padding-right: 84%
    }


    .dt-buttons {
        display: ruby;
        position: fixed;
        margin-left: 66%;
    }
</style>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">

                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Master Management >> Case Block for Loose Doc</h3>
                            </div>
                            <div class="col-sm-2"> </div>
                        </div>
                    </div>
                    <br /><br />
                    <input type="hidden" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>">
                    <!--start menu programming-->
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12"> <!-- Right Part -->
                                <div class="">
                                    <div class="d-block text-center">
                                        <!-- Main content -->

                                        <div class="">
                                            <form method="POST" action="<?= base_url(); ?>/MasterManagement/CaseBlockLooseDoc/Menu_List" class="form-horizontal" id="push-form">
                                                <?= csrf_field() ?>
                                                <div class="box-body">
                                                    <div class="form-group">
                                                        <label for="reportType" class="col-sm-2 col-md-8 col-lg-12 ">
                                                            <h2>SELECT USER FOR MENU PRIVILEGE DETAILS</h2>
                                                        </label>
                                                    </div>

                                                    <div class="row">

                                                        <div class="col-sm-12 col-md-3 mb-3">
                                                            <label class="">SELECT USER :</label>
                                                            <select class="select2 form-control" id="sect" name="emp" onchange="document.getElementById('target').value=this.value;">
                                                                <option value="">Enter User Name or ID</option>
                                                                <?php foreach ($user_code as $result): ?>
                                                                    <option value="<?= $result['usercode']; ?>" <?= (isset($target) && $target == $result['usercode']) ? 'selected' : ''; ?>>
                                                                        <?= $result['empid'] . ', ' . $result['name']; ?>
                                                                    </option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>

                                                        <input type="hidden" id="target" name="target"
                                                            value="<?php echo isset($target) ? $target : (isset($param[0]) ? $param[0] : ''); ?>" />
                                                        <div class="col-1">
                                                        </div>

                                                        <div class="col-sm-12 col-md-3 mb-3">
                                                            <button type="submit" onclick="return check();" id="view" name="view" class="quick-btn mt-26">View</button>
                                                        </div>

                                                    </div>
                                                </div>
                                            </form>

                                            <?php
                                            if (is_array($menu_list) && !empty($menu_list)) {

                                                $title =   "Menu Privilege details of :" . $menu_list[0]['name'] . '(' . $menu_list[0]['empid'] . ')';
                                            ?>
                                                <hr>
                                                <div id="printable">
                                                <div class="table-responsive">
                                                        <table id="reportTable1" class="table table-striped custom-table">
                                                            <h3 style="text-align: center;"> Menu Privilege details of : <strong><?= $menu_list[0]['name']; ?></strong> (<strong><?= $menu_list[0]['empid']; ?>)</strong></h3>
                                                            <thead>
                                                                <tr>
                                                                    <th>S.No.</th>
                                                                    <th>Menu</th>
                                                                    <th>Sub Menu</th>
                                                                    <th>Sub Sub Menu</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php

                                                                //var_dump($menu_list);
                                                                $s_no = 1;
                                                                foreach ($menu_list as $result) {
                                                                ?>
                                                                    <tr>
                                                                        <td><?php echo $s_no; ?></td>
                                                                        <td><a href="<?= base_url(); ?>/MasterManagement/CaseBlockLooseDoc/Menu_Remove?mn_me_per=<?php echo $result['main_menu_id']; ?>&emp_rem=<?php if (isset($_POST['target'])) {echo $_POST['target'];} else {echo $param[0]; } ?>" onClick="return confirm('Do you want to remove this Menu (including all the sub-menus)')"><?php echo $result['menu_nm']; ?></td>
                                                                        <td><a href="<?= base_url(); ?>/MasterManagement/CaseBlockLooseDoc/Menu_Remove?sub_me_per=<?php echo $result['sub_me_per']; ?>&emp_rem=<?php if (isset($_POST['target'])) {echo $_POST['target'];} else {echo $param[0];} ?>" onClick="return confirm('Do you want to remove this Sub-Menu including all the Sub-Sub-menus')"><?php echo $result['sub_mn_nm']; ?></td>
                                                                        <td><a href="<?= base_url(); ?>/MasterManagement/CaseBlockLooseDoc/Menu_Remove?sub_sub_menu=<?php echo $result['su_su_menu_id']; ?>&emp_rem=<?php if (isset($_POST['target'])) {echo $_POST['target']; } else {echo $param[0];} ?>" onClick="return confirm('Do you want to remove this Sub-Sub-Menu')"><?php echo $result['sub_sub_mn_nm']; ?></td>
                                                                    </tr>
                                                                <?php
                                                                    $s_no++;
                                                                    //echo str_replace('&', 'and', $result['state']);
                                                                }   //for each
                                                                ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            <?php
                                            }   //for each
                                            ?>
                                        </div>
                                        <!-- Report Div Start -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>


<script src="<?= base_url() ?>/assets/plugins/select2/select2.full.min.js"></script>


<script>
    $(document).ready(function() {
        $(function() {
            //Initialize Select2 Elements
            $(".select2").select2();
        });

        $(function() {
            $('.datepick').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true
            });
        });

        var title = "<?php echo (!empty($title)) ? $title : '' ?>";

    });
    $(function() {
    var table = $("#reportTable1").DataTable({
        "responsive": false,
        "lengthChange": false,
        "autoWidth": false,
        "dom": 'Bfrtip',
        "buttons": [
            {
                extend: "excel",
                text: "Export to Excel",
                title: function() {
                    var selectedUser = $("#sect option:selected").text();
                    return "Menu Privileges of the User  " + selectedUser;
                },
                filename: function() {
                    var selectedUser = $("#sect option:selected").text().trim().replace(/[^a-zA-Z0-9]/g, "_");
                    return "Menu Privileges of the User__" + selectedUser;
                }
            },
            {
                extend: "pdfHtml5",
                text: "Export to PDF",
                title: function() {
                    return ""; // We insert it manually in customize
                },
                filename: function() {
                    var selectedUser = $("#sect option:selected").text().trim().replace(/[^a-zA-Z0-9]/g, "_");
                    return "Menu Privileges of the User__" + selectedUser;
                },
                customize: function(doc) {
                    var selectedUser = $("#sect option:selected").text();
                    doc.content.splice(0, 0, {
                        text: "Menu Privileges of the User  " + selectedUser,
                        fontSize: 12,
                        alignment: "center",
                        margin: [0, 0, 0, 12]
                    });
                }
            }
        ]
    });

    table.buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
});


</script>

<script type="text/javascript">
    function printDiv(printable) {
        var printContents = document.getElementById(printable).innerHTML;
        var originalContents = document.body.innerHTML;
        //document.getElementById('header').style.display = 'none';
        // document.getElementById('footer').style.display = 'none';
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }

    function check() {

        var menuList = $('#sect').val();

        if (menuList == "") {
            alert("Please Select User.");
            $('#sect').focus();
            return false;
        } else {
            return true;
        }
    }
</script>