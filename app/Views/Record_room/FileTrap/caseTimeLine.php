<!--<p>Some text in the modal.</p>-->
<style>
   .timeline>li>.timeline-item {
    -webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
    box-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
    border-radius: 3px;
    margin-top: 0;
    background: #fff;
    color: #444;
    margin-left: 60px;
    margin-right: 15px;
    padding: 0;
    position: relative;
}

.timeline>li {
    position: relative;
    /* margin-right: 10px; */
    margin-bottom: 15px;
}
.timeline:before {
    content: '';
    position: absolute;
    top: 0;
    bottom: 0;
    width: 4px;
    background: #ddd;
    left: 31px;
    margin: 0;
    border-radius: 2px;
}
/* .timeline>li:before, .timeline>li:after {
    content: " ";
    display: table;
} */
.timeline>.time-label>span {
    font-weight: 600;
    padding: 5px;
    display: inline-block;
    background-color: #fff;
    border-radius: 4px;
}
.bg-red, .callout.callout-danger, .alert-danger, .alert-error, .label-danger, .modal-danger .modal-body {
    background-color: #dd4b39 !important;
}
/* .timeline>li:after {
    clear: both;
} */
/* .timeline>li:before, .timeline>li:after {
    content: " ";
    display: table;
} */
.timeline>li>.fa, .timeline>li>.glyphicon, .timeline>li>.ion {
    width: 30px;
    height: 30px;
    font-size: 15px;
    line-height: 30px;
    position: absolute;
    color: #666;
    background: #d2d6de;
    border-radius: 50%;
    text-align: center;
    left: 18px;
    top: 0;
}
li{
    list-style-type: none;
}
</style>
<div class="col-md-12" style="background-color: #f4f4f4;">
    <!-- The time line -->
    <ul class="timeline">
        <?php
        //var_dump($caseTimeline);
        $sno=0;
        if(is_array($caseTimeline))
        { ?>

        <!-- timeline time label -->
        <li class="time-label">
        <span class="bg-red">
            <?=$caseTimeline[0]['case_no'] ?><br>
            <span style="color: lightgoldenrodyellow;"><?=$caseTimeline[0]['cause_title'] ?></span><br>
            Order date:<?=$caseTimeline[0]['order_date'] ?>
            <br>
            <span style="color: lightgoldenrodyellow;">
                Coram: <?=$caseTimeline[0]['coram'];?>
            </span>
        </span>
        </li>

        <?php
        //var_dump($caseTimeline);

        foreach ($caseTimeline as $result)
        {
        $sno++;
        ?>
        <li>
            <?php
            if ($sno % 2 == 0) {
                ?>
                <i class="fa fa-user bg-aqua"></i>
            <?php } else { ?>
                <i class="fa fa-comments bg-yellow"></i>
            <?php } ?>
            <div class="timeline-item">
                <?php if($sno==1) { ?>
                    <?php if($result['d_to_empid']==99999) { ?>
                            <strong> <p class="blink_me ">File Storage Location : Hall No:<?=$result['hall_no'];?> (<?=$result['hall_location'];?>)</p></strong>
                        <?php } else { ?>
                            <strong> <p class="blink_me ">Current Location : <?=$result['dispathto']; ?>-<?=$result['roleto'] ?> (Emp ID:<?=$result['d_to_empid']; ?>)
                                    <?php
                                    if($result['hall_location']!=null and  $result['hall_location']!='')
                                    {
                                    ?>
                                    of - (Hall No:<?=$result['hall_no']?>-<?=$result['hall_location']?>)
                                        <?php }
                                        ?>
                                        </p></strong>

                    <?php }  ?>
                <?php } ?>

                <span class="time">
                     <span style="color: blue;">
                        <i class="fa fa-clock-o"></i> <?=$result['rece_dt'] ?>

                     </span>

                </span>

                <h3 class="timeline-header"><a href="#"><?=$result['remarks'] ?></a> </h3>

                <div class="timeline-body">
                    File Received and Auto Dispathced by <strong><?=$result['dispathby']; ?>-<?=$result['roleby'] ?> (Emp ID:<?=$result['d_by_empid']; ?>)</strong>
                    to <strong><?=$result['dispathto']; ?>-<?=$result['roleto'] ?> (Emp ID:<?=$result['d_to_empid']; ?>)</strong> on <strong><?=$result['rece_dt']; ?></strong>
                </div>
            </div>
            <?php
            }
            }
            ?>
        </li>
    </ul>
</div>





