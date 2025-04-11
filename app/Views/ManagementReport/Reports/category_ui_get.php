<style>
.table-striped tr:nth-child(odd) td {
    background: #fff !important;
    box-shadow: none;
    border: 1px solid #8080805e;
   
}

.table-striped tr:nth-child(even) td {
    background: #f5f5f5;
	border: 1px solid #8080805e;
    
}

td {
    line-height: 1.5 !important;
	 text-align: center;
}

th {
    line-height: 1.5 !important;
	text-align: center;
}
</style>

<div>
    <?php if (count($results) > 0): ?>
        <h3 style="text-align: center; line-height: 1.5;">Category wise Judge Report as on <?= date('d-m-Y h:i:s A') ?></h3>
        <div class="table-responsive">
			<table class="table table-striped custom-table" id="example1">
				<thead>
					<tr style="background-color:darkgray;">
						<th>SNo</th>
						<th>Case No</th>
						<th>Cause Title</th>
						<th>Group Count</th>
						<th>Coram</th>
						<th>Subject Category</th>
						<th>Section</th>
						<th>DA</th>
					</tr>
				</thead>
				<tbody>
            <?php foreach ($results as $data): ?>
                <tr>
                    <td><?= $data['sno'] ?></td>
                    <td><?= $data['case_no'] ?></td>
                    <td><?= $data['cause_title'] ?></td>
                    <td><?= $data['group_count'] ?></td>
                    <td><?= $data['coram'] ?></td>
                    <td><?= $data['subject_category'] ?></td>
                    <td><?= $data['section'] ?></td>
                    <td><?= $data['da'] ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
	</div>	
    <?php else: ?>
         <p style="text-align: center;"><h3>No Records Found</h3></p>
    <?php endif; ?>
</div>

<script>
    $(document).ready(function () {
        $('#example1').DataTable({
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'excel', 
					className: 'btn btn-primary glyphicon glyphicon-list-alt',
                    filename: 'Categorywise_Judges_report_as_on <?= date('d-m-Y h:i:s A') ?>',
                    title: 'List of categorywise Judges Report as on <?= date('d-m-Y h:i:s A') ?>',
                    text: 'Export to Excel',
                    autoFilter: true,
                    sheetName: 'Sheet1'
                },
                {
                    extend: 'pdf', 
					className: 'btn btn-primary glyphicon glyphicon-file',
                    filename: 'Categorywise_Judges_report_as_on <?= date('d-m-Y h:i:s A') ?>',
                    title: 'List of categorywise Judges Report as on <?= date('d-m-Y h:i:s A') ?>',
                    pageSize: 'A4',
                    orientation: 'landscape',
                    text: 'Save as Pdf'
                },
                {
                    extend: 'print', 
					className: 'btn btn-primary glyphicon glyphicon-print',
                    title: 'List of categorywise Judges Report as on <?= date('d-m-Y h:i:s A') ?>',
                    pageSize: 'A4',
                    orientation: 'portrait',
                    text: 'Print'
                }
            ],
            paging: true,
            ordering: false,
            info: false,
            searching: true
        });
    });
</script>

</body>
</html>
