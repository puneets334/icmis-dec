<?= view('header'); ?>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Judge Category</h3>
                            </div>
                            <?= view('Filing/filing_filter_buttons'); ?>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php
                        echo form_open();
                        ?>
                        <div class="col-md-12">
                            <div class="well">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <input type="radio" name="mf" id="mf" class="rd_active" value="M" checked>
                                        Miscelleneous &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <input type="radio" name="mf" id="mf" class="rd_active" value="F"> Regular
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    </div>
                                </div>
                                <br />
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-1">
                                            <button type="submit" id="view" name="view"
                                                class="btn btn-block btn-warning">view</button>
                                        </div>
                                        <div class="col-md-1 text-end">
                                            <button type="submit" id="print" name="print"
                                                onclick="printDiv('printable')"
                                                class="btn btn-block btn-warning">Print</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                        <br>
                        <?php
                        if (isset($result) && sizeof($result) > 0) {
                        ?>
                            <div id="printable">
                                <p align="center" style="font-weight: bold;">
                                    <?php if ($mf == 'M')
                                        $matters = 'MISCELLENEOUS';
                                    else $matters = 'REGULAR';
                                    echo "ROSTER OF THE WORK FOR $matters FRESH CASES,NOTIFIED UNDER THE ORDER OF HON'BLE THE CHIEF JUSTICE OF INDIA";
                                    ?>
                                </p>
                                <table class="table table-striped custom-table">
                                    <tbody>
                                        <?php
                                        foreach ($result as $k => $v) {
                                           
                                        ?>
                                         <tr>
                                         <td colspan="2" align="center" style="font-weight:bold; font-size:20px;"><?php echo $k; ?></td>
                                            </tr>
                                            <tr>
                                                <td align="center" style="font-weight:bold;">Subject Category Code</td>
                                                <td align="center" style="font-weight:bold;">Category Description</td>
                                            </tr>
                                            <?php

                                            // $subject = explode("#", $result1['catg']);
                                            // $count = count($subject);
                                            // for ($i = 0; $i <= $count; $i++) {
                                            //     // print_r($subject);
                                            //     echo '<tr><td>' . (string)$subject[0] . '</td>
                                            //             <td>' . (string)$subject[1] . '</td>
                                            //     </tr>';
                                            // }
                                            for($i = 0; $i < count($v); $i++){
                                                echo '<tr><td align="center">' . $v[$i]['subject_category_code'] . '</td>
                                                        <td align="center">' . $v[$i]['category_description'] . '</td>
                                                      </tr>';

                                            }
                                            ?>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                            </tr>
                                           
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    function printDiv(printable) {
        var printContents = document.getElementById(printable).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }
</script>