
<style>
    .inner-wrap {
        background-color: #dcdcdc;
    }
    .inner-wrap th {
        background-color: #dcdcdc;
        color: #000;
        font-weight: bold;
    }
    </style>
<div id="query_builder_wrapper">
    <?php if (!empty($category_result)) { ?>
        <table id="query_builder_report" class="inner-wrap table table-bordered table-striped">
            <thead>
                <tr>
                    <th>SNo. </th>
                    <th>Subject Category Description</th>
                    <th>Count</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sno = 1;
                foreach ($category_result as $row): ?>
                    <tr>
                        <td><?php echo $sno++; ?> </td>
                        <td><?php echo $row['Subject Category Description']; ?></td>
                        <td id="<?php echo $row['submasterid']; ?>" onclick="category_count_display(this.id)"
                         data-bs-toggle="modal" data-bs-target="#categoryDetailsModal">
                            <?php echo $row['count']; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="2" style="text-align:right">Total : </th>
                    <th id="total_count_footer"></th>
                </tr>
            </tfoot>
        </table>
    <?php } else { ?>
        <div class="text-center align-items-center"><i class="fas fa-info"> </i> No Record Found</div>
    <?php } ?>
</div>

<!-- Modal -->
<div class="modal fade" id="categoryDetailsModal" tabindex="-1" aria-labelledby="categoryDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" style="max-width:90vw;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="categoryDetailsModalLabel">Category Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="ggg">
            </div>
        </div>
    </div>
</div>
<script>
    $(function() {
        $("#query_builder_report").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["csv", "excel", {
                extend: 'pdfHtml5',
                orientation: 'landscape',
                pageSize: 'LEGAL'
            }],
            "bProcessing": true,
            "extend": 'colvis',
            "footerCallback": function(row, data, start, end, display) {
                var api = this.api();
                var intVal = function(i) {
                    return typeof i === 'string' ?
                        i.replace(/[\$,]/g, '') * 1 :
                        typeof i === 'number' ?
                        i : 0;
                };

                var total = api
                    .column(2)
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                // Update footer
                $(api.column(2).footer()).html(total);
            }
        }).buttons().container().appendTo('#query_builder_wrapper .col-md-6:eq(0)');
    });


    function category_count_display(id)
    {
        var category_id = id;
        var dateFrom = '<?php echo $from_date; ?>';
        var dateTo = '<?php echo $to_date; ?>';
    
        // Explicitly show the modal using Bootstrap JS
        $('#categoryDetailsModal').modal('show');
    
        $.ajax({
            url: '<?php echo base_url('Reports/filing/Report/getcategory_wisedetails'); ?>',
            type: "GET",
            beforeSend: function() {
            $("#ggg").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
            },
            data: {
                submaster_id: category_id,
                from: dateFrom,
                to: dateTo
            },
            success: function(data, status) {
                $('#ggg').html(data);
            },
            error: function() {
                alert('ERROR');
            }
        });
    }

</script>