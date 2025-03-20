<?= view('header'); ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-md-10">
                                <h3 class="card-title">DEPARTMENT CASES NOT GO BEFORE JUDGE (As on <?php echo date('d-m-Y'); ?>)</h3>
                            </div>
                            <?= view('Filing/filing_filter_buttons'); ?>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php
                        echo form_open();
                        csrf_token();
                        // include('../../mn_sub_menu.php');
                        ?>
                        <div id="dv_content1">

                            <div style="text-align: center">
                                <div id="prnnt" style="text-align: center; font-size:13px;">
                                <h3>DEPARTMENT CASES NOT GO BEFORE JUDGE<br>(As on <?php echo date('d-m-Y');?>)</h3>
                                    <?php
                                    if (count($result_array) > 0) {
                                    ?>
                                        <div class="table-responsive">
                                            <table class="table table-striped custom-table">
                                                <thead>
                                                    <tr>
                                                        <td>SrNo.</td>
                                                        <td>Judge</td>
                                                        <td>Department</td>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $sno = 1;

                                                    foreach ($result_array as $ro) {
                                                        $sno1 = $sno % 2;



                                                        if ($sno1 == '1') { ?>
                                                            <tr>
                                                            <?php } else { ?>
                                                            <tr>
                                                            <?php
                                                        }


                                                            ?>
                                                            <td align="left" style='vertical-align: top;'><?php echo $sno; ?></td>
                                                            <td align="left" style='vertical-align: top;'>
                                                                <?php echo $ro['jname'];  ?></td>
                                                            <td align="left" style='vertical-align: top;'>
                                                                <?php echo $ro['deptname'];  ?></td>
                                                            </tr>
                                                        <?php
                                                        $sno++;
                                                    }
                                                        ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php
                                    } else {
                                        echo "No Recrods Found";
                                    }
                                    ?>

                                </div>
                                <?php echo form_close(); ?>
                            </div>
                            <span id="toggle_hw"
                                style="color: #0066cc; font-weight: bold; cursor: pointer; padding-right: 1px;">
                            </span>
                            <input name="prnnt1" type="button" id="prnnt1" value="Print">
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