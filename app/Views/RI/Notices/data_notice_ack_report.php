<?php
if(isset($results) && sizeof($results)>0 && is_array($results))
{ 
?>
    <table width="100%" id="reportTable" class="table table-striped table-hover">
        <thead>
        <h3 style="text-align: center;"> Process ID Record </h3>
        <tr>
        <th>Sr. No.</th>
        <th>User Id</th>
        <th>Name</th>
        <th> Status/<br/> Serve Type</th>
        <th> DA Receiving Date & Sign</th>
       
        </tr>
        </thead>
        <tbody>
        <?php
        $i=0;
        foreach ($results as $results)
        {$i++;
            ?>
            <tr>
            <?= csrf_field(); ?>
                <td><?php echo $i;?></td>
                <td><?php //echo $results['process_id'];?></td>
              
                <td><?php echo $results['name'];?></td>
                
                <td><?php //echo $results['send_to_type'];?></td>
                <td><?php //echo $results['rec_dt']?></td>
                
            </tr>
            <?php
        }
        ?>
        </tbody>
        <tfoot></tfoot>
    </table>

    <?php
    }
    
    else if($_SERVER['REQUEST_METHOD'] === 'POST'){?>

        <div class="alert alert-info alert-dismissable fade in" id="info-alert">
            <button type="button" class="close" data-dismiss="alert">x</button>
            <strong>Info! </strong>
            Record Not Found.
        </div>
    <?php  }

    ?>