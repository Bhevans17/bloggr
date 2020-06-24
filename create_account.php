<?php session_start(); ?>
<?php include "./config/db.php"; ?>
<?php include "./includes/header.php"; ?>
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
        <div class="col-md-4 offset-md-4 d-flex flex-column justify-content-center mt-5">
            <h1>Create Account</h1>
            <form class="mt-3" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="form-group">
                    <input id="username" name="username" type="text" class="form-control" placeholder="Enter Username" required>
                </div>
                <div class="form-group">
                    <input id="password" name="password" type="password" class="form-control" placeholder="Enter Password" required>
                </div>
                <input class=" btn btn-success btn-block" type="submit">
            </form>
        </div>
    </div>

</div>

<?php include "./includes/footer.php"; ?>