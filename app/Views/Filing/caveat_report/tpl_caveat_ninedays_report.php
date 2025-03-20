<div id="printable">
	<div class="table-responsive">
		<table id="reportTable1" class="table table-striped custom-table">
			<thead>
				<tr>
					<th>S.No.</th>
					<th>CAVEAT NO.</th>
					<th>CAUSE TITLE</th>
					<th>CAVEATOR</th>
					<th>CAVEAT FILING DATE</th>
					<!--<th>Section</th>-->
					<th>NO. OF DAYS</th>
				</tr>
			</thead>

			<tbody>
				<?php
				$s_no = 1;
				if (!empty($caveat_list)) {
					foreach ($caveat_list as $result) {                      
				?>
						<tr>
							<td><?php echo $s_no; ?></td>
							<td><?php echo $result['caveat_no']; ?></td>
							<td><?php echo $result['cause_title']; ?></td>
							<td><?php echo $result['caveator']; ?></td>
							<td><?php echo date("d-m-Y", strtotime($result['caveat_filing_date'])); ?></td>
							<td><?php echo $result['no_of_days']; ?></td>

						</tr>
					<?php
						$s_no++;
						//echo str_replace('&', 'and', $result['state']);
					}   //for each
				} else {
					?>
					<tr>
						<td colspan="100%">Record Not Found.</td>
					</tr>

				<?php } ?>
			</tbody>
		</table>
	</div>
</div>

<script>
    $("#reportTable1").DataTable({
        "responsive": true,
        "lengthChange": true,
        "autoWidth": false,
        "dom": 'Bfrtip',
        "bProcessing": true,
        "buttons": [
            {
                extend: 'excel',
                text: 'Excel'
            },
            {
                extend: 'pdf',
                text: 'PDF'
            },
            {
                extend: 'print',
                text: 'Print',
                customize: function (win) {
                  
                    $(win.document.body)
                        .css('font-size', '10pt') 
                        .prepend(
                            '<h5 style="text-align: center;">Diary Report</h5>' 
                        );

                    $(win.document.body).find('div').remove(); 

                    $(win.document.body).find('table')
                        .addClass('display')
                        .css({
                            'font-size': '10pt',
                            'width': '100%'
                        });
                },
                exportOptions: {
                    columns: ':visible', 
                    modifier: {
                        page: 'all'
                    }
                }
            }
        ]
    });
</script>
