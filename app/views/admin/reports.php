<div class="panel panel-primary">
    <div class="panel-heading"><h4 style="margin:0">Attendance Report</h4></div>
    <div class="panel-body">
        <form method="GET" class="form-inline">
            <div class="form-group" style="margin-right:10px">
                <label>Class: </label>
                <select name="class_id" class="form-control" required>
                    <option value="">-- Select --</option>
                    <?php foreach ($classes as $c): ?>
                        <option value="<?= $c['id'] ?>" <?= ($selectedClass ?? '') == $c['id'] ? 'selected' : '' ?>><?= htmlspecialchars($c['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group" style="margin-right:10px">
                <label>From: </label>
                <input type="date" name="start_date" class="form-control" value="<?= htmlspecialchars($startDate ?? '') ?>" required>
            </div>
            <div class="form-group" style="margin-right:10px">
                <label>To: </label>
                <input type="date" name="end_date" class="form-control" value="<?= htmlspecialchars($endDate ?? '') ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Generate</button>
        </form>
    </div>
</div>

<?php if (!empty($report)): ?>
<div class="panel panel-default">
    <div class="panel-heading"><h4 style="margin:0">Report Results</h4></div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr><th>#</th><th>Name</th><th>Roll No.</th><th>Present</th><th>Absent</th><th>Late</th><th>Total Days</th><th>Attendance %</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($report as $i => $r): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= htmlspecialchars($r['name']) ?></td>
                        <td><?= htmlspecialchars($r['roll_number']) ?></td>
                        <td><span class="label label-success"><?= (int) $r['present_count'] ?></span></td>
                        <td><span class="label label-danger"><?= (int) $r['absent_count'] ?></span></td>
                        <td><span class="label label-warning"><?= (int) $r['late_count'] ?></span></td>
                        <td><?= (int) $r['total_days'] ?></td>
                        <td>
                            <?php
                                $total = (int) $r['total_days'];
                                $present = (int) $r['present_count'];
                                echo $total > 0 ? round(($present / $total) * 100, 1) . '%' : 'N/A';
                            ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php elseif (!empty($selectedClass)): ?>
    <div class="alert alert-info">No attendance data found for the selected criteria.</div>
<?php endif; ?>
