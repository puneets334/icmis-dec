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
$url_generate_neutral_citation = $url_delete_neutral_citation = '#';
$uri = current_url(true); ?>
<ul class="nav-breadcrumb" >
    <li>
        <?php
         if ($uri->getSegment(3) == ''){
                $ColorCode = 'background-color: #01ADEF';
                $status_color = 'btn-primary';
             $url_generate_neutral_citation = base_url('Court/Neutral_citation');
            }else{
                $ColorCode = 'background-color: #169F85;color:#ffffff;';
                $status_color = 'btn btn-info';
             $url_generate_neutral_citation = base_url('Court/Neutral_citation');
            }
        ?>
        <a href="<?= $url_generate_neutral_citation; ?>"><button  class="btn btn-block <?php echo $status_color; ?>">Generate</button> </a>
    </li>

    <li>
        <?php
       if ($uri->getSegment(3) == 'delete') {
               $ColorCode = 'background-color: #01ADEF';
               $status_color = 'btn-primary';
           $url_delete_neutral_citation = base_url('Court/Neutral_citation/delete');
           } else{
               $ColorCode = 'background-color: #169F85;color:#ffffff;';
               $status_color = 'btn btn-info';
           $url_delete_neutral_citation = base_url('Court/Neutral_citation/delete');
           }
        ?>
        <a href="<?= $url_delete_neutral_citation; ?>"><button  class="btn btn-block <?php echo $status_color; ?>">Delete</button> </a>

    </li>
</ul>
<div class="clearfix"></div>
