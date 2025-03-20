<style>
    .panel-heading .accordion-toggle:after {
        /* symbol for "opening" panels */
        font-family: 'Glyphicons Halflings';
        /* essential for enabling glyphicon */
        content: "\e114";
        /* adjust as needed, taken from bootstrap.css */
        float: right;
        /* adjust as needed */
        color: grey;
        /* adjust as needed */
    }

    .panel-heading .accordion-toggle.collapsed:after {
        /* symbol for "collapsed" panels */
        content: "\e080";
        /* adjust as needed, taken from bootstrap.css */
    }
</style>
<div style="text-align: left; padding:0px; ">
    <p style="font-size: 1.2vw; color: #4169E1;">Case Assets</span></p>
</div>
<div class="row text-left" style="height:95vh; overflow-y: scroll;">

    <div class="panel-group" id="accordion">






        <?php
        $db = \Config\Database::connect();
        $server_address = "http://10.40.186.23:9009/supreme_court/";
        $server_address_live = "http://XXXX/supreme_court/";
        $rop_text_web = 'rop_text_web';

        $ucode = $_SESSION['login']['usercode'];
        $diary_no = $_POST['diary_no'];
        $list_dt = $_POST['listdt'];
       
        $condition = $_POST['diary_no'];
        $DNumber_main = $_POST['conn_key'];

        if ($DNumber_main != '' && $DNumber_main != null && $DNumber_main != 0) {
            //$sql_conn_list = "select STRING_AGG(diary_no::TEXT, ',') AS conn_list from main where conn_key=$DNumber_main";
            //$result_conn_list = $db->query($sql_conn_list)->getResultArray();

            $result_conn_list = $ReportModel->getConnectedList($DNumber_main);
            if (!empty($result_conn_list)) {
                foreach($result_conn_list as $row_conn_list) {
                    $condition = $row_conn_list['conn_list'];
                }
            }
        }
        $condition = trim($condition, ',');
 

        $res_org = $ReportModel->getOrderDates($condition,$rop_text_web);
        if (!empty($res_org)) {
            $sno = 1;
            foreach ($res_org as $row10) {                
        ?>
                <div class="panel panel-default pb-3">
                    <div class="panel-heading">
                        <h4 class="panel-title m-0">
                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse<?= $sno ?>">
                                List Date <?= date('d-m-Y', strtotime($row10['orderdate'])) ?>
                            </a>
                        </h4>
                    </div> 
                    <div id="collapse<?= $sno ?>" class="panel-collapse collapse <?= $sno == 1 ? 'in' : '' ?>">
                        <div class="panel-body">
                            <div class="panel-group">
                                <?php
                                $sql_or = "select * from office_report_details  where display='Y' and web_status=1 and diary_no = $diary_no and order_dt = '" . $row10['orderdate'] . "' limit 1";
                                $sql_or = $db->query($sql_or)->getResultArray();

                                if (!empty($sql_or)) {
                                    foreach($sql_or as $row_or) {
                                        $summary_date_time_histroy = '';
                                        if ($list_dt == $row_or['order_dt']) {
                                            $server_location = $server_address;
                                            $sql_or_history = "select rec_dt from office_report_details  where web_status=1 and diary_no = $diary_no and order_dt = '" . $row10['orderdate'] . "' and id != '" . $row_or['id'] . "' order by id desc";
                                            $sql_or_history = $db->query($sql_or_history)->getResultArray();
                                            if (!empty($sql_or_history)) {
                                                foreach($sql_or_history as $row_or_history) {
                                                    $summary_date_time_histroy .= '<a style="font-size:1rem;" target="_blank" class="dropdown-item" href="#">' . date('d-m-Y h:i:s A', strtotime($row_or_history['rec_dt'])) . '</a><br>';
                                                }
                                            }
                                        } else {
                                            $server_location = $server_address_live;
                                        }
                                ?>
                                        <div class="panel">
                                            <a data-toggle="collapse">Summary &raquo; </a>
                                            <div class="btn-group">
                                                <span style="font-size:1rem;" class="badge badge-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?= date('d-m-Y h:i:s A', strtotime($row_or['rec_dt'])) ?></span>
                                                <div class="dropdown-menu" style="font-size:0.7rem;">
                                                    <?= $summary_date_time_histroy ?>
                                                </div>
                                            </div>



                                            <div class="panel-collapse ">
                                                <div class="panel-body text-danger">
                                                    <strong><?= $row_or['summary'] ?: 'Not Available' ?></strong>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="panel">
                                            <a data-toggle="collapse">Officer Report &raquo;
                                            </a>
                                            <div class="panel-collapse ">
                                                <div class="panel-body">
                                                    <a href="javascript:void(0);" class="pdflink " title="Office Report" data-file="<?= $server_location . "officereport/" . substr($_POST['diary_no'], -4) . "/" . substr($_POST['diary_no'], 0, -4) . "/" . $row_or['office_repot_name']; ?>" data-title="File <?= $sno ?>">
                                                    <i class="fa fa-folder-open"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    <?php
                                    }
                                } 

                                $sql_rop = $ReportModel->getRopDetails($condition, $row10['orderdate'],$rop_text_web);
                                if (!empty($sql_rop)) {
                                    foreach($sql_rop as $row_rop) {

                                        $rjm = explode("/", $row_rop['rop_path']);
                                        if ($rjm[0] == 'supremecourt') {
                                            $rop_path = $server_address_live . $row_rop['rop_path'];
                                        } else {
                                            $rop_path = $server_address_live . "judgment/" . $row_rop['rop_path'];
                                        }
                                    ?>
                                        <div class="panel">
                                            <a data-toggle="collapse"><?= $row_rop['jo'] ?> &raquo;
                                            </a>
                                            <div class="panel-collapse ">
                                                <div class="panel-body">
                                                    <a href="javascript:void(0);" class="pdflink" title="<?= $row_rop['jo'] ?>" data-file="<?= $rop_path ?>" data-title="File <?= $sno ?>">
                                                    <i class="fa fa-folder-open"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                <?php
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div> 
            <?php
                $sno++;
            }
        } else {
            ?>
            <li class="list-group-item">
                <b>No Submission ...</b>
            </li>
        <?php
        }
        ?>

    </div> <!-- end container -->
</div>