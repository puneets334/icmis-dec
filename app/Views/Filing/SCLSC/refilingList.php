<div class="box box-success" ng-if="refilingList">
    <div class="box-header with-border">
        <h3 class="box-title" id="form-title">SCLSC Refiling Report</h3>
        <span style="float: right"><input type="text" class="form-control" ng-model="searchText" placeholder="Search"></span>
        <span style="float: right"><button type="button" class="btn bg-purple btn-flat" onclick="print_table()">Print</button></span>
        <br />
    </div>


    <div class="table-responsive">
        <table class="table table-striped custom-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Diary no </th>
                    <th>Cause title</th>
                    <th>Filed on</th>
                    <th>Filed by</th>
                    <th>Document</th>
                    <th>Total Pages</th>
                    <th>Source</th>

                </tr>
            </thead>
            <tbody>

            <?php if(!empty($refilingList)){
                foreach($refilingList as $key => $value) {?>
                <tr>

                    <td><?php echo $key+1; ?></td>
                    <td><?php echo  $value['diary_no']?></td>
                    <td><?php echo $value['pet_name']?> vs <?php echo $valuex['res_name']?></td>
                    <td><?php echo  $value['filed_on']?></td>
                    <td><?php echo  $value['name']?> ( <?php echo  $value['aor_code']?> )</td>
                    <td style="cursor:pointer;"><a href="<?php echo $value['paperbook_url'] ?>" target="_blank"> View </a></td>
                    <td><?php echo  $value['total_pages']?></td>
                    <td ng-click="get_docs('<?php echo $value['sclsc_id']?>')" data-toggle="modal" data-target="#docModal" data-backdrop="static" data-keyboard="false" align="center">
                        <font color="blue">View</font>
                    </td>


                    </td>
                </tr>
                <?php } 
            }else{?>
                    <tr>
                        <td colspan="100%">No Record found ...</td>
                    </tr>
            <?php }?>
            </tbody>
        </table>
    </div>
</div>