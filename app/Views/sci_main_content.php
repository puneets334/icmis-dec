<?=view('sci_main_header'); ?>
<!-- Main Sidebar Container -->
<?=view('header');?>
<!-- Sidebar -->
<?=view('templates/left_side_bar_menu');?>
<!-- /.sidebar -->

<div class="content-wrapper_stop" style="padding-left: 16.2%;">
    <div id="cover-spin" style="display: none"></div>
    <!--
    <section class="content">-->
    <div class="sci_main_content_view" id="sci_main_content_view">

        <div class="alert alert-info alert-dismissable fadein" id="info-alert">
            <button type="button" class="close" data-dismiss="alert">x</button>
            <strong>Info! </strong>
            No Latest Updates Found.
        </div>

        <div>Welcome to dashboard</div>
    </div>
    <!--</section>
    -->
</div>
<!-- /.content-wrapper -->
<?= view('sci_main_footer'); ?>
