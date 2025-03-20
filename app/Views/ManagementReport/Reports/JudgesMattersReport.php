<?= view('header'); ?>
<section class="content">
    <div class="container-fluid">
        <div id="res_loader"></div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header heading">
                        <h3 class="mb-0">Management Reports >> Statistical Information of Judges</h3>
                    </div>
                    <div class="card-body">

        <h1><center>FINAL DISPOSAL REPORT</center></h1>
        <form method="post" action="<?php echo base_url(); ?>/ManagementReports/JudgesMatters/judges_matter_list" >
            <?php echo csrf_field(); ?>
            <section class="content">
                <div class="box box-info">
                    <div class="box-body">
                        <br/>
                        <div class="row">
                            <label class="col-md-2">Select Hon’ble Judge : </label>
                            <div class="col-md-2">
                                <select name="judges_list" id="judges_list" style="width: 50%;margin-left: 2%;"  class="form-control input-sm filter_select_dropdown" required>
                                    <option value="" title="Select">Select Hon’ble Judge </option>
                                    <?php foreach ($judge_list as $dataRes) { ?>
                                        <option  value="<?php echo ($dataRes['jcode'].'|'. $dataRes['jname']); ?>"><?php echo $dataRes['jname']; ?> </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                    <button type="submit" id="btn-shift-assign" class="btn bg-olive btn-flat pull-right" onclick="this.innerText='Loading..'">
                                        <i class="fa fa-save"></i> Submit </button>
                            </div>
                        </div>
                        <br/>

                    </div>


                </div>
                <br/><br/>

            </section>
        </form>
        </div>
        </div>
        <script>

            
        </script>