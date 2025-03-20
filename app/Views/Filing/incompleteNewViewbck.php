<?= view('header'); ?>

<section class="content">
  <div class="container-fluid">
    <form method="post" action="">
      <div>
        <table align="center" cellspacing="1" cellpadding="2" border="0" width="100%">
          <tr>
            <th>
              <?php if ($ref == 2): ?>
                <div align="center">
                  Search For
                  <select id="stype" onChange="f2()">
                    <option value="select_dno" selected>Diary No.</option>
                    <option value="all_dno">All Matters</option>
                  </select>
                  <span id="span_dno">
                    Diary No. <input type="text" id="dno" maxlength="6" size="5" autofocus />
                    &nbsp; Year <input type="text" id="dyr" maxlength="4" size="4" value="<?= date('Y'); ?>" />
                    &nbsp; <input type="button" value="SHOW" id="showbutton" onclick="f1()" />
                  </span>
                </div>
              <?php else: ?>
                <script>
                  window.location.replace("<?= site_url('incomplete'); ?>");
                </script>
              <?php endif; ?>
            </th>
          </tr>
          <tr>
            <th>
              <hr>
            </th>
          </tr>
        </table>
        <div id="newresult"></div>
      </div>
    </form>
  </div>
</section>


<script type="text/javascript">
  function f2() {
    selectElement = document.querySelector('#stype');
    output = selectElement.options[selectElement.selectedIndex].value;
    //document.querySelector('.output').textContent = output;
    if (output == 'all_dno') {
      document.getElementById("span_dno").style.display = "none";
      //  document.getElementById('newresult').innerText='';


      $.ajax({
          type: 'POST',
          url: "<?php echo base_url('Filing/IncompleteNew/incomplete'); ?>",
          beforeSend: function(xhr) {
            $("#newresult").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='../images/load.gif'></div>");
          }
        })
        .done(function(msg) {

          $("#newresult").html(msg);
        })
        .fail(function() {
          alert("ERROR, Please Contact Server Room");
        });
    } else {
      document.getElementById('newresult').innerText = '';
      var x = document.getElementById('span_dno');
      if (x.style.display === 'none') {
        // x.style.display = 'block';
        x.style.display = 'inline';
      } else {
        x.style.display = 'none';
      }

    }
  }

  function f1() {
    selectElement = document.querySelector('#stype');
    output = selectElement.options[selectElement.selectedIndex].value;
    if (output == 'select_dno') {
      var diaryno, diaryyear;
      var regNum = new RegExp('^[0-9]+$');
      diaryno = $("#dno").val();
      diaryyear = $("#dyr").val()
      if (!regNum.test(diaryno)) {
        alert("Please Enter Diary No in Numeric");
        $("#dno").focus();
        return false;
      }
      if (!regNum.test(diaryyear)) {
        alert("Please Enter Diary Year in Numeric");
        $("#dyr").focus();
        return false;
      }
      if (diaryno == 0) {
        alert("Diary No Can't be Zero");
        $("#dno").focus();
        return false;
      }
      if (diaryyear == 0) {
        alert("Diary Year Can't be Zero");
        $("#dyr").focus();
        return false;
      }
      $.ajax({
          type: 'POST',
          url: "<?php echo base_url('Filing/IncompleteNew/incompletenew'); ?>",
          beforeSend: function(xhr) {
            $("#newresult").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='../images/load.gif'></div>");
          },
          data: {
            dno: diaryno,
            dyr: diaryyear
          }
        })
        .done(function(msg) {

          $("#newresult").html(msg);
          //alert(diaryno);
          document.getElementById('dno').innerText = diaryno;
          document.getElementById('dyr').innerText = diaryyear;
        })
        .fail(function() {
          alert("ERROR, Please Contact Server Room");
        });
    }
  }
</script>