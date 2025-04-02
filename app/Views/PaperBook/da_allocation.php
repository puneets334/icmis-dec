<?= view('header') ?>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title"> GODOWN USER ALLOCATION REPORT </h3>
                            </div>
                        </div>
                    </div>

                        <!-- Main content start -->
                        <div class="col-md-12">
                            <div class="card-body">
                                <form method="post" action="<?= site_url('PaperBook/PaperBookController/allocationReport') ?>" name="alocatefrm" id="alocatefrm">
                                    <?= csrf_field() ?>                            
                                    <div id="dv_content1"   >                                     
                                        <TABLE align= center width=50% >
                                        <tr></tr>
                                        <tr><INPUT TYPE="submit" name='show'  id = 'show' value = "SHOW REPORT"> <td></TR>
                                        <hr>
                                        </TABLE>
                                    </div>
                                </form>
                            </div>
                            <div id="loader"></div>
                        </div>

                </div>

            </div>
        </div>
    </div>
</section>


<script>
        document.getElementById("alocatefrm").addEventListener("submit", function() {
            //document.getElementById("loader").style.display = "block";  
            $('#loader').html('<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>');
        });
    </script>