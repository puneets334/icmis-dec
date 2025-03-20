<?= view('header'); ?>

<section class="content">
  <div class="container">
    <form method="post" action="">
      <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" id="csrf_token" />
      <?php if ($ref == 2): ?>
        <div class="row mt-5">
          <div class="col-md-6">
            Search For
            <select id="stype" onChange="f2()">
              <option value="select_dno" selected>Diary No.</option>
              <option value="all_dno"> All Matters</option>
            </select>
          </div>
        </div>
        <div class="row mt-2">
          <div class="col-md-3">
            <span id="span_dno">Diary No.
              <input type="text" id="dno" maxlength="6" size="5" autofocus placeholder="Enter Diary Number" /></span>
          </div>
          <div class="col-md-3">
            <span id="span_dyr">Year
              <input type="text" id="dyr" maxlength="4" size="4" value="<?php echo date('Y'); ?>" /></span>
          </div>
          <div class="col-md-12 mt-3">
            <input type="button" value="SHOW" id="showbutton" onclick="f1()" />
          </div>
        </div>
      <?php endif; ?>
      <div id="newresult"></div>
    </form>
  </div>
</section>
<script>
  function f1() {
    var dno = $("#dno").val();
    var dyr = $("#dyr").val();
    var regNum = new RegExp('^[0-9]+$');
    var csrfName = document.querySelector('input[name="<?= csrf_token() ?>"]').name;
    var csrfHash = document.querySelector('input[name="<?= csrf_token() ?>"]').value;
    var stype = 'specific_dno';

    if (!regNum.test(dno)) {
      alert("Please Enter Diary No in Numeric");
      $("#dno").focus();
      return false;
    }

    if (!regNum.test(dyr)) {
      alert("Please Enter Diary Year in Numeric");
      $("#dyr").focus();
      return false;
    }

    $.ajax({
        type: 'POST',
        url: "<?php echo base_url('Filing/IncompleteNew/incomplete'); ?>",
        data: {
          dno: dno,
          dyr: dyr,
          [csrfName]: csrfHash,
          stype: 'specific_dno' 
        },
        beforeSend: function() {
          $("#newresult").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url(); ?>/images/load.gif'></div>");
        }
      })
      .done(function(response) {
        $("#newresult").html(response.html);
      })
      .fail(function() {
        alert("ERROR, Please Contact Server Room");
      });
  }


  function f2() {
    var output = document.querySelector('#stype').value;

    if (output === 'all_dno') {
      // Hide input fields for diary number and year
      document.getElementById("span_dno").style.display = "none";
      document.getElementById("span_dyr").style.display = "none";

      // CSRF token for security
      var csrfName = document.querySelector('input[name="<?= csrf_token() ?>"]').name;
      var csrfHash = document.querySelector('input[name="<?= csrf_token() ?>"]').value;

      // AJAX call to render the new page
      $.ajax({
          type: 'POST',
          url: "<?php echo base_url('Filing/IncompleteNew/incomplete'); ?>",
          data: {
            [csrfName]: csrfHash,
            stype: output
          },
          beforeSend: function() {
            $("#newresult").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url(); ?>/images/load.gif'></div>");
          }
        })
        .done(function(response) {
          // Inject the rendered HTML into the page
          $("#newresult").html(response.html);
        })
        .fail(function() {
          alert("ERROR, Please Contact Server Room");
        });
    } else {
      // Show the diary number and year fields
      document.getElementById('newresult').innerText = '';
      document.getElementById('span_dno').style.display = 'inline';
    }
  }
</script>