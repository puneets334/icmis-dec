<?php
    $isda = $copyRequestModel->getSectionByEmpID();
    $section_qry='';                   
    if($isda == 'Y'){
        if($session()->get('dcmis_usertype') == 17 OR session()->get('dcmis_usertype') == 50 OR session()->get('dcmis_usertype') == 51){
            $section_qry = " and m.dacode = ".$_SESSION['dcmis_user_idd']." "; //only for judicial sections
        }
        else{
                
        $section_qry = " and m.dacode IN (".$copyRequestModel->getUserCodes().") "; //only for judicial sections                            
                        
        }        
    }    
    else{
        $section_qry = " "; 
    }
                            
    $from_date = date("Y-m-d", strtotime($_POST['from_date']));
    $to_date = date("Y-m-d", strtotime($_POST['to_date']));
    if($_POST['copy_status'] == 'J'){//pending from juducial section
        $request_status_qry = " and a.request_status = 'P' and is_sent_to_section = 't' ";
    }
    else if($_POST['copy_status'] == 'P'){//pending from copying section
        $request_status_qry = " and a.request_status = 'P' and is_sent_to_section = 'f' ";
    }
    else if($_POST['copy_status'] == 'D'){
        $request_status_qry = " and a.request_status = 'D' ";
    }
    else{
        echo "Select Request Status";
        exit();
    }
   
$result=getUnavailableDocRequests($from_date, $to_date, $request_status_qry, $section_qry)

?>      
<div class="col-12 m-0 p-0" >
        <?php
        if(!empty($result)){
            ?>
    
        <table class="table"> 
            <thead>
                <tr>
                    <th>#</th>
                    <th>Request Id</th>
                    <th>Date</th>
                    <th>Requested Documents</th>
                    <th>Case No.</th>
                    <th>Cause Title</th>
                    <th>Status</th>                    
                    <th>Mobile</th>
                    <th>Email</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
        <?php 
        foreach($result as $row) {            
            $case_no = "";            
            if($row['reg_no_display'] != ''){
                $case_no = $row['reg_no_display'];
            }            
                $case_no .= ' DNo. '.substr($row['diary_no'], 0, -4).'-'.substr($row['diary_no'], -4);
            if($row['c_status'] == 'P'){
                $case_status = 'Pending';
            }
            else{
                $case_status = 'Disposed';
            }
                ?>        
                <tr>
                    <td><?= $srno++; ?></td>
                    <td><?= $row['id']; ?></td>
                    <td><?= date("d-m-Y", strtotime($row['ent_dt'])); ?></td>
                    <td><?= "Order Date : ".$row['order_date'].' '.$row['order_type_name']." (".$row['order_type_remark'].")"; ?></td>
                    <td><?= $case_no; ?></td>
                    <td><?= $row['cause_title']; ?></td>
                    <td><?= $case_status; ?></td>
                    <td><?= $row['mobile']; ?></td>
                    <td><?= $row['email']; ?></td>
                    <td class="action_copy" data-copyid="<?=$row['id'];?>">
                        
                        <?php
                        if($_POST['copy_status'] == 'J' OR $_POST['copy_status'] == 'P'){
                            ?>
                        
                        <button type="button" class="p-1 btn btn-success inline upload_copy" data-copyid="<?=$row['id'];?>" ><i class="fa fa-upload" aria-hidden="true"></i></button>
                        
                        <button type="button" class="p-1 btn btn-danger inline reject_copy" data-copyid="<?=$row['id'];?>" ><i class="far fa-trash-alt"></i></button>
                        
                            <?php
                            if($_POST['copy_status'] == 'P'){//pending from copying section
                            ?>
                        <button type="button" class="p-1 btn btn-primary inline sent_to_section_copy" data-copyid="<?=$row['id'];?>" ><i class="fa fa-share" aria-hidden="true"></i></button>
                            
                            
                         <?php
                            }     
                        }
                        else{
                            //only disposed information
                            echo '<span class="badge badge-default">Updated By: '.$row['updated_by_name'].'</span>';
                            echo '<br><span class="badge badge-default">On: '.date("d-m-Y H:i:s", strtotime($row['updated_on'])).'</span>';
                            
                            if($row['reject_cause'] != null && $row['reject_cause'] != ''){
                                echo "<br><p class='ml-1'><span class='text-danger fo'>Reject Cause : </span><small>".$row['reject_cause'].'</small></p>';
                            }
                            else{
                                echo "<a href='../../../".$row['url']."' class='badge badge-primary' target='_blank'>View</a>";
                            }
                        }
                            
                        ?>
                    </td>
                </tr>
                
            
            <?php
        }
        ?>
                 </tbody>
        </table>
           <?php
        }
        else{
            echo '<div class="alert alert-danger alert-dismissible"><strong>No Records Found.</strong></div>';
        }
        ?>    
</div>