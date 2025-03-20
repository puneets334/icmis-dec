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
            $url_district_master = base_url('RI/ReceiptController/index');
        }else{
            $ColorCode = 'background-color: #169F85;color:#ffffff;';
            $status_color = 'btn-outline-primary';
            $url_district_master = base_url('RI/ReceiptController/index');
        }
        ?>
        <a href="<?= $url_district_master; ?>"><button  class="btn btn-block <?php echo $status_color; ?>">Add/Update</button> </a>
    </li>

    <li>
        <?php
        if ($uri->getSegment(3) == 'dateWiseReceived') {
            $ColorCode = 'background-color: #01ADEF';
            $status_color = 'btn-primary';
            $url_lower_court_case_type = base_url('RI/ReceiptController/dateWiseReceived');
        } else{
            $ColorCode = 'background-color: #169F85;color:#ffffff;';
            $status_color = 'btn-outline-primary';
            $url_lower_court_case_type = base_url('RI/ReceiptController/dateWiseReceived');
        }
        ?>
        <a href="<?= $url_lower_court_case_type; ?>"><button type="button" class="btn btn-block <?php echo $status_color; ?>">Date-wise Report</button> </a>

    </li>

    <li>
        <?php
        if ($uri->getSegment(3) == 'getADToDispatch') {
            $ColorCode = 'background-color: #01ADEF';
            $status_color = 'btn-primary';
            $url_lower_court_judge = base_url('RI/ReceiptController/getADToDispatch');
        } else{
            $ColorCode = 'background-color: #169F85;color:#ffffff;';
            $status_color = 'btn-outline-primary';
            $url_lower_court_judge = base_url('RI/ReceiptController/getADToDispatch');
        }
        ?>
        <a href="<?= $url_lower_court_judge; ?>"><button  class="btn btn-block <?php echo $status_color; ?>">Dispatch AD To Section</button> </a>

    </li>

<!--    <li>-->
<!--        --><?php
//        if ($uri->getSegment(3) == 'showPilGroup') {
//            $ColorCode = 'background-color: #01ADEF';
//            $status_color = 'btn-primary';
//            $url_lower_court_judge = base_url('RI/ReceiptController/showPilGroup');
//        } else{
//            $ColorCode = 'background-color: #169F85;color:#ffffff;';
//            $status_color = 'btn-outline-primary';
//            $url_lower_court_judge = base_url('RI/ReceiptController/showPilGroup');
//        }
//        ?>
<!--        <a href="--><?//= $url_lower_court_judge; ?><!--"><button  class="btn btn-block --><?php //echo $status_color; ?><!--">Dispatch Report</button> </a>-->
<!---->
<!--    </li>-->

    <li>
        <?php
        if ($uri->getSegment(3) == 'getDispatch') {
            $ColorCode = 'background-color: #01ADEF';
            $status_color = 'btn-primary';
            $url_lower_court_judge = base_url('RI/ReceiptController/getDispatch');
        } else{
            $ColorCode = 'background-color: #169F85;color:#ffffff;';
            $status_color = 'btn-outline-primary';
            $url_lower_court_judge = base_url('RI/ReceiptController/getDispatch');
        }
        ?>
        <a href="<?= $url_lower_court_judge; ?>"><button  class="btn btn-block <?php echo $status_color; ?>">Dispatch To Officer/Section</button> </a>

    </li>

    <li>
        <?php
        if ($uri->getSegment(3) == 'dateWiseReceivedByConcern') {
            $ColorCode = 'background-color: #01ADEF';
            $status_color = 'btn-primary';
            $url_lower_court_judge = base_url('RI/ReceiptController/dateWiseReceivedByConcern');
        } else{
            $ColorCode = 'background-color: #169F85;color:#ffffff;';
            $status_color = 'btn-outline-primary';
            $url_lower_court_judge = base_url('RI/ReceiptController/dateWiseReceivedByConcern');
        }
        ?>
        <a href="<?= $url_lower_court_judge; ?>"><button  class="btn btn-block <?php echo $status_color; ?>">Receipt By Section/Officer</button> </a>

    </li>

    <li>
        <?php
        if ($uri->getSegment(3) == 'getDakDataForReceive') {
            $ColorCode = 'background-color: #01ADEF';
            $status_color = 'btn-primary';
            $url_lower_court_judge = base_url('RI/ReceiptController/getDakDataForReceive');
        } else{
            $ColorCode = 'background-color: #169F85;color:#ffffff;';
            $status_color = 'btn-outline-primary';
            $url_lower_court_judge = base_url('RI/ReceiptController/getDakDataForReceive');
        }
        ?>
        <a href="<?= $url_lower_court_judge; ?>"><button  class="btn btn-block <?php echo $status_color; ?>">Recieve DAK</button> </a>

    </li>

    <li>
        <?php
        if ($uri->getSegment(3) == 'receivedQuery') {
            $ColorCode = 'background-color: #01ADEF';
            $status_color = 'btn-primary';
            $url_lower_court_judge = base_url('RI/ReceiptController/receivedQuery');
        } else{
            $ColorCode = 'background-color: #169F85;color:#ffffff;';
            $status_color = 'btn-outline-primary';
            $url_lower_court_judge = base_url('RI/ReceiptController/receivedQuery');
        }
        ?>
        <a href="<?= $url_lower_court_judge; ?>"><button  class="btn btn-block <?php echo $status_color; ?>">Recieve Query</button> </a>

    </li>

    <li>
        <?php
        if ($uri->getSegment(3) == 'showServeUnServe') {
            $ColorCode = 'background-color: #01ADEF';
            $status_color = 'btn-primary';
            $url_lower_court_judge = base_url('RI/ReceiptController/showServeUnServe');
        } else{
            $ColorCode = 'background-color: #169F85;color:#ffffff;';
            $status_color = 'btn-outline-primary';
            $url_lower_court_judge = base_url('RI/ReceiptController/showServeUnServe');
        }
        ?>
        <a href="<?= $url_lower_court_judge; ?>"><button  class="btn btn-block <?php echo $status_color; ?>">Update Serve Status</button> </a>

    </li>

</ul>
<div class="clearfix"></div>
