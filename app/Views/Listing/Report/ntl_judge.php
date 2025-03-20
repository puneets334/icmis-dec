<?= view('header'); ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-md-10">
                                <h3 class="card-title">ADVOCATE ON RECORD NOT GO BEFORE JUDGE (As on <?php echo date('d-m-Y'); ?>)</h3>
                            </div>
                            <?= view('Filing/filing_filter_buttons'); ?>
                        </div>
                    </div>


                    <div class="card-body">
                        <div class="text-center">
                            <div id="prnnt" class="text-center" style="font-size: 13px;">
                                <?php if (isset($result_array)) { ?>
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th class="font-weight-bold">SrNo.</th>
                                                <th class="font-weight-bold">Judge</th>
                                                <th class="font-weight-bold">AOR Name</th>
                                                <th class="font-weight-bold">AOR Code</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $sno = 1;
                                            foreach ($result_array as $ro) {
                                                $sno1 = $sno % 2;
                                            ?>
                                                <tr>
                                                    <td><?php echo $sno; ?></td>
                                                    <td><?php echo $ro['jname']; ?></td>
                                                    <td><?php echo $ro['name']; ?></td>
                                                    <td><?php echo $ro['aor_code']; ?></td>
                                                </tr>
                                            <?php
                                                $sno++;
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                <?php
                                } else {
                                    echo "<p>No Records Found</p>";
                                }
                                ?>
                            </div>
                        </div>

                        <div class="text-center mt-3">
                            <span id="toggle_hw" class="text-primary font-weight-bold" style="cursor: pointer;"></span>
                            <input name="prnnt1" type="button" id="prnnt1" value="Print" class="btn btn-primary">
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