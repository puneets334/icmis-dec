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
                                <h2 align="center">Court Statistics Count Stats</h2>
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="active tab-pane">
                                                 
                                                       
                                                
                                        <?php
                                            if(isset($list_stats) && sizeof($list_stats)>0 ){


                                                    $heading="Court Wise Statistics of ROPs Uploaded in Matters Listed On ". date('d-m-Y',strtotime($listing_date));
                                                ?>
                                                <div id="printable">
                                                    <table class="table table-striped table-hover ">
                                                        <thead>
                                                        <tr><th colspan="3"></th>
                                                            <th colspan="2">ROPs</th>
                                                            <th colspan="2">Case Updation</th></tr>
                                                        <tr><th>#</th>
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
                                                        $cno=$result['court_number'];
                                                            ?>
                                                            <tr>
                                                                <td><?php echo $i;?></td>
                                                                <td><a target="_blank" href="<?php echo base_url() ?>/ManagementReports/Ropuploaded/details?cno=<?php echo $result['court_number'];?>&ldate=<?php echo $listing_date;?>"><?php if($result['court_number']==21) echo 'Registrar Court 1'; else if($result['court_number']==22) echo 'Registrar Court 2'; else echo $result['court_number'];?></td> 
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
                                                        <tr style="font-weight:bold;"><td colspan="2">Total</td><td><?=$total_matters;?></td>
                                                            <td><?=$total_rop;?></td><td><?=$total_not_uploaded;?></td><td><?=$total_updated;?></td>
                                                            <td><?=$total_not_updated;?></td></tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            <?php }
                                            else if(@$listing_date!='')
                                            {
                                                echo "Data not available for ".date('d-m-Y',strtotime($listing_date));
                                            }
                                            else{
                                                echo "<div class='text-center'><h3>Data not available.</h3></div>"; 
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