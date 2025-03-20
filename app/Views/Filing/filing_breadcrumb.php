
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
<?php if (!isset($_SESSION['filing_details'])) {
    header('Location:'.base_url('Filing/Diary/search'));exit();
}
$url_filing_diary_detail = $url_filing_diary_modify_detail=$url_filing_earlier_court = $url_filing_party = $url_filing_advocate = $url_filing_category = $url_filing_limitation = $url_filing_defect=$url_filing_refiling = $url_filing_ia_documents = $url_filing_coram = $url_filing_tagging = $url_filing_registration = $url_filing_verification = $url_filing_file_trap=$url_filing_view = $url_filing_similarity = '#';

$uri = service('uri');
$curi = $uri->getSegment(1).'/'.$uri->getSegment(2);

$uri = current_url(true);
 
//$curi = $uri->getSegment(2).'/'.$uri->getSegment(4);
$curi = $uri->getSegment(0).'/'.$uri->getSegment(1);
//$_SESSION['redirect_url_to'] = $curi;
$StageArray=(session()->get('login')['access_breadcrumb']);
?>
<ul class="nav-breadcrumb">

    <li>
        <?php
        if ( !empty($StageArray) && in_array(env('FILING_NEW_CASE_DETAIL'), $StageArray)) {
            if ($uri->getSegment(2) == 'Diary_modify'){
                $ColorCode = 'background-color: #01ADEF';
                $status_color = 'first active';
            }else{
                $ColorCode = 'background-color: #169F85;color:#ffffff;';
                $status_color = '';
            }
            $url_filing_diary_modify_detail = base_url('Filing/Diary_modify');
        } else {
            $ColorCode = 'background-color: #C11900;color:#ffffff;';
            $status_color = '';
        }
        ?>
        <?php if ($url_filing_diary_modify_detail !='#'){?>
        <a href="<?= $url_filing_diary_modify_detail ?>" class="<?php echo $status_color; ?>" style="z-index:15;">Basic Details</a>
        <?php }?>
    </li>

    <li>
        <?php
       if (!empty($StageArray) && in_array(env('FILING_NEW_CASE_EARLIER_COURT'), $StageArray)) {
           if ($uri->getSegment(2) == 'Earlier_court') {
               $ColorCode = 'background-color: #01ADEF';
               $status_color = 'first active';
           } else{
               $ColorCode = 'background-color: #169F85;color:#ffffff;';
               $status_color = '';
           }
            $url_filing_earlier_court = base_url('Filing/Earlier_court');
        } else {
            $ColorCode = 'background-color: #C11900;color:#ffffff;';
            $status_color = '';
        }
        ?>
        <?php if ($url_filing_earlier_court !='#'){?>
        <a href="<?= $url_filing_earlier_court ?>" class="<?php echo $status_color; ?>" style="z-index:14">Earlier Courts</a>
        <?php } ?>
    </li>

    <li>
        <?php
        if (!empty($StageArray) && in_array(env('FILING_NEW_CASE_PARTY'), $StageArray)) {
            if ($uri->getSegment(2) == 'Party') {
                $ColorCode = 'background-color: #01ADEF';
                $status_color = 'first active';
            } else{
                $ColorCode = 'background-color: #169F85;color:#ffffff;';
                $status_color = '';
            }
            $url_filing_party = base_url('Filing/Party');
        } else {
            $ColorCode = 'background-color: #C11900;color:#ffffff;';
            $status_color = '';
        }
        ?>
        <?php if ($url_filing_party !='#'){?>
        <a href="<?= $url_filing_party ?>" class="<?php echo $status_color; ?>" style="z-index:13">Parties</a>
        <?php } ?>
    </li>
    <li>
        <?php
        if (!empty($StageArray) && in_array(env('FILING_NEW_CASE_ADVOCATE'), $StageArray)) {
            if ($uri->getSegment(2) == 'Advocate') {
                $ColorCode = 'background-color: #01ADEF';
                $status_color = 'first active';
            } else{
                $ColorCode = 'background-color: #169F85;color:#ffffff;';
                $status_color = '';
            }
            $url_filing_advocate = base_url('Filing/Advocate');
        } else {
            $ColorCode = 'background-color: #C11900;color:#ffffff;';
            $status_color = '';
        }
        ?>
        <?php if ($url_filing_advocate !='#'){?>
        <a href="<?= $url_filing_advocate ?>" class="<?php echo $status_color; ?>" style="z-index:12;">Advocates</a>
        <?php } ?>
    </li>




    <li>
        <?php
        if (!empty($StageArray) && in_array(env('FILING_NEW_CASE_CATEGORY'), $StageArray)) {
            if ($uri->getSegment(2) == 'Category') {
                $ColorCode = 'background-color: #01ADEF';
                $status_color = 'first active';
            } else{
                $ColorCode = 'background-color: #169F85;color:#ffffff;';
                $status_color = '';
            }
            $url_filing_category = base_url('Filing/Category');
        } else {
            $ColorCode = 'background-color: #C11900;color:#ffffff;';
            $status_color = '';
        }
        ?>
        <?php if ($url_filing_category !='#'){?>
        <a href="<?= $url_filing_category ?>" class="<?php echo $status_color; ?>" style="z-index:11;">Category</a>
        <?php } ?>
    </li>


    <li>
        <?php
        if (!empty($StageArray) && in_array(env('FILING_NEW_CASE_LIMITATION'), $StageArray)) {
            if ($uri->getSegment(2) == 'Limitation') {
                $ColorCode = 'background-color: #01ADEF';
                $status_color = 'first active';
            } else{
                $ColorCode = 'background-color: #169F85;color:#ffffff;';
                $status_color = '';
            }
            $url_filing_limitation = base_url('Filing/Limitation');
        } else {
            $ColorCode = 'background-color: #C11900;color:#ffffff;';
            $status_color = '';
        }
        ?>
        <?php if ($url_filing_limitation !='#'){?>
        <a href="<?= $url_filing_limitation ?>" class="<?php echo $status_color; ?>" style="z-index:10;">Limitation</a>
        <?php } ?>
    </li>
    <li>
        <?php
        if (!empty($StageArray) && in_array(env('FILING_NEW_CASE_DEFECT'), $StageArray)) {
            if ($uri->getSegment(2) == 'Defect') {
                $ColorCode = 'background-color: #01ADEF';
                $status_color = 'first active';
            } else{
                $ColorCode = 'background-color: #169F85;color:#ffffff;';
                $status_color = '';
            }
            $url_filing_defect = base_url('Filing/Defect');
        } else {
            $ColorCode = 'background-color: #C11900;color:#ffffff;';
            $status_color = '';
        }
        ?>
        <?php if ($url_filing_defect !='#'){?>
        <a href="<?= $url_filing_defect ?>" class="<?php echo $status_color; ?>" style="z-index:9;">Defect</a>
        <?php } ?>
    </li>
    <li>
        <?php
        if (!empty($StageArray) && in_array(env('FILING_NEW_CASE_REFILING'), $StageArray)) {
            if ($uri->getSegment(2) == 'Refiling') {
                $ColorCode = 'background-color: #01ADEF';
                $status_color = 'first active';
            } else{
                $ColorCode = 'background-color: #169F85;color:#ffffff;';
                $status_color = '';
            }
            $url_filing_refiling = base_url('Filing/Refiling');
        } else {
            $ColorCode = 'background-color: #C11900;color:#ffffff;';
            $status_color = '';
        }
        ?>
        <?php if ($url_filing_refiling !='#'){?>
        <a href="<?= $url_filing_refiling ?>" class="<?php echo $status_color; ?>" style="z-index:8;">Refiling</a>
        <?php } ?>
    </li>
    <li>
        <?php
        if (!empty($StageArray) && in_array(env('FILING_NEW_CASE_IA_DOCUMNETS'), $StageArray)) {
           if ($uri->getSegment(2) == 'Ia_documents') {
                $ColorCode = 'background-color: #01ADEF';
                $status_color = 'first active';
            } else{
                $ColorCode = 'background-color: #169F85;color:#ffffff;';
                $status_color = '';
            }
            $url_filing_ia_documents = base_url('Filing/Ia_documents');
        } else {
            $ColorCode = 'background-color: #C11900;color:#ffffff;';
            $status_color = '';
        }
        ?>
        <?php if ($url_filing_ia_documents !='#'){?>
        <a href="<?= $url_filing_ia_documents ?>" class="<?php echo $status_color; ?>" style="z-index:7;">IA/Documents</a>
        <?php } ?>
    </li>
    <li>
        <?php
        if (!empty($StageArray) && in_array(env('FILING_NEW_CASE_CORAM'), $StageArray)) {
            if ($uri->getSegment(2) == 'Coram') {
                $ColorCode = 'background-color: #01ADEF';
                $status_color = 'first active';
            } else{
                $ColorCode = 'background-color: #169F85;color:#ffffff;';
                $status_color = '';
            }
            $url_filing_coram = base_url('Filing/Coram');
        } else {
            $ColorCode = 'background-color: #C11900;color:#ffffff;';
            $status_color = '';
        }
        ?>
        <?php if ($url_filing_coram !='#'){?>
        <a href="<?= $url_filing_coram ?>" class="<?php echo $status_color; ?>" style="z-index:6;">Coram</a>
        <?php } ?>
    </li>
    <li>
        <?php
       if (!empty($StageArray) && in_array(env('FILING_NEW_CASE_TAGGING'), $StageArray)) {
           if ($uri->getSegment(2) == 'Tagging') {
                $ColorCode = 'background-color: #01ADEF';
                $status_color = 'first active';
            } else{
                $ColorCode = 'background-color: #169F85;color:#ffffff;';
                $status_color = '';
            }
            $url_filing_tagging = base_url('Filing/Tagging');
        } else {
            $ColorCode = 'background-color: #C11900;color:#ffffff;';
            $status_color = '';
        }
        ?>
        <?php if ($url_filing_tagging !='#'){?>
        <a href="<?= $url_filing_tagging ?>" class="<?php echo $status_color; ?>" style="z-index:5;">Tagging</a>
        <?php } ?>
    </li>
    <li>
        <?php
       if (!empty($StageArray) && in_array(env('FILING_NEW_CASE_REGISTRATION'), $StageArray)) {
           if ($uri->getSegment(2) == 'Registration') {
                $ColorCode = 'background-color: #01ADEF';
                $status_color = 'first active';
            } else{
                $ColorCode = 'background-color: #169F85;color:#ffffff;';
                $status_color = '';
            }
            $url_filing_registration = base_url('Filing/Registration');
        } else {
            $ColorCode = 'background-color: #C11900;color:#ffffff;';
            $status_color = '';
        }
        ?>
        <?php if ($url_filing_registration !='#'){?>
        <a href="<?= $url_filing_registration ?>" class="<?php echo $status_color; ?>" style="z-index:4;">Registration</a>
        <?php } ?>
    </li>
    <li>
        <?php
       if (!empty($StageArray) && in_array(env('FILING_NEW_CASE_VERIFICATION'), $StageArray)) {
           if ($uri->getSegment(2) == 'Verification') {
                $ColorCode = 'background-color: #01ADEF';
                $status_color = 'first active';
            } else{
                $ColorCode = 'background-color: #169F85;color:#ffffff;';
                $status_color = '';
            }
            $url_filing_verification = base_url('Filing/Verification/verifyRecord');
        } else {
            $ColorCode = 'background-color: #C11900;color:#ffffff;';
            $status_color = '';
        }
        ?>
        <?php if ($url_filing_verification !='#'){?>
        <a href="<?= $url_filing_verification ?>" class="<?php echo $status_color; ?>" style="z-index:3;">Verification</a>
        <?php } ?>
    </li>


    <li>
        <?php
        if (!empty($StageArray) && in_array(env('FILING_NEW_CASE_FILE_TRAP'), $StageArray)) {
            if ($uri->getSegment(2) == 'FileTrap') {
                $ColorCode = 'background-color: #01ADEF';
                $status_color = 'first active';
            } else{
                $ColorCode = 'background-color: #169F85;color:#ffffff;';
                $status_color = '';
            }
            $url_filing_file_trap = base_url('Filing/FileTrap');
        } else {
            $ColorCode = 'background-color: #C11900;color:#ffffff;';
            $status_color = '';
        }
        ?>
        <?php if ($url_filing_file_trap !='#'){?>
        <a href="<?= $url_filing_file_trap ?>" class="<?php echo $status_color; ?>" style="z-index:2;">File Trap</a>
        <?php } ?>
    </li>
   <!-- <li>
        <?php
/*        if (in_array(env('FILING_NEW_CASE_VIEW'), $StageArray)) {
           if ($uri->getSegment(2) == 'View') {
                $ColorCode = 'background-color: #01ADEF';
                $status_color = 'first active';
            } else{
                $ColorCode = 'background-color: #169F85;color:#ffffff;';
                $status_color = '';
            }
            $url_filing_view = base_url('Filing/View');
        } else {
            $ColorCode = 'background-color: #169F85;color:#ffffff;';
            $status_color = '';
        }
        */?>
        <?php /*if ($url_filing_view !='#'){*/?>
        <a href="<?php /*= $url_filing_view */?>" class="<?php /*echo $status_color; */?>" style="z-index:1;">View </a>
        <?php /*} */?>
    </li>-->
    <li>
        <?php
        if (!empty($StageArray) && in_array(env('FILING_NEW_CASE_SIMILARITY'), $StageArray)) {
            if ($uri->getSegment(2) == 'Similarity') {
                $ColorCode = 'background-color: #01ADEF';
                $status_color = 'first active';
            } else{
                $ColorCode = 'background-color: #169F85;color:#ffffff;';
                $status_color = '';
            }
            $url_filing_similarity = base_url('Filing/Similarity');
        } else {
            $ColorCode = 'background-color: #169F85;color:#ffffff;';
            $status_color = '';
        }
        ?>
        <?php if ($url_filing_similarity !='#'){?>
            <a href="<?= $url_filing_similarity ?>" class="<?php echo $status_color; ?>" style="z-index:1;">Similarity</a>
        <?php } ?>
    </li>
</ul>
<div class="clearfix"></div>
<?php
$filing_details= session()->get('filing_details');
$show = (!empty($show)) ? $show : '';
?>
<?php if (!empty($filing_details) && (  $show == '' || $show == 'Y')){?>
 	
	<div class="row">
    <div class="col-sm-12">
        <div class="pg-breif-sec">
            <div class="row ">
                <div class="col-md-4">
                    <div class="breif-detlais-inner">
                        <label><b>Diary Number :</b> </label>
                        <label class="lable-rslt"> <?=substr($filing_details['diary_no'], 0, -4).'/'.substr($filing_details['diary_no'],-4);?> </label>
                    </div>
                </div>
				<?php if (!empty($filing_details['reg_no_display'])){?>
                <div class="col-md-4">
                    <div class="breif-detlais-inner">
                        <label><b>Case Number :</b></label>
                        <label class="lable-rslt"> <?=$filing_details['reg_no_display'];?></label>
                    </div>
                </div>
				 <?php } ?>
                <div class="col-md-4">
                    <div class="breif-detlais-inner">
                        <label><b>Case Title :</b></label>
                        <label class="lable-rslt"> <?=$filing_details['pet_name'].'  <b>Vs</b>  '.$filing_details['res_name'];?> </label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="breif-detlais-inner">
                        <label><b>Filing Date :</b></label>
                        <label class="lable-rslt"><?=(!empty($filing_details['diary_no_rec_date'])) ? date('d-m-Y',strtotime($filing_details['diary_no_rec_date'])): NULL ?>
						<span class="text-blue"><?php if ($filing_details['c_status'] =='P'){ echo '<span class="text-blue">Pending</span>';}else{echo '<span class="text-red">Disposed</span>';} ?></span> </label>
                    </div>
                </div>
            </div>
			
        </div>
    </div>
</div>

	
	
<?php } ?>
