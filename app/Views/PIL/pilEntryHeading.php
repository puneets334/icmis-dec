
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
        margin-left: 20px;
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
        if ($uri->getSegment(3) == 'addToPilGroupShow'){
            $ColorCode = 'background-color: #01ADEF';
            $status_color = 'btn-primary';
            $url_district_master = base_url('PIL/PilController/addToPilGroupShow');
        }else{
            $ColorCode = 'background-color: #169F85;color:#ffffff;';
            $status_color = 'btn-outline-primary';
            $url_district_master = base_url('PIL/PilController/addToPilGroupShow');
        }
        ?>
        <a href="<?= $url_district_master; ?>"><button  class="btn btn-block <?php echo $status_color; ?>">Add Cases In Pil Group</button> </a>
    </li>

    <li>
        <?php
        if ($uri->getSegment(3) == 'index') {
            $ColorCode = 'background-color: #01ADEF';
            $status_color = 'btn-primary';
            $url_lower_court_case_type = base_url('PIL/PilController/index');
        } else{
            $ColorCode = 'background-color: #169F85;color:#ffffff;';
            $status_color = 'btn-outline-primary';
            $url_lower_court_case_type = base_url('PIL/PilController/index');
        }
        ?>
        <a href="<?= $url_lower_court_case_type; ?>"><button type="button" class="btn btn-block <?php echo $status_color; ?>"> Add/Edit</button> </a>

    </li>

    <li>
        <?php
        if ($uri->getSegment(3) == 'showPilGroup') {
            $ColorCode = 'background-color: #01ADEF';
            $status_color = 'btn-primary';
            $url_lower_court_judge = base_url('PIL/PilController/showPilGroup');
        } else{
            $ColorCode = 'background-color: #169F85;color:#ffffff;';
            $status_color = 'btn-outline-primary';
            $url_lower_court_judge = base_url('PIL/PilController/showPilGroup');
        }
        ?>
        <a href="<?= $url_lower_court_judge; ?>"><button  class="btn btn-block <?php echo $status_color; ?>">Pil Group</button> </a>

    </li>

</ul>
<div class="clearfix"></div>
