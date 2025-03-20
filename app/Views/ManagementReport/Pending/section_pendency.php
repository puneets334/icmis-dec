<?= view('header'); ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Regular Case in Misc Head</h3>
                            </div>
                            <?= view('Filing/filing_filter_buttons'); ?>
                        </div>
                    </div>
                    <div class="card-body">
                        <form>
                            <button type="submit" style="width:15%;float:left" id="print" name="print" onclick="printDiv('printable')" class="btn btn-block btn-warning">Print</button>
                        </form>
                        <?php
                        if (isset($da_rog_result) && sizeof($da_rog_result) > 0) {
                        ?>
                            <div id="printable">
                                <h2 align="center">Pendency Report</h2>
                                <table class="table table-striped table-hover ">
                                    <thead>
                                        <tr>
                                            <th rowspan='2'>#</th>
                                            <th rowspan='2'>Dealing Assistant</th>
                                            <th rowspan='2'>Total<br />Matters<br />Allocated</th>
                                            <th colspan='4'>Matters under Specific Category</th>
                                            <th rowspan='2'>Difference</th>
                                        </tr>
                                        <tr>
                                            <th>Red</th>
                                            <th>Orange</th>
                                            <th>Green</th>
                                            <th>Yellow</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 0;
                                        $total_total = 0;
                                        $total_red = 0;
                                        $total_orange = 0;
                                        $total_green = 0;
                                        $total_diff = 0;
                                        $total_yellow = 0;
                                        foreach ($da_rog_result as $result) {
                                            $i++;
                                            $diff = $result['total'] - ($result['red'] + $result['orange'] + $result['green'] + $result['yellow']);
                                        ?>
                                            <tr>
                                                <td><?php echo $i; ?></td>
                                                <td><?php echo $result['name'] . "(" . $result['empid'] . ")<br/>" . $result['type_name'] . " / " . $result['section_name']; ?></td>
                                                <td><a target="_blank" href="<?php echo base_url() ?>index.php/Reports/cases?category=t&dacode=<?php echo $result['dacode']; ?>"><?php echo $result['total']; ?></td>
                                                <td><a target="_blank" href="<?php echo base_url() ?>index.php/Reports/cases?category=r&dacode=<?php echo $result['dacode']; ?>"><?php echo $result['red']; ?></td>
                                                <td><a target="_blank" href="<?php echo base_url() ?>index.php/Reports/cases?category=o&dacode=<?php echo $result['dacode']; ?>"><?php echo $result['orange']; ?></td>
                                                <td><a target="_blank" href="<?php echo base_url() ?>index.php/Reports/cases?category=g&dacode=<?php echo $result['dacode']; ?>"><?php echo $result['green']; ?></td>
                                                <td><a target="_blank" href="<?php echo base_url() ?>index.php/Reports/cases?category=y&dacode=<?php echo $result['dacode']; ?>"><?php echo $result['yellow']; ?></td>
                                                <td><a target="_blank" href="<?php echo base_url() ?>index.php/Reports/cases?category=d&dacode=<?php echo $result['dacode']; ?>"><?php echo $diff; ?></td>
                                            </tr>

                                        <?php
                                            $total_total += $result['total'];
                                            $total_red += $result['red'];
                                            $total_orange += $result['orange'];
                                            $total_green += $result['green'];
                                            $total_yellow += $result['yellow'];
                                            $total_diff += $diff;
                                        }
                                        ?>
                                        <tr style="font-weight: bold;">
                                            <td colspan="2">Total</td>
                                            <td><?= $total_total ?></td>
                                            <td><?= $total_red ?></td>
                                            <td><?= $total_orange ?></td>
                                            <td><?= $total_green ?></td>
                                            <td><?= $total_yellow ?></td>
                                            <td><?= $total_diff ?></td>
                                        </tr>
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
    <div id="div_print">
        <div id="header" style="background-color:White;"></div>
        <div id="footer" style="background-color:White;"></div>
    </div>
</section>
<script>
    $(document).on("click", "#print", function() {
        var prtContent = $("#printable").html();
        var temp_str = prtContent;
        var WinPrint = window.open('', '', 'left=100,top=0,align=center,width=800,height=1200,menubar=1,toolbar=1,scrollbars=1,status=1,cellspacing=1');
        WinPrint.document.write(temp_str);
        WinPrint.document.close();
        WinPrint.focus();
        WinPrint.print();
    });
</script>