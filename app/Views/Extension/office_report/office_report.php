<?= view('header') ?>

<style type="text/css">
    .al_left {
        text-align: left;
    }

    .cl_add_cst,
    .sp_aex {
        color: blue;
    }

    .cl_add_cst:hover,
    .sp_aex:hover {
        cursor: pointer;
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
                                <h3 class="card-title">Office Report >> Diary Search</h3>
                            </div>
                            <div class="col-sm-2">
                                <div class="custom_action_menu">
                                    <a href="<?= base_url('Extension/OfficeReport'); ?>"><button class="btn btn-primary btn-sm" type="button"><i class="fas fa-pen	" aria-hidden="true"></i></button></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">

                            <form>
                                <?= csrf_field() ?>
                                <div id="dv_content1">
                                    <div style="text-align: center">
                                        <table align="center">
                                            <tr>
                                                <td><b>Nature</b>
                                                    <select name="ddl_nature" id="ddl_nature" class="form-control">
                                                        <option value="">Select</option>
                                                        <?php foreach ($natures as $row): ?>
                                                            <option value="<?= $row['nature'] ?>">
                                                                <?= ($row['nature'] == 'R') ? 'Criminal' : (($row['nature'] == 'C') ? 'Civil' : '') ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </td>

                                                <td><b>Report Type</b> 
                                                    <select name="ddl_rt" id="ddl_rt" class="form-control">
                                                        <option value="">Select</option>
                                                        <!-- Populate options dynamically if needed -->
                                                    </select>
                                                </td>

                                                <td><b>Diary No.</b>
                                                    <input type="text" id="t_h_cno" name="t_h_cno" size="5" class="form-control" />
                                                </td>

                                                <td><b>Diary Year</b>
                                                    <select id="t_h_cyt" name="t_h_cyt" class="form-control">
                                                        <option value="">Select</option>
                                                        <?php
                                                        $currently_selected = date('Y');
                                                        $earliest_year = 1950;
                                                        $latest_year = date('Y');
                                                        foreach (range($latest_year, $earliest_year) as $i) {
                                                            echo "<option value='{$i}'" . ($i == $currently_selected ? ' selected="selected"' : '') . ">{$i}</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </td>

                                                <td style="vertical-align: bottom;">
                                                    <input type="button" name="sub" class="btn btn-primary" value="SUBMIT" id="sub" />
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div id="div_result"></div>
                                    <div id="tb_docdetails1"></div>
                                    <div id="chk_status"></div>
                                </div>
                            </form>


                        </div>



                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</section>
<script type="text/javascript" src="<?= base_url('filing/office_report.js') ?>?rendom=1dfd"></script>