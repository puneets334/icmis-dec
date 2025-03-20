
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
</style>
<?php
$url_filing_diary_detail = $url_caveat_modify_detail=$url_caveat_earlier_court = $url_caveat_renew = $url_caveat_similarities = $url_caveat_view = '#';
$uri = current_url(true); 
//pr($uri);
?>
<ul class="nav-breadcrumb">
    <li>
        <?php
         if ($uri->getPath() == '/Caveat/Modify'){
                //$ColorCode = 'background-color: #01ADEF';
                $status_color = 'first active';
             $url_caveat_modify_detail = base_url('Caveat/Modify');
            }else{
                //$ColorCode = 'background-color: #169F85;color:#ffffff;';
                $status_color = '';
             $url_caveat_modify_detail = base_url('Caveat/Modify');
            }
        ?>
        <a href="<?= $url_caveat_modify_detail; ?>" class="<?php echo $status_color; ?>" style="z-index:15;">Caveat Modify </a>
    </li>

    <li>
        <?php
       if ($uri->getPath() == '/Caveat/Earlier_court') {
               //$ColorCode = 'background-color: #01ADEF';
               $status_color = 'first active';
           $url_caveat_earlier_court = base_url('Caveat/Earlier_court');
           } else{
               //$ColorCode = 'background-color: #169F85;color:#ffffff;';
               $status_color = '';
           $url_caveat_earlier_court = base_url('Caveat/Earlier_court');
           }
        ?>
        <a href="<?= $url_caveat_earlier_court; ?>" class="<?php echo $status_color; ?>" style="z-index:14"> Earlier Courts </a>

    </li>

    <li>
        <?php
         if ($uri->getPath() == '/Caveat/Renew') {
                //$ColorCode = 'background-color: #01ADEF';
                $status_color = 'first active';
             $url_caveat_renew = base_url('Caveat/Renew');
            } else{
                //$ColorCode = 'background-color: #169F85;color:#ffffff;';
                $status_color = '';
             $url_caveat_renew = base_url('Caveat/Renew');
            }
        ?>
        <a href="<?= $url_caveat_renew; ?>" class="<?php echo $status_color; ?>" style="z-index:13">Renew </a>

    </li>
    <li>
        <?php
        if ($uri->getPath() == '/Caveat/Notice') {
                //$ColorCode = 'background-color: #01ADEF';
                $status_color = 'first active';
            $url_caveat_similarities = base_url('Caveat/Notice');
            } else{
                //$ColorCode = 'background-color: #169F85;color:#ffffff;';
                $status_color = '';
            $url_caveat_similarities = base_url('Caveat/Notice');
            }
         ?>
        <a href="<?= $url_caveat_similarities; ?>" class="<?php echo $status_color; ?>" style="z-index:12;"></span> Notice </a>

    </li>
    <li>
        <?php
         if ($uri->getPath() == '/Caveat/Similarity') {
                //$ColorCode = 'background-color: #01ADEF';
                $status_color = 'first active';
             $url_caveat_view = base_url('Caveat/Similarity');
            } else{
                //$ColorCode = 'background-color: #169F85;color:#ffffff;';
                $status_color = '';
             $url_caveat_view = base_url('Caveat/Similarity');
            }
        ?>
        <a href="<?= $url_caveat_view; ?>" class="<?php echo $status_color; ?>" style="z-index:1;"></span>  View </a>

    </li>
</ul>
<div class="clearfix"></div>
