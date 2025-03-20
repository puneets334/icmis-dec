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
                <h3 class="card-title">Filing</h3>
              </div>
              <div class="col-sm-2">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="card">
                <div class="card-header p-2" style="background-color: #fff; border-bottom:none;">
                  <h4 class="basic_heading">Refiling On Back Date</h4>
                </div>
                <div class="card-body">
                  <div class="tab-content">
                    <div class="active tab-pane">
                      <form method="post" action="<?= site_url(uri_string()) ?>">
                        <?= csrf_field() ?>
                        <div class="row">

                          <div class="col-sm-12 col-md-3 mb-3">
                            <label for="">Diary No.</label>
                            <input type="text" id="t_h_cno" name="t_h_cno" class="form-control" size="5" value="<?php echo $diary_no; ?>" oninput="sanitizeDiaryNo(this)" />
                          </div>

                          <div class="col-sm-12 col-md-3 mb-3">
                            <label for="">Diary Year</label>
                            <?php $currently_selected = date('Y');
                            $earliest_year = 1950;
                            $latest_year = date('Y');
                            print '<select id="t_h_cyt" class="form-control">';
                            foreach (range($latest_year, $earliest_year) as $i) {
                              print '<option value="' . $i . '"';
                              if ($diary_year) {
                                if ($i == $diary_year) {
                                  print 'selected="selected"';
                                }
                              } else {
                                if ($i == date('Y')) {
                                  print 'selected="selected"';
                                }
                              }
                              print '>' . $i . '</option>';
                            }
                            print '</select>'; ?>
                          </div>

                          <div class="col-sm-12 col-md-3 mb-3">
                            <button type="button" name="btn1" id="btn1" class="quick-btn mt-26" onClick="getDetails()">Submit</button>
                          </div>
                        </div>
                        <div id="div_result"></div>
                        <div id="div_show"></div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
  function sanitizeDiaryNo(input) {
    input.value = input.value.replace(/\D/g, ''); // Remove all non-numeric characters
  }
  <?php if (!empty($result)) { ?>
    getDetails();
  <?php } ?>

  function getDetails() {

    com_data = '';
    nm_cnt = '';
    cnt_data = 1;
    cntRem = '';
    cn_upd_dt = '';
    ck_totals = '';
    ck_hd_id = '';
    hd_show_dt = '';
    ans = '';
    $('#div_show').html('');
    var t_h_cno = $('#t_h_cno').val();
    var t_h_cyt = $('#t_h_cyt').val();
    var xmlhttp;
    if (window.XMLHttpRequest) {
      xmlhttp = new XMLHttpRequest();
    } else {
      xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    document.getElementById('div_result').innerHTML = '<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>';

    xmlhttp.onreadystatechange = function() {
      if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
        document.getElementById('div_result').innerHTML = xmlhttp.responseText;
        if (document.getElementById('hdChk_num_row')) {
          var hdChk_num_row = document.getElementById('hdChk_num_row').value;
          if (hdChk_num_row <= 0)
            document.getElementById('fiOD').style.display = 'none';
        }
        if (document.getElementById('hdChk_num_row_j')) {
          var hdChk_num_row_j = document.getElementById('hdChk_num_row_j').value;

          if (hdChk_num_row_j <= 0) {
            document.getElementById('fdDR').style.display = 'none';
            document.getElementById('ftAO').style.display = 'block';
          } else if (hdChk_num_row_j > 0) {
            if (document.getElementById('ftAO'))
              document.getElementById('ftAO').style.display = 'none';
          }
        }
        if (document.getElementById('hd_bnb')) {
          if (document.getElementById('hd_bnb').value == '2') {
            document.getElementById('ftAO').style.display = 'none';
          }
        }
      }
    }
    xmlhttp.open("GET", "<?php echo base_url('Filing/Defect/get_obj_data'); ?>?d_no=" + t_h_cno + "&d_yr=" + t_h_cyt, true);
    xmlhttp.send(null);
  }
</script>
<?php
die;
?>