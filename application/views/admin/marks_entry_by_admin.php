<div class="container">
    <div class="row">
        <!-- Left Table -->
        <div class="col-md-6">
            <div class="text-center">
                <h6 class="font-weight-bolder">Main Exam Status</h6>	
            </div>
            <table class="table text-uppercase table-striped dt-responsive">
                <thead>
                    <tr>
                        <th>Sr. No.</th>
                        <th>User</th>
                        <th>Count</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $sno = 1;
                    foreach ($marks_entry_count as $status) { ?>
                        <tr>
                            <td><?php echo $sno++; ?></td>
                            <td><?php echo $status->name; ?></td>
                            <td><?php echo $status->total; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <!-- Right Table -->
        <div class="col-md-6">
            <div class="text-center">
                <h6 class="font-weight-bolder">Backlog Exam Status</h6>	
            </div>
            <table class="table text-uppercase table-striped dt-responsive">
                <thead>
                    <tr>
                        <th>Sr. No.</th>
                        <th>User</th>
                        <th>Count</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $sno = 1;
                    foreach ($backlog_marks_entry_count as $status) { ?>
                        <tr>
                            <td><?php echo $sno++; ?></td>
                            <td><?php echo $status->name; ?></td>
                            <td><?php echo $status->total; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
