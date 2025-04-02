<div class="row">
                            <div class="col-sm-6 pull-right">
                                <span id="msg"  style="color: red;margin-left: 65%;"><?=$msg ?? ''; ?></span>
                            </div>
                        </div>
<?php
 
    if(!empty($casesInPilGroup))
    {
 
                        ?>
            <div class="row">
                <div class="pull-right dropdown" style="width:100%">
                    <button type="button" class="btn btn-primary dropdown-toggle" style="float:right"  data-toggle="dropdown">Dropdown Report <i class="fa fa-caret-down" aria-hidden="true"></i>
                    </button>
                    
                    <ul class="dropdown-menu" style="width: max-content;left: -3%;padding: 10px;">
                        <li ><a href=" <?= base_url() ?>/PIL/PilController/downloadFormatReport?id=1&eid=<?= $ecPilGroupId?>&uid=<?php echo $_SESSION['login']['usercode']; ?>" target="_blank">Not To SCI</a></li>
                        <li><a href="<?= base_url() ?>/PIL/PilController/downloadFormatReport?id=2&eid=<?= $ecPilGroupId?>&uid=<?php echo $_SESSION['login']['usercode']; ?>" target="_blank">Vernacular</a></li>
                        <li><a href="<?= base_url() ?>/PIL/PilController/downloadFormatReport?id=3&eid=<?= $ecPilGroupId?>&uid=<?php echo $_SESSION['login']['usercode']; ?>" target="_blank">Email Unsigned</a></li>
                        <li><a href="<?= base_url() ?>/PIL/PilController/downloadFormatReport?id=4&eid=<?= $ecPilGroupId?>&uid=<?php echo $_SESSION['login']['usercode']; ?>" target="_blank">Unsigned</a></li>
                        <li><a href="<?= base_url() ?>/PIL/PilController/downloadFormatReport?id=5&eid=<?= $ecPilGroupId?>&uid=<?php echo $_SESSION['login']['usercode']; ?>" target="_blank">Anonymous letter-petitions</a></li>

                    </ul>
                </div>

            </div>


        <div id="tabledata" >
            <h4 align="center">PILs in Group</h4>
            <br>
            <table class="table table-bordered table-striped custom-table">
                <thead>
                <tr>
                    <th>S.No</th>
                    <th>Inward No/Year</th>
                    <th>Received From</th>
                    <th>Received On</th>
                    <th>Petition Date</th>
                    <th>Remove</th>
                </tr>
                </thead>
                <tbody id="data_set">
                <tbody>
                <?php
                $i = 0;
                $s=1;
                $rowserial = "odd";
                foreach ($casesInPilGroup as $result){
                $i++;
                if ($i % 2 == 0)
                    $rowserial = "even";
                else {
                    $rowserial = "odd";
                }
                ?>
                <tr role="row" class="<?= $rowserial ?>" id="remove_<?=$result['id']?>">
                    <td><?= $s++;?></td>
                    <td><?=$result['pil_diary_number']?></td>
                    <td><?=$result['received_from']?>
                        <?php
                        if(!empty($result['address'])){
                            echo "<br/> Address: ".$result['address'];
                        }
                        if(!empty($result['email'])){
                            echo "<br/> Email: ".$result['email'];
                        }
                        if(!empty($result['mobile'])){
                            echo "<br/> Mobile: ".$result['mobile'];
                        }
                        ?>
                        </td>
                        <td><?= !empty($result['received_on'])?date("d-m-Y", strtotime($result['received_on'])):null?></td>

                        <td><?=!empty($result['petition_date'])?date("d-m-Y", strtotime($result['petition_date'])):null?></td>

                        <td>
                           <!-- <a href="<?=base_url()?>/PIL/PilController/removeCaseFromPilGroup/<?=$result['id']?>/<?=$ecPilGroupId?>/<?=$_SESSION['login']['usercode']?>" onclick="if (confirm('Do you really want to remove this PIL from PIL Group?')){return true;}else{event.stopPropagation(); event.preventDefault();};">
                                <i class="fas fa-trash" aria-hidden="true" style="color: red;"></i> -->

                                <a onclick="pilRemove('<?=$result['id']?>')" href="javascript:void(0);">
                                <i class="fas fa-trash" aria-hidden="true" style="color: red;"></i>
                            
                            </td>
                </tr>
                    <?php
                    }
                    
                        ?>

                </tbody>
            </table>
        </div>

<?php }else{ 

    echo "<center><h3 style='color:Red'>SORRY!!!, NO RECORD FOUND</h3></center>";

  }?>