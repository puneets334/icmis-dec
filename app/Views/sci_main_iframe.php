<style>

    .iFrameWrapper {
        position: relative;
        /*padding-bottom: 56.25%;*/
        padding-bottom: 70%;
        padding-top: 5px;
        height: 0;
    }
    .iFrameWrapper iframe {
        position: absolute;
        top: -8px;
        left: -8px;
        width: 100%;
        height: calc(100vh - 100px);
        border: 0;
    }
</style>
<div class="iFrameWrapper" style="margin-left: 300px; width: calc(100% - 300px);">
    <iframe src="<?php echo $content_url;?>" allowfullscreen></iframe>
</div>

<script>
$(document).ready(function(){
    $(".main-menu-close").on('click', function(){
        $('.iFrameWrapper').css('width', '100%');
        $('.iFrameWrapper').css('margin-left', '0');
    });
    $(".togglemenuSection").on('click', function(){
        $('.iFrameWrapper').css('width', 'calc(100% - 300px)');
        $('.iFrameWrapper').css('margin-left', '300px');
    });


    
});

</script>

