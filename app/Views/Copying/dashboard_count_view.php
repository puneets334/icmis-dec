<div class="container-fluid">
    <section>
        <div class="row">
            <div class="col-12 mt-3 mb-1">
                <h5 class="text-uppercase">Offline Copying - At a glance</h5>
            </div>
        </div>

        <div class="row">
            <div class="col-4 mb-4">
                <div class="card dashboard_modal" data-flag="offline_total_applications">
                    <div class="card-body">
                        <div class="d-flex justify-content-between px-md-1">
                            <div>
                                <h3 class="text-success"><?=$offline_total_filed?></h3>
                                <p class="mb-0">Applications</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-cart-arrow-down text-info fa-3x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-4 mb-4">
                <div class="card dashboard_modal" data-flag="offline_pending_applications">
                    <div class="card-body">
                        <div class="d-flex justify-content-between px-md-1">
                            <div>
                                <h3 class="text-success"><?=$offline_copy_pending?></h3>
                                <p class="mb-0">Pending</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-tasks text-danger fa-3x"></i>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-4 mb-4">
                <div class="card dashboard_modal" data-flag="offline_disposed_applications">
                    <div class="card-body">
                        <div class="d-flex justify-content-between px-md-1">
                            <div>
                                <h3 class="text-success"><?=$offline_copy_disposed?></h3>
                                <p class="mb-0">Disposed</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-check text-success fa-3x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>








    </section>

    <section>
        <div class="row">
            <div class="col-12 mt-3 mb-1">
                <h5 class="text-uppercase">E-Copying - At a glance</h5>
            </div>
        </div>

        <div class="row">
            <div class="col-4 mb-4">
                <div class="card dashboard_modal" data-flag="total_applications">
                    <div class="card-body">
                        <div class="d-flex justify-content-between px-md-1">
                            <div>
                                <h3 class="text-success"><?=$total_filed?></h3>
                                <p class="mb-0">Applications</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-cart-arrow-down text-info fa-3x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-4 mb-4">
                <div class="card dashboard_modal" data-flag="pending_applications">
                    <div class="card-body">
                        <div class="d-flex justify-content-between px-md-1">
                            <div>
                                <h3 class="text-success"><?=$e_copy_pending?></h3>
                                <p class="mb-0">Pending</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-tasks text-danger fa-3x"></i>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-4 mb-4">
                <div class="card dashboard_modal" data-flag="disposed_applications">
                    <div class="card-body">
                        <div class="d-flex justify-content-between px-md-1">
                            <div>
                                <h3 class="text-success"><?=$e_copy_disposed?></h3>
                                <p class="mb-0">Disposed</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-check text-success fa-3x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="row">
            <div class="col-4 mb-4">
                <div class="card dashboard_modal" data-flag="total_request">
                    <div class="card-body">
                        <div class="d-flex justify-content-between px-md-1">
                            <div>
                                <h3 class="text-success"><?=$total_request?></h3>
                                <p class="mb-0">Document Request</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-cart-arrow-down text-info fa-3x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-4 mb-4">
                <div class="card" >
                    <div class="card-body">
                        <div class="d-flex justify-content-between px-md-1">
                            <div>
                                <h3 class="text-success dashboard_modal" data-flag="pending_request"><?=$e_copy_request_pending?></h3>
                                <p class="mb-0">Pending 
                                    <button type="button" class="btn btn-success p-1 dashboard_modal" data-flag="request_pending_copying">
                                    Copying <span class="badge badge-light"><?=$e_copy_request_pending_at_copying?></span>
                                    </button>
                                    
                                    <button type="button" class="btn btn-primary p-1 dashboard_modal" data-flag="request_pending_judicial">
                                    Judicial <span class="badge badge-light"><?=$e_copy_request_pending_at_judicial?></span>
                                    </button>
                                    
                                    <!--<button type="button" class="btn btn-danger p-1 dashboard_modal" data-flag="request_pending_record_room">
                                    Record Room <span class="badge badge-light"><?/*=$e_copy_request_pending_at_record_room*/?></span>
                                    </button>-->
                                </p>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-tasks text-danger fa-3x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-4 mb-4">
                <div class="card dashboard_modal" data-flag="disposed_request">
                    <div class="card-body">
                        <div class="d-flex justify-content-between px-md-1">
                            <div>
                                <h3 class="text-success"><?=$e_copy_request_disposed?></h3>
                                <p class="mb-0">Completed</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-check text-success fa-3x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        
        
        <div class="row">
            <div class="col-4 mb-4">
                <div class="card dashboard_modal" data-flag="total_verify">
                    <div class="card-body">
                        <div class="d-flex justify-content-between px-md-1">
                            <div>
                                <h3 class="text-success"><?=$total_verify?></h3>
                                <p class="mb-0">Verify</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-cart-arrow-down text-info fa-3x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-4 mb-4">
                <div class="card dashboard_modal" data-flag="pending_verify">
                    <div class="card-body">
                        <div class="d-flex justify-content-between px-md-1">
                            <div>
                                <h3 class="text-success"><?=$e_copy_verify_pending?></h3>
                                <p class="mb-0">Pending</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-tasks text-danger fa-3x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-4 mb-4">
                <div class="card dashboard_modal" data-flag="disposed_verify">
                    <div class="card-body">
                        <div class="d-flex justify-content-between px-md-1">
                            <div>
                                <h3 class="text-success"><?=$e_copy_verify_disposed?></h3>
                                <p class="mb-0">Completed</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-check text-success fa-3x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        
        
        
    </section>
</div>
