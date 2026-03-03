<div class="panel panel-primary">
    <div class="panel-heading"><h4 style="margin:0">Attendance Report</h4></div>
    <div class="panel-body">
        <form method="GET" class="form-inline">
            <div class="form-group" style="margin-right:10px">
                <label>Student: </label>
                <select name="student_id" class="form-control" required>
                    <option value="">-- Select --</option>
                    <?php foreach ($students as $s): ?>
                        <option value="<?= $s['id'] ?>" <?= ($selectedStudent ?? '') == $s['id'] ? 'selected' : '' ?>><?= htmlspecialchars($s['name']) ?></option>
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
            <button type="submit" class="btn btn-primary">View Report</button>
        </form>
    </div>
</div>

<?php if (!empty($selectedStudent)): ?>
<div class="row" style="margin-bottom:15px">
    <div class="col-sm-3">
        <div class="panel panel-success"><div class="panel-body text-center"><h3><?= $summary['present'] ?></h3><p>Present</p></div></div>
    </div>
    <div class="col-sm-3">
        <div class="panel panel-danger"><div class="panel-body text-center"><h3><?= $summary['absent'] ?></h3><p>Absent</p></div></div>
    </div>
    <div class="col-sm-3">
        <div class="panel panel-warning"><div class="panel-body text-center"><h3><?= $summary['late'] ?></h3><p>Late</p></div></div>
    </div>
    <div class="col-sm-3">
        <div class="panel panel-info">
            <div class="panel-body text-center">
                <h3><?= $summary['total'] > 0 ? round(($summary['present'] / $summary['total']) * 100, 1) . '%' : 'N/A' ?></h3>
                <p>Attendance</p>
            </div>
        </div>
    </div>
</div>

<?php if (!empty($records)): ?>
<div class="panel panel-default">
    <div class="panel-heading"><h4 style="margin:0">Daily Records</h4></div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead><tr><th>#</th><th>Date</th><th>Status</th></tr></thead>
                <tbody>
                    <?php foreach ($records as $i => $r): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= htmlspecialchars($r['date']) ?></td>
                        <td>
                            <?php
                                $labels = ['present' => 'success', 'absent' => 'danger', 'late' => 'warning'];
                                $lbl = $labels[$r['status']] ?? 'default';
                            ?>
                            <span class="label label-<?= $lbl ?>"><?= ucfirst(htmlspecialchars($r['status'])) ?></span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php else: ?>
    <div class="alert alert-info">No attendance data found for the selected period.</div>
<?php endif; ?>
<?php endif; ?>
