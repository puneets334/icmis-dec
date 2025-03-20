<?php  if($status == 'success'){ ?>
    <table id="tblCasesForUploading" class="table table-striped table-hover">
        <thead>
            <tr>
                <th width="25%">Case Number</th>
                <th width="25%">Causetitle</th>
                <th width="10%">Upload Date</th>
                <th width="20%">Uploaded By</th>
                <th width="10%">Original Record</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $s_no = 1;
                foreach ($data as $case) {
            ?>
                <tr>

                    <?php
                        $diarynumber = $case['diary_no'];
                        $diarynumber = "DIary No. " . substr($diarynumber, 0, -4) . "/" . substr($diarynumber, -4);
                    ?>

                    <td>
                        <?php echo $case['case_no']; ?>
                    </td>
                    <td>
                        <?php echo $case['causetitle']; ?>
                    </td>
                    <td>
                        <?php
                        if ($case['updated_on'] != null && $case['updated_on'] != "") {
                            $date = date_create($case['updated_on']);
                            echo date_format($date, "d-m-Y H:i:s A");
                        }

                        ?>
                    </td>
                    <td>
                        <?php echo $case['uploaded_by']; ?>
                    </td>
                    <td>
                        <!-- <a class="btn btn-primary" target="_blank" href=" <?= 'http://' . $_SERVER['HTTP_HOST'] . '/' . WRITEPATH  .'/san_home/.'. $case['file_name'] . '.pdf'  ?>">Download</a> -->
                        <!-- <a class="btn btn-primary" target="_blank" href=" <?= 'http://' . $_SERVER['HTTP_HOST'] . '/writable' .'/san_home'. $case['file_name'] . '.pdf'  ?>">Download</a> -->
                        <a class="btn btn-primary" target="_blank" href="<?= base_url('Judicial/OriginalRecord/UploadScannedFile/downloadpdf_file/' . base64_encode($case['file_name'])) ?>">Download</a>                                              
                    </td>

                </tr>
            <?php

            }   //for each
            ?>
        </tbody>
    </table>
<?php }else{ ?>
    <div class="text-center"><h4 class="text-danger">No cases found for the given date range and user.</h4></div>
<?php } ?>
