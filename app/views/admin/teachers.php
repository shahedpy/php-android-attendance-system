<div class="panel panel-primary">
    <div class="panel-heading"><h4 style="margin:0">Add Teacher</h4></div>
    <div class="panel-body">
        <form method="POST" class="form-horizontal">
            <input type="hidden" name="action" value="add">
            <div class="form-group">
                <label class="col-sm-3 control-label">Name *</label>
                <div class="col-sm-9"><input type="text" name="name" class="form-control" required></div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">Username *</label>
                <div class="col-sm-9"><input type="text" name="username" class="form-control" required></div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">Email</label>
                <div class="col-sm-9"><input type="email" name="email" class="form-control"></div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">Phone</label>
                <div class="col-sm-9"><input type="text" name="phone" class="form-control"></div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">Assign Class</label>
                <div class="col-sm-9">
                    <select name="class_id" class="form-control">
                        <option value="">-- None --</option>
                        <?php foreach ($classes as $c): ?>
                            <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-9">
                    <button type="submit" class="btn btn-primary">Add Teacher</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading"><h4 style="margin:0">Teachers List</h4></div>
    <div class="panel-body">
        <?php if (empty($teachers)): ?>
            <p class="text-muted">No teachers added yet.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr><th>#</th><th>Name</th><th>Username</th><th>Email</th><th>Phone</th><th>Class</th><th>Action</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($teachers as $i => $t): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><?= htmlspecialchars($t['name']) ?></td>
                            <td><?= htmlspecialchars($t['username']) ?></td>
                            <td><?= htmlspecialchars($t['email'] ?? '') ?></td>
                            <td><?= htmlspecialchars($t['phone'] ?? '') ?></td>
                            <td><?= htmlspecialchars($t['class_name'] ?? 'N/A') ?></td>
                            <td>
                                <form method="POST" style="display:inline" onsubmit="return confirm('Delete this teacher?')">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?= $t['id'] ?>">
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
