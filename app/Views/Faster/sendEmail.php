<?php
?>
<section class="content " >
    <div class="row">
        <div class="col-sm-3"><h3 class="head text-left" style="padding: 0 !important; margin: 0 !important;">Send Email</h3></div>
        <div class="col-sm-9 form-grup">
            <label for="dated" class="col-sm-2 control-label text-primary">Dated: <?=convertTodmY($_SESSION['nextDate'])?></label>
            <label for="diaryNumber" class="col-sm-4 control-label text-primary"><?=$_SESSION['caseNumber']?></label>
            <label for="causeTitle" class="col-sm-6 control-label text-primary"><?=$_SESSION['causetitle']?></label>
        </div>
        <?php if(isset($_SESSION['warning_message']) && !empty($_SESSION['warning_message'])){ ?>
            <div class="col-sm-8">
                <div class="alert alert-warning" role="alert">
                    <?=$_SESSION['warning_message']?>
                </div>
            </div>
            <?php
            $_SESSION['warning_message']="";
            //unset($_SESSION['warning_message']);
        } ?>
    </div>
    <hr>
    <form id="frmAddEmail" method="post">
        <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" id="csrf_token" />
        <div class="form-group col-sm-2">
            <label for="stakeholderType">Stakeholder Type</label>
            <select class="form-control" name="stakeholderType" id="stakeholderType" onchange="getStakeholderDetails(this);">
                <option value="0">Select</option>
                <?php //var_dump($stakeholderType);
                foreach($stakeholderType as $stakeholder){
                    echo "<option value='$stakeholder->id'>$stakeholder->description</option>";
                }
                ?>
            </select>
        </div>
        <div class="form-group col-sm-8">
            <label for="stakeholderDetails">Stakeholder Details</label>
            <select class="form-control js-example-basic-multiple" name="stakeholderDetails" id="stakeholderDetails" >
                <option value="0">Select</option>
                <?php //var_dump($stakeholderType);
                foreach($stakeholderType as $stakeholder){
                    echo "<option value='$stakeholder->id'>$stakeholder->description</option>";
                }
                ?>
            </select>
        </div>
        <div class="form-group col-sm-1">
            <label for="btnAdd">&nbsp;</label>
            <button class="form-control btn btn-success" id="btnAdd" onclick="return addEmailid();"><span class="glyphicon glyphicon-plus"></span></button>

        </div>
        <div class="col-sm-2">
            <div class="btn-group">
                <button type="button" class="btn btn-primary">Action</button>
                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                </button>
                <ul class="dropdown-menu" role="menu">
                    <li><a href="#" onclick="setClipboard();">Copy Email Id(s)</a></li>
                    <li><a href="#">Push SMS</a></li>
                    <li class="divider"></li>
                    <li><a href="#">Send Email</a></li>
                </ul>
            </div>
        </div>
        <div class="col-sm-10" id="divMessage"></div>
        <input type="hidden" name="hiddenEmailIds" id="hiddenEmailIds" value="">
        <div class="form-group col-sm-12">
            <h4>Contacts List</h4>
            <hr>
            <table id="tbl_communications" class="table table-striped table-bordered">
                <thead>
                <th>Type</th>
                <th>Name</th>
                <th>Details</th>
                <th>Email Id</th>
                <th>Mobile No.</th>
                <th>Added On</th>
                <th>Delete</th>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </form>
</section>
<script type="text/javascript">

    function getStakeholderDetails(id){
        if(id.value!=0){
            $.post("<?=base_url()?>index.php/FasterController/getStakeholderDetails", {stakeholder: id.value},function(result){
                $("#stakeholderDetails").html(result);
                $("#divResult").html(result);
            });
            $('.js-example-basic-multiple').select2();
        }
        return false;
    }
    setTimeout(function(){ recipientDetails(); }, 100);

</script>
