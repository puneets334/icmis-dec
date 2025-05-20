<style>
    .inner-wrap {
        background-color: #f6f6f6;
    }
    .inner-wrap th {
        background-color: #f1f1f1;
        font-weight: bold !important;
        color: #000;
    }
    .inner-wrap td {
        color: #555;
    }
    </style>

<table class="table table-bordered table-striped table-hover table-sm" id="category_wise_data">
    <thead>
    <tr class="inner-wrap">
        <th>Sr.No.</th>
        <th>Diary No</th>
        <th>Filing Date</th>
        <th>Case No</th>
        <th>Cause Title</th>
        <th>Subject Category Description</th>
        <th>State</th>          
    </tr>
    </thead>
    <tbody>
        <?php $srno = 1; ?>
        <?php if (!empty($category_wise_data) && is_array($category_wise_data)): ?>
            <?php foreach ($category_wise_data as $row): ?>
                <tr bgcolor="white" class="inner-wrap">
                    <td><?= $srno++; ?></td>
                    <td><?= esc($row['diary_no']); ?></td>
                    <td><?= esc($row['filing_date']); ?></td>
                    <td><?= esc($row['case_no']); ?></td>
                    <td><?= esc($row['cause_title']); ?></td>
                    <td><?= esc($row['subject_category_description']); ?></td>
                    <td><?= esc($row['state']); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<script>
    $(document).ready(function() {
        $('#category_wise_data').DataTable({
            "responsive": true,
            "pageLength": 15,
            "lengthChange": false,
            "autoWidth": false,
            "order": [[0, "asc"]],
        });
    }); 
