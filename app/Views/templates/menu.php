<?php

error_reporting(0);
$class_active_menu = 'class="active"';
$display_url;
use App\Models\Menu_model;
$Menu_model = new Menu_model();
$this->db = \Config\Database::connect();
?>
<style>
    .addClassActive{
     font-weight: bold;
    }
</style>
<ul class="list">
    <?php

    $unique_id = 1;
    session_start();
    $_SESSION['menu_permission'] = 'all';
    $q_usercode = $_SESSION['dcmis_user_idd'];
    $result=$Menu_model->get_Main_menus($q_usercode);
    if (count($result) > 0) {
        foreach ($result as $Main_menus){
            $menu_id=$Main_menus['menu_id'];
            $mheading = ucfirst($Main_menus['menu_nm']);
            $murl = $Main_menus['url'];
            $icon_string = $Main_menus['icon'];
            $old_smenu_id = $Main_menus['old_smenu_id'];
            $menu_url = base64_encode($murl);
            $menu_title = base64_encode($mheading);
            $unique_id_processed = base64_encode($unique_id);

            $activeClass = '';
            if ($display_url == $murl AND $unique_id_processed_active == $unique_id) $activeClass = $class_active_menu;
            $sqrs=$Menu_model->get_sub_menus($q_usercode,$menu_id);
            if ($sqrs > 0) {
                if(strlen($icon_string) > 0 AND $icon_string != 'null'){
                    $icon_string;
                }
                else{
                    $icon_string = "assignment";
                }
                echo '<li class="treeview">
        <a href="javascript:void(0);" class="menu-toggle">
            <i class="material-icons">'.$icon_string.'</i>
            <span>' . $mheading . '</span>
        </a>
        <ul class="ml-menu treeview-menu">';
             foreach ($sqrs as $sm_rows){
                     $smlv1_id = $sm_rows['sml1_id'];
                     $smlv1_heading = ucfirst($sm_rows['menu_nm']);
                     $url_lv1 = $sm_rows['url'];
                     $old_smenu_id_v1 = $sm_rows['old_smenu_id'];
                    $menu_url = base64_encode($url_lv1);
                    $menu_title = base64_encode($smlv1_heading);
                    $unique_id_processed = base64_encode($unique_id);

                    $activeClass = '';
                    if ($display_url == $url_lv1 AND $unique_id_processed_active == $unique_id)
                        $activeClass = $class_active_menu;
                 $sml2_rs=$Menu_model->get_sub_menus_two($q_usercode,$smlv1_id);
            if ($sml2_rs > 0) {

                        echo '<li ' . $activeClass . '>
                <a href="javascript:void(0);" class="menu-toggle">
                    <span>' . $smlv1_heading . '</span>
                </a>
                <ul class="ml-menu treeview-menu">';
                foreach ($sml2_rs as $sml2_rows){
                    $smlv3_id = $sml2_rows['sml2_id'];
                    $smlv3_heading = ucfirst($sml2_rows['menu_nm']);
                    $url_lv3 = $sml2_rows['url'];
                    $old_smenu_id_v3 = $sml2_rows['old_smenu_id'];
                            $menu_url = base64_encode($url_lv3);
                            $menu_title = base64_encode($smlv3_heading);
                            $unique_id_processed = base64_encode($unique_id);

                            $activeClass = '';
                            if ($display_url == $url_lv3 AND $unique_id_processed_active == $unique_id)
                                $activeClass = $class_active_menu;
                    $sml3_rs=$Menu_model->get_sub_menus_three($q_usercode,$smlv3_id);
                    if ($sml3_rs > 0) {

                                echo '<li ' . $activeClass . '>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <span>' . $smlv3_heading . '</span>
                        </a>
                        <ul class="ml-menu treeview-menu">';
                        foreach ($sml3_rs as $sml3_rows){
                            $smlv4_id = $sml3_rows['sml3_id'];
                            $smlv4_heading = ucfirst($sml3_rows['menu_nm']);
                            $url_lv4 = $sml3_rows['url'];
                            $old_smenu_id_v4 = $sml3_rows['old_smenu_id'];
                                    $menu_url = base64_encode($url_lv4);
                                    $menu_title = base64_encode($smlv4_heading);
                                    $unique_id_processed = base64_encode($unique_id);
                                    $activeClass = '';
                                    if ($display_url == $url_lv4 AND $unique_id_processed_active == $unique_id)
                                        $activeClass = $class_active_menu;
                            $sml4_rs=$Menu_model->get_sub_menus_four($q_usercode,$smlv4_id);
                            if ($sml4_rs > 0) {

                                        echo '<li ' . $activeClass . '>
                                <a href="javascript:void(0);" class="menu-toggle">
                                    <span>' . $smlv4_heading . '</span>
                                </a>
                                <ul class="ml-menu treeview-menu">';
                                        foreach ($sml4_rs as $sml4_rows){
                                            $smlv5_id = $sml4_rows['sml4_id'];
                                            $smlv5_heading = ucfirst($sml4_rows['menu_nm']);
                                            $url_lv5 = $sml4_rows['url'];
                                            $old_smenu_id_v5 = $sml4_rows['old_smenu_id'];

                                            $menu_url = base64_encode($url_lv5);
                                            $menu_title = base64_encode($smlv5_heading);
                                            $unique_id_processed = base64_encode($unique_id);
                                            $activeClass = '';
                                            if ($display_url == $url_lv5 AND $unique_id_processed_active == $unique_id)
                                                $activeClass = $class_active_menu;
                                            $sml5_rs=$Menu_model->get_sub_menus_five($q_usercode,$smlv5_id);
                                            if ($sml5_rs > 0) {

                                                echo '<li>
                                        <a href="javascript:void(0);" class="menu-toggle">
                                            <span>' . $smlv5_heading . '</span>
                                        </a>
                                        <ul class="ml-menu treeview-menu">';
                                                foreach ($sml5_rs as $sml5_rows){
                                                    $smlv6_id = $sml5_rows['sml5_id'];
                                                    $smlv6_heading = ucfirst($sml5_rows['menu_nm']);
                                                    $url_lv6 = $sml5_rows['url'];
                                                    $old_smenu_id_v6 = $sml5_rows['old_smenu_id'];
                                                    $menu_url = base64_encode($url_lv6);
                                                    $menu_title = base64_encode($smlv6_heading);
                                                    $unique_id_processed = base64_encode($unique_id);

                                                    $activeClass = '';
                                                    if ($display_url == $url_lv6 AND $unique_id_processed_active == $unique_id)
                                                        $activeClass = $class_active_menu;
                                                    if ($menu_url == '#' OR $menu_url == '') {
                                                        $is_menu_url_avl = "";
                                                    } else {
                                                        //$is_menu_url_avl = WEB_ROOT . "content.php?id=" . $unique_id_processed . "&url=" . $menu_url . "&title=" . $menu_title."&old_smenu=".$old_smenu_id_v6;
                                                        $is_menu_url_avl = "data-menu-id='".$unique_id_processed."' data-menu-url='".$menu_url."' data-menu-title='".$menu_title."' data-menu-old_smenu='".$old_smenu_id_v6."'";
                                                    }
                                                    echo '<li ' . $activeClass . '>
                                                <a class="data_page_open" data-page="' . $unique_id . '/' . $url_lv6 . '" href="#" '. $is_menu_url_avl . '">
                                                    ' . $smlv6_heading . '</a>
                                            </li>';
                                                    $unique_id++;
                                                }
                                                echo '</ul>
                                    </li>';
                                            } else {
                                                if ($menu_url == '#' OR $menu_url == '') {
                                                    $is_menu_url_avl = "";
                                                } else {
                                                   // $is_menu_url_avl = WEB_ROOT . "content.php?id=" . $unique_id_processed . "&url=" . $menu_url . "&title=" . $menu_title."&old_smenu=".$old_smenu_id_v5;
                                                    $is_menu_url_avl = "data-menu-id='".$unique_id_processed."' data-menu-url='".$menu_url."' data-menu-title='".$menu_title."' data-menu-old_smenu='".$old_smenu_id_v5."'";
                                                }
                                                echo '<li ' . $activeClass . '>
                                        <a class="data_page_open" data-page="' . $unique_id . '/' . $url_lv5 . '" href="#" '. $is_menu_url_avl . '">
                                            ' . $smlv5_heading . '</a>
                                    </li>';
                                                $unique_id++;
                                            }


                                        }
                                        echo '</ul>
                            </li>';
                                    } else {
                                        if ($menu_url == '#' OR $menu_url == '') {
                                            $is_menu_url_avl = "";
                                        } else {
                                            //$is_menu_url_avl = WEB_ROOT . "content.php?id=" . $unique_id_processed . "&url=" . $menu_url . "&title=" . $menu_title."&old_smenu=".$old_smenu_id_v4;
                                            $is_menu_url_avl = "data-menu-id='".$unique_id_processed."' data-menu-url='".$menu_url."' data-menu-title='".$menu_title."' data-menu-old_smenu='".$old_smenu_id_v4."'";
                                        }
                                        echo '<li ' . $activeClass . '>
                                <a class="data_page_open" data-page="' . $unique_id . '/' . $url_lv4 . '" href="#" '. $is_menu_url_avl . '">
                                    ' . $smlv4_heading . '</a>
                            </li>';
                                        $unique_id++;
                                    }

                                }
                                echo '</ul>
                    </li>';

                            } else {
                                if ($menu_url == '#' OR $menu_url == '') {
                                    $is_menu_url_avl = "";
                                } else {
                                   // $is_menu_url_avl = WEB_ROOT . "content.php?id=" . $unique_id_processed . "&url=" . $menu_url . "&title=" . $menu_title."&old_smenu=".$old_smenu_id_v3;
                                    $is_menu_url_avl = "data-menu-id='".$unique_id_processed."' data-menu-url='".$menu_url."' data-menu-title='".$menu_title."' data-menu-old_smenu='".$old_smenu_id_v3."'";
                                }
                                echo '<li ' . $activeClass . '>
                        <a class="data_page_open" data-page="' . $unique_id . '/' . $url_lv3 . '" href="#" '. $is_menu_url_avl . '">
                            ' . $smlv3_heading . '</a>
                    </li>';
                                $unique_id++;
                            }

                        }
                        echo '</ul>
            </li>';

                    } else {
                        if ($menu_url == '#' OR $menu_url == '') {
                            $is_menu_url_avl = "";
                        } else {
                            //$is_menu_url_avl = WEB_ROOT . "content.php?id=" . $unique_id_processed . "&url=" . $menu_url . "&title=" . $menu_title."&old_smenu=".$old_smenu_id_v1;
                            $is_menu_url_avl = "data-menu-id='".$unique_id_processed."' data-menu-url='".$menu_url."' data-menu-title='".$menu_title."' data-menu-old_smenu='".$old_smenu_id_v1."'";
                        }

                        echo '<li ' . $activeClass . '>
                    <a class="data_page_open" data-page="' . $unique_id . '/' . $url_lv1 . '" href="#" '. $is_menu_url_avl . '">
                        ' . $smlv1_heading . '</a>
                </li>';
                        $unique_id++;
                    }
                }
                echo '</ul>
    </li>';
            } else {
                if ($menu_url == '#' OR $menu_url == '') {
                    $is_menu_url_avl = "";
                } else {
                    //$is_menu_url_avl = WEB_ROOT . "content.php?id=" . $unique_id_processed . "&url=" . $menu_url . "&title=" . $menu_title."&old_smenu=".$old_smenu_id;
                    $is_menu_url_avl = "data-menu-id='".$unique_id_processed."' data-menu-url='".$menu_url."' data-menu-title='".$menu_title."' data-menu-old_smenu='".$old_smenu_id."'";
                }
            if(strlen($icon_string) > 0 AND $icon_string != 'null'){
                $icon_string;
            }
            else{
                $icon_string = "assignment";
            }
                echo '<li class="treeview" ' . $activeClass . '><a class="data_page_open" data-page="' . $unique_id . '/' . $murl . '" href="#" '. $is_menu_url_avl . '">
                    <i class="material-icons">'.$icon_string.'</i>
                    <span>' . $mheading . '</span>
                </a>
            </li>';
                $unique_id++;
            }
        }

    } else {
        echo '<li><span>You don&#39;t have permission to view menus.</span></li>';
    }
    ?>

</ul>
<script>
    var url = "<?php echo $unique_id_processed_active . '/' . $display_url; ?>";
    // for sidebar menu entirely but not cover treeview
     $('ul.list a').filter(function() {
        return $(this).attr("data-page") == url;
     }).parent().addClass('active');
    // for treeview
    $('ul.treeview-menu a').filter(function () {
        return $(this).attr("data-page") == url;
    }).parentsUntil(".treeview-menu > .treeview").addClass('active');
    
    $(".data_page_open").click(function () {
    //$(this).unbind().click(function () {
        $('.data_page_open').removeClass('addClassActive');
        $(this).addClass('addClassActive');
        var id = $(this).data("menu-id"); 
        var url = $(this).data("menu-url"); 
        var title = $(this).data("menu-title"); 
        var old_smenu = $(this).data("menu-old_smenu"); 
        /*var url_content = "<//?php echo WEB_ROOT; ?>content.php";*/
        var url_content = "<?php echo base_url('Supreme_court/Content');?>";
        //alert(url);
        //console.log(old_smenu);
        $.ajax({
            url: url_content,
            cache: false,
            async: true,
            data: {id: id,url:url,title:title,old_smenu:old_smenu},
            beforeSend: function () {
                $('.main-content').html("<div class='preloader'><div class='spinner-layer pl-red'><div class='circle-clipper left'><div class='circle'></div></div><div class='circle-clipper right'><div class='circle'></div></div></div></div>");
            },
            type: 'POST',
            success: function (data, status) {
                $('.main-content').html(data);
                
                //addClassActive
                
            },
            error: function (xhr) {
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
    //});
});
</script>