<?=view('header'); ?>
 
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">

                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Filing</h3>
                            </div>
                          <?=view('Filing/filing_filter_buttons'); ?>
                        </div>
                    </div>
                    <?=view('Filing/filing_breadcrumb'); ?>
                    <!-- /.card-header -->
					<div class="row">
                        <div class="col-sm-12">
							<div class="card">
								<div class="card-header p-2" style="background-color: #fff;">
                                    <h4 class="basic_heading"> Defect Details </h4>
                                </div>
								<div class="card-body">
								 <div class="alert alert-danger col-md-12">
									<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
									<span style="text-align:center;color: black"> <?php if(!empty($message)) print_r($message); ?></span>
								</div>
								</div>
							</div>
						</div>
					<div>
                    <br>
<!-- 					<h4 class="basic_heading"> Defect Details </h4> -->
                    <br><br>
                    <br><br>
                    <br><br>
                    <br><br>

                   
                    <br>
                    <br>
                    <br>
                    <br>



                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
</section>
<!-- /.content -->
