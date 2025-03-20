<?= view('header') ?>
<style>
    div.dataTables_wrapper div.dataTables_filter label {
        display: flex;
        justify-content: end;
    }

    div.dataTables_wrapper div.dataTables_filter label input.form-control {
        width: auto !important;
        padding: 4px;
    }
</style>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <!-- <div class="col-sm-10">
                                <h3 class="card-title">Reports</h3>
                            </div> -->
                            <div class="col-sm-2">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">

                            <div class="card">

                                <span style="color:red">* Red Color :- Matter to be listed within 3 days(Including today). </span><br />
                                <span style="color:orange">* Orange Color :- Matter to be listed after 3 days(Including today). </span><br />
                                <span style="color:gray">* Gray/White Color :- Matters is proposed to list in feature / No feature Listing date</span>





                            </div>
                            <!-- Print Button -->
                            <!-- <div>
                                <input type="button" id="prnnt1" class="btn-primary" value="Print" class="btn btn-primary mt-4" />
                            </div> -->
                            <br /><br />
                        </div>
                        <div class="card-body" id="prnnt">
                            <div class="tab-content">
                                <div class="active tab-pane">
                                    <div class="table-responsive md-12">
                                        <table id="reportTable1" class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>S.No.</th>
                                                    <th>Case No.</th>
                                                    <th> Causetitle</th>
                                                    <?php
                                                    if ($usercode == '9796') {
                                                    ?>
                                                        <th>
                                                            Listed/Updated For
                                                        </th>
                                                    <?php } ?>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php

                                                $sno = 1;
                                                foreach ($results as $row) {
                                                ?>
                                                    <?php
                                                    $today = strtotime(date('Y-m-d'));
                                                    $dayAfterTommorow = strtotime(date('Y-m-d', strtotime("+2 days")));
                                                    $r_h_dt = $Monitoring->getNextdate($row['diary_no']);
                                                    $clr = '';
                                                    $nextDt = $r_h_dt['next_dt'];
                                                    $trhide = '';
                                                    $trhide = '';
                                                    if ($r_h_dt['clno'] != 0 && $r_h_dt['brd_slno'] != 0 && $dayAfterTommorow >= $nextDt && $nextDt >= $today) {
                                                        $clr = "red";
                                                        if ($category == 1 || $category == 0) {
                                                            $trhide = '';
                                                            $sno++;
                                                        }
                                                    } else if ($r_h_dt['clno'] != 0 && $r_h_dt['brd_slno'] != 0 && $nextDt >= $today) {
                                                        $clr = "Orange";
                                                        if ($category == 2 || $category == 0) {
                                                            $trhide = '';
                                                            $sno++;
                                                        }
                                                    } else {
                                                        $clr = "light-gray";
                                                        if ($category == 0) {
                                                            $trhide = '';
                                                            $sno++;
                                                        }
                                                    }
                                                    ?>
                                                    <tr style="background-color: <?= $clr ?>;<?= $trhide ?>">
                                                        <td>
                                                            <?php
                                                            echo $sno++;
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <?= $row['case_no'] ?>
                                                        </td>
                                                        <td>
                                                            <?= $row['cause_title'] ?>
                                                        </td>

                                                        <td>

                                                            <?php $r_h_dt['next_dt']; ?>
                                                        </td>
                                                    </tr>
                                                <?php

                                                }


                                                ?>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
    </div>
</section>

<script>
    $(document).on("click", "#prnnt1", function() {
        var prtContent = $("#prnnt").html();
        var temp_str = prtContent;
        var WinPrint = window.open('', '', 'left=100,top=0,align=center,width=800,height=1200,menubar=1,toolbar=1,scrollbars=1,status=1');
        WinPrint.document.write(temp_str);
        WinPrint.document.close();
        WinPrint.focus();
        WinPrint.print();
    });
</script>