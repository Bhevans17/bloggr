<?php session_start(); ?>
<?php include "db.php"; ?>
<?php
if (!isset($_SESSION["user"])) {
    header("location: index.php");
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link href="style.css" type="text/css" rel="stylesheet">
    <title>Document</title>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <span class="navbar-brand">Hello <?php echo "<span class='text-success'> {$_SESSION['user']}</span>"; ?></span>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a href="dashboard.php" class="nav-link">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a href="friends.php" class="nav-link">Friends</a>
                    </li>
                    <li class="nav-item">
                        <a href="my_posts.php" class="nav-link">My Posts</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
            </div>
        </div>
    </nav>
    <div class="container-fluid m-0 p-0">
        <?php
        if (!empty($_POST["post_title"]) && !empty($_POST["post_desc"])) {
            $post_title = $_POST["post_title"];
            $post_desc = $_POST["post_desc"];

            $sql = "INSERT INTO `post`(`post_title`, `post_desc`, `username`) VALUES (?, ?, ?)";
            $statement = $pdo->prepare($sql);
            $statement->execute([htmlspecialchars($post_title), htmlspecialchars($post_desc), htmlspecialchars($_SESSION["user"])]);

            echo "<script>";
            echo "setTimeout(function () {
                let postCreated = document.getElementById('post-created');
                postCreated.style.display = 'none';
              }, 2000);
              ";
            echo "</script>";
            echo "<div id='post-created' class='alert alert-success'><div class='container'>Post Created</div></div>";
        }
        ?>
    </div>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-3 text-left">
                <h3 class="mb-4">Friends List</h3>
                <ul>
                    <li>Bob Joe</li>
                    <li>Bob Joe</li>
                    <li>Bob Joe</li>
                </ul>
            </div>
            <div class="col-md-6">
                <h3 class="mb-4">Create Post</h3>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

                    <div class="form-group">
                        <input id="post_title" name="post_title" type="text" class="form-control" placeholder="Post Title" required>
                    </div>
                    <div class="form-group">
                        <textarea class="form-control" id="post_desc" name="post_desc" cols="30" rows="5" placeholder="Post Description" required></textarea>
                    </div>
                    <input class="btn btn-success" type="submit">
                </form>
            </div>
            <div class="col-md-3 text-right">
                <h3>Friends List</h3>
                <ul>
                    <li>Bob Joe</li>
                    <li>Bob Joe</li>
                    <li>Bob Joe</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <h3 class="mb-4">My Recent Posts</h3>
            </div>
            <div class="col-md-6 offset-md-3">
                <div class="list-group">
                    <?php
                    $stmt = $pdo->prepare("SELECT * FROM post WHERE username=? ORDER BY created_at DESC LIMIT 5");
                    $stmt->execute([$_SESSION["user"]]);
                    $user = $stmt->fetchAll();

                    foreach ($user as $post) {
                        echo "<div class='list-group-item list-group-item-action'>";
                        echo "<div class='d-flex w-100 justify-content-between'>";
                        echo "<h5 class='mb-1'>{$post['post_title']}</h5>";
                        echo "<small>{$post['created_at']}</small>";
                        echo "</div>";
                        echo "<p class='mb-1'>{$post['post_desc']}</p>";
                        echo "<div class='d-flex justify-content-between mt-5'>";
                        echo "<a id='update-post' class='text-primary' data-toggle='modal' data-target='#exampleModal'>Update</a>";
                        echo "<a id='delete-post' class='text-danger' data-toggle='modal' data-target='#deleteModal'>Delete Post</a>";
                        echo "</div>";
                        echo "</div>";
                        // Update Modal
                        echo "<div class='modal fade' id='exampleModal' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
                                <div class='modal-dialog'>
                                    <div class='modal-content'>
                                    <div class='modal-header'>
                                        <h5 class='modal-title' id='exampleModalLabel'>Update Post</h5>
                                        <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                        <span aria-hidden='true'>&times;</span>
                                        </button>
                                    </div>
                                    <div class='modal-body'>
                                        <form>
                                            <div class='form-group'>
                                                <input class='form-control' id='update_post_title' name='update_post_title' type='text' placeholder='Update Post Title'>
                                            </div>
                                            <div class='form-group'>
                                                <textarea class='form-control' id='update_post_desc' name='update_post_desc' cols='30' rows='5' placeholder='Update Post Description'></textarea>
                                            </div>
                                            <button type='submit' class='btn btn-primary'>Update</button>
                                        </form>
                                    </div>
                                    </div>
                                    </div>
                                </div>";
                        // Delete Modal
                        echo "<div class='modal fade' id='deleteModal' tabindex='-1' role='dialog' aria-labelledby='deleteModalLabel' aria-hidden='true'>
                            <div class='modal-dialog'>
                                <div class='modal-content'>
                                <div class='modal-header'>
                                    <h5 class='modal-title' id='deleteModalLabel'>Delete Post</h5>
                                    <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                    <span aria-hidden='true'>&times;</span>
                                    </button>
                                </div>
                                <div class='modal-footer'>
                                    <button type='button' class='btn btn-secondary' data-dismiss='modal'>Close</button>
                                    <button type='button' class='btn btn-danger'>Delete</button>
                                </div>
                                </div>
                            </div>
                        </div>";
                    }
                    ?>
                </div>
            </div>

        </div>
    </div>
    <div class="container-fluid bg-dark text-white m-0 p-0 mt-5 bottom-sticky">
        <div class="container py-3">
            <p class="m-0">Copyright &copy; 2020.</p>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
    <script src="dashboard.js"></script>
</body>

</html>