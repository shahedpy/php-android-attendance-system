<div class="panel panel-default">
    <div class="panel-heading"><h4 style="margin:0">Parent Feedback</h4></div>
    <div class="panel-body">
        <?php if (empty($feedbacks)): ?>
            <p class="text-muted">No feedback received yet.</p>
        <?php else: ?>
            <?php foreach ($feedbacks as $fb): ?>
            <div class="panel panel-info">
                <div class="panel-heading">
                    <strong><?= htmlspecialchars($fb['subject']) ?></strong>
                    <span class="pull-right">
                        <em>by <?= htmlspecialchars($fb['parent_username']) ?></em>
                        &mdash; <?= htmlspecialchars($fb['created_at'] ?? '') ?>
                    </span>
                </div>
                <div class="panel-body">
                    <?= nl2br(htmlspecialchars($fb['message'])) ?>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
