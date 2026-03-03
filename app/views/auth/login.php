<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Android Attendance System</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>
    <div class="container">
        <div class="page-header">
            <h1>Android Attendance System</h1>
        </div>

        <div class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2" style="margin-top: 50px;">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <div class="panel-title">Login</div>
                </div>
                <div class="panel-body">
                    <?php if (($errorMessage ?? '') !== ''): ?>
                        <div class="alert alert-danger"><?php echo htmlspecialchars((string) $errorMessage); ?></div>
                    <?php endif; ?>
                    <form action="../api/login.php" method="POST">
                        <div class="form-group">
                            <label>Username:</label>
                            <input type="text" class="form-control" name="username" required>
                        </div>
                        <div class="form-group">
                            <label>Password:</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <input type="submit" value="Login" class="btn btn-info">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
