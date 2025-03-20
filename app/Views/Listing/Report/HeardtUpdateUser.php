 <?=view('header') ?>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                         <div class="card-header heading">

                            <div class="row">
                                <div class="col-sm-10">
                                    <h3 class="card-title">  Heard Entry Show User</h3>
                                </div>

                                
                            </div>
                        </div>
                        <div class="card-body">
            <!-- Main content -->


            <table class="table table-striped table-hover custom-table">
                <thead>
                <tr> <center><h1>Userwise Number of Matters in which updations are done on <?php echo date('d-m-Y', strtotime($_REQUEST['date']));?> using Module-<?php  echo (!empty($list_users)) ? $list_users[0]['module_desc'] : '' ; ?></h1></center>
				</tr>
                <?php
                if(isset($list_users) && sizeof($list_users)>0 ){

                ?>
                <tr>
                    <th>#</th>
                    <th>User</th>
                    <th>No. of Matters</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $i=0;
                foreach ($list_users as $result)
                {$i++;
                    ?>
                    <tr>
                        <td><?php echo $i;?></td>
                        <td><?php echo $result['name']."<br/>".$result['type_name']."<br/>".$result['empid']." / ".$result['section_name'];?></td>
                        <td><a target="_blank" href="<?php echo base_url() ?>/Listing/Report/showMatters?mod=<?php echo $result['module_id'];?>&date=<?php echo $date;?>&user=<?php echo $result['empid'];?>"><?php echo $result['count'];?></td>
                    </tr>

                    <?php
                }
                ?>
                </tbody>
            </table>
            <?php }
            ?>
        </div>
                </div>
            </div>
    </section>