
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
$url_application_search = $url_barcodeconsume=$url_track = $url_copy_search = $url_dashboard=$url_request = '#';
$uri = current_url(true); ?>
<ul class="nav-breadcrumb">
    <li>
        <?php
         if ($uri->getSegment(3) == 'application_search'){
                $ColorCode = 'background-color: #01ADEF';
                $status_color = 'btn-primary';
             $url_application_search = base_url('copying/Copying/application_search');
            }else{
                $ColorCode = 'background-color: #169F85;color:#ffffff;';
                $status_color = 'btn-outline-primary';
             $url_application_search = base_url('copying/Copying/application_search');
            }
        ?>
        <a href="<?= $url_application_search; ?>"><button  class="btn btn-block <?php echo $status_color; ?>">Application Search</button> </a>
    </li>

    <li>
        <?php
       if ($uri->getSegment(3) == 'barcodeconsume') {
               $ColorCode = 'background-color: #01ADEF';
               $status_color = 'btn-primary';
           $url_barcodeconsume = base_url('Copying/Copying/barcodeconsume');
           } else{
               $ColorCode = 'background-color: #169F85;color:#ffffff;';
               $status_color = 'btn-outline-primary';
           $url_barcodeconsume = base_url('Copying/Copying/barcodeconsume');
           }
        ?>
        <a href="<?= $url_barcodeconsume; ?>"><button type="button" class="btn btn-block <?php echo $status_color; ?>"> Barcode Consume</button> </a>

    </li>

    <li>
        <?php
         if ($uri->getSegment(3) == 'track') {
                $ColorCode = 'background-color: #01ADEF';
                $status_color = 'btn-primary';
             $url_track = base_url('copying/Copying/track');
            } else{
                $ColorCode = 'background-color: #169F85;color:#ffffff;';
                $status_color = 'btn-outline-primary';
             $url_track = base_url('copying/Copying/track');
            }
        ?>
        <a href="<?=$url_track; ?>"><button  class="btn btn-block <?php echo $status_color; ?>">Consignment Track</button> </a>

    </li>
    <li>
        <?php
        if ($uri->getSegment(3) == 'copy_search') {
                $ColorCode = 'background-color: #01ADEF';
                $status_color = 'btn-primary';
            $url_copy_search = base_url('copying/Copying/copy_search');
            } else{
                $ColorCode = 'background-color: #169F85;color:#ffffff;';
                $status_color = 'btn-outline-primary';
            $url_copy_search = base_url('copying/Copying/copy_search');
            }
         ?>
        <a href="<?= $url_copy_search; ?>"><button  class="btn btn-block <?php echo $status_color; ?>">Copy Status</button> </a>

    </li>
    <li>
        <?php
        if ($uri->getSegment(3) == 'dashboard') {
            $ColorCode = 'background-color: #01ADEF';
            $status_color = 'btn-primary';
            $url_dashboard = base_url('copying/Copying/dashboard');
        } else{
            $ColorCode = 'background-color: #169F85;color:#ffffff;';
            $status_color = 'btn-outline-primary';
            $url_dashboard = base_url('copying/Copying/dashboard');
        }
        ?>
        <a href="<?= $url_dashboard; ?>"><button  class="btn btn-block <?php echo $status_color; ?>">Dashboard</button> </a>

    </li>
    <li>
        <?php
         if ($uri->getSegment(3) == 'request') {
                $ColorCode = 'background-color: #01ADEF';
                $status_color = 'btn-primary';
             $url_request = base_url('copying/Copying/request');
            } else{
                $ColorCode = 'background-color: #169F85;color:#ffffff;';
                $status_color = 'btn-outline-primary';
             $url_request = base_url('copying/Copying/request');
            }
        ?>
        <!--<a href="<?= $url_request; ?>"><button  class="btn btn-block <?php echo $status_color; ?>">Request</button> </a>-->
    </li>
</ul>
<div class="clearfix"></div>
