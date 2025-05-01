
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
    .nav-breadcrumb li a {
        background-image: none;
        background-repeat: no-repeat;
        background-position: 100% 3px;
        position: relative;
    }
    .nav-breadcrumb li a, .nav-breadcrumb li a:link, .nav-breadcrumb li a:visited {
        padding: 0 !important;
        margin: 0 7px !important;
    }
</style>
<?php
$url_district_master = $url_lower_court_case_type=$url_lower_court_judge = $url_police_station = $url_state_agency=$url_lower_court_judge_post = '#';
$uri = current_url(true); ?>
<ul class="nav-breadcrumb">
    <li>
        <?php
         if ($uri->getSegment(3) == 'District_master'){
                $ColorCode = 'background-color: #01ADEF';
                $status_color = 'btn-primary';
             $url_district_master = base_url('Filing/Master/District_master');
            }else{
                $ColorCode = 'background-color: #169F85;color:#ffffff;';
                $status_color = 'btn btn-info';
             $url_district_master = base_url('Filing/Master/District_master');
            }
        ?>
        <a href="<?= $url_district_master; ?>"><button  class="btn btn-block <?php echo $status_color; ?>">District</button> </a>
    </li>

    <li>
        <?php
       if ($uri->getSegment(3) == 'Lower_court_case_type') {
               $ColorCode = 'background-color: #01ADEF';
               $status_color = 'btn-primary';
           $url_lower_court_case_type = base_url('Filing/Master/Lower_court_case_type');
           } else{
               $ColorCode = 'background-color: #169F85;color:#ffffff;';
               $status_color = 'btn btn-info';
           $url_lower_court_case_type = base_url('Filing/Master/Lower_court_case_type');
           }
        ?>
        <a href="<?= $url_lower_court_case_type; ?>"><button type="button" class="btn btn-block <?php echo $status_color; ?>"> Lower Court Case Type</button> </a>

    </li>

    <li>
        <?php
         if ($uri->getSegment(3) == 'Lower_court_judge') {
                $ColorCode = 'background-color: #01ADEF';
                $status_color = 'btn-primary';
             $url_lower_court_judge = base_url('Filing/Master/Lower_court_judge');
            } else{
                $ColorCode = 'background-color: #169F85;color:#ffffff;';
                $status_color = 'btn btn-info';
             $url_lower_court_judge = base_url('Filing/Master/Lower_court_judge');
            }
        ?>
        <a href="<?= $url_lower_court_judge; ?>"><button  class="btn btn-block <?php echo $status_color; ?>">Lower Court Judge</button> </a>

    </li>
    <li>
        <?php
        if ($uri->getSegment(3) == 'Police_station') {
                $ColorCode = 'background-color: #01ADEF';
                $status_color = 'btn-primary';
            $url_police_station = base_url('Filing/Master/Police_station');
            } else{
                $ColorCode = 'background-color: #169F85;color:#ffffff;';
                $status_color = 'btn btn-info';
            $url_police_station = base_url('Filing/Master/Police_station');
            }
         ?>
        <a href="<?= $url_police_station; ?>"><button  class="btn btn-block <?php echo $status_color; ?>">Police Station</button> </a>

    </li>
    <li>
        <?php
        if ($uri->getSegment(3) == 'State_agency') {
            $ColorCode = 'background-color: #01ADEF';
            $status_color = 'btn-primary';
            $url_state_agency = base_url('Filing/Master/State_agency');
        } else{
            $ColorCode = 'background-color: #169F85;color:#ffffff;';
            $status_color = 'btn btn-info';
            $url_state_agency = base_url('Filing/Master/State_agency');
        }
        ?>
        <a href="<?= $url_state_agency; ?>"><button  class="btn btn-block <?php echo $status_color; ?>">State Agency</button> </a>

    </li>
    <li>
        <?php
         if ($uri->getSegment(3) == 'Lower_court_judge_post') {
                $ColorCode = 'background-color: #01ADEF';
                $status_color = 'btn-primary';
             $url_lower_court_judge_post = base_url('Filing/Master/Lower_court_judge_post');
            } else{
                $ColorCode = 'background-color: #169F85;color:#ffffff;';
                $status_color = 'btn btn-info';
             $url_lower_court_judge_post = base_url('Filing/Master/Lower_court_judge_post');
            }
        ?>
        <a href="<?= $url_lower_court_judge_post; ?>"><button  class="btn btn-block <?php echo $status_color; ?>">Lower Court Judge Post (Addition)</button> </a>

    </li>
</ul>
<div class="clearfix"></div>
