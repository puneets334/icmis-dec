<?php
$output = "";
if (!empty($results_notices)) {
    $output .= '<table width="100%" border="1" style="border-collapse: collapse;width:100%;" class="table table-striped custom-table table-hover dt-responsive" >
            <thead>
                <tr>
                    <th>S.No</th>
                    <th>Process Id</th>
                    <th>BarCode</th>
                    <th>Notice Type</th>
                    <th>Name</th>
                    <th>State / District</th>
                    <th>Station</th>
                    <th>Issue Date</th>
                    <th>Returnable Date</th>
                    <th>Dispatch Date</th>
                    <th>Serve Date</th>
                    <th>Receiving Date</th>
                    <th>Served/Unserved</th>
                    <th>View Notice</th>
                </tr>
            </thead>';
    $sno = 1;
    $get_dis_id = '';
    $tot_st = 0;

    foreach ($results_notices as $row) {
        //pr($row);
         $get_serve = get_serve_type($row['serve']);
         $get_serve_type = get_serve_type($row['ser_type']);
        $output .= '<tr>';
        $output .= '<td rowspan="">';
        $output .=  $sno;
        $output .= '</td>';
        $output .= '<td>';
        $output .= $row['process_id'] . '/' . $row['rec_dt'];
        $output .= '</td>';
        $output .= '<td>';
        $output .= $row['barcode'];
        if($row['barcode']!='')
            {
                $output.='<br> <span class="trackBarcode spantrack" data-id="'.$row['barcode'].'" data-toggle="modal" data-target="#exampleModal" 
                style="cursor: pointer;font-size: 85%;color:blue;" id ="barcode_'.$row['barcode'].'"> Click here to track </span> ';
            }
        $output .= '</td>';
        $output .= '<td>';
        $output .=  $row['nt_typ'];
        $output .= '</td>';
        $output .= '<td>
                        <div style="word-wrap:break-word;width: 90px">';
        if (!empty(trim($row['name'])) && $row['copy_type'] == 0) {
            $output .= $row['name'];
        }
        if (trim($row['name'], ' ') != '' && $row['tw_sn_to'] != 0 && $row['copy_type'] == 0) {
            $output .=  "<br/>Through ";
        }
        $send_to_name = '';
        if ($row['tw_sn_to'] != 0) {
            $send_to_name = send_to_name($row['send_to_type'], $row['tw_sn_to']);
        }

        $output .=  $send_to_name;

        $output .= '</div><div style="color: red">';

        if ($row['copy_type'] == 1) {
            $output .=  "Copy";
        }
        $output .= '</div>
                    </td>
                <td>';

        if ($row['tw_sn_to'] == 0) {
            $get_district = get_district($row['tal_state']);
            $get_state = get_state($row['tal_district']);
        } else {
            $get_district = get_district($row['sendto_district']);
            $get_state = get_state($row['sendto_state']);
        }
        $output .=  $get_district . '/<br/>' . $get_state;

        $output .= '</td>';
        $output .= '<td>';
        $get_tehsil = get_state($row['station']);
        $output .=  $get_tehsil;
        $output .= '</td>';
        $output .= '<td>';
        $output .= (!empty($row['rec_dt'])) ? date('d-m-Y',  strtotime($row['rec_dt'])) : '';
        $output .= '</td>';
        $output .= '<td>';
        $output .= (!empty($row['fixed_for'])) ? date('d-m-Y',  strtotime($row['fixed_for'])) : '';
        $output .= '</td>';
        $output .= '<td>';
        if ($row['dispatch_dt'] != '')
            $output .= date('d-m-Y',  strtotime($row['dispatch_dt']));
        $output .= '</td>';
        $output .= '<td>';
        if ($row['ser_date'] != '')
            $output .= date('d-m-Y',  strtotime($row['ser_date']));
        $output .= '</td>';
        $output .= '<td>';
        if ($row['ser_dt_ent_dt'] != '')
            $output .= date('d-m-Y',  strtotime($row['ser_dt_ent_dt']));
        $output .= '</td>';
        $output .= '<td>';

        // echo $get_serve.' / '.$get_serve_type;
        $output .= $get_serve . ' / ' . $get_serve_type;
        $output .= '</td>';



        $sno++;
        $get_dis_id = $row['dispatch_id'];



        $output .= '<td>';



        $fil_nm = "../pdf_notices/" . $row['notice_path'];
        //    $output.="<a href ='$fil_nm'>View</a>";
        $output .= "<a href='$fil_nm'  target='popup' onclick=window.open('$fil_nm','popup','width=600,height=400'); return false;'> view </a>";
        $output .= '</td>';
        $output .= '</tr>';
    }


    $output .= '</table>';
}

if ($output == "")
    $output = '<p align=center><font color=red><b>NOTICES NOT FOUND</b></font></p>';
echo  $output;
 
?>




<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="position: relative;border-bottom: 1px solid #e9ecef !important; ">
                <h4 class="modal-title" id="exampleModalLabel">Tracking Details: <span id="dataId"></span> </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="padding: 10px !important;">
                <div id="xml-content">
                    <table id="resultTable" width="100%" border="1" style="border-collapse: collapse">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Office</th>
                                <th>Event</th>
                            </tr>
                        </thead>
                        <tbody id="resultBody">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('.spantrack').click(function() {
        var dataId = $(this).data('id');
        $('#dataId').text(dataId);
        // $('#exampleModal').modal('show');
    });
});
</script>


<script>
$(document).ready(function(){ 
function xmlToHtml(xmlDoc) {
   // Get all dt_Details elements (can be more than one)
   const dtDetailsList = xmlDoc.getElementsByTagName('dt_Details');
    let htmlContent = ''; // Initialize empty string to accumulate rows

// Check if there are multiple dt_Details elements
if (dtDetailsList.length > 0) {
    for (let i = 0; i < dtDetailsList.length; i++) {
        const dtDetails = dtDetailsList[i];
        // Get and separate the date and time from EvntDate
        let eventDateTime = dtDetails.getElementsByTagName('EvntDate')[0]?.textContent || 'N/A';
        let eventDate = 'N/A';
        let eventTime = 'N/A';
        if (eventDateTime !== 'N/A') {
            const [date, time] = eventDateTime.split(' '); // Split date and time by space
            eventDate = date || 'N/A';
            eventTime = time || 'N/A';
        }
        const eventLoc = dtDetails.getElementsByTagName('EvntLctn')[0]?.textContent || 'N/A';
        const eventCode = dtDetails.getElementsByTagName('EvntCd')[0]?.textContent || 'N/A';
        // Debugging log for the extracted values
        console.log(eventDate, eventTime, eventLoc, eventCode);
        // Create table row with the separated date and time, location, and code
        htmlContent += `<tr>
                            <td>${eventDate}</td>
                            <td>${eventTime}</td>
                            <td>${eventLoc}</td>
                            <td>${eventCode}</td>
                        </tr>`;
    }
} else {
    // If no details are available, show a message
    htmlContent = `<tr><td colspan="4">No details available</td></tr>`;
}
return htmlContent;
}
  
    setTimeout(() => {
        $('.trackBarcode').on('click', function(e){
            // console.log("target:: ", e.target)
            var barcode = e.target.id
            barcode = barcode.split('barcode_');
            barcode = barcode[1];
            // alert(barcode);return false;CD619582587IN
            var requestapp = 'Cust0M$Tr@ck';
            //Dynamic
            var trackUrl = "http://data.cept.gov.in/CustomTracking/TrackConsignment.asmx/ArticleTracking?Article="+barcode+"&RequestingApplication="+requestapp
            //Static
            // var trackUrl = "http://data.cept.gov.in/CustomTracking/TrackConsignment.asmx/ArticleTracking?Article=JB070666762IN&RequestingApplication="+requestapp
            const apiURL = trackUrl;
            fetch(trackUrl)
            .then(response => response.text())
            .then(xmlText => {
                // Parsing the XML response
                const parser = new DOMParser();
                const xmlDoc = parser.parseFromString(xmlText, "text/xml");
                // Convert XML to HTML table rows
                const htmlContent = xmlToHtml(xmlDoc);            

                // Append the generated HTML into the table
                document.getElementById('resultBody').innerHTML = htmlContent;
            })
            .catch(err => {
                console.error("Error fetching the API:", err);
            });        
        })            

    }, 500);

})          
</script>