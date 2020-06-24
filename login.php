<?php session_start(); ?>
<?php include "./config/db.php"; ?>
<?php include "./includes/header.php"; ?>
<?php
if (!empty($_POST["username"]) && !empty($_POST["password"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $stmt = $pdo->prepare("SELECT username, password FROM user WHERE username=?");
    $stmt->execute([$username]);
    if ($user = $stmt->fetch()) {
        if (password_verify($password, $user["password"])) {
            $_SESSION["user"] = $username;
            header("location: dashboard.php");
        }
    } else {
        echo "<script>";
        echo "setTimeout(function () {
                let postCreated = document.getElementById('post-created');
                postCreated.style.display = 'none';
              }, 2000);
              ";
        echo "</script>";
        echo "<div id='post-created' class='alert alert-danger'><div class='container'>Wrong Credentials</div></div>";
    }
}

?>
<div class="container mt-5">
    <div class="row">
        <div class="col-md-4 offset-md-4 d-flex flex-column justify-content-center mt-5">
            <h1>Login</h1>
            <form class="mt-3" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="form-group">
                    <input id="username" name="username" type="text" class="form-control" placeholder="Enter Username" required>
                </div>
                <div class="form-group">
                    <input id="password" name="password" type="password" class="form-control" placeholder="Enter Password" required>
                </div>
                <input class="btn btn-success btn-block" type="submit">
            </form>
        </div>
    </div>
</div>

<?php include "./includes/footer.php"; ?>