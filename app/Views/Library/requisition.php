<?= view('header') ?>

<style>
.dropdown-content {
    display: none;
    position: absolute;
    background-color: #f6f6f6;
    width: 35vw;
    overflow: auto;
    border: 1px solid #ddd;
    z-index: 1;
}

.dropdown-content a {
    color: black;
    padding: 12px 16px;
    text-decoration: none;
    display: block;
}

.dropdown-content a:hover {
    background-color: #ddd;
}

.card-header {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.card-header img {
    margin-bottom: 10px;
}
</style>

<section class="content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header heading">
                        <a href="index">
                            <img src="<?= base_url('images/scilogo.png') ?>" alt="Supreme Court Logo" class="img-fluid" />
                        </a>
                        <h3 class="card-title">Judges' Library</h3>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($LoginError)) : ?>
                            <div class="form-group text-center">
                                <div class="alert alert-danger">
                                    <?= esc($LoginError['message']); ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <form method="post" name="frmusrLogin" id="frmusrLogin">
                            <?= csrf_field() ?>
                            <div class="form-group">
                                <label for="role_id">Select Role</label>
                                <select class="form-control" name="role_id" id="role_id">
                                    <option value="">Select Role</option>
                                    <?php if (!empty($listRole)) : ?>
                                        <?php foreach ($listRole as $role) : ?>
                                            <option value="<?= esc($role['role_id']); ?>">
                                                <?= esc($role['role_name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>

                            <div id="role_courtAssistant" style="display:none;">
                                <div class="form-group"> 
                                    <label for="court_number">Select Court</label>
                                    <select id="court_number" name="court_number" class="form-control">
                                        <option value="">Select Court</option> 
                                        
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="court_bench">Select Bench</label>
                                    <select id="court_bench" name="court_bench" class="form-control" style="visibility: hidden;">
                                        <option value="">Select Bench</option> 
                                       
                                    </select>
                                </div>

                                <div class="form-group" id="other_user_div" style="display:none">
                                    <label for="user_name_other">User Name</label>
                                    <input type="text" class="form-control" placeholder="User Name" name="user_name_other" id="user_name_other">
                                </div>
                            </div>

                            <div class="text-center">
                            <button   onclick="validateForm();return false;" class="btn btn-primary btn-block">Click</button>
                            </div>
                        </form>
                    </div> <!-- end card-body -->
                </div> <!-- end card -->
            </div> <!-- end col-md-6 -->
        </div> <!-- end row -->
    </div> <!-- end container-fluid -->
</section>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    
});


function validateForm() {
    var role = $("#role_id").val();

    if (role === '') {
        alert("Please select a role");
        $("#role_id").focus();
        return false;
    }

    var user_name, pass, court_number, user_name_a, user_name_other;

    // // Gather the necessary input based on the selected role
    // if (role === '5') { // LIBRARIAN
    //     user_name = $("#user_name").val();
    //     pass = $("#user_password").val();
    // } else if (role === '6') { // ADMIN
    //     user_name = $("#user_name_admin").val();
    //     pass = $("#user_password").val();
    // } else if (role === '7') { // ADVOCATE
    //     user_name = $("#user_name_adv").val();
    //     pass = $("#user_password").val();
    // } else if (role === '4') { // COURT ASSISTANT
    //     court_number = $("#court_number").val();
    //     user_name_a = $("#user_name_a").val();
    //     user_name_other = $("#user_name_other").val();

    //     if (court_number === '') {
    //         alert("Please enter court Number");
    //         $("#court_number").focus();
    //         return false;
    //     }
    //     if (user_name_a === '') {
    //         alert("Please enter username");
    //         $("#user_name_a").focus();
    //         return false;
    //     }
    //     if (user_name_a === "Other" && user_name_other === '') {
    //         alert("Please enter Other user Name");
    //         $("#user_name_other").focus();
    //         return false;
    //     }
    // } else {
    //     alert("Invalid role selected");
    //     return false;
    // }

    // // Ensure username and password are entered for roles 5, 6, and 7
    // if ((role === '5' || role === '6' || role === '7') && (!user_name || !pass)) {
    //     alert(!user_name ? "Please enter username" : "Please enter password");
    //     return false;
    // }

    var CSRF_TOKEN = 'CSRF_TOKEN';
    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val(); // Get the current CSRF token

    // Prepare data for AJAX call
    var data = {
        role_id: role,
        user_name: user_name,
        user_password: pass,
        court_number: court_number,
        user_name_a: user_name_a,
        user_name_other: user_name_other,
        CSRF_TOKEN: CSRF_TOKEN_VALUE 
    };

    $.ajax({
        url: '<?php echo base_url('Library/Requisition/frmusrLogin');?>',
        type: 'POST',
        dataType: "json",
        data: data,
        success: function (response) {
            if (response.status === 'Success') {
                switch (role) {
                    case '5':
                    case '6':
                        window.location.href = 'view_court_requisition.php';
                        break;
                    case '4':
                        window.location.href = '<?=base_url('Library/Requisition/court_dashboard');?>';
                        break;
                    case '7':
                        window.location.href = 'advocate_dashboard.php';
                        break;
                }
            } else {
                alert(response.msg);
                if (response.new_csrf_token) {
                    // Update CSRF token if the server responds with a new one
                    $('[name="CSRF_TOKEN"]').val(response.new_csrf_token);
                }
            }
        },
        error: function () {
            alert("Failure");
        }
    });

    return false; // Prevent default form submission
}





</script>
<script>
show_role_div('')
	$('#role_id').children('option:not(:selected)').prop('disabled', true);

	if('' == '4'){
		$("#court_number").val('').trigger("change");
		$('#court_number').children('option:not(:selected)').prop('disabled', true);
	}


</script>
