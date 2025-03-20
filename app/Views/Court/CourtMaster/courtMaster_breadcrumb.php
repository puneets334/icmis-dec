<?php
/**
 * Created by Sublime Text.
 * User: MOHAMMAD FARHAN
 * Date: 12/12/23
 * Time: 11:15 AM
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
$url_embed_qr=$url_generate_proceedings = $url_reprint = $url_Upload_one_by_one = '#';
$uri = current_url(true); ?>
<ul class="nav-breadcrumb">
    <li>
        <?php
         if ($uri->getSegment(3) == ''){
                $ColorCode = 'background-color: #01ADEF';
                $status_color = 'btn-primary';
             $url_embed_qr = base_url('Court/CourtMasterController');
            }else{
                $ColorCode = 'background-color: #169F85;color:#ffffff;';
                $status_color = 'btn btn-info';
             $url_embed_qr = base_url('Court/CourtMasterController');
            }
        ?>
        <a href="<?= $url_embed_qr; ?>"><button  class="btn btn-block <?php echo $status_color; ?>">Embed QR</button> </a>
    </li>

    <li>
        <?php
       if ($uri->getSegment(3) == 'proceedings') {
               $ColorCode = 'background-color: #01ADEF';
               $status_color = 'btn-primary';
           $url_generate_proceedings = base_url('Court/CourtMasterController/proceedings');
           } else{
               $ColorCode = 'background-color: #169F85;color:#ffffff;';
               $status_color = 'btn btn-info';
           $url_generate_proceedings = base_url('Court/CourtMasterController/proceedings');
           }
        ?>
        <a href="<?= $url_generate_proceedings; ?>"><button  class="btn btn-block <?php echo $status_color; ?>">Generate Proceedings</button> </a>

    </li>

    <li>
        <?php
         if ($uri->getSegment(3) == 'rePrint') {
                $ColorCode = 'background-color: #01ADEF';
                $status_color = 'btn-primary';
             $url_reprint = base_url('Court/CourtMasterController/rePrint');
            } else{
                $ColorCode = 'background-color: #169F85;color:#ffffff;';
                $status_color = 'btn btn-info';
             $url_reprint = base_url('Court/CourtMasterController/rePrint');
            }
        ?>
        <a href="<?= $url_reprint; ?>"><button  class="btn btn-block <?php echo $status_color; ?>">Reprint</button> </a>

    </li>
    <li>
        <?php
        if ($uri->getSegment(3) == 'UploadOneByOne') {
                $ColorCode = 'background-color: #01ADEF';
                $status_color = 'btn-primary';
            $url_Upload_one_by_one = base_url('Court/CourtMasterController/UploadOneByOne');
            } else{
                $ColorCode = 'background-color: #169F85;color:#ffffff;';
                $status_color = 'btn btn-info';
            $url_Upload_one_by_one = base_url('Court/CourtMasterController/UploadOneByOne');
            }
         ?>
        <a href="<?= $url_Upload_one_by_one; ?>"><button  class="btn btn-block <?php echo $status_color; ?>">Upload (One By One)</button> </a>

    </li>
</ul>
<div class="clearfix"></div>
