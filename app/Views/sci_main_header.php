<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<title>SCI-ICMIS</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body class="hold-transition sidebar-mini layout-fixed" style="overflow: hidden;">
	<div class="wrapper">
		<!-- Navbar -->
		<div class="mngmntHeader">
			<div class="menu-sec">
				<div class="mngmntLogoSection">
					<!-- Menu - Button Start  -->
					<div class="togglemenuSection">
						<button type="button" class="btn btn-sm togglebtn" id="topnav-hamburger-icon">
							<span class="hamburger-icon">
								<span></span>
								<span></span>
								<span></span>
							</span>
						</button>
					</div>
					<!-- Menu - Button Start  -->
					<div class="logo_mobile_">
						<div class="application_nave">
							Integrated Case Management Information System
						</div>
					</div>
				</div>
				<div class="mngmntUserSection">
					<div class="account-details">
						<div class="userInformation">
							<!--userDetail-->
							<div class="userDetail" id="usr-action-btn">
								<div class="userName"> User Login <i class="fas fa-chevron-down"></i>
									<!-- <span class="division">Customer</span> -->
								</div>
								<div class="user-action-sec">
									<ul>
										<li>
											<a href="javascript:void(0)">Profile</a>
										</li>
										<li>
											<a href="<?= base_url('Signout'); ?>">Log Out</a>
										</li>
									</ul>
								</div>
							</div>
							<!--userDetail-->

							<!--userImg-->
							<div class="userImgWrap">
								<div class="userImg">
									<a href="#"><img src="images/user.jpg" alt="Admin" title="admin" width="56"
											height="56"></a>
								</div>
							</div>
							<!--userImg-->
						</div>
						<div class="userInfo">						 
						<a title="Home Page" href="javascript:void(0);" onclick="location.reload();">
								<i class="material-icons" style="font-size:16px;">home</i>
							</a>						
							<!-- <a href="#"><span class="fa fa-envelope-o icon-animated-vertical"></span></a>
							<a href="#"><span class="fa fa-question animated bounceInDown"></span></a>  -->

							<?php
                                $menu_url = base64_encode('/Common/Case_status');
                                $menu_title = base64_encode('Case_status');
                                $unique_id_processed = base64_encode('pr');
                                $is_menu_url_avl = " data-page='/Common/Case_status' data-menu-id='".$unique_id_processed."' data-menu-url='".$menu_url."' data-menu-title='".$menu_title."'";
                                ?>

                        	<a href="javascript:void(0);" <?=$is_menu_url_avl;?> class="profile-lnk link-txt nav-link_ custom_page_open" data-menu-old_smenu="0"><i class="material-icons" style="font-size:16px;">local_library</i></a>


						<!--	<a target="_BLANK" href="<?= base_url() ?>/Common/Case_status"> 
								<i class="material-icons" style="font-size:16px;">local_library</i>
							 </a> -->
							<!-- <a class="data_page_open" type="button" title="Case Status" href="#" id="changeframe">
								<i class="material-icons" style="font-size:16px;">local_library</i>
							</a> -->
							<a href="#" class="bell"><span class="fa fa-bell ringing"></span><span
									class="count">5</span></a>
							<!-- <a	href="login.html" class="signOut"><span class="fa fa-sign-out"></span></a> -->
						</div>

					</div>
				</div>
			</div>
		</div>
