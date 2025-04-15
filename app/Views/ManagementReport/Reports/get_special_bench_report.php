<?php
if($part==1){

    if(count($result_arr)>0){
        $sno=1;
        ?>
<div>
<input name="prnnt1" type="button" id="prnnt1" value="Print" >
</div>
<div id="prnnt">
    <style>
            .top1{
                margin: 0 auto;
                text-align: center;
                overflow: hidden;
                border-bottom: 1px solid black;
                padding: 10px;
            }
            
            .inner_1{
                margin-left: auto;
                margin-right: auto;
                
            }
            
            select{
                
                height: 25px;
                width: 200px;
            }
            
            #result_main{
                margin-top: 20px;
            }
            
            .cl_manage{
                cursor: pointer;
            }
            
            #sar div table, #sar div table tr, #sar div table tr td, #sar div table tr th{
                font-size: 16px;
                border: none;
                padding: 5px;
            }
        
            
            .cl_chk_case{
                
                
                padding: 2px;
                margin: 5px;
                display: inline-block;
            }
            
            .sorry{
                text-align: center;
                font-size: 17px;
                font-weight: bold;
                color: red;
            }
            
            .add_result{
                text-align: center;
                font-size: 18px;
                margin: 5px 0px 5px 0px;
                display: none;
            }
            
            #dflag_utype, #mflag_utype{
                text-transform: uppercase;
            }
            
            #name_utype{
                text-transform: capitalize;
            }
            
            table td, table th{
                padding: 5px;
            }
            
            table {
                margin-left: auto;margin-right: auto;
                margin-bottom: 20px;
            }
            
            table, td,th,tr{
                border: 1px solid black;
                border-collapse: collapse;
            }
            
            #btnCan, #btnUp{
                display: none;
            }
            
            .sort:hover{
                cursor: pointer;
                color: blue
            }
        </style>
        SPECIAL BENCH MATTERS
    <table><tr><th>SNo.</th><th>Diary No</th><th>Case No.</th><th>Parties</th>
            <th style="width: 20%" class="sort">Judges <?php echo $sort_sign; ?>
            </th>
            <th style="width: 20%">Category</th>
        </tr>
            <?php
        foreach($result_arr as $row){
            $judges = $row['judges'] ?? ''; // Use null coalescing operator to handle potential null

            if (is_string($judges)) {
                $judges_val = str_replace(',', '', $judges);
            } else {
                $judges_val = ''; // Or handle the non-string case as needed (e.g., log an error)
            }

            ?>
        <tr><th><?php echo $sno; ?></th><td><?php echo substr($row['diary_no'],0,-4).'/'.substr($row['diary_no'],-4); ?></td>
            <td><?php echo $row['reg_no_display']; ?></td>
            <td><?php echo $row['pet_name'].'<b> V/S </b>'.$row['res_name']; ?></td>
            <td><?php echo $judges_val; ?></td>
            <td><?php echo $row['sub_name1']; ?></td>
        </tr>
                <?php
                $sno++;
        }
        ?>
    </table>
            <?php
    }
    else{
        ?>
    <div class="sorry">SORRY, NO RECORD FOUND!!!</div>
            <?php
    }
}
else if($part==2){
    
    if(count($result_arr)>0){
        $sno=1;
        ?>
    <table><tr><th>SNo.</th><th>Judges</th><th>Count</th></tr>
            <?php
        foreach($result_arr as $row){
            ?>
        <tr><th><?php echo $sno; ?></th>
            <td><?php echo $row['judges']; ?></td>
            <td><?php echo $row['num']; ?></td>
        </tr>
                <?php
                $sno++;
        }
        ?>
    </table>
    </div>

            <?php
    }
    else{
        ?>
    <div class="sorry">SORRY, NO RECORD FOUND!!!</div>
            <?php
    }
}
?>

<script>
//     $(document).on("click",".sort",function(){
//     var str = $(this).text();
//     var sort = "";
//     var order = "";
//     str = "sarthak"+str;
//     if(str.search("Judges"))
//         sort = 'J';
    
//     if(str.search("&#9650"))
//         order = 'D';
//     else if(str.search("&#9660"))
//         order = 'A';
      
//     //alert(sort+"<>"+order);
    
//     $.ajax({
//         type: 'POST',
//         url:"./get_special_bench_report.php",
//         beforeSend: function (xhr) {
//             $("#result_main").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='../images/load.gif'></div>");
//         },
//         data:{part:1,sort:sort,order:order}
//     })
//     .done(function(msg_new){
//         $("#result_main").html(msg_new);
//     })
//     .fail(function(){
//         alert("ERROR, Please Contact Server Room"); 
//     });
    
// });
    </script>

