<?php
/**
 * Created by PhpStorm.
 * User: RGV
 * Date: 1/4/19
 * Time: 2:43 PM
 */?>
 <?//= view('header') ?>
 <hr>
    <div class="card_">
        <?php
        if($reportType=="1")
        {
            $rval="Send To Authority(For Action)";
            $textFilled="";
        }
        elseif($reportType=="2")
        {
            $rval="Send To Authority(For Report)";
            $textFilled="";
        }
        elseif($reportType=="3")
        {
            $rval="Article 235";
            $textFilled=$pilDetails[0]['received_from'].",\n".$pilDetails[0]['address'];
        }
        ?>
        <?php if(isset($pilDetails))
        {
            $ecPilId=$pilDetails[0]['id'];
            $diaryNo=$pilDetails[0]['diary_number'];
            $diaryYear=$pilDetails[0]['diary_year'];
            $id=$pilDetails[0]['id'];
            $received_from=$pilDetails[0]['received_from'];
            $address=$pilDetails[0]['address'];
            /* print_r($pilDetails);*/
            /* echo "Sender: ".$received_from;
             echo "Address: ".$address;*/
            ?>      

        <div class="row">
                <div class="col-sm-3">
                    <b>Inward No:</b> <?=$diaryNo?>/<?=$diaryYear?>
                </div>
            <div class="col-sm-3">
                <b>Received From:</b> <?=$received_from?>
            </div>
            <div class="col-sm-3">
                <b>Address:</b> <?=$address?>
            </div>
            <div class="col-sm-3">
                <b>Selected Letter Type:</b> <?=$rval?>
            </div>
        </div>
      
      
        <form class="form-horizontal" id="downloadReports" action="<?=base_url()?>/PIL/PilController/downloadGeneratedReport/<?=$reportType?>/<?=$ecPilId?>/<?=$id;?>" target="_blank" method="post">
        <?= csrf_field() ?>
        
        <div class="row">          

                <input class="form-control" placeholder="Inward No" type="hidden" id="diaryNo" name="diaryNo" value=<?=$diaryNo?>>
                <input class="form-control" placeholder="Inward Year" type="hidden" id="diaryYear" name="diaryYear" value=<?=$diaryYear?>>
                <input class="form-control" placeholder="Received From" type="hidden" id="receivedFrom" name="receivedFrom" value=<?=$received_from?>>
                <!--<input class="form-control" placeholder="Address" type="hidden" id="text" name="address" value=--><?/*=$textFilled*/?>
                <div class="col-sm-6"><b>To:</b>
                <textarea class="form-control" required rows="5" name="comment" id="comment"><?=$textFilled?></textarea>
                </div>
                <input class="form-control" placeholder="Selected Letter Type" type="hidden" id="selectedLetterType" name="selectedLetterType" value=<?=$rval?>>
              

                <div class="col-sm-3 "><b>Title:</b>
                    <select class="form-control" id="Title" name="Title">
                        <option value="Mr.">Mr.</option>
                        <option value="Smt.">Smt.</option>
                        <option value="Ms.">Ms.</option>
                    </select>
                </div>

                <div class="col-sm-3 ">
                <button type="submit" name="generate" id="generate-btn" class="btn btn-flat bg-blue btn-block"  <!--onclick="downloadReport();"-->
                    Generate Report</button>

                    </div>   
            </div> 
        </form>                                                              
        <?php
        }?>
</div>

<br><br> 


