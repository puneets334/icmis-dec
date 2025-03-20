<?php
session_start();
include("../../menu_assign/config.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Appearance List</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="../../offline_copying/js/jquery-1.9.1.js"></script>
    <link rel="stylesheet" href="../../offline_copying/css/bootstrap.min.css" >
    <script src="../../offline_copying/js/bootstrap.min.js"></script>
    <link href="../../plugins/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet" />
    <script src="../../plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
    <style>
        .modal {
            padding: 0 !important; // override inline padding-right added from js
        }
        .modal .modal-dialog {
            width: 100%;
            max-width: none;
            height: 100%;
            margin: 0;
        }
        .modal .modal-content {
            height: 100%;
            border: 0;
            border-radius: 0;
        }
        .modal .modal-body {
            overflow-y: auto;
        }

    </style>
</head>
<body>
<div class="bg-light">
    <div class="container-fluid m-0 p-0">
        <div class="row clearfix mr-1 ml-1 p-0">
            <div class="col-12 m-0 p-0">

                <p id="show_error"></p> <!-- This Segment Displays The Validation Rule -->
                <div class="card">
                    <div class="card-header bg-info text-white font-weight-bolder">Appearance List
                    </div>

                    <div class="card-body">

                        <div class="row">
                            <div class="form-row col-12">


                                <div class="input-group col-3 mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="list_date_addon">List Date</span>
                                        </div>
                                        <input type="text" class="form-control bg-white list_date"
                                               aria-describedby="list_date_addon" placeholder="Date..." readonly>
                                    </div>

                                <div class="input-group col-3 mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="courtno_addon">Court No.<span style="color:red;">*</span></span>
                                    </div>
                                    <select class="form-control courtno" aria-describedby="courtno_addon">
                                        <option value="0">-Select-</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                        <option value="8">8</option>
                                        <option value="9">9</option>
                                        <option value="10">10</option>
                                        <option value="11">11</option>
                                        <option value="12">12</option>
                                        <option value="13">13</option>
                                        <option value="14">14</option>
                                        <option value="15">15</option>
                                        <option value="16">16</option>
                                        <option value="17">17</option>
                                        <option value="21">21 (Registrar)</option>
                                        <option value="22">22 (Registrar)</option>
                                    </select>
                                </div>

                                <div class="col-2 pl-4 mb-3">
                                    <input id="btn_search" name="btn_search" type="button" class="btn btn-success btn-block"
                                           value="Search">
                                </div>

                             </div>
                        </div>




                </div>


            </div>


        </div>

        <div class="row col-md-12 m-0 p-0" id="result"></div>



        </div>
</body>
</html>
<script>

    $(function () {
        $('.list_date').datepicker({
            autoclose: true,
            format: 'dd-mm-yyyy'
        });
    });

    $("#btn_search").click(function(){
        $("#result").html(""); $('#show_error').html("");
        var list_date = $(".list_date").val();
        var courtno = $(".courtno").val();

        if (list_date.length == 0) {
            $('#show_error').append('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Please select cause list date</strong></div>');
            return false;
        }
        else if (courtno == 0) {
            $('#show_error').append('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Please select court number</strong></div>');
            return false;
        }
        else{
            $.ajax({
                url:'list_process.php',
                cache: false,
                async: true,
                data: {list_date:list_date,courtno:courtno},
                beforeSend:function(){
                    $('#result').html('<table widht="100%" align="center"><tr><td><img src="../../images/load.gif"/></td></tr></table>');
                },
                type: 'POST',
                success: function(data, status) {
                    $("#result").html(data);
                },
                error: function(xhr) {
                    alert("Error: " + xhr.status + " " + xhr.statusText);
                }
            });

        }
    });




</script>