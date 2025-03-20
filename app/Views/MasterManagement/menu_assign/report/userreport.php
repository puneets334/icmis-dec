<?= view('header') ?>

<link rel="stylesheet" href="<?php echo base_url('Ajaxcalls/menu_assign/menu_assign.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('Ajaxcalls/menu_assign/style.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('Ajaxcalls/menu_assign/all.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('assets/vendor/fontawesome-free/css/all.min.css'); ?>">

<style> 

label, h2 {
        margin: 12px auto;
        border: 1px solid #ddd;
        display: inline-block;
        padding: 3px 6px;
        border-radius: 5px;
        background: rgb(228, 228, 228);
    }

    .dt-buttons {
        position: absolute;
        margin: -38px 0px 0px 26%;
    }

    .main {
        font-size: 20px !important;
        font-weight: 600 !important;
    }

    span > small {
        margin: auto 8px;
        color: #969696;
        font-weight: 300;
        font-size: 13px;
    }

    .level1 {
        font-size: 17px !important;
        padding-left: 30px !important;
        font-weight: 600;
    }

    .level2 {
        font-size: 16px !important;
        font-weight: 500 !important;
        padding-left: 50px !important;
    }

    .level3 {
        font-size: 14px !important;
        font-weight: 600 !important;
        padding-left: 70px !important;
    }

    .level4 {
        font-size: 13px !important;
        font-weight: 600 !important;
        padding-left: 90px !important;
    }

    .level5 {
        font-size: 11px !important;
        font-weight: 600 !important;
        padding-left: 100px !important;
    }

    .dropdown {
        padding: 0px;
        margin-bottom: 12px;
        box-shadow: 4px 4px 2px 0 #ddd;
    }

    font.url {
        font-weight: 300;
        font-size: 14px;
        color: #545353;
    }

    font.oldsmid {
        margin-left: 0px;
        font-size: 14px;
        font-weight: 300;
    }

    font.oldsmid:before {
        content: '/ ';
        margin-right: 2px;
    }

    .url_id {
        position: absolute;
        left: 65%;
        margin-top: -30px;
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
                                <h3 class="card-title">Master Management >> Menu >> Reports</h3>
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
                                <div class="form-div">
                                    <div class="d-block text-center">




                                     <!-- Main content -->
                                    
                                     <div class="container-fluid">
                                    <div class="row">
                                        <div class="col-md-12 text-center">
                                            <h2>User Report<span class="text-danger font-weight-bold">&#8628;</span></h2>
                                        </div>
                                    </div>
                                     
                                        <form action="<?=base_url()?>/MasterManagement/Menu_assign/MenuUserReport" method="POST"> 
                                        <input type="hidden" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>">
                                        <div class="row">
                                        <div class="col-sm-3">
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="users_list">Search Users:
                                                <select class="form-control e1" id="users_list" name="users_list">
                                                    <option value="">--Select Option--</option>
                                                    <?php foreach ($user_alllist as $item): ?>
                                                     <option value="<?= esc($item['usercode']); ?>" <?php if (isset($UserId) && !empty($UserId) && $UserId == $item['usercode']) echo 'selected'; ?>>
                                                        <?= esc($item['name']); ?>
                                                    </option>
                                                     <?php endforeach; ?>

                                                </select>
                                            </label>
                                        </div>
                                        <div class="col-sm-3">
                                        </div>
                                        </div>
                                        <div class="col-sm-12 mt-4" align="center">
                                            <button type="submit" class="btn btn-info" id="getrolereportbutton">Get Details</button>
                                        </div>
                                        </form>
                                        <hr>
 
                                <?php if (isset($user_list)): ?>
                                <!-- <h2>User Report</h2> -->
                                <div id="example_wrapper" class="dataTables_wrapper dt-bootstrap4">
                                    <div class="row">
                                        <div class="col-md-12"></div>
                                    </div>
                                    <div class="table-responsive">
                                    <table id="example" class="table table-bordered" width="100%">
                                    <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Role Name</th>
                                                    <th>Menu Name<sup>[level]</sup></th>
                                                    <th>URL</th>
                                                    <th>Old Sub Menu ID</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php                               
                                    $dbo = db_connect();                                  
                                    $count = 0;
                                    foreach ($user_list as $key => $rows) {
                                       $count++;
                                        $parentIds = array(); 
                                        echo '<tr>
                                                <td class="text-danger">' . $count . '</td>
                                                <td class="text-success">' . $rows['role_desc'] . '</td>';
                                        
                                        $html = '';
                                        $role_master_id = $rows['id'];
                                        $query =  $models->RoleMenuMapping($role_master_id);
                                                            if ($query->getNumRows() > 0) {
                                                                foreach ($query->getResultArray() as $menus) {
                                                                    $rmenu_id = $menus['menu_id']; 
                                                                    if (checkDisplayForSelfNParents($rmenu_id, $dbo) == false) {
                                                                        continue;  
                                                                    }                               
                                                                    $menu_heading = $menus['menu_nm'];
                                                                    $rmenu_parent_id = getParentId($rmenu_id);
                                                                    if (!in_array($rmenu_parent_id, $parentIds) && $rmenu_parent_id != '') {
                                                                    
                                                                        $html .= getAllParentMenuRows($rmenu_id, $dbo,$parentIds);
                                                                        
                                                                        $parentIds = array_unique(array_merge($parentIds, getAllParentId($rmenu_id)));
                                                                    }
                                                                    $submenuids = array();
                                                                    $html .= getAllSubMenuRows($rmenu_id, $menu_heading, $menus['url'], $menus['old_smenu_id'], $dbo,$submenuids);
                                                                    unset($submenuids);
                                                                }
                                                                $html = trimTags($html);  
                                                                echo $html;  
                                                                echo '</tr>';
                                                            } else {
                                                                echo '<td class="text-danger">No Menu Found</td>
                                                                    </tr>';
                                                            }
                                                            unset($parentIds); 
                                                        }

                                                        $dbo = null; 
                                                        ?>

                                                    </tbody>
                                                </table>
                                            <?php endif; ?>

                                            <?php if (isset($menu_html)): ?>
                                                <h2>Menu Structure</h2>
                                                <table>
                                                    <tbody>
                                                        <?= $menu_html; ?>
                                                    </tbody>
                                                </table>
                                            <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php      
                                 
                                        function trimTags($html)
                                        {
                                            return substr(trim($html), 22, strlen($html) - 27);
                                        }

                                        function getParentId($rmenu_id)
                                        {
                                        
                                            return substr($rmenu_id, 0, strlen($rmenu_id) - 2);
                                        }

                                        function getAllParentId($rmenu_id)
                                        {
                                            $parIdArr = array();
                                            while (strlen($rmenu_id) > 2) {
                                                $rmenu_id = getParentId($rmenu_id);
                                                array_push($parIdArr, $rmenu_id);
                                            }

                                            return $parIdArr;
                                        }
                                       
                                        function checkDisplayForSelfNParents($curmenu_id, $dbo){
                                            $masterMenu = new \App\Models\MasterManagement\MenuReportModel;
                                            $parentArr = getAllParentId($curmenu_id);
                                            array_push($parentArr,$curmenu_id);
                                            foreach ($parentArr as $rmenu_id) {
                                                $query = $masterMenu->getMasterMenu($rmenu_id);
                                                $data = $query->getRow();
                                                if ($data) {
                                                    if ($data->display === 'Y') {
                                                        continue;
                                                    } else {
                                                        return false;
                                                    }
                                                }
                                            }
                                            return true;
                                        }
                                    

                                        function getAllParentMenuRows($rmenu_id, $dbo,$parentIds)
                                        {
                                            $mastermenumodels = new \App\Models\MasterManagement\MenuReportModel;
                                            if (strlen($rmenu_id) < 4) {
                                                return '';
                                            }

                                            $parentMenuRow = '';
                                            $rmenu_id = getParentId($rmenu_id);
                                        
                                            if (in_array($rmenu_id, $parentIds)) {
                                                return '';
                                            }
                                                                               
                                            $class = getClassforMenuRow($rmenu_id);                                      
                                            $query = $mastermenumodels->getAllParentMenuRowID($rmenu_id);
                                            $results = $query->getResultArray();                 
                                            if ($results) {
                                                foreach ($results as $key=>$menus) {
                                                    $old_smenu_id = $menus['old_smenu_id'];
                                                    $url = $menus['url'];
                                                    $menu_heading = $menus['menu_nm'];
                                                    $formatted = formatDisplayHeading($menu_heading, $class);
                                        
                                                    $parentMenuRow .= getAllParentMenuRows($rmenu_id, $dbo, $parentIds);

                                                    $parentMenuRow .= '<tr>';
                                                    if ($key >= 0) {
                                                        $parentMenuRow .= '<td></td><td></td>';
                                                    }
                                                    $parentMenuRow .= '<td>' . $formatted . '</td>';
                                                    $parentMenuRow .= '<td><font class="url">' . esc($url) . '</font></td>';
                                                    $parentMenuRow .= '<td><font class="oldsmid">' . esc($old_smenu_id) . '</font></td>';
                                                    $parentMenuRow .= '</tr>';
                                                    
                                                }
                                            }
                                            
                                            return $parentMenuRow;
                                        }
                                        

                                        function getAllSubMenuRows($rmenu_id, $menu_heading, $url, $old_smenu_id,$dbo,$submenuids)
                                        {

                                            $mastermenumodels = new \App\Models\MasterManagement\MenuReportModel;

                                            if (strlen($rmenu_id) === 12) {
                                                return '';
                                            }
                                        
                                            $class = getClassforMenuRow($rmenu_id);
                                            $formatted = formatDisplayHeading($menu_heading, $class);
                                         
                                            $html = '<tr><td></td><td></td><td>' . $formatted . '</td>
                                                        <td><font class="url">' . esc($url) . '</font></td>
                                                        <td><font class="oldsmid">' . esc($old_smenu_id) . '</font></td>
                                                        </tr>';

                                            $query = $mastermenumodels->getallSubMenuRowsID($rmenu_id);
                                            $results = $query->getResultArray();
                                        
                                            if ($results) {
                                                foreach ($results as $menus) {
                                                    $old_smenu_id = $menus['old_smenu_id'];
                                                    $url = $menus['url'];
                                                    $menu_heading = $menus['menu_nm'];
                                                    $new1_rmenu_id = $menus['menu_id'];
                                                    $new2_rmenu_id = substr($new1_rmenu_id, 0, strlen($rmenu_id) + 2);
                                        
                                                    if (strcmp($rmenu_id . "00", $new2_rmenu_id) === 0) {
                                                        continue;
                                                    }
                                        
                                                    if (!checkDisplayForSelfNParents($new2_rmenu_id, $dbo)) {
                                                        continue;
                                                    }
                                        
                                                    if (!in_array($new2_rmenu_id, $submenuids)) {
                                                        $html .= getAllSubMenuRows($new2_rmenu_id, $menu_heading, $url, $old_smenu_id, $dbo, $submenuids);
                                                        array_push($submenuids, $new2_rmenu_id);
                                                    }
                                                }
                                                return $html;
                                            } else {
                                                return '';
                                            }
                                        }


                                        function getClassforMenuRow($rmenu_id)
                                        {
                                           
                                            $class = '';
                                            switch (strlen($rmenu_id)) {
                                                case  2:
                                                    $class = 'text-danger main';
                                                    break;
                                                case  4:
                                                    $class = 'text-success level1';
                                                    break;
                                                case  6:
                                                    $class = 'text-primary level2';
                                                    break;
                                                case  8:
                                                    $class = 'text-warning level3';
                                                    break;
                                                case 10:
                                                    $class = 'text-info level4';
                                                    break;
                                                case 12:
                                                    $class = 'text-secondary level5';
                                                    break;
                                            }
                                            return $class;
                                        }

                                        function formatDisplayHeading($menu_heading, $class){
                                            $formatted ='';
                                            $level = trim(substr($class, strpos($class, ' ')));
                                            switch ($level){
                                                case 'level1':
                                                    $formatted = '<span class="'.$class.'">'.'-- '.$menu_heading.'<sup>[1]</sup></span>';
                                                    break;
                                                case 'level2':
                                                    $formatted = '<span class="'.$class.'">'.'------ '.$menu_heading.'<sup>[2]</sup></span>';
                                                    break;
                                                case 'level3':
                                                    $formatted = '<span class="'.$class.'">'.'--------- '.$menu_heading.'<sup>[3]</sup></span>';
                                                    break;
                                                case 'level4':
                                                    $formatted = '<span class="'.$class.'">'.'------------ '.$menu_heading.'<sup>[4]</sup></span>';
                                                    break;
                                                case 'level5':
                                                    $formatted = '<span class="'.$class.'">'.'--------------- '.$menu_heading.'<sup>[5]</sup></span>';
                                                    break;
                                                default:
                                                    $formatted = '<span class="'.$class.'">'.$menu_heading.'<sup>[' . $level . ']</sup></span>';
                                            }
                                            return $formatted;
                                        }
                                    ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>

<script src="<?= base_url('/Ajaxcalls/menu_assign/menu_assign.js') ?>"></script>
<script src="<?=base_url()?>/assets/plugins/datatables/pdfmake.min.js"></script>
<script src="<?=base_url()?>/assets/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?=base_url()?>/assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?=base_url()?>/assets/plugins/datatables/dataTables.buttons.min.js"></script>
<script src="<?=base_url()?>/assets/plugins/datatables/buttons.print.min.js"></script>
<script src="<?=base_url()?>/assets/plugins/datatables/buttons.html5.min.js"></script>
<script src="<?=base_url()?>/assets/plugins/datatables/buttons.html5.min.js"></script>
<script src="<?=base_url()?>/js/select2.min.js"></script>
  <!-- Include Select2 and DataTables JS/CSS -->
 
<script>

$(document).ready(function () {
    $(".e1").select2();
});

$(document).ready(function() {
    var table = $('#example').DataTable({
        scrollY: 400,               // Set the vertical scroll height
        paging: false,              // Disable paging
        ordering: false,            // Disable column ordering
        buttons: ['copy', 'excel', 'pdf', 'colvis', 'print'] // Specify buttons
    });
 
    table.buttons().container()
        .appendTo('#example_wrapper .col-md-12:eq(0)');
});


$(document).ready(function() {
    $("form").on("submit", function() {
        $("#whole_page_loader").fadeIn();
    });
});



</script>
