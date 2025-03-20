<?= view('header') ?>
    <style>
        .custom-radio {
            float: left;
            display: inline-block;
            margin-left: 10px;
        }

        .custom_action_menu {
            float: left;
            display: inline-block;
            margin-left: 10px;
        }

        .basic_heading {
            text-align: center;
            color: #31B0D5
        }

        .btn-sm {
            padding: 0px 8px;
            font-size: 14px;
        }

        .card-header {
            padding: 5px;
        }

        h4 {
            line-height: 0px;
        }

        .row {
            margin-right: 15px;
            margin-left: 15px;
        }
        a {color:darkslategrey}      /* Unvisited link  */

        a:hover {color:black}    /* Mouse over link */
        a:active {color:#0000FF;}  /* Selected link   */

        .box.box-success {
            border-top-color: #00a65a;
        }
        .box {
            position: relative;
            border-radius: 3px;
            background: #ffffff;
            border-top: 3px solid #d2d6de;
            margin-bottom: 20px;
            width: 100%;
            box-shadow: 0 1px 1px rgba(0,0,0,0.1);
        }

        .box-header {
            color: #444;
            display: block;
            padding: 10px;
            position: relative;
        }
        .box-header.with-border {
            border-bottom: 1px solid #f4f4f4;
        }
        .box.box-danger {
            border-top-color: #dd4b39;
        }
       
</style>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Management Reports >> Pending >> Judge Wise Matter Listed Diaposal</h3>
                            </div>
                        </div>
                    </div>    <br><br> 
                    <div class="container-fluid">

                        <form id="dispatchQuery">
                            <?= csrf_field(); ?> 

                            <div class="row">

                                <div class="form-group col-sm-2">
                                <label for="caseType" class="text-right">From Date</label>
                                   <td><input  class="form-control" type="date" name="from_date" id="from_date" value="<?php echo date('Y-m-d'); ?>" required></td>
                                </div>

                                <div class="form-group col-sm-2">
                                    <label for="caseNo" class="text-right">To Date</label>
                                    <td><input  class="form-control" type="date" name="to_date" id="to_date" value="<?php echo date('Y-m-d'); ?>" required></td>
                                </div>

                                <div class="form-group col-sm-6">
                                    <label for="caseYear">Select Hon'ble Judge:</label>
                                    <td>
                                    <select class="form-control col-sm-4" id="jCode" name="jCode" placeholder="Judges">
                                        <option value="0">All</option>
                                        <option value="219">HON'BLE THE CHIEF JUSTICE</option>
                                        <option value="269">HON'BLE MR. JUSTICE SANJIV KHANNA</option>
                                        <option value="270">HON'BLE MR. JUSTICE B.R. GAVAI</option>
                                        <option value="271">HON'BLE MR. JUSTICE SURYA KANT</option>
                                        <option value="277">HON'BLE MR. JUSTICE HRISHIKESH ROY</option>
                                        <option value="278">HON'BLE MR. JUSTICE ABHAY S. OKA</option>
                                        <option value="279">HON'BLE MR. JUSTICE VIKRAM NATH</option>
                                        <option value="280">HON'BLE MR. JUSTICE J.K. MAHESHWARI</option>
                                        <option value="282">HON'BLE MRS. JUSTICE B.V. NAGARATHNA</option>
                                        <option value="283">HON'BLE MR. JUSTICE C.T. RAVIKUMAR</option>
                                        <option value="284">HON'BLE MR. JUSTICE M.M. SUNDRESH</option>
                                        <option value="285">HON'BLE MS. JUSTICE BELA M. TRIVEDI</option>
                                        <option value="286">HON'BLE MR. JUSTICE PAMIDIGHANTAM SRI NARASIMHA</option>
                                        <option value="287">HON'BLE MR. JUSTICE SUDHANSHU DHULIA</option>
                                        <option value="288">HON'BLE MR. JUSTICE J.B. PARDIWALA</option>
                                        <option value="289">HON'BLE MR. JUSTICE DIPANKAR DATTA</option>
                                        <option value="290">HON'BLE MR. JUSTICE PANKAJ MITHAL</option>
                                        <option value="291">HON'BLE MR. JUSTICE SANJAY KAROL</option>
                                        <option value="292">HON'BLE MR. JUSTICE SANJAY KUMAR</option>
                                        <option value="293">HON'BLE MR. JUSTICE AHSANUDDIN AMANULLAH</option>
                                        <option value="294">HON'BLE MR. JUSTICE MANOJ MISRA</option>
                                        <option value="295">HON'BLE MR. JUSTICE RAJESH BINDAL</option>
                                        <option value="296">HON'BLE MR. JUSTICE ARAVIND KUMAR</option>
                                        <option value="297">HON'BLE MR. JUSTICE PRASHANT KUMAR MISHRA</option>
                                        <option value="298">HON'BLE MR. JUSTICE K.V. VISWANATHAN</option>
                                        <option value="299">HON'BLE MR. JUSTICE UJJAL BHUYAN</option>
                                        <option value="300">HON'BLE MR. JUSTICE S.V.N. BHATTI</option>
                                        <option value="301">HON'BLE MR. JUSTICE SATISH CHANDRA SHARMA</option>
                                        <option value="302">HON'BLE MR. JUSTICE AUGUSTINE GEORGE MASIH</option>
                                        <option value="303">HON'BLE MR. JUSTICE SANDEEP MEHTA</option>
                                        <option value="304">HON'BLE  MR. JUSTICE PRASANNA B. VARALE</option>
                                        <option value="305">HON'BLE MR. JUSTICE NONGMEIKAPAM KOTISWAR SINGH</option>
                                     </select>
                                    </td>
                                </div>


                               
                                
                            </div>
                            <div class="row">
                                  <button type="button"  id="view" name="view" onclick="check(); " class="btn btn-block btn-primary">View</button>
                              </div>
    </br>
                           

                           
                        </form>
                        <div></div>
                        <div id="printable"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<script>
    $(function () {
        $('.datepick').datepicker({
            format: 'dd-mm-yyyy',
            todayHighlight: true,

            autoclose:true
        });
    });
  function updateCSRFToken() {
        $.getJSON("<?php echo base_url('Csrftoken'); ?>", function(result) {
            $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
        });
      }

    function check()
    {
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            var jCode = $('#jCode').val();
       
            $.ajax({
            type: "POST",
            data: $("#dispatchQuery").serialize(),  
            dataType: 'html', 
            url: "<?php echo base_url('/ManagementReports/Pending/pendency_reports_post'); ?>",
            success: function(data) {
                alert("Success!");
                // $('.card-title').hide();
                // $('.page-header').hide();
                $("#printable").html(data);  
                $("#dispatchDakFromRI").hide(); 
                updateCSRFToken();  
            },
            error: function() {
                alert('No Data Found');
                updateCSRFToken();  
            }
        });
    }
    function check_error(fromDate, toDate, param) {
        date1 = new Date(fromDate.split('-')[2], fromDate.split('-')[1] - 1, fromDate.split('-')[0]);
        date2 = new Date(toDate.split('-')[2], toDate.split('-')[1] - 1, toDate.split('-')[0]);

        if (fromDate!= toDate)
        {
            if (fromDate == "") {
                alert('Enter From Date');
                if (param == 1) {
                    $("#fromStoRI").focus();
                }
                else if (param == 2) {
                    $('#fromRItoS').focus();
                }
                else if (param == 3) {
                    $('#fromRItoR').focus();
                }
                return false;
            }

            else if (toDate == "") {
                alert('Enter To Date');
                if (param == 1) {
                    $("#toStoRI").focus();
                }
                else if (param == 2) {
                    $('#toRItoS').focus();
                }
                else if (param == 3) {
                    $('#toRItoR').focus();
                }
                return false;
            }
            else if (date1 > date2) {
                alert("To Date must be greater than From date");
                if (param == 1) {
                    $("#toStoRI").focus();
                }
                else if (param == 2) {
                    $('#toRItoS').focus();
                }
                else if (param == 3) {
                    $('#toRItoR').focus();
                }
                return false;
            }
            else {
                return true;
            }
        }
    }

</script>

