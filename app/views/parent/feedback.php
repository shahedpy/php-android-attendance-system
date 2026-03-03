<div class="panel panel-primary">
    <div class="panel-heading"><h4 style="margin:0">Submit Feedback</h4></div>
    <div class="panel-body">
        <form method="POST" class="form-horizontal">
            <div class="form-group">
                <label class="col-sm-3 control-label">Subject *</label>
                <div class="col-sm-9"><input type="text" name="subject" class="form-control" required></div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">Message *</label>
                <div class="col-sm-9"><textarea name="message" class="form-control" rows="4" required></textarea></div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-9">
                    <button type="submit" class="btn btn-primary">Submit Feedback</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php if (!empty($feedbacks)): ?>
<div class="panel panel-default">
    <div class="panel-heading"><h4 style="margin:0">Your Previous Feedback</h4></div>
    <div class="panel-body">
        <?php foreach ($feedbacks as $fb): ?>
        <div class="panel panel-info">
            <div class="panel-heading">
                <strong><?= htmlspecialchars($fb['subject']) ?></strong>
                <span class="pull-right"><?= htmlspecialchars($fb['created_at'] ?? '') ?></span>
            </div>
            <div class="panel-body"><?= nl2br(htmlspecialchars($fb['message'])) ?></div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>
