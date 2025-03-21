<?= view('header') ?>

<style>

</style>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">

                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">BULK DISPOSAL- DATA MONITORING</h3>
                            </div>
                            <div class="col-sm-2">

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">



                                    <form action="#" id="frm" name="frm">
                                        <center>
                                            <h2>BULK DISPOSAL- DATA MONITORING</h2>
                                        </center>
                                        <div id="dv_content1">
                                            <div id="main">

                                                <table align="center" id="rcorners3">
                                                    <tr>
                                                        <td> <b> Select Hon'ble Judge: </b>

                                                            <select name="jcode" id="jcode" class="form-control select2" multiple>
                                                                <?php foreach ($judges as $judge): ?>
                                                                    <option value="<?= esc($judge['jcode']); ?>"><?= esc($judge['jname']); ?></option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <br>
                                                            <b> Hearing Date : </b>
                                                            <input type='date' id='hdate' class="form-control"><br>
                                                            <b> Dismissal Date: </b>
                                                            <input type='date' id='dismissal_date' class="form-control">

                                                            <br>

                                                            <b> (RJ date): </b>
                                                            <input type='date' id='rj_date' class="form-control">
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td><br> <b> Select Disposal Type: </b>

                                                            <select name="disp_type" id="disp_type" class="form-control">
                                                                <option value="">Select dismissal type</option>
                                                                <?php if (!empty($disposals)): ?>
                                                                    <?php foreach ($disposals as $disposal): ?>
                                                                        <option value="<?= esc($disposal['dispcode']); ?>"><?= esc($disposal['dispname']); ?></option>
                                                                    <?php endforeach; ?>
                                                                <?php endif; ?>
                                                            </select>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td><br> <b> Enter Diary no's. for bulk dispose in comma separated format (CSV FORMAT) </b> </td>
                                                    </tr>
                                                    <tr>
                                                        <td align="center"> <textarea id="cavno" name="cavno" class="form-control" placeholder="diary no's in csv format i.e. diaryno 25/2020 as 252020"></textarea>

                                                    </tr>

                                                    <tr>
                                                        <td align="center" colspan=3>
                                                            <div id='div_result'> </div>
                                                        </td>
                                                    </tr>



                                                    <tr>
                                                        <td align="center" colspan=3>
                                                            <input type="button" id="button" value="Dispose " onClick="check_and_dispose()" name="btn" <?php if (@$is_renewed > 0) { ?> disabled <?php } ?>>
                                                        </td>
                                                    </tr>

                                                </table>

                                                <br>

                                            </div> <!--END div main-->

                                            <b>
                                                <div id="txtHint" align="center" color="blue" style="font-size:180%"><b></b></div>

                                    </form>





                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">
    $('.select2').select2();
    function check_and_dispose() {

        //var jcode=document.getElementById('jcode').value;
        var jcode = $('#jcode').val();
        var dismissal_date = document.getElementById('dismissal_date').value;
        var dno = document.getElementById('cavno').value;
        var disp_type = document.getElementById('disp_type').value;
        var rj_date = document.getElementById('rj_date').value;
        var h_date = document.getElementById('hdate').value;        
        
        if (dismissal_date == '') {
            alert("dismissal date is blank.");
            $('#dismissal_date').focus();
            return;
        }
        if (h_date == '') {
            alert("Hearing  date is blank.");
            $('#hdate').focus();
            return;
        }

        if (rj_date == '') {
            alert("RJ date is blank.");
            $('#rj_date').focus();
            return;
        }

        if (jcode == '') {
            alert("Please Select Judge name");
            $('#jcode').focus();
            return;
        }
        if (disp_type == '') {
            alert("Please Select dismissal type");
            $('#disp_type').focus();
            return;
        }
        if (dno == '') {
            alert("Please Select Diary Number.");
            $('#cavno').focus();
            return;
        }

        var pattern = /^[0-9,]*$/g; // for comma separated numbers only
        var pattern = /^[0-9,]*$/g;
        let result1 = dno.match(pattern);

        /*if(!result1)
        {
        alert("only comma separated values are allowed");
        $('#dno').focus();
        return;
        }*/
        // remove the last comma if any from diary numbers 
        //alert(dno);
        str = dno.replace(/,\s*$/, "");


        var result = confirm("Are you sure to dispose the above mentioned diary numbers?");
        if (result) {
            $('#button').attr('disabled', true);
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    //   alert(this.responseText);
                    document.getElementById('txtHint').innerHTML = this.responseText;
                    $('#button').attr('disabled', false);

                }
            };
            var url = "<?php echo base_url('BulkDismissal/MonitoringTeam/bulk_dispose'); ?>?diaryno=" + str + '&dismissal_date=' + dismissal_date + '&jcode=' + jcode + '&disp_type=' + disp_type + '&rj_date=' + rj_date + '&h_date=' + h_date;
            xmlhttp.open("GET", url, true);
            xmlhttp.send();
        }
    }

    function removeLastComma(strng) {
        var n = strng.lastIndexOf(",");
        var a = strng.substring(0, n)
        return a;
    }

    function check(id) {
        $('#txtHint').html('');
        $('#div_result').html('');
        $('#button').attr('disabled', false);
        if (id == 1) {
            var cav_no = document.getElementById('cavno').value;
            var cav_yr = document.getElementById('cavyr').value;
            if (cav_no == '') {
                alert("Enter Diary no.");
                document.getElementById('cavno').focus();
                return;
            }
            if (cav_yr == '') {
                alert("Enter Diary year");
                document.getElementById('cavyr').focus();
                return;
            }

            $.ajax({
                url: '../caveat/get_diary_info.php',
                cache: false,
                async: true,
                data: {
                    cav_no: cav_no,
                    cav_yr: cav_yr
                },

                type: 'POST',
                success: function(data, status) {
                    if (data == 'Case not found!!') {
                        $('#button').attr('disabled', true);
                    } else {
                        $('#div_result').html(data);
                        if (document.getElementById('hd_renew').value > 0) {
                            $('#button').attr('disabled', true);
                        }
                    }

                },
                error: function(xhr) {
                    alert("Error: " + xhr.status + " " + xhr.statusText);
                }

            });
        }
    }

    function onlynumbers(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        //alert(charCode);
        if ((charCode >= 48 && charCode <= 57) || charCode == 9 || charCode == 8 || charCode == 37 || charCode == 39) {
            return true;
        }
        return false;
    }
</script>