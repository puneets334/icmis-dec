
<?php
/**
 * Created by PhpStorm.
 * User: Anshu Gupta
 * Date: 20/10/23
 * Time: 8:55 AM
 */
?>
<style>
    .custom-radio{float: left; display: inline-block; margin-left: 10px; }
    .custom_action_menu{float: left; display: inline-block; margin-left: 10px; }
    .basic_heading{text-align: center;color: #31B0D5}
    .btn-sm {
        padding: 0px 8px;
        font-size: 14px;
    }
    .card-header {
        padding: 5px;
    }
    h4 {
        line-height: 0px;
    }
    .nav-breadcrumb {
    margin-left: 40px;
  }
    .nav-breadcrumb li a {
        background-image: none;
        background-repeat: no-repeat;
        background-position: 100% 3px;
        position: relative;
    }
    .nav-breadcrumb li a, .nav-breadcrumb li a:link, .nav-breadcrumb li a:visited {
        margin-left: -70px;
    }
</style>
<?php
$url_registration = $url_application_status=$url_bulk_status = $url_speciman_signature = '#';
$uri = current_url(true); ?>
<ul class="nav-breadcrumb">
    <li>
        <?php
         if ($uri->getSegment(3) == 'application'){
                $ColorCode = 'background-color: #01ADEF';
                $status_color = 'btn-primary';
             $url_registration = base_url('Copying/Copying/application');
            }else{
                $ColorCode = 'background-color: #169F85;color:#ffffff;';
                $status_color = 'btn-secondary';
             $url_registration = base_url('Copying/Copying/application');
            }
        ?>
        <a href="<?= $url_registration; ?>"><button  class="btn btn-block <?php echo $status_color; ?>">Add</button> </a>
    </li>
    <li>
        <?php
       if ($uri->getSegment(3) == 'application_status') {
               $ColorCode = 'background-color: #01ADEF';
               $status_color = 'btn-primary';
           $url_application_status = base_url('Copying/Copying/application_status');
           } else{
            $ColorCode = 'background-color: #169F85;color:#ffffff;';
            $status_color = 'btn-secondary';
           $url_application_status = base_url('Copying/Copying/application_status');
           }
        ?>
        <a href="<?= $url_application_status; ?>"><button type="button" class="btn btn-block <?php echo $status_color; ?>"> Application Status</button> </a>

    </li>

    <li>
        <?php
         if ($uri->getSegment(3) == 'bulk_status') {
                $ColorCode = 'background-color: #01ADEF';
                $status_color = 'btn-primary';
             $url_bulk_status = base_url('copying/Copying/bulk_status');
            } else{
                $ColorCode = 'background-color: #169F85;color:#ffffff;';
                $status_color = 'btn-secondary';
             $url_bulk_status = base_url('copying/Copying/bulk_status');
            }
        ?>
        <a href="<?=$url_bulk_status; ?>"><button  class="btn btn-block <?php echo $status_color; ?>">Bulk Status Update</button> </a>

    </li>
    <li>
        <?php
        if ($uri->getSegment(3) == 'specimen_signature') {
                $ColorCode = 'background-color: #01ADEF';
                $status_color = 'btn-primary';
            $url_speciman_signature = base_url('copying/Copying/specimen_signature');
            } else{
                $ColorCode = 'background-color: #169F85;color:#ffffff;';
                $status_color = 'btn-secondary';
            $url_speciman_signature = base_url('copying/Copying/specimen_signature');
            }
         ?>
        <a href="<?= $url_speciman_signature; ?>"><button  class="btn btn-block <?php echo $status_color; ?>">Speciman Signature</button> </a>

    </li>
    
</ul>
<div class="clearfix"></div>
