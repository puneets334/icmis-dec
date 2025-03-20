<?php
$profile_photo = "../../../userImage/".$_SESSION['icmis_user_photo'];
if($_SESSION['icmis_user_photo'] == ''){
    $profile_photo = "/images/user.png";
}
?>
                    <div class="image">
                        <img src="<?=base_url().$profile_photo;?>" width="64" height="64" alt="User"/>
                    </div>
                    <div class="info-container" style="top:5px;">
                        <div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <?php
                            /*$sec_name = "select group_concat(us.section_name) section_name from user_sec_map usm
inner join usersection us on us.id = usm.usec
where usm.empid = ".$_SESSION['icmic_empid']." and usm.display = 'Y' and us.display = 'Y'";
                            $sec_name = mysql_query($sec_name);*/



                            $sec_name = rtrim(implode(',', $_SESSION['dcmis_multi_section_name']), ',');



                            ?>
                            <?php
                            echo $_SESSION['emp_name_login'];
                            ?>

                        </div>
                        <div class="email" title="<?= $sec_name; ?>"><?= $_SESSION['dcmis_usertype_name'] . ", " . $sec_name; ?></div>
                        <div class="btn-group user-helper-dropdown">
                            <i class="material-icons" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">keyboard_arrow_down</i>
                            <ul class="dropdown-menu pull-right" style="width:250px !important;">

							<?php
							                                $menu_url = base64_encode('menu_assign/ourPeople.php');
							                                $menu_title = base64_encode('ourPeople');
							                                $unique_id_processed = base64_encode('op');
							                                ?>
							                                <li role="separator" class="divider"></li>
							                                <li>
							                                    <a title="ourPeople" href="<?php echo WEB_ROOT . 'content.php?id='.$unique_id_processed.'&url=' . $menu_url . '&title=' . $menu_title; ?>">
							                                        <i class="material-icons">person</i>ourPeople (Supnet)</a>
							                                    </a>
							                                </li>
							                                <li role="separator" class="divider"></li>


                                <?php
                                $menu_url = base64_encode('menu_assign/userProfile.php');
                                $menu_title = base64_encode('Profile');
                                $unique_id_processed = base64_encode('pr');
                                $is_menu_url_avl = "data-menu-id='".$unique_id_processed."' data-menu-url='".$menu_url."' data-menu-title='".$menu_title."'";
                                ?>
                                <li>
                                    <a class="data_page_open" title="Profile" href="#" <?=$is_menu_url_avl;?> >
                                        <i class="material-icons">person</i>Profile</a>
                                    </a>

                                    <!--<a title="Profile" href="<?php /*echo WEB_ROOT . 'content.php?id='.$unique_id_processed.'&url=' . $menu_url . '&title=' . $menu_title; */?>">
                                        <i class="material-icons">person</i>Profile</a>
                                    </a>-->
                                </li>
                                <li role="separator" class="divider"></li>
                                <li><a href="javascript:void(0);">

                                        <?php
                                        $menu_url = base64_encode('admin/u.php');
                                        $menu_title = base64_encode('Change Password');
                                        $unique_id_processed = base64_encode('a');
                                        $is_menu_url_avl = "data-menu-id='".$unique_id_processed."' data-menu-url='".$menu_url."' data-menu-title='".$menu_title."'";
                                        ?>
                                    <a class="data_page_open"  title="Change Password" href="#" <?=$is_menu_url_avl;?> >
                                        <i class="material-icons">refresh</i>Change
                                        Password</a>
                                    </a>
                                </li>
                                <!--<li><a href="javascript:void(0);"><i class="material-icons">shopping_cart</i>Sales</a></li>
                                <li><a href="javascript:void(0);"><i class="material-icons">favorite</i>Likes</a></li>-->
                                <li role="separator" class="divider"></li>
                                <li><a href="<?php echo WEB_ROOT; ?>?logout"><i class="material-icons">input</i>Sign Out</a></li>
                            </ul>
                        </div>
                    </div>
