<?php
error_reporting(0);
//$class_active_menu = 'class="active"';
$class_active_menu = 'active';
//$display_url;
use App\Models\Menu_model;
$Menu_model = new Menu_model();
$this->db = \Config\Database::connect();
$unique_id = 1;
session_start();
$_SESSION['menu_permission'] = 'all';
$q_usercode=session()->get('login')['usercode'];
//echo $menu_id; exit();
$uri = current_url(true);
?>

                <!--start programming part of menu list-->
                <?php

                $icon_string = 'fas fa-fill';
                $uri = current_url(true);
                if (!empty($menu_id) && $menu_id !=null) {
                    /*foreach ($result as $Main_menus){
                        $menu_id=$Main_menus['menu_id'];
                        $mheading = ucfirst($Main_menus['menu_nm']);
                        $murl = $Main_menus['url'];
                        $icon_string = $Main_menus['icon'];
                        $old_smenu_id = $Main_menus['old_smenu_id'];
                        $menu_url = base64_encode($murl);
                        $menu_title = base64_encode($mheading);
                        $unique_id_processed = base64_encode($unique_id);

                        $activeClass = '';
                        if ($display_url == $murl AND $unique_id_processed_active == $unique_id) $activeClass = $class_active_menu;*/

                        $sqrs=$Menu_model->get_sub_menus($q_usercode,$menu_id);
                        if ($sqrs > 0) {

                            echo '<li class="nav-item">
                <ul class="nav nav-treeview menu_level_2">';
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

                                    echo '<li class="nav-item">
                        <a href="javascript:void(0);" class="nav-link '.$activeClass.'  menu_level2">
                            <p>' . $smlv1_heading . '<i class="right fas fa-angle-left"></i></p>
                        </a>
                 
                    <ul class="nav nav-treeview menu_level_3">';
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

                                            echo '<li class="nav-item">
                         <a href="javascript:void(0);" class="nav-link '.$activeClass.'  menu_level3">
                            <p>' . $smlv3_heading . '<i class="right fas fa-angle-left"></i></p>
                        </a>

                        <ul class="nav nav-treeview">';
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

                                                    echo '<li class="nav-item">
                         <a href="javascript:void(0);" class="nav-link '.$activeClass.'  menu_level4">
                            <p>' . $smlv4_heading . '<i class="right fas fa-angle-left"></i></p>
                        </a>
                
                            <ul class="nav nav-treeview">';
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

                                                            echo '<li class="nav-item">
                                     <a href="javascript:void(0);" class="nav-link menu_level5">
                                        <p>' . $smlv5_heading . '<i class="right fas fa-angle-left"></i></p>
                                    </a>
                                    <ul class="nav nav-treeview">';
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
                                                                if ($menu_url == 'javascript:void(0);' OR $menu_url == '') {
                                                                    $is_menu_url_avl = "";
                                                                } else {
                                                                    //$is_menu_url_avl = WEB_ROOT . "content.php?id=" . $unique_id_processed . "&url=" . $menu_url . "&title=" . $menu_title."&old_smenu=".$old_smenu_id_v6;
                                                                    $is_menu_url_avl = "data-menu-id='".$unique_id_processed."' data-menu-url='".$menu_url."' data-menu-title='".$menu_title."' data-menu-old_smenu='".$old_smenu_id_v6."'";
                                                                }
                                                                echo '<li class="nav-item '.$activeClass.' " >
                                        <a class="data_page_open open_menu_level" data-page="' . $unique_id . '/' . $url_lv6 . '" href="javascript:void(0);" '. $is_menu_url_avl . '">
                                        ' . $smlv6_heading . '</a>
                                        </li>';
                                                                $unique_id++;
                                                            }
                                                            echo '</ul>
                                </li>';
                                                        } else {
                                                            if ($menu_url == 'javascript:void(0);' OR $menu_url == '') {
                                                                $is_menu_url_avl = "";
                                                            } else {
                                                                // $is_menu_url_avl = WEB_ROOT . "content.php?id=" . $unique_id_processed . "&url=" . $menu_url . "&title=" . $menu_title."&old_smenu=".$old_smenu_id_v5;
                                                                $is_menu_url_avl = "data-menu-id='".$unique_id_processed."' data-menu-url='".$menu_url."' data-menu-title='".$menu_title."' data-menu-old_smenu='".$old_smenu_id_v5."'";
                                                            }
                                                            echo '<li class="nav-item '.$activeClass.' ">
                                <a class="nav-link data_page_open open_menu_level5" data-page="' . $unique_id . '/' . $url_lv5 . '" href="javascript:void(0);" '. $is_menu_url_avl . '">  
                                ' . $smlv5_heading . '</a>
                                </li>';
                                                            $unique_id++;
                                                        }


                                                    }
                                                    echo '</ul>
                            </li>';
                                                } else {
                                                    if ($menu_url == 'javascript:void(0);' OR $menu_url == '') {
                                                        $is_menu_url_avl = "";
                                                    } else {
                                                        //$is_menu_url_avl = WEB_ROOT . "content.php?id=" . $unique_id_processed . "&url=" . $menu_url . "&title=" . $menu_title."&old_smenu=".$old_smenu_id_v4;
                                                        $is_menu_url_avl = "data-menu-id='".$unique_id_processed."' data-menu-url='".$menu_url."' data-menu-title='".$menu_title."' data-menu-old_smenu='".$old_smenu_id_v4."'";
                                                    }
                                                    echo '<li class="nav-item '.$activeClass.' ">
                             <a class="nav-link data_page_open open_menu_level4" data-page="' . $unique_id . '/' . $url_lv4 . '" href="javascript:void(0);" '. $is_menu_url_avl . '">
                            ' . $smlv4_heading . '</a>
                            </li>';
                                                    $unique_id++;
                                                }

                                            }
                                            echo '</ul>
                        </li>';

                                        } else {
                                            if ($menu_url == 'javascript:void(0);' OR $menu_url == '') {
                                                $is_menu_url_avl = "";
                                            } else {
                                                // $is_menu_url_avl = WEB_ROOT . "content.php?id=" . $unique_id_processed . "&url=" . $menu_url . "&title=" . $menu_title."&old_smenu=".$old_smenu_id_v3;
                                                $is_menu_url_avl = "data-menu-id='".$unique_id_processed."' data-menu-url='".$menu_url."' data-menu-title='".$menu_title."' data-menu-old_smenu='".$old_smenu_id_v3."'";
                                            }
                                            echo '<li class="nav-item '.$activeClass.' ">
                        <a class="nav-link data_page_open open_menu_level3" data-page="' . $unique_id . '/' . $url_lv3 . '" href="javascript:void(0);" '. $is_menu_url_avl . '">
                        ' . $smlv3_heading . '</a>
                        </li>';
                                            $unique_id++;
                                        }

                                    }
                                    echo '</ul>
                    </li>';

                                } else {
                                    if ($menu_url == 'javascript:void(0);' OR $menu_url == '') {
                                        $is_menu_url_avl = "";
                                    } else {
                                        //$is_menu_url_avl = WEB_ROOT . "content.php?id=" . $unique_id_processed . "&url=" . $menu_url . "&title=" . $menu_title."&old_smenu=".$old_smenu_id_v1;
                                        $is_menu_url_avl = "data-menu-id='".$unique_id_processed."' data-menu-url='".$menu_url."' data-menu-title='".$menu_title."' data-menu-old_smenu='".$old_smenu_id_v1."'";
                                    }

                                    echo '<li class="nav-item '.$activeClass.' ">
                    <a class="nav-link data_page_open open_menu_level2" data-page="' . $unique_id . '/' . $url_lv1 . '" href="javascript:void(0);" '. $is_menu_url_avl . '">
                    ' . $smlv1_heading . '</a>
                    </li>';
                                    $unique_id++;
                                }
                            }
                            echo '</ul>
            </li>';
                        }
                    //} // by ANshu 17mar23

                } else {
                    echo '<li><span>You don&#39;t have permission to view menus.</span></li>';
                }
                ?>
<script>

    $(".data_page_open").click(function () {
        //$(this).unbind().click(function () {
        var $clickedElement = $(this);
        $('.data_page_open').removeClass('addClassActive');
        $(this).addClass('addClassActive');
        var id = $(this).data("menu-id");
        var url = $(this).data("menu-url");
        var title = $(this).data("menu-title");
        var old_smenu = $(this).data("menu-old_smenu");
        
        //$('.nav-treeview').hide();
       // $(".menu-is-opening").removeClass("menu-is-opening");
       // $(".menu-open").removeClass("menu-open");

       if(old_smenu == 999011)
        {
            window.open('http://10.25.78.107/library', '_blank');
            return false;
        }


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
               
               // $clickedElement.parents('.nav-treeview').show();
                //$clickedElement.parents('.nav-item').addClass("menu-is-opening");
                //$clickedElement.parents('.nav-item').addClass("menu-open");
               
                $('#sci_main_content_view').html(data);
                //$('#cover-spin').hide();

                //addClassActive

            },
            error: function (xhr) {
                updateCSRFToken();
                // alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
        //});
    });
</script>
                <!--end programming part of menu list-->
