<?= view('header') ?>

<style>
    .login-box {
        margin: auto;
    }
</style>
<style>
    html, body { height: 100%; }
textarea {background-color: #fff; border:1px solid;}
select {background-color: #fff; border:1px solid;}
select option{}
/*input {background-color: #fff; font-size:14px; border:1px solid;}*/
body { position: relative;  font-family:Calibri, Arial, Helvetica, sans-serif; }
.lblclass{}
/*#s_box { width: 100%; background-color: #ADAEC0; border-top: 1px solid #fff; position: fixed; top: 71px; left: 0; right: 0; z-index: 0; }*/
#messagepost { background-color: #ADAEC0; border-top: 1px solid #fff; position: fixed; top: 72px; right: 5; z-index: 0; }
/*#r_box { background-color:#F6DFDF; position: relative; overflow:scroll; top:55px; height:100%; }*/
#newb { position: fixed; padding: 12px; left: 50%; top: 50%; display: none; color: black; background-color: #D7D7F4; border: 2px solid lightslategrey; }
#newc { position: fixed; padding: 12px; left: 50%; top: 50%; display: none; color: black; background-color: #D7D7F4; border: 2px solid lightslategrey; }
#newp { position: fixed; padding: 12px; left: 50%; top: 50%; display: none; color: black; background-color: #D7D7F4; border: 2px solid lightslategrey; }
#newadv { position: fixed; padding: 12px; left: 50%; top: 50%; display: none; color: black; background-color: #D7D7F4; border: 2px solid lightslategrey; }
#mrq { color: black; text-shadow: grey 0.1em 0.1em 0.2em; font-size:13px; }
#jodesg { font-size: 10pt; font-family:Calibri, Arial, Helvetica, sans-serif; font-weight:bold; }
#joname { font-size: 10pt; font-family:Calibri, Arial, Helvetica, sans-serif; font-weight:bold; }
table.mytable3 { width: 100%; }
table.mytable { width: 100%;  -moz-box-shadow: 2px 2px 2px #ccc;  -webkit-box-shadow: 2px 2px 2px #ccc;  box-shadow: 2px 2px 2px #ccc;}
table.mytable3 td { font-size: 12px; font-family:Calibri, Arial, Helvetica, sans-serif; border: none; vertical-align: top; padding: 0px;  }
table.mytable td { font-size: 10pt; font-family:Calibri, Arial, Helvetica, sans-serif; border: none; background-color: #F4F4F4; vertical-align: top; padding: 0px;  }
table.mytable th { font-size: 10pt; font-family:Calibri, Arial, Helvetica, sans-serif; border: none; background-color: #F4F4F4; vertical-align: top; padding: 0px;  }
hr { color: #666666; background-color:#999999; height: 1px; width:95%; }
.tbl_hr { border:1px solid darkgrey;}
.tbl_hr td{ border-bottom:1px solid lightgrey; border-right:1px solid lightgrey;}
#rightcontainer{overflow:auto; background-color:#fff;}
#overlay {
    background-color: #000;
    opacity: 0.7;
    filter:alpha(opacity=70);
    position: fixed;
    top: 0px;
    left: 0px;
    width: 100%;
    height: 100%;
}
table tr:nth-of-type(2n+1) {
    background: none repeat scroll 0% 0% #EEE;
}
table td, table th {
/*    padding: 2px;*/
    border: 1px solid #CCC;
    text-align: left;
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
                                <h3 class="card-title">DIRECT DISPOSAL OF CASE</h3>
                            </div>
                            <div class="col-sm-2">

                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <span class="alert-danger"><?= \Config\Services::validation()->listErrors() ?></span>

                                    <?php if (session()->getFlashdata('error')) { ?>
                                        <div class="alert alert-danger text-white ">
                                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                            <?= session()->getFlashdata('error') ?>
                                        </div>
                                    <?php } else if (session("message_error")) { ?>
                                        <div class="alert alert-danger">
                                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                            <?= session()->getFlashdata("message_error") ?>
                                        </div>
                                    <?php } else { ?>
                                        <br />
                                    <?php } ?>

                                    <?php
                                    $attribute = array('class' => 'form-horizontal', 'name' => 'component_search', 'id' => 'component_search', 'autocomplete' => 'off');
                                    echo form_open(base_url('#'), $attribute);
                                    ?>
                                    <?php echo component_html(); ?>

                                    <center> <input type="button" class="btn btn-primary" name="btnGetR" value="Submit"></center>
                                    <?php form_close(); ?>

                                    <div id="dv_res1">
                                    </div>
                                    <div id="overlay" style="display:none;">&nbsp;</div>

                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
<script src="<?= base_url() ?>/Ajaxcalls/menu_assign/set_dispose.js"></script>