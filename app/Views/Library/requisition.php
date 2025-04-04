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

                        <form  method="post" name="frmusrLogin" id="frmusrLogin">
                        <?= csrf_field() ?>    
                                <input type="hidden" name="mode" id="mode" value="login">
                                <input type="hidden" name="token" id="token" value="<?php echo session()->get('token'); ?>">
                        <div class="input-group mb-3">
                                       
                                        <select class="form-control" name="role_id" id="role_id" >
                                        <option value=""> Select Role</option>
                                        <?php
                                        foreach ($listRole as $result) { ?> 
                                           
                                            <option value="<?php echo $result['role_id']; ?>" <?php if(session()->get('role_id') == $result['role_id'])echo "selected"; ?> > <?php echo $result['role_name'] ?></option>

                                        <?php } ?>
                                        </select> 
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                    </div>
                                </div>
                                </div>
                               

                                <div id="role_admin" style="display:none;">

                                         
                                        

                                </div> 


                                <div id="role_librarian" style="display:none;">

                                         
                                        

                                </div> 

                                <div id="role_Advocate" style="display:none;">

                                         
                                        

                                </div> 




                                    <div id="role_courtAssitant"  style="display:none;">

                                        <div class="input-group mb-3"> 
                                        <select id="court_number" name="court_number" class="form-control" >
                                        <option value="">Select Court</option> 
                                        <?php foreach($requisitions as $result) {?> 
                                            <option value="<?php echo $result['requisition_dep_name'];?>" <?= (session()->get('court_number') == $result['requisition_dep_name']) ? "selected" : '' ?>  ><?php echo $result['requisition_dep_name'];?></option>
                                        <?php }?>
                                        </select>
                                        </div>


                                        <div class="input-group mb-3">
                                        <select id="court_bench" name="court_bench" class="form-control" style="visibility: hidden;" >
                                        <option value="">Select Bench</option> 
                                        <?php

                                        for($i=1;$i<=15;$i++){					
                                        ?> 			
                                        <option value="<?php echo $i;?>" <?= $i == 2 ? 'selected' : '' ?> ><?php echo $i;?></option> 
                                        <?php }?>
                                        </select>
                                        </div>


                                         


				<div class="input-group mb-3" id="other_user_div" style="display:none">
				<input type="text" class="form-control" placeholder="User Name" name="user_name_other" id="user_name_other" >
				<div class="input-group-append">
				<div class="input-group-text">
				<span class="fas fa-envelope"></span>
				</div>
				</div>
				</div>


			</div> 

			 
   
          
          <div class="col-4">
              <button type="button"   onclick="validateForm();" class="btn btn-primary btn-block">Click</button>
            <br>
          </div>
           
    
      </form>
                    </div> <!-- end card-body -->
                </div> <!-- end card -->
            </div> <!-- end col-md-6 -->
        </div> <!-- end row -->
    </div> <!-- end container-fluid -->
</section>

<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
<script src="<?php echo base_url();?>/requisition/requistion.js">   </script>
 
<script>
show_role_div('<?= session()->get('role_id') ?>')
	$('#role_id').children('option:not(:selected)').prop('disabled', true);

	if('<?= session()->get('role_id') ?>' == '4'){
		$("#court_number").val('<?= session()->get('court_number') ?>').trigger("change");
		$('#court_number').children('option:not(:selected)').prop('disabled', true);
	}


</script>



