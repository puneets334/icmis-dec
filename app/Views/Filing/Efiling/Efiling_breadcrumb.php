
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
$url_check_documents=$url_docs_from_sc_diary_no = $url_old_refiling_refiledcases=$url_transactions_by_refID = $url_pipreport = $url_refiled_documents=$url_transactions_by_date = '#';
$uri = current_url(true); ?>
<ul class="nav-breadcrumb">
    <li>
        <?php
        if ($uri->getPath() == '/Filing/Efiling/check_documents') {
            $ColorCode = 'background-color: #01ADEF';
            $status_color = 'first active';
            $url_check_documents = base_url('Filing/Efiling/check_documents');
        } else{
            $ColorCode = 'background-color: #169F85;color:#ffffff;';
            $status_color = '';
            $url_check_documents = base_url('Filing/Efiling/check_documents');
        }
        ?>
        <a href="<?= $url_check_documents; ?>" class="<?php echo $status_color; ?>" style="z-index:8;">Check Docs </a>

    </li>
    <li>
        <?php
         if ($uri->getPath() == '/Filing/Efiling/docs_from_sc_diary_no'){
                $ColorCode = 'background-color: #01ADEF';
                $status_color = 'first active';
             $url_docs_from_sc_diary_no = base_url('Filing/Efiling/docs_from_sc_diary_no');
            }else{
                $ColorCode = 'background-color: #169F85;color:#ffffff;';
                $status_color = '';
             $url_docs_from_sc_diary_no = base_url('Filing/Efiling/docs_from_sc_diary_no');
            }
        ?>
        <a href="<?= $url_docs_from_sc_diary_no; ?>" class="<?php echo $status_color; ?>" style="z-index:7;">Docs By Diary Number</a>
    </li>



    <li>
        <?php
         if ($uri->getPath() == '/Filing/Efiling/pipreport') {
                $ColorCode = 'background-color: #01ADEF';
                $status_color = 'first active';
             $url_pipreport = base_url('Filing/Efiling/pipreport');
            } else{
                $ColorCode = 'background-color: #169F85;color:#ffffff;';
                $status_color = '';
             $url_pipreport = base_url('Filing/Efiling/pipreport');
            }
        ?>
        <a href="<?= $url_pipreport; ?>" class="<?php echo $status_color; ?>" style="z-index:6;">Filing by Petitioner-in person</a>

    </li>

    <li>
        <?php
         if ($uri->getPath() == '/Filing/Efiling/efiling_applications') {
                $ColorCode = 'background-color: #01ADEF';
                $status_color = 'first active';
             $url_efiling_applications = base_url('Filing/Efiling/efiling_applications');
            } else{
                $ColorCode = 'background-color: #169F85;color:#ffffff;';
                $status_color = '';
             $url_efiling_applications = base_url('Filing/Efiling/efiling_applications');
            }
        ?>
        <a href="<?= $url_efiling_applications; ?>" class="<?php echo $status_color; ?>" style="z-index:5;" >Import data From Date</a>

    </li>

    <li>
        <?php
        if ($uri->getPath() == '/Filing/Efiling/old_refiling_refiledcases') {
                $ColorCode = 'background-color: #01ADEF';
                $status_color = 'first active';
            $url_old_refiling_refiledcases = base_url('Filing/Efiling/old_refiling_refiledcases');
            } else{
                $ColorCode = 'background-color: #169F85;color:#ffffff;';
                $status_color = '';
            $url_old_refiling_refiledcases = base_url('Filing/Efiling/old_refiling_refiledcases');
            }
         ?>
        <a href="<?= $url_old_refiling_refiledcases; ?>"  class="<?php echo $status_color; ?>" style="z-index:4;">Old EFiling ReFiled Cases</a>
    </li>
     <li>
        <?php        
         if ($uri->getPath() == '/Filing/Efiling/refiled_documents') {
            $ColorCode = 'background-color: #01ADEF';
            $status_color = 'first active';
            $url_refiled_documents = base_url('Filing/Efiling/refiled_documents');
        } else{
            $ColorCode = 'background-color: #169F85;color:#ffffff;';
            $status_color = '';
            $url_refiled_documents = base_url('Filing/Efiling/refiled_documents');
        }
         ?>
        <a href="<?php echo $url_refiled_documents; ?>" class="<?php echo $status_color;?>" style="z-index:3;">Refiling /Additional Documents Report For Srcutiny Assistants</a>

    </li> 
    <li>
        <?php
         if ($uri->getPath() == '/Filing/Efiling/transactions_by_date') {
                $ColorCode = 'background-color: #01ADEF';
                $status_color = 'first active';
             $url_transactions_by_date = base_url('Filing/Efiling/transactions_by_date');
            } else{
                $ColorCode = 'background-color: #169F85;color:#ffffff;';
                $status_color = '';
             $url_transactions_by_date = base_url('Filing/Efiling/transactions_by_date');
            }
        ?>
        <a href="<?= $url_transactions_by_date; ?>" class="<?php echo $status_color; ?>" style="z-index:2;">Transaction by Date</a>
    </li>
    <li>
        <?php
        if ($uri->getPath() == '/Filing/Efiling/transactions_by_refID') {
            $ColorCode = 'background-color: #01ADEF';
            $status_color = 'first active';
            $url_transactions_by_refID = base_url('Filing/Efiling/transactions_by_refID');
        } else{
            $ColorCode = 'background-color: #169F85;color:#ffffff;';
            $status_color = '';
            $url_transactions_by_refID = base_url('Filing/Efiling/transactions_by_refID');
        }
        ?>
        <a href="<?= $url_transactions_by_refID; ?>"  class="<?php echo $status_color; ?>" style="z-index:1;">Transaction by RefID</a>

    </li>
</ul>
<div class="clearfix"></div>
