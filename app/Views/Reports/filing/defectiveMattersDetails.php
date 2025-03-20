			<hr>
                <div id="printable">
                    <table class="table table-striped table-hover ">
                        <thead>
                        <?php
                        if($sel_section!='0')
                         $section_heading=" of section-".str_replace("'",'',$sel_section);
                        else
                            $section_heading="";
                        if(!empty($defects_result))
                        {?>
                        <tr><h3><?php echo "List of Defective Matters $section_heading in which Defects have been notified before $days days and are not Listed";?>  </h3></tr>
                        <tr>
                            <th>#</th>
                            <th>Diary<br/>Number</th>
                            <th>Cause Title</th>
                            <th>Filing<br/>Date</th>
                            <th>Defects <br/>Notified On</th>
                            <th>No. of <br/>Delay(in Days)</th>
                            <th>Alloted to</th>
                            <th>Tentative Section</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $i=0;
                        foreach ($defects_result as $result)
                        {$i++;
                            ?>
                            <tr>
                                <td><?php echo $i;?></td>
                                <td><?php echo $result['diary_no']."/".$result['diary_year'];?></td>
                                <td><?php echo $result['title'];?></td>
                                <td><?php echo date('d-m-Y',strtotime($result['diary_date']));?></td>
                                <td><?php echo date('d-m-Y',strtotime($result['save_dt']));?></td>
                                <td><?php echo $result['diff'];?></td>
                                <td><?php echo $result['name']."(".$result['empid'].")<br/>".$result['section_name'];?></td>
                                <td><?php echo $result['tentative_section'];?></td>
                            </tr>

                            <?php
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            <?php }
             else
            {              
                echo "<font size='18px'; color='red';>No case Found!</font>";
            } 
            ?>
         