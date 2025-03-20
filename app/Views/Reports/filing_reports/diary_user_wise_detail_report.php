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
              <div class="col-sm-10">
                <h3 class="card-title">Reports</h3>
              </div>
              <div class="col-sm-2">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="card">
                <div class="card-header p-2" style="background-color: #fff; border-bottom:none;">
                  <!-- <h4 class="basic_heading">User Wise Diary Report</h4> -->
                </div>


                <div class="box-footer">
                <button type="button" id="print" name="print" onclick="printDiv('printable')" class="btn btn-block btn-warning">Print</button>
                </div>
                <!-- Main content -->
                <section class="content">

                  <?php
                  if (isset($case_result) && sizeof($case_result) > 0 && is_array($case_result)) {
                  ?>

                   
                    <div id="printable" class="dataTables_wrapper dt-bootstrap4">
                      <table width="100%" id="datatable" class="table table-striped custom-table">
                        <thead>
                          <?php $name1 = str_replace('_', ' ', $name); ?>
                          <h3 style="text-align: center;"> Filing done by <?php echo $name1; ?> on <?php echo date('d-m-Y', strtotime($on_date)); ?></h3>
                          <tr>
                            <th rowspan='2'>SNo.</th>
                            <th rowspan='2'>Diary No.</th>
                            <th rowspan='2'>Case Type</th>
                            <th rowspan='2'>Cause Title</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          $i = 0;
                          $total_diary = 0;
                          foreach ($case_result as $result) {
                            $i++;
                            $result['total'] = 0;
                          ?>
                            <tr>
                              <td><?php echo $i; ?></td>
                              <td><?php echo $result['diaryno']; ?></td>
                              <td><?php echo $result['casetype']; ?></td>
                              <td><?php echo $result['causetitle']; ?></td>

                            </tr>
                          <?php
                            $total_diary += $result['total'];
                          }
                          ?>

                        </tbody>
                        <tfoot></tfoot>
                      </table>

                    <?php } ?>
                    </div>
                </section>

              </div>
            </div>
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
  // $(function() {
  //   $("#datatable").DataTable({
  //     "responsive": true,
  //     "lengthChange": false,
  //     "autoWidth": false,
  //     "buttons": ["copy", "csv", "excel", {
  //         extend: 'pdfHtml5',
  //         orientation: 'landscape',
  //         pageSize: 'LEGAL'
  //       },
  //       {
  //         extend: 'colvis',
  //         text: 'Show/Hide'
  //       }
  //     ],
  //     "bProcessing": true,
  //     "extend": 'colvis',
  //     "text": 'Show/Hide'
  //   }).buttons().container().appendTo('#query_builder_wrapper .col-md-6:eq(0)');

  // });
  // $(document).ready(function() {
  //   $('#reportTable').DataTable({
  //     "bSort": true,
  //     dom: 'Bfrtip',
  //     "scrollX": true,
  //     iDisplayLength: 20,

  //     buttons: [{
  //       extend: 'print',
  //       orientation: 'landscape',
  //       pageSize: 'A4'
  //     }]
  //   });
  // });
</script>