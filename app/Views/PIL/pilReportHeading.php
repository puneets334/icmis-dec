
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
      .nav-breadcrumb{
        margin-left:20px;
    }
    .nav-breadcrumb li a {
        background-image: none;
        background-repeat: no-repeat;
        background-position: 100% 3px;
        position: relative;
    }
    .nav-breadcrumb li a, .nav-breadcrumb li a:link, .nav-breadcrumb li a:visited {
        margin: 0 10px 0px 0;
        padding: 15px 0px 0 0;
    }  
</style>
<?php
$url_district_master = '#';
$uri = current_url(true);
 
//var_dump(current_url());
//die;  string(55) "http://10.40.186.38:93/PIL/PilController/reportsSection"

?>
<ul class="nav-breadcrumb">
   <!-- <li>
        <?php
/*//        echo $uri;
        if ($uri->getSegment(3) == 'reportPilGroup'){
            $ColorCode = 'background-color: #01ADEF';
            $status_color = 'btn-primary';
            $url_district_master = base_url('PIL/PilController/reportPilGroup');
        }else{
            $ColorCode = 'background-color: #169F85;color:#ffffff;';
            $status_color = 'btn-outline-primary';
            $url_district_master = base_url('PIL/PilController/reportPilGroup');
        }
        */?>
        <a href="<?/*= $url_district_master; */?>"><button  class="btn btn-block <?php /*echo $status_color; */?>">Generate Brief History</button> </a>
    </li>
    <li>
        <?php
/*        if ($uri->getSegment(3) == 'addToPilGroupShow'){
            $ColorCode = 'background-color: #01ADEF';
            $status_color = 'btn-primary';
            $url_district_master = base_url('PIL/PilController/addToPilGroupShow');
        }else{
            $ColorCode = 'background-color: #169F85;color:#ffffff;';
            $status_color = 'btn-outline-primary';
            $url_district_master = base_url('PIL/PilController/addToPilGroupShow');
        }
        */?>
        <a href="<?/*= $url_district_master; */?>"><button  class="btn btn-block <?php /*echo $status_color; */?>">Generate Letters</button> </a>
    </li>-->
   

    <li>
        <?php
        if ($uri->getPath() == '/PIL/PilController/reportPilGroup') {
            $ColorCode = 'background-color: #01ADEF';
            $status_color = 'btn-primary';
            $url_lower_court_case_type = base_url('PIL/PilController/reportPilGroup');
        } else{
            $ColorCode = 'background-color: #169F85;color:#ffffff;';
            $status_color = 'btn-info';
            $url_lower_court_case_type = base_url('PIL/PilController/reportPilGroup');
        }
        ?>
        <a href="<?= $url_lower_court_case_type; ?>"><button type="button" class="btn btn-block <?php echo $status_color; ?>"> Generate Brief History </button> </a>

    </li>

    <li>
        <?php
        if ($uri->getPath() == '/PIL/PilController/getPilDetailByDiaryNumberForLetterGeneration') {
            $ColorCode = 'background-color: #01ADEF';
            $status_color = 'btn-primary';
            $url_lower_court_case_type = base_url('PIL/PilController/getPilDetailByDiaryNumberForLetterGeneration');
        } else{
            $ColorCode = 'background-color: #169F85;color:#ffffff;';
            $status_color = 'btn-info';
            $url_lower_court_case_type = base_url('PIL/PilController/getPilDetailByDiaryNumberForLetterGeneration');
        }
        ?>
        <a href="<?= $url_lower_court_case_type; ?>"><button type="button" class="btn btn-block <?php echo $status_color; ?>"> Generate Letters </button> </a>

    </li>

    <li>
        <?php
        if ($uri->getPath() == '/PIL/PilController/queryPilData'){
            $ColorCode = 'background-color: #01ADEF';
            $status_color = 'btn-primary';
            $url_district_master = base_url('PIL/PilController/queryPilData');
        }else{
            $ColorCode = 'background-color: #169F85;color:#ffffff;';
            $status_color = 'btn-info';
            $url_district_master = base_url('PIL/PilController/queryPilData');
        }
        ?>
        <a href="<?= $url_district_master; ?>"><button  class="btn btn-block <?php echo $status_color; ?>">Query</button> </a>
    </li>
    <li>
        <?php
        if ($uri->getPath() == '/PIL/PilController/getPilReport') {
            $ColorCode = 'background-color: #01ADEF';
            $status_color = 'btn-primary';
            $url_lower_court_case_type = base_url('PIL/PilController/getPilReport');
        } else{
            $ColorCode = 'background-color: #169F85;color:#ffffff;';
            $status_color = 'btn-info';
            $url_lower_court_case_type = base_url('PIL/PilController/getPilReport');
        }
        ?>
        <a href="<?= $url_lower_court_case_type; ?>"><button type="button" class="btn btn-block <?php echo $status_color; ?>">Reports</button> </a>

    </li>


    <li>
        <?php
        if ($uri->getPath() == '/PIL/PilController/getPilUserWise') {
            $ColorCode = 'background-color: #01ADEF';
            $status_color = 'btn-primary';
            $url_lower_court_judge = base_url('PIL/PilController/getPilUserWise');
        } else{
            $ColorCode = 'background-color: #169F85;color:#ffffff;';
            $status_color = 'btn-info';
            $url_lower_court_judge = base_url('PIL/PilController/getPilUserWise');
        }
        ?>
        <a href="<?= $url_lower_court_judge; ?>"><button  class="btn btn-block <?php echo $status_color; ?>">User Wise</button> </a>

    </li>

</ul>
<div class="clearfix"></div>
