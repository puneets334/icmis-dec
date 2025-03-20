
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
    /* .nav-breadcrumb li a {
        background-image: none;
        background-repeat: no-repeat;
        background-position: 100% 3px;
        position: relative;
    }
    .nav-breadcrumb li a, .nav-breadcrumb li a:link, .nav-breadcrumb li a:visited {
        margin-left: -70px;
    } */
</style>
<?php
$url_district_master = '#';
$uri = current_url(true);
//echo $uri."<br>";
//echo $uri->getSegment(3);die;

$filing_details= session()->get('filing_details');

?>
 
<?php if ($filing_details['c_status'] =='P')
{
?>


<ul class="nav-breadcrumb">
    <li>
        <?php
        if ($uri->getPath() == '/Extension/OfficeReport/report'){
            $ColorCode = 'background-color: #01ADEF';
            $status_color = 'first active';
            $url_district_master = base_url('Extension/OfficeReport/report');
        }else{
            $ColorCode = 'background-color: #169F85;color:#ffffff;';
            $status_color = '';
            $url_district_master = base_url('Extension/OfficeReport/report');
        }
        ?>
        <a href="<?= $url_district_master; ?>"  class="<?php echo $status_color; ?>" style="z-index:3">Report</button> </a>
    </li>

    <li>
        <?php
        if ($uri->getPath() == '/Extension/OfficeReport/upload') {
            $ColorCode = 'background-color: #01ADEF';
            $status_color = 'first active';
            $url_lower_court_case_type = base_url('Extension/OfficeReport/upload');
        } else{
            $ColorCode = 'background-color: #169F85;color:#ffffff;';
            $status_color = '';
            $url_lower_court_case_type = base_url('Extension/OfficeReport/upload');
        }
        ?>
        <a href="<?= $url_lower_court_case_type; ?>"  class="<?php echo $status_color; ?>" style="z-index:2">Upload</button> </a>

    </li>

<!--    <li>-->
<!--        --><?php
//        if ($uri->getSegment(3) == 'reprint') {
//            $ColorCode = 'background-color: #01ADEF';
//            $status_color = 'btn-primary';
//            $url_lower_court_case_type = base_url('Extension/OfficeReport/reprint');
//        } else{
//            $ColorCode = 'background-color: #169F85;color:#ffffff;';
//            $status_color = 'btn-outline-primary';
//            $url_lower_court_case_type = base_url('Extension/OfficeReport/reprint');
//        }
//        ?>
<!--        <a href="--><?//= $url_lower_court_case_type; ?><!--"><button type="button" class="btn btn-block --><?php //echo $status_color; ?><!--">Reprint</button> </a>-->
<!---->
<!--    </li>-->

    <!--<li>
        <?php
/*        if ($uri->getSegment(3) == 'reprint') {
            $ColorCode = 'background-color: #01ADEF';
            $status_color = 'btn-primary';
            $url_lower_court_case_type = base_url('Extension/OfficeReport/reprint');
        } else{
            $ColorCode = 'background-color: #169F85;color:#ffffff;';
            $status_color = 'btn-outline-primary';
            $url_lower_court_case_type = base_url('Extension/OfficeReport/reprint');
        }
        */?>
        <a href="<?/*= $url_lower_court_case_type; */?>"><button type="button" class="btn btn-block <?php /*echo $status_color; */?>">Reprint</button> </a>

    </li>-->

    <li>
        <?php
        if ($uri->getPath() == '/Extension/OfficeReport/CopyOR') {
            $ColorCode = 'background-color: #01ADEF';
            $status_color = 'first active';
            $url_lower_court_case_type = base_url('Extension/OfficeReport/CopyOR');
        } else{
            $ColorCode = 'background-color: #169F85;color:#ffffff;';
            $status_color = '';
            $url_lower_court_case_type = base_url('Extension/OfficeReport/CopyOR');
        }
        ?>
        <a href="<?= $url_lower_court_case_type; ?>"  class="<?php echo $status_color; ?>" style="z-index:1" >Copy</button> </a>

    </li>

    <!--<li>
        <?php
/*        if ($uri->getSegment(3) == 'bulk_upload') {
            $ColorCode = 'background-color: #01ADEF';
            $status_color = 'btn-primary';
            $url_lower_court_case_type = base_url('Extension/OfficeReport/bulk_upload');
        } else{
            $ColorCode = 'background-color: #169F85;color:#ffffff;';
            $status_color = 'btn-outline-primary';
            $url_lower_court_case_type = base_url('Extension/OfficeReport/bulk_upload');
        }
        */?>
        <a href="<?/*= $url_lower_court_case_type; */?>"><button type="button" class="btn btn-block <?php /*echo $status_color; */?>">Bulk Upload</button> </a>

    </li>-->

<!--    <li>-->
<!--        --><?php
//        if ($uri->getSegment(3) == 'gist') {
//            $ColorCode = 'background-color: #01ADEF';
//            $status_color = 'btn-primary';
//            $url_lower_court_case_type = base_url('Extension/OfficeReport/gist');
//        } else{
//            $ColorCode = 'background-color: #169F85;color:#ffffff;';
//            $status_color = 'btn-outline-primary';
//            $url_lower_court_case_type = base_url('Extension/OfficeReport/gist');
//        }
//        ?>
<!--        <a href="--><?//= $url_lower_court_case_type; ?><!--"><button type="button" class="btn btn-block --><?php //echo $status_color; ?><!--">Gist</button> </a>-->
<!---->
<!--    </li>-->
</ul>

<?php
}else{

}
 
if (!empty($filing_details))
{?>
    <div class="row">
        <label class="col-sm-12 col-form-label">
            <b>Diary Number :</b> <?=substr($filing_details['diary_no'], 0, -4).'/'.substr($filing_details['diary_no'],-4);?> &nbsp;&nbsp;&nbsp;
    <?php if (!empty($filing_details['reg_no_display'])){?><b>Case Number :</b> <?=$filing_details['reg_no_display'];?> <?php } ?> &nbsp;&nbsp;&nbsp;
            <b>Case Title :</b> <?=$filing_details['pet_name'].'  <b>Vs</b>  '.$filing_details['res_name'];?> &nbsp;&nbsp;&nbsp;
            <b>Filing Date : </b><?=(!empty($filing_details['diary_no_rec_date'])) ? date('d-m-Y',strtotime($filing_details['diary_no_rec_date'])): NULL ?> &nbsp;&nbsp;&nbsp;
            <?php if ($filing_details['c_status'] =='P'){ echo '<span class="text-blue">Pending</span>';}else{echo '<span class="text-red">Disposed</span>';} ?>
        </label>

    </div>
<?php } ?>
<div class="clearfix"></div>
