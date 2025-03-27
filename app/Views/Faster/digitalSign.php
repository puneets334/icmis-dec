<section class="content " >
    <div class="row">
        <div class="col-sm-3"><h3 class="head text-left" style="padding: 0 !important; margin: 0 !important;">Digital Signature</h3></div>
        <div class="col-sm-9 form-grup">
            <label for="dated" class="col-sm-2 control-label text-primary">Dated: <?=convertTodmY($_SESSION['nextDate'])?></label>
            <label for="diaryNumber" class="col-sm-4 control-label text-primary"><?=$_SESSION['caseNumber']?></label>
            <label for="causeTitle" class="col-sm-6 control-label text-primary"><?=$_SESSION['causetitle']?></label>
        </div>
        <?php if(isset($_SESSION['warning_message']) && !empty($_SESSION['warning_message'])){ ?>
            <div class="col-sm-8">
                <div class="alert alert-warning" role="alert">
                    <?=$_SESSION['warning_message']?>
                </div>
            </div>
            <?php
            $_SESSION['warning_message'] = "";
            //unset($_SESSION['warning_message']);
        } ?>
    </div>
    <hr>

    <!-------------Result Section ------------>


        <style>
            .btn {
                border: 2px solid black;
                background-color: white;
                color: black;
                padding: 3px 8px;
                font-size: 14px;
                cursor: pointer;
            }
            /* Green */
            .success {
                border-color: #04AA6D;
                color: green;
            }

            .success:hover {
                background-color: #04AA6D;
                color: white;
            }
            /* Blue */
            .info {
                border-color: #2196F3;
                color: dodgerblue;
            }

            .info:hover {
                background: #2196F3;
                color: white;
            }
        </style>
        <div class="col-sm-6">

            <div class="col-sm-12">
                <h4>Attached Document(s)</h4>
                <table id="tblDigiSign" class="table table-striped">
                    <thead>
                    <tr>
                        <th>Document Name (Dated)</th>
                        <th>Attached On</th>
                        <th>Action</th>
                    </tr></thead><tbody>

                    </tbody>
                </table>

            </div>
        </div>
        <div class="rightDiv form-group col-sm-6">
            <div class="form-group col-sm-12" id="actionDigiSign" >

            </div>
            <div classs="form-group col-sm-12" id="divShowPdf">

            </div>

        </div>

</section>
<script type="text/javascript">
    /*$(window).on('load', function() {
        showDocumentsList();
    });*/
    /*$(document).load(function () {
        showDocumentsList();
    });*/

    setTimeout(function(){ showDocumentsList(); }, 100);
</script>
