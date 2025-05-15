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

    #reportTable thead th {
        pointer-events: none !important;
        cursor: default !important;
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
                  <h4 class="basic_heading">User Wise Diary Report</h4>
                </div>
                <div class="card-body">
                  <div class="tab-content">
                    <div class="active tab-pane">
                      <form method="post" id="push-form" action="<?= site_url(uri_string()) ?>">

                        <?= csrf_field() ?>
                        <div class="row">
                          <div class="col-sm-12 col-md-3 mb-3">
                            <label for="on_date">On Date:</label>
                            <input type="text" id="on_date" value="<?php echo isset($_POST['on_date']) ? $_POST['on_date'] : ''; ?>" name="on_date" class="form-control dtp" placeholder="On Date" required="required" autocomplete="off">
                          </div>

                          <div class="col-sm-12 col-md-3 mb-3">
                            <button type="submit" id="view" name="view" class="quick-btn mt-26">View</button>
                          </div>

                        </div>
                      </form>
                    </div>
                  </div>
                </div>

                <?php
                if (isset($_POST) && isset($_POST['on_date']) && $_POST['on_date'] != '') {

                  if (isset($_POST) && !empty($case_result)) {
                ?>
                   
                    <div class="table-responsive">
                      <table id="reportTable" class="table table-striped custom-table">
                        <thead>
                          <h3 style="text-align: center;"> User Wise Diary Report on <?php echo $_POST['on_date'] ?></h3>
                          <tr>
                            <th rowspan='2'>SNo.</th>
                            <th rowspan='2'>Diary User</th>
                            <th rowspan='2'>Section</th>
                            <th rowspan='2'>Total</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          $i = 0;
                          $total_diary = 0;
                          foreach ($case_result as $result) {
                            $i++;
                          ?>
                            <tr>
                              <td><?php echo $i; ?></td>
                              <td><?php echo $result['diary_user_name']; ?></td>
                              <td><?php echo $result['section']; ?></td>
                              <td><a target="_blank" href="<?php echo base_url('Reports/Filing/Filing_Reports/diary_user_wise_detail_report') ?>/<?= $result['usercode']; ?>/<?= $result['filing_date']; ?>/<?= str_replace(array(' ', '.'), '_', $result['diary_user_name']); ?>"> <?= $result['total']; ?></a></td>

                            </tr>
                          <?php
                            $total_diary += $result['total'];
                          }
                          ?>
                          <tr style="font-weight: bold;">
                            <td colspan="3">Total</td>
                            <td><?= $total_diary ?>
                          </tr>
                        </tbody>
                        <tfoot></tfoot>
                      </table>


                    </div>

                  <?php } else { ?>
                    <p>No record found!!!</p>
                <?php }
                } ?>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
  $(document).on("focus", ".dtp", function() {
    $('.dtp').datepicker({
      dateFormat: 'dd-mm-yy',
      changeMonth: true,
      changeYear: true,
      yearRange: '1950:2050'
    });
  });

  
</script>