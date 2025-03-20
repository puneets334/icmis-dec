<?= view('header') ?>
 
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Online Requests - Verification Module</h3>
                                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                            </div>
                        </div>
                    </div>
                   
                    <div class="card-body">
                        
                    <form method="post">
                <p id="show_error"></p> <!-- This Segment Displays The Validation Rule -->
                <div class="card">
                    <div class="card-header bg-primary text-white font-weight-bolder">Online Requests - Verification Module
                    </div>
                    <div class="card-body">
                        <div class="form-row">

                            <div class="row col-12 pl-2">

                                    <div class="input-group mb-3">



                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="copy_status_addon">Application Status<span style="color:red;">*</span></span>
                                        </div>


                                        <!--start Status-->
                                        <label class="radio-inline ml-1">
                                            <input type="radio" name="copy_status" id="copy_status" value="P" checked> Pending
                                        </label>
                                        <label class="radio-inline ml-1">
                                            <input type="radio" name="copy_status" id="copy_status" value="D"> Disposed
                                            <!-- value to be change -->
                                        </label>
                                        <!--end Status-->
                                    </div>
                            </div>

                            <div class="row daterange_action d-none col-12 pl-2">


                                    <div class="input-daterange input-group mb-3" id="app_date_range">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="from_date_addon">From<span style="color:red;">*</span></span>
                                        </div>
                                        <input type="text" class="form-control bg-white from_date" aria-describedby="from_date_addon"  placeholder="From Date..." readonly>
                                        <span class="input-group-text" id="to_date_addon">to</span>
                                        <input type="text" class="form-control bg-white to_date" aria-describedby="to_date_addon"  placeholder="To Date..." readonly>
                                    </div>

                            </div>

                            <!--start section-->
                            <?php
                            $section_list=array(); $multiple=''; $d_none_class = '';
                            $multiple='multiple="multiple"';

                            // Load the database connection
                                $db = \Config\Database::connect();

                                // Check the value of $dcmis_section and set the SQL query accordingly
                                if (!empty($dcmis_section) && ($dcmis_section == 10 || $dcmis_section == 1)) {
                                    $sql_section = "SELECT id, section_name, display, isda 
                                                    FROM master.usersection 
                                                    WHERE display = 'Y' 
                                                    ORDER BY 
                                                        CASE WHEN id IN (10, 61) THEN 1 ELSE 999 END ASC, 
                                                        CASE WHEN isda = 'Y' THEN 2 ELSE 999 END ASC, 
                                                        section_name ASC";
                                } else {
                                   // $d_none_class = "d-none";
                                    $sql_section = "SELECT id, section_name, display, isda 
                                                    FROM master.usersection 
                                                    WHERE display = 'Y' AND id = ?";
                                }

                                // Execute the query
                                if (!empty($dcmis_section) && ($dcmis_section == 10 || $dcmis_section == 1)) {
                                    $query = $db->query($sql_section);
                                } else {
                                    $query = $db->query($sql_section, [$dcmis_section]);
                                }

                                // Fetch results as an object (similar to mysql_fetch_object)
                                $section_list = [];
                                $section_count = $query->getNumRows();

                                if ($section_count > 0) {
                                    $result_section_list = $query->getResultObject();
                                    foreach ($result_section_list as $row) {
                                        $section_list[] = $row;
                                    }
                                }
                            
                            ?>
                            <div class="row col-12 pl-2">

                                <div class="input-group mb-3 <?=$d_none_class?>">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="usersection_addon">Section<span style="color:red;">*</span></span>
                                    </div>
                                    <select class="form-control" name="usersection" id="usersection" <?=$multiple;?>>
                                        <?php foreach($section_list as $row){ $sel = ($dcmis_section==$row->id) ? "selected=selected" : ''; ?>
                                            <option <?php echo $sel;?>  value="<?php echo $row->id;?>"><?php echo $row->section_name;?></option>
                                        <?php }?>
                                    </select>
                                </div>
                            </div>
                            <!--end section-->

                            <div class="row col-12 pl-2 <?=($dcmis_user_idd != 1 && $dcmis_user_idd!=10) ? 'd-none' : '' ?>">

                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="applicant_type_addon">Applicant Type<span style="color:red;">*</span></span>
                                    </div>
                                    <select class="form-control" multiple="multiple" id="applicant_type" aria-describedby="applicant_type_addon" >
                                        <?php
                                        if(in_array_any( [10], $dcmis_multi_section_id ) && ($dcmis_usertype == 50 OR $_SESSION['dcmis_usertype'] == 51 OR $dcmis_usertype == 17)){
                                            if(in_array(1,$applicant_type_array)){
                                                ?>
                                                <option value="1">Advocate on Record</option>
                                                <?php
                                            }
                                            if(in_array(2,$applicant_type_array)){
                                                ?>
                                                <option value="2">Party/Party-in-person</option>
                                                <?php
                                            }
                                            if(in_array(3,$applicant_type_array)){
                                                ?>
                                                <option value="3">Appearing Counsel</option>
                                                <?php
                                            }
                                            if(in_array(4,$applicant_type_array)){
                                                ?>
                                                <!--<option value="4">Third Party</option>-->
                                                <?php
                                            }
                                            if(in_array(6,$applicant_type_array)){
                                                ?>
                                                <option value="6">Authenticated By AOR</option>
                                                <?php
                                            }
                                        }
                                        else if((in_array_any( [10], $dcmis_multi_section_id) && dcmis_usertype != 50 && $dcmis_usertype != 51 && $dcmis_usertype != 17) OR $dcmis_user_idd == 1){
                                            ?>
                                            <option value="1">Advocate on Record</option>
                                            <option value="2">Party/Party-in-person</option>
                                            <option value="3">Appearing Counsel</option>
                                            <!--<option value="4">Third Party</option>-->
                                            <option value="6">Authenticated By AOR</option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="row col-12 pl-2 mb-3">
                                <input id="btn_search" name="btn_search" type="button" class="btn btn-success btn-block btn_search_click" value="Search">
                            </div>


                            <div class="row col-md-12 m-0 p-0" id="result"></div>


                            <!--                            <div class="col-md-7" >

                                                        </div>-->

                        </div>


                    </div>

                </div>
            </form>























                    </div>
                </div>
            </div>
        </div>
        <!-- /.card -->
    </div>
    <!-- /.col -->
    </div>
    <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>

