<?php

if ($_REQUEST['u_t'] == '1') {

  $ddl_court = '';
  $txt_order_date = '';
  $ddl_bench = '';
  $ddl_st_agncy = '';
  $ddl_ref_case_type = '';
  $txt_ref_caseno = '';
  $ddl_ref_caseyr = '';

  $ddl_court_t = '';
  $txt_order_date_t = '';
  $ddl_bench_t = '';
  $ddl_st_agncy_t = '';
  $ddl_ref_case_type_t = '';
  $txt_ref_caseno_t = '';
  $ddl_ref_caseyr_t = '';




  if ($_REQUEST['ddl_court'] != '') {
    $ddl_court = " ct_code = '$_REQUEST[ddl_court]'";
    $ddl_court_t = "  a.ct_code = b.ct_code";
  }
  if ($_REQUEST['txt_order_date'] != '') {
    $_REQUEST['txt_order_date'] = date('Y-m-d',  strtotime($_REQUEST['txt_order_date']));
    $txt_order_date = " and lct_dec_dt = '$_REQUEST[txt_order_date]'";
    $txt_order_date_t = " AND a.lct_dec_dt = b.lct_dec_dt";
  }
  if ($_REQUEST['ddl_bench'] != '') {
    $ddl_bench = " and  l_dist = '$_REQUEST[ddl_bench]'";
    $ddl_bench_t = " AND a.l_dist = b.l_dist";
  }
  if ($_REQUEST['ddl_st_agncy'] != '') {
    $ddl_st_agncy = " and  l_state = '$_REQUEST[ddl_st_agncy]'";
    $ddl_st_agncy_t = " AND a.l_state = b.l_state";
  }
  if ($_REQUEST['ddl_ref_case_type'] != '') {
    $ddl_ref_case_type = " and  lct_casetype = '$_REQUEST[ddl_ref_case_type]'";
    $ddl_ref_case_type_t = " and a.lct_casetype=b.lct_casetype";
  }
  if ($_REQUEST['txt_ref_caseno'] != '') {
    $txt_ref_caseno = " and  lct_caseno = '$_REQUEST[txt_ref_caseno]'";
    $txt_ref_caseno_t = " and a.lct_caseno=b.lct_caseno";
  }
  if ($_REQUEST['ddl_ref_caseyr'] != '') {
    $ddl_ref_caseyr = " and  lct_caseyear = '$_REQUEST[ddl_ref_caseyr]'";
    $ddl_ref_caseyr_t = " and a.lct_caseyear=b.lct_caseyear";
  }
  $fst = intval($_REQUEST['nw_hd_fst']);
  $inc_val = intval($_REQUEST['inc_val']);
} else {
  $ddl_court = '';
  $txt_order_date = '';
  $ddl_bench = '';
  $ddl_st_agncy = '';
  $ddl_ref_case_type = '';
  $txt_ref_caseno = '';
  $ddl_ref_caseyr = '';

  $ddl_court_t = '';
  $txt_order_date_t = '';
  $ddl_bench_t = '';
  $ddl_st_agncy_t = '';
  $ddl_ref_case_type_t = '';
  $txt_ref_caseno_t = '';
  $ddl_ref_caseyr_t = '';


  if ($_REQUEST['ddl_court'] != '') {
    $ddl_court = " ct_code = '$_REQUEST[ddl_court]'";
    $ddl_court_t = "  a.ct_code = b.ct_code";
  }
  if ($_REQUEST['txt_order_date'] != '') {
    $_REQUEST['txt_order_date'] = date('Y-m-d',  strtotime($_REQUEST['txt_order_date']));
    $txt_order_date = " AND lct_dec_dt = '$_REQUEST[txt_order_date]'";
    $txt_order_date_t = " AND a.lct_dec_dt = b.lct_dec_dt";
  }
  if ($_REQUEST['ddl_bench'] != '') {
    $ddl_bench = "  AND l_dist = '$_REQUEST[ddl_bench]'";
    $ddl_bench_t = " AND a.l_dist = b.l_dist";
  }
  if ($_REQUEST['ddl_st_agncy'] != '') {
    $ddl_st_agncy = "  AND l_state = '$_REQUEST[ddl_st_agncy]'";
    $ddl_st_agncy_t = " AND a.l_state = b.l_state";
  }
  if ($_REQUEST['ddl_ref_case_type'] != '') {
    $ddl_ref_case_type = "  AND lct_casetype = '$_REQUEST[ddl_ref_case_type]'";
    $ddl_ref_case_type_t = " and a.lct_casetype=b.lct_casetype";
  }
  if ($_REQUEST['txt_ref_caseno'] != '') {
    $txt_ref_caseno = "  AND  trim(leading '0' from lct_caseno) = '$_REQUEST[txt_ref_caseno]'";
    $txt_ref_caseno_t = " and  trim(leading '0' from a.lct_caseno)= trim(leading '0' from b.lct_caseno)";
  }
  if ($_REQUEST['ddl_ref_caseyr'] != '') {
    $ddl_ref_caseyr = " AND  lct_caseyear = '$_REQUEST[ddl_ref_caseyr]'";
    $ddl_ref_caseyr_t = " and a.lct_caseyear=b.lct_caseyear";
  }
}


 

$filters = [
  'ddl_court' => $ddl_court,
  'txt_order_date' => $txt_order_date,
  'ddl_bench' =>  $ddl_bench,
  'ddl_st_agncy' => $ddl_st_agncy,
  'ddl_ref_case_type' => $ddl_ref_case_type,
  'txt_ref_caseno' => $txt_ref_caseno,
  'ddl_ref_caseyr' => $ddl_ref_caseyr,
  'inc_val' => $inc_val, // Number of records per page
  'fst' => $fst // Offset for pagination
];

$result = $HighcourtModel->getCaseData($filters);
 
if (!empty($result)) {

  if ($_REQUEST['u_t'] == 0)
    $s_no = 1;
  else if ($_REQUEST['u_t'] == 1)
    $s_no = $_REQUEST['inc_tot_pg'];
?>

  <div class="table-responsive">
    <table id="customers" class="table table-striped custom-table">
      <thead>
        <tr>
          <th>
            S.No.
          </th>
          <th>
            Diary No.
          </th>
          <th>
            Petitioner<br />Vs<br />Respondents
          </th>
          <th>
            From Court
          </th>
          <th>
            State
          </th>
          <th>
            Bench
          </th>
          <th>
            Case No.
          </th>
          <th>
            Judgement Date
          </th>
        </tr>
      </thead>
      <tbody>
        <?php
        foreach ($result as $row) {
        ?>
          <tr>
            <td>
              <?php echo  $s_no; ?>
            </td>
            <td>
              <?php echo  substr($row['diary_no'], 0, -4) . '-' .  substr($row['diary_no'], -4); ?>
            </td>

            <td>
              <?php

              echo $row['pet_name'] . '<br/>Vs<br/>' . $row['res_name'];
              ?>
            </td>
            <td>
              <?php echo $row['court_name']; ?>
            </td>
            <td>
              <?php
              echo $row['name'];
              ?>
            </td>
            <td>
              <?php
              echo $row['agency_name'];
              ?>
            </td>
            <td>
              <?php
              echo  $row['type_sname'] . '-' . $row['lct_caseno'] . '-' . $row['lct_caseyear'];
              ?>
            </td>
            <td>
              <?php echo date('d-m-Y', strtotime($row['lct_dec_dt'])); ?>
            </td>

          </tr>
        <?php
          $s_no++;
        }
        ?>
      </tbody>
    </table>
    <input type="hidden" name="inc_tot_pg" id="inc_tot_pg" value="<?php echo $s_no; ?>" />
  <?php
} else {
  ?>
    <div class="cl_center"><b>No Record Found.</b></div>
  <?php
}
?>

 
<script>
    $(function() {
        var table = $("#customers").DataTable({
            "responsive": true,
            "searching": true,
            "lengthChange": false,
            "autoWidth": false,
            "pageLength": 20,
            "processing": true,
            "ordering": true,
            "paging": true
        });
    });
</script>