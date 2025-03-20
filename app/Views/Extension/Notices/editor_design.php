
<div id="dv_sh_hd" style="display: none;position: fixed;top: 0;width: 100%;height: 100%;background-color: black;opacity: 0.6;left: 0;overflow: hidden;z-index: 103" >
       &nbsp;
    </div>
    <div id="dv_fixedFor_P" style="position: fixed;top:0;display: none;
	left:0;
	width:100%;
	height:100%;z-index: 105;">
         <div id="sp_close" style="text-align: right;cursor: pointer;width: 40px;float: right" onclick="closeData()" ><b><img src="<?php echo base_url();?>/images/close_btn.png" style="width:30px;height:30px"/></b></div>
         <?php   include('editor.php'); ?>
         <div contenteditable="true"  style="width: auto;background-color: white;overflow: scroll;height: 500px;margin-left: 50px;margin-right: 50px;margin-bottom: 25px;margin-top: 1px;word-wrap: break-word;" id="ggg" onkeypress="return  nb(event)" onmouseup="checkStat()">
       </div>
        </div>


