<?= $this->extend('header_login') ?>
<?= $this->section('content') ?>
<?php  //echo $_SESSION["captcha"];
$attribute = array('class' => 'login-s-inner', 'name' => 'sign_in', 'id' => 'sign_in','accept-charset'=>'utf-8', 'autocomplete' => 'off' ,'onsubmit'=>'enableSubmit();');
echo form_open(base_url('Login/checkLogin/'), $attribute);
?>
    <span class="alert-danger"><?=\Config\Services::validation()->listErrors()?></span>

<?php  if(session()->has("message_error")){ ?>
        <div class="alert alert-danger">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <?=session("message_error")?>
        </div>
<?php } else if(session()->has("message_success")){ ?>
    <div class="alert alert-success">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <?=session("message_success")?>
    </div>
    <?php } else{?>
    <!-- <span class="login01-form-title my-3 primary-text">Sign in to start your session</span> -->
    <?php }?>



    <div class=" login-section">
						<div class="httxt">
							<h4>Login</h4>
						</div>
						<div class="loin-form">
						<form action="">
							<div class="row">
								<div class="col-12">
									<div class="mb-3">
										<label for="" class="form-label">User Name</label>
										<input class="form-control input01 cus-form-ctrl" type="number" id="txtuname" name="txtuname" autocomplete="off" placeholder="<?= env('Userid') ?>">
										<?php if (isset($validation) && $validation->getError('txtuname')) : ?>
											<span class="text-danger"><?= $validation->getError('txtuname') ?></span>
										<?php endif; ?>
									</div>
								</div>
								<div class="col-12">
            <div class="mb-3">
                <label for="" class="form-label">Password</label>
                <input class="form-control input01 cus-form-ctrl" type="password" id="txtpass" name="txtpass" placeholder="<?= env('Password') ?>">
                <input type="hidden" id="txtpass_hashed" name="txtpass_hashed" value=""> <!-- Hidden field -->
                <?php if (isset($validation) && $validation->getError('txtpass')) : ?>
                    <span class="text-danger"><?= $validation->getError('txtpass') ?></span>
                <?php endif; ?>
            </div>
        </div>
							</div>

							<div class="row">
								<div class="col-12">
									<button class="login01-form-btn border-0" type="submit">LOGIN</button>
								</div>
							</div>
						</form>
						</div>
					</div>

<!--</form>-->
    <script src="<?= base_url('assets/libs/js/sha256.js'); ?>" type="text/javascript"></script>
<?= form_close() ?>
    <script>
       function enableSubmit() {
    var password = $('[name="txtpass"]').val(); // Get the user entered password
    // If password is not empty, hash it and store in the hidden field
    if (password != '') {
        var hashedPassword = sha256(password) + '<?= $_SESSION['login_salt'] ?>';
        $('#txtpass_hashed').val(hashedPassword); // Set the hashed password to the hidden field
        $('[name="txtpass"]').val(password); // Ensure the password input remains the same for the user
    }
}

    </script>
<?= $this->endSection() ?>