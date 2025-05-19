<?= view('header'); ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Judges Disposal</h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                    <?php if(!empty($detail_result)){ ?>
                                <span><h3 style="text-align: center;"> <?php echo $heading;?></h3></span>
                                <div class="table-responsive">
                                <table class="table table-striped custom-table" id="example1">
                                    <thead>
                                    <tr>
                                        <th style='text-align: center;'><b>S.No.</b></th>
                                        <th style='text-align: left;'><b>CASE NUMBER @ DIARY NUMBER</b></th>
                                        <th style='text-align: left;'><b>TITLE</b></th>
                                        <th style='text-align: center;'><b>Disposal Date</b></th>
                                    </tr>   
                                    </thead>
                                      <tbody>
                                            <?php
                                                $i=0;
                                                foreach ($detail_result as $result){ $i++; ?>
                                                        <tr>
                                                            <td style='text-align: center;'><?php echo $i;?></td>
                                                            <td style='text-align: left;'><?php echo $result['regno_dno'];?></td>
                                                            <td style='text-align: left;'><?php echo $result['title'];?></td>
                                                            <td style='text-align: center;'> <?php echo date('d-m-Y',strtotime($result['ord_dt']));?></td>
                                                        </tr>

							                    <?php } ?>
                                     </tbody>
                                </table>
                                <?php }else { ?>
                                    <h3 style="text-align:center">Records Not Found.!!</h3>
                                <?php }?>
                       </div>
                </div>
            </div>   
        </div>
    </div>
</div>
</section>
