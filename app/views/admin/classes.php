<div class="panel panel-primary">
    <div class="panel-heading"><h4 style="margin:0">Add Class</h4></div>
    <div class="panel-body">
        <form method="POST" class="form-horizontal">
            <input type="hidden" name="action" value="add">
            <div class="form-group">
                <label class="col-sm-3 control-label">Class Name *</label>
                <div class="col-sm-9"><input type="text" name="name" class="form-control" required></div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-9">
                    <button type="submit" class="btn btn-primary">Add Class</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading"><h4 style="margin:0">Classes List</h4></div>
    <div class="panel-body">
        <?php if (empty($classes)): ?>
            <p class="text-muted">No classes added yet.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead><tr><th>#</th><th>Class Name</th><th>Created</th><th>Action</th></tr></thead>
                    <tbody>
                        <?php foreach ($classes as $i => $c): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><?= htmlspecialchars($c['name']) ?></td>
                            <td><?= htmlspecialchars($c['created_at'] ?? '') ?></td>
                            <td>
                                <form method="POST" style="display:inline" onsubmit="return confirm('Delete this class? Students in this class will also be removed.')">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?= $c['id'] ?>">
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
