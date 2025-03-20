<?= view('header') ?>
<style>
    #dropdown_container {
        max-height: 200px;
        /* overflow-y: auto; */
        position: absolute;
        background-color: white;
        border: 1px solid #ddd;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        width: 100%;
        z-index: 1000;
    }
    .dropdown-menu{
        max-height: calc(100vh - 9rem);
        overflow-y: auto;
    }
    .ui-menu-item {
        padding: 10px;
        cursor: pointer;
    }

    .ui-menu-item:hover {
        background-color: #e3e3e3;
    }
</style>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header heading">
                        <h3 class="card-title">Advocate Search by Name</h3>
                    </div>
                    <div class="card-body">
                        <form id="advocate_Search" method="post">
                            <?= csrf_field() ?>
                            <input type="hidden" id="fil_hd" />
                            <div class="row">
                                <div class="col-md-6 mx-auto">
                                    <div class="form-group">
                                        <label for="advocate_name_search" class="col-md-4 col-form-label">Type Name</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa fa-user"></i></span>
                                            <input type="text" class="form-control" id="advocate_name_search" placeholder="Type Advocate Name" require />
                                        </div>
                                        <div><small>Please type at least 3 characters</small></div>
                                        <div id="dropdown_container" class="position-relative btn-group dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="display: none;"></div>
                                    </div>

                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer">
                        <div class="table-responsive">
                            <table cellspacing="5" cellpadding="5">
                                <tbody>
                                    <tr>
                                        <td>Name:</td>
                                        <td><span id="adv_name"></span></td>
                                    </tr>
                                    <tr>
                                        <td>AOR/NAOR:</td>
                                        <td><span id="adv_aor"></span></td>
                                    </tr>
                                    <tr>
                                        <td>AOR Code:</td>
                                        <td><span id="adv_aor_code"></span></td>
                                    </tr>
                                    <tr>
                                        <td>Mobile:</td>
                                        <td><span id="adv_mobile"></span></td>
                                    </tr>
                                    <tr>
                                        <td>Email:</td>
                                        <td><span id="adv_email"></span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
</section>
<div id="res_loader"></div>
<?= view('sci_main_footer') ?>
<script>
    $(document).on("focus", "#advocate_name_search", function() {
        $("#advocate_name_search").autocomplete({
            source: "<?= base_url('Judicial/Advocate/AdvocateController/requestNameSearch') ?>",
            width: 450,
            matchContains: true,
            minChars: 3,
            selectFirst: false,
            select: function(event, ui) {
                // Set autocomplete element to display the label
                this.value = ui.item.label;
                // Store value in hidden field
                var data = ui.item.value;
                data = data.split('~');
                $("#adv_mobile").html(data[0]);
                $("#adv_email").html(data[1]);
                $("#adv_aor_code").html(data[2]);
                $("#adv_aor").html(data[3]);
                $("#adv_name").html(ui.item.label);
                // Prevent default behaviour
                return false;
            },
            focus: function( event, ui){
                $("#advocate_name_search").val(ui.item.label);
                return false;  
            }
        });
    });
</script>