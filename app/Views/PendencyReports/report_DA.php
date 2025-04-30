
<?= view('header'); ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">DA wise statistics for physical verification</h3>
                            </div>
                        </div>
                    </div>
	<div class="card-body">
		<center><h3 class="page-header">Dealing Assistant wise Statistics of matters Pending for Physical Verification</h2></center>
		<?php if(!empty($report_DA)){ ?>
				<div class="table-responsive">
					<table class="table table-striped custom-table" id="example1">
						  <thead>
									<tr>
										<th>S. No</th>
										<th>Dealing Assistant</th>
										<th>Total pending matters</th>
										<th>Verified</th>
										<th>Updated as not with me</th>
										<th>Not verified</th>
									</tr>
						</thead>	
						<tbody>
							<?php
								$i = 1;
								foreach($report_DA as $value): ?>
										<tr>
											<td><?=$i?></td>
											<td><?php
													if($value['name']=='' && $value['empid']=='' && $value['section_name']==''){
														echo 'No Dealing Assistant';
													}else{
														echo $value['name'].' ('.$value['empid'].') / '.$value['section_name'];
													}

												?></td>
											<td><?=$value['total_pending_metters']?></td>
											<td><?=$value['verified']?></td>
											<td><?=$value['not_with_me']?></td>
											<td><?=$value['not_verified']?></td>
										</tr>
							<?php
							$i++;
								endforeach;
							?>

							<tr>
								<th id="heading" colspan="2" style="text-align:center;"></th>
								<th id="1"></th>
								<th id="2"></th>
								<th id="3"></th>
								<th id="4"></th>
								<th style="display:none;"></th>                    
							 </tr>

						</tbody>
				   </table>
				</div>
		    <?php }else{?>
                  <h3 styl="text-align:center">Records Not Found..!!</h3>		
			<?php }?>	  
			</div>
		</div>
	 </div>
  </div>
</div>
</section>

<script>
    $(document).ready(function() {
        
        var title = function () {
            return 'Dealing-assistant-report'; }

        $('#example1').DataTable( {

            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'csv',
                    title: 'Dealing-assistant-report',
                    exportOptions: {
                        columns: [0,1,2,3,4,5],
                        stripHtml: true
                    }
                },
                {
                    extend: 'excel',
                    title:title,
                    exportOptions: {
                        columns: [0,1,2,3,4,5],
                        stripHtml: true
                    }
                },
                {
                    extend: 'pdfHtml5',
                    orientation: 'portrait',
                    pageSize: 'A4',
                    title:title,
                    exportOptions: {
                        columns: [0,1,2,3,4,5],
                        stripHtml: true
                    }
                },
                {
                    extend: 'print',
                    title:title,
                    exportOptions: {
                        columns: [0,1,2,3,4,5],
                        stripHtml: true
                    }
                }
            ] ,

            "aaSorting": [],

            "footerCallback": function ( row, data, start, end, display ) {
                var api = this.api(), data;

                // Remove the formatting to get integer data for summation
                var intVal = function ( i ) {
                    return typeof i === 'string' ?
                        i.replace(/[\$,]/g, '')*1 :
                        typeof i === 'number' ?
                            i : 0;
                };
                // Total over all pages
                total2 = api
                    .column( 2 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                total3 = api
                    .column( 3 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
                
                total4 = api
                    .column( 4 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                total5 = api
                    .column( 5 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );            

                
                // Update footer
                /*$( api.column( 2 ).footer() ).html(total2);
                $( api.column( 3 ).footer() ).html(total3);
                $( api.column( 4 ).footer() ).html(total4);
                $( api.column( 5 ).footer() ).html(total5);*/

                $("#heading").html('Total');
                $("#1").html(total2);
                $("#2").html(total3);
                $("#3").html(total4);
                $("#4").html(total5);
            }

        } );
    } );
</script>
