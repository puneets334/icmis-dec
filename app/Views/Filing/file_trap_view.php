<?=view('header'); ?>
 
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header heading">

                            <div class="row">
                                <div class="col-sm-10">
                                    <h3 class="card-title">Filing</h3>
                                </div>
                                 <?=view('Filing/filing_filter_buttons'); ?>
                            </div>
                        </div>
                        <?//=view('Filing/filing_breadcrumb'); ?>
                        <!-- /.card-header -->

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header p-2" style="background-color: #fff;">
                                        <h4 class="basic_heading"> File Trap Details </h4>
                                    </div><!-- /.card-header -->
                                    <div class="card-body">
                                        <div class="tab-content">



                                            <form method="post" action="">

                                                <table align="center" cellspacing="1" cellpadding="2" border="0" width="100%">
                                                    <tr>
                                                        <?php
                                                        $cur_date=date('d-m-Y');
                                                        $new_date=date('d-m-Y', strtotime($cur_date. ' + 60 days'));
                                                        $cat=0;$ref=0;
                                                        $condition="and remarks=''";
                                                        if(!empty($fil_trap_type)) {
                                                            if ($fil_trap_type['usertype'] == 108) {
                                                                $ref = 2;
                                                            }
                                                            else{
                                                                echo "file included";
                                                                ?><script>//alert('incomplete list');
                                                                    window.location.replace("<?=base_url('Filing/File_trap/incomplete')?>");</script><?php
                                                            }
                                                        }
                                                        ?>
                                                        </th></tr>
                                                    <tr><th><hr></th></tr>
                                                </table>


                                                <div id="dv_content1" >

                                                    <table align="center" cellspacing="1" cellpadding="2" border="0" width="100%">
                                                        <tr><th>Incomplete Matters for <span style="color: #d73d5a"></span>

                                                                <span style="color: #737add">[Data Entry]</span>
                                                                <div id='txtHint'></div>
                                                            </th></tr>
                                                        <tr><th><hr></th></tr>
                                                    </table>
                                                    <div id="result">

                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <table id="example1" class="table table-bordered table-striped" >
                                                                    <thead>
                                                                    <tr>
                                                                        <th>SNo.</th>
                                                                        <th>Diary No.</th>
                                                                        <th>Parties</th>
                                                                        <th>Dispatch By</th>
                                                                        <th>Dispatch On</th>
                                                                        <th>Remarks</th>
                                                                        <th>Receive</th>
                                                                        <th>Dispatch</th>
                                                                        <th>eFiling View</th>
                                                                    </tr>


                                                                    </thead>
                                                                    <tbody>
                                                                    <tr style=""><th>1</th><td>41499/2023</td>
                                                                        <td>IMRAN HASAN PATHAN <b>V/S</b> MACHINDRA M SHINDE</td> <td>ANJALI</td>
                                                                        <td>06-10-2023 05:04:03 PM</td>

                                                                        <td>FIL -> DE</td>
                                                                        <td> <div id='d'> <input type="button" id="rece507440" value="Receive"/>
                                                                        </td>
                                                                        <td>
                                                                            <input type="button" id="comp507440" value="Dispatch" />
                                                                        </td>
                                                                        <td>
                                                                            <button class="btn ui-button-text-icon-primary " style="background-color: #555555;color: #fff;cursor:pointer;font-size: large;" onclick="efiling_number('ECSCIN01086552023')">View</button>
                                                                        </td>
                                                                    </tr>
                                                                    <tr style=""><th>2</th><td>41408/2023</td>
                                                                        <td>KARNATAKA VIKAS GRAMEENA BANK <b>V/S</b> THE APPELLATE AUTHORITY AND THE DEPUTY CHIEF LABOUR AND COMMISSIONER (C ) SHRAMA SADHANA</td> <td>PRABHAT KUMAR SHARMA</td>
                                                                        <td>06-10-2023 03:35:49 PM</td>

                                                                        <td>FIL -> DE</td>
                                                                        <td> <div id='d'> <input type="button" id="rece507405" value="Receive"/>
                                                                        </td>
                                                                        <td>
                                                                            <input type="button" id="comp507405" value="Dispatch" />
                                                                        </td>
                                                                        <td>
                                                                            <button class="btn ui-button-text-icon-primary " style="background-color: #555555;color: #fff;cursor:pointer;font-size: large;" onclick="efiling_number('ECSCIN01086192023')">View</button>
                                                                        </td>
                                                                    </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>


                                                    </div>

                                                </div>

                                                <div id="newresult">  </div>


                                            </form>




                                        </div>
                                        <!-- /.tab-content -->
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                                <!-- /.card -->
                            </div>
                        </div>


                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
    </section>
    <!-- /.content -->

    <script>
        $(function () {
            $("#example1").DataTable({
                "responsive": true, "lengthChange": false, "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
            $('#example2').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });
        });
    </script>


    <script type="text/javascript">


        function f2() {
            selectElement = document.querySelector('#stype');
            output = selectElement.options[selectElement.selectedIndex].value;
            //document.querySelector('.output').textContent = output;
            if(output=='all_dno') {
                document.getElementById("span_dno").style.display = "none";
                //  document.getElementById('newresult').innerText='';


                $.ajax({
                    type: 'POST',
                    url: "./incomplete.php",
                    beforeSend: function (xhr) {
                        $("#newresult").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='../images/load.gif'></div>");
                    }
                })
                    .done(function (msg) {

                        $("#newresult").html(msg);
                    })
                    .fail(function () {
                        alert("ERROR, Please Contact Server Room");
                    });
            }
            else{
                document.getElementById('newresult').innerText='';
                var x = document.getElementById('span_dno');
                if (x.style.display === 'none') {
                    // x.style.display = 'block';
                    x.style.display='inline';
                } else {
                    x.style.display = 'none';
                }

            }
        }

        function f1() {

            selectElement = document.querySelector('#stype');
            output = selectElement.options[selectElement.selectedIndex].value;
            if(output=='select_dno'){
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
                    url: "./incomplete.php",
                    beforeSend: function (xhr) {
                        $("#newresult").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='../images/load.gif'></div>");
                    },
                    data: {dno: diaryno, dyr: diaryyear}
                })
                    .done(function (msg) {

                        $("#newresult").html(msg);
                        //alert(diaryno);
                        document.getElementById('dno').innerText=diaryno;
                        document.getElementById('dyr').innerText=diaryyear;
                    })
                    .fail(function () {
                        alert("ERROR, Please Contact Server Room");
                    });
            }


        }

    </script>


 <?=view('sci_main_footer');?>