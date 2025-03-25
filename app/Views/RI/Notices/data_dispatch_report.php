<?php
if(isset($result) && sizeof($result)>0 && is_array($result))
{ 
?>
<div id="printable" class="box box-danger">

    <table width="100%" id="reportTable" class="table table-striped table-hover">
        <thead>
        <h3 style="text-align: center;"> Process ID Record </h3>
        <tr>
            <th rowspan='1'>SNo.</th>
            <th rowspan='1'>
            <span class="sp_red">Process Id</span><br><span class="sp_green">Notice Type</span>/<br>Diary No.
            </th>
            <th rowspan='1'>Name & Address</th>
            <th rowspan='1'>Notice Type</th>
            <th rowspan='1'>Remark</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $i=0;
        foreach ($result as $result)
        {$i++;
            ?>
            <tr>
            <?= csrf_field(); ?>
                <td><?php echo $i;?></td>
                <td><?php echo $result['diary_no'];?></td>
                <td><?php echo $result['name'].'<br/> R/o. '.$result['address'];?></td>
                <td><?php echo $result['nt_type']?></td>
                <td><?php echo $result['tal_state'];?></td>
                <td><?php echo $result['reg_no_display'];?></td>
                
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