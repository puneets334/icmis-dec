
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
        margin-left: -70px;
    }
</style>
<?php
$url_district_master = '#';
$uri = current_url(true); ?>
<ul class="nav-breadcrumb">
    <li>
        <?php
        if ($uri->getSegment(3) == 'index'){
            $ColorCode = 'background-color: #01ADEF';
            $status_color = 'btn-primary';
            $url_district_master = base_url('RI/EcopyController/index');
        }else{
            $ColorCode = 'background-color: #169F85;color:#ffffff;';
            $status_color = 'btn-outline-primary';
            $url_district_master = base_url('RI/EcopyController/index');
        }
        ?>
        <a href="<?= $url_district_master; ?>"><button  class="btn btn-block <?php echo $status_color; ?>">Receive</button> </a>
    </li>

    <li>
        <?php
        if ($uri->getSegment(3) == 'report') {
            $ColorCode = 'background-color: #01ADEF';
            $status_color = 'btn-primary';
            $url_lower_court_case_type = base_url('RI/EcopyController/report');
        } else{
            $ColorCode = 'background-color: #169F85;color:#ffffff;';
            $status_color = 'btn-outline-primary';
            $url_lower_court_case_type = base_url('RI/EcopyController/report');
        }
        ?>
        <a href="<?= $url_lower_court_case_type; ?>"><button type="button" class="btn btn-block <?php echo $status_color; ?>">Report</button> </a>

    </li>

</ul>
<div class="clearfix"></div>
