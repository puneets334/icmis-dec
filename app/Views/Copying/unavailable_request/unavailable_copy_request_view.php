<div class="container-fluid m-0 p-0">
            <div class="row clearfix m-1 p-0">
                <div class="col-12 m-0 p-0">
                    <form method="post">
                <p id="show_error"></p> <!-- This Segment Displays The Validation Rule -->
                <div class="card">
                    <div class="card-header bg-info text-white font-weight-bolder">Unavailable Copy Request - Verification & Upload Module                                    
                    </div>
                    <div class="card-body">
                        <div class="form-row">                            
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="copy_status_addon">Status<span style="color:red;">*</span></span>
                                            </div>                                    
                                            <select class="form-control" id="copy_status" aria-describedby="copy_status_addon" >
                                                <option value="">-select-</option>
                                                <?php 
                                                //10 for copying section
                                                if($_SESSION['dcmis_usertype'] == 1 OR in_array_any( [10], $_SESSION['dcmis_multi_section_id'] ) ){
                                                    ?>
                                                    <option value="P">Pending (From Copying Section)</option>
                                                <?php
                                                }
                                                ?>                                                
                                                <option value="J">Pending (From Judicial Section)</option>
                                                <option value="D">Disposed</option>
                                            </select>                                        
                                        </div>
                                    </div>                                    
                                    <div class="col-md-6">
                                        <div class="input-daterange input-group mb-3" id="app_date_range">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="from_date_addon">Request Received Date<span style="color:red;">*</span></span>
                                            </div>                                    
                                            <input type="text" class="form-control bg-white from_date" aria-describedby="from_date_addon"  placeholder="From Date..." readonly>
                                            <span class="input-group-text" id="to_date_addon">to</span>
                                            <input type="text" class="form-control bg-white to_date" aria-describedby="to_date_addon"  placeholder="To Date..." readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="input-group mb-3">
                                            <div>   
                                                <input id="btn_search" name="btn_search" type="button" class="btn btn-success" value="Search">
                                            </div>   
                                        </div>
                                    </div>
                                </div>
                                <div class="row col-md-12 m-0 p-0" id="result"></div>                            
                        </div>                        
                    </div>
                </div>
            </form>
                    
                </div>

                
            </div>
            
            
            <div class="modal fade " id="myModal">
                <div class="modal-dialog">
                    <div class="modal-content">

                    </div>
                </div>
            </div>
            

        </div>