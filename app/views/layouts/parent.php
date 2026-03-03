<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Parent') ?> - Attendance System</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>
<div class="container">
    <div class="jumbotron">
        <h1>Android Attendance System</h1>
        <p>Welcome, <?= htmlspecialchars($username ?? '') ?> (Parent)</p>
    </div>
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#parent-nav">
                    <span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span>
                </button>
            </div>
            <div class="collapse navbar-collapse" id="parent-nav">
                <ul class="nav navbar-nav">
                    <li class="<?= ($active ?? '') === 'dashboard' ? 'active' : '' ?>"><a href="index.php">Dashboard</a></li>
                    <li class="<?= ($active ?? '') === 'feedback' ? 'active' : '' ?>"><a href="feedback.php">Add Feedback</a></li>
                    <li class="<?= ($active ?? '') === 'report' ? 'active' : '' ?>"><a href="report.php">Student Report</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="../logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <?php if (!empty($success)): ?>
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <?= htmlspecialchars($success) ?>
        </div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>
    <?= $content ?>
</div>
</body>
</html>
