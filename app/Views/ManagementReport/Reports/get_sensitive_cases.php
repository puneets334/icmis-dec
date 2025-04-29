<style media="print">
    
   .dataTables_filter,
    .dataTables_paginate,
    .dataTables_info,
    .dataTables_length {
        display: none !important;
    }
		
	.dataTables_wrapper .dataTables_filter,
		.dataTables_wrapper .dataTables_paginate,
		.dataTables_wrapper .dataTables_info,
		.dataTables_wrapper .dataTables_length {
			display: none !important;
    }
	
	.card-header {
		 display: none !important;
	}
	
	 #prnnt1 {
		 display: none !important;
	  }
	  
	  #btn_sensetive {
		 display: none !important;
	  } 

      table.dataTable>thead .sorting,
    table.dataTable>thead {
        background-color: #0d48be !important;
        color: #fff !important;
        white-space: nowrap;
    }

    table.dataTable>thead .sorting_disabled,
    table.dataTable>thead {
        background-color: #0d48be !important;
        color: #fff !important;
        white-space: nowrap;
    }
    table tfoot tr th {
        background-color: #0d48be !important;
        color: #fff !important;
        white-space: nowrap;
    }
    .dataTables_filter
    {
        margin-top: 10px;
    }
</style>
<div id="prnnt">
    <center class="m-1"><h3 style="margin-top: 45px;">SENSITIVE CASES</h3></center>
    <table id="reportTable" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>S. No.</th>
                <th>Diary No.</th>
                <th>Case No.</th>
                <th>Cause Title</th>
                <th>Coram</th>
                <th>Not Before</th>
                <th>Reason</th>
                <th>Next Date</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sno = 1;
            if(!empty($reports)) {
                foreach ($reports as $row) {
                    ?>
                    <tr>
                        <td data-key="S. No."><?php echo $sno; ?></td>
                        <td data-key="Diary No.">
                            <?php echo substr($row['diary_no'], 0, strlen($row['diary_no']) - 4); ?>
                            -
                            <?php
                            echo substr($row['diary_no'], -4);
                            echo "<br>" . $row['ten_sec'];
                            ?>
                        </td>
                        <td data-key="Case No.">
                            <?php
                            if ($row['active_fil_no'] != '') {
                                if ($row['reg_no_display']) {
                                    echo $row['reg_no_display'];
                                } else {
                                    echo $row['short_description'] . " / " . substr($row['active_fil_no'], 3) . "/" . $row['active_fil_dt'];
                                }
                            }
                            ?>
                        </td>
                        <td>
                            <?php echo $row['pet_name'] . " Vs. " . $row['res_name']; ?>
                        </td>
                        <td>
                            <?php
                            $db = \Config\Database::connect();
                            if ($row['coram'] != '' and $row['coram'] != '0') {
                                $sq = "select GROUP_CONCAT(abbreviation) abr from master.judge where jcode in (".$row['coram'].") and jtype = 'J' GROUP BY jtype";
                                // $sqqq =  mysql_query($sq) or die("Error: " . __LINE__ .  mysql_error());
                                $ros = $db->query($sq);
                                if ($ros->getNumRows() >= 1) {
                                    $result = $ros->getResultArray();
                                } else {
                                    $result[0]['abr'] = "";
                                }
                                echo $result[0]['abr'];
                            }
                            ?>
                        </td>
                        <td>
                            <?php
                            f_get_ntl_judge($row['diary_no']);
                            f_get_ndept_judge($row['diary_no']);
                            f_get_category_judge($row['diary_no']);
                            f_get_not_before($row['diary_no']);
                            ?>
                        </td>
                        <td>
                            <?php echo $row['reason']; ?>
                        </td>
                        <td>
                            <?php
                            if ($row['next_dt'] != '0000-00-00' && $row['next_dt'] != NULL && get_display_status_with_date_differnces($row['next_dt']) == 'T')
                                echo date('d-m-Y',  strtotime($row['next_dt']));
                            ?>
                        </td>
                    </tr>
                    <?php
                    $sno++;
                }
            } else {
                ?>
                <div class="cl_center"><b>No Record Found</b></div>
            <?php } ?>
        </tbody>
    </table>
</div>
<div>
    <input name="prnnt1" type="button" id="prnnt1" value="Print">
</div>
<script>
$(function() {
    var dynamicTitle = "Sensensitive Cases"; 
    var dynamicFilename = "Sensensitive_Cases"; 

    var table = $("#reportTable").DataTable({
        responsive: true,
        searching: true,
        lengthChange: false,
        autoWidth: true,
        pageLength: 20,
        processing: true,
        ordering: true,
        paging: true,
        buttons: [
            {
                extend: 'print',
                title: dynamicTitle,
                messageTop: 'Generated on: ' + new Date().toLocaleDateString(),
                filename: dynamicFilename
            },
            {
                extend: 'pdf',
                title: dynamicTitle,
                messageTop: 'Generated on: ' + new Date().toLocaleDateString(),
                filename: dynamicFilename,
                orientation: 'landscape',
                pageSize: 'A4' 
            }
        ]
    });

    table.buttons().container().appendTo('#reportTable_wrapper .col-md-6:eq(0)');
});
</script>
