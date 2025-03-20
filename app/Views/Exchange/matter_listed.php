<?= view('header') ?>
<section class="content">
    <div class="container-fluid">
        <!-- /.card-header -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="container-fluid m-0 p-0">
                        <div class="row clearfix mr-1 ml-1 p-0">
                            <div class="col-12 m-0 p-0">
                                <p id="show_error"></p> <!-- This Segment Displays The Validation Rule -->
                                <div class="card">
                                    <div class="card-header bg-info text-white font-weight-bolder">Matters Received</div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="form-row col-12 px-2">
                                            
                                                <form method="post" action="" class="col-md-12">
                                                    <?= csrf_field() ?>
                                                    <div id="dv_content1">
                                                        <table class="table table-bordered">
                                                            <tr>
                                                                <td class="col-md-6">
                                                                    <label for="dno">Court No</label>
                                                                    <input type="text" id="courtNo" maxlength="6" class="form-control cus-form-ctrl" />
                                                                </td>
                                                                <td class="col-md-6">
                                                                    <label for="dyr">DATE</label>
                                                                    <input type="date" id="dyr" maxlength="4" class="form-control cus-form-ctrl" value="" />
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2" class="text-center">
                                                                    <input type="button" value="Details" id="showbutton" class="btn btn-primary" />
                                                                </td>
                                                            </tr>
                                                        </table>
                                                        <div id="result"></div>
                                                    </div>
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
    </div>
</section>