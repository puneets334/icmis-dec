<?= view('header') ?>
<style>
        div.dataTables_wrapper div.dataTables_filter label {
        display: flex;
        justify-content: end;align-items:center
    }

    div.dataTables_wrapper div.dataTables_filter label input.form-control {
        width: auto !important;
        padding: 4px;
    }
</style>
<link rel="stylesheet" href="<?php echo base_url('Ajaxcalls/menu_assign/menu_assign.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('Ajaxcalls/menu_assign/style.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('Ajaxcalls/menu_assign/all.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('assets/vendor/fontawesome-free/css/all.min.css'); ?>">
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">

                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Master Management >> Menu List</h3>
                            </div>
                            <div class="col-sm-2"> </div>
                        </div>
                    </div>
                    <input type="hidden" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>">
                    <!--start menu programming-->
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12"> <!-- Right Part -->
                                <div class=" ">

                                    <div class="d-block text-center">
                                        <!--<span class="btn btn-danger">Add Menus/ Child</span>-->

                                        <div class="alert alert-success hide" role="alert" id="msgDiv">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            <strong></strong>
                                        </div>


                                        <div id="loginbox" class="mainbox my-4">

                                            <div class="panel panel-info form-box" id="addMenusDiv">
                                                <div class="panel-heading">
                                                    <div class="panel-title">
                                                       <span class="form-center-title">Create <span class="text-danger">[ Menus > Childs ]</span></span> 
                                                        <span class="menusList">
                                                            <a href="#" id="menusList" class="quick-btn"><i class="fa fa-list">&nbsp;</i>Menus list</a>
                                                            <a href="#" id="usersList" class="quick-btn gray-btn" ><i class="fa fa-users">&nbsp;</i>Users list</a>
                                                        </span>
                                                    </div>
                                                </div>

                                                <div class="panel-body">

                                                    <div class="alert hide"></div>

                                                    <form id="addNewMenus" class="form-horizontal p-3" autocomplete="off">
                                                        <div class="row no-padding text-left moveDiv hide">
                                                            <div class="col-md-6 col-offset-md-6"></div>
                                                        </div>
                                                        <?php
                                                        /*$query="select menu_nm,substr(menu_id,1,2),url as menu_id from menu where substr(menu_id,3)='0000000000' AND display='Y' AND menu_id is not null order by priority;";
                                                        $get_menus_rs=$dbo->prepare($query);
                                                        $get_menus_rs->execute();*/
                                                        ?>
                                                        <div class="row no-margin">

                                                            <div class="col-md-2">
                                                                <div class="input-group" style="margin-left: 0px !important;">
                                                                    <select id="smlv1" name="menu" class="form-control" required>
                                                                        <option value="">Select Menu</option>
                                                                        <option value="00addNew" class="text-danger">Add New</option>
                                                                        <?php
                                                                        foreach ($get_menus_rs as $rows) {
                                                                            echo '<option value="' . trim($rows['substr']) . '">' . trim($rows['menu_nm']) . '</option>';
                                                                        } ?>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-2 smlv2 hide">
                                                                <div class="input-group">
                                                                    <select id="smlv2" name="child1" class="form-control"></select>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-2 smlv3 hide">
                                                                <div class="input-group">
                                                                    <select id="smlv3" name="child2" class="form-control"></select>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-2 smlv4 hide">
                                                                <div class="input-group">
                                                                    <select id="smlv4" name="child3" class="form-control"></select>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-2 smlv5 hide">
                                                                <div class="input-group">
                                                                    <select id="smlv5" name="child4" class="form-control"></select>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-2 smlv6 hide">
                                                                <div class="input-group">
                                                                    <select id="smlv6" name="child5" class="form-control"></select>
                                                                </div>
                                                            </div>

                                                        </div>

                                                        <div class="row no-margin">

                                                            <div class="col-12 col-md-4">
                                                                <div class="input-group">
                                                                    <label class="form-label">Caption</label>
                                                                    <input id="caption" name="caption" type="text" class="form-control" value="" placeholder="Enter caption" required>
                                                                </div>
                                                            </div>

                                                            <div class="col-12 col-md-4">
                                                                <div class="input-group">
                                                                    <label class="form-label">URL</label>
                                                                    <input id="url" name="url" type="text" class="form-control" value="" placeholder="Enter URL if any">
                                                                </div>
                                                            </div>

                                                            <div class="col-12 col-md-4">
                                                                <div class="input-group">
                                                                    <label class="form-label">Priority</label>
                                                                    <input id="priority" name="priority" type="number" class="form-control" placeholder="1 - 99" pattern="[0-9]{1,2}" maxlength="2" required>
                                                                </div>
                                                            </div>

                                                            <div class="col-12 col-md-4">
                                                                <div class="input-group">
                                                                    <label class="form-label">Old Submenu ID</label>
                                                                    <input id="oldsmid" name="oldsmid" type="number" class="form-control" placeholder="1 - 9999" pattern="[0-9]{1,4}" maxlength="4">
                                                                </div>
                                                            </div>

                                                            <div class="col-12 col-md-4 hide">
                                                                <div class="input-group">
                                                                    <label class="form-label">Display</label>
                                                                    <input id="display" name="display" type="character" class="form-control" value="Y" placeholder="Y or N" maxlength="1" pattern="[YNyn]{1,1}" required>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row no-margin">

                                                            <div class="col-md-12">
                                                                <div class="center-buttons">
                                                                    <input type="submit" class="btn btn-success btn-block_" value="Create">

                                                                    <input type="reset" class="btn btn-warning btn-block_" value="Clear/ Reset">
                                                                    <!-- <button type="submit" id="addNewMenus" class="quick-btn" value="Create">Create</button> -->
                                                                    <!-- <button type="reset" class="quick-btn gray-btn" value="Clear/ Reset">Clear/ Reset</button> -->
                                                                </div>
                                                            </div>

                                                          <!--  <div class="col-md-offset-4 col-md-4">
                                                                <div class="form-group no-margin">
                                                                    <input type="reset" class="btn btn-warning btn-block" value="Clear/ Reset">
                                                                </div>
                                                            </div> -->

                                                        </div>

                                                    </form>



                                                </div>
                                            </div>

                                            <div class="panel panel-success hide form-box" id="listUsersDiv">
                                                <div class="panel-heading">
                                                    <div class="panel-title">
                                                        <span class="form-center-title">
                                                            List of [ <span class="fa fa-users text-danger"> Users </span> ]
                                                        </span>    
                                                        <span class="menusList">
                                                            <a href="#" id="addMenus" class="quick-btn"><i class="fa fa-plus">&nbsp;</i>Add Menus</a>
                                                            <a href="#" id="menusList" class="quick-btn gray-btn"><i class="fa fa-list">&nbsp;</i>Menus List</a>
                                                        </span>
                                                    </div>
                                                </div>

                                                <div style="margin-top: 10px; position: relative;" class="panel-body">

                                                    <div class="text-success msg hide">
                                                        <i class="fa fa-check"></i>
                                                        <span></span>
                                                    </div>
                                                    <div class="table-responsive dataTables_wrapper dt-bootstrap4 datatablereport_user_wrapper_dataTable">
                                                        <input type="hidden" value="" id="multiSelect">
                                                        <table id="example" class="table table-striped text-left datatablereport_user custom-table" style="width:100%">
                                                            <thead>
                                                                <tr>
                                                                    <th>#</th>
                                                                    <th><b class="text-success small">Multi Select</b>&nbsp;&nbsp;/ Name&nbsp;&nbsp;<small class="text-danger">[EMP ID]</small></th>
                                                                    <th>Type</th>
                                                                    <th>Section</th>
                                                                    <th style="max-width: 20%;">Action & Permission allotment</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php $count = 1;
                                                                foreach ($action_permission_allotment as $data) {

                                                                    /* $query='SELECT a.usercode,a.name,a.display,a.empid,b.type_name,c.section_name,a.attend FROM users a left join usertype b on a.usertype=b.id left join usersection c on a.section=c.id where a.display="Y" order by a.usercode;';
                                                            $rs=$dbo->prepare($query);
                                                            $rs->execute();
                                                            while ($data=$rs->fetch(PDO::FETCH_NUM, PDO::FETCH_ORI_NEXT)) {*/

                                                                    $menu_url = base64_encode('addUsers.php');
                                                                    $menu_title = base64_encode('User Modify');
                                                                    $unique_id_processed = base64_encode('2222');

                                                                    $crnAction = '<form action="addUsers.php" id="userUpdate" method="post" style="display: inline;" target="_self">
								        				<input type="hidden" value="' . $data['usercode'] . '" name="userId" />
								        				<div class="fa fa-user activated text-success">
								        				</div>
								        			</form><a href="javascript:void(0)" class="fa fa-trash text-danger" id="uy" data-id="' . $data['usercode'] . '">&nbsp;</a>';

                                                                    if ($data['attend'] == 'A' || $data['attend'] == 'a') {
                                                                        $crnAction = '<form action="addUsers.php" method="post" id="userUpdate" style="display: inline;" target="_self">
								        				<input type="hidden" value="' . $data['usercode'] . '" name="userId" />
								        				<div class="fa fa-user de-activated text-danger">
								        				
								        				</div>
								        			</form><a href="javascript:void(0)" class="fa fa-undo text-success" id="un" data-id="' . $data['usercode'] . '">&nbsp;</a>';
                                                                    }

                                                                    echo '<tr>
									        					<td>' . $count . '</td>
									        					<td><input type="checkbox" value="' . $data['usercode'] . '" name="selectUser" id="' . $count . '" />&nbsp;&nbsp;<label for="' . $count . '">' . $data['name'] . '&nbsp;&nbsp;<small class="text-danger">[' . $data['empid'] . ']</small></label></td>
									        					<td>' . $data['type_name'] . '</td>
									        					<td>' . $data['section_name'] . '</td>
									        					<td style="max-width: 20%; text-align:center;">' . $crnAction . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a addMenushref="javascript: void(0);" class="fa fa-edit text-info" id="upermisn" data-id="' . $data['usercode'] . '"></a></td>
									        			  </tr>';
                                                                    $count++;
                                                                }
                                                                ?>
                                                            </tbody>
                                                        </table>

                                                    </div>

                                                </div>
                                            </div>

                                            <div class="panel panel-success hide" id="listMenusDiv">
                                                <div class="panel-heading">
                                                    <div class="panel-title">
                                                    <span class="form-center-title">
                                                        List of [ <span class="fa fa-list text-danger"> Menus </span> ]
                                                    </span>    
                                                        <span class="menusList">
                                                            <a href="#" id="addMenus" class="quick-btn"><i class="fa fa-plus">&nbsp;</i>Add Menus</a>
                                                            <a href="#" id="usersList" class="quick-btn"><i class="fa fa-users">&nbsp;</i>Users List</a>
                                                        </span>
                                                    </div>
                                                </div>

                                                <div style="margin-top: 10px" class="panel-body">

                                                    <div class="table-responsive dataTables_wrapper dt-bootstrap4 query_builder_wrapper_dataTable ">
                                                        <table id="mlist" class="table table-striped custom-table datatablereport" style="width:100%">
                                                            <thead>
                                                                <tr>
                                                                    <th>#</th>
                                                                    <th>Heading&nbsp;&nbsp;<small class="text-danger">[Priority]</small></th>
                                                                    <th>URL</th>
                                                                    <th>Old Id</th>
                                                                    <th style="max-width: 20%;">Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                $count = 1;
                                                                /* $query='SELECT * from menu where menu_id is not null and display="Y" order by substr(menu_id,1,2),substr(menu_id,3,2),substr(menu_id,5,2),substr(menu_id,7,2),substr(menu_id,9,2),substr(menu_id,11,2),priority;';
                                                            $rs=$dbo->prepare($query);
                                                            $rs->execute();
                                                            while ($data=$rs->fetch(PDO::FETCH_BOTH, PDO::FETCH_ORI_NEXT)) {*/
                                                                foreach ($menu_list as $data) {
                                                                    $crnAction = '<span class="activated text-success hide">Display : Y</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" class="fa fa-trash text-danger" id="my" data-id="' . $data['id'] . '">&nbsp;</a>';
                                                                    if ($data['display'] == 'N' || $data['display'] == 'n')
                                                                        $crnAction = '<span class="de-activated text-danger hide">Display : N</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" class="fa fa-undo text-success" id="mn" data-id="' . $data['id'] . '">&nbsp;</a>';

                                                                    $mId = $data['menu_id'];
                                                                    if (substr($mId, 2) == '0000000000')
                                                                        $micon = 'text-danger menu';
                                                                    elseif (substr($mId, 4) == '00000000' && substr($mId, 2, 2) != '00')
                                                                        $micon = 'text-success level1';
                                                                    elseif (substr($mId, 6) == '000000' && substr($mId, 4, 2) != '00')
                                                                        $micon = 'text-primary level2';
                                                                    elseif (substr($mId, 8) == '0000' && substr($mId, 6, 2) != '00')
                                                                        $micon = 'text-warning level3';
                                                                    elseif (substr($mId, 10) == '00' && substr($mId, 8, 2) != '00')
                                                                        $micon = 'text-info level4';
                                                                    elseif (substr($mId, 10) != '00')
                                                                        $micon = 'text-secondary level5';

                                                                    $miconArray = explode(' ', $micon);

                                                                    echo '<tr>
                                    <td>' . $count . '</td>
                                    <td class="text-left"><span class="' . $micon . '">' . $data['menu_nm'] . '</span>&nbsp;&nbsp;<span id="' . $miconArray[1] . '">[' . $miconArray[1] . ']</span>&nbsp;&nbsp;<small class="text-danger">[' . $data['priority'] . ']</small></td>
                                    <td>' . $data['url'] . '</td>
                                    <td>' . $data['old_smenu_id'] . '</td>
                                    <td style="max-width: 20%;">' . $crnAction . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript: void(0);" class="fa fa-edit text-info" id="editMenu"  data-id="' . $data['id'] . '"></a></td>
                                  </tr>';
                                                                    $count++;
                                                                }
                                                                ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <!-- for get role list -->
                                                    <!-- <div class="table-responsive">
										<table id="mlist1" class="table table-striped table-bordered" style="width:100%">
									        <thead>
									            <tr>
									                <th>#</th>
									                <th>Role Description</th>
									                <th>Menu Name</th>
									                <th>URL</th>
									                <th>Old Id</th>
									            </tr>
									        </thead>
									        <tbody>
									        <?php
                                            $count = 1;
                                            //$query='SELECT * from menu where menu_id is not null and display="Y" order by substr(menu_id,1,2),substr(menu_id,3,2),substr(menu_id,5,2),substr(menu_id,7,2),substr(menu_id,9,2),substr(menu_id,11,2),priority;';
                                            /* $query='SELECT a.role_desc,c.menu_nm,c.url,c.old_smenu_id,c.menu_id FROM role_master a, role_menu_mapping b, menu c where a.id=b.role_master_id AND (b.menu_id=substr(c.menu_id,1,2) OR b.menu_id=substr(c.menu_id,1,4) OR b.menu_id=substr(c.menu_id,1,6) OR b.menu_id=substr(c.menu_id,1,8)) AND c.display="Y" order by a.role_desc,c.menu_id;';
                                                    $rs=$dbo->prepare($query);
                                                    $rs->execute();
                                                    while ($data=$rs->fetch(PDO::FETCH_BOTH, PDO::FETCH_ORI_NEXT)) {*/
                                            foreach ($role_master_list as $data) {
                                                $crnAction = '<span class="activated text-success hide">Display : Y</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" class="fa fa-trash text-danger" id="my" data-id="' . $data['role_desc'] . '">&nbsp;</a>';
                                                if ($data['display'] == 'N' || $data['display'] == 'n')
                                                    $crnAction = '<span class="de-activated text-danger hide">Display : N</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" class="fa fa-undo text-success" id="mn" data-id="' . $data['role_desc'] . '">&nbsp;</a>';

                                                $mId = $data['menu_id'];
                                                if (substr($mId, 2) == '0000000000')
                                                    $micon = 'text-danger menu';
                                                elseif (substr($mId, 4) == '00000000' && substr($mId, 2, 2) != '00')
                                                    $micon = 'text-success level1';
                                                elseif (substr($mId, 6) == '000000' && substr($mId, 4, 2) != '00')
                                                    $micon = 'text-primary level2';
                                                elseif (substr($mId, 8) == '0000' && substr($mId, 6, 2) != '00')
                                                    $micon = 'text-warning level3';
                                                elseif (substr($mId, 10) == '00' && substr($mId, 8, 2) != '00')
                                                    $micon = 'text-info level4';
                                                elseif (substr($mId, 10) != '00')
                                                    $micon = 'text-secondary level5';

                                                echo '<tr>
									        					<td>' . $count . '</td>
									        					<td class="text-left">' . $data['role_desc'] . '</td>
									        					<td class="text-left"><span class="' . $micon . '">' . $data['menu_nm'] . '</span></td>
									        					<td>' . $data['url'] . '</td>
									        					<td>' . $data['old_smenu_id'] . '</td>
									        			  </tr>';
                                                $count++;
                                            }
                                            ?>
									        </tbody>
									    </table>
									</div> -->

                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <!-- Modal -->
                        <div class="modal fade" id="loadMe" tabindex="-1" role="dialog" aria-labelledby="loadMeLabel">
                            <div class="modal-dialog modal-sm" role="document">
                                <div class="modal-content">
                                    <div class="modal-body text-center">
                                        <div class="loader"></div>
                                        <div class="loader-txt text-success">
                                            Details fetching.....
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <!-- Role Allotment modal -->
                        <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" id="menuPerm" aria-labelledby="loadMeLabel">
                            <div class="modal-dialog modal-lg">

                                <div class="modal-content">
                                    <div class="modal-body">
                                    <h5 class="modal-title">Menu List</h5>
                                    <form id="menu_perm" action="#">
                                        <input type="hidden" value="" id="usercode" />
                                        <div class="table-responsive" style="max-height: 450px;">
                                            <table class="table table-striped">
                                                <!-- <thead>
                                                    <tr>
                                                        <td colspan="2">
                                                            <div class="btn btn-info text-center btn-block font-size-30">Menu List</div>
                                                        </td>
                                                    </tr>
                                                </thead> -->
                                                <tbody id="menuTbody">
                                                </tbody>
                                            </table>
                                        </div>
                                        <!-- <div class="col-md-4"><input type="submit" value="Update" class="btn btn-success btn-block" id="action"></div>
                                        <div class="col-md-4"><input type="reset" value="Clear/ Reset" class="btn btn-warning btn-block"></div>
                                        <div class="col-md-4"><button type="button" class="btn btn-danger btn-block" data-dismiss="modal">Close</button></div> -->
                                        <div class="center-buttons">
                                        <button type="submit" value="Update" class="quick-btn" id="action">Update</button>
                                        <button type="reset" value="Clear/ Reset" class="quick-btn gray-btn">Clear/ Reset</button>
                                        <button type="button" class="quick-btn gray-btn" data-dismiss="modal">Close</button>
                                    </div>
                                    </form>
                                    </div>
                                            <!-- <div class="modal-footer">
                                        
                                        </div> -->
                                </div>
                                
                            </div>
                        </div>




                        <!--end menu programming-->

                    </div>
                </div>
            </div>
        </div>
</section>
<script src="<?= base_url('/Ajaxcalls/menu_assign/menu_assign.js') ?>"></script>
<script>
    $(function() {
        $(".datatablereport_user").DataTable({
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
        }).buttons().container().appendTo('.datatablereport_user_wrapper_dataTable .col-md-6:eq(0)');

    });
    $(function() {
        $(".datatablereport").DataTable({
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
        }).buttons().container().appendTo('.query_builder_wrapper_dataTable .col-md-6:eq(0)');

    });
</script>