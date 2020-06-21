<?php session_start(); ?>
<?php include "db.php"; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <title>Document</title>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">Bloggr</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="create_account.php">Create Account</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Login</a>
                    </li>
            </div>
        </div>
    </nav>
    <?php
    if (!empty($_POST["username"]) && !empty($_POST["password"])) {

        $username = $_POST["username"];
        $password = $_POST["password"];

        // Select usernames and check if it exists
        $stmt = $pdo->prepare("SELECT username, password FROM user WHERE username=?");
        $stmt->execute([$username]);
        if ($user = $stmt->fetch()) {
            // Clear Alert After 2 Seconds
            echo "<script>
                setTimeout(function () {
                    let userExists = document.getElementById('user-exists');
                    userExists.style.display = 'none';
              }, 2000);
              </script>";
            // Display Username Exists Alert
            echo "<div class='container-fluid m-0 p-0'>
                    <div id='user-exists' class='alert alert-danger'>
                        <div class='container'>
                            Username Exists
                        </div>
                    </div>
                </div>";
        } else {

            // Add username and password to database
            $hash_password = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO `user`(`username`, `password`) VALUES (?, ?)";
            $statement = $pdo->prepare($sql);
            $statement->execute([htmlspecialchars($username), htmlspecialchars($hash_password)]);
            $_SESSION["user"] = $username;
            header("location: dashboard.php");
        }
    } ?>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-4 offset-md-4">
                <h1>Create Account</h1>
                <form class="mt-3" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div class="form-group">
                        <label for="username">Enter Username</label>
                        <br>
                        <input id="username" name="username" type="text" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Enter Password</label>
                        <br>
                        <input id="password" name="password" type="password" class="form-control" required>
                    </div>
                    <input class="btn btn-success btn-block" type="submit">
                </form>
            </div>
        </div>

    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
</body>

</html>