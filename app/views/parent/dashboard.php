<div class="panel panel-default">
    <div class="panel-heading"><h4 style="margin:0">Your Children</h4></div>
    <div class="panel-body">
        <?php if (empty($students)): ?>
            <p class="text-muted">No students linked to your account yet. Please contact the school administrator.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead><tr><th>#</th><th>Name</th><th>Roll No.</th><th>Class</th><th>Report</th></tr></thead>
                    <tbody>
                        <?php foreach ($students as $i => $s): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><?= htmlspecialchars($s['name']) ?></td>
                            <td><?= htmlspecialchars($s['roll_number']) ?></td>
                            <td><?= htmlspecialchars($s['class_name'] ?? 'N/A') ?></td>
                            <td><a href="report.php?student_id=<?= $s['id'] ?>" class="btn btn-info btn-xs">View Report</a></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
