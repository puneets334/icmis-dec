<?=view('header'); ?>
 
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
	.c_vertical_align th {
		vertical-align: middle;
	}
</style>
 <script type="text/javascript" src="<?php echo base_url();?>/filing/diary_search.js"></script>
 <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header heading">

                            <div class="row">
                                <div class="col-sm-10">
                                    <h3 class="card-title">Filing >> Scrutiny >> Report >> User wise Defect Detail Report</h3>
                                </div>

                                <div class="col-sm-2">
                                    <div class="custom_action_menu">
                                        <a href="<?= base_url() ?>/Filing/Diary"><button class="btn btn-success btn-sm" type="button"><i class="fa fa-plus-circle" aria-hidden="true"></i></button></a>
                                        <a href="<?= base_url() ?>/Filing/Diary/search"><button class="btn btn-info btn-sm" type="button"><i class="fa fa-search-plus" aria-hidden="true"></i></button></a>
                                        <a href="<?= base_url() ?>/Filing/Diary/deletion"><button class="btn btn-danger btn-sm" type="button"><i class="fa fa-trash" aria-hidden="true"></i></button></a>
                                    </div>
                                </div>
                            </div>
                        </div>
 


            
                <div class="row">

                    <div class="col-md-12 mt-4">
						
                        <!-- Main content -->
                        <section class="content">

                            <?php
                            if(isset($case_result) && sizeof($case_result)>0 && is_array($case_result))  {
                            ?>
                            <div class="box-footer">
                                <form>
                                    <button type="submit"  class="btn btn-warning" id="print" name="print"  onclick="printDiv('printable')" class="btn btn-block btn-warning">Print</button>
                                </form>
                            </div>
                            <div id="printable" class="box box-danger">

                                <table width="100%" id="reportTable" class="table table-striped table-hover" align="center">
                                    <thead>
                                    <?php $name1=str_replace('_',' ',$name);?>
                                    <h3 style="text-align: center;"> Defect entered by <?php echo $name1;?>  on  <?php echo date('d-m-Y', strtotime($on_date));?></h3>
                                    <tr>
                                        <th rowspan='2'>SNo.</th>
                                        <th rowspan='2'>Diary No.</th>
                                        <th rowspan='2'>Case Type</th>
                                        <th rowspan='2'>Cause Title</th>
                                        <th rowspan='2'>Filing Date</th>
                                        <th rowspan='2'>No. of Defects</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $i=0;
                                    $total_diary=0;
                                    foreach ($case_result as $result)
                                    {$i++;
                                        ?>
                                        <tr>
                                            <td><?php echo $i;?></td>
                                            <td><?php echo $result['diaryno'];?></td>
                                            <td><?php echo $result['casetype'];?></td>
                                            <td><?php echo $result['causetitle'];?></td>
                                            <td><?php echo date('d-m-Y', strtotime($result['filingdate']));?></td>
                                            <td><?php echo $result['total_defect_count'];?></td>

                                        </tr>
                                        <?php
                                        $total_diary+=$result['total_defect_count'];
                                    }
                                    ?>

                                    </tbody>
                                    <tfoot></tfoot>
                                </table>

                                <?php } ?>
                            </div>
                        </section>
                    </div>
                </div>
             
 <!-- /.col -->
			</div>
			<!-- /.row -->
		</div>
		<!-- /.container-fluid -->
		</div>
</section>   
             