<?= view('header') ?>
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
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Freshly Filed - Verified Cases</h3>
                            </div>
                            <div class="col-sm-2">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">                               
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="active tab-pane">
                                            <form id="push-form" method="POST" action="">
                                                 <?=csrf_field(); ?>
                                                <div class="row">
                                                <div class="col-sm-5">
                                                    <div class="form-group row">
                                                        <label for="from" class="col-sm-6">From Date</label>
                                                        <div class="col-sm-6">
                                                            <input type="date" id="fromDate" name="fromDate" class="form-control datepick" required="" placeholder="From Date">
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="col-sm-5">

                                                    <div class="form-group row">
                                                        <label for="from" class="col-sm-6">To Date</label>
                                                        <div class="col-sm-6">
                                                            <input type="date" id="toDate" name="toDate" class="form-control datepick" required="" placeholder="To Date">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-2">

                                                    <div class="form-group row">

                                                        <div class="col-sm-7">
                                                            <button type="submit" id="view" name="view" value="date_wise" class="btn btn-block btn-primary">Search</button>


                                                        </div>
                                                    </div>
                                                </div>
                                            </form>

                                            </div>
                                                <div id="dv_data">

                                                <?php 
                                                if(!empty($title)) {
                                                   echo "<h2>".$title."</h2>";

                                                }
                                                if( isset($FreshlyData) && count($FreshlyData)>0) {
                                                ?>
                                                <table id="tab">
                                                    <thead><tr style="background-color:darkgrey;">
                                                        <th style="width: 5%;">SNo</th>
                                                        <th style="width:25%;">Case No./Diary No.</th>
                                                        <th style="width: 10%;">Filed On</th>
                                                        <th style="width: 45%;">Cause Title</th>            
                                                        <th style="width:15%;">Verified On</th>
                                                    </thead>
                                                    <?php  
                                                        foreach($FreshlyData as $k=> $data) {
                                                     ?>
                                                     <tr>
                                                        <td><?=$k+1; ?></td>
                                                        <td><?=$data->case_no;?></td>
                                                        <td><?=$data->diary_dt;?></td>
                                                        <td><?=$data->cause_title;?></td>
                                                        <td><?=$data->verified_on;?></td>                                                       
                                                     </tr>

                                                    <?php
                                                    }
                                                    ?>
                                                    <tbody>


                                                    </tbody>


                                                    </table>



                                                <?php } ?>      









                                                </div>
                                           
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    $(document).ready(function() {
        $("#reportTable1").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "dom": 'Bfrtip',
            "bProcessing": true,
            "buttons": ["excel", "pdf"]
        });
    });

    $(document).on("focus", ".dtp", function() {
        $('.dtp').datepicker({
            dateFormat: 'dd-mm-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: '1950:2050'
        });
    });

    
</script>