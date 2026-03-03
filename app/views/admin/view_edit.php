<ul class="nav nav-tabs" style="margin-bottom:15px">
    <li class="<?= ($currentType ?? '') === 'teachers' ? 'active' : '' ?>"><a href="view_edit.php?type=teachers">Teachers</a></li>
    <li class="<?= ($currentType ?? '') === 'classes' ? 'active' : '' ?>"><a href="view_edit.php?type=classes">Classes</a></li>
    <li class="<?= ($currentType ?? '') === 'students' ? 'active' : '' ?>"><a href="view_edit.php?type=students">Students</a></li>
</ul>

<?php if (!empty($editRecord)): ?>
<div class="panel panel-warning">
    <div class="panel-heading"><h4 style="margin:0">Edit Record</h4></div>
    <div class="panel-body">
        <form method="POST" class="form-horizontal">
            <input type="hidden" name="type" value="<?= htmlspecialchars($currentType) ?>">
            <input type="hidden" name="id" value="<?= $editRecord['id'] ?>">

            <?php if ($currentType === 'teachers'): ?>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Name</label>
                    <div class="col-sm-9"><input type="text" name="name" class="form-control" value="<?= htmlspecialchars($editRecord['name']) ?>" required></div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Email</label>
                    <div class="col-sm-9"><input type="email" name="email" class="form-control" value="<?= htmlspecialchars($editRecord['email'] ?? '') ?>"></div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Phone</label>
                    <div class="col-sm-9"><input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($editRecord['phone'] ?? '') ?>"></div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Class</label>
                    <div class="col-sm-9">
                        <select name="class_id" class="form-control">
                            <option value="">-- None --</option>
                            <?php foreach ($classes as $c): ?>
                                <option value="<?= $c['id'] ?>" <?= (($editRecord['class_id'] ?? '') == $c['id']) ? 'selected' : '' ?>><?= htmlspecialchars($c['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

            <?php elseif ($currentType === 'classes'): ?>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Class Name</label>
                    <div class="col-sm-9"><input type="text" name="name" class="form-control" value="<?= htmlspecialchars($editRecord['name']) ?>" required></div>
                </div>

            <?php elseif ($currentType === 'students'): ?>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Name</label>
                    <div class="col-sm-9"><input type="text" name="name" class="form-control" value="<?= htmlspecialchars($editRecord['name']) ?>" required></div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Roll Number</label>
                    <div class="col-sm-9"><input type="text" name="roll_number" class="form-control" value="<?= htmlspecialchars($editRecord['roll_number']) ?>" required></div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Class</label>
                    <div class="col-sm-9">
                        <select name="class_id" class="form-control" required>
                            <?php foreach ($classes as $c): ?>
                                <option value="<?= $c['id'] ?>" <?= (($editRecord['class_id'] ?? '') == $c['id']) ? 'selected' : '' ?>><?= htmlspecialchars($c['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Parent Username</label>
                    <div class="col-sm-9"><input type="text" name="parent_username" class="form-control" value="<?= htmlspecialchars($editRecord['parent_username'] ?? '') ?>"></div>
                </div>
            <?php endif; ?>

            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-9">
                    <button type="submit" class="btn btn-success">Update</button>
                    <a href="view_edit.php?type=<?= htmlspecialchars($currentType) ?>" class="btn btn-default">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>
<?php endif; ?>

<div class="panel panel-default">
    <div class="panel-heading"><h4 style="margin:0"><?= ucfirst(htmlspecialchars($currentType ?? '')) ?></h4></div>
    <div class="panel-body">
        <?php if (empty($records)): ?>
            <p class="text-muted">No records found.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <?php if ($currentType === 'teachers'): ?>
                                <th>Name</th><th>Username</th><th>Email</th><th>Phone</th><th>Class</th>
                            <?php elseif ($currentType === 'classes'): ?>
                                <th>Class Name</th><th>Created</th>
                            <?php elseif ($currentType === 'students'): ?>
                                <th>Name</th><th>Roll No.</th><th>Class</th><th>Parent</th>
                            <?php endif; ?>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($records as $i => $r): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <?php if ($currentType === 'teachers'): ?>
                                <td><?= htmlspecialchars($r['name']) ?></td>
                                <td><?= htmlspecialchars($r['username']) ?></td>
                                <td><?= htmlspecialchars($r['email'] ?? '') ?></td>
                                <td><?= htmlspecialchars($r['phone'] ?? '') ?></td>
                                <td><?= htmlspecialchars($r['class_name'] ?? 'N/A') ?></td>
                            <?php elseif ($currentType === 'classes'): ?>
                                <td><?= htmlspecialchars($r['name']) ?></td>
                                <td><?= htmlspecialchars($r['created_at'] ?? '') ?></td>
                            <?php elseif ($currentType === 'students'): ?>
                                <td><?= htmlspecialchars($r['name']) ?></td>
                                <td><?= htmlspecialchars($r['roll_number'] ?? '') ?></td>
                                <td><?= htmlspecialchars($r['class_name'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($r['parent_username'] ?? 'N/A') ?></td>
                            <?php endif; ?>
                            <td>
                                <a href="view_edit.php?type=<?= htmlspecialchars($currentType) ?>&edit=<?= $r['id'] ?>" class="btn btn-info btn-xs">Edit</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
