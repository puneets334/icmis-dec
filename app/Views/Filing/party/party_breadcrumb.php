<?php
$uri = current_url(true);
//pr($uri->getPath());
?>

<ul class="nav nav-pills inner-comn-tabs">
    <li class="nav-item"><a class="nav-link <?php echo ($uri->getPath() == '/Filing/Party/party_details') ? 'active' : '';?> " href="<?php echo base_url().'/Filing/Party/party_details'?>" data-toggle="tab_">Party Details</a></li>
    <li class="nav-item"><a class="nav-link <?php echo ($uri->getPath() == '/Filing/Party/copy_party_view') ? 'active' : '';?>" href="<?php echo base_url().'/Filing/Party/copy_party_view'?>" data-toggle="tab_">Copy Party Details</a></li>
    <li class="nav-item"><a class="nav-link <?php echo ($uri->getPath() == '/Filing/Party/dispose_selected_party_view') ? 'active' : '';?>" href="<?php echo base_url().'/Filing/Party/dispose_selected_party_view'?>" data-toggle="tab_">Multi Party Dispose</a></li>
    <li class="nav-item"><a class="nav-link <?php echo ($uri->getPath() == '/Filing/Party/restore_dispose_party_view') ? 'active' : '';?>" href="<?php echo base_url().'/Filing/Party/restore_dispose_party_view'?>" data-toggle="tab_">Restore</a></li>
    <!-- <li class="nav-item"><a class="nav-link" onclick="getPartyUpdate();" href="#multi_party_update_tab_panel" data-toggle="tab">Update</a></li> -->
</ul>