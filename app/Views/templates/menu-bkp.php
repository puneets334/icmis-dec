<?php
//require_once './menu_assign/config.php';
echo view('templates/menu_assign/config');
$class_active_menu = 'class="active"';
$display_url;
?>
<style>
    .addClassActive{
     font-weight: bold;
    }

    
</style>
<ul class="list">
    <!--    <li class="header">MAIN NAVIGATION</li>
    <li <?= $display_url == "dashboard/home.php" ? $class_active_menu : ""; ?> >
        <a class="data_page_open" href="<?php echo WEB_ROOT; ?>index.php" data-page="home.php">
            <i class="material-icons">home</i>
            <span>Home</span>
        </a>
    </li>-->
    <?php

    $unique_id = 1;
    session_start();
    $_SESSION['menu_permission'] = 'all';
    $q_usercode = $_SESSION['dcmis_user_idd'];
    /*$sql_per = "select substr(m.menu_id,1,2) as main_menu_id,substr(m.menu_id,3,2) as sub_menu_id,
m.menu_id,m.icon,m.old_smenu_id from user_role_master_mapping urmm inner join role_master rm on urmm.role_master_id=rm.id
inner join role_menu_mapping rmm on rm.id=rmm.role_master_id inner join menu m on m.menu_id like CONCAT(rmm.menu_id, '%')
where urmm.display='Y' and rm.display='Y' and rmm.display='Y' and m.display='Y' and urmm.usercode=$q_usercode order by m.priority";

    $mperm_ids = '00';
    if (isset($_SESSION['menu_permission'])) {
        $mperm_ids = $_SESSION['menu_permission'];
        unset($_SESSION['menu_permission']);
    }
    if ($mperm_ids == 'all') $in = '';
    else {
        $ids_array = explode(',', $mperm_ids);
        foreach ($ids_array as $ids) {
            $in .= "'" . $ids . "',";
        }
        $in = 'AND substr(menu_id,1,2) IN (' . rtrim($in, ',') . ')';
    }*/

    //$query = "select menu_nm,substr(menu_id,1,2),url as menu_id, icon, old_smenu_id from menu where substr(menu_id,3)='0000000000' AND display = 'Y' and menu_id is not null $in order by priority;";
 echo $query = "select distinct m.menu_nm,substr(m.menu_id,1,2),m.url as menu_id, m.icon, m.old_smenu_id from user_role_master_mapping urmm inner join role_master rm on urmm.role_master_id=rm.id
inner join role_menu_mapping rmm on rm.id=rmm.role_master_id inner join menu m on m.menu_id like 
 CONCAT(substr(rmm.menu_id,1,2), '%') 
where substr(m.menu_id,3)='0000000000' and m.menu_id is not null and urmm.display='Y' and rm.display='Y' and rmm.display='Y' and m.display='Y' 
and urmm.usercode=$q_usercode order by m.menu_nm";
  exit();  $rs = $dbo->prepare($query);
    $rs->execute();
    //var_dump($rs);
    if ($rs->rowCount() > 0) {
        while ($Main_menus = $rs->fetch(PDO::FETCH_BOTH, PDO::FETCH_ORI_NEXT)) {
            $menu_id = $Main_menus[1];
            $mheading = ucfirst($Main_menus[0]);
            $murl = $Main_menus[2];
            $icon_string = $Main_menus[3];
            $old_smenu_id = $Main_menus[4];

            $menu_url = base64_encode($murl);
            $menu_title = base64_encode($mheading);
            $unique_id_processed = base64_encode($unique_id);

            $activeClass = '';
            if ($display_url == $murl AND $unique_id_processed_active == $unique_id) $activeClass = $class_active_menu;
            //$squery = "select menu_nm,substr(menu_id,1,4) as sml1_id,url, old_smenu_id from menu where substr(menu_id,5)='00000000' AND substr(menu_id,1,2)=? AND substr(menu_id,3,2) <>'00' AND menu_id is not null order by priority, substr(menu_id,1,4);";

            $squery = "select distinct m.menu_nm,substr(m.menu_id,1,4) as sml1_id,m.url, m.old_smenu_id from user_role_master_mapping urmm inner join role_master rm on urmm.role_master_id=rm.id
                    inner join role_menu_mapping rmm on rm.id=rmm.role_master_id inner join menu m on 
                    m.menu_id like CONCAT(substr(rmm.menu_id,1,4), '%') 
                    # CONCAT(rmm.menu_id, '%')
                    where substr(m.menu_id,5)='00000000' AND substr(m.menu_id,1,2)=? AND substr(m.menu_id,3,2) <>'00' and m.menu_id is not null and urmm.display='Y' and rm.display='Y' and rmm.display='Y' and m.display='Y' 
                    and urmm.usercode=$q_usercode order by m.menu_nm";

            $sqrs = $dbo->prepare($squery);
            $sqrs->bindParam(1, $menu_id, PDO::PARAM_STR);
            $sqrs->execute();
            if ($sqrs->rowCount() > 0) {
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

                while ($sm_rows = $sqrs->fetch(PDO::FETCH_BOTH, PDO::FETCH_ORI_NEXT)) {
                    $smlv1_id = $sm_rows[1];
                    $smlv1_heading = ucfirst($sm_rows[0]);
                    $url_lv1 = $sm_rows[2];
                    $old_smenu_id_v1 = $sm_rows[3];

                    $menu_url = base64_encode($url_lv1);
                    $menu_title = base64_encode($smlv1_heading);
                    $unique_id_processed = base64_encode($unique_id);

                    $activeClass = '';
                    if ($display_url == $url_lv1 AND $unique_id_processed_active == $unique_id)
                        $activeClass = $class_active_menu;

                    //$sml2_query = "select menu_nm,substr(menu_id,1,6) as sml2_id,url, old_smenu_id from menu where substr(menu_id,7)='000000' AND substr(menu_id,1,4)=? AND substr(menu_id,5,2) <>'00' AND menu_id is not null order by priority, substr(menu_id,1,4);";

                    $sml2_query = "select distinct m.menu_nm,substr(m.menu_id,1,6) as sml2_id,m.url, m.old_smenu_id from user_role_master_mapping urmm inner join role_master rm on urmm.role_master_id=rm.id
                    inner join role_menu_mapping rmm on rm.id=rmm.role_master_id inner join menu m on m.menu_id 
                    like CONCAT(substr(rmm.menu_id,1,6), '%')
                     # CONCAT(rmm.menu_id, '%')
                    where substr(m.menu_id,7)='000000' AND substr(m.menu_id,1,4)=? AND substr(m.menu_id,5,2) <>'00' 
                    AND m.menu_id is not null and urmm.display='Y' and rm.display='Y' and rmm.display='Y' and m.display='Y' 
                    and urmm.usercode=$q_usercode order by m.menu_nm";

                    $sml2_rs = $dbo->prepare($sml2_query);
                    $sml2_rs->bindParam(1, $smlv1_id, PDO::PARAM_STR);
                    $sml2_rs->execute();

                    if ($sml2_rs->rowCount() > 0) {

                        echo '<li ' . $activeClass . '>
                <a href="javascript:void(0);" class="menu-toggle">
                    <span>' . $smlv1_heading . '</span>
                </a>
                <ul class="ml-menu treeview-menu">';

                        while ($sml2_rows = $sml2_rs->fetch(PDO::FETCH_BOTH, PDO::FETCH_ORI_NEXT)) {
                            $smlv3_id = $sml2_rows[1];
                            $smlv3_heading = ucfirst($sml2_rows[0]);
                            $url_lv3 = $sml2_rows[2];
                            $old_smenu_id_v3 = $sml2_rows[3];

                            $menu_url = base64_encode($url_lv3);
                            $menu_title = base64_encode($smlv3_heading);
                            $unique_id_processed = base64_encode($unique_id);

                            $activeClass = '';
                            if ($display_url == $url_lv3 AND $unique_id_processed_active == $unique_id)
                                $activeClass = $class_active_menu;

                            //$sml3_query = "select menu_nm,substr(menu_id,1,8) as sml3_id,url, old_smenu_id from menu where substr(menu_id,9)='0000' AND substr(menu_id,1,6)=? AND substr(menu_id,7,2) <>'00' AND menu_id is not null order by priority, substr(menu_id,1,4);";

                            $sml3_query = "select distinct m.menu_nm,substr(m.menu_id,1,8) as sml3_id,m.url, m.old_smenu_id from user_role_master_mapping urmm inner join role_master rm on urmm.role_master_id=rm.id
                            inner join role_menu_mapping rmm on rm.id=rmm.role_master_id inner join menu m on m.menu_id 
                            like CONCAT(substr(rmm.menu_id,1,8), '%') 
                            # CONCAT(rmm.menu_id, '%')                            
                            where substr(m.menu_id,9)='0000' AND substr(m.menu_id,1,6)=? AND substr(m.menu_id,7,2) <>'00' AND m.menu_id is not null and urmm.display='Y' and rm.display='Y' and rmm.display='Y' and m.display='Y' and urmm.usercode=$q_usercode order by m.menu_nm";

                            $sml3_rs = $dbo->prepare($sml3_query);
                            $sml3_rs->bindParam(1, $smlv3_id, PDO::PARAM_STR);
                            $sml3_rs->execute();

                            if ($sml3_rs->rowCount() > 0) {

                                echo '<li ' . $activeClass . '>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <span>' . $smlv3_heading . '</span>
                        </a>
                        <ul class="ml-menu treeview-menu">';

                                while ($sml3_rows = $sml3_rs->fetch(PDO::FETCH_BOTH, PDO::FETCH_ORI_NEXT)) {
                                    $smlv4_id = $sml3_rows[1];
                                    $smlv4_heading = ucfirst($sml3_rows[0]);
                                    $url_lv4 = $sml3_rows[2];
                                    $old_smenu_id_v4 = $sml3_rows[3];

                                    $menu_url = base64_encode($url_lv4);
                                    $menu_title = base64_encode($smlv4_heading);
                                    $unique_id_processed = base64_encode($unique_id);

                                    $activeClass = '';
                                    if ($display_url == $url_lv4 AND $unique_id_processed_active == $unique_id)
                                        $activeClass = $class_active_menu;

                                    //$sml4_query = "select menu_nm,substr(menu_id,1,10) as sml4_id,url, old_smenu_id from menu where substr(menu_id,11)='00' AND substr(menu_id,1,8)=? AND substr(menu_id,9,2) <>'00' AND menu_id is not null order by priority, substr(menu_id,1,4);";

                                    $sml4_query = "select distinct m.menu_nm,substr(m.menu_id,1,10) as sml4_id,m.url, m.old_smenu_id from user_role_master_mapping urmm inner join role_master rm on urmm.role_master_id=rm.id
                            inner join role_menu_mapping rmm on rm.id=rmm.role_master_id inner join menu m 
                            on m.menu_id like CONCAT(substr(rmm.menu_id,1,10), '%') 
                            # CONCAT(rmm.menu_id, '%')
                            where substr(m.menu_id,11)='00' AND substr(m.menu_id,1,8)=? AND substr(m.menu_id,9,2) <>'00' 
                            AND m.menu_id is not null and urmm.display='Y' and rm.display='Y' and rmm.display='Y' 
                            and m.display='Y' and urmm.usercode=$q_usercode order by m.menu_nm";

                                    $sml4_rs = $dbo->prepare($sml4_query);
                                    $sml4_rs->bindParam(1, $smlv4_id, PDO::PARAM_STR);
                                    $sml4_rs->execute();

                                    if ($sml4_rs->rowCount() > 0) {

                                        echo '<li ' . $activeClass . '>
                                <a href="javascript:void(0);" class="menu-toggle">
                                    <span>' . $smlv4_heading . '</span>
                                </a>
                                <ul class="ml-menu treeview-menu">';

                                        while ($sml4_rows = $sml4_rs->fetch(PDO::FETCH_BOTH, PDO::FETCH_ORI_NEXT)) {
                                            $smlv5_id = $sml4_rows[1];
                                            $smlv5_heading = ucfirst($sml4_rows[0]);
                                            $url_lv5 = $sml4_rows[2];
                                            $old_smenu_id_v5 = $sml4_rows[3];

                                            $menu_url = base64_encode($url_lv5);
                                            $menu_title = base64_encode($smlv5_heading);
                                            $unique_id_processed = base64_encode($unique_id);

                                            $activeClass = '';
                                            if ($display_url == $url_lv5 AND $unique_id_processed_active == $unique_id)
                                                $activeClass = $class_active_menu;

                                            //$sml5_query = "select menu_nm,substr(menu_id,1,12) as sml5_id,url, old_smenu_id from menu where substr(menu_id,1,10)=? AND substr(menu_id,11,2) <>'00' AND menu_id is not null order by priority, substr(menu_id,1,4);";

                                            $sml5_query = "select distinct m.menu_nm,substr(m.menu_id,1,12) as sml5_id,m.url, m.old_smenu_id from user_role_master_mapping urmm inner join role_master rm on urmm.role_master_id=rm.id
                                            inner join role_menu_mapping rmm on rm.id=rmm.role_master_id inner join menu m 
                                            on m.menu_id like CONCAT(substr(rmm.menu_id,1,12), '%') 
                                            # CONCAT(rmm.menu_id, '%')
                                            where substr(m.menu_id,1,10)=? AND substr(m.menu_id,11,2) <>'00' AND m.menu_id is not null and urmm.display='Y' 
                                            and rm.display='Y' and rmm.display='Y' and m.display='Y' and urmm.usercode=$q_usercode 
                                            order by m.menu_nm";

                                            $sml5_rs = $dbo->prepare($sml5_query);
                                            $sml5_rs->bindParam(1, $smlv5_id, PDO::PARAM_STR);
                                            $sml5_rs->execute();

                                            if ($sml5_rs->rowCount() > 0) {

                                                echo '<li>
                                        <a href="javascript:void(0);" class="menu-toggle">
                                            <span>' . $smlv5_heading . '</span>
                                        </a>
                                        <ul class="ml-menu treeview-menu">';

                                                while ($sml5_rows = $sml5_rs->fetch(PDO::FETCH_BOTH, PDO::FETCH_ORI_NEXT)) {

                                                    $smlv6_id = $sml5_rows[1];
                                                    $smlv6_heading = ucfirst($sml5_rows[0]);
                                                    $url_lv6 = $sml5_rows[2];
                                                    $old_smenu_id_v6 = $sml5_rows[3];

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
        var url_content = "<?php echo WEB_ROOT; ?>content.php";
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