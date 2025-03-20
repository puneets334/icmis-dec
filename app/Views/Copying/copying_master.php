
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
$url_reason_rejection_add = $url_reason_rejection_del=$url_role_assign = $url_role_delete = '#';
$uri = current_url(true); ?>
<ul class="nav-breadcrumb">
    <li>
        <?php
         if ($uri->getSegment(3) == 'reason_rejection_add'){
                $ColorCode = 'background-color: #01ADEF';
                $status_color = 'btn-primary';
             $url_reason_rejection_add = base_url('Copying/Copying/reason_rejection_add');
            }else{
                $ColorCode = 'background-color: #169F85;color:#ffffff;';
                $status_color = 'btn-outline-primary';
             $url_reason_rejection_add = base_url('Copying/Copying/reason_rejection_add');
            }
        ?>
        <a href="<?= $url_reason_rejection_add; ?>"><button  class="btn btn-block <?php echo $status_color; ?>">Reasons for Rejection</button> </a>
    </li>

    <li>
        <?php
       if ($uri->getSegment(3) == 'reason_rejection') {
               $ColorCode = 'background-color: #01ADEF';
               $status_color = 'btn-primary';
           $url_reason_rejection_del = base_url('Copying/Copying/reason_rejection');
           } else{
               $ColorCode = 'background-color: #169F85;color:#ffffff;';
               $status_color = 'btn-outline-primary';
           $url_reason_rejection_del = base_url('Copying/Copying/reason_rejection');
           }
        ?>
        <a href="<?= $url_reason_rejection_del; ?>"><button type="button" class="btn btn-block <?php echo $status_color; ?>"> Reason Rejection Delete</button> </a>

    </li>

    <li>
        <?php
         if ($uri->getSegment(3) == 'user_role_add') {
                $ColorCode = 'background-color: #01ADEF';
                $status_color = 'btn-primary';
             $url_role_assign = base_url('copying/Copying/user_role_add');
            } else{
                $ColorCode = 'background-color: #169F85;color:#ffffff;';
                $status_color = 'btn-outline-primary';
             $url_role_assign = base_url('copying/Copying/user_role_add');
            }
        ?>
        <a href="<?=$url_role_assign; ?>"><button  class="btn btn-block <?php echo $status_color; ?>">Role Add</button> </a>

    </li>
    <li>
        <?php
        if ($uri->getSegment(3) == 'user_role') {
                $ColorCode = 'background-color: #01ADEF';
                $status_color = 'btn-primary';
            $url_role_delete = base_url('copying/Copying/user_role');
            } else{
                $ColorCode = 'background-color: #169F85;color:#ffffff;';
                $status_color = 'btn-outline-primary';
            $url_role_delete = base_url('copying/Copying/user_role');
            }
         ?>
        <a href="<?= $url_role_delete; ?>"><button  class="btn btn-block <?php echo $status_color; ?>">Role Delete</button> </a>

    </li>
    
</ul>
<div class="clearfix"></div>
