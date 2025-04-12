<?= view('header'); ?>
<style>
    div.dataTables_wrapper div.dataTables_filter label {
        display: flex;
        justify-content: end;
    }

    div.dataTables_wrapper div.dataTables_filter label input.form-control {
        width: auto !important;
        padding: 4px;
    }
</style>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Reports</h3>
                            </div>
                            
                        </div>
                    </div>
                    <div class="card-body">
                        
                        
                            <div id="printable_all">
                                <h2 align="center">Court Statistics</h2>
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="active tab-pane">
                                                <form class="form-inline" id="push-form"  method="get" action = "<?php htmlspecialchars($_SERVER['PHP_SELF']);?>" >
                                                    <div class="box-body d-inline-block w-100">
                                                        <div class="row">
                                                            <div class="col-md-2">
                                                                <label for="from" class="mt-2">From Date</label>
                                                            </div>
                                                            <div class="col-md-2">
                                                            <input type="date" id="fromDate" name="fromDate" class="form-control datepick" required  placeholder="From Date">
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label for="from" class="mt-2">To Date</label>
                                                            </div>
                                                            <div class="col-md-2">
                                                            <input type="date" id="toDate" name="toDate" class="form-control datepick" required  placeholder="To Date">
                                                            </div>
                                                        </div>
                                                    </form>    
                                                        <hr/>
                                                        <div class="row">
                                                                <div class="col-md-6">                                                                        
                                                                    <form>
                                                                        <button type="submit" style="" id="print" name="print" onclick="printDiv('printable')" class="btn btn-block btn-warning">Print</button>
                                                                    </form>        
                                                                </div>
                                                            
                                                            <div class="col-md-6 text-right">
                                                                <button type="submit"  style="" id="view" name="view" class="btn btn-block btn-primary">View</button>
                                                            </div>
                                                        </div>                                                        
                                                    </div>
                                                
                                                
                                                <?php
                                                    if(isset($list_stats) && sizeof($list_stats)>0 ){

                                                        if($from_date!=$to_date)
                                                        $heading="Statistics of ROPs Uploaded in Matters Listed between ". date('d-m-Y',strtotime($from_date)). " and ". date('d-m-Y',strtotime($to_date));
                                                        else if($from_date==$to_date)
                                                            $heading="Statistics of ROPs Uploaded in Matters Listed on ". date('d-m-Y',strtotime($from_date));
                                                ?>
                                                <div id="printable">
                                                  
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            
                                                        <table class="table table-striped table-hover ">
                                                            <thead>
                                                                <tr>
                                                                    <td colspan="7" style="text-align:center; font-weight:bold; font-size:24px;">
                                                                        <?php echo @$heading; ?>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <th colspan="3"></th>
                                                                    <th colspan="2">ROPs</th>
                                                                    <th colspan="2">Case Updation</th>
                                                                </tr>
                                                                <tr>
                                                                    <th>#</th>
                                                                    <th>Listing Date</th>
                                                                    <th>Matters Listed</th>
                                                                    <th>Uploaded</th>
                                                                    <th>Not Uploaded</th>
                                                                    <th>Updated</th>
                                                                    <th>Not Updated</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                $i=0;
                                                                $total_matters=0;
                                                                $total_rop=0;
                                                                $total_not_uploaded=0;
                                                                $total_updated=0;
                                                                $total_not_updated=0;
                                                                foreach ($list_stats as $result)
                                                                {$i++;
                                                                    ?>
                                                                    <tr>
                                                                    <td><?php echo $i;?></td>
                                                                        <td><a target="_blank" href="<?php echo base_url() ?>/ManagementReports/Ropuploaded/show_count?listDate=<?php echo $result['listing_date'];?>">
                                                                            <?php echo date('d-m-Y',strtotime($result['listing_date']));?>
                                                                        </td>                                
                                                                        <td><?php echo $result['listed'];?></td>
                                                                        <td><?php echo $result['rop_uploaded'];?></td>
                                                                        <td><?php echo $result['listed']-$result['rop_uploaded'];?></td>
                                                                        <td><?php echo $result['rop_updated'];?></td>
                                                                        <td><?php echo $result['listed']-$result['rop_updated'];?></td>
                                                                    </tr>

                                                                    <?php
                                                                    $total_matters+=$result['listed'];
                                                                    $total_rop+=$result['rop_uploaded'];
                                                                    $total_not_uploaded+=$result['listed']-$result['rop_uploaded'];
                                                                    $total_updated+=$result['rop_updated'];
                                                                    $total_not_updated+=$result['listed']-$result['rop_updated'];
                                                                }
                                                                ?>
                                                                <tr style="font-weight:bold;">
                                                                    <td colspan="2">Total</td>
                                                                    <td><?=$total_matters;?></td>
                                                                    <td><?=$total_rop;?></td>
                                                                    <td><?=$total_not_uploaded;?></td>
                                                                    <td><?=$total_updated;?></td>
                                                                    <td><?=$total_not_updated;?></td>
                                                                </tr>

                                                            </tbody>
                                                        </table>


                                                        </div>
                                                    </div>
                                                
                                                
                                                </div>
                                                <?php }
                                                else if(@$from_date!='' && @$to_date!='')
                                                {
                                                    echo "Data not available for given dates";
                                                }
                                                ?>


                                        </div>
                                    </div>
                                </div>
                            </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="div_print">
        <!-- <div id="header" style="background-color:White;"></div>
        <div id="footer" style="background-color:White;"></div> -->
    </div>
</section>
<script>
    $(document).on("click", "#print", function(e) {
    e.preventDefault(); // prevent default form behavior

    var $printable = $("#printable");

    if ($printable.length === 0 || !$printable.html().trim()) {
        alert("No data available to print.");
        return;
    }

    var prtContent = $printable.html();
    var WinPrint = window.open('', '', 'left=100,top=0,width=800,height=1200,menubar=1,toolbar=1,scrollbars=1,status=1');
    WinPrint.document.write(prtContent);
    WinPrint.document.close();
    WinPrint.focus();
    WinPrint.print();
});
</script>