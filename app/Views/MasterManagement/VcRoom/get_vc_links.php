<?//= view('header') ?>
<style>
 #result .grid-container { 
  display: grid;
  grid-template-columns: auto auto auto auto auto;
  
  padding: 0px;
}
#result .grid-container .grid-item {
  background-color: rgba(255, 255, 255, 0.8);
  border: 1px solid rgba(0, 0, 0, 0.8);
  padding: 10px;
  font-size: 15px;
  text-align: center;
  cursor: all-scroll;
}
/* #result .grid-container .grid-item:hover {
  background-color: #223094;
   cursor: all-scroll;
} */
</style> 


    <h3>Supreme Court Hearings - VC Links for <?= date('d-M-Y', strtotime($vc_date)); ?></h3>
    <div class="grid-container">
        <?php if (!empty($links)): ?>
            <?php $i = 1; ?>
            <?php foreach ($links as $rs): ?>
                <?php
                //pr($links);
                if ($rs['court_sorting'] == 21) {
                    $rs['court_sorting'] = 'R1';
                }
                if ($rs['court_sorting'] == 22) {
                    $rs['court_sorting'] = 'R2';
                }

                $vc_url = ($vc_date == date('Y-m-d')) ? $rs['vc_url'] : '';
                $cancelledMessage = empty($rs['vc_url']) ? "<font color=red size=4><i> Cancelled </i></font>" : "";
                ?>

                <button class="grid-item" style="margin-bottom: 0px;" id="<?= $vc_url; ?>" onClick="open_link(this.id)" type="button">
                    <b>Ct no. <?= $rs['court_sorting']; ?></b>
                    <hr style="margin: 6px !important;">
                    <?= $rs['judge_name']; ?><br>
                    <?= $cancelledMessage; ?>
                    <b>
                        <font color=blue><br><?= $rs['frm_time']; ?></font>
                    </b>
                </button>

                <?php
                $i++;
                if ($i == 5) {
                    $i = 1; // Reset counter
                }
                ?>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No Links Available</p>
        <?php endif; ?>
    </div>

    <?php die;?>

 