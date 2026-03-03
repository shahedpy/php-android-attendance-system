<div class="panel panel-primary">
    <div class="panel-heading"><h4 style="margin:0">Add Student</h4></div>
    <div class="panel-body">
        <form method="POST" class="form-horizontal">
            <input type="hidden" name="action" value="add">
            <div class="form-group">
                <label class="col-sm-3 control-label">Name *</label>
                <div class="col-sm-9"><input type="text" name="name" class="form-control" required></div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">Roll Number *</label>
                <div class="col-sm-9"><input type="text" name="roll_number" class="form-control" required></div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">Class *</label>
                <div class="col-sm-9">
                    <select name="class_id" class="form-control" required>
                        <option value="">-- Select --</option>
                        <?php foreach ($classes as $c): ?>
                            <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">Parent Username</label>
                <div class="col-sm-9"><input type="text" name="parent_username" class="form-control" placeholder="Link to parent account"></div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-9">
                    <button type="submit" class="btn btn-primary">Add Student</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading"><h4 style="margin:0">Students List</h4></div>
    <div class="panel-body">
        <?php if (empty($students)): ?>
            <p class="text-muted">No students added yet.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr><th>#</th><th>Name</th><th>Roll No.</th><th>Class</th><th>Parent</th><th>Action</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($students as $i => $s): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><?= htmlspecialchars($s['name']) ?></td>
                            <td><?= htmlspecialchars($s['roll_number']) ?></td>
                            <td><?= htmlspecialchars($s['class_name'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($s['parent_username'] ?? 'N/A') ?></td>
                            <td>
                                <form method="POST" style="display:inline" onsubmit="return confirm('Delete this student?')">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?= $s['id'] ?>">
                                    <button type="submit" class="btn btn-danger btn-xs">Delete</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
