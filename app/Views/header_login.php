<?php error_reporting(2);
//header("X-Frame-Options: DENY");
//header("X-XSS-Protection: 1; mode=block");
//header("X-Content-Type-Options: nosniff");
//header("Strict-Transport-Security: max-age=31536000");
//header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
//header("Cache-Control: post-check=0, pre-check=0", false);
//header("Pragma: no-cache");
Header("set X-Content-Security-Policy default-src * 'self'");

?>
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="Sushant">
  <meta http-equiv="Content-Security-Policy" content="default-src *; style-src 'self' 'unsafe-inline'; script-src 'self' 'unsafe-inline' 'unsafe-eval' http://10.40.186.150:92/">
  <title>Supreme Court of India - Integrated Case Management & Information System</title>

  <link href="<?php echo base_url('assets/vendor/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
  <link href="<?php echo base_url('assets/libs/css/login.css') ?>" rel="stylesheet">
  <link href="<?php echo base_url('assets/vendor/font-awesome/css/font-awesome.css') ?>" rel="stylesheet" type="text/css">
  <script src="<?php echo base_url('assets/vendor/jquery/jquery.min.js'); ?>"></script>
  <script>
    function redirectLogin() {
        if(window.location !== window.parent.location &&  window.location.pathname == "/Login") 
        {
            window.top.location.href = window.location;
        }
    }

    $(document).ready(function() {
      // redirectLogin();
    });
  </script>

</head>

<body class="d-flex flex-column h-100 login-page">

  <header>
    <!-- Top Header Section End -->
    <div class="container	">
      <div class="row">
        <div class="col-md-12">
          <div class="logo_or_name">
            <div class="banner-txts">
              <h6>Integrated Case Management Information System</h6>
              <span>Supreme Court of India</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>
  <!-- Top Header Section End -->

  <section>
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="marque__text">
            <h6>Latest Updates</h6>
            <marquee behavior="scroll" direction="" onmouseover="this.stop();" onmouseout="this.start();" scrollamount="10">
              <ul class="marque_list_content">
                <li><a href="#">E-Committee, Supreme Court of India is launching various applications
                    for the benefit of the litigants and lawyers.</a></li>
                <li><a href="#">Conference on national initiative to reduce pendency and delay in
                    judicial system</a></li>
                <li><a href="#">E-Committee, Supreme Court of India is launching various applications
                    for the benefit of the litigants and lawyers</a></li>
                <li><a href="#">Conference on national initiative to reduce pendency and delay in
                    judicial system</a></li>
                <li><a href="#">E-Committee, Supreme Court of India is launching various applications
                    for the benefit of the litigants and lawyers</a></li>
                <li><a href="#">Conference on national initiative to reduce pendency and delay in
                    judicial</a></li>
              </ul>
            </marquee>
            <button type="button" class="btn_view_al">View all</button>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Begin page content -->
  <main class="mainDiv">
    <div class="container">
      <div class="row">
      <div class="col-12 col-sm-12 col-md-12 col-lg-7 login-banner">
					<div class="login-banner-inner">
						<div class="banimg-sec">
							<img src="<?php echo base_url('assets/images/SCI-banner-2.png') ?>" alt="" class="img-fluid">
						</div>
					</div>
				</div>
        <div class="col-12 col-sm-12 col-md-12 col-lg-5  ps-0">
          <?= $this->renderSection('content') ?>
        </div>
      </div>
    </div>
  </main>

  <footer>
		<!-- Footer Top Section Start -->
		<div class="footer-top-sec">
			<div class="container">
				<div class="row">
					<div class="col-12 col-sm-12 col-md-12 col-lg-12 copyright-sec">
						<p>Content Owned by Supreme Court of India</p>
					</div>
				</div>
			</div>
		</div>
		<!-- Footer Top Section End -->
	</footer>
  <script src="<?php echo base_url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
  <script src="<?php echo base_url('assets/vendor/jquery/jquery.min.js'); ?>"></script>
</body>

</html>