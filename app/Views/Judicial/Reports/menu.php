<?php $uri = current_url(true); ?>
<ul class="nav nav-pills">
    <!-- <li class="nav-item"><a class="nav-link <?php if (uri_string() == "Elimination_list") {
                                                echo 'active';
                                            } ?>" href="#Elimination_list" id="Elimination List">Elimination List</a></li>
    <li class="nav-item"><a class="nav-link <?php if (uri_string() == 'daily_disposal_search') {
                                                echo 'active';
                                            } ?>" href="#DDR" id="daily_disposal_search_click">Section-Wise</a></li>
    <li class="nav-item"><a class="nav-link <?php if (uri_string() == 'gist_module_search') {
                                                echo 'active';
                                            } ?>" href="#Gist Module" id="gist_module_search_click">Weekly Section List</a></li>
    <li class="nav-item"><a class="nav-link <?php if (uri_string() == 'matters_disposed_through_mentioning_search') {
                                                echo 'active';
                                            } ?>" href="#Matters_Disposed_through_Mentioning" id="matters_disposed_through_mentioning_click">Sec List</a></li>
    <li class="nav-item"><a class="nav-link <?php if (uri_string() == 'final_disposal_matters_search') {
                                                echo 'active';
                                            } ?>" href="#Final_Disposal_Matters" id="final_disposal_matters_search_click">Vacation Registrar List</a></li> -->
    <li class="nav-item"><a class="nav-link <?php if (uri_string() == 'Judicial/Report/da_daily_court_remarks') {
                                                echo 'active';
                                            } ?>" href="<?php echo base_url(); ?>/Judicial/Report/da_daily_court_remarks" id="fixed_date_matters_search_click">Daily Remarks</a></li>
    <li class="nav-item"><a class="nav-link <?php if (uri_string() == 'Judicial/Report/action_pending_report_da') {
                                                echo 'active';
                                            } ?>" href="<?php echo base_url(); ?>/Judicial/Report/action_pending_report_da" id="cause_list_with_OR_click">Pending Copying Requests</a></li>
    <li class="nav-item"><a class="nav-link <?php if (uri_string() == 'Judicial/Report/loose_document_da') {
                                                echo 'active';
                                            } ?>" href="<?php echo base_url(); ?>/Judicial/Report/loose_document_da" id="appearance_search_click">Loose Document</a></li>

    <li class="nav-item"><a class="nav-link <?php if (uri_string() == 'Judicial/Report/workdone') {
                                                echo 'active';
                                            } ?>" href="<?php echo base_url(); ?>/Judicial/Report/workdone" id="workdone_click">Work Done</a></li>

    <li class="nav-item"><a class="nav-link <?php if (uri_string() == 'Judicial/Report/da_wise_report') {
                                                echo 'active';
                                            } ?>" href="<?php echo base_url(); ?>/Judicial/Report/da_wise_report">ROGY DAWise</a></li>
    <li class="nav-item"><a class="nav-link <?php if (uri_string() == 'Judicial/Report/da_rog') {
                                                echo 'active';
                                            } ?>" href="<?php echo base_url(); ?>/Judicial/Report/da_rog" id="final_disposal_matters_search_click">ROGY Complete</a></li>
    
    <li class="nav-item"><a class="nav-link <?php if (uri_string() == 'Judicial/Report/aor_wise_matters') {
                                                echo 'active';
                                            } ?>" href="<?php echo base_url(); ?>/Judicial/Report/aor_wise_matters" id="aor_wise_matters">AOR wise matters</a></li>
    
    <li class="nav-item"><a class="nav-link <?php if (uri_string() == 'Judicial/Report/getORuploded_status') {
                                                echo 'active';
                                            } ?>" href="<?php echo base_url(); ?>/Judicial/Report/getORuploded_status" id="cause_list_with_OR_click">OR_Uploaded</a></li>
    <!-- <li class="nav-item"><a class="nav-link <?php if (uri_string() == 'appearance_search') {
                                                echo 'active';
                                            } ?>" href="#Appearance" id="appearance_search_click">Advance List</a></li> -->
    <li class="nav-item"><a class="nav-link <?php if (uri_string() == 'Judicial/Report/rop_daily_court_remarks') {
                                                echo 'active';
                                            } ?>" href="<?php echo base_url(); ?>/Judicial/Report/rop_daily_court_remarks" id="rop_daily_court_remarks">ROP Verification</a></li>
</ul>