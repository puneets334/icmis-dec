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
                                    <h3 class="card-title">  Heard Entry update Matters</h3>
                                </div>

                            </div>
                        </div>
                        <div class="card-body">
            <!-- Main content -->


            <table class="table table-striped table-hover custom-table">
                <thead>
                <tr> <center><h1>List of Matters in which updations are done on <?php echo date('d-m-Y', strtotime($date));?> using Module-<?php echo $list_matters[0]['module_desc'] ?> by <?php echo $list_matters[0]['name']."(".$list_matters[0]['empid'].") of Section- ".$list_matters[0]['section_name']?></h1></center></tr>
                <?php
                if(isset($list_matters) && sizeof($list_matters)>0 ){

                ?>
                <tr>
                    <th>#</th>
                    <th>Diary Number<br/>Case Number</th>
                    <th>Cause Title</th>
                    <th>Updated By</th>
                    <th>Listing Date</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $i=0;
                foreach ($list_matters as $result)
                {$i++;
                    ?>
                    <tr>
                        <td><?php echo $i;?></td>
                        <td><?php echo $result['diary']."<br/>".$result['reg_no_display'];?></td>
                        <td><?php echo $result['pet_name']." Vs ".$result['res_name'];?></td>
                        <td><?php echo $result['name']."<br/>".$result['empid']." / ".$result['section_name'];?></td>
                        <td><?php if($result['next_dt']!='0000-00-00') echo date('d-m-Y', strtotime($result['next_dt']));
                            else " ";?></td>

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