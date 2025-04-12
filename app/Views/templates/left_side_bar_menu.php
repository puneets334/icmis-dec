<?php
// Disable error reporting
error_reporting(0);

use App\Models\Menu_model;

$MenuModel = new Menu_model();

$this->db = \Config\Database::connect();
$unique_id = 1;
session_start();
$_SESSION['menu_permission'] = 'all';
$q_usercode = session()->get('login')['usercode'];
$result = $MenuModel->get_Main_menus($q_usercode);
$uri = current_url(true);
?>



<div class="content">
    <!-- Side Panel Starts -->



    <aside class="main-sidebar sidebar-dark-primary elevation-4 sidePanel hide">

        <div class="sidebar p-0">
            <!-- User Panel -->
            <div class="user-panel mt-3 pb-3 mb-3">

                <div class="menu-close-sec">
                    <a href="javascript:void(0)" class="main-menu-close">X</a>
                </div>
                <div class="menu-profile-sec">
                    <div class="profile-img">
                        <img src="images/user.jpg" alt="">
                    </div>
                    <div class="profile-info">
                        <h6><?= !empty(session()->get('login')['type_name']) ? ucfirst(strtolower(session()->get('login')['name'])) . ' [' . session()->get('login')['type_name'] . ']' : ucfirst(strtolower(session()->get('login')['name'])) ?> <a href="javascript:void(0)" class="profile-link link-txt"><span class="mdi mdi-circle-edit-outline"></span></a></h6>
                        
                        <?php
                                $menu_url = base64_encode('/MasterManagement/Menu_assign/userProfile');
                                $menu_title = base64_encode('Profile');
                                $unique_id_processed = base64_encode('pr');
                                $is_menu_url_avl = " data-page='/MasterManagement/Menu_assign/userProfile' data-menu-id='".$unique_id_processed."' data-menu-url='".$menu_url."' data-menu-title='".$menu_title."'";
                                ?>

                        <a href="#" <?=$is_menu_url_avl;?> class="profile-lnk link-txt nav-link_ custom_page_open" data-menu-old_smenu="0">User Profile</a>
                        <!-- <a href="<?= base_url('Signout'); ?>" class="d-block">
                            <i class="fa fa-power-off" style="font-size:20px;color:red"></i>
                        </a> -->
                    </div>
                
                </div>



            </div>

            <!-- Sidebar Menu -->
            <nav class="mt-2 menu  mean-nav" id="nav_menu">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <?php if (!empty($result) && $result != null) : ?>
                        <?php foreach ($result as $Main_menus) : ?>
                            <?php
                            $menu_id = $Main_menus['menu_id'];
                            $mheading = ucfirst($Main_menus['menu_nm']);
                            $murl = $Main_menus['url'];
                            $menu_url = base64_encode($murl);
                            $menu_title = base64_encode($mheading);
                            $unique_id_processed = base64_encode($unique_id);

                            // Use a default icon if none is provided
                            $icon_string = !empty($Main_menus['icon']) ? $Main_menus['icon'] : 'assignment';
                            $activeClass = ($display_url == $murl && $unique_id_processed_active == $unique_id) ? $class_active_menu : '';
                            $sqrs = $MenuModel->get_sub_menus($q_usercode, $menu_id);
                            ?>

                            <?php if ($sqrs > 0) : ?>
                                <li class="nav-item menu-open_stop menu_level1 menu_level_bg_<?= $menu_id; ?>">
                                    <a href="javascript:void(0);" id="link_open_<?= $menu_id; ?>" class="nav-link link_click" data-link-id="<?= $menu_id; ?>">

                                        <i class="fa fa-<?= htmlspecialchars($icon_string); ?>"></i>
                                        <p><?= $mheading; ?> <i class="right fas fa-angle-left"></i></p>
                                    </a>
                                    <ul class="nav nav-treeview menu_level_1" id="div_left_side_bar_sub_menu_<?= $menu_id; ?>"></ul>
                                </li>
                            <?php else : ?>
                                <li class="nav-item <?= $activeClass; ?>">
                                    <a class="data_page_open nav-link" data-page="<?= $unique_id . '/' . $murl; ?>" href="#" data-menu-id="<?= $unique_id_processed; ?>" data-menu-url="<?= $menu_url; ?>" data-menu-title="<?= $menu_title; ?>" data-menu-old_smenu="<?= $Main_menus['old_smenu_id']; ?>">
                                        <i class="fa fa-<?= htmlspecialchars($icon_string); ?>"></i>
                                        <span><?= $mheading; ?></span>
                                    </a>
                                </li>
                            <?php endif; ?>

                            <?php $unique_id++; ?>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <li><span>You don&#39;t have permission to view menus.</span></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </aside>


</div>

<script>
    $(document).ready(function() {
        $("#topnav-hamburger-icon").click(function() {
            $('.hamburger-icon').toggleClass("hide");
            $('.sidePanel').toggleClass("hide");
            $('.mainPanel').toggleClass("hide");
            $('.sidePanel').show("500");
        });
        $(".main-menu-close").click(function() {
            $('.sidePanel').removeClass("hide");
            $('.sidePanel').hide("500");

        });
    });


    $(".link_click").click(function() {
        var id = $(this).data("link-id");
        var already_selected_menu = $('#sci_left_side_level_first').val();
        var url_content = "<?= base_url('Supreme_court/Content/get_sub_menu_list'); ?>";

        var isOpened = $(".menu_level_bg_" + id).hasClass("menu-is-opening menu-open");
        
        if(!isOpened){
            $(".menu_level_1").html('');
            $(".menu-is-opening").removeClass("menu-is-opening");
            $(".menu-open").removeClass("menu-open");
           
            $.ajax({
                url: url_content,
                cache: false,
                async: true,
                data: {
                    id: id
                },
                type: 'GET',
                beforeSend: function() {
                    $('#main_content').html("<div class='preloader'><div class='spinner-layer pl-red'><div class='circle-clipper left'><div class='circle'></div></div><div class='circle-clipper right'><div class='circle'></div></div></div></div>");
                },
                success: function(data) {
                    $(".menu_level_bg_" + id).addClass("menu-is-opening menu-open");
                    $("#div_left_side_bar_sub_menu_" + id).html(data);

                    if (already_selected_menu == 99999) {
                        $('#sci_left_side_level_first').val(id);
                        $("#div_left_side_bar_sub_menu_" + id).css("display", "block");
                        $(".menu_level_2").css("display", "block");
                    } else if (already_selected_menu == id) {
                        $('#sci_left_side_level_first').val(99999);
                        $("#div_left_side_bar_sub_menu_" + id).css("display", "none");
                        $(".menu_level_2").css("display", "none");
                        $(".menu-is-opening").removeClass("menu-is-opening");
                        $(".menu-open").removeClass("menu-open");
                    } else {
                        $('#sci_left_side_level_first').val(99999);
                        $("#div_left_side_bar_sub_menu_" + id).css("display", "block");
                        $(".menu_level_2").css("display", "block");
                    }

                    updateCSRFToken();
                },
                error: function(xhr) {
                    updateCSRFToken();
                }
            });
        }
            
    });

    var url = "<?= $unique_id_processed_active . '/' . $display_url; ?>";
    $('ul.nav-item a').filter(function() {
        return $(this).attr("data-page") == url;
    }).parent().addClass('active');

    $('ul.nav-treeview a').filter(function() {
        return $(this).attr("data-page") == url;
    }).parentsUntil(".nav-sidebar > .nav-item").addClass('menu-open').prev('a').addClass('active');
</script>

<!-- // script for sidebar open and adjust width  -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var toggleButton = document.querySelector('.togglebtn');
        var sidePanel = document.querySelector('.sidePanel');
        var mainPanel = document.querySelector('.mainPanel');
       var application_nave = document.querySelector('.application_nave');
        var closeButton = document.querySelector('.main-menu-close'); // Select the close button

        toggleButton.addEventListener('click', function() {
            var screenWidth = window.innerWidth;
            if (sidePanel.style.display === 'none' || sidePanel.style.display === '') {
                sidePanel.style.display = 'block';
                if (screenWidth >= 1140) { // lg and xl screens
                    mainPanel.style.marginLeft = '300px';
                    application_nave.style.marginLeft = '200px';
                    mainPanel.style.width = 'calc(100% - 300px)';
                } else if (screenWidth >= 768 && screenWidth < 1140) { // md screens
                    mainPanel.style.marginLeft = '0';
                    application_nave.style.marginLeft = '0';
                    mainPanel.style.width = '100%';
                } else { // sm and xs screens
                    mainPanel.style.marginLeft = '0';
                    application_nave.style.marginLeft = '0';
                    mainPanel.style.width = '100%';
                }
            } else {
                sidePanel.style.display = 'none';
                mainPanel.style.marginLeft = '0';
                application_nave.style.marginLeft = '0';
                mainPanel.style.width = '100%';
            }
        });

        closeButton.addEventListener('click', function() {
            sidePanel.style.display = 'none'; // Hide the side panel
            mainPanel.style.marginLeft = ''; // Remove the margin-left style
            application_nave.style.marginLeft = '';
            mainPanel.style.width = ''; // Remove the width style
        });
    });



    $(document).on("click", ".custom_page_open", function () {
        
        $('.data_page_open').removeClass('addClassActive');
        //$(this).addClass('addClassActive');
        var id = $(this).data("menu-id");
        var url = $(this).data("menu-url");
        var title = $(this).data("menu-title");
        var old_smenu = $(this).data("menu-old_smenu");

        if(url == 'L0NvbW1vbi9DYXNlX3N0YXR1cw==')
        {
            var application_nave = document.querySelector('.application_nave');
            $('.hamburger-icon').toggleClass("hide");
            $('.sidePanel').toggleClass("hide");
            $('.mainPanel').toggleClass("hide");
            $('.sidePanel').show("500");
            application_nave.style.marginLeft = '200px';
        }

        $(".menu-is-opening").removeClass("menu-is-opening");
        $(".menu-open").removeClass("menu-open");
        $('.nav-treeview').hide();

        /*var url_content = "<//?php echo WEB_ROOT; ?>content.php";*/
        var url_content = "<?php echo base_url('Supreme_court/Content');?>";
        //alert('idwww='+id +'url='+url+'title='+title+'old_smenu='+old_smenu);
        // alert('url='+url);
        //console.log(old_smenu);
        // window.location.href = '<?php echo base_url('/'); ?>'+url;
        // return false;
        //$('#cover-spin').show();
        $('#main_content').html('');
        $.ajax({
            url: url_content,
            cache: false,
            async: true,
            data: {id: id,url:url,title:title,old_smenu:old_smenu},
            beforeSend: function () {
                $('#main_content').html("<div class='preloader'><div class='spinner-layer pl-red'><div class='circle-clipper left'><div class='circle'></div></div><div class='circle-clipper right'><div class='circle'></div></div></div></div>");
            },
            type: 'GET',
            success: function (data, status) {
                //alert(data);
                //window.location.href =data;
                updateCSRFToken();
                $('#sci_main_content_view').html(data);
                
            },
            error: function (xhr) {
                updateCSRFToken();
                // alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
        //});
    });

</script>
<!-- // End script for sidebar open and adjust width  -->