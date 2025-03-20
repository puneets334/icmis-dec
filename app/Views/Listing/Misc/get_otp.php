<style>
 .modal .modal-header {
    border: none;
    position: inherit !important;
    right: 0;
    top: 0;
    padding: 15px;
    z-index: 9;
}
</style>
<!-- Bootstrap Modal -->
<div class="modal fade" id="enterOTPDialog" tabindex="-1" role="dialog" aria-labelledby="enterOTPDialogLabel" aria-hidden="true">
    <div class="otp-modal modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title" id="enterOTPDialogLabel">OTP Screen</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <!-- Modal Body -->
            <div class="modal-body">
            <?php
                $mobile_no_array=array();$emailid_array=array();$usertype_array=array();
                    /* $authorized_otp_users_list= " select usercode,name,usertype,mobile_no,email_id,ut.type_name from users u
                      inner join usertype ut on u.usertype=ut.id
                      where usercode in(9801, 9802)                      
                      order by usertype desc ";
                    //where usercode in(742,559)
                $result_otp_user_list = mysql_query($authorized_otp_users_list) or die(mysql_error()." SQL:".$authorized_otp_users_list);*/
                $attributes = array('class' => 'form-horizontal');
                ?>




                <form name="otpEntry" action="#">
                    <input type="hidden" class="form-control " name="next_dt" id="next_dt" value=<?=$list_dt ?>>
                    <div class="box-body" id="divOtpEntry">                        
                        <div class="form-group row align-items-center">
                            <label for="otpInput" class="col-sm-4 col-form-label text-right">Enter OTP:</label>
                            <div class="col-sm-8">
                                <!--<input type="text" class="form-control" id="10102" name="10102" placeholder="Enter OTP" required>-->
                                <input type="text" class="form-control" id="10575" name="10575" placeholder="Enter OTP" required>
                            </div>
                        </div>
                    </div>    
                </form>
            </div>
            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary btn-otp">Submit</button>
            </div>
        </div>
    </div>
</div>
